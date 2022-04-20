<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Cash_Transactions'])){
	    /*if($_SESSION['userinfo']['Reception_Works'] != 'yes'){
		header("Location: ./index.php?InvalidPrivilege=yes");
	    } else{
		@session_start();
		if(!isset($_SESSION['supervisor'])){
                
                    //get patient registration id for future use
                    if(isset($_GET['Registration_ID'])){
                        $Registration_ID = $_GET['Registration_ID'];
                    }else{
                        $Registration_ID = '';
                    }
		    header("Location: ./receptionsupervisorauthentication.php?Registration_ID=$Registration_ID&InvalidSupervisorAuthentication=yes");
		}
	    }*/ 
	}else{
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	    header("Location: ../index.php?InvalidPrivilege=yes");
    }
?>
<?php
if(isset($_SESSION['userinfo'])){
    if(isset($_GET['Registration_ID'])){
        $Registration_ID = $_GET['Registration_ID'];
        $Patient_Payment_ID = $_GET['Patient_Payment_ID'];
	$section= $_GET['section'];
	}else{
	$Registration_ID = '';
        $Patient_Payment_ID = '';
	$section= '';
	}
?>
    <a href='departmentworkspage.php?section=<?php echo $section;?>&Registration_ID=<?php echo $Registration_ID; ?>&Patient_Payment_ID=<?php echo $Patient_Payment_ID; ?>&SearchListDirectCashPatientBilling=SearchListDirectCashPatientBillingThisPage' class='art-button-green'>
        WORKPAGE
    </a>
    <?php
    }
    else{
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
?>




<?php /*
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Reception_Works'] == 'yes'){ 
?>
    <a href='searchlistofoutpatientbilling.php?SearchListOfOutpatientBilling=SearchListOfOutpatientBillingThisPage' class='art-button-green'>
        OUTPATIENT
    </a>
<?php  } } */ ?>

<?php /*
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Revenue_Center_Works'] == 'yes'){ 
?>
    <a href='#?SearchListPatientBilling=SearchListPatientBillingThisPage' class='art-button-green'>
        INPATIENT
    </a>
<?php  } } */ ?>

<?php /*
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Revenue_Center_Works'] == 'yes'){ 
?>
    <a href='DirectCashsearchlistofoutpatientbilling.php?SearchListDirectCashPatientBilling=SearchListDirectCashPatientBillingThisPage' class='art-button-green'>
        DIRECT CASH
    </a>
<?php  } } */ ?>

<?php /*
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Revenue_Center_Works'] == 'yes'){ 
?>
    <a href='continueoutpatientbilling.php?ContinuePatientBilling=ContinuePatientBillingThisPage' class='art-button-green'>
        CONTINUOUS
    </a>
<?php  } } */ ?>

<?php /*
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Revenue_Center_Works'] == 'yes'){ 
?>
    <a href='patientbilling.php?PatientBilling=PatientBillingThisPage' class='art-button-green'>
        CLEAR
    </a>
<?php  } } */ ?>



<!-- old date function -->
<?php
    /*$Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
        
        $age = $Today - $original_Date; 
    }*/
?>
<!-- end of old date function -->


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



<!--popup window-->
<!-- not used-->
<!-- not used-->
<!-- not used-->
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>


<script type='text/javascript'>
    function di(){
        alert("All");
		$( "#d").attr("hidden","false").dialog();
	}
    function b(val){
        alert(val);
    }
</script>
<div id='d' title='CATEGORIES' hidden='hidden'>
    <a href='#' id='s' onclick="b('s')">ALL IS WELL</a><BR/>
    <a href='#'>ALL IS WELL</a><BR/>
    <a href='#'>ALL IS WELL</a><BR/>
    <a href='#'>ALL IS WELL</a><BR/>
    <a href='#'>ALL IS WELL</a><BR/>
    <a href='#'>ALL IS WELL</a><BR/>
</div>
<!-- not used-->
<!-- not used-->
<!-- not used-->
<!-- end of popup window-->






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

<script type="text/javascript" language="javascript">
    function getItemListType(Type) {
	    var Item_Category_Name = document.getElementById('Item_Category').value;
	    if(window.XMLHttpRequest) {
		mm = new XMLHttpRequest();
	    }
	    else if(window.ActiveXObject){ 
		mm = new ActiveXObject('Micrsoft.XMLHTTP');
		mm.overrideMimeType('text/xml');
	    }
	    
	    //getPrice();
	    document.getElementById('Price').value = '';
		mm.onreadystatechange= AJAXP2; //specify name of function that will handle server response....
	    mm.open('GET','GetItemListType.php?Item_Category_Name='+Item_Category_Name+'&Type='+Type,true);
	    mm.send();
	}
    function AJAXP2() {
	var data2 = mm.responseText; 
	document.getElementById('Item_Name').innerHTML = data2;	
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
		mm.open('GET','Get_Guarantor_Name.php?Type_Of_Check_In='+Type_Of_Check_In+'&direction=clinic',true);
		mm.send();
	    }
	}
    function AJAXP3(){
	var data3 = mm.responseText;
	document.getElementById('Consultant').innerHTML = data3;
    }
