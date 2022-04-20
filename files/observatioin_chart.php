<?php 
include("./includes/connection.php");
include("middleware/dialysisi_function.php");

$TodayDatenow = mysqli_query($conn,"select now() as today");
while ($row = mysqli_fetch_array($TodayDatenow)) {
    $startoriginal_new = $row['today'];
}
if(isset ($_POST['SaveObservationChartbtn'])){
    $Time=  mysqli_real_escape_string($conn,$_POST['Time']);
    $BP=  mysqli_real_escape_string($conn,$_POST['BP']);
    $HR=  mysqli_real_escape_string($conn,$_POST['HR']);
    $QB=  mysqli_real_escape_string($conn,$_POST['QB']);
    $QD=  mysqli_real_escape_string($conn,$_POST['QD']);
    $AP=  mysqli_real_escape_string($conn,$_POST['AP']);
    $VP=  mysqli_real_escape_string($conn,$_POST['VP']);
    $FldRmvd=  mysqli_real_escape_string($conn,$_POST['FldRmvd']);
    $Heparin=  mysqli_real_escape_string($conn,$_POST['Heparin']);
    $Saline=  mysqli_real_escape_string($conn,$_POST['Saline']);
    $UFR=  mysqli_real_escape_string($conn,$_POST['UFR']);
    $TMP=  mysqli_real_escape_string($conn,$_POST['TMP']);
    $BVP=  mysqli_real_escape_string($conn,$_POST['BVP']);
    $Access=mysqli_real_escape_string($conn,$_POST['Access']);
    $Notes=  mysqli_real_escape_string($conn,$_POST['Notes']);
    $Patient_reg=  mysqli_real_escape_string($conn,$_POST['Registration_ID']);
    $Consultant_employee=  mysqli_real_escape_string($conn,$_POST['Consultant_employee']);
    $dialysis_details_ID=  mysqli_real_escape_string($conn,$_POST['dialysis_details_ID']);
    // $AttendanceDate=  mysqli_real_escape_string($conn,$_POST['Attendance_Date']);
    $Payment_Item_Cache_List_ID=  mysqli_real_escape_string($conn,$_POST['Payment_Item_Cache_List_ID']);
    if(!empty($AttendanceDate)){
       $Attendance_Date = $AttendanceDate; 
    }
    // die("------------".$AttendanceDate);
    $data_seve = array(array(
      "Patient_reg"=>$Patient_reg,
      "Time"=>$Time,
      "BP"=>$BP,
      "HR"=>$HR,
      "QB"=>$QB,
      "QD"=>$QD,
      "AP"=>$AP,
      "VP"=>$VP,
      "FldRmvd"=>$FldRmvd,
      "Saline"=>$Saline,
      "UFR"=>$UFR,
      "TMP"=>$TMP,
      "BVP"=>$BVP,
      "Access"=>$Access,
      "Heparin"=>$Heparin,
      "Notes"=>$Notes,
      "dialysis_details_ID"=>$dialysis_details_ID,
      "Consultant_employee"=>$Consultant_employee
      ) );

    if(Save_Observation_Chart(json_encode($data_seve))>0){
        $Registration_ID = $Patient_reg;
    }
    //TO UPDATE PROCEDURE FROM PAID TO SERVED
    // die("SELECT Payment_Item_Cache_List_ID FROM tbl_item_list_cache WHERE Payment_Item_Cache_List_ID='$Payment_Item_Cache_List_ID'");
    if(isset($Payment_Item_Cache_List_ID) && $Payment_Item_Cache_List_ID !=''){
        $select_procedure=mysqli_query($conn,"SELECT Payment_Item_Cache_List_ID FROM tbl_item_list_cache WHERE Payment_Item_Cache_List_ID='$Payment_Item_Cache_List_ID'");
    $select_Status=mysqli_query($conn,"SELECT Status FROM tbl_item_list_cache WHERE Payment_Item_Cache_List_ID='$Payment_Item_Cache_List_ID'");

    $procedure=mysqli_fetch_assoc($select_procedure)['Payment_Item_Cache_List_ID'];
    $Status=mysqli_fetch_assoc($select_Status)['Status'];

    if($Status == 'paid'){
        $update_status=mysqli_query($conn,"UPDATE tbl_item_list_cache SET Status='served',ServedBy='$Consultant_employee' WHERE Payment_Item_Cache_List_ID='$Payment_Item_Cache_List_ID'");
    }else{

    }
    }
    
    
    
}

