


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
    $select_department = mysqli_query($conn, "SELECT * FROM tbl_department d, tbl_sub_department sd WHERE Department_Status='active' AND d.Department_ID=sd.Department_ID AND Department_Location IN('Matenity','Surgery','Admission', 'Dialysis', 'Clinic','Theater')") or die(mysqli_error($conn));
    $Select_employee = mysqli_query($conn, "SELECT Employee_Name, Employee_ID FROM tbl_employee WHERE Employee_Type='Doctor'") or die(mysqli_error($conn));
    $Select_employee1 = mysqli_query($conn, "SELECT Employee_Name, Employee_ID FROM tbl_employee WHERE Employee_Type='Doctor'") or die(mysqli_error($conn));

?>
 <?php 
      $select_warrant = mysqli_query($conn, "SELECT Icu_form_ID FROM tbl_anasthesia_icuform WHERE anasthesia_record_id='$anasthesia_record_id'") or die(mysqli_error($conn));
      if(mysqli_num_rows($select_warrant)>0){
        while($row = mysqli_fetch_assoc($select_warrant)){
          $Icu_form_ID = $row['Icu_form_ID'];
          echo "
                      <a class='art-button-green' href='#'  onclick='open_warrant_form_dialogy($Icu_form_ID)'>PRIVIEW WARRANT FORM</a>
                    ";
        }
      }
      ?>
<a href="#" onclick="goBack()"class="art-button-green">BACK</a>
 <script>
 function goBack() {
    window.history.back();
 }
 </script>
<br/><br/>
<style>
    .row{
        padding-left: 10px;
    }
</style>
<fieldset>
    <legend align="center" style='padding:10px; color:white; background-color:#2D8AAF; text-align:center'><b>
            <h4><b>REFERRAL WARRANT INTO THE INTENSIVE CARE UNIT</b></h4><br />
            <span style='color:yellow;'><?php echo "" . $Patient_Name . "  | " .$Registration_ID ." | " . $Gender . " | " . $age . " years | " . $Guarantor_Name  . ""; ?></span></b>
    </legend>
    <div class="row">
        <div class="col-md-1"></div>
        <div class="col-md-10">
            <div class="panel">               
                <div class="panel-body">
                    <div class="row" style="text-align: center;">
                        <label for="">Purpose:</label>
                        <span>This form needs to be filled in and sent to ICU before referring this patient</span>
                    </div>
                    <div class="row" style="text-align: center;">
                        <label for="">Preferred:</label>
                        <span>This patient should be reviewed by ICU specialist/Anesthesiologist prior to admission </span>
                    </div>
                    <div class="row" style="text-align: center;">
                        <label for="">Emergency Patient:</label>
                        <span>Inform the ICU team, you can fill in the form upon arrival to ICU. </span>
                    </div>
                    <br>
                    <div class="row">
                        <label for="">Patient from Where:</label>
                        <span>
                            <select class="form-control" name="Sub_Department_ID" style="width:40%; display:inline;" id="Sub_Department_ID">
                                <option value="">~~Select department~~</option>
                                <?php 
                                    if(mysqli_num_rows($select_department)>0){
                                        while($row = mysqli_fetch_assoc($select_department)){
                                            $Department_ID= $row['Sub_Department_ID'];
                                            echo "<option value='$Department_ID'>".$row['Sub_Department_Name']."</option>";
                                        }
                                    }
                                ?>
                            </select>
                        </span>
                    </div>
                    <br>
                    <div class="row">
                        <label for="">Provisional Diagnosis:</label>
                        <span> 
                            <textarea name="" id="Provisional_diagnosis" style="width:80%; display:inline;" rows="1"></textarea>
                        </span>
                    </div>
                    <br>
                    <div class="row">
                        <label for="">Reason for Transfer to ICU:</label>
                        <span> 
                            <textarea name="" id="Reason_for_transfer"  style="width:80%; display:inline;" rows="1"></textarea>
                        </span>
                    </div>
                    <br>
                    <div class="row">
                        <label for="">Patient is under the following Treatment</label>
                        <span> 
                            <textarea name="" id="Following_treatment"style="width:75%; display:inline;"  rows="1"></textarea>
                        </span>
                    </div>
                    <br>
                    <div class="row">
                        <label for="">What investigation have been done, any need of followup of result?:</label>
                        <span> 
                            <textarea name="" id="investigation_done"  style="width:60%; display:inline;" rows="1"></textarea>
                        </span>
                    </div>
                    <br>
                    <div class="row">
                        <p>If the patient is not reffered by the responsible specialists/Consultant himself/Consultant has been informed about the transfer.</p>                        
                    </div>
                    <br>
                    <div class="row">          
                        <label for="">Specialist/Consultant name:</label>
                        
                            <select name="" class="form-control" style="width:30%; display:inline;" id="Spececialist_ID">
                            <option value="">~~Select name~~</option>
                                <?php 
                                    if(mysqli_num_rows($Select_employee)>0){
                                        while($rows = mysqli_fetch_assoc($Select_employee)){
                                            $Employee_ID= $rows['Employee_ID'];
                                            echo "<option value='$Employee_ID'>".$rows['Employee_Name']."</option>";
                                        }
                                    }
                                ?>
                            </select>                               
                        
                        <label for="">Date Informed </label>
                        <input type="text" style="width:30%; display:inline;" class="ehms_date" id='Infromed_date'></>
                    </div>
                    <br>
                    <div class="row">
                        <label for="">
                            Referred By:</label>
                        <span> 
                            <select name="" class="form-control" style="width:40%; display:inline;" id="Referred_by">
                            <option value="">~~Select name~~</option>
                                <?php 
                                    if(mysqli_num_rows($Select_employee1)>0){
                                        while($rowsd = mysqli_fetch_assoc($Select_employee1)){
                                            $Employee_ID= $rowsd['Employee_ID'];
                                            echo "<option value='$Employee_ID'>".$rowsd['Employee_Name']."</option>";
                                        }
                                    }
                                ?>
                            </select>
                            
                        </span>
                        <label for="">Date Referred </label>
                        <span>
                            <input type="text" style="width:30%; display:inline;" id="ehms_date" id='referred_date'>
                            <input type="button" value="SEND TO ICU" class="art-button-green" onclick="save_anaesthesia_icu_form()">
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</fieldset>
<script src="css/jquery.js"></script>
<script src="css/jquery.datetimepicker.js"></script>
<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
<div id="warrantForm"></div>
<script>
  function open_warrant_form_dialogy(Icu_form_ID){
        $.ajax({
            type:'POST',
            url:'add_anaesthetic_item.php',           
            data:{Icu_form_ID:Icu_form_ID, icuformdialog:''},
            success:function(responce){
                $("#warrantForm").dialog({
                    title: 'REFERRAL WARRANT INTO THE INTENSIVE CARE UNIT',
                    width: 1000, 
                    height: 700, 
                    modal: true
                    });
                $("#warrantForm").html(responce);                
            }
        })
    }
