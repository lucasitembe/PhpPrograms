<?php
	session_start();
	include("./includes/connection.php");
	if(isset($_SESSION['General_Inward_ID'])){
		$Inward_ID = $_SESSION['General_Inward_ID'];
	}else{
		$Inward_ID = 0;
	}

	$slct = mysqli_query($conn,"SELECT
                            sd.Sub_Department_ID, sd.Sub_Department_Name
                         FROM tbl_sub_department sd, tbl_return_inward ri where
    						sd.Sub_Department_ID = ri.Store_Sub_Department_ID and
    						ri.Inward_ID = '$Inward_ID'") or die(mysqli_error($conn));
    $nm = mysqli_num_rows($slct);
    if($nm > 0){
    	while ($dt = mysqli_fetch_array($slct)) {
    		$Store_Receiving_Name = $dt['Sub_Department_Name'];
    		$Store_Receiving_ID = $dt['Sub_Department_ID'];
    	}
    }else{
    	$Store_Receiving_Name = '';
    	$Store_Receiving_ID = '';
    }
?>
<select name="Store_Receiving" id="Store_Receiving">
	<option selected='selected' value="<?php echo $Store_Receiving_ID; ?>"><?php echo $Store_Receiving_Name; ?></option>
</select>