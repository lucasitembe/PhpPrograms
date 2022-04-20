<?php
@session_start();
include("./includes/connection.php");
$filter = ' ';
$filterSub = ' ';
@$fromDate =$_POST['fromDate'];
@$toDate=$_POST['toDate'];
@$Filter_Category=$_POST['Filter_Category'];
@$SubCategory = $_POST['SubCategory'];

//display radiology report
    @$radiology_report_type=$_POST['radiology_report_type'];
    $filter = "  WHERE ilc.Transaction_Date_And_Time BETWEEN '" . $fromDate . "' AND '" . $toDate . "' AND ilc.Check_In_Type='Radiology' AND ilc.Status = 'served'";

    if ($SubCategory != 'All') {
        $filterSub .=" AND i.Item_Subcategory_ID='$SubCategory'";
    }
	if(isset($Filter_Category) && $Filter_Category=="yes"){
		if($radiology_report_type==="attendance"){
			echo "<h1 style='color:red;'>Page is under construction</h1>";
			$sqlCat = mysqli_query($conn,"SELECT sb.Item_Subcategory_ID,sb.Item_Subcategory_Name FROM tbl_item_list_cache ilc  INNER JOIN tbl_payment_cache pp ON pp.Payment_Cache_ID =ilc.Payment_Cache_ID JOIN tbl_items i ON ilc.Item_ID=i.Item_ID JOIN `tbl_item_subcategory` sb ON i.`Item_Subcategory_ID` = sb.`Item_Subcategory_ID` $filter $filterSub and i.Consultation_Type='Radiology' GROUP BY i.`Item_Subcategory_ID` ORDER BY sb.Item_Subcategory_Name");
		
			echo "<div style='background-color:white;'>";
			echo "<table width='100%'>";
			echo "<thead>";
			echo "<tr><th style='width:70%' >Modality</th><th style='width:30%;'>Tested Done</th><th>Tested Repeated</th></tr>";
			echo "</thead><tbody>";
			$sn = 1;
			$radiologyData=array();
			while($row=mysqli_fetch_assoc($sqlCat)){
				$subcategory_id = $row['Item_Subcategory_ID'];
				//echo "<tr><td colspan='2' style='background-color:grey;color:white;'>{$row['Item_Subcategory_Name']}</td></tr>";
				$modality=$row['Item_Subcategory_Name'];

				$number_of_item = "SELECT i.Product_Name,i.Item_ID FROM tbl_item_list_cache ilc  INNER JOIN tbl_payment_cache pp ON pp.Payment_Cache_ID =ilc.Payment_Cache_ID JOIN tbl_items i ON ilc.Item_ID=i.Item_ID JOIN `tbl_item_subcategory` sb ON i.`Item_Subcategory_ID` = sb.`Item_Subcategory_ID` $filter AND i.Item_Subcategory_ID=$subcategory_id GROUP BY Product_Name";
		
				$number_of_item_results = mysqli_query($conn,$number_of_item) or die(mysqli_error($conn));
				$grandTotal = 0;
				if(mysqli_num_rows($number_of_item_results) >0 ){
					while ($row = mysqli_fetch_assoc($number_of_item_results)){
						$number_item_count = "SELECT i.Item_ID FROM tbl_item_list_cache ilc  INNER JOIN tbl_payment_cache pp ON pp.Payment_Cache_ID =ilc.Payment_Cache_ID JOIN tbl_items i ON ilc.Item_ID=i.Item_ID $filter AND ilc.Item_ID='" . $row['Item_ID'] . "'";

						$number_of_item_count_results = mysqli_query($conn,$number_item_count) or die(mysqli_error($conn));
						$itemCount = mysqli_num_rows($number_of_item_count_results);
						$grandTotal+=$itemCount;
					}
					array_push($radiologyData,array(
						'tested'=>$grandTotal,
						'modality'=>$modality,
					));
				}
			}
			array_multisort($radiologyData,SORT_DESC);
			foreach($radiologyData as $key => $value){
				echo "<tr><td><b>{$sn}. {$value['modality']}</b></td><td><b>{$value['tested']}</b></td></tr>";
				$sn++;
			}
			echo "</tbody></table>";
			echo "</div>";
		}
		if($radiology_report_type==="frequency"){
			 $sqlCat = mysqli_query($conn,"SELECT sb.Item_Subcategory_ID,sb.Item_Subcategory_Name FROM tbl_item_list_cache ilc  INNER JOIN tbl_payment_cache pp ON pp.Payment_Cache_ID =ilc.Payment_Cache_ID JOIN tbl_items i ON ilc.Item_ID=i.Item_ID JOIN `tbl_item_subcategory` sb ON i.`Item_Subcategory_ID` = sb.`Item_Subcategory_ID` $filter $filterSub and i.Consultation_Type='Radiology' GROUP BY i.`Item_Subcategory_ID` ORDER BY sb.Item_Subcategory_Name");
		
			echo "<div style='background-color:white;'>";
			echo "<table width='100%'>";
			echo "<thead>";
			echo "<tr><th style='width:70%' >Examination Entity</th><th style='width:30%;'>Total Number</th></tr>";
			echo "</thead><tbody>";
			while($row=mysqli_fetch_assoc($sqlCat)){
				$subcategory_id = $row['Item_Subcategory_ID'];
				echo "<tr><td colspan='2' style='background-color:grey;color:white;'>{$row['Item_Subcategory_Name']}</td></tr>";
				$radiologyData=array();

				$number_of_item = "SELECT i.Product_Name,count(i.Item_ID) as counts FROM tbl_item_list_cache ilc  INNER JOIN tbl_payment_cache pp ON pp.Payment_Cache_ID =ilc.Payment_Cache_ID JOIN tbl_items i ON ilc.Item_ID=i.Item_ID JOIN `tbl_item_subcategory` sb ON i.`Item_Subcategory_ID` = sb.`Item_Subcategory_ID` $filter AND i.Item_Subcategory_ID=$subcategory_id GROUP BY Product_Name";
				
				$number_of_item_results = mysqli_query($conn,$number_of_item) or die(mysqli_error($conn));
				$sn = 1;
				$grandTotal = 0;
				if(mysqli_num_rows($number_of_item_results) >0 ){
					while ($row = mysqli_fetch_assoc($number_of_item_results)) {
						
						array_push($radiologyData, array(
									'quantity'=>$row['counts'],
									'name'=>$row['Product_Name']
								));
					}
					array_multisort($radiologyData,SORT_DESC);
					foreach($radiologyData as $key => $value){
						echo"<tr><td>{$sn}.  ".$value['name']."</td><td>".$value['quantity']."</td></tr>";
						$sn++; 
						$grandTotal+=$value['quantity'];
					}
				}
				echo "<tr><td colspan='2'><hr></td></tr>";
				echo "<tr><td><b>Total Examinations</b></td><td><b>{$grandTotal}</b></td></tr>";
				echo "<tr><td colspan='2'><hr></td></tr>";
			}	
			echo "</tbody></table>";
			echo "</div>";
		}
	}


?>