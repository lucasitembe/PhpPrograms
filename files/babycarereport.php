<?php
    session_start();
    include("./includes/connection.php");

    if(isset($_SESSION['userinfo'])){
        $E_Name = $_SESSION['userinfo']['Employee_Name'];
    }else{
        $E_Name = '';
    }

    if(isset($_GET['Registration_ID'])){
        $Registration_ID = $_GET['Registration_ID'];
    }else{
        $Registration_ID = 0;
    }

    if(isset($_GET['baby'])){
        $baby = $_GET['baby'];
    }else{
        $baby = 0;
    }

    if(isset($_GET['consultation_ID'])){
        $consultation_ID = $_GET['consultation_ID'];
    }else{
        $consultation_ID = 0;
    }

    $select_patien_details = mysqli_query($conn,"select Member_Number,Patient_Name, Registration_ID,Gender,Guarantor_Name,Date_Of_Birth
                                            from tbl_patient_registration pr, tbl_sponsor sp where
                                            pr.Registration_ID = '$Registration_ID' and sp.Sponsor_ID = pr.Sponsor_ID") or die(mysqli_error($conn));
    $no = mysqli_num_rows($select_patien_details);
    if ($no > 0) {
        while ($row = mysqli_fetch_array($select_patien_details)) {
            $Member_Number = $row['Member_Number'];
            $Patient_Name = $row['Patient_Name'];
            $Registration_ID = $row['Registration_ID'];
            $Gender = $row['Gender'];
            $Sponsor = $row['Guarantor_Name'];
            $ParentDOB = $row['Date_Of_Birth'];
        }
        $date1 = new DateTime();
        $date2 = new DateTime($ParentDOB);
        $diff = $date1->diff($date2);
        $age = $diff->y . " Years, ";
        $age .= $diff->m . " Months, ";
        $age .= $diff->d . " Days";
    } else {
        $Member_Number = '';
        $Patient_Name = '';
        $Registration_ID = '';
        $Gender = '';
        $Sponsor = '';
        $ParentDOB = '';
        $age = '';
    }

    $baby_care_id = 0;
    $data = '<img src="branchBanner/branchBanner.png" width="100%" ><br/><br/>';
    $data .= '<b><span style="font-size:15px;text-align:center;">BABY CARE CHARTING REPORT<br/>
                Parent Name: '.strtoupper($Patient_Name).'<br/>
                Reg #: '.$Registration_ID.'&nbsp;&nbsp;|&nbsp;&nbsp;Sponsor: '.$Sponsor.'&nbsp;&nbsp;|&nbsp;&nbsp;Age: '.$age.'
            </span></b><br/><br/>';
     

    $select = mysqli_query($conn,"select * from tbl_baby_care where parent_id='$Registration_ID' and consultation_id = '$consultation_ID' and babywho = '$baby'") or die(mysqli_error($conn));
    $nm = mysqli_num_rows($select);
    if($nm > 0){
        while ($infos = mysqli_fetch_array($select)) {
            $baby_care_id = $infos['baby_care_id'];
            $parent_id = $infos['parent_id'];
            $babywho = $infos['babywho'];
            $dob = $infos['dob'];
            $apgarscore = $infos['apgarscore'];
            $sex = $infos['sex'];
            $babyName = '';


            if ($babywho == 'babyone'){
                $babyName = 'Baby One';
            }else if ($babywho == 'babytwo'){
                $babyName = 'Baby Two';
            }else if ($babywho == 'babythree'){
                $babyName = 'Baby Three';
            }else if ($babywho == 'babyfour'){
                $babyName = 'Baby Four';
            }else if ($babywho == 'babyfive'){
                $babyName = 'Baby Five';
            }else if ($babywho == 'babysix'){
                $babyName = 'Baby One';
            }



            $weight = $infos['weight'];
            $length = $infos['length'];
            $type_of_delivery = $infos['type_of_delivery'];

            if ($type_of_delivery == 'virginal'){
                $type_of_delivery = 'Virginal Delivery';
            }else if ($type_of_delivery == 'cesarean'){
                $type_of_delivery = 'Cesarean Delivery';
            }else if ($type_of_delivery == 'virginalaftercesarean'){
                $type_of_delivery = 'Virginal Birth After Cesarean';
            }else if ($type_of_delivery == 'vacuumextraction'){
                $type_of_delivery = 'Vacuum Extraction';
            }else if ($type_of_delivery == 'forceps'){
                $type_of_delivery = 'Forceps Delivery';
            }
            
           

            $head_circum_chest = $infos['head_circum_chest'];

            //Maternity

            $checkMaternity = mysqli_query($conn,"SELECT  prom, hypertension, multpvexams, preeclampsia, resuscitation, rh_ve, maternal_std, spinal_or, maternal_hiv, general, diabetes, anaesthesia FROM tbl_maternity_risk_factor WHERE baby_care_id='$baby_care_id' ") or die(mysqli_error($conn));

            $maternityInfos = mysqli_fetch_array($checkMaternity);

            $prom = ($maternityInfos['prom'] == 'yes') ? "checked='checked'" : '';
            $hypertension = ($maternityInfos['hypertension'] == 'yes') ? "checked='checked'" : '';
            $multpvexams = ($maternityInfos['multpvexams'] == 'yes') ? "checked='checked'" : '';
            $preeclampsia = ($maternityInfos['preeclampsia'] == 'yes') ? "checked='checked'" : '';
            $maternal_std = ($maternityInfos['maternal_std'] == 'yes') ? "checked='checked'" : '';
            $rh_ve = ($maternityInfos['rh_ve'] == 'yes') ? "checked='checked'" : '';
            $resuscitation = ($maternityInfos['resuscitation'] == 'yes') ? "checked='checked'" : '';
            $diabetes = ($maternityInfos['diabetes'] == 'yes') ? "checked='checked'" : '';
            $anaesthesia = ($maternityInfos['anaesthesia'] == 'yes') ? "checked='checked'" : '';
            $spinal_or = ($maternityInfos['spinal_or'] == 'yes') ? "checked='checked'" : '';
            $maternal_hiv = ($maternityInfos['maternal_hiv'] == 'yes') ? "checked='checked'" : '';
           // $type_of_delivery = ($maternityInfos['type_of_delivery'] == 'yes') ? "checked='checked'" : '';
            $general = ($maternityInfos['general'] == 'yes') ? "checked='checked'" : '';

            //examinition

            $checkExams = mysqli_query($conn,"SELECT head_eyes_nose_ears_mouth, neck, chest, abdomen, cord, genital, limbs, eyes, hips, anus, neonatal_relexes, feeding, urine, stool, mouth, comments FROM tbl_baby_care_exam WHERE baby_care_id='$baby_care_id' ") or die(mysqli_error($conn));

            $examInfor = mysqli_fetch_array($checkExams);

            $neck = $examInfor['neck'];
            $chest = $examInfor['chest'];
            $abdomen = $examInfor['abdomen'];
            $cord = $examInfor['cord'];
            $genital = $examInfor['genital'];
            $limbs = $examInfor['limbs'];
            $eyes = $examInfor['eyes'];
            $hips = $examInfor['hips'];
            $anus = $examInfor['anus'];
            $neonatal_relexes = $examInfor['neonatal_relexes'];
            $feeding = $examInfor['feeding'];
            $urine = $examInfor['urine'];
            $stool = $examInfor['stool'];
            $mouth = $examInfor['mouth'];
            $feeding = $examInfor['feeding'];
            $comments = $examInfor['comments'];
            $head_eyes_nose_ears_mouth = $examInfor['head_eyes_nose_ears_mouth'];
            


            $data.=" <center>
                        <table width=100% border=1 style='border-collapse: collapse;'>
                            <tr>
                                <td style='width:14%; text-align: right;'><span style='font-size: x-small;'>Baby Name</span></td><td><span style='font-size: x-small;'>".$babyName."</span></td>
                                <td style='width:14%; text-align: right;'><span style='font-size: x-small;'>Apggar Score</span></td><td><span style='font-size: x-small;'>".$apgarscore."</span></td>
                                <td style='text-align: right;'><span style='font-size: x-small;'>Length</span></td><td><span style='font-size: x-small;'>".$length."</span></td>
                            </tr>
                            <tr>
                                <td style='text-align: right;'><span style='font-size: x-small;'>Date of Birth</span></td><td><span style='font-size: x-small;'>".$dob."</span></td>
                                <td style='text-align: right;'><span style='font-size: x-small;'>Sex</span></td><td style='width:10%'><span style='font-size: x-small;'>".strtoupper($sex)."</span></td>
                                <td style='width:17%; text-align: right;'><span style='font-size: x-small;'>Type of Delivery</span></td><td style='width:23%'><span style='font-size: x-small;'>".$type_of_delivery."</td>
                            </tr>
                            <tr>
                                <td style='text-align: right;'><span style='font-size: x-small;'>Weight</span></td><td><span style='font-size: x-small;'>".$weight."</span></td>
                                <td colspan='2' style='text-align: right;'><span style='font-size: x-small;'>Head Circum / Chest</span></td><td colspan='2'><span style='font-size: x-small;'>".$head_circum_chest."</span></td>
                            </tr>
                        </table><br/>
                          <span style='font-size: x-small;'><b>MARTENITY RISK FACTORS TO NEWBORN</b></span>
                           <table width=100% border=1 style='border-collapse: collapse;'>
                                <tr>
                                    <td><input type='checkbox' name='prom' id='prom' " . $prom . "  value='yes'><span style='font-size: x-small;'>PROM</span></td>
                                    <td><input type='checkbox' name='multpvexams' " . $multpvexams . " id='multpvexams' value='yes'><span style='font-size: x-small;'>Multiple PV Exams</span></td>
                                    <td><input type='checkbox' name='resuscitation' " . $resuscitation . " id='resuscitation' value='yes'><span style='font-size: x-small;'>Resuscitation</span></td>
                                    <td><input type='checkbox' name='maternal_std' " . $maternal_std . " id='maternal_std' value='yes'><span style='font-size: x-small;'>Maternal STD</span></td>
                                </tr>
                                <tr>
                                    <td><input type='checkbox' name='hypertension' id='hypertension' " . $hypertension . "  value='yes'><span style='font-size: x-small;'>Hypertension</span></td>
                                    <td><input type='checkbox' name='preeclampsia' id='preeclampsia' " . $preeclampsia . "  value='yes'><span style='font-size: x-small;'>Pre-Eclampsia</span></td>
                                    <td><input type='checkbox' name='rh_ve' id='rh_ve' " . $rh_ve . "  value='yes'><span style='font-size: x-small;'>RH -ve</span></td>
                                    <td><input type='checkbox' name='spinal_or' " . $spinal_or . "  id='spinal_or' value='yes'><span style='font-size: x-small;'>Spinal or</span></td>
                                </tr>
                                <tr>
                                    <td><input type='checkbox' name='maternal_hiv' " . $maternal_hiv . " id='maternal_hiv' value='yes'><span style='font-size: x-small;'>Maternal HIV</span></td>
                                    <td><input type='checkbox' name='diabetes' " . $diabetes . " id='diabetes' value='yes'><span style='font-size: x-small;'>Diabetes</span></td>
                                    <td><input type='checkbox' name='general' " . $general . "  id='general' value='yes'><span style='font-size: x-small;'>General</span></td>
                                    <td><input type='checkbox' name='anaesthesia' " . $anaesthesia . "  id='anaesthesia' value='yes'><span style='font-size: x-small;'>Anaesthesia</span></td>
                                </tr>
                            </table><br/>
                            <table width=100% border=1 style='border-collapse: collapse;'>
                                <tr>
                                    <td width='14%' style='text-align: right;'><span style='font-size: x-small;'>head:Eyes,Nose,<br>Ears,Mouth</span></td>
                                    <td><span style='font-size: x-small;'>" . $head_eyes_nose_ears_mouth . "</span></td>
                                    <td width='14%' style='text-align: right;'><span style='font-size: x-small;'>Cord</span></td>
                                    <td><span style='font-size: x-small;'>" . $cord . "</span></td>
                                </tr>
                                <tr>
                                    <td style='text-align: right;'><span style='font-size: x-small;'>Anus</span></td>
                                    <td><span style='font-size: x-small;'>" . $anus . "</span></td> 
                                    <td style='text-align: right;'><span style='font-size: x-small;'>Mouth</span></td>
                                    <td><span style='font-size: x-small;'>" . $mouth . "</span></td>
                                </tr>
                                <tr>
                                    <td style='text-align: right;'><span style='font-size: x-small;'>Genital</span></td>
                                    <td><span style='font-size: x-small;'>".$genital."</span></td>
                                    <td style='text-align: right;'><span style='font-size: x-small;'>Neonatal Relexes</span></td>
                                    <td><span style='font-size: x-small;'>".$neonatal_relexes."</span></td>
                                </tr>
                                <tr>
                                    <td style='text-align: right;'><span style='font-size: x-small;'>Feeding</span></td>
                                    <td><span style='font-size: x-small;'>".$feeding."</span></td>
                                    <td style='text-align: right;'><span style='font-size: x-small;'>Neck</span></td>
                                    <td><span style='font-size: x-small;'>".$neck."</span></td>
                                </tr>
                                <tr>
                                    <td style='text-align: right;'><span style='font-size: x-small;'>Limbs</span></td>
                                    <td><span style='font-size: x-small;'>".$limbs."</span></td>
                                    <td style='text-align: right;'><span style='font-size: x-small;'>Hips</span></td>
                                    <td><span style='font-size: x-small;'>" . $hips . "</span></td> 
                                </tr>
                                <tr>
                                    <td style='text-align: right;'><span style='font-size: x-small;'>Chest</span></td>
                                    <td><span style='font-size: x-small;'>" . $chest . "</span></td>
                                    <td style='text-align: right;'><span style='font-size: x-small;'>Eyes</span></td>
                                    <td><span style='font-size: x-small;'>" . $eyes . "</span></td>
                                </tr>
                                <tr>
                                    <td style='text-align: right;'><span style='font-size: x-small;'>Urine</span></td>
                                    <td><span style='font-size: x-small;'>" . $urine . "</span></td>
                                    <td style='text-align: right;'><span style='font-size: x-small;'>Abdomen</span></td>
                                    <td><span style='font-size: x-small;'>" . $abdomen . "</span></td>
                                </tr>
                                <tr>
                                    <td style='text-align: right;'><span style='font-size: x-small;'>comments</span></td>
                                    <td><span style='font-size: x-small;'>".$comments."</span></td>
                                    <td style='text-align: right;'><span style='font-size: x-small;'>Stool</span></td>
                                    <td><span style='font-size: x-small;'>" . $stool . "</span></td>
                                </tr>";

                    //health
                    $checkHealth = mysqli_query($conn,"select Employee_Name, date_saved, room_temp, rect_temp, bowel, urine, vomiting, feed, treatment, remarks
                                                from tbl_baby_care_health eh join tbl_employee e ON e.Employee_ID = eh.employee_id where
                                                baby_care_id = '$baby_care_id'") or die(mysqli_error($conn));
                    $nmz = mysqli_num_rows($checkHealth);
                    if($nmz > 0){
                        while ($healthInfos = mysqli_fetch_array($checkHealth)) {
                            $date_saved = $healthInfos['date_saved'];
                            $room_temp = $healthInfos['room_temp'];
                            $rect_temp = $healthInfos['rect_temp'];
                            $bowel = $healthInfos['bowel'];
                            $urine = $healthInfos['urine'];
                            $vomiting = $healthInfos['vomiting'];
                            $feed = $healthInfos['feed'];
                            $treatment = $healthInfos['treatment'];
                            $remarks = $healthInfos['remarks'];
                            $employee_name = $healthInfos['Employee_Name'];
                        }
                    }else{
                        $date_saved = '';
                        $room_temp = '';
                        $rect_temp = '';
                        $bowel = '';
                        $urine = '';
                        $vomiting = '';
                        $feed = '';
                        $treatment = '';
                        $remarks = '';
                        $employee_name = '';
                    }
                        $data .= "<tr>
                                    <td style='text-align: right;'><span style='font-size: x-small;'>Room Temp</span></td>
                                    <td><span style='font-size: x-small;'>".$room_temp."</span></td>
                                    <td style='text-align: right;'><span style='font-size: x-small;'>Treatment</span></td>
                                    <td><span style='font-size: x-small;'>".$treatment."</span></td>
                                </tr>
                                <tr>
                                    <td style='text-align: right;'><span style='font-size: x-small;'>Rect Temp</span></td>
                                    <td><span style='font-size: x-small;'></span>".$rect_temp."</td>
                                    <td style='text-align: right;'><span style='font-size: x-small;'>Bowel</span></td>
                                    <td><span style='font-size: x-small;'>".$bowel."</span></td>
                                </tr>
                                <tr>
                                    <td style='text-align: right;'><span style='font-size: x-small;'>Urine</span></td>
                                    <td><span style='font-size: x-small;'>".$urine."</span></td>
                                    <td style='text-align: right;'><span style='font-size: x-small;'>Vomiting</span></td>
                                    <td><span style='font-size: x-small;'>".$vomiting."</span></td>
                                </tr>
                                <tr>
                                    <td style='text-align: right;'><span style='font-size: x-small;'>Feed</span></td>
                                    <td><span style='font-size: x-small;'>".$feed."</span></td>
                                    <td style='text-align: right;'><span style='font-size: x-small;'>Remarks</span></td>
                                    <td><span style='font-size: x-small;'>".$remarks."</span></td>
                                </tr>
                                <tr>
                                    <td style='text-align: right;'><span style='font-size: x-small;'>Date and Time</span></td>
                                    <td><span style='font-size: x-small;'>".$date_saved."</span></td>
                                    <td style='text-align: right;'><span style='font-size: x-small;'>Saved By</span></td>
                                    <td><span style='font-size: x-small;'>".$employee_name."</span></td>
                                </tr>
                            </table>";

              
        }
}

    include("./MPDF/mpdf.php");
    $mpdf=new mPDF('','A4', 0, '', 15,15,20,40,15,35, 'P');
    $mpdf->SetFooter('Printed By '.strtoupper($E_Name).'|Page {PAGENO} Of {nb}|{DATE d-m-Y}');
    $mpdf->WriteHTML($data);
    $mpdf->Output();
?>