<?php
include("./includes/connection.php");
include("./includes/header.php");
$controlforminput = '';

if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}

if (isset($_SESSION['userinfo']['Quality_Assurance'])) {
    if ($_SESSION['userinfo']['Quality_Assurance'] != 'yes') {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
?>

<?php
if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Quality_Assurance'] == 'yes') {
        ?>
        <a href='Eclaim_Billing_Session_Control.php?New_Bill=New_Bill&QualityAssuranceWorks=QualityAssuranceWorksThisPage' class='art-button-green'>
            CREATE NEW BILL
        </a>
        <?php
    }
}
?>

<?php
if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Quality_Assurance'] == 'yes') {
        ?>
        <a href='claimsummeryitemtypepage.php?RevenueCollectionByFolio=RevenueCollectionByFolioThisPage' class='art-button-green'>
            BACK
        </a>
        <?php
    }
}
?>

<br/><br/>

<center>
    <center>       
        <fieldset>
            <legend align="right" ><b>Invoices</b></legend>
            <!--<form action='#' method='post' name='myForm' id='myForm'>-->
            <table width=70%>
                <tr>
                    <td style='text-align: right; width: 10%'><b>Start Date</b></td>
                    <td width=30%>
                        <input type='text' name='Start_Date' id='date' required='required' placeholder='Start Date' readonly='readonly'>
                    </td>
                    <td style='text-align: right; width: 10%'><b>End Date</b></td>
                    <td width=30%>
                        <input type='text' name='End_Date' id='date2' required='required' placeholder='End Date' readonly='readonly'>
                    </td>
                    <td style='text-align: right;'><b>Insurance</b></td>
                    <td>
                        <select name='Sponsor_ID' id='Sponsor_ID' required='required'>
                            <option selected='selected'></option>
                            <?php
                            $sql = mysqli_query($conn,"select Guarantor_Name, Sponsor_ID from tbl_Sponsor
                                                                where Guarantor_Name <> 'CASH'") or die(mysqli_error($conn));
                            $num = mysqli_num_rows($sql);
                            if ($num > 0) {
                                while ($row = mysqli_fetch_array($sql)) {
                                    ?>
                                    <option value='<?php echo $row['Sponsor_ID']; ?>'><?php echo $row['Guarantor_Name']; ?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                    </td>
                    <td style='width: 5%;'>
                        <input name='submit' id='submit' type='button' value='FILTER' class='art-button-green' onclick='Filter_Transaction()'>
                    </td>
                </tr>
            </table>
            <!--</form>-->

            <fieldset style='overflow-y: scroll; height: 320px;' id='invoices_Fieldset_List'>
                
            </fieldset>
    </center>
</center>

<script>
    function Filter_Transaction() {
        var Start_Date = document.getElementById("date").value;
        var End_Date = document.getElementById("date2").value;
        var Sponsor_ID = document.getElementById("Sponsor_ID").value;

        if (Sponsor_ID != null && Sponsor_ID != '' && Start_Date != null && Start_Date != '' && End_Date != null && End_Date != '') {
            var datastring = 'Start_Date=' + Start_Date + '&End_Date=' + End_Date + '&Sponsor_ID=' + Sponsor_ID;

            $.ajax({
                type: 'GET',
                url: 'previous_invoice_frame.php',
                data: datastring,
                beforeSend: function (xhr) {
                    $('#progressStatus').show();
                },
                success: function (data) {
                    $('#invoices_Fieldset_List').html(data);
                }, complete: function (jqXHR, textStatus) {
                    $('#progressStatus').hide();
                }, error: function (jqXHR, textStatus, errorThrown) {
                    $('#progressStatus').hide();
                }
            });
        } else {
            if (Start_Date == '' || Start_Date == null) {
                document.getElementById("date").style = 'border: 3px solid red; text-align: center;';
            }

            if (End_Date == '' || End_Date == null) {
                document.getElementById("date2").style = 'border: 3px solid red; text-align: center;';
            }

            if (Sponsor_ID == '' || Sponsor_ID == null) {
                document.getElementById("Sponsor_ID").style = 'border: 3px solid red';
            }
        }
    }
</script>
<script>
    $(document).ready(function () {
        $('select').select2();
    })
</script>
<link rel="stylesheet" href="css/select2.min.css" media="screen">
<script src="js/select2.min.js"></script>



<?php
include("./includes/footer.php");
?>