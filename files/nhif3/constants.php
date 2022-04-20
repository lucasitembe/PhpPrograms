<?php 
session_start();
include ('../includes/connection.php');
$retrieve = mysqli_query($conn,"SELECT nhif_base_url,private_key,public_key,facility_code,nhif_username FROM tbl_system_configuration WHERE Branch_ID='" . $_SESSION['userinfo']['Branch_ID'] . "'");
$data=  mysqli_fetch_assoc($retrieve);
define('base_url',$data['nhif_base_url']);
define('private_key',$data['private_key']);
define('public_key',$data['public_key']);
define('FacilityCode', $data['facility_code']);
define('UserName', $data['nhif_username']);


    define('AUTHORIZATION_TOKEN_END_POINT','https://verification.nhif.or.tz/nhifservice/Token');
    define('AUTHORIZATION_SERVICE_BASE_URL','https://verification.nhif.or.tz/nhifservice/breeze/');
    
	//For testing on IIS Express on Development Machine
    define('TEST_CLAIMS_TOKEN_END_POINT','http://196.13.105.15/claimsserver/Token');
    define('TEST_CLAIMS_SERVICE_BASE_URL','http://196.13.105.15/claimsserver/api/v1/');

    define('CLAIMS_TOKEN_END_POINT','https://verification.nhif.or.tz/claimsserver/Token');
    define('CLAIMS_SERVICE_BASE_URL','https://verification.nhif.or.tz/claimsserver/api/v1/');
	
	//Credentials Put your Credentials
   
    define('username','bugandomc');
    define('password','bugandomc@2014');
	
?>