$query = "SELECT * FROM `tbl_observation_chart` as obs,tbl_employee as emp WHERE `Patient_reg`='$Registration_ID' and obs.Consultant_employee=emp.Employee_ID and obs.dialysis_details_ID='$dialysis_details_ID'";
$result = mysqli_query($conn, $query) or die(mysqli_error($conn));
$html = '
    <table  class="" border="0" style="margin-top:-30px;width:100% " align="left" >
    <tr>
    <td width="6%" class="powercharts_td_left" style="font-weight:bold;background-color:#006400;color:white">TIME</td>
    <td width="6%" class="powercharts_td_left" style="font-weight:bold;background-color:#006400;color:white">BP</td>
    <td style="font-weight:bold; background-color:#006400;color:white" width="6%">HR</td> 
    <td style="font-weight:bold; background-color:#006400;color:white" width="6%">QB</td>
    <td style="font-weight:bold; background-color:#006400;color:white" width="6%">QD</td> 
    <td style="font-weight:bold; background-color:#006400;color:white" width="6%">AP</td> 
    <td style="font-weight:bold; background-color:#006400;color:white" width="6%">VP</td> 
    <td style="font-weight:bold; background-color:#006400;color:white" width="6%">FldRmvd</td> 
    <td style="font-weight:bold; background-color:#006400;color:white" width="6%">Heparin</td> 
    <td style="font-weight:bold; background-color:#006400;color:white" width="6%">Saline</td> 
    <td style="font-weight:bold; background-color:#006400;color:white" width="6%">UFR</td> 
    <td style="font-weight:bold; background-color:#006400;color:white" width="6%">TMP</td> 
    <td style="font-weight:bold; background-color:#006400;color:white" width="6%">Access</td> 
    <td style="font-weight:bold; background-color:#006400;color:white" width="10%">Notes</td>
    <td style="font-weight:bold; background-color:#006400;color:white" width="10%">Initials</td>
    </tr>';
if(mysqli_num_rows($result) > 0){
while($row = mysqli_fetch_assoc($result)){
    $html.='
        <tr>
            <td width="6%">
                <span class="pointer" id="spanuchunguzi_titiN"><input type="text" value="'.$row['Time'].'"></span>

            </td>

            <td width="6%">
                <span class="pointer" id="spanuchunguzi_titiN"><input type="text"  value="'.$row['BP'].'"></span>

            </td>

            <td width="6%">
                <span class="pointer" id="spanuchunguzi_titiN"><input type="text"   value="'.$row['HR'].'"></span>

            </td>
        
            <td width="6%">
                <span class="pointer" id="spanuchunguzi_titiN"><input type="text"  value="'.$row['QB'].'"></span>

            </td>

            <td width="6%">
                <span class="pointer" id="spanuchunguzi_titiN"><input type="text" value="'.$row['QD'].'"></span>

            </td>

            <td width="6%">
                <span class="pointer" id="spanuchunguzi_titiN"><input type="text"  value="'.$row['AP'].'"></span>

            </td>

            <td width="6%">
                <span class="pointer" id="spanuchunguzi_titiN"><input type="text"  value="'.$row['VP'].'"></span>

            </td>

            <td width="6%">
                <span class="pointer" id="spanuchunguzi_titiN"><input type="text"  value="'.$row['FldRmvd'].'"></span>
            </td>

            <td width="6%">
                <span class="pointer" id="spanuchunguzi_titiN"><input type="text"   value="'.$row['Heparin'].'"></span>

            </td>

            <td width="6%">
                <span class="pointer" id="spanuchunguzi_titiN"><input type="text"   value="'.$row['Saline'].'"></span>

            </td>

            <td width="6%">
                <span class="pointer" id="spanuchunguzi_titiN"><input type="text"  value="'.$row['UFR'].'"></span>

            </td>

            <td width="6%">
                <span class="pointer" id="spanuchunguzi_titiN"><input type="text" value="'.$row['TMP'].'"></span>

            </td>

            <td width="6%">
                <span class="pointer" id="spanuchunguzi_titiN"><input type="text" value="'.$row['Access'].'"></span>

            </td>

            <td width="6%">
                <span class="pointer" id="spanuchunguzi_titiN"><input type="text" value="'.$row['Notes'].'"></span>

            </td>
            <td width="6%">
            <span class="pointer" id="employeename"><input type="text" value="'.$row['Employee_Name'].'" readonly></span>

        </td>
        </tr>';
}}

