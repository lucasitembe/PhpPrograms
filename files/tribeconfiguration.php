<?php
    include("./includes/connection.php");
    include("./includes/header.php");
    //$controlforminput = '';
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }

?>
<a href='receptionsetup.php?ReceptionSetup=ReceptionSetupThisPage' class='art-button-green'>
    BACK
</a>

<?php 
    $feedback_message="";
    if(isset($_POST['save_btn'])){
        $tribe=$_POST['tribe'];
        $sql_save_result=mysqli_query($conn,"INSERT INTO tbl_tribe (tribe_name) VALUES('$tribe')") or die(mysqli_error($conn));
        if($sql_save_result){
           $feedback_message="<div class='alert alert-success alert-dismissable'>
                                <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                                <strong>Success!</strong> Tribe Saved Successfully
                              </div>"; 
        }else{
             $feedback_message="<div class='alert alert-danger alert-dismissable'>
                                <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                                <strong>Error!</strong> Saving Fail Please Try Again
                              </div>"; 
        }
    }
  
    if(isset($_POST['save_changes_btn'])){
		$tribe_id=$_POST['tribe_id'];
        $tribe_name=$_POST['tribe'];
        $sql_insert_result=mysqli_query($conn,"UPDATE tbl_tribe SET tribe_name='$tribe_name' WHERE tribe_id='$tribe_id'") or die(mysqli_error($conn));
           if($sql_insert_result){
             $feedback_message="<div class='alert alert-success alert-dismissable'>
                                <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                                <strong>Success!</strong> Changes Saved Successfully
                              </div>";  
           }else{
               
             $feedback_message="<div class='alert alert-danger alert-dismissable'>
                                <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                                <strong>Error!</strong>Changes Saving Fail Please Try Again
                              </div>"; 
           }
        }
        
        if(isset($_POST['edit_btn'])){
           $tribe_id=$_POST['tribe_id']; 
           $sql_select_idara_result=mysqli_query($conn,"SELECT *FROM tbl_tribe WHERE tribe_id='$tribe_id'") or die(mysqli_error($conn));
                            if(mysqli_num_rows($sql_select_idara_result)>0){
                                while($idara_rows=mysqli_fetch_assoc($sql_select_idara_result)){
                                   $tribe_id= $idara_rows['tribe_id'];
									$tribe_name=$idara_rows['tribe_name'];
              } 
           }else{
              $feedback_message="<div class='alert alert-danger alert-dismissable'>
                                <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                                <strong>Error!</strong> Process Fail..try again
                              </div>";  
           }
        }else{
           $tribe_id=""; 
           $tribe_name="";
        }
?>
<fieldset>  
    <legend align=center><b>TRIBE CONFIGURATION</b></legend>
    <div class="row">
        <div class="col-md-3"> </div>
        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header">
                    <?= $feedback_message ?>
                </div>
                <div class="box-body">
                    <form action="" method="POST" class="form-horizontal">
                        <div class="form-group">
                            <label class="col-md-3">Tribe Name</label>
                            <div class="col-md-9">
                                <input type="text" hidden="hidden" value="<?= $tribe_id ?>" name="tribe_id"/>
                                <input type="text" class="form-control" required="" value="<?= $tribe_name ?>" name="tribe" placeholder="Enter Tribe Name">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12"> 
                                <?php 
                                     if(isset($_POST['edit_btn'])){
                                        ?>
                                <input type="submit" value="SAVE CHANGES" name="save_changes_btn" onclick="return confirm('Are you sure you want to save changes?')"class="btn btn-success pull-right">
                                            <?php 
                                     }else{
                                       ?>
                                    <input type="submit" value="SAVE" name="save_btn" onclick="return confirm('Are you sure you want to save this New Tribe?')"class="art-button-green pull-right">      
                                    <?php  
                                     }
                                ?>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-8">
            <div class="box box-primary">
                <div class="box-header">
                    <h4 class="box-title">
                        Tribe List
                    </h4>
                </div>
                <div class="box-body" style="height:300px;overflow: auto">
                    <table class="table table-bordered table-hover table-striped">
                        <tr>
                            <th style="width: 50px">S/No.</th>
                            <th>Tribe Name</th>
                            <th style="width: 50px">EDIT</th>
                        </tr>
                        <?php 
                            $count=1;
                            $sql_select_idara_result=mysqli_query($conn,"SELECT * FROM tbl_tribe") or die(mysqli_error($conn));
                            if(mysqli_num_rows($sql_select_idara_result)>0){
                                while($idara_rows=mysqli_fetch_assoc($sql_select_idara_result)){
                                   $tribe_name= $idara_rows['tribe_name'];
									$tribe_id=$idara_rows['tribe_id'];
                                   echo "
                                       <tr>
                                        <td>$count</td>
                                        <td style='text-transform: capitalize;'>$tribe_name</td>
                                        <td>
                                            <form action='' method='POST'>
                                                <input type='text' value='$tribe_id' hidden='hidden' name='tribe_id'/>
                                                <input type='submit' value='EDIT' class='art-button-green' name='edit_btn'/>
                                            </form>
                                        </td>
                                       </tr>
                                         ";
                                   $count++;
                                }
                            }
                        ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
</fieldset>
<?php
include("./includes/footer.php");
?>