<?php

@session_start();
include("./includes/connection.php");
// $filter = ' AND DATE(pl.Transaction_Date_And_Time)=DATE(NOW())'; 

$Date_From = filter_input(INPUT_GET, 'Date_From');
$Date_To = filter_input(INPUT_GET, 'Date_To');
//$Patient_Name = filter_input(INPUT_GET, 'Patient_Name');
//$Patient_number = filter_input(INPUT_GET, 'Patient_number'); //
//$Old_patient_number = filter_input(INPUT_GET, 'Search_Old_Patient_number');

//$filter = " DATE(pr.Registration_Date_And_Time) = DATE(NOW()) ";
$filter2 = " ORDER BY img.Time_Given DESC ";

if (isset($Date_To) && !empty($Date_To) && isset($Date_From) && !empty($Date_From)) {
    $filter2 = " AND img.Time_Given BETWEEN '" . $Date_From . "' AND '" . $Date_To . "'";
}
/*
if (!empty($Patient_Name)) {
    $filter .="  AND pr.Patient_Name like '%$Patient_Name%'";
}

if (!empty($Patient_number)) {
    $filter .="  AND pr.Registration_ID = '$Patient_number'";
}

if (!empty($Old_patient_number)) {
    $filter .="  AND pr.Old_Registration_Number = '$Old_patient_number'";
}
*/
//echo $filter;exit;

$n = 1;

//GET BRANCH ID
//$Folio_Branch_ID = $_SESSION['userinfo']['Branch_ID'];

//today function
/*$Today_Date = mysqli_query($conn,"select now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
    $age = '';
}*/

echo '<center><table width ="100%" id="myPatients">';
echo " <thead>
       <tr ><th style='width:5%;'>SN</th>
            <th><b>DRUG NAME</b></th>
            <th><b>DISCONTINUED PATIENT NUMBER</b></th>
            <th><b>VIEW LIST</b></th>
         </tr>
       </thead>";

        /*$sql = "
                SELECT pr.Registration_ID,pr.Old_Registration_Number,pr.Gender,pr.Patient_Name,pr.Phone_Number,pr.Member_Number,pr.Date_Of_Birth,sp.Guarantor_Name,pr.Registration_Date_And_Time
                FROM  tbl_patient_registration pr 
                JOIN tbl_sponsor sp ON sp.Sponsor_ID = pr.Sponsor_ID
                WHERE 
                  $filter
                ORDER BY pr.Old_Registration_Number
            "; 
            $select_Filtered_Patients = mysqli_query($conn,$sql) or die(mysqli_error($conn));

            */

$medication_list="SELECT distinct i.product_name, i.Item_ID from tbl_inpatient_medicines_given img, tbl_items i, tbl_patient_registration pr WHERE img.Registration_ID=pr.Registration_ID AND img.Discontinue_Status='yes' AND i.Item_ID=img.Item_ID  $filter2";
$medication_results=mysqli_query($conn,$medication_list) or die(mysqli_error($conn));
if(mysqli_num_rows($medication_results)>0){
while ($row = mysqli_fetch_array($medication_results)) {
    $Item_ID=$row['Item_ID'];
    $select_quantity=mysqli_query($conn,"SELECT COUNT(img.Item_ID) as num FROM tbl_inpatient_medicines_given img, tbl_patient_registration pr WHERE img.Registration_ID=pr.Registration_ID AND Item_ID=$Item_ID AND Discontinue_Status='yes' $filter2");
    $quantity=mysqli_fetch_assoc($select_quantity);
    $quantitt=0;
    echo "<tr ><td >" . $n . "</td>";
    echo "<td>" . $row['product_name'] . "</td>";
    echo "<td style='text-align:center;'>" . $quantity['num'] . "</td>";
    echo "<td> <a href='#' onclick='preview_list(".$Item_ID.");' style='display:block;background-color:rgb(3,125,176);color:white;text-align:center;text-decoration:none;'>" . 'VIEW LIST' . "</a></td>";

    echo "</tr>";

    $n++;
}
}
echo '</table></center>';

 
?>