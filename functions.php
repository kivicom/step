<?php

function dd($var){
    echo '<pre>';
    var_dump($var);
    echo '<pre>';
    die();
}

function checkEmail($email)
{
    if(filter_var($email,FILTER_VALIDATE_EMAIL)){
        return true;
    }
    return false;
}

function isEmail($email, $is_email){
    if(in_array($email, $is_email)){
        return true;
    }
    return false;
}

function checkPassword($pass, $pass_confirm)
{
    if(($pass === $pass_confirm) && (strlen($pass) >= 6)){
        return true;
    }
    return false;
}

function generateCode($length=6) {
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPRQSTUVWXYZ0123456789";
    $code = "";
    $clen = strlen($chars) - 1;
    while (strlen($code) < $length) {

        $code .= $chars[mt_rand(0,$clen)];
    }
    return $code;
}