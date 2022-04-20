<?php
// include("./includes/header.php");
// include("./includes/connection.php");
if (isset($_GET['consultation_id'])) {
  $consultation_id = $_GET['consultation_id'];
}
if (isset($_GET['admision_id'])) {
   $admision_id = $_GET['admision_id'];
}

if (isset($_GET['patient_id'])) {
  $patient_id = $_GET['patient_id'];
}

// get patient details
if (isset($_GET['patient_id']) && $_GET['patient_id'] != 0) {
    $select_patien_details = mysqli_query($conn,"
		SELECT pr.Sponsor_ID,Member_Number,Patient_Name, Registration_ID,Gender,Guarantor_Name,Date_Of_Birth
			FROM
				tbl_patient_registration pr,
				tbl_sponsor sp
			WHERE
				pr.Registration_ID = '" . $patient_id . "' AND
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


 ?>
<!-- <a href="labour_atenal_neonatal_record.php?consultation_id=<?= $consultation_id;?>&patient_id=<?=$patient_id;?>&admission_id=<?=$admision_id?>" class="art-button-green">BACK</a> -->

<style media="screen">

  #chart2{
    height: 250px !important;
    /* margin:15px auto; */
    width: 93vw !important;
  }

  #chart2{
    height: 130px;
    margin:15px auto;
    width: 85vw;
  }
  #chart1{
    height: 480px;
    margin:15px auto;
    width: 85vw;
  }

  .input-to-graph{
    display: inline-block;
  }

#table{
  width: 90vw;
  background: white;
  float: left;
  margin-left: 25px;
}
  table{
    border-collapse: collapse;

  }

/* #time td{
  width: 1.5%;
} */
  table,tr,td{
    border: 1px solid grey;
    box-sizing: border-box;
  }
#table td{
  width: 1.9%;
}
#table #time{
  height: 20px;
}
#table-input
{
  background: #fff;
  width: 20%;
  margin-top:20px;
}

#table-input td{
  text-align: center !important;
}
#table-input td:hover{
  background: grey;
}
.btn-inputl{
  display: block;
  height: 25px !important;
  width: 60px !important;
  background: #fff;
}
.btn-inputm{
  display: block;
  height: 25px !important;
  width: 60px !important;
  background: #fff;
}
.btn-input:hover{
  background: grey;
}
.show-time{
  text-align: center;
}
#remark td{
  box-sizing: border-box;
}

#number-time{
  float: left;
  margin-left: 117px;
}
#table #moulding{
  height: 20px !important;
}
#liqour_remark{
  height: 20px !important;
}
#time td {
  padding-left: -3;
  border: none;
}

