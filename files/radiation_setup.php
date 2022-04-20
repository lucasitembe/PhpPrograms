<style>
    .otherdoclinks:hover{
        text-decoration:underline;
        color: #000000; 
        cursor:pointer; 
    }
</style>
<?php
include("./includes/header.php");
include("./includes/connection.php");
include './includes/cleaninput.php';
include("radical_treatment_functions.php");


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

   $Employee_ID = $_SESSION['userinfo']['Employee_ID'];

   $Radiotherapy_ID = (isset($_GET['Radiotherapy_ID'])) ? $_GET['Radiotherapy_ID'] : 0;
   $Registration_ID = (isset($_GET['Registration_ID'])) ? $_GET['Registration_ID'] : 0;
   $from_consulted = (isset($_GET['from_consulted'])) ? $_GET['from_consulted'] : 0;
   $Patient_Payment_ID = (isset($_GET['Patient_Payment_ID'])) ? $_GET['Patient_Payment_ID'] : 0;
   $Patient_Payment_Item_List_ID = (isset($_GET['Patient_Payment_Item_List_ID'])) ? $_GET['Patient_Payment_Item_List_ID ']: 0;


$response = json_decode(getDoctorRequests($conn,$Radiotherapy_ID,$Registration_ID),true);
$Patients = json_decode(getPatientInfomations($conn,$Registration_ID),true);

$Today_Date = mysqli_query($conn,"select now() as today");
while($row = mysqli_fetch_array($Today_Date)){
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
    $age ='';
}
    $date1 = new DateTime($Today);
    $date2 = new DateTime($Date_Of_Birth);
    $diff = $date1 -> diff($date2);
    $age = $diff->y." Years, ";
    $age .= $diff->m." Months, ";
    $age .= $diff->d." Days";
    
     $sql_date_time = mysqli_query($conn,"select now() as Date_Time ") or die(mysqli_error($conn));
    while($date = mysqli_fetch_array($sql_date_time)){
        $Current_Date_Time = $date['Date_Time'];
    }
       
     $Select_dataa= mysqli_query($conn, "SELECT Employee_Name, consultation_ID FROM tbl_employee em, tbl_radiotherapy_requests rq WHERE rq.Radiotherapy_ID = '$Radiotherapy_ID' AND em.Employee_ID = rq.Employee_ID") or die(mysqli_error($conn));
    while($data = mysqli_fetch_assoc($Select_dataa)){
        $Dr_Employee_Name = $data['Employee_Name'];
        $consultation_ID = $data['consultation_ID'];
    }
