<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    $temp = 1;
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Revenue_Center_Works'])){
	    if($_SESSION['userinfo']['Revenue_Center_Works'] != 'yes'){
		header("Location: ./index.php?InvalidPrivilege=yes");
	    }
	}else{
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	    header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_GET['Registration_ID'])){
        $Registration_ID = $_GET['Registration_ID'];
    }

    //get today's date
    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $End_Date = $row['today'];
        $Start_Date = date("Y-m-d", strtotime($End_Date)).' 00:00';
        $Today = date("Y-m-d", strtotime($End_Date));
    }

    if(isset($_GET['Billing_Type'])){
        $Billing_Type2 = $_GET['Billing_Type'];
    }else{
        $Billing_Type2 = 'OutpatientCash';
    }

    //generate filter value
    if(isset($_GET['Billing_Type'])){
        if(strtolower($_GET['Billing_Type']) == 'outpatientcash'){
            $B_Type = 'Outpatient Cash';
        }else if(strtolower($_GET['Billing_Type']) == 'inpatientcash'){
            $B_Type = 'Inpatient Cash';
        }else{
            $B_Type = 'Outpatient Cash';
        }
    }else{
        $B_Type = 'Outpatient Cash';
    }
?>
<style>
        table,tr,td{
        border-collapse:collapse !important;
        border:none !important;
        
        }
    tr:hover{
    background-color:#eeeeee;
    cursor:pointer;
    }
</style> 
<?php
    if(isset($_GET['Billing_Type'])){
        $Billing_Type = $_GET['Billing_Type'];
        if($Billing_Type == 'OutpatientCash'){
            $Page_Title = 'Outpatient Cash';
        }elseif($Billing_Type == 'OutpatientCredit'){
            $Page_Title = 'Outpatient Credit';
        }elseif($Billing_Type == 'InpatientCash'){
            $Page_Title = 'Inpatient Cash';
        }elseif($Billing_Type == 'InpatientCredit'){
            $Page_Title = 'Inpatient Credit';
        }elseif($Billing_Type == 'PatientFromOutside'){
            $Page_Title = 'Patient From Outside';
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
	    document.location = "revenuecenterradiologylist.php?Billing_Type=OutpatientCash&RadiologyList=RadiologyListThisForm";
	}else if (patientlist=='OUTPATIENT CREDIT') {
	    document.location = "revenuecenterradiologylist.php?Billing_Type=OutpatientCredit&RadiologyList=RadiologyListThisForm";
	}else if (patientlist=='INPATIENT CASH') {
	    document.location = "revenuecenterradiologylist.php?Billing_Type=InpatientCash&RadiologyList=RadiologyListThisForm";
	}else if (patientlist=='INPATIENT CREDIT') {
	    document.location = "revenuecenterradiologylist.php?Billing_Type=InpatientCredit&RadiologyList=RadiologyListThisForm";
	}else if (patientlist=='PATIENT FROM OUTSIDE') {
	    document.location = "revenuecenterradiologylist.php?Billing_Type=PatientFromOutside&RadiologyList=RadiologyListThisForm";
	}else{
	    alert("Choose Type Of Patients To View");
	}
    }
</script>

<label style='border: 1px ;padding: 8px;margin-right: 7px;' class='art-button-green'>
<select id='patientlist' name='patientlist'>
    <!-- <option></option> -->
    <option>
	OUTPATIENT CASH
    </option>
<!--    <option>-->
<!--	OUTPATIENT CREDIT-->
<!--    </option>-->
<!--    <option>
	INPATIENT CASH
    </option>-->
<!--    <option>-->
<!--	INPATIENT CREDIT-->
<!--    </option>-->
<!--     <option>
	PATIENT FROM OUTSIDE
    </option> -->
</select>
<input type='button' value='VIEW' onclick='gotolink()'>
</label> 


<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Revenue_Center_Works'] == 'yes'){ 
?>
<a href="revenuecenterradiologyremovedlist.php" class="art-button-green">REMOVED ITEMS</a>
    <a href='./departmentpatientbillingpage.php?DepartmentPatientBilling=DepartmentPatientBillingThisPage' class='art-button-green'>
        BACK
    </a>
<?php  } } ?>
 

