<?php include ("./includes/connection.php");
session_start();
$fromDate = $_GET['fromDate'];
$toDate = $_GET['toDate'];

$htm = "<center><img src='branchBanner/branchBanner.png' width='100%' ></center>";
$htm.= "<p align='center'><b>PATIENTS TESTS TAKEN REPORT <br/>FROM</b> <b style=''>" . $fromDate . "</b> <b>TO</b> <b style=''>" . $toDate . "</b><br/> " . "</p><hr style='width:100%'/>";
$htm.= '<center><table border=1 style=" border-spacing: 0px; border-collapse: separate;">';
$htm.= "<thead>
           <tr>
           <th style='width:1%'>SN</th>
           <th style=''><b>&nbsp;&nbsp;&nbsp;&nbsp;PATIENT NAME</th>
           <th style='text-align: left;' width=10%>PATIENT NUMBER</th>
           <th style='text-align: left;' width=10%>REGION</th>
           <th style='text-align: left;' width=10%>DISTRICT</th>
           <th style='text-align: left;' width=6%>GENDER</th>
           <th style='text-align: left;' width=10%>AGE</th>
                       <th style='text-align: left;' width=10%>TEST TAKEN</th>
           <th style='text-align: left;' width=13%>TEST DATE</th>
                       <th style='text-align: left;' width=10%>DIRECTED FROM</th>
        </tr>
      </thead>";
if (isset($_GET['fromDate'])) {
    $count = 1;
    $select_patient = "SELECT * FROM tbl_specimen_results INNER JOIN tbl_item_list_cache  ON payment_item_ID=Payment_Item_Cache_List_ID JOIN tbl_payment_cache ON tbl_payment_cache.Payment_Cache_ID=tbl_item_list_cache.Payment_Cache_ID
                 JOIN tbl_patient_registration ON tbl_patient_registration.Registration_ID=tbl_payment_cache.Registration_ID JOIN 
                 tbl_items ON tbl_items.Item_ID=tbl_item_list_cache.Item_ID WHERE TimeCollected BETWEEN '$fromDate' AND '$toDate' GROUP BY Patient_Name ORDER BY TimeCollected";
    $select_data_patient_result = mysqli_query($conn, $select_patient);
    while ($row = mysqli_fetch_array($select_data_patient_result)) {
        $registration_ID = $row['Registration_ID'];
        $patientName = $row['Patient_Name'];
        $Registration_ID = $row['Registration_ID'];
        $Region = $row['Region'];
        $District = $row['District'];
        $Gender = $row['Gender'];
        $dob = $row['Date_Of_Birth'];
        $Registration_Date_And_Time = $row['Registration_Date_And_Time'];
        $date1 = new DateTime(date('Y-m-d'));
        $date2 = new DateTime($dob);
        $diff = $date1->diff($date2);
        $age = $diff->y . " Years, ";
        $age.= $diff->m . " Months, ";
        $age.= $diff->d . " Days";
        $select_items = "SELECT Product_Name,Payment_Item_Cache_List_ID FROM tbl_specimen_results INNER JOIN tbl_item_list_cache  ON payment_item_ID=Payment_Item_Cache_List_ID JOIN tbl_payment_cache ON tbl_payment_cache.Payment_Cache_ID=tbl_item_list_cache.Payment_Cache_ID
                 JOIN tbl_patient_registration ON tbl_patient_registration.Registration_ID=tbl_payment_cache.Registration_ID JOIN 
                 tbl_items ON tbl_items.Item_ID=tbl_item_list_cache.Item_ID WHERE TimeCollected BETWEEN '$fromDate' AND '$toDate' AND tbl_patient_registration.Registration_ID='" . $row['Registration_ID'] . "' GROUP BY Payment_Item_Cache_List_ID ORDER BY TimeCollected";
        $selected_items = mysqli_query($conn, $select_items) or die(mysqli_error($conn));
        $products = '';
        $numberOfItem = mysqli_num_rows($selected_items);
        $track = 1;
        $num =1;
        while ($rowdata = mysqli_fetch_array($selected_items)) {
        $Product_Name = $rowdata['Product_Name'];
        $ppil=$rowdata['Payment_Item_Cache_List_ID'];
        if ($numberOfItem == 1) {
        $products =$num.'. '.$Product_Name;
        }else {
            if ($track <$numberOfItem) {
            $products .='<br><b>'.$num.'.</b> '.$Product_Name;
            }else {
            $products .='<br><b>'.$num.'.</b> '.$Product_Name .'.';
            }
        }
        $num++;
            $track++;
        }
        $htm.= "<tr><td>" . $count++ . "</td>";
        $htm.= "<td style='text-align:left; width:10%'>" . $patientName . "</td>";
        $htm.= "<td style='text-align:left; width:10%'>" . $row['Registration_ID'] . "</td>";
        $htm.= "<td style='text-align:left; width:10%'>" . $Region . "</td>";
        $htm.= "<td style='text-align:left; width:10%'>" . $District . "</td>";
        $htm.= "<td style='text-align:left; width:10%'>" . $Gender . "</td>";
        $htm.= "<td style='text-align:left; width:15%'>" . $age . "</td>";
        $htm.= "<td style='text-align:left; width:15%'>" . $products . "</td>";
        $htm.= "<td style='text-align:left; width:10%'>" . $Registration_Date_And_Time . "</td>";
        $htm.= "<td style='text-align:left; width:10%'>" . $row['Consultant'] . "</td>
           </tr>";
    }
} else {
}
$htm.= "</table></center>";
include ("MPDF/mpdf.php");
$mpdf = new mPDF();
$mpdf->SetDisplayMode('fullpage');
$mpdf->WriteHTML($htm);
$mpdf->Output();
exit;;
echo '


';