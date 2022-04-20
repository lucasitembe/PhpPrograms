<style>
    .otherdoclinks:hover{
        text-decoration:underline;
        color: #000000; 
        cursor:pointer; 
    }
</style>
<style media="screen">
  #chart1{
    height: 400px;
    margin:15px auto;
    width: 100%;
  }
  #chart2{
    height: 480px;
    margin:15px auto;
    width: 100%;
  }

  .input-to-graph{
    display: inline-block;
  }
</style>
<?php
include("./includes/header.php");
include("./includes/connection.php");
include './includes/cleaninput.php';
// session_start();

//        echo '<pre>';
//        print_r($_SESSION['hospitalConsultaioninfo']);exit;
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
    $Patient_Payment_Item_List_ID = 0;
}
if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'])) {
        if ($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'] != 'yes') {
            header("Location: ./index.php?InvalidPrivilege=yes");
        }
    } else {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}



?>
<?php
$Registration_ID=$_GET['Registration_ID'];
$previous_notes=$_GET['previous_notes'];
$from_consulted=$_GET['from_consulted'];

    if(isset($_GET['Registration_ID'])){
        $Registration_ID=$_GET['Registration_ID'];
    }
    if(isset($_GET['from_consulted'])){
        $from_consulted=$_GET['from_consulted'];
    }
    if(isset($_GET['consultation_ID'])){
        $consultation_ID=$_GET['consultation_ID'];
    }   
    if (isset($_GET['Patient_Payment_Item_List_ID'])) {
      $Patient_Payment_Item_List_ID = $_GET['Patient_Payment_Item_List_ID'];
      $Patient_Payment_Item_List_ID_consultation=$Patient_Payment_Item_List_ID;
    } else {
    //header("Location: ./index.php?InvalidPrivilege=yes");
      $Patient_Payment_Item_List_ID = 0;
      $Patient_Payment_Item_List_ID_consultation = 0;
    }
    
    if (isset($_GET['Patient_Payment_ID'])) {
      $Patient_Payment_ID = $_GET['Patient_Payment_ID'];
      $Patient_Payment_ID_consultation=$Patient_Payment_ID;
    } else {
    //header("Location: ./index.php?InvalidPrivilege=yes");
      $Patient_Payment_ID = 0;
      $Patient_Payment_ID_consultation = 0;
    }
    if(isset($_GET['Admission_ID'])){
      $Admission_ID=$_GET['Admission_ID'];
  }else{
      $Admission_ID='';
  }

    if (isset($_GET['Registration_ID']) && $_GET['Registration_ID'] != 0) {
      $select_patien_details = mysqli_query($conn, "
      SELECT pr.Sponsor_ID,Member_Number,Patient_Name, Registration_ID,Gender,Guarantor_Name,Date_Of_Birth
        FROM
          tbl_patient_registration pr,
          tbl_sponsor sp
        WHERE
          pr.Registration_ID = '" . $Registration_ID . "' AND
          sp.Sponsor_ID = pr.Sponsor_ID
          ") or die(mysqli_error($conn));
      $no = mysqli_num_rows($select_patien_details);
      if ($no > 0) {
          while ($row = mysqli_fetch_array($select_patien_details)) {
              $Member_Number = $row['Member_Number'];
              $Patient_Name = $row['Patient_Name'];
              $Registration_ID = $row['Registration_ID'];
              $Gender = $row['Gender'];
              $Guarantor_Name  = $row['Guarantor_Name'];
              $Sponsor_ID = $row['Sponsor_ID'];
              $DOB = $row['Date_Of_Birth'];
          }
      } else {
          $Guarantor_Name  = '';
          $Member_Number = '';
          $Patient_Name = '';
          $Gender = '';
          $Registration_ID = 0;
      }
  } else {
      $Member_Number = '';
      $Patient_Name = '';
      $Gender = '';
      $Registration_ID = 0;
  }
  
  $age = date_diff(date_create($DOB), date_create('today'))->y;
  $checkgender = '';
  if (strtolower($Gender) == 'male') {
      $checkgender = "onclick='notifyUser(this)'";
  }
?>
    <a href='print_pediatric_record.php?Registration_ID=<?= $Registration_ID ?>&consultation_ID=<?= $consultation_ID ?>&SearchListDirectCashPatientBilling=SearchListDirectCashPatientBillingThisPage&from_consulted=<?= $from_consulted ?>' class='art-button-green' target="_black">
       PREVIOUS DATA
    </a>
    <!-- <a href='previous_pediatric_graph.php?Registration_ID=<?= $Registration_ID ?>&consultation_ID=<?= $consultation_ID ?>&SearchListDirectCashPatientBilling=SearchListDirectCashPatientBillingThisPage&from_consulted=<?= $from_consulted ?>' class='art-button-green' target="_black">
       PREVIOUS DATA2
    </a> -->
    <a href='inpatientclinicalnotes.php?Registration_ID=<?= $Registration_ID ?>&consultation_ID=<?= $consultation_ID ?>&Patient_Payment_ID=<?php echo $Patient_Payment_ID; ?>&Patient_Payment_Item_List_ID=<?php echo $Patient_Payment_Item_List_ID; ?>&from_consulted=<?= $from_consulted ?>' class='art-button-green'>
       BACK
    </a>
    
<fieldset>  


<div id="show"></div>
        <legend align="center" style='padding:10px; color:white; background-color:#2D8AAF; text-align:center'><b>
            <b>PEDIATRIC PATIENT</b><br />
            <span style='color:yellow;'><?php echo "" . $Patient_Name . "  | " . $Gender . " | " . $age . " years | " . $Guarantor_Name  . ""; ?></span></b>
        </legend>
        <input type="hidden" value="<?php echo $Registration_ID;?>" id="Registration_ID">
        <input type="hidden" value="<?php echo $consultation_ID;?>" id="consultation_ID">
        <input type="hidden" value="<?php echo $Admission_ID;?>" id="Admission_ID">
        <center>
        <fieldset style='overflow-y:scroll; height:440px' > 
            <table class="table table-striped table-hover">
                <thead style="background-color:#a3c0cc">
                    <th colspan="10" style="text-align:center;font-size:20px;color:white">Date : <?php echo date("Y-m-d");?></th>
                </thead>
                <thead style="background-color:bdb5ac">
                <!-- bdb5ac -->
                <!-- e6eded -->
                <!-- a3c0cc -->
                    <tr>
                        <th>Time</th>
                        <th>Heart Rate</th>
                        <th>Respiratory Rate</th>
                        <th>PSO2</th>
                        <th>temperature(°C )</th>
                        <th>Blood Pressure Sytolic(mmHg)</th>
                        <th>Blood Pressure Diasotlic(mmHg)</th>
                        <th>Pulse Pressure(mmHg)</th>
                        <th>Map(mmHg)</th>
                        <th></th>
                    </tr>
                    </thead>
                <tbody>
                <tr> 
                        <td>
                        <input type="text"   id="time" name="time" class="form-control" readonly style="text-align:center;">
                        </td>
                        <td ><input type="text" id="heart_rate" name="heart_rate" class="form-control" style="text-align:center;"></td>
                        <td ><input type="text" id="respiratory_rate" name="respiratory_rate" class="form-control" style="text-align:center;"></td>
                        <td ><input type="text" id="pso2" name="pso2" class="form-control" style="text-align:center;"></td>
                        <td ><input type="text" id="temperature" name="temperature" class="form-control" style="text-align:center;"></td>
                        <td ><input type="text" id="blood_pressure_sytolic" name="blood_pressure_sytolic" class="form-control" onkeyup="calculate_map()" style="text-align:center;"></td>
                        <td ><input type="text" id="blood_pressure_diasotlic" name="blood_pressure_diasotlic" class="form-control" onkeyup="calculate_map()" style="text-align:center;"></td>
                        <td ><input type="text" id="pulse_pressure" name="pulse_pressure" class="form-control" disabled style="text-align:center;"> </td>
                        <td ><input type="text" id="map" name="map" class="form-control" disabled style="text-align:center;"> </td>
                        <td ><input type="button" id="save_pediatric" name="time" value="Save" class="art-button-green"  onclick="save_pediatric()" style="text-align:center;"></td>
                    </tr>
                    <?php
                        $select_pediatric_graph=mysqli_query($conn,"SELECT pediatric_graph_ID, heart_rate,respiratory_rate, pso2, temperature, blood_pressure_sytolic, blood_pressure_diasotlic, pulse_pressure, map, saved_time, time_min, Registration_ID, Employee_ID, consultation_ID FROM pediatric_graph WHERE Registration_ID='$Registration_ID' and consultation_ID='$consultation_ID' AND DATE(saved_time)=CURDATE() order by pediatric_graph_ID DESC ");
                        while($data=mysqli_fetch_array($select_pediatric_graph)){
                          

                          $date = date('Y-m-d H:i',strtotime($data['time_min']));
                          $time = date('H:i',strtotime($data['time_min']));
                          $splitTimeStamp = explode(":",$time);
                          $TimeStamp_hour = $splitTimeStamp[0];
                          $TimeStamp_min = $splitTimeStamp[1];

                                // $timetest=strtotime($data['time_min']);
                            ?>
                            <tr>
                                <!-- <td style="height:25px;text-align:center;"><?php echo $date;?></td> -->
                                <td style="height:25px;text-align:center;"><?php echo $time;?></td>
                                <!-- <td style="height:25px;text-align:center;"><?php echo $TimeStamp_hour;?></td> -->
                                <td style="height:25px;text-align:center;"><?php echo $data['heart_rate'];?></td>
                                <td style="height:25px;text-align:center;"><?php echo $data['respiratory_rate'];?></td>
                                <td style="height:25px;text-align:center;"><?php echo $data['pso2'];?></td>
                                <td style="height:25px;text-align:center;"><?php echo $data['temperature'];?></td>
                                <td style="height:25px;text-align:center;"><?php echo $data['blood_pressure_sytolic'];?></td>
                                <td style="height:25px;text-align:center;"><?php echo $data['blood_pressure_diasotlic'];?></td>
                                <td style="height:25px;text-align:center;"><?php echo $data['pulse_pressure'];?></td>
                                <td style="height:25px;text-align:center;"><?php echo $data['map'];?></td>
                                <td ></td>
                            </tr>
                            <?php
                        }
                    ?>

                    
                </tbody>
            </table>
            </fieldset>  
            <br>
            
            <fieldset>
            <!-- DIV FOR HEART RATE,PSO2 GRAPH -->
            <div id="chart2">
              </div>
              <!-- DIV FOR BLOOD TEMPERATURE GRAPH -->
              <div id="chart3">
              </div>
            <!-- DIV FOR BLOOD PRESSURE GRAPH -->
              <div id="chart1">
              </div>
            </fieldset>

           
           
        </center>
</fieldset><br/>
<script src="css/jquery.js"></script>
<script src="css/jquery.datetimepicker.js"></script>
<script>
    $('#time').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en',
        //startDate:    'now'
    });
    $('#time').datetimepicker({value: '', step: 01});
