<?php

$db = new User(Connection::make($config['database']));

//Проверяем куки на существование
if (isset($_COOKIE['id']) && isset($_COOKIE['hash'])){
    //Если куки существуют достаем из базы данные пользователя где ID = $_COOKIE['id']
    $user = $db->getUserId($_COOKIE['id']);

    //Сравниваем данные из кукисов м данными из БД
    if(($user['user_hash'] === $_COOKIE['hash']) && ($user['id'] === $_COOKIE['id'])){
        //Если данные совпадают, то записываем их в сессию
        foreach ($user as $key => $item) {
            $_SESSION['user'][$key] = $item;
        }
        echo header('Location: /');
        exit();
    }else{
        // Если данные не совпали, удаляем куки
        setcookie("id", "", time() - 3600*24*30*12, "/");
        setcookie("hash", "", time() - 3600*24*30*12, "/");
    }
}else{
    /*Если куков не существует, то проходим процедуру авторизации как полагается. Проверяем на пустые поля*/
    if( !empty( $_POST['email'] ) && !empty( $_POST['password'] ) ) {
        //Делаем выборку из базы данных пользователя по переданному $_POST['email']

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