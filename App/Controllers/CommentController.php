<?php
namespace App\Controllers;

use App\Models\Comment;

class CommentController
{
    private $db;

    public function __construct()
    {
        $this->db = new Comment();
    }

    public function addComment()
    {
        if(!empty($_POST['name']) && !empty($_POST['text'])) {
            $query = $this->db->addComment('comments', $_POST);
            if($query){
                flash()->success('Комментарий успешно добавлен!');
            }else{
                echo 'Сообщение не отправлено!';
                flash()->error('Ошибка. Комментарий не добавлен');
            }
            header('Location: /');
            exit;
        }else {
            if (empty($_POST['name'])) {
                flash()->error('Ошибка. Поле имя не заполненно');
            }
            if (empty($_POST['text'])) {
                flash()->error('Ошибка. Поле комментарий не заполненно');
            }
            echo header('Location: /');
        }
    }
}