$html .='
        <tr>
            <td width="6%">
                <span class="pointer" id="spanuchunguzi_titiN"><input type="text" class="Time_old_time" id="Time" readonly name="Time" value="'.$startoriginal_new.'"></span>

            </td>

            <td width="6%">
                <span class="pointer" id="spanuchunguzi_titiN"><input type="text"  id="BP" name="BP"></span>

            </td>

            <td width="6%">
                <span class="pointer" id="spanuchunguzi_titiN"><input type="text"  id="HR" name="HR"></span>

            </td>
        
            <td width="6%">
                <span class="pointer" id="spanuchunguzi_titiN"><input type="text"  id="QB" name="QB"></span>

            </td>

            <td width="6%">
                <span class="pointer" id="spanuchunguzi_titiN"><input type="text"  id="QD" name="QD"></span>

            </td>

            <td width="6%">
                <span class="pointer" id="spanuchunguzi_titiN"><input type="text"  id="AP" name="AP"></span>

            </td>

            <td width="6%">
                <span class="pointer" id="spanuchunguzi_titiN"><input type="text"  id="VP" name="VP"></span>

            </td>

            <td width="6%">
                <span class="pointer" id="spanuchunguzi_titiN"><input type="text"  id="FldRmvd" name="FldRmvd"></span>
            </td>

            <td width="6%">
                <span class="pointer" id="spanuchunguzi_titiN"><input type="text"  id="Heparin" name="Heparin"></span>

            </td>

            <td width="6%">
                <span class="pointer" id="spanuchunguzi_titiN"><input type="text"  id="Saline" name="Saline"></span>

            </td>

            <td width="6%">
                <span class="pointer" id="spanuchunguzi_titiN"><input type="text"  id="UFR" name="UFR"></span>

            </td>

            <td width="6%">
                <span class="pointer" id="spanuchunguzi_titiN"><input type="text"  id="TMP" name="TMP"></span>

            </td>

            <td width="6%">
                <span class="pointer" id="spanuchunguzi_titiN"><input type="text"  id="uchunguzi_titiN" name="Access"></span>

            </td>

            <td width="6%">
                <span class="pointer" id="spanuchunguzi_titiN"><input type="text"  id="uchunguzi_titiN" name="Notes"></span>

            </td>
            <td width="6%">
                <span class="pointer" id="spanuchunguzi_titiN"><input type="text"  id="employeename" name="employeename" readonly></span>

            </td>
        </tr>
    </table>

    <table>

        <tr>
            <td>
        <center> 
        
            <input type="button" onClick="save_observation_chart('.$Registration_ID.','.$Consultant_employee.','.$dialysis_details_ID.')" value="Save Data" class="btn btn-primary">
        </center>
        </td>

        </tr> 
    </table>';
    // @session_destroy();
echo $html;