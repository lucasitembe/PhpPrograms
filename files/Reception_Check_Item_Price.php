<?php
include("./includes/connection.php");
$Select_Price = '';
if (isset($_GET['Item_ID']) && ($_GET['Item_ID'] != '') && isset($_GET['Guarantor_Name']) && ($_GET['Guarantor_Name'] != '')) {
    $Item_ID = $_GET['Item_ID'];
    $Billing_Type = $_GET['Billing_Type'];
    $Guarantor_Name = $_GET['Guarantor_Name'];

    $sp = mysqli_query($conn,"SELECT Sponsor_ID FROM tbl_sponsor WHERE Guarantor_Name='$Guarantor_Name'") or die(mysqli_error($conn));
    $Sponsor_ID = mysqli_fetch_assoc($sp)['Sponsor_ID'];

//    if ($Billing_Type == 'Outpatient Credit' || $Billing_Type == 'Inpatient Credit') {
//        $sp = mysqli_query($conn,"SELECT Sponsor_ID FROM tbl_sponsor WHERE Guarantor_Name='$Guarantor_Name'") or die(mysqli_error($conn));
//        $Sponsor_ID = mysqli_fetch_assoc($sp)['Sponsor_ID'];
//    } elseif ($Billing_Type == 'Outpatient Cash' || $Billing_Type == 'Inpatient Cash') {
//        $sp = mysqli_query($conn,"SELECT Sponsor_ID FROM tbl_sponsor WHERE LOWER(Guarantor_Name)='cash'") or die(mysqli_error($conn));
//        $Sponsor_ID = mysqli_fetch_assoc($sp)['Sponsor_ID'];
//    }

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
        $Select_Price = "select Items_Price as price from tbl_general_item_price ig where ig.Item_ID = '$Item_ID'";
        $itemGenResult = mysqli_query($conn,$Select_Price) or die(mysqli_error($conn));
        if (mysqli_num_rows($itemGenResult) > 0) {
            $row = mysqli_fetch_assoc($itemGenResult);
            $Price = $row['price'];
        } else {
            $Price = 0;
        }
    }
} else {
    $Price = 0;
}
if ($Price > 0) {
    echo "yes";
} else {
    //
    $zerosponsorItemQuery = "select item_ID from tbl_sponsor_allow_zero_items ig where item_ID = '$Item_ID' AND sponsor_id='$Sponsor_ID'";
    $zerosponsorItemResult = mysqli_query($conn,$zerosponsorItemQuery) or die(mysqli_error($conn));

    if (mysqli_num_rows($zerosponsorItemResult) > 0) {
        echo "yes";
    } else {
        echo "no";
    }
}
?>