</script>

<script>
    function save_pediatric(){
        var Registration_ID = $("#Registration_ID").val();
        var consultation_ID=$("#consultation_ID").val();
        var time=$("#time").val();
        var heart_rate=$("#heart_rate").val();
        var respiratory_rate=$("#respiratory_rate").val();
        var pso2=$("#pso2").val();
        var temperature=$("#temperature").val();
        var blood_pressure_sytolic=$("#blood_pressure_sytolic").val();
        var blood_pressure_diasotlic=$("#blood_pressure_diasotlic").val();
        var pulse_pressure=$("#pulse_pressure").val();
        var map=$("#map").val();
        // var Patient_Payment_ID=$("#Patient_Payment_ID").val();
        // var Patient_Payment_Item_List_ID=$("#Patient_Payment_Item_List_ID").val();
         //alert(Registration_ID+'==='+Registration_ID1);
         if(time==''){
            $("#time").css("border","2px solid red");
            return false;
         }
         if(heart_rate==''){
            $("#heart_rate").css("border","2px solid red");
            return false;
         }
         if(respiratory_rate==''){
            $("#respiratory_rate").css("border","2px solid red");
            return false;
         }
         if(pso2==''){
            $("#pso2").css("border","2px solid red");
            return false;
         }
         if(temperature==''){
            $("#temperature").css("border","2px solid red");
            return false;
         }
         if(blood_pressure_sytolic==''){
            $("#blood_pressure_sytolic").css("border","2px solid red");
            return false;
         }
         if(blood_pressure_diasotlic==''){
            $("#blood_pressure_diasotlic").css("border","2px solid red");
            return false;
         }
         if(pulse_pressure==''){
            $("#pulse_pressure").css("border","2px solid red");
            return false;
         }

            if(confirm("Are you Sure you want to Save")){
                $.ajax({
                type:'post',
                url: 'save_pediatric.php',
                data : {
                    Registration_ID:Registration_ID,
                    consultation_ID:consultation_ID,
                    time:time,
                    heart_rate:heart_rate,
                    respiratory_rate:respiratory_rate,
                    pso2:pso2,
                    temperature:temperature,
                    blood_pressure_sytolic:blood_pressure_sytolic,
                    blood_pressure_diasotlic:blood_pressure_diasotlic,
                    pulse_pressure:pulse_pressure,
                    map:map


                },
                success : function(response){
                    $("#time").val('');
                    $("#heart_rate").val('');
                    $("#respiratory_rate").val('');
                    $("#pso2").val('');
                    $("#temperature").val('');
                    $("#blood_pressure_sytolic").val('');
                    $("#blood_pressure_diasotlic").val('');
                    $("#pulse_pressure").val('');
                    $("#map").val('');

                    $("#time").css("border","1px solid black");
                    $("#heart_rate").css("border","1px solid black");
                    $("#respiratory_rate").css("border","1px solid black");
                    $("#pso2").css("border","1px solid black");
                    $("#temperature").css("border","1px solid black");
                    $("#blood_pressure_sytolic").css("border","1px solid black");
                    $("#blood_pressure_diasotlic").css("border","1px solid black");
                    $("#pulse_pressure").css("border","1px solid black");
                    $("#map").css("border","1px solid black");
                    //location.reload(true);

                }
                });
            
            }  
    }
    function calculate_map(){
        var blood_pressure_sytolic=$("#blood_pressure_sytolic").val();
        var blood_pressure_diasotlic=$("#blood_pressure_diasotlic").val();
        var take_pulse=blood_pressure_sytolic-blood_pressure_diasotlic
        var take=(2*blood_pressure_diasotlic)
        var calc=parseInt(blood_pressure_sytolic) + parseInt(take);
        var take_map=((calc)/3)
        $("#pulse_pressure").val(take_pulse);
        $("#map").val(take_map.toFixed(1));
    }
