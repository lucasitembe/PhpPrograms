<?php
 include("./includes/connection.php");
$Treatment_ID="0";
if(isset($_POST['selectedCategory'])){
    $selectedCategory=$_POST['selectedCategory'];
    $Treatment_ID=$_POST['Treatment_ID'];
    
    foreach ($selectedCategory as $Item_ID){
         $Item_ID;
         $select_Item_result = mysqli_query($conn, "SELECT Item_ID FROM tbl_mtuha_treatment_category WHERE Item_ID='$Item_ID'") or die(mysqli_error($conn));
         if((mysqli_num_rows($select_Item_result))>0){
             
             $sql_attacheID_Update = mysqli_query($conn, "UPDATE tbl_mtuha_treatment_category SET Treatment_ID='$Treatment_ID' WHERE Item_ID='$Item_ID'") or die(mysqli_error($conn));
         }else{
         $sql_attache_result=mysqli_query($conn,"INSERT  INTO tbl_mtuha_treatment_category (Item_ID, Treatment_ID) VALUES('$Item_ID', '$Treatment_ID')") or die(mysqli_error($conn));
         }
        }   
}  
?>

<table class="table">
<?php
$Treatment_ID=$_POST['Treatment_ID'];
$sql_select_attached_category_result=mysqli_query($conn,"SELECT tc.Item_ID, Product_Name FROM tbl_items i, tbl_mtuha_treatment_category tc WHERE i.Item_ID=tc.Item_ID AND  Treatment_ID='$Treatment_ID'") or die(mysqli_error($conn));
           if(mysqli_num_rows($sql_select_attached_category_result)>0){
                                $count=1;
                                while($category_rows=mysqli_fetch_assoc($sql_select_attached_category_result)){
                                    $Item_ID=$category_rows['Item_ID'];
                                    $Product_Name=$category_rows['Product_Name'];
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


