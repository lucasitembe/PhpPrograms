<?php
    @session_start();
    include("./includes/connection.php");
    $temp = 1;
    if(!isset($_SESSION['userinfo'])){
	@session_destroy(); header("Location: ../index.php?InvalidPrivilege=yes");
    }
    
    if(isset($_GET['Disposal_Item_ID'])){
	$Disposal_Item_ID = $_GET['Disposal_Item_ID'];
    }else{
	$Disposal_Item_ID = '';
    }
    
    
    //get employee id
    if(isset($_SESSION['userinfo']['Employee_ID'])){
        $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    }else{
        $Employee_ID = 0;
    }
?>

<?php
    if($Disposal_Item_ID != '' && $Disposal_Item_ID != 0){
        //get Disposal id
        if(isset($_SESSION['Disposal_ID'])){
            $Disposal_ID = $_SESSION['Disposal_ID'];
        }else{
            $Disposal_ID = 0;
        }
        
	//check if Disposal is not submitted
	$check = mysqli_query($conn,"select Disposal_ID from tbl_disposal where Disposal_Status = 'pending' and Disposal_ID = '$Disposal_ID'") or die(mysqli_error($conn));
	$num_rows = mysqli_num_rows($check);
	
	if($num_rows > 0 ){
	    //delete selected record
	    $delete_details = mysqli_query($conn,"delete from tbl_disposal_items where Disposal_Item_ID = '$Disposal_Item_ID'") or die(mysqli_error($conn));        
	}else{
	    $delete_details = True;
	}
        if($delete_details){
            $total = 0;
            echo '<center><table width = 100% border=0>';
	    echo '<tr><td width=4% style="text-align: center;">Sn</td>
			<td width=40%>Item Name</td>
			    <td width=13% style="text-align: center;">Quantity Disposed</td>
				    <td width=25% style="text-align: center;">Remark</td>
					    <td style="text-align: center; width: 7%;">Remove</td></tr>';
            
            
            $select_Disposed_Items = mysqli_query($conn,"select di.Disposal_Item_ID, itm.Product_Name, di.Quantity_Disposed, di.Item_Remark
						    from tbl_disposal_items di, tbl_items itm where
							itm.Item_ID = di.Item_ID and
							    di.Disposal_ID = '$Disposal_ID'") or die(mysqli_error($conn));
        
            $Temp = 1;
	    while($row = mysqli_fetch_array($select_Disposed_Items)){ 
		echo "<tr><td><input type='text' readonly='readonly' value='".$Temp."' style='text-align: center;'></td>";
		echo "<td><input type='text' readonly='readonly' value='".$row['Product_Name']."'></td>";
		echo "<td><input type='text' value='".$row['Quantity_Disposed']."' style='text-align: center;'></td>";
		echo "<td><input type='text' value='".$row['Item_Remark']."'></td>";
	    ?>
                <td width=6%><input type='button' name='Remove_Item' id='Remove_Item' value='X' class='art-button-green'onclick='Confirm_Remove_Item("<?php echo str_replace("'","",$row['Product_Name']); ?>",<?php echo $row['Disposal_Item_ID']; ?>)'></td>
	    <?php
		echo "</tr>";
		$Temp++;
	    }
	    echo '</table>';
	}
    }
?>