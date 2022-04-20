<?php
include("./includes/connection.php");
$filter="";
$filter2="";

if(isset($_GET['patient_name'])){
    $patient_name=$_GET['patient_name'];
    if(!empty($patient_name)){
       $filter.="AND patient_name LIKE '%$patient_name%'"; 
       $filter2.="AND patient_name LIKE '%$patient_name%'"; 
    }
}
if(isset($_GET['patient_number'])){
    $patient_number=$_GET['patient_number'];
    if(!empty($patient_number)){
       $filter.="AND pr.Registration_ID LIKE '%$patient_number%'"; 
       $filter2.="AND ad.Registration_ID LIKE '%$patient_number%'"; 
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
               // $sql_select_patient="SELECT pr.Registration_ID,Patient_Name FROM tbl_check_in ci INNER JOIN tbl_patient_registration pr ON ci.Registration_ID=pr.Registration_ID WHERE $filter AND Diceased='yes' UNION SELECT pr.Registration_ID,Patient_Name FROM tbl_patient_registration pr INNER JOIN tbl_admission ad ON pr.Registration_ID=ad.Registration_ID INNER JOIN tbl_discharge_reason dr ON ad.Discharge_Reason_ID=dr.Discharge_Reason_ID WHERE ad.Admission_Status='Discharged' $filter2 AND discharge_condition='dead'";
              //$sql_select_patient="SELECT pr.Registration_ID,Patient_Name,pr.District as Hospital_Ward_Name,ci.Check_In_Date_And_Time as dis_check_time,emp.Employee_Name as dischareged_chckin_by FROM tbl_check_in ci INNER JOIN tbl_patient_registration pr ON ci.Registration_ID=pr.Registration_ID INNER JOIN tbl_employee emp ON ci.Employee_ID=emp.Employee_ID WHERE ci.Registration_ID NOT IN (SELECT Corpse_ID as Registration_ID FROM tbl_mortuary_admission) $filter AND Diceased='yes' UNION SELECT pr.Registration_ID,Patient_Name,hw.Hospital_Ward_Name,ad.Discharge_Date_Time as dis_check_time,emp.Employee_Name as dischareged_chckin_by FROM tbl_patient_registration pr INNER JOIN tbl_admission ad ON pr.Registration_ID=ad.Registration_ID INNER JOIN tbl_discharge_reason dr ON ad.Discharge_Reason_ID=dr.Discharge_Reason_ID INNER JOIN tbl_hospital_ward hw ON ad.Hospital_Ward_ID=hw.Hospital_Ward_ID INNER JOIN tbl_employee emp ON ad.Discharge_Employee_ID=emp.Employee_ID WHERE (ad.Admission_Status='Discharged' OR ad.Admission_Status='pending')AND ward_type<>'mortuary_ward'  AND ad.Registration_ID NOT IN (SELECT Corpse_ID as Registration_ID FROM tbl_mortuary_admission) $filter2 AND discharge_condition='dead'";
                $sql_select_patient="SELECT pr.Registration_ID,Patient_Name,pr.District as Hospital_Ward_Name,ci.Check_In_Date_And_Time as dis_check_time,emp.Employee_Name as dischareged_chckin_by FROM tbl_check_in ci INNER JOIN tbl_patient_registration pr ON ci.Registration_ID=pr.Registration_ID INNER JOIN tbl_employee emp ON ci.Employee_ID=emp.Employee_ID WHERE  ci.Registration_ID NOT IN (SELECT Corpse_ID as Registration_ID FROM tbl_mortuary_admission)$filter AND Diseased='yes' AND pr.Registration_ID IN (SELECT Patient_ID FROM tbl_diceased_patients WHERE send_notsend_to_morgue='no')  UNION SELECT pr.Registration_ID,Patient_Name,hw.Hospital_Ward_Name,ad.pending_set_time as dis_check_time,emp.Employee_Name as dischareged_chckin_by FROM tbl_patient_registration pr INNER JOIN tbl_admission ad ON pr.Registration_ID=ad.Registration_ID INNER JOIN tbl_discharge_reason dr ON ad.Discharge_Reason_ID=dr.Discharge_Reason_ID INNER JOIN tbl_hospital_ward hw ON ad.Hospital_Ward_ID=hw.Hospital_Ward_ID INNER JOIN tbl_employee emp ON ad.pending_setter=emp.Employee_ID WHERE (ad.Admission_Status='Discharged' OR ad.Admission_Status='pending')AND ward_type<>'mortuary_ward'  AND ad.Registration_ID NOT IN (SELECT Corpse_ID as Registration_ID FROM tbl_mortuary_admission) $filter2 AND discharge_condition='dead'";
                                     
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