?>
<a href="radiotherapy_patient_list.php" class='art-button-green'>BACK</a>
<br/>
<br/>
<fieldset>  
    <legend align=center><b>RADIATION SIMULATION</b></legend>

    <table class="table" style="background: #FFFFFF; width: 100%">
        <tr>
            <td><b>PATIENT NAME</b></td>
            <td><b>REGISTRATION No.</b></td>
            <td><b>PRESCRIBED DOCTOR</b></td>
            <td><b>AGE</b></td>
            <td><b>GENDER</b></td>
            <td><b>SPONSOR</b></td>
            <td><b>ADDRESS</b></td>
            
        </tr>
        <?php
                 foreach($Patients as $details) :
                    $date1 = new DateTime($Today);
                    $date2 = new DateTime($details['Date_Of_Birth']);
                    $diff = $date1 -> diff($date2);
                    $age = $diff->y." Years, ";
                    $age .= $diff->m." Months, ";
                    $age .= $diff->d." Days";
                     echo "<tr>
                                <td>".$details['Patient_Name']."</td>
                                <td>".$Registration_ID."</td>
                                <td>".$Dr_Employee_Name."</td>
                                <td>".$age."</td>
                                <td>".$details['Gender']."</td>
                                <td>".$details['Guarantor_Name']."</td>
                                <td>".$details['Region']."/".$details['District']."</td></tr>";
                 
                endforeach;
        ?>
        <tr>
            <th style='text-align: right;'>DIAGNOSED DISEASE</th>
            <td colspan='6'> 
    <?php foreach($response as $data) : ?>
        <?=$data['disease_name']?> (<b><?=$data['disease_code']?></b>); 
    <?php endforeach; ?>
    </td></tr>
        </table>

                <div class="row" style="background-color: white; margin:0 2%;width:95%">
                <div class="col-md-2"></div>
            <div class="box box-primary">
                <div class="box-header">
                    <center> <h4>POSITION AND IMMOBILIZATION</h4></center>  
                </div>
               <!--<input type="text" id='search_value' onkeyup="search_item()"placeholder="Search item" class="form-control" style="width:90%;"/></span></caption>-->
                <div class="box-body" >
                    <table class="table" style="background-color: white; margin:0% 0% 0% 5%;width:90%">
                        <tr>
                                <td width="13%">
                                    <b>Body Position</b>  
                        
                          <select style="height:27px" name='body_position' id='body_position'  required="">
                                <option value=" ">---select--</option>
                                <option value="Supine">Supine</option>
                                <option value="Prone">Prone</option>
                                <option value="Right Lateral">Right Lateral</option>
                                <option value="Left Lateral">Left Lateral</option>
                                <option value="Erect">Erect</option>
                                <option value="Sitting">Sitting</option>
                            </select>
                  
                        </td>
                                <td width="15%">
                                    <b>Head Position</b> 
                        
                          <select style="height:27px" name='head_position' id='head_position'  required="">
                                <option value=" ">--select--</option>
                                <option value="Pillow">Pillow</option>
                                <option value="Head rest">Head rest</option>
                            </select>
                          <select style="height:27px" name='number_alphabet' id='number_alphabet'  required="">
                                <option value=" ">----select---</option>
                                <option value="small">Small</option>
                                <option value="Medium">Medium</option>
                                <option value="Large">Large</option>
                                <option value="A">A</option>
                                <option value="B">B</option>
                                <option value="C">C</option>
                                <option value="D">D</option>
                                <option value="E">E</option>
                                <option value="F">F</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                            </select>

                        </td>
                        <td width="10%">
                                    <b>Mask</b> 
                        
                          <select style="height:27px" name='Mask' id='Mask'  required="">
                                <option value=" ">--select--</option>
                                <option value="No Mask">No Mask</option>
                                <option value="Head Only">Head Only</option>
                                <option value="Head & Neck">Head & Neck</option>
                                <option value="Chest">Chest</option>
                                <option value="Abdomen">Abdomen</option>
                                <option value="Extrimities">Extrimities</option>
                            </select>
                          <select style="height:27px" name='number_Mask' id='number_Mask'  required="">
                                <option value=" ">----select---</option>
                                <option value="2 Clips">2 Clips</option>
                                <option value="3 Clips">3 Clips</option>
                                <option value="4 Clips">4 Clips</option>
                                <option value="5 Clips">5 Clips</option>
                                <option value="6 Clips">6 Clips</option>
                            </select>

                        </td>
                        <td width="6%">
                            <b>Arm Position</b>
                                <select style="height:27px" name='arm_position' id='arm_position'  required="">
                                <option value=" ">--select--</option>
                                <option value="On the Chest">On the Chest</option>
                                <option value="By the Sides">By the Sides</option>
                                <option value="Over the head">Over the head</option>
                            </select>
                        </td>
                         <td width="10%">
                             <b>Legs Position</b> 
                        
                          <select style="height:27px" name='leg_position' id='leg_position'  required="">
                                <option value=" ">---select---</option>
                                <option value="On the pillow">On the pillow</option>
                                <option value="flog leg">flog leg</option>
                                <option value="On the knee rest">On the knee rest</option>
                                <option value="medio lateral">medio lateral</option>
                                <option value="Lateromedial">Lateromedial</option>
                            </select>
                  
                        </td>
                         <td width="10%">
                             <b> Blocks</b>
                        
                          <select style="height:27px" name='blocks' id='blocks'  required="">
                                <option value=" ">--select--</option>
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                               
                            </select>
                  
                        </td>
                        </tr>
                        <tbody id="table_search">
                          
                        </tbody>
                    </table>
                </div><br/>
                <center> <h4>PARAMETERS</h4></center> 
                <div class="box-body" ></div>
                    <table class="table" style="background-color: white; margin:0% 0% 0% 5%;width:90%">
                        <tr>
                                <td width="14%">
                                    <b>TECHNIQUE</b>  
                        
                          <select style="height:27px" name='technique' id='technique'  required="">
                                <option value=" ">--select--</option>
                                <option value="SAD">SAD</option>
                                <option value="SSD">SSD</option>
                                <option value="Extended SSD">Extended SSD</option>
                                <option value="Extended SAD">Extended SAD</option>
                            
                            </select>
                  
                        </td>
                                <td width="10%">
                                    <b>SEPARATION</b> 
                                    <input type="text" name="separation" id="separation" style="width:70px;">CM
                        </td>
                        </td>
                                <td width="10%">
                                    <b>DEPTH</b> 
                                    <input type="text" name="depth" id="depth" style="width:70px;">CM
                        </td>
                         <td width="14%">
                             <b>Number of Site</b> 
                        
                          <select style="height:27px" name='number_of_site' id='number_of_site'  required="">
                                <option value=" ">--select--</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                            </select>
                  
                        </td>
                         <td width="14%">
                             <b> Number of Fields</b>
                        
                          <select style="height:27px" name='number_fields' id='number_fields'  required="">
                                   <option value=" ">--select--</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                            </select>
                  
                        </td>
                        <td width="14%">
                             <b> Number of Phases</b>
                        
                          <select style="height:27px; width:100px;" name='number_phases' id='number_phases' onchange="check_phase()" required="">
                          <?php
                          $Select_Phases = mysqli_query($conn, "SELECT Treatment_Phase FROM tbl_radiotherapy_phases WHERE Radiotherapy_ID = '$Radiotherapy_ID' AND Phase_Status = 'active' ORDER BY Ordered_No ASC") or die(mysqli_error($conn));
                            while($rows = mysqli_fetch_assoc($Select_Phases)){
                                $Treatment_Phase = $rows['Treatment_Phase'];
                                echo "<option>".$Treatment_Phase."</option>";
                            }
                          ?>
                            </select>
                  
                        </td>
                        </tr>
                        <tr>
                            <td colspan="6" id="previous_data" style='overflow-y: scroll; overflow-x: hidden;'>
                            </td>
                        </tr>
                    </table>
                </div>
                <center> <h4>Position Devices  & Name of Site & Diagnosis</h4></center> 
                <div class="box-body" >
                    <table class="table" style="background-color: white; margin:0% 0% 0% 5%;width:90%">
                        <tr>
                         <td width="14%">
                             <b> Beam Modifing devices</b>
                        
                          <select style="height:27px" name='beam_devices' id='beam_devices'  required="" multiple>
                                <option value=" ">--select--</option>
                                <option value="Wedges">Wedges</option>
                                <option value="Bolus">Bolus</option>
                                <option value="Tissue compasation">Tissue compasation</option>
