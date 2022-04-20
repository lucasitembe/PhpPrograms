<?php

 include("./includes/header.php");
    include("./includes/connection.php");
	if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Nurse_Station_Works'])){
	    if($_SESSION['userinfo']['Nurse_Station_Works'] != 'yes'){
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

<!-- new date function (Contain years, Months and days)--> 
<?php
 $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
	$Age ='';
    }
?>
<!-- end of the function -->




<?php
   if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Nurse_Station_Works'] == 'yes'){ 
?>
    <a href='checkedpatients.php' class='art-button-green'>
        VIEW CHECKED
    </a>
<?php  } } ?>


<?php
 if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Nurse_Station_Works'] == 'yes'){ 
?>
    <a href='searchPatients.php' class='art-button-green'>
        PATIENTS LISTS
    </a>
<?php  } } ?>

<?php

//    select patient information to perform check in process
    if(isset($_GET['Registration_ID'])){
        $Registration_ID = $_GET['Registration_ID']; 
        $select_Patient = mysqli_query($conn,"select Registration_ID,
                            Old_Registration_Number,Title,Patient_Name,
                                Date_Of_Birth,
                                    Gender,pr.Region,pr.District,pr.Ward,
                                        Member_Number,Member_Card_Expire_Date,
                                            pr.Phone_Number,Email_Address,Occupation,
                                                Employee_Vote_Number,Emergence_Contact_Name,
                                                    Emergence_Contact_Number,Company,Registration_ID
                                                        Employee_ID,Registration_Date_And_Time,Guarantor_Name
                                                  
                                      
                                      
                                      
                                      
                                      from tbl_patient_registration pr, tbl_sponsor sp 
                                        where pr.Sponsor_ID = sp.Sponsor_ID and 
                                              Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
        $no = mysqli_num_rows($select_Patient);
        
        if($no>0){
            while($row = mysqli_fetch_array($select_Patient)){
                $Registration_ID = $row['Registration_ID'];
                $Old_Registration_Number = $row['Old_Registration_Number'];
                $Title = $row['Title'];
                $Patient_Name = $row['Patient_Name'];
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
            $age = floor( (strtotime(date('Y-m-d')) - strtotime($Date_Of_Birth)) / 31556926)." Years";
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



<?php

//to select patient info from patient table,sponsor, patient payments, patient payment list,Employee 
	 if(isset($_GET['Registration_ID'])){ 
        $Registration_ID = $_GET['Registration_ID']; 
		 //$Consultant_ID = $_GET['Consultant_ID'];
		 //$Check_In_Type = $_GET['Check_In_Type'];
		 //$Patient_Payment_Item_List_ID = $_GET['Patient_Payment_Item_List_ID'];
		 
	$select_Patient = mysqli_query($conn,"SELECT Patient_Name, pr.Registration_ID, Guarantor_Name, Gender, Date_Of_Birth,
	pp.Patient_Payment_ID,Patient_Payment_Item_List_ID ,ppl.Patient_Direction, Consultant_ID, Consultant, Employee_Type
FROM 
		tbl_Patient_Registration pr, tbl_sponsor sp, tbl_patient_payments pp, tbl_patient_payment_item_list ppl,
		tbl_Employee e
WHERE 
 pr.Registration_ID = pp.Registration_ID AND
	pp.Patient_Payment_ID = ppl.Patient_Payment_ID AND 
		(ppl.Patient_Direction = 'Direct To Doctor Via Nurse Station' OR
			ppl.Patient_Direction = 'Direct To Clinic Via Nurse Station') AND
				e.Employee_Type = 'Doctor' and
					pr.sponsor_id = sp.sponsor_id and 
						ppl.Process_Status='not saved' and 
								pp.Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
	
	$no = mysqli_num_rows($select_Patient);
	}
	 if($no>0){
            while($row = mysqli_fetch_array($select_Patient)){
                $Registration_ID = $row['Registration_ID'];
                $Patient_Name = $row['Patient_Name'];
				$Date_Of_Birth = $row['Date_Of_Birth'];
                $Gender = $row['Gender'];
                $Guarantor_Name = $row['Guarantor_Name']; 
				 $Patient_Payment_ID = $row['Patient_Payment_ID'];
				 $Patient_Direction = $row['Patient_Direction'];
				 $Consultant_ID = $row['Consultant_ID'];
				 $Consultant = $row['Consultant'];
				$Employee_Type = $row['Employee_Type'];
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
	 
	}
	
	//to update the person who get sevice to this form
	
	
	?>
	
<?php
		//validation of nurse form
			if(isset($_POST['submitnurseform'])){       
        if(isset($_SESSION['userinfo'])){ 
           if(isset($_SESSION['userinfo']['Employee_ID'])){
                $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
           }else{
            $Employee_ID = 0;
           }
        }
		
		if(isset($_SESSION['userinfo'])){
            if(isset($_SESSION['userinfo']['Branch_ID'])){
                $Branch_ID = $_SESSION['userinfo']['Branch_ID'];
            }else{
                $Branch_ID = 0;
            }
        }	
				$Registration_ID = mysqli_real_escape_string($conn,$_POST['Registration_ID']);
			   $Allegies = mysqli_real_escape_string($conn,$_POST['Allegies']);
			   $Special_Condition= mysqli_real_escape_string($conn,$_POST['Special_Condition']);
			    $bmi= mysqli_real_escape_string($conn,$_POST['bmi']);
				
				
			
        //get employee id
        if(isset($_SESSION['userinfo'])){
            if(isset($_SESSION['userinfo']['Employee_ID'])){
                $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
            }else{
                $Employee_ID = 0;
            }
        }
		
		//get branch id
		if(isset($_SESSION['userinfo'])){
            if(isset($_SESSION['userinfo']['Branch_ID'])){
                $Branch_ID = $_SESSION['userinfo']['Branch_ID'];
            }else{
                $Branch_ID = 0;
            }
        }
		//end of nurseform validation
 
	    
		//insert patient to nurse form
		 $Nurse_Sql = "INSERT INTO tbl_nurse(
                    Registration_ID,Employee_ID,Branch_ID,Allegies,
					Special_Condition,bmi,Nurse_DateTime)
                    VALUES
						('$Registration_ID','$Employee_ID','$Branch_ID','$Allegies','$Special_Condition',
							'$bmi',(Select now()))";
								if(!mysqli_query($conn,$Nurse_Sql)){
									die(mysqli_error($conn));
								die(mysqli_error($conn));
										$error = '1062yes';
										if(mysql_errno()."yes" == $error){ 
												$controlforminput = 'not valid';
										}
								}
						else {
							$Nurse_ID_query=mysqli_query($conn,"select Nurse_ID from tbl_nurse 
							where employee_ID=$Employee_ID order by Nurse_DateTime DESC limit 1");
							$Nurse_ID=mysqli_fetch_array($Nurse_ID_query);
							$j=0;
							foreach($_POST['Vital_ID'] as $vital_id){
							$Vital_Value=$_POST['Vital_Value'];
							$nurse_id=$Nurse_ID['Nurse_ID'];
							$NurseVitals2=mysqli_query($conn,"insert into tbl_nurse_vital(Nurse_ID,Vital_ID,Vital_Value) 
							values
								($nurse_id,$vital_id,$Vital_Value[$j])");
							  $j++;
							  
							  
		//Check if patient to update  or insert data
			if(isset($_GET['Registration_ID'])){
	$check_update = mysqli_query($conn,"SELECT pm.Registration_ID, pp.Patient_Payment_ID,ppl.Patient_Payment_ID
	FROM 
			tbl_patient_registration pm, tbl_patient_payments pp, tbl_patient_payment_item_list ppl 
	WHERE 
			pm.Registration_ID= '$Registration_ID' AND pp.Patient_Payment_ID = ppl.Patient_Payment_ID");
	
//	$check_result = mysqli_query($conn,$qr_dialysis_check);
	if(mysqli_num_rows($check_update)>0){ 
	
	//update query into tbl_patient_payment_item_list
	$DialysisUpdate_Sql= mysqli_query($conn,"UPDATE tbl_patient_payment_item_list ppl
	SET 
	Process_Status='saved' 
		WHERE ppl.Patient_Payment_ID=(SELECT p.Patient_Payment_ID
							FROM 
							   tbl_patient_payments p
							WHERE
								p.Patient_Payment_ID = ppl.Patient_Payment_ID AND 
								p.Registration_ID ='$Registration_ID')");	
	}
}		 
}
}
		//validation and Msg
	
					echo "<script type='text/javascript'>
			    alert('ADDED SUCCESSFUL');
			    document.location = './searchPatients.php?NurseForm=NurseFormThisPage';
			    </script>";
		//End of validation and Msg
		}
   
//function of list of patients from reception
	$Vital =mysqli_query($conn,"SELECT Count(*) as vita  from tbl_patient_payment_item_list ppl
	where (ppl.Patient_Direction = 'Direct To Doctor Via Nurse Station'
	OR ppl.Patient_Direction = 'Direct To Clinic Via Nurse Station') ") or die(mysqli_error($conn));
	$Vitals = mysqli_num_rows($Vital);
	if($Vitals>0){
            while($row = mysqli_fetch_array($Vital)){
                $vita = $row['vita'];
				}
				}
	//end of function of count
   
?>

<center>
		<form action="#" method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">
		<br/>
		<fieldset style="width:80%">
						<legend align=right><b>PATIENT</b></legend>
				<center>
		<table class='hiv_table' style="width:100%;text-align:right;" >
		
			<tr>
				<td width='8%' style="text-align:right;"><b>Patient Name</b></td>
				<td width='20%' colspan="3"><input type="text" name='Patient_Name'  readonly='readonly' id='Patient_Name' value="<?php echo $Patient_Name; ?>" /> </td>
			
				<td width='8%' style="text-align:right;"><b>Patient No</b></td>
				<td width='10%'><input type="text" name='Registration_ID'id="Registration_ID"  readonly='readonly'  value="<?php echo $Registration_ID; ?>" /> </td>
				
				<td width='6%' style="text-align:right;"><b>Sponsor<b></td>
				<td width='10%' colspan="2"><input type="text" readonly='readonly' name="Guarantor_Name" id="Guarantor_Name" value="<?php echo $Guarantor_Name; ?>" ></td>
			</tr>
			<tr>
				<td width='8%' style="text-align:right;"><b>Gender</b></td>
				<td><input type="text"  name="Gender" id="Gender"  value="<?php echo$Gender; ?>" readonly='readonly' ></td>
				
				<td width='8%' style="text-align:right;"><b>Age</b></td>
				<td><input type="text" readonly='readonly' name="Age" id="Age" value="<?php echo$age; ?>" ></td>
				
				<td width='8%' style="text-align:right;"><b>Visit Date</b></td>
				<td ><input type="text" readonly='readonly'  name="Visit_Date" id="Visit_Date" value="<?php echo$Today; ?>" ></td>
				
				
			</tr>
	</table >
			</center>
			<hr>
		<!-- SCript of BMI calculate-->	
		<script type='text/javascript'>
			function calculateBMI(){
			var Weight = document.getElementById('Weight').value;
			var Height = document.getElementById('Height').value;
			if(Weight!=''&& Height!=''){
				if(Height!=0){
				var bmi = (Weight*10000)/(Height*Height);
				document.getElementById('bmi').value = bmi.toFixed(2);
				}
			}
			}
		</script>
		<!-- End of script of BMI -->	
	

	<table class='hiv_table' style="width:100%;text-align:right;margin-top:5px;" >
		<tr>
			<td rowspan='2'>
			<fieldset class='vital' style="Height:240px;overflow-y:scroll;">
			<?php

$result=mysqli_query($conn,"SELECT * FROM tbl_vital");
echo "<table  border='0'  bgcolor='white' >
<thead>
<tr>
<th>SN</th>
<th>Vital</th>
<th width='25%'>Value</th>
<th width='20%' colspan='2'>Evolution</th>
</tr>
</thead>";
$i=1;
while($row=mysqli_fetch_array($result))
{
echo "<tr>";
echo "<td>" .$i . "</td>";
echo "<td>" .$row['Vital'] . "</td>";
echo "<td > <input id='".$row['Vital']."' type='text'  name='Vital_Value[]'";
	if($row['Vital']=="Height" || $row['Vital']=="Weight"){
	echo " onkeyup='calculateBMI()'";
	}
echo ">
<input name='Vital_ID[]' type='hidden' value='" .$row['Vital_ID'] . "'></td>";
if(isset($_GET['Registration_ID']) )
	{
	?>
	<td>
	<div class="nurse_tabs" style="width:100px;Height:30px;padding:0px;"> 
	<a href='graphdata.php?Registration_ID=<?php echo $Registration_ID; ?>&Vital_ID=<?php echo $row['Vital_ID']; ?>&Vital=<?php echo $row['Vital']; ?>'  class='art-button-green'>history</a>
	</div>
	</td>
	<?php
	}
	else
	{
	?>
	<td><a href='#' >history</a></td>
	<?php
	}
	$i++;
		}
		?>	
	</tr>
</table>
</fieldset >
 <div>  <span style="font-size:14px;margin-left:4px;margin-right:2px;"><b>BMI</b></span>
 <span><input type="text" name="bmi" id="bmi" style="width:100px" >
 <span class="nurse_tabs" style="width:10px;Height:30px;">
 <a href='graphbmi.php?Registration_ID=<?php echo $Registration_ID; ?>&bmi=<?php echo $bmi; ?>'  class='art-button-green'>history</a>
		</span>
		</span>
		</div>
		</td>
		<td>	
		<fieldset>
	<table width='100%'>
		<tr>
			<td width="20%" style="text-align:right;"><b>Allegies</b></td>
			<td ><textarea rows="3" name="Allegies"  id="Allegies"  style="resize:none;"></textarea></td>
		</tr>
		<tr>
			<td style="text-align:right;" width="21%"><b>Special Condition</b></td>
			<td><textarea rows="3" name="Special_Condition" id="Special_Condition"  style="resize:none;" ></textarea></td>
		</tr>
	</table>
	
		</fieldset>
		</td>
	</tr>
	</table>
	<hr>
		<div style="width:100%;text-align:right;padding-right:10px;">
			<input type="submit" class="art-button-green"   name='submit' id='submit' value='SAVE'    >
			<input type='hidden' name='submitnurseform' value='true'/> 
		</div>
</fieldset>
</form>
	</center>
<?php
    include("./includes/footer.php");
?>