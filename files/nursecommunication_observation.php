
<?php
include("./includes/header.php");
include("./includes/connection.php");
$requisit_officer = $_SESSION['userinfo']['Employee_Name'];

if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_SESSION['userinfo'])) {
//    if (isset($_SESSION['userinfo']['Admission_Works'])) {
//        if ($_SESSION['userinfo']['Admission_Works'] != 'yes') {
//            header("Location: ./index.php?InvalidPrivilege=yes");
//        }
//    } else {
//        header("Location: ./index.php?InvalidPrivilege=yes");
//    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}

$nav='';
$divStyle='style="height: 220px;overflow-y: auto;overflow-x: hidden;background-color:white"';

if(isset($_GET['discharged'])){
   $nav='&discharged=discharged';
   $divStyle='style="height: 280px;overflow-y: auto;overflow-x: hidden;background-color:white"';
}

if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Admission_Works'] == 'yes') {

      $backlink = $_SERVER['HTTP_REFERER'];
        ?>
        <a href="nursecommunicationpage.php?<?php echo $_SERVER['QUERY_STRING'] ?>" alt="" class='art-button-green'>
            BACK
        </a>
    <?php }
}
?>


<?php
if (isset($_GET['Registration_ID'])) {
    $Registration_ID = $_GET['Registration_ID'];
    $Patient_Payment_Item_List_ID = $_GET['Patient_Payment_Item_List_ID'];
} else {
    $Registration_ID = 0;
}

if ($Registration_ID != 0) {
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
    } else {
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

$Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
	
    }

$age = date_diff(date_create($DOB), date_create('today'))->y;
?>
<?php
if (isset($_POST['submitRadilogyform'])) {
        $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
       
        $Blood_Pressure=mysqli_real_escape_string($conn,$_POST['Blood_Pressure']);
	//$Registration_ID=mysqli_real_escape_string($conn,$_POST['Registration_ID']);
	$date=mysqli_real_escape_string($conn,$_POST['date']);
	$Pulse_Blood=mysqli_real_escape_string($conn,$_POST['Pulse_Blood']);
	$Temperature=mysqli_real_escape_string($conn,$_POST['Temperature']);
	$Resp_Bpressure=mysqli_real_escape_string($conn,$_POST['Resp_Bpressure']);
	$Fluid_Drug=mysqli_real_escape_string($conn,$_POST['Fluid_Drug']);
	$fbg=mysqli_real_escape_string($conn,$_POST['fbg']);
        $Drainage=mysqli_real_escape_string($conn,$_POST['Drainage']);
	$rbg=mysqli_real_escape_string($conn,$_POST['rbg']);	
	$oxygen_saturation=mysqli_real_escape_string($conn,$_POST['oxygen_saturation']);	
	$Urine=mysqli_real_escape_string($conn,$_POST['Urine']);    //
        $Registration_ID = mysqli_real_escape_string($conn,$_POST['registration_ID']);
        $consultation_ID = mysqli_real_escape_string($conn,$_POST['consultation_ID']);
        $blood_transfusion = mysqli_real_escape_string($conn,$_POST['blood_transfusion']);
        $body_weight = mysqli_real_escape_string($conn,$_POST['body_weight']);
        
        //Previous code..used to insert patient consultation id
	
	//$insert_testing_record = "INSERT INTO tbl_nursecommunication_observation(Registration_ID,consultation_ID,employee_ID,date,Blood_Pressure,Pulse_Blood,Temperature,Resp_Bpressure,Fluid_Drug,Oral_Fluid,Drainage,Gas_Tric,Urine)
	//			               VALUES('$Registration_ID','$consultation_ID','$Employee_ID',NOW(),'$Blood_Pressure','$Pulse_Blood','$Temperature','$Resp_Bpressure','$Fluid_Drug','$Oral_Fluid','$Drainage','$Gas_Tric','$Urine')";
	
        
	$insert_testing_record = "INSERT INTO tbl_nursecommunication_observation(Registration_ID,employee_ID,date,Blood_Pressure,Pulse_Blood,Temperature,Resp_Bpressure,Fluid_Drug,fbg,Drainage,rbg,Urine,oxygen_saturation,consultation_ID,body_weight,blood_transfusion)
				               VALUES('$Registration_ID','$Employee_ID',NOW(),'$Blood_Pressure','$Pulse_Blood','$Temperature','$Resp_Bpressure','$Fluid_Drug','$fbg','$Drainage','$rbg','$Urine','$oxygen_saturation','$consultation_ID','$body_weight','$blood_transfusion')";
	
        
	 if(!mysqli_query($conn,$insert_testing_record)){
            die(mysqli_error($conn));
         }
     
   
    ?>
<script>
   //alert("SAVED SUCCESSIFULLY");
   window.location="nursecommunication_observation.php?<?php echo $_SERVER['QUERY_STRING']?>";
</script>
<?php
  
}


 $sql_date_time = mysqli_query($conn,"select now() as Date_Time ") or die(mysqli_error($conn));
    while($date = mysqli_fetch_array($sql_date_time)){
        $Current_Date_Time = $date['Date_Time'];
    }
    $Filter_Value = substr($Current_Date_Time,0,11);
    $Start_Date = $Filter_Value.' 00:00';
    $End_Date = $Filter_Value.' 23:59';;