<!--                                <option value="5">Start</option>
                                <option value="3">Referral</option>-->
                            </select>
                  
                        </td>
                         <td width="14%">
                             <b>Name of Site</b>
                        
                          <select style="height:27px" name='name_of_site' id='name_of_site'>
                                <option value=" ">--select--</option>
                                <option value="Head">Head</option>
                                <option value="Head & Neck">Head & Neck</option>
                                <option value="Chest">Chest</option>
                                <option value="Abdomen">Abdomen</option>
                                <option value="Pelvis">Pelvis</option>
                                <option value="Lower extrimities">Lower extrimities</option>
                                <option value="Upper extrimities">Upper extrimities</option>
<!--                                <option value="5">Start</option>
                                <option value="3">Referral</option>-->
                            </select>
                  
                        </td>
                         <td width="14%">
                             <b>Diagnosis</b>
                        
                          <select style="height:27px" name='Diagnosis' id='Diagnosis'>
                                <option value=" ">--select--</option>
                                <option value="Breast">Breast</option>
                                <option value="Rectum">Rectum</option>
                                <option value="Prostate">Prostate</option>
                                <option value="Abdomen">Abdomen</option>
                                <option value="Kaposes sarcoma">Kaposes sarcoma</option>
                                <option value="CACX">CACX</option>
                                <option value="Oesophagus">Oesophagus</option>
                                <option value="Nasopharygeal">Nasopharygeal</option>
                                <option value="Oral pharygeal">Oral pharygeal</option>
                                <option value="larynx">larynx</option>
                                <option value="Oral cavity">Oral cavity</option>
                                <option value="Tongue">Tongue</option>
                                <option value="Vulva">Vulva</option>
                                <option value="Keloid">Keloid</option> 
