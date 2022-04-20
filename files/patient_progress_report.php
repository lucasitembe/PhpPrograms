<?php include ("./includes/header.php");
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Reception_Works'])) {
        if ($_SESSION['userinfo']['Reception_Works'] != 'yes') {
            header("Location: ./index.php?InvalidPrivilege=yes");
        }
    } else {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
};
echo '
<script type=\'text/javascript\'>
    function access_Denied(){
   alert("Access Denied");
   document.location = "./index.php";
    }
</script>
';
if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Reception_Works'] == 'yes') {;
        echo '    <a href=\'receptionReports.php?Section=Reception&ReceptionReportThisPage\' class=\'art-button\'>
        BACK
    </a>
';
    }
};
echo '
';
$Today_Date = mysqli_query($conn, "select now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $Today = $row['today'];
}
$today_start_date = mysqli_query($conn, "select cast(current_date() as datetime)");
while ($start_dt_row = mysqli_fetch_assoc($today_start_date)) {
    $today_start = $start_dt_row['cast(current_date() as datetime)'];
};
echo '

<br/><br/>
<center>
    <table width=100% style="background-color:white;">
	<tr>
	    <td style=\'text-align: right;\' width=7%>Sponsor</td>
	    <td width="10%">
	    	<select name="Sponsor_ID" id="Sponsor_ID">
	    		<option selected="selected" value="0">All</option>
	    	';
$select = mysqli_query($conn, "select Sponsor_ID, Guarantor_Name from tbl_sponsor order by Guarantor_Name") or die(mysqli_error($conn));
$num = mysqli_num_rows($select);
if ($num > 0) {
    while ($data = mysqli_fetch_array($select)) {;
        echo '	    			<option value="';
        echo $data['Sponsor_ID'];;
        echo '">';
        echo $data['Guarantor_Name'];;
        echo '</option>
	    	';
    }
};
echo '	    	</select>
	    </td>
	    <td style=\'text-align: right;\' width=7%><b>Start Date</b></td>
	    <td width=12%>
			<input type=\'text\' name=\'Start_Date\' id=\'Date_From\' style=\'text-align: center;\' placeholder=\'Start Date\' readonly=\'readonly\' value=\'';
echo $today_start;;
echo '\'>
	    </td>
	    <td style=\'text-align: right;\' width=7%><b>End Date</b></td>
	    <td width=12%>
			<input type=\'text\' name=\'Start_Date\' id=\'Date_To\' style=\'text-align: center;\' placeholder=\'End Date\' readonly=\'readonly\' value=\'';
echo $Today;;
echo '\'>
	    </td>
        <td width=20%>
	    	<input type=\'text\' id=\'Search_Value\' name=\'Search_Value\' style=\'text-align: center;\' placeholder=\'~~~~ Enter Patient Name ~~~~\' autocomplete=\'off\' onkeyup=\'Get_Filtered_Patients_Filter()\'>
	    </td>
	    <td style=\'text-align: right;\' width=9%><input name=\'Filter\' type=\'button\' value=\'FILTER\' class=\'art-button\' onclick=\'Get_Filtered_Patients()\'></td>
	</tr>
    </table>
	<br>
	<table width=100% style="background-color:white;">
	<tr>
	<td style=\'text-align: right; color:white;background:green; text-align:center;\' width=50%><b>Patients Within Limit Time</b></td>
	<td style=\'text-align: right; color:white;background:red;text-align:center;\' width=50%><b>Patient Out of Limit Time</b></td>
	</tr>
    </table>
</center>

<script type="text/javascript">
    function Select_Employee(){
        $("#List_Of_Employee").dialog("open");
    }
</script>

<script type="text/javascript">
	function Get_Selected_Employee(Employee_Name,Employee_ID){
		document.getElementById("Employee_Name").value = Employee_Name;
		document.getElementById("Employee_ID").value = Employee_ID;
		document.getElementById("Emp_Name").value = \'\';
		Search_Employees();
		$("#List_Of_Employee").dialog("close");
	}
</script>

