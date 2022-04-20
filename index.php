<?php
    session_start();
    session_destroy();
    include("./includes/connection.php");
    //get index setup
    $select = mysqli_query($conn,"SELECT Enable_Splash_Index from tbl_system_configuration") or die(mysqli_error($conn));
    $num = mysqli_num_rows($select);
    if($num > 0){
    	while ($data = mysqli_fetch_array($select)) {
    		$Enable_Splash_Index = strtolower($data['Enable_Splash_Index']);
    	}
    }else{
    	$Enable_Splash_Index = 'yes';
    }

    if($Enable_Splash_Index == 'yes'){
    	header("Location: ./splashform.php?Welcome");
    }else{
    	header("Location: ./loginform.php?Welcome");
    }
?>