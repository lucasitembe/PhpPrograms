<?php
	include("./includes/header.php");
	include("./includes/connection.php");
	if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Eye_Works'])){
	    if($_SESSION['userinfo']['Eye_Works'] != 'yes'){
			header("Location: ./index.php?InvalidPrivilege=yes");
	    }else{
			@session_start();
			if(!isset($_SESSION['Optical_Supervisor'])){ 
			    header("Location: ./deptsupervisorauthentication.php?SessionCategory=Optical&InvalidSupervisorAuthentication=yes");
			}
	    }
	}else{
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
		@session_destroy();
	    header("Location: ../index.php?InvalidPrivilege=yes");
    }

	$Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
		$age ='';
    }

    //get sub_department_id based on session created
    if(isset($_SESSION['Optical_info'])){
        $Sub_Department_ID = $_SESSION['Optical_info'];
    }else{
        header("Location: ./deptsupervisorauthentication.php?SessionCategory=Optical&InvalidSupervisorAuthentication=yes");
    }

    //get sub department name
    $select = mysqli_query($conn,"select Sub_Department_Name from tbl_sub_department where Sub_Department_ID = '$Sub_Department_ID'") or die(mysqli_error($conn));
    $nm = mysqli_num_rows($select);
    if($nm > 0){
        while ($dt = mysqli_fetch_array($select)) {
            $Sub_Department_Name = $dt['Sub_Department_Name'];
        }
    }else{
        $Sub_Department_Name = '';
    }

    if(isset($_GET['BillingType'])){
        $BillingType = $_GET['BillingType'];
    }else{
        $BillingType = 'OutpatientCash';
    }

    //generate billing type
    if(isset($_GET['BillingType'])){
        if(strtolower($BillingType) == 'outpatientcash'){
            $sql = "(Billing_Type = 'Outpatient Cash' or Billing_Type = 'Outpatient Credit') and ilc.Transaction_Type = 'Cash'";
            $Billing_Title = 'Outpatient Cash';
        }else if(strtolower($BillingType) == 'outpatientcredit'){
            $sql = "(Billing_Type = 'Outpatient Cash' or Billing_Type = 'Outpatient Credit') and ilc.Transaction_Type = 'Credit'";
            $Billing_Title = 'Outpatient Credit';
        }else if(strtolower($BillingType) == 'inpatientcash'){
            $sql = "(Billing_Type = 'Inpatient Cash' or Billing_Type = 'Inpatient Credit') and ilc.Transaction_Type = 'Cash'";
            $Billing_Title = 'Inpatient Cash';
        }else if(strtolower($BillingType) == 'inpatientcredit'){
            $sql = "(Billing_Type = 'Inpatient Cash' or Billing_Type = 'Inpatient Credit') and ilc.Transaction_Type = 'Credit'";
            $Billing_Title = 'Inpatient Credit';
        }else{
            $sql = "(Billing_Type = 'Outpatient Cash' or Billing_Type = 'Outpatient Credit') and ilc.Transaction_Type = 'Cash'";
            $Billing_Title = 'Outpatient Cash';
        }
    }else{
        $sql = "(Billing_Type = 'Outpatient Cash' or Billing_Type = 'Outpatient Credit') and ilc.Transaction_Type = 'Cash'";
        $Billing_Title = 'Outpatient Cash';
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

<script type="text/javascript">
    function gotolink(){
    	var patientlist = document.getElementById('patientlist').value;
    	if(patientlist == 'OUTPATIENT CASH'){
    	    document.location = "glassprocessing.php?BillingType=OutpatientCash&GlassProcessing=GlassProcessingThisPage";
    	}else if (patientlist == 'OUTPATIENT CREDIT') {
    	    document.location = "glassprocessing.php?BillingType=OutpatientCredit&GlassProcessing=GlassProcessingThisPage";
    	}else if (patientlist == 'INPATIENT CASH') {
    	    document.location = "glassprocessing.php?BillingType=InpatientCash&GlassProcessing=GlassProcessingThisPage";
    	}else if (patientlist == 'INPATIENT CREDIT') {
    	    document.location = "glassprocessing.php?BillingType=InpatientCredit&GlassProcessing=GlassProcessingThisPage";
    	}else{
    	    alert("Choose Type Of Patients To View");
    	}
    }
</script>

<label style="border: 1px ;padding: 8px;margin-right: 7px;"" class='art-button-green'>
<select id='patientlist' name='patientlist' onchange="gotolink()">
    <option <?php if($BillingType == 'OutpatientCash'){ echo "selected='selected'"; } ?>>
		OUTPATIENT CASH
    </option>
    <option <?php if($BillingType == 'OutpatientCredit'){ echo "selected='selected'"; } ?>>
		OUTPATIENT CREDIT
    </option>
    <option <?php if($BillingType == 'InpatientCash'){ echo "selected='selected'"; } ?>>
		INPATIENT CASH
    </option>
    <option <?php if($BillingType == 'InpatientCredit'){ echo "selected='selected'"; } ?>>
		INPATIENT CREDIT
    </option>
</select>
</label> 

<?php
    if(isset($_SESSION['userinfo'])){
        echo "<a href='opticalworkspage.php?OpticalWorks=OpticalWorksThisPage' class='art-button-green'>BACK</a>";
    }
?>
<br/><br/>

<fieldset>
	<table width="100%">
		<tr>
			<td>
                <input type='text' name='Search_Patient' id='Search_Patient' style="text-align: center;" oninput='Patient_List_Search()' onkeyup='Patient_List_Search()' placeholder=' ~~~~~ Enter Patient Name ~~~~~'>
            </td>
            <td>
                <input type="text" name="Patient_Number" id="Patient_Number" style="text-align: center;" oninput='Patient_List_Search3()' onkeyup='Patient_List_Search3()' placeholder=' ~~~~~ Enter Patient Number ~~~~~'>
            </td>
            <td style="text-align: right;" width="7%">Start Date</td>
            <td width="14%">
                <input type="text" name="date_From" id="date_From" placeholder='~~~ Start Date ~~~' autocomplete='off' style="text-align: center;" readonly="readonly">
            </td>
            <td style="text-align: right;" width="7%">End Date</td>
            <td width="14%">
                <input type="text" name="date_To" id="date_To" placeholder='~~~ End Date ~~~' autocomplete='off' style="text-align: center;" readonly="readonly">
            </td>
            <td width="7%" style="text-align: right;">Sponsor</td>
            <td>
                <select id="Sponsor_ID" name="Sponsor_ID" onchange="Filter_Patient_List()" class="form-control">
                    <option selected="selected" value="0">All</option>
                    <?php
                        $select = mysqli_query($conn,"select Sponsor_ID, Guarantor_Name from tbl_sponsor order by Guarantor_Name") or die(mysqli_error($conn));
                        $num = mysqli_num_rows($select);
                        if($num > 0){
                            while ($data = mysqli_fetch_array($select)) {
                                echo '<option value="'.$data['Sponsor_ID'].'">'.$data['Guarantor_Name'].'</option>';
                            }
                        }
                    ?>
                </select>
            </td>
            <td width="7%" style="text-align: center;">
            	<input type="button" name="Filter" id="Filter" value="FILTER" class="art-button-green" onclick="Patient_List_Search2();">
            </td>
		</tr>
	</table>
</fieldset>
<?php
                
?>
<fieldset style='overflow-y: scroll; height: 400px;' id='Patients_Fieldset_List'>
	<legend align="right"><b><?php echo ucwords(strtoupper($Sub_Department_Name)).' Glass Processing ~ '.$Billing_Title; ?></b></legend>
    <table width="100%" class="table table-striped table-hover ">
        <thead style="background-color:#bdb5ac">
        <!-- bdb5ac -->
        <!-- e6eded -->
        <!-- a3c0cc -->
            <tr>
                <th style="text-align:left;"><b>SN</b></th>
                <th style="text-align:left;"><b>PATIENT NAME</b></th>
                <th style="text-align:left;"><b>PATIENT NUMBER</b></th>
                <th style="text-align:left;"><b>SPONSOR</b></th>
                <th style="text-align:left;"><b>AGE</b></th>
                <th style="text-align:left;"><b>GENDER</b></th>
                <th style="text-align:left;"><b>PHONE NUMBER</b></th>
                <th style="text-align:left;"><b>LOCATION</b></th>
            </tr>
        </thead>
        <?php
        //$depyid=$_SESSION['Sub_Department_ID'];
			echo $Title; $temp = 0;
			$select = mysqli_query($conn,"SELECT pc.Registration_ID, pc.Transaction_Status as General_Transaction_Status, pc.Payment_Cache_ID, sp.Guarantor_Name, sd.Sub_Department_Name,
									preg.Patient_Name, pc.Sponsor_Name, preg.Date_Of_Birth, preg.Gender, preg.Phone_Number,
									preg.Member_Number, ilc.Transaction_Type from
									tbl_payment_cache pc, tbl_item_list_cache ilc, tbl_patient_registration preg, tbl_sponsor sp, tbl_sub_department sd where
									pc.payment_cache_id = ilc.payment_cache_id and
									sd.Sub_Department_ID = ilc.Sub_Department_ID and
									preg.registration_id = pc.registration_id and
									sp.Sponsor_ID = preg.Sponsor_ID and
									ilc.status <> 'dispensed' and
									ilc.status <> 'removed' and
									ilc.Check_In_Type = 'Optical' and
                                    ilc.Sub_Department_ID = '$Sub_Department_ID' and
                                    $sql
									group by pc.payment_cache_id order by pc.Payment_Cache_ID desc limit 100") or die(mysqli_error($conn));
			$num = mysqli_num_rows($select);
			if($num > 0){
				while ($data = mysqli_fetch_array($select)) {
					//calculate patient age
					$date1 = new DateTime($Today);
					$date2 = new DateTime($data['Date_Of_Birth']);
					$diff = $date1 -> diff($date2);
					$age = $diff->y." Years, ";
					$age .= $diff->m." Months, ";
					$age .= $diff->d." Days";

					echo "<tr>";
					echo "<td><a href='glassprocessingpatient.php?Registration_ID=".$data['Registration_ID']."&Payment_Cache_ID=".$data['Payment_Cache_ID']."&GlassProcessingPatient=GlassProcessingPatientThisPage' style='text-decoration: none;' target='_parent'>".++$temp."</a></td>";
					echo "<td><a href='glassprocessingpatient.php?Registration_ID=".$data['Registration_ID']."&Payment_Cache_ID=".$data['Payment_Cache_ID']."&GlassProcessingPatient=GlassProcessingPatientThisPage' style='text-decoration: none;' target='_parent'>".ucwords(strtolower($data['Patient_Name']))."</a></td>";
					echo "<td><a href='glassprocessingpatient.php?Registration_ID=".$data['Registration_ID']."&Payment_Cache_ID=".$data['Payment_Cache_ID']."&GlassProcessingPatient=GlassProcessingPatientThisPage' style='text-decoration: none;' target='_parent'>".$data['Registration_ID']."</a></td>";
					echo "<td><a href='glassprocessingpatient.php?Registration_ID=".$data['Registration_ID']."&Payment_Cache_ID=".$data['Payment_Cache_ID']."&GlassProcessingPatient=GlassProcessingPatientThisPage' style='text-decoration: none;' target='_parent'>".$data['Guarantor_Name']."</a></td>";
					echo "<td><a href='glassprocessingpatient.php?Registration_ID=".$data['Registration_ID']."&Payment_Cache_ID=".$data['Payment_Cache_ID']."&GlassProcessingPatient=GlassProcessingPatientThisPage' style='text-decoration: none;' target='_parent'>".$age."</a></td>";
					echo "<td><a href='glassprocessingpatient.php?Registration_ID=".$data['Registration_ID']."&Payment_Cache_ID=".$data['Payment_Cache_ID']."&GlassProcessingPatient=GlassProcessingPatientThisPage' style='text-decoration: none;' target='_parent'>".$data['Gender']."</a></td>";
					echo "<td><a href='glassprocessingpatient.php?Registration_ID=".$data['Registration_ID']."&Payment_Cache_ID=".$data['Payment_Cache_ID']."&GlassProcessingPatient=GlassProcessingPatientThisPage' style='text-decoration: none;' target='_parent'>".$data['Phone_Number']."</a></td>";
					echo "<td><a href='glassprocessingpatient.php?Registration_ID=".$data['Registration_ID']."&Payment_Cache_ID=".$data['Payment_Cache_ID']."&GlassProcessingPatient=GlassProcessingPatientThisPage' style='text-decoration: none;' target='_parent'>".$data['Sub_Department_Name']."</a></td>";
					echo "</tr>";
				}
			}
		?>
	</table>
</fieldset>


<script>
    function Patient_List_Search(){
		var Patient_Name = document.getElementById("Search_Patient").value;
		var date_From = document.getElementById("date_From").value;
		var date_To = document.getElementById("date_To").value;
		var Sponsor_ID = document.getElementById("Sponsor_ID").value;
		document.getElementById("Patient_Number").value = '';

        document.getElementById('Patients_Fieldset_List').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';
        if(window.XMLHttpRequest){
            myObjectSearchPatient = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObjectSearchPatient = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectSearchPatient.overrideMimeType('text/xml');
        }
        myObjectSearchPatient.onreadystatechange = function (){
            data29 = myObjectSearchPatient.responseText;
            if (myObjectSearchPatient.readyState == 4) {
                document.getElementById('Patients_Fieldset_List').innerHTML = data29;
            }
        }; //specify name of function that will handle server response........
        
        myObjectSearchPatient.open('GET','Glass_Processing_Filter_Patient_List2.php?Patient_Name='+Patient_Name+'&date_From='+date_From+'&date_To='+date_To+'&Sponsor_ID='+Sponsor_ID,true);
        myObjectSearchPatient.send();
    }
</script> 


<script>
    function Patient_List_Search2(){
        var date_From = document.getElementById("date_From").value;
        var date_To = document.getElementById("date_To").value;
        document.getElementById("Search_Patient").value = '';
        if(date_From != null && date_From != '' && date_To != null && date_To != ''){
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
            
            myObjectSearchPatient.open('GET','Glass_Processing_Filter_Patient_List2.php?Sponsor_ID='+Sponsor_ID+'&Start_Date='+date_From+'&End_Date='+date_To,true);
            myObjectSearchPatient.send(); 
        } else{
            if(date_From == null && date_From == ''){
                document.getElementById("date_From").style = 'border: 3px solid red; text-align: center;';
            }else{
                document.getElementById("date_From").style = 'border: 1px solid black; text-align: center;';
            }
            
            if(date_To == null && date_To == ''){
                document.getElementById("date_To").style = 'border: 3px solid red; text-align: center;';
            }else{
                document.getElementById("date_To").style = 'border: 1px solid black; text-align: center;';
            }
        }
    }
</script>


<script>
    function Patient_List_Search3(){
        var Patient_Number = document.getElementById("Patient_Number").value;

        document.getElementById('Patients_Fieldset_List').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';
        if(window.XMLHttpRequest){
            myObjectSearchPatient = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObjectSearchPatient = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectSearchPatient.overrideMimeType('text/xml');
        }
        myObjectSearchPatient.onreadystatechange = function (){
            data1280 = myObjectSearchPatient.responseText;
            if (myObjectSearchPatient.readyState == 4) {
                document.getElementById('Patients_Fieldset_List').innerHTML = data1280;
            }
        }; //specify name of function that will handle server response........
        
        myObjectSearchPatient.open('GET','Glass_Processing_Filter_Patient_List3.php?Patient_Number='+Patient_Number,true);
        myObjectSearchPatient.send();   
    }
</script>

<script type="text/javascript">
	function Filter_Patient_List(){
		var Sponsor_ID = document.getElementById("Sponsor_ID").value;
        Search_Patient = document.getElementById("Search_Patient").value = '';
        Patient_Number = document.getElementById("Patient_Number").value = '';

        document.getElementById('Patients_Fieldset_List').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';
        if(window.XMLHttpRequest){
            myObjectSearchPatient = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObjectSearchPatient = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectSearchPatient.overrideMimeType('text/xml');
        }
        myObjectSearchPatient.onreadystatechange = function (){
            data280 = myObjectSearchPatient.responseText;
            if (myObjectSearchPatient.readyState == 4) {
                document.getElementById('Patients_Fieldset_List').innerHTML = data280;
            }
        }; //specify name of function that will handle server response........
        
        myObjectSearchPatient.open('GET','Glass_Processing_Filter_Patient_List.php?Sponsor_ID='+Sponsor_ID,true);
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
$('#date_From').datetimepicker({value:'',step:5});
$('#date_To').datetimepicker({
dayOfWeekStart : 1,
lang:'en',
startDate:'now'
});
$('#date_To').datetimepicker({value:'',step:5});
</script>
<!--End datetimepicker-->
<?php
	include("./includes/footer.php");
?>