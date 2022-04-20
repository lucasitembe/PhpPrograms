<?php
	session_start();
	if(isset($_SESSION['General_Inward_ID'])){
		echo "<input type='button' class='art-button-green' value='SUBMIT INWARD TRANSACTION' onclick='Confirm_Submit_Inward()'>";
	}
?>