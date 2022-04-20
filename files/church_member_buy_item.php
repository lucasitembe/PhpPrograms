
<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Reception_Works'])){
	    if($_SESSION['userinfo']['Reception_Works'] != 'yes'){
		header("Location: ./index.php?InvalidPrivilege=yes");
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
    }

    if(isset($_GET['Section'])){
        $Section = $_GET['Section'];
    }else{
        $Section = '';
    }
?>

<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Reception_Works'] == 'yes'){ 
?>
    <a href='searchvisitorsoutpatientlist.php?SearchVisitorsOutPatientList=SearchVisitorsOutPatientListThisPage' class='art-button-green'>
        REGISTERED CUSTOMERS
    </a>
<?php  } } ?>

<?php
    /*if(strtolower($Section) == 'reception' || strtolower($Section) == 'visitor'){
        echo "<a href='patientslist.php?Section=".$Section."&NewVisitors=NewVisitorsThisPage' class='art-button-green'>BACK</a>";
    }else{
        echo "<a href='visitorform.php?RegisterPatient=RegisterPatientThisPage' class='art-button-green'>BACK</a>";
    }*/
?>
<a href="#" onclick="history.go(-1)" class="art-button-green">BACK</a>

<style type="text/css">
    .hiddenField {
        display: none;
    }
</style>

<script type='text/javascript'>
    function access_Denied(){ 
   alert("Access Denied");
   document.location = "./index.php";
    }
</script>

<script type="text/javascript">
    function readImage(input){
	if(input.files && input.files[0]) {
	    var reader = new FileReader();
		reader.onload = function(e){
                    $('#Patient_Picture').attr('src',e.target.result).width('50%').height('70%');
		};
		reader.readAsDataURL(input.files[0]);
	}
    }
    function clearPatientPicture() {
        document.getElementById('Patient_Picture_td').innerHTML="<img src='./patientImages/default.png' id='Patient_Picture' name='Patient_Picture' width=50% height=50%>"
    }
</script>

<script language="javascript" type="text/javascript">
    function searchEmployee(Employee_Name){
        document.getElementById('Search_Iframe').innerHTML = "<iframe width='100%' height=100% src='viewpatientsIframe.php?Employee_Name="+Employee_Name+"'></iframe>";
    }
</script>


<script type="text/javascript" language="javascript">
    function getDistricts() {
        var Region_Name = document.getElementById("region").value;
	    if(window.XMLHttpRequest) {
		mm = new XMLHttpRequest();
	    }
	    else if(window.ActiveXObject){ 
		mm = new ActiveXObject('Micrsoft.XMLHTTP');
		mm.overrideMimeType('text/xml');
	    }
	    
	    mm.onreadystatechange= AJAXP; //specify name of function that will handle server response....
	    mm.open('GET','GetDistricts.php?Region_Name='+Region_Name,true);
	    mm.send();
	}
    function AJAXP() {
	var data = mm.responseText;
	document.getElementById('District').innerHTML = data;	
    }
    
//    function to verify NHIF STATUS
    function nhifVerify(){
	//code
    }
</script>

<script type="text/javascript">
    function get_Regions(){
        var country = document.getElementById("country").value;

        if (window.XMLHttpRequest) {
            myObjectRegs = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectRegs = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectRegs.overrideMimeType('text/xml');
        }

        myObjectRegs.onreadystatechange = function () {
            dataReg = myObjectRegs.responseText;
            if (myObjectRegs.readyState == 4) {
                document.getElementById('region').innerHTML = dataReg;
                getDistricts();
            }
        }; //specify name of function that will handle server response........
        myObjectRegs.open('GET', 'get_Regions.php?country='+country, true);
        myObjectRegs.send();
    }
</script>

<!--		NHIF VERIFICATION FUNCTION		-->
<script type="text/javascript" language="javascript">
    //get verification button
    function setVerify(sponsor){
	if (sponsor=='NHIF') {
	    document.getElementById('eVerify_btn').style.visibility = "";
	}else{
	    document.getElementById('eVerify_btn').style.visibility = "hidden";
	    document.getElementById("Patient_Name").value = '';
	    document.getElementById("Patient_Name").removeAttribute('readonly');
	    document.getElementById("Employee_Vote_Number").value = '';
	    document.getElementById("Employee_Vote_Number").removeAttribute('readonly');
	    document.getElementById("date").value = '';
	    document.getElementById("date").removeAttribute('disabled');
	    document.getElementById("date2").value = '';
        document.getElementById("datetime").value = '';
	    document.getElementById("date2").removeAttribute('disabled');
	    document.getElementById("Gender").innerHTML = "<option></option><option>Male</option><option>Female</option>";
	    document.getElementById("Member_Number").setAttribute('style','border-color:default;width: 150px;text-align: left;');
	}
    } 

