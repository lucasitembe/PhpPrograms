<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    
    $Item_ID = 0;
    
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
	
	if($Consultation_Type=='Surgery'){
	    //$Consultation_Type = 'Theater';
	    $Consultation_Type = 'Surgery';
	}
	if($Consultation_Type=='Treatment'){
	    $Consultation_Type = 'Pharmacy';
	}
	
	if(strtolower($Consultation_Type)=='procedure'){
	    $Consultation_Condition = "((d.Department_Location='Dialysis') OR
	    (d.Department_Location='Physiotherapy') OR (d.Department_Location='Optical')OR
	    (d.Department_Location='Dressing')OR(d.Department_Location='Maternity')OR
	    (d.Department_Location='Cecap')OR(d.Department_Location='Dental')OR
	    (d.Department_Location='Ear') OR(d.Department_Location='Hiv') OR
	    (d.Department_Location='Eye') OR(d.Department_Location='Maternity') OR
	    (d.Department_Location='Rch') OR(d.Department_Location='Theater') OR
	    (d.Department_Location='Family Planning')OR(d.Department_Location='Surgery')
	    OR(d.Department_Location='Procedure'))";
	    
	    $Consultation_Condition2 = "((Consultation_Type='Dialysis') OR
	    (Consultation_Type='Physiotherapy') OR (Consultation_Type='Optical')OR
	    (Consultation_Type='Dressing')OR(Consultation_Type='Maternity')OR
	    (Consultation_Type='Cecap')OR(Consultation_Type='Dental')OR(Consultation_Type='Ear') OR
	    (Consultation_Type='Hiv') OR(Consultation_Type='Eye') OR(Consultation_Type='Maternity') OR
	    (Consultation_Type='Rch') OR(Consultation_Type='Theater') OR
	    (Consultation_Type='Family Planning'))";
	    
	    
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
	if(@mysqli_num_rows($Ppcid_result)>0){
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
	    die(mysqli_error($conn));
	    $inserted = FALSE;
	    }
	    $payment_cache_ID = mysqli_insert_id($conn);
	}
	if($inserted){
	 //Inserting Item List
	$Sponsor_Name = $Guarantor_Name;
	$Check_In_Type = $Consultation_Type;
	$Item_ID = $_GET['Item_ID'];
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
	$Service_Date_And_Time = $_POST['Service_Date_And_Time'];
	$Discount = $_POST['Discount'];
	
	
	$insert_query2 = "INSERT INTO tbl_item_list_cache(Check_In_Type, Item_ID,Discount, Price, Quantity, Patient_Direction, Consultant, Consultant_ID, Status,
			Payment_Cache_ID, Transaction_Date_And_Time, Process_Status, Doctor_Comment,Sub_Department_ID,Transaction_Type,Service_Date_And_Time)
			VALUES ('$Check_In_Type', '$Item_ID', $Discount, $Price, '$Quantity', '$Patient_Direction', '$Consultant', '$Consultant_ID',
			'$Status','$payment_cache_ID', $Transaction_Date_And_Time,
			'$Process_Status', '$Doctor_Comment','$Sub_Department_ID','$Transaction_Type','$Service_Date_And_Time')";
			
	if(mysqli_query($conn,$insert_query2)){
		$url = "doctoritemselect.php?Consultation_Type=$Consultation_Type&Patient_Payment_Item_List_ID=$Patient_Payment_Item_List_ID&consultation_id=$consultation_id&Registration_ID=$Registration_ID&payment_cache_ID=$payment_cache_ID&Patient_Payment_ID=$Patient_Payment_ID&SearchListDoctorsPagePatientBilling=SearchListDirectCashPatientBillingThisPage";
		?>
		<script type='text/javascript'>
		    document.location = '<?php echo $url;?>';
		</script>
	<?php
	    }else{
		die(mysqli_error($conn));
		}	   
	}else{
	    
	}
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
    
    <a href='clinicalnotes.php?Consultation_Type=<?php echo $Consultation_Type; ?>&<?php if($Registration_ID!=''){echo "Registration_ID=$Registration_ID&"; } ?><?php
    if(isset($_GET['Patient_Payment_ID'])){
	echo "Patient_Payment_ID=".$_GET['Patient_Payment_ID']."&";
	}
    if(isset($_GET['Patient_Payment_Item_List_ID'])){
	echo "Patient_Payment_Item_List_ID=".$_GET['Patient_Payment_Item_List_ID']."&";
	}
	
    if(isset($_GET['consultation_id'])){
	echo "consultation_id=".$_GET['consultation_id']."&";
	} ?>SearchListDoctorsPagePatientBilling=SearchListDirectCashPatientBillingThisPage' class='art-button-green'>
        CONSULTATION ITEM MAPPING
    </a>
    
    
    
    
    <a href='clinicalnotes.php?Consultation_Type=<?php echo $Consultation_Type; ?>&<?php if($Registration_ID!=''){echo "Registration_ID=$Registration_ID&"; } ?><?php
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
<br/>


<!-- new date function (Contain years, Months and days)--> 
<?php
    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
	$age ='';
    }
