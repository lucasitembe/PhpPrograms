<?php
	session_start();
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
	
	
	$Total_Five_Years_Or_Below_Sixty_Years_Male = 0;
	$Total_Five_Years_Or_Below_Sixty_Years_Female = 0;
	$Grand_Total_Five_Years_Or_Below_Sixty_Years = 0;
	
	$Total_Age_60_Years_And_Above_Male = 0;
	$Total_Age_60_Years_And_Above_Female = 0;
	$Grand_Total_Age_60_Years_And_Above = 0;

	$Total_Male = 0;
	$Total_Female = 0;
	$Grand_Total_Male = 0;
	$Grand_Total_Female = 0;
	
	$disp = '<table width=100%>
		<tr>
		    <td style="text-align: center;">
			<b>Taarifa ya Wagonjwa wa Nje (OPD)</b>
			<br>
			<span style="font-size: x-small;"><b>Mwezi '.$Month_Caption.' Mwaka '.$Year.'</b></span>
		    </td>
		</tr>
	    </table><br/>';
	$disp .= '<table width="100%">
		<tr><td colspan=8><hr></td></tr>
		<tr>
			<td width="5%" style="text-align: center;"></td>
			<td style="text-align: center;"></td>
			<td width="13%" style="text-align: center;">
				<table width="100%">
					<tr><td colspan="3" style="text-align: center;"><span style="font-size: small;">Umri chini ya mwezi 1</span></td></tr>
				</table>
			</td>
			<td width="13%" style="text-align: center;">
				<table width="100%">
					<tr><td colspan="3" style="text-align: center;"><span style="font-size: small;">Umri mwezi 1 hadi umri chini ya mwaka 1</span></td></tr>
				</table>
			</td>
			<td width="13%" style="text-align: center;">
				<table width="100%">
					<tr><td colspan="3" style="text-align: center;"><span style="font-size: small;">Umri mwaka 1 hadi umri chini ya miaka 5</span></td></tr>
				</table>
			</td>
			<td width="13%" style="text-align: center;">
				<table width="100%">
					<tr><td colspan="3" style="text-align: center;"><span style="font-size: small;">Umri miaka 5 hadi miaka chini ya 60</span></td></tr>
				</table>
			</td>
			<td width="13%" style="text-align: center;">
				<table width="100%">
					<tr><td colspan="3" style="text-align: center;"><span style="font-size: small;">Umri miaka 60 na kuendelea</span></td></tr>
				</table>
			</td>
			<td width="13%" style="text-align: center;">
				<table width="100%">
					<tr><td colspan="3" style="text-align: center;"><span style="font-size: small;">Jumla Kuu</span></td></tr>
				</table>
			</td>
		</tr>
		<tr>
			<td width="5%" style="text-align: center;"><span style="font-size: small;">Na</span></td>
			<td style="text-align: center;"><span style="font-size: small;">Maelezo</span></td>
			<td width="13%" style="text-align: center;">
				<table width="100%">
					<tr>
						<td width="5%" style="text-align: center;"><span style="font-size: small;">ME</span></td>
						<td width="5%" style="text-align: center;"><span style="font-size: small;">KE</span></td>
						<td width="5%" style="text-align: center;"><span style="font-size: small;">Jumla</span></td>
					</tr>
				</table>
			</td>
			<td width="13%" style="text-align: center;">
				<table width="100%">
					<tr>
						<td width="5%" style="text-align: center;"><span style="font-size: small;">ME</span></td>
						<td width="5%" style="text-align: center;"><span style="font-size: small;">KE</span></td>
						<td width="5%" style="text-align: center;"><span style="font-size: small;">Jumla</span></td>
					</tr>
				</table>
			</td>
			<td width="13%" style="text-align: center;">
				<table width="100%">
					<tr>
						<td width="5%" style="text-align: center;"><span style="font-size: small;">ME</span></td>
						<td width="5%" style="text-align: center;"><span style="font-size: small;">KE</span></td>
						<td width="5%" style="text-align: center;"><span style="font-size: small;">Jumla</span></td>
					</tr>
				</table>
			</td>
			<td width="13%" style="text-align: center;">
				<table width="100%">
					<tr>
						<td width="5%" style="text-align: center;"><span style="font-size: small;">ME</span></td>
						<td width="5%" style="text-align: center;"><span style="font-size: small;">KE</span></td>
						<td width="5%" style="text-align: center;"><span style="font-size: small;">Jumla</span></td>
					</tr>
				</table>
			</td>
			<td width="13%" style="text-align: center;">
				<table width="100%">
					<tr>
						<td width="5%" style="text-align: center;"><span style="font-size: small;">ME</span></td>
						<td width="5%" style="text-align: center;"><span style="font-size: small;">KE</span></td>
						<td width="5%" style="text-align: center;"><span style="font-size: small;">Jumla</span></td>
					</tr>
				</table>
			</td>
			<td>
				<table width="100%">
				<tr>
					<td width="5%" style="text-align: center;"><span style="font-size: small;">ME</span></td>
					<td width="5%" style="text-align: center;"><span style="font-size: small;">KE</span></td>
					<td width="5%" style="text-align: center;"><span style="font-size: small;">Jumla</span></td>
				</tr>
				</table>
			</td></tr>
		<tr><td colspan=8><hr></td></tr>
		';

