<link rel="stylesheet" href="table.css" media="screen">
<?php
    session_start();
    include("./includes/connection.php");
    
    //get variables from previous form
    if(isset($_GET['Type'])){
	$Type = $_GET['Type'];
    }else{
	header("location : ../index.php");
    }
    if(isset($_GET['Item_Category_Name'])){
	$Item_Category_Name = $_GET['Item_Category_Name'];
    }else{
	header("location : ../index.php");
    }
    
    if(isset($_GET['Registration_ID'])){
	$Registration_ID = $_GET['Registration_ID'];
    }else{
	header("location : ../index.php");
    }
    if(isset($_GET['item_name'])){
	$item_name = $_GET['item_name'];
    }else{
	$item_name = '';
    }
    ?>
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
	<script type='text/javascript'>
	    function sendOrRemove(Item_ID,check_ID) {
		<?php
		if(isset($_GET['bill_type'])){
		    ?>
		var bill_type = '<?php echo $_GET['bill_type'];?>';  
		    <?php
		}else{
		?>
		var bill_type = '';
		<?php
		}
		?>
		
		var action;
		var Patient_Direction = document.getElementById('Patient_Direction_'+Item_ID+'').value;
		var Registration_ID = "<?php echo $_GET['Registration_ID']; ?>";
		
		var quantity = document.getElementById('quantity_'+Item_ID+'').value;
		var Type_Of_Check_In = document.getElementById('Type_Of_Check_In_'+Item_ID+'').value;
		
		var Discount = document.getElementById('Discount_'+Item_ID+'').value;
		
		//var loc = document.getElementById('location_'+Item_ID+'').value;
		
		if (check_ID.checked==true){
		    action = "ADD";
		}else{
		    action = "REMOVE";
		}
		
		if(Item_ID!='') {
		    if(window.XMLHttpRequest) {
			myObject = new XMLHttpRequest();
		    }
		    else if(window.ActiveXObject){
			myObject = new ActiveXObject('Micrsoft.XMLHTTP');
			myObject.overrideMimeType('text/xml');
		    }
		if (action=='REMOVE') {
			myObject.onreadystatechange= sendOrRemove_AJAX; //specify name of function that will handle server response....
			myObject.open('GET','sendOrRemovePatientbilling_Item.php?action='+action+'&Item_ID='+Item_ID+'&bill_type='+bill_type+'&Registration_ID='+Registration_ID+'&Type_Of_Check_In='+Type_Of_Check_In,true);
			myObject.send();
		}else{
		    if (bill_type!='') {
			myObject.onreadystatechange= sendOrRemove_AJAX; //specify name of function that will handle server response....
			//myObject.open('GET','sendOrRemovePatientbilling_Item.php?action='+action+'&loc='+loc+'&Patient_Direction='+Patient_Direction+'&quantity='+quantity+'&Item_ID='+Item_ID+'&bill_type='+bill_type+'&Registration_ID='+Registration_ID+'&Type_Of_Check_In='+Type_Of_Check_In+'&Discount='+Discount,true);
			myObject.open('GET','sendOrRemovePatientbilling_Item.php?action='+action+'&Patient_Direction='+Patient_Direction+'&quantity='+quantity+'&Item_ID='+Item_ID+'&bill_type='+bill_type+'&Registration_ID='+Registration_ID+'&Type_Of_Check_In='+Type_Of_Check_In+'&Discount='+Discount,true);
			myObject.send();
			}else{
			    check_ID.checked = false;
			    message = 'Choose Bill Type';
			    alert(message);
			}
		}   
	    }
	}
    function sendOrRemove_AJAX(){
    }
	</script>
	<script type="text/javascript" language="javascript">
	    function getDoctor(Item_ID) {
		var Type_Of_Check_In = document.getElementById('Type_Of_Check_In_'+Item_ID+'').value;
		    if(window.XMLHttpRequest) {
			mm = new XMLHttpRequest();
		    }
		    else if(window.ActiveXObject){ 
			mm = new ActiveXObject('Micrsoft.XMLHTTP');
			mm.overrideMimeType('text/xml');
		    }
		    
		    if (document.getElementById('Patient_Direction_'+Item_ID+'').value =='Direct To Doctor Via Nurse Station' || document.getElementById('Patient_Direction_'+Item_ID+'').value =='Direct To Doctor') {
			mm.onreadystatechange= function(){
						    var data3 = mm.responseText;
						    document.getElementById('Consultant_'+Item_ID+'').innerHTML = data3;
						}; //specify name of function that will handle server response....
			mm.open('GET','Get_Guarantor_Name.php?Type_Of_Check_In='+Type_Of_Check_In+'&direction=doctor',true);
			mm.send();
		    }
		    else{
			mm.onreadystatechange= function(){
						    var data3 = mm.responseText;
						    document.getElementById('Consultant_'+Item_ID+'').innerHTML = data3;
						}; //specify name of function that will handle server response....
			mm.open('GET','Get_Guarantor_Name.php?Type_Of_Check_In='+Type_Of_Check_In+'&direction=clinic',true);
			mm.send();
		    }
		}
	    function getSubdepartment(Item_ID){
		var Type_Of_Check_In = document.getElementById('Type_Of_Check_In_'+Item_ID+'').value;
		    if (Type_Of_Check_In!="Doctor's Room") {
			    if(window.XMLHttpRequest){
				mm_rqobject = new XMLHttpRequest();
			    }
			    else if(window.ActiveXObject){ 
				mm_rqobject = new ActiveXObject('Micrsoft.XMLHTTP');
				mm_rqobject.overrideMimeType('text/xml');
			    }
			    
			    if (document.getElementById('Patient_Direction_'+Item_ID+'').value =='Direct To Clinic Via Nurse Station' || document.getElementById('Patient_Direction_'+Item_ID+'').value =='Direct To Clinic') {
				mm_rqobject.onreadystatechange= function (){
							    var data_result = mm_rqobject.responseText;
							    document.getElementById('Sub_Department_ID_'+Item_ID+'').innerHTML = data_result;
							}; //specify name of function that will handle server response....
				mm_rqobject.open('GET','Get_Subdepartment.php?Type_Of_Check_In='+Type_Of_Check_In+'&direction=clinic',true);
				mm_rqobject.send();
			    }
			    else{
				document.getElementById('Sub_Department_ID_'+Item_ID+'').innerHTML = '<option></option>';
			    }
			    
		    }else{
			document.getElementById('Sub_Department_ID_'+Item_ID+'').innerHTML = '<option></option>';
		    }
		}
	function getPrice(Item) {
	    var Product_Name = document.getElementById('Item_Name_'+Item+'').value;
		if (Product_Name!='') {
		    var Billing_Type = document.getElementById('Billing_Type_'+Item+'').value;
		    var Guarantor_Name = '<?php echo $Guarantor_Name; ?>';
		    if(window.XMLHttpRequest) {
			mm_price = new XMLHttpRequest();
		    }
		    else if(window.ActiveXObject){ 
			mm_price = new ActiveXObject('Micrsoft.XMLHTTP');
			mm_price.overrideMimeType('text/xml');
		    }
			mm_price.onreadystatechange= function (){
							    var data4 = mm_price.responseText;
							    document.getElementById('Price_'+Item+'').value = data4;
							    var price = document.getElementById('Price_'+Item+'').value;
							    var discount = document.getElementById('Discount_'+Item+'').value;
							    var quantity = document.getElementById('quantity_'+Item+'').value;
							    var ammount = 0;
							    
							    ammount = (price-discount)*quantity;
							    document.getElementById('ammount_'+Item+'').value = ammount;
							}; //specify name of function that will handle server response....
			mm_price.open('GET','Get_Item_price.php?Product_Name='+Product_Name+'&Billing_Type='+Billing_Type+'&Guarantor_Name='+Guarantor_Name,true);
			mm.send();
		}
	    }
	</script>
	    <table width='100%'>
		<tr id='thead'>
                    <td style='width: 2%'><b>Action</b></td>
                    <td style='width: 2%'><b>SN</b></td>
                    <td style='width: 10%'><b>Item</b></td>
		    <td style='width: 5%'><b>Check In Type</b></td>
                    <td style='width: 6%'><b>Direction</b></td>
		    <td style='width: 3%'><b>Consultant</b></td>
		    <!--<td style='width: 5%'><b>Location</b></td>-->
		    <td style='width: 2%'><b>Disco</b></td>
                    <td style='width: 2%'><b>Price</b></td>
                    <td style='width: 1%'><b>Blnc</b></td>
		    <td style='width: 1%'><b>Qty</b></td>
		    <td style='width: 1%'><b>Amount</b></td>
                </tr>
		<tr>
		    <td colspan=12>
			<hr>
		    </td>
		</tr>
                <?php
                $i = 1;
		$qr = "SELECT * FROM tbl_items WHERE Product_Name LIKE '%$item_name%' ";
		if($Type !="All Available"){
                    $qr.= " AND Item_Type='$Type'";
		    if($Item_Category_Name==''){
		    }else{
			$qr.= " AND Item_Subcategory_ID IN (SELECT isc.Item_Subcategory_ID FROM
			tbl_item_category ic, tbl_item_subcategory isc WHERE ic.Item_category_ID = isc.Item_category_ID
			AND ic.Item_Category_Name='$Item_Category_Name') ";
		    }
		}else{
		    if($Item_Category_Name == ''){
		    }else{
			$qr.= " AND Item_Subcategory_ID IN (SELECT isc.Item_Subcategory_ID FROM
			tbl_item_category ic, tbl_item_subcategory isc WHERE ic.Item_category_ID = isc.Item_category_ID
			AND ic.Item_Category_Name='$Item_Category_Name')";
		    }
		}
		$result = mysqli_query($conn,$qr);
                while($row = @mysqli_fetch_assoc($result)){
		    $Item_ID=$row['Item_ID'];
		    $checked_item_qr = "SELECT item_ID FROM tbl_payment_item_list_cache WHERE item_ID =$Item_ID  AND
		    Employee_ID = ".$_SESSION['userinfo']['Employee_ID']." AND Registration_ID = $Registration_ID ";
		    $checked = false;
		    $checked_item_qr_result = mysqli_query($conn,$checked_item_qr);
		    if(mysqli_num_rows($checked_item_qr_result)>0){
			$checked_item_qr_row = mysqli_fetch_assoc($checked_item_qr_result);
			$checked = true;
		    }
		    ?>
		    <tr>
                    <td style='text-align: center;vertical-align: middle;'><input type='checkbox' <?php
		    if($checked==true){
			?>
			checked='checked'
			<?php
		    }
		    ?> value='Yes' onclick="sendOrRemove('<?php echo $row['Item_ID']; ?>',this)"></td>
		    <td style='text-align: center;vertical-align: middle;' ><?php echo $i;?></td>
                    <td>
			<input type='text' id='Item_ID' name='Item_ID' style='width: 100%;' value='<?php echo $row['Product_Name']; ?>' readonly='readonly' onchange="getPrice('<?php echo $Item_ID; ?>')" onclick="getPrice('<?php echo $Item_ID; ?>')" required='required'>
		    </td>
		    <td>
			<select id='Type_Of_Check_In_<?php echo $Item_ID; ?>' style='width: 100%;' name='Type_Of_Check_In_<?php echo $Item_ID; ?>' onchange="getDoctor('<?php echo $Item_ID; ?>');getSubdepartment('<?php echo $Item_ID; ?>')" readonly='readonly'>
			    <option></option>
			    <option>Radiology</option>
			    <option>Dialysis</option>
			    <option>Physiotherapy</option>
			    <option>Optical</option> 
			    <option>Doctor Room</option>
			    <option>Dressing</option>
			    <option>Matenity</option>
			    <option>Cecap</option>
			    <option>Laboratory</option>
			    <option>Pharmacy</option>
			    <option>Theater</option>
			    <option>Dental</option>
			    <option>Ear</option>
			</select>
		    </td>
		    <td>
			<select id='Patient_Direction_<?php echo $Item_ID; ?>' name='Patient_Direction_<?php echo $Item_ID; ?>' onchange="getDoctor('<?php echo $Item_ID; ?>');getSubdepartment('<?php echo $Item_ID; ?>')" style='width: 100%;' required='required'>
			    <option></option>
			    <option>Direct To Doctor</option>
			    <option>Direct To Doctor Via Nurse Station</option>
			    <option>Direct To Clinic</option>
			    <option>Direct To Clinic Via Nurse Station</option>
			</select>
		    </td>
		    <td>
			<select name='Consultant_<?php echo $Item_ID; ?>' id='Consultant_<?php echo $Item_ID; ?>' style='width: 100%;' value='<?php echo $Guarantor_Name; ?>'>
			    <?php
			    $Select_Consultant = "select * from tbl_clinic"; 
			    $Select_Consultant_Result = mysqli_query($conn,$Select_Consultant);
			    ?>
			    <?php
			    while($row2 = mysqli_fetch_array($Select_Consultant_Result)){
				?>
				<option><?php echo $row2['Clinic_Name']; ?></option>
			    <?php
			    }
			    $Select_Doctors = "select * from tbl_employee where employee_type = 'Doctor'"; 
			    $Select_Doctors_Result = mysqli_query($conn,$Select_Doctors);
			    ?> 
			    <?php
			    while($row3 = mysqli_fetch_array($Select_Doctors_Result)){
				?>
				<option><?php echo $row3['Employee_Name']; ?></option>
			    <?php
			    }
			    ?>
			</select>
		    </td>
<!--		    <td>
			<select name='Sub_Department_ID_<?php echo $Item_ID; ?>' id='Sub_Department_ID_<?php echo $Item_ID; ?>' style='width: 100%' value='<?php echo $Guarantor_Name; ?>'>
			<option></option>
			</select>
		    </td>-->
		    <td><input type='text' name='Discount_<?php echo $Item_ID; ?>' value='0' style='width: 100%' id='Discount_<?php echo $Item_ID; ?>' size=11 style='text-align: right;'></td>
		    <td><input type='text' name='price_<?php echo $Item_ID; ?>' style='width: 100%' id='price_<?php echo $Item_ID; ?>' value='<?php
			if(isset($_GET['bill_type'])){
			 $Billing_Type = $_GET['bill_type'];   
			}else{
			   $Billing_Type = '';
			}
			if($Billing_Type=='Outpatient Credit'||$Billing_Type=='Inpatient Credit'){
				if(strtolower($Guarantor_Name)=='nhif'){
				echo $row['Selling_Price_NHIF'];
				}else{
				   echo $row['Selling_Price_Credit'];
				}
			}elseif($Billing_Type=='Outpatient Cash'||$Billing_Type=='Inpatient Cash'){
				echo $row['Selling_Price_Cash'];
			}
		    ?>' size=11 style='text-align: right;' readonly></td>
		    <td><input type='text' id='balance_<?php echo $Item_ID; ?>' style='width: 100%' size=10 name='balance_<?php echo $Item_ID; ?>' readonly='readonly' value='1'></td>
		    <td><input type='text' id='quantity_<?php echo $Item_ID; ?>' style='width: 100%' name='quantity_<?php echo $Item_ID; ?>' value='1' size=15 onchange="getPrice('<?php echo $row['Item_ID']; ?>')" onkeyup="getPrice('<?php echo $row['Item_ID']; ?>')" style='text-align: right;' required='required'></td>
		    <td><input type='text' name='ammount_<?php echo $Item_ID; ?>' id='ammount_<?php echo $Item_ID; ?>' style='width: 100%' size=15 style='text-align: right;' readonly></td>
                </tr>    
		<?php
			    $i++;
		}
			    ?>
	    </table>