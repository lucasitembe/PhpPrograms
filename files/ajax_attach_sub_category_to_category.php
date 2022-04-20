<?php
 include("./includes/connection.php");
$Item_Category_ID="0";
if(isset($_POST['selected_sub_Category'])){
    $selected_sub_Category=$_POST['selected_sub_Category'];
    $Item_Category_ID=$_POST['Item_Category_ID'];
    foreach ($selected_sub_Category as $Item_Subcategory_ID){
         $sql_attache_result=mysqli_query($conn,"UPDATE tbl_item_subcategory SET Item_Category_ID='$Item_Category_ID' WHERE Item_Subcategory_ID='$Item_Subcategory_ID'") or dei(mysqli_error($conn));
    }   
}
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
 ?>
</table>


