<link rel="stylesheet" href="table33.css" media="screen">
<style>
    .itemhover{
        background-color:white;

    }   
    .itemhover:hover{
        cursor:pointer;
        color:#000; 
        background-color:#ccc;
    }.itemhoverlabl:hover{
        cursor:pointer;
    }
    .itemhoverlabl{
        width:100%;
        display: block;
    }
</style>
<?php
//  session_start();
include("./includes/connection.php");
$Item_ID = 0;

//$consultation_id = (isset($_GET['consultation_id']) ? $_GET['consultation_id'] : '');

if (isset($_GET['Consultation_Type'])) {
    $Consultation_Type = $_GET['Consultation_Type'];

    if ($Consultation_Type == 'Surgery') {
        //$Consultation_Type = 'Theater';
        $Consultation_Type = 'Surgery';
    }
    if ($Consultation_Type == 'Treatment') {
        $Consultation_Type = 'Pharmacy';
    }

    if (strtolower($Consultation_Type) == 'procedure') {
        $Consultation_Condition = "((d.Department_Location='Dialysis') OR
	    (d.Department_Location='Physiotherapy') OR (d.Department_Location='Optical')OR
	    (d.Department_Location='Dressing')OR(d.Department_Location='Maternity')OR
	    (d.Department_Location='Cecap')OR(d.Department_Location='Dental')OR
	    (d.Department_Location='Ear') OR(d.Department_Location='Hiv') OR
	    (d.Department_Location='Eye') OR(d.Department_Location='Maternity') OR
	    (d.Department_Location='Rch') OR(d.Department_Location='Theater') OR
	    (d.Department_Location='Family Planning')OR(d.Department_Location='Surgery')
	    OR(d.Department_Location='Procedure'))";

        $Consultation_Condition2 = "((Consultation_Type='Dialysis') OR
	    (Consultation_Type='Physiotherapy') OR (Consultation_Type='Optical')OR
	    (Consultation_Type='Dressing')OR(Consultation_Type='Maternity')OR
	    (Consultation_Type='Cecap')OR(Consultation_Type='Dental')OR(Consultation_Type='Ear') OR
	    (Consultation_Type='Hiv') OR(Consultation_Type='Eye') OR(Consultation_Type='Maternity') OR
	    (Consultation_Type='Rch') OR(Consultation_Type='Theater') OR
	    (Consultation_Type='Family Planning') OR
	    (Consultation_Type='Procedure'))";
    } else {
        $Consultation_Condition = "d.Department_Location = '$Consultation_Type'";
        $Consultation_Condition2 = "Consultation_Type='$Consultation_Type'";
    }
}

if (isset($_GET['Patient_Payment_Item_List_ID'])) {
    $Patient_Payment_Item_List_ID = $_GET['Patient_Payment_Item_List_ID'];
}

if (isset($_GET['Patient_Payment_Item_List_ID'])) {
    $Patient_Payment_Item_List_ID = $_GET['Patient_Payment_Item_List_ID'];
} else {
    $Patient_Payment_Item_List_ID = 0;
}
?>

<?php
//check if provisional diagnosis
//Selecting Submitted Tests,Procedures, Drugs

$test_name = ''; //

if (isset($_GET['test_name'])) {
    $test_name = $_GET['test_name'];
} else {
    $test_name = '';
}

if (isset($_GET['consultation_id'])) {
    $_GET['consultation_ID'] = $_GET['consultation_id'];
}

if (isset($_GET['Registration_ID'])) {
    $consultation_id = $_GET['Registration_ID'];
}

if (isset($_GET['sponsor_id'])) {
    $Sponsor_ID = $_GET['sponsor_id'];
}


?>