<!--                                <option value="5">Start</option>
                                <option value="3">Referral</option>-->
                            </select>
                  
                        </td>
                        </tr>
                    </table>
                </div>
                <input type='button'class='art-button-green' id='addrow1' style='margin-left:95% !important;' value='Add'>
                <center> <h4>FIELDS</h4></center> 
                                    <table class="table" style="background-color: white; margin:0% 0% 0% 5%;width:90%" id='colum-addition'>
                                        <tr>
                                            <td>
                                                <b>FIELD</b>
                                            </td>
                                            <td colspan="2" width="10%">
                                                <b>Field Size(cm)</b>
                                            </td>
                                            <!-- <td>
                                               <b>F.S. on Tumour(cm)</b>
                                            </td> -->
                                            <td>
                                               <b>SSD(cm)</b>
                                            </td>
                                            <td>
                                                <b>Depth Ant/Post (cm)</b> 
                                            </td>
                                            <td>
                                                <b>Gantry Angle</b> 
                                            </td>
                                            <td>
                                                <b>Coll.Angle</b> 
                                            </td>
                                            <td>
                                                <b>Couch Angle</b>
                                            </td>
                                            <td>
                                                <b>Remove</b>
                                            </td>
                                          
                                        </tr>
<!--                                        <tr>
                                            <td>-->
                                                <!--<table class="table" style="background-color: white; margin:0% 0% 0% 5%;width:90%" id='colum-addition'>--> 
                                           <tr>
                                            <td width="20%">
                                               <select style="height:27px; width:100%;" id="field_type[]" name="field_type[]"  required="">
                                                   <option value=" ">--select--</option>
                                                   <option value="Anterior Posterior">Anterior Posterior</option>
                                                   <option value="Posterior anterior">Posterior anterior</option>
                                                   <option value="Left Lateral">Left Lateral</option>
                                                    <option value="Right Lateral">Right Lateral</option>
                                                     <option value="Left Medial Tangential">Left Medial Tangential</option>
                                                     <option value="Right medial Tangential">Right medial Tangential</option>
                                                     <option value="Left lateral Tangential">Left lateral Tangential</option>
                                                     <option value="Right lateral Tangential">Right lateral Tangential</option>
                                                </select>
                                            </td>
                                           
                                            <td>
                                               <input type="text" name="Skin[]" autocomplete="off" placeholder="Length (X)" style='width:100%;display:inline;height:30px;'/> 
                                            </td>
											<td>
                                               <input type="text" name="Skin_length[]" autocomplete="off" placeholder="Width (Y)" style='width:100%;display:inline;height:30px;'/> 
                                            </td>
                                            <!-- <td>
                                               <input type="text" name="Tumour[]" autocomplete="off" style='width:100%;display:inline;height:30px;'/> 
                                            </td> -->
                                            <td>
                                                <input type="text" name="SSD[]" autocomplete="off" style='width:100%;display:inline;height:30px;'/> 
                                            </td>
                                            <td>
                                                <input type="text" name="Depth[]" autocomplete="off" style='width:100%;display:inline;height:30px;'/> 
                                            </td>
                                            <td>
                                               <input type="text" name="Gantry[]" autocomplete="off" style='width:100%;display:inline;height:30px;'/> 
                                            </td>
                                            <td>
                                               <input type="text" name="Coll[]" autocomplete="off" style='width:100%;display:inline;height:30px;'/> 
                                            </td>
                                            <td>
                                               <input type="text" name="Cough[]" autocomplete="off" style='width:100%;display:inline;height:30px;'/> 
                                            </td>
                                          
                                        </tr>
                                       <input type='hidden' id='rowCount' value='1'>
                                     <!--</table>-->
                        <!--<tbody id="table_search">-->
