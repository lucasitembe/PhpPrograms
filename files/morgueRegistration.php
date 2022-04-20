<?php
    include("./includes/header.php");
	include("./includes/connection.php");
    
	if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Morgue_Works'])){
	    if($_SESSION['userinfo']['Morgue_Works'] != 'yes'){
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
<a href='unknownDeadBody.php' class='art-button-green'>
        BACK
    </a>
	
	
<script type='text/javascript'>
    function access_Denied(){ 
   alert("Access Denied");
   document.location = "./index.php";
    }
</script>




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
   
</script>

<?php
		
		if(isset($_POST['submitmorgueform'])){

     $Name_Of_Body = mysqli_real_escape_string($conn,$_POST['Name_Of_Body']);		
     $Time_For_Dead = mysqli_real_escape_string($conn,$_POST['Time_For_Dead']);		
     $Gender = mysqli_real_escape_string($conn,$_POST['Gender']);
	 $Age=mysqli_real_escape_string($conn,$_POST['Age']);
     $Reason = mysqli_real_escape_string($conn,$_POST['Reason']);
     $Description_Death= mysqli_real_escape_string($conn,$_POST['Description_Death']);
     $Morgue_Name = mysqli_real_escape_string($conn,$_POST['Morgue_Name']);		
     $Deadline_To_Pick_Up = mysqli_real_escape_string($conn,$_POST['Deadline_To_Pick_Up']);
	 $Member_Name = mysqli_real_escape_string($conn,$_POST['Member_Name']);
	 $Relationship_Dead= mysqli_real_escape_string($conn,$_POST['Relationship_Dead']);	
	 $Region= mysqli_real_escape_string($conn,$_POST['Region']);	
     $District = mysqli_real_escape_string($conn,$_POST['District']);		
     $Ward_Stress = mysqli_real_escape_string($conn,$_POST['Ward_Stress']);		
     $Phone_Number= mysqli_real_escape_string($conn,$_POST['Phone_Number']);
     $Status= 'not saved';
	 
     //$Emergency_Number = mysqli_real_escape_string($conn,$_POST['Emergency_Number']);		
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
	//insert query into tbl_dialysis
	
		$Donors_Sql = "insert into tbl_dead_regisration( Name_Of_Body,Time_For_Dead,Gender,Age,Reason,Description_Death,
		Morgue_Name,Deadline_To_Pick_Up,Member_Name,Relationship_Dead,Region,District,Ward_Stress,Phone_Number,Status)
		VALUES
		('$Name_Of_Body','$Time_For_Dead','$Gender','$Age','$Reason','$Description_Death','$Morgue_Name',
		'$Deadline_To_Pick_Up','$Member_Name','$Relationship_Dead','$Region','$District','$Ward_Stress','$Phone_Number',
		'$Status')";
		
					if(!mysqli_query($conn,$Donors_Sql)){
							die(mysqli_error($conn));
						die(mysqli_error($conn));
								$error = '1062yes';
								if(mysql_errno()."yes" == $error){ 
										$controlforminput = 'not valid';
								}
						}
						
			echo "<script type='text/javascript'>
								alert('ADDED SUCCESSFUL');
								document.location = './morgueRegistration.php?&DialysisForm=DialysisFormThisPage';
						</script>";			
}
?>



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

<br>
<br>
<br>

