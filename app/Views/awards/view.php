<?php
include(APPDIR.'views/layouts/header.php');
include(APPDIR.'views/layouts/errors.php');

use App\Helpers\Session;
use App\Helpers\Url;
?>

<?php if(Session::get('is_admin') == 1){
    Url::redirect('/404');
}?>

<h3><?php echo $title; ?></h3>

    <b>From:</b> fangrecognition@mail.com<br>
    <b>To: </b><?php echo $data->awardeeFullName . " (" . $data->awardeeEmail; ?>)<br>
    <b>Subject:</b> Congratulations on your recognition!<br>
    <br>
    <p>Congratulations!</p>
    <p><?php echo $data->firstName . " " . $data->lastName; ?> has selected you for the award <?php echo $data->awardName; ?>.</p>
    <p>This award is effective <?php 
        echo date("l", strtotime($data->awardDateTime));
        echo (", the ");
        echo date("jS \of F", strtotime($data->awardDateTime));
        echo ", ";
        echo date("Y", strtotime($data->awardDateTime));
    ?>.</p>
    <p>Please see the attached PDF for details.</p>

    <p><a href="/awards/sendfile/<?php echo $data->awardFilePath; ?>"><?php echo $data->awardFilePath; ?></a></p>

    <p class="pull-left"><a href="/awards/send/<?php echo $data->awardId; ?>" class="btn btn-xs btn-info">Click Here to Send</a></p>


<?php include(APPDIR.'views/layouts/footer.php');?>