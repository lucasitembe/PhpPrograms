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

            $validateQuery = "SELECT payment_item_ID FROM tbl_tests_parameters_results JOIN tbl_test_results ON test_result_ID=ref_test_result_ID WHERE Validated='Yes' GROUP BY test_result_ID";
            $anwerQuery = mysqli_query($conn,$validateQuery);
            $paymentID = array();
            while ($results = mysqli_fetch_assoc($anwerQuery)) {
                $paymentID[] = $results['payment_item_ID'];
            }
            $listItems = implode(',', $paymentID);

            $select_Filtered_Patients = mysqli_query($conn,
                    "SELECT * FROM tbl_test_results,tbl_item_list_cache,tbl_payment_cache,tbl_patient_registration,tbl_employee WHERE payment_item_ID=Payment_Item_Cache_List_ID AND tbl_item_list_cache.Payment_Cache_ID= tbl_payment_cache.Payment_Cache_ID AND tbl_patient_registration.Registration_ID=tbl_payment_cache.Registration_ID AND tbl_employee.Employee_ID=tbl_payment_cache.Employee_ID AND payment_item_ID IN ($listItems) $filter GROUP BY tbl_patient_registration.Registration_ID ORDER BY test_result_ID ASC");
        } else {


            $validateQuery = "SELECT * FROM tbl_tests_parameters_results JOIN tbl_test_results ON test_result_ID=ref_test_result_ID WHERE Validated='Yes' AND payment_item_ID='" . $payingID . "' GROUP BY test_result_ID";
            $anwerQuery = mysqli_query($conn,$validateQuery);
            // $paymentID=array();
            $results = mysqli_fetch_assoc($anwerQuery);
//     while ($results=mysqli_fetch_assoc($anwerQuery)){
//         $paymentID[]=$results['payment_item_ID'];
//     }
//     $listItems=implode(',',$paymentID);
//     
            $select_Filtered_Patients = mysqli_query($conn,"SELECT * FROM tbl_test_results,tbl_item_list_cache,tbl_payment_cache,tbl_patient_registration,tbl_employee WHERE payment_item_ID=Payment_Item_Cache_List_ID AND tbl_item_list_cache.Payment_Cache_ID= tbl_payment_cache.Payment_Cache_ID AND tbl_patient_registration.Registration_ID=tbl_payment_cache.Registration_ID AND tbl_employee.Employee_ID=tbl_payment_cache.Employee_ID AND tbl_test_results.removed_status='No' AND payment_item_ID='" . $results['payment_item_ID'] . "' GROUP BY tbl_patient_registration.Registration_ID ORDER BY test_result_ID ASC");
        }
    }
} else {
    $filter = ' AND DATE(Transaction_Date_And_Time) BETWEEN CURDATE()-INTERVAL 1 DAY AND DATE(NOW())';

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

    $validateQuery = "SELECT payment_item_ID FROM tbl_tests_parameters_results JOIN tbl_test_results ON test_result_ID=ref_test_result_ID WHERE Validated='Yes' GROUP BY test_result_ID";
    $anwerQuery = mysqli_query($conn,$validateQuery);
    $num_rows = mysqli_num_rows($anwerQuery);
    $paymentID = array();
    while ($results = mysqli_fetch_assoc($anwerQuery)) {
        $paymentID[] = $results['payment_item_ID'];
    }
    $listItems = implode(',', $paymentID);
    if ($num_rows > 0) {
        $select_Filtered_Patients = mysqli_query($conn,
                "SELECT * FROM tbl_test_results,tbl_item_list_cache,tbl_payment_cache,tbl_patient_registration,tbl_employee,tbl_sponsor WHERE payment_item_ID=Payment_Item_Cache_List_ID AND tbl_item_list_cache.Payment_Cache_ID= tbl_payment_cache.Payment_Cache_ID AND tbl_patient_registration.Registration_ID=tbl_payment_cache.Registration_ID AND tbl_employee.Employee_ID=tbl_payment_cache.Employee_ID AND tbl_sponsor.Sponsor_ID =tbl_patient_registration.Sponsor_ID AND payment_item_ID IN ($listItems) $filter  GROUP BY tbl_patient_registration.Registration_ID ORDER BY TimeSubmitted ASC");
    } else {
        $select_Filtered_Patients = mysqli_query($conn,
                "SELECT * FROM tbl_test_results,tbl_item_list_cache,tbl_payment_cache,tbl_patient_registration,tbl_employee,tbl_sponsor WHERE payment_item_ID=Payment_Item_Cache_List_ID AND tbl_item_list_cache.Payment_Cache_ID= tbl_payment_cache.Payment_Cache_ID AND tbl_patient_registration.Registration_ID=tbl_payment_cache.Registration_ID AND tbl_employee.Employee_ID=tbl_payment_cache.Employee_ID AND tbl_sponsor.Sponsor_ID =tbl_patient_registration.Sponsor_ID AND payment_item_ID IN (0) $filter   GROUP BY tbl_patient_registration.Registration_ID ORDER BY TimeSubmitted ASC");
    }
}

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
while ($row = mysqli_fetch_array($select_Filtered_Patients)) {
    //$Product_Name=$row['Product_Name'];
    /* $Submitted=$row['Submitted'];
      $Validated=$row['Validated']; */
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
				 
                <input type='button' class='searchresults' filter=\"".$filter."\" name='" . $row['Patient_Name'] . "' id='" . $row['Registration_ID'] . "' payment_id='" . $row['Payment_Cache_ID'] . "' value='Lab results' />
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
    $(document).on('click', '.searchresults', function () {
        var patient = $(this).attr('name');
        var id = $(this).attr('id');
        var filter=$(this).attr('filter');
        var payment_id = $(this).attr('payment_id');
        var barcode = $('#searchbarcode').val();
        $.ajax({
            type: 'POST',
            url: 'requests/testResultsConsulted.php',
            data: 'action=getResult&id=' + id + '&payment_id=' + payment_id + '&barcode=' + barcode+'&filter='+filter,
            cache: false,
            success: function (html) {
                //alert(html);
                $('#showLabResultsHere').html(html);
            }
        });

        $('#labResults').dialog({
            modal: true,
            width: '90%',
            height: 550,
            resizable: true,
            draggable: true,
        }).dialog("widget")
                .next(".ui-widget-overlay")
                .css({
                    background: "rgb(100,100,100)",
                    opacity: 1
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
