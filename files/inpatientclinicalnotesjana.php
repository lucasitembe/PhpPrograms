<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Doctors_Page_Inpatient_Work'])){
	    if($_SESSION['userinfo']['Doctors_Page_Inpatient_Work'] != 'yes'){
		header("Location: ./index.php?InvalidPrivilege=yes");
	    }
	}else{
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	    header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_GET['Patient_Payment_Item_List_ID']) || isset($_GET['Patient_Payment_ID'])){
		$Patient_Payment_Item_List_ID = $_GET['Patient_Payment_Item_List_ID'];
		$Patient_Payment_ID = $_GET['Patient_Payment_ID'];
	}else{
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
	
    $Registration_ID = $_GET['Registration_ID']; 
	
	$consultation_query = "SELECT Round_ID FROM tbl_ward_round WHERE Patient_Payment_Item_List_ID = '$Patient_Payment_Item_List_ID' AND Registration_ID='$Registration_ID'";
	
	//die($consultation_query);
	
	
    $consultation_query_result = mysqli_query($conn,$consultation_query) or die(mysqli_error($conn));
    
    $employee_ID= $_SESSION['userinfo']['Employee_ID'];
    
    $Findings='';
    $Comment_For_Laboratory ='';
    $Comment_For_Radiology = '';
    $investigation_comments='';
    $remarks='';
    $Ward_Round_Date_And_Time = Date('Y-m-d h:i:s');
    
    if(@mysqli_num_rows($consultation_query_result)>0){
	  //die("I am here");
    }else{
       $insert_query = "INSERT INTO tbl_ward_round(employee_ID, Registration_ID,
                                       investigation_comments, remarks,
                                       Comment_For_Laboratory,
                                       Comment_For_Radiology,
                                       Patient_Payment_Item_List_ID,
                                       Ward_Round_Date_And_Time)
        VALUES ('$employee_ID', '$Registration_ID', 
                                       '$investigation_comments', '$remarks',
                                       '$Comment_For_Laboratory','$Comment_For_Radiology','$Patient_Payment_Item_List_ID','$Ward_Round_Date_And_Time')";
         mysqli_query($conn,$insert_query) or die(mysqli_error($conn));
    }
?>
<!--START HERE-->
<link rel="stylesheet" type="text/css" href="jquerytabs/jquery-ui.theme.css"/>

<script>
	var Item_ID;
