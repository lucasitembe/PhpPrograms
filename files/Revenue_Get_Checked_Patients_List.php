
<?php @session_start();
include ("./includes/connection.php");
if (isset($_GET['Date_From'])) {
    $Date_From = $_GET['Date_From'];
} else {
    $Date_To = '';
}
if (isset($_GET['Date_To'])) {
    $Date_To = $_GET['Date_To'];
} else {
    $Date_To = '';
}
if (isset($_GET['Search_Value'])) {
    $Search_Value = $_GET['Search_Value'];
} else {
    $Search_Value = '';
}
if (isset($_GET['Sponsor_ID'])) {
    $Sponsor_ID = $_GET['Sponsor_ID'];
} else {
    $Sponsor_ID = '';
}
if (isset($_GET['Type_Of_Check_In'])) {
    $Type_Of_Check_In = $_GET['Type_Of_Check_In'];
    if ($Type_Of_Check_In == "All") {
        $Type_Of_Check_In = '';
    } else {
        $Type_Of_Check_In = "ci.Type_Of_Check_In='$Type_Of_Check_In' AND";
    }
} else {
    $Type_Of_Check_In = '';
}
if (isset($_GET['consultation_type'])) {
    $consultation_type = $_GET['consultation_type'];
    if ($consultation_type == "All") {
        $consultation_type = '';
    } else {
        if ($consultation_type == "Doctor Room") {
            $consultation_type = "ppl.Check_In_Type='Doctor Room' AND";
        } else {
            $consultation_type = "ppl.Check_In_Type<>'Doctor Room' AND";
        }
    }
} else {
    $consultation_type = '';
}
if (isset($_GET['Admit_Status'])) {
    $Admit_Status = $_GET['Admit_Status'];
    if ($Admit_Status == "All") {
        $Admit_Status = '';
    } else {
        $checkin_details_tbl = "tbl_check_in_details chd,";
        $checkin_details_join_cond = "ci.Check_In_ID=chd.Check_In_ID and pr.Registration_ID=chd.Registration_ID and";
        if ($Admit_Status == "admitted") {
            $Admit_Status = "chd.Admit_Status='admitted' AND";
        } else {
            $Admit_Status = "chd.Admit_Status<>'admitted' AND";
        }
    }
} else {
    $Admit_Status = "";
    $checkin_details_join_cond = "";
    $checkin_details_tbl = "";
}
if (isset($_GET['start_age'])) {
    $start_age = $_GET['start_age'];
} else {
    $start_age = '';
}
if (isset($_GET['end_age'])) {
    $end_age = $_GET['end_age'];
} else {
    $end_age = '';
}
if ($start_age !='' && $end_age != '') {
    $age_filter = "TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,CURDATE()) BETWEEN '" . $start_age . "' AND '" . $end_age . "' AND";
} else {
    $age_filter = "";
}

if (isset($_GET['diagnosis_type'])) {
    $diagnosis_type = $_GET['diagnosis_type'];
    $diagnosis_type_to_display = $_GET['diagnosis_type'];
    if ($diagnosis_type == "All") {
        $diagnosis_type = '';
    } else {
        $diagnosis_type = "dc.diagnosis_type='$diagnosis_type' AND";
    }
} else {
    $diagnosis_type = '';
}
if (isset($_GET['patient_gender'])) {
    $patient_gender = $_GET['patient_gender'];
    if ($patient_gender == "All") {
        $patient_gender = '';
    } else {
        $patient_gender = "pr.Gender='$patient_gender' AND";
    }
} else {
    $patient_gender = '';
}
if (isset($_GET['rank'])) {
    $rank = $_GET['rank'];
    if ($rank == "All") {
        $rank = '';
    } else {
        $rank = "pr.rank='$rank' AND";
    }
} else {
    $rank = '';
}
if (isset($_GET['patient_type'])) {
    $patient_type = $_GET['patient_type'];
    if ($patient_type == "All") {
        $patient_type = '';
    } else {
        $patient_type = "pr.patient_type='$patient_type' AND";
    }
} else {
    $patient_type = '';
}
if (isset($_GET['military_unit'])) {
    $military_unit = $_GET['military_unit'];
    if ($military_unit == "All") {
        $military_unit = '';
    } else {
        $military_unit = "pr.military_unit='$patient_type' AND";
    }
} else {
    $military_unit = '';
}
if (isset($_GET['visit_type'])) {
    $visit_type = $_GET['visit_type'];
    if ($visit_type == "All") {
        $visit_type = '';
    } else {
        $visit_type = "ci.visit_type='$visit_type' AND";
    }
} else {
    $visit_type = '';
}
if (isset($_GET['alive_diceased'])) {
    $alive_diceased = $_GET['alive_diceased'];
    if ($alive_diceased == "All") {
        $alive_diceased = '';
    } else {
        $alive_diceased = "ci.Diceased='$alive_diceased' AND";
    }
} else {
    $alive_diceased = '';
}
if (isset($_GET['pf3'])) {
    $pf3 = $_GET['pf3'];
    if ($pf3 == "All") {
        $pf3 = '';
    } else {
        $pf3 = "ci.pf3='$alive_diceased' AND";
    }
} else {
    $pf3 = '';
}
if (isset($_GET['Search_Patient_number'])) {
    $Search_Patient_number = $_GET['Search_Patient_number'];
    if ($Search_Patient_number != '') {
        $Search_Patient_number = "ci.Registration_ID='$Search_Patient_number' AND";
    } else {
        $Search_Patient_number = '';
    }
} else {
    $Search_Patient_number = '';
}
if (isset($_GET['Destination'])) {
    $Dest = $_GET['Destination'];
} else {
    $Dest = 'x';
};
echo '


