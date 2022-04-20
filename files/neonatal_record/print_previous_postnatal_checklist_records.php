<?php
include("../includes/connection.php");
include("../MPDF/mpdf.php");
require_once'forms/assets.php';



if ($_GET['Registration_ID']) {
  $registration_id = $_GET['Registration_ID'];
  $employee_ID = $_GET['Employee_ID'];
  $delivery_year = $_GET['delivery_year'];

}

$htm ='<style media="screen">
  th{
    background-color: #006400;
    color: white;
  }
  table, th, td {
    border: 1px solid black;
  }
</style>';

$htm .= '<link rel="stylesheet" href="forms/styles.css"/>';
$htm .= "<center><img src='../branchBanner/branchBanner.png' width='100%' ></center>";


$sql_delivery_date = mysqli_query($conn,"select Date_Time_Of_Delivery  from tbl_postnatal_after_delivery_records where Registration_ID = $registration_id and YEAR(Date_Time_Of_Delivery) = '".$delivery_year."' ORDER BY Date_Time_Of_Delivery ASC LIMIT 1");
$Delivery_Date1 = mysqli_fetch_assoc($sql_delivery_date)['Date_Time_Of_Delivery'];
$Delivery_Date = date_format(date_create($Delivery_Date1),"Y-M-d H:i");

$sql_patient_name = mysqli_query($conn,"select Patient_Name  from tbl_patient_registration  where Registration_ID = $registration_id");
$Patient_Name = mysqli_fetch_assoc($sql_patient_name)['Patient_Name'];

$sql_gender= mysqli_query($conn,"select Gender  from tbl_patient_registration  where Registration_ID = $registration_id ");
$Sex = mysqli_fetch_assoc($sql_gender)['Gender'];

$sql_parity = mysqli_query($conn,"select Parity  from tbl_postnatal_after_delivery_records where Parity !='' and Registration_ID = $registration_id and YEAR(Date_Time_Of_Delivery) = '".$delivery_year."' ORDER BY Date_Time_Of_Delivery ASC LIMIT 1");
$Parity = mysqli_fetch_assoc($sql_parity)['Parity'];

$sql_pmtct = mysqli_query($conn,"select Pmtct  from tbl_postnatal_after_delivery_records where Pmtct !='' and Registration_ID = $registration_id and YEAR(Date_Time_Of_Delivery) = '".$delivery_year."' ORDER BY Date_Time_Of_Delivery ASC LIMIT 1");
$Pmtct = mysqli_fetch_assoc($sql_pmtct)['Pmtct'];

$sql_niverapine = mysqli_query($conn,"select Niverapine  from tbl_postnatal_after_delivery_records where Niverapine !='' and Registration_ID = $registration_id and YEAR(Date_Time_Of_Delivery) = '".$delivery_year."' ORDER BY Date_Time_Of_Delivery ASC LIMIT 1");
$Niverapine = mysqli_fetch_assoc($sql_niverapine)['Niverapine'];

$sql_niverapine2 = mysqli_query($conn,"select Niverapine_Time  from tbl_postnatal_after_delivery_records where  Niverapine_Time !='' and Registration_ID = $registration_id  and YEAR(Date_Time_Of_Delivery) = '".$delivery_year."' ORDER BY Date_Time_Of_Delivery ASC LIMIT 1");
$Niverapine_Time = mysqli_fetch_assoc($sql_niverapine2)['Niverapine_Time'];




$sql_living = mysqli_query($conn,"select Living  from tbl_postnatal_after_delivery_records where Living !='' and Registration_ID = $registration_id and YEAR(Date_Time_Of_Delivery) = '".$delivery_year."' ORDER BY Date_Time_Of_Delivery ASC LIMIT 1");
$living = mysqli_fetch_assoc($sql_living)['Living'];