</script>
<script type="text/javascript" language="javascript">
    function getSubdepartment(){
	var Type_Of_Check_In = document.getElementById('Type_Of_Check_In').value;
	    if (Type_Of_Check_In!="Doctor's Room") {
		    if(window.XMLHttpRequest){
			mm_rqobject = new XMLHttpRequest();
		    }
		    else if(window.ActiveXObject){ 
			mm_rqobject = new ActiveXObject('Micrsoft.XMLHTTP');
			mm_rqobject.overrideMimeType('text/xml');
		    }
		    
		    if (document.getElementById('direction').value =='Direct To Clinic Via Nurse Station' || document.getElementById('direction').value =='Direct To Clinic') {
			mm_rqobject.onreadystatechange= function (){
						    var data = mm_rqobject.responseText;
						    document.getElementById('Sub_Department_ID').innerHTML = data;
						}; //specify name of function that will handle server response....
			mm_rqobject.open('GET','Get_Subdepartment.php?Type_Of_Check_In='+Type_Of_Check_In+'&direction=clinic',true);
			mm_rqobject.send();
		    }
		    else{
			document.getElementById('Sub_Department_ID').innerHTML = '<option></option>';
		    }
		    
	    }else{
		document.getElementById('Sub_Department_ID').innerHTML = '<option></option>';
	    }
	}
</script>
<script type="text/javascript" language="javascript">
    function changeDirection(){
	var Type_Of_Check_In = document.getElementById('Type_Of_Check_In').value;
	    if (Type_Of_Check_In!="Doctor's Room") {
		document.getElementById('direction').innerHTML = '<option></option><option>Direct To Clinic</option><option>Direct To Clinic Via Nurse Station</option>';
	    }else{
		document.getElementById('direction').innerHTML = '<option></option><option>Direct To Doctor</option><option>Direct To Doctor Via Nurse Station</option><option>Direct To Clinic</option><option>Direct To Clinic Via Nurse Station</option>';
	    }
	}
</script>
<!-- end of selection-->


<!-- pricing -->
<script type='text/javascript'>
    function getPrice() {
	
	var Product_Name = document.getElementById('Item_Name').value;
	if (Product_Name!='') {
	    var Billing_Type = document.getElementById('Billing_Type').value;
	    var Guarantor_Name = document.getElementById('Guarantor_Name').value;
	    if(window.XMLHttpRequest) {
		mm = new XMLHttpRequest();
	    }
	    else if(window.ActiveXObject){ 
		mm = new ActiveXObject('Micrsoft.XMLHTTP');
		mm.overrideMimeType('text/xml');
	    }
		mm.onreadystatechange= AJAXP4; //specify name of function that will handle server response....
		mm.open('GET','Get_Item_price.php?Product_Name='+Product_Name+'&Billing_Type='+Billing_Type+'&Guarantor_Name='+Guarantor_Name,true);
		mm.send();
	}
	}
    function AJAXP4(){
	var data4 = mm.responseText;
	document.getElementById('Price').value = data4;
	var price = document.getElementById('Price').value;
	var discount = document.getElementById('Discount').value;
	var quantity = document.getElementById('Quantity').value
	var ammount = 0;
	
	ammount = (price-discount)*quantity;
	document.getElementById('Amount').value = ammount;
    }
</script>






<!-- get employee id-->
<?php
    if(isset($_SESSION['userinfo']['Employee_ID'])){
	$Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    }
?>