<!--                               </td>
                                          
                            </tr>-->
                        <!--</tbody>-->
                                    </table><br/>
                                    <table class="table" style="background-color: white; margin:0% 0% 0% 5%;width:90%" id='colum-addition'>
                                        <tr>
                                            <td style="text-align: right; font-weight: bold; width: 15%;">Comment</td>
                                            <td colspan='8'>
                                                <textarea name="Comment" id="Comment" cols="30" rows="3"></textarea>
                                            </td>
                                        </tr>
                                    </table>
                                   <center> <input type="button" name="SAVE" value="SAVE" class="art-button-green" onclick="save_simulation_info()"></center>
            </div>
                
            </div
              
        
</fieldset>


    
<?php
    include("./includes/footer.php");
?>

    <script>
            $(document).ready(function () {
                check_phase();
            });

        function save_simulation_info(){
            
          var Registration_ID = <?php echo $Registration_ID  ?> 
          var Radiotherapy_ID = <?php echo $Radiotherapy_ID  ?> 
          var Employee_ID = <?php echo $Employee_ID  ?> 
          var body_position = $('#body_position').val();
          var head_position = $('#head_position').val();
          var number_alphabet = $('#number_alphabet').val();
          var arm_position = $('#arm_position').val();
          var leg_position = $('#leg_position').val();
          var blocks = $('#blocks').val();
          var technique = $('#technique').val();
          var separation = $('#separation').val();
          var depth = $('#depth').val();
          var number_of_site = $('#number_of_site').val();
          var number_fields = $('#number_fields').val();
          var beam_devices = $('#beam_devices').val();
          var Diagnosis = $('#Diagnosis').val();
          var name_of_site = $('#name_of_site').val();
          var number_phases = $("#number_phases").val();
          Comment = $("#Comment").val();
          number_Mask = $("#number_Mask").val();
          Mask = $("#Mask").val();
          
//           alert(Registration_ID);
            
          var field_type=[];
          var field_types = document.getElementsByName('field_type[]');
          for (var i = 0; i <field_types.length; i++) {
            var inp=field_types[i];
                field_type.push(inp.value);
            }
          var Tumour=[];
          var Tumours = document.getElementsByName('Tumour[]');
          for (var i = 0; i <Tumours.length; i++) {
            var inp=Tumours[i];
                Tumour.push(inp.value);
            }
          var Skin=[];
          var Skins = document.getElementsByName('Skin[]');
          for (var i = 0; i <Skins.length; i++) {
            var inp=Skins[i];
                Skin.push(inp.value);
            }
			var Skin_length=[];
          var Skin_lengths = document.getElementsByName('Skin_length[]');
          for (var i = 0; i <Skin_lengths.length; i++) {
            var inp=Skin_lengths[i];
                Skin_length.push(inp.value);
            }
          var Depth=[];
          var Depths = document.getElementsByName('Depth[]');
          for (var i = 0; i <Depths.length; i++) {
            var inp=Depths[i];
                Depth.push(inp.value);
            }
          var SSD=[];
          var SSDs = document.getElementsByName('SSD[]');
          for (var i = 0; i <SSDs.length; i++) {
            var inp=SSDs[i];
                SSD.push(inp.value);
            }
          var Gantry=[];
          var Gantrys = document.getElementsByName('Gantry[]');
          for (var i = 0; i <Gantrys.length; i++) {
            var inp=Gantrys[i];
                Gantry.push(inp.value);
            }
          var Coll=[];
          var Colls = document.getElementsByName('Coll[]');
          for (var i = 0; i <Colls.length; i++) {
            var inp=Colls[i];
                Coll.push(inp.value);
            }
          var Cough=[];
          var Coughs = document.getElementsByName('Cough[]');
          for (var i = 0; i <Coughs.length; i++) {
            var inp=Coughs[i];
                Cough.push(inp.value);
            }
            
//            alert(beam_devices);

            if(confirm("Are you sure you want to Save this details?")){
                $.ajax({
                    type: 'POST',
                    url: 'save_all_data_simulation.php',
                    data: {field_type:field_type,Tumour:Tumour,Skin:Skin,Skin_length:Skin_length,Depth:Depth,SSD:SSD,Gantry:Gantry,Coll:Coll,Cough:Cough,body_position:body_position,head_position:head_position,number_alphabet:number_alphabet,arm_position:arm_position,leg_position:leg_position,blocks:blocks,technique:technique,separation:separation,depth:depth,number_of_site:number_of_site,number_fields:number_fields,number_phases:number_phases,beam_devices:beam_devices,Registration_ID:Registration_ID,Employee_ID:Employee_ID,Diagnosis:Diagnosis,name_of_site:name_of_site,Radiotherapy_ID:Radiotherapy_ID,Comment:Comment,Mask:Mask,number_Mask:number_Mask},
                    success: function (result) {
                        console.log(result);
                        alert("Simulation Data was Submitted Successfully");
                        location.reload();
                    }, error:function(x,y,z){
                        console.log(x+y+z);
                    }
                });
            }
         
            
            
        }

                        function check_phase() {
                            consultation_ID = '<?= $consultation_ID ?>';
                            Registration_ID = '<?= $Registration_ID ?>';
                            Treatment_Phase = $("#number_phases").val();

                            if (window.XMLHttpRequest) {
                                myObjectPost = new XMLHttpRequest();
                            } else if (window.ActiveXObject) {
                                myObjectPost = new ActiveXObject('Micrsoft.XMLHTTP');
                                myObjectPost.overrideMimeType('text/xml');
                            }

                            myObjectPost.onreadystatechange = function () {
                                dataPost = myObjectPost.responseText;
                                if (myObjectPost.readyState == 4) {
                                    document.getElementById('previous_data').innerHTML = dataPost;
                                }
                            }; //specify name of function that will handle server response........

                            myObjectPost.open('GET', 'ajax_check_dr_radiotherapy_request.php?consultation_ID='+consultation_ID+'&Registration_ID='+Registration_ID+'&Treatment_Phase='+Treatment_Phase, true);
                            myObjectPost.send();                          
                        }
        
                        // <td><input name='Tumour[]' class='Tumour' type='text' class='duration' id='" + rowCount + " ' style='width:100%'></td>
          $('#addrow1').click(function () {
//                alert("hpat");
                        var rowCount = parseInt($('#rowCount').val()) + 1;
                        var newRow = "<tr class='addnewrow tr" + rowCount + "'><td><select style='height:27px; width:100%;' name='field_type[]' id='" + rowCount + " ' name='field_type[]'><option value='Anterior Posterior'>Anterior Posterior</option><option value='Posterior anterior'>Posterior anterior</option><option value='Left Lateral'>Left Lateral</option><option value='Right Lateral'>Right Lateral</option><option value='Left Medial Tangential'>Left Medial Tangential</option><option value='Right Medial Tangential'>Right Medial Tangential</option><option value='Left lateral Tangential'>Left lateral Tangential</option><option value='Right lateral Tangential'>Right lateral Tangential</option></select></td><td><input name='Skin[]' placeholder='Length (X)' class='txtbox' type='text' class='Skin' id='" + rowCount + " ' style='width:100%'></td><td><input name='Skin_length[]' placeholder='Width (Y)' class='txtbox' type='text' class='Skin_length' id='" + rowCount + " ' style='width:100%'></td><td><input name='SSD[]' class='SSD' type='text' class='duration' id='" + rowCount + " ' style='width:100%'></td><td><input name='Depth[]' class='Depth' type='text' class='duration' id='" + rowCount + " ' style='width:100%'></td><td><input name='Gantry[]' class='Gantry' type='text' class='duration' id='" + rowCount + " ' style='width:100%'></td><td><input name='Coll[]' class='Gantry' type='text' class='duration' id='" + rowCount + " ' style='width:100%'></td><td><input name='Cough[]' class='Cough' type='text' class='duration' id='" + rowCount + " ' style='width:100%'></td><td><input type='button' class='remove' row_id='" + rowCount + "' value='x'></td></tr>";
                        $('#colum-addition').append(newRow);
                        document.getElementById('rowCount').value = rowCount;
                        
                        $('select').select2();
                    });
       $(document).ready(function () {

         $('select').select2();
    });
          $(document).on('click', '.remove', function () {

                        var id = $(this).attr('row_id');
                        //alert(id);
                        $('.tr' + id).remove().fadeOut();
                    });

</script>
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>
    <link rel="stylesheet" href="css/select2.min.css" media="screen">
<script src="js/select2.min.js"></script> 