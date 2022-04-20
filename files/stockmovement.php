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
			echo "<a href='storageandsupply.php?StorageAndSupply=StorageAndSupplyThisPage' class='art-button-green'>BACK</a>";
		}
	}    
?>
<script language="javascript" type="text/javascript">
    function searchPatient(){
	var Branch_ID = document.getElementById('Branch_ID').value;
	var Sub_Department_ID = document.getElementById('Sub_Department_ID').value;
	var from_date = document.getElementById('from_date').value;
	var to_date = document.getElementById('to_date').value;
	var item_name = document.getElementById('item_name').value;
	if (Branch_ID==''||(Sub_Department_ID=='')||(from_date=='')||(to_date=='')||(item_name=='')) {
		if (Branch_ID=='') {
			document.getElementById('Branch_ID').focus();
		}
	}else{
	     document.getElementById('Search_Iframe').src = "stockmovement_Iframe.php?item_name="+item_name+"&Branch_ID="+Branch_ID+"&Sub_Department_ID="+Sub_Department_ID+"&from_date="+from_date+"&to_date="+to_date;	
	}
    }
</script>
<br><br>
<fieldset>
	<legend align=center><b>STORAGE AND SUPPLY WORKS</b></legend>
	<center>Stock Report</center>
	<br>
<table width='100%'>
	<tr>
		<td style='text-align: right'>
		Branch
		</td>
		<td>
			<select id='Branch_ID' name='Branch_ID'>
			<option></option>
			<?php
			$branch_qr = "SELECT * FROM tbl_branches ";
			$branch_result = mysqli_query($conn,$branch_qr);
			while($branch_row = mysqli_fetch_assoc($branch_result)){
				?><option value='<?php echo $branch_row['Branch_ID']; ?>'><?php echo $branch_row['Branch_Name']; ?></option><?php
			}
			?>
			</select>
		</td>
		<td style='text-align: right'>
		Store
		</td>
		<td>
			<select id='Sub_Department_ID' name='Sub_Department_ID'>
				<option></option>
			<?php
			$subdept_qr = "SELECT * FROM tbl_sub_department";
			$subdept_result = mysqli_query($conn,$subdept_qr);
				while($subdept_row = mysqli_fetch_assoc($subdept_result)){
					?><option value='<?php echo $subdept_row['Sub_Department_ID']; ?>'><?php echo $subdept_row['Sub_Department_Name']; ?></option><?php
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
		<td style='text-align: right'>
		Item
		</td>
		<td>
			<input type='text' id='item_name' name='item_name' placeholder='Andika Jina La Kifaa Hapa Kutafuta'>
		</td>
		<td>
			<input type='button' onclick='searchPatient()' value='Search'>
		</td>
	</tr>
</table>
</fieldset>
<fieldset>
	<iframe width='100%' id='Search_Iframe' src='stockmovement_Iframe.php?' height='300px'></iframe>
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
