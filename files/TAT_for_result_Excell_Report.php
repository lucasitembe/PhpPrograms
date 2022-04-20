<?php

session_start();
include("./includes/connection.php");
$data = '';
if ($_GET['patient_id'] != '') {
    $Registration_ID = $_GET['patient_id'];
} else {
    $Registration_ID = '';
}
$Date_From = $_GET['Date_From'];
        $Date_To = $_GET['Date_To'];
        $subcategory_ID = $_GET['subcategory_ID'];
        $searchspecmen_id = $_GET['searchspecmen_id'];
        $Patient_Name = $_GET['Patient_Name'];
        $Registration_ID = $_GET['Registration_ID'];
        
        //select item subcategory
        $Item_Subcategory_Name='';
        $sql_select_item_sub_category_result=mysqli_query($conn,"SELECT Item_Subcategory_Name FROM tbl_item_subcategory WHERE Item_Subcategory_ID='$subcategory_ID'") or die(mysqli_error($conn));
        if(mysqli_num_rows($sql_select_item_sub_category_result)>0){
           $Item_Subcategory_Name=mysqli_fetch_assoc($sql_select_item_sub_category_result)['Item_Subcategory_Name']; 
        }
$data = "<table width ='100%' height = '30px'  border='0'   class='nobordertable'>           
            <tr><td colspan='4'><hr style='width:100%;margin-top:-0.5%'/></td></tr>
            <tr>
                <td style='text-align: center;' colspan='4'><b>$Item_Subcategory_Name</b></td>
            </tr>
            <tr>
                <td style='text-align: center;' colspan='4'><b>SPECIMEN COLLECTED FROM PATIENT TO RESULT TIME</b></td>
            </tr>
            
            <tr><td colspan='4'><hr style='width:100%'/></td></tr>
            <tr>
                <td style='text-align: right;'>From Date :</td>
                <td style='text-align: left;'>$Date_From</td>
                <td style='text-align: right;'>To Date :</td>
                <td style='text-align: left;'>$Date_To</td>
            </tr>
</table><br/><table class='table'>
<tr>
    <td width='3'><b>S/No.</b></td>
    <td><b>Patient Name</b></td>
    <td><b>Reg: #</b></td>
    <td><b>Sponsor</b></td>
    <td><b>Age</b></td>
    <td><b>Gender</b></td>
    <td><b>Test Name</b></td>
    <td><b>Specimen ID</b></td>
    <td width='20%'><b>TAT</b></td>
