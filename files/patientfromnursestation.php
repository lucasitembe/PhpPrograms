<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    /*if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Nurse_Station_Works'])){
	    if($_SESSION['userinfo']['Nurse_Station_Works'] != 'yes'){
		header("Location: ./index.php?InvalidPrivilege=yes");
	    }
	}else{
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	    header("Location: ../index.php?InvalidPrivilege=yes");
    }*/
    
    //get employee id
    if(isset($_SESSION['userinfo']['Employee_ID'])){
	  $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    }else{
	  $Employee_ID = 0;
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
Error_reporting(E_ERROR|E_PARSE);

    //if(isset($_SESSION['userinfo'])){ 
?>
   <!-- <a href='viewnursepatient.php' class='art-button-green'>
        VIEW CHECKED
    </a>-->
<?php // }  ?>

<script type="text/javascript">
    function gotolink(){
	var url = "<?php
	if(isset($_GET['Registration_ID'])){
	echo "Registration_ID=".$_GET['Registration_ID']."&";
	}
	if(isset($_GET['Patient_Payment_ID'])){
	    echo "Patient_Payment_ID=".$_GET['Patient_Payment_ID']."&";
	    }
	if(isset($_GET['Patient_Payment_Item_List_ID'])){
	    echo "Patient_Payment_Item_List_ID=".$_GET['Patient_Payment_Item_List_ID']."&";
	    }
	?>SearchListDoctorsPagePatientBilling=SearchListDirectCashPatientBillingThisPage";
	var patientlist = document.getElementById('patientlist').value;
	
	if(patientlist=='MY PATIENT LIST'){
	    document.location = "doctorcurrentpatientlist.php?"+url;
	}else if (patientlist=='CLINIC PATIENT LIST') {
	    document.location = "clinicpatientlist.php?"+url;
	}else if (patientlist=='CONSULTED PATIENT LIST') {
	    document.location = "doctorconsultedpatientlist.php?"+url;
	}else if (patientlist=='FROM NURSE STATION') {
	    document.location = "patientfromnursestation.php?NurseStationPatientList=NurseStationPatientListThisPage"+url;
	}else{
	    alert("Choose Type Of Patients To View");
	}
    }
</script>
	
<label style='border: 1px ;padding: 8px;margin-right: 7px;background: #2A89AF' onchange="gotolink()" class='btn-default'>
    <select id='patientlist' name='patientlist'>
    <!--<option></option>-->
	<option>
	    MY PATIENT LIST
	</option>
	<option>
	    CLINIC PATIENT LIST
	</option>
	<option>
	    CONSULTED PATIENT LIST
	</option>
	<option selected='selected'>
	    FROM NURSE STATION
	</option>
    </select>
    <input type='button' value='VIEW' onclick='gotolink()'>
</label>


<?php
    if(isset($_SESSION['userinfo'])){
	echo "<a href='doctorspageoutpatientwork.php?RevisitedPatient=RevisitedPatientThisPage' class='art-button-green'>BACK</a>";
    }
?>

<?php
    //if(isset($_SESSION['userinfo'])){ 
?>
    <!--<a href='searchnurseform.php' class='art-button-green'>
      PATIENTS LISTS
    </a>-->
<?php  //} ?>

<?php
    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
        $Age = $Today - $original_Date; 
    }
?>

<script type='text/javascript'>
    function access_Denied(){ 
   alert("Access Denied");
   document.location = "./index.php";
    }
</script>

<script language="javascript" type="text/javascript">
    function searchPatient(Patient_Name){
              var direct_to_clinic= $("#Patient_Direction2").val()
           var direct_to_doctor= $("#Patient_Direction").val()
           var patient_direction;
           if($('#Patient_Direction2').is(':checked')) { 
              patient_direction=direct_to_clinic;
           }
           if($('#Patient_Direction').is(':checked')) { 
               patient_direction=direct_to_doctor;
           }
          var Date_From=$("#Date_From").val();
          var Date_To=$("#Date_To").val();
          var uri="Select_Patients_List_TypeByName.php";
          $("#loader").show();  
           $.ajax({
                type: 'GET',
                url: uri,
                data: {patient_direction : patient_direction,Patient_Name:Patient_Name,Date_From:Date_From,Date_To:Date_To},
                success: function(data){
                    document.getElementById('Patients_List_Area').innerHTML = data;
                    $("#loader").hide(); 
                   // alert(data)
                },
                error: function(){
                        alert("error");
                }
            });
    }
