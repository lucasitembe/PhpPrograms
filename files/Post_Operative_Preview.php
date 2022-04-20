<?php
	session_start();
	include("./includes/connection.php");

	if(isset($_GET['Payment_Item_Cache_List_ID'])){
		$Payment_Item_Cache_List_ID = $_GET['Payment_Item_Cache_List_ID'];
	}else{
		$Payment_Item_Cache_List_ID = 0;
	}
?>
<fieldset style='overflow-y: scroll; height: 305px;' id='Items_Fieldset'>
<?php
	$select = mysqli_query($conn,"select Procedure_Description, Identification_Of_Prosthesis, Estimated_Blood_loss, Complications,
							Drains, Specimen_sent, Postoperative_Orders from tbl_post_operative_notes where Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'") or die(mysqli_error($conn));
	$num = mysqli_num_rows($select);
	if($num > 0){
		while ($data = mysqli_fetch_array($select)) {
			//if($data['Procedure_Description'] != null && $data['Procedure_Description'] != ''){
	?>
				<table width="100%">
					<tr><td><b>TITLE : PROCEDURE DESCRIPTION AND CLOSURE</b></td></tr>
					<tr><td><?php echo $data['Procedure_Description']; ?></td></tr>
					<tr><td><hr></td></tr>
				</table><br/>
	<?php
			//}
			//if($data['Identification_Of_Prosthesis'] != null && $data['Identification_Of_Prosthesis'] != ''){
	?>
				<table width="100%">
					<tr><td><b>TITLE : IDENTIFICATION OF PROSTHESIS</b></td></tr>
					<tr><td><?php echo $data['Identification_Of_Prosthesis']; ?></td></tr>
					<tr><td><hr></td></tr>
				</table><br/>
	<?php
			//}
			//if($data['Estimated_Blood_loss'] != null && $data['Estimated_Blood_loss'] != ''){
	?>
				<table width="100%">
					<tr><td><b>TITLE : ESTIMATED BLOOD LOSS</b></td></tr>
					<tr><td><?php echo $data['Estimated_Blood_loss']; ?></td></tr>
					<tr><td><hr></td></tr>
				</table><br/>
	<?php
			//}
			//if($data['Complications'] != null && $data['Complications'] != ''){
	?>
				<table width="100%">
					<tr><td><b>TITLE : PROBLEMS /COMPLICATIONS</b></td></tr>
					<tr><td><?php echo $data['Complications']; ?></td></tr>
					<tr><td><hr></td></tr>
				</table><br/>
	<?php
			//}
			//if($data['Drains'] != null && $data['Drains'] != ''){
	?>
				<table width="100%">
					<tr><td><b>TITLE : DRAINS</b></td></tr>
					<tr><td><?php echo $data['Drains']; ?></td></tr>
					<tr><td><hr></td></tr>
				</table><br/>
	<?php
			//}
			//if($data['Specimen_sent'] != null && $data['Specimen_sent'] != ''){
	?>
				<table width="100%">
					<tr><td><b>TITLE : SPECIMEN SENT</b></td></tr>
					<tr><td><?php echo $data['Specimen_sent']; ?></td></tr>
					<tr><td><hr></td></tr>
				</table><br/>
	<?php
			//}
			//if($data['Postoperative_Orders'] != null && $data['Postoperative_Orders'] != ''){
	?>
				<table width="100%">
					<tr><td><b>TITLE : POSTOPERATIVE ORDERS</b></td></tr>
					<tr><td><?php echo $data['Postoperative_Orders']; ?></td></tr>
					<tr><td><hr></td></tr>
				</table><br/>
	<?php
			//}
		}
	}
?>
</fieldset>
<!-- <span style="color: #037CB0;">
	<center>
		NOTE: Review display only filled titles
	</center>
</span> -->