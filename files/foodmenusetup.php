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
    <a href='foodworkpage.php?BloodBank=BloodBankThisPage' class='art-button-green'>
         BACK
    </a>
<?php  } } ?>

<?php

//to insert food of patients in table below of this form
	if(isset($_POST['submittedfoodMenuForm'])){       
      
		
	
		
	//	$Food_Standard = mysqli_real_escape_string($conn,$_POST['Food_Standard']);
		$Food_Time = mysqli_real_escape_string($conn,$_POST['Food_Time']);
		$Days_of_Week = mysqli_real_escape_string($conn,$_POST['Days_of_Week']);
		$Food_Name = mysqli_real_escape_string($conn,$_POST['Food_Name']);
		$Selling_Price_Standard = mysqli_real_escape_string($conn,$_POST['Selling_Price_Standard']);
		$Selling_Price_Quality = mysqli_real_escape_string($conn,$_POST['Selling_Price_Quality']);
		
	
		
		  if(isset($_SESSION['userinfo'])){
            if(isset($_SESSION['userinfo']['Employee_ID'])){
                $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
            }else{
                $Employee_ID = 0;
            }
        }


//insert query into `tbl_food_menu

		$insert_Sql = "INSERT INTO tbl_food_menu (Days_of_Week,Food_Time,Food_Name,Employee_ID,Selling_Price_Standard,Selling_Price_Quality)
		VALUES
		('$Days_of_Week','$Food_Time','$Food_Name','$Employee_ID','$Selling_Price_Standard','$Selling_Price_Quality')";
		
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
								document.location = './foodmenusetup.php';
								
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
<fieldset style="width:60%;">
	<legend align="center">NEW MENU</legend>
<form action="#" method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">
	<table width="60%" class='hiv_table'>
		<tr>
			<td style="text-align:right;">Day Of the Week</td>
			<td>
				<select name="Days_of_Week" id="Days_of_Week" required='required'>
					<option selected="selected"></option>
					<option>Monday</option>
					<option>Tuesday</option>
					<option>Wednesday</option>
					<option>Thursday</option>
					<option>Friday</option>
					<option>Saturday</option>
					<option>Sunday</option>
				</select>
			</td>
		</tr>
		<tr>	
			<td style="text-align:right;">Food Time</td>
			<td>
				<select name="Food_Time" id="Food_Time" required='required'>
					<option selected="selected"></option>
					<option>BREAKFAST</option>
					<option>LUNCH</option>
					<option>DINNER</option>
				</select>
			</td>
		</tr>
	<!--	<tr>	
			<td style="text-align:right;">Diet Type</td>
			<td>
				<select name="Food_Standard" id="Food_Standard" required='required'>
						<option selected="selected"></option>
						<option>Standard</option>
						<option>Quality</option>
				</select>
			</td>
		
		</tr> -->
			
		<tr>
			<td style="text-align:right;">Menu Name</td><td><input type="text" required='required' placeholder='Enter Menu Name'  name="Food_Name" id="Food_Name"></td>
			
		</tr>
		
		<tr>
			<td style="text-align:right;">Selling Price Standard</td><td><input type="text" required='required' placeholder='Enter Selling Price Standard' name="Selling_Price_Standard" id="Selling_Price_Standard"></td>
			
		</tr>
		<tr>
			<td style="text-align:right;">Selling Price Quality</td><td><input type="text" required='required' placeholder='Enter Selling Price Quality' name="Selling_Price_Quality" id="Selling_Price_Quality"></td>
			
		</tr>
		
		<tr>
			<td colspan=2 style='text-align: right;'>
				<input type='submit' name='submit' id='submit' value='SAVE' class='art-button-green'>
				<input type='reset' name='clear' id='clear'  value='CLEAR' class='art-button-green'>
				<input type='hidden' name='submittedfoodMenuForm' value='true'/> 
			</td>
		 </tr>
		
	
	</table>
</form>	
</fieldset>
</center>


<?php
    include("./includes/footer.php");
?>