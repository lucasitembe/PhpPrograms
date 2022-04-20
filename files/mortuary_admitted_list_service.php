<?php
include("./includes/connection.php");
$filter="";
$filter2="";

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
       $filter ="AND pr.Registration_ID LIKE '%$patient_number%'"; 
       $filter .="AND ad.Registration_ID LIKE '%$patient_number%'"; 
    }
}

?>
<table id="list_of_checked_in_n_discharged_tbl" class="table table-bordered">
    <thead>
        <tr>
            <th style="width:50px">S/No.</th>
            <th>PATIENT NAME</th>
            <th>PATIENT NUMBER</th>
            <th>WARD ADMITTED</th>
            <th>CABINET</th>
            <th>ADMITTED DATE</th>
            <th>ADMITTED BY</th>
            <th>RELATIVE NAME</th>
        </tr>
    </thead>
<tbody>
                       <?php 
                            $count=1;
                            $sql_select_patient="SELECT hw.Hospital_Ward_Name, ad.Admission_Date_Time, ad.Bed_Name, pr.Patient_Name, pr.Registration_ID, em.Employee_Name, ma.Corpse_Brought_By FROM tbl_mortuary_admission ma, tbl_hospital_ward hw, tbl_patient_registration pr, tbl_employee em, tbl_admission ad WHERE pr.Registration_ID = ad.Registration_ID AND em.Employee_ID = ad.Admission_Employee_ID AND ma.Admision_ID = ad.Admision_ID AND hw.ward_type = 'mortuary_ward' $filter GROUP BY ad.Admision_ID ORDER BY ad.Admision_ID ASC LIMIT 20";
                            $sql_select_patient_result=mysqli_query($conn,$sql_select_patient) or die(mysqli_error($conn));
                            if(mysqli_num_rows($sql_select_patient_result)>0){
                                while($patient_rows=mysqli_fetch_assoc($sql_select_patient_result)){
                                    $Patient_Name=$patient_rows['Patient_Name'];
                                    $Registration_ID=$patient_rows['Registration_ID'];
                                    $Hospital_Ward_Name=$patient_rows['Hospital_Ward_Name'];
                                    $Admission_Date_Time=$patient_rows['Admission_Date_Time'];
                                    $Bed_Name=$patient_rows['Bed_Name'];
                                    $Employee_Name=$patient_rows['Employee_Name'];
                                    $Bed_Name=$patient_rows['Bed_Name'];
                                    $Corpse_Brought_By=$patient_rows['Corpse_Brought_By'];
                                    echo "<tr>
                                            <td><a href='add_mortuary_services.php?Registration_ID=$Registration_ID' style='text-decoration:none'>$count</a></td>
                                            <td><a href='add_mortuary_services.php?Registration_ID=$Registration_ID' style='text-decoration:none'>$Patient_Name</a></td>
                                            <td><a href='add_mortuary_services.php?Registration_ID=$Registration_ID' style='text-decoration:none'>$Registration_ID</a></td>
                                            <td><a href='add_mortuary_services.php?Registration_ID=$Registration_ID' style='text-decoration:none'>$Hospital_Ward_Name</a></td>
                                            <td><a href='add_mortuary_services.php?Registration_ID=$Registration_ID' style='text-decoration:none'>$Bed_Name</a></td>
                                            <td><a href='add_mortuary_services.php?Registration_ID=$Registration_ID' style='text-decoration:none'>$Admission_Date_Time</a></td>
                                            <td><a href='add_mortuary_services.php?Registration_ID=$Registration_ID' style='text-decoration:none'>$Employee_Name</a></td>
                                            <td><a href='add_mortuary_services.php?Registration_ID=$Registration_ID' style='text-decoration:none'>$Corpse_Brought_By</a></td>
                                          </tr>";
                                    $count++;
                                }
                            }
            ?>
    </tbody>
</table>