<?php
    @session_start();
    include("./includes/connection.php");
    $temp = 1;
    if(!isset($_SESSION['userinfo'])){
	@session_destroy(); header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Storage_And_Supply_Work'])){
		if($_SESSION['userinfo']['Storage_And_Supply_Work'] != 'yes'){
			header("Location: ./index.php?InvalidPrivilege=yes");
		}else{
		    @session_start();
		    if(!isset($_SESSION['Storage_Supervisor'])){
			header("Location: ./storagesupervisorauthentication.php?InvalidSupervisorAuthentication=yes");
		    }
		}
	}else{
		header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
        @session_destroy();
        header("Location: ../index.php?InvalidPrivilege=yes");
    }
    
    if(isset($_GET['Order_Item_ID'])){
        $Order_Item_ID = $_GET['Order_Item_ID'];
    }else{
        $Order_Item_ID = '';
    }
    
    if(isset($_SESSION['Edit_General_Order_ID'])){
        $Store_Order_ID = $_SESSION['Edit_General_Order_ID'];
    }else{
        $Store_Order_ID = 0;
    }
    
    //get employee id
    if(isset($_SESSION['userinfo']['Employee_ID'])){
        $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    }else{
        $Employee_ID = 0;
    }

    //delete selected item
    $delete = mysqli_query($conn,"delete from tbl_store_order_items where Order_Item_ID = '$Order_Item_ID'") or die(mysqli_error($conn));

    echo '<center><table width = 100% border=0>';
    echo '<tr>
            <td width=4% style="text-align: center; background-color:silver;color:black">Sn</td>
            <td style="background-color:silver;color:black">Item Name</td>
            <td width=7% style="text-align: center; background-color:silver;color:black">Units</td>
            <td width=7% style="text-align: center; background-color:silver;color:black">Items</td>
            <td width=7% style="text-align: center; background-color:silver;color:black">Quantity</td>
            <td width=9% style="text-align: center;"><b>Last Buying Price</b></td>
            <td width=25% style="text-align: center; background-color:silver;color:black">Remark</td>
            <td style="text-align: center; background-color:silver;color:black">Remove</td></tr>';
    
    
    $select_items = mysqli_query($conn,"select soi.Order_Item_ID, soi.Store_Order_ID, soi.Last_Buying_Price, itm.Product_Name, soi.Quantity_Required, soi.Item_Remark, soi.Store_Order_ID, soi.Container_Qty, soi.Items_Qty
                                                from tbl_store_order_items soi, tbl_items itm where
                                                itm.Item_ID = soi.Item_ID and
                                                soi.Store_Order_ID ='$Store_Order_ID'") or die(mysqli_error($conn)); 
    
    $Temp=1;
    $no = mysqli_num_rows($select_items);
    while($row = mysqli_fetch_array($select_items)){ 
        echo "<tr><td><input type='text' readonly='readonly' value='".$Temp."' style='text-align: center;'></td>";
        echo "<td><input type='text' readonly='readonly' value='".$row['Product_Name']."'></td>";
        echo "<td><input type='text' value='".$row['Container_Qty']."' style='text-align: center;'  onchange='numberOnly(this)' onkeyup='numberOnly(this)' onkeypress='numberOnly(this)'></td>";
        echo "<td><input type='text' value='".$row['Items_Qty']."' style='text-align: center;'  onchange='numberOnly(this)' onkeyup='numberOnly(this)' onkeypress='numberOnly(this)'></td>";
        echo "<td><input type='text' value='".$row['Quantity_Required']."' style='text-align: center;'  onchange='numberOnly(this)' onkeyup='numberOnly(this)' onkeypress='numberOnly(this)'></td>";
        echo "<td><input type='text' value='".number_format($row['Last_Buying_Price'])."'></td>";
        echo "<td><input type='text' id='Item_Remark_Seved' name='Item_Remark_Seved' value='".$row['Item_Remark']."' onclick='Update_Item_Remark(".$row['Store_Order_ID'].",this.value)' onkeyup='Update_Item_Remark(".$row['Store_Order_ID'].",this.value)'></td>";
    ?>
        <td width=6%><input type='button' name='Remove_Item' id='Remove_Item' value='X' class='art-button-green'onclick='Confirm_Remove_Item("<?php echo str_replace("'","",$row['Product_Name']); ?>",<?php echo $row['Order_Item_ID']; ?>)'></td>
    <?php
        echo "</tr>";
        $Temp++;
    }
    echo '</table>';
?>