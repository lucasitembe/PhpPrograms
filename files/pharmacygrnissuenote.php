<script src='js/functions.js'></script>
<?php
        include("./includes/header.php");
        include("./includes/connection.php");
	 
	$Grn_Status = '';
	$Grn_Issue_Note_ID = '';
	
	$Insert_Status = 'false';
	
	if(!isset($_SESSION['userinfo'])){
		@session_destroy();
		header("Location: ../index.php?InvalidPrivilege=yes");
	}
	
	if(isset($_SESSION['userinfo'])){
            $branch_ID=$_SESSION['userinfo']['Branch_ID'];
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

	if(isset($_SESSION['userinfo'])){
		if($_SESSION['userinfo']['Pharmacy'] == 'yes'){
			//get number of pending request
			if(isset($_SESSION['Pharmacy_ID'])){
				$Sub_Department_ID = $_SESSION['Pharmacy_ID'];
			}else{
				$Sub_Department_ID = '';
			}
			
			$select_Order_Number = mysqli_query($conn,"select rq.Requisition_Description, isu.Issue_ID, isu.Issue_Date_And_Time, rq.Requisition_ID, rq.Created_Date_Time, rq.Store_Need, rq.Sent_Date_Time, sd.Sub_Department_Name, emp.Employee_Name from
													tbl_requisition rq, tbl_sub_department sd, tbl_employee emp, tbl_issues isu where
													rq.store_issue = sd.sub_department_id and
													emp.employee_id = rq.employee_id and
													rq.requisition_status = 'served' and
													isu.Requisition_ID = rq.Requisition_ID and
													rq.Store_Need = '$Sub_Department_ID'") or die(mysqli_error($conn));
			$number = mysqli_num_rows($select_Order_Number);
			
			echo "<a href='pharmacygrnissuenotelist.php?PharmacyGrnIssueNoteList=PharmacyGrnIssueNoteListThisPage' class='art-button-green'>NEW GRN&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style='background-color: red; border-radius: 8px; color: white; padding: 6px;'>".$number."</span></a>";
		}
	}

	if(isset($_SESSION['userinfo'])){
		if($_SESSION['userinfo']['Pharmacy'] == 'yes'){
			echo "<a href='pharmacypreviousgrnlist.php?PreviousGrn=PreviousGrnThisPage' class='art-button-green'>PREVIOUS GRN</a>";
		}
	}
	
	if(isset($_SESSION['userinfo'])){
		if($_SESSION['userinfo']['Pharmacy'] == 'yes'){
			echo "<a href='pharmacygoodreceivingnote.php?GoodReceivingNote=GoodReceivingNoteThisPage' class='art-button-green'>BACK</a>";
		}
	}
?>


<?php
	//get all important Requisition details
	if(isset($_SESSION['Issue_ID'])){
		$Issue_ID = $_SESSION['Issue_ID'];
	}else{
		$Issue_ID = 0;
	}
	
	$select = mysqli_query($conn,"select rq.Requisition_ID, rq.Sent_Date_Time, rq.Requisition_Status, rq.Requisition_Description, emp.Employee_Name, rq.Employee_ID, rq.Store_Need, rq.Store_Issue
				from tbl_requisition rq, tbl_requisition_items ri, tbl_employee emp where
					rq.Requisition_ID = ri.Requisition_ID and
						emp.Employee_ID = rq.Employee_ID and
							Issue_ID = '$Issue_ID' group by Issue_ID") or die(mysqli_error($conn));
	$no = mysqli_num_rows($select);
	
	if($no > 0){
		while($data = mysqli_fetch_array($select)){
			$Requisition_ID = $data['Requisition_ID'];
			$Sent_Date_Time = $data['Sent_Date_Time'];
			$Requisition_Description = $data['Requisition_Description'];
			$Employee_Name = $data['Employee_Name'];
			$Store_Need = $data['Store_Need'];
			$Store_Issue = $data['Store_Issue'];
			$Requisition_Status = $data['Requisition_Status'];
			$Temp_Employee_ID = $data['Employee_ID']; //employee id (prepare the requisition)
		}
	}else{
		$Requisition_ID = 0;
		$Sent_Date_Time = '';
		$Requisition_Description = '';
		$Employee_Name = '';
		$Store_Need = 0;
		$Store_Issue = 0;
		$Requisition_Status = '';
		$Temp_Employee_ID = 0; //employee id (prepare the requisition)
	}
	
	//get sub department names and employee prepare selected requisition
	if($Requisition_ID != 0){
		//get store need
		$select = mysqli_query($conn,"select Sub_Department_Name from tbl_sub_department where Sub_Department_ID = '$Store_Need'") or die(mysqli_error($conn));
		$num = mysqli_num_rows($select);
		if($num > 0){
			while($row = mysqli_fetch_array($select)){
				$Temp_Store_Need = $row['Sub_Department_Name'];
			}
		}else{
			$Temp_Store_Need = '';
		}
		
		
		//get store issue
		$select = mysqli_query($conn,"select Sub_Department_Name from tbl_sub_department where Sub_Department_ID = '$Store_Issue'") or die(mysqli_error($conn));
		$num = mysqli_num_rows($select);
		if($num > 0){
			while($row = mysqli_fetch_array($select)){
				$Temp_Store_Issue = $row['Sub_Department_Name'];
			}
		}else{
			$Temp_Store_Issue = '';
		}
		
		
		//get employee prepare 
		$select = mysqli_query($conn,"select Employee_Name from tbl_employee where Employee_ID = '$Temp_Employee_ID'") or die(mysqli_error($conn));
		$num = mysqli_num_rows($select);
		if($num > 0){
			while($row = mysqli_fetch_array($select)){
				$Temp_Employee_Name = $row['Employee_Name'];
			}
		}else{
			$Temp_Employee_Name = '';
		}
		
		
	}else{
		$Temp_Store_Issue = '';
		$Temp_Store_Need = '';
		$Temp_Employee_Name = '';
	}
	
	//get employee name and id
	//employee id
	if(isset($_SESSION['userinfo']['Employee_ID'])){
		$Employee_ID = $_SESSION['userinfo']['Employee_ID'];
	}else{
		$Employee_ID = 0;
	}
	
	//employee name
	if(isset($_SESSION['userinfo']['Employee_Name'])){
		$Employee_Name = $_SESSION['userinfo']['Employee_Name'];
	}else{
		$Employee_Name = '';
	}
?>

<?php
	//get Issue ID then will help to search issue details
	if(isset($_SESSION['Issue_ID'])){
		$Issue_ID = $_SESSION['Issue_ID'];
	}else{
		$Issue_ID = 0;
	}
	
	$select_grn_details = mysqli_query($conn,"select * from tbl_grn_issue_note
						where Issue_ID = '$Issue_ID'") or die(mysqli_error($conn));
	$nop = mysqli_num_rows($select_grn_details);
	$Control = 'False';
	if($nop > 0){
		$Control = 'True';
		while($Grn_row = mysqli_fetch_array($select_grn_details)){
			$Created_Date_Time = $Grn_row['Created_Date_Time'];
			$Grn_Issue_Note_ID = $Grn_row['Grn_Issue_Note_ID'];
			$Issue_Description = $Grn_row['Issue_Description'];
		}
	}else{
		$Control = 'False';
		$Grn_Issue_Note_ID = 'New';
		$Created_Date_Time = '';
		$Issue_Description = '';
	}
?>



<fieldset>
	<legend align="right">
		<?php if(isset($_SESSION['Pharmacy'])){ echo $_SESSION['Pharmacy']; }?>, GRN Against Issue Note
	</legend>

	<table width=100%>
		<tr>
			<td width='10%' style='text-align: right;'>Requisition Number</td>
			<td width='26%'>
				<input type='text' name='order_id'  id='order_id' value='<?php echo $Requisition_ID;?>' readonly='readonly'>
			</td>
			<td width='10%' style='text-align: right;'>GRN Number</td>
			<td width='16%'>
				<input type='text' name='grn_number'  id='grn_number' value='<?php if(strtolower($Requisition_Status) == 'received'){ echo $Grn_Issue_Note_ID; } ?>' readonly='readonly'>
			</td>
		</tr>                               
		<tr>
			<td width='10%' style='text-align: right;'>Sent Date & Time</td>
			<td width='26%'>
				<input type='text' name='created_date'  id='created_date' readonly='readonly' value="<?php echo $Sent_Date_Time; ?>" >
			</td>
			<td style='text-align: right;'>GRN Date & Time</td>
			<td width='16%'>
				<input type='text' name='grn_date'  id='grn_date' readonly='readonly' value='<?php if(strtolower($Requisition_Status) == 'received'){ echo $Created_Date_Time; } ?>'>
			</td>
		</tr>                              
		<tr>
			<td width='10%' style='text-align: right;'>Received From</td>
			<td width='26%'>
				<input type='text' name='created_date'  id='created_date' readonly='readonly' value="<?php echo $Temp_Store_Issue; ?>" >
			</td>
			<td style='text-align: right;'>Current Location</td>
			<td width='16%'><input type='text' name='grn_date'  id='grn_date' readonly='readonly' value='<?php echo $Temp_Store_Need; ?>'></td>
		</tr> 
		<tr>
			<td width='10%' style='text-align: right;'>Requisition Description</td>
			<td width='26%'><input type='text' name='Supplier_Name'  id='Supplier_Name' value='<?php echo $Requisition_Description; ?>' readonly='readonly'></td>
			
			<td style='text-align: right;'>Received By</td>
			<?php
				//get employee name from the session
				if(isset($_SESSION['userinfo']['Employee_Name'])){
					$Receiver = $_SESSION['userinfo']['Employee_Name'];
				}else{
					$Receiver = '';
				}
			?>
			<td width='26%'>
				<input type='text' name='Receiver_Name'  id='Receiver_Name' readonly='readonly' value='<?php echo $Receiver; ?>'  >
			</td>
		</tr>
<form action='#' method='post' name='myForm' id='myForm' >
			<tr>
				<td style='text-align: right;'>Prepared By</td>
				<td><input type='text' name='Employee_Name' id='Employee_Name' value='<?php echo $Temp_Employee_Name; ?>' readonly='readonly'></td>
				<td style='text-align: right;'>GRN Description</td>
				<td>
					<input type='text' name='Issue_Description' id='Issue_Description' value='<?php echo $Issue_Description; ?>'>
				</td>
			</tr>
		</table>
        </center>
</fieldset>
	
	
	

	

<?php
	if(isset($_POST['Subbmit_Grn_Issue_Note'])){
		$Insert_Status = 'false';
		
		$Array_Size = $_POST['Array_Size'];
		$Qty_Received = $_POST['Qty_Received']; //array values
		$Item_ID = $_POST['Item_ID']; //array values
		$Requisition_Item_ID = $_POST['Requisition_Item_ID']; //array values
		$Issue_Description = $_POST['Issue_Description'];
		
		$insert_value = "insert into tbl_grn_issue_note(
						Created_Date,Created_Date_Time,Employee_ID,
							Issue_ID,Issue_Description)
						values((select now()),(select now()),'$Employee_ID',
							'$Issue_ID','$Issue_Description')";
							
		for($i = 0; $i <= $Array_Size; $i++){
			if($i == 0){
				$Insert_Status = 'true';
				//insert data into tbl_grn_issue_note
				$result = mysqli_query($conn,$insert_value);
				if($result){
					$get_Grn_Issue_Note_ID = mysqli_query($conn,"select Grn_Issue_Note_ID from
										 tbl_grn_issue_note where employee_id = '$Employee_ID' and
											Issue_ID = '$Issue_ID' order by Grn_Issue_Note_ID desc limit 1") or die(mysqli_error($conn));
					$no3 = mysqli_num_rows($get_Grn_Issue_Note_ID);
					if($no3 > 0){
						while($data = mysqli_fetch_array($get_Grn_Issue_Note_ID)){
							$Grn_Issue_Note_ID = $data['Grn_Issue_Note_ID'];
							$Insert_Status = 'true';
						}
					}else{
						$Grn_Issue_Note_ID = 0;
					}
				}else{
					//something to do
					echo "<script>
							alert('Process Fail! Please Try Again');
							document.location = 'pharmacygrnissuenote.php?GrnIssueNote=GrnIssueNoteThisPage';
						</script>";
				}
				
				if($Grn_Issue_Note_ID != 0){
					//update tbl_purchase_order_items table
					$update_items = "update tbl_requisition_items set
								Quantity_Received = '$Qty_Received[$i]',
									Item_Status = 'received' where
										Requisition_Item_ID = '$Requisition_Item_ID[$i]' and
											Issue_ID = '$Issue_ID'";
					
					
					$result2 = mysqli_query($conn,$update_items);
					if(!$result2){
						echo "<script>
							alert('Process Fail! Please Try Again');
							document.location = 'pharmacygrnissuenote.php?GrnIssueNote=GrnIssueNoteThisPage';
						</script>";
					}else{
						//update Requisition
						mysqli_query($conn,"update tbl_requisition set Requisition_Status = 'Received' where Requisition_ID = '$Requisition_ID'") or die(mysqli_error($conn));
						$Insert_Status = 'true';
						
						//get pre balance
						$select_pre_balance = mysqli_query($conn,"select Item_Balance from tbl_items_balance where Item_ID = '$Item_ID[$i]' and Sub_Department_ID = '$Store_Need'") or die(mysqli_error($conn));
						$nm = mysqli_num_rows($select_pre_balance);
						if($nm > 0){
							while ($dt = mysqli_fetch_array($select_pre_balance)) {
								$Pre_Balance = $dt['Item_Balance'];
							}
						}else{
							$Pre_Balance = 0;
						}

						//update balance by adding items received
						$add = mysqli_query($conn,"update tbl_items_balance set Item_Balance = Item_Balance + '$Qty_Received[$i]' where Item_ID = '$Item_ID[$i]' and Sub_Department_ID = '$Store_Need'") or die(mysqli_error($conn));
						
						if($add){
							//update balance by adding items received
							//$minus = mysqli_query($conn,"update tbl_items_balance set Item_Balance = Item_Balance - '$Qty_Received[$i]' where Item_ID = '$Item_ID[$i]' and Sub_Department_ID = '$Store_Issue'") or die(mysqli_error($conn));

	                        //insert data into tbl_stock_ledger_controler for auditing
	                        $insert_audit = mysqli_query($conn,"insert into tbl_stock_ledger_controler(
                                                        Item_ID, Sub_Department_ID, Movement_Type, 
                                                        Pre_Balance, Post_Balance, Movement_Date, Movement_Date_Time, Document_Number)

													values('$Item_ID[$i]','$Store_Need','GRN Agains Issue Note',
                                                            '$Pre_Balance',($Pre_Balance + $Qty_Received[$i]),(select now()),(select now()),'$Grn_Issue_Note_ID')") or die(mysqli_error($conn));
                        
	                        if($Qty_Received[$i] > 0){
	                            //update item status to available
	                            mysqli_query($conn,"update tbl_items set Status = 'Available' where Item_ID = '$Item_ID[$i]'") or die(mysqli_error($conn));
	                        }
						}
					}
				}else{
					echo "<script>
							alert('Process Fail! Please Try Again');
							document.location = 'pharmacygrnissuenote.php?GrnIssueNote=GrnIssueNoteThisPage';
						</script>";
				}
			}else{
				if($Grn_Issue_Note_ID != 0){
					//update tbl_purchase_order_items table
						$update_items = "update tbl_requisition_items set
							Quantity_Received = '$Qty_Received[$i]',
								Item_Status = 'received' where
									Requisition_Item_ID = '$Requisition_Item_ID[$i]' and
										Issue_ID = '$Issue_ID'";
					$result2 = mysqli_query($conn,$update_items);
					
					if(!$result2){
						echo "<script>
							alert('Process Fail! Please Try Again');
							document.location = 'pharmacygrnissuenote.php?GrnIssueNote=GrnIssueNoteThisPage';
						</script>";
					}else{
						$Insert_Status = 'true';
						//update Requisition
						mysqli_query($conn,"update tbl_requisition set Requisition_Status = 'Received' where Requisition_ID = '$Requisition_ID'") or die(mysqli_error($conn));
						$Insert_Status = 'true';
						
						//get pre balance
						$select_pre_balance = mysqli_query($conn,"select Item_Balance from tbl_items_balance where Item_ID = '$Item_ID[$i]' and Sub_Department_ID = '$Store_Need'") or die(mysqli_error($conn));
						$nm = mysqli_num_rows($select_pre_balance);
						if($nm > 0){
							while ($dt = mysqli_fetch_array($select_pre_balance)) {
								$Pre_Balance = $dt['Item_Balance'];
							}
						}else{
							$Pre_Balance = 0;
						}

						//update balance by adding items received
						$add = mysqli_query($conn,"update tbl_items_balance set Item_Balance = Item_Balance + '$Qty_Received[$i]' where Item_ID = '$Item_ID[$i]' and Sub_Department_ID = '$Store_Need'") or die(mysqli_error($conn));
						
						if($add){
							//update balance by adding items received
							//$minus = mysqli_query($conn,"update tbl_items_balance set Item_Balance = Item_Balance - '$Qty_Received[$i]' where Item_ID = '$Item_ID[$i]' and Sub_Department_ID = '$Store_Issue'") or die(mysqli_error($conn));
							
							//insert data into tbl_stock_ledger_controler for auditing
	                        $insert_audit = mysqli_query($conn,"insert into tbl_stock_ledger_controler(
                                                        Item_ID, Sub_Department_ID, Movement_Type, 
                                                        Pre_Balance, Post_Balance, Movement_Date, Movement_Date_Time, Document_Number)

													values('$Item_ID[$i]','$Store_Need','GRN Agains Issue Note',
                                                            '$Pre_Balance',($Pre_Balance + $Qty_Received[$i]),(select now()),(select now()),'$Grn_Issue_Note_ID')") or die(mysqli_error($conn));

	                        if($Qty_Received[$i] > 0){
	                            //update item status to available
	                            mysqli_query($conn,"update tbl_items set Status = 'Available' where Item_ID = '$Item_ID[$i]'") or die(mysqli_error($conn));
	                        }
						}
					}
				}
			}
		}		
	}
?>
	

	
<fieldset>
	<table width=100%>
		<tr>
			<?php if(strtolower($Grn_Status) != 'served'){ ?>
				<td style='text-align: right;'>
					<?php if(strtolower($Requisition_Status) == 'received'){ ?>
						<a href='grnissuenotereport.php?Issue_ID=<?php echo $Issue_ID; ?>&PharmacyGrnIssueNoteReport=PharmacyGrnIssueNoteReportThisPage' class='art-button-green' target="_blank">PREVIEW REPORT</a>
					<?php }else{ ?>
						<input type='submit' name='submit' id='submit' value='SUBMIT' class='art-button-green'>
					<?php } ?>
				</td>
			<?php }else{ ?>
				<td style='text-align: right;'>
					<a href='grnpurchaseorderreport.php?Grn_Purchase_Order_ID=<?php echo $Grn_Issue_Note_ID; ?>&GrnIssueNote=GrnIssueNoteThisPage' target='_Blank' class='art-button-green'>Preview GRN </a>
				</td>
			<?php } ?>
		</tr>
	</table>
</fieldset>
<fieldset style='overflow-y: scroll; height: 240px;'>   
	<center>
		<table width=100%>
			<tr>
				<td width=3% style='text-align: center;'>Sn</td>
				<td>Item Name</td>
				<td width=9% style='text-align: right;'>Quantity Required</td>
				<td width=9% style='text-align: right;'>Container Issued</td>
				<td width=9% style='text-align: right;'>Items per Container</td>
				<td width=9% style='text-align: right;'>Quantity Issued</td>
				<td width=9% style='text-align: right;'>Quantity Received</td>
				<td width=15% style='text-align: left;'>Item Description</td>
		</tr>
		<?php
		//get list of item ordered
		$select_items = mysqli_query($conn,"select i.Product_Name, rqi.Quantity_Issued, rqi.Quantity_Required, rqi.Quantity_Issued, rqi.Quantity_Received, i.Item_ID, rqi.Requisition_Item_ID, rqi.Item_Remark, rqi.Container_Issued, rqi.Items_Per_Container from
						tbl_items i, tbl_requisition_items rqi where
							i.Item_ID = rqi.Item_ID and
								rqi.Issue_ID = '$Issue_ID'") or die(mysqli_error($conn));
		$no2 = mysqli_num_rows($select_items);
		$temp = 1;
		if($no2 > 0){
			while($row = mysqli_fetch_array($select_items)){
			 	echo "<tr><td><input type='text' value='".$temp."'  readonly='readonly' style='text-align: center;'></td>";
				echo "<td><input type='text' value='".$row['Product_Name']."' readonly='readonly'>";
				echo "<input type='hidden' value='".$row['Item_ID']."' name='Item_ID[]' id='Item_ID[]'></td>";
				echo "<input type='hidden' value='".$row['Requisition_Item_ID']."' name='Requisition_Item_ID[]' id='Requisition_Item_ID[]'></td>";
				echo "<td><input type='text' value='".$row['Quantity_Required']."' readonly='readonly' style='text-align: right;'></td>";
				echo "<td><input type='text' value='".$row['Container_Issued']."' readonly='readonly' style='text-align: right;'></td>";
				echo "<td><input type='text' value='".$row['Items_Per_Container']."' readonly='readonly' style='text-align: right;'></td>";
				echo "<td><input type='text' name='Quantity_Issued' id='Quantity_Issued".$temp."' value='".$row['Quantity_Issued']."' readonly='readonly' style='text-align: right;'></td>";
				
                                $qry=mysqli_query($conn,"SELECT Editable_Quantity_Received FROM tbl_system_configuration WHERE Branch_ID='$branch_ID'");
                                $result= mysqli_fetch_assoc($qry);
                                
                                if($result['Editable_Quantity_Received']=='no'){// echo $Grn_Issue_Note_ID; exit();
                                   if($Grn_Issue_Note_ID == 'New'){
					echo "<td><input type='text' name='Qty_Received[]' id='Qty_Received".$temp."' readonly='readonly' autocomplete='off' value='".$row['Quantity_Issued']."' style='text-align: right;' required='required' onchange='numberOnly(this)' onkeyup='numberOnly(this)' onkeypress='numberOnly(this)'  oninput='Validate_Quantity_Received(".$temp.")'></td>";
                                    }else{
					echo "<td><input type='text' name='Qty_Received[]' id='Qty_Received".$temp."' autocomplete='off' value='".$row['Quantity_Received']."' style='text-align: right;' readonly='readonly'></td>";
                                    } 
                                    
                                }  else {
                                   if($Grn_Issue_Note_ID == 'New'){
					echo "<td><input type='text' name='Qty_Received[]' id='Qty_Received".$temp."' autocomplete='off' value='' style='text-align: right;' required='required' onchange='numberOnly(this)' onkeyup='numberOnly(this)' onkeypress='numberOnly(this)'  oninput='Validate_Quantity_Received(".$temp.")'></td>";
                                    }else{
					echo "<td><input type='text' name='Qty_Received[]' id='Qty_Received".$temp."' autocomplete='off' value='".$row['Quantity_Received']."' style='text-align: right;' readonly='readonly'></td>";
                                    }
                                    
                                }
	
				echo "<td><input type='text' name='Item_Remark' id='Item_Remark' value='".$row['Item_Remark']."' readonly='readonly'>";
				echo "<input type='hidden' name='Array_Size' id='Array_Size' value='".($no2-1)."'>";
				echo "<input type='hidden' name='Subbmit_Grn_Issue_Note' id='Subbmit_Grn_Issue_Note' value='true'>";
				echo "</tr>";
				$temp++;
			}
		}
	?>
		</table>
		</form>   
        </center>
</fieldset>
</form>




<script>
	function Validate_Quantity_Received(Temp) {
		var Quantity_Received = parseInt(document.getElementById("Qty_Received"+Temp).value);
		var Quantity_Issued = parseInt(document.getElementById("Quantity_Issued"+Temp).value);
		
		if (Quantity_Received == null || Quantity_Received == '') {
			Quantity_Received = 0;
		}
		
		if (Quantity_Received > Quantity_Issued) {
			alert("Invalid Input! Quantity Received Should Be Less Or Equal To Quantity Issued");
			document.getElementById("Qty_Received"+Temp).value = '';
			document.getElementById("Qty_Received"+Temp).focus();
		}
	}
</script>

<?php
	if($Insert_Status == 'true'){
		echo "<script>
			alert('Process Successful');
			document.location = 'pharmacygrnissuenote.php?GrnIssueNote=GrnIssueNoteThisPage';
			</script>";
	}
?>
<?php
    include("./includes/footer.php");
?>