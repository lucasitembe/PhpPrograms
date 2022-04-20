<?php
require_once('includes/connection.php');

	$Registration_ID = $_GET['Registration_ID'];//
	$ppil = $_GET['ppil'];
        $Round_ID = $_GET['Round_ID'];
	
	$select_docservices = "
	SELECT * FROM tbl_patient_payments pp LEFT JOIN tbl_patient_payment_item_list ppil ON ppil.Patient_Payment_ID=pp.Patient_Payment_ID
		WHERE 
			pp.Registration_ID = '$Registration_ID' AND 
			pp.Round_ID = '$Round_ID' AND ppil.Check_In_Type='IPD Services' ";
	$select_docservices_qry = mysqli_query($conn,$select_docservices) or die(mysqli_error($conn));
        
        if(mysqli_num_rows($select_docservices_qry)>0){
            echo 'service served';
        }  else {
              echo 'service not served'; 
        }