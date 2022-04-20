<?php
 include("./includes/connection.php");
$Item_Category_ID="0";
if(isset($_POST['Item_Subcategory_ID'])){
    $selected_items=$_POST['selected_items'];
    $Item_Subcategory_ID=$_POST['Item_Subcategory_ID'];
    $consultation_type=$_POST['consultation_type'];
    $Item_Type=$_POST['Item_Type'];
    foreach ($selected_items as $Item_ID){
         $sql_attache_result=mysqli_query($conn,"UPDATE tbl_items SET Item_Type='$Item_Type',Item_Subcategory_ID='$Item_Subcategory_ID',Consultation_Type='$consultation_type' WHERE Item_ID='$Item_ID'") or dei(mysqli_error($conn));
    }   
}
?>

<table class="table">
<?php
$Item_Subcategory_ID=$_POST['Item_Subcategory_ID'];
$sql_select_attached_item_result=mysqli_query($conn,"SELECT Product_Name,Item_ID FROM tbl_items WHERE Status='Available' AND Item_Subcategory_ID='$Item_Subcategory_ID'") or die(mysqli_error($conn));
           if(mysqli_num_rows($sql_select_attached_item_result)>0){
                                $count=1;
                                while($item_rows=mysqli_fetch_assoc($sql_select_attached_item_result)){
                                    $Item_ID=$item_rows['Item_ID'];
                                    $Product_Name=$item_rows['Product_Name'];
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


