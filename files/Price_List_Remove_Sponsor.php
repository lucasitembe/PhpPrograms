<?php
	include("./includes/connection.php");
	if(isset($_GET['Sponsor_ID'])){
		$Sponsor_ID = $_GET['Sponsor_ID'];
	}else{
		$Sponsor_ID = 0;
	}

	$delete = mysqli_query($conn,"delete from tbl_Price_List_Selected_Sponsors where Sponsor_ID = '$Sponsor_ID'") or die(mysqli_error($conn));
?>
<table width="100%">
	<tr>
		<td width="5%"><b>SN</b></td>
		<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>SPONSOR NAME</b></td>
		<td width="7%"></td>
	</tr>
<?php
	$select = mysqli_query($conn,"select Guarantor_Name, sp.Sponsor_ID from tbl_sponsor sp, tbl_Price_List_Selected_Sponsors ss where
							ss.Sponsor_ID = sp.Sponsor_ID") or die(mysqli_error($conn));
	$no = mysqli_num_rows($select);
	if($no > 0){
		$temp = 0;
		while ($row = mysqli_fetch_array($select)) {
?>
			<tr>
				<td><?php echo ++$temp; ?></td>
				<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $row['Guarantor_Name']; ?></td>
				<td width="7%">
					<input type="button" value="REMOVE" onclick="Remove_Sponsor(<?php echo $row['Sponsor_ID']; ?>)">
				</td>
			</tr>
<?php
		}
	}
?>
</table>