</script>
<script src="js/token.js"></script>
<script>
    function MemberNumberMandate(sponsor){
        var Insurance = document.getElementById("Guarantor_Name").value;
        $.ajax({
            url: "./MemberNumberMandateStatus.php?sponsor="+sponsor,
            type: "GET"
        }).done(function(result){
            if(result.replace(" ",'') == "Mandatory"){
                document.getElementById('Member_Number').setAttribute('required','required');
                if(Insurance == 'NHIF'){
                    document.getElementById('Employee_Vote_Number').setAttribute('required','required');
                }else{
                    document.getElementById('Employee_Vote_Number').removeAttribute('required');
                }
            }else{
                document.getElementById('Member_Number').removeAttribute('required');
                if(Insurance == 'NHIF'){
                    document.getElementById('Employee_Vote_Number').setAttribute('required','required');
                }else{
                    document.getElementById('Employee_Vote_Number').removeAttribute('required');
                }
            }
        });
    }
</script>
<br/><br/>

<script>
    //disable member number when cash is selected
    function disable_member_number(Guarantor_Name) {
	if (Guarantor_Name == 'CASH') {
	    document.getElementById("Member_Number").disabled='disabled';
	}else{
	    document.getElementById('Member_Number').removeAttribute('disabled');
	}
    }