#number-time{
  width: 84vw;
}
#number-time tr td{
  border: none !important;
  width: 2.7% !important;
  box-sizing: border-box;
  font-size: 10px;
  text-align: left;
}
#number-time ,tr{
  border: none !important;
}
#time{
  border: none !important;
}
</style>


 <center>
   <!-- <fieldset> -->
     <!-- <legend style="font-weight:bold"align=center>
       <div style="height:34px;margin:0px;padding:0px;font-weight:bold">
         <p style="margin:0px;padding:0px;">FETAL CONDITION</p>
         <p style="color:yellow;margin:0px;padding:0px; "><span style="margin-right:3px;"><?=$Patient_Name?> |</span><span style="margin-right:3px;"><?=$Gender?> |</span> <span style="margin-right:3px;"><?=$age?> | </span> <span style="margin-right:3px;"><?=$Guarantor_Name?></span> </p>
       </div>
   </legend> -->
     <form class="" action="" method="post" id="labour_form">
       <input type="hidden" name="patient_id" id="patient_id" value="<?=$patient_id; ?>">
       <input type="hidden" name="admission_id" id="admission_id" value="<?=$admision_id; ?>">
       <div id="chart2">
       </div>

       <div class="">
         <span>Fetal Heart Rate</span><span><input style="width:10%;;" type="text" name="fetal_heart_rate" value="" id="pointx"> </span>
         <span>Time</span><span><input style="width:10%;;" id="pointy" type="text" name="time" value=""> </span>
         <span><button type="button"style="width:100px; height:30px !important;" id="addy"  name="button">Add</button> </span>
       </div>

       
       <div class="">


      <hr style="border:10px solid #C0C0C0; width:96vw;" />
      </div>

       <div id="chart">
         <br />
          <table id="table">
            <tr id="liqour_remark">
              <td style="width:8%; font-weight:bold;">State Of Liqour</td>
              <?php
              for ($i=0; $i <= 48; $i++) {
                ?>
                <td id="<?=($i/2);?>"></td>
              <?php }
               ?>
               <br />
            </tr>
            <tr id="moulding">
              <td style="width:8%; font-weight:bold;">Moulding</td>
              <?php
              for ($i=0; $i <=48; $i++) {
                ?>
                <td id="<?=($i/2);?>"></td>
              <?php }
               ?>
            </tr>
            <tr id="caput">
              <td style="width:8%; font-weight:bold;">Caput</td>
              <?php
              for ($i=0; $i <=48; $i++) {
                ?>
                <td id="<?=($i/2);?>"></td>
              <?php }
               ?>
            </tr>
            <tr id="position">
              <td style="width:8%; font-weight:bold;">Position</td>
              <?php
              for ($i=0; $i <=48; $i++) {
                ?>
                <td id="<?=($i/2);?>"></td>
              <?php }
               ?>
            </tr>
            </table>
            <table id="number-time">
              <tr id="time">

                <?php
                for ($i=0; $i <= 48; $i++) {
                  ?>
                  <td id="<?=($i/2);?>"><?=($i/2);?></td>
                  
                <?php }
                 ?>
              </tr>

            </table>
       </div>


       <div class="">
          <br />
         <table id='table-input' width="100%;">
           <tr>
             <td ><p style="width:100px !important; margin:0px; padding:0px;">State Of Liqour</p></td>
             <!-- <td><button class="btn-inputl">B</button></td> -->
             <td><button class="btn-inputl">C</button></td>
             <td><button class="btn-inputl">I</button></td>
             <td><button class="btn-inputl">M</button></td>
             <td></td>
             <td ><p style="width:100px !important; margin:0px; padding:0px;">Moulding</p></td>
             <td><button class="btn-inputm">0</button></td>
             <td><button class="btn-inputm">+1</button></td>
             <td><button class="btn-inputm">+2</button></td>
             <td><button class="btn-inputm">+3</button></td>
             <td><input class="art-button-green" value="Caput" id="caput_open"></td>
             <td><input class="art-button-green" value="Position" id="position_open"></td>
             <!-- <td><button class="art-button-green" value="Position" id="position_open"></td> -->
              <td><button class="audit" id="audit" class="art-button-green">Audit</button></td>
           </tr>
         </table>
         <div class="">

         <hr style="border:10px solid #C0C0C0; width:96vw;" />
       </div>
         <h4>Progress Of Labour</h4>
          <div id="chart1">
          </div>
          <!-- ######################PROGRESS OF LABOUR GRAPH################### -->
  
      <div class="input-to-graph">
         <span style="font-weight:bold;">Cervical Dilation</span>
         <span>X:<input style="width:10%; "type="text" name="x"  class="input-fy" value=""> </span>
         <span>Time:<input style="width:10%; "type="text" name="o" class="input-fx" value=""> </span>
         <span><button type="button" id="add-first" name="button" style="width:80px; height:30px !important;">Add</button> </span>
       </div>

       <div class="input-to-graph">
         <span style="font-weight:bold;">Descent</span>
         <span>X:<input style="width:10%; "type="text" name="x"  class="input-sy" value=""> </span>
         <span>Time:<input style="width:10%; "type="text" name="o" class="input-sx" value=""> </span>
         <span><button type="button" id="add-second" name="button" style="width:80px; height:30px !important;">Add</button> </span>
       </div>

       <div class="input-to-graph">
         <!-- <button type="button" style="width:40px; height:30px !important; color:#fff !important;" class="art-button-green" name="button">Audit</button> -->
       </div>
<!-- ###################### END PROGRESS OF LABOUR GRAPH################### -->


<!--  start caput-->

<div id="show_dialog_caput">
  <center>
    <br />
  <table style="border:none;width:100%;">
   <tr style="height:35px; width:60%;">
     <td style="text-align:center" >Caput</td>
     <!-- <td  style="width:30%; border:none;"></td> -->
     <td style="text-align:center">Select Time</td>
   </tr>

     <tr style="height:35px; width:60%;">
     <td style="text-align:center" id="selected_liqour">
      <select id="caput_remark" style="width:90px;">
          <option value="0">0</option>
          <option value="+">+</option>
          <option value="++">++</option>
          <option value="+++">+++</option>
      </select>
     </td>
     <!-- <td  style="width:30%; border:none;"></td> -->
     <td style="text-align:center">
       <select id="time_caput" name="time_caput" required>
         <option value="">--Select Time--</option>
         <?php
         for ($i=0; $i <= 24 ; $i++){
           ?>
           <option value="<?=($i); ?>"><?=($i) ;?></option>
         <?php
         }
          ?>
       </select>
     </td>
   </tr>
  </table>
  <br />
  <input type="submit" style="width:20%;" name="submit" class="art-button-green" id="save_caput" value="Save">
</center>
</div>



<!--  end caput -->
<!-- <div id="show_dialog_soft">
  <center>
    <br />
  <table style="border:none;width:100%;">
   <tr style="height:35px; width:60%;">
     <td style="text-align:center" >Soft,Rigid</td> -->
     <!-- <td  style="width:30%; border:none;"></td> -->
     <!-- <td style="text-align:center">Select Time</td>
   </tr> -->

     <!-- <tr style="height:35px; width:60%;">
     <td style="text-align:center" id="selected_liqour">
      <select id="soft_remark" style="width:90px;">
          <option value="Soft">Soft</option>
          <option value="Rigid">Rigid</option>
      </select>
     </td> -->
     <!-- <td  style="width:30%; border:none;"></td> -->
     <!-- <td style="text-align:center">
       <select id="time_soft" name="time_soft" required>
         <option value="">--Select Time--</option>
         < ?php
         for ($i=0; $i <= 24 ; $i++){
           ?>
           <option value="< ?=($i); ?>"><?=($i) ;?></option>
         < ?php
         }
          ?>
       </select>
     </td>
   </tr> -->
  <!-- </table>
  <br />
  <input type="submit" style="width:20%;" name="submit" class="art-button-green" id="save_soft" value="Save">
