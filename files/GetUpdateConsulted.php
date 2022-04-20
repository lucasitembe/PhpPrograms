<?php
    include("./includes/connection.php");
  
	 if(isset($_GET['Registration_ID'])){
        $Registration_ID = $_GET['Registration_ID'];
    }else{
        $Registration_ID = "" ;
    }
	
	 if(isset($_GET['Reason'])){
        $Reason = $_GET['Reason'];
    }else{
        $Reason = "" ;
    }
	
	 if(isset($_GET['didthis'])){
        $didthis = $_GET['didthis'];
    }else{
        $didthis = "" ;
    }
	
	 if(isset($_GET['Patient_Direction'])){
        $Patient_Direction = $_GET['Patient_Direction'];
    }else{
        $Patient_Direction = "" ;
    } 
	
	
	 if(isset($_GET['Employee_ID'])){
        $Employee_ID = $_GET['Employee_ID'];
    }else{
        $Employee_ID = "" ;
    }
	
	 if(isset($_GET['Employee_IDs'])){
        $Employee_ID_From = $_GET['Employee_IDs'];
    }else{
        $Employee_ID_From = "";
    }
	
	$Select_Update1 = mysqli_query($conn,"UPDATE tbl_consultation c
					SET c.Employee_ID='$Employee_ID'
                   WHERE 
						c.Registration_ID = '$Registration_ID' ");
							
	 /* $Select_Update2 = mysqli_query($conn,"UPDATE tbl_patient_payment_item_list pp
					SET 
					pp.Consultant_ID='$Employee_ID'
                  WHERE pp.Patient_Payment_ID =(SELECT pp.Patient_Payment_ID
							FROM 
							   tbl_patient_payments p
							WHERE
								p.Patient_Payment_ID = pp.Patient_Payment_ID AND 
								p.Registration_ID ='$Registration_ID')"); */
									
	$insertatin_querry=mysqli_query($conn,"INSERT INTO tbl_transfer(Registration_ID,Employee_ID_From,Employee_ID_To,Transfer_Date,
											Reason,Employee_ID_Do_Transfer,Patient_Direction)
				VALUES ('$Registration_ID','$Employee_ID_From','$Employee_ID',(select now()),'$Reason','$didthis',
				'$Patient_Direction') ");
		
			$Select_Update1 = mysqli_query($conn,"UPDATE tbl_consultation c
					SET c.Employee_ID='$Employee_ID'
                   WHERE 
						c.Registration_ID = '$Registration_ID' ");
	
					
			echo "PATIENT SUCCESSFULLY TRANSFERED!";
?>