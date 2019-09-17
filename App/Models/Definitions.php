<?php

namespace App\Models;


use Delight\Auth\Auth;
use DI\ContainerBuilder;
use League\Plates\Engine;

class Definitions
{
    public static function addDefinitions()
    {
        $containerBuilder = new ContainerBuilder();

        $containerBuilder->addDefinitions([
            Engine::class => function(){
                return new Engine('../App/views');
            },
            \PDO::class => function(){
                return new \PDO("mysql:host=127.0.0.1;
                        dbname=marlinstep;charset=utf8","root","");
            },
            Auth::class => function($container){
                return new Auth($container->get('PDO'));
            }
        ]);
        return $containerBuilder;
    }
}