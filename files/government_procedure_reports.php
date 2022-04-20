<?php
@session_start();
include("./includes/connection.php");
$filter = ' ';
$filterSub = ' ';
@$fromDate =$_POST['fromDate'];
@$toDate=$_POST['toDate'];
@$Filter_Category=$_POST['Filter_Category'];
@$SubCategory = $_POST['SubCategory'];
//display procedure report
if(isset($Filter_Category) && $Filter_Category=="yes"){
$filter = "  WHERE ilc.Transaction_Date_And_Time BETWEEN '" . $fromDate . "' AND '" . $toDate . "' AND ilc.Check_In_Type='Procedure' AND ilc.Status = 'served'";

    if ($SubCategory != 'All') {
        $filterSub .=" AND i.Item_Subcategory_ID='$SubCategory'";
    }
	
      $categoryRow=1;
        $i=2;

  $sqlCat = "SELECT sb.Item_Subcategory_ID,sb.Item_Subcategory_Name FROM tbl_item_list_cache ilc  INNER JOIN tbl_payment_cache pp ON pp.Payment_Cache_ID =ilc.Payment_Cache_ID JOIN tbl_items i ON ilc.Item_ID=i.Item_ID JOIN `tbl_item_subcategory` sb ON i.`Item_Subcategory_ID` = sb.`Item_Subcategory_ID` $filter $filterSub  and  i.Consultation_Type='Procedure' GROUP BY i.`Item_Subcategory_ID` ORDER BY sb.Item_Subcategory_Name";
// echo $sqlCat;exit; 
	echo "<div style='background-color:white;'>";
	echo "<table width='100%'>";
		echo"<thead>";
			echo"<tr>";
				echo"<th>Procedure Name</th><th>Quantity</th>";
			echo"</tr>";
		echo"</thead>";
		echo"<tbody>";
		
    $querySubcategory = mysqli_query($conn,$sqlCat) or die(mysqli_error($conn).' here');
 if(mysqli_num_rows($querySubcategory) >0 ){
    while ($row1 = mysqli_fetch_array($querySubcategory)) {
        $subcategory_name = $row1['Item_Subcategory_Name'];
        $subcategory_id = $row1['Item_Subcategory_ID'];
        $procedureData=array();

        $number_of_item = "SELECT i.Product_Name,count(i.Item_ID) as counts FROM tbl_item_list_cache ilc  INNER JOIN tbl_payment_cache pp ON pp.Payment_Cache_ID =ilc.Payment_Cache_ID JOIN tbl_items i ON ilc.Item_ID=i.Item_ID JOIN `tbl_item_subcategory` sb ON i.`Item_Subcategory_ID` = sb.`Item_Subcategory_ID` $filter AND i.Item_Subcategory_ID='$subcategory_id' GROUP BY Product_Name";
		
			echo "<tr><td colspan='2' style='background-color:gray;color:white;'>$subcategory_name</td></tr>";
			
        $number_of_item_results = mysqli_query($conn,$number_of_item) or die(mysqli_error($conn).' hapa');
        $sn = 1;
        $grandTotal = 0;
     
        if(mysqli_num_rows($number_of_item_results) >0 ){
        while ($row = mysqli_fetch_assoc($number_of_item_results)) {
                array_push($procedureData, array(
                        'quantity'=>$row['counts'],
                        'name'=>$row['Product_Name']
                    ));
        }
        array_multisort($procedureData,SORT_DESC);

        foreach ($procedureData as $key => $value){
			echo "<tr><td>{$sn}. {$value['name']}</td><td>{$value['quantity']}</td></tr>";
			$sn++;
			$grandTotal+=$value['quantity'];
		}
        }
		echo "<tr><td colspan='2'><hr></td></tr>";
		echo "<tr><td>Total Procedures</td><td>{$grandTotal}</td></tr>";
		echo "<tr><td colspan='2'><hr></td></tr>";
    }
 }
	echo"</tbody>";
		echo "</table>";
		echo "</div>";

}
?>