//Mahudhurio ya marudio
	$Total_Marudio_Below_1_Month_Male = 0;
	$Total_Marudio_Below_1_Month_Female = 0;
	$Grand_Total_Marudio_Below_1_Month = 0;
	
	$Total_Marudio_Between_1_Month_But_Below_1_Year_Male = 0;
	$Total_Marudio_Between_1_Month_But_Below_1_Year_Female = 0;
	$Grand_Total_Marudio_Between_1_Month_But_Below_1_Year = 0;

	$Total_Marudio_Between_1_Year_But_Below_5_Year_Male = 0;
	$Total_Marudio_Between_1_Year_But_Below_5_Year_Female = 0;
	$Grand_Total_Marudio_Between_1_Year_But_Below_5_Year = 0;
	
	
	$Total_Marudio_Five_Years_Or_Below_Sixty_Years_Male = 0;
	$Total_Marudio_Five_Years_Or_Below_Sixty_Years_Female = 0;
	$Grand_Total__MarudioFive_Years_Or_Below_Sixty_Years = 0;
	
	$Total_Marudio_60_Years_And_Above_Male = 0;
	$Total_Marudio_60_Years_And_Above_Female = 0;
	$Grand_Total_Marudio_60_Years_And_Above = 0;

	$Total_Marudio_Male = 0;
	$Total_Marudio_Female = 0;
	$Grand_Total_Marudio_Male = 0;
	$Grand_Total_Marudio_Female = 0;

	$select_Marudio = mysqli_query($conn,"select pr.Registration_ID, pr.Date_Of_Birth, ci.Check_In_Date, pr.Gender from tbl_patient_registration pr, tbl_check_in ci where
									ci.Registration_ID = pr.Registration_ID and
									ci.Check_In_Date_And_Time between '$Start_Date' and '$End_Date' and
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
			

			//Chini Ya Mwezi mmoja
			if($Years == 0 && $Months == 0 && strtolower($Gender) == 'male'){
				$Total_Marudio_Below_1_Month_Male++;
				$Grand_Total_Marudio_Below_1_Month++;
				$Total_Marudio_Male++;
			}

			if($Years == 0 && $Months == 0 && strtolower($Gender) == 'female'){
				$Total_Marudio_Below_1_Month_Female++;
				$Grand_Total_Marudio_Below_1_Month++;
				$Total_Marudio_Female++;	
			}

			//Mwezi mmoja hadi Chini Ya Mwaka mmoja
			if($Years == 0 && $Months >= 1 && strtolower($Gender) == 'male'){
				$Total_Marudio_Between_1_Month_But_Below_1_Year_Male++;
				$Grand_Total_Marudio_Between_1_Month_But_Below_1_Year++;
				$Total_Marudio_Male++;
			}

			if($Years == 0 && $Months >= 1 && strtolower($Gender) == 'female'){
				$Total_Marudio_Between_1_Month_But_Below_1_Year_Female++;
				$Grand_Total_Marudio_Between_1_Month_But_Below_1_Year++;
				$Total_Marudio_Female++;
			}

			//Mwaka mmoja hadi Chini Ya Miaka Mitano
			if(($Years >=1 && $Years < 5) && strtolower($Gender)=='male'){
				$Total_Marudio_Between_1_Year_But_Below_5_Year_Male++;
				$Grand_Total_Marudio_Between_1_Year_But_Below_5_Year++;
				$Total_Marudio_Male++;
			}

			if(($Years >= 1 && $Years < 5) && strtolower($Gender)=='female'){
				$Total_Marudio_Between_1_Year_But_Below_5_Year_Female++;
				$Grand_Total_Marudio_Between_1_Year_But_Below_5_Year++;
				$Total_Marudio_Female++;
			}

			//Miaka 5 hadi chini ya miaka 60
			if(($Years >=5 && $Years < 60) && strtolower($Gender)=='male'){
				$Total_Marudio_Five_Years_Or_Below_Sixty_Years_Male++;
				$Grand_Total__MarudioFive_Years_Or_Below_Sixty_Years++;
				$Total_Marudio_Male++;
			}

			if(($Years >=5 && $Years < 60) && strtolower($Gender) == 'female'){
				$Total_Marudio_Five_Years_Or_Below_Sixty_Years_Female++;
				$Grand_Total__MarudioFive_Years_Or_Below_Sixty_Years++;
				$Total_Marudio_Female++;
			}

			//Miaka 60 na kuendelea
			if(($Years >= 60) && strtolower($Gender)=='male'){
				$Total_Marudio_60_Years_And_Above_Male++;
				$Grand_Total_Marudio_60_Years_And_Above++;
				$Total_Marudio_Male++;
			}

			if(($Years >= 60) && strtolower($Gender)=='female'){
				$Total_Marudio_60_Years_And_Above_Female++;
				$Grand_Total_Marudio_60_Years_And_Above++;
				$Total_Marudio_Female++;
			}
		}
	}

		//get total mahudhurio ya OPD
		//Tunajumlisha na marudio woteeeeeee
		$Total_OPD_Below_1_Month_Male = $Total_Marudio_Below_1_Month_Male;
		$Total_OPD_Below_1_Month_Female = $Total_Marudio_Below_1_Month_Female;
		$Grand_Total_OPD_Below_1_Month = $Grand_Total_Marudio_Below_1_Month;
		
		$Total_OPD_Between_1_Month_But_Below_1_Year_Male = $Total_Marudio_Between_1_Month_But_Below_1_Year_Male;
		$Total_OPD_Between_1_Month_But_Below_1_Year_Female = $Total_Marudio_Between_1_Month_But_Below_1_Year_Female;
		$Grand_Total_OPD_Between_1_Month_But_Below_1_Year = $Grand_Total_Marudio_Between_1_Month_But_Below_1_Year;

		$Total_OPD_Between_1_Year_But_Below_5_Year_Male = $Total_Marudio_Between_1_Year_But_Below_5_Year_Male;
		$Total_OPD_Between_1_Year_But_Below_5_Year_Female = $Total_Marudio_Between_1_Year_But_Below_5_Year_Female;
		$Grand_Total_OPD_Between_1_Year_But_Below_5_Year = $Grand_Total_Marudio_Between_1_Year_But_Below_5_Year;
		
		
		$Total_OPD_Five_Years_Or_Below_Sixty_Years_Male = $Total_Marudio_Five_Years_Or_Below_Sixty_Years_Male;
		$Total_OPD_Five_Years_Or_Below_Sixty_Years_Female = $Total_Marudio_Five_Years_Or_Below_Sixty_Years_Female;
		$Grand_Total__OPDFive_Years_Or_Below_Sixty_Years = $Grand_Total__MarudioFive_Years_Or_Below_Sixty_Years;
		
		$Total_OPD_60_Years_And_Above_Male = $Total_Marudio_60_Years_And_Above_Male;
		$Total_OPD_60_Years_And_Above_Female = $Total_Marudio_60_Years_And_Above_Female;
		$Grand_Total_OPD_60_Years_And_Above = $Grand_Total_Marudio_60_Years_And_Above;

		$Total_OPD_Male = $Total_Marudio_Male;
		$Total_OPD_Female = $Total_Marudio_Female;
		$Grand_Total_OPD_Male = $Grand_Total_Marudio_Male;
		$Grand_Total_OPD_Female = $Grand_Total_Marudio_Female;

		$select_OPD = mysqli_query($conn,"select pr.Registration_ID, pr.Date_Of_Birth, ci.Check_In_Date, pr.Gender from tbl_patient_registration pr, tbl_check_in ci where
									ci.Registration_ID = pr.Registration_ID and
									pr.Registration_Date_And_Time between '$Start_Date' and '$End_Date' group by pr.Registration_ID") or die(mysqli_error($conn));
		$num_OPD = mysqli_num_rows($select_OPD);
		if($num_OPD > 0){
			while ($pdata = mysqli_fetch_array($select_OPD)) {
				$Check_In_Date = $pdata['Check_In_Date'];
				$Date_Of_Birth = $pdata['Date_Of_Birth'];
				$Gender = $pdata['Gender'];
				$date1 = new DateTime($Check_In_Date);
				$date2 = new DateTime($Date_Of_Birth);
				$diff = $date1 -> diff($date2);
				$Years = $diff->y;
				$Months = $diff->m;
				$Days = $diff->d;
				

				//Chini Ya Mwezi mmoja
				if($Years == 0 && $Months == 0 && strtolower($Gender) == 'male'){
					$Total_OPD_Below_1_Month_Male++;
					$Grand_Total_OPD_Below_1_Month++;
					$Total_OPD_Male++;
				}

				if($Years == 0 && $Months == 0 && strtolower($Gender) == 'female'){
					$Total_OPD_Below_1_Month_Female++;
					$Grand_Total_OPD_Below_1_Month++;
					$Total_OPD_Female++;	
				}

				//Mwezi mmoja hadi Chini Ya Mwaka mmoja
				if($Years == 0 && $Months >= 1 && strtolower($Gender) == 'male'){
					$Total_OPD_Between_1_Month_But_Below_1_Year_Male++;
					$Grand_Total_OPD_Between_1_Month_But_Below_1_Year++;
					$Total_OPD_Male++;
				}

				if($Years == 0 && $Months >= 1 && strtolower($Gender) == 'female'){
					$Total_OPD_Between_1_Month_But_Below_1_Year_Female++;
					$Grand_Total_OPD_Between_1_Month_But_Below_1_Year++;
					$Total_OPD_Female++;
				}

				//Mwaka mmoja hadi Chini Ya Miaka Mitano
				if(($Years >=1 && $Years < 5) && strtolower($Gender)=='male'){
					$Total_OPD_Between_1_Year_But_Below_5_Year_Male++;
					$Grand_Total_OPD_Between_1_Year_But_Below_5_Year++;
					$Total_OPD_Male++;
				}

				if(($Years >= 1 && $Years < 5) && strtolower($Gender)=='female'){
					$Total_OPD_Between_1_Year_But_Below_5_Year_Female++;
					$Grand_Total_OPD_Between_1_Year_But_Below_5_Year++;
					$Total_OPD_Female++;
				}

				//Miaka 5 hadi chini ya miaka 60
				if(($Years >=5 && $Years < 60) && strtolower($Gender)=='male'){
					$Total_OPD_Five_Years_Or_Below_Sixty_Years_Male++;
					$Grand_Total__OPDFive_Years_Or_Below_Sixty_Years++;
					$Total_OPD_Male++;
				}

				if(($Years >=5 && $Years < 60) && strtolower($Gender) == 'female'){
					$Total_OPD_Five_Years_Or_Below_Sixty_Years_Female++;
					$Grand_Total__OPDFive_Years_Or_Below_Sixty_Years++;
					$Total_OPD_Female++;
				}

				//Miaka 60 na kuendelea
				if(($Years >= 60) && strtolower($Gender)=='male'){
					$Total_OPD_60_Years_And_Above_Male++;
					$Grand_Total_OPD_60_Years_And_Above++;
					$Total_OPD_Male++;
				}

				if(($Years >= 60) && strtolower($Gender)=='female'){
					$Total_OPD_60_Years_And_Above_Female++;
					$Grand_Total_OPD_60_Years_And_Above++;
					$Total_OPD_Female++;
				}
			}
		}
	//display mahudhurio ya OPD
	$disp .= '<tr>
			<td width="5%" style="text-align: center;"><span style="font-size: small;">1</span></td>
			<td><span style="font-size: small;">Mahuzurio ya OPD</span></td>
			<td width="13%" style="text-align: center;">
				<table width="100%">
					<tr>
						<td width="5%" style="text-align: center;"><span style="font-size: small;">'.$Total_OPD_Below_1_Month_Male.'</span></td>
						<td width="5%" style="text-align: center;"><span style="font-size: small;">'.$Total_OPD_Below_1_Month_Female.'</span></td>
						<td width="5%" style="text-align: center;"><span style="font-size: small;">'.$Grand_Total_OPD_Below_1_Month.'</span></td>
					</tr>
				</table>
			</td>
			<td width="13%" style="text-align: center;">
				<table width="100%">
					<tr>
						<td width="5%" style="text-align: center;"><span style="font-size: small;">'.$Total_OPD_Between_1_Month_But_Below_1_Year_Male.'</span></td>
						<td width="5%" style="text-align: center;"><span style="font-size: small;">'.$Total_OPD_Between_1_Month_But_Below_1_Year_Female.'</span></td>
						<td width="5%" style="text-align: center;"><span style="font-size: small;">'.$Grand_Total_OPD_Between_1_Month_But_Below_1_Year.'</span></td>
					</tr>
				</table>
			</td>
			<td width="13%" style="text-align: center;">
				<table width="100%">
					<tr>
						<td width="5%" style="text-align: center;"><span style="font-size: small;">'.$Total_OPD_Between_1_Year_But_Below_5_Year_Male.'</span></td>
						<td width="5%" style="text-align: center;"><span style="font-size: small;">'.$Total_OPD_Between_1_Year_But_Below_5_Year_Female.'</span></td>
						<td width="5%" style="text-align: center;"><span style="font-size: small;">'.$Grand_Total_OPD_Between_1_Year_But_Below_5_Year.'</span></td>
					</tr>
				</table>
			</td>
			<td width="13%" style="text-align: center;">
				<table width="100%">
					<tr>
						<td width="5%" style="text-align: center;"><span style="font-size: small;">'.$Total_OPD_Five_Years_Or_Below_Sixty_Years_Male.'</span></td>
						<td width="5%" style="text-align: center;"><span style="font-size: small;">'.$Total_OPD_Five_Years_Or_Below_Sixty_Years_Female.'</span></td>
						<td width="5%" style="text-align: center;"><span style="font-size: small;">'.$Grand_Total__OPDFive_Years_Or_Below_Sixty_Years.'</span></td>
					</tr>
				</table>
			</td>
			<td width="13%" style="text-align: center;">
				<table width="100%">
					<tr>
						<td width="5%" style="text-align: center;"><span style="font-size: small;">'.$Total_OPD_60_Years_And_Above_Male.'</span></td>
						<td width="5%" style="text-align: center;"><span style="font-size: small;">'.$Total_OPD_60_Years_And_Above_Female.'</span></td>
						<td width="5%" style="text-align: center;"><span style="font-size: small;">'.$Grand_Total_OPD_60_Years_And_Above.'</span></td>
					</tr>
				</table>
			</td>
			<td width="13%" style="text-align: center;">
				<table width="100%">
					<tr>
						<td width="5%" style="text-align: center;"><span style="font-size: small;">'.$Total_OPD_Male.'</span></td>
						<td width="5%" style="text-align: center;"><span style="font-size: small;">'.$Total_OPD_Female.'</span></td>
						<td width="5%" style="text-align: center;"><span style="font-size: small;">'.($Total_OPD_Male + $Total_OPD_Female).'</span></td>
					</tr>
				</table>
			</td>
		</tr>';


	//wagonjwa  waliohudhuria kwa mara ya kwanza
	$Total_Waliohudhuria_Below_1_Month_Male = 0;
	$Total_Waliohudhuria_Below_1_Month_Female = 0;
	$Grand_Total_Waliohudhuria_Below_1_Month = 0;
	
	$Total_Waliohudhuria_Between_1_Month_But_Below_1_Year_Male = 0;
	$Total_Waliohudhuria_Between_1_Month_But_Below_1_Year_Female = 0;
	$Grand_Total_Waliohudhuria_Between_1_Month_But_Below_1_Year = 0;

	$Total_Waliohudhuria_Between_1_Year_But_Below_5_Year_Male = 0;
	$Total_Waliohudhuria_Between_1_Year_But_Below_5_Year_Female = 0;
	$Grand_Total_Waliohudhuria_Between_1_Year_But_Below_5_Year = 0;
	
	
	$Total_Waliohudhuria_Five_Years_Or_Below_Sixty_Years_Male = 0;
	$Total_Waliohudhuria_Five_Years_Or_Below_Sixty_Years_Female = 0;
	$Grand_Total__WaliohudhuriaFive_Years_Or_Below_Sixty_Years = 0;
	
	$Total_Waliohudhuria_60_Years_And_Above_Male = 0;
	$Total_Waliohudhuria_60_Years_And_Above_Female = 0;
	$Grand_Total_Waliohudhuria_60_Years_And_Above = 0;

	$Total_Waliohudhuria_Male = 0;
	$Total_Waliohudhuria_Female = 0;
	$Grand_Total_Waliohudhuria_Male = 0;
	$Grand_Total_Waliohudhuria_Female = 0;

	$select_waliohudhuria = mysqli_query($conn,"select pr.Registration_ID, pr.Date_Of_Birth, ci.Check_In_Date, pr.Gender from tbl_patient_registration pr, tbl_check_in ci where
											ci.Registration_ID = pr.Registration_ID and
											pr.Registration_Date_And_Time between '$Start_Date' and '$End_Date' group by pr.Registration_ID") or die(mysqli_error($conn));
	

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
				

				//Chini Ya Mwezi mmoja
				if($Years == 0 && $Months == 0 && strtolower($Gender) == 'male'){
					$Total_Waliohudhuria_Below_1_Month_Male++;
					$Grand_Total_Waliohudhuria_Below_1_Month++;
					$Total_Waliohudhuria_Male++;
				}

				if($Years == 0 && $Months == 0 && strtolower($Gender) == 'female'){
					$Total_Waliohudhuria_Below_1_Month_Female++;
					$Grand_Total_Waliohudhuria_Below_1_Month++;
					$Total_Waliohudhuria_Female++;	
				}

				//Mwezi mmoja hadi Chini Ya Mwaka mmoja
				if($Years == 0 && $Months >= 1 && strtolower($Gender) == 'male'){
					$Total_Waliohudhuria_Between_1_Month_But_Below_1_Year_Male++;
					$Grand_Total_Waliohudhuria_Between_1_Month_But_Below_1_Year++;
					$Total_Waliohudhuria_Male++;
				}

				if($Years == 0 && $Months >= 1 && strtolower($Gender) == 'female'){
					$Total_Waliohudhuria_Between_1_Month_But_Below_1_Year_Female++;
					$Grand_Total_Waliohudhuria_Between_1_Month_But_Below_1_Year++;
					$Total_Waliohudhuria_Female++;
				}

				//Mwaka mmoja hadi Chini Ya Miaka Mitano
				if(($Years >=1 && $Years < 5) && strtolower($Gender)=='male'){
					$Total_Waliohudhuria_Between_1_Year_But_Below_5_Year_Male++;
					$Grand_Total_Waliohudhuria_Between_1_Year_But_Below_5_Year++;
					$Total_Waliohudhuria_Male++;
				}

				if(($Years >= 1 && $Years < 5) && strtolower($Gender)=='female'){
					$Total_Waliohudhuria_Between_1_Year_But_Below_5_Year_Female++;
					$Grand_Total_Waliohudhuria_Between_1_Year_But_Below_5_Year++;
					$Total_Waliohudhuria_Female++;
				}

				//Miaka 5 hadi chini ya miaka 60
				if(($Years >=5 && $Years < 60) && strtolower($Gender)=='male'){
					$Total_Waliohudhuria_Five_Years_Or_Below_Sixty_Years_Male++;
					$Grand_Total__WaliohudhuriaFive_Years_Or_Below_Sixty_Years++;
					$Total_Waliohudhuria_Male++;
				}

				if(($Years >=5 && $Years < 60) && strtolower($Gender) == 'female'){
					$Total_Waliohudhuria_Five_Years_Or_Below_Sixty_Years_Female++;
					$Grand_Total__WaliohudhuriaFive_Years_Or_Below_Sixty_Years++;
					$Total_Waliohudhuria_Female++;
				}

				//Miaka 60 na kuendelea
				if(($Years >= 60) && strtolower($Gender)=='male'){
					$Total_Waliohudhuria_60_Years_And_Above_Male++;
					$Grand_Total_Waliohudhuria_60_Years_And_Above++;
					$Total_Waliohudhuria_Male++;
				}

				if(($Years<5 && $Years >=1) && strtolower($Gender)=='female'){
					$Total_Waliohudhuria_60_Years_And_Above_Female++;
					$Grand_Total_Waliohudhuria_60_Years_And_Above++;
					$Total_Waliohudhuria_Female++;
				}
			}
		}


	//display wagonjwa waliohudhuria kwa mara ya kwanza mwaka huu(*)
	$disp .= '<tr>
			<td width="5%" style="text-align: center;"><span style="font-size: small;">2</span></td>
			<td><span style="font-size: small;">Wagonjwa waliohudhuria kwa mara ya kwanza mwaka huu (*)</span></td>
			<td width="13%" style="text-align: center;">
				<table width="100%">
					<tr>
						<td width="5%" style="text-align: center;"><span style="font-size: small;">'.$Total_Waliohudhuria_Below_1_Month_Male.'</span></td>
						<td width="5%" style="text-align: center;"><span style="font-size: small;">'.$Total_Waliohudhuria_Below_1_Month_Female.'</span></td>
						<td width="5%" style="text-align: center;"><span style="font-size: small;">'.$Grand_Total_Waliohudhuria_Below_1_Month.'</span></td>
					</tr>
				</table>
			</td>
			<td width="13%" style="text-align: center;">
				<table width="100%">
					<tr>
						<td width="5%" style="text-align: center;"><span style="font-size: small;">'.$Total_Waliohudhuria_Between_1_Month_But_Below_1_Year_Male.'</span></td>
						<td width="5%" style="text-align: center;"><span style="font-size: small;">'.$Total_Waliohudhuria_Between_1_Month_But_Below_1_Year_Female.'</span></td>
						<td width="5%" style="text-align: center;"><span style="font-size: small;">'.$Grand_Total_Waliohudhuria_Between_1_Month_But_Below_1_Year.'</span></td>
					</tr>
				</table>
			</td>
			<td width="13%" style="text-align: center;">
				<table width="100%">
					<tr>
						<td width="5%" style="text-align: center;"><span style="font-size: small;">'.$Total_Waliohudhuria_Between_1_Year_But_Below_5_Year_Male.'</span></td>
						<td width="5%" style="text-align: center;"><span style="font-size: small;">'.$Total_Waliohudhuria_Between_1_Year_But_Below_5_Year_Female.'</span></td>
						<td width="5%" style="text-align: center;"><span style="font-size: small;">'.$Grand_Total_Waliohudhuria_Between_1_Year_But_Below_5_Year.'</span></td>
					</tr>
				</table>
			</td>
			<td width="13%" style="text-align: center;">
				<table width="100%">
					<tr>
						<td width="5%" style="text-align: center;"><span style="font-size: small;">'.$Total_Waliohudhuria_Five_Years_Or_Below_Sixty_Years_Male.'</span></td>
						<td width="5%" style="text-align: center;"><span style="font-size: small;">'.$Total_Waliohudhuria_Five_Years_Or_Below_Sixty_Years_Female.'</span></td>
						<td width="5%" style="text-align: center;"><span style="font-size: small;">'.$Grand_Total__WaliohudhuriaFive_Years_Or_Below_Sixty_Years.'</span></td>
					</tr>
				</table>
			</td>
			<td width="13%" style="text-align: center;">
				<table width="100%">
					<tr>
						<td width="5%" style="text-align: center;"><span style="font-size: small;">'.$Total_Waliohudhuria_60_Years_And_Above_Male.'</span></td>
						<td width="5%" style="text-align: center;"><span style="font-size: small;">'.$Total_Waliohudhuria_60_Years_And_Above_Female.'</span></td>
						<td width="5%" style="text-align: center;"><span style="font-size: small;">'.$Grand_Total_Waliohudhuria_60_Years_And_Above.'</span></td>
					</tr>
				</table>
			</td>
			<td width="13%" style="text-align: center;">
				<table width="100%">
					<tr>
						<td width="5%" style="text-align: center;"><span style="font-size: small;">'.$Total_Waliohudhuria_Male.'</span></td>
						<td width="5%" style="text-align: center;"><span style="font-size: small;">'.$Total_Waliohudhuria_Female.'</span></td>
						<td width="5%" style="text-align: center;"><span style="font-size: small;">'.($Total_Waliohudhuria_Male + $Total_Waliohudhuria_Female).'</span></td>
					</tr>
				</table>
			</td>
		</tr>';

	
	//display Mahudhurio ya marudio
	$disp .= '<tr>
			<td width="5%" style="text-align: center;"><span style="font-size: small;">3</span></td>
			<td><span style="font-size: small;">Mahudhurio ya Marudio</span></td>
			<td width="13%" style="text-align: center;">
				<table width="100%">
					<tr>
						<td width="5%" style="text-align: center;"><span style="font-size: small;">'.$Total_Marudio_Below_1_Month_Male.'</span></td>
						<td width="5%" style="text-align: center;"><span style="font-size: small;">'.$Total_Marudio_Below_1_Month_Female.'</span></td>
						<td width="5%" style="text-align: center;"><span style="font-size: small;">'.$Grand_Total_Marudio_Below_1_Month.'</span></td>
					</tr>
				</table>
			</td>
			<td width="13%" style="text-align: center;">
				<table width="100%">
					<tr>
						<td width="5%" style="text-align: center;"><span style="font-size: small;">'.$Total_Marudio_Between_1_Month_But_Below_1_Year_Male.'</span></td>
						<td width="5%" style="text-align: center;"><span style="font-size: small;">'.$Total_Marudio_Between_1_Month_But_Below_1_Year_Female.'</span></td>
						<td width="5%" style="text-align: center;"><span style="font-size: small;">'.$Grand_Total_Marudio_Between_1_Month_But_Below_1_Year.'</span></td>
					</tr>
				</table>
			</td>
			<td width="13%" style="text-align: center;">
				<table width="100%">
					<tr>
						<td width="5%" style="text-align: center;"><span style="font-size: small;">'.$Total_Marudio_Between_1_Year_But_Below_5_Year_Male.'</span></td>
						<td width="5%" style="text-align: center;"><span style="font-size: small;">'.$Total_Marudio_Between_1_Year_But_Below_5_Year_Female.'</span></td>
						<td width="5%" style="text-align: center;"><span style="font-size: small;">'.$Grand_Total_Marudio_Between_1_Year_But_Below_5_Year.'</span></td>
					</tr>
				</table>
			</td>
			<td width="13%" style="text-align: center;">
				<table width="100%">
					<tr>
						<td width="5%" style="text-align: center;"><span style="font-size: small;">'.$Total_Marudio_Five_Years_Or_Below_Sixty_Years_Male.'</span></td>
						<td width="5%" style="text-align: center;"><span style="font-size: small;">'.$Total_Marudio_Five_Years_Or_Below_Sixty_Years_Female.'</span></td>
						<td width="5%" style="text-align: center;"><span style="font-size: small;">'.$Grand_Total__MarudioFive_Years_Or_Below_Sixty_Years.'</span></td>
					</tr>
				</table>
			</td>
			<td width="13%" style="text-align: center;">
				<table width="100%">
					<tr>
						<td width="5%" style="text-align: center;"><span style="font-size: small;">'.$Total_Marudio_60_Years_And_Above_Male.'</span></td>
						<td width="5%" style="text-align: center;"><span style="font-size: small;">'.$Total_Marudio_60_Years_And_Above_Female.'</span></td>
						<td width="5%" style="text-align: center;"><span style="font-size: small;">'.$Grand_Total_Marudio_60_Years_And_Above.'</span></td>
					</tr>
				</table>
			</td>
			<td width="13%" style="text-align: center;">
				<table width="100%">
					<tr>
						<td width="5%" style="text-align: center;"><span style="font-size: small;">'.$Total_Marudio_Male.'</span></td>
						<td width="5%" style="text-align: center;"><span style="font-size: small;">'.$Total_Marudio_Female.'</span></td>
						<td width="5%" style="text-align: center;"><span style="font-size: small;">'.($Total_Marudio_Male + $Total_Marudio_Female).'</span></td>
					</tr>
				</table>
			</td>
		</tr><tr><td colspan=8><hr></td></tr>';


	//get all disease groups
	$select_groups = mysqli_query($conn,"select * from tbl_disease_group where Opd_Report = 'yes' order by Opd_disease_Form_Id") or die(mysqli_error($conn));
	$group_num = mysqli_num_rows($select_groups);
	if($group_num > 0){
		//get details.....
		while($details = mysqli_fetch_array($select_groups)){
			$disease_group_id = $details['disease_group_id'];
			$disease_group_name = $details['disease_group_name'];
			$Gender_Type = $details['Gender_Type'];
			$dhis_Form_Id = $details['Opd_disease_Form_Id'];
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
					//count totat - gender and age must control the process
					$select = mysqli_query($conn,"select pr.Date_Of_Birth, pr.Gender, dc.Disease_Consultation_Date_And_Time
												from tbl_consultation c, tbl_disease_consultation dc, tbl_patient_registration pr where
												c.consultation_ID = dc.consultation_ID and
												pr.Registration_ID = c.Registration_ID and
												dc.diagnosis_type = '$diagnosis_type' and
												dc.disease_id = '$disease_id' and
												dc.Disease_Consultation_Date_And_Time between '$Start_Date' and '$End_Date'") or die(mysqli_error($conn));
					$num_total_details = mysqli_num_rows($select);
					if($num_total_details > 0){
						while($data = mysqli_fetch_array($select)){
							
							//generate patient age (Should be Date of birth agains Disease_Consultation_Date)
							$Date_Of_Birth = $data['Date_Of_Birth'];
							$Gender = $data['Gender'];
							$Disease_Consultation_Date = $data['Disease_Consultation_Date_And_Time'];
							$date1 = new DateTime($Disease_Consultation_Date);
							$date2 = new DateTime($Date_Of_Birth);
							$diff = $date1 -> diff($date2);
							$Years = $diff->y;
							$Months = $diff->m;
							$Days = $diff->d;
							$Status = '';
							
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

								if(strtolower($Five_Years_Or_Below_Sixty_Years) == 'yes'){
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
								}

								if(strtolower($Age_60_Years_And_Above) == 'yes'){
									if ($Status == 'Age' && $age >= 60 && strtolower($Gender) == 'male') {
										$Total_Age_60_Years_And_Above_Male += 1;
										$Grand_Total_Age_60_Years_And_Above += 1;
										$Total_Male += 1;
										$Grand_Total_Male += 1;
									}else if ($Status == 'Age' && $age >= 60 && strtolower($Gender) == 'female') {
										$Total_Age_60_Years_And_Above_Female += 1;
										$Grand_Total_Age_60_Years_And_Above += 1;
										$Total_Female += 1;
										$Grand_Total_Female += 1;
									}
								}else if(strtolower($Age_60_Years_And_Above) == 'no'){
									$Total_Age_60_Years_And_Above_Male = 'NULL';
									$Total_Age_60_Years_And_Above_Female = 'NULL';
									$Grand_Total_Age_60_Years_And_Above = 'NULL';
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
	
		/*$disp .= '
		<tr>
			<td width="5%" style="text-align: center;"><span style="font-size: small;">'.$dhis_Form_Id.'</span></td>
			<td><span style="font-size: small;">'.$disease_group_name.'</span></td>
			<td width="13%" style="text-align: center;">
				<table width="100%">
					<tr>
						<td style="text-align: center;" width="5%"><span style="font-size: small;">'.$Total_Age_Below_1_Month_Male.'</span></td>
						<td style="text-align: center;" width="5%"><span style="font-size: small;">'.$Total_Age_Below_1_Month_Female.'</span></td>
						<td style="text-align: center;" width="5%"><span style="font-size: small;">'.$Grand_Total_Age_Below_1_Month.'</span></td>
					</tr>
				</table>
			</td>
			<td width="13%" style="text-align: center;">
				<table width="100%">
					<tr>
						<td style="text-align: center;" width="5%"><span style="font-size: small;">'.$Total_Age_Between_1_Month_But_Below_1_Year_Male.'</span></td>
						<td style="text-align: center;" width="5%"><span style="font-size: small;">'.$Total_Age_Between_1_Month_But_Below_1_Year_Female.'</span></td>
						<td style="text-align: center;" width="5%"><span style="font-size: small;">'.$Grand_Total_Age_Between_1_Month_But_Below_1_Year.'</span></td>
					</tr>
				</table>
			</td>
			<td width="13%" style="text-align: center;">
				<table width="100%">
					<tr>
						<td style="text-align: center;" width="5%"><span style="font-size: small;">'.$Total_Age_Between_1_Year_But_Below_5_Year_Male.'</span></td>
						<td style="text-align: center;" width="5%"><span style="font-size: small;">'.$Total_Age_Between_1_Year_But_Below_5_Year_Female.'</span></td>
						<td style="text-align: center;" width="5%"><span style="font-size: small;">'.$Grand_Total_Age_Between_1_Year_But_Below_5_Year.'</span></td>
					</tr>
				</table>
			</td>
			<td width="13%" style="text-align: center;">
				<table width="100%">
					<tr>
						<td style="text-align: center;" width="5%"><span style="font-size: small;">'.$Total_Five_Years_Or_Below_Sixty_Years_Male.'</span></td>
						<td style="text-align: center;" width="5%"><span style="font-size: small;">'.$Total_Five_Years_Or_Below_Sixty_Years_Female.'</span></td>
						<td style="text-align: center;" width="5%"><span style="font-size: small;">'.$Grand_Total_Five_Years_Or_Below_Sixty_Years.'</span></td>
					</tr>
				</table>
			</td>
			<td width="13%" style="text-align: center;">
				<table width="100%">
					<tr>
						<td style="text-align: center;" width="5%"><span style="font-size: small;">'.$Total_Age_60_Years_And_Above_Male.'</span></td>
						<td style="text-align: center;" width="5%"><span style="font-size: small;">'.$Total_Age_60_Years_And_Above_Female.'</span></td>
						<td style="text-align: center;" width="5%"><span style="font-size: small;">'.$Grand_Total_Age_60_Years_And_Above.'</span></td>
					</tr>
				</table>
			</td>
			<td width="13%" style="text-align: center;">
				<table width="100%">
					<tr>
						<td style="text-align: center;" width="5%"><span style="font-size: small;">'.$Total_Male.'</span></td>
						<td style="text-align: center;" width="5%"><span style="font-size: small;">'.$Total_Female.'</span></td>
						<td style="text-align: center;" width="5%"><span style="font-size: small;">'.($Total_Male + $Total_Female).'</span></td>
					</tr>
				</table>
			</td>
		</tr>';
*/

		$disp .= '<tr>
			<td width="5%" style="text-align: center;"><span style="font-size: small;">'.$dhis_Form_Id.'</span></td>
			<td><span style="font-size: small;">'.$disease_group_name.'</span></td>
			<td width="13%" style="text-align: center;">
				<table width="100%">
					<tr>
						<td style="text-align: left;" width="5%"><span style="font-size: small;">'.$Total_Age_Below_1_Month_Male.'</span></td>
						<td style="text-align: center;" width="5%"><span style="font-size: small;">'.$Total_Age_Below_1_Month_Female.'</span></td>
						<td style="text-align: center;" width="5%"><span style="font-size: small;">'.$Grand_Total_Age_Below_1_Month.'</span></td>
					</tr>
				</table>
			</td>
			<td width="13%" style="text-align: center;">
				<table width="100%">
					<tr>
						<td style="text-align: left;" width="5%"><span style="font-size: small;">'.$Total_Age_Between_1_Month_But_Below_1_Year_Male.'</span></td>
						<td style="text-align: center;" width="5%"><span style="font-size: small;">'.$Total_Age_Between_1_Month_But_Below_1_Year_Female.'</span></td>
						<td style="text-align: center;" width="5%"><span style="font-size: small;">'.$Grand_Total_Age_Between_1_Month_But_Below_1_Year.'</span></td>
					</tr>
				</table>
			</td>
			<td width="13%" style="text-align: center;">
				<table width="100%">
					<tr>
						<td style="text-align: left;" width="5%"><span style="font-size: small;">'.$Total_Age_Between_1_Year_But_Below_5_Year_Male.'</span></td>
						<td style="text-align: center;" width="5%"><span style="font-size: small;">'.$Total_Age_Between_1_Year_But_Below_5_Year_Female.'</span></td>
						<td style="text-align: center;" width="5%"><span style="font-size: small;">'.$Grand_Total_Age_Between_1_Year_But_Below_5_Year.'</span></td>
					</tr>
				</table>
			</td>
			<td width="13%" style="text-align: center;">
				<table width="100%">
					<tr>
						<td style="text-align: left;" width="5%"><span style="font-size: small;">'.$Total_Five_Years_Or_Below_Sixty_Years_Male.'</span></td>
						<td style="text-align: center;" width="5%"><span style="font-size: small;">'.$Total_Five_Years_Or_Below_Sixty_Years_Female.'</span></td>
						<td style="text-align: center;" width="5%"><span style="font-size: small;">'.$Grand_Total_Five_Years_Or_Below_Sixty_Years.'</span></td>
					</tr>
				</table>
			</td>
			<td width="13%" style="text-align: center;">
				<table width="100%">
					<tr>
						<td style="text-align: left;" width="5%"><span style="font-size: small;">'.$Total_Age_60_Years_And_Above_Male.'</span></td>
						<td style="text-align: center;" width="5%"><span style="font-size: small;">'.$Total_Age_60_Years_And_Above_Female.'</span></td>
						<td style="text-align: center;" width="5%"><span style="font-size: small;">'.$Grand_Total_Age_60_Years_And_Above.'</span></td>
					</tr>
				</table>
			</td>
			<td width="13%" style="text-align: center;">
				<table width="100%">
					<tr>
						<td style="text-align: left;" width="5%"><span style="font-size: small;">'.$Total_Male.'</span></td>
						<td style="text-align: center;" width="5%"><span style="font-size: small;">'.$Total_Female.'</span></td>
						<td style="text-align: center;" width="5%"><span style="font-size: small;">'.($Total_Male + $Total_Female).'</span></td>
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

	$disp .= "</table>";
	include("MPDF/mpdf.php");

    //$mpdf=new mPDF('','Letter-L',0,'',12.7,12.7,14,12.7,8,8); 
    $mpdf=new mPDF('c','A3-L'); 

    $mpdf->WriteHTML($disp);
    $mpdf->Output();
    exit;
?>