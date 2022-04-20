<?php
    session_start();
    include("./includes/connection.php");

    $Brand_Id = $_SESSION['userinfo']['Branch_ID'];


    // Get inpatient patients
    if($_GET['load_all'] == "load_all"){
        $get_current_inpatient_patient = mysqli_query($conn, "SELECT ta.Registration_ID,pr.Patient_Name,pr.Sponsor_ID,pr.Gender,pr.Phone_Number FROM tbl_admission as ta,tbl_patient_registration as pr WHERE ta.Admission_Status = 'Admitted' AND ta.Bed_Name <> '' AND ta.Registration_ID = pr.Registration_ID ORDER BY Admision_ID DESC LIMIT 10") or die(mysqli_error($conn));
    }else if($_GET['number_search'] == "number_search"){
        $patient_number = (!empty(isset($_GET['patient_number']))) ?  $_GET['patient_number'] : '' ;
        $get_current_inpatient_patient = mysqli_query($conn, "SELECT ta.Registration_ID,pr.Patient_Name,pr.Sponsor_ID,pr.Gender,pr.Phone_Number FROM tbl_admission as ta,tbl_patient_registration as pr WHERE ta.Admission_Status = 'Admitted' AND ta.Bed_Name <> '' AND ta.Registration_ID = pr.Registration_ID AND ta.Registration_ID = '$patient_number' ORDER BY Admision_ID DESC LIMIT 10") or die(mysqli_error($conn));
    }else if($_GET['name_search'] == "name_search"){
        $patient_name = (!empty(isset($_GET['patient_name']))) ? $_GET['patient_name'] : '';
        $get_current_inpatient_patient = mysqli_query($conn, "SELECT ta.Registration_ID,pr.Patient_Name,pr.Sponsor_ID,pr.Gender,pr.Phone_Number FROM tbl_admission as ta,tbl_patient_registration as pr WHERE ta.Admission_Status = 'Admitted' AND ta.Bed_Name <> '' AND ta.Registration_ID = pr.Registration_ID AND pr.Patient_Name LIKE '%$patient_name%' ORDER BY Admision_ID DESC LIMIT 10") or die(mysqli_error($conn));
    }else if($_GET['phone_number_search'] == "phone_number_search"){
        $phone_number = (!empty(isset($_GET['phone_number']))) ? $_GET['phone_number'] : '';
        $get_current_inpatient_patient = mysqli_query($conn, "SELECT ta.Registration_ID,pr.Patient_Name,pr.Sponsor_ID,pr.Gender,pr.Phone_Number FROM tbl_admission as ta,tbl_patient_registration as pr WHERE ta.Admission_Status = 'Admitted' AND ta.Bed_Name <> '' AND ta.Registration_ID = pr.Registration_ID AND pr.Phone_Number LIKE '%$phone_number%' ORDER BY Admision_ID DESC LIMIT 10") or die(mysqli_error($conn));
    }

    $counter = 1;

    //get today's date
    $sql_date_time = mysqli_query($conn, "SELECT now() as Date_Time ") or die(mysqli_error($conn));
    while ($date = mysqli_fetch_array($sql_date_time)) {
        $Current_Date_Time = $date['Date_Time'];
    }

    $Filter_Value = substr($Current_Date_Time, 0, 11);
    $Start_Date = $Filter_Value . ' 00:00';
    $End_Date = $Current_Date_Time;


    if (mysqli_num_rows($get_current_inpatient_patient) > 0) {
        while ($data = mysqli_fetch_array($get_current_inpatient_patient)) {
            $Registration_ID = $data['Registration_ID'];
            $Patient_Name = $data['Patient_Name'];
            $Sponsor_ID = $data['Sponsor_ID'];
            $Gender = $data['Gender'];
            $Phone_Number = $data['Phone_Number'];

            $get_sponsor_name = mysqli_query($conn, "SELECT Guarantor_Name FROM tbl_sponsor WHERE Sponsor_ID = '$Sponsor_ID'");
            while ($sponsor_name = mysqli_fetch_assoc($get_sponsor_name)) {


                $payment_method = mysqli_fetch_assoc(mysqli_query($conn,"SELECT payment_method FROM tbl_sponsor WHERE Sponsor_ID = '$Sponsor_ID' LIMIT 1"))['payment_method'];

                $sponsor_names = $sponsor_name['Guarantor_Name'];

                # Check last payment cache id if not available create a new payment cache id
                $last_payment_cache = mysqli_query($conn, "SELECT Payment_Cache_ID,Billing_Type,Transaction_type FROM tbl_payment_cache WHERE Registration_ID = '$Registration_ID' AND Payment_Date_And_Time BETWEEN '$Start_Date' AND '$End_Date' ORDER BY Payment_Cache_ID DESC LIMIT 1");
                if (mysqli_num_rows($last_payment_cache) > 0) {

                    $comment = "Yes";

                    $last_payment_cache = mysqli_query($conn, "SELECT Payment_Cache_ID,Billing_Type,Transaction_type FROM tbl_payment_cache WHERE Registration_ID = '$Registration_ID' ORDER BY Payment_Cache_ID DESC LIMIT 1");
                    while ($data = mysqli_fetch_assoc($last_payment_cache)) {
                        $Payment_Cache_ID = $data['Payment_Cache_ID'];
                        $Billing_Type = $data['Billing_Type'];
                        $Transaction_type = $data['Transaction_type'];
                    }
                } else {

                    $payment_method = mysqli_fetch_assoc(mysqli_query($conn,"SELECT payment_method FROM tbl_sponsor WHERE Sponsor_ID = '$Sponsor_ID' "))['payment_method'];

                    $Billing_Type = "Inpatient " . $payment_method;

                    # Get last consulation id
                    $get_last_consultation_id = mysqli_query($conn, "SELECT consultation_ID FROM tbl_consultation WHERE Registration_ID = '$Registration_ID' ORDER BY consultation_ID DESC ");
                    if ($data = mysqli_fetch_assoc($get_last_consultation_id)) {
                        $consultation_ID = $data['consultation_ID'];
                    }
                    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
                    # Create new payment id
                    $insert_new_payment_cache_id = mysqli_query($conn, "INSERT INTO tbl_payment_cache (Registration_ID,Employee_ID,consultation_id,Payment_Date_And_Time,Sponsor_ID,Billing_Type,Transaction_status,Transaction_type,Order_Type,branch_id)
                                                                            VALUES ('$Registration_ID','$Employee_ID','$consultation_ID',NOW(),'$Sponsor_ID','$Billing_Type','active','indirect cash','normal','$Brand_Id')") or die(mysqli_error($conn));

                    if($insert_new_payment_cache_id){
                        $last_payment_cache = mysqli_query($conn, "SELECT Payment_Cache_ID,Billing_Type,Transaction_type FROM tbl_payment_cache WHERE Registration_ID = '$Registration_ID' ORDER BY Payment_Cache_ID DESC ") or die(mysqli_error($conn));
                        while ($data = mysqli_fetch_assoc($last_payment_cache)) {
                            $Payment_Cache_ID = $data['Payment_Cache_ID'];
                            $Billing_Type = $data['Billing_Type'];
                            $Transaction_type = $data['Transaction_type'];
                        }
                    }
                }

                echo "<tr>
                            <td width='5%'><a href = 'new_pharmacy_work_page.php?Registration_ID=" . $Registration_ID . "'>" . $counter++ . "</a></td>
                            <td width='15%'><a href = 'new_pharmacy_work_page.php?section=Pharmacy&Registration_ID=" . $Registration_ID . "&Payment_Cache_ID=" . $Payment_Cache_ID . "&Transaction_Type=" . ucfirst($payment_method) . "&Check_In_Type=Pharmacy&from=ipatient_list&Start_Date=".$Start_Date."&end_date=".$End_Date."'>" . $Patient_Name . "</a></td>
                            
                            <td width='15%'><a href = 'new_pharmacy_work_page.php?section=Pharmacy&Registration_ID=" . $Registration_ID . "&Payment_Cache_ID=" . $Payment_Cache_ID . "&Transaction_Type=" . ucfirst($payment_method) . "&Check_In_Type=Pharmacy&from=ipatient_list&Start_Date=".$Start_Date."&end_date=".$End_Date."'>" . $Registration_ID . "</a></td>
                            
                            <td width='15%'><a href = 'new_pharmacy_work_page.php?section=Pharmacy&Registration_ID=" . $Registration_ID . "&Payment_Cache_ID=" . $Payment_Cache_ID . "&Transaction_Type=" . ucfirst($payment_method) . "&Check_In_Type=Pharmacy&from=ipatient_list&Start_Date=".$Start_Date."&end_date=".$End_Date."'>" . $Gender . "</a></td>

                            <td width='15%'><a href = 'new_pharmacy_work_page.php?section=Pharmacy&Registration_ID=" . $Registration_ID . "&Payment_Cache_ID=" . $Payment_Cache_ID . "&Transaction_Type=" . ucfirst($payment_method) . "&Check_In_Type=Pharmacy&from=ipatient_list&Start_Date=".$Start_Date."&end_date=".$End_Date."'>" . $Gender . "</a></td>

                            <td width='15%'><a href = 'new_pharmacy_work_page.php?section=Pharmacy&Registration_ID=" . $Registration_ID . "&Payment_Cache_ID=" . $Payment_Cache_ID . "&Transaction_Type=" . ucfirst($payment_method) . "&Check_In_Type=Pharmacy&from=ipatient_list&Start_Date=".$Start_Date."&end_date=".$End_Date."'>" . $sponsor_names . "</a></td>

                            <td width='15%'><a href = 'new_pharmacy_work_page.php?section=Pharmacy&Registration_ID=" . $Registration_ID . "&Payment_Cache_ID=" . $Payment_Cache_ID . "&Transaction_Type=" . ucfirst($payment_method) . "&Check_In_Type=Pharmacy&from=ipatient_list&Start_Date=".$Start_Date."&end_date=".$End_Date."'>" . $Phone_Number . "</a></td>
                        </tr>";
            }
        }
    } else {
        if(!empty($patient_name)){

            $filter = mysqli_query($conn, "SELECT Patient_Name, Registration_ID FROM tbl_patient_registration WHERE Patient_Name LIKE '%$patient_name%'");
            if(mysqli_num_rows($filter) > 0){
                while($data = mysqli_fetch_assoc($filter)){
                    $Patient_Name = $data['Patient_Name'];
                    $Registration_ID = $data['Registration_ID'];

                    echo "<tr><td style='color:red;text-align:center;font-weight:bold; font-size: 19px;' colspan='7'>THE PATIENT NAMED ".$Patient_Name." WITH REGISTRATION NUMBER ".$Registration_ID." IS NOT ADMITTED IN eHMS</td></tr>";
                }
            }else{
                echo "<tr><td style='color:red;text-align:center;font-weight:bold; font-size: 19px;' colspan='7'>THIS PATIENT IS NOT REGISTERED IN eHMS</td></tr>";
            }
        }elseif(!empty($patient_number)){

            $filter = mysqli_query($conn, "SELECT Patient_Name, Registration_ID FROM tbl_patient_registration  WHERE Registration_ID = '$patient_number' LIMIT 1");
            if(mysqli_num_rows($filter) > 0){
                while($data = mysqli_fetch_assoc($filter)){
                    $Patient_Name = $data['Patient_Name'];
                    $Registration_ID = $data['Registration_ID'];
                
                    echo "<tr><td style='color:red;text-align:center;font-weight:bold; font-size: 19px;' colspan='7'>THE PATIENT NAMED ".$Patient_Name." WITH REGISTRATION NUMBER ".$Registration_ID." IS NOT ADMITTED IN eHMS</td></tr>";
                }
            }else{
                echo "<tr><td style='color:red;text-align:center;font-weight:bold; font-size: 19px;' colspan='7'>THIS PATIENT IS NOT REGISTERED IN eHMS</td></tr>";
            }
        }else{
            echo "<tr><td style='color:red;text-align:center;font-weight:bold; font-size: 19px;' colspan='7'>THIS PATIENT IS NOT REGISTERED IN eHMS</td></tr>";

        }
    }

    mysqli_close($conn);
?>