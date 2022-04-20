<?php
 include("./includes/connection.php");
if(isset($_POST['selected_sub_Category'])){
    $selected_sub_Category=$_POST['selected_sub_Category'];
    foreach ($selected_sub_Category as $Item_Subcategory){
        $sql_check_exist = mysqli_query($conn,"SELECT desease_names_id FROM tbl_desease_diagnosis WHERE desease_names_id =$Item_Subcategory");
        if(mysqli_num_rows($sql_check_exist)<=0){
            $sql_attache_result=mysqli_query($conn,"INSERT INTO tbl_desease_diagnosis (desease_names_id)VALUES($Item_Subcategory)") or dei(mysqli_error($conn));
        }
    }   
}
?>

<table class="table">
<?php
$sql_select_attached_sub_category_result=mysqli_query($conn,"SELECT ist.desease_names_id,ist.diagnosis_id,dit.disease_name,dit.disease_ID FROM tbl_desease_diagnosis ist,tbl_disease dit WHERE ist.desease_names_id = dit.disease_ID") or die(mysqli_error($conn));
           if(mysqli_num_rows($sql_select_attached_sub_category_result)>0){
                                $count=1;
                                while($sub_category_rows=mysqli_fetch_assoc($sql_select_attached_sub_category_result)){
                                    $diagnosis_id=$sub_category_rows['diagnosis_id'];
                                    $desease_name=$sub_category_rows['disease_name'];
                                    echo "<tr>
                                                <td style='width:50px'>
                                                    $count
                                                </td>
                                                <td>
                                          <input type='checkbox'class='diagnosis_id' name='diagnosis_id' value='$diagnosis_id'>$desease_name
                                                </td>
                                                
                                          </tr>";
                                    $count++;
                                }
                            }

 ?>
</table>


