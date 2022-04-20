<?php
	include("./includes/connection.php");
	if(isset($_GET['Sponsor_ID'])){
		$Sponsor_ID = $_GET['Sponsor_ID'];
	}else{
		$Sponsor_ID = 0;
	}

	if($Sponsor_ID != 0 && $Sponsor_ID != null && $Sponsor_ID != ''){
		//check if available
		$check = mysqli_query($conn,"select Sponsor_ID from tbl_Price_List_Selected_Sponsors where Sponsor_ID = '$Sponsor_ID'") or die(mysqli_error($conn));
		$num = mysqli_num_rows($check);
		if($num == 0){
			//check if less or equal
			$slct = mysqli_query($conn,"select Sponsor_ID from tbl_Price_List_Selected_Sponsors") or die(mysqli_error($conn));
			$no = mysqli_num_rows($slct);
			if($no <= 2){
				mysqli_query($conn,"insert into tbl_Price_List_Selected_Sponsors(Sponsor_ID) values('$Sponsor_ID')") or die(mysqli_error($conn));
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
<?php
			}else{
				echo "exceed";
			}
		}else{
			echo 'available';
		}
	}
?>