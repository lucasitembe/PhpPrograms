<?php
include('header.php');
include('../includes/connection.php');
//require_once'forms/db.php';
//require_once'allforms.php';
require_once'save_postnatal_record.php';
require_once'forms/assets.php';


if (isset($_GET['Registration_ID']) && isset($_GET['Admision_ID']) && isset($_GET['consultation_ID'])) {
  $registration_id = $_GET['Registration_ID'];
  $admission_id = $_GET['Admision_ID'];
  $consultation_id = $_GET['consultation_ID'];
  $employee_ID = $_GET['Employee_ID'];
}else{
  header("Location:../../index.php");
}

 $select_name=mysqli_query($conn,"SELECT * from tbl_patient_registration where Registration_ID='$registration_id'");
 $Patient_Name="";
 $address="";
 while($row = mysqli_fetch_assoc($select_name))
 {
   $Patient_Name = $row['Patient_Name'];
   $address = $row['address'];
   $ward = $row['Ward'];
 }
 //echo $Patient_Name;


?>


    <script src="forms/scripts.js" charset="utf-8"></script>
    <link rel="stylesheet" href="triage/styles.css"/>



      <!-- Func for Going to FluidsAndBloodTransfusion Page -->
      <?php echo Assets::btnFluidsAndBloodTransfusion($consultation_id,$employee_ID,$registration_id,$admission_id);?>
      <!-- Func for Going to Vital sign 2hrs After Delivery Page -->
      <?php echo Assets::btnUrineOutputMonitoring($consultation_id,$employee_ID,$registration_id,$admission_id);?>


            <!-- Func for Back Button -->
            <?php echo Assets::btnBackToNeonatalRecords($consultation_id,$employee_ID,$registration_id,$admission_id);?>


                         <!-- This is the function return postnatal checklist form -->
                        <?php //echo postnatalChecklistForm($Patient_Name,$consultation_id,$registration_id); ?>
                        <!-- End of postnatal checklist form -->



                        <center>
                          <form  method="post" id="vitalSignForm">

                        <fieldset style="width:85%;margin-left:200px;margin-right:100px;">
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
                                                  <td>
                                                    Patient name: <input type="text" class="form-control"  name="patientName" value="<?php echo $Patient_Name; ?>" disabled>
                                                    <input type="hidden" class="form-control"  name="employeeId" id="employeeId"  value="<?php echo $employee_ID; ?>">
                                                    <input type="hidden" name="Admision_ID" class="form-control" id="Admision_ID" value="<?php echo $Admision_ID;?>"/><br>
                                                    <input type="hidden" name="consultation_id" class="form-control" id="consultation_id" value="<?php echo $consultation_id;?>"/><br>
                                                  </td>
                                                  <td colspan="2">
                                                    Registration No:<input type="text" class="form-control"  value="<?php echo $registration_id; ?>" disabled>
                                                    <input type="hidden" class="form-control"  name="registrationId" id="registrationId" value="<?php echo $registration_id; ?>">
                                                  </td>
                                                </tr>

                                                <!-- row3 -->
                                                <tr>
                                                  <td>Parity: <input type="text" class="form-control"  name="parity" id="parity"></td>
                                                  <td colspan="3">Living <input type="text" class="form-control"  name="living" id="living"></td>
                                                </tr>

                                                <!-- row4 -->
                                                <tr>
                                                  <td colspan="4">
                                                    PMTCT&nbsp;
                                                    1<input type="checkbox" name="pmtct" value="1" id="pmtct1">&nbsp;
                                                    2<input type="checkbox" name="pmtct" value="2" id="pmtct2">
                                                  </td>
                                                </tr>

                                                <!-- row5 -->
                                                <tr>
                                                  <td style="width:15%;">Date and Time of delivery <input type="text" name="deliveryDateAndTime" id="deliveryDateAndTime" class="form-control input-label date"></td>
                                                  <td>NIVERAPINE    <input type="text" class="form-control"  name="niverapine" id="niverapine"></td>
                                                  <td>Time <input type="text" class="form-control"  name="niverapineTime" id="niverapineTime"></td>

                                                </tr>
                                              </table>
                                            </fieldset>
                                                <!-- End of patient details -->

                                                <!--  LABOUR HISTORY SECTION-->
                                                     <fieldset style="width:85%;margin-left:200px;margin-right:100px;">
                                                         <legend align="center" style="font-weight:bold">LABOUR HISTORY</legend>
                                                         <table>
                                                     <!-- row1 -->
                                                     <tr>
                                                       <td>Baby's Condition:</td>
                                                       <td><input type="text" class="form-control"  name="babyCondition" id="babyCondition"></td>
                                                       <td>Any abnormalities:</td>
                                                       <td><input type="text" class="form-control"  name="anyAbnormalities" id="anyAbnormalities"></td>
                                                     </tr>

                                                     <tr>
                                                       <td>APGAR SCORE:</td>
                                                       <td><input type="text" class="form-control"  name="apgarScore" id="apgarScore"></td>
                                                       <td>BWT:</td>
                                                       <td><input type="text" class="form-control"  name="bwt" id="bwt" ></td>
                                                     </tr>

                                                     <tr>
                                                       <td colspan="4">
                                                          <b>Mode of delivery:</b>
                                                          <input type="checkbox" name="modeOfDelivery" id="svd" value="svd">&nbsp;SVD&nbsp;
                                                          <input type="checkbox" name="modeOfDelivery" id="avd" value="avd">&nbsp;AVD&nbsp;
                                                          <input type="checkbox" name="modeOfDelivery" id="breech" value="breech">&nbsp;Breech&nbsp;&nbsp;
                                                          <input type="checkbox" name="modeOfDelivery" id="cs" value="cs">&nbsp;CS--->
                                                          <b>If CS:</b> &nbsp;
                                                          <input type="checkbox" name="ifcs" id="spinal" value="spinal">&nbsp;Spinal&nbsp;&nbsp;
                                                          <input type="checkbox" name="ifcs" id="generalanaesthesia" value="general anaesthesia">&nbsp;General anaesthesia&nbsp;&nbsp;
                                                          <input type="checkbox" name="ifcs" id="elective" value="elective">&nbsp;Elective&nbsp;&nbsp;
                                                          <input type="checkbox" name="ifcs" id="emergency" value="emergency">&nbsp;Emergency&nbsp;&nbsp;
                                                       </td>

                                                     </tr>

                                                   </table>
                                                 </fieldset>
                                                 <!-- End of vital sig after delivery -->


                                                <!--  VITAL SIGN EXAMINATION SECTION-->
                                                     <fieldset style="width:85%;margin-left:200px;margin-right:100px;">
                                                         <legend align="center" style="font-weight:bold">VITAL SIGN EXAMINATION (After delivery)</legend>
                                                         <table>
                                                     <!-- row1 -->
                                                     <tr>
                                                       <td>BP (mmHg)</td>
                                                       <td><input type="text" class="form-control"  name="bp" id="bp" required></td>
                                                       <td>temp (°C)</td>
                                                       <td><input type="text" class="form-control"  name="temp" id="temp" required></td>
                                                     </tr>

                                                     <tr>
                                                       <td>pulse (b/min)</td>
                                                       <td><input type="text" class="form-control"  name="pulse" id="pulse" required></td>
                                                       <td>rest rate (br/min)</td>
                                                       <td><input type="text" class="form-control"  name="restRate" id="restRate" required></td>
                                                     </tr>

                                                   </table>
                                                 </fieldset>
                                                 <!-- End of vital sig after delivery -->





                             <!--  GENENERAL MATERNAL CONDITION-->
                             <fieldset style="width:85%;margin-left:200px;margin-right:100px;">
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
                                   <td><input type="checkbox" name="pallor" id="pallorYes" value="yes">&nbsp;Yes</td>
                                   <td><input type="checkbox" name="pallor" id="pallorNot" value="not Pale">&nbsp;Not pale</td>
                                 </tr>
                                 <!-- row5 -->
                                 <tr>
                                   <td><b>Pain</b></td>
                                   <td><input type="checkbox" name="pain" id="painY"  value="yes">&nbsp;Yes</td>
                                   <td><input type="checkbox" name="pain" id="painN" value="no">&nbsp;No</td>
                                </tr>
                                 <!-- row6 -->
                                 <tr>
                                   <td colspan="3">
                                     <b>Any complains:</b><br/>
                                        <textarea name="complains" id="complains" class="form-control"  rows="5" cols="100"></textarea>
                                   </td>
                                 </tr>
                               </table>
                             </fieldset>
                                 <!-- End of vital sign examination -->

                                 <!-- PHYSICAL EXAMINATION SECTION -->
                            <fieldset style="width:85%;margin-left:200px;margin-right:100px;">
                               <legend align="center" style="font-weight:bold">
                                 PHYSICAL EXAMINATION SECTION<br/>
                                 <p style="color:red;background-color:white;">Please fill this after 2hrs from first vital signs</p>
                               </legend>
                               <table>

                                 <tr>
                                   <!--<td rowspan="2"><b>BREAST EXAMINATION</b></td>-->
                                   <td rowspan="2"><b>FH(cm):</b></td>
                                   <td><td><input type="text" class="form-control"  name="fh" id="fh"></td>

                                   <td rowspan="2" colspan="2" style="width:35%;">
                                     <b>C/S wound:</b>
                                     <input type="checkbox" name="wound" id="woundIntact" value="intact">&nbsp;Intact&nbsp;
                                     <input type="checkbox" name="wound" id="woundGaping" value="gaping">&nbsp;Gaping&nbsp;
                                     <input type="checkbox" name="wound" id="woundBleeding" value="bleeding from incision site">&nbsp;Bleeding from incision site
                                   </td>
                                 </tr>
                                 <tr>

                                 </tr>
                               </table>
                             </fieldset>
                              <!-- End of breast examination section -->




                                 <!-- BREAST EXAMINATION SECTION -->
                            <fieldset style="width:85%;margin-left:200px;margin-right:100px;">
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
                                     <input type="checkbox" name="BreastSecreteMilk" id="breastSecreteMilkE" value="enough milk">&nbsp;Enough milk&nbsp;
                                     <input type="checkbox" name="BreastSecreteMilk" id="breastSecreteMilkNotE" value="not enough">&nbsp;Not enough&nbsp;
                                     <input type="checkbox" name="BreastSecreteMilk" id="breastSecreteMilkNotS" value="not secrete milk">&nbsp;Not secrete milk
                                   </td>
                                 </tr>
                                 <tr>

                                 </tr>
                               </table>
                             </fieldset>
                              <!-- End of breast examination section -->

                                 <!-- P/A SECTION -->
                                 <fieldset style="width:85%;margin-left:200px;margin-right:100px;">
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
                                 <fieldset style="width:85%;margin-left:200px;margin-right:100px;">
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
                                      <td>
                                        <b>Estimated blood loss (mls):</b> <input type="text" class="form-control"  name="estimatedBloodLoss" id="estimatedBloodLoss" style="width:200px;" >&nbsp;&nbsp;
                                      </td>
                                      <td>
                                        <b> Interpretation:</b> <input type="text" class="form-control"  name="interpretation" id="interpretation" style="width:200px;">
                                      </td>
                                    </tr>
                                  </table>
                                </fieldset>
                                 <!-- End of perineal assessment -->


                                 <!-- ANY COMPLICATIONS AFTER DELIVERY  -->
                                 <fieldset style="width:85%;margin-left:200px;margin-right:100px;">
                                    <legend align="center" style="font-weight:bold">ANY COMPLICATIONS AFTER DELIVERY</legend>
                                    <table>
                                    <!-- row1 -->
                                    <tr>
                                      <!--<td><b>Any Complications after delivery</b></td>-->
                                      <td colspan="4">
                                        <textarea name="complications" id="complications"  class="form-control" rows="3" cols="100"></textarea>
                                      </td>
                                    </tr>
                                  </table>
                                </fieldset>
                                 <!-- End of Any Complications after delivery -->

                                 <!-- PLAN -->
                                 <fieldset style="width:85%;margin-left:200px;margin-right:100px;">
                                    <legend align="center" style="font-weight:bold">PLAN</legend>
                                    <table>
                                    <!-- row1 -->
                                    <tr>
                                      <!--<td><b>Any Complications after delivery</b></td>-->
                                      <td colspan="4">
                                        <textarea name="plan" id="plan"  class="form-control" rows="3" cols="100"></textarea>
                                      </td>
                                    </tr>
                                  </table>
                                </fieldset>
                                 <!-- End of plany -->

                                 <!-- ADITIONAL FINDINGS SECTION -->
                                 <fieldset style="width:85%;margin-left:200px;margin-right:100px;">
                                    <legend align="center" style="font-weight:bold">ADITIONAL FINDINGS</legend>
                                    <table>
                                 <tr>
                                   <!--<td><b>Aditional findings</b></td>-->
                                   <td colspan="4">
                                     <textarea name="aditionalFindings" id="aditionalFindings"  class="form-control" rows="3" cols="100"></textarea>
                                     <br><br>
                                     <center>
                                       <input type="button" class="art-button-green" id="vitalSisignInfo" onclick="savePostnatal(this.value)"  value="Save">&nbsp;&nbsp;

                                           <?php echo Assets::btnPriviewPostnatalRecords($consultation_id,$employee_ID,$registration_id,$admission_id); ?>
                                     </center>
                                   </td>
                                 </tr>
                               </table>

                             </fieldset>
                                 <!-- End of additionalfindings -->



                          </form>

                        </center>

            <?php echo Assets::js(); ?>

            <?php
                include("../includes/footer.php");
            ?>
