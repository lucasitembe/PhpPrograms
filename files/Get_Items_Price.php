<?php
@session_start();
include("./includes/connection.php");
if (isset($_GET['Transaction_Mode'])) {
    $Transaction_Mode = $_GET['Transaction_Mode'];
} else {
    $Transaction_Mode = 'Normal Transaction';
}

$Guarantor_Name = "";
$Select_Price = '';
if (isset($_GET['Item_ID']) && ($_GET['Item_ID'] != '') ) {
    $Item_ID = $_GET['Item_ID'];
    $Billing_Type = $_GET['Billing_Type'];
    @$Guarantor_Name = $_GET['Guarantor_Name'];
    @$Sponsor_ID=$_GET['Sponsor_ID'];


    $sp = mysqli_query($conn,"SELECT Sponsor_ID FROM tbl_sponsor WHERE Guarantor_Name='$Guarantor_Name'") or die(mysqli_error($conn));
    if($Sponsor_ID == '' || $Sponsor_ID == 0){
        $Sponsor_ID = mysqli_fetch_assoc($sp)['Sponsor_ID'];
    }
    
    if (strtolower($Transaction_Mode) != 'fast track transaction' || strtolower($Billing_Type) == 'outpatient credit' || strtolower($Billing_Type) == 'inpatient credit') {

//        if ($Billing_Type == 'Outpatient Credit' || $Billing_Type == 'Inpatient Credit') {
//            
//        } elseif ($Billing_Type == 'Outpatient Cash' || $Billing_Type == 'Inpatient Cash') {
//            $defSponsor = "cash";
//            if (strtolower($Guarantor_Name) == 'cash' || strtolower(getPaymentMethod($Sponsor_ID)) == 'cash') {
//                $defSponsor = "cash";
//            }
//            $sp = mysqli_query($conn,"SELECT Sponsor_ID FROM tbl_sponsor WHERE LOWER(Guarantor_Name)='cash'") or die(mysqli_error($conn));
//            $Sponsor_ID = mysqli_fetch_assoc($sp)['Sponsor_ID'];
//        }

        // echo "<script>".$Sponsor_ID."</script>";

        $Select_Price = "SELECT Items_Price as price from tbl_item_price ip
                                        where ip.Item_ID = '$Item_ID' AND ip.Sponsor_ID = '$Sponsor_ID'";
       // echo $Sponsor_ID."<===id";
        $itemSpecResult = mysqli_query($conn,$Select_Price) or die(mysqli_error($conn));

        if (mysqli_num_rows($itemSpecResult) > 0) {
            $row = mysqli_fetch_assoc($itemSpecResult);
            $Price = $row['price'];

            $sqlcheck2 = "SELECT sponsor_id,item_ID FROM tbl_sponsor_allow_zero_items WHERE sponsor_id = '$Sponsor_ID' AND item_ID=" . $Item_ID . "";
            $check_if_covered2 = mysqli_query($conn,$sqlcheck2) or die(mysqli_error($conn));

            if (mysqli_num_rows($check_if_covered2) > 0) {
                
            } else {

                if ($Price == 0) {
                    $Select_Price = "SELECT Items_Price as price from tbl_general_item_price ig
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
            echo (($_SESSION['systeminfo']['price_precision'] == 'yes') ? number_format($Price, 2) : number_format($Price));
            //echo $Select_Price;
        } else {
            $Select_Price = "SELECT Items_Price as price from tbl_general_item_price ig
                                        where ig.Item_ID = '$Item_ID'";
            $itemGenResult = mysqli_query($conn,$Select_Price) or die(mysqli_error($conn));

            if (mysqli_num_rows($itemGenResult) > 0) {
                $row = mysqli_fetch_assoc($itemGenResult);
                echo (($_SESSION['systeminfo']['price_precision'] == 'yes') ? number_format($row['price'], 2) : number_format($row['price']));
            } else {
                echo (($_SESSION['systeminfo']['price_precision'] == 'yes') ? number_format(0, 2) : number_format(0));
            }
            //echo $Select_Price;
        }
    } else {
        //Get Fast Track Price
        $select = mysqli_query($conn,"SELECT Item_Price from tbl_Fast_Track_Price where Item_ID = '$Item_ID'") or die(mysqli_error($conn));
        $num = mysqli_num_rows($select);
        if ($num > 0) {
            $row = mysqli_fetch_assoc($select);
            echo (($_SESSION['systeminfo']['price_precision'] == 'yes') ? number_format($row['Item_Price'], 2) : number_format($row['Item_Price']));
        } else {
            mysqli_query($conn,"INSERT into tbl_Fast_Track_Price(Item_ID,Item_Price) values('$Item_ID','0')");
            echo '0';
        }
    }
} else {
      echo "<script>alert('no sponsor id specifield')</script>";;
}
?>