  <table class="table"> 
<?php 
 include("./includes/connection.php");
    $Item_Subcategory_Name  = $_POST['Item_Subcategory_Name'];
                            $sql_select_category_result=mysqli_query($conn,"SELECT disease_name,disease_ID FROM tbl_disease WHERE disease_version='icd_10' AND disease_name LIKE '%$Item_Subcategory_Name%'") or die(mysqli_error($conn));
                            if(mysqli_num_rows($sql_select_category_result)>0){
                                while($category_rows=mysqli_fetch_assoc($sql_select_category_result)){
                                    $disease_ID=$category_rows['disease_ID'];
                                    $disease_name=$category_rows['disease_name'];
                                    echo "<tr>
                                                <td>
                                                    <label style='font-weight:normal'>
                                                        <input type='checkbox'class='Item_Subcategory_ID' name='Item_Subcategory_ID' value='$disease_ID'>$disease_name
                                                    </label>
                                                </td>
                                                
                                          </tr>";
                                }
                            }
                        ?>
</table>