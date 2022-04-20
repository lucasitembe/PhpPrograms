<?php
//@gkcchief
include("./includes/connection.php");
    $start_date=$_POST['start_date'];
    $end_date=$_POST['end_date'];
    $subcategory_ID=$_POST['subcategory_ID'];
    $diagnosis_type=$_POST['diagnosis_type'];
    $filter="";
    if($diagnosis_type!="All"){
       $filter=" AND diagnosis_type='$diagnosis_type'"; 
    }
    echo "<table class='table table-condensed' style='background:#FFFFFF'>
    <tr>
        <td style='width:5%'><b>S/No.</b></td>
        <td style='width:50%'><b>PATIENT NAME</b></td>
        <td style='width:20%;text-align:right'><b>REGISTRATION ID</b></td>
        <td style='width:10%'><b>GENDER</b></td>
        <td style='width:10%'><b>AGE</b></td>
    </tr>
    ";
    $count_sn=1;
    $get_disease_statistics_per_sub_category_result=mysqli_query($conn,"SELECT COUNT(c.Registration_ID) as patient_count,Type_of_patient_case,c.Registration_ID,Gender,TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,CURDATE()) AS patient_age,Patient_Name FROM  tbl_disease_consultation dc,tbl_consultation c,tbl_mtuha_book_11_report_employee mbrp,tbl_patient_registration pr,tbl_disease ds WHERE dc.consultation_ID=c.consultation_ID AND dc.employee_ID=mbrp.Employee_ID AND c.Registration_ID=pr.Registration_ID AND ds.disease_ID=dc.disease_ID AND subcategory_ID='$subcategory_ID' AND Disease_Consultation_Date_And_Time BETWEEN '$start_date' AND '$end_date' $filter GROUP BY c.Registration_ID") or die(mysqli_error($conn));
        if(mysqli_num_rows($get_disease_statistics_per_sub_category_result)>0){
            while($rows_count= mysqli_fetch_assoc($get_disease_statistics_per_sub_category_result)){
                $Type_of_patient_case=$rows_count['Type_of_patient_case'];
                $Gender=$rows_count['Gender'];
                $patient_age=$rows_count['patient_age'];
                $Registration_ID=$rows_count['Registration_ID'];
                $Patient_Name=$rows_count['Patient_Name'];
                echo "<tr>
                        <td>$count_sn.</td>
                        <td>$Patient_Name</td>
                        <td style='text-align:right'>". number_format($Registration_ID)."</td>
                        <td>$Gender</td>
                        <td>$patient_age Years</td>
                      </tr>
                    ";
                $count_sn++;
        }
    }
    echo "</table>";