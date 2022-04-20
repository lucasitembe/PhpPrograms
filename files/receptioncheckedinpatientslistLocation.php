<?php
    include("./includes/header.php");
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

<script type='text/javascript'>
    function access_Denied(){ 
   alert("Access Denied");
   document.location = "./index.php";
    }
</script>
<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Reception_Works'] == 'yes'){ 
?>
    <a href='./receptionworkspage.php?ReceptionWork=ReceptionWorkThisPage' class='art-button-green'>
        BACK
    </a>
<?php  } } ?>

<?php
    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $Today = $row['today'];
    }
?>


<br/><br/>
<center>
    <table width=100% style="background-color:white;">
	<tr>
	    <td width="7%" style="text-align: right;">
	    	<b>Employee Name</b>
	    </td>
	    <td>
	    	<input type="text" readonly="readonly" value="All" name="Employee_Name" id="Employee_Name">
	    	<input type="hidden" name="Employee_ID" id="Employee_ID" value="0">
	    </td>
	    <td width="10%">
	    	<input type="button" name="Select_Employee" id="Select_Employee" value="SELECT EMPLOYEE" class="art-button-green" onclick="Select_Employee()">
	    </td>
	    <td style='text-align: right;' width=7%>Sponsor</td>
	    <td width="10%">
	    	<select name="Sponsor_ID" id="Sponsor_ID">
	    		<option selected="selected" value="0">All</option>
	    	<?php
	    		$select = mysqli_query($conn,"select Sponsor_ID, Guarantor_Name from tbl_sponsor order by Guarantor_Name") or die(mysqli_error($conn));
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
	    <td style='text-align: right;' width=7%><b>Start Date</b></td>
	    <td width=12%>
			<input type='text' name='Start_Date' id='Date_From' style='text-align: center;' placeholder='Start Date' readonly='readonly' value='<?php echo $Today; ?>'>
	    </td>
	    <td style='text-align: right;' width=7%><b>End Date</b></td>
	    <td width=12%>
			<input type='text' name='Start_Date' id='Date_To' style='text-align: center;' placeholder='End Date' readonly='readonly' value='<?php echo $Today; ?>'>
	    </td>
	    <td style='text-align: right;' width=9%><input name='Filter' type='button' value='FILTER' class='art-button-green' onclick='Get_Filtered_Patients()'></td>
	</tr>
	<tr>
	    <td colspan="3">
	    	<input type='text' id='Search_Value' name='Search_Value' style='text-align: center;' placeholder='~~~~ ~~~~ ~~~~ Enter Patient Name ~~~~ ~~~~ ~~~~ ' autocomplete='off' oninput='Get_Filtered_Patients_Filter()'>
	    </td>
	    <td colspan="4">
	    	<input type='text' id='Patient_Number' name='Patient_Number' style='text-align: center;' placeholder='~~~~ ~~~~ ~~~~ Enter Patient Number ~~~~ ~~~~ ~~~~ ' autocomplete='off' oninput='Get_Filtered_Patients_Filter()'>
	    </td>
	    <td style='text-align: right;'>
	    	<b>Check In Type</b>
	    </td>
	    <td>
	    	<select name="Type_Of_Check_In" id="Type_Of_Check_In">
	    		<option selected="selected">All</option>
	    		<option>Afresh</option>
	    		<option>Continuous</option>
	    	</select>
	    </td>
	    <!-- <td>
	    	<input type="button" name="Preview_Report" id="" value="PREVIEW REPORT" class="art-button-green">
	    </td> -->
	</tr>
    </table>
</center>

