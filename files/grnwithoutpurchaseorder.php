<script src='js/functions.js'></script>
<?php
include_once("./includes/header.php");
include_once("./includes/connection.php");
include_once("./functions/items.php");
include_once("./functions/supplier.php");
include_once("./functions/grnpurchasecache.php");
include("return_unit_of_measure.php");

$canPakage = false;
$display = "style='display:none'";

if (isset($_SESSION['systeminfo']['enable_receive_by_package']) && $_SESSION['systeminfo']['enable_receive_by_package'] == 'yes') {
    $canPakage = true;
    $display = "";
}

$requisit_officer = $_SESSION['userinfo']['Employee_Name'];
$Temp = 0;

$Insert_Status = 'fasle';
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
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
if (!isset($_SESSION['Storage_Info'])) {
    header("Location: ./storagesupervisorauthentication.php?InvalidSupervisorAuthentication=yes");
}

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
?>

<?php
//get sub department id
if (isset($_SESSION['Storage_Info'])) {
    $Sub_Department_ID = $_SESSION['Storage_Info']['Sub_Department_ID'];
    $Sub_Department_Name = $_SESSION['Storage_Info']['Sub_Department_Name'];
}

$Debit_Note_Number = '';
$Invoice_Number = '';
$Delivery_Date = '';
$RV_Number = '';
$lpo = '';
if (isset($_SESSION['Debit_Note_Number'])) {
    $Debit_Note_Number = $_SESSION['Debit_Note_Number'];
}

if (isset($_SESSION['Invoice_Number'])) {
    $Invoice_Number = $_SESSION['Invoice_Number'];
}

if (isset($_SESSION['RV_Number'])) {
    $RV_Number = $_SESSION['RV_Number'];
}

if (isset($_SESSION['Delivery_Date'])) {
    $Delivery_Date = $_SESSION['Delivery_Date'];
}
?>

<?php
// die("SELECT COUNT(grn_without_id) as num FROM tbl_grn_without_purchase_order_approval_cache WHERE 
// Sub_Department_ID='$Sub_Department_ID'");

// $count = mysqli_query($conn, "SELECT COUNT(grn_without_id) as num FROM tbl_grn_without_purchase_order_approval_cache WHERE 
//     Sub_Department_ID='$Sub_Department_ID'") or die(mysqli_error($conn));
// while ($counter = mysqli_fetch_assoc($count)) {
//     $numbers = $counter['num'];
// }

include_once("./grnwithoutpurchaseorder_navigation.php");
?>


<style>
    table,
    tr,
    td {
        border-collapse: collapse !important;
        border: none !important;

    }

    td:hover {
        background-color: #eeeeee;
        cursor: pointer;
    }
</style>

