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
	
	
	
	
	//To get todays date
$Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
	$Age ='';
    }
//end of function
?>

<a href='searchdonorslist2.php' class='art-button-green'>
        BACK
    </a>

<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Reception_Works'] == 'yes'){ 
?>
    
<?php  } } ?>
<script type='text/javascript'>
    function access_Denied(){ 
   alert("Access Denied");
   document.location = "./index.php";
    }
</script>






<script type="text/javascript" language="javascript">
    function getBatches(Batch_Name) {
	    if(window.XMLHttpRequest) {
		mm = new XMLHttpRequest();
	    }
	    else if(window.ActiveXObject){ 
		mm = new ActiveXObject('Micrsoft.XMLHTTP');
		mm.overrideMimeType('text/xml');
	    }
	    
	    mm.onreadystatechange= AJAXP; //specify name of function that will handle server response....
	    mm.open('GET','GetBloodId.php?Batch_Name='+Batch_Name,true);
	    mm.send();
	}
    function AJAXP() {
	var data = mm.responseText;
	document.getElementById('Blood_ID').innerHTML = data;	
    }

</script>
<br/><br/>
<!--  insert data from the form  -->

<?php
			 if(isset($_GET['Registration_ID'])){ 
        $Registration_ID = $_GET['Registration_ID']; 

			 $select_Donors = mysqli_query($conn,"select Registration_ID,Patient_Name,b.Blood_ID Date_Of_Birth, Gender,
			 Phone_Number,Registration_Date_And_Time
		from tbl_patient_registration d,tbl_patient_blood_data b where d.Registration_ID = b.Donor_ID and
		Registration_ID='$Registration_ID' ") or die(mysqli_error($conn));
			
			 while($row = mysqli_fetch_array($select_Donors)){
			
               $Registration_ID = $row['Registration_ID'];
               $Donor_Name = $row['Patient_Name'];
               $Gender = $row['Gender'];
               $Date_Of_Birth = $row['Date_Of_Birth'];
			 
}				

}
 $Age = floor( (strtotime(date('Y-m-d')) - strtotime($Date_Of_Birth)) / 31556926)." Years";
 
	  if($Age == 0){
		$date1 = new DateTime($Today);
		$date2 = new DateTime($Date_Of_Birth);
		$diff = $date1 -> diff($date2);
		$Age = $diff->m." Months";
	    }
	    if($Age == 0){
		$date1 = new DateTime($Today);
		$date2 = new DateTime($Date_Of_Birth);
		$diff = $date1 -> diff($date2);
		$Age = $diff->d." Days";
}


    if(isset($_POST['submittedAddNewPatientForm'])){
	 $Registration_ID = $_GET['Registration_ID']; 
        
        if(isset($_SESSION['userinfo'])){ 
           if(isset($_SESSION['userinfo']['Employee_ID'])){
                $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
           }else{
            $Employee_ID = 0;
           }
        }
		
        
        $Blood_Runner = mysqli_real_escape_string($conn,$_POST['Blood_Runner']);
        $Blood_Expire_Date = mysqli_real_escape_string($conn,$_POST['Blood_Expire_Date']);
        $Blood_Batch = mysqli_real_escape_string($conn,$_POST['Blood_Batch']);
		$Date_Of_Transfusion = mysqli_real_escape_string($conn,$_POST['Date_Of_Transfusion']);
		$Blood_Volume=mysqli_real_escape_string($conn,$_POST['Blood_Volume']);
		$Blood_Group = mysqli_real_escape_string($conn,$_POST['Blood_Group']);
		
	$data = mysqli_query($conn,"select now() as Registration_Date_And_Time");
            while($row = mysqli_fetch_array($data)){
                $Registration_Date_And_Time = $row['Registration_Date_And_Time'];
            }
	    
	    
        $Insert_Sql = "insert into tbl_patient_blood_data(Donor_ID,
                    Date_Of_Transfusion,Blood_Group,Blood_Expire_Date,
                        Blood_Batch,Transfusion_Date_Time,Blood_Volume,Blood_Runner,Employee_ID)
	 values
	 ('$Registration_ID','$Date_Of_Transfusion','$Blood_Group','$Blood_Expire_Date',
                    '$Blood_Batch',(Select now()),'$Blood_Volume','$Blood_Runner','$Employee_ID')";
        if(!mysqli_query($conn,$Insert_Sql)){
            die(mysqli_error($conn));
	   
				
		}
		echo "<script type='text/javascript'>
			    alert('ADDED SUCCESSFUL');
			    document.location = './blooddonordata.php?Registration_ID=".$Registration_ID."&DonorsForm=DonorsThisPage';
			    </script>";
                                
    }
