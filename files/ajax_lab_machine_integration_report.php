<?php
include("./includes/connection.php");
    if(isset($_POST['start_date'])&&isset($_POST['end_date'])&&isset($_POST['search_item'])){
        $start_date=$_POST['start_date'];
        $end_date=$_POST['end_date'];
        $search_item=$_POST['search_item'];
        $sql_select_result=mysqli_query($conn,"SELECT *,COUNT(intergrated_lab_results_id) as received_result FROM tbl_intergrated_lab_results WHERE result_date BETWEEN '$start_date' AND '$end_date'  AND (sample_test_id LIKE '%$search_item%' OR operator LIKE '%$search_item%') GROUP BY result_date") or die(mysqli_error($conn));
        if(mysqli_num_rows($sql_select_result)>0){
            $count_sn=1;
            while($result_rows=mysqli_fetch_assoc($sql_select_result)){
                $sample_test_id=$result_rows['sample_test_id'];
                $operator=$result_rows['operator'];
                $result_date=$result_rows['result_date'];
                $obser_date=$result_rows['obser_date'];
                $validated=$result_rows['validated'];
                $sent_to_doctor=$result_rows['sent_to_doctor'];
                $received_result=$result_rows['received_result'];
                //verify the source of sample id
                $sql_check_if_valid_ehms_sample_code_resulr=mysqli_query($conn,"SELECT payment_item_ID FROM tbl_specimen_results WHERE TimeCollected BETWEEN '$start_date' AND '$end_date' AND payment_item_ID='$sample_test_id'") or die(mysqli_error($conn));
                if(mysqli_num_rows($sql_check_if_valid_ehms_sample_code_resulr)>0){
                   $sample_id_value_source="from ehms"; 
                }else{
                   $sample_id_value_source="unknown";  
                }
                echo "<tr>
                            <td>$count_sn</td>
                            <td>$sample_test_id</td>
                            <td>$operator</td>
                            <td>$received_result</td>
                            <td>$obser_date</td>
                            <td>$result_date</td>
                            <td>$validated</td>
                            <td>$sent_to_doctor</td>
                            <td>$sample_id_value_source</td>
                      </tr>";
                $count_sn++;
            }
        }
    }
