<?php
 include("./includes/connection.php");
 if(isset($_POST['document_approval_level_id'])){
     $document_approval_level_id=$_POST['document_approval_level_id'];
     $sql_remove_level_result=mysqli_query($conn,"DELETE FROM tbl_document_approval_level WHERE document_approval_level_id='$document_approval_level_id'") or die(mysqli_error($conn));
 }
 ?>