</script>
<?php
if(isset($_GET['Registration_ID'])){
    $muumini_id=$_GET['Registration_ID'];
    $select_Filtered_Members = mysqli_query($conn,"SELECT * FROM tbl_taarifa_muumini WHERE muumini_id='$muumini_id'") or die(mysqli_error($conn));   
    while($row = mysqli_fetch_array($select_Filtered_Members)){
    $muumini_id = $row['muumini_id'];
    $Full_Name = $row['jina_kamili'];
    $mahali_zaliwa =  $row['mahali_zaliwa'];
    $tarehe_zaliwa =  $row['tarehe_zaliwa'];
    $tarehe_batizwa = $row['tarehe_batizwa'];
    $mahali_batizwa =  $row['mahali_batizwa'];
    $mahali_kipaimara =  $row['mahali_kipaimara'];
    $tarehe_kipaimara =  $row['tarehe_kipaimara'];
    $ushirika_kipaimara = $row['ushirika_kipaimara'];
    $dayosis_kipaimara =  $row['dayosis_kipaimara'];
    $shiriki_meza = $row['chakula_cha_bwana'];
    $aina_za_ndoa =  $row['aina_ndoa'];
    $ndoa_taarifa =  $row['taarifa_ndoa'];
    $jina_mke_mme =  $row['mume_mke'];
    $watoto_idadi = $row['watoto_idadi'];	
    $kazi_yangu = $row['kazi'];
    $kikundi_kipindi =  $row['kikundi_aina'];
    $mzee_kanisa =  $row['mzee_kanisa'];     
    $namba_simu =  $row['namba_simu'];   
    }
}
?>
<fieldset>
            <legend align=center><b>CUSTOMER REGISTRATION</b></legend><br/>
        <center>
            <table width=100%>
                <form action='#' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">
                <tr>
                    <td width=35%>
                        <table width=100%>
                            <tr>
                                <td style='text-align: right'><b style='color: red'>Sponsor</td>
                                <td>
                                <select name='Guarantor_Name' id='Guarantor_Nam' required  style='border-color: red' onchang='MemberNumberMandate(this.value);setVerify(this.value); disable_member_number(this.value); Modify_Patient_Name();'>
                                    <option selected='selected'></option>
                                        <?php
                                            if(isset($_SESSION['systeminfo']['Include_Exemption_Sponsors_In_Normal_Registration']) && strtolower($_SESSION['systeminfo']['Include_Exemption_Sponsors_In_Normal_Registration']) == 'yes'){
                                                $data = mysqli_query($conn,"select * from tbl_sponsor") or die(mysqli_error($conn));
                                            }else{
                                                $data = mysqli_query($conn,"select * from tbl_sponsor WHERE Exemption = 'no'") or die(mysqli_error($conn));
                                            }
                                            while($row = mysqli_fetch_array($data)){
                                                echo '<option value="'.$row['Guarantor_Name'].'">'.$row['Guarantor_Name'].'</option>';
                                            }
                                            //echo '<option value="CASH" selected>CASH</option>';
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr class = "hiddenField">
                                <td style='text-align: right'>Member Number</td>
                                <td><input type='text' name='Member_Number' autocomplete='off' id='Member_Number' style="width: 150px;text-align: left;">
                                    <input type="button" value="NHIF-eVerify" id='eVerify_btn' onclick="verifyNHIF3();" class="art-button-green" style="text-align: right;visibility: hidden;"/>
                                </td>
                            </tr>
                            <tr class = "hiddenField">
                                <td style='text-align: right'>Member Card Expire Date</td>
                                <td><input type='text' name='Member_Card_Expire_Date' autocomplete='off' id='date'></td> 
                            </tr>
                            <tr class = "hiddenField">
                                <td style='text-align: right'>New Regiatration Number</td>
                                <td><input type='text' name='New_Registration_Number' disabled='disabled' id='New_Registration_Number'></td> 
                            </tr>
                            <tr class = "hiddenField">
                                <td style='text-align: right'>Old Registration Number</td>
                                <td><input type='text' name='Old_Registration_Number' autocomplete='off' id='Old_Registration_Number'></td> 
                            </tr>
                            <tr class = "hiddenField">
                               <td style='text-align: right'>Title</td>
                               <td>
                                <select name='Patient_Title' id='Patient_Title'>
                                    <option selected='selected'></option>
                                    <option>Mr</option>
                                    <option>Mrs</option>
                                    <option>Miss</option>
                                    <option>Master</option>
                                    <option>Dr</option>
                                    <option>Prof</option>
                                    <option>Ms</option>
                                </select>
                               </td>
                            </tr>
                                <?php if(isset($_SESSION['systeminfo']['Registration_Mode']) && strtolower($_SESSION['systeminfo']['Registration_Mode']) <> 'receiving patient names together'){ ?>
                            <tr>
                                <td style='text-align: right'><b style='color: red' id='F_Area'>First Name</td>
                                <td><input type='text' name='Patient_Name' autocomplete='off' id='Patient_Name' style='border-color: red' required='required'></td>
                            </tr>
                            <tr>
                                <td style='text-align: right'><b <?php if(strlen($_SESSION['systeminfo']['Registration_Mode']) == 58){ echo "style='color: red'"; } ?> id='M_Area'>Middle Name</td>
                                <td><input type='text' name='Patient_Middle_Name' autocomplete='off' id='Patient_Middle_Name' <?php if(strlen($_SESSION['systeminfo']['Registration_Mode']) == 58){ echo "required='required' style='border-color: red'"; } ?>></td>
                            </tr>
                            <tr>
                                <td style='text-align: right'><b style='color: red' id='L_Area'>Last Name</td>
                                <td><input type='text' name='Patient_Last_Name' autocomplete='off' id='Patient_Last_Name' style='border-color: red' required='required'></td>
                            </tr>
                                <?php }else{ ?>
                            <tr>
                                <td style='text-align: right'><b style='color: red'>Customer Name</td>
                                <td><input type='text' <?php if(isset($_GET['Registration_ID'])){ echo "value='".$Full_Name."'"; }?> name='Patient_Name' autocomplete='off' id='Patient_Name' style='border-color: red' required='required'></td>
                            </tr>
                                <?php } ?>
                            <tr class = "hiddenField">
                                <td style='text-align: right'><b style='color: red'>Date Of Birth</td>
                                <td><input type='text' name='Date_Of_Birth' autocomplete='off' id='date2' style='border-color: red;width:70%'  />&nbsp;&nbsp;<input type='text' autocomplete='off' id='datetime' oninput='calculatedate(this.value)' style='width:25%;text-align:center' placeholder="enter age" class="numberonly" /></td>
                            </tr>
                            <tr class = "hiddenField">
                                <td style='text-align: right'><b style='color: red'>Gender</td>
                                <td id='gender_dom'>
                                    <select name='Gender' id='Gender' style='border-color: red' >
                                        <option selected='selected'></option>
					<option>Male</option>
                                        <option>Female</option>
                                    </select>
                                </td>
                            </tr>
			    <tr>
                                <td style='text-align: right'><b <?php if(isset($_SESSION['systeminfo']['Require_Patient_Phone_Number']) && strtolower($_SESSION['systeminfo']['Require_Patient_Phone_Number']) == 'yes'){ echo "style='color: red'"; } ?>>Phone Number</b></td>
                                <td><input type='text' <?php if(isset($_GET['Registration_ID'])){ echo "value='".$namba_simu."'"; }?> name='Phone_Number' autocomplete='off' id='Phone_Numbe' <?php if(isset($_SESSION['systeminfo']['Require_Patient_Phone_Number']) && strtolower($_SESSION['systeminfo']['Require_Patient_Phone_Number']) == 'yes'){ echo "required='required'  style='border-color: red'"; } ?> onfocu="addCode()"></td> 
			    </tr>
                <tr>
                                <td style='text-align: right'>E-Mail</td>
                                <td><input type='email' name='Email' autocomplete='off' id='Email'></td> 
                            </tr>
                            <tr class = "hiddenField">
                                <td style='text-align: right;'>Emergency Contact Name</td>
                                <td><input type='text' name='Emergence_Contact_Name' autocomplete='off'  id='Emergence_Contact_Name'></td>
                            </tr>
                            <tr class = "hiddenField">
                                <td style='text-align: right'>Employee Vote</td>
                                <td><input type='text' name='Employee_Vote_Number' autocomplete='off' id='Employee_Vote_Number'></td>
                            </tr>
							<tr class = "hiddenField">
                                <td style='text-align: right'>Transfered Patient</td>
                                <td><input type='checkbox'  id="pf3_patient" name="pf3_patient" onclick=""></td>
                            </tr>
                        </table>
                    </td>
                    <td width=35%><table width=100%>
                        <tr>
                        <td style='text-align: right'>Country</td>
                        <td>
                        <select name='country' id='country' onchange='get_Regions()'>
                        <?php
                                $data = mysqli_query($conn,"select Country_Name, Country_ID, (select Country_ID from tbl_regions where Region_Status = 'Selected') as Country_ID2 from tbl_country ORDER BY Country_ID ASC");
                                        while($row = mysqli_fetch_array($data)){
                        ?>
                                <option value='<?php echo $row['Country_Name'];?>' <?php if($row['Country_ID'] == $row['Country_ID2']){ echo "selected='selected'"; } ?>><?php echo $row['Country_Name']; ?></option>
                        <?php
                                     }
                        ?>
                        </select>
                        </td>
                        </tr>
                            <tr>			
                                <td style='text-align: right'>Region</td>
                                <td id="Regions_Area">
                                <?php
                                    //get initial region
                                    $Control_Region = 'yes';
                                    $slct = mysqli_query($conn,"select Region_ID, Region_Name from tbl_regions where Region_Status = 'Selected'") or die(mysqli_error($conn));
                                    $num = mysqli_num_rows($slct);
                                    if($num > 0){
                                        while ($data = mysqli_fetch_array($slct)) {
                                            $Selected_Region = $data['Region_Name'];
                                            $Region_ID = $data['Region_ID'];
                                        }
                                    }else{ // select Dar es salaam
                                        $Control_Region = 'no';
                                        $Selected_Region = 'Dar es salaam';
                                    }
                                ?>
                                    <select name='region' id='region' onchange='getDistricts()'>
                                        <option selected='selected'><?php echo $Selected_Region; ?></option>
										<?php
											$data = mysqli_query($conn,"select * from tbl_regions where Region_Status = 'Not Selected' and Country_ID = (select Country_ID from tbl_regions where Region_Status = 'Selected')");
												while($row = mysqli_fetch_array($data)){
											?>
											<option value='<?php echo $row['Region_Name'];?>'>
											<?php echo $row['Region_Name']; ?>
											</option>
										<?php
											}
										?>
                                    </select>
                                </td> 
                            </tr> 
                            <tr>
                                <td style='text-align: right'>District</td>
                                <td>
                                    <select name='District' id='District' >
                                        <option selected='selected'></option>
                                <?php if($Control_Region == 'no'){ ?>
										<option>Kinondoni</option>
                                        <option>Ilala</option>
                                        <option>Temeke</option>  
                                <?php 
                                    }else{ 
                                        $select_districts = mysqli_query($conn,"select District_Name from tbl_district where Region_ID = '$Region_ID'") or die(mysqli_error($conn));
                                        $num_districts = mysqli_num_rows($select_districts);
                                        if($num_districts > 0){
                                            while ($dt = mysqli_fetch_array($select_districts)) {
                                ?>
                                                <option><?php echo $dt['District_Name']; ?></option>
                                <?php
                                            }
                                        }
                                    } 
                                ?>
                                    </select>
                                </td> 
                            </tr>
                            <tr class = "hiddenField">
                                <td style='text-align: right'>Ward</td>
                                <td>
                            <input type='text' autocomplete='off' name='Ward' id='Ward'>
                                </td> 
                            </tr>
							
                        <tr class = "hiddenField">
                                <td style='text-align: right'>Tribe</td>
                                <td>
                                <input type='text' autocomplete='off' name='Tribe' id='Tribe'>
                                </td> 
                        </tr>
                            
                            <tr class = "hiddenField">
                                <td style='text-align: right;'>Emergency Contact Number</td>
                                <td><input type='text' name='Emergence_Contact_Number' autocomplete='off'  id='Emergence_Contact_Number'></td>
                            </tr>
							<tr class = "hiddenField">
                                <td style='text-align: right'>Occupation</td>
                                <td><input type='text' name='Occupation' autocomplete='off' id='Occupation'></td>
                            </tr>
                            
                            <tr>
                                <td style='text-align: right'>Company</td>
                                <td><input type='text' name='Company' id='Company' autocomplete='off'></td>
                            </tr>
                            <?php
                                if(isset($_SESSION['userinfo']['Employee_Name'])){
                                    $Employee_Name = $_SESSION['userinfo']['Employee_Name'];    
                                }else{
                                    $Employee_Name = "Unknown Employee";
                                }
                            ?>
                            <tr>
                                <td style='text-align: right'>Prepared By</td>
                                <td><input type='text' name='Prepared_By' disabled='disabled' id='Prepared_By' value='<?php echo $Employee_Name; ?>'></td>
                            </tr>
                            <tr class = "hiddenField">
				<td style="text-align: right;">
                                    <label for="Diseased">Diceased</label>
				</td>
                                <td style="text-align: left;">
                                    <input type="checkbox" name="Diseased" id="Diseased" value="true" onclick="confirm_message(this)">
                                </td>
                            </tr>
                            <tr >
                                <td style="text-align: right;">Registration Type</td>
                                <td>
                                    <select name="Registration_Type" id="Registration_Type">
                                        <option selected="selected" value="Normal">Normal Registration</option>
                                        <option value="Pre_Paid">Pre Paid Registration</option>
                                    </select>
                                </td>
                            </tr>
						</table>
                    </td>
                    <td width=30%>
                        <table width=100%>
                            <tr><td style='text-align: center;'>Costumer Picture</td></tr>
                            <tr>
                                <td id='Patient_Picture_td' style='text-align: center;'>
                                    <img src='./patientImages/default.png' id='Patient_Picture' name='Patient_Picture' id='Patient_Picture' width=50% height=50%>
                                </td>
                            </tr>
                            <tr>
                                <td>
				    <center>
					SELECT PICTURE<input type="file" name="Patient_Picture" id="file" onchange='readImage(this)' title='SELECT PATIENT PICTURE'/>
				    </center>
                                </td>
                            </tr>
                            <tr>
                                <td colspan=2 style='text-align: center;'>
                                    <input type='submit' name='submit' id='submit' value='   SAVE   ' style='width: 30%' class='art-button-green' onclick="Validate_Date()">
                                    <input type='reset' name='clear' id='clear' value=' CLEAR ' style='width: 30%' class='art-button-green' onclick='clearPatientPicture()'>
                                    <input type='hidden' name='submittedAddNewPatientForm' style='width: 30%' value='true'/>
                                </td>
                            </tr>
			    <!--<tr>
				<td style='text-align: right;'>
				    <br><input type='button' id='verify' name='verify' class='art-button-green' value='Member Number Status'>
				    <br><div id='dom_verify' name='dom_verify' onclick='nhifVerify()'>
				    Status:
				    </div>
				</td>
			    </tr>-->
                        </table>    
                    </td>
                </form>
                </tr>
            </table>
        </center>
