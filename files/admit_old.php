<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Admission_Works'])){
	    if($_SESSION['userinfo']['Admission_Works'] != 'yes'){
		header("Location: ./index.php?InvalidPrivilege=yes");
	    }
	}else{
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	    header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(!isset($_SESSION['admissionsupervisor'])){
	header("Location: admissionsupervisorauthentication.php?section=Admission&Authenticate=SuperUser&Activity=Active");
    }
    $section = '';
    if(isset($_GET['section'])){
    $section = $_GET['section'];
    }else{
        
    }
    $Admission_Number = '';
//get the current date
		$Today_Date = mysqli_query($conn,"select now() as today");
		while($row = mysqli_fetch_array($Today_Date)){
		    $original_Date = $row['today'];
		    $new_Date = date("Y-m-d", strtotime($original_Date));
		    $Today = $new_Date; 
		}
//    select patient information
    if(isset($_GET['Registration_ID'])){ 
        $Registration_ID = $_GET['Registration_ID']; 
        $select_Patient = mysqli_query($conn,"select
                            Old_Registration_Number,Title,Patient_Name,pr.Sponsor_ID,
                                Second_Name,Last_Name,Date_Of_Birth,
                                    Gender,pr.Region,pr.District,pr.Ward,
                                        Member_Number,Member_Card_Expire_Date,
                                            pr.Phone_Number,Email_Address,Occupation,
                                                Employee_Vote_Number,Emergence_Contact_Name,
                                                    Emergence_Contact_Number,Company,Registration_ID,
                                                        Employee_ID,Registration_Date_And_Time,Guarantor_Name,Claim_Number_Status,
                                                        Registration_ID
                                      
                                      
                                      
                                      
                                      from tbl_patient_registration pr, tbl_sponsor sp 
                                        where pr.Sponsor_ID = sp.Sponsor_ID and 
                                              Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
        $no = mysqli_num_rows($select_Patient);
        
        if($no>0){
            while($row = mysqli_fetch_array($select_Patient)){
                $Registration_ID = $row['Registration_ID'];
                $Old_Registration_Number = $row['Old_Registration_Number'];
                $Title = $row['Title'];
                $First_Name = $row['Patient_Name'];
                $Sponsor_ID = $row['Sponsor_ID'];
                $Second_Name = $row['Second_Name'];
                $Last_Name = $row['Last_Name'];
                $Date_Of_Birth = $row['Date_Of_Birth'];
                $Gender = $row['Gender'];
                $Region = $row['Region'];
                $District = $row['District'];
                $Ward = $row['Ward'];
                $Guarantor_Name = $row['Guarantor_Name'];
		$Claim_Number_Status = $row['Claim_Number_Status'];
                $Member_Number = $row['Member_Number'];
                $Member_Card_Expire_Date = $row['Member_Card_Expire_Date'];
                $Phone_Number = $row['Phone_Number'];
                $Email_Address = $row['Email_Address'];
                $Occupation = $row['Occupation'];
                $Employee_Vote_Number = $row['Employee_Vote_Number'];
                $Emergence_Contact_Name = $row['Emergence_Contact_Name'];
                $Emergence_Contact_Number = $row['Emergence_Contact_Number'];
                $Company = $row['Company'];
                $Employee_ID = $row['Employee_ID'];
                $Registration_Date_And_Time = $row['Registration_Date_And_Time'];
               // echo $Ward."  ".$District."  ".$Ward; exit;
            }
            $age = floor( (strtotime(date('Y-m-d')) - strtotime($Date_Of_Birth)) / 31556926)." Years";
	    if($age == 0){
		$date1 = new DateTime($Today);
		$date2 = new DateTime($Date_Of_Birth);
		$diff = $date1 -> diff($date2);
		$age = $diff->m." Months";
	    }
	    if($age == 0){
		$date1 = new DateTime($Today);
		$date2 = new DateTime($Date_Of_Birth);
		$diff = $date1 -> diff($date2);
		$age = $diff->d." Days";
	    }
        }else{
            $Registration_ID = '';
            $Old_Registration_Number = '';
            $Title = '';
            $First_Name = '';
            $Sponsor_ID = '';
            $Second_Name = '';
            $Last_Name = '';
            $Date_Of_Birth = '';
            $Gender = '';
            $Region = '';
            $District = '';
            $Ward = '';
            $Guarantor_Name = '';
            $Claim_Number_Status = '';
	    $Member_Number = '';
            $Member_Card_Expire_Date = '';
            $Phone_Number = '';
            $Email_Address = '';
            $Occupation = '';
            $Employee_Vote_Number = '';
            $Emergence_Contact_Name = '';
            $Emergence_Contact_Number = '';
            $Company = '';
            $Employee_ID = '';
            $Registration_Date_And_Time = '';
	    $age =0;
        }
    }else{
            $Registration_ID = '';
            $Old_Registration_Number = '';
            $Title = '';
            $First_Name = '';
            $Sponsor_ID = '';
            $Second_Name = '';
            $Last_Name = '';
            $Date_Of_Birth = '';
            $Gender = '';
            $Region = '';
            $District = '';
            $Ward = '';
            $Guarantor_Name = '';
	    $Claim_Number_Status = '';
            $Member_Number = '';
            $Member_Card_Expire_Date = '';
            $Phone_Number = '';
            $Email_Address = '';
            $Occupation = '';
            $Employee_Vote_Number = '';
            $Emergence_Contact_Name = '';
            $Emergence_Contact_Number = '';
            $Company = '';
            $Employee_ID = '';
            $Registration_Date_And_Time = '';
	    $age =0;
        }
?>

<!-- get employee id-->
<?php
    if(isset($_SESSION['userinfo']['Employee_ID'])){
	$Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    }
?>

<!-- get id, date, Billing Type,Folio number and type of chech in -->
<?php
    if(isset($_GET['Registration_ID'])){
	//select the current Patient_Payment_ID to use as a foreign key
	$sql_Select_Current_Patient = mysqli_query($conn,"select pp.Patient_Payment_ID,pp.Claim_Form_Number, ppl.Patient_Direction,
					pp.Folio_Number, pp.Payment_Date_And_Time, ppl.Check_In_Type, ppl.Consultant,
					    pp.Billing_Type from tbl_patient_payments pp, tbl_patient_payment_item_list ppl
					    where pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
						registration_id = '$Registration_ID' order by pp.Patient_Payment_ID desc limit 1");
		$row = mysqli_fetch_array($sql_Select_Current_Patient);
		$Patient_Payment_ID = $row['Patient_Payment_ID'];
		$Payment_Date_And_Time = $row['Payment_Date_And_Time'];
		//$Check_In_Type = $row['Check_In_Type'];
		$Folio_Number = $row['Folio_Number'];
		$Claim_Form_Number = $row['Claim_Form_Number'];
		$Billing_Type = $row['Billing_Type'];
		$Patient_Direction = $row['Patient_Direction'];
		$Consultant = $row['Consultant'];
	    }else{
		$Patient_Payment_ID = '';
		$Payment_Date_And_Time = '';
		//$Check_In_Type = $row['Check_In_Type'];
		$Folio_Number = '';
		$Claim_Form_Number = '';
		$Billing_Type = '';
		$Patient_Direction = '';
		$Consultant ='';
	    }
?>
<!--Getting employee name -->
<?php
    if(isset($_SESSION['userinfo']['Employee_Name'])){
	$Employee_Name = $_SESSION['userinfo']['Employee_Name'];
    }else{
	$Employee_Name = 'Unknown Employee';
    }
?>


<?php
if(isset($_POST['send_admition_form'])){
    $Registration_ID = $_POST['Registration_ID'];
    $Hospital_Ward_ID = $_POST['Hospital_Ward_ID'];
    $Admission_Employee_ID = $_POST['Admission_Employee_ID'];
    $Admission_Supervisor_ID = $_POST['Admission_Supervisor_ID'];
    $Admission_Date_Time = '';
    $Admission_Status = 'Admitted';
    $District = $_POST['District'];
    $Ward = $_POST['Ward'];
    $Admission_Claim_Form_Number = $_POST['Admission_Claim_Form_Number'];
    $Folio_Number = $_POST['Folio_Number'];
    $Office_Area = $_POST['Office_Area'];
    $Office_Plot_Number = $_POST['Office_Plot_Number'];
    $Office_Street = $_POST['Office_Street'];
    $Office_Phone = $_POST['Office_Phone'];
    $Kin_Name = $_POST['Kin_Name'];
    $Kin_Relationship = $_POST['Kin_Relationship'];
    $Kin_Phone = $_POST['Kin_Phone'];
    $Kin_Area = $_POST['Kin_Area'];
    $Kin_Street = $_POST['Kin_Street'];
    $Kin_Plot_Number = $_POST['Kin_Plot_Number'];
    $Kin_Address = $_POST['Kin_Address'];
    
    $insert_query = "INSERT INTO tbl_admission(Registration_ID,Hospital_Ward_ID,
		    Admission_Employee_ID,Admission_Supervisor_ID,
		    Admission_Date_Time,Admission_Status,District,
		    Ward,Admission_Claim_Form_Number,Folio_Number,
		    Office_Area,Office_Plot_Number,Office_Street,Office_Phone,
		    Kin_Name,Kin_Relationship,Kin_Phone,Kin_Area,Kin_Street,Kin_Plot_Number,Kin_Address)
		    
		    VALUE('$Registration_ID','$Hospital_Ward_ID','$Admission_Employee_ID','$Admission_Supervisor_ID',
		    (SELECT NOW()),'$Admission_Status','$District',
		    '$Ward','$Admission_Claim_Form_Number','$Folio_Number',
		    '$Office_Area','$Office_Plot_Number','$Office_Street','$Office_Phone',
		    '$Kin_Name','$Kin_Relationship','$Kin_Phone','$Kin_Area','$Kin_Street','$Kin_Plot_Number','$Kin_Address')
		    ";
    if(mysqli_query($conn,$insert_query)){
	$admited = true;
	$select_admission_ID = "SELECT * FROM tbl_admission
	WHERE Registration_ID = $Registration_ID AND Admission_Status = '$Admission_Status'";
	if($result = mysqli_query($conn,$select_admission_ID)){
	    $row = mysqli_fetch_assoc($result);
	    ?>
	    <script>
	    alert('PATIENT ADMITED SUCCESSFULLY,\n ADMISSION NUMBER <?php echo $row['Admision_ID'];?> !');
	    document.location = "./admit.php?section=Admission&AdmissionWorks=AdmissionWorksThisPage";
	    </script>
	<?php
	}
    }else{
	die(mysqli_error($conn));
	?>
	<script>
	    alert('PATIENT NOT ADMITED TRY AGAIN !');
	</script>
	<?php
    }
}
?>

<script type='text/javascript'>
    function access_Denied(){ 
   alert("Access Denied");
   document.location = "./index.php";
    }
</script>
    <a href='admissionworkspage.php?section=<?php echo $section;?>&SearchListDirectCashPatientBilling=SearchListDirectCashPatientBillingThisPage' class='art-button-green'>
        ADMISSION MAIN WORKPAGE
    </a>
    <a href='searchlistofoutpatientadmission.php?section=<?php echo $section;?>&ContinuePatientBilling=ContinuePatientBillingThisPage' class='art-button-green'>
        CONTINUING OUTPATIENT LIST
    </a>
<form action='#' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">
<fieldset>
        <center>
        </center>
<!--<br/>-->

        <center> 
            <table width=100%> 
                <tr> 
                    <td>
		            <table width=100%>
                            <tr>
                                <td width='16%'><b>Patient Name</b></td>
                                <td width='26%'><input type='text' name='Patient_Name' disabled='disabled' id='Patient_Name' value='<?php echo $First_Name." ".$Second_Name." ".$Last_Name; ?>'></td>
                                <td width='13%'><b>Card Id Expire Date</b></td>
                                <td width='16%'><input type='text' name='Card_ID_Expire_Date' disabled='disabled' id='Card_ID_Expire_Date' value='<?php echo $Member_Card_Expire_Date; ?>'></td> 
                                <td width='13%'><b>Admission Date And Time</b></td>
                                <td width='16%'><input type='text' name='Admission_Date_And_Time' id='Admission_Date_And_Time' value='<?php echo date('Y-m-d H:i:s');?>' disabled='disabled'></td>
                            </tr> 
                            <tr>
                                <td><b>Gender</b></td><td width='16%'><input type='text' name='Gender' disabled='disabled' id='Gender' value='<?php echo $Gender; ?>'></td>
				
                                <td><b>Claim Form Number</b></td>
		    		<td><input type='text' name='Admission_Claim_Form_Number' id='Admission_Claim_Form_Number'<?php if($Claim_Number_Status == "Mandatory"){?> required='required'<?php }?> value='<?php echo $Claim_Form_Number;?>'></td>
                                <td><b>Admission Number</b></td>
                                <td>
				    <input type='text' disabled='disabled' value='<?php echo $Admission_Number; ?>'>
				    <input type='hidden' name='Admission_Number' id='Admission_Number' value='<?php echo $Admission_Number; ?>'>
				</td>
                            </tr>
                            <tr>
                                <td><b>Sponsor Name</b></td>
                                <td><input type='text' name='Guarantor_Name' disabled='disabled' id='Guarantor_Name' value='<?php echo $Guarantor_Name; ?>'></td>
                                <td><b>Patient Age</b></td>
                                <td><input type='text' name='Patient_Age' id='Patient_Age'  disabled='disabled' value='<?php echo $age; ?>'></td>
                                <input type='hidden' id='Admission_Employee_ID' name='Admission_Employee_ID' value='<?php echo $Employee_ID;?>'>
				<td><b>Ward</b></td>
				<td>
				    <select required='required' name='Hospital_Ward_ID' id='Hospital_Ward_ID'><option></option>
				<?php if(isset($_SESSION['userinfo']['Branch_ID'])){
				    $Branch_ID = $_SESSION['userinfo']['Branch_ID'];
				    $Select_Department = mysqli_query($conn,"select * from tbl_hospital_ward where Branch_ID = '$Branch_ID'");
				    while($row = mysqli_fetch_array($Select_Department)){
					$Ward_Name = $row['Hospital_Ward_Name'];
				    ?>
				    <option value='<?php echo $row['Hospital_Ward_ID'];?>'><?php echo $Ward_Name;?></option>
				    <?php
				    }
				} else { ?>
				    <option></option>
				<?php } ?>
				</select>
				</td>
                            </tr>
                            <tr> 
                                <td><b>Previous Number</b></td>
                                <td>
                                    <input type='text' name='Old_Registration_Number' id='Old_Registration_Number' disabled='disabled' value='<?php echo $Old_Registration_Number; ?>'>
				</td>
                                <td><b>Phone Number</b></td>
                                <td><input type='text' name='Phone_Number' id='Phone_Number' disabled='disabled' value='<?php echo $Phone_Number; ?>'></td>
                                <td><b>Folio Number</b></td>
                                <td><input type='text' disabled='disabled' value='<?php echo $Folio_Number; ?>'>
				    <input type='hidden' name='Folio_Number' id='Folio_Number' value='<?php echo $Folio_Number; ?>'>
				</td>
                            </tr>
                            <tr>
                                <td><b>Region</b></td>
                                <td>
                                    <input type='text' name='Region' id='Region' disabled='disabled' value='<?php echo $Region; ?>'>
                                </td>
                                <td><b>Registration Number</b></td>
                                <td><input type='text' disabled='disabled' value='<?php echo $Registration_ID; ?>'>
				<input type='hidden' name='Registration_ID' id='Registration_ID'value='<?php echo $Registration_ID; ?>'>
				</td>
                                <td><b>Prepared By</b></td>
                                <td><input type='text' name='Prepared_By' id='Prepared_By' disabled='disabled' value='<?php echo $Employee_Name; ?>'></td>
                            </tr>
                            <tr>
                                <td><b>District</b></td>
                                <td>
                                    <input type='text'disabled='disabled' value='<?php echo $District; ?>'>
                                    <input type='hidden' name='District' id='District' value='<?php echo $District; ?>'>
				    <input type='hidden' name='Ward' id='Ward' value='<?php echo $Ward; ?>'>
                                </td>
                                <td><b>Member Number</b></td>
                                <td><input type='text' name='Supervised_By' id='Supervised_By' disabled='disabled' value='<?php echo $Member_Number; ?>'></td> 
                                <td><b>Supervised By</b></td>
				<?php
				    if(isset($_SESSION['admissionsupervisor'])) {
					if(isset($_SESSION['admissionsupervisor']['Session_Master_Priveleges'])){
					    if($_SESSION['admissionsupervisor']['Session_Master_Priveleges'] = 'yes'){
						$Supervisor = $_SESSION['admissionsupervisor']['Employee_Name'];
						?>
				<input type='hidden' id='Admission_Supervisor_ID' name='Admission_Supervisor_ID' value='<?php echo $_SESSION['admissionsupervisor']['Employee_ID'];?>'>
				<?php
					    }else{
						$Supervisor = "Unknown Supervisor";
					    }
					}else{
						$Supervisor = "Unknown Supervisor";
					}
				    }else{
						$Supervisor = "Unknown Supervisor";
				    }
				?>
                                <td><input type='text' name='Member_Number' id='Member_Number' disabled='disabled' value='<?php echo $Supervisor; ?>'></td>
                            </tr> 
                        </table>
                    </td> 
                </tr>
            </table>
        </center>
</fieldset>
<fieldset>
    <table  width=100%>
	<tr>
	    <td colspan=2><center><b>OFFICE/BUSSINESS/WORK ADDRESS</b></center></td>
	    <td colspan=2><center><b>NEXT OF KIN NAME AND CONTACT</b></center></td>
	    <td colspan=2><center><b>NEXT OF KIN ADDRESS</b></center></td>
	</tr>
	<tr>
	    <td width='16%'><b>Area/Office :</b></td>
	    <td width='26%'><input type='text' id='Office_Area' name='Office_Area'></td>
	    
	    <td width='13%'><b>Name :</b></td>
	    <td width='16%'><input type='text' id='Kin_Name' name='Kin_Name' required='required'></td>
	    
	    <td width='13%'><b>Area :</b></td>
	    <td width='16%'><input type='text' id='Kin_Area' name='Kin_Area'></td>
	</tr>
	<tr>
	    <td><b>Plot N<u>o</u> :</b></td>
	    <td><input type='text' id='Office_Plot_Number' name='Office_Plot_Number'></td>
	    
	    <td><b>Relationship :</b></td>
	    <td><input type='text' id='Kin_Relationship' name='Kin_Relationship' required='required'></td>
	    
	    <td><b>Street :</b></td>
	    <td><input type='text' id='Kin_Street' name='Kin_Street'></td>
	</tr>
	<tr>
	    <td><b>Street :</b></td>
	    <td><input type='text' id='Office_Street' name='Office_Street'></td>
	    
	    <td><b>Phone :</b></td>
	    <td><input type='text' id='Kin_Phone' name='Kin_Phone' required='required'></td>
	    
	    <td><b>Plot N<u>o</u> :</b></td>
	    <td><input type='text' id='Kin_Plot_Number' name='Kin_Plot_Number'></td>
	</tr>
	<tr>
	    <td><b>Phone :</b></td>
	    <td><input type='text' id='Office_Phone' name='Office_Phone'></td>
	    
	    <td></td>
	    <td></td>
	    
	    <td><b>Address :</b></td>
	    <td><input type='text' id='Kin_Address' name='Kin_Address'></td>
	</tr>
    </table>
</fieldset>
    <table width='100%'>
	<tr>
	    <td>
		<?php if(isset($_GET['Registration_ID'])){
		    ?>
		    <input type='submit' value='ADMIT' class='art-button-green'>
		<?php }
		else{?>
		<input type='button' value='ADMIT' class='art-button-green' onclick="alert('Choose Patient To Admit!');">
		<?php }?>
		<input type='reset' value='CLEAR' class='art-button-green' >
		<input type='hidden' id='send_admition_form' name='send_admition_form'>
	    </td>
	</tr>
    </table>
</form>
<?php
    include("./includes/footer.php");
?>