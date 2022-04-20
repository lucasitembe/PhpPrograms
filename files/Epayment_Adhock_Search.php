<?php
	include("./includes/connection.php");
	if(isset($_GET['Payment_Code'])){
		$Payment_Code = $_GET['Payment_Code'];
	}else{
		$Payment_Code = '';
	}
        
        

	$select = mysqli_query($conn,"select pp.Patient_Payment_ID, pr.Patient_Name, pr.Date_Of_Birth, pr.Gender, pr.Phone_Number, pr.Registration_ID, sp.Guarantor_Name
							from tbl_patient_payments pp, tbl_patient_registration pr, tbl_sponsor sp where
							pp.Registration_ID = pr.Registration_ID and
							pp.Sponsor_ID = sp.Sponsor_ID and
							pp.Payment_Code = '$Payment_Code' ORDER BY Patient_Payment_ID DESC LIMIT 1") or die(mysqli_error($conn));
	$num = mysqli_num_rows($select);
        
        if(isset($_GET['src']) && $_GET['src']=='receipt'){
            $data = mysqli_fetch_array($select) ;
	    $Patient_Payment_ID = $data['Patient_Payment_ID'];
            //echo "ioooii";
            echo $Patient_Payment_ID;
            exit;
        }
        
	if($num > 0){
?>
<?php
    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
		$age = '';
    }
?>

<legend><b>ePAYMENT AdHOC SEARCH</b></legend>
	<table width="100%">
		<tr><td colspan="10"><hr></td></tr>
		<tr>
			<td width="3%"><b>SN</b></td>
			<td><b>PATIENT NAME</b></td>
			<td width="10%"><b>PATIENT NUMBER</b></td>
			<td width="9%"><b>SPONSOR NAME</b></td>
			<td width="13%"><b>AGE</b></td>
			<td width="8%"><b>GENDER</b></td>
			<td width="12%"><b>PHONE NUMBER</b></td>
			<td width="8%"><b>RECEIPT#</b></td>
			<td width="6%" style="text-align: right;"><b>AMOUNT</b>&nbsp;&nbsp;&nbsp;&nbsp;</td>
			<td width="7%"><b>ACTION</b></td>
		</tr>
		<tr><td colspan="10"><hr></td></tr>

<?php
		$temp = 0;
		while ($data = mysqli_fetch_array($select)) {
			$Patient_Payment_ID = $data['Patient_Payment_ID'];
			$Grand_Total = 0;
			//calculate patient age
			$date1 = new DateTime($Today);
			$date2 = new DateTime($data['Date_Of_Birth']);
			$diff = $date1 -> diff($date2);
			$age = $diff->y." Years, ";
			$age .= $diff->m." Months, ";
			$age .= $diff->d." Days";

			//calculate total
			$cal_total = mysqli_query($conn,"select Price, Discount, Quantity from tbl_patient_payment_item_list where Patient_Payment_ID = '$Patient_Payment_ID'") or die(mysqli_error($conn));
			$nm = mysqli_num_rows($cal_total);
			if($nm > 0){
				while ($row = mysqli_fetch_array($cal_total)) {
					$Grand_Total += (($row['Price'] - $row['Discount']) * $row['Quantity']);
				}
			}
?>
			<tr>
				<td><?php echo ++$temp; ?><b>.</b></td>
				<td><?php echo ucwords(strtolower($data['Patient_Name'])); ?></td>
				<td><?php echo $data['Registration_ID']; ?></td>
				<td><?php echo $data['Guarantor_Name']; ?></td>
				<td><?php echo $age; ?></td>
				<td><?php echo $data['Gender']; ?></td>
				<td><?php echo $data['Phone_Number']; ?></td>
				<td><?php echo $data['Patient_Payment_ID']; ?></td>
				<td style="text-align: right;"><?php echo number_format($Grand_Total); ?>&nbsp;&nbsp;&nbsp;&nbsp;</td>
				<td style="text-align: center;">
					<input type="button" class="art-button-green" value="PREVIEW" onclick="Print_Receipt_Payment(<?php echo $data['Patient_Payment_ID']; ?>)">
				</td>
			</tr>
<?php
		}

?>
	</table>
<?php
	}else{
?>
	<legend><b>ePAYMENT AdHOC SEARCH</b></legend>
	<br/><br/><br/><br/><br/><center><h3><b>NO TRANSACTIONS FOUND</b></h3></center>
<?php
	}
?>
