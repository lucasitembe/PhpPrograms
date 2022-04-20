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
<input type="button" value="BACK" onclick="history.go(-1)" class="art-button-green">
<br/>
<fieldset>  
    <legend align=center><b>RADIATION PARAMETER RE-CALCULATION</b></legend>

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

<?php 
$Techinique_tittle = mysqli_query($conn,"SELECT techniques from tbl_position_immobilization WHERE Registration_ID='$Registration_ID' ORDER BY position_immobilization_ID DESC LIMIT 1") or die(mysqli_error($conn));
	$cancer_title = mysqli_fetch_assoc($Techinique_tittle)['techniques'];
?>
    <div class="row" style="background-color: white; margin:0;width:100%">
        <div class="col-md-2"></div>
            <div class="box box-primary">
                <div class="box-header">
                    <center><h4>PRESCRIBED DOSAGE</h4></center>
                <div id="previous_data"></div>
                <hr>
                    <!-- <center><h4><b>FIELDS</b></h4></center> -->
             <center> <h4>FIELDS</h4></center> 
    <div class="box box-primary" style="height: 500px;overflow-y: scroll;overflow-x: hidden">

                                    
       <?php
                        $field_name= "";
                        $f_s_on_skin= "";
                        $f_s_on_tumour= "";
                        $ssd_sad= "";
                        $depth= "";
                        $gantry_angle= "";
                        $coll_angle="";
                        $cough_angle= "";
						$Skin_length="";
                        
        $select_data_simulation = mysqli_query($conn,"SELECT ps.field_ID, pm.techniques,ps.field_name,pm.number_phases, ps.f_s_on_skin,ps.Skin_length,ps.f_s_on_tumour,ps.ssd_sad,ps.depth,ps.gantry_angle,ps.coll_angle,ps.cough_angle FROM tbl_fields_position ps,tbl_position_immobilization pm WHERE ps.position_immobilization_ID=pm.position_immobilization_ID AND pm.Radiotherapy_ID='$Radiotherapy_ID' AND ps.field_ID IN(SELECT field_ID FROM tbl_calculation_parameter)");
        if(mysqli_num_rows($select_data_simulation)>0){
        while($row = mysqli_fetch_assoc($select_data_simulation)){
                        $field_name= $row['field_name'];
                        $f_s_on_skin= $row['f_s_on_skin'];
                        $f_s_on_tumour= $row['f_s_on_tumour'];
                        $ssd_sad= $row['ssd_sad'];
                        $depth= $row['depth'];
                        $gantry_angle= $row['gantry_angle'];
                        $coll_angle= $row['coll_angle'];
                        $cough_angle= $row['cough_angle'];
						$Skin_length= $row['Skin_length'];
                        $techniques= $row['techniques'];
                        $field_ID = $row['field_ID'];
                        $number_phases = $row['number_phases'];

 $area = ($f_s_on_skin * $Skin_length * 2) / ($f_s_on_skin + $Skin_length);
        
         $Result = $area;
            echo "<table class='table' style='background: #FFFFFF; width: 100%' id='colum-addition'>
                                        <tr>
                                            <td>
                                                <b>FIELD</b>
                                            </td>
                                            <td colspan='2' width='10%'>
                                                <b>F.S. on Skin(cm)</b>
                                            </td>
                                            <td colspan='2'>
                                               <b>Technique</b>
                                            </td>
                                            <td>
                                               <b>SAD(cm)</b>
                                            </td>
                                            <td>
                                                <b>Depth Ant/Post (cm)</b> 
                                            </td>
                                            <td colspan='2'>
                                                <b>Gantry Angle</b> 
                                            </td>
                                            <td>
                                                <b>Coll.Angle</b> 
                                            </td>
                                            <td>
                                                <b>Couch Angle</b>
                                            </td>
                                            <td>
                                                <b>Eq. Square</b>
                                            </td>
                                        </tr>
										<tr>
                                            <td >
                                              <input type='text' name='Skin[]' autocomplete='off' style='width:100%;display:inline;height:30px;'  value=".$field_name."> 
                                            </td>
                                           
                                            <td>
                                               <input type='text' name='Skin[]' autocomplete='off' style='width:100%;display:inline;height:30px;' value=".$f_s_on_skin."> 
                                            </td>
											<td>
                                               <input type='text' name='Skin_length[]' autocomplete='off' style='width:100%;display:inline;height:30px;' value=".$Skin_length."> 
                                            </td>
                                            <td colspan='2'>
                                               <input type='text' name='Tumour[]' autocomplete='off' style='width:100%;display:inline;height:30px;' value=".$cancer_title."> 
                                            </td>
                                            <td>
                                                <input type='text' name='SSD[]' autocomplete='off' style='width:100%;display:inline;height:30px;' value=".$ssd_sad."> 
                                            </td>
                                            <td>
                                                <input type='text' name='Depth[]' autocomplete='off' style='width:100%;display:inline;height:30px;' value=".$depth."> 
                                            </td>
                                            <td colspan='2'>
                                               <input type='text' name='Gantry[]' autocomplete='off' style='width:100%;display:inline;height:30px;' value=".$gantry_angle."> 
                                            </td>
                                            <td>
                                               <input type='text' name='Coll[]' autocomplete='off' style='width:100%;display:inline;height:30px;' value=".$coll_angle."> 
                                            </td>
                                            <td>
                                               <input type='text' name='Cough[]' autocomplete='off' style='width:100%;display:inline;height:30px;' value=".$cough_angle."> 
                                            </td>
                                            <td>
                                               <input type='text' id='Eq_Square' autocomplete='off' style='width:100%;display:inline;height:30px;' value=".$Result.">
                                            </td>
                                            
                                          
                                        </tr>
										<tr style='background: #dedede;'>
                                           <td>
                                                <b>cGY/Min in (Output)</b>
                                            </td>
                                            <td>
                                                <b>cGY/Min in (Dmax)</b>
                                            </td>
                                            <td>
                                                <b>PDD</b>
                                            </td>
                                            <td>
                                                <b>TAR</b>
                                            </td>
                                            <td>
                                                <b>Inverse Square Factor</b>
                                            </td>
                                            <td>
                                                <b>Couch Factor</b>
                                            </td>
                                            <td>
                                                <b>Wedge Factor</b>
                                            </td>
                                            <td>
                                                <b>Inhomogy Tray Factor</b>
                                            </td>
											<td>
                                                <b>BSF/TSF</b>
                                            </td>
                                            <td>
                                                <b>Tumour Dose Rate</b>
                                            </td>
                                            <td>
                                                <b>Dose per Field</b>
                                            </td>
                                            <td>
                                                <b>Treatment Time Min/MU</b>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                               <input type='text' id='cGY_SAD".$field_ID."' onkeyup='save_radiation_parameter(".$field_ID.")' autocomplete='off' style='width:100%;display:inline;height:30px;'/> 
                                            </td>
                                            <td>
                                               <input type='text' id='cGY_SSD".$field_ID."' onkeyup='save_radiation_parameter(".$field_ID.")' autocomplete='off' style='width:100%;display:inline;height:30px;'/> 
                                            </td>
                                            <td>
                                               <input type='text' id='PDD".$field_ID."' onkeyup='save_radiation_parameter(".$field_ID.")' autocomplete='off' style='width:100%;display:inline;height:30px;'/> 
                                            </td>
                                            <td>
                                               <input type='text' id='TAR".$field_ID."' onkeyup='save_radiation_parameter(".$field_ID.")' autocomplete='off' style='width:100%;display:inline;height:30px;'/> 
                                            </td>
                                            <td>
                                               <input type='text' id='Inverse_square_factor".$field_ID."' value='1.0125' onkeyup='save_radiation_parameter(".$field_ID.")' autocomplete='off' style='width:100%;display:inline;height:30px;'/> 
                                            </td>
                                            <td>
                                               <input type='text' id='Couch_Factor".$field_ID."' onkeyup='save_radiation_parameter(".$field_ID.")' autocomplete='off' style='width:100%;display:inline;height:30px;'/> 
                                            </td>
                                            <td>
                                               <input type='text' id='Wedge_Factor".$field_ID."' onkeyup='save_radiation_parameter(".$field_ID.")' autocomplete='off' style='width:100%;display:inline;height:30px;'/> 
                                            </td>
                                            <td>
                                               <input type='text' id='Inhomogy_Tray".$field_ID."' onkeyup='save_radiation_parameter(".$field_ID.")' autocomplete='off' style='width:100%;display:inline;height:30px;'/> 
                                            </td>
											<td>
                                               <input type='text' id='BSF".$field_ID."' onkeyup='save_radiation_parameter(".$field_ID.")' autocomplete='off' style='width:100%;display:inline;height:30px;'/> 
                                            </td>
                                            <td>
                                               <input type='text' id='Tumour_Dose".$field_ID."' onkeyup='save_radiation_parameter(".$field_ID.")' autocomplete='off' style='width:100%;display:inline;height:30px;'/> 
                                            </td>
                                            <td>
                                               <input type='text' id='Dose_Fraction".$field_ID."' onkeyup='save_radiation_parameter(".$field_ID.")' autocomplete='off' style='width:100%;display:inline;height:30px;'/> 
                                            </td>
                                            <td>
                                               <input type='text' id='Treatment_Time".$field_ID."' onkeyup='save_radiation_parameter(".$field_ID.")' autocomplete='off' style='width:100%;display:inline;height:30px;'/> 
                                               <input type='text' id='cancer_title".$field_ID."' value='".$cancer_title."' autocomplete='off' style='width:100%;display:none;height:30px;'/> 
                                            </td>
										</tr>
									</table>
									<br>
										";
        }
    }else{
        echo "<h3>NO PENDING REQUEST TO BE CALCULATED</h3>";
    }
        
       
         
         
          $Select_name = mysqli_query($conn,"SELECT Patient_Name,Gender,Sponsor_ID FROM tbl_patient_registration WHERE Registration_ID='$Registration_ID'");
   
      $Patient_Name ="";
      $Gender="";
      $Guarantor_Name="";
    while($row = mysqli_fetch_assoc($Select_name)){
          $Patient_Name =$row['Patient_Name'];
          $Gender =$row['Gender'];
          $Sponsor_ID =$row['Sponsor_ID'];
          
          $Guarantor_Name = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Guarantor_Name FROM tbl_sponsor WHERE Sponsor_ID='$Sponsor_ID'"))['Guarantor_Name'];
        
        
    }
