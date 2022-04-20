<link rel="stylesheet" href="table.css" media="screen"> 
<link rel="stylesheet" href="media/css/jquery.dataTables.css" media="screen"> 
<script src="media/js/jquery.js"></script>
<script src="media/js/jquery.dataTables.js"></script>
<script type="text/javascript">
$(document).ready(function(){
$('#reportID').DataTable();
});

</script>
<?php
	require_once('includes/connection.php');
	if(isset($_GET['Sub_Department_ID'])) $Sub_Department_ID = $_GET['Sub_Department_ID'];
	if(isset($_GET['Product_Name'])) { $Product_Name = $_GET['Product_Name']; } else { $Product_Name = '' ;}
	if(isset($_GET['DateFrom'])) { $DateFrom = $_GET['DateFrom']; } else { $DateFrom = ''; }
	if(isset($_GET['DateTo'])) { $DateTo = $_GET['DateTo']; } else { $DateTo = ''; }
	if(isset($_GET['PatientType'])) { $PatientType = $_GET['PatientType']; } else { $PatientType = ''; }
	if(isset($_GET['SI'])) { $Supervisor_ID = $_GET['SI']; } else { $Supervisor_ID = 0; }
	if(isset($_GET['listtype'])) { $listtype = $_GET['listtype']; } else { $listtype = 'FromRec'; }
	if(isset($_GET['Sponsor'])) { $Sponsor = $_GET['Sponsor']; } else { $Sponsor = ''; }
	if(isset($_GET['Item_ID'])) { $Item_ID = $_GET['Item_ID']; } else { $Item_ID = 0; }
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
		ppil.Price AS Price,
		COUNT(ppil.Item_ID) AS TheQuantity,
		pr.Patient_Name,
		pp.Sponsor_Name,
		pr.Gender,
		pr.Date_Of_Birth,
		pr.Region
			FROM 
			tbl_patient_payment_item_list ppil,
			tbl_items i,
			tbl_patient_payments pp,
			tbl_patient_registration pr,
			tbl_sponsor sp
				WHERE
				i.Item_ID = ppil.Item_ID AND
				ppil.Patient_Payment_ID = pp.Patient_Payment_ID AND
				pr.Registration_ID = pp.Registration_ID AND
				ppil.Check_In_Type = 'Radiology' AND
				ppil.Status = 'Served' AND
				ppil.Item_ID = '$Item_ID'
	";

	$group_by = " GROUP BY pp.Registration_ID";
	
	if($filter != ''){
		$SelectRadiItems_new = $SelectRadiItems.$filter.$group_by;
	} else {
		$SelectRadiItems_new = $SelectRadiItems.$group_by;		
	}
	
	echo '<table width="100%" id="reportID" class="display">
	<thead>
		<tr style="text-transform:uppercase; font-weight:bold;" class="thead">	
			<th style="width:3%;">SN</th>	
			<th>Test Name</th>
			<th>Gender</th>	
			<th>Age</th>	
			<th>Region</th>	
			<th>Sponsor Name</th>	
			<th style="text-align:right;">Quantity</th>	
			<th style="text-align:right;">Revenue</th>	
		</tr>
	</thead>';	
	
	$SelectRadiItems_qry = mysqli_query($conn,$SelectRadiItems_new) or die(mysqli_error($conn));
	$sn = 1;
	$total_revenue=0;
	$total_tests=0;
	while($RadiItems = mysqli_fetch_assoc($SelectRadiItems_qry)){
		$item_name = $RadiItems['Product_Name'];
		$patient_name = $RadiItems['Patient_Name'];
		$sponsor_name = $RadiItems['Sponsor_Name'];
		$gender = $RadiItems['Gender'];
		$dob = $RadiItems['Date_Of_Birth'];
		$age = CalculateAge($dob);
		$region = $RadiItems['Region'];
		$quantity = $RadiItems['TheQuantity'];
		$price = $RadiItems['Price'];
		$revenue = $price * $quantity;
		$total_revenue+=$revenue;
		$total_tests+=$quantity;
		
		$href = "#";
		$style = 'style="text-decoration:none;"';

		echo '<tr>';	
			echo '<td>'.$sn.'</td>';	
			echo '<td>'.$patient_name.'</td>';	
			echo '<td>'.$gender.'</td>';	
			echo '<td>'.$age.'</td>';	
			echo '<td>'.$region.'</td>';	
			echo '<td>'.$sponsor_name.'</td>';	
			echo '<td style="text-align:right;">'.$quantity.'</td>';	
			echo '<td style="text-align:right;">'.number_format($revenue).'</td>';;	
		echo '</tr>';;			
		$sn++;
		}
		
		echo '<tfoot>';	
		echo '<tr>';	
			echo '<td></td>';	
			echo '<td></td>';	
			echo '<td></td>';	
			echo '<td></td>';	
			echo '<td></td>';	
			echo '<td></td>';	
			echo '<td><strong>TOTAL NO. OF TESTS:<span  style="float:right;"> '.number_format($total_tests).'</span></strong></td>';	
			echo '<td><strong>TOTAL REVENUE: <span  style="float:right;">'.number_format($total_revenue).'</span></strong></td>';;	
		echo '</tr>';;			
		echo '</tfoot>';;			
		//echo '<tr><td colspan=4><strong>'.number_format($total).'</strong></td></tr>';;	

	echo '</table>';
	
	function CalculateAge($dob){
		 //CALCULATE AGE
		$today = new DateTime(date('Y-m-d'));
		$Birth_Date = new DateTime($dob);
		$diff = $today -> diff($Birth_Date);
		$age = $diff->y." Years, ";
		$age.= $diff->m." Months and ";
		$age.= $diff->d." Days ";
		
		return $age;
	}
		
?>	