<?php
    include("./includes/connection.php");
    include("./includes/header.php");
    $controlforminput = '';
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    
    if(isset($_SESSION['userinfo']['Reception_Works'])){
	if($_SESSION['userinfo']['Reception_Works'] != 'yes'){
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    
    if(isset($_GET['Registration_ID'])){
	$Registration_ID = $_GET['Registration_ID'];    
    }else{
	$Registration_ID = '';
    }
?>
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
                where b.branch_id = be.branch_id and e.employee_id = e.employee_id
                and e.employee_id = p.employee_id and p.Given_Username = '{$Supervisor_Username}' and
		p.Given_Password  = '{$Supervisor_Password}' and p.Session_Master_Priveleges = 'yes';
            ";
            
            // DML excution select from..
            $result= mysqli_query($conn,$query);
            $no=mysqli_num_rows($result);
            if($no>0){
                $row=mysqli_fetch_assoc($result);
                @session_start();
                $_SESSION['supervisor']=$row;
                header("Location:./bloodworkpage.php?Registration_ID=$Registration_ID&NR=True&SupervisorPrivileges=SupervisorPrivilegesThisPage");
            }
            else { 
                echo "<script type='text/javascript'>
                                alert('INVALID INFORMATION OR NO PRIVILEGES TO SUPPORT');
                            </script>";
            }
	} 
?>

<center>
    <table width=45%><tr><td>
        <center>
            <fieldset>
                    <legend align="center" ><b>SUPERVISOR</b></legend>
                    <table>
                        <form action='#' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">
                           
                                <tr>
                                    <td width=40% style="text-align:right;"><b>Username</b></td>
                                    <td width=70%>
                                        <input type='text' name='Supervisor_Username' required='required' size=70 id='Supervisor_Username' placeholder='Supervisor Username'>
                                    </td>
                                </tr> 
                                <tr>
                                    <td style="text-align:right;"><b>Password</b></td>
                                    <td width=70%>
                                        <input type='password' name='Supervisor_Password' required='required' size=70 id='Supervisor_Password' placeholder='Supervisor Password'>
                                    </td> 
                                </tr> 
								<tr>
									<td style="text-align:right;"><b>Sub Department</b></td>
									
                               
                                <td>
                                    <!--<select name='Sub_Department_ID' id='Sub_Department_ID'>-->
                                        <select name='Sub_Department_Name' id='Sub_Department_Name' required='required'>
                                            <option selected='selected'></option>
                                            <?php
                                                if(isset($_SESSION['userinfo']['Employee_ID'])){
                                                    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
                                                }
                                                $select_sub_departments = mysqli_query($conn,"select Sub_Department_Name from
                                                                                tbl_department dep, tbl_sub_department sdep,tbl_employee_sub_department ed
                                                                                    where dep.department_id = sdep.department_id and
                                                                                        ed.Employee_ID = '$Employee_ID' and
                                                                                            ed.Sub_Department_ID = sdep.Sub_Department_ID and
                                                                                            Department_Location = 'Blood Bank'
                                                                                        ");
                                                while($row = mysqli_fetch_array($select_sub_departments)){
                                                    echo "<option>".$row['Sub_Department_Name']."</option>";
                                                }
                                            
                                            ?>
                                        </select>
                                </td>
                            </tr>
                                <tr>
                                    <td colspan=2 style='text-align: center;'>
                                        <input type='submit' name='submit' id='submit' value='<?php echo 'ALLOW '.strtoupper($_SESSION['userinfo']['Employee_Name']); ?>' class='art-button-green'>
                                        <input type='reset' name='clear' id='clear' value='CLEAR' class='art-button-green'> 
                                        <a href='./index.php?TransactionAccessDenied=TransactionAccessDeniedThisPage' class='art-button-green'>CANCEL</a>
                                        <input type='hidden' name='submittedSupervisorInformationForm' value='true'/> 
                                    </td>
                                </tr>
                        </form></table>
            </fieldset>
        </center></td></tr></table>
</center>
<?php
    include("./includes/footer.php");
?>