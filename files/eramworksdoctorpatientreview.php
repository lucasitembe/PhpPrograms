<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['eRAM_Works'])){
	    if($_SESSION['userinfo']['eRAM_Works'] != 'yes'){
		header("Location: ./index.php?InvalidPrivilege=yes");
	    }
	}else{
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	    header("Location: ../index.php?InvalidPrivilege=yes");
    }
    $Patient_Payment_ID=$_GET['Patient_Payment_ID'];
    $Patient_Payment_Item_List_ID=$_GET['Patient_Payment_Item_List_ID'];
?>
<!--START HERE-->
<?php
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
                            Old_Registration_Number,Title,Patient_Name,pr.Sponsor_ID,Date_Of_Birth,
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
                $Patient_Name = $row['Patient_Name'];
                $Sponsor_ID = $row['Sponsor_ID'];
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
	    
		$date1 = new DateTime($Today);
		$date2 = new DateTime($Date_Of_Birth);
		$diff = $date1 -> diff($date2);
		$age = $diff->y." Years, ".$diff->m." Months, ".$diff->d." Days, ".$diff->h." Hours";
        }else{
            $Registration_ID = '';
            $Old_Registration_Number = '';
            $Title = '';
            $Patient_Name = '';
            $Sponsor_ID = '';
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
            $Sponsor_ID = '';
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
<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['eRAM_Works'] == 'yes'){
?>
    <!--<a href='doctorpatientlistfile.php?<?php if($Registration_ID!=''){echo "Registration_ID=$Registration_ID&"; } ?><?php
    if(isset($_GET['Patient_Payment_ID'])){
	echo "Patient_Payment_ID=".$_GET['Patient_Payment_ID']."&";
	} ?>SearchListDoctorsPagePatientBilling=SearchListDirectCashPatientBillingThisPage' class='art-button-green'>
        PATIENTS FILE LIST
    </a>-->
    <a href='eramworkspatientfile.php?<?php if($Registration_ID!=''){echo "Registration_ID=$Registration_ID&"; } ?>Patient_Payment_Item_List_ID=<?php echo $Patient_Payment_Item_List_ID; ?>&SearchListDoctorsPagePatientBilling=SearchListDirectCashPatientBillingThisPage' class='art-button-green'>
        BACK
    </a>
<?php  } } ?>
<br/><br/>
<!-- get employee id-->
<?php
    if(isset($_SESSION['userinfo']['Employee_ID'])){
	$Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    }
?>
<!-- get id, date, Billing Type,Folio number and type of chech in -->
<?php
    if(isset($_SESSION['userinfo']['Employee_Name'])){
	$Employee_Name = $_SESSION['userinfo']['Employee_Name'];
    }else{
	$Employee_Name = 'Unknown Employee';
    }
?>

<script type='text/javascript'>
    function access_Denied(){ 
   alert("Access Denied");
   document.location = "./index.php";
    }
</script>
<fieldset>
        <center>
	    <b>eRAM WORKS</b>
            <p>Patient Review</p>
            <p><?php echo $Patient_Name.", ".$Gender.", ".$Guarantor_Name.", (".$age.")";?></p>
        </center>
