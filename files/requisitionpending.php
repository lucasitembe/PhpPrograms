<script src='js/functions.js'></script>
<?php
    include("./includes/header.php");
    include("./functions/requisition.php");
?>

<a href="requisition.php" class="art-button-green">BACK </a>

<br/><br/>
<fieldset>
    <center>
        <table width="60%">
            <tbody><tr>
                <?php
                    $Today_Date = Get_Today_Date();
                    $This_Month_Start_Date = Get_This_Month_Start_Date();
                ?>
                <td width="30%">
                    <input name="Date_From" id="Date_From" readonly placeholder="Start Date" style="text-align: center;" type="text"
                           value="<?php echo $This_Month_Start_Date; ?>">
                </td>
                <td style="text-align: right;" width="10%"><b>End Date</b></td>
                <td width="30%">
                    <input name="Date_To" id="Date_To" readonly placeholder="End Date" style="text-align: center;" type="text"
                           value="<?php echo $Today_Date; ?>">
                </td>
                <td style="text-align: center;" width="7%">
                    <input name="Filter" value="FILTER" class="art-button-green" onclick="Filter_Document()" type="button">
                </td>
            </tr>
            </tbody>
        </table>
    </center>
</fieldset>

<br/>
<fieldset>
    <center>
        <span>
            This page retrieves all requisition that have been created but not yet been processed.
            <br/>Only items that belongs to the logged in Store will show up here.
        </span>
    </center>
</fieldset>

<br/>
<fieldset >
    <legend align='right'><b>Pending Requisition  ~ <?php if (isset($_SESSION['Storage'])) { echo $_SESSION['Storage']; } ?></b></legend>
    <div id="documentListWrapper" style="width:99%;height: 400px; overflow-x:hidden;overflow-y: scroll  ">

    </div>
</fieldset>

<script>
    $(document).ready(function () {
        $('#documentList').DataTable({ "bJQueryUI": true });
        addDatePicker($("#Date_From"));
        addDatePicker($("#Date_To"));

        Filter_Document();
    });

    function Filter_Document() {
        var Date_From = document.getElementById("Date_From").value;
        var Date_To = document.getElementById("Date_To").value;

        if (Date_From != "" && Date_To != "") {
            if (window.XMLHttpRequest) {
                myObject = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                myObject = new ActiveXObject('Micrsoft.XMLHTTP');
                myObject.overrideMimeType('text/xml');
            }

            myObject.onreadystatechange = function () {
                data = myObject.responseText;
                if (myObject.readyState == 4) {
                    document.getElementById('documentListWrapper').innerHTML = data;
                    $('#documentList').DataTable({ "bJQueryUI": true });
                }
            };
            myObject.open('GET', 'requisition_filter_pending_document.php?Date_From=' + Date_From
                + "&Date_To=" + Date_To, true);
            myObject.send();
        }
    }
</script>

<link rel="stylesheet" href="media/css/jquery.dataTables.css" media="screen">
<link rel="stylesheet" href="media/themes/smoothness/dataTables.jqueryui.css" media="screen">
<link rel="stylesheet" href="css/select2.min.css" media="screen">
<script src="media/js/jquery.js" type="text/javascript"></script>
<script src="media/js/jquery.dataTables.js" type="text/javascript"></script>
<script src="css/jquery-ui.js"></script>
<script src="js/select2.min.js"></script>
<?php include './includes/footer.php';?>