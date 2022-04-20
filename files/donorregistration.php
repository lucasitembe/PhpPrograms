<?php
    include("./includes/header.php");
	include("./includes/connection.php");
    if(!isset($_SESSION['userinfo'])){
        @session_destroy();
        header("Location: ../index.php?InvalidPrivilege=yes");
    }
	
	 
?>


    <a href='searchdonorslist.php' class='art-button-green'>
         REGISTERED 
    </a>
	

    <a href='bloodworkpage.php?Appointments=AppointmentsThisPage' class='art-button-green'>
    BACK
    </a>


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
   
</script>

<?php
		
		if(isset($_POST['submitdonorform'])){

     $Donor_Name = mysqli_real_escape_string($conn,$_POST['Donor_Name']);		
     $Date_Of_Birth = mysqli_real_escape_string($conn,$_POST['Date_Of_Birth']);		
     $Gender = mysqli_real_escape_string($conn,$_POST['Gender']);		
     $Phone_Number = mysqli_real_escape_string($conn,$_POST['Phone_Number']);		
     $Email = mysqli_real_escape_string($conn,$_POST['Email']);		
     $Region = mysqli_real_escape_string($conn,$_POST['Region']);		
     $District = mysqli_real_escape_string($conn,$_POST['District']);		
     $Ward = mysqli_real_escape_string($conn,$_POST['Ward']);		
     $Emergency_Name = mysqli_real_escape_string($conn,$_POST['Emergency_Name']);		
     $Emergency_Number = mysqli_real_escape_string($conn,$_POST['Emergency_Number']);	
     $Status='Donor';	 
     //$Registration_Date_And_Date = mysqli_real_escape_string($conn,$_POST['Registration_Date_And_Date']);		
   //  $Registration_Date = mysqli_real_escape_string($conn,$_POST['Registration_Date']);		



   	
        //get employee id
        if(isset($_SESSION['userinfo'])){
            if(isset($_SESSION['userinfo']['Employee_ID'])){
                $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
            }else{
                $Employee_ID = 0;
            }
        }
		
	//insert query into tbl_patient_registration
	
	$Donors_Sql = "insert into tbl_patient_registration ( Patient_Name,Date_Of_Birth,Gender,Phone_Number,Email_Address,Region,District,Ward,Emergence_Contact_Name,
	Emergence_Contact_Number,Employee_ID,Registration_Date_And_Time ,Status)
	VALUES
	('$Donor_Name','$Date_Of_Birth','$Gender','$Phone_Number','$Email','$Region',
	'$District','$Ward','$Emergency_Name','$Emergency_Number','$Employee_ID',(select now()),'$Status')";
		
	if(!mysqli_query($conn,$Donors_Sql)){
			die(mysqli_error($conn));
	}
echo "<script type='text/javascript'>
	    alert('REGISTERED SUCCESSFULL');
	    document.location = './donorregistration.php';
    </script>";
}
?>


<br>
<br>
<br>
<fieldset>  
    <legend align=right><b>DONOR'S REGISTRATION</b></legend>
    <center>
            <table width=100%>
                <form action='#' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">
                <tr>
                    <td width=40%>
                    <table width=100%>
			<tr>
			    <td style="text-align:right;" ><b>Donor Name</b></td>
			    <td><input type='text' name='Donor_Name'  id='Donor_Name' autocomplete="off"></td> 
			</tr>
				 
			<tr>
			    <td style="text-align:right;"><b>Date Of Birth</b></td>
			    <td><input type='text' name='Date_Of_Birth' id='date2' required='required'></td>
			</tr>
				
			<tr>
			    <td style="text-align:right;"><b>Gender</b></td>
			    <td>
				<select name='Gender' id='Gender'>
				    <option selected='selected'>Male</option>
				    <option>Female</option>
				</select>
			    </td>
			</tr>
			<!--<tr>
			    <td style="text-align:right;"><b>Mobile Number</b></td>
			    <td><input type='text' name='Mobile' id='Mobile'></td>
			</tr>-->
			<tr>
			    <td style="text-align:right;"><b>Phone Number</b></td>
			    <td><input type='text' name='Phone_Number' id='Phone_Number' autocomplete="off"></td>
			</tr>
			<tr>
			    <td style="text-align:right;"><b>Email</b></td>
			    <td><input type='text' name='Email' id='Email' autocomplete="off"></td>
			</tr>
		    </table>
                </td>
                <td width=35%>
		    <table width=100%>
			<tr>
			    <td style="text-align:right;"><b>Region</b></td>
			    <td>
				<select name='Region' id='region' onchange='getDistricts(this.value)'>
				    <option selected='selected' value='Dar es salaam'>Dar es salaam</option>
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
			    <td style="text-align:right;"><b>District</b></td>
			    <td>
				<select name='District' id='District'>
				    <option selected='selected'>Kinondoni</option>
				    <option>Ilala</option>
				    <option>Temeke</option>  
				</select>
			    </td> 
			</tr>
			<tr>
			    <td style="text-align:right;"><b>Ward</b></td>
			    <td>
						<input type='text' name='Ward' id='Ward' autocomplete="off">
			    </td> 
			</tr>
                        <tr>
			    <td style="text-align:right;"><b>Emergency Contact Name</b></td>
			    <td><input type='text' name='Emergency_Name' id='Emergency_Name' required='required' autocomplete="off"></td>
			</tr>
			<tr>
			    <td style="text-align:right;"><b>Emergency Contact Number</b></td>
			    <td><input type='text' name='Emergency_Number' id='Emergency_Number' required='required' autocomplete="off"></td>
			</tr>
                            <?php
                                if(isset($_SESSION['userinfo']['Employee_Name'])){
                                    $Employee_Name = $_SESSION['userinfo']['Employee_Name'];    
                                }else{
                                    $Employee_Name = "Unknown Employee";
                                }
                            ?>
			<tr>
			    <td style="text-align:right;"><b>Prepared By</b></td>
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
                                    <input type='hidden' name='submitdonorform' value='true'/> 
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