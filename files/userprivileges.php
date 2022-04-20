<?php
    include("./includes/connection.php");
    include("./includes/header.php");
    $controlforminput = '';
    $Control_Employee_Existance = '';
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo']['Setup_And_Configuration']) || isset($_SESSION['userinfo']['Appointment_Works'])){
	if($_SESSION['userinfo']['Setup_And_Configuration'] != 'yes' && $_SESSION['userinfo']['Appointment_Works'] != 'yes'){
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
    if($_SESSION['userinfo']['Setup_And_Configuration'] == 'yes' && !isset($_GET['HRWork'])){ 
        echo "<a href='employeepage.php?EmployeeManagement=EmployeeManagementThisPage' class='art-button-green'>BACK</a>";
    }
    $is_hr="";
    if(isset($_GET['HRWork']) && $_GET['HRWork']=='true'){ 
            echo "<a href='human_resource.php?HRWork=HRWorkThisPage' class='art-button-green'>BACK</a>";
            $is_hr='?HRWork=true';
        }
    }
    
    $control_Save_Button = 'active';
    if(isset($_GET['Employee_ID'])){
	$Employee_ID = $_GET['Employee_ID'];
    }else{
	$Employee_ID = 0;
	$Employee_Name = 'Undefined Employee Name'; 
	$Employee_Title = 'Undefined Employee Title';
	$control_Save_Button = 'inactive';
    }
?>
<!-- validate passwords entered-->
<script type="text/javascript">
    function validateForm() { 
        var Given_Password = document.forms["myForm"]["Given_Password"].value;
        var Given_Password2 = document.forms["myForm"]["Given_Password2"].value;
	if (Given_Password != Given_Password2){
            alert("     Passwords mismatch! Please fill information correctly     ");
            document.forms["myForm"]["Given_Password"].value = "";
            document.forms["myForm"]["Given_Password2"].value = ""; 
            document.getElementById("Given_Password").focus();
            return false;
        }
        else { 
            return true;
        }
    }
</script>
<?php
//check if this employee already assigned some priveleges
    $select_privileges = mysqli_query($conn,"select * from tbl_privileges where employee_id = '$Employee_ID'");
    $no = mysqli_num_rows($select_privileges);
    if($no > 0){
	$Control_Employee_Existance = 'yes';
    }
?>

<?php
    $selectThisRecord = mysqli_query($conn,"select * from tbl_employee where Employee_ID = '$Employee_ID'");
    $numberOfRecord = mysqli_num_rows($selectThisRecord);
    if($numberOfRecord > 0){
    while($row = mysqli_fetch_array($selectThisRecord)){ 
	$Employee_Name = $row['Employee_Name']; 
	$Employee_Title = $row['Employee_Title'];
    }
    }else{
	$Employee_Name = 'Undefined Employee Name'; 
	$Employee_Title = 'Undefined Employee Title';
	$control_Save_Button = 'inactive';
    }
