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

    if(isset($_SESSION['userinfo']['Employee_ID'])){
    	$Employee_ID =$_SESSION['userinfo']['Employee_ID'];
    }else{
    	$Employee_ID = NULL;
    }
?>
<a href='setupandconfiguration.php?BackToSetupAndConfiguration=BackTosetupAndConfigurationThisPage' class='art-button-green'>BACK</a>
<br/><br/>
<?php

	$select = mysqli_query($conn,"select * from tbl_printer_settings") or die(mysqli_error($conn));
	$num = mysqli_num_rows($select);
	if($num > 0){
		while ($data = mysqli_fetch_array($select)) {
			$Paper_Type = $data['Paper_Type'];
			$Include_Sponsor_Name_On_Printed_Receipts = $data['Include_Sponsor_Name_On_Printed_Receipts'];
		}
	}else{
		mysqli_query($conn,"insert into tbl_printer_settings(
						Paper_Type, Include_Sponsor_Name_On_Printed_Receipts, Date_Created, Created_By)
						values('Receipt','yes',(select now()),'$Employee_ID')") or die(mysqli_error($conn));
		$Paper_Type = 'Receipt';
		$Include_Sponsor_Name_On_Printed_Receipts = 'yes';
}

$max_number_of_receipt = 0;
$selecet_max_number_of_receipt = "SELECT max_receipt FROM tbl_receipt_config";
if ($select_result = mysqli_query($conn,$selecet_max_number_of_receipt)) {
	while($row = mysqli_fetch_assoc($select_result)){
		$max_number_of_receipt = $row['max_receipt'];	

	}

	
}

?>
<br/><br/>
<fieldset>
	<legend align="left" ><b>PRINTER CONFIGURATIONS</b></legend>
	<table width="100%">
		<tr>
			<td>
				<input type="radio" value="yes" name="Receipt_Setting" id="Receipt_Mode" <?php if(strtolower($Paper_Type) == 'receipt'){ echo "checked='checked'"; } ?>>
				<label for="Receipt_Mode" onclick="ShoElement()">Receipt Printing Mode</label>
			</td>
			<td>
				<input type="radio" value="yes" name="Receipt_Setting" id="A4_Mode" <?php if(strtolower($Paper_Type) != 'receipt'){ echo "checked='checked'"; } ?>>
				<label for="A4_Mode" onclick="ShoElement()">( A4,A5,A6,...) Printing Mode</label>
			</td>
			<td>
				<input type="checkbox" name="Include_Sponsor_Name_On_Printed_Receipts" id="Include_Sponsor_Name_On_Printed_Receipts" id="Include_Sponsor_Name_On_Printed_Receipts" <?php if(strtolower($Include_Sponsor_Name_On_Printed_Receipts) == 'yes'){ echo "checked='checked'"; } ?>>
				<label for="Include_Sponsor_Name_On_Printed_Receipts" onclick="ShoElement()">Include Sponsor Name on Printed Receipts</label>
			</td>
		</tr>

		<input type="hidden" name="employee_id" id="employee_id" value="<?=$Employee_ID?>"> 
		<tr>
			
			<td>SET NUMBER OF RECEIPT TO BE PRINTED PER USER</td>
						
			<td>
				<input type="text" name="receipt_number" id="set-number" value="<?php 
				if (!empty($max_number_of_receipt)) {
					echo $max_number_of_receipt;
				}
				?>">
			</td>
			
			<td colspan="">
				<div id="loading"></div>
			</td>
		</tr>

		<tr>
			<td colspan="3" style="text-align: right;">
				<input type="button" id="Update_Button" onclick="Update_Settings()" class="art-button-green" value="UPDATE" style='visibility: hidden;'>
				<input type="button" id="Cancel_Button" onclick="Cancel_Settings()" class="art-button-green" value="CANCEL" style='visibility: hidden;'>
			</td>
		</tr>
	</table>
</fieldset>
	<div id="set-receipt">
	</div>
<script type="text/javascript">     
    function ShoElement(){
        document.getElementById("Update_Button").style.visibility = 'visible';
        document.getElementById("Cancel_Button").style.visibility = 'visible';
    }
</script>

<script type="text/javascript">
	function Cancel_Settings(){
		window.open("printconfigurations.php?printerConfigurations=printerConfigurationsThisForm","_parent");
	}
</script>

<script type="text/javascript">
	function Update_Settings(){
		var Receipt_Setting = 'Others( A4,A5,A6,...)';
		var Include_Sponsor_Name_On_Printed_Receipts = 'no';

		if(document.getElementById("Receipt_Mode").checked){
			Receipt_Setting = 'Receipt';
		}

		if(document.getElementById("Include_Sponsor_Name_On_Printed_Receipts").checked){
			Include_Sponsor_Name_On_Printed_Receipts = 'yes';
		}

		if(window.XMLHttpRequest){
		    myObjectUpdate = new XMLHttpRequest();
		}else if(window.ActiveXObject){
		    myObjectUpdate = new ActiveXObject('Micrsoft.XMLHTTP');
		    myObjectUpdate.overrideMimeType('text/xml');
		}
		myObjectUpdate.onreadystatechange = function (){
		    dataUpdate = myObjectUpdate.responseText;
		    if (myObjectUpdate.readyState == 4) {
		    	alert("Printing settings updated successfully");
		    	window.open("printconfigurations.php?printerConfigurations=printerConfigurationsThisForm","_parent");
		    }
		}; //specify name of function that will handle server response........
		
		alert(Include_Sponsor_Name_On_Printed_Receipts)
		myObjectUpdate.open('GET','Update_Printing_Mode.php?Receipt_Setting='+Receipt_Setting+'&Include_Sponsor_Name_On_Printed_Receipts='+Include_Sponsor_Name_On_Printed_Receipts,true);
		myObjectUpdate.send();
	}


	$(document).ready(function(e){
		

		$('#set-receipt').dialog({
             autoOpen: false,
             modal: true,
             width: 700,
             height:300,
             title: 'Set Maximum Receipt Number To Print'
         });
	})


	// set receipt number
	$("#set-number").keyup(function(e){
		e.preventDefault();

		var receiptNumber = $(this).val()
		// alert(receiptNumber)

		var employee_id = $("#employee_id").val();

		// alert(employee_id)

		if (receiptNumber === '') {
			alert("you nust fill in receipt number");
			return false;
		}else if(receiptNumber == 0){
			alert("Receipt number can not be set to zero")
			return false;
		}else{

		$.ajax({
			type:"post",
			url:"save_receipt_number.php",
			data:{receiptNumber:receiptNumber,employee_id:employee_id},
			beforeSend:function(){
				$("#loading").html('<img src="images/saving.gif" title="saving"  width="40px"/>')
			},
			success:function(data){
				console.log(data)
				$("#loading").html('<img src="images/save_icon.png" title="saving"  width="40px"/>')
			}
		})

		}
		
		
		// $("#set-receipt").dialog("open");

		// $.ajax({
		// 	type:"GET",
		// 	url:"receipt_config.php",
		// 	success:function(data){
		// 		console.log(data)
		// 		$("#set-receipt").append(data)
		// 	}
		// })
		// alert("dialog show")
	})


</script>
<?php
    include("./includes/footer.php");
?>