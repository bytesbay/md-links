<?php

namespace App\Http\Controllers;

use InvalidArgumentException;
use Illuminate\Http\Request;
use App\Message;

class GroupController extends Controller
{
  // $request->attributes['user']['id_user']

  public function getGroupUsers(Request $request, $id_chat)
  {
    return app()->mdb->select('user_group', [
      '[>]user' => 'id_user'
    ], [
      'name',
      'user.id_user',
      'img',
    ], [
      'id_chat' => $id_chat
    ]);
  }


  public function index(Request $request)
  {
    $groups = app()->mdb->select('user_group', [
      '[>]public' => 'id_chat',
      '[>]chat' => 'id_chat',
    ], '*', [
      'id_user' => $request->attributes['user']['id_user']
    ]);

    $messages = [];
    foreach ($groups as $i => $group) {
      $messages[] = app()->mdb->get('message', [
        'id_message',
        'id_addresser',
        'id_reply',
        'content',
        'type',
        'date',
      ], [
        'id_chat' => $group['id_chat'],
        'ORDER' => [ 'id_message' => 'DESC' ],
      ]);
      $messages[$i]['new_messages'] = app()->mdb->count('notification', [
        'additional' => "{\"id_chat\":{$group['id_chat']}}",
        'id_user' => $request->attributes['user']['id_user'],
        'type' => 'msg',
      ]);
      $messages[$i]['chat'] = $group;
    }

    return $messages;
  }


  public function searchGroups(Request $request, $query)
  {
    return app()->mdb->select('public', [
      'chat' => [
        'id_chat',
        'name',
        'img',
      ]
    ], [
      'name[~]' => "%$query%"
    ]);
  }


  public function store(Request $request)
  {
    if(strlen($request->input('name')) > 32)
      throw new InvalidArgumentException('Group name length must be shorter than 32');

    app()->mdb->action(function() use($request) {
      app()->mdb->insert('chat', [
        'is_group' => 1,
        'date' => time(),
      ]);

      $id_chat = app()->mdb->id();
      app()->mdb->insert('public', [
        'id_chat' => $id_chat,
        'id_owner' => $request->attributes['user']['id_user'],
        'name' => $request->input('name'),
      ]);

      app()->mdb->insert('user_group', [
        'id_user' => $request->attributes['user']['id_user'],
        'id_chat' => $id_chat,
      ]);
    });
  }


  public function inviteUser(Request $request, $id_chat, $id_user)
  {

    if(!app()->mdb->has('user', [
      'id_user' => $id_user,
    ]))
      throw new InvalidArgumentException("User {$request->input('login')} not found", 404);

    if(!app()->mdb->has('user_group', [
      'id_user' => $id_user,
      'id_chat' => $id_chat,
    ]))
      app()->mdb->insert('user_group', [
        'id_user' => $id_user,
        'id_chat' => $id_chat,
      ]);
    else
      throw new InvalidArgumentException('User is already in the group', 400);
  }


  public function removeUser(Request $request, $id_chat, $id_user)
  {
    app()->mdb->delete('user_group', [
      'id_user' => $id_user,
      'id_chat' => $id_chat,
    ]);
  }

}