</script>
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
        if($_SESSION['userinfo']['Doctors_Page_Inpatient_Work'] == 'yes'){
?>
    <a href='doctorspageinpatientwork.php?Folio_Number=<?php echo $_GET['Folio_Number'] ?>&Registration_ID=<?php echo $_GET['Registration_ID'] ?>&consultation_ID=<?php echo $_GET['consultation_ID'] ?>&NR=true&PatientBilling=PatientBillingThisForm' class='art-button-green'>
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
    if(isset($_GET['Registration_ID']) && isset($_GET['Patient_Payment_ID'])){
	//select the current Patient_Payment_ID to use as a foreign key
	
	$qr = "select * from tbl_patient_payments pp
					    where pp.Patient_Payment_ID = ".$_GET['Patient_Payment_ID']."
					    and pp.registration_id = '$Registration_ID'";
	$sql_Select_Current_Patient = mysqli_query($conn,$qr);
		$row = mysqli_fetch_array($sql_Select_Current_Patient);
		$Patient_Payment_ID = $row['Patient_Payment_ID'];
		$Payment_Date_And_Time = $row['Payment_Date_And_Time'];
		//$Check_In_Type = $row['Check_In_Type'];
		$Folio_Number = $row['Folio_Number'];
		$Claim_Form_Number = $row['Claim_Form_Number'];
		$Billing_Type = $row['Billing_Type'];
		//$Patient_Direction = $row['Patient_Direction'];
		//$Consultant = $row['Consultant'];
	    }else{
		$Patient_Payment_ID = '';
		$Payment_Date_And_Time = '';
		//$Check_In_Type = $row['Check_In_Type'];
		$Folio_Number = '';
		$Claim_Form_Number = '';
		$Billing_Type = '';
		//$Patient_Direction = '';
		//$Consultant ='';
	    }
?>
<!--Getting employee name -->
<?php
    if(isset($_SESSION['userinfo']['Employee_Name'])){
	$Employee_Name = $_SESSION['userinfo']['Employee_Name'];
	$employee_ID = $_SESSION['userinfo']['Employee_ID'];
	$Branch_ID = $_SESSION['userinfo']['Branch_ID'];
    }else{
	$Employee_Name = 'Unknown Employee';
	$employee_ID = 0;
    }
    //GET CONSULATATION ID IF IS SET/AVAILABLE
    //$Patient_Payment_ID;
    $round_query = "SELECT MAX(Round_ID) as Round_ID FROM tbl_ward_round WHERE Patient_Payment_Item_List_ID = '$Patient_Payment_Item_List_ID'";
    $round_query_result = mysqli_query($conn,$round_query);
    
    
    if(@mysqli_num_rows($round_query_result)>0){
	$row = mysqli_fetch_assoc($round_query_result);
	$Round_ID = $row['Round_ID'];
	if($Round_ID==NULL){
	    $Round_ID = 0;
	}
    }else{
	$Round_ID = 0;
    }
	
	$consultation_ID=0;
?>
<script>
    
    function getItem(Consultation_Type){
        if (Consultation_Type=='') {
            Consultation_Type = 'NotSet'
        }
		
	 document.getElementById("recentConsultaionTyp").value=Consultation_Type;
		
	var url = './inpatientclinicalautosave.php?Consultation_Type='+Consultation_Type+'&<?php if($Registration_ID!=''){echo "Registration_ID=$Registration_ID&"; } ?><?php
	if(isset($_GET['Patient_Payment_ID'])){
	    echo "Patient_Payment_ID=".$_GET['Patient_Payment_ID']."&";
	}
	if($Round_ID!=0){
	echo "Round_ID=".$Round_ID."&";
	}
	if(isset($_GET['Patient_Payment_Item_List_ID'])){
	echo "Patient_Payment_Item_List_ID=".$_GET['Patient_Payment_Item_List_ID']."&";
	}if(isset($_GET['consultation_ID'])){
			  echo "consultation_ID=".$_GET['consultation_ID']."&";
	} 
	?>&SearchListDoctorsPagePatientBilling=SearchListDirectCashPatientBillingThisPage';
	
	// frm.action = url;
	// frm.method = 'POST';
	// frm.submit();
	
	        if(window.XMLHttpRequest) {
			 mm = new XMLHttpRequest();
		    }
		    else if(window.ActiveXObject){ 
			mm = new ActiveXObject('Microsoft.XMLHTTP');
			mm.overrideMimeType('text/xml');
		    }
		    
		    mm.onreadystatechange= AJAXP2; //specify name of function that will handle server response....
		    mm.open('POST',url,true);
		    mm.send();
			
			function AJAXP2() {
			var data1 = mm.responseText; 
			 //alert(data1);
			//document.getElementById('Item_Subcategory_ID').innerHTML = data1;
			}
			
		//alert(Consultation_Type);	
			
	 var url2= 'Consultation_Type='+Consultation_Type+'&<?php if($Registration_ID!=''){echo "Registration_ID=$Registration_ID&"; } ?><?php
	if(isset($_GET['Patient_Payment_ID'])){
	    echo "Patient_Payment_ID=".$_GET['Patient_Payment_ID']."&";
	}
	if($Round_ID!=0){
	echo "Round_ID=".$Round_ID."&";
	}if(isset($_GET['consultation_ID'])){
	  echo "consultation_ID=".$_GET['consultation_ID']."&";
	}
	if(isset($_GET['Patient_Payment_Item_List_ID'])){
	echo "Patient_Payment_Item_List_ID=".$_GET['Patient_Payment_Item_List_ID']."";
	}
	?>&SearchListDoctorsPagePatientBilling=SearchListDirectCashPatientBillingThisPage';
	//alert(url2);
	      $.ajax({
			   type:'GET', 
			   url:'inpatientdoctoritemselectajax.php',
			   data:url2,
			   cache:false,
			   success:function(html){
				  // alert(html);
				  $('#myConsult').html(html);
				  $("#showdataConsult").dialog("open"); 
			   }
			});
			
			
    }

</script>
<script type='text/javascript'>
    function access_Denied(){
   alert("Access Denied");
   document.location = "./index.php";
    }
    
    function getDisease(Consultation_Type){
        if (Consultation_Type=='') {
            Consultation_Type = 'NotSet'
        }
	var frm = document.getElementById("clinicalnotes");
	var url = './inpatientclinicalautosave_todisease.php?Consultation_Type='+Consultation_Type+'&<?php if($Registration_ID!=''){echo "Registration_ID=$Registration_ID&"; } ?><?php
	if(isset($_GET['Patient_Payment_ID'])){
	    echo "Patient_Payment_ID=".$_GET['Patient_Payment_ID']."&";
	    }
	if($Round_ID!=0){
	echo "Round_ID=".$Round_ID."&";
	}if(isset($_GET['consultation_ID'])){
			  echo "consultation_ID=".$_GET['consultation_ID']."&";
	}
	if(isset($_GET['Patient_Payment_Item_List_ID'])){
	echo "Patient_Payment_Item_List_ID=".$_GET['Patient_Payment_Item_List_ID']."&";
	}
	?>SearchListDoctorsPagePatientBilling=SearchListDirectCashPatientBillingThisPage';
	frm.action = url;
	frm.method = 'POST';
	frm.submit();
    }
</script>
<script>
    //function to confirm provisional diagnosis before test name selection 
    function confirmDiagnosis(Consultation_Type) {
		var testnameSelection=confirm("You about to specify laboratory test names for this patient.\nClick Ok to continue without provisional diagnosis.");
		if(testnameSelection) {
			getItem(Consultation_Type);
			return true;
		}else{
			location.href="#";
			return false;
		}
	
    }
</script>
<script>
function getDiseaseFinal(Consultation_Type){
	 // alert('Tis is it');
	 var Round_ID='<?php echo $Round_ID;?>';
	
        if (Consultation_Type=='') {
            Consultation_Type = 'NotSet'
        }
		
	var frm = document.getElementById("clinicalnotes");
	document.getElementById("recentConsultaionTyp").value=Consultation_Type;
	
	  //alert('gsmmm');
	var ul='inpatientdoctoritemselectajax.php';
	    if(Consultation_Type=='diagnosis' || Consultation_Type=='provisional_diagnosis' ||Consultation_Type=='diferential_diagnosis'){
		  ul='inpatientdoctordiagnosisselect.php';
		  
		}
	
	var url = './inpatientclinicalautosave_todisease.php?Consultation_Type='+Consultation_Type+'&Round_ID='+Round_ID+'&<?php if($Registration_ID!=''){echo "Registration_ID=$Registration_ID&"; } ?><?php
	if(isset($_GET['Patient_Payment_ID'])){
	    echo "Patient_Payment_ID=".$_GET['Patient_Payment_ID']."&";
	    }
	if(isset($_GET['consultation_ID'])){
			  echo "consultation_ID=".$_GET['consultation_ID']."&";
	}
	if(isset($_GET['Patient_Payment_Item_List_ID'])){
	echo "Patient_Payment_Item_List_ID=".$_GET['Patient_Payment_Item_List_ID']."&";
	}
	?>SearchListDoctorsPagePatientBilling=SearchListDirectCashPatientBillingThisPage';
	// frm.action = url;
	// frm.method = 'POST';
	// frm.submit();
	// return false;
	  if(window.XMLHttpRequest) {
			 mm = new XMLHttpRequest();
		    }
		    else if(window.ActiveXObject){ 
			mm = new ActiveXObject('Microsoft.XMLHTTP');
			mm.overrideMimeType('text/xml');
		    }
		    
		    mm.onreadystatechange= AJAXP2; //specify name of function that will handle server response....
		    mm.open('GET',url,true);
		    mm.send();
			
			function AJAXP2() {
			  var data1 = mm.responseText; 
			 
			//document.getElementById('Item_Subcategory_ID').innerHTML = data1;
			}
			
		
			 var url2= 'Consultation_Type='+Consultation_Type+'&Round_ID='+Round_ID+'&<?php if($Registration_ID!=''){echo "Registration_ID=$Registration_ID&"; } ?><?php
	if(isset($_GET['Patient_Payment_ID'])){
	    echo "Patient_Payment_ID=".$_GET['Patient_Payment_ID']."&";
	    }
	if($consultation_ID!=0){
	echo "consultation_ID=".$consultation_ID."&";
	}
	if(isset($_GET['Patient_Payment_Item_List_ID'])){
	echo "Patient_Payment_Item_List_ID=".$_GET['Patient_Payment_Item_List_ID']."&";
	}if(isset($_GET['consultation_ID'])){
			  echo "consultation_ID=".$_GET['consultation_ID']."&";
	}
	?>SearchListDoctorsPagePatientBilling=SearchListDirectCashPatientBillingThisPage';
	
     $.ajax({
			   type:'GET', 
			   url:ul,
			   data:url2,
			    success:function(html){
				 $('#myConsult').html(html);
				 $("#showdataConsult").dialog("open");
			   }
			});
			
			
	
    }
</script>
<?php
    if(isset($_POST['formsubmtt'])){
	$employee_ID= $_SESSION['userinfo']['Employee_ID'];
	$Findings=$_POST['Findings'];
	$Comment_For_Laboratory = $_POST['Comment_For_Laboratory'];
	$Comment_For_Radiology = $_POST['Comment_For_Radiology'];
	$investigation_comments=$_POST['investigation_comments'];
	$remarks=$_POST['remark'];
	$Process_Status = 'served';
	$Patient_Type = '';
	$Ward_Round_Date_And_Time = Date('Y-m-d h:i:s');
	if($Round_ID!=0){
	    //Update here
	    $update_query = "UPDATE tbl_ward_round SET Findings='$Findings',
			    Comment_For_Laboratory='$Comment_For_Laboratory',Comment_For_Radiology='$Comment_For_Radiology',
			    investigation_comments='$investigation_comments',Patient_Type='$Patient_Type',remarks='$remarks',Process_Status='$Process_Status',
			    Ward_Round_Date_And_Time='$Ward_Round_Date_And_Time'
			    WHERE Round_ID = '$Round_ID'";
	    if(mysqli_query($conn,$update_query)){
	    $update_payment = "UPDATE tbl_patient_payment_item_list SET Process_Status = 'served' WHERE Patient_Payment_ID = $Patient_Payment_ID AND
			      (((Patient_Direction = 'Direct To Doctor' OR Patient_Direction='Direct To Doctor Via Nurse Station')
						    AND (Consultant_ID = ".$_SESSION['userinfo']['Employee_ID'].")) OR
						    ((Patient_Direction = 'Direct To Clinic' OR Patient_Direction='Direct To Clinic Via Nurse Station')
						    AND (Consultant_ID IN (SELECT ce.Clinic_ID FROM tbl_clinic_employee ce WHERE ce.Employee_ID = ".$_SESSION['userinfo']['Employee_ID'].")))
						    )
			      ";
	    mysqli_query($conn,$update_payment);
	    $url = "./doctorspageinpatientwork.php";
	    ?>
	    <script type='text/javascript' >
		document.location = '<?php echo $url;?>';
	    </script>
	    <?php
	    }
	}else{
	    
	    $insert_query = "INSERT INTO tbl_ward_round(employee_ID,
                                            Registration_ID,
                                            Findings,
					   investigation_comments,
                                           remarks,
					   Comment_For_Laboratory,
                                           Comment_For_Radiology,
                                           Patient_Payment_Item_List_ID,
                                           Process_Status,
                                           Ward_Round_Date_And_Time)
	    VALUES ('$employee_ID', '$Registration_ID', '$Findings',
                                            '$investigation_comments','$remarks',
					   '$Comment_For_Laboratory','$Comment_For_Radiology',
					   '$Patient_Payment_Item_List_ID','$Process_Status','$Ward_Round_Date_And_Time')";
	    if(mysqli_query($conn,$insert_query)){
		$update_payment = "UPDATE tbl_patient_payment_item_list SET Process_Status = 'served' WHERE Patient_Payment_ID = $Patient_Payment_ID AND
			      (((Patient_Direction = 'Direct To Doctor' OR Patient_Direction='Direct To Doctor Via Nurse Station')
						    AND (Consultant_ID = ".$_SESSION['userinfo']['Employee_ID'].")) OR
						    ((Patient_Direction = 'Direct To Clinic' OR Patient_Direction='Direct To Clinic Via Nurse Station')
						    AND (Consultant_ID IN (SELECT ce.Clinic_ID FROM tbl_clinic_employee ce WHERE ce.Employee_ID = ".$_SESSION['userinfo']['Employee_ID'].")))
						    )
			      ";
		mysqli_query($conn,$update_payment);
		$result = mysqli_query($conn,"SELECT MAX(Round_ID) as Round_ID FROM tbl_ward_round
					       WHERE Registration_ID='$Registration_ID' AND employee_ID='$employee_ID' ");
		$row = mysqli_fetch_assoc($result);
		
		$Round_ID = $row['Round_ID'];
		$url = "./inpatientclinicalnotes.php?Registration_ID=$Registration_ID";
		$url.="&Patient_Payment_Item_List_ID=$Patient_Payment_Item_List_ID&Patient_Payment_ID=$Patient_Payment_ID&SearchListDoctorsPagePatientBilling=SearchListDirectCashPatientBillingThisPage";
		?>
		<script type='text/javascript' >
		    document.location = '<?php echo $url;?>';
		</script>
		<?php
	    }
	}
    }
?>
	<center>
	<table width='100%' style='background: #006400 !important;color: white;'>
	    <tr>
		<td>
		    <center>
		    <b>DOCTORS INPATIENT WORKPAGE : CLINICAL NOTES</b>
		    </center>
		</td>
	    </tr>
	    <tr>
		<td>
		    <center>
			<?php echo strtoupper($Patient_Name).', '.strtoupper($Gender).', ('.$age.'), '.strtoupper($Guarantor_Name);?>
		    </center>
		</td>
	    </tr>
	</table>
        </center>
<fieldset >
    <form action='#' name='clinicalnotes' id='clinicalnotes' method='post'>
	<?php
	//Selecting Submitted Tests,Procedures, Drugs
	$select_payment_cache = "SELECT * 
		FROM 
			tbl_payment_cache ipc,
			tbl_item_list_cache ilc,
			tbl_items i
			WHERE 
				ipc.Round_ID = $Round_ID AND 
				ipc.Payment_Cache_ID = ilc.Payment_Cache_ID	AND 
				i.Item_ID = ilc.Item_ID
				";
				
	$cache_result = mysqli_query($conn,$select_payment_cache);
	$Radiology = '';
	$Laboratory = '';
	$Pharmacy = "";
	$Procedure = "";
	$Surgery = "";
	if(@mysqli_num_rows($cache_result)>0){
	    while($cache_row = mysqli_fetch_assoc($cache_result)){
	       if($cache_row['Check_In_Type']=='Radiology'){
		   $Radiology.= ' '.$cache_row['Product_Name'].';';
	       }
	       if($cache_row['Check_In_Type']=='Laboratory'){
		   $Laboratory.= ' '.$cache_row['Product_Name'].';';
	       }
	       if($cache_row['Check_In_Type']=='Pharmacy'){
		   $Pharmacy.= ' '.$cache_row['Product_Name'].'[ Dosage: '.$cache_row['Doctor_Comment'].' ]'.';   ';
	       }
	       if($cache_row['Check_In_Type']=='Procedure'){
		   $Procedure.= ' '.$cache_row['Product_Name'].';';
	       }
	       if($cache_row['Check_In_Type']=='Surgery'){
		   $Surgery.= ' '.$cache_row['Product_Name'].';';
	       }
	   }   
	}
	
	//Selesting Previously written consultation note for this consultation
	$select_round = "SELECT * FROM tbl_ward_round WHERE Round_ID=$Round_ID";
	$round_result = mysqli_query($conn,$select_round);
	
	if(@mysql_numrows($round_result)>0){
	$round_row = @mysqli_fetch_assoc($round_result);
	$Findings = $round_row['Findings'];
	$Comment_For_Laboratory = $round_row['Comment_For_Laboratory'];
	$Comment_For_Radiology = $round_row['Comment_For_Radiology'];
	$investigation_comments = $round_row['investigation_comments'];
	$remarks = $round_row['remarks'];
	}else{
	$Findings = '';
        $Comment_For_Laboratory = '';
	$Comment_For_Radiology = '';
	$investigation_comments = '';
	$remarks = '';
	}
	
	//selecting diagnosois
	$diagnosis_qr = "SELECT * FROM tbl_ward_round_disease wd,tbl_disease d
		    WHERE wd.Round_ID ='$Round_ID' AND
		    wd.disease_ID = d.disease_ID";
	$result = mysqli_query($conn,$diagnosis_qr);
	$provisional_diagnosis = '';
	$diferential_diagnosis = '';
	$diagnosis = '';
	if(@mysqli_num_rows($result)>0){
	    while($row = mysqli_fetch_assoc($result)){
		if($row['diagnosis_type']=='provisional_diagnosis'){
		    $provisional_diagnosis.= ' '.$row['disease_name'].';';
		}
		if($row['diagnosis_type']=='diferential_diagnosis'){
		    $diferential_diagnosis.= ' '.$row['disease_name'].';';
		}
		if($row['diagnosis_type']=='diagnosis'){
		    $diagnosis.= ' '.$row['disease_name'].';';
		}
	    }   
	}
	?>
	<div id="showdataConsult" style="width:100%;overflow-x:hidden;height:520px;display:none;overflow-y:scroll">
		   <div id="myConsult">
		   </div>
    </div>
	<div id="tabs">
        <ul>
            <li><h3><a href="#observation" style='font-size: small'>Findings</a></h3></li>
            <li><h3><a href="#investigation" style='font-size: small'>Investigation & Results</a></h3></li>
            <li><h3><a href="#diagnosis_treatment" style='font-size: small'>Diagnosis &  Treatment</a></h3></li>
            <li><h3><a href="#servicesmedication" style='font-size: small'>Doctor's Round Services</a></h3></li>
            <li><h3><a href="#remarks" style='font-size: small'>Remarks</a></h3></li>
        </ul>
        <div id="observation">
            <table width=100% style='border: 0px;'>
                <tr><td width='15%' style="text-align:right;">Findings</td><td><textarea style='width: 100%;resize: none;padding-left:5px;' id='Findings' name='Findings'><?php echo $Findings;?></textarea></td></tr>
                <tr><td style="text-align:right;">Provisional Diagnosis</td><td><input style='width: 89%;display:inline' type='text' class='provisional_diagnosis' readonly='readonly' id='provisional_diagnosis' name='provisional_diagnosis' value='<?php echo $provisional_diagnosis;?>'>
                <input type='button' id='select_provisional_diagnosis' name='select_provisional_diagnosis' value='Select' class='art-button-green' onclick='getDiseaseFinal("provisional_diagnosis")'></a></td></tr>
                <tr><td style="text-align:right;">Differential Diagnosis</td><td><input style='width: 89%;display:inline' class='diferential_diagnosis' type='text' readonly='readonly' id='diferential_diagnosis' name='diferential_diagnosis' value='<?php echo $diferential_diagnosis;?>'>
                <input type='button' id='select_provisional_diagnosis' name='select_provisional_diagnosis'  value='Select' class='art-button-green' onclick='getDiseaseFinal("diferential_diagnosis")'></a></td></tr>
            </table>
        </div>
        <div id="investigation">
            <table width=100% style='border: 0px;'>
                <tr><td style="text-align:right;">Laboratory</td><td><textarea style='width: 89%;display:inline' readonly='readonly' id='laboratory' name='laboratory'><?php echo $Laboratory; ?></textarea>
                <input type='button' class='art-button confirmGetItem' id='select_Laboratory' name='select_Laboratory'  value='Select' <?php if($provisional_diagnosis == '' || $provisional_diagnosis == null){ ?> onclick = "confirmDiagnosis('Laboratory')" <?php }else{ ?> onclick="getItem('Laboratory')" <?php } ?> ></a></td></tr>
		<tr><td width='15%' style="text-align:right;">Comments For Laboratory</td><td><input type='text' id='Comment_For_Laboratory' name='Comment_For_Laboratory' value='<?php echo $Comment_For_Laboratory; ?>'></td></tr>
                <tr><td style="text-align:right;">Radiology</td><td><textarea style='width: 89%;display:inline' readonly='readonly' type='text' id='provisional_diagnosis' class='Radiology' name='provisional_diagnosis'><?php echo $Radiology;?></textarea>
                <input type='button' class='art-button-green' id='select_provisional_diagnosis' name='select_provisional_diagnosis' value='Select' onclick="getItem('Radiology')"></a></td></tr>
                <tr><td width='15%' style="text-align:right;">Comments For Radiology</td><td><input type='text' id='Comment_For_Radiology' name='Comment_For_Radiology' value='<?php echo $Comment_For_Radiology;?>'></td></tr>
                <tr><td width='15%' style="text-align:right;">Doctor's Investigation Comments</td><td><textarea style='resize: none;' id='investigation_comments' name='investigation_comments'><?php echo $investigation_comments;?></textarea></td></tr>
            </table>
        </div>
        <div id="diagnosis_treatment">
            <table width=100% style='border: 0px;'>
                <tr><td style="text-align:right;"><b>Final Diagnosis </b></td><td><input style='width: 88%;' type='text' readonly='readonly' id='diagnosis' class='final_diagnosis' name='diagnosis' value='<?php echo $diagnosis;?>'>
                <input type="button"  name="select_provisional_diagnosis" value="Select"  class="art-button-green" onclick="getDiseaseFinal('diagnosis')"></td></tr>
                <tr><td style="text-align:right;">Procedure</td><td><textarea style='width: 88%;resize: none;' type='text' readonly='readonly' class='Procedure' id='provisional_diagnosis' name='provisional_diagnosis'><?php echo $Procedure;?></textarea>
                <input type='button' id='select_provisional_diagnosis' name='select_provisional_diagnosis' value='Select'  class='art-button-green' onclick="getItem('Procedure')"></td></tr>
		
		<tr><td width='15%' style="text-align:right;">Procedure Comments</td><td><input type='text' id='ProcedureComments' name='ProcedureComments'></td></tr>
		
                  <tr><td style="text-align:right;">Surgery</td><td><textarea style='width: 88%;resize: none;' type='text' readonly='readonly' class='Surgery'  id='provisional_diagnosis' name='provisional_diagnosis'><?php echo $Surgery;?></textarea>
		<input type='button' id='select_provisional_diagnosis' name='select_provisional_diagnosis' value='Select'  class='art-button-green' onclick="getItem('Surgery')"></a></td></tr>
		<tr><td width='15%' style="text-align:right;">Sugery Comments</td><td><input style='width: 100%;' type='text' id='SugeryComments' name='SugeryComments'></td></tr>
                 <tr><td style="text-align:right;">Pharmacy</td><td><textarea style='width: 88%;resize: none;' readonly='readonly' id='provisional_diagnosis' class='Treatment' name='provisional_diagnosis'><?php echo $Pharmacy;?></textarea>
                <input type='button' id='select_provisional_diagnosis' name='select_provisional_diagnosis' value='Select'  class='art-button-green' onclick="getItem('Pharmacy')"></a></td></tr>
            </table>
        </div>
        <div id="servicesmedication">

		
		<?php 
			$select_consultation = "SELECT * FROM tbl_consultation WHERE Registration_ID = '$Registration_ID' ORDER BY Registration_ID LIMIT 1";
			$select_consultation_qry = mysqli_query($conn,$select_consultation) or die(mysqli_error($conn));
			$consultation_ID=0;
			while($consult = mysqli_fetch_assoc($select_consultation_qry)){
				$consultation_ID = $consult['consultation_ID'];
			}
		?>		
			<script>
				function getAmount(quantity){
					var price = document.getElementById('HiddenItemPrice').value;
					amount = quantity * price;
					var FormattedAmount = number_format(amount);
					//document.getElementById('Amount').value = FormattedAmount;
					document.getElementById('Amount').value = amount;
					document.getElementById('HiddenAmount').value = amount;
				}
				
				function getItemPricex(values){
					//alert('test');
					var thevalue = values.split(',');
					var itemID = thevalue[0];
					var price = thevalue[1];
					var formattedprice = number_format(price);
					
					//Clear Fields
					document.getElementById('quantity').value = '';
					document.getElementById('Amount').value = '';
					
					document.getElementById('ItemPrice').value = formattedprice;
					document.getElementById('ItemCode').value = itemID;
					document.getElementById('HiddenItemPrice').value = price;
				}

				function AddDocServices() {
					var price = document.getElementById('HiddenItemPrice').value;
					var amount = document.getElementById('HiddenAmount').value;
					var itemID = document.getElementById('ItemCode').value;
					var qty = document.getElementById('quantity').value;
					var bill_type = document.getElementById('bill_type').value;
					var billType ;
					  
					  if (qty=='' || qty==0) {
						 alert('Please enter quantity');
						 exit;
					   }
					  
					
					   if (bill_type=='Cash') {
						  billType = 'Inpatient Cash';
					   }else if(bill_type=='Credit'){
						  billType = 'Inpatient Credit';
					   }
					var reg_ID = '<?php echo $Registration_ID; ?>';
					var emp_ID = '<?php echo $Employee_ID; ?>';
					var cons_ID = '<?php echo $consultation_ID; ?>';
					var folio = '<?php echo $Folio_Number; ?>';
					var spID = '<?php echo $Sponsor_ID; ?>';
					var spName = '<?php echo $Guarantor_Name; ?>';
					
					var branchID = '<?php echo $Branch_ID; ?>';
					if(window.XMLHttpRequest) {
						ds = new XMLHttpRequest();
					}
					else if(window.ActiveXObject){ 
						ds = new ActiveXObject('Micrsoft.XMLHTTP');
						ds.overrideMimeType('text/xml');
					}
					
					ds.onreadystatechange= getDisease_AJAXP; //specify name of function that will handle server response....
					ds.open('GET','AddDocServices.php?price='+price+'&amount='+amount+'&qty='+qty+'&reg_ID='+reg_ID+'&emp_ID='+emp_ID+'&cons_ID='+cons_ID+'&folio='+folio+'&spID='+spID+'&spName='+spName+'&billType='+billType+'&itemID='+itemID+'&branchID='+branchID,true);
					ds.send();
				}
				function getDisease_AJAXP() {
					var response = ds.responseText; 
					document.getElementById('cached_services_iframe').src = './inpatient_docservices_list.php?Registration_ID='+response;
					if(response != ''){
						document.getElementById('SaveAllItems').style.display = 'block';
					}
				}
				
				function number_format(number, decimals, dec_point, thousands_sep) {
					number = (number + '')
					.replace(/[^0-9+\-Ee.]/g, '');
					var n = !isFinite(+number) ? 0 : +number,
					prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
					sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
					dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
					s = '',
					toFixedFix = function(n, prec) {
					var k = Math.pow(10, prec);
					return '' + (Math.round(n * k) / k)
					.toFixed(prec);
					};
					// Fix for IE parseFloat(0.55).toFixed(0) = 0;
					s = (prec ? toFixedFix(n, prec) : '' + Math.round(n))
					.split('.');
					if (s[0].length > 3) {
					s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
					}
					if ((s[1] || '')
					.length < prec) {
					s[1] = s[1] || '';
					s[1] += new Array(prec - s[1].length + 1)
					.join('0');
					}
					return s.join(dec);
				}
				
				function SaveAllItems(){
					var RegID = '<?php echo $Registration_ID; ?>';
					var EmpID = '<?php echo $Employee_ID; ?>';
					if(window.XMLHttpRequest) {
						saveit = new XMLHttpRequest();
					}
					else if(window.ActiveXObject){ 
						saveit = new ActiveXObject('Micrsoft.XMLHTTP');
						saveit.overrideMimeType('text/xml');
					}
					saveit.onreadystatechange= SaveAllItems_AJAXP; 
					saveit.open('GET','inpatient_docservices_save.php?RegID='+RegID+'&EmpID='+EmpID,true);
					saveit.send();
				}
				function SaveAllItems_AJAXP() {
					var results = saveit.responseText;
					document.getElementById('saved').innerHTML = results;
					var given_services_iframe = document.getElementById('given_services_iframe');
					var cached_services_iframe = document.getElementById('cached_services_iframe');
					given_services_iframe.src = given_services_iframe.src;	
					cached_services_iframe.src = cached_services_iframe.src;	
				}
				
			</script>	
			<script>
				function GetItemPrice(valuess){
					var thevalue = valuess.split(',');
					 Item_ID = thevalue[0];
					var price = thevalue[1];
					//var formattedprice = number_format(price);
					//alert('test' + price);
					
					//Clear Fields
					document.getElementById('quantity').value = '';
					document.getElementById('Amount').value = '';
					
					//document.getElementById('ItemPrice').value = formattedprice;
					document.getElementById('ItemPrice').value = price;
					document.getElementById('ItemCode').value = Item_ID;
					document.getElementById('HiddenItemPrice').value = price; 
				}
			</script>			
    <table  width="70%">
		<tr>
			<td width='25%'><b>Item Name</b></td>
			<td width=''><center><b>Billing Type</b></center></td>
			<td width='8%'><center><b>Price</b></center></td>
			<td width='8%'><center><b>Quantity</b></center></td>
			<td width='8%'><center><b>Amount</b></center></td>
			<td width='15%'><center><b>Action</b></center></td>
		</tr>
			<?php
				$docs_items = "SELECT * FROM tbl_items WHERE Ward_Round_Item = 'yes'";
				$docs_items_qry = mysqli_query($conn,$docs_items) or die(mysqli_error($conn));
				$sn = 1;
				echo "<td>";
				echo "<select name='doc_medication' onchange='GetItemPrice(this.value);' style='width:100%;padding:3px;text-align: left;'>";
				echo "<option value=''></option>";
				while($docs_item = mysqli_fetch_assoc($docs_items_qry)){
					$ItemCode = $docs_item['Item_ID'];
					$ItemName = $docs_item['Product_Name'];
					$Price ='';
					if(strtolower($Guarantor_Name) == "cash"){
					  $Price = $docs_item['Selling_Price_Cash'];
					}elseif(strtolower($Guarantor_Name) == "NHIF"){
					  $Price = $docs_item['Selling_Price_NHIF'];
					}else{
					  $Price = $docs_item['Selling_Price_Credit'];
					}
					
					$values = $ItemCode.','.$Price;
					echo "<option value='".$values."' >".$ItemName."</option>";
				}
				echo "</select>";
				echo "</td>";
				?>
				<td>
			    <select id='bill_type' name='bill_type' onchange='getPrice()' required='required' style='width:100%;padding:3px;text-align: left;'>
					<!--<option></option>-->
					<?php
					if(strtolower($Guarantor_Name) != "cash"){
					echo "<option selected='selected'>Credit</option>
					      <option>Cash</option>
					     ";
					}else{
					  echo "<option selected='selected'>Cash</option>
					     ";
					}
					?>
					
				    </select>
			</td>
			<?php
				echo "<td> <input type='text' id='ItemPrice' value='' name='ItemPrice' readonly /></td>";
				echo "<input type='hidden' id='ItemCode' readonly /> ";
				echo "<input type='hidden' id='HiddenItemPrice' name='HiddenItemPrice' />";
				echo "<td> <input type='text' id='quantity' name='quantity' onChange='getAmount(this.value)' onkeyup='getAmount(this.value)'> </td>";
				echo "<td> <input type='text' id='Amount' name='Amount' readonly /> </td>";
				echo "<input type='hidden' id='HiddenAmount' />";
				echo "<td> <input type='button' value='Add' style='width:150px;' onclick='AddDocServices()'/> </td>";
			?>
	</table>

			

			<div>
			<iframe src='./inpatient_docservices_list.php?Registration_ID=<?php echo filter_input(INPUT_GET,'Registration_ID');?>' id='cached_services_iframe' width='100%' height='120px'></iframe>
			</div>
			<?php 
			  $select_docservices = "SELECT * FROM tbl_inpatient_doctorservices_cache 
					WHERE Registration_ID = '$Registration_ID' AND Employee_ID = '$Employee_ID'
				";
	$select_docservices_qry = mysqli_query($conn,$select_docservices) or die(mysqli_error($conn));
	
	    if(mysqli_num_rows($select_docservices_qry) > 0){
			?>
			<div class='art-button-green' style="width:85px; cursor:pointer; floating:left;  color:white;font-size:11px;" onClick='SaveAllItems()' id='SaveAllItems'> Save Services </div>
			<span id='saved'></span>
			<br />
		<?php
		  //die("Found");
		}
		else{
		    // die($select_docservices);
		
		?>
		   <div class='art-button-green' style="width:85px; cursor:pointer; floating:left; display:none; color:white;font-size:11px;" onClick='SaveAllItems()' id='SaveAllItems'> Save Services </div>
			<span id='saved'></span>
			<br /> 
		<?php
		}
		?>
			<iframe src='./inpatient_docservices.php?Registration_ID=<?php echo filter_input(INPUT_GET,'Registration_ID');?>' id='given_services_iframe' width='100%' height='170px'></iframe>
        </div>
		
        <div id="remarks">
            <table width=100% style='border: 0px;'>
                <tr>
				  <td width='15%' style="text-align:right;">Remarks</td><td><textarea style='resize: none;' id='remark' name='remark'><?php echo $remarks;?></textarea></td>
			    </tr>
				<tr>
				  <td width='15%' style="text-align:right;">Patient Status</td>
				  <td>
				    <select id='dischargedPatient' style='width:40%;padding:4px;text-align: left;' onchange='dischargePatient(this.value)'>
					 <option selected="selected">Continue</option>
					 <option>Discharge</option>
				    </select>
				 </td>
			    </tr>
            </table>
        </div>
    </div>
    <input type='hidden' id='formsubmtt' name='formsubmtt'>
	<input type='hidden' id='recentConsultaionTyp' value=''>
	<center>
	<?php
	   if(isset($_GET['Patient_Payment_ID'])){ ?>
	       <input type='submit' id='send' name='send' value='Save' class='art-button-green'  style="width:20%;">
	   <?php }
	?>
        </center>
    </form>
    <script>
	$(function () {     
            $("#tabs").tabs('option','active',<?php
	    if(isset($_GET['Consultation_Type'])){
		$Consultation_Type = $_GET['Consultation_Type'];
		if($Consultation_Type=='provisional_diagnosis'||$Consultation_Type=='diferential_diagnosis'){
		    //add script for setting tab focus
		    echo 1;
		}else if($Consultation_Type=='diagnosis' || $Consultation_Type=='Treatment'|| $Consultation_Type=='Pharmacy'|| $Consultation_Type=='Procedure'){
		    echo 3;
		}else if($Consultation_Type=='Laboratory' || $Consultation_Type=='Radiology'){
		    echo 2;
		}else if($Consultation_Type=='Laboratory'){
		    echo 2;
		}else{
		    echo 0;
		}
	    }else{
			echo 0;
	    }
	?>);
	    });
	$('#tabs').tabs();
    </script>
     <link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
    <script src="js/jquery-1.8.0.min.js"></script>
    <script src="js/jquery-ui-1.8.23.custom.min.js"></script>
    <script src="css/jquery.datetimepicker.js"></script>
    <script>
	$('#firstsymptom_date').datetimepicker({
	dayOfWeekStart : 1,
	lang:'en',
	startDate:	'now'
	});
	$('#firstsymptom_date').datetimepicker({value:'',step:30});
    </script>
</fieldset>
<script>
$(document).ready(function(){
   $("#showdataConsult").dialog({ autoOpen: false, width:'90%', title:'SELECT  ITEM FROM THIS CONSULTATION',modal: true,position:'middle'});
   
 $(".ui-icon-closethick").click(function (){
    var Consultation_Type=document.getElementById("recentConsultaionTyp").value;
	 //alert(Consultation_Type);
	if(Consultation_Type=='provisional_diagnosis' || Consultation_Type=='diferential_diagnosis' || Consultation_Type=='diagnosis'){
	   updateDoctorConsult(); 
	}else{
      updateConsult();
	}
 });
   
});
</script>
<script>
 function updateConsult(){
    //alert('I am here');
	var Consultation_Type=document.getElementById("recentConsultaionTyp").value;
	//alert(Consultation_Type);
     var url2= "Consultation_Type="+Consultation_Type+"&Registration_ID=<?php echo$Registration_ID?>&Round_ID=<?php echo $Round_ID;?>&SearchListDoctorsPagePatientBilling=SearchListDirectCashPatientBillingThisPage";
	  //alert(url2);
	$.ajax({
			   type:'GET', 
			   url:'requests/itemselectupdateinpatient.php',
			   data:url2,
			   cache:false,
			   success:function(html){
				   //alert(html);
				   var Consultation_Type=html.split('<$$$&&&&>');
				       //alert(Consultation_Type[0]);
				      if(Consultation_Type[0]=='Radiology'){
						$('.Radiology').html(Consultation_Type[1]);
					  }else if(Consultation_Type[0]=='Treatment'){
						$('.Treatment').html(Consultation_Type[1]);
					  }else if(Consultation_Type[0]=='Laboratory'){
						$('#laboratory').html(Consultation_Type[1]);
						//alert(Consultation_Type[0]+"  "+Consultation_Type[1]);
					  }else if(Consultation_Type[0]=='Procedure'){
						$('.Procedure').html(Consultation_Type[1]);
					  }else if(Consultation_Type[0]=='Surgery'){
						$('.Surgery').html(Consultation_Type[1]);
				      }
			   }
			});
	      
 }

</script>
<script>
function updateDoctorConsult(){
    //alert('I am here');
	var Consultation_Type=document.getElementById("recentConsultaionTyp").value;
	//alert(Consultation_Type);
    var url2= "Consultation_Type="+Consultation_Type+"&Registration_ID=<?php echo$Registration_ID?>&Round_ID=<?php echo $Round_ID;?>&SearchListDoctorsPagePatientBilling=SearchListDirectCashPatientBillingThisPage";
	  //alert(url2);
	$.ajax({
			   type:'GET', 
			   url:'requests/itemdoctorselectupdateinpatient.php',
			   data:url2,
			   cache:false,
			   success:function(html){
				   var Consultation_Type=html.split('<$$$&&&&>');
				       
				      if(Consultation_Type[0]=='provisional_diagnosis'){
						$('.provisional_diagnosis').attr('value',Consultation_Type[1]);
						
						if($('.provisional_diagnosis').val() !=''){
						  $('.confirmGetItem').attr("onclick","getItem('Laboratory')");
						}else{
						   $('.confirmGetItem').attr("onclick","confirmDiagnosis('Laboratory')");
						}
					  }else if(Consultation_Type[0]=='diferential_diagnosis'){
					    //alert(Consultation_Type[0]+"  "+Consultation_Type[1]);
						$('.diferential_diagnosis').attr('value',Consultation_Type[1]);
					  }else if(Consultation_Type[0]=='diagnosis'){
						$('.final_diagnosis').attr('value',Consultation_Type[1]);
					  }
			   }
			});
	      
 }

</script>
<script>
 function doneDiagonosisselect(){
   var Consultation_Type=document.getElementById("recentConsultaionTyp").value;
	 //alert(Consultation_Type);
	if(Consultation_Type=='provisional_diagnosis' || Consultation_Type=='diferential_diagnosis' || Consultation_Type=='diagnosis'){
	   updateDoctorConsult(); 
	}else{
      updateConsult();
	}
	
	$("#showdataConsult").dialog("close");
 }
</script>
<script>
function getPrice() {
		//alert(Item_ID) ;
		var bill_type = document.getElementById('bill_type').value;
		var Billing_Type;
		if (Item_ID!='') {
		    if (bill_type=='Cash') {
			var Billing_Type = 'Inpatient Cash';
		    }else if(bill_type=='Credit'){
			var Billing_Type = 'Inpatient Credit';
		    }
		    var Guarantor_Name = '<?php echo $Guarantor_Name; ?>';
		    if(window.XMLHttpRequest) {
			mm2 = new XMLHttpRequest();
		    }
		    else if(window.ActiveXObject){ 
			mm2 = new ActiveXObject('Micrsoft.XMLHTTP');
			mm2.overrideMimeType('text/xml');
		    }
			mm2.onreadystatechange= AJAXP4; //specify name of function that will handle server response....
			mm2.open('GET','Get_Item_price.php?Product_Name='+Item_ID+'&Billing_Type='+Billing_Type+'&Guarantor_Name='+Guarantor_Name,true);
			mm2.send();
		    }
		}
	    function AJAXP4(){
			if (mm2.readyState == 4) {
			//al
				var data4 = mm2.responseText;//quantity
				//alert(data4);
				document.getElementById('ItemPrice').value = data4;
				
				var quantity=parseInt(document.getElementById('quantity').value);
				var ItemPrice=parseInt(document.getElementById('ItemPrice').value);
				var Amount='';
				
				//alert(document.getElementById('quantity').value);
				
				if(!isNaN(quantity)){
				   //alert(quantity);
				   Amount=quantity*ItemPrice;
				   document.getElementById('Amount').value = Amount;
				   
				}
				
				document.getElementById('HiddenItemPrice').value=ItemPrice;
				document.getElementById('HiddenAmount').value=Amount;
				//Calculate_Amount();
			}
	    }
	    
</script>
<script>
 function dischargePatient(status){
   //alert(status);
   if(status != ''){
    if(confirm('Are you sure you want to change the patient to '+status)){
	   
		if(window.XMLHttpRequest) {
			mm2 = new XMLHttpRequest();
		}
		else if(window.ActiveXObject){ 
		mm2 = new ActiveXObject('Microsoft.XMLHTTP');
		mm2.overrideMimeType('text/xml');
		}
		
		mm2.onreadystatechange= AJAXP4; //specify name of function that will handle server response....
		mm2.open('GET','doctor_discharge_release.php?status='+status+'&consultation_ID=<?php echo $_GET['consultation_ID']?>&Registration_ID=<?php echo $Registration_ID?>&Folio_Number=<?php echo $_GET['Folio_Number']?>',true);
		mm2.send();
  }
  } 

  
 }
 
 function AJAXP4(){
		if (mm2.readyState == 4) {
		  var data= mm2.responseText;
		     //alert(data);
		   if(data == '1'){
		     window.location='admittedpatientlist.php?SearchListDoctorsPagePatientBilling=SearchListDirectCashPatientBillingThisPage';
		   }
			
		}
	}
</script>
<?php
    include("./includes/footer.php");
?>