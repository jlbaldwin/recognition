<?php
include(APPDIR.'views/layouts/header.php');
include(APPDIR.'views/layouts/errors.php');
?>

<h1>Edit User</h1>

<form method="post">

    <div class="row">

        <div class="col-md-6">

            <div class="control-group">
                <label class="control-label" for="email"> Email</label>
                <input class="form-control" id="email" type="email" name="email" value="<?=$user->email;?>" required />
            </div>
            <div class="control-group">
                <label class="control-label" for="firstName"> First Name</label>
                <input class="form-control" id="firstName" type="firstName" name="firstName" value="<?=$user->firstName;?>" required />
            </div>
            <div class="control-group">
                <label class="control-label" for="LastName"> Last Name</label>
                <input class="form-control" id="lastName" type="lastName" name="lastName" value="<?=$user->lastName;?>" required />
            </div>

        </div>

        <div class="col-md-6">

            <div class="panel panel-primary">
                <div class="panel-heading">Password, only enter to change the existing password.</div>
                <div class="panel-body">

                    <div class="control-group">
                        <label class="control-label" for="password"> Password</label>
                        <input class="form-control" id="password" type="password" name="password" value="" />
                    </div>

                    <div class="control-group">
                        <label class="control-label" for="password_confirm"> Password</label>
                        <input class="form-control" id="password_confirm" type="password" name="password_confirm" value="" />
                    </div>

                </div>
            </div>

        </div>

    </div>
    Note: Please be careful to use real, working email addresses.  Bounced emails can cause our mail provider to temporarily disable email access.
    <p><button type="submit" class="btn btn-success" name="submit"><i class="fa fa-check"></i> Submit</button></p>

</form>

<?php include(APPDIR.'views/layouts/footer.php');?>
