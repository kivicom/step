<?php
namespace App\Controllers;

use App\Models\Comment;
use DB\Connection;
use Delight\Auth\Auth;
use JasonGrimes\Paginator;
use League\Plates\Engine;
use \Tamtamchik\SimpleFlash\Flash;
use App\Models\Pagination;

class MainController
{
    public $db;
    private $pdo;
    public $templates;
    public $pagination;
    private $auth;

    public function __construct()
    {
        $this->pdo = Connection::make();
        $this->auth = new Auth($this->pdo);
        $this->db = new Comment();
        $this->templates = new Engine('../App/views');
        $this->pagination = new Pagination();
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

        $items = $this->pagination->getCommentsOnPagination('comments', $itemsPerPage, $currentPage);


        $paginator = new Paginator($totalComments, $itemsPerPage, $currentPage, $urlPattern);

        echo $this->templates->render('index.view', ['items' => $items, 'auth' => $auth, 'paginator' => $paginator]);
    }
}