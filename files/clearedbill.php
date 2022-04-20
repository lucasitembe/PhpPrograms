<?php
	include("./includes/header.php");
    include("./includes/connection.php");
    
    if(!isset($_SESSION['userinfo'])){
		@session_destroy();
		header("Location: ../index.php?InvalidPrivilege=yes");
    }

    if(isset($_SESSION['userinfo'])){
		if(isset($_SESSION['userinfo']['Patients_Billing_Works'])){
		    if($_SESSION['userinfo']['Patients_Billing_Works'] != 'yes'){
			header("Location: ./index.php?InvalidPrivilege=yes");
		    }
		}else{
		    header("Location: ./index.php?InvalidPrivilege=yes");
		}
    }else{
		@session_destroy();
	    header("Location: ../index.php?InvalidPrivilege=yes");
    }

	if(isset($_GET['Registration_ID'])){
		$Registration_ID = $_GET['Registration_ID'];
	}else{
		$Registration_ID = '';
	}

	if(isset($_GET['Prepaid_ID'])){
		$Prepaid_ID = $_GET['Prepaid_ID'];
	}else{
		$Prepaid_ID = 0;
	}

	$Today_Date = mysqli_query($conn,"select now() as today") or die(mysqli_error($conn));
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
    }
	echo "<a href='prepaidclearedbills.php?PrePaidClearedBills=PrePaidClearedBillsThisPage' class='art-button-green'>BACK</a>";
?>
<br/><br/>

<style>
    table,tr,td{
        border-collapse:collapse !important;
        border:none !important;
    }
    tr:hover{
        background-color:#eeeeee;
        cursor:pointer;
    }
 </style> 
