<?php
include("./includes/connection.php");
if(isset($_POST['start_date'])){
    $start_date=$_POST['start_date'];
    $end_date=$_POST['end_date'];
    $Employee_Name= mysqli_real_escape_string($conn,$_POST['Employee_Name']);
    $sql_fetch_system_audit_trail_result=mysqli_query($conn,"SELECT Employee_Name,login_time,logout_time,user_ip_adress,sat.system_audit_trail_id FROM system_audit_trail_tbl sat,tbl_employee emp WHERE sat.Employee_ID=emp.Employee_ID AND login_time BETWEEN '$start_date' AND '$end_date' AND Employee_Name LIKE '%$Employee_Name%' GROUP BY sat.system_audit_trail_id ORDER BY sat.system_audit_trail_id DESC") or die(mysqli_error($conn));
    
    $count_sn=1;
    if(mysqli_num_rows($sql_fetch_system_audit_trail_result)>0){
        while($audit_rows=mysqli_fetch_assoc($sql_fetch_system_audit_trail_result)){
            $Employee_Name=$audit_rows['Employee_Name'];
            $login_time=$audit_rows['login_time'];
            $logout_time=$audit_rows['logout_time'];
            $user_ip_adress=$audit_rows['user_ip_adress'];
            $system_audit_trail_id=$audit_rows['system_audit_trail_id'];
            echo "<tr>
                        <td>$count_sn.</td>
                        <td style='text-align:center'>$Employee_Name</td>
                        <td style='text-align:center'>$login_time</td>
                        <td style='text-align:center'>$logout_time</td>
                        <td style='text-align:center'>$user_ip_adress</td>
                        <td><table class='table table-bordered'><tr><th style='width:50px'>S/No.</th><th style='width:15%'>Activities Time</th><th>Activities Description</th></tr>";
                        //elect user activities
                        $sql_select_employee_activities_result=mysqli_query($conn,"SELECT activities,saved_date_time FROM activities_log_tbl WHERE system_audit_trail_id='$system_audit_trail_id' ORDER BY activities_log_id ASC") or die(mysqli_error($conn));
                        if(mysqli_num_rows($sql_select_employee_activities_result)>0){
                            $count_act=1;
                            while($actv_rows= mysqli_fetch_assoc($sql_select_employee_activities_result)){
                                $activities=$actv_rows['activities'];
                                $saved_date_time=$actv_rows['saved_date_time'];
                                echo "<tr><td>$count_act.</td>
                                                <td>$saved_date_time</td>
                                                <td>$activities</td>
                                            </tr>";
                                $count_act++;
                            }
                        }else{
                            echo "<tr style='background:ghostwhite'><th colspan='3'>NO RECORDED ACTIVITIES</th></tr>";
                        }
            echo "</table></td>
                  </tr>";
            $count_sn++;
        }
    }
}

