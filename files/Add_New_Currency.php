<?php
	session_start();
	include("./includes/connection.php");
	if(!isset($_SESSION['userinfo'])){
		@session_destroy();
		header("Location: ../index.php?InvalidPrivilege=yes");
    }

    if(isset($_GET['Currency_Name'])){
    	$Currency_Name = mysqli_real_escape_string($conn,$_GET['Currency_Name']);
    }else{
    	$Currency_Name = '';
    }
    
    if(isset($_GET['Currency_Symbol'])){
    	$Currency_Symbol = mysqli_real_escape_string($conn,$_GET['Currency_Symbol']);
    }else{
    	$Currency_Symbol = '';
    }
    
    if(isset($_GET['Conversion_Rate'])){
    	$Conversion_Rate = mysqli_real_escape_string($conn,$_GET['Conversion_Rate']);
    }else{
    	$Conversion_Rate = '';
    }

    if($Currency_Name != null && $Currency_Name != '' && $Currency_Symbol != null && $Currency_Symbol != '' && $Conversion_Rate != null && $Conversion_Rate != ''){
    	$insert = mysqli_query($conn,"insert into tbl_multi_currency(Currency_Name, Currency_Symbol, Conversion_Rate)
    						values('$Currency_Name','$Currency_Symbol','$Conversion_Rate')");
    	if($insert){
    		echo "yes";
    	}else{
    		$error = '1062yes';
    		if(mysql_errno()."yes" == $error){
    			echo "repetition";
    		}else{
    			echo "error";
    		}
    	}
    }
?>