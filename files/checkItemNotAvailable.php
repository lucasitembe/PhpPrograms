<?php
 include("../includes/connection.php");
    @session_start();
	$Consultation_Type = $_GET['Consultation_Type'];
	$Registration_ID = $_GET['Registration_ID'];
        $Item_ID = $_GET['Item_ID'];
        $consultation_ID = $_GET['consultation_ID'];
        
//Selecting Submitted Tests,Procedures, Drugs
	$select_payment_cache = "SELECT Status FROM tbl_payment_cache pc,tbl_item_list_cache ilc
				WHERE  pc.Payment_Cache_ID = ilc.Payment_Cache_ID
				AND  ilc.Item_ID='$Item_ID' AND 
                                     pc.consultation_ID = '$consultation_ID' AND 
                                     Registration_ID='$Registration_ID' AND
                                     Status IN ('active','paid')    
				";
				
	 //die($select_payment_cache);
	$cache_result = mysqli_query($conn,$select_payment_cache) or die(mysqli_error($conn));
        
        if(mysqli_num_rows($result) > 0){
            echo 'yes';
        }else{
             echo 'no';
        }