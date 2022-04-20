<?php session_start(); ?>
<?php
    include_once("./includes/connection.php");
    include_once("./functions/stocktaking.php");

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

    echo '<table width="100%" id=pendingList>';
    echo '<thead> <tr>
                <th style="text-align:left">S/N</th>
                <th style="text-align:left">Stock Taking Number</th>
                <th style="text-align:left">Stock Taking Date</th>
                <th style="text-align:left">Stock Taking Officer</th>
                <th style="text-align:left">Stock Taking Location</th>
                <th style="text-align:left">Items Totals</th>
                </tr> </thead> ';

    $Stock_Taking_List = List_Stock_Taking("pending", Get_Day_Beginning($Date_From), Get_Day_Ending($Date_To), 200);
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