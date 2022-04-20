<?php
include("./includes/header.php");
include("./includes/connection.php");

if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Storage_And_Supply_Work'])) {
        if ($_SESSION['userinfo']['Storage_And_Supply_Work'] != 'yes') {
            header("Location: ./index.php?InvalidPrivilege=yes");
        }if ($_SESSION['userinfo']['can_edit'] != 'yes') {
           header("Location: ./previousopenbalances.php?Status=PreviousOpenBalances&OpenBalance=OpenBalanceThisPage");
        }
    } else {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}

$temp = 0;
if (isset($_GET['Grn_Open_Balance_ID'])) {
    $Grn_Open_Balance_ID = $_GET['Grn_Open_Balance_ID'];
} else {
    $Grn_Open_Balance_ID = '';
}

$canPakage = false;
$display = "style='display:none'";

if (isset($_SESSION['systeminfo']['enable_receive_by_package']) && $_SESSION['systeminfo']['enable_receive_by_package'] == 'yes') {
    $canPakage = true;
    $display = "";
}

$sql_select = mysqli_query($conn,"select gob.Grn_Open_Balance_ID, emp.Employee_Name, gob.Saved_Date_Time, gob.Grn_Open_Balance_Description, gob.Employee_ID, sd.Sub_Department_Name
								from tbl_grn_open_balance gob, tbl_employee emp, tbl_sub_department sd where
								emp.Employee_ID = gob.Supervisor_ID and 
								sd.Sub_Department_ID = gob.Sub_Department_ID and
								gob.Grn_Open_Balance_ID = '$Grn_Open_Balance_ID' and
								gob.Grn_Open_Balance_Status = 'saved' order by Grn_Open_Balance_ID desc limit 100") or die(mysqli_error($conn));
$num = mysqli_num_rows($sql_select);
if ($num > 0) {
    while ($data = mysqli_fetch_array($sql_select)) {
        //get employee prepared
        $Prep_Employee = $data['Employee_ID'];
        $sel = mysqli_query($conn,"select Employee_Name from tbl_employee where Employee_ID = '$Prep_Employee'") or die(mysqli_error($conn));
        $Pre_no = mysqli_num_rows($sel);
        if ($Pre_no > 0) {
            while ($dt = mysqli_fetch_array($sel)) {
                $Created_By = $dt['Employee_Name'];
            }
        } else {
            $Created_By = '';
        }

        $Saved_Date_Time = $data['Saved_Date_Time'];
        $Grn_Open_Balance_Description = $data['Grn_Open_Balance_Description'];
        $Employee_Name = $data['Employee_Name']; //supervisor name
        $Sub_Department_Name = $data['Sub_Department_Name'];
    }
} else {
    $Saved_Date_Time = '';
    $Grn_Open_Balance_Description = '';
    $Employee_Name = ''; //supervisor name
    $Sub_Department_Name = '';
}

  echo "<a href='previousopenbalances.php?Status=PreviousOpenBalances&OpenBalance=OpenBalanceThisPage' class='art-button-green'>BACK</a>";
?>

<style>
    table,tr,td{
        //border-collapse:collapse !important;
        border:none !important;
    }
    tr:hover{
        background-color:#eeeeee;
        cursor:pointer;
    }
</style>
<br/><br/>
<fieldset>
    <table width = 100% border=0>
        <tr><td colspan="7"><hr></td></tr>
        <tr>
            <td width=10% style="text-align: right;"><b>Grn Number ~ </b></td><td><?php echo $Grn_Open_Balance_ID; ?></td>
            <td width=13% style="text-align: right;"><b>Date ~ </b></td><td width="15%"><?php echo $Saved_Date_Time; ?></td>
            <td style="text-align: right;"><b>Supervisor Name ~ </b></td><td><?php echo $Employee_Name; ?></td>
        </tr>
        <tr><td colspan="7"><hr></td></tr>
        <tr>
            <td style="text-align: right;"><b>Prepared By ~ </b></td><td width="20%"><?php echo $Created_By; ?></td>
            <td style="text-align: right;"><b>Location ~ </b></td><td width="20%"><?php echo $Sub_Department_Name; ?></td>
            <td width=13% style="text-align: right;"><b>Grn Description ~ </b></td><td><?php echo $Grn_Open_Balance_Description; ?></td>
        </tr>
        <tr><td colspan="7"><hr></td></tr>
        <tr><td colspan="7" style="text-align: right;">
                <a href="Preview_Grn_Details_Report.php?Grn_Open_Balance_ID=<?php echo $Grn_Open_Balance_ID; ?>" target="_blank" class="art-button-green">PREVIEW REPORT</a>
                <!--<input type="button" name="P_Report" id="P_Report" value="PREVIEW REPORT" class="art-button-green" onclick="Preview_Report('')">-->
            </td></tr>
    </table>
</fieldset><br/>

<fieldset style='overflow-y: scroll; height: 320px; background-color: white;' id='Grn_Fieldset_List'>
    <table width="100%">
        <tr><td colspan="10"><hr></td></tr>
        <tr>
            <td width="5%"><b>SN</b></td>
            <td width="20%"><b>Item Name</b></td>
            <td width="10%" style="text-align: right;"><b>Previous Quantity</b></td>
            <td <?php echo $display ?> width="6%" style="text-align: right;"><b>Containers</b></td>
            <td <?php echo $display ?> width="8%" style="text-align: right;"><b>Items per Container</b></td>
            <td width="12%" style="text-align: center;"><b>Quantity</b></td>
            <td width="12%" style="text-align: center;"><b>Buying Price</b></td>
            <td width="9%" style="text-align: right;">&nbsp;&nbsp;&nbsp;<b>Manufacture Date</b></td>
            <td width="9%" style="text-align: right;"><b>Expire Date</b></td>
            <td width="10%" style="text-align: right;"><b>Amount</b></td>
        </tr>
        <tr><td colspan="10"><hr></td></tr>

        <?php
        $select = mysqli_query($conn,"select obi.Open_Balance_Item_ID,i.Product_Name, obi.Item_Quantity, obi.Buying_Price, obi.Manufacture_Date, obi.Expire_Date, ibh.Item_Balance, obi.Container_Qty, obi.Items_Per_Container
							from tbl_grn_open_balance_items obi, tbl_items i, tbl_items_balance_history ibh where 
							i.Item_ID = obi.Item_ID and
							ibh.Grn_Open_Balance_ID = obi.Grn_Open_Balance_ID and
							ibh.Item_ID = obi.Item_ID and
							obi.Grn_Open_Balance_ID = '$Grn_Open_Balance_ID'") or die(mysqli_error($conn));
        $num = mysqli_num_rows($select);
        $Amount = 0;
        $Grand_Total = 0;
        if ($num > 0) {
            while ($data = mysqli_fetch_array($select)) {
                ?>
                <tr>
                    <td width="5%"><?php echo ++$temp; ?></td>
                    <td><?php echo $data['Product_Name']; ?></td>
                    <td style="text-align: right;"><?php echo $data['Item_Balance']; ?></td>
                    <td  <?php echo $display ?>  style="text-align: right;"><?php echo $data['Container_Qty']; ?></td>
                    <td  <?php echo $display ?>  style="text-align: right;"><?php echo $data['Items_Per_Container']; ?></td>
                    <?php
                    echo "<td>"
                    . "<input type='text' name='Qty_Received[]'  orderid='" . $data['Open_Balance_Item_ID'] . "' edittype='qtyreceived'  class='numberonly qtychanged orderidqty_" . $data['Open_Balance_Item_ID'] . "'    id='Qty_Received_" . $data['Open_Balance_Item_ID'] . "' autocomplete='off' value='" . number_format($data['Item_Quantity']) . "' style='text-align: center;display:inline;margin:0;float:left;' >"
                    . "<img style='text-align: right;margin:0;float:right;display:none' src='images/ajax-loader-focus.gif' width='20' height='20' id='img_qty_" . $data['Open_Balance_Item_ID'] . "'>"
                    . "<input type='hidden'  id='cacheqty_" . $data['Open_Balance_Item_ID'] . "' value='" . $data['Item_Quantity'] . "' />  "
                    . "</td>";
                    ?>
                    <?php
                    echo "<td><input type='text' name='Buying_Price[]'  title='Edit this price' edittype='buyingprice'  orderid='" . $data['Open_Balance_Item_ID'] . "'  class='numberonly qtychanged orderidprc_" . $data['Open_Balance_Item_ID'] . "'  id='Buying_Price[]' autocomplete='off' value='"
                    . number_format($data['Buying_Price'],2) .
                    "' style='text-align: center;display:inline;margin:0;float:left;' >"
                    . "<img style='text-align: right;margin:0;float:right;display:none' src='images/ajax-loader-focus.gif' width='20' height='20' id='img_price_" . $data['Open_Balance_Item_ID'] . "'>"
                    . "<input type='hidden'  id='cacheprice_" . $data['Open_Balance_Item_ID'] . "' value='" . number_format($data['Buying_Price'],2) . "' />  "
                    . "</td>";
                    ?>
                    <td style="text-align: right;">&nbsp;&nbsp;&nbsp;<?php echo $data['Manufacture_Date']; ?></td>
                    <td style="text-align: right;"><?php echo $data['Expire_Date']; ?></td>
                    <?php
                    $Amount = $Amount + ($data['Buying_Price'] * $data['Item_Quantity']);
                    echo "<td><input type='text' name='Amount[]' id='Amount[]' class='amount_" . $data['Open_Balance_Item_ID'] . " subtotals' readonly value='" . number_format($Amount,2) . "' style='text-align: right;'></td>";

                    $Grand_Total = $Grand_Total + $Amount;
                    $Amount = 0;
                    ?>          
                </tr>
                <?php
            }
        }
        ?>
        <tr><td colspan="10"><hr></td></tr>
        <tr>
            <td colspan="10" style='text-align: right;'><b>GRAND TOTAL</b>&nbsp;&nbsp;&nbsp;<b id="Grand_Total_Total"><?php echo number_format($Grand_Total,2); ?></b></td>
        </tr>
        <tr><td colspan="10"><hr></td></tr>
    </table>
</fieldset>

<script>
    $('.qtychanged').focusin().blur(function () {
        var id = $(this).attr('orderid');
        var type = $(this).attr('edittype');
        var cachedValue = '';

        if (type == 'buyingprice') {
            cachedValue = $('#cacheprice_' + id).val();
        } else if (type == 'qtyreceived') {
            cachedValue = $('#cacheqty_' + id).val();
        }
        cachedValue = numeral().unformat(cachedValue);

        var currentValue = numeral().unformat($(this).val());

        //alert(currentValue);exit;

        if (currentValue != '') {
            if (cachedValue != currentValue) {
                if (parseInt(currentValue) <= 0) {
                    // alertMsg(msg, title, type, width, overlay_close,auto_close,position,center_buttons, btnText,modal,overlay_opacity,show_close_button)
                    alertMsg("You can't receve zero", "Invalid Data", 'error', 0, false, false, "", true, "Ok", true, .5, true);
                    $(this).val(cachedValue);
                    $(this).focus();
                } else {

                    $.ajax({
                        type: 'POST',
                        url: 'modifygrnissuenote.php',
                        data: 'actiongropenbalance=edit&Grn_Open_Balance_ID=' + id + '&new_value=' + currentValue + '&type=' + type,
                        cache: false,
                        beforeSend: function (xhr) {
                            if (type == 'buyingprice') {
                                $('#img_price_' + id).show();
                                $('.orderidprc_' + id).css('width', '80%');
                            } else if (type == 'qtyreceived') {
                                $('#img_qty_' + id).show();
                                $('.orderidqty_' + id).css('width', '80%');
                            }
                        },
                        success: function (result) {
                            if (parseInt(result) == 0) {
                                alertMsg("Couldn't complete your request. If problem persits contanct administrator for support", "Interna Error", 'error', 0, false, 3000, "right + 20,top + 20", true, false, false, 0, false);
                            } else if (parseInt(result) == 1) {
                                if (type == 'buyingprice') {
                                    $('#cacheprice_' + id).val(currentValue);
                                } else if (type == 'qtyreceived') {
                                    $('#cacheqty_' + id).val(currentValue);
                                }

                                var price = numeral().unformat($('.orderidprc_' + id).val());
                                var qty = numeral().unformat($('.orderidqty_' + id).val());
                                var amount = numeral(qty * price).format('0,0.00');

                                var newPrice = numeral(price).format('0,0.00');
                                var newQty = numeral(qty).format('0,0');

                                $('.orderidprc_' + id).val(newPrice);
                                $('.orderidqty_' + id).val(newQty);


                                $('.amount_' + id).val(amount);
                                updateGrandTotal();

                                alertMsg("Saved successifully", "", 'information', 0, false, 3000, "right - 20,top + 20", true, false, false, 0, false);
                            }
                        }, complete: function (jqXHR, textStatus) {
                            if (type == 'buyingprice') {
                                $('#img_price_' + id).hide();
                                $('.orderidprc_' + id).css('width', '100%');
                            } else if (type == 'qtyreceived') {
                                $('#img_qty_' + id).hide();
                                $('.orderidqty_' + id).css('width', '100%');
                            }
                        }, error: function (jqXHR, textStatus, errorThrown) {
                            alertMsg(errorThrown, "Interna Error", 'error', 0, false, 3000, "right + 20,top + 20", true, false, false, 0, false);
                        }
                    });
                }
            } else {

            }
        } else {
            //alert('Vital cannot be empty');
            if (type == 'buyingprice') {
                $('#cacheprice_' + id).val(currentValue);
            } else if (type == 'qtyreceived') {
                $('#cacheqty_' + id).val(currentValue);
            }
        }
    });
</script>
<script>
    $('.qtychanged').keypress(function (event) {

        var keycode = (event.keyCode ? event.keyCode : event.which);
        if (keycode == '13') {
            var id = $(this).attr('orderid');
            var type = $(this).attr('edittype');
            var cachedValue = '';

            if (type == 'buyingprice') {
                cachedValue = $('#cacheprice_' + id).val();
            } else if (type == 'qtyreceived') {
                cachedValue = $('#cacheqty_' + id).val();
            }
            cachedValue = numeral().unformat(cachedValue);

            var currentValue = numeral().unformat($(this).val());

            //alert(currentValue);exit;

            if (currentValue != '') {
                if (cachedValue != currentValue) {
                    if (parseInt(currentValue) <= 0) {
                        // alertMsg(msg, title, type, width, overlay_close,auto_close,position,center_buttons, btnText,modal,overlay_opacity,show_close_button)
                        alertMsg("You can't receve zero", "Invalid Data", 'error', 0, false, false, "", true, "Ok", true, .5, true);
                        $(this).val(cachedValue);
                        $(this).focus();
                    } else {

                        $.ajax({
                            type: 'POST',
                            url: 'modifygrnissuenote.php',
                            data: 'actiongropenbalance=edit&Grn_Open_Balance_ID=' + id + '&new_value=' + currentValue + '&type=' + type,
                            cache: false,
                            beforeSend: function (xhr) {
                                if (type == 'buyingprice') {
                                    $('#img_price_' + id).show();
                                    $('.orderidprc_' + id).css('width', '80%');
                                } else if (type == 'qtyreceived') {
                                    $('#img_qty_' + id).show();
                                    $('.orderidqty_' + id).css('width', '80%');
                                }
                            },
                            success: function (result) {
                                if (parseInt(result) == 0) {
                                    alertMsg("Couldn't complete your request. If problem persits contanct administrator for support", "Interna Error", 'error', 0, false, 3000, "right + 20,top + 20", true, false, false, 0, false);
                                } else if (parseInt(result) == 1) {
                                    if (type == 'buyingprice') {
                                        $('#cacheprice_' + id).val(currentValue);
                                    } else if (type == 'qtyreceived') {
                                        $('#cacheqty_' + id).val(currentValue);
                                    }

                                    var price = numeral().unformat($('.orderidprc_' + id).val());
                                    var qty = numeral().unformat($('.orderidqty_' + id).val());
                                    var amount = numeral(qty * price).format('0,0.00');

                                    var newPrice = numeral(price).format('0,0.00');
                                    var newQty = numeral(qty).format('0,0');

                                    $('.orderidprc_' + id).val(newPrice);
                                    $('.orderidqty_' + id).val(newQty);


                                    $('.amount_' + id).val(amount);
                                    updateGrandTotal();

                                    alertMsg("Saved successifully", "", 'information', 0, false, 3000, "right - 20,top + 20", true, false, false, 0, false);
                                }
                            }, complete: function (jqXHR, textStatus) {
                                if (type == 'buyingprice') {
                                    $('#img_price_' + id).hide();
                                    $('.orderidprc_' + id).css('width', '100%');
                                } else if (type == 'qtyreceived') {
                                    $('#img_qty_' + id).hide();
                                    $('.orderidqty_' + id).css('width', '100%');
                                }
                            }, error: function (jqXHR, textStatus, errorThrown) {
                                alertMsg(errorThrown, "Interna Error", 'error', 0, false, 3000, "right + 20,top + 20", true, false, false, 0, false);
                            }
                        });
                    }
                } else {

                }
            } else {
                //alert('Vital cannot be empty');
                if (type == 'buyingprice') {
                    $('#cacheprice_' + id).val(currentValue);
                } else if (type == 'qtyreceived') {
                    $('#cacheqty_' + id).val(currentValue);
                }
            }
        }

    });
</script>
<script>
    function updateGrandTotal() {
        var total = 0;
        $('.subtotals').each(function () {
            total += numeral().unformat($(this).val());
        });

        $('#Grand_Total_Total').html(numeral(total).format('0,0.00'));
    }
</script>

<link rel="stylesheet" href="css/dialog/zebra_dialog.css" media="screen">
<script src="js/zebra_dialog.js"></script>
<script src="js/ehms_zebra_dialog.js"></script>
<script src="js/functions.js"></script>
<script src="js/numeral/min/numeral.min.js"></script>

<?php
include("./includes/footer.php");
?>
    
