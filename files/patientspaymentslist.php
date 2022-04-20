<?php
    include("./includes/connection.php");
    include("./includes/header.php");
    
    if(!isset($_SESSION['userinfo'])){
        @session_destroy();
        header("Location: ../index.php?InvalidPrivilege=yes");
    }
    
    if(isset($_SESSION['userinfo']['Revenue_Center_Works'])){
        if($_SESSION['userinfo']['Revenue_Center_Works'] != 'yes'){
            header("Location: ./index.php?InvalidPrivilege=yes");
        }
    }else{
        @session_destroy();
        header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_GET['from_billing'])){
        echo "<a href='patientbillingwork.php?PatientsBillingWorks=PatientsBillingWorks' class='art-button-green'>BACK</a>";
    }else{
        echo "<a href='departmentpatientbillingpage.php?DepartmentPatientBilling=DepartmentPatientBillingThisPage' class='art-button-green'>BACK</a>";
    }
    ?>
<br/><br/>
<style>
    table,tr,td{
        border-collapse:collapse !important;
        border:none !important;
    }
    #sss:hover{
        background-color:#eeeeee;
        cursor:pointer;
    }
</style>
<fieldset>
    <table width="100%">
        <tr>
            <td width="20%">
                <input type="text" name="Patient_Name" id="Patient_Name" placeholder="~~~ ~~ Patient Name ~~ ~~~" autocomplete="off" style="text-align: center;" onkeypress="Search_Patient('Patient_Name')" oninput="Search_Patient('Patient_Name')">
            </td>
            <td width="20%">
                <input type="text" name="Patient_Number" id="Patient_Number" placeholder="~~~ ~~ Patient Number ~~ ~~~" autocomplete="off" style="text-align: center;" onkeypress="Search_Patient('Patient_Number')" oninput="Search_Patient('Patient_Number')">
            </td>
            <td width="10%" style="text-align: right;">Sponsor Name</td>
            <td>
                <select id="Sponsor_ID" name="Sponsor_ID" onchange="Filter_Patient()">
                    <option value="0">All</option>
            <?php
                $select = mysqli_query($conn,"select Guarantor_Name, Sponsor_ID from tbl_sponsor") or die(mysqli_error($conn));
                $num = mysqli_num_rows($select);
                if($num > 0){
                    while ($data = mysqli_fetch_array($select)) {
            ?>
                        <option value="<?php echo $data['Sponsor_ID']; ?>"><?php echo $data['Guarantor_Name']; ?></option>
            <?php
                    }
                }
            ?>
                </select>
            </td>
            <td width="15%"><input type="text" name="Start_Date" id="date" placeholder="~~~ Start Date ~~~" autocomplete="off" style="text-align: center;"></td><td width="1%">&nbsp;</td>
            <td width="15%"><input type="text" name="End_Date" id="date2" placeholder="~~~ End Date ~~~" autocomplete="off" style="text-align: center;"></td><td width="1%">&nbsp;</td>
            <td width="7%"><input type="button" name="FILTER" class="art-button-green" value="FILTER" onclick="Filter_Patient();"></td>
        </tr>
    </table>
</fieldset>
<fieldset style="overflow-y: scroll; height: 400px; background-color: white;" id="Patient_Area">
    <legend align="left"><b>PAYMENTS PREVIEW LIST</b></legend>
    <table width = "100%">
        <tr><td colspan="7"><hr></td></tr>
        <tr>
            <td width="5%"><b>SN</b></td>
            <td><b>PATIENT NAME</b></td>
            <td width="14%"><b>PATIENT NUMBER</b></td>
            <td width="14%"><b>SPONSOR NAME</b></td>
            <td width="15%"><b>PATIENT AGE</b></td>
            <td width="9%"><b>GENDER</b></td>
            <td width="15%"><b>VISIT DATE</b></td>
        </tr>
        <tr><td colspan="7"><hr></td></tr>
    </table>
</fieldset>

<dir id="Patient_Dateils">
    
</dir>

