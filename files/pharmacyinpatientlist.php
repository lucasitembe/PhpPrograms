<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    $temp = 0;
    if(!isset($_SESSION['userinfo'])){
    	@session_destroy();
    	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    
    if(isset($_SESSION['userinfo'])){
    	if(isset($_SESSION['userinfo']['Pharmacy'])){
    	    if($_SESSION['userinfo']['Pharmacy'] != 'yes'){
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

    
	if(isset($_SESSION['userinfo'])){
		if($_SESSION['userinfo']['Pharmacy'] == 'yes'){ 
			echo "<a href='pharmacyworkspage.php?section=Pharmacy&PharmacyWorks=PharmacyWorksThisPage' class='art-button-green'>BACK</a>";
		}
	}
?>

<!-- new date function (Contain years, Months and days)--> 
<?php
    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
    $age ='';
    }
?>
<!-- end of the function -->


   
  
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
    <legend align='right' style="background-color:#006400;color:white;padding:5px;"><b>AdHOC CONTINUING INPATIENT LIST</b></legend>
        <table width=100% border=1>
            <tr id='thead'>
    		    <td width=5%><b>SN</b></td>
    		    <td><b>PATIENT NAME</b></td>
    		    <td style='text-align: left; width: 10%;'><b>PATIENT NUMBER</b></td>
                <td style='text-align: left width: 10%;;'><b>MEMBER NUMBER</b></td>
                <td style='text-align: left width: 10%;;'><b>SPONSOR</b></td>
                <td style='text-align: left; width: 13%;'><b>AGE</b></td>
                <td style='text-align: left; width: 5%;'><b>GENDER</b></td>
                <td style='text-align: left; width: 10%;'><b>PHONE NUMBER</b></td>
    		</tr>
    	    <tr><td colspan="8"><hr></td></tr>
<?php
    //select patients
    $select = mysqli_query($conn,"select pr.Registration_ID, pr.Patient_Name, pr.Gender, pr.Date_Of_Birth, sp.Guarantor_Name, pr.Phone_Number, pr.Member_Number from
                            tbl_sponsor sp, tbl_patient_registration pr, tbl_admission ad where
                            pr.Registration_ID = ad.Registration_ID and
                            sp.Sponsor_ID = pr.Sponsor_ID and
                            ad.Admission_Status = 'Admitted' and
                            ad.Discharge_Clearance_Status = 'not cleared' limit 200") or die(mysqli_error($conn));

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
?>
            <tr id='thead'>
                <td width=5%><?php echo ++$temp.'<b>.</b>'; ?></td>
                <td>
                    <a href='pharmacyinpatientpage.php?Registration_ID=<?php echo $data['Registration_ID']; ?>&PharmacyInpatientPage=PharmacyInpatientPageThisForm' target='_parent' style='text-decoration: none;'>
                        <?php echo $data['Patient_Name']; ?>
                    </a>
                </td>
                <td>
                    <a href='pharmacyinpatientpage.php?Registration_ID=<?php echo $data['Registration_ID']; ?>&PharmacyInpatientPage=PharmacyInpatientPageThisForm' target='_parent' style='text-decoration: none;'>
                        <?php echo $data['Registration_ID']; ?>
                    </a>
                </td>
                <td>
                    <a href='pharmacyinpatientpage.php?Registration_ID=<?php echo $data['Registration_ID']; ?>&PharmacyInpatientPage=PharmacyInpatientPageThisForm' target='_parent' style='text-decoration: none;'>
                        <?php echo $data['Member_Number']; ?>
                    </a>
                </td>
                <td>
                    <a href='pharmacyinpatientpage.php?Registration_ID=<?php echo $data['Registration_ID']; ?>&PharmacyInpatientPage=PharmacyInpatientPageThisForm' target='_parent' style='text-decoration: none;'>
                        <?php echo $data['Guarantor_Name']; ?>
                    </a>
                </td>
                <td>
                    <a href='pharmacyinpatientpage.php?Registration_ID=<?php echo $data['Registration_ID']; ?>&PharmacyInpatientPage=PharmacyInpatientPageThisForm' target='_parent' style='text-decoration: none;'>
                        <?php echo $age; ?>
                    </a>
                </td>
                <td>
                    <a href='pharmacyinpatientpage.php?Registration_ID=<?php echo $data['Registration_ID']; ?>&PharmacyInpatientPage=PharmacyInpatientPageThisForm' target='_parent' style='text-decoration: none;'>
                        <?php echo $data['Gender']; ?>
                    </a>
                </td>
                <td>
                    <a href='pharmacyinpatientpage.php?Registration_ID=<?php echo $data['Registration_ID']; ?>&PharmacyInpatientPage=PharmacyInpatientPageThisForm' target='_parent' style='text-decoration: none;'>
                        <?php echo $data['Phone_Number']; ?>
                    </a>
                </td>
            </tr>
<?php
        }
        echo '<tr><td colspan="8"><hr></td></tr>';
    }
?>
        </table>
</fieldset>
<table width="100%">
	<tr> 
		<td style="text-align: right;" id="Report_Button_Area">
			 
		</td>
	</tr>
</table>

<!--popup window -->
<div id="Display_Details" style="width:50%;" >
    <center id='Details_Area'>
	<table width=100% style='border-style: none;'>
	    <tr>
		<td>
		    
		</td>
	    </tr>
	</table>
    </center>
</div>


<script>
    function open_Dialog(Employee_ID,Date_From,Date_To,Billing_Type,Sponsor_ID){
	if(window.XMLHttpRequest){
	    myObjectGetDetails = new XMLHttpRequest();
	}else if(window.ActiveXObject){
	    myObjectGetDetails = new ActiveXObject('Micrsoft.XMLHTTP');
	    myObjectGetDetails.overrideMimeType('text/xml');
	}
	myObjectGetDetails.onreadystatechange = function (){
	    data29 = myObjectGetDetails.responseText;
	    if (myObjectGetDetails.readyState == 4) {
		document.getElementById('Details_Area').innerHTML = data29;
		$("#Display_Details").dialog("open");
	    }
	}; //specify name of function that will handle server response........
	
	myObjectGetDetails.open('GET','generalperformancereportdetails.php?Employee_ID='+Employee_ID+'&Date_From='+Date_From+'&Date_To='+Date_To+'&Billing_Type='+Billing_Type+'&Sponsor_ID='+Sponsor_ID,true);
	myObjectGetDetails.send();
    }
</script>

<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>
<!--<script src="script.js"></script>-->
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
<!--<script src="script.responsive.js"></script>-->

<script>
   $(document).ready(function(){
      $("#Display_Details").dialog({ autoOpen: false, width:'90%',height:500, title:'TRANSACTIONS DETAIL',modal: true});      
   });
</script>
<!-- end popup window -->

<script>
    function filter_list(){
		var Date_From = document.getElementById("Date_From").value;
		var Date_To = document.getElementById("Date_To").value;
		var Employee_ID = document.getElementById("Employee_ID").value;
		var Sponsor_ID = document.getElementById("Sponsor_ID").value;

        if (Date_From != null && Date_From != '' && Date_To != null && Date_To != ''){
            if(window.XMLHttpRequest) {
                myObject2 = new XMLHttpRequest();
            }else if(window.ActiveXObject){
                myObject2 = new ActiveXObject('Micrsoft.XMLHTTP');
                myObject2.overrideMimeType('text/xml');
            }
            
            myObject2.onreadystatechange = function (){
                data2 = myObject2.responseText;
                if (myObject2.readyState == 4) {
                    document.getElementById('Fieldset_List').innerHTML = data2;
                    Display_Report_Button();
                }
		    }; //specify name of function that will handle server response........
	        
	        myObject2.open('GET','Patient_Registration_Performance.php?Date_From='+Date_From+'&Date_To='+Date_To+'&Employee_ID='+Employee_ID+'&Sponsor_ID='+Sponsor_ID,true);
	        myObject2.send();
	    
        }else{
            if(Date_From == '' || Date_From == null){
                document.getElementById("Date_From").style = 'border: 3px solid red';
            }
            if(Date_To =='' || Date_To == null){
                document.getElementById("Date_To").style = 'border: 3px solid red';
            }
        }
    }
</script>


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
        myObjectSearchPatient.open('GET','Inpatient_Search_Patients_Pharmacy.php?Sponsor_ID='+Sponsor_ID,true);
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
        myObjectSearchPatient.open('GET','Inpatient_Search_Patients_Pharmacy.php?Sponsor_ID='+Sponsor_ID+'&Patient_Name='+Patient_Name,true);
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
        myObjectSearchPatient.open('GET','Inpatient_Search_Patients_Pharmacy.php?Sponsor_ID='+Sponsor_ID+'&Patient_Number='+Patient_Number,true);
        myObjectSearchPatient.send();
    }
</script>

<?php
    include("./includes/footer.php");
?>