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

$delivery_year = date("Y");


?>
<style media="screen">
  th{
    background-color: #006400;
    color: white;
  }
</style>

<?php echo Assets::btnPostnatalChecklistPreviousRecords($consultation_id,$employee_ID,$registration_id,$admission_id); ?>
<?php echo Assets::btnBackToPostnatal($consultation_id,$employee_ID,$registration_id,$admission_id); ?>

<center>

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
     // $select_after = "SELECT p.Registration_ID,p.Date_Time_Of_Delivery,p.temp,p.pulse,p.Rest_Rate, p.bp,p.Pallor,p.Breast_Secrete_Milk,
     // p.Uteras,p.Pv_Bleeding,p.Baby_Condition,p.Employee_ID,e.Employee_ID,e.Employee_Name,p.Plan
     // FROM tbl_postnatal_after_delivery_records p
     // INNER JOIN tbl_employee e  ON p.Employee_ID = e.Employee_ID
     // INNER JOIN tbl_patient_registration pt ON pt.Registration_ID = p.Registration_ID
     // WHERE p.Registration_ID = '".$registration_id."' AND YEAR(Date_Time_Of_Delivery) = '$delivery_year'";

     $select_after = "SELECT p.Registration_ID,p.Date_Time_Of_Delivery,p.temp,p.pulse,p.Rest_Rate, p.bp,p.Pallor,p.Breast_Secrete_Milk,
     p.Uteras,p.Pv_Bleeding,p.Baby_Condition,p.Employee_ID,e.Employee_ID,e.Employee_Name,p.Plan
     FROM tbl_postnatal_after_delivery_records p
     INNER JOIN tbl_employee e  ON p.Employee_ID = e.Employee_ID
     INNER JOIN tbl_patient_registration pt ON pt.Registration_ID = p.Registration_ID
     WHERE p.Registration_ID = '".$registration_id."' AND  YEAR(Date_Time_Of_Delivery) = '$delivery_year'";

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
 <!-- End of observation --><br><br>






</center>

<?php
    include("../includes/footer.php");
?>
