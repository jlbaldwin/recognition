<?php
include(APPDIR.'views/layouts/header.php');

use App\Helpers\Session;
use App\Helpers\Url;
?>

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

<?php if(Session::get('is_admin') == 0){
    Url::redirect('/404');
}?>

<h1>Users</h1>

<?php include(APPDIR.'views/layouts/errors.php');?>

<p><a href="/users/add" class="btn btn-xs btn-info">Add User</a></p>

<div class='table-responsive'>
    <table class='table table-striped table-hover table-bordered'>
    <tr>
        <th>First Name</th>
        <th>Last Name</th>
        <th>Email</th>
        <th>Date Created</th>
        <th>Action</th>
    </tr>
    <?php foreach($users as $row) { ?>
        <td><?=htmlentities($row->firstName);?></td>
        <td><?=htmlentities($row->lastName);?></td>
        <td><?=htmlentities($row->email);?></td>
        <td><?=htmlentities($row->timeCreated) . " PDT";?></td>
        <td>
            <a href="/users/edit/<?=$row->userId;?>" class="btn btn-xs btn-warning">Edit</a>
            <button type="button" class="showModal btn btn-xs btn-danger" data-toggle="modal"  id="deleteButton" data-id="<?php echo $row->userId;?>">Delete
            </button>
<!--modal code-->
            <div id="deleteConfirm<?php echo $row->userId;?>" data-id="<?php echo $row->userId;?>" data-email="<?php echo $row->email;?>" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="deleteConfirmTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3 class="modal-title">Delete User</h3>    
                        </div>
                        <div class="modal-body">
                            <p>Are you sure you want to permanently delete <?=$row->email;?>? </p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <a href="/users/delete/<?=$row->userId;?>" class="btn btn-danger" id="btnConfirmDelete">Delete</a>
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

<?php include(APPDIR.'views/layouts/footer.php');

/* Adapted from "Beginning PHP" by David Carr and Markus Gray, Packt Publishing, July 2018. */

?>