</script>
<script language="javascript" type="text/javascript">
    function searchPatientbynum(Patient_Number){
              var direct_to_clinic= $("#Patient_Direction2").val()
           var direct_to_doctor= $("#Patient_Direction").val()
           var patient_direction;
           if($('#Patient_Direction2').is(':checked')) { 
              patient_direction=direct_to_clinic;
           }
           if($('#Patient_Direction').is(':checked')) { 
               patient_direction=direct_to_doctor;
           }
           var Date_From=$("#Date_From").val();
          var Date_To=$("#Date_To").val();
          var uri="Select_Patients_List_TypeByNumber.php";
          $("#loader").show();  
           $.ajax({
                type: 'GET',
                url: uri,
                data: {patient_direction : patient_direction,Patient_Number:Patient_Number,Date_From:Date_From,Date_To:Date_To},
                success: function(data){
                    document.getElementById('Patients_List_Area').innerHTML = data;
                    $("#loader").hide(); 
                   // alert(data)
                },
                error: function(){
                        alert("error");
                }
            });
    }
</script>
<br/><br/>
<center>

</center>
<br/>
<!---------------------------------------------------------------------------------------------------------------->
<center>
    <fieldset>  
    <table width="100%">
        <tr>
            <td>
                    <table width=100%>
                        <tr>
                            <td width=25%>
                                <input type='text' style='text-align: center;' name='Search_Patient' id='Search_Patient' onkeyup='searchPatient(this.value)' onkeypress='searchPatient(this.value)' placeholder='~~~~~~~~~~~~~Search Patient Name~~~~~~~~~~~~~'>
                            </td>
                            <td width=25%>
                                <input type='text' style='text-align: center;' name='Search_Patient' id='Search_Patient' onkeyup='searchPatientbynum(this.value)' onkeypress='searchPatientbynum(this.value)' placeholder='~~~~~~~~~~~~~Search Patient Number~~~~~~~~~~~~~'>
                            </td>
                            <td width=50% style='text-align: center;'>
                                    &nbsp;&nbsp;&nbsp;&nbsp;<input type='radio' name='Patient_Direction' id='Patient_Direction2' value='Direct To Clinic' checked='checked'onclick='Select_Patients_List_Type(this.value);'>My Clinic List<img id="loader" style="float:left;display:none" src="images/22.gif"/>
                            
                                &nbsp;&nbsp;&nbsp;&nbsp;<input type='radio' name='Patient_Direction' id='Patient_Direction' value='Direct To Doctor'  onclick='Select_Patients_List_Type(this.value);'>My Patients List
                            </td>
                        </tr>

                    </table>
                
            </td>
        </tr>
        <tr>
            <td style="text-align:center">
                <input type="text" autocomplete="off" style='text-align: center;width:20%;display:inline' id="Date_From" placeholder="Start Date"/>
                <input type="text" autocomplete="off" style='text-align: center;width:20%;display:inline' id="Date_To" placeholder="End Date"/>&nbsp;
                
                <input type="button" value="Filter" class="art-button-green" onclick="filterPatient()"><br/>
               
            </td>
        </tr>

    </table>
        </fieldset>  
</center>
<!----------------------------------------------------------------------------------------------------------------->
<?php
    //today function
    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
	$original_Date = $row['today'];
	$new_Date = date("Y-m-d", strtotime($original_Date));
	$Today = $new_Date;
	$age ='';
    }
    //end
?>




