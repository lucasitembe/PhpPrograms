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
?>

<a href='msamahareports.php?MsamahaReports=MsamahaReportsThisForm' class='art-button-green'>BACK</a>
<br/><br/>
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
<fieldset> 
    <table width="100%">
        <tr>
            <td width="15%"><input type="text" autocomplete="off" readonly="readonly" style='text-align: center;' id="Date_From" placeholder="Start Date"></td>
            <td width="15%"><input type="text" autocomplete="off" readonly="readonly" style='text-align: center;' id="Date_To" placeholder="End Date"></td>
            <td width="20%">
                <select id="msamaha_aina">
                    <option value="0">~~~ Aina ya Msamaha ~~~</option>
                    <?php
                        $Query= mysqli_query($conn,"SELECT * FROM tbl_msamaha_items");
                        while ($row=  mysqli_fetch_assoc($Query)){
                            echo '<option value="'.$row['msamaha_Items'].'">'.$row['msamaha_aina'].'</option>';
                        }
                    ?>
                </select>
            </td>
            <td width="9%">
                <select id="gender">
                    <option value="">~~~ Jinsi ~~~</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>
            </td>
            <td width="20%">
                <select id="employee_ID">
                    <option value="">~~~ Employee Name ~~~</option>
                    <?php
                        $Query= mysqli_query($conn,"SELECT * FROM tbl_employee where Account_Status = 'active' ORDER BY Employee_Name");
                         while ($row=  mysqli_fetch_assoc($Query)){
                            echo '<option value="'.$row['Employee_ID'].'">'.strtoupper($row['Employee_Name']).'</option>'; 
                       }
                    ?>
                </select>
            </td>
            <td width="7%" style="text-align: right;"><input type="button" value="FILTER" class="art-button-green" onclick="filterPatient()"></td>
        </tr>
        <tr>
            <td colspan="2">
                <input type='text' name='Search_Patient' style='text-align: center;' id='Search_Patient' oninput="Search_Patient()" placeholder='~~~ Enter Patient Name ~~~'>
            </td>
            <td colspan="2">
                <input type='text' name='Patient_Number' style='text-align: center;' id='Patient_Number' oninput="Search_Patient_Via_Number()" placeholder='~~~ Enter Patient Number ~~~'>
            </td>
            <td>
                <input type='text' name='Patient_Phone' style='text-align: center;' id='Patient_Phone' oninput="Search_Patient_Via_Phone()" placeholder='~~~ Enter Patient Phone Number ~~~'>
            </td>
            <td style="text-align: right;">
                <input type="button" name="Report_Button" id="Report_Button" value="REPORT" class="art-button-green" onclick="Preview_Report()">
            </td>
        </tr>
    </table>
</fieldset>

<fieldset style='overflow-y: scroll; height: 400px; background-color: white;' id='Msamaha_Area'>
    <legend align="left"><b id="dateRange">WAGONJWA WA MSAMAHA</span></b></legend>
    <table width="100%">
        <tr><td colspan="15"><hr></td></tr>
        <tr>
            <td width="4%"><b>SN</b></td>
            <td><b>Date</b></td>
            <td><b>Jina la mgojwa</b></td>
            <td><b>Umri</b></td>
            <td><b>Jinsia</b></td> 
            <td><b>Nambari ya simu</b></td>
            <td><b>Aina ya Msamaha</b></td>
            <td><b>Jina la mtu anayependekeza Msamaha</b></td>
            <td><b>Jina la Balozi</b></td>
            <td><b>Region</b></td>
            <td><b>District</b></td>
            <td><b>Ward</b></td>
            <td><b>kiwango cha elimu</b></td>        
            <td><b>Kazi ya mke/mlezi</b></td>
            <td><b>Prepared By</b></td>
       </tr>
       <tr><td colspan="15"><hr></td></tr>
    </table>
</fieldset>
<?php
    include("./includes/footer.php");
?>

