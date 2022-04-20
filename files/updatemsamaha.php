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

<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Morgue_Works'] == 'yes'){ 
?>
    
<?php  } } ?>


<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Morgue_Works'] == 'yes'){ 
?>

<?php  } } ?>


<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Morgue_Works'] == 'yes'){ 
?>
    <a href='msamahalist2.php?EditItem=EditItemThisPage' class='art-button-green'>
        BACK
    </a>
<?php  } } ?>

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
    

   
</script>

<br/><br/>
<center>

<?php
    //get all details from tbl_msamaharegistration
    if(isset($_GET['msamaha_ID'])){
	$msamaha_ID = $_GET['msamaha_ID'];
    }else{
	$msamaha_ID = '';
    }
    
    $Results = mysqli_query($conn,"select * from tbl_msamaha where msamaha_ID='$msamaha_ID' ");
    $no = mysqli_num_rows($Results);
    if($no > 0){
	while($row = mysqli_fetch_array($Results)){
$msamaha_ID=$row['msamaha_ID'];
$Patient_Name=$row['Patient_Name'];
$Ward_Number=$row['Ward_Number']; 
$Date_Birth=$row['Date_Birth'];
$Gender=$row['Gender'];
$Marital_Status=$row['Marital_Status'];
$Occupation=$row['Occupation'];
$Phone_Number=$row['Phone_Number'];
$Reason_Exception=$row['Reason_Exception'];
$Name_Approval=$row['Name_Approval'];
$Dat_Accident=$row['Dat_Accident']; 
$Name_Balozi=$row['Name_Balozi']; 
$Region=$row['Region']; 
$District=$row['District']; 
$Ward=$row['Ward'];
$Education_Level=$row['Education_Level'];      
$Work_Wife=$row['Work_Wife'];
$Mahudhulio=$row['Mahudhulio']; 
$Idadi_Mahudhulio=$row['Idadi_Mahudhulio']; 
$Emergence_Contact=$row['Emergence_Contact'];
$Pepared_By=$row['Prepared_By'];
	  
	}
    }
?>



<?php
    if(isset($_POST['submittedEditItemForm'])){
		 $Patient_Name= mysqli_real_escape_string($conn,$_POST['Patient_Name']);
		 $Ward_Number= mysqli_real_escape_string($conn,$_POST['Ward_Number']); 
		 $Date_Birth= mysqli_real_escape_string($conn,$_POST['Date_Birth']);
		 $Gender= mysqli_real_escape_string($conn,$_POST['Gender']);
		 $Marital_Status= mysqli_real_escape_string($conn,$_POST['Marital_Status']);
		 $Occupation= mysqli_real_escape_string($conn,$_POST['Occupation']);
		 $Phone_Number= mysqli_real_escape_string($conn,$_POST['Phone_Number']);
		 $Reason_Exception= mysqli_real_escape_string($conn,$_POST['Reason_Exception']);
		 $Name_Approval= mysqli_real_escape_string($conn,$_POST['Name_Approval']);
	    $Dat_Accident= mysqli_real_escape_string($conn,$_POST['Dat_Accident']); 
		$Name_Balozi= mysqli_real_escape_string($conn,$_POST['Name_Balozi']); 
		$Region= mysqli_real_escape_string($conn,$_POST['Region']); 
		$District= mysqli_real_escape_string($conn,$_POST['District']); 
		$Ward= mysqli_real_escape_string($conn,$_POST['Ward']);
		$Education_Level= mysqli_real_escape_string($conn,$_POST['Education_Level']);      
		$Work_Wife= mysqli_real_escape_string($conn,$_POST['Work_Wife']);
		$Mahudhulio= mysqli_real_escape_string($conn,$_POST['Mahudhulio']); 
		$Idadi_Mahudhulio= mysqli_real_escape_string($conn,$_POST['Idadi_Mahudhulio']); 
		$Emergence_Contact= mysqli_real_escape_string($conn,$_POST['Emergence_Contact']);
		$Prepared_By= mysqli_real_escape_string($conn,$_POST['Prepared_By']);
	   $select_dead="SELECT * FROM tbl_msamaha WHERE msamaha_ID = '$msamaha_ID' ";
	
	    $check_result = mysqli_query($conn,$select_dead);
	    if(mysqli_num_rows($check_result)>0){ 
	
	
	$Update_New_Item = "UPDATE tbl_msamaha
			    SET 
				 Patient_Name='$Patient_Name',
				 Ward_Number='$Ward_Number', 
				 Date_Birth='$Date_Birth',
				 Gender='$Gender',
				Marital_Status='$Marital_Status',
				 Occupation='$Occupation',
				 Phone_Number='$Phone_Number',
				Reason_Exception='$Reason_Exception',
				Name_Approval='$Name_Approval',
				Dat_Accident='$Dat_Accident', 
				Name_Balozi='$Name_Balozi', 
				Region='$Region', 
				District='$District', 
				Ward='$Ward',
				Education_Level='$Education_Level',      
				Work_Wife='$Work_Wife',
				Mahudhulio='$Mahudhulio', 
				Idadi_Mahudhulio='$Idadi_Mahudhulio', 
				Emergence_Contact='$Emergence_Contact',
				Prepared_By='$Prepared_By' where msamaha_ID = '$msamaha_ID'";
								
	if(!mysqli_query($conn,$Update_New_Item)){
							die(mysqli_error($conn));
						die(mysqli_error($conn));
								$error = '1062yes';
								if(mysql_errno()."yes" == $error){ 
										$controlforminput = 'not valid';
								}
						}
					echo "<script type='text/javascript'>
								alert('DETAIL UPDATED SUCCESSFUL');
								document.location = 'msamahalist2.php?EditForm=UpdatedInformFormThisPage';
						</script>";
	}	
    }