?> 
                                    
                                   <center> <input type="button" name="SAVE" value="SAVE" class="art-button-green" onclick="save_radiation_parameter()"></center>
            </div>
                
</div>
            <table>
                <tr>
                    <td style='width: 10%; text-align: right;'><h4>Comment</h4></td>
                    <td style='width: 90%;'><textarea name="Comments_calculation" id="Comments_calculation" cols="200" rows="3"></textarea></td>
                </tr>
            </table>
              
        
</fieldset>


    
<?php
    include("./includes/footer.php");
?>

<script>
            $(document).ready(function () {
                check_phase();
            });
      function save_radiation_parameter(field_ID){
           var Registration_ID = <?php echo $Registration_ID ?>;
           var Re_Employee_ID = <?php echo $Employee_ID ?>;
           var Eq_Square = $('#Eq_Square'+field_ID).val();
           var cGY_SSD = $('#cGY_SSD'+field_ID).val();
           var cGY_SAD = $('#cGY_SAD'+field_ID).val();
           var PDD = $('#PDD'+field_ID).val();
           var TAR = $('#TAR'+field_ID).val();
           var BSF = $("#BSF"+field_ID).val();
           var Inverse_square_factor = $("#Inverse_square_factor"+field_ID).val();
           var Couch_Factor = $('#Couch_Factor'+field_ID).val();
           var Wedge_Factor = $('#Wedge_Factor'+field_ID).val();
           var Inhomogy_Tray = $('#Inhomogy_Tray'+field_ID).val();
           var Tumour_Dose = $('#Tumour_Dose'+field_ID).val();
           var Dose_Fraction = $('#Dose_Fraction'+field_ID).val();
           var Treatment_Time = $('#Treatment_Time'+field_ID).val();
            
        //    alert(Tumour_Dose);
        //    exit();
           $.ajax({
            type: 'POST',
            url: 'save_radiation_parameter.php',
            data: {Eq_Square:Eq_Square,cGY_SSD:cGY_SSD,cGY_SAD:cGY_SAD,PDD:PDD,TAR:TAR,Couch_Factor:Couch_Factor,Wedge_Factor:Wedge_Factor,Inhomogy_Tray:Inhomogy_Tray,Tumour_Dose:Tumour_Dose,Dose_Fraction:Dose_Fraction,Treatment_Time:Treatment_Time,Registration_ID:Registration_ID,Re_Employee_ID:Re_Employee_ID,field_ID:field_ID,BSF:BSF,Inverse_square_factor:Inverse_square_factor},
            success: function (result) {
                  console.log(result);
                //  alert("success data saved");
                calculate_treatment(field_ID);
            }, error:function(x,y,z){
                console.log(x+y+z);
            }
        });  
    }
    function calculate_treatment(field_ID) {
        var Eq_Square = $('#Eq_Square'+field_ID).val();
        var cGY_SSD = $('#cGY_SSD'+field_ID).val();
        var cGY_SAD = $('#cGY_SAD'+field_ID).val();
        var PDD = $('#PDD'+field_ID).val();
        var TAR = $('#TAR'+field_ID).val();
        var BSF = $('#BSF'+field_ID).val();
        var Inverse_square_factor = $("#Inverse_square_factor"+field_ID).val();
        var Couch_Factor = $('#Couch_Factor'+field_ID).val();
        var Wedge_Factor = $('#Wedge_Factor'+field_ID).val();
        var Inhomogy_Tray = $('#Inhomogy_Tray'+field_ID).val();
        var Tumour_Dose = $('#Tumour_Dose'+field_ID).val();
        var Dose_Fraction = $('#Dose_Fraction'+field_ID).val();
        var Treatment_Time = $('#Treatment_Time'+field_ID).val();
        var cancer_title = $("#cancer_title"+field_ID).val();

        if(Couch_Factor == '' || Couch_Factor == 0){
            Couch_Factor = 1;
        }
        if(Wedge_Factor == '' || Wedge_Factor == 0){
            Wedge_Factor = 1;
        }
        if(Inhomogy_Tray == '' || Inhomogy_Tray == 0){
            Inhomogy_Tray = 1;
        }
        if(Tumour_Dose == '' || Tumour_Dose == 0){
            Tumour_Dose = 1;
        }
        if(Dose_Fraction == '' || Dose_Fraction == 0){
            Dose_Fraction = 1;
        }
        if(PDD == '' || PDD == 0){
            PDD = 1;
        }
        if(TAR == '' || TAR == 0){
            TAR = 1;
        }
        if(BSF == '' || BSF == 0){
            BSF = 1;
        }
        if(cancer_title.includes("SAD")){
            var datas = (cGY_SAD*Inverse_square_factor)/(BSF);
            let data = datas.toFixed(2)
           if (!isNaN(data)) {
            // results = results.toFixed(2);
               document.getElementById('Tumour_Dose'+field_ID).value = data;
            //    alert(results);
           }

            var results = (Dose_Fraction)/(Tumour_Dose*TAR*Couch_Factor*Wedge_Factor*Inhomogy_Tray);
            let num = results.toFixed(2)
           if (!isNaN(num)) {
            // results = results.toFixed(2);
               document.getElementById('Treatment_Time'+field_ID).value = num;
            //    alert(results);
           }
        }else if(cancer_title.includes("SSD")){
            var way = cGY_SSD*PDD*Couch_Factor*Wedge_Factor*Inhomogy_Tray;
            var results = (Dose_Fraction)/ (way);
            let num = results.toFixed(2)
           if (!isNaN(num)) {
            // results = results.toFixed(2);
               document.getElementById('Treatment_Time'+field_ID).value = num;
            //    alert(results);
           }
        }
        save_radiation_parameter(field_ID);

    }

    function check_phase() {
        consultation_ID = '<?= $consultation_ID ?>';
        Registration_ID = '<?= $Registration_ID ?>';
        Treatment_Phase = '<?= $number_phases ?>';

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
</script>