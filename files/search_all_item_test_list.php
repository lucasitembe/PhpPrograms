<?php
 include("./includes/connection.php");
 if(isset($_POST['Item_Test_Name'])){
    $Item_Test_Name=$_POST['Item_Test_Name'];
    ?>
 
<table class="table">
                        <?php 
                            $sql_select_test_result=mysqli_query($conn,"SELECT *FROM tbl_items WHERE Product_Name LIKE '%$Item_Test_Name%'") or die(mysqli_error($conn));
                            if(mysqli_num_rows($sql_select_test_result)>0){
                                while($category_rows=mysqli_fetch_assoc($sql_select_test_result)){
                                    $Item_ID=$category_rows['Item_ID'];
                                    $Item_Name=$category_rows['Product_Name'];
                                    echo "<tr>
                                                <td>
                                                    <label style='font-weight:normal'>
                                                        <input type='checkbox'class='Item_ID' name='Item_ID' value='$Item_ID'>$Item_Name
                                                    </label>
                                                </td>
                                                
                                          </tr>";
                                }
                            }
                        ?>
</table>
 <?php }