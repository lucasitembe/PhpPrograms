<?php
 include("./includes/connection.php");
 if(isset($_POST['Item_Category_ID'])){
    $Item_Category_ID=$_POST['Item_Category_ID'];
    ?>
 
<table class="table">
                        <?php 
                            $sql_select_category_result=mysqli_query($conn,"SELECT *FROM tbl_item_subcategory WHERE Item_Category_ID<>'$Item_Category_ID'") or die(mysqli_error($conn));
                            if(mysqli_num_rows($sql_select_category_result)>0){
                                while($category_rows=mysqli_fetch_assoc($sql_select_category_result)){
                                    $Item_Subcategory_ID=$category_rows['Item_Subcategory_ID'];
                                    $Item_Subcategory_Name=$category_rows['Item_Subcategory_Name'];
                                    echo "<tr>
                                                <td>
                                                    <label style='font-weight:normal'>
                                                        <input type='checkbox'class='Item_Subcategory_ID' name='Item_Subcategory_ID' value='$Item_Subcategory_ID'>$Item_Subcategory_Name
                                                    </label>
                                                </td>
                                                
                                          </tr>";
                                }
                            }
                        ?>
</table>
 <?php }