<?php
include("./includes/connection.php");

    $filter = ' AND DATE(tbl_test_results.TimeSubmitted) = DATE(NOW())';
    $parameter='fromDate=CURDATE()-INTERVAL 1 DAY&toDate=DATE(NOW())';
    
    if (isset($_GET['filterlabpatientdate']) && $_GET['filterlabpatientdate'] == true) {
        $Date_From = filter_input(INPUT_GET, 'fromDate');
        $Date_To = filter_input(INPUT_GET, 'toDate');
        $Sponsor = filter_input(INPUT_GET, 'sponsorID');
        
        $parameter="fromDate=$Date_From&toDate=$Date_To";

        if (isset($Date_From) && !empty($Date_From)) {
            $filter = " AND tbl_test_results.TimeSubmitted >='" . $Date_From . "'";
        }
        if (isset($Date_To) && !empty($Date_To)) {
            $filter = " AND tbl_test_results.TimeSubmitted <='" . $Date_To . "'";
        }
        if (isset($Date_To) && !empty($Date_To) && isset($Date_From) && !empty($Date_From)) {
            $filter = "  AND tbl_test_results.TimeSubmitted BETWEEN '" . $Date_From . "' AND '" . $Date_To . "'";
        }
        if (isset($Date_To) && empty($Date_To) && isset($Date_From) && empty($Date_From)) {
            $filter = "";
        }

        if ($Sponsor != 'All') {
            $filter .=" AND ts.Sponsor_ID='$Sponsor'";
        }
    }
    
    //echo $filter.'<br/>';exit;

    $validateQuery = "SELECT payment_item_ID FROM tbl_sponsor AS ts,tbl_tests_parameters_results JOIN tbl_test_results ON test_result_ID=ref_test_result_ID WHERE Validated='Yes' AND Submitted='Yes' $filter GROUP BY test_result_ID";

    // die($validateQuery);

    $anwerQuery = mysqli_query($conn,$validateQuery) or die(mysqli_error($conn));

    $num_rows = mysqli_num_rows($anwerQuery);
    $paymentID = array();
    while ($results = mysqli_fetch_assoc($anwerQuery)) {
        $paymentID[] = $results['payment_item_ID'];
    }
   // $queryItem = "SELECT tr.test_result_ID,i.Product_Name,ic.Item_Category_Name FROM tbl_item_list_cache ilc INNER JOIN tbl_test_results tr ON ilc.Payment_Item_Cache_List_ID=tr.payment_item_ID INNER JOIN tbl_tests_parameters_results tpr ON tpr.ref_test_result_ID=tr.test_result_ID INNER JOIN tbl_payment_cache pc ON pc.Payment_Cache_ID=ilc.Payment_Cache_ID JOIN  tbl_items i ON i.Item_ID=ilc.Item_ID JOIN tbl_item_subcategory is ON i.Item_Subcategory_ID=is.Item_Subcategory_ID JOIN tbl_item_category ic ON is.Item_Category_ID=ic.Item_Category_ID WHERE tpr.Submitted='Yes' AND tpr.Validated='Yes'";

    $listItems = implode(',', $paymentID);
    if ($num_rows > 0) {
        $select_Filtered_Patients = mysqli_query($conn,
                "SELECT * FROM tbl_test_results,tbl_item_list_cache,tbl_payment_cache,tbl_patient_registration,tbl_employee,tbl_sponsor AS ts WHERE payment_item_ID=Payment_Item_Cache_List_ID AND tbl_item_list_cache.Payment_Cache_ID= tbl_payment_cache.Payment_Cache_ID AND tbl_patient_registration.Registration_ID=tbl_payment_cache.Registration_ID AND tbl_employee.Employee_ID=tbl_payment_cache.Employee_ID AND ts.Sponsor_ID =tbl_patient_registration.Sponsor_ID AND payment_item_ID IN ($listItems) $filter  GROUP BY tbl_patient_registration.Registration_ID ORDER BY TimeSubmitted ASC");

    } else {
        $select_Filtered_Patients = mysqli_query($conn,
                "SELECT * FROM tbl_test_results,tbl_item_list_cache,tbl_payment_cache,tbl_patient_registration,tbl_employee,tbl_sponsor AS ts WHERE payment_item_ID=Payment_Item_Cache_List_ID AND tbl_item_list_cache.Payment_Cache_ID= tbl_payment_cache.Payment_Cache_ID AND tbl_patient_registration.Registration_ID=tbl_payment_cache.Registration_ID AND tbl_employee.Employee_ID=tbl_payment_cache.Employee_ID AND ts.Sponsor_ID =tbl_patient_registration.Sponsor_ID AND payment_item_ID IN (0) $filter   GROUP BY tbl_patient_registration.Registration_ID ORDER BY TimeSubmitted ASC");

    }


