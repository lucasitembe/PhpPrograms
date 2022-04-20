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
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Reception_Works'] == 'yes'){ 
?>
    <a href='visitorform.php?RegisterPatient=RegisterPatientThisPage' class='art-button-green'>
        VISITORS
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
</script>


<br/><br/>
     



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
        
        $Old_Registration_Number = $_POST['Old_Registration_Number'];
        $Patient_Title = $_POST['Patient_Title'];
        $First_Name = $_POST['First_Name'];
        $Second_Name = $_POST['Second_Name'];
        $Last_Name = $_POST['Last_Name'];
        $Date_Of_Birth = $_POST['Date_Of_Birth'];
        $Gender = $_POST['Gender'];
        $region = $_POST['region'];
        $District = $_POST['District'];
        $Ward = $_POST['Ward'];
        $Guarantor_Name = $_POST['Guarantor_Name'];
        $Member_Number = $_POST['Member_Number'];
        $Member_Card_Expire_Date = $_POST['Member_Card_Expire_Date'];
        $Phone_Number = $_POST['Phone_Number'];
        $Email = $_POST['Email'];
        $Occupation = $_POST['Occupation'];
        $Employee_Vote_Number = $_POST['Employee_Vote_Number'];
        $Emergence_Contact_Name = $_POST['Emergence_Contact_Name'];
        $Emergence_Contact_Number = $_POST['Emergence_Contact_Number'];
        $Company = $_POST['Company'];
        
        if(isset($_SESSION['userinfo'])){
            if(isset($_SESSION['userinfo']['Employee_ID'])){
                $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
            }else{
                $Employee_ID = 0;
            }
        }
       
       
	//select patient registrration date and time
	$data = mysqli_query($conn,"select now() as Registration_Date_And_Time");
            while($row = mysqli_fetch_array($data)){
                $Registration_Date_And_Time = $row['Registration_Date_And_Time'];
            }
	    
	    
        $Insert_Sql = "insert into tbl_patient_registration(
                    Old_Registration_Number,Title,First_Name,
                        Second_Name,Last_Name,Date_Of_Birth,
                        Gender,Region,District,Ward,
                            Sponsor_ID,
				Member_Number,Member_Card_Expire_Date,
				    Phone_Number,Email_Address,Occupation,
					Employee_Vote_Number,Emergence_Contact_Name,
					    Emergence_Contact_Number,Company,
						Employee_ID,Registration_Date_And_Time)
	
                    values('$Old_Registration_Number','$Patient_Title','$First_Name',
                    '$Second_Name','$Last_Name','$Date_Of_Birth',
                        '$Gender','$region','$District','$Ward',
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
			First_Name = '$First_Name' and
			    Last_Name = '$Last_Name' and
			    Emergence_Contact_Name = '$Emergence_Contact_Name' and
			    Registration_Date_And_Time = '$Registration_Date_And_Time'") or die(mysqli_error($conn));
		    
                    while($row = mysqli_fetch_array($selectThisRecord)){
                            $Registration_ID = $row['Registration_ID']; 
                    }
                    echo "<script type='text/javascript'>
			    alert('PARTIENT ADDED SUCCESSFUL');
			    document.location = './visitorform.php?Registration_ID=".$Registration_ID."&VisitorForm=VisitorFormThisPage';
			    </script>";
		    
		}
                                
    }
?>
                
