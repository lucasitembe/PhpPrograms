<?php if (session_status() == PHP_SESSION_NONE) { session_start(); } ?>
<?php
    include_once("./includes/connection.php");
    include_once("./functions/issuenotemanual.php");

    if(isset($_GET['Date_From'])){
        $Date_From = $_GET['Date_From'];
    }else{
        $Date_From = null;
    }

    if(isset($_GET['Date_To'])){
        $Date_To = $_GET['Date_To'];
    }else{
        $Date_To = null;
    }

    if(isset($_GET['Issue_ID'])){
        $Issue_ID = $_GET['Issue_ID'];
    }else{
        $Issue_ID = null;
    }

    if(isset($_GET['Store_Receiving_ID'])){
        $Store_Receiving_ID = $_GET['Store_Receiving_ID'];
    }else{
        $Store_Receiving_ID = null;
    }

    if(isset($_GET['Status'])){
        $Status = $_GET['Status'];
    }

    if (isset($_SESSION['Storage_Info'])) {
        $Current_Store_ID = $_SESSION['Storage_Info']['Sub_Department_ID'];
    }

    echo '<table width="100%" id=pendingList>';
    echo '<thead> <tr>
                <th style="text-align:left">S/N</th>
                <th style="text-align:left">Issue Number</th>
                <th style="text-align:left">Issue Date</th>
                <th style="text-align:left">Issue By</th>
                <th style="text-align:left">Requested By</th>
                <th style="text-align:left">Store Issuing</th>
                <th style="text-align:left">Store Receiving</th>
                <th style="text-align:left">Items Totals</th>
                </tr> </thead> ';

    $Issue_Note_Manual_List = List_Issue_Note_Manual($Current_Store_ID, array("pending"), null, null, $Issue_ID, $Store_Receiving_ID, 0);
    $sn=1;
    foreach($Issue_Note_Manual_List as $Issue_Note_Manual) {
        echo '<tr>';
        echo '<td><a href="issuenotemanual.php?IssueManual_ID='.$Issue_Note_Manual['Issue_ID'].'">'.$sn++.'</a></td>';
        echo '<td><a href="issuenotemanual.php?IssueManual_ID='.$Issue_Note_Manual['Issue_ID'].'">'.$Issue_Note_Manual['Issue_ID'].'</a></td>';
        echo '<td><a href="issuenotemanual.php?IssueManual_ID='.$Issue_Note_Manual['Issue_ID'].'">'.$Issue_Note_Manual['Issue_Date_And_Time'].'</a></td>';
        echo '<td><a href="issuenotemanual.php?IssueManual_ID='.$Issue_Note_Manual['Issue_ID'].'">'.$Issue_Note_Manual['EmployeeIssueing'].'</a></td>';
        echo '<td><a href="issuenotemanual.php?IssueManual_ID='.$Issue_Note_Manual['Issue_ID'].'">'.$Issue_Note_Manual['EmployeeReceiving'].'</a></td>';
        echo '<td><a href="issuenotemanual.php?IssueManual_ID='.$Issue_Note_Manual['Issue_ID'].'">'.$Issue_Note_Manual['StoreIssueing'].'</a></td>';
        echo '<td><a href="issuenotemanual.php?IssueManual_ID='.$Issue_Note_Manual['Issue_ID'].'">'.$Issue_Note_Manual['StoreReiceiving'].'</a></td>';
        echo '<td><a href="issuenotemanual.php?IssueManual_ID='.$Issue_Note_Manual['Issue_ID'].'">'.$Issue_Note_Manual['NumItems'].'</a></td>';
        echo '</tr>';
    }
    echo '</table>';


?>