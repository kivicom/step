<?php

class UserController
{
    protected $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function Index()
    {
        if(isset($_SESSION['user'])){
            return header('Location: /');
        }
        if($_POST){
            $this->Login();
        }
        return include '../App/views/login.php';
    }

    public function Profile()
    {

        if(!isset($_SESSION['user']['id'])){
            echo header('Location: /login');
            exit();
        }

        $db = new User($this->pdo);
        $user = $db->getUserId($_SESSION['user']['id']);

        //проверяем обязательные поля на пустоту
        if(isset($_POST['name']) || isset($_POST['email'])){
            if(empty($_POST['name']) || empty($_POST['email'])){
                $_SESSION['field_err'] = 'Заполните необходимые поля';
                echo header('Location: /profile');
                exit();
            }
            //Проверяем, отличается ли новый емейл от того, что хранится в базе
            if($_POST['email'] !== $_SESSION['user']['email']){

                //Если был введён новый емейл - проверяем, соответствует ли он определённому формату
                if(!checkEmail($_POST['email'])){
                    $_SESSION['email_err'] = 'Неправильный формат E-mail';
                    echo header('Location: /profile');
                    exit();
                }

                $is_email = $db->getEmail($_POST['email']);

                //Проверяем так же, занят ли он кем-либо
                if(isEmail($_POST['email'], $is_email)){
                    $_SESSION['email_err'] = 'Пользователь с таким E-mail уже существует';
                    echo header('Location: /profile');
                    exit();
                }

                $filename = User::uploadImage($_FILES['image']);

                $result = $db->userUpdate($_SESSION['user']['id'], $_POST, $filename);

                if($result){
                    $_SESSION['success'] = 'Профиль успешно обновлен';
                    $_SESSION['user']['name'] = $_POST['name'];
                    $_SESSION['user']['email'] = $_POST['email'];
                }
                echo header('Location:/profile');
                exit();
            }else{
                $_SESSION['email_err'] = 'Вы ввели прежний E-mail';
                echo header('Location: /profile');
                exit();
            }
        }
        return include '../App/views/profile.php';
    }

    public function Register()
    {

        $db = new User($this->pdo);

        if(!empty($_POST['name']) && !empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['password_confirmation'])){

            $is_email = $db->getEmail($_POST['email']);

            if(isEmail($_POST['email'], $is_email)){
                $_SESSION['email_err'] = 'Пользователь с таким E-mail уже существует';
                echo header('Location: /register');
                exit();
            }


            //print_r($emails);die();
            if(!checkEmail($_POST['email'])){
                $_SESSION['email_err'] = 'Неправильный формат E-mail';
                echo header('Location: /register');
                exit();
            }



            if(!checkPassword($_POST['password'], $_POST['password_confirmation'])){
                $_SESSION['pass_err'] = 'Пароли не совпадают или пароль содержит менее 6 символов';
                echo header('Location: /register');
                exit();
            }

            $name = $_POST['name'];
            $email = $_POST['email'];
            $pass = md5($_POST['password']);
            $password_confirmation = md5($_POST['password_confirmation']);

            $query = $db->Register($name, $email,$pass);

            if($query){
                $_SESSION['register_success'] = 'Вы успешно зарегистрировались. Теперь можете авторизоваться.';
                echo header('Location: /login');
                exit();
            }else{
                $_SESSION['register_error'] = 'Ошибка решистрации. Пожалуйста, повторите позже.';
                echo header('Location : /register');
                exit();
            }
        }

