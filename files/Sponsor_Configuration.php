<?php
	session_start();
	include("./includes/connection.php");
?>

<table width="100%">
	<tr>
		<td>
			<input type="text" name="Sponsor_Value" id="Sponsor_Value" placeholder="~~~ ~~~ ~~~ ~~~ Enter Sponsor Name ~~~ ~~~ ~~~ ~~~" style="text-align: center;">
		</td>
		<td style="text-align: center;">
			<b>SELECTED SPONSORS</b>
			&nbsp;&nbsp;&nbsp;
			<input type="checkbox" name="General" id="General" value="yes">
			<label for="General">Include General Price</label>
		</td>
	</tr>
	<tr>
		<td width="50%">
			<fieldset style='overflow-y: scroll; height: 305px;' id='Sponsor_List'>
				<table width="100%">
					<tr>
						<td width="5%"><b>SN</b></td>
						<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>SPONSOR NAME</b></td>
						<td width="7%"></td>
					</tr>
		<?php
			$select = mysqli_query($conn,"select Guarantor_Name, Sponsor_ID from tbl_sponsor order by Guarantor_Name") or die(mysqli_error($conn));
			$num = mysqli_num_rows($select);
			if($num > 0){
				$temp = 0;
				while ($data = mysqli_fetch_array($select)) {
		?>
					<tr>
						<td><?php echo ++$temp; ?><b>.</b></td>
						<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $data['Guarantor_Name']; ?></td>
						<td>
							<input type="button" value="ADD" onclick="Add_Sponsor(<?php echo $data['Sponsor_ID']; ?>)">
						</td>
					</tr>
		<?php
				}
			}
		?>
				</table>
			</fieldset>
		</td>
		<td width="50%">
			<fieldset style='overflow-y: scroll; height: 305px;' id='Selected_Sponsor_List'>
				<table width="100%">
					<tr>
						<td width="5%"><b>SN</b></td>
						<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>SPONSOR NAME</b></td>
						<td width="7%"></td>
					</tr>
				<?php
					$temp = 0;
					$select = mysqli_query($conn,"select Guarantor_Name, sp.Sponsor_ID from tbl_sponsor sp, tbl_Price_List_Selected_Sponsors ss where
											ss.Sponsor_ID = sp.Sponsor_ID") or die(mysqli_error($conn));
					$no = mysqli_num_rows($select);
					if($no > 0){
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
			</fieldset>
		</td>
	</tr>
	<tr>
		<td style="text-align: right;" colspan="2">
			<input type="button" name="Close" id="Close" value="CLOSE" class="art-button-green" onclick="Close_Dialog()">
		</td>
	</tr>
</table>