<style>
    .linkstyle{
        color:#3EB1D3;
    }

    .linkstyle:hover{
        cursor:pointer;
    }
</style>
<?php
include("./includes/header.php");
include("./includes/connection.php");
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Management_Works'])) {
        if ($_SESSION['userinfo']['Management_Works'] != 'yes') {
            header("Location: ./index.php?InvalidPrivilege=yes");
        }
    } else {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}

$Date_From = filter_input(INPUT_GET, 'Date_From');
$Date_To = filter_input(INPUT_GET, 'Date_To');
$Sponsor = filter_input(INPUT_GET, 'Sponsor');
$clinic = filter_input(INPUT_GET, 'clinic');
$Employee_ID = filter_input(INPUT_GET, 'Employee_ID');

$filter = " DATE(ch.cons_hist_Date)=DATE(NOW())";
$filterSp = " DATE(ch.cons_hist_Date)=DATE(NOW())";

if (isset($_GET['Sponsor'])) {


    if (isset($Date_To) && !empty($Date_To) && isset($Date_From) && !empty($Date_From)) {
        $filter = "  ch.cons_hist_Date BETWEEN '" . $Date_From . "' AND '" . $Date_To . "'";
        $filterSp = "  ch.cons_hist_Date BETWEEN '" . $Date_From . "' AND '" . $Date_To . "'";
    }

    if (!empty($Sponsor) && $Sponsor != 'All') {
        $filter .="  AND pr.Sponsor_ID=$Sponsor";
        $filterSp .="  AND pr.Sponsor_ID=$Sponsor";
    }
//echo $ward;exit;
    if (!empty($clinic) && $clinic != 'All') {
        $filter .= " AND pl.Clinic_ID IN (SELECT Clinic_ID FROM tbl_clinic_employee ce WHERE ce.Clinic_ID='$clinic' AND ce.Employee_ID =" . $Employee_ID . ")";
    }
}

 $range='<center>';
 
  if($Date_From !='' && $Date_To !=''){
              $range .=" FROM <span class='dates'>".$Date_From."</span> TO <span class='dates'>".$Date_To."</span>";
        }
        if($Sponsor != 'All'){
             $get_sponsor_name=  mysqli_query($conn,"SELECT Guarantor_Name FROM tbl_sponsor WHERE Sponsor_ID='$Sponsor'") or die(mysqli_error($conn));
             $SponsorName=mysqli_fetch_assoc($get_sponsor_name)['Guarantor_Name'];
        
             if($Date_From !='' && $Date_To !=''){
                 $range .="<br/>Sponsor:<span class='dates'>".$SponsorName."</span>";
             }else{
                 $range .="Sponsor:<span class='dates'>".$SponsorName."</span>";
             }
             
        }
        if($clinic != 'All'){
             $get_clinic_name=  mysqli_query($conn,"SELECT Clinic_Name FROM tbl_clinic WHERE Clinic_ID='$clinic'") or die(mysqli_error($conn));
             $Clinic_Name=mysqli_fetch_assoc($get_clinic_name)['Clinic_Name'];
        
             if($Sponsor != 'All'){
                       $range .=" | Clinic Name:<span class='dates'>".$Clinic_Name."</span>";
       
             }else{
                  if($Date_From !='' && $Date_To !=''){
                      $range .="<br/>Clinic Name:<span class='dates'>".$Clinic_Name."</span>";
                  }else{
                      $range .="Clinic Name:<span class='dates'>".$Clinic_Name."</span>";
                  }
       
             }
        }
         $range .="</center>";


$EmployeeName = strtoupper(mysqli_fetch_assoc(mysqli_query($conn,"SELECT Employee_Name FROM tbl_employee WHERE Employee_ID='$Employee_ID'"))['Employee_Name']);
?>
<a target="_blank" href='./printdoctorsclinicperformancedetails.php?Employee_ID=<?php echo $Employee_ID ?>&Date_From=<?php echo $Date_From ?>&Date_To=<?php echo $Date_To ?>&Sponsor=<?php echo $Sponsor ?>&clinic=<?php echo $clinic ?>' class='art-button-green'>
    PRINT
</a>
<?php
echo "<a href='doctorsclinicperformancesummary.php?Date_From=" . $_GET['Date_From'] . "&Date_To=" . $_GET['Date_To'] . "&Sponsor=" . $_GET['Sponsor'] . "&clinic=" . $_GET['clinic'] . "' class='art-button-green'>
        BACK
    </a>
 <br/><br/>";
?>
<br/>
<style>
    .dates{
        color:#cccc00;
    }
