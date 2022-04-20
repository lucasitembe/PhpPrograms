<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    if (!isset($_SESSION['userinfo'])) {
        @session_destroy();
        header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if (isset($_GET['Registration_ID'])) {
        $Registration_ID = $_GET['Registration_ID'];
    } else {
        $Registration_ID = 0; 
    } 
    if(isset($_GET['anasthesia_record_id'])){
       $anasthesia_record_id= $_GET['anasthesia_record_id'];
    }else{
        $anasthesia_record_id='';
    }

    if (isset($_GET['Registration_ID']) && $_GET['Registration_ID'] != 0) {
        $select_patien_details = mysqli_query($conn,"SELECT pr.Sponsor_ID,Member_Number,Patient_Name, Registration_ID,Gender,Guarantor_Name,Date_Of_Birth  FROM tbl_patient_registration pr,   tbl_sponsor sp  WHERE  pr.Registration_ID = '" . $Registration_ID . "' AND  sp.Sponsor_ID = pr.Sponsor_ID  ") or die(mysqli_error($conn));
        
        if (mysqli_num_rows($select_patien_details) > 0) {
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
    } 
    
    $age = date_diff(date_create($DOB), date_create('today'))->y;
    
     ?>
     <a href="anesthesia_record_chart.php?Registration_ID=<?= $Registration_ID;?>&anasthesia_record_id=<?=$anasthesia_record_id;?>" class="art-button-green">BACK</a>
     <fieldset style='overflow-y: scroll; height: 30vh; background-color: white;'>
          <legend style="font-weight:bold"align=center>
            <div style="height:34px;margin:0px;padding:0px;font-weight:bold">
              <p style="margin:0px;padding:0px;">ANAESTHESIA VITALS GRAPHS</p>
              <p style="color:yellow;margin:0px;padding:0px; "><span style="margin-right:3px;"><?=$Patient_Name?> |</span><span style="margin-right:3px;"><?=$Gender?> |</span> <span style="margin-right:3px;"><?=$age?> | </span> <span style="margin-right:3px;"><?=$Guarantor_Name?></span> </p>
            </div>
        </legend>
            
              <input type="text" hidden  name="Registration_ID" value="<?php echo $Registration_ID?>" >
              <table width="100%" >
                  <thead>
                    <tr>
                        <th>SPO₂</th>
                        <th>ETCO₂</th>
                        <th>ECG</th>
                        <th>Temp</th>
                        <th>Fluids/BT</th>
                        <th>MAC</th>                
                        <th>Action</th>
                    </tr>
                  </thead>
                  <tbody id='table_vital_maintanance_body'>
                  </bdody>
              </table>
      </fieldset>  
       <fieldset style='overflow-y: scroll; height: 50vh; background-color: white;'>
         
         <form class="" action="" method="post" id="labour_form">
    
           <input type="hidden" name="Registration_ID" id="Registration_ID" value="<?=$Registration_ID; ?>">
          
    
           <div id="chart1">
    
           </div>
    
           <div class="input_to_graph">
             <span style="font-weight:bold;"> BP</span>
             <span>O:<input style="text-align:center; width:15%;display:inline;" type="text" placeholder="time" name="o" class="input-fx" value=""> </span>
             <span>X:<input style="text-align:center; width:15%;display:inline;" type="text" placeholder="SBP" name="x"  class="input-fy" value=""> </span>
             <span>S:<input style="text-align:center; width:15%;display:inline;" type="text" placeholder="DBP" name="z"  class="input-fz" value=""> </span>

             <span><button type="button" id="add-first" class="btn btn-info" name="button" style="width:40px; height:30px !important;">Add</button> </span>
           </div>
    
           <div class="input_to_graph">
             <span style="font-weight:bold;">HR</span>
             <span>O:<input style="text-align:center; width:20%;display:inline;" type="text" placeholder="time" name="o" class="input-sx" value=""> </span>
             <span>X:<input style="text-align:center; width:20%;display:inline;" type="text" placeholder="vitals" name="x"  class="input-sy" value=""> </span>
             <span><button type="button" id="add-second" class="btn btn-info" name="button" style="width:40px; height:30px !important;">Add</button> </span>
           </div>

           <div class="input_to_graph">
             <span style="font-weight:bold;">MAP</span>
             <span>O:<input style="text-align:center; width:20%;display:inline;" type="text" name="o" placeholder="time" class="input-zx" value=""> </span>
             <span>X:<input style="text-align:center; width:20%;display:inline;" type="text" name="x" placeholder="vitals" class="input-zy" value=""> </span>
             <span><button type="button" id="add-third" class="btn btn-info" name="button" style="width:40px; height:30px !important;">Add</button> </span>
           </div>
    
          
    </form>
    </fieldset>
    <?php
      include("./includes/footer.php");
    ?>
    <style media="screen">
      #chart1{
        height: 500px;
        margin:15px auto;
        width: 80vw;
      }
    
      .input_to_graph{
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
    <script type="text/javascript">
    
    
    var cervical_pointsx = [[]];
    var cervical_pointsy = [[]];
    var cervical_pointzx = [[]];
    var cervical_pointzy = [[]];
    var cervical_pointfx = [[]];
    var cervical_pointfy = [[]];
    var cervical_pointfz = [[]];
    function plotFetalGraph(){
    
    
    //   var powPoints1= [[8,0],[8,10]];
    //   var powPoints2 =[[0,3],[8,3]];
    //   var powPoints3=[[8,3],[15,10]];
    //   var powPoints4 = [[12,3],[19,10]];
            var powPoints1= [[0,0],[0,0]];
            var powPoints2 =[[0,0],[0,0]];
            var powPoints3=[[0,0],[0,0]];
            var powPoints4 = [[0,0],[0,0]];
    
    
    
    
      var plot3 = $.jqplot('chart1', [cervical_pointsx,cervical_pointsy,cervical_pointzx,cervical_pointzy,cervical_pointfx,cervical_pointfy, cervical_pointfz],
        {
    
      axes:{
        xaxis:{
          max:300,
          min:0,
          tickInterval:5,
          label:"Time"
        },
        yaxis:{    
          max:200,
          min:0,
          tickInterval:20,
          label:" VITALS ",
          labelRenderer: $.jqplot.CanvasAxisLabelRenderer,
          tickOptions:{
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
              // {
              //   // Change our line width and use a diamond shaped marker.
              //   fill: false, fillAndStroke: true, color: '#000', fillColor: '#000',
              //   markerOptions: { style:'x' }
              // },
              {
                // Don't show a line, just show markers.
                // Make the markers 7 pixels with an 'x' style
                fill: false, fillAndStroke: true, color: '#000', fillColor: '#000',
                
                showLine:true,
                markerOptions: { size: 5, style:"x" }
              },
              {
                // Use (open) circlular markers.
                showLine:true,
                lineWidth:3,
                fill: false, fillAndStroke: true, color: '#', fillColor: '#000',
                lineColor:"#000",
                markerOptions: { sizi:5,style:"x" }
              },
              {
                // Use a thicker, 5 pixel line and 10 pixel
                // filled square markers.
    
                showLine:false,
                // lineWidth:3,
                 fill: false, fillAndStroke: true, color: '#3231ce', fillColor: '#3231ce',
                markerOptions: {style:"circle"}
              },
              {
                // Use a thicker, 5 pixel line and 10 pixel
                // filled square markers.
                showLine:false,
                // lineWidth:3,
                fill: false, fillAndStroke: true, color: '#0ff', fillColor: '#0ff',
                markerOptions: {size:7,style:"square" }
              }
              // {
              //   // Use a thicker, 5 pixel line and 10 pixel
              //   // filled square markers.
              //   showLine:true,
              //   lineWidth:3,
              //   fill: false, fillAndStroke: true, color: '#0ff', fillColor: '#0ff',
              //   markerOptions: {size:7,style:"square" }
              // },
             // {
                // Use a thicker, 5 pixel line and 10 pixel
                // filled square markers.
                // showLine:true,
                // lineWidth:3,
                // fill: false, fillAndStroke: true, color: '#000', fillColor: '#000',
              //   markerOptions: {size:5 }
              // },
              //{
                // Use a thicker, 5 pixel line and 10 pixel
                // filled square markers.
              //   showLine:true,
              //   lineWidth:3,
              //   fill: false, fillAndStroke: true, color: '#000', fillColor: '#000',
              //   markerOptions: {size:10 }
              // }
          ]
        }
      );
     
    }
    
    $(document).ready(function(){
    
        
      var Registration_ID = $("#Registration_ID").val();
      var anasthesia_record_id = '<?= $anasthesia_record_id; ?>'
    
    // fetch cervical points if exist
      $.ajax({
        url:"add_anaesthetic_item.php",
        type:"POST",
        data:{Registration_ID:Registration_ID,anasthesia_record_id:anasthesia_record_id, HRreadings:''},
        success:function(data){
            console.log(data);
        
          var cervical_data = JSON.parse(data);
         
          
          var point=[];
    
        for (var i = 0; i <cervical_data.length; i++) {
          var counter = cervical_data[i];
    
          point = [parseFloat(counter.sx),parseInt(counter.sy)];
    
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
    
      $.ajax({
        url:"add_anaesthetic_item.php",
        type:"POST",
        data:{Registration_ID:Registration_ID,anasthesia_record_id:anasthesia_record_id, MAPReading:''},
        success:function(data){
            console.log(data);
          var cervical_data1 = JSON.parse(data);
          var point=[];
    
          for (var i = 0; i <cervical_data1.length; i++) {
            var counter = cervical_data1[i];
            point = [parseFloat(counter.zx),parseInt(counter.zy)];
            cervical_pointzx.push(point)
              plotFetalGraph()
          
          }
        },
        error: function( data, status, error ) { 
                    console.log(data);
                    console.log(status);
                    console.log(error);
                }
      })
    
      $.ajax({
        url:"add_anaesthetic_item.php",
        type:"POST",
        data:{Registration_ID:Registration_ID,anasthesia_record_id:anasthesia_record_id, BPreadings:''},
        success:function(data){ 
          console.log(data);       
          var cervical_data = JSON.parse(data);
          var point=[];
        for (var i = 0; i <cervical_data.length; i++) {
          var counter = cervical_data[i];
          point = [parseFloat(counter.fx), parseInt(counter.fy)];
          points = [parseFloat(counter.fx), parseInt(counter.fz)];

          cervical_pointfy.push(point)
          cervical_pointfz.push(points)
          
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
    
      var Registration_ID = $("#Registration_ID").val();
      var anasthesia_record_id = '<?= $anasthesia_record_id; ?>'
    
        var fx = $(".input-fx").val();
        var fy = $(".input-fy").val();
        var fz = $(".input-fz").val();

        if(fx==""){
          $(".input-fx").css("border", "1px solid red");
        }else if(fy==""){
          $(".input-fy").css("border", "1px solid red");  
        }else if(fz==""){
          $(".input-fz").css("border", "1px solid red");  
        }else{
          $(".input-fx").css("border", "");
          $(".input-fy").css("border", ""); 
          $(".input-fz").css("border", ""); 

        $.ajax({
          url:"add_anaesthetic_item.php",
          type:"POST",
          data:{Registration_ID:Registration_ID,anasthesia_record_id:anasthesia_record_id,fx:fx,fy:fy,fz:fz, BP:''},
          success:function(data){
            $(".input-fx").val('')
            $(".input-fy").val('')
            $(".input-fz").val('')
            if(data=='ok'){
              location.reload();
            }
                        
            console.log(JSON.parse(data))
            
            var cervical_data = JSON.parse(data);
            cervical_pointfx.push(cervical_data);
            plotFetalGraph();
          }        
        })
      }
    })
    
    
    $("#add-second").click(function(e){
      e.preventDefault();
    
       var Registration_ID = $("#Registration_ID").val();
       var anasthesia_record_id = '<?= $anasthesia_record_id; ?>'
       var sx = $(".input-sx").val();
       var sy = $(".input-sy").val();
       if(sx==""){
          $(".input-sx").css("border", "1px solid red");
        }else if(sy==""){
          $(".input-sy").css("border", "1px solid red");  
        }else{
          $(".input-sx").css("border", "");
          $(".input-sy").css("border", ""); 
       $.ajax({
         url:"add_anaesthetic_item.php",
         type:"POST",
         data:{Registration_ID:Registration_ID,anasthesia_record_id:anasthesia_record_id,sx:sx,sy:sy,HR:''},
         success:function(data){
          $(".input-sy").val('');
          $(".input-sx").val('');
          
           console.log(data)
           if(data=='ok'){
              location.reload();
            }
           var cervical_data = JSON.parse(data.trim());
           cervical_pointsy.push(cervical_data);
           plotFetalGraph();
            
         }
       })
      }
    })

    $("#add-third").click(function(e){
      e.preventDefault();
    
        var Registration_ID = $("#Registration_ID").val();
        var anasthesia_record_id = '<?= $anasthesia_record_id; ?>'
        var zx = $(".input-zx").val();
        var zy = $(".input-zy").val();       
        if(zx==""){
            $(".input-zx").css("border", "1px solid red");
          }else if(zy==""){
            $(".input-zy").css("border", "1px solid red");  
          }else{
            $(".input-zx").css("border", "");
            $(".input-zy").css("border", "");
       $.ajax({
         url:"add_anaesthetic_item.php",
         type:"POST",
         data:{Registration_ID:Registration_ID,anasthesia_record_id:anasthesia_record_id,zx:zx,zy:zy,MAP_insert:''},
         success:function(data){
          $(".input-zx").val()
          $(".input-zy").val()
           console.log(data)
           if(data=='ok'){
              location.reload();
            }
           var cervical_data = JSON.parse(data.trim());
           cervical_pointsy.push(cervical_data);
    
           plotFetalGraph();
    
         }
       })
      }
    })
    
 

</script>
  
<script>
          $(document).ready(function(){
            Select_mntainance_vitals();
          })
          function add_Vitals_meaintanance(){
            var SPO2e = $("#SPO2").val();
            var ETCO2e = $("#ETCO2").val();
            var ECGe = $("#ECG").val();
            var Temp = $("#Temp").val();
            var Fluids_bt = $("#Fluids_bt").val();
            var MACe = $("#MAC").val();
            var Registration_ID = '<?= $Registration_ID?>';
            var anasthesia_record_id = <?= $anasthesia_record_id; ?>;
            $.ajax({
              type:'POST',
              url:'add_anaesthetic_item.php', 
              data:{SPO2:SPO2e, ETCO2:ETCO2e,ECG:ECGe,anasthesia_record_id:anasthesia_record_id, Registration_ID:Registration_ID, Temp:Temp,Fluids_bt:Fluids_bt, MAC:MACe, Vitals_meaintanance_add:''},
              success:function(responce){ 
                Select_mntainance_vitals();
              }
            })
          }
          function Select_mntainance_vitals(){
             
            var Registration_ID = $("#Registration_ID").val();
            var anasthesia_record_id = <?= $anasthesia_record_id; ?>;
    
            $.ajax({
              type:'POST',
              url:'add_anaesthetic_item.php', 
              data:{Registration_ID:Registration_ID,anasthesia_record_id:anasthesia_record_id, view_mntainance_vitals:''},
              success:function(responce){
                $("#table_vital_maintanance_body").html(responce);
              }
            })
          }
          
  </script>
  
<!--<script src="js/jquery-1.8.0.min.js"></script>-->
<script type="text/javascript" src="plugins/jqplot/jquery.jqplot.js"></script>
<script type="text/javascript" src="plugins/jqplot.dateAxisRenderer.js"></script>
<script type="text/javascript" src="plugins/jqplot.canvasTextRenderer.js"></script>
<script type="text/javascript" src="plugins/jqplot.canvasAxisLabelRenderer.js"></script>
<script type="text/javascript" src="plugins/jqplot.canvasTextRenderer.js"></script>
<script type="text/javascript" src="plugins/jqplot.canvasAxisTickRenderer.js"></script>
<script type="text/javascript" src="plugins/jqplot.canvasAxisLabelRenderer.js"></script>
<script type="text/javascript" src="plugins/jqplot.logAxisRenderer.js"></script>
<script type="text/javascript" src="plugins/jqplot.highlighter.js"></script>
<link rel="stylesheet" type="text/css" href="plugins/jqplot/jquery.jqplot.css" />


<script>
    //A jqplot plugin to render image as a data point.
(function($) {
	$.jqplot.ImageMarkerRenderer = function() {
		$.jqplot.MarkerRenderer.call(this);
		//image element which should have src attribute populated with the image source path
		this.imageElement = null;
		//the offset to be added to the x position of the point to align the image correctly in the center of the point.
		this.xOffset = null;
		//the offset to be added to the y position of the point to align the image correctly in the center of the point.
		this.yOffset = null;
	};
	$.jqplot.ImageMarkerRenderer.prototype = new $.jqplot.MarkerRenderer();
	$.jqplot.ImageMarkerRenderer.constructor = $.jqplot.ImageMarkerRenderer;

	$.jqplot.ImageMarkerRenderer.prototype.init = function(options) {
		options = options || {};
		this.imageElement = options.imageElement;
		this.xOffset = options.xOffset || 0;
		this.yOffset = options.yOffset || 0;
		$.jqplot.MarkerRenderer.prototype.init.call(this, options);
	};

	$.jqplot.ImageMarkerRenderer.prototype.draw = function(x, y, ctx, options) {
		//draw the image onto the canvas
		ctx.drawImage(this.imageElement, x + this.xOffset, y + this.yOffset);
		ctx.restore();
		return;
	};
})(jQuery);
</script>
  