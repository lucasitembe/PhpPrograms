<?php
 include("./includes/connection.php");
 if(isset($_POST['Treatment_ID'])){
    $Treatment_ID=$_POST['Treatment_ID'];
    ?>
<table class="table">
<?php 
$sql_select_attached_category_result=mysqli_query($conn,"SELECT tc.Item_ID, Product_Name FROM tbl_items i, tbl_mtuha_treatment_category tc WHERE i.Item_ID=tc.Item_ID AND  Treatment_ID='$Treatment_ID'") or die(mysqli_error($conn));
           if(mysqli_num_rows($sql_select_attached_category_result)>0){
                                $count=1;
                                while($category_rows=mysqli_fetch_assoc($sql_select_attached_category_result)){
                                    $Item_ID=$category_rows['Item_ID'];
                                    $Product_Name=$category_rows['Product_Name'];
                                    echo "<tr>
                                                <td style='width:50px'>
                                                    $count
                                                </td>
                                                <td>
                                                    $Product_Name
                                                </td> 
                                                
                                          </tr>"; 
                                    $count++;
                                }
                            }
 ?>
</table>
    <?php
 }

?>
