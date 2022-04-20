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
	@session_destroy(); header("Location: ../index.php?InvalidPrivilege=yes");
    }
    
    
    if(isset($_GET['Open_Balance_Item_ID'])){
	$Open_Balance_Item_ID = $_GET['Open_Balance_Item_ID'];
    }else{
	$Open_Balance_Item_ID = '';
    }
    
    $canPakage = false;
	$display = "style='display:none'";

	if (isset($_SESSION['systeminfo']['enable_receive_by_package']) && $_SESSION['systeminfo']['enable_receive_by_package'] == 'yes') {
	    $canPakage = true;
	    $display = "";
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
        $delete_details = mysqli_query($conn,"delete from tbl_grn_open_balance_items where Open_Balance_Item_ID = '$Open_Balance_Item_ID'") or die(mysqli_error($conn));
        
        if($delete_details){
            $total = 0;
            echo '<center><table width = 100% border=0>';
            echo '<tr><td colspan="11"><hr></td></tr>';
            echo '<tr><td width=4% style="text-align: center;">Sn</td>
                    <td width=30%>Item Name</td>
                    <td width=9% style="text-align: center;">UOM</td>
                    <td '.$display.' width="9%">Containers</td>
                    <td '.$display.' width="9%">Items Per Container</td>
                    <td width=9% style="text-align: center;">Quantity</td>
                    <td width=9% style="text-align: right;">Buying Price</td>
                    <td width=9% style="text-align: right;">Batch No</td>
                    <td width=7% style="text-align: right;display:none">Manuf Date</td>
                    
                    <td width=7% style="text-align: right;">Sub Total</td>
                    <td width=10% style="text-align: right;">Expire Date</td>
                      
                    <td width=5%>Remove</td></tr>';
            echo '<tr><td colspan="11"><hr></td></tr>';
            
	    if(isset($_SESSION['Grn_Open_Balance_ID'])){
		$Grn_Open_Balance_ID = $_SESSION['Grn_Open_Balance_ID'];
	    }else{
		$Grn_Open_Balance_ID = 0;
	    }
	    
	    $select_Open_Balance_Items = mysqli_query($conn,"select ads.reasons,obi.batch_no,obi.Open_Balance_Item_ID, itm.Product_Name, obi.Item_Quantity, obi.Item_Remark, 
	    					obi.Manufacture_Date, obi.Expire_Date, obi.Buying_Price, obi.Container_Qty, obi.Items_Per_Container, itm.Unit_Of_Measure
							    from tbl_grn_open_balance_items obi, tbl_items itm,tbl_reasons_adjustment ads where
								itm.Item_ID = obi.Item_ID and obi.reason_id=ads.reason_id and
								    obi.Grn_Open_Balance_ID ='$Grn_Open_Balance_ID' order by obi.Open_Balance_Item_ID desc") or die(mysqli_error($conn));
									
									
	    $Temp=1;
	    while($row = mysqli_fetch_array($select_Open_Balance_Items)){
			echo "<tr><td><input type='text' readonly='readonly' value='".$Temp."' style='text-align: center;'></td>";
            echo "<td><input type='text' readonly='readonly' value='".$row['Product_Name']."'></td>";
	    echo "<td><input type='text' readonly='readonly' value='" . $row['Unit_Of_Measure'] . "' style='text-align: center;'></td>";
            echo "<td ".$display." ><input type='text' name='Container_Qty' id='Container_Qty' value='".$row['Container_Qty']."' style='text-align: center;' onchange='numberOnly(this)' onkeyup='numberOnly(this)' onkeypress='numberOnly(this)'></td>";
            echo "<td ".$display." ><input type='text' name='Items_Per_Container' id='Items_Per_Container' value='".$row['Items_Per_Container']."' style='text-align: center;' onchange='numberOnly(this)' onkeyup='numberOnly(this)' onkeypress='numberOnly(this)'></td>";
            echo "<td><input type='text' name='Item_Quantity' id='Item_Quantity' value='".$row['Item_Quantity']."' style='text-align: center;' onchange='numberOnly(this)' onkeyup='numberOnly(this)' onkeypress='numberOnly(this)'></td>";
            echo "<td style='text-align: right;'><input type='text' name='Buying_Price' id='Buying_Price' value='".$row['Buying_Price']."' style='text-align: right;' onchange='numberOnly(this)' onkeyup='numberOnly(this)' onkeypress='numberOnly(this)'></td>";
             echo "<td style='text-align: right;'><input type='text' value='".$row['batch_no']."' style='text-align: right;'></td>";
	    echo "<td style='text-align: right;'><input type='text' value='".number_format($row['Item_Quantity'] * $row['Buying_Price'])."' style='text-align: right;'></td>";
            echo "<td style='display:none'><input type='text' style='text-align: right;' readonly='readonly' value='".$row['Manufacture_Date']."'></td>";
            echo "<td><input type='text' style='text-align: right;' readonly='readonly' value='".$row['Expire_Date']."'></td>";
		?>
		      <!--<td width=6%><input type='button' class='art-button-green' onclick="select_clinic_dialog()" value='R'></td>-->
		    <td width=6%><input type='button' name='Remove_Item' id='Remove_Item' class='art-button-green' value='X' onclick='Confirm_Remove_Item("<?php echo str_replace("'","",$row['Product_Name']); ?>",<?php echo $row['Open_Balance_Item_ID']; ?>)'></td>
		<?php
		    echo "</tr>";
                    echo "<tr><td></td><td><input type='text' readonly='readonly' value='" . $row['reasons'] . "'></td></tr>";
		    $Temp++;
		}
		echo '</table>';
		
	}
    }
		?>