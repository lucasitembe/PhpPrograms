<?php
include("./includes/connection.php");
include("./includes/header.php");
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

//get branch id
if (isset($_SESSION['userinfo']['Branch_ID'])) {
    $Branch_ID = $_SESSION['userinfo']['Branch_ID'];
} else {
    $Branch_ID = 0;
}
?>
<?php if (isset($_SESSION['userinfo'])) { ?>
    <a href='procurementotherconfiguration.php?ProcurementOtherConfiguration=ProcurementOtherConfigurationThisPage' class='art-button-green'>BACK</a>
<?php } ?>


<?php
$select = mysqli_query($conn,"select Departmental_Stock_Movement from tbl_system_configuration") or die(mysqli_error($conn));
$num = mysqli_num_rows($select);
if ($num > 0) {
    while ($data = mysqli_fetch_array($select)) {
        $Departmental_Stock_Movement = $data['Departmental_Stock_Movement'];
    }
} else {
    $Departmental_Stock_Movement = 'yes';
}

//
//        editable Quantity
$select1 = mysqli_query($conn,"select Editable_Quantity_Received,enable_receive_by_package from tbl_system_configuration WHERE Branch_ID='$Branch_ID'") or die(mysqli_error($conn));
$row = mysqli_fetch_assoc($select1);
$Editable = $row['Editable_Quantity_Received'];
$enable_receive_by_package = $row['enable_receive_by_package'];

//save changes
if (isset($_POST['Update'])) {
    if (isset($_POST['Edit_Received_Qty'])) {
        $Edit_Received_Qty = 'yes';
    } else {
        $Edit_Received_Qty = 'no';
    }

    if (isset($_POST['enable_receive_by_package'])) {
        $enable_receive_by_package = 'yes';
    } else {
        $enable_receive_by_package = 'no';
    }

    $Dept_Stock_Movement = $_POST['Dept_Stock_Movement'];
    $update = mysqli_query($conn,"update tbl_system_configuration set Departmental_Stock_Movement = '$Dept_Stock_Movement',Editable_Quantity_Received='$Edit_Received_Qty',enable_receive_by_package='$enable_receive_by_package' WHERE Branch_ID='$Branch_ID'") or die(mysqli_error($conn));
    if ($update) {
        ?>
        <script type="text/javascript">
            alert("Changes Saved Successfully");
            document.location = "procurementotherconfiguration.php?ProcurementOtherConfiguration=ProcurementOtherConfigurationThisPage";
        </script>
        <?php
    }
}
?>


<br/><br/><br/><br/>
<fieldset>  
    <legend align=center><b>STOCK MOVEMENT CONFIGURATIONS</b></legend>
    <center>
        <form action="#" method="post">
            <table width = "75%">
                <tr>
                    <td style='text-align: center;'>
                        <input type="radio" name="Dept_Stock_Movement" id="Departmental" value="yes" <?php
if (strtolower($Departmental_Stock_Movement) == 'yes') {
    echo "checked='checked'";
}
?> title="Stock movement within department">
                        <label for="Departmental" title="Stock movement within department">ALLOW DEPARTMENTAL STOCK MOVEMENT</label>
                    </td>
                    <td style='text-align: center;'>
                        <input type="radio" name="Dept_Stock_Movement" id="Centralized" value="no" <?php
                               if (strtolower($Departmental_Stock_Movement) == 'no') {
                                   echo "checked='checked'";
                               }
                               ?> title="Centralized Stock movement">
                        <label for="Centralized" title="Centralized Stock movement">ALLOW CENTRALIZED STOCK MOVEMENT</label>
                    </td>

                    <td style='text-align: center;'>
                        <span id="editable">
                            <?php
                            if ($Editable == 'no') {
                                echo'<input type="checkbox" name="Edit_Received_Qty" id="Quantity_Received">';
                            } else {
                                echo '<input type="checkbox" checked="true" name="Edit_Received_Qty" id="Quantity_Received" >';
                            }
                            ?>

                            <label for="Quantity_received">EDITABLE QUANTITY RECEIVED</label>
                        </span>
                    </td>
                    <td style='text-align: center;'>
                        <span id="enable_package">
                            <?php
                            if ($enable_receive_by_package == 'no') {
                                echo'<input type="checkbox" name="enable_receive_by_package" id="enable_receive_by_package">';
                            } else {
                                echo '<input type="checkbox" checked="true" name="enable_receive_by_package" id="enable_receive_by_package" >';
                            }
                            ?>

                            <label for="enable_receive_by_package">ENABLE RECEIVE BY PACKAGE</label>
                        </span>
                    </td>
                    <td width="15%" style="text-align: center;">
                        <input type="submit" name="submit" id="submit" value="UPDATE" class="art-button-green">
                        <input type="hidden" name="Update" id="Update" value="true">
                    </td>
                </tr>
            </table>
        </form>
    </center>
</fieldset>
<?php
include("./includes/footer.php");
?>

<script>
    $('#editable,#Quantity_Received').on('click', function () {
        if ($('#Quantity_Received').is(':checked')) {
            $('#Quantity_Received').prop('checked', false);
        } else {
            $('#Quantity_Received').prop('checked', true);
        }
    });
</script>
<!--<script>
    $('#enable_receive,#enable_receive_by_package').on('click', function () {
        if ($('#enable_receive_by_package').is(':checked')) {
            $('#enable_receive_by_package').prop('checked', false);
        } else {
            $('#enable_receive_by_package').prop('checked', true);
        }
    });
</script>-->