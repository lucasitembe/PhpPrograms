<?php 
include("./includes/connection.php");
if(isset($_GET['dependent_service_number'])){
  $dependent_service_number=$_GET['dependent_service_number'];
  if($dependent_service_number==" "||$dependent_service_number=="  "||$dependent_service_number=="   "){
      $dependent_service_number="0";
  }
}else{
   $dependent_service_number="0"; 
}
$sql_select_info_result=mysqli_query($conn,"SELECT Patient_Name,Gender,Status,rank,military_unit FROM tbl_patient_registration WHERE service_no='$dependent_service_number'") or die(mysqli_error($conn));
if(mysqli_num_rows($sql_select_info_result)>0){
   $rows_info=mysqli_fetch_assoc($sql_select_info_result);
   $Patient_Name=$rows_info['Patient_Name'];
   $Gender=$rows_info['Gender'];
   $Status=$rows_info['Status'];
   $rank=$rows_info['rank'];
   $military_unit=$rows_info['military_unit'];
   ?>
<table class="table table-bordered" style="background: #FFFFFF">
    <tr>
        <th colspan="2" style="text-align: center">
            MILITARY PERSONNEL FOUND WITH THE FOLLOWING INFORMATION 
        </th>
    </tr>
    <tr>
        <th style="width: 35%;text-align: right">Military Personnel Name</th>
        <td><?= $Patient_Name ?></td>
    </tr>
    <tr>
        <th style="text-align: right">Rank</th>
        <td><?= $rank ?></td>
    </tr>
    <tr>
        <th style="text-align: right">Unit</th>
        <td><?= $military_unit ?></td>
    </tr>
    <tr>
        <th style="text-align: right">Status</th>
        <td><?= $Status ?></td>
    </tr>
    <tr>
        <th style="text-align: right">Gender</th>
        <td><?= $Gender ?></td>
    </tr>
    <tr>
        <td colspan="2">
            <input type="button" value="CONFIRMED" onclick="click_mil_p_info_dialog()" class="art-button-green pull-right">
        </td>
    </tr>
</table>
       <?php
}else{
?>
<br/>
<br/>
<br/>
<center><div style="background: #cccccc;padding: 15px">SERVICE NUMBER NOT FOUND</div></center>
<br/><br/>
<br/><br/>
<div class="row">
    <div class="col-md-8">
        <input type="button" class="art-button-green"value="REGISTER MILITARY PERSONEL" onclick="open_registration_dialog()">
    </div>
    <div class="col-md-4">
        <input type="button" class="art-button-green pull-right" onclick="click_mil_p_info_dialog()" value="CANCEL">
    </div>
</div>
<?php
}
?>
<script>
    function click_mil_p_info_dialog(){
        $("#militsry_personnel_information").dialog("close"); 
    }
    function open_registration_dialog(){
        $("#military_personnel_radio").attr("checked","checked");
        $("#military_row_data").show();
        $("#militsry_personnel_information").dialog("close"); 
        $("#dependent_service_number").val("");
        $("#dependant_number").val("");
        $("#dependant_row_data").hide();
        $("#from_search_military_info").val("from_search_military_info")
    }
</script>