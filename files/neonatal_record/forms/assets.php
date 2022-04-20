<?php

class Assets{

  public static function js()
  {
    return '
    <script src="../css/jquery.datetimepicker.js"></script>

        ';
  }

  public static function btnPrintPostnatalChecklist($employee_ID,$registration_id,$delivery_year)
  {
    return '<a href="print_previous_postnatal_checklist_records.php?Employee_ID='.$employee_ID.'&Registration_ID='.$registration_id.'&delivery_year='.$delivery_year.'" target="_blank" class="art-button-green">PREVIEW</a>
    ';
  }


  public static function btnBackPostnatalChecklist($consultation_id,$employee_ID,$registration_id,$admission_id)
  {
    return '<a href="postnatal_checklist.php?consultation_ID='.$consultation_id.'&Employee_ID='.$employee_ID.'&Registration_ID='.$registration_id.'&Admision_ID='.$admission_id.'" class="art-button-green">BACK</a>
    ';
  }

  public static function btnBackPreviousPostnatalChecklist($consultation_id,$employee_ID,$registration_id,$admission_id)
  {
    return '<a href="previous_postnatal_checklist.php?consultation_ID='.$consultation_id.'&Employee_ID='.$employee_ID.'&Registration_ID='.$registration_id.'&Admision_ID='.$admission_id.'" class="art-button-green">BACK</a>
    ';
  }

  public static function btnPostnatalChecklistPreviousRecords($consultation_id,$employee_ID,$registration_id,$admission_id)
  {
    return '<a href="previous_postnatal_checklist.php?consultation_ID='.$consultation_id.'&Employee_ID='.$employee_ID.'&Registration_ID='.$registration_id.'&Admision_ID='.$admission_id.'" class="art-button-green">ALL POSTNATAL CHECKLIST</a>
    ';
  }

  public static function btnPostnatalChecklist($consultation_id,$employee_ID,$registration_id,$admission_id)
  {
    return '<a href="postnatal_checklist.php?consultation_ID='.$consultation_id.'&Employee_ID='.$employee_ID.'&Registration_ID='.$registration_id.'&Admision_ID='.$admission_id.'" class="art-button-green">POSTNATAL CHECKLIST</a>
    ';
  }


  public static function btnBackPriviewPostnatalRecords($consultation_id,$employee_ID,$registration_id,$admission_id)
  {
    return '<a href="preview_postnatal_records.php?consultation_ID='.$consultation_id.'&Employee_ID='.$employee_ID.'&Registration_ID='.$registration_id.'&Admision_ID='.$admission_id.'" class="art-button-green">BACK</a>
    ';
  }


  public static function btnPriviewPostnatalRecords($consultation_id,$employee_ID,$registration_id,$admission_id)
  {
    return '<a href="preview_postnatal_records.php?consultation_ID='.$consultation_id.'&Employee_ID='.$employee_ID.'&Registration_ID='.$registration_id.'&Admision_ID='.$admission_id.'" class="art-button-green">Preview</a>
    ';
  }


  public static function btnVitalSign2hrAfterDelivery($consultation_id,$employee_ID,$registration_id,$admission_id)
  {
    return '<a href="postnatal_virtal_sign_2hr.php?consultation_ID='.$consultation_id.'&Employee_ID='.$employee_ID.'&Registration_ID='.$registration_id.'&Admision_ID='.$admission_id.'" class="art-button-green">VITAL SIGN 2HRS</a>
    ';
  }

  public static function btnUrineOutputMonitoring($consultation_id,$employee_ID,$registration_id,$admission_id)
  {
    return '<a href="postnatal_urine_output_monitoring.php?consultation_ID='.$consultation_id.'&Employee_ID='.$employee_ID.'&Registration_ID='.$registration_id.'&Admision_ID='.$admission_id.'" class="art-button-green">URINE</a>
    ';
  }



  public static function btnFluidsAndBloodTransfusion($consultation_id,$employee_ID,$registration_id,$admission_id)
  {
    return '<a href="postnatal_fluids_and_blood_transfusion.php?consultation_ID='.$consultation_id.'&Employee_ID='.$employee_ID.'&Registration_ID='.$registration_id.'&Admision_ID='.$admission_id.'" class="art-button-green">FLUIDS</a>
    ';
  }

  public static function btnBackToPostnatal($consultation_id,$employee_ID,$registration_id,$admission_id)
  {
    return '<a href="postnatal_record_font_page.php?consultation_ID='.$consultation_id.'&Employee_ID='.$employee_ID.'&Registration_ID='.$registration_id.'&Admision_ID='.$admission_id.'" class="art-button-green">BACK</a>
    ';
  }

  public static function btnBackToNeonatalRecords($consultation_id,$employee_ID,$registration_id,$admission_id)
  {
    return '<a href="neonatal_record.php?consultation_ID='.$consultation_id.'&Employee_ID='.$employee_ID.'&Registration_ID='.$registration_id.'&Admision_ID='.$admission_id.'" class="art-button-green">BACK</a>
    ';
  }

}
 ?>
