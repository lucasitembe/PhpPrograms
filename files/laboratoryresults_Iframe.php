<link rel="stylesheet" href="table1.css" media="screen">
<?php
@session_start();
include("./includes/connection.php");
$temp = 1;
$Employee_ID = $_SESSION['userinfo']['Employee_ID'];
if (isset($_GET['Patient_Name'])) {
    $Patient_Name = $_GET['Patient_Name'];
} else {
    $Patient_Name = '';
}

if (isset($_GET['Patient_Number'])) {
    $Patient_Number = $_GET['Patient_Number'];
} else {
    $Patient_Number = '';
}
if (isset($_GET['Phone_Number'])) {
    $Phone_Number = $_GET['Phone_Number'];
} else {
    $Phone_Number = '';
}

$filterNav = '';

//Find the current date to filter check in list

$Today_Date = mysqli_query($conn,"select now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
}

//get employee id
if (isset($_SESSION['userinfo']['Employee_ID'])) {
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
} else {
    $Employee_ID = 0;
}

$filter = ' AND DATE(tprs.TimeSubmitted) = DATE(NOW()) ';
$filternoem = ' AND DATE(tprs.TimeSubmitted) = DATE(NOW()) ';

$Date_From = filter_input(INPUT_GET, 'Date_From');
$Date_To = filter_input(INPUT_GET, 'Date_To');
$MaximumTimeToSeePatientHistory = $_SESSION['configData']['MaximumTimeToSeePatientHistory'];

if (isset($Date_To) && !empty($Date_To) && isset($Date_From) && !empty($Date_From)) {
    $filter = "  AND tprs.TimeSubmitted BETWEEN '" . $Date_From . "' AND '" . $Date_To . "'";
    $filternoem = "  AND tprs.TimeSubmitted BETWEEN '" . $Date_From . "' AND '" . $Date_To . "'";

    if ($MaximumTimeToSeePatientHistory > 0) {
        $filter .= "  AND TIMESTAMPDIFF(HOUR,tprs.TimeSubmitted,NOW()) <= $MaximumTimeToSeePatientHistory ";
        $filternoem .= "  AND TIMESTAMPDIFF(HOUR,tprs.TimeSubmitted,NOW()) <= $MaximumTimeToSeePatientHistory ";
    }
}

$hospitalConsultType = $_SESSION['hospitalConsultaioninfo']['consultation_Type'];
$emp = '';

if ($hospitalConsultType == 'One patient to one doctor') {
    $emp = "AND tlc.Consultant_ID =" . $_SESSION['userinfo']['Employee_ID'] . " ";
    $empsnot = "AND tlc.Consultant_ID !=" . $_SESSION['userinfo']['Employee_ID'] . " ";

    $filter .= "  AND tlc.Consultant_ID =" . $_SESSION['userinfo']['Employee_ID'] . " ";
    $filternoem .= "  AND tlc.Consultant_ID !=" . $_SESSION['userinfo']['Employee_ID'] . " ";
}

$filterNav = "Date_From=$Date_From&Date_To=$Date_To";

if (!empty($Patient_Name)) {
    $filter .= " AND pr.Patient_Name LIKE '%$Patient_Name%'";
    $filternoem .= " AND pr.Patient_Name LIKE '%$Patient_Name%'";
}

