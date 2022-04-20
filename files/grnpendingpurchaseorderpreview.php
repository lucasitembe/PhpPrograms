<script src='js/functions.js'></script>
<?php
include("./includes/header.php");
include("./includes/connection.php");
include_once("./functions/items.php");
$requisit_officer = $_SESSION['userinfo']['Employee_Name'];


$Grn_Status = '';
$Grn_Purchase_Order_ID = '';

$Insert_Status = 'fasle';
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}

$canPakage = false;
$display = "style='display:none'";

if (isset($_SESSION['systeminfo']['enable_receive_by_package']) && $_SESSION['systeminfo']['enable_receive_by_package'] == 'yes') {
    $canPakage = true;
    $display = "";
}

if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Storage_And_Supply_Work'])) {
        if ($_SESSION['userinfo']['Storage_And_Supply_Work'] != 'yes') {
            header("Location: ./index.php?InvalidPrivilege=yes");
        }
    } else {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}


if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes') {
        //get number of pending request
        if (isset($_SESSION['Storage'])) {
            $Sub_Department_Name = $_SESSION['Storage'];
        } else {
            $Sub_Department_Name = '';
        }

        $select_Order_Number = mysqli_query($conn,"select po.Purchase_Order_ID from tbl_purchase_order po, tbl_sub_department sd, tbl_supplier sp where
					    po.sub_department_id = sd.sub_department_id and
					    po.supplier_id = sp.supplier_id and
						po.order_status = 'submitted' and
						po.Approval_Level = po.Approvals and
                        sd.sub_department_id = (select sub_department_id from tbl_sub_department where sub_department_name = '$Sub_Department_Name') group by purchase_order_id") or die(mysqli_error($conn));
        $number = mysqli_num_rows($select_Order_Number);

        echo "<a href='grnpurchaseorderlist.php?GrnPurchaseOrderList=GrnPurchaseOrderListThisPage' class='art-button-green'>NEW GRN&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style='background-color: red; border-radius: 8px; color: white; padding: 6px;'>" . $number . "</span></a>";
    }
}

if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes') {
        $Select_Pending_Order_Number = mysqli_query($conn,"select po.Purchase_Order_ID from  tbl_purchase_order po,tbl_pending_purchase_order_items poi,tbl_sub_department sd where
                            po.Purchase_Order_ID = poi.Purchase_Order_ID and
                            po.Sub_Department_ID = sd.Sub_Department_ID and
                            LOWER(poi.Grn_Status) = 'pending' and 
                            poi.Grn_Purchase_Order_ID IS NULL  and
                            sd.sub_department_id = (select sub_department_id from tbl_sub_department where sub_department_name = '$Sub_Department_Name') group by poi.Purchase_Order_ID") or die(mysqli_error($conn));
        $number = mysqli_num_rows($Select_Pending_Order_Number);
        echo "<a href='grnpendingpurchaseorderlist.php?PendingPurchaseOrderList=PendingPurchaseOrderListThisPage' class='art-button-green'>PENDING PURCHASE ORDER&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style='background-color: red; border-radius: 8px; color: white; padding: 6px;'>" . $number . "</span></a>";
    }
}

if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes') {
        echo "<a href='previousgrnlist.php?PreviousGrn=PreviousGrnThisPage' class='art-button-green'>PREVIOUS GRN</a>";
    }
}
if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes') {
        echo "<a href='goodreceivednote.php?GoodReceivingNote=GoodReceivingNoteThisPage' class='art-button-green'>BACK</a>";
    }
}
?>

<?php
//get sub department id
if (isset($_SESSION['Storage'])) {
    $Sub_Department_Name = $_SESSION['Storage'];

    $sql_select = mysqli_query($conn,"select Sub_Department_ID from tbl_sub_department where Sub_Department_Name = '$Sub_Department_Name'") or die(mysqli_error($conn));
    $no_rows = mysqli_num_rows($sql_select);
    if ($no_rows > 0) {
        while ($row = mysqli_fetch_array($sql_select)) {
            $Sub_Department_ID = $row['Sub_Department_ID'];
        }
    } else {
        $Sub_Department_ID = 0;
    }
}
?>


