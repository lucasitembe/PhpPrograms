<?php
    @session_start();
    include("./includes/connection.php");
    $temp = 1;
    if(!isset($_SESSION['userinfo'])){
	@session_destroy(); header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Pharmacy'])){
		if($_SESSION['userinfo']['Pharmacy'] != 'yes'){
			header("Location: ./index.php?InvalidPrivilege=yes");
		}else{
		    @session_start();
                    if(!isset($_SESSION['Pharmacy_Supervisor'])){ 
                        header("Location: ./pharmacysupervisorauthentication.php?InvalidSupervisorAuthentication=yes");
                    }
		}
	}else{
		header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
        header("Location: ../index.php?InvalidPrivilege=yes");
    }
    
    
    if(isset($_GET['Pharmacy_Open_Balance_Item_ID'])){
		$Pharmacy_Open_Balance_Item_ID = $_GET['Pharmacy_Open_Balance_Item_ID'];
    }else{
		$Pharmacy_Open_Balance_Item_ID = '';
    }
    
    //get employee id
    if(isset($_SESSION['userinfo']['Employee_ID'])){
        $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    }else{
        $Employee_ID = 0;
    }
?>

<?php
    if($Pharmacy_Open_Balance_Item_ID != '' && $Pharmacy_Open_Balance_Item_ID != 0){
        //delete selected record
        $delete_details = mysqli_query($conn,"delete from tbl_grn_open_balance_items where Open_Balance_Item_ID = '$Pharmacy_Open_Balance_Item_ID'") or die(mysqli_error($conn));
        
        if($delete_details){
            $total = 0;
            echo '<center><table width = 100% border=0>';
			echo '<tr><td width=4% style="text-align: center;">Sn</td>
						<td>Item Name</td>
						<td width=7% style="text-align: center;">Containers</td>
						<td width=7% style="text-align: center;">Items per containers</td>
						<td width=7% style="text-align: center;">Quantity</td>
						<td width=7% style="text-align: right;">Buying Price</td>
						<td width=7% style="text-align: center;">Sub Total</td>
						<td width=7% style="text-align: center;">Manuf Date</td>
						<td width=7% style="text-align: center;">Expire Date</td>
						<td width=5%>Remove</td></tr>';
            
	    if(isset($_SESSION['Pharmacy_Grn_Open_Balance_ID'])){
		$Pharmacy_Grn_Open_Balance_ID = $_SESSION['Pharmacy_Grn_Open_Balance_ID'];
	    }else{
		$Pharmacy_Grn_Open_Balance_ID = 0;
	    }
	    
	    $select_Open_Balance_Items = mysqli_query($conn,"select obi.Grn_Open_Balance_ID, obi.Container_Qty, obi.Items_Per_Container, itm.Product_Name, obi.Item_Quantity, obi.Item_Remark, obi.Buying_Price, obi.Open_Balance_Item_ID,
								obi.Manufacture_Date, obi.Expire_Date
							    from tbl_grn_open_balance_items obi, tbl_items itm where
								itm.Item_ID = obi.Item_ID and
								    obi.Grn_Open_Balance_ID ='$Pharmacy_Grn_Open_Balance_ID'") or die(mysqli_error($conn));
									
									
	    $Temp=1;
	    while($row = mysqli_fetch_array($select_Open_Balance_Items)){ 
			echo "<tr><td><input type='text' readonly='readonly' value='".$Temp."' style='text-align: center;'></td>";
			echo "<td><input type='text' readonly='readonly' value='".$row['Product_Name']."'></td>";
			echo "<td><input type='text' readonly='readonly' value='".$row['Container_Qty']."' style='text-align: center;'></td>";
			echo "<td><input type='text' readonly='readonly' value='".$row['Items_Per_Container']."' style='text-align: center;'></td>";
			echo "<td><input type='text' readonly='readonly' value='".$row['Item_Quantity']."' style='text-align: center;'></td>";
			echo "<td style='text-align: right;'><input type='text' readonly='readonly' value='".$row['Buying_Price']."' style='text-align: right;'></td>";
			echo "<td style='text-align: right;'><input type='text' readonly='readonly' value='".($row['Item_Quantity'] * $row['Buying_Price'])."' style='text-align: right;'></td>";
			echo "<td><input type='text' readonly='readonly' value='".$row['Manufacture_Date']."'></td>";
			echo "<td><input type='text' readonly='readonly' value='".$row['Expire_Date']."'></td>";
		?>
		    <td><input type='button' name='Remove_Item' id='Remove_Item' class='art-button-green' value='X' onclick='Confirm_Remove_Item("<?php echo str_replace("'","",$row['Product_Name']); ?>",<?php echo $row['Open_Balance_Item_ID']; ?>)'></td>
		<?php
		    echo "</tr>";
		    $Temp++;
		}
		echo '</table>';
	}
    }
?>