<?php
include("All.Templates.Functions.php");
$Registration_ID = (isset($_GET['Registration_ID'])) ? $_GET['Registration_ID'] : 0;
$Temp_Name = (isset($_GET['Temp_Name'])) ? $_GET['Temp_Name'] : 0;
$Admision_ID = (isset($_GET['Admision_ID'])) ? $_GET['Admision_ID'] : 0;
$Form_Number = (isset($_GET['Form_Number'])) ? $_GET['Form_Number'] : "";

if($Temp_Name == 'Audiology' && $Registration_ID != 0){
    $Radiotherapy_Dates = json_decode(getAudiologyDates($conn,$Registration_ID), true);
        if(sizeof($Radiotherapy_Dates)>0){
            foreach($Radiotherapy_Dates AS $rth){
                $Consultation_Dates = $rth['Consultation_Dates'];
                $payment_item_cache_list_id = $rth['payment_item_cache_list_id'];

                
                    $thisDate = date('jS, F Y', strtotime($Consultation_Dates)) . '';

                // echo "<input type='button' style='width: 130px; border-radius: 3px; font-size: 15px;' onclick='audiology_form($payment_item_cache_list_id,$Registration_ID)' class='art-button-green' value='".$thisDate."'>";

                echo "<a href='preview_audiogram.php?Registration_id=$Registration_ID&Payment_Item_Cache_List_ID=$payment_item_cache_list_id' style='width: 130px; border-radius: 3px; font-size: 15px; background: #136a7f' target='_blank' class='art-button-green'>".$thisDate."</a>";

            }
        }else{
            echo "<center><h4>NO AUDIOLOGY VISIT RECORD FOUND</h4></center>";
        }
}elseif($Temp_Name == 'Radiotherapy' && $Registration_ID != 0){
    $Radiotherapy_Dates = json_decode(getRadiotherapyConsultationDates($conn,$Registration_ID), true);
        if(sizeof($Radiotherapy_Dates)>0){
            foreach($Radiotherapy_Dates AS $rth){
                $Consultation_Dates = $rth['Consultation_Dates'];
                $Radiotherapy_ID = $rth['Radiotherapy_ID'];
                $consultation_ID = $rth['consultation_ID'];

                
                    $thisDate = date('jS, F Y', strtotime($Consultation_Dates)) . '';

                echo "<input type='button' style='width: 130px; border-radius: 3px; font-size: 15px;' onclick='Display_Radiotherapy_notes($consultation_ID,$Radiotherapy_ID)' class='art-button-green' value='".$thisDate."'>";
            }
        }else{
            echo "<center><h4>NO RADIOTHERAPY VISIT RECORD FOUND</h4></center>";
        }
}elseif($Temp_Name == 'Brachytherapy' && $Registration_ID != 0){
    $Branchy_Dates = json_decode(getBrachtherapyConsultationDates($conn,$Registration_ID), true);
        if(sizeof($Branchy_Dates)>0){
            foreach($Branchy_Dates AS $dt):
                $Consultation_Dates = $dt['Consultation_Dates'];
                $Brachytherapy_ID = $dt['Brachytherapy_ID'];
                $consultation_ID = $dt['consultation_ID'];

                    $thisDate = date('jS, F Y', strtotime($Consultation_Dates)) . '';

                echo "<input type='button' style='width: 130px; border-radius: 3px; font-size: 15px;' onclick='Display_Branchy_notes($consultation_ID,$Brachytherapy_ID)' class='art-button-green' value='".$thisDate."'>";
            endforeach;
        }else{
            echo "<center><h4>NO BRACHYTHERAPY VISIT RECORD FOUND</h4></center>";
        }

}elseif($Temp_Name == 'ICU' && $Registration_ID != 0){
    // $Templates = '';?
    ?>
    <input type='button' style='border-radius: 3px; font-size: 13px;' onclick="Display_ICU_Forms(<?= $Registration_ID ?>,'tbl_icu_form_one')" class='art-button-green' value='VITAL SIGNS'>
    <input type='button' style='border-radius: 3px; font-size: 13px;' onclick="Display_ICU_Forms(<?= $Registration_ID ?>,'tbl_icu_form_two')" class='art-button-green' value='GLASGOW COMA SCALE (GCS)'>
    <input type='button' style='border-radius: 3px; font-size: 13px;' onclick="Display_ICU_Forms(<?= $Registration_ID ?>,'tbl_icu_form_three')" class='art-button-green' value='MEDICATION ADMINISTRATION'>
    <input type='button' style='border-radius: 3px; font-size: 13px;' onclick="Display_ICU_Forms(<?= $Registration_ID ?>,'tbl_icu_form_four')" class='art-button-green' value='LABORATORY REPORT'>
    <input type='button' style='border-radius: 3px; font-size: 13px;' onclick="Display_ICU_Forms(<?= $Registration_ID ?>,'tbl_icu_form_five')" class='art-button-green' value='HANDOVER ISSUE'>
    <input type='button' style='border-radius: 3px; font-size: 13px;' onclick="Display_ICU_Forms(<?= $Registration_ID ?>,'tbl_icu_form_six')" class='art-button-green' value='PATIENT ASSESSMENT'>
    <input type='button' style='border-radius: 3px; font-size: 13px;' onclick="Display_ICU_Forms(<?= $Registration_ID ?>,'tbl_icu_form_seven')" class='art-button-green' value='ROUTINE CARE'>


    <?php
}elseif($Temp_Name == 'ICU Dates' && $Registration_ID != 0 && $Form_Number != ''){
    $Select_Dates = json_decode(getICU_Dates_General($conn,$Registration_ID,$Form_Number), true);
        if(sizeof($Select_Dates)>0){
            foreach($Select_Dates AS $dts){
                $consultation_ID = $dts['consultation_id'];
                $Doc_ID = $dts['id'];
                $ICU_DATES = $dts['ICU_DATES'];

                $thisDate = date('jS, F Y', strtotime($ICU_DATES)) . '';
                    
                if($Form_Number == 'tbl_icu_form_one'){
                    echo "<a href='icu/form_one_preview.php?record_id=$Doc_ID&Registration_ID=$Registration_ID' style='width: 130px; border-radius: 3px; font-size: 15px;' target='_blank' class='art-button-green'>".$thisDate."</a>";
                }elseif($Form_Number == 'tbl_icu_form_two'){
                    echo "<a href='icu/form_two_preview.php?record_id=$Doc_ID&Registration_ID=$Registration_ID' style='width: 130px; border-radius: 3px; font-size: 15px; background: #5dade2 !important; color: #000;' target='_blank' class='art-button-green'>".$thisDate."</a>";
                }elseif($Form_Number == 'tbl_icu_form_three'){
                    echo "<a href='icu/form_three_preview.php?record_id=$Doc_ID&Registration_ID=$Registration_ID' style='width: 130px; border-radius: 3px; font-size: 15px;background: #FFC300; color: #000;' target='_blank' class='art-button-green'>".$thisDate."</a>";
                }elseif($Form_Number == 'tbl_icu_form_four'){
                    echo "<a href='icu/form_four_preview.php?record_id=$Doc_ID&Registration_ID=$Registration_ID' style='width: 130px; border-radius: 3px; font-size: 15px; background: #137f53;' target='_blank' class='art-button-green'>".$thisDate."</a>";
                }elseif($Form_Number == 'tbl_icu_form_five'){
                    echo "<a href='icu/form_five_preview.php?record_id=$Doc_ID&Registration_ID=$Registration_ID' style='width: 130px; border-radius: 3px; font-size: 15px; background: #3bd596' target='_blank' class='art-button-green'>".$thisDate."</a>";
                }elseif($Form_Number == 'tbl_icu_form_six'){
                    echo "<a href='icu/form_six_preview.php?record_id=$Doc_ID&Registration_ID=$Registration_ID' style='width: 130px; border-radius: 3px; font-size: 15px; background: #137d7f' target='_blank' class='art-button-green'>".$thisDate."</a>";
                }elseif($Form_Number == 'tbl_icu_form_seven'){
                    echo "<a href='icu/form_seven_preview.php?record_id=$Doc_ID&Registration_ID=$Registration_ID' style='width: 130px; border-radius: 3px; font-size: 15px; background: #136a7f' target='_blank' class='art-button-green'>".$thisDate."</a>";
                }           
            }
        }else{
            echo "<center><h4>NO ICU RECORD FOUND</h4></center>";
        }
}else{
    echo "<center><h4>PLEASE SELECT TEMPLATE DATA YOU WANT</h4></center>";
}

mysqli_close($conn);
?>