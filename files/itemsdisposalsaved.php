<script src='js/functions.js'></script>
<?php
    include("./includes/header.php");
    include("./functions/itemsdisposal.php");
?>

<a href="itemsdisposal.php" class="art-button-green">BACK </a>

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
            </tbody></table>
    </center>
</fieldset>

<br/>
<fieldset>
    <center>
        <span>
            This page retrieves all disposal that have been created and processed.
            Only items that belongs to the logged in Store will show up here.
            <br/>Any document can be canceled or edited.
            <br> <b>NOTE : All edited/canceled balances will adjusted back accordingly and an audit will be tracked.</b>
        </span>
    </center>
</fieldset>

<br/>
<fieldset >
    <legend align='right'><b>Saved Adjustments  ~ <?php if (isset($_SESSION['Storage'])) { echo $_SESSION['Storage']; } ?></b></legend>
    <div id="savedListWrapper" style="width:99%;height: 400px; overflow-x:hidden;overflow-y: scroll  ">
    </div>
</fieldset>

<script>
    $(document).ready(function () {
        $('#savedList').DataTable({ "bJQueryUI": true });
        addDatePicker($("#Date_From"));
        addDatePicker($("#Date_To"));

        Filter_Document();
    });
</script>

<script>
    function Cancel_Disposal(Disposal_ID) {
        var Confirm_Message = confirm("Are you sure you want to cancel this Adjustment??");
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
                        window.location = "itemsdisposalsaved.php?PreviousDisposals=PreviousDisposalsThisPage";
                    } else {
                        alert("Something went wrong");
                    }
                }
            };
            myObject.open('GET', 'itemsdisposal_cancel.php?Disposal_ID=' + Disposal_ID, true);
            myObject.send();
        }
    }
</script>

<script>
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
                    document.getElementById('savedListWrapper').innerHTML = data;
                    $('#savedList').DataTable({ "bJQueryUI": true });
                }
            };
            myObject.open('GET', 'itemsdisposal_filter_saved_disposal.php?Date_From=' + Date_From
                + "&Date_To=" + Date_To + "&Status=saved", true);
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