<br /><br />
<fieldset>
    <legend style="background-color:#006400;color:white;padding:5px;" align="right">
        <b><?php if (isset($_SESSION['Storage_Info'])) {
                echo ucwords(strtolower($Sub_Department_Name));
            } ?>, GRN Without Purchase Order</b>
    </legend>

    <fieldset>
        <center>
            <table width=100%>
                <tr>
                    <td width='10%' style='text-align: right;' id="Supplier_Name_label">Supplier Name</td>
                    <td width='25%'>
                        <select name="Supplier_ID" id="Supplier_ID">
                            <option selected="selected"></option>
                            <?php
                            $Supplier_List = Get_Supplier_All();
                            foreach ($Supplier_List as $Supplier) {
                                echo "<option value='{$Supplier['Supplier_ID']}'> {$Supplier['Supplier_Name']} </option>";
                            }
                            ?>
                        </select>
                    </td>
                    <td width='10%' style='text-align: right;'>Delivery Note Number</td>
                    <td width='25%'>
                        <input type='text' name='Debit_Note_Number' id='Debit_Note_Number' value='<?php if ($Debit_Note_Number != '') {
                                                                                                        echo $Debit_Note_Number;
                                                                                                    } ?>' />
                    </td>
                    <td width='10%' style='text-align: right;'>Invoice Number</td>
                    <td width='25%'>
                        <input type='text' name='Invoice_Number' id='Invoice_Number' value='<?php if ($Invoice_Number != '') {
                                                                                                echo $Invoice_Number;
                                                                                            } ?>' />
                    </td>
                </tr>

                <tr>
                    <td style='text-align: right;'>Delivery Date</td>
                    <td>
                        <input type='text' name='Delivery_Date' id='Delivery_Date' readonly='readonly' value='<?php if ($Delivery_Date != '') {
                                                                                                                    echo $Delivery_Date;
                                                                                                                } ?>' />
                    </td>
                    <td style='text-align: right;'>Receiver Name</td>
                    <td>
                        <input type='text' name='Receiver_Name' id='Receiver_Name' readonly='readonly' value='<?php if ($Employee_ID != 0 && $Employee_Name != '') {
                                                                                                                    echo $Employee_Name;
                                                                                                                } ?>' />
                    </td>
                    <td width='10%' style='text-align: right;'>RV Number one</td>
                    <td width='25%'>
                        <input type='text' name='RV_Number' id='RV_Number' value='<?php if ($RV_Number != '') {
                                                                                        echo $RV_Number;
                                                                                    } ?>' />
                    </td>
                </tr>

                <tr>
                    <td style="text-align:right">LPO</td>
                    <td><input type='text' name='lpo_number' id='lpo' value='<?php if ($lpo != '') {
                                                                                    echo $lpo;
                                                                                } ?>' />
                    </td>
                    <td style="text-align:right">Receive As Donation</td>
                    <td><input type="checkbox" id="received_donation_status"></td>
                    <td style='text-align: right;'>
                        <input type="button" name="Add_Item" id="Add_Item" value="ADD ITEMS" class="art-button-green" onclick="openItemDialog()">
                    </td>
                    <td style='text-align: right;'>
                        <input type="button" name="Submit_GRN" id="Submit_GRN" value="ADDITIONAL COST" class="art-button-green" onclick="additional_cost()">
                        <input type="button" name="Submit_GRN" id="Submit_GRN" value="SUBMIT GRN" class="art-button-green" onclick="Verify_Submit_Grn()">
                    </td>
                </tr>
            </table>
        </center>
    </fieldset>

    <fieldset style='overflow-y: scroll; height: 400px;' id="Items_Fieldset_List">
        <center>
            <table width=100%>
                <tr>
                    <td colspan="12">
                        <hr>
                    </td>
                </tr>
                <tr>
                    <td width=4% style="text-align: center;">Sn</td>
                    <td>Item Name</td>
                    <td>Unit Of Measure</td>
                    <td <?php echo $display ?> width=7% style="text-align: center;">Units Received</td>
                    <td <?php echo $display ?> width=10% style="text-align: right;">Items per Unit</td>
                    <td width=7% style="text-align: right;">Total Items</td>
                    <td width=7% style="text-align: right;">Rejected</td>
                    <td width=7% style="text-align: right;">Price</td>
                    <td width=7% style="text-align: right;">Sub Total</td>
                    <td width=10% style="text-align: right;">Batch No</td>
                    <td width=10% style="text-align: right;">Expire Date&nbsp;&nbsp;</td>
                    <td width=5%>Remove</td>
                </tr>
                <tr>
                    <td colspan="12">
                        <hr>
                    </td>
                </tr>
                <?php
                $Grand_Total = 0;

                $Purchase_Order_Cache_Items = Get_Purchase_Order_Cache_Items_By_Employee($Employee_ID);
                foreach ($Purchase_Order_Cache_Items as $Purchase_Order_Cache_Item) {
                ?>
                    <tr>
                        <td><input type='text' readonly='readonly' value='<?php echo ++$Temp; ?>' style='text-align: center;'></td>


                        <td><input type='text' readonly='readonly' value='<?php echo $Purchase_Order_Cache_Item['Product_Name']; ?>' title='<?php echo $Purchase_Order_Cache_Item['Product_Name']; ?>'></td>
                        <!--  unit of measure-->
                        <td>
                            <input type="text" name="name" value="<?php echo unitOfMeasure($Purchase_Order_Cache_Item['Item_ID']); ?>" id="<?php echo $Purchase_Order_Cache_Item['Item_ID'] ?>" />
                        </td>

                        <td <?php echo $display ?>>
                            <input type='text' id="Container_<?php echo $Purchase_Order_Cache_Item['Purchase_Cache_ID']; ?>' readonly='readonly" value="<?php echo $Purchase_Order_Cache_Item['Container_Qty']; ?>" style='text-align: right;' oninput="Update_Quantity(<?php echo $Purchase_Order_Cache_Item['Purchase_Cache_ID']; ?>)">
                        </td>
                        <td <?php echo $display ?>>
                            <input type='text' id='Items_<?php echo $Purchase_Order_Cache_Item['Purchase_Cache_ID']; ?>' readonly='readonly' value="<?php echo $Purchase_Order_Cache_Item['Items_Per_Container']; ?>" style='text-align: right;' oninput="Update_Quantity('<?php echo $Purchase_Order_Cache_Item['Purchase_Cache_ID']; ?>')">
                        </td>
                        <td>
                            <input type='text' id="QR<?php echo $Purchase_Order_Cache_Item['Purchase_Cache_ID']; ?>" readonly='readonly' value='<?php echo $Purchase_Order_Cache_Item['Quantity_Required']; ?>' style='text-align: right;' oninput="Update_Quantity2(this.value,<?php echo $Purchase_Order_Cache_Item['Purchase_Cache_ID']; ?>)">
                        </td>
                        <!--  rejected quantity -->
                        <td>
                            <input style='text-align:right' type="text" name="rejected" value="<?php echo $Purchase_Order_Cache_Item['rejected']; ?>" placeholder="rejected" id="rejected" />
                        </td>
                        <td>
                            <input type='text' id='<?php echo $Purchase_Order_Cache_Item['Purchase_Cache_ID']; ?>' readonly='readonly' name='<?php echo $Purchase_Order_Cache_Item['Purchase_Cache_ID']; ?>' value='<?php echo $Purchase_Order_Cache_Item['Price']; ?>' style='text-align: right;' oninput="Update_Price(this.value,<?php echo number_format($Purchase_Order_Cache_Item['Purchase_Cache_ID'], 2); ?>)">
                        </td>
                        <td><input type='text' name='Sub_Total<?php echo $Purchase_Order_Cache_Item['Purchase_Cache_ID']; ?>' readonly='readonly' id='Sub_Total<?php echo $Purchase_Order_Cache_Item['Purchase_Cache_ID']; ?>' readonly='readonly' value='<?php echo number_format($Purchase_Order_Cache_Item['Quantity_Required'] * $Purchase_Order_Cache_Item['Price'], 2); ?>' style='text-align: right;'></td>

                        <td><input type='text' value='<?php echo $Purchase_Order_Cache_Item['batch_no']; ?>  ' readonly='readonly' style="text-align: right;"></td>
                        <td><input type='text' value='<?php echo $Purchase_Order_Cache_Item['Expire_Date']; ?>  ' readonly='readonly' style="text-align: right;"></td>
                        <td>
                            <input type='button' name='Remove_Item' id='Remove_Item' value='X' class='art-button-green' onclick='Confirm_Remove_Item("<?php echo str_replace("'", "", $Purchase_Order_Cache_Item['Product_Name']); ?>",
    <?php echo $Purchase_Order_Cache_Item['Purchase_Cache_ID']; ?>)'>
                        </td>
                    <?php
                    $Grand_Total += ($Purchase_Order_Cache_Item['Quantity_Required'] * $Purchase_Order_Cache_Item['Price']);
                }
                    ?>

                    <tr>
                        <td colspan="12">
                            <hr>
                        </td>
                    </tr>
                    <tr>
                        <td colspan=6 style="text-align: right;"><b>GRAND TOTAL</b></td>
                        <td colspan=3 style="text-align: right;"><b><?php echo number_format($Grand_Total); ?></b></td>
                    </tr>
                    <tr>
                        <td colspan="12">
                            <hr>
                        </td>
                    </tr>
            </table>
            </form>
        </center>
    </fieldset>

    <!-- Add Pharmarcy Items -->
    <div id="Add_Pharmacy_Items" style="width:50%;">
        <table width=100% style='border-style: none;'>
            <tr>
                <td width=40%>
                    <table width=100% style='border-style: none;'>
                        <tr>
                            <!--td>
                            <b>Category : </b>
                            <select name='Item_Category_ID' id='Item_Category_ID' onchange='getItemsList(this.value)'
                            onchange='Calculate_Amount()' onkeypress='Calculate_Amount()'>
                            <option selected='selected'></option>
