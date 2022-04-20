<?php
	session_start();
	include("./includes/connection.php");

    if(isset($_GET['Username2'])){
    	$Username2 = $_GET['Username2'];
    }else{
    	$Username2 = '';
    }

    if(isset($_GET['Password2'])){
    	$Password2 = md5($_GET['Password2']);
    }else{
    	$Password2 = md5('');
    }

    //check if second employee entered correct info
    $select = mysqli_query($conn,"select Employee_ID from tbl_privileges where
                            Given_Username = '$Username2' and
                            Given_Password = '$Password2' and
                            Session_Master_Priveleges = 'yes'") or die(mysqli_error($conn));
    $num = mysqli_num_rows($select);
    if($num > 0){
        echo "yes";
    }else{
        echo "no";
    }
?>