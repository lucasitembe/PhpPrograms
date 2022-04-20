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
    <a href='laundryin.php' class='art-button-green'>
         LAUNDRY-IN
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
    <a href='laundryoutpage.php' class='art-button-green'>
         FINE SEARCH
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
<br>
<br>
<?php
	
	//to select the out items from those who inserted it.
	 if(isset($_GET['laundry_in_or_out_ID'])){ 
       	$laundry_in_or_out_ID = $_GET['laundry_in_or_out_ID']; 
	$select_laundry_out=mysqli_query($conn,"SELECT * FROM tbl_laundry_in_or_out
	WHERE laundry_in_or_out_ID = '$laundry_in_or_out_ID' AND Status='IN' ");
	
		while($row=mysqli_fetch_array($select_laundry_out)){
		   $Quantity = $row['Quantity'];
		   $Product_Name = $row['Product_Name'];
		   $From_To = $row['From_To'];
		}
} 

//to select the out items from those who inserted it.
	 if(isset($_GET['laundry_in_or_out_ID'])){ 
       	$laundry_in_or_out_ID = $_GET['laundry_in_or_out_ID']; 
	$select_laundry_cache=mysqli_query($conn,"SELECT * FROM tbl_laundry_in_or_out li,tbl_laundry_out_cache lc
				WHERE  li.laundry_in_or_out_ID=lc.laundry_in_or_out_ID AND
				lc.laundry_in_or_out_ID = '$laundry_in_or_out_ID'  ");
			
		while($row=mysqli_fetch_array($select_laundry_cache)){
		   $laundry_in_or_out_ID = $row['laundry_in_or_out_ID'];
		   $Remain = $row['Quantity'];
		
		}
	
} 
 
		//submit out laundry form
		if(isset($_POST['submittedInOutItemForm'])){       
			  
				$Product_Name = mysqli_real_escape_string($conn,$_POST['Product_Name']);
				
				$From_To = mysqli_real_escape_string($conn,$_POST['From_To']);
				$Quantity = mysqli_real_escape_string($conn,$_POST['Quantity']);
				$Status = 'OUT';
				
				  if(isset($_SESSION['userinfo'])){
					if(isset($_SESSION['userinfo']['Employee_ID'])){
						$Employee_ID = $_SESSION['userinfo']['Employee_ID'];
					}else{
						$Employee_ID = 0;
					}
				}


//inseert query into tbl_dialysis
		$insert_Sql = "INSERT INTO tbl_laundry_in_or_out (Product_Name,From_To,Quantity,Status,Laundry_Date_And_Time,
				Employee_ID )
		VALUES
				('$Product_Name','$From_To','$Quantity','$Status',(select now()),'$Employee_ID')";
		
					if(!mysqli_query($conn,$insert_Sql)){
							die(mysqli_error($conn));
						die(mysqli_error($conn));
								$error = '1062yes';
								if(mysql_errno()."yes" == $error){ 
										$controlforminput = 'not valid';
								}
						}
				
		if(isset($_GET['laundry_in_or_out_ID'])){ 
       	$laundry_in_or_out_ID = $_GET['laundry_in_or_out_ID']; 
	$select_update=mysqli_query($conn,"SELECT laundry_in_or_out_ID FROM tbl_laundry_in_or_out 
	WHERE 
	laundry_in_or_out_ID = '$laundry_in_or_out_ID'");
	 
	 $no=mysqli_num_rows($select_update);
	 
	 if($no > 0){
		$diff = mysqli_real_escape_string($conn,$_POST['diff']);
		
				$Select_Update = mysqli_query($conn,"UPDATE tbl_laundry_out_cache
					SET Quantity='$diff'
                    where  laundry_in_or_out_ID = '$laundry_in_or_out_ID'");
	}	
} 			
					echo "<script type='text/javascript'>
								alert('ADDED SUCCESSFUL');
								document.location = './laundryoutpage.php';
						</script>";
						}

?>
<script type='text/javascript'>
    function access_Denied(){ 
   alert("Access Denied");
   document.location = "./index.php";
    }
</script>
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

<script type='text/javascript'>
	    function getUpdate() {
		
		if(window.XMLHttpRequest) {
			mm2 = new XMLHttpRequest();
	    }
	    else if(window.ActiveXObject){ 
			mm2 = new ActiveXObject('Micrsoft.XMLHTTP');
			mm2.overrideMimeType('text/xml');
	    }
		
		var Quantity1 = document.getElementById('Quantity1').value;
		var Quantity = document.getElementById('Quantity').value;
		var laundry_in_or_out_ID = '<?php echo $laundry_in_or_out_ID;?>';
		
		
		//alert(Food_Standard_Bill);
		//alert('Getfoodprice.php?Food_Name='+Food_Name+'&Food_Standard_Bill='+Food_Standard_Bill);
		
		     
			//document.location='Getfoodprice.php?Food_Name='+Food_Name+'&Food_Standard_Bill='+Food_Standard_Bill;
			mm2.onreadystatechange= AJAXP4; //specify name of function that will handle server response....
			mm2.open('GET','GetoutUpdate.php?laundry_in_or_out_ID='+laundry_in_or_out_ID+'&Quantity1='+Quantity1+'&Quantity='+Quantity,true);
			mm2.send();
				 
		}
	
	    function AJAXP4(){
		var data4 = mm2.responseText;
		document.getElementById("diff").value = data4;
		}
	</script>

<form action="#" method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">
<center>

<fieldset style="width:40%;">
		<legend align='center'><b>LAUNDRY OUT</b></legend>
<table>
	<tr>
		<td>Item</td>
		<td>
		<input type="text" name="Product_Name" id="Product_Name" value="<?php  echo$Product_Name;?>" readonly='readonly'>
			
		</td>
	</tr>
	<tr>
		<td>Location To</td><td>
		<input type="text" name="From_To" id="From_To" value="<?php  echo$From_To;?>" readonly='readonly'>
		
		</td>
	</tr>
	<tr>	
		<td>Quantity</td><td><input type="text" name="Quantity" autocomplete='off' id="Quantity1" required='required'  oninput="Numbercompare();getUpdate()" ></td>
		
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
<br>
<fieldset style="width:40%;">
<table>
	<tr>
		<td>Transaction</td><td><input type="text" name="laundry_in_or_out_ID" id="laundry_in_or_out_ID" value="<?php echo$laundry_in_or_out_ID;?>" readonly='readonly'></td>
	</tr>
	<tr>
		<td>Quantity Received</td><td><input type="text" name="Quantity"  value="<?php echo$Quantity;?>" readonly='readonly'></td>
		
		<input type="hidden"  id="diff" name='diff'  readonly='readonly'>
	</tr>
	<tr>
		<td>Quantity Remained</td><td><input type="text" name="Quantity" id="Quantity" value="<?php echo$Remain;?>" readonly='readonly'></td>
	</tr>
	
	
</table>
</fieldset>
</form>
</center>

<?php
    include("./includes/footer.php");
?>