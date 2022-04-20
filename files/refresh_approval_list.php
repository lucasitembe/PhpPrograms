<?php
 include("./includes/connection.php");
 ?>
<table class="table">
     <?php
 if(isset($_POST['document_type_to_approve'])){
//     $document_type_to_approve=$_POST['document_type_to_approve'];
//     $sql_select_category_result=mysqli_query($conn,"SELECT *FROM tbl_document_approval_level_title dalt,tbl_document_approval_level dal WHERE dalt.document_approval_level_title_id=dal.document_approval_level_title_id AND document_type<>'$document_type_to_approve'") or die(mysqli_error($conn));
//        if(mysqli_num_rows($sql_select_category_result)>0){
//            $count=1;
//            while($approve_level_rows=mysqli_fetch_assoc($sql_select_category_result)){
//                $document_approval_level_title_id=$approve_level_rows['document_approval_level_title_id'];
//                $document_approval_level_title=$approve_level_rows['document_approval_level_title'];
//                echo "<tr>
//                            <td>
//                                <label style='font-weight:normal'>
//                                    <input type='checkbox'class='document_approval_level_title_id' name='document_approval_level_title_id' value='$document_approval_level_title_id'>$document_approval_level_title
//                                </label>
//                            </td>
//                      </tr>";
//                $count++;
//            }
//        }else{
             $sql_select_category_result=mysqli_query($conn,"SELECT *FROM tbl_document_approval_level_title") or die(mysqli_error($conn));
                            if(mysqli_num_rows($sql_select_category_result)>0){
                                while($approve_level_rows=mysqli_fetch_assoc($sql_select_category_result)){
                                    $document_approval_level_title_id=$approve_level_rows['document_approval_level_title_id'];
                                    $document_approval_level_title=$approve_level_rows['document_approval_level_title'];
                                    echo "<tr>
                                                <td>
                                                    <label style='font-weight:normal'>
                                                        <input type='checkbox'class='document_approval_level_title_id' name='document_approval_level_title_id' value='$document_approval_level_title_id'>$document_approval_level_title
                                                    </label>
                                                </td>
                                                
                                          </tr>";
                                }
                            }
       // }
 }
?>
</table>