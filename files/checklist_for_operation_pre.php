




<!-- inner modal for  showing previous induction before the operation-->
   <!-- Modal -->
         <div id="myModalp<?=$sn?>" class="modal fade" role="dialog">
         <div class="modal-dialog" style="width:95%;">

           <!-- Modal content-->
           <div class="modal-content">
             <div class="modal-header" style="background-color:#037CB0;">
               <button type="button" class="close" data-dismiss="modal">&times;</button>
               <h4 class="modal-title" style="color:white;font-size:16px;font-weight:200;"><?=$date?></h4>
             </div>
             <div class="modal-body">
               <center>
               <form class="" action="#" method="post">


                  <!-- // ******************************************************************************************************************* -->

                    <fieldset>
                      <legend>Checklist for Operations Responsible Staff in Italics</legend>
                      <table border="1">
                        <tr>
                          <td>Day before:name</td>
                          <td colspan="3">
                            <input type="text" class="form-control" name="patient_name" id="patient_name" value="<?= $Patient_Name;?>">
                            <input type="hidden"  name="consultation_ID" id="consultation_ID2" value="<?= $consultation_ID2?>">
                            <input type="hidden"  name="Admision_ID" id="Admision_ID2" value="<?= $Admision_ID2?>">
                            <input type="hidden"  name="Registration_ID" id="Registration_ID2" value="<?= $Registration_ID2?>">
                            <input type="hidden"  name="Employee_ID" id="Employee_ID2" value="<?= $Employee_ID2?>">
                            <input type="hidden"  name="Created_date2" id="Created_date2" value="<?= $date?>">
                            <input type="hidden"  name="Patient_Payment_Item_List_ID2" id="Patient_Payment_Item_List_ID2" value="<?= $Patient_Payment_Item_List_ID2?>">
                          </td>
                          <td>Hb</td>
                          <td><input type="text" class="form-control" name="hb2" id="hb2" value="<?php if(!empty($hb2)){ echo $hb2; }?>"></td>
                        </tr>

                        <tr>
                          <td>(Clerking Weight:</td>
                          <td><input type="text" class="form-control" name="weight2" id="weight2" value="<?= $weight2; ?>"></td>
                          <td>Age:</td>
                          <td><input type="text" class="form-control" name="age" id="age" value="<?= $Age;?>"></td>
                          <td>Allergies Known:</td>
                          <td><textarea class="form-control" rows="2" name="allergies2" id="allergies2"><?= $allegies2;?></textarea></td>
                        </tr>

                        <tr>
                          <td>Doctor Indication for operation:</td>
                          <td colspan="5"><textarea class="form-control" rows="2" name="indication_for_operation" id="indication_for_operation"><?=$comment_from_doctor2?></textarea></td>
                        </tr>

                        <tr>
                          <td>Operation to be done:</td>
                          <td colspan="4"><input type="text" class="form-control" name="operation_to_be_done" id="operation_to_be_done" value="<?= $operation_tobe_done2;?>">
                            <input type="hidden" name="Patient_Payment_Item_List_ID2" value="<?=$operation_tobe_done2_ID?>">
                          </td>
                        </tr>

                        <tr>
                          <td>Consultant approved:</td>
                          <?php
                              if($Consultant_approved2 == 'yes')
                              {
                                echo '<td><label><input type="radio" name="consultant_approved2" id="consultant_approvedYes2" style="width:20px;height:20px;" value="yes" checked="true">Yes</label></td>
                                      <td><label><input type="radio" name="consultant_approved2" id="consultant_approvedNo2" style="width:20px;height:20px;" value="no">No</label></td>';
                              }
                              else if($Consultant_approved2 == 'no')
                              {
                                echo '<td><label><input type="radio" name="consultant_approved2" id="consultant_approvedYes2" style="width:20px;height:20px;" value="yes">Yes</label></td>
                                      <td><label><input type="radio" name="consultant_approved2" id="consultant_approvedNo2" style="width:20px;height:20px;" value="no" checked="true">No</label></td>';
                              }
                              else{
                                echo '<td><label><input type="radio" name="consultant_approved2" id="consultant_approvedYes2" style="width:20px;height:20px;" value="yes">Yes</label></td>
                                      <td><label><input type="radio" name="consultant_approved2" id="consultant_approvedNo2" style="width:20px;height:20px;" value="no">No</label></td>';
                              }
                           ?>
                        </tr>

                        <tr>
                          <td>Any essential imaging needed?</td>
                          <?php

                              if($Any_essential_imaging2 == 'yes')
                              {
                                echo '<td><label><input type="radio" name="essential_imaging2" id="essential_imagingYes2" style="width:20px;height:20px;" value="yes" checked="true">Yes</label></td>
                                      <td><label><input type="radio" name="essential_imaging2" id="essential_imagingNo2" style="width:20px;height:20px;" value="no">No</label></td>';
                              }
                              else if($Any_essential_imaging2 == 'no')
                              {
                                echo '<td><label><input type="radio" name="essential_imaging2" id="essential_imagingYes2" style="width:20px;height:20px;" value="yes">Yes</label></td>
                                      <td><label><input type="radio" name="essential_imaging2" id="essential_imagingNo2" style="width:20px;height:20px;" value="no" checked="true">No</label></td>';
                              }
                              else{
                                echo '<td><label><input type="radio" name="essential_imaging2" id="essential_imagingYes2" style="width:20px;height:20px;" value="yes">Yes</label></td>
                                      <td><label><input type="radio" name="essential_imaging2" id="essential_imagingNo2" style="width:20px;height:20px;" value="no">No</label></td>';
                              }
                              ?>
                      </table>
                    </fieldset>
                    <br>

                    <!-- <fieldset>
                      <legend>DAY BEFORE - DOCTORS PRESCRIBE ON DRUG CHART<br> (ward staff  to give 1hr pre-op )</legend>
                      <table>
                        <tr>
                          <th>Medication</th>
                          <th>Dose/route</th>
                          <th>Dr's sign</th>
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
                    <br> -->


                          <fieldset>
                            <legend>Before Induction</legend>
                            <table border="1">
                              <tr>
                                <th>Morning team brief completed</th>
                                <?php
                                  if($team_brief2 == 'yes')
                                  {
                                    echo '<td><label><input type="radio" name="team_brief2" id="team_briefYes2" style="width:20px;height:20px;" value="yes" checked="true">Yes</label></td>
                                          <td><label><input type="radio" name="team_brief2" id="team_briefNo2" style="width:20px;height:20px;" value="no">No</label></td>';
                                  }
                                  else if($team_brief2 == 'no')
                                  {
                                    echo '<td><label><input type="radio" name="team_brief2" id="team_briefYes2" style="width:20px;height:20px;" value="yes">Yes</label></td>
                                          <td><label><input type="radio" name="team_brief2" id="team_briefNo2" style="width:20px;height:20px;" value="no" checked="true">No</label></td>';
                                  }
                                  else{
                                    echo '<td><label><input type="radio" name="team_brief2" id="team_briefYes2" style="width:20px;height:20px;" value="yes">Yes</label></td>
                                          <td><label><input type="radio" name="team_brief2" id="team_briefNo2" style="width:20px;height:20px;" value="no">No</label></td>';
                                  }

                                 ?>

                              </tr>
                            </table>
                          </fieldset>
                          <br>

                          <!-- scrub team -->
                          <fieldset>
                            <legend>Scrub team</legend>
                            <table border="1">
                              <tr>
                                <th>Confirm patient & consent?</th>
                                <?php
                                  if($patient_consent2 == 'yes')
                                  {
                                    echo '<td><label><input type="radio" name="patient_consent2" id="patient_consentYes2" style="width:20px;height:20px;" value="yes" checked="true">Yes</label></td>
                                         <td><label><input type="radio" name="patient_consent2" id="patient_consentNo2" style="width:20px;height:20px;" value="no">No</label></td>';
                                  }
                                  else if($patient_consent2 == 'no')
                                  {
                                    echo '<td><label><input type="radio" name="patient_consent2" id="patient_consentYes2" style="width:20px;height:20px;" value="yes">Yes</label></td>
                                         <td><label><input type="radio" name="patient_consent2" id="patient_consentNo2" style="width:20px;height:20px;" value="no" checked="true">No</label></td>';
                                  }
                                  else{
                                    echo '<td><label><input type="radio" name="patient_consent2" id="patient_consentYes2" style="width:20px;height:20px;" value="yes">Yes</label></td>
                                         <td><label><input type="radio" name="patient_consent2" id="patient_consentNo2" style="width:20px;height:20px;" value="no">No</label></td>';
                                  }
                                 ?>

                              </tr>

                              <tr>
                                <th>Patient discussed at team brief?</th>
                                <?php
                                    if($patient_discussed2 == 'yes')
                                    {
                                      echo '<td><label><input type="radio" name="patient_discussed2" id="patient_discussedYes2" style="width:20px;height:20px;" value="yes" checked="true">Yes</label></td>
                                            <td><label><input type="radio" name="patient_discussed2" id="patient_discussedNo2" style="width:20px;height:20px;" value="no">No</label></td>';
                                    }
                                    else if($patient_discussed2 == 'no')
                                    {
                                      echo '<td><label><input type="radio" name="patient_discussed2" id="patient_discussedYes2" style="width:20px;height:20px;" value="yes">Yes</label></td>
                                            <td><label><input type="radio" name="patient_discussed2" id="patient_discussedNo2" style="width:20px;height:20px;" value="no" checked="true">No</label></td>';
                                    }
                                    else{
                                      echo '<td><label><input type="radio" name="patient_discussed2" id="patient_discussedYes2" style="width:20px;height:20px;" value="yes">Yes</label></td>
                                            <td><label><input type="radio" name="patient_discussed2" id="patient_discussedNo2" style="width:20px;height:20px;" value="no">No</label></td>';
                                    }
                                 ?>

                              </tr>

                              <tr>
                                <th>Operation confirmed (plus site)?</th>
                                <?php
                                    if($operation_confirmed2 == 'yes')
                                    {
                                      echo '<td><label><input type="radio" name="operation_confirmed2" id="operation_confirmedYes2" style="width:20px;height:20px;" value="yes" checked="true">Yes</label></td>
                                            <td><label><input type="radio" name="operation_confirmed2" id="operation_confirmedNo2" style="width:20px;height:20px;" value="no">No</label></td>';
                                    }
                                    else if($operation_confirmed2 == 'no')
                                    {
                                      echo '<td><label><input type="radio" name="operation_confirmed2" id="operation_confirmedYes2" style="width:20px;height:20px;" value="yes">Yes</label></td>
                                            <td><label><input type="radio" name="operation_confirmed2" id="operation_confirmedNo2" style="width:20px;height:20px;" value="no" checked="true">No</label></td>';
                                    }
                                    else{
                                      echo '<td><label><input type="radio" name="operation_confirmed2" id="operation_confirmedYes2" style="width:20px;height:20px;" value="yes">Yes</label></td>
                                            <td><label><input type="radio" name="operation_confirmed2" id="operation_confirmedNo2" style="width:20px;height:20px;" value="no">No</label></td>';
                                    }
                                 ?>

                              </tr>

                              <tr>
                                <th>All equipments available/sterile?</th>
                                <?php
                                  if($equipments_available2 == 'yes')
                                  {
                                    echo '<td><label><input type="radio" name="equipments_available2" id="equipments_availableYes2" style="width:20px;height:20px;" value="yes" checked="true">Yes</label></td>
                                          <td><label><input type="radio" name="equipments_available2" id="equipments_availableNo2" style="width:20px;height:20px;" value="no">No</label></td>';
                                  }
                                  else if($equipments_available2 == 'no')
                                  {
                                    echo '<td><label><input type="radio" name="equipments_available2" id="equipments_availableYes2" style="width:20px;height:20px;" value="yes">Yes</label></td>
                                          <td><label><input type="radio" name="equipments_available2" id="equipments_availableNo2" style="width:20px;height:20px;" value="no" checked="true">No</label></td>';
                                  }
                                  else{
                                    echo '<td><label><input type="radio" name="equipments_available2" id="equipments_availableYes2" style="width:20px;height:20px;" value="yes">Yes</label></td>
                                          <td><label><input type="radio" name="equipments_available2" id="equipments_availableNo2" style="width:20px;height:20px;" value="no">No</label></td>';
                                  }
                                 ?>

                              </tr>
                            </table>
                          </fieldset>
                          <br/>
                          <!-- end scrub team -->


                          <!-- surgeon -->
                          <fieldset>
                            <legend>Surgeon</legend>
                            <table border="1">
                              <tr>
                                <th>Antibiotics needed?(Ward nurse to bring)</th>
                                <?php
                                    if($antibiotics_needed2 == 'yes')
                                    {
                                      echo '<td><label><input type="radio" name="antibiotics_needed2" id="antibiotics_neededYes2" style="width:20px;height:20px;" value="yes" checked="true">Yes</label></td>
                                            <td><label><input type="radio" name="antibiotics_needed2" id="antibiotics_neededNo2" style="width:20px;height:20px;" value="no">No</label></td>';
                                    }
                                    else if($antibiotics_needed2 == 'no')
                                    {
                                      echo '<td><label><input type="radio" name="antibiotics_needed2" id="antibiotics_neededYes2" style="width:20px;height:20px;" value="yes">Yes</label></td>
                                            <td><label><input type="radio" name="antibiotics_needed2" id="antibiotics_neededNo2" style="width:20px;height:20px;" value="no" checked="true">No</label></td>';
                                    }
                                    else{
                                      echo '<td><label><input type="radio" name="antibiotics_needed2" id="antibiotics_neededYes2" style="width:20px;height:20px;" value="yes">Yes</label></td>
                                            <td><label><input type="radio" name="antibiotics_needed2" id="antibiotics_neededNo2" style="width:20px;height:20px;" value="no">No</label></td>';
                                    }
                                 ?>

                              </tr>

                              <tr>
                                <th>Estimated blood loss</th>
                                <td colspan="2"><input type="text" class="form-control" name="estimated_blood_loss2" id="estimated_blood_loss2" value="<?php echo $estimated_blood_loss2;?>"></td>
                              </tr>

                              <tr>
                                <th>Blood transfusion predicted?</th>
                                <?php
                                  if($blood_transfusion2 == 'yes')
                                  {
                                     echo '<td><label><input type="radio" name="blood_transfusion2" id="blood_transfusionYes2" style="width:20px;height:20px;" value="yes" checked="true">Yes</label></td>
                                           <td><label><input type="radio" name="blood_transfusion2" id="blood_transfusionNo2" style="width:20px;height:20px;" value="no">No</label></td>';
                                  }
                                  else if($blood_transfusion2 == 'no')
                                  {
                                    echo '<td><label><input type="radio" name="blood_transfusion2" id="blood_transfusionYes2" style="width:20px;height:20px;" value="yes">Yes</label></td>
                                          <td><label><input type="radio" name="blood_transfusion2" id="blood_transfusionNo2" style="width:20px;height:20px;" value="no" checked="true">No</label></td>';
                                  }
                                  else{
                                    echo '<td><label><input type="radio" name="blood_transfusion2" id="blood_transfusionYes2" style="width:20px;height:20px;" value="yes">Yes</label></td>
                                          <td><label><input type="radio" name="blood_transfusion2" id="blood_transfusionNo2" style="width:20px;height:20px;" value="no">No</label></td>';
                                  }
                                 ?>

                              </tr>

                              <tr>
                                <th>Available?</th>
                                <?php
                                    if($available2  == 'yes')
                                    {
                                      echo '<td><label><input type="radio" name="available2" id="availableYes2" style="width:20px;height:20px;" value="yes" checked="true">Yes</label></td>
                                            <td><label><input type="radio" name="available2" id="availableNo2" style="width:20px;height:20px;" value="no">No</label></td>';
                                    }
                                    else if($available2  == 'no')
                                    {
                                      echo '<td><label><input type="radio" name="available2" id="availableYes2" style="width:20px;height:20px;" value="yes">Yes</label></td>
                                            <td><label><input type="radio" name="available2" id="availableNo2" style="width:20px;height:20px;" value="no" checked="true">No</label></td>';
                                    }
                                    else{
                                      echo '<td><label><input type="radio" name="available2" id="availableYes2" style="width:20px;height:20px;" value="yes">Yes</label></td>
                                            <td><label><input type="radio" name="available2" id="availableNo2" style="width:20px;height:20px;" value="no">No</label></td>';
                                    }
                                 ?>

                              </tr>

                              <tr>
                                <th>Essential imaging displayed?</th>
                                <?php
                                    if($imaging_displayed2 == 'yes')
                                    {
                                      echo '<td><label><input type="radio" name="imaging_displayed2" id="imaging_displayedYes2" style="width:20px;height:20px;" value="yes" checked="true">Yes</label></td>
                                            <td><label><input type="radio" name="imaging_displayed2" id="imaging_displayedNo2" style="width:20px;height:20px;" value="no">No</label></td>';
                                    }
                                    else if($imaging_displayed2 == 'no')
                                    {
                                      echo '<td><label><input type="radio" name="imaging_displayed2" id="imaging_displayedYes2" style="width:20px;height:20px;" value="yes">Yes</label></td>
                                            <td><label><input type="radio" name="imaging_displayed2" id="imaging_displayedNo2" style="width:20px;height:20px;" value="no" checked="true">No</label></td>';
                                    }
                                    else{
                                      echo '<td><label><input type="radio" name="imaging_displayed2" id="imaging_displayedYes2" style="width:20px;height:20px;" value="yes">Yes</label></td>
                                            <td><label><input type="radio" name="imaging_displayed2" id="imaging_displayedNo2" style="width:20px;height:20px;" value="no">No</label></td>';
                                    }
                                 ?>

                              </tr>
                            </table>
                          </fieldset>
                          <br/>
                          <!-- end surgeon -->


                          <!-- anaesthetists -->
                          <fieldset>
                            <legend>Anaesthetists</legend>
                            <table border="1">
                              <tr>
                                <th>Pulse oximeter/machine/drugs ready?</th>
                                <?php
                                    if($pulse_oximeter2 == 'yes')
                                    {
                                      echo '<td><label><input type="radio" name="pulse_oximeter2" id="pulse_oximeterYes2" style="width:20px;height:20px;" value="yes" checked="true">Yes</label></td>
                                            <td><label><input type="radio" name="pulse_oximeter2" id="pulse_oximeterNo2" style="width:20px;height:20px;" value="no">No</label></td>';
                                    }
                                    else if($pulse_oximeter2 == 'no')
                                    {
                                      echo '<td><label><input type="radio" name="pulse_oximeter2" id="pulse_oximeterYes2" style="width:20px;height:20px;" value="yes">Yes</label></td>
                                            <td><label><input type="radio" name="pulse_oximeter2" id="pulse_oximeterNo2" style="width:20px;height:20px;" value="no" checked="true">No</label></td>';
                                    }
                                    else{
                                      echo '<td><label><input type="radio" name="pulse_oximeter2" id="pulse_oximeterYes2" style="width:20px;height:20px;" value="yes">Yes</label></td>
                                            <td><label><input type="radio" name="pulse_oximeter2" id="pulse_oximeterNo2" style="width:20px;height:20px;" value="no">No</label></td>';
                                    }
                                 ?>

                              </tr>


                              <tr>
                                <th>Aspiration/airway risk?</th>
                                <?php
                                    if($aspiration2  == 'yes')
                                    {
                                      echo '<td><label><input type="radio" name="aspiration2" id="aspirationYes2" style="width:20px;height:20px;" value="yes" checked="true">Yes</label></td>
                                            <td><label><input type="radio" name="aspiration2" id="aspirationNo2" style="width:20px;height:20px;" value="no">No</label></td>';
                                    }
                                    else if($aspiration2  == 'no')
                                    {
                                      echo '<td><label><input type="radio" name="aspiration2" id="aspirationYes2" style="width:20px;height:20px;" value="yes">Yes</label></td>
                                            <td><label><input type="radio" name="aspiration2" id="aspirationNo2" style="width:20px;height:20px;" value="no" checked="true">No</label></td>';
                                    }
                                    else{
                                      echo '<td><label><input type="radio" name="aspiration2" id="aspirationYes2" style="width:20px;height:20px;" value="yes">Yes</label></td>
                                            <td><label><input type="radio" name="aspiration2" id="aspirationNo2" style="width:20px;height:20px;" value="no">No</label></td>';
                                    }
                                 ?>

                              </tr>

                              <tr>
                                <th>Analgesia (eg morphine 0.1 mg/kg,pethidine 1 mg/kg)?</th>
                                <?php
                                    if($analgesia_morphine2 == 'yes')
                                    {
                                      echo '<td><label><input type="radio" name="analgesia_morphine2" id="analgesia_morphineYes2" style="width:20px;height:20px;" value="yes" checked="true">Yes</label></td>
                                            <td><label><input type="radio" name="analgesia_morphine2" id="analgesia_morphineNo2" style="width:20px;height:20px;" value="no">No</label></td>';
                                    }
                                    else if($analgesia_morphine2 == 'no')
                                    {
                                      echo '<td><label><input type="radio" name="analgesia_morphine2" id="analgesia_morphineYes2" style="width:20px;height:20px;" value="yes">Yes</label></td>
                                            <td><label><input type="radio" name="analgesia_morphine2" id="analgesia_morphineNo2" style="width:20px;height:20px;" value="no" checked="true">No</label></td>';
                                    }
                                    else{
                                      echo '<td><label><input type="radio" name="analgesia_morphine2" id="analgesia_morphineYes2" style="width:20px;height:20px;" value="yes">Yes</label></td>
                                            <td><label><input type="radio" name="analgesia_morphine2" id="analgesia_morphineNo2" style="width:20px;height:20px;" value="no">No</label></td>';
                                    }
                                 ?>

                              </tr>
                            </table>
                          </fieldset>
                          <br/>
                          <!-- end anaesthetists -->


                          <!-- operation finishing -->
                          <fieldset>
                            <legend>Operation Finishing</legend>
                            <table border="1">
                              <tr>
                                <th>Swabs counted?</th>
                                <?php
                                    if($swabs_counted2 == 'yes')
                                    {
                                      echo '<td><label><input type="radio" name="swabs_counted2" id="swabs_countedYes2" style="width:20px;height:20px;" value="yes" checked="true">Yes</label></td>
                                            <td><label><input type="radio" name="swabs_counted2" id="swabs_countedNo2" style="width:20px;height:20px;" value="no">No</label></td>';
                                    }
                                    else if($swabs_counted2 == 'no')
                                    {
                                      echo '<td><label><input type="radio" name="swabs_counted2" id="swabs_countedYes2" style="width:20px;height:20px;" value="yes">Yes</label></td>
                                            <td><label><input type="radio" name="swabs_counted2" id="swabs_countedNo2" style="width:20px;height:20px;" value="no" checked="true">No</label></td>';
                                    }
                                    else{
                                      echo '<td><label><input type="radio" name="swabs_counted2" id="swabs_countedYes2" style="width:20px;height:20px;" value="yes">Yes</label></td>
                                            <td><label><input type="radio" name="swabs_counted2" id="swabs_countedNo2" style="width:20px;height:20px;" value="no">No</label></td>';
                                    }
                                 ?>

                              </tr>


                              <tr>
                                <th>(scrub team) Equipment problems addressed?</th>
                                <?php
                                    if($equipment_problems2 == 'yes')
                                    {
                                      echo '<td><label><input type="radio" name="equipment_problems2" id="equipment_problemsYes2" style="width:20px;height:20px;" value="yes" checked="true">Yes</label></td>
                                            <td><label><input type="radio" name="equipment_problems2" id="equipment_problemsNo2" style="width:20px;height:20px;" value="no">No</label></td>';
                                    }
                                    else if($equipment_problems2 == 'no')
                                    {
                                      echo '<td><label><input type="radio" name="equipment_problems2" id="equipment_problemsYes2" style="width:20px;height:20px;" value="yes">Yes</label></td>
                                            <td><label><input type="radio" name="equipment_problems2" id="equipment_problemsNo2" style="width:20px;height:20px;" value="no" checked="true">No</label></td>';
                                    }
                                    else{
                                      echo '<td><label><input type="radio" name="equipment_problems2" id="equipment_problemsYes2" style="width:20px;height:20px;" value="yes">Yes</label></td>
                                            <td><label><input type="radio" name="equipment_problems2" id="equipment_problemsNo2" style="width:20px;height:20px;" value="no">No</label></td>';
                                    }
                                 ?>

                              </tr>

                              <tr>
                                <th>Operation documented in the records book?</th>
                                <?php
                                    if($operation_documented2 == 'yes')
                                    {
                                      echo '<td><label><input type="radio" name="operation_documented2" id="operation_documentedYes2" style="width:20px;height:20px;" value="yes" checked="true">Yes</label></td>
                                            <td><label><input type="radio" name="operation_documented2" id="operation_documentedNo2" style="width:20px;height:20px;" value="no">No</label></td>';
                                    }
                                    else if($operation_documented2 == 'no')
                                    {
                                      echo '<td><label><input type="radio" name="operation_documented2" id="operation_documentedYes2" style="width:20px;height:20px;" value="yes">Yes</label></td>
                                            <td><label><input type="radio" name="operation_documented2" id="operation_documentedNo2" style="width:20px;height:20px;" value="no" checked="true">No</label></td>';
                                    }
                                    else{
                                      echo '<td><label><input type="radio" name="operation_documented2" id="operation_documentedYes2" style="width:20px;height:20px;" value="yes">Yes</label></td>
                                            <td><label><input type="radio" name="operation_documented2" id="operation_documentedNo2" style="width:20px;height:20px;" value="no">No</label></td>';
                                    }
                                 ?>

                              </tr>

                              <tr>
                                <th>(surgeon/anaesthetist) Any patient concerns?</th>
                                <?php
                                  if($any_patient_concerns2 == 'yes')
                                  {
                                    echo '<td><label><input type="radio" name="any_patient_concerns2" id="any_patient_concernsYes2" style="width:20px;height:20px;" value="yes" checked="true">Yes</label></td>
                                          <td><label><input type="radio" name="any_patient_concerns2" id="any_patient_concernsNo2" style="width:20px;height:20px;" value="no">No</label></td>';
                                  }
                                  else if($any_patient_concerns2 == 'no')
                                  {
                                    echo '<td><label><input type="radio" name="any_patient_concerns2" id="any_patient_concernsYes2" style="width:20px;height:20px;" value="yes">Yes</label></td>
                                          <td><label><input type="radio" name="any_patient_concerns2" id="any_patient_concernsNo2" style="width:20px;height:20px;" value="no" checked="true">No</label></td>';
                                  }
                                  else{
                                    echo '<td><label><input type="radio" name="any_patient_concerns2" id="any_patient_concernsYes2" style="width:20px;height:20px;" value="yes">Yes</label></td>
                                          <td><label><input type="radio" name="any_patient_concerns2" id="any_patient_concernsNo2" style="width:20px;height:20px;" value="no">No</label></td>';
                                  }
                                 ?>

                              </tr>
                            </table>
                          </fieldset>
                          <br/>
                          <!-- end operation finishing  -->

                          <fieldset>
                            <legend>Recovery</legend>
                            <table border="1">
                              <tr>
                                <th>Handover to ward staff</th>
                                <?php
                                  if($handover_to_ward2 == 'yes')
                                  {
                                    echo '<td><label><input type="radio" name="handover_to_ward2" id="handover_to_wardYes2" style="width:20px;height:20px;" value="yes" checked="true">Yes</label></td>
                                          <td><label><input type="radio" name="handover_to_ward2" id="handover_to_wardNo2" style="width:20px;height:20px;" value="no">No</label></td>';
                                  }
                                  else if($handover_to_ward2 == 'no')
                                  {
                                    echo '<td><label><input type="radio" name="handover_to_ward2" id="handover_to_wardYes2" style="width:20px;height:20px;" value="yes">Yes</label></td>
                                          <td><label><input type="radio" name="handover_to_ward2" id="handover_to_wardNo2" style="width:20px;height:20px;" value="no" checked="true">No</label></td>';
                                  }
                                  else{
                                    echo '<td><label><input type="radio" name="handover_to_ward2" id="handover_to_wardYes2" style="width:20px;height:20px;" value="yes">Yes</label></td>
                                          <td><label><input type="radio" name="handover_to_ward2" id="handover_to_wardNo2" style="width:20px;height:20px;" value="no">No</label></td>';
                                  }
                                 ?>

                              </tr>
                            </table>
                          </fieldset>
                          <br/>
                          <input type="button" class="btn art-button" value="Update" onclick="updateOperation(this.value,hb2.value,allergies2.value,weight2.value,consultant_approved2.value,essential_imaging2.value,team_brief2.value,patient_consent2.value,patient_discussed2.value,operation_confirmed2.value,equipments_available2.value,antibiotics_needed2.value,estimated_blood_loss2.value,blood_transfusion2.value,available2.value,imaging_displayed2.value,pulse_oximeter2.value,aspiration2.value,analgesia_morphine2.value,swabs_counted2.value,equipment_problems2.value,operation_documented2.value,any_patient_concerns2.value,handover_to_ward2.value,Created_date2.value)"> &nbsp;&nbsp;&nbsp;
                          <!-- <input type="button" class="btn art-button" value="Update" onclick="saveOperation(this.value)">&nbsp;&nbsp;&nbsp; -->

                          <a class="art-button-green" href="print_checklist_for_operation.php?checklist_date=<?=$date?>&Registration_ID=<?=$Registration_ID ?>" target='_blank'>Preview</a>
                  <!-- End of modal checklist for operation -->

                  <!-- End of modal  for previous records-->
                   </form>
                 </center>

                  <!-- // ****************************************************************************************************************** -->

             </div>
             <div class="modal-footer" style="background-color:#037CB0;">
               <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
             </div>
           </div>
           <!-- end of modal -->
         </div>
         </div>
   <!-- End modal for  showing previous induction before the operation-->

   <script type="text/javascript">

     function updateOperation(act,hb2,allergies2,weight2,consultant_approved2,essential_imaging2,team_brief2,patient_consent2,patient_discussed2,operation_confirmed2,equipments_available2,antibiotics_needed2,estimated_blood_loss2,blood_transfusion2,available2,imaging_displayed2,pulse_oximeter2,aspiration2,analgesia_morphine2,swabs_counted2,equipment_problems2,operation_documented2,any_patient_concerns2,handover_to_ward2,Created_date2)
     {
       //alert(allergies2)

       var action = act;
       var Created_date = Created_date2;
       var allergies = allergies2;
       var weight = weight2;

       var estimated_blood_loss2 = estimated_blood_loss2;
       var hb2 = hb2;
       var consultation_ID = $("#consultation_ID2").val();
       var Patient_Payment_Item_List_ID2 = $("#Patient_Payment_Item_List_ID2").val();
       var Admision_ID = $("#Admision_ID2").val();
       var Registration_ID = $("#Registration_ID2").val();
       var Employee_ID = $("#Employee_ID2").val();
       var blood_transfusion = blood_transfusion2;
       var essential_imaging = essential_imaging2;
       var consultant_approved = consultant_approved2;
       var team_brief = team_brief2;
       var patient_consent = patient_consent2;
       var patient_discussed = patient_discussed2;
       var operation_confirmed = operation_confirmed2;
       var equipments_available = equipments_available2;
       var antibiotics_needed = antibiotics_needed2;
       var available = available2;
       var imaging_displayed = imaging_displayed2;
       var pulse_oximeter = pulse_oximeter2;
       var aspiration = aspiration2;
       var analgesia_morphine = analgesia_morphine2;
       var swabs_counted = swabs_counted2;
       var equipment_problems = equipment_problems2;
       var operation_documented = operation_documented2;
       var any_patient_concerns = any_patient_concerns2;
       var handover_to_ward = handover_to_ward2;

       var swabs_counted2 = swabs_counted2;
       var equipment_problems2 = equipment_problems2;
       var operation_documented2 = operation_documented2;
       var any_patient_concerns2 = any_patient_concerns2;
       var handover_to_ward2 = handover_to_ward2;


       if(confirm('Are sure want to '+action+' this?'))
       {

         $.ajax({
         url:"update_checklist_for_operation.php",
         type:"POST",
         data:{
           swabs_counted2:swabs_counted2,
            equipment_problems2:equipment_problems2,
            operation_documented2:operation_documented2,
            any_patient_concerns2:any_patient_concerns2,
            handover_to_ward2:handover_to_ward2,
            blood_transfusion:blood_transfusion,
            estimated_blood_loss2:estimated_blood_loss2,
            team_brief:team_brief,
            patient_consent:patient_consent,
            patient_discussed:patient_discussed,
            operation_confirmed:operation_confirmed,
            equipments_available:equipments_available,
            antibiotics_needed:antibiotics_needed,
            available:available,
            imaging_displayed:imaging_displayed,
            pulse_oximeter:pulse_oximeter,
            aspiration:aspiration,
            analgesia_morphine:analgesia_morphine,
            equipment_problems:equipment_problems,
            operation_documented:operation_documented,
            any_patient_concerns:any_patient_concerns,
            handover_to_ward:handover_to_ward,
            consultation_ID:consultation_ID,
            Admision_ID:Admision_ID,
            Registration_ID:Registration_ID,
            Employee_ID:Employee_ID,
            Patient_Payment_Item_List_ID2:Patient_Payment_Item_List_ID2,
            Created_date:Created_date,
            hb2:hb2,
            consultant_approved:consultant_approved,
            essential_imaging:essential_imaging,
            allergies:allergies,
            weight:weight,
            "action":action
          },
         success:function(data){
            alert(data)
            location.reload(true);
            console.log(data);
          }
       })

       }

     }
   </script>
