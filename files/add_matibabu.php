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
<a href='mtuha_book_11.php' class='art-button-green'>
    BACK
</a>

<br/>
<?php 
    $feedback_message="";
    if(isset($_POST['save_btn'])){
        $name_of_treatment=$_POST['name_of_treatment'];
        $sql_save_result=mysqli_query($conn,"INSERT INTO tbl_mtuha_treatment (name_of_treatment) VALUES('$name_of_treatment')") or die(mysqli_error($conn));
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
        $Treatment_ID=$_POST['Treatment_ID'];
        $name_of_treatment=$_POST['name_of_treatment']; 
        $sql_insert_result=mysqli_query($conn,"UPDATE tbl_mtuha_treatment SET name_of_treatment='$name_of_treatment' WHERE Treatment_ID='$Treatment_ID'") or die(mysqli_error($conn));
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
            $Treatment_ID=$_POST['Treatment_ID'];
            $sql_disable_diagnosis=mysqli_query($conn,"UPDATE tbl_mtuha_treatment SET enabled_disabled='disabled' WHERE Treatment_ID='$Treatment_ID'") or die(mysqli_error($conn));
            
            if($sql_disable_diagnosis){
                 $feedback_message="<div class='alert alert-success alert-dismissable'>
                                <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                                <strong>Success!</strong> you have disable main treatment successfully
                              </div>";
            }else{
                $feedback_message="<div class='alert alert-danger alert-dismissable'>
                                <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                                <strong>Error!</strong> Fail to disable main treatment..please try again
                              </div>"; 
            }
        }
        if($_POST['enable_btn']){
            $Treatment_ID=$_POST['Treatment_ID'];
            $sql_disable_main_department=mysqli_query($conn,"UPDATE tbl_mtuha_treatment SET enabled_disabled='enabled' WHERE Treatment_ID='$Treatment_ID'") or die(mysqli_error($conn));
            
            if($sql_disable_main_department){
                 $feedback_message="<div class='alert alert-success alert-dismissable'>
                                <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                                <strong>Success!</strong> you have enable main treatment successfully
                              </div>";
            }else{
                $feedback_message="<div class='alert alert-danger alert-dismissable'>
                                <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                                <strong>Error!</strong> Fail to enable enable main treatment..please try again
                              </div>"; 
            }
        }
        
        if(isset($_POST['edit_btn'])){
           $Treatment_ID=$_POST['Treatment_ID']; 
           $sql_select_idara_result=mysqli_query($conn,"SELECT *FROM tbl_mtuha_treatment WHERE Treatment_ID='$Treatment_ID'") or die(mysqli_error($conn));
                            if(mysqli_num_rows($sql_select_idara_result)>0){
                                while($idara_rows=mysqli_fetch_assoc($sql_select_idara_result)){
                                   $name_of_treatment= $idara_rows['name_of_treatment'];
                                   $Treatment_ID=$idara_rows['Treatment_ID'];
                                   $enabled_disabled=$idara_rows['enabled_disabled'];
              } 
           }else{
              $feedback_message="<div class='alert alert-danger alert-dismissable'>
                                <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                                <strong>Error!</strong> Process Fail..try again
                              </div>";  
           }
        }else{
           $name_of_treatment=""; 
           $Treatment_ID="";
           $enabled_disabled="";
        }
?>
<fieldset>  
    <legend align=center><b>MAIN TREATMENT</b></legend>
    <div class="row">
        <div class="col-md-12"> 
            <div class="col-md-offset-3 col-md-6 col-md-offset-3">
                <div class="box box-primary">
                    <div class="box-header">
                        <?= $feedback_message ?>
                    </div>
                    <div class="box-body">
                        <form action="" method="POST" class="form-horizontal">
                            <div class="form-group">
                                <label class="col-md-4">Treatment Name</label>
                                <div class="col-md-8">
                                    <input type="text" hidden="hidden" value="<?= $Treatment_ID ?>" name="Treatment_ID"/>
                                    <input type="tex" class="form-control" required="" value="<?= $name_of_treatment ?>" name="name_of_treatment"placeholder="Enter Main Treatment Name">
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
                                        <input type="submit" value="SAVE" name="save_btn" onclick="return confirm('Are you sure you want to save this Main Treatment?')"class="art-button-green pull-right">      
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
            </div>
            <div class="row">
            <div class="col-md-12">
            <div class="col-md-offset-2 col-md-8 col-md-offset-2">
                <div class="box box-primary">
                    <div class="box-header">
                        <h4 class="box-title">
                            Main Treatment List
                        </h4>
                    </div>
                    <div class="box-body" style="height:300px;overflow: auto">
                        <table class="table table-bordered table-hover table-striped">
                            <tr>
                                <th style="width: 50px">S/No.</th>
                                <th>MAIN TREATMENT</th>
                                <th style="width: 50px">EDIT</th>
                                <th style="width: 200px">DISABLE/ENABLE</th>
                            </tr>
                            <?php 
                                $count=1;
                                $sql_select_idara_result=mysqli_query($conn,"SELECT *FROM tbl_mtuha_treatment") or die(mysqli_error($conn));
                                if(mysqli_num_rows($sql_select_idara_result)>0){
                                    while($idara_rows=mysqli_fetch_assoc($sql_select_idara_result)){
                                    $name_of_treatment= $idara_rows['name_of_treatment'];
                                    $Treatment_ID=$idara_rows['Treatment_ID'];
                                    $enabled_disabled=$idara_rows['enabled_disabled'];
                                    echo "
                                        <tr>
                                            <td>$count</td>
                                            <td>$name_of_treatment</td>
                                            <td>
                                                <form action='' method='POST'>
                                                    <input type='text' value='$Treatment_ID'hidden='hidden' name='Treatment_ID'/>
                                                    <input type='submit' value='EDIT' class='art-button-green' name='edit_btn'/>
                                                </form>
                                            </td>
                                            <td>
                                            <form action='' method='POST'>
                                                            <input type='text' name='Treatment_ID' value='$Treatment_ID' hidden='hidden'>";
                                        
                                                            if($enabled_disabled=="enabled"){
                                                            echo "<input type='submit' name='disable_btn' value='DISABLE MAIN TREATMENT' class='btn art-button btn-sm'>";  
                                                            }else{
                                                            echo "<input type='submit' name='enable_btn' value='ENABLE MAIN TREATMENT' class='btn btn-danger btn-block btn-sm'>"; 
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
    </div>
   
</fieldset>
<?php
include("./includes/footer.php");
?>