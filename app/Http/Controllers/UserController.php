<?php

namespace App\Http\Controllers;

use App\Mailer;
use InvalidArgumentException;
use RuntimeException;
use Gregwar\Image\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;

class UserController extends Controller
{

    private $per_page = 12;

    public function joinGroup (Request $request, $id_group)
    {
        if(!app()->mdb->has('user_group', [
            'id_user' => $request->attributes['user']['id_user'],
            'id_group' => $id_group,
        ]))
            app()->mdb->insert('user_group', [
                'id_user' => $request->attributes['user']['id_user'],
                'id_group' => $id_group,
            ]);
    }

    public function leaveGroup (Request $request, $id_group)
    {
        app()->mdb->delete('user_group', [
            'id_user' => $request->attributes['user']['id_user'],
            'id_group' => $id_group,
        ]);
    }

    public function index (Request $request)
    {
        return app()->mdb->select('user', '*');
    }

    public function get ($id_user)
    {
        return app()->mdb->get('user', '*', [
            'id_user' => $id_user
        ]);
    }

    public function delete ($id_user)
    {
        return [
            'status' => app()->mdb->delete('user', [
                'id_user' => $id_user
            ])
        ];
    }

    private function validatePass($pass)
    {

        if(mb_strlen($pass) < 6)
            throw new InvalidArgumentException("Длинна пароля должна быть более 6 символов.");
        if(mb_strlen($pass) > 32)
            throw new InvalidArgumentException("Длинна пароля должна быть менее 32 символов.");
        if(preg_match("/'\{\}\[\]\(\)\`\"/", $pass))
            throw new InvalidArgumentException("Некоторые символы недопустимы.");
    }
    private function validateLogin($login)
    {
        if(preg_match("/'\{\}\[\]\(\)\`\"/", $login))
            throw new InvalidArgumentException("Некоторые символы недопустимы.");
        if(mb_strlen($login) < 4)
            throw new InvalidArgumentException("Длинна логина должна быть более 4 символов.");
        if(mb_strlen($login) > 32)
            throw new InvalidArgumentException("Длинна логина должна быть менее 32 символов.");
    }

    public function chPass(Request $request)
    {

        $pass = $request->input('pass');
        $confirm = $request->input('confirm');
        $user = $request->input('user');

        $this->validatePass($pass);

        if($confirm != $pass)
            throw new InvalidArgumentException("Повторите пароль верно.");
        if(empty($confirm))
            throw new InvalidArgumentException("Повторите пароль.");

        app()->mdb->update('user', [
            'pass' => $this->hashPassword($pass)
        ], [
            'id_user' => $request->attributes['user']['id_user']
        ]);
    }

    public function chLogin(Request $request)
    {

        $login = $request->input('login');

        $this->validateLogin($login);

        app()->mdb->update('user', [
            'login' => $login
        ], [
            'id_user' => $request->attributes['user']['id_user']
        ]);
    }

    public function chPhone(Request $request)
    {

        $phone = $request->input('phone');

        if(!preg_match("/^(\+([0-9]{1,2}) (\([0-9]{3}\)) ([0-9]{3})\-([0-9]{2})\-([0-9]{2}))$/s", $phone))
            throw new InvalidArgumentException("Неверный формат номера.");

        app()->mdb->update('user', [
            'phone' => $phone
        ], [
            'id_user' => $request->attributes['user']['id_user']
        ]);
    }

    public function chPersonal(Request $request)
    {

        $name = $request->input('name');

        if(empty($name))
            throw new InvalidArgumentException('Введите ФИО.');

        app()->mdb->update('user', [
            'name' => $name
        ], [
            'id_user' => $request->attributes['user']['id_user']
        ]);
    }

    public function recoverPass(Request $request) {

        $email = $request->input('email');

        $id_user = app()->mdb->get('user', 'id_user', [
            'email' => $email
        ]);

        if(empty($id_user))
            throw new InvalidArgumentException("User not found.");

        $code = str_random(8);

        app()->mdb->insert('user_recover', [
            'id_user' => $id_user,
            'code' => $code,
            'date' => time()
        ]);

        $subject = "MDlinks password recovery.";
        if(!Mailer::send($email, $subject, [
            'link' => $_ENV['APP_URL'] . "/#/recover/$code",
        ])) {
            app()->mdb->delete('user_recover', [
                'id_user' => $id_user
            ]);
            throw new RuntimeException('Error sending mail.');
        }
    }

    public function changePassKey(Request $request) {

        $pass = $request->input('pass');
        $confirm = $request->input('confirm');
        $code = $request->input('code');

        $id_user = app()->mdb->get('user_recover', 'id_user', [
            'code' => $code
        ]);

        if(empty($id_user))
            throw new InvalidArgumentException("Invalid key.");

        $this->validatePass($pass);

        if($confirm != $pass)
            throw new InvalidArgumentException("Повторите пароль верно.");
        if(empty($confirm))
            throw new InvalidArgumentException("Повторите пароль.");

        $login = app()->mdb->get('user', 'login', [
            'id_user' => $id_user
        ]);

        app()->mdb->update('user', [
            'pass' => $this->hashPassword($pass)
        ], [
            'id_user' => $id_user
        ]);

        app()->mdb->delete('user_recover', [
            'id_user' => $id_user
        ]);
    }

