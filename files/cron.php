<?php
	session_start();
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

	//get pending transactions
	$select = mysqli_query($conn,"select pr.Registration_ID, bt.Payment_Code, bt.Amount_Required, bt.Employee_ID, bt.Transaction_Status, bt.Transaction_ID,
							bt.Transaction_Date_Time, bt.Transaction_Date, pr.Patient_Name, pr.Date_Of_Birth, pr.Gender, pr.Phone_Number, pr.Registration_Date_And_Time
							from tbl_bank_transaction_cache bt, tbl_patient_registration pr where 
							bt.Transaction_Status = 'pending' and
							pr.Registration_ID = bt.Registration_ID limit 50") or die(mysqli_error($conn));
	$num = mysqli_num_rows($select);

	if($num > 0){
		while ($data = mysqli_fetch_array($select)) {
			$Transaction_ID = $data['Transaction_ID'];
			$Registration_ID = $data['Registration_ID'];
			$Payment_Code = $data['Payment_Code'];
			$Amount_Required = $data['Amount_Required'];
			$Employee_ID  = $data['Employee_ID'];
			$Transaction_Date_Time = $data['Transaction_Date_Time'];
			$Transaction_Date  = $data['Transaction_Date'];
			$Transaction_Status = $data['Transaction_Status'];
			$Patient_Name = $data['Patient_Name'];
			$Date_Of_Birth = $data['Date_Of_Birth'];
			$Gender = $data['Gender'];
			$Phone_Number = $data['Phone_Number'];
			$Registration_Date_And_Time = $data['Registration_Date_And_Time'];

			//update remote server
			//$remote_payment  = simplexml_load_file("http://gpitg.com/wsdlapi/response.php?Registration_ID=$Registration_ID&Payment_Code=$Payment_Code&Amount_Required=$Amount_Required&Employee_ID=$Employee_ID&Transaction_Date_Time=$Transaction_Date_Time&Transaction_Date=$Transaction_Date&Transaction_Status=$Transaction_Status&Patient_Name=$Patient_Name&Date_Of_Birth=$Date_Of_Birth&Gender=$Gender&Phone_Number=$Phone_Number&Registration_Date_And_Time=$Registration_Date_And_Time");
			$remote_payment  = simplexml_load_file("http://127.0.0.1/api/response.php?Registration_ID=$Registration_ID&Payment_Code=$Payment_Code&Amount_Required=$Amount_Required&Employee_ID=$Employee_ID&Transaction_Date_Time=$Transaction_Date_Time&Transaction_Date=$Transaction_Date&Transaction_Status=$Transaction_Status&Patient_Name=$Patient_Name&Date_Of_Birth=$Date_Of_Birth&Gender=$Gender&Phone_Number=$Phone_Number&Registration_Date_And_Time=$Registration_Date_And_Time");
				$remote_status = $remote_payment->STATUS;
				if($remote_status == 300){
					$results = mysqli_query($conn,"update tbl_bank_transaction_cache set Transaction_Status = 'uploaded' where Transaction_ID = '$Transaction_ID'") or die(mysqli_error($conn));
					//echo $remote_status.'<br/>';
				}else if($remote_status == 301){
					//code ......................
					//echo $remote_status.'<br/>';
				}else{
					//code ......................
					//echo $remote_status.'<br/>';
					//302 - Record exists
				}
		}
	}
?>