$select_Filtered_Patients = mysqli_query($conn,"SELECT pr.Patient_Name,pr.Registration_ID,pr.Date_Of_Birth,pc.Sponsor_Name,pr.Phone_Number,pr.Gender,em.Employee_Name,pc.consultation_id FROM
					tbl_test_results as trs,tbl_tests_parameters_results as tprs,tbl_item_list_cache tlc,
					tbl_payment_cache pc,tbl_patient_registration pr,
					tbl_employee em,tbl_consultation tc,tbl_patient_payment_item_list  tpipi WHERE
					payment_item_ID=Payment_Item_Cache_List_ID AND
					tlc.Payment_Cache_ID= pc.Payment_Cache_ID AND
					tc.consultation_id=pc.consultation_id AND
					tc.Patient_Payment_Item_List_ID=tpipi.Patient_Payment_Item_List_ID AND tpipi.Process_Status !='signedoff' AND
				        trs.test_result_ID=tprs.ref_test_result_ID AND
				        tprs.Submitted='Yes' AND pr.Registration_ID=pc.Registration_ID AND
					em.Employee_ID=pc.Employee_ID  AND tc.consultation_id NOT IN (select consultation_id from tbl_consultation c join tbl_patient_payment_item_list_transfer p ON c.Patient_Payment_Item_List_ID=p.Patient_Payment_Item_List_ID) $emp 
                                        $filter 
                                        GROUP BY pr.Registration_ID
					ORDER BY test_result_ID ASC") or die(mysqli_error($conn));


$Today_Date = mysqli_query($conn,"select now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
    $age = '';
}
$htm = "<center><table width ='100%' border='0' id='patientsResultInfo'>";
$htm .= "<thead>
            <tr>
                <th style='width:5%;'><b>SN</b></th>
                <th><b>PATIENT NAME</b></th>
                <th style='width:8%;'><b>REG NO.</b></th>
                <th><b>SPONSOR</b></th>
                <th style='width:15%;'><b>AGE</b></th>
                <th width=5%><b>GENDER</b></th>
                <th><b>DOCTOR NAME</b></th>
                <th><b>PHONE No.</b></th>
            </tr>
        </thead>";

//echo $filterNav;exit;

$temp = 1;
while ($row = mysqli_fetch_array($select_Filtered_Patients)) {
    //$Product_Name=$row['Product_Name'];
    $Date_Of_Birth = $row['Date_Of_Birth'];
    $age = floor((strtotime(date('Y-m-d')) - strtotime($Date_Of_Birth)) / 31556926) . " Years";

    //Retrieve Patient_Payment_ID,Payment_Item_Cache_List_ID by consultationID
    $queryPay = "SELECT ppl.Patient_Payment_ID,ppl.Patient_Payment_Item_List_ID 
			           FROM tbl_patient_payment_item_list AS ppl JOIN tbl_consultation AS tcon ON ppl.Patient_Payment_Item_List_ID=tcon.Patient_Payment_Item_List_ID				   
					   WHERE consultation_ID='" . $row['consultation_id'] . "' AND Registration_ID='" . $row['Registration_ID'] . "'
					   ";

    //echo $row['consultation_ID'].'<br/>';
    $resultPay = mysqli_query($conn,$queryPay) or die(mysqli_error($conn));
    $pay = mysqli_fetch_assoc($resultPay);



    $date1 = new DateTime($Today);
    $date2 = new DateTime($Date_Of_Birth);
    $diff = $date1->diff($date2);
    $age = $diff->y . " Years, ";
    $age .= $diff->m . " Months";
//              $age.= $diff->d." Days";
//              if(strtolower($Product_Name)=='registration and consultation fee'){
//              }else{Payment_Item_Cache_List_ID
    $htm .= "<tr>";
    $htm .= "<td>" . $temp . "</td><td><a href='laboratory_result_details.php?Registration_ID=" . $row['Registration_ID'] . "&Patient_Payment_ID=" . $pay['Patient_Payment_ID'] . "&Patient_Payment_Item_List_ID=" . $pay['Patient_Payment_Item_List_ID'] . "&$filterNav' target='_parent' style='text-decoration: none';>" . ucwords(strtolower($row['Patient_Name'])) . "</a></td>";
    $htm .= "<td><a href='laboratory_result_details.php?Registration_ID=" . $row['Registration_ID'] . "&Patient_Payment_ID=" . $pay['Patient_Payment_ID'] . "&Patient_Payment_Item_List_ID=" . $pay['Patient_Payment_Item_List_ID'] . "&$filterNav' target='_parent' style='text-decoration: none';>" . $row['Registration_ID'] . "</a></td>";
    $htm .= "<td><a href='laboratory_result_details.php?Registration_ID=" . $row['Registration_ID'] . "&Patient_Payment_ID=" . $pay['Patient_Payment_ID'] . "&Patient_Payment_Item_List_ID=" . $pay['Patient_Payment_Item_List_ID'] . "&$filterNav' target='_parent' style='text-decoration: none';>" . $row['Sponsor_Name'] . "</a></td>";
    $htm .= "<td><a href='laboratory_result_details.php?Registration_ID=" . $row['Registration_ID'] . "&Patient_Payment_ID=" . $pay['Patient_Payment_ID'] . "&Patient_Payment_Item_List_ID=" . $pay['Patient_Payment_Item_List_ID'] . "&$filterNav' target='_parent' style='text-decoration: none';>" . $age . "</a></td>";
    $htm .= "<td><a href='laboratory_result_details.php?Registration_ID=" . $row['Registration_ID'] . "&Patient_Payment_ID=" . $pay['Patient_Payment_ID'] . "&Patient_Payment_Item_List_ID=" . $pay['Patient_Payment_Item_List_ID'] . "&$filterNav' target='_parent' style='text-decoration: none';>" . $row['Gender'] . "</a></td>";
    $htm .= "<td><a href='laboratory_result_details.php?Registration_ID=" . $row['Registration_ID'] . "&Patient_Payment_ID=" . $pay['Patient_Payment_ID'] . "&Patient_Payment_Item_List_ID=" . $pay['Patient_Payment_Item_List_ID'] . "&$filterNav' target='_parent' style='text-decoration: none';>" . $row['Employee_Name'] . "</a></td>";
    $htm .= "<td><a href='laboratory_result_details.php?Registration_ID=" . $row['Registration_ID'] . "&Patient_Payment_ID=" . $pay['Patient_Payment_ID'] . "&Patient_Payment_Item_List_ID=" . $pay['Patient_Payment_Item_List_ID'] . "&$filterNav' target='_parent' style='text-decoration: none';>" . $row['Phone_Number'] . "</a></td>";


    $htm .= "</tr>";
    $temp++;
}

//Check for transfer for one to one doctor only 
$totalab = 0;
$totalrad = 0;
if ($hospitalConsultType == 'One patient to one doctor') {

    $select_Filtered_Patients2 = mysqli_query($conn,"SELECT pr.Patient_Name,pr.Registration_ID,pr.Date_Of_Birth,pc.Sponsor_Name,pr.Phone_Number,pr.Gender,em.Employee_Name,pc.consultation_id FROM
					tbl_test_results as trs,tbl_tests_parameters_results as tprs,tbl_item_list_cache tlc,
					tbl_payment_cache pc,tbl_patient_registration pr,
					tbl_employee em,tbl_consultation tc,tbl_patient_payment_item_list  tpipi WHERE
					payment_item_ID=Payment_Item_Cache_List_ID AND
					tlc.Payment_Cache_ID= pc.Payment_Cache_ID AND
					tc.consultation_id=pc.consultation_id AND
					tc.Patient_Payment_Item_List_ID=tpipi.Patient_Payment_Item_List_ID AND tpipi.Process_Status !='signedoff' AND
				        trs.test_result_ID=tprs.ref_test_result_ID AND
				        tprs.Submitted='Yes' AND pr.Registration_ID=pc.Registration_ID AND
					em.Employee_ID=pc.Employee_ID 
                                        $filternoem 
                                        GROUP BY pr.Registration_ID
					ORDER BY test_result_ID ASC") or die(mysqli_error($conn));
    $arrayInLab = array(0);

    while ($qrc = mysqli_fetch_array($select_Filtered_Patients2)) {

        if (!in_array($qrc['consultation_id'], $arrayInLab)) {
            //check if is transfered
            $qrytrn = mysqli_query($conn,"SELECT c.Patient_Payment_Item_List_ID FROM tbl_consultation c 
                                  JOIN tbl_patient_payment_item_list_transfer p  ON c.Patient_Payment_Item_List_ID=p.Patient_Payment_Item_List_ID 
                                  JOIN tbl_patient_payment_item_list pl
                                  ON pl.Patient_Payment_Item_List_ID=p.Patient_Payment_Item_List_ID 
                                  where c.consultation_id='" . $qrc['consultation_id'] . "' AND pl.Consultant_ID='" . $_SESSION['userinfo']['Employee_ID'] . "'
                                  ") or die(mysqli_error($conn));

            if (mysqli_num_rows($qrytrn) > 0) {
                //$Product_Name=$row['Product_Name'];
                $Date_Of_Birth = $qrc['Date_Of_Birth'];
                $age = floor((strtotime(date('Y-m-d')) - strtotime($Date_Of_Birth)) / 31556926) . " Years";

                //Retrieve Patient_Payment_ID,Payment_Item_Cache_List_ID by consultationID
                $queryPay = "SELECT ppl.Patient_Payment_ID,ppl.Patient_Payment_Item_List_ID 
			           FROM tbl_patient_payment_item_list AS ppl JOIN tbl_consultation AS tcon ON ppl.Patient_Payment_Item_List_ID=tcon.Patient_Payment_Item_List_ID				   
					   WHERE consultation_ID='" . $qrc['consultation_id'] . "' AND Registration_ID='" . $qrc['Registration_ID'] . "'
					   ";

                //echo $row['consultation_ID'].'<br/>';
                $resultPay = mysqli_query($conn,$queryPay) or die(mysqli_error($conn));
                $pay = mysqli_fetch_assoc($resultPay);



                $date1 = new DateTime($Today);
                $date2 = new DateTime($Date_Of_Birth);
                $diff = $date1->diff($date2);
                $age = $diff->y . " Years, ";
                $age .= $diff->m . " Months";
//              $age.= $diff->d." Days";
//              if(strtolower($Product_Name)=='registration and consultation fee'){
//              }else{Payment_Item_Cache_List_ID
                $htm .= "<tr>";
                $htm .= "<td>" . $temp . "</td><td><a href='laboratory_result_details.php?Registration_ID=" . $qrc['Registration_ID'] . "&Patient_Payment_ID=" . $pay['Patient_Payment_ID'] . "&Patient_Payment_Item_List_ID=" . $pay['Patient_Payment_Item_List_ID'] . "&consultation_id=" . $qrc['consultation_id'] . "&$filterNav' target='_parent' style='text-decoration: none';>" . ucwords(strtolower($qrc['Patient_Name'])) . "</a></td>";
                $htm .= "<td><a href='laboratory_result_details.php?Registration_ID=" . $qrc['Registration_ID'] . "&Patient_Payment_ID=" . $pay['Patient_Payment_ID'] . "&Patient_Payment_Item_List_ID=" . $pay['Patient_Payment_Item_List_ID'] . "&consultation_id=" . $qrc['consultation_id'] . "&$filterNav' target='_parent' style='text-decoration: none';>" . $qrc['Registration_ID'] . "</a></td>";
                $htm .= "<td><a href='laboratory_result_details.php?Registration_ID=" . $qrc['Registration_ID'] . "&Patient_Payment_ID=" . $pay['Patient_Payment_ID'] . "&Patient_Payment_Item_List_ID=" . $pay['Patient_Payment_Item_List_ID'] . "&consultation_id=" . $qrc['consultation_id'] . "&$filterNav' target='_parent' style='text-decoration: none';>" . $qrc['Sponsor_Name'] . "</a></td>";
                $htm .= "<td><a href='laboratory_result_details.php?Registration_ID=" . $qrc['Registration_ID'] . "&Patient_Payment_ID=" . $pay['Patient_Payment_ID'] . "&Patient_Payment_Item_List_ID=" . $pay['Patient_Payment_Item_List_ID'] . "&consultation_id=" . $qrc['consultation_id'] . "&$filterNav' target='_parent' style='text-decoration: none';>" . $age . "</a></td>";
                $htm .= "<td><a href='laboratory_result_details.php?Registration_ID=" . $qrc['Registration_ID'] . "&Patient_Payment_ID=" . $pay['Patient_Payment_ID'] . "&Patient_Payment_Item_List_ID=" . $pay['Patient_Payment_Item_List_ID'] . "&consultation_id=" . $qrc['consultation_id'] . "&$filterNav' target='_parent' style='text-decoration: none';>" . $qrc['Gender'] . "</a></td>";
                $htm .= "<td><a href='laboratory_result_details.php?Registration_ID=" . $qrc['Registration_ID'] . "&Patient_Payment_ID=" . $pay['Patient_Payment_ID'] . "&Patient_Payment_Item_List_ID=" . $pay['Patient_Payment_Item_List_ID'] . "&consultation_id=" . $qrc['consultation_id'] . "&$filterNav' target='_parent' style='text-decoration: none';>" . $qrc['Employee_Name'] . "</a></td>";
                $htm .= "<td><a href='laboratory_result_details.php?Registration_ID=" . $qrc['Registration_ID'] . "&Patient_Payment_ID=" . $pay['Patient_Payment_ID'] . "&Patient_Payment_Item_List_ID=" . $pay['Patient_Payment_Item_List_ID'] . "&consultation_id=" . $qrc['consultation_id'] . "&$filterNav' target='_parent' style='text-decoration: none';>" . $qrc['Phone_Number'] . "</a></td>";


                $htm .= "</tr>";
                $temp++;
            }
        }
        $arrayInLab[] = $qrc['consultation_id'];
    }
}

$htm .= "</table></center>";
echo $htm;
?>

<script>
    $('.searchresults').click(function () {
        var patient = $(this).attr('name');
        var id = $(this).attr('id');
        $.ajax({
            type: 'POST',
            url: 'requests/testResults.php',
            data: 'action=getResult&id=' + id,
            cache: false,
            success: function (html) {
                //  alert(html);
                $('#showLabResultsHere').html(html);
            }
        });


        $('#labResults').dialog({
            modal: true,
            width: '90%',
            minHeight: 450,
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
</script>
