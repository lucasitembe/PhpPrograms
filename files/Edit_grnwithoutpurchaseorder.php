<script src='js/functions.js'></script>
<?php
include_once("./includes/header.php");
include_once("./includes/connection.php");
include_once("./functions/items.php");
include_once("./functions/supplier.php");
include_once("./functions/grnpurchasecache.php");

include_once("./grnwithoutpurchaseorder_navigation.php");

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
        }if ($_SESSION['userinfo']['can_edit'] != 'yes') {
            header("Location: ./previousgrnwithoutpurchaseorder.php?PreviousGrnWithoutPurchaseOrder=PreviousGrnWithoutPurchaseOrderThisPage");
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

if (isset($_SESSION['Debit_Note_Number'])) {
    $Debit_Note_Number = $_SESSION['Debit_Note_Number'];
}

if (isset($_SESSION['Invoice_Number'])) {
    $Invoice_Number = $_SESSION['Invoice_Number'];
}

if (isset($_SESSION['Delivery_Date'])) {
    $Delivery_Date = $_SESSION['Delivery_Date'];
}


$Temp = 0;
$Grand_Total = 0;
$htm2 = '';

if (isset($_SESSION['Grn_ID'])) {
    $Grn_ID = $_SESSION['Grn_ID'];
} else if (isset($_GET['Grn_ID'])) {
    $Grn_ID = $_GET['Grn_ID'];
} else {
    $Grn_ID = 0;
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
<fieldset>
    <legend style="background-color:#006400;color:white;padding:5px;" align="right">
        <b><?php
            if (isset($_SESSION['Storage_Info'])) {
                echo ucwords(strtolower($Sub_Department_Name));
            }
            ?>, GRN Without Purchase Order</b>
    </legend>   
    <?php
    if (isset($_POST['Submitted_Grn_Purchase_Order_Form'])) {
        Start_Transaction();

        $Array_Size = $_POST['Array_Size'];
        $Container_Qty = $_POST['Container_Qty'];
        $Items_Per_Container = $_POST['Items_Per_Container'];
        $Quantity_Required = $_POST['Quantity_Required'];
        $Price = $_POST['Price'];
        $Purchase_Order_Item_ID = $_POST['Purchase_Order_Item_ID'];
        $Expire_Date = $_POST['Expire_Date'];
        $Item_ID = $_POST['Item_ID']; //
        $Supplier_ID = $_POST['Supplier_ID'];
        $Debit_Note_Number = $_POST['Debit_Note_Number'];
        $Invoice_Number = $_POST['Invoice_Number'];
        $Delivery_Date = $_POST['Delivery_Date'];

        $supplier_name = getSupplierInfoById($Supplier_ID)['Supplier_Name'];
        $Grn_Array = array();

        $sms = '';
        $has_error = false;

        for ($count = 0; $count <= $Array_Size; $count++) {

            $current_qr = mysqli_query($conn,"SELECT Purchase_Order_Item_ID,Quantity_Required,gr.Grn_ID,Supplier_ID FROM tbl_grn_without_purchase_order_items gri JOIN tbl_grn_without_purchase_order gr ON gri.Grn_ID=gr.Grn_ID  WHERE Purchase_Order_Item_ID='$Purchase_Order_Item_ID[$count]'") or die(mysqli_error($conn));
            $item_dt = mysqli_fetch_assoc($current_qr);
            $Quantity_Received = $item_dt['Quantity_Required'];
            $Grn_ID = $item_dt['Grn_ID'];
            $Supplier_ID_Curr = $item_dt['Supplier_ID'];
            $source_id = $item_dt['Purchase_Order_Item_ID'];

            if (empty($Supplier_ID)) {
                $Supplier_ID = $Supplier_ID_Curr;
            }

            $Quantity = (int) $Quantity_Received - (int) $Quantity_Required[$count];

            if ($Quantity > 0) {
                $status = Update_Item_Balance($Item_ID[$count], $Sub_Department_ID, 'Without Purchase', null, $Supplier_ID, null, $Grn_ID, Get_Time_Now(), abs($Quantity), false);
            } else {
                $status = Update_Item_Balance($Item_ID[$count], $Sub_Department_ID, 'Without Purchase', null, $Supplier_ID, null, $Grn_ID, Get_Time_Now(), abs($Quantity), true);
            }

            if ($status) {
                //echo "UPDATE tbl_grn_without_purchase_order_items SET Quantity_Required='$Quantity_Required[$count]',Container_Qty='$Container_Qty[$count]',Items_Per_Container='$Items_Per_Container[$count]',Price='$Price[$count]',Expire_Date='$Expire_Date[$count]' WHERE Purchase_Order_Item_ID='$Purchase_Order_Item_ID[$count]' <br/>";
                $update = mysqli_query($conn,"UPDATE tbl_grn_without_purchase_order_items SET Quantity_Required='$Quantity_Required[$count]',Container_Qty='$Container_Qty[$count]',Items_Per_Container='$Items_Per_Container[$count]',Price='" . str_replace(',', '', $Price[$count]) . "',Expire_Date='$Expire_Date[$count]' WHERE Purchase_Order_Item_ID='$Purchase_Order_Item_ID[$count]'") or die(mysqli_error($conn));
                if ($update) {
                    
                } else {
                    $has_error = true;
                    $sms = mysqli_error($conn);
                }

                $update_last_buying_price = "UPDATE tbl_items SET Last_Buy_Price='" . str_replace(',', '', $Price[$count]) . "' WHERE Item_ID='" . $Item_ID[$count] . "'";
                $result23 = mysqli_query($conn,$update_last_buying_price) or die(mysqli_error($conn));
                if (!$result23) {
                    $HAS_ERROR = true;
                }
            } else {
                $has_error = true;
                $sms = mysqli_error($conn);
            }

            $subtotal = $Price[$count] * $Quantity_Required[$count];

            if ($subtotal > 0) {

                $cons = mysqli_query($conn,"SELECT Consultation_Type FROM tbl_items WHERE Item_ID='" . $Item_ID[$count] . "'") or die(mysqli_error($conn));
                $consultation_type = mysqli_fetch_assoc($cons)['Consultation_Type'];

                $inventory_ledger = getInventoryLedgerByConsultationType($consultation_type);

                $Product_Name_Array = array(
                    'ref_no' => $Grn_ID,
                    'source_name' => 'ehms_grn_without',
                    'comment' => 'Delivery Note Number -> ' . $Debit_Note_Number . '  inv_no -> ' . $Invoice_Number . ", " . $Quantity_Required[$count] . " item(s) @ " . $Price[$count] . " Tsh.",
                    'debit_entry_ledger' => $inventory_ledger,
                    'credit_entry_ledger' => $supplier_name,
                    'sub_total' => $subtotal,
                    'source_id' => $source_id,
                    'Employee_Name' => $_SESSION['userinfo']['Employee_Name'],
                    'Employee_ID' => $Employee_ID
                );

                array_push($Grn_Array, $Product_Name_Array);
            }
        }

        // echo "UPDATE tbl_grn_without_purchase_order SET Supplier_ID='$Supplier_ID',Debit_Note_Number='$Debit_Note_Number',Invoice_Number='$Invoice_Number',Delivery_Date='$Delivery_Date' WHERE Grn_ID='".$_GET['Grn_ID']."'  <br/>";
        $update2 = mysqli_query($conn,"UPDATE tbl_grn_without_purchase_order SET Supplier_ID='$Supplier_ID',Debit_Note_Number='$Debit_Note_Number',Invoice_Number='$Invoice_Number',Delivery_Date='$Delivery_Date' WHERE Grn_ID='" . $_GET['Grn_ID'] . "'");
        if ($update2) {
            $sms = 'Updated successfully';

            //update general ledger supplier
            $update3 = mysqli_query($conn,"UPDATE tbl_stock_ledger_controler SET External_Source='$Supplier_ID' WHERE Document_Number='" . $_GET['Grn_ID'] . "'");

            if (!$update3) {
                $has_error = true;
            }
        } else {
            $has_error = true;
        }

        if (count($Grn_Array) > 0) {
            $endata = json_encode($Grn_Array);

            $acc = gAccJournalEntry($endata,'edit');
            
            echo $acc;

            if ($acc != "success") {
                $HAS_ERROR = true;
            }
        }

        if (!$has_error) {
            Commit_Transaction();
            header('Location:Edit_grnwithoutpurchaseorder.php?Grn_ID=' . $_GET['Grn_ID']);
        } else {
            Rollback_Transaction();
        }

        echo $sms;
    }



    $select = mysqli_query($conn,"SELECT
                              emp.Employee_Name, gpo.Supervisor_ID, gpo.Grn_Date_And_Time, sd.Sub_Department_Name,gpo.Supplier_ID,
                              sp.Supplier_Name, gpo.Supervisor_Comment, gpo.Debit_Note_Number, gpo.Invoice_Number, gpo.Delivery_Date
							FROM
							  tbl_grn_without_purchase_order gpo, tbl_employee emp, tbl_supplier sp, tbl_sub_department sd
                                WHERE
                                gpo.Sub_Department_ID = sd.Sub_Department_ID AND
                                gpo.Employee_ID = emp.Employee_ID AND
                                sp.Supplier_ID = gpo.Supplier_ID AND
                                Grn_ID = '$Grn_ID'") or die(mysqli_error($conn));
    $num = mysqli_num_rows($select);
    if ($num > 0) {
        while ($data = mysqli_fetch_array($select)) {
            $Grn_Date_And_Time = $data['Grn_Date_And_Time'];
            $Supervisor_ID = $data['Supervisor_ID'];
            $Supplier_Name = $data['Supplier_Name'];
            $Supplier_ID = $data['Supplier_ID'];
            $Sub_Department_Name = $data['Sub_Department_Name'];
            $Supervisor_Comment = $data['Supervisor_Comment'];
            $Debit_Note_Number = $data['Debit_Note_Number'];
            $Invoice_Number = $data['Invoice_Number'];
            $Delivery_Date = $data['Delivery_Date'];
        }
    } else {
        $Grn_Date_And_Time = '';
        $Supervisor_ID = '';
        $Supplier_Name = '';
        $Supplier_ID = '';
        $Sub_Department_Name = '';
        $Supervisor_Comment = '';
        $Debit_Note_Number = '';
        $Invoice_Number = '';
        $Delivery_Date = '';
    }


    //get supervisor name
    $select = mysqli_query($conn,"select Employee_Name from tbl_employee where Employee_ID = '$Supervisor_ID'") or die(mysqli_error($conn));
    $no = mysqli_num_rows($select);
    if ($no > 0) {
        while ($row = mysqli_fetch_array($select)) {
            $Supervisor_Name = $row['Employee_Name'];
        }
    } else {
        $Supervisor_Name = '';
    }

    echo "<form action='#' method='post'>
        <fieldset>   
            <center> 
             <table width=100%>
		    <tr>
			<td width='10%' style='text-align: right;'>Supplier Name</td>
			<td width='25%'>
				<select name='Supplier_ID' id='Supplier_ID'>
					<option value=''>$Supplier_Name</option>";

    $Supplier_List = Get_Supplier_All();
    foreach ($Supplier_List as $Supplier) {
        if ($Supplier_ID == $Supplier['Supplier_ID']) {
            echo "<option value='{$Supplier['Supplier_ID']}' selected> {$Supplier['Supplier_Name']} </option>";
        } else {
            echo "<option value='{$Supplier['Supplier_ID']}'> {$Supplier['Supplier_Name']} </option>";
        }
    }

    echo "</select>
			</td>
            <td width='10%' style='text-align: right;'>Delivery Note Number</td>
            <td width='25%'>
                <input type='text' name='Debit_Note_Number'  id='Debit_Note_Number'
                       value='$Debit_Note_Number'>
            </td>
            <td width='10%' style='text-align: right;'>Invoice Number</td>
            <td width='25%'>
                <input type='text' name='Invoice_Number'  id='Invoice_Number'
                       value='$Invoice_Number'>
            </td>
            </tr>
            <tr>
                <td style='text-align: right;'>Delivery Date</td>
                <td >
                    <input type='text' name='Delivery_Date'  id='Delivery_Date' readonly='readonly'
                           value='$Delivery_Date'>  
                </td>
                <td style='text-align: right;'>Receiver Name</td>
                <td >
                    <input type='text' name='Receiver_Name'  id='Receiver_Name' readonly='readonly'
                           value='$Employee_ID'>
                </td>
                <td></td>
                <td></td>
            </tr>
            <tr>
            
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td style='text-align: right;'>
                     <input type='button' name='Add_Item' id='Add_Item' value='ADD ITEMS' class='art-button-green' onclick='openItemDialog()'>
                </td>
                <td style='text-align:'>
                    <input type='submit' name='Submit_GRN' id='Submit_GRN' value='SAVE CHANGES' class='art-button-green'>
                </td>
		   </tr> 
		</table>
        </center>
</fieldset>";
    ?>

    <fieldset style='overflow-y: scroll; height: 400px;' id="Items_Fieldset_List">   
        <center>

            <table width=100%>
                <tr><td colspan="10"><hr></td></tr>
                <tr>
                    <td width=4% style="text-align: center;">Sn</td>
                    <td>Item Name</td>
                    <td  <?php echo $display ?>  width=7% style="text-align: center;">Units</td>
                    <td  <?php echo $display ?>  width=10% style="text-align: right;">Items per Unit</td>
                    <td width=7% style="text-align: right;">Quantity</td>
                    <td width=7% style="text-align: right;">Price</td>
                    <td width=7% style="text-align: right;">Sub Total</td>
                    <td width=10% style="text-align: right;">Expire Date&nbsp;&nbsp;</td>
                    <td width=5%>Remove</td>
                </tr>
                <tr><td colspan="10"><hr></td></tr>
                <?php
                $Grand_Total = 0;
                $select_order_items = mysqli_query($conn,"select itm.Product_Name,gpo.Item_ID,gpo.Purchase_Order_Item_ID,gpo.Quantity_Required, gpo.Price, gpo.Container_Qty, 
                                                                gpo.Items_Per_Container, gpo.Expire_Date
                                                                from tbl_grn_without_purchase_order_items gpo, tbl_items itm where
                                                            itm.Item_ID = gpo.Item_ID and
                                                                gpo.Grn_ID ='$Grn_ID'") or die(mysqli_error($conn));
                $no = mysqli_num_rows($select_order_items);

                if ($no > 0) {
                    while ($row = mysqli_fetch_array($select_order_items)) {
                        echo "<tr><td>" . ++$Temp . ".</td>
	    	<td><input type='text' readonly=true name='' value='" . $row['Product_Name'] . "''></td>
                <td " . $display . " style='text-align: right;'>
                <input type='text' style='text-align: right;' class='Container_Qty' id='Qty_" . $row['Item_ID'] . "' name='Container_Qty[]' value='" . $row['Container_Qty'] . "' oninput='numberOnly(this)'>
                </td>
                <td " . $display . "  style='text-align: right;'>
			<input style='text-align: right;' class='Items_Per_Container' id='ItemCont_" . $row['Item_ID'] . "' type='text' name='Items_Per_Container[]' value=" . $row['Items_Per_Container'] . " oninput='numberOnly(this)'>
                </td>
                <td style='text-align: right;'>
			<input style='text-align: right;' type='text' id='Qty_" . $row['Purchase_Order_Item_ID'] . "' item_ID='" . $row['Item_ID'] . "' name='Quantity_Required[]' value=" . $row['Quantity_Required'] . " oninput='numberOnly(this)'>
		</td>
                <td style='text-align: right;'>
                        <input type='text' style='text-align: right;' class='Price' id='price_" . $row['Price'] . "' name='Price[]'  value=" . number_format($row['Price'], 2) . " oninput='numberOnly(this)'>
                </td>
                <td style='text-align: right;'><input style='text-align: right;'class='subTotal' id='subTotal_" . $row['Item_ID'] . "' readonly=true type='text' value=" . number_format($row['Quantity_Required'] * $row['Price'], 2) . "></td>
	    	<td style='text-align: right;'> <input style='text-align: right;' class='expDate' type='text' name='Expire_Date[]'  value=" . $row['Expire_Date'] . ">&nbsp;&nbsp;</td>
                <td><input class='art-button remove' style='border-radius:3px' type='button' value='X' id='" . $row['Purchase_Order_Item_ID'] . "'></td>";
                        echo "<input type='hidden' name='Array_Size' id='Array_Size' value='" . ($no - 1) . "'>";
                        echo "<input type='hidden' name='Purchase_Order_Item_ID[]' id='Array_Size' value='" . $row['Purchase_Order_Item_ID'] . "'>";
                        echo "<input type='hidden' name='Item_ID[]'  value='" . $row['Item_ID'] . "'>";

                        $Grand_Total += ($row['Quantity_Required'] * $row['Price']);
                    }
                }
                ?>


                <tr><td colspan="10"><hr></td></tr>
                <tr>
                    <td colspan=6 style="text-align: right;"><b>GRAND TOTAL</b></td>
                    <td colspan=4 style="text-align: right;"><b><?php echo number_format($Grand_Total, 2); ?></b></td>
                </tr>
                <tr><td colspan="10"><hr></td></tr>
            </table>

        </center>
    </fieldset>
    <input type='hidden' name='Submitted_Grn_Purchase_Order_Form' id='Submitted_Grn_Purchase_Order_Form' value='True'>
    </form>   
    <div id="Add_Pharmacy_Items" style="width:50%;" >
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
                            $data = mysqli_query($conn,"
                select Item_Category_Name, Item_Category_ID
                from tbl_item_category WHERE Category_Type = 'Pharmacy'
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
                                        $Item_List = Get_Items_By_Item_Type('Pharmacy', 200);
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
            <center> <label id='Item_Name_Label'></label><br/> </center>
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
                <tr <?php echo $display ?> >
                    <td style='text-align: right;' id='Containers_Label'>Units</td>
                    <td>
                        <input type='text' name='Container' id='Container' autocomplete='off' placeholder='Containers' onchange='numberOnly(this);
                                Calculate_Quantity();' onkeyup='numberOnly(this);
                                        Calculate_Quantity();' onkeypress='numberOnly(this);
                                                Calculate_Quantity();' oninput='numberOnly(this); Calculate_Quantity();'>
                    </td>
                </tr>
                <tr <?php echo $display ?> >
                    <td style='text-align: right;' id='Items_Per_Container_Label'>Items per Unit</td>
                    <td>
                        <input type='text' name='Items_per_Container' id='Items_per_Container' autocomplete='off' placeholder='Items per Container' onchange='numberOnly(this);
                                Calculate_Quantity();' onkeyup='numberOnly(this);
                                        Calculate_Quantity();' onkeypress='numberOnly(this);
                                                Calculate_Quantity();' oninput='numberOnly(this); Calculate_Quantity();'>
                    </td>
                </tr>
                <tr>
                    <td style='text-align: right;' id='Quantity_Label'>Quantity</td>
                    <td>
                        <input type='text' name='Quantity' id='Quantity' placeholder='Quantity' autocomplete='off'
                               oninput="Clear_Containers_Items(); numberOnly(this);" onkeyup="Clear_Containers_Items();
                                       numberOnly(this);" onkeypress="Clear_Containers_Items();
                                               numberOnly(this);">
                    </td>
                </tr>
                <tr>
                    <td id='Expire_Date' style='text-align: right;'>
                        Expire Date
                    </td>
                    <td>
                        <input type='text' name='date' readonly="readonly" id='date' placeholder='Expire Date'>
                    </td>
                </tr>

                <!--tr>
                    <td style='text-align: right;'> VAT (%) </td>
                    <td>
                        <input type='text' id='Value_Added_Tax' name='Value_Added_Tax' placeholder='VAT'
                               oninput="numberOnly(this);"
                               onkeyup="numberOnly(this);" onkeypress="numberOnly(this);" />
                    </td>
                </tr>
                <tr>
                    <td style='text-align: right;'> Discount (%) </td>
                    <td>
                        <input type='text' id='Discount' name='Discount' placeholder='Discount'
                               oninput="numberOnly(this);"
                               onkeyup="numberOnly(this);" onkeypress="numberOnly(this);" />
                    </td>
                </tr-->
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

            myObject.onreadystatechange = function () {
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
            var Item_Category_ID = "";//document.getElementById("Item_Category_ID").value;
            if (Item_Category_ID == '' || Item_Category_ID == null) {
                Item_Category_ID = 'All';
            }

            if (window.XMLHttpRequest) {
                myObject = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                myObject = new ActiveXObject('Micrsoft.XMLHTTP');
                myObject.overrideMimeType('text/xml');
            }

            myObject.onreadystatechange = function () {
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

                My_Object_Remove_Item.onreadystatechange = function () {
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

        $(document).ready(function () {
            $("#Submit_Prepared_Grn").dialog({autoOpen: false, width: '50%', height: 300, title: 'SUBMIT GRN', modal: true});
            addDatePicker($("#Delivery_Date,.expDate"));
        });

        //.Container_Qty
        $('.Items_Per_Container').on('input', function () {
            //       var values=$(this).val();
            //       var items=$('.Items_Per_Container').attr('id');
            //       var Qty=$('#Qty_'+items).val();
            //      
            //        alert(values); 


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
        $(document).ready(function () {
            $('select').select2();
            $("#Add_Pharmacy_Items").dialog({autoOpen: false, width: 950, height: 450, title: 'ADD NEW ITEM', modal: true});
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

            myObjectGetItemName.onreadystatechange = function () {
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
                        document.getElementById("Price_Label").innerHTML = '<b>Price</b>';
                        document.getElementById("Price").setAttribute("ReadOnly", "ReadOnly");

                        document.getElementById("Item_Name_Label").style.color = '#037CB0';
                        document.getElementById("Item_Name_Label").innerHTML = '<b>This Item Already Added!.</b>';

                        //change add button to warning add button
                        //					document.getElementById("Add_Button_Area").innerHTML = "<input type='button' name='submit' id='Submit_btn1' value='ADD ITEM' class='art-button Submit_btn'>";
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
                        document.getElementById("Price_Label").innerHTML = 'Price';
                        document.getElementById("Price_Label").style.color = 'black';

                        //change warning add button to add button
                        //					document.getElementById("Add_Button_Area").innerHTML = "<input type='button' name='submit' id='Submit_btn2' value='ADD ITEM' class='art-button Submit_btn'>";
                    }
                }
            }; //specify name of function that will handle server response........
            myObjectGetItemName.open('GET', 'Grn_Purchase_Order_Check_Item_Selected.php?Item_ID=' + Item_ID + '&Grn_ID=<?php echo $_GET['Grn_ID'] ?>', true);
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

            myObjectGetPrice.onreadystatechange = function () {
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
            var Supplier_ID = document.getElementById("Supplier_ID").value;

            if (Item_ID != '' && Item_ID != null && Quantity != '' && Quantity != null && Quantity != '' && Price != null && Price != '') {
                if (window.XMLHttpRequest) {
                    my_Object_Get_Selected_Item = new XMLHttpRequest();
                } else if (window.ActiveXObject) {
                    my_Object_Get_Selected_Item = new ActiveXObject('Micrsoft.XMLHTTP');
                    my_Object_Get_Selected_Item.overrideMimeType('text/xml');
                }

                my_Object_Get_Selected_Item.onreadystatechange = function () {
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
                        alert("Item Added Successfully");

                    }
                }; //specify name of function that will handle server response........

                my_Object_Get_Selected_Item.open('GET', 'Grn_Purchase_Order_Add_Selected_Item_edit.php?Item_ID=' + Item_ID + '&Quantity=' + Quantity + '&Price=' + Price + '&Container=' + Container + '&Items_per_Container=' + Items_per_Container + '&Expire_Date=' + Expire_Date + '&Grn_ID=<?php echo $_GET['Grn_ID'] ?>' + '&Supplier_ID=' + Supplier_ID, true);
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
            var Delivery_Date = document.getElementById("Delivery_Date").value;

            if (Supplier_ID != null && Supplier_ID != '' && Debit_Note_Number != '' && Invoice_Number != '' && Delivery_Date != '') {
                document.getElementById("Supplier_ID").style = 'border: white;';
                if (window.XMLHttpRequest) {
                    myObjectSubmit = new XMLHttpRequest();
                } else if (window.ActiveXObject) {
                    myObjectSubmit = new ActiveXObject('Micrsoft.XMLHTTP');
                    myObjectSubmit.overrideMimeType('text/xml');
                }

                myObjectSubmit.onreadystatechange = function () {
                    data29 = myObjectSubmit.responseText;
                    if (myObjectSubmit.readyState == 4) {
                        document.getElementById('Grn_Detail_Area').innerHTML = data29;
                        $("#Submit_Prepared_Grn").dialog("open");
                    }
                }; //specify name of function that will handle server response........

                myObjectSubmit.open('GET', 'Verify_Submit_Grn_Without_Purchase_Order.php', true);
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

                if (Delivery_Date == '') {
                    document.getElementById("Delivery_Date").style = 'border: 3px solid red;';
                } else {
                    document.getElementById("Delivery_Date").style = 'border: 1px solid #B9B59D;';
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

        function Verify_Current_Grn() {
            var Supervisor_Name = document.getElementById("Supervisor_Name").value;
            var Supervisor_Password = document.getElementById("Supervisor_Password").value;
            if (Supervisor_Name != null && Supervisor_Name != '' && Supervisor_Password != null && Supervisor_Password != '') {
                if (window.XMLHttpRequest) {
                    myObjectSubmitGrn = new XMLHttpRequest();
                } else if (window.ActiveXObject) {
                    myObjectSubmitGrn = new ActiveXObject('Micrsoft.XMLHTTP');
                    myObjectSubmitGrn.overrideMimeType('text/xml');
                }

                myObjectSubmitGrn.onreadystatechange = function () {
                    data29 = myObjectSubmitGrn.responseText;
                    if (myObjectSubmitGrn.readyState == 4) {
                        var feedback = data29;
                        if (feedback == 'yes') {
                            Submit_Grn();
                        } else {
                            alert("Invalid supervisor name or supervisor passsword!!\nPlease enter correct supervisor name and passsword");
                            document.getElementById("Supervisor_Name").value = '';
                            document.getElementById("Supervisor_Password").value = '';
                            document.getElementById("Supervisor_Name").focus();
                        }
                    }
                }; //specify name of function that will handle server response........

                myObjectSubmitGrn.open('GET', 'Verify_Grn_Without_Purchase_Order.php?Supervisor_Name=' + Supervisor_Name + '&Supervisor_Password=' + Supervisor_Password, true);
                myObjectSubmitGrn.send();
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

        function Submit_Grn() {
            var Supplier_ID = document.getElementById("Supplier_ID").value;
            var Sub_Department_ID = '<?php echo $Sub_Department_ID; ?>';
            var Supervisor_Name = document.getElementById("Supervisor_Name").value;
            var Supervisor_Password = document.getElementById("Supervisor_Password").value;
            var Supervisor_Comment = document.getElementById("Supervisor_Comment").value;

            var Debit_Note_Number = document.getElementById("Debit_Note_Number").value;
            var Invoice_Number = document.getElementById("Invoice_Number").value;
            var Delivery_Date = document.getElementById("Delivery_Date").value;

            var r = confirm("Are you sure you want to submit this grn?\n\nClick ok to proceed or cancel to terminate process");
            if (r == true) {
                document.location = 'Submit_Grn_Without_Purchase_Order.php?Supplier_ID=' + Supplier_ID +
                        '&Sub_Department_ID=' + Sub_Department_ID + '&Supervisor_Name=' + Supervisor_Name +
                        '&Supervisor_Password=' + Supervisor_Password + '&Supervisor_Comment=' + Supervisor_Comment +
                        '&Debit_Note_Number=' + Debit_Note_Number + '&Invoice_Number=' + Invoice_Number +
                        '&Delivery_Date=' + Delivery_Date;
            }
        }

        $('.remove').on('click', function () {
            var id = $(this).attr('id');
            var Quantity = $('#Qty_' + id).val();
            var item_ID = $('#Qty_' + id).attr('item_ID');
            var GRN_ID = $('#GRN_ID').val();
            if (confirm("Are you sure you want to remove this item?")) {
                $.ajax({
                    type: 'POST',
                    url: "Delete_grnwithoutpurchaseorder.php",
                    data: "action=DeleteItem&Purchase_Order_Item_ID=" + id + '&Quantity=' + Quantity + '&item_ID=' + item_ID + '&GRN_ID=' + GRN_ID,
                    success: function (html) {
                        alert(html);
                        var url = window.location.href;
                        location.href = url;
                    }
                });
            }
        });


        $('.Submit_btn').on('click', function () {
            var Item_ID = $('#Item_ID').val();
            var Price = $('#Price').val();
            var Container = $('#Container').val();
            var Items_per_Container = $('#Items_per_Container').val();
            var GRN_ID = $('#GRN_ID').val();
            var Quantity = $('#Quantity').val();
            var date = $('#date').val();
            $.ajax({
                type: 'POST',
                url: "Delete_grnwithoutpurchaseorder.php",
                data: "action=Add_GRN&Item_ID=" + Item_ID + '&Price=' + Price + '&Container=' + Container + '&Items_per_Container=' + Items_per_Container + '&GRN_ID=' + GRN_ID + '&Quantity=' + Quantity + '&date=' + date,
                success: function (html) {
                    alert(html);
                }
            });

        });


        $("#Add_Pharmacy_Items").on("dialogclose", function (event, ui) {
            var url = window.location.href;
            location.href = url;

        });


    </script>

    <?php include("./includes/footer.php"); ?>