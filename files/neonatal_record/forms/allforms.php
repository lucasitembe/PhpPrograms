<?php
include('../../includes/connection.php');

// class Forms{

  function getUsername($id)
  {
      $sql = "select * from tbl_patient_registration where Registration_ID =".$id."";
      $Patient_Name = "";
      $error = "No record found";

      // if (!$conn) {
      //   die("Connection failed: ".mysqli_connect_error());
      // }

      $query = mysqli_query($conn, $sql);

      if (mysqli_num_rows($query) > 0)
      {
          while ($row = mysqli_fetch_assoc($query))
          {
               $Patient_Name =  $row['Patient_Name'];
          }
          return $Patient_Name;
      }
      else{
         return $error;
      }

  }


  //Func return observation form
  function postnatalObservationForm()
  {
    return'      <form  action="index.html" method="post">
              <fieldset style="width:70%;margin-left:200px;margin-right:100px;">
                  <legend align="center" style="font-weight:bold">OBSERVATION</legend>
                  <table>
                    <!-- row1 -->
                    <tr>
                      <td>BT(°C)</td>
                      <td>
                        <input type="text" class="form-control"  name="" value="">
                      </td>

                      <td>PR(b/min)</td>
                      <td>
                        <input type="text" class="form-control"  name="" value="">
                      </td>
                    </tr>
                    <!-- row2 -->

                    <tr>
                      <td>RR(br/min)</td>
                      <td>
                        <input type="text" class="form-control"  name="" value="">
                      </td>

                      <td align="right">BP(mmHg)</td>
                      <td>
                        <input type="text" class="form-control"  name="" value="">
                      </td>
                    </tr>

                    <!-- row3 -->
                    <tr>
                      <td>Pale(Ø/ √)</td>
                      <td>
                        <input type="text" class="form-control"  name="" value="">
                      </td>
                      <td>Breast secrete enough milk</td>
                      <td>
                        <input type="text" class="form-control"  name="" value="">
                      </td>
                    </tr>

                    <!-- row4 -->
                    <tr>
                      <td>Uterus well contracted</td>
                      <td>
                        <input type="text" class="form-control"  name="" value="">
                      </td>
                      <td>PV bleeding</td>
                      <td>
                        <input type="text" class="form-control"  name="" value="">
                      </td>
                    </tr>

                    <!-- row5 -->
                    <tr>
                      <td>General condition</td>
                      <td>
                        <input type="text" class="form-control"  name="" value="">
                      </td>
                      <td>Checked by (name)</td>
                      <td>
                        <input type="text" class="form-control"  name="" value="">
                      </td>
                    </tr>

                    <!-- row6 -->
                    <tr>
                      <td>Date&Time</td>
                      <td>
                        <input type="text" class="form-control"  name="" class="form-control input-label date">

                      </td>
                    </tr>
                    <!-- PLAN SECTION -->

                        <tr>

                          <td colspan="3">
                              Plan:<br>
                              <textarea name="plan" class="form-control"  rows="5" cols="100">
                              Write something in here...
                              </textarea>
                              <br><br>
                              <input type="submit" class="art-button-green" name="" value="Save">
                          </td>
                        </tr>

                          <!-- End of plan -->

                  </table>
              </fieldset>
          </form>';
  }


  //Func return postnatal checklist form
  function postnatalChecklistForm($consultation_id,$id)
  {
    $Patient_Name = getUsername($id);
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
               <td>Patient name: <input type="text" class="form-control"  name="patientName" value="'.$Patient_Name.'"</td>
               <td>FILE No:<input type="text" class="form-control"  name="fileNumber"></td>
               <td colspan="3">Referral from <input type="text"class="form-control"  name="referalForm"></td>
             </tr>

             <!-- row3 -->
             <tr>
               <td>Age: <input type="text" class="form-control" name="age"></td>
               <td>Parity: <input type="text" class="form-control"  name="parity"></td>
               <td colspan="3">Living <input type="text" class="form-control"  name="living"></td>
             </tr>

             <!-- row4 -->
             <tr>
               <td>Sex M <input type="radio" name="gender" value="male">&nbsp;
                 F <input type="radio" name="gender" value="female">
               </td>
               <td style="width:15%;">Address: <input type="text" class="form-control" name="address"></td></td>
               <td colspan="3">
                 PMTCT&nbsp;1
                 <input type="checkbox" name="1" value="1">&nbsp;2
                 <input type="checkbox" name="2" value="2">
               </td>
             </tr>

             <!-- row5 -->
             <tr>
               <td colspan="2" style="width:15%;">Date of delivery <input type="text" name="deliveryDate" class="form-control input-label date"></td>
               <td colspan="3">
                 NIVERAPINE    <input type="text" class="form-control"  name="niverapine"><br/><br/>
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
               <td><input type="text" class="form-control"  name="niverapine"></td>
             </tr>
             <tr>
               <td>temp (\'C)</td>
               <td><input type="text" class="form-control"  name="niverapine"></td>
             </tr>
             <tr>
               <td>pulse (b/min)</td>
               <td><input type="text" class="form-control"  name="niverapine"></td>
             </tr>
             <tr>
                <td>rest rate (br/min)</td>
                <td><input type="text" class="form-control"  name="niverapine"></td>
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
               <td><input type="checkbox" name="temp" value="alert">&nbsp;Alert</td>
               <td><input type="checkbox" name="pulse" value="weak">&nbsp;Weak(restlessness)</td>
            </tr>
             <!-- row3 -->
             <tr>
               <td><b>Hx of Fever</b></td>
               <td><input type="checkbox" name="temp" value="yes">&nbsp;Yes</td>
               <td><input type="checkbox" name="pulse" value="no">&nbsp;No</td>
             </tr>
             <!-- row4 -->
             <tr>
               <td><b>Pallor</b></td>
               <td><input type="checkbox" name="temp" value="yes">&nbsp;Yes</td>
               <td><input type="checkbox" name="pulse" value="not Pale">&nbsp;Not pale</td>
             </tr>
             <!-- row5 -->
             <tr>
               <td><b>Pain</b></td>
               <td><input type="checkbox" name="temp" value="yes">&nbsp;Yes</td>
               <td><input type="checkbox" name="pulse" value="no">&nbsp;No</td>
            </tr>
             <!-- row6 -->
             <tr>
               <td colspan="3">
                 <b>Any complains:</b><br>
                    <textarea name="complains" class="form-control"  rows="5" cols="100">
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
                 <input type="checkbox" name="nipples" value="normal">&nbsp;Normal&nbsp;
                 <input type="checkbox" name="nipples" value="flat">&nbsp;Flat&nbsp;
                 <input type="checkbox" name="nipples" value="flat">&nbsp;Engorged
               </td>
               <td rowspan="2" colspan="2" style="width:35%;">
                 <b>Breast secrete milk:</b>
                 <input type="checkbox" name="BreastSecreteMilk" value="enough milk">&nbsp;Enough milk&nbsp;
                 <input type="checkbox" name="BreastSecreteMilk" value="not enough">&nbsp;Not enough&nbsp;
                 <input type="checkbox" name="BreastSecreteMilk" value="not secrete milk">&nbsp;Not secrete milk
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
                    <input type="checkbox" name="uteras" value="well contracted">&nbsp;well contracted&nbsp;
                    <input type="checkbox" name="uteras" value="not contracted">&nbsp;Not contracted
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
                    <input type="checkbox" name="perinealPad" value="soaked">&nbsp;Soaked&nbsp;
                    <input type="checkbox" name="perinealPad" value="normal">&nbsp;Normal
                  </td>
                </tr>
                <!-- row2 -->
                <tr>
                  <td><b>Pv bleeding</b></td>
                  <td colspan="3">
                    <input type="checkbox" name="pvBleeding" value="slight">&nbsp;Slight&nbsp;
                    <input type="checkbox" name="pvBleeding" value="heavy bleeding">&nbsp;Heavy bleeding
                  </td>
                </tr>
                <!-- row3 -->
                <tr>
                  <td><b>Source of bleeding </b></td>
                  <td colspan="3">
                    <input type="checkbox" name="sourceOfBleeding" value="perineal tear">&nbsp;perineal tear ----><b>Type</b>&nbsp;
                    <input type="checkbox" name="perinealTearType" value="cervical tear ">&nbsp;cervical tear &nbsp;
                    <input type="checkbox" name="perinealTearType" value="un-contracted uterus">&nbsp;un-contracted uterus
                  </td>
                </tr>
                <!-- row4 -->
                <tr>
                  <td colspan="5">
                    <b>Estimated blood loss</b> <input type="text" class="form-control"  name="bloadLoss" style="width:200px;">(mls)&nbsp;&nbsp;
                    <br><br>
                    <b> Interpretation</b> <input type="text" class="form-control"  name="interpretation" style="width:200px;">
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
                    <textarea name="complications"  class="form-control" rows="3" cols="100">
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
                 <textarea name="aditionalFindings"  class="form-control" rows="3" cols="100">
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
