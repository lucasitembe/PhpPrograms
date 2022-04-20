<script src='js/functions.js'></script>
<?php
        include("./includes/header.php");
        include("./includes/connection.php");
        unset($_SESSION['Purchase_Order_ID']);
	//get employee name
	if(isset($_SESSION['userinfo']['Employee_Name'])){
		$Employee_Name = $_SESSION['userinfo']['Employee_Name'];
	}else{
		$Employee_Name = 'Unknown Employee';
	}
	
	
	//get employee id
	if(isset($_SESSION['userinfo']['Employee_ID'])){
		$Employee_ID = $_SESSION['userinfo']['Employee_ID'];
	}else{
		$Employee_ID = 0;
	}
	
	
	//get branch id
	if(isset($_SESSION['userinfo']['Branch_ID'])){
		$Branch_ID = $_SESSION['userinfo']['Branch_ID'];
	}else{
		$Branch_ID = 0;
	}
	
	if(!isset($_SESSION['userinfo'])){
		@session_destroy();
		header("Location: ../index.php?InvalidPrivilege=yes");
	}
	if(isset($_SESSION['userinfo'])) {
		if(isset($_SESSION['userinfo']['Storage_And_Supply_Work'])) {
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
        
	if(isset($_SESSION['userinfo'])){
		if($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes'){ 
			echo "<a href='reorderlevelnotification.php?ReorderLevel=ReorderLevelthisPage' class='art-button-green'>BACK</a>";
		}
	}    
?>

<?php
	//get order information if and only if inserted
	if(isset($_SESSION['Purchase_Order_ID'])){
		$Purchase_Order_ID = $_SESSION['Purchase_Order_ID'];
		$select_Order_Details = mysqli_query($conn,"
						    select * from tbl_purchase_order po, tbl_purchase_order_items poi,tbl_supplier sup where
							po.Purchase_Order_ID = poi.Purchase_Order_ID and
								sup.Supplier_ID = po.Supplier_ID and
								po.Purchase_Order_ID = '$Purchase_Order_ID'") or die(mysqli_error($conn));
		$no = mysqli_num_rows($select_Order_Details);
		if($no > 0){
			while($row = mysqli_fetch_array($select_Order_Details)){
				$Purchase_Order_ID = $row['Purchase_Order_ID'];
				$Order_Description = $row['Order_Description'];
				$Created_Date = $row['Created_Date'];
				$Supplier_Name = $row['Supplier_Name'];
			}
		}else{
			$Purchase_Order_ID = '';
			$Order_Description = '';
			$Created_Date = '';
			$Supplier_Name = '';
		}
		
	}else{
		$Purchase_Order_ID = 0;
	}


?>


<form action='#' method='post' name='myForm' id='myForm' >
<!--<br/>-->
<fieldset> <legend align='right'><b>Quick Purchase Order ~ <?php if(isset($_SESSION['Storage'])){ echo $_SESSION['Storage']; }?></b></legend>  
        <table width=100%>
		<tr>
			<td width='10%' style='text-align: right;'>Purchase Number</td>
			<td width='10%'>
				<?php if(isset($_SESSION['Purchase_Order_ID'])){ ?>
					<input type='text' name='Purchase_Number'  id='Purchase_Number' readonly='readonly' value='<?php echo $_SESSION['Purchase_Order_ID']; ?>'>
				<?php }else{ ?>
					<input type='text' name='Purchase_Number'  id='Purchase_Number' value='new'>
				<?php } ?>
			</td>
			
			<td width='10%' style='text-align: right;'>Order Description</td>
			<td>
				<?php
					if(isset($_SESSION['Purchase_Order_ID'])){
						//get order description
						$Temp_Purchase_Order_ID = $_SESSION['Purchase_Order_ID'];
						$select_Order_Description = mysqli_query($conn,"select Order_Description from tbl_purchase_order
											where Purchase_Order_ID = '$Temp_Purchase_Order_ID'") or die(mysqli_error($conn));
						$num = mysqli_num_rows($select_Order_Description);
						if($num > 0){
							while($row = mysqli_fetch_array($select_Order_Description)){
								$Order_Description = $row['Order_Description'];
							}
						}else{
							$Order_Description = '';
						}
				?>
					<input type='text' name='Order_Description' id='Order_Description' value='<?php echo $Order_Description; ?>' onclick='updateOrder_Description()' onkeyup='updateOrder_Description()'>
				<?php }else{ ?>
					<input type='text' name='Order_Description' id='Order_Description'>
				<?php } ?>
			</td> 
			
			<td width='10%' style='text-align: right;'>Purchase Date</td>
			<td width='16%'>
				<?php if(isset($_SESSION['Purchase_Order_ID'])){
					//GET PURCHASE ORDER DATE
					$Temp_Purchase_Order_ID = $_SESSION['Purchase_Order_ID'];
					$select_date = mysqli_query($conn,"select Created_Date from tbl_Purchase_Order where Purchase_Order_ID = '$Temp_Purchase_Order_ID'") or die(mysqli_error($conn));
					$num = mysqli_num_rows($select_date);
					if($num > 0){
						while($row = mysqli_fetch_array($select_date)){
							$Created_Date = $row['Created_Date'];
						}
					}else{
						$Created_Date = '';
					}
				?>
					<input type='text' readonly='readonly' name='Purchase_Date' id='Purchase_Date' value='<?php echo $Created_Date; ?>'>
				<?php }else{ ?>
					<input type='text' readonly='readonly' name='Purchase_Date' id='Purchase_Date'>
				<?php } ?>
			</td> 
		</tr>
		<tr>
			<td width='10%' style='text-align: right;'>Store Requesting</td>
			<td width='16%'>
				<select name='Store_Need' id='Store_Need'>
					<?php
						if(isset($_SESSION['Storage'])){
							echo '<option selected="selected">'.$_SESSION['Storage'].'</option>';
						}else{
							echo '<option selected="selected">Unknown Store Need</option></select>';
						}
					?>
				</select>
			</td>
			
		<td style='text-align: right;'>Supplier</td>
		<td id='Supplier_ID_Area'>
			
			<?php	
				if(isset($_SESSION['Purchase_Order_ID']) && $_SESSION['Purchase_Order_ID'] != '' && $_SESSION['Purchase_Order_ID'] != null){
					$Purchase_Order_ID = $_SESSION['Purchase_Order_ID'];
					//select Supplier_ID via session Purchase_Order_ID
					$select_purchase_id = mysqli_query($conn,"select Supplier_ID from tbl_purchase_order where
										Purchase_Order_ID = '$Purchase_Order_ID'") or die(mysqli_error($conn));
					$no = mysqli_num_rows($select_purchase_id);
					if($no > 0){
						while($row = mysqli_fetch_array($select_purchase_id)){
							$Supplier_ID = $row['Supplier_ID'];
						}
					}else{
						$Supplier_ID = 0;
					}
					
					if($Supplier_ID != 0){
						//get supplier name
						$select_Supplier_Name = mysqli_query($conn,"select Supplier_Name from tbl_Supplier where
											Supplier_ID = '$Supplier_ID'") or die(mysqli_error($conn));
						$no = mysqli_num_rows($select_Supplier_Name);
						if($no > 0){
							while($row = mysqli_fetch_array($select_Supplier_Name)){
								$Supplier_Name = ucfirst($row['Supplier_Name']);
							}
						}else{
							$Supplier_Name = '';
						}
					}else{
						$Supplier_Name = '';
					}
					?>
						<select name='Supplier_ID' id='Supplier_ID' required='required'>
							<option selected='selected' value='<?php echo $Supplier_ID; ?>'><?php echo ucfirst($Supplier_Name); ?></option>
						</select>
					<?php
				}else{
					$select_Supplier = mysqli_query($conn,"select * from tbl_supplier") or die(mysqli_error($conn));
					echo "<select name='Supplier_ID' id='Supplier_ID' required='required'>";
					echo "<option selected='selected'></option>";
					while($row = mysqli_fetch_array($select_Supplier)){
						echo "<option value='".$row['Supplier_ID']."'>".ucfirst($row['Supplier_Name'])."</option>";
					}
					echo "</select>";
				}
			?>
			
		</td>
		    <td style='text-align: right;'>Prepared By&nbsp;</td> 
			<td>
				<input type='text' readonly='readonly' value='<?php echo $Employee_Name; ?>'>
			</td>
		</tr>
        </table> 
</center>
</fieldset>

<?php
	//get sub department id
	if(isset($_SESSION['Storage'])){
		$Sub_Department_Name = $_SESSION['Storage'];
		
		$sql_select = mysqli_query($conn,"select Sub_Department_ID from tbl_Sub_Department where Sub_Department_Name = '$Sub_Department_Name'") or die(mysqli_error($conn));
		$no_rows = mysqli_num_rows($sql_select);
		if($no_rows > 0){
			while($row = mysqli_fetch_array($sql_select)){
				$Sub_Department_ID = $row['Sub_Department_ID'];
			}
		}else{
			$Sub_Department_ID = 0;
		}
	}
?>



<!--<iframe width='100%' src='requisition_items_Iframe.php?Purchase_Order_ID=<?php echo $Purchase_Order_ID; ?>' width='100%' height=250px></iframe>-->
    <fieldset style='overflow-y: scroll; height: 350px;' id='Items_Fieldset_List'>
            <?php
                    echo '<center><table width = 100% border=0>';
                    echo '<tr><td width=4% style="text-align: center;">Sn</td>
                                <td>Item Name</td>
                                    <td width=7% style="text-align: center;">Quantity</td>
                                            <td width=7% style="text-align: right;">Price</td>
                                            <td width=7% style="text-align: center;">Sub Total</td>
                                            <td width=25% style="text-align: center;">Remark</td><td width=5%>Remove</td></tr>';
                    
                    if(isset($_SESSION['Storage'])){
                        $Sub_Department_Name = $_SESSION['Storage'];
            
                        $select_order_items = mysqli_query($conn,"select ib.Item_ID, i.Product_Name, ib.Item_Balance, ib.Reorder_Level from tbl_items_balance ib, tbl_Items i where
								i.Item_ID = ib.Item_ID and
									ib.Item_Balance < ib.Reorder_Level and
										Sub_Department_ID = (select Sub_Department_ID from tbl_Sub_Department where Sub_Department_Name = '$Sub_Department_Name' limit 1)") or die(mysqli_error($conn)); 
			$no2 = mysqli_num_rows($select_order_items);
		    }
                    $Temp = 1; $total = 0;
                    while($row = mysqli_fetch_array($select_order_items)){ 
                        echo "<tr><td><input type='text' readonly='readonly' value='".$Temp."' style='text-align: center;'></td>";
                        echo "<td><input type='text' readonly='readonly' value='".$row['Product_Name']."'></td>";
                        echo "<td><input type='text' name = 'Quantity[]' required='required' style='text-align: center;' onchange='numberOnly(this)' onkeyup='numberOnly(this)' onkeypress='numberOnly(this)'></td>";
                        echo "<td><input type='text' name = 'Price[]' required='required' style='text-align: right;' onchange='numberOnly(this)' onkeyup='numberOnly(this)' onkeypress='numberOnly(this)'></td>";
                        echo "<td><input type='text' style='text-align: right;'></td>";
                        echo "<td><input type='text' name = 'Item_Remark[]'></td>";
			echo "<input type='hidden' name='Array_Size' id='Array_Size' value='".($no2-1)."'>";
                    ?>
                            <td width=6%><input type='button' name='Remove_Item' id='Remove_Item' value='X' class='art-button-green'onclick='Confirm_Remove_Item("<?php echo str_replace("'","",$row['Product_Name']); ?>",<?php echo $row['Item_ID']; ?>)'></td>
                    <?php	
                            
                        echo "</tr>";
                        $Temp++; 
                    }
                    echo '</table>';
            ?>
    </fieldset>
<fieldset>
    <table width=100%>
        <tr>
            <td style='text-align: right;'>
                <input type='submit' name='Submit' id='Submit' value='SUBMIT' class='art-button-green'>
            </td>
        </tr>
    </table>
</fieldset>
</form>


<?php
	if(isset($_POST['Submitted_Grn_Purchase_Order_Form'])){
		$Insert_Status = 'false';
		$Array_Size = $_POST['Array_Size'];
		$Quantity_Supplied = $_POST['Quantity_Supplied']; //array values
		$Qty_Received = $_POST['Qty_Received']; //array values
		$Buying_Price = $_POST['Buying_Price']; //array values
		$Order_Item_ID = $_POST['Order_Item_ID']; //array values
		$Expire_Date = $_POST['Expire_Date'];
		$insert_value = "insert into tbl_grn_purchase_order(
						Created_Date,Created_Date_Time,Employee_ID,
							Supplier_ID,Purchase_Order_ID)
						values((select now()),(select now()),'$Employee_ID',
							'$Supplier_ID','$Purchase_Order_ID')";
							
		for($i = 0; $i <= $Array_Size; $i++){
			if($i == 0){
				$Insert_Status = 'true';
				//insert data into tbl_grn_purchase_order
				$result = mysqli_query($conn,$insert_value);
				if($result){
					$get_Grn_Purchase_Order_ID = mysqli_query($conn,"select Grn_Purchase_Order_ID from
										 tbl_grn_purchase_order where employee_id = '$Employee_ID' and
											Purchase_order_id = '$Purchase_Order_ID' and
												supplier_id = '$Supplier_ID' order by Grn_Purchase_Order_ID desc limit 1") or die(mysqli_error($conn));
					$no3 = mysqli_num_rows($get_Grn_Purchase_Order_ID);
					if($no3 > 0){
						while($data = mysqli_fetch_array($get_Grn_Purchase_Order_ID)){
							$Grn_Purchase_Order_ID = $data['Grn_Purchase_Order_ID'];
							$Insert_Status = 'true';
						}
					}else{
						$Grn_Purchase_Order_ID = 0;
					}
				}else{
					//something to do
					echo "<script>
							alert('Process Fail! Please Try Again');
							document.location = 'grnpurchaseorder.php?Purchase_Order_ID=".$Purchase_Order_ID."&GrnPurchaseOrder=GrnPurchaseOrderThisPage';
						</script>";
				}
				
				if($Grn_Purchase_Order_ID != 0){
					//update tbl_purchase_order_items table
					$update_items = "update tbl_purchase_order_items set
						Quantity_Supplied = '$Quantity_Supplied[$i]',
							Quantity_Received = '$Qty_Received[$i]',
								Buying_Price = '$Buying_Price[$i]',
									Grn_Purchase_Order_ID = '$Grn_Purchase_Order_ID',
										Expire_Date = '$Expire_Date[$i]' where
											Order_Item_ID = '$Order_Item_ID[$i]'";
					
					
					$result2 = mysqli_query($conn,$update_items);
					if(!$result2){
						echo "<script>
							alert('Process Fail! Please Try Again');
							document.location = 'grnpurchaseorder.php?Purchase_Order_ID=".$Purchase_Order_ID."&GrnPurchaseOrder=GrnPurchaseOrderThisPage';
						</script>";
					}else{
						//update tbl_purchase_order (Order status)
						mysqli_query($conn,"update tbl_purchase_order set Order_Status = 'Served', Grn_Purchase_Order_ID = '$Grn_Purchase_Order_ID' where Purchase_Order_ID = '$Purchase_Order_ID'") or die(mysqli_error($conn));
						$Insert_Status = 'true';
					}
				}else{
					echo "<script>
							alert('Process Fail! Please Try Again');
							document.location = 'grnpurchaseorder.php?Purchase_Order_ID=".$Purchase_Order_ID."&GrnPurchaseOrder=GrnPurchaseOrderThisPage';
						</script>";
				}
			}else{
				if($Grn_Purchase_Order_ID != 0){
					//update tbl_purchase_order_items table
					$update_items = "update tbl_purchase_order_items set
						Quantity_Supplied = '$Quantity_Supplied[$i]',
							Quantity_Received = '$Qty_Received[$i]',
								Buying_Price = '$Buying_Price[$i]',
									Grn_Purchase_Order_ID = '$Grn_Purchase_Order_ID',
										Expire_Date = '$Expire_Date[$i]' where
											Order_Item_ID = '$Order_Item_ID[$i]'";
					$result2 = mysqli_query($conn,$update_items);
					if(!$result2){
						echo "<script>
							alert('Process Fail! Please Try Again');
							document.location = 'grnpurchaseorder.php?Purchase_Order_ID=".$Purchase_Order_ID."&GrnPurchaseOrder=GrnPurchaseOrderThisPage';
						</script>";
					}else{
						$Insert_Status = 'true';
						mysqli_query($conn,"update tbl_purchase_order set Order_Status = 'Served', Grn_Purchase_Order_ID = '$Grn_Purchase_Order_ID' where Purchase_Order_ID = '$Purchase_Order_ID'");
					}
				}
			}
		}		
	}
?>


<script type='text/javascript'>
	function Confirm_Remove_Item(Item_Name,Adhoc_Item_ID,Registration_ID){ 
	    var Confirm_Message = confirm("Are you sure you want to remove \n"+Item_Name);
	    if (Confirm_Message == true) {
		if(window.XMLHttpRequest) {
		    myObject = new XMLHttpRequest();
		}else if(window.ActiveXObject){ 
		    myObject = new ActiveXObject('Micrsoft.XMLHTTP');
		    myObject.overrideMimeType('text/xml');
		}
		
		myObject.onreadystatechange = function (){
		    data = myObject.responseText;
		    if (myObject.readyState == 4) {
			document.getElementById('Picked_Items_Fieldset').innerHTML = data;
			update_total(Registration_ID);
			update_Billing_Type();
		    }
		}; //specify name of function that will handle server response........
		
		myObject.open('GET','Adhoc_Remove_Item_From_List.php?Adhoc_Item_ID='+Adhoc_Item_ID+'&Registration_ID='+Registration_ID,true);
		myObject.send();
	    }
	}
</script>

<?php
	include("./includes/footer.php");
?>