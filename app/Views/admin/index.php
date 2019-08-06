<?php
use App\Helpers\Session;
use App\Helpers\Url;

include(APPDIR.'views/layouts/header.php');
include(APPDIR.'views/layouts/errors.php');
?>

<?php                 

    if(Session::get('is_admin') == 1) {    
        Url::redirect('/users');
    }
    else {
        Url::redirect('/awards');   
    } 
?>

<?php include(APPDIR.'views/layouts/footer.php');

/* Adapted from "Beginning PHP" by David Carr and Markus Gray, Packt Publishing, July 2018. */

?>