?>
<form action='' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">
        <center>
            <fieldset>
                    <legend align="center"><b>USER REGISTRATION</b></legend>
                    <table width = 100%> 
                                <tr>
                                    <td width = 50%>
                                        <fieldset>
                                            <legend align="left"><b>Account Information</b>
                                            </legend>
                                            <table width=100%>
                                                    <tr>
                                                        <td width=40%>Employee Name</td>
                                                        <td><input type='text' name='Employee_Name' id='Employee_Name' disabled='disabled' value='<?php echo $Employee_Name; ?>'> </td>
                                                    </tr>
                                                    <tr>
                                                        <td width=40%>Job Title</td>
                                                        <td><input type='text' name='Job_Title' id='Job_Title' disabled='disabled' value='<?php echo $Employee_Title; ?>'> </td>
                                                    </tr>
						    
						    <?php if($control_Save_Button == 'active'){ ?>
							<tr>
							    <td width=40%>Given Username</td>
							    <td><input type='text' name='Given_Username' id='Given_Username' required='required'> </td>
							</tr>
							<tr>
							    <td width=40%>Given Password</td>
							    <td><input type='password' name='Given_Password' id='Given_Password' required='required'> </td>
							</tr>
							<tr>
							    <td width=40%>Re-Type Given Password</td>
							    <td><input type='password' name='Given_Password2' id='Given_Password2' required='required'> </td>
							</tr>
						    <?php }else{ ?>
							<tr>
							    <td width=40%>Given Username</td>
							    <td><input type='text' name='Given_Username' disabled='disabled id='Given_Username' required='required'> </td>
							</tr>
							<tr>
							    <td width=40%>Given Password</td>
							    <td><input type='password' name='Given_Password' disabled='disabled id='Given_Password' required='required'> </td>
							</tr>
							<tr>
							    <td width=40%>Re-Type Given Password</td>
							    <td><input type='password' name='Given_Password2' disabled='disabled id='Given_Password2' required='required'> </td>
							</tr>
						    <?php } ?>
                                                </table>

                                        </fieldset>
                                    </td>
                                    <td width = 50%>
					 
						    <fieldset>
							<legend align="left"><b>User Type Privileges</b></legend>
							<table width = 100%>
							    <tr>
								<td>
								    <input type='checkbox' name='Cash_Transactions' id='Cash_Transactions' value='yes'><label for="Cash_Transactions">Cash Transaction</label>
								</td>
                                </tr>
							    <tr>
								<td>
								    <input type='checkbox' name='Modify_Cash_Information' id='Modify_Cash_Information' value='yes'><label for="Modify_Cash_Information">This User Can Modify Cash Information / Transaction</label>
								</td>
							    </tr>
							    <tr>
								<td>
								    <input type='checkbox' name='Session_Master_Privelege' id='Session_Master_Privelege' value='yes'><label for="Session_Master_Privelege">Session Master Privelege</label>
								</td>
                                </tr>
							    <tr>
								<td>
								    <input type='checkbox' name='Modify_Credit_Information' id='Modify_Credit_Information' value='yes'><label for="Modify_Credit_Information">This User Can Modify Credit Information / Transaction</label>
								</td>
							    </tr>
							    <tr>
								<td>
								    <input type='checkbox' name='Setup_And_Configuration' id='Setup_And_Configuration' value='yes'><label for="Setup_And_Configuration">Setup And Configuration</label>
								</td>
                                </tr>
							    <tr>
								<td>
								    <input type='checkbox' name='Employee_Collection_Report' id='Employee_Collection_Report' value='yes'><label for="Employee_Collection_Report">Employee Collection Report</label>
								</td>
							    </tr>
							</table> 
						    </fieldset>    
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan=2>
                                        <fieldset>
                                            <legend align="left"><b>Dashboard Access Privileges</b></legend>
                                            <table width = 100%>
                                                <tr>
                                                    <td width=20%>
                                                        <input type='checkbox' name='Reception_Works' id='Reception_Works' value='yes'>
                                                            <label for='Reception_Works'>Reception Works</label>
                                                    </td>
                                                    <td width=20%>
                                                        <input type='checkbox' name='Revenue_Center_Works' id='Revenue_Center_Works' value='yes'>
                                                            <label for="Revenue_Center_Works">Revenue Center Works</label>
                                                    </td>
                                                    <td width=20%>
                                                        <input type='checkbox' name='Pharmacy' id='Pharmacy' value='yes'>
                                                            <label for="Pharmacy">Pharmacy Works</label>
                                                    </td> 
                                                    <td width=20%>
                                                        <input type='checkbox' name='Patients_Billing_Works' id='Patients_Billing_Works' value='yes'>
                                                            <label for="Patients_Billing_Works">Patients Billing Works</label>
                                                    </td>
                                                    <td width=20%>
                                                        <input type='checkbox' name='Management_Works' id='Management_Works' value='yes'>
                                                            <label for="Management_Works">Management Works</label>
                                                    </td>
                                                    
                                                </tr>
                                                <tr>
                                                    <td width=20%>
                                                        <input type='checkbox' name='Procurement_Works' id='Procurement_Works' value='yes'>
                                                            <label for="Procurement_Works">Procurement Works</label>
                                                    </td>
                                                    <td width=20%>
                                                        <input type='checkbox' name='Mtuha_Reports' id='Mtuha_Reports' value='yes'>
                                                            <label for="Mtuha_Reports">Mtuha Reports</label>
                                                    </td>
                                                    <td width=20%>
                                                        <input type='checkbox' name='Nurse_Station_Works' id='Nurse_Station_Works' value='yes'>
                                                            <label for="Nurse_Station_Works">Nurse Station Works</label>
                                                    </td> 
                                                    <td width=20%>
                                                        <input type='checkbox' name='General_Ledger' id='General_Ledger' value='yes'>
                                                            <label for="General_Ledger">General Ledger</label>
                                                    </td> 
                                                    <td width=20%>
                                                        <input type='checkbox' name='Dressing_Works' id='Dressing_Works' value='yes'>
                                                            <label for="Dressing_Works">Dressing Works</label>
                                                    </td> 
                                                    
                                                </tr>
                                                <tr>
                                                    <td width=20%>
                                                        <input type='checkbox' name='Dialysis_Works' id='Dialysis_Works' value='yes'>
                                                            <label for="Dialysis_Works">Dialysis Works</label>
                                                    </td> 
                                                    <td width=20%>
                                                        <input type='checkbox' name='Theater_Works' id='Theater_Works' value='yes'>
                                                            <label for="Theater_Works">Theater Works</label>
                                                    </td>
                                                    <td width=20%>
                                                        <input type='checkbox' name='Physiotherapy_Works' id='Physiotherapy_Works' value='yes'>
                                                            <label for="Physiotherapy_Works">Physiotherapy Works</label>
                                                    </td> 
                                                    <td width=20%>
                                                        <input type='checkbox' name='Maternity_Works' id='Maternity_Works' value='yes'>
                                                            <label for="Maternity_Works">Martenity Works</label>
                                                    </td>
                                                    <td width=20%>
                                                        <input type='checkbox' name='Dental_Works' id='Dental_Works' value='yes'>
                                                            <label for="Dental_Works">Dental Works</label>
                                                    </td> 
                                                </tr>
                                                <tr>
                                                    <td width=20%>
                                                        <input type='checkbox' name='Eye_Works' id='Eye_Works' value='yes'>
                                                            <label for="Eye_Works">Optical Works</label>
                                                    </td>
                                                    <td width=20%>
                                                        <input type='checkbox' name='Cecap_Works' id='Cecap_Works' value='yes'>
                                                            <label for="Cecap_Works">Cecap Works</label>
                                                    </td>
                                                    <td width=20%>
                                                        <input type='checkbox' name='Doctors_Page_Outpatient_Work' id='Doctors_Page_Outpatient_Work' value='yes'>
                                                            <label for="Doctors_Page_Outpatient_Work">Doctors Page Outpatient Work</label>
                                                    </td>
                                                    <td width=20%>
                                                        <input type='checkbox' name='Doctors_Page_Inpatient_Work' id='Doctors_Page_Inpatient_Work' value='yes'>
                                                            <label for="Doctors_Page_Inpatient_Work">Doctors Page Inpatient Work</label>
                                                    </td> 
                                                    <td width=20%>
                                                        <input type='checkbox' name='Admission_Works' id='Admission_Works' value='yes'>
                                                            <label for="Admission_Works">Admission Works</label>
                                                    </td>
                                                </tr>  
                                                <tr>
                                                    <td width=20%>
                                                        <input type='checkbox' name='Laboratory' id='Laboratory' value='yes'>
                                                            <label for="Laboratory">Laboratory Works</label>
                                                    </td>
                                                    <td width=20%>
                                                        <input type='checkbox' name='Radiology' id='Radiology' value='yes'>
                                                            <label for="Radiology">Radiology Works</label>
                                                    </td>
                                                    <td width=20%>
                                                        <input type='checkbox' name='Quality_Assurance' id='Quality_Assurance' value='yes'>
                                                            <label for="Quality_Assurance">Quality Assurance</label>
                                                    </td> 
                                                    <td width=20%>
                                                        <input type='checkbox' name='Storage_And_Supply_Work' id='Storage_And_Supply_Work' value='yes'>
                                                            <label for="Storage_And_Supply_Work">Storage And Supply</label>
                                                    </td>   
                                                    <td width=20%>
                                                        <input type='checkbox' name='Ear_Works' id='Ear_Works' value='yes'>
                                                            <label for="Ear_Works">Ear Work</label>
                                                    </td> 
                                                </tr>
                                                <tr>
                                                    <td width=20%>
                                                        <input type='checkbox' name='Rch_Works' id='Rch_Works' value='yes'>
                                                            <label for="Rch_Works">RCH Works</label>
                                                    </td>
                                                    <td width=20%>
                                                        <input type='checkbox' name='Hiv_Works' id='Hiv_Works' value='yes'>
                                                            <label for="Hiv_Works">HIV Works</label>
                                                    </td>
                                                    <td width=20%>
                                                        <input type='checkbox' name='Family_Planning_Works' id='Family_Planning_Works' value='yes'>
                                                            <label for="Family_Planning_Works">Family Planning Works</label>
                                                    </td>
                                                    <td width=20%>
                                                        <input type='checkbox' name='Morgue_Works' id='Morgue_Works' value='yes'>
                                                            <label for="Morgue_Works">Morgue Works</label>
                                                    </td> 
													<td width=20%>
                                                        <input type='checkbox' name='Blood_Bank_Works' id='Blood_Bank_Works' value='yes'>
                                                            <label for="Blood_Bank_Works">Blood Bank Works</label>
                                                    </td> 
                                                </tr>
                                                <tr>
                                                    <td width=20%>
                                                        <input type='checkbox' name='Food_And_Laundry_Works' id='Food_And_Laundry_Works' value='yes'>
                                                            <label for="Food_And_Laundry_Works">Food And Laundry Works</label>
                                                    </td>
                                                    <td width=20%>
                                                        <input type='checkbox' name='Appointment_Works' id='Appointment_Works' value='yes'>
                                                            <label for="Appointment_Works">Patient Appointment Works</label>
                                                    </td>
                                                    <td width=20%>
                                                        <input type='checkbox' name='Laboratory_Results_Validation' id='Laboratory_Results_Validation' value='yes'>
                                                            <label for="Laboratory_Results_Validation">Laboratory Results Validation</label>
                                                    </td>
                                                    <td width=20%>
                                                        <input type='checkbox' name='Patient_Transfer' id='Patient_Transfer' value='yes'>
                                                            <label for="Patient_Transfer">Patient Transfer</label>
                                                    </td>
                                                    <td width=20%>
                                                        <input type='checkbox' name='Patient_Record_Works' id='Patient_Record_Works' value='yes'>
                                                            <label for='Patient_Record_Works'>Patient Record Works</label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width=20%>
                                                        <input type='checkbox' name='Laboratory_Consulted_Patients' id='Laboratory_Consulted_Patients' value='yes'>
                                                            <label for='Laboratory_Consulted_Patients'>Laboratory Consulted Patients</label>
                                                    </td>
                                                    <td width=20%>
                                                        <input type='checkbox' name='Procedure_Works' id='Procedure_Works' value='yes'>
                                                            <label for='Procedure_Works'>Procedure Works</label>
                                                    </td>
                                                    <td width=20%>
                                                        <input type='checkbox' name='Approval_Orders' id='Approval_Orders' value='yes'>
                                                            <label for='Approval_Orders'>Approve Store Order</label>
                                                    </td>
                                                    <td width=20%>
                                                        <input type='checkbox' name='Engineering_Works' id='Engineering_Works' value='yes'>
                                                            <label for='Engineering_Works'>Engineering Works</label>
                                                    </td>
                                                    <td width=20% style="display: none">
                                                        <input type='checkbox' name='Msamaha_Works' id='Msamaha_Works' value='yes'>
                                                            <label for='Msamaha_Works'>Msamaha Works</label>
                                                    </td>
                                                </tr>
                                            </table> 
                                        </fieldset>
                                    </td>
                                </tr>
                                <tr>
				    <td>
					<table width = 100%>
					    <tr>
						<td style='text-align: right;'>Admin Username</td>
						<td><input type='text' name='Admin_Username' id='Admin_Username' placeholder='Enter Your Admin Username'></td>
					    </tr>
					    <tr>
						<td style='text-align: right;'>Admin Password</td>
						<td><input type='password' name='Admin_Password' id='Admin_Password' placeholder='Enter Your Admin Password'></td>
					    </tr>
					</table>
					
				     
				    </td>
                                    <td style='text-align: right;'><br/>
					<table width=100%>
					    <tr>
						<td style='vertical-align: middle;'>
						    &nbsp;&nbsp;&nbsp;
						    
						    <?php if($control_Save_Button == 'active'){ ?>
							<input type='submit' name='submit' id='submit' value='   SAVE   ' class='art-button-green'>&nbsp;&nbsp;&nbsp; 
							<input type='reset' name='clear' id='clear' value=' CLEAR ' class='art-button-green'>
						    
						    <?php //display link if this employee already added to the privileges table
							if($Control_Employee_Existance == 'yes') {
							    echo "<a href='assignaccessbranch.php?Employee_ID=".$Employee_ID."&AssignAccessBranch=AssignAccessBranchThisPage&HRWork=true' class='art-button-green'>CONTINUE</a>";
							}
						    ?>
						    
							<input type='hidden' name='submittedUserPrivelegesForm' value='true'/>
						    <?php }else{ ?>
							<button class='art-button-green'>INACTIVE SAVE</button>  
						    <?php } ?>
						</td>
					    </tr>
					</table>
                                        
                                    </td>
                                </tr>
                    </table>
            </fieldset>
        </center> 
