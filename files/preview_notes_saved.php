<script src='js/functions.js'></script>
<style type="text/css">
    /*.labefor{display:block;width: 100% }*/
    .labefor:hover{background-color: #a8d1ff;cursor: pointer}
    label.labefor { width: 100%; 
    }
                #spu_lgn_tbl{
                    width:100%;
                    border:none!important;
                }
                #spu_lgn_tbl tr, #spu_lgn_tbl tr td{
                    border:none!important;
                    padding: 5px;
                    font-size: 14PX;
                }
</style>

<?php
    include_once("./includes/header.php");
    include_once("./includes/connection.php");

    include_once("./functions/department.php");
    include_once("./functions/employee.php");
    
//get nurse duty id
    if (isset($_GET['duty_ID'])) {
        $duty_ID = $_GET['duty_ID'];
    }
$fromDate = $_GET['Date_From'];
$toDate = $_GET['Date_To'];

    //get employee name
    if (isset($_SESSION['userinfo'])) {
        $Employee_Name = $_SESSION['userinfo']['Employee_Name'];
        $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    } else {
        $Employee_Name = 'Unknown Officer';
        $Employee_ID = 0;
    }

    $Sub_Department_Name = $_SESSION['Admission'];

    $sql_date_time = mysqli_query($conn,"select now() as Date_Time ") or die(mysqli_error($conn));
    while($date = mysqli_fetch_array($sql_date_time)){
        $Current_Date_Time = $date['Date_Time'];
    }
    $Filter_Value = substr($Current_Date_Time,0,11);
    $Date_From = $Filter_Value.' 00:00';
    $Date_To = $Current_Date_Time;


    if (isset($_SESSION['Ward_Info'])) {
        $Current_Store_ID = $_SESSION['Ward_Info']['Hospital_Ward_ID'];
        $Current_Store_Name = $_SESSION['Ward_Info']['Hospital_Ward_Name'];
    }
if(!empty($fromDate) && !(empty($toDate))){
    $Date_From = $fromDate;
    $Date_To = $toDate;
}else{
    $Today_Date = mysqli_query($conn,"select now() as today");
    while ($row = mysqli_fetch_array($Today_Date)) {
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
    }


}

$qr = "SELECT * FROM tbl_hospital_ward WHERE Hospital_Ward_Name LIKE  '%$Sub_Department_Name%'";
$ward_results = mysqli_query($conn,$qr);
while ($ward_rows = mysqli_fetch_assoc($ward_results)) {
    $Hospital_Ward_ID = $ward_rows['Hospital_Ward_ID'];
    $Hospital_Ward_Name = $ward_rows['Hospital_Ward_Name'];
    $down_Hospital_Ward_Name = $Hospital_Ward_Name;
}
?>

<a href="completed_nurse_notes.php?NurseDuty=NurseDutyThisForm&frompage=addmission" class='art-button-green'>BACK</a>
<a href="preview_completed_duties.php?Date_From='<?php echo $Date_From ?>'&Date_To='<?php echo $Date_To ?>'&Hospital_Ward_ID='<?php echo $Hospital_Ward_ID ?>'"  target='_blank' style="background: #d40b72; font-weight: bold; border-radius: 2px;" class='art-button-green'>PREVIEW DUTIES NOTES</a>

<?php
// die("SELECT * from tbl_nurse_duties WHERE duty_handled BETWEEN '$Date_From' AND '$Date_To' AND duty_ward = '$Hospital_Ward_ID' ORDER BY Ward_Type, duty_handled ASC");
        $get_details = mysqli_query($conn,"SELECT * from tbl_nurse_duties WHERE duty_handled BETWEEN '$Date_From' AND '$Date_To' AND duty_ward = '$Hospital_Ward_ID' AND Process_Status = 'Submitted' ORDER BY Ward_Type, duty_handled ASC") or die(mysqli_error($conn));
