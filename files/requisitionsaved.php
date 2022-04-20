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
            <tbody>
                <tr>
                    <td style="text-align: right;" width="15%"><b>Requisition Number</b></td>
                    <td colspan=4>
                        <input name="Requisition_ID" id="Requisition_ID" placeholder="Requisition Number" style="text-align: center;"
                               onkeyup="Filter_Document()" type="text"/>
                    </td>
                </tr>
                <tr>
                    <?php
                    $Today_Date = Get_Today_Date();
                    $This_Month_Start_Date = Get_This_Month_Start_Date();
                    ?>
                    <td style="text-align: right;" width="15%"><b>Start Date</b></td>
                    <td width="35%">
                        <input name="Date_From" id="Date_From" readonly placeholder="Start Date" style="text-align: center;" type="text"
                               value="<?php echo $This_Month_Start_Date; ?>">
                    </td>
                    <td style="text-align: right;" width="15%"><b>End Date</b></td>
                    <td width="35%">
                        <input name="Date_To" id="Date_To" readonly placeholder="End Date" style="text-align: center;" type="text"
                               value="<?php echo $Today_Date; ?>">
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right;" colspan=4>
                        <input name="Filter" value="FILTER" class="art-button-green" onclick="Clear_Doc_No();Filter_Document();" type="button">
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
            This page retrieves all requisition that have been created and processed.
            Only items that belongs to the logged in Store will show up here.
            <br/>Items can only be canceled or edited if they have not have Issue Note against them.
        </span>
    </center>
</fieldset>

<br/>
<fieldset >
    <legend align='right'><b>Saved Requisition  ~ <?php if (isset($_SESSION['Storage'])) { echo $_SESSION['Storage']; } ?></b></legend>
    <div id="documentListWrapper" style="width:99%;height: 400px; overflow-x:hidden;overflow-y: scroll  "> </div>
</fieldset>

<script>
    $(document).ready(function () {
        $('#documentList').DataTable({ "bJQueryUI": true });
        addDatePicker($("#Date_From"));
        addDatePicker($("#Date_To"));

        Filter_Document();
    });
</script>

<script>
    function Cancel_Requisition(Requisition_ID) {
        var Confirm_Message = confirm("Are you sure you want to cancel this disposal??");
        if (Confirm_Message == true){
            if (window.XMLHttpRequest) {
                myObject = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                myObject = new ActiveXObject('Micrsoft.XMLHTTP');
                myObject.overrideMimeType('text/xml');
            }

            myObject.onreadystatechange = function () {
                data = myObject.responseText;
                if (myObject.readyState == 4) {
                    var mrejesho = data;
                    if (mrejesho.trim() == "yes") {
                        window.location = "requisitionsaved.php";
                    } else {
                        alert("Something went wrong");
                    }
                }
            };
            myObject.open('GET', 'requisition_cancel.php?Requisition_ID=' + Requisition_ID, true);
            myObject.send();
        }
    }
</script>

<script>
    function Clear_Doc_No() {
        document.getElementById("Requisition_ID").value = "";
    }
</script>

<script>
        function Filter_Document() {
        var Date_From = document.getElementById("Date_From").value;
        var Date_To = document.getElementById("Date_To").value;
        var Requisition_ID = document.getElementById("Requisition_ID").value;

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
            myObject.open('GET', 'requisition_filter_saved_document.php?Date_From=' + Date_From
                + "&Date_To=" + Date_To + "&Requisition_ID=" + Requisition_ID, true);
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