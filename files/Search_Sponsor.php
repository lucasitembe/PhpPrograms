<?php
	session_start();
	include("./includes/connection.php");

	if(isset($_GET['Sponsor_Name'])){
		$Sponsor_Name = str_replace(" ", "%", $_GET['Sponsor_Name']);
	}else{
		$Sponsor_Name = '';
	}
?>
<table width="100%">
	<tr>
		<td width="3%"></td>
		<td><b>SPONSOR NAME</b></td>
	</tr>
<?php
	$temp = 0;
	$select = mysqli_query($conn,"select Sponsor_ID, Guarantor_Name from tbl_sponsor where Guarantor_Name like '%$Sponsor_Name%' order by Guarantor_Name limit 100") or die(mysqli_error($conn));
	$num = mysqli_num_rows($select);
	if($num > 0){
		while ($data = mysqli_fetch_array($select)) {
?>
			<tr>
				<td><input type="radio" name="Sp" id="<?php echo $data['Sponsor_ID']; ?>" onclick="Get_Sponsor(<?php echo $data['Sponsor_ID']; ?>,'<?php echo strtoupper($data['Guarantor_Name']); ?>')"></td>
				<td><label for="<?php echo $data['Sponsor_ID']; ?>"><?php echo strtoupper($data['Guarantor_Name']); ?></label></td>
			</tr>
<?php
		}
	}
?>
</table>