</script>
<script>
     $(document).ready(function(e) {
        $("#Referred_by").select2();
        $("#Spececialist_ID").select2();
        $("#Department_ID").select2();
        $('#ehms_date').datetimepicker({
            dayOfWeekStart: 1,
            lang: 'en',
            //startDate:    'now'
        });
        $('#ehms_date').datetimepicker({value: '', step: 30});
    });
    function save_anaesthesia_icu_form(){
        var Registration_ID='<?php echo $Registration_ID; ?>';
        var consultation_ID ='<?php echo $consultation_ID; ?>';
        var Sub_Department_ID = $("#Sub_Department_ID").val();
        var Provisional_diagnosis = $("#Provisional_diagnosis").val();
        var Reason_for_transfer = $("#Reason_for_transfer").val();
        var investigation_done = $("#investigation_done").val();
        var Spececialist_ID = $("#Spececialist_ID").val();
        var Infromed_date = $("#Infromed_date").val();
        var Referred_by = $("#Referred_by").val();
        var referred_date =$("#referred_date").val();
        var Following_treatment = $("#Following_treatment").val();
        if(Sub_Department_ID==''){
            $("#Sub_Department_ID").css("border", "1px solid red");
        }else if(Spececialist_ID==''){
            $("#Spececialist_ID").css("border", "1px solid red");
        }else if(Referred_by==''){
            $("#Referred_by").css("border", "1px solid red");
        }else{
            $("#Sub_Department_ID").css("border", "1px solid green");
            $("#Spececialist_ID").css("border", "1px solid red");
            $("#Referred_by").css("border", "1px solid red");
            $.ajax({
                type:'POST',
                url:'add_anaesthetic_item.php',           
                data:{referred_date:referred_date,Following_treatment:Following_treatment, consultation_ID:consultation_ID, Sub_Department_ID:Sub_Department_ID,Provisional_diagnosis:Provisional_diagnosis, Reason_for_transfer:Reason_for_transfer,investigation_done:investigation_done, Spececialist_ID:Spececialist_ID, Infromed_date:Infromed_date,Referred_by:Referred_by,Registration_ID:Registration_ID, icu_form:''},
                success:function(responce){                
                    $("#icuform").html(responce);
                    alert(responce)
                    diaplay_icuform_data();
                }
            })
        }
    }
    function diaplay_icuform_data(){

    }
</script>

<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>
<?php
include("./includes/footer.php");
?>
