<?php
include("./includes/header.php");
include("./includes/connection.php");
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Patients_Billing_Works'])) {
        if ($_SESSION['userinfo']['Patients_Billing_Works'] != 'yes') {
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
if (isset($_SESSION['userinfo'])) {
    echo "<a href='pendingbillingreport.php?PendingBillingReportWork=PendingBillingReportWorkThisPage' class='art-button-green'>PENDING BILLS REPORT</a>";
}
?>
<?php
if (isset($_SESSION['userinfo'])) {
    echo "<a href='patientbillingreports.php?PatientBillingReport=PatientBillingReportThisPage' class='art-button-green'>BACK</a>";
}
?>

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

<?php
$Today_Date = mysqli_query($conn,"select now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
    $age = '';
}
?>


<br/><br/>
<fieldset>
    <center>
        <table width=100%>
            <tr>
                <td style="text-align: center;">
                    <input type='text' name='Start_Date' id='Start_Date' style="text-align: center; width:17%" placeholder='~~ Start Date ~~'>
                    <input type="text" name="End_Date" id="End_Date" style="text-align: center; width:17%" placeholder='~~ End Date ~~'>
                    <select id="Sponsor_ID" name="Sponsor_ID" onchange="Filter_Patient_List()"  style="width:20%">
                        <option selected="selected" value="0">All Sponsor</option>
                        <?php
                        $select = mysqli_query($conn,"select Sponsor_ID, Guarantor_Name from tbl_sponsor order by Guarantor_Name") or die(mysqli_error($conn));
                        $num = mysqli_num_rows($select);
                        if ($num > 0) {
                            while ($data = mysqli_fetch_array($select)) {
                                echo '<option value="' . $data['Sponsor_ID'] . '">' . $data['Guarantor_Name'] . '</option>';
                            }
                        }
                        ?>
                    </select>
                    <select id="Transaction_Type" onchange="Filter_Patient_List()" style="width:15%" >
                        <option value="All">All Bill Types</option>
                        <option value="Credit_Bill_Details">Credit Bill Details</option>
                        <option value="Cash_Bill_Details">Cash Bill Details</option>
                    </select>
                    <select id="Hospital_Ward_ID" name="Hospital_Ward_ID" onchange="Filter_Patient_List();" style="width:15%">
                        <option selected="selected" value="All">~~ All Ward ~~</option>
                        <?php
                        $select = mysqli_query($conn,"select Hospital_Ward_ID, Hospital_Ward_Name from tbl_hospital_ward order by Hospital_Ward_Name") or die(mysqli_error($conn));
                        $nm = mysqli_num_rows($select);
                        if ($nm > 0) {
                            while ($dt = mysqli_fetch_array($select)) {  
                                ?>
                                <option value="<?php echo $dt['Hospital_Ward_ID']; ?>"><?php echo $dt['Hospital_Ward_Name']; ?></option>
                                <?php
                            }
                        }
                        ?>
                    </select>
                    <input type="button" name="Filter" id="Filter" value="FILTER" class="art-button-green" onclick="Patient_List_Search()">
                </td>
                <td width="6%">
                    <input type="button" name="Filter" id="Filter" value="PREVIEW REPORT" class="art-button-green" onclick="Preview_Report()">                    
                </td>
            </tr>
            <tr>
                <td colspan="2" style="text-align: center;">
                    <input type='text' name='Search_Patient' id='Search_Patient' style="text-align: center; width:30%" oninput='Patient_List_Search()' placeholder='~~ Enter Patient Name ~~'>
                    <input type="text" name="Patient_Number" id="Patient_Number" style="text-align: center; width:30%" oninput='Patient_List_Search2()' placeholder='~~ Enter Patient Number ~~'>
                    <select id="Item_Category_ID" onchange="Filter_Patient_List();">
                        <option value="All">All Category</option>
                        <?php 
                            $sql_select_category_result=mysqli_query($conn,"SELECT Item_Category_ID,Item_Category_Name FROM tbl_item_category") or die(mysqli_error($conn));
                            if(mysqli_num_rows($sql_select_category_result)>0){
                                while($category_rows=mysqli_fetch_assoc($sql_select_category_result)){
                                    $Item_Category_ID=$category_rows['Item_Category_ID'];
                                    $Item_Category_Name=$category_rows['Item_Category_Name'];
                                    echo "<option value='$Item_Category_ID'>$Item_Category_Name</option>";
                                }
                            }
                        ?>
                    </select>

                    <select id="Item_Employee_ID" onchange="Filter_Patient_List();">
                        <option value="All">All Employee</option>
                        <?php 
                            $sql_select_employee_result=mysqli_query($conn,"SELECT Employee_ID,Employee_Name FROM tbl_employee") or die(mysqli_error($conn));
                            if(mysqli_num_rows($sql_select_employee_result)>0){
                                while($employee_rows=mysqli_fetch_assoc($sql_select_employee_result)){
                                    $Employee_ID=$employee_rows['Employee_ID'];
                                    $Employee_Name=$employee_rows['Employee_Name'];
                                    echo "<option value='$Employee_ID'>$Employee_Name</option>";
                                }
                            }
                        ?>
                    </select>
                </td>
            </tr>
        </table>
    </center>
</fieldset>
<br/>

<script src="css/jquery.js"></script>
<script src="css/jquery.datetimepicker.js"></script>
<script>
    $('#Start_Date').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en',
        startDate: 'now'
    });
    $('#Start_Date').datetimepicker({value: '', step: 01});
    $('#End_Date').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en',
        startDate: 'now'
    });
    $('#End_Date').datetimepicker({value: '', step: 01});
