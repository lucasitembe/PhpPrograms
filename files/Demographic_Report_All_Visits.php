<?php
	session_start();
	include("./includes/connection.php");
	
	if(isset($_GET['Region_ID'])){
		$Region_ID = $_GET['Region_ID'];
	}else{
		$Region_ID = 0;
	}

	if(isset($_GET['District_ID'])){
		$District_ID = $_GET['District_ID'];
	}else{
		$District_ID = 0;
	}

	if(isset($_GET['Date_From'])){
		$Date_From = $_GET['Date_From'];
	}else{
		$Date_From = '';
	}

	if(isset($_GET['Date_To'])){
		$Date_To = $_GET['Date_To'];
	}else{
		$Date_To = '';
	}

	if(isset($_GET['Age_From'])){
		$Age_From = $_GET['Age_From'];
	}else{
		$Age_From = 0;
	}
	
	if(isset($_GET['Age_To'])){
		$Age_To = $_GET['Age_To'];
	}else{
		$Age_To = 0;
	}
	$Employee_ID = $_GET['Employee_ID'];
	if(isset($_GET['agetype'])){
		$agetype = $_GET['agetype'];
	}else{
		$agetype = '';
	}
	if(isset($_GET['visit_type'])){
		$visit_type = $_GET['visit_type'];
	}else{
		$visit_type = '';
	}
	if(isset($_GET['Type_Of_Check_In'])){
		$Type_Of_Check_In = $_GET['Type_Of_Check_In'];
	}else{
		$Type_Of_Check_In = '';
	}
	
	$filter ='';
	$filterdaterange='';
	if($Region_ID != 'All'){
		$filter = " AND Region='$Region_ID'";
	}
	if($District_ID != 'All' && $District_ID != 0){
		$filter .= "  AND District='$District_ID'";
	}
	if($visit_type != 'All' ){
		$filter .= " AND visit_type='$visit_type'";
	}
	if($Type_Of_Check_In != 'All'){
		$filter .= " AND Type_Of_Check_In='$Type_Of_Check_In'";
	}
	if($Employee_ID != ''){
		$filter .=" AND ci.Employee_ID='$Employee_ID'";
	}
	if(isset($_GET['Clinic_ID'])){
		$Clinic_ID = $_GET['Clinic_ID'];
	}else{
		$Clinic_ID = '';
	}
	if($Clinic_ID != 'All'){
		$filter .=" AND Clinic_ID='$Clinic_ID' ";
	}
	$filterAge='';
	if($Age_From!='' && $Age_To !=''){
		$filterAge =" AND TIMESTAMPDIFF($agetype ,pr.Date_Of_Birth,CURDATE()) BETWEEN '".$Age_From."' AND '".$Age_To."' AND ci.Check_In_Date_And_Time between '$Date_From' and '$Date_To' ";
	}
	
	//&& $visit_type !='All' && $District_ID != 'All' && $Region_ID != 'All'
	// die($filter);
	$Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
    }

	//generate 
    $select = mysqli_query($conn,"SELECT min(Check_In_ID) as Check_In_ID from tbl_check_in where Check_In_Date_And_Time between '$Date_From' and '$Date_To'") or die(mysqli_error($conn));
    $num = mysqli_num_rows($select);
    if($num > 0){
    	while ($data = mysqli_fetch_array($select)) {
    		$Initial_Check_In_ID = $data['Check_In_ID'];
    	}
    }else{
    	$Initial_Check_In_ID = 0;
    }