</script>
<script type="text/javascript" src="jquery.min.js"></script>
<script type="text/javascript" src="jquery.jqplot.js"></script>
<script type="text/javascript" src="plugins/jqplot.canvasTextRenderer.js"></script>
<script type="text/javascript" src="plugins/jqplot.canvasAxisLabelRenderer.js"></script>
<script type="text/javascript" src="plugins/jqplot.canvasTextRenderer.js"></script>
<script type="text/javascript" src="plugins/jqplot.canvasAxisLabelRenderer.js"></script>
<script type="text/javascript" src="plugins/jqplot.highlighter.js"></script>
<link rel="stylesheet" type="text/css" href="jquery.jqplot.css"/>
<!-- <script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script> -->



<script type="text/javascript">


var blood_pressure_time = [[]];
var blood_pressure = [[]];

var temperature_time = [[]];
var temperature_graph = [[]];

var heart_rate_time = [[]];
var heart_rate = [[]];
var respiratory_rate_time = [[]];

function plotFetalGraph(){
//   var powPoints1= [[8,0],[8,10]];
//   var powPoints2 =[[0,4],[8,4]];
//   var powPoints3=[[8,4],[14,10]];
//   var powPoints4 = [[12,4],[18,10]];

  var powPoints1= [[0,0],[0,0]];
  var powPoints2 =[[0,0],[0,0]];
  var powPoints3=[[0,0],[0,0]];
  var powPoints4 = [[0,0],[0,0]];



//################################################BLOOD PRESSURE GRAPH########################################
  var plot3 = $.jqplot('chart1', [powPoints1,powPoints2,powPoints3,powPoints4,blood_pressure_time,blood_pressure],
    {

  axes:{
    xaxis:{
      max:24,
      min:0,
      tickInterval:1,
      label:"Duration(Hour Time In 24 Hrs)"
    },
    yaxis:{

      max:120,
      min:30,
      tickInterval:10,
      label:" BLOOD PRESSURE",
      labelRenderer: $.jqplot.CanvasAxisLabelRenderer,
      tickOptions: {
                      angle: 180
                  }
    },

  },
  grid: {
              drawBorder: false,
              shadow: false,
              // background: 'rgba(0,0,0,0)'  works to make transparent.
              background: 'white'
          },
  highlighter: {
        show: true,
        sizeAdjust: 10.5
      },
  legend:{
    show: true,
    placement: 'outsideGrid',
    location: 'ne',
    rowSpacing: '0px'
  },
      cursor: {
        show: false
      },

      // Set default options on all series, turn on smoothing.
      seriesDefaults: {

          rendererOptions: {
              smooth: true
          }
      },
      // Series options are specified as an array of objects, one object
      // for each series.
      series:[
          {
            // Change our line width and use a diamond shaped marker.
            fill: false, fillAndStroke: true, color: '#000', fillColor: '#000',
            markerOptions: { style:'x' }
          },
          {
            // Don't show a line, just show markers.
            // Make the markers 7 pixels with an 'x' style
            fill: false, fillAndStroke: true, color: '#000', fillColor: '#000',
            
            showLine:true,
            markerOptions: { size: 5, style:"X" }
          },
          {
            // Use (open) circlular markers.
            showLine:true,
            lineWidth:3,
            fill: false, fillAndStroke: true, color: '#000', fillColor: '#000',
            lineColor:"#000",
            markerOptions: { size:5,style:"x" }
          },
          {
            // Use a thicker, 5 pixel line and 10 pixel
            // filled square markers.

            showLine:true,
            lineWidth:3,
            fill: false, fillAndStroke: true, color: '#000', fillColor: '#000',
            markerOptions: {size:5,style:"x"}
          },
          {
            // Use a thicker, 5 pixel line and 10 pixel
            // filled square markers.
            showLine:true,
            lineWidth:4,
            fill: false, fillAndStroke: true, color: '#0079AE', fillColor: '#0079AE',
            markerOptions: {size:11,style:"x" }
          },
          {
            // Use a thicker, 5 pixel line and 10 pixel
            // filled square markers.
            showLine:true,
            lineWidth:4,
            // fill: false, fillAndStroke: true, color: '#000', fillColor: '#000',
            markerOptions: {size:11 }
          },
          {
            // Use a thicker, 5 pixel line and 10 pixel
            // filled square markers.
            showLine:true,
            lineWidth:5,
            fill: false, fillAndStroke: true, color: '#0079AE', fillColor: '#0079AE',
            markerOptions: {size:14 }
          }
      ]
    }
  );
   //##################################GRAPH FOR HEART RATE ,PSO2,RESPIRATORY RATE######################################
  var plot4 = $.jqplot('chart2', [powPoints1,powPoints2,powPoints3,powPoints4,heart_rate_time,heart_rate,respiratory_rate_time],
    {

  axes:{
    xaxis:{
      max:24,
      min:0,
      tickInterval:1,
      label:"Duration(Hour Time In 24 Hrs)"
    },
    yaxis:{

      max:210,
      min:10,
      tickInterval:20,
      label:"HEART RATE,PSO2,RESPIRATORY",
      labelRenderer: $.jqplot.CanvasAxisLabelRenderer,
      tickOptions: {
                      angle: 180
                  }
    },

  },
  grid: {
              drawBorder: false,
              shadow: false,
              // background: 'rgba(0,0,0,0)'  works to make transparent.
              background: 'white'
          },
  highlighter: {
        show: true,
        sizeAdjust: 10.5
      },
  legend:{
    show: true,
    placement: 'outsideGrid',
    location: 'ne',
    rowSpacing: '0px'
  },
      cursor: {
        show: false
      },

      // Set default options on all series, turn on smoothing.
      seriesDefaults: {

          rendererOptions: {
              smooth: true
          }
      },
      // Series options are specified as an array of objects, one object
      // for each series.
      series:[
          {
            // Change our line width and use a diamond shaped marker.
            fill: false, fillAndStroke: true, color: '#000', fillColor: '#000',
            markerOptions: { style:'x' }
          },
          {
            // Don't show a line, just show markers.
            // Make the markers 7 pixels with an 'x' style
            fill: false, fillAndStroke: true, color: '#000', fillColor: '#000',
            
            showLine:true,
            markerOptions: { size: 5, style:"X" }
          },
          {
            // Use (open) circlular markers.
            showLine:true,
            lineWidth:4,
            fill: false, fillAndStroke: true, color: '#000', fillColor: '#000',
            lineColor:"#000",
            markerOptions: { size:5,style:"x" }
          },
          {
            // Use a thicker, 5 pixel line and 10 pixel
            // filled square markers.

            showLine:true,
            lineWidth:4,
            fill: false, fillAndStroke: true, color: '#000', fillColor: '#000',
            markerOptions: {size:5,style:"x"}
          },
          {
            // Use a thicker, 5 pixel line and 10 pixel
            // filled square markers.
            showLine:true,
            lineWidth:4,
            fill: false, fillAndStroke: true, color: '#0079AE', fillColor: '#0079AE',
            markerOptions: {size:11,style:"x" }
          },
          {
            // Use a thicker, 5 pixel line and 10 pixel
            // filled square markers.
            showLine:true,
            lineWidth:4,
            fill: false, fillAndStroke: true, color: '#ec7313', fillColor: '#ec7313',
            markerOptions: {size:11 }
          },
          {
            // Use a thicker, 5 pixel line and 10 pixel
            // filled square markers.
            showLine:true,
            lineWidth:4,
            fill: false, fillAndStroke: true, color: '#dc232a', fillColor: '#dc232a',
            markerOptions: {size:11 }
          }
      ]
    }
  );
  //##################################GRAPH FOR TEMPERATURE################################################
  var plot5 = $.jqplot('chart3', [powPoints1,powPoints2,powPoints3,powPoints4,temperature_time,temperature_graph],
    {

  axes:{
    xaxis:{
      max:24,
      min:0,
      tickInterval:1,
      label:"Duration(Hour Time In 24 Hrs)"
    },
    yaxis:{

      max:40,
      min:32,
      tickInterval:2,
      label:" TEMPERATURE",
      labelRenderer: $.jqplot.CanvasAxisLabelRenderer,
      tickOptions: {
                      angle: 180
                  }
    },

  },
  grid: {
              drawBorder: false,
              shadow: false,
              // background: 'rgba(0,0,0,0)'  works to make transparent.
              background: 'white'
          },
  highlighter: {
        show: true,
        sizeAdjust: 10.5
      },
  legend:{
    show: true,
    placement: 'outsideGrid',
    location: 'ne',
    rowSpacing: '0px'
  },
      cursor: {
        show: false
      },

      // Set default options on all series, turn on smoothing.
      seriesDefaults: {

          rendererOptions: {
              smooth: true
          }
      },
      // Series options are specified as an array of objects, one object
      // for each series.
      series:[
          {
            // Change our line width and use a diamond shaped marker.
            fill: false, fillAndStroke: true, color: '#000', fillColor: '#000',
            markerOptions: { style:'x' }
          },
          {
            // Don't show a line, just show markers.
            // Make the markers 7 pixels with an 'x' style
            fill: false, fillAndStroke: true, color: '#000', fillColor: '#000',
            
            showLine:true,
            markerOptions: { size: 5, style:"X" }
          },
          {
            // Use (open) circlular markers.
            showLine:true,
            lineWidth:3,
            fill: false, fillAndStroke: true, color: '#000', fillColor: '#000',
            lineColor:"#000",
            markerOptions: { size:5,style:"x" }
          },
          {
            // Use a thicker, 5 pixel line and 10 pixel
            // filled square markers.

            showLine:true,
            lineWidth:3,
            fill: false, fillAndStroke: true, color: '#000', fillColor: '#000',
            markerOptions: {size:5,style:"x"}
          },
          {
            // Use a thicker, 5 pixel line and 10 pixel
            // filled square markers.
            showLine:true,
            lineWidth:4,
            fill: false, fillAndStroke: true, color: '#0079AE', fillColor: '#0079AE',
            markerOptions: {size:11,style:"x" }
          },
          {
            // Use a thicker, 5 pixel line and 10 pixel
            // filled square markers.
            showLine:true,
            lineWidth:4,
            // fill: false, fillAndStroke: true, color: '#000', fillColor: '#000',
            markerOptions: {size:11 }
          },
          {
            // Use a thicker, 5 pixel line and 10 pixel
            // filled square markers.
            showLine:true,
            lineWidth:5,
            fill: false, fillAndStroke: true, color: '#0079AE', fillColor: '#0079AE',
            markerOptions: {size:14 }
          }
      ]
    }
  );
  //##################################END GRAPH FOR TEMPERATURE############################################

}