$no = mysqli_num_rows($get_details);
if ($no > 0) {
while ($data2 = mysqli_fetch_array($get_details)) {
    $duty_ID = $data2['duty_ID'];
    $Refugees=$data2['Refugees'];
    $current_nurse=$data2['current_nurse'];
    $duty_nurse=$data2['duty_nurse'];
    $duty_ward=$data2['duty_ward'];
    $Doctor_round=$data2['Doctor_round'];
    $select_round=$data2['select_round'];
    $current_inpatient=$data2['current_inpatient'];
    $received_inpatient=$data2['received_inpatient'];
    $discharged_inpatient=$data2['discharged_inpatient'];
    $death_inpatient=$data2['death_inpatient'];
    $Abscondees=$data2['Abscondees'];
    $debt_inpatient=$data2['debt_inpatient'];
    $transferIn=$data2['transferIn'];
    $transferOut=$data2['transferOut'];
    $lodgers=$data2['lodgers'];
    $major_round=$data2['major_round'];
    $nurse_notes=$data2['nurse_notes'];
    $duty_handled=$data2['duty_handled'];
    $serious_inpatient=$data2['serious_inpatient'];
    $serious_inpatient=$data2['serious_inpatient'];
    $Ward_Type = $data2['Ward_Type'];
    $Refugees = $data2['Refugees'];
    $taarifa = '';

    $select_notes = mysqli_query($conn, "SELECT Nurse_Notes, Updated_Date_Time, Employee_Name FROM tbl_employee em, tbl_nurse_duty_nurse nd WHERE nd.duty_ID = '$duty_ID' AND em.Employee_ID = nd.Employee_ID ORDER BY Notes_ID ASC");
    if(mysqli_num_rows($select_notes)>0){
        while($data = mysqli_fetch_assoc($select_notes)){
            $Employee_Name = $data['Employee_Name'];
            $Nurse_Notes = $data['Nurse_Notes'];
            $Updated_Date_Time = $data['Updated_Date_Time'];

            $details = $Nurse_Notes."


Reported By: ".ucfirst($Employee_Name)."  Time: ".$Updated_Date_Time."
------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------";

        $taarifa .= $details."
        
";

        }   
    }else{
        $taarifa = $nurse_notes;
    }

?>
<form action='' method='GET' name='myForm' id='myForm' >
    <fieldset>
        <legend align='center'><b>NURSING DUTY DETAILS - <span style='color: yellow;'><?php echo strtoupper($Ward_Type); ?> WARD</span> - <span style='color: yellow;'><?php echo strtoupper($select_round); ?></span></b></legend>
        <table  id="spu_lgn_tbl" width=100%>
        <?php
                                                 
                   $employee = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Employee_Name FROM tbl_employee WHERE Employee_ID='$duty_nurse'"))['Employee_Name'];
                   $ward = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Hospital_Ward_Name FROM tbl_hospital_ward WHERE Hospital_Ward_ID='$duty_ward'"))['Hospital_Ward_Name'];
                   $doctor = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Employee_Name FROM tbl_employee WHERE Employee_ID='$Doctor_round'"))['Employee_Name'];
                   if($current_nurse > 0){
                       $current_nurse = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Employee_Name FROM tbl_employee WHERE Employee_ID = '$current_nurse'"))['Employee_Name'];
                   }else{
                       $current_nurse = $current_nurse;
                   }

                                    ?>

            <tr  id="select_clinic">
                <!-- <td width='12%' style='text-align: right;'>Requisition Date</td>
                <td width='16%'>
                   
                </td>  -->
                <td style='text-align: right;'>Nurse On Duty</td>
                <td>
                <?php echo"<input type='text' value='{$current_nurse}'>"?>
                </td>
                <td style='text-align: right;'>Nurse Taking Over</td>
                <td>
                    <?php echo"<input type='text' value='{$employee}'>"?>
                </td>
                <td width='10%' style='text-align: right;'>Working Ward</td>
                <td width='30%'>
                <?php echo"<input type='text' value='{$ward}'>"?>
                </td>
                

            </tr>
            <tr   id="select_clinic">
                <td width='10%' style='text-align: right;'>Shift</td>
                <td width='30%'>
                <?php echo"<input type='text' style='border: 3px solid black;' value='{$select_round}'>"?>
                </td>
                <td width='10%' style='text-align: right;'>Time Duty Handled</td>
                <td>
                <?php echo"<input type='text' value='{$duty_handled}'>"?>
                </td>
            </tr>
            <tr style='background: #ccc;'>
                <th colspan='6'><b>DUTY'S DETAILS</b></th>                
            </tr>
            <tr>
                <td width='10%' style='text-align: right;'>Total Current Patients</td>
                <td>
                <?php 
                        echo"<input type='text' readonly='readonly' value='{$current_inpatient}'>"
                    ?>
               </td>
                <td width='10%' style='text-align: right;'>Total Admitted Patients</td>
                <td>
                <?php 
                        echo"<input type='text' readonly='readonly' value='{$received_inpatient}'>"
                    ?>
                </td>
                <td width='10%' style='text-align: right;'>Total Discharged Patients</td>
                <td>
                <?php 
                        echo"<input type='text' readonly='readonly' value='{$discharged_inpatient}'>"
                    ?>
                </td>
            </tr>
            <tr>
                <td style='text-align: right;'>Refugees</td>
                <td>
                <?php 
                        echo"<input type='text' readonly='readonly' value='{$Refugees}'>"
                    ?>
                </td>
                <td style='text-align: right;'>Admitted Not Paid</td>
                <td>
                <?php 
                        echo"<input type='text' readonly='readonly' value='{$debt_inpatient}'>"
                    ?>
                </td>
                <td style='text-align: right;'>Total Deaths</td>
                <td>
                <?php 
                        echo"<input type='text' readonly='readonly' value='{$death_inpatient}'>"
                    ?>
                </td>
            </tr>
            <tr>
                <td width='10%' style='text-align: right;'>Total Serious Patients</td>
                <td>
                <?php 
                        echo"<input type='text' readonly='readonly' value='{$serious_inpatient}'>"
                    ?>
                </td>
                <td width='10%' style='text-align: right;'>Lodgers</td>
                <td>
                <?php 
                        echo"<input type='text' readonly='readonly' value='{$lodgers}'>"
                    ?>
                </td>
                <td width='10%' style='text-align: right;'>Abscondees</td>
                <td>
                <?php 
                        echo"<input type='text' readonly='readonly' value='{$Abscondees}'>"
                    ?>
                </td>
            </tr>
            <tr>
                <td width='10%' style='text-align: right;'>Total Transfer INs</td>
                <td>
                <?php 
                        echo"<input type='text' readonly='readonly' value='{$transferIn}'>"
                    ?>
                </td>
                <td width='10%' style='text-align: right;'>Total Transfer OUTs</td>
                <td>
                <?php 
                        echo"<input type='text' readonly='readonly' value='{$transferOut}'>"
                    ?>
                </td>
                <td width='10%' style='text-align: right;'>Ward Round</td>
                <td>
                    <?php 
                        echo"<input type='text' readonly='readonly' value='{$major_round}'>"
                    ?>
                </td>
            </tr>
            <tr>
            <td width='10%' style='text-align: right;'>Refugees</td>
                <td>
                    <?php 
                        echo"<input type='text' readonly='readonly' value='{$Refugees}'>"
                    ?>
                </td>
            </tr>
            <tr>
                <td  style='text-align: right;'>Nurse Notes</td>
                <td colspan='5'>
                <textarea name='nurse_notes' readonly='readonly' style='height: 250px;'><?php echo $taarifa ?></textarea></td>
            </tr>
        </table> 
        </center>
    </fieldset>
</form>
<br/>

<?php 
}
}else{
    ?>
    <br/></br>
    <fieldset>
        <legend>NURSE HANDLING DUTIES REPORT</legend>
        <table witdh=100%>
            <tr>
                <td style='text-align: center; width: 100%' colspan='6'>
                    <span style='text-align: center;'>NO DUTY HANDLED FOR <?php echo $down_Hospital_Ward_Name ?> FROM <?php echo $fromDate; ?> TO <?php echo $toDate; ?></span>
                </td>
            </tr>
        </table>
    </fieldset>
    <?php
}

include("./includes/footer.php"); 

?>