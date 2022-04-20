<?php
	//include("./includes/connection.php");
	//include("C:\xampp\htdocs\Final_One\files\includes\connection.php");
// 1. Create a database connection
	$connection = mysql_connect("127.0.0.1","root","");
	if (!$connection) {
		die("Database connection failed: " . mysqli_error($conn));
	}
	
	// 2. Select a database to use
	//$db_select = mysql_select_db("amana_new_2015_07_27",$connection);
	$db_select = mysql_select_db("final_one",$connection);
	//$db_select = mysql_select_db("amana_new_2015_08_31",$connection);
	if (!$db_select) {
		die("Database selection failed: " . mysqli_error($conn));
	}

	$doc = new DOMDocument;
	$doc->load("http://127.0.0.1/api/croner_payments_update.php");

	$Patient_Name = $doc->getElementsByTagName('PATIENT_NAME'); //array patients names
	$Registration_ID = $doc->getElementsByTagName('REGISTRATION_ID'); //array registration numbers
	$Amount_Paid = $doc->getElementsByTagName('AMOUNT_PAID'); //array amounts paid
	$Payment_Code = $doc->getElementsByTagName('PAYMENT_CODE'); //array payments codes
	$Payment_Receipt = $doc->getElementsByTagName('PAYMENT_RECEIPT'); //array receipt numbers
	$Transaction_Ref = $doc->getElementsByTagName('TRANSACTION_REF'); //array transaction ref
	$Transaction_Date = $doc->getElementsByTagName('TRANSACTION_DATE'); //array transaction dates
	$Payment_ID = $doc->getElementsByTagName('PAYMENT_ID'); //array payments ids
	$Controler = $doc->getElementsByTagName('CONTROLER'); //array controler	

	for ($i = 0; $i < $Patient_Name->length; $i++) {
		$Control = 1;
	    $P_Name =  $Patient_Name->item($i)->nodeValue;
	    $R_ID = $Registration_ID->item($i)->nodeValue;
	    $A_Paid = $Amount_Paid->item($i)->nodeValue;
	    $P_Code = $Payment_Code->item($i)->nodeValue;
	    $P_Receipt = $Payment_Receipt->item($i)->nodeValue;
	    $T_Ref = $Transaction_Ref->item($i)->nodeValue;
	    $T_Date = $Transaction_Date->item($i)->nodeValue;
	    $P_ID = $Payment_ID->item($i)->nodeValue;
	    $Cont = $Controler->item($i)->nodeValue; //Controler Value
	   	

	    if($Cont == 200){
	    	$insert = mysqli_query($conn,"insert into tbl_bank_api_payments_details(Patient_Name, Registration_ID, Amount_Paid,P_ID,
	    							Payment_Code, Payment_Receipt, Transaction_Ref, Transaction_Date)
	    							
	    							values('$P_Name','$R_ID','$A_Paid','$P_ID',
	    									'$P_Code','$P_Receipt','$T_Ref','$T_Date')") or die(mysqli_error($conn));
	    	if($insert){
	    		$update_remote  = simplexml_load_file("http://127.0.0.1/api/croner_payments_update_responce.php?Payment_ID=$P_ID");
	    		$remote_status = $update_remote->STATUS;
    			if($remote_status == 300){
    				mysqli_query($conn,"delete from tbl_bank_api_payments_details where P_ID = '$P_ID'") or die(mysqli_error($conn));
    			}
	    	}
	    }
	}
?>