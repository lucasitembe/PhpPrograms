<?php
	session_start();
	include("./includes/connection.php");
	/**
	*   Process Responce Code
	*   100 Refund required
	*   200 No enough payments to discharge
	*   300 Error occur during the process
	*   400 Credit Bill - No complication
	**/
	$Grand_Total = 0;
	$Grand_Total_Direct_Cash = 0;

	if(isset($_SESSION['userinfo']['Employee_ID'])){
		$Employee_ID = $_SESSION['userinfo']['Employee_ID'];
	}else{
		$Employee_ID = 0;
	}
    if(isset($_GET['excemption_number'])){
        $excemption_number = $_GET['excemption_number'];
    }else{
        $excemption_number = 0;
    }
    $Exemption_ID = $_GET['excemption_number'];
	if(isset($_GET['Patient_Bill_ID'])){
		$Patient_Bill_ID = $_GET['Patient_Bill_ID'];
	}else{
		$Patient_Bill_ID = '';
	}

	if(isset($_GET['Prepaid_ID'])){
		$Prepaid_ID = $_GET['Prepaid_ID'];
	}else{
		$Prepaid_ID = '';
	}


	if(isset($_GET['Registration_ID'])){
		$Registration_ID = $_GET['Registration_ID'];
	}else{
		$Registration_ID = '';
	}
    if(isset($_GET['imbalance_approval_reason'])){
        $imbalance_approval_reason=$_GET['imbalance_approval_reason'];
    }else{
        $imbalance_approval_reason="null";
    }
    
    if(isset($_GET['approve_bill_status'])){
        $approve_bill_status=$_GET['approve_bill_status'];
    }else{
        $approve_bill_status="null";
    }

    if(isset($_GET['Grand_Total'])){
        $Grand_Total=$_GET['Grand_Total'];
    }else{ 
        $Grand_Total="";
    }
    if(isset($_GET['Grand_Total_Direct_Cash'])){
        $Grand_Total_Direct_Cash=$_GET['Grand_Total_Direct_Cash'];
    }else{ 
        $Grand_Total_Direct_Cash="";
    }
 
	//check if status allows bill to be approved
	$select = mysqli_query($conn,"SELECT Status from tbl_prepaid_details where Prepaid_ID = '$Prepaid_ID'") or die(mysqli_error($conn));
	
	if(mysqli_num_rows($select)> 0){
		while ($data = mysqli_fetch_array($select)) {
			$Status = $data['Status'];
		}
	}else{
		$Status = '';
    }
    
    
        if(strtolower($Status) != 'cleared'){   
          
        $updatebill = mysqli_query($conn,"UPDATE tbl_prepaid_details set Status = 'cleared', Cleared_By = '$Employee_ID', excemption_number='$excemption_number',imbalance_approval_reason='$imbalance_approval_reason',  Cleared_Date_Time =NOW() where Prepaid_ID = '$Prepaid_ID'") or die(mysqli_error($conn));
       
		if($updatebill){
                
             $updatestatus = mysqli_query($conn, "UPDATE tbl_temporary_exemption_form SET exemptionstatus = 'Completed' WHERE Exemption_ID='$Exemption_ID'") or die(mysqli_error($conn));
    
			if($approve_bill_status == 'dept' && $Grand_Total > $Grand_Total_Direct_Cash){
               
                $select_bill =  mysqli_query($conn, "SELECT Patient_Bill_ID FROM tbl_patient_debt WHERE Patient_Bill_ID='$Patient_Bill_ID' AND Registration_ID='$Registration_ID' ") or die(mysqli_error($conn));
                if(mysqli_num_rows($select_bill)>0 ){
                   echo "Bill cleared,  Debit already created Successful";
                }else{
                    $sql_insert_dept = mysqli_query($conn, "INSERT INTO tbl_patient_debt(Admision_ID,Patient_Bill_ID,Registration_ID, Grand_Total, Grand_Total_Direct_Cash, Employee_ID )VALUES('$Admision_ID', '$Patient_Bill_ID', '$Registration_ID', '$Grand_Total', '$Grand_Total_Direct_Cash', '$Employee_ID')") or die(mysqli_error($conn));
                    $updatebill = mysqli_query($conn, "UPDATE tbl_patient_bill SET Status='cleared' WHERE Registration_ID='$Registration_ID' AND Patient_Bill_ID='$Patient_Bill_ID'") or die(mysqli_error($conn));
                    if($sql_insert_dept){
                        echo "Bill cleared Successful";
                    }else{
                        echo "Failed to create debit";
                    }
                }
            }else{
                echo "Bill cleared Successfully";
            }
		}else{
			echo "Error Occored";
        }
    }else{
        echo "Bill already Cleared";
    }
	
?>