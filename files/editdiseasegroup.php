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
        if(strtolower($_SESSION['userinfo']['Setup_And_Configuration']) == 'yes' || strtolower($_SESSION['Mtuha_Reports']) == 'yes'){
?>
    <a href='editdiseasegrouplist.php?EditDiseaseList=EditDiseaseListThisPage' class='art-button-green'>
        EDIT DISEASE GROUP
    </a>
<?php  } } ?>


<?php
    if(isset($_SESSION['userinfo'])){
        if(strtolower($_SESSION['userinfo']['Setup_And_Configuration']) == 'yes' || strtolower($_SESSION['Mtuha_Reports']) == 'yes'){
?>
    <a href='editdiseasegrouplist.php?EditDiseaseList=EditDiseaseListThisPage' class='art-button-green'>
        BACK
    </a>
<?php  } } ?> 

<?php
    
    //get disease ID
    if(isset($_GET['disease_group_ID'])){
        $disease_group_id = $_GET['disease_group_ID'];
    }else{
        $disease_group_id = 0;
    }
    
    //get all data from the database
    $select = "select * from tbl_disease_group dg where dg.disease_group_id = '$disease_group_id'";
    $result = mysqli_query($conn,$select) or die(mysqli_error($conn));
    $no = mysqli_num_rows($result);
    if($no > 0){
        while($row = mysqli_fetch_array($result)){
            $disease_group_name = $row['disease_group_name'];
            $Gender_Type = $row['Gender_Type'];
            $Age_Between_1_Year_But_Below_5_Year = $row['Age_Between_1_Year_But_Below_5_Year'];
            $Age_60_Years_And_Above = $row['Age_60_Years_And_Above'];
            $Age_Between_1_Month_But_Below_1_Year = $row['Age_Between_1_Month_But_Below_1_Year'];
            $Age_Below_1_Month = $row['Age_Below_1_Month'];
            $Five_Years_Or_Below_Sixty_Years = $row['Five_Years_Or_Below_Sixty_Years'];
            $Opd_Report = $row['Opd_Report'];
            $Opd_Option = $row['Opd_Report'];
			$Ipd_Option = $row['Ipd_Report'];
			$DENTAL_Option = $row['Dental_Report'];
			$Ipd_disease_Form_Id = $row['Ipd_disease_Form_Id'];
			$Opd_disease_Form_Id = $row['Opd_disease_Form_Id'];
			$Dental_disease_Form_Id = $row['Dental_disease_Form_Id'];
			$disease_group_for = $row['disease_group_for'];
        }
    }else{
        $disease_group_name = '';
        $Gender_Type = '';
        $Age_Between_1_Year_But_Below_5_Year = '';
        $Age_60_Years_And_Above = '';
        $Age_Between_1_Month_But_Below_1_Year = '';
        $Age_Below_1_Month = '';
        $Five_Years_Or_Below_Sixty_Years = '';
        $Opd_Report = '';
        $dhis_Form_Id = '';
        $Opd_Option = '';
		$Ipd_Option = '';
		$DENTAL_Option = '';
		$Ipd_disease_Form_Id = '';
		$Opd_disease_Form_Id = '';
		$Dental_disease_Form_Id = '';
    }

?>
<script type="text/javascript" language="javascript">
	function setDhis2ID(chbox){
		if(chbox.checked == true){
			document.getElementById('disease_Form_Id').removeAttribute('disabled');
			document.getElementById('disease_Form_Id').setAttribute('required','required');
		}else{
			document.getElementById('disease_Form_Id').setAttribute('disabled','disabled');
		}
	}
</script>
<script src="js/functions.js"></script>
<br/><br/>
<center>

