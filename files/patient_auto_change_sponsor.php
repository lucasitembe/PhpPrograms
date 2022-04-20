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
<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Setup_And_Configuration'] == 'yes'){ 
?>
    <a href='sponsorpage.php?SponsorConfiguration=SponsorConfigurationThisPage' class='art-button-green'>
        BACK
    </a>
<?php  } } ?>

<?php 
////save information

if(isset($_POST['save_btn'])){ 
    $from_sponsor_id=$_POST['from_sponsor_id'];
    $to_sponsor_id=$_POST['to_sponsor_id'];
    $sql_check_if_arleady_assigned_result=mysqli_query($conn,"SELECT from_sponsor_id FROM tbl_patient_auto_sponsor_change WHERE from_sponsor_id='$from_sponsor_id'") or die(mysqli_error($conn));
    if(mysqli_num_rows($sql_check_if_arleady_assigned_result)>0){
        $sql_update_selected_sponsor_result=mysqli_query($conn,"UPDATE tbl_patient_auto_sponsor_change SET to_sponsor_id='$to_sponsor_id' WHERE from_sponsor_id='$from_sponsor_id'") or die(mysqli_error($conn));
        if($sql_update_selected_sponsor_result){
          ?>  <script>alert('Saved Successfully') </script><?php
        }else{
           ?>  <script>alert('Saving Fail...Try Again') </script><?php  
        }
    }else{
        $sql_insert_sponsor_result=mysqli_query($conn,"INSERT INTO tbl_patient_auto_sponsor_change (from_sponsor_id,to_sponsor_id) VALUES('$from_sponsor_id','$to_sponsor_id')") or die(mysqli_error($conn));
    
        if($sql_insert_sponsor_result){
          ?>  <script>alert('Saved Successfully') </script><?php
        }else{
           ?>  <script>alert('Saving Fail...Try Again') </script><?php  
        }
    }
    
}
if(isset($_POST['delete_btn'])){
    $patient_auto_sponsor_change_id=$_POST['patient_auto_sponsor_change_id'];
    $sql_delete_configured_sponsor_result=mysqli_query($conn,"DELETE FROM tbl_patient_auto_sponsor_change WHERE patient_auto_sponsor_change_id='$patient_auto_sponsor_change_id'") or die(mysqli_error($conn));

    if($sql_delete_configured_sponsor_result){
          ?>  <script>alert('Deleted Successfully') </script><?php
    }else{
       ?>  <script>alert('Deleting Fail...Try Again') </script><?php  
    }
}