?>
<fieldset>  
            <legend align="center"><b>FORM YA MSAMAHA</b></legend>
			</br>
        <center>
            <table width=100%>
                <form action='#' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">
                <tr>
                    <td width=35%>
                        <table width=100%>
						    <tr>
                                <td><b>Jina la mgojwa </b></td>
                                <td>
                                   <input type='text' name='Patient_Name' id='Patient_Name' value='<?php echo $Patient_Name;?>'/>
                                </td>
                            </tr>
						    <tr>
                                <td><b>Nambari ya wodi/kliniki ya wagojwa wa nje</b></td>
                                <td><input type='text' name='Ward_Number' id='Ward_Number' required='required' value='<?php echo $Ward_Number;?>'></td>
                            </tr>
                            
							 <tr>
                                <td><b>Tarehe ya kuzaliwa/Umri </b></td>
                                <td><input type='text' name='Date_Birth' id='date2' value='<?php echo $Date_Birth;?>'></td>
                            </tr>
							   <tr>
                                <td><b>Jinsia</b></td>
                                <td>
                                    <select name='Gender' id='Gender' value='<?php echo $Gender;?>'>
                                        <option selected='selected'>Me</option>
                                        <option>Ke</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td><b>Hali ya ndoa</b></td>
                                <td><input type='text' name='Marital_Status' id='Marital_Status' value='<?php echo $Marital_Status;?>'></td>
                            </tr>
							  <tr>
                                <td><b>Kazi</b></td>
                                <td><input type='text' name='Occupation' id='Occupation' value='<?php echo $Occupation;?>'></td>
                            </tr>
                            <tr> 
                                <td><b>Nambari ya simu</b></td>
                                <td><input type='text' name='Phone_Number' id='Phone_Number' value='<?php echo $Phone_Number;?>'></td> 
                            </tr>  <tr> 
                                <td><b>Sababu ya Msamaha</b></td>
                                <td><textarea type='text' name='Reason_Exception' id='Reason_Exception' value='<?php echo $Reason_Exception;?>'/></textarea></td> 
                            </tr>
								 <tr>
                                <td><b>Jina la mtu anayependekeza Msamaha </b></td>
                               <td><input type='text' name='Name_Approval' id='Name_Approval' required='required' value='<?php echo $Name_Approval;?>'></td>
                            </tr>
								 <tr>
                                <td><b>Tarehe ya kulazwa </b></td>
                                <td><input type='date' name='Dat_Accident' id='date2' required='required'  value='<?php echo $Dat_Accident;?>'></td>
                            </tr>
							
                        </table>
                    </td>
                    <td width=35%><table width=100%>
					<tr>
                                <td><b>Jina la Balozi</b></td>
                               <td><input type='text' name='Name_Balozi' id='Name_Balozi' required='required' value='<?php echo $Name_Balozi;?>'></td>
                            </tr>
                            <tr>
                                <td><b>Region</b></td>
                                <td>
                                    <select name='Region' id='region' onchange='getDistricts(this.value)' value='<?php echo $Region;?>'>
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
                            </tr> 
                            <tr>
                                <td><b>District</b></td>
                                <td>
                                    <select name='District' id='District' value='<?php echo $District;?>'>
                                        <option selected='selected'>Kinondoni</option>
                                        <option>Ilala</option>
                                        <option>Temeke</option>  
                                    </select>
                                </td> 
                            </tr>
                            <tr>
                                <td><b>Ward</b></td>
                                <td>
				             <input type='text' name='Ward' id='Ward' value='<?php echo $Ward;?>'>
                                </td> 
                            </tr>
            
                            <tr>
                                <td><b>kiwango cha elimu</b></td>
                                <td><input type='text' name='Education_Level' id='Education_Level' required='required' value='<?php echo $Education_Level;?>'></td>
                            </tr>
							 <tr>
                                <td><b>Kazi ya mke/mlezi</b></td>
                                <td><input type='text' name='Work_Wife' id='Work_Wife' required='required' value='<?php echo $Work_Wife;?>'></td>
                            </tr>
							 <tr>
                                <td><b>Idadi ya mahudhurio(wagojwa wa nje kwa miezi 6 iliyopita)</b></td>
                                <td><input type='text' name='Mahudhulio' id='Mahudhulio' required='required' value='<?php echo $Mahudhulio;?>'></td>
                            </tr>
							 <tr>
                                <td><b>Idadi ya kulazwa hospitali kwa miezi 6 iliyopita</b></td>
                                <td><input type='text' name='Idadi_Mahudhulio' id='Idadi_Mahudhulio' required='required' value='<?php echo $Idadi_Mahudhulio;?>'></td>
                            </tr>
                            <tr>
                                <td><b>Namba ya simu ya mtu wa karibu</b></td>
                                <td><input type='text' name='Emergence_Contact' id='Emergence_Contact' required='required' value='<?php echo $Emergence_Contact;?>'></td>
                            </tr>
                            <?php
                                if(isset($_SESSION['userinfo']['Employee_Name'])){
                                    $Employee_Name = $_SESSION['userinfo']['Employee_Name'];    
                                }else{
                                    $Employee_Name = "Unknown Employee";
                                }
                            ?>
							  <tr>
                                <td><b>Prepared By</b></td>
                                <td><input type='text' name='Prepared_By' readonly="readonly" id='Prepared_By' value='<?php echo $Employee_Name; ?>'></td>
                      
								<tr>
							         <td><b>Preview attach Evidence</b></td>
								     <td colspan=3>
									  <input type='button' name='preveview' value='Preveview' class='art-button-green'>
									 </td>
									 </tr>
								
                        </table>
						<br><br>
					            
					
							
						<tr>
                                <td colspan=5 style='text-align:center;'>
                                    <input type='submit' name='submit' id='submit' value='UPDATE' class='art-button-green'>
                                    <input type='reset' name='clear' id='clear' value=' CLEAR ' class='art-button-green' onclick='clearPatientPicture()'>
                                    <input type='hidden' name='submittedEditItemForm' value='true'/> 
                                </td>
                            </tr>
                   
                </form>
              
            </table>
        </center>
</fieldset><br/>
<?php
    include("./includes/footer.php");
?>