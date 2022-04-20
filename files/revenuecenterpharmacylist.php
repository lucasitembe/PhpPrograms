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
        }else{
        @session_start();
        if(!isset($_SESSION['supervisor'])){ 
            header("Location: ./supervisorauthentication.php?InvalidSupervisorAuthentication=yes");
        }
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
	    document.location = "revenuecenterpharmacylist.php?Billing_Type=OutpatientCash&PharmacyList=PharmacyListThisForm";
	}else if (patientlist=='OUTPATIENT CREDIT') {
	    document.location = "revenuecenterpharmacylist.php?Billing_Type=OutpatientCredit&PharmacyList=PharmacyListThisForm";
	}else if (patientlist=='INPATIENT CASH') {
	    document.location = "revenuecenterpharmacylist.php?Billing_Type=InpatientCash&PharmacyList=PharmacyListThisForm";
	}else if (patientlist=='INPATIENT CREDIT') {
	    document.location = "revenuecenterpharmacylist.php?Billing_Type=InpatientCredit&PharmacyList=PharmacyListThisForm";
	}else if (patientlist=='PATIENT FROM OUTSIDE') {
	    document.location = "revenuecenterpharmacylist.php?Billing_Type=PatientFromOutside&PharmacyList=PharmacyListThisForm";
	}else{
	    alert("Choose Type Of Patients To View");
	}
    }
</script>

  <!--<label style='border: 1px ;padding: 8px;margin-right: 7px;' class='art-button-green'>
<select id='patientlist' name='patientlist'>
   <option></option> 
    <option>
	OUTPATIENT CASH
    </option>
   <option>-->
<!--	OUTPATIENT CREDIT-->
<!--    </option>-->
<!--    <option>
	INPATIENT CASH
    </option>-->
<!--    <option>-->
<!--	INPATIENT CREDIT-->
<!--    </option>-->
 <!--    <option>
	PATIENT FROM OUTSIDE
    </option> 
</select>
<input type='button' value='VIEW' onclick='gotolink()'>
</label> -->

<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Revenue_Center_Works'] == 'yes'){ 
?>
    <a href='./departmentpatientbillingpage.php?DepartmentPatientBilling=DepartmentPatientBillingThisPage' class='art-button-green'>
        BACK
    </a>
<?php  } } ?>

