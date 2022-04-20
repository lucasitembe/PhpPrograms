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
?>
<a href='opticalpatientlist.php?BillingType=OutpatientCash&GlassProcessing=GlassProcessingThisPage' class='art-button-green'>BACK</a>
<br/><br/>
<fieldset>
	<legend align=center><b>PATIENT REGISTRATION</b></legend><br/>
        <center>
            <table width=100%>
                <form action='#' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">
                <tr>
                    <td width=35%>
                        <table width=100%>
                            <tr>
                                <td style='text-align: right'><b style='color: red'>Sponsor Name</td>
                                <td><select name='Guarantor_Name' id='Guarantor_Name' required='required' style='border-color: red' onchange='MemberNumberMandate(this.value);setVerify(this.value); disable_member_number(this.value);'>
                                        <?php
                                            $data = mysqli_query($conn,"select * from tbl_sponsor where Guarantor_Name = 'CASH'") or die(mysqli_error($conn));
                                            while($row = mysqli_fetch_array($data)){
                                                echo '<option value="'.$row['Guarantor_Name'].'">'.$row['Guarantor_Name'].'</option>';
                                            }
                                        ?>
                                    </select>
                                    </td>
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
                            <tr>
                                <td style='text-align: right'><b style='color: red'>Patient Name</td>
                                <td><input type='text' name='Patient_Name' autocomplete='off' id='Patient_Name' style='border-color: red' required='required'></td>
                            </tr> 
                            <tr>
                                <td style='text-align: right'><b style='color: red'>Date Of Birth</td>
                                <td><input type='text' name='Date_Of_Birth' autocomplete='off' id='date2' style='border-color: red' required='required' /></td>
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
                                <td style='text-align: right'><b style='color: red'>Patient Phone Number</td>
                                <td><input type='text' name='Phone_Number' autocomplete='off' style='border-color: red' id='Phone_Number' required='required' onfocus="addCode()"></td> 
			    			</tr>
                        </table>
                    </td>
                    <td width=35%><table width=100%>
						<tr>
							<td style='text-align: right'>Country</td>
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
                                    <select name='region' id='region' onchange='getDistricts(this.value)'>
                                        <option selected='selected'><?php echo $Selected_Region; ?></option>
										<?php
											$data = mysqli_query($conn,"select * from tbl_regions where Region_Status = 'Not Selected'");
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
                                <td style='text-align: right'>Occupation</td>
                                <td><input type='text' name='Occupation' autocomplete='off' id='Occupation'></td>
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
						</table>
                    </td>
                </tr>
                <tr>
                    <td colspan=2 style='text-align: right;'>
                        <input type='submit' name='submit' id='submit' value='   SAVE   ' class='art-button-green' onclick="Validate_Date()">
                        <input type='reset' name='clear' id='clear' value=' CLEAR ' class='art-button-green' onclick='clearPatientPicture()'>
                        <input type='hidden' name='submittedAddNewPatientForm' style='width: 30%' value='true'/>
                    </td>
                </tr>
                </form>
            </table>
        </center>
        
</fieldset><br/>

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
	
		$Patient_Title = mysqli_real_escape_string($conn,$_POST['Patient_Title']);
        $Patient_Name = mysqli_real_escape_string($conn,$_POST['Patient_Name']);
        $Date_Of_Birth = mysqli_real_escape_string($conn,$_POST['Date_Of_Birth']);
        $Gender = mysqli_real_escape_string($conn,$_POST['Gender']);
        $Country = mysqli_real_escape_string($conn,$_POST['country']);
        $region = mysqli_real_escape_string($conn,$_POST['region']);
        $District = mysqli_real_escape_string($conn,$_POST['District']);
        $Ward = mysqli_real_escape_string($conn,$_POST['Ward']);
        $Phone_Number = mysqli_real_escape_string($conn,$_POST['Phone_Number']);
        $Occupation = mysqli_real_escape_string($conn,$_POST['Occupation']);

		if(isset($_SESSION['userinfo']['Employee_ID'])){
		    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
		}else{
		    $Employee_ID = 0;
		}
	   
	    //select patient registration date and time
	    $data = mysqli_query($conn,"select now() as Registration_Date_And_Time") or die(mysqli_error($conn));
	    while($row = mysqli_fetch_array($data)){
			$Registration_Date_And_Time = $row['Registration_Date_And_Time'];
	    }
		
	    $Insert_Sql = "insert into tbl_patient_registration(
						Title, Patient_Name, Date_Of_Birth, Gender,
						Country, Region, District, Ward, Sponsor_ID,
						Phone_Number, Occupation, Employee_ID,
						Registration_Date_And_Time, District_ID, Registration_Date)
    				values('$Patient_Title','$Patient_Name','$Date_Of_Birth','$Gender',
    					'$Country','$region','$District','$Ward',(select Sponsor_ID from tbl_sponsor where Guarantor_Name = 'Cash'),
		    			'$Phone_Number','$Occupation','$Employee_ID','$Registration_Date_And_Time',(select District_ID from tbl_district where District_Name = '$District'),(select now()))";
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
			    Registration_Date_And_Time = '$Registration_Date_And_Time' and
			    Date_Of_Birth = '$Date_Of_Birth'") or die(mysqli_error($conn));
		    
		    while($row = mysqli_fetch_array($selectThisRecord)){
			    $Registration_ID = $row['Registration_ID']; 
		    }

		    //add details into tbl_consultation
		    $insert_consultation = mysqli_query($conn,"insert into tbl_consultation(employee_ID, Registration_ID, Consultation_Date_And_Time)
		    									values('$Employee_ID','$Registration_ID',(select now()))") or die(mysqli_error($conn));
		    //get last consultation
		    $slct = mysqli_query($conn,"select consultation_ID from tbl_consultation where Registration_ID = '$Registration_ID' order by consultation_ID desc limit 1") or die(mysqli_error($conn));
		    $num = mysqli_num_rows($slct);
		    if($num > 0){
		    	while ($data = mysqli_fetch_array($slct)) {
		    		$consultation_ID = $data['consultation_ID'];
		    	}

		    	//insert data into tbl_spectacles
		    	mysqli_query($conn,"insert into tbl_spectacles(Registration_ID,consultation_ID,Date_Time) values('$Registration_ID','$consultation_ID',(select now()))") or die(mysqli_error($conn));
		    }else{
		    	$consultation_ID = 0;
		    }
		    echo "<script type='text/javascript'>
			    alert('PATIENT ADDED SUCCESSFULLY');
			    document.location = './opticaltransaction.php?consultation_ID=".$consultation_ID."&Registration_ID=".$Registration_ID."&Section=outside&OpticalTransaction=OpticalTransactionThisPage';
			    </script>";
		}
    }
?>

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

<script>
    function Validate_Date(){
        var Today = new Date(); //current date
        var Date_Of_Birth = new Date(document.getElementById("date2").value);
        var Initial_Date = new Date("1900, 01, 01");

        if(Date_Of_Birth < Initial_Date || Date_Of_Birth > Today){
            alert("Invalid Date Of Birth");
            document.getElementById("date2").value = '';
        }
    }
</script>