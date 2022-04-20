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
	
	 if(isset($_GET['transfers_IDs'])){
        $transfers_IDs = $_GET['transfers_IDs'];
    }else{
        $transfers_IDs = "";
    }
	
   /*  $Select_Update1 = mysqli_query($conn,"UPDATE tbl_consultation c
					SET c.Employee_ID='$Employee_ID'
                   WHERE 
						c.Registration_ID = '$Registration_ID' "); */
							
	
									
	$insertatin_querry=mysqli_query($conn,"INSERT INTO tbl_transfer(Registration_ID,Employee_ID_From,Employee_ID_To,Transfer_Date,
											Reason,Employee_ID_Do_Transfer,Patient_Direction)
				VALUES ('$Registration_ID','$transfers_IDs','$Employee_ID',(select now()),'$Reason','$didthis',
				'$Patient_Direction') ");
	
					
			echo "PATIENT SUCCESSFULLY TRANSFERED!";
?>