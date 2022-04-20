<?php
    session_start();
    $Brand_Id = $_SESSION['userinfo']['Branch_ID'];
    include './includes/connection.php';

    $Registration_ID = $_GET['Registration_ID'];
    $Transaction_Type = $_GET['Transaction_Type'];
    $Check_In_Type = $_GET['Check_In_Type'];
    $from = $_GET['from'];
    $Start_Date = $_GET['Start_Date'];
    $End_Date = $_GET['end_date'];
    $Sponsor_ID = $_GET['Sponsor_ID'];
    $payment_method = mysqli_fetch_assoc(mysqli_query($conn,"SELECT payment_method FROM tbl_sponsor WHERE Sponsor_ID = '$Sponsor_ID' "))['payment_method'];
    $Billing_Type = "Inpatient " . $payment_method;

    $Billing_Type = ucwords($Billing_Type);

    $currentDate = mysqli_fetch_assoc(mysqli_query($conn,"SELECT CURDATE() AS today"))['today'];
    $get_last_consultation_id = mysqli_query($conn, "SELECT consultation_ID FROM tbl_consultation WHERE Registration_ID = '$Registration_ID' ORDER BY consultation_ID DESC LIMIT 1");
    if ($data = mysqli_fetch_assoc($get_last_consultation_id)) {
        $consultation_ID = $data['consultation_ID'];
    }

    #CHECK IF PAYMENT IF TODAY IS CREATED
    $select_last_cache_id = mysqli_query($conn,"SELECT Payment_Cache_ID FROM tbl_payment_cache WHERE Payment_Date_And_Time = '$currentDate' ORDER BY Payment_Cache_ID DESC LIMIT 1");
    if(mysqli_num_rows($select_last_cache_id) > 0){
        $newPayment_Cache_ID = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Payment_Cache_ID FROM `tbl_payment_cache` WHERE `Registration_ID` = '$Registration_ID' order by `Payment_Cache_ID` DESC limit 1 "))['Payment_Cache_ID'];
        header('Location:new_pharmacy_work_page.php?section=Pharmacy&Registration_ID=' . $Registration_ID . '&Payment_Cache_ID=' . $newPayment_Cache_ID . '&Transaction_Type='.$Transaction_Type.'&from=ipatient_list&Start_Date='.$Start_Date.'&end_date='.$End_Date.'&NR=True&Check_In_Type=Pharmacy');
    }else{
        #CREATE A NEW PAYMENT CACHE OF THE DAY
        $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
        # Create new payment id
        $insert_new_payment_cache_id = mysqli_query($conn, "INSERT INTO tbl_payment_cache (Registration_ID,Employee_ID,consultation_id,Payment_Date_And_Time,Sponsor_ID,Billing_Type,Transaction_status,Transaction_type,Order_Type,branch_id)
                                                            VALUES ('$Registration_ID','$Employee_ID','$consultation_ID',NOW(),'$Sponsor_ID','$Billing_Type','active','indirect cash','normal','$Brand_Id')") or die(mysqli_error($conn));
        if(!$insert_new_payment_cache_id){
            echo "Some thing went wrong try again";
        }else{
            $newPayment_Cache_ID = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Payment_Cache_ID FROM `tbl_payment_cache` WHERE `Registration_ID` = '$Registration_ID' order by `Payment_Cache_ID` DESC limit 1 "))['Payment_Cache_ID'];
            header('Location:new_pharmacy_work_page.php?section=Pharmacy&Registration_ID=' . $Registration_ID . '&Payment_Cache_ID=' . $newPayment_Cache_ID . '&Transaction_Type='.$Transaction_Type.'&from=ipatient_list&Start_Date='.$Start_Date.'&end_date='.$End_Date.'&NR=True&Check_In_Type=Pharmacy');
        }
    }
?>