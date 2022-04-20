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

 
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Setup_And_Configuration'] == 'yes'){ 
                echo "<a href='configureapprovallevel.php?ConfigureApprovalLevel=ConfigureApprovalLevelThisPage' class='art-button-green'>CONFIGURE APPROVAL LEVEL</a>";
        }
    }
 
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Setup_And_Configuration'] == 'yes'){ 
                echo "<a href='procurementconfiguration.php?ProcurementConfiguration=ProcurementConfigurationThisPage' class='art-button-green'>BACK</a>";
        }
    }
?>
<br/><br/>
<center>
    <table width=60%> 
	<tr>
	    <td width=50%>
			<input type='text' name='Employee_Name' id='Employee_Name' placeholder='~~~~~~~~ Search Employee Name ~~~~~~~~' style='text-align: center;' oninput='Search_Patients_Filter()' onkeypress='Search_Patients_Filter()' onkeyup='Search_Patients_Filter()'>
	    </td>
	    <td style="text-align: right;" width="20%">
	    	Approval Levels
	    </td>
	    <td width="30%">
	    	<select name="Approval_Title" id="Approval_Title" onchange="Search_Patients();">
	    		<option selected="selected">~~~ Select Approval Title ~~~
		    	<?php
		    		$select = mysqli_query($conn,"select * from tbl_approval_level order by Approval_Title") or die(mysqli_error($conn));
		    		$num = mysqli_num_rows($select);
		    		if($num > 0){
		    			while ($row = mysqli_fetch_array($select)) {
		    	?>
		    			<option><?php echo $row['Approval_Title']; ?></option>
		    	<?php
		    			}
		    		}
		    	?>
	    	</select>
	    </td>
	</tr>
    </table>
</center>
<br/>
<fieldset style='overflow-y: scroll; height: 380px;' id='List_Of_Employees_Area'>
    <legend align='right'><b>ASSIGN APPROVAL LEVELS</b></legend>
	<table width=100%>
		<tr>
			<td width="5%"><b>SN</b></td>
			<td><b>EMPLOYEE NAME</b></td>
			<td width="14%"><b>EMPLOYEE TYPE</b></td>
			<td width="14%"><b>EMPLOYEE TITLE</b></td>
			<td width="14%"><b>DEPARTMENT NAME</b></td>
			<td width="7%"><b>ACTION</b></td>
		</tr>
    </table>
</fieldset>

<script>
    function Search_Patients() {
	var Approval_Title = document.getElementById("Approval_Title").value;
	Employee_Name = document.getElementById("Employee_Name").value = '';
        if(window.XMLHttpRequest) {
                muObjectSearch = new XMLHttpRequest();
        }else if(window.ActiveXObject){ 
                muObjectSearch = new ActiveXObject('Micrsoft.XMLHTTP');
                muObjectSearch.overrideMimeType('text/xml');
        }
                
        muObjectSearch.onreadystatechange = function (){
                data = muObjectSearch.responseText;
                if (muObjectSearch.readyState == 4) {
                    document.getElementById('List_Of_Employees_Area').innerHTML = data;
                }
        }; //specify name of function that will handle server response........
                
        muObjectSearch.open('GET','Search_Patients_Via_Titles.php?Approval_Title='+Approval_Title,true);
        muObjectSearch.send();
    }
</script>

<script>
    function Search_Patients_Filter() {
    	var Employee_Name = document.getElementById("Employee_Name").value;
		var Approval_Title = document.getElementById("Approval_Title").value;

        if(window.XMLHttpRequest) {
			muObjectSearch = new XMLHttpRequest();
        }else if(window.ActiveXObject){ 
            muObjectSearch = new ActiveXObject('Micrsoft.XMLHTTP');
            muObjectSearch.overrideMimeType('text/xml');
        }
                
        muObjectSearch.onreadystatechange = function (){
                data = muObjectSearch.responseText;
                if (muObjectSearch.readyState == 4) {
                    document.getElementById('List_Of_Employees_Area').innerHTML = data;
                }
        }; //specify name of function that will handle server response........
                
        muObjectSearch.open('GET','Search_Patients_Via_Titles.php?Approval_Title='+Approval_Title+'&Employee_Name='+Employee_Name,true);
        muObjectSearch.send();
    }
</script>


<script type="text/javascript">
	function Assign_Approval_Level(Employee_ID,Approval_Level){
		if(window.XMLHttpRequest) {
			muObjectAssign = new XMLHttpRequest();
        }else if(window.ActiveXObject){ 
            muObjectAssign = new ActiveXObject('Micrsoft.XMLHTTP');
            muObjectAssign.overrideMimeType('text/xml');
        }
                
        muObjectAssign.onreadystatechange = function (){
                data1212 = muObjectAssign.responseText;
                if (muObjectAssign.readyState == 4) {
                    var feedback = data1212;
                    if(feedback == 'yes'){
                    	document.getElementById(Employee_ID).innerHTML = '<input type="button" name="Remove_Level" id="Remove_Level" value="REMOVE" class="art-button-green" onclick=\'Remove_Approval_Level("'+Employee_ID+'","'+Approval_Level+'")\'>';
                    }else{
                    	alert("Process Fail!!. Please try again");
                    }
                }
        }; //specify name of function that will handle server response........
                
        muObjectAssign.open('GET','Assign_Approval_Level.php?Employee_ID='+Employee_ID+'&Approval_Level='+Approval_Level,true);
        muObjectAssign.send();
	}
</script>

<script type="text/javascript">
	function Remove_Approval_Level(Employee_ID,Approval_Level){
		if(window.XMLHttpRequest) {
			muObjectRemove = new XMLHttpRequest();
        }else if(window.ActiveXObject){ 
            muObjectRemove = new ActiveXObject('Micrsoft.XMLHTTP');
            muObjectRemove.overrideMimeType('text/xml');
        }
                
        muObjectRemove.onreadystatechange = function (){
                data1212 = muObjectRemove.responseText;
                if (muObjectRemove.readyState == 4) {
                    var feedback = data1212;
                    if(feedback == 'yes'){
                    	document.getElementById(Employee_ID).innerHTML = '<input type="button" name="Update" id="Update" value="ASSIGN" class="art-button-green" onclick=\'Assign_Approval_Level("'+Employee_ID+'","'+Approval_Level+'")\'>';
                    }else{
                    	alert("Process Fail!!. Please try again");
                    }
                }
        }; //specify name of function that will handle server response........
                
        muObjectRemove.open('GET','Remove_Approval_Level.php?Employee_ID='+Employee_ID,true);
        muObjectRemove.send();
	}
</script>

<?php
    include('./includes/footer.php');
?>