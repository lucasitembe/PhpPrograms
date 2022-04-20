<?php
  include('header.php');
  include('../includes/connection.php');

  $list = array(
    'Respiratory',
    'Air entry',
    'Breath Sound',
    'Chest Expansion',
    'Use of Accessory Muscle',
    'Ability To Cough',
    'cv',
    'Rythm',
    'Daily Weight',
    'Capillary Refil',
    'Skin Condition',
    'Colour:Pink/Pale/Cynotic/Juandice',
    'Turgor:Normal/Loose/Tight/Shiny',
    'Texture Dry/Moist',
    'Odema[Sites]',
    'GI', 
    'Abdomen:Soft/Hard/Distended/Tender',
    'Bowel Sound:Normal hyperactive',
    'Hypoactive/Absent',
    '*NG Tube Insertion Date NA/Clamped/Cont Suction/INT.Suction Gravity',
    'Diet(Restricted/Regular)',
    'Activity',
    'Level Of Mobility',
    '(**CBR/up To Washroom)',
    'Activity(Assisted/Self)',
    'Drains NA/Type/Location',
    'Character',
    'Vomitus: Amount/Colour',
    'Stool: Consistency/Clour/Pattern',
    'Amount Small,Medium,Large,Nil',
    'GU',
    'Urine Colour /Sediments /Haematuria',
    'Foleys Isertion Date',
    'Dialysis',

    'Pulse Code',
    '0 = Absent Radial:R/L','+1 = Weak  Femoral: R/L',
    '+2 = Normal Dor Ped/R/L',
    '+3 = Strong Post tip R/L',
    '+4 = Bounding',
    'Nurse - Family Interaction'
  ); 

  $list_name_id = array(
    'respiratory',
    'air_entry',
    'breath_sound',

    'chest_expansion',
    'use_of_accessory_muscle',
    'ability_to_cough',

    'cv',
    'rythm',
    'daily_weight',

    'capillary_refil',
    'skin_condition',
    'colour_pink_pale_cynotic_juandice',

    'turgor_normal_loose_ight_shiny',
    'texture_dry_moist',
    'odema_sites',

    'gi', 
    'abdomen_soft_hard_distended_tender',
    'bowel_sound_normal_hyperactive',

    'hypoactive_absent',
    'ng_tube_insertion_date_na_clamped_cont_suction_int',
    'diet_restricted',

    'activity',
    'level_of_mobility',
    'cbr_up_to_washroom',

    'activity_assisted_self',
    'drains_na_type_location',
    'character',

    'vomitus_amount_colour',
    'stool_consistency',
    'amount_small_m_l_nil','gu',

    'urine_colour',
    'foleys_isertion_date',
    'dialysis',

    'pulse_code',
    'absent_radial',
    'weak_femoral',

    'normal_dor_ped',
    'strong_post_tip',
    'bounding',
    'nurse_family_interaction'
  )
?>

<style>
  .section {
    margin: 2em;
    background-color: #ccc;
  }

  table {
    background-color: #fff;
    width: 100%;
    font-size: 16px;
  }
  td{
    padding: 2em;
  }
  #save_form_six{
    background-color: #0079AE;
    padding: 10px 30px;
    border: none;
    border-radius: 5px;
  }
</style>


<a href="icu.php?consultation_ID=<?= $consultation_id;?>&Registration_ID=<?=$registration_id;?>&Admision_ID=<?=$admission_id?>" class="art-button-green">BACK</a>

<center>
  <fieldset>
    <legend align=center style="font-weight:bold">Form Six</legend>

    <div class="section">
      <table>
        <thead>
          <tr>
            <th width="30%">Assessment</th>
            <th width="23.3%">Am</th>
            <th width="23.3%">Pm</th>
            <th width="23.3%">Night</th>
          </tr>
        </thead>

        <tbody>
          <?php for($i = 0; $i < sizeof($list); $i++ ) : ?>
          <tr>
            <td><?php echo $list[$i]; ?></td>
            <td><input type="text" id="<?php echo $list_name_id[$i] ?>_am"></td>
            <td><input type="text" id="<?php echo $list_name_id[$i] ?>_pm"></td>
            <td><input type="text" id="<?php echo $list_name_id[$i] ?>_night"></td>
          </tr>
          <?php endfor; ?>
          <tr>
            <td colspan="3"></td>
            <td style="text-align: end;"><button id="save_form_six"><span style="color: #fff;font-weight:bold">SAVE</span></button></td>
          </tr>
        </tbody>
      </table>
    </div>
  </fieldset>
