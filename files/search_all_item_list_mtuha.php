<?php
 include("./includes/connection.php");
 if(isset($_POST['Product_Name'])){
    $Product_Name=$_POST['Product_Name'];
    ?>

<table class="table">
<?php
$sql_select_attached_category_result=mysqli_query($conn,"SELECT *FROM tbl_items  WHERE   Consultation_Type='Procedure' AND Product_Name LIKE '%$Product_Name%'") or die(mysqli_error($conn));
           if(mysqli_num_rows($sql_select_attached_category_result)>0){
                                $count=1;
                                while($category_rows=mysqli_fetch_assoc($sql_select_attached_category_result)){
                                    $Item_ID=$category_rows['Item_ID'];
                                    $Product_Name=$category_rows['Product_Name'];
                                      echo "<tr>
                                                <td>
                                                    <label style='font-weight:normal'>
                                                        <input type='checkbox'class='Item_ID' name='Item_ID' value='$Item_ID'>$Product_Name
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


