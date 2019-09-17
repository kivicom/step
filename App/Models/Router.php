<?php
namespace App\Models;

use FastRoute;

class Router{

    public static function getRouter(){
        $dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {
            $r->addRoute('GET', '/', ['App\Controllers\MainController', 'Index']);
            $r->addRoute('POST', '/', ['App\Controllers\CommentController', 'addComment']);

            $r->addRoute('GET', '/profile', ['App\Controllers\UserController', 'Profile']);
            $r->addRoute('POST', '/profile', ['App\Controllers\UserController', 'editProfile']);

            $r->addRoute('GET', '/login', ['App\Controllers\UserController', 'Login']);
            $r->addRoute('POST', '/login', ['App\Controllers\UserController', 'Login']);

            $r->addRoute('GET', '/register', ['App\Controllers\UserController', 'Register']);
            $r->addRoute('GET', '/verification', ['App\Controllers\UserController', 'verify_email']);
            $r->addRoute('POST', '/register', ['App\Controllers\UserController', 'Register']);
            $r->addRoute('GET', '/logout', ['App\Controllers\UserController', 'Logout']);

            $r->addRoute('GET', '/admin', ['App\Controllers\AdminController', 'Index']);
            $r->addRoute('POST', '/admin', ['App\Controllers\AdminController', 'manageComment']);
            // {id} must be a number (\d+)
            $r->addRoute('GET', '/user/{id:\d+}', ['App\Controllers\UserController', 'Index']);
        });

        // Fetch method and URI from somewhere
        $httpMethod = $_SERVER['REQUEST_METHOD'];
        $uri = $_SERVER['REQUEST_URI'];

        // Strip query string (?foo=bar) and decode URI
        if (false !== $pos = strpos($uri, '?')) {
            $uri = substr($uri, 0, $pos);
        }
        $uri = rawurldecode($uri);

        $routeInfo = $dispatcher->dispatch($httpMethod, $uri);

        switch ($routeInfo[0]) {
            case FastRoute\Dispatcher::NOT_FOUND:
                echo '... 404 Not Found';
                break;
            case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
                $allowedMethods = $routeInfo[1];
                echo '... 405 Method Not Allowed';
                break;
            case FastRoute\Dispatcher::FOUND:
                $handler = $routeInfo[1];
                $vars = $routeInfo[2];
                $containerBuilder = Definitions::addDefinitions();
                $container = $containerBuilder->build();
                $container->call($routeInfo[1], $routeInfo[2]);
                break;
        }
    }

}