$sql_age = mysqli_query($conn,"select Date_Of_Birth  from  tbl_patient_registration  where Registration_ID = $registration_id");
$Birth_Date = mysqli_fetch_assoc($sql_age)['Date_Of_Birth'];
$date = date_format(date_create($Birth_Date),"Y");
$today_date = date("Y");
$diff=$today_date - $date;
$Age = $diff;


$year = date_format(date_create($Delivery_Date1),"Y");
$month = date_format(date_create($Delivery_Date1),"m");
$day = date_format(date_create($Delivery_Date1),"d");




$htm .= '<center>
 <table border="1" class="table">
      <tr>
        <!-- td1 for the patient details table -->
        <td style="width:45%;">
            <fieldset>
                <legend>PATIENT\'S DETAILS</legend>
                <table border="1">
                  <!-- row1 patient name-->
                  <tr>
                    <td><b>Patient Name</b></td>
                    <td style="width:85%;">'.$Patient_Name.'</td>
                  </tr>
                  <!-- row2 file no-->
                  <tr>
                    <td> <b>File No.</b></td>
                    <td>'.$registration_id.'</td>
                  </tr>
                  <!-- row3 sex-->
                  <tr>
                    <td><b>Sex</b></td>
                    <td>'.$Sex.'</td>
                  </tr>
                  <!-- row4 age-->
                  <tr>
                    <td><b>Age</b></td>
                    <td>'.$Age.'</td>
                  </tr>
                  <!-- row5 Living-->
                  <tr>
                    <td><b>Living</b></td>
                    <td>'.$living.'</td>
                  </tr>
                  <!-- row6 address-->

                  <!-- row7 Parity-->
                  <tr>
                    <td><b>Parity</b></td>
                    <td>'.$Parity.'</td>
                  </tr>
                  <!-- row8 pmtct1-->
                  <tr>
                    <td><b>PMTCT</b></td>
                    <td>'.$Pmtct.'</td>
                  </tr>
                  <!-- row9 Niverapine-->
                  <tr>
                    <td><b>Niverapine</b></td>
                    <td>'.$Niverapine.'</td>
                  </tr>
                  <!-- row10 Time-->
                  <tr>
                    <td><b>Time</b></td>
                    <td>'.$Niverapine_Time.'</td>
                  </tr>
                  <!-- row11 -->
                  <tr>
                    <td><b>Date of Delivery</b></td>
                    <td>'.$Delivery_Date.'</td>
                  </tr>
                </table>
            </fieldset><br><br>
            <!-- End of PATIENT\'S DETAILS -->

            <!-- GENENERAL MATERNAL CONDITION -->
            <fieldset>
              <legend>GENENERAL MATERNAL CONDITION</legend>
              <table border="1">
                 <tr>
                   <th>Physical appearance</th><th>Pallor</th><th>Pain</th><th>Any complains</th>
                 </tr>';

                     $sql_Physical_Appearance = mysqli_query($conn,"select Physical_Appearance  from tbl_postnatal_after_delivery_records where Physical_Appearance !='' and Registration_ID = $registration_id and YEAR(Date_Time_Of_Delivery) = '".$delivery_year."' ORDER BY Date_Time_Of_Delivery ASC LIMIT 1");
                     $Physical_Appearance = mysqli_fetch_assoc($sql_Physical_Appearance)['Physical_Appearance'];

                     $sql_Pallor = mysqli_query($conn,"select Pallor  from tbl_postnatal_after_delivery_records where Pallor !='' and  Registration_ID = $registration_id and YEAR(Date_Time_Of_Delivery) = '".$delivery_year."' ORDER BY Date_Time_Of_Delivery ASC LIMIT 1");
                     $Pallor = mysqli_fetch_assoc($sql_Pallor)['Pallor'];

                     $sql_Pain = mysqli_query($conn,"select Pain  from tbl_postnatal_after_delivery_records where Pain !='' and Registration_ID = $registration_id and YEAR(Date_Time_Of_Delivery) = '".$delivery_year."' ORDER BY Date_Time_Of_Delivery ASC LIMIT 1");
                     $Pain = mysqli_fetch_assoc($sql_Pain)['Pain'];

                     $sql_Complains = mysqli_query($conn,"select Complains  from tbl_postnatal_after_delivery_records where Complains !='' and Registration_ID = $registration_id and YEAR(Date_Time_Of_Delivery) = '".$delivery_year."' ORDER BY Date_Time_Of_Delivery ASC LIMIT 1");
                     $Complains = mysqli_fetch_assoc($sql_Complains)['Complains'];

              $htm .= '<tr>
                   <td>'.$Physical_Appearance.'</td>
                   <td>'.$Pallor.'</td>
                   <td>'.$Pain.'</td>
                    <td>'.$Complains.'</td>
                 </tr>
              </table>
            </fieldset><br><br>
            <!-- End of GENENERAL MATERNAL CONDITION -->

            <!--ANY COMPLICATIONS AFTER DELIVERY-->
            <fieldset>
              <legend>ANY COMPLICATIONS AFTER DELIVERY</legend>
              <table border="1">';

                    $sql_Complications = mysqli_query($conn,"select Complications  from tbl_postnatal_after_delivery_records where Complications!='' and Registration_ID = $registration_id and YEAR(Date_Time_Of_Delivery) = '".$delivery_year."' ORDER BY Date_Time_Of_Delivery ASC LIMIT 1");
                    $Complications = mysqli_fetch_assoc($sql_Complications)['Complications'];

              $htm .= '<tr>
                  <td>'.$Complications.'</td>
                </tr>
              </table>
            </fieldset><br><br>
            <!-- End of ANY COMPLICATIONS AFTER DELIVERY -->


            <!-- PLAN -->

            <fieldset>
              <legend>PLAN</legend>
              <table border="1">';

                    $sql_Plan = mysqli_query($conn,"select Plan  from tbl_postnatal_after_delivery_records where Plan !='' and Registration_ID = $registration_id and YEAR(Date_Time_Of_Delivery) = '".$delivery_year."' ORDER BY Date_Time_Of_Delivery ASC LIMIT 1");
                    $Plan1= mysqli_fetch_assoc($sql_Plan)['Plan'];

              $htm .= ' <tr>
                  <td>'.$Plan1.'</td>
                </tr>
              </table>
            </fieldset>

            <!--  End of  PLAN -->


        </td>
        <!-- End of td1 -->

        <!-- td2 for the labour ward table -->
        <td>

          <fieldset>
              <legend>LABOUR HISTORY</legend>
              <table border="1">';


                $sql_baby_condition = mysqli_query($conn,"select Baby_Condition  from tbl_postnatal_after_delivery_records where Baby_Condition !='' and Registration_ID = $registration_id and YEAR(Date_Time_Of_Delivery) = '".$delivery_year."' ORDER BY Date_Time_Of_Delivery ASC LIMIT 1");
                $Baby_Condition = mysqli_fetch_assoc($sql_baby_condition)['Baby_Condition'];

                $sql_any_abnormalities= mysqli_query($conn,"select Any_Abnormalities  from tbl_postnatal_after_delivery_records where Any_Abnormalities !='' and Registration_ID = $registration_id and YEAR(Date_Time_Of_Delivery) = '".$delivery_year."' ORDER BY Date_Time_Of_Delivery ASC LIMIT 1");
                $any_abnormalities = mysqli_fetch_assoc($sql_any_abnormalities)['Any_Abnormalities'];

                // $sql_any_abnormalities= mysqli_query($conn,"select Any_Abnormalities  from tbl_postnatal_after_delivery_records where Registration_ID = $registration_id and YEAR(Date_Time_Of_Delivery) = '".$delivery_year."' ORDER BY Date_Time_Of_Delivery ASC LIMIT 1");
                // $any_abnormalities = mysqli_fetch_assoc($sql_any_abnormalities)['Any_Abnormalities'];

                $sql_apgar_score = mysqli_query($conn,"select Apgar_Score  from tbl_postnatal_after_delivery_records  where Apgar_Score !='' and Registration_ID = $registration_id and YEAR(Date_Time_Of_Delivery) = '".$delivery_year."' ORDER BY Date_Time_Of_Delivery ASC LIMIT 1");
                $Apgar_Score  = mysqli_fetch_assoc($sql_apgar_score)['Apgar_Score'];

                $sql_bwt = mysqli_query($conn,"select bwt  from tbl_postnatal_after_delivery_records where bwt !='' and Registration_ID = $registration_id and YEAR(Date_Time_Of_Delivery) = '".$delivery_year."' ORDER BY Date_Time_Of_Delivery ASC LIMIT 1");
                $bwt  = mysqli_fetch_assoc($sql_bwt)['bwt'];

                $sql_mode_of_delivery = mysqli_query($conn,"select mode_of_delivery  from tbl_postnatal_after_delivery_records where mode_of_delivery !='' and Registration_ID = $registration_id and YEAR(Date_Time_Of_Delivery) = '".$delivery_year."' ORDER BY Date_Time_Of_Delivery ASC LIMIT 1");
                $mode_of_delivery  = mysqli_fetch_assoc($sql_mode_of_delivery)['mode_of_delivery'];



                $htm .= '<!-- row1 Mode of Delivery-->
                <tr>
                  <td><b>Mode of Delivery</b></td>
                  <td style="width:85%;">'.$mode_of_delivery.'</td>
                </tr>
                <tr>
                  <td><b>Baby\'s Condition</b></td>
                  <td style="width:85%;">'.$Baby_Condition.'</td>
                </tr>
                <!-- row2 Any Abnormalities-->
                <tr>
                  <td><b>Any Abnormalities</b></td>
                  <td>'.$any_abnormalities.'</td>
                </tr>
                <!-- row3 APGAR Score-->
                <tr>
                  <td><b>APGAR Score</b></td>
                  <td>'.$Apgar_Score.'</td>
                </tr>
                <!-- row4 BWT-->
                <tr>
                  <td><b>BWT</b></td>
                  <td>'.$bwt.'</td>
                </tr>
              </table>

            </fieldset><br><br>


                        <fieldset>
                            <legend>PHYSICAL EXAMINATION</legend>
                            <table border="1">';


                              //echo $year." ".$month." ".$day;
                              $sql_fh = mysqli_query($conn,"select fh  from tbl_postnatal_after_delivery_records where fh != 0 and Registration_ID = $registration_id and YEAR(Date_Time_Of_Delivery) = '$delivery_year' and MONTH(Date_Time_Of_Delivery) = '$month' and DAY(Date_Time_Of_Delivery) = '$day'");
                              $fh = mysqli_fetch_assoc($sql_fh)['fh'];

                              $sql_wound= mysqli_query($conn,"SELECT wound FROM tbl_postnatal_after_delivery_records WHERE wound != '' AND Registration_ID = $registration_id and YEAR(Date_Time_Of_Delivery) = '$delivery_year' and MONTH(Date_Time_Of_Delivery) = '$month' and DAY(Date_Time_Of_Delivery) = '$day'");
                              $wound = mysqli_fetch_assoc($sql_wound)['wound'];



                            $htm .= ' <!-- row1 FH-->
                              <tr>
                                <td><b>FH(Cm)</b></td>
                                <td style="width:90%;">'.$fh.'</td>
                              </tr>
                              <!-- row2 WOUND-->
                              <tr>
                                <td><b>C/S wound</b></td>
                                <td>'.$wound.'</td>
                              </tr>

                            </table>

                          </fieldset><br><br>
                          <!-- End of PHYSICAL EXAMINATION -->


                          <!-- PERINEAL ASSESSMENT-->
                          <fieldset>
                            <legend>PERINEAL ASSESSMENT</legend>
                            <table border="1">
                               <tr>
                                 <th>Perineal pad</th><th>Pv bleeding</th><th>Source of bleeding</th><th>Estimated blood loss (mls)</th><th>Interpretation:</th>
                               </tr>';

                                   $sql_Perineal_Pad = mysqli_query($conn,"select Perineal_Pad  from tbl_postnatal_after_delivery_records where Registration_ID = $registration_id and YEAR(Date_Time_Of_Delivery) = '".$delivery_year."' ORDER BY Date_Time_Of_Delivery ASC LIMIT 1");
                                   $Perineal_Pad = mysqli_fetch_assoc($sql_Perineal_Pad)['Perineal_Pad'];

                                   $sql_Pv_Bleeding = mysqli_query($conn,"select Pv_Bleeding  from tbl_postnatal_after_delivery_records where Registration_ID = $registration_id and YEAR(Date_Time_Of_Delivery) = '".$delivery_year."' ORDER BY Date_Time_Of_Delivery ASC LIMIT 1");
                                   $Pv_Bleeding = mysqli_fetch_assoc($sql_Pv_Bleeding)['Pv_Bleeding'];

                                   $sql_Source_Of_Bleeding = mysqli_query($conn,"select Source_Of_Bleeding  from tbl_postnatal_after_delivery_records where Registration_ID = $registration_id and YEAR(Date_Time_Of_Delivery) = '".$delivery_year."' ORDER BY Date_Time_Of_Delivery ASC LIMIT 1");
                                   $Source_Of_Bleeding = mysqli_fetch_assoc($sql_Source_Of_Bleeding)['Source_Of_Bleeding'];

                                   $sql_Estimated_Blood_Loss = mysqli_query($conn,"select Estimated_Blood_Loss  from tbl_postnatal_after_delivery_records where Estimated_Blood_Loss !='' and Registration_ID = $registration_id and YEAR(Date_Time_Of_Delivery) = '".$delivery_year."' ORDER BY Date_Time_Of_Delivery ASC LIMIT 1");
                                   $Estimated_Blood_Loss = mysqli_fetch_assoc($sql_Estimated_Blood_Loss)['Estimated_Blood_Loss'];

                                   $sql_Interpretation = mysqli_query($conn,"select Interpretation  from tbl_postnatal_after_delivery_records where Interpretation !='' and  Registration_ID = $registration_id and YEAR(Date_Time_Of_Delivery) = '".$delivery_year."' ORDER BY Date_Time_Of_Delivery ASC LIMIT 1");
                                   $Interpretation = mysqli_fetch_assoc($sql_Interpretation)['Interpretation'];



                              $htm .= ' <tr>
                                 <td>'.$Perineal_Pad.'</td>
                                 <td>'.$Pv_Bleeding.'</td>
                                 <td>'.$Source_Of_Bleeding.'</td>
                                 <td>'.$Estimated_Blood_Loss.'</td>
                                 <td>'.$Interpretation.'</td>
                               </tr>
                            </table>
                          </fieldset><br><br>
                          <!-- End of PERINEAL ASSESSMENT-->

                          <!-- ADDITIONAL FINDINGS -->

                          <fieldset>
                            <legend>ADDITIONAL FINDINGS</legend>
                            <table boder="1">';

                                  $sql_Additional_Findings = mysqli_query($conn,"select Additional_Findings  from tbl_postnatal_after_delivery_records where Additional_Findings !='' and Registration_ID = $registration_id and YEAR(Date_Time_Of_Delivery) = '".$delivery_year."' ORDER BY Date_Time_Of_Delivery ASC LIMIT 1");
                                  $Additional_Findings = mysqli_fetch_assoc($sql_Additional_Findings)['Additional_Findings'];

                            $htm .= ' <tr>
                                <td>'.$Additional_Findings.'</td>
                              </tr>
                            </table>
                          </fieldset>

                          <!--  End of ADDITIONAL FINDINGS -->

        </td>
        <!-- End of td2 -->
      </tr>

      <!-- OBSERVATION SECTION -->
      <tr>
        <td colspan="2">

          <!-- OBSERVATION SECTION -->
          <fieldset style="width:100%;margin-left:200px;margin-right:100px;">
             <legend align="center" style="font-weight:bold">OBSERVATION CHART(After Delivery)</legend>
          <!-- <div style="width:20%; margin-left:50px;"> -->
               <table border="1" class="table table-striped table-hover">
            <!-- row2 -->
            <tr>

              <th>Date&Time</th>
              <th>BT(°C)</th>
              <th>PR(b/min)</th>
              <th>RR(br/min)</th>
              <th>BP(mmHg)</th>
              <th>Pale(Ø/ √)</th>
              <th>Breast secrete enough milk</th>
              <th>Uterus well contracted</th>
              <th>PV bleeding</th>
              <th>General condition</th>
              <th>Plan</th>
              <th>Checked by (name)</th>
            </tr>
            <!-- row3 -->

            <!-- select p.Registration_ID,p.Date_Time_Of_Delivery,p.temp,p.pulse,p.Rest_Rate, p.bp,p.Pallor,p.Breast_Secrete_Milk,p.Uteras,p.Pv_Bleeding,p.Baby_Condition,p.Employee_ID,e.Employee_ID,e.Employee_Name FROM
            tbl_postnatal_after_delivery_records p INNER JOIN tbl_employee e ON p.Employee_ID = e.Employee_ID WHERE p.Registration_ID = \'16829\' AND p.Employee_ID = \'14492\'; -->';


              $select_after = "SELECT p.Registration_ID,p.Date_Time_Of_Delivery,p.temp,p.pulse,p.Rest_Rate, p.bp,p.Pallor,p.Breast_Secrete_Milk,
              p.Uteras,p.Pv_Bleeding,p.Baby_Condition,p.Employee_ID,e.Employee_ID,e.Employee_Name,p.Plan
              FROM tbl_postnatal_after_delivery_records p
              INNER JOIN tbl_employee e  ON p.Employee_ID = e.Employee_ID
              INNER JOIN tbl_patient_registration pt ON pt.Registration_ID = p.Registration_ID
              WHERE p.Registration_ID = '".$registration_id."' AND  YEAR(Date_Time_Of_Delivery) = '$delivery_year'";
              $query_after = mysqli_query($conn, $select_after);
                   while($row = mysqli_fetch_assoc($query_after))
                   {
                         $htm .= '
                           <tr>
                         <td style="background-color:LightGray;" >'.$row['Date_Time_Of_Delivery'].'</td>
                         <td>'.$row['temp'].'</td>
                         <td>'.$row['pulse'].'</td>
                         <td>'.$row['Rest_Rate'].'</td>
                         <td>'.$row['bp'].'</td>
                         <td>'.$row['Pallor'].'</td>
                         <td>'.$row['Breast_Secrete_Milk'].'</td>
                         <td>'.$row['Uteras'].'</td>
                         <td>'.$row['Pv_Bleeding'].'</td>
                         <td>'.$row['Baby_Condition'].'</td>
                         <td>'.$row['Plan'].'</td>
                         <td>'.$row['Employee_Name'].'</td>

                          </tr>
                         ';
                   }


         $htm .='</table>
         <!-- </div> -->
         </fieldset>
          <!-- End of observation -->
          <!-- End of observation -->
        </td>
      </tr>

      <!-- URINE SECTION -->
      <tr>
        <td colspan="2">

          <fieldset style="width:100%;margin-left:200px;margin-right:100px;">
             <legend align="center" style="font-weight:bold">URINE OUTPUT MONITORING DETAILS</legend>
          <!-- <div style="width:20%; margin-left:50px;"> -->
               <table border="1" class="table table-striped table-hover">


            <!-- row2 -->
            <tr>
              <th>DATE and TIME</th>
              <th>AMOUNT</th>
              <th>COLOR</th>
              <th>PLAN</th>
              <th>Checked By(name)</th>

            </tr>
            <!-- row3 -->';


            $sql = "SELECT u.Employee_ID,u.Registration_ID,u.Postnatal_ID,u.amount,u.color,u.plan,u.date_and_time,em.Employee_ID,em.Employee_Name
                    FROM tbl_postnatal_urine_output_monitoring u
                    INNER JOIN tbl_employee em
                    ON u.Employee_ID = em.Employee_ID
                    WHERE u.Registration_ID = '".$registration_id."' AND YEAR(date_and_time) = '$delivery_year'";

               $query = mysqli_query($conn,$sql);

               while($r = mysqli_fetch_assoc($query))
               {
                 $htm .= '<tr>
                       <td style="background-color:LightGray;">'.$r['date_and_time'].'</td>
                       <td>'.$r['amount'].'</td>
                       <td>'.$r['color'].'</td>
                       <td>'.$r['plan'].'</td>
                       <td>'.$r['Employee_Name'].'</td>
                       </tr>';
               }




      $htm .=' </table>
        <!-- </div> -->
      </fieldset>

        </td>
      </tr>
        <!-- End of observation -->

        <tr>
          <td colspan="2">
            <!-- FLUIDS SECTION -->
            <fieldset style="width:100%;margin-left:200px;margin-right:100px;">
               <legend align="center" style="font-weight:bold">FLUIDS AND BLOOD TRANSFUSION DETAILS</legend>
            <!-- <div style="width:20%; margin-left:50px;"> -->
                 <table border="1" class="table table-striped table-hover">


              <!-- row2 -->
              <tr>
                <th>Date&Time</th>
                <th>BFLUID GIVEN</th>
                <th>MLS</th>
                <th>PLAN</th>
                <th>CHECKED By(name)</th>

              </tr>
              <!-- row3 -->';


                 // $sql = "SELECT f.Employee_ID,f.Registration_ID,f.Postnatal_ID,f.bfluid,pt.Registration_ID,f.mils,f.plan,f.date_and_time,e.Employee_ID,e.Employee_Name
                 //         FROM tbl_postnatal_fluids_and_blood_transfusion f
                 //         INNER JOIN tbl_employee e
                 //         ON f.Employee_ID = e.Employee_ID
                 //          INNER JOIN tbl_postnatal_after_delivery_records pt ON pt.Registration_ID = u.Registration_ID
                 //         WHERE f.Registration_ID = '".$registration_id."' AND YEAR(date_and_time) = '$delivery_year'";

                 $sql = "SELECT f.Employee_ID,f.Registration_ID,f.Postnatal_ID,f.bfluid,f.mils,f.plan,f.date_and_time,e.Employee_ID,e.Employee_Name
                         FROM tbl_postnatal_fluids_and_blood_transfusion f
                         INNER JOIN tbl_employee e
                         ON f.Employee_ID = e.Employee_ID
                         -- INNER JOIN tbl_postnatal_after_delivery_records p
                         -- ON p.Registration_ID = f.Registration_ID
                         WHERE f.Registration_ID = '".$registration_id."' AND YEAR(date_and_time) = '$delivery_year'";



                 $query = mysqli_query($conn,$sql);

                 while($r = mysqli_fetch_assoc($query))
                 {
                   $htm .= '<tr>
                         <td style="background-color:LightGray;">'.$r['date_and_time'].'</td>
                         <td>'.$r['bfluid'].'</td>
                         <td>'.$r['mils'].'</td>
                         <td>'.$r['plan'].'</td>
                         <td>'.$r['Employee_Name'].'</td>
                         </tr>';
                 }




            $htm .= '</table>
              <!-- </div> -->
            </fieldset>


          </td>
        </tr>
        <!-- End of observation -->
 </table>
</center>';




$mpdf = new mPDF('s', 'A4');
$mpdf->SetDisplayMode('fullpage');
$mpdf->SetFooter('Printed By '.strtoupper($employee_Name3).'|Page {PAGENO} Of {nb}|{DATE d-m-Y} Powered By GPITG');
$mpdf->WriteHTML($htm);
$mpdf->Output();
exit;

 ?>
