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
    <a href='laundryoutpage.php' class='art-button-green'>
         LAUNDRY-OUT
    </a>
<?php  } } ?>
<?php
 if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Reception_Works'] == 'yes'){ 
?>
    <a href='laundrydisposed.php' class='art-button-green'>
         LAUNDRY-DISPOSED
    </a>
<?php  } } ?>


<?php
 if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Reception_Works'] == 'yes'){ 
?>
    <a href='laundry_in_out.php' class='art-button-green'>
         BACK
    </a>
<?php  } } ?>

<br>


<?php

if(isset($_POST['submittedInOutItemForm'])){       
      
		$Product_Name = mysqli_real_escape_string($conn,$_POST['Product_Name']);
		$From_To = mysqli_real_escape_string($conn,$_POST['From_To']);
		$Quantity = mysqli_real_escape_string($conn,$_POST['Quantity']);
		$Laundry_Name = mysqli_real_escape_string($conn,$_POST['Laundry_Name']);
		$Personnel = mysqli_real_escape_string($conn,$_POST['Personnel']);
		$Status = 'IN';
		  if(isset($_SESSION['userinfo'])){
            if(isset($_SESSION['userinfo']['Employee_ID'])){
                $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
            }else{
                $Employee_ID = 0;
            }
        }


//insert query into tbl_laundry_in_or_out

		$insert_Sql = "insert into tbl_laundry_in_or_out (Product_Name,From_To,Quantity,Status,Laundry_Date_And_Time,
		Employee_ID,Laundry_Name,Personnel)
		VALUES
		('$Product_Name','$From_To','$Quantity','$Status',(select now()),'$Employee_ID','$Laundry_Name',
			'$Personnel')";
		
					if(!mysqli_query($conn,$insert_Sql)){
							die(mysqli_error($conn));
						die(mysqli_error($conn));
								$error = '1062yes';
								if(mysql_errno()."yes" == $error){ 
										$controlforminput = 'not valid';
								}
						}
						
						else {
									$query=mysqli_query($conn,"select laundry_in_or_out_ID from tbl_laundry_in_or_out 
									where employee_ID='$Employee_ID' order by Laundry_Date_And_Time DESC limit 1");
									$row=mysqli_fetch_array($query);
									
								//	foreach($_POST['Vital_ID'] as $vital_id){
								//	$laundry_in_or_out_ID=$_POST['laundry_in_or_out_ID'];
									$Quantity = mysqli_real_escape_string($conn,$_POST['Quantity']);
									$laundry_in_or_out_ID=$row['laundry_in_or_out_ID'];
									$NurseVitals=mysqli_query($conn,"insert into tbl_laundry_out_cache(laundry_in_or_out_ID,Quantity,Laundry_Cache_Date) 
									values
										($laundry_in_or_out_ID,'$Quantity',(select now()))");
								
									//  }
								}
								//".mysql_insert_id()."
				
					echo "<script type='text/javascript'>
								alert(' ADDED SUCCESSFUL \\n  ".strtoupper($Product_Name)." \\n Transaction No ".$laundry_in_or_out_ID."');
								document.location = './laundryin.php';
								
						</script>";

}
?>
<script>
function Numbercompare() {
//alert("put a number");
//	var a = parseInt(document.getElementById('Quantity').value);
	var b = document.getElementById('Quantity').innerHTML;
	//alert("Quantity");
	if (isNaN(b))
	{
	alert("put a number");
	document.getElementById('Quantity').value= '';	
	} 
 }  
</script>
<br>
<br>
<center>
<form action="#" method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">
<fieldset style="width:40%;">
		<legend align='center'><b>LAUNDRY IN</b></legend>
	<table>
		<tr>
			<td>Item</td>
			<td>
				<select required='required' name="Product_Name" id="Product_Name">
					<option selected='selected' ></option>
					<?php  
					$select_item=mysqli_query($conn,"SELECT * FROM tbl_laundry");
					while($rows=mysqli_fetch_array($select_item)){
					  $Product_Name = $rows['Product_Name'];
						?>
					<option value="<?php  echo$rows['Product_Name'] ;?>" ><?php echo$rows['Product_Name']; ?></option>
					
					<?php
					}?>
				</select>
			</td>
		</tr>
		<tr>
			<td>Location From</td><td>
			
			<select required='required' name="From_To" id="From_To">
				<option selected='selected' ></option>
				<?php  
				$select_item=mysqli_query($conn,"SELECT * FROM tbl_new_location_laundry");
				while($rows=mysqli_fetch_array($select_item)){
				  $Location = $rows['Location'];
			?>
				<option value="<?php  echo$rows['Location'] ;?>" ><?php echo$rows['Location']; ?></option>
				
				<?php
				}?>
			</select>
			</td>
		</tr>
		<tr>	
			<td>Personnel</td><td><input type="text" name="Personnel" id="Personnel"></td>
		</tr>
		<tr>	
			<td>Laundry Name</td>
			<td>
			<select required='required' name="Laundry_Name" id="Laundry_Name">
				<option selected='selected' ></option>
				<?php  
				$select_new=mysqli_query($conn,"SELECT * FROM tbl_laundry_new_name");
				while($rows=mysqli_fetch_array($select_new)){
				  $Laundry_Name = $rows['Laundry_Name'];
			?>
				<option value="<?php  echo$rows['Laundry_Name'] ;?>" ><?php echo$rows['Laundry_Name']; ?></option>
				
				<?php
				}
				?>
				
			</select>
			</td>
		</tr>
		<tr>	
			<td>Quantity</td><td><input type="text" name="Quantity" autocomplete='off' id="Quantity" required='required' oninput="Numbercompare()"></td>
		</tr>
		
			<?php
					if(isset($_SESSION['userinfo']['Employee_Name'])){
						$Employee_Name = $_SESSION['userinfo']['Employee_Name'];    
					}else{
						$Employee_Name = "Unknown Employee";
					}
				?>
		<tr>	
			<td>Received By</td><td><input type="text" name="Employee_Name" id="Employee_Name" value="<?php echo $Employee_Name; ?>" readonly='readonly'></td>
		</tr>
		<tr>
			<td colspan=2 style='text-align: right;'>
				<input type='submit' name='submit' id='submit' value='SAVE' class='art-button-green'>
				<input type='reset' name='clear' id='clear'  value='CLEAR' class='art-button-green'>
				<input type='hidden' name='submittedInOutItemForm' value='true'/> 
			</td>
		 </tr>
	</table>
</fieldset>
</form>
</center>




<?php
    include("./includes/footer.php");
?>