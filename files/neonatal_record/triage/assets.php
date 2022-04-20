<?php

class Assets{


  public static function js()
  {
    return '
    <script src="../css/jquery.datetimepicker.js"></script>

        ';
  }


  public static function btnPrint($employee_ID,$registration_id,$delivery_year)
  {
    return '<a href="print_previous_newborn_checklist_per_year_records.php?Employee_ID='.$employee_ID.'&Registration_ID='.$registration_id.'&delivery_year='.$delivery_year.'" target="_blank" class="art-button-green">PREVIEW</a>
    ';
  }

  public static function btnPrintObser($employee_ID,$registration_id,$delivery_year)
  {
    return '<a href="print_newborn_triage_checklist_observation_chart.php?Employee_ID='.$employee_ID.'&Registration_ID='.$registration_id.'&delivery_year='.$delivery_year.'" target="_blank" class="art-button-green">PREVIEW</a>
    ';
  }


  public static function btnBackNewBornChecklistPerYearRecords($consultation_id,$employee_ID,$registration_id,$admission_id,$delivery_year)
  {
    return '<a href="previous_newborn_checklist_per_year_records.php?consultation_ID='.$consultation_id.'&Employee_ID='.$employee_ID.'&Registration_ID='.$registration_id.'&Admision_ID='.$admission_id.'&delivery_year='.$delivery_year.'" class="art-button-green">BACK</a>
    ';
  }

  public static function btnNewBornChecklistObservationChat($consultation_id,$employee_ID,$registration_id,$admission_id,$delivery_year)
  {
    return '<a href="newborn_triage_checklist_observation_chart.php?consultation_ID='.$consultation_id.'&Employee_ID='.$employee_ID.'&Registration_ID='.$registration_id.'&Admision_ID='.$admission_id.'&delivery_year='.$delivery_year.'" class="art-button-green">OBSERVATION CHART</a>
    ';
  }

  public static function btnBackToNeonatalRecords($consultation_id,$employee_ID,$registration_id,$admission_id)
  {
    return '<a href="neonatal_record.php?consultation_ID='.$consultation_id.'&Employee_ID='.$employee_ID.'&Registration_ID='.$registration_id.'&Admision_ID='.$admission_id.'" class="art-button-green">BACK</a>
    ';
  }



  public static function btnNewBornChecklistPerYear($consultation_id,$employee_ID,$registration_id,$admission_id)
  {
    return '<a href="newborn_triage_checklist_per_years.php?consultation_ID='.$consultation_id.'&Employee_ID='.$employee_ID.'&Registration_ID='.$registration_id.'&Admision_ID='.$admission_id.'" class="art-button-green">PREVIEW</a>
    ';
  }


    public static function btnBackNewBornChecklistPerYear($consultation_id,$employee_ID,$registration_id,$admission_id)
    {
      return '<a href="newborn_triage_checklist_per_years.php?consultation_ID='.$consultation_id.'&Employee_ID='.$employee_ID.'&Registration_ID='.$registration_id.'&Admision_ID='.$admission_id.'" class="art-button-green">BACK</a>
      ';
    }


  public static function btnBackNewBornTriageChecklist($consultation_id,$employee_ID,$registration_id,$admission_id)
  {
    return '<a href="newborn_triage_checklist_font_page.php?consultation_ID='.$consultation_id.'&Employee_ID='.$employee_ID.'&Registration_ID='.$registration_id.'&Admision_ID='.$admission_id.'" class="art-button-green">BACK</a>
    ';
  }


}



 ?>
