<?php
    error_reporting(!E_NOTICE);
    include("./includes/header.php");
    include("./includes/connection.php");
    if (!isset($_SESSION['userinfo'])) {
        @session_destroy();
        header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if (isset($_SESSION['userinfo'])) {
//        if (isset($_SESSION['userinfo']['Admission_Works'])) {
//            if ($_SESSION['userinfo']['Admission_Works'] != 'yes') {
//                header("Location: ./index.php?InvalidPrivilege=yes");
//            }
//        } else {
//            header("Location: ./index.php?InvalidPrivilege=yes");
//        }
    } else {
        @session_destroy();
        header("Location: ../index.php?InvalidPrivilege=yes");
    }

    if(isset($_GET['Registration_ID'])){
        $Registration_ID = $_GET['Registration_ID'];
    }else{
        $Registration_ID = 0;
    }

    if(isset($_GET['Admision_ID'])){
        $Admision_ID = $_GET['Admision_ID'];
    }else{
        $Admision_ID = 0;
    }

    if(isset($_GET['consultation_ID'])){
        $consultation_ID = $_GET['consultation_ID'];
    }else{
        $consultation_ID = 0;
    }

    echo "<a href='labour_atenal_neonatal_record.php?patient_id=" .$Registration_ID. "&admision_id=".$Admision_ID."&consultation_id=".$consultation_ID."&NurseCommunication=NurseCommunicationThisPage' class='art-button-green'>BACK</a>";

    //Get Patient Details
    $select_patient_details = mysqli_query($conn,"select Member_Number, Patient_Name, Registration_ID, Gender, Guarantor_Name, Date_Of_Birth
                                            from tbl_patient_registration pr, tbl_sponsor sp where
                                            pr.Registration_ID = '$Registration_ID' and sp.Sponsor_ID = pr.Sponsor_ID") or die(mysqli_error($conn));
    $no = mysqli_num_rows($select_patient_details);
    if ($no > 0) {
        while ($row = mysqli_fetch_array($select_patient_details)) {
            $Member_Number = $row['Member_Number'];
            $Patient_Name = $row['Patient_Name'];
            $Registration_ID = $row['Registration_ID'];
            $Gender = $row['Gender'];
            $Sponsor = $row['Guarantor_Name'];
            $DOB = $row['Date_Of_Birth'];
        }
    } else {
        $Member_Number = '';
        $Patient_Name = '';
        $Registration_ID = '';
        $Gender = '';
        $Sponsor = '';
        $DOB = '';
    }


    $baby_care_id = 0;

if (isset($_GET['baby'])) {
    $baby = $_GET['baby'];
    $select = mysqli_query($conn,"select * from tbl_baby_care where parent_id = '$Registration_ID' AND DATE(saved_date)=CURDATE() AND babywho = '$baby'") or die(mysqli_error($conn));
    $infos = mysqli_fetch_array($select);
    $baby_care_id = $infos['baby_care_id'];
    $parent_id = $infos['parent_id'];
    $babywho = $infos['babywho'];
    $dob = $infos['dob'];
    $apgarscore = $infos['apgarscore'];
    $sex = $infos['sex'];


    if ($baby == 'babyone'){
        $babyone = 'selected';
    }else if ($baby == 'babytwo'){
        $babytwo = 'selected';
    }else if ($baby == 'babythree'){
        $babythree = 'selected';
    }else if ($baby == 'babyfour'){
        $babyfour = 'selected';
    }else if ($baby == 'babyfive'){
        $babyfive = 'selected';
    }else if ($baby == 'babysix'){
        $babysix = 'selected';
    }

    if ($sex == 'male'){
        $male = 'selected';
    }else if ($sex == 'female'){
        $female = 'selected';
    }

    $weight = $infos['weight'];
    $length = $infos['length'];
    $type_of_delivery = $infos['type_of_delivery'];


    if ($type_of_delivery == 'virginal'){
        $virginal = 'selected';
    }else if ($type_of_delivery == 'cesarean'){
        $cesarean = 'selected';
    }else if ($type_of_delivery == 'virginalaftercesarean'){
        $virginalaftercesarean = 'selected';
    }else if ($type_of_delivery == 'vacuumextraction'){
        $vacuumextraction = 'selected';
    }else if ($type_of_delivery == 'forceps'){
        $forceps = 'selected';
    }

    $head_circum_chest = $infos['head_circum_chest'];
    $drugs_given = $infos['drugs_given'];

    $maturity = $infos['maturity'];
    if($maturity == 'matured')
    {
      $matured = 'selected';
    }else if($maturity == 'pre-matured')
    {
      $prematured = 'selected';
    }

    $abnormality = $infos['abnormality'];

    //Maternity

    $checkMaternity = mysqli_query($conn,"SELECT  prom, hypertension, multpvexams, preeclampsia, resuscitation, rh_ve, maternal_std, spinal_or, maternal_hiv, general, diabetes, anaesthesia FROM tbl_maternity_risk_factor WHERE baby_care_id='$baby_care_id' ") or die(mysqli_error($conn));

    $maternityInfos = mysqli_fetch_array($checkMaternity);

    $prom = ($maternityInfos['prom'] == 'yes') ? 'checked' : '';
    $hypertension = ($maternityInfos['hypertension'] == 'yes') ? 'checked' : '';
    $multpvexams = ($maternityInfos['multpvexams'] == 'yes') ? 'checked' : '';
    $preeclampsia = ($maternityInfos['preeclampsia'] == 'yes') ? 'checked' : '';
    $maternal_std = ($maternityInfos['maternal_std'] == 'yes') ? 'checked' : '';
    $rh_ve = ($maternityInfos['rh_ve'] == 'yes') ? 'checked' : '';
    $resuscitation = ($maternityInfos['resuscitation'] == 'yes') ? 'checked' : '';
    $diabetes = ($maternityInfos['diabetes'] == 'yes') ? 'checked' : '';
    $anaesthesia = ($maternityInfos['anaesthesia'] == 'yes') ? 'checked' : '';
    $spinal_or = ($maternityInfos['spinal_or'] == 'yes') ? 'checked' : '';
    $maternal_hiv = ($maternityInfos['maternal_hiv'] == 'yes') ? 'checked' : '';
    $general = ($maternityInfos['general'] == 'yes') ? 'checked' : '';

    //examinition

    $checkExams = mysqli_query($conn,"SELECT head_eyes_nose_ears_mouth, neck, chest, abdomen, cord, genital, limbs, eyes, hips, anus, neonatal_relexes, feeding, urine, stool, mouth, comments FROM tbl_baby_care_exam WHERE baby_care_id='$baby_care_id' ") or die(mysqli_error($conn));

    $examInfor = mysqli_fetch_array($checkExams);

    $head_eyes_nose_ears_mouth = $examInfor['head_eyes_nose_ears_mouth'];
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

    //health

    $checkHealth = mysqli_query($conn,"SELECT Employee_Name, date_saved, room_temp, rect_temp, bowel, urine, vomiting, feed, treatment, remarks
                                       FROM tbl_baby_care_health eh
                                       JOIN tbl_employee e ON e.Employee_ID = eh.employee_id
                                       WHERE baby_care_id = '$baby_care_id'") or die(mysqli_error($conn));
    $h_num = mysqli_num_rows($checkHealth);
    $healthInfos = mysqli_fetch_array($checkHealth);

    $date_saved = $healthInfos['date_saved'];
    $room_temp = $healthInfos['room_temp'];
    $rect_temp = $healthInfos['rect_temp'];
    $bowel = $healthInfos['bowel'];
    $urine = $healthInfos['urine'];
    $vomiting = $healthInfos['vomiting'];
    $feed = $healthInfos['feed'];
    $treatment = $healthInfos['treatment'];
    $remarks = $healthInfos['remarks'];
}

//posting

if (isset($_POST['send_data']) && $_POST['send_data'] == 'true') {
    $querystring = $_POST['querystring'];

    //tbl_baby_care
    $parent_id = $_POST['parent_id'];
    $employee_id = $_SESSION['userinfo']['Employee_ID'];
    $consultation_id = $_GET['consultation_ID']; //$_POST['consultation_id'];
    $babywho = $_POST['babywho'];
    $dob = $_POST['dob'];
    $apgarscore = $_POST['apgarscore'];
    $sex = $_POST['sex'];
    $weight = $_POST['weight'];
    $length = $_POST['length'];
    $type_of_delivery = $_POST['type_of_delivery'];
    $head_circum_chest = $_POST['head_circum_chest'];
    $drugs_given= $_POST['drugs_given'];
    $maturity = $_POST['maturity'];
    $abnormality = $_POST['abnormality'];

    $check = mysqli_query($conn,"SELECT baby_care_id
                                 FROM tbl_baby_care
                                 WHERE parent_id='$parent_id'
                                 AND consultation_id='$consultation_id'
                                 AND babywho='$babywho' ") or die(mysqli_error($conn));

    if (mysqli_num_rows($check) > 0) {
        $babyinfo = $baby_care_id = mysqli_fetch_assoc($check);
        session_start();
        // $_SESSION['babyinfo'] = $babyinfo;
        $baby_care_id = mysqli_fetch_assoc($check)['baby_care_id'];
        $sqlBabycare = "UPDATE  tbl_baby_care
                        SET parent_id='$parent_id', employee_id='$employee_id', consultation_id='$consultation_id',
                        babywho='$babywho', dob='$dob', apgarscore= '$apgarscore', sex='$sex', weight='$weight',
                        length='$length', type_of_delivery='$type_of_delivery', head_circum_chest='$head_circum_chest',
                        drugs_given = '$drugs_given',maturity='$maturity',abnormality='$abnormality',saved_date=NOW()
                        WHERE parent_id='$parent_id' AND consultation_id='$consultation_id' AND babywho='$babywho'";
    } else {
        $Registration_ID = $_GET['Registration_ID'];
        $select_mother_details = mysqli_query($conn,"SELECT *
                                                     FROM tbl_patient_registration
                                                     WHERE Registration_ID = '$Registration_ID' LIMIT  1");

        while($get_details = mysqli_fetch_assoc($select_mother_details)) {
            $get_mother_country = $get_details['Country'];
            $get_mother_region = $get_details['Region'];
            $get_mother_district = $get_details['District'];
            $get_mother_ward = $get_details['ward'];
            $get_mother_sponsor_id = $get_details['Sponsor_ID'];
            $get_mother_Registration_Date_And_Time = $get_details['Sponsor_Id'];
        }

        $select_result = mysqli_query($conn,"SELECT Sponsor_ID,Guarantor_Name
                                             FROM  tbl_sponsor
                                             WHERE cover_child = 'yes'
                                             AND Guarantor_Name = '$Sponsor' LIMIT 1");

        $select_result_inner = mysqli_query($conn,"SELECT *
                                                   FROM tbl_sponsor
                                                   WHERE Guarantor_Name = 'CASH'");

        if(mysqli_num_rows($select_result) > 0) :
            while($sponsor_result = mysqli_fetch_assoc($select_result)) :
                $sponsor_name = $sponsor_result['Guarantor_Name'];
                $sponsor_id = $sponsor_result['Sponsor_ID'];
            endwhile;
        else:
            if(mysqli_num_rows($select_result_inner) > 0) :
                while($sponsor_result = mysqli_fetch_assoc($select_result_inner)) :
                    $sponsor_name = $sponsor_result['Guarantor_Name'];
                    $sponsor_id = $sponsor_result['Sponsor_ID'];
                endwhile;
            endif;
        endif;

        $child_registration_name = $Patient_Name."(".$Registration_ID.")";
        $register_baby = "INSERT INTO tbl_patient_registration (
                          Patient_Name,Date_Of_Birth,Gender,Country,District,Ward,Sponsor_Id,Registration_Date_And_Time)
                          VALUES ('$child_registration_name',NOW(),'$sex','$get_mother_country','$get_mother_district',
                          '$get_mother_ward','$sponsor_id','$get_mother_Registration_Date_And_Time')";

        $sqlBabycare = "INSERT INTO tbl_baby_care (
                        parent_id, employee_id, consultation_id, babywho, dob, apgarscore, sex, weight, length,
                        type_of_delivery, head_circum_chest,drugs_given,maturity,abnormality,saved_date)
                        VALUES ( '$parent_id', '$employee_id', '$consultation_id', '$babywho', '$dob', '$apgarscore',
                        '$sex', '$weight', '$length', '$type_of_delivery', '$head_circum_chest','$drugs_given','$maturity','$abnormality', NOW());
          ";
    }

    $resultBabyCare = mysqli_query($conn,$sqlBabycare)or die(mysqli_error($conn));
    echo '<script>
    alert("Saved successifuly");
    window.location="nursecommunication_babycarechart.php?Registration_ID=' . $_GET['Registration_ID'] . '&Admision_ID=' . $_GET['Admision_ID'] . '&consultation_ID=' . $_GET['consultation_ID'] . '&baby=' . $babywho . '";
  </script>';
    mysqli_query($conn,$register_baby)or die(mysqli_error($conn));


    $check = mysqli_query($conn,"SELECT baby_care_id
                                 FROM tbl_baby_care
                                 WHERE parent_id='$parent_id'
                                 AND consultation_id='$consultation_id'
                                 AND babywho='$babywho' ") or die(mysqli_error($conn));

    $baby_care_id = mysqli_fetch_assoc($check)['baby_care_id'];

    $prom = isset($_POST['prom']) ? $_POST['prom'] : 'no';
    $hypertension = isset($_POST['hypertension']) ? $_POST['hypertension'] : 'no';
    $multpvexams = isset($_POST['multpvexams']) ? $_POST['multpvexams'] : 'no';
    $preeclampsia = isset($_POST['preeclampsia']) ? $_POST['preeclampsia'] : 'no';
    $maternal_std = isset($_POST['maternal_std']) ? $_POST['maternal_std'] : 'no';
    $rh_ve = isset($_POST['rh_ve']) ? $_POST['rh_ve'] : 'no';
    $resuscitation = isset($_POST['resuscitation']) ? $_POST['resuscitation'] : 'no';
    $diabetes = isset($_POST['diabetes']) ? $_POST['diabetes'] : 'no';
    $anaesthesia = isset($_POST['anaesthesia']) ? $_POST['anaesthesia'] : 'no';
    $spinal_or = isset($_POST['spinal_or']) ? $_POST['spinal_or'] : 'no';
    $maternal_hiv = isset($_POST['maternal_hiv']) ? $_POST['maternal_hiv'] : 'no';
    $type_of_delivery = isset($_POST['type_of_delivery']) ? $_POST['type_of_delivery'] : 'no';
    $general = isset($_POST['general']) ? $_POST['general'] : 'no';

    $checkMaternity = mysqli_query($conn,"SELECT baby_care_id FROM tbl_maternity_risk_factor WHERE baby_care_id='$baby_care_id' ") or die(mysqli_error($conn));

    if (mysqli_num_rows($checkMaternity) > 0) {
        $sqlMaternity = "UPDATE tbl_maternity_risk_factor SET
                         prom='$prom', hypertension='$hypertension',
                         multpvexams='$multpvexams', preeclampsia='$preeclampsia',
                         resuscitation='$resuscitation', rh_ve='$rh_ve',
                         maternal_std='$maternal_std', spinal_or='$spinal_or',
                         maternal_hiv='$maternal_hiv', general='$general',
                         diabetes='$diabetes', anaesthesia='$anaesthesia'
                         WHERE baby_care_id='$baby_care_id' ";
    } else {
        $sqlMaternity = "INSERT INTO tbl_maternity_risk_factor (
                         baby_care_id, prom, hypertension, multpvexams, preeclampsia, resuscitation,
                         rh_ve, maternal_std, spinal_or, maternal_hiv, general, diabetes, anaesthesia)
                         VALUES ($baby_care_id, '$prom', '$hypertension','$multpvexams', '$preeclampsia',
                         '$resuscitation', '$rh_ve', '$maternal_std', '$spinal_or', '$maternal_hiv', '$general',
                         '$diabetes', '$anaesthesia');
";
    }

    $resultMaterniy = mysqli_query($conn,$sqlMaternity) or die(mysqli_error($conn));
    //end
    //tbl_baby_care_exam
    $head_eyes_nose_ears_mouth = $_POST['head_eyes_nose_ears_mouth'];
    $neck = $_POST['neck'];
    $chest = $_POST['chest'];
    $abdomen = $_POST['abdomen'];
    $cord = $_POST['cord'];
    $genital = $_POST['genital'];
    $limbs = $_POST['limbs'];
    $eyes = $_POST['eyes'];
    $hips = $_POST['hips'];
    $anus = $_POST['anus'];
    $neonatal_relexes = $_POST['neonatal_relexes'];
    $feeding = $_POST['feeding'];
    $urine = $_POST['urine'];
    $stool = $_POST['stool'];
    $mouth = $_POST['mouth'];
    $feeding = $_POST['feeding'];
    $comments = $_POST['comments'];


    $checkExams = mysqli_query($conn,"SELECT baby_care_id FROM tbl_baby_care_exam WHERE baby_care_id='$baby_care_id' ") or die(mysqli_error($conn));

    if (mysqli_num_rows($checkExams) > 0) {
        $sqlExams = "UPDATE tbl_baby_care_exam SET head_eyes_nose_ears_mouth='$head_eyes_nose_ears_mouth', neck='$neck', chest='$chest', abdomen='$abdomen', cord='$cord', genital='$genital', limbs='$limbs', eyes='$eyes', hips='$hips', anus='$anus', neonatal_relexes='$neonatal_relexes', feeding='$feeding', urine='$urine', stool='$stool', mouth='$mouth', comments='$comments'";
    } else {
        $sqlExams = "INSERT INTO tbl_baby_care_exam (
                     baby_care_id, head_eyes_nose_ears_mouth, neck, chest, abdomen, cord, genital, limbs,
                     eyes, hips, anus, neonatal_relexes, feeding, urine, stool, mouth, comments)
                     VALUES ($baby_care_id, '$head_eyes_nose_ears_mouth', '$neck', '$chest', '$abdomen',
                     '$cord', '$genital', '$limbs', '$eyes', '$hips', '$anus', '$neonatal_relexes', '$feeding',
                     '$urine', '$stool', '$mouth', '$comments');
";
    }

    $resultExams = mysqli_query($conn,$sqlExams) or die(mysqli_error($conn));
    $room_temp = $_POST['room_temp'];
    $rect_temp = $_POST['rect_temp'];
    $bowel = $_POST['bowel'];
    $urine = $_POST['urine'];
    $vomiting = $_POST['vomiting'];
    $feed = $_POST['feed'];
    $treatment = $_POST['treatment'];
    $remarks = $_POST['remarks'];

    $sqlHealth = "INSERT INTO tbl_baby_care_health (
                  baby_care_id, employee_id, date_saved,
                  room_temp, rect_temp, bowel, urine,
                  vomiting, feed, treatment, remarks)
                  VALUES ('$baby_care_id', '$employee_id', NOW(),
                  '$room_temp', '$rect_temp', '$bowel', '$urine',
                  '$vomiting', '$feed', '$treatment', '$remarks')";
    $resultHealth = mysqli_query($conn,$sqlHealth) or die(mysqli_error($conn));


    echo '<script>
            alert("Saved successifuly");
            window.location="nursecommunication_babycarechart.php?Registration_ID='.$_GET['Registration_ID'] . '&Admision_ID=' . $_GET['Admision_ID'] . '&consultation_ID=' . $_GET['consultation_ID'] . '&baby=' . $babywho . '";
          </script>';

    //end
}
?>
<style>
    table,tr,td{
        border-collapse:collapse !important;
        border:none !important;
    }
    tr:hover{
        background-color:#eeeeee;
        cursor:pointer;
    }
</style>
<style>
    td.pr{
        text-align: right
    }

</style>
<style>
        input[type="checkbox"]{
            width: 30px;
            height: 30px;
            cursor: pointer;
    }
    </style>
<br/><br/>
<fieldset>
    <legend><b>BABY CARE CHART</b></legend>
    <form action="nursecommunication_babycarechart.php?Registration_ID=<?php echo $_GET['Registration_ID'] ?>&Admision_ID=<?php echo $_GET['Admision_ID'] ?>&consultation_ID=<?php echo $_GET['consultation_ID'] ?>"  method="post">
        <table width="100%">
            <tr>
                <td class="pr">Baby of</td><td><input type="text" readonly="readonly" value="<?php echo $Patient_Name ?>"></td>
                <td class="pr">Registration #</td><td><input type="text" readonly="readonly" value="<?php echo $Registration_ID ?>"></td>
                <td class="pr">Patient Gender</td><td><input type="text" readonly="readonly" value="<?php echo $Gender ?>"></td>
                <td class="pr">Sponsor Name</td>
                <td>
                    <?php
                        $select_result = mysqli_query($conn,"SELECT Sponsor_ID,Guarantor_Name FROM  tbl_sponsor WHERE cover_child = 'yes' AND Guarantor_Name = '$Sponsor' LIMIT 1");
                        $select_result_inner = mysqli_query($conn,"SELECT * FROM tbl_sponsor WHERE Guarantor_Name = 'CASH'");
                        if(mysqli_num_rows($select_result) > 0) :
                            while($sponsor_result = mysqli_fetch_assoc($select_result)) :
                                $sponsor_name = $sponsor_result['Guarantor_Name'];
                                $sponsor_id = $sponsor_result['Sponsor_ID'];
                            endwhile;
                        else:
                            if(mysqli_num_rows($select_result_inner) > 0) :
                                while($sponsor_result = mysqli_fetch_assoc($select_result_inner)) :
                                    $sponsor_name = $sponsor_result['Guarantor_Name'];
                                    $sponsor_id = $sponsor_result['Sponsor_ID'];
                                endwhile;
                            endif;
                        endif;
                    ?>
                    <input type="text" readonly="readonly" value="<?=$sponsor_name?>">
                </td>
            </tr>
            <tr>
                <td class="pr">Baby Number</td>
                <td>
                    <select name="babywho" required id="babywho" style="font-size: large " onchange="checkIfExits(this.value)">
                        <option value="">Select</option>
                        <option value="babyone" <?php echo $babyone ?>>Baby One</option>
                        <option value="babytwo" <?php echo $babytwo ?>>Baby Two</option>
                        <option value="babythree" <?php echo $babythree ?>>Baby Three</option>
                        <option value="babyfour" <?php echo $babyfour ?>>Baby Four</option>
                        <option value="babyfive" <?php echo $babyfive ?>>Baby Five</option>
                        <option value="babysix" <?php echo $babysix ?>>Baby Six</option>
                    </select>
                </td>
                <td class="pr">Apggar Score</td><td><input required name="apgarscore" value="<?php echo $apgarscore ?>" id="apgarscore" type="text" autocomplete="off"></td>
                <td class="pr">Length</td><td><input required name="length"  value="<?php echo $length ?>" id="length" type="text" autocomplete="off"></td>
                <td class="pr">Birth Date & Time </td>
                <td><input type="text" name="dob" id="dob" value="<?php echo $dob; ?>" required placeholder = "Birth Date & Time" autocomplete="off"></td>
            </tr>
            <tr>
                <td class="pr">Sex:</td><td><select required name="sex" id="sex"   style="padding:4px;font-size:18px;width: 100%"><option value="">Select</option><option value="male" <?php echo $male ?>>Male</option><option value="female" <?php echo $female ?>>Female</option></select></td>
                <td class="pr">Type of Delivery:</td>
                <td>
                    <select name="type_of_delivery" required id="type_of_delivery" style="padding:4px; width: 100%;font-size:18px;font-weight: lighter ">
                        <option value="">Select</option>
                        <option value="virginal" <?php echo $virginal ?>>Virginal Delivery</option>
                        <option value="cesarean" <?php echo $cesarean ?>>Cesarean Delivery</option>
                        <option value="virginalaftercesarean" <?php echo $virginalaftercesarean ?>>Virginal Birth After Cesarean</option>
                        <option value="vacuumextraction" <?php echo $vacuumextraction ?>>Vacuum Extraction</option>
                        <option value="forceps" <?php echo $forceps ?>>Forceps Delivery</option>
                    </select>
                </td>
                <td class="pr">Weight:</td><td><input required name="weight" id="weight" value="<?php echo $weight ?>" type="text" autocomplete="off"></td>
                <td class="pr">Head Circum/Chest:</td><td><input name="head_circum_chest" value="<?php echo $head_circum_chest ?>" id=""head_circum_chest type="text" autocomplete="off"></td>
            </tr>
            <tr>
              <!-- Done by Abdul -->
              <td class="pr">Drugs Given:</td><td>
                <textarea name="drugs_given" id="drugs_given" rows="4" cols="10"  autocomplete="off"><?php echo $drugs_given; ?></textarea>
              </td>
              <td class="pr">Maturity:</td>
              <td>
                  <select name="maturity" required id="maturity" style="padding:4px; width: 100%;font-size:18px;font-weight: lighter ">
                      <option value="">Select</option>
                      <option value="matured" <?php echo $matured; ?>>Matured</option>
                      <option value="pre-matured" <?php echo $prematured; ?>>Pre-Matured</option>

                  </select>
              </td>
              <td class="pr">Abnormality:</td><td>
                <textarea name="abnormality" id="abnormality" rows="4" cols="10"  autocomplete="off"><?php echo $abnormality; ?></textarea>
              </td>

              <!-- End done by Abdul -->
            </tr>
        </table>
        <table width="100%">
            <tr>
                <td width="40%"><b>MARTENITY RISK FACTORS TO NEWBORN</b></td>
                <td style="text-align: right;"></td>
            </tr>
        </table><hr>
        <table width="100%">
            <tr>
                <td><input type="checkbox" name="prom" id="prom" <?php echo $prom ?> value="yes"><label for="prom">PROM</label></td>
                <td><input type="checkbox" name="multpvexams" <?php echo $multpvexams ?> id="multpvexams" value="yes"><label for="multpvexams">Multiple PV Exams</label></td>
                <td><input type="checkbox" name="resuscitation" <?php echo $resuscitation ?> id="resuscitation" value="yes"><label for="resuscitation">Resuscitation</label></td>
                <td><input type="checkbox" name="maternal_std" <?php echo $maternal_std ?> id="maternal_std" value="yes"><label for="maternal_std">Maternal STD</label></td>
                <td><input type="checkbox" name="maternal_hiv" <?php echo $maternal_hiv ?> id="maternal_hiv" value="yes"><label for="maternal_hiv">Maternal HIV</label></td>
                <td><input type="checkbox" name="diabetes" <?php echo $diabetes ?> id="diabetes" value="yes"><label for="diabetes">Diabetes</label></td>
            </tr>
            <tr>
                <td><input type="checkbox" name="hypertension" id="hypertension" <?php echo $hypertension ?>  value="yes"><label for="hypertension">Hypertension</label></td>
                <td><input type="checkbox" name="preeclampsia" id="preeclampsia" <?php echo $preeclampsia ?>  value="yes"><label for="preeclampsia">Pre-Eclampsia</label></td>
                <td><input type="checkbox" name="rh_ve" id="rh_ve" <?php echo $rh_ve ?>  value="yes"><label for="rh_ve">RH -ve</label></td>
                <td><input type="checkbox" name="spinal_or" <?php echo $spinal_or ?>  id="spinal_or" value="yes"><label for="spinal_or">Spinal or</label></td>
                <td><input type="checkbox" name="general" <?php echo $general ?>  id="general" value="yes"><label for="general">General</label></td>
                <td><input type="checkbox" name="anaesthesia" <?php echo $anaesthesia ?>  id="anaesthesia" value="yes"><label for="anaesthesia">Anaesthesia</label></td>
            </tr>
        </table>
        <hr>
        <table width="100%">
            <tr >
                <td class="pr" width="14%">head:Eyes,Nose,Ears,Mouth</td>
                <td><input name="head_eyes_nose_ears_mouth" value="<?php echo $head_eyes_nose_ears_mouth ?>" id="head_eyes_nose_ears_mouth" type="text" autocomplete="off"></td>
                <td class="pr">Cord</td>
                <td><input name="cord" id="cord"  value="<?php echo $cord ?>"  type="text" autocomplete="off"></td>
                <td class="pr">Anus</td>
                <td><input name="anus" id="anus"  value="<?php echo $anus ?>"  type="text" autocomplete="off"></td>
                <td class="pr">Mouth</td>
                <td><input name="mouth" id="mouth"  value="<?php echo $mouth ?>"  type="text" autocomplete="off"></td>
            </tr>
            <tr>
                <td class="pr">Genital</td>
                <td><input name="genital" id="genital"  value="<?php echo $genital ?>"  type="text" autocomplete="off"></td>
                <td class="pr">Neonatal Relexes</td>
                <td><input name="neonatal_relexes" id="neonatal_relexes"  value="<?php echo $neonatal_relexes ?>"  type="text" autocomplete="off"></td>
                <td class="pr">Feeding</td>
                <td><input name="feeding" id="feeding"  value="<?php echo $feeding ?>"  type="text" autocomplete="off"></td>
                <td class="pr">comments</td>
                <td><textarea style="width: 100%; height: 20px;"name="comments" id="comments"> <?php echo $comments ?> </textarea></td>
            </tr>
            <tr>
                <td class="pr">Neck</td>
                <td><input name="neck" id="neck"  value="<?php echo $neck ?>"  type="text" autocomplete="off"></td>
                <td class="pr">Limbs</td>
                <td><input name="limbs" id="limbs"  value="<?php echo $limbs ?>"  type="text" autocomplete="off"></td>
                <td class="pr">Abdomen</td>
                <td><input name="abdomen" id="abdomen"  value="<?php echo $abdomen ?>"  type="text" autocomplete="off"></td>
                <td class="pr">Hips</td>
                <td><input name="hips" id="hips"  value="<?php echo $hips ?>"  type="text" autocomplete="off"></td>
            </tr>
            <tr>
                <td class="pr">Chest</td>
                <td><input name="chest" id="chest"  value="<?php echo $chest ?>"  type="text" autocomplete="off"></td>
                <td class="pr">Eyes</td>
                <td><input name="eyes" id="eyes"  value="<?php echo $eyes ?>"  type="text" autocomplete="off"></td>
                <td class="pr">Urine</td>
                <td><input name="urine" id="urine"  value="<?php echo $urine ?>"  type="text" autocomplete="off"></td>
                <td class="pr">Stool</td>
                <td><input name="stool" id="stool"  value="<?php echo $stool ?>"  type="text" autocomplete="off"></td>
            </tr>
        </table><hr>
        <table width="100%">
          <tr>
            <td class="pr">Room Temp</td>
            <td><input type='text' name='room_temp'  id='room_temp' value="<?php echo $room_temp; ?>" autocomplete="off"></td>
            <td class="pr">Rect Temp</td>
            <td><input type='text' name='rect_temp' id='rect_temp' value="<?php echo $rect_temp; ?>" autocomplete="off"></td>
            <td class="pr">Bowel</td>
            <td><input type='text' name='bowel' id='bowel' value="<?php echo $bowel; ?>" autocomplete="off"></td>
            <td class="pr">Urine</td>
            <td><input type='text' name='urine' id='urine'value="<?php echo $urine; ?>"  autocomplete="off"></td>
        </tr>
        <tr>
            <td class="pr">Vomiting</td>
            <td><input type='text' name='vomiting' id='vomiting' value="<?php echo $vomiting; ?>" autocomplete="off"></td>
            <td class="pr">Feed</td>
            <td><input type='text' name='feed' id='feed' value="<?php echo $feed; ?>" autocomplete="off"></td>
            <td class="pr">Treatment</td>
            <td><input type='text' name='treatment' id='treatment' value="<?php echo $treatment; ?>" autocomplete="off"></td>
            <td class="pr">Remarks</td>
            <td><textarea name='remarks' rows="1" id='remarks'><?php echo $remarks; ?></textarea></td>
        </tr>
        </table><hr>
        <table width="100%">
            <tr>
                <td colspan="8" class="pr">
                    <?php if (empty($nav)) { ?>
                        <input type='submit' name='submit' id='submit' value='ADD / UPDATE DETAILS' class='art-button-green'>
                    <?php } ?>
                    <a href="babycarereport.php?Registration_ID=<?php echo $_GET['Registration_ID'] ?>&Admision_ID=<?php echo $_GET['Admision_ID'] ?>&consultation_ID=<?php echo $_GET['consultation_ID'] ?>&baby=<?php echo $_GET['baby'] ?>" class="art-button-green" target="_blank">PREVIEW DETAILS</a>
                </td>
            </tr>
        </table>
            <div>
                <div style='float: left;'>
                    <input type='hidden' name='send_data' value='true'/>
                    <input type='hidden' name='parent_id' value='<?php echo $_GET['Registration_ID'] ?>'/>
                    <input type='hidden' name='consultation_ID' value='<?php echo $_GET['consultation_ID'] ?>'/>
                    <input type='hidden' name='querystring' value='<?php echo $_SERVER['QUERY_STRING'] ?>'/>
                </div>
                <div style="float:  right"></div>
            </div>
    </fieldset>
</form>

<script>
    function checkIfExits(baby) {
        var querystring = '<?php echo $_SERVER['QUERY_STRING'] ?>';
        if (baby != '') {
            window.location = 'nursecommunication_babycarechart.php?Registration_ID=<?php echo $_GET['Registration_ID'] ?>&Admision_ID=<?php echo $_GET['Admision_ID'] ?>&consultation_ID=<?php echo $_GET['consultation_ID'] ?>&baby=' + baby;
        } else {
            window.location = 'nursecommunication_babycarechart.php?Registration_ID=<?php echo $_GET['Registration_ID'] ?>&Admision_ID=<?php echo $_GET['Admision_ID'] ?>&consultation_ID=<?php echo $_GET['consultation_ID'] ?>';
        }
    }
</script>

<script src="css/jquery.js"></script>
<script src="css/jquery.datetimepicker.js"></script>
<script>
    $('#dob').datetimepicker({
    dayOfWeekStart : 1,
    lang:'en',
    //startDate:    'now'
    });
    $('#dob').datetimepicker({value:'',step:01});
</script>
<?php

function cleanInput($input) {

    $search = array(
        '@<script[^>]*?>.*?</script>@si', // Strip out javascript
        '@<[\/\!]*?[^<>]*?>@si', // Strip out HTML tags
        '@<style[^>]*?>.*?</style>@siU', // Strip style tags properly
        '@<![\s\S]*?--[ \t\n\r]*>@'         // Strip multi-line comments
    );

    $output = preg_replace($search, '', $input);
    return $output;
}

function sanitize($input) {
    if (is_array($input)) {
        foreach ($input as $var => $val) {
            $output[$var] = sanitize($val);
        }
    } else {
        if (get_magic_quotes_gpc()) {
            $input = stripslashes($input);
        }
        $input = cleanInput($input);
        $output = mysqli_real_escape_string($conn,$input);
    }
    return $output;
}

include("./includes/footer.php");
?>