<?php
	$select = mysqli_query($conn,"select pr.Patient_Name, pr.Registration_ID, pr.Gender, pr.Date_Of_Birth, pr.Member_Number, sp.Guarantor_Name, sp.Sponsor_ID, Patient_Bill_ID, pd.Status, emp.Employee_Name, pd.Cleared_Date_Time
							from tbl_patient_registration pr ,tbl_sponsor sp, tbl_prepaid_details pd, tbl_employee emp where
							pd.Registration_ID = pr.Registration_ID and
							emp.Employee_ID = pd.Cleared_By and
							pr.Sponsor_ID = sp.Sponsor_ID and
							pd.Prepaid_ID = '$Prepaid_ID' and
							pr.Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
	
	$num = mysqli_num_rows($select);
	if($num > 0){
		while ($data = mysqli_fetch_array($select)) {
			$Patient_Name = $data['Patient_Name'];
			$Registration_ID = $data['Registration_ID'];
			$Gender = $data['Gender'];
			$Date_Of_Birth = $data['Date_Of_Birth'];
			$Member_Number = $data['Member_Number'];
			$Guarantor_Name = $data['Guarantor_Name'];
			$Sponsor_ID = $data['Sponsor_ID'];
			$Patient_Bill_ID = $data['Patient_Bill_ID'];
			$Status = ucwords(strtolower($data['Status']));
			$Employee_Approved = $data['Employee_Name'];
			$Cleared_Date_Time = $data['Cleared_Date_Time'];
			//calculate age
			$date1 = new DateTime($Today);
			$date2 = new DateTime($Date_Of_Birth);
			$diff = $date1 -> diff($date2);
			$age = $diff->y." Years, ";
			$age .= $diff->m." Months, ";
			$age .= $diff->d." Days";
		}
	}else{
		$Patient_Name = '';
		$Registration_ID = '';
		$Gender = '';
		$Date_Of_Birth = '';
		$Member_Number = '';
		$Guarantor_Name = '';
		$Sponsor_ID = '';
		$age = '';
		$Patient_Bill_ID  = 0;
		$Status = 'Pending';
		$Employee_Approved = '';
		$Cleared_Date_Time = '';
	}
?>

<fieldset>
	<table width="100%">
		<tr>
			<td width="10%" style="text-align: right;"><b>Patient Name</b></td>
			<td><input type="text" value="<?php echo ucwords(strtolower($Patient_Name)); ?>" readonly="readonly"></td>
			<td width="10%" style="text-align: right;"><b>Patient Number</b></td>
			<td><input type="text" value="<?php echo $Registration_ID; ?>" readonly="readonly"></td>
			<td width="13%" style="text-align: right;"><b>Sponsor Name</b></td>
			<td><input type="text" value="<?php echo strtoupper($Guarantor_Name); ?>" readonly="readonly"></td>
			<td style="text-align: right;"><b>Gender</b></td>
			<td><input type="text" value="<?php echo $Gender; ?>" readonly="readonly"></td>
		</tr>
		<tr>
			<td style="text-align: right;"><b>Patient Age</b></td>
			<td><input type="text" value="<?php echo $age; ?>" readonly="readonly"></td>
			<td style="text-align: right;"><b>Member Number</b></td>
			<td><input type="text" value="<?php echo $Member_Number; ?>" readonly="readonly"></td>
			<td style="text-align: right;"><b>Approved By</b></td>
			<td><input type="text" value="<?php echo $Employee_Approved; ?>" readonly="readonly"></td>
			<td style="text-align: right;"><b>Approved Date</b></td>
			<td><input type="text" value="<?php echo $Cleared_Date_Time; ?>" readonly="readonly"></td>
		</tr>
	</table>
</fieldset>

<?php
?>
<fieldset>
<table width="100%">
	<tr>
		<td width="70%">
			<fieldset style='overflow-y: scroll; height: 400px; background-color: white;' id='Transaction_Items_Details'>
				<legend>PRE / POST PAID  BILL DETAILS</legend>
			<?php
				$Grand_Total = 0;
				//get categories
				$get_cat = mysqli_query($conn,"select ic.Item_category_ID, ic.Item_Category_Name from 
											tbl_items i, tbl_item_subcategory isc, tbl_item_category ic, tbl_patient_payments pp, tbl_patient_payment_item_list ppl where
											ic.Item_Category_ID = isc.Item_Category_ID and
											isc.Item_Subcategory_ID = i.Item_Subcategory_ID and
											i.Item_ID = ppl.Item_ID and
											pp.Transaction_type = 'indirect cash' and
											pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
											pp.Billing_Type = 'Outpatient Cash' and
											pp.Patient_Bill_ID = '$Patient_Bill_ID' and
											pp.Transaction_status <> 'cancelled' and
											pp.Registration_ID = '$Registration_ID' and
											pp.Pre_Paid = 1 group by ic.Item_Category_ID") or die(mysqli_error($conn));
				$num = mysqli_num_rows($get_cat);
				if($num > 0){
					$temp_cat = 0;
					while ($row = mysqli_fetch_array($get_cat)) {
						$Item_category_ID = $row['Item_category_ID'];
						echo "<table width='100%'>";
						echo "<tr><td colspan='7'><b>".++$temp_cat.'. '.strtoupper($row['Item_Category_Name'])."</b></td></tr>";

					?>
						<tr>
							<td width="4%">SN</td>
                            <td>ITEM NAME</td>
							<td width="10%" style="text-align: center;">TRANSACTION#</td>
							<td width="10%" style="text-align: right;">PRICE</td>
							<td width="10%" style="text-align: right;">DISCOUNT</td>
							<td width="10%" style="text-align: right;">QUANTITY</td>
							<td width="10%" style="text-align: right;">SUB TOTAL</td>
						</tr>
						<!-- <tr><td colspan='7'><hr></td></tr> -->
					<?php
						$items = mysqli_query($conn,"select i.Product_Name, ppl.Price, ppl.Quantity, ppl.Discount, ppl.Patient_Payment_Item_List_ID, ic.Item_Category_Name, pp.Patient_Payment_ID, pp.Payment_Date_And_Time from 
											tbl_items i, tbl_item_subcategory isc, tbl_item_category ic, tbl_patient_payments pp, tbl_patient_payment_item_list ppl where
											ic.Item_Category_ID = isc.Item_Category_ID and
											isc.Item_Subcategory_ID = i.Item_Subcategory_ID and
											i.Item_ID = ppl.Item_ID and
											pp.Transaction_type = 'indirect cash' and
											pp.Billing_Type = 'Outpatient Cash' and
											pp.Transaction_status <> 'cancelled' and
											pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
											pp.Patient_Bill_ID = '$Patient_Bill_ID' and
											ic.Item_category_ID = '$Item_category_ID' and
											pp.Registration_ID = '$Registration_ID' and
											pp.Pre_Paid = 1 ") or die(mysqli_error($conn));

						$nm = mysqli_num_rows($items);
						if($nm > 0){
							$temp = 0;
							$Sub_Total = 0;
							while ($dt = mysqli_fetch_array($items)) {
								echo '<tr><td width="4%">'.++$temp.'<b>.</b></td>';
								echo '<td><label for="'.$dt['Patient_Payment_Item_List_ID'].'" style="display:block">'.ucwords(strtolower($dt['Product_Name'])).'</label></td>
										<td width="10%" style="text-align: center"><label style="color: #0079AE;" onclick="View_Details('.$dt['Patient_Payment_ID'].','.$dt['Patient_Payment_Item_List_ID'].');"><b>'.$dt['Patient_Payment_ID'].'</b></label></td>
								        <td width="10%" style="text-align: right">'.number_format($dt['Price']).'</td>
										<td width="10%" style="text-align: right;">'.number_format($dt['Discount']).'</td>
										<td width="10%" style="text-align: right;">'.$dt['Quantity'].'</td>
										<td width="10%" style="text-align: right;">'.number_format(($dt['Price'] - $dt['Discount']) * $dt['Quantity']).'</td></tr>';
								$Sub_Total += (($dt['Price'] - $dt['Discount']) * $dt['Quantity']);
								$Grand_Total += (($dt['Price'] - $dt['Discount']) * $dt['Quantity']);
							}
							echo "<tr><td colspan='7'><hr></td></tr>";
							echo "<tr><td colspan='6' style='text-align: right;'><b>SUB TOTAL</b></td><td style='text-align: right;'><b>".number_format($Sub_Total)."</b></td></tr>";
						}
						echo "</table>";
					}
				}
			?>
			</fieldset>
		</td>
		<td id='Transaction_Summary_Area'>
			<fieldset style='overflow-y: scroll; height: 280px; background-color: white;'>
				<legend>PRE / POST PAID  BILL SUMMARY
				</legend>

				<table width="100%">
					<tr><td><b>CATEGORY</b></td><td style="text-align: right;"><b>TOTAL</b>&nbsp;&nbsp;&nbsp;</td></tr>
				<?php
					$get_cate = mysqli_query($conn,"select ic.Item_Category_ID, ic.Item_Category_Name from 
												tbl_items i, tbl_item_subcategory isc, tbl_item_category ic, tbl_patient_payments pp, tbl_patient_payment_item_list ppl where
												ic.Item_Category_ID = isc.Item_Category_ID and
												isc.Item_Subcategory_ID = i.Item_Subcategory_ID and
												i.Item_ID = ppl.Item_ID and
												pp.Transaction_type = 'indirect cash' and
												pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
												pp.Billing_Type = 'Outpatient Cash' and
												pp.Patient_Bill_ID = '$Patient_Bill_ID' and
												pp.Transaction_status <> 'cancelled' and
												pp.Registration_ID = '$Registration_ID' and
												pp.Pre_Paid = 1 group by ic.Item_Category_ID") or die(mysqli_error($conn));

					$nms_slct = mysqli_num_rows($get_cate);
					$tmp = 0;
					if($nms_slct > 0){
						$cont = 0;
						while ($dts = mysqli_fetch_array($get_cate)) {
							$Item_Category_Name = $dts['Item_Category_Name'];
							$Item_Category_ID = $dts['Item_Category_ID'];
							$Category_Grand_Total = 0;

							//calculate total
							$items = mysqli_query($conn,"select ppl.Price, ppl.Quantity, ppl.Discount from 
												tbl_items i, tbl_item_subcategory isc, tbl_item_category ic, tbl_patient_payments pp, tbl_patient_payment_item_list ppl where
												ic.Item_Category_ID = isc.Item_Category_ID and
												isc.Item_Subcategory_ID = i.Item_Subcategory_ID and
												i.Item_ID = ppl.Item_ID and
												pp.Transaction_type = 'indirect cash' and
												pp.Billing_Type = 'Outpatient Cash' and
												pp.Transaction_status <> 'cancelled' and
												pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
												pp.Patient_Bill_ID = '$Patient_Bill_ID' and
												ic.Item_Category_ID = '$Item_Category_ID' and
												pp.Registration_ID = '$Registration_ID' and
												pp.Pre_Paid = 1") or die(mysqli_error($conn));

							$nums = mysqli_num_rows($items);
							if($nums > 0){
								while ($t_item = mysqli_fetch_array($items)) {
									$Category_Grand_Total += (($t_item['Price'] - $t_item['Discount']) * $t_item['Quantity']);
								}
							}
							echo "<tr><td>".++$cont.'<b>. </b>'.ucwords(strtolower($Item_Category_Name))."</td><td style='text-align: right;'>".number_format($Category_Grand_Total)."&nbsp;&nbsp;&nbsp;</td></tr>";
						}
					}
				?>
					<tr><td colspan="2"><hr></td></tr>
					<tr>
						<td><b>Bill Status</b></td>
						<td style='text-align: right;'><?php echo ucwords(strtolower($Status)); ?>&nbsp;&nbsp;&nbsp;</td>
					</tr>
					<tr>
						<td width="65%"><b>Total Amount Required </b></td><td style="text-align: right;"><?php echo number_format($Grand_Total); ?>&nbsp;&nbsp;&nbsp;</td>
					</tr>
					<tr>
						<td width="65%"><b>Advance Payments</b></td>
						<td style="text-align: right;">
						<?php 
							$Grand_Total_Direct_Cash = 0;
							//calculate cash payments
							$cal = mysqli_query($conn,"select ppl.Price, ppl.Quantity, ppl.Discount from 
												tbl_patient_payments pp, tbl_patient_payment_item_list ppl where
												pp.Transaction_type = 'direct cash' and
												pp.Transaction_status <> 'cancelled' and
												pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
												pp.Patient_Bill_ID = '$Patient_Bill_ID' and
												pp.Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
							$nms = mysqli_num_rows($cal);
							if($nms > 0){
								while ($cls = mysqli_fetch_array($cal)) {
									$Grand_Total_Direct_Cash += (($cls['Price'] - $cls['Discount']) * $cls['Quantity']);
								}
							}
							echo number_format($Grand_Total_Direct_Cash);
							$Temp_Balance = ($Grand_Total - $Grand_Total_Direct_Cash);
						?>&nbsp;&nbsp;&nbsp;
						</td>
					</tr>
					<tr><td colspan="2"><hr></td></tr>
					<tr>
						<td><b>Balance </b></td>
						<td style="text-align: right;">
							<?php
								if($Temp_Balance > 0){
									echo number_format($Temp_Balance);
								}else{
									echo 0;									
								}
							?>&nbsp;&nbsp;&nbsp;
						</td>
					</tr>
					<?php
						if($Temp_Balance < 0){
					?>
					<tr><td><b>Refund Amount</b></td><td style="text-align: right;"><?php echo number_format(substr($Temp_Balance, 1)); ?>&nbsp;&nbsp;&nbsp;</td></tr>
					<?php
						}
					?>
					<tr><td colspan="2"><hr></td></tr>
				</table>
			</fieldset><br/>
			<table width="100%">
				<tr>
					<td>
						<input type="button" name="Filter" id="Filter" value="PREVIEW BILL" class="art-button-green" onclick="Preview_Patient_Bill(<?php echo $Patient_Bill_ID; ?>,<?php echo $Registration_ID; ?>)">
					</td>
					<td>
						<?php
							if(strtolower($Status) == 'pending' && $Temp_Balance <= 0){
						?>
								<input type="button" name="Approval_Button" id="Approval_Button" class="art-button-green" value="APPROVE BILL" onclick="Approve_Patient_Bill(<?php echo $Patient_Bill_ID; ?>,<?php echo $Prepaid_ID; ?>,<?php echo $Registration_ID; ?>)">
						<?php
							}else if(strtolower($Status) == 'cleared'){

							}else if($Temp_Balance > 0){
						?>
								<input type="button" name="Approval_Button" id="Approval_Button" class="art-button-green" value="APPROVE BILL" onclick="Approve_Patient_Bill_Warning2()">
						<?php
							}else{
						?>
								<input type="button" name="Approval_Button" id="Approval_Button" class="art-button-green" value="APPROVE BILL" onclick="Approve_Patient_Bill_Warning()">
						<?php
							}
						?>
					</td>
				</tr>
				<tr><td colspan="2"><hr></td></tr>
				<tr>
					<td>
						<input type="button" name="Preview_Direct_Cash" id="Preview_Direct_Cash" value="PREVIEW ADVANCE PAYMENTS" class="art-button-green" onclick="Preview_Advance_Payments(<?php echo $Patient_Bill_ID; ?>,<?php echo $Registration_ID; ?>);">
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</fieldset>
<div id="Get_Patient_Details" style="width:50%;">
    
</div>

<div id="Add_Items">
	<center id="Details_Area">
		
	</center>	
</div>

<div id="Preview_Transaction_Details" style="width:50%;">
    
</div>
<div id="MessageAlert">
	Advance Payments Not Applicable For Credit Bills
</div>

<div id="Preview_Advance">

</div>

<div id="Approval_Warning_Message">

</div>


<div id="Patient_Already_Cleared">
	Selected Bill already cleared.
</div>

<div id="Zero_Price_Alert">
	<center>Process fail!. Selected item missing price</center>
</div>

<div id="No_Items_Found">
	<center>Process fail!. No items found</center>
</div>

<div id="Zero_Price_Or_Quantity_Alert">
	<center>Process fail!. Some items missing Price or Quantity.</center>
</div>

<div id="Something_Wrong">
	<center>Process fail!. Please try again</center>
</div>

<div id="Unsuccessful_Dialogy">
	<center>Process Fail! Please try again</center>
</div>

<div id="Successful_Dialogy">
	<center>Selected items added successfully</center>
</div>

<div id="Verify_Remove_Item" style="width:25%;">
    <span id="Remove_Selected_Area">
        
    </span>
</div>

<div id="Editing_Transaction" style="width:25%;">
	<span id="Edit_Area">
		
	</span>
</div>


<div id="List_OF_Doctors">
	<center>
		<table width="100%">
			<tr>
				<td>
					<input type="text" name="Doc_Name" id="Doc_Name" placeholder="~~~ ~~~ Enter Doctor Name ~~~ ~~~" autocomplete="off" style="text-align: center;" onkeyup="Search_Doctors()" oninput="Search_Doctors()">
				</td>
			</tr>
			<tr>
				<td>
					<fieldset style='overflow-y: scroll; height: 200px; background-color: white;' id='Doctors_Area'>
						<table width="100%">
						<?php
							$counter = 0;
							$get_doctors = mysqli_query($conn,"select Employee_ID, Employee_Name from tbl_employee where Employee_Type = 'Doctor' and Account_Status = 'active' order by Employee_Name limit 100") or die(mysqli_error($conn));
							$doctors_num = mysqli_num_rows($get_doctors);
							if($doctors_num > 0){
								while ($data = mysqli_fetch_array($get_doctors)) {
						?>
								<tr>
									<td style='text-align: right;'>
										<label onclick="Get_Selected_Doctor('<?php echo $data['Employee_Name']; ?>')"><?php echo ++$counter; ?></label>
									</td>
									<td>
										<label onclick="Get_Selected_Doctor('<?php echo $data['Employee_Name']; ?>')">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo strtoupper($data['Employee_Name']); ?></label>
									</td>
								</tr>
						<?php
								}
							}
						?>
						</table>
					</fieldset>
				</td>
			</tr>
		</table>
	</center>
</div>
<div id="Change_Item" style="width:50%;" >
    <center id='Edit_Items_Area'>
        <table width=100% style='border-style: none;'>
            <tr>
                <td>

                </td>
            </tr>
        </table>
    </center>
</div>

<div id="No_Enough_Payments">
	<center>
		Malipo zaidi yanahitajika
	</center>
</div>

<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>
<script src="script.js"></script>
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
<script src="script.responsive.js"></script>
<link rel="stylesheet" href="css/dialog/zebra_dialog.css" media="screen">
<script src="js/zebra_dialog.js"></script>
<script src="js/ehms_zebra_dialog.js"></script>
<script>
   $(document).ready(function(){
      $("#Get_Patient_Details").dialog({ autoOpen: false, width:"90%",height:630, title:'INPATIENT BILLING DETAILS',modal: true});
   });
</script>

<script>
   $(document).ready(function(){
      $("#Preview_Transaction_Details").dialog({ autoOpen: false, width:"80%",height:500, title:'TRANSACTION DETAILS',modal: true});
   });
</script>

<script>
   $(document).ready(function(){
      $("#MessageAlert").dialog({ autoOpen: false, width:600,height:110, title:'eHMS 2.0 ~ Alert Message',modal: true});
   });
</script>

<script>
   $(document).ready(function(){
      $("#Preview_Advance").dialog({ autoOpen: false, width:"70%",height:450, title:'ADVANCE PAYMENTS',modal: true});
   });
</script>

<script>
   $(document).ready(function(){
      $("#Approval_Warning_Message").dialog({ autoOpen: false, width:"70%",height:450, title:'eHMS 2.0 ~ Alert Message',modal: true});
   });
</script>

<script>
   $(document).ready(function(){
      $("#Not_Ready_To_Bill").dialog({ autoOpen: false, width:"40%",height:110, title:'eHMS 2.0 ~ Alert Message',modal: true});
   });
</script>
<script>
   $(document).ready(function(){
      $("#Zero_Price_Alert").dialog({ autoOpen: false, width:"40%",height:110, title:'eHMS 2.0 ~ Alert Message',modal: true});
   });
</script>
<script>
   $(document).ready(function(){
      $("#No_Items_Found").dialog({ autoOpen: false, width:"40%",height:110, title:'eHMS 2.0 ~ Alert Message',modal: true});
   });
</script>
<script>
   $(document).ready(function(){
      $("#Something_Wrong").dialog({ autoOpen: false, width:"40%",height:110, title:'eHMS 2.0 ~ Alert Message',modal: true});
   });
</script>
<script>
   $(document).ready(function(){
      $("#Zero_Price_Or_Quantity_Alert").dialog({ autoOpen: false, width:"40%",height:110, title:'eHMS 2.0 ~ Alert Message',modal: true});
   });
</script>

<script>
   $(document).ready(function(){
      $("#Patient_Already_Cleared").dialog({ autoOpen: false, width:"40%",height:110, title:'eHMS 2.0 ~ Alert Message',modal: true});
   });
</script>

<script>
   $(document).ready(function(){
      $("#Refund_Required").dialog({ autoOpen: false, width:"40%",height:110, title:'eHMS 2.0 ~ Alert Message',modal: true});
   });
</script>

<script>
   $(document).ready(function(){
      $("#No_Enough_Payments").dialog({ autoOpen: false, width:"35%",height:130, title:'eHMS 2.0 ~ Alert Message',modal: true});
   });
</script>

<script>
   $(document).ready(function(){
      $("#Error_During_Process").dialog({ autoOpen: false, width:"40%",height:110, title:'eHMS 2.0 ~ Alert Message',modal: true});
   });
</script>

<script>
   $(document).ready(function(){
      $("#Credit_Bill").dialog({ autoOpen: false, width:"40%",height:110, title:'eHMS 2.0 ~ Alert Message',modal: true});
   });
</script>

<script>
   $(document).ready(function(){
      $("#Add_Items").dialog({ autoOpen: false, width:"90%",height:500, title:'ADD MORE ITEMS',modal: true});
   });
</script>

<script>
   $(document).ready(function(){
      $("#Successful_Dialogy").dialog({ autoOpen: false, width:"40%",height:110, title:'eHMS 2.0 ~ Alert Message',modal: true});
   });
</script>

<script>
   $(document).ready(function(){
      $("#Unsuccessful_Dialogy").dialog({ autoOpen: false, width:"40%",height:110, title:'eHMS 2.0 ~ Alert Message',modal: true});
   });
</script>

<script>
   $(document).ready(function(){
      $("#Verify_Remove_Item").dialog({ autoOpen: false, width:'40%',height:220, title:'REMOVE ITEM',modal: true});
   });
</script>

<script>
   $(document).ready(function(){
      $("#Editing_Transaction").dialog({ autoOpen: false, width:'80%',height:200, title:'EDIT ITEM',modal: true});
   });
</script>


<script>
   $(document).ready(function(){
      $("#List_OF_Doctors").dialog({ autoOpen: false, width:'30%',height:350, title:'DOCTORS LIST',modal: true});
   });
</script>


<script>
    $(document).ready(function () {
        $("#Change_Item").dialog({autoOpen: false, width: '30%', height: 500, title: 'CHANGE ITEM', modal: true});
    });
</script>

<script type="text/javascript">
	function Approve_Patient_Bill_Warning2(){
		$("#No_Enough_Payments").dialog("open");
	}
</script>

<script type="text/javascript">
	function Get_Item_Name(Item_Name,Item_ID){
		document.getElementById("Item_Name").value = Item_Name;
		document.getElementById("Item_ID").value = Item_ID;
		document.getElementById("Quantity").value = '';
		document.getElementById("Quantity").focus();
    }
</script>

<script type="text/javascript">
	function Save_Information_Verify(){
		var Registration_ID = '<?php echo $Registration_ID; ?>';
		if(window.XMLHttpRequest) { 
		    myObjectVerify = new XMLHttpRequest();
		}else if(window.ActiveXObject){
		    myObjectVerify = new ActiveXObject('Micrsoft.XMLHTTP');
		    myObjectVerify.overrideMimeType('text/xml');
		}

		myObjectVerify.onreadystatechange = function (){
		    dataVerify = myObjectVerify.responseText;
		    if (myObjectVerify.readyState == 4) {
				var feedback = dataVerify;
				if(feedback == 'yes'){
					Save_Information(Registration_ID);
				}else if(feedback == 'not'){
					$("#Zero_Price_Or_Quantity_Alert").dialog("open");
				}else if(feedback == 'no'){
					$("#No_Items_Found").dialog("open");				
				}else{
					$("#Something_Wrong").dialog("open");
				}
		    }
		}; //specify name of function that will handle server response........
		
		myObjectVerify.open('GET','Inpatient_Verify_Information.php?Registration_ID='+Registration_ID,true);
		myObjectVerify.send();
	}	
</script>

<script type="text/javascript">
	function Save_Information(Registration_ID){
		Patient_Bill_ID = '<?php echo $Patient_Bill_ID; ?>';
		Sponsor_ID = '<?php echo $Sponsor_ID; ?>';
		Folio_Number = '<?php echo $Folio_Number; ?>';
		Check_In_ID = '<?php echo $Check_In_ID; ?>';
		var Transaction_Type = document.getElementById("Transaction_Type").value;
		var sms = confirm("Are you sure you want to add selected items?");
		if(sms == true){
			if(window.XMLHttpRequest) {
			    myObjectSave = new XMLHttpRequest();
			}else if(window.ActiveXObject){
			    myObjectSave = new ActiveXObject('Micrsoft.XMLHTTP');
			    myObjectSave.overrideMimeType('text/xml');
			}

			myObjectSave.onreadystatechange = function (){
			    dataSave = myObjectSave.responseText;
			    if (myObjectSave.readyState == 4) {
			    	var feedbacks = dataSave;
			    	if(feedbacks == 'yes'){
			    		$("#Add_Items").dialog("close");
			    		Sort_Mode(Patient_Bill_ID,Folio_Number,Sponsor_ID,Check_In_ID,Registration_ID)
			    		$("#Successful_Dialogy").dialog("open");
			    	}else{
			    		$("#Unsuccessful_Dialogy").dialog("open");
			    	}
			    }
			}; //specify name of function that will handle server response........
			
			myObjectSave.open('GET','Save_Information_Inpatient.php?Registration_ID='+Registration_ID+'&Transaction_Type='+Transaction_Type+'&Check_In_ID='+Check_In_ID+'&Folio_Number='+Folio_Number+'&Sponsor_ID='+Sponsor_ID+'&Patient_Bill_ID='+Patient_Bill_ID,true);
			myObjectSave.send();
		}
	}
</script>

<script type="text/javascript">
	function Calculate_Grand_Total(){
		var Registration_ID = '<?php echo $Registration_ID; ?>';
		if(window.XMLHttpRequest) {
		    myObjectGrand = new XMLHttpRequest();
		}else if(window.ActiveXObject){
		    myObjectGrand = new ActiveXObject('Micrsoft.XMLHTTP');
		    myObjectGrand.overrideMimeType('text/xml');
		}

		myObjectGrand.onreadystatechange = function (){
		    dataGrandTotal = myObjectGrand.responseText;
		    if (myObjectGrand.readyState == 4) {
				document.getElementById('Grand_Total_Area').innerHTML = dataGrandTotal;
		    }
		}; //specify name of function that will handle server response........
		
		myObjectGrand.open('GET','Inpatient_Calculate_Grand_Total.php?Registration_ID='+Registration_ID,true);
		myObjectGrand.send();
	}
</script>
<script type="text/javascript">
	function Add_Selected_Item(){
		var Registration_ID = '<?php echo $Registration_ID; ?>';
		var Transaction_Type = document.getElementById("Transaction_Type").value;
		var Item_ID = document.getElementById("Item_ID").value;
		var Quantity = document.getElementById("Quantity").value;
		var Discount = document.getElementById("Discount").value;
		var Check_In_Type = document.getElementById("Check_In_Type").value;
		var Sponsor_ID = '<?php echo $Sponsor_ID; ?>';
		var Price = document.getElementById("Price").value;

		if(Price != 0 && Item_ID != null && Item_ID != '' && Check_In_Type != null && Check_In_Type != '' && Registration_ID != '' && Registration_ID != null && Quantity != null && Quantity != '' && Quantity != 0){
			if(window.XMLHttpRequest) {
			    myObjectAddSelectedItem = new XMLHttpRequest();
			}else if(window.ActiveXObject){
			    myObjectAddSelectedItem = new ActiveXObject('Micrsoft.XMLHTTP');
			    myObjectAddSelectedItem.overrideMimeType('text/xml');
			}

			myObjectAddSelectedItem.onreadystatechange = function (){
			    data921 = myObjectAddSelectedItem.responseText;
			    if (myObjectAddSelectedItem.readyState == 4) {
			    	document.getElementById("Cached_Items").innerHTML = data921
			    	Calculate_Grand_Total();
			    	document.getElementById("Price").value = '';
			    	document.getElementById("Item_Name").value = '';
			    	document.getElementById("Discount").value = '';
			    	document.getElementById("Quantity").value = '';
			    }
			}; //specify name of function that will handle server response........
			
			myObjectAddSelectedItem.open('GET','Inpatient_Add_More_Selected_Item.php?Registration_ID='+Registration_ID+'&Item_ID='+Item_ID+'&Quantity='+Quantity+'&Discount='+Discount+'&Check_In_Type='+Check_In_Type+'&Transaction_Type='+Transaction_Type+'&Sponsor_ID='+Sponsor_ID,true);
			myObjectAddSelectedItem.send();
		}else{
			if((Price == null || Price == '' || Price == 0) && Item_ID != null && Item_ID != ''){
				$("#Zero_Price_Alert").dialog("open");
				return false
			}
			if(Item_ID == null || Item_ID == ''){
				document.getElementById("Item_Name").style = 'border: 3px solid red';
			}else{
				document.getElementById("Item_Name").style = 'border: 3px solid white';
			}

			if(Check_In_Type == null || Check_In_Type == ''){
				document.getElementById("Check_In_Type").style = 'border: 3px solid red';
			}else{
				document.getElementById("Check_In_Type").style = 'border: 3px solid white';
			}

			if(Quantity == null || Quantity == '' || Quantity == 0){
				document.getElementById("Quantity").style = 'border: 3px solid red';
			}else{
				document.getElementById("Quantity").style = 'border: 3px solid white';
			}
		}
	}
</script>

<script type="text/javascript">
	function Remove_Item(Item_Cache_ID,Product_Name){
		var Registration_ID = '<?php echo $Registration_ID; ?>';
		var sms = confirm("Are you sure you want to remove "+Product_Name+"?");
		if(sms == true){
			if(window.XMLHttpRequest) {
			    myObjectRemove = new XMLHttpRequest();
			}else if(window.ActiveXObject){
			    myObjectRemove = new ActiveXObject('Micrsoft.XMLHTTP');
			    myObjectRemove.overrideMimeType('text/xml');
			}

			myObjectRemove.onreadystatechange = function (){
			    dataRemove = myObjectRemove.responseText;
			    if (myObjectRemove.readyState == 4) {
					document.getElementById('Cached_Items').innerHTML = dataRemove;
					Calculate_Grand_Total();
			    }
			}; //specify name of function that will handle server response........
			
			myObjectRemove.open('GET','Inpatient_Remove_Selected_Item.php?Item_Cache_ID='+Item_Cache_ID+'&Registration_ID='+Registration_ID,true);
			myObjectRemove.send();
		}
	}
</script>
<script type="text/javascript">
	function Get_Item_Price(Item_ID,Guarantor_Name){
		var Transaction_Type = document.getElementById("Transaction_Type").value;

		if(window.XMLHttpRequest) {
		    myObjectPrice = new XMLHttpRequest();
		}else if(window.ActiveXObject){
		    myObjectPrice = new ActiveXObject('Micrsoft.XMLHTTP');
		    myObjectPrice.overrideMimeType('text/xml');
		}

		myObjectPrice.onreadystatechange = function (){
		    data = myObjectPrice.responseText;
		    
		    if (myObjectPrice.readyState == 4) { 
				document.getElementById('Price').value = data;
				document.getElementById("Quantity").value = 1;
		    }
		}; //specify name of function that will handle server response........
		
		myObjectPrice.open('GET','Get_Items_Price_Inpatient.php?Item_ID='+Item_ID+'&Guarantor_Name='+Guarantor_Name+'&Transaction_Type='+Transaction_Type,true);
		myObjectPrice.send();
    }
</script>
<script type="text/javascript">
    function Preview_Patient_Details(Check_In_ID){
        if(window.XMLHttpRequest) {
            myObjectPreview = new XMLHttpRequest();
        }else if(window.ActiveXObject){ 
            myObjectPreview = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectPreview.overrideMimeType('text/xml');
        }
  
        myObjectPreview.onreadystatechange = function (){
            data2000 = myObjectPreview.responseText;
            if (myObjectPreview.readyState == 4) {
                document.getElementById("Get_Patient_Details").innerHTML = data2000;
                $("#Get_Patient_Details").dialog("open");
            }
        }; //specify name of function that will handle server response........
        myObjectPreview.open('GET','Preview_Patient_Bill_Details.php?Check_In_ID='+Check_In_ID,true);
        myObjectPreview.send();
    }
</script>

<script type="text/javascript">
    function View_Details(Patient_Payment_ID,Patient_Payment_Item_List_ID){
        /**if(window.XMLHttpRequest) {
            myObjectViewDetails = new XMLHttpRequest();
        }else if(window.ActiveXObject){ 
            myObjectViewDetails = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectViewDetails.overrideMimeType('text/xml');
        }
  
        myObjectViewDetails.onreadystatechange = function (){
            data = myObjectViewDetails.responseText;
            if (myObjectViewDetails.readyState == 4) {
                document.getElementById("Preview_Transaction_Details").innerHTML = data;
                $("#Preview_Transaction_Details").dialog("open");
            }
        }; //specify name of function that will handle server response........
        myObjectViewDetails.open('GET','Preview_Transaction_Details.php?Patient_Payment_ID='+Patient_Payment_ID+'&Patient_Payment_Item_List_ID='+Patient_Payment_Item_List_ID,true);
        myObjectViewDetails.send();*/
    }
</script>
<script>
    function Patient_List_Search(){
        var Patient_Name = document.getElementById("Search_Patient").value;
        var date_From = document.getElementById("date_From").value;
        var date_To = document.getElementById("date_To").value;
        var Billing_Type = '<?php echo $Billing_Type2; ?>';
        document.getElementById("Patient_Number").value = '';

        if(window.XMLHttpRequest){
            myObjectSearchPatient = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObjectSearchPatient = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectSearchPatient.overrideMimeType('text/xml');
        }
        myObjectSearchPatient.onreadystatechange = function (){
            data28 = myObjectSearchPatient.responseText;
            if (myObjectSearchPatient.readyState == 4) {
                document.getElementById('Patients_Fieldset_List').innerHTML = data28;
            }
        }; //specify name of function that will handle server response........
        
        myObjectSearchPatient.open('GET','Revenue_Center_Pharmacy_List_Iframe.php?Patient_Name='+Patient_Name+'&date_From='+date_From+'&date_To='+date_To+'&Billing_Type='+Billing_Type,true);
        myObjectSearchPatient.send();   
    }
</script>

<script>
    function Patient_List_Search2(){
        var Patient_Number = document.getElementById("Patient_Number").value;
        var date_From = document.getElementById("date_From").value;
        var date_To = document.getElementById("date_To").value;
        var Billing_Type = '<?php echo $Billing_Type2; ?>';
        document.getElementById("Search_Patient").value = '';

        if(window.XMLHttpRequest){
            myObjectSearchPatient = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObjectSearchPatient = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectSearchPatient.overrideMimeType('text/xml');
        }
        myObjectSearchPatient.onreadystatechange = function (){
            data218 = myObjectSearchPatient.responseText;
            if (myObjectSearchPatient.readyState == 4) {
                document.getElementById('Patients_Fieldset_List').innerHTML = data218;
            }
        }; //specify name of function that will handle server response........
        
        myObjectSearchPatient.open('GET','Revenue_Center_Pharmacy_List_Iframe.php?Patient_Number='+Patient_Number+'&date_From='+date_From+'&date_To='+date_To+'&Billing_Type='+Billing_Type,true);
        myObjectSearchPatient.send();   
    }
</script>

<script type="text/javascript">
    function Sort_Mode(Patient_Bill_ID,Folio_Number,Sponsor_ID,Check_In_ID,Registration_ID){
        var Receipt_Mode = document.getElementById("Receipt_Mode").value;
        var Transaction_Type = document.getElementById("Transaction_Type").value;

        if(window.XMLHttpRequest){
            myObjectMode = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObjectMode = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectMode.overrideMimeType('text/xml');
        }
        myObjectMode.onreadystatechange = function (){
            data288 = myObjectMode.responseText;
            if (myObjectMode.readyState == 4) {
                document.getElementById('Transaction_Items_Details').innerHTML = data288;
                Summary_Sort_Mode(Patient_Bill_ID,Folio_Number,Sponsor_ID,Check_In_ID,Registration_ID);
            }
        }; //specify name of function that will handle server response........
        
        myObjectMode.open('GET','Sort_Mode_Display.php?Patient_Bill_ID='+Patient_Bill_ID+'&Folio_Number='+Folio_Number+'&Sponsor_ID='+Sponsor_ID+'&Check_In_ID='+Check_In_ID+'&Receipt_Mode='+Receipt_Mode+'&Transaction_Type='+Transaction_Type+'&Registration_ID='+Registration_ID,true);
        myObjectMode.send();
    }
</script>



<script type="text/javascript">
    function Summary_Sort_Mode(Patient_Bill_ID,Folio_Number,Sponsor_ID,Check_In_ID,Registration_ID){
        var Receipt_Mode = document.getElementById("Receipt_Mode").value;
        var Transaction_Type = document.getElementById("Transaction_Type").value;
        var Admision_ID = '<?php echo $Admision_ID; ?>';

        if(window.XMLHttpRequest){
            myObjectSummary = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObjectSummary = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectSummary.overrideMimeType('text/xml');
        }
        myObjectSummary.onreadystatechange = function (){
            data9999 = myObjectSummary.responseText;
            if (myObjectSummary.readyState == 4){
                document.getElementById('Transaction_Summary_Area').innerHTML = data9999;
            }
        }; //specify name of function that will handle server response........
        
        myObjectSummary.open('GET','Sort_Mode_Summary_Display.php?Patient_Bill_ID='+Patient_Bill_ID+'&Folio_Number='+Folio_Number+'&Sponsor_ID='+Sponsor_ID+'&Check_In_ID='+Check_In_ID+'&Receipt_Mode='+Receipt_Mode+'&Transaction_Type='+Transaction_Type+'&Registration_ID='+Registration_ID+'&Admision_ID='+Admision_ID,true);
        myObjectSummary.send();
    }
</script>


<script type="text/javascript">
	function Preview_Advance_Payments(Patient_Bill_ID,Registration_ID){

		if(window.XMLHttpRequest){
            myObjectPreviewAdvance = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObjectPreviewAdvance = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectPreviewAdvance.overrideMimeType('text/xml');
        }
        myObjectPreviewAdvance.onreadystatechange = function (){
            data987 = myObjectPreviewAdvance.responseText;
            if (myObjectPreviewAdvance.readyState == 4){
            	document.getElementById("Preview_Advance").innerHTML = data987;
                $("#Preview_Advance").dialog("open");
            }
        }; //specify name of function that will handle server response........
        
        myObjectPreviewAdvance.open('GET','Preview_Advance_Payments_Pre_Paid.php?Patient_Bill_ID='+Patient_Bill_ID+'&Registration_ID='+Registration_ID,true);
        myObjectPreviewAdvance.send();
	}
</script>

<script type="text/javascript">
    function Display_Transaction(Patient_Bill_ID,Folio_Number,Sponsor_ID,Check_In_ID){
    	var Transaction_Type = document.getElementById("Transaction_Type").value;
    	var Registration_ID = '<?php echo $Registration_ID; ?>';
    	var Admision_ID = '<?php echo $Admision_ID; ?>';
    	if(window.XMLHttpRequest){
            myObjectDisplay = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObjectDisplay = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectDisplay.overrideMimeType('text/xml');
        }
        myObjectDisplay.onreadystatechange = function (){
            data99929 = myObjectDisplay.responseText;
            if (myObjectDisplay.readyState == 4){
                document.getElementById('Transaction_Summary_Area').innerHTML = data99929;
            }
        }; //specify name of function that will handle server response........
        
        myObjectDisplay.open('GET','Sort_Mode_Summary_Display.php?Patient_Bill_ID='+Patient_Bill_ID+'&Folio_Number='+Folio_Number+'&Sponsor_ID='+Sponsor_ID+'&Check_In_ID='+Check_In_ID+'&Receipt_Mode='+Receipt_Mode+'&Transaction_Type='+Transaction_Type+'&Registration_ID='+Registration_ID+'&Admision_ID='+Admision_ID,true);
        myObjectDisplay.send();
    }
</script>

<script type="text/javascript">
	function Add_More_Items(Patient_Bill_ID,Folio_Number,Sponsor_ID,Check_In_ID,Registration_ID){
		var Transaction_Type = document.getElementById("Transaction_Type").value;
		if(window.XMLHttpRequest){
            myObjectDisplay = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObjectDisplay = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectDisplay.overrideMimeType('text/xml');
        }
        myObjectDisplay.onreadystatechange = function (){
            mydata = myObjectDisplay.responseText;
            if (myObjectDisplay.readyState == 4){
                document.getElementById('Details_Area').innerHTML = mydata;
				$("#Add_Items").dialog("open");
            }
        }; //specify name of function that will handle server response........
        
        myObjectDisplay.open('GET','Patient_Billing_Add_More_Items.php?Patient_Bill_ID='+Patient_Bill_ID+'&Folio_Number='+Folio_Number+'&Sponsor_ID='+Sponsor_ID+'&Check_In_ID='+Check_In_ID+'&Transaction_Type='+Transaction_Type+'&Registration_ID='+Registration_ID,true);
        myObjectDisplay.send();
	}
</script>

<script type="text/javascript">
    function Preview_Patient_Bill(Patient_Bill_ID,Registration_ID){
        window.open('previewpatientprebill.php?Patient_Bill_ID='+Patient_Bill_ID+'&Registration_ID='+Registration_ID+'&PreviewPatientBill=PreviewPatientBillThisReport','_blank');
    }
</script>

<script type="text/javascript">
	function Approve_Patient_Bill(Patient_Bill_ID,Folio_Number,Sponsor_ID,Check_In_ID,Registration_ID){
		var Transaction_Type = document.getElementById("Transaction_Type").value;
		var Admision_ID = '<?php echo $Admision_ID; ?>';
		
		var Confirm_Message = confirm("Are you sure you want to approve selected bill?");
		if(Confirm_Message == true){
			if(window.XMLHttpRequest){
	            myObjectVerifyItems = new XMLHttpRequest();
	        }else if(window.ActiveXObject){
	            myObjectVerifyItems = new ActiveXObject('Micrsoft.XMLHTTP');
	            myObjectVerifyItems.overrideMimeType('text/xml');
	        }
	        myObjectVerifyItems.onreadystatechange = function (){
	            data01 = myObjectVerifyItems.responseText;
	            if (myObjectVerifyItems.readyState == 4){
	                var feedback = data01;
	                if(feedback == 'yes'){
	                	Approve_Bill_Process(Patient_Bill_ID,Folio_Number,Sponsor_ID,Check_In_ID,Registration_ID);
	                }else if(feedback == 'not'){
	                	$("#Not_Ready_To_Bill").dialog("open"); //not ready to bill
	                }else if(feedback == 'true'){
	                	$("#Patient_Already_Cleared").dialog("open");
	                }else{
	                	$("#Approval_Warning_Message").dialog("open"); //something happened
	                }
	            }
	        }; //specify name of function that will handle server response........
	        myObjectVerifyItems.open('GET','Approve_Patient_Bill_Verify.php?Transaction_Type='+Transaction_Type+'&Admision_ID='+Admision_ID,true);
	        myObjectVerifyItems.send();
	    }
	}
</script>

<script type="text/javascript">
	function Approve_Bill_Process(Patient_Bill_ID,Folio_Number,Sponsor_ID,Check_In_ID,Registration_ID){
		var Transaction_Type = document.getElementById("Transaction_Type").value;
		var Registration_ID = '<?php echo $Registration_ID; ?>';
		var Admision_ID = '<?php echo $Admision_ID; ?>';
		if(window.XMLHttpRequest){
            myObjectApprove = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObjectApprove = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectApprove.overrideMimeType('text/xml');
        }
        myObjectApprove.onreadystatechange = function (){
            data99991 = myObjectApprove.responseText;
            if (myObjectApprove.readyState == 4){
            	var feedback = data99991;
            	if(feedback == '100'){ //refund required
            		Display_Transaction(Patient_Bill_ID,Folio_Number,Sponsor_ID,Check_In_ID)
            		$("#Refund_Required").dialog("open");
            	}else if(feedback == '200'){ //no enough paymments to clear bill
            		$("#No_Enough_Payments").dialog("open");
            	}else if(feedback == '300'){ //error occur during the process
            		$("$Error_During_Process").dialog("open");
            	}else if(feedback == '400'){ //credit bill ~ no complexity
            		Display_Transaction(Patient_Bill_ID,Folio_Number,Sponsor_ID,Check_In_ID)	
            		$("#Credit_Bill").dialog("open");
            	}
            }
        }; //specify name of function that will handle server response........
        
        myObjectApprove.open('GET','Approve_Patient_Bill.php?Patient_Bill_ID='+Patient_Bill_ID+'&Folio_Number='+Folio_Number+'&Sponsor_ID='+Sponsor_ID+'&Check_In_ID='+Check_In_ID+'&Transaction_Type='+Transaction_Type+'&Registration_ID='+Registration_ID+'&Admision_ID='+Admision_ID,true);
        myObjectApprove.send();
	}
</script>

<script type="text/javascript" language="javascript">
    function getItemsList(Item_Category_ID){
		document.getElementById("Search_Product").value = '';
		document.getElementById("Price").value = '';
		document.getElementById("Item_Name").value = '';
		document.getElementById("Quantity").value = '';
		var Guarantor_Name = '<?php echo $Guarantor_Name; ?>';
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
			//document.getElementById('Approval').readonly = 'readonly';
			document.getElementById('Items_Fieldset').innerHTML = data;
		    }
		}; //specify name of function that will handle server response........
		myObject.open('GET','Get_List_Of_Items.php?Item_Category_ID='+Item_Category_ID+'&Guarantor_Name='+Guarantor_Name,true);
		myObject.send();
    }
</script>

<script type="text/javascript">
	function getItemsListFiltered(Item_Name,Guarantor_Name){
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
    
		myObject.onreadystatechange = function (){
		    data = myObject.responseText;
		    if (myObject.readyState == 4) {
			//document.getElementById('Approval').readonly = 'readonly';
			document.getElementById('Items_Fieldset').innerHTML = data;
		    }
		}; //specify name of function that will handle server response........
		myObject.open('GET','Get_List_Of_Items_Filtered.php?Item_Category_ID='+Item_Category_ID+'&Item_Name='+Item_Name+'&Guarantor_Name='+Guarantor_Name,true);
		myObject.send();
    }
</script>

<script type="text/javascript">
	function Remove_Transaction(Patient_Payment_ID,Patient_Payment_Item_List_ID,Item_Name){
		if(window.XMLHttpRequest) {
		    myObjectContent = new XMLHttpRequest();
		}else if(window.ActiveXObject){ 
		    myObjectContent = new ActiveXObject('Micrsoft.XMLHTTP');
		    myObjectContent.overrideMimeType('text/xml');
		}
    
		myObjectContent.onreadystatechange = function (){
		    data9834 = myObjectContent.responseText;
		    if (myObjectContent.readyState == 4) {
				document.getElementById("Remove_Selected_Area").innerHTML = data9834;
				$("#Verify_Remove_Item").dialog("open");
		    }
		}; //specify name of function that will handle server response........
		myObjectContent.open('GET','Remove_Selected_Item_Contents.php?Patient_Payment_Item_List_ID='+Patient_Payment_Item_List_ID+'&Patient_Payment_ID='+Patient_Payment_ID+'&Item_Name='+Item_Name,true);
		myObjectContent.send();
	}
</script>

<script type="text/javascript">
	function Edit_Transaction(Patient_Payment_ID,Patient_Payment_Item_List_ID,Item_Name){
		if(window.XMLHttpRequest) {
		    myObjectRemoveItem = new XMLHttpRequest();
		}else if(window.ActiveXObject){ 
		    myObjectRemoveItem = new ActiveXObject('Micrsoft.XMLHTTP');
		    myObjectRemoveItem.overrideMimeType('text/xml');
		}
    
		myObjectRemoveItem.onreadystatechange = function (){
		    dataEdit = myObjectRemoveItem.responseText;
		    if (myObjectRemoveItem.readyState == 4) {
		    	document.getElementById("Edit_Area").innerHTML = dataEdit;
		    	$("#Editing_Transaction").dialog("open");
		    }
		}; //specify name of function that will handle server response........
		myObjectRemoveItem.open('GET','Patient_Billing_Edit_Transaction.php?Patient_Payment_Item_List_ID='+Patient_Payment_Item_List_ID+'&Patient_Payment_ID='+Patient_Payment_ID,true);
		myObjectRemoveItem.send();
	}
</script>

<script type="text/javascript">
	function Remove_Selected_Item(Patient_Payment_ID,Patient_Payment_Item_List_ID){
		var Patient_Bill_ID = '<?php echo $Patient_Bill_ID; ?>';
		var Folio_Number = '<?php echo $Folio_Number; ?>';
		var Sponsor_ID = '<?php echo $Sponsor_ID; ?>';
		var Check_In_ID = '<?php echo $Check_In_ID; ?>';
		var Registration_ID = '<?php echo $Registration_ID; ?>';
		if(window.XMLHttpRequest) {
		    myObjectRemoveItem = new XMLHttpRequest();
		}else if(window.ActiveXObject){ 
		    myObjectRemoveItem = new ActiveXObject('Micrsoft.XMLHTTP');
		    myObjectRemoveItem.overrideMimeType('text/xml');
		}
    
		myObjectRemoveItem.onreadystatechange = function (){
		    data = myObjectRemoveItem.responseText;
		    if (myObjectRemoveItem.readyState == 4) {
		    	$("#Verify_Remove_Item").dialog("close");
		    	Sort_Mode(Patient_Bill_ID,Folio_Number,Sponsor_ID,Check_In_ID,Registration_ID)
		    	View_Details(Patient_Payment_ID,0);
		    }
		}; //specify name of function that will handle server response........
		myObjectRemoveItem.open('GET','Patient_Billing_Remove_Selected_Item.php?Patient_Payment_Item_List_ID='+Patient_Payment_Item_List_ID,true);
		myObjectRemoveItem.send();
	}
</script>


<script type="text/javascript">
    function getDoctor() {
		/*var Type_Of_Check_In = document.getElementById('Type_Of_Check_In').value;
	    if(window.XMLHttpRequest) {
		mm = new XMLHttpRequest();
	    }
	    else if(window.ActiveXObject){ 
		mm = new ActiveXObject('Micrsoft.XMLHTTP');
		mm.overrideMimeType('text/xml');
	    }
	    
	    if (document.getElementById('Patient_Direction').value =='Direct To Doctor Via Nurse Station' || document.getElementById('Patient_Direction').value =='Direct To Doctor') {
	    	document.getElementById('Doctors_List').style.visibility = "";
			mm.onreadystatechange= AJAXP3; //specify name of function that will handle server response....
			mm.open('GET','Patient_Billing_Select_Patient_Direction.php?Type_Of_Check_In='+Type_Of_Check_In+'&Patient_Direction=doctor',true);
			mm.send();
	    }
	    else{
			mm.onreadystatechange= AJAXP3; //specify name of function that will handle server response....
			mm.open('GET','Patient_Billing_Select_Patient_Direction.php?Patient_Direction=clinic',true);
			mm.send();
			document.getElementById('Doctors_List').style.visibility = "hidden";
	    }
	}
    function AJAXP3(){
		var data3 = mm.responseText;
		document.getElementById('Consultant').innerHTML = data3;
    }*/

	    var Patient_Direction = document.getElementById('Patient_Direction').value;
	    var Type_Of_Check_In = document.getElementById('Type_Of_Check_In').value;
	    if(window.XMLHttpRequest) {
			myObjectFilter = new XMLHttpRequest();
		}else if(window.ActiveXObject){
		    myObjectFilter = new ActiveXObject('Micrsoft.XMLHTTP');
		    myObjectFilter.overrideMimeType('text/xml');
		}
	    
		myObjectFilter.onreadystatechange = function (){
		    dataFilter = myObjectFilter.responseText;
		    if (myObjectFilter.readyState == 4) {
		    	document.getElementById("Consultant_Area").innerHTML = dataFilter;
		    }
		}; //specify name of function that will handle server response........

		if(Patient_Direction == 'Direct To Doctor Via Nurse Station' || Patient_Direction == 'Direct To Doctor'){
			document.getElementById('Doctors_List').style.visibility = "";
			myObjectFilter.open('GET','Patient_Billing_Select_Patient_Direction.php?Type_Of_Check_In='+Type_Of_Check_In+'&Patient_Direction=doctor',true);
		}else{
			document.getElementById('Doctors_List').style.visibility = "hidden";
			myObjectFilter.open('GET','Patient_Billing_Select_Patient_Direction.php?Patient_Direction=clinic',true);
		}
		myObjectFilter.send();
	}
</script>

<script type="text/javascript">
	function Cancel_Edit_Process(){
		$("#Editing_Transaction").dialog("close");
	}
</script>


<script type="text/javascript">
	function Get_Doctor(){
		var Direction = document.getElementById("Patient_Direction").value;
		if(Direction != null && Direction != '' && (Direction == 'Direct To Doctor Via Nurse Station' || Direction == 'Direct To Doctor')){
			$("#List_OF_Doctors").dialog("open");
		}
	}
</script>

<script type="text/javascript">
	function Get_Selected_Doctor(Doctror_Name)	{
		document.getElementById("Consultant").value = Doctror_Name;
		// document.getElementById("Doc_Name").value = '';
		// Search_Doctors();
		$("#List_OF_Doctors").dialog("close");
	}
</script>


<script type="text/javascript">
	function Search_Doctors(){
		var Doctror_Name = document.getElementById("Doc_Name").value;
		if(window.XMLHttpRequest){
		    myObject_Search_Doctor = new XMLHttpRequest();
		}else if(window.ActiveXObject){
		    myObject_Search_Doctor = new ActiveXObject('Micrsoft.XMLHTTP');
		    myObject_Search_Doctor.overrideMimeType('text/xml');
		}

		myObject_Search_Doctor.onreadystatechange = function (){
		    data = myObject_Search_Doctor.responseText;
		    if (myObject_Search_Doctor.readyState == 4) {
				document.getElementById('Doctors_Area').innerHTML = data;
		    }
		}; //specify name of function that will handle server response........
		myObject_Search_Doctor.open('GET','Search_Doctors.php?Doctror_Name='+Doctror_Name,true);
		myObject_Search_Doctor.send();
	}
</script>
<script type="text/javascript">
	function Close_Dialog(){
		$("#Verify_Remove_Item").dialog("close");
	}
</script>

<script type="text/javascript">
	function Preview_Advance_Payments_Warning(){
		$("#MessageAlert").dialog("open");
	}
</script>

<script type="text/javascript">
    function Open_Item_Dialogy(Sponsor_ID) {
    	var Billing_Type = document.getElementById("Billing_Type").value;
        if (window.XMLHttpRequest) {
            myObjectChangeItem = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectChangeItem = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectChangeItem.overrideMimeType('text/xml');
        }

        myObjectChangeItem.onreadystatechange = function () {
            data2922 = myObjectChangeItem.responseText;
            if (myObjectChangeItem.readyState == 4) {
                document.getElementById('Edit_Items_Area').innerHTML = data2922;
                $("#Change_Item").dialog("open");
            }
        }; //specify name of function that will handle server response........

        myObjectChangeItem.open('GET', 'Patient_Billing_Change_Item.php?Sponsor_ID='+Sponsor_ID+'&Billing_Type='+Billing_Type, true);
        myObjectChangeItem.send();
    }
</script>

<script type="text/javascript">
	function Get_Selected_Item(Item_Name,Item_ID,Sponsor_ID){
		document.getElementById("Pro_Name").value = Item_Name;
		document.getElementById("Pro_ID").value = Item_ID;
		var Billing_Type = document.getElementById("Billing_Type").value;
		Get_Price(Item_ID,Sponsor_ID);
	}
</script>

<script type="text/javascript">
	function Get_Price(Item_ID,Sponsor_ID){
		var Billing_Type = document.getElementById("Billing_Type").value;
		if(window.XMLHttpRequest) {
		    myObjectEditedPrice = new XMLHttpRequest();
		}else if(window.ActiveXObject){
		    myObjectEditedPrice = new ActiveXObject('Micrsoft.XMLHTTP');
		    myObjectEditedPrice.overrideMimeType('text/xml');
		}
		myObjectEditedPrice.onreadystatechange = function (){
		    data5050 = myObjectEditedPrice.responseText;
		    if (myObjectEditedPrice.readyState == 4) { 
				document.getElementById('Edited_Price').value = data5050;
				$("#Change_Item").dialog("close");
		    }
		}; //specify name of function that will handle server response........
		
		myObjectEditedPrice.open('GET','Patient_Billing_Get_Price.php?Item_ID='+Item_ID+'&Sponsor_ID='+Sponsor_ID+'&Billing_Type='+Billing_Type,true);
		myObjectEditedPrice.send();
    }
</script>


<script type="text/javascript">
	function Get_Items_List_Filtered(){
		var Item_Category_ID = document.getElementById("Item_Category_ID").value;
		var Item_Name = document.getElementById("Search_Product_Name").value;
		var Sponsor_ID = '<?php echo $Sponsor_ID; ?>';

		if (Item_Category_ID == '' || Item_Category_ID == null) {
		    Item_Category_ID = 'All';
		}
	
		if(window.XMLHttpRequest) {
		    myObjectSearchEdit = new XMLHttpRequest();
		}else if(window.ActiveXObject){ 
		    myObjectSearchEdit = new ActiveXObject('Micrsoft.XMLHTTP');
		    myObjectSearchEdit.overrideMimeType('text/xml');
		}
    
		myObjectSearchEdit.onreadystatechange = function (){
		    data97 = myObjectSearchEdit.responseText;
		    if (myObjectSearchEdit.readyState == 4) {
				document.getElementById('Items_Area').innerHTML = data97;
		    }
		}; //specify name of function that will handle server response........
		myObjectSearchEdit.open('GET','Get_Items_List_Filtered.php?Item_Category_ID='+Item_Category_ID+'&Item_Name='+Item_Name+'&Sponsor_ID='+Sponsor_ID,true);
		myObjectSearchEdit.send();
	}
</script>

<script type="text/javascript">
	function Get_Items_List(Item_Category_ID){
		document.getElementById("Search_Product_Name").value = '';
		var Sponsor_ID = '<?php echo $Sponsor_ID; ?>';
		if(window.XMLHttpRequest) {
		    myObjectSearchEdit2 = new XMLHttpRequest();
		}else if(window.ActiveXObject){ 
		    myObjectSearchEdit2 = new ActiveXObject('Micrsoft.XMLHTTP');
		    myObjectSearchEdit2.overrideMimeType('text/xml');
		}
    
		myObjectSearchEdit2.onreadystatechange = function (){
		    data456 = myObjectSearchEdit2.responseText;
		    if (myObjectSearchEdit2.readyState == 4) {
				document.getElementById('Items_Area').innerHTML = data456;
		    }
		}; //specify name of function that will handle server response........
		myObjectSearchEdit2.open('GET','Get_Items_List.php?Item_Category_ID='+Item_Category_ID+'&Sponsor_ID='+Sponsor_ID,true);
		myObjectSearchEdit2.send();
	}
</script>
<script>
    function confirmZering(){
         var count = 0;
        var i = 1;
        $(".zero_items").each(function () {
            if ($(this).is(':checked')) {
                var id = $(this).attr('id');
                if (i == 1) {
                    dataInfo = id;
                } else {
                    dataInfo += '^$*^%$' + id;
                }

                i = i + 1;
                count = count + 1;
            }
            //this.checked=true;
        });
        if (count == 0) {
            alertMsg("Select Item(s) to zero price", "No Item Selected", 'error', 0, false, false, "", true, "Ok", true, .5, true);
            exit;
        }
        
        var msg='';
        
        if(count==1){
           msg='the selected item'; 
        }else{
           msg=count+' selected items';   
        }
        
        confirmAction('<h3 style="text-align:center;font-weight:100">Are you sure you want to zero price for '+msg+'?</h3>', 'Please make sure you know what you are doing', 'confirmation', 450, false,false, 'Yes', "No",.5,zeroItems);
    }
</script>
<script>
    function zeroItems() {
        var dataInfo = '';
        var count = 0;
        var i = 1;
        $(".zero_items").each(function () {
            if ($(this).is(':checked')) {
                var id = $(this).attr('id');
                if (i == 1) {
                    dataInfo = id;
                } else {
                    dataInfo += '^$*^%$' + id;
                }

                i = i + 1;
                count = count + 1;
            }
            //this.checked=true;
        });
        if (count == 0) {
            alertMsg("Select Item(s) to zero price", "No Item Selected", 'error', 0, false, false, "", true, "Ok", true, .5, true);
            exit;
        }

        $.ajax({
            type: 'POST',
            url: 'zero_item_price.php',
            data: 'action=zeroprice&dataInfos=' + dataInfo,
            beforeSend: function (xhr) {
                $("#progressStatus").show();
            },
            success: function (html) {
                if (html == '1') {
                    document.location.reload();
                } else {
                    alertMsg("An error has occured! Please Contact administrator for support", "Process Failed", 'error', 0, false, false, "", true, "Ok", true, .5, true);
                }
            }, complete: function () {
                $("#progressStatus").hide();
            }, error: function (html, jjj) {
                alert(html);
            }
        });

    }
</script>
<script>
    function addPrice(ppil,element){
        var status=$(element).is(':checked');
        if(!status){
            $.ajax({
            type: 'POST',
            url: 'zero_item_price.php',
            data: 'action=unzeroprice&ppil=' + ppil,
            beforeSend: function (xhr) {
                $("#progressStatus").show();
            },
            success: function (html) {
                if (html == '1') {
                    document.location.reload();
                } else {
                     alertMsg("An error has occured! Please Contact administrator for support", "Process Failed", 'error', 0, false, false, "", true, "Ok", true, .5, true);
                }
            }, complete: function () {
                $("#progressStatus").hide();
            }, error: function (html, jjj) {
                alert(html);
            }
        }); 
        }
    }
</script>
<?php
	include("./includes/footer.php");
?>