<script type="text/javascript">
	function Search_Employees(){
		var Emp_Name = document.getElementById("Emp_Name").value;
		if(window.XMLHttpRequest){
            myObject_Search_Employee = new XMLHttpRequest();
        }else if(window.ActiveXObject){
            myObject_Search_Employee = new ActiveXObject(\'Micrsoft.XMLHTTP\');
            myObject_Search_Employee.overrideMimeType(\'text/xml\');
        }

        myObject_Search_Employee.onreadystatechange = function (){
            data = myObject_Search_Employee.responseText;
            if (myObject_Search_Employee.readyState == 4) {
                document.getElementById(\'Employee_Area\').innerHTML = data;
            }
        }; //specify name of function that will handle server response........
        myObject_Search_Employee.open(\'GET\',\'Reception_Search_Employees.php?Emp_Name=\'+Emp_Name,true);
        myObject_Search_Employee.send();
	}
</script>

<script src="css/jquery.js"></script>
    <script src="css/jquery.datetimepicker.js"></script>
    <script>
    $(\'#Date_From\').datetimepicker({
    dayOfWeekStart : 1,
    lang:\'en\',
    //startDate:    \'now\'
    });
    $(\'#Date_From\').datetimepicker({value:\'\',step:10});
    $(\'#Date_To\').datetimepicker({
    dayOfWeekStart : 1,
    lang:\'en\',
    //startDate:\'now\'
    });
    $(\'#Date_To\').datetimepicker({value:\'\',step:10});
    </script>
    <!--End datetimepicker-->
<br/>
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

<fieldset style=\'overflow-y:scroll; height:400px; background-color:white;\' id=\'Patients_Fieldset_List\';>

	<legend  align="right" style="background-color:#006400;color:white;padding:5px;"><b>LIST OF CHECKED IN PATIENTS</b></legend>
	<table width=100%>
	<tr><td colspan="10"><hr></td></tr>
	    <tr>
            <td width=5%><b>SN</b></td>
            <td width=20%><b>PATIENT NAME</b></td>
            <td width=10%><b>PATIENT#</b></td>
            <td width=10%><b>SPONSOR</b></td>
            <td width=10%><b>PHONE#</b></td>
            <td width=10%><b>CHECKED-IN DATE</b></td>
            <td width=15%><b>EMPLOYEE</b></td>
            <td width=30%><b>WAITING TIME</b></td>
        </tr>
		<tr><td colspan="10"><hr></td></tr>
	    ';;
echo '	</table>
</fieldset>

<script>
    function Get_Filtered_Patients(){

	var Date_From = document.getElementById("Date_From").value;
	var Date_To = document.getElementById("Date_To").value;
	var Search_Value = document.getElementById("Search_Value").value;
	var Sponsor_ID = document.getElementById("Sponsor_ID").value;
	var Sponsor_ID = document.getElementById("Sponsor_ID").value;

	if(window.XMLHttpRequest) {
	    My_Object_Filter_Patient = new XMLHttpRequest();
	}else if(window.ActiveXObject){
	    My_Object_Filter_Patient = new ActiveXObject(\'Micrsoft.XMLHTTP\');
	    My_Object_Filter_Patient.overrideMimeType(\'text/xml\');
	}

    document.getElementById("Patients_Fieldset_List").innerHTML = "<div align=\'center\' style=\' id=\'progressStatus\'><img src=\'images/ajax-loader_1.gif\' width=\' style=\'border-color:white\'></div>";
	My_Object_Filter_Patient.onreadystatechange = function (){
	    data6 = My_Object_Filter_Patient.responseText;
	    if (My_Object_Filter_Patient.readyState == 4) {
		document.getElementById(\'Patients_Fieldset_List\').innerHTML = data6;
	    }
	}; //specify name of function that will handle server response........

	if (Search_Value) {
	    My_Object_Filter_Patient.open(\'GET\',\'Get_Patient_Progress_Report.php?Date_From=\'+Date_From+\'&Date_To=\'+Date_To+\'&Search_Value=\'+Search_Value+\'&Sponsor_ID=\'+Sponsor_ID,true);
	}else{
	    My_Object_Filter_Patient.open(\'GET\',\'Get_Patient_Progress_Report.php?Date_From=\'+Date_From+\'&Date_To=\'+Date_To+\'&Sponsor_ID=\'+Sponsor_ID,true);
	}

	My_Object_Filter_Patient.send();
    }

    Get_Filtered_Patients();
</script>


<script>
    function Get_Filtered_Patients_Filter(){

	var Date_From = document.getElementById("Date_From").value;
	var Date_To = document.getElementById("Date_To").value;
	var Search_Value = document.getElementById("Search_Value").value;
	var Sponsor_ID = document.getElementById("Sponsor_ID").value;

	if(window.XMLHttpRequest) {
	    My_Object_Filter_Patient = new XMLHttpRequest();
	}else if(window.ActiveXObject){
	    My_Object_Filter_Patient = new ActiveXObject(\'Micrsoft.XMLHTTP\');
	    My_Object_Filter_Patient.overrideMimeType(\'text/xml\');
	}

	My_Object_Filter_Patient.onreadystatechange = function (){
	    data6 = My_Object_Filter_Patient.responseText;
	    if (My_Object_Filter_Patient.readyState == 4) {
		document.getElementById(\'Patients_Fieldset_List\').innerHTML = data6;
	    }
	}; //specify name of function that will handle server response........

	My_Object_Filter_Patient.open(\'GET\',\'Get_Patient_Progress_Report.php?Date_From=\'+Date_From+\'&Date_To=\'+Date_To+\'&Search_Value=\'+Search_Value+\'&Sponsor_ID=\'+Sponsor_ID,true);
	My_Object_Filter_Patient.send();
    }
</script>

<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
<script>
   $(document).ready(function(){
      $("#List_Of_Employee").dialog({ autoOpen: false, width:\'30%\',height:350, title:\'EMPLOYEES LIST\',modal: true});
   });
</script>
';
include ("./includes/footer.php");