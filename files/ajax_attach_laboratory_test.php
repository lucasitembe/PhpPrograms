<?php
 include("./includes/connection.php");
if(isset($_POST['selected_sub_Category'])){
    $selected_sub_Category=$_POST['selected_sub_Category'];
    $Age_ID=$_POST['Age_ID'];
    foreach ($selected_sub_Category as $Item_test){
        $sql_check_exist = mysqli_query($conn,"SELECT laboratory_test_id FROM tbl_attach_age_laboratory_test WHERE laboratory_test_id='$Item_test' AND age_range_id ='$Age_ID'");
        if(mysqli_num_rows($sql_check_exist)<=0){
            $sql_attache_result=mysqli_query($conn,"INSERT INTO tbl_attach_age_laboratory_test (laboratory_test_id,age_range_id)VALUES($Item_test,$Age_ID)") or dei(mysqli_error($conn));
        }
    }   
}
?>

<table class="table">
<?php
$Age_ID=$_POST['Age_ID'];
// echo $Age_ID;
$sql_select_laboratory_test_result=mysqli_query($conn,"SELECT it.Product_Name,tes.attach_id FROM tbl_items it,tbl_attach_age_laboratory_test tes WHERE it.Item_ID=tes.laboratory_test_id AND age_range_id='$Age_ID'") or die(mysqli_error($conn));
           if(mysqli_num_rows($sql_select_laboratory_test_result)>0){
                                $count=1;
                                while($sub_test_rows=mysqli_fetch_assoc($sql_select_laboratory_test_result)){
                                    $Item_ID=$sub_test_rows['attach_id'];
                                    $Item_laboratory_test_Name=$sub_test_rows['Product_Name'];
                                    echo "<tr>
                                                <td style='width:50px'>
                                                    $count
                                                </td>
                                                <td>
                                                   <input type='checkbox'class='diagnosis_id' name='diagnosis_id' value='$Item_ID'>$Item_laboratory_test_Name
                                                </td>
                                                
                                          </tr>";
                                    $count++;
                                }
                            }
 ?>
</table>