<table width='100%' class='thead'>
    <tr class='thead'>
        <td style='width: 3%'><b>Click</b></td>
        <td><b>Item</b></td>
    </tr>
    <?php
    $Item_Category_ID = 'All';
    $Item_Subcategory_ID = 'All';
    if (isset($_GET['Item_Category_ID'])) {
        $Item_Category_ID = $_GET['Item_Category_ID'];
    }if (isset($_GET['Item_Category_ID'])) {
        $Item_Subcategory_ID = $_GET['Item_Subcategory_ID'];
    }

    $i = 1;

    $qr = "SELECT * FROM tbl_items i INNER JOIN tbl_item_price ip ON i.Item_ID=ip.Item_ID where $Consultation_Condition2 ";
    //die($qr);
    if ($Item_Subcategory_ID == 'All' || $Item_Subcategory_ID == 0) {
        if ($Item_Category_ID == 'All') {
            $category_condition = "";
        } else {
            $category_condition = " AND Item_Subcategory_ID IN (
			    SELECT DISTINCT ics77.Item_Subcategory_ID FROM tbl_item_subcategory ics77,tbl_item_category ic77
			    WHERE ic77.Item_Category_ID = $Item_Category_ID AND ic77.Item_Category_ID = ics77.Item_Category_ID )";
        }
    } else {
        $category_condition = " AND Item_Subcategory_ID = $Item_Subcategory_ID";
    }

    $search_name = '';
    if (!empty($test_name)) {
        $search_name = " AND Product_Name LIKE '%$test_name%' ";
    }

    $qr .= $category_condition;
    $qr .= " AND Status='Available' AND Can_Be_Sold='yes' AND Ct_Scan_Item='no' AND Ward_Round_Item='no' $search_name ";

	$sponsor_item_filter = '';
//if (isset($_GET['Patient_Payment_ID'])) {
//    $Patient_Payment_ID= $_GET['Patient_Payment_ID'];
//    $sp_query = mysqli_query($conn,"SELECT Guarantor_name,sp.Sponsor_ID,item_update_api,auto_item_update_api FROM tbl_sponsor sp 
//	JOIN tbl_patient_payments p ON p.Sponsor_ID=sp.Sponsor_ID
//	WHERE p.Patient_Payment_ID='" . $Patient_Payment_ID . "'") or die(mysqli_error($conn));
//
//    if (mysqli_num_rows($sp_query) > 0) {
//        $rowSp = mysqli_fetch_assoc($sp_query);
//        $Guarantor_name = $rowSp['Guarantor_name'];
//        $Sponsor_ID = $rowSp['Sponsor_ID'];
//        $auto_item_update_api = $rowSp['auto_item_update_api'];
//
//        if ($auto_item_update_api == '1') {
//            $qr .= " AND sponsor_id='$Sponsor_ID'";
//        }
//    }
//}
$qr .= " AND ip.sponsor_id='$Sponsor_ID'";
$qr .=" ORDER BY Product_Name LIMIT 20";