?>
<legend align="center" style="background-color:#006400;color:white;padding:5px;" ><b>DEMOGRAPHIC VISITS BY SPONSOR  REPORT</b></legend>
	<table width =100% style="border: 0">
		<tr>
            <td style='text-align:left; width:3%;border: 1px #ccc solid;'><b>SN</b></td>
            <td style='text-align:left; width:3%;border: 1px #ccc solid;'><b>SPONSOR NAME</b></td>
            <td style='text-align:right; width:3%;border: 1px #ccc solid;'><b>MALE</b></td>
            <td style='text-align:right; width:3%;border: 1px #ccc solid;'><b>FEMALE</b></td>
			<td style='text-align:right; width:3%;border: 1px #ccc solid;'><b>TOTAL</b></td>
		</tr>
		<tr><td colspan=5 style='border: 1px #ccc solid'><hr></td></tr>
		<?php
			$temp = 0;
			$Total_Male = 0;
			$Total_Female = 0;
			$Grand_Total = 0;
			$Grand_Total_Male = 0;
			$Grand_Total_Female = 0;
			$mvst = 0;
			$mnvst = 0;
			$fvst = 0;
			$fnvst = 0;

			$select = mysqli_query($conn,"select Sponsor_ID, Guarantor_Name from tbl_sponsor order by Guarantor_Name") or die(mysqli_error($conn));
			$num = mysqli_num_rows($select);
			if($num > 0){
				while ($data = mysqli_fetch_array($select)) {
					//get all new visits based on selected sponsor
					$Sponsor_ID = $data['Sponsor_ID'];
						//THIS IS FOR KCMC
					$get_patients = mysqli_query($conn,"SELECT Date_Of_Birth, Gender, pr.Registration_ID from tbl_check_in ci, tbl_patient_registration pr, tbl_patient_payment_item_list ptl,	tbl_patient_payments pp where ptl.Patient_payment_ID=pp.Patient_payment_ID AND ci.Check_In_ID = pp.Check_In_ID AND ptl.Check_In_Type = 'Doctor Room' AND	ci.Registration_ID = pr.Registration_ID and 	pr.Sponsor_ID = '$Sponsor_ID'  $filter $filterAge") or die(mysqli_error($conn));
						
					//HAJI DODOMA HOSP ANATAKA WALIOKUWA CHECKED IN TU DOESNT MATTER KM AMEONWA NA DOCTOR===>
					// $get_patients = mysqli_query($conn,"SELECT Date_Of_Birth, Gender, pr.Registration_ID from tbl_check_in ci, tbl_patient_registration pr  where ci.Registration_ID = pr.Registration_ID and 	pr.Sponsor_ID = '$Sponsor_ID'  $filter $filterAge") or die(mysqli_error($conn));
					$no = mysqli_num_rows($get_patients);
					
					if($no > 0){
						while ($row = mysqli_fetch_array($get_patients)) {
							$Date_Of_Birth = $row['Date_Of_Birth'];
							//generate age
							$date1 = new DateTime($Today);
							$date2 = new DateTime($Date_Of_Birth);
							$diff = $date1 -> diff($date2);
							$age = $diff->y;

							$Gender = $row['Gender'];
							$Registration_ID = $row['Registration_ID'];

							//if($age >= $Age_From && $age <= $Age_To){
								if(strtolower($Gender) == 'male'){
									$Total_Male += 1;
									$Grand_Total_Male += 1;
								}else if(strtolower($Gender) == 'female'){
									$Total_Female += 1;
									$Grand_Total_Female += 1;
								}
							//}
							
																			
						}
					}
		?>
					<tr>
						<td><?php echo ++$temp; ?></td>
						<td>
							<label onclick="Display_Details('<?php echo $Region_ID; ?>','<?php echo $District_ID; ?>','<?php echo $Date_From; ?>','<?php echo $Date_To; ?>','<?php echo $Age_From; ?>','<?php echo $Age_To; ?>','<?php echo $visit_type;?>','<?php echo $Type_Of_Check_In; ?>','<?php echo $agetype;?>' <?php echo $Employee_ID;?>,'<?php echo $data['Sponsor_ID']; ?>', '<?php echo $Clinic_ID; ?>')">
								<?php echo $data['Guarantor_Name']; ?>
							</label>
						</td>
						<td style="text-align: right;">
							<label onclick="Display_Details('<?php echo $Region_ID; ?>','<?php echo $District_ID; ?>','<?php echo $Date_From; ?>','<?php echo $Date_To; ?>','<?php echo $Age_From; ?>','<?php echo $Age_To; ?>','<?php echo $visit_type;?>','<?php echo $Type_Of_Check_In; ?>','<?php echo $agetype;?>' <?php echo $Employee_ID;?>,'<?php echo $data['Sponsor_ID']; ?>', '<?php echo $Clinic_ID; ?>')">
								<?php echo $Total_Male; ?>
							</label>
						</td>
						<td style="text-align: right;">
							<label onclick="Display_Details('<?php echo $Region_ID; ?>','<?php echo $District_ID; ?>','<?php echo $Date_From; ?>','<?php echo $Date_To; ?>','<?php echo $Age_From; ?>','<?php echo $Age_To; ?>','<?php echo $visit_type;?>','<?php echo $Type_Of_Check_In; ?>','<?php echo $agetype;?>' <?php echo $Employee_ID;?>,'<?php echo $data['Sponsor_ID']; ?>', '<?php echo $Clinic_ID; ?>')">
								<?php echo $Total_Female; ?>
							</label>
						</td>
						<td style="text-align: right;">
							<label onclick="Display_Details('<?php echo $Region_ID; ?>','<?php echo $District_ID; ?>','<?php echo $Date_From; ?>','<?php echo $Date_To; ?>','<?php echo $Age_From; ?>','<?php echo $Age_To; ?>','<?php echo $visit_type;?>','<?php echo $Type_Of_Check_In; ?>','<?php echo $agetype;?>' <?php echo $Employee_ID;?>,'<?php echo $data['Sponsor_ID']; ?>', '<?php echo $Clinic_ID; ?>')">
								<?php echo $Total_Male+$Total_Female; ?>
							</label>
						</td>
					</tr>
		<?php
					$Total_Male = 0;
					$Total_Female = 0;
					$Grand_Total = 0;
				}
		?>
				<tr><td colspan=5 style='border: 1px #ccc solid'><hr></td></tr>
				<tr>
					<td colspan="2" style="text-align: left;"><b>TOTAL</b></td>
					<td style="text-align: right;"><?php echo number_format($Grand_Total_Male); ?></td>
					<td style="text-align: right;"><?php echo number_format($Grand_Total_Female); ?></td>
					<td style="text-align: right;"><?php echo number_format($Grand_Total_Male + $Grand_Total_Female); ?></td>
				</tr>
				<tr><td colspan=5 style='border: 1px #ccc solid'><hr></td></tr>
		<?php
			}
		?>
	</table>