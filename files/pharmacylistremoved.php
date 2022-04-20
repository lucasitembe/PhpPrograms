<?php
include("./includes/header.php");
include("./includes/connection.php");
include("./button_configuration.php");
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Pharmacy'])) {
        if ($_SESSION['userinfo']['Pharmacy'] != 'yes') {
            header("Location: ./index.php?InvalidPrivilege=yes");
        }
    } else {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_GET['Registration_ID'])) {
    $Registration_ID = $_GET['Registration_ID'];
}

    //Get Employee_ID
    if(isset($_SESSION['userinfo']['Employee_ID'])){
        $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    }else{
        $Employee_ID = 0;
    }

    //get today's date
    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
        $age ='';
        $Date_Value = $Today.' 00:00';
    }

    //get menu additional information
    if(isset($_SESSION['systeminfo']['Allow_Aditional_Instructions_On_Pharmacy_Menu']) && 
        strtolower($_SESSION['systeminfo']['Allow_Aditional_Instructions_On_Pharmacy_Menu']) == 'yes' && 
        $_SESSION['systeminfo']['Pharmacy_Additional_Instruction'] != null &&
        $_SESSION['systeminfo']['Pharmacy_Additional_Instruction'] != ''){
        $Additional_Instruction = '('.strtoupper($_SESSION['systeminfo']['Pharmacy_Additional_Instruction']).')';
    }else{
        $Additional_Instruction = '';
    }

    //delete selected patients in multi dispense list if available
    if(isset($_SESSION['systeminfo']['Allow_Pharmacy_To_Dispense_Multiple_Patients']) && strtolower($_SESSION['systeminfo']['Allow_Pharmacy_To_Dispense_Multiple_Patients']) == 'yes'){
        mysqli_query($conn,"delete from tbl_multi_dispense_cache where Employee_ID = '$Employee_ID'") or die(mysqli_error($conn));
    }
?>
<?php 
    if(isset($_SESSION['systeminfo']['Filtered_Pharmacy_Patient_List']) && strtolower($_SESSION['systeminfo']['Filtered_Pharmacy_Patient_List']) == 'yes'){
        $Start_Date = $Date_Value;
        $End_Date = $original_Date;
    }else{
        $Start_Date = '';
        $End_Date = '';
    }
?>

<!--get sub department name-->
<?php
if (isset($_SESSION['Pharmacy_ID'])) {
    $Sub_Department_ID = $_SESSION['Pharmacy_ID'];
} else {
    $Sub_Department_ID = 0;
}

