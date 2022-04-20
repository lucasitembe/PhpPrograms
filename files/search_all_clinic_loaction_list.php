<?php
 include("./includes/connection.php");
 if(isset($_POST['Item_Category_Name'])){
    $Item_Category_Name=$_POST['Item_Category_Name'];
    ?>

<table class="table">
<?php
$sql_select_attached_category_result=mysqli_query($conn,"SELECT * FROM tbl_sub_department  WHERE Sub_Department_Name LIKE '%$Item_Category_Name%'") or die(mysqli_error($conn));
           if(mysqli_num_rows($sql_select_attached_category_result)>0){
                                $count=1;
                                while($category_rows=mysqli_fetch_assoc($sql_select_attached_category_result)){
                                    $Item_Category_ID=$category_rows['Sub_Department_ID'];
                                    $Item_Category_Name=$category_rows['Sub_Department_Name'];
                                      echo "<tr>
                                                <td>
                                                    <label style='font-weight:normal'>
                                                        <input type='checkbox'class='category_id' name='category_id' value='$Item_Category_ID'>$Item_Category_Name
                                                    </label>
                                                </td>
                                                
                                          </tr>";
                                }
                            }
 ?>
</table>
    <?php
 }

?>


