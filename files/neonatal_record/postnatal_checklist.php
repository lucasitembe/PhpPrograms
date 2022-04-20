<?php
include('header.php');
include('../includes/connection.php');
//require_once'forms/db.php';
require_once'allforms.php';
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



?>
<style media="screen">
  th{
    background-color: #006400;
    color: white;
  }
</style>

<?php echo Assets::btnPostnatalChecklistPreviousRecords($consultation_id,$employee_ID,$registration_id,$admission_id); ?>
<?php echo Assets::btnBackPriviewPostnatalRecords($consultation_id,$employee_ID,$registration_id,$admission_id); ?>

<?php

  $sql_patient_name = mysqli_query($conn,"select Patient_Name  from tbl_patient_registration  where Registration_ID = $registration_id");
  $Patient_Name = mysqli_fetch_assoc($sql_patient_name)['Patient_Name'];

  $sql_gender= mysqli_query($conn,"select Gender  from tbl_patient_registration  where Registration_ID = $registration_id ");
  $Sex = mysqli_fetch_assoc($sql_gender)['Gender'];

  $sql_parity = mysqli_query($conn,"select Parity  from tbl_postnatal_after_delivery_records where Registration_ID = $registration_id ORDER BY Date_Time_Of_Delivery ASC LIMIT 1");
  $Parity = mysqli_fetch_assoc($sql_parity)['Parity'];

  $sql_pmtct = mysqli_query($conn,"select Pmtct  from tbl_postnatal_after_delivery_records where Registration_ID = $registration_id ORDER BY Date_Time_Of_Delivery ASC LIMIT 1");
  $Pmtct = mysqli_fetch_assoc($sql_pmtct)['Pmtct'];

  $sql_niverapine = mysqli_query($conn,"select Niverapine  from tbl_postnatal_after_delivery_records where Registration_ID = $registration_id ORDER BY Date_Time_Of_Delivery ASC LIMIT 1");
  $Niverapine = mysqli_fetch_assoc($sql_niverapine)['Niverapine'];

  $sql_niverapine2 = mysqli_query($conn,"select Niverapine_Time  from tbl_postnatal_after_delivery_records where Registration_ID = $registration_id ORDER BY Date_Time_Of_Delivery ASC LIMIT 1");
  $Niverapine_Time = mysqli_fetch_assoc($sql_niverapine2)['Niverapine_Time'];

  $sql_delivery_date = mysqli_query($conn,"select Date_Time_Of_Delivery  from tbl_postnatal_after_delivery_records where Registration_ID = $registration_id ORDER BY Date_Time_Of_Delivery ASC LIMIT 1");
  $Delivery_Date1 = mysqli_fetch_assoc($sql_delivery_date)['Date_Time_Of_Delivery'];
  $Delivery_Date = date_format(date_create($Delivery_Date1),"Y-M-d H:i");


  $sql_living = mysqli_query($conn,"select Living  from tbl_postnatal_after_delivery_records where Registration_ID = $registration_id ORDER BY Date_Time_Of_Delivery ASC LIMIT 1");
  $living = mysqli_fetch_assoc($sql_living)['Living'];

  $sql_age = mysqli_query($conn,"select Date_Of_Birth  from  tbl_patient_registration  where Registration_ID = $registration_id");
  $Birth_Date = mysqli_fetch_assoc($sql_age)['Date_Of_Birth'];
  $date = date_format(date_create($Birth_Date),"Y");
  $today_date = date("Y");
  $diff=$today_date - $date;
  $Age = $diff;




 ?>

 <center>
   <table class="table">
        <tr>
          <!-- td1 for the patient details table -->
          <td style="width:45%;">
              <fieldset>
                  <legend>PATIENT'S DETAILS</legend>
                  <table>
                    <!-- row1 patient name-->
                    <tr>
                      <td><b>Patient Name</b></td>
                      <td style="width:85%;"><?php echo $Patient_Name;?></td>
                    </tr>
                    <!-- row2 file no-->
                    <tr>
                      <td> <b>File No.</b></td>
                      <td><?php echo $registration_id;?></td>
                    </tr>
                    <!-- row3 sex-->
                    <tr>
                      <td><b>Sex</b></td>
                      <td><?php echo $Sex;?></td>
                    </tr>
                    <!-- row4 age-->
                    <tr>
                      <td><b>Age</b></td>
                      <td><?php echo $Age;?></td>
                    </tr>
                    <!-- row5 Living-->
                    <tr>
                      <td><b>Living</b></td>
                      <td><?php echo $living;?></td>
                    </tr>
                    <!-- row6 address-->
                    <!-- <tr>
                      <td>Address</td>
                      <td><?php //echo $Patient_Name;?></td>
                    </tr> -->
                    <!-- row7 Parity-->
                    <tr>
                      <td><b>Parity</b></td>
                      <td><?php echo $Parity;?></td>
                    </tr>
                    <!-- row8 pmtct1-->
                    <tr>
                      <td><b>PMTCT</b></td>
                      <td><?php echo $Pmtct;?></td>
                    </tr>
                    <!-- row9 Niverapine-->
                    <tr>
                      <td><b>Niverapine</b></td>
                      <td><?php echo $Niverapine;?></td>
                    </tr>
                    <!-- row10 Time-->
                    <tr>
                      <td><b>Time</b></td>
                      <td><?php echo $Niverapine_Time;?></td>
                    </tr>
                    <!-- row11 -->
                    <tr>
                      <td><b>Date of Delivery</b></td>
                      <td><?php echo $Delivery_Date;?></td>
                    </tr>
                  </table>
              </fieldset>
          </td>
          <!-- End of td1 -->

          <!-- td2 for the labour ward table -->
          <td>

            <fieldset>
                <legend>LABOUR WARD</legend>
                <table>

                  <?php
                  $sql_baby_condition = mysqli_query($conn,"select Baby_Condition  from tbl_postnatal_after_delivery_records where Registration_ID = $registration_id and Date_Time_Of_Delivery = '$Delivery_Date1' ORDER BY Date_Time_Of_Delivery ASC LIMIT 1");
                  $Baby_Condition = mysqli_fetch_assoc($sql_baby_condition)['Baby_Condition'];

                  $sql_any_abnormalities= mysqli_query($conn,"select Any_Abnormalities  from tbl_postnatal_after_delivery_records where Registration_ID = $registration_id and Date_Time_Of_Delivery = '$Delivery_Date1' ORDER BY Date_Time_Of_Delivery ASC LIMIT 1");
                  $any_abnormalities = mysqli_fetch_assoc($sql_any_abnormalities)['Any_Abnormalities'];

                  $sql_any_abnormalities= mysqli_query($conn,"select Any_Abnormalities  from tbl_postnatal_after_delivery_records where Registration_ID = $registration_id and Date_Time_Of_Delivery = '$Delivery_Date1' ORDER BY Date_Time_Of_Delivery ASC LIMIT 1");
                  $any_abnormalities = mysqli_fetch_assoc($sql_any_abnormalities)['Any_Abnormalities'];

                  $sql_apgar_score = mysqli_query($conn,"select Apgar_Score  from tbl_postnatal_after_delivery_records where Registration_ID = $registration_id and Date_Time_Of_Delivery = '$Delivery_Date1' ORDER BY Date_Time_Of_Delivery ASC LIMIT 1");
                  $Apgar_Score  = mysqli_fetch_assoc($sql_apgar_score)['Apgar_Score'];

                  $sql_bwt = mysqli_query($conn,"select bwt  from tbl_postnatal_after_delivery_records where Registration_ID = $registration_id and Date_Time_Of_Delivery = '$Delivery_Date1' ORDER BY Date_Time_Of_Delivery ASC LIMIT 1");
                  $bwt  = mysqli_fetch_assoc($sql_bwt)['bwt'];


                   ?>
                  <!-- row1 Baby's Condition-->
                  <tr>
                    <td><b>Baby's Condition</b></td>
                    <td style="width:85%;"><?php echo $Baby_Condition;?></td>
                  </tr>
                  <!-- row2 Any Abnormalities-->
                  <tr>
                    <td><b>Any Abnormalities</b></td>
                    <td><?php echo $any_abnormalities;?></td>
                  </tr>
                  <!-- row3 APGAR Score-->
                  <tr>
                    <td><b>APGAR Score</b></td>
                    <td><?php echo $Apgar_Score;?></td>
                  </tr>
                  <!-- row4 BWT-->
                  <tr>
                    <td><b>BWT</b></td>
                    <td><?php echo $bwt;?></td>
                  </tr>
                </table>

              </fieldset><br><br>


                          <fieldset>
                              <legend>PHYSICAL EXAMINATION</legend>
                              <table>

                                <?php
                                $year = date_format(date_create($Delivery_Date1),"Y");
                                $month = date_format(date_create($Delivery_Date1),"m");
                                $day = date_format(date_create($Delivery_Date1),"d");

                                //echo $year." ".$month." ".$day;
                                $sql_fh = mysqli_query($conn,"select fh  from tbl_postnatal_after_delivery_records where fh != 0 and Registration_ID = $registration_id and YEAR(Date_Time_Of_Delivery) = '$year' and MONTH(Date_Time_Of_Delivery) = '$month' and DAY(Date_Time_Of_Delivery) = '$day'");
                                $fh = mysqli_fetch_assoc($sql_fh)['fh'];

                                $sql_wound= mysqli_query($conn,"SELECT wound FROM tbl_postnatal_after_delivery_records WHERE wound != '' AND Registration_ID = $registration_id and YEAR(Date_Time_Of_Delivery) = '$year' and MONTH(Date_Time_Of_Delivery) = '$month' and DAY(Date_Time_Of_Delivery) = '$day'");
                                $wound = mysqli_fetch_assoc($sql_wound)['wound'];


                                 ?>
                                <!-- row1 FH-->
                                <tr>
                                  <td><b>FH(Cm)</b></td>
                                  <td style="width:90%;"><?php echo $fh;?></td>
                                </tr>
                                <!-- row2 WOUND-->
                                <tr>
                                  <td><b>C/S wound</b></td>
                                  <td><?php echo $wound;?></td>
                                </tr>

                              </table>

                            </fieldset>

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
                 <table class="table table-striped table-hover">
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
              tbl_postnatal_after_delivery_records p INNER JOIN tbl_employee e ON p.Employee_ID = e.Employee_ID WHERE p.Registration_ID = '16829' AND p.Employee_ID = '14492'; -->

                <?php
                $select_after = "SELECT p.Registration_ID,p.Date_Time_Of_Delivery,p.temp,p.pulse,p.Rest_Rate, p.bp,p.Pallor,p.Breast_Secrete_Milk,
                p.Uteras,p.Pv_Bleeding,p.Baby_Condition,p.Employee_ID,e.Employee_ID,e.Employee_Name,p.Plan
                FROM tbl_postnatal_after_delivery_records p
                INNER JOIN tbl_employee e  ON p.Employee_ID = e.Employee_ID
                INNER JOIN tbl_patient_registration pt ON pt.Registration_ID = p.Registration_ID
                WHERE p.Registration_ID = '".$registration_id."'";
                $query_after = mysqli_query($conn, $select_after);
                     while($row = mysqli_fetch_assoc($query_after))
                     {
                           echo '
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
                 ?>

           </table>
           <!-- </div> -->
           </fieldset>
            <!-- End of observation -->
            <!-- End of observation -->
          </td>
        </tr>

        <!-- OBSERVATION SECTION -->
        <tr>
          <td colspan="2">

            <fieldset style="width:100%;margin-left:200px;margin-right:100px;">
               <legend align="center" style="font-weight:bold">URINE OUTPUT MONITORING DETAILS</legend>
            <!-- <div style="width:20%; margin-left:50px;"> -->
                 <table class="table table-striped table-hover">


              <!-- row2 -->
              <tr>
                <th>DATE and TIME</th>
                <th>AMOUNT</th>
                <th>COLOR</th>
                <th>PLAN</th>
                <th>Checked By(name)</th>

              </tr>
              <!-- row3 -->

              <?php
                 $sql = "SELECT u.Employee_ID,u.Registration_ID,u.Postnatal_ID,u.amount,u.color,u.plan,u.date_and_time,em.Employee_ID,em.Employee_Name
                         FROM tbl_postnatal_urine_output_monitoring u
                         INNER JOIN tbl_employee em
                         ON u.Employee_ID = em.Employee_ID
                         WHERE u.Registration_ID = '".$registration_id."'";

                 $query = mysqli_query($conn,$sql);

                 while($r = mysqli_fetch_assoc($query))
                 {
                   echo '<tr>
                         <td style="background-color:LightGray;">'.$r['date_and_time'].'</td>
                         <td>'.$r['amount'].'</td>
                         <td>'.$r['color'].'</td>
                         <td>'.$r['plan'].'</td>
                         <td>'.$r['Employee_Name'].'</td>
                         </tr>';
                 }


               ?>

          </table>
          <!-- </div> -->
        </fieldset>

          </td>
        </tr>
          <!-- End of observation -->

          <tr>
            <td colspan="2">
              <!-- OBSERVATION SECTION -->
              <fieldset style="width:100%;margin-left:200px;margin-right:100px;">
                 <legend align="center" style="font-weight:bold">FLUIDS AND BLOOD TRANSFUSION DETAILS</legend>
              <!-- <div style="width:20%; margin-left:50px;"> -->
                   <table class="table table-striped table-hover">


                <!-- row2 -->
                <tr>
                  <th>Date&Time</th>
                  <th>BFLUID GIVEN</th>
                  <th>MLS</th>
                  <th>PLAN</th>
                  <th>CHECKED By(name)</th>

                </tr>
                <!-- row3 -->

                <?php
                   $sql = "SELECT f.Employee_ID,f.Registration_ID,f.Postnatal_ID,f.bfluid,f.mils,f.plan,f.date_and_time,e.Employee_ID,e.Employee_Name
                           FROM tbl_postnatal_fluids_and_blood_transfusion f
                           INNER JOIN tbl_employee e
                           ON f.Employee_ID = e.Employee_ID
                           WHERE f.Registration_ID = '".$registration_id."'";

                   $query = mysqli_query($conn,$sql);

                   while($r = mysqli_fetch_assoc($query))
                   {
                     echo '<tr>
                           <td style="background-color:LightGray;">'.$r['date_and_time'].'</td>
                           <td>'.$r['bfluid'].'</td>
                           <td>'.$r['mils'].'</td>
                           <td>'.$r['plan'].'</td>
                           <td>'.$r['Employee_Name'].'</td>
                           </tr>';
                   }


                 ?>

                </table>
                <!-- </div> -->
              </fieldset>


            </td>
          </tr>
          <!-- End of observation -->
   </table>
 </center>
<?php //} ?>




<?php
    include("../includes/footer.php");
?>
