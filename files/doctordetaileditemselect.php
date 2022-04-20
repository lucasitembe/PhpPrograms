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
	
	if($Consultation_Type=='Sugery'){
	    $Consultation_Type = 'Theater';
	}
	if($Consultation_Type=='Treatment'){
	    $Consultation_Type = 'Pharmacy';
	}
	if(strtolower($Consultation_Type)=='procedure'){
	    $Consultation_Condition = "((d.Department_Location='Dialysis') OR
	    (d.Department_Location='Physiotherapy') OR (d.Department_Location='Optical')OR
	    (d.Department_Location='Dressing')OR(d.Department_Location='Maternity')OR
	    (d.Department_Location='Cecap')OR(d.Department_Location='Dental')OR(d.Department_Location='Ear'))";
	    
	    $Consultation_Condition2 = "((Consultation_Type='Dialysis') OR
	    (Consultation_Type='Physiotherapy') OR (Consultation_Type='Optical')OR
	    (Consultation_Type='Dressing')OR(Consultation_Type='Maternity')OR
	    (Consultation_Type='Cecap')OR(Consultation_Type='Dental')OR(Consultation_Type='Ear'))";
	}else{
	    $Consultation_Condition = "d.Department_Location = '$Consultation_Type'";
	    $Consultation_Condition2 = "Consultation_Type='$Consultation_Type'";
	}
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
?>
<!-- GET POST VARIABLES FROM doctoritemselect.php -->
    <?php
    
    $Item_Category_ID = $_GET['Item_Category_ID'];
    $Item_Subcategory_ID = $_GET['Item_Subcategory_ID'];
    
    // on submit repost them again to avoid error...
    ?>
<!-- xxxxxx end of GET POST VARIABLES FROM doctoritemselect.php xxxxx -->

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
	    $years = $diff->y." Years ";
	    $months =$diff->m.' Months ';
	    $days = $diff->d.' Days ';
	    $hrs =  $diff->h.' Hours';
	    if($diff->y==0){
		$years = '';
	    }
	    if($diff->m==0){
		$months = '';
	    }
	    if($diff->d==0){
		$days = '';
	    }
	    if($diff->h==0){
		$hrs = '';
	    }
	    $age = $years.$months.$days.$hrs;
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
    <a href='doctoritemselect.php?Consultation_Type=<?php
    echo $_GET['Consultation_Type'];
    ?>&consultation_id=<?php
    echo $_GET['consultation_id'];
    ?>&Registration_ID=<?php
    echo $_GET['Registration_ID'];
    ?>&Patient_Payment_Item_List_ID=<?php
    echo $_GET['Patient_Payment_Item_List_ID'];
    ?>&Patient_Payment_ID=<?php
    echo $_GET['Patient_Payment_ID'];
    ?>&SearchListDoctorsPagePatientBilling=SearchListDirectCashPatientBillingThisPage' class='art-button-green'>
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
    function searchItem() {
        test_name = document.getElementById('test_name').value;
	Item_Subcategory_ID = document.getElementById('Item_Subcategory_ID').value;
	Item_Category_ID = document.getElementById('Item_Category_ID').value;
        document.getElementById('frmaesearch').innerHTML = "<iframe src='./doctordetaileditemselect_Iframe.php?test_name="+test_name+"&Item_Subcategory_ID="+Item_Subcategory_ID+"&Item_Category_ID="+Item_Category_ID+"&Consultation_Type=<?php
        echo $Consultation_Type;?>&Patient_Payment_ID=<?php
        echo $Patient_Payment_ID;
        ?>&consultation_id=<?php
        echo $consultation_id;?>&Consultation_Type=<?php
        echo $Consultation_Type;?>&Patient_Payment_Item_List_ID=<?php
        echo $Patient_Payment_Item_List_ID;?>&Patient_Payment_ID=<?php
        echo $Patient_Payment_ID;?>&Registration_ID=<?php
        echo $Registration_ID; ?>&Payment_Cache_ID=<?php
        echo $payment_cache_ID;?>&SearchListDoctorsPagePatientBilling=SearchListDirectCashPatientBillingThisPage' width='100%' height='300px'></iframe>";
    }
