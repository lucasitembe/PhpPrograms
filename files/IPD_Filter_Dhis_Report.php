<?php
	include("includes/connection.php");
	$myBody = '';
	if(isset($_GET['Month'])){
		$Month = mysqli_real_escape_string($conn,$_GET['Month']);
	}else{
		$Month = '00';
	}
	
	if(isset($_GET['Year'])){
		$Year = mysqli_real_escape_string($conn,$_GET['Year']);
	}else{
		$Year = '0000';
	}

	if(isset($_SESSION['userinfo'])){
		$Branch_ID = $_SESSION['userinfo']['Branch_ID'];
	}else{
		$Branch_ID = 0;
	}
	
	$select_setting = mysqli_query($conn,"select DHIS_Source_Report from tbl_system_configuration where Branch_ID = '$Branch_ID'") or die(mysqli_error($conn));
	$nm_check = mysqli_num_rows($select_setting);
	if($nm_check > 0){
		while ($dt = mysqli_fetch_array($select_setting)) {
			$DHIS_Source_Report = $dt['DHIS_Source_Report'];
		}

		if($DHIS_Source_Report == 'Provisional Diagnosis'){
			$diagnosis_type = 'provisional_diagnosis';
		}else{
			$diagnosis_type = 'diagnosis';
		}
	}else{
		$diagnosis_type = 'diagnosis';
	}
?>
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


	//generate month_caption
	if($Month =='01'){ $Month_Caption = 'Januari'; }
	else if($Month =='02'){ $Month_Caption = 'Februari'; }
	else if($Month =='03'){ $Month_Caption = 'Machi'; }
	else if($Month =='04'){ $Month_Caption = 'Aprili'; }
	else if($Month =='05'){ $Month_Caption = 'Mei'; }
	else if($Month =='06'){ $Month_Caption = 'Juni'; }
	else if($Month =='07'){ $Month_Caption = 'Julai'; }
	else if($Month =='08'){ $Month_Caption = 'Agosti'; }
	else if($Month =='09'){ $Month_Caption = 'Septemba'; }
	else if($Month =='10'){ $Month_Caption = 'Octoba'; }
	else if($Month =='11'){ $Month_Caption = 'Novemba'; }
	else if($Month =='12'){ $Month_Caption = 'Decemba'; }

	//generate start year
	$Start_Year = $Year.'-01-01';

	//generate start date....
	if($Month != '00' && $Month != null && $Month != '' && $Year != '0000' && $Year != null && $Year != ''){
		$Start_Date = $Year.'-'.$Month.'-'.'01 00:00:00';

	}else{
		$Start_Date = '0000-00-00 00:00:00';
	}
	
	//generate end date....
	$d = new DateTime($Start_Date); 
	$End_Date = $d->format( 'Y-m-t' ).' 23:59:59';

	//declare all category variables.......
	$Total_Age_Below_1_Month_Male = 0;
	$Total_Age_Below_1_Month_Female = 0;
	$Grand_Total_Age_Below_1_Month = 0;
	
	$Total_Age_Between_1_Month_But_Below_1_Year_Male = 0;
	$Total_Age_Between_1_Month_But_Below_1_Year_Female = 0;
	$Grand_Total_Age_Between_1_Month_But_Below_1_Year = 0;

	$Total_Age_Between_1_Year_But_Below_5_Year_Male = 0;
	$Total_Age_Between_1_Year_But_Below_5_Year_Female = 0;
	$Grand_Total_Age_Between_1_Year_But_Below_5_Year = 0;
	
	
	$Total_Five_Years_Or_Above_Male = 0;
	$Total_Five_Years_Or_Above_Female = 0;
	$Grand_Total_Five_Years_Or_Above = 0;

	$Total_Male = 0;
	$Total_Female = 0;
	$Grand_Total_Male = 0;
	$Grand_Total_Female = 0;
	
