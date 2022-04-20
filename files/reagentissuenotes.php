 <script src='js/functions.js'></script><!--<script src="jquery.js"></script>-->
<?php
	include("./includes/header.php");
        include("./includes/connection.php");
        
	if(!isset($_SESSION['userinfo'])){
		@session_destroy();
		header("Location: ../index.php?InvalidPrivilege=yes");
	}
	
	if(isset($_SESSION['userinfo'])){
		if(isset($_SESSION['userinfo']['Storage_And_Supply_Work'])){
			if($_SESSION['userinfo']['Storage_And_Supply_Work'] != 'yes'){
				header("Location: ./index.php?InvalidPrivilege=yes");
			}
		}else{
			header("Location: ./index.php?InvalidPrivilege=yes");
		}
	}else{
		@session_destroy();
		header("Location: ../index.php?InvalidPrivilege=yes");
	}
    
        
        if(!isset($_SESSION['Storage_Info'])){
            header("Location: ./storagesupervisorauthentication.php?InvalidSupervisorAuthentication=yes");
        }
	//get employee name
	if(isset($_SESSION['userinfo']['Employee_Name'])){
		$Employee_Name = $_SESSION['userinfo']['Employee_Name'];
	}else{
		$Employee_Name = '';
	}
	
	//get employee name
	if(isset($_SESSION['userinfo']['Employee_ID'])){
		$Employee_ID = $_SESSION['userinfo']['Employee_ID'];
	}else{
		$Employee_ID = '';
	}
	
	//get branch id
	if(isset($_SESSION['userinfo']['Branch_ID'])){
		$Branch_ID = $_SESSION['userinfo']['Branch_ID'];
	}else{
		$Branch_ID = 0;
	}
	
	//get requisition id
	if(isset($_SESSION['Reagent_Issue_Note']['Requisition_ID'])){
		$Requisition_ID = $_SESSION['Reagent_Issue_Note']['Requisition_ID'];
	}else{
		$Requisition_ID = 0;
	}
	
	if(isset($_SESSION['userinfo'])){
		if($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes'){
			echo "<a href='listofissuenotes.php?IssueNote=IssueNoteThisPage' class='art-button-green'>NEW ISSUE NOTE</a>";
		}
	}
	
	if(isset($_SESSION['userinfo'])){
		if($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes'){
			echo "<a href='listofissuenotesothers.php?IssueNote=IssueNoteThisPage' class='art-button-green'>NEW ISSUE NOTE ~ OTHERS</a>";
		}
	}
	
	if(isset($_SESSION['userinfo'])){
	    if($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes'){ 
		echo "<a href='previousissuenoteslist.php?lform=sentData&page=issue_list' class='art-button-green'>PREVIOUS ISSUES</a>";
	    }
	}
        
	if(isset($_SESSION['userinfo'])){
	    if($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes'){ 
		echo "<a href='previousissuenoteslistothers.php?lform=sentData&page=issue_list' class='art-button-green'>PREVIOUS ISSUES ~ OTHERS</a>";
	    }
	}
        
	if(isset($_SESSION['userinfo'])){
	    if($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes'){ 
		echo "<a href='listofissuenotes.php?IssueNote=IssueNoteThisPage' class='art-button-green'>BACK</a>";
	    }
	}
?>


