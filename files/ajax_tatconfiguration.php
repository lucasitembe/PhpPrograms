<?php
/**
 * Created by PhpStorm.
 * User: gpitg
 * Date: 1/7/19
 * Time: 11:05 AM
 */

include("./includes/connection.php");

if (isset($_GET['request'])){
    $item_id = trim($_GET['Item_ID']);
    $min_range = trim($_GET['min_range']);

    $sql_attache_result=mysqli_query($conn,"UPDATE `tbl_items` SET `tat_normal_range`='$min_range' WHERE `Item_ID`='$item_id'") or dei(mysqli_error($conn));
    echo "Saved Succsesfull.";
}

if (isset($_GET['getFilterValue'])){
    $query = "SELECT * FROM tbl_items where Consultation_Type='Laboratory'";
    $output = "";
    $index = 1;
    $sql_select_laboratory_test=mysqli_query($conn,$query) or die(mysqli_error($conn));
    if(mysqli_num_rows($sql_select_laboratory_test)>0){
        while($category_rows=mysqli_fetch_assoc($sql_select_laboratory_test)){
            $Item_ID = $category_rows['Item_ID'];
            $Product_Name = trim($category_rows['Product_Name']);
            $tat_normal_range = trim($category_rows['tat_normal_range']);
            $firstId = $category_rows['Item_ID']."first";
            $secondId = $category_rows['Item_ID']."second";
            $outputId = $firstId."-".$secondId;
            $output .= "<tr>
                            <td>$index</td>
                            <td>$Product_Name</td>
                            <td>$tat_normal_range</td>
                            <td><input type='text' id='$firstId' value='' ></td>
                            <td><select id='$secondId'><option value=''>Select Units</option><option value='Hrs'>Hrs</option><option value='Min'>Min</option></select></td>
                            <td><button type='submit' id='$Item_ID' name='$outputId' class='btn btn-primary' onclick='update_tat(this);'>Update</button></td>
                      </tr>";
            $index ++;
        }
        echo $output;
    }

}
