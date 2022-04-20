<?php
@session_start();
include("./includes/connection.php");
if(isset($_POST['procedure_name'])){
    $procedure_name=$_POST['procedure_name'];
    $sql_select_all_procedure_result=mysqli_query($conn,"SELECT Product_Name,Item_ID FROM tbl_items WHERE Consultation_Type='Procedure' AND Product_Name LIKE '%$procedure_name%' LIMIT 50") or die(mysqli_error($conn));
    if(mysqli_num_rows($sql_select_all_procedure_result)>0){
        $count_sn=1;
        while($procedure_rows=mysqli_fetch_assoc($sql_select_all_procedure_result)){
           $Product_Name=$procedure_rows['Product_Name'];
           $Item_ID=$procedure_rows['Item_ID'];
           echo "<tr>
                    <td>$count_sn.</td>
                    <td>$Product_Name</td>
                    <td><input type='button' value='>>' onclick='shift_this_item_to_setup($Item_ID)'/></td>
                </tr>";
           $count_sn++;
        }
    }
}