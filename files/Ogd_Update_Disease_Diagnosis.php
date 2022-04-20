<?php
	session_start();
	include("./includes/connection.php");

	if(isset($_GET['Payment_Item_Cache_List_ID'])){
		$Payment_Item_Cache_List_ID = $_GET['Payment_Item_Cache_List_ID'];
	}else{
		$Payment_Item_Cache_List_ID = '';
	}

	//get Ogd_Post_operative_ID
    $get_Ogd_Post_operative_ID = mysqli_query($conn,"select Ogd_Post_operative_ID from tbl_ogd_post_operative_notes where Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'") or die(mysqli_error($conn));
    $nm = mysqli_num_rows($get_Ogd_Post_operative_ID);
    if($nm > 0){
    	while ($dt = mysqli_fetch_array($get_Ogd_Post_operative_ID)) {
    		$Ogd_Post_operative_ID = $dt['Ogd_Post_operative_ID'];
    	}
    }else{
    	$Ogd_Post_operative_ID = 0;
    }


	$selected_diagnosis = '';
	//get selected diagnosis disease
	$select_diagnosis = mysqli_query($conn,"select d.disease_code, d.disease_name, d.disease_ID 
	                        from tbl_disease d, tbl_ogd_post_operative_diagnosis po where
	                        d.disease_ID = po.Disease_ID and
	                        po.Ogd_Post_operative_ID = '$Ogd_Post_operative_ID'") or die(mysqli_error($conn));
	$num = mysqli_num_rows($select_diagnosis);
	if($num > 0){
	    while ($dtz = mysqli_fetch_array($select_diagnosis)) {
	        $selected_diagnosis .= $dtz['disease_name'].'('.$dtz['disease_code'].');  ';
	    }
	}
	echo $selected_diagnosis;
?>