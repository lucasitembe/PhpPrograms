<?php
 include("./includes/connection.php");
 if(isset($_POST['Product_Name'])){
    $Product_Name=$_POST['Product_Name'];
    $consultation_type=$_POST['consultation_type'];
    $limit="";
    if($consultation_type==""){
       $limit=" LIMIT 100"; 
    }
    ?>
 
<table class="table">
                <?php 
                                $sql_select_category_result=mysqli_query($conn,"SELECT revenue_report_category,Product_Name,Item_ID FROM tbl_items WHERE Status='Available'  AND Product_Name LIKE '%$Product_Name%' AND consultation_type LIKE '%$consultation_type' ORDER BY Product_Name ASC  $limit") or die(mysqli_error($conn));
                                if(mysqli_num_rows($sql_select_category_result)>0){
                                    $count=1;
                                    while($category_rows=mysqli_fetch_assoc($sql_select_category_result)){
                                        $Item_ID=$category_rows['Item_ID'];
                                        $Product_Name=$category_rows['Product_Name'];
                                        $revenue_report_category=$category_rows['revenue_report_category'];
                                        echo "<tr>
                                                    <td>
                                                    $count
                                                    </td>
                                                    <td>
                                                        <label style='font-weight:normal'>
                                                            <input type='checkbox'class='Item_ID' name='Item_ID' value='$Item_ID'>$Product_Name <label>~~~($revenue_report_category)</label>
                                                        </label>
                                                    </td>

                                              </tr>";
                                        $count++;
                                    }
                                }
                            ?>
</table>
 <?php }