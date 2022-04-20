<?php
        include("./includes/header.php");
        include("./includes/connection.php");
        
	//get employee name
	if(isset($_SESSION['userinfo']['Employee_Name'])){
		$Employee_Name = $_SESSION['userinfo']['Employee_Name'];
	}else{
		$Employee_Name = 'Unknown Purchase Officer';
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
	}else{ @session_destroy(); header("Location: ../index.php?InvalidPrivilege=yes"); }
		/*if(isset($_SESSION['userinfo'])){
			if($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes'){ 
				echo "<a href='storageandsupply.php' class='art-button-green'>STORAGE AND SUPPLY</a>";
			}
		}  */          
                
        
        
	if(isset($_SESSION['userinfo'])){
		if($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes')
			{ 
			echo "<a href='purchaseorder.php?status=new&NPO=True&PurchaseOrder=PurchaseOrderThisPage' class='art-button-green'>NEW ORDER</a>";
			}
	}
	if(!isset($_GET['status'])){
		if(isset($_SESSION['userinfo'])){
			if($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes'){ 
				//echo "<a href='purchase_process.php?action=send&ofcer=$requisit_officer&purchase_id=$purchase_id' class='art-button-green'>SEND ORDER</a>";
			}
		}
		if(isset($_SESSION['userinfo'])){
			if($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes') { 
				//echo "<a href='purchase_edit_list.php?action=edit&ofcer=$requisit_officer&purchase_id=$purchase_id' class='art-button-green'>EDIT ORDER</a>";
			}
		}
	}
        if(isset($_SESSION['userinfo'])){
		if($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes'){ 
			echo "<a href='pendingorders.php?PendingOrders=PendingOrdersThisPage' class='art-button-green'>PENDING ORDERS</a>";
		}
	}
	if(isset($_SESSION['userinfo'])){
		if($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes'){ 
			echo "<a href='previousorders.php?PreviousOrder=PreviousOrderThisPage' class='art-button-green'>PREVIOUS ORDERS</a>";
		}
	}
	if(isset($_SESSION['userinfo'])){
		if($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes'){ 
			echo "<a href='stockmovement.php?Stockmovement=Stockmovement' class='art-button-green'>BACK</a>";
		}
	}    
?>
<br><br>
<fieldset>
	<legend align=center><b>STORAGE AND SUPPLY WORKS</b></legend>
	<center>Stock Report</center>
	<?php
		$qr = "SELECT * FROM tbl_items WHERE item_id = ".$_GET['Item_ID'];
		$result = mysqli_query($conn,$qr);
		$row = mysqli_fetch_assoc($result);
		$Product_Name = $row['Product_Name'];
	?>
	<center>Item : <?php echo $Product_Name; ?></center>
	<br>
<table width='100%'>
<td style='text-align: right'>
		Branch
		</td>
		<td>
			<select>
			<option></option>
			<?php
			$branch_qr = "SELECT * FROM tbl_branches ";
			$branch_result = mysqli_query($conn,$branch_qr);
			while($branch_row = mysqli_fetch_assoc($branch_result)){
				?><option><?php echo $branch_row['Branch_Name']; ?></option><?php
			}
			?>
			</select>
		</td>
		<td style='text-align: right'>
		Store
		</td>
		<td>
			<select>
				<option></option>
			<?php
			$subdept_qr = "SELECT * FROM tbl_sub_department";
			$subdept_result = mysqli_query($conn,$subdept_qr);
				while($subdept_row = mysqli_fetch_assoc($subdept_result)){
					?><option><?php echo $subdept_row['Sub_Department_Name']; ?></option><?php
				}
			?>
			</select>
		</td>
		<td style='text-align: right'>
		From Date
		</td>
		<td>
			<input type='text' id='from_date' name='from_date'>
		</td>
		<td style='text-align: right'>
		To Date
		</td>
		<td>
			<input type='text' id='to_date' name='to_date'>
		</td>
	</tr>
</table>
</fieldset>
<fieldset>
	<iframe width='100%' src='stockmovementItemdetails_Iframe.php?Item_ID=<?php echo $_GET['Item_ID']; ?>' height='280px'></iframe>
</fieldset>
<?php
	include("./includes/footer.php");
?>
    <script>
        $(function () { 
            $("#to_date").datepicker({ 
                changeMonth: true,
                changeYear: true,
                showWeek: true,
                showOtherMonths: true,  
                //buttonImageOnly: true, 
                //showOn: "both",
                dateFormat: "yy-mm-dd",
                //showAnim: "bounce"
            });
            
        });
    </script>
    <script>
        $(function () { 
            $("#from_date").datepicker({ 
                changeMonth: true,
                changeYear: true,
                showWeek: true,
                showOtherMonths: true,  
                //buttonImageOnly: true, 
                //showOn: "both",
                dateFormat: "yy-mm-dd",
                //showAnim: "bounce"
            });
            
        });
    </script>