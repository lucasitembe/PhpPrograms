<?php 
include("./includes/connection.php");
if(isset ($_POST['SaveObservationChartbtn'])){
    include("middleware/dialysisi_function.php");
    $Dialyzer=  mysqli_real_escape_string($conn,$_POST['Dialyzer']);
    $Dialysate=  mysqli_real_escape_string($conn,$_POST['Dialysate']);
    $Sodium=  mysqli_real_escape_string($conn,$_POST['Sodium']);
    $UD=  mysqli_real_escape_string($conn,$_POST['UD']);
    $Temp=  mysqli_real_escape_string($conn,$_POST['Temp']);
    $Patient_reg=  mysqli_real_escape_string($conn,$_POST['Registration_ID']);
    $Consultant_employee=  mysqli_real_escape_string($conn,$_POST['Consultant_employee']);
    $dialysis_details_ID=  mysqli_real_escape_string($conn,$_POST['dialysis_details_ID']);
    $AttendanceDate =  mysqli_real_escape_string($conn,$_POST['Attendance_Date']);
    if(!empty($AttendanceDate)){
        $Attendance_Date = $AttendanceDate;
    }
  
    $data_nine = array(array(
        "Patient_reg"=>$Patient_reg,
        'Attendance_Date'=>$AttendanceDate,
        "Dialyzer"=>$Dialyzer,
        "Dialysate"=>$Dialysate,
        "Sodium"=>$Sodium,
        "UD"=>$UD,
        "Temp"=>$Temp,
        "Consultant_employee"=>$Consultant_employee,
        "dialysis_details_ID"=>$dialysis_details_ID
    ));

    if(Save_Dialysis_oder(json_encode($data_nine))>0){
        $Registration_ID = $Patient_reg;
    }
}

$query = "SELECT * FROM `tbl_Dialysis_oder` WHERE `Patient_reg`='$Registration_ID' AND Attendance_Date='".$Attendance_Date."'";
$result = mysqli_query($conn, $query) or die(mysqli_error($conn));
$html = '
    <table  class="" border="0" style="margin-top:-30px;width:100% " align="left" >
    <tr>
        <td class="td-vital" style="font-weight:bold; background-color:#006400;color:white" width="16%">
            Dialyzer
        </td>
        <td class="td-vital" style="font-weight:bold; background-color:#006400;color:white" width="16%">
            Dialysate
        </td>

        <td class="td-vital" style="font-weight:bold; background-color:#006400;color:white" width="16%">
            Sodium Modelling
        </td>

        <td class="td-vital" style="font-weight:bold; background-color:#006400;color:white" width="16%">
            UD Profiling Max UFR
        </td>

        <td class="td-vital" style="font-weight:bold; background-color:#006400;color:white" width="16%">
            Dialysate Temp
        </td>
    </tr>';
if(mysqli_num_rows($result) > 0){
while($row = mysqli_fetch_assoc($result)){
    $html.=' <tr>
                  <td width="16%">
                        <span class="pointer" id="spanuchunguzi_titiN"><input type="text"  id="Initials_1" value='.$row['Dialyzer'].'></span>
                    </td>
                    <td width="16%">
                        <span class="pointer" id="spanuchunguzi_titiN"><input type="text"  id="Initials_1" value='.$row['Dialysate'].'></span>
                    </td>
                    <td width="16%">
                        <span class="pointer" id="spanBujeH"><input type="text"  id="Initials_2" value='.$row['Sodium'].'></span>
                    </td>
                    <td width="16%">
                        <span class="pointer" id="spankidondaN"><input type="text"  id="Initials_3" value='.$row['UD'].'></span>
                    </td>
                    <td width="16%">
                        <span class="pointer" id="spanchuchu_damuN"><input type="text"  id="Initials_4" value='.$row['Temp'].'></span>
                    </td>
                </tr>
                ';
}}

$html .='
        <tr>
            <td width="16%">
                <span class="pointer" id="spanuchunguzi_titiN"><input type="text"  id="Time" name="Dialyzer"></span>

            </td>

            <td width="16%">
                <span class="pointer" id="spanuchunguzi_titiN"><input type="text"  id="BP" name="Dialysate"></span>

            </td>

            <td width="16%">
                <span class="pointer" id="spanuchunguzi_titiN"><input type="text"  id="HR" name="Sodium"></span>

            </td>
        
            <td width="16%">
                <span class="pointer" id="spanuchunguzi_titiN"><input type="text"  id="QB" name="UD"></span>

            </td>

            <td width="16%">
                <span class="pointer" id="spanuchunguzi_titiN"><input type="text"  id="QD" name="Temp"></span>

            </td>
        </tr>
        </table>

        <table>
        <tr>
            <td>
        <center> 
            <input type="button" onClick="save_dialysis_oders_form('.$Registration_ID.','.$Consultant_employee.','.$dialysis_details_ID.',\''.$Attendance_Date.'\')" value="Save Data" class="btn btn-primary">
        </center>
        </td>
        </tr> 
    </table>';

echo $html;