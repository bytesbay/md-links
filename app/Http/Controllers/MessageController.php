<?php

namespace App\Http\Controllers;

use InvalidArgumentException;
use RuntimeException;
use Illuminate\Http\Request;
use Medoo\Medoo;
use App\Message;
use App\Notification;

class MessageController extends Controller
{


  public function forwardMessages(Request $request, $id_chat)
  {
    $obj = new Message();
    $ids_message = $request->input('ids_message');
    $ids_message = is_array($ids_message) ? $ids_message : [ $ids_message ];

    foreach ($ids_message as $id_message) {
      $message = app()->mdb->get('message', [
        'type',
        'id_chat',
        'id_reply',
        'id_message',
      ], [
        'id_message' => $id_message
      ]);

      if(!$message)
        throw new InvalidArgumentException('Message not found', 404);

      $obj->isInChat($request->attributes['user']['id_user'], $message['id_chat']);

      while(true) {
        if($message['type'] !== 'fwd') break;
        $message = app()->mdb->get('message', [
          'type',
          'id_message',
        ], [
          'id_message' => $message['id_reply']
        ]);
      }

      $obj->send(
        $request->attributes['user']['id_user'],
        $id_chat,
        '',
        [],
        null,
        'fwd',
        $message['id_message']
      );
    }
  }


  public function deleteMessages(Request $request, $id_chat) {

    $ids_message = $request->input('ids_message');
    $ids_message = is_array($ids_message) ? $ids_message : [ $ids_message ];

    foreach ($ids_message as $id_message) {
      if(!app()->mdb->has('message', [
        'id_message' => $id_message,
        'id_chat' => $id_chat,
      ]))
        throw new InvalidArgumentException('Message not found', 404);

      if(!app()->mdb->has('message', [
        'id_message' => $id_message,
        'id_addresser' => $request->attributes['user']['id_user']
      ]))
        throw new InvalidArgumentException('Message is not yours', 401);

      app()->mdb->delete('message', [
        'id_message' => $id_message,
      ]);
    }
  }


