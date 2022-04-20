<?php
	session_start();
	include("./includes/connection.php");

	if(isset($_GET['Payment_Item_Cache_List_ID'])){
		$Payment_Item_Cache_List_ID = $_GET['Payment_Item_Cache_List_ID'];
	}else{
		$Payment_Item_Cache_List_ID = 0;
	}
?>
<fieldset style='overflow-y: scroll; height: 340px;' id='Items_Fieldset'>
<?php
	$select = mysqli_query($conn,"select Anal_lessor, Haemoral, PR,rectum,Symd, Dex_colon, Splenic, Ple_Tran_Col, Hepatic_Flexure, Ascending_Colon, Caecum, Terminal_Ileum
							from tbl_ogd_post_operative_notes where Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'") or die(mysqli_error($conn));
	$num = mysqli_num_rows($select);
	if($num > 0){
		while ($data = mysqli_fetch_array($select)) {
			//if($data['Anal_lessor'] != null && $data['Anal_lessor'] != ''){
	?>
				<table width="100%">
					<tr><td><b>TITLE : ANAL LESION</b></td></tr>
					<tr><td><?php echo $data['Anal_lessor']; ?></td></tr>
					<tr><td><hr></td></tr>
				</table><br/>
				
				
				<table width="100%">
					<tr><td><b>TITLE : PR</b></td></tr>
					<tr><td><?php echo $data['PR']; ?></td></tr>
					<tr><td><hr></td></tr>
				</table><br/>
	<?php
			//}
			//if($data['Haemoral'] != null && $data['Haemoral'] != ''){
	?>
				<table width="100%">
					<tr><td><b>TITLE : HAEMORROIDS</b></td></tr>
					<tr><td><?php echo $data['Haemoral']; ?></td></tr>
					<tr><td><hr></td></tr>
				</table><br/>
	<?php
			//}
			//if($data['PR'] != null && $data['PR'] != ''){
	?>
				<table width="100%">
					<tr><td><b>TITLE : RECTUM</b></td></tr>
					<tr><td><?php echo $data['rectum']; ?></td></tr>
					<tr><td><hr></td></tr>
				</table><br/>
				
				
				
				<table width="100%">
					<tr><td><b>TITLE : SIGMOID COLON</b></td></tr>
					<tr><td><?php echo $data['Symd']; ?></td></tr>
					<tr><td><hr></td></tr>
				</table><br/>
				
				
					<table width="100%">
					<tr><td><b>TITLE : DESCENDING COLON</b></td></tr>
					<tr><td><?php echo $data['Dex_colon']; ?></td></tr>
					<tr><td><hr></td></tr>
				</table><br/>
				
				
						<table width="100%">
					<tr><td><b>TITLE : SPLENIC FLEXURE</b></td></tr>
					<tr><td><?php echo $data['Splenic']; ?></td></tr>
					<tr><td><hr></td></tr>
				</table><br/>
			<?php
			//}
			//if($data['Ple_Tran_Col'] != null && $data['Ple_Tran_Col'] != ''){
	?>
				<table width="100%">
					<tr><td><b>TITLE : TRANSVERSE COLON</b></td></tr>
					<tr><td><?php echo $data['Ple_Tran_Col']; ?></td></tr>
					<tr><td><hr></td></tr>
				</table><br/>
				
				
					<?php
			//}
			//if($data['Hepatic_Flexure'] != null && $data['Hepatic_Flexure'] != ''){
	?>
				<table width="100%">
					<tr><td><b>TITLE : HEPATIC FLEXURE</b></td></tr>
					<tr><td><?php echo $data['Hepatic_Flexure']; ?></td></tr>
					<tr><td><hr></td></tr>
				</table><br/>
	
				<?php
			//}
			//if($data['Dex_colon'] != null && $data['Dex_colon'] != ''){
	?>
				<table width="100%">
					<tr><td><b>TITLE : ASCENDING COLON</b></td></tr>
					<tr><td><?php echo $data['Ascending_Colon']; ?></td></tr>
					<tr><td><hr></td></tr>
				</table><br/>

	
				
				
	<?php
			//}
			//if($data['Caecum'] != null && $data['Caecum'] != ''){
	?>
				<table width="100%">
					<tr><td><b>TITLE : CAECUM</b></td></tr>
					<tr><td><?php echo $data['Caecum']; ?></td></tr>
					<tr><td><hr></td></tr>
				</table><br/>
	<?php
			//}
			//if($data['Symd'] != null && $data['Symd'] != ''){
	?>
				

	<?php
			//}
			//if($data['Caecum'] != null && $data['Caecum'] != ''){
	?>
				<table width="100%">
					<tr><td><b>TITLE : TERMINAL ILEUM</b></td></tr>
					<tr><td><?php echo $data['Terminal_Ileum']; ?></td></tr>
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