<legend style="background-color:#006400;color:white;padding:5px;" align="right"><b>MASTER SHEET</b></legend>
    <table width=100%>
        
        <tr style="background:#cccccc">
            <td width=2%><b>SN</b></td>
            <td width=6%><b>PATIENT#</b></td>
            <td><b>PATIENT NAME</b></td>
            <td><b>AGE</b></td>
            <td><b>SEX</b></td>
            <td><b>TRIBE</b></td>
            <td><b>RESIDENT</b></td>	
            <td><b>REFEREED FROM </b></td>
            <td><b>REFEREED REASON </b></td>
            <td><b>REFERAL ATTACHMENT </b></td>
            <td><b>READ</b></td>
            <td><b>ADMITTED</b></td>
            <td><b>DISCHARGE</b></td>
            <td><b>DIED</b></td>
            <td>
                    ';
if ($diagnosis_type_to_display == "provisional_diagnosis") {;
    echo '                                <b>PROVISIONAL DIAGNOSIS</b>
                                ';
} else if ($diagnosis_type_to_display == "diferential_diagnosis") {;
    echo '                                <b>DIFFERENTIAL DIAGNOSIS</b>
                                ';
} else if ($diagnosis_type_to_display == "diagnosis") {;
    echo '                                <b>FINAL DIAGNOSIS</b>
                                ';
} else {;
    echo '                                <b> DIAGNOSIS</b>
                                ';
};
echo '</td>
            <td><b>REMARKS</b></td>
            <td><b>PATIENT DIRECTION</b></td>
            <td><b>PATIENT DESTINATION</b></td>
            <td><b>PHONE</b></td>
            <td><b>AUTHORIZATION NUMBER</b></td>
        </tr>
        
        ';