</script>
    <center>
	<table width='100%' style='background: #006400 !important;color: white;'>
	    <tr>
		<td>
		    <center>
		    <b>DOCTORS WORKPAGE : <?php if(isset($_GET['Consultation_Type'])){
		     echo    strtoupper($Consultation_Type);
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
        <table width='100%'>
	<!--filtering services against categories-->
	<script type="text/javascript" language="javascript">
	    function getSubcategory(Item_Category_ID) {
		    if(window.XMLHttpRequest) {
			mm = new XMLHttpRequest();
		    }
		    else if(window.ActiveXObject){ 
			mm = new ActiveXObject('Micrsoft.XMLHTTP');
			mm.overrideMimeType('text/xml');
		    }
		    
		    mm.onreadystatechange= AJAXP2; //specify name of function that will handle server response....
		    mm.open('GET','GetItemSubcategory.php?Item_Category_ID='+Item_Category_ID,true);
		    mm.send();
		}
	    function AJAXP2() {
		var data1 = mm.responseText; 
		document.getElementById('Item_Subcategory_ID').innerHTML = data1;
	    }
	</script>
	    <td width='50px'>Category</td>
		    <td width='275px'>
			<select id='Item_Category_ID' style='width: 100%;' name='Item_Category_ID' onchange='getSubcategory(this.value)' required='required' style='width: 180px;'>
			    <option>All</option>
			    <?php
			    if($Consultation_Type=='Pharmacy'){
				$qr = "SELECT * FROM tbl_item_category as ic
				join tbl_item_subcategory as iss on iss.Item_Category_ID = ic.Item_Category_ID
				join tbl_items  as i on iss.Item_Subcategory_ID = i.Item_Subcategory_ID
				WHERE i.Item_Type='Pharmacy' group by ic.Item_Category_ID";
				
			    }else{
				$qr = "SELECT * FROM tbl_item_category as ic
				join tbl_item_subcategory as iss on iss.Item_Category_ID = ic.Item_Category_ID
				WHERE iss.Item_Subcategory_ID IN
				(SELECT Item_Subcategory_ID FROM tbl_items where $Consultation_Condition2)
				group by ic.Item_Category_ID
				";
			    }
			    $result = mysqli_query($conn,$qr);
			    while($row = mysqli_fetch_assoc($result)){
				?>
				<option <?php
					if($row['Item_Category_ID'] == $Item_Category_ID){
					?>
					selected='selected'
					<?php
					}
					?>
					value="<?php echo $row['Item_Category_ID']?>">
				    <?php echo $row['Item_Category_Name']?>
				</option>
				<?php
			    }
			    ?>
			</select>
		    </td>
		    <td width='50px'>Subcategory</td>
		    <td width='275px'>
			<select id='Item_Subcategory_ID' style='width: 100%;' name='Item_Subcategory_ID' onchange='searchItem()' required='required'>
			    <option>All</option>
			    <?php
			    if($Consultation_Type=='Pharmacy'){
				$qr = "SELECT * FROM tbl_item_subcategory as iss
				WHERE iss.Item_Subcategory_ID IN
				(SELECT Item_Subcategory_ID FROM tbl_items where Item_Type='Pharmacy')
				group by iss.Item_Subcategory_ID";
			    }else{
				$qr = "SELECT * FROM tbl_item_subcategory as iss
				WHERE iss.Item_Subcategory_ID IN
				(SELECT Item_Subcategory_ID FROM tbl_items where $Consultation_Condition2)
				group by iss.Item_Subcategory_ID";
			    }
			    $result = mysqli_query($conn,$qr);
			    while($row = mysqli_fetch_assoc($result)){
				?>
				<option
					<?php
					if($row['Item_Subcategory_ID'] == $Item_Subcategory_ID){
					?>
					selected='selected'
					<?php
					}
					?>
					value='<?php echo $row['Item_Subcategory_ID'];?>'>
				    <?php echo $row['Item_Subcategory_Name'];?>
				</option>
				<?php
			    }
			    ?>
			</select>
		    </td>
            <td>
                <input type='text' onkeyup='searchItem()' size='60' placeholder='-----------------------------Ingiza kipimo kukitafuta kwenye Database------------------------------------------' id='test_name' name='test_name'>
            </td>
	    <td><input type='button' class='art-button-green' value='Filter' onclick='searchItem()'></td>
        </table>
    </center>
<fieldset id='frmaesearch'>
    <iframe src="./doctordetaileditemselect_Iframe.php?Item_Subcategory_ID=<?php echo $Item_Subcategory_ID;?>&Item_Category_ID=<?php echo $Item_Category_ID;?>&Consultation_Type=<?php echo $Consultation_Type;?>&Patient_Payment_ID=<?php echo $Patient_Payment_ID;
    ?>&consultation_id=<?php echo $consultation_id;?>&Consultation_Type=<?php echo $Consultation_Type;?>&Patient_Payment_Item_List_ID=<?php echo $Patient_Payment_Item_List_ID;?>&Patient_Payment_ID=<?php echo $Patient_Payment_ID;?>&Registration_ID=<?php echo $Registration_ID; ?>&Payment_Cache_ID=<?php echo $payment_cache_ID;?>&SearchListDoctorsPagePatientBilling=SearchListDirectCashPatientBillingThisPage" width='100%' height='300px'></iframe>
</fieldset>
<fieldset>
    <table width='100%'>
	<tr>
	    <td style='text-align: right;width: 40%'>
		<?php
		$Select_Sum = "SELECT (SELECT SUM(ppl.Price) FROM tbl_patient_payments pp,tbl_patient_payment_item_list ppl
			      WHERE pp.Billing_Type = 'Outpatient Credit' AND  pp.Folio_Number = $Folio_Number AND
			      ppl.Patient_Payment_ID = pp.Patient_Payment_ID AND
			      pp.Sponsor_ID=$Sponsor_ID AND
			      pp.Receipt_Date >= (SELECT f.Folio_date FROM tbl_folio f WHERE f.Folio_Number = 1 AND f.Branch_ID=16 ORDER BY f.Folio_ID DESC LIMIT 1)) as Credit,
			      
			      (SELECT SUM(ppl.Price) FROM tbl_patient_payments pp,tbl_patient_payment_item_list ppl
			      WHERE pp.Billing_Type = 'Outpatient Cash' AND  pp.Folio_Number = $Folio_Number AND
			      ppl.Patient_Payment_ID = pp.Patient_Payment_ID AND
			      pp.Sponsor_ID=$Sponsor_ID AND
			      pp.Receipt_Date >= (SELECT f.Folio_date FROM tbl_folio f WHERE f.Folio_Number = 1 AND f.Branch_ID=16 ORDER BY f.Folio_ID DESC LIMIT 1)) as Cash
			      ";
		$sum_result = mysqli_query($conn,$Select_Sum);
		$sum_row = mysqli_fetch_assoc($sum_result);
		?>
		<b>Overall Cash </b>
	    </td>
	    <td width='10%'>
		<input type='text' style='text-align: right' size='3' value='<?php echo number_format($sum_row['Cash']);?>' readonly>
	    </td>
	    <td width='10%' style='text-align: right'>
		<b>Overall Credit </b>
	    </td>
	    <td width='10%'>
	    <input type='text' style='text-align: right' size='4' value='<?php echo number_format($sum_row['Credit']);?>' readonly>
	    </td>
	    <td style='text-align: right;'>
		<a href='clinicalnotes.php?Consultation_Type=<?php echo $Consultation_Type;?>&<?php if($Registration_ID!=''){echo "Registration_ID=$Registration_ID&"; } ?><?php
		if(isset($_GET['Patient_Payment_ID'])){
		    echo "Patient_Payment_ID=".$_GET['Patient_Payment_ID']."&";
		    }
		if(isset($_GET['Patient_Payment_Item_List_ID'])){
		    echo "Patient_Payment_Item_List_ID=".$_GET['Patient_Payment_Item_List_ID']."&";
		    }
		if(isset($_GET['consultation_id'])){
		    echo "consultation_id=".$_GET['consultation_id']."&";
		    } ?>SearchListDoctorsPagePatientBilling=SearchListDirectCashPatientBillingThisPage' class='art-button-green'>
		    DONE
		</a>
	    </td>
	</tr>
    </table>
</fieldset>
<?php
    include("./includes/footer.php");
?>