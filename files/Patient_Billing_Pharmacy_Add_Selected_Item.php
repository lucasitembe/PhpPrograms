<?php

session_start();
include("./includes/connection.php");
$temp = 1;
$total = 0;
if (isset($_SESSION['userinfo']['Employee_ID'])) {
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
} else {
    $Employee_ID = 0;
}

if (isset($_SESSION['userinfo']['Employee_Name'])) {
    $Employee_Name = $_SESSION['userinfo']['Employee_Name'];
} else {
    $Employee_Name = 0;
}

if (isset($_GET['Registration_ID'])) {
    $Registration_ID = $_GET['Registration_ID'];
} else {
    $Registration_ID = 0;
}

if (isset($_GET['Item_ID'])) {
    $Item_ID = $_GET['Item_ID'];
} else {
    $Item_ID = 0;
}

if (isset($_GET['Comment'])) {
    $Comment = $_GET['Comment'];
} else {
    $Comment = '';
}

if (isset($_GET['Quantity'])) {
    $Quantity = $_GET['Quantity'];
} else {
    $Quantity = 0;
}

if (isset($_GET['Transaction_Type'])) {
    $Transaction_Type = $_GET['Transaction_Type'];
} else {
    $Transaction_Type = 0;
}
if (isset($_GET['Check_In_Type'])) {
    $Check_In_Type = $_GET['Check_In_Type'];
} else {
    $Check_In_Type = "Pharmacy";
}

//get sub department id
if (isset($_SESSION['Pharmacy_ID'])) {
    $Sub_Department_ID = $_SESSION['Pharmacy_ID'];
} else {
    $Sub_Department_ID = 0;
}

//Get Sub_Department_Name
$select = mysqli_query($conn,"select Sub_Department_Name from tbl_sub_department where Sub_Department_ID = '$Sub_Department_ID'") or die(mysqli_error($conn));
$noz = mysqli_num_rows($select);
if ($noz > 0) {
    while ($dt = mysqli_fetch_array($select)) {
        $Sub_Department_Name = $dt['Sub_Department_Name'];
    }
} else {
    $Sub_Department_Name = '';
}

if (isset($_GET['Guarantor_Name'])) {
    $Guarantor_Name = $_GET['Guarantor_Name'];
} else {
    $Guarantor_Name = '';
}

if (isset($_GET['Payment_Cache_ID'])) {
    $Payment_Cache_ID = $_GET['Payment_Cache_ID'];
} else {
    $Payment_Cache_ID = '';
}

if (isset($_GET['Billing_Type'])) {
    $Billing_Type = $_GET['Billing_Type'];
} else {
    $Billing_Type = '';
}

if (isset($_GET['dosade_duration'])) {
    $dosade_duration = $_GET['dosade_duration'];
} else {
    $dosade_duration = 0;
}

if (isset($_GET['Price'])) {
    $Pric = $_GET['Price'];
} else {
    $Pric = 0;
}

//get some imprtant previous details 
$get_details = mysqli_query($conn,"select Consultant, Consultant_ID from tbl_item_list_cache where 
                                Payment_Cache_ID = '$Payment_Cache_ID' and
                                Check_In_Type = '$Check_In_Type' and
                                (Status = 'active' or Status = 'approved')") or die(mysqli_error($conn));

$num = mysqli_num_rows($get_details);

if ($num > 0) {
    while ($data = mysqli_fetch_array($get_details)) {
        $Consultant = $data['Consultant'];
        $Consultant_ID = $data['Consultant_ID'];
    }
} else {
    $Consultant = $Employee_Name;
    $Consultant_ID = $Employee_ID;
}
$Sponsor_ID=$_GET['Sponsor_ID'];
if (isset($_GET['Item_ID']) && ($_GET['Item_ID'] != '') && isset($_GET['Guarantor_Name']) && ($_GET['Guarantor_Name'] != '')) {
    $Item_ID = $_GET['Item_ID'];
    $Guarantor_Name = $_GET['Guarantor_Name'];
    $Price = 0;

    if ($Billing_Type == 'Outpatient Credit' || $Billing_Type == 'Inpatient Credit') {
        $sp = mysqli_query($conn,"SELECT Sponsor_ID FROM tbl_sponsor WHERE Guarantor_Name='$Guarantor_Name'") or die(mysqli_error($conn));
        $Sponsor_ID2 = mysqli_fetch_assoc($sp)['Sponsor_ID'];
        $Sponsor_ID=$_GET['Sponsor_ID'];
    } elseif ($Billing_Type == 'Outpatient Cash' || $Billing_Type == 'Inpatient Cash') {
        $sp = mysqli_query($conn,"SELECT Sponsor_ID FROM tbl_sponsor WHERE LOWER(Guarantor_Name)='cash'") or die(mysqli_error($conn));
        $Sponsor_ID2 = mysqli_fetch_assoc($sp)['Sponsor_ID'];
    }

    $Select_Price = "select Items_Price as price from tbl_item_price ip
                            where ip.Item_ID = '$Item_ID' AND ip.Sponsor_ID = '$Sponsor_ID'";
    $itemSpecResult = mysqli_query($conn,$Select_Price) or die(mysqli_error($conn));

    if (mysqli_num_rows($itemSpecResult) > 0) {
        $row = mysqli_fetch_assoc($itemSpecResult);
        $Price = $row['price'];
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
    } else {
        $Select_Price = "select Items_Price as price from tbl_general_item_price ig
                                    where ig.Item_ID = '$Item_ID'";
        $itemGenResult = mysqli_query($conn,$Select_Price) or die(mysqli_error($conn));
        $row = mysqli_fetch_assoc($itemGenResult);
        $Price = $row['price'];
    }
} else {
    $Price = '0';
}


//get employee id
if (isset($_SESSION['userinfo']['Employee_ID'])) {
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
} else {
    $Employee_ID = 0;
}

//validate data entered then proceed
if ($Employee_ID != 0 && $Registration_ID != 0 && $Item_ID != 0 && $Quantity != '' && $Employee_ID != 0 && $Billing_Type != '' && $Sub_Department_ID != 0 && $Sub_Department_ID != null) {

    //add selected item
    $prc = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Items_Price AS price FROM tbl_item_price ip WHERE ip.Item_ID = '$Item_ID' AND ip.Sponsor_ID = '$Sponsor_ID'"))['price'];

    $insert = mysqli_query($conn,"INSERT into tbl_item_list_cache(
                                Check_In_Type, Item_ID, Price, dose,
                                Edited_Quantity, Patient_Direction, Consultant, Consultant_ID, 
                                Status, Payment_Cache_ID, Transaction_Date_And_Time, Doctor_Comment,
                                Sub_Department_ID, Transaction_Type, Service_Date_And_Time,
                                Employee_Created, Created_Date_Time,dosage_duration) 
                            values('$Check_In_Type','$Item_ID','$prc','$Quantity',
                                '$Quantity','Others','$Consultant','$Consultant_ID',
                                'active','$Payment_Cache_ID',(select now()),'$Comment',
                                '$Sub_Department_ID','$Transaction_Type',(select now()),
                                '$Employee_ID',(select now()),'$dosade_duration')") or die(mysqli_error($conn));
}
// $Transaction_Status_Title = 'NOT YET APPROVED';
// include "Pharmacy_Works_Page_Iframe.php";
?>