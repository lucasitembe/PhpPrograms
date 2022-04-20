<?php
include("./includes/header.php");
include("./includes/connection.php");
include_once("./functions/items.php");
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['General_Ledger'])) {
        if ($_SESSION['userinfo']['General_Ledger'] != 'yes') {
            header("Location: ./index.php?InvalidPrivilege=yes");
        }
    } else {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_GET['Section']) && $_GET['Section'] == 'managementworkspage') {

    $_SESSION['Section_managementworkspage'] = true;
}


if (isset($_SESSION['Section_managementworkspage']) && $_SESSION['Section_managementworkspage'] == true) {
    echo '<a href="costofsaleitemsprint.php?' . $_SERVER['QUERY_STRING'] . '" id="Preview" value="PREVIEW" target="_blank" class="art-button-green" >PREVIEW</a>';
    echo '<a href="managementworkspage.php?ManagementWorksPage=ThisPage&Start_Date=' . $_GET['Start_Date'] . '&End_Date=' . $_GET['End_Date'] . '&Sub_Department_ID=' . $_GET['Sub_Department_ID'] . '" style=""><button type="button" class="art-button-green">Back</button></a>
';
} else {
    echo '<a href="costofsaleitemsprint.php?' . $_SERVER['QUERY_STRING'] . '" id="Preview" value="PREVIEW" target="_blank" class="art-button-green" >PREVIEW</a>';
    echo '<a href="costofsales.php?Start_Date=' . $_GET['Start_Date'] . '&End_Date=' . $_GET['End_Date'] . '&Sub_Department_ID=' . $_GET['Sub_Department_ID'] . '" style=""><button type="button" class="art-button-green">BACK</button></a>
';
}

$Start_Date = filter_input(INPUT_GET, 'Start_Date');
$End_Date = filter_input(INPUT_GET, 'End_Date');
$Item_Classification = filter_input(INPUT_GET, 'Item_Classification');
$Sub_Department_ID = filter_input(INPUT_GET, 'Sub_Department_ID');

//echo $Start_Date.' '.$End_Date.' '.$Item_Category_ID.' '.$Sub_Department_ID;

$filterIss = " AND Issue_Date BETWEEN '$Start_Date' AND '$End_Date' AND rqi.Item_Status='received' and i.Item_Type !='Pharmacy'";
$filterIssManual = " AND DATE(Created_Date_And_Time) BETWEEN '$Start_Date' AND '$End_Date' AND iss.status='saved' and i.Item_Type !='Pharmacy'";
$filterIssPharmacy = " AND DATE(Dispense_Date_Time) BETWEEN '$Start_Date' AND '$End_Date' AND il.Status='dispensed'";

if (!empty($Sub_Department_ID) && $Sub_Department_ID !== 'All') {
    $filterIss .="  AND rq.Store_Need = '$Sub_Department_ID'";
    $filterIssManual .=" AND iss.Store_Need = '$Sub_Department_ID'";
    $filterIssPharmacy .=" AND il.Sub_Department_ID = '$Sub_Department_ID'";
}
?>
<script type='text/javascript'>
    function access_Denied() {
        alert("Access Denied");
        document.location = "./index.php";
    }
</script>

<script type="text/javascript">
    function Warning() {
        alert("This report is under construction. Please use the report named REVENUE COLLECTION - DETAIL instead");
    }
</script>

<style>
    table,tr,td{
        border-collapse:collapse !important;
        border:none !important;
    }
    .inputCost{
        margin-bottom:15px; 
        text-align: center;
        width:100%;
    }
    .hover a{
        text-decoration: none;
    }
    tr.hover:hover{
        background-color: #CCC;  
    }
</style>
<br/><br/>
<center>
    <fieldset style="width:100%;height: 430px;overflow-y: scroll;overflow-x: hidden">
        <legend align='right' style="background-color:#006400;color:white;padding:5px;">
            <b>COST OF SALE FOR <?php echo strtoupper($Item_Classification) ?></b>
        </legend>
        <?php
        $data = "
        <table width='100%'>
          <tr><td colspan='4'><hr></td></tr>
          <tr>
           <td ><b>Item Name</b></td>
           <td style='text-align:right;width:17%' ><b>Quantity</b></td>
           <td style='text-align:right;width:25%' ><b>Cost Of Sale</b></td>
          </tr>
          <tr><td colspan='4'><hr></td></tr>
        ";


        $grandTotal = 0;
