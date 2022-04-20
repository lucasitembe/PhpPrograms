<?php
include("./includes/header.php");
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (!isset($_SESSION['Procedure_Supervisor'])) {
    header("Location: ./deptsupervisorauthentication.php?SessionCategory=Procedure&InvalidSupervisorAuthentication=yes");
}

if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Procedure_Works'])) {
        if ($_SESSION['userinfo']['Procedure_Works'] != 'yes') {
            header("Location: ./index.php?InvalidPrivilege=yes");
        }
    } else {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
    
//    echo '< pre>';
//    print_r($_SESSION);
//    exit;
?>
<a href='deptsupervisorauthentication.php?SessionCategory=Procedure&InvalidSupervisorAuthentication=yes' class='art-button-green'>CHANGE DEPARTMENT</a>
<a href="index.php?DashboardThisPage=ThisPage" class="art-button-green">BACK</a>
<script type='text/javascript'>
    function access_Denied() {
        alert("Access Denied");
        document.location = "./index.php";
    }
</script>

<br /><br />
<br />
<fieldset>
    <legend align=center><b>PROCEDURE WORKS</b></legend>
    <center>
        <table width=60%>

            <tr>
                <td style='text-align: center; height:40px;width:100%'>

                    <a href='searchpatientprocedurelist.php'><button style='width: 100%; height:40px'>Procedure Patients</button></a>
                </td>

            </tr>
            <tr>
                <td style='text-align: center; height:40px;width:100%'>

                    <a href='procedureconcertform.php'><button style='width: 100%; height:40px'>Procedure Consent(Online) Form</button></a>
                </td>

            </tr>
            <tr>
                <td style='text-align: center; height:40px;width:100%'>

                    <a href='patient_with_concert_form2.php'><button style='width: 100%; height:40px'>Patients With Online Concert Form</button></a>
                </td>

            </tr>

            <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <a href='procedurelistreport.php'><button style='width: 100%; height:40px'>Reports</button></a>
                </td>

            </tr>

            <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <a href='proceduresetupsone.php'><button style='width: 100%; height:40px'>Procedure Setups</button></a>
                </td>

            </tr>
        </table>
    </center>
</fieldset>
<br />
<?php
include("./includes/footer.php");
?>