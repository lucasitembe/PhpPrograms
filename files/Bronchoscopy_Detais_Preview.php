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
	$select = mysqli_query($conn,"select Indication, Premedication, vocal_cords, Trachea, Carina, Rt_Bronchial_tree, Rt_UL_Bronchus, Rt_ML_Bronchus, Rt_LL_Bronchus, Lt_Bronchial_tree, Lt_UL_Bronchus, Lt_LL_Bronchus, Liangula, Biopsy, Impression, Bal, Comments from tbl_Bronchoscopy_notes where Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'") or die(mysqli_error($conn));
	$num = mysqli_num_rows($select);
	if($num > 0){
		while ($data = mysqli_fetch_array($select)) {
	?>
				<table width="100%">
					<tr><td><b>TITLE : INDICATION</b></td></tr>
					<tr><td><?php echo $data['Indication']; ?></td></tr>
					<tr><td><hr></td></tr>
				</table><br/>


				<table width="100%">
					<tr><td><b>TITLE : PREMEDICATION(s)</b></td></tr>
					<tr><td><?php echo $data['Premedication']; ?></td></tr>
					<tr><td><hr></td></tr>
				</table><br/>
				
				
				<table width="100%">
					<tr><td><b>TITLE : VOCAL CORDS</b></td></tr>
					<tr><td><?php echo $data['vocal_cords']; ?></td></tr>
					<tr><td><hr></td></tr>
				</table><br/>
	
				<table width="100%">
					<tr><td><b>TITLE : TRACHEA</b></td></tr>
					<tr><td><?php echo $data['Trachea']; ?></td></tr>
					<tr><td><hr></td></tr>
				</table><br/>
	
				<table width="100%">
					<tr><td><b>TITLE : CARINA</b></td></tr>
					<tr><td><?php echo $data['Carina']; ?></td></tr>
					<tr><td><hr></td></tr>
				</table><br/>
				
				<table width="100%">
					<tr><td><b>TITLE : RT BRONCHIAL TREE</b></td></tr>
					<tr><td><?php echo $data['Rt_Bronchial_tree']; ?></td></tr>
					<tr><td><hr></td></tr>
				</table><br/>
				
				
					<table width="100%">
					<tr><td><b>TITLE : RT UL BRONCHUS</b></td></tr>
					<tr><td><?php echo $data['Rt_UL_Bronchus']; ?></td></tr>
					<tr><td><hr></td></tr>
				</table><br/>
				
				
						<table width="100%">
					<tr><td><b>TITLE : RT ML BRONCHUS</b></td></tr>
					<tr><td><?php echo $data['Rt_ML_Bronchus']; ?></td></tr>
					<tr><td><hr></td></tr>
				</table><br/>


				<table width="100%">
					<tr><td><b>TITLE : RT LL BRONCHUS</b></td></tr>
					<tr><td><?php echo $data['Rt_LL_Bronchus']; ?></td></tr>
					<tr><td><hr></td></tr>
				</table><br/>


<!-- END HERE-->
                <table width="100%">
					<tr><td><b>TITLE : LT BRONCHIAL TREE</b></td></tr>
					<tr><td><?php echo $data['Lt_Bronchial_tree']; ?></td></tr>
					<tr><td><hr></td></tr>
				</table><br/>


                <table width="100%">
					<tr><td><b>TITLE : LT UL BRONCHUS</b></td></tr>
					<tr><td><?php echo $data['Lt_UL_Bronchus']; ?></td></tr>
					<tr><td><hr></td></tr>
				</table><br/>


                <table width="100%">
					<tr><td><b>TITLE : LT LL BRONCHUS</b></td></tr>
					<tr><td><?php echo $data['Lt_LL_Bronchus']; ?></td></tr>
					<tr><td><hr></td></tr>
				</table><br/>
				

				<table width="100%">
					<tr><td><b>TITLE : LIANGULA</b></td></tr>
					<tr><td><?php echo $data['Liangula']; ?></td></tr>
					<tr><td><hr></td></tr>
				</table><br/>
	
				
				<table width="100%">
					<tr><td><b>TITLE : BIOPSY</b></td></tr>
					<tr><td><?php echo $data['Biopsy']; ?></td></tr>
					<tr><td><hr></td></tr>
				</table><br/>

	
				
				<table width="100%">
					<tr><td><b>TITLE : IMPRESSION</b></td></tr>
					<tr><td><?php echo $data['Impression']; ?></td></tr>
					<tr><td><hr></td></tr>
				</table><br/>
	

				<table width="100%">
					<tr><td><b>TITLE : BAL</b></td></tr>
					<tr><td><?php echo $data['Bal']; ?></td></tr>
					<tr><td><hr></td></tr>
				</table><br/>

                <table width="100%">
					<tr><td><b>TITLE : COMMENTS</b></td></tr>
					<tr><td><?php echo $data['Comments']; ?></td></tr>
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