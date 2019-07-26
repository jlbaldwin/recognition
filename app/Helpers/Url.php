<?php namespace App\Helpers;

class Url
{
    public static function redirect($path = '/')
    {
        header('Location: '.$path);
        exit();
    }
}

/* CREDIT TO "Beginning PHP" by David Carr and Markus Gray, Packt Publishing, July 2018. */
