<?php

include './includes/connection.php';

$fromDate = DATE('Y-m-d H:m:s');
$toDate = DATE('Y-m-d H:m:s');
$employee_id=0;
$filterDoct='';
$filter = " ilc.ServedDateTime BETWEEN '$fromDate' AND '$toDate' AND ilc.Status='served'  AND ilc.Check_In_Type='Procedure'";

if (isset($_GET['fromDate'])) {
    $fromDate = $_GET['fromDate'];
    $toDate = $_GET['toDate'];
    $employee_id = $_GET['employee_id'];
    $filter = " ilc.ServedDateTime BETWEEN '$fromDate' AND '$toDate' AND ilc.Status='served'  AND ilc.Check_In_Type='Procedure'";
}

if ($employee_id != 'All') {
    $filter .=" AND ilc.ServedBy='$employee_id'";
    $filterDoct = " AND ilc.ServedBy='$employee_id'";
}

$selectEmployee = "SELECT e.Employee_ID, Employee_Name FROM tbl_item_list_cache ilc INNER JOIN tbl_employee e ON e.Employee_ID=ilc.ServedBy WHERE  Employee_Type='Doctor' AND $filter GROUP BY ilc.ServedBy";
$selectEmployee_qry = mysqli_query($conn,$selectEmployee) or die(mysqli_error($conn));
if(mysqli_num_rows($selectEmployee_qry) > 0){
$dataRange = returnBetweenDates($fromDate, $toDate);
while ($emp = mysqli_fetch_array($selectEmployee_qry)) {
    $empname = $emp['Employee_Name'];
    $empid = $emp['Employee_ID'];

    echo "<div class='daterange'>" . $empname . "<span> </span></div><br/>";

    $totalPPP = 0;
    foreach ($dataRange as $value) {
        $thisDate = date('d, M y', strtotime($value)) . '';

        $notIn = array('-1');

        $selectPatients = "SELECT pr.Registration_ID,Patient_Name,Gender,Date_Of_Birth,Guarantor_Name,Employee_Name AS Consultant,ilc.Consultant_ID FROM tbl_item_list_cache ilc JOIN tbl_items i ON ilc.Item_ID=i.Item_ID JOIN tbl_payment_cache pp ON pp.Payment_Cache_ID =ilc.Payment_Cache_ID JOIN tbl_patient_registration pr ON pr.Registration_ID =pp.Registration_ID JOIN tbl_sponsor sp ON sp.Sponsor_ID=pp.Sponsor_ID JOIN tbl_employee e ON e.Employee_ID=ilc.ServedBy WHERE DATE(ilc.ServedDateTime)='$value'  AND ilc.ServedBy='$empid'  AND ilc.Status='served'  AND ilc.Check_In_Type='Procedure' AND ilc.Payment_Item_Cache_List_ID NOT IN (" . implode(',', $notIn) . ")";

        $select_data_patient_result = mysqli_query($conn,$selectPatients) or die(mysqli_error($conn));
        $noOfPatient = mysqli_num_rows($select_data_patient_result);

        if (mysqli_num_rows($select_data_patient_result) > 0) {

            echo "<div class='daterange'>" . $thisDate . "<span style='float:right'>PATIENT NO. $noOfPatient </span></div>";
            echo '<center><table width =100% border=0 id="patientspecimenCollected" class="display">';
            echo "<thead>
	                    <tr>
                                <th  width='4%'>SN</th>
                                <th style='text-align: left;' width='20%'><b>PATIENT NAME</th>
                                <th style='text-align: left;' >REG #</th>
                                <th style='text-align: left;' >SPONSOR</th>
                                <th style='text-align: left;' width='30%'>PROCEDURE NAME</th>
                                <th style='text-align: left;' width='20%'>DATE TIME</th>
		            </tr>
                         </thead>";

            $count = 1;
            while ($row = mysqli_fetch_array($select_data_patient_result)) {
                $registration_ID = $row['Registration_ID'];
                $patientName = $row['Patient_Name'];
                $Registration_ID = $row['Registration_ID'];
                $Consultant = $row['Consultant'];
                $Guarantor_Name = $row['Guarantor_Name'];
                $Gender = $row['Gender'];
                $dob = $row['Date_Of_Birth'];
                $Consultant_ID = $row['Consultant_ID'];

                //these codes are here to determine the age of the patient
                $date1 = new DateTime(date('Y-m-d'));
                $date2 = new DateTime($dob);
                $diff = $date1->diff($date2);
                $age = $diff->y . " Years, ";
                $age .= $diff->m . " Months, ";
                $age .= $diff->d . " Days";

                //get its patient items

                $select_items = "SELECT Product_Name,Payment_Item_Cache_List_ID,ServedDateTime FROM tbl_item_list_cache ilc JOIN tbl_items i ON ilc.Item_ID=i.Item_ID JOIN tbl_payment_cache pp ON pp.Payment_Cache_ID =ilc.Payment_Cache_ID JOIN tbl_sponsor sp ON sp.Sponsor_ID=pp.Sponsor_ID WHERE  DATE(ilc.ServedDateTime)='$value'  AND ilc.ServedBy='$empid' AND ilc.Status='served'  AND ilc.Check_In_Type='Procedure' AND pp.Registration_ID='$Registration_ID' AND ilc.Payment_Item_Cache_List_ID NOT IN (" . implode(',', $notIn) . ")";

                $selected_items = mysqli_query($conn,$select_items) or die(mysqli_error($conn));

                $products = '';
                $itemdates = '';
                $numberOfItem = mysqli_num_rows($selected_items);
                $track = 1;
                while ($rowdata = mysqli_fetch_array($selected_items)) {
                    $Product_Name = $rowdata['Product_Name'];
                    $ppil = $rowdata['Payment_Item_Cache_List_ID'];
                    $Date_Time = $rowdata['ServedDateTime'];
                    $notIn[] = $ppil;

                    if ($numberOfItem == 1) {
                        $products = $Product_Name;
                        $itemdates = $Date_Time;
                    } else {
                        if ($track < $numberOfItem) {
                            $products .=$Product_Name . ',  ';
                            $itemdates .= $Date_Time . ',  ';
                        } else {
                            $products .=$Product_Name . '.';
                            $itemdates .= $Date_Time . '.';
                        }
                    }

                    $track++;
                }

                if (!empty($products)) {

                    echo "<tr><td>" . $count++ . "</td>";
                    echo "<td style='text-align:left; '>" . $patientName . "</td>";
                    echo "<td style='text-align:left; '>" . $row['Registration_ID'] . "</td>";
                    echo "<td style='text-align:left; '>" . $Guarantor_Name . "</td>";
                    echo "<td style='text-align:left; '>" . $products . "</td>";
                    echo "<td style='text-align:left; '>" . $itemdates . "</td>";
                    echo " </tr>";
                }
            }
            echo "</table></center><br/><br/>";
        }
    }
}
}else{
     echo '0';
}
//function between
function returnBetweenDates($startDate, $endDate) {
    $startStamp = strtotime($startDate);
    $endStamp = strtotime($endDate);

    if ($endStamp > $startStamp) {
        while ($endStamp >= $startStamp) {

            $dateArr[] = date('Y-m-d', $startStamp);

            $startStamp = strtotime(' +1 day ', $startStamp);
        }
        return $dateArr;
    } else {
        return $startDate;
    }
}