</tr>
<tbody id='list_of_patient_arleady_collected_specimen_body'>";
 $filter = ' AND DATE(TimeSubmitted) BETWEEN CURDATE()-INTERVAL 1 DAY AND DATE(NOW())';
    $navFilter = ' AND DATE(TimeSubmitted) BETWEEN CURDATE()-INTERVAL 1 DAY AND DATE(NOW())';
    $idsfilters = ' AND DATE(tbl_test_results.TimeSubmitted) BETWEEN CURDATE()-INTERVAL 1 DAY AND DATE(NOW())';

    
        
       
        
        

        if (isset($Date_From) && !empty($Date_From)) {
            $filter = " AND TimeSubmitted >='" . $Date_From . "'";
            $navFilter = " AND TimeSubmitted >='" . $Date_From . "'";
        }
        if (isset($Date_To) && !empty($Date_To)) {
            $filter = " AND TimeSubmitted <='" . $Date_To . "'";
            $navFilter = " AND TimeSubmitted <='" . $Date_To . "'";
        }
        if (isset($Date_To) && !empty($Date_To) && isset($Date_From) && !empty($Date_From)) {
            $filter = "  AND TimeSubmitted BETWEEN '" . $Date_From . "' AND '" . $Date_To . "'";
            $navFilter = "  AND TimeSubmitted BETWEEN '" . $Date_From . "' AND '" . $Date_To . "'";
            $idsfilters = "  AND tbl_test_results.TimeSubmitted BETWEEN '" . $Date_From . "' AND '" . $Date_To . "'";
        }

        

        if ($subcategory_ID != 'All') {
            $filter .=" AND i.Item_Subcategory_ID='$subcategory_ID'";
            $navFilter.=" AND i.Item_Subcategory_ID='$subcategory_ID'";
        }
         if (!empty($Patient_Name)) {
            $filter .= " AND pr.Patient_Name LIKE '%" . $Patient_Name . "%'";
        }
         if (!empty($Registration_ID)) {
            $filter .= " AND pr.Registration_ID LIKE '%" . $Registration_ID . "%'";
        }
         if (!empty($searchspecmen_id)) {
            $filter .= " AND il.Payment_Item_Cache_List_ID LIKE '%" . $searchspecmen_id . "%'";
        }





    $validateQuery = "SELECT payment_item_ID FROM tbl_tests_parameters_results JOIN tbl_test_results ON test_result_ID=ref_test_result_ID WHERE Validated='Yes' AND Submitted='Yes' $idsfilters GROUP BY test_result_ID";
    $anwerQuery = mysqli_query($conn,$validateQuery) or die(mysqli_error($conn));
    $num_rows = mysqli_num_rows($anwerQuery);
    $paymentID = array();
    while ($results = mysqli_fetch_assoc($anwerQuery)) {
        $paymentID[] = $results['payment_item_ID'];
    }

    
    $listItems = implode(',', $paymentID); //tbl_sponsor
    $select_Filtered_Patients = '';
    if ($num_rows > 0) {
        $select_Filtered_Patients = "SELECT * FROM tbl_test_results tr,tbl_item_list_cache il,tbl_payment_cache pc,tbl_patient_registration pr,tbl_employee em,tbl_sponsor sp,tbl_items i WHERE payment_item_ID=Payment_Item_Cache_List_ID AND il.Payment_Cache_ID= pc.Payment_Cache_ID AND i.Item_ID=il.Item_ID AND pr.Registration_ID=pc.Registration_ID AND em.Employee_ID=pc.Employee_ID AND tr.removed_status='No' AND sp.Sponsor_ID =pr.Sponsor_ID AND payment_item_ID IN ($listItems) $filter  ORDER BY TimeSubmitted ";
    } else {
        $select_Filtered_Patients = "SELECT * FROM tbl_test_results tr,tbl_item_list_cache il,tbl_payment_cache pc,tbl_patient_registration pr,tbl_employee em,tbl_sponsor sp,tbl_items i WHERE payment_item_ID=Payment_Item_Cache_List_ID AND il.Payment_Cache_ID= pc.Payment_Cache_ID AND i.Item_ID=il.Item_ID AND pr.Registration_ID=pc.Registration_ID AND em.Employee_ID=pc.Employee_ID AND tr.removed_status='No' AND sp.Sponsor_ID =pr.Sponsor_ID $filter   ORDER BY TimeSubmitted ASC LIMIT 100";
    }

    //echo $select_Filtered_Patients;
    //die($select_Filtered_Patients);

    $excecuteQuery = mysqli_query($conn,$select_Filtered_Patients) or die(mysqli_error($conn));
    $Today_Date = mysqli_query($conn,"select now() as today") or die(mysqli_error($conn));
    while ($row = mysqli_fetch_array($Today_Date)) {
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
        $age = '';
    }

    $temp = 1;
    while ($row = mysqli_fetch_assoc($excecuteQuery)) {

        $Date_Of_Birth = $row['Date_Of_Birth'];
        $age = floor((strtotime(date('Y-m-d')) - strtotime($Date_Of_Birth)) / 31556926) . " Years";

        $date1 = new DateTime($Today);
        $date2 = new DateTime($Date_Of_Birth);
        $diff = $date1->diff($date2);
        $age = $diff->y . " Years, ";
        $age.= $diff->m . " Months";
        $Payment_Item_Cache_List_ID=$row['Payment_Item_Cache_List_ID'];
        ///////////////////////////////////////////////////////////////////////
        $lapsed_time="";
        $sql_select_result_time_result=mysqli_query($conn,"SELECT TimeCollected FROM tbl_specimen_results WHERE payment_item_ID='$Payment_Item_Cache_List_ID' LIMIT 1") or die(mysqli_error($conn));
if(mysqli_num_rows($sql_select_result_time_result)>0){
    $TimeCollected=mysqli_fetch_assoc($sql_select_result_time_result)['TimeCollected'];
  $TimeSubmitted="";
$Query = "SELECT Product_Name,Payment_Item_Cache_List_ID,TimeSubmitted,test_result_ID,tr.Employee_ID FROM tbl_item_list_cache il INNER JOIN tbl_test_results tr ON Payment_Item_Cache_List_ID=payment_item_ID JOIN tbl_payment_cache pc ON  pc.Payment_Cache_ID=il.Payment_Cache_ID JOIN  tbl_items i ON i.Item_ID=il.Item_ID WHERE il.Check_In_Type='Laboratory' and il.Status='Sample Collected' AND Payment_Item_Cache_List_ID='$Payment_Item_Cache_List_ID' GROUP BY Payment_Item_Cache_List_ID";
$result2 = mysqli_query($conn,$Query) or die(mysqli_error($conn));
if(mysqli_num_rows($result2)>0){
    $rows_2=mysqli_fetch_assoc($result2);
    $TimeSubmitted=$rows_2['TimeSubmitted'];
}


    $validateQuery = mysqli_query($conn,"SELECT tpr.TimeSubmitted FROM tbl_tests_parameters_results tpr JOIN tbl_test_results ON test_result_ID=ref_test_result_ID WHERE Validated='Yes' AND Submitted='Yes' AND payment_item_ID='$Payment_Item_Cache_List_ID' GROUP BY test_result_ID") or die(mysqli_error($conn));
if(mysqli_num_rows($validateQuery)>0){
    $TimeSubmitted=mysqli_fetch_assoc($validateQuery)['TimeSubmitted'];
}
////select speciment submitted time

$date1 = new DateTime($TimeSubmitted);
        $date2 = new DateTime($TimeCollected);
        $diff = $date1->diff($date2);
        $lapsed_time = $diff->d . " days, ";
        $lapsed_time .= $diff->h . " hours, ";
        $lapsed_time .= $diff->i . " minutes,";
        $lapsed_time .= $diff->s . " Seconds";
}
///////////////////////////////////////////////////////////////////////
        
        $htm.="<tr>";
        $htm.="<td id='thead'>" . $temp . "<td>" . ucwords(strtolower($row['Patient_Name'])) . "</td>";
        $htm.="<td>" . $row['Registration_ID'] . "</td>";
        $htm.="<td>" . $row['Sponsor_Name'] . "</td>";
        $htm.="<td>" . $age . "</td>";
        $htm.="<td>" . $row['Gender'] . "</td>";
        $htm.="<td>" . $row['Product_Name'] . "</td>";
        $htm.="<td>" . $row['Payment_Item_Cache_List_ID'] . "</td>";
        $htm.="<td>---$TimeSubmitted----$TimeCollected <br/>$lapsed_time</td>";
        $htm.="</tr>";
        $temp++;
        //}				
    }
    $htm .="</tbody></table></center>";
    $data.=$htm;




    header("Content-Type:application/xls");
    header("content-Disposition: attachement; filename=Episode Of Care excell Report.xls");
    echo $data;
exit;
