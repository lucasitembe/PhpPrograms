<link rel="stylesheet" href="table33.css" media="screen"> 
<?php
include("./includes/connection.php");
@session_start();
$employee_ID = $_SESSION['userinfo']['Employee_ID'];

$Item_ID = 0;

$consultation_id = (isset($_GET['consultation_id']) ? $_GET['consultation_id'] : $_GET['consultation_ID']);

$filter="pc.consultation_id = $consultation_id";
if (empty($consultation_id) || $consultation_id == 0 || $consultation_id == null || $consultation_id == 'NULL' ) {
     $filter="pc.consultation_id IS NULL";
}

if (isset($_GET['External_Payment_Cache_ID']) && !empty ($_GET['External_Payment_Cache_ID'])) {
    $filter .="  AND pc.Payment_Cache_ID='".$_GET['External_Payment_Cache_ID']."'";
}

if (isset($_GET['Payment_Cache_ID'])) {
    $Payment_Cache_ID = $_GET['Payment_Cache_ID'];
    $payment_cache_ID = $Payment_Cache_ID;
}

if (isset($_GET['Consultation_Type'])) {
    $Consultation_Type = $_GET['Consultation_Type'];
} else {
    $Consultation_Type = 0;
}
if (isset($_GET['Registration_ID'])) {
    $Registration_ID = $_GET['Registration_ID'];
} else {
    $Registration_ID = 0;
}

if (isset($_GET['Patient_Payment_Item_List_ID'])) {
    $Patient_Payment_Item_List_ID = $_GET['Patient_Payment_Item_List_ID'];
} else {
    $Patient_Payment_Item_List_ID = 0;
}

//echo $consultation_id.' '.$Consultation_Type;exit;
?>

<table width='100%'>
    <tr id='thead'>
        <td style='width: 1%'><b>SN</b></td>
        <td><b>Description</b></td>
        <?php if (strtolower($Consultation_Type) == "pharmacy") { ?>
            <td width='35%'><b>Dosage</b></td>
        <?php } else { ?>
            <td width='35%'><b>Comments</b></td>
        <?php }
        ?>
        <?php if (strtolower($Consultation_Type) == "procedure") { ?>
            <td ><b>To be performed by</b></td>
        <?php }
        ?>

        <?php //if(strtolower($Consultation_Type) == "pharmacy"){ ?>
        <td ><b>QTY</b></td>
        <?php // }
        ?>
        <?php if (strtolower($Consultation_Type) == "pharmacy") { ?>
               <!-- <td ><b>Balance</b></td>-->
        <?php } ?>
        <td align='right'><b>Cash</b></td>
        <td align='right'><b>Credit</b></td>
        <td align='center'><b>Action</b></td>
    </tr>
    <?php
    
    $qr = "SELECT * FROM tbl_item_list_cache il,tbl_payment_cache pc,tbl_items it
		    WHERE pc.Payment_Cache_ID=il.Payment_Cache_ID AND
		    $filter
                    AND il.Item_ID = it.Item_ID AND il.Check_In_Type ='$Consultation_Type' AND il.Consultant_ID=$employee_ID ORDER BY il.Payment_Item_Cache_List_ID ASC";
   // echo $qr;exit;
