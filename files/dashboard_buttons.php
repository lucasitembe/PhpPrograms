<?php
include("./includes/connection.php");
include("./includes/header.php");
//include("./includes/cleaninput.php");
//include_once("./functions/items.php");
//require_once('auditor/Auditor.php');

$controlforminput = '';
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}

if (isset($_SESSION['userinfo']['Setup_And_Configuration'])) {
    if ($_SESSION['userinfo']['Setup_And_Configuration'] != 'yes') {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
$employeeid = $_SESSION['userinfo']['Employee_ID'];
if (!isset($_SESSION['superuser'])) {
    @session_destroy();
    header("Location: dashboard_config_login.php");
}
?>
<fieldset style='overflow-y: scroll; height: 360px; background-color: white;' id='Currency_Area'>
    <legend align="right"><b>SYSTEM DASHBOARD CONFIGURATION</b></legend>
    <center>   
        <table width="60%">
            <table class="table table-bordered  table-striped table-hover"> 
                <thead> 
                    <tr> 
                        <th>#</th> 
                        <th>Button Name</th> 
                        <th>Status</th> 
                    </tr> </thead> 
                <tbody> 
                    <?php
                    $sql = "SELECT * FROM tbl_dashboard_buttons";
                    $result = mysqli_query($conn,$sql) or die(mysqli_error($conn));
                    $i = 1;
                    while ($row = mysqli_fetch_array($result)) {
                        ?>
                        <tr>
                            <td><?= $i ?></td>
                            <td><?= $row['button_desc']; ?></td>
                            <td><input onclick="updateDashboardButton(<?= $row['id']; ?>)" value="<?= $row['status']; ?>" id="<?= $row['id']; ?>"
                        <?php echo ($row['status'] == "visible" ? "checked='checked'" : "");
                        ?> type="checkbox"/></td>
                        </tr>
                        <?php
                        $i++;
                    }
                    ?>
                </tbody> 
            </table> 

            <tr><td colspan="2" style="text-align: center;"><b><span style="color: #037CB0;" id="Error_Area" >&nbsp;</span></b></td></tr>

        </table> 
    </center>
</fieldset>
<link rel="stylesheet" href="css/smoothness/jquery-ui-1.10.1.custom.min.css" />
<script src="js/jquery-1.9.1.js"></script>
<script src="js/jquery-ui-1.10.1.custom.min.js"></script>
<link rel="stylesheet" href="css/dialog/zebra_dialog.css" media="screen">
<script src="js/zebra_dialog.js"></script>
<script src="js/ehms_zebra_dialog.js"></script>
<script src="js/functions.js"></script>
<script src="js/numeral/min/numeral.min.js"></script>
<script>
                            function updateDashboardButton(id) {
                                var status = $('#' + id).val();
                                if (status === "visible") {
                                    status = "invisible";
                                } else {
                                    status = "visible";
                                }
                                $.ajax({
                                    type: 'GET',
                                    url: "dashboard_update.php",
                                    data: {status: status, id: id},
                                    success: function (data) {
                                        alert(data);
//            alertToastr('', data, 'success', '', false);
                                        document.location.href = "dashboard_buttons.php";
                                    },
                                    error: function (jqXHR, textStatus, errorThrown) {
                                        alert(errorThrown);
                                    }
                                });
                            }
</script>
<?php
include("./includes/footer.php");
?>