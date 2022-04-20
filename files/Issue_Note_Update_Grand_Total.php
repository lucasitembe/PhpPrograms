<?php
	include("./includes/connection.php");
	include_once("./functions/issuenotemanual.php");
    include_once("./functions/items.php");

	if(isset($_GET['IssueManual_ID'])){
		$IssueManual_ID = $_GET['IssueManual_ID'];
	}else{
		$IssueManual_ID = 0;
	}
	$Issue_Manual_Item_List = Get_Issue_Note_Manual_Items($IssueManual_ID);

    $Grand_Total = 0;
    foreach($Issue_Manual_Item_List as $Issue_Manual_Item) {
        $Grand_Total += ($Issue_Manual_Item['Quantity_Issued'] * $Issue_Manual_Item['Buying_Price']);
    }
?>
<b>Grand Total : <?php echo number_format($Grand_Total); ?>