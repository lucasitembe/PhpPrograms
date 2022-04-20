<?php
    include("./includes/connection.php");
    include("./includes/header.php");
    $controlforminput = '';
    $Title = '';
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    
    if(isset($_SESSION['userinfo']['Setup_And_Configuration']) || isset($_SESSION['userinfo']['Appointment_Works'])){
	if($_SESSION['userinfo']['Setup_And_Configuration'] != 'yes' && $_SESSION['userinfo']['Appointment_Works']!='yes'){
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
     
    //get registration id
    if(isset($_GET['Employee_ID'])){
        $Employee_ID = $_GET['Employee_ID'];
    }else{
        $Employee_ID = 0;
    }
?>



<?php
    if(isset($_POST['submittedchangeemployeepassword'])){
        
        $New_Password = md5($_POST['newpassword']);
		$Username = mysqli_real_escape_string($conn,$_POST['Username']);
		     
        $Admin_Username = $_POST['Admin_Username'];
        $Admin_Password = md5($_POST['Admin_Password']);
         
        $Current_Username = $_SESSION['userinfo']['Given_Username'];
        $Current_Password = $_SESSION['userinfo']['Given_Password'];
        
    $select_username = mysqli_query($conn,"SELECT Given_Username from tbl_privileges 
										where Given_username = '$Username' and 
											Employee_ID <> '$Employee_ID'") or die(mysqli_error($conn));
	$no = mysqli_num_rows($select_username);
	// echo $no;
	if($no > 0 ){
           // die("-------------------111111111111111111111111---------------");
		echo "<script>
				alert('Username Already Exists! Try Another Username');
				document.getElementById('Username').value = '';
				document.getElementById('Username').focus();
			</script>";
	}else{
           // die("-------------------22222222222222222222-----------------");
           
           
           if($Current_Username == $Admin_Username && $Current_Password == $Admin_Password){

				$update_query = "UPDATE tbl_privileges set Given_Password = '$New_Password', Given_Username = '$Username'
							where Employee_ID = '$Employee_ID'";
				
				if(!mysqli_query($conn,$update_query)){
					 echo "<script type='text/javascript'>
							alert('PROCESS FAIL! PLEASE TRY AGAIN');
							</script>";
				}else{
                                    //log the employee who change the password
                    $changed_by=$_SESSION['userinfo']['Employee_ID'];
                    mysqli_query($conn,"INSERT INTO tbl_change_passowrd_log(account_affected,changed_by,changed_date_time) VALUES('$Employee_ID','$changed_by',NOW())") or die(mysqli_error($conn));
                    echo "<script type='text/javascript'>
                        alert('CHANGES SAVED SUCCESSFULLY');
                        document.location = 'editemployee.php?Employee_ID=".$Employee_ID."&EditEmployee=EditEmployeeThisForm".((isset($_GET['HRWork']) && $_GET['HRWork']=='true')?'&HRWork=true':'')."';
                        </script>";
                    
				}
		}else{
			echo "<script>
				alert('Process Fail!. Invalid Admin Username OR Password');
			</script>";
		}
    }
	}
?>


<?php
    if(isset($_SESSION['userinfo'])){ 
        if(isset($_GET['HRWork']) && $_GET['HRWork']=='true'){
        echo "<a href='editemployee.php?Employee_ID=".$Employee_ID."&EditEmployee=EditEmployeeThisForm&HRWork=true' class='art-button-green'>BACK</a>";
    }else{
        echo "<a href='editemployee.php?Employee_ID=".$Employee_ID."&EditEmployee=EditEmployeeThisForm' class='art-button-green'>BACK</a>";
   }}?>


<script type="text/javascript">
    function validateForm() { 
        var newpassword = document.forms["changepass"]["newpassword"].value;
        var confirmpassword = document.forms["changepass"]["confirmpassword"].value;
	if (confirmpassword != newpassword){
            alert("New passwords mismatch! Please fill information correctly");
            document.forms["changepass"]["newpassword"].value = "";
            document.forms["changepass"]["confirmpassword"].value = ""; 
            document.getElementById("newpassword").focus();
            return false;
        }
        else { 
            return true;
        }
    }
</script>

<?php
    if(isset($_GET['Employee_ID'])){
        $Employee_ID = $_GET['Employee_ID'];
        $select_query = mysqli_query($conn,"select * from tbl_employee emp, tbl_department dep, tbl_privileges pr
                                        where emp.department_id = dep.department_id and
                                        pr.Employee_ID = emp.Employee_ID and
                                        emp.Employee_ID = '$Employee_ID'");
        while($row = mysqli_fetch_array($select_query)){
            $Employee_Name = $row['Employee_Name']; 
            $Employee_Title = $row['Employee_Title'];
            $Employee_Type = $row['Employee_Type'];
            $Employee_Branch_Name = $row['Employee_Branch_Name'];
            $Department_Name = $row['Department_Name'];
            $Given_Username = $row['Given_Username'];
        }
    }else{
        $Employee_Name = 'Unknown Employee';
        $Employee_Title = '';
        $Employee_Type = '';
        $Employee_Branch_Name = '';
        $Department_Name = '';
        $Given_Username = '';
    }
?>

<br/><br/><br/><br/>
<fieldset>  
            <legend align=center><b>CHANGE EMPLOYEE USERNAME/PASSWORD</b></legend>
        <center>
            <table width = 50%>
                <tr>
                    <td width=30% style='text-align: right;'>Employee Name </td>
                    <td><input type='text' disabled='disabled' value='<?php echo $Employee_Name; ?>'></td>
                </tr>
                <tr>
                    <td style='text-align: right;'>Employee Title </td>
                    <td><input type='text' disabled='disabled' value='<?php echo $Employee_Title; ?>'></td>
                </tr><tr>
                    <td style='text-align: right;'>Department</td>
                    <td><input type='text' disabled='disabled' value='<?php echo $Department_Name; ?>'></td>
                </tr> 
            </table>
            <tr/><tr/>
            <form name='changepass' action='#' method='post' onsubmit="return validateForm()">
            <table width = 50%>
                <tr>
                    <td width=30% style='text-align: right;'>Username</td>
                    <td><input type='text' name='Username' id='Username' autocomplete='off' required='required' placeholder = "Enter Username" value='<?php echo $Given_Username; ?>'></td>
                </tr>
                <tr>
                    <td width=30% style='text-align: right;'>New Password</td>
                    <td><input type='password' name='newpassword' id='newpassword' required='required' placeholder = "Enter New Password"/></td>
                </tr>
                <tr>
                    <td align='right' style='text-align: right;'>Confirm New Password</td>
                    <td><input type='password' name='confirmpassword' id='confirmpassword' required='required' placeholder = "Confirm New Password"/></td>
                </tr>
	    </table><br/><br/><br/>
	    <table width=50%>
		<tr>
		    <td width=30% style='text-align: right;'>Admin Username</td>
		    <td>
			<input type='text' name='Admin_Username' id='Admin_Username' autocomplete='off' required='required' placeholder='Enter Your Admin Username'>
		    </td>
		</tr>
		<tr>
		    <td style='text-align: right;'>Admin Password</td>
		    <td>
			<input type='password' name='Admin_Password' id='Admin_Password' required='required' placeholder='Enter Your Admin Password'>
		    </td>
		</tr>
                <tr>
                    <td style='text-align: center;' colspan=2>
                        <input type="submit" name="submittedchangeemployeepassword" id="submit" value="SAVE CHANGES" class='art-button-green'/>
                        <input type="reset" name="reset" id="reset" value="REFRESH" class='art-button-green' /> 
                        <input type='hidden' name='' value='true' />
                        <?php if(isset($_GET['HRWork']) && $_GET['HRWork']=='true'){
                            echo "<a href='editemployee.php?Employee_ID=".$Employee_ID."&EditEmployee=EditEmployeeThisForm&HRWork=true' class='art-button-green'>CANCEL</a>";
                        }else{
                            echo "<a href='editemployee.php?Employee_ID=".$Employee_ID."&EditEmployee=EditEmployeeThisForm' class='art-button-green'>CANCEL</a>";
                        }?>
                    </td>
                </tr> 
            </table>
            </form>
        </center>
</fieldset><br/>
<?php
    include("./includes/footer.php");
?>
