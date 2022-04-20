<?php
include("./includes/connection.php");
@session_start();

 if(isset($_POST['currentValue'])){
		$Vital_Name = mysqli_real_escape_string($conn,$_POST['currentValue']);
                $id = mysqli_real_escape_string($conn,$_POST['id']);
                
		$sql = "UPDATE tbl_vital SET Vital='$Vital_Name' WHERE Vital_ID='$id'";
		
		if(!mysqli_query($conn,$sql)){ 
			$error = '1062yes';
			    if(mysql_errno()."yes" == $error){ 
                           echo 0;
			}
		}
		else { 
                    echo 1; 
		}
	}