<?php
include("./includes/connection.php");
$Git_Post_operative_ID=$_POST['Git_Post_operative_ID'];
$selected_diagnosis = '';
//get selected diagnosis disease
$select_diagnosis = mysqli_query($conn,"select d.procedure_dignosis_code, d.procedure_dignosis_name, po.Diagnosis_ID 
                                                                    from tbl_procedure_diagnosis d, tbl_gti_post_operative_diagnosis po where
                                                                    d.procedure_diagnosis_id = po.procedure_diagnosis_id and
                                                                    po.Git_Post_operative_ID = '$Git_Post_operative_ID'") or die(mysqli_error($conn));
$num = mysqli_num_rows($select_diagnosis);
if($num > 0){
    while ($dtz = mysqli_fetch_array($select_diagnosis)) {
        $selected_diagnosis .= $dtz['procedure_dignosis_name'].'('.$dtz['procedure_dignosis_code'].');  ';
    }
}
echo $selected_diagnosis;