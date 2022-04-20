<?php
include("./includes/connection.php");
$Item_Category_ID="0";
if(isset($_POST['selected_items'])){
    $selected_items=$_POST['selected_items'];
    
    foreach ($selected_items as $Item_ID){
         $sql_attache_result=mysqli_query($conn,"UPDATE tbl_items SET nurse_can_add='yes' WHERE Item_ID='$Item_ID'") or dei(mysqli_error($conn));
    }   
}
?>


<table class="table">
<?php 
    $sql_select_category_result=mysqli_query($conn,"SELECT Product_Name,Item_ID,Item_Subcategory_Name FROM tbl_items i INNER JOIN tbl_item_subcategory tis ON i.Item_Subcategory_ID=tis.Item_Subcategory_ID WHERE Status='Available' AND nurse_can_add='yes' ORDER BY Product_Name ASC") or die(mysqli_error($conn));
    if(mysqli_num_rows($sql_select_category_result)>0){
        $count=1;
        while($category_rows=mysqli_fetch_assoc($sql_select_category_result)){
            $Item_ID=$category_rows['Item_ID'];
            $Product_Name=$category_rows['Product_Name'];
            $Item_Subcategory_Name=$category_rows['Item_Subcategory_Name'];
            echo "<tr>
                        <td>
                        $count
                        </td>
                        <td>
                            <label style='font-weight:normal'>
                                <input type='checkbox'class='Item_ID2' name='Item_ID2' value='$Item_ID'>$Product_Name <label>~~~($Item_Subcategory_Name)</label>
                            </label>
                        </td>

                    </tr>";
            $count++;
        }
    }
?>
</table>


<?php
    mysqli_close($conn);
?>
