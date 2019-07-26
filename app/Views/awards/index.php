<?php
include(APPDIR.'views/layouts/header.php');

use App\Helpers\Session;
use App\Helpers\Url;
?>

<?php if(Session::get('is_admin') == 1){
    Url::redirect('/404');
}?>

<h1>Awards</h1>

<?php include(APPDIR.'views/layouts/errors.php');?>

<p><a href="/awards/add" class="btn btn-xs btn-info">Add Award</a></p>

<div class='table-responsive'>
    <table class='table table-striped table-hover table-bordered'>
    <tr>
        <th>Awardee</th>
        <th>Award Type</th>
        <th>Award Creator</th>
        <th>Date of Award</th>
        <th>Action</th>
    </tr>
    <?php foreach($records as $row) { ?>
    <tr>
        <td><?=htmlentities($row->awardeeFullName);?></td>
        <td><?=htmlentities($row->awardName);?></td>
        <td><?=htmlentities($row->firstName) . ' ' . htmlentities($row->lastName);?></td>
        <?php date_default_timezone_set("America/Los_Angeles");
        $displayDate = $row->awardDateTime;
        list($Date)=explode('-07:00', $displayDate);
        $displayDate = $Date;?>
        <td><?=$displayDate;?></td>
        <td>
            <a href="/awards/view/<?=$row->awardId;?>" class="btn btn-xs btn-info">Preview/Send</a>
            <a href="/awards/edit/<?=$row->awardId;?>" class="btn btn-xs btn-warning">Edit</a>

            <?php if (Session::get('user_id') == $row->awardCreatorId) {          
                echo '
            <button type="button" class="showModal btn btn-xs btn-danger" data-toggle="modal"  id="deleteButton" data-id="' . $row->awardId . '">Delete
            </button>';
        }
        ?>

        <!--modal code-->
        <div id="deleteConfirm<?php echo $row->awardId;?>" data-id="<?php echo $row->awardId;?>" data-name="<?php echo $row->awardeeFullName;?>" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="deleteConfirmTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title">Delete Award</h3>    
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to permanently delete the award for <?=$row->awardeeFullName;?>? </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <a href="/awards/delete/<?=$row->awardId;?>" class="btn btn-danger" id="btnConfirmDelete">Delete</a>
                    </div>
                </div>    
            </div>      
        </div>
<!--end modal-->     
        </td>
    </tr>
    <?php } ?>
    </table>
</div>

<script type="text/javascript">
    $(function(){
        $(".showModal").click(function(e){
            e.preventDefault();
            var id = $(this).attr('data-id');
            $("#deleteConfirm"+id).modal('show');
    });

    $("#btnConfirmDelete").click(function(e) {
      $("#deleteConfirm"+id).modal('hide');
    });

    });
</script>

<?php include(APPDIR.'views/layouts/footer.php');?>
