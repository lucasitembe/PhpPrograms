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
	    $Consultation_Condition = "((d.Department_Location='Dialisis') OR
	    (d.Department_Location='Physiotherapy') OR (d.Department_Location='Optical')OR
	    (d.Department_Location='Dressing')OR(d.Department_Location='Maternity')OR
	    (d.Department_Location='Cecap')OR(d.Department_Location='Dental')OR(d.Department_Location='Ear'))";
	    
	    $Consultation_Condition2 = "((Consultation_Type='Dialisis') OR
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
    <a href='clinicalnotes.php?Consultation_Type=<?php echo $_GET['Consultation_Type']; ?>&<?php if($Registration_ID!=''){echo "Registration_ID=$Registration_ID&"; } ?><?php
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
</script>
    <center>
	<table width='100%' style='background: #006400 !important;color: white;'>
	    <tr>
		<td>
		    <center>
		    <b>DOCTORS WORKPAGE : <?php if(isset($_GET['Consultation_Type'])){
		     echo    strtoupper($Consultation_Type);
		    }
		    ?></b>
		    </center>
		</td>
	    </tr>
	    <tr>
		<td>
		    <center>
			<b><?php echo strtoupper($Patient_Name).', '.strtoupper($Gender).', ('.$age.'), '.strtoupper($Guarantor_Name);?></b>
		    </center>
		</td>
	    </tr>
	</table>
    </center>
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
	<script type="text/javascript" language="javascript">
	    function getItem(Item_Subcategory_ID) {
		    if(window.XMLHttpRequest) {
			mm1 = new XMLHttpRequest();
		    }
		    else if(window.ActiveXObject){ 
			mm1 = new ActiveXObject('Micrsoft.XMLHTTP');
			mm1.overrideMimeType('text/xml');
		    }
		    mm1.onreadystatechange= AJAXP; //specify name of function that will handle server response....
		    mm1.open('GET','GetItemBySubcategory.php?Item_Subcategory_ID='+Item_Subcategory_ID,true);
		    mm1.send();
		}
	    function AJAXP() {
		var data2 = mm1.responseText; 
		document.getElementById('Item_ID').innerHTML = data2;
	    }
	</script>
	<script type='text/javascript'>
	    function getPrice() {
		var Product_Name = document.getElementById('Item_ID').value;
		var bill_type = document.getElementById('bill_type').value;
		var Billing_Type;
		if (Product_Name!='') {
		    if (bill_type=='Cash') {
			var Billing_Type = 'Outpatient Cash';
		    }else if(bill_type=='Credit'){
			var Billing_Type = 'Outpatient Credit';
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
			mm2.open('GET','Get_Item_price.php?Product_Name='+Product_Name+'&Billing_Type='+Billing_Type+'&Guarantor_Name='+Guarantor_Name,true);
			mm2.send();
		}
		}
	    function AJAXP4(){
		var data4 = mm2.responseText;
		document.getElementById('price').value = data4;
		var price = document.getElementById('price').value;
		var quantity = document.getElementById('quantity').value;
		var ammount = 0;
		
		ammount = price*quantity;
		document.getElementById('ammount').value = ammount;
	    }
	</script>
	<script type="text/javascript" language="javascript">
	    function getLocationQueSize() {
		var Sub_Department_ID = document.getElementById('Sub_Department_ID').value;
		    if(window.XMLHttpRequest) {
			mm5 = new XMLHttpRequest();
		    }
		    else if(window.ActiveXObject){ 
			mm5 = new ActiveXObject('Micrsoft.XMLHTTP');
			mm5.overrideMimeType('text/xml');
		    }
		    mm5.onreadystatechange= AJAXP5; //specify name of function that will handle server response....
		    mm5.open('GET','getLocationQueSize.php?Sub_Department_ID='+Sub_Department_ID,true);
		    mm5.send();
		}
	    function AJAXP5() {
		var data5 = mm5.responseText; 
		document.getElementById('queue').value = data5;
	    }
	</script>
	
	<script type='text/javascript'>
	    function changeFineSearch(){
		var Item_Subcategory_ID = document.getElementById('Item_Subcategory_ID').value;
		var Item_Category_ID = document.getElementById('Item_Category_ID').value;
		
		document.location = 'doctordetailedPharmacyitemselect.php?Consultation_Type=<?php
		    echo $Consultation_Type;
		    ?>&consultation_id=<?php
		    echo $consultation_id;
		    ?>&Registration_ID=<?php
		    echo $Registration_ID;
		    ?>&Patient_Payment_Item_List_ID=<?php
		    echo $Patient_Payment_Item_List_ID;
		    ?>&Patient_Payment_ID=<?php
		    echo $Patient_Payment_ID;
		    ?>&Item_Category_ID='+Item_Category_ID+'&Item_Subcategory_ID='+Item_Subcategory_ID+'&SearchListDoctorsPagePatientBilling=SearchListDirectCashPatientBillingThisPage';
		
	    }
	</script>
	
<!--ON SUBMIT THIS EXECUTES-->
<?php
    $inserted = TRUE;
    if(isset($_POST['submitted'])){
	if($_POST['submitted']==0){
	    
	    $Payment_Date_And_Time = '(SELECT NOW())';
	    $Receipt_Date = Date('Y-m-d');
	    $Transaction_status = 'pending';
	    $Transaction_type = 'indirect cash';
	    $Sponsor_Name = $Guarantor_Name;
	    if($_POST['bill_type']=='Cash'){
		$Billing_Type = 'Outpatient Cash';
	    }else{
		$Billing_Type = 'Outpatient Credit';
	    }
	    
	    $branch_id = $_SESSION['userinfo']['Branch_ID'];
	    
	    $insert_query = "INSERT INTO tbl_payment_cache(Registration_ID, Employee_ID, consultation_id, Payment_Date_And_Time,
	    Folio_Number, Sponsor_ID, Sponsor_Name, Billing_Type, Receipt_Date, Transaction_status, Transaction_type, branch_id)
	    VALUES ('$Registration_ID', '$Employee_ID', '$consultation_id', $Payment_Date_And_Time,
	    '$Folio_Number', '$Sponsor_ID', '$Sponsor_Name', '$Billing_Type', '$Receipt_Date',
	    '$Transaction_status', '$Transaction_type','$branch_id')";
	    
	    if(!mysqli_query($conn,$insert_query)){
	    die(mysqli_error($conn));exit;
	    $inserted = FALSE;
	    }
	    $payment_cache_ID = mysql_insert_id();
	}
	if($inserted){
	 //Inserting Item List
	$Sponsor_Name = $Guarantor_Name;
	$Check_In_Type = $Consultation_Type;
	$Item_ID = $_POST['Item_ID'];
	$bill_type = $_POST['bill_type'];
	
	if($bill_type=='Cash'){
	$Price = "(SELECT Selling_Price_Cash FROM tbl_items WHERE Item_ID = $Item_ID )";
	}else{
	    if(strtoupper($Sponsor_Name)=='NHIF'){
		$Price = "(SELECT Selling_Price_NHIF FROM tbl_items WHERE Item_ID = $Item_ID )";
	    }else{
		$Price = "(SELECT Selling_Price_Credit FROM tbl_items WHERE Item_ID = $Item_ID )";
	    }
	}
	$Sub_Department_ID = $_POST['Sub_Department_ID'];
	$Quantity = $_POST['quantity'];
	$Patient_Direction = "others";
	$Consultant = $_SESSION['userinfo']['Employee_Name'];
	$Consultant_ID = $_SESSION['userinfo']['Employee_ID'];
	$Status = 'active';
	$Transaction_Date_And_Time = '(SELECT NOW())';
	$Process_Status = 'inactive';
	$Doctor_Comment = $_POST['comments'];
	$Transaction_Type = $bill_type;
	$insert_query2 = "INSERT INTO tbl_item_list_cache(Check_In_Type, Item_ID,Discount, Price, Quantity, Patient_Direction, Consultant, Consultant_ID, Status,
			Payment_Cache_ID, Transaction_Date_And_Time, Process_Status, Doctor_Comment,Sub_Department_ID,Transaction_Type)
			VALUES ('$Check_In_Type', '$Item_ID', 0, $Price, '$Quantity', '$Patient_Direction', '$Consultant', '$Consultant_ID',
			'$Status','$payment_cache_ID', $Transaction_Date_And_Time,
			'$Process_Status', '$Doctor_Comment','$Sub_Department_ID','$Transaction_Type')";
	if(mysqli_query($conn,$insert_query2)){
		$url = "doctorspharmacy.php?Consultation_Type=$Consultation_Type&Patient_Payment_Item_List_ID=$Patient_Payment_Item_List_ID&consultation_id=$consultation_id&Registration_ID=$Registration_ID&payment_cache_ID=$payment_cache_ID&Patient_Payment_ID=$Patient_Payment_ID&SearchListDoctorsPagePatientBilling=SearchListDirectCashPatientBillingThisPage";
		?>
		<script type='text/javascript'>
		    document.location = '<?php echo $url;?>';
		</script>
	<?php
	    }else{
		}	   
	}else{
	    
	}
    }
