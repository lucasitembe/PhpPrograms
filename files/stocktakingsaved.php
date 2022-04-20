<script src='js/functions.js'></script>
<?php
    include("./includes/header.php");
    include("./functions/stocktaking.php");
?>

<a href="stocktaking.php" class="art-button-green">BACK </a>

<br/><br/>
<fieldset>
    <center>
        <table width="60%">
            <tbody><tr>
                <td style="text-align: right;" width="10%"><b>Start Date</b></td>
                <td width="30%">
                    <input name="Date_From" id="Date_From" readonly placeholder="Start Date" style="text-align: center;" type="text">
                </td>
                <td style="text-align: right;" width="10%"><b>End Date</b></td>
                <td width="30%">
                    <input name="Date_To" id="Date_To" readonly placeholder="End Date" style="text-align: center;" type="text">
                </td>
                <td style="text-align: center;" width="7%">
                    <input name="Filter" value="FILTER" class="art-button-green" onclick="Filter_Issue_Note()" type="button">
                </td>
            </tr>
            </tbody></table>
    </center>
</fieldset>

<fieldset >
    <legend align='right'><b>Saved Stock Taking  ~ <?php if (isset($_SESSION['Storage'])) { echo $_SESSION['Storage']; } ?></b></legend>
    <div id="savedListWrapper" style="width:99%;height: 400px; overflow-x:hidden;overflow-y: scroll  ">
        <?php
            echo '<table width="100%" id=savedList>';
            echo '<thead> <tr>
                    <th style="text-align:left">S/N</th>
                    <th style="text-align:left">Stock Taking Number</th>
                    <th style="text-align:left">Stock Taking Date</th>
                    <th style="text-align:left">Stock Taking Officer</th>
                    <th style="text-align:left">Stock Taking Location</th>
                    <th style="text-align:left">Items Totals</th>
                    <th style="text-align:left">Actions</th>
                    </tr> </thead> ';

            $Stock_Taking_List = List_Stock_Taking(array("saved","edited"), null, null, 200);
            $sn=1;
            foreach($Stock_Taking_List as $Stock_Taking) {
                echo '<tr>';
                echo '<td><a target="_blank" href="stocktaking_preview.php?Stock_Taking_ID='.$Stock_Taking['Stock_Taking_ID'].'">'.$sn++.'</a></td>';
                echo '<td><a target="_blank" href="stocktaking_preview.php?Stock_Taking_ID='.$Stock_Taking['Stock_Taking_ID'].'">'.$Stock_Taking['Stock_Taking_ID'].'</a></td>';
                echo '<td><a target="_blank" href="stocktaking_preview.php?Stock_Taking_ID='.$Stock_Taking['Stock_Taking_ID'].'">'.$Stock_Taking['Stock_Taking_Date'].'</a></td>';
                echo '<td><a target="_blank" href="stocktaking_preview.php?Stock_Taking_ID='.$Stock_Taking['Stock_Taking_ID'].'">'.$Stock_Taking['Stock_Taking_Officer'].'</a></td>';
                echo '<td><a target="_blank" href="stocktaking_preview.php?Stock_Taking_ID='.$Stock_Taking['Stock_Taking_ID'].'">'.$Stock_Taking['Stock_Taking_Location'].'</a></td>';
                echo '<td><a target="_blank" href="stocktaking_preview.php?Stock_Taking_ID='.$Stock_Taking['Stock_Taking_ID'].'">'.$Stock_Taking['NumItems'].'</a></td>';
                echo '<td><a class="art-button-green" href="stocktaking_edit.php?Stock_Taking_ID='.$Stock_Taking['Stock_Taking_ID'].'" > Edit </a>
                            <a class="art-button-green" class="Cancel_Stock_Taking" href="#" onclick="Cancel_Stock_Taking('. $Stock_Taking['Stock_Taking_ID'] .')" > Cancel </a></td>';
                echo '</tr>';
            }
            echo '</table>';
        ?>
    </div>
</fieldset>

<script>
    $(document).ready(function () {
        $('#savedList').DataTable({ "bJQueryUI": true });
        addDatePicker($("#Date_From"));
        addDatePicker($("#Date_To"));
    });
</script>

<script>
    function Cancel_Stock_Taking(Stock_Taking_ID) {
        var Confirm_Message = confirm("Are you sure you want to cancel this stock taking??");
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
                        window.location = "stocktakingsaved.php?StockingTakingSaved=StockingTakingSavedThisPage";
                    } else {
                        alert("Something went wrong");
                    }
                }
            };
            myObject.open('GET', 'stocktaking_cancel.php?Stock_Taking_ID=' + Stock_Taking_ID, true);
            myObject.send();
        }
    }
</script>

<script>
    function Filter_Issue_Note() {
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
            myObject.open('GET', 'stocktaking_filter_stocktakingsaved.php?Date_From=' + Date_From
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