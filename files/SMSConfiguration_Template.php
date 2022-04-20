<?php
    include("./includes/connection.php");
    include("./includes/header.php");

    $from = "";

    if(isset($_GET['from']) && $_GET['from'] == "smsConfig") {
        $from = $_GET['from'];
    } else {
        $from = "";
    }

    $controlforminput = '';
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    
    if(isset($_SESSION['userinfo']['Setup_And_Configuration'])){
	if($_SESSION['userinfo']['Setup_And_Configuration'] != 'yes'){
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
     
?>

<a href="SMSConfiguration.php?SMSConfiguration=SMSConfiguration&from=<?php echo $from; ?>" class="art-button-green">BACK</a>
<br/><br/><br/><br/><br/><br/>
<fieldset>  
	<legend align=center><b>ADD OR EDIT SMS TEMPLATE</b></legend>
	<center>
		<table width=60%>
			<tr>
				<td width="30%">
					<strong>SELECT MESSAGE TYPE:</strong>
				</td>
				
				<td width="60%">
					<strong>ENTER THE MESSAGE:</strong>
				</td>
				
				<td width="10%">
				</td>
			</tr>
			
			<tr>
				<td>
					<select id="Message_Department" required="required" style=" height:30px; padding-left:10px; font-size:16px;" onChange="GetMessageTemplates(this.value)" >
						<option value=""> - Select Message Type - </option>
						<option value="Registration">Patient Registration</option>
						<option value="Radiology">Radiology Results</option>
						<option value="Lab">Laboratory Results</option>
					</select>
				</td>
				
				<td>
					<textarea id="Message" rows="" cols="" style='padding-left:12px;'></textarea>
				</td>
				
				<td>
					<input type="button" class='art-button-green' onClick="SaveMessageTemplates()"  id="SaveMessage" value="SAVE MESSAGE" style="margin-left:13px !important;" />
				</td>
			</tr>
		</table>
		<div id="Cached" style="width:60%;"></div>
		<div id="DelResults"  style="width:60%;"></div>
		<div id="ItemParameters"  style="width:60%;"></div>
		
		<script>
			//GET ITEM PARAMETERS
			function GetMessageTemplates(Dept){
				if(window.XMLHttpRequest) {
					gip = new XMLHttpRequest();
				}
				else if(window.ActiveXObject){ 
					gip = new ActiveXObject('Micrsoft.XMLHTTP');
					gip.overrideMimeType('text/xml');
				}
				gip.onreadystatechange = AJAXStat; 
				gip.open('GET','GetMessageTemplates.php?Dept='+Dept,true);
				gip.send();

				function AJAXStat() {
					var respond = gip.responseText; 
					document.getElementById('Message').value = respond;
				}					
			}
		
			//CACHE THE TEMPLATE
			function SaveMessageTemplates(){
				var Message_Department = document.getElementById('Message_Department').value;
				var Message = document.getElementById('Message').value;
				
				if(Message == ''){
					alert('You can not Save an Empty Message');
					exit;
				} else if(Message_Department == ''){
					alert('You Must select Message type');
					exit;					
				}
						
				if(window.XMLHttpRequest) {
					add = new XMLHttpRequest();
				}
				else if(window.ActiveXObject){ 
					add = new ActiveXObject('Micrsoft.XMLHTTP');
					add.overrideMimeType('text/xml');
				}
				add.onreadystatechange = AJAXStat; 
				add.open('GET','SaveMessageTemplates.php?Message_Department='+Message_Department+'&Message='+Message,true);
				add.send();

				function AJAXStat() {
					var respond = add.responseText; 
					document.getElementById('Cached').innerHTML = respond;	
					document.getElementById('Message').value = '';	
					document.getElementById('Message_Department').value = '';	
				}	
			}
			
			//DELETE MESSAGE
			function RemoveThis(Parameter_ID){
				var CachedRow = 'row'+Parameter_ID;
				document.getElementById(CachedRow).style.display = 'none';
				if(window.XMLHttpRequest) {
					del = new XMLHttpRequest();
				}
				else if(window.ActiveXObject){ 
					del = new ActiveXObject('Micrsoft.XMLHTTP');
					del.overrideMimeType('text/xml');
				}
				del.onreadystatechange = AJAXDel; 
				del.open('GET','DeleteCachedRadiologyParameters.php?Parameter_ID='+Parameter_ID+'&Delete=yes',true);
				del.send();

				function AJAXDel() {
					var paramdeleted = del.responseText;
				}
			}			
		</script>					
	</center>
</fieldset>
<br/>

<?php
    include("./includes/footer.php");
?>