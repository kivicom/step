<?php

class Router{

    protected static $routes = [];
    protected static $route;

    public static function getRoute($route, array $routes)
    {
        if(array_key_exists($route, $routes)){
            //dd($routes);
            return $routes[$route];
            exit();
        }else{
            dd('404');
        }
    }


}