<script type="text/javascript">
    function Preview_Report(){
        var Date_From = document.getElementById('Date_From').value;
        var Date_To = document.getElementById('Date_To').value;
        var msamaha_aina = document.getElementById('msamaha_aina').value;
        var jinsi = document.getElementById('gender').value;
        var employee_ID = document.getElementById('employee_ID').value;
        var Patient_Name = document.getElementById('Search_Patient').value;
        var Patient_Number = document.getElementById('Patient_Number').value;
        var Patient_Phone = document.getElementById('Patient_Phone').value;

        if(Date_From != null && Date_From != '' && Date_To != null && Date_To != ''){
            window.open("msamahareport.php?Patient_Name="+Patient_Name+"&Patient_Number="+Patient_Number+"&Patient_Phone="+Patient_Phone+"&msamaha_aina="+msamaha_aina+"&Date_To="+Date_To+"&Date_From="+Date_From+"&jinsi="+jinsi+"&employee_ID="+employee_ID,"_blank");
        }else{
            if(Date_From == '' || Date_From == null){
                document.getElementById("Date_From").style = 'border: 3px solid red; text-align: center;';
            }else{
                document.getElementById("Date_From").style = 'border: 2px solid black; text-align: center;';
            }
            if(Date_To =='' || Date_To == null){
                document.getElementById("Date_To").style = 'border: 3px solid red; text-align: center;';
            }else{
                document.getElementById("Date_To").style = 'border: 2px solid black; text-align: center;';
            }
        }
    }
</script>

<script>
    function filterPatient() {
        var Date_From = document.getElementById('Date_From').value;
        var Date_To = document.getElementById('Date_To').value;
        var msamaha_aina = document.getElementById('msamaha_aina').value;
        var jinsi = document.getElementById('gender').value;
        var employee_ID = document.getElementById('employee_ID').value;

        if(Date_From != null && Date_From != '' && Date_To != null && Date_To != ''){
            document.getElementById("Date_From").style = 'border: 2px solid black; text-align: center;';
            document.getElementById("Date_To").style = 'border: 2px solid black; text-align: center;';
            document.getElementById('Msamaha_Area').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';
            
            if(window.XMLHttpRequest) {
                myObjectFilter = new XMLHttpRequest();
            }else if(window.ActiveXObject){
                myObjectFilter = new ActiveXObject('Micrsoft.XMLHTTP');
                myObjectFilter.overrideMimeType('text/xml');
            }
            
            myObjectFilter.onreadystatechange = function (){
                dataFilter = myObjectFilter.responseText;
                if (myObjectFilter.readyState == 4) {
                    document.getElementById('Msamaha_Area').innerHTML = dataFilter;
                }
            }; //specify name of function that will handle server response........
            myObjectFilter.open('GET','Msamaha_Filter_Patient_List.php?Date_From='+Date_From+'&Date_To='+Date_To+'&msamaha_aina='+msamaha_aina+'&jinsi='+jinsi+'&employee_ID='+employee_ID,true);
            myObjectFilter.send();
        }else{
            if(Date_From == '' || Date_From == null){
                document.getElementById("Date_From").style = 'border: 3px solid red; text-align: center;';
            }else{
                document.getElementById("Date_From").style = 'border: 2px solid black; text-align: center;';
            }
            if(Date_To =='' || Date_To == null){
                document.getElementById("Date_To").style = 'border: 3px solid red; text-align: center;';
            }else{
                document.getElementById("Date_To").style = 'border: 2px solid black; text-align: center;';
            }
        }
    }
</script>

