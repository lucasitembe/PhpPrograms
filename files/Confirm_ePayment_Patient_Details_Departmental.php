<?php

session_start();
include("./includes/connection.php");
//get employee id
if (isset($_SESSION['userinfo']['Employee_ID'])) {
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
} else {
    $Employee_ID = 0;
}


$Control = 'yes';
if (isset($_GET['Section'])) {
    $Section = $_GET['Section'];
} else {
    $Section = '';
}

if (isset($_GET['Registration_ID'])) {
    $Registration_ID = $_GET['Registration_ID'];
} else {
    $Registration_ID = '';
}

if (isset($_GET['Payment_Cache_ID'])) {
    $Payment_Cache_ID = $_GET['Payment_Cache_ID'];
} else {
    $Payment_Cache_ID = '';
}

if (isset($_GET['Sub_Department_ID'])) {
    $Sub_Department_ID = $_GET['Sub_Department_ID'];
} else {
    $Sub_Department_ID = '';
}

if ($Section == 'Pharmacy') {
    $Status = 'approved';
} else {
    $Status = 'active';
}

if ($Section == 'MainPharmacy') {
    $Section = 'Pharmacy';
    $Status = 'active';
}

$src = "";
$pharmacylockupTable="";
if (isset($_GET['src']) && $_GET['src']) {
    $src = $_GET['src'];
    if($src == 'patlist'){
      $pharmacylockupTable="tbl_pharmacy_items_list_cache";  
    } elseif ($src == 'inpatlist') {
      $pharmacylockupTable="tbl_pharmacy_inpatient_items_list_cache";  
    }
}

//check if transaction contains zero price or quantity
if (strtolower($_SESSION['systeminfo']['Show_Pharmaceutical_Before_Payments']) == 'yes') {
   if($Section=="Others"){
     $ph = "select ilc.Price, ilc.Discount, ilc.Quantity, ilc.Edited_Quantity
                                                                            from tbl_item_list_cache ilc where ilc.status = '$Status' and
                                                                            ilc.Payment_Cache_ID = '$Payment_Cache_ID' and
                                                                          
                                                                            ilc.Transaction_Type = 'Cash' and
                                                                            ilc.Check_In_Type = '$Section' and
                                                                            ilc.ePayment_Status = 'pending'";  
   }else{
    $ph = "select ilc.Price, ilc.Discount, ilc.Quantity, ilc.Edited_Quantity
                                                                            from tbl_item_list_cache ilc where ilc.status = '$Status' and
                                                                            ilc.Payment_Cache_ID = '$Payment_Cache_ID' and
                                                                            ilc.Sub_Department_ID = '$Sub_Department_ID' and
                                                                            ilc.Transaction_Type = 'Cash' and
                                                                            ilc.Check_In_Type = '$Section' and
                                                                            ilc.ePayment_Status = 'pending'";
   }
    if (!empty($src)) {
        $ph = "select Price, Quantity, Discount, 0 AS Edited_Quantity
                    from tbl_items t, $pharmacylockupTable alc
                        where alc.Item_ID = t.Item_ID and
                            alc.Employee_ID = '$Employee_ID' and
                                    Registration_ID = '$Registration_ID'";
    }
    
    $select = mysqli_query($conn,$ph) or die(mysqli_error($conn));
} else {
    
    if (strtolower($Section) == 'pharmacy') {
        $ph = "select ilc.Price, ilc.Discount, ilc.Quantity, ilc.Edited_Quantity
									from tbl_item_list_cache ilc where (ilc.status = 'approved' or ilc.status = 'active') and
									ilc.Payment_Cache_ID = '$Payment_Cache_ID' and
									ilc.Sub_Department_ID = '$Sub_Department_ID' and
									ilc.Transaction_Type = 'Cash' and
									ilc.Check_In_Type = '$Section' and
									ilc.ePayment_Status = 'pending'";
        if (!empty($src)) {
            $ph = "select Price, Quantity, Discount, 0 AS Edited_Quantity
                    from tbl_items t, $pharmacylockupTable alc
                        where alc.Item_ID = t.Item_ID and
                            alc.Employee_ID = '$Employee_ID' and
                                    Registration_ID = '$Registration_ID'";
        }
        $select = mysqli_query($conn,$ph) or die(mysqli_error($conn));
    } else {
   if($Section=="Others"){
       
     $ph = "select ilc.Price, ilc.Discount, ilc.Quantity, ilc.Edited_Quantity
                                                                            from tbl_item_list_cache ilc where ilc.status = '$Status' and
                                                                            ilc.Payment_Cache_ID = '$Payment_Cache_ID' and
                                                                          
                                                                            ilc.Transaction_Type = 'Cash' and
                                                                            ilc.Check_In_Type = '$Section' and
                                                                            ilc.ePayment_Status = 'pending'";  
   }else{
        $select = mysqli_query($conn,"select ilc.Price, ilc.Discount, ilc.Quantity, ilc.Edited_Quantity
								from tbl_item_list_cache ilc where ilc.status = '$Status' and
								ilc.Payment_Cache_ID = '$Payment_Cache_ID' and
								ilc.Sub_Department_ID = '$Sub_Department_ID' and
								ilc.Transaction_Type = 'Cash' and
								ilc.Check_In_Type = '$Section' and
								ilc.ePayment_Status = 'pending'") or die(mysqli_error($conn));
        }
    }
}
$num = mysqli_num_rows($select);

if ($num > 0) {
    while ($data = mysqli_fetch_array($select)) {
        $Qty = 0;
        if ($data['Edited_Quantity'] > 0) {
            $Qty = $data['Edited_Quantity'];
        } else {
            $Qty = $data['Quantity'];
        }

        if ($data['Price'] < 1) {
            $Control = 'no';
        }

        if ($Qty < 1) {
            $Control = 'no';
        }
    }
} else {
    $Control = 'not';
}
echo $Control;
?>