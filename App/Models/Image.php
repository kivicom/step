<?php
namespace App\Models;

class Image
{
    public static function uploadImage($image, $id, $dir, $userdir){

        $filename = '';
        if(!empty($image['name'])){
            $uploaddir = $dir;
            $userDir = $userdir;
            if(!file_exists($uploaddir . $userDir)){
                mkdir($uploaddir . $userDir, 0777, true);
            }
            $extention = pathinfo($image['name'], PATHINFO_EXTENSION);
            $filename = uniqid() . "." . $extention;
            $isFile = array_diff(scandir($uploaddir . $userDir, 1), ['.','..']);

            if(!empty($isFile)){
                if(file_exists($uploaddir . $userDir . '/' . $isFile[0])){
                    unlink($uploaddir . $userDir . '/' . $isFile[0]);
                }
            }

            move_uploaded_file($image['tmp_name'], $uploaddir . $userDir . '/' . $filename);
        }

        return $filename;
    }
}