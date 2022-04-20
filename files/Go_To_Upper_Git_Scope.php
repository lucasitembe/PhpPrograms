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
    
    if(isset($_GET['Registration_ID'])){
    	$Registration_ID = $_GET['Registration_ID'];
    }else{
    	$Registration_ID = 0;
    }

    if(isset($_GET['Endoscorpic_internvention'])){
        $Endoscorpic_internvention = $_GET['Endoscorpic_internvention'];
    }else{
        $Endoscorpic_internvention = '';
    }

    if(isset($_GET['Indication'])){
        $Indication = $_GET['Indication'];
    }else{
        $Indication = '';
    }

    if(isset($_GET['Dose_Of_Sedation'])){
        $Dose_Of_Sedation = $_GET['Dose_Of_Sedation'];
    }else{
        $Dose_Of_Sedation = '';
    }
    

    if(isset($_GET['Adverse_Event'])){
        $Adverse_Event = $_GET['Adverse_Event'];
    }else{
        $Adverse_Event = '';
    }
    
    if(isset($_GET['Management_recommendation'])){
        $Management_recommendation = $_GET['Management_recommendation'];
    }else{
        $Management_recommendation = '';
    }
    

    if(isset($_GET['Commobility'])){
        $Commobility = $_GET['Commobility'];
    }else{
        $Commobility = '';
    }
    

    if(isset($_GET['Extent_Of_Examination'])){
        $Extent_Of_Examination = $_GET['Extent_Of_Examination'];
    }else{
        $Extent_Of_Examination = '';
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
    	//check if data available into tbl_ogd_post_operative_notes
    	$select = mysqli_query($conn,"select Ogd_Post_operative_ID from tbl_ogd_post_operative_notes where Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'") or die(mysqli_error($conn));
	    $num = mysqli_num_rows($select);
	    if($num < 1){
	    	$insert = mysqli_query($conn,"insert into tbl_ogd_post_operative_notes(
	    							Payment_Item_Cache_List_ID, Surgery_Date, Surgery_Date_Time, 
	    							consultation_ID, Registration_ID, Employee_ID)
	    							
	    						values('$Payment_Item_Cache_List_ID',(select now()),(select now()),
	    							'$consultation_ID','$Registration_ID','$Employee_ID')") or die(mysqli_error($conn));
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

	    //update
	    $update = mysqli_query($conn,"update tbl_ogd_post_operative_notes set
                                Endoscorpic_Internvention='$Endoscorpic_internvention',
                                Type_And_Dose='$Dose_Of_Sedation',
                                Adverse_Event_Resulting ='$Adverse_Event',
                                Management_Recommendation='$Management_recommendation',
                                Extent_Of_Examination='$Extent_Of_Examination',
                                Commobility ='$Commobility', Status = 'Saved'
                                where Ogd_Post_operative_ID = '$Ogd_Post_operative_ID'") or die(mysqli_error($conn));
	}
?>







