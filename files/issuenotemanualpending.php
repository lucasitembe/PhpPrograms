<script src='js/functions.js'></script>
<?php
    include_once("./includes/header.php");
    include_once("./functions/issuenotemanual.php");
    include_once("./functions/department.php");
?>

<a href="issuenotemanual.php" class="art-button-green">BACK </a>

<link rel="stylesheet" href="css/select2.min.css" media="screen">
<script src="media/js/jquery.js" type="text/javascript"></script>
<script src="css/jquery-ui.js"></script>
<script src="js/select2.min.js"></script>

<script type='text/javascript'>
    $(document).ready(function () {
        $('select').select2();
    });
</script>

<br/><br/>
<fieldset>
    <center>
        <table width="60%">
            <tbody>
            <tr>
                <td style="text-align: right;" width="10%"><b>Issue Number</b></td>
                <td width="30%">
                    <input name="Issue_ID" id="Issue_ID" placeholder="Issue Number" style="text-align: center;"
                           onkeyup="Filter_Document()" type="text"/>
                </td>
                <td style="text-align: right;" width="10%"><b>Receiving Store</b></td>
                <td width="30%">
                    <select name='Store_Receiving_ID' id='Store_Receiving_ID' style="width:100%" onchange="Clear_Doc_No();Filter_Document();">
                        <?php
                        echo "<option value=''>All</option>";
                        $Sub_Department_List = Get_Sub_Department_All();
                        foreach($Sub_Department_List as $Sub_Department) {
                            echo "<option value='{$Sub_Department['Sub_Department_ID']}'>";
                            echo "{$Sub_Department['Sub_Department_Name']}";
                            echo "</option>";
                        }
                        ?>
                    </select>
                </td>
                <td></td>
            </tr>
            <tr>
                <?php
                $Today_Date = Get_Today_Date();
                $This_Month_Start_Date = Get_This_Month_Start_Date();
                ?>
                <td style="text-align: right;" width="10%"><b>Start Date</b></td>
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
                    <input name="Filter" value="FILTER" class="art-button-green" onclick="Clear_Doc_No();Filter_Document();" type="button">
                </td>
            </tr>
            </tbody></table>
    </center>
</fieldset>

<br/>
<fieldset>
    <center>
        <span>
            This page retrieves all issue note that have been created but not yet been processed.
            <br/>Only documents that belongs to the logged in Store will show up here.
        </span>
    </center>
</fieldset>

<br/>
<fieldset >
    <legend align='right'><b>Pending Issue Note ( Manual )  ~ <?php if (isset($_SESSION['Storage'])) { echo $_SESSION['Storage']; } ?></b></legend>
    <div id="pendingListWrapper" style="width:99%;height: 400px; overflow-x:hidden;overflow-y: scroll  "> </div>
</fieldset>

<script>
    $(document).ready(function () {
        $('#pendingList').DataTable({ "bJQueryUI": true });
        addDatePicker($("#Date_From"));
        addDatePicker($("#Date_To"));

        Filter_Document();
    });
</script>

<script>
    function Clear_Doc_No() {
        document.getElementById("Issue_ID").value = "";
    }
</script>

<script>
    function Filter_Document() {
        var Date_From = document.getElementById("Date_From").value;
        var Date_To = document.getElementById("Date_To").value;
        var Issue_ID = document.getElementById("Issue_ID").value;
        var Store_Receiving_ID = document.getElementById("Store_Receiving_ID").value;

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
                    document.getElementById('pendingListWrapper').innerHTML = data;
                    $('#pendingList').DataTable({ "bJQueryUI": true });
                }
            };
            myObject.open('GET', 'issuenotemanual_filter_pending_issue_note.php?Date_From=' + Date_From
                + "&Date_To=" + Date_To + "&Issue_ID=" + Issue_ID + "&Store_Receiving_ID=" + Store_Receiving_ID + "&Status=pending", true);
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