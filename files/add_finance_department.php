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
<a href='departmentpage.php?Department=DepartmentThisPage' class='art-button'>
    BACK
</a>

<br/>
<?php 
    $feedback_message="";
    if(isset($_POST['save_btn'])){
        $finance_department_name=$_POST['finance_department_name'];
        $finance_department_code=$_POST['finance_department_code'];
        $sql_save_result=mysqli_query($conn,"INSERT INTO tbl_finance_department (finance_department_name,finance_department_code) VALUES('$finance_department_name','$finance_department_code')") or die(mysqli_error($conn));
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
        $finance_department_id=$_POST['finance_department_id'];
        $finance_department_name=$_POST['finance_department_name']; 
        $finance_department_code=$_POST['finance_department_code']; 
        $sql_insert_result=mysqli_query($conn,"UPDATE tbl_finance_department SET finance_department_name='$finance_department_name',finance_department_code='$finance_department_code' WHERE finance_department_id='$finance_department_id'") or die(mysqli_error($conn));
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
            $finance_department_id=$_POST['finance_department_id'];
            $sql_disable_diagnosis=mysqli_query($conn,"UPDATE tbl_finance_department SET enabled_disabled='disabled' WHERE finance_department_id='$finance_department_id'") or die(mysqli_error($conn));
            
            if($sql_disable_diagnosis){
                 $feedback_message="<div class='alert alert-success alert-dismissable'>
                                <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                                <strong>Success!</strong> you have disable FINANCE department successfully
                              </div>";
            }else{
                $feedback_message="<div class='alert alert-danger alert-dismissable'>
                                <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                                <strong>Error!</strong> Fail to disable FINANCE department..please try again
                              </div>"; 
            }
        }
        if($_POST['enable_btn']){
            $finance_department_id=$_POST['finance_department_id'];
            $sql_disable_FINANCE_department=mysqli_query($conn,"UPDATE tbl_finance_department SET enabled_disabled='enabled' WHERE finance_department_id='$finance_department_id'") or die(mysqli_error($conn));
            
            if($sql_disable_FINANCE_department){
                 $feedback_message="<div class='alert alert-success alert-dismissable'>
                                <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                                <strong>Success!</strong> you have enable FINANCE department successfully
                              </div>";
            }else{
                $feedback_message="<div class='alert alert-danger alert-dismissable'>
                                <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                                <strong>Error!</strong> Fail to enable enable FINANCE department..please try again
                              </div>"; 
            }
        }
        
        if(isset($_POST['edit_btn'])){
           $finance_department_id=$_POST['finance_department_id']; 
           $sql_select_idara_result=mysqli_query($conn,"SELECT *FROM tbl_finance_department WHERE finance_department_id='$finance_department_id'") or die(mysqli_error($conn));
                            if(mysqli_num_rows($sql_select_idara_result)>0){
                                while($idara_rows=mysqli_fetch_assoc($sql_select_idara_result)){
                                   $finance_department_name= $idara_rows['finance_department_name'];
                                   $finance_department_id=$idara_rows['finance_department_id'];
                                   $enabled_disabled=$idara_rows['enabled_disabled'];
                                   $finance_department_code=$idara_rows['finance_department_code'];
              } 
           }else{
              $feedback_message="<div class='alert alert-danger alert-dismissable'>
                                <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                                <strong>Error!</strong> Process Fail..try again
                              </div>";  
           }
        }else{
           $finance_department_name=""; 
           $finance_department_code=""; 
           $finance_department_id="";
           $enabled_disabled="";
        }
?>
<fieldset>  
    <legend align=center><b>FINANCE DEPARTMENT</b></legend>
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
                            <label class="col-md-3">Department Name</label>
                            <div class="col-md-9">
                                <input type="text" hidden="hidden" value="<?= $finance_department_id ?>" name="finance_department_id"/>
                                <input type="tex" class="form-control" required="" value="<?= $finance_department_name ?>" name="finance_department_name"placeholder="Enter Finance Department Name">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3">Department Code</label>
                            <div class="col-md-9">
                                <input type="tex" class="form-control" required="" value="<?= $finance_department_code ?>" name="finance_department_code"placeholder="Enter Finance Department Code">
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
                                    <input type="submit" value="SAVE" name="save_btn" onclick="return confirm('Are you sure you want to save this FINANCE Department?')"class="art-button pull-right">      
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
                        Finance Department List
                    </h4>
                </div>
                <div class="box-body" style="height:300px;overflow: auto">
                    <table class="table table-bordered table-hover table-striped">
                        <tr>
                            <th style="width: 50px">S/No.</th>
                            <th>FINANCE DEPARTMENT</th>
                            <th>DEPARTMENT CODE</th>
                            <th style="width: 50px">EDIT</th>
                            <th style="width: 200px">DISABLE/ENABLE</th>
                        </tr>
                        <?php 
                            $count=1;
                            $sql_select_idara_result=mysqli_query($conn,"SELECT *FROM tbl_finance_department") or die(mysqli_error($conn));
                            if(mysqli_num_rows($sql_select_idara_result)>0){
                                while($idara_rows=mysqli_fetch_assoc($sql_select_idara_result)){
                                   $finance_department_name= $idara_rows['finance_department_name'];
                                   $finance_department_id=$idara_rows['finance_department_id'];
                                   $enabled_disabled=$idara_rows['enabled_disabled'];
                                   $finance_department_code=$idara_rows['finance_department_code'];
                                   echo "
                                       <tr>
                                        <td>$count</td>
                                        <td>$finance_department_name</td>
                                        <td>$finance_department_code</td>
                                        <td>
                                            <form action='' method='POST'>
                                                <input type='text' value='$finance_department_id'hidden='hidden' name='finance_department_id'/>
                                                <input type='submit' value='EDIT' class='art-button' name='edit_btn'/>
                                            </form>
                                        </td>
                                        <td>
                                         <form action='' method='POST'>
                                                        <input type='text' name='finance_department_id' value='$finance_department_id' hidden='hidden'>";
                                    
                                                        if($enabled_disabled=="enabled"){
                                                           echo "<input type='submit' name='disable_btn' value='DISABLE FINANCE DEPARTMENT' class='btn art-button btn-sm'>";  
                                                        }else{
                                                           echo "<input type='submit' name='enable_btn' value='ENABLE FINANCE DEPARTMENT' class='btn btn-danger btn-block btn-sm'>"; 
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