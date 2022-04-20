<?php

include("../includes/connection.php");
session_start();

if($_SESSION['userinfo'])
{
  $employee_Name3 = $_SESSION['userinfo']['Employee_Name'];
}

include("./MPDF/mpdf.php");

$htm = "
        <style>
        table{
          border-collapse:1px;
        }
        </style>
      ";
$htm .= "<center><img src='branchBanner/branchBanner.png' width='100%' ></center>";



// PREVIEW THE PDF REPORT
// if($_GET['action'] == 'preview_pre_operative')
// {


  $checklist_date = $_GET['checklist_date'];
  $Registration_ID = $_GET['Registration_ID'];


  // get patient details
  if (isset($_GET['Registration_ID']) && $_GET['Registration_ID'] != 0) {
    $select_patien_details = mysqli_query($conn,"SELECT pr.Sponsor_ID,Member_Number,Patient_Name, Registration_ID,Gender,Guarantor_Name,Date_Of_Birth
  			FROM
  				tbl_patient_registration pr,
  				tbl_sponsor sp
  			WHERE
  				pr.Registration_ID = '" . $Registration_ID . "' AND
  				sp.Sponsor_ID = pr.Sponsor_ID
  				") or die(mysqli_error($conn));
    $no = mysqli_num_rows($select_patien_details);
    if ($no > 0) {
      while ($row = mysqli_fetch_array($select_patien_details)) {
        $Member_Number = $row['Member_Number'];
        $Patient_Name = $row['Patient_Name'];
        $Registration_ID = $row['Registration_ID'];
        $Gender = $row['Gender'];
        $Guarantor_Name = $row['Guarantor_Name'];
        $Sponsor_ID = $row['Sponsor_ID'];
        $DOB = $row['Date_Of_Birth'];
      }
    } else {
      $Guarantor_Name = '';
      $Member_Number = '';
      $Patient_Name = '';
      $Gender = '';
      $Registration_ID = 0;
    }
  } else {
    $Member_Number = '';
    $Patient_Name = '';
    $Gender = '';
    $Registration_ID = 0;
  }


  // <!-- new date function (Contain years, Months and days)-->
  $Today = "";
  $Today_Date = mysqli_query($conn,"select now() as today");
  while ($row = mysqli_fetch_array($Today_Date)) {
      $original_Date = $row['today'];
      $new_Date = date("Y-m-d", strtotime($original_Date));
      $Today = $new_Date;
      $age = '';
  }
  //<!-- end of the function -->


    $select_operation2 = mysqli_query($conn,"SELECT *  FROM tbl_checklist_for_operation  WHERE Registration_ID = '$Registration_ID' AND
                         Created_date = '$checklist_date'
                         ORDER BY Created_date DESC") or die(mysqli_error($conn));






          $sn = 1;
          while($rw2  = mysqli_fetch_assoc($select_operation2))
          {
            $date = $rw2['Created_date'];
            $estimated_blood_loss2 = $rw2['Estimated_Blood_Loss'];
            $team_brief2 = $rw2['Patient_Discussed_At_Team_Brief'];
            $blood_transfusion2 = $rw2['Blood_Transfusion_Predicted'];
            $patient_consent2 = $rw2['Confirm_Patient_Consent'];
            $patient_discussed2 = $rw2['Patient_Discussed_At_Team_Brief'];
            $operation_confirmed2 = $rw2['Operation_Confirmed'];
            $equipments_available2 = $rw2['All_Equipment_Available'];
            $antibiotics_needed2 = $rw2['Antibiotics_Needed'];
            $available2 = $rw2['Available'];
            $imaging_displayed2 = $rw2['Essential_Imaging_Displayed'];
            $pulse_oximeter2 = $rw2['Pulse_Oximeter'];
            $aspiration2 = $rw2['Aspiration'];
            $analgesia_morphine2 = $rw2['Analgesia'];
            $swabs_counted2 = $rw2['Swabs_Counted'];
            $equipment_problems2 = $rw2['Equipment_Problems_Addressed'];
            $operation_documented2 = $rw2['Operation_Documented'];
            $any_patient_concerns2 = $rw2['Any_Patient_Concerns'];
            $handover_to_ward2 = $rw2['Hand_Over_To_Ward_Staff'];
            $consultation_ID2 = $rw2['consultation_id'];
            $Admision_ID2 = $rw2['Admision_ID'];
            $Registration_ID2 = $rw2['Registration_ID'];
            $Employee_ID2 = $rw2['Employee_ID'];
            $Patient_Payment_Item_List_ID2 = $rw2['Patient_Payment_Item_List_ID'];
            $Created_date2 = $rw2['Created_date'];
            $hb2 = $rw2['Hb'];
            $Consultant_approved2 = $rw2['Consultant_Approved'];
            $Any_essential_imaging2 = $rw2['Any_Essential_Imaging'];
            $allegies2 = $rw2['allergies'];
            $weight2 = $rw2['weight'];

            $sql2 = "SELECT pr.Registration_ID,pr.Gender,pr.Patient_Name,pr.Phone_Number,pr.Member_Number,pr.Date_Of_Birth,ppl.Transaction_Date_And_Time,ppl.Patient_Payment_Item_List_ID,
                      pp.Patient_Payment_ID,sp.Guarantor_Name,ppl.Patient_Direction,ppl.Consultant_ID,em.Employee_Type,em.Employee_Name,c.Doctor_Comment,i.Product_Name
                      FROM tbl_patient_payment_item_list ppl
                      INNER JOIN tbl_patient_payments pp ON pp.Patient_Payment_ID = ppl.Patient_Payment_ID
                      INNER JOIN tbl_item_list_cache c ON ppl.Patient_Payment_ID = c.Patient_Payment_ID
                      INNER JOIN tbl_items i ON i.Item_ID = c.Item_ID
                      JOIN tbl_patient_registration pr ON pp.Registration_ID = pr.Registration_ID
                      JOIN tbl_sponsor sp ON sp.Sponsor_ID = pr.Sponsor_ID
                      LEFT JOIN tbl_employee em ON em.Employee_ID = ppl.Consultant_ID
                      WHERE
                      ppl.Nursing_Status='served' AND
                     Patient_Payment_Item_List_ID=' $Patient_Payment_Item_List_ID2'";

            // $sql2 = "SELECT pr.Registration_ID,pr.Gender,pr.Patient_Name,pr.Phone_Number,pr.Member_Number,pr.Date_Of_Birth,ppl.Transaction_Date_And_Time,ppl.Patient_Payment_Item_List_ID,pp.Patient_Payment_ID,sp.Guarantor_Name,ppl.Patient_Direction,ppl.Consultant_ID,em.Employee_Type,em.Employee_Name
            //           FROM tbl_patient_payment_item_list ppl
            //           INNER JOIN tbl_patient_payments pp ON pp.Patient_Payment_ID = ppl.Patient_Payment_ID
            //           JOIN tbl_patient_registration pr ON pp.Registration_ID = pr.Registration_ID
            //           JOIN tbl_sponsor sp ON sp.Sponsor_ID = pr.Sponsor_ID
            //           LEFT JOIN tbl_employee em ON em.Employee_ID = ppl.Consultant_ID
            //           WHERE
            //           ppl.Nursing_Status='served' AND
            //          Patient_Payment_Item_List_ID=' $Patient_Payment_Item_List_ID2'";

            $select_Patient2 = mysqli_query($conn,$sql2) or die(mysqli_error($conn));

            $no2 = mysqli_num_rows($select_Patient2);

            if ($no2 > 0) {
                while ($row2 = mysqli_fetch_array($select_Patient2)) {
                    $Registration_ID2 = $row2['Registration_ID'];
                    $Patient_Name2 = $row2['Patient_Name'];
                    $Date_Of_Birt2h = $row2['Date_Of_Birth'];
                    $doctor_comment2 = $row2['Doctor_Comment'];
                }

                $Age = floor((strtotime(date('Y-m-d')) - strtotime($Date_Of_Birt2h)) / 31556926) . " Years";
                // if($age == 0){

                $date1 = new DateTime($Today);
                $date2 = new DateTime($Date_Of_Birt2h);
                $diff = $date1->diff($date2);
                $Age = $diff->y . " Years, ";
                $Age .= $diff->m . " Months, ";
                $Age .= $diff->d . " Days";

            } else {
                $Registration_ID2 = '';
                $Patient_Name2 = '';
                $Age = "";

            }


            // // select vital signs data
            //  $nurse_details2 = mysqli_query($conn,"SELECT * FROM tbl_nurse n RIGHT JOIN tbl_nurse_vital nv ON n.Nurse_ID = nv.Nurse_ID
            //                                       WHERE
            //                                       n.Registration_ID ='$Registration_ID2' AND
            //                                       n.Patient_Payment_Item_List_ID='$Patient_Payment_Item_List_ID2'
            //                                       ORDER BY Nurse_DateTime DESC LIMIT 11
            //                                       ") or die(mysqli_error($conn));
            //   $weight = "";
            //   while($vt = mysqli_fetch_assoc($nurse_details2))
            //   {
            //     // weight
            //     if($vt['Vital_ID'] == 2)
            //     {
            //       $weight2 = $vt['Vital_Value'];
            //     }
            //
            //      $allegies2 = $vt['Allegies'];
            //
            //   }


              // operation to be DONE
              $operation_tobe_done3 = mysqli_query($conn,"SELECT i.Product_Name FROM tbl_items i INNER JOIN tbl_patient_payment_item_list p ON i.Item_ID = p.Item_ID
                                                         WHERE p.Patient_Payment_Item_List_ID='$Patient_Payment_Item_List_ID2'") or die(mysqli_error($conn));

               $operation_tobe_done2 = mysqli_fetch_assoc($operation_tobe_done3) ['Product_Name'];





          } //end of while loop

          $htm .='
          <div style="background-color:#037CB0;color:white;">
           <center>
           <div style="height:34px;margin:0px;padding:0px;font-weight:bold">
           <center>
           <p style="margin:0px;padding:0px;">PRE-OPERATIVE CHECKLIST FOR OPERATION</p>
           <p style="color:yellow;margin:0px;padding:0px; "><span style="margin-right:3px;">'.$Patient_Name.' |</span><span style="margin-right:3px;">'.$Gender.' |</span> <span style="margin-right:3px;">'.$age .' | </span> <span style="margin-right:3px;">'.$Guarantor_Name.'</span> </p>

           </center>
           </div>

           </center>
          </div>
          <br/><br/>
          ';



           // ******************************************************************************************************************* -->

          $htm .='  <fieldset>
              <div style=background-color:#037CB0;color:white;font-weight:bold;>Checklist for Operations Responsible Staff in Italics</div>
              <table border="1">
                <tr>
                  <td><b>Day before:name</b></td>
                  <td colspan="3">'.$Patient_Name2.'</td>
                  <td><b>Hb</b></td>
                  <td>'.$hb2.'</td>
                </tr>

                <tr>
                  <td><b>(Clerking Weight:</b></td>
                  <td>'.$weight2.'</td>
                  <td><b>Age:</b></td>
                  <td>'.$Age.'</td>
                  <td><b>Allergies Known:</b></td>
                  <td>'.$allegies2.'</td>
                </tr>

                <tr>
                  <td><b>Doctor Indication for operation:</b></td>
                  <td colspan="5">'.$doctor_comment2.'</textarea></td>
                </tr>

                <tr>
                  <td><b>Operation to be done:</b></td>
                  <td colspan="5">'.$operation_tobe_done2.'</td>
                </tr>

                <tr>
                  <td><b>Consultant approved:</b></td>';
                  if($Consultant_approved2 == 'yes')
                  {
                    $htm .= '<td colspan="5">Yes</td>';

                  }
                  else if($Consultant_approved2 == 'no')
                  {
                    $htm .= '<td colspan="5">No</td>';
                  }
                  else{
                    $htm .= '<td colspan="5"></td>';
                  }

                $htm .='</tr>

                <tr>
                  <td><b>Any essential imaging needed?</b></td>';
                  if($Any_essential_imaging2 == 'yes')
                  {
                    $htm .= '<td colspan="5">Yes</td>';
                  }
                  else if($Any_essential_imaging2 == 'no')
                  {
                    $htm .= '<td colspan="5">No</td>';
                  }
                  else{
                    $htm .= '<td colspan="5"></td>';
                  }

          $htm .='      </tr>
              </table>
            </fieldset>
            <br>

            <!--<fieldset>
              <legend>DAY BEFORE - DOCTORS PRESCRIBE ON DRUG CHART<br> (ward staff  to give 1hr pre-op )</legend>
              <table>
                <tr>
                  <th>Medication</th>
                  <th>Dose/route</th>
                  <th>Dr\'s sign</th>
                  <th>Nurse administer sign</th>
                </tr>

                <tr>
                  <td>Paracetemol (15mg/kg)</td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>

                <tr>
                  <td>Ibuprofen (5mg/kg)</td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>

                <tr>
                  <td>Pethidine (1mg/kg)</td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
              </table>
            </fieldset>
            <br>-->


                  <fieldset>
                    <div style=background-color:#037CB0;color:white;font-weight:bold;>Before Induction</div>
                    <table border="1">
                      <tr>
                        <th>Morning team brief completed</t>';

                          if($team_brief2 == 'yes')
                          {
                            $htm .= '<td colspan="5">Yes</td>';
                          }
                          else if($team_brief2 == 'no')
                          {
                            $htm .= '<td colspan="5">No</td>';
                          }
                          else{
                              $htm .= '<td colspan="5"></td>';
                          }



                    $htm .='  </tr>
                    </table>
                  </fieldset>
                  <br>

                  <!-- scrub team -->
                  <fieldset>
                    <div style=background-color:#037CB0;color:white;font-weight:bold;>Scrub team</div>
                    <table border="1">
                      <tr>
                        <th>Confirm patient & consent?</th>';

                          if($patient_consent2 == 'yes')
                          {
                            $htm .= '<td colspan="5">Yes</td>';
                          }
                          else if($patient_consent2 == 'no')
                          {
                            $htm .= '<td colspan="5">No</td>';
                          }
                          else{
                            $htm .= '<td colspan="5"></td>';
                          }


                    $htm .=' </tr>

                      <tr>
                        <th>Patient discussed at team brief?</th>';

                            if($patient_discussed2 == 'yes')
                            {
                              $htm .= '<td colspan="5">Yes</td>';
                            }
                            else if($patient_discussed2 == 'no')
                            {
                              $htm .= '<td colspan="5">No</td>';
                            }
                            else{
                              $htm .= '<td colspan="5"></td>';
                            }


                    $htm .='</tr>

                      <tr>
                        <th>Operation confirmed (plus site)?</th>';

                            if($operation_confirmed2 == 'yes')
                            {
                              $htm .= '<td colspan="5">Yes</td>';
                            }
                            else if($operation_confirmed2 == 'no')
                            {
                              $htm .= '<td colspan="5">No</td>';
                            }
                            else{
                              $htm .= '<td colspan="5"></td>';
                            }


                    $htm .=' </tr>

                      <tr>
                        <th>All equipments available/sterile?</th>';

                          if($equipments_available2 == 'yes')
                          {
                            $htm .= '<td colspan="5">Yes</td>';
                          }
                          else if($equipments_available2 == 'no')
                          {
                            $htm .= '<td colspan="5">No</td>';
                          }
                          else{
                            $htm .= '<td colspan="5"></td>';
                          }


                  $htm .= ' </tr>
                    </table>
                  </fieldset>
                  <br/>
                  <!-- end scrub team -->


                  <!-- surgeon -->
                  <fieldset>
                    <div style=background-color:#037CB0;color:white;font-weight:bold;>Surgeon</div>
                    <table border="1">
                      <tr>
                        <th>Antibiotics needed?(Ward nurse to bring)</th>';

                            if($antibiotics_needed2 == 'yes')
                            {
                              $htm .= '<td colspan="5">Yes</td>';
                            }
                            else if($antibiotics_needed2 == 'no')
                            {
                              $htm .= '<td colspan="5">No</td>';
                            }
                            else{
                              $htm .= '<td colspan="5"></td>';
                            }


                    $htm .='</tr>

                      <tr>
                        <th>Estimated blood loss</th>
                        <td colspan="5">'.$estimated_blood_loss2.' mls</td>
                      </tr>

                      <tr>
                        <th>Blood transfusion predicted?</th>';

                          if($blood_transfusion2 == 'yes')
                          {
                             $htm .= '<td colspan="5">Yes</td>';
                          }
                          else if($blood_transfusion2 == 'no')
                          {
                            $htm .= '<td colspan="5">No</td>';
                          }
                          else{
                            $htm .= '<td colspan="5"></td>';
                          }


                    $htm .='</tr>

                      <tr>
                        <th>Available?</th>';

                            if($available2  == 'yes')
                            {
                              $htm .= '<td colspan="5">Yes</td>';
                            }
                            else if($available2  == 'no')
                            {
                              $htm .= '<td colspan="5">No</td>';
                            }
                            else{
                              $htm .= '<td colspan="5"></td>';
                            }


                    $htm .=' </tr>

                      <tr>
                        <th>Essential imaging displayed?</th>';

                            if($imaging_displayed2 == 'yes')
                            {
                            $htm .= '<td colspan="5">Yes</td>';
                            }
                            else if($imaging_displayed2 == 'no')
                            {
                              $htm .= '<td colspan="5">No</td>';
                            }
                            else{
                              $htm .= '<td colspan="5"></td>';
                            }


                    $htm .=' </tr>
                    </table>
                  </fieldset>
                  <br/>
                  <!-- end surgeon -->


                  <!-- anaesthetists -->
                  <fieldset>
                    <div style=background-color:#037CB0;color:white;font-weight:bold;>Anaesthetists</div>
                    <table border="1">
                      <tr>
                        <th>Pulse oximeter/machine/drugs ready?</th>';

                            if($pulse_oximeter2 == 'yes')
                            {
                              $htm .= '<td colspan="5">Yes</td>';
                            }
                            else if($pulse_oximeter2 == 'no')
                            {
                              $htm .= '<td colspan="5">No</td>';
                            }
                            else{
                              $htm .= '<td colspan="5"></td>';
                            }


                    $htm .=' </tr>


                      <tr>
                        <th>Aspiration/airway risk?</th>';

                            if($aspiration2  == 'yes')
                            {
                              $htm .= '<td colspan="5">Yes</td>';
                            }
                            else if($aspiration2  == 'no')
                            {
                              $htm .= '<td colspan="5">No</td>';
                            }
                            else{
                              $htm .= '<td colspan="5"></td>';
                            }


                    $htm .=' </tr>

                      <tr>
                        <th>Analgesia (eg morphine 0.1 mg/kg,pethidine 1 mg/kg)?</th>';

                            if($analgesia_morphine2 == 'yes')
                            {
                              $htm .= '<td colspan="5">Yes</td>';
                            }
                            else if($analgesia_morphine2 == 'no')
                            {
                              $htm .= '<td colspan="5">No</td>';
                            }
                            else{
                              $htm .= '<td colspan="5"></td>';
                            }


                    $htm .='  </tr>
                    </table>
                  </fieldset>
                  <br/>
                  <!-- end anaesthetists -->


                  <!-- operation finishing -->
                  <fieldset>
                    <div style=background-color:#037CB0;color:white;font-weight:bold;>Operation Finishing</div>
                    <table border="1">
                      <tr>
                        <th>Swabs counted?</th>';

                            if($swabs_counted2 == 'yes')
                            {
                              $htm .= '<td colspan="5">Yes</td>';
                            }
                            else if($swabs_counted2 == 'no')
                            {
                              $htm .= '<td colspan="5">No</td>';
                            }
                            else{
                              $htm .= '<td colspan="5"></td>';
                            }


                    $htm .=' </tr>


                      <tr>
                        <th>(scrub team) Equipment problems addressed?</th>';

                            if($equipment_problems2 == 'yes')
                            {
                              $htm .= '<td colspan="5">Yes</td>';
                            }
                            else if($equipment_problems2 == 'no')
                            {
                                $htm .= '<td colspan="5">No</td>';
                            }
                            else{
                              $htm .= '<td colspan="5"></td>';
                            }


                    $htm .='  </tr>

                      <tr>
                        <th>Operation documented in the records book?</th>';

                            if($operation_documented2 == 'yes')
                            {
                              $htm .= '<td colspan="5">Yes</td>';
                            }
                            else if($operation_documented2 == 'no')
                            {
                              $htm .= '<td colspan="5">No</td>';
                            }
                            else{
                              $htm .= '<td colspan="5"></td>';
                            }


                    $htm .='  </tr>

                      <tr>
                        <th>(surgeon/anaesthetist) Any patient concerns?</th>';

                          if($any_patient_concerns2 == 'yes')
                          {
                            $htm .= '<td colspan="5">Yes</td>';
                          }
                          else if($any_patient_concerns2 == 'no')
                          {
                            $htm .= '<td colspan="5">No</td>';
                          }
                          else{
                            $htm .= '<td colspan="5"></td>';
                          }


                    $htm .='  </tr>
                    </table>
                  </fieldset>
                  <br/>
                  <!-- end operation finishing  -->

                  <fieldset>
                    <div style=background-color:#037CB0;color:white;font-weight:bold;>Recovery</div>
                    <table border="1">
                      <tr>
                        <th>Handover to ward staff</th>';

                          if($handover_to_ward2 == 'yes')
                          {
                            $htm .= '<td colspan="5">Yes</td>';
                          }
                          else if($handover_to_ward2 == 'no')
                          {
                            $htm .= '<td colspan="5">No</td>';
                          }
                          else{
                            $htm .= '<td colspan="5"></td>';
                          }


                  $htm .=' </tr>
                    </table>
                  </fieldset>';



//}


$mpdf = new mPDF('s', 'A4');
$mpdf->SetDisplayMode('fullpage');
$mpdf->SetFooter('Printed By '.strtoupper($employee_Name3).'|Page {PAGENO} Of {nb}|{DATE d-m-Y} Powered By GPITG');
$mpdf->WriteHTML($htm);
$mpdf->Output();
exit;

 ?>
