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
    <a href='foodpage.php?Food=FoodThisPage' class='art-button-green'>
         BACK
    </a>
<?php  } } ?>

<?php

	//to select patient info
	 	if(isset($_GET['Registration_ID'])){  
		$Registration_ID = $_GET['Registration_ID']; 
		$select_Patient = mysqli_query($conn,"SELECT Patient_Name, pr.Registration_ID, Guarantor_Name, 
				Gender, Date_Of_Birth
				FROM 
						tbl_Patient_Registration pr, tbl_sponsor sp
					
				WHERE 
						pr.sponsor_id = sp.sponsor_id and  
						pr.Registration_ID='$Registration_ID'
						group by pr.Registration_ID ") or die(mysqli_error($conn));
	
	$no = mysqli_num_rows($select_Patient);
										}
	 if($no>0){
            while($row = mysqli_fetch_array($select_Patient)){
                $Registration_ID = $row['Registration_ID'];
                $Patient_Name = $row['Patient_Name'];
				$Date_Of_Birth = $row['Date_Of_Birth'];
                $Gender = $row['Gender'];
                $Guarantor_Name = $row['Guarantor_Name']; 
               
           
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
			
		//ANOTHER QUERY FOR RESTRICTION
	if(isset($_GET['Registration_ID'])){  
		$Registration_ID = $_GET['Registration_ID']; 	
		$select_restriction = mysqli_query($conn,"SELECT  pr.Registration_ID,Restriction,Description,
			fr.Registration_ID,Restriction_Name,Food_Restriction_Setup_ID
		 FROM 
				tbl_Patient_Registration pr,tbl_food_restriction fr,tbl_food_restriction_setup frs 
		 WHERE 
		 pr.Registration_ID=fr.Registration_ID and 
		 Restriction=Food_Restriction_Setup_ID AND 
		 fr.Registration_ID='$Registration_ID' ORDER BY  Food_restriction_ID DESC
		");
		 $no = mysqli_num_rows($select_restriction);
		 if($no>0){
		$row = mysqli_fetch_array($select_restriction);
                $Description = $row['Description'];
                $Restriction = $row['Restriction_Name'];
                $Registration_ID = $row['Registration_ID'];
		}else{
            $Description = '';
            $Restriction = '';
			}
	}
	
	//to insert food of patients in table below of this form
	if(isset($_POST['submittedfoodForm'])){       
      
		$Registration_ID = mysqli_real_escape_string($conn,$_POST['Registration_ID']);
		$Food_Standard = mysqli_real_escape_string($conn,$_POST['Food_Standard']);
		$Food_Time = mysqli_real_escape_string($conn,$_POST['Food_Time']);
		$Days_of_Week = mysqli_real_escape_string($conn,$_POST['Days_of_Week']);
		$Food_Menu_ID = mysqli_real_escape_string($conn,$_POST['Food_Menu_ID']);
		$Comments = mysqli_real_escape_string($conn,$_POST['Comments']);
		$Blook_Name = mysqli_real_escape_string($conn,$_POST['Blook_Name']);
		$Ward_No = mysqli_real_escape_string($conn,$_POST['Ward_No']);
		$Bed_No = mysqli_real_escape_string($conn,$_POST['Bed_No']);
		$price = mysqli_real_escape_string($conn,$_POST['price']);
	
		 if(isset($_SESSION['userinfo'])){
            if(isset($_SESSION['userinfo']['Employee_ID'])){
                $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
            }else{
                $Employee_ID = 0;
            }
        }

//insert query into tbl_food_transaction
		$insert_Sql = "INSERT INTO tbl_food_transaction (Registration_ID,Trans_Date_Time,Employee_ID,Food_Standard,
					Food_Time,Days_of_Week,Food_Menu_ID,Comments,Blook_Name,Ward_No,Bed_No,price)
			VALUES
				('$Registration_ID',(select now()),'$Employee_ID','$Food_Standard','$Food_Time','$Days_of_Week','$Food_Menu_ID',
				'$Comments','$Blook_Name','$Ward_No','$Bed_No','$price')";
		
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
								document.location = './foodpage.php?Registration_ID=".$Registration_ID."';
						</script>";

}	
?>

<script type='text/javascript'>
    function access_Denied(){ 
   alert("Access Denied");
   document.location = "./index.php";
    }
</script>

<script type="text/javascript" language="javascript">
    function getFood(Food_Time) {
	    if(window.XMLHttpRequest) {
		mm = new XMLHttpRequest();
	    }
	    else if(window.ActiveXObject){ 
		mm = new ActiveXObject('Micrsoft.XMLHTTP');
		mm.overrideMimeType('text/xml');
	    }
	    mm.onreadystatechange= AJAXP; //specify name of function that will handle server response....
	    mm.open('GET','getFood.php?Food_Time='+Food_Time,true);
	    mm.send();
	}
    function AJAXP() {
	var data = mm.responseText;
	document.getElementById('Selected_Food').innerHTML = data;	
    }
    
   
</script>

<script type='text/javascript'>
	    function getPrice() {
		if(window.XMLHttpRequest) {
			mm2 = new XMLHttpRequest();
	    }
	    else if(window.ActiveXObject){ 
			mm2 = new ActiveXObject('Micrsoft.XMLHTTP');
			mm2.overrideMimeType('text/xml');
	    }
		var Food_Name = document.getElementById('Food_Menu_ID').value;
		var Food_Standard = document.getElementById('Food_Standard').value;
		var Food_Standard_Bill = '';
		if (Food_Name!=''){
		    if (Food_Standard=='Standard') {
				Food_Standard_Bill = 'standard_price';
		    }else if(Food_Standard=='Quality'){
				Food_Standard_Bill = 'quality_price';
		    }   
			mm2.onreadystatechange= AJAXP4; //specify name of function that will handle server response....
			mm2.open('GET','Getfoodprice.php?Food_Name='+Food_Name+'&Food_Standard_Bill='+Food_Standard_Bill,true);
			mm2.send();
		}
		}
	    function AJAXP4(){
		var data4 = mm2.responseText;
		document.getElementById("price").value = data4;
		}
	</script>

<br/><br/><br/>
<center>
<fieldset style="width:90%;">
	<legend align="center"><b>PATIENT ORDER</b></legend>
	
<form action="#" method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">
	<table  class='hiv_table'>
		<tr><td><br></td></tr>
		<tr>
			
				<td width='10%' style="resize:none;text-align:right;">Patient Name</td>
				<td width='20%' colspan="2"><input type="text" name='Patient_Name'  disabled='disabled' id='Patient_Name' value="<?php echo$Patient_Name;?>" /> </td>
			
				<td width='8%' style="resize:none;text-align:right;">Patient No</td>
				<td width='8%'><input type="text" name="Registration_ID" id="Registration_ID"  readonly='readonly' value="<?php echo$Registration_ID;?>" /> </td>
				
				<td width='6%' style="resize:none;text-align:right;">Sponsor</td>
				<td width='10%' colspan="2"><input type="text"  name="Guarantor_Name" disabled='disabled' id="Guarantor_Name" value="<?php echo$Guarantor_Name;?>" ></td>
			
				<td width='6%' style="resize:none;text-align:right;">Gender</td>
				<td width='6%'><input type="text"  name="Gender" id="Gender"  value="<?php echo$Gender;?>" disabled='disabled' ></td>
				
				<td width='6%' style="resize:none;text-align:right;">Age</td>
				<td width='6%'><input type="text" disabled='disabled' name="Age" id="Age" value="<?php echo$Age;?>" ></td>
		</tr>
		<tr>
		<td  style="resize:none;text-align:right;">Description</td>
		<td colspan=4>
		    <textarea  rows=1 style="resize:none;"  name="Description" id="Description" readonly='readonly'> <?php echo$Description;?></textarea>
		</td>
			<td  style="resize:none;text-align:right;">Restriction</td>
			<td colspan=6>
			    <textarea rows="1"  style="resize:none;" name="Restriction" id="Restriction" readonly='readonly'><?php echo$Restriction;?> </textarea>
			</td>
		</tr>
	</table>
	<hr>
	<table width="90%">
		<tr>
			<td style="text-align:right;">Day Of the Week</td>
			<td>
				<input type="text" name="Days_of_Week" value="<?php echo date("l");?>" readonly="readonly">
			</td>
			<td  style="text-align:right;">Food Time</td>
			<td>
				<select name="Food_Time" id="Food_Time" required='required' onchange='getFood(this.value)'>
					<option selected="selected"></option>
					<option>BREAKFAST</option>
					<option>LUNCH</option>
					<option>DINNER</option>
				</select>
			</td>
			
			<td style="text-align:right;">Menu Name</td>
			<td id='Selected_Food'>
				<select name="Food_Menu_ID" id="Food_Menu_ID" required='required' >
							<option selected="selected"></option>
							
							<?php
							$data = mysqli_query($conn,"select * from tbl_food_menu");
								while($row = mysqli_fetch_array($data)){
								 
							?>
							  <option value='<?php echo $row['Food_Menu_ID']; ?>'><?php echo ucwords(strtolower($row['Food_Name'])); ?></option>
						  <?php
							}
						?>		
				</select>
			</td>
		</tr>
		<tr>
			<td style="text-align:right;">Blook Name</td>
			<td>
				<select name="Blook_Name" id="Blook_Name" required='required' >
						<option selected="selected"></option>
						
						<?php
					    $data = mysqli_query($conn,"select * from tbl_hospital_ward`");
					        while($row = mysqli_fetch_array($data)){
						?>
					  <option value='<?php echo $row['Hospital_Ward_ID']; ?>'><?php echo $row['Hospital_Ward_Name']; ?></option>
					  <?php
					    }
					?>		
				</select>
			</td>
			
			<td  style="text-align:right;">Ward Number/Name</td>
			<td>
				<input type="text" name="Ward_No" id="Ward_No">
			</td>
			
			<td style="text-align:right;">Bed Number</td>
			<td id='Selected_Food'>
				<input type="text" name="Bed_No" id="Bed_No" >
			
			</td>
		</tr>
		<tr>	
			<td  style="text-align:right;">Diet Type</td>
			<td>
				<select name="Food_Standard" id="Food_Standard" required='required' onchange='getPrice()'>
						<option selected="selected"></option>
					    <option>Standard</option>
						<option>Quality</option>
				</select>
				
			</td>
			
			<td  style="text-align:right;">Price</td><td width="9%"><input type="text" name="price" id="price" readonly="readonly" required='required' ></td>
		<td  style="text-align:right;">Comments</td>
			<td>
				<textarea rows="1" name="Comments" id="Comments"></textarea>
			</td>
		</tr>
	
		<tr>
			<td></td>
			<td></td>
			<td></td>
			<td colspan=2 style='text-align:right;'>
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