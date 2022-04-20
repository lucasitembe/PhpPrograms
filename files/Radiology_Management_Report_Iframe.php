<link rel="stylesheet" href="table.css" media="screen"> 

<?php
	require_once('includes/connection.php');
	if(isset($_GET['Sub_Department_ID'])) $Sub_Department_ID = $_GET['Sub_Department_ID'];
	if(isset($_GET['Product_Name'])) { $Product_Name = $_GET['Product_Name']; } else { $Product_Name = '' ;}
	//if(isset($_GET['DateFrom'])) { $DateFrom = $_GET['DateFrom']; } else { $DateFrom = '2015-04-03 16:52:47'; }
	if(isset($_GET['DateFrom'])) { $DateFrom = $_GET['DateFrom']; } else { $DateFrom = ''; }
	//if(isset($_GET['DateTo'])) { $DateTo = $_GET['DateTo']; } else { $DateTo = '2015-04-02 21:48:44'; }
	if(isset($_GET['DateTo'])) { $DateTo = $_GET['DateTo']; } else { $DateTo = ''; }
	if(isset($_GET['PatientType'])) { $PatientType = $_GET['PatientType']; } else { $PatientType = ''; }
	if(isset($_GET['SI'])) { $Supervisor_ID = $_GET['SI']; } else { $Supervisor_ID = 0; }
	if(isset($_GET['listtype'])) { $listtype = $_GET['listtype']; } else { $listtype = 'FromRec'; }
	if(isset($_GET['Sponsor'])) { $Sponsor = $_GET['Sponsor']; } else { $Sponsor = ''; }
	$apID = 0;
	$filter = '';
	
	if($Product_Name != ''){
		$filter = $filter . " AND i.Product_Name LIKE '%$Product_Name%'";
	} 
	
	if ($DateFrom != '' && $DateTo != ''){
		$filter = $filter . " AND ppil.Transaction_Date_And_Time BETWEEN '$DateFrom' AND '$DateTo' ";
	}
	
	if ($Sponsor != ''){
		$filter = $filter . " AND pp.Sponsor_Name = '$Sponsor' ";
	}
	
	//SELECTING PATIENTS LIST				
	$SelectRadiItems = "
		SELECT 
		i.Product_Name,
		i.Item_ID,
		SUM((ppil.Price - ppil.Discount)* ppil.Quantity) AS Revenue,
		COUNT(ppil.Item_ID) AS TheQuantity
			FROM 
			tbl_patient_payment_item_list ppil,
			tbl_items i,
			tbl_patient_payments pp
				WHERE
				i.Item_ID = ppil.Item_ID AND
				ppil.Patient_Payment_ID = pp.Patient_Payment_ID AND
				ppil.Check_In_Type = 'Radiology' AND
				ppil.Status = 'Served'
	";

	$group_by = " GROUP BY ppil.Item_ID";
	
	if($filter != ''){
		$SelectRadiItems_new = $SelectRadiItems.$filter.$group_by;
	} else {
		$SelectRadiItems_new = $SelectRadiItems.$group_by;		
	}
	
	echo '<table width="100%">';
	echo '<tr style="text-transform:uppercase; font-weight:bold;" class="thead">';	
		echo '<td style="width:3%;">SN</td>';	
		echo '<td>Test Name</td>';	
		echo '<td style="text-align:right;">Quantity</td>';	
		echo '<td style="text-align:right;">Revenue</td>';	
	echo '</tr>';	
	
	$SelectRadiItems_qry = mysqli_query($conn,$SelectRadiItems_new) or die(mysqli_error($conn));
	$sn = 1;
	$total_revenue=0;
	$total_tests=0;
	while($RadiItems = mysqli_fetch_assoc($SelectRadiItems_qry)){
		$item_name = $RadiItems['Product_Name'];
		$quantity = $RadiItems['TheQuantity'];
		$revenue = $RadiItems['Revenue'];
		$Item_ID = $RadiItems['Item_ID'];
		$total_revenue+=$revenue;
		$total_tests+=$quantity;
		
		$href = "Radiology_Management_Report_Patients.php?Item_ID=".$Item_ID. " target='_parent'  style='text-decoration:none;'";
		$style = 'style="text-decoration:none;"';

		echo '<tr>';	
			echo '<td><a href='.$href.'>'.$sn.'</a></td>';	
			echo '<td><a href='.$href.'>'.$item_name.'</a></td>';	
			echo '<td style="text-align:right;"><a href='.$href.'>'.$quantity.'</a></td>';	
			echo '<td style="text-align:right;"><a href='.$href.'>'.number_format($revenue).'</a></td>';;	
		echo '</tr>';;			
		$sn++;
		}
		
		echo '<tr>';	
			echo '<td></td>';	
			echo '<td></td>';	
			echo '<td><strong>TOTAL NO. OF TESTS:<span  style="float:right;"> '.number_format($total_tests).'</span></strong></td>';	
			echo '<td><strong>TOTAL REVENUE: <span  style="float:right;">'.number_format($total_revenue).'</span></strong></td>';;	
		echo '</tr>';;			
		//echo '<tr><td colspan=4><strong>'.number_format($total).'</strong></td></tr>';;	

	echo '</table>';
		
?>	