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
	$Total_Age_Below_5_Years_Male = 0;
	$Total_Age_Below_5_Years_Female = 0;
	$Grand_Total_Age_Below_5_Years = 0;
	
	$Total_Age_Between_5_Years_But_Below_15_Years_Male = 0;
	$Total_Age_Between_5_Years_But_Below_15_Years_Female = 0;
	$Grand_Total_Age_Between_5_Years_But_Below_15_Years = 0;
	
	$Total_Age_15_Years_And_Above_Male = 0;
	$Total_Age_15_Years_And_Above_Female = 0;
	$Grand_Total_Age_15_Years_And_Above = 0;

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
					<tr><td colspan="3" style="text-align: center;"><span style="font-size: x-small;">Umri chini ya miaka 5</span></td></tr>
				</table>
			</td>
			<td width="13%" style="text-align: center;">
				<table width="100%">
					<tr><td colspan="3" style="text-align: center;"><span style="font-size: x-small;">Umri miaka 5 hadi miaka 14</span></td></tr>
				</table>
			</td>
			<td width="13%" style="text-align: center;">
				<table width="100%">
					<tr><td colspan="3" style="text-align: center;"><span style="font-size: x-small;">Umri miaka 15 na kuendelea</span></td></tr>
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
			<td style="text-align: center;"><span style="font-size: x-small;">Maelezo</span></td>
			<td width="13%" style="text-align: center;">
				<table width="100%">
					<tr>
						<td style="text-align: center; width: 33%;"><span style="font-size: x-small;">ME</span></td>
						<td style="text-align: center; width: 33%;"><span style="font-size: x-small;">KE</span></td>
						<td style="text-align: center; width: 33%;"><span style="font-size: x-small;">Jumla</span></td>
					</tr>
				</table>
			</td>
			<td width="13%" style="text-align: center;">
				<table width="100%">
					<tr>
						<td style="text-align: center; width: 33%;"><span style="font-size: x-small;">ME</span></td>
						<td style="text-align: center; width: 33%;"><span style="font-size: x-small;">KE</span></td>
						<td style="text-align: center; width: 33%;"><span style="font-size: x-small;">Jumla</span></td>
					</tr>
				</table>
			</td>
			<td width="13%" style="text-align: center;">
				<table width="100%">
					<tr>
						<td style="text-align: center; width: 33%;"><span style="font-size: x-small;">ME</span></td>
						<td style="text-align: center; width: 33%;"><span style="font-size: x-small;">KE</span></td>
						<td style="text-align: center; width: 33%;"><span style="font-size: x-small;">Jumla</span></td>
					</tr>
				</table>
			</td>
			<td>
				<table width="100%">
				<tr>
					<td style="text-align: center; width: 33%;"><span style="font-size: x-small;">ME</span></td>
					<td style="text-align: center; width: 33%;"><span style="font-size: x-small;">KE</span></td>
					<td style="text-align: center; width: 33%;"><span style="font-size: x-small;">Jumla</span></td>
				</tr>
				</table>
			</td></tr>
		<tr><td colspan=8><hr></td></tr>
		
