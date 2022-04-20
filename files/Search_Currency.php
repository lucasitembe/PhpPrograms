<?php
	include("./includes/connection.php");
	if(isset($_GET['Search_Value'])){
		$Search_Value = $_GET['Search_Value'];
	}else{
		$Search_Value = '';
	}
?>
<legend align="right"><b>MULTI-CURRENCY CONFIGURATION</b></legend>
<table width="100%">
    <tr>
        <td width="5%"><b>SN</b></td>
        <td><b>CURRENCY NAME</b></td>
        <td><b>CURRENCY SYMBOL</b></td>
        <td><b>CONVERSION RATE</b></td>
        <td width="15%" style="text-align: center;" colspan="2"><b>ACTION</b></td>
    </tr>
    <tr><td colspan="6"><hr></td></tr>
<?php
    $select = mysqli_query($conn,"select * from tbl_multi_currency where Currency_Name like '%$Search_Value%' order by Currency_Name") or die(mysqli_error($conn));
    $num = mysqli_num_rows($select);
    if($num > 0){
        $temp = 0;
        while($data = mysqli_fetch_array($select)){
?>
            <tr>
                <td><?php echo ++$temp; ?></td>
                <td><?php echo $data['Currency_Name']; ?></td>
                <td><?php echo $data['Currency_Symbol']; ?></td>
                <td><?php echo $data['Conversion_Rate']; ?></td>
                <td><input type="button" value="EDIT" class="art-button-green" onclick="Edit_Currency(<?php echo $data['Currency_ID']; ?>)"></td>
                <td><input type="button" value="REMOVE" class="art-button-green" onclick="Remove_Currency(<?php echo $data['Currency_ID']; ?>)"></td>
            </tr>
<?php
        }
    }
?>
</table>