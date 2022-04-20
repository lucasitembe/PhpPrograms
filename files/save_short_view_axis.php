<?php
    include("./includes/connection.php");
    $aorta = mysqli_real_escape_string($conn, $_POST['aorta']);
    $pulm_trunk = mysqli_real_escape_string($conn, $_POST['pulm_trunk']);
    $short_patient_id = mysqli_real_escape_string($conn, $_POST['short_patient_id']);
    $short_item_cache_list_id = mysqli_real_escape_string($conn, $_POST['short_item_cache_list_id']);
    $SPO2 = mysqli_real_escape_string($conn, $_POST['SPO2']);
    $PPR = mysqli_real_escape_string($conn, $_POST['PPR']);
    $WT = mysqli_real_escape_string($conn, $_POST['WT']);
    $Abdominal_situs = mysqli_real_escape_string($conn, $_POST['Abdominal_situs']);
    $SVC_RA = mysqli_real_escape_string($conn, $_POST['SVC_RA']);
    $IVC_RA = mysqli_real_escape_string($conn, $_POST['IVC_RA']);
    $pulmonary_drainage = mysqli_real_escape_string($conn, $_POST['pulmonary_drainage']);
    $Atrio_ventricular = mysqli_real_escape_string($conn, $_POST['Atrio_ventricular']);
    $avc_Remarks = mysqli_real_escape_string($conn, $_POST['avc_Remarks']);
    $Ventricular_arterial = mysqli_real_escape_string($conn, $_POST['Ventricular_arterial']);
    $Mitral_valve = mysqli_real_escape_string($conn, $_POST['Mitral_valve']);
    $vac_Remarks = mysqli_real_escape_string($conn, $_POST['vac_Remarks']);
    $Left_attrium = mysqli_real_escape_string($conn, $_POST['Left_attrium']);
    $la_Remarks = mysqli_real_escape_string($conn, $_POST['la_Remarks']);
    $tricuspid_valves = mysqli_real_escape_string($conn, $_POST['tricuspid_valves']);
    $Right_attrium = mysqli_real_escape_string($conn, $_POST['Right_attrium']);
    $ra_Remarks = mysqli_real_escape_string($conn, $_POST['ra_Remarks']);
    $mv_Remarks = mysqli_real_escape_string($conn, $_POST['mv_Remarks']);
    $tv_Remarks = mysqli_real_escape_string($conn, $_POST['tv_Remarks']);
    $Aortic_valve = mysqli_real_escape_string($conn, $_POST['Aortic_valve']);
    $aortic_Remarks = mysqli_real_escape_string($conn, $_POST['aortic_Remarks']);
    $pulmonary_valves = mysqli_real_escape_string($conn, $_POST['pulmonary_valves']);
    $pulmonary_Remarks = mysqli_real_escape_string($conn, $_POST['pulmonary_Remarks']);
    $lv_ventricles = mysqli_real_escape_string($conn, $_POST['lv_ventricles']);
    $lv_Remarks = mysqli_real_escape_string($conn, $_POST['lv_Remarks']);
    $rv_ventricles = mysqli_real_escape_string($conn, $_POST['rv_ventricles']);
    $rv_Remarks = mysqli_real_escape_string($conn, $_POST['rv_Remarks']);
    $IAS = mysqli_real_escape_string($conn, $_POST['IAS']);
    $IVS = mysqli_real_escape_string($conn, $_POST['IVS']);
    $IVS_Remarks = mysqli_real_escape_string($conn, $_POST['IVS_Remarks']);
    $PDA = mysqli_real_escape_string($conn, $_POST['PDA']);
    $Aortic_Arch = mysqli_real_escape_string($conn, $_POST['Aortic_Arch']);
    $LA_AO = mysqli_real_escape_string($conn, $_POST['LA_AO']);
    $LVPWD = mysqli_real_escape_string($conn, $_POST['LVPWD']);
    $Cardiac_position = mysqli_real_escape_string($conn, $_POST['Cardiac_position']);
    $p_recommendation = mysqli_real_escape_string($conn, $_POST['p_recommendation']);
    $p_final_impression = mysqli_real_escape_string($conn, $_POST['p_final_impression']);
    $others1 = mysqli_real_escape_string($conn, $_POST['others1']);

    
    $sql_save_result = mysqli_query($conn, "INSERT INTO tbl_paediatric_information (patient_item_cache_list_id, patient_id, SPO2, PPR, WT, Abdominal_situs, Cardiac_position, SVC_RA, IVC_RA, pulmonary_drainage, Atrio_ventricular, avc_Remarks, Left_attrium, la_Remarks, Right_attrium, ra_Remarks, Mitral_valve, mv_Remarks, tricuspid_valves, tv_Remarks, Aortic_valve, aortic_Remarks, pulmonary_Remarks, lv_ventricles, lv_Remarks, rv_ventricles, rv_Remarks, IAS, IVS, IVS_Remarks, PDA, Aortic_Arch, LA_AO, LVPWD, Created_at, status, others) VALUES ('$short_item_cache_list_id', '$short_patient_id', '$SPO2', '$PPR', '$WT', '$Abdominal_situs', '$Cardiac_position', '$SVC_RA', '$IVS_RA', '$pulmonary_drainage', '$Atrio_ventricular', '$avc_Remarks', '$Left_attrium', '$la_Remarks', '$Right_attrium', '$ra_Remarks', '$Mitral_valve', '$mv_Remarks', '$tricuspid_valves', '$tv_Remarks', '$Aortic_valve', '$aortic_Remarks', '$pulmonary_Remarks', '$lv_ventricles', '$lv_Remarks', '$rv_ventricles', '$rv_Remarks', '$IAS', '$IVS', '$IVS_Remarks', '$PDA', '$Aortic_Arch', '$LA_AO', '$LVPWD', NOW(), 'saved', '$others1')") or die(mysqli_error($conn));
    if ($sql_save_result) {
        echo "Information was Saved Successfully ";
    } 
    else {
        echo "Failed To Save Information";
    }
    mysqli_close($conn);
    ?>

