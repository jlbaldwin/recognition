<?php
include(APPDIR.'views/layouts/header.php');
include(APPDIR.'views/layouts/errors.php');

use App\Helpers\Session;
use App\Helpers\Url;
?>

<?php if(Session::get('is_admin') == 1){
    Url::redirect('/404');
}?>

<h1>Create New Award</h1>
<h5>NOTE: Awards will show as coming from you, the award creator.  The awardee manager is tracked separately for reporting purposes.</h5>

<form method="post">

    <div class="row">

        <div class="col-md-6">
            <div class="control-group">
                <label class="control-label" for="awardClassId">Award Name</label>
                <select class="form-control" id="awardClassId" name="awardClassId" required>
                <option value="">--Select Award Type--</option>
                    <?php
                        foreach($records as $result) {
                            echo '<option value="';
                            echo $result->classId;
                            echo '">';
                            echo $result->awardName,'</option>';
                        }
                    ?>
                </select>
            </div>
            <div class="control-group">
                <label class="control-label" for="awardName">Awardee Full Name</label>
                <input class="form-control" id="awardeeFullName" type="text" name="awardeeFullName" value="<?=(isset($_POST['awardeeFullName']) ? $_POST['awardeeFullName'] : '');?>" required  />
            </div>
            <div class="control-group">
                <label class="control-label" for="awardeeEmail">Awardee Email Address</label>
                <input class="form-control" id="awardeeEmail" type="text" name="awardeeEmail" value="<?=(isset($_POST['awardeeEmail']) ? $_POST['awardeeEmail'] : '');?>" required  />
            </div>
            <div class="control-group">
                <label class="control-label" for="awardeePosition">Awardee Position</label>
                <input class="form-control" id="awardeePosition" type="text" name="awardeePosition" value="<?=(isset($_POST['awardeePosition']) ? $_POST['awardeePosition'] : '');?>" required  />
            </div>
            <div class="control-group">
                <label class="control-label" for="awardeeLocation">Awardee Location</label>
                <input class="form-control" id="awardeeLocation" type="text" name="awardeeLocation" value="<?=(isset($_POST['awardeeLocation']) ? $_POST['awardeeLocation'] : '');?>" required  />
            </div>
            <div class="control-group">
                <label class="control-label" for="awardeeManager">Awardee Manager</label>
                <input class="form-control" id="awardeeManager" type="text" name="awardeeManager" value="<?=(isset($_POST['awardeeManager']) ? $_POST['awardeeManager'] : '');?>" required  />
            </div>
            <div class="control-group">
                <label class="control-label" for="awardDateTime">Award Date</label>
                <?php
                    date_default_timezone_set("America/Los_Angeles");
                    $displayDate = date("c");
                    list($Date)=explode('-07:00', $displayDate);
                    $displayDate = $Date;
                ?>
                <input class="form-control" id="awardDateTime" type="datetime-local" name="awardDateTime" value="<?php echo $displayDate ?>" required />
            </div>
        </div>
    </div>

    <br>
    Note: Please be careful to use real, working email addresses.  Bounced emails can cause our mail provider to temporarily disable email access.
    <p><button type="submit" class="btn btn-success" name="submit"><i class="fa fa-check"></i> Submit</button></p>

</form>

<?php include(APPDIR.'views/layouts/footer.php');?>
