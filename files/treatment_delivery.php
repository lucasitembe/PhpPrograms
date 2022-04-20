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

    $Current_Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    
    if(isset($_GET['Registration_ID'])){
    $Registration_ID=$_GET['Registration_ID'];
}
    if(isset($_GET['from_consulted'])){
    $from_consulted=$_GET['from_consulted'];
}
//     if(isset($_GET['Patient_Payment_ID'])){
//   $Patient_Payment_ID=$_GET['Patient_Payment_ID'];
// }
//     if(isset($_GET['Patient_Payment_Item_List_ID'])){
//      $Patient_Payment_Item_List_ID=$_GET['Patient_Payment_Item_List_ID'];
    
// }
$position_immobilization_ID = $_GET['position_immobilization_ID'];
$Radiotherapy_ID = $_GET['Radiotherapy_ID'];
$response = json_decode(getDoctorRequests($conn,$Radiotherapy_ID,$Registration_ID),true);

$select_Patient = mysqli_query($conn,"SELECT
patient_type,
Old_Registration_Number,Title,Patient_Name,
    Date_Of_Birth,Patient_Picture,
        Gender,Religion_Name,Denomination_Name,
pr.Country,pr.Region,pr.District,pr.Ward,pr.Tribe,
            Member_Number,Member_Card_Expire_Date,
                pr.Phone_Number,Email_Address,Occupation,
                    Employee_Vote_Number,Emergence_Contact_Name,
                        Emergence_Contact_Number,Company,Registration_ID,
                            Employee_ID,Registration_Date_And_Time,Guarantor_Name,
                            Registration_ID,sp.Sponsor_ID, sp.Exemption,pr.Diseased,pr.national_id,
                            village
          from tbl_patient_registration pr LEFT JOIN tbl_denominations de ON de.Denomination_ID=pr.Denomination_ID LEFT JOIN tbl_religions re  ON re.Religion_ID=de.Religion_ID, 
          tbl_sponsor sp 
            where pr.Sponsor_ID = sp.Sponsor_ID and 
                  Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
    $no = mysqli_num_rows($select_Patient);

    if ($no > 0) {
    while ($row = mysqli_fetch_array($select_Patient)) { //

    $Registration_ID = $row['Registration_ID'];
    $Old_Registration_Number = $row['Old_Registration_Number'];
    $Title = $row['Title'];
    $Patient_Name = ucwords(strtolower($row['Patient_Name']));
    $Date_Of_Birth = $row['Date_Of_Birth'];
    $Gender = $row['Gender'];
    $village = $row['village'];
    $Country = $row['Country'];
    $Region = $row['Region'];
    $Sponsor_ID = $row['Sponsor_ID'];
    $District = $row['District'];
    $Ward = $row['Ward'];
    $Tribe = $row['Tribe'];
    $Guarantor_Name = $row['Guarantor_Name'];
    $Member_Number = $row['Member_Number'];
    $Member_Card_Expire_Date = $row['Member_Card_Expire_Date'];
    $Phone_Number = $row['Phone_Number'];
    $Email_Address = $row['Email_Address'];
    $Occupation = $row['Occupation'];
    $Employee_Vote_Number = $row['Employee_Vote_Number'];
    $Emergence_Contact_Name = ucwords(strtolower($row['Emergence_Contact_Name']));
    $Emergence_Contact_Number = $row['Emergence_Contact_Number'];
    $Company = $row['Company'];
    $Employee_ID = $row['Employee_ID'];
    $Registration_Date_And_Time = $row['Registration_Date_And_Time'];
    $Exemption = strtolower($row['Exemption']); //
    $Diseased = strtolower($row['Diseased']);
    $national_id = $row['national_id'];
    $Patient_Picture = $row['Patient_Picture'];
    $Religion_Name = $row['Religion_Name'];
    $Denomination_Name = $row['Denomination_Name'];
    // echo $Ward."  ".$District."  ".$Ward; exit;
    }
    }
 //today function
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
    $Filter_Value = substr($Current_Date_Time,0,11);
    $Start_Date = $Filter_Value.' 00:00';
    $End_Date = $Current_Date_Time;

       $Dr_Employee_Name = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Employee_Name FROM tbl_employee em, tbl_radiotherapy_requests rq WHERE rq.Radiotherapy_ID = '$Radiotherapy_ID' AND em.Employee_ID = rq.Employee_ID"))['Employee_Name'];
?>

<a href='radiation_parameter_re_calculation.php?Registration_ID=<?= $Registration_ID ?>&Radiotherapy_ID=<?= $Radiotherapy_ID ?>&Patient_Payment_Item_List_ID=<?= $Patient_Payment_Item_List_ID ?>&SearchListDirectCashPatientBilling=SearchListDirectCashPatientBillingThisPage&from_consulted=<?= $from_consulted ?>' class='art-button-green'>
       RE-CALCULATE PARAMETER
    </a>
  <a href='treatment_devery_patientlist.php?Registration_ID=<?= $Registration_ID ?>&Patient_Payment_ID=<?= $Patient_Payment_ID ?>&Patient_Payment_Item_List_ID=<?= $Patient_Payment_Item_List_ID ?>&SearchListDirectCashPatientBilling=SearchListDirectCashPatientBillingThisPage&from_consulted=<?= $from_consulted ?>' class='art-button-green'>
       BACK
    </a>
<br/>
<br/>
<fieldset>  
    <legend align=center><b>RADIOTHERAPY TREATMENT DELIVERY</b></legend>
    <table  width="40%" class="table" style="background: #FFFFFF;">
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
                        echo "<tr>
                        <td>$Patient_Name</td>
                        <td>$Registration_ID</td>
                        <td>$Dr_Employee_Name</td>
                        <td>$age</td>
                        <td>$Gender </td>
                        <td>$Guarantor_Name </td>
                        <td>".$Region."/".$District."</td>
                      </tr>";
        ?>
                <tr>
                    <th style='text-align: right;'>DIAGNOSED DISEASE</th>
                    <td colspan='6'> 
                    <?php foreach($response as $data) : ?>
                        <?=$data['disease_name']?> (<b><?=$data['disease_code']?></b>); 
                    <?php endforeach; ?>
                    </td>
                </tr>
        </table>
                <div class="row" style="background-color: white; margin:0% 0% 0% 5px;width:99%; ">
                <div class="col-md-2"></div>
            <div class="box box-primary">
                <div class="box-header">
                    <center> <h4>Machine Setup</h4></center>  
                </div>
               <!--<input type="text" id='search_value' onkeyup="search_item()"placeholder="Search item" class="form-control" style="width:90%;"/></span></caption>-->
        <div class="box-body" >
            <table class="table" style="background-color: white; margin:0% 0% 0% 5%;width:90%">
                        <tr>
                                <td width="6%">
                                    <b>Unit</b>  
                        
                          <select style="height:27px" name='unit' id='unit'  required="">
                                <option value="Co-60 BHABHATRON II">Co-60 BHABHATRON II</option>
                            </select>
                  
                        </td>
                                <td width="5%">
                                    <b>Wedge</b> 
                        
                          <select style="height:27px" name='wedge' id='wedge'  required="">
                                <option value="0">0</option>
                                <option value="15">15</option>
                                <option value="30">30</option>
                                <option value="40">45</option>
                                <option value="60">60</option>
                            </select>
                        </td>
                        <td width="6%">
                            <b>Block</b>
                                <select style="height:27px" name='block' id='block'  required="">
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                            </select>
                        </td>
                         <td width="6%">
                             <b>Dose per Fraction</b> 
                        
                          <input type="text" name="Dose_per_Fraction" id="Dose_per_Fraction" style="width:70px;">
                  
                        </td>
                         <td width="6%">
                             <b>Total tumour Dose</b>
                        
                         <input type="text" name="total_tumour_dose" id="total_tumour_dose" style="width:70px;">
                  
                        </td>
                         <td width="6%">
                             <b>Number of Fraction</b>
                             <input type="text" name="number_of_fraction" id="number_of_fraction" style="width:70px;">
                  
                        </td>
                        <td width="6%">
                             <b>Phases</b>
                             <select style="height:27px; width:100px;" name='number_phases' id='number_phases'  onchange='load_phase()' required="">
                                <?php
                                $Select_Phases = mysqli_query($conn, "SELECT Treatment_Phase FROM tbl_radiotherapy_phases WHERE Radiotherapy_ID = '$Radiotherapy_ID' AND Phase_Status = 'Submitted' ORDER BY Ordered_No ASC") or die(mysqli_error($conn));
                                    while($rows = mysqli_fetch_assoc($Select_Phases)){
                                        $Treatment_Phase = $rows['Treatment_Phase'];
                                        echo "<option>".$Treatment_Phase."</option>";
                                    }
                                ?>
                                
                            </select>
                            
                        </td>
                        </tr>
                        <tbody id="table_search">
                          
                        </tbody>
                    </table>
                </div>
                <!-- <input type='button'class='art-button-green' id='addrow1' style='margin-left:95% !important;' value='Add'><br/><br/> -->

                <center> <h4>Treatment Delivery</h4></center> 
                <div class="box-body" >
                    <table class="table" style="background-color: white; margin:0% 0% 0% 5%;width:90%" id='colum-addition'>
                        <tr>

                            <div id="load_treatment"></div>
                            </tr>
                          <input type='hidden' id='rowCount' value='1'>
                        
                    </table>
                </div>

                <center> <input type="button" name="SAVE" value="SAVE" class="art-button-green" onclick="save_treatment_develiver()"></center></br>
                <div class="box-body" >
                <center> <h4>Previous Delivered Treatment Delivery</h4></center> 
                    <table class="table" style="background-color: white; margin:0% 0% 0% 0%;width:90%" id='colum-addition'>
                        <tr>

                            <div id="load_Previous_treatment" style="height: 300px;overflow-y: scroll;overflow-x: hidden"></div>
                            </tr>
                          <input type='hidden' id='rowCount' value='1'>
                        
                    </table>
                </div>
            </div>
                
                </div>
              
        
</fieldset>


    
<?php
    include("./includes/footer.php");
?>

<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
    <script src="js/jquery-1.8.0.min.js"></script>
    <script src="js/jquery-ui-1.8.23.custom.min.js"></script>
    <script src="css/jquery.datetimepicker.js"></script>

<script>
    // $(document).ready(function () {
    //     load_phase(Radiotherapy_ID);
    // });
    $(document).ready(function(){
        load_phase();
	})
    function add_treatment(field_name) {
        dosage = $("#Commulative_dose"+field_name).val()
        Treatment_Time = $("#Treatment_Time"+field_name).val()
        Employee_ID = <?php echo $Current_Employee_ID ?>;
        position_immobilization_ID = <?php echo $position_immobilization_ID ?>;
        wedge = $('#wedge').val();
        block = $('#block').val();
        Dose_per_Fraction = $('#Dose_per_Fraction'+field_name).val();
        total_tumour_dose = $('#total_tumour_dose').val();
        number_of_fraction = $('#number_of_fraction').val();
        unit = $('#unit').val();
        Radiotherapy_ID = '<?= $Radiotherapy_ID ?>';
        number_phases = $("#number_phases").val();

        $.ajax({
            type: "POST",
            url: "ajax_treatment_delivery.php",
            data: {
                    dosage:dosage,
                    field_name:field_name,
                    Employee_ID:Employee_ID,
                    position_immobilization_ID:position_immobilization_ID,
                    wedge:wedge,
                    block:block,
                    total_tumour_dose:total_tumour_dose,
                    Treatment_Time:Treatment_Time,
                    Dose_per_Fraction:Dose_per_Fraction,
                    unit:unit,
                    Radiotherapy_ID:Radiotherapy_ID,
                    number_of_fraction:number_of_fraction,
                    number_phases:number_phases,
                    action:'update',
                    Radiotherapy_ID:Radiotherapy_ID
                    },
            cache: false,
            success: function (response) {

            }
        });
    }
    
    function save_treatment_develiver() {
        Employee_ID = <?php echo $Current_Employee_ID ?>;
        Radiotherapy_ID = '<?= $Radiotherapy_ID ?>';
        number_phases = $("#number_phases").val();

            if(confirm("This action is Irreversable, Do You want to Save this Treatment?")){
                $.ajax({
                    type: "POST",
                    url: "ajax_treatment_delivery.php",
                    data: {
                            Employee_ID:Employee_ID,
                            number_phases:number_phases,
                            action:'save',
                            Radiotherapy_ID:Radiotherapy_ID
                            },
                    cache: false,
                    success: function (response) {
                        load_phase();
                    }
                });
            }
    }

    function load_phase() {
        Radiotherapy_ID = '<?= $Radiotherapy_ID ?>';
        number_phases = $("#number_phases").val();
        number_of_fraction = $("#number_of_fraction").val();

        if (window.XMLHttpRequest) {
            myObjectPost = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectPost = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectPost.overrideMimeType('text/xml');
        }

        myObjectPost.onreadystatechange = function () {
            dataPost = myObjectPost.responseText;
            if (myObjectPost.readyState == 4) {
                document.getElementById('load_treatment').innerHTML = dataPost;
                // $("#Submit_data").dialog("open");
                check_Tumor_Dose();
                check_Number_Of_Fraction();
                check_Dose_per_Fraction();
                check_previous_treatment();
            }
        }; //specify name of function that will handle server response........

        myObjectPost.open('GET', 'ajax_check_treatment_delivery.php?Radiotherapy_ID='+Radiotherapy_ID+'&number_phases='+number_phases+'&number_of_fraction='+number_of_fraction, true);
        myObjectPost.send();                    
    }

    function check_previous_treatment() {
        Radiotherapy_ID = '<?= $Radiotherapy_ID ?>';
        number_phases = $("#number_phases").val();
        number_of_fraction = $("#number_of_fraction").val();

        if (window.XMLHttpRequest) {
            myObjectPost = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectPost = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectPost.overrideMimeType('text/xml');
        }

        myObjectPost.onreadystatechange = function () {
            dataPost = myObjectPost.responseText;
            if (myObjectPost.readyState == 4) {
                document.getElementById('load_Previous_treatment').innerHTML = dataPost;
            }
        }; //specify name of function that will handle server response........

        myObjectPost.open('GET', 'ajax_check_previous_treatment_delivery.php?Radiotherapy_ID='+Radiotherapy_ID+'&number_phases='+number_phases+'&number_of_fraction='+number_of_fraction, true);
        myObjectPost.send(); 
    }
    
    function check_Dose_per_Fraction() {
        Dose_per_Fraction = $("#Dose_per_Fraction").val();
        Radiotherapy_ID = '<?= $Radiotherapy_ID ?>';
        number_phases = $("#number_phases").val();

        $.ajax({
            type: "GET",
            url: "ajax_check_radiotherapy_dosage.php",
            data: {number_phases:number_phases, Radiotherapy_ID:Radiotherapy_ID,key_value:'Dose_per_Fraction'},
            cache: false,
            success: function (response) {
                Results = response;
                // $("#Dose_per_Fraction").val() = Results;
                document.getElementById('Dose_per_Fraction').value = Results+" Grays";

            }
        });
    }

    function check_Number_Of_Fraction() {
        number_of_fraction = $("#number_of_fraction").val();
        Radiotherapy_ID = '<?= $Radiotherapy_ID ?>';
        number_phases = $("#number_phases").val();

        $.ajax({
            type: "GET",
            url: "ajax_check_radiotherapy_dosage.php",
            data: {number_phases:number_phases, Radiotherapy_ID:Radiotherapy_ID,key_value:'Number_of_Fraction'},
            cache: false,
            success: function (response) {
                Results = response;
                // $("#Dose_per_Fraction").val() = Results;
                document.getElementById('number_of_fraction').value = Results;

            }
        });
    }
    function check_Tumor_Dose() {
        total_tumour_dose = $("#total_tumour_dose").val();
        Radiotherapy_ID = '<?= $Radiotherapy_ID ?>';
        number_phases = $("#number_phases").val();

        $.ajax({
            type: "GET",
            url: "ajax_check_radiotherapy_dosage.php",
            data: {number_phases:number_phases, Radiotherapy_ID:Radiotherapy_ID,key_value:'Tumor_Dose'},
            cache: false,
            success: function (response) {
                Results = response;
                // $("#Dose_per_Fraction").val() = Results;
                document.getElementById('total_tumour_dose').value = Results+" Grays";

            }
        });
    }
    
    $('#date_From').datetimepicker({
    dayOfWeekStart: 1,
    lang: 'en',
    startDate: 'now'
    });
    $('#date_From').datetimepicker({value: '', step: 1});
    
       $(document).ready(function () {

         $('select').select2();
    });

</script>
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>
    <link rel="stylesheet" href="css/select2.min.css" media="screen">
<script src="js/select2.min.js"></script> 