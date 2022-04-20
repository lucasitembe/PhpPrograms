<?php include ("includes/connection.php");
include ("./includes/constants.php");
echo "<link rel='stylesheet' href='fixHeader.css'>";

if (isset($_POST['action'])) {
    if ($_POST['action'] == 'barcode') {
        $payingID = $_POST['value'];
        if ($payingID == '') {
            $filter = ' AND DATE(TimeSubmitted) BETWEEN CURDATE()-INTERVAL 1 DAY AND DATE(NOW())';
            $validateQuery = "SELECT payment_item_ID FROM tbl_tests_parameters_results JOIN tbl_test_results ON test_result_ID=ref_test_result_ID WHERE Validated='Yes' GROUP BY test_result_ID";
            $anwerQuery = mysqli_query($conn, $validateQuery);
            $num_rows = mysqli_num_rows($anwerQuery);
            $paymentID = array();
            while ($results = mysqli_fetch_assoc($anwerQuery)) {
                $paymentID[] = $results['payment_item_ID'];
            }
            $listItems = implode(',', $paymentID);
            if ($num_rows > 0) {
                $select_Filtered_Patients = "SELECT * FROM tbl_test_results,tbl_item_list_cache,tbl_payment_cache,tbl_patient_registration,tbl_employee,tbl_sponsor WHERE payment_item_ID=Payment_Item_Cache_List_ID AND tbl_item_list_cache.Payment_Cache_ID= tbl_payment_cache.Payment_Cache_ID AND tbl_patient_registration.Registration_ID=tbl_payment_cache.Registration_ID AND tbl_employee.Employee_ID=tbl_payment_cache.Employee_ID AND tbl_test_results.removed_status='No' AND tbl_sponsor.Sponsor_ID =tbl_patient_registration.Sponsor_ID AND payment_item_ID NOT IN ($listItems) $filter  GROUP BY tbl_payment_cache.Payment_Cache_ID ORDER BY TimeSubmitted ASC";
            } else {
                $select_Filtered_Patients = "SELECT * FROM tbl_test_results,tbl_item_list_cache,tbl_payment_cache,tbl_patient_registration,tbl_employee,tbl_sponsor WHERE payment_item_ID=Payment_Item_Cache_List_ID AND tbl_item_list_cache.Payment_Cache_ID= tbl_payment_cache.Payment_Cache_ID AND tbl_patient_registration.Registration_ID=tbl_payment_cache.Registration_ID AND tbl_employee.Employee_ID=tbl_payment_cache.Employee_ID AND tbl_test_results.removed_status='No' AND tbl_sponsor.Sponsor_ID =tbl_patient_registration.Sponsor_ID $filter  GROUP BY tbl_payment_cache.Payment_Cache_ID ORDER BY TimeSubmitted ASC LIMIT 100";
            }
            $excecuteQuery = mysqli_query($conn, $select_Filtered_Patients);
            $Today_Date = mysqli_query($conn, "select now() as today");
            while ($row = mysqli_fetch_array($Today_Date)) {
                $original_Date = $row['today'];
                $new_Date = date("Y-m-d", strtotime($original_Date));
                $Today = $new_Date;
                $age = '';
            }
            $htm = "<center>
      <table width =100% id='resultsPatientList' class='display fixTableHead'>
        <thead>
            <tr style='background-color: #ccc;'>
                <th style='width:2%;'><b>SN</b></th>
                <th><b>PATIENT NAME</b></th>
                <th><b>REG#</b></th>
                <th><b>SPONSOR</b></th>
                <th style='width:14%;'><b>AGE</b></th>
                <th><b>GENDER</b></th>
                <th><b>PHONE#</b></th>
                <th><b>DOCTOR</b></th>
                <th style='width:5%;'><b>ACTION</b></th>
            </tr>
        </thead>";
            $temp = 1;
            while ($row = mysqli_fetch_assoc($excecuteQuery)) {
                $Date_Of_Birth = $row['Date_Of_Birth'];
                $age = floor((strtotime(date('Y-m-d')) - strtotime($Date_Of_Birth)) / 31556926) . " Years";
                $date1 = new DateTime($Today);
                $date2 = new DateTime($Date_Of_Birth);
                $diff = $date1->diff($date2);
                $age = $diff->y . " Years, ";
                $age.= $diff->m . " Months";
                $htm.= "<tr>";
                $htm.= "<td id='thead'>" . $temp . "<td>" . ucwords(strtolower($row['Patient_Name'])) . "</td>";
                $htm.= "<td>" . $row['Registration_ID'] . "</td>";
                $htm.= "<td>" . $row['Sponsor_Name'] . "</td>";
                $htm.= "<td>" . $age . "</td>";
                $htm.= "<td>" . $row['Gender'] . "</td>";
                $htm.= "<td>" . $row['Phone_Number'] . "</td>";
                $htm.= "<td>" . $row['Employee_Name'] . "</td>";
                if (isset($_POST['action'])) {
                    $htm.= "<td><input type='button' class='searchresults' name='" . $row['Patient_Name'] . "' id='" . $row['Registration_ID'] . "' value='Lab results' /> <input type='button' class='removing' id='" . $row['Payment_Item_Cache_List_ID'] . "' value='Remove' /></td>";
                } else {
                    $htm.= "<td><input type='button' filter='" . $filter . "' class='results' name='" . $row['Patient_Name'] . "' id='" . $row['Registration_ID'] . "' payment_id='" . $row['Payment_Cache_ID'] . "' value='Lab results' /> 
                  <input type='button' class='removing' id='" . $row['Payment_Item_Cache_List_ID'] . "' value='Remove' style='width:78px'/></td>";
                }
                $htm.= "</tr>";
                $temp++;
            }
            $htm.= "</table></center>";
            echo $htm;
        } else {
            $validateQuery = "SELECT * FROM tbl_test_results WHERE payment_item_ID='" . $payingID . "'";
            $anwerQuery = mysqli_query($conn, $validateQuery) or die(mysqli_error($conn));
            $num = mysqli_num_rows($anwerQuery);
            if ($num > 0) {
                $validationStatus = "SELECT * FROM tbl_tests_parameters_results JOIN tbl_test_results ON test_result_ID=ref_test_result_ID WHERE Validated='No' OR Validated='' AND payment_item_ID='" . $payingID . "' GROUP BY test_result_ID";
                $statusResult = mysqli_query($conn, $validationStatus);
                $checkExist = mysqli_num_rows($statusResult);
                if ($checkExist > 0) {
                    $results = mysqli_fetch_assoc($statusResult);
                } else {
                    $results = mysqli_fetch_assoc($anwerQuery);
                }
            } else {
                $results = mysqli_fetch_assoc($anwerQuery);
            }
            $select_Filtered_Patients = "SELECT * FROM tbl_test_results,tbl_item_list_cache,tbl_payment_cache,tbl_patient_registration,tbl_employee WHERE payment_item_ID=Payment_Item_Cache_List_ID AND tbl_item_list_cache.Payment_Cache_ID= tbl_payment_cache.Payment_Cache_ID AND tbl_patient_registration.Registration_ID=tbl_payment_cache.Registration_ID AND tbl_employee.Employee_ID=tbl_payment_cache.Employee_ID AND tbl_test_results.removed_status='No' AND payment_item_ID='" . $results['payment_item_ID'] . "' GROUP BY tbl_payment_cache.Payment_Cache_ID ORDER BY test_result_ID ASC";
            $excecuteQuery = mysqli_query($conn, $select_Filtered_Patients);
            $Today_Date = mysqli_query($conn, "select now() as today");
            while ($row = mysqli_fetch_array($Today_Date)) {
                $original_Date = $row['today'];
                $new_Date = date("Y-m-d", strtotime($original_Date));
                $Today = $new_Date;
                $age = '';
            }
            $htm = "<center>
      <table width =100% id='resultsPatientList' class='display fixTableHead'>
        <thead>
            <tr style='background-color: #ccc;'>
                <th style='width:2%;'><b>SN</b></th>
                <th><b>PATIENT NAME</b></th>
                <th><b>REG#</b></th>
                <th><b>SPONSOR</b></th>
                <th style='width:14%;'><b>AGE</b></th>
                <th><b>GENDER</b></th>
                <th><b>PHONE#</b></th>
                <th><b>DOCTOR</b></th>
                <th style='width:5%;'><b>ACTION</b></th>
            </tr>
        </thead>";
            $temp = 1;
            while ($row = mysqli_fetch_assoc($excecuteQuery)) {
                $Date_Of_Birth = $row['Date_Of_Birth'];
                $age = floor((strtotime(date('Y-m-d')) - strtotime($Date_Of_Birth)) / 31556926) . " Years";
                $date1 = new DateTime($Today);
                $date2 = new DateTime($Date_Of_Birth);
                $diff = $date1->diff($date2);
                $age = $diff->y . " Years, ";
                $age.= $diff->m . " Months";
                $htm.= "<tr>";
                $htm.= "<td id='thead'>" . $temp . "<td>" . ucwords(strtolower($row['Patient_Name'])) . "</td>";
                $htm.= "<td>" . $row['Registration_ID'] . "</td>";
                $htm.= "<td>" . $row['Sponsor_Name'] . "</td>";
                $htm.= "<td>" . $age . "</td>";
                $htm.= "<td>" . $row['Gender'] . "</td>";
                $htm.= "<td>" . $row['Phone_Number'] . "</td>";
                $htm.= "<td>" . $row['Employee_Name'] . "</td>";
                if (isset($_POST['action'])) {
                    $htm.= "<td><input type='button' class='searchresults' name='" . $row['Patient_Name'] . "' id='" . $row['Registration_ID'] . "' value='Lab results' /> <input type='button' class='removing' id='" . $row['Payment_Item_Cache_List_ID'] . "' value='Remove' /></td>";
                } else {
                    $htm.= "<td><input type='button' filter=\"" . $filter . "\" class='results' name='" . $row['Patient_Name'] . "' id='" . $row['Registration_ID'] . "' payment_id='" . $row['Payment_Cache_ID'] . "' value='Lab results' /> 
				 
				 <input type='button' class='removing' id='" . $row['Payment_Item_Cache_List_ID'] . "' value='Remove' style='width:78px'/></td>";
                }
                $htm.= "</tr>";
                $temp++;
            }
            $htm.= "</table></center>";
            echo $htm;
        }
    }
} else {
    $filter = ' AND DATE(TimeSubmitted) BETWEEN CURDATE()-INTERVAL 1 DAY AND DATE(NOW())';
    $navFilter = ' AND DATE(TimeSubmitted) BETWEEN CURDATE()-INTERVAL 1 DAY AND DATE(NOW())';
    $idsfilters = ' AND DATE(tbl_test_results.TimeSubmitted) BETWEEN CURDATE()-INTERVAL 1 DAY AND DATE(NOW())';
    $filterpatient = '';
    if (isset($_GET['filterlabpatientdate']) && $_GET['filterlabpatientdate'] == true) {
        $Date_From = filter_input(INPUT_GET, 'Date_From');
        $Date_To = filter_input(INPUT_GET, 'Date_To');
        $Sponsor = filter_input(INPUT_GET, 'Sponsor');
        $subcategory_ID = filter_input(INPUT_GET, 'subcategory_ID');
        $patient_name = filter_input(INPUT_GET, 'patient_name');
        $Registration_ID = filter_input(INPUT_GET, 'seach_patient_id');
        $searchspecmen_id = filter_input(INPUT_GET, 'searchspecmen_id');
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
        if ($Sponsor != 'All') {
            $filter.= " AND sp.Sponsor_ID='$Sponsor'";
        }
        if ($subcategory_ID != 'All') {
            $filter.= " AND i.Item_Subcategory_ID='$subcategory_ID'";
            $navFilter.= " AND i.Item_Subcategory_ID='$subcategory_ID'";
        }
        if (!empty($patient_name)) {
            $filterpatient = " AND Patient_Name LIKE '%" . $patient_name . "%'";
        }
        if (!empty($Registration_ID)) {
            $filterpatient = " AND pr.Registration_ID LIKE '%" . $Registration_ID . "%'";
        }
        if (!empty($searchspecmen_id)) {
            $filterpatient = " AND il.Payment_Item_Cache_List_ID LIKE '%" . $searchspecmen_id . "%'";
        }
    }
    $validateQuery = "SELECT payment_item_ID FROM tbl_tests_parameters_results JOIN tbl_test_results ON test_result_ID=ref_test_result_ID WHERE Validated='Yes' $idsfilters GROUP BY test_result_ID";
    $anwerQuery = mysqli_query($conn, $validateQuery) or die(mysqli_error($conn));
    $num_rows = mysqli_num_rows($anwerQuery);
    $paymentID = array();
    while ($results = mysqli_fetch_assoc($anwerQuery)) {
        $paymentID[] = $results['payment_item_ID'];
    }
    $listItems = implode(',', $paymentID);
    $select_Filtered_Patients = '';
    if ($num_rows > 0) {
        $select_Filtered_Patients = "SELECT * FROM tbl_test_results tr,tbl_item_list_cache il,tbl_payment_cache pc,tbl_patient_registration pr,tbl_employee em,tbl_sponsor sp,tbl_items i WHERE payment_item_ID=Payment_Item_Cache_List_ID AND il.Payment_Cache_ID= pc.Payment_Cache_ID AND i.Item_ID=il.Item_ID AND pr.Registration_ID=pc.Registration_ID AND em.Employee_ID=pc.Employee_ID AND tr.removed_status='No' AND sp.Sponsor_ID =pr.Sponsor_ID AND payment_item_ID NOT IN ($listItems) $filter $filterpatient GROUP BY pc.Payment_Cache_ID ORDER BY TimeSubmitted ASC  LIMIT 100";
    } else {
        $select_Filtered_Patients = "SELECT * FROM tbl_test_results tr,tbl_item_list_cache il,tbl_payment_cache pc,tbl_patient_registration pr,tbl_employee em,tbl_sponsor sp,tbl_items i WHERE payment_item_ID=Payment_Item_Cache_List_ID AND il.Payment_Cache_ID= pc.Payment_Cache_ID AND i.Item_ID=il.Item_ID AND pr.Registration_ID=pc.Registration_ID AND em.Employee_ID=pc.Employee_ID AND tr.removed_status='No' AND sp.Sponsor_ID =pr.Sponsor_ID $filter $filterpatient  GROUP BY pc.Payment_Cache_ID ORDER BY TimeSubmitted ASC LIMIT 100";
    }
    $excecuteQuery = mysqli_query($conn, $select_Filtered_Patients) or die(mysqli_error($conn));
    $Today_Date = mysqli_query($conn, "select now() as today") or die(mysqli_error($conn));
    while ($row = mysqli_fetch_array($Today_Date)) {
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
        $age = '';
    }
    $htm = "<center>
      <table width =100% id='resultsPatientList' class='display fixTableHead'>
        <thead>
            <tr style='background-color: #ccc;'>
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
    while ($row = mysqli_fetch_assoc($excecuteQuery)) {
        $Date_Of_Birth = $row['Date_Of_Birth'];
        $age = floor((strtotime(date('Y-m-d')) - strtotime($Date_Of_Birth)) / 31556926) . " Years";
        $date1 = new DateTime($Today);
        $date2 = new DateTime($Date_Of_Birth);
        $diff = $date1->diff($date2);
        $age = $diff->y . " Years, ";
        $age.= $diff->m . " Months";
        $htm.= "<tr>";
        $htm.= "<td id='thead'>" . $temp . "<td>" . ucwords(strtolower($row['Patient_Name'])) . "</td>";
        $htm.= "<td>" . $row['Registration_ID'] . "</td>";
        $htm.= "<td>" . $row['Sponsor_Name'] . "</td>";
        $htm.= "<td>" . $age . "</td>";
        $htm.= "<td>" . $row['Gender'] . "</td>";
        $htm.= "<td>" . $row['TimeSubmitted'] . "</td>";
        $htm.= "<td>" . $row['Phone_Number'] . "</td>";
        $htm.= "<td>" . $row['Employee_Name'] . "</td>";
        if (isset($_POST['action'])) {
            $htm.= "<td><input type='button' class='searchresults' name='" . $row['Patient_Name'] . "' id='" . $row['Registration_ID'] . "' value='Lab results' /> <input type='button' class='removing' id='" . $row['Payment_Item_Cache_List_ID'] . "' value='Remove' /></td>";
        } else {
            $htm.= "<td>
                    <input type='button' style='width:100%' filter=\"" . $navFilter . "\" class='results' name='" . $row['Patient_Name'] . "' id='" . $row['Registration_ID'] . "' payment_id='" . $row['Payment_Cache_ID'] . "' value='Lab results' /> 
                     <input type='button' style='width:100%' class='removing' id='" . $row['Payment_Item_Cache_List_ID'] . "' value='Remove' style='width:78px'/>
                     <input type='button' filter=\"" . $navFilter . "\" id='reg_" . $row['Registration_ID'] . "' onclick='doctorReview(\"" . $row['Patient_Name'] . "\"," . $row['Registration_ID'] . ")' value='Doctor Review'/> 
                   </td>";
        }
        $htm.= "</tr>";
        $temp++;
    }
    $htm.= "</table></center>";
    echo $htm;
};