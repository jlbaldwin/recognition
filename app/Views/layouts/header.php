<?php
use App\Helpers\Session;
?>

<html lang="en">
<head>
  <title><?php echo 'Employee Recognition Portal'; ?></title>
<!--   <title>Employee Recognition Portal</title> -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
  <link rel="stylesheet" type="text/css" href="/CSS/style.css">
</head>
<body>

<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      <div class="navbar-brand noHover">Employee Recognition Portal</div>
    </div>
    <?php if(Session::get('logged_in') == 1) {
      echo '    
        <ul class="nav navbar-nav navbar-right">
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <span class="glyphicon glyphicon-log-in" id="usernav"></span> ' . Session::get('last_name') . ', ' . Session::get('first_name') . '</a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
              <a class="dropdown-item" href="/users/edit/' . Session::get('user_id') . '"> Settings</a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="/admin/logout">Logout</a>
            </div>
          </li>
        </ul>'
    ;} ?>
  </div>
</nav>
  
<div class="container-fluid text-left">    
  <div class="row content">
  <?php if(Session::get('logged_in') == 1) {?>
    <!--if isAdmin==1 only display Admin menu options -->
    <?php if(Session::get('is_admin') == 1){?>    
      <div class="col-sm-2 sidenav">
        <div>
          <a class="sidenav" href="/users/add">
              <span class="glyphicon glyphicon-user"></span>    Add User
          </a>
        </div>
        <div>
          <a class="sidenav" href="/users">
              <span class="glyphicon glyphicon-edit"></span>    Manage Users
          </a>
        </div>
        <div>
          <a class="sidenav" href="/reports">
              <span class="glyphicon glyphicon-stats"></span>  Reports
          </a>
        </div>
      </div>
    <!--if isAdmin!=1 only display User menu options -->
    <?php }else{?>                    
    <div class="col-sm-2 sidenav">
      <div>
        <a class="sidenav" href="/awards/add">
            <span class="glyphicon glyphicon-plus"></span>  Create Award
        </a>
      </div>
      <div>
        <a class="sidenav" href="/awards">
            <span class="glyphicon glyphicon-tasks"></span>  Awards
        </a>
      </div>
      <div>
        <a class="sidenav" href="/awardClasses">
            <span class="glyphicon glyphicon-tags"></span>  Award Types
        </a>
      </div>
    </div>
    <?php }?>

  <?php } else { ?> 
    <div class="col-sm-2 sidenav">
    </div>
  <?php }
  ?>
<div class="col-sm-8 text-left">
