<?php
include("./includes/connection.php");
 if(isset($_GET['document_type_to_approve'])){
     $document_type_to_approve=$_GET['document_type_to_approve'];
     echo '<option value="">--------Select Approval Level Title--------</option>';
    $sql_select_approval_level_title_result=mysqli_query($conn,"SELECT document_approval_level_title,dalt.document_approval_level_title_id FROM tbl_document_approval_level_title dalt,tbl_document_approval_level dal WHERE dalt.document_approval_level_title_id=dal.document_approval_level_title_id AND document_type='$document_type_to_approve'") or die(mysqli_error($conn));
    if(mysqli_num_rows($sql_select_approval_level_title_result)>0){
        while($approve_level_rows=mysqli_fetch_assoc($sql_select_approval_level_title_result)){
            $document_approval_level_title_id=$approve_level_rows['document_approval_level_title_id'];
            $document_approval_level_title=$approve_level_rows['document_approval_level_title'];
            echo " 
                <option value='$document_approval_level_title_id'>$document_approval_level_title</option>
                ";
        }
 }
 
        }
?>