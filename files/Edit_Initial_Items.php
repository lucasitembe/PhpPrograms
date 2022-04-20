<?php
	session_start();
	include("./includes/connection.php");
	$temp = 0;
?>
<table width="100%">
	<tr>
		<td style="text-align: right;">
			<input type="button" name="Add_Item" id="Add_Item" class="art-button-green" value="ADD ITEM" onclick="Add_Items()">
		</td>
	</tr>
</table>
<fieldset style='overflow-y: scroll; height: 250px;background-color:white;margin-top:20px;' id='Fieldset_List'>
    <legend align='right' style="background-color:#006400;color:white;padding:5px;"><b>LIST OF SELECTED ITEMS</b></legend>
    <table width=100% border=1>
    	<tr>
    		<td width="5%"><b>SN</b></td>
    		<td><b>ITEM NAME</b></td>
    		<td width="10%" style="text-align: center"><b>ACTION</b></td>
    	</tr>
    	<tr><td colspan="3"><hr></td></tr>

<?php
	$select = mysqli_query($conn,"select i.Item_ID, i.Product_Name from tbl_items i, tbl_initial_items ii where
							i.Item_ID = ii.Item_ID order by i.Product_Name") or die(mysqli_error($conn));
	$no = mysqli_num_rows($select);
	if($no > 0){
		while ($data = mysqli_fetch_array($select)) {
			
?>
		<tr>
    		<td width="5%"><?php echo ++$temp; ?></td>
    		<td><?php echo ucwords(strtolower($data['Product_Name'])); ?></td>
    		<td width="10%" style="text-align: center">
    			<input type="button" name="remove" id="remove" value="X" onclick="Remove_Item('<?php echo ucwords(strtolower($data['Product_Name'])); ?>','<?php echo $data['Item_ID']; ?>')">
    		</td>
    	</tr>
<?php
		}
	}

?>
    </table>
</fieldset>