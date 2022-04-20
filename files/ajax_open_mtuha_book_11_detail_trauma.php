<?php 
include("./includes/connection.php");
    if(isset($_POST['hosp_course_injury_ID'])){
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];
        $hosp_course_injury_ID = $_POST['hosp_course_injury_ID'];

              echo "<table class='table table-condensed' style='background:#FFFFFF'> <thead>";
              echo "<tr>
              <td style='width:5%'><b>S/N.</b></td>
              <td style='width:50%'><b>PATIENT NAME</b></td>
              <td style='width:20%;text-align:right'><b>REGISTRATION ID</b></td>
              <td style='width:10%'><b>GENDER</b></td>
              <td style='width:10%'><b>AGE</b></td>
          </tr>
          </thead><tbody>";

              $get_disease_statistics_per_sub_category_result=mysqli_query($conn,"SELECT Type_of_patient_case,c.Registration_ID,Gender,TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,CURDATE()) AS patient_age,Patient_Name FROM  tbl_consultation c,tbl_mtuha_book_11_report_employee mbrp,tbl_patient_registration pr WHERE  c.employee_ID=mbrp.Employee_ID AND c.Registration_ID=pr.Registration_ID AND course_of_injuries=$hosp_course_injury_ID AND Consultation_Date_And_Time BETWEEN '$start_date' AND '$end_date'") or die(mysqli_error($conn));
                $count = 1;
                  while($rows_count= mysqli_fetch_assoc($get_disease_statistics_per_sub_category_result)){
                      $Type_of_patient_case=$rows_count['Type_of_patient_case'];
                      $Gender=$rows_count['Gender'];
                      $patient_age=$rows_count['patient_age'];
                      $Registration_ID=$rows_count['Registration_ID'];
                      $patient_name = $rows_count['Patient_Name'];

                        echo "<tr>
                            <td style='width:5%'><b>$count</b></td>
                            <td style='width:50%'><b>$patient_name</b></td>
                            <td style='width:20%;text-align:right'><b>$Registration_ID</b></td>
                            <td style='width:10%'><b>$Gender</b></td>
                            <td style='width:10%'><b>$patient_age</b></td>
                        </tr>
                        ";
                        $count++;
                    } 

                echo "</tbody></table>";
                 

    }

    if(isset($_POST['to_come_again_id'])){
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];
        $to_come_again_id = $_POST['to_come_again_id'];
        $to_come_again_reason = $_POST['to_come_again_reason'];

              echo "<table class='table table-condensed' style='background:#FFFFFF'> <thead>";
              echo "<tr>
              <td style='width:5%'><b>$to_come_again_id</b></td>
              <td style='width:50%'><b>PATIENT NAME</b></td>
              <td style='width:20%;text-align:right'><b>REGISTRATION ID</b></td>
              <td style='width:10%'><b>GENDER</b></td>
              <td style='width:10%'><b>AGE</b></td>
          </tr>
          </thead><tbody>";

              $get_disease_statistics_per_sub_category_result=mysqli_query($conn,"SELECT Type_of_patient_case,c.Registration_ID,Gender,TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,CURDATE()) AS patient_age,Patient_Name FROM  tbl_consultation c,tbl_mtuha_book_11_report_employee mbrp,tbl_patient_registration pr WHERE  c.employee_ID=mbrp.Employee_ID AND c.Registration_ID=pr.Registration_ID AND to_come_again_reason='$to_come_again_reason' AND Consultation_Date_And_Time BETWEEN '$start_date' AND '$end_date'") or die(mysqli_error($conn));
                $count = 1;
                  while($rows_count= mysqli_fetch_assoc($get_disease_statistics_per_sub_category_result)){
                      $Type_of_patient_case=$rows_count['Type_of_patient_case'];
                      $Gender=$rows_count['Gender'];
                      $patient_age=$rows_count['patient_age'];
                      $Registration_ID=$rows_count['Registration_ID'];
                      $patient_name = $rows_count['Patient_Name'];

                        echo "<tr>
                            <td style='width:5%'><b>$count</b></td>
                            <td style='width:50%'><b>$patient_name</b></td>
                            <td style='width:20%;text-align:right'><b>$Registration_ID</b></td>
                            <td style='width:10%'><b>$Gender</b></td>
                            <td style='width:10%'><b>$patient_age</b></td>
                        </tr>
                        ";
                        $count++;
                    } 

                echo "</tbody></table>";
                 

    }

?>