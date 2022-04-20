<?php
    session_start();
    include("./includes/connection.php");

    //get employee id
    if(isset($_SESSION['userinfo']['Employee_ID'])){
    	$Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    }else{
    	$Employee_ID = 0;
    }

    if(isset($_GET['Payment_Item_Cache_List_ID'])){
    	$Payment_Item_Cache_List_ID = $_GET['Payment_Item_Cache_List_ID'];
    }else{
    	$Payment_Item_Cache_List_ID = 0;
    }
    
    if(isset($_GET['consultation_ID'])){
    	$consultation_ID = $_GET['consultation_ID'];
    }else{
    	$consultation_ID = 0;
    }

    if(isset($_GET['Registration_ID'])){
    	$Registration_ID = $_GET['Registration_ID'];
    }else{
    	$Registration_ID = 0;
    }


    if(isset($_GET['Indication'])){
        $Indication = $_GET['Indication'];
    }else{
        $Indication = '';
    }

    if(isset($_GET['Comments'])){
        $Comments = $_GET['Comments'];
    }else{
        $Comments = '';
    }

    if(isset($_GET['Premedication'])){
        $Premedication = $_GET['Premedication'];
    }else{
        $Premedication = '';
    }


    //Get Patient_Payment_Item_List_ID
    //get Payment_Cache_ID
    $select = mysqli_query($conn,"select pc.consultation_id, pc.Payment_Cache_ID from 
                            tbl_item_list_cache ilc, tbl_payment_cache pc where
                            pc.Payment_Cache_ID = ilc.Payment_Cache_ID and 
                            Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'") or die(mysqli_error($conn));
    $no = mysqli_num_rows($select);
    if($no > 0){
        while ($data = mysqli_fetch_array($select)) {
            $Payment_Cache_ID = $data['Payment_Cache_ID'];
            $consultation_ID = $data['consultation_id'];
        }
    }else{
        $Payment_Cache_ID = 0;
        $consultation_ID = 0;
    }
    
    if($Employee_ID != 0 && $Payment_Item_Cache_List_ID != 0 && $consultation_ID != 0){
    	//check if data available into tbl_Bronchoscopy_notes
    	$select = mysqli_query($conn,"select Bronchoscopy_Notes_ID from tbl_Bronchoscopy_notes where Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'") or die(mysqli_error($conn));
	    $num = mysqli_num_rows($select);
	    if($num < 1){
	    	$insert = mysqli_query($conn,"insert into tbl_Bronchoscopy_notes(
	    							Payment_Item_Cache_List_ID, Surgery_Date, Surgery_Date_Time, 
	    							consultation_ID, Registration_ID, Employee_ID)
	    							
	    						values('$Payment_Item_Cache_List_ID',(select now()),(select now()),
	    							'$consultation_ID','$Registration_ID','$Employee_ID')") or die(mysqli_error($conn));
	    }

	    //get Bronchoscopy_Notes_ID
	    $get_Bronchoscopy_Notes_ID = mysqli_query($conn,"select Bronchoscopy_Notes_ID from tbl_Bronchoscopy_notes where Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'") or die(mysqli_error($conn));
	    $nm = mysqli_num_rows($get_Bronchoscopy_Notes_ID);
	    if($nm > 0){
	    	while ($dt = mysqli_fetch_array($get_Bronchoscopy_Notes_ID)) {
	    		$Bronchoscopy_Notes_ID = $dt['Bronchoscopy_Notes_ID'];
	    	}
	    }else{
	    	$Bronchoscopy_Notes_ID = 0;
	    }

	    //update
	    $update = mysqli_query($conn,"update tbl_Bronchoscopy_notes set
                                Indication='$Indication', Premedication='$Premedication', Comments='$Comments', status = 'Saved' where Bronchoscopy_Notes_ID = '$Bronchoscopy_Notes_ID'") or die(mysqli_error($conn));
	}
?>