<!-- get id, date, Billing Type,Folio number and type of chech in -->
<!-- id will be used as receipt number( Unique from the parent payment table -->
<?php
    if(!isset($_GET['NR']) && !isset($_GET['CP'])){
	//select the current Patient_Payment_ID to use as a foreign key
	$sql_Select_Current_Patient = mysqli_query($conn,"select pp.Patient_Payment_ID,pp.Claim_Form_Number, ppl.Patient_Direction,
					pp.Folio_Number, pp.Payment_Date_And_Time, ppl.Check_In_Type, ppl.Consultant,ppl.Sub_Department_ID,
					    pp.Billing_Type from tbl_patient_payments pp, tbl_patient_payment_item_list ppl
					    where pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
						registration_id = '$Registration_ID' order by pp.Patient_Payment_ID desc limit 1");
    	$no = mysqli_num_rows($sql_Select_Current_Patient);
	if($no > 0){
	    while($row = mysqli_fetch_array($sql_Select_Current_Patient)){
		$Patient_Payment_ID = $row['Patient_Payment_ID'];
		$Payment_Date_And_Time = $row['Payment_Date_And_Time'];
		//$Check_In_Type = $row['Check_In_Type'];
		$Folio_Number = $row['Folio_Number'];
		$Billing_Type = $row['Billing_Type'];
		$Patient_Direction = $row['Patient_Direction'];
		$Consultant = $row['Consultant'];
		$Claim_Form_Number = $row['Claim_Form_Number'];
		$Sub_Department_ID2 = $row['Sub_Department_ID'];
	    }
	//get sub department name to display
	$Get_Sub_Department_Name = mysqli_query($conn,"Select Sub_Department_Name from tbl_Sub_Department where sub_Department_id = '$Sub_Department_ID2' ");
	$num = mysqli_num_rows($Get_Sub_Department_Name);
	if($num > 0){
	    while($row = mysqli_fetch_array($Get_Sub_Department_Name)){
		$Sub_Department_Name = $row['Sub_Department_Name'];
	    }
	}else{
	    $Sub_Department_Name = '';
	}
	
	
	//Select Last Check in to display
	//select the current Patient_Payment_ID to use as a foreign key
	$sql_Select_Current_Check_In = mysqli_query($conn,"select ppl.Check_In_Type from tbl_patient_payments pp, tbl_patient_payment_item_list ppl
				       where pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
					registration_id = '$Registration_ID' order by ppl.Patient_Payment_Item_List_ID desc limit 1");
    	$no = mysqli_num_rows($sql_Select_Current_Check_In);
	if($no > 0){
	    while($row = mysqli_fetch_array($sql_Select_Current_Check_In)){ 
		$Check_In_Type = $row['Check_In_Type']; 
	    }
	}	
	
	
	
	}else{
	    $Patient_Payment_ID = '';
	    $Payment_Date_And_Time = '';
	    $Check_In_Type = '';
	    $Billing_Type = '';
	    $Folio_Number = '';
	    $Patient_Direction = '';
	    $Consultant = '';
	    $Claim_Form_Number = '';
	    $Sub_Department_Name = '';
	}
    } 
    elseif(isset($_GET['CP'])){
	//select the current folio number only to use
	$sql_Select_Current_Patient = mysqli_query($conn,"select pp.Folio_Number from tbl_patient_payments pp
						  where registration_id = '$Registration_ID' order by pp.Patient_Payment_ID desc limit 1");
    	$no = mysqli_num_rows($sql_Select_Current_Patient);
	if($no > 0){
	    while($row = mysqli_fetch_array($sql_Select_Current_Patient)){ 
		$Folio_Number = $row['Folio_Number']; 
	    }
	    $Patient_Payment_ID = '';
	    $Payment_Date_And_Time = '';
	    $Check_In_Type = '';
	    $Billing_Type = '';
	    $Patient_Direction = '';
	    $Consultant = '';
	}else{
	    $Folio_Number = '';
	    $Patient_Payment_ID = '';
	    $Payment_Date_And_Time = '';
	    $Check_In_Type = '';
	    $Billing_Type = '';
	    $Patient_Direction = '';
	    $Consultant = '';
	}
    }
    else{
	$Patient_Payment_ID = '';
	$Payment_Date_And_Time = '';
	$Check_In_Type = '';
	$Billing_Type = '';
	$Folio_Number = '';
	$Patient_Direction = '';
	$Consultant = '';
    }
?>


<?php
	if(isset($_POST['submittedPatientBillingForm'])){ 
	    
	    if(isset($_GET['NR'])){
		$Receipt_Date = mysqli_real_escape_string($conn,$_POST['Receipt_Date_Hidden']); 
		$Claim_Form_Number = mysqli_real_escape_string($conn,$_POST['Claim_Form_Number']); 
		$Billing_Type = mysqli_real_escape_string($conn,$_POST['Billing_Type']);
		$Type_Of_Check_In = mysqli_real_escape_string($conn,$_POST['Type_Of_Check_In']);
		$Item_Category = mysqli_real_escape_string($conn,$_POST['Item_Category']);
		$Item_Name = mysqli_real_escape_string($conn,$_POST['Item_Name']);
		$Discount = mysqli_real_escape_string($conn,$_POST['Discount']);
		//$Price = mysqli_real_escape_string($conn,$_POST['Price']);
		$Quantity = mysqli_real_escape_string($conn,$_POST['Quantity']);
		$direction = mysqli_real_escape_string($conn,$_POST['direction']);
		$Consultant = mysqli_real_escape_string($conn,$_POST['Consultant']);
		if(isset($_POST['Sub_Department_ID'])){
		    $Sub_Department_ID = mysqli_real_escape_string($conn,$_POST['Sub_Department_ID']);
		}else{
		    $Sub_Department_ID = '';
		}
	    }else{
		$Type_Of_Check_In = mysqli_real_escape_string($conn,$_POST['Type_Of_Check_In']);
		$Item_Category = mysqli_real_escape_string($conn,$_POST['Item_Category']);
		$Item_Name = mysqli_real_escape_string($conn,$_POST['Item_Name']);
		$Discount = mysqli_real_escape_string($conn,$_POST['Discount']);
		//$Price = mysqli_real_escape_string($conn,$_POST['Price']);
		$Quantity = mysqli_real_escape_string($conn,$_POST['Quantity']);
		$direction = mysqli_real_escape_string($conn,$_POST['direction']);
		$Consultant = mysqli_real_escape_string($conn,$_POST['Consultant']);
		if(isset($_POST['Sub_Department_ID'])){
		    $Sub_Department_ID = mysqli_real_escape_string($conn,$_POST['Sub_Department_ID']);
		}else{
		    $Sub_Department_ID = '';
		}
	    }
	    
	    /*selecting the price from the database
	    if($Billing_Type=='Outpatient Credit'){
            if(strtolower($Guarantor_Name)=='nhif'){
            $Select_Price = "select Selling_Price_NHIF as price from tbl_items i
                                    where i.Product_Name = '$Product_Name' ";
            }else{
                $Select_Price = "select Selling_Price_Credit as price from tbl_items i
                                    where i.Product_Name = '$Product_Name' ";
            }
	    }elseif($Billing_Type=='Outpatient Cash'){
		$Select_Price = "select Selling_Price_Cash as price from tbl_items i
					where i.Product_Name = '$Product_Name' ";
	    }
	    
		    $result = @mysqli_query($conn,$Select_Price);
		    $row = @mysqli_fetch_assoc($result);
		    echo $row['price'];
	    */
	    
	    
	    
	    
	
	if(isset($_GET['NR']) && !isset($_GET['CP'])){
	    //GENERATING FOLIO NUMBER
	    //GENERATING FOLIO NUMBER
	    //GENERATING FOLIO NUMBER
	    //GENERATING FOLIO NUMBER
	    
		//get the current date		
		$Today_Date = mysqli_query($conn,"select now() as today");
		while($row = mysqli_fetch_array($Today_Date)){
		    $original_Date = $row['today'];
		    $new_Date = date("Y-m-d", strtotime($original_Date));
		    $Today = $new_Date; 
		}
		
		//GET BRANCH ID
		$Folio_Branch_ID = $_SESSION['userinfo']['Branch_ID'];
                //check if the current date and the last folio number are in the same month and year
                //select the current folio number to check the month and year
		//$current_folio = mysqli_query($conn,"select Folio_Number, Folio_date from tbl_folio where sponsor_id = '$Sponsor_ID' order by folio_id desc limit 1");
		$current_folio = mysqli_query($conn,"select Folio_Number, Folio_date
						from tbl_folio where sponsor_id = '$Sponsor_ID' and
						    Branch_ID = '$Folio_Branch_ID' order by folio_id desc limit 1");
                $no = mysqli_num_rows($current_folio); 
                if($no > 0){
                    while($row = mysqli_fetch_array($current_folio)){
                        $Folio_Number = $row['Folio_Number'];
                        $Folio_date = $row['Folio_date'];
                    } 
                }else{
                    $Folio_Number = 1;
                    $Folio_date = 0;
                }
		
		//check the current month and year (Remove the day part of the date)
		$Current_Month_and_year = substr($Today,0,7); 
		
		//check month and year of the last folio number (Remove the day part of the date)
		$Last_Folio_Month_and_year = substr($Folio_date,0,7); 
		
		//compare month and year    
		if($Last_Folio_Month_and_year == $Current_Month_and_year){
		    mysqli_query($conn,"insert into tbl_folio(Folio_Number,Folio_date,Sponsor_id,branch_id)
				values(($Folio_Number+1),(select now()),'$Sponsor_ID','$Folio_Branch_ID')") or die(mysqli_error($conn));
		    $Folio_Number = $Folio_Number + 1;
		}else{
		    mysqli_query($conn,"insert into tbl_folio(Folio_Number,Folio_date,Sponsor_id,branch_id)
				values(1,(select now()),'$Sponsor_ID','$Folio_Branch_ID')");
		    $Folio_Number = 1;
		}
		
	    //END OF GENERATING FOLIO NUMBER
	    //END OF GENERATING FOLIO NUMBER
	    //END OF GENERATING FOLIO NUMBER
	    //END OF GENERATING FOLIO NUMBER
	    
	}    
	    
	    
	    if(isset($_GET['NR'])) {
	    //generate new receipt
	    
	    //---get supervisor id 
		if(isset($_SESSION['supervisor'])) {
		    if(isset($_SESSION['supervisor']['Session_Master_Priveleges'])){
			if($_SESSION['supervisor']['Session_Master_Priveleges'] = 'yes'){
			    $Supervisor_ID = $_SESSION['supervisor']['Employee_ID'];
			}else{
			    $Supervisor_ID = '';
			}
		    }else{
			 $Supervisor_ID = '';
		    }
		}else{
			 $Supervisor_ID = '';
		}
	   //end of fetching supervisor id
	    
	    
	    
		$Insert_Data_To_tbl_patient_payments = "insert into tbl_patient_payments(
			    registration_id,supervisor_id,employee_id,
				payment_date_and_time,folio_number,claim_form_number,
				    sponsor_id,sponsor_name,billing_type,receipt_date)
			values(
			    '$Registration_ID','$Supervisor_ID','$Employee_ID',
				(select now()),'$Folio_Number','$Claim_Form_Number',
				    '$Sponsor_ID','$Guarantor_Name','$Billing_Type',(select now()))";
				     	    
		if(!mysqli_query($conn,$Insert_Data_To_tbl_patient_payments)){
		    echo "<script type='text/javascript'>
			    alert('TRANSACTION FAIL');
			    document.location = './departmentpatientbilling.php?section=".$section."&This=1&Patient_Payment_ID=".$Patient_Payment_ID."&Registration_ID=".$Registration_ID."&Fail=True&TarnsactionFail=TransactionFailThisPage';
			    </script>";
		}
		
		
		//Getting item price from the database
		    if($Billing_Type=='Outpatient Credit'){
			if(strtolower($Guarantor_Name)=='nhif'){
		            $Select_Price = "select Selling_Price_NHIF as price from tbl_items i
						where i.item_id = '$Item_Name' ";
			}else{
			    $Select_Price = "select Selling_Price_Credit as price from tbl_items i
						where i.item_id = '$Item_Name' ";
			}
		    }elseif($Billing_Type=='Outpatient Cash'){
			$Select_Price = "select Selling_Price_Cash as price from tbl_items i
					    where i.item_id = '$Item_Name' ";
		    }
        
		    $result = @mysqli_query($conn,$Select_Price);
		    $row = @mysqli_fetch_assoc($result);
		    
		    $Price = $row['price'];
		
		//select the current Patient_Payment_ID to use as a foreign key (Also will be used as a new receipt number)
		$sql_Select_date = mysqli_query($conn,"select Patient_Payment_ID, Payment_Date_And_Time from tbl_patient_payments
				       where registration_id = '$Registration_ID' order by Patient_Payment_ID desc limit 1");
		
		while($row = mysqli_fetch_array($sql_Select_date)){
		    $Patient_Payment_ID = $row['Patient_Payment_ID'];
		    $Payment_Date_And_Time = $row['Payment_Date_And_Time']; 
		}
		//understand the patient direction to allow the query either to select clinic id of doctor id
                if(strtolower($direction) == 'direct to doctor' || strtolower($direction) == 'direct to doctor via nurse station'){    
                    $Insert_Data_To_tbl_patient_payment_item_list = "insert into tbl_patient_payment_item_list(
                            check_In_type,category,item_id,discount,price,quantity,
                                    patient_direction,consultant,consultant_id,patient_payment_id,Transaction_Date_And_Time,Sub_Department_ID)
                            values(
                                '$Type_Of_Check_In','$Item_Category','$Item_Name',
                                    '$Discount','$Price','$Quantity',
                                        '$direction','$Consultant',(select Employee_ID from tbl_employee where employee_name = '$Consultant'),
                                            '$Patient_Payment_ID',(select now()),'$Sub_Department_ID')";
                }elseif(strtolower($direction) == 'direct to clinic' || strtolower($direction) == 'direct to clinic via nurse station'){
                    $Insert_Data_To_tbl_patient_payment_item_list = "insert into tbl_patient_payment_item_list(
                            check_In_type,category,item_id,discount,price,quantity,
                                    patient_direction,consultant,consultant_id,patient_payment_id,Transaction_Date_And_Time,Sub_Department_ID)
                            values(
                                '$Type_Of_Check_In','$Item_Category','$Item_Name',
                                    '$Discount','$Price','$Quantity',
                                        '$direction','$Consultant',(select clinic_ID from tbl_clinic where clinic_name = '$Consultant')
                                        ,'$Patient_Payment_ID',(select now()),'$Sub_Department_ID')";                   
                }
                
                		    
		if(!mysqli_query($conn,$Insert_Data_To_tbl_patient_payment_item_list)){ 
		    echo "<script type='text/javascript'>
			    alert('TRANSACTION FAIL');
			    document.location = './departmentpatientbilling.php?section=".$section."&This=2&Patient_Payment_ID=".$Patient_Payment_ID."&Registration_ID=".$Registration_ID."&Fail=True&TarnsactionFail=TransactionFailThisPage';
			    </script>";
		}
		else {
		    //get branch id
		    $Branch_ID = $_SESSION['userinfo']['Branch_ID'];
		    mysqli_query($conn,"update tbl_check_in set Check_In_Status = 'saved' where registration_id = '$Registration_ID' and branch_id = '$Branch_ID'");
                    echo "<script type='text/javascript'>
			    alert('TRANSACTION ADDED SUCCESSFUL');
			    document.location = './departmentpatientbilling.php?section=".$section."&Patient_Payment_ID=".$Patient_Payment_ID."&Registration_ID=".$Registration_ID."&UpdatePayments=UpdatePaymentsThisPage';
			    </script>"; 
		}
	    }else{
		
		//Getting item price from the database
		    if($Billing_Type=='Outpatient Credit'){
			if(strtolower($Guarantor_Name)=='nhif'){
		            $Select_Price = "select Selling_Price_NHIF as price from tbl_items i
						where i.Item_ID = '$Item_Name' ";
			}else{
			    $Select_Price = "select Selling_Price_Credit as price from tbl_items i
						where i.Item_ID = '$Item_Name' ";
			}
		    }elseif($Billing_Type=='Outpatient Cash'){
			$Select_Price = "select Selling_Price_Cash as price from tbl_items i
					    where i.Item_ID = '$Item_Name' ";
		    }
		    $result = @mysqli_query($conn,$Select_Price);
		    $row = @mysqli_fetch_assoc($result); 
		    $Price = $row['price'];
		    
		    
		    
		//select the current Patient_Payment_ID to use as a foreign key (Also this is working as receipt number)
		$sql_Select_date = mysqli_query($conn,"select Patient_Payment_ID, Billing_Type, Payment_Date_And_Time from tbl_patient_payments where
						registration_id = '$Registration_ID' order by Patient_Payment_ID desc limit 1");
		
		while($row = mysqli_fetch_array($sql_Select_date)){
		    $Patient_Payment_ID = $row['Patient_Payment_ID'];
		    $Payment_Date_And_Time = $row['Payment_Date_And_Time'];
		    $Billing_Type = $row['Billing_Type'];
		}
		
                //we insert employee id if the option is direct to doctor
		//and we insert clinic id if the option is direct to clinic
                if(strtolower($direction) == 'direct to doctor' || strtolower($direction) == 'direct to doctor via nurse station'){    
                    $Insert_Data_To_tbl_patient_payment_item_list = "insert into tbl_patient_payment_item_list(
                            check_In_type,category,item_id,
                                discount,price,quantity,
                                    patient_direction,consultant,consultant_id,patient_payment_id,Transaction_Date_And_Time,Sub_Department_ID)
                            values(
                                '$Type_Of_Check_In','$Item_Category','$Item_Name',
                                    '$Discount','$Price','$Quantity',
                                        '$direction','$Consultant',(select Employee_ID from tbl_employee where employee_name = '$Consultant'),
                                        '$Patient_Payment_ID',(select now()),'$Sub_Department_ID')";
                }elseif(strtolower($direction) == 'direct to clinic' || strtolower($direction) == 'direct to clinic via nurse station'){
	    	    $Insert_Data_To_tbl_patient_payment_item_list = "insert into tbl_patient_payment_item_list(
			check_In_type,category,item_id,
			    discount,price,quantity,
				patient_direction,consultant,consultant_id,patient_payment_id,Transaction_Date_And_Time,Sub_Department_ID)
			values(
			    '$Type_Of_Check_In','$Item_Category','$Item_Name',
				'$Discount','$Price','$Quantity',
				    '$direction','$Consultant',(select clinic_ID from tbl_clinic where clinic_name = '$Consultant'),
                                    '$Patient_Payment_ID',(select now()),'$Sub_Department_ID')";
                }
		if(!mysqli_query($conn,$Insert_Data_To_tbl_patient_payment_item_list)){ 
		    /*$Foreign_Key_Error = mysql_errno()."Yes";
		    if($Foreign_Key_Error == '1452Yes'){
			echo '<span style="color: red;"><b>TRANSACTION FAIL.</b></span>';
		    }
		    die(mysqli_error($conn));*/
		    //die(mysqli_error($conn));
		    echo "<script type='text/javascript'>
			    alert('TRANSACTION FAIL');
			    document.location = './departmentpatientbilling.php?section=".$section."&This=3&Patient_Payment_ID=".$Patient_Payment_ID."&Registration_ID=".$Registration_ID."&Fail=True&TarnsactionFail=TransactionFailThisPage';
			    </script>";
		} 
		else {
		    mysqli_query($conn,"update tbl_check_in set Check_In_Status = 'saved' where registration_id = '$Registration_ID'");
                    echo "<script type='text/javascript'>
			    alert('TRANSACTION ADDED SUCCESSFUL');
			    document.location = './departmentpatientbilling.php?section=".$section."&Patient_Payment_ID=".$Patient_Payment_ID."&Registration_ID=".$Registration_ID."&UpdatePayments=UpdatePaymentsThisPage';
			    </script>"; 
		}
	    } 
	}
?>
<!--end of process-->






<script language="javascript" type="text/javascript">
    function searchPatient(Patient_Name){
        document.getElementById('Search_Iframe').innerHTML = "<iframe width='100%' height=350px src='viewpatientsIframe.php?Patient_Name="+Patient_Name+"'></iframe>";
    }
</script>
<form action='#' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">
<!--<br/>-->
<fieldset>  
            <legend align=right><b>REVENUE CENTER</b></legend>
        <center> 
            <table width=100%> 
                <tr> 
                    <td>
                        <table width=100%>
                            <tr>
                                <td width='10%'><b>Patient Name</b></td>
                                <td width='15%'><input type='text' name='Patient_Name' disabled='disabled' id='Patient_Name' value='<?php echo $Patient_Name; ?>'></td>
                                <td width='12%'><b>Card Expire Date</b></td>
                                <td width='15%'><input type='text' name='Card_ID_Expire_Date' disabled='disabled' id='Card_ID_Expire_Date' value='<?php echo $Member_Card_Expire_Date; ?>'></td> 
                                <td width='11%'><b>Gender</b></td>
                                <td width='12%'><input type='text' name='Receipt_Number' disabled='disabled' id='Receipt_Number' value='<?php echo $Gender; ?>'></td>
				<td><b>Receipt Number</b></td>
                                <td><input type='text' name='Receipt_Number' disabled='disabled' id='Receipt_Number' value='<?php echo $Patient_Payment_ID; ?>'></td>
                            </tr>  
                            <tr>
                                <td><b>Billing Type</b></td>
				
				<?php if(isset($_GET['NR'])) { ?>
                                <td>
				    
				<?php if(isset($_GET['section'])){
				    if(strtolower($_GET['section']) == 'admission'){ ?>
                                    <select name='Billing_Type' id='Billing_Type'  onchange='getPrice()' required='required'>
					<option selected='selected'></option>
					<?php if(strtolower($Guarantor_Name) != 'cash') {?>
					    <option>Inpatient Credit</option>
					<?php } ?>
                                        <?php
                                            //check if employee have the privilege to perform cash transaction then display the item list
                                            if(isset($_SESSION['userinfo']['Cash_Transactions'])){
                                                if(strtolower($_SESSION['userinfo']['Cash_Transactions']) == 'yes'){
                                                    echo '<option>Inpatient Cash</option>';
                                                }
                                            }                                       
                                        ?>                                        
                                    </select>
				    
				<?php }else{ ?>
                                    <select name='Billing_Type' id='Billing_Type'  onchange='getPrice()' required='required'>
					<option selected='selected'></option>
					<?php if(strtolower($Guarantor_Name) != 'cash') {?>
					    <option>Outpatient Credit</option>
					<?php } ?>
                                        <?php
                                            //check if employee have the privilege to perform cash transaction then display the item list
                                            if(isset($_SESSION['userinfo']['Cash_Transactions'])){
                                                if(strtolower($_SESSION['userinfo']['Cash_Transactions']) == 'yes'){
                                                    echo '<option>Outpatient Cash</option>';
                                                }
                                            }                                       
                                        ?>                                        
                                    </select>
				    
				<?php } } ?>
                                </td>
				<?php }else{ ?>
				<td>
                                    <select name='Billing_Type' id='Billing_Type' disabled='disabled'>
					<option selected='selected'><?php echo $Billing_Type; ?></option> 
                                    </select>
                                </td>
				<?php } ?>
				
                                <td><b>Claim Form Number</b></td>
				    <?php
					$select_claim_status = mysqli_query($conn,"select Claim_Number_Status from tbl_sponsor where Guarantor_Name = '$Guarantor_Name'");
					while($row = mysqli_fetch_array($select_claim_status)){
					    $Claim_Number_Status = $row['Claim_Number_Status'];
					} 
				    ?>
				<?php if(!isset($_GET['NR']) && !isset($_GET['CP'])){ ?>
				    <td><input type='text' name='Claim_Form_Number' disabled='disabled' id='Claim_Form_Number' value='<?php echo $Claim_Form_Number; ?>'></td>
				<?php }else{ ?>
				    <?php if(strtolower($Claim_Number_Status) == 'mandatory'){ ?>
					<td><input type='text' name='Claim_Form_Number' id='Claim_Form_Number' required='required'></td>
				    <?php } else { ?>
					<td><input type='text' name='Claim_Form_Number' id='Claim_Form_Number'></td>
				    <?php } ?>
				<?php } ?>
                                
                                <td><b>Occupation</b></td>
                                <td>
				    <input type='text' name='Receipt_Date' disabled='disabled' id='date2' value='<?php echo $Occupation; ?>'>
				</td>
				<td><b>Receipt Date & Time</b></td> 
                                <td>
				    <input type='text' name='Receipt_Date' disabled='disabled' id='date2' value='<?php echo $Payment_Date_And_Time; ?>'>
				    <input type='hidden' name='Receipt_Date_Hidden' id='Receipt_Date_Hidden' value='<?php echo $Payment_Date_And_Time; ?>'>
				</td>
                            </tr>
                            <tr>
                                <td><b>Type Of Check In</b></td>
                                <td>  
				<select name='Type_Of_Check_In' id='Type_Of_Check_In' required='required' onchange='changeDirection();getDoctor();getSubdepartment()'>
				    
				    <?php if(!isset($_GET['NR'])){ ?>
					<option selected='selected'><?php echo $Check_In_Type; ?></option>
				    <?php } else{ ?>
					<option selected='selected'><?php $Check_In_Type; ?></option>
				    <?php } ?>
				    <option>Cecap</option>
				    <option>Dental</option>
				    <option>Dialysis</option>
				    <option>Doctor's Room</option>
				    <option>Dressing</option>
				    <option>Ear</option>
				    <option>HIV</option>
				    <option>Laboratory</option>
				    <option>Matenity</option>
				    <option>Optical</option>
				    <option>Pharmacy</option>
				    <option>Physiotherapy</option>
				    <option>Radiology</option>
				    <option>Rch</option>
				    <option>Theater</option> 
				</select>
				</td>
                                <td><b>Patient Age</b></td>
                                <td><input type='text' name='Patient_Age' id='Patient_Age'  disabled='disabled' value='<?php echo $age; ?>'></td>
                                <td><b>Registered Date</b></td>
                                <td><input type='text' name='Folio_Number' id='Folio_Number' disabled='disabled' value='<?php echo $Registration_Date_And_Time; ?>'></td>
                                <td><b>Folio Number</b></td>
                                <td><input type='text' name='Folio_Number' id='Folio_Number' disabled='disabled' value='<?php echo $Folio_Number; ?>'></td>
                            </tr>
                            <tr> 
                                <td><b>Patient Direction</b></td>
                                <td>
                                    <select id='direction' name='direction' onclick='getDoctor();getSubdepartment()' required='required'>
					<?php if(!isset($_GET['NR']) && !isset($_GET['CP'])){ ?>
					    <option selected='selected'><?php echo $Patient_Direction; ?></option>
					<?php }else{ ?>
					<option selected='selected'></option>
					<?php } ?>
                                        <option>Direct To Doctor</option>
                                        <option>Direct To Doctor Via Nurse Station</option>
                                        <option>Direct To Clinic</option>
                                        <option>Direct To Clinic Via Nurse Station</option>
                                    </select>
                                </td>
                                <td><b>Sponsor Name</b></td>
                                <td><input type='text' name='Guarantor_Name' disabled='disabled' id='Guarantor_Name' value='<?php echo $Guarantor_Name; ?>'></td>
                                <td><b>Phone Number</b></td>
                                <td><input type='text' name='Phone_Number' id='Phone_Number' disabled='disabled' value='<?php echo $Phone_Number; ?>'></td>
				
				<td><b>Prepared By</b></td>
                                <td><input type='text' name='Prepared_By' id='Prepared_By' disabled='disabled' value='<?php echo $Employee_Name; ?>'></td>
                            </tr>
                            <tr>
                                <td><b>Consultant</b></td>
                                <td>
				    <select name='Consultant' id='Consultant' value='<?php echo $Guarantor_Name; ?>' onchange='getSubdepartment()'>
					
					<?php if(!isset($_GET['NR']) && !isset($_GET['CP'])){ ?>
					    <option selected='selected'><?php echo $Consultant; ?></option>
					<?php }else{ ?>
					<option selected='selected'></option>
					<?php } ?>
					
					<?php
					$Select_Consultant = "select * from tbl_clinic"; 
					$result = mysqli_query($conn,$Select_Consultant);
					?> 
					<?php
					while($row = mysqli_fetch_array($result)){
					    ?>
					    <option><?php echo $row['Clinic_Name']; ?></option>
					<?php
					}
					
					$Select_Doctors = "select * from tbl_employee where employee_type = 'Doctor'"; 
					$result = mysqli_query($conn,$Select_Doctors);
					?> 
					<?php
					while($row = mysqli_fetch_array($result)){
					    ?>
					    <option><?php echo $row['Employee_Name']; ?></option>
					<?php
					}
					
					?>
				    </select>
				</td>
                                <td><b>Registration Number</b></td>
                                <td><input type='text' name='Registration_Number' id='Registration_Number' disabled='disabled' value='<?php echo $Registration_ID; ?>'></td>
                                <td><b>Member Number</b></td>
                                <td><input type='text' name='Supervised_By' id='Supervised_By' disabled='disabled' value='<?php echo $Member_Number; ?>'></td>
				<td><b>Supervised By</b></td>
				
				<?php
				    if(isset($_SESSION['supervisor'])) {
					if(isset($_SESSION['supervisor']['Session_Master_Priveleges'])){
					    if($_SESSION['supervisor']['Session_Master_Priveleges'] = 'yes'){
						$Supervisor = $_SESSION['supervisor']['Employee_Name'];
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
			    <tr>
				<td><b>Location </b></td>
				<td>
				    <select name='Sub_Department_ID' id='Sub_Department_ID'>
					<?php if(!isset($_GET['NR']) && !isset($_GET['CP']) && $Check_In_Type != ''){ ?>
					    <option selected='selected'><?php echo $Sub_Department_Name; ?></option>
					    <!-- Get list of items from this department -->
					    <?php
						$Get = mysqli_query($conn,"select Sub_Department_ID, Sub_Department_Name
								    from tbl_Sub_Department sd,tbl_Department d where
									sd.Department_ID = d.Department_id and
									d.Department_Location = '$Check_In_Type' and
								   Sub_Department_Name <> '$Sub_Department_Name'") or die(mysqli_error($conn));
						$number = mysqli_num_rows($Get);
						if($number > 0){
						    while($row = mysqli_fetch_array($Get)){
							echo "<option value = '".$row['Sub_Department_ID']."'>".$row['Sub_Department_Name']."</option>";
						    }
						}
					    ?>
					<?php }else{ ?>
					<option></option>
					<?php } ?>
				    </select>
				</td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
			    </tr>
                        </table>
                    </td> 
                </tr>
            </table>
        </center>
</fieldset>

<fieldset>   
        <center>
            <table width=100%>
                <tr>
                    <td>Type</td>
                    <td>Category</td>
                    <td>Item Description</td>
                    <td>Discount</td>
                    <td>Price</td>
                    <td>Balance</td>
                    <td>Qty</td>
                    <td>Amount</td>
		    
		    <?php /*
			if($Patient_Payment_ID != 0){		    
			    echo "<td> <a href='individualpaymentreportindirect.php?Patient_Payment_ID=".$Patient_Payment_ID."&IndividualPaymentReport=IndividualPaymentReportThisPage' target='_blank' class='art-button-green'>RECEIPT</a>";
			}else{
			    echo '<td>&nbsp;</td>';
			} */
		    ?>
                    <?php
			if($Patient_Payment_ID != 0){		    
			    echo "<td>";?>
			    <!--PRINTING RECEIPT-->
			<script>
			function printReceipt(){
			    //code
			    //print receipt on the next tab
			    window.open("individualpaymentreportindirect.php?Patient_Payment_ID=<?php echo $Patient_Payment_ID;?>&IndividualPaymentReport=IndividualPaymentReportThisPage","_blank");
			    //reload page 
			    window.location = "visitorform.php?UpdatePayments=UpdatePaymentsThisPage&Redirect=VitualRedirect&Action=Major";
			    //document.getElementById("myForm").reset();
			}
			</script>
		    <!--END OF PRINT RECEIPT-->
			    <input type='button' id='print_receipt' name='print_receipt' value='RECEIPT' class='art-button-green' onclick='printReceipt();'>
		    <?php
		    }else{
			    echo '<td>&nbsp;</td>';
			}
		    ?>
                </tr>
                <tr>
		    <td> 
                        <select name='Type' id='Type' onchange='getItemListType(this.value)'>
			    <option selected='selected'>All Available</option>
                            <option>Service</option>
                            <option>Pharmacy</option> 
                        </select>
                    </td>
                    <td>
                        <select name='Item_Category' id='Item_Category' onchange='getItemList(this.value)'>
			    <option selected='selected'></option>
			    <?php
                                $data = mysqli_query($conn,"select * from tbl_item_category");
                                while($row = mysqli_fetch_array($data)){
                                    echo '<option>'.$row['Item_Category_Name'].'</option>';
                                }
                            ?>   
			</select>
                    </td>
                    <td> 
                        <select name='Item_Name' id='Item_Name' onchange='getPrice()' required='required'>
			    <option selected='selected'></option>
			    <option>All Available</option>
                            <?php
                                $data = mysqli_query($conn,"SELECT * FROM tbl_items");
                                while($row = mysqli_fetch_array($data)){
                                    echo "<option value='".$row['Item_ID']."'>".$row['Product_Name'].'</option>';
                                }
                            ?> 
                        </select>
                    </td> 
                    <td>
                        <input type='text' name='Discount' id='Discount' placeholder='Discount' onchange='getPrice()' onkeypress='getPrice()' value=0>
                    </td>
                    <td>
                        <input type='text' name='Price' id='Price' placeholder='Price'>
                    </td>
                    <td>
                        <input type='text' name='Balance' id='Balance' placeholder='Balance' value=1 disabled='disabled'>
                    </td>
                    <td>
                        <input type='text' name='Quantity' id='Quantity' required='required' placeholder='Quantity' onchange='getPrice()' onkeypress='getPrice()'>
                    </td>
                    <td>
                        <input type='text' name='Amount' id='Amount' placeholder='Amount' disabled='disabled'>
                    </td>
                    <td style='text-align: center;'>
                        <input type='submit' name='submit' id='submit' value='ADD' class='art-button-green'>
                        <input type='hidden' name='submittedPatientBillingForm' value='true'/> 
                    </td>
                </tr>
            </table>  
        </center>
</fieldset>
<fieldset>   
        <center>
            <table width=100%>
		<tr>
		    <td>
			<iframe width='100%' src='Patient_Billing_Iframe.php?Patient_Payment_ID=<?php echo $Patient_Payment_ID; ?>' width='100%' height=250px></iframe>
		    </td>
		</tr> 
	    </table>
        </center>
</fieldset>
<?php
    include("./includes/footer.php");
?>