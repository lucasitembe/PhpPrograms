<?php 
include './includes/constants.php';
//include("./includes/connection.php");
/////hard coded must be moved to connection file
	$connection2 = mysql_connect(EPAY_SERVER_HOST,EPAY_SERVER_USER,EPAY_SERVER_PASS);
	if (!$connection2) {
		die("Database connection failed: " . mysqli_error($conn));
	}
        $db_select2 = mysql_select_db(EPAY_SERVER_DB,$connection2);         
	if (!$db_select2) {
	   die("Database selection failed: " . mysqli_error($conn));
	}
 /////////////////////////////////////////            
if(isset($_GET['Payment_Code'])){
    $Payment_Code=$_GET['Payment_Code'];  
}else{
   $Payment_Code=""; 
}
if(isset($_GET['Registration_ID'])){
    $Registration_ID=$_GET['Registration_ID'];  
}else{
   $Registration_ID=""; 
}

$sql_check_if_transaction_completed="SELECT Registration_ID FROM tbl_bank_transaction_cache WHERE Registration_ID='$Registration_ID' AND Payment_Code='$Payment_Code' AND Amount_Required<='0' AND Transaction_Status='Completed'";
$sql_check_if_transaction_completed_result=mysqli_query($conn,$sql_check_if_transaction_completed) or die(mysqli_error($conn));


if(mysqli_num_rows($sql_check_if_transaction_completed_result)>0){

    echo "Completed";

}else{
    echo "not_Completed";
}
