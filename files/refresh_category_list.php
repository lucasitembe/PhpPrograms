<?php
 include("./includes/connection.php");
 if(isset($_POST['Item_Category_ID'])){
    $Item_Category_ID=$_POST['Item_Category_ID'];
 ?>
<table class="table">
<?php
$Item_Category_ID=$_POST['Item_Category_ID'];
$sql_select_attached_sub_category_result=mysqli_query($conn,"SELECT *FROM tbl_item_subcategory  WHERE Item_Category_ID='$Item_Category_ID'") or die(mysqli_error($conn));
           if(mysqli_num_rows($sql_select_attached_sub_category_result)>0){
                                $count=1;
                                while($sub_category_rows=mysqli_fetch_assoc($sql_select_attached_sub_category_result)){
                                    $Item_Subcategory_ID=$sub_category_rows['Item_Subcategory_ID'];
                                    $Item_Subcategory_Name=$sub_category_rows['Item_Subcategory_Name'];
                                    echo "<tr>
                                                <td style='width:50px'>
                                                    $count
                                                </td>
                                                <td>
                                                    $Item_Subcategory_Name
                                                </td>
                                                
                                          </tr>";
                                    $count++;
                                }
                            }
 
 }
 ?>
</table>


