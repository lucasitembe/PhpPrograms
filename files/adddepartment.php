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
    <a href='departmentpage.php?DepartmentPage=DepartmentPageThisPage' class='art-button-green'>
        BACK
    </a>
<?php } } ?>

<br/>
 <br/>
 <br/>
 <br/>
 <br/>
 <br/>
 <br/>
 <br/>

<?php
	if(isset($_POST['submittedAddNewDepartmentForm'])){
	    //get branch id to use as a foreign key
	    if(isset($_SESSION['userinfo']['Branch_ID'])){
		$Branch_ID = $_SESSION['userinfo']['Branch_ID'];
	    }else{
		$Branch_ID = 0;
	    }
	    
	    $Department_Name = mysqli_real_escape_string($conn,str_replace("  ", " ", $_POST['Department_Name']));
            $Department_Location = mysqli_real_escape_string($conn,$_POST['Department_Location']);
	    
	    if($Branch_ID != 0){
		$sql = "insert into tbl_department(Department_Name,Department_Location,Branch_ID)
			values ('$Department_Name','$Department_Location','$Branch_ID')";
		$sql_reslt=mysqli_query($conn,$sql) or die(mysqli_error($conn));
		if(!$sql_reslt){
		    //die(mysqli_error($conn));
				    $error = '1062yes';
				    if(mysqli_errno($conn)."yes" == $error){ 
					echo '<script>
						alert("Department Name Already Exist! Try Another Name");
						</script>';	
				    }else{
					echo '<script>
					    alert("Process Fail! Please Try Again");
					    </script>';	
				    }
		    }
		else {
		    echo '<script>
			alert("Department Added Successful");
			</script>';	
		}
	    }else{
		echo '<script>
			alert("Process Fail! No Branch Selected. Please Try Again");
			</script>';	
	    }
	}	     
?>
<center>
    <table width=60%><tr><td>
        <center>
            <fieldset>
                    <legend align="center" ><b>ADD NEW DEPARTMENT</b></legend>
                    <table>
                        <form action='#' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">
				<tr>
				    <td><b>Nature Of The Department</b></td>
				    <td>
					<select name='Department_Location' id='Department_Location' required='required'>
					    <option selected='selected'></option>
					    <option>Admission</option>
                                            <option>Blood Bank</option>
					    <option>Cecap</option>
					    <option>Dental</option>
					    <option>Dialysis</option>
					    <option>Dressing</option>
					    <option>Eram</option>
					    <option>Ear</option>
					    <option>Family Planning</option>
					    <option>Finance</option>
					    <option>HIV</option>
					    <option>Laboratory</option>
					    <option>Management</option>
					    <option>Maternity</option> 
					    <option>Nurse Station</option>
					    <option>Optical</option>
                        <option>Physiotherapy</option>
					    <option>Pharmacy</option>
					    <option>Procedure</option>
					    <option>Procurement</option>
					    <option>Quality Assuarance</option>
					    <option>Rch</option>
					    <option>Radiology</option>
					    <option>Reception</option>
					    <option>Revenue Center</option>
					    <option>Setup And Configuration</option>
					    <option>Storage And Supply</option>
					    <option>Surgery</option>
					    <option>Theater</option>
					    <option>Clinic</option>
					    <option>Deposit</option>
						<option value="Oncology">Oncology</option>
						<option value="Nuclearmedicine">Nuclear Medicine</option>
					</select>
				    </td>
				</tr>
				<tr>
                                    <td width=30%><b>Department Name</b></td>
                                    <td width=70%>
                                        <input type='text' name='Department_Name' required='required' autocomplete="off" size=70 id='Department_Name' placeholder='Enter Department Name'>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan=2 style='text-align: right;'>
                                        <input type='submit' name='submit' id='submit' value='   SAVE   ' class='art-button-green'>
                                        <input type='reset' name='clear' id='clear' value=' CLEAR ' class='art-button-green'>
                                        <input type='hidden' name='submittedAddNewDepartmentForm' value='true'/> 
                                    </td>
                                </tr>
                        </form></table>
            </fieldset>
        </center></td></tr></table>
</center>
<?php
    include("./includes/footer.php");
?>