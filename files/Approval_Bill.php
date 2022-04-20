<?php
    @session_start();
    include("./includes/connection.php");
    include_once("./functions/items.php");
    if(isset($_SESSION['userinfo']['Employee_ID'])){
        $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    }else{
        $Employee_ID = 0;
    }

    if(isset($_GET['Sponsor_ID'])){
        $Sponsor_ID = $_GET['Sponsor_ID'];
    }else{
        $Sponsor_ID = '';
    }
    if(isset($_GET['Check_In_ID'])){
        $Check_In_ID = $_GET['Check_In_ID'];
    }else{
        $Check_In_ID = '';
    }
    if(isset($_GET['Bill_ID'])){
        $Bill_ID = $_GET['Bill_ID'];
    }else{
        $Bill_ID = '';
    }

    //get sponsor name
    $select = mysqli_query($conn,"select Guarantor_Name from tbl_sponsor where Sponsor_ID = '$Sponsor_ID'") or die(mysqli_error($conn));
    $nm = mysqli_num_rows($select);
    if($nm > 0){
        while ($row = mysqli_fetch_array($select)) {
            $Guarantor_Name = $row['Guarantor_Name'];
        }
    }else{
        $Guarantor_Name = '';
    }

    if(isset($_GET['Registration_ID'])){
        $Registration_ID = $_GET['Registration_ID'];
    }else{
        $Registration_ID = 0;
    }

    if(isset($_GET['Folio_Number'])){
        $Folio_Number = $_GET['Folio_Number'];
    }else{
        $Folio_Number = '';
    }

    if(isset($_GET['Patient_Bill_ID'])){
        $Patient_Bill_ID = $_GET['Patient_Bill_ID'];
    }else{
        $Patient_Bill_ID = '';
    }

	if(isset($_GET['Patient_Payment_ID'])){
		$Patient_Payment_ID = $_GET['Patient_Payment_ID'];
	}else{
		$Patient_Payment_ID = '';
	}

    //echo 'Employee ID : '.$Employee_ID.'  ,Sponsor ID : '.$Sponsor_ID.'  ,Registration ID : '.$Registration_ID.'  ,Payment Type  : '.$Payment_Type.'  ,Folio Number  : '.$Folio_Number; exit;
    if($Employee_ID != 0 && $Sponsor_ID != null && $Sponsor_ID != '' && $Folio_Number != '' && $Folio_Number != null){
        Start_Transaction();
		$has_error = false;

        $update_Transaction = mysqli_query($conn,"UPDATE tbl_patient_payments set       Billing_Process_Status = 'Approved', Billing_Process_Employee_ID = '$Employee_ID', Billing_Process_Date = (select now())    where Sponsor_ID = '$Sponsor_ID' and   Registration_ID = '$Registration_ID'   and     Patient_Bill_ID = '$Patient_Bill_ID' AND Check_In_ID='$Check_In_ID' and     (Billing_Type = 'Outpatient Credit' or Billing_Type = 'Inpatient Credit') and    Bill_ID IS NULL") or die(mysqli_error($conn));
// and Folio_Number = '$Folio_Number' 
        if($update_Transaction){
            $sqlreceipt = mysqli_query($conn, "SELECT Patient_Payment_ID FROM tbl_patient_payments WHERE Sponsor_ID = '$Sponsor_ID' and   Registration_ID = '$Registration_ID'  and     Patient_Bill_ID = '$Patient_Bill_ID' and     (Billing_Type = 'Outpatient Credit' or Billing_Type = 'Inpatient Credit') and  Billing_Process_Status = 'Approved' AND Check_In_ID='$Check_In_ID' AND   Bill_ID IS NULL") or die(mysqli_error($conn));
            if(mysqli_num_rows($sqlreceipt)>0){
                while($row = mysqli_fetch_assoc($sqlreceipt)){
                    $Patient_Payment_ID = $row['Patient_Payment_ID'];
                    $update_list = mysqli_query($conn,"UPDATE tbl_patient_payment_item_list SET Billing_approval_status='yes' WHERE Patient_Payment_ID='$Patient_Payment_ID' AND Price<>0") or die(mysqli_error($conn));
                    if($update_list){

                    }else{
                        $has_error = true;
                    }
                }
            }
			

        } else {

		$has_error = true;

		}


		if (!$has_error) {
            Commit_Transaction();

			$select_Employee_Name = mysqli_query($conn,"select Employee_Name from tbl_employee where Employee_ID = '$Employee_ID'") or die(mysqli_error($conn));
            $no2 = mysqli_num_rows($select_Employee_Name);
            if($no2 > 0){
                while($row = mysqli_fetch_array($select_Employee_Name)){
                    $Employee_Name = $row['Employee_Name'];
                }
            } else {
                $Employee_Name = '';
            }

        } else {
            Rollback_Transaction();
        }

        echo '<b style="color: #037CB0;">Approved By'.$Employee_Name.'</b> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
        //echo '<button class="art-button-green">SEND TO eBIVE</button> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';

		echo "<a href='eclaimprintreport.php?Folio_Number=".$Folio_Number."&Insurance=".$Guarantor_Name."&Registration_ID=".$Registration_ID."&Patient_Bill_ID=".$Patient_Bill_ID."&Check_In_ID=".$Check_In_ID."&Bill_ID=".$Bill_ID."' style='text-decoration: none;' class='art-button-green' target='_blank'>PRINT PATIENT BILL</a>";
    }else{
        echo '';
    }
?>