//echo $qr;exit;
   
    $result = mysqli_query($conn,$qr);
    if (mysqli_num_rows($result) > 0) {
        if($Consultation_Type == 'Laboratory'){
            ////////////////////////
            
                   while ($row = mysqli_fetch_assoc($result)) {
            $sqlcheck = "SELECT sponsor_id,item_ID FROM tbl_sponsor_non_supported_items WHERE sponsor_id = '$Sponsor_ID' AND item_ID=" . $row['Item_ID'] . "";
            $check_if_covered = mysqli_query($conn,$sqlcheck) or die(mysqli_error($conn));
            $isfound = '';
            $isallowed = '';
            if (mysqli_num_rows($check_if_covered) > 0) {
                $isfound = "<input type='hidden' id='item_supported_" . $row['Item_ID'] . "' value='no'>";
            } else {
                $isfound = "<input type='hidden' id='item_supported_" . $row['Item_ID'] . "' value='yes'>";
            }

            $sqlcheck2 = "SELECT sponsor_id,item_ID FROM tbl_sponsor_allow_zero_items WHERE sponsor_id = '$Sponsor_ID' AND item_ID=" . $row['Item_ID'] . "";
            $check_if_covered2 = mysqli_query($conn,$sqlcheck2) or die(mysqli_error($conn));

            if (mysqli_num_rows($check_if_covered2) > 0) {
                $isallowed = "<input type='hidden' id='item_allow_zero_" . $row['Item_ID'] . "' value='yes'>";
            } else {
                $isallowed = "<input type='hidden' id='item_allow_zero_" . $row['Item_ID'] . "' value='no'>";
            }

            //check if can add


            if ($Consultation_Type == 'Others') {
                $select_payment_cache = "SELECT Status FROM tbl_payment_cache pc,tbl_item_list_cache ilc
				WHERE  pc.Payment_Cache_ID = ilc.Payment_Cache_ID AND 
				Check_In_Type='$Consultation_Type'  AND
                                ilc.Item_ID='" . $row['Item_ID'] . "' AND 
                                pc.consultation_ID = '" . $_GET['consultation_ID'] . "' AND 
                                Registration_ID='" . $_GET['Registration_ID'] . "' AND
                                Status  IN ('active','notsaved') AND pc.Billing_Type IN ('Outpatient Credit','Outpatient Cash')    
				";
            } else {
                $select_payment_cache = "SELECT Status FROM tbl_payment_cache pc,tbl_item_list_cache ilc
				WHERE  pc.Payment_Cache_ID = ilc.Payment_Cache_ID AND 
				Check_In_Type='$Consultation_Type'  AND
                                ilc.Item_ID='" . $row['Item_ID'] . "' AND 
                                pc.consultation_ID = '" . $_GET['consultation_ID'] . "' AND 
                                Registration_ID='" . $_GET['Registration_ID'] . "' AND
                                Status IN ('active','paid','notsaved') AND pc.Billing_Type IN ('Outpatient Credit','Outpatient Cash')    
				";
            }

            //die($select_payment_cache);
            $result_check = mysqli_query($conn,$select_payment_cache) or die(mysqli_error($conn));
            $mystatust = 'false';
            if (mysqli_num_rows($result_check) > 0) {
                $mystatust = 'true';
            }
            ?>
    
            <tr>
                <!--<td style='text-align: center;vertical-align: middle;color: black'><?php //echo $i;  ?></td>-->
                <td>
                       <!-- <input type='radio' onclick="sendOrRemove('<?php //echo $row['Item_ID'];   ?>',this)">-->
                       
                    <input type="hidden" name="Sponsor_ID" id="Sponsor_ID" value="<?=$Sponsor_ID?>"><!-- added on 18/02/2018 @Mfoy dn -->
                    <input type='checkbox' name='selection' id='<?php echo $row['Item_ID']; ?>' value='<?php echo $row['Product_Name']; ?>' onclick="alert_items_control('<?php echo $row['Item_ID'] ?>', <?php echo $mystatust ?>);">
                    <!-- <input type='checkbox' name='selection' id='<?php //echo $row['Item_ID']; ?>' value='<?php //echo $row['Product_Name']; ?>' onclick="multiple_add_items('<?php //echo $row['Item_ID'] ?>', <?php //echo $mystatust ?>);"> -->
                    <?php echo $isfound . $isallowed ?>
                </td>
                <td class="itemhover" >
                    <label class="itemhoverlabl" for="<?php echo $row['Item_ID']; ?>">
                        <?php echo $row['Product_Name']; ?>
                    </label>
                </td>
            </tr>
                <!--<a href="./doctoritemselect.php?Consultation_Type=<?php //echo $Consultation_Type;   ?>&consultation_id=<?php //echo $consultation_id;   ?>&Registration_ID=<?php //echo $Registration_ID;   ?>&Patient_Payment_Item_List_ID=<?php //echo $_GET['Patient_Payment_Item_List_ID'];   ?>&Patient_Payment_ID=<?php //echo $_GET['Patient_Payment_ID'];   ?>&Item_ID=<?php //echo $row['Item_ID'];   ?>&SearchListDoctorsPagePatientBilling=SearchListDirectCashPatientBillingThisPage" style='text-decoration: none;' target='_parent'><?php //echo $row['Product_Name'];   ?></a>-->
            <?php
            $i++;
            }
            //////////////////
        }else{
                    while ($row = mysqli_fetch_assoc($result)) {
            $sqlcheck = "SELECT sponsor_id,item_ID FROM tbl_sponsor_non_supported_items WHERE sponsor_id = '$Sponsor_ID' AND item_ID=" . $row['Item_ID'] . "";
            $check_if_covered = mysqli_query($conn,$sqlcheck) or die(mysqli_error($conn));
            $isfound = '';
            $isallowed = '';
            if (mysqli_num_rows($check_if_covered) > 0) {
                $isfound = "<input type='hidden' id='item_supported_" . $row['Item_ID'] . "' value='no'>";
            } else {
                $isfound = "<input type='hidden' id='item_supported_" . $row['Item_ID'] . "' value='yes'>";
            }

            $sqlcheck2 = "SELECT sponsor_id,item_ID FROM tbl_sponsor_allow_zero_items WHERE sponsor_id = '$Sponsor_ID' AND item_ID=" . $row['Item_ID'] . "";
            $check_if_covered2 = mysqli_query($conn,$sqlcheck2) or die(mysqli_error($conn));

            if (mysqli_num_rows($check_if_covered2) > 0) {
                $isallowed = "<input type='hidden' id='item_allow_zero_" . $row['Item_ID'] . "' value='yes'>";
            } else {
                $isallowed = "<input type='hidden' id='item_allow_zero_" . $row['Item_ID'] . "' value='no'>";
            }

            //check if can add


            if ($Consultation_Type == 'Others') {
                $select_payment_cache = "SELECT Status FROM tbl_payment_cache pc,tbl_item_list_cache ilc
				WHERE  pc.Payment_Cache_ID = ilc.Payment_Cache_ID AND 
				Check_In_Type='$Consultation_Type'  AND
                                ilc.Item_ID='" . $row['Item_ID'] . "' AND 
                                pc.consultation_ID = '" . $_GET['consultation_ID'] . "' AND 
                                Registration_ID='" . $_GET['Registration_ID'] . "' AND
                                Status  IN ('active','notsaved') AND pc.Billing_Type IN ('Outpatient Credit','Outpatient Cash')    
				";
            } else {
                $select_payment_cache = "SELECT Status FROM tbl_payment_cache pc,tbl_item_list_cache ilc
				WHERE  pc.Payment_Cache_ID = ilc.Payment_Cache_ID AND 
				Check_In_Type='$Consultation_Type'  AND
                                ilc.Item_ID='" . $row['Item_ID'] . "' AND 
                                pc.consultation_ID = '" . $_GET['consultation_ID'] . "' AND 
                                Registration_ID='" . $_GET['Registration_ID'] . "' AND
                                Status IN ('active','paid','notsaved') AND pc.Billing_Type IN ('Outpatient Credit','Outpatient Cash')    
				";
            }

            //die($select_payment_cache);
            $result_check = mysqli_query($conn,$select_payment_cache) or die(mysqli_error($conn));
            $mystatust = 'false';
            if (mysqli_num_rows($result_check) > 0) {
                $mystatust = 'true';
            }
            ?>
    
            <tr>
                <!--<td style='text-align: center;vertical-align: middle;color: black'><?php //echo $i;  ?></td>-->
                <td>
                       <!-- <input type='radio' onclick="sendOrRemove('<?php //echo $row['Item_ID'];   ?>',this)">-->
                    <input type='radio' name='selection' id='<?php echo $row['Item_ID']; ?>' value='<?php echo $row['Product_Name']; ?>' onclick="check_provisional_diagnosis('<?php echo $row['Item_ID'] ?>', <?php echo $mystatust ?>);">
                    <?php echo $isfound . $isallowed ?>
                </td>
                <td class="itemhover" >
                    <label class="itemhoverlabl" for="<?php echo $row['Item_ID']; ?>">
                        <?php echo $row['Product_Name']; ?>
                    </label>
                </td>
            </tr>
                <!--<a href="./doctoritemselect.php?Consultation_Type=<?php //echo $Consultation_Type;   ?>&consultation_id=<?php //echo $consultation_id;   ?>&Registration_ID=<?php //echo $Registration_ID;   ?>&Patient_Payment_Item_List_ID=<?php //echo $_GET['Patient_Payment_Item_List_ID'];   ?>&Patient_Payment_ID=<?php //echo $_GET['Patient_Payment_ID'];   ?>&Item_ID=<?php //echo $row['Item_ID'];   ?>&SearchListDoctorsPagePatientBilling=SearchListDirectCashPatientBillingThisPage" style='text-decoration: none;' target='_parent'><?php //echo $row['Product_Name'];   ?></a>-->
            <?php
            $i++;
            }
        }
    } else {
        echo '<tr><td colspan="2" style="text-align:center;width:100%;font-size:20px;color:red;font-weight:bold;">
											  No Item Found
										  </td>
									</tr>';
    }
    ?>
</table>