</center>
</div> -->

<!-- <div id="show_dialog_thin">
  <center>
    <br />
  <table style="border:none;width:100%;">
   <tr style="height:35px; width:60%;">
     <td style="text-align:center" >Thin,Thick,SWellen</td> -->
     <!-- <td  style="width:30%; border:none;"></td> -->
     <!-- <td style="text-align:center">Select Time</td>
   </tr>

     <tr style="height:35px; width:60%;">
     <td style="text-align:center" id="selected_liqour">
      <select id="thin_remark" style="width:90px;">
          <option value="Thin">Thin</option>
          <option value="Thick">Thick</option>
          <option value="Swellen">Swellen</option> -->
      <!-- </select>
     </td> -->
     <!-- <td  style="width:30%; border:none;"></td> -->
     <!-- <td style="text-align:center">
       <select id="time_thin" name="time_thin" required>
         <option value="">--Select Time--</option>
         < ?php
         for ($i=0; $i <= 24 ; $i++){
           ?>
           <option value="< ?=($i); ?>">< ?=($i) ;?></option>
         < ?php
         }
          ?>
       </select>
     </td>
   </tr>
  </table>
  <br />
  <input type="submit" style="width:20%;" name="submit" class="art-button-green" id="save_thin" value="Save"> -->
<!-- </center>
</div>

<div id="show_dialog_presenting">
  <center>
    <br />
  <table style="border:none;width:100%;">
   <tr style="height:35px; width:60%;">
     <td style="text-align:center" >Presenting number</td> -->
     <!-- <td  style="width:30%; border:none;"></td> -->
     <!-- <td style="text-align:center">Select Time</td>
   </tr>

     <tr style="height:35px; width:60%;">
     <td style="text-align:center" id="selected_liqour">
      <select id="presenting_remark" style="width:90px;">
          <option value="Applicable">Applied</option>
          <option value="Applicable">Not Applied</option>
      </select>
     </td> -->
     <!-- <td  style="width:30%; border:none;"></td> -->
     <!-- <td style="text-align:center">
       <select id="time_presenting" name="time_presenting" required>
         <option value="">--Select Time--</option>
         < ?php
         for ($i=0; $i <= 24 ; $i++){
           ?>
           <option value="< ?=($i); ?>">< ?=($i) ;?></option>
         < ?php
         }
          ?>
       </select>
     </td>
   </tr>
  </table>
  <br />
  <input type="submit" style="width:20%;" name="submit" class="art-button-green" id="save_presenting" value="Save"> -->
<!-- </center>
</div> -->


<!-- start position -->

<div id="show_dialog_position">
  <center>
    <br />
  <table style="border:none;width:100%;">
   <tr style="height:35px; width:60%;">
     <td style="text-align:center" >Position</td>
     <!-- <td  style="width:30%; border:none;"></td> -->
     <td style="text-align:center">Select Time</td>
   </tr>

     <tr style="height:35px; width:60%;">
     <td style="text-align:center" id="selected_liqour">
      <select id="position_remark" style="width:90px;">
          <option>--Select--</option>
          <option value="LOA">LOA</option>
          <option value="LOP">LOP</option>
          <option value="LOL">LOL</option>
          <option value="ROA">ROA</option>
          <option value="ROP">ROP</option>
          <option value="ROL">ROL</option>
      </select>
     </td>
     <!-- <td  style="width:30%; border:none;"></td> -->
     <td style="text-align:center">
       <select id="time_position" name="time_position" required>
         <option value="">--Select Time--</option>
         <?php
         for ($i=0; $i <= 24 ; $i++){
           ?>
           <option value="<?=($i); ?>"><?=($i) ;?></option>
         <?php
         }
          ?>
       </select>
     </td>
   </tr>
  </table>
  <br />
  <input type="submit" style="width:20%;" name="submit" class="art-button-green" id="save_position" value="Save">
</center>
</div>
<!-- end position -->

         <div id="show_dialogl">
           <center>
             <br />
           <table style="border:none;width:100%;">
            <tr style="height:35px; width:60%;">
              <td style="text-align:center" >State Of Liqour</td>
              <!-- <td  style="width:30%; border:none;"></td> -->
              <td style="text-align:center">Select Time</td>
            </tr>

              <tr style="height:35px; width:60%;">
              <td style="text-align:center" id="selected_liqour"></td>
              <!-- <td  style="width:30%; border:none;"></td> -->
              <td style="text-align:center">
                <select class="show-time" name="time" required>
                  <option value="">--Select Time--</option>
                  <?php
                  for ($i=0; $i <= 48 ; $i++){
                    ?>
                    <option value="<?=($i/2); ?>"><?=($i/2) ;?></option>
                  <?php
                  }
                   ?>
                </select>
              </td>
            </tr>
           </table>
           <br />
           <input type="submit" style="width:20%;" name="submit" class="art-button-green" id="savel" value="Save">
         </center>
         </div>


         <div id="show_dialogm">
           <center>
             <br />
           <table style="border:none;width:100%;">
            <tr style="height:35px; width:60%;">
              <td style="text-align:center" >Moulding</td>
              <!-- <td  style="width:30%; border:none;"></td> -->
              <td style="text-align:center">Select Time</td>
            </tr>

              <tr style="height:35px; width:60%;">
              <td style="text-align:center" id="selected_moulding"></td>
              <!-- <td  style="width:30%; border:none;"></td> -->
              <td style="text-align:center">
                <select class="show-timem" name="time" required>
                  <option value="">--Select Time--</option>
                  <?php
                  for ($i=0; $i <= 48 ; $i++){
                    ?>
                    <option value="<?=($i/2); ?>"><?=($i/2) ;?></option>
                  <?php
                  }
                   ?>
                </select>
              </td>
            </tr>
           </table>
           <br />
           <input type="submit" style="width:20%;" name="submit" class="art-button-green" id="savem" value="Save">
         </center>
         </div>