</script>

<?php
?>
<center id="Patients_Fieldset_List">
<fieldset style='overflow-y: scroll; height: 400px; background-color:white'>
    <legend style="background-color:#006400;color:white" align="right"><b>CLEARED BILLS REPORT</b></legend>
    <table width="100%">
        <tr><td colspan="9"><hr></td></tr>
        <tr>
            <td width="3%"><b>SN</b></td>
            <td><b>PATIENT NAME</b></td>
            <td width="6%"><b>PATIENT#</b></td>
            <td width="10%"><b>SPONSOR NAME</b></td>
            <td width="14%"><b>PATIENT AGE</b></td>
            <td width="7%"><b>GENDER</b></td>
            <td width="9%"><b>WARD</b></td>
            <td width="6%"><b>NO. DAYS</b></td>
            <td width=10% style="text-align: right;"><b>AMOUNT</b></td>
        </tr>
        <tr><td colspan="9"><hr></td></tr>
    </table>
</fieldset>
<fieldset><table width="100%"><tr><td style="text-align: right;"><b>GRAND TOTAL : 0;</b></td></tr></table></fieldset>
</center>

<div id="Get_Patient_Details">

</div>

<div id="Select_Ward">
    Please make sure both start date, end date and ward are selected
</div>


<script>
    function Patient_List_Search() {
        var Patient_Name = document.getElementById("Search_Patient").value;
        var Sponsor_ID = document.getElementById("Sponsor_ID").value;
        var Hospital_Ward_ID = document.getElementById("Hospital_Ward_ID").value;
        var Transaction_Type = document.getElementById("Transaction_Type").value;
        var Item_Category_ID = document.getElementById("Item_Category_ID").value;
        document.getElementById("Patient_Number").value = '';
        var Start_Date  = document.getElementById("Start_Date").value;
        var End_Date  = document.getElementById("End_Date").value;
        if(Hospital_Ward_ID != null && Hospital_Ward_ID != '' && Start_Date != null && Start_Date != '' && End_Date != null && End_Date != ''){
            document.getElementById('Patients_Fieldset_List').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';
            document.getElementById("Hospital_Ward_ID").style = 'border: 2px solid black';
            if (window.XMLHttpRequest) {
                myObjectSearchPatient = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                myObjectSearchPatient = new ActiveXObject('Micrsoft.XMLHTTP');
                myObjectSearchPatient.overrideMimeType('text/xml');
            }
            myObjectSearchPatient.onreadystatechange = function () {
                data28 = myObjectSearchPatient.responseText;
                if (myObjectSearchPatient.readyState == 4) {
                    document.getElementById('Patients_Fieldset_List').innerHTML = data28;
                }
            }; //specify name of function that will handle server response........

            myObjectSearchPatient.open('GET', 'Cleared_Patient_Billing_List_Report.php?Patient_Name=' + Patient_Name + '&Sponsor_ID=' + Sponsor_ID + '&Hospital_Ward_ID=' + Hospital_Ward_ID + '&Transaction_Type=' + Transaction_Type+'&Start_Date='+Start_Date+'&End_Date='+End_Date+'&Item_Category_ID='+Item_Category_ID, true);
            myObjectSearchPatient.send();
        }else{
            $("#Select_Ward").dialog("open");
        }
    }
