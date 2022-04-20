<?php
    @session_start();
    include("./includes/connection.php");
    $temp = 4;
    $GrandTotal = 0;
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }

	$Today_Date = mysqli_query($conn,"select now() as today");
	while($row = mysqli_fetch_array($Today_Date)){
	    $original_Date = $row['today'];
	    $new_Date = date("Y-m-d", strtotime($original_Date));
	    $Today = $new_Date;
	    $age ='';
	}
	
    //declare all age category variable
    $Chini_Ya_Mwezi_Mmoja_Me = 0;
    $Chini_Ya_Mwezi_Mmoja_Ke = 0;
    
    
    $Mwezi_Mmoja_Hadi_Chini_Ya_Mwaka_Mmoja_Me = 0;
    $Mwezi_Mmoja_Hadi_Chini_Ya_Mwaka_Mmoja_Ke = 0;
    
    $Mwaka_Mmoja_Hadi_Chini_Ya_Miaka_Mitano_Me = 0;
    $Mwaka_Mmoja_Hadi_Chini_Ya_Miaka_Mitano_Ke = 0;
    
    $Miaka_Mitano_Au_Zaidi_Me = 0;
    $Miaka_Mitano_Au_Zaidi_Ke = 0;
    
    $date = explode(('-'),$_GET['from_date']);
    $period = $date[0].$date[1];
    $facilityCode = '0001';

    //get diseases
	    
	//declare all age category variable
    $Chini_Ya_Mwezi_Mmoja_Me = 0;
    $Chini_Ya_Mwezi_Mmoja_Ke = 0;
    
    
    $Mwezi_Mmoja_Hadi_Chini_Ya_Mwaka_Mmoja_Me = 0;
    $Mwezi_Mmoja_Hadi_Chini_Ya_Mwaka_Mmoja_Ke = 0;
    
    $Mwaka_Mmoja_Hadi_Chini_Ya_Miaka_Mitano_Me = 0;
    $Mwaka_Mmoja_Hadi_Chini_Ya_Miaka_Mitano_Ke = 0;
    
    $Miaka_Mitano_Au_Zaidi_Me = 0;
    $Miaka_Mitano_Au_Zaidi_Ke = 0;
	
	$numDays = @cal_days_in_month(CAL_GREGORIAN,substr($_GET['from_date'],5,6), substr($_GET['from_date'],0,3));
    //substr(string, start)

    if(isset($_GET['from_date'])){
    	$from_date = $_GET['from_date'];
    	$from_date = substr_replace($from_date,'01',8);
    }else {
    	$from_date = "0000-00-00";
    }

    if(isset($_GET['to_date'])){
    	$to_date = $_GET['to_date'];
    	$to_date = substr_replace($to_date,$numDays,8);
    }else {
    	$to_date = "0000-00-00";
    }

    if($original_Date[5].$original_Date[6]== $to_date[5].$to_date[6]){
    	echo "<center>NO DATA FOR THE SELECTED MONTH</center>";
    	exit;
    }

	$selectQr = "SELECT chk.Check_In_Date,chk.Visit_Date,pr.Gender,pr.Date_Of_Birth FROM tbl_check_in chk, tbl_patient_registration pr 
				 WHERE chk.Registration_ID = pr.Registration_ID AND chk.Visit_Date BETWEEN '".$_GET['from_date']."' AND '".$_GET['to_date']."'";
	
	$result = mysqli_query($conn,$selectQr);
	while($row = mysqli_fetch_assoc($result)){
		$Gender = $row['Gender'];
		$Date_Of_Birth = $row['Date_Of_Birth'];
		$Check_In_Date = $row['Check_In_Date'];

		$date1 = new DateTime($Check_In_Date);
		$date2 = new DateTime($Date_Of_Birth);
		$diff = $date1 -> diff($date2);
		$Years = $diff->y;
		$Months = $diff->m;
		$Days = $diff->d;
		
		//Chini Ya Mwezi mmoja
		if($Years==0 && $Months==0 && strtolower($Gender)=='male'){
			$Chini_Ya_Mwezi_Mmoja_Me++;
		}

		if($Years==0 && $Months==0 && strtolower($Gender)=='female'){
			$Chini_Ya_Mwezi_Mmoja_Ke++;	
		}

		//Chini Ya Mwaka mmoja
		if($Years==0 && $Months>=1 && strtolower($Gender)=='male'){
			$Mwezi_Mmoja_Hadi_Chini_Ya_Mwaka_Mmoja_Me++;
		}

		if($Years==0 && $Months>=1 && strtolower($Gender)=='female'){
			$Mwezi_Mmoja_Hadi_Chini_Ya_Mwaka_Mmoja_Ke++;	
		}

		//Chini Ya Miaka Mitano na Mwaka mmoja
		if(($Years<5 && $Years >=1) && strtolower($Gender)=='male'){
			$Mwaka_Mmoja_Hadi_Chini_Ya_Miaka_Mitano_Me++;
		}

		if(($Years<5 && $Years >=1) && strtolower($Gender)=='female'){
			$Mwaka_Mmoja_Hadi_Chini_Ya_Miaka_Mitano_Ke++;	
		}

		//Chini Ya Miaka Mitano na Mwaka mmoja
		if($Years>=5 && strtolower($Gender)=='male'){
			$Miaka_Mitano_Au_Zaidi_Me++;
		}

		if($Years>=5 && strtolower($Gender)=='female'){
			$Miaka_Mitano_Au_Zaidi_Ke++;	
		}
	}
	
    $htm = "INSERT INTO tbl_opd (facility_Code ,period,disease_Form_Id,under_1_Month_Male,under_1_month_Female,1_month_Under_1_yearMale,1_month_Under_1_year_Female,1_year_Under_5_male,1_year_Under_5_Female ,5_Orabove_Male,5_Or_above_female) VALUES('".$facilityCode."','".$period."',1,".$Chini_Ya_Mwezi_Mmoja_Me.",".$Chini_Ya_Mwezi_Mmoja_Ke.",".$Mwezi_Mmoja_Hadi_Chini_Ya_Mwaka_Mmoja_Me.",".$Mwezi_Mmoja_Hadi_Chini_Ya_Mwaka_Mmoja_Ke.",".$Mwaka_Mmoja_Hadi_Chini_Ya_Miaka_Mitano_Me.",".$Mwaka_Mmoja_Hadi_Chini_Ya_Miaka_Mitano_Ke.",".$Miaka_Mitano_Au_Zaidi_Me.",".$Miaka_Mitano_Au_Zaidi_Ke."); ";
		
	$Chini_Ya_Mwezi_Mmoja_Me = 0;
    $Chini_Ya_Mwezi_Mmoja_Ke = 0;
    
    
    $Mwezi_Mmoja_Hadi_Chini_Ya_Mwaka_Mmoja_Me = 0;
    $Mwezi_Mmoja_Hadi_Chini_Ya_Mwaka_Mmoja_Ke = 0;
    
    $Mwaka_Mmoja_Hadi_Chini_Ya_Miaka_Mitano_Me = 0;
    $Mwaka_Mmoja_Hadi_Chini_Ya_Miaka_Mitano_Ke = 0;
    
    $Miaka_Mitano_Au_Zaidi_Me = 0;
    $Miaka_Mitano_Au_Zaidi_Ke = 0;


	$selectQr = "SELECT chk.Check_In_Date,chk.Visit_Date,pr.Gender,pr.Date_Of_Birth FROM tbl_check_in chk, tbl_patient_registration pr 
				 WHERE chk.Registration_ID = pr.Registration_ID AND  chk.Visit_Date BETWEEN '".$_GET['from_date']."' AND '".$_GET['to_date']."'
				 AND pr.Registration_Date BETWEEN '".$_GET['from_date']."' AND '".$_GET['to_date']."' GROUP BY pr.Registration_ID";
	
	$result = mysqli_query($conn,$selectQr);
	while($row = mysqli_fetch_assoc($result)){
		$Gender = $row['Gender'];
		$Date_Of_Birth = $row['Date_Of_Birth'];
		$Check_In_Date = $row['Check_In_Date'];

		$date1 = new DateTime($Check_In_Date);
		$date2 = new DateTime($Date_Of_Birth);
		$diff = $date1 -> diff($date2);
		$Years = $diff->y;
		$Months = $diff->m;
		$Days = $diff->d;
		
		//Chini Ya Mwezi mmoja
		if($Years==0 && $Months==0 && strtolower($Gender)=='male'){
			$Chini_Ya_Mwezi_Mmoja_Me++;
		}

		if($Years==0 && $Months==0 && strtolower($Gender)=='female'){
			$Chini_Ya_Mwezi_Mmoja_Ke++;	
		}

		//Chini Ya Mwaka mmoja
		if($Years==0 && $Months>=1 && strtolower($Gender)=='male'){
			$Mwezi_Mmoja_Hadi_Chini_Ya_Mwaka_Mmoja_Me++;
		}

		if($Years==0 && $Months>=1 && strtolower($Gender)=='female'){
			$Mwezi_Mmoja_Hadi_Chini_Ya_Mwaka_Mmoja_Ke++;	
		}

		//Chini Ya Miaka Mitano na Mwaka mmoja
		if(($Years<5 && $Years >=1) && strtolower($Gender)=='male'){
			$Mwaka_Mmoja_Hadi_Chini_Ya_Miaka_Mitano_Me++;
		}

		if(($Years<5 && $Years >=1) && strtolower($Gender)=='female'){
			$Mwaka_Mmoja_Hadi_Chini_Ya_Miaka_Mitano_Ke++;	
		}

		//Chini Ya Miaka Mitano na Mwaka mmoja
		if($Years>=5 && strtolower($Gender)=='male'){
			$Miaka_Mitano_Au_Zaidi_Me++;
		}

		if($Years>=5 && strtolower($Gender)=='female'){
			$Miaka_Mitano_Au_Zaidi_Ke++;	
		}
	}
		
		
		
    $htm .= "INSERT INTO tbl_opd(facility_Code ,
    	period,disease_Form_Id,
    	under_1_Month_Male,
    	under_1_month_Female,
    	1_month_Under_1_yearMale,
    	1_month_Under_1_year_Female,
    	1_year_Under_5_male,
    	1_year_Under_5_Female ,
    	5_Orabove_Male,
    	5_Or_above_female) VALUES('".$facilityCode."','".$period."',2,".$Chini_Ya_Mwezi_Mmoja_Me.",".$Chini_Ya_Mwezi_Mmoja_Ke.",".$Mwezi_Mmoja_Hadi_Chini_Ya_Mwaka_Mmoja_Me.",".$Mwezi_Mmoja_Hadi_Chini_Ya_Mwaka_Mmoja_Ke.",".$Mwaka_Mmoja_Hadi_Chini_Ya_Miaka_Mitano_Me.",".$Mwaka_Mmoja_Hadi_Chini_Ya_Miaka_Mitano_Ke.",".$Miaka_Mitano_Au_Zaidi_Me.",".$Miaka_Mitano_Au_Zaidi_Ke."); ";
		
	//declare all age category variable
    $Chini_Ya_Mwezi_Mmoja_Me = 0;
    $Chini_Ya_Mwezi_Mmoja_Ke = 0;
    
    
    $Mwezi_Mmoja_Hadi_Chini_Ya_Mwaka_Mmoja_Me = 0;
    $Mwezi_Mmoja_Hadi_Chini_Ya_Mwaka_Mmoja_Ke = 0;
    
    $Mwaka_Mmoja_Hadi_Chini_Ya_Miaka_Mitano_Me = 0;
    $Mwaka_Mmoja_Hadi_Chini_Ya_Miaka_Mitano_Ke = 0;
    
    $Miaka_Mitano_Au_Zaidi_Me = 0;
    $Miaka_Mitano_Au_Zaidi_Ke = 0;
		
	$selectQr = "SELECT chk.Check_In_Date,chk.Visit_Date,pr.Gender,pr.Date_Of_Birth FROM tbl_check_in chk, tbl_patient_registration pr 
				 WHERE chk.Registration_ID = pr.Registration_ID AND  chk.Visit_Date BETWEEN '".$_GET['from_date']."' AND '".$_GET['to_date']."'
				 AND (pr.Registration_Date < chk.Visit_Date)";
	
	$result = mysqli_query($conn,$selectQr);
	while($row = mysqli_fetch_assoc($result)){
		$Gender = $row['Gender'];
		$Date_Of_Birth = $row['Date_Of_Birth'];
		$Check_In_Date = $row['Check_In_Date'];

		$date1 = new DateTime($Check_In_Date);
		$date2 = new DateTime($Date_Of_Birth);
		$diff = $date1 -> diff($date2);
		$Years = $diff->y;
		$Months = $diff->m;
		$Days = $diff->d;
		
		//Chini Ya Mwezi mmoja
		if($Years==0 && $Months==0 && strtolower($Gender)=='male'){
			$Chini_Ya_Mwezi_Mmoja_Me++;
		}

		if($Years==0 && $Months==0 && strtolower($Gender)=='female'){
			$Chini_Ya_Mwezi_Mmoja_Ke++;	
		}

		//Chini Ya Mwaka mmoja
		if($Years==0 && $Months>=1 && strtolower($Gender)=='male'){
			$Mwezi_Mmoja_Hadi_Chini_Ya_Mwaka_Mmoja_Me++;
		}

		if($Years==0 && $Months>=1 && strtolower($Gender)=='female'){
			$Mwezi_Mmoja_Hadi_Chini_Ya_Mwaka_Mmoja_Ke++;	
		}

		//Chini Ya Miaka Mitano na Mwaka mmoja
		if(($Years<5 && $Years >=1) && strtolower($Gender)=='male'){
			$Mwaka_Mmoja_Hadi_Chini_Ya_Miaka_Mitano_Me++;
		}

		if(($Years<5 && $Years >=1) && strtolower($Gender)=='female'){
			$Mwaka_Mmoja_Hadi_Chini_Ya_Miaka_Mitano_Ke++;	
		}

		//Chini Ya Miaka Mitano na Mwaka mmoja
		if($Years>=5 && strtolower($Gender)=='male'){
			$Miaka_Mitano_Au_Zaidi_Me++;
		}

		if($Years>=5 && strtolower($Gender)=='female'){
			$Miaka_Mitano_Au_Zaidi_Ke++;	
		}
	}
		
    $htm .= "INSERT INTO tbl_opd(facility_Code ,period,disease_Form_Id,under_1_Month_Male,under_1_month_Female,1_month_Under_1_yearMale,1_month_Under_1_year_Female,1_year_Under_5_male,1_year_Under_5_Female ,5_Orabove_Male,5_Or_above_female) VALUES('".$facilityCode."','".$period."',3,".$Chini_Ya_Mwezi_Mmoja_Me.",".$Chini_Ya_Mwezi_Mmoja_Ke.",".$Mwezi_Mmoja_Hadi_Chini_Ya_Mwaka_Mmoja_Me.",".$Mwezi_Mmoja_Hadi_Chini_Ya_Mwaka_Mmoja_Ke.",".$Mwaka_Mmoja_Hadi_Chini_Ya_Miaka_Mitano_Me.",".$Mwaka_Mmoja_Hadi_Chini_Ya_Miaka_Mitano_Ke.",".$Miaka_Mitano_Au_Zaidi_Me.",".$Miaka_Mitano_Au_Zaidi_Ke."); ";
		
	
	
    //resert all variable
    
    
    $Chini_Ya_Mwezi_Mmoja_Me = 0;
    $Chini_Ya_Mwezi_Mmoja_Ke = 0;
    $Chini_Ya_Mwezi_Mmoja_Me_Jumla = 0;
    $Chini_Ya_Mwezi_Mmoja_Ke_Jumla = 0;
    $Chini_Ya_Mwezi_Mmoja_Jumla = 0;    
    
    $Mwezi_Mmoja_Hadi_Chini_Ya_Mwaka_Mmoja_Me = 0;
    $Mwezi_Mmoja_Hadi_Chini_Ya_Mwaka_Mmoja_Ke = 0;
    $Mwezi_Mmoja_Hadi_Chini_Ya_Mwaka_Mmoja_Me_Jumla = 0;
    $Mwezi_Mmoja_Hadi_Chini_Ya_Mwaka_Mmoja_Ke_Jumla = 0;
    $Mwezi_Mmoja_Hadi_Chini_Ya_Mwaka_Mmoja_Jumla = 0;
    
    $Mwaka_Mmoja_Hadi_Chini_Ya_Miaka_Mitano_Me = 0;
    $Mwaka_Mmoja_Hadi_Chini_Ya_Miaka_Mitano_Ke = 0;
    $Mwaka_Mmoja_Hadi_Chini_Ya_Miaka_Mitano_Me_Jumla = 0;
    $Mwaka_Mmoja_Hadi_Chini_Ya_Miaka_Mitano_Ke_Jumla = 0;
    $Mwaka_Mmoja_Hadi_Chini_Ya_Miaka_Mitano_Jumla = 0;
    
    $Miaka_Mitano_Au_Zaidi_Me = 0;
    $Miaka_Mitano_Au_Zaidi_Ke = 0;
    $Miaka_Mitano_Au_Zaidi_Me_Jumla = 0;
    $Miaka_Mitano_Au_Zaidi_Ke_Jumla = 0;
    $Miaka_Mitano_Au_Zaidi_Jumla = 0;
    $Jumla = 0;

		
		
    $select_disease = mysqli_query($conn,"select * from tbl_disease_group where opd_report = 'yes' order by disease_group_id ASC") or die(mysqli_error($conn)); //selecting list of diseases
    while($disease = mysqli_fetch_array($select_disease)){
	$disease_group_id = $disease['disease_group_id'];
	$disease_group_name = $disease['disease_group_name'];
	$Age_Below_1_Month = $disease['Age_Below_1_Month'];
	$Age_Between_1_Month_But_Below_1_Year = $disease['Age_Between_1_Month_But_Below_1_Year'];
	$Age_Between_1_Year_But_Below_5_Year = $disease['Age_Between_1_Year_But_Below_5_Year'];
	$Age_Above_5_Years = $disease['Age_Above_5_Years'];
	$Gender_Type = $disease['Gender_Type'];
	$dhis_Form_Id = $disease['dhis_Form_Id'];
	
	
	//select all patient consulted from the select disease
	$sql = "select pr.Gender, pr.Date_Of_Birth, Disease_Consultation_Date_And_Time from tbl_disease_consultation dc, tbl_disease d, tbl_consultation c, tbl_patient_registration pr where
		    dc.consultation_ID = c.consultation_ID and
			dc.disease_ID = d.disease_ID and
			    pr.Registration_ID = c.Registration_ID and
				d.disease_ID IN (select disease_ID from tbl_disease_group_mapping where disease_group_id = $disease_group_id)";
	
	$result = mysqli_query($conn,$sql) or die(mysqli_error($conn));
	$no = mysqli_num_rows($result);
	if($no > 0){
	    //generate statistic based on info we get
	    while($row = mysqli_fetch_array($result)){
		//get date of birth and gender
		$Gender = $row['Gender']; $Date_Of_Birth = $row['Date_Of_Birth'];
		
		//generate patient age (Should be Date of birth agains Disease_Consultation_Date)
		$age = floor( (strtotime(date('Y-m-d')) - strtotime($Date_Of_Birth)) / 31556926)." Years";
		$Disease_Consultation_Date = $row['Disease_Consultation_Date_And_Time'];
		$date1 = new DateTime($Disease_Consultation_Date);
		$date2 = new DateTime($Date_Of_Birth);
		$diff = $date1 -> diff($date2);
		$Years = $diff->y;
		$Months = $diff->m;
		$Days = $diff->d;
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
		
		
		if(strtolower($Gender_Type) == 'both'){
		    
		    if(strtolower($Age_Above_5_Years) == 'yes'){
			if($age > 5 && $Status == 'Age' && strtolower($Gender) == 'male'){
			    $Miaka_Mitano_Au_Zaidi_Me = $Miaka_Mitano_Au_Zaidi_Me + 1;
			    $Miaka_Mitano_Au_Zaidi_Me_Jumla = $Miaka_Mitano_Au_Zaidi_Me_Jumla + 1;
			    $Miaka_Mitano_Au_Zaidi_Jumla = $Miaka_Mitano_Au_Zaidi_Jumla + 1;
			}elseif($age > 5 && $Status == 'Age' && strtolower($Gender) == 'female'){
			    $Miaka_Mitano_Au_Zaidi_Ke = $Miaka_Mitano_Au_Zaidi_Ke + 1;
			    $Miaka_Mitano_Au_Zaidi_Ke_Jumla = $Miaka_Mitano_Au_Zaidi_Ke_Jumla + 1;
			    $Miaka_Mitano_Au_Zaidi_Jumla = $Miaka_Mitano_Au_Zaidi_Jumla + 1;
			}
		    }elseif(strtolower($Age_Above_5_Years) == 'no'){
			$Miaka_Mitano_Au_Zaidi_Me = 'NULL';
			$Miaka_Mitano_Au_Zaidi_Ke = 'NULL';
			$Miaka_Mitano_Au_Zaidi_Me_Jumla = 'NULL';
			$Miaka_Mitano_Au_Zaidi_Ke_Jumla = 'NULL';
			$Miaka_Mitano_Au_Zaidi_Jumla = 'NULL';
		    }
		    
		    if(strtolower($Age_Between_1_Year_But_Below_5_Year) == 'yes'){
			if($age < 5 && $age >= 1 && $Status == 'Age' && strtolower($Gender) == 'male'){
			    $Mwaka_Mmoja_Hadi_Chini_Ya_Miaka_Mitano_Me = $Mwaka_Mmoja_Hadi_Chini_Ya_Miaka_Mitano_Me + 1;
			    $Mwaka_Mmoja_Hadi_Chini_Ya_Miaka_Mitano_Me_Jumla = $Mwaka_Mmoja_Hadi_Chini_Ya_Miaka_Mitano_Me_Jumla + 1;
			    $Mwaka_Mmoja_Hadi_Chini_Ya_Miaka_Mitano_Jumla = $Mwaka_Mmoja_Hadi_Chini_Ya_Miaka_Mitano_Jumla + 1;
			}elseif($age > 5 && $Status == 'Age' && strtolower($Gender) == 'female'){
			    $Mwaka_Mmoja_Hadi_Chini_Ya_Miaka_Mitano_Ke = $Mwaka_Mmoja_Hadi_Chini_Ya_Miaka_Mitano_Ke + 1;
			    $Mwaka_Mmoja_Hadi_Chini_Ya_Miaka_Mitano_Ke_Jumla = $Mwaka_Mmoja_Hadi_Chini_Ya_Miaka_Mitano_Ke_Jumla + 1;
			    $Mwaka_Mmoja_Hadi_Chini_Ya_Miaka_Mitano_Jumla = $Mwaka_Mmoja_Hadi_Chini_Ya_Miaka_Mitano_Jumla + 1;
			}
		    }elseif(strtolower($Age_Between_1_Year_But_Below_5_Year) == 'no'){
			$Mwaka_Mmoja_Hadi_Chini_Ya_Miaka_Mitano_Me = 'NULL';
			$Mwaka_Mmoja_Hadi_Chini_Ya_Miaka_Mitano_Ke = 'NULL';
			$Mwaka_Mmoja_Hadi_Chini_Ya_Miaka_Mitano_Me_Jumla = 'NULL';
			$Mwaka_Mmoja_Hadi_Chini_Ya_Miaka_Mitano_Ke_Jumla = 'NULL';
			$Mwaka_Mmoja_Hadi_Chini_Ya_Miaka_Mitano_Jumla = 'NULL';
		    }
		    
		    
		    if(strtolower($Age_Between_1_Month_But_Below_1_Year) == 'yes'){
			if(($age >= 1 && $Status == 'Month') && strtolower($Gender) == 'male'){
			    $Mwezi_Mmoja_Hadi_Chini_Ya_Mwaka_Mmoja_Me = $Mwezi_Mmoja_Hadi_Chini_Ya_Mwaka_Mmoja_Me + 1;
			    $Mwezi_Mmoja_Hadi_Chini_Ya_Mwaka_Mmoja_Me_Jumla = $Mwezi_Mmoja_Hadi_Chini_Ya_Mwaka_Mmoja_Me_Jumla + 1;
			    $Mwezi_Mmoja_Hadi_Chini_Ya_Mwaka_Mmoja_Jumla = $Mwezi_Mmoja_Hadi_Chini_Ya_Mwaka_Mmoja_Jumla + 1;
			}elseif(($age >= 1 && $Status == 'Month') && strtolower($Gender) == 'female'){
			    $Mwezi_Mmoja_Hadi_Chini_Ya_Mwaka_Mmoja_Ke = $Mwezi_Mmoja_Hadi_Chini_Ya_Mwaka_Mmoja_Ke + 1;
			    $Mwezi_Mmoja_Hadi_Chini_Ya_Mwaka_Mmoja_Ke_Jumla = $Mwezi_Mmoja_Hadi_Chini_Ya_Mwaka_Mmoja_Ke_Jumla + 1;
			    $Mwezi_Mmoja_Hadi_Chini_Ya_Mwaka_Mmoja_Jumla = $Mwezi_Mmoja_Hadi_Chini_Ya_Mwaka_Mmoja_Jumla + 1;
			}
		    }elseif(strtolower($Age_Between_1_Month_But_Below_1_Year) == 'no'){
			$Mwezi_Mmoja_Hadi_Chini_Ya_Mwaka_Mmoja_Me = 'NULL';
			$Mwezi_Mmoja_Hadi_Chini_Ya_Mwaka_Mmoja_Ke = 'NULL';
			$Mwezi_Mmoja_Hadi_Chini_Ya_Mwaka_Mmoja_Me_Jumla = 'NULL';
			$Mwezi_Mmoja_Hadi_Chini_Ya_Mwaka_Mmoja_Ke_Jumla = 'NULL';
			$Mwezi_Mmoja_Hadi_Chini_Ya_Mwaka_Mmoja_Jumla = 'NULL';
		    }
		    
		    if(strtolower($Age_Below_1_Month) == 'yes'){
			if(($age < 1 && $Status == 'Month') && strtolower($Gender) == 'male'){
			    $Chini_Ya_Mwezi_Mmoja_Me = $Chini_Ya_Mwezi_Mmoja_Me + 1;
			    $Chini_Ya_Mwezi_Mmoja_Me_Jumla = $Chini_Ya_Mwezi_Mmoja_Me_Jumla + 1;
			    $Chini_Ya_Mwezi_Mmoja_Jumla = $Chini_Ya_Mwezi_Mmoja_Jumla + 1;
			}elseif($age > 5 && $Status == 'Age' && strtolower($Gender) == 'female'){
			    $Chini_Ya_Mwezi_Mmoja_Ke = $Chini_Ya_Mwezi_Mmoja_Ke + 1;
			    $Chini_Ya_Mwezi_Mmoja_Ke_Jumla = $Chini_Ya_Mwezi_Mmoja_Ke_Jumla + 1;
			    $Chini_Ya_Mwezi_Mmoja_Jumla = $Chini_Ya_Mwezi_Mmoja_Jumla + 1;
			}
		    }elseif(strtolower($Age_Below_1_Month) == 'no'){
			$Chini_Ya_Mwezi_Mmoja_Me = 'NULL';
			$Chini_Ya_Mwezi_Mmoja_Ke = 'NULL';
			$Chini_Ya_Mwezi_Mmoja_Me_Jumla = 'NULL';
			$Chini_Ya_Mwezi_Mmoja_Ke_Jumla = 'NULL';
			$Chini_Ya_Mwezi_Mmoja_Jumla = 'NULL';
		    } 		    
		    
		}elseif(strtolower($Gender_Type) == 'male only'){
		    if(strtolower($Age_Above_5_Years) == 'yes'){
			if($age > 5 && $Status == 'Age' && strtolower($Gender) == 'male'){
			    $Miaka_Mitano_Au_Zaidi_Ke = 'NULL';
			    $Miaka_Mitano_Au_Zaidi_Ke_Jumla = 'NULL';
			    $Miaka_Mitano_Au_Zaidi_Me = $Miaka_Mitano_Au_Zaidi_Me + 1;
			    $Miaka_Mitano_Au_Zaidi_Me_Jumla = $Miaka_Mitano_Au_Zaidi_Me_Jumla + 1;
			} 
		    }elseif(strtolower($Age_Above_5_Years) == 'no'){
			$Miaka_Mitano_Au_Zaidi_Ke = 'NULL';
			$Miaka_Mitano_Au_Zaidi_Ke_Jumla = 'NULL';
			$Miaka_Mitano_Au_Zaidi_Me = 'NULL';
			$Miaka_Mitano_Au_Zaidi_Me_Jumla = 'NULL';
		    }
		    
		    if(strtolower($Age_Between_1_Year_But_Below_5_Year) == 'yes'){
			if(($age > 1 && $age < 5) && $Status == 'Age' && strtolower($Gender) == 'male'){
			    $Mwaka_Mmoja_Hadi_Chini_Ya_Miaka_Mitano_Ke = 'NULL';
			    $Mwaka_Mmoja_Hadi_Chini_Ya_Miaka_Mitano_Ke_Jumla = 'NULL';
			    $Mwaka_Mmoja_Hadi_Chini_Ya_Miaka_Mitano_Me = $Mwaka_Mmoja_Hadi_Chini_Ya_Miaka_Mitano_Me + 1;
			    $Mwaka_Mmoja_Hadi_Chini_Ya_Miaka_Mitano_Me_Jumla = $Mwaka_Mmoja_Hadi_Chini_Ya_Miaka_Mitano_Me_Jumla + 1;
			} 
		    }elseif(strtolower($Age_Between_1_Year_But_Below_5_Year) == 'no'){
			$Mwaka_Mmoja_Hadi_Chini_Ya_Miaka_Mitano_Ke_Jumla = 'NULL';
			$Mwaka_Mmoja_Hadi_Chini_Ya_Miaka_Mitano_Ke = 'NULL';
			$Mwaka_Mmoja_Hadi_Chini_Ya_Miaka_Mitano_Me = 'NULL';
			$Mwaka_Mmoja_Hadi_Chini_Ya_Miaka_Mitano_Me_Jumla = 'NULL';
		    }
		    
		    if(strtolower($Age_Between_1_Month_But_Below_1_Year) == 'yes'){
			if($age >= 1 && $Status == 'Month' && strtolower($Gender) == 'male'){
			    $Mwezi_Mmoja_Hadi_Chini_Ya_Mwaka_Mmoja_Ke = 'NULL';
			    $Mwezi_Mmoja_Hadi_Chini_Ya_Mwaka_Mmoja_Ke_Jumla = 'NULL';
			    $Mwezi_Mmoja_Hadi_Chini_Ya_Mwaka_Mmoja_Me = $Mwezi_Mmoja_Hadi_Chini_Ya_Mwaka_Mmoja_Me + 1;
			    $Mwezi_Mmoja_Hadi_Chini_Ya_Mwaka_Mmoja_Me_Jumla = $Mwezi_Mmoja_Hadi_Chini_Ya_Mwaka_Mmoja_Me_Jumla + 1;
			} 
		    }elseif(strtolower($Age_Between_1_Month_But_Below_1_Year) == 'no'){
			$Mwezi_Mmoja_Hadi_Chini_Ya_Mwaka_Mmoja_Ke = 'NULL';
			$Mwezi_Mmoja_Hadi_Chini_Ya_Mwaka_Mmoja_Ke_Jumla = 'NULL';
			$Mwezi_Mmoja_Hadi_Chini_Ya_Mwaka_Mmoja_Me = 'NULL';
			$Mwezi_Mmoja_Hadi_Chini_Ya_Mwaka_Mmoja_Me_Jumla = 'NULL';
		    }
		     
		    if(strtolower($Age_Below_1_Month) == 'yes'){
			if($age < 1 && $Status == 'Days' && strtolower($Gender) == 'male'){
			    $Chini_Ya_Mwezi_Mmoja_Ke = 'NULL';
			    $Chini_Ya_Mwezi_Mmoja_Ke_Jumla = 'NULL';
			    $Chini_Ya_Mwezi_Mmoja_Me = $Chini_Ya_Mwezi_Mmoja_Me + 1;
			    $Chini_Ya_Mwezi_Mmoja_Me_Jumla = $Chini_Ya_Mwezi_Mmoja_Me_Jumla + 1;
			}
		    }elseif(strtolower($Age_Below_1_Month) == 'no'){
			$Chini_Ya_Mwezi_Mmoja_Ke = 'NULL';
			$Chini_Ya_Mwezi_Mmoja_Ke_Jumla = 'NULL';
			$Chini_Ya_Mwezi_Mmoja_Me = 'NULL';
			$Chini_Ya_Mwezi_Mmoja_Me_Jumla = 'NULL';
		    }
		    
		}elseif(strtolower($Gender_Type) == 'female only'){
		    
		    
		    if(strtolower($Age_Above_5_Years) == 'yes'){
			if($age > 5 && $Status == 'Age' && strtolower($Gender) == 'female'){
			    $Miaka_Mitano_Au_Zaidi_Me = 'NULL';
			    $Miaka_Mitano_Au_Zaidi_Ke = $Miaka_Mitano_Au_Zaidi_Ke + 1;
			    $Miaka_Mitano_Au_Zaidi_Ke_Jumla = $Miaka_Mitano_Au_Zaidi_Ke_Jumla + 1;
			} 
		    }elseif(strtolower($Age_Above_5_Years) == 'no'){
			$Miaka_Mitano_Au_Zaidi_Me = 'NULL'; 
			$Miaka_Mitano_Au_Zaidi_Ke = 'NULL';
			$Miaka_Mitano_Au_Zaidi_Jumla = 'NULL';
		    }
		    
		    if(strtolower($Age_Between_1_Year_But_Below_5_Year) == 'yes'){
			if(($age > 1 && $age < 5) && $Status == 'Age' && strtolower($Gender) == 'female'){
			    $Mwaka_Mmoja_Hadi_Chini_Ya_Miaka_Mitano_Me = 'NULL'; 
			    $Mwaka_Mmoja_Hadi_Chini_Ya_Miaka_Mitano_Ke = $Mwaka_Mmoja_Hadi_Chini_Ya_Miaka_Mitano_Ke + 1;
			    $Mwaka_Mmoja_Hadi_Chini_Ya_Miaka_Mitano_Ke_Jumla = $Mwaka_Mmoja_Hadi_Chini_Ya_Miaka_Mitano_Ke_Jumla + 1;
			} 
		    }elseif(strtolower($Age_Between_1_Year_But_Below_5_Year) == 'no'){ 
			$Mwaka_Mmoja_Hadi_Chini_Ya_Miaka_Mitano_Me = 'NULL';
			$Mwaka_Mmoja_Hadi_Chini_Ya_Miaka_Mitano_Ke = 'NULL';
			$Mwaka_Mmoja_Hadi_Chini_Ya_Miaka_Mitano_Jumla = 'NULL';
		    }
		    
		    if(strtolower($Age_Between_1_Month_But_Below_1_Year) == 'yes'){
			if($age >= 1 && $Status == 'Months' && strtolower($Gender) == 'female'){
			    $Mwezi_Mmoja_Hadi_Chini_Ya_Mwaka_Mmoja_Me = 'NULL'; 
			    $Mwezi_Mmoja_Hadi_Chini_Ya_Mwaka_Mmoja_Ke = $Mwezi_Mmoja_Hadi_Chini_Ya_Mwaka_Mmoja_Ke + 1;
			    $Mwezi_Mmoja_Hadi_Chini_Ya_Mwaka_Mmoja_Ke_Jumla = $Mwezi_Mmoja_Hadi_Chini_Ya_Mwaka_Mmoja_Ke_Jumla + 1;
			} 
		    }elseif(strtolower($Age_Between_1_Month_But_Below_1_Year) == 'no'){
			$Mwezi_Mmoja_Hadi_Chini_Ya_Mwaka_Mmoja_Me = 'NULL'; 
			$Mwezi_Mmoja_Hadi_Chini_Ya_Mwaka_Mmoja_Ke = 'NULL'; 
			$Mwezi_Mmoja_Hadi_Chini_Ya_Mwaka_Mmoja_Jumla = 'NULL';
		    }
		     
		    if(strtolower($Age_Below_1_Month) == 'yes'){
			if($age < 1 && $Status == 'Days' && strtolower($Gender) == 'female'){
			    $Chini_Ya_Mwezi_Mmoja_Me = 'NULL'; 
			    $Chini_Ya_Mwezi_Mmoja_Ke = $Chini_Ya_Mwezi_Mmoja_Ke + 1;
			    $Chini_Ya_Mwezi_Mmoja_Ke_Jumla = $Chini_Ya_Mwezi_Mmoja_Ke_Jumla + 1;
			}
		    }elseif(strtolower($Age_Below_1_Month) == 'no'){
			$Chini_Ya_Mwezi_Mmoja_Me = 'NULL'; 
			$Chini_Ya_Mwezi_Mmoja_Ke = 'NULL'; 
			$Chini_Ya_Mwezi_Mmoja_Jumla = 'NULL';
		    }
		    
		}
	    }
	}else{
	    //if there is no any patient related to selected disease    
		if(strtolower($Gender_Type) == 'both'){
		    
		    if(strtolower($Age_Above_5_Years) == 'yes'){
			$Miaka_Mitano_Au_Zaidi_Me = 0;
			$Miaka_Mitano_Au_Zaidi_Ke = 0;
			$Miaka_Mitano_Au_Zaidi_Me_Jumla = 0;
			$Miaka_Mitano_Au_Zaidi_Ke_Jumla = 0;
			$Miaka_Mitano_Au_Zaidi_Jumla = 0;
		    }else{
			$Miaka_Mitano_Au_Zaidi_Me = 'NULL';
			$Miaka_Mitano_Au_Zaidi_Ke = 'NULL';
			$Miaka_Mitano_Au_Zaidi_Me_Jumla = 'NULL';
			$Miaka_Mitano_Au_Zaidi_Ke_Jumla = 'NULL';
			$Miaka_Mitano_Au_Zaidi_Jumla = 'NULL';
		    }
		    
		    if(strtolower($Age_Between_1_Year_But_Below_5_Year) == 'yes'){
			$Mwaka_Mmoja_Hadi_Chini_Ya_Miaka_Mitano_Me = 0;
			$Mwaka_Mmoja_Hadi_Chini_Ya_Miaka_Mitano_Ke = 0;
			$Mwaka_Mmoja_Hadi_Chini_Ya_Miaka_Mitano_Me_Jumla = 0;
			$Mwaka_Mmoja_Hadi_Chini_Ya_Miaka_Mitano_Ke_Jumla = 0;
			$Mwaka_Mmoja_Hadi_Chini_Ya_Miaka_Mitano_Jumla = 0;
		    }else{
			$Mwaka_Mmoja_Hadi_Chini_Ya_Miaka_Mitano_Me = 'NULL';
			$Mwaka_Mmoja_Hadi_Chini_Ya_Miaka_Mitano_Ke = 'NULL';
			$Mwaka_Mmoja_Hadi_Chini_Ya_Miaka_Mitano_Me_Jumla = 'NULL';
			$Mwaka_Mmoja_Hadi_Chini_Ya_Miaka_Mitano_Ke_Jumla = 'NULL';
			$Mwaka_Mmoja_Hadi_Chini_Ya_Miaka_Mitano_Jumla = 'NULL';
		    }
		    
		    if(strtolower($Age_Between_1_Month_But_Below_1_Year) == 'yes'){
			$Mwezi_Mmoja_Hadi_Chini_Ya_Mwaka_Mmoja_Me = 0;
			$Mwezi_Mmoja_Hadi_Chini_Ya_Mwaka_Mmoja_Ke = 0;
			$Mwezi_Mmoja_Hadi_Chini_Ya_Mwaka_Mmoja_Me_Jumla = 0;
			$Mwezi_Mmoja_Hadi_Chini_Ya_Mwaka_Mmoja_Ke_Jumla = 0;
			$Mwezi_Mmoja_Hadi_Chini_Ya_Mwaka_Mmoja_Jumla = 0;
		    }else{
			$Mwezi_Mmoja_Hadi_Chini_Ya_Mwaka_Mmoja_Me = 'NULL';
			$Mwezi_Mmoja_Hadi_Chini_Ya_Mwaka_Mmoja_Ke = 'NULL';
			$Mwezi_Mmoja_Hadi_Chini_Ya_Mwaka_Mmoja_Me_Jumla = 'NULL';
			$Mwezi_Mmoja_Hadi_Chini_Ya_Mwaka_Mmoja_Ke_Jumla = 'NULL';
			$Mwezi_Mmoja_Hadi_Chini_Ya_Mwaka_Mmoja_Jumla = 'NULL';
		    }
		    
		    if(strtolower($Age_Below_1_Month) == 'yes'){
			$Chini_Ya_Mwezi_Mmoja_Me = 0;
			$Chini_Ya_Mwezi_Mmoja_Ke = 0;
			$Chini_Ya_Mwezi_Mmoja_Me_Jumla = 0;
			$Chini_Ya_Mwezi_Mmoja_Ke_Jumla = 0;
			$Chini_Ya_Mwezi_Mmoja_Jumla = 0;
		    }else{
			$Chini_Ya_Mwezi_Mmoja_Me = 'NULL';
			$Chini_Ya_Mwezi_Mmoja_Ke = 'NULL';
			$Chini_Ya_Mwezi_Mmoja_Me_Jumla = 'NULL';
			$Chini_Ya_Mwezi_Mmoja_Ke_Jumla = 'NULL';
			$Chini_Ya_Mwezi_Mmoja_Jumla = 'NULL';
		    }
		    
		}elseif(strtolower($Gender_Type) == 'male only'){
		    if(strtolower($Age_Above_5_Years) == 'yes'){
			$Miaka_Mitano_Au_Zaidi_Ke = 'NULL';
			$Miaka_Mitano_Au_Zaidi_Me = 0;
			$Miaka_Mitano_Au_Zaidi_Me_Jumla = 0;
		    }else{
			$Miaka_Mitano_Au_Zaidi_Ke = 'NULL';
			$Miaka_Mitano_Au_Zaidi_Me = 'NULL';
			$Miaka_Mitano_Au_Zaidi_Me_Jumla = 'NULL';
		    }
		    
		    if(strtolower($Age_Between_1_Year_But_Below_5_Year) == 'yes'){
			$Mwaka_Mmoja_Hadi_Chini_Ya_Miaka_Mitano_Ke = 'NULL';
			$Mwaka_Mmoja_Hadi_Chini_Ya_Miaka_Mitano_Me = 0;
			$Mwaka_Mmoja_Hadi_Chini_Ya_Miaka_Mitano_Me_Jumla = 0;
		    }else{
			$Mwaka_Mmoja_Hadi_Chini_Ya_Miaka_Mitano_Ke = 'NULL';
			$Mwaka_Mmoja_Hadi_Chini_Ya_Miaka_Mitano_Me = 'NULL';
			$Mwaka_Mmoja_Hadi_Chini_Ya_Miaka_Mitano_Me_Jumla = 'NULL';
		    }
		    
		    if(strtolower($Age_Between_1_Month_But_Below_1_Year) == 'yes'){
			$Mwezi_Mmoja_Hadi_Chini_Ya_Mwaka_Mmoja_Ke = 'NULL';
			$Mwezi_Mmoja_Hadi_Chini_Ya_Mwaka_Mmoja_Me = 0;
			$Mwezi_Mmoja_Hadi_Chini_Ya_Mwaka_Mmoja_Me_Jumla = 0;
		    }else{
			$Mwezi_Mmoja_Hadi_Chini_Ya_Mwaka_Mmoja_Ke = 'NULL';
			$Mwezi_Mmoja_Hadi_Chini_Ya_Mwaka_Mmoja_Me = 'NULL';
			$Mwezi_Mmoja_Hadi_Chini_Ya_Mwaka_Mmoja_Me_Jumla = 'NULL';
		    }
		    
		    if(strtolower($Age_Below_1_Month) == 'yes'){
			$Chini_Ya_Mwezi_Mmoja_Ke = 'NULL';
			$Chini_Ya_Mwezi_Mmoja_Me = 0;
			$Chini_Ya_Mwezi_Mmoja_Me_Jumla = 0;
		    }else{
			$Chini_Ya_Mwezi_Mmoja_Ke = 'NULL';
			$Chini_Ya_Mwezi_Mmoja_Me = 'NULL';
			$Chini_Ya_Mwezi_Mmoja_Me_Jumla = 'NULL';
		    }
		    
		}elseif(strtolower($Gender_Type) == 'female only'){
		    if(strtolower($Age_Above_5_Years) == 'yes'){
			$Miaka_Mitano_Au_Zaidi_Me = 'NULL';
			$Miaka_Mitano_Au_Zaidi_Ke = 0;
			$Miaka_Mitano_Au_Zaidi_Ke_Jumla = 0;
		    }else{
			$Miaka_Mitano_Au_Zaidi_Me = 'NULL';
			$Miaka_Mitano_Au_Zaidi_Ke = 'NULL';
			$Miaka_Mitano_Au_Zaidi_Ke_Jumla = 'NULL';
		    }
		    
		    if(strtolower($Age_Between_1_Year_But_Below_5_Year) == 'yes'){
			$Mwaka_Mmoja_Hadi_Chini_Ya_Miaka_Mitano_Me = 'NULL';
			$Mwaka_Mmoja_Hadi_Chini_Ya_Miaka_Mitano_Ke = 0;
			$Mwaka_Mmoja_Hadi_Chini_Ya_Miaka_Mitano_Ke_Jumla = 0;
		    }else{
			$Mwaka_Mmoja_Hadi_Chini_Ya_Miaka_Mitano_Me = 'NULL';
			$Mwaka_Mmoja_Hadi_Chini_Ya_Miaka_Mitano_Ke = 'NULL';
			$Mwaka_Mmoja_Hadi_Chini_Ya_Miaka_Mitano_Ke_Jumla = 'NULL';
		    }
		    
		    if(strtolower($Age_Between_1_Month_But_Below_1_Year) == 'yes'){
			$Mwezi_Mmoja_Hadi_Chini_Ya_Mwaka_Mmoja_Me = 'NULL';
			$Mwezi_Mmoja_Hadi_Chini_Ya_Mwaka_Mmoja_Ke = 0;
			$Mwezi_Mmoja_Hadi_Chini_Ya_Mwaka_Mmoja_Ke_Jumla = 0;
		    }else{
			$Mwezi_Mmoja_Hadi_Chini_Ya_Mwaka_Mmoja_Me = 'NULL';
			$Mwezi_Mmoja_Hadi_Chini_Ya_Mwaka_Mmoja_Ke = 'NULL';
			$Mwezi_Mmoja_Hadi_Chini_Ya_Mwaka_Mmoja_Ke_Jumla = 'NULL';
		    }
		    
		    
		    if(strtolower($Age_Below_1_Month) == 'yes'){
			$Chini_Ya_Mwezi_Mmoja_Me = 'NULL';
			$Chini_Ya_Mwezi_Mmoja_Ke = 0;
			$Chini_Ya_Mwezi_Mmoja_Ke_Jumla = 0;
		    }else{
			$Chini_Ya_Mwezi_Mmoja_Me = 'NULL';
			$Chini_Ya_Mwezi_Mmoja_Ke = 'NULL';
			$Chini_Ya_Mwezi_Mmoja_Ke_Jumla = 'NULL';
		    }
		    
		}
	}
	
	$htm .= "INSERT INTO tbl_opd (facility_Code ,period,disease_Form_Id,under_1_Month_Male,under_1_month_Female,1_month_Under_1_yearMale,1_month_Under_1_year_Female,1_year_Under_5_male,1_year_Under_5_Female ,5_Orabove_Male,5_Or_above_female) VALUES( '".$facilityCode."','".$period."',$dhis_Form_Id,".$Chini_Ya_Mwezi_Mmoja_Me.",".$Chini_Ya_Mwezi_Mmoja_Ke.",".$Mwezi_Mmoja_Hadi_Chini_Ya_Mwaka_Mmoja_Me.",".$Mwezi_Mmoja_Hadi_Chini_Ya_Mwaka_Mmoja_Ke.",".$Mwaka_Mmoja_Hadi_Chini_Ya_Miaka_Mitano_Me.",".$Mwaka_Mmoja_Hadi_Chini_Ya_Miaka_Mitano_Ke.",".$Miaka_Mitano_Au_Zaidi_Me.",".$Miaka_Mitano_Au_Zaidi_Ke."); ";
	$temp++;
	
	//reset all variables
	$Chini_Ya_Mwezi_Mmoja_Me = 0;
	$Chini_Ya_Mwezi_Mmoja_Ke = 0;
	$Chini_Ya_Mwezi_Mmoja_Jumla = 0;
	
	$Mwezi_Mmoja_Hadi_Chini_Ya_Mwaka_Mmoja_Me = 0;
	$Mwezi_Mmoja_Hadi_Chini_Ya_Mwaka_Mmoja_Ke = 0;
	$Mwezi_Mmoja_Hadi_Chini_Ya_Mwaka_Mmoja_Jumla = 0;
	
	$Mwaka_Mmoja_Hadi_Chini_Ya_Miaka_Mitano_Me = 0;
	$Mwaka_Mmoja_Hadi_Chini_Ya_Miaka_Mitano_Ke = 0;
	$Mwaka_Mmoja_Hadi_Chini_Ya_Miaka_Mitano_Jumla = 0;
	
	$Miaka_Mitano_Au_Zaidi_Me = 0;
	$Miaka_Mitano_Au_Zaidi_Ke = 0;
	$Miaka_Mitano_Au_Zaidi_Jumla = 0;
	$Jumla = 0;
    }

    //hardcoded because There Is No Data For (Waliopewa Rufaa)
    $htm .= "INSERT INTO tbl_opd (facility_Code ,period,disease_Form_Id,under_1_Month_Male,under_1_month_Female,1_month_Under_1_yearMale,1_month_Under_1_year_Female,1_year_Under_5_male,1_year_Under_5_Female ,5_Orabove_Male,5_Or_above_female) VALUES( '".$facilityCode."','".$period."',92,0,0,0,0,0,0,0,0); ";  
?>
<?php
   	function addValues($a,$b,$return){
   		if(($a!=="NULL")&& ($b!=="NULL")){
   			return ($a+$b);
   		}else if($a ==='NULL'&& $b !== 'NULL') {
   			return $b;
   		}else if($a !== 'NULL'&& $b === 'NULL') {
   			return $a;
   		}else if($a === 'NULL'&& $b === 'NULL') {
   			return $return;
   		}
   	}

	$con = new mysqli("www.gpitg.com","gpitg_adek","adek2014#","gpitg_ehms_cloud");
	if(mysqli_connect_errno()){
		die(mysqli_connect_error());
	}
	if(!$con->multi_query($htm)){
		die("Failed to execute Batch");
	}else{
		?><H1>DATA HAS BEEN SENT TO EHMS DHIS2 CLOUD SERVICE</H1><?php
	}
?>