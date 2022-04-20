<?php
	session_start();
	include("./includes/connection.php");


?>
<table width="100%">
	<tr>
		<td>
			<input type="text" name="S_Name" id="S_Name" placeholder="~~~ ~~~ ~~~ Enter Sponsor Name ~~~ ~~~ ~~~" style="text-align: center;" onkeypress="Search_Sponsor()" oninput="Search_Sponsor()">
		</td>
	</tr>
	<tr>
		<td>
			<fieldset style='overflow-y: scroll; height: 305px;' id='Sponsors_Fieldset'>
				<table width="100%">
					<tr>
						<td width="3%"></td>
						<td><b>SPONSOR NAME</b></td>
					</tr>
				<?php
					$temp = 0;
					$select = mysqli_query($conn,"select Sponsor_ID, Guarantor_Name from tbl_sponsor order by Guarantor_Name limit 100") or die(mysqli_error($conn));
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
			</fieldset>
		</td>
	</tr>
	<tr>
		<td style="text-align: center;">
			<input type="radio" id="All_Sponsor" name="All_Sponsor" onclick="Select_All_Sponsor()"><label for="All_Sponsor"><b>All Sponsors</b></label>
		</td>
	</tr>
</table>