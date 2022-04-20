<?php
	session_start();
	include("./includes/connection.php");
	if(isset($_GET['Clinic_ID'])){
		$Clinic_ID = $_GET['Clinic_ID'];
	}else{
		$Clinic_ID = '';
	}

	if(isset($_GET['date_From'])){
		$date_From = $_GET['date_From'];
	}else{
		$date_From = '';
	}

	if(isset($_GET['date_To'])){
		$date_To = $_GET['date_To'];
	}else{
		$date_To = '';
	}

	if(isset($_GET['Sponsor_ID'])){
		$Sponsor_ID = $_GET['Sponsor_ID'];
	}else{
		$Sponsor_ID = 0;
	}
	
?>
<legend align="right" style="background-color:#006400;color:white;padding:5px;" ><b>CLINICS PERFORMANCE REPORT</b></legend>
	<table width =100% style="border: 0">
		<tr>
            <td style='text-align:left; width:3%;border: 1px #ccc solid;'><b>SN</b></td>
            <td style='text-align:left; border: 1px #ccc solid;'><b>SPONSOR NAME</b></td>
            <td style='text-align:right; width:20%;border: 1px #ccc solid;'><b>CONSULTED PATIENTS</b></td>
            <td style='text-align:right; width:20%;border: 1px #ccc solid;'><b>NON-CONSULTED PATIENTS</b></td>
			<td style='text-align:right; width:15%;border: 1px #ccc solid;'><b>TOTAL PATIENTS</b></td>
		</tr>
		<tr><td colspan=5 style='border: 1px #ccc solid'><hr></td></tr>
		<?php
			$temp = 0;
			if($Sponsor_ID == 0){
				$sql = "select Sponsor_ID, Guarantor_Name from tbl_sponsor order by Guarantor_Name";
			}else{
				$sql = "select Sponsor_ID, Guarantor_Name from tbl_sponsor where Sponsor_ID = '$Sponsor_ID'";
			}
			$select = mysqli_query($conn,$sql) or die(mysqli_error($conn));
			$num = mysqli_num_rows($select);
			if($num > 0){
				while ($data = mysqli_fetch_array($select)) {
					$Total_Consulted = 0;
					$Sponsor_ID = $data['Sponsor_ID'];
					$Guarantor_Name = $data['Guarantor_Name'];
					//get details
					if($Clinic_ID == 0){
						$sql2 = "select ppl.Consultant_ID, pp.Check_In_ID  from tbl_patient_payments pp, tbl_patient_payment_item_list ppl where
													pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
													pp.Sponsor_ID = '$Sponsor_ID' and
													(ppl.Patient_Direction = 'Direct To Clinic' or ppl.Patient_Direction = 'Direct To Clinic Via Nurse Station') and
													pp.Payment_Date_And_Time between '$date_From' and '$date_To'
													group by pp.Registration_ID, pp.Check_In_ID, ppl.Consultant_ID";
					}else{
						$sql2 = "select ppl.Consultant_ID, pp.Check_In_ID from tbl_patient_payments pp, tbl_patient_payment_item_list ppl where
													pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
													pp.Sponsor_ID = '$Sponsor_ID' and
													ppl.Consultant_ID = '$Clinic_ID' and
													(ppl.Patient_Direction = 'Direct To Clinic' or ppl.Patient_Direction = 'Direct To Clinic Via Nurse Station') and
													pp.Payment_Date_And_Time between '$date_From' and '$date_To'
													group by pp.Registration_ID, pp.Check_In_ID, ppl.Consultant_ID";
					}
					$get_details = mysqli_query($conn,$sql2) or die(mysqli_error($conn));
					$no = mysqli_num_rows($get_details);
					if($no > 0){
						while ($data = mysqli_fetch_array($get_details)) {
							$Control_Values = '';
							$Consultant_ID = $data['Consultant_ID'];
							$Check_In_ID = $data['Check_In_ID'];
							$get_itm = mysqli_query($conn,"select Patient_Payment_Item_List_ID from
													tbl_patient_payment_item_list ppl, tbl_patient_payments pp where
													ppl.Patient_Payment_ID = pp.Patient_Payment_ID and
													ppl.Consultant_ID = '$Consultant_ID' and
													pp.Check_In_ID = '$Check_In_ID'") or die(mysqli_error($conn));
							$numz = mysqli_num_rows($get_itm);
							if($numz > 0){
								while ($row = mysqli_fetch_array($get_itm)) {
									$Control_Values .= ','.$row['Patient_Payment_Item_List_ID'];
								}
								$Filter_Value = substr($Control_Values, 1);
								//check if served or not
								$verify = mysqli_query($conn,"select consultation_ID from tbl_consultation where Patient_Payment_Item_List_ID IN ($Filter_Value)") or die(mysqli_error($conn));
								$verify_num = mysqli_num_rows($verify);
								if($verify_num > 0){
									$Total_Consulted += 1;
								}
							}
						}
					}
		?>
					<tr>
			            <td style='text-align:left; width:3%;border: 1px #ccc solid;'><?php echo ++$temp; ?><b>.</b></td>
			            <td style='text-align:left; border: 1px #ccc solid;'><?php echo $Guarantor_Name; ?></td>
			            <td style='text-align:right; width:20%;border: 1px #ccc solid;'><?php echo $Total_Consulted; ?></td>
			            <td style='text-align:right; width:20%;border: 1px #ccc solid;'><?php echo ($no - $Total_Consulted); ?></td>
						<td style='text-align:right; width:15%;border: 1px #ccc solid;'><?php echo $no; ?></td>
					</tr>
		<?php
				}
			}
		?>
		<tr><td colspan="5"><hr></td></tr>
	</table>