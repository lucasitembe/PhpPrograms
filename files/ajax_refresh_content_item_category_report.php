<?php
 include("./includes/connection.php");
$Item_Category_ID="0";
if(isset($_POST['Item_report_category'])){
    $Item_report_category=$_POST['Item_report_category'];  
}
?>
<table class="table">
<?php
$Item_report_category=$_POST['Item_report_category'];
$sql_select_attached_item_result=mysqli_query($conn,"SELECT Product_Name,Item_ID FROM tbl_items WHERE Status='Available' AND revenue_report_category='$Item_report_category'") or die(mysqli_error($conn));
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