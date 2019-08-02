<?php namespace App\Helpers;

/*redirection method:
* redirect() for home page
* redirect('/mypage') for mypage	
*/

class Url
{
    public static function redirect($path = '/')
    {
        header('Location: '.$path);
        exit();
    }
}

/* CREDIT TO "Beginning PHP" by David Carr and Markus Gray, Packt Publishing, July 2018. */