<script language="javascript" type="text/javascript">
    function searchPatient(Patient_Name){
	var Registration_Number = document.getElementById('Registration_Number').value;
        document.getElementById('Search_Iframe').innerHTML = "<iframe width='100%' height=370px src='Radiology_List_Iframe.php?Patient_Name="+Patient_Name+"&Registration_Number="+Registration_Number+"'></iframe>";
    }
</script>

<br/><br/>
<center>
    <table width="100%">
        <tr>
            <td width="7%" style="text-align: right;"><b>Start Date</b></td>
            <td width="10%">
                <input type="text" name="date_From" id="date_From" value="<?php echo $Start_Date; ?>" readonly="readonly" style="text-align: center;">
            </td>
            <td width="7%" style="text-align: right;"><b>End Date</b></td>
            <td width="10%">
                <input type="text" name="date_To" id="date_To" value="<?php echo $End_Date; ?>" readonly="readonly" style="text-align: center;">
            </td>
            <td width="7%" style="text-align: right;"><b>Patients Type</b></td>
            <td width="7%">
                <select name="Patient_Type" id="Patient_Type" onchange="Patient_List_Search();">
                    <option selected="selected" value="Outpatient">Outpatient Cash</option>
                    <option value="Inpatient" <?php if(strtolower($B_Type) == 'inpatient cash'){ echo "selected='selected'"; } ?>>Inpatient Cash</option>
                </select>
            </td>
            <td width="20%">
                <input type='text' name='Search_Patient' id='Search_Patient' style="text-align: center;" onkeypress='Patient_List_Search()' oninput='Patient_List_Search()' placeholder='~~~ ~~~ Enter Patient Name ~~~ ~~~'>
            </td>
            <td width="20%">
                <input type="text" name="Patient_Number" id="Patient_Number" autocomplete="off" onkeypress="Patient_List_Search_Via_Number()" oninput="Patient_List_Search_Via_Number()" placeholder="~~~ ~~~ Enter Patient Number ~~~ ~~~" style="text-align: center;" >
            </td>
            <td width="7%">
                <input type="button" name="Filter" id="Filter" class="art-button-green" value="FILTER" onclick="Patient_List_Search()">
            </td>
        </tr>        
    </table>
</center>

<fieldset style='overflow-y: scroll; height: 400px; background-color:white' id='Patients_Fieldset_List'>
    <legend style="background-color:#006400;color:white" align="right">
        <b>
            <?php 
                if(strtolower($B_Type) == 'inpatient cash'){
                    echo strtoupper('Radiology Payments ~ inpatient cash'); 
                }else{
                    echo strtoupper('Radiology Payments ~ outpatient cash'); 
                }
            ?> 
        </b>
    </legend>
