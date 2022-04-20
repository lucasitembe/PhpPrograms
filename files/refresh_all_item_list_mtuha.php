<?php
 include("./includes/connection.php");
 if(isset($_POST['Treatment_ID'])){
    $Treatment_ID=$_POST['Treatment_ID'];
    ?>

<table class="table"> 
<?php
$sql_select_attached_item_result=mysqli_query($conn,"SELECT tc.Item_ID, Product_Name FROM tbl_items i, tbl_mtuha_treatment_category tc WHERE tc.Item_ID=i.Item_ID AND Treatment_ID='$Treatment_ID' ") or die(mysqli_error($conn));
           if(mysqli_num_rows($sql_select_attached_item_result)>0){
                                $count=1;
                                while($item_rows=mysqli_fetch_assoc($sql_select_attached_item_result)){
                                    $Item_ID=$item_rows['Item_ID'];
                                    $Product_Name=$item_rows['Product_Name'];
                                      echo "<tr>
                                                <td>
                                                    <label style='font-weight:normal'>
                                                        <input type='checkbox'class='Item_ID' name='Item_ID' value='$Item_ID'>$Product_Name
                                                    </label>
                                                </td>
                                                
                                          </tr>";
                                }
                            }else{
                                $sql_select_item_result=mysqli_query($conn,"SELECT Item_ID,	Product_Name FROM tbl_items i WHERE i.Status='Available' AND Consultation_Type=('Procedure' OR 'Surgery' ) LIMIT 50") or die(mysqli_error($conn));
           if(mysqli_num_rows($sql_select_item_result)>0){
                                $count=1;
                                while($item=mysqli_fetch_assoc($sql_select_item_result)){
                                    $Item_ID=$item['Item_ID'];
                                    $Product_Name=$item['Product_Name'];
                                      echo "<tr>
                                                <td>
                                                    <label style='font-weight:normal'>
                                                        <input type='checkbox'class='Item_ID' name='Item_ID' value='$Item_ID'>$Product_Name
                                                    </label>
                                                </td>
                                                
                                          </tr>";
                                }
                            }
                            }
 ?>
</table>
    <?php
 }

?>