<?php
if (isset($_GET['Purchase_Order_ID'])) {
    $Purchase_Order_ID = $_GET['Purchase_Order_ID'];
} else {
    $Purchase_Order_ID = 0;
}

//get employee name and id
//employee id
if (isset($_SESSION['userinfo']['Employee_ID'])) {
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
} else {
    $Employee_ID = 0;
}

//employee name
if (isset($_SESSION['userinfo']['Employee_Name'])) {
    $Employee_Name = $_SESSION['userinfo']['Employee_Name'];
} else {
    $Employee_Name = '';
}

//get information
$select_purchase_details = mysqli_query($conn,"select * from tbl_purchase_order po, tbl_supplier sp
						where po.supplier_id = sp.supplier_id and 
									po.purchase_order_id = '$Purchase_Order_ID'") or die(mysqli_error($conn));
$no = mysqli_num_rows($select_purchase_details);
if ($no > 0) {
    while ($row = mysqli_fetch_array($select_purchase_details)) {
        $Purchase_Order_ID = $row['Purchase_Order_ID'];
        $Created_Date = $row['Created_Date'];
        $Supplier_Name = $row['Supplier_Name'];
        $Supplier_ID = $row['Supplier_ID'];
    }
} else {
    $Purchase_Order_ID = '';
    $Created_Date = '';
    $Supplier_Name = '';
    $Supplier_ID = '';
}
?>

<?php
//get the GRN status
if (isset($_GET['Purchase_Order_ID'])) {
    $Purchase_Order_ID = $_GET['Purchase_Order_ID'];
} else {
    $Purchase_Order_ID = 0;
}

if (isset($_GET['Pending_Purchase_Order_ID'])) {
    $Pending_Purchase_Order_ID = $_GET['Pending_Purchase_Order_ID'];
} else {
    $Pending_Purchase_Order_ID = 0;
}


$select_GRN_Status = mysqli_query($conn,"SELECT Order_Status,poi.Grn_Purchase_Order_ID,Grn_Status,was_pending from tbl_purchase_order po ,tbl_pending_purchase_order_items poi WHERE
                                    poi.Purchase_Order_ID = po.Purchase_Order_ID and
                                    po.Purchase_Order_ID = '$Purchase_Order_ID'  and poi.Grn_Purchase_Order_ID IS NOT NULL   AND LOWER(Grn_Status) IN ('received','outstanding','pending')") or die(mysqli_error($conn));
$nop = mysqli_num_rows($select_GRN_Status);
if ($nop > 0) {
    while ($Grn_row = mysqli_fetch_array($select_GRN_Status)) {
        $Order_Status = $Grn_row['Order_Status'];
        $Grn_Status = $Grn_row['Grn_Status'];
        $was_pending = $Grn_row['was_pending'];
        $Grn_Purchase_Order_ID = $Grn_row['Grn_Purchase_Order_ID'];
    }

    $select_ID_and_Date = mysqli_query($conn,"SELECT
                                                  Grn_Purchase_Order_ID, Created_Date_Time,
                                                  Debit_Note_Number, Invoice_Number, Delivery_Date, Delivery_Person
                                               FROM tbl_grn_purchase_order
                                               WHERE Grn_Purchase_Order_ID = '$Grn_Purchase_Order_ID'");
    $no = mysqli_num_rows($select_ID_and_Date);
    if ($no > 0) {
        while ($row = mysqli_fetch_array($select_ID_and_Date)) {
            $Grn_Purchase_Order_ID = $row['Grn_Purchase_Order_ID'];
            $Created_Date_Time = $row['Created_Date_Time'];

            $Debit_Note_Number = $row['Debit_Note_Number'];
            $Invoice_Number = $row['Invoice_Number'];
            $Delivery_Date = $row['Delivery_Date'];
            $Delivery_Person = $row['Delivery_Person'];
        }
    } else {
        $Grn_Purchase_Order_ID = '';
        $Created_Date_Time = '';

        $Debit_Note_Number = '';
        $Invoice_Number = '';
        $Delivery_Date = '';
        $Delivery_Person = '';
    }
} else {
    $Grn_Purchase_Order_ID = '';
    $Created_Date_Time = '';

    $Debit_Note_Number = '';
    $Invoice_Number = '';
    $Delivery_Date = '';
    $Delivery_Person = '';
}
?>

<style>
    table,tr,td{
        border-collapse:collapse !important;
        border:none !important;
    }
    td:hover{
        background-color:#eeeeee;
        cursor:pointer;
    }
</style>
<br/><br/>
<form action='#' method='post' name='myForm' id='myForm' >
    <fieldset>
        <legend style="background-color:#006400;color:white;padding:5px;" align="right"><?php
            if (isset($_SESSION['Storage'])) {
                echo $_SESSION['Storage'];
            }
            ?>, GRN Against Pending Purchase Order</legend>
        <!--<form action='grn_process.php?pgfrom=purchase_order' method='post' name='myForm' id='myForm'>-->
        <fieldset>   
            <center> 
                <table width=100%>
                    <br/>
                    <tr>
                        <td width='10%' style='text-align: right;'>LPO Number</td>
                        <td width='26%'>
                            <input type='text' name='order_id'  id='order_id' value='<?php echo $Purchase_Order_ID; ?>' readonly='readonly'>
                        </td>
                        <td width='13%' style='text-align: right;'>GRN Number</td>
                        <td width='16%'><input type='text' name='grn_number'  id='grn_number' value='<?php echo $Grn_Purchase_Order_ID; ?>' readonly='readonly'></td> 
                    </tr>                               
                    <tr>
                        <td width='10%' style='text-align: right;'>Order Date</td>
                        <td width='26%'>
                            <input type='text' name='created_date'  id='created_date' readonly='readonly' value="<?php echo $Created_Date; ?>" >
                        </td>
                        <td width='13%' style='text-align: right;'>GRN Date</td>
                        <td width='16%'><input type='text' name='grn_date'  id='grn_date' readonly='readonly' value='<?php echo $Created_Date_Time; ?>'></td>
                    </tr> 
                    <tr>
                        <td width='10%' style='text-align: right;'>Supplier</td>
                        <td width='26%'><input type='text' name='Supplier_Name'  id='Supplier_Name' value='<?php echo $Supplier_Name; ?>' readonly='readonly'></td>
                        <td width='16%' style='text-align: right;'>Receiver</td>
                        <td width='26%'>
                            <input type='text' name='Receiver_Name'  id='Receiver_Name' readonly='readonly' value='<?php
                            if ($Employee_ID != 0 && $Employee_Name != '') {
                                echo $Employee_Name;
                            }
                            ?>'  >
                        </td>
                    </tr>
                    <tr>
                        <td width='10%' style='text-align: right;'>Delivery Note Number</td>
                        <td width='26%'>
                            <input type='text' name='Debit_Note_Number'  id='Debit_Note_Number' value='<?php echo $Debit_Note_Number; ?>'/>
                        </td>
                        <td width='16%' style='text-align: right;'>Invoice Number</td>
                        <td width='26%'>
                            <input type='text' name='Invoice_Number'  id='Invoice_Number' value='<?php echo $Invoice_Number; ?>'/>
                        </td>
                    </tr>
                    <tr>
                        <td width='10%' style='text-align: right;'>Delivery Date</td>
                        <td width='26%'>
                            <input type='text' name='Delivery_Date'  id='Delivery_Date' value='<?php echo $Delivery_Date; ?>' readonly="readonly"/>
                        </td>
                        <td width='16%' style='text-align: right;'>Delivery Person</td>
                        <td width='26%'>
                            <input type='text' name='Delivery_Person'  id='Delivery_Person' value='<?php echo $Delivery_Person; ?>'/>
                        </td>
                    </tr>
                </table>
            </center>
        </fieldset>	
    </fieldset>

  
    <style>
        table,tr,td{
            border-collapse:collapse !important;
            border:none !important;

        }

    </style>

    <fieldset>
        <table width=100%>
            <tr>
                <?php if (empty($Grn_Purchase_Order_ID) || $Grn_Purchase_Order_ID == 0) { ?>
                    <td style='text-align: right;'>
                        <input type='checkbox' name='Bacode_Scanner' id='Bacode_Scanner' value='true'><b>Use Bacode Scanner</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <input type='submit' name='submit' id='submit' value='SUBMIT' class='art-button-green' >
                    </td>
                <?php } else { ?>
                    <td style='text-align: right;'>
                        <!--<input type="button" value="Preview GRN" class="art-button-green" onclick="Preview_Grn_Report(<?php echo $Grn_Purchase_Order_ID; ?>);">-->
                        <a href='grnpurchaseorderreport.php?Grn_Purchase_Order_ID=<?php echo $Grn_Purchase_Order_ID; ?>&GrnPurchaseOrder=GrnPurchaseOrderThisPage' target='_Blank' class='art-button-green'>Preview GRN </a> 
                    </td>
                <?php } ?>
            </tr>
        </table>
    </fieldset>

    <script>
        document.getElementById("submit").addEventListener("click", function (event) {
            Debit_Note_Number = document.getElementById("Debit_Note_Number").value;
            Invoice_Number = document.getElementById("Invoice_Number").value;
            Delivery_Date = document.getElementById("Delivery_Date").value;

            if (Debit_Note_Number == '') {
                document.getElementById("Debit_Note_Number").style = 'border: 3px solid red;';
                event.preventDefault();
            }
            else {
                document.getElementById("Debit_Note_Number").style = 'border: 1px solid #B9B59D;';
            }

            if (Invoice_Number == '') {
                document.getElementById("Invoice_Number").style = 'border: 3px solid red;';
                event.preventDefault();
            }
            else {
                document.getElementById("Invoice_Number").style = 'border: 1px solid #B9B59D;';
            }

            if (Delivery_Date == '') {
                document.getElementById("Delivery_Date").style = 'border: 3px solid red;';
                event.preventDefault();
            }
            else {
                document.getElementById("Delivery_Date").style = 'border: 1px solid #B9B59D;';
            }
        });
    </script>

    <fieldset style='overflow-y: scroll; height: 240px;'>   
        <center>
            <table width=100%>
                <tr>
                <tr><td colspan="11"><hr></td></tr>
                <td width=3% style='text-align: center;'>Sn</td>
                <td>Item Name</td>
                <td <?php echo $display ?> width=9% style='text-align: center;'>Units Required</td>
                <td <?php echo $display ?>  width=9% style='text-align: center;'>Items required per unit</td>
                <td width=7% style='text-align: center;'>Quantity required</td>
                <td <?php echo $display ?>  width=7% style='text-align: center;'>Units received</td>
                <td <?php echo $display ?>  width=7% style='text-align: center;'>Items received per unit</td>
                <td width=7% style='text-align: center;'>Quantity Received</td>
                <td width=7% style='text-align: center;'>Buying Price</td>
                <td width=10% style='text-align: center;'>Expire Date</td>
                <td width=10% style='text-align: center;'>Status</td>
                <td width=14% colspan='2' style='text-align: right;'>Amount</td>
                <tr><td colspan="11"><hr></td></tr>
                </tr>
                <?php
//get list of item ordered
                $select_items = mysqli_query($conn,"SELECT * FROM
                                          tbl_purchase_order po, tbl_pending_purchase_order_items poi, tbl_items itm
                                     WHERE
						                  po.Purchase_Order_ID = poi.Purchase_Order_ID AND
							              poi.item_id = itm.item_id AND
								          poi.Grn_Purchase_Order_ID = '$Grn_Purchase_Order_ID'") or die(mysqli_error($conn));
                $no2 = mysqli_num_rows($select_items);
                $temp = 1;
                $Amount = 0;
                $Grand_Total = 0;


                if ($Grn_Purchase_Order_ID != '') {

                    if ($no2 > 0) {
                        while ($row = mysqli_fetch_array($select_items)) {
                            
                            ?>

                            <script>
                                $(function () {
                                    addDatePicker($("#<?php echo $temp; ?>"));
                                });
                            </script>
                            <!--    end of datepicker script-->
                            <?php
                            echo "<tr>"
                            . "<td><input type='text' value='" . $temp . "'  readonly='readonly' style='text-align: center;'></td>";
                            echo "<td><input type='text' value='" . $row['Product_Name'] . "' readonly='readonly'>";
                            echo "<input type='hidden' value='" . $row['Item_ID'] . "' name='Item_ID[]' id='Item_ID[]'></td>";
                            if ($row['Containers_Required'] < 1) {
                                echo "<td><input type='text' value='1' readonly='readonly' style='text-align: right;'></td>";
                            } else {
                                echo "<td $display><input type='text' value='" . $row['Containers_Required'] . "' readonly='readonly' style='text-align: right;'></td>";
                            }
                            if ($row['Containers_Required'] < 1) {
                                echo "<td $display><input type='text' value='" . $row['Quantity_Required'] . "' readonly='readonly' style='text-align: right;'></td>";
                            } else {
                                echo "<td $display><input type='text' value='" . $row['Items_Per_Container_Required'] . "' readonly='readonly' style='text-align: right;'></td>";
                            }
                            echo "<td><input type='text' value='" . $row['Quantity_Required'] . "' readonly='readonly' style='text-align: right;'></td>";
                            //echo "<td><input type='text' value='".number_format($row['Price'],2)."' readonly='readonly' style='text-align: right;'></td>";
                            //echo "<td><input type='text' name='Quantity_Supplied[]' id='Quantity_Supplied[]' autocomplete='off' autocomplete='off' value='".$row['Quantity_Supplied']."' readonly='readonly' style='text-align: right;' onchange='numberOnly(this)' onkeyup='numberOnly(this)' onkeypress='numberOnly(this)'></td>";
                            echo "<td $display><input type='text' name='Container[]' id='Container_" . $row['pending_purchase_id'] . "' autocomplete='off' value='" . $row['Containers_Received'] . "' readonly='readonly' style='text-align: center;' onchange='numberOnly(this)' onkeyup='numberOnly(this)' onkeypress='numberOnly(this)'></td>";
                            echo "<td $display><input type='text' name='Items_Per_Container[]' id='Items_" . $row['pending_purchase_id'] . "' autocomplete='off' value='" . $row['Items_Per_Container'] . "' readonly='readonly' style='text-align: center;' onchange='numberOnly(this)' onkeyup='numberOnly(this)' onkeypress='numberOnly(this)'></td>";
                            echo "<td>"
                            . "<input type='text' name='Qty_Received[]'  orderid='" . $row['pending_purchase_id'] . "' edittype='qtyreceived'  class='numberonly qtychanged orderidqty_" . $row['pending_purchase_id'] . "' id='Qty_Received_" . $row['pending_purchase_id'] . "' autocomplete='off' value='" . number_format($row['Quantity_Received']) . "' style='text-align: center;display:inline;margin:0;float:left;' >"
                            . "<img style='text-align: right;margin:0;float:right;display:none' src='images/ajax-loader-focus.gif' width='20' height='20' id='img_qty_" . $row['pending_purchase_id'] . "'>"
                            . "<input type='hidden'  id='cacheqty_" . $row['pending_purchase_id'] . "' value='" . $row['Quantity_Received'] . "' />  "
                            . "</td>";

                            $Select_Buying_Price = ($row['Buying_Price'] != '') ? number_format($row['Buying_Price'], 2) : number_format($row['Price'], 2);
                            echo "<td><input type='text' name='Buying_Price[]'  title='Edit this price' edittype='buyingprice'  orderid='" . $row['pending_purchase_id'] . "' class='numberonly qtychanged orderidprc_" . $row['pending_purchase_id'] . "'  id='Buying_Price[]' autocomplete='off' value='"
                            . $Select_Buying_Price .
                            "' style='text-align: center;display:inline;margin:0;float:left;' >"
                            . "<img style='text-align: right;margin:0;float:right;display:none' src='images/ajax-loader-focus.gif' width='20' height='20' id='img_price_" . $row['pending_purchase_id'] . "'>"
                            . "<input type='hidden'  id='cacheprice_" . $row['pending_purchase_id'] . "' value='" . $Select_Buying_Price . "' />  "
                            . "</td>";

                            echo "<td><input type='text' style='text-align: right;' disabled='disabled' name='Expire_Date[]' autocomplete='off' id='" . $temp . "' value='" . $row['Expire_Date'] . "'></td>";

                            echo "<td><select name='Grn_Status[]' id='Grn_Status_" . $row['pending_purchase_id'] . "' disabled='disabled' required >";
                            echo "<option value='RECEIVED'> RECEIVED </option>";
                            echo "<option value='OUTSTANDING'> OUTSTANDING </option>";
                            echo "<option value='PENDING'> PENDING </option>";
                            echo "<option value='DISCARD'> DISCARD </option>";
                            echo "</select></td>";
                            echo "<script>$(document).ready(function(){ Select_Grn_Status('" . $row['Grn_Status'] . "'," . $row['pending_purchase_id'] . "); });</script>";

                            echo "<input type='hidden' name='Array_Size' id='Array_Size' value='" . ($no2 - 1) . "'>";
                            $Amount = $Amount + ($row['Buying_Price'] * $row['Quantity_Received']);
                            echo "<td><input type='text' name='Amount[]' id='Amount[]' class='amount_" . $row['pending_purchase_id'] . " subtotals' readonly value='" . number_format($Amount, 2) . "' style='text-align: right;'></td>";
                            echo "</tr>";
                            $Grand_Total = $Grand_Total + $Amount;
                            $Amount = 0;
                            $temp++;
                        }
                    }
                } 
                ?>
                <tr><td colspan="13"><hr></td></tr>
                <tr>
                    <td colspan="13" style='text-align: right;'><b>GRAND TOTAL</b>&nbsp;&nbsp;&nbsp;<b id="Grand_Total_Total"><?php echo number_format($Grand_Total, 2); ?></b></td>
                </tr>
                <tr><td colspan="13"><hr></td></tr>
            </table>
        </center>
    </fieldset>
</form>

<script>
    function calculateTotal(orderId) {
        var price = numeral().unformat($('.orderidprc_' + orderId).val());
        var qty = numeral().unformat($('.orderidqty_' + orderId).val());
        var amount = numeral(qty * price).format('0,0');

        $('.amount_' + orderId).val(amount);

        var total = 0;
        $('.Sub_Amount').each(function () {
            total += numeral().unformat($(this).val());
        });

        total = numeral(total).format('0,0');

        $('#Grand_Total_Total').html(total);
    }
</script>
<script>
    function Select_Grn_Status(Select_Status, Order_Item_ID) {
        document.getElementById("Grn_Status_" + Order_Item_ID).value = Select_Status;
    }

    function Grn_Status_Changed(Select_Status, Order_Item_ID) {
        /*if (Select_Status == "RECEIVED") {
         document.getElementById("Container_"+Order_Item_ID).value = document.getElementById("Containers_Required_"+Order_Item_ID).value;
         document.getElementById("Items_"+Order_Item_ID).value = document.getElementById("Items_Per_Container_Required_"+Order_Item_ID).value;
         document.getElementById("Qty_Received_"+Order_Item_ID).value = document.getElementById("Quantity_Required_"+Order_Item_ID).value;
         Calculate_Quantity(Order_Item_ID);
         Add_Containers(Order_Item_ID);
         Add_Items_Per_Containers(Order_Item_ID);
         }*/
        if (Select_Status == "DISCARD") {
            document.getElementById("Container_" + Order_Item_ID).value = 0;
            document.getElementById("Items_" + Order_Item_ID).value = 0;
            document.getElementById("Qty_Received_" + Order_Item_ID).value = 0;
            Calculate_Quantity(Order_Item_ID);
            Add_Containers(Order_Item_ID);
            Add_Items_Per_Containers(Order_Item_ID);
        }

        if (Select_Status != '') {
            if (window.XMLHttpRequest) {
                myObjectAddGrnStatus = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                myObjectAddGrnStatus = new ActiveXObject('Micrsoft.XMLHTTP');
                myObjectAddGrnStatus.overrideMimeType('text/xml');
            }

            myObjectAddGrnStatus.onreadystatechange = function () {
                data2320 = myObjectAddGrnStatus.responseText;
                if (myObjectAddGrnStatus.readyState == 4) {
                    //Codes...........
                }
            };
            myObjectAddGrnStatus.open('GET', 'Update_Grn_Status_For_Pending_PO.php?Grn_Status=' + Select_Status + '&Order_Item_ID=' + Order_Item_ID, true);
            myObjectAddGrnStatus.send();
        }
    }
</script>

<script type="text/javascript">
    function Calculate_Quantity(Order_Item_ID) {
        var Containers = document.getElementById("Container_" + Order_Item_ID).value;
        var Items_Quantity = document.getElementById("Items_" + Order_Item_ID).value;
        var Total_Quantity = (Items_Quantity * Containers);

        if (Items_Quantity != null && Items_Quantity != '' && Containers != null && Containers != '') {
            document.getElementById("Qty_Received_" + Order_Item_ID).value = (Items_Quantity * Containers);
        }

        if (Containers > 0 && Items_Quantity > 0 && Containers != null && Containers != '' && Items_Quantity != null && Items_Quantity != '') {
            if (window.XMLHttpRequest) {
                myObjectAddCalculate = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                myObjectAddCalculate = new ActiveXObject('Micrsoft.XMLHTTP');
                myObjectAddCalculate.overrideMimeType('text/xml');
            }

            myObjectAddCalculate.onreadystatechange = function () {
                data2320 = myObjectAddCalculate.responseText;
                if (myObjectAddCalculate.readyState == 4) {
                    //Codes...........
                }
            }; //specify name of function that will handle server response........
            myObjectAddCalculate.open('GET', 'Update_Grn_Quantity_Calculated_For_Pending_PO.php?Total_Quantity=' + Total_Quantity + '&Order_Item_ID=' + Order_Item_ID, true);
            myObjectAddCalculate.send();
        }
    }
</script>

<script type="text/javascript">
    function Clear_Quantity(Order_Item_ID) {
        var Quantity = document.getElementById("Qty_Received_" + Order_Item_ID).value;
        Containers = document.getElementById("Container_" + Order_Item_ID).value = 1;
        Items_Quantity = document.getElementById("Items_" + Order_Item_ID).value = Quantity;

        if (window.XMLHttpRequest) {
            myObjectClear = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectClear = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectClear.overrideMimeType('text/xml');
        }

        myObjectClear.onreadystatechange = function () {
            data20 = myObjectClear.responseText;
            if (myObjectClear.readyState == 4) {
                //Codes...........
            }
        }; //specify name of function that will handle server response........
        myObjectClear.open('GET', 'Grn_Update_Entered_Values_For_Pending_PO.php?Quantity=' + Quantity + '&Order_Item_ID=' + Order_Item_ID, true);
        myObjectClear.send();
    }
</script>

<script type="text/javascript">
    function Add_Containers(Containers_ID) {
        var Containers_Received = document.getElementById("Container_" + Containers_ID).value;
        if (window.XMLHttpRequest) {
            myObjectAdd = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectAdd = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectAdd.overrideMimeType('text/xml');
        }

        myObjectAdd.onreadystatechange = function () {
            data20 = myObjectAdd.responseText;
            if (myObjectAdd.readyState == 4) {
                //Codes...........
            }
        }; //specify name of function that will handle server response........
        myObjectAdd.open('GET', 'Grn_Add_Containers_For_Pending_PO.php?Containers_Received=' + Containers_Received + '&Containers_ID=' + Containers_ID, true);
        myObjectAdd.send();
    }
</script>



<script type="text/javascript">
    function Add_Items_Per_Containers(Items_ID) {
        var Items_Per_Container = document.getElementById("Items_" + Items_ID).value;
        if (window.XMLHttpRequest) {
            myObjectAddItems = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectAddItems = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectAddItems.overrideMimeType('text/xml');
        }

        myObjectAddItems.onreadystatechange = function () {
            data2302 = myObjectAddItems.responseText;
            if (myObjectAddItems.readyState == 4) {
                //Codes...........
            }
        }; //specify name of function that will handle server response........
        myObjectAddItems.open('GET', 'Grn_Add_Items_Per_Containers_For_Pending_PO.php?Items_Per_Container=' + Items_Per_Container + '&Items_ID=' + Items_ID, true);
        myObjectAddItems.send();
    }
</script>
<script type="text/javascript">
    function Update_Buying_Price(Order_Item_ID) {
        var Buying_Price = document.getElementById("Buying_Price_" + Order_Item_ID).value;
        if (window.XMLHttpRequest) {
            myObjectUpdatePrice = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectUpdatePrice = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectUpdatePrice.overrideMimeType('text/xml');
        }

        myObjectUpdatePrice.onreadystatechange = function () {
            data230 = myObjectUpdatePrice.responseText;
            if (myObjectUpdatePrice.readyState == 4) {
                //Codes...........
            }
        }; //specify name of function that will handle server response........
        myObjectUpdatePrice.open('GET', 'Grn_Update_Buying_Price_For_Pending_PO.php?Buying_Price=' + Buying_Price + '&Order_Item_ID=' + Order_Item_ID, true);
        myObjectUpdatePrice.send();
    }
</script>

<script type="text/javascript">
    function Update_Date(Date_Value, Order_Item_ID) {
        if (window.XMLHttpRequest) {
            myObjectUpdateExpireDate = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectUpdateExpireDate = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectUpdateExpireDate.overrideMimeType('text/xml');
        }

        myObjectUpdateExpireDate.onreadystatechange = function () {
            data2305 = myObjectUpdateExpireDate.responseText;
            if (myObjectUpdateExpireDate.readyState == 4) {
                //Codes...........
            }
        }; //specify name of function that will handle server response........
        myObjectUpdateExpireDate.open('GET', 'Grn_Update_Expire_Date_For_Pending_PO.php?Date_Value=' + Date_Value + '&Order_Item_ID=' + Order_Item_ID, true);
        myObjectUpdateExpireDate.send();
    }
</script>


<script type="text/javascript">
    function Preview_Grn_Report(Grn_Purchase_Order_ID) {
        var winClose = popupwindow('grnpurchaseorderreport.php?Grn_Purchase_Order_ID=' + Grn_Purchase_Order_ID + '&GrnPurchaseOrder=GrnPurchaseOrderThisPage', 'PURCHASE ORDER REPORT', 1200, 500);
    }

    function popupwindow(url, title, w, h) {
        var wLeft = window.screenLeft ? window.screenLeft : window.screenX;
        var wTop = window.screenTop ? window.screenTop : window.screenY;

        var left = wLeft + (window.innerWidth / 2) - (w / 2);
        var top = wTop + (window.innerHeight / 2) - (h / 2);
        var mypopupWindow = window.showModalDialog(url, title, 'dialogWidth:' + w + '; dialogHeight:' + h + '; center:yes;dialogTop:' + top + '; dialogLeft:' + left);
        return mypopupWindow;
    }
</script>

<script>
    $(document).ready(function () {
        addDatePicker($("#Delivery_Date"));
    });
</script>

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
                        data: 'actiongrpord=edit&orderid=' + id + '&new_value=' + currentValue + '&type=' + type + '&src=pending',
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
                            data: 'actiongrpord=edit&orderid=' + id + '&new_value=' + currentValue + '&type=' + type + '&src=pending',
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
    function updateinfo(field, val, Purchase_Order_ID) {
        $.ajax({
            type: 'POST',
            url: 'modifygrnissuenote.php',
            data: 'actionpurchaseorderinfo=update&field=' + field + '&val=' + val + '&Purchase_Order_ID=' + Purchase_Order_ID,
            cache: false,
            success: function (result) {
            }, error: function (jqXHR, textStatus, errorThrown) {
            }
        });
    }
</script>
<script>
    function updateGrandTotal() {
        var total = 0;
        $('.subtotals').each(function () {
            total += numeral().unformat($(this).val());
        });

        total = numeral(total).format('0,0.00');

        $('#Grand_Total_Total').html(total);
    }
</script>
<link rel="stylesheet" href="css/smoothness/jquery-ui-1.10.1.custom.min.css" />
<script src="js/jquery-1.9.1.js"></script>
<script src="js/jquery-ui-1.10.1.custom.min.js"></script>
<link rel="stylesheet" href="css/dialog/zebra_dialog.css" media="screen">
<script src="js/zebra_dialog.js"></script>
<script src="js/ehms_zebra_dialog.js"></script>
<script src="js/functions.js"></script>
<script src="js/numeral/min/numeral.min.js"></script>

<?php
include("./includes/footer.php");
?>