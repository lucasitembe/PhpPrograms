<?php
@session_start();
include("./includes/connection.php");
$filter = ' ';
$filterIn = ' ';
$filterSub = ' ';
@$reportType=$_POST['reportType'];
@$fromDate =$_POST['fromDate'];
@$toDate=$_POST['toDate'];
@$Filter_Category=$_POST['Filter_Category'];
@$SubCategory = $_POST['SubCategory'];
@$search_top_n_diseases = $_POST['search_top_n_diseases'];
@$filter_top_n_diseases = $_POST['filter_top_n_diseases'];
@$opd_report_category=$_POST['opd_report_category'];
//opd attendance
if($reportType==='opd_reports'){
    if($opd_report_category==='opd_attendance')
    echo 'mzee am coming to you';
}
//search for the top n diseases
if(trim($search_top_n_diseases)!=='' && $filter_top_n_diseases=='yes'){
    @$bill_type = $_POST['bill_type'];
    $filter = "  and dc.Disease_Consultation_Date_And_Time between '$fromDate' and '$toDate' ";
    $filterIn = "  and wd.Round_Disease_Date_And_Time between '$fromDate' and '$toDate' '";
        $sqloutpatient = "select d.disease_name, d.disease_ID, d.disease_code
                                    from tbl_disease_consultation dc, tbl_disease d, tbl_consultation c,tbl_patient_registration pr where
                                    d.disease_ID = dc.disease_ID and
                                    c.consultation_ID = dc.consultation_ID and
                                    c.Registration_ID = pr.Registration_ID and 
                                    diagnosis_type IN ('diagnosis','provisional_diagnosis') $filter 
                                    group by d.disease_ID order by d.disease_name";

    //echo $sqloutpatient;exit;
    $result = mysqli_query($conn,$sqloutpatient) or die(mysqli_error($conn));
    $diseasesData=array();
    $sn=1;
    echo "<div style='background-color:white;font-size:12px;'>";
        echo "<table width='100%;'>";
            echo "<thead>";
            echo "<tr><th>SN</th><th>Disease Name</th><th>ICD</th><th>quantity</th></tr>";
            echo "</thead>";
            echo "<tbody>";

    while ($row = mysqli_fetch_array($result)) {

        $no_diagnosis = mysqli_fetch_assoc(mysqli_query($conn,"select count(dc.disease_ID) as amount
                                    from tbl_disease_consultation dc, tbl_disease d,tbl_consultation c,tbl_patient_registration pr where
                                    d.disease_ID = dc.disease_ID and
                                    c.consultation_ID = dc.consultation_ID and
                                    c.Registration_ID = pr.Registration_ID and 
                                    diagnosis_type = 'diagnosis' and
                                    d.disease_ID='" . $row['disease_ID'] . "' $filter"))['amount'];
        array_push($diseasesData, array(
                        'final_quantity'=>number_format($no_diagnosis),
                        'disease_code'=>trim($row['disease_code']),
                        'disease_name'=>trim($row['disease_name'])
                    ));

    }
    array_multisort($diseasesData,SORT_DESC);
    if(mysqli_num_rows(($result))){
		$top_diseases=(mysqli_num_rows($result)<$search_top_n_diseases)? mysqli_num_rows($result):$search_top_n_diseases;
        for ($i=0; $i<$top_diseases; $i++) {
            echo "<tr><td>{$sn}</td><td>{$diseasesData[$i]['disease_name']}</td><td style='text-align:center;'>{$diseasesData[$i]['disease_code']}</td><td style='text-align:center;'>{$diseasesData[$i]['final_quantity']}</td></tr>";  
            $sn++;          
        }
    }
        echo "</tbody>";
        echo "</table>";
        echo "</div>";

}
//display radiology report
if($reportType==='Radiology'){
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
}
//display procedure report
if($reportType==='Procedure'){
	echo "Procedure on the road";
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
if($reportType==='Laboratory'){
    $filter = "  WHERE tr.TimeSubmitted BETWEEN '" . $fromDate . "' AND '" . $toDate . "'";
	if ($SubCategory != 'All') {
        $filterSub .=" AND i.Item_Subcategory_ID='$SubCategory'";
    }
	@$laboratory_report_type=$_POST['laboratory_report_type'];
	if(isset($Filter_Category) && $Filter_Category=="yes"){
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

}}

if($reportType=="Pharmacy"){
    $Start_Date=$fromDate;
    $End_Date=$toDate;
    if(isset($_POST['Sub_Department_ID'])){
        $Sub_Department_ID = $_POST['Sub_Department_ID'];
    }else{
        $Sub_Department_ID = 0;
    }
    echo "<table width='100%' style='background-color:white;'>";
    echo "<thead>";
    echo "<tr><th>Description Of Item</th><th>UoM</th><th>Stock Beginning Balance</th><th>Stock Received</th><th>Remaining Balance</th></tr>";
    echo "</thead>";
        $select="SELECT sb.Item_Subcategory_ID,sb.Item_Subcategory_Name FROM `tbl_item_subcategory` sb INNER JOIN tbl_items i ON i.`Item_Subcategory_ID` = sb.`Item_Subcategory_ID` WHERE i.Consultation_Type='Pharmacy' GROUP BY i.`Item_Subcategory_ID` ORDER BY sb.Item_Subcategory_Name";
        $resulsts=mysqli_query($conn,$select) or die(mysqli_error($conn));
        $temp=1;
        while($row=mysqli_fetch_array($resulsts)){
            $subcategory_id=$row['Item_Subcategory_ID'];
            $subcategory_name=$row['Item_Subcategory_Name'];
            
            echo "<tr><td colspan='5' style='background-color:gray;color:white;'>{$subcategory_name}</td></tr>";

            $Total_Final_Inward=0;
            $Total_Final_Outward=0;
            $Grand_Final_Balance=0;

            $item_results=mysqli_query($conn,"SELECT * FROM tbl_items WHERE Item_Subcategory_ID={$subcategory_id}");
            while ($row=mysqli_fetch_assoc($item_results)) {
                $Item_ID=$row['Item_ID'];
                $Product_Name=$row['Product_Name'];

                //get details
                $select_sub_dept=mysqli_query($conn,"SELECT Sub_Department_ID FROM tbl_sub_department sdept, tbl_department dept WHERE sdept.Department_ID=dept.Department_ID and dept.Department_Name='Main Store'");
                
                while($cat=mysqli_fetch_assoc($select_sub_dept)){
                $select = mysqli_query($conn,"SELECT * FROM tbl_stock_ledger_controler
                           WHERE Movement_Date BETWEEN '$Start_Date' AND '$End_Date'
                           AND Item_ID = '$Item_ID' AND Sub_Department_ID={$cat['Sub_Department_ID']}
                           ORDER BY Controler_ID") or die(mysqli_error($conn));

                $no = mysqli_num_rows($select);
                $Pre_Balance1=0;

    if($no > 0){
        $controler = 'yes';
        $Total_inward = 0;
        $Total_outward = 0;
        $temp = 0;
        $Running_Balance = 0;

        while ($data = mysqli_fetch_array($select)) {
            $Movement_Type = $data['Movement_Type'];
            $Internal_Destination = $data['Internal_Destination'];
            $External_Source = $data['External_Source'];
            $Pre_Balance = $data['Pre_Balance'];
            $Movement_Date = $data['Movement_Date'];
            $Movement_Date_Time = $data['Movement_Date_Time'];
            $Registration_ID = $data['Registration_ID'];

            if($controler == 'yes'){
                $Opening_Balance=$Pre_Balance;
            }
            if($Movement_Type == 'From External'){

                $Total_inward += ($data['Post_Balance'] - $data['Pre_Balance']);
 
                $Grand_Balance = $data['Post_Balance'];
            }else if($Movement_Type == 'Without Purchase'){

                $Total_inward += ($data['Post_Balance'] - $data['Pre_Balance']);
 
                $Grand_Balance = $data['Post_Balance'];
            }else if($Movement_Type == 'Open Balance'){
                $Total_inward = $data['Post_Balance'];
                $Total_outward = 0;
 
                $Grand_Balance = $data['Post_Balance'];
            } else if($Movement_Type == 'Issue Note'){

                $Total_outward += ($data['Pre_Balance'] - $data['Post_Balance']);
                 
                $Grand_Balance = $data['Post_Balance'];
            } else if($Movement_Type == 'Dispensed'){

                $Total_outward += ($data['Pre_Balance'] - $data['Post_Balance']); 
               
                $Grand_Balance = $data['Post_Balance'];
            } else if($Movement_Type == 'GRN Agains Issue Note'){
 
                $Total_inward += ($data['Post_Balance'] - $data['Pre_Balance']);
                 
                $Grand_Balance = $data['Post_Balance'];
            } else if($Movement_Type == 'Disposal'){
 
                $Total_inward += 0;
                $Total_outward += ($data['Pre_Balance'] - $data['Post_Balance']);
                
                $Grand_Balance = $data['Post_Balance'];
            } else if($Movement_Type == 'Return Outward'){

                $Total_inward += 0;
                $Total_outward += ($data['Pre_Balance'] - $data['Post_Balance']);
                
                $Grand_Balance = $data['Post_Balance'];
            } else if($Movement_Type == 'Return Inward'){

                $Sub_Department = Get_Sub_Department($Internal_Destination);

                $Total_inward += ($data['Post_Balance'] - $data['Pre_Balance']);
                $Total_outward += 0;
                 
                $Grand_Balance = $data['Post_Balance'];
            } else if($Movement_Type == 'Return Inward Outward'){

                $Sub_Department = Get_Sub_Department($Internal_Destination);
                 
                $Total_inward += 0;
                $Total_outward += ($data['Pre_Balance'] - $data['Post_Balance']);
              
                $Grand_Balance = $data['Post_Balance'];
            } else if($Movement_Type == 'Issue Note Manual'){

                $Total_inward += 0;
                $Total_outward += ($data['Pre_Balance'] - $data['Post_Balance']);
                 
                $Grand_Balance = $data['Post_Balance'];
            }  else if($Movement_Type == 'Stock Taking Under'){

                $Total_inward += 0;
                $Total_outward += ($data['Pre_Balance'] - $data['Post_Balance']);
               
                $Grand_Balance = $data['Post_Balance'];
            } else if($Movement_Type == 'Stock Taking Over'){

                $Total_inward += ($data['Post_Balance'] - $data['Pre_Balance']);
                $Total_outward += 0;
               
                $Grand_Balance = $data['Post_Balance'];
            }else if($Movement_Type == 'Received From Issue Note Manual'){
                $Total_inward += ($data['Post_Balance'] - $data['Pre_Balance']);
                $Total_outward += 0;
 
                $Grand_Balance = $data['Post_Balance'];
            }
        }
         /*
        echo "<tr><td colspan='7'><hr></td></tr>";
        echo "<tr><td colspan='2'>
                <td style='text-align: right;'>".$Item_ID."</td>
                <td style='text-align: right;'>".$Product_Name."</td>
                <td style='text-align: right;'>".$Total_inward."</td>
                <td style='text-align: right;'>".$Total_outward."</td>
                <td style='text-align: right;'>".$Grand_Balance."&nbsp;&nbsp;</td>
                </tr>";
        echo "<tr><td colspan='7'><hr></td></tr>";
        echo "</table>";

        */
            $Total_Final_Inward=$Total_inward;
            $Total_Final_Outward=$Total_outward;
            $Grand_Final_Balance=$Grand_Balance;

            $receivedItems=($Grand_Final_Balance+$Total_Final_Outward)-$Total_Final_Inward;
           /* $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('B'.$i,$row['Product_Name'])
                ->setCellValue('C'.$i,$row['Unit_Of_Measure'])
                ->setCellValue('D'.$i,$receivedItems)
                ->setCellValue('E'.$i,$Total_Final_Inward)
                ->setCellValue('F'.$i,$Grand_Final_Balance);
*/
            echo "<tr><td>".$row['Product_Name']."</td><td>".$row['Unit_Of_Measure']."</td><td>".$receivedItems."</td><td>".$Total_Final_Inward."</td><td>".$Grand_Final_Balance."</td></tr>";
           
        }else{
            //Get Item Balance
            $select = mysqli_query($conn,"select Item_Balance from tbl_items_balance where Item_ID = '$Item_ID' and Sub_Department_ID = '$Sub_Department_ID'") or die(mysqli_error($conn));
            $result = mysqli_fetch_assoc($select);
            $Item_Balance = $result['Item_Balance'];
            $Date = mysqli_fetch_assoc(mysqli_query($conn,"select now() as Today"));
            }}
    }
}
    echo "</table>";
}
if($reportType==='Diseases'){
    @$Disease_Cat_Id=$_POST['Disease_Cat_Id'];
    @$Clinic_ID=$_POST['Clinic_ID'];
    @$bill_type=$_POST['bill_type'];
    @$disease_report_type=$_POST['disease_report_type'];
    @$start_age=$_POST['start_age'];
    @$end_age=$_POST['end_age'];
    if($Clinic_ID!='all'){
        $filter=" AND c.Clinic_ID=$Clinic_ID ";
        $filterIn=" AND cl.Clinic_ID=$Clinic_ID ";
    }
 ?>
<style type="text/css">
    #patientList td,th{
        text-align: center;
    }
</style>
<?php if(isset($Filter_Category) && $Filter_Category=="yes"){?>
<div id="type1_report" style="display:none; background-color:white;">
<?php
    
    echo "<br> <hr><table width='100%' id='patientList'>";
        echo "<thead>
             <tr >
                <th style='width:50%;' rowspan='3'>Diagnosis</th>
                <th style='width:40%;' colspan='6'><b>Number Of Patients</b></th>
                <th style='width:10%;' rowspan='3'><b>Total</b></th>
             </tr>
             <tr>
                <td colspan='3'>Age < $start_age </td>
                <td colspan='3'> Age &ge; $end_age </td>
                <td></td></tr>
             <tr>
                <td>M</td><td>F</td><td>T</td><td>M</td><td>F</td><td>T</td>
             </tr>
         </thead>";
        
    
    
    //Feeding the data start
    $diagnosisRow=4;
    //Total counts
    $total_less_male_count=0;
    $total_less_female_count=0;
    $total_greater_male_count=0;
    $total_greater_female_count=0;
    $grand_total=0;
    $select_diagnosis=mysqli_query($conn,"select d.disease_ID, d.disease_name from tbl_disease d,tbl_disease_category dc,tbl_disease_subcategory ds where dc.disease_category_ID=ds.disease_category_ID and d.subcategory_ID=ds.subcategory_ID and dc.disease_category_ID=$Disease_Cat_Id");
	if($bill_type=="Outpatient"){
       while ($row=mysqli_fetch_assoc($select_diagnosis)) {
        $disease_name=$row['disease_name'];
        $disease_ID=$row['disease_ID'];
        $less_male_count=0;
        $less_female_count=0;
        $greater_male_count=0;
        $greater_female_count=0;
        $less_male_count_result=mysqli_query($conn,"select count(distinct pr.Registration_ID) as counts from tbl_disease_consultation dc, tbl_disease d,tbl_consultation c,tbl_patient_registration pr WHERE
                                    d.disease_ID = dc.disease_ID and
                                    c.consultation_ID = dc.consultation_ID and
                                    c.Registration_ID = pr.Registration_ID and 
                                    dc.diagnosis_type = 'diagnosis' and
                                    d.disease_ID=$disease_ID and pr.Gender='Male' $filter and c.Consultation_Date_And_Time BETWEEN '$fromDate' and '$toDate' and TIMESTAMPDIFF(YEAR,Date_Of_Birth,CURDATE()) < $start_age");
        if($less_male_count_result)
        $less_male_count=mysqli_fetch_assoc($less_male_count_result)['counts'];

        $less_female_count_result=mysqli_query($conn,"select count(distinct pr.Registration_ID) as counts from tbl_disease_consultation dc, tbl_disease d,tbl_consultation c,tbl_patient_registration pr WHERE
                                    d.disease_ID = dc.disease_ID and
                                    c.consultation_ID = dc.consultation_ID and
                                    c.Registration_ID = pr.Registration_ID and 
                                    dc.diagnosis_type = 'diagnosis' and
                                    d.disease_ID=$disease_ID and pr.Gender='Female' $filter and c.Consultation_Date_And_Time BETWEEN '$fromDate' and '$toDate' and TIMESTAMPDIFF(YEAR,Date_Of_Birth,CURDATE()) < $start_age");
        if($less_female_count_result)
        $less_female_count=mysqli_fetch_assoc($less_female_count_result)['counts'];
        
        $greater_male_count_result=mysqli_query($conn,"select count(distinct pr.Registration_ID) as counts from tbl_disease_consultation dc, tbl_disease d,tbl_consultation c,tbl_patient_registration pr WHERE
                                    d.disease_ID = dc.disease_ID and
                                    c.consultation_ID = dc.consultation_ID and
                                    c.Registration_ID = pr.Registration_ID and 
                                    dc.diagnosis_type = 'diagnosis' and
                                    d.disease_ID=$disease_ID and pr.Gender='Male' $filter and c.Consultation_Date_And_Time BETWEEN '$fromDate' and '$toDate' and TIMESTAMPDIFF(YEAR,Date_Of_Birth,CURDATE()) >= $end_age");
        if($greater_male_count_result)
        $greater_male_count=mysqli_fetch_assoc($greater_male_count_result)['counts'];
        
        $greater_female_count_result=mysqli_query($conn,"select count(distinct pr.Registration_ID) as counts from tbl_disease_consultation dc, tbl_disease d,tbl_consultation c,tbl_patient_registration pr WHERE
                                    d.disease_ID = dc.disease_ID and
                                    c.consultation_ID = dc.consultation_ID and
                                    c.Registration_ID = pr.Registration_ID and 
                                    dc.diagnosis_type = 'diagnosis' and
                                    d.disease_ID=$disease_ID and pr.Gender='Female' $filter and c.Consultation_Date_And_Time BETWEEN '$fromDate' and '$toDate' and TIMESTAMPDIFF(YEAR,Date_Of_Birth,CURDATE()) >= $end_age");
        if($greater_female_count_result)
        $greater_female_count=mysqli_fetch_assoc($greater_female_count_result)['counts'];
        $patients_details = array('disease_ID' => $disease_ID,'disease_name'=>$disease_name,'fromDate'=>$fromDate,'toDate'=>$toDate,'start_age'=>$start_age,'end_age'=>$end_age,'Clinic_ID'=>$Clinic_ID,'patient_type'=>$bill_type);
        $patients_object=json_encode($patients_details);
        echo "<tr>";
            echo "<td style='text-align:left;'><a href='#' onclick='viewPatientList(".$patients_object.");'>".$disease_name."</a></td>";
            echo "<td>".$less_male_count."</td>";
            echo "<td>".$less_female_count."</td>";
            echo "<td>".($less_male_count+$less_female_count)."</td>";
            echo "<td>".$greater_male_count."</td>";
            echo "<td>".$greater_female_count."</td>";
            echo "<td>".($greater_male_count+$greater_female_count)."</td>";
            echo "<td>".($less_male_count+$less_female_count+$greater_male_count+$greater_female_count)."</td>";
        echo "</tr>";
        $patients_details=array();
                
                $total_less_male_count+=$less_male_count;
                $total_less_female_count+=$less_female_count;
                $total_greater_male_count+=$greater_male_count;
                $total_greater_female_count+=$greater_female_count;
        }
        
    }
	if($bill_type=="Inpatient"){
    while ($row=mysqli_fetch_assoc($select_diagnosis)) {
        $disease_name=$row['disease_name'];
        $disease_ID=$row['disease_ID'];
        $less_male_count=mysqli_fetch_assoc(mysqli_query($conn,"select count(distinct pr.Registration_ID) as counts
                                    from tbl_ward_round_disease wd, tbl_disease d,tbl_ward_round wr,tbl_patient_registration pr,tbl_consultation c,tbl_clinic cl where
									wr.consultation_ID=c.consultation_ID AND
                                    d.disease_ID = wd.disease_ID and
                                    wr.Round_ID = wd.Round_ID and
                                    wr.Registration_ID = pr.Registration_ID and
                                    diagnosis_type = 'diagnosis' and 
                                    pr.Gender='Male' and
									cl.Clinic_ID=c.Clinic_ID and
                                    d.disease_name='$disease_name' $filterIn  and wd.Round_Disease_Date_And_Time between '$fromDate' and '$toDate' and  TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,CURDATE()) < $start_age "))['counts'];

        $less_female_count=mysqli_fetch_assoc(mysqli_query($conn,"select count(distinct pr.Registration_ID) as counts
                                    from tbl_ward_round_disease wd, tbl_disease d,tbl_ward_round wr,tbl_patient_registration pr,tbl_consultation c,tbl_clinic cl where
									wr.consultation_ID=c.consultation_ID AND
                                    d.disease_ID = wd.disease_ID and
                                    wr.Round_ID = wd.Round_ID and
                                    wr.Registration_ID = pr.Registration_ID and
                                    diagnosis_type = 'diagnosis' and 
                                    pr.Gender='Female' and
									cl.Clinic_ID=c.Clinic_ID and
                                    d.disease_name='$disease_name' $filterIn  and wd.Round_Disease_Date_And_Time between '$fromDate' and '$toDate' and  TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,CURDATE()) < $start_age"))['counts'];

        $greater_male_count=mysqli_fetch_assoc(mysqli_query($conn,"select count(distinct pr.Registration_ID) as counts
                                    from tbl_ward_round_disease wd, tbl_disease d,tbl_ward_round wr,tbl_patient_registration pr,tbl_consultation c,tbl_clinic cl where
									wr.consultation_ID=c.consultation_ID AND
                                    d.disease_ID = wd.disease_ID and
                                    wr.Round_ID = wd.Round_ID and
                                    wr.Registration_ID = pr.Registration_ID and
                                    diagnosis_type = 'diagnosis' and 
                                    pr.Gender='Male' and
									cl.Clinic_ID=c.Clinic_ID and
                                    d.disease_name='$disease_name' $filterIn  and wd.Round_Disease_Date_And_Time between '$fromDate' and '$toDate' and  TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,CURDATE()) >= $end_age"))['counts'];

        $greater_female_count=mysqli_fetch_assoc(mysqli_query($conn,"select count(distinct pr.Registration_ID) as counts
                                    from tbl_ward_round_disease wd, tbl_disease d,tbl_ward_round wr,tbl_patient_registration pr,tbl_consultation c,tbl_clinic cl where
									wr.consultation_ID=c.consultation_ID AND
                                    d.disease_ID = wd.disease_ID and
                                    wr.Round_ID = wd.Round_ID and
                                    wr.Registration_ID = pr.Registration_ID and
                                    diagnosis_type = 'diagnosis' and 
                                    pr.Gender='Female' and
									cl.Clinic_ID=c.Clinic_ID and
                                    d.disease_name='$disease_name' $filterIn  and wd.Round_Disease_Date_And_Time between '$fromDate' and '$toDate' and  TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,CURDATE()) >= $end_age"))['counts'];
        $patients_details = array('disease_ID' => $disease_ID,'disease_name'=>$disease_name,'fromDate'=>$fromDate,'toDate'=>$toDate,'start_age'=>$start_age,'end_age'=>$end_age,'Clinic_ID'=>$Clinic_ID,'patient_type'=>$bill_type);
        $patients_object=json_encode($patients_details);
        echo "<tr>";
            echo "<td style='text-align:left;'><a href='#' onclick='viewPatientList(".$patients_object.");'>".$disease_name."</a></td>";
            echo "<td>".$less_male_count."</td>";
            echo "<td>".$less_female_count."</td>";
            echo "<td>".($less_male_count+$less_female_count)."</td>";
            echo "<td>".$greater_male_count."</td>";
            echo "<td>".$greater_female_count."</td>";
            echo "<td>".($greater_male_count+$greater_female_count)."</td>";
            echo "<td>".($less_male_count+$less_female_count+$greater_male_count+$greater_female_count)."</td>";
        echo "</tr>";
		
                $total_less_male_count+=$less_male_count;
                $total_less_female_count+=$less_female_count;
                $total_greater_male_count+=$greater_male_count;
                $total_greater_female_count+=$greater_female_count;

    }
	}
	$grand_total+=$total_less_male_count+$total_less_female_count+$total_greater_male_count+$total_greater_female_count;
        echo "<tr><td colspan='8'><hr></td></tr>";
        echo "<tr>";
            echo "<td style='text-align:left;'> Total </td>";
            echo "<td>".$total_less_male_count."</td>";
            echo "<td>".$total_less_female_count."</td>";
            echo "<td>".($total_less_male_count+$total_less_female_count)."</td>";
            echo "<td>".$total_greater_male_count."</td>";
            echo "<td>".$total_greater_female_count."</td>";
            echo "<td>".($total_greater_male_count+$total_greater_female_count)."</td>";
            echo "<td>".$grand_total."</td>";
        echo "</tr>";
        echo "<tr><td colspan='8'><hr></td></tr>";
        echo "</table>";
?>
</div>
<div id="type2_report" style="display:none;background:white;">
<?php
    
    echo "<br> <hr><table width='100%' id='patientList'>";
        echo "<thead>
             <tr >
                <th style='width:50%;' rowspan='4'>Diagnosis</th>
                <th style='width:40%;' colspan='12'><b>Number Of Patients</b></th>
                <th style='width:10%;' rowspan='4'><b>Total</b></th>
             </tr>
             <tr>
                <td colspan='6'>Age < $start_age </td>
                <td colspan='6'> Age &ge; $end_age </td>
             </tr>
             <tr><td colspan='3'>new</td><td colspan='3'>return</td><td colspan='3'>new</td><td colspan='3'>return</td></tr>
             <tr>
                <td>M</td><td>F</td><td>T</td><td>M</td><td>F</td><td>T</td><td>M</td><td>F</td><td>T</td><td>M</td><td>F</td><td>T</td>
             </tr>
         </thead>";
        
         $diagnosisRow=5;
    //Total counts

$sub_total_less_male_count_new=0;
$sub_total_less_male_count_return=0;
$sub_total_less_female_count_new=0;
$sub_total_less_female_count_return=0;
$sub_total_greater_male_count_new=0;
$sub_total_greater_male_count_return=0;
$sub_total_greater_female_count_new=0;
$sub_total_greater_female_count_return=0;

$grand_total_new=0;
$grand_total_return=0;
$grand_total=0;
$select_diagnosis=mysqli_query($conn,"select d.disease_ID, d.disease_name from tbl_disease d,tbl_disease_category dc,tbl_disease_subcategory ds where dc.disease_category_ID=ds.disease_category_ID and d.subcategory_ID=ds.subcategory_ID and dc.disease_category_ID=$Disease_Cat_Id");
if($bill_type=="Outpatient"){
    while($row=mysqli_fetch_assoc($select_diagnosis)){
        $disease_ID=$row['disease_ID'];
        $disease_name=$row['disease_name'];
        $total_less_male_count_new=0;
        $total_less_male_count_return=0;
        $total_less_female_count_new=0;
        $total_less_female_count_return=0;
        $total_greater_male_count_new=0;
        $total_greater_male_count_return=0;
        $total_greater_female_count_new=0;
        $total_greater_female_count_return=0;
        //patients less than x age 
        //new patients male
        $less_male_count_new=mysqli_query($conn,"select count(distinct pr.Registration_ID) AS counts from tbl_disease_consultation dc, tbl_disease d,tbl_consultation c,tbl_patient_registration pr WHERE d.disease_ID = dc.disease_ID and c.consultation_ID = dc.consultation_ID and c.Registration_ID = pr.Registration_ID and dc.diagnosis_type = 'diagnosis' and d.disease_ID=$disease_ID and pr.Gender='Male' $filter and c.Consultation_Date_And_Time BETWEEN '$fromDate' and '$toDate' and TIMESTAMPDIFF(YEAR,Date_Of_Birth,CURDATE()) < $start_age GROUP BY c.Registration_ID HAVING ( COUNT(c.Registration_ID) = 1 ) ");
        if($less_male_count_new)
            while ($row=mysqli_fetch_assoc($less_male_count_new)) {
                $total_less_male_count_new+=$row['counts'];
            }
        //new patients female
        $less_female_count_new=mysqli_query($conn,"select count(distinct pr.Registration_ID) AS counts from tbl_disease_consultation dc, tbl_disease d,tbl_consultation c,tbl_patient_registration pr WHERE d.disease_ID = dc.disease_ID and c.consultation_ID = dc.consultation_ID and c.Registration_ID = pr.Registration_ID and dc.diagnosis_type = 'diagnosis' and d.disease_ID=$disease_ID and pr.Gender='Female' $filter and c.Consultation_Date_And_Time BETWEEN '$fromDate' and '$toDate' and TIMESTAMPDIFF(YEAR,Date_Of_Birth,CURDATE()) < $start_age GROUP BY c.Registration_ID HAVING ( COUNT(c.Registration_ID) = 1 ) ");
        if($less_female_count_new)
            while ($row=mysqli_fetch_assoc($less_female_count_new)) {
                $total_less_female_count_new+=$row['counts'];
            }
        //return patient male
        $less_male_count_return=mysqli_query($conn,"select count(distinct pr.Registration_ID) AS counts from tbl_disease_consultation dc, tbl_disease d,tbl_consultation c,tbl_patient_registration pr WHERE d.disease_ID = dc.disease_ID and c.consultation_ID = dc.consultation_ID and c.Registration_ID = pr.Registration_ID and dc.diagnosis_type = 'diagnosis' and d.disease_ID=$disease_ID and pr.Gender='Male' $filter and c.Consultation_Date_And_Time BETWEEN '$fromDate' and '$toDate' and TIMESTAMPDIFF(YEAR,Date_Of_Birth,CURDATE()) < $start_age GROUP BY c.Registration_ID HAVING ( COUNT(c.Registration_ID) > 1 ) ");
        if($less_male_count_return)
        while ($row=mysqli_fetch_assoc($less_male_count_return)) {
            $total_less_male_count_return+=$row['counts'];
        }
        //return patient female
        $less_female_count_return=mysqli_query($conn,"select count(distinct pr.Registration_ID) AS counts from tbl_disease_consultation dc, tbl_disease d,tbl_consultation c,tbl_patient_registration pr WHERE d.disease_ID = dc.disease_ID and c.consultation_ID = dc.consultation_ID and c.Registration_ID = pr.Registration_ID and dc.diagnosis_type = 'diagnosis' and d.disease_ID=$disease_ID and pr.Gender='Female' $filter and c.Consultation_Date_And_Time BETWEEN '$fromDate' and '$toDate' and TIMESTAMPDIFF(YEAR,Date_Of_Birth,CURDATE()) < $start_age GROUP BY c.Registration_ID HAVING ( COUNT(c.Registration_ID) > 1 ) ");
        if($less_female_count_return)
            while ($row=mysqli_fetch_assoc($less_female_count_return)) {
                $total_less_female_count_return+=$row['counts'];
            }
        //patints greater than x age
        //new patients male
        $greater_male_count_new=mysqli_query($conn,"select count(distinct pr.Registration_ID) AS counts from tbl_disease_consultation dc, tbl_disease d,tbl_consultation c,tbl_patient_registration pr WHERE d.disease_ID = dc.disease_ID and c.consultation_ID = dc.consultation_ID and c.Registration_ID = pr.Registration_ID and dc.diagnosis_type = 'diagnosis' and d.disease_ID=$disease_ID and pr.Gender='Male' $filter and c.Consultation_Date_And_Time BETWEEN '$fromDate' and '$toDate' and TIMESTAMPDIFF(YEAR,Date_Of_Birth,CURDATE()) >= $end_age GROUP BY c.Registration_ID HAVING ( COUNT(c.Registration_ID) = 1 ) ");
        if($greater_male_count_new)
            while ($row=mysqli_fetch_assoc($greater_male_count_new)) {
                $total_greater_male_count_new+=$row['counts'];
            }
        //new patients female
        $greater_female_count_new=mysqli_query($conn,"select count(distinct pr.Registration_ID) AS counts from tbl_disease_consultation dc, tbl_disease d,tbl_consultation c,tbl_patient_registration pr WHERE d.disease_ID = dc.disease_ID and c.consultation_ID = dc.consultation_ID and c.Registration_ID = pr.Registration_ID and dc.diagnosis_type = 'diagnosis' and d.disease_ID=$disease_ID and pr.Gender='Female' $filter and c.Consultation_Date_And_Time BETWEEN '$fromDate' and '$toDate' and TIMESTAMPDIFF(YEAR,Date_Of_Birth,CURDATE()) >= $end_age GROUP BY c.Registration_ID HAVING ( COUNT(c.Registration_ID) = 1 ) ");
        if($greater_female_count_new)
            while ($row=mysqli_fetch_assoc($greater_female_count_new)) {
                $total_greater_female_count_new+=$row['counts'];
            }
        //return patient male
        $greater_male_count_return=mysqli_query($conn,"select count(distinct pr.Registration_ID) AS counts from tbl_disease_consultation dc, tbl_disease d,tbl_consultation c,tbl_patient_registration pr WHERE d.disease_ID = dc.disease_ID and c.consultation_ID = dc.consultation_ID and c.Registration_ID = pr.Registration_ID and dc.diagnosis_type = 'diagnosis' and d.disease_ID=$disease_ID and pr.Gender='Male' $filter and c.Consultation_Date_And_Time BETWEEN '$fromDate' and '$toDate' and TIMESTAMPDIFF(YEAR,Date_Of_Birth,CURDATE()) >= $end_age GROUP BY c.Registration_ID HAVING ( COUNT(c.Registration_ID) > 1 ) ");
        if($greater_male_count_return)
            while ($row=mysqli_fetch_assoc($greater_male_count_return)) {
                $total_greater_male_count_return+=$row['counts'];
            }
        //return patient female
        $greater_female_count_return=mysqli_query($conn,"select count(distinct pr.Registration_ID) AS counts from tbl_disease_consultation dc, tbl_disease d,tbl_consultation c,tbl_patient_registration pr WHERE d.disease_ID = dc.disease_ID and c.consultation_ID = dc.consultation_ID and c.Registration_ID = pr.Registration_ID and dc.diagnosis_type = 'diagnosis' and d.disease_ID=$disease_ID and pr.Gender='Female' $filter and c.Consultation_Date_And_Time BETWEEN '$fromDate' and '$toDate' and TIMESTAMPDIFF(YEAR,Date_Of_Birth,CURDATE()) >= $end_age GROUP BY c.Registration_ID HAVING ( COUNT(c.Registration_ID) > 1 ) ");
        if($greater_female_count_return)
            while ($row=mysqli_fetch_assoc($greater_female_count_return)) {
                $total_greater_female_count_return+=$row['counts'];
            }
            $patients_details = array('disease_ID' => $disease_ID,'disease_name'=>$disease_name,'fromDate'=>$fromDate,'toDate'=>$toDate,'start_age'=>$start_age,'end_age'=>$end_age,'Clinic_ID'=>$Clinic_ID,'patient_type'=>$bill_type);
            $patients_object=json_encode($patients_details);
            echo "<tr>";
                echo "<td style='text-align:left;'><a href='#' onclick='viewPatientList(".$patients_object.");'>".$disease_name."</a></td>";
                echo "<td>".$total_less_male_count_new."</td>";
                echo "<td>".$total_less_female_count_new."</td>";
                echo "<td>".($total_less_male_count_new+$total_less_female_count_new)."</td>";
                echo "<td>".$total_less_male_count_return."</td>";
                echo "<td>".$total_less_female_count_return."</td>";
                echo "<td>".($total_less_male_count_return+$total_less_female_count_return)."</td>";
                echo "<td>".$total_greater_male_count_new."</td>";
                echo "<td>".$total_greater_female_count_new."</td>";
                echo "<td>".($total_greater_male_count_new+$total_greater_female_count_new)."</td>";
                echo "<td>".$total_greater_male_count_return."</td>";
                echo "<td>".$total_greater_female_count_return."</td>";
                echo "<td>".($total_greater_male_count_return+$total_greater_female_count_return)."</td>";
                echo "<td>".($total_less_male_count_new+$total_less_female_count_new+$total_less_male_count_return+$total_less_female_count_return+$total_greater_male_count_new+$total_greater_female_count_new+$total_greater_male_count_return+$total_greater_female_count_return)."</td>";
            echo "</tr>"; 
                $sub_total_less_male_count_new+=$total_less_male_count_new;
                $sub_total_less_female_count_new+=$total_less_female_count_new;
                $sub_total_less_male_count_return+=$total_less_male_count_return;
                $sub_total_less_female_count_return+=$total_less_female_count_return;
                $sub_total_greater_male_count_new+=$total_greater_male_count_new;
                $sub_total_greater_female_count_new+=$total_greater_female_count_new;
                $sub_total_greater_male_count_return+=$total_greater_male_count_return;
                $sub_total_greater_female_count_return+=$total_greater_female_count_return;
    }

}
if($bill_type=="Inpatient"){
	    while($row=mysqli_fetch_assoc($select_diagnosis)){
        $disease_name=$row['disease_name'];
        $disease_ID=$row['disease_ID'];
        $total_less_male_count_new=0;
        $total_less_male_count_return=0;
        $total_less_female_count_new=0;
        $total_less_female_count_return=0;
        $total_greater_male_count_new=0;
        $total_greater_male_count_return=0;
        $total_greater_female_count_new=0;
        $total_greater_female_count_return=0;
        //patients less than x age 
        //new patients male
        $less_male_count_new=mysqli_query($conn,"select count(distinct pr.Registration_ID) as counts
                                    from tbl_ward_round_disease wd, tbl_disease d,tbl_ward_round wr,tbl_patient_registration pr,tbl_consultation c,tbl_clinic cl where
									wr.consultation_ID=c.consultation_ID AND
                                    d.disease_ID = wd.disease_ID and
                                    wr.Round_ID = wd.Round_ID and
                                    wr.Registration_ID = pr.Registration_ID and
                                    diagnosis_type = 'diagnosis' and 
                                    pr.Gender='Male' and
									cl.Clinic_ID=c.Clinic_ID and
                                    d.disease_name='$disease_name' $filterIn  and wd.Round_Disease_Date_And_Time between '$fromDate' and '$toDate' and  TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,CURDATE()) < $start_age  
									GROUP BY wr.Registration_ID HAVING ( COUNT(wr.Registration_ID) = 1 )");
        if($less_male_count_new)
            while ($row=mysqli_fetch_assoc($less_male_count_new)) {
                $total_less_male_count_new+=$row['counts'];
            }
        //new patients female
        $less_female_count_new=mysqli_query($conn,"select count(distinct pr.Registration_ID) as counts
                                    from tbl_ward_round_disease wd, tbl_disease d,tbl_ward_round wr,tbl_patient_registration pr,tbl_consultation c,tbl_clinic cl where
									wr.consultation_ID=c.consultation_ID AND
                                    d.disease_ID = wd.disease_ID and
                                    wr.Round_ID = wd.Round_ID and
                                    wr.Registration_ID = pr.Registration_ID and
                                    diagnosis_type = 'diagnosis' and 
                                    pr.Gender='Female' and
									cl.Clinic_ID=c.Clinic_ID and
                                    d.disease_name='$disease_name' $filterIn  and wd.Round_Disease_Date_And_Time between '$fromDate' and '$toDate' and  TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,CURDATE()) < $start_age  
									GROUP BY wr.Registration_ID HAVING ( COUNT(wr.Registration_ID) = 1 )");
        if($less_female_count_new)
            while ($row=mysqli_fetch_assoc($less_female_count_new)) {
                $total_less_female_count_new+=$row['counts'];
            }
        //return patient male
        $less_male_count_return=mysqli_query($conn,"select count(distinct pr.Registration_ID) as counts
                                    from tbl_ward_round_disease wd, tbl_disease d,tbl_ward_round wr,tbl_patient_registration pr,tbl_consultation c,tbl_clinic cl where
									wr.consultation_ID=c.consultation_ID AND
                                    d.disease_ID = wd.disease_ID and
                                    wr.Round_ID = wd.Round_ID and
                                    wr.Registration_ID = pr.Registration_ID and
                                    diagnosis_type = 'diagnosis' and 
                                    pr.Gender='Male' and
									cl.Clinic_ID=c.Clinic_ID and
                                    d.disease_name='$disease_name' $filterIn  and wd.Round_Disease_Date_And_Time between '$fromDate' and '$toDate' and  TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,CURDATE()) < $start_age  
									GROUP BY wr.Registration_ID HAVING ( COUNT(wr.Registration_ID) > 1 )");
        if($less_male_count_return)
        while ($row=mysqli_fetch_assoc($less_male_count_return)) {
            $total_less_male_count_return+=$row['counts'];
        }
        //return patient female
        $less_female_count_return=mysqli_query($conn,"select count(distinct pr.Registration_ID) as counts
                                    from tbl_ward_round_disease wd, tbl_disease d,tbl_ward_round wr,tbl_patient_registration pr,tbl_consultation c,tbl_clinic cl where
									wr.consultation_ID=c.consultation_ID AND
                                    d.disease_ID = wd.disease_ID and
                                    wr.Round_ID = wd.Round_ID and
                                    wr.Registration_ID = pr.Registration_ID and
                                    diagnosis_type = 'diagnosis' and 
                                    pr.Gender='Female' and
									cl.Clinic_ID=c.Clinic_ID and
                                    d.disease_name='$disease_name' $filterIn  and wd.Round_Disease_Date_And_Time between '$fromDate' and '$toDate' and  TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,CURDATE()) < $start_age  
									GROUP BY wr.Registration_ID HAVING ( COUNT(wr.Registration_ID) > 1 )");
        if($less_female_count_return)
            while ($row=mysqli_fetch_assoc($less_female_count_return)) {
                $total_less_female_count_return+=$row['counts'];
            }
        //patints greater than x age
        //new patients male
        $greater_male_count_new=mysqli_query($conn,"select count(distinct pr.Registration_ID) as counts
                                    from tbl_ward_round_disease wd, tbl_disease d,tbl_ward_round wr,tbl_patient_registration pr,tbl_consultation c,tbl_clinic cl where
									wr.consultation_ID=c.consultation_ID AND
                                    d.disease_ID = wd.disease_ID and
                                    wr.Round_ID = wd.Round_ID and
                                    wr.Registration_ID = pr.Registration_ID and
                                    diagnosis_type = 'diagnosis' and 
                                    pr.Gender='Male' and
									cl.Clinic_ID=c.Clinic_ID and
                                    d.disease_name='$disease_name'  $filterIn and wd.Round_Disease_Date_And_Time between '$fromDate' and '$toDate' and  TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,CURDATE()) >= $end_age  
									GROUP BY wr.Registration_ID HAVING ( COUNT(wr.Registration_ID) = 1 )");
        if($greater_male_count_new)
            while ($row=mysqli_fetch_assoc($greater_male_count_new)) {
                $total_greater_male_count_new+=$row['counts'];
            }
        //new patients female
        $greater_female_count_new=mysqli_query($conn,"select count(distinct pr.Registration_ID) as counts
                                    from tbl_ward_round_disease wd, tbl_disease d,tbl_ward_round wr,tbl_patient_registration pr,tbl_consultation c,tbl_clinic cl where
									wr.consultation_ID=c.consultation_ID AND
                                    d.disease_ID = wd.disease_ID and
                                    wr.Round_ID = wd.Round_ID and
                                    wr.Registration_ID = pr.Registration_ID and
                                    diagnosis_type = 'diagnosis' and 
                                    pr.Gender='Female' and
									cl.Clinic_ID=c.Clinic_ID and
                                    d.disease_name='$disease_name' $filterIn  and wd.Round_Disease_Date_And_Time between '$fromDate' and '$toDate' and  TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,CURDATE()) >= $end_age  
									GROUP BY wr.Registration_ID HAVING ( COUNT(wr.Registration_ID) = 1 )");
        if($greater_female_count_new)
            while ($row=mysqli_fetch_assoc($greater_female_count_new)) {
                $total_greater_female_count_new+=$row['counts'];
            }
        //return patient male
        $greater_male_count_return=mysqli_query($conn,"select count(distinct pr.Registration_ID) as counts
                                    from tbl_ward_round_disease wd, tbl_disease d,tbl_ward_round wr,tbl_patient_registration pr,tbl_consultation c,tbl_clinic cl where
									wr.consultation_ID=c.consultation_ID AND
                                    d.disease_ID = wd.disease_ID and
                                    wr.Round_ID = wd.Round_ID and
                                    wr.Registration_ID = pr.Registration_ID and
                                    diagnosis_type = 'diagnosis' and 
                                    pr.Gender='Male' and
									cl.Clinic_ID=c.Clinic_ID and
                                    d.disease_name='$disease_name' $filterIn  and wd.Round_Disease_Date_And_Time between '$fromDate' and '$toDate' and  TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,CURDATE()) >= $end_age  
									GROUP BY wr.Registration_ID HAVING ( COUNT(wr.Registration_ID) > 1 )");
        if($greater_male_count_return)
            while ($row=mysqli_fetch_assoc($greater_male_count_return)) {
                $total_greater_male_count_return+=$row['counts'];
            }
        //return patient female
        $greater_female_count_return=mysqli_query($conn,"select count(distinct pr.Registration_ID) as counts
                                    from tbl_ward_round_disease wd, tbl_disease d,tbl_ward_round wr,tbl_patient_registration pr,tbl_consultation c,tbl_clinic cl where
									wr.consultation_ID=c.consultation_ID AND
                                    d.disease_ID = wd.disease_ID and
                                    wr.Round_ID = wd.Round_ID and
                                    wr.Registration_ID = pr.Registration_ID and
                                    diagnosis_type = 'diagnosis' and 
                                    pr.Gender='Female' and
									cl.Clinic_ID=c.Clinic_ID and
                                    d.disease_name='$disease_name' $filterIn  and wd.Round_Disease_Date_And_Time between '$fromDate' and '$toDate' and  TIMESTAMPDIFF(YEAR,pr.Date_Of_Birth,CURDATE()) >= $end_age  
									GROUP BY wr.Registration_ID HAVING ( COUNT(wr.Registration_ID) > 1 )");
        if($greater_female_count_return)
            while ($row=mysqli_fetch_assoc($greater_female_count_return)) {
                $total_greater_female_count_return+=$row['counts'];
            }
            $patients_details = array('disease_ID' => $disease_ID,'disease_name'=>$disease_name,'fromDate'=>$fromDate,'toDate'=>$toDate,'start_age'=>$start_age,'end_age'=>$end_age,'Clinic_ID'=>$Clinic_ID,'patient_type'=>$bill_type);
        $patients_object=json_encode($patients_details);
        echo "<tr>";
                echo "<td style='text-align:left;'><a href='#' onclick='viewPatientList(".$patients_object.");'>".$disease_name."</a></td>";
                echo "<td>".$total_less_male_count_new."</td>";
                echo "<td>".$total_less_female_count_new."</td>";
                echo "<td>".($total_less_male_count_new+$total_less_female_count_new)."</td>";
                echo "<td>".$total_less_male_count_return."</td>";
                echo "<td>".$total_less_female_count_return."</td>";
                echo "<td>".($total_less_male_count_return+$total_less_female_count_return)."</td>";
                echo "<td>".$total_greater_male_count_new."</td>";
                echo "<td>".$total_greater_female_count_new."</td>";
                echo "<td>".($total_greater_male_count_new+$total_greater_female_count_new)."</td>";
                echo "<td>".$total_greater_male_count_return."</td>";
                echo "<td>".$total_greater_female_count_return."</td>";
                echo "<td>".($total_greater_male_count_return+$total_greater_female_count_return)."</td>";
                echo "<td>".($total_less_male_count_new+$total_less_female_count_new+$total_less_male_count_return+$total_less_female_count_return+$total_greater_male_count_new+$total_greater_female_count_new+$total_greater_male_count_return+$total_greater_female_count_return)."</td>";
            echo "</tr>"; 
                
                $sub_total_less_male_count_new+=$total_less_male_count_new;
                $sub_total_less_female_count_new+=$total_less_female_count_new;
                $sub_total_less_male_count_return+=$total_less_male_count_return;
                $sub_total_less_female_count_return+=$total_less_female_count_return;
                $sub_total_greater_male_count_new+=$total_greater_male_count_new;
                $sub_total_greater_female_count_new+=$total_greater_female_count_new;
                $sub_total_greater_male_count_return+=$total_greater_male_count_return;
                $sub_total_greater_female_count_return+=$total_greater_female_count_return;
    }
}

    $grand_total=$sub_total_less_male_count_new+$sub_total_less_male_count_return+$sub_total_less_female_count_new+$sub_total_less_female_count_return+$sub_total_greater_male_count_new+$sub_total_greater_male_count_return+$sub_total_greater_female_count_new+$sub_total_greater_female_count_return;
    echo "<tr><td colspan='14'><hr></td></tr>";
    echo "<tr>";
        echo "<td style='text-align:left;'> Total</td>";
        echo "<td>".$sub_total_less_male_count_new."</td>";
        echo "<td>".$sub_total_less_female_count_new."</td>";
        echo "<td>".($sub_total_less_male_count_new+$sub_total_less_female_count_new)."</td>";
        echo "<td>".$sub_total_less_male_count_return."</td>";
        echo "<td>".$sub_total_less_female_count_return."</td>";
        echo "<td>".($sub_total_less_male_count_return+$sub_total_less_female_count_return)."</td>";
        echo "<td>".$sub_total_greater_male_count_new."</td>";
        echo "<td>".$sub_total_greater_female_count_new."</td>";
        echo "<td>".($sub_total_greater_male_count_new+$sub_total_greater_female_count_new)."</td>";
        echo "<td>".$sub_total_greater_male_count_return."</td>";
        echo "<td>".$sub_total_greater_female_count_return."</td>";
        echo "<td>".($sub_total_greater_male_count_return+$sub_total_greater_female_count_return)."</td>";
            echo "<td>".$grand_total."</td>";
        echo "</tr>";
        echo "<tr><td colspan='14'><hr></td></tr>";
    echo "</table>";
}}
if($reportType=="Death"){
	$Clinic_ID=$_POST['Clinic_ID'];
    $death_ward=$_POST['death_ward'];
    $filterDeathWard=' ';
    if($death_ward!=='all'){
        $filterDeathWard =" AND ad.Hospital_Ward_ID='$death_ward'";
    }
	@$start_age_death=$_POST['start_age_death'];
	@$end_age_death=$_POST['end_age_death'];
	//$result = mysqli_query($conn,$sqloutpatient) or die(mysqli_error($conn));
    $diseasesData=array();
    $sn=1;
	$total_less_age_male_death=0;
	$total_less_age_female_death=0;
	$total_greater_age_male_death=0;
	$total_greater_age_female_death=0;
	$grandTotal=0;
	
    $disease_caused_death_select=mysqli_query($conn,"SELECT DISTINCT dcd.disease_name from tbl_admission ad, tbl_patient_registration pr, tbl_disease_caused_death dcd where pr.Registration_ID=ad.Registration_ID and ad.Admision_ID=dcd.Admision_ID and pr.Registration_ID=dcd.Registration_ID and ad.death_date_time BETWEEN '$fromDate' AND '$toDate' $filterDeathWard group by dcd.disease_name
");

    echo "<div>";
        echo "<table width='100%;' style='font-size:15px;background-color:white;text-align:center;'>";
            echo "<thead>";
            echo "<tr><th rowspan='3'>Diagnosis</th><th rowspan='3'>ICD</th><th colspan='6'>Number Of Death</th rowspan='3'><th>Total</th></tr>";
			echo "<tr><th colspan='3'>Age < $start_age_death</th><th colspan='3'>Age &ge; $end_age_death</th></tr>";
			echo "<tr><th>M</th><th>F</th><th>T</th><th>M</th><th>F</th><th>T</th></tr>";
            echo "</thead>";
            echo "<tbody>";
			$deathData=array();
			while($row=mysqli_fetch_assoc($disease_caused_death_select)){
				extract($row);
				$icd=($disease_name=='others')?"NIL":mysqli_fetch_assoc(mysqli_query($conn,"SELECT disease_code FROM tbl_disease WHERE disease_name='$disease_name'"))['disease_code'];
				//select less male
				$less_age_male_death=mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(dcd.disease_name) AS total_death FROM tbl_disease_caused_death dcd, tbl_patient_registration pr, tbl_admission ad WHERE dcd.registration_ID=pr.registration_ID AND pr.Gender='Male' AND ad.Admision_ID=dcd.Admision_ID AND TIMESTAMPDIFF(YEAR,Date_Of_Birth,CURDATE()) < $start_age_death AND dcd.disease_name='$disease_name' $filterDeathWard"))['total_death'];
				//select less female
				$less_age_female_death=mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(dcd.disease_name) AS total_death FROM tbl_disease_caused_death dcd, tbl_patient_registration pr, tbl_admission ad  WHERE dcd.registration_ID=pr.registration_ID AND pr.Gender='Female' AND ad.Admision_ID=dcd.Admision_ID AND TIMESTAMPDIFF(YEAR,Date_Of_Birth,CURDATE()) < $start_age_death AND dcd.disease_name='$disease_name' $filterDeathWard"))['total_death'];
				//select greater male
				$greater_age_male_death=mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(dcd.disease_name) AS total_death FROM tbl_disease_caused_death dcd, tbl_patient_registration pr, tbl_admission ad  WHERE dcd.registration_ID=pr.registration_ID AND pr.Gender='Male' AND ad.Admision_ID=dcd.Admision_ID AND TIMESTAMPDIFF(YEAR,Date_Of_Birth,CURDATE()) >= $end_age_death AND dcd.disease_name='$disease_name' $filterDeathWard"))['total_death'];
				//select greater female
				$greater_age_female_death=mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(dcd.disease_name) AS total_death FROM tbl_disease_caused_death dcd, tbl_patient_registration pr, tbl_admission ad  WHERE dcd.registration_ID=pr.registration_ID AND pr.Gender='Female' AND ad.Admision_ID=dcd.Admision_ID AND TIMESTAMPDIFF(YEAR,Date_Of_Birth,CURDATE()) >= $start_age_death AND dcd.disease_name='$disease_name' $filterDeathWard"))['total_death'];
				
				$subTotal=$less_age_male_death+$less_age_female_death+$greater_age_male_death+$greater_age_female_death;
				if($subTotal==0)continue;
	array_push($deathData,array(
	'disease_death_total'=>$subTotal,
	'disease_caused_death'=>$disease_name,
	'icd'=>$icd,
	'less_age_male_death'=>$less_age_male_death,
	'less_age_female_death'=>$less_age_female_death,
	'greater_age_male_death'=>$greater_age_male_death,
	'greater_age_female_death'=>$greater_age_female_death
	));
	$total_less_age_male_death+=$less_age_male_death;
	$total_less_age_female_death+=$less_age_female_death;
	$total_greater_age_male_death+=$greater_age_male_death;
	$total_greater_age_female_death+=$greater_age_female_death;
	$grandTotal+=$subTotal;
}
array_multisort($deathData,SORT_DESC);
	foreach ($deathData as $key => $value){
		echo"<tr>
				<td>".$value['disease_caused_death']."</td>
				<td>".$value['icd']."</td>
				<td>".$value['less_age_male_death']."</td>
				<td>".$value['less_age_female_death']."</td>
				<td>".($value['less_age_male_death']+$value['less_age_female_death'])."</td>
				<td>".$value['greater_age_male_death']."</td>
				<td>".$value['greater_age_female_death']."</td>
				<td>".($value['greater_age_male_death']+$value['greater_age_female_death'])."</td>
				<td>".$value['disease_death_total']."</td>
			</tr>";
    
	}
			echo "<tr><td colspan='9'><hr></td></tr>";
			echo "<tr><td colspan='2'>Total Death</td><td>{$total_less_age_male_death}</td><td>{$total_less_age_female_death}</td><td>".($total_less_age_male_death+$total_less_age_female_death)."</td><td>{$total_greater_age_male_death}</td><td>{$total_greater_age_female_death}</td><td>".($total_greater_age_male_death+$total_greater_age_female_death)."</td><td>{$grandTotal}</td></tr>";
			echo "<tr><td colspan='9'><hr></td></tr>";
            echo "</tbody>";
            echo "</table>";
            echo "</div>";
}
?>
</div>

<script type="text/javascript">
    /*$('#disease_category').on('change',function(){
        alert('change');
    });*/
$('#clinic_report_type').on('change',function(){
        //alert('change '+$('#clinic_report_type').val());
    });
$('#disease_report_type').on('change',function(){
        if($("#disease_report_type").val()==='type1'){
            $("#type2_report").hide();
            $("#type1_report").show();
        }
        if($("#disease_report_type").val()==='type2'){
            $("#type1_report").hide();
            $("#type2_report").show();
        }
    });
    
</script>
<script type="text/javascript">
    $(document).ready(function(){
        if($("#disease_report_type").val()==='type1'){
            $("#type2_report").hide();
            $("#type1_report").show();
        }
        if($("#disease_report_type").val()==='type2'){
            $("#type1_report").hide();
            $("#type2_report").show();
        }
    });
</script>