<?php session_start(); ?>
<?php
    include_once("./includes/connection.php");
    include_once("./functions/returninward.php");

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
            <th style="text-align:left">Store Issuing</th>
            <th style="text-align:left">Store Receiving</th>
            <th style="text-align:left">Items Totals</th>
            <th style="text-align:left">Actions</th>
                </tr> </thead> ';

    $Return_Inward_List = List_Return_Inward(array("submitted","saved","edited"), $Date_From, $Date_To, 200);
    $sn=1;
    foreach($Return_Inward_List as $Return_Inward) {
        echo '<tr>';
        echo '<td><a target="_blank" href="returninward_preview.php?Inward_ID='.$Return_Inward['Inward_ID'].'">'.$sn++.'</a></td>';
        echo '<td><a target="_blank" href="returninward_preview.php?Inward_ID='.$Return_Inward['Inward_ID'].'">'.$Return_Inward['Inward_ID'].'</a></td>';
        echo '<td><a target="_blank" href="returninward_preview.php?Inward_ID='.$Return_Inward['Inward_ID'].'">'.$Return_Inward['Inward_Date'].'</a></td>';
        echo '<td><a target="_blank" href="returninward_preview.php?Inward_ID='.$Return_Inward['Inward_ID'].'">'.$Return_Inward['Employee_Name'].'</a></td>';
        echo '<td><a target="_blank" href="returninward_preview.php?Inward_ID='.$Return_Inward['Inward_ID'].'">'.$Return_Inward['Employee_Name'].'</a></td>';
        echo '<td><a target="_blank" href="returninward_preview.php?Inward_ID='.$Return_Inward['Inward_ID'].'">'.$Return_Inward['Store_Receiving'].'</a></td>';
        echo '<td><a target="_blank" href="returninward_preview.php?Inward_ID='.$Return_Inward['Inward_ID'].'">'.$Return_Inward['Store_Returning'].'</a></td>';
        echo '<td><a target="_blank" href="returninward_preview.php?Inward_ID='.$Return_Inward['Inward_ID'].'">'.$Return_Inward['NumItems'].'</a></td>';
        echo '<td><a class="art-button-green" href="returninward_edit.php?Inward_ID='.$Return_Inward['Inward_ID'].'" > Edit </a>
                          <a class="art-button-green" class="Cancel_Return_Inward" href="#" onclick="Cancel_Return_Inward('. $Return_Inward['Inward_ID'] .')" > Cancel </a></td>';
        echo '</tr>';
    }
    echo '</table>';


?>