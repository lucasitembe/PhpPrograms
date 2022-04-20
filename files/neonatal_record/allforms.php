<?php
include('../includes/connection.php');

//class Forms{

  // function priviewPostnatalRecordsModal()
  // {
  //   return '';
  // }

  function postnatalVitalSign2hrsAfterDeliveryForm()
  {
    return '<center>
    <form action="" method="post">
      <!--  VITAL SIGN EXAMINATION SECTION-->
      <fieldset style="width:70%;margin-left:200px;margin-right:100px;">
          <legend align="center" style="font-weight:bold">VITAL SIGN EXAMINATION (After delivery)</legend>

          <table>

      <!-- row1 -->
      <tr>
        <td>BP (mmHg)</td>
        <td><input type="text" class="form-control"  name="bp" id="bd"></td>
        <td>temp (\'C)</td>
        <td><input type="text" class="form-control"  name="temp" id="temp"></td>
      </tr>

      <tr>
        <td>pulse (b/min)</td>
        <td><input type="text" class="form-control"  name="pulse" id="pulse"></td>
        <td>rest rate (br/min)</td>
        <td><input type="text" class="form-control"  name="restRate" id="restRate"></td>
      </tr>

    </table>
  </fieldset>
  <!-- End of vital sig after delivery -->



                  <!--  PHYSICAL EXAMINATION-->
                  <fieldset>
                     <legend align="center" style="font-weight:bold">PHYSICAL EXAMINATION</legend>
                         <table width="1000px">
                           <tr>
                             <td><b>FH (Cm):</b><input type="text" class="form-control"  name="fh" id="fh"></td>
                             <td>
                               <b>P/A- uterus:</b> &nbsp;&nbsp;
                               <input type="checkbox" name="uteras" value="well contracted" id="uteraswell">&nbsp;well contracted<br>
                               <input type="checkbox" name="uteras" value="not contracted" id="uterasnot">&nbsp;Not contracted<br>
                             </td>
                             <td>
                               <b>C/S wound:</b> &nbsp;
                               <input type="checkbox" name="wound" value="intact" id="woundIntact">&nbsp;Intact<br>
                               <input type="checkbox" name="wound" value="gaping" id="woundGaping">&nbsp;Gaping<br>
                               <input type="checkbox" name="wound" value="Bleeding from incision site" id="woundBleeding">&nbsp;Bleeding from incision site<br>
                             </td>
                           </tr>
                       </table>
                  </fieldset>


                  <!--  BREAST EXAMINATION-->
                  <fieldset style="width:70%;margin-left:200px;margin-right:100px;">
                     <legend align="center" style="font-weight:bold">BREAST EXAMINATION</legend>
                         <table width="1000px">
                           <tr>
                             <td><b>Nipples:</b> &nbsp;&nbsp;</td>
                             <td><input type="checkbox" name="nipples" value="normal" id="nipplesNormal">&nbsp;Normal<br>
                             <input type="checkbox" name="nipples" value="flat" id="nipplesFlat">&nbsp;Flat<br>
                             <input type="checkbox" name="nipples" value="engorge" id="nipplesEngorge">&nbsp;Engorged<br></td>
                             <td>
                               <b>Breast secrete milk:</b> &nbsp;&nbsp;
                               <input type="checkbox" name="breastSecreteMilk" value="enough milk" id="breastSecreteMilkE">&nbsp;Enough milk<br>
                               <input type="checkbox" name="breastSecreteMilk" value="not secrete milk" id="breastSecreteMilkNotS">&nbsp;Not secrete milk<br>
                               <input type="checkbox" name="breastSecreteMilk" value="not enough" id="breastSecreteMilkNotE">&nbsp;Not enough<br>
                               <br>
                               <input type="submit" class="art-button-green" name="vitalSign2hrs" value="Save">
                             </td>
                           </tr>
                         </table>
                  </fieldset>
    </center></form>';
  }
  //Func return observation form
  function fluidsAndBloodTransfusionForm($employee_ID,$registration_id)
  {
    return'
         <form  action="" method="post">
              <fieldset style="width:70%;margin-left:200px;margin-right:100px;">
                  <legend align="center" style="font-weight:bold">OBSERVATION</legend>
                  <table>
                    <!-- row1 -->
                    <tr>
                      <td>BT(°C)</td>
                      <td>
                        <input type="text" class="form-control"  name="bt" id="bt" value="">
                      </td>


                    </tr>
                    <!-- row2 -->

                    <tr>
                      <td>RR(br/min)</td>
                      <td>
                        <input type="text" class="form-control"  name="rr" id="rr" value="">
                      </td>

                      <td align="right">BP(mmHg)</td>
                      <td>
                        <input type="text" class="form-control"  name="bp" id="bp" value="">
                      </td>
                    </tr>

                    <!-- row3 -->
                    <tr>
                      <td>Pale(Ø/ √)</td>
                      <td>
                        <input type="text" class="form-control"  name="pale" id="pale" value="">
                      </td>
                      <td>Breast secrete enough milk</td>
                      <td>
                        <input type="text" class="form-control"  name="breastSecreteEnoughMilk" id="breastSecreteEnoughMilk" value="">
                      </td>
                    </tr>

                    <!-- row4 -->
                    <tr>
                      <td>Uterus well contracted</td>
                      <td>
                        <input type="text" class="form-control"  name="uterusWellContracted" id="uterusWellContracted" value="">
                      </td>
                      <td>PV bleeding</td>
                      <td>
                        <input type="text" class="form-control"  name="pvBleeding" id="pvBleeding" value="">
                      </td>
                    </tr>

                    <!-- row5 -->
                    <tr>
                      <td>General condition</td>
                      <td>
                        <input type="text" class="form-control"  name="generalCondition" id="generalCondition" value="">
                      </td>
                      <td>Checked by (name)</td>
                      <td>
                        <input type="text" class="form-control"  name="checkBy" id="checkBy" value="">
                      </td>
                    </tr>

                    <!-- row6 -->
                    <tr>
                      <td>Date&Time</td>
                      <td>
                        <input type="text"   name="dateAndTime" id="dateAndTime" class="form-control input-label date">

                      </td>
                    </tr>
                    <!-- PLAN SECTION -->

                        <tr>

                          <td colspan="3">
                              Plan:<br>
                              <textarea name="plan" class="form-control" id="plan"  rows="5" cols="100">
                              Write something in here...
                              </textarea>
                              <br><br>
                              <input type="submit" class="art-button-green" name="observationPost" value="Save">
                          </td>
                        </tr>

                          <!-- End of plan -->

                  </table>
              </fieldset>
          </form>
          ';
  }


  //Func return postnatal checklist form
  function postnatalChecklistForm($id)
  {

    return '<center>

        <form action="index.html" method="post">
    <fieldset style="width:70%;margin-left:200px;margin-right:100px;">
        <legend align="center" style="font-weight:bold">POSTNATAL CHECKLIST</legend>
        <table>
          <!-- Patient Details -->

             <!-- PATIENT DETAILS SECTION -->
             <!-- row1 -->
             <tr>
               <td colspan="3" align="center"><b>PATIENT DETAILS</b></td>
             </tr>

             <!-- row2 -->
             <tr>
               <td>Patient name: <input type="text" class="form-control"  name="patientName" id="patientName" value="'.$Patient_Name.'" disabled></td>
               <td>FILE No:<input type="text" class="form-control"  name="registrationId" id="registrationId" disabled></td>
               <td colspan="3">Referral from <input type="text"class="form-control"  name="referalForm" id="registrationId"></td>
             </tr>

             <!-- row3 -->
             <tr>
               <td>Age: <input type="text" class="form-control" name="age" id="age"></td>
               <td>Parity: <input type="text" class="form-control"  name="parity" id="parity"></td>
               <td colspan="3">Living <input type="text" class="form-control"  name="living" id="living"></td>
             </tr>

             <!-- row4 -->
             <tr>
               <td>Sex M <input type="radio" name="gender" value="male">&nbsp;
                 F <input type="radio" name="gender" value="female">
               </td>
               <td style="width:15%;">Address: <input type="text" class="form-control" name="address" id="address"></td></td>
               <td colspan="3">
                 PMTCT&nbsp;1
                 <input type="checkbox" name="pmtct" value="1" id="pmtct1">&nbsp;2
                 <input type="checkbox" name="pmtct" value="2" id="pmtc2">
               </td>
             </tr>

             <!-- row5 -->
             <tr>
               <td colspan="2" style="width:15%;">Date of delivery <input type="text" name="deliveryDate" id="deliveryDate" class="form-control input-label date"></td>
               <td colspan="3">
                 NIVERAPINE    <input type="text" class="form-control"  name="niverapine" id="niverapine"><br/><br/>
                 Time <input type="text" class="form-control"  name="niverapineTime">
               </td>

             </tr>
           </table>
         </fieldset>
             <!-- End of patient details -->

             <!--  VITAL SIGN EXAMINATION SECTION-->
             <fieldset style="width:70%;margin-left:200px;margin-right:100px;">
                 <legend align="center" style="font-weight:bold">VITAL SIGN EXAMINATION (After delivery)</legend>
                 <table>
             <!-- row1 -->
             <tr>
               <td>BP (mmHg)</td>
               <td><input type="text" class="form-control"  name="bp" id="bp"></td>
             </tr>
             <tr>
               <td>temp (\'C)</td>
               <td><input type="text" class="form-control"  name="temp" id="temp"></td>
             </tr>
             <tr>
               <td>pulse (b/min)</td>
               <td><input type="text" class="form-control"  name="pulse" id="pulse"></td>
             </tr>
             <tr>
                <td>rest rate (br/min)</td>
                <td><input type="text" class="form-control"  name="restRate"></td>
             </tr>
             <!-- row2 -->
           </table>
         </fieldset>
         <!-- End of vital sig after delivery -->




         <!--  GENENERAL MATERNAL CONDITION-->
         <fieldset style="width:70%;margin-left:200px;margin-right:100px;">
             <legend align="center" style="font-weight:bold">GENENERAL MATERNAL CONDITION</legend>
             <table>
             <tr>
               <!-- <td rowspan="5"><b>GENENERAL MATERNAL CONDITION<b></td> -->
               <td><b>Physical appearance</b></td>
               <td><input type="checkbox" name="physicalAppearance" id="physicalAppearanceA" value="alert">&nbsp;Alert</td>
               <td><input type="checkbox" name="physicalAppearance" id="physicalAppearanceW" value="weak">&nbsp;Weak(restlessness)</td>
            </tr>
             <!-- row3 -->
             <tr>
               <td><b>Hx of Fever</b></td>
               <td><input type="checkbox" name="hxOfFever" id="hxOfFeverY" value="yes">&nbsp;Yes</td>
               <td><input type="checkbox" name="hxOfFever" id="hxOfFeverN" value="no">&nbsp;No</td>
             </tr>
             <!-- row4 -->
             <tr>
               <td><b>Pallor</b></td>
               <td><input type="checkbox" name="pallor" value="yes">&nbsp;Yes</td>
               <td><input type="checkbox" name="pallor" value="not Pale">&nbsp;Not pale</td>
             </tr>
             <!-- row5 -->
             <tr>
               <td><b>Pain</b></td>
               <td><input type="checkbox" name="painY" value="yes">&nbsp;Yes</td>
               <td><input type="checkbox" name="painN" value="no">&nbsp;No</td>
            </tr>
             <!-- row6 -->
             <tr>
               <td colspan="3">
                 <b>Any complains:</b><br>
                    <textarea name="complains" id="complains" class="form-control"  rows="5" cols="100">
                    Write something in here...
                    </textarea>
               </td>
             </tr>
           </table>
         </fieldset>
             <!-- End of vital sign examination -->



             <!-- BREAST EXAMINATION SECTION -->
        <fieldset style="width:70%;margin-left:200px;margin-right:100px;">
           <legend align="center" style="font-weight:bold">BREAST EXAMINATION SECTION</legend>
           <table>

             <tr>
               <!--<td rowspan="2"><b>BREAST EXAMINATION</b></td>-->
               <td rowspan="2"><b>Nipples:</b></td>
               <td>
                 <input type="checkbox" name="nipples" id="nipplesN" value="normal">&nbsp;Normal&nbsp;
                 <input type="checkbox" name="nipples" id="nipplesF" value="flat">&nbsp;Flat&nbsp;
                 <input type="checkbox" name="nipples" id="nipplesE" value="engorge">&nbsp;Engorged
               </td>
               <td rowspan="2" colspan="2" style="width:35%;">
                 <b>Breast secrete milk:</b>
                 <input type="checkbox" name="BreastSecreteMilk" id="breastSecreteMilkNotE" value="enough milk">&nbsp;Enough milk&nbsp;
                 <input type="checkbox" name="BreastSecreteMilk" id="breastSecreteMilkNotE" value="not enough">&nbsp;Not enough&nbsp;
                 <input type="checkbox" name="BreastSecreteMilk" id="breastSecreteMilkNotS" value="not secrete milk">&nbsp;Not secrete milk
               </td>
             </tr>
             <tr>

             </tr>
             <!-- End of breast examination section -->
           </table>
         </fieldset>

             <!-- P/A SECTION -->
             <fieldset style="width:70%;margin-left:200px;margin-right:100px;">
                <legend align="center" style="font-weight:bold">P/A </legend>
                <table>
                <!-- row1 -->
                <tr>
                  <td><b>P/A</b></td>
                  <td><b>Uterus</b></td>
                  <td colspan="3">
                    <input type="checkbox" name="uteras" id="uterusWellContracted" value="well contracted">&nbsp;well contracted&nbsp;
                    <input type="checkbox" name="uteras" id="uterasnot" value="not contracted">&nbsp;Not contracted
                  </td>
                </tr>
              </table>
            </fieldset>
             <!-- End of P/A -->

             <!-- PERINEAL ASSESSMENT SECTION -->
             <fieldset style="width:70%;margin-left:200px;margin-right:100px;">
                <legend align="center" style="font-weight:bold">PERINEAL ASSESSMENT</legend>
                <table>
                <!-- row1 -->
                <tr>
                  <!--<td rowspan="4" valign="middle"><b>PERINEAL ASSESSMENT</b></td>-->
                  <td><b>Perineal pad</b></td>
                  <td colspan="3">
                    <input type="checkbox" name="perinealPad" id="perinealPadS" value="soaked">&nbsp;Soaked&nbsp;
                    <input type="checkbox" name="perinealPad" id="perinealPadN" value="normal">&nbsp;Normal
                  </td>
                </tr>
                <!-- row2 -->
                <tr>
                  <td><b>Pv bleeding</b></td>
                  <td colspan="3">
                    <input type="checkbox" name="pvBleeding" id="pvBleedingS" value="slight">&nbsp;Slight&nbsp;
                    <input type="checkbox" name="pvBleeding" id="pvBleedingH" value="heavy bleeding">&nbsp;Heavy bleeding
                  </td>
                </tr>
                <!-- row3 -->
                <tr>
                  <td><b>Source of bleeding </b></td>
                  <td colspan="3">
                    <input type="checkbox" name="sourceOfBleeding" id="sourceOfBleedingPerineal" value="perineal tear">&nbsp;perineal tear ----><b>Type</b>&nbsp;
                    <input type="checkbox" name="perinealTearType" id="perinealTearTypeC" value="cervical tear ">&nbsp;cervical tear &nbsp;
                    <input type="checkbox" name="perinealTearType" id="perinealTearTypeU" value="un-contracted uterus">&nbsp;un-contracted uterus
                  </td>
                </tr>
                <!-- row4 -->
                <tr>
                  <td colspan="5">
                    <b>Estimated blood loss</b> <input type="text" class="form-control"  name="estimatedBloodLoss" id="estimatedBloodLoss" style="width:200px;">(mls)&nbsp;&nbsp;
                    <br><br>
                    <b> Interpretation</b> <input type="text" class="form-control"  name="interpretation" id="interpretation" style="width:200px;">
                  </td>
                </tr>
              </table>
            </fieldset>
             <!-- End of perineal assessment -->


             <!-- ANY COMPLICATIONS AFTER DELIVERY  -->
             <fieldset style="width:70%;margin-left:200px;margin-right:100px;">
                <legend align="center" style="font-weight:bold">ANY COMPLICATIONS AFTER DELIVERY</legend>
                <table>
                <!-- row1 -->
                <tr>
                  <!--<td><b>Any Complications after delivery</b></td>-->
                  <td colspan="4">
                    <textarea name="complications" id="complications"  class="form-control" rows="3" cols="100">
                    Write something about complications after delivery...
                    </textarea>
                  </td>
                </tr>
              </table>
            </fieldset>
             <!-- End of Any Complications after delivery -->

             <!-- ADITIONAL FINDINGS SECTION -->
             <fieldset style="width:70%;margin-left:200px;margin-right:100px;">
                <legend align="center" style="font-weight:bold">ADITIONAL FINDINGS</legend>
                <table>
             <tr>
               <!--<td><b>Aditional findings</b></td>-->
               <td colspan="4">
                 <textarea name="aditionalFindings" id="aditionalFindings"  class="form-control" rows="3" cols="100">
                 Write something something in here...
                 </textarea>
                 <br><br>
                 <center><input type="submit" class="art-button-green" name="" value="Save"></center>
               </td>
             </tr>
           </table>
         </fieldset>
             <!-- End of additionalfindings -->

               </table></fieldset>
     </form>
    </center>
      ';
  }

//}

 ?>
