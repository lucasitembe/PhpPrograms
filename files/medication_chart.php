<?php 
include("./includes/connection.php");
if(isset ($_POST['SaveMedicationChartbtn'])){
    include("middleware/dialysisi_function.php");
    $Ancillary=  mysqli_real_escape_string($_POST['Ancillary']);
    $Indication=  mysqli_real_escape_string($_POST['Indication']);
    $Dose=  mysqli_real_escape_string($_POST['Dose']);
    $Route=  mysqli_real_escape_string($_POST['Route']);
    $Time_chart=  mysqli_real_escape_string($_POST['Time_chart']);
    $Initials_charts=  mysqli_real_escape_string($_POST['Initials_charts']);
    $Ancillary_1=  mysqli_real_escape_string($_POST['Ancillary_1']);
    $Indication_1=  mysqli_real_escape_string($_POST['Indication_1']);
    $Dose_1=  mysqli_real_escape_string($_POST['Dose_1']);
    $Route_1=  mysqli_real_escape_string($_POST['Route_1']);
    $Time=  mysqli_real_escape_string($_POST['Time']);
    $Initials=  mysqli_real_escape_string($_POST['Initials']);
    $Patient_reg=  mysqli_real_escape_string($_POST['Registration_ID']);
    $Consultant_employee=  mysqli_real_escape_string($_POST['Consultant_employee']);
    $dialysis_details_ID=  mysqli_real_escape_string($_POST['dialysis_details_ID']);
    $AttendanceDate =  mysqli_real_escape_string($_POST['Attendance_Date']);
    if(!empty($AttendanceDate)){
        $Attendance_Date = $AttendanceDate;
    }
  
    $data_eight = array(array (
        "Patient_reg"=>$Patient_reg,
        'Attendance_Date'=>$AttendanceDate,
        "Ancillary"=>$Ancillary,
        "Indication"=>$Indication,
        "Dose"=>$Dose,
        "Route"=>$Route,
        "Time_chart"=>$Time_chart,
        "Initials_charts"=>$Initials_charts,
        "Ancillary_1"=>$Ancillary_1,
        "Indication_1"=>$Indication_1,
        "Dose_1"=>$Dose_1,
        "Route_1"=>$Route_1,
        "Time"=>$Time,
        "Initials"=>$Initials,
        "dialysis_details_ID"=>$dialysis_details_ID,
        "Consultant_employee"=>$Consultant_employee
    ));

    if(Save_Medication_Chart(json_encode($data_eight))>0){
        $Registration_ID = $Patient_reg;
    }
}

$query = "SELECT * FROM `tbl_medication_chart` WHERE `Patient_reg`='$Registration_ID' AND Attendance_Date='".$Attendance_Date."'";
$result = mysqli_query($conn,$query) or die(mysqli_error($conn));
$html = '
    <table  class="" border="0" style="margin-top:-30px;width:100% " align="left" >
    <tr>
        <td width="4%" class="powercharts_td_left" style="font-weight:bold;background-color:#006400;color:white">Drug/Ancillary </td> <td style="font-weight:bold; background-color:#006400;color:white" width="4%">Indication/comment</td>  <td width="4%" class="powercharts_td_left" style="font-weight:bold;background-color:#006400;color:white">Dose</td><td width="4%" class="powercharts_td_left" style="font-weight:bold;background-color:#006400;color:white">Route</td><td width="4%" class="powercharts_td_left" style="font-weight:bold;background-color:#006400;color:white">Time</td><td width="4%" class="powercharts_td_left" style="font-weight:bold;background-color:#006400;color:white">Initials</td>    <td style="font-weight:bold; background-color:#006400;color:white" width="4%">Drug/Ancillary</td> <td style="font-weight:bold; background-color:#006400;color:white" width="4%">Indication/Comment</td> <td style="font-weight:bold; background-color:#006400;color:white" width="4%">Dose</td> <td style="font-weight:bold; background-color:#006400;color:white" width="4%">Route</td> <td style="font-weight:bold; background-color:#006400;color:white" width="4%">Time</td> <td style="font-weight:bold; background-color:#006400;color:white" width="4%">Initials</td>
    </tr>';
