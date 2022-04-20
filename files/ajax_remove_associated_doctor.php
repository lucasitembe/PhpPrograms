<?php
session_start();
include("includes/connection.php");
if (isset($_POST['round_associated_doctor_cache_id'])) {
$round_associated_doctor_cache_id=$_POST['round_associated_doctor_cache_id']; 
mysqli_query($conn,"DELETE FROM tbl_round_associated_doctor_cache WHERE round_associated_doctor_cache_id='$round_associated_doctor_cache_id'") or die(mysqli_error($conn));
}