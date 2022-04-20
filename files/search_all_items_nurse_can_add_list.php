<?php
 include("./includes/connection.php");
 if(isset($_POST['Product_Name']) || isset($_POST['consultation_type'])){
    $Product_Name = $_POST['Product_Name'];  
    
    $filter1 = "";
    $filter2 = "";

    if($_POST['Product_Name'] != "") {
        $Product_Name = $_POST['Product_Name'];
        $filter1 = " AND Product_Name LIKE '%$Product_Name%' ";
    } else {
        $consultation_type = "";
    }

    if($_POST['consultation_type'] != "") {
        $consultation_type = $_POST['consultation_type'];
        $filter2 = " AND Consultation_Type = '$consultation_type' ";
    } else {
        $consultation_type = "";
    }

    if(isset($_POST['nurse_can_add']) && $_POST['nurse_can_add'] == "yes") {
        
    ?>
 
    
    
    <table class="table">
    <?php 
        $sql_select_category_result=mysqli_query($conn,"SELECT Product_Name,Item_ID,Item_Subcategory_Name FROM tbl_items i INNER JOIN tbl_item_subcategory tis ON i.Item_Subcategory_ID=tis.Item_Subcategory_ID WHERE Status='Available' $filter1 $filter2 AND nurse_can_add='yes' ORDER BY Product_Name ASC") or die(mysqli_error($conn));
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
        } else {
            echo "<h5 style='color: red;text-align: center;font-size: 20px;'>No Item Found</h5>";
        }
    
    ?>
    </table>
    <?php 
    } else { ?>


        <table class="table">
        <?php 
            $sql_select_category_result=mysqli_query($conn,"SELECT Product_Name,Item_ID,Item_Subcategory_Name FROM tbl_items i INNER JOIN tbl_item_subcategory tis ON i.Item_Subcategory_ID=tis.Item_Subcategory_ID WHERE Status='Available' $filter1 $filter2 AND nurse_can_add='no' ORDER BY Product_Name ASC") or die(mysqli_error($conn));
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
                                        <input type='checkbox'class='Item_ID' name='Item_ID' value='$Item_ID'>$Product_Name <label>~~~($Item_Subcategory_Name)</label>
                                    </label>
                                </td>

                            </tr>";
                    $count++;
                }
            } else {
                echo "<h5 style='color: red;text-align: center;font-size: 20px;'>No Item Found</h5>";
            }
        ?>
        </table>
    <?php
    }
}

?>