<?php
    if(isset($_POST['submittedAddNewItemForm'])){ 
		$disease_name = mysqli_real_escape_string($conn,$_POST['disease_group_name']);
		$Gender_Type = mysqli_real_escape_string($conn,$_POST['Gender_Type']);
		$Ipd_disease_Form_Id = mysqli_real_escape_string($conn,$_POST['Ipd_disease_Form_Id']);
		$Opd_disease_Form_Id = mysqli_real_escape_string($conn,$_POST['Opd_disease_Form_Id']);
		$DENTAL_disease_Form_Id = mysqli_real_escape_string($conn,$_POST['DENTAL_disease_Form_Id']);
		$disease_group_for = mysqli_real_escape_string($conn,$_POST['disease_group_for']);

		//initialize variables.......
		$Age_Between_1_Year_But_Below_5_Year = 'No';
		$Age_60_Years_And_Above = 'No';
		$Age_Between_1_Month_But_Below_1_Year = 'No';
		$Age_Below_1_Month = 'No';
		$Five_Years_Or_Below_Sixty_Years = 'No';
		$Opd_Option = 'No';
		$Ipd_Option = 'No';
		$DENTAL_Option = 'No';

		//assign value if and only if selected...............
		if(isset($_POST['Age_Between_1_Year_But_Below_5_Year'])) { $Age_Between_1_Year_But_Below_5_Year = 'yes'; } 
		if(isset($_POST['Age_60_Years_And_Above'])) { $Age_60_Years_And_Above = 'yes'; } 
		if(isset($_POST['Age_Between_1_Month_But_Below_1_Year'])) { $Age_Between_1_Month_But_Below_1_Year = 'yes'; } 
		if(isset($_POST['Age_Below_1_Month'])) { $Age_Below_1_Month = 'yes'; } 
		if(isset($_POST['Five_Years_Or_Below_Sixty_Years'])) { $Five_Years_Or_Below_Sixty_Years = 'yes'; }
		if(isset($_POST['Opd_Option'])) { $Opd_Option = 'yes'; }
		if(isset($_POST['Ipd_Option'])) { $Ipd_Option = 'yes'; }
		if(isset($_POST['DENTAL_Option'])) { $DENTAL_Option = 'yes'; }


		
		$Insert_New_Item = "UPDATE tbl_disease_group set disease_group_name = '$disease_name', Gender_Type = '$Gender_Type', 
							Age_60_Years_And_Above = '$Age_60_Years_And_Above', Age_Between_1_Year_But_Below_5_Year = '$Age_Between_1_Year_But_Below_5_Year', 
							Five_Years_Or_Below_Sixty_Years = '$Five_Years_Or_Below_Sixty_Years', Age_Between_1_Month_But_Below_1_Year = '$Age_Between_1_Month_But_Below_1_Year',
							Age_Below_1_Month = '$Age_Below_1_Month', Opd_Report = '$Opd_Option', Ipd_Report = '$Ipd_Option', 
							Opd_disease_Form_Id = '$Opd_disease_Form_Id', Ipd_disease_Form_Id = '$Ipd_disease_Form_Id',
							Dental_disease_Form_Id = '$DENTAL_disease_Form_Id',Dental_Report = '$DENTAL_Option',disease_group_for='$disease_group_for' where disease_group_ID = '$disease_group_id'";
								 
		if(!mysqli_query($conn,$Insert_New_Item)){
				$error = '1062yes';
				if(mysql_errno()."yes" == $error){
				    
					echo '<script>
							alert("DISEASE GROUP ALREADY EXISTS!\nTRY ANOTHER NAME");
							document.location="./editdiseasegroup.php?disease_group_ID='.$disease_group_id.'&EditDiseaseGroup=EditDiseaseThisForm";
					    </script>';
				    
				}else{
					
					echo '<script>
							alert("PROCESS FAIL! TRY AGAIN");
							document.location="./editdiseasegroup.php?disease_group_ID='.$disease_group_id.'&EditDiseaseGroup=EditDiseaseThisForm";
					    </script>
					<?php';
				}
		}
		else {
		    echo '<script>
			alert("DISEASE GROUP EDITED SUCCESSFULY");
			document.location="./editdiseasegrouplist.php?EditDiseaseList=EditDiseaseListThisPage";
		    </script>';	
		}
    }
