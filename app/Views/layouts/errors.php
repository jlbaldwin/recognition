<?php
use App\Helpers\Session;

if (isset($errors)) {
    foreach($errors as $error) {
        echo "<div class='alert alert-danger'>$error</div>";
    }
}

if (Session::get('success')) {
    echo "<div class='alert alert-success'>".Session::pull('success')."</div>";
}

if (Session::get('danger')) {
    echo "<div class='alert alert-danger'>".Session::pull('danger')."</div>";
}

/* CREDIT TO "Beginning PHP" by David Carr and Markus Gray, Packt Publishing, July 2018. */
