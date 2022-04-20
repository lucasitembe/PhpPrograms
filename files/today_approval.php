<?php
include('includes/connection.php');

	$today = Date("Y-m-d");
	$filter = "";
	$start_date = $_POST['start_date'];
	$end_date = $_POST['end_date'];
	$Employee_ID = $_POST['Employee_ID'];
	$patient_id = $_POST['patient_id'];
	if(isset($_POST['filter_by_date'])){
		$filter ="AND DATE(ilc.Approval_Date_Time) BETWEEN DATE('$start_date') AND DATE('$end_date')";
	}
	if(isset($_POST['filter_date'])){
		$filter .=" AND DATE(ilc.Approval_Date_Time) BETWEEN DATE('$start_date') AND DATE('$end_date') AND Approved_By='$Employee_ID'";
	}
	if(isset($_POST['filter_by_reg_no'])){
		$filter .=" AND DATE(ilc.Approval_Date_Time) BETWEEN DATE('$start_date') AND DATE('$end_date') AND pc.Registration_ID = '$patient_id'";
	}
	
	$slect_approve = mysqli_query($conn, "SELECT Employee_Name,Patient_Name,  pc.Payment_Cache_ID,pc.Registration_ID,ilc.Item_ID,ilc.Approved_By,ilc.Approval_Date_Time FROM tbl_payment_cache pc, tbl_item_list_cache ilc, tbl_employee e, tbl_patient_registration pr WHERE  pc.Registration_ID=pr.Registration_ID AND  pc.Payment_Cache_ID = ilc.Payment_Cache_ID AND Approved_By=e.Employee_ID AND  ilc.Category='indirect cash'  $filter GROUP BY  ilc.Payment_Cache_ID ORDER BY ilc.Approval_Date_Time  ASC") or die(mysqli_error($conn));
	$i=1;

	$data = '<table style="width:100%;background:#fff" >';
	$data_item =[];
	$i=1;
if(mysqli_num_rows($slect_approve)>0){
	while ($row = mysqli_fetch_assoc($slect_approve)) {
		$Registration_ID = $row['Registration_ID'];
		$Payment_Cache_ID = $row['Payment_Cache_ID'];
		$Employee_Name = $row['Employee_Name'];
		$Patient_Name = $row['Patient_Name'];
		
		$Item_IDs = $row['Item_ID'];
		$approve_date_and_time = $row['Approval_Date_Time'];
		$select_items = mysqli_query($conn, "SELECT Product_Name, Price, Quantity FROM  tbl_item_list_cache ilc, tbl_items i WHERE Payment_Cache_ID='$Payment_Cache_ID'  AND ilc.Item_ID= i.Item_ID") or die(mysqli_error($conn));
		$products = '';		
		$Total_amount = 0;
		$num=1;
		while($rowD = mysqli_fetch_assoc($select_items)){
			$Product_Name = $rowD['Product_Name'];
			$Price = $rowD['Price'];
			$Quantity = $rowD['Quantity'];
			$Amount = ($Price * $Quantity);
			$j++;
			if ($numberOfItem == 1) {
				$products ='<b>'. $num.'. </b> '.$Product_Name.'---> '.number_format($Amount).'.';
			} else {
				if ($track < $numberOfItem) {
					$products .= '<b>'.$num.'.</b>  '.$Product_Name.'---> '.number_format($Amount).', <br/> ';
				} else {
					$products .='<b>'. $num.'. </b> '.$Product_Name .'---> '.number_format($Amount).', <br/> ';				
				}
			}
			$num++;
			$Total_amount +=$Amount;
		}
		
		$data.= "<tr>
					<td style='text-align:center;width:3%;'>$i</td>
					<td style='width:15.1%'>".ucfirst($Patient_Name)."</td>
					<td style='width:9.1%; text-align: center;'>$Registration_ID</td>
					<td style='width:10.1%;'>$approve_date_and_time</td>
					<td style='width:30.1%'>$products	</td>
					<td style='text-align:right; width:20.1%'>".number_format($Total_amount, 2)."</td>
					<td style='width:20%; text-align: center;'>".ucfirst($Employee_Name)."</td></tr>"; 
		$i++;


		$grand_total += $Total_amount;
		
	}

	$data.= "<tr style='background: #dedede; pad'>
	<td style='text-align:right; width:28%; font-weight: bold; font-size: 20px;' colspan='5'>GRAND TOTAL AMOUNT APPROVED</td>
	<td style='text-align:right; width:19%; font-weight: bold; font-size: 20px;'>".number_format($grand_total, 2)."</td>
	<td style='width:14%;'></td></tr>"; 
}else{
	$data .= "<tr><td colspan='7'><h4><b style='color:red;'>No result found </b></h4></td></tr>";
}

$data .= '</table>';
echo $data;


?>