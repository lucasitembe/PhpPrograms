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
    <a href='editsearchregitration.php?EditItem=EditItemThisPage' class='art-button-green'>
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
    
//    function to verify NHIF STATUS
   
</script>

<br/><br/>
<center>

<?php
    //get all item details based on item id
    if(isset($_GET['Dead_ID'])){
	$Dead_ID = $_GET['Dead_ID'];
    }else{
	$Dead_ID = '';
    }
    
    $Results = mysqli_query($conn,"select * from tbl_dead_regisration where Dead_ID='$Dead_ID' ");
    $no = mysqli_num_rows($Results);
    if($no > 0){
	while($row = mysqli_fetch_array($Results)){
	    $Dead_ID = $row['Dead_ID'];
	    $Name_Of_Body = $row['Name_Of_Body'];
	    $Time_For_Dead = $row['Time_For_Dead'];
	    $Gender = $row['Gender'];
	    $Age = $row['Age'];
	    $Reason = $row['Reason'];
	    $Description_Death = $row['Description_Death'];
	    $Morgue_Name = $row['Morgue_Name'];
	    $Deadline_To_Pick_Up = $row['Deadline_To_Pick_Up'];
	    $Member_Name = $row['Member_Name'];
	    $Relationship_Dead = $row['Relationship_Dead'];
	    $Item_Region = $row['Region'];
	    $District = $row['District'];
		$Ward_Stress=$row['Ward_Stress'];
		$Phone_Number=$row['Phone_Number'];
	  
	}
    }
?>



<?php
    if(isset($_POST['submittedEditItemForm'])){
	$Name_Of_Body = mysqli_real_escape_string($conn,$_POST['Name_Of_Body']); 
	$Time_For_Dead = mysqli_real_escape_string($conn,$_POST['Time_For_Dead']); 
	$Gender = mysqli_real_escape_string($conn,$_POST['Gender']);
	$Age = mysqli_real_escape_string($conn,$_POST['Age']);
	$Reason = mysqli_real_escape_string($conn,$_POST['Reason']); 
	$Description_Death = mysqli_real_escape_string($conn,$_POST['Description_Death']); 
	$Morgue_Name = mysqli_real_escape_string($conn,$_POST['Morgue_Name']); 
	$Member_Name = mysqli_real_escape_string($conn,$_POST['Member_Name']); 
	$Relationship_Dead = mysqli_real_escape_string($conn,$_POST['Relationship_Dead']); 
	$Item_Region = mysqli_real_escape_string($conn,$_POST['Region']); 
	$District = mysqli_real_escape_string($conn,$_POST['District']);
	$Ward_Stress = mysqli_real_escape_string($conn,$_POST['Ward_Stress']);
	$Phone_Number = mysqli_real_escape_string($conn,$_POST['Phone_Number']);
	/* if(isset($_POST['Can_Be_Substituted_In_Doctors_Page'])) {
	    $Can_Be_Substituted_In_Doctors_Page = 'yes';
	}else{
	    $Can_Be_Substituted_In_Doctors_Page = 'no';
	} 
	 */
	$select_dead="SELECT * FROM tbl_dead_regisration WHERE Dead_ID = '$Dead_ID' ";
	
	$check_result = mysqli_query($conn,$select_dead);
	if(mysqli_num_rows($check_result)>0){ 
	
	
	$Update_New_Item = "UPDATE tbl_dead_regisration
			    SET 
			     Name_Of_Body= '$Name_Of_Body', Time_For_Dead = '$Time_For_Dead',Age='$Age',
				Gender = '$Gender', Reason = '$Reason', Description_Death = '$Description_Death',Ward_Stress='$Ward_Stress',
				    Morgue_Name = '$Morgue_Name', Member_Name='$Member_Name',Phone_Number='$Phone_Number',
					Region = '$Item_Region',
					Relationship_Dead = '$Relationship_Dead', District = '$District',Ward_Stress='$Ward_Stress'
				
						where Dead_ID = '$Dead_ID'";
			    
	if(!mysqli_query($conn,$Update_New_Item)){
							die(mysqli_error($conn));
						die(mysqli_error($conn));
								$error = '1062yes';
								if(mysql_errno()."yes" == $error){ 
										$controlforminput = 'not valid';
								}
						}
					echo "<script type='text/javascript'>
								alert('UPDATED SUCCESSFUL');
								document.location = './editsearchregitration.php?EditForm=DeadRegistrationFormThisPage';
						</script>";
	}	
    }
?>




 <form action='#' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">
    
