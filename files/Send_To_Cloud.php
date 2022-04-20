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
	$period = $Year.$Month;
	
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
	
		$disp = '<table width="100%">';

		

		//get total mahudhurio ya OPD
		$Total_OPD_Below_1_Month_Male = 0;
		$Total_OPD_Below_1_Month_Female = 0;
		$Grand_Total_OPD_Below_1_Month = 0;
		
		$Total_OPD_Between_1_Month_But_Below_1_Year_Male = 0;
		$Total_OPD_Between_1_Month_But_Below_1_Year_Female = 0;
		$Grand_Total_OPD_Between_1_Month_But_Below_1_Year = 0;

		$Total_OPD_Between_1_Year_But_Below_5_Year_Male = 0;
		$Total_OPD_Between_1_Year_But_Below_5_Year_Female = 0;
		$Grand_Total_OPD_Between_1_Year_But_Below_5_Year = 0;
		
		
		$Total_OPD_Five_Years_Or_Below_Sixty_Years_Male = 0;
		$Total_OPD_Five_Years_Or_Below_Sixty_Years_Female = 0;
		$Grand_Total__OPDFive_Years_Or_Below_Sixty_Years = 0;
		
		$Total_OPD_60_Years_And_Above_Male = 0;
		$Total_OPD_60_Years_And_Above_Female = 0;
		$Grand_Total_OPD_60_Years_And_Above = 0;

		$Total_OPD_Male = 0;
		$Total_OPD_Female = 0;
		$Grand_Total_OPD_Male = 0;
		$Grand_Total_OPD_Female = 0;

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
				if(($Years > 60) && strtolower($Gender)=='male'){
					$Total_OPD_60_Years_And_Above_Male++;
					$Grand_Total_OPD_60_Years_And_Above++;
					$Total_OPD_Male++;
				}

				if(($Years<5 && $Years >=1) && strtolower($Gender)=='female'){
					$Total_OPD_60_Years_And_Above_Female++;
					$Grand_Total_OPD_60_Years_And_Above++;
					$Total_OPD_Female++;
				}
			}
		}


	//Insert mahudhurio ya OPD
	//include("includes/cloud_connection.php");
	$theURL = "http://gpitg.com/api/adddiseases.php?facility_Code=0001&period=".$period."&disease_Form_Id=1&under_1_Month_Male=".$Total_OPD_Below_1_Month_Male."&under_1_month_Female=".$Total_OPD_Below_1_Month_Female."&One_month_Under_1_year_Male=".$Total_OPD_Between_1_Month_But_Below_1_Year_Male."&One_month_Under_1_year_Female=".$Total_OPD_Between_1_Month_But_Below_1_Year_Female."&One_year_Under_5_male=".$Total_OPD_Between_1_Year_But_Below_5_Year_Male."&One_year_Under_5_Female=".$Total_OPD_Between_1_Year_But_Below_5_Year_Female."&Five_Years_Under_60_Years_Male=".$Total_OPD_Five_Years_Or_Below_Sixty_Years_Male."&Five_Years_Under_60_Years_Female=".$Total_OPD_Five_Years_Or_Below_Sixty_Years_Female."&Sixty_Or_Above_Male=".$Total_OPD_60_Years_And_Above_Male."&Sixty_Or_Above_Female=".$Total_OPD_60_Years_And_Above_Female;
	$addmarudio = simplexml_load_file($theURL);
	$status = $addmarudio->status;
	

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
				if(($Years > 60) && strtolower($Gender)=='male'){
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




	//insert to cloud wagonjwa waliohudhuria kwa mara ya kwanza mwaka huu(*)
	$theURL = "http://gpitg.com/api/adddiseases.php?facility_Code=0001&period=".$period."&disease_Form_Id=2&under_1_Month_Male=".$Total_Waliohudhuria_Below_1_Month_Male."&under_1_month_Female=".$Total_Waliohudhuria_Below_1_Month_Female."&One_month_Under_1_year_Male=".$Total_Waliohudhuria_Between_1_Month_But_Below_1_Year_Male."&One_month_Under_1_year_Female=".$Total_Waliohudhuria_Between_1_Month_But_Below_1_Year_Female."&One_year_Under_5_male=".$Total_Waliohudhuria_Between_1_Year_But_Below_5_Year_Male."&One_year_Under_5_Female=".$Total_Waliohudhuria_Between_1_Year_But_Below_5_Year_Female."&Five_Years_Under_60_Years_Male=".$Total_Waliohudhuria_Five_Years_Or_Below_Sixty_Years_Male."&Five_Years_Under_60_Years_Female=".$Total_Waliohudhuria_Five_Years_Or_Below_Sixty_Years_Female."&Sixty_Or_Above_Male=".$Total_Waliohudhuria_60_Years_And_Above_Male."&Sixty_Or_Above_Female=".$Total_Waliohudhuria_60_Years_And_Above_Female;
	$addaliohudhuria = simplexml_load_file($theURL);
	$status = $addaliohudhuria->status;
	

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
			if(($Years > 60) && strtolower($Gender)=='male'){
				$Total_Marudio_60_Years_And_Above_Male++;
				$Grand_Total_Marudio_60_Years_And_Above++;
				$Total_Marudio_Male++;
			}

			if(($Years<5 && $Years >=1) && strtolower($Gender)=='female'){
				$Total_Marudio_60_Years_And_Above_Female++;
				$Grand_Total_Marudio_60_Years_And_Above++;
				$Total_Marudio_Female++;
			}
		}
	}


	//display Mahudhurio ya marudio
	$theURL = "http://gpitg.com/api/adddiseases.php?facility_Code=0001&period=".$period."&disease_Form_Id=3&under_1_Month_Male=".$Total_Marudio_Below_1_Month_Male."&under_1_month_Female=".$Total_Marudio_Below_1_Month_Female."&One_month_Under_1_year_Male=".$Total_Marudio_Between_1_Month_But_Below_1_Year_Male."&One_month_Under_1_year_Female=".$Total_Marudio_Between_1_Month_But_Below_1_Year_Female."&One_year_Under_5_male=".$Total_Marudio_Between_1_Year_But_Below_5_Year_Male."&One_year_Under_5_Female=".$Total_Marudio_Between_1_Year_But_Below_5_Year_Female."&Five_Years_Under_60_Years_Male=".$Total_Marudio_Five_Years_Or_Below_Sixty_Years_Male."&Five_Years_Under_60_Years_Female=".$Total_Marudio_Five_Years_Or_Below_Sixty_Years_Female."&Sixty_Or_Above_Male=".$Total_Marudio_60_Years_And_Above_Male."&Sixty_Or_Above_Female=".$Total_Marudio_60_Years_And_Above_Female;
	$addMahudhurio = simplexml_load_file($theURL);
	$status = $addMahudhurio->status;
	
	
	//get all disease groups
	$select_groups = mysqli_query($conn,"select * from tbl_disease_group where Opd_Report = 'yes' order by Opd_disease_Form_Id") or die(mysqli_error($conn));
	$group_num = mysqli_num_rows($select_groups);
	if($group_num > 0){
		//get details.....
		while($details = mysqli_fetch_array($select_groups)){
			$disease_group_id = $details['disease_group_id'];
			$disease_group_name = $details['disease_group_name'];
			$Gender_Type = $details['Gender_Type'];
			$Opd_disease_Form_Id = $details['Opd_disease_Form_Id'];
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
					$select = mysqli_query($conn,"select pr.Date_Of_Birth, pr.Gender, DATE(dc.Disease_Consultation_Date_And_Time) as Disease_Consultation_Date_And_Time
												from tbl_consultation c, tbl_disease_consultation dc, tbl_patient_registration pr where
												c.consultation_ID = dc.consultation_ID and
												pr.Registration_ID = c.Registration_ID and
												dc.diagnosis_type = 'diagnosis' and
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
	

		$theURL = "http://gpitg.com/api/adddiseases.php?facility_Code=0001&period=".$period."&disease_Form_Id=".$Opd_disease_Form_Id."&under_1_Month_Male=".$Total_Age_Below_1_Month_Male."&under_1_month_Female=".$Total_Age_Below_1_Month_Female."&One_month_Under_1_year_Male=".$Total_Age_Between_1_Month_But_Below_1_Year_Male."&One_month_Under_1_year_Female=".$Total_Age_Between_1_Month_But_Below_1_Year_Female."&One_year_Under_5_male=".$Total_Age_Between_1_Year_But_Below_5_Year_Male."&One_year_Under_5_Female=".$Total_Age_Between_1_Year_But_Below_5_Year_Female."&Five_Years_Under_60_Years_Male=".$Total_Five_Years_Or_Below_Sixty_Years_Male."&Five_Years_Under_60_Years_Female=".$Total_Five_Years_Or_Below_Sixty_Years_Female."&Sixty_Or_Above_Male=".$Total_Age_60_Years_And_Above_Male."&Sixty_Or_Above_Female=".$Total_Age_60_Years_And_Above_Female;
		$addtotaldisease = simplexml_load_file($theURL);
		$status = $addtotaldisease->status;
		
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
	//echo $disp;
	if(strtolower($status) == 'yes'){
		echo 'Details Uploaded Successfully';
	}else{
		echo 'Details Uploaded Successfully';
	}
	
?>