//     $employee_ID=$_SESSION['userinfo']['Employee_ID'];
//    $qr = "SELECT * FROM tbl_item_list_cache il,tbl_payment_cache pc,tbl_items it
//		    WHERE pc.Payment_Cache_ID=il.Payment_Cache_ID AND
//		    pc.consultation_id = $consultation_id
//                    AND il.Item_ID = it.Item_ID AND il.Check_In_Type ='$Consultation_Type' ORDER BY il.Payment_Item_Cache_List_ID ASC";
//   

    $result = mysqli_query($conn,$qr) or die(mysqli_error($conn));
    $i = 1;
    $Cashsum = 0;
    $Creditsum = 0;
    $CashSubTotal = 0;
    $CreditSubTotal = 0;
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_array($result)) {

            if ($row['Transaction_Type'] == 'Cash' || $row['Transaction_Type'] == 'Outpatient Cash') {
                $Cash = ($row['Price'] - $row['Discount']);
                $Cashsum += ($row['Price'] - $row['Discount']) * $row['Quantity'];
                $Credit = 0;
            } else {
                $Credit = $row['Price'];
                $Creditsum += $row['Price'] * $row['Quantity'];
                $Cash = 0;
            }
            ?>
            <tr>
                <td style="1px solid #ccc;"><b><?php echo $i; ?></b></td>
                <td style="1px solid #ccc;"><?php echo $row['Product_Name']; ?>
                    <?php
                    $Sub_Department_ID = $row['Sub_Department_ID'];
                    $select_sub_category_name = mysqli_query($conn,"SELECT Sub_Department_Name FROM tbl_sub_department WHERE Sub_Department_ID = '$Sub_Department_ID' ");
                    while ($namerow = mysqli_fetch_array($select_sub_category_name)) {
                        echo "(<b>" . $Sub_Department_Name = $namerow['Sub_Department_Name'] . "</b>)";
                    }

                    $dcm = $row['Doctor_Comment'];
                    ?>
                </td>

                <td style="1px solid #ccc;">
                    <textarea style="height:35px" rows="" oninput="updateDoctorItemCOmment('<?php echo $row['Payment_Item_Cache_List_ID']; ?>',this.value)"><?php echo $dcm; ?></textarea>
                </td>
                <?php
                if (strtolower($Consultation_Type) == "procedure") {
                    $localproc = '';
                    if (empty($row['Procedure_Location']) || $row['Procedure_Location'] == null || $row['Procedure_Location'] == 'Others') {
                        $localproc = 'Others';
                    } else {
                        $localproc = 'Me';
                    }
                    ?>
                    <td ><?php echo $localproc; ?></b></td>
                <?php }
                ?>
        <!--<td><?php //echo $row['Quantity'];?>&nbsp;</td>-->
                <?php //if(strtolower($Consultation_Type) == "pharmacy"){  ?>
                <td style="1px solid #ccc;"><?php echo $row['Quantity'] ?></td>
                <?php //}
                ?>
                <td align='right' style="text-align: right;border:1px solid #ccc;" ><?php
        if ($Cash != 0) {
            echo (($_SESSION['systeminfo']['price_precision'] == 'yes') ? number_format($Cash, 2) : number_format($Cash));
        } else {
            echo (($_SESSION['systeminfo']['price_precision'] == 'yes') ? number_format(0, 2) : number_format(0));
        }
                ?></td>
                <td align='right' style="text-align: right;border:1px solid #ccc;"><?php
                    if ($Credit != 0) {
                        echo (($_SESSION['systeminfo']['price_precision'] == 'yes') ? number_format($Credit, 2) : number_format($Credit));
                    } else {
                        echo (($_SESSION['systeminfo']['price_precision'] == 'yes') ? number_format(0, 2) : number_format(0));
                    }
                    ?></td>
                <td align='center' width='2%' style="border:1px solid #ccc;">
                    <?php
                    if ($row['Status'] != 'paid') {
                        ?>
                        <input style="border:1px solid #ccc;" type='button' value='Remove' onclick="check_item('<?php echo $row['Payment_Item_Cache_List_ID'] ?>', '<?php echo $row['Payment_Cache_ID'] ?>')">
            <?php } else {
                echo '&nbsp';
                ?>
                    </td>
                </tr>
                <?php
            }

            if (mysqli_num_rows($result) == 0) {
                ?>
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <?php
            }
            $i++;
        }
        ?>
        <tr>

            <?php
            if (strtolower($Consultation_Type) == "laboratory" || strtolower($Consultation_Type) == "radiology" || strtolower($Consultation_Type) == "surgery") {
                echo " <td colspan='4' ><center><b>TOTAL</b></center></td>";
            } else {
                ?>	 
                <td colspan='4' ><center><b>TOTAL</b></center></td>
        <?php
    }
} else {
    ?>
    <td colspan='3' ><center><b>TOTAL</b></center></td>
    <?php
}
?>	    
<td align='right' style="text-align: right"><b><?php echo (($_SESSION['systeminfo']['price_precision'] == 'yes') ? number_format($Cashsum, 2) : number_format($Cashsum)); ?></b></td>
<td align='right' style="text-align: right"><b><?php echo (($_SESSION['systeminfo']['price_precision'] == 'yes') ? number_format($Creditsum, 2) : number_format($Creditsum)); ?></b></td>
<td align='center'><b><?php echo (($_SESSION['systeminfo']['price_precision'] == 'yes') ? number_format($Cashsum + $Creditsum, 2) : number_format($Cashsum + $Creditsum)) . '  ' . $_SESSION['hospcurrency']['currency_code'] . '&nbsp;&nbsp;'; ?></b></td>
</tr>
</table>