</script>

<script>
    function Patient_List_Search2() {
        var Patient_Number = document.getElementById("Patient_Number").value;
        var Sponsor_ID = document.getElementById("Sponsor_ID").value;
        var Hospital_Ward_ID = document.getElementById("Hospital_Ward_ID").value;
        var Item_Category_ID = document.getElementById("Item_Category_ID").value;
        document.getElementById("Search_Patient").value = '';
        var Start_Date  = document.getElementById("Start_Date").value;
        var End_Date  = document.getElementById("End_Date").value;
        if(Hospital_Ward_ID != null && Hospital_Ward_ID != '' && Start_Date != null && Start_Date != '' && End_Date != null && End_Date != ''){
            document.getElementById('Patients_Fieldset_List').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';
            if (window.XMLHttpRequest) {
                myObjectSearchPatient2 = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                myObjectSearchPatient2 = new ActiveXObject('Micrsoft.XMLHTTP');
                myObjectSearchPatient2.overrideMimeType('text/xml');
            }
            myObjectSearchPatient2.onreadystatechange = function () {
                data1 = myObjectSearchPatient2.responseText;
                if (myObjectSearchPatient2.readyState == 4) {
                    document.getElementById('Patients_Fieldset_List').innerHTML = data1;
                }
            }; //specify name of function that will handle server response........

            myObjectSearchPatient2.open('GET', 'Cleared_Patient_Billing_List_Report.php?Patient_Number=' + Patient_Number + '&Sponsor_ID=' + Sponsor_ID + '&Hospital_Ward_ID=' + Hospital_Ward_ID+'&Start_Date='+Start_Date+'&End_Date='+End_Date+'&Item_Category_ID='+Item_Category_ID, true);
            myObjectSearchPatient2.send();
        }else{
            $("#Select_Ward").dialog("open");
        }
    }
</script>


<script type="text/javascript">
    function Filter_Patient_List() {
        var Sponsor_ID = document.getElementById("Sponsor_ID").value;
        var Hospital_Ward_ID = document.getElementById("Hospital_Ward_ID").value;
        var Transaction_Type = document.getElementById("Transaction_Type").value;
        var Item_Category_ID = document.getElementById("Item_Category_ID").value;
        var Item_Employee_ID = document.getElementById("Item_Employee_ID").value;
        

        document.getElementById("Patient_Number").value = '';
        document.getElementById("Search_Patient").value = '';
        var Start_Date  = document.getElementById("Start_Date").value;
        var End_Date  = document.getElementById("End_Date").value;
        if(Hospital_Ward_ID != null && Hospital_Ward_ID != '' && Start_Date != null && Start_Date != '' && End_Date != null && End_Date != ''){
            document.getElementById('Patients_Fieldset_List').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';
            if (window.XMLHttpRequest) {
                myObjectFilter = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                myObjectFilter = new ActiveXObject('Micrsoft.XMLHTTP');
                myObjectFilter.overrideMimeType('text/xml');
            }
            myObjectFilter.onreadystatechange = function () {
                data28349 = myObjectFilter.responseText;
                if (myObjectFilter.readyState == 4) {
                    document.getElementById('Patients_Fieldset_List').innerHTML = data28349;
                }
            }; //specify name of function that will handle server response........
            myObjectFilter.open('GET', 'Cleared_Patient_Billing_List_Report.php?Sponsor_ID=' + Sponsor_ID + '&Hospital_Ward_ID=' + Hospital_Ward_ID + '&Transaction_Type=' + Transaction_Type+'&Start_Date='+Start_Date+'&End_Date='+End_Date+'&Item_Category_ID='+Item_Category_ID+'&Item_Employee_ID='+Item_Employee_ID, true);
            myObjectFilter.send();
        }
    }
