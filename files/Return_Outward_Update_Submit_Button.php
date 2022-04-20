<?php
	session_start();
	if(isset($_SESSION['General_Outward_ID'])){
?>
		<input type="button" name="Reset" id="Reset" value="CLEAR ALL ITEMS" class="art-button-green" onclick="Clear_All_Seleced_Items(<?php echo $_SESSION['General_Outward_ID']; ?>)">
		<input type='button' class='art-button-green' value='SUBMIT OUTWARD TRANSACTION' onclick='Confirm_Submit_Outward()'>
<?php
	}
?>