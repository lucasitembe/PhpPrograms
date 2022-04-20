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
        if($_SESSION['userinfo']['Setup_And_Configuration'] == 'yes'){ 
?>
    <a href='foodworkpage.php' class='art-button-green'>
       BACK
    </a>
<?php  } } ?>

<center>

<?php
   
//function of list of patients from reception
	$Vital =mysqli_query($conn,"SELECT Count(*) as vita  from tbl_food_transaction 
	where Food_Time='BREAKFAST'  AND DATE(Trans_Date_Time)=DATE_SUB('".DATE("Y-m-d")."',INTERVAL 0 DAY) AND 
	(TIME(Trans_Date_Time) BETWEEN '00:00:01' AND '08:30:30' )  
	 ") or die(mysqli_error($conn));
	$Vitals = mysqli_num_rows($Vital);
	if($Vitals>0){
            while($row = mysqli_fetch_array($Vital)){
                $vita = $row['vita'];
				}
				}
	//end of function of count
	
	//function of list of patients from reception
	$Vital =mysqli_query($conn,"SELECT Count(Food_transaction_ID) as vitas  from tbl_food_transaction 
	where Food_Time='LUNCH'  AND DATE(Trans_Date_Time)='".DATE("Y-m-d")."'  ") or die(mysqli_error($conn));
	$Vitals = mysqli_num_rows($Vital);
	if($Vitals>0){
            while($row = mysqli_fetch_array($Vital)){
                $vitas = $row['vitas'];
				}
				}
	//end of function of count
	
	//function of list of patients from reception
	$Vital =mysqli_query($conn,"SELECT Count(*) as vita2 from tbl_food_transaction 
	where Food_Time='DINNER'  and DATE(Trans_Date_Time)='".DATE("Y-m-d")."' ") or die(mysqli_error($conn));
	$Vitals = mysqli_num_rows($Vital);
	if($Vitals>0){
            while($row = mysqli_fetch_array($Vital)){
                $vita2 = $row['vita2'];
				}
				}
	//end of function of count
	
	
	//TO FIND TOTAL NO OF ALL APTIENT WHO ADMITTED

	$Vital =mysqli_query($conn,"SELECT Count(Registration_ID) as noms  from  
	tbl_admission pr
	WHERE Registration_ID IN (SELECT ad.Registration_ID
				FROM 
						tbl_Patient_Registration pr,tbl_admission ad
						
				WHERE 
				        pr.Registration_ID = ad.Registration_ID AND  
					
						Admission_Status='Admitted'
						group by pr.Registration_ID ) ") or die(mysqli_error($conn));
	
	$Vitals = mysqli_num_rows($Vital);
	if($Vitals>0){
            while($row = mysqli_fetch_array($Vital)){
                $noms = $row['noms'];
				}
				}
	//end of function of count
	
  
?>

<script>
//for breakfast msg
function CountPatients() {
	var b = parseInt(document.getElementById('vita').value);
    if ( b==0 ) {
        alert ("NO FOOD TRANSACTION FOR BREAKFAST");
			document.location = './foodkitchen.php';
	 }
	else{
	
	document.location = './foodcount.php';
	}
 }  
 
 //for lunch msg
 function CountPatients2() {
	var b = parseInt(document.getElementById('vitas').value);
    if ( b==0 ) {
        alert ("NO FOOD TRANSACTION FOR LUNCH");
			document.location = './foodkitchen.php';
	 }
	else{
	
	document.location = './foodcount2.php';
	}
 }
 
 
 //for dinner msg
 function CountPatients3() {
	var b = parseInt(document.getElementById('vita2').value);
    if ( b==0 ) {
        alert ("NO FOOD TRANSACTION FOR DINNER");
			document.location = 'foodkitchen.php';
	 }
	else{
	
	document.location = 'foodcount3.php';
	}
 }
</script>
<br/>
<br/>
<br/>

<fieldset style="width:30%;">  
            <legend align="center"><b>SUMMARY MENU OF TODAY</b></legend>
     
			<center>
				<table width=100%>
					<tr>
						<th>FOOD TIME</th><th>NUMBERS</th>
					</tr>
					<tr>
						<td style="text-align:right;"><a href="#.php" target='_parent' style='text-decoration:none;color:#0000FF;'onclick="CountPatients()" > BREAKFAST </a></td>
						<td><input type="text" readonly='readonly' id="vita" value="<?php echo$vita;?>"></td>
					</tr>
					<tr>
						<td style="text-align:right;"><a href="#.php" target='_parent' onclick="CountPatients2()" style='text-decoration: none;color:#0000FF;'> LUNCH </a></td>
						<td><input type="text" readonly='readonly' id="vitas"  value="<?php echo$vitas;?>"></td>
						
					</tr>
					<tr>
						<td style="text-align:right;"><a href="#.php" target='_parent' onclick="CountPatients3()" style='text-decoration: none;color:#0000FF;'> DINNER </a></td>
						<td><input type="text" readonly='readonly' id="vita2"  value="<?php echo$vita2;?>"></td>
						
						
					</tr>
				</table>
			</center>
</fieldset>
<br/><br/>
<fieldset style="width:40%;">  
            <legend align="center"><b>ALL ADMISSIONS PATIENTS</b></legend>
			<center>
				<table>
					<tr>
						<td><b>PATIENTS</b></td><td><input type="text" readonly='readonly' value="<?php echo$noms;?>"></td>
					</tr>
				</table>
			</center> 
</fieldset>
</center>
<?php
    include("./includes/footer.php");
?>