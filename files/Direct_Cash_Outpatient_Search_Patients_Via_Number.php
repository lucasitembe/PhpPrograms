<?php
	session_start();
	include("./includes/connection.php");

	if(isset($_GET['Registration_ID'])){
		$Registration_Number = $_GET['Registration_ID'];
	}else{
		$Registration_Number = 0;
	}

	$Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
		$age ='';
    }
    $Title = '<tr><td colspan="7"><hr></tr>
				<tr>
		        	<td width="5%"><b>SN</b></td>
		            <td width="25%"><b>PATIENT NAME</b></td>
		            <td width="10%"><b>PATIENT NUMBER</b></td>
		            <td width="15%"><b>SPONSOR</b></td>
		            <td width="15%"><b>AGE</b></td>
		            <td width="8%"><b>GENDER</b></td>
		            <td width="10%"><b>MEMBER NUMBER</b></td>
		        </tr>
		        <tr><td colspan="7"><hr></tr>';
?>
<legend align="right"><b>DIRECT CASH OUTPATIENT ~ PATIENTS LIST</b></legend>
	<table width = "100%">
       <?php
       		echo $Title;
       		if($Registration_Number != 0 && $Registration_Number != '' && $Registration_Number != null){
       			$select = mysqli_query($conn,"select pr.Patient_Name, pr.Gender, pr.Date_Of_Birth, pr.Registration_ID, pr.Member_Number, sp.Sponsor_ID, sp.Guarantor_Name from
       									tbl_patient_registration pr, tbl_sponsor sp where
       									sp.Sponsor_ID = pr.Sponsor_ID and
       									pr.Registration_ID = '$Registration_Number' order by pr.Registration_Date_And_Time desc limit 20") or die(mysqli_error($conn));
       		}else{
       			$select = mysqli_query($conn,"select pr.Patient_Name, pr.Gender, pr.Date_Of_Birth, pr.Registration_ID, pr.Member_Number, sp.Sponsor_ID, sp.Guarantor_Name from
       									tbl_patient_registration pr, tbl_sponsor sp where
       									sp.Sponsor_ID = pr.Sponsor_ID order by pr.Registration_Date_And_Time desc limit 20") or die(mysqli_error($conn));
       		}
       		$nm = mysqli_num_rows($select);
       		if($nm > 0){
       			$temp = 0;
       			while ($data = mysqli_fetch_array($select)) {
       				$Registration_ID = $data['Registration_ID'];
       				//check if inpatient
       				$check = mysqli_query($conn,"select Registration_ID from tbl_admission where Registration_ID = '$Registration_ID' and admission_status IN ('Admitted','pending')") or die(mysqli_error($conn));
       				$num_check = mysqli_num_rows($check);
       				if($num_check == 0){

	       				$Date_Of_Birth = $data['Date_Of_Birth'];
	       				$date1 = new DateTime($Today);
						$date2 = new DateTime($Date_Of_Birth);
						$diff = $date1 -> diff($date2);
						$age = $diff->y." Years, ";
						$age .= $diff->m." Months, ";
						$age .= $diff->d." Days";
       	?>
	       				<tr>
				        	<td><a href='patientbillingdirectcash.php?Registration_ID=<?php echo $data['Registration_ID']; ?>&PatientBillingDirectCash=PatientBillingDirectCashThisPage' target='_parent' style='text-decoration: none;'><?php echo ++$temp; ?></a></td>
				            <td><a href='patientbillingdirectcash.php?Registration_ID=<?php echo $data['Registration_ID']; ?>&PatientBillingDirectCash=PatientBillingDirectCashThisPage' target='_parent' style='text-decoration: none;'><?php echo ucwords(strtolower($data['Patient_Name'])); ?></a></td>
				            <td><a href='patientbillingdirectcash.php?Registration_ID=<?php echo $data['Registration_ID']; ?>&PatientBillingDirectCash=PatientBillingDirectCashThisPage' target='_parent' style='text-decoration: none;'><?php echo $data['Registration_ID']; ?></a></td>
				            <td><a href='patientbillingdirectcash.php?Registration_ID=<?php echo $data['Registration_ID']; ?>&PatientBillingDirectCash=PatientBillingDirectCashThisPage' target='_parent' style='text-decoration: none;'><?php echo $data['Guarantor_Name']; ?></a></td>
				            <td><a href='patientbillingdirectcash.php?Registration_ID=<?php echo $data['Registration_ID']; ?>&PatientBillingDirectCash=PatientBillingDirectCashThisPage' target='_parent' style='text-decoration: none;'><?php echo $age; ?></a></td>
				            <td><a href='patientbillingdirectcash.php?Registration_ID=<?php echo $data['Registration_ID']; ?>&PatientBillingDirectCash=PatientBillingDirectCashThisPage' target='_parent' style='text-decoration: none;'><?php echo $data['Gender']; ?></a></td>
				            <td><a href='patientbillingdirectcash.php?Registration_ID=<?php echo $data['Registration_ID']; ?>&PatientBillingDirectCash=PatientBillingDirectCashThisPage' target='_parent' style='text-decoration: none;'><?php echo $data['Member_Number']; ?></a></td>
				        </tr>
       	<?php
       					if(($temp%21) == 0){ echo $Title; }
       				}
       			}
       		}
       ?>
	</tr>
</table>