<script type="text/javascript">
    function Search_Patient(){
        var Date_From = document.getElementById('Date_From').value;
        var Date_To = document.getElementById('Date_To').value;
        var msamaha_aina = document.getElementById('msamaha_aina').value;
        var jinsi = document.getElementById('gender').value;
        var employee_ID = document.getElementById('employee_ID').value;
        var Patient_Name = document.getElementById("Search_Patient").value;
        document.getElementById("Patient_Number").value = '';
        document.getElementById("Patient_Phone").value = '';

        if(Date_From != null && Date_From != '' && Date_To != null && Date_To != ''){
            document.getElementById("Date_From").style = 'border: 2px solid black; text-align: center;';
            document.getElementById("Date_To").style = 'border: 2px solid black; text-align: center;';
            document.getElementById('Msamaha_Area').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';
            
            if(window.XMLHttpRequest) {
                myObjectSearch1 = new XMLHttpRequest();
            }else if(window.ActiveXObject){
                myObjectSearch1 = new ActiveXObject('Micrsoft.XMLHTTP');
                myObjectSearch1.overrideMimeType('text/xml');
            }
            
            myObjectSearch1.onreadystatechange = function (){
                dataSearch1 = myObjectSearch1.responseText;
                if (myObjectSearch1.readyState == 4) {
                    document.getElementById('Msamaha_Area').innerHTML = dataSearch1;
                }
            }; //specify name of function that will handle server response........
            myObjectSearch1.open('GET','Msamaha_Filter_Patient_List.php?Patient_Name='+Patient_Name+'&Date_From='+Date_From+'&Date_To='+Date_To+'&msamaha_aina='+msamaha_aina+'&jinsi='+jinsi+'&employee_ID='+employee_ID,true);
            myObjectSearch1.send();
        }else{
            if(Date_From == '' || Date_From == null){
                document.getElementById("Date_From").style = 'border: 3px solid red; text-align: center;';
            }else{
                document.getElementById("Date_From").style = 'border: 2px solid black; text-align: center;';
            }
            if(Date_To =='' || Date_To == null){
                document.getElementById("Date_To").style = 'border: 3px solid red; text-align: center;';
            }else{
                document.getElementById("Date_To").style = 'border: 2px solid black; text-align: center;';
            }
        }
    }
</script>


<script type="text/javascript">
    function Search_Patient_Via_Number(){
        var Date_From = document.getElementById('Date_From').value;
        var Date_To = document.getElementById('Date_To').value;
        var msamaha_aina = document.getElementById('msamaha_aina').value;
        var jinsi = document.getElementById('gender').value;
        var employee_ID = document.getElementById('employee_ID').value;
        var Patient_Number = document.getElementById("Patient_Number").value;
        document.getElementById("Search_Patient").value = '';
        document.getElementById("Patient_Phone").value = '';
        
        if(Date_From != null && Date_From != '' && Date_To != null && Date_To != ''){
            document.getElementById("Date_From").style = 'border: 2px solid black; text-align: center;';
            document.getElementById("Date_To").style = 'border: 2px solid black; text-align: center;';
            document.getElementById('Msamaha_Area').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';
            
            if(window.XMLHttpRequest) {
                myObjectSearch1 = new XMLHttpRequest();
            }else if(window.ActiveXObject){
                myObjectSearch1 = new ActiveXObject('Micrsoft.XMLHTTP');
                myObjectSearch1.overrideMimeType('text/xml');
            }
            
            myObjectSearch1.onreadystatechange = function (){
                dataSearch1 = myObjectSearch1.responseText;
                if (myObjectSearch1.readyState == 4) {
                    document.getElementById('Msamaha_Area').innerHTML = dataSearch1;
                }
            }; //specify name of function that will handle server response........
            myObjectSearch1.open('GET','Msamaha_Filter_Patient_List.php?Patient_Number='+Patient_Number+'&Date_From='+Date_From+'&Date_To='+Date_To+'&msamaha_aina='+msamaha_aina+'&jinsi='+jinsi+'&employee_ID='+employee_ID,true);
            myObjectSearch1.send();
        }else{
            if(Date_From == '' || Date_From == null){
                document.getElementById("Date_From").style = 'border: 3px solid red; text-align: center;';
            }else{
                document.getElementById("Date_From").style = 'border: 2px solid black; text-align: center;';
            }
            if(Date_To =='' || Date_To == null){
                document.getElementById("Date_To").style = 'border: 3px solid red; text-align: center;';
            }else{
                document.getElementById("Date_To").style = 'border: 2px solid black; text-align: center;';
            }
        }
    }
</script>


