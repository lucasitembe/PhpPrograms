<?php
    @session_start();
    include("./includes/connection.php");
    $temp = 1;
    if(!isset($_SESSION['userinfo'])){
	@session_destroy(); header("Location: ../index.php?InvalidPrivilege=yes");
    }
    
    if(isset($_GET['Purchase_Cache_ID'])){
	$Purchase_Cache_ID = $_GET['Purchase_Cache_ID'];
    }else{
	$Purchase_Cache_ID = '';
    }
    
    
    
    //get employee id
    if(isset($_SESSION['userinfo']['Employee_ID'])){
        $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    }else{
        $Employee_ID = 0;
    }
?>

<?php
    if($Purchase_Cache_ID != '' && $Purchase_Cache_ID != 0 && $Purchase_Cache_ID != null){
        //get Purchase_Order_ID
        if(isset($_SESSION['Purchase_Order_ID'])){
            $Purchase_Order_ID = $_SESSION['Purchase_Order_ID'];
        }else{
            $Purchase_Order_ID = 0;
        }

        //get requisition id & item id
        $slck = mysqli_query($conn,"select * from tbl_purchase_cache where Purchase_Cache_ID = '$Purchase_Cache_ID'") or die(mysqli_error($conn));
        $nm = mysqli_num_rows($slck);
        if($nm > 0){
            while ($dt = mysqli_fetch_array($slck)) {
                $Requisition_ID = $dt['Requisition_ID'];
                $Item_ID = $dt['Item_ID'];
                $Store_Order_ID = $dt['Store_Order_Id'];
                $Is_Store_Order = $dt['is_store_order'];
            }
        }else{
            $Requisition_ID = 0;
            $Item_ID = 0;
            $Store_Order_ID = 0;
            $Is_Store_Order = 'no';
        }
        
        //delete selected record
        $delete_details = mysqli_query($conn,"delete from tbl_purchase_cache where Purchase_Cache_ID = '$Purchase_Cache_ID'") or die(mysqli_error($conn));
        
        if($delete_details){
            if ($Is_Store_Order == "no") {
                mysqli_query($conn,"update tbl_requisition_items set Procurement_Status = 'active' where Requisition_ID = '$Requisition_ID' and Item_ID = '$Item_ID'");
            } else {
                mysqli_query($conn,"update tbl_store_order_items set Procurement_Status = 'active' where Store_Order_Id = '$Store_Order_ID' and Item_ID = '$Item_ID'");
            }


            $total = 0;
            echo '<center><table width = 100% border=0>';
    	    echo '<tr><td width=4% style="text-align: center;">Sn</td>
                    <td>Item Name</td>
                    <td width=7% style="text-align: center;">Containers</td>
                    <td width=10% style="text-align: right;">Items per Container</td>
                    <td width=7% style="text-align: right;">Quantity</td>
                    <td width=7% style="text-align: right;">Balance</td>
                    <td width=7% style="text-align: right;">Buying Price</td>
                    <td width=7% style="text-align: right;">Sub Total</td>
                    <td width=5%>Remove</td></tr>';
            echo "<tr><td colspan='9'><hr></td></tr>";
            
	    
            $select_order_items = mysqli_query($conn,"select itm.Product_Name, pc.Quantity_Required, pc.Item_Remark, pc.Purchase_Cache_ID, pc.Price, pc.Container_Qty, pc.Items_Per_Container, pc.Item_ID, pc.Store_Need
                                                from tbl_purchase_cache pc, tbl_items itm where
                                                itm.Item_ID = pc.Item_ID and
                                                pc.Employee_ID ='$Employee_ID'") or die(mysqli_error($conn)); 
        
            $Temp=1; $total = 0;
            while($row = mysqli_fetch_array($select_order_items)){
                $Item_ID = $row['Item_ID'];
                $Store_Need = $row['Store_Need'];

                //get item balance
                $get_balance = mysqli_query($conn,"select Item_Balance from tbl_items_balance where Sub_Department_ID = '$Store_Need' and Item_ID = '$Item_ID'") or die(mysqli_error($conn));
                $n_get = mysqli_num_rows($get_balance);
                if($n_get > 0){
                    while ($nget = mysqli_fetch_array($get_balance)) {
                        $Item_Balance = $nget['Item_Balance'];
                    }
                }else{
                    $Item_Balance = 0;
                }

                echo "<tr><td><input type='text' readonly='readonly' value='".$Temp."' style='text-align: center;'></td>";
                echo "<td><input type='text' readonly='readonly' value='".$row['Product_Name']."'></td>";
	    ?>
        <td>
            <input type='text' id='Container_<?php echo $row['Purchase_Cache_ID']; ?>' value='<?php echo $row['Container_Qty']; ?>' style='text-align: right;' oninput="Update_Quantity('<?php echo $row['Purchase_Cache_ID']; ?>')">
        </td>
        <td>
            <input type='text' id='Items_<?php echo $row['Purchase_Cache_ID']; ?>' value='<?php echo $row['Items_Per_Container']; ?>' style='text-align: right;' oninput="Update_Quantity('<?php echo $row['Purchase_Cache_ID']; ?>')">
        </td>
		<td>
		    <input type='text' id='QR<?php echo $row['Purchase_Cache_ID']; ?>' value='<?php echo $row['Quantity_Required']; ?>' style='text-align: right;' oninput="Update_Quantity2(this.value,<?php echo $row['Purchase_Cache_ID']; ?>)">
		</td>    
        <td>
            <input type='text' id='Balance_<?php echo $row['Purchase_Cache_ID']; ?>' value='<?php echo $Item_Balance; ?>' style='text-align: right;' readonly="readonly">
        </td>
		<td>
		    <input type='text' id='<?php echo $row['Purchase_Cache_ID']; ?>' name='<?php echo $row['Purchase_Cache_ID']; ?>' value='<?php echo $row['Price']; ?>' style='text-align: right;' oninput="Update_Price(this.value,<?php echo $row['Purchase_Cache_ID']; ?>)">
		</td>
	    <?php
		echo "<td><input type='text' name='Sub_Total".$row['Purchase_Cache_ID']."' id='Sub_Total".$row['Purchase_Cache_ID']."' readonly='readonly' value='".number_format($row['Quantity_Required'] * $row['Price'])."' style='text-align: right;'></td>";
		//echo "<td><input type='text' value='".$row['Item_Remark']."'></td>";
            ?>
                <td width=6%>
		    <input type='button' name='Remove_Item' id='Remove_Item' value='X' class='art-button-green' onclick='Confirm_Remove_Item("<?php echo str_replace("'","",$row['Product_Name']); ?>",<?php echo $row['Purchase_Cache_ID']; ?>)'>
		</td>
            <?php	
                    
                 echo "</tr>";
		$Temp++;
	    }
	    echo '</table>';
	}
    }
?>