<?php
    session_start();
    include("./includes/connection.php");
?>
<table width='100%' border='1'>
    <tr style='background-color: #1e90ff;'>
	<td align='center' >SN</td>
	<td width='10%'>DOC NO</td>
	<td width='10%'>Date</td>
	<td>Description</td>
	<td width='5%'>Inward</td>
	<td width='5%'>Outward</td>
	<td width='10%'>Running Balance</td>
    </tr>
    <?php
	$qr = "SELECT * FROM tbl_items WHERE Item_ID = ".$_GET['Item_ID'];
	$result = mysqli_query($conn,$qr);
	$i = 1;
	while($row = mysqli_fetch_assoc($result)){
	    ?>
	    <tr>
		<td align='center' style='background-color: #1e90ff;text-decoration: none;'><?php echo $i; ?></td>
		<td><input style='width: 100%' readonly type='text' value='0'></td>
		<td><input style='width: 100%' readonly type='text' value='0'></td>
		<td><input style='width: 100%' readonly type='text' value='0'></td>
		<td><input style='width: 100%' readonly type='text' value='0'></td>
		<td><input style='width: 100%' readonly type='text' value='0'></td>
		<td><input style='width: 100%' readonly type='text' value='0'></td>
	    </tr>
	    <?php
	    $i++;
	}
    ?>
</table>