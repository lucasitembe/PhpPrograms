<?php if (session_status() == PHP_SESSION_NONE) { session_start(); } ?>
<?php
    include_once("./includes/connection.php");
    include_once("./functions/issuenotemanual.php");

if(isset($_GET['Store_Receiving_ID'])){
   $Store_Receiving_ID=$_GET['Store_Receiving_ID'];
}else{
  $Store_Receiving_ID="";  
}
if(isset($_GET['Store_Receiving_Name'])){
   $Store_Receiving_Name=$_GET['Store_Receiving_Name'];
}else{
   $Store_Receiving_Name="";  
}
if(isset($_GET['from_date'])){
   $from_date=$_GET['from_date'];
}
if(isset($_GET['to_date'])){
   $to_date=$_GET['to_date'];
}

$Date_From=$from_date;
$Date_To=$to_date;

$Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        if($from_date==""){
          $Date_From=$new_Date;  
        }
        if($from_date==""){
          $Date_To=$new_Date;  
        }  
    }

$filter="";
if($Store_Receiving_ID!="All"){
   $filter="AND rq.Store_Need='$Store_Receiving_ID'"; 
}
    if (isset($_SESSION['Storage_Info'])) {
        $Current_Store_ID = $_SESSION['Storage_Info']['Sub_Department_ID'];
    }
?>
    <fieldset style='overflow-y: scroll; height: 410px;background: #FFFFFF' id='Previous_Fieldset_List'>
    <?php
    echo '<table width="100%" class="table table-condensed" >';
    echo ' <tr>
                
                <th style="text-align:left">S/N</th>
                <th style="text-align:left">Issue Number</th>
                <th style="text-align:left">Issue Date</th>
                <th style="text-align:left">Issue By</th>
                <th style="text-align:left">Requested By</th>
                <th style="text-align:left">Store Issuing</th>
                <th style="text-align:left">Store Receiving</th>
                <th style="text-align:left">Items Totals</th>
                <th style="text-align:left">Buying Price</th>
                <th style="text-align:left">Selling Price</th>
                <tr><td colspan="11"><hr></td></tr>
                </tr> ';

    $Issue_Note_Manual_List = List_Issue_Note_Manual($Current_Store_ID, array("submitted","saved","edited","Received"), $Date_From, $Date_To, null, $Store_Receiving_ID, 0);
    $sn=1;
    $manual_total_selling_price=0;
    $manual_total_buying_price=0;
    foreach($Issue_Note_Manual_List as $Issue_Note_Manual) {
        $IssueManualItem_List = Get_Issue_Note_Manual_Items($Issue_Note_Manual['Issue_ID']);
        $manual_Total_sell = 0;$manual_Total_buy = 0;
        foreach($IssueManualItem_List as $IssueManualItem) {
                $manual_Total_buy += ($IssueManualItem['Buying_Price'] * $IssueManualItem['Quantity_Issued']);
                $manual_Total_sell += ($IssueManualItem['Selling_Price'] * $IssueManualItem['Quantity_Issued']);
                
            } 
        $manual_total_selling_price+=$manual_Total_sell;
        $manual_total_buying_price+=$manual_Total_buy;
        echo '<tr>';
        echo '<td><a target="_blank" href="issuenotemanual_preview.php?IssueManual_ID='.$Issue_Note_Manual['Issue_ID'].'">'.$sn++.'</a></td>';
        echo '<td><a target="_blank" href="issuenotemanual_preview.php?IssueManual_ID='.$Issue_Note_Manual['Issue_ID'].'">'.$Issue_Note_Manual['Issue_ID'].'</a></td>';
        echo '<td><a target="_blank" href="issuenotemanual_preview.php?IssueManual_ID='.$Issue_Note_Manual['Issue_ID'].'"style="color: #0079AE;font-weight:bold">'.$Issue_Note_Manual['Issue_Date_And_Time'].'</a></td>';
        echo '<td><a target="_blank" href="issuenotemanual_preview.php?IssueManual_ID='.$Issue_Note_Manual['Issue_ID'].'">'.$Issue_Note_Manual['EmployeeIssueing'].'</a></td>';
        echo '<td><a target="_blank" href="issuenotemanual_preview.php?IssueManual_ID='.$Issue_Note_Manual['Issue_ID'].'">'.$Issue_Note_Manual['EmployeeReceiving'].'</a></td>';
        echo '<td><a target="_blank" href="issuenotemanual_preview.php?IssueManual_ID='.$Issue_Note_Manual['Issue_ID'].'">'.$Issue_Note_Manual['StoreIssueing'].'</a></td>';
        echo '<td><a target="_blank" href="issuenotemanual_preview.php?IssueManual_ID='.$Issue_Note_Manual['Issue_ID'].'">'.$Issue_Note_Manual['StoreReiceiving'].'</a></td>';
        echo '<td style="text-align:right"><a target="_blank" href="issuenotemanual_preview.php?IssueManual_ID='.$Issue_Note_Manual['Issue_ID'].'">'.$Issue_Note_Manual['NumItems'].'</a></td>';
        echo '<td style="text-align:right"><a target="_blank" href="issuenotemanual_preview.php?IssueManual_ID='.$Issue_Note_Manual['Issue_ID'].'">'.number_format($manual_Total_buy).'</a></td>';
        echo '<td style="text-align:right"><a target="_blank" href="issuenotemanual_preview.php?IssueManual_ID='.$Issue_Note_Manual['Issue_ID'].'">'.number_format($manual_Total_sell).'</a></td>';
        
        echo '</tr>';
    }
    echo '</table>';

?>
</fieldset>
<fieldset>
    <table class="table">
        <tr>
            <td><b>GRAND TOTAL:</b></td>
            <td><b>TOTAL BUYING PRICE:</b>  <?= number_format($manual_total_buying_price)?></td>
            <td><b>TOTAL SELLING PRICE:</b>  <?= number_format($manual_total_selling_price)?></td>
        </tr>
    </table>
</fieldset>
