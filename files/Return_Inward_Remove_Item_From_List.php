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
    
    
    if(isset($_GET['Inward_Item_ID'])){
        $Inward_Item_ID = $_GET['Inward_Item_ID'];
    }else{
        $Inward_Item_ID = '';
    }
    
    //get employee id
    if(isset($_SESSION['userinfo']['Employee_ID'])){
        $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    }else{
        $Employee_ID = 0;
    }
?>

<?php
    if($Inward_Item_ID != '' && $Inward_Item_ID != 0){
        //get requisition id
        if(isset($_SESSION['General_Inward_ID'])){
            $Inward_ID = $_SESSION['General_Inward_ID'];
        }else{
            $Inward_ID = 0;
        }
        
	//check if requisition is not submitted
	$check = mysqli_query($conn,"SELECT
                              Inward_ID
                          FROM
                              tbl_return_inward
                          WHERE
                              Inward_Status = 'pending' and Inward_ID = '$Inward_ID'") or die(mysqli_error($conn));
	$num_rows = mysqli_num_rows($check);
	
	if($num_rows > 0 ){
	    //delete selected record
	    $delete_details = mysqli_query($conn,"delete from  tbl_return_inward_items where Inward_Item_ID = '$Inward_Item_ID'") or die(mysqli_error($conn));
	}else{
	    $delete_details = True;
	}
        if($delete_details){
            $total = 0;
            echo '<center><table width = 100% border=0>';
            echo '<tr><td width=4% style="text-align: center; background-color:silver;color:black">Sn</td>
                                            <td style="background-color:silver;color:black">Item Name</td>
                                            <td width=10% style="text-align: center;">UoM</td>
                                            <td width=10% style="text-align: center;">Qty Returned</td>
                                            <td width=18% style="text-align: left;">Remark</td>
                                            <td style="text-align: center;" width="7%">Remove</td></tr>';


            $select_Transaction_Items = mysqli_query($conn,"SELECT
                                                        rii.Inward_Item_ID, rii.Inward_ID, itm.Product_Name,
                                                        rii.Quantity_Returned, rii.Item_Remark, itm.Unit_Of_Measure
                                                     FROM
                                                        tbl_return_inward_items rii, tbl_items itm
                                                     WHERE
                                                        itm.Item_ID = rii.Item_ID AND
                                                        rii.Inward_ID ='$Inward_ID'") or die(mysqli_error($conn));

            $Temp=1;
            while($row = mysqli_fetch_array($select_Transaction_Items)){
                echo "<tr><td><input type='text' readonly='readonly' value='".$Temp."' style='text-align: center;'></td>";
                echo "<td><input type='text' readonly='readonly' value='".$row['Product_Name']."'></td>";
                echo "<td><input type='text' readonly='readonly' value='".$row['Unit_Of_Measure']."' style='text-align: center;'></td>";
                echo "<td><input type='text' readonly='readonly' value='".$row['Quantity_Returned']."' style='text-align: center;'></td>";
                echo "<td><input type='text' id='Item_Remark_Saved' name='Item_Remark_Saved' value='".$row['Item_Remark'].
                    "' onclick='Update_Item_Remark(".$row['Inward_ID'].",this.value)' onkeyup='Update_Item_Remark("
                    .$row['Inward_ID'].",this.value)'></td>";
                ?>
                <td width=6%>
                    <input type='button' name='Remove_Item' id='Remove_Item' value='X' class='art-button-green'
                           onclick='Confirm_Remove_Item("<?php echo str_replace("'","",$row['Product_Name']); ?>",<?php echo $row['Inward_Item_ID']; ?>)'>
                </td>
                <?php
                echo "</tr>";
                $Temp++;
            }
            echo '</table>';
	    }
    }
?>