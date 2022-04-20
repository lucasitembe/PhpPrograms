<?php
    include("./includes/header.php");
    include("./includes/connection.php");
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

    <!-- <a href='Pre_Operative_Patients.php?PreOperativeList=PreOperativeListThisPage' class='art-button-green'>
	REGISTERED
    </a> -->
    <input type="button" value="BACK" onclick="history.go(-1)" class="art-button-green">
    <!-- <a href='preoperativelist_theater_list.php?ItemsConfiguration=ItemConfigurationThisPage&theater=yes' class='art-button-green'>
	LIST OF COMPLETED PRE-OPERATIVE CHECKLIST
    </a> -->





<?php
    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
        
        $Age = $Today - $original_Date; 
    }
?>

<script language="javascript" type="text/javascript">
    function searchPatient(){
		Patient_Name = document.getElementById('Patient_Name').value;
		Patient_Number = document.getElementById('Patient_Number').value;
		Date_From = document.getElementById('Date_From').value;
		Date_To = document.getElementById('Date_To').value;
        document.getElementById('Search_Iframe').innerHTML ="<iframe width='100%' height=380px src='preoperative_checklist_iframe.php?Patient_Name="+Patient_Name+"&Date_To="+Date_To+"&Date_From="+Date_From+"&Patient_Number="+Patient_Number+"'></iframe>";
    }
</script>
<br/><br/>
<center>
        <table width="100%">
            <tr>            
                <td width="20%">
                    <input type="text" autocomplete="off" class="form-control" style='text-align: center;width:100%;display:inline' id="Date_From" placeholder="Start Date"/>

                </td>
                <td width="20%"> 
                   <input type="text" autocomplete="off" class="form-control" style='text-align: center;width:100%;display:inline' id="Date_To" placeholder="End Date"/>
                </td>
                <td width="20%"> 
                    <input type='text' name='Patient_Name' id='Patient_Name' onkeyup='searchPatient()' placeholder='~~~~~~~~~~~~~~~~~~~Search Patient Name~~~~~~~~~~~~~~~~~~~~~~~'>
                </td>
                <td width="20%"> 
                    <input type='text' name='Patient_Number' id='Patient_Number' onkeyup='searchPatient()' placeholder='~~~~~~~~~~~~~~~~~~~Search Patient Number~~~~~~~~~~~~~~~~~~~~~~~'>
                </td>
                <td width="10%">
                    <input type="button" class="art-button-green" value='FILTER' onclick='searchPatient()'>
                </td>
        </tr>
    </table>
</center>
<br>
<fieldset>  
    <legend align=center><b>PATIENT LIST WITH ONLINE PRE-OPERATIVE CHECKLIST</b></legend>
        <center>
            <table width=100% border=1>
                <tr>
		    <td id='Search_Iframe'>
			<iframe width='100%' height=380px src='preoperative_checklist_iframe.php?Patient_Name='></iframe>
		    </td>
		</tr>
            </table>
        </center>
</fieldset><br/>
<?php
    include("./includes/footer.php");
?>

<script type="text/javascript">
    $(document).ready(function () {
        $('#patients-list').DataTable({
            "bJQueryUI": true
        });

        $('#Date_From').datetimepicker({
            dayOfWeekStart: 1,
            changeMonth: true,
            changeYear: true,
            showWeek: true,
            showOtherMonths: true,
            lang: 'en',
            //startDate:    'now'
        });
        $('#Date_From').datetimepicker({value: '', step: 1});
        $('#Date_To').datetimepicker({
            dayOfWeekStart: 1,
            changeMonth: true,
            changeYear: true,
            showWeek: true,
            showOtherMonths: true,
            lang: 'en',
            //startDate:'now'
        });
        $('#Date_To').datetimepicker({value: '', step: 1});
    });
</script>
<link rel="stylesheet" href="media/css/jquery.dataTables.css" media="screen">
<link rel="stylesheet" href="media/themes/smoothness/dataTables.jqueryui.css" media="screen">
<script src="media/js/jquery.js" type="text/javascript"></script>
<script src="media/js/jquery.dataTables.js" type="text/javascript"></script>
<script src="css/jquery.datetimepicker.js" type="text/javascript"></script>
<script src="css/jquery-ui.js"></script>