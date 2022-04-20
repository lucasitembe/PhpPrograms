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
    
    
    if(isset($_GET['Requisition_Item_ID'])){
	$Requisition_Item_ID = $_GET['Requisition_Item_ID'];
    }else{
	$Requisition_Item_ID = '';
    }
    
    
    
    //get employee id
    if(isset($_SESSION['userinfo']['Employee_ID'])){
        $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    }else{
        $Employee_ID = 0;
    }
?>

<?php
    if($Requisition_Item_ID != '' && $Requisition_Item_ID != 0){
        //get requisition id
        if(isset($_SESSION['Pharmacy_Requisition_ID'])){
            $Requisition_ID = $_SESSION['Pharmacy_Requisition_ID'];
        }else{
            $Requisition_ID = 0;
        }
        
	//check if requisition is not submitted
	$check = mysqli_query($conn,"select Requisition_ID from tbl_requisition where Requisition_Status = 'pending' and requisition_id = '$Requisition_ID'") or die(mysqli_error($conn));
	$num_rows = mysqli_num_rows($check);
	
	if($num_rows > 0 ){
	    //delete selected record
	    $delete_details = mysqli_query($conn,"delete from tbl_requisition_items where Requisition_Item_ID = '$Requisition_Item_ID'") or die(mysqli_error($conn));        
	}else{
	    $delete_details = True;
	}
        if($delete_details){
            $total = 0;
            echo '<center><table width = 100% border=0>';
            echo '<tr><td width=4% style="text-align: center;">Sn</td>
                        <td>Item Name</td>
                        <td width=7% style="text-align: center;">Containers</td>
                        <td width=7% style="text-align: center;">Items</td>
                        <td width=7% style="text-align: center;">Quantity</td>
                        <td width=25% style="text-align: center;">Remark</td>
                        <td style="text-align: center;">Remove</td></tr>';
            
            
            $select_Transaction_Items = mysqli_query($conn,"select itm.Product_Name, rqi.Quantity_Required, rqi.Container_Qty, rqi.Items_Qty, rqi.Item_Remark, rqi.Requisition_Item_ID
                                                    from tbl_requisition_items rqi, tbl_items itm where
                                                        itm.Item_ID = rqi.Item_ID and
                                                            rqi.Requisition_ID ='$Requisition_ID'") or die(mysqli_error($conn)); 
        
            $Temp=1;
            while($row = mysqli_fetch_array($select_Transaction_Items)){
                    echo "<tr><td><input type='text' readonly='readonly' value='".$Temp."' style='text-align: center;'></td>";
                    echo "<td><input type='text' readonly='readonly' value='".$row['Product_Name']."'></td>";
                    echo "<td><input type='text' value='".$row['Container_Qty']."' style='text-align: center;'></td>";
                    echo "<td><input type='text' value='".$row['Items_Qty']."' style='text-align: center;'></td>";
                    echo "<td><input type='text' value='".$row['Quantity_Required']."' style='text-align: center;'></td>";
                    echo "<td><input type='text' value='".$row['Item_Remark']."'></td>";
            ?>
                    <td width=6%><input type='button' name='Remove_Item' id='Remove_Item' value='X' class='art-button-green'onclick='Confirm_Remove_Item("<?php echo str_replace("'","",$row['Product_Name']); ?>",<?php echo $row['Requisition_Item_ID']; ?>)'></td>
            <?php
                echo "</tr>";
                $Temp++;
            }
            echo '</table>';
	}
    }
?>