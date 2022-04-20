<?php
include('header.php');
include('../includes/connection.php');
require_once'allforms.php';
require_once'save_postnatal_record.php';
require_once'forms/assets.php';
//require_once'forms/formstyle.css';

if (isset($_GET['Registration_ID']) && isset($_GET['Admision_ID']) && isset($_GET['consultation_ID'])) {
  $registration_id = $_GET['Registration_ID'];
  $admission_id = $_GET['Admision_ID'];
  $consultation_id = $_GET['consultation_ID'];
  $employee_ID = $_GET['Employee_ID'];
}else{
  header("Location:../../index.php");
}

$sql = "SELECT Postnatal_ID FROM tbl_postnatal_after_delivery_records WHERE Employee_ID = '$employee_ID' OR Registration_ID = '$registration_id' ORDER BY Postnatal_ID DESC LIMIT 1";
$select_postnatalid = mysqli_query($conn,$sql);
$postnatalaId = "";
while($r = mysqli_fetch_assoc($select_postnatalid))
{
  $postnatalaId = $r['Postnatal_ID'];
}

$delivery_year = date("Y");
?>

      <link rel="stylesheet" href="forms/formstyle.css"></link>
        <script src="forms/scripts.js" charset="utf-8"></script>
      <style media="screen">
        th{
          background-color: #006400;
          color: white;
        }
      </style>


    <!-- Func for Back Button -->
    <?php echo Assets::btnBackToPostnatal($consultation_id,$employee_ID,$registration_id,$admission_id);?>

      <center>
        <!-- This is the function return postnatal observation form -->
       <?php //echo fluidsAndBloodTransfusionForm($employee_ID,$registration_id); ?>
       <!-- End of postnatal checklist form -->

       <form   method="post">
            <fieldset style="width:90%;margin-left:200px;margin-right:100px;">
                <legend align="center" style="font-weight:bold">URINE OUTPUT MONITORING</legend>
                <table>
                  <!-- row1 -->
                  <tr>
                    <td>Amount</td>
                    <td>
                      <input type="text" class="form-control"  name="amount" id="amount" value="" required>
                        <input type="hidden" class="form-control"  name="employeeId" id="employeeId" value="<?php echo $employee_ID; ?>">
                        <input type="hidden" class="form-control"  name="registrationId" id="registrationId" value="<?php echo $registration_id; ?>">
                          <input type="hidden" class="form-control"  name="postnatalId" id="postnatalId" value="<?php echo $postnatalaId; ?>">
                          <input type="hidden" name="Admision_ID" class="form-control" id="Admision_ID" value="<?php echo $Admision_ID;?>"/><br>
                          <input type="hidden" name="consultation_id" class="form-control" id="consultation_id" value="<?php echo $consultation_id;?>"/><br>
                    </td>
                  </tr>
                  <!-- row2 -->
                  <tr>
                    <td>Color</td>
                    <td>
                      <input type="text" class="form-control"  name="colord" id="colord" value="" required>
                    </td>


                  <!-- row6 -->
                  <tr>
                    <td>Date&Time</td>
                    <td>
                      <input type="text"   name="dateAndTime" id="dateAndTime" value="" class="form-control input-label date" required>

                    </td>
                  </tr>
                  <!-- PLAN SECTION -->

                      <tr>

                        <td colspan="2">
                            Plan:<br>
                            <textarea name="plan" class="form-control" id="plan" value rows="5" cols="100"></textarea>
                            <br><br>
                            <input type="button" class="art-button-green" onclick="saveUrine(this.value)" value="Save">
                        </td>
                      </tr>

                        <!-- End of plan -->
                </table>
            </fieldset>
        </form>


       <br><br>
       <!-- OBSERVATION SECTION -->
       <fieldset style="width:90%;margin-left:200px;margin-right:100px;">
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
                    WHERE u.Registration_ID = '".$registration_id."' AND YEAR(date_and_time) = '$delivery_year'";

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
       <!-- End of observation -->


      </center>

    <?php echo Assets::js(); ?>
    <?php
        include("../includes/footer.php");
    ?>