?>
	<table width="100%">
		<tr><td colspan=8><hr></td></tr>
		<tr>
			<td width="5%" style="text-align: center;"></td>
			<td style="text-align: center;"></td>
			<td width="13%" style="text-align: center;">
				<table width="100%">
					<tr><td colspan="3" style="text-align: center;"><span style="font-size: x-small;">Umri chini ya mwezi 1</span></td></tr>
				</table>
			</td>
			<td width="13%" style="text-align: center;">
				<table width="100%">
					<tr><td colspan="3" style="text-align: center;"><span style="font-size: x-small;">Umri mwezi 1 hadi umri chini ya mwaka 1</span></td></tr>
				</table>
			</td>
			<td width="13%" style="text-align: center;">
				<table width="100%">
					<tr><td colspan="3" style="text-align: center;"><span style="font-size: x-small;">Umri mwaka 1 hadi umri chini ya miaka 5</span></td></tr>
				</table>
			</td>
			<td width="13%" style="text-align: center;">
				<table width="100%">
					<tr><td colspan="3" style="text-align: center;"><span style="font-size: x-small;">Umri miaka 5 au zaidi</span></td></tr>
				</table>
			</td>
			<td width="13%" style="text-align: center;">
				<table width="100%">
					<tr><td colspan="3" style="text-align: center;"><span style="font-size: x-small;">Jumla Kuu</span></td></tr>
				</table>
			</td>
		</tr>
		<tr>
			<td width="5%" style="text-align: center;"><span style="font-size: x-small;">Na</span></td>
			<td style="text-align: center;"><span style="font-size: x-small;">Ugonjwa</span></td>
			<td width="13%" style="text-align: center;">
				<table width="100%">
					<tr>
						<td style="text-align: center;"><span style="font-size: x-small;">ME</span></td>
						<td style="text-align: center;"><span style="font-size: x-small;">KE</span></td>
						<td style="text-align: center;"><span style="font-size: x-small;">Jumla</span></td>
					</tr>
				</table>
			</td>
			<td width="13%" style="text-align: center;">
				<table width="100%">
					<tr>
						<td style="text-align: center;"><span style="font-size: x-small;">ME</span></td>
						<td style="text-align: center;"><span style="font-size: x-small;">KE</span></td>
						<td style="text-align: center;"><span style="font-size: x-small;">Jumla</span></td>
					</tr>
				</table>
			</td>
			<td width="13%" style="text-align: center;">
				<table width="100%">
					<tr>
						<td style="text-align: center;"><span style="font-size: x-small;">ME</span></td>
						<td style="text-align: center;"><span style="font-size: x-small;">KE</span></td>
						<td style="text-align: center;"><span style="font-size: x-small;">Jumla</span></td>
					</tr>
				</table>
			</td>
			<td width="13%" style="text-align: center;">
				<table width="100%">
					<tr>
						<td style="text-align: center;"><span style="font-size: x-small;">ME</span></td>
						<td style="text-align: center;"><span style="font-size: x-small;">KE</span></td>
						<td style="text-align: center;"><span style="font-size: x-small;">Jumla</span></td>
					</tr>
				</table>
			</td>
			<td width="13%" style="text-align: center;">
				<table width="100%">
					<tr>
						<td style="text-align: center;"><span style="font-size: x-small;">ME</span></td>
						<td style="text-align: center;"><span style="font-size: x-small;">KE</span></td>
						<td style="text-align: center;"><span style="font-size: x-small;">Jumla</span></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr><td colspan=8><hr></td></tr>		
