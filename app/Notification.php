<?php

namespace App;

class Notification
{


  public function newNotification($id_user, $type, $additional = [])
  {
    app()->mdb->insert('notification', [
      'id_user' => $id_user,
      'type' => $type,
      'additional[JSON]' => empty($additional) ? '' : $additional,
      'date' => time(),
    ]);
  }

  public function chatNote($id_chat, $id_addresser, $additional = [])
  {
    $chat = app()->mdb->get('chat', '*', [
      'id_chat' => $id_chat
    ]);

    if($chat['is_group']) {
      $users = app()->mdb->select('user_group', '*', [
        'id_chat' => $id_chat,
        'id_user[!]' => $id_addresser,
      ]);
      foreach ($users as $user) {
        $this->newNotification($user['id_user'], 'msg', [ 'id_chat' => intval($id_chat) ]);
      }
    } else {
      $tmp_chat = app()->mdb->get('user_chat', '*', [
        'id_chat' => $id_chat,
      ]);
      $this->newNotification(
        $tmp_chat['id_user'] == $id_addresser ? $tmp_chat['id_partner'] : $tmp_chat['id_user'],
        'msg',
        [ 'id_chat' => intval($id_chat) ]
      );
    }
  }
}
