<?php
	session_start();
	include("./includes/connection.php");

	if(isset($_GET['Patient_Bill_ID'])){
		$Patient_Bill_ID = $_GET['Patient_Bill_ID'];
	}else{
		$Patient_Bill_ID = '';
	}

	if(isset($_GET['Folio_Number'])){
		$Folio_Number = $_GET['Folio_Number'];
	}else{
		$Folio_Number = '';
	}

	if(isset($_GET['Sponsor_ID'])){
		$Sponsor_ID = $_GET['Sponsor_ID'];
	}else{
		$Sponsor_ID = '';
	}

	if(isset($_GET['Check_In_ID'])){
		$Check_In_ID = $_GET['Check_In_ID'];
	}else{
		$Check_In_ID = '';
	}

	if(isset($_GET['Registration_ID'])){
		$Registration_ID = $_GET['Registration_ID'];
	}else{
		$Registration_ID = '';
	}

	//get patient details
	$select = mysqli_query($conn,"select pr.Patient_Name, pr.Registration_ID, pr.Gender, pr.Date_Of_Birth, pr.Member_Number, sp.Guarantor_Name
							from tbl_patient_registration pr, tbl_sponsor sp where
							sp.Sponsor_ID = pr.Sponsor_ID and
							pr.Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
	$nm = mysqli_num_rows($select);
	if($nm > 0){
		while ($dt = mysqli_fetch_array($select)) {
			$Patient_Name = $dt['Patient_Name'];
			$Guarantor_Name = $dt['Guarantor_Name'];
			$Member_Number = $dt['Member_Number'];
		}
	}
?>
<table width="100%">
	<tr>
		<td width="33%"><b>Patient Name &nbsp;&nbsp;&nbsp;</b><?php echo ucwords(strtolower($Patient_Name)); ?></td>
		<td width="33%"><b>Patient Number &nbsp;&nbsp;&nbsp;</b><?php echo $Registration_ID; ?></td>
		<td><b>Sponsor Name &nbsp;&nbsp;&nbsp;</b><?php echo strtoupper($Guarantor_Name); ?></td>
		<td><b>Folio Number &nbsp;&nbsp;&nbsp;</b><?php echo $Folio_Number; ?></td>
	</tr><td colspan="4"><hr></td><tr>
	</tr>
</table><br/>
<fieldset style='overflow-y: scroll; height: 280px; background-color: white;'>
<?php 
	$Title = '<tr>
				<td width="5%"><b>SN</b></td>
				<td><b>ITEM NAME</b></td>
				<td width="20%" style="text-align: left;"><b>RECEIPT NUMBER</b></td>
				<td width="20%" style="text-align: left;"><b>RECEIPT DATE</b></td>
				<td width="20%" style="text-align: right;"><b>AMOUNT PAID</b></td>
			</tr>
			<tr><td colspan="5"><hr></td></tr>';
?>
<table width="100%">
	<tr><td>
<?php
	$Grand_Total = 0;
	$select = mysqli_query($conn,"select i.Product_Name, ppl.Item_Name, ppl.Price, ppl.Quantity, ppl.Discount, pp.Patient_Payment_ID, pp.Payment_Date_And_Time from 
							tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_items i where
							pp.Transaction_type = 'direct cash' and
							ppl.Item_ID = i.Item_ID and
							pp.Transaction_status <> 'cancelled' and
							pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
							pp.Patient_Bill_ID = '$Patient_Bill_ID' and
							pp.Folio_Number = '$Folio_Number' and
							pp.Check_In_ID = '$Check_In_ID' and
							pp.Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
	$num = mysqli_num_rows($select);
	if($num > 0){
		$temp = 0;
		echo $Title;
		while ($data = mysqli_fetch_array($select)) {
?>
			<tr>
				<td><b><?php echo ++$temp; ?>.</b></td>
				<td><?php echo ucwords(strtolower($data['Product_Name'])).' ~ '.$data['Item_Name']; ?></td>
				<td style="text-align: left;"><?php echo $data['Patient_Payment_ID']; ?></td>
				<td style="text-align: left;"><?php echo $data['Payment_Date_And_Time']; ?></td>
				<td style="text-align: right;"><?php echo number_format(($data['Price'] - $data['Discount']) * $data['Quantity']); ?></td>
			</tr>
<?php
		$Grand_Total += (($data['Price'] - $data['Discount']) * $data['Quantity']);
		}
		echo "<tr><td colspan='5'><hr></td></tr>";
		echo "<tr><td colspan='4' style='text-align: right;'><b>GRAND TOTAL</b></td><td style='text-align: right;'><b>".number_format($Grand_Total)."</b></td></tr>";
		echo "<tr><td colspan='5'><hr></td></tr>";
	}else{
		echo "<center>
				<br/><br/><br/><br/>
				<b>NO TRANSACTIONS FOUND!</b>
				</center>";
	}
?>
</table>

</fieldset>