<?php 
		//get total mahudhurio ya Ipd
		$Total_Ipd_Below_1_Month_Male = 0;
		$Total_Ipd_Below_1_Month_Female = 0;
		$Grand_Total_Ipd_Below_1_Month = 0;
		
		$Total_Ipd_Between_1_Month_But_Below_1_Year_Male = 0;
		$Total_Ipd_Between_1_Month_But_Below_1_Year_Female = 0;
		$Grand_Total_Ipd_Between_1_Month_But_Below_1_Year = 0;

		$Total_Ipd_Between_1_Year_But_Below_5_Year_Male = 0;
		$Total_Ipd_Between_1_Year_But_Below_5_Year_Female = 0;
		$Grand_Total_Ipd_Between_1_Year_But_Below_5_Year = 0;
		
		
		$Total_Ipd_Five_Years_Or_Above_Male = 0;
		$Total_Ipd_Five_Years_Or_Above_Female = 0;
		$Grand_Total__IpdFive_Years_Or_Above = 0;

		$Total_Ipd_Male = 0;
		$Total_Ipd_Female = 0;
		$Grand_Total_Ipd_Male = 0;
		$Grand_Total_Ipd_Female = 0;

		$select_IPD = mysqli_query($conn,"select pr.Registration_ID, pr.Date_Of_Birth, DATE(ad.Admission_Date_Time) as Admission_Date_Time, pr.Gender from 
							tbl_patient_registration pr, tbl_admission ad where
							ad.Registration_ID = pr.Registration_ID and
							ad.Admission_Date_Time between '$Start_Date' and '$End_Date' group by pr.Registration_ID") or die(mysqli_error($conn));
		$num_Ipd = mysqli_num_rows($select_IPD);
		//echo $num_Ipd; exit;
		if($num_Ipd > 0){
			while ($pdata = mysqli_fetch_array($select_IPD)) {
				$Admission_Date_Time = $pdata['Admission_Date_Time'];
				$Date_Of_Birth = $pdata['Date_Of_Birth'];
				$Gender = $pdata['Gender'];
				$date1 = new DateTime($Admission_Date_Time);
				$date2 = new DateTime($Date_Of_Birth);
				$diff = $date1 -> diff($date2);
				$Years = $diff->y;
				$Months = $diff->m;
				$Days = $diff->d;
				

				//Chini Ya Mwezi mmoja
				if($Years == 0 && $Months == 0 && strtolower($Gender) == 'male'){
					$Total_Ipd_Below_1_Month_Male++;
					$Grand_Total_Ipd_Below_1_Month++;
					$Total_Ipd_Male++;
				}

				if($Years == 0 && $Months == 0 && strtolower($Gender) == 'female'){
					$Total_Ipd_Below_1_Month_Female++;
					$Grand_Total_Ipd_Below_1_Month++;
					$Total_Ipd_Female++;	
				}

				//Mwezi mmoja hadi Chini Ya Mwaka mmoja
				if($Years == 0 && $Months >= 1 && strtolower($Gender) == 'male'){
					$Total_Ipd_Between_1_Month_But_Below_1_Year_Male++;
					$Grand_Total_Ipd_Between_1_Month_But_Below_1_Year++;
					$Total_Ipd_Male++;
				}

				if($Years == 0 && $Months >= 1 && strtolower($Gender) == 'female'){
					$Total_Ipd_Between_1_Month_But_Below_1_Year_Female++;
					$Grand_Total_Ipd_Between_1_Month_But_Below_1_Year++;
					$Total_Ipd_Female++;
				}

				//Mwaka mmoja hadi Chini Ya Miaka Mitano
				if(($Years >=1 && $Years < 5) && strtolower($Gender)=='male'){
					$Total_Ipd_Between_1_Year_But_Below_5_Year_Male++;
					$Grand_Total_Ipd_Between_1_Year_But_Below_5_Year++;
					$Total_Ipd_Male++;
				}

				if(($Years >= 1 && $Years < 5) && strtolower($Gender)=='female'){
					$Total_Ipd_Between_1_Year_But_Below_5_Year_Female++;
					$Grand_Total_Ipd_Between_1_Year_But_Below_5_Year++;
					$Total_Ipd_Female++;
				}

				//Miaka 5 na kuendelea
				if(($Years >=5) && strtolower($Gender)=='male'){
					$Total_Ipd_Five_Years_Or_Above_Male++;
					$Grand_Total__IpdFive_Years_Or_Above++;
					$Total_Ipd_Male++;
				}

				if(($Years >=5) && strtolower($Gender) == 'female'){
					$Total_Ipd_Five_Years_Or_Above_Female++;
					$Grand_Total__IpdFive_Years_Or_Above++;
					$Total_Ipd_Female++;
				}
			}
		}
	//display mahudhurio ya Ipd
?>
		<tr>
			<td width="5%" style="text-align: center;"><span style="font-size: x-small;">1</span></td>
			<td><span style="font-size: x-small;">Mahudhurio ya Wodini</span></td>
			<td width="13%" style="text-align: center;">
				<table width="100%">
					<tr>
						<td style="text-align: center;"><span style="font-size: x-small;"><?php echo $Total_Ipd_Below_1_Month_Male; ?></span></td>
						<td style="text-align: center;"><span style="font-size: x-small;"><?php echo $Total_Ipd_Below_1_Month_Female; ?></span></td>
						<td style="text-align: center;"><span style="font-size: x-small;"><?php echo $Grand_Total_Ipd_Below_1_Month; ?></span></td>
					</tr>
				</table>
			</td>
			<td width="13%" style="text-align: center;">
				<table width="100%">
					<tr>
						<td style="text-align: center;"><span style="font-size: x-small;"><?php echo $Total_Ipd_Between_1_Month_But_Below_1_Year_Male; ?></span></td>
						<td style="text-align: center;"><span style="font-size: x-small;"><?php echo $Total_Ipd_Between_1_Month_But_Below_1_Year_Female; ?></span></td>
						<td style="text-align: center;"><span style="font-size: x-small;"><?php echo $Grand_Total_Ipd_Between_1_Month_But_Below_1_Year; ?></span></td>
					</tr>
				</table>
			</td>
			<td width="13%" style="text-align: center;">
				<table width="100%">
					<tr>
						<td style="text-align: center;"><span style="font-size: x-small;"><?php echo $Total_Ipd_Between_1_Year_But_Below_5_Year_Male; ?></span></td>
						<td style="text-align: center;"><span style="font-size: x-small;"><?php echo $Total_Ipd_Between_1_Year_But_Below_5_Year_Female; ?></span></td>
						<td style="text-align: center;"><span style="font-size: x-small;"><?php echo $Grand_Total_Ipd_Between_1_Year_But_Below_5_Year; ?></span></td>
					</tr>
				</table>
			</td>
			<td width="13%" style="text-align: center;">
				<table width="100%">
					<tr>
						<td style="text-align: center;"><span style="font-size: x-small;"><?php echo $Total_Ipd_Five_Years_Or_Above_Male; ?></span></td>
						<td style="text-align: center;"><span style="font-size: x-small;"><?php echo $Total_Ipd_Five_Years_Or_Above_Female; ?></span></td>
						<td style="text-align: center;"><span style="font-size: x-small;"><?php echo $Grand_Total__IpdFive_Years_Or_Above; ?></span></td>
					</tr>
				</table>
			</td>
			<td width="13%" style="text-align: center;">
				<table width="100%">
					<tr>
						<td style="text-align: center;"><span style="font-size: x-small;"><?php echo $Total_Ipd_Male; ?></span></td>
						<td style="text-align: center;"><span style="font-size: x-small;"><?php echo $Total_Ipd_Female; ?></span></td>
						<td style="text-align: center;"><span style="font-size: x-small;"><?php echo ($Total_Ipd_Male + $Total_Ipd_Female); ?></span></td>
					</tr>
				</table>
			</td>
		</tr>
<?php
	

 	//get all disease groups
	$select_groups = mysqli_query($conn,"select * from tbl_disease_group where Ipd_Report = 'yes' order by Ipd_disease_Form_Id") or die(mysqli_error($conn));
	$group_num = mysqli_num_rows($select_groups);
	if($group_num > 0){
		//get details.....
		while($details = mysqli_fetch_array($select_groups)){
			$disease_group_id = $details['disease_group_id'];
			$disease_group_name = $details['disease_group_name'];
			$Gender_Type = $details['Gender_Type'];
			$Ipd_disease_Form_Id = $details['Ipd_disease_Form_Id'];
			$Age_Below_1_Month = $details['Age_Below_1_Month'];
			$Age_Between_1_Month_But_Below_1_Year = $details['Age_Between_1_Month_But_Below_1_Year'];
			$Age_Between_1_Year_But_Below_5_Year = $details['Age_Between_1_Year_But_Below_5_Year'];
			$Five_Years_Or_Below_Sixty_Years = $details['Five_Years_Or_Below_Sixty_Years'];
			$Age_60_Years_And_Above = $details['Age_60_Years_And_Above'];

			//get all disease assigned to disease group based on disease_group_id
			$select_diseases = mysqli_query($conn,"select * from tbl_disease_group_mapping where disease_group_id = '$disease_group_id'") or die(mysqli_error($conn));
			$disease_num = mysqli_num_rows($select_diseases);
			if($disease_num > 0){
				//get all final diagnoses based on disease ids selected
				while ($dise = mysqli_fetch_array($select_diseases)) {
					$disease_id = $dise['disease_id'];
					//count total - gender and age must control the process
					$select = mysqli_query($conn,"select pr.Registration_ID, pr.Date_Of_Birth, pr.Gender, DATE(wrd.Round_Disease_Date_And_Time) as Round_Disease_Date_And_Time
												from tbl_ward_round wr, tbl_ward_round_disease wrd, tbl_patient_registration pr where
												wr.Round_ID = wrd.Round_ID and
												pr.Registration_ID = wr.Registration_ID and
												wrd.diagnosis_type = '$diagnosis_type' and
												wrd.disease_id = '$disease_id' and
												wrd.Round_Disease_Date_And_Time between '$Start_Date' and '$End_Date'") or die(mysqli_error($conn));
					$num_total_details = mysqli_num_rows($select);
					if($num_total_details > 0){
						while($data = mysqli_fetch_array($select)){
							$Registration_ID = $data['Registration_ID'];
							//generate patient age (Should be Date of birth agains Disease_Consultation_Date)
							$Date_Of_Birth = $data['Date_Of_Birth'];
							$Gender = $data['Gender'];
							$Disease_Consultation_Date = $data['Round_Disease_Date_And_Time'];
							

							$date1 = new DateTime($Disease_Consultation_Date);
							$date2 = new DateTime($Date_Of_Birth);
							$diff = $date1 -> diff($date2);
							$Years = $diff->y;
							$Months = $diff->m;
							$Days = $diff->d;
							$Status = '';




							//echo $Disease_Consultation_Date.' - '.$Date_Of_Birth.' - '.$Registration_ID.' - '.$Years.'     <br/>';
							
							if($Years > 0){
							    $Status = "Age";
							    $age = $Years;
							}elseif($Months > 0 && $Years == 0){
							    $Status = "Month";
							    $age = $Months;
							}elseif($Years <= 0 && $Months <= 0){
							    $Status = "Days";
							    $age = $Days;
							}

							if(strtolower($Gender_Type) == 'both'){ //both male and female
								
								if(strtolower($Age_Below_1_Month) == 'yes'){
									if($Status == 'Days' && strtolower($Gender) == 'male'){
										$Total_Age_Below_1_Month_Male += 1;
										$Grand_Total_Age_Below_1_Month += 1;
										$Total_Male += 1;
										$Grand_Total_Male += 1;
									}else if($Status == 'Days' && strtolower($Gender) == 'female'){
										$Total_Age_Below_1_Month_Female += 1;
										$Grand_Total_Age_Below_1_Month += 1;
										$Total_Female += 1;
										$Grand_Total_Female += 1;
									}
								}else if(strtolower($Age_Below_1_Month) == 'no'){
									$Total_Age_Below_1_Month_Male = 'NULL';
									$Total_Age_Below_1_Month_Female = 'NULL';
									$Grand_Total_Age_Below_1_Month = 'NULL';
								}

								if(strtolower($Age_Between_1_Month_But_Below_1_Year) == 'yes'){
									if($Status == 'Months' && strtolower($Gender) == 'male'){
										$Total_Age_Between_1_Month_But_Below_1_Year_Male += 1;
										$Grand_Total_Age_Between_1_Month_But_Below_1_Year += 1;
										$Total_Male += 1;
										$Grand_Total_Male += 1;
									}else if($Status == 'Months' && strtolower($Gender) == 'female'){
										$Total_Age_Between_1_Month_But_Below_1_Year_Female += 1;
										$Grand_Total_Age_Between_1_Month_But_Below_1_Year += 1;
										$Total_Female += 1;
										$Grand_Total_Female += 1;
									}
								}else if(strtolower($Age_Between_1_Month_But_Below_1_Year) == 'no'){
									$Total_Age_Between_1_Month_But_Below_1_Year_Male = 'NULL';
									$Total_Age_Between_1_Month_But_Below_1_Year_Female = 'NULL';
									$Grand_Total_Age_Between_1_Month_But_Below_1_Year = 'NULL';
								}

								if(strtolower($Age_Between_1_Year_But_Below_5_Year) == 'yes'){
									if($Status == 'Age' && ($age >= 1 && $age < 5) && strtolower($Gender) == 'male'){
										$Total_Age_Between_1_Year_But_Below_5_Year_Male += 1;
										$Grand_Total_Age_Between_1_Year_But_Below_5_Year += 1;
										$Total_Male += 1;
										$Grand_Total_Male += 1;
									}else if ($Status == 'Age' && ($age >= 1 && $age < 5) && strtolower($Gender) == 'female') {
										$Total_Age_Between_1_Year_But_Below_5_Year_Female += 1;
										$Grand_Total_Age_Between_1_Year_But_Below_5_Year += 1;
										$Total_Female += 1;
										$Grand_Total_Female += 1;
									}
								}else if(strtolower($Age_Between_1_Year_But_Below_5_Year) == 'no'){
									$Total_Age_Between_1_Year_But_Below_5_Year_Male = 'NULL';
									$Total_Age_Between_1_Year_But_Below_5_Year_Female = 'NULL';
									$Grand_Total_Age_Between_1_Year_But_Below_5_Year = 'NULL';
								}

							}else if(strtolower($Gender_Type) == 'female only'){ //female only	
								
								if(strtolower($Age_Below_1_Month) == 'yes'){
									if($Status == 'Days' && strtolower($Gender) == 'female'){
										$Total_Age_Below_1_Month_Female += 1;
										$Grand_Total_Age_Below_1_Month += 1;
										$Total_Age_Below_1_Month_Male = 'NULL';
										$Total_Female += 1;
										$Grand_Total_Female += 1;
										$Total_Male = 'NULL';
									}else{
										$Total_Age_Below_1_Month_Male = 'NULL';
										$Total_Male = 'NULL';
									}
								}else if(strtolower($Age_Below_1_Month) == 'no'){
									$Total_Age_Below_1_Month_Male = 'NULL';
									$Total_Age_Below_1_Month_Female = 'NULL';
									$Grand_Total_Age_Below_1_Month = 'NULL';
									$Total_Male = 'NULL';
								}

								if(strtolower($Age_Between_1_Month_But_Below_1_Year) == 'yes'){
									if($Status == 'Months' && strtolower($Gender) == 'female'){
										$Total_Age_Between_1_Month_But_Below_1_Year_Female += 1;
										$Grand_Total_Age_Between_1_Month_But_Below_1_Year += 1;
										$Total_Age_Between_1_Month_But_Below_1_Year_Male = 'NULL';
										$Total_Female += 1;
										$Grand_Total_Female += 1;
										$Total_Male = 'NULL';
									}else{
										$Total_Age_Between_1_Month_But_Below_1_Year_Male = 'NULL';
										$Total_Male = 'NULL';
									}
								}else if(strtolower($Age_Between_1_Month_But_Below_1_Year) == 'no'){
									$Total_Age_Between_1_Month_But_Below_1_Year_Male = 'NULL';
									$Total_Age_Between_1_Month_But_Below_1_Year_Female = 'NULL';
									$Grand_Total_Age_Between_1_Month_But_Below_1_Year = 'NULL';
									$Total_Male = 'NULL';
								}

								if(strtolower($Age_Between_1_Year_But_Below_5_Year) == 'yes'){
									if ($Status == 'Age' && ($age >= 1 && $age < 5) && strtolower($Gender) == 'female') {
										$Total_Age_Between_1_Year_But_Below_5_Year_Female += 1;
										$Grand_Total_Age_Between_1_Year_But_Below_5_Year += 1;
										$Total_Age_Between_1_Year_But_Below_5_Year_Male = 'NULL';
										$Total_Female += 1;
										$Grand_Total_Female += 1;
										$Total_Male = 'NULL';
									}else{
										$Total_Age_Between_1_Year_But_Below_5_Year_Male = 'NULL';
										$Total_Male = 'NULL';
									}
								}else if(strtolower($Age_Between_1_Year_But_Below_5_Year) == 'no'){
									$Total_Age_Between_1_Year_But_Below_5_Year_Male = 'NULL';
									$Total_Age_Between_1_Year_But_Below_5_Year_Female = 'NULL';
									$Grand_Total_Age_Between_1_Year_But_Below_5_Year = 'NULL';
									$Total_Male = 'NULL';
								}

								if(strtolower($Five_Years_Or_Below_Sixty_Years) == 'yes'){
									if ($Status == 'Age' && ($age >= 5 && $age < 60) && strtolower($Gender) == 'female') {
										$Total_Five_Years_Or_Below_Sixty_Years_Female += 1;
										$Grand_Total_Five_Years_Or_Below_Sixty_Years += 1;
										$Total_Five_Years_Or_Below_Sixty_Years_Male = 'NULL';
										$Total_Female += 1;
										$Grand_Total_Female += 1;
										$Total_Male = 'NULL';
									}else{
										$Total_Five_Years_Or_Below_Sixty_Years_Male = 'NULL';
										$Total_Male = 'NULL';
									}
								}else if(strtolower($Five_Years_Or_Below_Sixty_Years) == 'no'){
									$Total_Five_Years_Or_Below_Sixty_Years_Male = 'NULL';
									$Total_Five_Years_Or_Below_Sixty_Years_Female = 'NULL';
									$Grand_Total_Five_Years_Or_Below_Sixty_Years = 'NULL';
									$Total_Male = 'NULL';
								}

								if(strtolower($Age_60_Years_And_Above) == 'yes'){
									if ($Status == 'Age' && $age >= 60 && strtolower($Gender) == 'female') {
										$Total_Age_60_Years_And_Above_Female += 1;
										$Grand_Total_Age_60_Years_And_Above += 1;
										$Total_Age_60_Years_And_Above_Male = 'NULL';
										$Total_Female += 1;
										$Grand_Total_Female += 1;
										$Total_Male = 'NULL';
									}else{
										$Total_Age_60_Years_And_Above_Male = 'NULL';
										$Total_Male = 'NULL';
									}
								}else if(strtolower($Age_60_Years_And_Above) == 'no'){
									$Total_Age_60_Years_And_Above_Male = 'NULL';
									$Total_Age_60_Years_And_Above_Female = 'NULL';
									$Grand_Total_Age_60_Years_And_Above = 'NULL';
									$Total_Male = 'NULL';
								}


							}else if(strtolower($Gender_Type) == 'male only'){ //male only
								if(strtolower($Age_Below_1_Month) == 'yes'){
									if($Status == 'Days' && strtolower($Gender) == 'male'){
										$Total_Age_Below_1_Month_Male += 1;
										$Grand_Total_Age_Below_1_Month += 1;
										$Total_Age_Below_1_Month_Female = 'NULL';
										$Total_Male += 1;
										$Grand_Total_Male += 1;
										$Total_Female = 'NULL';
									}else{
										$Total_Age_Below_1_Month_Female = 'NULL';
										$Total_Female = 'NULL';
									}
								}else if(strtolower($Age_Below_1_Month) == 'no'){
									$Total_Age_Below_1_Month_Male = 'NULL';
									$Total_Age_Below_1_Month_Female = 'NULL';
									$Grand_Total_Age_Below_1_Month = 'NULL';
									$Total_Female = 'NULL';
								}

								if(strtolower($Age_Between_1_Month_But_Below_1_Year) == 'yes'){
									if($Status == 'Months' && strtolower($Gender) == 'male'){
										$Total_Age_Between_1_Month_But_Below_1_Year_Male += 1;
										$Grand_Total_Age_Between_1_Month_But_Below_1_Year += 1;
										$Total_Age_Between_1_Month_But_Below_1_Year_Female = 'NULL';
										$Total_Male += 1;
										$Grand_Total_Male += 1;
										$Total_Female = 'NULL';
									}else{
										$Total_Age_Between_1_Month_But_Below_1_Year_Female = 'NULL';
										$Total_Female = 'NULL';
									}
								}else if(strtolower($Age_Between_1_Month_But_Below_1_Year) == 'no'){
									$Total_Age_Between_1_Month_But_Below_1_Year_Male = 'NULL';
									$Total_Age_Between_1_Month_But_Below_1_Year_Female = 'NULL';
									$Grand_Total_Age_Between_1_Month_But_Below_1_Year = 'NULL';
									$Total_Female = 'NULL';
								}

								if(strtolower($Age_Between_1_Year_But_Below_5_Year) == 'yes'){
									if ($Status == 'Age' && ($age >= 1 && $age < 5) && strtolower($Gender) == 'male') {
										$Total_Age_Between_1_Year_But_Below_5_Year_Male += 1;
										$Grand_Total_Age_Between_1_Year_But_Below_5_Year += 1;
										$Total_Age_Between_1_Year_But_Below_5_Year_Female = 'NULL';
										$Total_Male += 1;
										$Grand_Total_Male += 1;
										$Total_Female = 'NULL';
									}else{
										$Total_Age_Between_1_Year_But_Below_5_Year_Female = 'NULL';
										$Total_Female = 'NULL';
									}
								}else if(strtolower($Age_Between_1_Year_But_Below_5_Year) == 'no'){
									$Total_Age_Between_1_Year_But_Below_5_Year_Male = 'NULL';
									$Total_Age_Between_1_Year_But_Below_5_Year_Female = 'NULL';
									$Grand_Total_Age_Between_1_Year_But_Below_5_Year = 'NULL';
									$Total_Female = 'NULL';
								}

								if(strtolower($Five_Years_Or_Below_Sixty_Years) == 'yes'){
									if ($Status == 'Age' && ($age >= 5 && $age < 60) && strtolower($Gender) == 'male') {
										$Total_Five_Years_Or_Below_Sixty_Years_Male += 1;
										$Grand_Total_Five_Years_Or_Below_Sixty_Years += 1;
										$Total_Five_Years_Or_Below_Sixty_Years_Female = 'NULL';
										$Total_Male += 1;
										$Grand_Total_Male += 1;
										$Total_Female = 'NULL';
									}else{
										$Total_Five_Years_Or_Below_Sixty_Years_Female = 'NULL';
										$Total_Female = 'NULL';
									}
								}else if(strtolower($Five_Years_Or_Below_Sixty_Years) == 'no'){
									$Total_Five_Years_Or_Below_Sixty_Years_Male = 'NULL';
									$Total_Five_Years_Or_Below_Sixty_Years_Female = 'NULL';
									$Grand_Total_Five_Years_Or_Below_Sixty_Years = 'NULL';
									$Total_Female = 'NULL';
								}

								if(strtolower($Age_60_Years_And_Above) == 'yes'){
									if ($Status == 'Age' && $age >= 60 && strtolower($Gender) == 'male') {
										$Total_Age_60_Years_And_Above_Male += 1;
										$Grand_Total_Age_60_Years_And_Above += 1;
										$Total_Age_60_Years_And_Above_Female = 'NULL';
										$Total_Male += 1;
										$Grand_Total_Male += 1;
										$Total_Female = 'NULL';
									}else{
										$Total_Age_60_Years_And_Above_Female = 'NULL';
										$Total_Female = 'NULL';
									}
								}else if(strtolower($Age_60_Years_And_Above) == 'no'){
									$Total_Age_60_Years_And_Above_Male = 'NULL';
									$Total_Age_60_Years_And_Above_Female = 'NULL';
									$Grand_Total_Age_60_Years_And_Above = 'NULL';
									$Total_Female = 'NULL';
								}

							}
						}
					}
				}
			}
	
		echo '<tr>
			<td width="5%" style="text-align: center;"><span style="font-size: x-small;">'.$Ipd_disease_Form_Id.'</span></td>
			<td><span style="font-size: x-small;">'.$disease_group_name.'</span></td>
			<td width="13%" style="text-align: center;">
				<table width="100%">
					<tr>
						<td style="text-align: center;" width="33%" title="'.$disease_group_name.'  &#xA;Watoto wanaume umri chini ya mwezi 1"><span style="font-size: x-small;" >'.$Total_Age_Below_1_Month_Male.'</span></td>
						<td style="text-align: center;" width="33%" title="'.$disease_group_name.'&#xA;Watoto wanawake umri chini ya mwezi 1"><span style="font-size: x-small;">'.$Total_Age_Below_1_Month_Female.'</span></td>
						<td style="text-align: center;" width="33%" title="'.$disease_group_name.' &#xA;Jumla kuu watoto umri chini ya mwezi 1"><span style="font-size: x-small;">'.$Grand_Total_Age_Below_1_Month.'</span></td>
					</tr>
				</table>
			</td>
			<td width="13%" style="text-align: center;">
				<table width="100%">
					<tr>
						<td style="text-align: center;" width="33%" title="'.$disease_group_name.'  &#xA;Watoto wanaume umri mwezi 1 hadi chini ya mwaka 1"><span style="font-size: x-small;">'.$Total_Age_Between_1_Month_But_Below_1_Year_Male.'</span></td>
						<td style="text-align: center;" width="33%" title="'.$disease_group_name.'  &#xA;Watoto wanawake umri mwezi 1 hadi chini ya mwaka 1"><span style="font-size: x-small;">'.$Total_Age_Between_1_Month_But_Below_1_Year_Female.'</span></td>
						<td style="text-align: center;" width="33%" title="'.$disease_group_name.'  &#xA;Jumla kuu watoto umri mwezi 1 hadi chini ya mwaka 1"><span style="font-size: x-small;">'.$Grand_Total_Age_Between_1_Month_But_Below_1_Year.'</span></td>
					</tr>
				</table>
			</td>
			<td width="13%" style="text-align: center;">
				<table width="100%">
					<tr>
						<td style="text-align: center;" width="33%"  title="'.$disease_group_name.'  &#xA;Watoto wanaume umri mwaka 1 hadi chini ya miaka 5"><span style="font-size: x-small;">'.$Total_Age_Between_1_Year_But_Below_5_Year_Male.'</span></td>
						<td style="text-align: center;" width="33%"  title="'.$disease_group_name.'  &#xA;Watoto wanawake umri mwaka 1 hadi chini ya miaka 5"><span style="font-size: x-small;">'.$Total_Age_Between_1_Year_But_Below_5_Year_Female.'</span></td>
						<td style="text-align: center;" width="33%"  title="'.$disease_group_name.'  &#xA;Jumla kuu watoto umri mwaka 1 hadi chini ya miaka 5"><span style="font-size: x-small;">'.$Grand_Total_Age_Between_1_Year_But_Below_5_Year.'</span></td>
					</tr>
				</table>
			</td>
			<td width="13%" style="text-align: center;">
				<table width="100%">
					<tr>
						<td style="text-align: center;" width="33%" title="'.$disease_group_name.'  &#xA;Wanaume umri miaka 5 au zaidi"><span style="font-size: x-small;">'.$Total_Five_Years_Or_Above_Male.'</span></td>
						<td style="text-align: center;" width="33%" title="'.$disease_group_name.'  &#xA;Wanawake umri miaka 5 au zaidi"><span style="font-size: x-small;">'.$Total_Five_Years_Or_Above_Female.'</span></td>
						<td style="text-align: center;" width="33%" title="'.$disease_group_name.'  &#xA;Jumla kuu umri miaka 5 au zaidi"><span style="font-size: x-small;">'.$Grand_Total_Five_Years_Or_Above.'</span></td>
					</tr>
				</table>
			</td>
			<td width="13%" style="text-align: center;">
				<table width="100%">
					<tr>
						<td style="text-align: center;" width="33%" title="'.$disease_group_name.'  &#xA;Jumla kuu wanaume"><span style="font-size: x-small;">'.$Total_Male.'</span></td>
						<td style="text-align: center;" width="33%" title="'.$disease_group_name.'  &#xA;Jumla kuu wanawake"><span style="font-size: x-small;">'.$Total_Female.'</span></td>
						<td style="text-align: center;" width="33%" title="'.$disease_group_name.'  &#xA;Jumla kuu wanaume na wanawake"><span style="font-size: x-small;">'.($Total_Male + $Total_Female).'</span></td>
					</tr>
				</table>
			</td>
		</tr>';

			//reset...............
			$Total_Age_Below_1_Month_Male = 0;
			$Total_Age_Below_1_Month_Female = 0;
			$Grand_Total_Age_Below_1_Month = 0;
			
			$Total_Age_Between_1_Month_But_Below_1_Year_Male = 0;
			$Total_Age_Between_1_Month_But_Below_1_Year_Female = 0;
			$Grand_Total_Age_Between_1_Month_But_Below_1_Year = 0;

			$Total_Age_Between_1_Year_But_Below_5_Year_Male = 0;
			$Total_Age_Between_1_Year_But_Below_5_Year_Female = 0;
			$Grand_Total_Age_Between_1_Year_But_Below_5_Year = 0;
			
			
			$Total_Five_Years_Or_Below_Sixty_Years_Male = 0;
			$Total_Five_Years_Or_Below_Sixty_Years_Female = 0;
			$Grand_Total_Five_Years_Or_Below_Sixty_Years = 0;
			
			$Total_Age_60_Years_And_Above_Male = 0;
			$Total_Age_60_Years_And_Above_Female = 0;
			$Grand_Total_Age_60_Years_And_Above = 0;
			$Total_Male = 0;
			$Total_Female = 0;
		}
	}
	echo $myBody;
?>