<?php
	//get sub department name
	if(isset($_SESSION['Storage_Info']['Sub_Department_ID'])){
		$Sub_Department_ID = $_SESSION['Storage_Info']['Sub_Department_ID'];
		//exit($Sub_Department_ID);
		$select = mysqli_query($conn,"select Sub_Department_Name from tbl_Sub_Department where Sub_Department_ID = '$Sub_Department_ID'") or die(mysqli_error($conn));
		$no = mysqli_num_rows($select);
		if($no > 0){
			$row = mysqli_fetch_assoc($select);
			$Sub_Department_Name = $row['Sub_Department_Name'];
		}else{
			$Sub_Department_Name = '';
		}
	}
	
	if(isset($_SESSION['Storage_Info']['Sub_Department_ID'])){
		//get all other details
		$Sub_Department_ID = $_SESSION['Storage_Info']['Sub_Department_ID'];
		//exit($Sub_Department_ID);
		$sql_select = mysqli_query($conn,"select rq.Requisition_ID, rq.Requisition_Description, rq.Sent_Date_Time, sd.Sub_Department_Name, emp.Employee_Name
						from tbl_employee emp, tbl_Sub_Department sd, tbl_reagents_requisition rq where
							emp.Employee_ID = rq.Employee_ID and
								rq.Store_Issue = sd.Sub_Department_ID and
									rq.Requisition_ID = '$Requisition_ID'") or die(mysqli_error($conn));
		$num = mysqli_num_rows($sql_select);
		if($num > 0){
			while($row = mysqli_fetch_array($sql_select)){
				$Requisition_Description = $row['Requisition_Description'];
				$Sent_Date_Time = $row['Sent_Date_Time'];
				$Deparment_Requesting = $row['Sub_Department_Name'];
				$Employee_Prepared = $row['Employee_Name'];
			}
		}else{
			$Requisition_Description = '';
			$Sent_Date_Time = '';
			$Deparment_Requesting = '';
			$Employee_Prepared = '';
		}
	}
	
	
	$Deparment_Requesting = '';
	$Department_Issue = '';
	
	if(isset($_SESSION['Reagent_Issue_Note']['Requisition_ID'])){
		$Requisition_ID = $_SESSION['Reagent_Issue_Note']['Requisition_ID'];
		//get store need
		$select_store_need = mysqli_query($conn,"select Sub_Department_Name from tbl_Sub_Department sd, tbl_reagents_requisition req where
							sd.Sub_Department_ID = req.Store_Need and
								req.Requisition_ID = '$Requisition_ID'") or die(mysqli_error($conn));
		$num_rows = mysqli_num_rows($select_store_need);
		if($num_rows > 0){
			while($row = mysqli_fetch_array($select_store_need)){
				$Deparment_Requesting = $row['Sub_Department_Name'];
			}
		}
	}
	
	if(isset($_SESSION['Reagent_Issue_Note']['Requisition_ID'])){
		$Requisition_ID = $_SESSION['Reagent_Issue_Note']['Requisition_ID'];
		//get store issue
		$select_store_issue = mysqli_query($conn,"select Sub_Department_Name from tbl_Sub_Department sd, tbl_reagents_requisition req where
							sd.Sub_Department_ID = req.Store_Issue and
								req.Requisition_ID = '$Requisition_ID'") or die(mysqli_error($conn));
		$num_rows = mysqli_num_rows($select_store_issue);
		if($num_rows > 0){
			while($row = mysqli_fetch_array($select_store_issue)){
				$Department_Issue = $row['Sub_Department_Name'];
			}
		}
	}	
?>
	
<br/><br/>
<fieldset>
	<legend align=right><b> Issue Note ~ <?php if(isset($_SESSION['Storage'])){ echo $_SESSION['Storage']; } ?></b></legend>
	<table width=100%>
		<tr>
		<?php
			$Process_Status = 'not processed';
			if(isset($_SESSION['Reagent_Issue_Note']['Requisition_ID'])){
				$Requisition_ID = $_SESSION['Reagent_Issue_Note']['Requisition_ID'];
				$Process_Status = 'not processed';
				//check if this requisition already processed
				$check_status = mysqli_query($conn,"select Requisition_Status from tbl_reagents_requisition where Requisition_ID = '$Requisition_ID'") or die(mysqli_error($conn));
				$num = mysqli_num_rows($check_status);
				if($num > 0){
					while($row = mysqli_fetch_array($check_status)){
						$Requisition_Status = $row['Requisition_Status'];
						if($Requisition_Status == 'Served'){
							//get details from tbl_issues
							$get_details = mysqli_query($conn,"select isu.Issue_ID, isu.Issue_Description, isu.Issue_Date_And_Time from tbl_reagent_issues isu
											where Requisition_ID = '$Requisition_ID'") or die(mysqli_error($conn));
							$no = mysqli_num_rows($get_details);
							if($no > 0 ){
								$Process_Status = 'processed';
								while($data2 = mysqli_fetch_array($get_details)){
									$Issue_ID = $data2['Issue_ID'];
									$Issue_Description = $data2['Issue_Description'];
									$Issue_Date = $data2['Issue_Date_And_Time'];
								}
								$Requisition_Status = 'served';
							}else{
								$Issue_ID = '';
								$Issue_Description = '';
								$Issue_Date = '';
							}
						}else{
							$Issue_ID = '';
							$Issue_Description = '';
							$Issue_Date = '';
						}
					}
				}else{
					$Issue_ID = '';
					$Issue_Description = '';
					$Issue_Date = '';
				}
			}else{
				$Issue_ID = '';
				$Issue_Description = '';
				$Issue_Date = '';
			}
		?>
			<td style='text-align: right;' width=12%>Requisition Number</td>
				<?php if(isset($_SESSION['Reagent_Issue_Note']['Requisition_ID'])){ ?>
					<td width=13%><input type='text' readonly='readonly' value='<?php echo $Requisition_ID; ?>' name='Requisition_Number' id='Requisition_Number' size=10></td>
				<?php }else{ ?>
					<td width=13%><input type='text' readonly='readonly' value='' name='Requisition_Number' id='Requisition_Number' size=10></td>
				<?php } ?>
			<td style='text-align: right;' width=10%>Requisition Date</td>
				<?php if(isset($_SESSION['Reagent_Issue_Note']['Requisition_ID'])){ ?>
					<td width=15%><input type='text' readonly='readonly' value='<?php echo $Sent_Date_Time; ?>' name='Requisition_Date' id='Requisition_Date'></td>
				<?php }else{ ?>
					<td width=15%><input type='text' readonly='readonly' value='' name='Requisition_Date' id='Requisition_Date'></td>
				<?php } ?>
			<td style='text-align: right;' width=12%>Issue Number</td>
				<?php if($Process_Status == 'processed'){ ?>
					<td width=13%><input type='text' readonly='readonly' value='<?php echo $Issue_ID; ?>' name='Issue_Number' id='Issue_Number' size=10></td>
				<?php }else{ ?>
					<td width=13%><input type='text' readonly='readonly' value='New' name='Issue_Number' id='Issue_Number' size=10></td>
				<?php } ?>
			<td style='text-align: right;' width=10%>Issue Date</td>
				<?php if($Process_Status == 'processed'){ ?>
					<td width=15%><input type='text' readonly='readonly' value='<?php echo $Issue_Date; ?>' name='Issue_Date' id='Issue_Date'></td>
				<?php }else{ ?>
					<td width=15%><input type='text' readonly='readonly' value='' name='Issue_Date' id='Issue_Date'></td>
				<?php } ?>
		</tr>
		<tr>
			<td style='text-align: right;' width=12%>Department Requesting</td>
				<?php if(isset($_SESSION['Reagent_Issue_Note']['Requisition_ID'])){ ?>
					<td width=13%><input type='text' readonly='readonly' value='<?php echo $Deparment_Requesting; ?>' name='Requisition_Number' id='Requisition_Number' size=10></td>
				<?php }else{ ?>
					<td width=13%><input type='text' readonly='readonly' value='' name='Requisition_Number' id='Requisition_Number' size=10></td>
				<?php } ?>
			<td style='text-align: right;' width=10%>Prepared By</td>
				<?php if(isset($_SESSION['Reagent_Issue_Note']['Requisition_ID'])){ ?>
					<td width=15%><input type='text' readonly='readonly' value='<?php echo $Employee_Prepared; ?>' name='Requisition_Date' id='Requisition_Date'></td>
				<?php }else{ ?>
					<td width=15%><input type='text' readonly='readonly' value='' name='Requisition_Date' id='Requisition_Date'></td>
				<?php } ?>
				
			<td style='text-align: right;' width=12%>Department Issuing</td>
			<td width=13%><input type='text' readonly='readonly' value='<?php echo $Department_Issue; ?>' name='Issue_Number' id='Issue_Number' size=10></td>
			<td style='text-align: right;' width=10%>Issued By</td>
			<td width=15%><input type='text' readonly='readonly' name='Issue_By' id='Issue_By' value='<?php echo $Employee_Name; ?>'></td>
		</tr>
		<tr>
			<td style='text-align: right;'>Requisition Description</td>
				<?php if(isset($_SESSION['Reagent_Issue_Note']['Requisition_ID'])){ ?>
					<td colspan=3><input type='text' readonly='readonly' value='<?php echo $Requisition_Description; ?>' name='Requisition_Description' id='Requisition_Description'></td>
				<?php }else{ ?>
					<td colspan=3><input type='text' readonly='readonly' value='' name='Requisition_Description' id='Requisition_Description'></td>
				<?php } ?>
				
	<form action='#' method='post'>
			<td style='text-align: right;'>Issue Description</td>
				<?php if($Process_Status == 'processed'){ ?>
					<td colspan=3><input type='text' name='Issue_Description' value='<?php echo $Issue_Description; ?>' id='Issue_Description'></td>
				<?php }else{ ?>
					<td colspan=3><input type='text' name='Issue_Description' value='' id='Issue_Description'></td>
				<?php } ?>
		</tr>
	</table>
</fieldset>

<fieldset>
	<table width=100%>
		<tr>
			<?php if($Process_Status == 'processed'){ ?>
				<td style='text-align: right;'><a href='#' class='art-button-green'>PREVIEW ISSUE</a></td>
			<?php }else{ ?>
				<td style='text-align: right;'><input type='submit' name='Submit' id='Submit' value='SUBMIT' class='art-button-green'></td>
			<?php } ?>
		</tr>
	</table>
</fieldset>

<fieldset style='overflow-y: scroll; height: 300px;' id='Items_Fieldset'>
        <center>
		<table width=100%>
			<tr>
			    <td width=5%>Sn</td>
			    <td width=30%>Item Name</td>
			    <td width=12% style='text-align: right;'>Balance Remaining</td>
			    <td width=12% style='text-align: right;'>Store Balance</td>
			    <td width=12% style='text-align: right;'>Quantity Required</td>
			    <td width=12% style='text-align: right;'>Quantity Issued</td>
			    <td width=12% style='text-align: right;'>Outstanding</td>
			    
			    <?php if($Process_Status != 'processed'){ ?>
			    <td style='text-align: center; width: 5%;'>Action</td>
			    <?php } ?>
			</tr>
			
			<?php
				$temp = 1;
				if(isset($_SESSION['Reagent_Issue_Note']['Requisition_ID']) && isset($_SESSION['Storage_Info']['Sub_Department_ID'])){
					$Sub_Department_ID = $_SESSION['Storage_Info']['Sub_Department_ID'];
					$Requisition_ID = $_SESSION['Reagent_Issue_Note']['Requisition_ID'];
					
					$select_items = mysqli_query($conn,"select i.Item_ID, rqi.Requisition_Item_ID, i.Product_Name,rqi.Quantity_Issued, rqi.Quantity_Required, rqi.Item_Remark, rqi.Requisition_Item_ID
									from tbl_reagents_requisition_items rqi, tbl_reagents_requisition rq, tbl_reagents_items i where
										rq.Requisition_ID = rqi.Requisition_ID and
											i.Item_ID = rqi.Item_ID and
												rqi.Requisition_ID = '$Requisition_ID' and
													rqi.Item_Status = 'active'") or die(mysqli_error($conn));
					$no = mysqli_num_rows($select_items);
					if($no > 0){
						while($data = mysqli_fetch_array($select_items)){
							$Item_ID = $data['Item_ID'];
							
							//get item store balance
							$sql_get = mysqli_query($conn,"select Item_Balance from tbl_reagent_items_balance where
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
							$Quantity_Required = $data['Quantity_Required'];
							$Item_Remark = $data['Item_Remark'];
							$Requisition_Item_ID = $data['Requisition_Item_ID'];
							$Product_Name = $data['Product_Name'];
							
			?>
				<tr>
					<td><input type='text' readonly='readonly' value='<?php echo $temp; ?>'></td>
					<td><input type='text' name='Item_Name' id='Item_Name' readonly='readonly' value='<?php echo $data['Product_Name']; ?>'></td>
					<td><input type='text' name='Balance_Remaining' readonly='readonly' id='Balance_Remaining' value='' style='text-align: right;'></td>
					<td><input type='text' name='Store_Balance' readonly='readonly' id='Store_Balance<?php echo $temp; ?>' value='<?php echo $Store_Balance; ?>' style='text-align: right;'></td>
					<td><input type='text' name='Quantity_Required' readonly='readonly' id='Quantity_Required<?php echo $temp; ?>' value='<?php echo $data['Quantity_Required']; ?>' style='text-align: right;'></td>
					
			<?php  if($Process_Status == 'processed'){ ?>
					<td><input type='text' name='Quantity_Issued[]' id='Quantity_Issued<?php echo $temp; ?>' readonly='readonly' value='<?php echo $data['Quantity_Issued']; ?>' style='text-align: right;'></td>
			<?php }else{ ?>
					<td><input type='text' name='Quantity_Issued[]' id='Quantity_Issued<?php echo $temp; ?>' required='required' autocomplete='off' style='text-align: right;' oninput='Update_Outstanding(<?php echo $temp; ?>); numberOnly(this);' ></td>
			<?php } ?>
					
					<td>
						<?php if($Process_Status == 'processed'){ ?>
							<input type='text' name='Outstanding_Balance' readonly='readonly' id='<?php echo $temp; ?>' value='<?php echo ($data['Quantity_Required'] - $data['Quantity_Issued']); ?>' style='text-align: right;'>
						<?php }else{ ?>
							<input type='text' name='Outstanding_Balance' readonly='readonly' id='<?php echo $temp; ?>' value='' style='text-align: right;'>
						<?php } ?>
						<input type='hidden' name='Array_Size' id='Array_Size' value='<?php echo ($no-1); ?>'>
						<input type='hidden' name='Submit_Issue_Note' id='Submit_Issue_Note' value='True'>
						<input type='hidden' name='Requisition_Item_ID[]' id='Requisition_Item_ID[]' value='<?php echo $data['Requisition_Item_ID']; ?>'>
					</td>
			<?php  if($Process_Status != 'processed'){ ?>
					<td><input type='button' name='Remove' id='Remove' class='art-button-green' value='X' onclick='Confirm_Remove_'></td>
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
</fieldset></form>

<script>
	function Update_Outstanding(Temp) {
		var Quantity_Required =parseInt( document.getElementById("Quantity_Required"+Temp).value);
		var Quantity_Issued = parseInt(document.getElementById("Quantity_Issued"+Temp).value);
		var Store_Balance = parseInt(document.getElementById("Store_Balance"+Temp).value);
		
		if (Quantity_Issued == null || Quantity_Issued == '') {
			Quantity_Issued = 0;
		}
		
		if (validate_input(Quantity_Issued , Store_Balance,Temp)) {
			var Outstanding = (Quantity_Required - Quantity_Issued);
		        document.getElementById(Temp).value = Outstanding;
		}
		
		
	}
</script>


<script>
	function validate_input(QuantityIssued , StoreBalance,Temp) {
		var check=true;
		if (QuantityIssued > StoreBalance){
			alert("Invalid Input! Quantity Issued Should Be Less Or Equal To Item Store Balance");
			document.getElementById("Quantity_Issued"+Temp).value = '';
			document.getElementById("Quantity_Issued"+Temp).focus();
		     check=false;;
		}
		
		return check;
	}
</script>

<?php
	//get details sent
	if(isset($_POST['Submit_Issue_Note']) && isset($_SESSION['Reagent_Issue_Note']['Requisition_ID'])){
		$Quantity_Issued = $_POST['Quantity_Issued']; //array values
		$Array_Size = $_POST['Array_Size'];
		if($Quantity_Issued[0] != null && $Quantity_Issued[0] != '' && $Quantity_Issued[$Array_Size] != null && $Quantity_Issued[$Array_Size] != ''){
			
			$Requisition_ID = $_SESSION['Reagent_Issue_Note']['Requisition_ID'];
			$Insert_Status = 'false';
			$Array_Size = $_POST['Array_Size'];
			$Issue_Description = $_POST['Issue_Description'];
			$Quantity_Issued = $_POST['Quantity_Issued']; //array values
			$Requisition_Item_ID = $_POST['Requisition_Item_ID']; //arry values
			
			
			$insert_value = "insert into tbl_reagent_issues(Requisition_ID,Issue_Date_And_Time,Issue_Date,Employee_ID,Branch_ID,Issue_Description)
						values('$Requisition_ID',(select now()),(select now()),'$Employee_ID','$Branch_ID','$Issue_Description')";
								
			for($i = 0; $i <= $Array_Size; $i++){
				if($i == 0){
					$Insert_Status = 'true';
					//insert data into tbl_issues
					$result = mysqli_query($conn,$insert_value) or die(mysqli_error($conn));
					if($result){
						$get_Issue_ID = mysqli_query($conn,"select Issue_ID from
											 tbl_reagent_issues where employee_id = '$Employee_ID' and
												Requisition_ID = '$Requisition_ID'
													order by Issue_ID desc limit 1") or die(mysqli_error($conn));
						$no3 = mysqli_num_rows($get_Issue_ID);
						if($no3 > 0){
							while($data = mysqli_fetch_array($get_Issue_ID)){
								$Issue_ID = $data['Issue_ID'];
								$Insert_Status = 'true';
							}
						}else{
							$Issue_ID = 0;
						}
					}else{
						//something to do
						//die(mysqli_error($conn)."1ttt");
						echo "<script>
								alert('Process Fail! Please Try Again');
								document.location = 'Control_Issue_Note_Session.php?New_Issue_Note=True&Requisition_ID=".$Requisition_ID."';
							</script>";
					}
					
					if($Issue_ID != 0){
						//update tbl_requisition_items table
						$update_items = "update tbl_reagents_requisition_items set
							Quantity_Issued = '$Quantity_Issued[$i]',
								Issue_ID = '$Issue_ID' where
									Requisition_Item_ID = '$Requisition_Item_ID[$i]'";
						
						
						$result2 = mysqli_query($conn,$update_items);
						if(!$result2){
							//die(mysqli_error($conn)."2");
							echo "<script>
								alert('Process Fail! Please Try Again');
								document.location = 'Control_Issue_Note_Session.php?New_Issue_Note=True&Requisition_ID=".$Requisition_ID."';
							</script>";
						}else{
							//update tbl_reagents_requisition (Requisition Status)
							mysqli_query($conn,"update tbl_reagents_requisition set Requisition_Status = 'Served' where Requisition_ID = '$Requisition_ID'") or die(mysqli_error($conn));
							$Insert_Status = 'true';
						}
					}else{
						//die(mysqli_error($conn)."3");
						echo "<script>
								alert('Process Fail! Please Try Again');
								document.location = 'Control_Issue_Note_Session.php?New_Issue_Note=True&Requisition_ID=".$Requisition_ID."';
							</script>";
					}
				}else{
					if($Issue_ID != 0){
						//update tbl_requisition_items table
						$update_items = "update tbl_requisition_items set
							Quantity_Issued = '$Quantity_Issued[$i]',
								Issue_ID = '$Issue_ID' where
									Requisition_Item_ID = '$Requisition_Item_ID[$i]'";
									
						$result2 = mysqli_query($conn,$update_items);
						if(!$result2){
							//die(mysqli_error($conn)."4");
							echo "<script>
								alert('Process Fail! Please Try Again');
								document.location = 'Control_Issue_Note_Session.php?New_Issue_Note=True&Requisition_ID=".$Requisition_ID."';
							</script>";
						}else{
							//update tbl_reagents_requisition (Requisition Status)
							mysqli_query($conn,"update tbl_reagents_requisition set Requisition_Status = 'Served' where Requisition_ID = '$Requisition_ID'") or die(mysqli_error($conn));
							$Insert_Status = 'true';
						}
					}else{
						//die(mysqli_error($conn)."5");
						echo "<script>
								alert('Process Fail! Please Try Again');
								document.location = 'Control_Issue_Note_Session.php?New_Issue_Note=True&Requisition_ID=".$Requisition_ID."';
							</script>";
					}
				}
			}
			echo "<script>
					document.location = 'reagentissuenotes.php?Status=ServedIssueNote&IssueNote=IssueNoteThisPage';
				</script>";
			
		}else{
			echo "<script>
				alert('Please Fill All Required Details!');
					</script>";
		}
	}
?>
<?php
    include("./includes/footer.php");
?>  