</script>

<script type="text/javascript">
    function Preview_Report(){
        var Patient_Name = document.getElementById("Search_Patient").value;
        var Patient_Number = document.getElementById("Patient_Number").value;
        var Sponsor_ID = document.getElementById("Sponsor_ID").value;
        var Hospital_Ward_ID = document.getElementById("Hospital_Ward_ID").value;
        var Transaction_Type = document.getElementById("Transaction_Type").value;
        var Item_Category_ID = document.getElementById("Item_Category_ID").value;
        document.getElementById("Patient_Number").value = '';
        var Start_Date  = document.getElementById("Start_Date").value;
        var End_Date  = document.getElementById("End_Date").value;
        if(Hospital_Ward_ID != null && Hospital_Ward_ID != '' && Start_Date != null && Start_Date != '' && End_Date != null && End_Date != ''){
            window.open('clearedbillspreview.php?Patient_Name=' + Patient_Name + '&Sponsor_ID=' + Sponsor_ID + '&Hospital_Ward_ID=' + Hospital_Ward_ID + '&Transaction_Type=' + Transaction_Type+'&Patient_Number='+Patient_Number+'&Start_Date='+Start_Date+'&End_Date='+End_Date+'&ClearedBillPreview=ClearedBillPreviewThisPage'+'&Item_Category_ID='+Item_Category_ID,'_blank');
        }else{
            $("#Select_Ward").dialog("open");
        }
    }
</script>

<script type="text/javascript">
    function Preview_Details(Registration_ID,Check_In_ID,Hospital_Ward_ID,Admision_ID,Transaction_Type){
        var Item_Category_ID = document.getElementById("Item_Category_ID").value;
        if (window.XMLHttpRequest) {
            myObjectDetails = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectDetails = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectDetails.overrideMimeType('text/xml');
        }
        myObjectDetails.onreadystatechange = function () {
            dataDetails = myObjectDetails.responseText;
            if (myObjectDetails.readyState == 4) {
                document.getElementById('Get_Patient_Details').innerHTML = dataDetails;
                $("#Get_Patient_Details").dialog("open");
            }
        }; //specify name of function that will handle server response........

        myObjectDetails.open('GET', 'Inpatient_Preview_Details.php?Registration_ID='+Registration_ID+'&Check_In_ID='+Check_In_ID+'&Hospital_Ward_ID='+Hospital_Ward_ID+'&Admision_ID='+Admision_ID+'&Transaction_Type='+Transaction_Type+'&Item_Category_ID='+Item_Category_ID, true);
        myObjectDetails.send();
    }
</script>

<link rel="stylesheet" href="css/select2.min.css" media="screen">
<script src="media/js/jquery.js" type="text/javascript"></script>
<script src="css/jquery-ui.js"></script>
<link rel="stylesheet" href="css/select2.min.css" media="screen">
<script src="js/select2.min.js"></script>

<script>
    $(document).ready(function () {
        $("#Get_Patient_Details").dialog({autoOpen: false, width: "90%", height: 630, title: 'INPATIENT BILLING DETAILS', modal: true});
        $("#Select_Ward").dialog({autoOpen: false, width: "50%", height: 130, title: 'eHMS 2.0', modal: true});
        $('select').select2();
    });
</script>

<?php
    include("./includes/footer.php");
?>