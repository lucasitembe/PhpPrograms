<?php
include("./includes/header.php");
include("./includes/connection.php");
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (!isset($_SESSION['Procedure_Supervisor'])) {
    header("Location: ./deptsupervisorauthentication.php?SessionCategory=Procedure&InvalidSupervisorAuthentication=yes");
}

if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Procedure_Works'])) {
        if ($_SESSION['userinfo']['Procedure_Works'] != 'yes') {
            header("Location: ./index.php?InvalidPrivilege=yes");
        }
    } else {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
?>
<a href="Procedure.php?PatientsBillingWorks=PatientsBillingWorks" class="art-button-green">BACK</a>
<br/>
<br/>

<?php 
    $feedback_message="";
    if(isset($_POST['save_btn'])){
        $diagnosis_name=$_POST['diagnosis_name'];
        $diagnosis_code=$_POST['diagnosis_code'];
        $sql_insert_result=mysqli_query($conn,"INSERT INTO tbl_procedure_diagnosis (procedure_dignosis_name,procedure_dignosis_code) VALUES('$diagnosis_name','$diagnosis_code')") or die(mysqli_error($conn));
           if($sql_insert_result){
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
        $diagnosis_name=$_POST['diagnosis_name'];
        $diagnosis_code=$_POST['diagnosis_code'];
        $procedure_diagnosis_id=$_POST['procedure_diagnosis_id']; 
        $sql_insert_result=mysqli_query($conn,"UPDATE tbl_procedure_diagnosis SET procedure_dignosis_name='$diagnosis_name',procedure_dignosis_code='$diagnosis_code' WHERE procedure_diagnosis_id='$procedure_diagnosis_id'") or die(mysqli_error($conn));
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
            $procedure_diagnosis_id=$_POST['procedure_diagnosis_id'];
            $sql_disable_diagnosis=mysqli_query($conn,"UPDATE tbl_procedure_diagnosis SET disable_enable='disabled' WHERE procedure_diagnosis_id='$procedure_diagnosis_id'") or die(mysqli_error($conn));
            
            if($sql_disable_diagnosis){
                 $feedback_message="<div class='alert alert-success alert-dismissable'>
                                <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                                <strong>Success!</strong> you have disable diagnosis successfully
                              </div>";
            }else{
                $feedback_message="<div class='alert alert-danger alert-dismissable'>
                                <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                                <strong>Error!</strong> Fail to disable diagnosis..please try again
                              </div>"; 
            }
        }
        if($_POST['enable_btn']){
            $procedure_diagnosis_id=$_POST['procedure_diagnosis_id'];
            $sql_disable_diagnosis=mysqli_query($conn,"UPDATE tbl_procedure_diagnosis SET disable_enable='enabled' WHERE procedure_diagnosis_id='$procedure_diagnosis_id'") or die(mysqli_error($conn));
            
            if($sql_disable_diagnosis){
                 $feedback_message="<div class='alert alert-success alert-dismissable'>
                                <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                                <strong>Success!</strong> you have enable diagnosis successfully
                              </div>";
            }else{
                $feedback_message="<div class='alert alert-danger alert-dismissable'>
                                <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                                <strong>Error!</strong> Fail to enable diagnosis..please try again
                              </div>"; 
            }
        }
        
        if(isset($_POST['edit_btn'])){
           $procedure_diagnosis_id=$_POST['procedure_diagnosis_id']; 
           $sql_select_diagnosis_to_edit=mysqli_query($conn,"SELECT *FROM tbl_procedure_diagnosis WHERE procedure_diagnosis_id='$procedure_diagnosis_id'") or die(mysqli_error($conn));
           if(mysqli_num_rows($sql_select_diagnosis_to_edit)>0){
              while($dignos_row=mysqli_fetch_assoc($sql_select_diagnosis_to_edit)){
                  $procedure_dignosis_name=$dignos_row['procedure_dignosis_name'];
                  $procedure_dignosis_code=$dignos_row['procedure_dignosis_code'];
                  $procedure_diagnosis_id=$dignos_row['procedure_diagnosis_id'];
              } 
           }else{
              $feedback_message="<div class='alert alert-danger alert-dismissable'>
                                <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                                <strong>Error!</strong> Process Fail..try again
                              </div>";  
           }
        }else{
           $procedure_dignosis_code=""; 
           $procedure_dignosis_name="";
           $procedure_diagnosis_id="";
        }
?>
<fieldset>
    <legend align="center">
        PROCEDURE DIAGNOSIS
    </legend>
    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header">
                    <?= $feedback_message ?>
                </div>
                <div class="box-body">
                    <form action="" method="POST" class="form-horizontal">
                        <div class="form-group">
                            <label class="col-md-4"><b>Diagnosis Name</b></label> 
            
                            <div class="col-md-8">
                                <input type="text" name="diagnosis_name" required="" id="diagnosis_name"class="form-control"value="<?= $procedure_dignosis_name ?>" placeholder="Enter Diagnosis name">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4"><b>Diagnosis code</b></label>
                            <div class="col-md-8">
                                <input type="text" name="diagnosis_code" id="diagnosis_code"class="form-control" placeholder="Enter Diagnosis code" value="<?= $procedure_dignosis_code ?>">
                            </div>
                        </div>
                        <div>
                            <input type='text' name='procedure_diagnosis_id' value='<?= $procedure_diagnosis_id ?>' hidden='hidden'>
                                    
                            <?php 
                                 if(isset($_POST['edit_btn'])){
                                    ?>
                            <input type="submit" value="SAVE CHANGES" name="save_changes_btn" onclick="return confirm('Are you sure you want to save changes to this this diagnosis?')"class="art-button-green pull-right">
                                        <?php 
                                 }else{
                                   ?>
                                <input type="submit" value="SAVE" name="save_btn" onclick="return confirm('Are you sure you want to save this diagnosis?')"class="art-button-green pull-right">      
                                <?php  
                                 }
                            ?>
                            
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-1"></div>
        <div class="col-md-10">
            <div class="box box-primary">
                <div class="box-header">
                    <div class="col-md-6"><h4 class="box-title">LIST OF SAVED DIAGNOSIS</h4></div>
                    <div class="col-md-6">
                        <input type="text" name="search_diagnosis" id="search_diagnosis" onkeyup="search_diagnosis()" placeholder="Search..." class="form-control">
                    </div>
                </div>
                <div class="box-body" style="height:300px;overflow: auto" id="procedure_diagnosis_list_body">
                    <table class="table table-bordered">
                        <tr>
                            <th style="width:50px">S/No.</th>
                            <th>DIAGNOSIS NAME</th>
                            <th>DIAGNOSIS CODE</th>
                            <th style="width: 50px">EDIT</th>
                            <th style="width: 50px">DISABLE/ENABLE</th>
                        </tr>
                        <?php 
                            $count=1;
                            $sql_select_saved_diagnosis_result=mysqli_query($conn,"SELECT *FROM tbl_procedure_diagnosis ORDER BY procedure_diagnosis_id DESC LIMIT 20") or die(mysqli_error($conn));
                            if(mysqli_num_rows($sql_select_saved_diagnosis_result)>0){
                                while($diagnosis_rows=mysqli_fetch_assoc($sql_select_saved_diagnosis_result)){
                                    $procedure_diagnosis_id=$diagnosis_rows['procedure_diagnosis_id'];
                                    $procedure_dignosis_name=$diagnosis_rows['procedure_dignosis_name'];
                                    $procedure_dignosis_code=$diagnosis_rows['procedure_dignosis_code'];
                                    $disable_enable=$diagnosis_rows['disable_enable'];
                                    echo "
                                            <tr>
                                                <td>$count</td>
                                                <td>$procedure_dignosis_name</td>
                                                <td>$procedure_dignosis_code</td>
                                                <td>
                                                    <form action='' method='POST'>
                                                        <input type='text' name='procedure_diagnosis_id' value='$procedure_diagnosis_id' hidden='hidden'>
                                                        <input type='submit' name='edit_btn' value='Edit' class='art-button-green'>
                                                    </form>
                                                </td>
                                                <td>
                                                    <form action='' method='POST'>
                                                        <input type='text' name='procedure_diagnosis_id' value='$procedure_diagnosis_id' hidden='hidden'>";
                                    
                                                        if($disable_enable=="enabled"){
                                                           echo "<input type='submit' name='disable_btn' value='Enabled' class='btn btn-success btn-sm'>";  
                                                        }else{
                                                           echo "<input type='submit' name='enable_btn' value='Disabled' class='btn btn-danger btn-sm'>"; 
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
<script>
    function search_diagnosis(){
       var search_diagnosis= $("#search_diagnosis").val()
       $.ajax({
           type:'GET',
           url:'search_procedure_diagnosis.php',
           data:{search_diagnosis:search_diagnosis},
           success:function(data){
               $("#procedure_diagnosis_list_body").html(data)
           }
       });
    }
</script>

<?php
include("./includes/footer.php");
?>