$(document).ready(function(){


  var Registration_ID = $("#Registration_ID").val();
  var consultation_ID = $("#consultation_ID").val();

  // f#########################################etch BLOOD PRESSURE DIASOTLIC##############################
  $.ajax({
    url:"fetch_pediatric_diasotlic.php",
    type:"POST",
    data:{Registration_ID:Registration_ID,consultation_ID:consultation_ID},
    success:function(data){
    
      var blood_pressure_data = JSON.parse(data);
      var point=[];

    for (var i = 0; i <blood_pressure_data.length; i++) {
      var counter = blood_pressure_data[i];

      point = [parseFloat(counter.final_time_diasotlic),parseFloat(counter.blood_pressure_diasotlic)];

      blood_pressure_time.push(point)
    
    plotFetalGraph()
    }
    },
    error: function( data, status, error ) { 
                console.log(data);
                console.log(status);
                console.log(error);
            }
  });

// ################################fetch BLOOD PRESSURE SYSTOLIC#########################################

  $.ajax({
    url:"fetch_pediatric_systolic.php",
    type:"POST",
    data:{Registration_ID:Registration_ID,consultation_ID:consultation_ID},
    success:function(data){
    
      var blood_pressure_data = JSON.parse(data);
      var point=[];

    for (var i = 0; i <blood_pressure_data.length; i++) {
      var counter = blood_pressure_data[i];

      point = [parseFloat(counter.final_time_systolic),parseFloat(counter.blood_pressure_sytolic)];

      blood_pressure.push(point)
    
    plotFetalGraph()
    }
    },
    error: function( data, status, error ) { 
                console.log(data);
                console.log(status);
                console.log(error);
            }
  })
  // #################################FETCH TEMPERATURE DATA########################################

  $.ajax({
    url:"fetch_pediatric_temperature.php",
    type:"POST",
    data:{Registration_ID:Registration_ID,consultation_ID:consultation_ID},
    success:function(data){
    
      var blood_pressure_data = JSON.parse(data);
      var point=[];

    for (var i = 0; i <blood_pressure_data.length; i++) {
      var counter = blood_pressure_data[i];

      point = [parseFloat(counter.final_time_temperature),parseFloat(counter.temperature)];

      temperature_graph.push(point)
    
    plotFetalGraph()
    }
    },
    error: function( data, status, error ) { 
                console.log(data);
                console.log(status);
                console.log(error);
            }
  })

  // #################################FETCH RESPIRATORY RATE ########################################

  $.ajax({
    url:"fetch_pediatric_respiratory.php",
    type:"POST",
    data:{Registration_ID:Registration_ID,consultation_ID:consultation_ID},
    success:function(data){
    
      var blood_pressure_data = JSON.parse(data);
      var point=[];

    for (var i = 0; i <blood_pressure_data.length; i++) {
      var counter = blood_pressure_data[i];

      point = [parseFloat(counter.final_time_respiratory_rate),parseFloat(counter.respiratory_rate)];

      heart_rate.push(point)
    
    plotFetalGraph()
    }
    },
    error: function( data, status, error ) { 
                console.log(data);
                console.log(status);
                console.log(error);
            }
  })
  // #####################################FETCH PSO2 DATA############################################

  $.ajax({
    url:"fetch_pediatric_pso2.php",
    type:"POST",
    data:{Registration_ID:Registration_ID,consultation_ID:consultation_ID},
    success:function(data){
    
      var blood_pressure_data = JSON.parse(data);
      var point=[];

    for (var i = 0; i <blood_pressure_data.length; i++) {
      var counter = blood_pressure_data[i];

      point = [parseFloat(counter.final_time_pso2),parseFloat(counter.pso2)];

      heart_rate_time.push(point)
    
    plotFetalGraph()
    }
    },
    error: function( data, status, error ) { 
                console.log(data);
                console.log(status);
                console.log(error);
            }
  })

  // #############################FETCH HEART RATE DATA########################################

  $.ajax({
    url:"fetch_pediatric_heart_rate.php",
    type:"POST",
    data:{Registration_ID:Registration_ID,consultation_ID:consultation_ID},
    success:function(data){
    
      var blood_pressure_data = JSON.parse(data);
      var point=[];

    for (var i = 0; i <blood_pressure_data.length; i++) {
      var counter = blood_pressure_data[i];

      point = [parseFloat(counter.final_time_heart_rate),parseFloat(counter.heart_rate)];

      respiratory_rate_time.push(point)
    
    plotFetalGraph()
    }
    },
    error: function( data, status, error ) { 
                console.log(data);
                console.log(status);
                console.log(error);
            }
  })


  

// Some simple loops to build up data arrays.
// plotFetalGraph()
});




