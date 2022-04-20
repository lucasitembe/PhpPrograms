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
?>

<?php
    if(isset($_GET['Registration_ID'])){
	$Registration_ID = $_GET['Registration_ID'];
    }else{
	$Registration_ID = '';
    }
?>

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
    <a href='visitorformMsamaha.php?Registration_ID=<?php echo $Registration_ID;?>&VisitorFormPatient=VisitorFormPatientThisPage' class='art-button-green'>
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
     




<?php

//    select patient information to perform check in process
    if(isset($_GET['Registration_ID'])){
        $Registration_ID = $_GET['Registration_ID']; 
        $select_Patient = mysqli_query($conn,"select
                            Old_Registration_Number,Title,Patient_Name,
                                Date_Of_Birth,
                                    Gender,pr.Region,pr.District,pr.Ward,
                                        Member_Number,Member_Card_Expire_Date,
                                            pr.Phone_Number,Email_Address,Occupation,
                                                Employee_Vote_Number,Emergence_Contact_Name,
                                                    Emergence_Contact_Number,Company,Registration_ID
                                                        Employee_ID,Registration_Date_And_Time,Guarantor_Name,
                                                        Registration_ID
                                      
                                      
                                      
                                      
                                      from tbl_patient_registration pr, tbl_sponsor sp 
                                        where pr.Sponsor_ID = sp.Sponsor_ID and 
                                              Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
        $no = mysqli_num_rows($select_Patient);
        
        if($no>0){
            while($row = mysqli_fetch_array($select_Patient)){
                $Registration_ID = $row['Registration_ID'];
                $Old_Registration_Number = $row['Old_Registration_Number'];
                $Title = $row['Title'];
                $Patient_Name = ucwords(strtolower($row['Patient_Name']));
                $Date_Of_Birth = $row['Date_Of_Birth'];
                $Gender = $row['Gender'];
                $Region = $row['Region'];
                $District = $row['District'];
                $Ward = $row['Ward'];
                $Guarantor_Name = $row['Guarantor_Name'];
                $Member_Number = $row['Member_Number'];
                $Member_Card_Expire_Date = $row['Member_Card_Expire_Date'];
                $Phone_Number = $row['Phone_Number'];
                $Email_Address = $row['Email_Address'];
                $Occupation = $row['Occupation'];
                $Employee_Vote_Number = $row['Employee_Vote_Number'];
                $Emergence_Contact_Name = $row['Emergence_Contact_Name'];
                $Emergence_Contact_Number = $row['Emergence_Contact_Number'];
                $Company = $row['Company'];
                $Employee_ID = $row['Employee_ID'];
                $Registration_Date_And_Time = $row['Registration_Date_And_Time'];
               // echo $Ward."  ".$District."  ".$Ward; exit;
            }
             
        }else{
            $Registration_ID = '';
            $Old_Registration_Number = '';
            $Title = '';
            $Patient_Name = '';
            $Date_Of_Birth = '';
            $Gender = '';
            $Region = '';
            $District = '';
            $Ward = '';
            $Guarantor_Name = '';
            $Member_Number = '';
            $Member_Card_Expire_Date = '';
            $Phone_Number = '';
            $Email_Address = '';
            $Occupation = '';
            $Employee_Vote_Number = '';
            $Emergence_Contact_Name = '';
            $Emergence_Contact_Number = '';
            $Company = '';
            $Employee_ID = '';
            $Registration_Date_And_Time = '';            
        }
    }else{
            $Registration_ID = '';
            $Old_Registration_Number = '';
            $Title = '';
            $Patient_Name = ''; 
            $Date_Of_Birth = '';
            $Gender = '';
            $Region = '';
            $District = '';
            $Ward = '';
            $Guarantor_Name = '';
            $Member_Number = '';
            $Member_Card_Expire_Date = '';
            $Phone_Number = '';
            $Email_Address = '';
            $Occupation = '';
            $Employee_Vote_Number = '';
            $Emergence_Contact_Name = '';
            $Emergence_Contact_Number = '';
            $Company = '';
            $Employee_ID = '';
            $Registration_Date_And_Time = '';
        }
?>





<!--  insert data from the form  -->

<?php
    if(isset($_POST['submittedEditPatientForm'])){
        
        if(isset($_SESSION['userinfo'])){ 
           if(isset($_SESSION['userinfo']['Employee_ID'])){
                $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
           }else{
            $Employee_ID = 0;
           }
        }
        
        $Old_Registration_Number = mysqli_real_escape_string($conn,$_POST['Old_Registration_Number']);
        $Patient_Title = mysqli_real_escape_string($conn,$_POST['Patient_Title']);
        $Patient_Name = mysqli_real_escape_string($conn,$_POST['Patient_Name']);
        $Date_Of_Birth = mysqli_real_escape_string($conn,$_POST['Date_Of_Birth']);
        $Gender = mysqli_real_escape_string($conn,$_POST['Gender']);
        $region = mysqli_real_escape_string($conn,$_POST['region']);
        $District = mysqli_real_escape_string($conn,$_POST['District']);
        $Ward = mysqli_real_escape_string($conn,$_POST['Ward']);
        $Phone_Number = mysqli_real_escape_string($conn,$_POST['Phone_Number']);
        $Email = mysqli_real_escape_string($conn,$_POST['Email']);
	$Guarantor_Name = mysqli_real_escape_string($conn,$_POST['Guarantor_Name']);
        $Member_Number = mysqli_real_escape_string($conn,$_POST['Member_Number']);
        $Member_Card_Expire_Date = mysqli_real_escape_string($conn,$_POST['Member_Card_Expire_Date']);
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
	    
	    
        $Update_Sql = "update tbl_patient_registration set
                    Old_Registration_Number = '$Old_Registration_Number',Title = '$Patient_Title',Patient_Name = '$Patient_Name',
                        Date_Of_Birth = '$Date_Of_Birth',Gender = '$Gender',Region = '$region',District = '$District',Ward = '$Ward', 
				    Phone_Number = '$Phone_Number',Email_Address = '$Email',Occupation = '$Occupation',
					Sponsor_ID = (select Sponsor_ID from tbl_sponsor where Guarantor_Name = '$Guarantor_Name'),
					    Member_Number = '$Member_Number',Member_Card_Expire_Date = '$Member_Card_Expire_Date',
					        Employee_Vote_Number = '$Employee_Vote_Number',Emergence_Contact_Name = '$Emergence_Contact_Name',
						    Emergence_Contact_Number = '$Emergence_Contact_Number',Company = '$Company' where Registration_ID = '$Registration_ID'";
        if(!mysqli_query($conn,$Update_Sql)){
            die(mysqli_error($conn));
	    die(mysqli_error($conn));
	
            echo "<script type='text/javascript'>
                    alert('Process Fail! Please Try Again');
                    </script>";
        }else{
            echo "<script type='text/javascript'>
                    alert('PARTIENT EDITED SUCCESSFULLY');
                    document.location = 'editpatientMsamaha.php?Registration_ID=".$Registration_ID."&VisitorFormPatient=VisitorFormPatientThisPage';
                    </script>";
            //call the function to log the action
            $Activity_Date_And_Time = date('Y-m-d H:i:s');
            activity_log($Employee_ID,$Activity_Date_And_Time,"Edited Patient Information.");
        }
    }
?>
                
<fieldset>  
            <legend align=right><b>EDIT PATIENT</b></legend>
        <center>
            <table width=100%>
                <form action='#' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">
                <tr>
                    <td width=35%>
                        <table width=100%>
                            <tr>
                                <td style="text-align:right;">New Regiatration Number</td>
                                <td><input type='text' name='Registration_Number' id='Registration_Number' disabled='disabled' value='<?php echo $Registration_ID; ?>'></td> 
                            </tr>
                            <tr>
                                <td style="text-align:right;">Old Registration Number</td>
                                <td><input type='text' name='Old_Registration_Number' id='Old_Registration_Number' value='<?php echo $Old_Registration_Number; ?>'></td>
                            </tr>
                            <tr>
                               <td style="text-align:right;">Title</td>
                               <td>
                                <select name='Patient_Title' id='Patient_Title'>
                                    <option selected='selected'><?php echo $Title; ?></option>
                                    <option>Mr</option>
                                    <option>Mrs</option>
                                    <option>Miss</option>
                                    <option>Master</option>
                                    <option>Dr</option>
                                    <option>Prof</option>
                                </select>
                               </td>
                            </tr>
                            <tr>
                                <td style="text-align:right;">Patient Name</td>
                                <td><input type='text' name='Patient_Name' id='Patient_Name' value='<?php echo $Patient_Name; ?>'></td>
                            </tr> 
                            <tr>
                                <td style="text-align:right;">Date Of Birth</td>
                                <td><input type='text' name='Date_Of_Birth' id='date2' required='required' value='<?php echo $Date_Of_Birth; ?>'></td>
                            </tr>
                            <tr>
                                <td style="text-align:right;">Gender</td>
                                <td>
                                    <select name='Gender' id='Gender'>
					<?php
					    if(strtolower($Gender) == 'male'){
						echo "<option selected='selected'>Male</option>";
						echo "<option>Female</option>";
					    }else{
						echo "<option selected='selected'>Female</option>";
						echo "<option>Male</option>";
					    }
					?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align:right;">Occupation</td>
                                <td><input type='text' name='Occupation' id='Occupation' value='<?php echo $Occupation; ?>'></td>
                            </tr>
                            <tr>
                                <td style="text-align:right;">Employee Vote Number</td>
                                <td><input type='text' name='Employee_Vote_Number' id='Employee_Vote_Number' value='<?php echo $Employee_Vote_Number; ?>'></td>
                            </tr>
			    <tr>
                                <td style="text-align:right;">Company</td>
                                <td><input type='text' name='Company' id='Company' value='<?php echo $Company; ?>'></td>
                            </tr>
                            <tr> 
                                <td style="text-align:right;">Patient Phone Number</td>
                                <td><input type='text' name='Phone_Number' id='Phone_Number' value='<?php echo $Phone_Number; ?>'></td> 
                            </tr>
                        </table>
                    </td>
                    <td width=35%><table width=100%>
                            <tr>
                                <td style="text-align:right;">Region</td>
                                <td>
                                    <select name='region' id='region' onchange='getDistricts(this.value)'>
                                        <option selected='selected' value='<?php echo $Region; ?>'><?php echo $Region; ?></option>
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
                                <td style="text-align:right;">District</td>
                                <td>
                                    <select name='District' id='District'>
                                        <option selected='selected'><?php echo $District; ?></option>
                                        <option>Ilala</option>
                                        <option>Temeke</option>  
                                    </select>
                                </td> 
                            </tr>
                            <tr>
                                <td style="text-align:right;">Ward</td>
                                <td>
				    <input type='text' name='Ward' id='Ward' value='<?php echo $Ward; ?>'>
                                </td> 
                            </tr>
                            <tr>
                                <td style="text-align:right;">Sponsor</td>
				<?php if(strtolower($_SESSION['userinfo']['Modify_Credit_Information']) == 'yes' && strtolower($_SESSION['userinfo']['Modify_Cash_information']) == 'yes' && strtolower($_SESSION['userinfo']['Session_Master_Priveleges']) == 'yes'){ ?>
    				    <td>
					<select name='Guarantor_Name' id='Guarantor_Name' required='required'>
					    <option selected='selected'><?php echo $Guarantor_Name; ?></option>
						<?php
						    $data = mysqli_query($conn,"select * from tbl_sponsor where Guarantor_Name <> '$Guarantor_Name'");
						    while($row = mysqli_fetch_array($data)){
							echo '<option>'.$row['Guarantor_Name'].'</option>';
						    }
						?>
					</select>
				    </td>
				<?php }else{ ?>
				    <td>
					<select name='Guarantor_Name' id='Guarantor_Name' required='required'>
					    <option selected='selected'><?php echo $Guarantor_Name; ?></option>
					</select>
				    </td>
				<?php } ?> 
                            </tr>
                            <tr>
                                <td style="text-align:right;">Member Number</td>
				<?php if(strtolower($_SESSION['userinfo']['Modify_Credit_Information']) == 'yes'){ ?>
				    <td><input type='text' name='Member_Number' id='Member_Number' value='<?php echo $Member_Number; ?>'></td>
				<?php }elseif($Member_Number == '' || $Member_Number == NULL){ ?>
				    <td><input type='text' name='Member_Number' id='Member_Number' value='<?php echo $Member_Number; ?>'></td>
				<?php }else{ ?>
				    <td>
					<input type='text' disabled='disabled' value='<?php echo $Member_Number; ?>'>
					<input type='hidden' name='Member_Number' id='Member_Number' value='<?php echo $Member_Number; ?>'>
				    </td>
				<?php } ?>
                            </tr>                             
                            <tr>
                                <td style="text-align:right;">Member Card Expire Date</td>
				<?php if(strtolower($_SESSION['userinfo']['Modify_Credit_Information']) == 'yes'){ ?>
				    <td><input type='text' name='Member_Card_Expire_Date' id='date' value='<?php echo $Member_Card_Expire_Date; ?>'></td>
				<?php }else{ ?>
				    <td>
					<input type='text' disabled='disabled' value='<?php echo $Member_Card_Expire_Date; ?>'>
					<input type='hidden' name='Member_Card_Expire_Date' id='date' value='<?php echo $Member_Card_Expire_Date; ?>'>
				    </td>
				<?php } ?>
                            </tr>
			    <tr>
                                <td style="text-align:right;">E Mail</td>
                                <td><input type='email' name='Email' id='Email' value='<?php echo $Email_Address; ?>'></td> 
                            </tr>
                            <tr>
                                <td style="text-align:right;">Emergency Contact Name</td>
                                <td><input type='text' name='Emergence_Contact_Name' id='Emergence_Contact_Name'  value='<?php echo $Emergence_Contact_Name; ?>'></td>
                            </tr>
                            <tr>
                                <td style="text-align:right;">Emergency Contact Number</td>
                                <td><input type='text' name='Emergence_Contact_Number' id='Emergence_Contact_Number'  value='<?php echo $Emergence_Contact_Number; ?>'></td>
                            </tr>
                            <?php
                                if(isset($_SESSION['userinfo']['Employee_Name'])){
                                    $Employee_Name = $_SESSION['userinfo']['Employee_Name'];    
                                }else{
                                    $Employee_Name = "Unknown Employee";
                                }
                            ?> 
                        </table>
                    </td>
                    <td width=30%>
                        <table width=100%>
                            <tr><td style='text-align: center;'>Patient Picture</td></tr>
                            <tr>
                                <td id='Patient_Picture_td' style='text-align: center;'>
                                    <img src='./patientImages/default.png' id='Patient_Picture' name='Patient_Picture' width=50% height=50%>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    SELECT PICTURE
                                    <input type="file" name="Patient_Picture" id="file" onchange='readImage(this)' title='SELECT PATIENT PICTURE'/> 
                                </td>
                            </tr>
                            <tr>
                                <td colspan=2 style='text-align: right;'>
                                    <input type='submit' name='submit' id='submit' value='   UPDATE   ' class='art-button-green'>
                                    <input type='reset' name='clear' id='clear' value=' CLEAR ' class='art-button-green' onclick='clearPatientPicture()'>
                                    <input type='hidden' name='submittedEditPatientForm' value='true'/> 
                                </td>
                            </tr> 
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