?>


<style>
    select,input{
        padding:5px;
        font-size:18px;
        width:100%; 
    }

    table,tr,td{
        border-collapse:collapse !important;
        border:none !important;

    }	

</style>
<fieldset>
    <legend align="center" style='padding:10px;color:white;background-color:#2D8AAF;text-align:center'><b>
            <b>OBSERVATION CHART</b><br/>
            <?php echo "<b>" . $Patient_Name . "</b>  | " . $Gender . "</b> | <b>" . $age . " years</b> | <b>" . $Sponsor . "</b>"; ?></b>
    </legend>
      <?php if(empty($nav)){?>
    <center>
        <table width="100%" class="hiv_table" > 

            <tr>
                <td colspan="4" width="100%"><hr></td>
            </tr>
            <tr>
                <td colspan="4">


                    <form action="#" method="POST"  name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">
                        <center>
                            <table style="width:100%" class="hiv_table"  >

                                <tr>
                                    <td style="text-align:right;font-size:14px" width="13%"><b>Date and Time</b></td>
                                    <td>
                                        <input type="text" name="date" class="input_style" readonly value='<?php echo $original_Date; ?>' />
                                    </td>
                                    <td style="text-align:right;font-size:14px" width="13%"><b>BP  (mmhg)</b></td>
                                    <td>
                                        <input type="text" name="Blood_Pressure" class="input_style" />
                                    </td>
                                    <td style="text-align:right;font-size:14px" width="13%"><b>Pulse  (bpm)</b></td>
                                    <td>
                                       <input type="text" name="Pulse_Blood" class="input_style"  />
                                    </td> 
                                </tr>
                               
                                <tr>
                                    <td style="text-align:right;font-size:14px" width="13%"><b>Temp(centigrade)</b></td>
                                    <td>
                                       <input type="text" name="Temperature" class="input_style" />
                                    </td>
                                    <td style="text-align:right;font-size:14px" width="13%" required="required"><b>RESP(bpm)</b></td>
                                    <td  ><input type="text" name="Resp_Bpressure" class="input_style"/></td>
                                    
                                    <td style="text-align:right;font-size:14px " width="13%" ><b>Oxygen saturation</b></td>
                                    <td><input type="text" name="oxygen_saturation" class="input_style"  /></td>
                                    <td style="text-align:right;font-size:14px;display: none " width="15%" ><b>Intravenous Fluid Drugs</b></td>
                                    <td ><input type="text" style="text-align:right;font-size:14px;display: none " name="Fluid_Drug" class="input_style"  /></td>
                                </tr>
                                <tr>
                                    <td style="text-align:right;font-size:14px" width="13%" required="required"><b>FBG</b></td>
                                    <td  ><input type="text" name="fbg" class="input_style"  /></td>
<!--                                    <td style="text-align:right;font-size:14px" width="13%" required="required"><b>Oral Fluid (cc)</b></td>
                                    <td  ><input type="text" name="Oral_Fluid" class="input_style"  /></td>-->
                                    <td style="text-align:right;font-size:14px" width="13%" required="required"><b>Drainage (cc)</b></td>
                                    <td  ><input type="text" name="Drainage" class="input_style"  /></td>
                                   <td style="text-align:right;font-size:14px;display: none " width="13%" ><b>Urine</b></td>
                                    <td style="text-align:right;font-size:14px;display: none "><input type="text" name="Urine" class="input_style"  /></td>
                                   
                                </tr>
                                <tr>
                                    <td style="text-align:right;font-size:14px " width="13%" ><b>RBG</b></td>
                                    <td ><input type="text" name="rbg" class="input_style"  /></td>
<!--                                    <td style="text-align:right;font-size:14px " width="13%" class="hide"><b>Gastric</b></td>
                                    <td ><input type="text" name="Gas_Tric" class="input_style hide"  /></td>-->
                                    <td style="text-align:right;font-size:14px " width="13%" ><b>Body Weight</b></td>
                                    <td ><input type="text" name="body_weight" class="input_style"  /></td>

                                    <!-- ************************************************************************************************************* -->
                                   <?php

                                   if($Patient_Payment_Item_List_ID != ''){



                                         $patient_payment_item_id = mysqli_query($conn, "SELECT `Patient_Payment_Item_List_ID`,c.Status,t.Product_Name
                                         FROM tbl_patient_payment_item_list i,tbl_patient_payments p,tbl_item_list_cache c,tbl_items t
                                         WHERE p.Patient_Payment_ID = i.Patient_Payment_ID AND
                                         p.Patient_Payment_ID = c.Patient_Payment_ID AND
                                         i.Item_ID = t.Item_ID AND
                                         p.Registration_ID = '$Registration_ID' AND
                                         c.`Check_In_Type` = 'Surgery' AND
                                         i.Patient_Payment_Item_List_ID IN ($Patient_Payment_Item_List_ID)
                                         ORDER BY p.Payment_Date_And_Time DESC") or die(mysqli_error($conn));

                                         $found = mysqli_num_rows($patient_payment_item_id);

                                         if($found > 0)
                                         {

                                           ?>

                                <td style="text-align:right;font-size:14px;color:red;" width="13%" required="required"><b>Select Surgery:</b></td>
                                 <td  ><select class="input_style" id="sel1" name="Patient_Payment_Item_List_ID">
                                   <?php
                                     while($r = mysqli_fetch_assoc($patient_payment_item_id)){
                                       echo "<option value='".$r['Patient_Payment_Item_List_ID']."'>".$r['Product_Name']."</option>";
                                     }
                                    ?>

                                 </select></td>
                                 <?php

                               }

                             }
                          ?>
                                 <!-- ************************************************************************************************************* -->
                                    
<!--                                    <td style="text-align:right;font-size:14px " width="13%" ><b>Oxygen saturation</b></td>
                                    <td><input type="text" name="oxygen_saturation" class="input_style"  /></td>-->
                                </tr>
                                <tr>
                                    <td style="text-align:right;font-size:14px " width="13%" ><b>Blood Transfusion</b></td>
                                    <td ><input type="text" name="blood_transfusion" class="input_style"  /></td>
                                    <td style='text-align: center;' colspan="4">
                                        <br/>
                                        <input type='hidden' name="registration_ID" value='<?php echo $_GET['Registration_ID']; ?>'/>
                                        <input type='hidden' name="consultation_ID" value='<?php echo $_GET['consultation_ID']; ?>'/>
                                       <input type='submit' name='submitRadilogyform' id='submit' value='ADD' onclick="return confirm('Are you sure you want to save infos?')" class='art-button pull-right' style="width:10%">
                                        <input type='hidden' name='submitRadilogyform' value='true'>
                                    </td>
                                </tr>
                               
                            </table> 
                        </center>
                    </form>


                </td>
            </tr>


        </table>               
    </center>
      <?php } ?>
</fieldset> 
<br/>
<fieldset>
    <center>
        <table width='100%'>
            <tr>
                <td>    
                    <input type="text" autocomplete="off" style='text-align: center;width:15%;display:inline' readonly id="start_date"  value="<?= $Start_Date ?>"placeholder="Start Date"/>
                    <input type="text" autocomplete="off" style='text-align: center;width:15%;display:inline' readonly id="end_date" value="<?= $End_Date ?>"placeholder="End Date"/>&nbsp;
                    <input type="button" value="Filter" style='text-align: center;width:15%;' class="art-button-green" onclick="filterPatient()">
                    <a href="#" class="art-button-green pull-right" onclick="view_observation_chart_by_graph()">Observation By Graph</a>
                    <a href="nursecommunication_observation_print.php?Registration_ID=<?php echo $_GET['Registration_ID']?>&consultation_ID=<?php echo $_GET['consultation_ID']?>" id="printPreview" class="art-button-green" target="_blank" style="float:right">Preview</a>
             
                </td>
            </tr>
        </table>
    </center>    
</fieldset>
<br/>

<div id="Search_Iframe" <?php echo $divStyle ?>>
    <?php include 'nursecommunication_observation_Iframe.php'; ?>
</div>

<script>
    function filterPatient() {
        var start = document.getElementById('start_date').value;
        var end = document.getElementById('end_date').value;
        var Registration_ID = '<?php echo $_GET['Registration_ID']; ?>';
        var consultation_ID = '<?php echo $_GET['consultation_ID']; ?>';
        
        if(start =='' || end==''){
            alert("Please enter both dates");
            exit;
        }
        
         $('#printPreview').attr('href', 'nursecommunication_observation_print.php?start=' + start + '&end=' + end + '&Registration_ID=' + Registration_ID + '&consultation_ID=' + consultation_ID);

        
         document.getElementById('Search_Iframe').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';
       
        
         $.ajax({
            type: "GET",
            url: "nursecommunication_observation_Iframe.php",
            data: 'start=' + start + '&end=' + end + '&Registration_ID=' + Registration_ID+ '&consultation_ID=' + consultation_ID,
            
            success: function (data) {
              if(data != ''){
               $('#Search_Iframe').html(data);
               $.fn.dataTableExt.sErrMode = 'throw';
                $('#nurse_obsv').DataTable({
                    "bJQueryUI": true,
                    "bFilter": false,
                    "sPaginationType": "fully_numbers",
                    "sDom": 't'

                });
              }
            }
        });
    }
</script>

<script src="css/jquery.datetimepicker.js"></script>
<script>
    $(document).ready(function () {
        $.fn.dataTableExt.sErrMode = 'throw';
        $('#nurse_obsv').DataTable({
            "bJQueryUI": true,
            "bFilter": false,
            "sPaginationType": "fully_numbers",
            "sDom": 't'

        });


        $('#start_date').datetimepicker({
            dayOfWeekStart: 1,
            lang: 'en',
            startDate: 'now'
        });
        $('#start_date').datetimepicker({value: '', step: 1});

        $('#end_date').datetimepicker({
            dayOfWeekStart: 1,
            lang: 'en',
            startDate: 'now'
        });
        $('#end_date').datetimepicker({value: '', step: 1});
    });

</script>
<script src="js/jquery-1.8.0.min.js"></script>
<link rel="stylesheet" type="text/css" href="css/jquery.datetimepicker.css"/>
<link rel="stylesheet" href="media/css/jquery.dataTables.css" media="screen">
<link rel="stylesheet" href="media/themes/smoothness/dataTables.jqueryui.css" media="screen">
<script src="media/js/jquery.js" type="text/javascript"></script>
<script src="media/js/jquery.dataTables.js" type="text/javascript"></script>
<script src="css/jquery.datetimepicker.js" type="text/javascript"></script>
<script src="css/jquery-ui.js"></script>


<?php
include("./includes/footer.php");
?>



<div id="observation_chart"></div>
<script>
    function view_observation_chart_by_graph(){
        var Registration_ID='<?= $Registration_ID ?>';
        var start_date = document.getElementById('start_date').value;
        var end_date = document.getElementById('end_date').value;
        var consultation_ID = '<?php echo $_GET['consultation_ID']; ?>';
        
        if(start_date =='' || end_date==''){
            alert("Please enter both dates");
            exit;
        }
        $.ajax({
            type:'POST',
            url:'ajax_view_observation_chart_by_graph.php',
            data:{Registration_ID:Registration_ID,start_date:start_date,end_date:end_date,consultation_ID:consultation_ID},
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
        var start_date = document.getElementById('start_date').value;
        var end_date = document.getElementById('end_date').value;
        var consultation_ID = '<?php echo $_GET['consultation_ID']; ?>';
        $.ajax({
            type:'POST',
            url:'ajax_get_observation_chart.php',
            data:{Registration_ID:Registration_ID,consultation_ID:consultation_ID,start_date:start_date,end_date:end_date},
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
<link rel="stylesheet" type="text/css" href="jqplot/jquery.jqplot.css" />-->


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