<script type="text/javascript">
    function Search_Patient_Via_Phone(){
        var Date_From = document.getElementById('Date_From').value;
        var Date_To = document.getElementById('Date_To').value;
        var msamaha_aina = document.getElementById('msamaha_aina').value;
        var jinsi = document.getElementById('gender').value;
        var employee_ID = document.getElementById('employee_ID').value;
        var Patient_Phone = document.getElementById("Patient_Phone").value;
        document.getElementById("Search_Patient").value = '';
        document.getElementById("Patient_Number").value = '';
        
        if(Date_From != null && Date_From != '' && Date_To != null && Date_To != ''){
            document.getElementById("Date_From").style = 'border: 2px solid black; text-align: center;';
            document.getElementById("Date_To").style = 'border: 2px solid black; text-align: center;';
            document.getElementById('Msamaha_Area').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';
            
            if(window.XMLHttpRequest) {
                myObjectSearch1 = new XMLHttpRequest();
            }else if(window.ActiveXObject){
                myObjectSearch1 = new ActiveXObject('Micrsoft.XMLHTTP');
                myObjectSearch1.overrideMimeType('text/xml');
            }
            
            myObjectSearch1.onreadystatechange = function (){
                dataSearch1 = myObjectSearch1.responseText;
                if (myObjectSearch1.readyState == 4) {
                    document.getElementById('Msamaha_Area').innerHTML = dataSearch1;
                }
            }; //specify name of function that will handle server response........
            myObjectSearch1.open('GET','Msamaha_Filter_Patient_List.php?Patient_Phone='+Patient_Phone+'&Date_From='+Date_From+'&Date_To='+Date_To+'&msamaha_aina='+msamaha_aina+'&jinsi='+jinsi+'&employee_ID='+employee_ID,true);
            myObjectSearch1.send();
        }else{
            if(Date_From == '' || Date_From == null){
                document.getElementById("Date_From").style = 'border: 3px solid red; text-align: center;';
            }else{
                document.getElementById("Date_From").style = 'border: 2px solid black; text-align: center;';
            }
            if(Date_To =='' || Date_To == null){
                document.getElementById("Date_To").style = 'border: 3px solid red; text-align: center;';
            }else{
                document.getElementById("Date_To").style = 'border: 2px solid black; text-align: center;';
            }
        }
    }
</script>

<script type="text/javascript">
    $(document).ready(function () {
        $('#consultpatients').DataTable({
            "bJQueryUI": true
        });
        $('#Date_From').datetimepicker({
            dayOfWeekStart: 1,
            lang: 'en',
        });
        $('#Date_From').datetimepicker({value: '', step: 01});
        $('#Date_To').datetimepicker({
            dayOfWeekStart: 1,
            lang: 'en',
        });
        $('#Date_To').datetimepicker({value: '', step: 01});
    });
</script>

<script>
    $('#printmsamaha').click(function(){
      var msamaha_aina=$('#msamaha_aina').val();
      var Date_To=$('#Date_To').val();
      var Date_From=$('#Date_From').val();
      var Search_Product=$('#Search_Patient').val();
      var jinsi=$('#gender').val();
      var employee_ID=$('#employee_ID').val();
       window.open('PrintMsamahaList.php?Patient_Name='+Search_Product+'&msamaha_aina='+msamaha_aina+'&Date_To='+Date_To+'&Date_From='+Date_From+'&jinsi='+jinsi+'&employee_ID='+employee_ID+'');  
    });
</script>

<link rel="stylesheet" type="text/css" href="css/jquery.datetimepicker.css"/>
<link rel="stylesheet" href="media/css/jquery.dataTables.css" media="screen">
<link rel="stylesheet" href="media/themes/smoothness/dataTables.jqueryui.css" media="screen">
<script src="media/js/jquery.js" type="text/javascript"></script>
<script src="media/js/jquery.dataTables.js" type="text/javascript"></script>
<script src="css/jquery.datetimepicker.js" type="text/javascript"></script>
<script src="css/jquery-ui.js"></script>

<script>
    $(document).ready(function () {
        $('select').select2();
    })
</script>
<link rel="stylesheet" href="css/select2.min.css" media="screen">
<script src="js/select2.min.js"></script>