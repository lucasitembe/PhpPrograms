<?php
    include("./includes/connection.php");
    
    if(isset($_GET['disease_ID'])){
        $disease_ID = $_GET['disease_ID'];
    }else{ 
        $disease_ID = 0;
    }
    
    if(isset($_GET['Registration_ID'])){
        $Registration_ID = $_GET['Registration_ID'];
    }else{
        $Registration_ID = 0;
    }
    
    //Get Disease
    $selectDisese = "SELECT * FROM tbl_disease_group_mapping dgm,tbl_disease_group dg 
                    WHERE dgm.disease_group_id = dg.disease_group_id
                    AND disease_ID = $disease_ID ";
    $diseseResult = mysqli_query($conn,$selectDisese);
    if(mysqli_num_rows($diseseResult)==0){
        echo "Disease Setup Is Not Well Set !";
        exit;
    }
    while($diseaseRow = mysqli_fetch_assoc($diseseResult)){
        $Age_60_Years_And_Above = $diseaseRow['Age_60_Years_And_Above'];
        $Age_Between_1_Year_But_Below_5_Year = $diseaseRow['Age_Between_1_Year_But_Below_5_Year'];
        $Five_Years_Or_Below_Sixty_Years = $diseaseRow['Five_Years_Or_Below_Sixty_Years'];
        $Age_Between_1_Month_But_Below_1_Year = $diseaseRow['Age_Between_1_Month_But_Below_1_Year'];
        $Age_Below_1_Month = $diseaseRow['Age_Below_1_Month'];
        $Gender_Type = $diseaseRow['Gender_Type'];

        /*$Age_Between_1_Year_But_Below_5_Year = $diseaseRow['Age_Between_1_Year_But_Below_5_Year'];
        $Age_Above_5_Years = $diseaseRow['Age_Above_5_Years'];
        $Age_Between_1_Month_But_Below_1_Year = $diseaseRow['Age_Between_1_Month_But_Below_1_Year'];
        $Age_Below_1_Month = $diseaseRow['Age_Below_1_Month'];*/
    }
    
    //Get Patient Info
    $SelectPatient = "SELECT * FROM tbl_patient_registration WHERE Registration_ID = $Registration_ID";
    
    $patientResult = mysqli_query($conn,$SelectPatient);
    
    while($patientRow = mysqli_fetch_assoc($patientResult)){
        $Date_Of_Birth = $patientRow['Date_Of_Birth'];
        $Gender = $patientRow['Gender'];
    }
    
    $timeqr = "SELECT NOW() as nowtime ";
    $timeresult = mysqli_query($conn,$timeqr);
    $Time = mysqli_fetch_assoc($timeresult)['nowtime'];
    
    $now = new DateTime($Time);
    $dob = new DateTime($Date_Of_Birth);
    $dif = $now->diff($dob);
    
    if(strtolower($Gender_Type)=='both'){
    }elseif(strtolower($Gender_Type)=='female only' && strtolower($Gender)=='female'){
    }elseif(strtolower($Gender_Type)=='male only' && strtolower($Gender)=='male'){
    }else{
        echo "This Disease is Gender Specific( $Gender_Type )";
        exit;
    }
    
    if(strtolower($Age_Below_1_Month)=='no' && ($dif->y <1 && $dif->m <1)){
        echo "This Disease is Age Specific ( Not For Age Below 1 Month !)";
        exit;
    }
    
    if(strtolower($Age_Between_1_Month_But_Below_1_Year)=='no' && ($dif->y < 1 || ($dif->y <1 && $dif->m >=1) ) ){
        echo "This Disease is Age Specific ( Not For Age Between 1 Month to 1 Year Old !)";
        exit;
    }
    
    if((strtolower($Age_Between_1_Year_But_Below_5_Year)=='no') && ($dif->y < 5 && $dif->y >=1) ){
        echo "This Disease is Age Specific ( Not For Age Between 5 and 1 Year(s) Old !)";
        exit;
    }
    
    if(strtolower($Five_Years_Or_Below_Sixty_Years) =='no' && ($dif->y >= 5 && $dif->y < 60)){
        echo "This Disease is Age Specific ( Not For Age Between 5 and 60 years Old!)";
        exit;
    }
    if(strtolower($Age_60_Years_And_Above)=='no' && $dif->y >= 60 ){
        echo "This Disease is Age Specific ( Not For 60 Years Old Or Above!)";
        exit;
    }
   /* if(strtolower($Age_Above_5_Years)=='no' && $dif->y >= 5 ){
        echo "This Disease is Age Specific ( Not For Age Above 5 Years Old !)";
        exit;
    }*/
?>