<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Setup_And_Configuration'])){
	    if($_SESSION['userinfo']['Setup_And_Configuration'] != 'yes'){
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
        if($_SESSION['userinfo']['Nurse_Station_Works'] == 'yes'){ 
?>
    <a href='foodpage.php' class='art-button-green'>
        BACK
    </a>
<?php  } } ?>

<?php

	
	 	if(isset($_GET['Registration_ID'])){  
		$Registration_ID = $_GET['Registration_ID']; 
		$select_Patient = mysqli_query($conn,"SELECT Patient_Name, pr.Registration_ID, Guarantor_Name, 
		Gender, Date_Of_Birth, pp.Patient_Payment_ID,Patient_Payment_Item_List_ID ,Patient_Direction
		FROM tbl_Patient_Registration pr, tbl_sponsor sp, tbl_patient_payments pp, tbl_patient_payment_item_list ppl
		WHERE 
		pr.Registration_ID = pp.Registration_ID AND  pp.Patient_Payment_ID = ppl.Patient_Payment_ID AND 
		pr.sponsor_id = sp.sponsor_id and  ppl.Check_In_Type='Dialysis' AND Process_Status='saved' AND 
		pr.Registration_ID='$Registration_ID'") or die(mysqli_error($conn));
	
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
	
	
?>

<?php

if(isset($_POST['submitdietform'])){       
      
		$Registration_ID = mysqli_real_escape_string($conn,$_POST['Registration_ID']);
		$Description = mysqli_real_escape_string($conn,$_POST['Description']);
		$Restriction = mysqli_real_escape_string($conn,$_POST['Restriction']);
		$Meal_Time = mysqli_real_escape_string($conn,$_POST['Meal_Time']);
		$Diet_Std = mysqli_real_escape_string($conn,$_POST['Diet_Std']);
		$Meal_ID = '111';
		
		
		if(isset($_SESSION['userinfo'])){ 
           if(isset($_SESSION['userinfo']['Employee_ID'])){
                $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
           }else{
            $Employee_ID = 0;
           }
        }


//inseert query into tbl_dialysis
		$insert_Sql = "insert into tbl_fooddiet (Registration_ID,Description,Restriction,Meal_Time,Diet_Std,Employee_ID,Food_Date_Time,Food_Date,Meal_ID )
		VALUES
		('$Registration_ID','$Description','$Restriction','$Meal_Time','$Diet_Std','$Employee_ID',(select now()),(select now()),'$Meal_ID')";
		
					if(!mysqli_query($conn,$insert_Sql)){
							die(mysqli_error($conn));
						die(mysqli_error($conn));
								$error = '1062yes';
								if(mysql_errno()."yes" == $error){ 
										$controlforminput = 'not valid';
								}
						}
					echo "<script type='text/javascript'>
								alert('ADDED SUCCESSFUL');
							
						</script>";

}


?>



<br>
<center>
<form action="#" method="POST" name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">
<fieldset style="width:96%;margin-top:8px;">
	<table  class='hiv_table'>
		<tr>
				<td width='10%' style="resize:none;text-align:right;">Patient Name</td>
				<td width='20%' colspan="2"><input type="text" name='Patient_Name'  disabled='disabled' id='Patient_Name' value="<?php echo$Patient_Name;?>" /> </td>
			
				<td width='8%' style="resize:none;text-align:right;">Patient No</td>
				<td width='8%'><input type="text" name="Registration_ID" id="Registration_ID"  value="<?php echo$Registration_ID;?>" /> </td>
				
				<td width='6%' style="resize:none;text-align:right;">Sponsor</td>
				<td width='10%' colspan="2"><input type="text"  name="Guarantor_Name" id="Guarantor_Name" value="<?php echo$Guarantor_Name;?>" ></td>
			
				<td width='6%' style="resize:none;text-align:right;">Gender</td>
				<td width='6%'><input type="text"  name="Gender" id="Gender"  value="<?php echo$Gender;?>" disabled='disabled' ></td>
				
				<td width='6%' style="resize:none;text-align:right;">Age</td>
				<td width='6%'><input type="text" disabled='disabled' name="Age" id="Age" value="<?php echo$Age;?>" ></td>
		</tr>
	</table>
	<hr>
	<table  width="100%" >
		<tr>
	
			<td width='50px' style="resize:none;text-align:right;">Description</td><td><input type="text" name="Description" id="Description" ></td>
			<td width='50px' style="resize:none;text-align:right;">Restriction</td><td><input type="text" name="Restriction" id="Restriction" ></td>
			
			<td width='60px' style="resize:none;text-align:right;">Meal Time</td>
			<td>
				<select name="Meal_Time" id="Meal_Time" required='required'>
				   <option></option>
					<option>BREAKFAST</option>
					<option>LUNCH</option>
					<option>DINNER</option>
					
				</select>
			
			</td>
			
			<td width='50px' style="resize:none;text-align:right;">Diet Type</td>
			<td>
				<select name="Diet_Std" id="Diet_Std"  required='required'>
					<option></option>
					<option>Normal</option>
					<option>Standard</option>
					<option>Quality</option>
					
				</select>
			</td>
		</tr>
		<tr>
			<td colspan="6"></td>
			<td>
					<div style="width:100%;text-align:right;padding-right:100px;">
						<td ><input type="submit" value="ADD" name="" id="" class='art-button-green'>
							<input type='hidden' name='submitdietform' value='true'>
						</td>
					</div>
			</td>
		</tr>
	
			
	</table>
	</form>
<center>
            <table width="100%" border="1">
                <tr>
					<td id='Search_Iframe'>
				<iframe width='100%' height="240px" src='foodpage2_iframe.php?Registration_ID=<?php echo$Registration_ID;?>'></iframe>
					</td>
					<td id='Search_Iframe'>
				<iframe width='100%' height="240px" src='foodpage2_iframe2.php?Registration_ID=<?php echo$Registration_ID;?>'></iframe>
					</td>
					<td id='Search_Iframe'>
				<iframe width='100%' height="240px" src='foodpage2_iframe3.php?Registration_ID=<?php echo$Registration_ID;?>'></iframe>
					</td>
				</tr>
            </table>
</center>

</fieldset>

<br/>
<?php
    include("./includes/footer.php");
?>