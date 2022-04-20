<?php
include("./includes/connection.php");
include("./includes/header.php");
//$Process_Title = '';
$controlforminput = '';
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}

if (isset($_SESSION['userinfo']['Setup_And_Configuration'])) {
    if ($_SESSION['userinfo']['Setup_And_Configuration'] != 'yes') {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
?>


<?php
//select systemconfiguration based on branch
if (isset($_SESSION['userinfo']['Branch_ID'])) {
    $Branch_ID = $_SESSION['userinfo']['Branch_ID'];
} else {
    $Branch_ID = 0;
}
$select_system_configuration = mysqli_query($conn,"select Centralized_Collection,Departmental_Collection,Mobile_Payment,Imbalance_Discharge,Inpatient_Prepaid,price_precision,c.currency_id,currency_name,currency_code,enable_zeroing_price from tbl_system_configuration s left join tbl_currency c ON c.currency_id=s.currency_id  where Branch_ID = '$Branch_ID'") or die(mysqli_error($conn));
while ($row = mysqli_fetch_array($select_system_configuration)) {
    $Centralized_Collection = $row['Centralized_Collection'];
    $Departmental_Collection = $row['Departmental_Collection'];
    $Mobile_Payment = $row['Mobile_Payment'];
    $Imbalance_Discharge = $row['Imbalance_Discharge'];
    $Inpatient_Prepaid = $row['Inpatient_Prepaid'];
    $price_precision = $row['price_precision'];//
    $currency_id = $row['currency_id'];
    $enable_zeroing_price = $row['enable_zeroing_price'];
}
?>
<br/><br/><br/><br/><br/><br/>

<?php
$control_Check_boxes = 'no';
if (isset($_POST['SubmittedEditRevenueCenterConfForm'])) {
    $Centralized_Collection = 'no';
    $Departmental_Collection = 'no';
    $Mobile_Payment = 'no';
    $Imbalance_Discharge = 'no';
    $Inpatient_Prepaid = 'yes';
    $price_precision = 'no';
    $enable_zeroing_price='no';
    $currency=  mysqli_real_escape_string($conn,$_POST['currency']);

    if (isset($_POST['Centralized_Collection'])) {
        $Centralized_Collection = 'yes';
        $control_Check_boxes = 'yes';
    }

    if (isset($_POST['Departmental_Collection'])) {
        $Departmental_Collection = 'yes';
        $control_Check_boxes = 'yes';
    }

    if (isset($_POST['Mobile_Payment'])) {
        $Mobile_Payment = 'yes';
    }

    if (isset($_POST['Imbalance_Discharge'])) {
        $Imbalance_Discharge = 'yes';
    }

    if (isset($_POST['Inpatient_Prepaid'])) {
        $Inpatient_Prepaid = 'yes';
    }

    if (isset($_POST['price_precision'])) {
        $price_precision = 'yes';
    }
    if (isset($_POST['enable_zeroing_price'])) {
        $enable_zeroing_price = 'yes';
    }

    if ($control_Check_boxes == 'yes') {
        $update_query = "update tbl_system_configuration set
						Centralized_Collection = '$Centralized_Collection', Departmental_Collection = '$Departmental_Collection',
						Mobile_Payment = '$Mobile_Payment', Imbalance_Discharge = '$Imbalance_Discharge', Inpatient_Prepaid = '$Inpatient_Prepaid', price_precision = '$price_precision', currency_id = '$currency', enable_zeroing_price='$enable_zeroing_price'
		    		    where branch_id = '$Branch_ID'";
        if (!mysqli_query($conn,$update_query)) {
            echo '<script>
			alert("Process Fail! Please Please Try Again");
			</script>';
        } else {
            echo '<script>
			alert("Configuration Updated Successful");
			document.location = "./revenuecenterconfigurationpage.php?RevenueCenterConfiguration=RevenueCenterConfigurationThisPage";
			</script>';
        }
    } else {
        echo '<script>
			alert("You must select at least one Collection Mode (Either Centralized, Departmental or Both)");
			</script>';
    }
}

$curreny = mysqli_query($conn,"SELECT * FROM tbl_currency") or die(mysqli_error($conn));
$data = "<select name='currency' id='currency'>";
while ($rowCurr = mysqli_fetch_array($curreny)) {

    if ($currency_id == $rowCurr['currency_id']) {
        $data .="<option value='" . $rowCurr['currency_id'] . "' selected='selected'>" . $rowCurr['currency_name'] . "</option>";
    } else {
        $data .="<option value='" . $rowCurr['currency_id'] . "'>" . $rowCurr['currency_name'] . "</option>";
    }
}
$data .="</select>";
?>
</span>
<form action='#' method='post' name='myForm' id='myForm' enctype="multipart/form-data"> 
    <fieldset>  
        <legend align=center><b>EDIT REVENUE CENTER CONFIGURATION</b></legend>
        <center><table width = 90%>
                <tr>
                    <td>
                        <input type='checkbox' name='Centralized_Collection' title='Centralized Collection' id='Centralized_Collection' value='yes' <?php
if (strtolower($Centralized_Collection) == 'yes') {
    echo 'checked="checked"';
}
?>><label for="Centralized_Collection"><b>Centralized Collection</b></label>
                    </td> 
                    <td>
                        <input type='checkbox' name='Departmental_Collection' title='Departmental Collection' id='Departmental_Collection' value='yes' <?php
                               if (strtolower($Departmental_Collection) == 'yes') {
                                   echo 'checked="checked"';
                               }
                               ?>><label for="Departmental_Collection"><b>Departmental Collection</b></label>
                    </td>
                    <td>
                        <input type='checkbox' name='Mobile_Payment' title='Departmental Collection' id='Mobile_Payment' value='yes' <?php
                               if (strtolower($Mobile_Payment) == 'yes') {
                                   echo 'checked="checked"';
                               }
                               ?>><label for="Mobile_Payment"><b>ePayment Collection</b></label>
                    </td> 
                    <td>
                        <input type='checkbox' name='Imbalance_Discharge' title='Imbalance Payments Discharge' id='Imbalance_Discharge' value='yes' <?php
                        if (strtolower($Imbalance_Discharge) == 'yes') {
                            echo 'checked="checked"';
                        }
                        ?>><label for="Imbalance_Discharge"><b>Imbalance Payments Discharge</b></label>
                    </td>
                </tr>
                <tr>
                    <td>
                        <input type='checkbox' name='Inpatient_Prepaid' title='Inpatient Prepaid' id='Inpatient_Prepaid' value='yes' <?php
                        if (strtolower($Inpatient_Prepaid) == 'yes') {
                            echo 'checked="checked"';
                        }
                        ?>><label for="Inpatient_Prepaid"><b>Allow Inpatient Prepaid</b></label>
                    </td>
                    <td>
                        <input type='checkbox' name='price_precision' title='Price Precision' id='price_precision' value='yes' <?php
                        if (strtolower($price_precision) == 'yes') {
                            echo 'checked="checked"';
                        }
                        ?>><label for="Inpatient_Prepaid"><b>Allow Price Precision</b></label>
                    </td>
                    <td style="text-align: center;">
                        <strong> Currency:</strong> <b><?php echo $data; ?></b>
                    </td>
                     <td>
                        <input type='checkbox' name='enable_zeroing_price' title='Price Precision' id='enable_zeroing_price' value='yes' <?php
                        if (strtolower($enable_zeroing_price) == 'yes') {
                            echo 'checked="checked"';
                        }
                        ?>><label for="enable_zeroing_price"><b>Enable Billing Item To Zero Price</b></label>
                    </td>
                </tr>
                <tr>
                    <td colspan="5" style='text-align: center;'>
                        <input type='submit' name='submit' id='submit' value='   UPDATE   ' class='art-button-green'>
                        <a href='revenuecenterconfigurationpage.php?RevenueCenterConfiguration=RevenueCenterConfigurationThisPage' class='art-button-green'>CANCEL</a>
                        <input type='hidden' name='SubmittedEditRevenueCenterConfForm' value='true'/> 
                    </td>
                </tr>
            </table>
        </center>
    </fieldset>
</form>
<br/>

<?php
include("./includes/footer.php");
?>