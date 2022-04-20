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
        if($_SESSION['userinfo']['Dialysis_Works'] == 'yes'){ 
?>
    <a href='laundryin.php' class='art-button-green'>
         LAUNDRY-IN
    </a>
<?php  } } ?>
<?php
 if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Dialysis_Works'] == 'yes'){ 
?>
    <a href='laundryoutpage.php' class='art-button-green'>
         LAUNDRY-OUT
    </a>
<?php  } } ?>


<?php
 if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Dialysis_Works'] == 'yes'){ 
?>
    <a href='laundry_in_out.php' class='art-button-green'>
         BACK
    </a>
<?php  } } ?>
<html>
<body>

<style>
.kulia
{
text-align:right;
}


</style>
</body>
</html>

<br>
<br>
<?php



if(isset($_POST['submittedInOutItemForm'])){
		$Product_Name = mysqli_real_escape_string($conn,$_POST['Product_Name']);
		$comments = mysqli_real_escape_string($conn,$_POST['comments']);
		$Quantity = mysqli_real_escape_string($conn,$_POST['Quantity']);
		$From_To = mysqli_real_escape_string($conn,$_POST['From_To']);
		$Status = 'DISPOSED';
		
		//get employee id
        if(isset($_SESSION['userinfo'])){
            if(isset($_SESSION['userinfo']['Employee_ID'])){
                $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
            }else{
                $Employee_ID = 0;
            }
        }
	
	$Insert_Item = "INSERT INTO tbl_laundry_in_or_out(Product_Name,Quantity,Status,Laundry_Date_And_Time,Employee_ID,
			comments,From_To )
			VALUES
			('$Product_Name','$Quantity','$Status',(select now()),'$Employee_ID','$comments','$From_To')";
			
			if(!mysqli_query($conn,$Insert_Item)){
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

<script>
function Numbercompare() {
	var a = parseInt(document.getElementById('Quantity').value);
	var b = parseInt(document.getElementById('Quantity1').value);
	
	if (isNaN(b))
	{
	alert("put a number");
	document.getElementById('Quantity1').value= '';	
	}
   else if ( b>a ) {
        alert ("exceed quantity Remained");
		
	document.getElementById('Quantity1').value= '';	
    }
 }  
</script>

<style type="text/stylesheet">
	

</style>
<form action="#" method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">
<br>
<br>
<br>
<center>
<fieldset style="width:60%;">
			<legend align='center'><b>LAUNDRY DISPOSED</b></legend>
<table width="60%">
	<tr>
		<td class="kulia" style="text-align:right;">Item</td><td>
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
			<td style="text-align:right;">Location From</td><td>
			
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
		<td style="text-align:right;">Quantity</td><td><input type="text" name="Quantity" autocomplete='off' id="Quantity1" required='required' oninput="Numbercompare()"></td>
		
	</tr>
	<tr>
		<td style="text-align:right;">Comments<td>
	
			<textarea name="comments" id="comments"></textarea>
		
	</tr>
	
	<tr>
		<td colspan=2 style='text-align: right;'>
			<input type='submit' name='submit' id='submit' value='SAVE' class='art-button-green'>
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