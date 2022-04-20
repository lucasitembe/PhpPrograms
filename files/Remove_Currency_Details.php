<?php
	session_start();
	include("./includes/connection.php");

	if(isset($_GET['Currency_ID'])){
		$Currency_ID = $_GET['Currency_ID'];
	}else{
		$Currency_ID = 0;
	}

	//get details
	$select = mysqli_query($conn,"select * from tbl_multi_currency where Currency_ID = '$Currency_ID'") or die(mysqli_error($conn));
	$num = mysqli_num_rows($select);
	if($num > 0){
		while ($data = mysqli_fetch_array($select)) {
			$Currency_Name = $data['Currency_Name'];
			$Currency_Symbol = $data['Currency_Symbol'];
			$Conversion_Rate = $data['Conversion_Rate'];
		}
	}else{
		$Currency_Name = '';
		$Currency_Symbol = '';
		$Conversion_Rate = '';
	}
?>
<table width="100%">
	<tr><td colspan="2" style="text-align: left;"><b>ARE YOU SURE YOU WANT TO REMOVE</b></td></tr>
	<tr><td width="25%">Currency Name</td><td><input type="text" readonly="readonly" value="<?php echo $Currency_Name; ?>"></td></tr>
	<tr><td width="25%">Currency Symbol</td><td><input type="text" readonly="readonly" value="<?php echo $Currency_Symbol; ?>"></td></tr>
	<tr><td width="25%">Conversion Rate</td><td><input type="text" readonly="readonly" value="<?php echo $Conversion_Rate; ?>"></td></tr>
	<tr>
		<td colspan="2" style="text-align: right;">
			<input type="button" value="REMOVE" class="art-button-green" onclick="Remove_Selected_Currency(<?php echo $Currency_ID; ?>)">
			<input type="button" value="CANCEL" class="art-button-green" onclick="Cancel_Remove(<?php echo $Currency_ID; ?>)">
		</td>
	</tr>
</table>