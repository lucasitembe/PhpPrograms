<?php
	session_start();
	include("./includes/connection.php");

	if(isset($_GET['Patient_Payment_Item_List_ID'])){
		$Patient_Payment_Item_List_ID = $_GET['Patient_Payment_Item_List_ID'];
	}else{
		$Patient_Payment_Item_List_ID = '';
	}

	if(isset($_GET['Patient_Payment_ID'])){
		$Patient_Payment_ID = $_GET['Patient_Payment_ID'];
	}else{
		$Patient_Payment_ID = '';
	}

	$select = mysqli_query($conn,"select ppl.Check_In_Type, i.Product_Name, ppl.Discount, pp.Sponsor_ID, pp.Billing_Type,
							ppl.Price, ppl.Quantity, ppl.Patient_Direction,
							ppl.Consultant_ID, ppl.Clinic_ID
							from tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_items i where 
							Patient_Payment_Item_List_ID = '$Patient_Payment_Item_List_ID' and
							pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
							i.Item_ID = ppl.Item_ID") or die(mysqli_error($conn));
	$num = mysqli_num_rows($select);
	if($num > 0){
		while ($data = mysqli_fetch_array($select)) {
			$Check_In_Type = $data['Check_In_Type'];
			$Product_Name = $data['Product_Name'];
			$Discount = $data['Discount'];
			$Price = $data['Price'];
			$Quantity = $data['Quantity'];
			$Patient_Direction = $data['Patient_Direction'];
			$Consultant_ID = $data['Consultant_ID'];
			$Clinic_ID = $data['Clinic_ID'];
			$Check_In_Type = $data['Check_In_Type'];
			$Billing_Type = $data['Billing_Type'];
			$Sponsor_ID = $data['Sponsor_ID'];
		}
	}else{
		$Check_In_Type = '';
		$Product_Name = '';
		$Discount = '';
		$Price = '';
		$Quantity = '';
		$Patient_Direction = '';
		$Consultant_ID = '';
		$Clinic_ID = '';
		$Check_In_Type = '';
		$Billing_Type = '';
		$Sponsor_ID = '';
	}

	//get guarantor name
	$get_guarantor = mysqli_query($conn,"select Guarantor_Name from tbl_sponsor where Sponsor_ID = '$Sponsor_ID'") or die(mysqli_error($conn));
	$nmz = mysqli_num_rows($get_guarantor);
	if($nmz > 0){
		while ($dtz = mysqli_fetch_array($get_guarantor)) {
			$Guarantor_Name = $dtz['Guarantor_Name'];
		}
	}else{
		$Guarantor_Name = '';
	}

	//get consultant
	if($Consultant_ID > 0){
		$select = mysqli_query($conn,"select Employee_Name from tbl_employee where Employee_ID = '$Consultant_ID'") or die(mysqli_error($conn));
		$nm = mysqli_num_rows($select);
		if($nm > 0){
			while ($dt = mysqli_fetch_array($select)) {
				$Consultant = $dt['Employee_Name'];
			}
		}else{
			$Consultant = '';
		}
	}elseif ($Clinic_ID > 0) {
		$select = mysqli_query($conn,"select Clinic_Name from tbl_clinic where Clinic_ID = '$Clinic_ID'") or die(mysqli_error($conn));
		$nm = mysqli_num_rows($select);
		if($nm > 0){
			while ($dt = mysqli_fetch_array($select)) {
				$Consultant = $dt['Clinic_Name'];
			}
		}else{
			$Consultant = '';
		}
	}
?>
<table width="100%">
	<tr>
		<td width="8%"><b>Check In Type</b></td>
		<td width="12%"><b>Patient Direction</b></td>
		<td width="15%"><b>Consultant</b></td>
		<td width="7%" style="text-align: left;">Item Name</td>
		<td width="8%" style="text-align: right;"><b>Price</b></td>
		<td width="8%" style="text-align: right;"><b>Discount</b></td>
		<td width="8%" style="text-align: center;"><b>Quantity</b></td>
	</tr>
	<tr>
		<td>
			<select name="Type_Of_Check_In" id="Type_Of_Check_In">
				<option <?php if(strtolower($Check_In_Type) == 'ipd services'){ echo "selected='selected'"; } ?>>IPD Services</option>
				<option <?php if(strtolower($Check_In_Type) == 'doctor room'){ echo "selected='selected'"; } ?>>Doctor Room</option>
				<option <?php if(strtolower($Check_In_Type) == 'dental'){ echo "selected='selected'"; } ?>>Dental</option>
				<option <?php if(strtolower($Check_In_Type) == 'dialysis'){ echo "selected='selected'"; } ?>>Dialysis</option>
				<option <?php if(strtolower($Check_In_Type) == 'dressing'){ echo "selected='selected'"; } ?>>Dressing</option>
				<option <?php if(strtolower($Check_In_Type) == 'ear'){ echo "selected='selected'"; } ?>>Ear</option>
				<option <?php if(strtolower($Check_In_Type) == 'laboratory'){ echo "selected='selected'"; } ?>>Laboratory</option>
				<option <?php if(strtolower($Check_In_Type) == 'matenity'){ echo "selected='selected'"; } ?>>Matenity</option>
				<option <?php if(strtolower($Check_In_Type) == 'optical'){ echo "selected='selected'"; } ?>>Optical</option>
				<option <?php if(strtolower($Check_In_Type) == 'procedure'){ echo "selected='selected'"; } ?>>Procedure</option>
				<option <?php if(strtolower($Check_In_Type) == 'physiotherapy'){ echo "selected='selected'"; } ?>>Physiotherapy</option>
				<option <?php if(strtolower($Check_In_Type) == 'radiology'){ echo "selected='selected'"; } ?>>Radiology</option>
				<option <?php if(strtolower($Check_In_Type) == 'theater'){ echo "selected='selected'"; } ?>>Theater</option>
			</select>
		</td>
		<td>
			<select id="Patient_Direction" id="Patient_Direction" onchange="getDoctor()">
				<option <?php if(strtolower($Patient_Direction) == 'others' || strtolower($Patient_Direction) == 'other'){ echo "selected='selected'"; }?>>Othes</option>
				<option <?php if(strtolower($Patient_Direction) == 'direct to doctor'){ echo "selected='selected'"; }?>>Direct To Doctor</option>
				<option <?php if(strtolower($Patient_Direction) == 'direct to doctor via nurse station'){ echo "selected='selected'"; }?>>Direct To Doctor Via Nurse Station</option>
				<option <?php if(strtolower($Patient_Direction) == 'direct to clinic'){ echo "selected='selected'"; }?>>Direct To Clinic</option>
				<option <?php if(strtolower($Patient_Direction) == 'direct to clinic via nurse station'){ echo "selected='selected'"; }?>>Direct To Clinic Via Nurse Station</option>
			</select>
		</td>
		<td id="Consultant_Area">
			<select name='Consultant' id='Consultant' required='required'>
				<option selected='selected'><?php echo $Consultant; ?></option>					
				<?php
					$Select_Consultant = "select * from tbl_clinic where Clinic_Status = 'Available'"; 
					$result = mysqli_query($conn,$Select_Consultant);
					while($row = mysqli_fetch_array($result)){
				?>
						<option><?php echo $row['Clinic_Name']; ?></option>
				<?php
					}
					$Select_Doctors = "select * from tbl_employee where employee_type = 'Doctor' and Account_Status = 'active'"; 
					$result = mysqli_query($conn,$Select_Doctors);
					while($row = mysqli_fetch_array($result)){
				?>
					    <option><?php echo ucwords(strtolower($row['Employee_Name'])); ?></option>
				<?php
					}
				?>
			</select>
		</td>
		<td width="30%">
			<input type="text" name="Pro_Name" id="Pro_Name" readonly="readonly" value="<?php echo $Product_Name; ?>">
			<input type="hidden" name="Pro_ID" id="Pro_ID" value="0">
		</td>
		<td>
			<input type="text" name="Price" id="Edited_Price" value="<?php echo number_format($Price); ?>" readonly="readonly" style="text-align: right;">
		</td>
		<td>
			<input type="text" name="Price" id="Edited_Discount" value="<?php echo $Discount; ?>" style="text-align: right;">
		</td>
		<td>
			<input type="text" name="Price" id="Edited_Quantity" value="<?php echo $Quantity; ?>" style="text-align: center;">
		</td>
	</tr>
	<tr>
		<td style="text-align: right;"><b>Billing Type&nbsp;&nbsp;&nbsp;</b></td>
		<td>
			<select name="Billing_Type" id="Billing_Type">
			<?php if(strtolower($Guarantor_Name) == 'cash'){ ?>
				<option <?php if(strtolower($Billing_Type) == 'inpatient cash'){ echo "selected='selected'"; } ?>>Inpatient Cash</option>
			<?php }else{ ?>
				<option <?php if(strtolower($Billing_Type) == 'outpatient credit'){ echo "selected='selected'"; } ?>>Outpatient Credit</option>
				<option <?php if(strtolower($Billing_Type) == 'inpatient credit'){ echo "selected='selected'"; } ?>>Inpatient Credit</option>
				<option <?php if(strtolower($Billing_Type) == 'inpatient cash'){ echo "selected='selected'"; } ?>>Inpatient Cash</option>
			<?php } ?>
			</select>
		</td>
		<td style="text-align: center;">
		<?php if(strtolower($Patient_Direction) == 'direct to doctor' || strtolower($Patient_Direction) == 'direct to doctor via nurse station'){ ?>
			<input type="button" name="Doctors_List" id="Doctors_List" value="SELECT DOCTOR" onclick="Get_Doctor()" class="art-button-green">
		<?php }else{ ?>
			<input type="button" name="Doctors_List" id="Doctors_List" value="SELECT DOCTOR" onclick="Get_Doctor()" style="Visibility: hidden;" class="art-button-green">
		<?php } ?>
		</td>
	</tr>
	<tr><td colspan="7"><hr></td></tr>
	<tr>
		<td colspan="7" style="text-align: right;">
			<input type="button" name="Item_Button" id="Item_Button" value="CHANGE ITEM" class="art-button-green" onclick="Open_Item_Dialogy(<?php echo $Sponsor_ID; ?>)">
			<input type="button" name="Submit" id="Submit" value="UPDATE" class="art-button-green" onclick="alert('Under construction');">
			<input type="button" name="Cancel" id="Cancel" value="CANCEL" class="art-button-green" onclick="Cancel_Edit_Process()">
		</td>
	</tr>
</table>
