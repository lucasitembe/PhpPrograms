<style>
.button_alignment{
    margin: 4px;
}
.button_alignment:hover{
    background:#DEDEDE;
}
</style>
<?php
session_start();
include("./includes/connection.php");

if (isset($_GET['Registration_ID'])) {
    $Registration_ID=$_GET['Registration_ID'];
} else {
    $Registration_ID = '';
}
if (isset($_GET['consultation_ID'])) {
    $consultation_ID=$_GET['consultation_ID'];
} else {
    $consultation_ID = '';
}
if (isset($_GET['Admission_ID'])) {
    $Admission_ID=$_GET['Admission_ID'];
} else {
    $Admission_ID = '';
}
$Today_Date = mysqli_query($conn, "SELECT now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
}

//select patient information
if (!empty($Registration_ID)) {
    $select_Patient = mysqli_query($conn, "SELECT pr.Old_Registration_Number, pc.Payment_Cache_ID, pr.Patient_Name, pr.Sponsor_ID, pr.Date_Of_Birth, pr.Gender, 
        								sp.Guarantor_Name, pr.Member_Number, pr.Phone_Number, pr.Occupation, pr.Registration_Date_And_Time
										from tbl_payment_cache pc, tbl_patient_registration pr, tbl_sponsor sp where
										pc.Registration_ID = pr.Registration_ID and
										pc.Sponsor_ID = sp.Sponsor_ID and
                                        pc.Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
    $no = mysqli_num_rows($select_Patient);

    if ($no > 0) {
        while ($row = mysqli_fetch_array($select_Patient)) {
            $Old_Registration_Number = $row['Old_Registration_Number'];
            $Patient_Name = $row['Patient_Name'];
            $Sponsor_ID = $row['Sponsor_ID'];
            $Date_Of_Birth = $row['Date_Of_Birth'];
            $Gender = $row['Gender'];
            $Guarantor_Name = $row['Guarantor_Name'];
            $Member_Number = $row['Member_Number'];
            $Phone_Number = $row['Phone_Number'];
            $Occupation = $row['Occupation'];
            $Registration_Date_And_Time = $row['Registration_Date_And_Time'];
            $Payment_Cache_ID = $row['Payment_Cache_ID'];
        }
    } else {
        $Old_Registration_Number = '';
        $Patient_Name = '';
        $Sponsor_ID = '';
        $Date_Of_Birth = '';
        $Gender = '';
        $Guarantor_Name = '';
        $Member_Number = '';
        $Phone_Number = '';
        $Occupation = '';
        $Registration_Date_And_Time = '';
        $Payment_Cache_ID = '';
    }
} else {
    $Old_Registration_Number = '';
    $Patient_Name = '';
    $Sponsor_ID = '';
    $Date_Of_Birth = '';
    $Gender = '';
    $Guarantor_Name = '';
    $Member_Number = '';
    $Phone_Number = '';
    $Occupation = '';
    $Registration_Date_And_Time = '';
}

//calculate patient age
$date1 = new DateTime($Today);
$date2 = new DateTime($Date_Of_Birth);
$diff = $date1->diff($date2);
$age = $diff->y . " Years, ";
$age .= $diff->m . " Months, ";
$age .= $diff->d . " Days";
?>
<fieldset>
    <table width="100%">
        <tr>
            <td width="12%" style="text-align: right;">Patient Name</td>
            <td><input type="text" value="<?php echo ucwords(strtolower($Patient_Name)); ?>" readonly="readonly"></td>
            <td width="12%" style="text-align: right;">Patient Number</td>
            <td><input type="text" value="<?php echo $Registration_ID; ?>" readonly="readonly"></td>
            <td width="12%" style="text-align: right;">Sponsor Name</td>
            <td><input type="text" value="<?php echo $Guarantor_Name; ?>" readonly="readonly"></td>
        </tr>
        <tr>
            <td width="12%" style="text-align: right;">Patient Age</td>
            <td><input type="text" value="<?php echo ucwords(strtolower($age)); ?>" readonly="readonly"></td>
            <td width="12%" style="text-align: right;">Gender</td>
            <td><input type="text" value="<?php echo $Gender; ?>" readonly="readonly"></td>
            <td width="12%" style="text-align: right;">Phone Number</td>
            <td><input type="text" value="<?php echo $Phone_Number; ?>" readonly="readonly"></td>
        </tr>
        <tr>
            <td width="12%" style="text-align: right;">Registered Date</td>
            <td><input type="text" value="<?php echo $Registration_Date_And_Time; ?>" readonly="readonly"></td>
            <td width="12%" style="text-align: right;">Occupation</td>
            <td><input type="text" value="<?php echo $Occupation; ?>" readonly="readonly"></td>
            <td width="12%" style="text-align: right;">Admission ID</td>
            <td><input type="text" value="<?php echo $Admission_ID; ?>" readonly="readonly"></td>
        </tr>
    </table>
</fieldset>

<fieldset style='overflow-y: scroll; height: 340px; background-color: white;' id='Medication_Area'>
<center>
      <div class="button_container">
            
            <div class="button_alignment">
                    <button style='width: 100%; height: 100%' onclick='observation_chart_inpatient_preview(<?php echo $consultation_ID; ?>,<?php echo $Registration_ID; ?>,<?php echo $Admission_ID; ?>)'>
                        Preview Observation Chart
                    </button>
            </div>
            <div class="button_alignment">
                    <button style='width: 100%; height: 100%' onclick='view_observation_chart_by_graph()'>
                        Preview Observation Chart (Graphical)
                    </button>
            </div>
            <div class="button_alignment">
                    <button style='width: 100%; height: 100%;' onclick='Preview_nurse_care_notes(<?php echo $consultation_ID; ?>,<?php echo $Registration_ID; ?>,<?php echo $Admission_ID; ?>)'>
                        Preview Nurse Care Plan
                    </button>
            </div>
            <div class="button_alignment">
                <button style='width: 100%; height: 100%' onclick='Preview_notes(<?php echo $consultation_ID; ?>,<?php echo $Registration_ID; ?>,<?php echo $Admission_ID; ?>)'>
                    Preview Nurse Notes
                </button>
            </div>
            <div class="button_alignment">
                <button style='width: 100%; height: 100%' onclick='Preview_medication(<?php echo $consultation_ID; ?>,<?php echo $Registration_ID; ?>,<?php echo $Admission_ID; ?>)'>
                    Preview Medication Administration
                </button>
            </div>
                
      </div>
</fieldset>
<script type="text/javascript">
function Preview_notes(consultation_ID,Registration_ID,Admission_ID){
    var Consultation_ID= consultation_ID;
    var Registration_ID= Registration_ID;
    var Admission_ID=Admission_ID;
            $.get(
                'nurse_notes_inpatient_preview.php', {
                    Registration_ID:Registration_ID,Consultation_ID:Consultation_ID,Admission_ID:Admission_ID
                }, (data) => {
                    $("#Preview_Details").dialog({
                        autoOpen: false,
                        width: '80%',
                        title: 'eHMS 2.0: NURSING NOTES',
                        modal: true
                    });
                    $("#Preview_Details").html(data);
                    $("#Preview_Details").dialog("open");
                }
            );
}
function Preview_medication(consultation_ID,Registration_ID,Admission_ID){
    var Consultation_ID= consultation_ID;
    var Registration_ID= Registration_ID;
    var Admission_ID=Admission_ID;
            $.get(
                'medication_inpatient_preview.php', {
                    Registration_ID:Registration_ID,Consultation_ID:Consultation_ID,Admission_ID:Admission_ID
                }, (data) => {
                    $("#Preview_Details").dialog({
                        autoOpen: false,
                        width: '80%',
                        title: 'eHMS 2.0: MEDICATION ADMINISTRATION HISTORY',
                        modal: true
                    });
                    $("#Preview_Details").html(data);
                    $("#Preview_Details").dialog("open");
                }
            );
}
function Preview_nurse_care_notes(consultation_ID,Registration_ID,Admission_ID){
    var Consultation_ID= consultation_ID;
    var Registration_ID= Registration_ID;
    var Admission_ID=Admission_ID;
            $.get(
                'nurse_careplan_inpatient_preview.php', {
                    Registration_ID:Registration_ID,Consultation_ID:Consultation_ID,Admission_ID:Admission_ID
                }, (data) => {
                    $("#Preview_Details").dialog({
                        autoOpen: false,
                        width: '80%',
                        title: 'eHMS 2.0: NURSE CARE PLAN ',
                        modal: true
                    });
                    $("#Preview_Details").html(data);
                    $("#Preview_Details").dialog("open");
                }
            );
}
function observation_chart_inpatient_preview(consultation_ID,Registration_ID,Admission_ID){
    var Consultation_ID= consultation_ID;
    var Registration_ID= Registration_ID;
    var Admission_ID=Admission_ID;
            $.get(
                'observation_chart_inpatient_preview.php', {
                    Registration_ID:Registration_ID,Consultation_ID:Consultation_ID,Admission_ID:Admission_ID
                }, (data) => {
                    $("#Preview_Details").dialog({
                        autoOpen: false,
                        width: '80%',
                        title: 'eHMS 2.0: OBSERVATION CHART ',
                        modal: true
                    });
                    $("#Preview_Details").html(data);
                    $("#Preview_Details").dialog("open");
                }
            );
}
</script>
<div id="observation_chart"></div>
<script>
    function view_observation_chart_by_graph(){
        var Registration_ID='<?= $Registration_ID ?>';
        var consultation_ID = '<?php echo $_GET['consultation_ID']; ?>';
        
        if(start_date =='' || end_date==''){
            alert("Please enter both dates");
            exit;
        }
        $.ajax({
            type:'POST',
            url:'ajax_view_observation_chart_by_graph.php',
            data:{Registration_ID:Registration_ID,consultation_ID:consultation_ID},
            success:function(data){
                $('#observation_chart').html(data);
                $("#observation_chart").dialog({
                    title: 'OBSERVATION CHART',
                    width: '90%',
                    height: 850,
                    modal: true,
                }); 
                get_observation_chart()
            }
        }); 
    }
    function get_observation_chart(){
        var Registration_ID='<?= $Registration_ID ?>';
        var consultation_ID = '<?php echo $_GET['consultation_ID']; ?>';
        $.ajax({
            type:'POST',
            url:'ajax_get_observation_chart.php',
            data:{Registration_ID:Registration_ID,consultation_ID:consultation_ID},
            success:function(data){
                //console.log(data)
                var all_saved_data_result = JSON.parse(data);
//                console.log(all_saved_data_result);
                var systoric_pressure=all_saved_data_result[0]
                var diastolic_pressure=all_saved_data_result[1]
                var Pulse_Blood_value=all_saved_data_result[2]
                var Temperature_value=all_saved_data_result[3]
                var Resp_Bpressure_value=all_saved_data_result[4]
                var oxygen_saturation_value=all_saved_data_result[5]
                var blood_transfusion_value=all_saved_data_result[6]
                var body_weight_value=all_saved_data_result[7]
                if(systoric_pressure.length<=0){
                    systoric_pressure=[[]]
                    diastolic_pressure=[[]]
                }
                if(Pulse_Blood_value.length<=0){
                    Pulse_Blood_value=[[]]
                }
                if(Temperature_value.length<=0){
                    Temperature_value=[[]]
                }
                if(Resp_Bpressure_value.length<=0){
                    Resp_Bpressure_value=[[]]
                }
                if(oxygen_saturation_value.length<=0){
                    oxygen_saturation_value=[[]]
                }
                if(blood_transfusion_value.length<=0){
                    blood_transfusion_value=[[]]
                }
                if(body_weight_value.length<=0){
                    body_weight_value=[[]]
                }
                
                console.log(systoric_pressure+"<====sytolic\n dystolic===>"+diastolic_pressure+"\n pulse_blood=>"+Pulse_Blood_value+"\n temperature==>"+Temperature_value+"\n resp==>"+Resp_Bpressure_value+"\n oxygensatu==>"+oxygen_saturation_value);
                //console.log("sytolic==="+systoric_pressure+"\n"+diastolic_pressure);
                
                Resp_Bpressure_value=[[]]
                oxygen_saturation_value=[[]]
                observation_chart_graph(Temperature_value,Pulse_Blood_value,systoric_pressure,diastolic_pressure,Resp_Bpressure_value,oxygen_saturation_value,body_weight_value,blood_transfusion_value)
            }
        });
    }
</script>
<!--<script src="js/jquery-1.8.0.min.js"></script>-->
<script type="text/javascript" src="jqplot/jquery.jqplot.js"></script>
<script type="text/javascript" src="jqplot/plugins/jqplot.dateAxisRenderer.js"></script>
<script type="text/javascript" src="jqplot/plugins/jqplot.canvasTextRenderer.js"></script>
<script type="text/javascript" src="jqplot/plugins/jqplot.canvasAxisLabelRenderer.js"></script>
<script type="text/javascript" src="jqplot/plugins/jqplot.canvasTextRenderer.js"></script>
<script type="text/javascript" src="jqplot/plugins/jqplot.canvasAxisTickRenderer.js"></script>
<script type="text/javascript" src="jqplot/plugins/jqplot.canvasAxisLabelRenderer.js"></script>
<script type="text/javascript" src="jqplot/plugins/jqplot.logAxisRenderer.js"></script>
<script type="text/javascript" src="jqplot/plugins/jqplot.highlighter.js"></script>
<link rel="stylesheet" type="text/css" href="jqplot/jquery.jqplot.css" />