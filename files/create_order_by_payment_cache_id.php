<?php

session_start();
include("./includes/connection.php");


$Employee_ID = 3157;
$Guarantor_Name = 'NHIF';
$Sponsor_ID = 1138;
$Item_ID = 2128;
$Consultation_Type = 'Radiology';
$Check_In_Type = 'Radiology';

$Sponsor_Name = $Guarantor_Name;


//die($Billing_Type);


   $payment_cache_ID= 56277;

    $Price = '';
    $inserted=true;
    if ($inserted) {

      


        $Select_Price = "select Items_Price as price from tbl_item_price ip
                                    where ip.Item_ID = '$Item_ID' AND ip.Sponsor_ID = '$Sponsor_ID'";
        $itemSpecResult = mysqli_query($conn,$Select_Price) or die(mysqli_error($conn));

        if (mysqli_num_rows($itemSpecResult) > 0) {

            $row = mysqli_fetch_assoc($itemSpecResult);
            $Price = $row['price'];

            $sqlcheck2 = "SELECT sponsor_id,item_ID FROM tbl_sponsor_allow_zero_items WHERE sponsor_id = '$Sponsor_ID' AND item_ID=" . $Item_ID . "";
            $check_if_covered2 = mysqli_query($conn,$sqlcheck2) or die(mysqli_error($conn));

            if (mysqli_num_rows($check_if_covered2) > 0) {
                
            } else {

                if ($Price == 0) {
                    $Select_Price = "select Items_Price as price from tbl_general_item_price ig
                                    where ig.Item_ID = '$Item_ID'";
                    $itemGenResult = mysqli_query($conn,$Select_Price) or die(mysqli_error($conn));

                    if (mysqli_num_rows($itemGenResult) > 0) {
                        $row = mysqli_fetch_assoc($itemGenResult);
                        $Price = $row['price'];
                    } else {
                        $Price = 0;
                    }
                }
            }
            // echo $Select_Price;
        } else {
            $Select_Price = "select Items_Price as price from tbl_general_item_price ig
                                    where ig.Item_ID = '$Item_ID'";
            $itemGenResult = mysqli_query($conn,$Select_Price) or die(mysqli_error($conn));

            if (mysqli_num_rows($itemGenResult) > 0) {

                $row = mysqli_fetch_assoc($itemGenResult);
                $Price = $row['price'];
                //die($Billing_Type." == Sponsor_ID=".$Sponsor_ID.' Price='.$Price);
            } else {
                $Price = 0;
            }
            //echo $Select_Price;
        }

        $Sub_Department_ID = 4;


        $Quantity = 1;
        $Patient_Direction = "others";
        $Consultant = '';
        $Consultant_ID = 3157;
        $Status = 'active';
        $Transaction_Date_And_Time = '2016-05-09 14:49:00';
        $Process_Status = 'inactive';
        $Doctor_Comment = '';
        $Transaction_Type = 'Credit';
        $Service_Date_And_Time ='';
        $Priority = '';
        $Discount =0;
        $Procedure_Location = '';
        $service_hour = '';
        $service_min = '';
        $insert_query2 = "INSERT INTO tbl_item_list_cache(Check_In_Type, Item_ID,Discount, Price, Quantity, Patient_Direction, Consultant, Consultant_ID, Status,
			Payment_Cache_ID, Transaction_Date_And_Time, Process_Status, Doctor_Comment,Sub_Department_ID,Transaction_Type,Service_Date_And_Time,Priority,Surgery_hour,Surgery_min,Procedure_Location)
			VALUES ('$Check_In_Type', '$Item_ID', $Discount, $Price, '$Quantity', '$Patient_Direction', '$Consultant', '$Consultant_ID',
			'$Status','$payment_cache_ID', '$Transaction_Date_And_Time',
			'$Process_Status', '$Doctor_Comment',$Sub_Department_ID,'$Transaction_Type','$Service_Date_And_Time','$Priority','$service_hour','$service_min','$Procedure_Location')";


        // die($insert_query2);
        if (!mysqli_query($conn,$insert_query2)) {
            die(mysqli_error($conn));
            exit;
        } else {
            echo "added";
        }
    } else {
        die("Not inserted");
    }

?>