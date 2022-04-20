<?php
    include("./includes/connection.php");
    include("./includes/header.php");
    $controlforminput = '';
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    $section = '';
    if(isset($_GET['section'])){
    $section = $_GET['section'];
    }
    if(isset($_SESSION['userinfo']['Admission_Works'])){
	if($_SESSION['userinfo']['Admission_Works'] != 'yes'){
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_GET['status'])){
	$status = $_GET['status'];
    }else{
	$status='';
    }
?>
<br/>
 <br/>
 <br/>
 <br/>
 <br/>
 <br/>
 <br/>
 <br/>
<?php
	if(isset($_POST['submittedSupervisorInformationForm'])){
            $Supervisor_Username = mysqli_real_escape_string($conn,$_POST['Supervisor_Username']);
            $Supervisor_Password = mysqli_real_escape_string($conn,md5($_POST['Supervisor_Password']));
            
            $query="select * from tbl_branches b, tbl_branch_employee be, tbl_employee e, tbl_privileges p
			where b.branch_id = be.branch_id and
			    be.employee_id = e.employee_id and
				e.employee_id = p.employee_id and
				    p.Given_Username = '$Supervisor_Username' and
					p.Given_Password  = '$Supervisor_Password' and
					    p.Session_Master_Priveleges = 'yes';
            ";
            //DML excution select from..
            $result= mysqli_query($conn,$query);
            $no=mysqli_num_rows($result);
	     
            if($no>0){
                $row=mysqli_fetch_assoc($result);
                @session_start();
                $_SESSION['admissionsupervisor']=$row;
                if($status=='discharge'){
		header("Location:./discharge.php?section=Admission&PatientFile=PatientFileThisPage&SupervisorPrivileges=SupervisorPrivilegesThisPage");    
		}else{
		header("Location:./admit.php?section=Admission&PatientFile=PatientFileThisPage&SupervisorPrivileges=SupervisorPrivilegesThisPage");    
		}
            }
            else { 
                echo "<script type='text/javascript'>
                                alert('INVALID INFORMATION OR NO PRIVILEGES TO SUPPORT');
                            </script>";
            }
	}
?>

<center>
    <table width="60%"><tr><td>
        <center>
            <fieldset>
                    <legend align="center" ><b>ADMISSION SUPERVISOR AUTHENTICATION</b></legend>
                        <form action='#' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">
			    <table>
						<tr>
							<td width="30%" style="text-align:right;">Supervisor Username</td>
							<td width="70%">
								<input type='text' name='Supervisor_Username' required='required' size=70 id='Supervisor_Username' placeholder='Supervisor Username'>
							</td>
						</tr> 
                                <tr>
                                    <td style="text-align:right;">Supervisor Password</td>
                                    <td width=70%>
                                        <input type='password' name='Supervisor_Password' required='required' size=70 id='Supervisor_Password' placeholder='Supervisor Password'>
                                    </td> 
                                </tr> 
                                <tr>
                                    <td colspan=2 style='text-align: center;'>
                                        <input type='submit' name='submit' id='submit' value='<?php echo 'ALLOW '.strtoupper($_SESSION['userinfo']['Employee_Name']).' TO PERFORM ADMISSION WORKS'; ?>' class='art-button-green'>
                                        <input type='reset' name='clear' id='clear' value='CLEAR' class='art-button-green'> 
                                        <a href='./admissionworkspage.php?TransactionAccessDenied=TransactionAccessDeniedThisPage' class='art-button-green'>CANCEL</a>
                                        <input type='hidden' name='submittedSupervisorInformationForm' value='true'/> 
                                    </td>
                                </tr>
				</table>
                        </form>
            </fieldset>
        </center></td></tr></table>
</center>



<?php
    include("./includes/footer.php");
?>