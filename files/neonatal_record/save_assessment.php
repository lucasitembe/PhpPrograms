<?php
include('../includes/connection.php');
  if (isset($_POST['signature_nurse'])) {

    $weight = mysqli_real_escape_string($conn,trim($_POST['weight']));
    $length = mysqli_real_escape_string($conn,trim($_POST['length']));
    $head_circumference = mysqli_real_escape_string($conn,trim($_POST['head_circumference']));
    $temperature = mysqli_real_escape_string($conn,trim($_POST['temperature']));
    $heart_rate = mysqli_real_escape_string($conn,trim($_POST['heart_rate']));
    $respiration_rate = mysqli_real_escape_string($conn,trim($_POST['respiration_rate']));
    $date_and_urine = mysqli_real_escape_string($conn,trim($_POST['date_and_urine']));
    $Signature_urine = mysqli_real_escape_string($conn,trim($_POST['Signature_urine']));
    $date_and_time_meconium = mysqli_real_escape_string($conn,trim($_POST['date_and_time_meconium']));
    $signature_meconium = mysqli_real_escape_string($conn,trim($_POST['signature_meconium']));
    $normal_shape_head = mysqli_real_escape_string($conn,trim($_POST['normal_shape_head']));
    $asymmetrical_shape_head = mysqli_real_escape_string($conn,trim($_POST['asymmetrical_shape_head']));
    $caput_succedanem = mysqli_real_escape_string($conn,trim($_POST['caput_succedanem']));
    $cephalohaematoma = mysqli_real_escape_string($conn,trim($_POST['cephalohaematoma']));
    $normal_fontanels = mysqli_real_escape_string($conn,trim($_POST['normal_fontanels']));
    $sunken_fontanels = mysqli_real_escape_string($conn,trim($_POST['sunken_fontanels']));
    $bulging = mysqli_real_escape_string($conn,trim($_POST['bulging']));
    $normal_suture = mysqli_real_escape_string($conn,trim($_POST['normal_suture']));
    $mouldng = mysqli_real_escape_string($conn,trim($_POST['mouldng']));
    $no_skin = mysqli_real_escape_string($conn,trim($_POST['no_skin']));
    $yes_skin = mysqli_real_escape_string($conn,trim($_POST['yes_skin']));
    $symetrical_shape_face = mysqli_real_escape_string($conn,trim($_POST['symetrical_shape_face']));
    $asymetric_shape_face = mysqli_real_escape_string($conn,trim($_POST['asymetric_shape_face']));
    $no_eye  = mysqli_real_escape_string($conn,trim($_POST['no_eye']));
    $yes_eye = mysqli_real_escape_string($conn,trim($_POST['yes_eye']));
    $no_nose = mysqli_real_escape_string($conn,trim($_POST['no_nose']));
    $yes_nose = mysqli_real_escape_string($conn,trim($_POST['yes_nose']));
    $normal_mouth = mysqli_real_escape_string($conn,trim($_POST['normal_mouth']));
    $cleft_palete = mysqli_real_escape_string($conn,trim($_POST['cleft_palete']));
    $symetric = mysqli_real_escape_string($conn,trim($_POST['symetric_shape_thorax']));
    $asymetric = mysqli_real_escape_string($conn,trim($_POST['asymetric_shape_thorax']));
    $rib_retraction = mysqli_real_escape_string($conn,trim($_POST['rib_retraction']));
    $stern_retraction = mysqli_real_escape_string($conn,trim($_POST['stern_retraction']));
    $normal_respiration = mysqli_real_escape_string($conn,trim($_POST['normal_respiration']));
    $difficult = mysqli_real_escape_string($conn,trim($_POST['difficult']));
    $strider = mysqli_real_escape_string($conn,trim($_POST['strider']));
    $grunting = mysqli_real_escape_string($conn,trim($_POST['grunting']));
    $normal = mysqli_real_escape_string($conn,trim($_POST['normal_shape_abdomen']));
    $distened = mysqli_real_escape_string($conn,trim($_POST['distened']));
    $sunken = mysqli_real_escape_string($conn,trim($_POST['sunken']));
    $visible_peristalisis = mysqli_real_escape_string($conn,trim($_POST['visible_peristalisis']));
    $normal_umblicord = mysqli_real_escape_string($conn,trim($_POST['normal_umblicord']));
    $bleeding = mysqli_real_escape_string($conn,trim($_POST['bleeding']));
    $normal_upper_limb = mysqli_real_escape_string($conn,trim($_POST['normal_upper_limb']));
    $abdomal_upper_limb = mysqli_real_escape_string($conn,trim($_POST['abdomal_upper_limb']));
    $normal_lower_limb = mysqli_real_escape_string($conn,trim($_POST['normal_lower_limb']));
    $abdomal_lower_limb = mysqli_real_escape_string($conn,trim($_POST['abdomal_lower_limb']));
    $normal_spinal_column = mysqli_real_escape_string($conn,trim($_POST['normal_spinal_column']));
    $spina_benifida = mysqli_real_escape_string($conn,trim($_POST['spina_benifida']));
    $meningocele = mysqli_real_escape_string($conn,trim($_POST['meningocele']));
    $present = mysqli_real_escape_string($conn,trim($_POST['present_unus']));
    $absent = mysqli_real_escape_string($conn,trim($_POST['absent_unus']));
    $yes_perforated =mysqli_real_escape_string($conn,trim($_POST['yes_perforated']));
    $no_perforated = mysqli_real_escape_string($conn,trim($_POST['no_perforated']));
    $normal_boy_genital = mysqli_real_escape_string($conn,trim($_POST['normal_boy_genital']));
    $abdomal_boy_genetal = mysqli_real_escape_string($conn,trim($_POST['abdomal_boy_genetal']));
    $normal_girl_genital = mysqli_real_escape_string($conn,trim($_POST['normal_girl_genital']));
    $abdomal_girl_genital = mysqli_real_escape_string($conn,trim($_POST['abdomal_girl_genital']));
    $yes_grip_reflex = mysqli_real_escape_string($conn,trim($_POST['yes_grip_reflex']));
    $no_grip_reflex = mysqli_real_escape_string($conn,trim($_POST['no_grip_reflex']));
    $yes_moro_reflex = mysqli_real_escape_string($conn,trim($_POST['yes_moro_reflex']));
    $no_moro_reflex = mysqli_real_escape_string($conn,trim($_POST['no_moro_reflex']));
    $yes_suck_refelex = mysqli_real_escape_string($conn,trim($_POST['yes_suck_refelex']));
    $no_suck_reflex = mysqli_real_escape_string($conn,trim($_POST['no_suck_reflex']));
    $yes_muscle_tone =mysqli_real_escape_string($conn,trim($_POST['yes_muscle_tone']));

    $no_muscle_tone = mysqli_real_escape_string($conn,trim($_POST['no_muscle_tone']));
    $normal_colour_skin = mysqli_real_escape_string($conn,trim($_POST['normal_colour_skin']));
    $pale = mysqli_real_escape_string($conn,trim($_POST['pale']));
    $Juandice = mysqli_real_escape_string($conn,trim($_POST['Juandice']));
    $cynosis = mysqli_real_escape_string($conn,trim($_POST['cynosis']));
    $intact = mysqli_real_escape_string($conn,trim($_POST['intact']));
    $rash = mysqli_real_escape_string($conn,trim($_POST['rash']));
    $broken = mysqli_real_escape_string($conn,trim($_POST['broken']));
    $bruised = mysqli_real_escape_string($conn,trim($_POST['bruised']));
    $assessed_by = mysqli_real_escape_string($conn,trim($_POST['assessed_by']));
    $data_time_assessed = mysqli_real_escape_string($conn,trim($_POST['data_time_assessed']));
    $signature_mother = mysqli_real_escape_string($conn,trim($_POST['signature_mother']));
    $signature_nurse = mysqli_real_escape_string($conn,trim($_POST['signature_nurse']));
    $date_time_mother = mysqli_real_escape_string($conn,trim($_POST['date_time_mother']));
    $date_time_nurse = mysqli_real_escape_string($conn,trim($_POST['date_time_nurse']));


    $insert_asssessment = "INSERT INTO tbl_assessment1(weight,length,head_circumference,temperature,heart_rate,
      respiration_rate,date_and_urine,Signature_urine,date_and_time_meconium,
    signature_meconium,normal_shape_head,asymmetrical_shape_head,caput_succedanem,cephalohaematoma,normal_fontanels,sunken_fontanels,bulging,normal_suture,
  mouldng,no_skin,yes_skin,symetrical_shape_face,asymetric_shape_face,no_eye,
yes_eye,no_nose,yes_nose,normal_mouth,cleft_palete,symetric_shape_thorax,
asymetric_shape_thorax,rib_retraction,stern_retraction,normal_respiration,
difficult,strider,grunting,normal_shape_abdomen,distened,sunken,
visible_peristalisis,normal_umblicord,bleeding,normal_upper_limb,
abdomal_upper_limb,normal_lower_limb,abdomal_lower_limb,normal_spinal_column,
spina_benifida,meningocele,present_unus,absent_unus,yes_perforated,
no_perforated,normal_boy_genital,abdomal_boy_genetal,normal_girl_genital,
abdomal_girl_genital,yes_grip_reflex,no_grip_reflex,yes_moro_reflex,
no_moro_reflex,yes_suck_refelex,no_suck_reflex,yes_muscle_tone,no_muscle_tone,
normal_colour_skin,pale,Juandice,cynosis,intact,rash,broken,bruised,assessed_by,
data_time_assessed,signature_mother,signature_nurse,date_time_mother,
date_time_nurse) VALUES('$weight','$length','$head_circumference','$temperature','$heart_rate',
'$respiration_rate','$date_and_urine','$Signature_urine',
'$date_and_time_meconium','$signature_meconium','$normal_shape_head',
'$asymmetrical_shape_head','$caput_succedanem','$cephalohaematoma',
'$normal_fontanels','$sunken_fontanels','$bulging','$normal_suture','$mouldng',
'$no_skin','$yes_skin','$symetrical_shape_face','$asymetric_shape_face',
'$no_eye','$yes_eye','$no_nose','$yes_nose','$normal_mouth','$cleft_palete',
'$symetric','$asymetric','$rib_retraction','$stern_retraction',
'$normal_respiration','$difficult','$strider','$grunting','$normal','$distened',
'$sunken','$visible_peristalisis','$normal_umblicord','$bleeding',
'$normal_upper_limb','$abdomal_upper_limb','$normal_lower_limb',
'$abdomal_lower_limb','$normal_spinal_column','$spina_benifida','$meningocele',
'$present','$absent','$yes_perforated','$no_perforated','$normal_boy_genital',
'$abdomal_boy_genetal','$normal_girl_genital','$abdomal_girl_genital',
'$yes_grip_reflex','$no_grip_reflex','$yes_moro_reflex','$no_moro_reflex',
'$yes_suck_refelex','$no_suck_reflex','$yes_muscle_tone','$no_muscle_tone',
'$normal_colour_skin','$pale','$Juandice','$cynosis','$intact','$rash','$broken','$bruised','$assessed_by','$data_time_assessed','$signature_mother',
'$signature_nurse','$date_time_mother','$date_time_nurse')";


  if ($result = mysqli_query($conn,$insert_asssessment)) {
    echo "data saved";
  }else {
    echo mysqli_error($conn);
  }


    }
 ?>