</form>

<div id="auditl">

</div>

<div id="auditm">

</div>

<!-- </fieldset> -->

<!-- <div class="">
    <hr style="border:10px solid #C0C0C0; width:96vw;" />
  </div> -->

<!-- cervix  -->
<!-- <h4 style="margin:0px; padding:0px;">Cervix</h4>
<table id="table"> -->
<!--   
  <tr id="soft">
    <td  style="width:8%; font-weight:bold;" >Soft,Rigid</td>
    < ?php
    for ($i=0; $i <= 24; $i++) {
      ?>
      <td id="pro< ?=($i);?>"></td>
    < ?php }
     ?>
     <br />
  </tr>
  <tr id="thin" class="thin1">
    <td style="width:8%; font-weight:bold;">Thin,Thick,Swallen</td>
    < ?php
    for ($i=0; $i <=24; $i++) {
      ?>
      <td id="ace< ?=($i);?>"></td>
    < ?php }
     ?>
  </tr>
  <tr id="presenting_number">
    <td style="width:8%; font-weight:bold;">Presenting Number</td>
    < ?php
    for ($i=0; $i <=24; $i++) {
      ?>
      <td id="vol< ?=($i);?>"></td>
    < ?php }
     ?>
  </tr>
  </table>
  <table id="number-timet">
    <tr id="time">

      < ?php
      for ($i=0; $i <=24; $i++) {
        ?>
        <td id="< ?=($i);?>">< ?=($i);?></td>
      < ?php }
       ?>
    </tr>

  </table>
  <div class="">
    <span> -->
      <!-- <button style="width:70px; height:30px !important; color:#fff !important " class="art-button-green" id="protein"> Soft,Rigid </button></span>
      <span><button style="width:70px; height:30px !important; color:#fff !important " class="art-button-green" id="acetone"> Acetone</button> </span>
      <span> <button style="width:70px; height:30px !important; color:#fff !important " class="art-button-green" id="volume">Volume</button> </span> -->

      <!-- <button style="width:80px; height:30px !important; color:#fff !important " class="art-button-green" id="soft_open"> Soft,Rigid </button></span>
      <span><button style="width:80px; height:30px !important; color:#fff !important " class="art-button-green" id="thin_open"> Thin,Thick,Swallen</button> </span>
      <span> <button style="width:80px; height:30px !important; color:#fff !important " class="art-button-green" id="presenting_open">Presenting Number</button> </span>
    <span>
    <span> -->
      <!-- <button style="width:70px; height:30px !important; color:#fff !important " class="art-button-green" id="volume_audit">Audit -->
      <!-- </button>
     </span>
  </div> -->






<!-- <script type="text/javascript" src="jquery.min.js"></script> -->
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>
<script type="text/javascript" src="jquery.jqplot.js"></script>
<script type="text/javascript" src="plugins/jqplot.canvasTextRenderer.js"></script>
<script type="text/javascript" src="plugins/jqplot.canvasAxisLabelRenderer.js"></script>
<script type="text/javascript" src="plugins/jqplot.canvasTextRenderer.js"></script>
<script type="text/javascript" src="plugins/jqplot.canvasAxisLabelRenderer.js"></script>
<script type="text/javascript" src="../src/plugins/jqplot.pointLabels.js"></script>

<script type="text/javascript" src="../plugins/jqplot.logAxisRenderer.min.js"></script>
<script type="text/javascript" src="../plugins/jqplot.canvasAxisTickRenderer.min.js"></script>
<script type="text/javascript" src="../plugins/jqplot.dateAxisRenderer.min.js"></script>
<script type="text/javascript" src="../plugins/jqplot.categoryAxisRenderer.min.js"></script>

<script type="text/javascript" src="plugins/jqplot.highlighter.js">

</script>
<link rel="stylesheet" type="text/css" href="jquery.jqplot.css" />


<script type="text/javascript">


var cervical_pointsx = [[]];
var cervical_pointsy = [[]];


  var powPoints1= [[8,0],[8,10]];
  var powPoints2 =[[0,4],[8,4]];
  var powPoints3=[[8,4],[14,10]];
  var powPoints4 = [[12,4],[18,10]];
var points=[[]];