$Today_Date = mysqli_query($conn,"select now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
    $age = '';
}
$htm = "<center>
      <table width =100% id='patient-lab-result' class='display'>
        <thead>
            <tr>
                <th style='width:2%;'><b>SN</b></th>
                <th><b>PATIENT NAME</b></th>
                <th><b>REG#</b></th>
                <th><b>SPONSOR</b></th>
                <th style='width:14%;'><b>AGE</b></th>
                <th><b>GENDER</b></th>
                <th><b>SENT DATE</b></th>
                <th><b>PHONE#</b></th>
                <th><b>DOCTOR</b></th>
                <th style='width:5%;'><b>ACTION</b></th>
            </tr>
        </thead>";
$temp = 1;
while ($row = mysqli_fetch_array($select_Filtered_Patients)) {
    //$Product_Name=$row['Product_Name'];
    /* $Submitted=$row['Submitted'];
      $Validated=$row['Validated']; */
    $par='';
    $Date_Of_Birth = $row['Date_Of_Birth'];
    $age = floor((strtotime(date('Y-m-d')) - strtotime($Date_Of_Birth)) / 31556926) . " Years";

    $date1 = new DateTime($Today);
    $date2 = new DateTime($Date_Of_Birth);
    $diff = $date1->diff($date2);
    $age = $diff->y . " Years, ";
    $age.= $diff->m . " Months";
//              $age.= $diff->d." Days";
//              if(strtolower($Product_Name)=='registration and consultation fee'){
//              }else{
    // if($Submitted =='Yes' && $Validated=='Yes'){
    // }else{
    $par=$parameter.'&Registration_ID='.$row['Registration_ID'].'&Payment_Cache_ID='. $row['Payment_Cache_ID'];
    
    $htm.="<tr>";
    $htm.="<td id='thead'>" . $temp . "<td>" . ucwords(strtolower($row['Patient_Name'])) . "</td>";
    $htm.="<td>" . $row['Registration_ID'] . "</td>";
    $htm.="<td>" . $row['Sponsor_Name'] . "</td>";
    $htm.="<td>" . $age . "</td>";
    $htm.="<td>" . $row['Gender'] . "</td>";
    $htm.="<td>" . $row['TimeSubmitted'] . "</td>";
    $htm.="<td>" . $row['Phone_Number'] . "</td>";
    $htm.="<td>" . $row['Employee_Name'] . "</td>";

    // if(isset($_POST['action'])){
    // $htm.="<td>
    // <input type='button' class='searchresults' name='".$row['Patient_Name']."' id='".$row['Registration_ID']."' value='Lab results' />
    // </td>";
    // }  else {
    $patient_name=str_replace("'", '', $row['Patient_Name']);
    $htm.='<td>
				 
                <input type="button" value="Lab Results" onclick="openPatientResults(\''.$par.'\',\''.$patient_name.'\',\''.$row['Registration_ID'].'\')" />
                </td>';

    // }

    $htm.="</tr>";
    $temp++;
    //}				
}
$htm .="</table></center>";
echo $htm;
?>