        return include '../App/views/register.php';
    }

    public function Login()
    {
        //Проверяем куки на существование
        if (isset($_COOKIE['id']) && isset($_COOKIE['hash'])){
            //Если куки существуют достаем из базы данные пользователя где ID = $_COOKIE['id']
            $db = new User($this->pdo);
            $user = $db->getUserId($_COOKIE['id']);

            //Сравниваем данные из кукисов м данными из БД
            if(($user['user_hash'] === $_COOKIE['hash']) && ($user['id'] === $_COOKIE['id'])){
                //Если данные совпадают, то записываем их в сессию
                foreach ($user as $key => $item) {
                    $_SESSION['user'][$key] = $item;
                }
                return header('Location: /');
            }else{
                // Если данные не совпали, удаляем куки
                setcookie("id", "", time() - 3600*24*30*12, "/");
                setcookie("hash", "", time() - 3600*24*30*12, "/");
            }
            return header('Location: /');
        }else{
            /*Если куков не существует, то проходим процедуру авторизации как полагается. Проверяем на пустые поля*/
            if( !empty( $_POST['email'] ) && !empty( $_POST['password'] ) ) {
                //Делаем выборку из базы данных пользователя по переданному $_POST['email']
                $db = new User($this->pdo);
                $user = $db->getEmail($_POST['email']);

                //Проверяем на валидность формата
                if(checkEmail($_POST['email'])){

                    //Проверяем на сравнение с email из $_POST['email'] и Из БД
                    if($_POST['email'] === $user['email']){

                        //Проверяем на сравнение с password из $_POST['password'] и Из БД
                        if(md5($_POST['password']) === $user['password']){

                            //Если данные совпадают, то записываем их в сессию
                            foreach ($user as $key => $item) {
                                $_SESSION['user'][$key] = $item;
                            }

                            //Если пользователь отметил "Запомнить меня"
                            if($_POST['remember']){
                                //Генерируем хеш
                                $hash = md5(generateCode(10));
                                $db->getRememberMe($hash, $user['id']);
                                //Записываем в куки
                                setcookie("id", $user['id'], time()+60*60*24*30);
                                setcookie("hash", $hash, time()+60*60*24*30);
                            }

                            echo header('Location: /');
                            exit();
                        }else{
                            $_SESSION['pass_err'] = 'Пароль не верный';
                            echo header('Location: /login');
                            exit();
                        }
                    }else{
                        $_SESSION['email_err'] = 'Email не найден';
                        echo header('Location: /login');
                        exit();
                    }
                }else{
                    $_SESSION['email_err'] = 'Неправильный формат E-mail';
                    echo header('Location: /login');
                    exit();
                }
            }
        }
        return header('../App/views/login.php');
    }

    public function UpdPassword()
    {
        $db = new User($this->pdo);

        if(empty($_POST['current']) || empty($_POST['password']) || empty($_POST['password_confirmation'])){
            $_SESSION['empty_err'] = 'Заполните необходимые поля';
            echo header('Location: /profile');
            exit();

        }else{
            $current = isset($_POST['current']) ? $_POST['current'] : '';
            $new_password = isset($_POST['password']) ? $_POST['password'] : '';
            $password_confirmation = isset($_POST['password_confirmation']) ? $_POST['password_confirmation'] : '';

            $currentDB = $db->getUserId($_SESSION['user']['id']);

            if(md5($current) !== $currentDB['password']){
                $_SESSION['newpass_err'] = 'Текущий пароль не верный';
                echo header('Location: /profile');
                exit();
            }

            if(!checkPassword($new_password, $password_confirmation)){
                $_SESSION['pass_err'] = 'Пароли не совпадают или пароль содержит менее 6 символов';
                echo header('Location: /profile');
                exit();
            }


            $db->updPassword($_SESSION['user']['id'], $new_password);

            $_SESSION['pass_success'] = 'Пароль успешно обновлен';
            echo header('Location: /profile');
            exit();
        }
    }

    public function Logout()
    {
        unset($_SESSION['user']);
        if (isset($_COOKIE['id']) && isset($_COOKIE['hash'])) {
            setcookie("id", "", time() - 3600 * 24 * 30 * 12, "/");
            setcookie("hash", "", time() - 3600 * 24 * 30 * 12, "/");
        }
        return header('Location: /');
    }
}