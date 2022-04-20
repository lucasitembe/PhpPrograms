<?php
function return_form_four($registration_id)
{
$select_data = "SELECT * FROM tbl_icu_form_four WHERE patient_id='$registration_id'";

$response = array();

if ($result = mysqli_query($conn,$select_data)) {
    while ($row = mysqli_fetch_assoc($result)) {

      $response['timeone'] = $row['timeone'];
      $response['potassiumone'] = $row['potassiumone'];
      $response['sodiumone'] = $row['sodiumone'];
      $response['calciumone'] = $row['calciumone'];
      $response['day_accumlated_balance'] = $row['day_accumlated_balance'];
      $response['fluid_restriction'] = $row['fluid_restriction'];
      $response['magnesiumone'] = $row['magnesiumone'];
      $response['chlorideone'] = $row['chlorideone'];
      $response['bicarbonateone'] = $row['bicarbonateone'];
      $response['bunone'] = $row['bunone'];
      $response['cteatinineone'] = $row['cteatinineone'];
      $response['bblood_glucoseone'] = $row['bblood_glucoseone'];
      $response['hbone'] = $row['hbone'];
      $response['hctone'] = $row['hctone'];
      $response['rbcone'] = $row['rbcone'];
      $response['wbcone'] = $row['wbcone'];
      $response['neutrophilone'] = $row['neutrophilone'];
      $response['basophilone'] = $row['basophilone'];
      $response['esinophilone'] = $row['esinophilone'];
      $response['esrone'] = $row['esrone'];
      $response['pt_contone'] = $row['pt_contone'];
      $response['aptt_contone'] = $row['aptt_contone'];
      $response['inrone'] = $row['inrone'];
      $response['pltone'] = $row['pltone'];
      $response['pmone'] = $row['pmone'];

      $response['timesecond'] = $row['timesecond'];
      $response['potassiumsecond'] = $row['potassiumsecond'];
      $response['sodiumsecond'] = $row['sodiumsecond'];
      $response['calciumsecond'] = $row['calciumsecond'];
      $response['magnesiumsecond'] = $row['magnesiumsecond'];
      $response['chloridesecond'] = $row['chloridesecond'];
      $response['bicarbonatesecond'] = $row['bicarbonatesecond'];
      $response['bunsecond'] = $row['bunsecond'];
      $response['cteatininesecond'] = $row['cteatininesecond'];
      $response['bblood_glucosesecond'] = $row['bblood_glucosesecond'];
      $response['hbsecond'] = $row['hbsecond'];
      $response['hctsecond'] = $row['hctsecond'];
      $response['rbcsecond'] = $row['rbcsecond'];
      $response['wbcsecond'] = $row['wbcsecond'];
      $response['neutrophilsecond'] = $row['neutrophilsecond'];
      $response['basophilsecond'] = $row['basophilsecond'];
      $response['esinophilsecond'] = $row['esinophilsecond'];
      $response['esrsecond'] = $row['esrsecond'];
      $response['pt_contsecond'] = $row['pt_contsecond'];
      $response['aptt_contsecond'] = $row['aptt_contsecond'];
      $response['inrsecond'] = $row['inrsecond'];
      $response['pltsecond'] = $row['pltsecond'];
      $response['pmsecond'] = $row['pmsecond'];

      $response['timethird'] = $row['timethird'];
      $response['potassiumthird'] = $row['potassiumthird'];
      $response['sodiumthird'] = $row['sodiumthird'];
      $response['calciumthird'] = $row['calciumthird'];
      $response['magnesiumthird'] = $row['magnesiumthird'];
      $response['chloridethird'] = $row['chloridethird'];
      $response['bicarbonatethird'] = $row['bicarbonatethird'];
      $response['bunthird'] = $row['bunthird'];
      $response['cteatininethird'] = $row['cteatininethird'];
      $response['bblood_glucosethird'] = $row['bblood_glucosethird'];
      $response['hbthird'] = $row['hbthird'];
      $response['hctthird'] = $row['hctthird'];
      $response['rbcthird'] = $row['rbcthird'];
      $response['wbcthird'] = $row['wbcthird'];
      $response['neutrophilthird'] = $row['neutrophilthird'];
      $response['basophilthird'] = $row['basophilthird'];
      $response['esinophilthird'] = $row['esinophilthird'];
      $response['esrthird'] = $row['esrthird'];
      $response['pt_contthird'] = $row['pt_contthird'];
      $response['aptt_contthird'] = $row['aptt_contthird'];
      $response['inrthird'] = $row['inrthird'];
      $response['pltthird'] = $row['pltthird'];
      $response['pmthird'] = $row['pmthird'];
    }
}
return $response;
}




function return_form_four_second($registration_id)
{
$select_data = "SELECT * FROM tbl_icu_form_foursecond WHERE patient_id='$registration_id'";

$response = array();

if ($result = mysqli_query($conn,$select_data)) {
    while ($row = mysqli_fetch_assoc($result)) {

      $response['total_blirrubinone'] = $row['total_blirrubinone'];
      $response['total_blirrubinsecond'] = $row['total_blirrubinsecond'];
      $response['total_blirrubinthird'] = $row['total_blirrubinthird'];
      $response['total_proteinone'] = $row['total_proteinone'];
      $response['total_proteinsecond'] = $row['total_proteinsecond'];
      $response['total_proteinthird'] = $row['total_proteinthird'];
      $response['albuminone'] = $row['albuminone'];
      // $response['chlorideone'] = $row['chlorideone'];
      $response['albuminsecond'] = $row['albuminsecond'];
      $response['albuminthird'] = $row['albuminthird'];
      $response['ag_ratioone'] = $row['ag_ratioone'];
      $response['ag_ratiosecond'] = $row['ag_ratiosecond'];
      $response['ag_ratiothird'] = $row['ag_ratiothird'];
      $response['amylaseone'] = $row['amylaseone'];
      $response['amylasesecond'] = $row['amylasesecond'];
      $response['amylasethird'] = $row['amylasethird'];
      $response['astone'] = $row['astone'];
      $response['astsecond'] = $row['astsecond'];
      $response['astthird'] = $row['astthird'];
      $response['altone'] = $row['altone'];
      $response['altsecond'] = $row['altsecond'];
      $response['altthird'] = $row['altthird'];
      $response['alpone'] = $row['alpone'];
      $response['alpsecond'] = $row['alpsecond'];
      $response['alpthird'] = $row['alpthird'];

      $response['sgotone'] = $row['sgotone'];
      $response['sgotsecond'] = $row['sgotsecond'];
      $response['sgotthird'] = $row['sgotthird'];
      $response['sgptone'] = $row['sgptone'];
      $response['sgptsecond'] = $row['sgptsecond'];
      $response['sgptthird'] = $row['sgptthird'];
      $response['ckone'] = $row['ckone'];
      $response['cksecond'] = $row['cksecond'];
      $response['ckthird'] = $row['ckthird'];
      $response['ck_mbone'] = $row['ck_mbone'];
      $response['ck_mbsecond'] = $row['ck_mbsecond'];
      $response['ldhone'] = $row['ldhone'];
      $response['ldhsecond'] = $row['ldhsecond'];
      $response['ldhthird'] = $row['ldhthird'];
      $response['troponinone'] = $row['troponinone'];
      $response['troponinsecond'] = $row['troponinsecond'];
      $response['troponinthird'] = $row['troponinthird'];
      $response['myglobinone'] = $row['myglobinone'];

    }
}
return $response;
}
 ?>
