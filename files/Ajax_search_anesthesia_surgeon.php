<?php
include("./includes/connection.php");
$Employee_Name=mysqli_real_escape_string($conn,$_POST['Employee_Name']);

$sql_search_surgeon_result=mysqli_query($conn,"SELECT Employee_ID, Employee_Name FROM tbl_employee WHERE  Employee_Name LIKE '%$Employee_Name%' AND Employee_Job_Code='Surgeon' LIMIT 50") or die(mysqli_error($conn));
if(mysqli_num_rows($sql_search_surgeon_result)>0){ 
    $count_sn=1;
    while($employee_rows=mysqli_fetch_assoc($sql_search_surgeon_result)){
        $Employee_ID=$employee_rows['Employee_ID'];
        $Employee_Name=$employee_rows['Employee_Name'];
        echo "<tr class='rows_list' onclick='save_anasthesia_surgeon($Employee_ID)'>
                <td>$count_sn</td>
                <td>$Employee_Name</td>
                
            </tr>";
            $count_sn++;
    }
}

?>