<div id="List_Of_Employee">
    <center>
        <table width="100%">
            <tr>
                <td>
                    <input type="text" name="Emp_Name" id="Emp_Name" placeholder="~~~ ~~~ Enter Employee Name ~~~ ~~~" autocomplete="off" style="text-align: center;" onkeyup="Search_Employees()" oninput="Search_Employees()">
                </td>
            </tr>
            <tr>
                <td>
                    <fieldset style='overflow-y: scroll; height: 200px; background-color: white;' id='Employee_Area'>
                        <table width="100%">
                        <?php
                            $counter = 0;
                            $get_employees = mysqli_query($conn,"select Employee_ID, Employee_Name from tbl_employee where Account_Status = 'active' and Employee_Name <> 'CRDB' order by Employee_Name limit 100") or die(mysqli_error($conn));
                            $emp_num = mysqli_num_rows($get_employees);
                        ?>
							<tr>
                                <td style='text-align: right;'>
                                    <label onclick="Get_Selected_Employee('All',0)"><?php echo ++$counter; ?></label>
                                </td>
                                <td>
                                    <label onclick="Get_Selected_Employee('All',0)">&nbsp;&nbsp;&nbsp;&nbsp;All</label>
                                </td>
                            </tr>
                        <?php
                            if($emp_num > 0){
                                while ($data = mysqli_fetch_array($get_employees)) {
                        ?>
                                <tr>
                                    <td style='text-align: right;'>
                                        <label onclick="Get_Selected_Employee('<?php echo $data['Employee_Name']; ?>',<?php echo $data['Employee_ID']; ?>)"><?php echo ++$counter; ?></label>
                                    </td>
                                    <td>
                                        <label onclick="Get_Selected_Employee('<?php echo $data['Employee_Name']; ?>',<?php echo $data['Employee_ID']; ?>)">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo strtoupper($data['Employee_Name']); ?></label>
                                    </td>
                                </tr>
                        <?php
                                }
                            }
                        ?>
                        </table>
                    </fieldset>
                </td>
            </tr>
        </table>
    </center>
</div>
<script type="text/javascript">
    function Select_Employee(){
        $("#List_Of_Employee").dialog("open");
    }
</script>

<script type="text/javascript">
	function Get_Selected_Employee(Employee_Name,Employee_ID){
		document.getElementById("Employee_Name").value = Employee_Name;
		document.getElementById("Employee_ID").value = Employee_ID;
		document.getElementById("Emp_Name").value = '';
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
            myObject_Search_Employee = new ActiveXObject('Micrsoft.XMLHTTP');
            myObject_Search_Employee.overrideMimeType('text/xml');
        }

        myObject_Search_Employee.onreadystatechange = function (){
            data = myObject_Search_Employee.responseText;
            if (myObject_Search_Employee.readyState == 4) {
                document.getElementById('Employee_Area').innerHTML = data;
            }
        }; //specify name of function that will handle server response........
        myObject_Search_Employee.open('GET','Reception_Search_Employees.php?Emp_Name='+Emp_Name,true);
        myObject_Search_Employee.send();
	}	
</script>

<script src="css/jquery.js"></script>
    <script src="css/jquery.datetimepicker.js"></script>
    <script>
    $('#Date_From').datetimepicker({
    dayOfWeekStart : 1,
    lang:'en',
    //startDate:    'now'
    });
    $('#Date_From').datetimepicker({value:'',step:10});
    $('#Date_To').datetimepicker({
    dayOfWeekStart : 1,
    lang:'en',
    //startDate:'now'
    });
    $('#Date_To').datetimepicker({value:'',step:10});
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

<fieldset style='overflow-y:scroll; height:400px; background-color:white;' id='Patients_Fieldset_List';> 
    
	<legend  align="right" style="background-color:#006400;color:white;padding:5px;"><b>PATIENTS LOCATION</b></legend>
	<table width=100% id='location_tbl' class="display" border='1'>
	<tr><td colspan="9"><hr></td></tr>
	    <tr>
            <td width=5%><b>SN</b></td>
            <td><b>PATIENT NAME</b></td>
            <td width=10%><b>PATIENT#</b></td>
            <td width=10%><b>LABORATORY</b></td>
            <td width=10%><b>RADIOLOGY</b></td>
            <td width=15%><b>PHARMACY</b></td>
            <td width=20%><b>PROCEDURE</b></td>
            <td><b>SURGERY</b></td>
           <!---- <td width="9%"><b>AUTHORIZATION NO</b></td>--->
        </tr>
		<tr><td colspan="9"><hr></td></tr>	
	    <?php
		/*$temp = 0;
		//get list of checked in patients
		$select = mysqli_query($conn,"select pr.Registration_ID, pr.Patient_Name, pr.Phone_Number, ci.Type_Of_Check_In, ci.Check_In_Date_And_Time, emp.Employee_Name
					from tbl_check_in ci, tbl_employee emp, tbl_patient_registration pr where
					pr.Registration_ID = ci.Registration_ID and
					ci.Employee_ID = emp.Employee_ID and
					ci.Check_In_Date between '$Today' and '$Today'
					order by ci.Check_In_Date_And_Time desc") or die(mysqli_error($conn));
		$num = mysqli_num_rows($select);
		if($num > 0){
		   while($row = mysqli_fetch_array($select)){
			echo "<tr>
		    <td>".++$temp."</td>
			    <td>".$row['Patient_Name']."</td>
			    <td>".$row['Registration_ID']."</td>
			    <td>".$row['Phone_Number']."</td>
			    <td>".$row['Type_Of_Check_In']."</td>
			    <td>".$row['Check_In_Date_And_Time']."</td>
			    <td>".$row['Employee_Name']."</td>
			</tr>";
		    }
		}*/
	  ?>
	</table>
