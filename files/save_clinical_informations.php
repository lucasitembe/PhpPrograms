<?php
    include("./includes/connection.php");
        $ABP = mysqli_real_escape_string($conn,$_POST['ABP']);
        $APR = mysqli_real_escape_string($conn,$_POST['APR']);
        $clinical_patient_id = mysqli_real_escape_string($conn,$_POST['clinical_patient_id']);
        $clinical_item_cache_list_id = mysqli_real_escape_string($conn,$_POST['clinical_item_cache_list_id']);
        $aortic = mysqli_real_escape_string($conn,$_POST['aortic']);
        $aortic1 = mysqli_real_escape_string($conn,$_POST['aortic1']);
        $L_atrium = mysqli_real_escape_string($conn,$_POST['L_atrium']);
        $L_atrium1 = mysqli_real_escape_string($conn,$_POST['L_atrium1']);
        $R_atrium = mysqli_real_escape_string($conn,$_POST['R_atrium']);
        $R_atrium1 = mysqli_real_escape_string($conn,$_POST['R_atrium1']);
        $rvid = mysqli_real_escape_string($conn,$_POST['rvid']);
        $rvid1 = mysqli_real_escape_string($conn,$_POST['rvid1']);
        $Tapse = mysqli_real_escape_string($conn,$_POST['Tapse']);
        $Tapse1 = mysqli_real_escape_string($conn,$_POST['Tapse1']);
        $mv_area = mysqli_real_escape_string($conn,$_POST['mv_area']);
        $mv_area1 = mysqli_real_escape_string($conn,$_POST['mv_area1']);
        $lv_systolic = mysqli_real_escape_string($conn,$_POST['lv_systolic']);
        $lvef = mysqli_real_escape_string($conn,$_POST['lvef']);
        $chamber = mysqli_real_escape_string($conn,$_POST['chamber']);
        $regional = mysqli_real_escape_string($conn,$_POST['regional']);
        $MV_AV = mysqli_real_escape_string($conn,$_POST['MV_AV']);
        $Thrombus = mysqli_real_escape_string($conn,$_POST['Thrombus']);
        $Vegetation = mysqli_real_escape_string($conn,$_POST['Vegetation']);
        $Effusion = mysqli_real_escape_string($conn,$_POST['Effusion']);
        $IVC = mysqli_real_escape_string($conn,$_POST['IVC']);
        $E_A = mysqli_real_escape_string($conn,$_POST['E_A']);
        $AV_Vmax = mysqli_real_escape_string($conn,$_POST['AV_Vmax']);
        $AV = mysqli_real_escape_string($conn,$_POST['AV']);
        $MR = mysqli_real_escape_string($conn,$_POST['MR']);
        $AR = mysqli_real_escape_string($conn,$_POST['AR']);
        $MS = mysqli_real_escape_string($conn,$_POST['MS']);
        $MS_Remarks = mysqli_real_escape_string($conn,$_POST['MS_Remarks']);
        $MR_Remarks1 = mysqli_real_escape_string($conn,$_POST['MR_Remarks1']);
        $TR = mysqli_real_escape_string($conn,$_POST['TR']);
        $Pulmonary_Valve = mysqli_real_escape_string($conn,$_POST['Pulmonary_Valve']);
        $tr_Vmax = mysqli_real_escape_string($conn,$_POST['tr_Vmax']);
        $tr_Vmax_Remarks = mysqli_real_escape_string($conn,$_POST['tr_Vmax_Remarks']);
        $RVSP = mysqli_real_escape_string($conn,$_POST['RVSP']);
        $lvtdi_septal = mysqli_real_escape_string($conn,$_POST['lvtdi_septal']);
        $lvtdi_leptal = mysqli_real_escape_string($conn,$_POST['lvtdi_leptal']);
        $rvtdi_sa = mysqli_real_escape_string($conn,$_POST['rvtdi_sa']);
        $es_ratio = mysqli_real_escape_string($conn,$_POST['es_ratio']);
        $a_final_impression = mysqli_real_escape_string($conn,$_POST['a_final_impression']);
        $a_recommendation = mysqli_real_escape_string($conn,$_POST['a_recommendation']);
        $TV = mysqli_real_escape_string($conn,$_POST['TV']);
        $TV_structure = mysqli_real_escape_string($conn,$_POST['TV_structure']);
        $MV_structure = mysqli_real_escape_string($conn,$_POST['MV_structure']);
        $AV_structure = mysqli_real_escape_string($conn,$_POST['AV_structure']);
        $PV = mysqli_real_escape_string($conn,$_POST['PV']);
        $PV_structure = mysqli_real_escape_string($conn,$_POST['PV_structure']);
        $IAS = mysqli_real_escape_string($conn,$_POST['IAS']);
        $lv_diastolic = mysqli_real_escape_string($conn,$_POST['lv_diastolic']);
        $MV = mysqli_real_escape_string($conn,$_POST['MV']);
        $others = mysqli_real_escape_string($conn, $_POST['others']);
        $Employee_ID = mysqli_real_escape_string($conn, $_POST['Employee_ID']);
        

    $sql_save_result = mysqli_query($conn, "INSERT INTO tbl_clinical_information (ABP, APR, aortic, aortic1, L_atrium, L_atrium1, R_atrium, R_atrium1, rvid, rvid1, Tapse, Tapse1, mv_area, mv_area1, lv_systolic, lvef, chamber, regional, MV_AV, Thrombus, Vegetation, Effusion, IVC, E_A, AV_Vmax, AV, MR, AR, MS, MS_Remarks, MR_Remarks1, TR, Pulmonary_Valve, tr_Vmax, tr_Vmax_Remarks, RVSP, lvtdi_septal, lvtdi_leptal, rvtdi_sa, es_ratio, a_final_impression, a_recommendation, TV, TV_structure, MV_structure, AV_structure, PV, PV_structure, IAS, lv_diastolic, MV, payment_item_cache_list_id, patient_id, others, status, Employee_ID) VALUES ('$ABP', '$APR', '$aortic', '$aortic1', '$L_atrium', '$L_atrium1', '$R_atrium', '$R_atrium1', '$rvid', '$rvid1', '$Tapse', '$Tapse1', '$mv_area', '$mv_area1', '$lv_systolic', '$lvef', '$chamber', '$regional', '$MV_AV', '$Thrombus', '$Vegetation', '$Effusion', '$IVC', '$E_A', '$AV_Vmax', '$AV', '$MR', '$AR', '$MS', '$MS_Remarks', '$MR_Remarks1', '$TR', '$Pulmonary_Valve', '$tr_Vmax', '$tr_Vmax_Remarks', '$RVSP', '$lvtdi_septal', '$lvtdi_leptal', '$rvtdi_sa', '$es_ratio', '$a_final_impression', '$a_recommendation', '$TV', '$TV_structure', '$MV_structure', '$AV_structure', '$PV', '$PV_structure', '$IAS', '$lv_diastolic', '$MV', '$clinical_item_cache_list_id', '$clinical_patient_id', '$others', 'saved', '$Employee_ID')") or die(mysqli_error($conn));
    if ($sql_save_result) {
        echo "Information was Saved Successfully!";
    } 
    else {
        echo "Failed To Save Information";
    }
    mysqli_close($conn);
    ?>

