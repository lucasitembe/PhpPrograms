<?php
include("./includes/connection.php");
if(isset($_POST['Registration_ID'])){
    $Registration_ID=$_POST['Registration_ID'];
    $start_date=$_POST['start_date'];
    $end_date=$_POST['end_date'];
    $consultation_ID=$_POST['consultation_ID'];
   
    $select_patien_details = mysqli_query($conn,"
		SELECT Member_Number,Patient_Name, Registration_ID,Gender,Guarantor_Name,Date_Of_Birth
			FROM 
				tbl_patient_registration pr, 
				tbl_sponsor sp
			WHERE 
				pr.Registration_ID = '$Registration_ID' AND
				sp.Sponsor_ID = pr.Sponsor_ID
				") or die(mysqli_error($conn));
    $no = mysqli_num_rows($select_patien_details);
    if ($no > 0) {
        while ($row = mysqli_fetch_array($select_patien_details)) {
            $Member_Number = $row['Member_Number'];
            $Patient_Name = $row['Patient_Name'];
            $Registration_ID = $row['Registration_ID'];
            $Gender = $row['Gender'];
            $Sponsor = $row['Guarantor_Name'];
            $DOB = $row['Date_Of_Birth'];
        }
    }
    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
	
    }

    $age = date_diff(date_create($DOB), date_create('today'))->y;
    echo "<center><b>" . $Patient_Name . "</b>  | " . $Gender . "</b> | <b>" . $age . " years</b> | <b>" . $Sponsor . "</b></center>";
}
?>
<hr/>
<style>
    .table tr td{
        border-collapse:collapse !important;
        border:1px solid  black!important;
        font-size: 11px;
    }	

</style>
<div id="observation_chart_graph"></div>
<div class="row">
    <div class="col-md-12">
        <table class="table">
            <?php 
            $date_time="";
            $Blood_Pressure="";
            $oxygen_saturation="";
            $Pulse_Blood="";
            $Resp_Bpressure="";
            $blood_transfusion="";
            $body_weight="";
    $sql_select_vital_sign_result=mysqli_query($conn,"SELECT body_weight,blood_transfusion,date,Blood_Pressure,Pulse_Blood,Temperature,Resp_Bpressure,oxygen_saturation FROM tbl_nursecommunication_observation WHERE Registration_ID='$Registration_ID' AND consultation_ID='$consultation_ID' AND date BETWEEN '$start_date' AND '$end_date' ORDER BY Observation_ID ASC") or die(mysqli_error($conn));
    if(mysqli_num_rows($sql_select_vital_sign_result)>0){
        $count_rw=0;
        while($vitals_rows=mysqli_fetch_assoc($sql_select_vital_sign_result)){
            $date_v=$vitals_rows['date'];
            $Blood_Pressure_v=$vitals_rows['Blood_Pressure'];
            $Pulse_Blood_v=$vitals_rows['Pulse_Blood'];
            $Temperature_v=$vitals_rows['Temperature'];
            $Resp_Bpressure_v=$vitals_rows['Resp_Bpressure'];
            $oxygen_saturation_v=$vitals_rows['oxygen_saturation'];
            $blood_transfusion_v=$vitals_rows['blood_transfusion'];
            $body_weight_v=$vitals_rows['body_weight'];
            
            $date_time .="<td>$date_v</td>";
            $Blood_Pressure .="<td>$Blood_Pressure_v</td>";
            $oxygen_saturation .="<td>$oxygen_saturation_v</td>";
            $Pulse_Blood .="<td>$Pulse_Blood_v</td>";
            $Resp_Bpressure .="<td>$Resp_Bpressure_v</td>";
            $blood_transfusion .="<td>$blood_transfusion_v</td>";
            $body_weight .="<td>$body_weight_v</td>";
        }
    }
            ?>
            <tr style="background: #dedede"><td><b>DATE/TIME</b></td><?= $date_time ?></tr>
                <tr>
                    <td width='8%'><b>BLOOD <br>PRESSURE</b></td><?= $Blood_Pressure ?>
                </tr>
                <tr>
                    <td><b>OXYGEN <br>SATURATION</b></td><?= $oxygen_saturation ?>
                </tr>
                <tr>
                    <td><b>PULSE</b> </td><?= $Pulse_Blood ?>
                </tr>
                <tr>
                    <td><b>RESPIRATION</b> </td><?= $Resp_Bpressure ?>
                </tr>
                <tr>
                    <td><b>BLOOD TRAN</b> </td><?= $blood_transfusion ?>
                </tr>
                <tr>
                  <td><b>BWT</b> </td><?= $body_weight ?>
                </tr>
        </table>
        <table class="table">
            <tr><caption><b>KEY</b></caption></tr>
            <tr>
                <td>
                    <b>Temperature </b> <img src="observation_chart/temperature.png"/>
                    <div style='width:100px;background:#000000;height:10px;float:right'></div>
                </td>
                <td>
                    <b>Pulse Rate </b> <img src="observation_chart/pulse_rate.png"/>
                    <div style='width:100px;background:#CCA400;height:10px;float:right'></div>
                </td>
            </tr>
            <tr>
                <td>
                    <b>Systolic Pressure </b> <img src="observation_chart/systolic_pressure.png"/>
                    <div style='width:100px;background:#054D99;height:10px;float:right'></div>
                </td>
                <td>
                    <b>Diastolic Pressure</b> <img src="observation_chart/diastolic_pressure.png"/>
                    <div style='width:100px;background:#054D99;height:10px;float:right'></div>
                </td>
            </tr>
            <tr>
                <td>
                    <b>Blood Transfusion </b> <img src="observation_chart/blood_transfusion.png"/>
                    <div style='width:100px;background:#CC0000;height:10px;float:right'></div>
                </td>
                <td>
                    <b>Body Weight</b> <img src="observation_chart/body_weight.png"/>
                    <div style='width:100px;background:#AA136F;height:10px;float:right'></div>
                </td>
            </tr>
        </table>
    </div>