<fieldset>  <legend align="center"><b> DEAD BODY NOT REGISTERED IN HOSPITAL</b></legend>
 <form action='#' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">
          
        <center>
            <table width=100%>
               
                <tr>
                    <td width=100%>
                        <table width=100%>
							<tr>
                                <td style="text-align:right;"><b>Name of dead body</b></td>
                                <td><input type='text' name='Name_Of_Body' id='Name_Of_Body' required='required'></td>
                           
                             
                                <td  width='15%' style="text-align:right;"><b>Date of death</b></td>
                                <td><input type='text' name='Time_For_Dead' id='date2' required='required'></td>
                            </tr>
                            <tr>
                                <td style="text-align:right;"><b>Gender</b></td>
                                <td>
                                    <select name='Gender' id='Gender'>
                                        <option selected='selected'>Male</option>
                                        <option>Female</option>
                                    </select>
                                </td>
                            
						 <td width='6%' style="text-align:right;"><b>Age</b></td>
				         <td width=''><input name="Age" id="Age" required='required'></td>
						 </tr>
				         </tr>
                            <tr>
                                <td style="text-align:right;"><b>Reason for death</b></td>
                                <td>
                                 <select name='Reason' id='region'  required='required'>
                                 <option  ></option>
					             <?php
					            $data = mysqli_query($conn,"select * from tbl_Reason_Dead_Body");
					           while($row = mysqli_fetch_array($data)){
					           ?>
					       <option value='<?php echo $row['Reason_dead_body_ID'];?>'>
						  <?php echo $row['Reason_DeadBody']; ?>
					    </option>
				     	<?php
					    }
					?>
                                    </select>
                                </td> 
								
							</input></td>
							<td style="text-align:right;"><b>Description</b></td>
                                <td><textarea name="Description_Death" rows="2" cols="10" >
							</textarea></td>
                            </tr>
                            <tr>
                                <td style="text-align:right;"><b>Morgue name</b></td>
								<td>
                                 <select name='Morgue_Name' id='region' required='required'>
                                 <option  ></option>
					             <?php
					            $data = mysqli_query($conn,"select * from tbl_Morgue_Name");
					           while($row = mysqli_fetch_array($data)){
					           ?>
					       <option value='<?php echo $row['Morgue_Name'];?>'>
						  <?php echo $row['Morgue_Name']; ?>
					    </option>
				     	<?php
					         }
					       ?>
                                    </select>
                                </td> 
                                <td style="text-align:right;"><b>Deadline to pick up </b></td>
                                <td><input type='text' name='Deadline_To_Pick_Up' id='date_From1'></td>
                            </tr>
							 
                        </table>
                    </td>
                    
                    
             
                </tr>
            </table>
        </center>
</fieldset><br/>
<fieldset>
<legend align="center"><b> PARTICULAR DETAILS OF MEMBER FAMILY</b></legend>
    <table  width='100%'>
	
	<tr>
	    
	    <td width='13%'style="text-align:right;"><b>Name</b></td>
	    <td width='16%'><input type='text' id='Kin_Name' name='Member_Name' required='required'></td>
	    
	    <td width='13%'style="text-align:right;"><b>Relationship</b></td>
	    <td width='16%'><input type='text' id='Kin_Area' name='Relationship_Dead' required='required'></td>

	</tr>
	
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
	    <td style="text-align:right;"><b>Ward/Area</b></td>
	    <td><input type='text' id='Kin_Address' name='Ward_Stress'requred='required'></td>
		<td style="text-align:right;"><b>Phone </b></td>
	    <td><input type='text' id='Office_Phone' name='Phone_Number'required='required'></td>
	     </tr>
	  
		   <td colspan=4 style='text-align: center;'>
				<input type='submit' name='submit' id='submit'  value=' SAVE   ' class='art-button-green'>
				 <input type='reset' name='clear' id='clear' value=' CLEAR ' class='art-button-green' >
				<input type="hidden"   name='submitmorgueform' value='true'>
				<a href='editsearchregitration.php' class='art-button-green'>SEARCH TO EDIT </a>
			</td>
                           
    </table>
	   </form>
	<script src="css/jquery.datetimepicker.js"></script>
	<script>
	$('#date_From1').datetimepicker({
	dayOfWeekStart : 1,
	lang:'en',
	startDate:	'now'
	});
	$('#date_From').datetimepicker({value:'',step:30});
	$('#date_To').datetimepicker({
	dayOfWeekStart : 1,
	lang:'en',
	startDate:'now'
	});
	$('#date_To').datetimepicker({value:'',step:30});
	</script>
	<!--End datetimepicker-->
</fieldset>
<?php
    include("./includes/footer.php");
?>