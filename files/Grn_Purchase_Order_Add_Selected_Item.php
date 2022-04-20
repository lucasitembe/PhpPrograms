<?php
@session_start();
include("./includes/connection.php");
include("return_unit_of_measure.php");
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
if (isset($_GET['batch_no'])) {
    $batch_no = $_GET['batch_no'];
} else {
    $batch_no = 0;
}

if (isset($_GET['Quantity'])) {
    $Quantity = str_replace(',', '', $_GET['Quantity']);
} else {
    $Quantity = 0;
}
if (isset($_GET['Item_Remark'])) {
    $Item_Remark = $_GET['Item_Remark'];
} else {
    $Item_Remark = '';
}

if (isset($_GET['Price'])) {
    $Price = str_replace(',', '', $_GET['Price']);
      // echo "<script>alert('".$price."')</script>";
} else {
    $Price = '';
}

if (isset($_GET['rejected'])) {
    $rejected = str_replace(',', '', $_GET['rejected']);
    // echo "<script>alert('".$rejected."')</script>";
} else {
    $rejected = '';
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

$Supplier_ID = (!empty(isset($_GET['Supplier_ID']))) ? $_GET['Supplier_ID'] : '';

//get employee id
if (isset($_SESSION['userinfo']['Employee_ID'])) {
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
} else {
    $Employee_ID = 0;
}

$Grand_Total = 0;
if ($Item_ID != 0 && $Item_ID != '') {

    $sql_select = mysqli_query($conn,"SELECT Item_ID from tbl_grn_purchase_cache where
                                    Item_ID = '$Item_ID' and
                                        Employee_ID = '$Employee_ID'") or die(mysqli_error($conn));

    $num = mysqli_num_rows($sql_select);
    if ($num == 0) {
 
        //insert data to tbl_grn_purchase_cache
        $Sub_Department_ID = $_SESSION['Storage_Info']['Sub_Department_ID'];
        
        $insert = mysqli_query($conn,"INSERT into tbl_grn_purchase_cache(Quantity_Required,Item_Remark,Item_ID,Price,Employee_ID,Container_Qty,Items_Per_Container,rejected,Expire_Date,batch_no,Sub_Department_ID,Supplier_ID)
                                      VALUES ('$Quantity','$Item_Remark','$Item_ID','$Price','$Employee_ID','$Container','$Items_per_Container','$rejected','$Expire_Date','$batch_no','$Sub_Department_ID','$Supplier_ID')") 
                                      or die(mysqli_error($conn) . 'One');
    }
    $select_order_items = mysqli_query($conn,"SELECT pc.batch_no,itm.Product_Name,pc.Item_ID, pc.Quantity_Required, pc.Item_Remark, pc.Purchase_Cache_ID, pc.Price, pc.Container_Qty,pc.rejected,
										pc.Items_Per_Container, pc.Expire_Date
										from tbl_grn_purchase_cache pc, tbl_items itm where
									    itm.Item_ID = pc.Item_ID and
                                        pc.Employee_ID ='$Employee_ID' AND pc.Sub_Department_ID='$Sub_Department_ID' AND pc.Supplier_ID = '$Supplier_ID'") or die(mysqli_error($conn));
                                        
    $no = mysqli_num_rows($select_order_items);
    echo '<center><table width = 100% border=0>';
    echo '<tr>
				<td width=4% style="text-align: center;">Sn</td>
				<td>Item Name</td>
                <td>Unit Of Measure</td>
				<td ' . $display . '  style="text-align: center;">Containers</td>
				<td ' . $display . ' style="text-align: right;">Items per Container</td>
				<td width=10% style="text-align: right;">Quantity</td>
                <td width=10% style="text-align: right;">Rejected</td>
				<td width=7% style="text-align: right;">Buying Price</td>
				<td width=7% style="text-align: right;">Sub Total</td>
				<td width=7% style="text-align: right;">Batch No</td>
				<td width=10% style="text-align: right;">Expire Date</td>
				<td width=5%>Remove</td>
			</tr>';
    echo "<tr><td colspan='12'><hr></td></tr>";

    $Temp = 1;
    $total = 0;
    while ($row = mysqli_fetch_array($select_order_items)) {
        echo "<tr><td><input type='text' readonly='readonly' value='" . $Temp . "' style='text-align: center;'></td>";
        echo "<td><input type='text' readonly='readonly' value='" . $row['Product_Name'] . "' title='" . $row['Product_Name'] . "'></td>";
        echo "<td><input type='text' name='quantity_rejected[]' value='".unitOfMeasure($row['Item_ID'])."'/></td>";
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
            <input type='text' id='re<?php echo $row['Purchase_Cache_ID']; ?>' value='<?=$row['rejected']; ?>' style='text-align: right;' oninput="Update_Quantity2(this.value,<?php echo $row['Purchase_Cache_ID']; ?>)" placeholder="rejected"/>
        </td>
        <td>
            <input type='text' id='<?php echo $row['Purchase_Cache_ID']; ?>' name='<?php echo $row['Purchase_Cache_ID']; ?>' value='<?php echo $row['Price']; ?>' style='text-align: right;' oninput="Update_Price(this.value,<?php echo $row['Purchase_Cache_ID']; ?>)">
        </td>
        <?php
        echo "<td><input type='text' name='Sub_Total" . $row['Purchase_Cache_ID'] . "' id='Sub_Total" . $row['Purchase_Cache_ID'] . "' readonly='readonly' value='" . number_format($row['Quantity_Required'] * $row['Price'],2) . "' style='text-align: right;'></td>";
        echo "<td><input type='text' value='" . $row['batch_no'] . "'></td>";
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
    echo "<tr><td colspan=12><hr></td></tr>";
    echo "<tr>";
    echo "<td colspan=6 style='text-align: right;'><b>GRAND TOTAL</b></td>";
    echo "<td colspan=3 style='text-align: right;'><b>" . number_format($Grand_Total,2) . "</b></td>";
    echo "</tr>";
    echo "<tr><td colspan=12><hr></td></tr>";
    echo '</table>'; 
}
?>
