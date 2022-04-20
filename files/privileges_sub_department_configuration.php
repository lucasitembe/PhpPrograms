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
<br/>
<br/>
<fieldset>
    <legend align='center'><b>PRIVILEGES SUB DEPARTMENT CONFIGURATION</b></legend>
    <div class="row">
        <div class="col-md-6">
            <div class="box box-primary" style="height:400px;overflow-y: scroll">
                <div class="box-header">
                    <h4>
                        List Of All Sub Department
                    </h4>
                </div>
                <div class="box-body">
                    <table class="table">
                        <tr>
                            <th width="50px">S/No.</th>
                            <th>Sub Department Name</th>
                            <th width="100px">Action</th>
                        </tr>
                        <?php 
                         $sql_select_subdepartment_result=mysqli_query($conn,"SELECT Sub_Department_Name,Sub_Department_ID FROM tbl_sub_department WHERE Sub_Department_Status='active'") or (mysqli_error($conn));
                         if(mysqli_num_rows($sql_select_subdepartment_result)>0){
                             $count=1;
                             while($sub_d_rows=mysqli_fetch_assoc($sql_select_subdepartment_result)){
                                 $Sub_Department_Name=$sub_d_rows['Sub_Department_Name'];
                                 $Sub_Department_ID=$sub_d_rows['Sub_Department_ID'];
                                 echo "<tr>
                                            <td>$count</td>
                                            <td>$Sub_Department_Name</td>
                                            <td>
                                                <input type='button' class='art-button-green' onclick='assign_high_privileges_to_this_sub_d($Sub_Department_ID)' value='ASSIGN'/>
                                            </td>
                                       </tr>";
                                 $count++;
                             }
                         }
                         ?>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="box box-primary" style="height:400px;overflow-y: scroll">
                <div class="box-header">
                    <h4>List of high privileges sub departments</h4>
                </div>
                <div class="box-body" id="high_privileges_sub_d">
                    <?php 
                        require "high_privileges_sub_department.php";
                    ?>
                </div>
            </div>
        </div>
    </div>
</fieldset>
<script>
    function assign_high_privileges_to_this_sub_d(Sub_Department_ID){
        $.ajax({
            type:'GET',
            url:'assign_high_privileges_to_this_sub_d.php',
            data:{Sub_Department_ID:Sub_Department_ID},
            success:function(data){
                $("#high_privileges_sub_d").html(data);
            }
        });
    }
    function remove_high_privileges_to_this_sub_d(Sub_Department_ID){
        $.ajax({
            type:'GET',
            url:'remove_high_privileges_to_this_sub_d.php',
            data:{Sub_Department_ID:Sub_Department_ID},
            success:function(data){
                $("#high_privileges_sub_d").html(data);
            }
        });
    }
</script>
<?php
    include("./includes/footer.php");
?>

