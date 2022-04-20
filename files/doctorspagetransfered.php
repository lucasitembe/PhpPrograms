<?php
include("./includes/header.php");
include("./includes/connection.php");

if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'])) {
        if ($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'] != 'yes') {
            header("Location: ./index.php?InvalidPrivilege=yes");
        }
    } else {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}

if (isset($_SESSION['userinfo'])) {
   
    if (isset($_GET['section']) && $_GET['section'] == 'reception') {
           
            echo "<a href='transferdoctor.php?ReceptionWork=ReceptionWorkThisPage' class='art-button-green'>
				 BACK
			  </a>";
        } elseif (isset($_GET['Section']) && $_GET['Section'] == 'DocInpatient') {
           
            echo "<a href='transferdoctor.php' class='art-button-green'>BACK</a>";
            
        } elseif (isset($_GET['section']) && $_GET['section'] == 'revenuecenter') {
             
            echo "<a href='transferdoctor.php?RevenueCenterWork=RevenueCenterWorkThisPage' class='art-button-green'>BACK</a>";
        } else {
            
            ?>
            <a href='transferdoctor.php?DoctorsWorksPage=DoctorsWorksThisPage' class='art-button-green'>
                BACK
            </a>
        <?php
        }
}
?>


<script language="javascript" type="text/javascript">
    function searchFilter() {

        var date_From = document.getElementById('date_From').value;
        var date_To = document.getElementById('date_To').value;
        //alert(date_From);
        //alert(date_To);
        document.getElementById('Search_Iframe').innerHTML =
                "<iframe width='100%' height=380px src='doctorspagetransfered_iframe.php?date_From=" + date_From + "&date_To=" + date_To + "'></iframe>";
    }
</script>

<script language="javascript" type="text/javascript">
    function searchPatient(){
        var Patient_Name=document.getElementById('Search_Patient').value;
        var Patient_Name_No=document.getElementById('Search_Patient_ID').value;
        document.getElementById('Search_Iframe').innerHTML = "<iframe width='100%' height=320px src='doctorspagetransfered_iframe.php?Patient_Name="+Patient_Name+"&Patient_Name_No="+Patient_Name_No+"'></iframe>";
    }
</script>
<br>
<br>
<center>
    <table width="100%">
		<tr>
            <td>
                <input type='text' name='Search_Patient' id='Search_Patient' onclick='searchPatient()' onkeyup='searchPatient()' placeholder='~~~~~~~~~~~~~~~~~~~~~~~~~~Enter Patient Name~~~~~~~~~~~~~~~~~~~~~~~~~~'>
             
            </td>
            
            <td>
                 <input type='text' name='Search_Patient_ID' id='Search_Patient_ID' onclick='searchPatient()' onkeyup='searchPatient()' placeholder='~~~~~~~~~~~~~~~~~~~~~~~~~~Enter Patient No~~~~~~~~~~~~~~~~~~~~~~~~~~'>
            </td>
        </tr>
        <tr>
            <td style="text-align:center"><b>FROM</b></td>
            <td style="text-align: center"><b>TO</b></td>
        </tr>
        <tr>
            <td >
                <input type='text' autocomplete="off" id='date_From' style="text-align: center">
            </td>
            <td>
                <input type="text" id="date_To" autocomplete="off" style="text-align: center" >
            </td>
            <td>
                <input type="button" value="FILTER" class="art-button-green" onclick='searchFilter()'>
            </td>
        </tr>

    </table>
    <br>
</center>
<fieldset>  
    <legend align=center><b>PATIENT-DOCTOR TRANSFER LIST</b></legend>
    <center>
        <table width=100% border=1>
            <tr>
                <td id='Search_Iframe'>
                    <iframe width='100%' height=380px src='doctorspagetransfered_iframe.php' ></iframe>
                </td>
            </tr>
        </table>
    </center>
</fieldset><br/>


<script src="css/jquery.js"></script>
<script src="css/jquery.datetimepicker.js"></script>
<script>
                    $('#date_From').datetimepicker({
                        dayOfWeekStart: 1,
                        lang: 'en',
                        startDate: 'now'
                    });
                    $('#date_From').datetimepicker({value: '', step: 30});
                    $('#date_To').datetimepicker({
                        dayOfWeekStart: 1,
                        lang: 'en',
                        startDate: 'now'
                    });
                    $('#date_To').datetimepicker({value: '', step: 30});
</script>
<!--End datetimepicker-->
<?php
include("./includes/footer.php");
?>