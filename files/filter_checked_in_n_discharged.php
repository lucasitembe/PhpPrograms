<?php
include("./includes/connection.php");

$Date_From = $_GET['Date_From'];
$Date_To = $_GET['Date_To'];

$filter="";
$filter2="";
$filter4 = "AND ci.Check_In_Date_And_Time BETWEEN  '$Date_From' AND  '$Date_To' ";
if(isset($_GET['patient_name'])){
    $patient_name=$_GET['patient_name'];
    if(!empty($patient_name)){
       $filter.="AND patient_name LIKE '%$patient_name%'"; 
       $filter2.="AND patient_name LIKE '%$patient_name%'"; 
        $filter3 = " order by dis_check_time limit 1";
    }
}
if(isset($_GET['patient_number'])){
    $patient_number=$_GET['patient_number'];
    if(!empty($patient_number)){
       $filter.="AND pr.Registration_ID = '$patient_number'"; 
       $filter2.="AND ad.Registration_ID = '$patient_number'"; 
       $filter3 = "order by dis_check_time limit 1";
    }
}

?>
<table id="list_of_checked_in_n_discharged_tbl" class="table table-bordered">
    <thead>
        <tr>
            <th style="width:50px">S/No.</th>
            <th>PATIENT NAME</th>
            <th>PATIENT NUMBER</th>
            <th>WARD FROM/DISTRICT</th>
            <th>TIME OF DISCHARGE/CHECKED IN</th>
            <th>DISCHARGED/CHECKED IN BY</th>
        </tr>
    </thead>
    <tbody>
           <?php 
                $count=1;


                $Today_Date = mysqli_query($conn,"select now() as today");
            
                

                // die("SELECT DISTINCT pr.Registration_ID, pr.Patient_Name,pr.District as Hospital_Ward_Name,ci.Check_In_Date_And_Time as dis_check_time,emp.Employee_Name as dischareged_chckin_by FROM tbl_check_in ci, tbl_patient_registration pr, tbl_employee emp AND ci.Registration_ID = pr.Registration_ID  AND ci.Employee_ID=emp.Employee_ID AND ci.Registration_ID NOT IN (SELECT Corpse_ID as Registration_ID FROM tbl_mortuary_admission) $filter AND Diseased='yes' AND pr.Registration_ID IN (SELECT Patient_ID FROM tbl_diceased_patients WHERE send_notsend_to_morgue='yes')");

                // die("SELECT pr.Registration_ID,Patient_Name,hw.Hospital_Ward_Name,ad.pending_set_time as dis_check_time,emp.Employee_Name as dischareged_chckin_by FROM tbl_patient_registration pr, tbl_admission ad, tbl_discharge_reason dr, tbl_hospital_ward hw, tbl_employee emp WHERE pr.Registration_ID=ad.Registration_ID AND ad.Discharge_Reason_ID=dr.Discharge_Reason_ID AND ad.Hospital_Ward_ID=hw.Hospital_Ward_ID AND ad.pending_setter=emp.Employee_ID AND (ad.Admission_Status='Discharged' OR ad.Admission_Status='pending') AND ward_type<>'mortuary_ward'  AND ad.Registration_ID NOT IN (SELECT Corpse_ID as Registration_ID FROM tbl_mortuary_admission) AND discharge_condition='dead' GROUP BY pr.Registration_ID");
               
                $sql_select_patient="SELECT DISTINCT pr.Registration_ID, pr.Patient_Name,pr.District as Hospital_Ward_Name,ci.Check_In_Date_And_Time as dis_check_time,emp.Employee_Name as dischareged_chckin_by FROM tbl_check_in ci INNER JOIN tbl_patient_registration pr ON ci.Registration_ID=pr.Registration_ID INNER JOIN tbl_employee emp ON ci.Employee_ID=emp.Employee_ID WHERE  ci.Registration_ID NOT IN (SELECT Corpse_ID as Registration_ID FROM tbl_mortuary_admission)$filter $filter4 AND Diseased='yes' AND pr.Registration_ID IN (SELECT Patient_ID FROM tbl_diceased_patients WHERE send_notsend_to_morgue='yes')  UNION SELECT pr.Registration_ID,Patient_Name,hw.Hospital_Ward_Name,ad.pending_set_time as dis_check_time,emp.Employee_Name as dischareged_chckin_by FROM tbl_patient_registration pr INNER JOIN tbl_admission ad ON pr.Registration_ID=ad.Registration_ID INNER JOIN tbl_discharge_reason dr ON ad.Discharge_Reason_ID=dr.Discharge_Reason_ID INNER JOIN tbl_hospital_ward hw ON ad.Hospital_Ward_ID=hw.Hospital_Ward_ID INNER JOIN tbl_employee emp ON ad.pending_setter=emp.Employee_ID WHERE (ad.Admission_Status='Discharged' OR ad.Admission_Status='pending') AND ward_type<>'mortuary_ward'  AND ad.Registration_ID NOT IN (SELECT Corpse_ID as Registration_ID FROM tbl_mortuary_admission) $filter2 AND discharge_condition='dead' $filter3 ";
                while($row = mysqli_fetch_array($Today_Date)){
                    $original_Date = $row['today'];
                    $new_Date = date("Y-m-d", strtotime($original_Date));
                    $Today = $new_Date;}
                                     
                $sql_select_patient_result=mysqli_query($conn,$sql_select_patient) or die(mysqli_error($conn));
                            if(mysqli_num_rows($sql_select_patient_result)>0){
                                while($patient_rows=mysqli_fetch_assoc($sql_select_patient_result)){
                                    $Patient_Name=$patient_rows['Patient_Name'];
                                    $Registration_ID=$patient_rows['Registration_ID'];
                                    $Hospital_Ward_Name=$patient_rows['Hospital_Ward_Name'];
                                    $dis_check_time=$patient_rows['dis_check_time'];
                                    $dischareged_chckin_by=$patient_rows['dischareged_chckin_by'];
                                    echo "<tr>
                                            <td><a href='motuary_admission.php?Registration_ID=$Registration_ID' style='text-decoration:none'>$count</a></td>
                                            <td><a href='motuary_admission.php?Registration_ID=$Registration_ID' style='text-decoration:none'>$Patient_Name</a></td>
                                            <td><a href='motuary_admission.php?Registration_ID=$Registration_ID' style='text-decoration:none'>$Registration_ID</a></td>
                                            <td>$Hospital_Ward_Name</td>
                                            <td>$dis_check_time</td>
                                            <td>$dischareged_chckin_by</td>
                                          </tr>";
                                    $count++;
                                }
                            }
            ?>
    </tbody>
</table>