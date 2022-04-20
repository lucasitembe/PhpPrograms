<?php 
    session_start();
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
   

    if (isset($_GET['consultation_ID'])) {
        $consultation_ID = $_GET['consultation_ID']; 
    } else {
        $consultation_ID = 0;
    }
    if (isset($_GET['anasthesia_record_id'])) {
        $anasthesia_record_id = $_GET['anasthesia_record_id']; 
    } else {
        $anasthesia_record_id = 0;
    }
    include('patient_demographic.php');
    ?>
   <input type="text" id="anasthesia_record_id" value="<?= $anasthesia_record_id?>" style="display: none;">
    <a href="#" onclick="goBack()"class="art-button-green">BACK</a> 
     <div id="recoverypredata"></div>  
    <script>
        
        function goBack(){
            window.history.back();
        }
        function anaesthesia_recovery_predata_dialog(){
            $.ajax({
            type:'POST',
            url:'Ajax_anaesthesia_form.php',           
            data:{recovery_predata:''},
            success:function(responce){
                $("#recoverypredata").dialog({
                    title: 'ANAESTHESIA PRE-DATA',
                    width: 1200, 
                    height: 600, 
                    modal: true
                    });
                $("#recoverypredata").html(responce);
            }
        })
        }
    </script>
    <?php
        $select_arrival_data = mysqli_query($conn, "SELECT * FROM tbl_anaesthesia_recovery_data WHERE  anasthesia_record_id='$anasthesia_record_id' ") or die(mysqli_error($conn));

        while($arrival_rw = mysqli_fetch_assoc($select_arrival_data)){
            $Arrival_date = $arrival_rw['Arrival_date'];
            $Time_in = $arrival_rw['Time_in'];
            $o2by = $arrival_rw['o2by'];
            $Airway = $arrival_rw['Airway'];
            $condition_state = $arrival_rw['condition_state'];
            $ventilated = explode(',', $arrival_rw['ventilated']);
            $ventilateddata = explode(',', $arrival_rw['ventilateddata']);
            $Ettsize = explode(',', $arrival_rw['Ettsize']);
            $ETTs  = $arrival_rw['ETTs'];

            if($ETTs=='Nasal'){
                $Nasal="checked='checked'";
            }else if($ETTs=='Oral'){
                $Oral="checked='checked'";
            }
            if($o2by=='Facemask'){
                $Facemask="checked='checked'";
            }else if($o2by=='Nasalcannula'){
                $Nasalcannula="checked='checked'";
            }
            if($Airway=='Extubated'){
                $Extubated="checked='checked'";
            }else if($Airway=='Intubeted'){
                $Intubeted="checked='checked'";
            }

            if($condition_state=='Awake'){
                $Awake="checked='checked'";
            }else if($condition_state=='Rousable'){
                $Rousable="checked='checked'";
            }else if($condition_state=='Unconscious'){
                $Unconscious="checked='checked'";
            }else if($condition_state=='InPain'){
                $InPain="checked='checked'";
            }else if($condition_state=='Restless'){
                $Restless="checked='checked'";
            }else if($condition_state=='Calm'){
                $Calm="checked='checked'";
            }
            if($ventilated=='Manual'){
                $Manual="checked='checked'";
            }else if($ventilated=='Mechanical'){
                $Mechanical="checked='checked'";
            }
        }

        $select_discharge_data = mysqli_query($conn, "SELECT * FROM tbl_anaesthesia_recovery_discharge WHERE  anasthesia_record_id='$anasthesia_record_id' ") or die(mysqli_error($conn));
        while($dischage_rw = mysqli_fetch_assoc($select_discharge_data)){
            $others_meds = $dischage_rw['others_meds'];
            $Analgesia = $dischage_rw['Analgesia_state'];
            $vitalsondischarge = explode(',', $dischage_rw['vitalsondischarge']);
            $dataondischarge = explode(',', $dischage_rw['dataondischarge']);
        }

    ?>
    <fieldset>
        <legend align="center" style='padding:10px; color:white; background-color:#2D8AAF; text-align:center'><b>
                <h4><b>RECOVERY FORM</b></h4><br/>
                <span style='color:yellow;'><?php echo "" . $Patient_Name . "  | " .$Registration_ID ." | " . $Gender . " | " . $age . " years | " . $Guarantor_Name  . ""; ?></span></b>
        </legend>
        <input type="text" hidden  name="Registration_ID" value="<?php echo $Registration_ID?>" >
        <table class="table">
            <tbody>
                <tbody>

                    <tr>
                        <td>                            
                            <span style="display:inline;">Arrival Date:
                                <input type="date" name="condition" style="width:25%;" value="<?php echo $Arrival_date;?>" id='Arrival_date'>
                            </span>
                        </td>
                        <td>                            
                            <span style="display:inline;">Time IN:
                                <input type="time" name="condition" style="width:25%;" id='Time_in' value="<?php echo $Time_in;?>">
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td>Condition: 
                            <span style="display:inline;">
                                Awake <input type="radio" name="condition" class="condition" value='Awake' <?php echo $Awake;?>>
                                Rousable <input type="radio" name="condition" class="condition" value='Rousable' <?php echo $Rousable;?>>
                                Unconscious <input type="radio" name="condition" class="condition" value='Unconscious' <?php echo $Unconscious;?>>
                                In Pain <input type="radio" name="condition" class="condition" value='InPain' <?php echo $InPain;?>>
                                Restless <input type="radio" name="condition" class="condition" value='Restless' <?php echo $Restless;?>>
                                Calm <input type="radio" name="condition" class="condition" value='Calm' <?php echo $Calm;?>>
                                O₂ by: Face mask <input type="radio" name="o2" class="o2by" value='Facemask' <?php echo $Facemask;?>>
                                Nasal cannula <input type="radio" name="o2" class="o2by" value='Nasalcannula' <?php echo $Nasalcannula;?>>
                            </span>
                        </td>
                        <td>Airway: 
                            <span style="display:inline;">
                                Extubated <input type="radio"  name="Airway" class="Airways" value='Extubated' <?php echo $Extubated;?>>
                                Intubeted: <input type="radio"  name="Airway" class="Airways" value='Intubeted' <?php echo $Intubeted;?>>
                                ETT
                                Nasal <input type="radio" name="ETT" class='ETT' value='Nasal' <?php echo $Nasal;?>>
                                Oral <input type="radio" name="ETT" class='ETT' value='Oral' <?php echo $Oral;?>>
                                Size <input type="text" style="width:10%;" name="Ettsize[]" id='AirwaySize' value="<?php echo $Ettsize[0];?>">
                                Tracheostomy <input type="radio" name="ETT" value='Tracheostomy' <?php echo $Tracheostomy;?>>
                                Size <input type="text" style="width:10%;" name="Ettsize[]" id='tracheostomysize' value="<?php echo $Ettsize[1];?>">
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td>Ventilated:
                            <span style="display:inline;">
                                Manual <input type="radio" class="ventilated" name="ventilated"  value="Manual" <?php echo $Manual;?>>
                                Mechanical: <input type="radio" class="ventilated" name="ventilated" value="Mechanical" <?php echo $Mechanical;?>>
                                TV <input type="text" name="ventilateddata[]" value="<?php echo $ventilateddata[0];?>" style="width:15%;" id='TV' >
                                RR <input type="text" name="ventilateddata[]" value="<?php echo $ventilateddata[1];?>" style="width:15%;" id='RR'>
                                Paw <input type="text" name="ventilateddata[]" value="<?php echo $ventilateddata[2];?>" style="width:15%;" id='Raw'>
                                PEEP <input type="text" name="ventilateddata[]" value="<?php echo $ventilateddata[3];?>" style="width:15%;" id='PEEP'>
                            </span>
                        </td>
                        <td>
                            Urinary Catheter:
                            <span style="display:inline;">
                                Drain <input type="text" name="ventilateddata[]" value="<?php echo $ventilateddata[4];?>" id='Drain' style="width:15%;">
                                Site <input type="text" name="ventilateddata[]" value="<?php echo $ventilateddata[5];?>" id='CatheterSite' style="width:15%;">
                            </span>
                        </td>
                    </tr>
                    <tr>
                        
                    </tr>
                    <tr>
                        <td>VITALS ON ARRIVAL:
                            <span style="display:inline;">
                                BP <input type="text" name="ventilateddata[]" value="<?php echo $ventilateddata[6];?>" style="width:10%;" id='BP'>
                                HR <input type="text" name="ventilateddata[]" value="<?php echo $ventilateddata[7];?>" style="width:10%;" id='HR'>
                                RR <input type="text" name="ventilateddata[]" value="<?php echo $ventilateddata[8];?>" style="width:10%;" id='RR'>
                                T <input type="text" name="ventilateddata[]" value="<?php echo $ventilateddata[9];?>" style="width:10%;" id='T'>
                                SPO₂ <input type="text" name="ventilateddata[]" value="<?php echo $ventilateddata[10];?>" style="width:10%;" id='SPO2'>
                            </span>
                        </td>
                        <td>Position: 
                            <span style="display:inline;">
                                Supine  <input type="radio" name="" class="ventilated" value='Supine' <?php echo $Manual;?>>
                                Recovery <input type="radio" name="" class="ventilated" value='Recovery' <?php echo $Supine;?>>
                                IV lines: Size <input type="text" name="Ettsize[]" id='Size' style="width:5%;" value="<?php echo $Ettsize[2];?>">
                                Location  <input type="text" name="Ettsize[]" id='Location' style="width:15%;" value="<?php echo $Ettsize[3];?>">
                                Size <input type="text" name="Ettsize[]" id='Size' style="width:5%;" value="<?php echo $Ettsize[4];?>">
                                Location  <input type="text" name="Ettsize[]" id='Location' style="width:15%;" value="<?php echo $Ettsize[5];?>">
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <?php 
                            if(mysqli_num_rows($select_arrival_data)>0){

                            }else{
                                echo "<td colspan='2'>
                                    <input class='art-button-green' type='button' style='width:20%; align:right;' onclick='save_arrival_data($anasthesia_record_id)' value='SAVE'>
                                </td>";
                            }
                        ?>
                        
                    </tr>
                </tbody>
            </tbody>
        </table>
        <table width="100%" >
            <thead>
                <tr>
                    <th>SPO₂</th>
                    <th>ETCO₂</th>
                    <th>FIO₂</th>
                    <th>Temp</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id='table_vital_maintanance_body'>
            </bdody>
        </table>
    </fieldset>
    <fieldset style='overflow-y: scroll; height: 80vh; background-color: white;'>
         
         <form class="" action="" method="post" id="labour_form">
    
           <input type="hidden" name="Registration_ID" id="Registration_ID" value="<?=$Registration_ID; ?>">
          
    
           <div id="chart1">
    
           </div>
    
           <div class="col-md-3">
             <span style="font-weight:bold;"> BP</span>
             <span>O:<input style="text-align:center; width:15%;display:inline;" type="text" placeholder="time" name="o" class="input-fx" value=""> </span>
             <span>X:<input style="text-align:center; width:15%;display:inline;" type="text" placeholder="SBP" name="x"  class="input-fy" value=""> </span>
             <span>S:<input style="text-align:center; width:15%;display:inline;" type="text" placeholder="DBP" name="z"  class="input-fz" value=""> </span>

             <span><button type="button" id="add-first" class="btn btn-info" name="button" style="width:100px; height:30px !important;">Add</button> </span>
           </div>
    
           <div class="col-md-3">
             <span style="font-weight:bold;">HR</span>
             <span>O:<input style="text-align:center; width:20%;display:inline;" type="text" placeholder="time" name="o" class="input-sx" value=""> </span>
             <span>X:<input style="text-align:center; width:20%;display:inline;" type="text" placeholder="vitals" name="x"  class="input-sy" value=""> </span>
             <span><button type="button" id="add-second" class="btn btn-info" name="button" style="width:100px; height:30px !important;">Add</button> </span>
           </div>

           <div class="col-md-3">
             <span style="font-weight:bold;">MAP</span>
             <span>O:<input style="text-align:center; width:20%;display:inline;" type="text" name="o" placeholder="time" class="input-zx" value=""> </span>
             <span>X:<input style="text-align:center; width:20%;display:inline;" type="text" name="x" placeholder="vitals" class="input-zy" value=""> </span>
             <span><button type="button" id="add-third" class="btn btn-info" name="button" style="width:100px; height:30px !important;">Add</button> </span>
           </div> 
           <div class="col-md-3">
             <span style="font-weight:bold;">Temp</span>
             <span>O:<input style="text-align:center; width:20%;display:inline;" type="text" name="o" placeholder="time" class="input-tx" value=""> </span>
             <span>X:<input style="text-align:center; width:20%;display:inline;" type="text" name="x" placeholder="temp" class="input-ty" value=""> </span>
             <span><button type="button" id="add-fouth" class="btn btn-info" name="button" style="width:100px; height:30px !important;">Add</button> </span>
           </div>   
          
    </form>
    </fieldset>
    <fieldset>
        <table class="table" border="0">
            <tr>
                <td width="60%">ANALGESIA
                    <span style="display:inline;">
                        <textarea name="" id="Analgesia" class="form-control" style="width:80%;"  rows="1"><?php echo $Analgesia; ?></textarea>
                    </span>
                </td>
                <td>OTHER MEDS
                    <span style="display:inline;">
                        <textarea name="" id="others_meds" class="form-control"  rows="1"><?php echo $others_meds; ?></textarea>
                    </span>
                </td>
            </tr>
            <tr>
                <td>IV FLUIDS
                    <span style="display:inline;">
                    <input name="dataondischarge[]" type="text" id="" style="width:80%;" value="<?php echo $dataondischarge[0]; ?>" >
                    </span>
                </td>
                <td>BLOOD PRODUCTs
                    <span style="display:inline;">
                    <input name="dataondischarge[]" type="text" id="" style="width:80%;" value="<?php echo $dataondischarge[1]; ?>" >
                    </span>
                </td>
            </tr>
            <tr>
                <td>Discharge Time:
                    <span style="display:inline;">
                    <input name="dataondischarge[]" type="text" id="" style="width:80%;"  value="<?php echo $dataondischarge[2]; ?>">
                    </span>
                </td>
                <td>Condition
                    <span style="display:inline;">
                        <input name="dataondischarge[]" type="text" id="" style="width:80%;" value="<?php echo $dataondischarge[3]; ?>" >
                    </span>
                </td>
            </tr>
            <tr>
                <td>Discharge To:
                    <span style="display:inline;">
                        <input name="dataondischarge[]" type="text" id=""  style="width:70%;" value="<?php echo $dataondischarge[4]; ?>">
                    </span>
                </td>
                <td>Discharged By:
                    <span style="display:inline;">
                        <?php $Employee_name = $_SESSION['userinfo']['Employee_Name']; ?>
                        <input type="text" value="<?= $Employee_name?>" style="width:80%;"> 
                    </span>
                </td>
            </tr>
            <tr>
                <td>Vitals On Discharge:
                    <span style="display:inline;" >
                        BP <input type="text" name="vitalsondischarge[]" placeholder="SBS/DBS" style="width:10%;" id='BP' value="<?php echo $vitalsondischarge[0]; ?>">
                        HR <input type="text" name="vitalsondischarge[]" style="width:10%;" id='HR' value="<?php echo $vitalsondischarge[1]; ?>">
                        RR <input type="text" name="vitalsondischarge[]" style="width:10%;" id='RR' value="<?php echo $vitalsondischarge[2]; ?>">
                        T <input type="text" name="vitalsondischarge[]" style="width:10%;" id='T' value="<?php echo $vitalsondischarge[3]; ?>">
                        SPO₂ <input type="text" name="vitalsondischarge[]" style="width:10%;" id='SPO2' value="<?php echo $vitalsondischarge[4]; ?>">
                    </span>
                </td>
                <?php 
                    if(mysqli_num_rows($select_discharge_data)>0){

                    }else{
                        echo "<td>
                                <input class='art-button-green' style='width:20%;' type='button' onclick='save_ondischarge_data($anasthesia_record_id)' value='SAVE'>
                            </td>";
                    }
                ?>
                
            </tr>
        </table>
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
    <script>
        function  save_arrival_data(anasthesia_ID){
            var Arrival_date = $("#Arrival_date").val();            
            var Time_in = $("#Time_in").val();
            // if(Arrival_date=='' || Time_in==''){
            //     alert("Fill time of arrival");
                
            // }
            var ventilated= [];
            $(".ventilated:checked").each(function() {
                ventilated.push($(this).val());
            });
            var condition=[]
            $(".condition:checked").each(function() {
                condition.push($(this).val());
            });
            var Airway=[]
            $(".Airways:checked").each(function() {
                Airway.push($(this).val());
            });
            var ETTs=[]
            $(".ETT:checked").each(function() {
                ETTs.push($(this).val());
            });
            var o2by=[]
            $(".o2by:checked").each(function() {
                o2by.push($(this).val());
            });
            var ventilateddata=[];
            var valuedata = document.getElementsByName('ventilateddata[]');
            for (var i = 0; i <valuedata.length; i++) {
                var inp=valuedata[i];
                ventilateddata.push(inp.value);
            }
            var Ettsize=[];
            var size = document.getElementsByName('Ettsize[]');
            for (var i = 0; i <size.length; i++) {
                var inp=size[i];
                Ettsize.push(inp.value);
            }
            var ventilated = ventilated.toString()
            var Ettsize = Ettsize.toString()
            var ventilateddata = ventilateddata.toString()
            var o2by = o2by.toString()
            var ETTs = ETTs.toString()
            var Airway = Airway.toString()
            var condition = condition.toString()
            $.ajax({
                type:"POST",
                url:'Ajax_anaesthesia_form.php',
                data:{Arrival_date:Arrival_date,Time_in:Time_in, ventilated:ventilated, condition:condition, Airway:Airway, ETTs:ETTs, o2by:o2by, ventilateddata:ventilateddata,Ettsize:Ettsize, anasthesia_ID:anasthesia_ID},
                success:function(responce){
                    alert(responce);
                }
            })
        }
        function save_ondischarge_data(anasthesia_ID){
            var Registration_ID = '<?= $Registration_ID ?>';
            var anasthesia_record_id = '<?= $anasthesia_record_id; ?>';
            var consultation_ID = '<?= $consultation_ID;?>' 
            var others_meds = $("#others_meds").val();
            var Analgesia = $("#Analgesia").val();
            if(Analgesia==''){
              $("#Analgesia").css("border", "2px solid red");
              return false;
            }
            var vitalsondischarge=[];
            var valuedata = document.getElementsByName('vitalsondischarge[]');
            for (var i = 0; i <valuedata.length; i++) {
                var inp=valuedata[i];
                vitalsondischarge.push(inp.value);
            }
            
            if(vitalsondischarge ==''){
              alert("Please fill discharge vitals");
              return false;
            }
            var dataondischarge=[];
            var dischargedt = document.getElementsByName('dataondischarge[]');
            for (var i = 0; i <dischargedt.length; i++) {
                var inp=dischargedt[i];
                dataondischarge.push(inp.value);
            }
            var dataondischarge = dataondischarge.toString();
            var vitalsondischarge = vitalsondischarge.toString();                                                                                      
            $.ajax({
                type:"POST",
                url:'Ajax_anaesthesia_form.php',
                data:{others_meds:others_meds,Analgesia:Analgesia,consultation_ID:consultation_ID,Registration_ID:Registration_ID,anasthesia_record_id:anasthesia_record_id, vitalsondischarge:vitalsondischarge, dataondischarge:dataondischarge,  anasthesia_ID:anasthesia_ID, patient_ondischarge:''},
                success:function(responce){
                  alert(responce);
                    // window.open("anesthesia_record_chart.php?Registration_ID=<?= $Registration_ID ?>", 'parent')
                }
            })
        }
    </script>
    <script type="text/javascript">
    
    
    var cervical_pointsx = [[]];
    var cervical_pointsy = [[]];
    var cervical_pointzx = [[]];
    var cervical_pointzy = [[]];
    var cervical_pointfx = [[]];
    var cervical_pointfy = [[]];
    var cervical_pointfz = [[]];

    var cervical_pointty = [[]];
    var cervical_pointtz = [[]];
    function plotFetalGraph(){
    
    
    //   var powPoints1= [[8,0],[8,10]];
    //   var powPoints2 =[[0,3],[8,3]];
    //   var powPoints3=[[8,3],[15,10]];
    //   var powPoints4 = [[12,3],[19,10]];
            var powPoints1= [[0,0],[0,0]];
            var powPoints2 =[[0,0],[0,0]];
            var powPoints3=[[0,0],[0,0]];
            var powPoints4 = [[0,0],[0,0]];
            var powPoints5 = [[0,0],[0,0]];
    
    
    
    
      var plot3 = $.jqplot('chart1', [cervical_pointsx,cervical_pointsy,cervical_pointzx,cervical_pointzy,cervical_pointfx,cervical_pointfy, cervical_pointfz,cervical_pointty, cervical_pointtz],
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
                markerOptions: {size:7,style:"line" }
              },
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
      var anasthesia_record_id = $("#anasthesia_record_id").val();
    
    // fetch cervical points if exist
      $.ajax({
        url:"Ajax_anaesthesia_form.php",
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
        url:"Ajax_anaesthesia_form.php",
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
        url:"Ajax_anaesthesia_form.php",
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

      $.ajax({
        url:"Ajax_anaesthesia_form.php",
        type:"POST",
        data:{Registration_ID:Registration_ID,anasthesia_record_id:anasthesia_record_id, tempreadings:''},
        success:function(data){ 
          console.log(data);       
          var cervical_data = JSON.parse(data);
          var point=[];
        for (var i = 0; i <cervical_data.length; i++) {
          var counter = cervical_data[i];
          point = [parseFloat(counter.tx), parseInt(counter.ty)];

          cervical_pointty.push(point)
          
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
      var anasthesia_record_id = $("#anasthesia_record_id").val();
    
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
          url:"Ajax_anaesthesia_form.php",
          type:"POST",
          data:{Registration_ID:Registration_ID,anasthesia_record_id:anasthesia_record_id,fx:fx,fy:fy,fz:fz, BP:''},
          success:function(data){
            $(".input-fx").val('')
            $(".input-fy").val('')
            $(".input-fz").val('')
            location.reload();            
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
       var anasthesia_record_id = $("#anasthesia_record_id").val();
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
         url:"Ajax_anaesthesia_form.php",
         type:"POST",
         data:{Registration_ID:Registration_ID,anasthesia_record_id:anasthesia_record_id,sx:sx,sy:sy,HR:''},
         success:function(data){
          $(".input-sy").val('');
          $(".input-sx").val('');
          
           console.log(data)
           location.reload();
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
        var anasthesia_record_id = $("#anasthesia_record_id").val();
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
         url:"Ajax_anaesthesia_form.php",
         type:"POST",
         data:{Registration_ID:Registration_ID,anasthesia_record_id:anasthesia_record_id,zx:zx,zy:zy,MAP_insert:''},
         success:function(data){
          $(".input-zx").val()
          $(".input-zy").val()
           console.log(data)
           location.reload();
           var cervical_data = JSON.parse(data.trim());
           cervical_pointsy.push(cervical_data);
    
           plotFetalGraph();
    
         }
       })
      }
    })

    $("#add-fouth").click(function(e){
      e.preventDefault();
    
        var Registration_ID = $("#Registration_ID").val();
        var anasthesia_record_id = $("#anasthesia_record_id").val();
        var tx = $(".input-tx").val();
        var ty = $(".input-ty").val();       
        if(tx==""){
            $(".input-tx").css("border", "1px solid red");
          }else if(ty==""){
            $(".input-ty").css("border", "1px solid red");  
          }else{
            $(".input-tx").css("border", "");
            $(".input-ty").css("border", "");
       $.ajax({
         url:"Ajax_anaesthesia_form.php",
         type:"POST",
         data:{Registration_ID:Registration_ID,anasthesia_record_id:anasthesia_record_id,tx:tx,ty:ty,temp_insert:''},
         success:function(data){
          $(".input-tx").val()
          $(".input-ty").val()
           console.log(data)
          //  location.reload();
           var cervical_data = JSON.parse(data.trim());
           cervical_pointsy.push(cervical_data);
    
           plotFetalGraph();
    
         }
       })
      }
    })
    
 

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
  
<script>
          $(document).ready(function(){
            Select_mntainance_vitals();
          })     

          function add_Vitals_meaintanance(){
            var SPO2e = $("#SPO2normal").val();
            var ETCO2e = $("#ETCO2").val();
            var FIO2s = $("#FIO2").val();
            var Temp = $("#Temp").val();
            // var Fluids_bt = $("#Fluids_bt").val();
            // var MACe = $("#MAC").val();
            var Registration_ID = '<?= $Registration_ID?>';
            var anasthesia_record_id = <?= $anasthesia_record_id; ?>;
            // alert(SPO2e);exit();
            $.ajax({
              type:'POST',
              url:'Ajax_anaesthesia_form.php', 
              data:{SPO2:SPO2e, ETCO2:ETCO2e,FIO2:FIO2s,anasthesia_record_id:anasthesia_record_id, Registration_ID:Registration_ID, Temp:Temp, Vitals_meaintanance_add:''},
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
              url:'Ajax_anaesthesia_form.php', 
              data:{Registration_ID:Registration_ID,anasthesia_record_id:anasthesia_record_id, view_mntainance_vitals:''},
              success:function(responce){
                $("#table_vital_maintanance_body").html(responce);
              }
            })
          }
          
  </script>