</fieldset><br/>



<?php
    include("./includes/footer.php");
?>


<!--  insert data from the form  -->

<?php
    if(isset($_POST['submittedAddNewPatientForm'])){
        
        if(isset($_SESSION['userinfo'])){ 
           if(isset($_SESSION['userinfo']['Employee_ID'])){
                $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
           }else{
            $Employee_ID = 0;
           }
        }
	
        
        $Old_Registration_Number = mysqli_real_escape_string($conn,$_POST['Old_Registration_Number']);
        $Patient_Title = mysqli_real_escape_string($conn,$_POST['Patient_Title']);

        $Date_Of_Birth = mysqli_real_escape_string($conn,$_POST['Date_Of_Birth']);
        $Gender = mysqli_real_escape_string($conn,$_POST['Gender']);
        $Country = mysqli_real_escape_string($conn,$_POST['country']);
        $region = mysqli_real_escape_string($conn,$_POST['region']);
        $District = mysqli_real_escape_string($conn,$_POST['District']);
        $Ward = mysqli_real_escape_string($conn,$_POST['Ward']);
        $Tribe = mysqli_real_escape_string($conn,$_POST['Tribe']);
        $Guarantor_Name = mysqli_real_escape_string($conn,$_POST['Guarantor_Name']);
        $Member_Number = mysqli_real_escape_string($conn,$_POST['Member_Number']);
        $Member_Card_Expire_Date = mysqli_real_escape_string($conn,$_POST['Member_Card_Expire_Date']);
        $Phone_Number = mysqli_real_escape_string($conn,$_POST['Phone_Number']);
        $Email = mysqli_real_escape_string($conn,$_POST['Email']);
        $Occupation = mysqli_real_escape_string($conn,$_POST['Occupation']);
        $Employee_Vote_Number = mysqli_real_escape_string($conn,$_POST['Employee_Vote_Number']);
        $Emergence_Contact_Name = mysqli_real_escape_string($conn,$_POST['Emergence_Contact_Name']);
        $Emergence_Contact_Number = mysqli_real_escape_string($conn,$_POST['Emergence_Contact_Number']);
        $Company = mysqli_real_escape_string($conn,$_POST['Company']);

        if(isset($_SESSION['systeminfo']['Registration_Mode']) && strtolower($_SESSION['systeminfo']['Registration_Mode']) <> 'receiving patient names together'){
            $Patient_First_Name = mysqli_real_escape_string($conn,preg_replace('/\s+/', ' ', $_POST['Patient_Name']));
            $Patient_Middle_Name = mysqli_real_escape_string($conn,preg_replace('/\s+/', '', $_POST['Patient_Middle_Name']));
            $Patient_Last_Name = mysqli_real_escape_string($conn,preg_replace('/\s+/', '', $_POST['Patient_Last_Name']));
            if(strtolower($Guarantor_Name) == 'nhif'){
                $Patient_Name = $Patient_First_Name;
            }else{
                $Patient_Name = $Patient_First_Name.' '.$Patient_Middle_Name.' '.$Patient_Last_Name;
            }
        }else{
            $Patient_Name = mysqli_real_escape_string($conn,preg_replace('/\s+/', ' ', $_POST['Patient_Name']));
        }
        
        if(isset($_POST['Diseased'])) {
            $Diseased = 'yes'; 
        }else{
            $Diseased = 'no';
        }
        $Registration_Type = $_POST['Registration_Type'];

	//check if there is another patient based on entered member number
	$select_Membership_Id_Number_Status = mysqli_query($conn,"select Membership_Id_Number_Status from tbl_sponsor where Guarantor_Name = '$Guarantor_Name'") or die(mysqli_error($conn));
	$row = mysqli_fetch_assoc($select_Membership_Id_Number_Status);
	$Membership_Id_Number_Status = $row['Membership_Id_Number_Status'];
	
	if(strtolower($Membership_Id_Number_Status) == 'mandatory' ){
	    $check_info = mysqli_query($conn,"select * from tbl_patient_registration
					    where sponsor_id = (select Sponsor_ID from tbl_sponsor where Guarantor_Name = '$Guarantor_Name' limit 1) and
						Member_Number = '$Member_Number' limit 1") or die(mysqli_error($conn));
	    
	    $num_info = mysqli_num_rows($check_info);
	    if($num_info > 0){
		while($row = mysqli_fetch_array($check_info)){
		    $Temp_Patient_Name = $row['Patient_Name'];
		    $Temp_Date_Of_Birth = $row['Date_Of_Birth'];
		    $Temp_Gender = $row['Gender'];
		    $Temp_Emergence_Contact_Name = $row['Emergence_Contact_Name'];
		}
	    }else{
		$Temp_Patient_Name = '';
		$Temp_Date_Of_Birth = '';
		$Temp_Gender = '';
		$Temp_Emergence_Contact_Name = '';
	    }
	}else{
            $num_info = 0;
        }
	if($num_info > 0 ){
?>
	    <script>
		
		var Temp_Patient_Name = '<?php echo $Temp_Patient_Name; ?>';
		var Temp_Date_Of_Birth = '<?php echo $Temp_Date_Of_Birth; ?>';
		var Temp_Gender = '<?php echo $Temp_Gender; ?>';
		var Temp_Entered_Patient_Name = '<?php echo $Patient_Name; ?>';
		var Old_Registration_Number  = '<?php echo $Old_Registration_Number; ?>';
		var Patient_Title = '<?php echo $Patient_Title; ?>'; 
		var Patient_Name = '<?php echo $Patient_Name; ?>';
		var Date_Of_Birth = '<?php echo $Date_Of_Birth; ?>';
		var Gender = '<?php echo $Gender; ?>';
		var Country = '<?php echo $Country; ?>';
		var region = '<?php echo $region; ?>';
		var District = '<?php echo $District; ?>';
		var Ward = '<?php echo $Ward; ?>';
		var Tribe = '<?php echo $Tribe; ?>';
		var Guarantor_Name = '<?php echo $Guarantor_Name; ?>';
		var Member_Number = '<?php echo $Member_Number; ?>';
		var Member_Card_Expire_Date = '<?php echo $Member_Card_Expire_Date; ?>';
		var Phone_Number = '<?php echo $Phone_Number; ?>';
		var Email = '<?php echo $Email; ?>';
		var Occupation = '<?php echo $Occupation; ?>';
		var Employee_Vote_Number = '<?php echo $Employee_Vote_Number; ?>';
		var Emergence_Contact_Name = '<?php echo $Emergence_Contact_Name; ?>';
		var Emergence_Contact_Number = '<?php echo $Emergence_Contact_Number; ?>';
		var Company = '<?php echo $Company; ?>';
		
		alert('SORRY, PROCESS FAIL!!!\n\nMay be This Patient Already Registrered\n\n\nThis MEMBER NUMBER already used by\n Patient Name : '+Temp_Patient_Name+'\nDate of birth : '+Temp_Date_Of_Birth+'\nGender : '+Temp_Gender+'\n\nIf The Patient is Exactly ('+Temp_Patient_Name+') Select him/her from the registred list\nTo proceed with services.\nOthewise enter the member number correctly');
		
		document.getElementById("Patient_Name").value = Patient_Name;
		document.getElementById("date2").value = Date_Of_Birth;
		document.getElementById("Gender").value = Gender;
		document.getElementById("Country").value = Country;
		document.getElementById("Ward").value = Ward;
		document.getElementById("Tribe").value = Tribe;
		document.getElementById("Guarantor_Name").value = Guarantor_Name;
		document.getElementById("Member_Number").value = Member_Number;
		document.getElementById("date").value = Member_Card_Expire_Date;
		document.getElementById("Phone_Number").value = Phone_Number;
		document.getElementById("Email").value = Email;
		document.getElementById("Occupation").value = Occupation;
		document.getElementById("Employee_Vote_Number").value = Employee_Vote_Number;
		document.getElementById("Emergence_Contact_Name").value = Emergence_Contact_Name;
		document.getElementById("Emergence_Contact_Number").value = Emergence_Contact_Number;
		document.getElementById("Company").value = Company;
		document.getElementById("Old_Registration_Number").value = Old_Registration_Number;
		document.getElementById("Patient_Title").value = Patient_Title;
		document.getElementById("Member_Number").focus();
		document.getElementById('eVerify_btn').style.visibility = "";
	    </script>
<?php	}else{
	    if(isset($_SESSION['userinfo'])){
		if(isset($_SESSION['userinfo']['Employee_ID'])){
		    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
		}else{
		    $Employee_ID = 0;
		}
	    }
	   
	    //select patient registration date and time
	    $data = mysqli_query($conn,"select now() as Registration_Date_And_Time");
	    while($row = mysqli_fetch_array($data)){
		$Registration_Date_And_Time = $row['Registration_Date_And_Time'];
	    }
		
		
	    $Insert_Sql = "insert into tbl_patient_registration(
			Old_Registration_Number,Title,Patient_Name,
			    Date_Of_Birth,Gender,Country,Region,District,Ward,Tribe,
				Sponsor_ID,
				    Member_Number,Member_Card_Expire_Date,
					Phone_Number,Email_Address,Occupation,
					    Employee_Vote_Number,Emergence_Contact_Name,
						Emergence_Contact_Number,Company,
						    Employee_ID,Registration_Date_And_Time,District_ID,Registration_Date,Diseased)
	    
			values('$Old_Registration_Number','$Patient_Title','$Patient_Name',
			'$Date_Of_Birth',
			    '$Gender','$Country','$region','$District','$Ward','$Tribe',
			    (select Sponsor_ID from tbl_sponsor where Guarantor_Name = '$Guarantor_Name'),
			    '$Member_Number','$Member_Card_Expire_Date',
				'$Phone_Number','$Email','$Occupation',
				'$Employee_Vote_Number','$Emergence_Contact_Name',
				    '$Emergence_Contact_Number','$Company',
				    '$Employee_ID','$Registration_Date_And_Time',(select District_ID from tbl_district where District_Name = '$District'),(select now()),'$Diseased')";
		if(!mysqli_query($conn,$Insert_Sql)){
		    $error = '1062yes';
		    if(mysql_errno()."yes" == $error){
				    $controlforminput = 'not valid';
		    }else{
			die(mysqli_error($conn));
		    }
		}else{
		    $selectThisRecord = mysqli_query($conn,"select Registration_ID  from tbl_patient_registration where
			Patient_Name = '$Patient_Name' and
			    Emergence_Contact_Name = '$Emergence_Contact_Name' and
			    Registration_Date_And_Time = '$Registration_Date_And_Time' and
			    Date_Of_Birth = '$Date_Of_Birth'") or die(mysqli_error($conn));
		    
		    while($row = mysqli_fetch_array($selectThisRecord)){
			    $Registration_ID = $row['Registration_ID']; 
		    }
            if($Registration_Type == 'Pre_Paid'){
                echo "<script type='text/javascript'>
                    alert('CUSTOMER ADDED SUCCESSFULLY');
                    document.location = './postpaidform.php?Registration_ID=".$Registration_ID."&Section=".$Section."&Section=Reception&PostPaid=PostPaidThisForm';
                    </script>";
            }else{
    		    echo "<script type='text/javascript'>
    			    alert('CUSTOMER ADDED SUCCESSFULLY');
    			    document.location = './church_member_buy_item.php?Registration_ID=".$Registration_ID."&VisitorForm=VisitorFormThisPage';
    			    </script>";
            }
		}
	}
    }
?>
<script src="js/functions.js"></script>
<script src="pikaday.js"></script>
    <script>

    var picker = new Pikaday(
    {
        field: document.getElementById('xy'),
        firstDay: 1,
        minDate: new Date('1910-01-01'),
        maxDate: new Date('2020-12-31'),
        yearRange: [1910,2020]
    });

    </script>
	
	<script>
	 function addCode(){
	   document.getElementById('Phone_Number').value='255';
	 }
	</script>
<script>
    function confirm_message(state){
        if(state.checked){
            if(confirm("Patient is registered as diseased. Do you want to continue?")){
                state.checked = true;
            }else{
                state.checked = false;
            }
        }
    }
</script>

<script type="text/javascript">
    function Modify_Patient_Name(){
        var Guarantor_Name = document.getElementById("Guarantor_Name").value;
        <?php if(isset($_SESSION['systeminfo']['Registration_Mode']) && strtolower($_SESSION['systeminfo']['Registration_Mode']) <> 'receiving patient names together'){ ?>
            if(Guarantor_Name == 'NHIF'){
                document.getElementById("F_Area").innerHTML = 'Patient Name';
                document.getElementById("M_Area").innerHTML = '';
                document.getElementById("L_Area").innerHTML = '';
                document.getElementById("Patient_Middle_Name").removeAttribute("required");
                document.getElementById("Patient_Last_Name").removeAttribute("required");
                //document.getElementById("Patient_Middle_Name").value = '';
                //document.getElementById("Patient_Last_Name").value = '';
                document.getElementById('Patient_Middle_Name').style.visibility = "hidden";
                document.getElementById('Patient_Last_Name').style.visibility = "hidden";
            }else{
                document.getElementById("F_Area").innerHTML = 'Patient First Name';
                document.getElementById("M_Area").innerHTML = 'Patient Middle Name';
                document.getElementById("L_Area").innerHTML = 'Patient Last Name';
                document.getElementById("Patient_Middle_Name").value = '';
                document.getElementById("Patient_Last_Name").value = '';
                document.getElementById('Patient_Middle_Name').style.visibility = "visible";
                document.getElementById('Patient_Last_Name').style.visibility = "visible";
                document.getElementById("Patient_Last_Name").setAttribute('required','required');
            }
        <?php } ?>
    }
</script>

<script>
    function Validate_Date(){
        var Today = new Date(); //current date
        var Date_Of_Birth = new Date(document.getElementById("date2").value);
        var Initial_Date = new Date("1900, 01, 01");

        if(Date_Of_Birth < Initial_Date || Date_Of_Birth > Today){
            alert("Invalid Date Of Birth");
            document.getElementById("date2").value = '';
            document.getElementById("datetime").value = '';
        }
    }
</script>
<script>
 function calculatedate(age){
   $.ajax({
            type: 'GET',
            url: 'getinfos.php',
            data: 'getage=' + age ,
            cache: false,
            beforeSend: function (xhr) {
               $("#date2").attr('readonly',true);
            },
            success: function (html) {
               $("#date2").val(html);
            },
            complete: function (jqXHR, textStatus) {
                 $("#date2").attr('readonly',false);
            }, 
            error: function (html) {
         
            }
        });
}
</script>
