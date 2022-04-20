<style>
    .table {
        border: 1px solid black;
        border-collapse: collapse;
    }

    *{
        font-size: small !important;
    }

</style>
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
    }


    if($Admision_ID =='' || $Admision_ID == 0){
        $Admision_ID = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Admision_ID FROM tbl_who_pre_operative_checklist  WHERE Pre_Operative_ID = '$Pre_Operative_ID'"))['Admision_ID'];
    }else{
        $Admision_ID = $_GET['Admision_ID'];
    }

    $Select_Surgery = mysqli_query($conn,"SELECT em.Employee_Name as surgeon, i.Product_Name FROM tbl_items i, tbl_employee em, tbl_item_list_cache ilc WHERE i.Item_ID = ilc.Item_ID AND em.Employee_ID = ilc.Consultant_ID AND Payment_Item_Cache_List_ID = '$Payment_Item_Cache_List_ID'") or die(mysqli_error($conn));
    while($items = mysqli_fetch_assoc($Select_Surgery));{
        $surgeon_ordered = $items['surgeon'];
        $surgery_ordered = $items['Product_Name'];
    }

    //    select patient information to perform check in process

                $select = mysqli_query($conn,"SELECT pr.Patient_Name, wr.room_name, pr.Registration_ID, pr.Gender, pr.Date_Of_Birth, pr.Member_Number, sp.Guarantor_Name,sp.payment_method,sp.Sponsor_ID,TIMESTAMPDIFF(DAY,Admission_Date_Time,NOW()) AS NoOfDay,
                                        emp.Employee_Name, ad.Admission_Date_Time, hp.Hospital_Ward_Name, ad.Bed_Name, ad.Cash_Bill_Status, ad.Credit_Bill_Status, ad.Admision_ID
                                        from tbl_patient_registration pr ,tbl_sponsor sp, tbl_admission ad, tbl_employee emp, tbl_hospital_ward hp, tbl_ward_rooms wr WHERE						
                                        ad.Hospital_Ward_ID = hp.Hospital_Ward_ID and
                                        pr.Registration_ID = ad.Registration_ID and 
                                        pr.Sponsor_ID = sp.Sponsor_ID and
                                        emp.Employee_ID= ad.Admission_Employee_ID and ad.Admision_ID = '$Admision_ID' and ad.Registration_ID = '$Registration_ID' AND wr.ward_room_id = ad.ward_room_id
                                        ") or die(mysqli_error($conn));
            
            $num = mysqli_num_rows($select);
            $htm="";
            // $num = mysqli_num_rows($select);
            
                while ($data = mysqli_fetch_array($select)) {
                    $Patient_Name = $data['Patient_Name'];
                    $Registration_ID = $data['Registration_ID'];
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
                      
            $operative_details = mysqli_query($conn, "SELECT * FROM tbl_who_pre_operative_checklist  WHERE Pre_Operative_ID = '$Pre_Operative_ID'");
            while($surgical = mysqli_fetch_assoc($operative_details)){
                $doctor_list = $surgical['doctor_list'];
                $Theatre_Time = $surgical['Theatre_Time'];
                $Surgeon_filled = $surgical['Surgeon_filled'];
                $nurse_instruments = $surgical['nurse_instruments'];
                $Anesthetist = $surgical['Anesthetist'];
                $Special_Information = $surgical['Special_Information'];
                $Handling_nurse = $surgical['Handling_nurse'];
                $Operative_Date_Time = substr($surgical['Operative_Date_Time'], 0, -9);
                $can_proceed = $surgical['can_proceed'];
                $instruction = $surgical['instruction'];
                $Theatre_Signature = $surgical['Theatre_Signature'];
                $recovery_nurse = $surgical['recovery_nurse'];
            }

               $htm .= "<table width ='100%' height = '30px'>
               <tr><td><img src='./branchBanner/branchBanner.png' width=100%></td></tr>
               </table>";
           $htm .= "<table width ='100%' height = '30px'>
                        <tr><td style='text-align: center; font-weight: bold;' colspan='7'>PRE-OP SURGICAL - ANESTHETHIA CHECKLIST</td></tr>
                            <tr>
                            <td colspan='7'><hr></td>
                        </tr>
                        <tr>
                            <td><span style='text-align: right; font-size: small;'><b>Patient Name: </td>
                            <td colspan='2'><span style='font-size: small;'>" . ucwords(strtolower($Patient_Name)) . "</span></td>
                            <td><span style='text-align: right; font-size: small;'><b>Registration #: </td>
                            <td><span style='font-size: small;'></b>" . $Registration_ID . "</span></td>
                            <td><span style='text-align: right; font-size: small;'><b>Gender:</span></td>
                            <td><span style='font-size: small;'></b>" . $Gender . "</span></td>
                        </tr>
                        <tr>
                            <td><span style='text-align: right; font-size: small;'><b>Sponsor Name: </td>
                            <td colspan='2'></b>" . strtoupper($Guarantor_Name) . "</span></td>
                            <td><span style='text-align: right; font-size: small;'><b>Age: </span></td>
                            <td><span style='font-size: small;'></b>" . $age . "</span></td>
                            <td><span style='text-align: right; font-size: small;'><b>Admitted By: </td>
                            <td><span style='font-size: small;'></b>" . $Employee_Name . "</span></td>
                        </tr>
                        <tr>
                            <td><span style='text-align: right; font-size: small;'><b>Ward: </td>
                            <td colspan='2'></b>" . $Hospital_Ward_Name . "</td>
                            <td><span style='text-align: right; font-size: small;'><b>Room/Bed #: </td>
                            <td><span style='font-size: small;'></b>".$room_name."/" . $Bed_Name . "</td>
                            <td><span style='text-align: right; font-size: small;'><b>Ordered Doctor: </span></td>
                            <td><span style='font-size: small;'></b>" . $surgeon_ordered . "</span></td>

                        </tr>
                        <tr>
                            <td><span style='text-align: right; font-size: small;'><b>Operation: </span></td>
                            <td colspan='2'></b>" . $surgery_ordered. "</span></td>
                            <td><span style='text-align: right; font-size: small;'><b>Surgery Date: </span></td>
                            <td><span style='font-size: small;'></b>" . $Operative_Date_Time . "</span></td>
                        </tr>
                        </table>
                        
                        <table width ='100%'  border='1' style='border-collapse: collapse;'>
                        <tr style='background: #dedede;'>
                        <td style='width: 2%;'>SN</td>
                            <td  style='text-align: center;'><b>ACTIVITIES DONE</b></td>
                            <td  style='text-align: center;' width=5%><b>YES/NO/N/A</b></td>
                            <td style='text-align: center;'><b>REMARKS</b></td>SELECT Sn, Task_Name, Task_Value, Remark FROM tbl_who_pre_operative_checklist_items WHERE Pre_Operative_ID = '4' AND Sn < 9 GROUP BY Sn ORDER BY Sn ASC
                        </tr>";

    $preoperative_item_list = mysqli_query($conn, "SELECT Sn, Task_Name, Task_Value, Remark FROM tbl_who_pre_operative_checklist_items WHERE Pre_Operative_ID = '$Pre_Operative_ID' AND Sn < 9 GROUP BY Sn ORDER BY Sn ASC");
            while($details = mysqli_fetch_array($preoperative_item_list)){
                $task_Name = $details['Task_Name'];
                $task_value = $details['Task_Value'];
                $Remarkss = $details['Remark'];
                $num = $details['Sn'];
        
                $htm.="<tr>
                            <td>$num</td>
                            <td>$task_Name</td>
                            <td style='text-align: center; font-weight: bold;'>$task_value</td>
                            <td>$Remarkss</td>
                        </tr>";
                // $num++;
            }

            $htm .="               <tr style='background: #dedede;'><td></td>
                        <td colspan='4' style='font-weight: bold;'>TIME OUT PRIOR TO SKIN INCISION</td></tr>";

            $preoperative_item_list = mysqli_query($conn, "SELECT Sn, Task_Name, Task_Value, Remark FROM tbl_who_pre_operative_checklist_items WHERE Pre_Operative_ID = '$Pre_Operative_ID' AND Sn BETWEEN 9 AND 20 GROUP BY Sn ORDER BY Sn ASC");
            while($details = mysqli_fetch_array($preoperative_item_list)){
                $task_Name = $details['Task_Name'];
                $task_value = $details['Task_Value'];
                $Remarkss = $details['Remark'];
                $num = $details['Sn'];
        
                $htm.="<tr>
                            <td>$num</td>
                            <td>$task_Name</td>
                            <td style='text-align: center; font-weight: bold;'>$task_value</td>
                            <td>$Remarkss</td>
                        </tr>";
                // $num++;
            }

            $htm .= "<tr style='background: #dedede;'><td></td>
            <td colspan='5' style='text-align: '>ANTICIPATED CRITICAL EVENTS:</td></tr>
            <tr style='background: #dedede;'><td></td>
            <td colspan='5' style='text-align: '>Surgeon reviews:</td></tr>";

            $preoperative_item_list = mysqli_query($conn, "SELECT Sn, Task_Name, Task_Value, Remark FROM tbl_who_pre_operative_checklist_items WHERE Pre_Operative_ID = '$Pre_Operative_ID' AND Sn > 20 GROUP BY Sn ORDER BY Sn ASC");
            while($details = mysqli_fetch_array($preoperative_item_list)){
                $task_Name = $details['Task_Name'];
                $task_value = $details['Task_Value'];
                $Remarkss = $details['Remark'];
                $num = $details['Sn'];
        
                $htm.="<tr>
                            <td>$num</td>
                            <td>$task_Name</td>
                            <td style='text-align: center; font-weight: bold;'>$task_value</td>
                            <td>$Remarkss</td>
                        </tr>";
                // $num++;
            }
            $htm.="</table>";
            $signature_ward = mysqli_fetch_assoc(mysqli_query($conn, "SELECT employee_signature FROM tbl_employee WHERE   Employee_ID = '$Surgeon_filled'"))['employee_signature'];
            $Ward_Nurse = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Employee_Name FROM tbl_employee WHERE  Employee_ID = '$Surgeon_filled'"))['Employee_Name'];

            
            if(!empty($signature_ward)){
                $ward_nurse_22 = "<img src='../esign/employee_signatures/$signature_ward' style='height:25px'>";
            }else{
                $ward_nurse_22 = "________________";
            }

            $htm .="<table width='100%'>
                    <tr><td>&nbsp;</td></tr>
                    <tr>
                        <td colspan='5'><b>Remarks: The operation can proceed? $can_proceed</b></td>
                    </tr>";
                    $htm .="<tr>
                    <td>Surgeon:</td>
                    <td colspan='2'><b>".ucwords($Ward_Nurse)."</b></td>
                    <td>Signature:</td>";
                    $htm.="
                    <td colspan='2'><b>$ward_nurse_22</b></td>
                </tr>";

                $signature_ward_2 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT employee_signature FROM tbl_employee WHERE   Employee_ID = '$nurse_instruments'"))['employee_signature'];
                $Ward_Nurse_2= mysqli_fetch_assoc(mysqli_query($conn, "SELECT Employee_Name FROM tbl_employee WHERE   Employee_ID = '$nurse_instruments'"))['Employee_Name'];

                if(!empty($signature_ward_2)){
                    $ward_nurse_22 = "<img src='../esign/employee_signatures/$signature_ward_2' style='height:25px'>";
                }else{
                    $ward_nurse_22 = "________________";
                }

                $htm.="<tr>
                    <td>Nurse Instruments:</td>
                    <td colspan='2'><b>".ucwords($Ward_Nurse_2)."</b></td>
                    <td>Signature:</td>";
            // $signature_handling = mysqli_fetch_assoc(mysqli_query($conn, "SELECT employee_signature FROM tbl_employee WHERE   Employee_Name = '$Handling_nurse'"))['employee_signature'];
                if(!empty($signature_ward_2)){
                    $ward_handling = "<img src='../esign/employee_signatures/$signature_ward_2' style='height:25px'>";
                }else{
                    $ward_handling = "________________";
                }
                $htm.="
                    <td colspan='2'><b>$ward_nurse_22</b></td>
                </tr>";


                $Anesthetist_2 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT employee_signature FROM tbl_employee WHERE   Employee_ID = '$Anesthetist'"))['employee_signature'];
                $Anesthetist_22= mysqli_fetch_assoc(mysqli_query($conn, "SELECT Employee_Name FROM tbl_employee WHERE   Employee_ID = '$Anesthetist'"))['Employee_Name'];


                        $htm .="<tr>
                    <td>Anesthetist:</td>
                    <td colspan='2'><b>".ucwords($Anesthetist_22)."</b></td>
                    <td>Signature:</td>";
            // $signature_theather = mysqli_fetch_assoc(mysqli_query($conn, "SELECT employee_signature FROM tbl_employee WHERE   Employee_Name = '$Theatre_Signature'"))['employee_signature'];
                if(!empty($Anesthetist_2)){
                    $ward_theater = "<img src='../esign/employee_signatures/$Anesthetist_2' style='height:25px'>";
                }else{
                    $ward_theater = "________________";
                }
                $htm.="
                    <td colspan='2'><b>$ward_theater</b></td></tr>";


                    $htm .="</table>
                    <br/>";

                // echo $htm;


include("MPDF/mpdf.php");
$htm = utf8_encode($htm);
$mpdf=new mPDF(); 
$mpdf->WriteHTML($htm);
$mpdf->Output();
?>