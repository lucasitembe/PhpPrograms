<link rel="stylesheet" href="table.css" media="screen"> 
<?php
    session_start();
    include("./includes/connection.php");
    if(isset($_GET['Consultation_Type'])){
	$Consultation_Type = $_GET['Consultation_Type'];
	
	if($Consultation_Type=='Sugery'){
	    $Consultation_Type = 'Theater';
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
    if(isset($_GET['test_name'])){
	$test_name = $_GET['test_name'];
    }else{
	$test_name = "";
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

<!-- GET VARIABLES FROM doctoritemselect.php -->
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
	<script type='text/javascript'>
	    function getPrice(Product_Name) {
		var bill_type = document.getElementById("bill_type_"+Product_Name+"").value;
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
			mm2.onreadystatechange= function (){
						    var data4 = mm2.responseText;
						    document.getElementById('price_'+Product_Name+'').value = data4;
						    var price = document.getElementById('price_'+Product_Name+'').value;
						    var quantity = document.getElementById('quantity_'+Product_Name+'').value;
						    var ammount = 0;
						    
						    ammount = price*quantity;
						    document.getElementById('ammount_'+Product_Name+'').value = ammount;
						}; //specify name of function that will handle server response....
			mm2.open('GET','Get_Item_price.php?Product_Name='+Product_Name+'&Billing_Type='+Billing_Type+'&Guarantor_Name='+Guarantor_Name,true);
			mm2.send();
		}
		}
	</script>
	<script type="text/javascript" language="javascript">
	    function getLocationQueSize(Item_ID) {
		    if(window.XMLHttpRequest) {
			mm5 = new XMLHttpRequest();
		    }
		    else if(window.ActiveXObject){ 
			mm5 = new ActiveXObject('Micrsoft.XMLHTTP');
			mm5.overrideMimeType('text/xml');
		    }
		    
		    var Sub_Department_ID = document.getElementById('Sub_Department_ID_'+Item_ID).value;
		       mm5.onreadystatechange = function (){
		        var data5 = mm5.responseText;
		        document.getElementById('queue_'+Item_ID).value = data5;
		    }; //specify name of function that will handle server response....
		        mm5.open('GET','getLocationQueSize.php?Sub_Department_ID='+Sub_Department_ID<?php
			if($Consultation_Type!='Pharmacy'){
			?>+'&Item_ID='+Item_ID<?php } ?>,true);
		        mm5.send();
	    }
	</script>
	<script type='text/javascript'>
	    function sendOrRemove(Item_ID,check_ID) {
		var bill_type = document.getElementById("bill_type_"+Item_ID+"").value;
		var action;
		var Registration_ID = "<?php echo $_GET['Registration_ID']; ?>";
		var Patient_Payment_ID = "<?php echo $_GET['Patient_Payment_ID']; ?>";
		var Sub_Department_ID = document.getElementById("Sub_Department_ID_"+Item_ID+"").value;
		var quantity = document.getElementById('quantity_'+Item_ID+'').value;
		var comments = document.getElementById('comments_'+Item_ID).value;
		if (check_ID.checked==true){
		    action = "ADD";
		}else{
		    action = "REMOVE";
		}
		if(Item_ID!='') {
		    var Guarantor_Name = '<?php echo $Guarantor_Name; ?>';
		    if(window.XMLHttpRequest) {
			myObject = new XMLHttpRequest();
		    }
		    else if(window.ActiveXObject){ 
			myObject = new ActiveXObject('Micrsoft.XMLHTTP');
			myObject.overrideMimeType('text/xml');
		    }
		if (action=='REMOVE') {
			myObject.onreadystatechange= sendOrRemove_AJAX; //specify name of function that will handle server response....
			myObject.open('GET','sendOrRemove.php?action='+action+'&Consultation_Type=<?php echo $_GET['Consultation_Type'];?>&Sub_Department_ID='+Sub_Department_ID+'&consultation_id=<?php echo $consultation_id; ?>&Sponsor_ID=<?php echo $Sponsor_ID; ?>&Item_ID='+Item_ID+'&bill_type='+bill_type+'&Guarantor_Name='+Guarantor_Name+'&Registration_ID='+Registration_ID,true);
			myObject.send();
		}else{
		    if (bill_type!='' && Sub_Department_ID!='') {
			myObject.onreadystatechange= sendOrRemove_AJAX; //specify name of function that will handle server response....
			myObject.open('GET','sendOrRemove.php?action='+action+'&quantity='+quantity+'&Patient_Payment_ID='+Patient_Payment_ID+'&Consultation_Type=<?php echo $_GET['Consultation_Type'];?>&Sub_Department_ID='+Sub_Department_ID+'&consultation_id=<?php echo $consultation_id; ?>&Sponsor_ID=<?php echo $Sponsor_ID; ?>&Item_ID='+Item_ID+'&bill_type='+bill_type+'&Guarantor_Name='+Guarantor_Name+'&Registration_ID='+Registration_ID+'&comments='+comments,true);
			myObject.send();
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
	    }
	</script>
	<script type="text/javascript" language="javascript">
	    function checkNonSupportedItem(Item_ID) {
		var bill_type = document.getElementById("bill_type_"+Item_ID+"").value;
		if (bill_type=='Credit') {
			var Sponsor_name = '<?php echo $Guarantor_Name; ?>';
			if(window.XMLHttpRequest) {
			    supportObject = new XMLHttpRequest();
			}
			else if(window.ActiveXObject){ 
			    supportObject = new ActiveXObject('Micrsoft.XMLHTTP');
			    supportObject.overrideMimeType('text/xml');
			}
			   supportObject.onreadystatechange = function (){
								    var supportResult = supportObject.responseText;
								    //check if item is supported
								    if (supportObject.readyState==4) {
									if (supportResult=='not supported') {
									    var choice = confirm("This Item Is Not Supported By "+Sponsor_name+"\n Do You Want To Proceed ?");
									    if (choice) {
									    }else{
										document.getElementById("bill_type_"+Item_ID+"").innerHTML = "<option></option><option>Cash</option><option>Credit</option>"
									    }
									}
								    }
								}; //specify name of function that will handle server response....
								
			    supportObject.open('GET','checkNonSupportedItem.php?Item_ID='+Item_ID+"&Sponsor_name="+Sponsor_name,true);
			    supportObject.send();
		}
	    }
	</script>
	    <table width='100%' id='thead'>
		<tr id='thead'>
                    <td width='5%'><b>Action</b></td>
                    <td width='5%'><b>SN</b></td>
                    <td><b>Item</b></td>
                    <td width='10%'><b>Bill Type</b></td>
                    <td><b>Location</b></td>
                    <td width='5%'><b>Status</b></td>
                    <td width='5%'><b>Queue</b></td>
                    <td width='5%'><b>Quantity</b></td>
                </tr>
		<tr>
		    <td colspan=8>
			<hr>
		    </td>
		</tr>
                <?php
                $i = 1;
                    $qr = "SELECT * FROM tbl_items where $Consultation_Condition2 ";
		    if($Item_Subcategory_ID=='All'||$Item_Subcategory_ID==0){
			if($Item_Category_ID =='All'){
			 $category_condition = "";
			}else{
			    $category_condition = " AND Item_Subcategory_ID IN (
			    SELECT DISTINCT ics77.Item_Subcategory_ID FROM tbl_item_subcategory ics77,tbl_item_category ic77
			    WHERE ic77.Item_Category_ID = $Item_Category_ID AND ic77.Item_Category_ID = ics77.Item_Category_ID )";
			}
		    }else{
			$category_condition = " AND Item_Subcategory_ID = $Item_Subcategory_ID";
		    }
		    $qr.=$category_condition;
		    $qr.=" AND Product_Name LIKE '%$test_name%' ";
		$result = mysqli_query($conn,$qr);
                while($row = @mysqli_fetch_assoc($result)){
                    ?>
                <tr>
                    <td style='text-align: center;vertical-align: middle;' width='5%' rowspan=2><input type='checkbox'<?php
		    $qr_check_if_selected = "SELECT Item_ID,ilc.status FROM tbl_payment_cache pc,tbl_item_list_cache ilc
					    WHERE ilc.Payment_Cache_ID=pc.Payment_Cache_ID AND ilc.Item_ID=".$row['Item_ID']."
					    AND pc.consultation_id =".$consultation_id." LIMIT 1";
		    $qr_check_result = mysqli_query($conn,$qr_check_if_selected);
		    if(mysqli_num_rows($qr_check_result)>0){
			if(mysqli_fetch_assoc($qr_check_result)['status']=='paid'){
			?>
			disabled='disabled'
			<?php
			}
			?>
			checked='checked'
			<?php
		    }
		    ?> value='Yes' onclick="sendOrRemove('<?php echo $row['Item_ID']; ?>',this)"></td>
		    <td style='text-align: center;vertical-align: middle;color: black'  rowspan=2><?php echo $i;?></td>
                    <td>
			<input type='text' id='Item_ID' name='Item_ID' style='width: 100%;' value='<?php echo $row['Product_Name']; ?>' readonly='readonly' onchange="getPrice('<?php echo $row['Item_ID']; ?>')" onclick="getPrice('<?php echo $row['Item_ID']; ?>')" required='required'>
		    </td>
		    <td><select style='width: 100%;' id='bill_type_<?php echo $row['Item_ID']; ?>' name='bill_type_<?php echo $row['Item_ID']; ?>' onclick="getPrice(<?php echo $row['Item_ID']; ?>)" onchange="getPrice('<?php echo $row['Item_ID']; ?>');checkNonSupportedItem('<?php echo $row['Item_ID']; ?>');" required='required'>
			    <option></option>
			    <option>Cash</option>
			    <?php
			    if(strtolower($Guarantor_Name)!='cash'){
				?><option>Credit</option><?php
			    }
			    ?>
			</select>
		    </td>
		    <td>
			<select name='Sub_Department_ID_<?php echo $row['Item_ID']; ?>' style='width: 100%;' id='Sub_Department_ID_<?php echo $row['Item_ID']; ?>' required='required' onchange="getLocationQueSize('<?php echo $row['Item_ID']; ?>')">
			    <option></option>
			    <?php
			    $qr = "SELECT * FROM tbl_department d,tbl_sub_department s
			    where
			    d.Department_ID = s.Department_ID and
			    $Consultation_Condition";
			    $result2 = mysqli_query($conn,$qr);
			    while($row2 = mysqli_fetch_assoc($result2)){
				?>
				<option value='<?php echo $row2['Sub_Department_ID']; ?>'>
				    <?php echo $row2['Sub_Department_Name'];?>
				</option>
				<?php
			    }
			    ?>
			</select>
		    </td>
		    <td><input type='text' id='balance_<?php echo $row['Item_ID']; ?>' size=10 name='balance_<?php echo $row['Item_ID']; ?>' readonly='readonly' value='Available'></td>
		    <td><input type='text' id='queue_<?php echo $row['Item_ID']; ?>' name='queue_<?php echo $row['Item_ID']; ?>' size=10 readonly='readonly'></td>
		    <td width='5%'><input type='text' id='quantity_<?php echo $row['Item_ID']; ?>' name='quantity_<?php echo $row['Item_ID']; ?>' value='1' size=15 onchange="getPrice('<?php echo $row['Item_ID']; ?>')" onkeyup="getPrice('<?php echo $row['Item_ID']; ?>')" style='text-align: right;' required='required'></td>
                </tr>
                <tr>
		    <td colspan=6>
		<table>
		    <tr>
			<td>Comments</td>
			<td width='70%'><input style='width: 90%' type='text' id='comments_<?php echo $row['Item_ID']; ?>' name='comments_<?php echo $row['Item_ID']; ?>'></td>
			<td style='width: 8%;text-align: right;'>
			    Price
			</td>
			<td width='5%'><input type='text' name='price_<?php echo $row['Item_ID']; ?>' id='price_<?php echo $row['Item_ID']; ?>' size=11 style='text-align: right;' readonly></td>
			<td width='7%' style='text-align: right'>Amount</td><td width='5%'><input type='text' name='ammount_<?php echo $row['Item_ID']; ?>' id='ammount_<?php echo $row['Item_ID']; ?>' size=15 style='text-align: right;' readonly></td>
		    </tr>
		</table>
                    </td>
		</tr>
		<tr>
		    <td colspan=8>
			<hr>
		    </td>
		</tr>
                <?php
			    $i++;
                            }
			    ?>
	    </table>