<?php
include("./includes/connection.php");

    //$filter = ' AND DATE(tbl_test_results.TimeSubmitted) = DATE(NOW())';
    $parameter='fromDate=CURDATE()-INTERVAL 1 DAY&toDate=DATE(NOW())';
    $filter = '';
    $Today_Date = mysqli_query($conn,"select now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
}
    // if (isset($_GET['filterlabpatientdate']) && $_GET['filterlabpatientdate'] == true) {
        $Date_From = $_GET['fromDate'];
        $Date_To = $_GET['toDate'];
        $Product_Name = $_GET['Product_Name'];
        $labSubCategoryID = $_GET['labSubCategoryID'];
        $sponsorID = $_GET['sponsorID'];

         if($labSubCategoryID != 'All'){
           $filter .= " AND i.Item_Subcategory_ID = '$labSubCategoryID'";
         }
         if($labSubCategoryID != 'All'){
          $filter .= " AND i.Item_Subcategory_ID = '$labSubCategoryID'";
        }
         if($sponsorID != 'All'){
          $filter .= " AND pc.Sponsor_ID = '$sponsorID'";
        }

      // }else{
        if(!empty($Date_From) && !empty($Date_To)){
          $filter .=" AND tpr.TimeSubmitted BETWEEN '$Date_From' AND '$Date_To'";
        }else{
          $Date_From = $Today." 00:00:00";
          // $Date_To = date("Y-m-d")." ".date("h:i:s");
          $filter .=" AND tpr.TimeSubmitted BETWEEN '$Date_From' AND NOW()";
        }
    // }
      // die("SELECT pr.Registration_ID, pr.Patient_Name, i.Product_Name, ppit.Transaction_Date_And_Time as ordered_time, sr.TimeCollected, tpr.TimeSubmitted, tpr.TimeValidate, timestampdiff(MINUTE,sr.TimeCollected,tpr.TimeSubmitted) as time_lapsed FROM tbl_patient_registration pr, tbl_items i, tbl_specimen_results sr, tbl_tests_parameters_results tpr, tbl_item_list_cache ilc, tbl_payment_cache pc, tbl_test_results tr, tbl_patient_payment_item_list ppit WHERE pr.Registration_ID = pc.Registration_ID and pc.Payment_Cache_ID=ilc.Payment_Cache_ID and ilc.Payment_Item_Cache_List_ID=sr.payment_item_ID and ilc.Item_ID=i.Item_ID and tpr.ref_test_result_ID=tr.test_result_ID and ppit.Patient_Payment_ID=ilc.Patient_Payment_ID and tr.payment_item_ID=sr.payment_item_ID $filter GROUP BY ilc.Payment_Item_Cache_List_ID order by ppit.Transaction_Date_And_Time ");

        $select_list=mysqli_query($conn,"SELECT pr.Registration_ID, pr.Patient_Name, i.Product_Name, ppit.Transaction_Date_And_Time as ordered_time, sr.TimeCollected, tpr.TimeSubmitted, tpr.TimeValidate, timestampdiff(MINUTE,sr.TimeCollected,tpr.TimeSubmitted) as time_lapsed FROM tbl_patient_registration pr, tbl_items i, tbl_specimen_results sr, tbl_tests_parameters_results tpr, tbl_item_list_cache ilc, tbl_payment_cache pc, tbl_test_results tr, tbl_patient_payment_item_list ppit WHERE pr.Registration_ID = pc.Registration_ID and pc.Payment_Cache_ID=ilc.Payment_Cache_ID and ilc.Payment_Item_Cache_List_ID=sr.payment_item_ID and ilc.Item_ID=i.Item_ID and tpr.ref_test_result_ID=tr.test_result_ID and ppit.Patient_Payment_ID=ilc.Patient_Payment_ID and tr.payment_item_ID=sr.payment_item_ID and tpr.TimeSubmitted between '$Date_From' and '$Date_To' $filter GROUP BY ilc.Payment_Item_Cache_List_ID order by ppit.Transaction_Date_And_Time ");
      


// $Today_Date = mysqli_query($conn,"select now() as today");
// while ($row = mysqli_fetch_array($Today_Date)) {
//     $original_Date = $row['today'];
//     $new_Date = date("Y-m-d", strtotime($original_Date));
//     $Today = $new_Date;
//     $age = '';
// }
//
// echo "<center>
//       <table width =100% id='patient-lab-result' class='display'>
//         <thead>
//             <tr>
//                 <th style='width:2%;'><b>SN</b></th>
//                 <th><b>PATIENT NAME</b></th>
//                 <th><b>REG#</b></th>
//                 <th><b>TEST NAME</b></th>
//                 <th><b>DOCTOR ORDERED AT</b></th>
//                 <th><b>COLLECTED AT</b></th>
//                 <th style='width:14%;'><b>VALIDATED AT</b></th>
//                 <th><b>SENT TO DOCTOR AT</b></th>
//                 <th><b>LAPSED TIME</b></th>
//             </tr>
//         </thead><tbody>";

echo "<table width =100% id='patient-lab-result' class='display' style='background-color:white;'>
  <thead>
    <tr style='background: #dedede;'>
      <th style='width:2%;'><b>SN</b></th>
      <th><b>PATIENT NAME</b></th>
      <th><b>REG#</b></th>
      <th><b>TEST NAME</b></th>
      <th><b>DOCTOR ORDERED AT</b></th>
      <th><b>COLLECTED AT</b></th>
      <th><b>VALIDATED AT</b></th>
      <th><b>SENT TO DOCTOR AT</b></th>
      <th><b>LAPSED TIME</b></th>
    </tr>
  </thead><tbody>
";
$temp = 1;
while ($row = mysqli_fetch_array($select_list)) {
  $time_lapse=floor($row['time_lapsed']/60)." Hrs: ".($row['time_lapsed']%60)."Min";
  echo "<tr>
          <td>".$temp."</td>
          <td>".$row['Patient_Name']."</td>
          <td>".$row['Registration_ID']."</td>
          <td>".$row['Product_Name']."</td>
          <td>".$row['ordered_time']."</td>
          <td>".$row['TimeCollected']."</td>
          <td>".$row['TimeValidate']."</td>
          <td>".$row['TimeSubmitted']."</td>
          <td style='text-align:center;'>".$time_lapse."</td>
        </tr>";
  $temp++;
}
echo"</tbody></table></center>";

?>
