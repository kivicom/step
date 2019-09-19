<?php
namespace App\Controllers;

use App\Models\Comment;
use \Tamtamchik\SimpleFlash\Flash;


class CommentController
{
    private $db;

    public function __construct(Comment $objComments)
    {
        $this->db = $objComments;
    }

    public function addComment()
    {
        if(!empty($_POST['name']) && !empty($_POST['text'])) {
            $query = $this->db->addComment('comments', $_POST);
            if($query){
                $msg = flash()->success('Комментарий успешно добавлен!');
            }else{
                echo 'Сообщение не отправлено!';
                $msg = flash()->error('Ошибка. Комментарий не добавлен');
            }
            header('Location: /');
            exit;
        }else {
            if (empty($_POST['name'])) {
                $msg = flash()->error('Ошибка. Поле имя не заполненно');
            }
            if (empty($_POST['text'])) {
                $msg = flash()->error('Ошибка. Поле комментарий не заполненно');
            }
            header('Location: /');
        }
    }

}