if(mysqli_num_rows($result) > 0){
while($row = mysqli_fetch_assoc($result)){
    $html.=' <tr>
                  <td width="">
                        <span class="pointer" id="spanuchunguzi_titiN"><input type="text" value='.$row['Ancillary'].'></span>

                    </td>
                    <td>
                        <span class="pointer" id="spanBujeH"><input type="text"  value='.$row['Indication'].'></span>
                    </td>
                    <td>
                        <span class="pointer" id="spankidondaN"><input type="text"  value='.$row['Dose'].'></span>
                    </td>
                    <td>
                        <span class="pointer" id="spanchuchu_damuN"><input type="text"  value='.$row['Route'].'></span>
                    </td>
                    <td>

                        <span class="pointer" id="spanchuchu_damuN"><input type="text" value='.$row['Time_chart'].'></span>
                    </td>
                    <td>

                        <span class="pointer" id="spanchuchu_damuN"><input type="text" value='.$row['Initials_charts'].'></span>
                    </td>
                    <td>

                        <span class="pointer" id="spanchuchu_damuN"><input type="text"  value='.$row['Ancillary_1'].'></span>
                    </td>
                     <td>

                        <span class="pointer" id="spanchuchu_damuN"><input type="text"  value='.$row['Indication_1'].'></span>
                    </td>
                    <td>

                        <span class="pointer" id="spanchuchu_damuN"><input type="text"  value='.$row['Dose_1'].'></span>
                    </td>
                    <td>

                        <span class="pointer" id="spanchuchu_damuN"><input type="text"  value='.$row['Route_1'].'></span>
                    </td>
                    <td>

                        <span class="pointer" id="spanchuchu_damuN"><input type="text" value='.$row['Time'].'></span>
                    </td>
                    <td>

                        <span class="pointer" id="spanchuchu_damuN"><input type="text" value='.$row['Initials'].'></span>
                    </td>
                </tr>
                ';
}}

$html .='
        <tr>
            <td width="6%">
                <span class="pointer" id="spanuchunguzi_titiN"><input type="text"  id="Time" name="Ancillary"></span>

            </td>

            <td width="6%">
                <span class="pointer" id="spanuchunguzi_titiN"><input type="text"  id="BP" name="Indication"></span>

            </td>

            <td width="6%">
                <span class="pointer" id="spanuchunguzi_titiN"><input type="text"  id="HR" name="Dose"></span>

            </td>
        
            <td width="6%">
                <span class="pointer" id="spanuchunguzi_titiN"><input type="text"  id="QB" name="Route"></span>

            </td>

            <td width="6%">
                <span class="pointer" id="spanuchunguzi_titiN"><input type="text"  id="QD" name="Time_chart"></span>

            </td>

            <td width="6%">
                <span class="pointer" id="spanuchunguzi_titiN"><input type="text"  id="AP" name="Initials_charts"></span>

            </td>

            <td width="6%">
                <span class="pointer" id="spanuchunguzi_titiN"><input type="text"  id="VP" name="Ancillary_1"></span>

            </td>

            <td width="6%">
                <span class="pointer" id="spanuchunguzi_titiN"><input type="text"  id="FldRmvd" name="Indication_1"></span>
            </td>

            <td width="6%">
                <span class="pointer" id="spanuchunguzi_titiN"><input type="text"  id="uchunguzi_titiN" name="Dose_1"></span>

            </td>

            <td width="6%">
                <span class="pointer" id="spanuchunguzi_titiN"><input type="text"  id="uchunguzi_titiN" name="Route_1"></span>

            </td>

            <td width="6%">
                <span class="pointer" id="spanuchunguzi_titiN"><input type="text"  id="uchunguzi_titiN" name="Times"></span>

            </td>

            <td width="6%">
                <span class="pointer" id="spanuchunguzi_titiN"><input type="text"  id="uchunguzi_titiN" name="Initials"></span>

            </td>
        </tr>
        </table>

        <table>
        <tr>
            <td>
        <center> 
            <input type="button" onClick="save_medication_chart('.$Registration_ID.','.$dialysis_details_ID.','.$Consultant_employee.',\''.$Attendance_Date.'\')" value="Save Data" class="btn btn-primary">
        </center>
        </td>
        </tr> 
    </table>';

echo $html;