?>
   <center>              
<fieldset>  
            <legend align=center><b>BLOOD DONOR DATA</b></legend>
       
                <form action='#' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">
                        <table width=90%>
						
						<tr>
							<td width='10%' style="text-align:right;">Donor Name</td>
							<td width='20%' ><input type="text" name='Donor_Name'  readonly='readonly' id='Donor_Name' value="<?php echo $Donor_Name;?>" /> </td>
						
							<td width='8%' style="text-align:right;">Donor Number</td>
							<td width='8%'><input type="text" name="Registration_ID" id="Registration_ID"  value="<?php echo $Registration_ID; ?>" /> </td>
							
							
						
							<td width='6%' style="text-align:right;">Gender</td>
							<td width='6%'><input type="text"  name="Gender" id="Gender"  value="<?php echo $Gender;?>" disabled='disabled' ></td>
							
							<td width='6%' style="text-align:right;">Age</td>
							<td width='6%'><input type="text" disabled='disabled' name="Age" id="Age" value="<?php echo $Age;?>" ></td>
							
				</tr>
				
				 
				<tr>
				<td style="text-align:right;">Blood Batch</td>
					<td>
                                    <select name='Batch_Name' id='Batch_Name' onchange='getBatches(this.value)'>
                                        <option selected='selected' value=''></option>
					<?php
					    $data = mysqli_query($conn,"select * from tbl_blood_batches");
					        while($row = mysqli_fetch_array($data)){
					    ?>
					    <option value='<?php echo $row['Batch_Name'];?>'>
						<?php echo $row['Batch_Name']; ?>
					    </option>
					<?php
					    }
					?>
                                    </select>
									</td>
					
					
					<td style="text-align:right;">Blood Volume</td>
					<td width='6%'><input type='text'  name='Blood_Volume' id='Blood_Volume' placeholder='In milliliter'  ></td>
					</tr>
					
					
					<tr>
					<td style="text-align: right;">Blood Group</td>
					<td style="text-align: left;"> 
								   <select name='Blood_Group' id='Blood_Group' required='required' >
							       <option selected='selected'></option> 
			                       <option value='A+'>A+</option>
							       <option value='A-'>A-</option>
							       <option  value='B+'>B+</option>
							       <option  value='B-'>B-</option>
							       <option value='O+' >O+</option> 
							       <option value='O-' >O-</option>
							       <option  value='AB+'>AB+</option>
							       <option  value='AB-'>AB-</option>
						           </select>
					             </td>
								 
					
                               
					<td style="text-align:right;">Blood Runner</td>
						<td><input type='text' name='Blood_Runner' id='Blood_Runner' ></td>
					</tr>
					
					
				  
				
					 <tr>
						<td style="text-align:right;">Transfusion Date</td>
						<td><input type='text' name='Date_Of_Transfusion' id='date2' required='required'></td>
						
						
						
					
						
				                  
					</tr>
							
							
							<tr>
                                <td style="text-align:right;">Blood Expire Date</td>
                                <td><input type='text' name='Blood_Expire_Date' id='date'  required='required'></td>
								
								
								
								<link rel="stylesheet" href="css/smoothness/jquery-ui-1.10.1.custom.min.css" />
				<script src="js/jquery-1.9.1.js"></script>
				<script src="js/jquery-ui-1.10.1.custom.min.js"></script>
				<script>
					$(function () { 
						$("#date").datepicker({ 
							changeMonth: true,
							changeYear: true,
							showWeek: true,
							showOtherMonths: true,  
						   dateFormat: "yy-mm-dd",
							
						});
						
					});
				</script> 
                            </tr>
							
							<tr>
                                <td colspan=2 style='text-align:right;'>
                                    <input type='submit' name='submit' id='submit' value='   SAVE   ' class='art-button-green'>
                                    
                                    <a href='./barcode/BCGcode39.php?Registration_ID=<?php echo $Registration_ID; ?>&Barcode=BarcodeThisPage' target='_Blank' class='art-button-green'>PRINT CODE  
                                    </a>
									
                                    <input type='hidden' name='submittedAddNewPatientForm' value='true'/> 
                                </td>
								
								
                            </tr>
                        </table>
                    
                        
                            
			    
                   
                </form>
             
        
</fieldset><br/>
</center>
<?php
    include("./includes/footer.php");
?>