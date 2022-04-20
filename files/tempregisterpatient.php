<?php
    include("./includes/header.php");
    include("./includes/connection.php");
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
?>

<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Revenue_Center_Works'] == 'yes'){ 
?>
    <a href='revenuecenterworkpage.php?RevenueCenterWorkPage=RevenueCenterWorkPageThisPage' class='art-button-green'>
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

<script type="text/javascript" language="javascript">
    function getDistricts(Region_Name) {
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


<br/><br/>
     



<!--  insert data from the form  -->

                
<fieldset>  
            <legend align=center><b>PATIENT REGISTRATION</b></legend>
        <center>
            <table width=100%>
                <form action='#' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">
                <tr>
                    <td width=35%>
                        <table width=100%>
                            <tr>
                                <td style='text-align: right'><b style='color: red'>Sponsor</b></td>
                                <td><select name='Guarantor_Name' id='Guarantor_Name' required='required' style='border-color: red' onchange='MemberNumberMandate(this.value);setVerify(this.value)'>
                                    <option selected='selected'></option>
                                        <?php
                                            $data = mysqli_query($conn,"select * from tbl_sponsor");
                                            while($row = mysqli_fetch_array($data)){
                                                echo '<option>'.$row['Guarantor_Name'].'</option>';
                                            }
                                        ?>
                                    </select>
                                    </td>
                                </tr>
                            <tr>
                                <td style='text-align: right'><b>Member Number</b></td>
                                <td><input type='text' name='Member_Number' autocomplete='off' id='Member_Number' style="width: 150px;text-align: left;">
                                    <input type="button" value="NHIF-eVerify" id='eVerify_btn' onclick="verifyNHIF2();" class="art-button-green" style="text-align: right;visibility: hidden;"/>
                                </td>
                            </tr>
                            <tr>
                                <td style='text-align: right'><b>Member Card Expire Date</b></td>
                                <td><input type='text' name='Member_Card_Expire_Date' autocomplete='off' id='date'></td> 
                            </tr>
                            <tr>
                                <td style='text-align: right'><b>New Regiatration Number</b></td>
                                <td><input type='text' name='New_Registration_Number' disabled='disabled' id='New_Registration_Number'></td> 
                            </tr>
                            <tr>
                                <td style='text-align: right'><b>Old Registration Number</b></td>
                                <td><input type='text' name='Old_Registration_Number' autocomplete='off' id='Old_Registration_Number'></td> 
                            </tr>
                            <tr>
                               <td style='text-align: right'><b>Title</b></td>
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
                            <tr>
                                <td style='text-align: right'><b style='color: red'>Patient Name</b></td>
                                <td><input type='text' name='Patient_Name' autocomplete='off' id='Patient_Name' style='border-color: red' required='required'></td>
                            </tr> 
                            <tr>
                                <td style='text-align: right'><b style='color: red'>Date Of Birth</b></td>
                                <td><input type='text' name='Date_Of_Birth' autocomplete='off' id='date2' style='border-color: red' required='required'></td>
                            </tr>
                            <tr>
                                <td style='text-align: right'><b style='color: red'>Gender</b></td>
                                <td id='gender_dom'>
                                    <select name='Gender' id='Gender' required='required' style='border-color: red' >
                                        <option selected='selected'></option>
					<option>Male</option>
                                        <option>Female</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td style='text-align: right'><b>Occupation</b></td>
                                <td><input type='text' name='Occupation' autocomplete='off' id='Occupation'></td>
                            </tr>
                        </table>
                    </td>
                    <td width=35%><table width=100%>
					        <tr>
							<td style='text-align: right'><b>Country</b></td>
								<td >
									<select name='country' id='country'>
                                       
										<?php
											$data = mysqli_query($conn,"select * from tbl_country ORDER BY Country_ID ASC");
												while($row = mysqli_fetch_array($data)){
											?>
											<option value='<?php echo $row['Country_Name'];?>'>
											<?php echo $row['Country_Name']; ?>
											</option>
										<?php
											}
										?>
                                    </select>
								</td>
						</tr>
                            <tr>
                                <td style='text-align: right'><b>Region</b></td>
                                <td>
                                    <select name='region' id='region' onchange='getDistricts(this.value)'>
                                        <option selected='selected'>Dar es salaam</option>
										<?php
											$data = mysqli_query($conn,"select * from tbl_regions");
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
                                <td style='text-align: right'><b>District</b></td>
                                <td>
                                    <select name='District' id='District'>
                                        <option selected='selected'></option>
										<option>Kinondoni</option>
                                        <option>Ilala</option>
                                        <option>Temeke</option>  
                                    </select>
                                </td> 
                            </tr>
                            <tr>
                                <td style='text-align: right'><b>Ward</b></td>
                                <td>
				    <input type='text' autocomplete='off' name='Ward' id='Ward'>
                                </td> 
                            </tr>
                            <tr>
                                <td style='text-align: right'><b>E Mail</b></td>
                                <td><input type='email' name='Email' autocomplete='off' id='Email'></td> 
                            </tr>
                            <tr>
                                <td style='text-align:right;' ><b>Emergency Contact Name</b></td>
                                <td><input type='text' name='Emergence_Contact_Name' autocomplete='off'  id='Emergence_Contact_Name' ></td>
                            </tr>
                            <tr>
                                <td style='text-align: right; '><b>Emergency Contact Number</b></td>
                                <td><input type='text' name='Emergence_Contact_Number' autocomplete='off'  id='Emergence_Contact_Number' ></td>
                            </tr>
                            <tr>
                                <td style='text-align: right'><b>Employee Vote Number</b></td>
                                <td><input type='text' name='Employee_Vote_Number' autocomplete='off' id='Employee_Vote_Number'></td>
                            </tr>
                            <tr>
                                <td style='text-align: right'><b>Company</b></td>
                                <td><input type='text' name='Company' id='Company' autocomplete='off'></td>
                            </tr>
                            <tr> 
                                <td style='text-align: right'><b>Phone Number</b></td>
                                <td><input type='text' name='Phone_Number' autocomplete='off' id='Phone_Number'></td> 
                            </tr>
                            <?php
                                if(isset($_SESSION['userinfo']['Employee_Name'])){
                                    $Employee_Name = $_SESSION['userinfo']['Employee_Name'];    
                                }else{
                                    $Employee_Name = "Unknown Employee";
                                }
                            ?>
                            <tr>
                                <td style='text-align: right'><b>Prepared By</b></td>
                                <td><input type='text' name='Prepared_By' disabled='disabled' id='Prepared_By' value='<?php echo $Employee_Name; ?>'></td>
                            </tr>
                        </table>
                    </td>
                    <td width=30%>
                        <table width=100%>
                            <tr><td style='text-align: center;'><b>Patient Picture</b></td></tr>
                            <tr>
                                <td id='Patient_Picture_td' style='text-align: center;'>
                                    <img src='./patientImages/default.png' id='Patient_Picture' name='Patient_Picture' id='Patient_Picture' width=50% height=50%>
                                </td>
                            </tr>
                            <tr>
                                <td>
				    <center>
					<b>SELECT PICTURE</b><input type="file" name="Patient_Picture" id="file" onchange='readImage(this)' title='SELECT PATIENT PICTURE'/>
				    </center>
                                </td>
                            </tr>
                            <tr>
                                <td colspan=2 style='text-align: center;'>
                                    <input type='submit' name='submit' id='submit' value='   SAVE   ' style='width: 30%' class='art-button-green'>
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
    if(isset($_POST['submittedAddNewPatientForm'])){
        
        if(isset($_SESSION['userinfo'])){ 
           if(isset($_SESSION['userinfo']['Employee_ID'])){
                $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
           }else{
            $Employee_ID = 0;
           }
        }
        
        if(isset($_SESSION['userinfo']['Branch_ID'])){
            $Branch_ID = $_SESSION['userinfo']['Branch_ID'];
        }else{
            $Branch_ID = 0;
        }
        $Old_Registration_Number = mysqli_real_escape_string($conn,$_POST['Old_Registration_Number']);
        $Patient_Title = mysqli_real_escape_string($conn,$_POST['Patient_Title']);
        $Patient_Name = mysqli_real_escape_string($conn,$_POST['Patient_Name']);
        $Date_Of_Birth = mysqli_real_escape_string($conn,$_POST['Date_Of_Birth']);
        $Gender = mysqli_real_escape_string($conn,$_POST['Gender']);
        $region = mysqli_real_escape_string($conn,$_POST['region']);
		$Country = mysqli_real_escape_string($conn,$_POST['country']);
        $District = mysqli_real_escape_string($conn,$_POST['District']);
        $Ward = mysqli_real_escape_string($conn,$_POST['Ward']);
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
                        Date_Of_Birth,Gender,Country,Region,District,Ward,
                            Sponsor_ID,
				Member_Number,Member_Card_Expire_Date,
				    Phone_Number,Email_Address,Occupation,
					Employee_Vote_Number,Emergence_Contact_Name,
					    Emergence_Contact_Number,Company,
						Employee_ID,Registration_Date_And_Time)
	
                    values('$Old_Registration_Number','$Patient_Title','$Patient_Name',
                    '$Date_Of_Birth',
                        '$Gender','$Country','$region','$District','$Ward',
                        (select Sponsor_ID from tbl_sponsor where Guarantor_Name = '$Guarantor_Name'),
			'$Member_Number','$Member_Card_Expire_Date',
                            '$Phone_Number','$Email','$Occupation',
                            '$Employee_Vote_Number','$Emergence_Contact_Name',
                                '$Emergence_Contact_Number','$Company',
                                '$Employee_ID','$Registration_Date_And_Time')";
        if(!mysqli_query($conn,$Insert_Sql)){
            die(mysqli_error($conn));
	    die(mysqli_error($conn));
				$error = '1062yes';
				if(mysql_errno()."yes" == $error){ 
						$controlforminput = 'not valid';
				}
		}
		else {
		    $selectThisRecord = mysqli_query($conn,"select Registration_ID  from tbl_patient_registration where
			Patient_Name = '$Patient_Name' and
			    Emergence_Contact_Name = '$Emergence_Contact_Name' and
			    Registration_Date_And_Time = '$Registration_Date_And_Time' and
                                Date_Of_Birth = '$Date_Of_Birth'") or die(mysqli_error($conn));
		    
                    while($row = mysqli_fetch_array($selectThisRecord)){
                        $Registration_ID = $row['Registration_ID']; 
                    }
                    
                    mysqli_query($conn,"insert into tbl_check_in(
                                    Registration_ID,Check_In_Date_And_Time,Visit_Date,
                                        Employee_ID,Branch_ID,Check_In_Date,Type_Of_Check_In)                                
                                    values(
                                        '$Registration_ID',(select now()),(select now()),
                                        '$Employee_ID','$Branch_ID',(select now()),'Afresh'
                                )") or die(mysqli_error($conn));

                    //create patient bill number
                    mysqli_query($conn,"INSERT INTO tbl_patient_bill(Registration_ID,Date_Time) VALUES ('$Registration_ID',(select now()))") or die(mysqli_error($conn));
								
					//get the last check in
					$check_in = mysqli_query($conn,"select Check_In_ID from tbl_check_in where Registration_id = '$Registration_ID' and employee_id = '$Employee_ID' order by Check_In_ID desc limit 1") or die(mysqli_error($conn));
                    $num = mysqli_num_rows($check_in);
					if($num > 0){
						while($row = mysqli_fetch_array($check_in)){
							$Check_In_ID = $row['Check_In_ID'];
						}
					}else{
						$Check_In_ID = 0;
					}
                    echo "<script type='text/javascript'>
			    alert('PARTIENT ADDED SUCCESSFUL');
			    document.location = './revenuecenterpatientbillingreception.php?Registration_ID=".$Registration_ID."&NR=true&Check_In_ID=".$Check_In_ID."&PatientBilling=PatientBillingThisForm';
			    </script>";		    
		}
                                
    }
?>

<?php
    include("./includes/footer.php");
?>