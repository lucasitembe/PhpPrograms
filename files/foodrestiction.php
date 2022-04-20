<?php
    include("./includes/connection.php");
    include("./includes/header.php");
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
    <a href='foodpagerestriction.php?BloodBank=BloodBankThisPage' class='art-button-green'>
         BACK
    </a>
<?php  } } ?>

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



		//select patient info
		if(isset($_GET['Registration_ID'])){  
			$Registration_ID = $_GET['Registration_ID']; 
			$select_Patient = mysqli_query($conn,"SELECT Patient_Name, pr.Registration_ID, Guarantor_Name, 
					Gender, Date_Of_Birth
					FROM 
						tbl_Patient_Registration pr, tbl_sponsor sp
				WHERE 
						pr.sponsor_id = sp.sponsor_id and  
				 pr.Registration_ID='$Registration_ID' ") or die(mysqli_error($conn));
	$no = mysqli_num_rows($select_Patient);
										}
	 if($no>0){
            while($row = mysqli_fetch_array($select_Patient)){
                $Registration_ID = $row['Registration_ID'];
                $Patient_Name = ucwords(strtolower($row['Patient_Name']));
				$Date_Of_Birth = $row['Date_Of_Birth'];
                $Gender = $row['Gender'];
                $Guarantor_Name = $row['Guarantor_Name']; 
            
	}
	 $Age = floor( (strtotime(date('Y-m-d')) - strtotime($Date_Of_Birth)) / 31556926)." Years";
	   // if($age == 0){
		
		$date1 = new DateTime($Today);
		$date2 = new DateTime($Date_Of_Birth);
		$diff = $date1 -> diff($date2);
		$Age = $diff->y." Years, ";
		$Age .= $diff->m." Months, ";
		$Age .= $diff->d." Days";
	}
	
	//to insert food of patients in table below of this form
	if(isset($_POST['submittedfoodForm'])){       
      
		$Registration_ID = mysqli_real_escape_string($conn,$_POST['Registration_ID']);
		$Restriction = mysqli_real_escape_string($conn,$_POST['Restriction']);
		$Description = mysqli_real_escape_string($conn,$_POST['Description']);
	
		  if(isset($_SESSION['userinfo'])){
            if(isset($_SESSION['userinfo']['Employee_ID'])){
                $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
            }else{
                $Employee_ID = 0;
            }
        }

//insert query into tbl_food_restriction
		$insert_Sql = "INSERT INTO tbl_food_restriction (Registration_ID,Restriction,Description,Employee_ID,Food_Restriction_Date_Time)
			VALUES
			('$Registration_ID','$Restriction','$Description','$Employee_ID',(select now()))";
		
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
								document.location = './foodpagerestriction.php';
						</script>";
}	
?>
<script type='text/javascript'>
    function access_Denied(){ 
   alert("Access Denied");
   document.location = "./index.php";
    }
</script>

<br/><br/><br/><br/><br/><br/>
<center>
<fieldset style="width:90%;">
	<legend align="center"><B>PATIENT RESTRICTION</B></legend>
<form action="#" method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">
	<table  class='hiv_table'>
		<tr>
				<td width='10%' style="resize:none;text-align:right;">Patient Name</td>
				<td width='20%' colspan="2"><input type="text" name='Patient_Name'  disabled='disabled' id='Patient_Name' value="<?php echo$Patient_Name;?>" /> </td>
			
				<td width='8%' style="resize:none;text-align:right;">Patient No</td>
				<td width='8%'><input type="text" name="Registration_ID" id="Registration_ID"  readonly='readonly' value="<?php echo$Registration_ID;?>" /> </td>
				
				<td width='6%' style="resize:none;text-align:right;">Sponsor</td>
				<td width='8%' colspan="2"><input type="text"  name="Guarantor_Name" disabled='disabled' id="Guarantor_Name" value="<?php echo$Guarantor_Name;?>" ></td>
			
				<td width='6%' style="resize:none;text-align:right;">Gender</td>
				<td width='6%'><input type="text"  name="Gender" id="Gender"  value="<?php echo$Gender;?>" disabled='disabled' ></td>
				
				<td width='6%' style="resize:none;text-align:right;">Age</td>
				<td width='18%'><input type="text" disabled='disabled' name="Age" id="Age" value="<?php echo$Age;?>" ></td>
		</tr>
		<tr>
		<td  style="resize:none;text-align:right;">Restriction</td>
		<td style="resize:none;text-align:right;">
			<select name="Restriction" id="Restriction">
				<option selected="selected"></option>
				<?php
					$data = mysqli_query($conn,"select * from tbl_food_restriction_setup");
						while($row = mysqli_fetch_array($data)){
					?>
					  <option value='<?php echo $row['Food_Restriction_Setup_ID']; ?>'><?php echo $row['Restriction_Name']; ?></option>
				  <?php
					}
				?>		
			</select>
		</td>
		<td  style="resize:none;text-align:right;">Description</td>
		<td colspan=10>
		    <textarea  rows=6 style="resize:none;"  name="Description" id="Description"> </textarea>
		</td>
		</tr>
		<tr>
			<td colspan=5 style='text-align: right;'>
				<input type='submit' name='submit' id='submit' value='SAVE' class='art-button-green'>
				<input type='reset' name='clear' id='clear'  value='CLEAR' class='art-button-green'>
				<input type='hidden' name='submittedfoodForm' value='true'/> 
			</td>
		 </tr>
	</table>	
</form>	
</fieldset>
</center>
<?php
    include("./includes/footer.php");
?>