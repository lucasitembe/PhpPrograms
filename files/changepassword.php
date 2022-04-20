<?php
    include("./includes/connection.php");
    include("./includes/header.php");
?> 
  
<script type="text/javascript">
    function validateForm() {
        var oldpassword = document.forms["changepass"]["oldpassword"].value;
        var newpassword = document.forms["changepass"]["newpassword"].value;
        var confirmpassword = document.forms["changepass"]["confirmpassword"].value;
        if (confirmpassword != newpassword){
            alert("New passwords mismatch! Please fill information correctly");
            document.forms["changepass"]["newpassword"].value = "";
            document.forms["changepass"]["confirmpassword"].value = "";
            document.forms["changepass"]["oldpassword"].value = "";
            document.getElementById("oldpassword").focus();
            return false;
        } else {
            return true;
        }
    }
</script>
<br/><br/><br/>
 <?php
	if(isset($_POST['submittedchangepassword'])){
		$username = mysqli_real_escape_string($conn,$_POST['username']);
		$oldpassword = mysqli_real_escape_string($conn,md5($_POST['oldpassword']));
		$newpassword = mysqli_real_escape_string($conn,md5($_POST['newpassword']));
		
                if($oldpassword==$newpassword){
                   echo "<script>
			alert('Your old and new passwords are the same,please choose a different password!');
			</script>";
                }else{
		$Select_User_Info = mysqli_query($conn,"select *,b.branch_id as branch_id from tbl_branches b, tbl_branch_employee be, tbl_employee e, tbl_privileges p, tbl_department dep
                where b.branch_id = be.branch_id and
		e.employee_id = be.employee_id and
		dep.department_id = e.department_id
                and e.employee_id = p.employee_id and p.Given_Password = '$oldpassword' and p.Given_Username = '$username'");
		$Number_Of_Rows_Affected = mysqli_num_rows($Select_User_Info); 
                while($row = mysqli_fetch_array($Select_User_Info)){
                    $Given_Username = $row['Given_Username'];
		    $Given_Password = $row['Given_Password'];
		    $Employee_ID = $row['Employee_ID'];
                    $branch_id=$row['branch_id'];
                    
                }
                
                
                $confirmNewPassword=$_POST['newpassword'];
                $passwordLegth=  strlen($confirmNewPassword);

		if($Number_Of_Rows_Affected == 1 && $oldpassword == $Given_Password && $username = $Given_Username){
                    
                    $sysConfig=  mysqli_query($conn,"SELECT minimum_password_length,alphanumeric_password FROM tbl_system_configuration WHERE Branch_ID='$branch_id'");
                    $sysrows = mysqli_fetch_assoc($sysConfig);
                    $minpassLength=$sysrows['minimum_password_length'];
                    $alphanumeric=$sysrows['alphanumeric_password'];
                    if(($alphanumeric=='yes')&& (!preg_match('/^(?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z]/', $confirmNewPassword)) || ($minpassLength!=0 && $passwordLegth<$minpassLength)){                       
                       echo "<script>
						alert('Your Password must be alphanumeric and at least ".$minpassLength." characters long');
						</script>";
		
                   } else {
                    mysqli_query($conn,"update tbl_privileges set Given_Password = '$newpassword',Last_Password_Change=NOW(),Changed_first_login='yes'
				    where Employee_ID = '$Employee_ID'
					and Given_Username = '$Given_Username'
					    and Given_Password = '$Given_Password'");
						
							echo "<script type='text/javascript'>
                    alert('Your Password Changed Successfully');
                 </script>";
                        
                header("location:../index.php");          
                     }
                    
		} else {
		    echo "<script>
			alert('Process Fail! Please Provide Valid Information');
			</script>";
		}
                
                }
	} 
?>


<center>
    <table width=50%>
	<tr>
	    <td>
             <center>
                
                     <?php
                     if(isset($_GET['ChangePassword'])){
                         if($_GET['ChangePassword']=='changeFirstPassword'){
                             echo '<p style="font-size:16px;font-weight:bold;color:rgb(255,0,0)">Change your password and login again!</p>'; 
                         }
                      }
                     
                     ?>
                
             </center>
		<fieldset>
                   
                    <legend align="center" ><b>CHANGE PASSWORD</b></legend>
		    <center>
			 <form name='changepass' action='#' method='post' onsubmit="return validateForm()">
			    <table>
				<tr>
				    <td align='right'><b>USERNAME</b></td>
				    <td><input type='text' size=30 name='username' required='required' id='username' placeholder = "Enter Your Username"/></td>
				</tr>
				<tr>
				    <td align='right'><b>OLD PASSWORD</b></td>
				    <td><input type='password' size=30 name='oldpassword' required='required' id='oldpassword' placeholder = "Enter Your Old Password"/></td>
				</tr>
				<tr>
				    <td align='right'><b>NEW PASSWORD</b></td>
				    <td><input type='password' size=30 name='newpassword' id='newpassword' required='required' placeholder = "Enter New Password"/></td>
				</tr>
				<tr>
				    <td align='right'><b>CONFIRM NEW PASSWORD</b></td>
				    <td><input type='password' size=30 name='confirmpassword' id='confirmpassword' required='required' placeholder = "Confirm New Password"/></td>
				</tr>
				<tr>
				    <td style='text-align: right;' colspan=2>
					<input type="submit" name="submit" id="submit" value="CHANGE PASSWORD" class='art-button-green'/>
					<input type="reset" name="reset" id="reset" value="RESET" class='art-button-green' /> 
					<input type='hidden' name='submittedchangepassword' value='true' />
					<a href='../index.php' class='art-button-green'>GO TO LOGIN</a>
				    </td>
				</tr> 
			    </table>
			 </form>
		    </center>
		</fieldset>
	    </td>
	</tr>
    </table>
    
</center>
 
 

<?php
    include("./includes/footer.php");
?>