    public function registrate(Request $request)
    {

        $login = $request->input('login');
        $email = $request->input('email');
        $name = $request->input('name');
        $pass = $request->input('pass');
        $date = time();
        $confirm = $request->input('confirm');

        if(preg_match('/[^\w\ ]/', $name))
            throw new InvalidArgumentException("Name can't include special chars");

        if(!preg_match("/^([a-z0-9_\.\-]){1,}@([a-z0-9\.\-]){1,}\.([a-z0-9\.\-]){1,}$/is", $email))
            throw new InvalidArgumentException("Неверный формат почты.");
        if(empty($name))
            throw new InvalidArgumentException("Введите ФИО.");

        if(app()->mdb->has('user', [
            'email' => $email
        ]))
            throw new InvalidArgumentException("Эта почта уже занята.");

        if(app()->mdb->has('user', [
            'login' => $login
        ]))
            throw new InvalidArgumentException("Этот логин уже занят.");

        $this->validatePass($pass);
        $this->validateLogin($login);

        // if($confirm != $pass)
        //     throw new InvalidArgumentException("Повторите пароль верно.");
        // if(empty($confirm))
        //     throw new InvalidArgumentException("Повторите пароль.");

        app()->mdb->action(function() use($login, $pass, $email, $date, $name) {

            app()->mdb->insert('user', [
                'name' => $name,
                'login' => $login,
                'email' => $email,
                'pass' => $this->hashPassword($pass),
                'date' => $date,
                'role' => 'usr',
                'verified' => 0,
            ]);

            app()->mdb->insert('user_info', []);

            $id_user = app()->mdb->id();
            $code = str_random(8);
            app()->mdb->insert('user_verify', [
                'code' => $code,
                'id_user' => $id_user
            ]);

            $subject = "MDLinks registration.";
            if(!Mailer::send($email, $subject, [
                'link' => $_ENV['APP_URL'] . "/user/verify/$code",
            ])) {
                throw new RuntimeException('Error sending mail.');
            }

        });
    }

    public function verify($code)
    {

        $id_user = app()->mdb->get('user_verify', 'id_user', [
            'code' => $code
        ]);

        app()->mdb->update('user', [
            'verified' => 1
        ], [
            'id_user' => $id_user
        ]);

        app()->mdb->delete('user_verify', [
            'id_user' => $id_user
        ]);

        echo "OKAY";
    }

    public function stayLogged(Request $request)
    {

        $login = $request->input('login');
        $pass = $request->input('pass');

        $user = $this->login($login, $pass);

        if($user === false)
            throw new InvalidArgumentException("Неверный логин или пароль.");

        $cookie = Crypt::encrypt(json_encode([
            'login' => $login,
            'pass' => $pass,
        ], JSON_UNESCAPED_UNICODE));

        if(!setcookie('auth', $cookie, 0, "/"))
            throw new RuntimeException("Cookie error.");
    }

    public function hashPassword($pass)
    {
        return Hash::make($pass);
    }

    public function getUserInfo($id_user = 0)
    {
        if(!$id_user) {

            $cookie = empty($_COOKIE['auth']) ? [] : json_decode(Crypt::decrypt($_COOKIE['auth']), true);

            $user = app()->mdb->get('user', [
                '[>]user_info' => 'id_user'
            ], '*', [
                'login' => $cookie['login']
            ]);
            $id_user = $user['id_user'];
        } else {
            $user = app()->mdb->get('user', [
                '[>]user_info' => 'id_user'
            ], '*', [
                'id_user' => $id_user
            ]);
        }

        $user['qual'] = app()->mdb->select('user_qual', '*', [
            'id_user' => $id_user
        ]);

        $user['skills'] = app()->mdb->select('user_skills', '*', [
            'id_user' => $id_user
        ]);

        return array_diff_key($user, [
            'pass' => null,
            'login' => null,
            'role' => null
        ]);
    }

    public function setImage(Request $request)
    {
        $file = Image::open($request->file('image')->getPathName());
        $min_file = Image::open($request->file('image')->getPathName());
        $full_name = str_random(16) . '.jpg';
        $min_name = str_random(16) . '.jpg';
        $path = './../storage/app/user/' . $request->attributes['user']['id_user'] . '/';
        $url = '/storage/user/' . $request->attributes['user']['id_user'] . '/';

        $images = app()->mdb->get('user', [
            '[>]user_info' => 'id_user'
        ], [
            'img', 'full_img'
        ], [
            'id_user' => $request->attributes['user']['id_user']
        ]);

        if(!empty($images['img']) && !empty($images['full_img'])) {
            $images['img'] = explode('/', $images['img']);
            $images['full_img'] = explode('/', $images['full_img']);

            unlink($path . end($images['img']));
            unlink($path . end($images['full_img']));
        }

        $min_file
            ->fill(0xffffff)
            ->cropResize(70, 70)
            ->save($path . $min_name, 'jpg');

        $file
            ->fill(0xffffff)
            ->cropResize(320, 320)
            ->save($path . $full_name, 'jpg');

        app()->mdb->update('user_info', [
            'full_img' => $url . $full_name
        ], [
            'id_user' => $request->attributes['user']['id_user']
        ]);

        app()->mdb->update('user', [
            'img' => $url . $min_name
        ], [
            'id_user' => $request->attributes['user']['id_user']
        ]);

    }

    public function login($login, $pass)
    {

        $user = app()->mdb->get('user', '*', [
            'login' => $login,
            'verified' => 1
        ]);

        if(!empty($user) && Hash::check($pass, $user['pass'])) return $user;
        else return false;
    }

}
