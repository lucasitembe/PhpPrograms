<style>
    #help {
        background-color: #2b8f8a;
        padding: 5px;
        color: white;
        border-radius: 5px;
        font-weight: bold;
        cursor: pointer;
    }
</style>

<?php
include("./includes/header.php");
include("./button_configuration.php");
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}

if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Pharmacy'])) {
        if ($_SESSION['userinfo']['Pharmacy'] != 'yes') {
            header("Location: ./index.php?InvalidPrivilege=yes");
        } else {
            @session_start();
            if (!isset($_SESSION['Pharmacy_Supervisor'])) {
                header("Location: ./pharmacysupervisorauthentication.php?InvalidSupervisorAuthentication=yes");
            }
        }
    } else {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}

echo "<a href='pharmacysupervisorauthentication.php?ChangeWorkingLocation=ChangeWorkingLocationThisPage' class='art-button-green'>CHANGE DEPARTMENT</a>";
echo "<span id='help' style='float:right' onclick='openHelp()'> HELP </span>";
?>

<?php

$select_admission_patient = mysqli_query($conn, "SELECT Registration_ID FROM tbl_admission WHERE (Admission_Status='Admitted') AND ward_room_id <> 0 AND DATE(Admission_Date_Time)=CURDATE()");


while ($row = mysqli_fetch_assoc($select_admission_patient)) {
    $Registration_ID = $row['Registration_ID'];

    $payment_cache_ID_result = mysqli_query($conn, "SELECT Payment_Cache_ID FROM tbl_payment_cache WHERE Registration_ID='$Registration_ID' ORDER BY Payment_Cache_ID DESC LIMIT 1");
    if (mysqli_num_rows($payment_cache_ID_result) > 0) {
        while ($p_cahe_id_rows = mysqli_fetch_assoc($payment_cache_ID_result)) {
            $Payment_Cache_ID = $p_cahe_id_rows['Payment_Cache_ID'];
            $Payment_Cache_ID2 = $Payment_Cache_ID;
            $select_transaction_type = mysqli_query($conn, "SELECT Transaction_Type FROM tbl_item_list_cache WHERE Payment_Cache_ID='$Payment_Cache_ID'") or die(mysqli_error($conn));
            if (mysqli_num_rows($select_transaction_type) > 0) {
                while ($row2 = mysqli_fetch_assoc($select_transaction_type)) {
                    $Transaction_Type = $row2['Transaction_Type'];
                    // $result = mysqli_query($conn, "UPDATE tbl_payment_cache SET Billing_Type='Inpatient $Transaction_Type' WHERE Payment_Cache_ID='$Payment_Cache_ID2'");
                }
            }
        }
    }
}
?>
<script type='text/javascript'>
    function access_Denied() {
        alert("Access Denied");
        document.location = "./index.php";
    }
</script>

<br /><br />
<fieldset>
<legend align=center><b>DISPENSING WORKS</b></legend>

    <center>
        <table width=35% style="background-color: #eee;">
            <tr>
                <td style='text-align: center; height: 40px; width: 33%;' colspan="2">
                    <?php if ($_SESSION['userinfo']['Pharmacy'] == 'yes') { ?>
                        <a href='pharmacyworkspage_new.php'>
                            <button style="width: 100%; height: 100%;">
                                Dispensing 
                            </button>
                        </a>
                    <?php } else { ?>
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Dispensing
                        </button>
                    <?php } ?>
                </td>
            </tr>
            
            <tr>
                <td style='text-align: center; height: 40px; width: 33%;' colspan="2">
                    <?php if ($_SESSION['userinfo']['Pharmacy'] == 'yes') { ?>
                        <a href='itemsconfiguration.php?Section=Pharmacy&ItemsConfiguration=ItemsConfigurationThisPage'>
                            <button style='width: 100%; height: 100%'>
                                Item Configuration
                            </button>
                        </a>
                    <?php } else { ?>
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Item Configuration
                        </button>
                    <?php } ?>
                </td>
            </tr>

            <!-- new return feature -->
            <tr>
                <td style='text-align: center; height: 40px; width: 33%;' colspan="">
                    <?php if ($_SESSION['userinfo']['Pharmacy'] == 'yes') { ?>
                        <a href='list_of_inpatient_patient.php'>
                            <button style='width: 100%; height: 100%'>
                                Return Dispensed Item To Pharmacy
                            </button>
                        </a>
                    <?php } else { ?>
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Return Dispensed Item To Pharmacy
                        </button>
                    <?php } ?>
                </td>
            </tr>

            <tr>
                <td style='text-align: center; height: 40px; width: 33%;' colspan="2">
                    <?php if ($_SESSION['userinfo']['Pharmacy'] == 'yes') { ?>
                        <a href='pharmacyreportspage.php?PhrmacyReports=PharmacyReportsThisPage'>
                            <button style='width: 100%; height: 100%'>
                                Reports
                            </button>
                        </a>
                    <?php } else { ?>
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Reports
                        </button>
                    <?php } ?>
                </td>
            </tr>
        </table>
        </center>
        <br>
</fieldset>

<div id="helpContent"></div>

<script>
    function openHelp() {
        $.get(
            'pharmacy_help_content.php', {}, (data) => {
                $("#helpContent").dialog({
                    autoOpen: false,
                    width: '30%',
                    title: 'eHMS 4.0 PHARMACY HELP',
                    modal: true,
                    height: 400
                });
                $("#helpContent").html(data);
                $("#helpContent").dialog("open");
            }
        );
    }
</script>


<?php
include("./includes/footer.php");
?>
