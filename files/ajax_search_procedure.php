<?php
include("./includes/connection.php");
$Product_Name=mysqli_real_escape_string($conn,$_POST['Product_Name']);

$sql_search_procedure=mysqli_query($conn,"SELECT Item_ID, Product_Name FROM tbl_items WHERE Product_Name LIKE '%$Product_Name%' AND (Consultation_Type='Procedure' OR Consultation_Type='Surgery') LIMIT 50") or die(mysqli_error($conn));
if(mysqli_num_rows($sql_search_procedure)>0){ 
    $count_sn=1;
    while($employee_rows=mysqli_fetch_assoc($sql_search_procedure)){
        $Item_ID=$employee_rows['Item_ID'];
        $Product_Name=$employee_rows['Product_Name'];
        echo "<tr class='rows_list' onclick='save_anasthesia_procedure($Item_ID)'>

                <td>$count_sn</td>
                <td>$Product_Name</td>
                
            </tr>";  
            $count_sn++;
    }
}

?>