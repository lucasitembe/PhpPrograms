<?php
include './includes/connection.php';

$classification=array('Ordinary Xray Register','Special Xray Register','Eeg Register','Ultrasound Register','Echo Cardiogram Register');
$fromDate = DATE('Y-m-d H:m:s');
$toDate = DATE('Y-m-d H:m:s');
$filter = "  WHERE ilc.Transaction_Date_And_Time BETWEEN '" . $fromDate . "' AND '" . $toDate . "'";
$filterSub = '';
if (isset($_GET['fromDate']) ) {
    $fromDate = $_GET['fromDate'];
    $toDate = $_GET['toDate'];
    $sponsorName = $_GET['Sponsor'];
    $SubCategory = $_GET['SubCategory'];
    
    $filter = "  WHERE ilc.Transaction_Date_And_Time BETWEEN '" . $fromDate . "' AND '" . $toDate . "' ";
    if ($sponsorName != 'All') {
        $filter .=" AND pp.Sponsor_ID='$sponsorName'";
    }

    if ($SubCategory != 'All') {
        $filter .=" AND i.Item_Subcategory_ID='$SubCategory'";
    }
    $test_done_process = $_GET['test_done_process'];
    if ($test_done_process != 'All') {
        if($test_done_process=="done"){
          $filter .=" AND ilc.Status='served'";  
        }else{
          $filter .=" AND ilc.Status<>'served'";  
        }  
    }
    $Employee_ID = $_GET['Employee_ID'];
    if ($Employee_ID != 'All') {
        $filter .=" AND ilc.Consultant_ID='$Employee_ID'"; 
        
      
      
    }
    $Employee_ID_done = $_GET['Employee_ID_done'];
    if ($Employee_ID_done != 'All') {
        $filter .=" AND rp.Radiologist_ID='$Employee_ID_done'"; 
        
      
      
    }
}

//    $doctor_done = mysqli_fetch_assoc(mysqli_query($conn,"select Employee_Name from tbl_employee where Employee_ID='$Employee_ID'"))['Employee_Name'];

