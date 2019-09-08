<?php

class CommentController
{
    protected $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function add()
    {
        $db = new Comment($this->pdo);

        if(!empty($_POST['name']) && !empty($_POST['text'])){

            $user_id = isset($_SESSION['user']['id']) ? $_SESSION['user']['id'] : '0';
            $name = $_POST['name'];
            $text = $_POST['text'];

            $query = $db->liveComments($user_id, $name, $text);

            if($query){
                $_SESSION['com_ok'] = 'Комментарий успешно добавлен';
                echo header('Location:/');
            }else{
                echo 'Сообщение не отправлено!';
                $_SESSION['com_err'] = 'Ошибка. Комментарий не добавлен';
                echo header('Location: /');
            }
        }else{
            if(empty($_POST['name'])){
                $_SESSION['name_err'] = 'Ошибка. Поле имя не заполненно';
            }
            if(empty($_POST['text'])){
                $_SESSION['text_err'] = 'Ошибка. Поле комментарий не заполненно';
            }
            echo header('Location: /');
        }
    }
}