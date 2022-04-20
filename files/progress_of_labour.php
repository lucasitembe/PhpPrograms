<?php
include("./includes/header.php");
include("./includes/connection.php");
if (isset($_GET['consultation_id'])) {
  $consultation_id = $_GET['consultation_id'];
}
if (isset($_GET['admission_id'])) {
   $admision_id = $_GET['admission_id'];
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
 <input type="button" value=" PREVIOUS DATA" class="art-button-green" onclick="open_previous_labour_data()">
 <a href="patograph_record.php?consultation_id=<?= $consultation_id;?>&patient_id=<?=$patient_id;?>&admission_id=<?=$admision_id?>" class="art-button-green">BACK</a>


 <center>
   <fieldset>
     <legend style="font-weight:bold"align=center>
       <div style="height:34px;margin:0px;padding:0px;font-weight:bold">
         <p style="margin:0px;padding:0px;">progress Of Labour</p>
         <p style="color:yellow;margin:0px;padding:0px; "><span style="margin-right:3px;"><?=$Patient_Name?> |</span><span style="margin-right:3px;"><?=$Gender?> |</span> <span style="margin-right:3px;"><?=$age?> | </span> <span style="margin-right:3px;"><?=$Guarantor_Name?></span> </p>
       </div>
   </legend>
     <form class="" action="" method="post" id="labour_form">

       <input type="hidden" name="patient_id" id="patient_id" value="<?=$patient_id; ?>">
       <input type="hidden" name="admission_id" id="admission_id" value="<?=$admision_id; ?>">

       <div id="chart1">

       </div>

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
         <button type="button" style="width:40px; height:30px !important; color:#fff !important;" class="art-button-green" name="button">Audit</button>
       </div>
</form>
</fieldset>


<style media="screen">
  #chart1{
    height: 480px;
    margin:15px auto;
    width: 85vw;
  }

  .input-to-graph{
    display: inline-block;
  }
</style>



<script type="text/javascript" src="jquery.min.js"></script>
<script type="text/javascript" src="jquery.jqplot.js"></script>
<script type="text/javascript" src="plugins/jqplot.canvasTextRenderer.js"></script>
<script type="text/javascript" src="plugins/jqplot.canvasAxisLabelRenderer.js"></script>
<script type="text/javascript" src="plugins/jqplot.canvasTextRenderer.js"></script>
<script type="text/javascript" src="plugins/jqplot.canvasAxisLabelRenderer.js"></script>
<script type="text/javascript" src="plugins/jqplot.highlighter.js"></script>
<link rel="stylesheet" type="text/css" href="jquery.jqplot.css" />
<!-- <script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script> -->

<script type="text/javascript">


var cervical_pointsx = [[]];
var cervical_pointsy = [[]];
function plotFetalGraph(){


  var powPoints1= [[8,0],[8,10]];
  var powPoints2 =[[0,4],[8,4]];
  var powPoints3=[[8,4],[14,10]];
  var powPoints4 = [[12,4],[18,10]];

  // var powPoints1= [[0,0],[0,0]];
  // var powPoints2 =[[0,0],[0,0]];
  // var powPoints3=[[0,0],[0,0]];
  // var powPoints4 = [[0,0],[0,0]];




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

}

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
   if(fx > 10){
     alert("You Can't Enter maximum Of 24Hours");
     $(".input-fx").css("border","2px solid red");
     return false;
   }
   if(fy > 24){
    alert("You Can't Enter Maximum Of 10 Length Of Cervix");
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
        // plotFetalGraph();
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
   if(sx > 10){
     alert("You Can't Enter Maximum Of 24 Hours");
     $(".input-sx").css("border","2px solid red");
     return false;
   }
   if(sy > 24){
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


function open_previous_labour_data(){
      var patient_id = $("#patient_id").val();
      var admision_id=$("#admision_id").val();
      $.ajax({
            type:'GET',
            url:'pediatric_previous_data.php',
            data : {
              patient_id:patient_id,
                admision_id:admision_id
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
