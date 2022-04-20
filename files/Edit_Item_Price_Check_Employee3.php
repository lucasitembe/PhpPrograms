<?php
	session_start();
	include("./includes/connection.php");

    if(isset($_GET['Username3'])){
    	$Username3 = $_GET['Username3'];
    }else{
    	$Username3 = '';
    }

    if(isset($_GET['Password3'])){
    	$Password3 = md5($_GET['Password3']);
    }else{
    	$Password3 = md5('');
    }

    //check if second employee entered correct info
    $select = mysqli_query($conn,"select Employee_ID from tbl_privileges where
                            Given_Username = '$Username3' and
                            Given_Password = '$Password3' and
                            Session_Master_Priveleges = 'yes'") or die(mysqli_error($conn));
    $num = mysqli_num_rows($select);
    if($num > 0){
        echo "yes";
    }else{
        echo "no";
    }
?>