<?php
include("./includes/connection.php");
 
if(isset($_POST['Sub_Department_ID'])&&isset($_POST['Department_ID'])){
    $Sub_Department_ID=$_POST['Sub_Department_ID'];
    $Department_ID=$_POST['Department_ID'];
    $Employee_ID=$_POST['Employee_ID'];
    $sql = "insert into tbl_employee_sub_department(Employee_ID,Sub_Department_ID)
				values('$Employee_ID','$Sub_Department_ID')";
		
		if(!mysqli_query($conn,$sql)){
		    $error = '1062yes';
		    if(mysql_errno()."yes" == $error){ 
			echo $controlforminput = "<b>Duplication! Employee Already Assigned To Selected Sub Department </b>";
		    }else{
                       echo $controlforminput = "<b>Process Fail! Please Try Again</b>";
                    }
		}
		else {
		   echo  $controlforminput = '<b>Process Successful</b>';
		}
}else{
    echo "fail to execute";
}
