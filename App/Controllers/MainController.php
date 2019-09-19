<?php
namespace App\Controllers;

use App\Models\Comment;
use Delight\Auth\Auth;
use JasonGrimes\Paginator;
use League\Plates\Engine;
use App\Models\Pagination;

class MainController
{
    public $db;
    public $templates;
    public $pagination;
    private $auth;

    public function __construct(Auth $auth, Comment $objComment, Engine $engine, Pagination $pagination)
    {
        $this->auth = $auth;
        $this->db = $objComment;
        $this->templates = $engine;
        $this->pagination = $pagination;
    }

    public function Index()
    {
        $itemsPerPage = 5;
        $currentPage = $_GET['page'] ?? 1;
        $urlPattern = '?page=(:num)';

        if ($this->auth->isLoggedIn()) {
            $auth = true;
        }else {
            $auth = false;
        }

        $totalComments = count($this->db->getAll('comments'));

        $items = $this->pagination->getItemsOnPagination('comments', $itemsPerPage, $currentPage);


        $paginator = new Paginator($totalComments, $itemsPerPage, $currentPage, $urlPattern);

        echo $this->templates->render('index.view', ['items' => $items, 'auth' => $auth, 'paginator' => $paginator]);
    }
}