$('#heart_rate').keyup(function () { 
    this.value = this.value.replace(/[^0-9\.]/g,'');
});
$('#respiratory_rate').keyup(function () { 
    this.value = this.value.replace(/[^0-9\.]/g,'');
});
$('#temperature').keyup(function () { 
    this.value = this.value.replace(/[^0-9\.]/g,'');
});
$('#blood_pressure_sytolic').keyup(function () { 
    this.value = this.value.replace(/[^0-9\.]/g,'');
});
$('#blood_pressure_diasotlic').keyup(function () { 
    this.value = this.value.replace(/[^0-9\.]/g,'');
});
$('#pso2').keyup(function () { 
    this.value = this.value.replace(/[^0-9\.]/g,'');
});

</script>
<script>
  $('#time').datetimepicker({
        dayOfWeekStart : 1,
        lang:'en',
        startDate:  'now'
    });
    $('#time').datetimepicker({value:'',step:05});

    function open_previous_data(){
      var Registration_ID = $("#Registration_ID").val();
      var consultation_ID=$("#consultation_ID").val();
      $.ajax({
            type:'GET',
            url:'pediatric_previous_data.php',
            data : {
                Registration_ID:Registration_ID,
                consultation_ID:consultation_ID
            },
                success : function(data){
                    $('#show').dialog({
                        autoOpen:true,
                        width:'95%',
                        position:["center",50],
                        title:'PEDIATRIC',
                        modal:true
                       
                    });  
                    $('#show').html(data);
                    $('#show').dialog('data');
                }
            })
    }
</script>
<?php
    include("./includes/footer.php");
?>
