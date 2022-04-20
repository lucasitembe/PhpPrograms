<?php
    include("./includes/connection.php");
    include("./includes/header.php");
    $controlforminput = '';
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    
    if(isset($_SESSION['userinfo']['Setup_And_Configuration'])){
	if($_SESSION['userinfo']['Setup_And_Configuration'] != 'yes'){
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
     
?>
<a href='departmentpage.php?Department=DepartmentThisPage' class='art-button-green'>
    BACK
</a>

<br />
<?php 
    $feedback_message="";
    if(isset($_POST['save_btn'])){
        $idara_name=$_POST['idara_name'];
        $sql_save_result=mysqli_query($conn,"INSERT INTO tbl_idara_kuu (idara_name) VALUES('$idara_name')") or die(mysqli_error($conn));
        if($sql_save_result){
           $feedback_message="<div class='alert alert-success alert-dismissable'>
                                <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                                <strong>Success!</strong> Saved Successfully
                              </div>"; 
        }else{
             $feedback_message="<div class='alert alert-danger alert-dismissable'>
                                <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                                <strong>Error!</strong> Saving Fail Please Try Again
                              </div>"; 
        }
    }
  
    if(isset($_POST['save_changes_btn'])){
        $idara_id=$_POST['idara_id'];
        $idara_name=$_POST['idara_name']; 
        $sql_insert_result=mysqli_query($conn,"UPDATE tbl_idara_kuu SET idara_name='$idara_name' WHERE idara_id='$idara_id'") or die(mysqli_error($conn));
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
    
    
        if($_POST['disable_btn']){
            $idara_id=$_POST['idara_id'];
            $sql_disable_diagnosis=mysqli_query($conn,"UPDATE tbl_idara_kuu SET enabled_disabled='disabled' WHERE idara_id='$idara_id'") or die(mysqli_error($conn));
            
            if($sql_disable_diagnosis){
                 $feedback_message="<div class='alert alert-success alert-dismissable'>
                                <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                                <strong>Success!</strong> you have disable main department successfully
                              </div>";
            }else{
                $feedback_message="<div class='alert alert-danger alert-dismissable'>
                                <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                                <strong>Error!</strong> Fail to disable main department..please try again
                              </div>"; 
            }
        }
        if($_POST['enable_btn']){
            $idara_id=$_POST['idara_id'];
            $sql_disable_main_department=mysqli_query($conn,"UPDATE tbl_idara_kuu SET enabled_disabled='enabled' WHERE idara_id='$idara_id'") or die(mysqli_error($conn));
            
            if($sql_disable_main_department){
                 $feedback_message="<div class='alert alert-success alert-dismissable'>
                                <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                                <strong>Success!</strong> you have enable main department successfully
                              </div>";
            }else{
                $feedback_message="<div class='alert alert-danger alert-dismissable'>
                                <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                                <strong>Error!</strong> Fail to enable enable main department..please try again
                              </div>"; 
            }
        }
        
        if(isset($_POST['edit_btn'])){
           $idara_id=$_POST['idara_id']; 
           $sql_select_idara_result=mysqli_query($conn,"SELECT *FROM tbl_idara_kuu WHERE idara_id='$idara_id'") or die(mysqli_error($conn));
                            if(mysqli_num_rows($sql_select_idara_result)>0){
                                while($idara_rows=mysqli_fetch_assoc($sql_select_idara_result)){
                                   $idara_name= $idara_rows['idara_name'];
                                   $idara_id=$idara_rows['idara_id'];
                                   $enabled_disabled=$idara_rows['enabled_disabled'];
              } 
           }else{
              $feedback_message="<div class='alert alert-danger alert-dismissable'>
                                <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                                <strong>Error!</strong> Process Fail..try again
                              </div>";  
           }
        }else{
           $idara_name=""; 
           $idara_id="";
           $enabled_disabled="";
        }
?>
<fieldset>
    <legend align=center><b>MAIN DEPARTMENT</b></legend>
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
                            <label class="col-md-3">Idara Name</label>
                            <div class="col-md-9">
                                <input type="text" hidden="hidden" value="<?= $idara_id ?>" name="idara_id" />
                                <input type="tex" class="form-control" required="" value="<?= $idara_name ?>"
                                    name="idara_name" placeholder="Enter Main Department Name">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12">
                                <?php 
                                     if(isset($_POST['edit_btn'])){
                                        ?>
                                <input type="submit" value="SAVE CHANGES" name="save_changes_btn"
                                    onclick="return confirm('Are you sure you want to save changes?')"
                                    class="btn btn-success pull-right">
                                <?php 
                                     }else{
                                       ?>
                                <input type="submit" value="SAVE" name="save_btn"
                                    onclick="return confirm('Are you sure you want to save this Main Department?')"
                                    class="art-button-green pull-right">
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
                        Main Department List
                    </h4>
                </div>
                <div class="box-body" style="height:300px;overflow: auto">
                    <table class="table table-bordered table-hover table-striped">
                        <tr>
                            <th style="width: 50px">S/No.</th>
                            <th>MAIN DEPARTMENT</th>
                            <th style="width: 50px">EDIT</th>
                            <th style="width: 200px">DISABLE/ENABLE</th>
                        </tr>
                        <?php 
                            $count=1;
                            $sql_select_idara_result=mysqli_query($conn,"SELECT *FROM tbl_idara_kuu") or die(mysqli_error($conn));
                            if(mysqli_num_rows($sql_select_idara_result)>0){
                                while($idara_rows=mysqli_fetch_assoc($sql_select_idara_result)){
                                   $idara_name= $idara_rows['idara_name'];
                                   $idara_id=$idara_rows['idara_id'];
                                   $enabled_disabled=$idara_rows['enabled_disabled'];
                                   echo "
                                       <tr>
                                        <td>$count</td>
                                        <td>$idara_name</td>
                                        <td>
                                            <form action='' method='POST'>
                                                <input type='text' value='$idara_id'hidden='hidden' name='idara_id'/>
                                                <input type='submit' value='EDIT' class='art-button-green' name='edit_btn'/>
                                            </form>
                                        </td>
                                        <td>
                                         <form action='' method='POST'>
                                                        <input type='text' name='idara_id' value='$idara_id' hidden='hidden'>";
                                    
                                                        if($enabled_disabled=="enabled"){
                                                           echo "<input type='submit' name='disable_btn' value='DISABLE MAIN DEPARTMENT' class='btn art-button btn-sm'>";  
                                                        }else{
                                                           echo "<input type='submit' name='enable_btn' value='ENABLE MAIN DEPARTMENT' class='btn btn-danger btn-block btn-sm'>"; 
                                                        }
                                                        
                                                                echo "
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