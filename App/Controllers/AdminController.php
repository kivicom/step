<?php
namespace App\Controllers;
use App\Models\Admin;
use App\Models\Pagination;
use DB\Connection;
use Delight\Auth\Auth;
use JasonGrimes\Paginator;
use League\Plates\Engine;

class AdminController
{
    public $db;
    public $templates;
    private $auth;
    private $pdo;
    public $pagination;

    public function __construct()
    {
        $this->pdo = Connection::make();
        $this->auth = new Auth($this->pdo);
        $this->db = new Admin();
        $this->templates = new Engine('../App/views');
        $this->pagination = new Pagination();
    }
    public function Index()
    {
        $itemsPerPage = 5;
        $currentPage = $_GET['page'] ?? 1;
        $urlPattern = '?page=(:num)';

        if(!$this->auth->hasRole(\Delight\Auth\Role::ADMIN)){
            header('Location: /login');
            exit();
        }
        if ($this->auth->isLoggedIn()) {
            $auth = true;
        }else {
            $auth = false;
        }
        $totalComments = count($this->db->getAll('comments'));
        $items = $this->pagination->getCommentsOnPagination('comments', $itemsPerPage, $currentPage);


        $paginator = new Paginator($totalComments, $itemsPerPage, $currentPage, $urlPattern);

        echo $this->templates->render('admin', ['items' => $items, 'auth' => $auth, 'paginator' => $paginator]);
    }

    public function manageComment()
    {
        if(isset($_POST['published'])){
            $this->db->manageComments('comments', $_POST['id'], $_POST);
        }
        if(isset($_POST['remove'])){
            $this->db->deleteComments('comments', $_POST['id']);
        }
        echo header('Location:/admin');
        exit();
    }
}