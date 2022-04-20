<?php
    include("./includes/header.php");
    include("./includes/connection.php");
	
?>
<?php
//Error_reporting(E_ERROR|E_PARSE);

    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Morgue_Works'] == 'yes'){ 
?><a href='editPersonPickingUpDeadBody.php?EditPickingBody=EditPickingBodyPage' class='art-button-green'>
EDIT
</a>
    <a href='searchdeadbody.php?DischargeDeadBody=DeliveryPage' class='art-button-green'>
        BACK
    </a>
<?php  } } ?>

<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Morgue_Works'] == 'yes'){ 
?>
    
<?php  } } //&Dead_ID=".$row['Dead_ID']."
if(isset($_GET['Registration_ID'])){ 
        $Dead_ID = $_GET['Registration_ID']; 
	}
	
if(isset($_GET['Dead_ID'])){ 
        $Dead_ID = $_GET['Dead_ID']; 
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

<?php 
//To get todays date
$Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
	$Age ='';
    }
//end of function

	//to take current time to the dialysis form
	$Dates=mysqli_query($conn,"SELECT NOW() as todaytime  ");

		while($row = mysqli_fetch_array($Dates)){
                $todaytime = $row['todaytime'];
				}
	//end of current time
				
//to select dead body into a deadbody table 
		if(isset($_GET['Dead_ID'])){  
		$Dead_ID = $_GET['Dead_ID']; 
		$select_Patient = mysqli_query($conn,"SELECT Dead_ID,Name_Of_Body,Gender,Age,Morgue_Name
FROM tbl_dead_regisration WHERE Dead_ID='$Dead_ID'") or die(mysqli_error($conn));
	
	$no = mysqli_num_rows($select_Patient);
										}
	 if($no>0){
            while($row = mysqli_fetch_array($select_Patient)){
                $Dead_ID = $row['Dead_ID'];
                $Name_Of_Body = $row['Name_Of_Body'];
				$Age = $row['Age'];
                $Gender = $row['Gender'];
                $Morgue_Name = $row['Morgue_Name']; 
                //$Patient_Payment_ID = $row['Patient_Payment_ID']; 
				
	}
		
	}
	
	
	
	
?>
<!-- SCRIPT OF GET CURRENT TIME INTO DIALYSIS FORM-->
<script type="text/javascript" language="javascript">
    function getTime(param) {
	if(window.XMLHttpRequest) {
		 ajaxTimeObjt = new XMLHttpRequest();
	     }
	     else if(window.ActiveXObject){ 
		 ajaxTimeObjt = new ActiveXObject('Micrsoft.XMLHTTP');
		 ajaxTimeObjt.overrideMimeType('text/xml');
	     }
		 ajaxTimeObjt.onreadystatechange= function (){
											var data = ajaxTimeObjt.responseText;
											document.getElementById(param).value = data;
											}; //specify name of function that will handle server response....
		 ajaxTimeObjt.open('GET','Get_Time.php',true);
		 ajaxTimeObjt.send();
	}
</script>
<br><br><br>
<!--  END OF SCRIPT -->

<!--  dialysis form-->
<center>
<form action="#" method="POST" name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">
<fieldset>
	<legend align="center"><b>PARTICULAR DETAILS OF DEAD BODY</b></legend>
	<table  class='hiv_table' width="100%">
		<tr>
				<td width='20%' style="text-align:left;">Name of dead body</td>
				<td width='15%' colspan="2" ><input type="text" name='Name_Of_Body'  disabled='disabled' id='Patient_Name' value="<?php echo $Name_Of_Body;?>" /> </td>
				
				<td width='10%' style="text-align:left;">Corpse No</td>
				<td width='10%' colspan="2" ><input type="text" name='Dead_ID'  readonly='readonly' id='Dead_ID' value="<?php echo $Dead_ID;?>" /> </td>
				
				
				<td width='6%' style="text-align:left;">Gender</td>
				<td width='6%'><input type="text"  name="Gender" id="Gender"  value="<?php echo $Gender;?>" disabled='disabled' ></td>
				<td width='6%' style="text-align:left;">Age</td>
				<td width='6%'><input type="text" readonly='readonly' name="Age" id="Age" value="<?php echo$Age;?>" ></td>
				<td width='10%' style="text-align:left;">Morgue Name</td>
				<td width='10%'><input type="text" name="Dead_ID" id="Registration_No"  value="<?php echo $Morgue_Name;?>" /> </td>
			
				
				
				
			</tr>
	</table>

</fieldset>

<fieldset>  
            <legend align="center"><b>PARTICULAR DETAILS OF A PERSON PICKING UP BODY</b></legend>
        <center>
         
                
                
                    <td width=35%>
                        <table width=100%>
						
							<tr>
							 <tr>
                                <td style="text-align:right;"><b>Name of person picking the body</b></td>
                                <td><input type='text' name='Person_Name' id='Person_Name' required='required'></td>
                                <td style="text-align:right;"><b>Gender</b></td>
                                <td>
                                    <select name='Gender' id='Gender'>
                                        <option selected='selected'>Male</option>
                                        <option>Female</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align:right;"><b>Person identification</b></td>
                                <td><input type='text' name='Person_ID' id='Mobile' required='required'></td>
                            
                                <td style="text-align:right;"><b>Morgue Name</b></td>
                                <td>
					
									<select name='Dead_ID' id='region' onchange='getDistricts(this.value)'>
                                    <option selected='selected'>Morgue Name</option>
					               <?php
											$data = mysqli_query($conn,"select * from tbl_Dead_ID");
											while($row = mysqli_fetch_array($data)){
											?>
												<option value='<?php echo $row['Dead_ID'];?>'>
												<?php echo $row['Dead_ID']; ?>
					               </option>
				     	           <?php
					         }
					       ?>
                                    </select></td>
                            </tr>
							<tr>
                                <td style="text-align:right;"><b>Phone number </b></td>
                                <td><input type='text' name='Phone_number' id='Donated_Patient' required="required"></td>
						<?php
						$select_date = mysqli_query($conn,"SELECT NOW() as time_now");
							$date_now =mysqli_fetch_array($select_date );
						?>
                                <td style="text-align:right;"><b>Transaction date </b></td>
                                <td><input type='text' name='Transaction_Date' id='Donated_Patient' value="<?php echo $date_now['time_now']; ?>"></td>
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
                                <td style="text-align:right;"><b>Aproved By</b></td>
                                <td>
								<center>
			<?php
		    if(isset($_SESSION['userinfo']['Employee_ID'])){
			$Employee_ID = $_SESSION['userinfo']['Employee_ID'];
			$select_Employee_Details = mysqli_query($conn,"select Employee_Name, Employee_ID from tbl_employee where Employee_ID = '$Employee_ID'");
			while($row = mysqli_fetch_array($select_Employee_Details)){
			    $Employee_Name = $row['Employee_Name'];
			    $Employee_ID = $row['Employee_ID'];
			}
		    }
				 	
				 
		 ?>
				<input type="text" name='administration' value="<?php echo ".$Employee_Name."?>"></center>
								
								</td>
                                <td style="text-align:right;"><b>Approval date</b></td>
                                <td><input type='text' name='Approval_Date' id='date2' required='required'></td>
                            </tr>
							 <tr>
                                <td colspan=4 style='text-align: center;'>
                                    <input type='submit' name='submit' id='submit' value=' SAVE ' class='art-button-green'>
                                    <input type='reset' name='clear' id='clear' value=' CLEAR ' class='art-button-green' onclick='clearPatientPicture()'>
                                   <input type="hidden"   name='submitpersonform' value='true'>
                                   <input type="hidden"   name='frmsubmitted' value='true'>
                                </td>
                            </tr>
                            
                        </table>
                    </td>
					
<?php
		
		if(isset($_POST['submitpersonform'])){

     $Person_Name = mysqli_real_escape_string($conn,$_POST['Person_Name']);		
     $Gender = mysqli_real_escape_string($conn,$_POST['Gender']);		
	 $Person_ID=mysqli_real_escape_string($conn,$_POST['Person_ID']);
     $Dead_ID = mysqli_real_escape_string($conn,$_POST['Dead_ID']);		
     $Phone_number = mysqli_real_escape_string($conn,$_POST['Phone_number']);
	 $Transaction_Date= mysqli_real_escape_string($conn,$_POST['Transaction_Date']);
	 $administration= mysqli_real_escape_string($conn,$_POST['administration']);	
	 $Region= mysqli_real_escape_string($conn,$_POST['Region']);	
     $District = mysqli_real_escape_string($conn,$_POST['District']);		
     $Approval_Date= mysqli_real_escape_string($conn,$_POST['Approval_Date']);
     $Status= 'not saved';
	

	if(isset($_GET['Dead_ID'])){  
		$Dead_ID = $_GET['Dead_ID']; 
   	
       
        if(isset($_SESSION['userinfo'])){
            if(isset($_SESSION['userinfo']['Employee_ID'])){
                $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
            }else{
                $Employee_ID = 0;
            }
        }
		
	//insert query into tbl_deadDelivery_person
	
		$deadDelivery_Sql = "INSERT into tbl_deadDelivery_person (Person_Name,Gender,Person_ID,Dead_ID,Phone_number,
		Transaction_Date,Employee_ID,Region,District,Approval_Date)
		VALUES
		('$Person_Name','$Gender','$Person_ID','$Dead_ID','$Phone_number','$Transaction_Date','$Employee_ID','$Region','$District','$Approval_Date')";
					
					if(!mysqli_query($conn,$deadDelivery_Sql)){
							die(mysqli_error($conn));
						die(mysqli_error($conn));
								$error = '1062yes';
								if(mysql_errno()."yes" == $error){ 
										$controlforminput = 'not valid';
								}
						}
			}
			
			
		if(isset($_GET['Dead_ID'])){  
		$Dead_ID = $_GET['Dead_ID']; 
		$select_update = mysqli_query($conn,"SELECT Dead_ID,Name_Of_Body,Gender,Age,Morgue_Name
FROM tbl_dead_regisration WHERE Dead_ID='$Dead_ID'") or die(mysqli_error($conn));
	
	$no = mysqli_num_rows($select_update);	
		
	if(mysqli_num_rows($select_update)>0){ 	
		
			$Update_Sql= mysqli_query($conn,"UPDATE tbl_dead_regisration
			SET 
			Status='saved' WHERE Dead_ID='$Dead_ID'");
			}
			}
			
				echo "<script type='text/javascript'>
								alert('ADDED SUCCESSFUL');
								document.location = './searchdeadbody.php?&DialysisForm=DialysisFormThisPage';
						</script>";				
}
?>
					
                    
                    
                
                </tr>
          
        </center>
</fieldset><br/>
</form>
<!--  End ofform-->
<br>
	
</center>

<!--  Script of date time picker to dialysis form must be end/after the input datetime-->
	
	<script src="css/jquery.datetimepicker.js"></script>
	<script>
	$('#date_From').datetimepicker({
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
<!--  end of script-->
<?php
    include("./includes/footer.php");
?>