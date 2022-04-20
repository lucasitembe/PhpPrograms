<?php
    include("./includes/header.php");
    include("./includes/connection.php");
	
	if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'])){
	    if($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'] != 'yes'){
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
        if($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'] == 'yes'){ 
?>
    <a href='doctorspagetransfered.php' class='art-button-green'>
         TRANSFERED PATIENTS
    </a>
<?php  } } ?>

<?php
if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'] == 'yes'){ 
?>
    <a href='transferworks.php' class='art-button-green'>
         BACK
    </a>
<?php  } } ?>

<script type="text/javascript" language="javascript">
    function getpatients(Employee_IDs) {
	    if(window.XMLHttpRequest) {
		mm = new XMLHttpRequest();
	    }
	    else if(window.ActiveXObject){ 
		mm = new ActiveXObject('Micrsoft.XMLHTTP');
		mm.overrideMimeType('text/xml');
	    }
		
		var Employee_IDs = document.getElementById('Employee_IDs').value;
		//alert(Employee_ID);
		//document.location='getpatients.php?Employee_ID='+Employee_ID;
	    mm.onreadystatechange= AJAXP; //specify name of function that will handle server response....
	    mm.open('GET','GetConsulted.php?Employee_IDs='+Employee_IDs,true);
	    mm.send();  
	}
    function AJAXP() {
	var data = mm.responseText;
	//alert (data);
	document.getElementById('Selected_patient').innerHTML = data;	
    }
    
   
</script>

<script type="text/javascript" language="javascript">
	function saveUpdate(box,Registration_ID){
	
			var Employee_ID = document.getElementById('user_'+Registration_ID).value;
		//	var Clinic = document.getElementById('Clinic_'+Registration_ID).value;
			var Reason = document.getElementById('reason_'+Registration_ID).value;
			var Employee_IDs = document.getElementById('Employee_IDs').value;
			var didthis = document.getElementById('didthis').value ;
			var Patient_Direction= '';
			if (Employee_ID !=''){
			Patient_Direction='doctor';
			}else {
			Patient_Direction='clinic';
			}
/* if(Employee_ID !='' && Employee_IDs != '')
	{
	  Employee_ID.setAttribute("disabled","disabled");
	  Employee_ID.setAttribute("disabled","disabled");
	} */
			   if(window.XMLHttpRequest) {
				mm2 = new XMLHttpRequest();
				}
				else if(window.ActiveXObject){ 
				mm2 = new ActiveXObject('Microsoft.XMLHTTP');
				mm2.overrideMimeType('text/xml');
				} 
//document.location='GetPatientUpdate.php?Registration_ID='+Registration_ID+'&Employee_ID='+Employee_ID+'&Reason='+Reason+'&didthis='+didthis+'&Employee_IDs='+Employee_IDs;
			  mm2.onreadystatechange= function (){
									if(mm2.readyState == 4){
								box.setAttribute("disabled","disabled");
										var data2 = mm2.responseText;
										alert (data2);
									}
								}  					
			mm2.open('GET','GetUpdateConsulted.php?Registration_ID='+Registration_ID+'&Employee_ID='
			+Employee_ID+'&Reason='+Reason+'&didthis='+didthis+'&Employee_IDs='+Employee_IDs+'&Patient_Direction='
			+Patient_Direction,true);
			
			mm2.send();   	
}	
</script>

<script>
	/* function disableElement(Registration_ID,elname){
		el1 = document.getElementById('user_'+Registration_ID);
		//el2 = document.getElementById('Clinic_'+Registration_ID);
		if(elname == 'Clinic_'){
			if(el2.value != '' && el2.value != null){
				el1.setAttribute('disabled','disabled');
			}else{
				el1.removeAttribute('disabled');
			}
		}else{
			if(el1.value != '' && el1.value != null){
				el2.setAttribute('disabled','disabled');
			}else{
				el2.removeAttribute('disabled');
			}
		}
	} */
</script>
<br><br>
<br><br>
<!--  transfer form-->
<center>
<form action="#" method="POST" name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">
<fieldset style="width:90%;margin-top:10px;">
	<legend align="center"><b>TRANSFER WORKS</b></legend>
	<br/>
	<table  class='' width='100%'>
		<tr>
			<td width='10%' style="text-align:right;"><b>TRANSFER FROM DOCTOR</b></td>
			
			<td width='20%' >
				<select name="employee_ID" id="Employee_IDs" onchange="getpatients()" >
					<option selected="selected"></option>
					<?php
					
							$consult=mysqli_query($conn,"Select * from tbl_employee where Employee_Type='Doctor'");
							{
							while($row = mysqli_fetch_array($consult)){
						
							$Employee_IDS = $row['Employee_ID'];
							$Employee_Name = $row['Employee_Name'];
							
					?>
						<option  value="<?php echo $Employee_IDS;?>">
									<?php echo $Employee_Name;?>		 			 
						</option>
					<?php
						}
						}					
					?>
				</select>
				
			</td>
		</tr>
		<tr>
			<td style="text-align:center;" colspan=2>
				<div id="Selected_patient">
				
				</div>
				<input type="hidden" id="didthis"  value="<?php echo $_SESSION['userinfo']['Employee_ID']; ?>" >
			</td>
		</tr>
	</table>
</fieldset>
</form>
<!--  End ofform-->

</center>


<?php
    include("./includes/footer.php");
?>