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
<a href="Procedure.php" class="art-button-green">BACK</a>
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
                <td>
                    <a href="procedure_diagnosis_configuration_setup.php">
                        <button style='width: 100%; height: 100%'>
                            Procedure Diagnosis Configuration setup
                        </button>
                    </a>
                </td>
            </tr>
            <tr>
                <td>
                    <a href="procedure_items_configuration_setup.php">
                        <button style='width: 100%; height: 100%'>
                            Procedure Items Configuration setup
                        </button>
                    </a>
                </td>
            </tr>
            <tr>
                <td>
                    <a href="procedure_setup.php">
                        <button style='width: 100%; height: 100%'>
                            Procedure setup
                        </button>
                    </a>
                </td>
            </tr>
        </table>
    </center>
</fieldset>
<br />
<?php
include("./includes/footer.php");
?>