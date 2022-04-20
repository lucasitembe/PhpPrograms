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
<a href='msamahareports.php?MsamahaReports=MsamahaReportsThisForm' class='art-button-green'>BACK</a>
<br/><br/>
<fieldset> 
    <table width="100%">
        <tr>
            <td width="%" style="text-align: right;">Start Date</td>
            <td width="17%"><input type="text" autocomplete="off" style='text-align: center;' id="Date_From" placeholder="~~~ ~~ Start Date ~~ ~~~" readonly="readonly"></td>
            <td width="%" style="text-align: right;">End Date</td>
            <td width="17%"><input type="text" autocomplete="off" style='text-align: center;' id="Date_To" placeholder="~~~ ~~ End Date ~~ ~~~" readonly="readonly"></td>
            <td>
                <select id="employee_ID">
                    <option value="">~~~ Employee Name ~~~</option>
                    <?php
                     $Query= mysqli_query($conn,"SELECT * FROM tbl_employee ORDER BY Employee_Name");
                         while ($row=  mysqli_fetch_assoc($Query)){
                            echo '<option value="'.$row['Employee_ID'].'">'.$row['Employee_Name'].'</option>'; 
                       }
                    ?>
                </select>
            </td>
            <td width="5%"><input type="button" value="FILTER" class="art-button-green" onclick="filterPatient()"></td>
            <td width="2%">&nbsp;</td>
            <td width="9%"><input type="button" id="Report_Button" class="art-button-green" value="PREVIEW REPORT" onclick="Preview_Report()"></td>
        </tr>
    </table>
</fieldset>

<fieldset style='overflow-y: scroll; height: 400px; background-color: white;' id='Msamaha_Area'>
    <legend align="left" ><b id="dateRange">WAGONJWA WA MSAMAHA</span></b></legend>
    <table width = "100%">
        <tr><td colspan="5"><hr></tr>
        <tr>
            <td width="5%"><b>SN</b></td>
            <td><b>AINA YA MSAMAHA</b></td>
            <td width="15%" style="text-align: center;"><b>MALE</b></td>
            <td width="15%" style="text-align: center;"><b>FEMALE</b></td>
            <td width="15%" style="text-align: center;"><b>TOTAL</b></td>
        </tr>
        <tr><td colspan="5"><hr></tr>
    </table>
</fieldset>
<?php
    include("./includes/footer.php");
?>

<script>
    function filterPatient() {
        var Date_From = document.getElementById('Date_From').value;
        var Date_To = document.getElementById('Date_To').value;
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
            myObjectFilter.open('GET','Msamaha_Filter_Patient_List_General.php?Date_From='+Date_From+'&Date_To='+Date_To+'&employee_ID='+employee_ID,true);
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
    function Preview_Report(){
        var Date_From = document.getElementById('Date_From').value;
        var Date_To = document.getElementById('Date_To').value;
        var employee_ID = document.getElementById('employee_ID').value;

        if(Date_From != null && Date_From != '' && Date_To != null && Date_To != ''){
            document.getElementById("Date_From").style = 'border: 2px solid black; text-align: center;';
            document.getElementById("Date_To").style = 'border: 2px solid black; text-align: center;';
            window.open("generalmsamahareport.php?Date_From="+Date_From+"&Date_To="+Date_To+"&employee_ID="+employee_ID+"&GeneralMsamahaReport=GeneralMsamahaReportThisPage","_blank");
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
            //startDate:    'now'
        });
        $('#Date_From').datetimepicker({value: '', step: 01});
        $('#Date_To').datetimepicker({
            dayOfWeekStart: 1,
            lang: 'en',
            //startDate:'now'
        });
        $('#Date_To').datetimepicker({value: '', step: 01});
    });
</script>

<script>
    $('#printmsamaha').click(function(){
      var Date_To=$('#Date_To').val();
      var Date_From=$('#Date_From').val();
      var employee_ID=$('#employee_ID').val();
       window.open('PrintMsamahaListGeneral.php?Date_To='+Date_To+'&Date_From='+Date_From+'&employee_ID='+employee_ID+'');  
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