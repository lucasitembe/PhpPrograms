<?php session_start(); ?>
<?php
    include_once("./includes/connection.php");
    include_once("./functions/requisition.php");

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

    echo '<table width="100%" id=documentList>';
    echo '<thead> <tr>
                <th style="text-align:left">S/N</th>
                <th style="text-align:left">Document Number</th>
                <th style="text-align:left">Requesting Date</th>
                <th style="text-align:left">Officer</th>
                <th style="text-align:left">Store Requesting</th>
                <th style="text-align:left">Store Issuing</th>
                <th style="text-align:left">Items Totals</th>
                </tr> </thead> ';

    $Requisition_List = List_Requisition($Current_Store_ID, "pending", $Date_From, $Date_To, null, 200);
    $sn=1;
    foreach($Requisition_List as $Requisition) {
        echo '<tr>';
        echo '<td><a href="requisition.php?Requisition_ID='.$Requisition['Requisition_ID'].'">'.$sn++.'</a></td>';
        echo '<td><a href="requisition.php?Requisition_ID='.$Requisition['Requisition_ID'].'">'.$Requisition['Requisition_ID'].'</a></td>';
        echo '<td><a href="requisition.php?Requisition_ID='.$Requisition['Requisition_ID'].'">'.$Requisition['Created_Date'].'</a></td>';
        echo '<td><a href="requisition.php?Requisition_ID='.$Requisition['Requisition_ID'].'">'.$Requisition['Employee_Name'].'</a></td>';
        echo '<td><a href="requisition.php?Requisition_ID='.$Requisition['Requisition_ID'].'">'.$Requisition['Store_Requesting'].'</a></td>';
        echo '<td><a href="requisition.php?Requisition_ID='.$Requisition['Requisition_ID'].'">'.$Requisition['Store_Issuing'].'</a></td>';
        echo '<td><a href="requisition.php?Requisition_ID='.$Requisition['Requisition_ID'].'">'.$Requisition['NumItems'].'</a></td>';
        echo '</tr>';
    }
    echo '</table>';


?>