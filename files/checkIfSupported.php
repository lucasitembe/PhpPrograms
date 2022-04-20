<?php

include("./includes/connection.php");
//action=viewStatus&id=' + id+'&registration_ID='+registration_ID,
if (isset($_GET['action'])){
   
 $registration_ID=$_GET['registration_ID'];
 $id=$_GET['id'];
 
 $getSponsor=  mysqli_query($conn,"SELECT Sponsor_ID FROM tbl_patient_registration WHERE Registration_ID='$registration_ID'");
 $Sponsor_ID=  mysqli_fetch_assoc($getSponsor);
 $sponsor=$Sponsor_ID['Sponsor_ID'];

 $checkSupported=  mysqli_query($conn,"SELECT * FROM tbl_sponsor_non_supported_items WHERE sponsor_id='$sponsor' AND item_ID='$id'");
 $numRows=  mysqli_num_rows($checkSupported);
 if($numRows>0){
     echo 'yes';
 }  else {
     echo 'no';
 }
};

?>