<?php
        echo '<center><table width =100% border=0>';
        echo "<tr><td colspan='10'><hr></tr>";
        echo '<tr id="thead" style="width:5%;"><td><b>SN</b></td>
            <td width><b>STATUS</b></td>
            <td><b>PATIENT NAME</b></td>
            <td><b>PATIENT NUMBER</b></td>
            <td><b>SPONSOR</b></td>
            <td><b>AGE</b></td>
            <td><b>GENDER</b></td>
            <td><b>SUB DEPARTMENT</b></td>
            <td><b>TRANSACTION DATE</b></td>
            <td><b>MEMBER NUMBER</b></td>
        </tr>';
        echo "<tr><td colspan='10'><hr></tr>";
        $qr="select pc.Registration_ID, pc.Transaction_Status as General_Transaction_Status, pc.Payment_Cache_ID, ilc.Transaction_Date_And_Time,
            preg.Patient_Name, pc.Sponsor_Name, preg.Date_Of_Birth, preg.Gender, preg.Phone_Number,
            preg.Member_Number, ilc.Transaction_Type, ilc.status, sd.Sub_Department_Name,ilc.Sub_Department_ID from
            tbl_payment_cache pc, tbl_item_list_cache ilc, tbl_patient_registration preg, tbl_sub_department sd where
            pc.payment_cache_id = ilc.payment_cache_id and
            preg.registration_id = pc.registration_id and
            sd.sub_department_id = ilc.sub_department_id and
            ilc.status = 'active' and
            ilc.Check_In_Type = 'Radiology' and
            ilc.Transaction_Date_And_Time between '$Start_Date' and '$End_Date' and
            pc.billing_type = '$B_Type'
            group by pc.payment_cache_id,ilc.sub_department_id order by pc.payment_cache_id desc limit 200";

        

        $select_Filtered_Patients = mysqli_query($conn,$qr) or die(mysqli_error($conn));
        while($row = mysqli_fetch_array($select_Filtered_Patients)){
        $Sub_Department_ID = $row['Sub_Department_ID'];
        echo "<tr><td id='thead' style='width:5%;' >".$temp."</td>";
        if(strtolower($row['status']) == 'active'){
            echo "<td><b>Not Paid</b></td>";
        }else{
            echo "<td> <b>Not Approved</b></td>";  
        }
    
        //GENERATE PATIENT YEARS, MONTHS AND DAYS
        $age = floor( (strtotime(date('Y-m-d')) - strtotime($row['Date_Of_Birth'])) / 31556926)." Years";       
        $date1 = new DateTime($Today);
        $date2 = new DateTime($row['Date_Of_Birth']);
        $diff = $date1 -> diff($date2);
        $age = $diff->y." Years, ";
        $age .= $diff->m." Months, ";
        $age .= $diff->d." Days";
    
    
        echo "<td><a href='patientbillingradiology.php?section=Radiology&Registration_ID=".$row['Registration_ID']."&Transaction_Type=".$row['Transaction_Type']."&Payment_Cache_ID=".$row['Payment_Cache_ID']."&NR=True&Billing_Type=".$Billing_Type2."&Sub_Department_ID=".$Sub_Department_ID."&RadiologyWorks=RadiologyWorksThisPage' target='_parent' style='text-decoration: none;'>".ucwords(strtolower($row['Patient_Name']))."</a></td>";
        echo "<td><a href='patientbillingradiology.php?section=Radiology&Registration_ID=".$row['Registration_ID']."&Transaction_Type=".$row['Transaction_Type']."&Payment_Cache_ID=".$row['Payment_Cache_ID']."&NR=True&Billing_Type=".$Billing_Type2."&Sub_Department_ID=".$Sub_Department_ID."&RadiologyWorks=RadiologyWorksThisPage' target='_parent' style='text-decoration: none;'>".$row['Registration_ID']."</a></td>";
        echo "<td><a href='patientbillingradiology.php?section=Radiology&Registration_ID=".$row['Registration_ID']."&Transaction_Type=".$row['Transaction_Type']."&Payment_Cache_ID=".$row['Payment_Cache_ID']."&NR=True&Billing_Type=".$Billing_Type2."&Sub_Department_ID=".$Sub_Department_ID."&RadiologyWorks=RadiologyWorksThisPage' target='_parent' style='text-decoration: none;'>".$row['Sponsor_Name']."</a></td>";
        echo "<td><a href='patientbillingradiology.php?section=Radiology&Registration_ID=".$row['Registration_ID']."&Transaction_Type=".$row['Transaction_Type']."&Payment_Cache_ID=".$row['Payment_Cache_ID']."&NR=True&Billing_Type=".$Billing_Type2."&Sub_Department_ID=".$Sub_Department_ID."&RadiologyWorks=RadiologyWorksThisPage' target='_parent' style='text-decoration: none;'>".$age."</a></td>";
        echo "<td><a href='patientbillingradiology.php?section=Radiology&Registration_ID=".$row['Registration_ID']."&Transaction_Type=".$row['Transaction_Type']."&Payment_Cache_ID=".$row['Payment_Cache_ID']."&NR=True&Billing_Type=".$Billing_Type2."&Sub_Department_ID=".$Sub_Department_ID."&RadiologyWorks=RadiologyWorksThisPage' target='_parent' style='text-decoration: none;'>".$row['Gender']."</a></td>";
        echo "<td><a href='patientbillingradiology.php?section=Radiology&Registration_ID=".$row['Registration_ID']."&Transaction_Type=".$row['Transaction_Type']."&Payment_Cache_ID=".$row['Payment_Cache_ID']."&NR=True&Billing_Type=".$Billing_Type2."&Sub_Department_ID=".$Sub_Department_ID."&RadiologyWorks=RadiologyWorksThisPage' target='_parent' style='text-decoration: none;'>".$row['Sub_Department_Name']."</a></td>";
        echo "<td><a href='patientbillingradiology.php?section=Radiology&Registration_ID=".$row['Registration_ID']."&Transaction_Type=".$row['Transaction_Type']."&Payment_Cache_ID=".$row['Payment_Cache_ID']."&NR=True&Billing_Type=".$Billing_Type2."&Sub_Department_ID=".$Sub_Department_ID."&RadiologyWorks=RadiologyWorksThisPage' target='_parent' style='text-decoration: none;'>".$row['Transaction_Date_And_Time']."</a></td>";
        echo "<td><a href='patientbillingradiology.php?section=Radiology&Registration_ID=".$row['Registration_ID']."&Transaction_Type=".$row['Transaction_Type']."&Payment_Cache_ID=".$row['Payment_Cache_ID']."&NR=True&Billing_Type=".$Billing_Type2."&Sub_Department_ID=".$Sub_Department_ID."&RadiologyWorks=RadiologyWorksThisPage' target='_parent' style='text-decoration: none;'>".$row['Member_Number']."</a></td>";
        echo "</tr>"; 
        $temp++;
    }
