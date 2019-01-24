<?php

namespace App;

use InvalidArgumentException;
use RuntimeException;
use Medoo\Medoo;

class Message
{


  public $message_per_chunk = 30;


  public function isInChat($id_user, $id_chat)
  {
    $chat = app()->mdb->get('chat', '*', [ 'id_chat' => $id_chat ]);

    if(!$chat)
      throw new InvalidArgumentException('No chat found', 404);

    if($chat['is_group']) {
      if(!app()->mdb->has('user_group', [
        'id_chat' => $id_chat,
        'id_user' => $id_user,
      ])) {
        throw new InvalidArgumentException('You have no permission to write to this chat', 401);
      }
    } else {
      if(!app()->mdb->has('user_chat', [
        'id_chat' => $id_chat,
        'OR' => [
          'id_user' => $id_user,
          'id_partner' => $id_user,
        ]
      ])) {
        throw new InvalidArgumentException('You have no permission to write to this chat', 401);
      }
    }
  }


  public function getMessages($where = [])
  {
    $joins = [
      '[>]message_file' => 'id_message',
      '[>]message_paint' => 'id_message',
    ];
    $cols = [
      'id_message',
      'id_addresser',
      'id_chat',
      'id_reply',
      'content',
      'date',
      'data_uri',
      'message.type',
      'files' => Medoo::raw('COUNT(message_file.id_file)')
    ];

    $where['GROUP'] = 'id_message';
    $where['ORDER'] = [ 'id_message' => 'DESC' ];
    $messages = app()->mdb->select('message', $joins, $cols, $where);

    sort($messages);

    foreach ($messages as $i => $message) {
      if(!$message['files']) {
        $messages[$i]['files'] = false;
      } else {
        $messages[$i]['files'] = app()->mdb->select('message_file', '*', [
          'id_message' =>  $message['id_message']
        ]);
      }

      if($message['type'] === 'fwd') {
        $messages[$i] = app()->mdb->get('message', $joins, $cols, [
          'id_message' => $message['id_reply'],
        ]);

        if(!$messages[$i]['files']) {
          $messages[$i]['files'] = false;
        } else {
          $messages[$i]['files'] = app()->mdb->select('message_file', '*', [
            'id_message' => $message['id_reply']
          ]);
        }

        $messages[$i]['from'] = [
          'id_user' => $message['id_addresser'],
          'name' => app()->mdb->get('user', 'name', [
            'id_user' => $messages[$i]['id_addresser'],
          ])
        ];

        $messages[$i]['id_addresser'] = $message['id_addresser'];
        $messages[$i]['type'] = 'fwd';
        $messages[$i]['date'] = $message['date'];
        $messages[$i]['id_message'] = $message['id_message'];
        $messages[$i]['id_reply'] = $message['id_reply'];
      }
      else if($message['type'] === 'rpl') {
        $messages[$i]['reply_source'] = app()->mdb->get('message', [
          'content',
          'id_reply',
        ], [
          'id_message' => $message['id_reply'],
        ]);

        if($messages[$i]['reply_source']['id_reply']) {
          $messages[$i]['reply_source'] = app()->mdb->get('message', 'content', [
            'id_message' => $messages[$i]['reply_source']['id_reply'],
          ]);
        } else {
          $messages[$i]['reply_source'] = $messages[$i]['reply_source']['content'];
        }
      }
    }

    return $messages;
  }


  public function send(
    $id_addresser,
    $id_chat,
    $content = '',
    $files = [],
    $paint = null,
    $type = 'smp',
    $id_reply = null
  ) {
    if(!is_array($files))
      throw new InvalidArgumentException('Files must be an array');

    app()->mdb->action(function () use ($paint, $files, $id_chat, $id_addresser, $content, $type, $id_reply) {
      app()->mdb->insert('message', [
        'content' => $content,
        'type' => $type,
        'date' => time(),
        'id_addresser' => $id_addresser,
        'id_chat' => $id_chat,
        'id_reply' => $id_reply,
      ]);

      $id_message = app()->mdb->id();

      (new Notification)->chatNote($id_chat, $id_addresser);

      if($paint != null) {
        app()->mdb->insert('message_paint', [
          'id_message' => $id_message,
          'data_uri' => $paint,
        ]);
      }

      if(!empty($files)) $this->transferFiles($id_message, $files);

    });
  }


  public function transferFiles($id_message, $files)
  {
    $tmp_files = app()->mdb->select('file_tmp', '*', [
      'id_file' => $files
    ]);

    if(!mkdir('./../storage/app/message/' . $id_message))
      throw new RuntimeException('Could not make message dir');

    foreach ($tmp_files as $i => $file) {
      $query[] = [
        'name' => $file['original_name'],
        'id_message' => $id_message,
        'url' => '/storage/message/' . $id_message . '/' . $i,
      ];
      try {
        rename(
          './../storage/app/tmp/' . $file['tmp_name'],
          './../storage/app/message/' . $id_message . '/' . $i
        );
      } catch (Exception $e) {
        foreach ($tmp_files as $file) {
          @unlink('./../storage/app/message/' . $id_message . '/' . $i);
        }
        throw $e;
      }
    }

    app()->mdb->delete('file_tmp', [
      'id_file' => $files
    ]);

    app()->mdb->insert('message_file', $query);
  }
}
