<?php
	include("./includes/connection.php");
	if(isset($_GET['Payment_Code'])){
		$Payment_Code = mysqli_real_escape_string($conn,$_GET['Payment_Code']);
	}else{
		$Payment_Code = '';
	}


	if(isset($_GET['Amount_Paid'])){
		//check if exists
		$check = mysqli_query($conn,"select Registration_ID, Amount_Required from tbl_bank_transaction_cache where Payment_Code = '$Payment_Code'") or die(mysqli_error($conn));
		$num = mysqli_num_rows($check);
		if($num > 0){
			while ($data = mysqli_fetch_array($check)) {
				$Registration_ID = $data['Registration_ID'];
				$Amount_Required = $data['Amount_Required'];
			}

			//get patient details
			$select_details = mysqli_query($conn,"select Patient_Name from tbl_patient_registration where Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
			$no = mysqli_num_rows($select_details);
			if($no > 0){
				while ($row = mysqli_fetch_array($select_details)) {
					$Patient_Name = $row['Patient_Name'];
				}
			}else{
				$Patient_Name = 'NOT FOUND';
			}
		}else{
			$Registration_ID = 'NOT FOUND';
			$Amount_Required = 'NOT FOUND';
			$Patient_Name = 'NOT FOUND';
		}
	}else{
		//check if exists
		$check = mysqli_query($conn,"select Registration_ID, Amount_Required from tbl_bank_transaction_cache where Payment_Code = '$Payment_Code'") or die(mysqli_error($conn));
		$num = mysqli_num_rows($check);
		if($num > 0){
			while ($data = mysqli_fetch_array($check)) {
				$Registration_ID = $data['Registration_ID'];
				$Amount_Required = $data['Amount_Required'];
			}

			//get patient details
			$select_details = mysqli_query($conn,"select Patient_Name from tbl_patient_registration where Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
			$no = mysqli_num_rows($select_details);
			if($no > 0){
				while ($row = mysqli_fetch_array($select_details)) {
					$Patient_Name = $row['Patient_Name'];
				}
			}else{
				$Patient_Name = 'NOT FOUND';
			}
		}else{
			$Registration_ID = 'NOT FOUND';
			$Amount_Required = 'NOT FOUND';
			$Patient_Name = 'NOT FOUND';
		}
	}
	//echo $Registration_ID.'<br/>';
?>

<?php
	$xml = new DOMDocument("1.0");
	
	$root = $xml->createElement("data");
	$xml->appendChild($root);
	
	$Reg_ID = $xml->createElement("Patient_Number");
	$RegText = $xml->createTextNode($Registration_ID);
	$Reg_ID->appendChild($RegText);

	$P_Name = $xml->createElement("Patient_Name");
	$P_Text = $xml->createTextNode($Patient_Name);
	$P_Name->appendChild($P_Text);

	$A_Paid = $xml->createElement("Amount_Required");
	$A_Text = $xml->createTextNode($Amount_Required);
	$A_Paid->appendChild($A_Text);

	$root->appendChild($Reg_ID);
	$root->appendChild($P_Name);
	$root->appendChild($A_Paid);

	$xml->formatOutput = true;
	echo "<xmp>".$xml->saveXML()."</xmp>";
?>