<?php
    //get patient ward id
	$slct_hospital_ward = mysqli_query($conn,"select Hospital_Ward_ID from tbl_admission where Registration_ID = '$Registration_ID' order by Admision_ID desc limit 1") or die(mysqli_error($conn));
	$nm = mysqli_num_rows($slct_hospital_ward);
    if($nm > 0){
        while ($ward_details = mysqli_fetch_array($slct_hospital_ward)) {
            $Hospital_Ward_ID = $ward_details['Hospital_Ward_ID'];
        }
    }else{
    	$Hospital_Ward_ID = null;
    }
?>