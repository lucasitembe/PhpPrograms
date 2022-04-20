 <?php
    @session_start();
    include("./includes/connection.php");
    $temp = 1;
    if(!isset($_SESSION['userinfo'])){
	@session_destroy(); header("Location: ../index.php?InvalidPrivilege=yes");
    }
    
    
    if(isset($_GET['Open_Balance_Item_ID'])){
	$Open_Balance_Item_ID = $_GET['Open_Balance_Item_ID'];
    }else{
	$Open_Balance_Item_ID = '';
    }
    
    
    //get employee id
    if(isset($_SESSION['userinfo']['Employee_ID'])){
        $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    }else{
        $Employee_ID = 0;
    }
?>

<?php
    if($Open_Balance_Item_ID != '' && $Open_Balance_Item_ID != 0){
        //delete selected record
        $delete_details = mysqli_query($conn,"delete from tbl_reagent_grn_open_balance_items where Open_Balance_Item_ID = '$Open_Balance_Item_ID'") or die(mysqli_error($conn));
        
        if($delete_details){
            $total = 0;
            echo '<center><table width = 100% border=0>';
	    echo '<tr><td width=4% style="text-align: center;">Sn</td>
		    <td width=30%>Item Name</td>
			<td width=10% style="text-align: center;">Quantity</td>
				<td width=10% style="text-align: right;">Buying Price</td>
					<td width=10% style="text-align: center;">Sub Total</td>
						<td width=25% style="text-align: center;">Remark</td>
							<td width=5%>Remove</td></tr>';
            
	    if(isset($_SESSION['Grn_Open_Balance_ID'])){
		$Grn_Open_Balance_ID = $_SESSION['Grn_Open_Balance_ID'];
	    }else{
		$Grn_Open_Balance_ID = 0;
	    }
	    
	    $select_Open_Balance_Items = mysqli_query($conn,"select obi.Open_Balance_Item_ID, itm.Product_Name, obi.Item_Quantity, obi.Item_Remark, obi.Buying_Price
							    from tbl_reagent_grn_open_balance_items obi, tbl_reagents_items itm where
								itm.Item_ID = obi.Item_ID and
								    obi.Grn_Open_Balance_ID ='$Grn_Open_Balance_ID'") or die(mysqli_error($conn));
									
									
	    $Temp=1;
	    while($row = mysqli_fetch_array($select_Open_Balance_Items)){ 
		echo "<tr><td><input type='text' readonly='readonly' value='".$Temp."' style='text-align: center;'></td>";
		echo "<td><input type='text' readonly='readonly' value='".$row['Product_Name']."'></td>";
		echo "<td><input type='text' value='".$row['Item_Quantity']."' style='text-align: center;'></td>";
		echo "<td style='text-align: right;'><input type='text' value='".$row['Buying_Price']."' style='text-align: right;'></td>";
		echo "<td style='text-align: right;'><input type='text' value='".($row['Item_Quantity'] * $row['Buying_Price'])."' style='text-align: right;'></td>";
		echo "<td><input type='text' value='".$row['Item_Remark']."'></td>";
		?>
		    <td width=6%><input type='button' name='Remove_Item' id='Remove_Item' class='art-button-green' value='X' onclick='Confirm_Remove_Item("<?php echo str_replace("'","",$row['Product_Name']); ?>",<?php echo $row['Open_Balance_Item_ID']; ?>)'></td>
		<?php
		    echo "</tr>";
		    $Temp++;
		}
		echo '</table>';
		
	}
    }
		?>