$temp = 0;
$Destination = '';
if ($Sponsor_ID == 0) {
    if (isset($_GET['Search_Value']) && $Search_Value !='') {
        $select = mysqli_query($conn, "select ci.AuthorizationNo,ci.referral_letter,ci.referral_reason,sp.Guarantor_Name,ci.Check_In_Date_And_Time, pr.Registration_ID, pr.Patient_Name,pr.Date_Of_Birth, pr.Gender, pr.Tribe, pr.Region, pr.District, pr.village, pr.Phone_Number, ci.Type_Of_Check_In, ci.Check_In_Date_And_Time, emp.Employee_Name, ci.Check_In_ID    from $checkin_details_tbl tbl_check_in ci, tbl_employee emp, tbl_patient_registration pr, tbl_patient_payments pp, tbl_sponsor sp where    pr.Registration_ID = ci.Registration_ID and     ci.Employee_ID = emp.Employee_ID and      sp.Sponsor_ID = pr.Sponsor_ID and   $checkin_details_join_cond     pp.Check_In_ID = ci.Check_In_ID and $pf3 $alive_diceased $visit_type $patient_gender $Type_Of_Check_In $Search_Patient_number $rank $military_unit $patient_type $Admit_Status $age_filter    ci.Check_In_Date_And_Time between '$Date_From' and '$Date_To' and     pr.Patient_Name like '%$Search_Value%'    group by ci.Check_In_ID order by ci.Check_In_Date_And_Time asc") or die(mysqli_error($conn));
    } else {
        $select = mysqli_query($conn, "select ci.AuthorizationNo,ci.referral_letter,ci.referral_reason,sp.Guarantor_Name,ci.Check_In_Date_And_Time, pr.Registration_ID, pr.Patient_Name,pr.Date_Of_Birth, pr.Gender, pr.Tribe, pr.Region, pr.District, pr.village, pr.Phone_Number, ci.Type_Of_Check_In, ci.Check_In_Date_And_Time, emp.Employee_Name, ci.Check_In_ID       from $checkin_details_tbl tbl_check_in ci, tbl_employee emp, tbl_patient_registration pr, tbl_patient_payments pp, tbl_sponsor sp where       pr.Registration_ID = ci.Registration_ID and     ci.Employee_ID = emp.Employee_ID and     sp.Sponsor_ID = pr.Sponsor_ID and     $checkin_details_join_cond       pp.Check_In_ID = ci.Check_In_ID and $pf3 $alive_diceased $visit_type $patient_gender $Type_Of_Check_In $Search_Patient_number $rank $military_unit $patient_type $Admit_Status $age_filter   ci.Check_In_Date_And_Time between '$Date_From' and '$Date_To'    group by ci.Check_In_ID order by ci.Check_In_Date_And_Time asc") or die(mysqli_error($conn));
    }
} else {
    if (isset($_GET['Search_Value']) && $Search_Value !='') {
        $select = mysqli_query($conn, "select ci.AuthorizationNo,ci.referral_letter,ci.referral_reason,sp.Guarantor_Name,ci.Check_In_Date_And_Time, pr.Registration_ID, pr.Patient_Name,pr.Date_Of_Birth, pr.Gender, pr.Tribe, pr.Region, pr.District, pr.village, pr.Phone_Number, ci.Type_Of_Check_In, ci.Check_In_Date_And_Time, emp.Employee_Name, ci.Check_In_ID    from $checkin_details_tbl tbl_check_in ci, tbl_employee emp, tbl_patient_registration pr, tbl_patient_payments pp, tbl_sponsor sp where
                                            pr.Registration_ID = ci.Registration_ID and
                                            ci.Employee_ID = emp.Employee_ID and
                                            sp.Sponsor_ID = pr.Sponsor_ID and
                                            $checkin_details_join_cond
                                            
                                            pr.Sponsor_ID = '$Sponsor_ID' and
                                            pp.Check_In_ID = ci.Check_In_ID and $pf3 $alive_diceased $visit_type $patient_gender $Type_Of_Check_In $Search_Patient_number $rank $military_unit $patient_type $Admit_Status $age_filter
                                            ci.Check_In_Date_And_Time between '$Date_From' and '$Date_To' and
                                            pr.Patient_Name like '%$Search_Value%'
                                            group by ci.Check_In_ID order by ci.Check_In_Date_And_Time asc") or die(mysqli_error($conn));
    } else {
        $select = mysqli_query($conn, "select ci.AuthorizationNo,ci.referral_letter,ci.referral_reason,sp.Guarantor_Name,ci.Check_In_Date_And_Time, pr.Registration_ID, pr.Patient_Name,pr.Date_Of_Birth, pr.Gender, pr.Tribe, pr.Region, pr.District, pr.village, pr.Phone_Number, ci.Type_Of_Check_In, ci.Check_In_Date_And_Time, emp.Employee_Name, ci.Check_In_ID   from $checkin_details_tbl tbl_check_in ci, tbl_employee emp, tbl_patient_registration pr, tbl_patient_payments pp, tbl_sponsor sp where     pr.Registration_ID = ci.Registration_ID and     ci.Employee_ID = emp.Employee_ID and   $checkin_details_join_cond     sp.Sponsor_ID = pr.Sponsor_ID and     pr.Sponsor_ID = '$Sponsor_ID' and    pp.Check_In_ID = ci.Check_In_ID and $pf3 $alive_diceased $visit_type $patient_gender $Type_Of_Check_In $Search_Patient_number $rank $military_unit $patient_type $Admit_Status $age_filter     ci.Check_In_Date_And_Time between '$Date_From' and '$Date_To'     group by ci.Check_In_ID order by ci.Check_In_Date_And_Time asc") or die(mysqli_error($conn));

    }
}
$num = mysqli_num_rows($select);
if ($num > 0) {
    while ($row = mysqli_fetch_array($select)) {
        $Check_In_ID = $row['Check_In_ID'];
        $AuthorizationNo = $row['AuthorizationNo'];
        if ($Dest == 'x') {
            $get = mysqli_query($conn, "select ppl.Patient_Payment_Item_List_ID,ppl.Patient_Direction, ppl.Consultant_ID, ppl.Consultant, ppl.Check_In_Type from
                                        tbl_patient_payment_item_list ppl, tbl_patient_payments pp where
                                        pp.Check_In_ID = '$Check_In_ID' and $consultation_type
                                        pp.Patient_Payment_ID = ppl.Patient_Payment_ID
                                        group by pp.Check_In_ID order by Patient_Payment_Item_List_ID limit 1") or die(mysqli_error($conn));
        } else if ($Dest == 'y') {
            $get = mysqli_query($conn, "select ppl.Patient_Payment_Item_List_ID,ppl.Patient_Direction, ppl.Consultant_ID, ppl.Consultant, ppl.Check_In_Type from
                                        tbl_patient_payment_item_list ppl, tbl_patient_payments pp where
                                        pp.Check_In_ID = '$Check_In_ID' and $consultation_type
                                        pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                                        ppl.Patient_Direction = 'Direct To Doctor'
                                        group by pp.Check_In_ID order by Patient_Payment_Item_List_ID limit 1") or die(mysqli_error($conn));
        } else if ($Dest == 'z') {
            $get = mysqli_query($conn, "select ppl.Patient_Payment_Item_List_ID,ppl.Patient_Direction, ppl.Consultant_ID, ppl.Consultant, ppl.Check_In_Type from
                                        tbl_patient_payment_item_list ppl, tbl_patient_payments pp where
                                        pp.Check_In_ID = '$Check_In_ID' and $consultation_type
                                        pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                                        ppl.Patient_Direction = 'Direct To Doctor Via Nurse Station'
                                        group by pp.Check_In_ID order by Patient_Payment_Item_List_ID limit 1") or die(mysqli_error($conn));
        } else if (substr($Dest, 0, 9) == 'Clinic_ID') {
            $Clinic_ID = substr($Dest, 9);
            $get = mysqli_query($conn, "select ppl.Patient_Payment_Item_List_ID,ppl.Patient_Direction, ppl.Consultant_ID, ppl.Consultant, ppl.Check_In_Type from
                                        tbl_patient_payment_item_list ppl, tbl_patient_payments pp where
                                        pp.Check_In_ID = '$Check_In_ID' and $consultation_type
                                        pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                                        (ppl.Patient_Direction = 'Direct To Clinic' or ppl.Patient_Direction = 'Direct To Clinic Via Nurse Station') and
                                        ppl.Clinic_ID = '$Clinic_ID'
                                        group by pp.Check_In_ID order by Patient_Payment_Item_List_ID limit 1") or die(mysqli_error($conn));
        } else {
            $get = mysqli_query($conn, "select ppl.Patient_Payment_Item_List_ID,ppl.Patient_Direction, ppl.Consultant_ID, ppl.Consultant, ppl.Check_In_Type from
                                        tbl_patient_payment_item_list ppl, tbl_patient_payments pp where
                                        pp.Check_In_ID = '$Check_In_ID' and $consultation_type
                                        pp.Patient_Payment_ID = ppl.Patient_Payment_ID
                                        group by pp.Check_In_ID order by Patient_Payment_Item_List_ID limit 1") or die(mysqli_error($conn));
        }
        $num_rows = mysqli_num_rows($get);
        if ($num_rows > 0) {
            while ($data = mysqli_fetch_array($get)) {
                $Patient_Direction = $data['Patient_Direction'];
                $Consultant_ID = $data['Consultant_ID'];
                $Check_In_Type = $data['Check_In_Type'];
                $Consultant = $data['Consultant'];
                $Patient_Payment_Item_List_ID = $data['Patient_Payment_Item_List_ID'];
                if (strtolower($Patient_Direction) == 'others') {
                    $Patient_Direction = $Check_In_Type;
                }
                if (substr($Dest, 0, 9) == 'Clinic_ID') {
                    $slct = mysqli_query($conn, "select Clinic_Name from tbl_clinic where Clinic_ID = '$Clinic_ID'") or die(mysqli_error($conn));
                    $nm = mysqli_num_rows($slct);
                    if ($nm > 0) {
                        while ($dt = mysqli_fetch_array($slct)) {
                            $Destination = $dt['Clinic_Name'];
                        }
                    } else {
                        $Destination = 'Clinic';
                    }
                } else {
                    if (strtolower($Check_In_Type) == 'doctor room') {
                        if (strtolower($Patient_Direction) == 'direct to doctor' || strtolower($Patient_Direction) == 'direct to doctor via nurse station') {
                            $select_doctor = mysqli_query($conn, "select Employee_Name from tbl_employee where Employee_ID = '$Consultant_ID'") or die(mysqli_error($conn));
                            $no_of_rows = mysqli_num_rows($select_doctor);
                            if ($no_of_rows > 0) {
                                while ($detail = mysqli_fetch_array($select_doctor)) {
                                    $Destination = 'Dr. ' . $detail['Employee_Name'];
                                }
                            } else {
                                $Destination = $Consultant;
                            }
                        } else {
                            $Destination = $Consultant;
                        }
                    } else {
                        $Destination = $Check_In_Type;
                    }
                }
                $Registration_ID = $row['Registration_ID'];
                $referral_reason = $row['referral_reason'];
                $referral_letter = $row['referral_letter'];
                $hospital_name = "";
                $reffered_patient_from_result = mysqli_query($conn, "SELECT hospital_name FROM tbl_referred_from_hospital rfh INNER JOIN tbl_referred_patients rp ON rfh.referred_from_hospital_id=rp.Referral_Hospital_ID WHERE Registration_ID='$Registration_ID' AND Check_In_ID='$Check_In_ID' ") or die(mysqli_error($conn));
                if (mysqli_num_rows($reffered_patient_from_result) > 0) {
                    $hospital_name = $hosp_row = mysqli_fetch_assoc($reffered_patient_from_result) ['hospital_name'];
                }
                $diagnosis = "";
                $sql_get_diagnosis = mysqli_query($conn, "SELECT disease_name FROM tbl_disease d INNER JOIN tbl_disease_consultation dc ON d.disease_ID=dc.disease_ID INNER JOIN tbl_consultation c ON dc.consultation_ID=c.consultation_ID WHERE $diagnosis_type c.Patient_Payment_Item_List_ID='$Patient_Payment_Item_List_ID'") or die(mysqli_error($conn));
                if (mysqli_num_rows($sql_get_diagnosis) > 0) {
                    while ($diagnosis_rows = mysqli_fetch_assoc($sql_get_diagnosis)) {
                        $disease_name = $diagnosis_rows['disease_name'];
                        $diagnosis = $diagnosis . $disease_name . ",";
                    }
                }
                $sql_select_admission_status_result = mysqli_query($conn, "SELECT Admit_Status FROM tbl_check_in_details WHERE Registration_ID='$Registration_ID' AND Check_In_ID='$Check_In_ID'") or die(mysqli_error($conn));
                if (mysqli_num_rows($sql_select_admission_status_result) > 0) {
                    $row_admt_status = mysqli_fetch_assoc($sql_select_admission_status_result);
                    $Admit_Status = $row_admt_status['Admit_Status'];
                }
                $date1 = new DateTime($Today);
                $date2 = new DateTime($row['Date_Of_Birth']);
                $diff = $date1->diff($date2);
                $age = $diff->y . " Years, ";
                $age.= $diff->m . " Months, ";
                $age.= $diff->d . " Days";
                $resident = $row['village'] . " " . $row['District'] . " " . $row['Region'];
                echo "<tr>
			    <td>" . ++$temp . "</td>
			    <td>" . $row['Registration_ID'] . "</td>
                            <td>" . $row['Patient_Name'] . "</td>
			    <td>" . $age . "</td>
			    <td>" . $row['Gender'] . "</td>
			    <td>" . $row['Tribe'] . "</td>
			    <td>" . $resident . "</td>
			    <td>$hospital_name</td>
			    <td>$referral_reason</td>
			    <td>";
                if ($referral_letter != "") {
                    echo "<a href='excelfiles/$referral_letter' target='_blank' ><img src='attachment_icon/attachment.png' width='50px'/></a></td>";
                }
                echo " <td>" . $row['Check_In_Date_And_Time'] . "</td>
			    <td>$Admit_Status</td>
                            <td></td>
                            <td>" . "</td>
                            <td>$diagnosis</td>
                            <td>" . "</td>
                            <td>" . $Patient_Direction . "</td>
                            <td>" . $Destination . "</td>
                            <td>" . $row['Phone_Number'] . "</td>
                            <td>" . $AuthorizationNo . "</td>
			</tr>
                        </tr>";
            }
        } else {
            $Destination = '';
            $Patient_Direction = '';
        }
    }
};
echo '    </table>';