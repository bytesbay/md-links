<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
  readfile('../resources/views/app.html');
});

$router->post('/user/registrate', 'UserController@registrate');
$router->post('/user/login', 'UserController@stayLogged');
$router->post('/user/recover', 'UserController@recoverPass');
$router->post('/user/new-pass', 'UserController@changePassKey');
$router->get('/user/verify/{code}', 'UserController@verify');
$router->get('/user/info[/{id_user}]', 'UserController@getUserInfo');
$router->post('/test', 'PatientController@parserPDF');

$router->group([ 'middleware' => 'user' ], function () use ($router) {

  $router->post('/file', 'FileController@attachFile');

  // User routes
  $router->get('/user', 'UserController@index');
  $router->post('/user/image', 'UserController@setImage');
  $router->get('/user/group', 'GroupController@index');
  $router->get('/user/friends', 'MessageController@getUserFriends');
  $router->get('/user/{id}', 'UserController@get');
  $router->delete('/user/{id}', 'UserController@delete');
  $router->put('/user/group/{id-group}', 'UserController@joinGroup');
  $router->delete('/user/group/{id-group}', 'UserController@leaveGroup');


  // Patient
  $router->get('/flows/{type}', 'PatientController@getFlows');
  $router->get('/patients', 'PatientController@index');
  $router->post('/patient', 'PatientController@store');
  $router->post('/flow', 'PatientController@createFlow');
  $router->group([ 'prefix' => '/flow/{id_flow}', 'middleware' => 'flow' ], function () use ($router) {
    $router->put('/check', 'PatientController@check');
    $router->put('/invite-doctor/{id_doctor}', 'PatientController@addDoctorToFlow');
    $router->get('/', 'PatientController@getFlow');
    $router->get('/comments', 'PatientController@getComments');
    $router->post('/comment', 'PatientController@addComment');
    // $router->get('/{id_flow}', 'PatientController@getFlow');
  });


  // Chat routes
  $router->get('/chat/search/{query}', 'MessageController@searchChats'); // +
  $router->post('/chat', 'MessageController@startChat'); // +
  $router->get('/chats', 'MessageController@getUserChats'); // +
  $router->group([ 'prefix' => '/chat/{id_chat}', 'middleware' => 'chat' ], function () use ($router) {
    $router->get('/search/{query}', 'MessageController@searchMessages'); // +
    $router->get('/messages', 'MessageController@index'); // +
    $router->put('/messages/forward', 'MessageController@forwardMessages'); // +
    $router->post('/message', 'MessageController@store'); // +
    $router->post('/messages/delete', 'MessageController@deleteMessages'); // +
  });


  // Groups
  $router->get('/groups', 'GroupController@index');
  $router->post('/group', 'GroupController@store');
  $router->post('/group/search/{query}', 'GroupController@searchGroups');
  $router->group([ 'prefix' => '/group/{id_chat}', 'middleware' => 'chat' ], function () use ($router) {
    $router->get('/users', 'GroupController@getGroupUsers');
    $router->delete('/', 'GroupController@delete');
    $router->put('/invite/{id_user}', 'GroupController@inviteUser');
    $router->delete('/remove/{id_user}', 'GroupController@removeUser');
  });
});
