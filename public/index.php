<?php

if( !session_id() ) @session_start();

require '../vendor/autoload.php';
require_once '../functions.php';

\App\Models\Router::getRouter();
