<?php include ("./includes/header.php");
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Dialysis_Works'])) {
        if ($_SESSION['userinfo']['Dialysis_Works'] != 'yes') {
            header("Location: ./index.php?InvalidPrivilege=yes");
        } else {
            @session_start();
            if (!isset($_SESSION['Dialysis_Supervisor'])) {
                header("Location: ./deptsupervisorauthentication.php?SessionCategory=Dialysis&InvalidSupervisorAuthentication=yes");
            }
        }
    } else {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
};
echo '<a href="dialysisworkspage.php" class="art-button-green">BACK</a>
<script type=\'text/javascript\'>
    function access_Denied() {
        alert("Access Denied");
        document.location = "./index.php";
    }
</script>
<br/><br/><br/><br/><br/><br/>
<fieldset>  
    <legend align=center><b>HEMODIALYSIS CONSENT FORMS</b></legend>
    <center><table width = 60%>
            <tr>
                <td style=\'text-align: center; height: 40px; width: 33%;\'>
                    <a href=\'HEMODIALYSIS_SWAHILI1.pdf\' target="_blank">
                            <button style=\'width: 100%; height: 100%\'>
                               DOWNLOAD HEMODIALYSIS CONSENT (SWAHILI)
                            </button>
                        </a>
                </td>
                <td style=\'text-align: center; height: 40px; width: 33%;\'>
                    <a href=\'HEMODIALYSIS_CONSENT_ENGLISH1.pdf\' target="_blank">
                            <button style=\'width: 100%; height: 100%\'>
                               DOWNLOAD HEMODIALYSIS CONSENT (ENGLISH)
                            </button>
                        </a>
                </td>
            </tr>
        </table>
    </center>
</fieldset><br/>
';
include ("./includes/footer.php");