echo "</table>";
?>
</fieldset>

<br/>
<script>
    function Patient_List_Search(){
        var Billing_Type = '<?php echo $Billing_Type2; ?>';
        var Patient_Name = document.getElementById("Search_Patient").value;
        var Billing_Type = '<?php echo $Billing_Type2; ?>';
        var Patient_Type = document.getElementById("Patient_Type").value;
        var Start_Date = document.getElementById("date_From").value;
        var End_Date = document.getElementById("date_To").value;
        document.getElementById('Patients_Fieldset_List').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';
        if(window.XMLHttpRequest){
            myObjectSearchPatient = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObjectSearchPatient = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectSearchPatient.overrideMimeType('text/xml');
        }
        myObjectSearchPatient.onreadystatechange = function (){
            data28 = myObjectSearchPatient.responseText;
            if (myObjectSearchPatient.readyState == 4) {
                document.getElementById('Patients_Fieldset_List').innerHTML = data28;
            }
        }; //specify name of function that will handle server response........
        
        myObjectSearchPatient.open('GET','Revenue_Center_Radiology_List_Iframe.php?Patient_Name='+Patient_Name+'&Billing_Type='+Billing_Type+'&Patient_Type='+Patient_Type+'&Start_Date='+Start_Date+'&End_Date='+End_Date,true);
        myObjectSearchPatient.send();   
    }
</script>

<script type="text/javascript">
    function Patient_List_Search_Via_Number(){
        var Billing_Type = '<?php echo $Billing_Type2; ?>';
        var Patient_Number = document.getElementById("Patient_Number").value;
        var Billing_Type = '<?php echo $Billing_Type2; ?>';
        var Patient_Type = document.getElementById("Patient_Type").value;
        var Start_Date = document.getElementById("date_From").value;
        var End_Date = document.getElementById("date_To").value;
        document.getElementById('Patients_Fieldset_List').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';
        if(window.XMLHttpRequest){
            myObjectSearchPatient = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObjectSearchPatient = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectSearchPatient.overrideMimeType('text/xml');
        }
        myObjectSearchPatient.onreadystatechange = function (){
            data30 = myObjectSearchPatient.responseText;
            if (myObjectSearchPatient.readyState == 4) {
                document.getElementById('Patients_Fieldset_List').innerHTML = data30;
            }
        }; //specify name of function that will handle server response........
        
        myObjectSearchPatient.open('GET','Revenue_Center_Radiology_List_Iframe.php?Patient_Number='+Patient_Number+'&Billing_Type='+Billing_Type+'&Patient_Type='+Patient_Type+'&Start_Date='+Start_Date+'&End_Date='+End_Date,true);
        myObjectSearchPatient.send();   
    }
</script>


<script src="css/jquery.js"></script>
<script src="css/jquery.datetimepicker.js"></script>
<script>
$('#date_From').datetimepicker({
dayOfWeekStart : 1,
lang:'en',
startDate:  'now'
});
$('#date_From').datetimepicker({value:'',step:30});
$('#date_To').datetimepicker({
dayOfWeekStart : 1,
lang:'en',
startDate:'now'
});
$('#date_To').datetimepicker({value:'',step:30});
</script>
<!--End datetimepicker-->
<?php
    include("./includes/footer.php");
?>