<table width=100%>
    <tr>
        <td>
            <fieldset>  
            <legend align="center"><b>EDIT DETAYS OF DEAD BODY & MEMBER FAMILY</b></legend>
         
        <center>
            <table width=100%>
               
                <tr>
                    <td width=100%>
                        <table width=100%>
							<tr>
                                <td style="text-align:right;"><b>Name of dead body</b></td>
                                <td><input type='text' name='Name_Of_Body' id='Name_Of_Body' required='required' value='<?php echo $Name_Of_Body;?>'></td>
                           
                             
                                <td  width='15%' style="text-align:right;"><b>Date of death</b></td>
                                <td><input type='text' name='Time_For_Dead' id='date2' required='required' value='<?php echo $Time_For_Dead;?>'</td>
                            </tr>
                            <tr>
                                <td style="text-align:right;"><b>Gender</b></td>
                                <td>
                                    <select name='Gender' id='Gender' value='<?php echo $Gender;?>'>
                                        <option selected='selected'>Male</option>
                                        <option>Female</option>
                                    </select>
                                </td>
						 <td width='6%' style="text-align:right;"><b>Age</b></td>
				         <td width=''><input type="text" name='Age' id='Age' value='<?php echo $Age;?>'></td>
						 </tr>
				         </tr>
                            <tr>
                                <td style="text-align:right;"><b>Reason for death</b></td>
                                <td>
                                 <select name='Reason' id='region' >
                                 <option  value=''>Reason for death</option>
					             <?php
					            $data = mysqli_query($conn,"select * from tbl_Reason_Dead_Body,tbl_dead_regisration WHERE Reason=Registration_ID AND 
								Dead_ID = '$Dead_ID'");
					           while($row = mysqli_fetch_array($data)){
					           ?><option value='<?php echo $row['Registration_ID'];?>' >
						         <?php echo $row['Reason_DeadBody']; ?> 
					    </option>
				     	<?php
					    }
					?>
                                    </select>
                                </td> 
								
							</input></td>
							<td style="text-align:right;"><b>Description</b></td>
                                <td>
								<textarea name="Description_Death" rows="2" cols="10" >
									<?php echo $Description_Death;?>
								</textarea>
							</td>
                            </tr>
                            <tr>
                                <td style="text-align:right;"><b>Morgue_Name</b></td>
								<td>
                                 <select name='Morgue Name' >
                                 <option '></option>
					             <?php
					            $data = mysqli_query($conn,"select * from tbl_Morgue_Name");
					           while($row = mysqli_fetch_array($data)){
					           ?>
					       <option value='<?php echo $row['Morgue_Name'];?>' <?php if($row['Morgue_Name']==$Morgue_Name ){echo"selected='selected'";}?>' >
						  <?php echo $row['Morgue_Name']; ?>
					    </option>
				     	<?php
					         }
					       ?>
                                    </select>
                                </td> 
                                <td style="text-align:right;"><b>Deadline to pick up </b></td>
                                <td><input type='text' name='Deadline_To_Pick_Up' id='date_From1'value='<?php echo $Deadline_To_Pick_Up;?>'></td>
                            </tr>
							 
                        </table>
                    </td>
                    
                    
             
                </tr>
            </table>
        </center>
</fieldset><br/>
<fieldset>
<legend align="center"><b> PARTICULAR DETAILS OF MEMBER FAMILY</b></legend>
    <table  width=100%>
	
	<tr>
	    
	    <td width='13%'style="text-align:right;"><b>Name</b></td>
	    <td width='16%'><input type='text' id='Kin_Name' name='Member_Name' value='<?php echo $Member_Name;?>'></td>
	    
	    <td width='13%'style="text-align:right;"><b>Relationship</b></td>
	    <td width='16%'><input type='text' id='Kin_Area' name='Relationship_Dead' value='<?php echo $Relationship_Dead ;?>'></td>

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
							<td style="text-align:right;"><b>Ward/Area</b></td>
							<td><input type='text' id='Kin_Address' name='Ward_Stress' value='<?php echo $Ward_Stress;?>'></td>
							<td style="text-align:right;"><b>Phone </b></td>
							<td><input type='text' id='Office_Phone' name='Phone_Number' value='<?php echo $Phone_Number;?>'></td>
	     </tr>
                <tr>
                    <td colspan=4 style='text-align:center;'>
			<?php if($no > 0) { ?>
                        <input type='submit' name='submit' id='submit' value='   UPDATE  ' class='art-button-green'>
			<?php } ?>
                        <a href='editsearchregitration.php?EditItem=EditItemThisPage' class='art-button-green'>CANCEL</a> 
                        <input type='hidden' name='submittedEditItemForm' value='true'/> 
                    </td>
                </tr>
            </table>
</fieldset>
        </td>
    </tr>
</table>
 </form>
        </center>
<br/>
<?php
    include("./includes/footer.php");
?>