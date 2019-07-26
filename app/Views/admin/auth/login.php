<?php

/* echo password_hash('demo', PASSWORD_BCRYPT); */

include(APPDIR.'views/layouts/header.php');?>



<div class="wrapper well">

    <?php include(APPDIR.'views/layouts/errors.php');?>

    <form action="/admin/login" method="post">

    <h1>Login</h1>

    <div class="control-group">
        <label class="control-label" for="username"> Username</label>
        <input class="form-control" id="username" type="text" name="username" />
    </div>

    <div class="control-group">
        <label class="control-label" for="password"> Password</label>
        <input class="form-control" id="password" type="password" name="password" />
    </div>

    <br>

    <p class="pull-left"><button type="submit" class="btn btn-sm btn-success" name="submit">Login</button></p>
    <p class="pull-right"><a href="/admin/reset">Forgot Password</a></p>

    <div class="clearfix"></div>

    </form>

</div>

<!--No footer on login - Closing body/html tags from the included header file.-->
</body>
</html>

<!-- CREDIT TO "Beginning PHP" by David Carr and Markus Gray, Packt Publishing, July 2018. -->