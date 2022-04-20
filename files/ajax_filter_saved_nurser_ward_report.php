<?php
include("./includes/connection.php");
if(isset($_POST['ward_id'])){
    $ward_id=$_POST['ward_id'];
    $start_date=$_POST['start_date'];
    $end_date=$_POST['end_date'];
    $filter="";
   if($start_date==""||$end_date==""){
      $filter=" AND DATE(saved_date)=CURDATE()"; 
   }else{
       $filter=" AND saved_date BETWEEN '$start_date' AND '$end_date'";
   }
   $sql_select_saved_nurse_ward_report_result=mysqli_query($conn,"SELECT Employee_Name,nurse_ward_report,ward_id,saved_by,saved_date FROM tbl_nurse_ward_report nwr,tbl_employee emp WHERE  nwr.saved_by=emp.Employee_ID AND  ward_id='$ward_id' $filter") or die(mysqli_error($conn));

    if(mysqli_num_rows($sql_select_saved_nurse_ward_report_result)>0){
        $count=1;
        while($rows=mysqli_fetch_assoc($sql_select_saved_nurse_ward_report_result)){
            $nurse_ward_report=$rows['nurse_ward_report'];
            $saved_by=$rows['Employee_Name'];
            $saved_date=$rows['saved_date'];
            echo "<tr>
                    <td>$count.</td>
                    <td>$nurse_ward_report</td>
                    <td>$saved_date</td>
                    <td>$saved_by</td>
                 </tr>";
            $count++;
        }
    }
}
