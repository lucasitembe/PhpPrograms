<?php
@session_start();
include("./includes/connection.php");
if(isset($_POST['Item_ID'])){
    $Item_ID=$_POST['Item_ID'];

        $sql_insert_item_to_procedure_setup_result=mysqli_query($conn,"DELETE FROM tbl_procedure_setup WHERE Item_ID='$Item_ID'") or die(mysqli_error($conn));
}
//select all procedure from setup
$sql_select_all_procedure_from_setup_result=mysqli_query($conn,"SELECT ps.Item_ID,Product_Name FROM tbl_procedure_setup ps,tbl_items it WHERE ps.Item_ID=it.Item_ID") or die(mysqli_error($conn));
if(mysqli_num_rows($sql_select_all_procedure_from_setup_result)>0){
    $count_sn=1;
    while($selected_proc_rows=mysqli_fetch_assoc($sql_select_all_procedure_from_setup_result)){
       $Product_Name=$selected_proc_rows['Product_Name'];
       $Item_ID=$selected_proc_rows['Item_ID'];
       echo "<tr>
                    <td>$count_sn.</td>
                    <td>$Product_Name</td>
                    <td><input type='button' value='X' onclick='remove_this_item_to_setup($Item_ID)'/></td>
                </tr>";
           $count_sn++;
    }
}