?>
<!-- end of the function -->


 

 



<!--Getting employee name -->
<?php
    if(isset($_SESSION['userinfo']['Employee_Name'])){
	$Employee_Name = $_SESSION['userinfo']['Employee_Name'];
    }else{
	$Employee_Name = 'Unknown Employee';
    }
?>




<?php
//    select patient information
    if(isset($_GET['Registration_ID'])){ 
        $Registration_ID = $_GET['Registration_ID']; 
        $select_Patient = mysqli_query($conn,"select
                            Old_Registration_Number,Title,Patient_Name,pr.Sponsor_ID,
                                Date_Of_Birth,
                                    Gender,pr.Region,pr.District,pr.Ward,
                                        Member_Number,Member_Card_Expire_Date,
                                            pr.Phone_Number,Email_Address,Occupation,
                                                Employee_Vote_Number,Emergence_Contact_Name,
                                                    Emergence_Contact_Number,Company,Registration_ID
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
                $Patient_Name = $row['Patient_Name'];
                $Sponsor_ID = $row['Sponsor_ID'];
                $Date_Of_Birth = $row['Date_Of_Birth'];
                $Gender = $row['Gender'];
                $Region = $row['Region'];
                $District = $row['District'];
                $Ward = $row['Ward'];
                $Guarantor_Name = $row['Guarantor_Name'];
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
	   // if($age == 0){
		
		$date1 = new DateTime($Today);
		$date2 = new DateTime($Date_Of_Birth);
		$diff = $date1 -> diff($date2);
		$age = $diff->y." Years, ";
		$age .= $diff->m." Months, ";
		$age .= $diff->d." Days";
		
	    /*}
	    if($age == 0){
		$date1 = new DateTime($Today);
		$date2 = new DateTime($Date_Of_Birth);
		$diff = $date1 -> diff($date2);
		$age = $diff->d." Days";
	    }*/
	  
	    
	    
	    
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
        }
?>


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
    <!--Global Item_ID-->
    <script>
	var Item_ID;
    </script>


<!--filtering services against categories-->
<script type="text/javascript" language="javascript">
    function getItemList(Item_Category_Name) {
	    if(window.XMLHttpRequest) {
		mm = new XMLHttpRequest();
	    }
	    else if(window.ActiveXObject){ 
		mm = new ActiveXObject('Micrsoft.XMLHTTP');
		mm.overrideMimeType('text/xml');
	    }
	    //getPrice();
	    var ItemListType = document.getElementById('Type').value;
	    getItemListType(ItemListType);
	    document.getElementById('Price').value = '';
	    mm.onreadystatechange= AJAXP; //specify name of function that will handle server response....
	    mm.open('GET','GetItemList.php?Item_Category_Name='+Item_Category_Name,true);
	    mm.send();
	}
    function AJAXP() {
	var data1 = mm.responseText; 
	document.getElementById('Item_Name').innerHTML = data1;	
    }
</script>

<script type='text/javascript'>
    function getItemListType(){
	var Item_Category_Name = document.getElementById("Item_Category").value;
	
	
	if(window.XMLHttpRequest) {
	    myObject = new XMLHttpRequest();
	}else if(window.ActiveXObject){ 
	    myObject = new ActiveXObject('Micrsoft.XMLHTTP');
	    myObject.overrideMimeType('text/xml');
	}
	
	//alert(Item_Category_Name);
	//document.location = 'Approval_Bill.php?Registration_ID='+Registration_ID+'&Insurance='+Insurance+'&Folio_Number='+Folio_Number;
	
	myObject.onreadystatechange = function (){
					    data = myObject.responseText;
					    if (myObject.readyState == 4) {
						document.getElementById('Approval').disabled = 'disabled';
						document.getElementById('Approval_Comment').innerHTML = data;
					    }
					}; //specify name of function that will handle server response........
	myObject.open('GET','Approval_Bill.php?Item_Category_Name='+Item_Category_Name,true);
	myObject.send();
    }
</script>
<!-- end of filtering-->




<!-- clinic and doctor selection-->
<script type="text/javascript" language="javascript">
    function getDoctor() {
	var Type_Of_Check_In = document.getElementById('Type_Of_Check_In').value;
	    if(window.XMLHttpRequest) {
		mm = new XMLHttpRequest();
	    }
	    else if(window.ActiveXObject){ 
		mm = new ActiveXObject('Micrsoft.XMLHTTP');
		mm.overrideMimeType('text/xml');
	    }
	    
	    if (document.getElementById('direction').value =='Direct To Doctor Via Nurse Station' || document.getElementById('direction').value =='Direct To Doctor') {
		mm.onreadystatechange= AJAXP3; //specify name of function that will handle server response....
		mm.open('GET','Get_Guarantor_Name.php?Type_Of_Check_In='+Type_Of_Check_In+'&direction=doctor',true);
		mm.send();
	    }
	    else{
		mm.onreadystatechange= AJAXP3; //specify name of function that will handle server response....
		mm.open('GET','Get_Guarantor_Name.php?direction=clinic',true);
		mm.send();
	    }
	}
    function AJAXP3(){
	var data3 = mm.responseText;
	document.getElementById('Consultant').innerHTML = data3;
    }
</script>


<!--##########################################################################################
Scripts from the doctorsitem selection
-->



<script type="text/javascript" language="javascript">
    
	    function getLocationQueSize() {
		var Sub_Department_ID = document.getElementById('Sub_Department_ID').value;
		var Item_ID = parent.Item_ID;
		    if(window.XMLHttpRequest) {
			mm5 = new XMLHttpRequest();
		    }
		    else if(window.ActiveXObject){ 
			mm5 = new ActiveXObject('Micrsoft.XMLHTTP');
			mm5.overrideMimeType('text/xml');
		    }
		    mm5.onreadystatechange= AJAXP5; //specify name of function that will handle server response....
		    mm5.open('GET','getLocationQueSize.php?Sub_Department_ID='+Sub_Department_ID+'&Item_ID='+Item_ID,true);
		    mm5.send();
		}
	    function AJAXP5() {
		var data5 = mm5.responseText; 
		document.getElementById('QueuSize').value = data5;
	    }
	    
	    //function to get the Item Queu Size
	    function getItemQueuSize() {
		var Sub_Department_ID = document.getElementById('Sub_Department_ID').value;
		var Item_ID = parent.Item_ID;
		    if(window.XMLHttpRequest) {
			mm5 = new XMLHttpRequest();
		    }
		    else if(window.ActiveXObject){ 
			mm5 = new ActiveXObject('Micrsoft.XMLHTTP');
			mm5.overrideMimeType('text/xml');
		    }
		    mm5.onreadystatechange= AJAXPQ; //specify name of function that will handle server response....
		    mm5.open('GET','getItemQueuSize.php?Item_ID='+Item_ID,true);
		    mm5.send();
		}
	    function AJAXPQ() {
		var data5 = mm5.responseText; 
		document.getElementById('QueuSize').value = data5;
	    }

        //function to get the Item Balance
        function getItemBalance() {
            var Item_ID = parent.Item_ID;
            if(window.XMLHttpRequest) {
                mm5 = new XMLHttpRequest();
            }
            else if(window.ActiveXObject){
                mm5 = new ActiveXObject('Micrsoft.XMLHTTP');
                mm5.overrideMimeType('text/xml');
            }
            mm5.onreadystatechange= AJAXPB; //specify name of function that will handle server response....
            mm5.open('GET','getItemBalance.php?Item_ID='+Item_ID,true);
            mm5.send();
        }
        function AJAXPB() {
            var data5 = mm5.responseText;
            document.getElementById('QueuSize').value = data5;
        }








	    
	    
	</script>
	
	
	<script type='text/javascript'>
	    
	    function check_discount() {
		    var Product_Name = Item_ID;
		    var bill_type = document.getElementById('bill_type').value;
		    var Billing_Type;
		    if (Product_Name =='' || Product_Name == null) {
			alert("You must select an item to get it's price before discount.");
			document.getElementById("Discount").value=0;
			document.getElementById("Discount").focus();
			return false;
		    }
		    if (bill_type == 'Credit') {
			    alert("You cannot make discounts for credit patients.")
			    document.getElementById("Discount").value=0;
			    document.getElementById("Discount").focus();
			    return true;
		    }else{
			Calculate_Amount();
		    }
		}
	    
	    
	    
	    
	    function getPrice() {
		var Product_Name = Item_ID;
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
		if (mm2.readyState == 4) {
		    var data4 = mm2.responseText;
		    document.getElementById('Price').value = data4;
		    Calculate_Amount();
		}
	    }
	    
	    
	    
	    
	    
	    //function to calculate the amount
	    function Calculate_Amount(){
		var price = document.getElementById('Price').value;
		var Discount = document.getElementById("Discount").value;
		
		price=price.replace(',','')
		var quantity = <?php if( ($Consultation_Type != 'Laboratory') && ($Consultation_Type != 'Radiology') && ($Consultation_Type != 'Surgery') && ($Consultation_Type != 'Procedure') ){ ?>parseInt(document.getElementById('Quantity').value) <?php }else{ ?>1<?php } ?>;
		if (isNaN(price)) {
		    price=0;
		}
		if (isNaN(quantity)) {
		    quantity =0;
		}
		quantity == parseInt(quantity);
		var ammount = 0;
		
		ammount = price * quantity;
		
		if (isNaN(ammount)) {
		    ammount=0;
		}
		
		if (isNaN(Discount)) {
		    Discount=0;
		}
		
		var Discount = document.getElementById("Discount").value;
		var NetAmount= ammount - Discount;
		
		
		document.getElementById('Amount').value = NetAmount;
		
		//change the border color
		<?php if(($Consultation_Type != 'Laboratory') && ($Consultation_Type != 'Radiology') && ($Consultation_Type != 'Surgery') && ($Consultation_Type != 'Procedure')){ ?>
		document.getElementById("Quantity").style.borderColor = "";
		<?php } ?>
		//alert(ammount)
	    }
	    
	    //the function to select the items
	    function searchItemList() {
		Item_Category_ID = document.getElementById('Item_Category_ID').value;
		Item_Subcategory_ID = document.getElementById('Item_Subcategory_ID').value;
		test_name = document.getElementById('test_name').value;
		document.getElementById('doctordetaileddiagnosisselect_Iframe').src ='./doctordetaileddiagnosisselect_Iframe.php?disease_name='+disease_name+'&disease_category_ID='+disease_category_ID+'&subcategory_ID='+subcategory_ID+'&consultation_id=<?php echo $consultation_id;?>&Consultation_Type=<?php echo $Consultation_Type;?>&Registration_ID=<?php echo $Registration_ID; ?>&Payment_Cache_ID=<?php echo $payment_cache_ID;?>&SearchListDoctorsPagePatientBilling=SearchListDirectCashPatientBillingThisPage';
	    }
	    
	    
	</script>





<script type='text/javascript'>
	    function changeFineSearch(){
		var Item_Subcategory_ID = document.getElementById('Item_Subcategory_ID').value;
		var Item_Category_ID = document.getElementById('Item_Category_ID').value;
		
		document.location = "doctordetaileditemselect.php?Consultation_Type=<?php
				    echo $Consultation_Type;
				    ?>&consultation_id=<?php
				    echo $consultation_id;
				    ?>&Registration_ID=<?php
				    echo $Registration_ID;
				    ?>&Patient_Payment_Item_List_ID=<?php
				    echo $Patient_Payment_Item_List_ID;
				    ?>&Patient_Payment_ID=<?php
				    echo $Patient_Payment_ID;
				    ?>&Item_Category_ID="+Item_Category_ID+"&Item_Subcategory_ID="+Item_Subcategory_ID+"&SearchListDoctorsPagePatientBilling=SearchListDirectCashPatientBillingThisPage";
		
	    }
	</script>

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
	    
	    //function to search items
	    function searchItem() {
		    Item_Category_ID = document.getElementById('Item_Category_ID').value;
		    Item_Subcategory_ID = document.getElementById('Item_Subcategory_ID').value;
		    test_name = document.getElementById('test_name').value;
		    
		    document.getElementById('frmaesearch').src = " ./doctordetaileditemselect_Iframe.php?test_name="+test_name+"&Item_Subcategory_ID="+Item_Subcategory_ID+"&Item_Category_ID="+Item_Category_ID+"&Consultation_Type=<?php
		    echo $Consultation_Type;?>&Patient_Payment_ID=<?php
		    echo $Patient_Payment_ID;
		    ?>&consultation_id=<?php
		    echo $consultation_id;?>&Consultation_Type=<?php
		    echo $Consultation_Type;?>&Patient_Payment_Item_List_ID=<?php
		    echo $Patient_Payment_Item_List_ID;?>&Patient_Payment_ID=<?php
		    echo $Patient_Payment_ID;?>&Registration_ID=<?php
		    echo $Registration_ID; ?>&Payment_Cache_ID=<?php
		    echo $payment_cache_ID;?>&SearchListDoctorsPagePatientBilling=SearchListDirectCashPatientBillingThisPage";
    }
	 
	</script>

<!--##########################################################################################-->




<!-- end of selection-->



<!-- get employee id-->
<?php
    if(isset($_SESSION['userinfo']['Employee_ID'])){
	$Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    }
	
	//echo $Consultation_Type;
?>



<!-- get id, date, Billing Type,Folio number and type of chech in -->
<!-- id will be used as receipt number( Unique from the parent payment table -->



<script language="javascript" type="text/javascript">
    function searchPatient(Patient_Name){
        document.getElementById('Search_Iframe').innerHTML = "<iframe width='100%' height=100% src='viewpatientsIframe.php?Patient_Name="+Patient_Name+"'></iframe>";
    }
</script>
<form action='' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">
<!--<br/>-->

<fieldset>
    <center>
	<form method='post' action=''>
	 <table style='width: 100%;'>
	    <td style='width: 50%'>
		<table>
		    <tr>
			<td style="text-align: right;">Bill Type</td>
			<td>
			    <select id='bill_type' name='bill_type' onchange='getPrice()' required='required' style='width: 500px;text-align: left;'>
					
					<?php
					if(strtolower($Guarantor_Name) == "cash"){
					echo "<option selected='selected'>Cash</option>
					      <option>Credit</option>
					";
					}else{
					  echo "<option selected='selected'>Credit</option>
					        <option>Cash</option>
					  ";
					}
					?>
					
				    </select>
			</td>
		    </tr>
		    
		    <tr>
			<td style="text-align: right;">Category </td>
			<td>
			    <select id='Item_Category_ID' name='Item_Category_ID' onchange='getSubcategory(this.value)' required='required' style='width: 100%'>
				    
					<?php
					  $qr ='';
					  
					if($Consultation_Type=='Pharmacy'){
					    $qr = "SELECT * FROM tbl_item_category as ic
					    join tbl_item_subcategory as iss on iss.Item_Category_ID = ic.Item_Category_ID
					    join tbl_items  as i on iss.Item_Subcategory_ID = i.Item_Subcategory_ID
					    WHERE i.Item_Type='Pharmacy' group by ic.Item_Category_ID";
					    
					}
					if($Consultation_Type=='Laboratory'){
					    $qr = "SELECT * FROM tbl_item_category as ic
					    join tbl_item_subcategory as iss on iss.Item_Category_ID = ic.Item_Category_ID
					    join tbl_items  as i on iss.Item_Subcategory_ID = i.Item_Subcategory_ID
					    WHERE i.Consultation_Type='Laboratory' group by ic.Item_Category_ID";
					    
					}
					if($Consultation_Type=='Surgery'){
					    $qr = "SELECT * FROM tbl_item_category as ic
					    join tbl_item_subcategory as iss on iss.Item_Category_ID = ic.Item_Category_ID
					    join tbl_items  as i on iss.Item_Subcategory_ID = i.Item_Subcategory_ID
					    WHERE i.Consultation_Type='Surgery' group by ic.Item_Category_ID";
					    
					}
					if($Consultation_Type=='Procedure'){
					    $qr = "SELECT * FROM tbl_item_category as ic
					    join tbl_item_subcategory as iss on iss.Item_Category_ID = ic.Item_Category_ID
					    join tbl_items  as i on iss.Item_Subcategory_ID = i.Item_Subcategory_ID
					    WHERE i.Consultation_Type='Procedure' group by ic.Item_Category_ID";
					    
					}
					else{
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
					    <?php
						if(strtolower($row['Item_Category_Name']) == 'laboratory'){?>
						    <option value="<?php echo $row['Item_Category_ID']?>" selected='selected'>
							<?php echo $row['Item_Category_Name']?>
						    </option>
						<?php }
						if(strtolower($row['Item_Category_Name']) == 'imaging'){ ?>
						    <option value="<?php echo $row['Item_Category_ID']?>" selected='selected'>
							<?php echo $row['Item_Category_Name']?>
						    </option>
						<?php }if(strtolower($row['Item_Category_Name']) == 'MINOR SURGERY SERVICES'){ ?>
						    <option value="<?php echo $row['Item_Category_ID']?>" selected='selected'>
							<?php echo $row['Item_Category_Name']?>
						    </option>
						<?php }if(strtolower($row['Item_Category_Name']) == 'procedures'){ ?>
						    <option value="<?php echo $row['Item_Category_ID']?>" selected='selected'>
							<?php echo $row['Item_Category_Name']?>
						    </option>
						    <?php }else{?>
							<option value="<?php echo $row['Item_Category_ID']?>" selected='selected'>
							<?php echo $row['Item_Category_Name']?>
						    </option>
							<?php } ?>
					<?php }
					?>
			        </select>
			</td>
		    </tr>
		    <tr>
			<td style="text-align: right;">Subcategory</td>
			<td>
			    <select id='Item_Subcategory_ID' name='Item_Subcategory_ID' onchange='searchItem(this.value)' onclick='getPrice()' required='required' style='width: 100%'>
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
		    </tr>
		    <tr>
			<td style='text-align: right'>Item Name</td>
			<td>
			    <input type='text' oninput='searchItem()'  placeholder='-----Search an item-----' id='test_name' name='test_name'>
			</td>
		    </tr>
		</table>
	    </td>
	    <td>
		<table>
			<tr>
			<td style="text-align:right;width:20%;">Service Date</td>
			<td style=''>
				    <input type='text' id='date' name='Service_Date_And_Time' value="<?php echo date('Y-m-d')?>">
					
				</td>
		    </tr>
		    <tr>
			<td style="text-align:right;"><?php
				if($Consultation_Type=='Pharmacy'){ echo 'Dosage';}else{echo 'Comments';}
			     ?>
			</td>
			<td>
			    <textarea id='comments' name='comments' required='required' rows='2' cols='100' ></textarea>
			</td>
			<?php
			     if(strtolower($Consultation_Type)=='laboratory'){?>
				<td>PRIORITY</td>
				<td>
				    <select name="Priority" id="Priority" required="required">
					<option>Normal</option>
					<option>Urgent</option>
					<option>Low</option>
				    </select>
				</td>
			     <?php }
			?>
		    </tr>
		</table>
	    </td>
	 </table>
	</form>
    </center>
        <center>
            <table width=100%>
		<tr>
		    <td width=35%>
			
			<script type='text/javascript'>
			    function getItemsList(Item_Category_ID){
				document.getElementById("Search_Value").value = '';
				document.getElementById("Price").value = '';
				document.getElementById("Item_Name").value = '';
				document.getElementById("Quantity").value = '';
				if(window.XMLHttpRequest) {
				    myObject = new XMLHttpRequest();
				}else if(window.ActiveXObject){ 
				    myObject = new ActiveXObject('Micrsoft.XMLHTTP');
				    myObject.overrideMimeType('text/xml');
				}
				//alert(data);
			    
				myObject.onreadystatechange = function (){
					    data = myObject.responseText;
					    if (myObject.readyState == 4) {
						//document.getElementById('Approval').disabled = 'disabled';
						document.getElementById('Items_Fieldset').innerHTML = data;
					    }
					}; //specify name of function that will handle server response........
				myObject.open('GET','Get_List_Of_Items.php?Item_Category_ID='+Item_Category_ID,true);
				myObject.send();
			    }
			    
			    function getItemsListFiltered(Item_Name){
				document.getElementById("Price").value = '';
				document.getElementById("Item_Name").value = '';
				document.getElementById("Quantity").value = '';
				var Item_Category_ID = document.getElementById("Item_Category_ID").value;
				if (Item_Category_ID == '' || Item_Category_ID == null) {
				    Item_Category_ID = 'All';
				}
				
				if(window.XMLHttpRequest) {
				    myObject = new XMLHttpRequest();
				}else if(window.ActiveXObject){ 
				    myObject = new ActiveXObject('Micrsoft.XMLHTTP');
				    myObject.overrideMimeType('text/xml');
				}
				//alert(data);
			    
				myObject.onreadystatechange = function (){
					    data = myObject.responseText;
					    if (myObject.readyState == 4) {
						//document.getElementById('Approval').disabled = 'disabled';
						document.getElementById('Items_Fieldset').innerHTML = data;
					    }
					}; //specify name of function that will handle server response........
				myObject.open('GET','Get_List_Of_Items_Filtered.php?Item_Category_ID='+Item_Category_ID+'&Item_Name='+Item_Name,true);
				myObject.send();
			    }
			    
			    
			</script>
			<!--HHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHH-->
			<!--This script adds item to tbl_item_list_cache -->
			
			<script type='text/javascript'>
			    function sendOrRemoveItem() {
				
				var Item_Name=document.getElementById("Item_Name").value;
				
				if (Item_Name == '' || Item_Name == null) {
				    alert("Please,you must select an item.");
				    document.getElementById("Item_Name").focus();
				    document.getElementById("Item_Name").style.borderColor='red';
				    return false;
				}else{
					var bill_type = document.getElementById("bill_type").value;
					var action="ADD";
					var Registration_ID = "<?php echo $_GET['Registration_ID']; ?>";
					var Patient_Payment_ID = "<?php echo $_GET['Patient_Payment_ID']; ?>";
					var Sub_Department_ID = document.getElementById("Sub_Department_ID").value;




					var quantity = <?php  if($Consultation_Type == 'Laboratory' || $Consultation_Type == 'Radiology' || $Consultation_Type == 'Surgery' || $Consultation_Type == 'Procedure'){ echo '1;';}else{ ?> document.getElementById('Quantity').value; <?php } ?>

					if (quantity == null || quantity == '') {
					    alert("Please,specify the item quantity.");
					    document.getElementById("Quantity").focus();
					    <?php if((strtolower($Consultation_Type) == 'laboratory') && (strtolower($Consultation_Type) == 'radiology') && (strtolower($Consultation_Type) == 'surgery') && (strtolower($Consultation_Type) == 'procedure') ){ ?>document.getElementById("Quantity").style.borderColor = "red"; <?php }else{?> document.getElementById("Quantity").style.borderColor = "black"; <?php }  ?>
					    return false;
					}
				
				
				    var comments = document.getElementById('comments').value;
				    var Priority=<?php if((strtolower($Consultation_Type) == 'laboratory') ){ ?>document.getElementById("Priority").value; <?php }else{ echo '0;'?>; <?php }  ?>
				    //var Priority = document.getElementById('Priority').value;
				    var Consultation_Type = "<?php echo $_GET['Consultation_Type']?>";
				    
				    if (Consultation_Type.toLowerCase() == 'pharmacy') {
					if (comments == '' || comments == null) {
					    alert("You must specify the dosage for this item.");
					    document.getElementById("comments").focus();
					    document.getElementById("comments").style.borderColor='red';
					    return false;
					}
				    }
				    
				    
				    var Service_Date_And_Time = document.getElementById('date').value;
				    var Discount = document.getElementById('Discount').value;
				    
				    if(Item_ID!='') {
					var Guarantor_Name = '<?php echo $Guarantor_Name; ?>';
					if(window.XMLHttpRequest) {
					    myObject = new XMLHttpRequest();
					}
					else if(window.ActiveXObject){ 
					    myObject = new ActiveXObject('Micrsoft.XMLHTTP');
					    myObject.overrideMimeType('text/xml');
					}
					if (bill_type!='' && Sub_Department_ID!='') {
					    myObject.onreadystatechange= sendOrRemove_AJAX; //specify name of function that will handle server response....
					    myObject.open('GET','sendOrRemove.php?action='+action+'&quantity='+quantity+'&Patient_Payment_ID='+Patient_Payment_ID+'&Consultation_Type=<?php echo $_GET['Consultation_Type'];?>&Sub_Department_ID='+Sub_Department_ID+'&consultation_id=<?php echo $consultation_id; ?>&Sponsor_ID=<?php echo $Sponsor_ID; ?>&Item_ID='+Item_ID+'&bill_type='+bill_type+'&Guarantor_Name='+Guarantor_Name+'&Registration_ID='+Registration_ID+'&comments='+comments+'&Discount='+Discount+'&Priority='+Priority+'&Service_Date_And_Time='+Service_Date_And_Time,true);
					    myObject.send();
					    document.getElementById("Item_Name").style.borderColor='';
					    document.getElementById("comments").style.borderColor='';
				      
					    }else{
						var message = "choose";
						if (bill_type=='') {
						 check_ID.checked = false;
						 message = message+' bill type';   
						}
						if (Sub_Department_ID=='') {
						 check_ID.checked = false;
						 message=message+' ,location';
						}
						message=message+" first";
						alert(message);
					    } 
				    }
				
				}
			    }
			    function sendOrRemove_AJAX(){
				document.getElementById('doctoritemcache').src="./doctoritemcache.php?consultation_id=<?php echo $consultation_id;?>&Consultation_Type=<?php echo $Consultation_Type;?>&Registration_ID=<?php echo $Registration_ID; ?>&Payment_Cache_ID=<?php echo $payment_cache_ID;?>&Guarantor_Name=<?php echo $Guarantor_Name?>&SearchListDoctorsPagePatientBilling=SearchListDirectCashPatientBillingThisPage";
				document.location.reload(true);
			    }
			    
			    
			//function to get item balance
				function Get_Item_Balance(){
					Sub_Department_ID = document.getElementById('Sub_Department_ID').value;
					if(window.XMLHttpRequest) {
						myObject = new XMLHttpRequest();
					}else if(window.ActiveXObject){
						myObject = new ActiveXObject('Micrsoft.XMLHTTP');
						myObject.overrideMimeType('text/xml');
					}
					myObject.onreadystatechange = function (){
						data = myObject.responseText;

						if (myObject.readyState == 4) {
							<?php if($Consultation_Type == 'Laboratory' || $Consultation_Type == 'Radiology' || $Consultation_Type == 'Surgery' || $Consultation_Type == 'Procedure'){ ?> <?php }else{?> document.getElementById('Balance').value = data; <?php }?>
						}
					}; //specify name of function that will handle server response........

					myObject.open('GET','Get_Items_Balance.php?Item_ID='+Item_ID+'&Sub_Department_ID='+Sub_Department_ID,true);//TO DO Change Get_Items_Price to Get_Item_Price
					myObject.send();
				}
				
				function Get_Item_Status(){
					if(window.XMLHttpRequest) {
						myObject = new XMLHttpRequest();
					}else if(window.ActiveXObject){ 
						myObject = new ActiveXObject('Micrsoft.XMLHTTP');
						myObject.overrideMimeType('text/xml');
					}
					myObject.onreadystatechange = function (){
						data = myObject.responseText;
						
						if (myObject.readyState == 4) { 
						parent.document.getElementById('Status').value = data;
						parent.getItemQueuSize();
						//alert(data);
						}
					}; //specify name of function that will handle server response........
					
					myObject.open('GET','Get_Items_status.php?Item_ID='+Item_ID,true);
					myObject.send();
				}
			</script>
			
			
			
			<!--HHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHH-->
				<table width = 100%>
				    <tr>
					<td>
					    <fieldset>
						    <table width="100%">
							 <tr>
							     <td>
								  <iframe name='itemIframe' src="./doctordetaileditemselect_Iframe.php?Item_Subcategory_ID=All&Item_Category_ID=All&Consultation_Type=<?php echo $Consultation_Type;?>&Patient_Payment_ID=<?php echo $Patient_Payment_ID;
								     ?>&consultation_id=<?php echo $consultation_id;?>&Patient_Payment_Item_List_ID=<?php echo $Patient_Payment_Item_List_ID;?>&Patient_Payment_ID=<?php echo $Patient_Payment_ID;?>&Registration_ID=<?php echo $Registration_ID; ?>&Payment_Cache_ID=<?php echo $payment_cache_ID;?>&Guarantor_Name=<?php echo $Guarantor_Name?>&provisional_diagnosis=<?php echo $_GET['provisional_diagnosis'];?>&SearchListDoctorsPagePatientBilling=SearchListDirectCashPatientBillingThisPage" width='100%' height='320px' id='frmaesearch'></iframe>
							     </td>
							 </tr>
						     </table>
					    </fieldset>	
					</td>
				    </tr>
				</table> 
		    </td>
		    <td>
			<table width=100%>
			    <tr>
				<td style='text-align: center;' colspan=2>
				<!-- ITEM DESCRIPTION START HERE -->
				    <center>
					<table width=100%>
					    <tr>
						<td><b>Item Name</b></td>
						<!--<td>Discount</td>-->
						<td><b>Price</b></td>
						<td style="width: 80px;"><b>
						    <?php
							if(strtolower($Consultation_Type) == "pharmacy"){
							    echo "Balance";
							}else{
							    echo "Status";
							}
						    ?>    
						</b></td>
						<?php
						    if(strtolower($Consultation_Type) == "pharmacy"){ ?>
							    <td><b>Quantity</b></td>
							<?php }
						?>
						<td style="width: 100px;"><b>Location</b></td>
						<?php
						    if(strtolower($Consultation_Type) != "pharmacy"){ ?>
							<td><b>Queue</b></td>
						    <?php }
						?>
						<td style="width: 75px;"><b>Amount</b></td>
						
					    </tr>
					    <form action='' method='POST'>
						<tr>  
						<td width=35%> 
						    <input type='text' name='Item_Name' id='Item_Name' size=20 placeholder='Item Name' readonly='readonly' required='required'>
						    <input type='hidden' name='Item_ID' id='Item_ID' value=''>
						    <input type='hidden' name='Discount' id='Discount' placeholder='Discount' onkeyup='check_discount()' value="0">
						</td> 
						<!--<td>-->
						<!--    <!--Column for discount if needed-->
						<!--</td>-->
						<td>
						    <input type='text' name='Price' id='Price' placeholder='Price' readonly='readonly'>
						</td>
						<td>
						    <?php
							if(strtolower($Consultation_Type) == "pharmacy"){ ?>
							    <input type='text' name='Balance' id='Balance' placeholder='Balance' readonly="readonly">
							<?php }else{?>
							    <input type='text' name='Status' id='Status' placeholder='Status'  readonly='readonly'>
							<?php } ?>
						</td>
						<?php
						    if(strtolower($Consultation_Type) == "pharmacy"){?>
							<td>
							    <input type='text' name='Quantity' id='Quantity' required='required' placeholder='Quantity' value='1' onchange='Calculate_Amount()' onkeyup='Calculate_Amount()' required='required'>
							</td>
						    <?php }
						?>
						<td>
						    <select style='width: 100%;' name='Sub_Department_ID' id='Sub_Department_ID' required='required' onchange='getLocationQueSize();<?php if($Consultation_Type == 'Laboratory' && $Consultation_Type=='Radiology'){?>Get_Item_Status();<?php }else{ ?>Get_Item_Balance();<?php } ?>'>
								<!--<option>Select</option>-->
								<?php
								$qr = "SELECT * FROM tbl_department d,tbl_sub_department s
								where
								d.Department_ID = s.Department_ID and
								$Consultation_Condition ";
								$result = mysqli_query($conn,$qr);
								while($row = mysqli_fetch_assoc($result)){
								    $Sub_Department_Name=$row['Sub_Department_Name'];
								    ?>
								    <?php
								      if($Sub_Department_Name == 'Laboratory' ){ ?>
									<option value='<?php echo $row['Sub_Department_ID']; ?>'>
									    <?php echo $row['Sub_Department_Name'];?>
									</option>
								      <?php }
								      else{ ?>
									<option value='<?php echo $row['Sub_Department_ID']; ?>' selected='selected'>
									    <?php echo $row['Sub_Department_Name'];?>
									</option>
								      <?php }
								    ?>
								    <?php
								}
								?>
							    </select>
							    
						    
						</td>
						<?php
						if(strtolower($Consultation_Type) != "pharmacy"){ ?>
						    <td>
						    <input type='text' name='Price' id='QueuSize' placeholder='Queue' readonly='readonly'>
						</td>
						<?php }
						?>
						<td>
						    <input type='text' name='Amount' id='Amount' placeholder='Amount' readonly='readonly'>
						</td>
						<td style='text-align: center;'>
						<input type="button" name='submint' id="add" value='ADD' class='art-button-green' style='width: 8px;' onclick="sendOrRemoveItem()"/>
					
						</td>
					    </tr>
					    </form>
            </table>   
        </center>
    <!-- ITEM DESCRIPTION ENDS HERE -->
 				</td>
			    </tr>
				<tr>
				    <td colspan=2>
					<iframe src='./doctoritemcache.php?consultation_id=<?php echo $consultation_id;?>&Consultation_Type=<?php echo $Consultation_Type;?>&Registration_ID=<?php echo $Registration_ID; ?>&Payment_Cache_ID=<?php echo $payment_cache_ID;?>&Guarantor_Name=<?php echo $Guarantor_Name?>&SearchListDoctorsPagePatientBilling=SearchListDirectCashPatientBillingThisPage' width='100%' height='250px' id='doctoritemcache'></iframe>		    				    
				    </td>
				</tr>
				<tr>
				    <td style='text-align: right'>
					<?php
					    if(isset($_SESSION['userinfo'])){
						if($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'] == 'yes'){
					?>
					    <a href='clinicalnotes.php?Consultation_Type=<?php echo $Consultation_Type; ?>&<?php if($Registration_ID!=''){echo "Registration_ID=$Registration_ID&"; } ?><?php
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
					<?php  } } ?>
									    </td>
				</tr>
			</table>
			
		    </td>
		</tr> 
	    </table>
        </center>
</fieldset>
</form>
<?php
    include("./includes/footer.php");
?>