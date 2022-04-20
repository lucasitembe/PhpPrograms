<?php 
	include("./includes/connection.php"); 
	if(isset($_GET['Region_Name'])){
		$Region_Name = $_GET['Region_Name'];
	}else{
		$Region_Name = '';
	}
?>
<legend align="center" ><b>INITIAL REGION REGISTRATION SETTING</b></legend>
<table width="100%">
	<tr>
		<td width="8%"><b>SN</b></td>
		<td><b>REGION NAME</b></td>
		<td width="15%" style="text-align: center;"><b>ACTION</b></td>
	</tr>
<?php
	$temp = 0;
	$select = mysqli_query($conn,"select * from tbl_regions where Region_Name like '%$Region_Name%'") or die(mysqli_error($conn));
	$num = mysqli_num_rows($select);
	if($num > 0){
		while ($data = mysqli_fetch_array($select)) {
?>
	<tr>
		<td><?php echo ++$temp; ?></td>
		<td><?php echo ucwords(strtolower($data['Region_Name'])); ?></td>
		<td>
			<input type="button" name="Submit" id="Submit" value="SELECT" class="art-button-green" onclick="Get_Region(<?php echo $data['Region_ID']; ?>)">
		</td>
	</tr>
<?php
		}
	}
?>
</table>