</fieldset>

<script>
    function Get_Filtered_Patients(){
	
	var Date_From = document.getElementById("Date_From").value;
	var Date_To = document.getElementById("Date_To").value;
	var Search_Value = document.getElementById("Search_Value").value;
	var Sponsor_ID = document.getElementById("Sponsor_ID").value;
	var Sponsor_ID = document.getElementById("Sponsor_ID").value;
	var Type_Of_Check_In = document.getElementById("Type_Of_Check_In").value;
	var Employee_ID = document.getElementById("Employee_ID").value;
	
	if(window.XMLHttpRequest) {
	    My_Object_Filter_Patient = new XMLHttpRequest();
	}else if(window.ActiveXObject){
	    My_Object_Filter_Patient = new ActiveXObject('Micrsoft.XMLHTTP');
	    My_Object_Filter_Patient.overrideMimeType('text/xml');
	}
	
	My_Object_Filter_Patient.onreadystatechange = function (){
	    data6 = My_Object_Filter_Patient.responseText;
	    if (My_Object_Filter_Patient.readyState == 4) {
		document.getElementById('Patients_Fieldset_List').innerHTML = data6;
	    }
	}; //specify name of function that will handle server response........
	
	if (Search_Value) {
	    My_Object_Filter_Patient.open('GET','Get_Checked_Patients_List_location.php?Date_From='+Date_From+'&Date_To='+Date_To+'&Search_Value='+Search_Value+'&Sponsor_ID='+Sponsor_ID+'&Type_Of_Check_In='+Type_Of_Check_In+'&Employee_ID='+Employee_ID,true);
	}else{
	    My_Object_Filter_Patient.open('GET','Get_Checked_Patients_List_location.php?Date_From='+Date_From+'&Date_To='+Date_To+'&Sponsor_ID='+Sponsor_ID+'&Type_Of_Check_In='+Type_Of_Check_In+'&Employee_ID='+Employee_ID,true);
	}
	
	My_Object_Filter_Patient.send();
    }
</script>


<script>
    function Get_Filtered_Patients_Filter(){
	
	var Date_From = document.getElementById("Date_From").value;
	var Date_To = document.getElementById("Date_To").value;
	var Search_Value = document.getElementById("Search_Value").value;
	var Sponsor_ID = document.getElementById("Sponsor_ID").value;
	var Type_Of_Check_In = document.getElementById("Type_Of_Check_In").value;
	var Employee_ID = document.getElementById("Employee_ID").value;
	
	if(window.XMLHttpRequest) {
	    My_Object_Filter_Patient = new XMLHttpRequest();
	}else if(window.ActiveXObject){
	    My_Object_Filter_Patient = new ActiveXObject('Micrsoft.XMLHTTP');
	    My_Object_Filter_Patient.overrideMimeType('text/xml');
	}
	
	My_Object_Filter_Patient.onreadystatechange = function (){
	    data6 = My_Object_Filter_Patient.responseText;
	    if (My_Object_Filter_Patient.readyState == 4) {
		document.getElementById('Patients_Fieldset_List').innerHTML = data6;
	    }
	}; //specify name of function that will handle server response........
		
	My_Object_Filter_Patient.open('GET','Get_Checked_Patients_List_location.php?Date_From='+Date_From+'&Date_To='+Date_To+'&Search_Value='+Search_Value+'&Sponsor_ID='+Sponsor_ID+'&Type_Of_Check_In='+Type_Of_Check_In+'&Employee_ID='+Employee_ID,true);
	My_Object_Filter_Patient.send();
    }
</script>

<link rel="stylesheet" href="media/css/jquery.dataTables.css" media="screen">
<link rel="stylesheet" href="media/themes/smoothness/dataTables.jqueryui.css" media="screen">

<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>
<script src="media/js/jquery.dataTables.js"></script>
 
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
<script>
   $(document).ready(function(){
      $("#List_Of_Employee").dialog({ autoOpen: false, width:'30%',height:350, title:'EMPLOYEES LIST',modal: true});
	   $('#location_tbl').dataTable({
            "bJQueryUI": true,
			});
   });
</script>
<?php
    include("./includes/footer.php");
?>