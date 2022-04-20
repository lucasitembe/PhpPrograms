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
    <a href='clinicpage.php?ClinicConfiguration=ClinicConfigurationThisForm' class='art-button-green'>
        BACK
    </a>
<?php  } } ?>

<br/>
 <br/>
 <br/>
 <br/>
 <br/>
 <br/>
 <br/>
 <br/>
<?php
    if(isset($_GET['Clinic_Department_ID'])){
        $Clinic_Department_ID = $_GET['Clinic_Department_ID'];
    }else{
        $Clinic_Department_ID = 0;
    }
    
    //select clinic details
    $select_clinic_department = "select * from tbl_clinic_department where Clinic_Department_ID = '$Clinic_Department_ID'";
    $result = mysqli_query($conn,$select_clinic_department);
    $row = mysqli_fetch_array(($result));
    $Clinic_Department_Name = $row['Clinic_Department_Name'];
    $current_clinic_id=$row['Clinic_ID'];
    $current_clinic_name=mysqli_fetch_assoc(mysqli_query($conn,"SELECT Clinic_Name FROM tbl_clinic WHERE Clinic_ID=$current_clinic_id"))['Clinic_Name'];

?>
<?php
	if(isset($_POST['submittedEditClinicDepartmentForm'])){
        $Clinic_ID = mysqli_real_escape_string($conn,$_POST['Clinic_ID']);
        $Clinic_Department_Name = mysqli_real_escape_string($conn,$_POST['Clinic_Department_Name']);
	    $sql = "update tbl_clinic_department set Clinic_ID=$Clinic_ID, Clinic_Department_Name = '$Clinic_Department_Name' where Clinic_Department_ID = $Clinic_Department_ID";
	    
	    if(!mysqli_query($conn,$sql)){
				$error = '1062yes';
				if(mysql_errno()."yes" == $error){ 
				    echo '<script>
					    alert("Department Name Already Exist!");
					    </script>';	
				}else{
				    echo '<script>
					alert("Process Fail! Please Please Try Again");
					</script>';	
				}
		}
	    else {
		echo '<script>
		    alert("Department Updated Successfully");
		    document.location = "editclinicdepartmentlist.php?EditClinicDepartment=EditClinicDepartmentThisForm";
		    </script>';	
	    }
		
	}
?>
<center>
    <table width=70%><tr><td>
        <center>
            <fieldset>
                    <legend align="center" ><b>UPDATE CLINIC</b></legend>
                    <table>
                        <form action='#' method='post' name='myForm' id='myForm' enctype="multipart/form-data"> 
                        <tr>
                            <td width=30% style='text-align: right;'><b>Clinic Name</b></td>
                           
                                    <td width="15%">
                                   
                                        <select name="Clinic_ID" id="Clinic_ID" style='padding: 5px 0px 5px 0px;'>

                                        <?php //select clinic details
                                            echo "<option value='".$current_clinic_id."' selected>".$current_clinic_name."</option>";
                                            $select_clinic_name = "select * from tbl_clinic";
                                            $clinic_result = mysqli_query($conn,$select_clinic_name);
                                            
                                            while($row = mysqli_fetch_array($clinic_result)){
                                                echo "<option value='".$row['Clinic_ID']."'>".$row['Clinic_Name']."</option>";
                                            }
                                        ?>
                                        </select>
                                    </td>
                        </tr>
				<tr>
                                    <td width=30% style='text-align: right;'><b>Department Name</b></td>
                                    <td width=70%>
                                        <input type='text' name='Clinic_Department_Name' required='required' autocomplete='off' value='<?php echo htmlspecialchars($Clinic_Department_Name,ENT_QUOTES); ?>' size=70 id='Clinic_Name' placeholder='Enter Clinic Name'>
                                    </td>
                                     
                                </tr>
                                <tr>
                                    <td colspan=3 style='text-align: right;'>
                                        <input type='submit' name='submit' id='submit' value='   UPDATE   ' class='art-button-green'>
					<a href='editclinicdepartment.php?EditClinic=EditClinicThisForm' class='art-button-green'>CANCEL</a>
                                        <input type='hidden' name='submittedEditClinicDepartmentForm' value='true'/> 
                                    </td>
                                </tr>
                        </form>
		    </table>
            </fieldset>
        </center></td></tr></table>
</center>
<?php
    include("./includes/footer.php");
?>
<script type="text/javascript">
    $('#submit').on('click',function(){
        var department_name=document.myForm.Clinic_Department_Name.value;
        if(department_name.trim()===''){
            alert('WRITE THE DEPARTMENT NAME');
            return false;
        }
    });
</script>