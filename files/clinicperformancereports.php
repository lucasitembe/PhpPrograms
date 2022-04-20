<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Management_Works'])){
	    if($_SESSION['userinfo']['Management_Works'] != 'yes'){
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
        if($_SESSION['userinfo']['Management_Works'] == 'yes'){ 
?>
    <a href='./managementworkspage.php?ManagementWorksPage=ThisPage' class='art-button-green'>
        BACK
    </a>
<?php  } } ?>

<br/><br/>
<center>
 <style>
        table,tr,td{
        border-collapse:collapse !important;
        border:none !important;
        
        }
    tr:hover{
    background-color:#eeeeee;
    cursor:pointer;
    }
 </style> 
    <table width="80%">
    	<tr>
    		<td style="text-align: rigth;"><b>SPONSOR</b></td>
    		<td style="text-align: left;">
    			<select name='Sponsor_ID' id='Sponsor_ID'>
					<option selected='selected' value='0'>All</option>
						<?php
						    $data = mysqli_query($conn,"select * from tbl_sponsor order by Guarantor_Name");
						        while($row = mysqli_fetch_array($data)){
						    ?>
						    		<option value='<?php echo $row['Sponsor_ID'];?>'><?php echo $row['Guarantor_Name']; ?></option>
						<?php
					    		}
						?>
				</select>
    		</td>
    		<td width="10%" style="text-align: right;"><b>CLINIC NAME</b></td>
    		<td width="">
    			<select name='Clinic_ID' id='Clinic_ID'>
					<option selected='selected' value='0'>All</option>
						<?php
						    $data = mysqli_query($conn,"select * from tbl_clinic");
						        while($row = mysqli_fetch_array($data)){
						    ?>
						    		<option value='<?php echo $row['Clinic_ID'];?>'>
						<?php echo $row['Clinic_Name']; ?>
						    		</option>
						<?php
					    		}
						?>
				</select>
    		</td>
    		<td width="" style="text-align: right;"><b>Start Date</b></td>
    		<td width="15%">
    			<input type='text' name='Date_From' id='date_From' style="text-align: center;" required='required' autocomplete='off' style="text-align: center;">
    		</td>
    		<td width="" style="text-align: right;"><b>End Date</b></td>
    		<td width="15%">
    			<input type='text' name='Date_To' id='date_To' style="text-align: center;" required='required' autocomplete='off'>
    		</td>
    		<td width="12%" style="text-align: center;">
    			<input type='button' name='Print_Filter' id='Print_Filter' class='art-button-green' value='FILTER' onclick="Filter_Patients()">
    		</td>
    	</tr>
    </table>
<fieldset style='overflow-y: scroll; height: 305px; background-color:white' id='Patient_List'>
	<legend align="right" style="background-color:#006400;color:white;padding:5px;" ><b>CLINICS PERFORMANCE REPORT</b></legend>
	<table width =100% style="border: 0">
		<tr>
            <td style='text-align:left; width:3%;border: 1px #ccc solid;'><b>SN</b></td>
            <td style='text-align:left; border: 1px #ccc solid;'><b>SPONSOR NAME</b></td>
            <td style='text-align:right; width:20%;border: 1px #ccc solid;'><b>CONSULTED PATIENTS</b></td>
            <td style='text-align:right; width:20%;border: 1px #ccc solid;'><b>NON-CONSULTED PATIENTS</b></td>
			<td style='text-align:right; width:15%;border: 1px #ccc solid;'><b>TOTAL PATIENTS</b></td>
		</tr>
		<tr><td colspan=5 style='border: 1px #ccc solid'><hr></td></tr>
		<?php
			$temp = 0;
			$select = mysqli_query($conn,"select Guarantor_Name from tbl_sponsor order by Guarantor_Name") or die(mysqli_error($conn));
			$num = mysqli_num_rows($select);
			if($num > 0){
				while ($data = mysqli_fetch_array($select)) {
		?>
					<tr>
						<td><?php echo ++$temp; ?></td>
						<td><?php echo $data['Guarantor_Name']; ?></td>
						<td style="text-align: right;">0</td>
						<td style="text-align: right;">0</td>
						<td style="text-align: right;">0</td>
					</tr>
		<?php
				}
			}
		?>
	</table>
</fieldset>


<script>
	function Filter_Patients(){
		var date_From = document.getElementById("date_From").value;
		var date_To = document.getElementById("date_To").value;
		var Clinic_ID = document.getElementById("Clinic_ID").value;
		var Sponsor_ID = document.getElementById("Sponsor_ID").value;
		if(date_From != null && date_From != '' && date_To != null && date_To != ''){
			if(window.XMLHttpRequest) {
			    myObjectFilter = new XMLHttpRequest();
			}else if(window.ActiveXObject){ 
			    myObjectFilter = new ActiveXObject('Micrsoft.XMLHTTP');
			    myObjectFilter.overrideMimeType('text/xml');
			}
		    
			myObjectFilter.onreadystatechange = function (){
				data = myObjectFilter.responseText;
				if (myObjectFilter.readyState == 4) {
				    document.getElementById('Patient_List').innerHTML = data;
				}
			}; //specify name of function that will handle server response........
			myObjectFilter.open('GET','Clini_Performance_Reception.php?Clinic_ID='+Clinic_ID+'&date_From='+date_From+'&date_To='+date_To+'&Sponsor_ID='+Sponsor_ID,true);
			myObjectFilter.send();
		}else{
			if(date_From == null || date_From == ''){
				document.getElementById("date_From").style = 'border: 3px solid red; text-align: center;';
			}else{
				document.getElementById("date_From").style = 'border: 3px white; text-align: center;';
			}

			if(date_To == null || date_To == ''){
				document.getElementById("date_To").style = 'border: 3px solid red; text-align: center;';
			}else{
				document.getElementById("date_To").style = 'border: 3px white; text-align: center;';
			}
		}		
	}
</script>



<script src="css/jquery.js"></script>
<script src="css/jquery.datetimepicker.js"></script>
<script>
$('#date_From').datetimepicker({
dayOfWeekStart : 1,
lang:'en',
startDate:	'now'
});
$('#date_From').datetimepicker({value:'',step:30});
$('#date_To').datetimepicker({
dayOfWeekStart : 1,
lang:'en',
startDate:'now'
});
$('#date_To').datetimepicker({value:'',step:30});
</script>
<!--End datetimepicker-->
     
<?php
    include("./includes/footer.php");
?>