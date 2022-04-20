<?php if (session_status() == PHP_SESSION_NONE) { session_start(); } ?>
<?php
    include_once("./includes/connection.php");
    include_once("./functions/itemsdisposal.php");

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

    if(isset($_GET['Status'])){
        $Status = $_GET['Status'];
    }

    if (isset($_SESSION['Storage_Info'])) {
        $Current_Store_ID = $_SESSION['Storage_Info']['Sub_Department_ID'];
    }

    echo '<table width="100%" id=pendingList>';
    echo '<thead> <tr>
            <th style="text-align:left">S/N</th>
            <th style="text-align:left">Disposal Number</th>
            <th style="text-align:left">Disposal Date</th>
            <th style="text-align:left">Disposing Officer</th>
            <th style="text-align:left">Disposal Location</th>
            <th style="text-align:left">Items Totals</th>
            </tr> </thead> ';

    $Disposal_List = List_Items_Disposal($Current_Store_ID, array("pending"), $Date_From, $Date_To, 200);
    $sn=1;
    foreach($Disposal_List as $Disposal) {
        echo '<tr>';
        echo '<td><a href="itemsdisposal.php?Disposal_ID='.$Disposal['Disposal_ID'].'">'.$sn++.'</a></td>';
        echo '<td><a href="itemsdisposal.php?Disposal_ID='.$Disposal['Disposal_ID'].'">'.$Disposal['Disposal_ID'].'</a></td>';
        echo '<td><a href="itemsdisposal.php?Disposal_ID='.$Disposal['Disposal_ID'].'">'.$Disposal['Disposed_Date'].'</a></td>';
        echo '<td><a href="itemsdisposal.php?Disposal_ID='.$Disposal['Disposal_ID'].'">'.$Disposal['Disposal_Officer'].'</a></td>';
        echo '<td><a href="itemsdisposal.php?Disposal_ID='.$Disposal['Disposal_ID'].'">'.$Disposal['Disposal_Location'].'</a></td>';
        echo '<td><a href="itemsdisposal.php?Disposal_ID='.$Disposal['Disposal_ID'].'">'.$Disposal['NumItems'].'</a></td>';
        echo '</tr>';
    }
    echo '</table>';


?>