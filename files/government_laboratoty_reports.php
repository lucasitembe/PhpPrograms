

<?php
@session_start();
include("./includes/connection.php");
$filter = ' ';
$filterSub = ' ';
@$fromDate =$_POST['fromDate'];
@$toDate=$_POST['toDate'];
@$Filter_Category=$_POST['Filter_Category'];
@$SubCategory = $_POST['SubCategory'];

if(isset($Filter_Category) && $Filter_Category=="yes"){
	$filter = "  WHERE tr.TimeSubmitted BETWEEN '" . $fromDate . "' AND '" . $toDate . "'";
	if ($SubCategory != 'All') {
	    $filterSub .=" AND i.Item_Subcategory_ID='$SubCategory'";
	}
	@$laboratory_report_type=$_POST['laboratory_report_type'];
	if($laboratory_report_type=='laboratory_test'){
		echo "<div style='background-color:white;'>";
			echo "<table width='100%'>";
			echo "<thead>";
			echo "<tr><th style='width:70%' >Type Of Test</th><th style='width:30%;'>Total Number</th></tr>";
			echo "</thead><tbody>";
		$sqlCat = "SELECT sb.Item_Subcategory_ID,sb.Item_Subcategory_Name FROM tbl_test_results tr INNER JOIN tbl_item_list_cache ilc ON ilc.Payment_Item_Cache_List_ID=tr.payment_item_ID JOIN tbl_items i ON ilc.Item_ID=i.Item_ID JOIN `tbl_item_subcategory` sb ON i.`Item_Subcategory_ID` = sb.`Item_Subcategory_ID` JOIN tbl_payment_cache pp ON pp.Payment_Cache_ID =ilc.Payment_Cache_ID $filter$filterSub  AND i.Consultation_Type='Laboratory' GROUP BY i.`Item_Subcategory_ID` ORDER BY sb.Item_Subcategory_Name";


		$querySubcategory = mysqli_query($conn,$sqlCat) or die(mysqli_error($conn));

while($row1 = mysqli_fetch_array($querySubcategory)) {
    $subcategory_name = $row1['Item_Subcategory_Name'];
    $subcategory_id = $row1['Item_Subcategory_ID'];
    $laboratoryData=array();

    $number_of_item = mysqli_query($conn,"SELECT i.Product_Name,count(i.Item_ID) as counts FROM tbl_test_results tr INNER JOIN tbl_item_list_cache ilc ON ilc.Payment_Item_Cache_List_ID=tr.payment_item_ID JOIN tbl_items i ON ilc.Item_ID=i.Item_ID JOIN tbl_payment_cache pp ON pp.Payment_Cache_ID =ilc.Payment_Cache_ID $filter  AND i.Item_Subcategory_ID='$subcategory_id' GROUP BY Product_Name");

    echo "<tr><td colspan='2' style='background-color:grey;color:white;'>{$subcategory_name}</td></tr>";
      
        $sn = 1;
        $grandTotal = 0;
 
    while ($row = mysqli_fetch_assoc($number_of_item)) {
        array_push($laboratoryData, array(
            'quantity'=>$row['counts'],
            'name'=>$row['Product_Name']
        ));
    }

    array_multisort($laboratoryData,SORT_DESC);

        foreach ($laboratoryData as $key => $value) {
			echo"<tr><td>{$sn}.  ".$value['name']."</td><td>".$value['quantity']."</td></tr>";
			$sn++; 
			$grandTotal+=$value['quantity'];
		}

	echo "<tr><td colspan='2'><hr></td></tr>";
	echo "<tr><td><b>Total Tests</b></td><td><b>{$grandTotal}</b></td></tr>";
	echo "<tr><td colspan='2'><hr></td></tr>";
	}
	
		
		echo "</tbody></table>";
		echo "</div>";
	}
	if($laboratory_report_type=='blood_transfusion')
		echo "<h1 style='color:red;'>Page is under construction</h1>";

}
?>