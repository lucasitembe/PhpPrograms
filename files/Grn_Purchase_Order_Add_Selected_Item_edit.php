<?php
@session_start();
include("./includes/connection.php");
include_once("./functions/items.php");
include_once("./functions/grnpurchasecache.php");

if (isset($_SESSION['Storage_Info'])) {
    $Sub_Department_ID = $_SESSION['Storage_Info']['Sub_Department_ID'];
    $Sub_Department_Name = $_SESSION['Storage_Info']['Sub_Department_Name'];
}


$canPakage = false;
$display = "style='display:none'";

if (isset($_SESSION['systeminfo']['enable_receive_by_package']) && $_SESSION['systeminfo']['enable_receive_by_package'] == 'yes') {
    $canPakage = true;
    $display = "";
}

if (isset($_GET['Item_ID'])) {
    $Item_ID = $_GET['Item_ID'];
} else {
    $Item_ID = 0;
}

if (isset($_GET['Quantity'])) {
    $Quantity = $_GET['Quantity'];
} else {
    $Quantity = 0;
}
if (isset($_GET['Item_Remark'])) {
    $Item_Remark = $_GET['Item_Remark'];
} else {
    $Item_Remark = '';
}

if (isset($_GET['Price'])) {
    $Price = $_GET['Price'];
} else {
    $Price = '';
}

if (isset($_GET['Container'])) {
    $Container = $_GET['Container'];
} else {
    $Container = '';
}

if (isset($_GET['Items_per_Container'])) {
    $Items_per_Container = $_GET['Items_per_Container'];
} else {
    $Items_per_Container = '';
}

if (isset($_GET['Expire_Date'])) {
    $Expire_Date = $_GET['Expire_Date'];
} else {
    $Expire_Date = '';
}

if (isset($_GET['Grn_ID'])) {
    $Grn_ID = $_GET['Grn_ID'];
}

if (isset($_GET['Supplier_ID'])) {
    $Supplier_ID = $_GET['Supplier_ID'];
}

//get employee id
if (isset($_SESSION['userinfo']['Employee_ID'])) {
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
} else {
    $Employee_ID = 0;
}



$has_error = false;
Start_Transaction();


$insert_item = mysqli_query($conn,"insert into tbl_grn_without_purchase_order_items(
                                    Grn_ID, Quantity_Required, Container_Qty,
                                    Items_Per_Container, Price, Expire_Date, Item_ID)

                                    values('$Grn_ID','$Quantity','$Container',
                                    '$Items_per_Container','$Price','$Expire_Date','$Item_ID')") or die(mysqli_error($conn));

if ($insert_item) {
    $status = Update_Item_Balance($Item_ID, $Sub_Department_ID, 'Without Purchase', null, $Supplier_ID, null, $Grn_ID, Get_Time_Now(), $Quantity, true);

    if (!$status) {
        $has_error = true;
    }
} else {
    $has_error = true;
}





$Grand_Total = 0;
if (!$has_error) {
    Commit_Transaction();

    $select_order_items = mysqli_query($conn,"select itm.Product_Name, gr.Quantity_Required,gr.Price,gr.Container_Qty, 
										gr.Items_Per_Container, gr.Expire_Date
										from tbl_grn_without_purchase_order_items gr, tbl_items itm where
									    itm.Item_ID = gr.Item_ID and
										gr.Grn_ID ='$Grn_ID'") or die(mysqli_error($conn));
    $no = mysqli_num_rows($select_order_items);

    echo '<center><table width = 100% border=0>';
    echo '<tr>
				<td width=4% style="text-align: center;">Sn</td>
				<td>Item Name</td>
				<td ' . $display . '  style="text-align: center;">Containers</td>
				<td ' . $display . ' style="text-align: right;">Items per Container</td>
				<td width=10% style="text-align: right;">Quantity</td>
				<td width=7% style="text-align: right;">Price</td>
				<td width=7% style="text-align: right;">Sub Total</td>
				<td width=10% style="text-align: right;">Expire Date</td>
				<td width=5%>Remove</td>
			</tr>';
    echo "<tr><td colspan='9'><hr></td></tr>";

    $Temp = 1;
    $total = 0;
    while ($row = mysqli_fetch_array($select_order_items)) {
        echo "<tr><td><input type='text' readonly='readonly' value='" . $Temp . "' style='text-align: center;'></td>";
        echo "<td><input type='text' readonly='readonly' value='" . $row['Product_Name'] . "' title='" . $row['Product_Name'] . "'></td>";
        ?>
        <td <?php echo $display ?> >
            <input type='text' id='Container_<?php echo $row['Purchase_Cache_ID']; ?>' value='<?php echo $row['Container_Qty']; ?>' style='text-align: right;width:100%' oninput="Update_Quantity('<?php echo $row['Purchase_Cache_ID']; ?>')">
        </td>
        <td <?php echo $display ?> >
            <input type='text' id='Items_<?php echo $row['Purchase_Cache_ID']; ?>' value='<?php echo $row['Items_Per_Container']; ?>' style='text-align: right;' oninput="Update_Quantity('<?php echo $row['Purchase_Cache_ID']; ?>')">
        </td>
        <td>
            <input type='text' id='QR<?php echo $row['Purchase_Cache_ID']; ?>' value='<?php echo $row['Quantity_Required']; ?>' style='text-align: right;' oninput="Update_Quantity2(this.value,<?php echo $row['Purchase_Cache_ID']; ?>)">
        </td>
        <td>
            <input type='text' id='<?php echo $row['Purchase_Cache_ID']; ?>' name='<?php echo $row['Purchase_Cache_ID']; ?>' value='<?php echo number_format($row['Price'],2); ?>' style='text-align: right;' oninput="Update_Price(this.value,<?php echo $row['Purchase_Cache_ID']; ?>)">
        </td>
        <?php
        echo "<td><input type='text' name='Sub_Total" . $row['Purchase_Cache_ID'] . "' id='Sub_Total" . $row['Purchase_Cache_ID'] . "' readonly='readonly' value='" . number_format($row['Quantity_Required'] * $row['Price'],2) . "' style='text-align: right;'></td>";
        echo "<td><input type='text' value='" . $row['Expire_Date'] . "'></td>";
        ?>
        <td width=6%>
            <input type='button' name='Remove_Item' id='Remove_Item' value='X' class='art-button-green' onclick='Confirm_Remove_Item("<?php echo str_replace("'", "", $row['Product_Name']); ?>",<?php echo $row['Purchase_Cache_ID']; ?>)'>
        </td>
        <?php
        echo "</tr>";
        $Temp++;
        $Grand_Total += ($row['Quantity_Required'] * $row['Price']);
    }
    echo "<tr><td colspan=10><hr></td></tr>";
    echo "<tr>";
    echo "<td colspan=6 style='text-align: right;'><b>GRAND TOTAL</b></td>";
    echo "<td colspan=4 style='text-align: right;'><b>" . number_format($Grand_Total,2) . "</b></td>";
    echo "</tr>";
    echo "<tr><td colspan=10><hr></td></tr>";

    echo '</table>';
}else{
    Rollback_Transaction();
}
?>