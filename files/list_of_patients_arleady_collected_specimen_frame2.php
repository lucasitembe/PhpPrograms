<?php
include("includes/connection.php");

    $filter = ' AND DATE(TimeSubmitted) BETWEEN CURDATE()-INTERVAL 1 DAY AND DATE(NOW())';
    $navFilter = ' AND DATE(TimeSubmitted) BETWEEN CURDATE()-INTERVAL 1 DAY AND DATE(NOW())';
    $idsfilters = ' AND DATE(tbl_test_results.TimeSubmitted) BETWEEN CURDATE()-INTERVAL 1 DAY AND DATE(NOW())';


        $Date_From = $_POST['Date_From'];
        $Date_To = $_POST['Date_To'];
        $subcategory_ID = $_POST['subcategory_ID'];
        $searchspecmen_id = $_POST['searchspecmen_id'];
        $Registration_ID = $_POST['Registration_ID'];
        $Patient_Name = $_POST['Patient_Name'];



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
    $total_over = 0;
    $total_nomal = 0;
    while ($row = mysqli_fetch_assoc($excecuteQuery)) {

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
        $Payment_Item_Cache_List_ID=$row['Payment_Item_Cache_List_ID'];
        ///////////////////////////////////////////////////////////////////////
        $lapsed_time="";
        $sql_select_result_time_result=mysqli_query($conn,"SELECT TimeCollected FROM tbl_specimen_results WHERE payment_item_ID='$Payment_Item_Cache_List_ID' LIMIT 1") or die(mysqli_error($conn));
if(mysqli_num_rows($sql_select_result_time_result)>0){
    $result_date=mysqli_fetch_assoc($sql_select_result_time_result)['TimeCollected'];
  $TimeSubmitted="";
  $Query = "SELECT Product_Name,Payment_Item_Cache_List_ID,TimeSubmitted,test_result_ID,tr.Employee_ID FROM tbl_item_list_cache il INNER JOIN tbl_test_results tr ON Payment_Item_Cache_List_ID=payment_item_ID JOIN tbl_payment_cache pc ON  pc.Payment_Cache_ID=il.Payment_Cache_ID JOIN  tbl_items i ON i.Item_ID=il.Item_ID WHERE il.Check_In_Type='Laboratory' and il.Status='Sample Collected' AND Payment_Item_Cache_List_ID='$Payment_Item_Cache_List_ID' GROUP BY Payment_Item_Cache_List_ID";
  $result2 = mysqli_query($conn,$Query) or die(mysqli_error($conn));
  if(mysqli_num_rows($result2)>0){
      $rows_2=mysqli_fetch_assoc($result2);
      $test_result_ID  =$rows_2['test_result_ID'];
      $resultDATE = mysqli_query($conn,"SELECT  `TimeSubmitted` FROM `tbl_tests_parameters_results` WHERE Validated='Yes' AND Submitted='Yes' AND `ref_test_result_ID`='$test_result_ID' LIMIT 1") or die(mysqli_error($conn));
      if(mysqli_num_rows($resultDATE)>0){
          $rows_3=mysqli_fetch_assoc($resultDATE);
          $TimeSubmitted=$rows_3['TimeSubmitted'];
      }
  }

  //$sql_select_result_date=mysqli_query($conn,"") or die(mysqli_error($conn));

$date1 = new DateTime($TimeSubmitted);
          $date2 = new DateTime($result_date);
          $diff = $date1->diff($date2);
          $lapsed_time = $diff->d . " days, ";
          $days = $diff->d;
          $lapsed_time .= $diff->h . " hours, ";
          $hrs = $diff->h;
          $lapsed_time .= $diff->i . " minutes,";
          $min = $diff->i;
          $lapsed_time .= $diff->s . " Seconds";
  //}
//      $lapsed_time = date_diff($TimeSubmitted,$result_date,absolute);
  ///////////////////////////////////////////////////////////////////////
        $style = "background: green;";
       
        $tatrang = explode("Min",  $row['tat_normal_range']);
        if($tatrang[0]."Min" == $row['tat_normal_range']){
            if ((int)$min > (int)$tatrang[0]){
                $style = "background: red;";
                $total_over++;
            }
            
        } else {
            $tatrang = explode("Hrs",  $row['tat_normal_range']);
            if (((int)$days*24) > (int)$tatrang[0]){
                $style = "background: red;";
                $total_over++;
            } elseif ((int)$hrs > (int)$tatrang[0]) {
                $style = "background: red;";
                $total_over++;
           }
        }
        $htm.="<tr style='".$style."color: white;'>";
        $htm.="<td id='thead'>" . $temp . "<td>" . ucwords(strtolower($row['Patient_Name'])) . "</td>";
        $htm.="<td>" . $row['Registration_ID'] . "</td>";
        $htm.="<td>" . $row['Sponsor_Name'] . "</td>";
        $htm.="<td>" . $age . "</td>";
        $htm.="<td>" . $row['Gender'] . "</td>";
        $htm.="<td>" . $row['Product_Name'] . "</td>";
        $htm.="<td>".$row['Payment_Item_Cache_List_ID']."</td>";
        $htm.="<td>$lapsed_time</td>";
        $htm.="</tr>";
        $temp++;
        $total_nomal++;
        }
    }
    $htm .="<tr>"
            . "<td><b>Total in Range</b></td>"
            . "<td colspan='8'>".((int)$total_nomal - (int)$total_over)."</td>"
            . "</tr>"
            . "<td><b>Total Out of Range</b></td>"
            . "<td colspan='8'>".$total_over."</td>"
            . "<tr>"
            . "<td><b>Tatal Sample</b></td>"
            . "<td colspan='8'>".(int)$total_nomal."</td>"
            . "</tr>"
            . "<tr>"
            . "</tr>"
            . "</table></center>";
    echo $htm;
