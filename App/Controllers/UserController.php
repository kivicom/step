<?php
namespace App\Controllers;

use App\Models\Image;
use App\Models\User;
use Delight\Auth\Auth;
use League\Plates\Engine;
use SimpleMail;

class UserController
{
    public $templates;
    public $user;
    private $auth;

    public function __construct(Engine $engine, Auth $auth, User $user)
    {
        $this->templates = $engine;
        $this->auth = $auth;
        $this->user = $user;
    }

    public function Profile()
     {
         if ($this->auth->isLoggedIn()) {
             $auth = true;
         }else {
             $auth = false;
             header('Location: /login');
         }

         $user = $this->user->getUserInfo('users', $this->auth->getUserId());

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

            if(!empty($_FILES)){
                $image = Image::uploadImage($_FILES['image'], $this->auth->getUserId(), 'cabinet/','user'.$this->auth->getUserId());
            }
            $result = $this->user->userUpdate('users', $this->auth->getUserId(), $_POST, $image);
            if($result){
                unset($_SESSION['user']['name']);
                unset($_SESSION['user']['email']);
                flash()->success('Профиль успешно обновлен');
                $_SESSION['user']['name'] = $_POST['username'];
                $_SESSION['user']['email'] = $_POST['email'];
                echo header('Location: /profile');
            }

        }

        if(isset($_POST['upd'])){
            $this->UpdPassword();
        }
        header('Location: /profile');
        exit();
    }

    public function Login()
    {
        if($_POST){
            if (isset($_POST['remember']) == 1) {
                $rememberDuration = (int) (60 * 60 * 24 * 365.25);
            }
            else {
                $rememberDuration = null;
            }

            try {
                $data = $_POST;
                $this->user->load($data);
                $key = array_keys($data);
                $rules = [
                    'required' => $key,
                    'email' => ['email'],
                    'lengthMin' => [
                        ['password', 6],
                    ]
                ];
                if(!$this->user->Validate($data, $rules)){
                    $this->user->getErrors();
                }
                $this->auth->login($_POST['email'], $_POST['password'], $rememberDuration);
                foreach ($_POST as $key => $item) {
                    if($key != 'password') $_SESSION['user'][$key] = $item;
                }

                $_SESSION['user']['id'] .= $this->auth->getUserId();
                $_SESSION['user']['name'] .= $this->auth->getUsername();

                flash()->success('Вы успешно авторизовались');
                header('Location: /profile');
                die();
            }
            catch (\Delight\Auth\InvalidEmailException $e) {
                $_SESSION['error']['email'] = 'Wrong email address';
            }
            catch (\Delight\Auth\InvalidPasswordException $e) {
                $_SESSION['error']['password'] = 'Wrong password';
            }
            catch (\Delight\Auth\EmailNotVerifiedException $e) {
                $_SESSION['error']['email'] = 'Email not verified';
            }
            catch (\Delight\Auth\TooManyRequestsException $e) {
                flash()->error('Too many requests');
            }
        }

        if ($this->auth->isLoggedIn()) {
            header('Location: /profile');
        }else {
            $auth = false;
        }

        echo $this->templates->render('login', ['auth' => $auth]);
    }

    public function Register()
    {
        if($_POST){
            $data = $_POST;
            $this->user->load($data);
            $key = array_keys($data);
            $rules = [
                'required' => $key,
                'email' => [
                    ['email'],
                ],
                'lengthMin' => [
                    ['password', 6],
                ],
                'equals' => [
                    ['password','password_confirmation'],
                ]
            ];

            if(!$this->user->Validate($data, $rules)){
                $this->user->getErrors();
            }else{
                try {
                    $userId = $this->auth->register($_POST['email'], $_POST['password'], $_POST['username'], function ($selector, $token) {
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
                    //flash()->error('Invalid email address');
                }
                catch (\Delight\Auth\InvalidPasswordException $e) {
                    //flash()->error('Invalid password');
                }
                catch (\Delight\Auth\UserAlreadyExistsException $e) {
                    flash()->error('User already exists');
                }
                catch (\Delight\Auth\TooManyRequestsException $e) {
                    flash()->error('Too many requests');
                }
            }
            header('Location: /register');
            die;
        }
        if ($this->auth->isLoggedIn()) {
            $auth = true;
        }else {
            $auth = false;
        }
        echo $this->templates->render('register', ['auth' => $auth]);
    }

    public function SendAfterRegister($email, $username, $selector, $token)
    {
        $subject = 'Информация с сайта http://marlinstep.loc';
        $message = '<a href="http://marlinstep.loc/verification?selector='. \urlencode($selector) . '&token='.\urlencode($token).'">Подвердить регистрацию</a>';
        SimpleMail::make()
            ->setTo($email, $username)
            ->setFrom('marlinstep@loc.com', 'Marlinstep')
            ->setSubject($subject)
            ->setMessage($message)
            ->setHtml()
            ->send();
    }

    public function verify_email()
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
        if($this->checkEmptyUpdPassword()){
            $data = $_POST;
            $this->user->load($data);
            $key = array_keys($data);
            $rules = [
                'required' => $key,
                'lengthMin' => [
                    ['password', 6],
                ]
            ];
            if(!$this->user->Validate($data, $rules)){
                $this->user->getErrors();
            }
            try {
                $this->auth->changePassword($_POST['current'], $_POST['newpassword']);

                flash()->success('Password has been changed');
            }
            catch (\Delight\Auth\NotLoggedInException $e) {
                flash()->error('Not logged in');
            }
            catch (\Delight\Auth\InvalidPasswordException $e) {
                $_SESSION['password_error'] = 'Invalid password(s)';
            }
            catch (\Delight\Auth\TooManyRequestsException $e) {
                flash()->error('Too many requests');
            }
        }
    }

    public function checkEmptyUpdPassword()
    {
        if(empty($_POST['current'])) {
            $_SESSION['error']['current'] = "Поле Current password не должно быть пустым";
            return false;
        }
        if(empty($_POST['newpassword'])) {
            $_SESSION['error']['newpassword'] = "Поле New password не должно быть пустым";
            return false;
        }
        if(empty($_POST['password_confirmation'])) {
            $_SESSION['error']['password_confirmation'] = "Поле Password confirmation не должно быть пустым";
            return false;
        }

        if($_POST['newpassword'] === $_POST['current']){
            $_SESSION['error']['newpassword'] = "Новый пароль не должен совпадать с прежним";
            return false;
        }

        if(!$this->checkPassword($_POST['newpassword'], $_POST['password_confirmation'])) {
            $_SESSION['error']['password_confirmation'] = "Поля 'New password' и 'Password confirmation' не совпадают";
            return false;
        }
        if(!$this->auth->reconfirmPassword($_POST['current'])) {
            $_SESSION['error']['current'] = "Текущий пароль не верный";
            return false;
        }
        return true;
    }

    public function checkPassword($pass, $pass_confirm)
    {
        if(($pass === $pass_confirm) && (strlen($pass) >= 6)){
            return true;
        }
        return false;
    }

    public function Logout()
    {
        $this->auth->logOut();
        unset($_SESSION['user']);
        return header('Location: /');
    }

}