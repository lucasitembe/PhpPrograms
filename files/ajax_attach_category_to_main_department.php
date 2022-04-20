<?php
 include("./includes/connection.php");
$idara_id="0";
if(isset($_POST['selectedCategory'])){
    $selectedCategory=$_POST['selectedCategory'];
    $idara_id=$_POST['idara_id'];
    foreach ($selectedCategory as $Item_Category_ID){
         $Item_Category_ID;
         $sql_attache_result=mysqli_query($conn,"UPDATE tbl_item_category SET idara_id='$idara_id' WHERE Item_Category_ID='$Item_Category_ID'") or dei(mysqli_error($conn));
    }   
}
?>

<table class="table">
<?php
$idara_id=$_POST['idara_id'];
$sql_select_attached_category_result=mysqli_query($conn,"SELECT *FROM tbl_item_category  WHERE idara_id='$idara_id'") or die(mysqli_error($conn));
           if(mysqli_num_rows($sql_select_attached_category_result)>0){
                                $count=1;
                                while($category_rows=mysqli_fetch_assoc($sql_select_attached_category_result)){
                                    $Item_Category_ID=$category_rows['Item_Category_ID'];
                                    $Item_Category_Name=$category_rows['Item_Category_Name'];
                                    echo "<tr>
                                                <td style='width:50px'>
                                                    $count
                                                </td>
                                                <td>
                                                    $Item_Category_Name
                                                </td>
                                                
                                          </tr>";
                                    $count++;
                                }
                            }
 ?>
</table>


