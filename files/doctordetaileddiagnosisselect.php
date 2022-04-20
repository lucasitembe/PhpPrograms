<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'])){
	    if($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'] != 'yes'){
		header("Location: ./index.php?InvalidPrivilege=yes");
	    }
	}else{
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	    header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_GET['Consultation_Type'])){
	$Consultation_Type = $_GET['Consultation_Type'];
    }
    
    if(isset($_GET['consultation_id'])){
	$consultation_id = $_GET['consultation_id'];
    }else{
	header("Location: ./index.php?InvalidPrivilege=yes"); 
    }
    if(isset($_GET['Patient_Payment_Item_List_ID'])){
	$Patient_Payment_Item_List_ID = $_GET['Patient_Payment_Item_List_ID'];
	}else{
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    if(isset($_GET['payment_cache_ID'])){
	$payment_cache_ID = $_GET['payment_cache_ID'];
    }else{
	$select_pcid = "SELECT payment_cache_ID FROM tbl_payment_cache WHERE consultation_id = $consultation_id";
	$Ppcid_result = mysqli_query($conn,$select_pcid);
	if(@mysql_numrows($Ppcid_result)>0){
	    	$Ppcid_row = mysqli_fetch_assoc($Ppcid_result);
		$payment_cache_ID = $Ppcid_row['payment_cache_ID'];   
	}else{
	    $payment_cache_ID = 0;
	}
    }
    if(isset($_GET['Patient_Payment_Item_List_ID'])){
	$Patient_Payment_Item_List_ID = $_GET['Patient_Payment_Item_List_ID'];
    }else{
	$Patient_Payment_Item_List_ID = 0;
    }
    
    //Get Subcategory and category from diagnosisselect
    if(isset($_GET['disease_category_ID'])){
	$disease_category_ID = $_GET['disease_category_ID'];
    }else{
	$disease_category_ID = 0;
    }
    
    if(isset($_GET['subcategory_ID'])){
	$subcategory_ID = $_GET['subcategory_ID'];
    }else{
	$subcategory_ID = 0;
    }
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
        if($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'] == 'yes'){
?>
    <a href='doctorpatientfile.php?<?php if($Registration_ID!=''){echo "Registration_ID=$Registration_ID&"; } ?><?php
    if(isset($_GET['Patient_Payment_ID'])){
	echo "Patient_Payment_ID=".$_GET['Patient_Payment_ID']."&";
	} ?>SearchListDoctorsPagePatientBilling=SearchListDirectCashPatientBillingThisPage' class='art-button-green'>
        PATIENT FILE
    </a>
    <a id='finesearch' href='doctordiagnosisselect.php?Consultation_Type=<?php
		    echo $Consultation_Type;
		    ?>&consultation_id=<?php
		    echo $consultation_id;
		    ?>&Registration_ID=<?php
		    echo $Registration_ID.'&';
    if(isset($_GET['Patient_Payment_ID'])){
	echo "Patient_Payment_ID=".$_GET['Patient_Payment_ID']."&";
	}
    if(isset($_GET['Patient_Payment_Item_List_ID'])){
	echo "Patient_Payment_Item_List_ID=".$_GET['Patient_Payment_Item_List_ID']."&";
	}
    if(isset($_GET['consultation_id'])){
	echo "consultation_id=".$_GET['consultation_id']."&";
	} ?>SearchListDoctorsPagePatientBilling=SearchListDirectCashPatientBillingThisPage' class='art-button-green'>
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
    }else{
	$Employee_Name = 'Unknown Employee';
    }
?>

<script type='text/javascript'>
    function access_Denied(){ 
   alert("Access Denied");
   document.location = "./index.php";
    }
    function searchDisease() {
        disease_name = document.getElementById('disease_name').value;
	subcategory_ID = document.getElementById('subcategory_ID').value;
	disease_category_ID = document.getElementById('disease_category_ID').value;
        document.getElementById('doctordetaileddiagnosisselect_Iframe').src ='./doctordetaileddiagnosisselect_Iframe.php?disease_name='+disease_name+'&disease_category_ID='+disease_category_ID+'&subcategory_ID='+subcategory_ID+'&consultation_id=<?php echo $consultation_id;?>&Consultation_Type=<?php echo $Consultation_Type;?>&Registration_ID=<?php echo $Registration_ID; ?>&Payment_Cache_ID=<?php echo $payment_cache_ID;?>&SearchListDoctorsPagePatientBilling=SearchListDirectCashPatientBillingThisPage';
    }
</script>
        <center>
	<table width='100%' style='background: #006400 !important;color: white;'>
	    <tr>
		<td>
		    <center>
		    <b>DOCTORS WORKPAGE : <?php if($_GET['Consultation_Type']=='provisional_diagnosis'){
			    echo "PROVISSIONAL DIAGNOSIS";
			   }elseif($_GET['Consultation_Type']=='diferential_diagnosis'){
			       echo "DIFERENTIAL DIAGNOSIS";
			   }else{
			       echo "DIAGNOSIS";
			   }
			   ?> (<?php if($Consultation_Type=='Laboratory'){
                        echo "TESTS ";
                        }?>SELECTION)</b>
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
	<script type="text/javascript" language="javascript">
	    function getSubcategory(disease_category_ID) {
		    if(window.XMLHttpRequest) {
			mm = new XMLHttpRequest();
		    }
		    else if(window.ActiveXObject){ 
			mm = new ActiveXObject('Micrsoft.XMLHTTP');
			mm.overrideMimeType('text/xml');
		    }
			
		    mm.onreadystatechange= AJAXP; //specify name of function that will handle server response....
		    mm.open('GET','GetDiseaseSubcategory.php?disease_category_ID='+disease_category_ID,true);
		    mm.send();
		}
	    function AJAXP() {
		var data = mm.responseText; 
		document.getElementById('subcategory_ID').innerHTML = data;	
	    }
	</script>
	<script type="text/javascript" language="javascript">
	    function getDisease() {
		    var subcategory_ID = document.getElementById('subcategory_ID').value;
		    if(window.XMLHttpRequest) {
			mm1 = new XMLHttpRequest();
		    }
		    else if(window.ActiveXObject){ 
			mm1 = new ActiveXObject('Micrsoft.XMLHTTP');
			mm1.overrideMimeType('text/xml');
		    }
			
		    mm1.onreadystatechange= getDisease_AJAXP; //specify name of function that will handle server response....
		    mm1.open('GET','GetDisease.php?subcategory_ID='+subcategory_ID,true);
		    mm1.send();
		}
	    function getDisease_AJAXP() {
		var data1 = mm1.responseText; 
		document.getElementById('disease_ID').innerHTML = data1;	
	    }
	</script>

<!--ON SUBMIT THIS EXECUTES-->
<?php
    if(isset($_POST['submitted'])){
        $consultation_ID = $consultation_id;
        $disease_ID = $_POST['disease_ID'];
        $diagnosis_type = $Consultation_Type;
        $insert_query = "INSERT INTO tbl_disease_consultation(disease_ID,consultation_ID,diagnosis_type)
                        VALUES('$disease_ID','$consultation_ID','$diagnosis_type')";
	if(mysqli_query($conn,$insert_query)){
		$url = "doctordiagnosisselect.php?Consultation_Type=$Consultation_Type&Patient_Payment_Item_List_ID=$Patient_Payment_Item_List_ID&consultation_id=$consultation_id&Registration_ID=$Registration_ID&payment_cache_ID=$payment_cache_ID&Patient_Payment_ID=$Patient_Payment_ID&SearchListDoctorsPagePatientBilling=SearchListDirectCashPatientBillingThisPage";
		?>
		<script type='text/javascript'>
		    document.location = '<?php echo $url;?>';
		</script>
	<?php
	}
    }
?>
<fieldset>
    <center>
	<form action='#' method='Post'>
	    <input type='hidden' id='submitted' name='submitted' value=<?php echo $payment_cache_ID; ?>>
	    <table width='100%'>
		<tr>
		    <td style='text-align: right;width: 9%'>Disease Category</td>
		    <td width='5 px'>
			<select id='disease_category_ID' onchange='getSubcategory(this.value);' name='disease_category_ID' required='required' style='width: 180px;'>
			    <option>ALL</option>
			    <?php
			    $qr = "SELECT * FROM tbl_disease_category";
			    
			    $result = mysqli_query($conn,$qr);
			    while($row = mysqli_fetch_assoc($result)){
				?>
				<option value='<?php echo $row['disease_category_ID']?>' <?php
				if($disease_category_ID == $row['disease_category_ID'] ){
				    ?>
				    selected='selected'
				    <?php
				    } ?> >
				    <?php echo $row['category_discreption']; ?>
				</option>
				<?php
			    }
			    ?>
			</select>
		    </td>
		    <td style='text-align: right;width: 14%'>Disease Sub Category</td>
		    <td><select id='subcategory_ID' name='subcategory_ID' onchange='searchDisease()' required='required' style='width: 180px;'>
			    <option>ALL</option>
			    <?php
			    if($disease_category_ID=='ALL'){
				$qr = "SELECT * FROM tbl_disease_subcategory";
			    }else{
				$qr = "SELECT * FROM tbl_disease_subcategory WHERE disease_category_ID=$disease_category_ID";
			    }
			    
			    $result = mysqli_query($conn,$qr);
			    while($row = mysqli_fetch_assoc($result)){
				?>
				<option value='<?php echo $row['subcategory_ID']?>'<?php
				if($subcategory_ID == $row['subcategory_ID'] ){
				    ?>
				    selected='selected'
				    <?php
				    } ?>>
				    <?php echo $row['subcategory_description']?>
				</option>
				<?php
			    }
			    ?>
			</select>
		    </td>
		    <td style='text-align: right;'>Disease</td>
		    <td><input type='text' id='disease_name' name='disease_name' style='width: 100%;' onkeyup='searchDisease()' placeholder='---------Andika Ugonjwa Hapa Kuutafuta-------------'>
		    </td>
		    <td style='text-align: right'>
		    <input type='button' id='submitsearch' name='submitsearch' value='Search' onclick='searchDisease()' class='art-button-green'>
		    </td>
		</tr>
	    </table>
	</form>
    </center>
</fieldset>
<br>
<fieldset>
    <iframe id='doctordetaileddiagnosisselect_Iframe' src='./doctordetaileddiagnosisselect_Iframe.php?disease_category_ID=<?php echo $disease_category_ID;?>&subcategory_ID=<?php echo $subcategory_ID;?>&consultation_id=<?php echo $consultation_id;?>&Consultation_Type=<?php echo $Consultation_Type;?>&Registration_ID=<?php echo $Registration_ID; ?>&Payment_Cache_ID=<?php echo $payment_cache_ID;?>&SearchListDoctorsPagePatientBilling=SearchListDirectCashPatientBillingThisPage' width='100%' height='300px'></iframe>
</fieldset>
<table align='right'>
    <tr>
	<td style='text-align: right;'>
	    <?php
	    if(isset($_SESSION['userinfo'])){
		if($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'] == 'yes'){
		?>
	    <a id='finesearch' href='doctordiagnosisselect.php?Consultation_Type=<?php
			    echo $Consultation_Type;
			    ?>&consultation_id=<?php
			    echo $consultation_id;
			    ?>&Registration_ID=<?php
			    echo $Registration_ID.'&';
	    if(isset($_GET['Patient_Payment_ID'])){
		echo "Patient_Payment_ID=".$_GET['Patient_Payment_ID']."&";
		}
	    if(isset($_GET['Patient_Payment_Item_List_ID'])){
		echo "Patient_Payment_Item_List_ID=".$_GET['Patient_Payment_Item_List_ID']."&";
		}
	    if(isset($_GET['consultation_id'])){
		echo "consultation_id=".$_GET['consultation_id']."&";
		} ?>SearchListDoctorsPagePatientBilling=SearchListDirectCashPatientBillingThisPage' class='art-button-green'>
		Done
	    </a>
	<?php  } } ?>
	</td>
    </tr>
</table>
<?php
    include("./includes/footer.php");
?>