<fieldset style='overflow-y: scroll; height: 400px; background-color:white;'>
    <legend legend align="right" style=background-color:#006400;color:white;padding:5px;"><b>CHECKED LISTS</b></legend>
        <center id='Patients_List_Area'>
		<table width =100% border=0>
		    <tr><td colspan="7"><hr></td></tr>
		    <tr ID="thead">
                        <td style="width:5%;"><b>SN</b></td>
			<td><b>PATIENT NAME</b></td>
			<td width=10%><b>PATIENT NO</b></td>
			<td width=18%><b>AGE</b></td>
			<td width=8%><b>GENDER</b></td>
			<td width=15%><b>SPONSOR</b></td>
			<td width=12%><b>VISITED DATE</b></td>
                    </tr>
		    <tr><td colspan="7"><hr></td></tr>
		
<?php
       $select_Filtered_Patient =
            mysqli_query($conn,"SELECT * FROM tbl_patient_payments pp,
                tbl_patient_payment_item_list ppl,
                tbl_patient_registration pr,
                tbl_sponsor sp,
                tbl_clinic_employee ce,
                tbl_check_in ci WHERE
                ce.Clinic_ID = ppl.Clinic_ID and
                ce.Employee_ID = '$Employee_ID' and
                pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                pr.Registration_ID = pp.Registration_ID and
                pp.Sponsor_ID = sp.Sponsor_ID and
                ci.Check_In_ID = pp.Check_In_ID and
                ppl.Status = 'active' and
                Check_In_Type = 'doctor room' and
                DATE(ci.Check_In_Date_And_Time) = CURDATE() and
                Patient_Direction = 'Direct To Clinic Via Nurse Station'
                ORDER BY Patient_Payment_Item_List_ID desc") or die(mysqli_error($conn));    
		 
    $temp = 1;
    while($row = mysqli_fetch_array($select_Filtered_Patient)){
	
	//AGE FUNCTION
	 $age = floor( (strtotime(date('Y-m-d')) - strtotime($row['Date_Of_Birth'])) / 31556926)." Years";
	   // if($age == 0){
		
		$date1 = new DateTime($Today);
		$date2 = new DateTime($row['Date_Of_Birth']);
		$diff = $date1 -> diff($date2);
		$age = $diff->y." Years, ";
		$age .= $diff->m." Months, ";
		$age .= $diff->d." Days";
    ?>
	    <tr>
		<td id='thead'><?php echo $temp; ?></td>
		<td>
		    <a href='doctorspageoutpatientwork.php?Registration_ID=<?php echo $row['Registration_ID']; ?>&Patient_Payment_ID=<?php echo $row['Patient_Payment_ID']; ?>&Patient_Payment_Item_List_ID=<?php echo $row['Patient_Payment_Item_List_ID']; ?>&NR=True&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'><?php echo ucwords(strtolower($row['Patient_Name'])); ?></a>
		</td>
		<td>
		    <a href='doctorspageoutpatientwork.php?Registration_ID=<?php echo $row['Registration_ID']; ?>&Patient_Payment_ID=<?php echo $row['Patient_Payment_ID']; ?>&Patient_Payment_Item_List_ID=<?php echo $row['Patient_Payment_Item_List_ID']; ?>&NR=True&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'><?php echo $row['Registration_ID']; ?></a>
		</td>
		<td>
		    <a href='doctorspageoutpatientwork.php?Registration_ID=<?php echo $row['Registration_ID']; ?>&Patient_Payment_ID=<?php echo $row['Patient_Payment_ID']; ?>&Patient_Payment_Item_List_ID=<?php echo $row['Patient_Payment_Item_List_ID']; ?>&NR=True&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'><?php echo $age; ?></a>
		</td>
		<td>
		    <a href='doctorspageoutpatientwork.php?Registration_ID=<?php echo $row['Registration_ID']; ?>&Patient_Payment_ID=<?php echo $row['Patient_Payment_ID']; ?>&Patient_Payment_Item_List_ID=<?php echo $row['Patient_Payment_Item_List_ID']; ?>&NR=True&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'><?php echo $row['Gender']; ?></a>
		</td>
		<td>
		    <a href='doctorspageoutpatientwork.php?Registration_ID=<?php echo $row['Registration_ID']; ?>&Patient_Payment_ID=<?php echo $row['Patient_Payment_ID']; ?>&Patient_Payment_Item_List_ID=<?php echo $row['Patient_Payment_Item_List_ID']; ?>&NR=True&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'><?php echo $row['Guarantor_Name']; ?></a>
		</td>
		<td>
		    <a href='doctorspageoutpatientwork.php?Registration_ID=<?php echo $row['Registration_ID']; ?>&Patient_Payment_ID=<?php echo $row['Patient_Payment_ID']; ?>&Patient_Payment_Item_List_ID=<?php echo $row['Patient_Payment_Item_List_ID']; ?>&NR=True&PatientBilling=PatientBillingThisForm' target='_parent' style='text-decoration: none;'><?php echo $row['Check_In_Date_And_Time']; ?></a>
		</td>

    <?php
	$temp++;
	echo "</tr>";
    }
?>
	    </table>
        </center>
</fieldset><br/>
<script>
    function Select_Patients_List_Type(List_Type) {
	    if(window.XMLHttpRequest) {
		    myObjectPatientList = new XMLHttpRequest();
	    }else if(window.ActiveXObject){ 
		    myObjectPatientList = new ActiveXObject('Micrsoft.XMLHTTP');
		    myObjectPatientList.overrideMimeType('text/xml');
	    }
		  $("#loader").show();  
	    myObjectPatientList.onreadystatechange = function (){
		    data80 = myObjectPatientList.responseText;
		    if (myObjectPatientList.readyState == 4) {
			document.getElementById('Patients_List_Area').innerHTML = data80;
                        $("#loader").hide();  
		    }
	    }; //specify name of function that will handle server response........
		    
	    myObjectPatientList.open('GET','Select_Patients_List_Type.php?List_Type='+List_Type,true);
	    myObjectPatientList.send();
	} 
        function filterPatient(){
           var direct_to_clinic= $("#Patient_Direction2").val()
           var direct_to_doctor= $("#Patient_Direction").val()
           var patient_direction;
           if($('#Patient_Direction2').is(':checked')) { 
              patient_direction=direct_to_clinic;
           }
           if($('#Patient_Direction').is(':checked')) { 
               patient_direction=direct_to_doctor;
           }
          var Date_From=$("#Date_From").val();
          var Date_To=$("#Date_To").val();
          var uri="Select_Patients_List_TypeByDate.php";
          $("#loader").show();  
           $.ajax({
                type: 'GET',
                url: uri,
                data: {patient_direction : patient_direction,Date_From:Date_From,Date_To:Date_To},
                success: function(data){
                    document.getElementById('Patients_List_Area').innerHTML = data;
                    $("#loader").hide(); 
                   // alert(data)
                },
                error: function(){
                        alert("error");
                }
            });
        }
</script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#PatientsList').DataTable({
            "bJQueryUI": true

        });

        $('#Date_From').datetimepicker({
            dayOfWeekStart: 1,
            lang: 'en',
            //startDate:    'now'
        });
        $('#Date_From').datetimepicker({value: '', step: 30});
        $('#Date_To').datetimepicker({
            dayOfWeekStart: 1,
            lang: 'en',
            //startDate:'now'
        });
        $('#Date_To').datetimepicker({value: '', step: 30});
    });
</script>

<link rel="stylesheet" type="text/css" href="css/jquery.datetimepicker.css"/>
<link rel="stylesheet" href="media/css/jquery.dataTables.css" media="screen">
<link rel="stylesheet" href="media/themes/smoothness/dataTables.jqueryui.css" media="screen">
<script src="media/js/jquery.js" type="text/javascript"></script>
<script src="media/js/jquery.dataTables.js" type="text/javascript"></script>
<script src="css/jquery.datetimepicker.js" type="text/javascript"></script>
<script src="css/jquery-ui.js"></script>
<?php
    include("./includes/footer.php");
?>