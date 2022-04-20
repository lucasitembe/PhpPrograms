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
        
    if(isset($_SESSION['General_Outward_ID'])){
        $Outward_ID = $_SESSION['General_Outward_ID'];
    }else{
        $Outward_ID = 0;
    }
    
    //get employee id
    if(isset($_SESSION['userinfo']['Employee_ID'])){
        $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    }else{
        $Employee_ID = 0;
    }

    //check if return outward available
    $select = mysqli_query($conn,"select Outward_ID from tbl_return_outward where Outward_ID = '$Outward_ID' and Employee_ID = '$Employee_ID' and Outward_Status = 'pending'") or die(mysqli_error($conn));
    $num = mysqli_num_rows($select);
    if($num > 0){
        //delete selected item
        $delete_items = mysqli_query($conn,"delete from tbl_return_outward_items where Outward_ID = '$Outward_ID'") or die(mysqli_error($conn));
        if($delete_items){
            $delete = mysqli_query($conn,"delete from tbl_return_outward where Outward_ID = '$Outward_ID'") or die(mysqli_error($conn));
            if($delete){
                unset($_SESSION['General_Outward_ID']);
            }
        }
    }


    echo '<center><table width = "100%">';
    echo '<tr><td width=4% style="text-align: center; background-color:silver;color:black">Sn</td>
            <td style="background-color:silver;color:black">Item Name</td>
            <td width=10% style="text-align: center;">UoM</td>
            <td width=10% style="text-align: center;">Qty Returned</td>
            <td width=18% style="text-align: left;">Remark</td>
            <td style="text-align: center;" width="7%">Remove</td></tr>';
    
    
    $select_Transaction_Items = mysqli_query($conn,"select roi.Outward_Item_ID, roi.Outward_ID, itm.Product_Name, roi.Quantity_Returned, roi.Item_Remark, itm.Unit_Of_Measure
                                                from tbl_return_outward_items roi, tbl_items itm where
                                                itm.Item_ID = roi.Item_ID and
                                                roi.Outward_ID ='$Outward_ID'") or die(mysqli_error($conn)); 
    $nm = mysqli_num_rows($select_Transaction_Items);
    if($nm > 0){
        $Temp=1;
        while($row = mysqli_fetch_array($select_Transaction_Items)){
            echo "<tr><td><input type='text' readonly='readonly' value='".$Temp."' style='text-align: center;'></td>";
            echo "<td><input type='text' readonly='readonly' value='".$row['Product_Name']."'></td>";
            echo "<td><input type='text' value='".$row['Unit_Of_Measure']."' style='text-align: center;' readonly='readonly'></td>";
            echo "<td><input type='text' value='".$row['Quantity_Returned']."' style='text-align: center;'></td>";
            echo "<td><input type='text' id='Item_Remark_Seved' name='Item_Remark_Seved' value='".$row['Item_Remark']."' onclick='Update_Item_Remark(".$row['Outward_ID'].",this.value)' onkeyup='Update_Item_Remark(".$row['Outward_ID'].",this.value)'></td>";
        ?>
            <td width=6%><input type='button' name='Remove_Item' id='Remove_Item' value='X' class='art-button-green'onclick='Confirm_Remove_Item("<?php echo str_replace("'","",$row['Product_Name']); ?>",<?php echo $row['Outward_Item_ID']; ?>)'></td>
        <?php
            echo "</tr>";
            $Temp++;
        }
    }
    echo '</table>';
?>