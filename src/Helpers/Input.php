<?php
namespace App\Helpers;

class Input
{
    public static function post($data)
    {
        if (isset($_POST[$data])) {
            return $_POST[$data];
        }
        return "";
    }

    public static function get($data)
    {
        if (isset($_GET[$data])) {
            return $_GET[$data];
        }
        return "";
    }

    public static function file($data)
    {
        if (isset($_FILES[$data])) {
            return $_FILES[$data];
        }
        return false;
    }
}
?>