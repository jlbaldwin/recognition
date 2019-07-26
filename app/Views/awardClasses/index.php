<?php
include(APPDIR.'views/layouts/header.php');

use App\Helpers\Session;
use App\Helpers\Url;
?>

<?php if(Session::get('is_admin') == 1){
    Url::redirect('/404');
}?>

<h1>Award Types</h1>

<?php include(APPDIR.'views/layouts/errors.php');?>

<p><a href="/awardClasses/add" class="btn btn-xs btn-info">Add Award Type</a></p>

<div class='table-responsive'>
    <table class='table table-striped table-hover table-bordered'>
    <tr>
        <th>Award Class</th>
        <th>Action</th>
    </tr>
    <?php foreach($records as $row) { ?>
    <tr>
        <td><?=htmlentities($row->awardName);?></td>
        <td>
          
            <button type="button" class="showModal btn btn-xs btn-danger" data-toggle="modal"  id="deleteButton" data-id="<?php echo $row->classId;?>">Delete
            </button>

<!--modal code-->
        <div id="deleteConfirm<?php echo $row->classId;?>" data-id="<?php echo $row->classId;?>" data-name="<?php echo $row->awardName;?>" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="deleteConfirmTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title">Delete Award Type</h3>    
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to permanently delete <?=$row->awardName;?>? </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <a href="/awardClasses/delete/<?=$row->classId;?>" class="btn btn-danger" id="btnConfirmDelete">Delete</a>
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
