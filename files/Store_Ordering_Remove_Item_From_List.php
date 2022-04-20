<?php
@session_start();
include("./includes/connection.php");
$temp = 1;
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Storage_And_Supply_Work'])) {
        if ($_SESSION['userinfo']['Storage_And_Supply_Work'] != 'yes') {
            header("Location: ./index.php?InvalidPrivilege=yes");
        } else {
            @session_start();
            if (!isset($_SESSION['Storage_Supervisor'])) {
                header("Location: ./storagesupervisorauthentication.php?InvalidSupervisorAuthentication=yes");
            }
        }
    } else {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}

if (isset($_GET['Order_Item_ID'])) {
    $Order_Item_ID = $_GET['Order_Item_ID'];
} else {
    $Order_Item_ID = '';
}

if (isset($_GET['Sub_Department_ID'])) {
    $Sub_Department_ID = $_GET['Sub_Department_ID'];
} else {
    $Sub_Department_ID = '';
}

if (isset($_SESSION['General_Order_ID'])) {
    $Store_Order_ID = $_SESSION['General_Order_ID'];
} else {
    $Store_Order_ID = 0;
}

//get employee id
if (isset($_SESSION['userinfo']['Employee_ID'])) {
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
} else {
    $Employee_ID = 0;
}

//delete selected item
$delete = mysqli_query($conn, "delete from tbl_store_order_items where Order_Item_ID = '$Order_Item_ID'") or die(mysqli_error($conn));

echo '<center><table width = 100% border=0>';
echo '<center><table class="table" width = 100% border=0>';
echo '<tr style="background-color:#ddd" class="table">
                                            <td width=4% style="text-align: center;color:black"><b>Sn</b></td>
										    <td style="color:black"><b>Item Name</b></td>
											<td width=1% style="display:none;text-align: center;color:black">Units</td>
											<td width=1% style="display:none;text-align: center;color:black">Items</td>
											<td width=7% style="text-align: center;color:black"><b>Unit<b/></td>
											<td width=10% style="text-align: center;color:black"><b>Item Per Unit<b/></td>
											<td width=7% style="text-align: center;color:black"><b>Total Unit</b></td>
                                    		<td width=9% style="text-align: center;"><b>Store Balance</b></td>
                                    		<td width=10%% style="text-align: center;"><b>Last Buying Price</b></td>
											<td width=7% style="text-align: center;color:black"><b>Remark<b/></td>
											<td width="10%" style="text-align: center;color:black"><b>Action</b></td></tr>';


$select_items = mysqli_query($conn, "SELECT soi.Order_Item_ID, soi.Store_Order_ID, soi.Last_Buying_Price, itm.Product_Name,
                                        soi.Quantity_Required, soi.Item_Remark, soi.Store_Order_ID, soi.Container_Qty,
                                        soi.Items_Qty, ib.Item_Balance
                                 FROM tbl_store_order_items soi, tbl_items itm, tbl_items_balance ib
                                 WHERE itm.Item_ID = soi.Item_ID AND
                                       itm.Item_ID = ib.Item_ID AND
                                       ib.Sub_Department_ID = '$Sub_Department_ID' AND
                                       soi.Store_Order_ID ='$Store_Order_ID'") or die(mysqli_error($conn));

$Temp = 1;
$no = mysqli_num_rows($select_items);
while ($row = mysqli_fetch_array($select_items)) {
    echo "<tr><td><input type='text' readonly='readonly' value='" . $Temp . "' style='text-align: center;'></td>";
    echo "<td><input type='text' readonly='readonly' value='" . $row['Product_Name'] . "'></td>";

    echo "<td><input type='text' id='Container_Qty_" . $row['Order_Item_ID'] . "'
        value='" . $row['Container_Qty'] . "' style='text-align: center;'
        onkeyup='Change_Container_And_Items(" . $row['Order_Item_ID'] . ");Update_Store_Order_Item(" . $row['Order_Item_ID'] . ");'
        onkeyup='Change_Container_And_Items(" . $row['Order_Item_ID'] . ");'></td>";

    echo "<td><input type='text' id='Items_Qty_" . $row['Order_Item_ID'] . "'
        value='" . $row['Items_Qty'] . "' style='text-align: center;'
        onkeyup='Change_Container_And_Items(" . $row['Order_Item_ID'] . ");Update_Store_Order_Item(" . $row['Order_Item_ID'] . ");'
        onkeyup='numberOnly(this);'></td>";

    echo "<td><input type='text' readonly='readonly' class='Quantity_Required_' id='Quantity_Required_" . $row['Order_Item_ID'] . "'
        value='" . $row['Quantity_Required'] . "' style='text-align: center;'
        onkeyup='Update_Store_Order_Item(" . $row['Order_Item_ID'] . ");'
        onkeyup='Change_Quantity_Required(" . $row['Order_Item_ID'] . ");'
        onkeyup='numberOnly(this);Change_Quantity_Required(" . $row['Order_Item_ID'] . ");'></td>";

    echo "<td><input type='text' style='text-align:center' readonly value='" . number_format($row['Item_Balance']) . "'></td>";
    echo "<td><input type='text' style='text-align:center' readonly value='" . number_format($row['Last_Buying_Price']) . "'></td>";
    echo "<td><input type='text' id='Item_Remark_Seved' name='Item_Remark_Seved' value='" . $row['Item_Remark'] . "'
										            onchange='Update_Store_Order_Item_Remark(" . $row['Order_Item_ID'] . ",this.value)'></td>";
?>
    <td width=6% style="text-align: center;"><input type='button' name='Remove_Item' id='Remove_Item' value='X' class='art-button-green' onclick='Confirm_Remove_Item("<?php echo str_replace("'", "", $row['Product_Name']); ?>",<?php echo $row['Order_Item_ID']; ?>)'></td>
<?php
    echo "</tr>";
    $Temp++;
}
echo '</table>';
?>