<?php
    include("./includes/header.php");
    include("./includes/connection.php");
	
	if(!isset($_SESSION['userinfo'])){
		@session_destroy();
		header("Location: ../index.php?InvalidPrivilege=yes");
    }

	if(isset($_SESSION['userinfo'])){
		if(isset($_SESSION['userinfo']['Pharmacy'])){
			if($_SESSION['userinfo']['Pharmacy'] != 'yes'){
				header("Location: ./index.php?InvalidPrivilege=yes");
	    	}
		}else{
	    	header("Location: ./index.php?InvalidPrivilege=yes");
		}
    }else{
		@session_destroy();
	    header("Location: ../index.php?InvalidPrivilege=yes");
    }

    $temp = 0;
	$grand_total_cash = 0;
	$grand_total_credit = 0;
	$grand_total_cancelled = 0;
   
    if(isset($_SESSION['Location'])){
		$Location = $_SESSION['Location'];
    }else{
		$Location = '';
    }
?>

<input type="button" name="Cashiers_Conf" id="Cashiers_Conf" class="art-button-green" value="CASHIERS CONFIGURATION" onclick="Open_Cashier_Configuration()">
<a href='pharmacyreportspage.php?PhrmacyReports=PharmacyReportsThisPage' class='art-button-green'>BACK</a>
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

<br/><br/>
<fieldset>
	<center>
	    <table width=100%>
	        <tr>
	        	<td style="text-align: right;"><b>Sponsor Name</b></td>
				<td>
					<select name="Sponsor_ID" id="Sponsor_ID">
						<option selected="selected" value="0">All</option>
						<?php
							$select = mysqli_query($conn,"select Guarantor_Name, Sponsor_ID from tbl_sponsor order by Guarantor_Name") or die(mysqli_error($conn));
							$num = mysqli_num_rows($select);
							if($num > 0){
								while ($data = mysqli_fetch_array($select)) {
						?>
									<option value="<?php echo $data['Sponsor_ID']; ?>"><?php echo $data['Guarantor_Name']; ?></option>
						<?php
								}
							}
						?>
					</select>
				</td>
	            <td style='text-align: right;'><b>Bill Type</b></td>
	            <td>
	                <select name='Bill_Type' id='Bill_Type' required='required'>
	                    <option selected='selected'>All</option>
	                    <option>Outpatient</option>
	                    <option>Inpatient</option>
	                </select>
	            </td>
	            <td style='text-align: right;'><b>Start Date</b></td>
	            <td><input type='text' name='Start_Date' id='Start_Date' style='text-align: center;' placeholder='Start Date' readonly='readonly' value=''></td>
	            <td style='text-align: right;'><b>End Date</b></td>
	            <td><input type='text' name='Start_Date' id='End_Date' style='text-align: center;' placeholder='End Date' readonly='readonly' value=''></td>
	            <td style='text-align: center;' width=10%><input type='button' name='Filter' id='Filter' value='FILTER' class='art-button-green' onclick='Get_Details()'></td>
	        </tr>
	    </table>
	</center>
</fieldset>
   
<fieldset style='overflow-y: scroll; height: 380px;background-color:white;margin-top:20px;' id='Details_Area'>
	<legend align='right' style="background-color:#006400;color:white;padding:5px;"><b>General Employees Performance ~ Pharmacy</b></legend>
		<table width="100%">
			<tr id='thead'>
			    <td width=5%><b>SN</b></td>
			    <td width='12%'style='text-align: right;'><b>NO OF PATIENTS</b></td>
			    <td width='12%'style='text-align: right;'><b>CASH</b></td>
			    <td width='12%'style='text-align: right;'><b>CREDIT</b></td>
			    <td width='12%'style='text-align: right;'><b>CANCELLED</b></td>
			    <td width='12%'style='text-align: right;'><b>TOTAL</b></td>
			</tr>
			<tr><td colspan="8"><hr></td></tr>
		</table>
</fieldset>
<fieldset>
	<table width="100%">
		<tr>
			<td style="text-align: right;" id="Report_Button_Area">
				<a href="#" class="art-button-green">PREVIEW REPORT</a>
			</td>
		</tr>
	</table>
</fieldset>

<div id="Emp_Configuration">
    <center id="Employees_Area">
        
    </center>
</div>
<script type="text/javascript">
	function Open_Cashier_Configuration(){
		if(window.XMLHttpRequest) {
            myObject = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObject = new ActiveXObject('Micrsoft.XMLHTTP');
            myObject.overrideMimeType('text/xml');
        }

        myObject.onreadystatechange = function (){
            data = myObject.responseText;
            if (myObject.readyState == 4) {
            	document.getElementById('Employees_Area').innerHTML = data;
            	$("#Emp_Configuration").dialog("open");
            }
        }; //specify name of function that will handle server response........
        myObject.open('GET','Cashiers_Configuration.php',true);
        myObject.send();
	}
</script>

<script type="text/javascript">
	function Close_Dialog(){
		$("#Emp_Configuration").dialog("close");
	}
</script>

<script type="text/javascript">
	function Search_Employee(Employee_Type){
		var Unselected_Employee = document.getElementById("Unselected_Employee").value;
		var Selected_Employee = document.getElementById("Selected_Employee").value
		if(window.XMLHttpRequest) {
            myObjectSearch = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObjectSearch = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectSearch.overrideMimeType('text/xml');
        }

        myObjectSearch.onreadystatechange = function (){
            dataSearch = myObjectSearch.responseText;
            if (myObjectSearch.readyState == 4) {
            	if(Employee_Type == 'Unselected'){
					document.getElementById('Unselected_List').innerHTML = dataSearch;
            	}else{
					document.getElementById('Selected_List').innerHTML = dataSearch;
            	}
            	
            	$("#Emp_Configuration").dialog("open");
            }
        }; //specify name of function that will handle server response........
        myObjectSearch.open('GET','Cashiers_Configuration_Search_Employee_Search.php?Employee_Type='+Employee_Type+'&Unselected_Employee='+Unselected_Employee+'&Selected_Employee='+Selected_Employee,true);
        myObjectSearch.send();
	}
