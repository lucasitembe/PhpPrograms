<?php
	require_once('./connection.php');

	if(isset($_GET['facility_Code'])){
		$facility_Code = mysqli_real_escape_string($conn,$_GET['facility_Code']);
	} else {
		$facility_Code = "000";		
	}
	if(isset($_GET['period'])){
		$period = mysqli_real_escape_string($conn,$_GET['period']);
	} else {
		$period = 000000;		
	}
	if(isset($_GET['disease_Form_Id'])){
		$disease_Form_Id = mysqli_real_escape_string($conn,$_GET['disease_Form_Id']);
	} else {
		$disease_Form_Id = 0;		
	}
	if(isset($_GET['under_1_Month_Male'])){
		$under_1_Month_Male = mysqli_real_escape_string($conn,$_GET['under_1_Month_Male']);
	} else {
		$under_1_Month_Male = 0;
	}
	if(isset($_GET['under_1_month_Female'])){
		$under_1_month_Female = mysqli_real_escape_string($conn,$_GET['under_1_month_Female']);
	} else {
		$under_1_month_Female = 0;		
	}
	if(isset($_GET['1_month_Under_1_year_Male'])){
		$1_month_Under_1_year_Male = mysqli_real_escape_string($conn,$_GET['1_month_Under_1_year_Male']);
	} else {
		$1_month_Under_1_year_Male = 0;		
	}
	if(isset($_GET['1_month_Under_1_year_Female'])){
		$1_month_Under_1_year_Female = mysqli_real_escape_string($conn,$_GET['1_month_Under_1_year_Female']);
	} else {
		$1_month_Under_1_year_Female = 0;		
	}
	if(isset($_GET['1_year_Under_5_male'])){
		$1_year_Under_5_male = mysqli_real_escape_string($conn,$_GET['1_year_Under_5_male']);
	} else {
		$1_year_Under_5_male = 0;		
	}
	if(isset($_GET['1_year_Under_5_Female'])){
		$1_year_Under_5_Female = mysqli_real_escape_string($conn,$_GET['1_year_Under_5_Female']);
	} else {
		$1_year_Under_5_Female = 0;		
	}
	if(isset($_GET['5_Years_Under_60_Years_Male'])){
		$5_Years_Under_60_Years_Male = mysqli_real_escape_string($conn,$_GET['5_Years_Under_60_Years_Male']);
	} else {
		$5_Years_Under_60_Years_Male = 0;		
	}
	if(isset($_GET['5_Years_Under_60_Years_Female'])){
		$5_Years_Under_60_Years_Female = mysqli_real_escape_string($conn,$_GET['5_Years_Under_60_Years_Female']);
	} else {
		$5_Years_Under_60_Years_Female = 0;		
	}
	if(isset($_GET['60_Or_Above_Male'])){
		$60_Or_Above_Male = mysqli_real_escape_string($conn,$_GET['60_Or_Above_Male']);
	} else {
		$60_Or_Above_Male = 0;		
	}
	if(isset($_GET['60_Or_Above_Female'])){
		$60_Or_Above_Female = mysqli_real_escape_string($conn,$_GET['60_Or_Above_Female']);
	} else {
		$60_Or_Above_Female = 0;		
	}
	facility_Code=0001&period=".$period."&disease_Form_Id=1&under_1_Month_Male=".$Total_Waliohudhuria_Below_1_Month_Male."&under_1_month_Female=".$Total_Waliohudhuria_Below_1_Month_Female."&1_month_Under_1_year_Male=".$Total_Waliohudhuria_Between_1_Month_But_Below_1_Year_Male."&1_month_Under_1_year_Female=".$Total_Waliohudhuria_Between_1_Month_But_Below_1_Year_Female."&1_year_Under_5_male=".$Total_Waliohudhuria_Between_1_Year_But_Below_5_Year_Male."&1_year_Under_5_Female=".$Total_Waliohudhuria_Between_1_Year_But_Below_5_Year_Female."&5_Years_Under_60_Years_Male=".$Total_Waliohudhuria_Five_Years_Or_Below_Sixty_Years_Male."&5_Years_Under_60_Years_Female=".$Total_Waliohudhuria_Five_Years_Or_Below_Sixty_Years_Female."&60_Or_Above_Male=".$Total_Waliohudhuria_60_Years_And_Above_Male."&60_Or_Above_Female".$Total_Waliohudhuria_60_Years_And_Above_Female;
	
	
	$add_payment = "INSERT INTO tbl_payments(patient_payment_id, payment_code, payment_date, amount,balance, customer_name,payment_mode) VALUES('$PatientPaymentID', '$PaymentCode', '$Date', '$Amount', '$Amount', '$ClientName','POS')";
	
	$payment_added = mysqli_query($conn,$add_payment) or die(mysqli_error($conn));
	
	if($payment_added){
		$status =  'yes';
	} else {
		$status = 'no';
	}

		header("Content-type: text/xml");
		?>
		<respond>
			<status><?php echo $status;?></status>
		</respond>			
		<?php	
	
	
?>