</fieldset>
<fieldset style='overflow-y: scroll;height: 340px;'>
    <?php
    $qr = "SELECT * FROM tbl_consultation c, tbl_employee e WHERE e.Employee_ID=c.Employee_ID
           AND c.consultation_ID = ".$_GET['consultation_ID'];
    $result = mysqli_query($conn,$qr);
    $row = mysqli_fetch_assoc($result);
    ?>
    <table width='100%' style='background: #ffffff'>
        <tr>
            <td><b>Consultation Date And Time</b></td>
            <td><?php
                echo $row['Consultation_Date_And_Time'];
                ?></td>
        </tr>
	<tr>
            <td><b>Doctor Consulted</b></td>
            <td><?php
                echo $row['Employee_Name'];
                ?></td>
        </tr>
        <tr>
            <td width='13%'><b>Main Complaign</b></td>
            <td><<?php
                echo $row['maincomplain'];
                ?></td>
        </tr>
        <tr>
            <td><b>First Date Of Symptom</b></td>
            <td><?php
                echo $row['firstsymptom_date'];
                ?></td>
        </tr>
        <tr>
            <td><b>History Of Present Illness</b></td>
            <td><?php
                echo $row['history_present_illness'];
                ?></td>
        </tr>
        <tr>
            <td><b>Patient Status</b></td>
            <td><?php
                echo "Outpatient";
                ?></td>
        </tr>
	<tr>
            <td><b>Review Of Other Systems</b></td>
            <td><?php
                echo $row['review_of_other_systems'];
                ?></td>
        </tr>
	<tr>
            <td><b>General Examination Observation</b></td>
            <td><?php
                echo $row['general_observation'];
                ?></td>
        </tr>
	<tr>
            <td><b>Systemic Examination Observation</b></td>
            <td><?php
                echo $row['systemic_observation'];
                ?></td>
        </tr>
	<?php
	$subqr = "SELECT * FROM tbl_item_list_cache ilc,tbl_payment_cache pc,tbl_items i
		  WHERE ilc.Payment_Cache_ID = pc.Payment_Cache_ID and pc.consultation_id =".$row['consultation_ID']." and i.item_ID = ilc.item_ID ";
	$subqr_result = mysqli_query($conn,$subqr);
	$Pharmacy = '';
	$Sugery = '';
	$Procedure = '';
	$Laboratory = '';
	$Radiology = '';
	while($subqr_row = mysqli_fetch_assoc($subqr_result)){
	    if($subqr_row['Check_In_Type']=='Pharmacy'){
		$Pharmacy.= $subqr_row['Product_Name']."; ";
	    }
	    if($subqr_row['Check_In_Type']=='Sugery'){
		$Sugery.= $subqr_row['Product_Name']."; ";
	    }
	    if($subqr_row['Check_In_Type']=='Procedure'){
		$Procedure.= $subqr_row['Product_Name']."; ";
	    }
	    if($subqr_row['Check_In_Type']=='Laboratory'){
		$Laboratory.= $subqr_row['Product_Name']."; ";
	    }
	    if($subqr_row['Check_In_Type']=='Radiology'){
		$Radiology.= $subqr_row['Product_Name']."; ";
	    }
	}
	
	//selecting disease
	$subqr2 = "SELECT * FROM tbl_disease_consultation dc,tbl_disease d WHERE dc.disease_ID=d.disease_ID AND dc.consultation_ID=".$row['consultation_ID'];
	$subqr_result2 = mysqli_query($conn,$subqr2);
	$provisional_diagnosis = '';
	$diferential_diagnosis = '';
	$diagnosis ='';
	
	while($subqr_row2 = mysqli_fetch_assoc($subqr_result2)){
	    if($subqr_row2['diagnosis_type']=='provisional_diagnosis'){
		$provisional_diagnosis.= $subqr_row2['disease_name']."; ";
	    }
	    if($subqr_row2['diagnosis_type']=='diferential_diagnosis'){
		$diferential_diagnosis.= $subqr_row2['disease_name']."; ";
	    }
	    if($subqr_row2['diagnosis_type']=='diagnosis'){
		$diagnosis.= $subqr_row2['disease_name']."; ";
	    }
	}
	?>
	<tr>
            <td><b>Provisional Diagnosis</b></td>
            <td><?php
		//quey from tbl_disease_consultation
		echo $provisional_diagnosis;
                ?></td>
        </tr>
	<tr>
            <td><b>Diferential Diagnosis</b></td>
            <td><?php
                //quey from tbl_disease_consultation
		echo $diferential_diagnosis;
                ?></td>
        </tr>
	<tr>
            <td><b>Laboratory</b></td>
            <td><?php
                //quey from cache tables
		echo $Laboratory;
                ?></td>
        </tr>
	<tr>
            <td><b>Comments For Laboratory</b></td>
            <td><?php
                echo $row['Comment_For_Laboratory'];
                ?></td>
        </tr>
	<tr>
            <td><b>Radiology</b></td>
            <td><?php
                //query cache tables
		echo $Radiology;
                ?></td>
        </tr>
	<tr>
            <td><b>Comments For Radiology</b></td>
            <td><?php
                echo $row['Comment_For_Radiology'];
                ?></td>
        </tr>
	<tr>
            <td><b>Doctor's Investigation Comments</b></td>
            <td><?php
                echo $row['investigation_comments'];
                ?></td>
        </tr>
	<tr>
            <td><b>Diagnosis</b></td>
            <td><?php
                //query disease table
		echo $diagnosis;
                ?></td>
        </tr>
	<tr>
            <td><b>Procedure</b></td>
            <td><?php
                //query cache tables
		echo $Procedure;
                ?></td>
        </tr>
	<tr>
            <td><b>Procedure Comments</b></td>
            <td><?php
                //
                ?></td>
        </tr>
	<tr>
            <td><b>Sugery</b></td>
            <td><?php
                //query from cache table
		echo $Sugery;
                ?></td>
        </tr>
	<tr>
            <td><b>Sugery Comments</b></td>
            <td><?php
                //
                ?></td>
        </tr>
	<tr>
            <td><b>Pharmacy</b></td>
            <td><?php
                //query from cache table
		echo $Pharmacy;
                ?></td>
        </tr>
	<tr>
            <td><b>Remarks</b></td>
            <td><?php
                echo $row['remarks'];
                ?></td>
        </tr>
    </table>
</fieldset>
<!--END HERE-->
<?php
    include("./includes/footer.php");
?>