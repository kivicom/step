<?php
namespace App\Controllers;

use App\Models\Image;
use App\Models\User;
use DB\Connection;
use Delight\Auth\Auth;
use League\Plates\Engine;
use SimpleMail;

class UserController
{
    private $pdo;
    public $templates;
    private $auth;

    public function __construct()
    {
        $this->templates = new Engine('../App/views');
        $this->pdo = Connection::make();
        $this->auth = new Auth($this->pdo);
    }

     public function Profile()
     {
         if ($this->auth->isLoggedIn()) {
             $auth = true;
         }else {
             $auth = false;
             header('Location: /login');
         }

         if(isset($_POST['upd'])){
             $this->UpdPassword();
         }

         $user = new User();
         $user = $user->getUserInfo('users', $this->auth->getUserId());

         echo $this->templates->render('profile', ['user' => $user, 'auth' => $auth]);
    }

    public function editProfile()
    {
        if(isset($_POST['edit_user'])){

            if(empty($_POST['username']) || empty($_POST['email'])){

                flash()->error('Заполните необходимые поля');
                echo header('Location: /profile');
                exit();
            }
            $user = new User();
            if(!empty($_FILES)){
                $image = Image::uploadImage($_FILES['image'], $this->auth->getUserId(), 'cabinet/','user'.$this->auth->getUserId());
            }
            $result = $user->userUpdate('users', $this->auth->getUserId(), $_POST, $image);
            if($result){
                unset($_SESSION['user']['name']);
                unset($_SESSION['user']['email']);
                flash()->success('Профиль успешно обновлен');
                $_SESSION['user']['name'] = $_POST['username'];
                $_SESSION['user']['email'] = $_POST['email'];
                echo header('Location: /profile');
            }

        }
        header('Location: /profile');
        exit();
    }

    public function Login()
    {
        if ($this->auth->isLoggedIn()) {
            header('Location: /profile');
        }else {
            $auth = false;
        }

        if($_POST){
            if (isset($_POST['remember']) == 1) {
                $rememberDuration = (int) (60 * 60 * 24 * 365.25);
            }
            else {
                $rememberDuration = null;
            }

            try {
                $this->auth->login($_POST['email'], md5($_POST['password']), $rememberDuration);
                foreach ($_POST as $key => $item) {
                    $_SESSION['user'][$key] = $item;
                }

                $_SESSION['user']['id'] .= $this->auth->getUserId();
                $_SESSION['user']['name'] .= $this->auth->getUsername();

                flash()->success('Вы успешно авторизовались');
                header('Location: /profile');
            }
            catch (\Delight\Auth\InvalidEmailException $e) {
                flash()->error('Wrong email address');
            }
            catch (\Delight\Auth\InvalidPasswordException $e) {
                flash()->error('Wrong password');
            }
            catch (\Delight\Auth\EmailNotVerifiedException $e) {
                flash()->error('Email not verified');
            }
            catch (\Delight\Auth\TooManyRequestsException $e) {
                flash()->error('Too many requests');
            }
        }
        echo $this->templates->render('login', ['auth' => $auth]);
    }

    public function Register()
    {
        if ($this->auth->isLoggedIn()) {
            $auth = true;
        }else {
            $auth = false;
        }

        if ($_POST){

            try {
                $userId = $this->auth->register($_POST['email'], md5($_POST['password']), $_POST['username'], function ($selector, $token) {
                    $this->SendAfterRegister($_POST['email'], $_POST['username'], $selector, $token);
                });

                if($userId){
                    flash()->success('Вы успешно зарегистрировались. Теперь можете авторизоваться.');
                    echo header('Location: /login');
                    exit();
                }else{
                    flash()->error('Ошибка решистрации. Пожалуйста, повторите позже.');
                    echo header('Location : /register');
                    exit();
                }
            }
            catch (\Delight\Auth\InvalidEmailException $e) {
                flash()->error('Invalid email address');
            }
            catch (\Delight\Auth\InvalidPasswordException $e) {
                flash()->error('Invalid password');
            }
            catch (\Delight\Auth\UserAlreadyExistsException $e) {
                flash()->error('User already exists');
            }
            catch (\Delight\Auth\TooManyRequestsException $e) {
                flash()->error('Too many requests');
            }
        }
        echo $this->templates->render('register', ['auth' => $auth]);
    }

    public function SendAfterRegister($email, $username, $selector, $token)
    {
        $subject = 'Информация с сайта http://marlinstep.loc';
        $url = 'http://marlinstep.loc/verify_email?selector=' . \urlencode($selector) . '&token=' . \urlencode($token);
        $message = '<a href="{$url}">Подвердить регистрацию</a>';
        SimpleMail::make()
            ->setTo($email, $username)
            ->setFrom('marlinstep@loc.com', 'Marlinstep')
            ->setSubject($subject)
            ->setMessage($message)
            ->setHtml()
            ->send();
    }

    public function emailVerification()
    {
        try {
            $this->auth->confirmEmail($_GET['selector'], $_GET['token']);
            //$this->auth->confirmEmail('RNB3u3xVs_l0lk6H', '67_ZmPcA5REFtl9_');

            flash()->success('Email address has been verified');
            header('Location: /');
        }
        catch (\Delight\Auth\InvalidSelectorTokenPairException $e) {
            flash()->error('Invalid token');
        }
        catch (\Delight\Auth\TokenExpiredException $e) {
            flash()->error('Token expired');
        }
        catch (\Delight\Auth\UserAlreadyExistsException $e) {
            flash()->error('Email address already exists');
        }
        catch (\Delight\Auth\TooManyRequestsException $e) {
            flash()->error('Too many requests');
        }

    }

    public function UpdPassword()
    {
        try {
            $this->auth->changePassword(md5($_POST['current']), md5($_POST['password']));

            flash()->success('Password has been changed');
        }
        catch (\Delight\Auth\NotLoggedInException $e) {
            flash()->error('Not logged in');
        }
        catch (\Delight\Auth\InvalidPasswordException $e) {
            flash()->error('Invalid password(s)');
        }
        catch (\Delight\Auth\TooManyRequestsException $e) {
            flash()->error('Too many requests');
        }
    }

    public function Logout()
    {
        $this->auth->logOut();
        unset($_SESSION['user']);
        return header('Location: /');
    }

}