<?php
session_start();
include("./includes/connection.php");

if (isset($_SESSION['userinfo']['Employee_ID'])) {
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
} else {
    $Employee_ID = 0;
}

if (isset($_GET['Registration_ID'])) {
    $Registration_ID = $_GET['Registration_ID'];
} else {
    $Registration_ID = 0;
}

/* if(isset($_SESSION['Payment_Cache_ID'])){
  $Payment_Cache_ID = $_SESSION['Payment_Cache_ID'];
  }else{
  $Payment_Cache_ID = '';
  }

  if(isset($_SESSION['Sub_Department_ID'])){
  $Sub_Department_ID = $_SESSION['Sub_Department_ID'];
  }else{
  $Sub_Department_ID = '';
  } */

if (isset($_GET['Sub_Department_ID'])) {
    $Sub_Department_ID = $_GET['Sub_Department_ID'];
}

if (isset($_GET['Payment_Cache_ID'])) {
    $Payment_Cache_ID = $_GET['Payment_Cache_ID'];
} else {
    $Payment_Cache_ID = '';
}


if (isset($_GET['Section'])) {
    $Section = $_GET['Section'];
} else {
    $Section = '';
}

//src=
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


//get patient details
$select = mysqli_query($conn,"select Patient_Name, Guarantor_Name, pr.Phone_Number, pr.Gender from tbl_patient_registration pr, tbl_sponsor sp where
							pr.Sponsor_ID = sp.Sponsor_ID and
							pr.Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
$num = mysqli_num_rows($select);
if ($num > 0) {
    while ($data = mysqli_fetch_array($select)) {
        $Patient_Name = $data['Patient_Name'];
        $Guarantor_Name = $data['Guarantor_Name'];
        $Phone_Number = $data['Phone_Number'];
        $Gender = $data['Gender'];
    }
} else {
    $Patient_Name = '';
    $Guarantor_Name = '';
    $Phone_Number = '';
    $Gender = '';
}
?>

<?php
//calculate total
//$get_total = mysqli_query($conn,"select sum((Price - Discount) * Quantity) as Amount from tbl_reception_items_list_cache where Employee_ID = '$Employee_ID' and Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
$Amount = 0;
if ($Section == 'Pharmacy') {
    $Status = 'approved';
} else {
    $Status = 'active';
}

if (strtolower($_SESSION['systeminfo']['Show_Pharmaceutical_Before_Payments']) == 'no') {
        $ph = "select ilc.Price, ilc.Discount, ilc.Quantity, ilc.Edited_Quantity
                                                                            from tbl_item_list_cache ilc where ilc.status = '$Status' and
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
    $get_total = mysqli_query($conn,$ph) or die(mysqli_error($conn));
} else {

    if (strtolower($Section) == 'pharmacy') {
        $ph = "select ilc.Price, ilc.Discount, ilc.Quantity, ilc.Edited_Quantity
									from tbl_item_list_cache ilc where (ilc.status = 'approved' or ilc.status = 'active') and
									ilc.Payment_Cache_ID = '$Payment_Cache_ID' and
									
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
        $get_total = mysqli_query($conn,$ph) or die(mysqli_error($conn));
    } else {
        $get_total = mysqli_query($conn,"select ilc.Price, ilc.Discount, ilc.Quantity, ilc.Edited_Quantity
									from tbl_item_list_cache ilc where ilc.status = '$Status' and
									ilc.Payment_Cache_ID = '$Payment_Cache_ID' and
									
									ilc.Transaction_Type = 'Cash' and
									ilc.Check_In_Type = '$Section' and
									ilc.ePayment_Status = 'pending'") or die(mysqli_error($conn));
    }
}

$nums = mysqli_num_rows($get_total);

if ($nums > 0) {
    while ($data = mysqli_fetch_array($get_total)) {
        //get quantity
        $Qty = 0;
        if ($data['Edited_Quantity'] > 0) {
            $Qty = $data['Edited_Quantity'];
        } else {
            $Qty = $data['Quantity'];
        }

        $Amount += (($data['Price'] - $data['Discount']) * $Qty);
    }
}

$Amount = number_format($Amount);
?>

<fieldset style="background-color:white;">
    <table width="100%">
        <tr>
            <td width="18%" style="text-align: right;"><b>Patient Name</b></td>
            <td style="text-align: left;">
                <input type="text" value="<?php echo ucwords(strtolower($Patient_Name)); ?>" readonly="readonly">
            </td>
            <td width="20%" style="text-align: right;"><b>Sponsor</b></td>
            <td style="text-align: left; width: 30%;">
                <input type="text" value="<?php echo strtoupper($Guarantor_Name); ?>" readonly="readonly">
            </td>
        </tr>
        <tr>
            <td width="20%" style="text-align: right;"><b>Gender</b></td>
            <td style="text-align: left; width: 30%;">
                <input type="text" value="<?php echo $Gender; ?>" id='Gender' readonly="readonly">
            </td>
            <td width="18%" style="text-align: right;"><b>Phone Number</b></td>
            <td style="text-align: left;">
                <input type="text" name="Patient_Phone_Number" id="Patient_Phone_Number" value="<?php echo $Phone_Number; ?>" oninput="Update_Phone_Number()" onkeyup="Update_Phone_Number()">
            </td>
        </tr>
        <?php if ($nums > 0) { ?>
            <tr>
                <td style="text-align: right;"><b>Amount</b></td>
                <td style="text-align: left;">
                    <input type="text" name="Amount_Required" id="Amount_Required" value="<?php echo $Amount; ?>">
                </td>
                <td style="text-align: right;"><b>Payment Mode</b></td>
                <td>
                    <select name="Payment_Mode" id="Payment_Mode" onchange="check_payment_mode()">
                        <option value="Bank_Payment" selected="selected" >Bank Payment</option>
                        <option value="Mobile_Payemnt">Mobile Payment</option>
                        <!--<option value="government_payment_gateway">GPG</option>-->
                    </select>
                </td>
            </tr>
        <?php } ?>
    </table>
</fieldset>
<fieldset style="background-color:white;">
    <table width="100%">
        <?php if ($nums > 0) { ?>
            <tr>
                <td style="text-align: right;" width="100%">
                    <input type="button" value="CREATE eBILL" class="art-button-green" onclick="Confirm_Create_ePayment_Bill();">
                </td>
            </tr>
            <?php
        } else {
            //get all transaction codes based on Payment_Cache_ID
            if (strtolower($_SESSION['systeminfo']['Show_Pharmaceutical_Before_Payments']) == 'yes') {
                $select = mysqli_query($conn,"select Transaction_ID
											from tbl_item_list_cache ilc where ilc.status = '$Status' and
											ilc.Payment_Cache_ID = '$Payment_Cache_ID' and
											ilc.Sub_Department_ID = '$Sub_Department_ID' and
											ilc.Transaction_Type = 'Cash' and
											ilc.Check_In_Type = '$Section' and
											ilc.ePayment_Status = 'Served' group by Transaction_ID") or die(mysqli_error($conn));
            } else {
                if (strtolower($Section) == 'pharmacy') {
                    $select = mysqli_query($conn,"select Transaction_ID
											from tbl_item_list_cache ilc where (ilc.status = 'approved' or ilc.status = 'active') and
											ilc.Payment_Cache_ID = '$Payment_Cache_ID' and
											ilc.Sub_Department_ID = '$Sub_Department_ID' and
											ilc.Transaction_Type = 'Cash' and
											ilc.Check_In_Type = '$Section' and
											ilc.ePayment_Status = 'Served' group by Transaction_ID") or die(mysqli_error($conn));
                } else {
                    $select = mysqli_query($conn,"select Transaction_ID
											from tbl_item_list_cache ilc where ilc.status = '$Status' and
											ilc.Payment_Cache_ID = '$Payment_Cache_ID' and
											ilc.Sub_Department_ID = '$Sub_Department_ID' and
											ilc.Transaction_Type = 'Cash' and
											ilc.Check_In_Type = '$Section' and
											ilc.ePayment_Status = 'Served' group by Transaction_ID") or die(mysqli_error($conn));
                }
            }
            $no = mysqli_num_rows($select);
            if ($no > 0) {
                while ($data = mysqli_fetch_array($select)) {
                    //get details
                    $Transaction_ID = $data['Transaction_ID'];
                    $get_details = mysqli_query($conn,"select Payment_Code, Amount_Required from tbl_bank_transaction_cache where Transaction_ID = '$Transaction_ID'") or die(mysqli_error($conn));
                    $nm = mysqli_num_rows($get_details);
                    if ($nm > 0) {
                        while ($rw = mysqli_fetch_array($get_details)) {
                            ?>
                            <tr><td colspan="5" style="text-align: center;"><b><span style="color: #037CB0;"><br/>ePayment TRANSACTION ALREADY CREATED</span></b></td></tr>
                            <tr>
                                <td style="text-align: right;" width="20%">
                                    <b>Payment Code</b>
                                </td>
                                <td style="text-align: left;" width="15%">
                                    <?php echo $rw['Payment_Code']; ?>
                                </td>
                                <td style="text-align: right;" width="20%">
                                    <b>Amount Required</b>
                                </td>
                                <td style="text-align: left;" width="15%">
                                    <?php echo number_format($rw['Amount_Required']); ?>
                                </td>
                                <td style="text-align: left;">
                                    <input type="button" name="Preview" id="Preview" value="PRINT PAYMENT CODE" class="art-button-green" onclick="Print_Payment_Code('<?php echo $rw['Payment_Code']; ?>')">
                                </td>
                            </tr>
                            <?php
                        }
                    }
                }
            }
        }
        ?>
    </table>
</fieldset>

