<?php
include("./includes/header.php");
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Msamaha_Works'])) {
        if ($_SESSION['userinfo']['Msamaha_Works'] != 'yes') {
            header("Location: ./index.php?InvalidPrivilege=yes");
        }
    } else {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
?>
<a href='index.php?RegisterPatient=RegisterPatientThisPage' class='art-button-green'>
    BACK
</a>

<script type='text/javascript'>
    function access_Denied() {
        alert("Access Denied");
        document.location = "./index.php";
    }
</script>

<br /><br /><br /><br /><br />
<fieldset>
    <legend align="center"><b>HEALTH SECTOR</b></legend>
    <center>
        <table width=60%>
            <!-- <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if ($_SESSION['userinfo']['Free_Items_Works'] == 'yes') { ?>
                        <a href='free_items_setup.php'>
                            <button style='width: 100%; height: 100%'>
                                Free Items Configuration
                            </button>
                        </a>
                    <?php } else { ?>
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Free Items configuration
                        </button>

                    <?php } ?>
                </td>
            </tr> -->

            <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <a href='unconsultedpatients.php?HealthUnConsulted=HealthUnConsultedThisPage'>
                        <button style='width: 100%; height: 100%'>
                            UnConsulted Patients
                        </button>
                    </a>
                </td>
            </tr>

            <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <a href='consultedpatients.php?HealthUnConsulted=HealthUnConsultedThisPage'>
                        <button style='width: 100%; height: 100%'>
                            Consulted Patients
                        </button>
                    </a>
                </td>
            </tr>
            <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <a href='healthItemConfiguration.php?HealthUnConsulted=HealthUnConsultedThisPage'>
                        <button style='width: 100%; height: 100%'>
                            Health Items Configurations
                        </button>
                    </a>
                </td>
            </tr>

            <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <a href=''>
                        <button style='width: 100%; height: 100%'>
                            Reports
                        </button>
                    </a>
                </td>
            </tr>
        </table>
    </center>
</fieldset><br />
<?php
include("./includes/footer.php");
?>
