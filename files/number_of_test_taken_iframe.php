<?php
include './includes/connection.php';

$female_count = 0;
$male_count = 0;

$filter = '   WHERE DATE(tr.TimeSubmitted) BETWEEN CURDATE()-INTERVAL 1 DAY AND DATE(NOW())';
$filterSub = '';
if (isset($_POST['action']) && $_POST['action'] == 'getItem') {
    $fromDate = $_POST['fromDate'];
    $toDate = $_POST['toDate'];
    $sponsorName = $_POST['Sponsor'];
    $SubCategory = $_POST['SubCategory'];
    $filter = "  WHERE tr.TimeSubmitted BETWEEN '" . $fromDate . "' AND '" . $toDate . "'";
    if ($sponsorName != 'All') {
        $filter .=" AND pp.Sponsor_ID='$sponsorName'";
    }

    if ($SubCategory != 'All') {
        $filterSub .=" AND i.Item_Subcategory_ID='$SubCategory'";
    }
}

$sqlCat = "SELECT sb.Item_Subcategory_ID,sb.Item_Subcategory_Name FROM tbl_test_results tr INNER JOIN tbl_item_list_cache ilc ON ilc.Payment_Item_Cache_List_ID=tr.payment_item_ID JOIN tbl_items i ON ilc.Item_ID=i.Item_ID JOIN `tbl_item_subcategory` sb ON i.`Item_Subcategory_ID` = sb.`Item_Subcategory_ID` JOIN tbl_payment_cache pp ON pp.Payment_Cache_ID =ilc.Payment_Cache_ID JOIN tbl_sponsor sp ON sp.Sponsor_ID=pp.Sponsor_ID $filter $filterSub AND i.Consultation_Type='Laboratory' GROUP BY i.`Item_Subcategory_ID` ORDER BY sb.Item_Subcategory_ID";
// echo $sqlCat;exit; 
$querySubcategory = mysqli_query($conn,$sqlCat) or die(mysqli_error($conn));

while ($row1 = mysqli_fetch_array($querySubcategory)) {
    $subcategory_name = $row1['Item_Subcategory_Name'];
    $subcategory_id = $row1['Item_Subcategory_ID'];
    
    $number_of_item = "SELECT i.Product_Name,i.Item_ID,Guarantor_Name,i.Item_ID FROM tbl_test_results tr INNER JOIN tbl_item_list_cache ilc ON ilc.Payment_Item_Cache_List_ID=tr.payment_item_ID JOIN tbl_items i ON ilc.Item_ID=i.Item_ID JOIN tbl_payment_cache pp ON pp.Payment_Cache_ID =ilc.Payment_Cache_ID JOIN tbl_sponsor sp ON sp.Sponsor_ID=pp.Sponsor_ID $filter   AND i.Item_Subcategory_ID='$subcategory_id' GROUP BY i.Item_ID";

    //echo $number_of_item;exit;
    $number_of_item_results = mysqli_query($conn,$number_of_item) or die(mysqli_error($conn));

    $sn = 1;
    $grandTotal = 0;

    echo "<h1 style='margin:10px;width:100%;border-bottom:1px solid #A3A3A3;padding-bottom:5px;text-align:left'>" . ucfirst(strtolower($subcategory_name)) . "</h1>";

    echo "<table class='display' id='numberTests' width='100%'> 
             <thead>
                <tr>
                    <th style='text-align:left;width:5%'>S/n</th>
                    <th style='text-align:left'>Test Name</th>
                    <th style='text-align:left;width:15%'>Quantity</th>
                </tr>
            </thead>";
    while ($row = mysqli_fetch_assoc($number_of_item_results)) {

        $number_item_count = "SELECT i.Item_ID FROM tbl_test_results tr INNER JOIN tbl_item_list_cache ilc ON ilc.Payment_Item_Cache_List_ID=tr.payment_item_ID JOIN tbl_items i ON ilc.Item_ID=i.Item_ID JOIN tbl_payment_cache pp ON pp.Payment_Cache_ID =ilc.Payment_Cache_ID JOIN tbl_sponsor sp ON sp.Sponsor_ID=pp.Sponsor_ID $filter AND ilc.Item_ID='" . $row['Item_ID'] . "'   AND i.Item_Subcategory_ID='$subcategory_id' ORDER BY i.Item_ID";

        // die("SELECT i.Item_ID FROM tbl_test_results tr INNER JOIN tbl_item_list_cache ilc ON ilc.Payment_Item_Cache_List_ID=tr.payment_item_ID JOIN tbl_items i ON ilc.Item_ID=i.Item_ID JOIN tbl_payment_cache pp ON pp.Payment_Cache_ID =ilc.Payment_Cache_ID JOIN tbl_sponsor sp ON sp.Sponsor_ID=pp.Sponsor_ID $filter AND ilc.Item_ID='" . $row['Item_ID'] . "'   AND i.Item_Subcategory_ID='$subcategory_id'");

        //die($number_item_count);
        $number_of_item_count_results = mysqli_query($conn,$number_item_count) or die(mysqli_error($conn));
        $itemCount = mysqli_num_rows($number_of_item_count_results);

        echo '<tr>';
        echo '<td>' . $sn++ . '</td>';
        echo '<td>' . $row['Product_Name'] . '</td>';
        echo '<td style="text-align:left;">' . $itemCount . '</td>';
        echo '</tr>';
    }
    echo '</table><br/>';
}
?>
<div id="revenueitemsList" style="display: none">
    <div id="showrevenueitemList">


    </div>
</div>
<script>
    $.fn.dataTableExt.sErrMode = 'throw';
    $('#numberTests').dataTable({
        "bJQueryUI": true
    });
</script>