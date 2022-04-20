<?php
	session_start();
	include("./includes/connection.php");
	//get employee id
    if(isset($_SESSION['userinfo']['Employee_ID'])){
        $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    }else{
        $Employee_ID = 0;
    }

	if(isset($_GET['Username1'])){
		$Username1 = $_GET['Username1'];
	}else{
		$Username1 = '';
	}

	
	if(isset($_GET['Username2'])){
		$Username2 = $_GET['Username2'];
	}else{
		$Username2 = '';
	}
	
	if(isset($_GET['Username3'])){
		$Username3 = $_GET['Username3'];
	}else{
		$Username3 = '';
	}
	
	if(isset($_GET['Password1'])){
		$Password1 = md5($_GET['Password1']);
	}else{
		$Password1 = md5('');
	}
	
	if(isset($_GET['Password2'])){
		$Password2 = md5($_GET['Password2']);
	}else{
		$Password2 = md5('');
	}
	
	if(isset($_GET['Password3'])){
		$Password3 = md5($_GET['Password3']);
	}else{
		$Password3 = md5('');
	}
	
	if(isset($_GET['Percentage'])){
		$Percentage = $_GET['Percentage'];
	}else{
		$Percentage = '';
	}
	
	if(isset($_GET['Status'])){
		$Status = $_GET['Status'];
	}else{
		$Status = '';
	}
	
	if(isset($_GET['Sponsor_ID'])){
		$Sponsor_ID = $_GET['Sponsor_ID'];
	}else{
		$Sponsor_ID = '';
	}

	//get first supervisor id
	$select = mysqli_query($conn,"select Employee_ID from tbl_privileges where Given_Username = '$Username2' and Given_Password = '$Password2'") or die(mysqli_error($conn));
	$num = mysqli_num_rows($select);
	if($num > 0){
		while ($data = mysqli_fetch_array($select)) {
			$Employee_ID2 = $data['Employee_ID'];
		}
	}else{
		$Employee_ID2 = 0;
	}
	//get second supervisor id
	$select = mysqli_query($conn,"select Employee_ID from tbl_privileges where Given_Username = '$Username3' and Given_Password = '$Password3'") or die(mysqli_error($conn));
	$num = mysqli_num_rows($select);
	if($num > 0){
		while ($data = mysqli_fetch_array($select)) {
			$Employee_ID3 = $data['Employee_ID'];
		}
	}else{
		$Employee_ID3 = 0;
	}

	if($Employee_ID != null && $Employee_ID != '' && $Employee_ID2 != null && $Employee_ID2 != '' && $Employee_ID3 != null && $Employee_ID3 != ''){
		//insert into tbl_edit_price_summary
		if($Sponsor_ID == 0){
			$insert = mysqli_query($conn,"insert into tbl_edit_price_summary(
								Employee_ID, First_Supervisor_ID, 
								Second_Supervisor_ID, Percentage, Status, Date_And_Time) 

							VALUES ('$Employee_ID','$Employee_ID2',
									'$Employee_ID3','$Percentage','$Status',(select now()))") or die(mysqli_error($conn));
		}else{
			$insert = mysqli_query($conn,"insert into tbl_edit_price_summary(
								Sponsor_ID, Employee_ID, First_Supervisor_ID, 
								Second_Supervisor_ID, Percentage, Status, Date_And_Time) 

							VALUES ('$Sponsor_ID','$Employee_ID','$Employee_ID2',
									'$Employee_ID3','$Percentage','$Status',(select now()))") or die(mysqli_error($conn));
		}

		if($insert){
			//get summary id
			$get_id = mysqli_query($conn,"select Summary_ID from tbl_edit_price_summary where 
									Employee_ID = '$Employee_ID' and 
									First_Supervisor_ID = '$Employee_ID2' and
									Second_Supervisor_ID = '$Employee_ID3' order by Summary_ID desc limit 1") or die(mysqli_error($conn));
			$num_get_id = mysqli_num_rows($get_id);
			if($num_get_id > 0){
				while ($row = mysqli_fetch_array($get_id)) {
					$Summary_ID = $row['Summary_ID'];
				}
			}else{
				$Summary_ID = '';
			}

			if($Sponsor_ID == 0){
				//get selected items then update general price
				if($Summary_ID != null && $Summary_ID != ''){
					$get_items = mysqli_query($conn,"select gip.Item_ID, gip.Items_Price, epc.Edited_Item_ID from tbl_edit_price_cache epc, tbl_general_item_price gip where
												gip.Item_ID = epc.Item_ID and
												epc.Employee_ID = '$Employee_ID'") or die(mysqli_error($conn));
					$nm_items = mysqli_num_rows($get_items);
					if($nm_items > 0){
						while ($dt = mysqli_fetch_array($get_items)) {
							$Item_ID = $dt['Item_ID'];
							$Items_Price = $dt['Items_Price'];
							$Edited_Item_ID = $dt['Edited_Item_ID'] ;
							if(strtolower($Status) == 'increase'){
								$update = ("update tbl_general_item_price set Items_Price = ($Items_Price + ($Items_Price * ($Percentage/100))) where Item_ID = '$Item_ID'") or die(mysqli_error($conn));
							}else{
								$update = mysqli_query($conn,"update tbl_general_item_price set Items_Price = ($Items_Price - ($Items_Price * ($Percentage/100))) where Item_ID = '$Item_ID'") or die(mysqli_error($conn));
							}

							$update = mysqli_query($conn,"update tbl_general_item_price set Items_Price = ($Items_Price + ($Items_Price *($Percentage/100))) where Item_ID = '$Item_ID'") or die(mysqli_error($conn));
							if($update){
								$insert_history = mysqli_query($conn,"insert into tbl_edit_price_items_history(Item_ID, Old_price, New_Price,Summary_ID)
																values('$Item_ID','$Items_Price',($Items_Price + ($Items_Price *($Percentage/100))),'$Summary_ID')") or die(mysqli_error($conn));
								if($insert_history){
									$delete_cache = mysqli_query($conn,"delete from tbl_edit_price_cache where Edited_Item_ID = '$Edited_Item_ID'") or die(mysqli_error($conn));
								}
							}
						}
						//erase other items if available
						mysqli_query($conn,"delete from tbl_edit_price_cache where Employee_ID = '$Employee_ID'") or die(mysqli_error($conn));
						echo "yes";
					}
				}
			}else{
				//get selected items then update price based on selected sponsor id
				if($Summary_ID != null && $Summary_ID != ''){
					$get_items = mysqli_query($conn,"select ipr.Item_ID, ipr.Items_Price, epc.Edited_Item_ID from tbl_edit_price_cache epc, tbl_item_price ipr where
												ipr.Item_ID = epc.Item_ID and
												epc.Employee_ID = '$Employee_ID' and
												ipr.Sponsor_ID = '$Sponsor_ID'") or die(mysqli_error($conn).'1');
					$nm_items = mysqli_num_rows($get_items);
					if($nm_items > 0){
						while ($dt = mysqli_fetch_array($get_items)) {
							$Item_ID = $dt['Item_ID'];
							$Items_Price = $dt['Items_Price'];
							$Edited_Item_ID = $dt['Edited_Item_ID'] ;

							if(strtolower($Status) == 'increase'){
								$update = mysqli_query($conn,"update tbl_item_price set Items_Price = ($Items_Price + ($Items_Price * ($Percentage/100))) where Item_ID = '$Item_ID' and Sponsor_ID = '$Sponsor_ID'") or die(mysqli_error($conn).'2');
							}else{
								$update = mysqli_query($conn,"update tbl_item_price set Items_Price = ($Items_Price - ($Items_Price * ($Percentage/100))) where Item_ID = '$Item_ID' and Sponsor_ID = '$Sponsor_ID'") or die(mysqli_error($conn).'3');
							}
							
							if($update){
								$insert_history = mysqli_query($conn,"insert into tbl_edit_price_items_history(Item_ID, Old_price, New_Price,Summary_ID)
																values('$Item_ID','$Items_Price',($Items_Price + ($Items_Price *($Percentage/100))),'$Summary_ID')") or die(mysqli_error($conn));
								if($insert_history){
									$delete_cache = mysqli_query($conn,"delete from tbl_edit_price_cache where Edited_Item_ID = '$Edited_Item_ID'") or die(mysqli_error($conn));
								}
							}
						}
					}
					//erase other items if available
					mysqli_query($conn,"delete from tbl_edit_price_cache where Employee_ID = '$Employee_ID'") or die(mysqli_error($conn));
					echo "yes";
				}
			}
		}else{
			echo "no";
		}
	}else{
		echo "no";
	}
?>