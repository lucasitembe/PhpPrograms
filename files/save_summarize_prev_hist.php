<?php
include("./includes/connection.php");
if(isset($_GET['patient_previous_sum_hist_content'])){
    $patient_previous_sum_hist_content=$_GET['patient_previous_sum_hist_content'];
}else{
   $patient_previous_sum_hist_content=""; 
}
if(isset($_GET['Registration_ID'])){
    $Registration_ID=$_GET['Registration_ID'];
}else{
   $Registration_ID=""; 
}
session_start();
?>
    <tr>
        <th>S/No.</th>
        <th>Notes</th>
        <th>Saved Date And Time</th>
        <th>Saved By</th>
    </tr>
    <?php
$Employee_ID = $_SESSION['userinfo']['Employee_ID'];
if(!empty($Registration_ID)&&!empty($patient_previous_sum_hist_content)){
 $sql_save_hist_result=mysqli_query($conn,"INSERT INTO tbl_patient_summarize_prev_hist(summarize_prev_hist,Registration_ID,saved_date_n_time,Employee_ID) VALUES('$patient_previous_sum_hist_content','$Registration_ID',NOW(),'$Employee_ID')") or die(mysqli_error($conn));
   if($sql_save_hist_result){
        $sql_select_saved_result=mysqli_query($conn,"SELECT summarize_prev_hist,saved_date_n_time,Employee_Name FROM tbl_patient_summarize_prev_hist psph INNER JOIN tbl_employee e ON  psph.Employee_ID =e.Employee_ID WHERE Registration_ID='$Registration_ID'") or die(mysqli_error($conn));
        if(mysqli_num_rows($sql_select_saved_result)>0){
            $count=1;
            while($hist_rows=mysqli_fetch_assoc($sql_select_saved_result)){
                $patient_previous_sum_hist_content=$hist_rows['summarize_prev_hist'];
                $saved_date_n_time=$hist_rows['saved_date_n_time'];
                $Employee_Name=$hist_rows['Employee_Name'];
                echo "<tr>
                            <td style='width:50px'>$count</td>
                            <td>$patient_previous_sum_hist_content</td>
                            <td>$saved_date_n_time</td>
                            <td>$Employee_Name</td>
                      </tr>";
                $count++;
            }
        }
   }
}

