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
	header("Location: admissionsupervisorauthentication.php?section=Admission&status=discharge&Authenticate=SuperUser&Activity=Active");
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
                            Old_Registration_Number,Title,First_Name,pr.Sponsor_ID,
                                Second_Name,Last_Name,Date_Of_Birth,
                                    Gender,pr.Region,pr.District,pr.Ward,
                                        Member_Number,Member_Card_Expire_Date,
                                            pr.Phone_Number,Email_Address,Occupation,
                                                Employee_Vote_Number,Emergence_Contact_Name,
                                                    Emergence_Contact_Number,Company,Registration_ID,Claim_Number_Status,
                                                        Employee_ID,Registration_Date_And_Time,Guarantor_Name,
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
                $First_Name = $row['First_Name'];
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

<!--GET ADMISSION INFORMATION-->
    <?php
	if(isset($_GET['Registration_ID'])){
        $select_from_admission ="SELECT * FROM tbl_admission WHERE Admission_Status = 'Admitted' AND Registration_ID =$Registration_ID";
        $result = mysqli_query($conn,$select_from_admission);
        $row = mysqli_fetch_assoc($result);
        
        $Admision_ID = $row['Admision_ID'];
        $Registration_ID = $row['Registration_ID'];
        $Hospital_Ward_ID = $row['Hospital_Ward_ID'];
        $Admission_Employee_ID = $row['Admission_Employee_ID'];
        $Admission_Supervisor_ID = $row['Admission_Supervisor_ID'];
        $Admission_Date_Time = $row['Admission_Date_Time'];
        $District = $row['District'];
        $Ward = $row['Ward'];
        $Admission_Claim_Form_Number = $row['Admission_Claim_Form_Number'];
        $Folio_Number = $row['Folio_Number'];
        $Office_Area = $row['Office_Area'];
        $Office_Plot_Number = $row['Office_Plot_Number'];
        $Office_Street = $row['Office_Street'];
        $Office_Phone = $row['Office_Phone'];
        $Kin_Name = $row['Kin_Name'];
        $Kin_Relationship = $row['Kin_Relationship'];
        $Kin_Phone = $row['Kin_Phone'];
        $Kin_Area = $row['Kin_Area'];
        $Kin_Street = $row['Kin_Street'];
        $Kin_Plot_Number = $row['Kin_Plot_Number'];
        $Kin_Address = $row['Kin_Address'];
	}else{
	$Admision_ID = '';
        $Registration_ID = '';
        $Hospital_Ward_ID = '';
        $Admission_Employee_ID = '';
        $Admission_Supervisor_ID = '';
        $Admission_Date_Time = '';
        $District = '';
        $Ward = '';
        $Admission_Claim_Form_Number = '';
        $Folio_Number = '';
        $Office_Area = '';
        $Office_Plot_Number = '';
        $Office_Street = '';
        $Office_Phone = '';
        $Kin_Name = '';
        $Kin_Relationship = '';
        $Kin_Phone = '';
        $Kin_Area = '';
        $Kin_Street = '';
        $Kin_Plot_Number = '';
        $Kin_Address = '';    
	}
    ?>
<!--END OF ADMISSION INFORMATION-->