<?php
    if(isset($_GET['Billing_Type'])){
        $Billing_Type2 = $_GET['Billing_Type'];
    }else{
        $Billing_Type2 = '';
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
?>

<script language="javascript" type="text/javascript">
    function searchPatient(Patient_Name){
        document.getElementById('Search_Iframe').innerHTML = "<iframe width='100%' height=380px src='Revenue_Center_Pharmacy_List_Iframe.php?Patient_Name="+Patient_Name+"'></iframe>";
    }
</script>

<br/><br/>
<center>
    <table width="100%">
        <tr>
            <td>
                <input type='text' name='Search_Patient' id='Search_Patient' style="text-align: center;" oninput='Patient_List_Search()' onkeyup='Patient_List_Search()' placeholder='~~~~~ Enter Patient Name ~~~~~'>
            </td>
            <td width="20%" style="text-align: right;">
                <input type="text" name="Patient_Number" id="Patient_Number" style="text-align: center;" oninput='Patient_List_Search2()' onkeyup='Patient_List_Search2()' placeholder='~~~~~ Enter Patient Number ~~~~~'>
            </td>
            <td width="15%">
                <select name="Patient_Type" id="Patient_Type" class="form-control">
                    <option selected="selected" value="Outpatient">Outpatient Cash</option>
        <?php if(strtolower($_SESSION['systeminfo']['Inpatient_Prepaid']) =='yes'){ ?>
                    <option value="Inpatient">Inpatient Cash</option>
        <?php } ?>
                </select>
            </td>
            <td style="text-align: right;" width="7%">Start Date</td>
            <td width="14%">
                <input type="text" name="date_From" id="date_From" placeholder='~~~ Start Date ~~~' autocomplete='off' style="text-align: center;" value="<?php echo $Date_Value; ?>">
            </td>
            <td style="text-align: right;" width="7%">End Date</td>
            <td width="14%">
                <input type="text" name="date_To" id="date_To" placeholder='~~~ End Date ~~~' autocomplete='off' style="text-align: center;" value="<?php echo $original_Date; ?>">
            </td>
            <td width="9%" style="text-align: right;">
                <input type="button" name="Filter" id="Filter" value="FILTER" class="art-button-green" onclick="Patient_List_Search()">
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
$('#date_From').datetimepicker({value:'',step:30});
$('#date_To').datetimepicker({
dayOfWeekStart : 1,
lang:'en',
startDate:'now'
});
$('#date_To').datetimepicker({value:'',step:30});
</script>
<!--End datetimepicker-->
<br/>

<fieldset style='overflow-y: scroll; height: 400px; background-color:white' id='Patients_Fieldset_List'>
    <legend style="background-color:#006400;color:white" align="right"><b><?php echo strtoupper('Pharmacy Payments ~ outpatient cash'); ?> </b></legend>
<?php
    if(strtolower($_SESSION['systeminfo']['Show_Pharmaceutical_Before_Payments']) == 'yes'){
        echo '<center><table width =100% border=0>';
        echo "<tr><td colspan='10'><hr></tr>";
        echo '<tr id="thead"><td style="width:5%;"><b>SN</b></td>
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
        $qr="select pc.Registration_ID, pc.Transaction_Status as General_Transaction_Status, pc.Payment_Cache_ID,ilc.Transaction_Date_And_Time,
            preg.Patient_Name, pc.Sponsor_Name, preg.Date_Of_Birth, preg.Gender, preg.Phone_Number,
            preg.Member_Number, ilc.Transaction_Type, ilc.status, sd.Sub_Department_Name,ilc.Sub_Department_ID from
            tbl_payment_cache pc, tbl_item_list_cache ilc, tbl_patient_registration preg, tbl_sub_department sd where
            pc.payment_cache_id = ilc.payment_cache_id and
            preg.registration_id = pc.registration_id and
            sd.sub_department_id = ilc.sub_department_id and
            ilc.status = 'approved' and
            ilc.Check_In_Type='Pharmacy' and 
            pc.billing_type = 'Outpatient Cash' and
            ilc.Transaction_Date_And_Time between '$Date_Value' and '$original_Date'
            group by pc.payment_cache_id,ilc.sub_department_id order by pc.Payment_Cache_ID desc limit 200";

        $select_Filtered_Patients = mysqli_query($conn,$qr) or die(mysqli_error($conn));
        while($row = mysqli_fetch_array($select_Filtered_Patients)){
            $Sub_Department_ID = $row['Sub_Department_ID'];
        
        
            //GENERATE PATIENT YEARS, MONTHS AND DAYS
            $age = floor( (strtotime(date('Y-m-d')) - strtotime($row['Date_Of_Birth'])) / 31556926)." Years";       
            $date1 = new DateTime($Today);
            $date2 = new DateTime($row['Date_Of_Birth']);
            $diff = $date1 -> diff($date2);
            $age = $diff->y." Years, ";
            $age .= $diff->m." Months, ";
            $age .= $diff->d." Days";
            
            
            echo "<tr><td id='thead'>".$temp."</td><td><b>Approved</b></td><td><a href='patientbillingpharmacy.php?section=Pharmacy&Registration_ID=".$row['Registration_ID']."&Transaction_Type=".$row['Transaction_Type']."&Payment_Cache_ID=".$row['Payment_Cache_ID']."&NR=True&Billing_Type=".$Billing_Type2."&Sub_Department_ID=".$Sub_Department_ID."&PharmacyWorks=PharmacyWorksThisPage' target='_parent' style='text-decoration: none;'>".ucwords(strtolower($row['Patient_Name']))."</a></td>";
            echo "<td><a href='patientbillingpharmacy.php?section=Pharmacy&Registration_ID=".$row['Registration_ID']."&Transaction_Type=".$row['Transaction_Type']."&Payment_Cache_ID=".$row['Payment_Cache_ID']."&NR=True&Billing_Type=".$Billing_Type2."&Sub_Department_ID=".$Sub_Department_ID."&PharmacyWorks=PharmacyWorksThisPage' target='_parent' style='text-decoration: none;'>".$row['Registration_ID']."</a></td>";
                echo "<td><a href='patientbillingpharmacy.php?section=Pharmacy&Registration_ID=".$row['Registration_ID']."&Transaction_Type=".$row['Transaction_Type']."&Payment_Cache_ID=".$row['Payment_Cache_ID']."&NR=True&Billing_Type=".$Billing_Type2."&Sub_Department_ID=".$Sub_Department_ID."&PharmacyWorks=PharmacyWorksThisPage' target='_parent' style='text-decoration: none;'>".$row['Sponsor_Name']."</a></td>";
                echo "<td><a href='patientbillingpharmacy.php?section=Pharmacy&Registration_ID=".$row['Registration_ID']."&Transaction_Type=".$row['Transaction_Type']."&Payment_Cache_ID=".$row['Payment_Cache_ID']."&NR=True&Billing_Type=".$Billing_Type2."&Sub_Department_ID=".$Sub_Department_ID."&PharmacyWorks=PharmacyWorksThisPage' target='_parent' style='text-decoration: none;'>".$age."</a></td>";
                echo "<td><a href='patientbillingpharmacy.php?section=Pharmacy&Registration_ID=".$row['Registration_ID']."&Transaction_Type=".$row['Transaction_Type']."&Payment_Cache_ID=".$row['Payment_Cache_ID']."&NR=True&Billing_Type=".$Billing_Type2."&Sub_Department_ID=".$Sub_Department_ID."&PharmacyWorks=PharmacyWorksThisPage' target='_parent' style='text-decoration: none;'>".$row['Gender']."</a></td>";
                echo "<td><a href='patientbillingpharmacy.php?section=Pharmacy&Registration_ID=".$row['Registration_ID']."&Transaction_Type=".$row['Transaction_Type']."&Payment_Cache_ID=".$row['Payment_Cache_ID']."&NR=True&Billing_Type=".$Billing_Type2."&Sub_Department_ID=".$Sub_Department_ID."&PharmacyWorks=PharmacyWorksThisPage' target='_parent' style='text-decoration: none;'>".$row['Sub_Department_Name']."</a></td>";
                echo "<td><a href='patientbillingpharmacy.php?section=Pharmacy&Registration_ID=".$row['Registration_ID']."&Transaction_Type=".$row['Transaction_Type']."&Payment_Cache_ID=".$row['Payment_Cache_ID']."&NR=True&Billing_Type=".$Billing_Type2."&Sub_Department_ID=".$Sub_Department_ID."&PharmacyWorks=PharmacyWorksThisPage' target='_parent' style='text-decoration: none;'>".$row['Transaction_Date_And_Time']."</a></td>";
                echo "<td><a href='patientbillingpharmacy.php?section=Pharmacy&Registration_ID=".$row['Registration_ID']."&Transaction_Type=".$row['Transaction_Type']."&Payment_Cache_ID=".$row['Payment_Cache_ID']."&NR=True&Billing_Type=".$Billing_Type2."&Sub_Department_ID=".$Sub_Department_ID."&PharmacyWorks=PharmacyWorksThisPage' target='_parent' style='text-decoration: none;'>".$row['Member_Number']."</a></td>";
                //echo "<td><a href='patientbillingpharmacy.php?section=Pharmacy&Registration_ID=".$row['Registration_ID']."&Transaction_Type=".$row['Transaction_Type']."&Payment_Cache_ID=".$row['Payment_Cache_ID']."&NR=True&PharmacyWorks=PharmacyWorksThisPage' target='_parent' style='text-decoration: none;'>".$row['Transaction_Date_And_Time']."</a></td>";
            echo "</tr>"; 
            $temp++;
        }
        echo "</table>";
    }else{
        echo '<center><table width =100% border=0>';
        echo "<tr><td colspan='10'><hr></tr>";
        echo '<tr id="thead"><td style="width:5%;"><b>SN</b></td>
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
            (ilc.status = 'approved' or ilc.status = 'active') and
            ilc.Check_In_Type='Pharmacy' and 
            pc.billing_type = 'Outpatient Cash' and
            ilc.Transaction_Date_And_Time between '$Date_Value' and '$original_Date'
            group by pc.payment_cache_id,ilc.sub_department_id order by pc.Payment_Cache_ID desc limit 200";

        $select_Filtered_Patients = mysqli_query($conn,$qr) or die(mysqli_error($conn));
        while($row = mysqli_fetch_array($select_Filtered_Patients)){
            $Sub_Department_ID = $row['Sub_Department_ID'];
        
        
            //GENERATE PATIENT YEARS, MONTHS AND DAYS
            $age = floor( (strtotime(date('Y-m-d')) - strtotime($row['Date_Of_Birth'])) / 31556926)." Years";       
            $date1 = new DateTime($Today);
            $date2 = new DateTime($row['Date_Of_Birth']);
            $diff = $date1 -> diff($date2);
            $age = $diff->y." Years, ";
            $age .= $diff->m." Months, ";
            $age .= $diff->d." Days";
            
            
            echo "<tr><td id='thead'>".$temp."</td><td><b>Approved</b></td><td><a href='billingpharmacy.php?section=Pharmacy&Registration_ID=".$row['Registration_ID']."&Transaction_Type=".$row['Transaction_Type']."&Payment_Cache_ID=".$row['Payment_Cache_ID']."&NR=True&Billing_Type=".$Billing_Type2."&Sub_Department_ID=".$Sub_Department_ID."&PharmacyWorks=PharmacyWorksThisPage' target='_parent' style='text-decoration: none;'>".ucwords(strtolower($row['Patient_Name']))."</a></td>";
            echo "<td><a href='billingpharmacy.php?section=Pharmacy&Registration_ID=".$row['Registration_ID']."&Transaction_Type=".$row['Transaction_Type']."&Payment_Cache_ID=".$row['Payment_Cache_ID']."&NR=True&Billing_Type=".$Billing_Type2."&Sub_Department_ID=".$Sub_Department_ID."&PharmacyWorks=PharmacyWorksThisPage' target='_parent' style='text-decoration: none;'>".$row['Registration_ID']."</a></td>";
                echo "<td><a href='billingpharmacy.php?section=Pharmacy&Registration_ID=".$row['Registration_ID']."&Transaction_Type=".$row['Transaction_Type']."&Payment_Cache_ID=".$row['Payment_Cache_ID']."&NR=True&Billing_Type=".$Billing_Type2."&Sub_Department_ID=".$Sub_Department_ID."&PharmacyWorks=PharmacyWorksThisPage' target='_parent' style='text-decoration: none;'>".$row['Sponsor_Name']."</a></td>";
                echo "<td><a href='billingpharmacy.php?section=Pharmacy&Registration_ID=".$row['Registration_ID']."&Transaction_Type=".$row['Transaction_Type']."&Payment_Cache_ID=".$row['Payment_Cache_ID']."&NR=True&Billing_Type=".$Billing_Type2."&Sub_Department_ID=".$Sub_Department_ID."&PharmacyWorks=PharmacyWorksThisPage' target='_parent' style='text-decoration: none;'>".$age."</a></td>";
                echo "<td><a href='billingpharmacy.php?section=Pharmacy&Registration_ID=".$row['Registration_ID']."&Transaction_Type=".$row['Transaction_Type']."&Payment_Cache_ID=".$row['Payment_Cache_ID']."&NR=True&Billing_Type=".$Billing_Type2."&Sub_Department_ID=".$Sub_Department_ID."&PharmacyWorks=PharmacyWorksThisPage' target='_parent' style='text-decoration: none;'>".$row['Gender']."</a></td>";
                echo "<td><a href='billingpharmacy.php?section=Pharmacy&Registration_ID=".$row['Registration_ID']."&Transaction_Type=".$row['Transaction_Type']."&Payment_Cache_ID=".$row['Payment_Cache_ID']."&NR=True&Billing_Type=".$Billing_Type2."&Sub_Department_ID=".$Sub_Department_ID."&PharmacyWorks=PharmacyWorksThisPage' target='_parent' style='text-decoration: none;'>".$row['Sub_Department_Name']."</a></td>";
                echo "<td><a href='billingpharmacy.php?section=Pharmacy&Registration_ID=".$row['Registration_ID']."&Transaction_Type=".$row['Transaction_Type']."&Payment_Cache_ID=".$row['Payment_Cache_ID']."&NR=True&Billing_Type=".$Billing_Type2."&Sub_Department_ID=".$Sub_Department_ID."&PharmacyWorks=PharmacyWorksThisPage' target='_parent' style='text-decoration: none;'>".$row['Transaction_Date_And_Time']."</a></td>";
                echo "<td><a href='billingpharmacy.php?section=Pharmacy&Registration_ID=".$row['Registration_ID']."&Transaction_Type=".$row['Transaction_Type']."&Payment_Cache_ID=".$row['Payment_Cache_ID']."&NR=True&Billing_Type=".$Billing_Type2."&Sub_Department_ID=".$Sub_Department_ID."&PharmacyWorks=PharmacyWorksThisPage' target='_parent' style='text-decoration: none;'>".$row['Member_Number']."</a></td>";
                //echo "<td><a href='billingpharmacy.php?section=Pharmacy&Registration_ID=".$row['Registration_ID']."&Transaction_Type=".$row['Transaction_Type']."&Payment_Cache_ID=".$row['Payment_Cache_ID']."&NR=True&PharmacyWorks=PharmacyWorksThisPage' target='_parent' style='text-decoration: none;'>".$row['Transaction_Date_And_Time']."</a></td>";
            echo "</tr>"; 
            $temp++;
        }
        echo "</table>";
    }
?>
</fieldset>



<script>
    function Patient_List_Search(){
        var Patient_Name = document.getElementById("Search_Patient").value;
        var date_From = document.getElementById("date_From").value;
        var date_To = document.getElementById("date_To").value;
        var Billing_Type = '<?php echo $Billing_Type2; ?>';
        var Patient_Type = document.getElementById("Patient_Type").value;

        document.getElementById("Patient_Number").value = '';

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
        
        myObjectSearchPatient.open('GET','Revenue_Center_Pharmacy_List_Iframe.php?Patient_Name='+Patient_Name+'&date_From='+date_From+'&date_To='+date_To+'&Billing_Type='+Billing_Type+'&Patient_Type='+Patient_Type,true);
        myObjectSearchPatient.send();   
    }
</script>


<script>
    function Patient_List_Search2(){
        var Patient_Number = document.getElementById("Patient_Number").value;
        var date_From = document.getElementById("date_From").value;
        var date_To = document.getElementById("date_To").value;
        var Billing_Type = '<?php echo $Billing_Type2; ?>';
        var Patient_Type = document.getElementById("Patient_Type").value;

        document.getElementById("Search_Patient").value = '';

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
        
        myObjectSearchPatient.open('GET','Revenue_Center_Pharmacy_List_Iframe.php?Patient_Number='+Patient_Number+'&date_From='+date_From+'&date_To='+date_To+'&Billing_Type='+Billing_Type+'&Patient_Type='+Patient_Type,true);
        myObjectSearchPatient.send();   
    }
</script>
<br/>
<?php
    include("./includes/footer.php");
?>