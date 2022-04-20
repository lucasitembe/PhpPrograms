<link rel="stylesheet" type="text/css" href="jquerytabs/jquery-ui.theme.css"/>
<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Radiology_Works'])){
	    if($_SESSION['userinfo']['Radiology_Works'] != 'yes'){
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
        if($_SESSION['userinfo']['Radiology_Works'] == 'yes'){ 
?>
    <a href='Radiologyparameter.php?RadiologyParameterThisPage=RadiologyParameterThisPage' class='art-button-green'>
        BACK
    </a>
<?php  } } ?>

<br/><br/><br/>
<center>               
<table width=80%>
    <tr>
        <td>
            <fieldset>  
            <legend align="center"><b>ENTER RADIOLOGY PARAMETER</b></legend>
            <table width=100%>
				<tr>
					<td width="40%">
						 <strong>Select Test Name:</strong>
					</td>
					
					<td width="40%">
						<strong>Enter Parameter Name:</strong>
					</td>
					
					<td>
					</td>
				</tr>
				
				<tr>
					<td>
						<select id="Item_ID" name="Item_ID" style=" height:32px; padding-left:10px;" onChange="GetItemParameters(this.value)" >
							<option value=""> -- Select Item -- </option>
							<option value="0"> Defauld Parameters </option>
							<?php 
								$select_items = "SELECT Item_ID, Product_Name FROM tbl_items WHERE Consultation_Type = 'Radiology' GROUP BY Product_Name ASC";
								$select_items_qry = mysqli_query($conn,$select_items) or die(mysqli_error($conn));
								while($RadiItems = mysqli_fetch_assoc($select_items_qry)){
									$RadiItemID = $RadiItems['Item_ID'];
									$RadiItemName = $RadiItems['Product_Name'];
									echo "<option value='".$RadiItemID."'>".$RadiItemName."</option>";
								}
							?>
						</select>
					</td>
					
					<td>
                         <input type='text' name='Parameter_Name' style='padding-left:12px; height:28px;' id='Parameter_Name' required='required' placeholder='Enter Parameter'>
					</td>
					
					<td>
                        <button class='art-button-green' onClick="AddToCache()" style="margin-left:13px !important;" >ADD</button>
					</td>
				</tr>
            </table>
			
			<div id="Cached"></div>
			<div id="DelResults"></div>
			<div id="ItemParameters"></div>
			
			<script>
				//GET ITEM PARAMETERS
				function GetItemParameters(Item_ID){
					if(window.XMLHttpRequest) {
						gip = new XMLHttpRequest();
					}
					else if(window.ActiveXObject){ 
						gip = new ActiveXObject('Micrsoft.XMLHTTP');
						gip.overrideMimeType('text/xml');
					}
					gip.onreadystatechange = AJAXStat; 
					gip.open('GET','GetItemParameters.php?Item_ID='+Item_ID,true);
					gip.send();

					function AJAXStat() {
						var respond = gip.responseText; 
						document.getElementById('ItemParameters').innerHTML = respond;	
					}					
				}
			
				//CACHE THE PARAMETERS
				function AddToCache(){
					var Item_ID = document.getElementById('Item_ID').value;
					var Parameter_Name = document.getElementById('Parameter_Name').value;
							
					if(window.XMLHttpRequest) {
						add = new XMLHttpRequest();
					}
					else if(window.ActiveXObject){ 
						add = new ActiveXObject('Micrsoft.XMLHTTP');
						add.overrideMimeType('text/xml');
					}
					add.onreadystatechange = AJAXStat; 
					add.open('GET','CacheRadiologyParameters.php?Item_ID='+Item_ID+'&Parameter_Name='+Parameter_Name,true);
					add.send();

					function AJAXStat() {
						var respond = add.responseText; 
						document.getElementById('Cached').innerHTML = respond;	
						document.getElementById('Parameter_Name').value = '';	
					}	
				}
				
				//DELETE ITEM PARAMETER
				function RemoveThisParam(Parameter_ID){
					var ParamRow = 'param'+Parameter_ID;
					document.getElementById(ParamRow).style.display = 'none';
					if(window.XMLHttpRequest) {
						delp = new XMLHttpRequest();
					}
					else if(window.ActiveXObject){ 
						delp = new ActiveXObject('Micrsoft.XMLHTTP');
						delp.overrideMimeType('text/xml');
					}
					delp.onreadystatechange = AJAXDelP; 
					delp.open('GET','DeleteRadiologyParameters.php?Parameter_ID='+Parameter_ID,true);
					delp.send();
				}				
				function AJAXDelP() {
					var paramdel = delp.responseText;
					//alert(paramdel);
					//document.GetElementById('DelResults').innerHTML = paramdel;
				}
				
				//DELETE CACHE ITEM
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
				
				//SAVE ALL PARAMETERS
				function SaveAll(){
					var Sav = 'yes';
					if(window.XMLHttpRequest) {
						sav = new XMLHttpRequest();
					}
					else if(window.ActiveXObject){ 
						sav = new ActiveXObject('Micrsoft.XMLHTTP');
						sav.overrideMimeType('text/xml');
					}
					sav.onreadystatechange = AJAXSav; 
					sav.open('GET','SaveRadiologyParameters.php?Save='+Sav,true);
					sav.send();

					function AJAXSav() {
						var saveResponse = sav.responseText;
						document.getElementById('Cached').innerHTML = "<strong style='background-color:white; color:green;'><center> &#x02713; Parameters Saved.</center></strong>";
						//alert('Parameters Saved!');
					} 
				}				
			</script>			
			

<script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript" src="js/jquery.searchabledropdown-1.0.8.min.js"></script>
	
	<script type="text/javascript">
		$(document).ready(function() {
			$("select").searchable();
		});
	
		// demo functions
		$(document).ready(function() {
			$("#value").html($("#Item_ID :selected").text() + " (VALUE: " + $("#Item_ID").val() + ")");
			$("select").change(function(){
				$("#value").html(this.options[this.selectedIndex].text + " (VALUE: " + this.value + ")");
			});
		});
	
		function modifySelect() {
			$("select").get(0).selectedIndex = 5;
		}
	
		function appendSelectOption(str) {
			$("select").append("<option value=\"" + str + "\">" + str + "</option>");
		}
	
		function applyOptions() {			  
			$("select").searchable({
				maxListSize: $("#maxListSize").val(),
				maxMultiMatch: $("#maxMultiMatch").val(),
				latency: $("#latency").val(),
				exactMatch: $("#exactMatch").get(0).checked,
				wildcards: $("#wildcards").get(0).checked,
				ignoreCase: $("#ignoreCase").get(0).checked
			});
	
			alert(
				"OPTIONS\n---------------------------\n" + 
				"maxListSize: " + $("#maxListSize").val() + "\n" +
				"maxMultiMatch: " + $("#maxMultiMatch").val() + "\n" +
				"exactMatch: " + $("#exactMatch").get(0).checked + "\n"+
				"wildcards: " + $("#wildcards").get(0).checked + "\n" +
				"ignoreCase: " + $("#ignoreCase").get(0).checked + "\n" +
				"latency: " + $("#latency").val()
			);
		}
	</script>
			
</fieldset>
        </td>
    </tr>
</table>      
        </center>
<br/>
<?php
    include("./includes/footer.php");
?>