?>
<br/><br/>
<fieldset>
    <legend align=center><b>PATIENT AUTO SPONSOR CHANGER</b></legend>
    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-8">
            <div class="box box-primary">
                <div class="header">
                    <h4 class="box-title" style="text-align: center">
                        This setting change patient sponsor automatically when check in and visit type is Normal Visit
                    </h4>
                </div>
                <div class="box-body">
                    <form class="form-horizontal" action="" method="POST">
                        <div class="form-group">
                            <label class="col-md-4">Change From Sponsor</label>
                            <div class="col-md-8">
                                <select class="form-control" name="from_sponsor_id" id="from_sponsor_id">
                                    <option value="">Select Sponsor</option>
                                    <?php 
                                        $sql_select_sponsor_result=mysqli_query($conn,"SELECT Sponsor_ID,Guarantor_Name FROM tbl_sponsor") or die(mysqli_error($conn));
                                        if(mysqli_num_rows($sql_select_sponsor_result)>0){
                                            while($sponsor_rows=mysqli_fetch_assoc($sql_select_sponsor_result)){
                                              $Sponsor_ID=$sponsor_rows['Sponsor_ID'];
                                              $Guarantor_Name=$sponsor_rows['Guarantor_Name'];
                                              echo "<option value='$Sponsor_ID'>$Guarantor_Name</option>";
                                            }
                                        }
                                     ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4">Change To Sponsor</label>
                            <div class="col-md-8">
                                <select class="form-control" name="to_sponsor_id" id="to_sponsor_id">
                                    <option value="">Select Sponsor</option>
                                     <?php 
                                        $sql_select_sponsor_result=mysqli_query($conn,"SELECT Sponsor_ID,Guarantor_Name FROM tbl_sponsor") or die(mysqli_error($conn));
                                        if(mysqli_num_rows($sql_select_sponsor_result)>0){
                                            while($sponsor_rows=mysqli_fetch_assoc($sql_select_sponsor_result)){
                                              $Sponsor_ID=$sponsor_rows['Sponsor_ID'];
                                              $Guarantor_Name=$sponsor_rows['Guarantor_Name'];
                                              echo "<option value='$Sponsor_ID'>$Guarantor_Name</option>";
                                            }
                                            }
                                         ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12">
                                <input type="submit" value="SAVE"onclick="return validate_sponsor()" name="save_btn"class="art-button-green pull-right">
                            </div>
                        </div>
                    </form>
                    <div class="row" style="height: 300px;overflow-y: auto;overflow-x: hidden">
                        <div class="col-md-12">
                             
                              
                             <table class="table table-striped">
                                <tr>
                                    <th colspan="4">Configured Sponsor</th>
                                </tr>
                                <tr>
                                    <th style="width: 50px">S/No.</th>
                                    <th>FROM SPONSOR</th>
                                    <th>TO SPONSOR</th>
                                    <th style="width: 50px">DELETE</th>
                                </tr>
                                <?php 
                                    $from_sponsor_name="";
                                    $to_sponsor_name="";
                                    $count=0;
                                    $sql_select_list_of_configured_sponsor_result=mysqli_query($conn,"SELECT *FROM tbl_patient_auto_sponsor_change") or die(mysqli_error($conn));
                                    if(mysqli_num_rows($sql_select_list_of_configured_sponsor_result)>0){
                                        while($p_auto_sp_rows=mysqli_fetch_assoc($sql_select_list_of_configured_sponsor_result)){
                                            $from_sponsor_id=$p_auto_sp_rows['from_sponsor_id'];
                                            $to_sponsor_id=$p_auto_sp_rows['to_sponsor_id'];
                                            $patient_auto_sponsor_change_id=$p_auto_sp_rows['patient_auto_sponsor_change_id'];
                                            $sql_select_from_sponsor_name_result=mysqli_query($conn,"SELECT Guarantor_Name FROM tbl_sponsor WHERE Sponsor_ID='$from_sponsor_id'") or die(mysqli_error($conn));
                                        
                                            if(mysqli_num_rows($sql_select_from_sponsor_name_result)>0){
                                               $from_sp_row=mysqli_fetch_assoc($sql_select_from_sponsor_name_result);
                                               $from_sponsor_name=$from_sp_row['Guarantor_Name'];
                                            }
                                            $sql_select_to_sponsor_name_result=mysqli_query($conn,"SELECT Guarantor_Name FROM tbl_sponsor WHERE Sponsor_ID='$to_sponsor_id'") or die(mysqli_error($conn));
                                        
                                            if(mysqli_num_rows($sql_select_to_sponsor_name_result)>0){
                                               $to_sp_row=mysqli_fetch_assoc($sql_select_to_sponsor_name_result);
                                               $to_sponsor_name=$to_sp_row['Guarantor_Name'];
                                            }
                                            $count++;
                                            echo "<tr>
                                                    <td>$count</td>
                                                    <td>$from_sponsor_name</td>
                                                    <td>$to_sponsor_name</td>
                                                    <td>
                                                        <form action='' method='POST'>
                                                            <input type='text' name='patient_auto_sponsor_change_id' hidden='hidden' value='$patient_auto_sponsor_change_id'>
                                                            <input type='submit' value='Delete' name='delete_btn' onclick='return confirm(\"Are you sure you want to delete this\")' class='art-button-green'/>
                                                        </form>
                                                    </td>
                                            </tr>";
                                        }
                                    }
                                 ?>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</fieldset>
<script>
    function validate_sponsor(){
        var from_sponsor_id=$("#from_sponsor_id").val();
        var to_sponsor_id=$("#to_sponsor_id").val();
        if(from_sponsor_id==""||to_sponsor_id==""){
            alert("Select Sponsor First")
            return false;
        }
        if(confirm("Are you sure you want to save?")){
            return true;   
        }
        return false;
    }
    $(document).ready(function(){
        $("#from_sponsor_id").select2();
        $("#to_sponsor_id").select2();
    })
</script>
<?php
    include("./includes/footer.php");
?>