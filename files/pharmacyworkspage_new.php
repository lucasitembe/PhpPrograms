<?php
    include("./includes/connection.php");
    include("./includes/header.php");

    if(!isset($_SESSION['userinfo'])){
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
    }
    
    //get today's date
    $sql_date_time = mysqli_query($conn,"SELECT now() as Date_Time ") or die(mysqli_error($conn));
    while($date = mysqli_fetch_array($sql_date_time)){
        $Current_Date_Time = $date['Date_Time'];
    }

    $duration = 1;
    $Filter_Value = substr($Current_Date_Time,0,11);

    $mod_date=date_create($Filter_Value);
    date_sub($mod_date,date_interval_create_from_date_string("$duration days"));
    $newdate =  date_format($mod_date,"Y-m-d");

    $Start_Date = $newdate.' 00:00';
    $End_Date = $Current_Date_Time;
    $sub_department_name = $_SESSION['Pharmacy'];
?>
<a href="inpatient_list.php" style="font-family: Arial, Helvetica, sans-serif;" class="art-button-green">INPATIENT CUSTOMER LIST</a>
<a href="new_pharmacy_customer_list.php" class="art-button-green" style="font-family: Arial, Helvetica, sans-serif;">OUTPATIENT CUSTOMER LIST</a>
<a href="pharmacyworks.php" class="art-button-green" style="font-family: Arial, Helvetica, sans-serif;">BACK</a>
<style>
    .rows_list{
        cursor: pointer;
    }
    .rows_list:active{
        color: #328CAF!important;
        font-weight:normal!important;
    }
    .rows_list:hover{ 
        color:#00416a;
        background: #dedede;
        font-weight:bold;
    }
    a{
        text-decoration: none;
    }
</style>
<br/><br/>
<fieldset>
    <legend align='center' style="font-family: Arial, Helvetica, sans-serif;"><b>LIST OF PATIENT SENT TO ~ <span style="color: yellow;"><?=strtoupper($sub_department_name)?> </span></b></legend>
    <center>
        <table>
            <tr>
                <td><input type="text" style="text-align:center" id='start_date' value="<?= $Start_Date ?>" readonly="readonly" placeholder="Start Date"/></td>
                <td><input type="text" style="text-align:center" id='end_date' value="<?= $End_Date ?>" readonly="readonly"  placeholder="End Date"/></td>
                <td><input type="text" style="text-align:center" onkeyup='filter_phamacy_list()' placeholder="Patient Name" id='Patient_Name'/></td>
                <td><input type="text" style="text-align:center" onkeyup='filter_phamacy_list()' placeholder="Patient Number" id="Registration_ID"/></td>
                <td><input type="button" value="FILTER" onclick="filter_phamacy_list()" style="font-family: Arial, Helvetica, sans-serif;" class="art-button-green"/>
                    <input type="hidden" id="sub_department_name" style="font-family: Arial, Helvetica, sans-serif;" value="<?=$sub_department_name?>"/></td>
            </tr>
        </table>
    </center>
    <div class="box box-primary" style="height: 600px;overflow-y: scroll;overflow-x: hidden">
        <table class="table">
            <tr style="background-color: #ddd">
                <td width='3%'><center>S/No</center></td>
                <td width='10%'>STATUS</td>
                <td width='15%'>PATIENT NAME</td>
                <td width='10%'>PATIENT NUMBER</td>
                <td width='10%'>AGE</td>
                <td width='10%'>GENDER</td>
                <td width='10%'>SPONSOR</td>
                <td width='10%'>TRANSACTION DATE</td>
                <td width='10%'>MEMBER NUMBER</td>
                <td width='10%'>BILL TYPE</td>
            </tr>
            <tbody id='patient_sent_to_pharmacy'></tbody>
        </table>
    </div>
</fieldset>
<script src="css/jquery.js"></script>
<script src="css/jquery.datetimepicker.js"></script>
<script>
    $('#start_date').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en',
    });
    $('#start_date').datetimepicker({value: '', step: 01});
    $('#end_date').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en',
    });
    $('#end_date').datetimepicker({value: '', step: 01});
</script>

<script>
    function confirm_mobile_payment(){
        $.ajax({
            type:'POST',
            url:'mobile_processing_payfolder/ajax_confirm_mobile_payment.php',
            data:{data_send:"data_send"},
            success:function(data){
                console.log(data)
            }
        });
    }
    
    function open_selected_patient(Payment_Cache_ID,Registration_ID,Transaction_Type,Check_In_Type,start_date,end_date){
        window.location="new_pharmacy_work_page.php?section=Pharmacy&Registration_ID="+Registration_ID+"&Payment_Cache_ID="+Payment_Cache_ID+"&Transaction_Type="+Transaction_Type+"&NR=True&PharmacyWorks=PharmacyWorksThisPage&Check_In_Type="+Check_In_Type;
    }

    function filter_phamacy_list(){
        var start_date=$('#start_date').val();
        var end_date=$('#end_date').val();
        var Registration_ID=$('#Registration_ID').val();
        var Patient_Name=$('#Patient_Name').val();
        var Sub_Department_Name = $('#sub_department_name').val();
        
        document.getElementById('patient_sent_to_pharmacy').innerHTML = '<tr><td colspan="10"><div align="center" style="" id="progressStatus"><center><img style="text-align:center" src="images/ajax-loader_1.gif" width="" style="border-color:white "></center></div></td></tr>';
        $.ajax({
            type:'POST',
            url:'ajax_pharmacyworks_new.php',
            data:{start_date:start_date,end_date:end_date,Registration_ID:Registration_ID,Patient_Name:Patient_Name,Sub_Department_Name:Sub_Department_Name},
            success:function(data){
                $("#patient_sent_to_pharmacy").html(data);
            }
        });
    }
     $(document).ready(function () {
        filter_phamacy_list();
    });
</script>
<?php
    include("./includes/footer.php");
?>