<script type="text/javascript">
    function Search_Patient(parameter){
        var Patient_Number = document.getElementById("Patient_Number").value;
        var Patient_Name = document.getElementById("Patient_Name").value;
        var Sponsor_ID = document.getElementById("Sponsor_ID").value;
        var Start_Date = document.getElementById("date").value;
        var End_Date = document.getElementById("date2").value;

        if(parameter == 'Patient_Name'){
            document.getElementById("Patient_Number").value = '';
        }else if(parameter == 'Patient_Number'){
            document.getElementById("Patient_Name").value = '';
            document.getElementById("Sponsor_ID").value = 0;
            Sponsor_ID = 0;
        }
        if(Start_Date != null && Start_Date != '' && End_Date != null && End_Date != ''){
            document.getElementById("date").style = 'border: 1px solid black; text-align: center;';
            document.getElementById("date2").style = 'border: 1px solid black; text-align: center;';
            if (window.XMLHttpRequest) {
                myObjectSearch = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                myObjectSearch = new ActiveXObject('Micrsoft.XMLHTTP');
                myObjectSearch.overrideMimeType('text/xml');
            }

            myObjectSearch.onreadystatechange = function () {
                dataCheck = myObjectSearch.responseText;
                if (myObjectSearch.readyState == 4) {
                    document.getElementById("Patient_Area").innerHTML = dataCheck;
                }
            }; //specify name of function that will handle server response........

            if(parameter == 'Patient_Name'){
                myObjectSearch.open('GET', 'Patient_Payments_List.php?Sponsor_ID='+Sponsor_ID+'&Patient_Name='+Patient_Name+'&Start_Date='+Start_Date+'&End_Date='+End_Date, true);
            }else if(parameter == 'Patient_Number'){
                myObjectSearch.open('GET', 'Patient_Payments_List.php?Sponsor_ID='+Sponsor_ID+'&Patient_Number='+Patient_Number+'&Start_Date='+Start_Date+'&End_Date='+End_Date, true);
            }
            myObjectSearch.send();
        }else{
            if (Start_Date == '' || Start_Date == null) {
                document.getElementById("date").style = 'border: 3px solid red; text-align: center;';
            }else{
                document.getElementById("date").style = 'border: 1px solid black; text-align: center;';
            }
            if (End_Date == '' || End_Date == null) {
                document.getElementById("date2").style = 'border: 3px solid red; text-align: center;';
            }else{
                document.getElementById("date2").style = 'border: 1px solid black; text-align: center;';
            }
        }
    }
</script>

<script type="text/javascript">
    function Filter_Patient(){
        var Sponsor_ID = document.getElementById("Sponsor_ID").value;
        var Patient_Number = document.getElementById("Patient_Number").value;
        var Patient_Name = document.getElementById("Patient_Name").value;
        var Start_Date = document.getElementById("date").value;
        var End_Date = document.getElementById("date2").value;

        if(Start_Date != null && Start_Date != '' && End_Date != null && End_Date != ''){
            document.getElementById("date").style = 'border: 1px solid black; text-align: center;';
            document.getElementById("date2").style = 'border: 1px solid black; text-align: center;';
            if (window.XMLHttpRequest) {
                myObjectPr = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                myObjectPr = new ActiveXObject('Micrsoft.XMLHTTP');
                myObjectPr.overrideMimeType('text/xml');
            }

            myObjectPr.onreadystatechange = function () {
                dataPr = myObjectPr.responseText;
                if (myObjectPr.readyState == 4) {
                    document.getElementById("Patient_Area").innerHTML = dataPr;
                }
            }; //specify name of function that will handle server response........

            myObjectPr.open('GET', 'Patient_Payments_List.php?Sponsor_ID='+Sponsor_ID+'&Patient_Number='+Patient_Number+'&Patient_Name='+Patient_Name+'&Start_Date='+Start_Date+'&End_Date='+End_Date, true);
            myObjectPr.send();
        }else{
            if (Start_Date == '' || Start_Date == null) {
                document.getElementById("date").style = 'border: 3px solid red; text-align: center;';
            }else{
                document.getElementById("date").style = 'border: 1px solid black; text-align: center;';
            }
            if (End_Date == '' || End_Date == null) {
                document.getElementById("date2").style = 'border: 3px solid red; text-align: center;';
            }else{
                document.getElementById("date2").style = 'border: 1px solid black; text-align: center;';
            }
        }
    }
</script>

<script type="text/javascript">
    function Preview_Details(Registration_ID,Check_In_ID,Sponsor_ID){
        if (window.XMLHttpRequest) {
            myObjectDetails = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectDetails = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectDetails.overrideMimeType('text/xml');
        }

        myObjectDetails.onreadystatechange = function () {
            dataDetails = myObjectDetails.responseText;
            if (myObjectDetails.readyState == 4) {
                document.getElementById("Patient_Dateils").innerHTML = dataDetails;
                $("#Patient_Dateils").dialog("open");
            }
        }; //specify name of function that will handle server response........

        myObjectDetails.open('GET', 'Preview_Details.php?Registration_ID='+Registration_ID+'&Sponsor_ID='+Sponsor_ID+'&Check_In_ID='+Check_In_ID, true);
        myObjectDetails.send();
    }
</script>

<script type="text/javascript">
    function Preview_Report(Registration_ID,Check_In_ID,Sponsor_ID){
        window.open("patientpaymentdetail.php?Registration_ID="+Registration_ID+"&Check_In_ID="+Check_In_ID+"&Sponsor_ID="+Sponsor_ID+"PatientPaymentDetails=PatientPaymentDetailsThisPage","_blank");
    }
</script>
<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>
<script src="script.js"></script>
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
<script src="script.responsive.js"></script>
<link rel="stylesheet" href="css/select2.min.css" media="screen">
<script src="js/select2.min.js"></script>

<script type='text/javascript'>
    $(document).ready(function () {
      $("#Patient_Dateils").dialog({ autoOpen: false, width:'80%',height:530, title:'PATIENT PAYMENT DETAILS',modal: true});
        $('select').select2();
    });
</script>
<?php
    include("./includes/footer.php");
?>