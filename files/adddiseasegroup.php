<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
		if(isset($_SESSION['userinfo']['Setup_And_Configuration'])){
		    if(strtolower($_SESSION['userinfo']['Setup_And_Configuration']) != 'yes'  && strtolower($_SESSION['userinfo']['Mtuha_Reports']) != 'yes'){
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
	if(isset($_GET['section'])){
		if(strtolower($_GET['section']) == 'dhis'){
			echo "<a href='editdiseasegrouplist.php?section=DHIS&EditDiseaseList=EditDiseaseListThisPage' class='art-button-green'>EDIT DISEASE GROUP</a>";
			echo "<a href='mapdiseasegroup.php?section=DHIS&MapDiseaseGroup=MapDiseaseGroupThisPage' class='art-button-green'>BACK</a>";

		}else{
			echo "<a href='editdiseasegrouplist.php?EditDiseaseList=EditDiseaseListThisPage' class='art-button-green'>EDIT DISEASE GROUP</a>";
			echo "<a href='diseaseconfiguration.php?OtherConfigurations=OtherConfigurationsThisForm' class='art-button-green'>BACK</a>";
		}
	}else{
		echo "<a href='editdiseasegrouplist.php?EditDiseaseList=EditDiseaseListThisPage' class='art-button-green'>EDIT DISEASE GROUP</a>";
		echo "<a href='diseaseconfiguration.php?OtherConfigurations=OtherConfigurationsThisForm' class='art-button-green'>BACK</a>";
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

		
		$Insert_New_Item = "INSERT INTO tbl_disease_group(disease_group_name, Gender_Type, 
								Age_60_Years_And_Above, Age_Between_1_Year_But_Below_5_Year, 
								Five_Years_Or_Below_Sixty_Years, Age_Between_1_Month_But_Below_1_Year,
								Age_Below_1_Month, Opd_Report, Ipd_Report, 
								Opd_disease_Form_Id, Ipd_disease_Form_Id,
								Dental_disease_Form_Id,Dental_Report,disease_group_for
							)
							VALUES ('$disease_name','$Gender_Type',
									'$Age_60_Years_And_Above','$Age_Between_1_Year_But_Below_5_Year',
									'$Five_Years_Or_Below_Sixty_Years','$Age_Between_1_Month_But_Below_1_Year',
									'$Age_Below_1_Month','$Opd_Option','$Ipd_Option',
									'$Opd_disease_Form_Id','$Ipd_disease_Form_Id','$DENTAL_disease_Form_Id','$DENTAL_Option','$disease_group_for')";
		 $result_query=mysqli_query($conn,$Insert_New_Item) or die(mysqli_error($conn));
		
		if(!$result_query){
				$error = '1062yes';
				if(mysql_errno()."yes" == $error){
				    ?>
					    <script>
							alert("DISEASE GROUP ALREADY EXISTS!\nTRY ANOTHER NAME");
							document.location="./adddiseasegroup.php?AddNewItemCategory=AddNewItemCategoryThisPage";
					    </script>
				    <?php
				}else{
					?>
						<script>
							alert("PROCESS FAIL! TRY AGAIN");
							document.location="./adddiseasegroup.php?AddNewItemCategory=AddNewItemCategoryThisPage";
					    </script>
					<?php
				}
		}
		else {
		    echo '<script>
			alert("DISEASE GROUP ADDED SUCCESSFULY");
			document.location="./adddiseasegroup.php?AddNewItemCategory=AddNewItemCategoryThisPage";
		    </script>';	
		}
    }
?>
<br/>   
<form action='#' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">
	<fieldset>  
        <legend align=center><b>ADD DISEASE GROUP</b></legend>
    
        <table width=100%>
            <tr>
                <td style='text-align: center;' colspan="2">
                    <table width="100%">
                    	<tr>
                    		<td width="15%" style="text-align: right;">Group Name</td>
                    		<td width="50%">
                    			<input type='text' name='disease_group_name' size="5" id='disease_group_name' required='required' placeholder='Enter Group Name' autocomplete='off'>
                    		</td>
							<td width="20%">Gender Affected
								<select name='Gender_Type' id='Gender_Type' required='required'>
								    <option selected='selected'></option>
								    <option>Both</option>
								    <option>Male Only</option>
								    <option>Female Only</option>
								</select>
						    </td>
                                                    <td>
                                                        Disease Group For
                                                    </td>
                                                    <td>
                                                        <select required="" name="disease_group_for">
                                                            <option value=""></option>
                                                            <option value="icd_10">ICD 10</option>
                                                            <option value="icd_9">ICD 9</option>
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
							    	<input type="checkbox" name="Age_Below_1_Month" id="Age_Below_1_Month">
									<label for="Age_Below_1_Month">Below One Month</label>
							    </td>
							    <td>
							    	<input type="checkbox" name="Age_Between_1_Month_But_Below_1_Year" id="Age_Between_1_Month_But_Below_1_Year">
									<label for="Age_Between_1_Month_But_Below_1_Year">One Month Or Below One Year</label>
							    </td>
							</tr>
							<tr>
							    <td>
							    	<input type="checkbox" name="Age_Between_1_Year_But_Below_5_Year" id="Age_Between_1_Year_But_Below_5_Year">
									<label for="Age_Between_1_Year_But_Below_5_Year">One Year Or Below Five Years</label>
							    </td>
							    <td>
							    	<input type="checkbox" name="Age_60_Years_And_Above" id="Age_60_Years_And_Above">
									<label for="Age_60_Years_And_Above">Sixty Years And Above</label>
							    </td>
							</tr> 
							<tr>
							    <td>
									<input type="checkbox" name="Five_Years_Or_Below_Sixty_Years" id="Five_Years_Or_Below_Sixty_Years">
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
					    <legend>REPORTS CONFIGURATION</legend>
					    <table width=100%>	
							<tr>
							    <td width="6%" style="text-align: left;">
							    	<input type="checkbox" name="Opd_Option" id="Opd_Option"><label for="Opd_Option">OPD Report</label>
							    </td>
							    <td width="12%">
							    	<input style="width : 40%;text-align: center" type='text' autocomplete='off' id='Opd_disease_Form_Id' name='Opd_disease_Form_Id'>OPD Report Position
							    </td>
							    <td width="6%" style="text-align: left;">
							    	<input type="checkbox" name="Ipd_Option" id="Ipd_Option"><label for="Ipd_Option">IPD Report</label>
							    </td>
							    <td width="12%">
							    	<input style="width : 40%;text-align: center" type='text' autocomplete='off' id='Ipd_disease_Form_Id' name='Ipd_disease_Form_Id'>IPD Report Position
							    </td>
							    <td width="8%" style="text-align: left;">
							    	<input type="checkbox" name="DENTAL_Option" id="DENTAL_Option"><label for="DENTAL_Option">DENTAL Report</label>
							    </td>
							    <td width="12%">
							    	<input style="width : 40%;text-align: center" type='text' autocomplete='off' id='DENTAL_disease_Form_Id' name='DENTAL_disease_Form_Id'>DENTAL Report Position
							    </td>
							</tr>
			    		</table>
					</fieldset>
	    		</td>
			</tr>
            <tr>
	            <td style="text-align: center;">
	            	<i><b>NB: DENTAL REPORT CONFIGURATION DOES NOT REQUIRE AGE RANGE CONFIGURATION</b></i>
	            </td>
            	<td style='text-align: right; width="30%" '>
                    <input type='submit' name='submit' id='submit' value='   SAVE   ' class='art-button-green'>
                    <input type='reset' name='clear' id='clear' value=' CLEAR ' class='art-button-green'>
                    <input type='hidden' name='submittedAddNewItemForm' value='true'/> 
                </td>
            </tr>
        </table><br/>
	</fieldset>
 </form>
 </center>
<?php
    include("./includes/footer.php");
?>