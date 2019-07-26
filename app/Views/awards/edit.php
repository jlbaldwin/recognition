<?php
include(APPDIR.'views/layouts/header.php');
include(APPDIR.'views/layouts/errors.php');

use App\Helpers\Session;
use App\Helpers\Url;
?>

<?php if(Session::get('is_admin') == 1){
    Url::redirect('/404');
}?>

<h1>Edit Award</h1>

<form method="post">

    <div class="row">

        <div class="col-md-6">
            <div class="control-group">
                <label class="control-label" for="awardClassId">Award Name</label>
                <select class="form-control" id="awardClassId" name="awardClassId" required>
                <option value="">--Select Award Type--</option>
                <?php
                        foreach($awardTypes as $result) {
                            echo '<option value="';
                            echo $result->classId;
                            echo '"';
                            if ($result->classId == $award->awardClassId)
                            {
                                echo ' selected="selected" ';
                            }
                            echo '>';
                            echo $result->awardName,'</option>';
                        }
                    ?>
                </select>
            </div>
            <div class="control-group">
                <label class="control-label" for="awardName">Awardee Full Name</label>
                <input class="form-control" id="awardeeFullName" type="text" name="awardeeFullName" value="<?=$award->awardeeFullName; ?>"  required  />            </div>
            <div class="control-group">
                <label class="control-label" for="awardeeEmail">Awardee Email Address</label>
                <input class="form-control" id="awardeeEmail" type="text" name="awardeeEmail" value="<?=$award->awardeeEmail;?>" required  />
            </div>
            <div class="control-group">
                <label class="control-label" for="awardeePosition">Awardee Position</label>
                <input class="form-control" id="awardeePosition" type="text" name="awardeePosition" value="<?=$award->awardeePosition;?>" required  />
            </div>
            <div class="control-group">
                <label class="control-label" for="awardeeLocation">Awardee Location</label>
                <input class="form-control" id="awardeeLocation" type="text" name="awardeeLocation" value="<?=$award->awardeeLocation;?>" required  />
            </div>
            <div class="control-group">
                <label class="control-label" for="awardeeManager">Awardee Manager</label>
                <input class="form-control" id="awardeeManager" type="text" name="awardeeManager" value="<?=$award->awardeeManager;?>" required  />
            </div>
            <div class="control-group">
                <label class="control-label" for="awardDateTime">Award Date</label>
                <?php
                    $displayDate = date("c", strtotime($award->awardDateTime));
                    list($Date)=explode('+', $displayDate);
                    $displayDate = $Date;
                ?>
                <input class="form-control" id="awardDateTime" type="datetime-local" name="awardDateTime" value="<?=$displayDate;?>" required />
            </div>

        </div>
    </div>

    <br>
    Note: Please be careful to use real, working email addresses.  Bounced emails can cause our mail provider to temporarily disable email access.
    <p><button type="submit" class="btn btn-success" name="submit"><i class="fa fa-check"></i> Submit</button></p>

</form>

<?php include(APPDIR.'views/layouts/footer.php');?>