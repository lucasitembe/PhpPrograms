<?php
	session_start();
	include("./includes/connection.php");

	if(isset($_GET['Currency_ID'])){
		$Currency_ID = $_GET['Currency_ID'];
	}else{
		$Currency_ID = 0;
	}

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
    <tr>
        <td width="20%" style="text-align: right;"><b>Currency Name</b></td>
        <td><b><input type="text" name="Currency_Name_Edit" id="Currency_Name_Edit" placeholder="Currency Name e.g Tanzania Shilings" autocomplete="off" value="<?php echo $Currency_Name; ?>"></b></td>
    </tr>
    <tr>
        <td width="20%" style="text-align: right;"><b>Currency Symbol</b></td>
        <td><b><input type="text" name="Currency_Symbol_Edit" id="Currency_Symbol_Edit" placeholder="Currency Symbol e.g Tshs" autocomplete="off" value="<?php echo $Currency_Symbol; ?>"></b></td>
    </tr>
    <tr>
        <td width="20%" style="text-align: right;"><b>Conversion Rate</b></td>
        <td><b><input type="text" name="Conversion_Rate_Edit" id="Conversion_Rate_Edit" placeholder="Conversion Rate" autocomplete="off" value="<?php echo $Conversion_Rate; ?>"></b></td>
    </tr>
    <tr><td colspan="2" style="text-align: center;"><b><span style="color: #037CB0;" id="Error_Area2" >&nbsp;</span></b></td></tr>
    <tr>
        <td colspan="2" style="text-align: right;">
            <input type="button" name="Calcel_Button" id="Calcel_Button" value="CANCEL" class="art-button-green" onclick="Close_Edit_Dialog()">
            <input type="button" name="Edit_Button" id="Edit_Button" value="EDIT CURRENCY" class="art-button-green" onclick="Update_Currency(<?php echo $Currency_ID; ?>)">
        </td>
    </tr>
</table>