  public function searchMessages(Request $request, $id_chat, $query)
  {
    $obj = new Message();
    $count = app()->mdb->count('message', [
      'id_chat' => $id_chat,
    ]);

    $chunk = 0;
    $id_message = 0;
    for ($i = 0; $i < $count; $i += $obj->message_per_chunk) {
      $to = $i + $obj->message_per_chunk;
      $from = $i;

      $found = app()->mdb->query("
        SELECT id_message FROM (
          SELECT id_message, content FROM message
          ORDER BY id_message DESC
          LIMIT $to OFFSET $from
        ) as tmp
        WHERE tmp.content LIKE '%$query%'
      ")->fetchAll();

      if(!$found) continue;

      $messages = app()->mdb->select('message', [
        'id_message',
        'content',
      ], [
        'id_chat' => $id_chat,
        'ORDER' => [ 'id_message' => 'DESC' ],
        'LIMIT' => [ $from, $to ],
      ]);

      foreach ($messages as $j => $message) {
        if(preg_match("/$query/i", $message['content'])) {
          $id_message = $message['id_message'];
          $chunk = $i;
          break;
        }
      }
      if($chunk) { break; }
    }

    if($id_message)
      return [
        'messages' => $obj->getMessages([
          'id_chat' => $id_chat,
          'LIMIT' => [ $chunk, $chunk + $obj->message_per_chunk ],
        ]),
        'id_message' => $id_message
      ];
    else
      return [
        'messages' => [],
        'id_message' => 0,
      ];
  }


  public function index(Request $request, $id_chat)
  {
    $obj = new Message();
    $obj->isInChat($request->attributes['user']['id_user'], $id_chat);
    app()->mdb->delete('notification', [
      'additional' => "{\"id_chat\":$id_chat}",
      'id_user' => $request->attributes['user']['id_user'],
      'type' => 'msg',
    ]);
    return $obj->getMessages([
      'id_chat' => $id_chat,
      'LIMIT' => $obj->message_per_chunk
    ]);
  }


  public function searchChats(Request $request, $query)
  {
    $id_user = $request->attributes['user']['id_user'];

    $users = app()->mdb->select('user', [
      'chat' => [
        'id_user',
        'name',
        'img',
      ]
    ], [
      'OR' => [
        'name[~]' => "%$query%",
        'login[~]' => "%$query%",
      ]
    ]);

    return array_merge($users);
  }


  // Сделать позже id_chat, и посылать сообщения не напрямую собеседнику, а чату.
  public function getUserChats(Request $request)
  {
    $id_user = $request->attributes['user']['id_user'];

    $chats = app()->mdb->select('user_chat', '*', [
      'OR' => [
        'id_user' => $id_user,
        'id_partner' => $id_user,
      ]
    ]);

    $messages = [];
    foreach ($chats as $i => $chat) {
      $messages[] = app()->mdb->get('message', [
        'content',
        'id_message',
        'id_addresser',
        'id_reply',
        'type',
        'date',
      ], [
        'id_chat' => $chat['id_chat'],
        'ORDER' => [ 'id_message' => 'DESC' ],
      ]);
      $messages[$i]['chat'] = app()->mdb->get('user', [
        'id_user',
        'name',
        'img',
      ], [
        'id_user' => $chat['id_user'] == $id_user ? $chat['id_partner'] : $chat['id_user']
      ]);
      $messages[$i]['new_messages'] = app()->mdb->count('notification', [
        'additional' => "{\"id_chat\":{$chat['id_chat']}}",
        'id_user' => $id_user,
        'type' => 'msg',
      ]);
      $messages[$i]['chat']['is_group'] = false;
      $messages[$i]['chat']['id_chat'] = $chat['id_chat'];
    }

    $messages = array_merge($messages, (new GroupController)->index($request));

    return $messages;
  }

  // Сделать в колижн сохранение предыдущей скорости и работать только по одному объкту сразу


  public function getUserFriends(Request $request)
  {
    $id_user = $request->attributes['user']['id_user'];

    $chats = app()->mdb->select('user_chat', '*', [
      'OR' => [
        'id_user' => $id_user,
        'id_partner' => $id_user,
      ]
    ]);

    $messages = [];
    foreach ($chats as $i => $chat) {
      $messages[$i] = app()->mdb->get('user', [
        'id_user',
        'name',
        'img',
      ], [
        'id_user' => $chat['id_user'] == $id_user ? $chat['id_partner'] : $chat['id_user']
      ]);
    }

    return $messages;
  }


  public function startChat(Request $request) {
    app()->mdb->action(function() use($request) {
      app()->mdb->insert('chat', [
        'is_group' => 0,
        'date' => time(),
      ]);
      $id_chat = app()->mdb->id();
      if(!app()->mdb->has('user_chat', [
        'id_chat' => $id_chat,
        'id_user' => $request->attributes['user']['id_user'],
        'id_partner' => $request->input('id_user'),
      ]))
        app()->mdb->insert('user_chat', [
          'id_chat' => $id_chat,
          'id_user' => $request->attributes['user']['id_user'],
          'id_partner' => $request->input('id_user'),
        ]);
      else
        throw new InvalidArgumentException('You already started chat', 401);
    });
  }


  public function store(Request $request, $id_chat)
  {

    $id_reply = $request->input('id_reply');

    if($id_reply) {
      if(!app()->mdb->has('message', [
        'id_message' => $id_reply,
        'id_chat' => $id_chat,
      ]))
        throw new InvalidArgumentException('Message not found', 404);
    }

    $files = $request->has('files') ? $request->input('files') : [];
    $paint = $request->has('paint') ? $request->input('paint') : null;

    (new Message)->send(
      $request->attributes['user']['id_user'],
      $id_chat,
      $request->input('content'),
      $files,
      $paint,
      $id_reply ? 'rpl' : 'smp',
      $id_reply
    );
  }
}
