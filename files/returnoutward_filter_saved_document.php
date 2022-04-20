<?php session_start(); ?>
<?php
    include_once("./includes/connection.php");
    include_once("./functions/returnoutward.php");

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

    echo '<table width="100%" id=documentList>';
    echo '<thead> <tr>
            <th style="text-align:left">S/N</th>
            <th style="text-align:left">Document Number</th>
            <th style="text-align:left">Transaction Date</th>
            <th style="text-align:left">Posted By</th>
            <th style="text-align:left">Requested By</th>
            <th style="text-align:left">Store</th>
            <th style="text-align:left">Supplier Receiving</th>
            <th style="text-align:left">Items Totals</th>
            <th style="text-align:left">Actions</th>
                </tr> </thead> ';

    $Return_Outward_List = List_Return_Outward(array("submitted","saved","edited"), $Date_From, $Date_To, 200);
    $sn=1;
    foreach($Return_Outward_List as $Return_Outward) {
        echo '<tr>';
        echo '<td><a target="_blank" href="returnoutward_preview.php?Outward_ID='.$Return_Outward['Outward_ID'].'">'.$sn++.'</a></td>';
        echo '<td><a target="_blank" href="returnoutward_preview.php?Outward_ID='.$Return_Outward['Outward_ID'].'">'.$Return_Outward['Outward_ID'].'</a></td>';
        echo '<td><a target="_blank" href="returnoutward_preview.php?Outward_ID='.$Return_Outward['Outward_ID'].'">'.$Return_Outward['Outward_Date'].'</a></td>';
        echo '<td><a target="_blank" href="returnoutward_preview.php?Outward_ID='.$Return_Outward['Outward_ID'].'">'.$Return_Outward['Employee_Name'].'</a></td>';
        echo '<td><a target="_blank" href="returnoutward_preview.php?Outward_ID='.$Return_Outward['Outward_ID'].'">'.$Return_Outward['Employee_Name'].'</a></td>';
        echo '<td><a target="_blank" href="returnoutward_preview.php?Outward_ID='.$Return_Outward['Outward_ID'].'">'.$Return_Outward['Sub_Department'].'</a></td>';
        echo '<td><a target="_blank" href="returnoutward_preview.php?Outward_ID='.$Return_Outward['Outward_ID'].'">'.$Return_Outward['Supplier'].'</a></td>';
        echo '<td><a target="_blank" href="returnoutward_preview.php?Outward_ID='.$Return_Outward['Outward_ID'].'">'.$Return_Outward['NumItems'].'</a></td>';
        echo '<td><a class="art-button-green" href="returnoutward_edit.php?Outward_ID='.$Return_Outward['Outward_ID'].'" > Edit </a>
                          <a class="art-button-green" class="Cancel_Return_Outward" href="#" onclick="Cancel_Return_Outward('. $Return_Outward['Outward_ID'] .')" > Cancel </a></td>';
        echo '</tr>';
    }
    echo '</table>';


?>