$select = mysqli_query($conn,"select Sub_Department_Name from tbl_sub_department where Sub_Department_ID = '$Sub_Department_ID'") or die(mysqli_error($conn));
$num = mysqli_num_rows($select);
if ($num > 0) {
    while ($data = mysqli_fetch_array($select)) {
        $Sub_Department_Name = $data['Sub_Department_Name'];
    }
} else {
    $Sub_Department_Name = '';
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
    if(isset($_GET['Billing_Type'])){
        $Billing_Type = $_GET['Billing_Type'];
        if($Billing_Type == 'OutpatientCash'){
            $Page_Title = ' - Outpatient Cash';
        }elseif($Billing_Type == 'OutpatientCredit'){
            $Page_Title = ' - Outpatient Credit';
        }elseif($Billing_Type == 'InpatientCash'){
            $Page_Title = ' - Inpatient Cash';
        }elseif($Billing_Type == 'InpatientCredit'){
            $Page_Title = ' - Inpatient Credit';
        }elseif($Billing_Type == 'PatientFromOutside'){
            $Page_Title = ' - Patient From Outside';
        }else{
            $Page_Title = '';
        }
    }else{
        $Billing_Type = '';
        $Page_Title = '';
    }
?>
   
   
<script type="text/javascript">
    function gotolink(){
	var patientlist = document.getElementById('patientlist').value;
	if(patientlist=='OUTPATIENT CASH'){
	    document.location = "pharmacylist.php?Billing_Type=OutpatientCash&PharmacyList=PharmacyListThisForm";
	}else if (patientlist=='OUTPATIENT CREDIT') {
	    document.location = "pharmacylist.php?Billing_Type=OutpatientCredit&PharmacyList=PharmacyListThisForm";
	}else if (patientlist=='INPATIENT CASH') {
	    document.location = "pharmacylist.php?Billing_Type=InpatientCash&PharmacyList=PharmacyListThisForm";
	}else if (patientlist=='INPATIENT CREDIT') {
	    document.location = "pharmacylist.php?Billing_Type=InpatientCredit&PharmacyList=PharmacyListThisForm";
	}else if (patientlist=='PATIENTS LIST') {
	    //document.location = "pharmacylist.php?Billing_Type=PatientFromOutside&PharmacyList=PharmacyListThisForm";
	    document.location = "pharmacypatientlist.php?PharmacyPatientsList=PharmacyPatientsListThisForm";
	}else if(patientlist == 'DISPENSED LIST'){
	    document.location = "dispensedlist.php?Billing_Type=DispensedList&PharmacyList=PharmacyListThisForm";
	}else{
	    alert("Choose Type Of Patients To View");
	}
    }
</script>

<label style='border: 1px ;padding: 8px;margin-right: 7px;background: #2A89AF' class='btn-default'>
    <select id='patientlist' name='patientlist' onchange='gotolink()'>
        <option>Select From List</option>
        <?php if (getButtonStatus("outpatient_cash_opt") == "visible") { ?>
            <option value="OUTPATIENT CASH">
                OUTPATIENT CASH <?php echo $Additional_Instruction; ?>
            </option>
        <?php } ?>
        <?php if (getButtonStatus("outpatient_credit_opt") == "visible") { ?>         
            <option value="OUTPATIENT CREDIT">
                OUTPATIENT CREDIT <?php echo $Additional_Instruction; ?>
            </option>
        <?php } ?>   
        <?php if (getButtonStatus("inpatient_cash_opt") == "visible") { ?>     
            <option value="INPATIENT CASH">
                INPATIENT CASH <?php echo $Additional_Instruction; ?>
            </option>
        <?php } ?>   
        <?php if (getButtonStatus("inpatient_credit_opt") == "visible") { ?>       
            <option value="INPATIENT CREDIT">
                INPATIENT CREDIT <?php echo $Additional_Instruction; ?>
            </option>
        <?php } ?>      
        <?php //if(isset($_SESSION['systeminfo']['Direct_departmental_payments']) && strtolower($_SESSION['systeminfo']['Direct_departmental_payments']) == 'yes'){  ?>
        <?php if (getButtonStatus("patient_lists_op") == "visible") { ?>      
            <option value="PATIENTS LIST">
                COSTUMER LIST
            </option>
        <?php } ?>
        <?php //}  ?>
        <?php if (getButtonStatus("dispensed_lists_op") == "visible") { ?>    
            <option>
                DISPENSED LIST
            </option>
        <?php } ?>   
    </select>
    <!--
    <input type='button' value='VIEW' onclick='gotolink()'>
    -->
</label>
<?php
    if(isset($_SESSION['systeminfo']['Allow_Pharmacy_To_Dispense_Multiple_Patients']) && strtolower($_SESSION['systeminfo']['Allow_Pharmacy_To_Dispense_Multiple_Patients']) == 'yes'){
?>
        <input type="button" name="Process_Button" id="Process_Button" class="art-button-green" value="PROCESS SELECTED PATIENTS" onclick="Preview_Selected_Transactions()">
<?php
    }
?>
<a href='pharmacyworkspage.php?section=Pharmacy&PharmacyWorks=PharmacyWorksThisPage' class='art-button-green'> BACK</a>


<script language="javascript" type="text/javascript">
    function searchPatient(){
        var Patient_Name = document.getElementById("Search_Patient").value;
        var Billing_Type = '<?php echo $Billing_Type; ?>';
        var date_From = document.getElementById("date_From").value;
        var date_To = document.getElementById("date_To").value;
       // alert(Billing_Type)
        if(date_From != null && date_From != '' && date_To != null && date_To != ''){
            document.getElementById('Search_Iframe').innerHTML = "<iframe width='100%' height=380px src='Pharmacy_List_removed_Iframe.php?Patient_Name="+Patient_Name+"&Billing_Type="+Billing_Type+"&date_From="+date_From+"&date_To="+date_To+"'></iframe>";
        }else{
            document.getElementById('Search_Iframe').innerHTML = "<iframe width='100%' height=380px src='Pharmacy_List_removed_Iframe.php?Patient_Name="+Patient_Name+"&Billing_Type="+Billing_Type+"'></iframe>";
        }
    }
</script>

<script language="javascript" type="text/javascript">
    function Patient_List_Search2(){
        var Billing_Type = '<?php echo $Billing_Type; ?>';
        var date_From = document.getElementById("date_From").value;
        var date_To = document.getElementById("date_To").value;
        var Patient_Number = document.getElementById("Patient_Number").value;
        if(date_From != null && date_From != '' && date_To != null && date_To != ''){
            document.getElementById('Search_Iframe').innerHTML = "<iframe width='100%' height=380px src='Pharmacy_List_removed_Iframe.php?Billing_Type="+Billing_Type+"&date_From="+date_From+"&date_To="+date_To+"&Patient_Number="+Patient_Number+"'></iframe>";
        }else{
            document.getElementById('Search_Iframe').innerHTML = "<iframe width='100%' height=380px src='Pharmacy_List_removed_Iframe.php?Billing_Type="+Billing_Type+"&Patient_Number="+Patient_Number+"'></iframe>";
        }
    }
</script>

<br/><br/>
<center>
    <table width=90%>
        <tr>
            <td>
                <input type='text' name='Search_Patient' id='Search_Patient' oninput='searchPatient()' onkeyup='searchPatient()'  placeholder='~~~~~ Search Patient Name ~~~~~' style="text-align: center;">
            </td>
            <td width="20%">
                <input type="text" name="Patient_Number" id="Patient_Number" style="text-align: center;" oninput='Patient_List_Search2()' onkeyup='Patient_List_Search2()' placeholder='~~~~~ Enter Patient Number ~~~~~'>
            </td>
            <td style="text-align: right;" width="7%">Start Date</td>
            <td width="14%">
                <input type="text" name="date_From" id="date_From" placeholder='~~~ Start Date ~~~' autocomplete='off' style="text-align: center;" value="<?php echo $Start_Date; ?>" readonly="readonly">
            </td>
            <td style="text-align: right;" width="7%">End Date</td>
            <td width="14%">
                <input type="text" name="date_To" id="date_To" placeholder='~~~ End Date ~~~' autocomplete='off' style="text-align: center;" value="<?php echo $End_Date; ?>" readonly="readonly">
            </td>
            <td width="9%" style="text-align: right;">
                <input type="button" name="Filter" id="Filter" value="FILTER" class="art-button-green" onclick="searchPatient()">
            </td>
        </tr>
    </table>
</center>

<script src="css/jquery.js"></script>
<script src="css/jquery.datetimepicker.js"></script>
<script>
    $('#date_From').datetimepicker({
        dayOfWeekStart : 1,
        lang:'en',
        startDate:  'now'
    });
    $('#date_From').datetimepicker({value:'',step:5});
    $('#date_To').datetimepicker({
        dayOfWeekStart : 1,
        lang:'en',
        startDate:'now'
    });
    $('#date_To').datetimepicker({value:'',step:5});
</script>

<script type="text/javascript">
    function Preview_Selected_Transactions(){
        if (window.XMLHttpRequest) {
            myObjectPreviewSelected = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectPreviewSelected = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectPreviewSelected.overrideMimeType('text/xml');
        }
        myObjectPreviewSelected.onreadystatechange = function () {
            DataPreview = myObjectPreviewSelected.responseText;
            if (myObjectPreviewSelected.readyState == 4) {
                document.getElementById("Selected_Transactions_Details").innerHTML = DataPreview;
                $("#Selected_Transactions_Details").dialog("open");
            }
        }; //specify name of function that will handle server response........
        myObjectPreviewSelected.open('GET', 'Multi_Dispense_Preview_Selected_Transactions.php', true);
        myObjectPreviewSelected.send();
    }
</script>

<script type="text/javascript">
    function Cancel_Dispense(){
        $("#Cancel_Dispense_Dialog").dialog("open");
    }
</script>

<script type="text/javascript">
    function Close_Cancel_DIspense_Dialog(){
        $("#Cancel_Dispense_Dialog").dialog("close");
    }
</script>

<script type="text/javascript">
    function Confirm_Dispensing_Process(){
        $("#Confirm_Dispensing_Process_Dialog").dialog("open");
    }
</script>

<script type="text/javascript">
    function Close_Confirm_Dispensing_Process_Dialog(){
        $("#Confirm_Dispensing_Process_Dialog").dialog("close");
    }
</script>


<script type="text/javascript">
    function Dispense(){
        var Billing_Type = '<?php echo $Billing_Type; ?>';
        if (window.XMLHttpRequest) {
            myObjectDispense = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectDispense = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectDispense.overrideMimeType('text/xml');
        }
        myObjectDispense.onreadystatechange = function () {
            DataDis = myObjectDispense.responseText;
            if (myObjectDispense.readyState == 4) {
                //var feedback = DataDis;
                alert("dispensed successfully");
                $("#Selected_Transactions_Details").dialog("close");
                $("#Confirm_Dispensing_Process_Dialog").dialog("close");
                searchPatient()
                //window.open("pharmacylist.php?Billing_Type="+Billing_Type+"&PharmacyList=PharmacyListThisForm","_parent");
            }
        }; //specify name of function that will handle server response........
        myObjectDispense.open('GET', 'Multi_Dispense_Process_Selected_Transactions.php', true);
        myObjectDispense.send();
    }
</script>

<script type="text/javascript">
    function Remove_Transaction_Confrim(Dispense_Cache_ID){
        document.getElementById("Rem_Area").innerHTML = '<input type="button" value="REMOVE" class="art-button-green" onclick="Remove_Transaction('+Dispense_Cache_ID+')"><input type="button" value="CANCEL REMOVE" class="art-button-green" onclick="Close_Remove_Transaction_Dialog()">';
        $("#Remove_Transaction_Dialog").dialog("open");
    }
</script>

<script type="text/javascript">
    function Close_Remove_Transaction_Dialog(){
        $("#Remove_Transaction_Dialog").dialog("close");
    }
</script>
<script type="text/javascript">
    function Remove_Transaction(Dispense_Cache_ID){
        if (window.XMLHttpRequest) {
            myObjectRemove = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectRemove = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectRemove.overrideMimeType('text/xml');
        }
        myObjectRemove.onreadystatechange = function () {
            DataRem = myObjectRemove.responseText;
            if (myObjectRemove.readyState == 4) {
                searchPatient();
                $("#Remove_Transaction_Dialog").dialog("close");
                Preview_Selected_Transactions();
            }
        }; //specify name of function that will handle server response........
        myObjectRemove.open('GET', 'Multi_Dispense_Remove_Transaction.php?Dispense_Cache_ID='+Dispense_Cache_ID, true);
        myObjectRemove.send();
    }
</script>

<script type="text/javascript">
    function Cancel_Process(){
        $("#Selected_Transactions_Details").dialog("close");
        $("#Cancel_Dispense_Dialog").dialog("close");
    }
</script>

<!--End datetimepicker-->
<fieldset>  
    <legend align='right'><b>PHARMACY WORKS <?php echo strtoupper($Page_Title).'  :'.strtoupper($Sub_Department_Name); ?> </b></legend>
        <center>
            <table width=100% border=1>
                <tr>
            <td id='Search_Iframe'>
            <?php if(isset($_SESSION['systeminfo']['Filtered_Pharmacy_Patient_List']) && strtolower($_SESSION['systeminfo']['Filtered_Pharmacy_Patient_List']) == 'yes'){ ?>
                <iframe width='100%' height=380px src='Pharmacy_List_Iframe.php?Billing_Type=<?php echo $Billing_Type; ?>&date_From=<?php echo $Date_Value; ?>&date_To=<?php echo $original_Date; ?>'></iframe>
            <?php }else{ ?>
                <iframe width='100%' height=380px src='Pharmacy_List_removed_Iframe.php?Billing_Type=<?php echo $Billing_Type; ?>'></iframe>
            <?php } ?>
            </td>
        </tr>
            </table>
        </center>
</fieldset><br/>
<div id="Selected_Transactions_Details">
    
</div>

<div id="Cancel_Dispense_Dialog">
    <b>Are you sure you want to cancel dispensing process?</b><br/><br/>
    <table width="100%">
        <tr>
            <td style="text-align: right;">
                <input type="button" value="YES" class="art-button-green" onclick="Cancel_Process()">
                <input type="button" value="NO" class="art-button-green" onclick="Close_Cancel_DIspense_Dialog()">
            </td>
        </tr>
    </table>
</div>

<div id="Confirm_Dispensing_Process_Dialog">
    Are you sure you want to dispense selected transactions?<br/><br/>
    <table width="100%">
        <tr>
            <td style="text-align: right;">
                <input type="button" value="YES" class="art-button-green" onclick="Dispense()">
                <input type="button" value="NO" class="art-button-green" onclick="Close_Confirm_Dispensing_Process_Dialog()">
            </td>
        </tr>
    </table>
</div>

<div id="Remove_Transaction_Dialog">
    Are you sure you want to remove selected transaction?<br/><br/>
    <table width="100%">
        <tr><td style="text-align: right;" id="Rem_Area"></td></tr>
    </table>
</div>

<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>
<script src="script.js"></script>
<script src="script.responsive.js"></script>
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">

<script>
   $(document).ready(function(){
      $("#Selected_Transactions_Details").dialog({ autoOpen: false, width:'90%',height:550, title:'SELECTED TRANSACTIONS DETAILS',modal: true});
      $("#Cancel_Dispense_Dialog").dialog({ autoOpen: false, width:'40%',height:140, title:'CANCEL PROCESS',modal: true});
      $("#Confirm_Dispensing_Process_Dialog").dialog({ autoOpen: false, width:'40%',height:140, title:'CONFIRM PROCESS',modal: true});
      $("#Remove_Transaction_Dialog").dialog({ autoOpen: false, width:'40%',height:140, title:'CONFIRM REMOVE',modal: true});
   });
</script>

<?php
    include("./includes/footer.php");
?>