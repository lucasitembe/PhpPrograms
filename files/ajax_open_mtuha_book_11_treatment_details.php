<?php 
include("./includes/connection.php");
//$filter_matibabu .=" AND ilc.Transaction_Date_And_Time BETWEEN '$start_date' AND '$end_date'";



    
     

//$filter_matibabu .=" AND ilc.Transaction_Date_And_Time BETWEEN '$start_date' AND '$end_date'";
    if(isset($_POST['Treatment_ID'])){
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];
        $Treatment_ID = $_POST['Treatment_ID'];
        $name_of_treatment = $_POST['name_of_treatment'];
        $process_status=$_POST['process_status'];
        $filter_matibabu="";
            if($process_status!="All"){
            if($process_status=="done_procedure"){
                $filter_matibabu .=" AND (ilc.Status='served')";
            }
            }
        //  echo "<table class='table table-condensed' style='background:#FFFFFF'> <thead>";
        //       echo "<tr>
        //       <td style='width:5%'><b>#</b></td>
        //       <td style='width:50%'><b>PATIENT NAME</b></td>
        //       <td style='width:20%;text-align:right'><b>REGISTRATION ID</b></td>
        //       <td style='width:10%'><b>GENDER</b></td>
        //       <td style='width:10%'><b>AGE</b></td>
        //   </tr>
        //   </thead><tbody>";
        //       //  die("SELECT mtc.Item_ID,pp.Registration_ID,Gender,TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,CURDATE()) AS patient_age,Patient_Name FROM tbl_payment_cache pp,tbl_mtuha_treatment_category mtc,  tbl_item_list_cache ilc, tbl_mtuha_book_11_report_employee mbrp,tbl_patient_registration pr WHERE ilc.Item_ID=mtc.Item_ID AND mbrp.Employee_ID=ilc.Consultant_ID  AND pp.Registration_ID=pr.Registration_ID AND ilc.Item_ID  IN(SELECT Item_ID FROM tbl_mtuha_treatment_category WHERE Treatment_ID='$Treatment_ID' ) AND pp.Payment_Cache_ID =ilc.Payment_Cache_ID   AND ilc.Transaction_Date_And_Time BETWEEN '$start_date' AND '$end_date' $filter_matibabu GROUP BY pp.Registration_ID ");
        //       $get_Treatment_statistics_per_item_result=mysqli_query($conn,"SELECT ilc.Item_ID,pp.Registration_ID,Gender,TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,CURDATE()) AS patient_age,Patient_Name FROM tbl_payment_cache pp,tbl_mtuha_treatment_category mtc,  tbl_item_list_cache ilc, tbl_mtuha_book_11_report_employee mbrp,tbl_patient_registration pr              
        //        WHERE ilc.Item_ID=mtc.Item_ID AND mbrp.Employee_ID=ilc.Consultant_ID  AND pp.Registration_ID=pr.Registration_ID AND Treatment_ID='$Treatment_ID' AND pp.Payment_Cache_ID =ilc.Payment_Cache_ID   AND ilc.Transaction_Date_And_Time BETWEEN '$start_date' AND '$end_date' $filter_matibabu GROUP BY pp.Registration_ID  ") or die(mysqli_error($conn));
        //         $count = 1;
        //           while($rows_count= mysqli_fetch_assoc($get_Treatment_statistics_per_item_result)){
        //               $Type_of_patient_case=$rows_count['Type_of_patient_case'];
        //               $Gender=$rows_count['Gender'];
        //               $patient_age=$rows_count['patient_age'];
        //               $Registration_ID=$rows_count['Registration_ID'];
        //               $patient_name = $rows_count['Patient_Name'];

        //                 echo "<tr>
        //                     <td style='width:5%'><b>$count</b></td>
        //                     <td style='width:50%'><b>$patient_name</b></td>
        //                     <td style='width:20%;text-align:right'><b>$Registration_ID</b></td>
        //                     <td style='width:10%'><b>$Gender</b></td>
        //                     <td style='width:10%'><b>$patient_age</b></td>
        //                 </tr>
        //                 ";
        //                 $count++;
        //             } 

        //         echo "</tbody></table>";

                echo "<table class='table table-condensed' style='background:#FFFFFF'> <thead>";
                echo "<tr>
                <td style='width:5%'><b>#</b></td>
                <td style='width:50%'><b>PATIENT NAME</b></td>
                <td style='width:20%;text-align:right'><b>REGISTRATION ID</b></td>
                <td style='width:10%'><b>GENDER</b></td>
                <td style='width:10%'><b>AGE</b></td>
            </tr>
            </thead><tbody>";


                $sql_count_procedure_result= mysqli_query($conn, "SELECT pr.Registration_ID,Patient_Name,Gender,TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,CURDATE()) AS patient_age FROM tbl_item_list_cache ilc  INNER JOIN tbl_payment_cache pp ON pp.Payment_Cache_ID =ilc.Payment_Cache_ID JOIN tbl_patient_registration pr ON pr.Registration_ID=pp.Registration_ID JOIN tbl_mtuha_book_11_report_employee mbrp ON mbrp.Employee_ID=ilc.Consultant_ID
                JOIN tbl_mtuha_treatment_category mtc ON ilc.Item_ID=mtc.Item_ID
                 WHERE  Check_In_Type='Procedure' AND Treatment_ID='$Treatment_ID' AND ilc.Transaction_Date_And_Time BETWEEN '$start_date' AND '$end_date' $filter_matibabu GROUP BY pr.Registration_ID,Gender") or die(mysqli_error($conn));
                if(mysqli_num_rows($sql_count_procedure_result)>0){
                    $count=1;
                    while($count_rows=mysqli_fetch_assoc($sql_count_procedure_result)){
                            $Gender=$count_rows['Gender'];
                            $patient_age=$count_rows['patient_age'];
                            $Type_of_patient_case=$count_rows['Type_of_patient_case'];
                            $Registration_ID=$count_rows['Registration_ID'];
                            $patient_name = $count_rows['Patient_Name'];
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
                }
                echo "</tbody></table>";
                 

    }

?>