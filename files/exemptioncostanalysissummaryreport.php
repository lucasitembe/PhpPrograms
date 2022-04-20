<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Msamaha_Works'])){
	    if($_SESSION['userinfo']['Msamaha_Works'] != 'yes'){
		header("Location: ./index.php?InvalidPrivilege=yes");
	    }
	}else{
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	    header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_GET['Registration_ID'])){
        $Registration_ID = $_GET['Registration_ID'];
    }
    
    //get today date
    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $Today = $original_Date;
    }
?>
	<input type="button" name="Preview_Configuration" id="Preview_Configuration" class="art-button-green" value="EXEMPTION SUMMARY CONFIGURATION" onclick="Exemption_Configuration_Dialog()">
    <a href='msamahareports.php?MsamahaReports=MsamahaReportsThisForm' class='art-button-green'>BACK</a>
<?php

    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $Today = $row['today'];
    }
?>

<style>
    table,tr,td{
        border-collapse:collapse !important;
        border:none !important;
    }
    #sss:hover{
        background-color:#eeeeff;
        cursor:pointer;
        border-collapse:collapse !important;
        border:none !important;
    }
</style>
<br/><br/>
<fieldset>
    <table width=100%>
        <tr>
        	<td style="text-align: right;"><b>Category Name</b></td>
			<td>
				<select name="Item_Category_ID" id="Item_Category_ID">
					<option selected="selected" value="0">All</option>
					<?php
						$select = mysqli_query($conn,"select ic.Item_Category_Name, ic.Item_Category_ID from tbl_item_category ic, tbl_exemption_categories ec where 
												ic.Item_Category_ID = ec.Item_Category_ID order by Item_Category_Name") or die(mysqli_error($conn));
	    				$num = mysqli_num_rows($select);
	    				if($num > 0){
	    					while ($data = mysqli_fetch_array($select)) {
					?>
								<option value="<?php echo $data['Item_Category_ID']; ?>"><?php echo ucwords(strtolower($data['Item_Category_Name'])); ?></option>
					<?php 	}
						}
					?>
				</select>
			</td>
			<td style="text-align: right;"><b>Exemption Type</b></td>
			<td>
				<select name="msamaha_Items" id="msamaha_Items">
					<option selected="selected" value="0">All</option>
					<?php
						$select = mysqli_query($conn,"select msamaha_Items, msamaha_aina from tbl_msamaha_items order by msamaha_aina") or die(mysqli_error($conn));
						$num = mysqli_num_rows($select);
						if($num > 0){
							while ($data = mysqli_fetch_array($select)) {
					?>
								<option value="<?php echo $data['msamaha_Items']; ?>"><?php echo ucwords(strtolower($data['msamaha_aina'])); ?></option>
					<?php
							}
						}
					?>
				</select>
			</td>
            <td>
                <input type='text' name='Start_Date' id='Date_From' style='text-align: center;' placeholder='~~~ Start Date ~~~' readonly='readonly' value='' title="Start Date">
            </td>
            <td>
                <input type='text' name='End_Date' id='Date_To' style='text-align: center;' placeholder='~~~ End Date ~~~' readonly='readonly' value='' title="End Date">
            </td>
            <td style='text-align: center;' width=10%>
                <input type='button' name='Filter' id='Filter' value='FILTER' class='art-button-green' onclick='Filter_Details()'>
            </td>
            <td width="8%"><input type="button" name="Report_Button" id="Report_Button" value="DETAILS REPORT" class="art-button-green" onclick="Preview_Report()"></td>
            <td width="8%"><input type="button" name="Report_Button" id="Report_Button" value="SUMMARY REPORT" class="art-button-green" onclick="Preview_Summary_Report()"></td>
        </tr>
    </table>
</fieldset>
<fieldset style='overflow-y: scroll; height: 400px;background-color:white;margin-top:20px;' id='Details_Area'>
	<legend style="background-color:#006400;color:white;padding:5px;"><b>EXEMPTION COST ANALYSIS REPORT</b></legend>
</fieldset>

<div id="Exemption_Categories">
	
</div>

<script type="text/javascript">
	function Filter_Details(){
		var Item_Category_ID = document.getElementById("Item_Category_ID").value;
		var msamaha_Items = document.getElementById("msamaha_Items").value;
		var Date_From = document.getElementById("Date_From").value;
		var Date_To = document.getElementById("Date_To").value;

		if(Date_From != '' && Date_From != null && Date_To != '' && Date_To != null){
			document.getElementById('Details_Area').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';
			if(window.XMLHttpRequest){
	            myObjectFilter = new XMLHttpRequest();
	        }else if(window.ActiveXObject){
	            myObjectFilter = new ActiveXObject('Micrsoft.XMLHTTP');
	            myObjectFilter.overrideMimeType('text/xml');
	        }
	        
	        myObjectFilter.onreadystatechange = function (){
	            dataFilter = myObjectFilter.responseText;
	            if (myObjectFilter.readyState == 4) {
	                document.getElementById("Details_Area").innerHTML = dataFilter;
	            }
	        }; //specify name of function that will handle server response........
	        
	        myObjectFilter.open('GET','Exemption_Cost_Analysis_Summary_Report.php?Item_Category_ID='+Item_Category_ID+'&msamaha_Items='+msamaha_Items+'&Date_From='+Date_From+'&Date_To='+Date_To,true);
	        myObjectFilter.send();
	    }else{
	    	if (Date_From == '' || Date_From == null) {
                document.getElementById("Date_From").style = 'border: 3px solid red; text-align: center;';
            }else{
            	document.getElementById("Date_From").style = 'border: 2px solid black; text-align: center;';
            }

	    	if (Date_To == '' || Date_To == null) {
                document.getElementById("Date_To").style = 'border: 3px solid red; text-align: center;';
            }else{
            	document.getElementById("Date_To").style = 'border: 2px solid black; text-align: center;';
            }
	    }
	}
</script>

<script type="text/javascript">
	function Preview_Report(){
		var Item_Category_ID = document.getElementById("Item_Category_ID").value;
		var msamaha_Items = document.getElementById("msamaha_Items").value;
		var Date_From = document.getElementById("Date_From").value;
		var Date_To = document.getElementById("Date_To").value;

		if(Date_From != '' && Date_From != null && Date_To != '' && Date_To != null){
			document.getElementById("Date_From").style = 'border: 2px solid black; text-align: center;';
			document.getElementById("Date_To").style = 'border: 2px solid black; text-align: center;';
			window.open('exemptioncostanalysis.php?Item_Category_ID='+Item_Category_ID+'&msamaha_Items='+msamaha_Items+'&Date_From='+Date_From+'&Date_To='+Date_To+'&ExemptionCostAnalysis=ExemptionCostAnalysisThisPage','_blank');
	    }else{
	    	if (Date_From == '' || Date_From == null) {
                document.getElementById("Date_From").style = 'border: 3px solid red; text-align: center;';
            }else{
            	document.getElementById("Date_From").style = 'border: 2px solid black; text-align: center;';
            }

	    	if (Date_To == '' || Date_To == null) {
                document.getElementById("Date_To").style = 'border: 3px solid red; text-align: center;';
            }else{
            	document.getElementById("Date_To").style = 'border: 2px solid black; text-align: center;';
            }
	    }
	}
</script>

<script type="text/javascript">
	function Preview_Summary_Report(){
		var Item_Category_ID = document.getElementById("Item_Category_ID").value;
		var msamaha_Items = document.getElementById("msamaha_Items").value;
		var Date_From = document.getElementById("Date_From").value;
		var Date_To = document.getElementById("Date_To").value;

		if(Date_From != '' && Date_From != null && Date_To != '' && Date_To != null){
			document.getElementById("Date_From").style = 'border: 2px solid black; text-align: center;';
			document.getElementById("Date_To").style = 'border: 2px solid black; text-align: center;';
			window.open('exemptioncostanalysissummary.php?Item_Category_ID='+Item_Category_ID+'&msamaha_Items='+msamaha_Items+'&Date_From='+Date_From+'&Date_To='+Date_To+'&ExemptionCostAnalysisSummary=ExemptionCostAnalysisSummaryThisPage','_blank');
	    }else{
	    	if (Date_From == '' || Date_From == null) {
                document.getElementById("Date_From").style = 'border: 3px solid red; text-align: center;';
            }else{
            	document.getElementById("Date_From").style = 'border: 2px solid black; text-align: center;';
            }

	    	if (Date_To == '' || Date_To == null) {
                document.getElementById("Date_To").style = 'border: 3px solid red; text-align: center;';
            }else{
            	document.getElementById("Date_To").style = 'border: 2px solid black; text-align: center;';
            }
	    }
	}
</script>

<script type="text/javascript">
	function Add_Category(Item_Category_ID){
		if(window.XMLHttpRequest){
            myObjectExempAdd = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObjectExempAdd = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectExempAdd.overrideMimeType('text/xml');
        }
        
        myObjectExempAdd.onreadystatechange = function (){
            dataExempAdd = myObjectExempAdd.responseText;
            if (myObjectExempAdd.readyState == 4) {
                Refresh_List_Of_Categories();
                Refresh_Selected_Categories();
                Refresh_Main_List();
            }
        }; //specify name of function that will handle server response........
        
        myObjectExempAdd.open('GET','Exemption_Add_Selected_Category.php?Item_Category_ID='+Item_Category_ID,true);
        myObjectExempAdd.send();
	}
</script>


<script type="text/javascript">
	function Remove_Category(Exemption_Category_ID){
		if(window.XMLHttpRequest){
            myObjectExempRemove = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObjectExempRemove = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectExempRemove.overrideMimeType('text/xml');
        }
        
        myObjectExempRemove.onreadystatechange = function (){
            dataExemp = myObjectExempRemove.responseText;
            if (myObjectExempRemove.readyState == 4) {
                Refresh_List_Of_Categories();
                Refresh_Selected_Categories();
                Refresh_Main_List();
            }
        }; //specify name of function that will handle server response........
        
        myObjectExempRemove.open('GET','Exemption_Remove_Category.php?Exemption_Category_ID='+Exemption_Category_ID,true);
        myObjectExempRemove.send();
	}
</script>

<script type="text/javascript">
	function Refresh_List_Of_Categories(){
		if(window.XMLHttpRequest){
            myObjectExempRef1 = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObjectExempRef1 = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectExempRef1.overrideMimeType('text/xml');
        }
        
        myObjectExempRef1.onreadystatechange = function (){
            dataExemp1 = myObjectExempRef1.responseText;
            if (myObjectExempRef1.readyState == 4) {
            	document.getElementById("List_Of_Categories_Area").innerHTML = dataExemp1;
            }
        }; //specify name of function that will handle server response........
        
        myObjectExempRef1.open('GET','Refresh_List_Of_Categories.php',true);
        myObjectExempRef1.send();
	}
</script>


<script type="text/javascript">
	function Refresh_Selected_Categories(){
		if(window.XMLHttpRequest){
            myObjectExempRef2 = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObjectExempRef2 = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectExempRef2.overrideMimeType('text/xml');
        }
        
        myObjectExempRef2.onreadystatechange = function (){
            dataExemp2 = myObjectExempRef2.responseText;
            if (myObjectExempRef2.readyState == 4) {
            	document.getElementById("Selected_Categories_Area").innerHTML = dataExemp2;
            }
        }; //specify name of function that will handle server response........
        
        myObjectExempRef2.open('GET','Refresh_Selected_Categories.php',true);
        myObjectExempRef2.send();
	}
</script>

<script type="text/javascript">
	function Refresh_Main_List(){
		if(window.XMLHttpRequest){
            myObjectMain = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObjectMain = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectMain.overrideMimeType('text/xml');
        }
        
        myObjectMain.onreadystatechange = function (){
            dataMain = myObjectMain.responseText;
            if (myObjectMain.readyState == 4) {
            	document.getElementById("Item_Category_ID").innerHTML = dataMain;
            }
        }; //specify name of function that will handle server response........
        
        myObjectMain.open('GET','Exemption_Refresh_Main_List.php',true);
        myObjectMain.send();
	}
</script>

<script src="css/jquery.js"></script>
<script src="css/jquery.datetimepicker.js"></script>
<script>
	$('#Date_From').datetimepicker({
		dayOfWeekStart : 1,
		lang:'en',
	});
	$('#Date_From').datetimepicker({value:'',step:01});
	$('#Date_To').datetimepicker({
		dayOfWeekStart : 1,
		lang:'en',
	});
	$('#Date_To').datetimepicker({value:'',step:01});
</script>


<script type="text/javascript">
	function Exemption_Configuration_Dialog(){
		if(window.XMLHttpRequest) {
	  		myObject = new XMLHttpRequest();
      	}else if(window.ActiveXObject){
	  		myObject = new ActiveXObject('Micrsoft.XMLHTTP');
	  		myObject.overrideMimeType('text/xml');
      	}
  
      	myObject.onreadystatechange = function (){
	  		data265 = myObject.responseText;
		  	if (myObject.readyState == 4) {
		      	document.getElementById('Exemption_Categories').innerHTML = data265;
		      	$("#Exemption_Categories").dialog("open");
		  	}
      	}; //specify name of function that will handle server response........
      	myObject.open('GET','Exemption_Configuration_Dialog.php',true);
      	myObject.send();
	}
</script>

<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>
<script src="script.js"></script>
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
<script src="script.responsive.js"></script>
<script>

    $(document).ready(function () {
        $('select').select2();
      	$("#Exemption_Categories").dialog({ autoOpen: false, width:950,height:450, title:'CATEGORIES',modal: true});
    })
</script>
<link rel="stylesheet" href="css/select2.min.css" media="screen">
<script src="js/select2.min.js"></script>
<?php
    include("./includes/footer.php");
?>