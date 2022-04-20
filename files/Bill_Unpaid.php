<?php

session_start();
include("./includes/connection.php");
$Payment_Cache_ID = '';
if (isset($_GET['Payment_Cache_ID'])) {
    $Payment_Cache_ID = $_GET['Payment_Cache_ID'];
}

if (isset($_SESSION['supervisor'])) {
    if (isset($_SESSION['supervisor']['Session_Master_Priveleges'])) {
        if ($_SESSION['supervisor']['Session_Master_Priveleges'] = 'yes') {
            $Supervisor_ID = $_SESSION['supervisor']['Employee_ID'];
        } else {
            $Supervisor_ID = '';
        }
    } else {
        $Supervisor_ID = '';
    }
} else {
    $Supervisor_ID = '';
}
//end of fetching supervisor id   
//get branch ID
if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Branch_ID'])) {
        $Branch_ID = $_SESSION['userinfo']['Branch_ID'];
    } else {
        $Branch_ID = '';
    }
} else {
    $Branch_ID = '';
}
//end of fetching branch ID
//get employee id
if (isset($_SESSION['userinfo']['Employee_ID'])) {
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
} else {
    $Employee_ID = '';
}


if (isset($_POST['action'])) {
    $datastring = $_POST['datastring'];
    $Payment_Cache_ID = $_POST['Payment_Cache_ID'];
    if ($_POST['action'] == 'makepayment') {
        $results = explode('@', $datastring);

        foreach ($results as $value) {
            $select_medication = mysqli_query($conn,"select * from tbl_item_list_cache ilc where
                                                        Payment_Item_Cache_List_ID = '$value' and status = 'removed'") or die(mysqli_error($conn));
            
            $row3 = mysqli_fetch_array($select_medication);
            $Payment_Item_Cache_List_ID = $row3['Payment_Item_Cache_List_ID'];
            $Check_In_Type = $row3['Check_In_Type'];
            $Item_ID = $row3['Item_ID'];
            $Discount = $row3['Discount'];
            $Price = $row3['Price'];
            $Patient_Direction = $row3['Patient_Direction'];
            $Consultant = $row3['Consultant'];
            $Consultant_ID = $row3['Consultant_ID'];
            $Quantity = $row3['Quantity'];
             $payment_type = $row3['payment_type'];

           
            $select_cache_info = "select * from tbl_payment_cache where payment_cache_id = '$Payment_Cache_ID'";
        //echo $select_cache_info;
        $result = mysqli_query($conn,$select_cache_info);
        $row = mysqli_fetch_array($result);
        $Registration_ID = $row['Registration_ID'];
        $Folio_Number = $row['Folio_Number'];
        $Sponsor_ID = $row['Sponsor_ID'];
        $Sponsor_Name = $row['Sponsor_Name'];
        $Billing_Type= $row['Billing_Type'];
        $Transaction_status = 'active';
        $Transaction_type = 'indirect cash';
        //select the last claim form number
        $select_claim_form = mysqli_query($conn,"select Claim_Form_Number from tbl_patient_payments where
                                                Folio_number = '$Folio_Number' and
                                                    Registration_ID = '$Registration_ID' and
                                                        Sponsor_ID = '$Sponsor_ID'
                                                            order by patient_payment_id desc limit 1");
        while ($row = mysqli_fetch_array($select_claim_form)) {
            $Claim_Form_Number = $row['Claim_Form_Number'];
        }

        include("./includes/Get_Patient_Transaction_Number.php");
        //time to insert data
        $insert = "insert into tbl_patient_payments(
                        Registration_ID,Supervisor_ID,Employee_ID,
                            Payment_Date_And_Time,Folio_Number,Claim_Form_Number,
                                Sponsor_ID,Sponsor_Name,Billing_Type,
                                    Receipt_Date,Transaction_status,Transaction_type,payment_type,Branch_ID,Patient_Bill_ID)
                    values('$Registration_ID','$Supervisor_ID','$Employee_ID',
                                (select now()),'$Folio_Number','$Claim_Form_Number',
                                    '$Sponsor_ID','$Sponsor_Name','$Billing_Type',
                                        (select now()),'$Transaction_status','$Transaction_type','$payment_type','$Branch_ID','$Patient_Bill_ID')";
         
          mysqli_query($conn,$insert) or die(mysqli_error($conn));
            //get patient payment id to use as a foreign key
            $select_patient_payment_id = mysqli_query($conn,"select Patient_Payment_ID, Payment_Date_And_Time from tbl_patient_payments where
                                            Registration_ID = '$Registration_ID' and
                                                Supervisor_ID = '$Supervisor_ID' and
                                                    Employee_ID = '$Employee_ID' order by patient_payment_id desc limit 1");
            $no = mysqli_num_rows($select_patient_payment_id);
            if ($no > 0) {
                while ($row2 = mysqli_fetch_array($select_patient_payment_id)) {
                    $Patient_Payment_ID = $row2['Patient_Payment_ID'];
                    $Payment_Date_And_Time = $row2['Payment_Date_And_Time'];
                }
            } else {
                $Patient_Payment_ID = '';
                $Payment_Date_And_Time = '';
            }
            
            //insert select record
            $Insert_Data_To_tbl_patient_payment_item_list = "insert into tbl_patient_payment_item_list(
                                    check_In_type,item_id,discount,
                                        price,quantity,patient_direction,
                                            consultant,consultant_id,patient_payment_id,Transaction_Date_And_Time,ItemOrigin)
                                    values(
                                        '$Check_In_Type','$Item_ID','$Discount',
                                            '$Price','$Quantity','$Patient_Direction',
                                                '$Consultant','$Consultant_ID','$Patient_Payment_ID',(select now()),'Doctor')";
            if (!mysqli_query($conn,$Insert_Data_To_tbl_patient_payment_item_list)) {
                //die(mysqli_error($conn));
                echo "<script type='text/javascript'>
			    alert('TRANSACTION FAIL');
			    document.location = './patientbilling.php?Fail=True&TarnsactionFail=TransactionFailThisPage';
			    </script>";
            } else {
                mysqli_query($conn,"update tbl_item_list_cache set status = 'paid',
                                        Patient_Payment_ID = '$Patient_Payment_ID',
                                        Payment_Date_And_Time = '$Payment_Date_And_Time' 
                                        where Payment_Item_Cache_List_ID = '$value'") or die(mysqli_error($conn));
            }
        }
        // $makepayment = mysqli_query($conn,"UPDATE tbl_item_list_cache SET Status='paid' WHERE Payment_Item_Cache_List_ID='" . $value . "'");
    }
}
    