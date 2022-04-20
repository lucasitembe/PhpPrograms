<?php

   session_start();
    include("./includes/connection.php");
    
    if(isset($_GET['Pre_Operative_ID'])){
        $Pre_Operative_ID = $_GET['Pre_Operative_ID'];
    }
    if(isset($_GET['Registration_ID'])){
        $Registration_ID = $_GET['Registration_ID'];
    }
    if(isset($_GET['consultation_ID'])){
        $consultation_ID = $_GET['consultation_ID'];
    }
    if(isset($_GET['Admision_ID'])){
        $Admision_ID = $_GET['Admision_ID'];
    }else{
        $Admision_ID = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Admision_ID FROM tbl_admission WHERE Registration_ID = '$Registration_ID' ORDER BY Admision_ID DESC LIMIT 1"))['Admision_ID'];
    }

    //    select patient information to perform check in process
    if(!empty($Registration_ID)){
                $select = mysqli_query($conn,"SELECT pr.Patient_Name, wr.room_name, pr.Registration_ID, pr.Gender, pr.Date_Of_Birth, pr.Member_Number, sp.Guarantor_Name,sp.payment_method,sp.Sponsor_ID,TIMESTAMPDIFF(DAY,Admission_Date_Time,NOW()) AS NoOfDay,
                                        emp.Employee_Name, ad.Admission_Date_Time, hp.Hospital_Ward_Name, ad.Bed_Name, ad.Cash_Bill_Status, ad.Credit_Bill_Status, ad.Admision_ID
                                        from tbl_patient_registration pr ,tbl_sponsor sp, tbl_admission ad, tbl_employee emp, tbl_hospital_ward hp, tbl_ward_rooms wr WHERE						
                                        ad.Hospital_Ward_ID = hp.Hospital_Ward_ID and
                                        pr.Registration_ID = ad.Registration_ID and 
                                        pr.Sponsor_ID = sp.Sponsor_ID and
                                        emp.Employee_ID= ad.Admission_Employee_ID and pr.Registration_ID = '$Registration_ID' AND wr.ward_room_id = ad.ward_room_id
                                        ") or die(mysqli_error($conn));
            }
            // $num = mysqli_num_rows($select);
            $htm="";
            // $num = mysqli_num_rows($select);
            
            $nums = 1;
            if (mysqli_num_rows($select) > 0) {
                while ($data = mysqli_fetch_assoc($select)) {
                    $Patient_Name = $data['Patient_Name'];
                    $Gender = $data['Gender'];
                    $Date_Of_Birth = $data['Date_Of_Birth'];
                    $Member_Number = $data['Member_Number'];
                    $Payment_Method = $data['payment_method'];
                    $Guarantor_Name = $data['Guarantor_Name'];
                    $Employee_Name = $data['Employee_Name'];
                    $Admission_Date_Time = $data['Admission_Date_Time'];
                    $Bed_Name = $data['Bed_Name'];
                    $Hospital_Ward_Name = $data['Hospital_Ward_Name'];
                    $Sponsor_ID = $data['Sponsor_ID'];
                    $Cash_Bill_Status = $data['Cash_Bill_Status'];
                    $Credit_Bill_Status = $data['Credit_Bill_Status'];
                    $Admision_ID = $data['Admision_ID'];
                    $NoOfDay = $data['NoOfDay'];
                    $room_name = $data['room_name'];
                    
            
                    //calculate age
                    $date1 = new DateTime($Today);
                    $date2 = new DateTime($Date_Of_Birth);
                    $diff = $date1->diff($date2);
                    $age = $diff->y . " Years, ";
                    $age .= $diff->m . " Months, ";
                    $age .= $diff->d . " Days";
                }
            }
                            
            
            
            $operative_details = mysqli_query($conn, "SELECT * FROM tbl_pre_operative_checklist  WHERE Pre_Operative_ID = '$Pre_Operative_ID'");
            while($surgical = mysqli_fetch_assoc($operative_details)){
                $doctor_list = $surgical['doctor_list'];
                $Theatre_Time = $surgical['Theatre_Time'];
                $Ward_Signature = $surgical['Ward_Signature'];
                $surgeon = $surgical['surgeon'];
                $blood_available = $surgical['blood_available'];
                $blood = $surgical['blood'];
                $Special_Information = $surgical['Special_Information'];
                $Handling_nurse = $surgical['Handling_nurse'];
                $surgery = $surgical['surgery'];
                $Operative_Date_Time = substr($surgical['Operative_Date_Time'], 0, -9);
                $surgeon = $surgical['surgeon'];
                $acceptance = $surgical['acceptance'];
                $instruction = $surgical['instruction'];
                $Theatre_Signature = $surgical['Theatre_Signature'];
                $recovery_nurse = $surgical['recovery_nurse'];
            }

               $htm .= "<table width ='100%' height = '30px'>
               <tr><td><img src='./branchBanner/branchBanner.png' width=100%></td></tr>
               </table>";
           $htm .= "<table width ='100%' height = '30px'>
                        <tr><td style='text-align: center; font-weight: bold;' colspan='6'>PATIENT THEATRE CHECKLIST</td></tr>
                            <tr>
                            <td colspan='7'><hr></td>
                        </tr>
                        <tr>
                            <td><span style='text-align: right;'><b>Patient Name: </td>
                            <td colspan='2'><span style='text-align: right;'></b>" . ucwords(strtolower($Patient_Name)) . "</span></td>
                            <td><span style='text-align: right;'><b>Registration Number: </td>
                            <td></b>" . $Registration_ID . "</span></td>
                            <td><span style='text-align: right;'><b>Gender:</span></td>
                            <td></b>" . $Gender . "</span></td>
                        </tr>
                        <tr>
                            <td><span style='text-align: right;'><b>Sponsor Name: </td>
                            <td colspan='2'></b>" . strtoupper($Guarantor_Name) . "</span></td>
                            <td><span style='text-align: right;'><b>Age: </td>
                            <td></b>" . $age . "</span></td>
                            <td><span style='text-align: right;'><b>Admitted By: </td>
                            <td></b>" . $Employee_Name . "</span></td>
                        </tr>
                        <tr>
                            <td><span style='text-align: right;'><b>Ward: </td>
                            <td colspan='2'></b>" . $Hospital_Ward_Name . "</span></td>
                            <td><span style='text-align: right;'><b>Room/Bed Number: </td>
                            <td></b>".$room_name."/" . $Bed_Name . "</span></td>

                        </tr>
                        <tr>
                            <td><span style='text-align: right;'><b>Operation: </td>
                            <td colspan='2'></b>" . $surgery. "</span></td>
                            <td><span style='text-align: right;'><b>Surgery Date: </td>
                            <td></b>" . $Operative_Date_Time . "</span></td>
                            <td><span style='text-align: right;'><b>Surgeon: </td>
                            <td></b>" . $surgeon . "</span></td>
                        </tr>
                        <tr>
                            <td colspan='7'><hr></td>
                        </tr>
                        <tr>
                            <td style='text-align: center;'><b>SN</b><td>
                            <td  style='text-align: center;' colspan='2'><b>ACTIVITIES DONE</b></td>
                            <td  style='text-align: center;'><b>YES/NO</b><td>
                            <td style='text-align: center;'><b>REMARKS</b></td>
                        </tr>";
// die("SELECT Task_Name, Task_Value, Remark FROM tbl_pre_operative_checklist_items WHERE Pre_Operative_ID = '$Pre_Operative_ID' AND Task_Name <> ''");
    $preoperative_item_list = mysqli_query($conn, "SELECT Task_Name, Task_Value, Remark FROM tbl_pre_operative_checklist_items WHERE Pre_Operative_ID = '$Pre_Operative_ID' AND Task_Name <> ''");
            while($details = mysqli_fetch_array($preoperative_item_list)){
                $task_Name = $details['Task_Name'];
                $task_value = $details['Task_Value'];
                $Remarkss = $details['Remark'];
        
                $htm.="<tr>
                            <td style='text-align: center; font-weight: bold;'>$nums</td>
                            <td colspan='3'>$task_Name</td>
                            <td style='text-align: center; font-weight: bold;'>$task_value</td>
                            <td>$Remarkss</td>
                        </tr>";
                $nums++;
            }
            $htm .="<tr>
                        <td colspan='7'><hr></td>
                    </tr>
                    <tr>
                        <td colspan='2' style='text-align: center;'>List Prepared By:</td>
                        <td colspan='2'><b>$doctor_list</b></td>";
            $htm .="<tr>
                        <td rowspan='2'><b>Blood:</b></td>
                        <td colspan='3'>(a) How many units required:</td>
                        <td><b>$blood</b></td>
                    </tr>
                    <tr>
                    <td colspan='3'>(b) How many units required:</td>
                    <td><b>$blood_available</b></td>";
            if(!empty($Special_Information)){
                $htm .="<tr>
                            <td><b>Others:</b></td>
                            <td colspan='5'>$Special_Information<td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                        </tr>";
            }

            $htm .="<tr>
                        <td colspan='6'><b>HANDLING OVER</b></td>
                    </tr>";
                    $htm .="<tr>
                    <td>Ward Nurse:</td>
                    <td colspan='2'><b>$Ward_Signature</b></td>
                    <td><b>Signature:</b></td>";
            $signature_ward = mysqli_fetch_assoc(mysqli_query($conn, "SELECT employee_signature FROM tbl_employee WHERE   Employee_Name = '$Ward_Signature'"))['employee_signature'];
                if(!empty($signature_ward)){
                    $ward_nurse = "<img src='../esign/employee_signatures/$employee_signature' style='height:25px'>";
                }else{
                    $ward_nurse = "________________";
                }
                $htm.="
                    <td colspan='2'><b>$ward_nurse</b></td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                </tr>";
                

                $htm .="<tr>
                        <td colspan='6'><b>TAKING OVER IN OUT</b></td>
                    </tr>";
                    $htm .="<tr>
                    <td>Handling Nurse/Porter:</td>
                    <td colspan='2'><b>$Handling_nurse</b></td>
                    <td><b>Signature:</b></td>";
            $signature_handling = mysqli_fetch_assoc(mysqli_query($conn, "SELECT employee_signature FROM tbl_employee WHERE   Employee_Name = '$Handling_nurse'"))['employee_signature'];
                if(!empty($signature_handling)){
                    $ward_handling = "<img src='../esign/employee_signatures/$signature_handling' style='height:25px'>";
                }else{
                    $ward_handling = "________________";
                }
                $htm.="
                    <td colspan='2'><b>$ward_handling</b></td>
                </tr>
                
                <tr>
                    <td>&nbsp;</td>
                </tr>";

                $htm .="<tr>
                            <td colspan='2'>Is the Patient accepted in Theatre?</td>
                            <td><b>$acceptance</b></td>
                        </tr>
                        <tr>
                            <td colspan='2'><b>If Not:</b> What are the instruction? </td>
                            <td colspan='4'><b>$instruction</b></td>
                        </tr>";


                        $htm .="<tr>
                    <td>Theatre Nurse:</td>
                    <td colspan='2'><b>$Theatre_Signature</b></td>
                    <td><b>Signature:</b></td>";
            $signature_theather = mysqli_fetch_assoc(mysqli_query($conn, "SELECT employee_signature FROM tbl_employee WHERE   Employee_Name = '$Theatre_Signature'"))['employee_signature'];
                if(!empty($signature_theather)){
                    $ward_theater = "<img src='../esign/employee_signatures/$signature_theather' style='height:25px'>";
                }else{
                    $ward_theater = "________________";
                }
                $htm.="
                    <td colspan='2'><b>$ward_theater</b></td>
                </tr>
                <tr>
                            <td colspan='2'>Theatre Date Time</td>
                            <td colspan='2'>$Theatre_Time</td>
                </tr>
                <tr>
                        <td>Receiving Nurse/Recovery Nurse:</td>
                        <td colspan='2'><b>$recovery_nurse</b></td>
                        <td><b>Signature:</b></td>";
                $signature_recovery = mysqli_fetch_assoc(mysqli_query($conn, "SELECT employee_signature FROM tbl_employee WHERE   Employee_Name = '$recovery_nurse'"))['employee_signature'];
                    if(!empty($signature_recovery)){
                        $ward_recovery = "<img src='../esign/employee_signatures/$signature_recovery' style='height:25px'>";
                    }else{
                        $ward_recovery = "________________";
                    }
                    $htm.="
                        <td colspan='2'><b>$ward_recovery</b></td>
                    </tr>";


                    $htm .="</table>
                    <br/>";

                // echo $htm;

            

include("MPDF/mpdf.php");
$htm = utf8_encode($htm);
$mpdf=new mPDF(); 
$mpdf->WriteHTML($htm);
$mpdf->Output();
?>