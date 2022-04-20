<link rel="stylesheet" href="table3.css" media="screen"> 
<?php
	require_once('includes/connection.php');
	
	$Registration_ID = $_GET['Registration_ID'];
	
	$select_docservices = "
	SELECT * 
		FROM 
			tbl_inpatient_doctorservices_cache idc,
			tbl_items it
			WHERE 
				idc.Registration_ID = '$Registration_ID' AND
				idc.Item_ID = it.Item_ID
				ORDER BY DS_Cache_ID DESC
				";
	$select_docservices_qry = mysqli_query($conn,$select_docservices) or die(mysqli_error($conn));
	
	if(mysqli_num_rows($select_docservices_qry) > 0){
	
	$sn = 1;
	?>
		<table width="99%">
		<tr class="thead">
			<td style="width:3%;"><strong>SN</strong></td>
			<td width="40%"><strong>Item Name</strong></td>
			<td width="15%"><strong>Price</strong></td>
			<td width="15%"><strong>Quantity</strong></td>
			<td width="15%"><strong>Amount</strong></td>
			<td width="15%"><strong>Time Given</strong></td>
			<td width="15%"><strong>Remove</strong></td>
		</tr>
	<?php
		while($services = mysqli_fetch_assoc($select_docservices_qry)){
			$item_name = $services['Product_Name'];
			$Price = $services['Price'];
			$Quantity = $services['Quantity'];
			$Amount = $services['Amount'];
			$time = $services['Date_Time'];
			$ID = $services['DS_Cache_ID'];
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
				echo "<td align='center'>";
					echo "<center><button style='color:red; cursor:pointer;' onClick='DeleteThis(".$ID.")'>&Cross;</button></center>";
				echo "</td>";
			echo "</tr>";
			$sn++;
		}
	?>
	</table>
	<?php } ?>	
	<script>
		function DeleteThis(cid){
			var row = 'i'+cid;
			if(window.XMLHttpRequest) {
				delete_ds = new XMLHttpRequest();
			}
			else if(window.ActiveXObject){ 
				delete_ds = new ActiveXObject('Micrsoft.XMLHTTP');
				delete_ds.overrideMimeType('text/xml');
			}
			
			delete_ds.onreadystatechange= DeleteThis_AJAXP; 
			delete_ds.open('GET','inpatient_docservices_delete.php?cid='+cid,true);
			delete_ds.send();
		}
		function DeleteThis_AJAXP() {
			var response = delete_ds.responseText;
			var rowid = 'i'+response;
			document.getElementById(rowid).style.display = 'none';
		}
	</script>