</style>
<fieldset style='overflow-y:scroll; height:500px'>
    <br>
    <legend align="center" style="background-color:#037DB0;color: white;padding: 5px;">
        <b id="dateRange" ><?php echo $EmployeeName.'  CLINIC PERFORMANCE REPORT'.$range; ?> </b>
    </legend>
    <?php
    if ($clinic == 'All' || $clinic == '') {

        // $query = "SELECT cl.Clinic_ID,Clinic_Name FROM tbl_clinic cl INNER JOIN tbl_clinic_employee ce ON ce.Clinic_ID=cl.Clinic_ID JOIN tbl_patient_payment_item_list pl ON ce.Clinic_ID=pl.Clinic_ID WHERE ce.Employee_ID ='$Employee_ID'";
        //die($query);
        // $result = mysqli_query($conn,$query) or die(mysqli_error($conn));

// die("SELECT cl.Clinic_ID,Clinic_Name FROM tbl_consultation_history ch 
// LEFT JOIN tbl_consultation c ON c.consultation_ID=ch.consultation_ID 
// JOIN tbl_employee e ON ch.employee_ID=e.employee_ID
// JOIN tbl_patient_payment_item_list pl ON c.Patient_Payment_Item_List_ID=pl.Patient_Payment_Item_List_ID
// JOIN tbl_clinic cl ON cl.Clinic_ID=pl.Clinic_ID
// JOIN tbl_clinic_employee ce ON ce.Clinic_ID=cl.Clinic_ID
// JOIN tbl_patient_registration pr ON pr.Registration_ID = c.Registration_ID
// WHERE  $filterSp AND ch.employee_ID='$Employee_ID' group by cl.Clinic_ID");

        $sql = "SELECT cl.Clinic_ID,Clinic_Name FROM tbl_consultation_history ch 
                                       LEFT JOIN tbl_consultation c ON c.consultation_ID=ch.consultation_ID 
                                       JOIN tbl_employee e ON ch.employee_ID=e.employee_ID
                                       JOIN tbl_patient_payment_item_list pl ON c.Patient_Payment_Item_List_ID=pl.Patient_Payment_Item_List_ID
                                       JOIN tbl_clinic cl ON cl.Clinic_ID=pl.Clinic_ID
                                       JOIN tbl_clinic_employee ce ON ce.Clinic_ID=cl.Clinic_ID
                                       JOIN tbl_patient_registration pr ON pr.Registration_ID = c.Registration_ID
                                       WHERE  $filterSp AND ch.employee_ID='$Employee_ID' group by cl.Clinic_ID";
        $resultcl = mysqli_query($conn,$sql) or die(mysqli_error($conn));

        while ($row = mysqli_fetch_array($resultcl)) {
            $Clinic_ID = $row['Clinic_ID'];
            $Clinic_Name = $row['Clinic_Name'];
            
            echo "<h1 style='margin:10px 0 0 0;width:100%;border-bottom:1px solid #A3A3A3;padding-bottom:5px;text-align:left'>" . ucfirst(strtolower($Clinic_Name)) . "</h1>";
            echo "<table class='patientList' width='100%'> 
                <thead>
                   <tr>
                       <th style='text-align:left;width:5%'>S/n</th>
                       <th style='text-align:left'>PATIENT NAME</th>
                       <th>SPONSOR</th>
                       <th>AGE</th>
                       <th>GENDER</th>
                       <th>PHONE NUMBER</th>
                       <th>MEMBER NUMBER</th>
                       <th>DISTRICT</th>
                       <th>CONSULTED DATE</th>
                   </tr>
                </thead>";

            $sql = "SELECT NOW() as today, pr.Patient_Name,pr.Date_Of_Birth,pr.Gender,pr.Phone_Number,pr.Registration_ID,pr.District,sp.Guarantor_Name,ch.cons_hist_Date FROM tbl_consultation_history ch, tbl_consultation c, tbl_employee e, tbl_patient_payment_item_list pl, tbl_patient_registration pr, tbl_sponsor sp WHERE c.consultation_ID=ch.consultation_ID AND ch.employee_ID=e.employee_ID AND c.Patient_Payment_Item_List_ID=pl.Patient_Payment_Item_List_ID AND  ch.Saved='yes' AND  pr.Registration_ID = c.Registration_ID AND sp.Sponsor_ID = pr.Sponsor_ID AND $filterSp AND pl.Clinic_ID='$Clinic_ID' AND ch.employee_ID='$Employee_ID'";
            //echo($sql).'<br/>';
            $result_patient = mysqli_query($conn,$sql) or die(mysqli_error($conn));
            $sn=1;
            while ($row_patient = mysqli_fetch_array($result_patient)) {
                 $row_patient['Patient_Name'] . '<br/>';
                 $age = floor( (strtotime(date('Y-m-d')) - strtotime($row_patient['Date_Of_Birth'])) / 31556926)." Years";
	   // if($age == 0){
                $Today=$row_patient['today'];
                $date1 = new DateTime($Today);
                $date2 = new DateTime($row_patient['Date_Of_Birth']);
                $diff = $date1 -> diff($date2);
                $age = $diff->y." Years, ";
                $age .= $diff->m." Months, ";
                $age .= $diff->d." Days";
                        
                echo "<tr>"
                         ."<td>".$sn++."</td>"
                         ."<td>".$row_patient['Patient_Name']."</td>"
                         ."<td>".$row_patient['Guarantor_Name']."</td>"
                         ."<td>".$age."</td>"
                        ."<td>".$row_patient['Gender']."</td>"
                         ."<td>".$row_patient['Phone_Number']."</td>"
                         ."<td>".$row_patient['Registration_ID']."</td>"
                         ."<td>".$row_patient['District']."</td>"
                         ."<td>".$row_patient['cons_hist_Date']."</td>"
                   ."</tr>";
            }
            echo "</table>";
        }
    } else {
        $get_clinic_name=  mysqli_query($conn,"SELECT Clinic_Name FROM tbl_clinic WHERE Clinic_ID='$clinic'") or die(mysqli_error($conn));
    
        $Clinic_Name=mysqli_fetch_assoc($get_clinic_name)['Clinic_Name'];
        
        echo "<h1 style='margin:10px 0 0 0;width:100%;border-bottom:1px solid #A3A3A3;padding-bottom:5px;text-align:left'>" . ucfirst(strtolower($Clinic_Name)) . "</h1>";
            echo "<table class='patientList' width='100%'> 
                <thead>
                   <tr>
                       <th style='text-align:left;width:5%'>S/n</th>
                       <th style='text-align:left'>PATIENT NAME</th>
                       <th>SPONSOR</th>
                       <th>AGE</th>
                       <th>GENDER</th>
                       <th>PHONE NUMBER</th>
                       <th>MEMBER NUMBER</th>
                       <th>DISTRICT</th>
                       <th>CONSULTED DATE</th>
                   </tr>
                </thead>";
                
            $sql = "SELECT NOW() as today, pr.Patient_Name,pr.Date_Of_Birth,pr.Gender,pr.Phone_Number,pr.Registration_ID,pr.District,sp.Guarantor_Name,ch.cons_hist_Date FROM tbl_consultation_history ch, tbl_consultation c, tbl_employee e, tbl_patient_payment_item_list pl, tbl_patient_registration pr, tbl_sponsor sp WHERE c.consultation_ID=ch.consultation_ID AND ch.employee_ID=e.employee_ID AND c.Patient_Payment_Item_List_ID=pl.Patient_Payment_Item_List_ID  AND  ch.Saved='yes' AND pr.Registration_ID = c.Registration_ID AND sp.Sponsor_ID = pr.Sponsor_ID AND $filterSp AND pl.Clinic_ID='$clinic' AND ch.employee_ID='$Employee_ID' GROUP BY c.consultation_ID";
            //echo($sql).'<br/>';
            $result_patient = mysqli_query($conn,$sql) or die(mysqli_error($conn));
            $sn=1;
            while ($row_patient = mysqli_fetch_array($result_patient)) {
                 $row_patient['Patient_Name'] . '<br/>';
                 $age = floor( (strtotime(date('Y-m-d')) - strtotime($row_patient['Date_Of_Birth'])) / 31556926)." Years";
	   // if($age == 0){
		$Today=$row_patient['today'];
		$date1 = new DateTime($Today);
		$date2 = new DateTime($row_patient['Date_Of_Birth']);
		$diff = $date1 -> diff($date2);
		$age = $diff->y." Years, ";
		$age .= $diff->m." Months, ";
		$age .= $diff->d." Days";
                
                echo "<tr>"
                         ."<td>".$sn++."</td>"
                         ."<td>".$row_patient['Patient_Name']."</td>"
                         ."<td>".$row_patient['Guarantor_Name']."</td>"
                         ."<td>".$age."</td>"
                        ."<td>".$row_patient['Gender']."</td>"
                         ."<td>".$row_patient['Phone_Number']."</td>"
                         ."<td>".$row_patient['Registration_ID']."</td>"
                         ."<td>".$row_patient['District']."</td>"
                         ."<td>".$row_patient['cons_hist_Date']."</td>"
                   ."</tr>";
            }
            echo "</table>";
    }
    ?>
</fieldset>   

<script type="text/javascript">
    $(document).ready(function () {
        $('.patientList').DataTable({
            "bJQueryUI": true

        });
    });
</script>

<link rel="stylesheet" href="media/css/jquery.dataTables.css" media="screen">
<link rel="stylesheet" href="media/themes/smoothness/dataTables.jqueryui.css" media="screen">
<script src="media/js/jquery.js" type="text/javascript"></script>
<script src="media/js/jquery.dataTables.js" type="text/javascript"></script>
<script src="css/jquery-ui.js"></script>
<?php
    include("./includes/footer.php");
?>

