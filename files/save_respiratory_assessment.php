<?php
	session_start();
	include("./includes/connection.php");



       if(isset($_POST['Timescale'])){
		$Timescale = $_POST['Timescale'];
	}else{
		$Timescale = 0;
	}
		$Treatment_Plan_Goals = $_POST['Treatment_Plan_Goals'];
	
		$Problem_List = $_POST['Problem_List'];
	
		$type_Productive = $_POST['type_Productive'];
	
		$amount_Productive = $_POST['amount_Productive'];
	
		$color_Productive = $_POST['color_Productive'];
	
		$No_Productive = $_POST['No_Productive'];
	
		$yes_Cough = $_POST['yes_Cough'];
	
		$RR_new= $_POST['RR_new'];
	
		$FIO2_new = $_POST['FIO2_new'];
	
		$SPO2_new = $_POST['SPO2_new'];
	
		$Temp = $_POST['Temp'];
	
		$H_R = $_POST['H_R'];
	
		$B_P = $_POST['B_P'];
	
		$Observation = $_POST['Observation'];
	
		$Subjective_Markers = $_POST['Subjective_Markers'];

		$Via_2 = $_POST['Via_2'];
	
		$O_percent_2 = $_POST['O_percent_2'];
	
		$SaO2_2 = $_POST['SaO2_2'];
	
		$BE_2 = $_POST['BE_2'];
	
		$HCO3_2 = $_POST['HCO3_2'];
	
		$PCO2_2 = $_POST['PCO2_2'];
	
		$PH_2 = $_POST['PH_2'];
	
		$PCO2_2 = $_POST['PCO2_2'];
        
        	$PH_2 = $_POST['PH_2'];
	
        	$Via = $_POST['Via'];
	
        	$O_percent = $_POST['O_percent'];
	
        	$SaO2 = $_POST['SaO2'];
	
        	$BE = $_POST['BE'];
	
        	$HCO3 = $_POST['HCO3'];

        	$PCO2 = $_POST['PCO2'];
	
        	$PH = $_POST['PH'];
	
        	$fromDate = $_POST['fromDate'];
	
        	$invesiigations = $_POST['invesiigations'];
	
        	$chest_x_ray = $_POST['chest_x_ray'];
	
        	$specimen_na= $_POST['specimen_na'];

        	$specimen_yes= $_POST['specimen_yes'];
	
        	$specimen_no= $_POST['specimen_no'];
	
        	$inhalers_home= $_POST['inhalers_home'];
	
        	$medical_clerking= $_POST['medical_clerking'];
	
        	$medical_diagnosis= $_POST['medical_diagnosis'];
	
        	$interests_consent= $_POST['interests_consent'];
	
        	$treatment_consent= $_POST['treatment_consent'];
        	$Registration_ID= $_POST['Registration_ID'];
                
                
                         $mysqli_check_simulation_data=mysqli_query($conn,"SELECT Respiratory_ID FROM tbl_respiratory_assessment_data WHERE Registration_ID='$Registration_ID' AND date(date_time_transaction)=CURDATE()");
   if(mysqli_num_rows($mysqli_check_simulation_data) > 0){
       
               $Respiratory_ID= mysqli_fetch_assoc(mysqli_query($conn,"SELECT Respiratory_ID FROM tbl_respiratory_assessment_data WHERE Registration_ID='$Registration_ID' AND date(date_time_transaction)=CURDATE()"))['Respiratory_ID'];
               
                echo $sql_save_data = mysqli_query($conn,"UPDATE tbl_respiratory_assessment_data SET treatment_consent='$treatment_consent',interests_consent='$interests_consent',medical_diagnosis='$medical_diagnosis',medical_clerking='$medical_clerking',inhalers_home='$inhalers_home',specimen_no='$specimen_no',specimen_yes='$specimen_yes',specimen_na='$specimen_na',chest_x_ray='$chest_x_ray',invesiigations='$invesiigations',fromDate='$fromDate',PH='$PH',PCO2='$PCO2',HCO3='$HCO3',BE='$BE',SaO2='$SaO2',O_percent='$O_percent',Via='$Via',PH_2='$PH_2',PCO2_2='$PCO2_2',HCO3_2='$HCO3_2',BE_2='$BE_2',SaO2_2='$SaO2_2',O_percent_2='$O_percent_2',"
                        . "                 Via_2='$Via_2',Subjective_Markers='$Subjective_Markers',Observation='$Observation',B_P='$B_P',H_R='$H_R',Temp='$Temp',SPO2_new='$SPO2_new',FIO2_new='$FIO2_new',RR_new='$RR_new',yes_Cough='$yes_Cough',No_Productive='$No_Productive',color_Productive='$color_Productive',amount_Productive='$amount_Productive',type_Productive='$type_Productive',Problem_List='$Problem_List',Treatment_Plan_Goals='$Treatment_Plan_Goals',Timescale='$Timescale,date_time_transaction=NOW() WHERE Respiratory_ID='$Respiratory_ID'") or die(mysqli_error($conn));
                
   }else{   
      echo  $mysqli_save = mysqli_query($conn,"INSERT INTO tbl_respiratory_assessment_data(Registration_ID,treatment_consent,interests_consent,medical_diagnosis,medical_clerking,inhalers_home,specimen_no,specimen_yes,specimen_na,chest_x_ray,invesiigations,fromDate,PH,PCO2,HCO3,BE,SaO2,O_percent,Via,PH_2,PCO2_2,HCO3_2,BE_2,SaO2_2,O_percent_2,"
                        . "                 Via_2,Subjective_Markers,Observation,B_P,H_R,Temp,SPO2_new,FIO2_new,RR_new,yes_Cough,No_Productive,color_Productive,amount_Productive,type_Productive,Problem_List,Treatment_Plan_Goals,Timescale,date_time_transaction)VALUES('$Registration_ID','$treatment_consent','$interests_consent','$medical_diagnosis','$medical_clerking','$inhalers_home','$specimen_no','$specimen_yes','$specimen_na','$chest_x_ray','$invesiigations','$fromDate','$PH','$PCO2','$HCO3','$BE','$SaO2','$O_percent','$Via','$PH_2','$PCO2_2','$HCO3_2','$BE_2','$SaO2_2','$O_percent_2',"
                        . "                 '$Via_2','$Subjective_Markers','$Observation','$B_P','$H_R','$Temp','$SPO2_new','$FIO2_new','$RR_new','$yes_Cough','$No_Productive','$color_Productive','$amount_Productive','$type_Productive','$Problem_List','$Treatment_Plan_Goals','$Timescale',NOW())") or die(mysqli_error($conn));
	
   }