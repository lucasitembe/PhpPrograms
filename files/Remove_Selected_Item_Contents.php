<?php
	session_start();
	include("./includes/connection.php");
	
	if(isset($_GET['Patient_Payment_Item_List_ID'])){
		$Patient_Payment_Item_List_ID = $_GET['Patient_Payment_Item_List_ID'];
	}else{
		$Patient_Payment_Item_List_ID = 0;
	}
	
	if(isset($_GET['Patient_Payment_ID'])){
		$Patient_Payment_ID = $_GET['Patient_Payment_ID'];
	}else{
		$Patient_Payment_ID = 0;
	}
	
	if(isset($_GET['Item_Name'])){
		$Item_Name = $_GET['Item_Name'];
	}else{
		$Item_Name = '';
	}
?>
Are you sure you want to remove <br/>
<?php echo $Item_Name; ?>?
<br/><br/>
<center>
	<input type="button" name="RemoveItem" id="RemoveItem" value="REMOVE" class="art-button-green" onclick="Remove_Selected_Item(<?php echo $Patient_Payment_ID; ?>,<?php echo $Patient_Payment_Item_List_ID; ?>)">
	<input type="button" name="Cancel" id="Cancel" value="CANCEL" class="art-button-green" onclick="Close_Dialog()">
</center>