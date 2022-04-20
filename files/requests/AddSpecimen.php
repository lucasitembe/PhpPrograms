<?php
 require_once('../includes/connection.php');
//
 isset($_GET['Specimen']) ? $Specimen = mysqli_real_escape_string($conn,$_GET['Specimen']) : $Specimen != '';

//	$cached_data = '';
  if(!empty($Specimen)){
   $insert_cache = "INSERT INTO `tbl_laboratory_specimen`(`Specimen_Name`, `Status`) VALUES ('$Specimen','Active')";
   $insert_cache_qry = mysqli_query($conn,$insert_cache) or die(mysqli_error($conn));
//   $cached_data .='<option value="' .mysql_insert_id(). '">' .$Specimen. '</option>';
//   echo $cached_data;
   $cached_data = "";
  $query_sub_specimen = mysqli_query($conn,"SELECT Specimen_Name,Specimen_ID FROM tbl_laboratory_specimen WHERE Status='Active'") or die(mysqli_error($conn));
   echo '<option value="All">~~~~~Select Specimen~~~~~</option>';
  while ($row = mysqli_fetch_array($query_sub_specimen)) {
   $cached_data .='<option value="' . $row['Specimen_ID'] . '">' . $row['Specimen_Name'] . '</option>';
  }
  echo $cached_data;
  }
  
  if(isset($_GET['Specimen_ID'])){
     $Specimen_ID = trim($_GET['Specimen_ID']);
   $insert_cache = "UPDATE `tbl_laboratory_specimen` SET `Status`='InActive' WHERE `Specimen_ID`='$Specimen_ID'";
   $insert_cache_qry = mysqli_query($conn,$insert_cache) or die(mysqli_error($conn));
   $cached_data = "";
  $query_sub_specimen = mysqli_query($conn,"SELECT Specimen_Name,Specimen_ID FROM tbl_laboratory_specimen WHERE Status='Active'") or die(mysqli_error($conn));
   echo '<option select="selected" value="All">~~~~~Select Specimen~~~~~</option>';
  while ($row = mysqli_fetch_array($query_sub_specimen)) {
   $cached_data .='<option value="' . $row['Specimen_ID'] . '">' . $row['Specimen_Name'] . '</option>';
  }
  echo $cached_data;
  }
  
  if(isset($_GET['ShapeName'])){
     $ShapeName = trim($_GET['ShapeName']);
   $insert_cache = "INSERT INTO `tbl_laboratory_shape`( `Shape_Name`, `status`) VALUES ('$ShapeName','Active')";
   $insert_cache_qry = mysqli_query($conn,$insert_cache) or die(mysqli_error($conn));
   $cached_data = "";
  $query_sub_specimen = mysqli_query($conn,"SELECT `Shape_Name` FROM `tbl_laboratory_shape` WHERE `status`='Active'") or die(mysqli_error($conn));
   echo '<option select="selected" value="All">~~~~~Select Shape~~~~~</option>';
  while ($row = mysqli_fetch_array($query_sub_specimen)) {
   $cached_data .='<option value="' . $row['Shape_Name'] . '">' . $row['Shape_Name'] . '</option>';
  }
  echo $cached_data;
  }
?>