<fieldset>  
            <legend align=center><b>PATIENT REGISTRATION</b></legend>
        <center>
            <table width=100%>
                <form action='#' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">
                <tr>
                    <td width=35%>
                        <table width=100%>
                            <tr>
                                <td><b>New Regiatration Number</b></td>
                                <td><input type='text' name='New_Registration_Number' disabled='disabled' id='New_Registration_Number'></td> 
                            </tr>
                            <tr>
                                <td><b>Old Registration Number</b></td>
                                <td><input type='text' name='Old_Registration_Number' id='Old_Registration_Number'></td> 
                            </tr>
                            <tr>
                               <td><b>Title</b></td>
                               <td>
                                <select name='Patient_Title' id='Patient_Title'>
                                    <option selected='selected'>Mr</option>
                                    <option>Mrs</option>
                                    <option>Miss</option>
                                    <option>Dr</option>
                                    <option>Prof</option>
                                </select>
                               </td>
                            </tr>
                            <tr>
                                <td><b>First Name</b></td>
                                <td><input type='text' name='First_Name' id='First_Name' required='required'></td>
                            </tr>
                            <tr>
                                <td><b>Second Name</b></td>
                                <td><input type='text' name='Second_Name' id='Second_Name'></td>
                            </tr>
                            <tr>
                                <td><b>Last Name</b></td>
                                <td><input type='text' name='Last_Name' id='Last_Name' required='required'></td>
                            </tr>
                            <tr>
                                <td><b>Date Of Birth</b></td>
                                <td><input type='text' name='Date_Of_Birth' id='date2' required='required'></td>
                            </tr>
                            <tr>
                                <td><b>Gender</b></td>
                                <td>
                                    <select name='Gender' id='Gender'>
                                        <option selected='selected'>Male</option>
                                        <option>Female</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td><b>Occupation</b></td>
                                <td><input type='text' name='Occupation' id='Occupation'></td>
                            </tr>
                            <tr>
                                <td><b>Employee Vote Number</b></td>
                                <td><input type='text' name='Employee_Vote_Number' id='Employee_Vote_Number'></td>
                            </tr>
                            <tr>
                                <td><b>Company</b></td>
                                <td><input type='text' name='Company' id='Company'></td>
                            </tr>
                        </table>
                    </td>
                    <td width=35%><table width=100%>
                            <tr>
                                <td><b>Region</b></td>
                                <td>
                                    <select name='region' id='region' onchange='getDistricts(this.value)'>
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
                                <td><b>District</b></td>
                                <td>
                                    <select name='District' id='District'>
                                        <option selected='selected'>Kinondoni</option>
                                        <option>Ilala</option>
                                        <option>Temeke</option>  
                                    </select>
                                </td> 
                            </tr>
                            <tr>
                                <td><b>Ward</b></td>
                                <td>
				    <input type='text' name='Ward' id='Ward'>
                                </td> 
                            </tr>
                            <tr>
                                <td><b>Sponsor</b></td>
                                <td><select name='Guarantor_Name' id='Guarantor_Name'>
                                    <?php
                                        $data = mysqli_query($conn,"select * from tbl_sponsor");
                                        while($row = mysqli_fetch_array($data)){
                                            echo '<option>'.$row['Guarantor_Name'].'</option>';
                                        }
                                    ?>
                                </select></td>
                            </tr>
                            <tr>
                                <td><b>Member Number</b></td>
                                <td><input type='text' name='Member_Number' id='Member_Number'></td> 
                            </tr>
                            <tr>
                                <td><b>Member Card Expire Date</b></td>
                                <td><input type='text' name='Member_Card_Expire_Date' id='Member_Card_Expire_Date'></td> 
                            </tr>
                            <tr> 
                                <td><b>Phone Number</b></td>
                                <td><input type='text' name='Phone_Number' id='Phone_Number'></td> 
                            </tr>
                            <tr>
                                <td><b>E Mail</b></td>
                                <td><input type='email' name='Email' id='Email'></td> 
                            </tr>
                            <tr>
                                <td><b>Emergency Contact Name</b></td>
                                <td><input type='text' name='Emergence_Contact_Name' id='Emergence_Contact_Name' required='required'></td>
                            </tr>
                            <tr>
                                <td><b>Emergency Contact Number</b></td>
                                <td><input type='text' name='Emergence_Contact_Number' id='Emergence_Contact_Number' required='required'></td>
                            </tr>
                            <?php
                                if(isset($_SESSION['userinfo']['Employee_Name'])){
                                    $Employee_Name = $_SESSION['userinfo']['Employee_Name'];    
                                }else{
                                    $Employee_Name = "Unknown Employee";
                                }
                            ?>
                            <tr>
                                <td><b>Prepared By</b></td>
                                <td><input type='text' name='Prepared_By' disabled='disabled' id='Prepared_By' value='<?php echo $Employee_Name; ?>'></td>
                            </tr>
                        </table>
                    </td>
                    <td width=30%>
                        <table width=100%>
                            <tr><td style='text-align: center;'><b>Patient Picture</b></td></tr>
                            <tr>
                                <td id='Patient_Picture_td' style='text-align: center;'>
                                    <img src='./patientImages/default.png' id='Patient_Picture' name='Patient_Picture' width=50% height=50%>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <b>SELECT PICTURE</b>
                                    <input type="file" name="Patient_Picture" id="file" onchange='readImage(this)' title='SELECT PATIENT PICTURE'/> 
                                </td>
                            </tr>
                            <tr>
                                <td colspan=2 style='text-align: right;'>
                                    <input type='submit' name='submit' id='submit' value='   SAVE   ' class='art-button-green'>
                                    <input type='reset' name='clear' id='clear' value=' CLEAR ' class='art-button-green' onclick='clearPatientPicture()'>
                                    <input type='hidden' name='submittedAddNewPatientForm' value='true'/> 
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