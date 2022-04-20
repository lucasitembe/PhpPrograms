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
    <legend align='right'><b>Pending Stock Taking  ~ <?php if (isset($_SESSION['Storage'])) { echo $_SESSION['Storage']; } ?></b></legend>
    <div id="pendingListWrapper" style="width:99%;height: 400px; overflow-x:hidden;overflow-y: scroll  ">
        <?php
            echo '<table width="100%" id=pendingList>';
            echo '<thead> <tr>
                    <th style="text-align:left">S/N</th>
                    <th style="text-align:left">Stock Taking Number</th>
                    <th style="text-align:left">Stock Taking Date</th>
                    <th style="text-align:left">Stock Taking Officer</th>
                    <th style="text-align:left">Stock Taking Location</th>
                    <th style="text-align:left">Items Totals</th>
                    </tr> </thead> ';

            $Today = Get_Time_Now();
            $One_Week_Ago = Get_Time($Today, "-7 days");

            $Stock_Taking_List = List_Stock_Taking("pending", Get_Day_Beginning($One_Week_Ago), Get_Day_Ending($Today), 200);
            $sn=1;
            foreach($Stock_Taking_List as $Stock_Taking) {
                echo '<tr>';
                echo '<td><a href="stocktaking.php?Stock_Taking_ID='.$Stock_Taking['Stock_Taking_ID'].'">'.$sn++.'</a></td>';
                echo '<td><a href="stocktaking.php?Stock_Taking_ID='.$Stock_Taking['Stock_Taking_ID'].'">'.$Stock_Taking['Stock_Taking_ID'].'</a></td>';
                echo '<td><a href="stocktaking.php?Stock_Taking_ID='.$Stock_Taking['Stock_Taking_ID'].'">'.$Stock_Taking['Stock_Taking_Date'].'</a></td>';
                echo '<td><a href="stocktaking.php?Stock_Taking_ID='.$Stock_Taking['Stock_Taking_ID'].'">'.$Stock_Taking['Stock_Taking_Officer'].'</a></td>';
                echo '<td><a href="stocktaking.php?Stock_Taking_ID='.$Stock_Taking['Stock_Taking_ID'].'">'.$Stock_Taking['Stock_Taking_Location'].'</a></td>';
                echo '<td><a href="stocktaking.php?Stock_Taking_ID='.$Stock_Taking['Stock_Taking_ID'].'">'.$Stock_Taking['NumItems'].'</a></td>';
                echo '</tr>';
            }
            echo '</table>';
        ?>
    </div>
</fieldset>

<script>
    $(document).ready(function () {
        $('#pendingList').DataTable({ "bJQueryUI": true });
        addDatePicker($("#Date_From"));
        addDatePicker($("#Date_To"));
    });

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
                    document.getElementById('pendingListWrapper').innerHTML = data;
                    $('#pendingList').DataTable({ "bJQueryUI": true });
                }
            };
            myObject.open('GET', 'stocktaking_filter_stocktakingpending.php?Date_From=' + Date_From
                + "&Date_To=" + Date_To + "&Status=pending", true);
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