
<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Reception_Works'] == 'yes'){ 
?>
    <a href='searchvisitorsoutpatientlist.php?SearchVisitorsOutPatientList=SearchVisitorsOutPatientListThisPage' class='art-button-green'>
        VISITORS
    </a>
<?php  } } ?>

<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Reception_Works'] == 'yes'){ 
?>
    <a href='visitorform.php?RegisterPatient=RegisterPatientThisPage' class='art-button-green'>
        BACK
    </a>
<?php  } } ?>



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
                    $('#Patient_Picture').attr('src',e.target.result).width('50%').height('140');
		};
		reader.readAsDataURL(input.files[0]);
	}
    }
    function clearPatientPicture() {
        document.getElementById('Patient_Picture_td').innerHTML="<img src='./patientImages/default.png' id='Patient_Picture' name='Patient_Picture' width=50% height='140'>"
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
	    document.getElementById("date2").removeAttribute('disabled');
	    document.getElementById("Gender").innerHTML = "<option></option><option>Male</option><option>Female</option>";
	    document.getElementById("Member_Number").setAttribute('style','border-color:default;width: 150px;text-align: left;');
	}
    } 

</script>
<script src="js/token.js"></script>
<script>
    function MemberNumberMandate(sponsor){
        $.ajax({
            url: "./MemberNumberMandateStatus.php?sponsor="+sponsor,
            type: "GET"
        }).done(function(result){
            if( result.replace(" ",'') == "Mandatory"){
                document.getElementById('Member_Number').setAttribute('required','required');
            }else{
                document.getElementById('Member_Number').removeAttribute('required');
            }
        });
    }
</script>
<br/><br/>


