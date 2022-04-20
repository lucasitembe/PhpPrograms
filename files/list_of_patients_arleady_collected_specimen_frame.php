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
        $filter .= " AND i.Item_Subcategory_ID='$subcategory_ID'";
        $navFilter .= " AND i.Item_Subcategory_ID='$subcategory_ID'";
    }
    if (!empty($Patient_Name)) {
        $filter .= " AND pr.Patient_Name LIKE '%" . $Patient_Name . "%'";
    }
    if (!empty($Registration_ID)) {
        $filter .= " AND pr.Registration_ID ='$Registration_ID'";
    }
    if (!empty($searchspecmen_id)) {
        $filter .= " AND il.Payment_Item_Cache_List_ID LIKE '%" . $searchspecmen_id . "%'";
    }


    $validateQuery = "SELECT payment_item_ID FROM tbl_tests_parameters_results JOIN tbl_test_results ON test_result_ID=ref_test_result_ID WHERE Validated='Yes' AND Submitted='Yes' $idsfilters GROUP BY test_result_ID";
    $anwerQuery = mysqli_query($conn, $validateQuery) or die(mysqli_error($conn));
    $num_rows = mysqli_num_rows($anwerQuery);
    $paymentID = array();
    while ($results = mysqli_fetch_assoc($anwerQuery)) {
        $paymentID[] = $results['payment_item_ID'];
    }

    $listItems = implode(',', $paymentID); //tbl_sponsor
    $select_Filtered_Patients = '';
    if ($num_rows > 0) {
        $select_Filtered_Patients = "SELECT * FROM tbl_test_results tr,tbl_item_list_cache il,tbl_payment_cache pc,tbl_patient_registration pr,tbl_employee em,tbl_sponsor sp,tbl_items i WHERE payment_item_ID=Payment_Item_Cache_List_ID AND il.Payment_Cache_ID= pc.Payment_Cache_ID AND i.Item_ID=il.Item_ID AND pr.Registration_ID=pc.Registration_ID AND em.Employee_ID=pc.Employee_ID AND tr.removed_status='No' AND sp.Sponsor_ID =pr.Sponsor_ID AND payment_item_ID NOT IN ($listItems) $filter  ORDER BY TimeSubmitted ASC  LIMIT 100";
    } else {
        $select_Filtered_Patients = "SELECT * FROM tbl_test_results tr,tbl_item_list_cache il,tbl_payment_cache pc,tbl_patient_registration pr,tbl_employee em,tbl_sponsor sp,tbl_items i WHERE payment_item_ID=Payment_Item_Cache_List_ID AND il.Payment_Cache_ID= pc.Payment_Cache_ID AND i.Item_ID=il.Item_ID AND pr.Registration_ID=pc.Registration_ID AND em.Employee_ID=pc.Employee_ID AND tr.removed_status='No' AND sp.Sponsor_ID =pr.Sponsor_ID $filter   ORDER BY TimeSubmitted ASC LIMIT 100";
    }

    //echo $select_Filtered_Patients;
    //die($select_Filtered_Patients);

    $excecuteQuery = mysqli_query($conn, $select_Filtered_Patients) or die(mysqli_error($conn));
    $Today_Date = mysqli_query($conn, "select now() as today") or die(mysqli_error($conn));
    while ($row = mysqli_fetch_array($Today_Date)) {
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
        $age = '';
    }

    $temp = 1;
    while ($row = mysqli_fetch_assoc($excecuteQuery)) {
        $paymentitemID = $row['Payment_Item_Cache_List_ID'];
        $excecuteQueryTime = mysqli_query($conn, "SELECT `TimeCollected`,payment_item_ID,Specimen_ID,received_status, Employee_Name FROM tbl_specimen_results sr, tbl_employee em WHERE sr.payment_item_ID='$paymentitemID' AND em.Employee_ID = sr.specimen_results_Employee_ID") or die(mysqli_error($conn));
        $rowTime = mysqli_fetch_assoc($excecuteQueryTime);
        $received_status = $rowTime['received_status'];
        $Employee_Name = $rowTime['Employee_Name'];
        if ($received_status == "received") {
            continue;
        }

        $Date_Of_Birth = $row['Date_Of_Birth'];
        $age = floor((strtotime(date('Y-m-d')) - strtotime($Date_Of_Birth)) / 31556926) . " Years";

        $date1 = new DateTime($Today);
        $date2 = new DateTime($Date_Of_Birth);
        $diff = $date1->diff($date2);
        $age = $diff->y . " Years, ";
        $age .= $diff->m . " Months";
        
        $htm .= "<tr>";
        $htm .= "<td id='thead'>" . $temp . "<td>" . ucwords(strtolower($row['Patient_Name'])) . "</td>";
        $htm .= "<td>" . $row['Registration_ID'] . "</td>";
        $htm .= "<td>" . $row['Sponsor_Name'] . "</td>";
        $htm .= "<td>" . $age . "</td>";
        $htm .= "<td>" . $row['Gender'] . "</td>";
        $htm .= "<td>" . $row['Product_Name'] . "</td>";
        $htm .= "<td>" . $row['Payment_Item_Cache_List_ID'] . "</td>";
        $htm .= "<td>" . ucwords(strtolower($Employee_Name)) . "</td>";
        $htm .= "<td>" . $rowTime['TimeCollected'] . "</td>";

        $htm .= "<td><table><tr><td><input type='button' value='Receive' class='art-button-green' onclick='receiveSpecimen(this," . $row['Registration_ID'] . ");' name='" . $rowTime['payment_item_ID'] . "' id='" . $rowTime['Specimen_ID']. "'></td>";
        
        $htm .= "<td><input type='button' value='Reject' class='art-button-green' onclick='rejectSpecimen(this,".$row['Registration_ID'].",".$rowTime['Specimen_ID'].$temp.");' name='" . $rowTime['payment_item_ID'] . "' id='" . $rowTime['Specimen_ID']."'></td></tr></table></td>";
        $Specimen_ID = $rowTime['Specimen_ID'];

        $htm .= "<td><select class='rejectionreason form-control'  id='reject_$Specimen_ID$temp' ><option value=''>Choose reason for rejection</option>";
        $sql_select_added_title_result = mysqli_query($conn, "SELECT `reason_id`, `reason` FROM `tbl_rejection_reasons`") or die(mysqli_error($conn));
        if (mysqli_num_rows($sql_select_added_title_result) > 0) {
            $count = 1;
            while ($added_level_rows = mysqli_fetch_assoc($sql_select_added_title_result)) {
                $document_approval_level_title_id = $added_level_rows['reason_id'];
                $document_approval_level_title = $added_level_rows['reason'];
                $htm .= "<option value='" . $document_approval_level_title . "'>" . $document_approval_level_title . "</option>";
                $count++;
            }
        }
        $htm .= "</td></tr>";
        $htm .= "<tr>
                        <td colspan='7' style='background:#DEDEDE'></td>
                        <td colspan='4'>Sample Quality :-
                            <label>Suitable <input type='radio' name='sample_quality' id='suitable_$Specimen_ID'/></label>
                            <label>Unsuitable <input type='radio' name='sample_quality' id='unsuitable_$Specimen_ID'/></label>
                            <label>Explain</label>  <input type='text' placeholder='Enter Explanation' id='explaination_$Specimen_ID' style='width:40%'>
                        </td>
                    </tr>";
        $temp++;
        //}				
    }
    $htm .= "</table></center>";
    echo $htm;
?>