function plotFetalGraph(){
  // ######################GRAPH FOR PROGRESS OF LABOUR######################################################
  var plot3 = $.jqplot('chart1', [powPoints1,powPoints2,powPoints3,powPoints4,cervical_pointsx,cervical_pointsy],
    {

  axes:{
    xaxis:{
      max:24,
      min:0,
      tickInterval:1,
      label:"Duration Of Labour(Hour Time)"
    },
    yaxis:{

      max:10,
      min:0,
      tickInterval:1,
      label:" Plot X  CERVICAL DILATION ",
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
            markerOptions: { sizi:5,style:"x" }
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
            lineWidth:3,
            fill: false, fillAndStroke: true, color: '#0ff', fillColor: '#0ff',
            markerOptions: {size:7,style:"x" }
          },
          {
            // Use a thicker, 5 pixel line and 10 pixel
            // filled square markers.
            showLine:true,
            lineWidth:3,
            // fill: false, fillAndStroke: true, color: '#000', fillColor: '#000',
            markerOptions: {size:5 }
          },
          {
            // Use a thicker, 5 pixel line and 10 pixel
            // filled square markers.
            showLine:true,
            lineWidth:3,
            fill: false, fillAndStroke: true, color: '#000', fillColor: '#000',
            markerOptions: {size:10 }
          }
      ]
    }
  );

  var plot3 = $.jqplot('chart2', [points],
    {
  axes:{
    xaxis:{
      max:24,
      min:0,
      tickInterval:0.5,
      label:"Hour"
    },
    yaxis:{
      max:180,
      min:80,
      tickInterval:20,
      label:" Fetal Heart Rate ",
      labelRenderer: $.jqplot.CanvasAxisLabelRenderer,
      tickOptions: {
                      angle: 180
        }
    },
  },
  grid: {
              drawBorder: false,
              shadow: false,
              background: 'white'
          },
  highlighter: {
        show: true,
        sizeAdjust: 10.5
      },

      cursor: {
        show: false
      },


      series:[
          {

            lineWidth:3,
            fill: false, fillAndStroke: true, color: '#000', fillColor: '#ccc',
            markerOptions: { style:'circle' }
          },
          {

            showLine:true,
            markerOptions: { size: 7, style:"circle" }
          },

      ]
    }
  );

}
//end function


  //get caput

  $(document).ready(function(){
  $.ajax({
    url:"get_caput.php",
    type:"POST",
    data:{patient_id:patient_id,admission_id:admission_id},
    success:function(data){

      var jsonData = JSON.parse(data)
      // console.log(jsonData);
      for (var i = 0; i < jsonData.length; i++) {
        if (jsonData[i] !="" && jsonData[i] != "") {
            var counter = jsonData[i];
        }
          $("#caput #"+counter.caput_remark_time).html(counter.caput_remark);

      }
    }
  })
  })
  //get soft
  $(document).ready(function(){

  $.ajax({
    url:"get_soft.php",
    type:"POST",
    data:{patient_id:patient_id,admission_id:admission_id},
    success:function(data){

      var jsonData = JSON.parse(data)
      // console.log(jsonData);
      for (var i = 0; i < jsonData.length; i++) {
        if (jsonData[i] !="" && jsonData[i] != "") {
            var counter = jsonData[i];
        }
          $("#soft #"+counter.soft_remark_time).html(counter.soft_remark);

      }
    }
  })
  })
  
  //get thin

    //get caput

    $(document).ready(function(){
  $.ajax({
    url:"get_thin.php",
    type:"POST",
    data:{patient_id:patient_id,admission_id:admission_id},
    success:function(data){

      var jsonData = JSON.parse(data)
      // console.log(jsonData);
      for (var i = 0; i < jsonData.length; i++) {
        if (jsonData[i] !="" && jsonData[i] != "") {
            var counter = jsonData[i];
        }
          $("#thin #"+counter.thin_remark_time).html(counter.thin_remark);

      }
    }
  })
  })
      //get presenting number

      $(document).ready(function(){
  $.ajax({
    url:"get_presenting.php",
    type:"POST",
    data:{patient_id:patient_id,admission_id:admission_id},
    success:function(data){

      var jsonData = JSON.parse(data)
      // console.log(jsonData);
      for (var i = 0; i < jsonData.length; i++) {
        if (jsonData[i] !="" && jsonData[i] != "") {
            var counter = jsonData[i];
        }
          $("#caput #"+counter.presenting_remark_time).html(counter.presenting_remark);

      }
    }
  })
  })
    //get position

    $(document).ready(function(){
  $.ajax({
    url:"get_labour_position.php",
    type:"POST",
    data:{patient_id:patient_id,admission_id:admission_id},
    success:function(data){

      var jsonData = JSON.parse(data)
      // console.log(jsonData);
      for (var i = 0; i < jsonData.length; i++) {
        if (jsonData[i] !="" && jsonData[i] != "") {
            var counter = jsonData[i];
        }


          // $("#temp_fill #"+counter.tr_time).html(counter.temp+"c");
          $("#position #"+counter.labour_position_remark_time).html(counter.labour_position_remark);
          // $("#res_fill #"+counter.tr_time).html(counter.resp);

      }
    }
  })
  })

