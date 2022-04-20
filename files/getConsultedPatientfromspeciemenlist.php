<?php
include("./includes/connection.php");
if (isset($_POST['action'])) {
    if ($_POST['action'] == 'barcode') {
        //echo 'Tumefikaaa';
        $payingID = $_POST['value'];
        if ($payingID == '') {
            $filter = ' AND DATE(TimeSubmitted) BETWEEN CURDATE()-INTERVAL 1 DAY AND DATE(NOW())';

            if (isset($_GET['filterlabpatientdate']) && $_GET['filterlabpatientdate'] == true) {
                $Date_From = filter_input(INPUT_GET, 'Date_From');
                $Date_To = filter_input(INPUT_GET, 'Date_To');
                $Sponsor = filter_input(INPUT_GET, 'Sponsor');

                if (isset($Date_From) && !empty($Date_From)) {
                    $filter = " AND TimeSubmitted >='" . $Date_From . "'";
                }
                if (isset($Date_To) && !empty($Date_To)) {
                    $filter = " AND TimeSubmitted <='" . $Date_To . "'";
                }
                if (isset($Date_To) && !empty($Date_To) && isset($Date_From) && !empty($Date_From)) {
                    $filter = "  AND TimeSubmitted BETWEEN '" . $Date_From . "' AND '" . $Date_To . "'";
                }
                if (isset($Date_To) && empty($Date_To) && isset($Date_From) && empty($Date_From)) {
                    $filter = "";
                }

                if ($Sponsor != 'All') {
                    $filter .=" AND tbl_sponsor.Sponsor_ID='$Sponsor'";
                }
            }

            // $validateQuery = "SELECT payment_item_ID FROM tbl_tests_parameters_results,tbl_test_results WHERE test_result_ID=ref_test_result_ID and Validated='Yes' GROUP BY test_result_ID";
            // $anwerQuery = mysqli_query($conn,$validateQuery);
            // $paymentID = array();
            // while ($results = mysqli_fetch_assoc($anwerQuery)) {
            //     $paymentID[] = $results['payment_item_ID'];
            // }
            // $listItems = implode(',', $paymentID);

            // $select_Filtered_Patients = mysqli_query($conn,
            //         "SELECT * FROM tbl_test_results,tbl_item_list_cache,tbl_payment_cache,tbl_patient_registration,tbl_employee WHERE payment_item_ID=Payment_Item_Cache_List_ID AND tbl_item_list_cache.Payment_Cache_ID= tbl_payment_cache.Payment_Cache_ID AND tbl_patient_registration.Registration_ID=tbl_payment_cache.Registration_ID AND tbl_employee.Employee_ID=tbl_payment_cache.Employee_ID AND payment_item_ID IN ($listItems) $filter GROUP BY tbl_payment_cache.Payment_Cache_ID ORDER BY test_result_ID ASC");
        } else {


            // $validateQuery = "SELECT * FROM tbl_tests_parameters_results JOIN tbl_test_results ON test_result_ID=ref_test_result_ID WHERE Validated='Yes' AND payment_item_ID='" . $payingID . "' GROUP BY test_result_ID";
            // $anwerQuery = mysqli_query($conn,$validateQuery);
            // // $paymentID=array();
            // $results = mysqli_fetch_assoc($anwerQuery);
//     while ($results=mysqli_fetch_assoc($anwerQuery)){
//         $paymentID[]=$results['payment_item_ID'];
//     }
//     $listItems=implode(',',$paymentID);
//     
            // $select_Filtered_Patients = mysqli_query($conn,"SELECT * FROM tbl_test_results,tbl_item_list_cache,tbl_payment_cache,tbl_patient_registration,tbl_employee WHERE payment_item_ID=Payment_Item_Cache_List_ID AND tbl_item_list_cache.Payment_Cache_ID= tbl_payment_cache.Payment_Cache_ID AND tbl_patient_registration.Registration_ID=tbl_payment_cache.Registration_ID AND tbl_employee.Employee_ID=tbl_payment_cache.Employee_ID AND tbl_test_results.removed_status='No' AND payment_item_ID='" . $results['payment_item_ID'] . "' GROUP BY tbl_payment_cache.Payment_Cache_ID ORDER BY test_result_ID ASC");
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
        $patient_name = mysqli_real_escape_string($conn,filter_input(INPUT_GET, 'patient_name'));
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
            $filter .=" AND sp.Sponsor_ID='$Sponsor'";
        }

        if ($subcategory_ID != 'All') {
            $filter .=" AND i.Item_Subcategory_ID='$subcategory_ID'";
            $navFilter.=" AND i.Item_Subcategory_ID='$subcategory_ID'";
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

    $validateQuery = "SELECT payment_item_ID FROM tbl_tests_parameters_results, tbl_test_results WHERE test_result_ID = ref_test_result_ID and Validated='Yes' $idsfilters GROUP BY test_result_ID";
    $anwerQuery = mysqli_query($conn,$validateQuery);
    $num_rows = mysqli_num_rows($anwerQuery);
    $paymentID = array();
    while ($results = mysqli_fetch_assoc($anwerQuery)) {
        $paymentID[] = $results['payment_item_ID'];
    }
    $listItems = implode(',', $paymentID);
//    if ($num_rows > 0) {
//        $select_Filtered_Patients = mysqli_query($conn,
//                "SELECT * FROM tbl_test_results,tbl_item_list_cache,tbl_payment_cache,tbl_patient_registration,tbl_employee,tbl_sponsor WHERE payment_item_ID=Payment_Item_Cache_List_ID AND tbl_item_list_cache.Payment_Cache_ID= tbl_payment_cache.Payment_Cache_ID AND tbl_patient_registration.Registration_ID=tbl_payment_cache.Registration_ID AND tbl_employee.Employee_ID=tbl_payment_cache.Employee_ID AND tbl_sponsor.Sponsor_ID =tbl_patient_registration.Sponsor_ID AND payment_item_ID IN ($listItems) $filter  GROUP BY tbl_patient_registration.Registration_ID ORDER BY TimeSubmitted ASC");
//    } else {
//        $select_Filtered_Patients = mysqli_query($conn,
//                "SELECT * FROM tbl_test_results,tbl_item_list_cache,tbl_payment_cache,tbl_patient_registration,tbl_employee,tbl_sponsor WHERE payment_item_ID=Payment_Item_Cache_List_ID AND tbl_item_list_cache.Payment_Cache_ID= tbl_payment_cache.Payment_Cache_ID AND tbl_patient_registration.Registration_ID=tbl_payment_cache.Registration_ID AND tbl_employee.Employee_ID=tbl_payment_cache.Employee_ID AND tbl_sponsor.Sponsor_ID =tbl_patient_registration.Sponsor_ID AND payment_item_ID IN (0) $filter   GROUP BY tbl_patient_registration.Registration_ID ORDER BY TimeSubmitted ASC");
//    }

    if ($num_rows > 0) {
        $select_Filtered_Patients = "SELECT pr.Date_Of_Birth,pr.Registration_ID,pr.Patient_Name,pr.Gender,pr.Phone_Number,pc.Payment_Cache_ID,Sponsor_Name,Employee_Name,TimeSubmitted FROM tbl_test_results tr,tbl_item_list_cache il,tbl_payment_cache pc,tbl_patient_registration pr,tbl_employee em,tbl_sponsor sp,tbl_items i WHERE payment_item_ID=Payment_Item_Cache_List_ID AND il.Payment_Cache_ID= pc.Payment_Cache_ID AND i.Item_ID=il.Item_ID AND pr.Registration_ID=pc.Registration_ID AND em.Employee_ID=pc.Employee_ID AND sp.Sponsor_ID =pr.Sponsor_ID AND payment_item_ID IN ($listItems) $filter $filterpatient GROUP BY pr.Registration_ID,pc.Payment_Cache_ID ORDER BY TimeSubmitted ASC LIMIT 5";
    } else {
        $select_Filtered_Patients = "SELECT pr.Date_Of_Birth,pr.Registration_ID,pr.Patient_Name,pr.Gender,pr.Phone_Number,pc.Payment_Cache_ID,Sponsor_Name,Employee_Name,TimeSubmitted FROM tbl_test_results tr,tbl_item_list_cache il,tbl_payment_cache pc,tbl_patient_registration pr,tbl_employee em,tbl_sponsor sp,tbl_items i WHERE payment_item_ID=Payment_Item_Cache_List_ID AND il.Payment_Cache_ID= pc.Payment_Cache_ID AND i.Item_ID=il.Item_ID AND pr.Registration_ID=pc.Registration_ID AND em.Employee_ID=pc.Employee_ID  AND sp.Sponsor_ID =pr.Sponsor_ID  AND payment_item_ID IN (0) $filter $filterpatient GROUP BY pr.Registration_ID,pc.Payment_Cache_ID ORDER BY TimeSubmitted ASC LIMIT 5";
    }
}

$consultedPatient = mysqli_query($conn,$select_Filtered_Patients) or die(mysqli_error($conn));

$Today_Date = mysqli_query($conn,"select now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
    $age = '';
}
$htm = "<center>
      <table width =100% id='resultsPatientList' class='display'>
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
while ($row = mysqli_fetch_array($consultedPatient)) {
    //$Product_Name=$row['Product_Name'];
    /* $Submitted=$row['Submitted'];
      $Validated=$row['Validated']; */
    $Date_Of_Birth = $row['Date_Of_Birth'];
    $age = floor((strtotime(date('Y-m-d')) - strtotime($Date_Of_Birth)) / 31556926) . " Years";

    $date1 = new DateTime($Today);
    $date2 = new DateTime($Date_Of_Birth);
    $diff = $date1->diff($date2);
    $age = $diff->y . " Years, ";
    $age.= $diff->m . " Months, ";
    $age .= $diff->d . " Days";
//              $age.= $diff->d." Days";
//              if(strtolower($Product_Name)=='registration and consultation fee'){
//              }else{
    // if($Submitted =='Yes' && $Validated=='Yes'){
    // }else{
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
    $htm.="<td>
				 
                <input type='button' style='width:100%' class='searchresults' filter=\"" . $filter . "\" name='" . $row['Patient_Name'] . "' id='" . $row['Registration_ID'] . "' payment_id='" . $row['Payment_Cache_ID'] . "' value='Lab results' />
                <input type='button' filter=\"" . $navFilter . "\" id='reg_" . $row['Registration_ID'] . "' onclick='doctorReview(\"" . $row['Patient_Name'] . "\"," . $row['Registration_ID'] . ")' value='Doctor Review'/> 
                                 
</td>";

    // }

    $htm.="</tr>";
    $temp++;
    //}				
}
$htm .="</table></center>";
echo $htm;
?>





<script type="text/javascript">
    $(document).on('click', '.searchresults', function (e) {
        e.stopImmediatePropagation();

        var patient = $(this).attr('name');
        var id = $(this).attr('id');
        var filter = $(this).attr('filter');
        var payment_id = $(this).attr('payment_id');
        var barcode = $('#searchbarcode').val();


        $.ajax({
            type: 'GET',
            url: 'requests/testResultsConsulted.php',
            data: 'action=getResult&id=' + id + '&payment_id=' + payment_id + '&barcode=' + barcode + '&filter=' + filter,
            cache: false,
            beforeSend: function (xhr) {
                $('#progressDialogStatus').show();
            },
            success: function (html) {
                $('#showLabResultsHere').html(html);
            }, complete: function (jqXHR, textStatus) {
                $('#progressDialogStatus').hide();
            }
        });

        $('#labResults').dialog({
            modal: true,
            width: '90%',
            height: 550,
            resizable: true,
            draggable: true
        });

        $("#labResults").dialog('option', 'title', patient + '  ' + 'No.' + id);
    });

    $('#resultsProvidedList').click(function () {
        //$('#resultsconsultationLablist').text('CONSULTED LAB PATIENTS LIST')
        $.ajax({
            type: 'POST',
            url: 'getPatientfromspeciemenlist.php',
            data: 'action=consultedPatients&value=t',
            success: function (html) {
                $('#Search_Iframe').html(html);
            }
        });
    });
</script>