</form>


  

<?php
	if(isset($_POST['submittedUserPrivelegesForm'])){ 
		    $Given_Username = $_POST['Given_Username'];
		    $Given_Password = md5($_POST['Given_Password']);
		    
			$Cash_Transactions = 'no';
			$Modify_Cash_Information = 'no';
			$Session_Master_Privelege = 'no';
			$Modify_Credit_Information = 'no';
			$Setup_And_Configuration = 'no';
			$Reception_Works = 'no';
			$Revenue_Center_Works = 'no';
			$Pharmacy = 'no';
			$Patients_Billing_Works = 'no';
			$Management_Works = 'no';
			$Procurement_Works = 'no';
			$Mtuha_Reports = 'no';
			$Nurse_Station_Works = 'no';
			$General_Ledger = 'no';
			$Dressing_Works = 'no';
			$Dialysis_Works = 'no';
			$Theater_Works = 'no';
			$Physiotherapy_Works = 'no';
			$Maternity_Works = 'no';
			$Dental_Works = 'no';
			$Eye_Works = 'no';
			$Cecap_Works = 'no';
			$Doctors_Page_Outpatient_Work = 'no';
			$Doctors_Page_Inpatient_Work = 'no';
			$Admission_Works = 'no';
			$Laboratory = 'no';
			$Radiology = 'no';
			$Quality_Assurance = 'no';
			$Storage_And_Supply_Work = 'no';
			$Ear_Works = 'no';
			$Employee_Collection_Report = 'no';
			$Rch_Works = 'no';
			$Hiv_Works = 'no';
			$Family_Planning_Works = 'no';
			$Morgue_Works = 'no';
			$Blood_Bank_Works = 'no';
			$Food_And_Laundry_Works = 'no';
			$Appointment_Works = 'no';
			$Laboratory_Results_Validation = 'no';
			$Patient_Transfer = 'no';
			$Patient_Record_Works = 'no';
            $Laboratory_Consulted_Patients = 'no';
            $Procedure_Works = 'no';
            $Approval_Orders = 'no';
            $Engineering_Works = 'no';
            $Msamaha_Works = 'no';
		    
		    if(isset($_POST['Cash_Transactions'])) { $Cash_Transactions = 'yes'; } 
		    if(isset($_POST['Modify_Cash_Information'])) { $Modify_Cash_Information = 'yes'; }
		    if(isset($_POST['Session_Master_Privelege'])) { $Session_Master_Privelege = 'yes'; }
		    if(isset($_POST['Modify_Credit_Information'])) { $Modify_Credit_Information = 'yes'; }
		    if(isset($_POST['Setup_And_Configuration'])) { $Setup_And_Configuration = 'yes'; }
		    if(isset($_POST['Reception_Works'])) { $Reception_Works = 'yes'; }
		    if(isset($_POST['Revenue_Center_Works'])) { $Revenue_Center_Works= 'yes'; }
		    if(isset($_POST['Pharmacy'])) { $Pharmacy = 'yes'; }
		    if(isset($_POST['Patients_Billing_Works'])) { $Patients_Billing_Works = 'yes'; }
		    if(isset($_POST['Management_Works'])) { $Management_Works = 'yes'; }
		    if(isset($_POST['Procurement_Works'])) { $Procurement_Works = 'yes'; }
		    if(isset($_POST['Mtuha_Reports'])) { $Mtuha_Reports = 'yes'; }
		    if(isset($_POST['Nurse_Station_Works'])) { $Nurse_Station_Works = 'yes'; }
		    if(isset($_POST['General_Ledger'])) { $General_Ledger = 'yes'; }
		    if(isset($_POST['Dressing_Works'])) { $Dressing_Works = 'yes'; }
		    if(isset($_POST['Dialysis_Works'])) { $Dialysis_Works = 'yes'; }
		    if(isset($_POST['Theater_Works'])) { $Theater_Works = 'yes'; }
		    if(isset($_POST['Physiotherapy_Works'])) { $Physiotherapy_Works = 'yes'; }
		    if(isset($_POST['Maternity_Works'])) { $Maternity_Works = 'yes'; }
		    if(isset($_POST['Dental_Works'])) { $Dental_Works = 'yes'; }
		    if(isset($_POST['Eye_Works'])) { $Eye_Works = 'yes'; }
		    if(isset($_POST['Cecap_Works'])) { $Cecap_Works = 'yes'; }
		    if(isset($_POST['Doctors_Page_Outpatient_Work'])) { $Doctors_Page_Outpatient_Work = 'yes'; }
		    if(isset($_POST['Doctors_Page_Inpatient_Work'])) { $Doctors_Page_Inpatient_Work = 'yes'; }
		    if(isset($_POST['Admission_Works'])) { $Admission_Works = 'yes'; }
		    if(isset($_POST['Laboratory'])) { $Laboratory = 'yes'; }
		    if(isset($_POST['Radiology'])) { $Radiology = 'yes'; }
		    if(isset($_POST['Quality_Assurance'])) { $Quality_Assurance = 'yes'; }
		    if(isset($_POST['Storage_And_Supply_Work'])) { $Storage_And_Supply_Work = 'yes'; } 
		    if(isset($_POST['Ear_Works'])) { $Ear_Works = 'yes'; } 
		    if(isset($_POST['Employee_Collection_Report'])) { $Employee_Collection_Report = 'yes'; } 
		    if(isset($_POST['Rch_Works'])) { $Rch_Works = 'yes'; }
		    if(isset($_POST['Hiv_Works'])) { $Hiv_Works = 'yes'; }
		    if(isset($_POST['Family_Planning_Works'])) { $Family_Planning_Works = 'yes'; }
		    if(isset($_POST['Morgue_Works'])) { $Morgue_Works = 'yes'; }
		    if(isset($_POST['Blood_Bank_Works'])) { $Blood_Bank_Works = 'yes'; }
		    if(isset($_POST['Food_And_Laundry_Works'])) { $Food_And_Laundry_Works = 'yes'; }
		    if(isset($_POST['Appointment_Works'])) { $Appointment_Works = 'yes'; }
		    if(isset($_POST['Laboratory_Results_Validation'])) { $Laboratory_Results_Validation = 'yes'; }
		    if(isset($_POST['Patient_Transfer'])) { $Patient_Transfer = 'yes'; }
            if(isset($_POST['Patient_Record_Works'])) { $Patient_Record_Works = 'yes'; }
            if(isset($_POST['Laboratory_Consulted_Patients'])) { $Laboratory_Consulted_Patients = 'yes'; }
            if(isset($_POST['Procedure_Works'])) { $Procedure_Works = 'yes'; }
            if(isset($_POST['Approval_Orders'])) { $Approval_Orders = 'yes'; }
            if(isset($_POST['Msamaha_Works'])) { $Msamaha_Works = 'yes'; }
            if(isset($_POST['Engineering_Works'])) { $Engineering_Works = 'yes'; }
		    
		    $sql = "insert into tbl_privileges(
				Employee_ID,Given_Username,Given_Password,Reception_Works,Revenue_Center_Works,Patients_Billing_Works,Procurement_Works,
				Mtuha_Reports,General_Ledger,Management_Works,Nurse_Station_Works,Admission_Works,Laboratory_Works,
				Radiology_Works,Quality_Assurance,Dressing_Works,Dialysis_Works,Theater_Works,Physiotherapy_Works,
				Maternity_Works,Dental_Works,Eye_Works,Cecap_Works,Modify_Cash_information,Session_Master_Priveleges,
				Cash_Transactions,Modify_Credit_Information,Setup_And_Configuration,
				Doctors_Page_Inpatient_Work,Doctors_Page_Outpatient_Work,Pharmacy,Storage_And_Supply_Work,Ear_Works,
				Employee_Collection_Report,Rch_Works,Hiv_Works,Family_Planning_Works,Morgue_Works,Blood_Bank_Works,Food_And_Laundry_Works,
				Appointment_Works,Laboratory_Result_Validation,Patient_Transfer,Patient_Record_Works,Laboratory_Consulted_Patients,Procedure_Works,Approval_Orders,Msamaha_Works,Engineering_Works
			   )
			   values(
				'$Employee_ID','$Given_Username','$Given_Password','$Reception_Works','$Revenue_Center_Works','$Patients_Billing_Works','$Procurement_Works',
				'$Mtuha_Reports','$General_Ledger','$Management_Works','$Nurse_Station_Works','$Admission_Works','$Laboratory',
				'$Radiology','$Quality_Assurance','$Dressing_Works','$Dialysis_Works','$Theater_Works','$Physiotherapy_Works',
				'$Maternity_Works','$Dental_Works','$Eye_Works','$Cecap_Works','$Modify_Cash_Information','$Session_Master_Privelege',
				'$Cash_Transactions','$Modify_Credit_Information','$Setup_And_Configuration',
				'$Doctors_Page_Inpatient_Work','$Doctors_Page_Outpatient_Work','$Pharmacy','$Storage_And_Supply_Work','$Ear_Works',
				'$Employee_Collection_Report','$Rch_Works','$Hiv_Works','$Family_Planning_Works','$Morgue_Works','$Blood_Bank_Works',
				'$Food_And_Laundry_Works','$Appointment_Works','$Laboratory_Results_Validation',
				'$Patient_Transfer','$Patient_Record_Works','$Laboratory_Consulted_Patients','$Procedure_Works','$Approval_Orders','$Msamaha_Works','$Engineering_Works'
			   )"; 
		
		//check if this employee already has privileges assigned