</div>


<script>
    
function observation_chart_graph(temperature,pulse_rate,systoric_pressure,diastolic_pressure,Resp_Bpressure,oxygen_saturation,body_weight,blood_transfusion){
    var customImg = new Image();
    var temperature_img=new Image();
    var pulse_rate_img=new Image();
    var systoric_pressure_img=new Image();
    var diastolic_pressure_img=new Image();
    var Resp_Bpressure_img=new Image();
    var body_weight_img=new Image();
    var oxygen_saturation_img=new Image();
    var blood_transfusion_img=new Image();
    temperature_img.src = 'observation_chart/temperature.png';
    pulse_rate_img.src = 'observation_chart/pulse_rate.png';
    systoric_pressure_img.src = 'observation_chart/systolic_pressure.png';
    diastolic_pressure_img.src = 'observation_chart/diastolic_pressure.png';
    Resp_Bpressure_img.src = 'observation_chart/soundfield_blue.png';
    body_weight_img.src = 'observation_chart/body_weight.png';
    oxygen_saturation_img.src = 'observation_chart/temperature.png';
    blood_transfusion_img.src = 'observation_chart/blood_transfusion.png';
     $.jqplot.config.enablePlugins=true;
     
    var plot1 = $.jqplot('observation_chart_graph', [temperature,pulse_rate,systoric_pressure,diastolic_pressure,Resp_Bpressure,oxygen_saturation,body_weight,blood_transfusion], {
        title:'OBSERVATION CHART',
        axesDefaults: {
            labelRenderer: $.jqplot.CanvasAxisLabelRenderer,
          },
        axes:{
            xaxis:{ 
//                max:31,
//                min:0,
//                tickInterval:1,
//                min: "2011-08-01",
//                max: "2011-09-30",
//                tickInterval: "7 days",
//                drawMajorGridlines: false,
//                label: "Date And Time",
//                renderer:$.jqplot.DateAxisRenderer, 
//                rendererOptions:{
//                    tickRenderer:$.jqplot.CanvasAxisTickRenderer
//                },
//                
////                ticks:['1','2','3','4','5','6','7','8'],
//                tickOptions:{ 
////                    angle: -90,
//                    fontSize:'10pt', 
//                }
                renderer: $.jqplot.DateAxisRenderer,
                tickRenderer: $.jqplot.CanvasAxisTickRenderer,
                tickOptions: {
//                    formatString: "%b %e",
                    formatString: '%d/%m/%Y %H:%M',
                    angle: -30,
                    textColor: '#454545'
                },
//                min: "2019-08-01 00:00:00",
//                max: "2019-08-30 00:00:00",
//                
                min: "<?= $start_date ?>",
                max: "<?= $end_date ?>",
                

//                tickInterval: "1 days",
                drawMajorGridlines: true
            },
            yaxis:{
                ticks:['34','35','36','37','38','39','40','41'],
                label: "Temperature C",
                rendererOptions:{
                    tickRenderer:$.jqplot.CanvasAxisTickRenderer},
                    tickOptions:{
                        fontSize:'10pt',                        
                    }
            }
            ,
            y2axis:{
                ticks:['40','60','80','100','120','140','160','180'],
                label: "Pressure",
                rendererOptions:{
                    tickRenderer:$.jqplot.CanvasAxisTickRenderer},
                    tickOptions:{
                        fontSize:'10pt',                        
                    }
            }
        },
  grid: {
              drawBorder: false,
              shadow: false,
              background: '#fff'
          },
  highlighter: {
        show: true,
        sizeAdjust: 20
      },      
        series:[{
            yaxis : 'yaxis',
            label : 'dataForAxis1',
            markerRenderer:$.jqplot.ImageMarkerRenderer,
            markerOptions: {
                    show:true,
                    imageElement:temperature_img,
                    xOffset:-7,
                    yOffset:-7				
            },lineWidth:2,fillAndStroke:true,color:'#000000',
        }
        , {
            yaxis : 'y2axis',
            label : 'dataForAxis2',
            markerRenderer:$.jqplot.ImageMarkerRenderer,
            markerOptions: {
                    show:true,
                    imageElement:pulse_rate_img,
                    xOffset:-7,
                    yOffset:-7				
            },lineWidth:2,fillAndStroke:true,color:'#CCA400',
        }
        , {
            yaxis : 'y2axis',
            label : 'dataForAxis2',
            markerRenderer:$.jqplot.ImageMarkerRenderer,
            markerOptions: {
                    show:true,
                    imageElement:systoric_pressure_img,
                    xOffset:-7,
                    yOffset:-7				
            },lineWidth:2,fillAndStroke:true,color:'#054D99',
        }
        , {
            yaxis : 'y2axis',
            label : 'dataForAxis2',
             markerRenderer:$.jqplot.ImageMarkerRenderer,
            markerOptions: {
                    show:true,
                    imageElement:diastolic_pressure_img,
                    xOffset:-7,
                    yOffset:-7				
            },lineWidth:2,fillAndStroke:true,color:'#054D99',
        }
        , {
            yaxis : 'yaxis',
            label : 'dataForAxis1',
             markerRenderer:$.jqplot.ImageMarkerRenderer,
            markerOptions: {
                    show:true,
                    imageElement:Resp_Bpressure_img,
                    xOffset:-7,
                    yOffset:-7				
            },lineWidth:2,fillAndStroke:true,
        }
        , {
            yaxis : 'y2axis',
            label : 'dataForAxis2',
//            markerRenderer:$.jqplot.ImageMarkerRenderer,
//            markerOptions: {
//                    show:true,
//                    imageElement:oxygen_saturation_img,
//                    xOffset:-7,
//                    yOffset:-7				
//            },lineWidth:2,fillAndStroke:true,
        }
        , {
            yaxis : 'y2axis',
            label : 'dataForAxis2',
            markerRenderer:$.jqplot.ImageMarkerRenderer,
            markerOptions: {
                    show:true,
                    imageElement:body_weight_img,
                    xOffset:-7,
                    yOffset:-7				
            },lineWidth:2,fillAndStroke:true,color:'#AA136F',
        }
        , {
            yaxis : 'y2axis',
            label : 'dataForAxis2',
            markerRenderer:$.jqplot.ImageMarkerRenderer,
            markerOptions: {
                    show:true,
                    imageElement:blood_transfusion_img,
                    xOffset:-7,
                    yOffset:-7				
            },lineWidth:2,fillAndStroke:true,color:'#CC0000',
        }
                ],
//        series:[{ markerRenderer:$.jqplot.ImageMarkerRenderer,
//			markerOptions: {
//				show:true,
//				imageElement:customImg,
//				xOffset:-7,
//				yOffset:-7				
//			},lineWidth:2,fillAndStroke:true,color:'red'},{ lineWidth:2, markerOptions:{ style:'filledDiamond' }},{ lineWidth:2, markerOptions:{ style:'diamond' }}],
        cursor:{
            zoom:true,
            looseZoom: true
        }
    });

}
    
    $(document).ready(function(){
        //observation_chart_graph([[]],[[]],[[]],[[]],[[]],[[]])
    });
</script>
