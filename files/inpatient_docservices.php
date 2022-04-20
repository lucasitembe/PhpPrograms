<link rel="stylesheet" href="table.css" media="screen"> 
<?php
	require_once('includes/connection.php');
	$Registration_ID = $_GET['Registration_ID'];
	$select_docservices = "
	SELECT * 
		FROM 
			tbl_inpatient_doctorservices id,
			tbl_items it,
			tbl_employee em
			WHERE 
				id.Registration_ID = '$Registration_ID' AND
				id.Item_ID = it.Item_ID AND 
				em.Employee_ID = id.Employee_ID
				ORDER BY DocServices_ID DESC
				";
	$select_docservices_qry = mysqli_query($conn,$select_docservices) or die(mysqli_error($conn));
	
	if(mysqli_num_rows($select_docservices_qry) > 0){
	
	$sn = 1;
	?>
		<table width="99%">
		<tr class="theader">
			<td style="width:3%;"><strong>SN</strong></td>
			<td width="40%"><strong>Item Name</strong></td>
			<td width="15%"><strong>Price</strong></td>
			<td width="10%"><strong>Quantity</strong></td>
			<td width="10%"><strong>Amount</strong></td>
			<td width="15%"><strong>Time Given</strong></td>
			<td width="15%"><strong>Given By</strong></td>
		</tr>
	<?php
		while($services = mysqli_fetch_assoc($select_docservices_qry)){
			$item_name = $services['Product_Name'];
			$Price = $services['Price'];
			$Quantity = $services['Quantity'];
			$Amount = $services['Amount'];
			$time = $services['Date_Time'];
			$Employee_Name = $services['Employee_Name'];
			$ID = $services['DocServices_ID'];
			echo "<tr id='i".$ID."'>";
				echo "<td>";
					echo $sn;
				echo "</td>";
				echo "<td align='left'>";
					echo $item_name;
				echo "</td>";
				echo "<td>";
					echo number_format($Price);
				echo "</td>";
				echo "<td>";
					echo $Quantity;
				echo "</td>";
				echo "<td>";
					echo number_format($Amount);
				echo "</td>";
				echo "<td>";
					echo $time;
				echo "</td>";
				echo "<td>";
					echo $Employee_Name;
				echo "</td>";
			echo "</tr>";
			$sn++;
		}
	?>
	</table>
	<?php } ?>	