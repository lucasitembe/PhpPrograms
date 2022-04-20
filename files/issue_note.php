<script src='js/functions.js'></script>
<?php

        include("./includes/header.php");
        include("./includes/connection.php");
        $requisit_officer=$_SESSION['userinfo']['Employee_Name'];
	
	$Issue_Status = '';
	$Requisition_ID = '';
	
	$Insert_Status = 'fasle';    
	if(!isset($_SESSION['userinfo'])){
		@session_destroy();
		header("Location: ../index.php?InvalidPrivilege=yes");
	}
	if(isset($_SESSION['userinfo'])){
		if(isset($_SESSION['userinfo']['Storage_And_Supply_Work'])){
			if($_SESSION['userinfo']['Storage_And_Supply_Work'] != 'yes'){ header("Location: ./index.php?InvalidPrivilege=yes");} 
		}else{
			header("Location: ./index.php?InvalidPrivilege=yes");
		}
	}else{
		@session_destroy();
		header("Location: ../index.php?InvalidPrivilege=yes");
	}

        
	if(isset($_SESSION['userinfo'])){
		if($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes'){
			//get number of pending request
				if(isset($_SESSION['Storage'])){
					$Sub_Department_Name = $_SESSION['Storage'];
				}else{
					$Sub_Department_Name = '';
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
                        echo "<a href='a2_list.php?PreviousGrnList=PreviousGrnListThisPage' class='art-button-green'>ISSUE NOTE</a>";
                }
        }
        if(isset($_SESSION['userinfo'])){
                if($_SESSION['userinfo']['Storage_And_Supply_Work'] == 'yes'){ 
                        echo "<a href='goodreceivednote.php?GoodReceivedNote=GoodReceivedNoteThisPage' class='art-button-green'>BACK</a>";
                }
        }


//SELECT * FROM `tbl_sub_department` WHERE `Sub_Department_ID`, `Department_ID`, `Sub_Department_Name`
//SELECT * FROM `tbl_requisition_items.Item_ID` WHERE `Requisition_Item_ID`, `Requisition_ID`, `Quantity_Required`, `Item_Remark`, `Item_ID`, `Quantity_Issued`, `Quantity_Received`, `Issue_ID`
//SELECT * FROM `tbl_requisition` WHERE `Requisition_ID`, `Requisition_Description`, `Created_Date_Time`, `Created_Date`, `Sent_Date_Time`, `Store_Need`, `Store_Issue`, `Employee_ID`, `Requisition_Status`, `Branch_ID`
//SELECT * FROM `tbl_employee` WHERE `Employee_ID`, `Employee_Name`, `Employee_Type`, `Employee_Number`, `Employee_Title`, `Employee_Job_Code`, `Employee_Branch_Name`, `Department_ID`, `Account_Status`

$Issue_Date="";
$Issue_ID="";
	$sent_date = '';
	$Requisition_ID=$_GET['Requisition_ID'];
	$get_requisition_data = "SELECT * FROM tbl_requisition inner join tbl_requisition_items on tbl_requisition_items.Requisition_ID=tbl_requisition.Requisition_ID 
	inner join tbl_sub_department on tbl_sub_department.Sub_Department_ID=tbl_requisition.Store_Need
	inner join tbl_employee on tbl_employee.Employee_ID=tbl_requisition.Employee_ID
	WHERE tbl_requisition.Requisition_ID = '$Requisition_ID'";

	$requisition_data = mysqli_query($conn,$get_requisition_data) or die(mysqli_error($conn));
	
	while($datax = mysqli_fetch_assoc($requisition_data)){
		$sent_date = $datax['Created_Date'];
		$Store_Need= $datax['Sub_Department_Name'];
		$Employee_Name = $datax['Employee_Name'];
		$Employee_ID=$datax['Employee_ID'];
		
	}
	
	
	
?>


<fieldset>
<legend align="right"><?php if(isset($_SESSION['Storage'])){ echo $_SESSION['Storage']; }?>, Issue Note</legend>      
<!--<form action='grn_process.php?pgfrom=purchase_order' method='post' name='myForm' id='myForm'>-->
	<fieldset>   
        <center> 
		<table width=100%>
			<tr>
				<td width='10%' style='text-align: right;'>Issue Number</td>
				<td width='26%'>
					<input type='text' name='Issue_ID'  id='Issue_ID' value='<?php echo $Issue_ID;?>' readonly='readonly'>
				</td>
				<td width='13%' style='text-align: right;'>Issue Date</td>
				<td width='16%'><input type='text' name='Issue_Date'  id='Issue_Date' value='<?php echo $Issue_Date; ?>' readonly='readonly'></td> 
			</tr>                               
		    <tr>
			<td width='10%' style='text-align: right;'>Requisition Number</td>
			<td width='26%'>
				<input type='text' name='Requisition_ID'  id='Requisition_ID' readonly='readonly' value="<?php echo $Requisition_ID; ?>" >
			</td>
			<td width='13%' style='text-align: right;'>Requisition Date</td>
			<td width='16%'><input type='text' name='Created_Date_Time'  id='Created_Date_Time' readonly='readonly' value='<?php echo $sent_date; ?>'></td>
		   </tr> 
		    <tr>
			<td width='10%' style='text-align: right;'>Store Need</td>
			<td width='26%'><input type='text' name='Store_Need'  id='Store_Need' value='<?php echo $Store_Need; ?>' readonly='readonly'></td>
			<td width='16%' style='text-align: right;'>Requisition Officer</td>
			<td width='26%'>
				<input type='text' name='Receiver_Name'  id='Receiver_Name' readonly='readonly' value='<?php if($Employee_ID != 0 && $Employee_Name != ''){ echo $Employee_Name; }?>'  >
			</td>
		   </tr> 
		</table>
        </center>
</fieldset>
	
<?php
?>


<form action='#' method='post' name='myForm' id='myForm' >	
<fieldset>
	<table width=100%>
		<tr>
			<?php if(strtolower($Issue_Status) != 'served'){ ?>
				<td style='text-align: right;'>
					<input type='submit' name='submit' id='submit' value='SUBMIT' class='art-button-green'>
				</td>
			<?php }else{ ?>
				<td style='text-align: right;'>
					<a href='grnpurchaseorderreport.php?Requisition_ID=<?php echo $Requisition_ID; ?>&Requisition_ID=IsuueNoteThisPage' target='_Blank' class='art-button-green'>Preview Issue Note </a>
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
			<td width=3% style='text-align: right;'>Requisition N<u>o</u></td>
			<td width=9% style='text-align: right;'>Particular</td>
		    <td width=9% style='text-align: right;'>Quantity Required</td>
		    <td width=9% style='text-align: right;'>Item Remark</td>
		    <td width=9% style='text-align: right;'>Quantity Issued</td>
			<td width=9% style='text-align: right;'>Item Remarked</td>
			<td width=2% style='text-align: center;'><b>Action</b></td>
			<?php
    @session_start();
    $temp = 1;
	// Connect to database server to Select database table
    include("./includes/connection.php");
		// SQL query
	$strSQL = "SELECT * FROM tbl_requisition_items";
	// Execute the query (the recordset $rs contains the result)
	//$rs = mysqli_query($conn,$strSQL);
	//$rs=$requisition_data;
	// Loop the recordset $rs
	// Each row will be made into an array ($row) using mysqli_fetch_array
	//SELECT * FROM `tbl_items` WHERE `Item_ID`, `Item_Type`, `Product_Code`, `Product_Type_ID`, `Unit_Of_Measure`, `Product_Name`, `Selling_Price_Cash`, `Selling_Price_Credit`, `Selling_Price_NHIF`, `NSSF`, `Item_Subcategory_ID`, `Status`, `Reoder_Level`, `Consultation_Type`, `Can_Be_Substituted_In_Doctors_Page`, `Visible_Statu
		$get_requisition_data = "SELECT * FROM tbl_requisition inner join tbl_requisition_items on tbl_requisition_items.Requisition_ID=tbl_requisition.Requisition_ID 
	inner join tbl_sub_department on tbl_sub_department.Sub_Department_ID=tbl_requisition.Store_Need
	inner join tbl_employee on tbl_employee.Employee_ID=tbl_requisition.Employee_ID
	inner join tbl_items on tbl_items.Item_ID=tbl_requisition_items.Item_ID
	WHERE tbl_requisition.Requisition_ID = '$Requisition_ID'";

	$requisition_data = mysqli_query($conn,$get_requisition_data) or die(mysqli_error($conn));
	
	
	
		if($Requisition_ID != ''){
		
			if(!empty($requisition_data)){
				
		while($row= mysqli_fetch_assoc($requisition_data)){	
	
			echo "<tr><td><input type='text' value='".$temp."'  readonly='readonly' style='text-align: center;'></td>";
			echo "<td><input type='text' value='".$row['Requisition_Item_ID']."' readonly='readonly'></td>";
			echo "<td><input type='text' value='".$row['Product_Name']."' readonly='readonly'></td>";
			echo "<td><input type='text' value='".$row['Quantity_Required']."' readonly='readonly'></td>";
			echo "<td><input type='text' value='".$row['Item_Remark']."' readonly='readonly'></td>";
			echo "<td><input type='text' name='Quantity_Issued[]' id='Quantity_Issued[]' required='required' style='text-align: right;' onchange='numberOnly(this)' onkeyup='numberOnly(this)' onkeypress='numberOnly(this)'></td>";
			echo "<td><input type='text' name='Remark[]' id='Remark[]' required='required' style='text-align: right;' onchange='numberOnly(this)' onkeyup='numberOnly(this)' onkeypress='numberOnly(this)'></td>";
			echo "<td width=2%><a href='' class='art-button-green'>X</a></td></tr>";
			echo "</tr>";
			$temp++;
				}
			}
		}else{
			
			if($rs > 0){
				
				if($rs == 1){
			while($row = mysqli_fetch_array($rs)){
			echo "<tr><td><input type='text' value='".$temp."'  readonly='readonly' style='text-align: center;'></td>";
			echo "<td><input type='text' value='".$row['Requisition_Item_ID']."' readonly='readonly'></td>";
			echo "<td><input type='text' value='".$row['Requisition_ID']."' readonly='readonly'></td>";
			echo "<td><input type='text' value='".$row['Quantity_Required']."' readonly='readonly'></td>";
			echo "<td><input type='text' value='".$row['Item_Remark']."' readonly='readonly'></td>";
			echo "<td><input type='text' name='Quantity_Issued[]' id='Quantity_Issued[]' required='required' style='text-align: right;' onchange='numberOnly(this)' onkeyup='numberOnly(this)' onkeypress='numberOnly(this)'></td>";
			echo "<td><input type='text' name='Remark[]' id='Remark[]' required='required' style='text-align: right;' onchange='numberOnly(this)' onkeyup='numberOnly(this)' onkeypress='numberOnly(this)'></td>";
			echo "<td width=2%><a href='' class='art-button-green'>X</a></td></tr>";
			echo "</tr>";
			$temp++;
					}
				}else{
					while($row = mysqli_fetch_array($rs)){
						echo "<tr><td><input type='text' value='".$temp."'  readonly='readonly' style='text-align: center;'></td>";
						echo "<td><input type='text' value='".$row['Requisition_Item_ID']."' readonly='readonly'></td>";
						echo "<td><input type='text' value='".$row['Requisition_ID']."' readonly='readonly'></td>";
						echo "<td><input type='text' value='".$row['Quantity_Required']."' readonly='readonly'></td>";
						echo "<td><input type='text' value='".$row['Item_Remark']."' readonly='readonly'></td>";
						echo "<td><input type='text' name='Quantity_Issued[]' id='Quantity_Issued[]' required='required' style='text-align: right;' onchange='numberOnly(this)' onkeyup='numberOnly(this)' onkeypress='numberOnly(this)'></td>";
						echo "<td><input type='text' name='Remark[]' id='Remark[]' required='required' style='text-align: right;' onchange='numberOnly(this)' onkeyup='numberOnly(this)' onkeypress='numberOnly(this)'></td>";
						echo "<td width=2%><a href='' class='art-button-green'>X</a></td></tr>";
						$temp++;
					}
				}
				
			}
		}
	?>

		</table>
		
		
		</form>   
        </center>
</fieldset>
</form>
<?php
	if($Insert_Status == 'true'){
		echo "<script>
			alert('Process Successful');
			document.location = 'a3.php?Requisition_ID=".$Requisition_ID."&Requisition_ID=IssueNoteThisPage';
			</script>";
	}
?>
<?php
    include("./includes/footer.php");
?>