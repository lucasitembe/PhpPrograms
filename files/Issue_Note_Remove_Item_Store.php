<?php
	session_start();
	include("./includes/connection.php");
    include("./functions/issuenotes.php");
	$Process_Status = 'not processed';

	if(isset($_GET['Requisition_Item_ID'])){
		$Requisition_Item_ID = $_GET['Requisition_Item_ID'];
	}else{
		$Requisition_Item_ID = '';
	}

    $Department_Issue = Store_Order_Department_Issuing($_SESSION['Issue_Note']['Requisition_ID']);
    $Department_Requesting = Store_Order_Department_Requesting($_SESSION['Issue_Note']['Requisition_ID']);

	if(isset($_GET['Requisition_Item_ID'])){
		$delete = mysqli_query($conn,"update tbl_requisition_items set Status = 'removed' where Requisition_Item_ID = '$Requisition_Item_ID'") or die(mysqli_error($conn));
	}
?>

<center>
	<table width=100%>
		<tr>
		    <tr><td colspan='9'><hr></td></tr>
		    <td width=3%>Sn</td>
		    <td width=30%>Item Name</td>
            <td width=6% style='text-align: right;'><?php echo substr($Department_Issue['Sub_Department_Name'], 0,15); ?> Balance</td>
            <td width=6% style='text-align: right;'><?php echo substr($Department_Requesting['Sub_Department_Name'], 0,15); ?> Balance</td>
		    <td width=6% style='text-align: right;'>Quantity Required</td>
		    <td width=6% style='text-align: right;'>Containers Issued</td>
		    <td width=6% style='text-align: center;'>Items per container</td>
		    <td width=6% style='text-align: right;'>Quantity Issued</td>
		    <td width=6% style='text-align: right;'>Outstanding</td>
		    
		    <?php if($Process_Status != 'processed'){ ?>
		    <td style='text-align: center; width: 4%;'>Action</td>
		    <?php } ?>
			<tr><td colspan='9'><hr></td></tr>
		</tr>
		
		<?php
			$temp = 1;
			if(isset($_SESSION['Issue_Note']['Requisition_ID']) && isset($_SESSION['Storage_Info']['Sub_Department_ID'])){
				$Sub_Department_ID = $_SESSION['Storage_Info']['Sub_Department_ID'];
				$Requisition_ID = $_SESSION['Issue_Note']['Requisition_ID'];

                $select_items = mysqli_query($conn,"select i.Item_ID, rqi.Requisition_Item_ID, i.Product_Name,rqi.Quantity_Issued, rqi.Quantity_Required, rqi.Item_Remark, rqi.Requisition_Item_ID, rqi.Container_Issued, rqi.Items_Per_Container
													from tbl_requisition_items rqi, tbl_requisition rq, tbl_items i where
													rq.Requisition_ID = rqi.Requisition_ID and
													i.Item_ID = rqi.Item_ID and
													rqi.Requisition_ID = '$Requisition_ID' and
													rqi.Item_Status = 'active' and rqi.Status = 'active'") or die(mysqli_error($conn));
				$no = mysqli_num_rows($select_items);
				if($no > 0){
					while($data = mysqli_fetch_array($select_items)){
						$Item_ID = $data['Item_ID'];
						
						//get item store balance
						$sql_get = mysqli_query($conn,"select Item_Balance from tbl_items_balance where
									Sub_Department_id = '$Sub_Department_ID' and
										Item_ID = '$Item_ID'") or die(mysqli_error($conn));
						$num_of_rows = mysqli_num_rows($sql_get);
						if($num_of_rows > 0){
							while($balance = mysqli_fetch_array($sql_get)){
								$Store_Balance = $balance['Item_Balance']; //Store balance
							}
						}else{
							$Store_Balance = 0; // Store balance
						}

                        //get requested balance
                        $sql_get_req = mysqli_query($conn,"select Item_Balance from tbl_items_balance where
										Sub_Department_ID = '".$Department_Requesting['Sub_Department_ID']."' and
											Item_ID = '$Item_ID'") or die(mysqli_error($conn));
                        $num_of_rows = mysqli_num_rows($sql_get_req);
                        if($num_of_rows > 0){
                            while($balance = mysqli_fetch_array($sql_get_req)){
                                $Store_Balance_Req = $balance['Item_Balance']; //Store balance
                            }
                        }else{
                            $Store_Balance_Req = 0; // Store balance
                        }

						$Quantity_Required = $data['Quantity_Required'];
						$Item_Remark = $data['Item_Remark'];
						$Requisition_Item_ID = $data['Requisition_Item_ID'];
						$Product_Name = $data['Product_Name'];
						
		?>
			<tr>
				<td><input type='text' readonly='readonly' value='<?php echo $temp; ?>'></td>
				<td><input type='text' name='Item_Name' id='Item_Name' readonly='readonly' value='<?php echo $data['Product_Name']; ?>'></td>
				<td><input type='text' name='Store_Balance' readonly='readonly' id='Store_Balance<?php echo $temp; ?>' value='<?php echo $Store_Balance; ?>' style='text-align: right;'></td>
                <td><input type='text' name='Req_Balance' readonly='readonly' id='Req_Balance<?php echo $temp; ?>' value='<?php echo $Store_Balance_Req; ?>' style='text-align: right;'></td>
				<td><input type='text' name='Quantity_Required' readonly='readonly' id='Quantity_Required<?php echo $temp; ?>' value='<?php echo $data['Quantity_Required']; ?>' style='text-align: right;'></td>
				
		<?php  if($Process_Status == 'processed'){ ?>
				<td><input type='text' name='Quantity_Issued[]' id='Quantity_Issued<?php echo $temp; ?>' readonly='readonly' value='<?php echo $data['Quantity_Issued']; ?>' style='text-align: center;'></td>
				<td><input type='text' name='Quantity_Issued[]' id='Quantity_Issued<?php echo $temp; ?>' readonly='readonly' value='<?php echo $data['Quantity_Issued']; ?>' style='text-align: center;'></td>
				<td><input type='text' name='Quantity_Issued[]' id='Quantity_Issued<?php echo $temp; ?>' readonly='readonly' value='<?php echo $data['Quantity_Issued']; ?>' style='text-align: center;'></td>
		<?php }else{ ?>
				<td><input type='text' name='Container_Issued[]' id='Container_Issued<?php echo $temp; ?>'
                           value="<?php if($data['Container_Issued'] > 0){ echo $data['Container_Issued']; } ?>"
                           required='required' autocomplete='off' style='text-align: center;' oninput='numberOnly(this);
                           Generate_Quantity_Issued(<?php echo $temp; ?>,<?php echo $data['Requisition_Item_ID']; ?>)' ></td>

				<td><input type='text' name='Items_Issued[]' id='Items_Issued<?php echo $temp; ?>'
                           value="<?php if($data['Items_Per_Container'] > 0){ echo $data['Items_Per_Container']; } ?>"
                           required='required' autocomplete='off' style='text-align: center;' oninput='numberOnly(this);
                           Generate_Quantity_Issued(<?php echo $temp; ?>,<?php echo $data['Requisition_Item_ID']; ?>)' ></td>

				<td><input type='text' name='Quantity_Issued[]' id='Quantity_Issued<?php echo $temp; ?>'
                           value="<?php if($data['Quantity_Issued'] > 0){ echo $data['Quantity_Issued']; } ?>"
                           readonly='readonly' autocomplete='off' style='text-align: center;' oninput='numberOnly(this);
                           Generate_Container_Items_Issued(<?php echo $temp; ?>,<?php echo $data['Requisition_Item_ID']; ?>)' ></td>
		<?php } ?>
				
				<td>
					<?php if($Process_Status == 'processed'){ ?>
						<input type='text' name='Outstanding_Balance' readonly='readonly' id='<?php echo $temp; ?>'
                               value='<?php echo ($data['Quantity_Required'] - $data['Quantity_Issued']); ?>' style='text-align: right;'>
					<?php }else{ ?>
						<input type='text' name='Outstanding_Balance' readonly='readonly' id='<?php echo $temp; ?>'
                               value='<?php echo ($data['Quantity_Required'] - $data['Quantity_Issued']); ?>' style='text-align: right;'>
					<?php } ?>
					<input type='hidden' name='Array_Size' id='Array_Size' value='<?php echo ($no-1); ?>'>
					<input type='hidden' name='Submit_Issue_Note' id='Submit_Issue_Note' value='True'>
					<input type='hidden' name='Requisition_Item_ID[]' id='Requisition_Item_ID[]' value='<?php echo $data['Requisition_Item_ID']; ?>'>
				</td>
		<?php  if($Process_Status != 'processed'){ ?>
				<td style="text-align: center;"><input type='button' name='Remove' id='Remove' class='art-button-green' value='X' onclick='Confirm_Remove_Item("<?php echo $data['Requisition_Item_ID']; ?>")'></td>
		<?php } ?>
			</tr>
		<?php
						$temp++;							
					}
				}
			}
		?>
	</table>
    </center>