<!-- get employee id-->
<?php
    if(isset($_SESSION['userinfo']['Employee_ID'])){
	$Employee_ID = $_SESSION['userinfo']['Employee_ID'];
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
    $Admision_ID = $_POST['Admision_ID'];
    $Admission_Status = 'Discharged';
    $Discharge_Claim_Form_Number = $_POST['Discharge_Claim_Form_Number'];
    $Discharge_Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    $Discharge_Supervisor_ID = $_SESSION['admissionsupervisor']['Employee_ID'];
    $Discharge_Date_Time = '';
    $Discharge_Reason_ID = $_POST['Discharge_Reason_ID'];
    
    $update_query = "UPDATE tbl_admission SET Discharge_Employee_ID='$Discharge_Employee_ID',
    Discharge_Supervisor_ID='$Discharge_Supervisor_ID',Discharge_Date_Time=(SELECT NOW()),
    Discharge_Reason_ID='$Discharge_Reason_ID',Discharge_Claim_Form_Number='$Discharge_Claim_Form_Number',
    Admission_Status='$Admission_Status' WHERE Admision_ID = $Admision_ID";
    if(mysqli_query($conn,$update_query)){
	    ?>
	    <script>
	    alert('PATIENT DISCHARGED SUCCESSFULLY !');
	    document.location = "./discharge.php?section=Admission&AdmissionWorks=AdmissionWorksThisPage";
	    </script>
	<?php
    }
    else{
        ?>
	<script>
	    alert('PATIENT NOT DISCHARGED TRY AGAIN !');
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
    <a href='searchlistofoutpatientadmited.php?section=<?php echo $section;?>&ContinuePatientBilling=ContinuePatientBillingThisPage' class='art-button-green'>
        ADMITTED PATIENTS
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
                                <td width='13%'><b>Discharge Date And Time</b></td>
                                <td width='16%'><input type='text' name='Admission_Date_And_Time' id='Admission_Date_And_Time' value='<?php echo date('Y-m-d H:i:s');?>' disabled='disabled'></td>
                            </tr> 
                            <tr>
                                <td><b>Gender</b></td><td width='16%'><input type='text' name='Gender' disabled='disabled' id='Gender' value='<?php echo $Gender; ?>'></td>
				
                                <td><b>Claim Form Number</b></td>
		    		<td><input type='text' name='Discharge_Claim_Form_Number' id='Discharge_Claim_Form_Number' value='<?php echo $Admission_Claim_Form_Number;?>'<?php if($Claim_Number_Status=='Mandatory'){ ?> required='required'<?php }?>></td>
                                <td><b>Admission Number</b></td>
                                <td>
				    <input type='text' disabled='disabled' value='<?php echo $Admision_ID; ?>'>
				    <input type='hidden' name='Admision_ID' id='Admision_ID' value='<?php echo $Admision_ID;?>'>
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
				    <?php
					if(isset($_SESSION['userinfo']['Branch_ID'] )&& $Hospital_Ward_ID !=''){
					$Branch_ID = $_SESSION['userinfo']['Branch_ID'];
					$Select_Department = mysqli_query($conn,"select * from tbl_hospital_ward where Branch_ID = '$Branch_ID' AND Hospital_Ward_ID = '$Hospital_Ward_ID'");
					$row = mysqli_fetch_array($Select_Department);
					$Ward_Name = $row['Hospital_Ward_Name'];
				    ?>
				    <input type='text' disabled='disabled' value='<?php echo $Ward_Name;?>'>
				    <?php }else{?>
				    <input type='text' disabled='disabled' value='Unknown'>
				    <?php }?>
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
	    <td width='26%'><input type='text' id='Office_Area' name='Office_Area' disabled='disabled' value='<?php echo $Office_Area;?>'></td>
	    
	    <td width='13%'><b>Name :</b></td>
	    <td width='16%'><input type='text' id='Kin_Name' name='Kin_Name' disabled='disabled' value='<?php echo $Kin_Name;?>'></td>
	    
	    <td width='13%'><b>Area :</b></td>
	    <td width='16%'><input type='text' id='Kin_Area' name='Kin_Area' disabled='disabled' value='<?php echo $Kin_Area;?>'></td>
	</tr>
	<tr>
	    <td><b>Plot N<u>o</u> :</b></td>
	    <td><input type='text' id='Office_Plot_Number' name='Office_Plot_Number' disabled='disabled' value='<?php echo $Office_Plot_Number;?>'></td>
	    
	    <td><b>Relationship :</b></td>
	    <td><input type='text' id='Kin_Relationship' name='Kin_Relationship' disabled='disabled' value='<?php echo $Kin_Relationship;?>'></td>
	    
	    <td><b>Street :</b></td>
	    <td><input type='text' id='Kin_Street' name='Kin_Street' disabled='disabled' value='<?php echo $Kin_Street;?>'></td>
	</tr>
	<tr>
	    <td><b>Street :</b></td>
	    <td><input type='text' id='Office_Street' name='Office_Street' disabled='disabled' value='<?php echo $Office_Street;?>'></td>
	    
	    <td><b>Phone :</b></td>
	    <td><input type='text' id='Kin_Phone' name='Kin_Phone' disabled='disabled' value='<?php echo $Kin_Phone;?>'></td>
	    
	    <td><b>Plot N<u>o</u> :</b></td>
	    <td><input type='text' id='Kin_Plot_Number' name='Kin_Plot_Number' disabled='disabled' value='<?php echo $Kin_Plot_Number;?>'></td>
	</tr>
	<tr>
	    <td><b>Phone :</b></td>
	    <td><input type='text' id='Office_Phone' name='Office_Phone' disabled='disabled' value='<?php echo $Office_Phone;?>'></td>
	    
	    <td></td>
	    <td></td>
	    
	    <td><b>Address :</b></td>
	    <td><input type='text' id='Kin_Address' name='Kin_Address' disabled='disabled' value='<?php echo $Kin_Address;?>'></td>
	</tr>
    </table>
</fieldset>
<fieldset>
    <table width='100%'>
	<tr>
	    <td width='16%'><b>Discharge Reason :</b></td><td>
	    <select id='Discharge_Reason_ID' name='Discharge_Reason_ID' required='required' style='width: 31%'>
		<option></option>
		<?php
		$select_discharge_reason = "SELECT * FROM tbl_discharge_reason";
		$reslt = mysqli_query($conn,$select_discharge_reason);
		while($output = mysqli_fetch_assoc($reslt)){
		?>
		<option value='<?php echo $output['Discharge_Reason_ID'];?>'><?php echo $output['Discharge_Reason'];?></option>    
		<?php
		}
		?>
	    </select>
	    </td>
	</tr>
	<tr>
	    <td colspan=2>
		<?php if(isset($_GET['Registration_ID'])){
		    ?>
		    <input type='submit' value='DISCHARGE' class='art-button-green'>
		<?php }
		else{?>
		<input type='button' value='DISCHARGE' class='art-button-green' onclick="alert('Choose Patient To Discharge!');">
		<?php }?>
		<input type='reset' value='CLEAR' class='art-button-green' >
		<input type='hidden' id='send_admition_form' name='send_admition_form'>
	    </td>
	</tr>
    </table>
</fieldset>
</form>
<?php
    include("./includes/footer.php");
?>