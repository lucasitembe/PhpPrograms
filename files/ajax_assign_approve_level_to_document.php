<?php
 include("./includes/connection.php");
$document_type_to_approve="0";
if(isset($_POST['selectedApprovel_level'])){
     $selectedApprovel_level=$_POST['selectedApprovel_level'];
     $document_type_to_approve=$_POST['document_type_to_approve'];
    foreach ($selectedApprovel_level as $document_approval_level_title_id){
         $sql_check_if_already_assigned_result=mysqli_query($conn,"SELECT *FROM tbl_document_approval_level WHERE document_approval_level_title_id='$document_approval_level_title_id' AND document_type='$document_type_to_approve'") or die(mysqli_error($conn));
    
         if(mysqli_num_rows($sql_check_if_already_assigned_result)<=0){
             $sql_insert_level_result=mysqli_query($conn,"INSERT INTO tbl_document_approval_level(document_approval_level_title_id,document_type) VALUES('$document_approval_level_title_id','$document_type_to_approve')") or die(mysqli_error($conn));
         }
    }   
}
?>

<table class="table">
     <?php
 if(isset($_POST['document_type_to_approve'])){
     $document_type_to_approve=$_POST['document_type_to_approve'];
     $sql_select_category_result=mysqli_query($conn,"SELECT *FROM tbl_document_approval_level_title dalt,tbl_document_approval_level dal WHERE dalt.document_approval_level_title_id=dal.document_approval_level_title_id AND document_type='$document_type_to_approve'") or die(mysqli_error($conn));
        if(mysqli_num_rows($sql_select_category_result)>0){
            $count=1;
            while($approve_level_rows=mysqli_fetch_assoc($sql_select_category_result)){
                $document_approval_level_title_id=$approve_level_rows['document_approval_level_title_id'];
                $document_approval_level_title=$approve_level_rows['document_approval_level_title'];
                echo "<tr>
                            <td style='width:50px'>$count</td>
                            <td>
                                <label style='font-weight:normal'>
                                    $document_approval_level_title
                                </label>
                            </td>
                      </tr>";
                $count++;
            }
        }
 }
?>
</table>