//foreach ($classifications as $value) {

        if (!empty($Item_Classification) && $Item_Classification != 'All') {
            $array = array($Item_Classification);
            $classifications = array_intersect($classifications, $array);
        }
        if ($Item_Classification == 'Pharmaceuticals') {
            $sql_select_pharmacy_items = mysqli_query($conn,
                    "SELECT DISTINCT(il.Item_ID),Product_Name FROM tbl_item_list_cache il, tbl_items i WHERE
                                    i.Item_ID = il.Item_ID AND
                                    i.Classification='Pharmaceuticals' $filterIssPharmacy") or die(mysqli_error($conn));

            while ($rowPharm_Item = mysqli_fetch_array($sql_select_pharmacy_items)) {
                $sql_select_pharmacy = mysqli_query($conn,"SELECT il.Item_ID,IF(il.Edited_Quantity > 0, il.Edited_Quantity,il.Quantity) AS Qty,il.Price FROM tbl_item_list_cache il, tbl_items i WHERE
                                    i.Item_ID = il.Item_ID AND
                                    i.Classification='Pharmaceuticals' $filterIssPharmacy AND 
                                     il.Item_ID ='" . $rowPharm_Item['Item_ID'] . "'   
                                     ") or die(mysqli_error($conn));
                $costSale = 0;
                $totalQty = 0;

                while ($rowPharm = mysqli_fetch_array($sql_select_pharmacy)) {
                    $Quantity = $rowPharm['Qty'];
                    $last_buying_prices = Get_Item_Last_Buying_Price_With_Supplier($rowPharm['Item_ID']);

                    if (!empty($last_buying_prices)) {
                        $last_buying_price = $last_buying_prices[0]["Buying_Price"];
                    } else {
                        $last_buying_price = 0;
                    }

                    $costSale +=$Quantity * $last_buying_price;
                }

                $grandTotal +=$costSale;

                $data .= "<tr class='hover'>";
                $data .= "<td >" . $rowPharm_Item['Product_Name'] . "</td>";
                $data .= "<td style='text-align:right;width:17%'>" . number_format($totalQty) . "</td>";
                $data .= "<td style='text-align:right;width:25%'>" . number_format($costSale) . "</td>";
                $data .= "</tr>";
            }
        } else {
            $superarray = array();
            $sql_select = mysqli_query($conn,"SELECT DISTINCT(rqi.Item_ID) FROM tbl_issues iss, tbl_requisition rq, tbl_requisition_items rqi, tbl_items i WHERE
                                    iss.Requisition_ID = rq.Requisition_ID AND
                                    rqi.Requisition_ID = rq.Requisition_ID AND
                                    i.Item_ID = rqi.Item_ID AND
                                    i.Classification='$Item_Classification' $filterIss") or die(mysqli_error($conn));

            while ($rowsel = mysqli_fetch_assoc($sql_select)) {
                $superarray[] = $rowsel['Item_ID'];
            }

            $sql_select_manual = mysqli_query($conn,"SELECT DISTINCT(ii.Item_ID)  FROM tbl_issuesmanual iss,  tbl_issuemanual_items ii, tbl_items i WHERE
                                    iss.Issue_ID = ii.Issue_ID AND
                                    i.Item_ID = ii.Item_ID AND
                                    i.Classification='$Item_Classification' $filterIssManual") or die(mysqli_error($conn));

            while ($rowmansel = mysqli_fetch_assoc($sql_select_manual)) {
                $superarray[] = $rowmansel['Item_ID'];
            }

            $superarray = array_unique($superarray);

            foreach ($superarray as $item_id) {
                $costSale = 0;
                $totalQty = 0;

                $productName = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Product_Name FROM tbl_items WHERE Item_ID='$item_id' "))['Product_Name'];

                $sql_select_item = mysqli_query($conn,"SELECT rqi.Item_ID,rqi.Quantity_Received FROM tbl_issues iss, tbl_requisition rq, tbl_requisition_items rqi, tbl_items i WHERE
                                    iss.Requisition_ID = rq.Requisition_ID AND
                                    rqi.Requisition_ID = rq.Requisition_ID AND
                                    i.Item_ID = rqi.Item_ID AND
                                    rqi.Item_ID = '$item_id' AND
                                    i.Classification='$Item_Classification' $filterIss") or die(mysqli_error($conn));

                $sql_select_manual_item = mysqli_query($conn,"SELECT ii.Item_ID,ii.Quantity_Issued FROM tbl_issuesmanual iss,  tbl_issuemanual_items ii, tbl_items i WHERE
                                    iss.Issue_ID = ii.Issue_ID AND
                                    i.Item_ID = ii.Item_ID AND
                                    ii.Item_ID = '$item_id' AND
                                    i.Classification='$Item_Classification' $filterIssManual") or die(mysqli_error($conn));

                while ($rowItem = mysqli_fetch_array($sql_select_item)) {
                    $last_buying_prices = Get_Item_Last_Buying_Price_With_Supplier($rowItem['Item_ID']);

                    if (!empty($last_buying_prices)) {
                        $last_buying_price = $last_buying_prices[0]["Buying_Price"];
                        //print_r($last_buying_prices);
                    } else {
                        $last_buying_price = 0;
                    }

                    $costSale +=$rowItem['Quantity_Received'] * $last_buying_price;

                    $totalQty +=$rowItem['Quantity_Received'];
                }

                while ($rowManulItem = mysqli_fetch_array($sql_select_manual_item)) {
                    $last_buying_prices = Get_Item_Last_Buying_Price_With_Supplier($rowManulItem['Item_ID']);

                    if (!empty($last_buying_prices)) {
                        $last_buying_price = $last_buying_prices[0]["Buying_Price"];
                        //print_r($last_buying_prices);
                    } else {
                        $last_buying_price = 0;
                    }

                    $costSale +=$rowManulItem['Quantity_Issued'] * $last_buying_price;

                    $totalQty +=$rowManulItem['Quantity_Issued'];
                }

                $grandTotal +=$costSale;

                $data .= "<tr class='hover'>";
                $data .= "<td >" . $productName . "</td>";
                $data .= "<td style='text-align:right;width:17%'>" . number_format($totalQty) . "</td>";
                $data .= "<td style='text-align:right;width:25%'>" . number_format($costSale) . "</td>";
                $data .= "</tr>";
            }
        }

        $data .= "
          <tr><td colspan='4'><hr></td></tr>
          <tr>
           <td colspan='4' style='text-align:right;width:100%;font-size:16px'><b>Grand Total:&nbsp;&nbsp;&nbsp;" . number_format($grandTotal) . "</b></td>
          </tr>
          <tr><td colspan='4'><hr></td></tr>
        ";

        $data .= "</table>";


        echo $data;
        ?>
    </fieldset><br/>

    <?php
    include("./includes/footer.php");
    