<fieldset>
            <legend align=center><b>PATIENT REGISTRATION</b></legend><br/>
        <center>
            <table width=100%>
                <tr>
                    <td width=35%>
                        <table width=100%>
                            <tr>
                                <td style='text-align: right'><b style='color: red'>Sponsor</td>
                                <td><select name='Guarantor_Name' id='Guarantor_Name' required='required' style='border-color: red' onchange='MemberNumberMandate(this.value);setVerify(this.value)'>
                                    <option selected='selected'></option>
                                        <?php
                                            $data = mysqli_query($conn,"select * from tbl_sponsor where Exemption = 'yes'");
                                            while($row = mysqli_fetch_array($data)){
                                                if($row['Guarantor_Name']=='MSAMAHA'){
                                                    echo '<option selected="selected">'.$row['Guarantor_Name'].'</option>';
                                                }else{
                                                    echo '<option>'.$row['Guarantor_Name'].'</option>';
                                                }
                                            }
                                        ?>
                                    </select>
                                    </td>
                                </tr>
                            <tr>
                                <td style='text-align: right'>Member Number</td>
                                <td><input type='text' name='Member_Number' autocomplete='off' id='Member_Number' style="width: 150px;text-align: left;">
                                    <input type="button" value="NHIF-eVerify" id='eVerify_btn' onclick="verifyNHIF2();" class="art-button-green" style="text-align: right;visibility: hidden;"/>
                                </td>
                            </tr>
                            <tr>
                                <td style='text-align: right'>Member Card Expire Date</td>
                                <td><input type='text' name='Member_Card_Expire_Date' autocomplete='off' id='date'></td> 
                            </tr>
                            <tr>
                                <td style='text-align: right'>New Regiatration Number</td>
                                <td><input type='text' name='New_Registration_Number' disabled='disabled' id='New_Registration_Number'></td> 
                            </tr>
                            <tr>
                                <td style='text-align: right'>Old Registration Number</td>
                                <td><input type='text' name='Old_Registration_Number' autocomplete='off' id='Old_Registration_Number'></td> 
                            </tr>
                            <tr>
                               <td style='text-align: right'>Title</td>
                               <td>
                                <select name='Patient_Title' id='Patient_Title'>
                                    <option selected='selected'></option>
                                    <option>Mr</option>
				    <option>Mrs</option>
                                    <option>Miss</option>
                                    <option>Dr</option>
                                    <option>Prof</option>
                                </select>
                               </td>
                            </tr>
                            <?php if(isset($_SESSION['systeminfo']['Registration_Mode']) && strtolower($_SESSION['systeminfo']['Registration_Mode']) <> 'receiving patient names together'){ ?>
                            <tr>
                                <td style='text-align: right'><b style='color: red'>Patient First Name</td>
                                <td><input type='text' name='Patient_First_Name' autocomplete='off' id='Patient_First_Name' style='border-color: red' required='required' placeholder='Patient First Name'></td>
                            </tr>
                            <tr>
                                <td style='text-align: right'><b <?php if(strlen($_SESSION['systeminfo']['Registration_Mode']) == 58){ echo "style='color: red'"; } ?>>Patient Middle Name</td>
                                <td><input type='text' name='Patient_Middle_Name' autocomplete='off' id='Patient_Middle_Name' <?php if(strlen($_SESSION['systeminfo']['Registration_Mode']) == 58){ echo "required='required' style='border-color: red'"; } ?> placeholder='Patient Middle Name'></td>
                            </tr>
                            <tr>
                                <td style='text-align: right'><b style='color: red'>Patient Last Name</td>
                                <td><input type='text' name='Patient_Last_Name' autocomplete='off' id='Patient_Last_Name' style='border-color: red' required='required' placeholder='Patient Last Name'></td>
                            </tr>
                                <?php }else{ ?>
                            <tr>
                                <td style='text-align: right'><b style='color: red'>Patient Name</td>
                                <td><input type='text' name='Patient_Name' autocomplete='off' id='Patient_Name' style='border-color: red' required='required'></td>
                            </tr>
                                <?php } ?>
                            <tr>
                                <td style='text-align: right'><b style='color: red'>Date Of Birth</td>
                                <td><input type='text' name='Date_Of_Birth' readonly="readonly"autocomplete='off' id='date2' style='border-color: red;width:70%' required='required' />&nbsp;&nbsp;<input type='text' autocomplete='off' id='datetime' oninput='calculatedate(this.value)' style='width:25%;text-align:center' placeholder="enter age" maxlength="3" class="numberonly" /></td>
                            </tr>
                            <tr>
                                <td style='text-align: right'><b style='color: red'>Gender</td>
                                <td id='gender_dom'>
                                    <select name='Gender' id='Gender' required='required' style='border-color: red' >
                                        <option selected='selected'></option>
					<option>Male</option>
                                        <option>Female</option>
                                    </select>
                                </td>
                            </tr>
			    <tr>
                                <td style='text-align: right'><b <?php if(isset($_SESSION['systeminfo']['Require_Patient_Phone_Number']) && strtolower($_SESSION['systeminfo']['Require_Patient_Phone_Number']) == 'yes'){ echo "style='color: red'"; } ?>>Patient Phone Number</b></td>
                                <td><input type='text' name='Phone_Number' autocomplete='off' id='Phone_Number' <?php if(isset($_SESSION['systeminfo']['Require_Patient_Phone_Number']) && strtolower($_SESSION['systeminfo']['Require_Patient_Phone_Number']) == 'yes'){ echo "required='required'  style='border-color: red'"; } ?> onfocus="addCode()"></td> 
                </tr>
                            <tr>
                                <td style='text-align: right'>Occupation</td>
                                <td><input type='text' name='Occupation' autocomplete='off' id='Occupation'></td>
                            </tr>
                        </table>
                    </td>
                    <td width=35%><table width=100%>
						<tr>
							<td style='text-align: right'>Country</td>
								<td >
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
                                <td>
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
                                    <select name='District' id='District' required='required'>
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
                            <tr>
                                <td style='text-align: right'>Ward</td>
                                <td>
								<input type='text' autocomplete='off' name='Ward' id='Ward'>
                                </td> 
                            </tr>
                            <tr>
                                <td style='text-align: right'>E-Mail</td>
                                <td><input type='text' name='Email' autocomplete='off' id='Email'></td> 
                            </tr>
                            <tr>
                                <td style='text-align: right;'>Emergency Contact Name</td>
                                <td><input type='text' name='Emergence_Contact_Name' autocomplete='off'  id='Emergence_Contact_Name'></td>
                            </tr>
                            <tr>
                                <td style='text-align: right;'>Emergency Contact Number</td>
                                <td><input type='text' name='Emergence_Contact_Number' autocomplete='off'  id='Emergence_Contact_Number'></td>
                            </tr>
                            <tr>
                                <td style='text-align: right'>Patient Vote Number</td>
                                <td><input type='text' name='Employee_Vote_Number' autocomplete='off' id='Employee_Vote_Number'></td>
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
							
							<tr>
                                <td style='text-align: right'>PF3 Patient</td>
                                <td><input type='checkbox'  id="pf3_patient" name="pf3_patient" onclick=""></td>
                            </tr>
                        </table>
                    </td>
                    <td width=30%>
                        <table width=100%>
                            <tr><td style='text-align: center;'>Patient Picture</td></tr>
                            <tr>
                                <td id='Patient_Picture_td' style='text-align: center;'>
                                    <img src='./patientImages/default.png' id='Patient_Picture' name='Patient_Picture' id='Patient_Picture' width=50% height="140">
                                </td>
                            </tr>
                            <tr>
                                <td>
									<center>
										SELECT PICTURE<input type="file" name="Patient_Picture" id="file" onchange='readImage(this)' title='SELECT PATIENT PICTURE' />
									</center>
                                </td>
                            </tr>
                           <tr>
							
								<td style="text-align:center;"><input type="checkbox" id="" name="" /><b>Take a picture</b></td>
						   </tr>
                        </table>    
                    </td>
                </tr>
            </table>
        </center>
</fieldset><br/>
<script>
    function calculatedate(age) {
        $.ajax({
            type: 'GET',
            url: 'getinfos.php',
            data: 'getage=' + age,
            cache: false,
            beforeSend: function (xhr) {
                $("#date2").attr('readonly', true);
            },
            success: function (html) {
                $("#date2").val(html);
            },
            complete: function (jqXHR, textStatus) {
                $("#date2").attr('readonly', false);
            },
            error: function (html) {

            }
        });
    }
</script>