?>
<fieldset>
    <center>
	<form method='post' action=''>
	<table width='100%'>
		    <tr>
			<td width='5%'>Category</td>
			<td width='15%'>
			    <select id='Item_Category_ID' name='Item_Category_ID' onchange='getSubcategory(this.value)' required='required' style='width: 100%'>
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
				    <option value="<?php echo $row['Item_Category_ID']?>">
					<?php echo $row['Item_Category_Name']?>
				    </option>
				    <?php
				}
				?>
			    </select>
			</td>
			<td width='50px'>Subcategory</td>
			<td width='275px'>
			    <select id='Item_Subcategory_ID' name='Item_Subcategory_ID' onchange='getItem(this.value)' onclick='getPrice()' required='required' style='width: 100%'>
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
				    <option value='<?php echo $row['Item_Subcategory_ID']?>'>
					<?php echo $row['Item_Subcategory_Name']?>
				    </option>
				    <?php
				}
				?>
			    </select>
			</td>
			<td style='text-align: right;'><input type='button' class='art-button-green' value='Choose Item' onclick='changeFineSearch()'></td>
			<td colspan=5>
			    <?php
				if(isset($_GET['Item_ID'])){
				    $Item_ID = $_GET['Item_ID'];
				    $itemQr = "SELECT Product_Name,Consultation_Type FROM tbl_Items WHERE Item_ID = $Item_ID";
				    $ItemResult = mysqli_query($conn,$itemQr);
				    
				    while($itemrow = mysqli_fetch_assoc($ItemResult)){
					$Product_Name = $itemrow['Product_Name'];
					$ItemConsultation = $itemrow['Consultation_Type'];
				    }
				}else
				    $Product_Name = '';
			    ?>
			    <input type='text' id='Product_Name' name='Product_Name' readonly='readonly' placeholder='Click Choose Items' value='<?php echo $Product_Name; ?>' >
			</td>
			<td style='text-align: right;width: 5%'>Bill Type</td>
			<td><select id='bill_type' name='bill_type' onclick='getPrice()' onchange='getPrice()' required='required' style='width: 100%'>
				<option></option>
				<option>Cash</option>
				<option>Credit</option>
			    </select>
			</td>
		    </tr>
		<tr>
		    <td style='text-align: right;'>Location</td>
		    <td>
			<select style='width: 140px;' name='Sub_Department_ID' id='Sub_Department_ID' required='required' onchange='getLocationQueSize()'>
			    <option></option>
			    <?php
			    $qr = "SELECT * FROM tbl_department d,tbl_sub_department s
			    where
			    d.Department_ID = s.Department_ID and
			    $Consultation_Condition and d.Department_Location='$ItemConsultation'";
			    $result = mysqli_query($conn,$qr);
			    while($row = mysqli_fetch_assoc($result)){
				?>
				<option value='<?php echo $row['Sub_Department_ID']; ?>'>
				    <?php echo $row['Sub_Department_Name'];?>
				</option>
				<?php
			    }
			    ?>
			</select>
		    </td>
		    <td style='text-align: right;'>Balance/Status</td><td><input type='text' id='balance' style='width: 60px;' name='balance' readonly='readonly' value='Available'></td>
		    <td style='text-align: right;'>Patient Queue</td><td><input type='text' id='queue' name='queue' style='width: 60px;' readonly='readonly'></td>
		    <td style='text-align: right;'>Quantity</td>
			<td><input type='text' id='quantity' name='quantity' onchange='getPrice()' onkeyup='getPrice()' style='width: 60px; text-align: right;' required='required'></td>
		    <td style='text-align: right;'>Price</td>
		    <td><input type='text' name='price' id='price' style='text-align: right;width: 140px;' readonly></td>
		    <td style='text-align: right;'>Amount</td>
		    <td><input type='text' name='ammount' id='ammount' style='text-align: right;width: 140px;' readonly></td>
		</tr>
		<tr>
		    <td style='width: 60px'><?php
		    if($Consultation_Type=='Pharmacy'){ echo 'Dosage';}else{echo 'Comments';}
		    ?>
		    </td>
		    <td style='width: 50%' colspan=6>
			<input style='width: 100%' type='text' id='comments' name='comments'>
		    </td>
		    <td style='text-align: right;' colspan=2>
			Date and Time
		    </td>
		    <td >
			<input style='width: 100%' type='text' id='dateTime' name='dateTime'>
		    </td>
		    <?php
			if(isset($_GET['Item_ID'])){
			    ?>
			    <td style='text-align: right'>
				<input type='hidden' id='submitted' name='submitted' value='<?php echo $payment_cache_ID; ?>'>
				<input type='submit' value='Add Item' class='art-button-green'>
			    </td>
			    <?php
			}else{
		    ?>
		    <td style='text-align: right' colspan=2>
			<input type='hidden' id='submitted' name='submitted' value='<?php echo $payment_cache_ID; ?>'>
			<input type='button' onclick="alert('Choose Item First')" value='Add Item' class='art-button-green'>
		    </td>
		    <?php
			}
		    ?>
		</tr>
	    </table>
	</form>
    </center>
</fieldset>
<fieldset>
    <iframe src='./doctorspharmacycache.php?consultation_id=<?php echo $consultation_id;?>&Consultation_Type=<?php echo $Consultation_Type;?>&Registration_ID=<?php echo $Registration_ID; ?>&Payment_Cache_ID=<?php echo $payment_cache_ID;?>&SearchListDoctorsPagePatientBilling=SearchListDirectCashPatientBillingThisPage' width='100%' height='300px'></iframe>
</fieldset>
<fieldset>
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
<center>
    <table>
	<tr>
	    <td>
	    Overall Cash
	    </td>
	    <td>
	    <input type='text' value='<?php echo number_format($sum_row['Cash']);?>' style='text-align: right;width: 100%' readonly>
	    </td>
	    <td width='5%'></td>
	    <td>
	    Overall Credit
	    </td>
	    
	    <td>
	    <input type='text' value='<?php echo number_format($sum_row['Credit']);?>' style='text-align: right;width: 100%' readonly>
	    </td>
	</tr>
    </table>
</center>
</fieldset>
<?php
    include("./includes/footer.php");
?>