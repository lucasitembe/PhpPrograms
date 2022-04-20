<?php
include('header.php');
include('../includes/connection.php');
require_once'triage/assets.php';

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




<?php echo Assets::btnBackNewBornTriageChecklist($consultation_id,$employee_ID,$registration_id,$admission_id); ?>

<center>
  <fieldset>
    <legend>ALL NEWBORN TRIAGE CHECKLIST DETAILS</legend>
    <table class="table">
      <th>#</th>
      <th>DELIVERY YEAR</th>

      <?php
        $sn = 1;
        $select_year = mysqli_query($conn,"SELECT DISTINCT(YEAR(delivery_date)) as 'year' FROM tbl_newborn_triage_checklist_records WHERE  YEAR(delivery_date) !='0' and  Registration_ID = $registration_id");

        while($y = mysqli_fetch_assoc($select_year))
        {
           $year = $y['year'];
            echo "
            <tr>
                <td>".$sn."</td>
                <td><a href='previous_newborn_checklist_per_year_records.php?Registration_ID=".$registration_id."&delivery_year=".$year."&Employee_ID=".$employee_ID."&Admision_ID=".$admission_id."&consultation_ID=".$consultation_id."'>".$year."</a></td>
            </tr>";
              $sn++;
        }

       ?>

    </table>
  </fieldset>
</center>

<?php
    include("../includes/footer.php");
?>
