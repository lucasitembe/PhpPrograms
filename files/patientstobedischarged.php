<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    $temp = 0;
    if(!isset($_SESSION['userinfo'])){
    	@session_destroy();
    	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    
    if(isset($_SESSION['userinfo'])){
    	if(isset($_SESSION['userinfo']['Admission_Works'])){
    	    if($_SESSION['userinfo']['Admission_Works'] != 'yes'){
                header("Location: ./index.php?InvalidPrivilege=yes");
    	    }
    	}else{
    	    header("Location: ./index.php?InvalidPrivilege=yes");
    	}
    }else{
        @session_destroy();
	    header("Location: ../index.php?InvalidPrivilege=yes");
    }
       
	if(isset($_SESSION['userinfo'])){
		echo "<a href='admissionworkspage.php?section=Admission&AdmisionWorks=AdmisionWorksThisPage' class='art-button-green'>BACK</a>";
	}

    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
        $age ='';
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

<br/><br/>
<center>
    <table width=90% style="background-color:white;">
        <tr>
			<td style="text-align: right; width: 10%;"><b>Sponsor Name</b></td>
			<td width="20%">
				<select name="Sponsor_ID" id="Sponsor_ID" onchange="Search_Patients()">
					<option selected="selected" value="0">All</option>
					<?php
						$select = mysqli_query($conn,"select Guarantor_Name, Sponsor_ID from tbl_sponsor order by Guarantor_Name") or die(mysqli_error($conn));
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
            <td width="34%">
                <input type="text" name="Patient_Name" id="Patient_Name" autocomplete='off' style="text-align: center;" placeholder="~~~~~ Enter Patient Name ~~~~~" onkeyup="Search_Patients_Via_Name()" oninput="Search_Patients_Via_Name()">
            </td>
            <td></td>
            <td width="34%">
                <input type="text" name="Patient_Number" id="Patient_Number" autocomplete='off' style="text-align: center;" placeholder="~~~~~ Enter Patient Number ~~~~~" onkeyup="Search_Patients_Via_Number()" oninput="Search_Patients_Via_Number()">
            </td>
            <td></td>
        </tr>
    </table>
</center>
<fieldset style='overflow-y: scroll; height: 400px;background-color:white;margin-top:20px;' id='Patient_Fieldset_List'>
    <legend align='right' style="background-color:#006400;color:white;padding:5px;"><b>PATIENTS TO BE DISCHARGED</b></legend>
        <table width=100% border=1>
            <tr id='thead'>
    		    <td width=5%><b>SN</b></td>
    		    <td><b>PATIENT NAME</b></td>
    		    <td style='text-align: left; width: 7%;'><b>PATIENT #</b></td>
                <td style='text-align: left;width: 7%;'><b>MEMBER #</b></td>
                <td style='text-align: left;width: 10%;'><b>SPONSOR</b></td>
                <td style='text-align: left; width: 13%;'><b>PATIENT AGE</b></td>
                <td style='text-align: left; width: 5%;'><b>GENDER</b></td>
                <td style='text-align: left; width: 10%;'><b>EMPLOYEE DISCHARGE</b></td>
                <td style='text-align: left; width: 13%;'>&nbsp;&nbsp;<b>DISCHARGE DATE</b></td>
                <?php if(strtolower($_SESSION['userinfo']['Session_Master_Priveleges']) == 'yes'){ ?>
                    <td width="7%"><b>ACTION</b></td>
                <?php } ?>
    		</tr>
    	    <tr><td colspan="10"><hr></td></tr>
<?php
    //select patients
    $select = mysqli_query($conn,"select pr.Registration_ID, pr.Patient_Name, pr.Gender, pr.Date_Of_Birth, sp.Guarantor_Name, pr.Phone_Number, pr.Member_Number, ad.pending_setter, ad.pending_set_time, ad.Admision_ID 
                            from tbl_sponsor sp, tbl_patient_registration pr, tbl_admission ad where
                            pr.Registration_ID = ad.Registration_ID and
                            sp.Sponsor_ID = pr.Sponsor_ID and
                            ad.Admission_Status = 'pending' and
                            ad.Credit_Bill_Status = 'pending' and
                            ad.Cash_Bill_Status = 'pending' and
                            ad.Discharge_Clearance_Status = 'not cleared' order by Admision_ID desc limit 10") or die(mysqli_error($conn));

    $num = mysqli_num_rows($select);
    if($num > 0){
        while ($data = mysqli_fetch_array($select)) {
            $Date_Of_Birth = $data['Date_Of_Birth'];
            //calculate age
            $date1 = new DateTime($Today);
            $date2 = new DateTime($Date_Of_Birth);
            $diff = $date1 -> diff($date2);
            $age = $diff->y." Years, ";
            $age .= $diff->m." Months, ";
            $age .= $diff->d." Days";

            if($data['pending_setter'] != null && $data['pending_setter'] != ''){
                $Emp_ID = $data['pending_setter'];
                //get employee set
                $slct = mysqli_query($conn,"select Employee_Name from tbl_employee where Employee_ID = '$Emp_ID'") or die(mysqli_error($conn));
                $no = mysqli_num_rows($slct);
                if($no > 0){
                    while ($dt = mysqli_fetch_array($slct)) {
                        $Employee_Name = $dt['Employee_Name'];
                    }
                }else{
                    $Employee_Name = '';
                }
            }else{
                $Employee_Name = '';
            }
?>
            <tr id='thead'>
                <td width=5%><?php echo ++$temp.'<b>.</b>'; ?></td>
                <td><?php echo $data['Patient_Name']; ?></td>
                <td><?php echo $data['Registration_ID']; ?></td>
                <td><?php echo $data['Member_Number']; ?></td>
                <td><?php echo $data['Guarantor_Name']; ?></td>
                <td><?php echo $age; ?></td>
                <td><?php echo $data['Gender']; ?></td>
                <td><?php echo ucwords(strtolower($Employee_Name)); ?></td>
                <td>&nbsp;&nbsp;<?php echo $data['pending_set_time']; ?></td>
            <?php // if(strtolower($_SESSION['userinfo']['Session_Master_Priveleges']) == 'yes'){ ?>
                <td>
                    <input type="button" name="Action" id="Action" value="UNDO DISCHARGED" class="art-button-green" onclick="Undo_Discharge_Warning(<?php echo $data['Admision_ID']; ?>);">
                </td>
            <?php //} ?>
            </tr>
<?php
        }
    }
?>
        </table>
</fieldset>

<div id="Undo_Discharge_Dialog">
    <center>
        Are you sure you want to undo discharge process?<br/><br/>
        <table width="100%">
            <tr>
                <td style="text-align: right;" id="Button_Area">
                    
                </td>
            </tr>
        </table>
    </center>
</div>

<script type="text/javascript">
    function Search_Patients(){
        var Sponsor_ID = document.getElementById("Sponsor_ID").value;
        if(window.XMLHttpRequest) {
            myObjectSearchPatient = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObjectSearchPatient = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectSearchPatient.overrideMimeType('text/xml');
        }

        myObjectSearchPatient.onreadystatechange = function (){
            data2605 = myObjectSearchPatient.responseText;
            if (myObjectSearchPatient.readyState == 4) {
                document.getElementById('Patient_Fieldset_List').innerHTML = data2605;
            }
        }; //specify name of function that will handle server response........
        myObjectSearchPatient.open('GET','Patient_To_Be_Discharges_Search.php?Sponsor_ID='+Sponsor_ID,true);
        myObjectSearchPatient.send();
    }
</script>



<script type="text/javascript">
    function Search_Patients_Via_Name(){
        var Sponsor_ID = document.getElementById("Sponsor_ID").value;
        var Patient_Name = document.getElementById("Patient_Name").value;
        document.getElementById("Patient_Number").value = '';

        if(window.XMLHttpRequest) {
            myObjectSearchPatient = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObjectSearchPatient = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectSearchPatient.overrideMimeType('text/xml');
        }

        myObjectSearchPatient.onreadystatechange = function (){
            data2605 = myObjectSearchPatient.responseText;
            if (myObjectSearchPatient.readyState == 4) {
                document.getElementById('Patient_Fieldset_List').innerHTML = data2605;
            }
        }; //specify name of function that will handle server response........
        myObjectSearchPatient.open('GET','Patient_To_Be_Discharges_Search_Specific.php?Sponsor_ID='+Sponsor_ID+'&Patient_Name='+Patient_Name,true);
        myObjectSearchPatient.send();
    }
</script>


<script type="text/javascript">
    function Search_Patients_Via_Number(){
        var Sponsor_ID = document.getElementById("Sponsor_ID").value;
        var Patient_Number = document.getElementById("Patient_Number").value;
        document.getElementById("Patient_Name").value = '';

        if(window.XMLHttpRequest) {
            myObjectSearchPatient = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObjectSearchPatient = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectSearchPatient.overrideMimeType('text/xml');
        }

        myObjectSearchPatient.onreadystatechange = function (){
            data2605 = myObjectSearchPatient.responseText;
            if (myObjectSearchPatient.readyState == 4) {
                document.getElementById('Patient_Fieldset_List').innerHTML = data2605;
            }
        }; //specify name of function that will handle server response........
        myObjectSearchPatient.open('GET','Patient_To_Be_Discharges_Search_Specific.php?Sponsor_ID='+Sponsor_ID+'&Patient_Number='+Patient_Number,true);
        myObjectSearchPatient.send();
    }
</script>

<script type="text/javascript">
    function Undo_Discharge_Warning(Admision_ID){
        document.getElementById("Button_Area").innerHTML = '<input type="button" value="YES" onclick="Undo_Discharge('+Admision_ID+')" class="art-button-green">&nbsp;&nbsp;&nbsp;<input type="button" value="CANCEL" onclick="Close_Button()" class="art-button-green">';
        $("#Undo_Discharge_Dialog").dialog("open");
    }
</script>

<script type="text/javascript">
    function Close_Button(){
        $("#Undo_Discharge_Dialog").dialog("close");
    }
</script>


<script type="text/javascript">
    function Undo_Discharge(Admision_ID){
        if (window.XMLHttpRequest) {
            myObjectUndoDischarge = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectUndoDischarge = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectUndoDischarge.overrideMimeType('text/xml');
        }

        myObjectUndoDischarge.onreadystatechange = function () {
            data = myObjectUndoDischarge.responseText;
            if (myObjectUndoDischarge.readyState == 4) {
                document.location = 'patientstobedischarged.php?PatientsToBeDischarged=PatientsToBeDischargedThisPage';
            }
        }; //specify name of function that will handle server response........

        myObjectUndoDischarge.open('GET','Undo_Discharge.php?Admision_ID='+Admision_ID, true);
        myObjectUndoDischarge.send();
    }
</script>


<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">

<script>
    $(document).ready(function () {
        $("#Undo_Discharge_Dialog").dialog({autoOpen: false, width: '40%', height: 150, title: 'CONFIRM PROCESS', modal: true});
    });
</script>
<?php
    include("./includes/footer.php");
?>