//end position
$(document).ready(function(){

  // select all points in table:
  var patient_id = $("#patient_id").val();
  var  admission_id = $("#admission_id").val();



  $.ajax({
    url:"get_moulding.php",
    type:"POST",
    data:{patient_id:patient_id,admission_id:admission_id},
    success:function(data)
    {

      var json_data = JSON.parse(data)
      var time = json_data[0].moulding_time;
      // console.log(time)
      for (var i = 0; i < json_data.length;i++) {
        var current = json_data[i];

        // console.log("test")
      $("#moulding #"+(current.moulding_time).replace(/(:|\.|\[|\]|,|=)/g, "\\$1" )).html(current.moulding);
        // console.log(current.moulding)
      }
      }

  })


// display liqour remark
$.ajax({
    url:"get_liqour_remark.php",
    type:"POST",
    data:{patient_id:patient_id,admission_id:admission_id},
    success:function(data)
    {

      var json_data = JSON.parse(data)
      var time = json_data[0].moulding_time;
      // console.log(time)
      for (var i = 0; i < json_data.length;i++) {
        var current = json_data[i];

        console.log((current.liqour_remark_time).replace(/(:|\.|\[|\]|,|=)/g, "\\$1" ) );
      $("#liqour_remark #"+(current.liqour_remark_time).replace(/(:|\.|\[|\]|,|=)/g, "\\$1" )).html(current.liqour_remark);
        // console.log(current.moulding)
      }
      }

  })


  var d=[]
  $.ajax({
    type:"POST",
    url:"select_fetal_heart_rate_points.php",
    data:{patient_id:patient_id,admission_id:admission_id},
    success:function(data){
      if (data != "") {

        var jsonData = JSON.parse(data);
        for (var i = 0; i < jsonData.length; i++) {
      var counter = jsonData[i];
      d=[parseFloat(counter.y) , counter.x]
      points.push(d);

      }

}
    // console.log(points);  // console.log();
    plotFetalGraph();
    }
  });
  // end points selection




$("#addy").click(function(e){
  e.preventDefault()
  var x = $("#pointx").val();
  var y = $("#pointy").val();
  // console.log();
  // console.log((points[points.length - 2])[0]);

// var total_points = (points[points.length])[0];
// console.log(total_points);

  var patient_id = $("#patient_id").val();
  var admission_id = $("#admission_id").val();

if (x!="" && y != ""){
  if (y>=0 && y <=24 ){
      // if ( y > total_points)  {
    if(x>=80 && x<=180){
      $.ajax({
        type:"GET",
        url:"add_fetal_heart_rate_toc_cache.php",
        data:{x:x,y:y,patient_id:patient_id,admission_id:admission_id},
        success:function(data){
          if (data != "") {
          points.push(JSON.parse(data));
          $("#pointx").css("border","1px  black");
          $("#pointy").css("border","1px  black");
          $("#pointx").val("");
          $("#pointy").val("");
          }

          // console.log(points)

          plotFetalGraph()
          // end drawing

        }
      })
    }else {
      alert("You exceed fetal heart rate")
      $("#pointx").css("border","2px solid red");
     return false;
    }

  // }else {
  //   alert("The entered time is behind the previos maximum time of the chart")
  // }
  }else {
    alert("your exceed normal hours")
    $("#pointy").css("border","2px solid red");
     return false;
  }

}else {
  alert("Please Fill in both Fetal Heart Rate and Time value");
  $("#pointy").css("border","2px solid red");
  $("#pointx").css("border","2px solid red");
}
})



// start moulding graph
// plotMouldingGraph()
  // end Moilding grp

});
// validate time

// start moulding graph
  // end Moilding grp

