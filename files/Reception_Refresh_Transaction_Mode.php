<?php
	session_start();
?>
<select id="Transaction_Mode" name="Transaction_Mode" onchange="Validate_Transaction_Mode()">
    <option selected="selected">Normal Transaction</option>
    <option <?php if(isset($_SESSION['Transaction_Mode']) && strtolower($_SESSION['Transaction_Mode']) == 'fast track transaction'){ echo "selected='selected'"; } ?>>Fast Track Transaction</option>
</select>