</center>

<script>
  $(document).ready(() => {
    $('#save_form_six').click((e) => {
      var form_six = 'form_six';

      var respiratory_am = $('#respiratory_am').val();
      var respiratory_pm = $('#respiratory_pm').val();
      var respiratory_night = $('#respiratory_night').val();

      var air_entry_am = $('#air_entry_am').val();
      var air_entry_pm = $('#air_entry_pm').val();
      var air_entry_night = $('#air_entry_night').val();

      var breath_sound_am = $('#breath_sound_am').val();
      var breath_sound_pm = $('#breath_sound_pm').val();
      var breath_sound_night = $('#breath_sound_night').val();

      var chest_expansion_am = $('#chest_expansion_am').val();
      var chest_expansion_pm = $('#chest_expansion_pm').val();
      var chest_expansion_night = $('#chest_expansion_night').val();

      var use_of_accessory_muscle_am = $('#use_of_accessory_muscle_am').val();
      var use_of_accessory_muscle_pm = $('#use_of_accessory_muscle_pm').val();
      var use_of_accessory_muscle_night = $('#use_of_accessory_muscle_night').val();

      var ability_to_cough_am = $('#ability_to_cough_am').val();
      var ability_to_cough_pm = $('#ability_to_cough_pm').val();
      var ability_to_cough_night = $('#ability_to_cough_night').val();

      var cv_am = $('#cv_am').val();
      var cv_pm = $('#cv_pm').val();
      var cv_night = $('#cv_night').val();

      var rythm_am = $('#rythm_am').val();
      var rythm_pm = $('#rythm_pm').val();
      var rythm_night = $('#rythm_night').val();

      var daily_weight_am = $('#daily_weight_am').val();
      var daily_weight_pm = $('#daily_weight_pm').val();
      var daily_weight_night = $('#daily_weight_night').val();

      var capillary_refil_am = $('#capillary_refil_am').val();
      var capillary_refil_pm = $('#capillary_refil_pm').val();
      var capillary_refil_night = $('#capillary_refil_night').val();

      var skin_condition_am = $('#skin_condition_am').val();
      var skin_condition_pm = $('#skin_condition_pm').val();
      var skin_condition_night = $('#skin_condition_night').val();

      var colour_pink_pale_cynotic_juandice_am = $('#colour_pink_pale_cynotic_juandice_am').val();
      var colour_pink_pale_cynotic_juandice_pm = $('#colour_pink_pale_cynotic_juandice_pm').val();
      var colour_pink_pale_cynotic_juandice_night = $('#colour_pink_pale_cynotic_juandice_night').val();

      var turgor_normal_loose_ight_shiny_am = $('#turgor_normal_loose_ight_shiny_am').val();
      var turgor_normal_loose_ight_shiny_pm = $('#turgor_normal_loose_ight_shiny_pm').val();
      var turgor_normal_loose_ight_shiny_night = $('#turgor_normal_loose_ight_shiny_night').val();
    
      var texture_dry_moist_am = $('#texture_dry_moist_am').val();
      var texture_dry_moist_pm = $('#texture_dry_moist_pm').val();
      var texture_dry_moist_night = $('#texture_dry_moist_night').val();

      var odema_sites_am = $('#odema_sites_am').val();
      var odema_sites_pm = $('#odema_sites_pm').val();
      var odema_sites_night = $('#odema_sites_night').val();

      var gi_am = $('#gi_am').val();
      var gi_pm = $('#gi_pm').val();
      var gi_night = $('#gi_night').val();

      var abdomen_soft_hard_distended_tender_am = $('#abdomen_soft_hard_distended_tender_am').val();
      var abdomen_soft_hard_distended_tender_pm = $('#abdomen_soft_hard_distended_tender_pm').val();
      var abdomen_soft_hard_distended_tender_night = $('#abdomen_soft_hard_distended_tender_night').val();

      var bowel_sound_normal_hyperactive_am = $('#bowel_sound_normal_hyperactive_am').val();
      var bowel_sound_normal_hyperactive_pm = $('#bowel_sound_normal_hyperactive_pm').val();
      var bowel_sound_normal_hyperactive_night = $('#bowel_sound_normal_hyperactive_night').val();

      var hypoactive_absent_am = $('#hypoactive_absent_am').val();
      var hypoactive_absent_pm = $('#hypoactive_absent_pm').val();
      var hypoactive_absent_night = $('#hypoactive_absent_night').val();

      var ng_tube_insertion_date_na_clamped_cont_suction_int_am = $('#ng_tube_insertion_date_na_clamped_cont_suction_int_am').val();
      var ng_tube_insertion_date_na_clamped_cont_suction_int_pm = $('#ng_tube_insertion_date_na_clamped_cont_suction_int_pm').val();
      var ng_tube_insertion_date_na_clamped_cont_suction_int_night = $('#ng_tube_insertion_date_na_clamped_cont_suction_int_night').val();

      var diet_restricted_am = $('#diet_restricted_am').val();
      var diet_restricted_pm = $('#diet_restricted_pm').val();
      var diet_restricted_night = $('#diet_restricted_night').val();

      var activity_am = $('#activity_am').val();
      var activity_pm = $('#activity_pm').val();
      var activity_night = $('#activity_night').val();

      var level_of_mobility_am = $('#level_of_mobility_am').val();
      var level_of_mobility_pm = $('#level_of_mobility_pm').val();
      var level_of_mobility_night = $('#level_of_mobility_night').val();

      var cbr_up_to_washroom_am = $('#cbr_up_to_washroom_am').val();
      var cbr_up_to_washroom_pm = $('#cbr_up_to_washroom_pm').val();
      var cbr_up_to_washroom_night = $('#cbr_up_to_washroom_night').val();

      var activity_assisted_self_am = $('#activity_assisted_self_am').val();
      var activity_assisted_self_pm = $('#activity_assisted_self_pm').val();
      var activity_assisted_self_night = $('#activity_assisted_self_night').val();

      var drains_na_type_location_am = $('#drains_na_type_location_am').val();
      var drains_na_type_location_pm = $('#drains_na_type_location_pm').val();
      var drains_na_type_location_night = $('#drains_na_type_location_night').val();

      var character_am = $('#character_am').val();
      var character_pm = $('#character_pm').val();
      var character_night = $('#character_night').val();

      var vomitus_amount_colour_am = $('#vomitus_amount_colour_am').val();
      var vomitus_amount_colour_pm = $('#vomitus_amount_colour_pm').val();
      var vomitus_amount_colour_night = $('#vomitus_amount_colour_night').val();

      var stool_consistency_am = $('#stool_consistency_am').val();
      var stool_consistency_pm = $('#stool_consistency_pm').val();
      var stool_consistency_night = $('#stool_consistency_night').val();

      var amount_small_m_l_nil_am = $('#amount_small_m_l_nil_am').val();
      var amount_small_m_l_nil_pm = $('#amount_small_m_l_nil_pm').val();
      var amount_small_m_l_nil_night = $('#amount_small_m_l_nil_night').val();

      var gu_am = $('#gu_am').val();
      var gu_pm = $('#gu_pm').val();
      var gu_night = $('#gu_night').val();

      var urine_colour_am = $('#urine_colour_am').val();
      var urine_colour_pm = $('#urine_colour_pm').val();
      var urine_colour_night = $('#urine_colour_night').val();

      var foleys_isertion_date_am = $('#foleys_isertion_date_am').val();
      var foleys_isertion_date_pm = $('#foleys_isertion_date_pm').val();
      var foleys_isertion_date_night = $('#foleys_isertion_date_night').val();

      var dialysis_am = $('#dialysis_am').val();
      var dialysis_pm = $('#dialysis_pm').val();
      var dialysis_night = $('#dialysis_night').val();

      var pulse_code_am = $('#pulse_code_am').val();
      var pulse_code_pm = $('#pulse_code_pm').val();
      var pulse_code_night = $('#pulse_code_night').val();

      var absent_radial_am = $('#absent_radial_am').val();
      var absent_radial_pm = $('#absent_radial_pm').val();
      var absent_radial_night = $('#absent_radial_night').val();

      var weak_femoral_am = $('#weak_femoral_am').val();
      var weak_femoral_pm = $('#weak_femoral_pm').val();
      var weak_femoral_night = $('#weak_femoral_night').val();

      var normal_dor_ped_am = $('#normal_dor_ped_am').val(); 
      var normal_dor_ped_pm = $('#normal_dor_ped_pm').val(); 
      var normal_dor_ped_night = $('#normal_dor_ped_night').val(); 

      var strong_post_tip_am = $('#strong_post_tip_am').val();
      var strong_post_tip_pm = $('#strong_post_tip_pm').val();
      var strong_post_tip_night = $('#strong_post_tip_night').val();

      var bounding_am = $('#bounding_am').val();
      var bounding_pm = $('#bounding_pm').val();
      var bounding_night = $('#bounding_night').val();

      var nurse_family_interaction_am = $('#nurse_family_interaction_am').val();
      var nurse_family_interaction_pm = $('#nurse_family_interaction_pm').val();
      var nurse_family_interaction_night = $('#nurse_family_interaction_night').val();

      if(confirm('Are you sure ?' + respiratory_am)){
        $.post(
          'icu_recap.php',
          {
            respiratory_am: respiratory_am,
            respiratory_pm: respiratory_pm,
            respiratory_night: respiratory_night,

            air_entry_am: air_entry_am,
            air_entry_pm: air_entry_pm,
            air_entry_night: air_entry_night,

            breath_sound_am: breath_sound_am,
            breath_sound_pm: breath_sound_pm,
            breath_sound_night: breath_sound_night,

            chest_expansion_am: chest_expansion_am,
            chest_expansion_pm: chest_expansion_pm,
            chest_expansion_night: chest_expansion_night,

            use_of_accessory_muscle_am: use_of_accessory_muscle_am,
            use_of_accessory_muscle_pm: use_of_accessory_muscle_pm,
            use_of_accessory_muscle_night: use_of_accessory_muscle_night,

            ability_to_cough_am: ability_to_cough_am,
            ability_to_cough_pm: ability_to_cough_pm,
            ability_to_cough_night: ability_to_cough_night,

            cv_am: cv_am,
            cv_pm: cv_pm,
            cv_night: cv_night,

            rythm_am: rythm_am,
            rythm_pm: rythm_pm,
            rythm_night: rythm_night,

            daily_weight_am: daily_weight_am,
            daily_weight_pm: daily_weight_pm,
            daily_weight_night: daily_weight_night,

            capillary_refil_am: capillary_refil_am,
            capillary_refil_pm: capillary_refil_pm,
            capillary_refil_night: capillary_refil_night,

            skin_condition_am:skin_condition_am,
            skin_condition_pm: skin_condition_pm,
            skin_condition_night: skin_condition_night,

            colour_pink_pale_cynotic_juandice_am: colour_pink_pale_cynotic_juandice_am,
            colour_pink_pale_cynotic_juandice_pm: colour_pink_pale_cynotic_juandice_pm,
            colour_pink_pale_cynotic_juandice_night: colour_pink_pale_cynotic_juandice_night,

            turgor_normal_loose_ight_shiny_am: turgor_normal_loose_ight_shiny_am,
            turgor_normal_loose_ight_shiny_pm: turgor_normal_loose_ight_shiny_pm,
            turgor_normal_loose_ight_shiny_night: turgor_normal_loose_ight_shiny_night,

            texture_dry_moist_am: texture_dry_moist_am,
            texture_dry_moist_pm: texture_dry_moist_pm,
            texture_dry_moist_night: texture_dry_moist_night,

            odema_sites_am: odema_sites_am,
            odema_sites_pm: odema_sites_pm,
            odema_sites_night: odema_sites_night,

            gi_am: gi_am,
            gi_pm : gi_pm,
            gi_night: gu_night,

            abdomen_soft_hard_distended_tender_am: abdomen_soft_hard_distended_tender_am,
            abdomen_soft_hard_distended_tender_pm: abdomen_soft_hard_distended_tender_pm,
            abdomen_soft_hard_distended_tender_night: abdomen_soft_hard_distended_tender_night,

            bowel_sound_normal_hyperactive_am: bowel_sound_normal_hyperactive_am,
            bowel_sound_normal_hyperactive_pm: bowel_sound_normal_hyperactive_pm,
            bowel_sound_normal_hyperactive_night: bowel_sound_normal_hyperactive_night,

            hypoactive_absent_am: hypoactive_absent_am,
            hypoactive_absent_pm: hypoactive_absent_pm,
            hypoactive_absent_night: hypoactive_absent_night,

            ng_tube_insertion_date_na_clamped_cont_suction_int_am: ng_tube_insertion_date_na_clamped_cont_suction_int_am,
            ng_tube_insertion_date_na_clamped_cont_suction_int_pm: ng_tube_insertion_date_na_clamped_cont_suction_int_pm,
            ng_tube_insertion_date_na_clamped_cont_suction_int_night: ng_tube_insertion_date_na_clamped_cont_suction_int_night,

            diet_restricted_am: diet_restricted_am,
            diet_restricted_pm: diet_restricted_pm,
            diet_restricted_night: diet_restricted_night,

            activity_am: activity_am,
            activity_pm: activity_pm,
            activity_night: activity_night,

            level_of_mobility_am: level_of_mobility_am,
            level_of_mobility_pm: level_of_mobility_pm,
            level_of_mobility_night: level_of_mobility_night,

            cbr_up_to_washroom_am: cbr_up_to_washroom_am,
            cbr_up_to_washroom_pm: cbr_up_to_washroom_pm,
            cbr_up_to_washroom_night: cbr_up_to_washroom_night,

            activity_assisted_self_am: activity_assisted_self_am,
            activity_assisted_self_pm: activity_assisted_self_pm,
            activity_assisted_self_night: activity_assisted_self_night,

            drains_na_type_location_am: drains_na_type_location_am,
            drains_na_type_location_pm: drains_na_type_location_pm,
            drains_na_type_location_night: drains_na_type_location_night,

            character_am: character_am,
            character_pm: character_pm,
            character_night: character_night,

            vomitus_amount_colour_am: vomitus_amount_colour_am,
            vomitus_amount_colour_pm: vomitus_amount_colour_pm,
            vomitus_amount_colour_night: vomitus_amount_colour_night,

            stool_consistency_am: stool_consistency_am,
            stool_consistency_pm: stool_consistency_pm,
            stool_consistency_night: stool_consistency_night,

            amount_small_m_l_nil_am: amount_small_m_l_nil_am,
            amount_small_m_l_nil_pm: amount_small_m_l_nil_pm,
            amount_small_m_l_nil_night: amount_small_m_l_nil_night,

            gu_am: gu_am,
            gu_pm: gu_pm,
            gu_night: gu_night,

            urine_colour_am: urine_colour_am,
            urine_colour_pm: urine_colour_pm,
            urine_colour_night: urine_colour_night,

            foleys_isertion_date_am: foleys_isertion_date_am,
            foleys_isertion_date_pm: foleys_isertion_date_pm,
            foleys_isertion_date_night: foleys_isertion_date_night,

            dialysis_am: dialysis_am,
            dialysis_pm: dialysis_pm,
            dialysis_night: dialysis_night,

            pulse_code_am: pulse_code_am,
            pulse_code_pm: pulse_code_pm,
            pulse_code_night: pulse_code_night,

            absent_radial_am: absent_radial_am,
            absent_radial_pm: absent_radial_pm,
            absent_radial_night: absent_radial_night,

            weak_femoral_am: weak_femoral_am,
            weak_femoral_pm: weak_femoral_pm,
            weak_femoral_night: weak_femoral_night,

            normal_dor_ped_am: normal_dor_ped_am,
            normal_dor_ped_pm: normal_dor_ped_pm,
            normal_dor_ped_night: normal_dor_ped_night,

            strong_post_tip_am: strong_post_tip_am,
            strong_post_tip_pm: strong_post_tip_pm,
            strong_post_tip_night: strong_post_tip_night,

            bounding_am: bounding_am,
            bounding_pm: bounding_pm,
            bounding_night: bounding_pm,

            nurse_family_interaction_am: nurse_family_interaction_am,
            nurse_family_interaction_pm: nurse_family_interaction_pm,
            nurse_family_interaction_night: nurse_family_interaction_night,

            pulse_code_am:pulse_code_am,
            pulse_code_pm: pulse_code_pm,
            pulse_code_night: pulse_code_night,

            absent_radial_am: absent_radial_am,
            absent_radial_pm: absent_radial_pm,
            absent_radial_night: absent_radial_night,
            
            nurse_family_interaction_am: nurse_family_interaction_am,
            nurse_family_interaction_pm: nurse_family_interaction_pm,
            nurse_family_interaction_night: nurse_family_interaction_night,

            form_six: form_six

          },
          (response)=>{
            alert(response);
          }
        )
      }

    })
  })
</script>