<?php
$data = mysqli_query($conn, "SELECT Item_Category_Name, Item_Category_ID
                from tbl_item_category WHERE Category_Type = 'All'
                ") or die(mysqli_error($conn));
while ($row = mysqli_fetch_array($data)) {
    echo '<option value="' . $row['Item_Category_ID'] . '">' . $row['Item_Category_Name'] . '</option>';
}
?>
                            </select>
                            </td-->
                        </tr>
                        <tr>
                            <td>
                                <input type='text' id='Search_Value' name='Search_Value' autocomplete='off' onkeyup='getItemsListFiltered(this.value)' placeholder='Enter Item Name'>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <fieldset style='overflow-y: scroll; height: 270px;' id='Items_Fieldset'>
                                    <table width=100%>
                                        <?php
                                        $Item_List = Get_Items_By_Item_Type('', 200);
                                        foreach ($Item_List as $Item) {
                                            echo "<tr>";
                                            echo "<td style='color:black; border:2px solid #ccc;text-align: left;' width=5%>";
                                            echo "<input type='radio' name='selection' id='{$Item['Item_ID']}' ";
                                            echo "value='{$Item['Product_Name']}' onclick='Get_Item_Name(this.value,{$Item['Item_ID']});'/>";
                                            echo "</td>";
                                            echo "<td style='color:black; border:2px solid #ccc;text-align: left;'>";
                                            echo "<label for='{$Item['Item_ID']}'> {$Item['Product_Name']} </label>";
                                            echo "</td>";
                                            echo "</tr>";
                                        }
                                        ?>
                                    </table>
                                </fieldset>
                            </td>
                        </tr>
                    </table>
                </td>
                <td>
                    <center> <label id='Item_Name_Label'></label><br /> </center>
                    <table width=100% border=0>
                        <tr>
                            <td style='text-align: right;' width=30% id="Item_Name_Label2">Item Name</td>
                            <td>
                                <input type='text' name='Item_Name' id='Item_Name' readonly='readonly' placeholder='Item Name'>
                                <input type='hidden' name='Item_ID' id='Item_ID' value=''>
                            </td>
                        </tr>
                        <tr>
                            <td style='text-align: right;' id='Price_Label'>Price</td>
                            <td>
                                <input type='text' name='Price' id='Price' placeholder='Price' autocomplete='off'>
                            </td>
                        </tr>
                        <tr <?php echo $display ?>>
                            <td style='text-align: right;' id='Containers_Label'>Units Received</td>
                            <td>
                                <input type='text' name='Container' id='Container' autocomplete='off' placeholder='Units Received' onchange='numberOnly(this);
                                Calculate_Quantity();' onkeyup='numberOnly(this);
                                        Calculate_Quantity();' onkeypress='numberOnly(this);
                                                Calculate_Quantity();' oninput='numberOnly(this); Calculate_Quantity();'>
                            </td>
                        </tr>
                        <tr <?php echo $display ?>>
                            <td style='text-align: right;' id='Items_Per_Container_Label'>Items per Unit</td>
                            <td>
                                <input type='text' name='Items_per_Container' id='Items_per_Container' autocomplete='off' placeholder='Items per Unit' onchange='numberOnly(this);
                                Calculate_Quantity();' onkeyup='numberOnly(this);
                                        Calculate_Quantity();' onkeypress='numberOnly(this);
                                                Calculate_Quantity();' oninput='numberOnly(this); Calculate_Quantity();'>
                            </td>
                        </tr>
                        <tr>
                            <td style='text-align: right;' id='Quantity_Label'>Total Items</td>
                            <td>
                                <input type='text' name='Quantity' id='Quantity' placeholder='Quantity' autocomplete='off' oninput="Clear_Containers_Items(); numberOnly(this);" onkeyup="Clear_Containers_Items();
                                       numberOnly(this);" onkeypress="Clear_Containers_Items();
                                               numberOnly(this);">
                            </td>
                        </tr>
                        <tr>
                            <td style='text-align: right;' id='Quantity_Label'>Rejected</td>
                            <td>
                                <input type='text' name='rejected' id='rejected' placeholder='Rejected' autocomplete='off' oninput="numberOnly(this);" onkeyup="
                                       numberOnly(this);" onkeypress="
                                               numberOnly(this);">
                                <!--                        <input type='text' name='rejected' id='rejected' placeholder='Rejected' autocomplete='off'
                               oninput="Clear_Containers_Items(); numberOnly(this);" onkeyup="Clear_Containers_Items();
                                       numberOnly(this);" onkeypress="Clear_Containers_Items();
                                               numberOnly(this);">-->
                            </td>
                        </tr>
                        <tr>
                            <td style='text-align: right;'>Batch No</td>
                            <td> <input type='text' id='batch_no' placeholder='Batch No'> </td>
                        </tr>
                        <tr>
                            <td id='Expire_Date' style='text-align: right;'>
                                Expire Date
                            </td>
                            <td>
                                <input type='text' name='date' readonly="readonly" id='date' placeholder='Expire Date'>
                            </td>
                        </tr>
                        <tr>
                            <td colspan=2 style='text-align: center;' id='Add_Button_Area'>
                                <input type='button' name='Submit' id='Submit' class='art-button-green' value='ADD ITEM' onclick='Get_Selected_Item()'>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>


    <div id="Submit_Prepared_Grn" style="width:50%;">
        <span id='Grn_Detail_Area'>
            <table width=100% style='border-style: none;'>
                <tr>
                    <td>

                    </td>
                </tr>
            </table>
        </span>
    </div>

    <div id=""></div>

    <script type="text/javascript">
        function additional_cost() {
            $.post(
                'additional_cost.php', {
                }, (response) => {
                    $('additional_cost').html(response);
                }
            );
        }
    </script>

    <script type="text/javascript">
        function getItemsList(Item_Category_ID) {
            document.getElementById("Search_Value").value = '';
            document.getElementById("Item_Name").value = '';
            document.getElementById("Item_ID").value = '';
            if (window.XMLHttpRequest) {
                myObject = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                myObject = new ActiveXObject('Micrsoft.XMLHTTP');
                myObject.overrideMimeType('text/xml');
            }

            myObject.onreadystatechange = function() {
                data = myObject.responseText;
                if (myObject.readyState == 4) {
                    //document.getElementById('Approval').readonly = 'readonly';
                    document.getElementById('Items_Fieldset').innerHTML = data;
                }
            }; //specify name of function that will handle server response........
            myObject.open('GET', 'Get_List_Of_Requisition_Items_List.php?Item_Category_ID=' + Item_Category_ID, true);
            myObject.send();
        }

        function getItemsListFiltered(Item_Name) {
            document.getElementById("Item_Name").value = '';
            document.getElementById("Item_ID").value = '';
            var Item_Category_ID = ""; //document.getElementById("Item_Category_ID").value;
            if (Item_Category_ID == '' || Item_Category_ID == null) {
                Item_Category_ID = 'All';
            }

            if (window.XMLHttpRequest) {
                myObject = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                myObject = new ActiveXObject('Micrsoft.XMLHTTP');
                myObject.overrideMimeType('text/xml');
            }

            myObject.onreadystatechange = function() {
                data = myObject.responseText;
                if (myObject.readyState == 4) {
                    //document.getElementById('Approval').readonly = 'readonly';
                    document.getElementById('Items_Fieldset').innerHTML = data;
                }
            }; //specify name of function that will handle server response........
            myObject.open('GET', 'Get_List_Of_Requisition_Filtered_Items.php?Item_Category_ID=' + Item_Category_ID + '&Item_Name=' + Item_Name, true);
            myObject.send();
        }

        function Confirm_Remove_Item(Item_Name, Purchase_Cache_ID) {
            var Confirm_Message = confirm("Are you sure you want to remove \n" + Item_Name);

            if (Confirm_Message == true) {
                if (window.XMLHttpRequest) {
                    My_Object_Remove_Item = new XMLHttpRequest();
                } else if (window.ActiveXObject) {
                    My_Object_Remove_Item = new ActiveXObject('Micrsoft.XMLHTTP');
                    My_Object_Remove_Item.overrideMimeType('text/xml');
                }

                My_Object_Remove_Item.onreadystatechange = function() {
                    data6 = My_Object_Remove_Item.responseText;
                    if (My_Object_Remove_Item.readyState == 4) {
                        document.getElementById('Items_Fieldset_List').innerHTML = data6;
                        //update_total(Registration_ID);
                        //update_Billing_Type();
                        //Update_Claim_Form_Number();
                    }
                }; //specify name of function that will handle server response........

                My_Object_Remove_Item.open('GET', 'Grn_Without_Purchase_Remove_Item_From_List.php?Purchase_Cache_ID=' + Purchase_Cache_ID, true);
                My_Object_Remove_Item.send();
            }
        }

        $(document).ready(function() {
            $("#Submit_Prepared_Grn").dialog({
                autoOpen: false,
                width: '50%',
                height: 300,
                title: 'SUBMIT GRN',
                modal: true
            });
            addDatePicker($("#Delivery_Date"));
        });
    </script>

    <?php
    if ($Insert_Status == 'true') {
        echo "<script>
			alert('Process Successful');
			document.location = 'grnpurchaseorder.php?Purchase_Order_ID=" . $Purchase_Order_ID . "&GrnPurchaseOrder=GrnPurchaseOrderThisPage';
			</script>";
    }
    ?>

    <script src="js/jquery-1.8.0.min.js"></script>
    <script src="js/jquery-ui-1.8.23.custom.min.js"></script>
    <script src="script.js"></script>
    <link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
    <script src="script.responsive.js"></script>
    <link rel="stylesheet" href="css/select2.min.css" media="screen">
    <script src="js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            $('select').select2();
            $("#Add_Pharmacy_Items").dialog({
                autoOpen: false,
                width: 950,
                height: 450,
                title: 'ADD NEW ITEM',
                modal: true
            });



        });

        function openItemDialog() {
            $("#Add_Pharmacy_Items").dialog("open");
        }



        function Calculate_Quantity(Items_Quantity, Cont_Quantity) {
            //var Quantity = document.getElementById("Quantity").value = '';

            if (Items_Quantity != null && Items_Quantity != '' && Cont_Quantity != null && Cont_Quantity != '') {
                document.getElementById("Items_Per_Container[1]").value = (Items_Quantity * Cont_Quantity);
            }
        }

        function Clear_Quantity() {
            //Items_Quantity = document.getElementById("Items_Quantity").value = '';
            //Cont_Quantity = document.getElementById("Cont_Quantity").value = '';
        }

        function Get_Selected_Item_Warning() {
            var Item_Name = document.getElementById("Item_Name").value;
            if (Item_Name != '' && Item_Name != null) {
                alert("Process Fail!!\n" + Item_Name + " already available from the selected items list");
            } else {
                alert("Process Fail!!\nSelected item already available from the selected items list");
            }
        }

        function Get_Item_Name(Item_Name, Item_ID) {
            var Temp = '';
            if (window.XMLHttpRequest) {
                myObjectGetItemName = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                myObjectGetItemName = new ActiveXObject('Micrsoft.XMLHTTP');
                myObjectGetItemName.overrideMimeType('text/xml');
            }

            myObjectGetItemName.onreadystatechange = function() {
                data22 = myObjectGetItemName.responseText;
                if (myObjectGetItemName.readyState == 4) {
                    Temp = data22;
                    if (Temp == 'Yes') {
                        document.getElementById("Item_Name").style.backgroundColor = '#037CB0';

                        document.getElementById("Quantity").style.backgroundColor = '#037CB0';
                        document.getElementById("Quantity").value = '';
                        document.getElementById("Quantity_Label").style.color = '#037CB0';
                        document.getElementById("Quantity_Label").innerHTML = '<b>Quantity</b>';
                        document.getElementById("Quantity").setAttribute("ReadOnly", "ReadOnly");


                        document.getElementById("Price").style.backgroundColor = '#037CB0';
                        document.getElementById("Price").value = '';
                        document.getElementById("Price_Label").style.color = '#037CB0';
                        document.getElementById("Price_Label").innerHTML = '<b>Buying Price</b>';
                        document.getElementById("Price").setAttribute("ReadOnly", "ReadOnly");

                        document.getElementById("Item_Name_Label").style.color = '#037CB0';
                        document.getElementById("Item_Name_Label").innerHTML = '<b>This Item Already Added!.</b>';

                        //change add button to warning add button
                        document.getElementById("Add_Button_Area").innerHTML = "<input type='button' name='submit' id='submit' value='ADD ITEM' class='art-button-green' onclick='Get_Selected_Item_Warning()'>";
                    } else {
                        document.getElementById("Item_Name").style.backgroundColor = 'white';
                        document.getElementById("Item_Name_Label").style.color = 'black';
                        document.getElementById("Item_Name_Label").innerHTML = '';

                        document.getElementById("Quantity").style.backgroundColor = 'white';
                        document.getElementById("Quantity").value = '';
                        //document.getElementById("Quantity").focus();
                        document.getElementById("Quantity").removeAttribute("ReadOnly");
                        document.getElementById("Quantity_Label").innerHTML = 'Quantity';
                        document.getElementById("Quantity_Label").style.color = 'black';

                        document.getElementById("Price").style.backgroundColor = 'white';
                        document.getElementById("Price").removeAttribute("ReadOnly");
                        document.getElementById("Price_Label").innerHTML = 'Buying Price';
                        document.getElementById("Price_Label").style.color = 'black';

                        //change warning add button to add button
                        document.getElementById("Add_Button_Area").innerHTML = "<input type='button' name='submit' id='submit' value='ADD ITEM' class='art-button-green' onclick='Get_Selected_Item()'>";
                    }
                }
            }; //specify name of function that will handle server response........
            myObjectGetItemName.open('GET', 'Grn_Purchase_Order_Check_Item_Selected.php?Item_ID=' + Item_ID, true);
            myObjectGetItemName.send();


            document.getElementById("Item_Name").value = Item_Name;
            document.getElementById("Item_ID").value = Item_ID;
            document.getElementById("Container").value = '';
            document.getElementById("Items_per_Container").value = '';
            //document.getElementById("Quantity").focus();

            if (Item_ID != null && Item_ID != '') {
                Get_Last_Price(Item_ID);
            }
        }

        function Get_Last_Price(Item_ID) {
            if (window.XMLHttpRequest) {
                myObjectGetPrice = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                myObjectGetPrice = new ActiveXObject('Micrsoft.XMLHTTP');
                myObjectGetPrice.overrideMimeType('text/xml');
            }

            myObjectGetPrice.onreadystatechange = function() {
                data_Last_Price = myObjectGetPrice.responseText;
                if (myObjectGetPrice.readyState == 4) {
                    document.getElementById("Price").value = data_Last_Price;
                }
            }; //specify name of function that will handle server response........

            myObjectGetPrice.open('GET', 'Grn_Get_Last_Price.php?Item_ID=' + Item_ID, true);
            myObjectGetPrice.send();
        }

        function Get_Selected_Item() {
            var Item_ID = document.getElementById("Item_ID").value;
            var Quantity = document.getElementById("Quantity").value;
            var Container = document.getElementById("Container").value;
            var Items_per_Container = document.getElementById("Items_per_Container").value;
            var Price = document.getElementById("Price").value;
            var Expire_Date = document.getElementById("date").value;
            var rejected = document.getElementById("rejected").value;
            var batch_no = document.getElementById("batch_no").value;

            var Supplier_ID = document.getElementById('Supplier_ID').value;

            if (Supplier_ID == "") {
                alert("Choose Supplier First.")
                exit();
            }

            // alert(rejected) 
            if (Item_ID != '' && Item_ID != null && Quantity != '' && Quantity != null && Quantity != '' && Price != null && Price != '' && Expire_Date != "" && Expire_Date != null && batch_no != "" && batch_no != null) {
                if (window.XMLHttpRequest) {
                    my_Object_Get_Selected_Item = new XMLHttpRequest();
                } else if (window.ActiveXObject) {
                    my_Object_Get_Selected_Item = new ActiveXObject('Micrsoft.XMLHTTP');
                    my_Object_Get_Selected_Item.overrideMimeType('text/xml');
                }

                my_Object_Get_Selected_Item.onreadystatechange = function() {
                    data = my_Object_Get_Selected_Item.responseText;
                    if (my_Object_Get_Selected_Item.readyState == 4) {
                        document.getElementById('Items_Fieldset_List').innerHTML = data;
                        document.getElementById("Item_Name").value = '';
                        document.getElementById("Quantity").value = '';
                        document.getElementById("Item_ID").value = '';
                        document.getElementById("Container").value = '';
                        document.getElementById("Items_per_Container").value = '';
                        document.getElementById("date").value = '';
                        document.getElementById("Price").value = '';
                        document.getElementById("rejected").value = '';
                        document.getElementById("batch_no").value = '';
                        alert("Item Added Successfully");
                        display_submit_button();
                    }
                }; //specify name of function that will handle server response........

                my_Object_Get_Selected_Item.open('GET', 'Grn_Purchase_Order_Add_Selected_Item.php?Item_ID=' + Item_ID + '&Quantity=' + Quantity + '&Price=' + Price + '&Container=' + Container + '&Items_per_Container=' + Items_per_Container + '&rejected=' + rejected + '&Supplier_ID=' + Supplier_ID + '&Expire_Date=' + Expire_Date + '&batch_no=' + batch_no, true);
                my_Object_Get_Selected_Item.send();

            } else if ((Item_Name == '' || Item_Name == null || Item_ID == '' || Item_ID == null) && Quantity != '' && Quantity != null) {
                alertMessage();
            } else {
                if (Item_ID == '' || Item_ID == null) {
                    document.getElementById("Item_Name").style = 'border: 3px solid red;';
                } else {
                    document.getElementById("Item_Name").style = 'border: white;';
                }
                if (Price == '' || Price == null) {
                    document.getElementById("Price").focus();
                    document.getElementById("Price").style = 'border: 3px solid red;';
                } else {
                    document.getElementById("Price").style = 'border: white;';
                }
                if (Quantity == '' || Quantity == null) {
                    document.getElementById("Quantity").focus();
                    document.getElementById("Quantity").style = 'border: 3px solid red;';
                } else {
                    document.getElementById("Quantity").style = 'border: white;';
                }
                if (Expire_Date == '' || Expire_Date == null) {
                    document.getElementById("date").style = 'border: 3px solid red;';
                } else {
                    document.getElementById("date").style = 'border: white;';
                }
                if (batch_no == '' || batch_no == null) {
                    document.getElementById("batch_no").style = 'border: 3px solid red;';
                } else {
                    document.getElementById("batch_no").style = 'border: white;';
                }
            }
        }

        function Calculate_Quantity() {
            var Items_Quantity = document.getElementById("Items_per_Container").value;
            var Cont_Quantity = document.getElementById("Container").value;
            var Quantity = document.getElementById("Quantity").value = '';

            if (Items_Quantity != null && Items_Quantity != '' && Cont_Quantity != null && Cont_Quantity != '') {
                document.getElementById("Quantity").value = (Items_Quantity * Cont_Quantity);
            } else {
                document.getElementById("Quantity").value = '';
            }
        }

        function Verify_Submit_Grn() {
            var Supplier_ID = document.getElementById("Supplier_ID").value;
            var Debit_Note_Number = document.getElementById("Debit_Note_Number").value;
            var Invoice_Number = document.getElementById("Invoice_Number").value;
            var RV_Number = document.getElementById("RV_Number").value;
            var Delivery_Date = document.getElementById("Delivery_Date").value;
            var lpo = document.getElementById("lpo").value;
            var received_donation_status = "";

            var Supplier_ID = document.getElementById('Supplier_ID').value;

            if (Supplier_ID == "") {
                alert("Select supplier first");
                exit();
            }

            if ($("#received_donation_status").is(":checked")) {
                received_donation_status = "yes";
            } else {
                received_donation_status = "no";
            }
            if (Supplier_ID != null && Supplier_ID != '' && Debit_Note_Number != '' && Invoice_Number != '' && Delivery_Date != '' && lpo != '' || $("#received_donation_status").is(":checked")) {
                document.getElementById("Supplier_ID").style = 'border: white;';
                if (window.XMLHttpRequest) {
                    myObjectSubmit = new XMLHttpRequest();
                } else if (window.ActiveXObject) {
                    myObjectSubmit = new ActiveXObject('Micrsoft.XMLHTTP');
                    myObjectSubmit.overrideMimeType('text/xml');
                }

                myObjectSubmit.onreadystatechange = function() {
                    data29 = myObjectSubmit.responseText;
                    if (myObjectSubmit.readyState == 4) {
                        // document.getElementById('Grn_Detail_Area').innerHTML = data29;
                        // $("#Submit_Prepared_Grn").dialog("open");
                        if (data29 == "stop_no_item_found") {
                            alert("PLEASE ADD ITEM FIRST")
                        } else {
                            if (confirm("Are you sure you want to submit this GRN for approval?"))
                                Submit_Grn_for_approval(); 
                        }
                    }
                }; //specify name of function that will handle server response........

                myObjectSubmit.open('GET', 'Verify_Submit_Grn_Without_Purchase_Order.php?Supplier_ID=' + Supplier_ID, true);
                myObjectSubmit.send();
            } else {
                document.getElementById("Supplier_ID").style = 'border: 3px solid red;';
                document.getElementById("Supplier_ID").focus();

                if (Debit_Note_Number == '') {
                    document.getElementById("Debit_Note_Number").style = 'border: 3px solid red;';
                } else {
                    document.getElementById("Debit_Note_Number").style = 'border: 1px solid #B9B59D;';
                }

                if (Invoice_Number == '') {
                    document.getElementById("Invoice_Number").style = 'border: 3px solid red;';
                } else {
                    document.getElementById("Invoice_Number").style = 'border: 1px solid #B9B59D;';
                }

                if (RV_Number == '') {
                    document.getElementById("RV_Number").style = 'border: 3px solid red;';
                } else {
                    document.getElementById("RV_Number").style = 'border: 1px solid #B9B59D;';
                }
                if (lpo == '') {
                    document.getElementById("lpo").style = 'border: 3px solid red;';
                } else {
                    document.getElementById("lpo").style = 'border: 1px solid #B9B59D;';
                }

                if (Delivery_Date == '') {
                    document.getElementById("Delivery_Date").style = 'border: 3px solid red;';
                } else {
                    document.getElementById("Delivery_Date").style = 'border: 1px solid #B9B59D;';
                }
                if (Supplier_ID == '') {
                    document.getElementById("Supplier_Name_label").style = 'border: 3px solid red!important;text-align:right';
                } else {
                    document.getElementById("Supplier_Name_label").style = 'border: 1px solid #B9B59D;';
                }
            }
        }

        function alertMessage() {
            alert("Please Select Item First");
        }

        function Clear_Containers_Items() {
            var Quantity = document.getElementById("Quantity").value;
            document.getElementById("Container").value = 1;
            document.getElementById("Items_per_Container").value = Quantity;
        }

        function check_if_valid_user_to_approve_this_document() {
            var Supervisor_Username = document.getElementById("Supervisor_Username").value;
            var Supervisor_Password = document.getElementById("Supervisor_Password").value;
            var Issue_ID = '<?php echo $Issue_ID; ?>';
            $.ajax({
                type: 'GET',
                url: 'verify_approver_privileges_support.php',
                data: 'Username=' + Supervisor_Username + '&Password=' + Supervisor_Password + '&document_number=' + Issue_ID + "&document_type=issue_note",
                cache: false,
                success: function(feedback) {
                    if (feedback == 'all_approve_success') {
                        $("#remove_button_column").hide();
                        Submit_Grn();
                    } else if (feedback == "invalid_privileges") {
                        alert("Invalid Username or Password or you do not have enough privilage to approve this requisition");
                    } else if (feedback == "fail_to_approve") {
                        alert("Fail to approve..please try again");
                    } else {
                        Submit_Grn_for_approval()
                        alert(feedback);
                    }
                }
            });
        }

        function Verify_Current_Grn() {
            var Supervisor_Name = document.getElementById("Supervisor_Name").value;
            var Supervisor_Password = document.getElementById("Supervisor_Password").value;
            if (Supervisor_Name != null && Supervisor_Name != '' && Supervisor_Password != null && Supervisor_Password != '') {
                Submit_Grn_for_approval();
            } else {
                if (Supervisor_Name == '' || Supervisor_Name == null) {
                    document.getElementById("Supervisor_Name").style = 'border: 3px solid red;';
                    document.getElementById("Supervisor_Name").focus()
                } else {
                    document.getElementById("Supervisor_Name").style = 'border: white;';
                }

                if (Supervisor_Password == '' || Supervisor_Password == null) {
                    document.getElementById("Supervisor_Password").style = 'border: 3px solid red;';
                    if (Supervisor_Name != '' || Supervisor_Name != null) {
                        document.getElementById("Supervisor_Password").focus();
                    }
                } else {
                    document.getElementById("Supervisor_Password").style = 'border: white;';
                }
            }
        }

        function Submit_Grn_for_approval() {
            var Supplier_ID = document.getElementById("Supplier_ID").value;
            var Sub_Department_ID = '<?php echo $Sub_Department_ID; ?>';
            var Employee_ID = "<?=$_SESSION['userinfo']['Employee_ID']?>";

            var lpo = document.getElementById("lpo").value;
            var Debit_Note_Number = document.getElementById("Debit_Note_Number").value;
            var Invoice_Number = document.getElementById("Invoice_Number").value;
            var RV_Number = document.getElementById("RV_Number").value;
            var Delivery_Date = document.getElementById("Delivery_Date").value;
            var received_donation_status = "";

            if ($("#received_donation_status").is(":checked")) {
                received_donation_status = "yes";
            } else {
                received_donation_status = "no";
            }

            document.location = 'Submit_Grn_Without_Purchase_Order_for_approval.php?Supplier_ID=' + Supplier_ID +
                '&Sub_Department_ID=' + Sub_Department_ID +
                '&Supervisor_Password=' +
                '&Debit_Note_Number=' + Debit_Note_Number + '&Invoice_Number=' + Invoice_Number +
                '&Delivery_Date=' + Delivery_Date + '&RV_Number=' + RV_Number + "&lpo=" + lpo + "&received_donation_status=" + received_donation_status+"&Employee_ID="+Employee_ID;

        }

        function Submit_Grn() {
            var Supplier_ID = document.getElementById("Supplier_ID").value;
            var Sub_Department_ID = '<?php echo $Sub_Department_ID; ?>';
            var Supervisor_Name = document.getElementById("Supervisor_Name").value;
            var Supervisor_Password = document.getElementById("Supervisor_Password").value;
            var Supervisor_Comment = document.getElementById("Supervisor_Comment").value;
            var lpo = document.getElementById("lpo").value;
            var Debit_Note_Number = document.getElementById("Debit_Note_Number").value;
            var Invoice_Number = document.getElementById("Invoice_Number").value;
            var RV_Number = document.getElementById("RV_Number").value;
            var Delivery_Date = document.getElementById("Delivery_Date").value;
            var batch_no = document.getElementById("batch_no").value;

            var received_donation_status = "";

            if ($("#received_donation_status").is(":checked")) {
                received_donation_status = "yes";
            } else {
                received_donation_status = "no";
            }
            var r = confirm("Are you sure you want to submit this grn?\n\nClick ok to proceed or cancel to terminate process");
            if (r == true) {
                document.location = 'Submit_Grn_Without_Purchase_Order.php?Supplier_ID=' + Supplier_ID +
                    '&Sub_Department_ID=' + Sub_Department_ID + '&Supervisor_Name=' + Supervisor_Name +
                    '&Supervisor_Password=' + Supervisor_Password + '&Supervisor_Comment=' + Supervisor_Comment +
                    '&Debit_Note_Number=' + Debit_Note_Number + '&Invoice_Number=' + Invoice_Number +
                    '&Delivery_Date=' + Delivery_Date + '&RV_Number=' + RV_Number + "&lpo=" + lpo + '&batch_no=' + batch_no + '&received_donation_status=' + received_donation_status;
            }
        }
    </script>

    <?php include("./includes/footer.php"); ?>