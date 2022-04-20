<?php
include("./includes/connection.php");
$disease_code=mysqli_real_escape_string($conn,$_POST['disease_code']);
$disease_name=mysqli_real_escape_string($conn,$_POST['disease_name']);

$sql_search_disease_code_result=mysqli_query($conn,"SELECT disease_ID,disease_code,disease_name FROM tbl_disease WHERE disease_code LIKE '%$disease_code%' AND disease_name LIKE '%$disease_name%' AND disease_version='icd_10' LIMIT 50") or die(mysqli_error($conn));
if(mysqli_num_rows($sql_search_disease_code_result)>0){
    $count_sn=1;
    while($disease_rows=mysqli_fetch_assoc($sql_search_disease_code_result)){
        $disease_ID=$disease_rows['disease_ID'];
        $disease_code=$disease_rows['disease_code'];
        $disease_name=$disease_rows['disease_name'];
        echo "<tr class='rows_list' onclick='save_disease_anathesia($disease_ID)'>
                <td>$count_sn</td>
                <td>$disease_name</td>
                <td>$disease_code</td>
                 
            </tr>";
            $count_sn++;
    }
}

?>