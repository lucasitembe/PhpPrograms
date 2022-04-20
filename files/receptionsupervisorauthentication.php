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
 <br/>
 <br/>
 <br/>



<center>
    <table width=70%><tr><td>
        <center>
            <fieldset>
                    <legend align="center" ><b>SUPERVISOR AUTHENTICATION</b></legend>
                    <table>
                        <form action='#' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">
                           
                                <tr>
                                    <td width=30% style='text-align: right;'><b>Supervisor Username</b></td>
                                    <td width=70%>
                                        <input type='text' name='Supervisor_Username' required='required' size=70 autocomplete='off' id='Supervisor_Username' placeholder='Supervisor Username'>
                                    </td>
                                </tr> 
                                <tr>
                                    <td style='text-align: right;'><b>Supervisor Password</b></td>
                                    <td width=70%>
                                        <input type='password' name='Supervisor_Password' required='required' size=70 id='Supervisor_Password' placeholder='Supervisor Password'>
                                    </td> 
                                </tr> 
                                <tr>
                                    <td width='100%' colspan='2' style='text-align:center;'>
                                        <input type='submit' name='submit' id='submit' 
										value='<?php echo 'ALLOW '.strtoupper($_SESSION['userinfo']['Employee_Name']).' TO PERFORM TRANSACTIONS'; ?>' class='art-button-green'>
                                        <input type='reset' name='clear' id='clear' value='CLEAR' class='art-button-green'> 
                                        <a href='./visitorform.php?TransactionAccessDenied=TransactionAccessDeniedThisPage' class='art-button-green'>CANCEL</a>
                                        <input type='hidden' name='submittedSupervisorInformationForm' value='true'/> 
                                    </td>
									
                                </tr>
                        </form></table>
            </fieldset>
        </center></td></tr></table>
</center>


<?php
	if(isset($_POST['submittedSupervisorInformationForm'])){
            $Supervisor_Username = mysqli_real_escape_string($conn,$_POST['Supervisor_Username']);
            $Supervisor_Password = mysqli_real_escape_string($conn,md5($_POST['Supervisor_Password']));
            
            $query="select * from tbl_branches b, tbl_branch_employee be, tbl_employee e, tbl_privileges p
                where b.branch_id = be.branch_id and e.employee_id = e.employee_id
                and e.employee_id = p.employee_id and p.Given_Username = '{$Supervisor_Username}' and
		p.Given_Password  = '{$Supervisor_Password}' and p.Session_Master_Priveleges = 'yes';
            ";
            
            //DML excution select from..
            $result= mysqli_query($conn,$query);
            $no=mysqli_num_rows($result);
            if($no>0){
                $row=mysqli_fetch_assoc($result);
                @session_start();
                $_SESSION['supervisor']=$row;
		
		//check if patient is cash
		
		if(isset($_GET['Registration_ID'])){
		    
		    $select_sponsor = mysqli_query($conn,"select Guarantor_Name from tbl_sponsor sp, tbl_patient_registration pr where
						    sp.Sponsor_ID = pr.Sponsor_ID and
							pr.Registration_ID = '$Registration_ID'");
		    
		    while($row2 = mysqli_fetch_array($select_sponsor)){
			$Guarantor_Name = $row2['Guarantor_Name'];
		    }
		    if(strtolower($Guarantor_Name) == 'cash'){
			if(strtolower($_SESSION['systeminfo']['Departmental_Collection']) == 'yes'){
			    if(strtolower($_SESSION['userinfo']['Cash_Transactions']) == 'yes'){
				echo '<script>
				    document.location = "./patientbillingreception.php?Registration_ID='.$Registration_ID.'&NR=True&PatientBillingReception=PatientBillingReceptionThisForm";
				    </script>';
			    }else{
				echo '<script>
				    document.location = "./patientbillingreception.php?Registration_ID='.$Registration_ID.'&NR=True&PatientBillingReception=PatientBillingReceptionThisForm";
				    </script>';					
			    }
			}else{
			    echo '<script>
				document.location = "./patientbillingprepare.php?Registration_ID='.$Registration_ID.'&NR=True&PatientBillingPrepare=PatientBillingPrepareThisForm";
				</script>';
			}
			//header("Location:./patientbillingprepare.php?Registration_ID=$Registration_ID&NR=True&PatientBillingPrepare=PatientBillingPrepareThisForm");
		    }else{
			header("Location:./patientbillingreception.php?Registration_ID=$Registration_ID&NR=True&SupervisorPrivileges=SupervisorPrivilegesThisPage");
		    }
		    
		}else{
		    header("Location:./patientbillingreception.php?Registration_ID=$Registration_ID&NR=True&SupervisorPrivileges=SupervisorPrivilegesThisPage");
		}
            }
            else { 
                echo "<script type='text/javascript'>
                                alert('INVALID INFORMATION OR NO PRIVILEGES TO SUPPORT');
                            </script>";
            }
	}
?>

<?php
    include("./includes/footer.php");
?>