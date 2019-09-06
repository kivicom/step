<?php

if(!isset($_SESSION['user']['id'])){
    echo header('Location: /login');
    exit();
}

$db = new User(Connection::make($config['database']));
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

        $filename = '';
        if(!empty($_FILES['image']['name'])){
            $uploaddir = __DIR__ . '/pablic/cabinet/';
            $userDir = 'user'.$_SESSION['user']['id'];
            if(!file_exists($userDir)){
                @mkdir($uploaddir . $userDir . '/');
            }
            $file = explode(".", $_FILES['image']['name']);
            $filename = md5($file[0]) . '.' . $file[1];
            $isFile = array_diff(scandir($uploaddir . $userDir, 1), ['.','..']);

            if(file_exists($uploaddir . $userDir . '/' . $isFile[0])){
                unlink($uploaddir . $userDir . '/' . $isFile[0]);
                unset($_SESSION['user']['avatar']);
            }


            if(move_uploaded_file($_FILES['image']['tmp_name'], $uploaddir . $userDir . '/' . $filename)){
                $_SESSION['user']['avatar'] = $filename;
            }
        }

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