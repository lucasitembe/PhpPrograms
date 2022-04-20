<?php
	include("./includes/connection.php");



	if(isset($_GET['Action']))
		if(filter_input(INPUT_GET, 'Action') == 1){

			$add_item=mysqli_query($conn,"INSERT into tbl_laboratory_test_specimen ( Item_ID,Specimen_ID ) value('".filter_input(INPUT_GET, 'Item_ID')."','".filter_input(INPUT_GET, 'Specimen_ID')."')");


		}else if(filter_input(INPUT_GET, 'Action') == 2){
			$delete =mysqli_query($conn,"DELETE FROM tbl_laboratory_test_specimen where Item_ID='".filter_input(INPUT_GET, 'Item_ID')."' 
									and Specimen_ID ='".filter_input(INPUT_GET, 'Specimen_ID')."'");

		}