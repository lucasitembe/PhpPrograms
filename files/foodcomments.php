<?php
    include("./includes/connection.php");
    include("./includes/header.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Food_And_Laundry_Works'])){
	    if($_SESSION['userinfo']['Food_And_Laundry_Works'] != 'yes'){
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
        if($_SESSION['userinfo']['Food_And_Laundry_Works'] == 'yes'){ 
?>
    <a href='foodpagecomment.php?BloodBank=BloodBankThisPage' class='art-button-green'>
         BACK
    </a>
<?php  } } ?>

<?php

	
	 	if(isset($_GET['Registration_ID'])){  
		$Registration_ID = $_GET['Registration_ID']; 
		$Food_transaction_ID = $_GET['Food_transaction_ID']; 
		$select_Patient = mysqli_query($conn,"SELECT Patient_Name, pr.Registration_ID, Guarantor_Name, Gender,
			Date_Of_Birth,Food_transaction_ID,ft.Food_Standard,ft.Food_Time,ft.Days_of_Week,fm.Food_Name,
			fm.Food_Menu_ID,ft.Food_Menu_ID,Comments
		FROM 
			tbl_Patient_Registration pr, tbl_sponsor sp, tbl_food_transaction ft,tbl_food_menu fm
		WHERE
			pr.sponsor_id = sp.sponsor_id AND 
				pr.Registration_ID = ft.Registration_ID  AND 
					fm.Food_Menu_ID=ft.Food_Menu_ID AND
						pr.Registration_ID='$Registration_ID' AND 
							Food_transaction_ID='$Food_transaction_ID'") or die(mysqli_error($conn));
	
	$no = mysqli_num_rows($select_Patient);
	  $Food_transaction_ID=0;							}
	 if($no>0){
            while($row = mysqli_fetch_array($select_Patient)){
                $Registration_ID = $row['Registration_ID'];
                $Patient_Name = ucwords(strtolower($row['Patient_Name']));
				$Date_Of_Birth = $row['Date_Of_Birth'];
                $Gender = $row['Gender'];
                $Guarantor_Name = $row['Guarantor_Name']; 
                $Food_transaction_ID = $row['Food_transaction_ID']; 
                $Food_Time = $row['Food_Time']; 
                $Food_Name = $row['Food_Name']; 
                $Days_of_Week = $row['Days_of_Week']; 
                $Food_Standard = $row['Food_Standard'];
				$Comments= $row['Comments'];
		
         
				
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
	
	//to insert food of patients in table below of this form
	if(isset($_POST['submittedfoodCommentForm'])){       
      
		$Registration_ID = mysqli_real_escape_string($conn,$_POST['Registration_ID']);
		$Comments = mysqli_real_escape_string($conn,$_POST['Comments']);
		
	if(isset($_POST['Checked'])) {
				$Checked = 'yes';
			}else{
				$Checked = 'no';
			} 
		
		  if(isset($_SESSION['userinfo'])){
            if(isset($_SESSION['userinfo']['Employee_ID'])){
                $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
            }else{
                $Employee_ID = 0;
            }
        }
		
//insert query into tbl_food_transaction_comment
		
		$insert_Sql = "INSERT INTO tbl_food_transaction_comment (Registration_ID,Trans_Date_Time,
				Employee_ID,Comments,Food_transaction_ID,Checked)
		VALUES
				('$Registration_ID',(select now()),'$Employee_ID','$Comments','$Food_transaction_ID',	
					'$Checked')";
		
				if(!mysqli_query($conn,$insert_Sql)){
							die(mysqli_error($conn));
						die(mysqli_error($conn));
								$error = '1062yes';
								if(mysql_errno()."yes" == $error){ 
										$controlforminput = 'not valid';
								}
						}
			echo "<script type='text/javascript'>
						alert(' ADDED SUCCESSFUL \\n  Transaction No ".mysql_insert_id()."');
						document.location = './foodpagecomment.php';
				</script>";
}	
?>
<script type='text/javascript'>
    function access_Denied(){ 
   alert("Access Denied");
   document.location = "./index.php";
    }
</script>
<br/><br/><br/>

<center>
<fieldset style="width:90%;">
	<legend align="center"><B>PATIENT ORDER</B></legend>
<form action="#" method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">
	<table  class='hiv_table'>
		<tr><td><br></td></tr>
		<tr>
			<td width='10%' style="resize:none;text-align:right;">Patient Name</td>
			<td width='20%' colspan="2"><input type="text" name='Patient_Name'  disabled='disabled' id='Patient_Name' value="<?php echo$Patient_Name;?>" /> </td>
		
			<td width='8%' style="resize:none;text-align:right;">Patient No</td>
			<td width='8%'><input type="text" name="Registration_ID" id="Registration_ID" readonly='readonly'  value="<?php echo$Registration_ID;?>" /> </td>
			
			<td width='6%' style="resize:none;text-align:right;">Sponsor</td>
			<td width='10%' colspan="2"><input type="text"  name="Guarantor_Name" id="Guarantor_Name" value="<?php echo$Guarantor_Name;?>" ></td>
		
			<td width='6%' style="resize:none;text-align:right;">Gender</td>
			<td width='6%'><input type="text"  name="Gender" id="Gender"  value="<?php echo$Gender;?>" disabled='disabled' ></td>
			
			<td width='6%' style="resize:none;text-align:right;">Age</td>
			<td width='6%'><input type="text" disabled='disabled' name="Age" id="Age" value="<?php echo$Age;?>" ></td>
		</tr>
	</table>
	<hr>
	<table width="100%">
		<tr>
			<td style="text-align:right;">Day Of the Week</td>
			<td>
				<input type="text" readonly='readonly' name="Days_of_Week" id="Days_of_Week" value="<?php echo$Days_of_Week;?>">
			</td>
			
			<td  style="text-align:right;">Food Time</td>
			<td>
				<input type="text" readonly='readonly' name="Food_Time" id="Food_Time" value="<?php echo$Food_Time;?>">
			</td>
		</tr>
		
		<tr>	
			<td  style="text-align:right;">Diet Type</td>
			<td>
				<input type="text" readonly='readonly' value="<?php echo$Food_Standard;?>" name="Food_Standard" id="Food_Standard">
			</td>
			
			<td  style="text-align:right;">Menu Name</td>
			<td><input type="text" readonly='readonly' name="Food_Name" id="Food_Name" value="<?php echo$Food_Name;?>" ></td>
		</tr>
		<tr>
		    <td  style="text-align:right;">Comments</td>
			<td>
			<textarea name="Comments" id="Comments" readonly='readonly'><?php echo$Comments;?></textarea>
			</td>
			<td  style="text-align:right;">Nurse Comments</td>
			<td>
			<textarea name="Comments" id="Comments" placeholder='put your comment here'></textarea>
			</td>
		</tr>
		<tr>
			<td></td>
			<td  style="text-align:right;">Get Food</td><td><input type="checkbox"  name="Checked" id="Checked"></td>
		</tr>			
		<tr>
			<td></td>
			<td colspan=2 style='text-align: right;'>
				<input type='submit' name='submit' id='submit' value='SAVE' class='art-button-green'>
				<input type='reset' name='clear' id='clear'  value='CLEAR' class='art-button-green'>
				<input type='hidden' name='submittedfoodCommentForm' value='true'/> 
			</td>
		 </tr>
	</table>
</form>	
</fieldset>
</center>

<?php
    include("./includes/footer.php");
?>