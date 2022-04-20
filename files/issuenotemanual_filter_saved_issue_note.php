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

    echo '<table width="100%" id=savedList>';
    echo '<thead> <tr>
                <th style="text-align:left">S/N</th>
                <th style="text-align:left">Issue Number</th>
                <th style="text-align:left">Issue Date</th>
                <th style="text-align:left">Issue By</th>
                <th style="text-align:left">Requested By</th>
                <th style="text-align:left">Store Issuing</th>
                <th style="text-align:left">Store Receiving</th>
                <th style="text-align:left">Items Totals</th>
                <th style="text-align:left">Actions</th>
                </tr> </thead> ';

    $Issue_Note_Manual_List = List_Issue_Note_Manual($Current_Store_ID, array("submitted","saved","edited","Received"), $Date_From, $Date_To, $Issue_ID, $Store_Receiving_ID, 0);
    $sn=1;
    foreach($Issue_Note_Manual_List as $Issue_Note_Manual) {
        echo '<tr>';
        echo '<td><a target="_blank" href="issuenotemanual_preview.php?IssueManual_ID='.$Issue_Note_Manual['Issue_ID'].'">'.$sn++.'</a></td>';
        echo '<td><a target="_blank" href="issuenotemanual_preview.php?IssueManual_ID='.$Issue_Note_Manual['Issue_ID'].'">'.$Issue_Note_Manual['Issue_ID'].'</a></td>';
        echo '<td><a target="_blank" href="issuenotemanual_preview.php?IssueManual_ID='.$Issue_Note_Manual['Issue_ID'].'">'.$Issue_Note_Manual['Issue_Date_And_Time'].'</a></td>';
        echo '<td><a target="_blank" href="issuenotemanual_preview.php?IssueManual_ID='.$Issue_Note_Manual['Issue_ID'].'">'.$Issue_Note_Manual['EmployeeIssueing'].'</a></td>';
        echo '<td><a target="_blank" href="issuenotemanual_preview.php?IssueManual_ID='.$Issue_Note_Manual['Issue_ID'].'">'.$Issue_Note_Manual['EmployeeReceiving'].'</a></td>';
        echo '<td><a target="_blank" href="issuenotemanual_preview.php?IssueManual_ID='.$Issue_Note_Manual['Issue_ID'].'">'.$Issue_Note_Manual['StoreIssueing'].'</a></td>';
        echo '<td><a target="_blank" href="issuenotemanual_preview.php?IssueManual_ID='.$Issue_Note_Manual['Issue_ID'].'">'.$Issue_Note_Manual['StoreReiceiving'].'</a></td>';
        echo '<td><a target="_blank" href="issuenotemanual_preview.php?IssueManual_ID='.$Issue_Note_Manual['Issue_ID'].'">'.$Issue_Note_Manual['NumItems'].'</a></td>';
        echo '<td><a class="art-button-green" href="issuenotemanual_edit.php?IssueManual_ID='.$Issue_Note_Manual['Issue_ID'].'" > Edit </a>
                          <a class="art-button-green" class="Cancel_Issue_Note_Manual" href="#" onclick="Cancel_Issue_Note_Manual('. $Issue_Note_Manual['Issue_ID'] .')" > Cancel </a></td>';
        echo '</tr>';
    }
    echo '</table>';


?>