</script>

<script type="text/javascript">
	function Remove_Employee(Employee_ID){
		document.getElementById("Unselected_Employee").value = '';
		document.getElementById("Selected_Employee").value = '';

		if(window.XMLHttpRequest) {
            myObjectRemove = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObjectRemove = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectRemove.overrideMimeType('text/xml');
        }

        myObjectRemove.onreadystatechange = function (){
            dataRemove = myObjectRemove.responseText;
            if (myObjectRemove.readyState == 4) {
            	document.getElementById("Selected_List").innerHTML = dataRemove;
            	Refresh_List("Unselected");
            }
        }; //specify name of function that will handle server response........
        myObjectRemove.open('GET','Cashiers_Configuration_Remove_Employee.php?Employee_ID='+Employee_ID,true);
        myObjectRemove.send();
	}
</script>

<script type="text/javascript">
	function Add_Employee(Employee_ID){
		document.getElementById("Unselected_Employee").value = '';
		document.getElementById("Selected_Employee").value = '';
		if(window.XMLHttpRequest) {
            myObjectAdd = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObjectAdd = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectAdd.overrideMimeType('text/xml');
        }

        myObjectAdd.onreadystatechange = function (){
            dataAdd = myObjectAdd.responseText;
            if (myObjectAdd.readyState == 4) {
            	document.getElementById("Selected_List").innerHTML = dataAdd;
            	Refresh_List("Unselected");
            }
        }; //specify name of function that will handle server response........
        myObjectAdd.open('GET','Cashiers_Configuration_Add_Employee.php?Employee_ID='+Employee_ID,true);
        myObjectAdd.send();
	}
</script>

<script type="text/javascript">
	function Refresh_List(Employee_Type){
		if(window.XMLHttpRequest) {
            myObjectRefresh = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObjectRefresh = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectRefresh.overrideMimeType('text/xml');
        }

        myObjectRefresh.onreadystatechange = function (){
            dataRefresh = myObjectRefresh.responseText;
            if (myObjectRefresh.readyState == 4) {
            	if(Employee_Type == 'Selected'){
            		document.getElementById("Selected_List").innerHTML = dataRefresh;
            	}else{
            		document.getElementById("Unselected_List").innerHTML = dataRefresh;
            	}
            }
        }; //specify name of function that will handle server response........
        myObjectRefresh.open('GET','Cashiers_Configuration_Refresh_List.php?Employee_Type='+Employee_Type,true);
        myObjectRefresh.send();
	}
</script>

<script type="text/javascript">
	function Get_Details(){
		var Sponsor_ID = document.getElementById("Sponsor_ID").value;
		var Bill_Type = document.getElementById("Bill_Type").value;
		var Start_Date = document.getElementById("Start_Date").value;
		var End_Date = document.getElementById("End_Date").value;

		if(Start_Date != null && Start_Date != '' && End_Date != null && End_Date != ''){
			document.getElementById("Start_Date").style = 'border: 2px solid black; text-align: center;';
			document.getElementById("End_Date").style = 'border: 2px solid black; text-align: center;';

			if(window.XMLHttpRequest) {
            	myObjectGetDetails = new XMLHttpRequest();
	        }else if(window.ActiveXObject){
	            myObjectGetDetails = new ActiveXObject('Micrsoft.XMLHTTP');
	            myObjectGetDetails.overrideMimeType('text/xml');
	        }

	        myObjectGetDetails.onreadystatechange = function (){
	            dataGetDetails = myObjectGetDetails.responseText;
	            if (myObjectGetDetails.readyState == 4) { 
	            	document.getElementById("Details_Area").innerHTML = dataGetDetails;
	            }
	        }; //specify name of function that will handle server response........
	        myObjectGetDetails.open('GET','Cashiers_Configuration_Get_Details.php?Sponsor_ID='+Sponsor_ID+'&Bill_Type='+Bill_Type+'&Start_Date='+Start_Date+'&End_Date='+End_Date,true);
	        myObjectGetDetails.send();
		}else{
			if(Start_Date=='' || Start_Date == null){
				document.getElementById("Start_Date").style = 'border: 3px solid red; text-align: center;';
			}else{
				document.getElementById("Start_Date").style = 'border: 2px solid black; text-align: center;';
			}
			if(End_Date=='' || End_Date == null){
				document.getElementById("End_Date").style = 'border: 3px solid red; text-align: center;';
			}else{
				document.getElementById("End_Date").style = 'border: 2px solid black; text-align: center;';
			}
		}
	}
</script>

<script src="css/jquery.js"></script>
<script src="css/jquery.datetimepicker.js"></script>
<script>
    $('#Start_Date').datetimepicker({
    dayOfWeekStart : 1,
    lang:'en',
    //startDate:    'now'
    });
    $('#Start_Date').datetimepicker({value:'',step:01});
    $('#End_Date').datetimepicker({
    dayOfWeekStart : 1,
    lang:'en',
    //startDate:'now'
    });
    $('#End_Date').datetimepicker({value:'',step:01});
</script>

<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>
<script src="script.js"></script>
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
<script src="script.responsive.js"></script>
<script>
   $(document).ready(function(){
      $("#Emp_Configuration").dialog({ autoOpen: false, width:'65%',height:500, title:'CASHIERS CONFIGURATION',modal: true});
   });
</script>
<?php
    include("./includes/footer.php");
?>