<?php
		//get total mahudhurio ya DENTAL
		$Total_DENTAL_Below_5_Years_Male = 0;
		$Total_DENTAL_Below_5_Years_Female = 0;
		$Grand_Total_DENTAL_Below_5_Years = 0;
		
		$Total_DENTAL_Between_5_Years_But_Below_15_Years_Male = 0;
		$Total_DENTAL_Between_5_Years_But_Below_15_Years_Female = 0;
		$Grand_Total_DENTAL_Between_5_Years_But_Below_15_Years = 0;
		
		$Total_DENTAL_15_Years_And_Above_Male = 0;
		$Total_DENTAL_15_Years_And_Above_Female = 0;
		$Grand_Total_DENTAL_15_Years_And_Above = 0;

		$Total_DENTAL_Male = 0;
		$Total_DENTAL_Female = 0;
		$Grand_Total_DENTAL_Male = 0;
		$Grand_Total_DENTAL_Female = 0;

		$select_DENTAL = mysqli_query($conn,"select pr.Registration_ID, pr.Date_Of_Birth, ci.Check_In_Date, pr.Gender 
										from tbl_patient_registration pr, tbl_check_in ci, tbl_patient_payments pp, tbl_patient_payment_item_list ppl where
										pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
										pp.Check_In_ID = ci.Check_In_ID and
										ppl.Check_In_Type = 'Dental' and
										pp.Registration_ID = pr.Registration_ID and
										ci.Registration_ID = pr.Registration_ID and
										pr.Registration_Date_And_Time between '$Start_Date' and '$End_Date' group by pr.Registration_ID") or die(mysqli_error($conn));
		$num_DENTAL = mysqli_num_rows($select_DENTAL);
		if($num_DENTAL > 0){
			while ($pdata = mysqli_fetch_array($select_DENTAL)) {
				$Check_In_Date = $pdata['Check_In_Date'];
				$Date_Of_Birth = $pdata['Date_Of_Birth'];
				$Gender = $pdata['Gender'];
				$date1 = new DateTime($Check_In_Date);
				$date2 = new DateTime($Date_Of_Birth);
				$diff = $date1 -> diff($date2);
				$Years = $diff->y;
				//$Months = $diff->m;
				//$Days = $diff->d;
				

				//Chini Ya miaka 5
				if($Years < 5 && strtolower($Gender) == 'male'){
					$Total_DENTAL_Below_5_Years_Male++;
					$Grand_Total_DENTAL_Below_5_Years++;
					$Total_DENTAL_Male++;
				}

				if($Years < 5 && strtolower($Gender) == 'female'){
					$Total_DENTAL_Below_5_Years_Female++;
					$Grand_Total_DENTAL_Below_5_Years++;
					$Total_DENTAL_Female++;	
				}

				//Miaka 5 hadi chini ya miaka 15 
				if($Years >= 5 && $Years < 15 && strtolower($Gender) == 'male'){
					$Total_DENTAL_Between_5_Years_But_Below_15_Years_Male++;
					$Grand_Total_DENTAL_Between_5_Years_But_Below_15_Years++;
					$Total_DENTAL_Male++;
				}

				if($Years >= 5 && $Years < 15 && strtolower($Gender) == 'female'){
					$Total_DENTAL_Between_5_Years_But_Below_15_Years_Female++;
					$Grand_Total_DENTAL_Between_5_Years_But_Below_15_Years++;
					$Total_DENTAL_Female++;
				}

				// Miaka 15 na kuendelea
				if(($Years >= 15) && strtolower($Gender)=='male'){
					$Total_DENTAL_15_Years_And_Above_Male++;
					$Grand_Total_DENTAL_15_Years_And_Above++;
					$Total_DENTAL_Male++;
				}

				if(($Years >= 15) && strtolower($Gender)=='female'){
					$Total_DENTAL_15_Years_And_Above_Female++;
					$Grand_Total_DENTAL_15_Years_And_Above++;
					$Total_DENTAL_Female++;
				}
			}
		}
	//display mahudhurio ya DENTAL
?>
		<tr>
			<td width="5%" style="text-align: center;"><span style="font-size: x-small;">1</span></td>
			<td><span style="font-size: x-small;">Mahudhurio ya wagonjwa wa kinywa</span></td>
			<td width="13%" style="text-align: center;">
				<table width="100%">
					<tr>
						<td style="text-align: center; width: 33%;"><span style="font-size: x-small;"><?php echo $Total_DENTAL_Below_5_Years_Male; ?></span></td>
						<td style="text-align: center; width: 33%;"><span style="font-size: x-small;"><?php echo $Total_DENTAL_Below_5_Years_Female; ?></span></td>
						<td style="text-align: center; width: 33%;"><span style="font-size: x-small;"><?php echo $Grand_Total_DENTAL_Below_5_Years; ?></span></td>
					</tr>
				</table>
			</td>
			<td width="13%" style="text-align: center;">
				<table width="100%">
					<tr>
						<td style="text-align: center; width: 33%;"><span style="font-size: x-small;"><?php echo $Total_DENTAL_Between_5_Years_But_Below_15_Years_Male; ?></span></td>
						<td style="text-align: center; width: 33%;"><span style="font-size: x-small;"><?php echo $Total_DENTAL_Between_5_Years_But_Below_15_Years_Female; ?></span></td>
						<td style="text-align: center; width: 33%;"><span style="font-size: x-small;"><?php echo $Grand_Total_DENTAL_Between_5_Years_But_Below_15_Years; ?></span></td>
					</tr>
				</table>
			</td>
			<td width="13%" style="text-align: center;">
				<table width="100%">
					<tr>
						<td style="text-align: center; width: 33%;"><span style="font-size: x-small;"><?php echo $Total_DENTAL_15_Years_And_Above_Male; ?></span></td>
						<td style="text-align: center; width: 33%;"><span style="font-size: x-small;"><?php echo $Total_DENTAL_15_Years_And_Above_Female; ?></span></td>
						<td style="text-align: center; width: 33%;"><span style="font-size: x-small;"><?php echo $Grand_Total_DENTAL_15_Years_And_Above; ?></span></td>
					</tr>
				</table>
			</td>
			<td width="13%" style="text-align: center;">
				<table width="100%">
					<tr>
						<td style="text-align: center; width: 33%;"><span style="font-size: x-small;"><?php echo $Total_DENTAL_Male; ?></span></td>
						<td style="text-align: center; width: 33%;"><span style="font-size: x-small;"><?php echo $Total_DENTAL_Female; ?></span></td>
						<td style="text-align: center; width: 33%;"><span style="font-size: x-small;"><?php echo ($Total_DENTAL_Male + $Total_DENTAL_Female); ?></span></td>
					</tr>
				</table>
			</td>
		</tr>
<?php

	//wagonjwa  waliohudhuria kwa mara ya kwanza mwaka huu
	$Total_Waliohudhuria_Below_5_Years_Male = 0;
	$Total_Waliohudhuria_Below_5_Years_Female = 0;
	$Grand_Total_Waliohudhuria_Below_5_Years = 0;
	
	$Total_Waliohudhuria_Between_5_Years_But_Below_15_Years_Male = 0;
	$Total_Waliohudhuria_Between_5_Years_But_Below_15_Years_Female = 0;
	$Grand_Total_Waliohudhuria_Between_5_Years_But_Below_15_Years = 0;

	$Total_Waliohudhuria_15_Years_And_Above_Male = 0;
	$Total_Waliohudhuria_15_Years_And_Above_Female = 0;
	$Grand_Total_Waliohudhuria_15_Years_And_Above = 0;

	$Total_Waliohudhuria_Male = 0;
	$Total_Waliohudhuria_Female = 0;
	$Grand_Total_Waliohudhuria_Male = 0;
	$Grand_Total_Waliohudhuria_Female = 0;

	$select_waliohudhuria = mysqli_query($conn,"select pr.Registration_ID, pr.Date_Of_Birth, ci.Check_In_Date, pr.Gender 
										from tbl_patient_registration pr, tbl_check_in ci, tbl_patient_payments pp, tbl_patient_payment_item_list ppl where
										pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
										pp.Check_In_ID = ci.Check_In_ID and
										ppl.Check_In_Type = 'Dental' and
										pp.Registration_ID = pr.Registration_ID and
										ci.Registration_ID = pr.Registration_ID and
										pr.Registration_Date_And_Time between '$Start_Year' and '$End_Date' group by pr.Registration_ID") or die(mysqli_error($conn));
	

		$num_Waliohudhuria = mysqli_num_rows($select_waliohudhuria);
		if($num_Waliohudhuria > 0){
			while ($wdata = mysqli_fetch_array($select_waliohudhuria)) {
				$Check_In_Date = $wdata['Check_In_Date'];
				$Date_Of_Birth = $wdata['Date_Of_Birth'];
				$Gender = $wdata['Gender'];
				$date1 = new DateTime($Check_In_Date);
				$date2 = new DateTime($Date_Of_Birth);
				$diff = $date1 -> diff($date2);
				$Years = $diff->y;
				$Months = $diff->m;
				$Days = $diff->d;
				

				//Chini ya miaka 5
				if($Years < 5 && strtolower($Gender) == 'male'){
					$Total_Waliohudhuria_Below_5_Years_Male++;
					$Grand_Total_Waliohudhuria_Below_5_Years++;
					$Total_Waliohudhuria_Male++;
				}

				if($Years < 5 && strtolower($Gender) == 'female'){
					$Total_Waliohudhuria_Below_5_Years_Female++;
					$Grand_Total_Waliohudhuria_Below_5_Years++;
					$Total_Waliohudhuria_Female++;	
				}

				//Miaka 5 hadi chini ya miaka 15
				if($Years >= 5 && $Years < 15 && strtolower($Gender) == 'male'){
					$Total_Waliohudhuria_Between_5_Years_But_Below_15_Years_Male++;
					$Grand_Total_Waliohudhuria_Between_5_Years_But_Below_15_Years++;
					$Total_Waliohudhuria_Male++;
				}

				if($Years >= 5 && $Years < 15 && strtolower($Gender) == 'female'){
					$Total_Waliohudhuria_Between_5_Years_But_Below_15_Years_Female++;
					$Grand_Total_Waliohudhuria_Between_5_Years_But_Below_15_Years++;
					$Total_Waliohudhuria_Female++;
				}

				//Miaka 15 na kuendelea
				if(($Years >= 15) && strtolower($Gender)=='male'){
					$Total_Waliohudhuria_15_Years_And_Above_Male++;
					$Grand_Total_Waliohudhuria_15_Years_And_Above++;
					$Total_Waliohudhuria_Male++;
				}

				if(($Years >= 15) && strtolower($Gender)=='female'){
					$Total_Waliohudhuria_15_Years_And_Above_Female++;
					$Grand_Total_Waliohudhuria_15_Years_And_Above++;
					$Total_Waliohudhuria_Female++;
				}
			}
		}


	//display wagonjwa waliohudhuria kwa mara ya kwanza mwaka huu(*)
	echo '<tr>
			<td width="5%" style="text-align: center;"><span style="font-size: x-small;">2</span></td>
			<td><span style="font-size: x-small;">Wagonjwa waliohudhuria kwa mara ya kwanza mwaka huu (*)</span></td>
			<td width="13%" style="text-align: center;">
				<table width="100%">
					<tr>
						<td style="text-align: center; width: 33%;"><span style="font-size: x-small;">'.$Total_Waliohudhuria_Below_5_Years_Male.'</span></td>
						<td style="text-align: center; width: 33%;"><span style="font-size: x-small;">'.$Total_Waliohudhuria_Below_5_Years_Female.'</span></td>
						<td style="text-align: center; width: 33%;"><span style="font-size: x-small;">'.$Grand_Total_Waliohudhuria_Below_5_Years.'</span></td>
					</tr>
				</table>
			</td>
			<td width="13%" style="text-align: center;">
				<table width="100%">
					<tr>
						<td style="text-align: center; width: 33%;"><span style="font-size: x-small;">'.$Total_Waliohudhuria_Between_5_Years_But_Below_15_Years_Male.'</span></td>
						<td style="text-align: center; width: 33%;"><span style="font-size: x-small;">'.$Total_Waliohudhuria_Between_5_Years_But_Below_15_Years_Female.'</span></td>
						<td style="text-align: center; width: 33%;"><span style="font-size: x-small;">'.$Grand_Total_Waliohudhuria_Between_5_Years_But_Below_15_Years.'</span></td>
					</tr>
				</table>
			</td>
			<td width="13%" style="text-align: center;">
				<table width="100%">
					<tr>
						<td style="text-align: center; width: 33%;"><span style="font-size: x-small;">'.$Total_Waliohudhuria_15_Years_And_Above_Male.'</span></td>
						<td style="text-align: center; width: 33%;"><span style="font-size: x-small;">'.$Total_Waliohudhuria_15_Years_And_Above_Female.'</span></td>
						<td style="text-align: center; width: 33%;"><span style="font-size: x-small;">'.$Grand_Total_Waliohudhuria_15_Years_And_Above.'</span></td>
					</tr>
				</table>
			</td>
			<td width="13%" style="text-align: center;">
				<table width="100%">
					<tr>
						<td style="text-align: center; width: 33%;"><span style="font-size: x-small;">'.$Total_Waliohudhuria_Male.'</span></td>
						<td style="text-align: center; width: 33%;"><span style="font-size: x-small;">'.$Total_Waliohudhuria_Female.'</span></td>
						<td style="text-align: center; width: 33%;"><span style="font-size: x-small;">'.($Total_Waliohudhuria_Male + $Total_Waliohudhuria_Female).'</span></td>
					</tr>
				</table>
			</td>
		</tr>';

	//Mahudhurio ya marudio
	$Total_Marudio_Below_5_Years_Male = 0;
	$Total_Marudio_Below_5_Years_Female = 0;
	$Grand_Total_Marudio_Below_5_Years = 0;
	
	$Total_Marudio_Between_5_Years_But_Below_15_Years_Male = 0;
	$Total_Marudio_Between_5_Years_But_Below_15_Years_Female = 0;
	$Grand_Total_Marudio_Between_5_Years_But_Below_15_Years = 0;
	
	$Total_Marudio_15_Years_And_Above_Male = 0;
	$Total_Marudio_15_Years_And_Above_Female = 0;
	$Grand_Total_Marudio_15_Years_And_Above = 0;

	$Total_Marudio_Male = 0;
	$Total_Marudio_Female = 0;
	$Grand_Total_Marudio_Male = 0;
	$Grand_Total_Marudio_Female = 0;

	$select_Marudio = mysqli_query($conn,"select pr.Registration_ID, pr.Date_Of_Birth, ci.Check_In_Date, pr.Gender 
										from tbl_patient_registration pr, tbl_check_in ci, tbl_patient_payments pp, tbl_patient_payment_item_list ppl where
										pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
										pp.Check_In_ID = ci.Check_In_ID and
										ppl.Check_In_Type = 'Dental' and
										ci.Check_In_Date_And_Time between '$Start_Date' and '$End_Date' and
										pp.Registration_ID = pr.Registration_ID and
										ci.Registration_ID = pr.Registration_ID and
										pr.Registration_Date < ci.Check_In_Date") or die(mysqli_error($conn));
	

	$num_Marudio = mysqli_num_rows($select_Marudio);
	if($num_Marudio > 0){
		while ($mrdata = mysqli_fetch_array($select_Marudio)) {
			$Check_In_Date = $mrdata['Check_In_Date'];
			$Date_Of_Birth = $mrdata['Date_Of_Birth'];
			$Gender = $mrdata['Gender'];
			$date1 = new DateTime($Check_In_Date);
			$date2 = new DateTime($Date_Of_Birth);
			$diff = $date1 -> diff($date2);
			$Years = $diff->y;
			$Months = $diff->m;
			$Days = $diff->d;
			

			//Chini ya miaka 5
			if($Years < 5 && strtolower($Gender) == 'male'){
				$Total_Marudio_Below_5_Years_Male++;
				$Grand_Total_Marudio_Below_5_Years++;
				$Total_Marudio_Male++;
			}

			if($Years < 5 && strtolower($Gender) == 'female'){
				$Total_Marudio_Below_5_Years_Female++;
				$Grand_Total_Marudio_Below_5_Years++;
				$Total_Marudio_Female++;	
			}

			//Miaka 5 hadi chini ya miaka 15
			if($Years >=5 && $Years < 15 && strtolower($Gender) == 'male'){
				$Total_Marudio_Between_5_Years_But_Below_15_Years_Male++;
				$Grand_Total_Marudio_Between_5_Years_But_Below_15_Years++;
				$Total_Marudio_Male++;
			}

			if($Years >= 5 && $Years < 15 && strtolower($Gender) == 'female'){
				$Total_Marudio_Between_5_Years_But_Below_15_Years_Female++;
				$Grand_Total_Marudio_Between_5_Years_But_Below_15_Years++;
				$Total_Marudio_Female++;
			}

			//Miaka 15 na kuendelea
			if(($Years >= 15) && strtolower($Gender)=='male'){
				$Total_Marudio_15_Years_And_Above_Male++;
				$Grand_Total_Marudio_15_Years_And_Above++;
				$Total_Marudio_Male++;
			}

			if(($Years >= 15) && strtolower($Gender)=='female'){
				$Total_Marudio_15_Years_And_Above_Female++;
				$Grand_Total_Marudio_15_Years_And_Above++;
				$Total_Marudio_Female++;
			}
		}
	}
	//display Mahudhurio ya marudio
	echo '<tr>
			<td width="5%" style="text-align: center;"><span style="font-size: x-small;">3</span></td>
			<td><span style="font-size: x-small;">Mahudhurio ya Marudio</span></td>
			<td width="13%" style="text-align: center;">
				<table width="100%">
					<tr>
						<td style="text-align: center; width: 33%;"><span style="font-size: x-small;">'.$Total_Marudio_Below_5_Years_Male.'</span></td>
						<td style="text-align: center; width: 33%;"><span style="font-size: x-small;">'.$Total_Marudio_Below_5_Years_Female.'</span></td>
						<td style="text-align: center; width: 33%;"><span style="font-size: x-small;">'.$Grand_Total_Marudio_Below_5_Years.'</span></td>
					</tr>
				</table>
			</td>
			<td width="13%" style="text-align: center;">
				<table width="100%">
					<tr>
						<td style="text-align: center; width: 33%;"><span style="font-size: x-small;">'.$Total_Marudio_Between_5_Years_But_Below_15_Years_Male.'</span></td>
						<td style="text-align: center; width: 33%;"><span style="font-size: x-small;">'.$Total_Marudio_Between_5_Years_But_Below_15_Years_Female.'</span></td>
						<td style="text-align: center; width: 33%;"><span style="font-size: x-small;">'.$Grand_Total_Marudio_Between_5_Years_But_Below_15_Years.'</span></td>
					</tr>
				</table>
			</td>
			<td width="13%" style="text-align: center;">
				<table width="100%">
					<tr>
						<td style="text-align: center; width: 33%;"><span style="font-size: x-small;">'.$Total_Marudio_15_Years_And_Above_Male.'</span></td>
						<td style="text-align: center; width: 33%;"><span style="font-size: x-small;">'.$Total_Marudio_15_Years_And_Above_Female.'</span></td>
						<td style="text-align: center; width: 33%;"><span style="font-size: x-small;">'.$Grand_Total_Marudio_15_Years_And_Above.'</span></td>
					</tr>
				</table>
			</td>
			<td width="13%" style="text-align: center;">
				<table width="100%">
					<tr>
						<td style="text-align: center; width: 33%;"><span style="font-size: x-small;">'.$Total_Marudio_Male.'</span></td>
						<td style="text-align: center; width: 33%;"><span style="font-size: x-small;">'.$Total_Marudio_Female.'</span></td>
						<td style="text-align: center; width: 33%;"><span style="font-size: x-small;">'.($Total_Marudio_Male + $Total_Marudio_Female).'</span></td>
					</tr>
				</table>
			</td>
		</tr><tr><td colspan=8><hr></td></tr>';


	//get all disease groups
	$select_groups = mysqli_query($conn,"select * from tbl_disease_group where DENTAL_Report = 'yes' order by Dental_disease_Form_Id") or die(mysqli_error($conn));
	$group_num = mysqli_num_rows($select_groups);
	if($group_num > 0){
		//get details.....
		while($details = mysqli_fetch_array($select_groups)){
			$disease_group_id = $details['disease_group_id'];
			$disease_group_name = $details['disease_group_name'];
			$Gender_Type = $details['Gender_Type'];
			$Dental_disease_Form_Id = $details['Dental_disease_Form_Id'];
			//$Age_Below_1_Month = $details['Age_Below_1_Month'];
			//$Age_Between_1_Month_But_Below_1_Year = $details['Age_Between_1_Month_But_Below_1_Year'];
			//$Age_Between_1_Year_But_Below_5_Year = $details['Age_Between_1_Year_But_Below_5_Year'];
			//$Five_Years_Or_Below_Sixty_Years = $details['Five_Years_Or_Below_Sixty_Years'];
			//$Age_60_Years_And_Above = $details['Age_60_Years_And_Above'];

			//get all disease assigned to disease group based on disease_group_id
			$select_diseases = mysqli_query($conn,"select * from tbl_disease_group_mapping where disease_group_id = '$disease_group_id'") or die(mysqli_error($conn));
			$disease_num = mysqli_num_rows($select_diseases);
			if($disease_num > 0){
				//get all final diagnoses based on disease ids selected
				while ($dise = mysqli_fetch_array($select_diseases)) {
					$disease_id = $dise['disease_id'];
					//count total - gender and age must control the process
					$select = mysqli_query($conn,"select pr.Registration_ID, pr.Date_Of_Birth, pr.Gender, DATE(dc.Disease_Consultation_Date_And_Time) as Disease_Consultation_Date_And_Time
												from tbl_consultation c, tbl_disease_consultation dc, tbl_patient_registration pr where
												c.consultation_ID = dc.consultation_ID and
												pr.Registration_ID = c.Registration_ID and
												dc.diagnosis_type = 'diagnosis' and
												dc.disease_id = '$disease_id' and
												dc.Disease_Consultation_Date_And_Time between '$Start_Date' and '$End_Date'") or die(mysqli_error($conn));
					$num_total_details = mysqli_num_rows($select);
					if($num_total_details > 0){
						while($data = mysqli_fetch_array($select)){
							$Registration_ID = $data['Registration_ID'];
							//generate patient age (Should be Date of birth agains Disease_Consultation_Date)
							$Date_Of_Birth = $data['Date_Of_Birth'];
							$Gender = $data['Gender'];
							$Disease_Consultation_Date = $data['Disease_Consultation_Date_And_Time'];
							

							$date1 = new DateTime($Disease_Consultation_Date);
							$date2 = new DateTime($Date_Of_Birth);
							$diff = $date1 -> diff($date2);
							$Years = $diff->y;
							//$Months = $diff->m;
							//$Days = $diff->d;
							//$Status = '';




							//echo $Disease_Consultation_Date.' - '.$Date_Of_Birth.' - '.$Registration_ID.' - '.$Years.'     <br/>';
							
							/*if($Years > 0){
							    $Status = "Age";
							    $age = $Years;
							}elseif($Months > 0 && $Years == 0){
							    $Status = "Month";
							    $age = $Months;
							}elseif($Years <= 0 && $Months <= 0){
							    $Status = "Days";
							    $age = $Days;
							}*/

			//calculations started here..................

								if($Years < 5 && strtolower($Gender) == 'male'){
									$Total_Age_Below_5_Years_Male += 1;
									$Grand_Total_Age_Below_5_Years += 1;
									$Total_Male += 1;
									$Grand_Total_Male += 1;
								}else if ($Years < 5 && strtolower($Gender) == 'female') {
									$Total_Age_Below_5_Years_Female += 1;
									$Grand_Total_Age_Below_5_Years += 1;
									$Total_Female += 1;
									$Grand_Total_Female += 1;
								}
								
								if($Years < 5 && strtolower($Gender) == 'male'){
									$Total_Age_Below_5_Years_Male += 1;
									$Grand_Total_Age_Below_5_Years += 1;
									$Total_Male += 1;
									$Grand_Total_Male += 1;
								}else if ($Years < 5 && strtolower($Gender) == 'female') {
									$Total_Age_Below_5_Years_Female += 1;
									$Grand_Total_Age_Below_5_Years += 1;
									$Total_Female += 1;
									$Grand_Total_Female += 1;
								}
								
								if($Years < 5 && strtolower($Gender) == 'male'){
									$Total_Age_Below_5_Years_Male += 1;
									$Grand_Total_Age_Below_5_Years += 1;
									$Total_Male += 1;
									$Grand_Total_Male += 1;
								}else if ($Years < 5 && strtolower($Gender) == 'female') {
									$Total_Age_Below_5_Years_Female += 1;
									$Grand_Total_Age_Below_5_Years += 1;
									$Total_Female += 1;
									$Grand_Total_Female += 1;
								}
								


								/*if(strtolower($Five_Years_Or_Below_Sixty_Years) == 'yes'){
									if($Status == 'Age' && ($age >= 5 && $age < 60) && strtolower($Gender) == 'male'){
										$Total_Five_Years_Or_Below_Sixty_Years_Male += 1;
										$Grand_Total_Five_Years_Or_Below_Sixty_Years += 1;
										$Total_Male += 1;
										$Grand_Total_Male += 1;
									}else if ($Status == 'Age' && ($age >= 5 && $age < 60) && strtolower($Gender) == 'female') {
										$Total_Five_Years_Or_Below_Sixty_Years_Female += 1;
										$Grand_Total_Five_Years_Or_Below_Sixty_Years += 1;
										$Total_Female += 1;
										$Grand_Total_Female += 1;
									}
								}else if(strtolower($Five_Years_Or_Below_Sixty_Years) == 'no'){
									$Total_Five_Years_Or_Below_Sixty_Years_Male = 'NULL';
									$Total_Five_Years_Or_Below_Sixty_Years_Female = 'NULL';
									$Grand_Total_Five_Years_Or_Below_Sixty_Years = 'NULL';
								}*/

								/*if(strtolower($Age_60_Years_And_Above) == 'yes'){
									if ($Status == 'Age' && $age >= 60 && strtolower($Gender) == 'male') {
										$Total_Age_15_Years_And_Above_Male += 1;
										$Grand_Total_Age_15_Years_And_Above += 1;
										$Total_Male += 1;
										$Grand_Total_Male += 1;
									}else if ($Status == 'Age' && $age >= 60 && strtolower($Gender) == 'female') {
										$Total_Age_15_Years_And_Above_Female += 1;
										$Grand_Total_Age_15_Years_And_Above += 1;
										$Total_Female += 1;
										$Grand_Total_Female += 1;
									}
								}else if(strtolower($Age_60_Years_And_Above) == 'no'){
									$Total_Age_15_Years_And_Above_Male = 'NULL';
									$Total_Age_15_Years_And_Above_Female = 'NULL';
									$Grand_Total_Age_15_Years_And_Above = 'NULL';
								}*/							
			//ended here................................
						}
					}
				}
			}
	
		echo '<tr>
			<td width="5%" style="text-align: center;"><span style="font-size: x-small;">'.$Dental_disease_Form_Id.'</span></td>
			<td><span style="font-size: x-small;">'.$disease_group_name.'</span></td>
			<td width="13%" style="text-align: center;">
				<table width="100%">
					<tr>
						<td style="text-align: center;" width="33%" title="'.$disease_group_name.'  &#xA;Wagonjwa watoto wanaume umri chini ya miaka 5"><span style="font-size: x-small;" >'.$Total_Age_Below_5_Years_Male.'</span></td>
						<td style="text-align: center;" width="33%" title="'.$disease_group_name.'&#xA;Wagonjwa watoto wanawake umri chini ya miaka 5"><span style="font-size: x-small;">'.$Total_Age_Below_5_Years_Female.'</span></td>
						<td style="text-align: center;" width="33%" title="'.$disease_group_name.' &#xA;Jumla kuu wagonjwa watoto umri chini ya miaka 5"><span style="font-size: x-small;">'.$Grand_Total_Age_Below_5_Years.'</span></td>
					</tr>
				</table>
			</td>
			<td width="13%" style="text-align: center;">
				<table width="100%">
					<tr>
						<td style="text-align: center;" width="33%" title="'.$disease_group_name.'  &#xA;Wagonjwa watoto wanaume umri miaka 5 hadi chini ya miaka 15"><span style="font-size: x-small;">'.$Total_Age_Between_5_Years_But_Below_15_Years_Male.'</span></td>
						<td style="text-align: center;" width="33%" title="'.$disease_group_name.'  &#xA;Wagonjwa watoto wanawake umri miaka 5 hadi chini ya miaka 15"><span style="font-size: x-small;">'.$Total_Age_Between_5_Years_But_Below_15_Years_Female.'</span></td>
						<td style="text-align: center;" width="33%" title="'.$disease_group_name.'  &#xA;Jumla kuu Wagonjwa watoto umri miaka 5 hadi chini ya miaka 15"><span style="font-size: x-small;">'.$Grand_Total_Age_Between_5_Years_But_Below_15_Years.'</span></td>
					</tr>
				</table>
			</td>
			<td width="13%" style="text-align: center;">
				<table width="100%">
					<tr>
						<td style="text-align: center;" width="33%"  title="'.$disease_group_name.'  &#xA;Wagonjwa wanaume umri miaka 15 na kuendelea"><span style="font-size: x-small;">'.$Total_Age_Between_5_Years_But_Below_15_Years_Male.'</span></td>
						<td style="text-align: center;" width="33%"  title="'.$disease_group_name.'  &#xA;Wagonjwa wanawake umri miaka 15 na kuendelea"><span style="font-size: x-small;">'.$Total_Age_Between_5_Years_But_Below_15_Years_Female.'</span></td>
						<td style="text-align: center;" width="33%"  title="'.$disease_group_name.'  &#xA;Jumla kuu wagonjwa umri miaka 15 na kuendelea"><span style="font-size: x-small;">'.$Grand_Total_Age_Between_5_Years_But_Below_15_Years.'</span></td>
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
			$Total_Age_Below_5_Years_Male = 0;
			$Total_Age_Below_5_Years_Female = 0;
			$Grand_Total_Age_Below_5_Years = 0;
			
			$Total_Age_Between_5_Years_But_Below_15_Years_Male = 0;
			$Total_Age_Between_5_Years_But_Below_15_Years_Female = 0;
			$Grand_Total_Age_Between_5_Years_But_Below_15_Years = 0;

			$Total_Age_Below_5_Years_Male = 0;
			$Total_Age_Below_5_Years_Female = 0;
			$Grand_Total_Age_Below_5_Years = 0;
			
			
			$Total_Five_Years_Or_Below_Sixty_Years_Male = 0;
			$Total_Five_Years_Or_Below_Sixty_Years_Female = 0;
			$Grand_Total_Five_Years_Or_Below_Sixty_Years = 0;
			
			$Total_Age_15_Years_And_Above_Male = 0;
			$Total_Age_15_Years_And_Above_Female = 0;
			$Grand_Total_Age_15_Years_And_Above = 0;
			$Total_Male = 0;
			$Total_Female = 0;
		}
	}
	echo $myBody;
?>