?>
<br/>   
<form action='#' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">
	<fieldset>  
        <legend align=center><b>EDIT DISEASE GROUP</b></legend>
    
        <table width=80%>
            <tr>
                <td style='text-align: center;' colspan="2">
                    <table width="100%">
                    	<tr>
                    		<td width="15%" style="text-align: right;">Group Name</td>
                    		<td width="40%">
                    			<input type='text' name='disease_group_name' size="5" id='disease_group_name' required='required' placeholder='Enter Group Name' autocomplete='off' value="<?php echo $disease_group_name; ?>">
                    		</td>
							<td width="20%">Gender Affected
								<select name='Gender_Type' id='Gender_Type' required='required'>
								    <option selected='selected'><?php echo $Gender_Type; ?></option>
								    <?php if(strtolower($Gender_Type) != 'both'){ ?><option>Both</option><?php } ?>
								    <?php if(strtolower($Gender_Type) != 'male only'){ ?><option>Male Only</option><?php } ?>
								    <?php if(strtolower($Gender_Type) != 'female only'){ ?><option>Female Only</option><?php } ?>
								</select>
						    </td>
                                                    <td>
                                                        Disease Group For
                                                    </td>
                                                    <td>
                                                        <select required="" name="disease_group_for">
                                                            <option value=""></option>
                                                            <option value="icd_10" <?php if($disease_group_for=="icd_10")echo "selected='selected'";?>>ICD 10</option>
                                                            <option value="icd_9" <?php if($disease_group_for=="icd_9")echo "selected='selected'";?>>ICD 9</option>
                                                        </select>
                                                    </td>
                    	</tr>
                    </table>
                </td>
            </tr>
			<tr>
			    <td colspan=2>
					<fieldset>
					    <legend>AGE RANGE</legend>
					    <table width=100%>	
							<tr>
							    <td>
							    	<input type="checkbox" name="Age_Below_1_Month" id="Age_Below_1_Month" <?php if(strtolower($Age_Below_1_Month) == 'yes'){ echo 'checked="checked"'; } ?>>
									<label for="Age_Below_1_Month">Below One Month</label>
							    </td>
							    <td>
							    	<input type="checkbox" name="Age_Between_1_Month_But_Below_1_Year" id="Age_Between_1_Month_But_Below_1_Year" <?php if(strtolower($Age_Between_1_Month_But_Below_1_Year) == 'yes'){ echo 'checked="checked"'; } ?>>
									<label for="Age_Between_1_Month_But_Below_1_Year">One Month Or Below One Year</label>
							    </td>
							</tr>
							<tr>
							    <td>
							    	<input type="checkbox" name="Age_Between_1_Year_But_Below_5_Year" id="Age_Between_1_Year_But_Below_5_Year" <?php if(strtolower($Age_Between_1_Year_But_Below_5_Year) == 'yes'){ echo 'checked="checked"'; } ?>>
									<label for="Age_Between_1_Year_But_Below_5_Year">One Year Or Below Five Years</label>
							    </td>
							    <td>
							    	<input type="checkbox" name="Age_60_Years_And_Above" id="Age_60_Years_And_Above" <?php if(strtolower($Age_60_Years_And_Above) == 'yes'){ echo 'checked="checked"'; } ?>>
									<label for="Age_60_Years_And_Above">Sixty Years And Above</label>
							    </td>
							</tr> 
							<tr>
							    <td>
									<input type="checkbox" name="Five_Years_Or_Below_Sixty_Years" id="Five_Years_Or_Below_Sixty_Years" <?php if(strtolower($Five_Years_Or_Below_Sixty_Years) == 'yes'){ echo 'checked="checked"'; } ?>>
									<label for="Five_Years_Or_Below_Sixty_Years">Five Years Or Below Sixty Years</label>
							    </td>
							</tr>
			    		</table>
					</fieldset>
	    		</td>
			</tr>
			<tr>
			    <td colspan=2>
					<fieldset>
					    <legend>REPORTS</legend>
					    <table width=100%>	
							<tr>
							    <td width="6%" style="text-align: left;">
							    	<input type="checkbox" name="Opd_Option" id="Opd_Option" <?php if(strtolower($Opd_Option) == 'yes'){ echo 'checked="checked"'; } ?>>
							    	<label for="Opd_Option">OPD Report</label>
							    </td>
							    <td width="12%">
							    	Opd Report Position<input style="width : 40%;text-align: center" type='text' id='Opd_disease_Form_Id' name='Opd_disease_Form_Id' value="<?php echo $Opd_disease_Form_Id; ?>">
							    </td>
							    <td width="6%" style="text-align: left;">
							    	<input type="checkbox" name="Ipd_Option" id="Ipd_Option"  <?php if(strtolower($Ipd_Option) == 'yes'){ echo 'checked="checked"'; } ?>>
							    	<label for="Ipd_Option">IPD Report</label>
							    </td>
							    <td width="12%">
							    	Ipd Report Position<input style="width : 40%;text-align: center" type='text' id='Ipd_disease_Form_Id' name='Ipd_disease_Form_Id' value="<?php echo $Ipd_disease_Form_Id; ?>">
							    </td>
							    <td width="8%" style="text-align: left;">
							    	<input type="checkbox" name="DENTAL_Option" id="DENTAL_Option" <?php if(strtolower($DENTAL_Option) == 'yes'){ echo 'checked="checked"'; } ?>><label for="DENTAL_Option">DENTAL Report</label>
							    </td>
							    <td width="12%">
							    	<input style="width : 40%;text-align: center" type='text' id='DENTAL_disease_Form_Id' name='DENTAL_disease_Form_Id' value="<?php echo $Dental_disease_Form_Id; ?>">DENTAL Report Position
							    </td>
							</tr>
			    		</table>
					</fieldset>
	    		</td>
			</tr>
            <tr>
            	<td style='text-align: right; width="30%" '>
                    <input type='submit' name='submit' id='submit' value='   SAVE   ' class='art-button-green'>
                    <input type='reset' name='clear' id='clear' value=' CLEAR ' class='art-button-green'>
                    <input type='hidden' name='submittedAddNewItemForm' value='true'/> 
                </td>
            </tr>
        </table>
	</fieldset>
 </form>
 </center>
<?php
    include("./includes/footer.php");
?>