foreach ($classification as $clafname) {
    
    $notIn=array('-1');

    $selectPatients = "SELECT rp.Radiologist_ID,rp.Sonographer_ID,ilc.Status,pr.Registration_ID,Patient_Name,Gender,Date_Of_Birth,Guarantor_Name,Employee_Name AS Consultant,ilc.Consultant_ID FROM tbl_item_list_cache ilc JOIN tbl_radiology_patient_tests rp ON ilc.Payment_Item_Cache_List_ID=rp.Patient_Payment_Item_List_ID JOIN tbl_items i ON ilc.Item_ID=i.Item_ID JOIN tbl_payment_cache pp ON pp.Payment_Cache_ID =ilc.Payment_Cache_ID JOIN tbl_patient_registration pr ON pr.Registration_ID =pp.Registration_ID JOIN tbl_sponsor sp ON sp.Sponsor_ID=pp.Sponsor_ID JOIN tbl_employee e ON e.Employee_ID=ilc.Consultant_ID $filter   AND rp.Classification='$clafname' AND ilc.Payment_Item_Cache_List_ID NOT IN (".  implode(',', $notIn).")";
    
   // echo $selectPatients.'<br/>';
    
    $select_data_patient_result = mysqli_query($conn,$selectPatients) or die(mysqli_error($conn));
    $noOfPatient = mysqli_num_rows($select_data_patient_result);

  if(mysqli_num_rows($select_data_patient_result) >0){  
    echo "<div class='daterange'>".  $clafname."<span style='float:right'>PATIENT NO. $noOfPatient </span></div>";
    echo '<center><table width =100% border=0 id="patientspecimenCollected" class="display">';
    echo "<thead>
	            <tr>
			    <th  width='4%'>SN</th>
			    <th style='text-align: left;' width='20%'><b>PATIENT NAME</th>
			    <th style='text-align: left;' >REG #</th>
			    <th style='text-align: left;' >SPONSOR</th>
                             <th style='text-align: left;' >GENDER</th>
			    <th style='text-align: left;' >AGE</th>
                            <th style='text-align: left;' width='13%'>REQ. DOCTOR</th>
                            <th style='text-align: left;' width='13%'>Done by. DOCTOR</th>
                            <th style='text-align: left;' width='30%'>EXAM & RESULT</th>
                            <th style='text-align: left;' width='30%'>STATUS</th>
		     </tr>
           </thead>";

    $count = 1;
    while ($row = mysqli_fetch_array($select_data_patient_result)) {
        $registration_ID = $row['Registration_ID'];
        $patientName = $row['Patient_Name'];
        $Registration_ID = $row['Registration_ID'];
        $Consultant = $row['Consultant'];
        $Guarantor_Name = $row['Guarantor_Name'];
        $Gender = $row['Gender'];
        $dob = $row['Date_Of_Birth'];
        $Consultant_ID = $row['Consultant_ID'];
        $Radiologist_ID = $row['Radiologist_ID'];
        $Sonographer_ID = $row['Sonographer_ID'];
        
         if($Radiologist_ID != ""){
           $doctor_done = mysqli_fetch_assoc(mysqli_query($conn,"select Employee_Name from tbl_employee where Employee_ID='$Radiologist_ID'"))['Employee_Name'];  
         }else{
             $doctor_done = mysqli_fetch_assoc(mysqli_query($conn,"select Employee_Name from tbl_employee where Employee_ID='$Sonographer_ID'"))['Employee_Name'];
         }
        
   
        
        
         
        $Status = $row['Status'];
        $test_done_status="Not Done";
        $test_done_style="background:red;color:white;font-weight:bold";
        if($Status=="served"){
            $test_done_status="Done";
            $test_done_style="background:green;color:white;font-weight:bold";
        }

        //these codes are here to determine the age of the patient
        $date1 = new DateTime(date('Y-m-d'));
        $date2 = new DateTime($dob);
        $diff = $date1->diff($date2);
        $age = $diff->y . " Years, ";
        $age .= $diff->m . " Months, ";
        $age .= $diff->d . " Days";
        
        //get its patient items
     
        $select_items = "SELECT Product_Name,Payment_Item_Cache_List_ID FROM tbl_item_list_cache ilc JOIN tbl_radiology_patient_tests rp ON ilc.Payment_Item_Cache_List_ID=rp.Patient_Payment_Item_List_ID JOIN tbl_items i ON ilc.Item_ID=i.Item_ID JOIN tbl_payment_cache pp ON pp.Payment_Cache_ID =ilc.Payment_Cache_ID JOIN tbl_sponsor sp ON sp.Sponsor_ID=pp.Sponsor_ID $filter AND pp.Registration_ID='$Registration_ID'  AND ilc.Consultant_ID='$Consultant_ID'  AND rp.Classification='$clafname'  AND ilc.Payment_Item_Cache_List_ID NOT IN (".  implode(',', $notIn).")";
 
        $selected_items = mysqli_query($conn,$select_items) or die(mysqli_error($conn));

        $products = '';
        $numberOfItem = mysqli_num_rows($selected_items);
        $track = 1;
        while ($rowdata = mysqli_fetch_array($selected_items)) {
            $Product_Name = $rowdata['Product_Name'];
            $ppil = $rowdata['Payment_Item_Cache_List_ID'];
            
            $notIn[]=$ppil;

            if ($numberOfItem == 1) {
                $products = $Product_Name;
            } else {
                if ($track < $numberOfItem) {
                    $products .=$Product_Name . ',  ';
                } else {
                    $products .=$Product_Name . '.';
                }
            }

            $track++;
        }
        
        if(!empty($products)){

        echo "<tr><td>" . $count++ . "</td>";
        echo "<td style='text-align:left; '>" . $patientName . "</td>";
        echo "<td style='text-align:left; '>" . $row['Registration_ID'] . "</td>";
        echo "<td style='text-align:left; '>" . $Guarantor_Name . "</td>";
        echo "<td style='text-align:left; '>" . $Gender . "</td>";
        echo "<td style='text-align:left; '>" . $age . "</td>";
        echo "<td style='text-align:left; '>" . $Consultant. "</td>";
        echo "<td style='text-align:left; '>" . $doctor_done. "</td>";
        echo "<td style='text-align:left; '>" . $products . "</td>";
        echo "<td style='text-align:left;".$test_done_style."'>" . $test_done_status . "</td>";
        echo " </tr>";
        }
    }

    echo "</table></center><br/><br/>";
  }
}
?>
<div id="revenueitemsList" style="display: none">
    <div id="showrevenueitemList">


    </div>
</div>