//		$sql_check = mysqli_query($conn,"select Employee_ID from tbl_privileges where Employee_ID = '$Employee_ID'") or die(mysqli_error($conn));
//		$num_rows = mysqli_num_rows($sql_check);
//		if($num_rows == 0){
//		    echo "<script type='text/javascript'>
//			       alert('PROCESS FAIL!! THIS EMPLOYEE ALREADY ASSIGNED PRIVILEGES. TRY TO EDIT WHEN NEEDED');
//			       document.location = './editemployee.php?Employee_ID=".$Employee_ID."&EditEmployee=EditEmployeeThisForm';
//			   </script>";
//		}else{
//		     
//		} 
                    
                    if(!mysqli_query($conn,$sql)){
			//if username already used by another employee 
			$error = '1062yes';
			
			if($Control_Employee_Existance == 'yes'){
			    echo "<script type='text/javascript'>
				    alert('Process Fail! This Employee already assigned privileges');
                                    document.location = './assignaccessbranch.php?Employee_ID=".$Employee_ID."&AssignAccessBranch=AssignAccessBranchThisPage".((isset($_GET['HRWork']) && $_GET['HRWork']=='true')?'&HRWork=true':'')."';
				    </script>";
			}elseif(mysql_errno()."yes" == $error){ 
			    echo "<script type='text/javascript'>
				    alert('    Process Fail! Username (".$Given_Username.") already assigned to another employee! Please use another name');
				    </script>";
			}else{
			    die(mysqli_error($conn));
			}		       
		   }else {
		       echo "<script type='text/javascript'>
			       alert('PRIVILEGES ASSIGNED SUCCESSFUL');
			       document.location = './assignaccessbranch.php?Employee_ID=".$Employee_ID."&AssignAccessBranch=AssignAccessBranchThisPage".((isset($_GET['HRWork']) && $_GET['HRWork']=='true')?'&HRWork=true':'')."';
			   </script>";
		       
		   } 
		    
		    
	} 
?>




<?php
    include("./includes/footer.php");
?>