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

    
    
    //Calculate all total
    $htm = '<script src="jquery.js"></script>
	    <script src="script.js"></script>
	    <script src="script.responsive.js"></script>


	    <style>.art-content .art-postcontent-0 .layout-item-0 { margin-bottom: 10px;  }
	    .art-content .art-postcontent-0 .layout-item-1 { padding-right: 10px;padding-left: 10px;  }
	    .ie7 .art-post .art-layout-cell {border:none !important; padding:0 !important; }
	    .ie6 .art-post .art-layout-cell {border:none !important; padding:0 !important; }
	    
	    </style>';
    
    
    $htm .= '<table width=100%>
		<tr>
		    <td style="text-align: center;">
			<b>Taarifa ya Wagonjwa wa Nje (OPD)</b>
			<br>
			<span style="font-size: x-small;"><b>Kutoka '.$_GET['from_date'].' Mpaka '.$_GET['to_date'].'</b></span>
		    </td>
		</tr>
	    </table><br/>';
	    
    $htm .= '<table width=100%>
		<tr><td colspan=7><hr></td></tr>
		<tr>
		    <td style="text-align: center;" width=4%>
			<span style="font-size: x-small;"></span>
		    </td>
		    <td style="text-align: left;">
			<span style="font-size: x-small; vertical-align: middle;"></span>
		    </td>
		    <td style="text-align: center;" width=15%>
			<table width=100%>
			    <tr>
				<td colspan=3 style="text-align: center;"><span style="font-size: x-small;"><b>Umri chini ya mwezi 1</b></span></td>
			    </tr> 
			</table>
		    </td>
		    <td style="text-align: center;" width=15%> 
			<table width=100%>
			    <tr>
				<td colspan=3 style="text-align: center;"><span style="font-size: x-small;"><b>Umri mwezi 1 hadi chini ya mwaka 1</b></span></td>
			    </tr> 
			</table>
		    </td>
		    <td style="text-align: center;" width=15%>
			<table width=100%>
			    <tr>
				<td style="text-align: center;" colspan=3><span style="font-size: x-small;"><b>Umri mwaka 1 hadi chini ya miaka 5</b></span></td>
			    </tr> 
			</table>
		    </td>
		    <td style="text-align: center;" width=15%>
			<table width=100%>
			    <tr>
				<td style="text-align: center;" colspan=3><span style="font-size: x-small;"><b>Umri miaka 5 au zaidi</b></span></td>
			    </tr> 
			</table>
		    </td>
		    <td width=5%>
			<span style="font-size: x-small;"><b>Jumla</b></span>
		    </td></tr>';
    //get diseases
    
    $htm .= "<tr>
		<td style='text-align: center;'><span style='font-size: xx-small;'><b>Na</b></span></td>
		<td><span style='font-size: xx-small;'><b>Maelezo</b></span></td>
		<td>
		    <table width=100%>
			<tr>
			    <td width=5%><span style='font-size: xx-small;'><b>ME</b></span></td>
			    <td width=5%><span style='font-size: xx-small;'><b>KE</b></span></td>
			    <td width=5%><span style='font-size: xx-small;'><b>JUMLA</b></span></td>
			</tr>
		    </table>
		</td>
		<td>
		    <table width=100%>
			<tr>
			    <td width=5%><span style='font-size: xx-small;'><b>ME</b></span></td>
			    <td width=5%><span style='font-size: xx-small;'><b>KE</b></span></td>
			    <td width=5%><span style='font-size: xx-small;'><b>JUMLA</b></span></td>
			</tr>
		    </table>
		</td>
		<td>
		    <table width=100%>
			<tr>
			    <td width=5%><span style='font-size: xx-small;'><b>ME</b></span></td>
			    <td width=5%><span style='font-size: xx-small;'><b>KE</b></span></td>
			    <td width=5%><span style='font-size: xx-small;'><b>JUMLA</b></span></td>
			</tr>
		    </table>
		</td>
		<td>
		    <table width=100%>
			<tr>
			    <td width=5%><span style='font-size: xx-small;'><b>ME</b></span></td>
			    <td width=5%><span style='font-size: xx-small;'><b>KE</b></span></td>
			    <td width=5%><span style='font-size: xx-small;'><b>JUMLA</b></span></td>
			</tr>
		    </table>
		</td>
		<td width=5% style='text-align: center;'><span style='font-size: x-small;'></span></td> 
		</tr>";
		
    $htm .= "<tr><td colspan=7><hr></td></tr>";
	    
	
	
	
	/*
	
	
    $htm .= "<tr>
		<td style='text-align: center;'><span style='font-size: xx-small;'>1.</span></td>
		<td><span style='font-size: xx-small;'><b>Jumla ya Mahudhurio ya OPD</b></span></td>
		<td>
		    <table width=100%>
			<tr>
			    <td width=5%><span style='font-size: xx-small;'>".$Chini_Ya_Mwezi_Mmoja_Me_Jumla."</span></td>
			    <td width=5%><span style='font-size: xx-small;'>".$Chini_Ya_Mwezi_Mmoja_Ke_Jumla."</span></td>
			    <td width=5%><span style='font-size: xx-small;'>".($Chini_Ya_Mwezi_Mmoja_Me_Jumla + $Chini_Ya_Mwezi_Mmoja_Ke_Jumla)."</span></td>
			</tr>
		    </table>
		</td>
		<td>
		    <table width=100%>
			<tr>
			    <td width=5%><span style='font-size: xx-small;'>".$Mwezi_Mmoja_Hadi_Chini_Ya_Mwaka_Mmoja_Me_Jumla."</span></td>
			    <td width=5%><span style='font-size: xx-small;'>".$Mwezi_Mmoja_Hadi_Chini_Ya_Mwaka_Mmoja_Ke_Jumla."</span></td>
			    <td width=5%><span style='font-size: xx-small;'>".($Mwezi_Mmoja_Hadi_Chini_Ya_Mwaka_Mmoja_Me_Jumla + $Mwezi_Mmoja_Hadi_Chini_Ya_Mwaka_Mmoja_Ke_Jumla)."</span></td>
			</tr>
		    </table>
		</td>
		<td>
		    <table width=100%>
			<tr>
			    <td width=5%><span style='font-size: xx-small;'>".$Mwaka_Mmoja_Hadi_Chini_Ya_Miaka_Mitano_Me_Jumla."</span></td>
			    <td width=5%><span style='font-size: xx-small;'>".$Mwaka_Mmoja_Hadi_Chini_Ya_Miaka_Mitano_Ke_Jumla."</span></td>
			    <td width=5%><span style='font-size: xx-small;'>".($Mwaka_Mmoja_Hadi_Chini_Ya_Miaka_Mitano_Me_Jumla + $Mwaka_Mmoja_Hadi_Chini_Ya_Miaka_Mitano_Ke_Jumla)."</span></td>
			</tr>
		    </table>
		</td>
		<td>
		    <table width=100%>
			<tr>
			    <td width=5%><span style='font-size: xx-small;'>".$Miaka_Mitano_Au_Zaidi_Me_Jumla."</span></td>
			    <td width=5%><span style='font-size: xx-small;'>".$Miaka_Mitano_Au_Zaidi_Ke_Jumla."</span></td>
			    <td width=5%><span style='font-size: xx-small;'>".($Miaka_Mitano_Au_Zaidi_Me_Jumla + $Miaka_Mitano_Au_Zaidi_Ke_Jumla)."</span></td>
			</tr>
		    </table>
		</td>
		<td width=5% style='text-align: center;'><span style='font-size: xx-small;'>
			".(($Chini_Ya_Mwezi_Mmoja_Me_Jumla + $Chini_Ya_Mwezi_Mmoja_Ke_Jumla)+
			    ($Mwezi_Mmoja_Hadi_Chini_Ya_Mwaka_Mmoja_Me_Jumla + $Mwezi_Mmoja_Hadi_Chini_Ya_Mwaka_Mmoja_Ke_Jumla)+
				($Mwaka_Mmoja_Hadi_Chini_Ya_Miaka_Mitano_Me_Jumla + $Mwaka_Mmoja_Hadi_Chini_Ya_Miaka_Mitano_Ke_Jumla)+
				    ($Miaka_Mitano_Au_Zaidi_Me_Jumla + $Miaka_Mitano_Au_Zaidi_Ke_Jumla))."</span></td> 
		</tr>";
		
		
		
		
		
    $htm .= "<tr>
		<td style='text-align: center;'><span style='font-size: xx-small;'>2.</span></td>
		<td><span style='font-size: xx-small;'><b>Wagonjwa waliohudhuria kwa mara ya kwanza mwaka huu(*)</b></span></td>
		<td>
		    <table width=100%>
			<tr>
			    <td width=5%><span style='font-size: xx-small;'>0</span></td>
			    <td width=5%><span style='font-size: xx-small;'>0</span></td>
			    <td width=5%><span style='font-size: xx-small;'>0</span></td>
			</tr>
		    </table>
		</td>
		<td>
		    <table width=100%>
			<tr>
			    <td width=5%><span style='font-size: xx-small;'>0</span></td>
			    <td width=5%><span style='font-size: xx-small;'>0</span></td>
			    <td width=5%><span style='font-size: xx-small;'>0</span></td>
			</tr>
		    </table>
		</td>
		<td>
		    <table width=100%>
			<tr>
			    <td width=5%><span style='font-size: xx-small;'>0</span></td>
			    <td width=5%><span style='font-size: xx-small;'>0</span></td>
			    <td width=5%><span style='font-size: xx-small;'>0</span></td>
			</tr>
		    </table>
		</td>
		<td>
		    <table width=100%>
			<tr>
			    <td width=5%><span style='font-size: xx-small;'>0</span></td>
			    <td width=5%><span style='font-size: xx-small;'>0</span></td>
			    <td width=5%><span style='font-size: xx-small;'>0</span></td>
			</tr>
		    </table>
		</td>
		<td width=5% style='text-align: center;'><span style='font-size: xx-small;'>0</span></td> 
		</tr>";
		
		
		
		
		
    $htm .= "<tr>
		<td style='text-align: center;'><span style='font-size: xx-small;'>3.</span></td>
		<td><span style='font-size: xx-small;'><b>Jumla ya Mahudhurio ya Marudio</b></span></td>
		<td>
		    <table width=100%>
			<tr>
			    <td width=5%><span style='font-size: xx-small;'>0</span></td>
			    <td width=5%><span style='font-size: xx-small;'>0</span></td>
			    <td width=5%><span style='font-size: xx-small;'>0</span></td>
			</tr>
		    </table>
		</td>
		<td>
		    <table width=100%>
			<tr>
			    <td width=5%><span style='font-size: xx-small;'>0</span></td>
			    <td width=5%><span style='font-size: xx-small;'>0</span></td>
			    <td width=5%><span style='font-size: xx-small;'>0</span></td>
			</tr>
		    </table>
		</td>
		<td>
		    <table width=100%>
			<tr>
			    <td width=5%><span style='font-size: xx-small;'>0</span></td>
			    <td width=5%><span style='font-size: xx-small;'>0</span></td>
			    <td width=5%><span style='font-size: xx-small;'>0</span></td>
			</tr>
		    </table>
		</td>
		<td>
		    <table width=100%>
			<tr>
			    <td width=5%><span style='font-size: xx-small;'>0</span></td>
			    <td width=5%><span style='font-size: xx-small;'>0</span></td>
			    <td width=5%><span style='font-size: xx-small;'>0</span></td>
			</tr>
		    </table>
		</td>
		<td width=5% style='text-align: center;'><span style='font-size: xx-small;'>0</span></td> 
		</tr>";
		
	
	    
    $htm .= "<tr><td colspan=7><hr></td></tr>"; 
	*/	
    $htm .= "<tr>
		<td style='text-align: center;'></td>
		<td><span style='font-size: x-small;'><b>Diagnoses Za OPD</b></span></td>
		<td>
		    <table width=100%>
			<tr>
			    <td width=5%></td>
			    <td width=5%></td>
			    <td width=5%></td>
			</tr>
		    </table>
		</td>
		<td>
		    <table width=100%>
			<tr>
			    <td width=5%></td>
			    <td width=5%></td>
			    <td width=5%></td>
			</tr>
		    </table>
		</td>
		<td>
		    <table width=100%>
			<tr>
			    <td width=5%></td>
			    <td width=5%></td>
			    <td width=5%></td>
			</tr>
		    </table>
		</td>
		<td>
		    <table width=100%>
			<tr>
			    <td width=5%></td>
			    <td width=5%></td>
			    <td width=5%></td>
			</tr>
		    </table>
		</td>
		<td width=5% style='text-align: center;'><span style='font-size: x-small;'></span></td> 
		</tr>";
		
	
	
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

		
		
    $select_disease = mysqli_query($conn,"select * from tbl_disease where opd_report = 'yes' order by disease_name") or die(mysqli_error($conn)); //selecting list of diseases
    while($disease = mysqli_fetch_array($select_disease)){
	$disease_ID = $disease['disease_ID']; 
	$disease_name = $disease['disease_name'];
	$Age_Below_1_Month = $disease['Age_Below_1_Month'];
	$Age_Between_1_Month_But_Below_1_Year = $disease['Age_Between_1_Month_But_Below_1_Year'];
	$Age_Between_1_Year_But_Below_5_Year = $disease['Age_Between_1_Year_But_Below_5_Year'];
	$Age_Above_5_Years = $disease['Age_Above_5_Years'];
	$Gender_Type = $disease['Gender_Type'];
	
	
	
	//select all patient consulted from the select disease
	$sql = "select pr.Gender, pr.Date_Of_Birth, Disease_Consultation_Date_And_Time from tbl_disease_consultation dc, tbl_disease d, tbl_consultation c, tbl_patient_registration pr where
		    dc.consultation_ID = c.consultation_ID and
			dc.disease_ID = d.disease_ID and
			    pr.Registration_ID = c.Registration_ID and
				d.disease_ID = '$disease_ID'";
	
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
	
	$htm .= "<tr><td style='text-align: center;'><span style='font-size: x-small;'>".$temp.".</span></td>"; //record number
        $htm .= "<td><span style='font-size: x-small;'>".$disease_name."</span></td>";
	
	$htm .='<td><table><tr>
		<td width=5%><span style="font-size: x-small;">'.$Chini_Ya_Mwezi_Mmoja_Me.'</span></td>
		<td width=5%><span style="font-size: x-small;">'.$Chini_Ya_Mwezi_Mmoja_Ke.'</span></td>
		<td width=5%><span style="font-size: x-small;">'.$Chini_Ya_Mwezi_Mmoja_Jumla.'</span></td>
		</tr></table></td>';
	 
	$htm .='<td><table><tr>
		<td width=5%><span style="font-size: x-small;">'.$Mwezi_Mmoja_Hadi_Chini_Ya_Mwaka_Mmoja_Me.'</span></td>
		<td width=5%><span style="font-size: x-small;">'.$Mwezi_Mmoja_Hadi_Chini_Ya_Mwaka_Mmoja_Ke.'</span></td>
		<td width=5%><span style="font-size: x-small;">'.$Mwezi_Mmoja_Hadi_Chini_Ya_Mwaka_Mmoja_Jumla.'</span></td>
		</tr></table></td>';
	 
	$htm .='<td><table><tr>
		<td width=5%><span style="font-size: x-small;">'.$Mwaka_Mmoja_Hadi_Chini_Ya_Miaka_Mitano_Me.'</span></td>
		<td width=5%><span style="font-size: x-small;">'.$Mwaka_Mmoja_Hadi_Chini_Ya_Miaka_Mitano_Ke.'</span></td>
		<td width=5%><span style="font-size: x-small;">'.$Mwaka_Mmoja_Hadi_Chini_Ya_Miaka_Mitano_Jumla.'</span></td>
		</tr></table></td>';
	 
	
	$htm .='<td><table><tr>
		<td width=5%><span style="font-size: x-small;">'.$Miaka_Mitano_Au_Zaidi_Me.'</span></td>
		<td width=5%><span style="font-size: x-small;">'.$Miaka_Mitano_Au_Zaidi_Ke.'</span></td>
		<td width=5%><span style="font-size: x-small;">'.$Miaka_Mitano_Au_Zaidi_Jumla.'</span></td>
		</tr></table></td><td style="text-align: center;"><span style="font-size: x-small;">'.$Jumla.'</td>';
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
    $htm .= "<tr><td colspan=7><hr></td></tr>";
    $htm .= "</table>";
    
    //select printing date and time
    $select_Time_and_date = mysqli_query($conn,"select now() as datetime");
    while($row = mysqli_fetch_array($select_Time_and_date)){
	$Date_Time = $row['datetime'];
    }  
?>
<?php
   
    include("MPDF/mpdf.php");

    $mpdf=new mPDF('','Letter',0,'',12.7,12.7,14,12.7,8,8); 

    $mpdf->WriteHTML($htm);
    $mpdf->Output();
    exit;
?>