<?php
include(APPDIR.'views/layouts/header.php');
include(APPDIR.'views/layouts/errors.php');

use App\Helpers\Session;
use App\Helpers\Url;
?>

<?php if(Session::get('is_admin') == 0){
    Url::redirect('/404');
}?>

<h1>Add User</h1>

<form method="post" enctype="multipart/form-data">

    <div class="row">

        <div class="col-md-6">

            <div class="control-group">
                <label class="control-label" for="firstName">First Name</label>
                <input class="form-control" id="firstName" type="text" name="firstName" value="<?=(isset($_POST['firstName']) ? $_POST['firstName'] : '');?>" required  />
            </div>

            <div class="control-group">
                <label class="control-label" for="lastName">Last Name</label>
                <input class="form-control" id="lastName" type="text" name="lastName" value="<?=(isset($_POST['lastName']) ? $_POST['lastName'] : '');?>" required  />
            </div>

            <div class="control-group">
                <label class="control-label" for="email"> Email</label>
                <input class="form-control" autocomplete="off" id="email" type="email" name="email" value="<?=(isset($_POST['email']) ? $_POST['email'] : '');?>" required  />
            </div>

            <div class="control-group" id="signatureFile">
                <label class='control-label'>Signature File</label>
                <input type='file' id='signature' name='signature' accept='image/*'>
                Maximum image height x width: 123 x 823 pixels<br>
            </div>

        </div>

        <div class="col-md-6">

            <div class="control-group">
                <label class="control-label" for="password"> Password</label>
                <input class="form-control" autocomplete="password" id="password" type="password" name="password" required/>
            </div>

            <div class="control-group">
                <label class="control-label" for="password_confirm"> Confirm Password</label>
                <input class="form-control" id="password_confirm" type="password" name="password_confirm" required/>
            </div>
            <script>
                let isToggled = 0;

                function showFile() {
                    let divContents = '';
                    if (isToggled == 1) {
                        divContents = "<label class='control-label'>Signature File</label>"+
                        "<input type='file' id='signature' name='signature' accept='image/*'>"+
                        "Maximum image height x width: 123 x 823 pixels<br>";
                        isToggled = 0;
                    }
                    else
                    {
                        divContents = '';
                        isToggled = 1;
                    }
                    document.getElementById("signatureFile").innerHTML = divContents;
                }
            </script>
            <div class="control-group">
                <label class="checkbox">
                    Administrator (check for Admin access)
                </label>

                <!-- hidden input provides the "0" flag for isAdmin, if the Admin box is unchecked.-->
                <input type="hidden" name="isAdmin" value="0">
                <input type="checkbox" name="isAdmin" value="1" onclick="showFile()">
                
            </div>

        </div>

    </div>

    <br>

    Note: Please be careful to use real, working email addresses.  Bounced emails can cause our mail provider to temporarily disable email access.

    <p><button type="submit" class="btn btn-success" name="submit"><i class="fa fa-check"></i> Submit</button></p>

</form>

<?php include(APPDIR.'views/layouts/footer.php');

/* Adapted from "Beginning PHP" by David Carr and Markus Gray, Packt Publishing, July 2018. */

?>