$(document).ready(function(e){

  $('#show_dialogl').dialog({
               autoOpen: false,
               modal: true,
               width: 550,
               height:300,
               title: 'State Of Liqour'
           });

// Moolding dialog
           $('#show_dialogm').dialog({
                        autoOpen: false,
                        modal: true,
                        width: 550,
                        height:300,
                        title: 'Moulding'
                    });



                    $('#auditl').dialog({
                                 autoOpen: false,
                                 modal: true,
                                 width: 800,
                                 height:400,
                                 title: 'Moulding'
                             });

                    $('#show_dialogm').dialog({
                            autoOpen: false,
                            modal: true,
                            width: 550,
                            height:300,
                            title: 'Moulding'
                            });

                            $('#show_dialog_caput').dialog({
                                        autoOpen: false,
                                        modal: true,
                                        width: 550,
                                        height:300,
                                        title: 'Caput'
                                    });
                                    $('#show_dialog_soft').dialog({
                                        autoOpen: false,
                                        modal: true,
                                        width: 550,
                                        height:300,
                                        title: 'Soft,Rigid'
                                    });
                                    $('#show_dialog_thin').dialog({
                                        autoOpen: false,
                                        modal: true,
                                        width: 550,
                                        height:300,
                                        title: 'Thin,Thick,Swallow'
                                    });
                                    $('#show_dialog_presenting').dialog({
                                        autoOpen: false,
                                        modal: true,
                                        width: 550,
                                        height:300,
                                        title: 'Presenting Number'
                                    });

                                    $('#show_dialog_position').dialog({
                                        autoOpen: false,
                                        modal: true,
                                        width: 550,
                                        height:300,
                                        title: 'Position'
                                    });




           var liqour_remark;

           // process liqour remark
             $(".btn-inputl").click(function(e){
               e.preventDefault();

           var val = $(this).text();

           liqour_remark = val;
           // console.log(liqour_remark);
              $("#selected_liqour").html(val)
               $("#show_dialogl").dialog("open");

             })

// save liqour remark
             $("#savel").click(function(e){

               var patient_id = $("#patient_id").val();
               var admission_id = $("#admission_id").val();

               var time = $(".show-time").val();
               var liquor = $("#selected_liqour").val();
               // console.log(liqour_remark)
               // console.log(time.replace( /(:|\.|\[|\]|,|=)/g, "\\$1" ) );
               $("#liqour_remark #"+time.replace( /(:|\.|\[|\]|,|=)/g, "\\$1" )).html(liqour_remark);
               $("#show_dialogl"
             ).dialog("close");
             // alert(time)
             $.ajax({
               type:"post",
               url:"save_moulding_liqour.php",
               data:{patient_id:patient_id,admission_id:admission_id,
                 liqour_remark:liqour_remark,liqour_remark_time:time},
               success:function(data){
                 console.log(data);
               }
             })

             })
// end liqour remark


//start of saving caput

$("#save_caput").click(function(e){
  e.preventDefault();

  var patient_id = $("#patient_id").val();
  var admission_id = $("#admission_id").val();

  var time = $("#time_caput").val();
  var caput_remark = $("#caput_remark").val();
  $.ajax({
    url:"add_caput.php",
    type:"POST",
    data:{patient_id:patient_id,admission_id:admission_id,
      caput_remark:caput_remark,time:time},
    success:function(data){
      // var jsonData = JSON.parse(data)
      // console.log(data);
      $("#caput #"+time).html(caput_remark);
      $("#show_dialog_caput").dialog("close");
    }
})


  $("#show_dialog_caput").dialog("close");
})

//end of saving caput

//save soft
$("#save_soft").click(function(e){
  e.preventDefault();

  var patient_id = $("#patient_id").val();
  var admission_id = $("#admission_id").val();

  var time = $("#time_soft").val();
  var soft_remark = $("#soft_remark").val();
  // alert(soft_remark)
  $.ajax({
    url:"add_soft.php",
    type:"POST",
    data:{patient_id:patient_id,admission_id:admission_id,
      soft_remark:soft_remark,time:time},
    success:function(data){
      // var jsonData = JSON.parse(data)
      // console.log(data);
      $("#soft #"+time).html(soft_remark);
      $("#show_dialog_soft").dialog("close");
    }
})
  $("#show_dialog_soft").dialog("close");
})

//save thin
$("#save_thin").click(function(e){
  e.preventDefault();

  var patient_id = $("#patient_id").val();
  var admission_id = $("#admission_id").val();
  var time = $("#time_thin").val();
  var thin_remark = $("#thin_remark").val();
  $.ajax({
    url:"add_thin.php",
    type:"POST",
    data:{patient_id:patient_id,admission_id:admission_id,
      thin_remark:thin_remark,time:time},
    success:function(data){
      // var jsonData = JSON.parse(data)
      // console.log(data);
      $("#thin #"+time).html(thin_remark);
      $("#show_dialog_thin").dialog("close");
    }
})
  $("#show_dialog_thin").dialog("close");
})

$("#save_presenting").click(function(e){
  e.preventDefault();

  var patient_id = $("#patient_id").val();
  var admission_id = $("#admission_id").val();
  var time = $("#time_presenting").val();
  var presenting_remark = $("#presenting_remark").val();
  $.ajax({
    url:"add_presenting.php",
    type:"POST",
    data:{patient_id:patient_id,admission_id:admission_id,
      presenting_remark:presenting_remark,time:time},
    success:function(data){
      // var jsonData = JSON.parse(data)
      // console.log(data);
      $("#presenting #"+time).html(presenting_remark);
      $("#show_dialog_presenting").dialog("close");
    }
})
  $("#show_dialog_presenting").dialog("close");
})


  //start of saving position

$("#save_position").click(function(e){
  e.preventDefault();

  var patient_id = $("#patient_id").val();
  var admission_id = $("#admission_id").val();
  var time = $("#time_position").val();
  var position_remark = $("#position_remark").val();
  // alert(position_remark)
  // alert(time)
  $.ajax({
    url:"add_labour_position.php",
    type:"POST",
    data:{patient_id:patient_id,admission_id:admission_id,
      position_remark:position_remark,time:time},
    success:function(data){
      // var jsonData = JSON.parse(data)
      // console.log(data);
      $("#position #"+time).html(position_remark);
      $("#show_dialog_position").dialog("close");
    }
})
  $("#show_dialog_position").dialog("close");
})

//end of saving position

// start processing moulding
              var moulding;
             $(".btn-inputm").click(function(e){
               e.preventDefault();

           var val = $(this).text();

           moulding = val;
           // console.log(val);
              $("#selected_moulding").html(val)
               $("#show_dialogm").dialog("open");

             })

             $("#savem").click(function(e){
               e.preventDefault();

               var time = $(".show-timem").val();
               var patient_id = $("#patient_id").val();
               var admission_id = $("#admission_id").val();


               $("#moulding #"+time.replace( /(:|\.|\[|\]|,|=)/g, "\\$1" )).html(moulding);
               $("#show_dialogm").dialog("close");

               $.ajax({
                 type:"post",
                 url:"save_moulding.php",
                 data:{patient_id:patient_id,admission_id:admission_id,
                   moulding:moulding,moulding_time:time},
                 success:function(data){
                   console.log(data);
                 }
               })
             })
             

             $("#audit").click(function(e){
               e.preventDefault();
               var patient_id = $("#patient_id").val();
               var admission_id = $('#admission_id').val();
               // alert(patient_id + " " +admission_id)
               $.ajax({
                 url:"liqour_molding_audit.php",
                 type:"POST",
                 data:{patient_id:patient_id,admission_id:admission_id},
                 success:function(data){
                   console.log(data)
                  $("#auditl").dialog("open");
                  $("#auditl").html(data);
                 }
               })


             })
           })
           $(document).ready(function(){


var patient_id = $("#patient_id").val();
var admission_id = $("#admission_id").val();

// fetch cervical points if exist
$.ajax({
  url:"fetch_progress_labour_cervcal_points.php",
  type:"POST",
  data:{patient_id:patient_id,admission_id:admission_id},
  success:function(data){
  
    var cervical_data = JSON.parse(data);
    var point=[];

  for (var i = 0; i <cervical_data.length; i++) {
    var counter = cervical_data[i];

    point = [parseInt(counter.fx),parseFloat(counter.fy)];

    cervical_pointsx.push(point)
  
  plotFetalGraph()
  }
  },
  error: function( data, status, error ) { 
              console.log(data);
              console.log(status);
              console.log(error);
          }
})

// fetch head points id exist

$.ajax({
  url:"fetch_progress_labour_head_points.php",
  type:"POST",
  data:{patient_id:patient_id,admission_id:admission_id},
  success:function(data){
  
    var cervical_data = JSON.parse(data);
    var point=[];

  for (var i = 0; i <cervical_data.length; i++) {
    var counter = cervical_data[i];

    point = [parseInt(counter.sx),parseFloat(counter.sy)];

    cervical_pointsy.push(point)
  
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
plotFetalGraph()
});


$("#add-first").click(function(e){
  e.preventDefault();

  var patient_id = $("#patient_id").val();
  var admission_id = $("#admission_id").val();
  var fx = $(".input-fx").val();
   var fy = $(".input-fy").val();
   if (fx!="" && fy != ""){
   if(fx > 24){
     alert("You Can't Enter maximum Of 24Hours");
    // alert("You Can't Enter Maximum Of 10 Length Of Cervix");

     $(".input-fx").css("border","2px solid red");
     return false;
   }
   if(fy > 10){
    alert("You Can't Enter Maximum Of 10 Length Of Cervix");
    // alert("You Can't Enter maximum Of 24Hours");

    $(".input-fy").css("border","2px solid red");
     return false;
   }
    $.ajax({
      url:"save_progress_of_labour_points.php",
      type:"POST",
      data:{patient_id:patient_id,admission_id:admission_id,fx:fx,fy:fy},
      success:function(data){
        console.log(JSON.parse(data))
        // var cervical_data = JSON.parse(data);
        var cervical_data = JSON.parse(data.trim());
       cervical_pointsx.push(cervical_data);
       plotFetalGraph();
        // location.reload(true);
        $(".input-fx").css("border","1px black");
        $(".input-fy").css("border","1px black");
        $(".input-fy").val("");
        $(".input-fx").val("");
        // location.reload(true);

      }
    })
  }else{
    alert("Please Fill in both value Of Cervical Dilation");
  $(".input-fx").css("border","2px solid red");
  $(".input-fy").css("border","2px solid red");
  }
})


$("#add-second").click(function(e){
  e.preventDefault();

  var patient_id = $("#patient_id").val();
  var admission_id = $("#admission_id").val();
  var sx = $(".input-sx").val();
   var sy = $(".input-sy").val();
   if (sx!="" && sy != ""){
   if(sx > 24){
     alert("You Can't Enter Maximum Of 24 Hours");
     $(".input-sx").css("border","2px solid red");
     return false;
   }
   if(sy > 10){
    alert("You Can't Enter Maximum Of 10 Length Of Cervix");
     $(".input-sy").css("border","2px solid red");
     return false;
   }

   $.ajax({
     url:"save_progress_of_labour_head_points.php",
     type:"POST",
     data:{patient_id:patient_id,admission_id:admission_id,sx:sx,sy:sy},
     success:function(data){
       console.log(data)
       var cervical_data = JSON.parse(data.trim());
       cervical_pointsy.push(cervical_data);
       plotFetalGraph();
       $(".input-sy").css("border","1px black");
       $(".input-sx").css("border","1px black");
       $(".input-sx").val("");
       $(".input-sy").val("");

     }
   })
  }else{
    alert("Please Fill in both value Of Head Circumference");
  $(".input-sy").css("border","2px solid red");
  $(".input-sx").css("border","2px solid red");
  }

})


</script>
<script>
  
</script>
