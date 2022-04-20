<?php
	session_start();
	include("./includes/connection.php");
	$total = 0;

	if(isset($_GET['Payment_Cache_ID'])){
		$Payment_Cache_ID = $_GET['Payment_Cache_ID'];
	}else{
		$Payment_Cache_ID = '';
	}

	if(isset($_GET['Sub_Department_ID'])){
		$Sub_Department_ID = $_GET['Sub_Department_ID'];
	}else{
		$Sub_Department_ID = '';
	}

	if(isset($_GET['Transaction_Type'])){
		$Transaction_Type = $_GET['Transaction_Type'];
	}else{
		$Transaction_Type = '';
	}
    
    $select = mysqli_query($conn,"select ilc.status from tbl_item_list_cache ilc
                            where ilc.status = 'removed' and
                            ilc.Payment_Cache_ID = '$Payment_Cache_ID' and
                            ilc.Sub_Department_ID = '$Sub_Department_ID' and
                            ilc.Transaction_Type = '$Transaction_Type' and
                            ilc.Check_In_Type = 'Laboratory'") or die(mysqli_error($conn));
	$no = mysqli_num_rows($select);
    if($no > 0){
?>
	<input type="button" name="Removed" id="Removed" value="VIEW REMOVED ITEMS" class="art-button-green" onclick="Preview_Removed_Items(<?php echo $Payment_Cache_ID; ?>,<?php echo $Sub_Department_ID; ?>,'<?php echo $Transaction_Type; ?>')">
<?php
    }
?>