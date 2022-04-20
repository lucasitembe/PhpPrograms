<?php
	session_start();
	include("./includes/connection.php");
        

	if(isset($_POST['consent_treatement'])){
		$consent_treatement = $_POST['consent_treatement'];
	}else{
		$consent_treatement = 0;
	}
	if(isset($_POST['ll'])){
		$ll = $_POST['ll'];
	}else{
		$ll = 0;
	}
	if(isset($_POST['ul'])){
		$ul = $_POST['ul'];
	}else{
		$ul = 0;
	}
	if(isset($_POST['plan_analgesia'])){
		$plan_analgesia = $_POST['plan_analgesia'];
	}else{
		$plan_analgesia = 0;
	}
	if(isset($_POST['feet_footwear'])){
		$feet_footwear = $_POST['feet_footwear'];
	}else{
		$feet_footwear = 0;
	}
	if(isset($_POST['trunk_kyphosis'])){
		$trunk_kyphosis = $_POST['trunk_kyphosis'];
	}else{
		$trunk_kyphosis = 0;
	}
	if(isset($_POST['trunk_impared'])){
		$trunk_impared = $_POST['trunk_impared'];
	}else{
		$trunk_impared = 0;
	}
	if(isset($_POST['trunk_funcional'])){
		$trunk_funcional = $_POST['trunk_funcional'];
	}else{
		$trunk_funcional= 0;
	}
	if(isset($_POST['neck_dizziness'])){
		$neck_dizziness = $_POST['neck_dizziness'];
	}else{
		$neck_dizziness = 0;
	}
	if(isset($_POST['neck_impared'])){
		$neck_impared = $_POST['neck_impared'];
	}else{
		$neck_impared = 0;
	}
	if(isset($_POST['neck_functional'])){
		$neck_functional = $_POST['neck_functional'];
	}else{
		$neck_functional = 0;
	}
	if(isset($_POST['power2'])){
		$power2 = $_POST['power2'];
	}else{
		$power2 = 0;
	}
	if(isset($_POST['Defomity_text2'])){
		$Defomity_text2 = $_POST['Defomity_text2'];
	}else{
		$Defomity_text2 = 0;
	}
	if(isset($_POST['lower_impaired'])){
		$lower_impaired = $_POST['lower_impaired'];
	}else{
		$lower_impaired = 0;
	}
	if(isset($_POST['power'])){
		$power = $_POST['power'];
	}else{
		$power = 0;
	}
	if(isset($_POST['Defomity_text'])){
		$Defomity_text= $_POST['Defomity_text'];
	}else{
		$Defomity_text= 0;
	}
	if(isset($_POST['upper_impaired'])){
		$upper_impaired= $_POST['upper_impaired'];
	}else{
		$upper_impaired= 0;
	}
	if(isset($_POST['upper_functional'])){
		$upper_functional= $_POST['upper_functional'];
	}else{
		$upper_functional= 0;
	}
	if(isset($_POST['respiratory_screening'])){
		$respiratory_screening= $_POST['respiratory_screening'];
	}else{
		$respiratory_screening= 0;
	}
	if(isset($_POST['patient_perception'])){
		$patient_perception= $_POST['patient_perception'];
	}else{
		$patient_perception= 0;
	}
	if(isset($_POST['medical_clerking'])){
		$medical_clerking= $_POST['medical_clerking'];
	}else{
		$medical_clerking= 0;
	}
	if(isset($_POST['consent_hearing'])){
		$consent_hearing= $_POST['consent_hearing'];
	}else{
		$consent_hearing= 0;
	}
	if(isset($_POST['consent_vision'])){
		$consent_vision= $_POST['consent_vision'];
	}else{
		$consent_vision= 0;
	}
	if(isset($_POST['consent_speech'])){
		$consent_speech= $_POST['consent_speech'];
	}else{
		$consent_speech= 0;
	}
	if(isset($_POST['consent_cognition'])){
		$consent_cognition= $_POST['consent_cognition'];
	}else{
		$consent_cognition= 0;
	}
	if(isset($_POST['history_assessment'])){
		$history_assessment= $_POST['history_assessment'];
	}else{
		$history_assessment= 0;
	}
	if(isset($_POST['treatement_plan'])){
		$treatement_plan= $_POST['treatement_plan'];
	}else{
		$treatement_plan= 0;
	}
	if(isset($_POST['consent_interest'])){
		$consent_interest= $_POST['consent_interest'];
	}else{
		$consent_interest= 0;
	}
	if(isset($_POST['Registration_ID'])){
		$Registration_ID= $_POST['Registration_ID'];
	}else{
		$Registration_ID= 0;
	}
           
          echo $Registration_ID;
        
          
          
 $mysqli_check_simulation_data=mysqli_query($conn,"SELECT mobility_ID FROM  tbl_mobility_assessment_save WHERE Registration_ID='$Registration_ID' AND date(date_time_transaction)=CURDATE()");
   if(mysqli_num_rows($mysqli_check_simulation_data) > 0){
       
               $mobility_ID= mysqli_fetch_assoc(mysqli_query($conn,"SELECT mobility_ID FROM tbl_mobility_assessment_save WHERE Registration_ID='$Registration_ID' AND date(date_time_transaction)=CURDATE()"))['mobility_ID'];
       
        $sql_save_data = mysqli_query($conn,"UPDATE tbl_mobility_assessment_save SET consent_treatement='$consent_treatement',consent_interest='$consent_interest',treatement_plan='$treatement_plan',history_assessment='$history_assessment',consent_cognition='$consent_cognition',consent_speech='$consent_speech',consent_vision='$consent_vision',consent_hearing='$consent_hearing',medical_clerking='$medical_clerking',patient_perception='$patient_perception', respiratory_screening='$respiratory_screening',upper_functional='$upper_functional',upper_impaired='$upper_impaired',Defomity_text='$Defomity_text',"
                . "power='$power',lower_functional='$lower_functional',lower_impaired='$lower_impaired',Defomity_text2='$Defomity_text2',power2='$power2',neck_functional='$neck_functional',neck_impared='$neck_impared',neck_dizziness='$neck_dizziness',"
                . "trunk_funcional='$trunk_funcional',trunk_impared='$trunk_impared',trunk_kyphosis='$trunk_kyphosis', feet_footwear='$feet_footwear',plan_analgesia='$plan_analgesia',ul='$ul',ll='$ll',date_time_transaction=NOW()   WHERE mobility_ID='$mobility_ID'");
   }else{
//        $mysql_save_mobility_assessment = mysqli_query("INSERT INTO tbl_mobility_assessment_save(Registration_ID)VALUES('1')") or die(mysqli_error());
        $mysql_save_mobility_assessment = mysqli_query($conn,"INSERT INTO tbl_mobility_assessment_save(Registration_ID,consent_treatement,consent_interest,treatement_plan,history_assessment,consent_cognition,consent_speech,consent_vision,consent_hearing,medical_clerking,patient_perception,respiratory_screening,upper_functional,upper_impaired,Defomity_text,"
                . "                                     power,lower_functional,lower_impaired,Defomity_text2,power2,neck_functional,neck_impared,neck_dizziness,trunk_funcional,trunk_impared,trunk_kyphosis,feet_footwear,"
                . "                                     plan_analgesia,ul,ll,date_time_transaction)VALUES('$Registration_ID','$consent_treatement','$consent_interest','$treatement_plan','$history_assessment','$consent_cognition','$consent_speech','$consent_vision','$consent_hearing','$medical_clerking','$patient_perception','$respiratory_screening','$upper_functional','$upper_impaired','$Defomity_text',"
                . "                                     '$power','$lower_functional','$lower_impaired','$Defomity_text2','$power2','$neck_functional','$neck_impared','$neck_dizziness','$trunk_funcional','$trunk_impared','$trunk_kyphosis','$feet_footwear',"
                . "                                     '$plan_analgesia','$ul','$ll',NOW())") or die(mysqli_error($conn));
   }