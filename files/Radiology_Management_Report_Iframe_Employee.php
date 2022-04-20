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
	if(isset($_GET['Product_Name'])) { $Product_Name = $_GET['Product_Name']; } else { $Product_Name = '' ;}
	if(isset($_GET['DateFrom'])) { $DateFrom = $_GET['DateFrom']; } else { $DateFrom = ''; }
	if(isset($_GET['DateTo'])) { $DateTo = $_GET['DateTo']; } else { $DateTo = ''; }
	if(isset($_GET['Item_ID'])) { $Item_ID = $_GET['Item_ID']; } else { $Item_ID = 0; }
	if(isset($_GET['Registration_ID'])) { $Registration_ID = $_GET['Registration_ID']; } else { $Registration_ID = 0; }
	
	$select_patient = mysqli_query($conn,"SELECT * FROM tbl_patient_registration WHERE Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
	$patient = mysqli_fetch_assoc($select_patient);
	$Sponsor_ID = $patient['Sponsor_ID'];
	
	$select_sponsor = mysqli_query($conn,"SELECT Guarantor_Name FROM tbl_sponsor WHERE Sponsor_ID = '$Sponsor_ID'") or die(mysqli_error($conn));
	$sponsor = mysqli_fetch_assoc($select_sponsor);
	$Sponsor_Name = $sponsor['Guarantor_Name'];
	
	if($Sponsor_Name == 'NHIF'){
		$Price_Col = 'Selling_Price_NHIF';
	} elseif($Sponsor_Name == 'CASH'){
		$Price_Col = 'Selling_Price_Cash';
	} else {
		$Price_Col = 'Selling_Price_Credit';
	}
	
	$Employee = 0;
	if(isset($_GET['Sonographer_ID'])) {
		$Sono_ID = $_GET['Sonographer_ID']; 
		$Employee = $Sono_ID;
	} else { 
		$Sono_ID = 0; 
	}
	if(isset($_GET['Radiologist_ID'])) { 
		$Radi_ID = $_GET['Radiologist_ID']; 
		$Employee = $Radi_ID;
	} else { 
		$Radi_ID = 0; 
	}
	$apID = 0;
	$filter = '';
	
	//SELECTING PATIENTS LIST				
	$SelectRadiItems = "
		SELECT *
			FROM 
			tbl_radiology_patient_tests rpt,
			tbl_items i
				WHERE
				rpt.Item_ID = '$Item_ID' AND
				i.Item_ID = rpt.Item_ID
	";

	$group_by = "";
	
	if($filter != ''){
		$SelectRadiItems_new = $SelectRadiItems.$group_by;
	} else {
		$SelectRadiItems_new = $SelectRadiItems.$group_by;		
	}
	
	echo '<table width="100%" id="reportID" class="display">
	<thead>
		<tr style="text-transform:uppercase; font-weight:bold;" class="thead">	
			<th style="width:3% !important;">SN</th>	
			<th>Date</th>
			<th>Radiologist</th>	
			<th>Imaging</th>	
			<th style="text-align:right;">Quantity</th>	
			<th style="text-align:right;">Revenue</th>	
		</tr>
	</thead>';	
	
	$SelectRadiItems_qry = mysqli_query($conn,$SelectRadiItems_new) or die(mysqli_error($conn));
	$sn = 1;
	$total_revenue=0;
	$total_tests=0;
	while($RadiTests = mysqli_fetch_assoc($SelectRadiItems_qry)){
		$Date = $RadiTests['Date_Time'];
		$Radiologist_ID = $RadiTests['Radiologist_ID'];
		$Sonographer_ID = $RadiTests['Sonographer_ID'];
		$quantity = 1;
		$revenue = $RadiTests[$Price_Col];
		$total_revenue += $revenue;
		$total_tests += $quantity;
		
		$select_radist = mysqli_query($conn,"SELECT Employee_Name FROM tbl_employee WHERE Employee_ID = '$Radiologist_ID'") or die(mysqli_error($conn));
		$radist = mysqli_fetch_assoc($select_radist);
		$Radiologist = $radist['Employee_Name'];
		
		$select_sonog = mysqli_query($conn,"SELECT Employee_Name FROM tbl_employee WHERE Employee_ID = '$Sonographer_ID'") or die(mysqli_error($conn));
		$sonog = mysqli_fetch_assoc($select_sonog);
		$Sonographer = $sonog['Employee_Name'];
		
		
		echo '<tr>';	
			echo '<td>'.$sn.'</td>';	
			echo '<td>'.$Date.'</td>';	
			echo '<td>'.$Radiologist.'</td>';	
			echo '<td>'.$Sonographer.'</td>';	
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
			echo '<td><strong>TOTAL NO. OF TESTS:<span  style="float:right;"> '.number_format($total_tests).'</span></strong></td>';	
			echo '<td><strong>TOTAL REVENUE: <span  style="float:right;">'.number_format($total_revenue).'</span></strong></td>';;	
		echo '</tr>';			
		echo '</tfoot>';

	echo '</table>';

		
?>	