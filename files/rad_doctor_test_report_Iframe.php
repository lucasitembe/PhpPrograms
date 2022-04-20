<style>
    .todialog{
        display: block;
        color: #2182B0;
    }
    .todialog:hover{
        cursor: pointer;
        text-decoration:underline 
    }
</style>
<?php
@session_start();
include("./includes/connection.php");

//$filter = "   AND Admission_Status='Admitted'  AND DATE(ad.Admission_Date_Time)=DATE(NOW())";

$Date_From = filter_input(INPUT_GET, 'Date_From');
$Date_To = filter_input(INPUT_GET, 'Date_To');
$testNameResults = filter_input(INPUT_GET, 'testNameResults');
$Sponsor = filter_input(INPUT_GET, 'Sponsor');
$employee_id= filter_input(INPUT_GET, 'employee_id');
$check_in_type= filter_input(INPUT_GET, 'check_in_type');

$filter = "   AND DATE(il.Transaction_Date_And_Time)=DATE(NOW())";
 

if (isset($Date_To) && !empty($Date_To) && isset($Date_From) && !empty($Date_From)) {
    $filter = "  AND  il.Transaction_Date_And_Time BETWEEN '" . $Date_From . "' AND '" . $Date_To . "' ";
}

if (!empty($Sponsor) && $Sponsor != 'All') {
    $filter .="  AND sp.Sponsor_ID=$Sponsor";
}
//echo $ward;exit;
if (!empty($employee_id) && $employee_id != 'All') {
    $filter .= " AND il.Consultant_ID  = $employee_id";
}


if (!empty($testNameResults)) {
  if($testNameResults=='ct-scan'){
        $filter .="  AND il.Item_ID IN (SELECT Item_ID FROM tbl_items WHERE Ct_Scan_Item = 'yes')";
  } else {
     $filter .="  AND il.Item_ID='$testNameResults'";  
  }
}

//echo $filter;
//exit();

//GET BRANCH ID

echo '<center><table width ="100%" id="doctosTestResult">';
echo "<thead>
             <tr >
                <th style='width:5%;'>SN</th>
                <th><b>DOCTORS</b></th>
                <th style='width:20%;'><b>NUMBER OF TESTS</b></th>
             </tr>
         </thead>";

$sql= "
        SELECT em.Employee_ID,em.Employee_Name FROM tbl_item_list_cache il 
            JOIN tbl_payment_cache pc ON il.Payment_Cache_ID=pc.Payment_Cache_ID
            JOIN tbl_patient_registration AS pr ON pr.Registration_ID =pc.Registration_ID
            JOIN tbl_sponsor AS sp ON sp.Sponsor_ID =pr.Sponsor_ID
            JOIN tbl_employee em ON em.Employee_ID=il.Consultant_ID
            WHERE Employee_Type='Doctor' AND il.Status != 'notsaved' AND Check_In_Type='$check_in_type' $filter  GROUP BY il.Consultant_ID
        ";

//echo $sql;exit;

  if($testNameResults=='ct-scan'){
         $testName= "CT Scan";
  } else {
    $testName= clean(mysqli_fetch_assoc(mysqli_query($conn,"SELECT Product_Name FROM tbl_items WHERE Item_ID='$testNameResults'"))['Product_Name']);
  }


$select_Filtered_Patients = mysqli_query($conn,$sql) or die(mysqli_error($conn));
$sn = 1;
while ($row = mysqli_fetch_array($select_Filtered_Patients)) {
    $Employee_ID=$row['Employee_ID'];
    $Employee_Name= strtoupper(clean($row['Employee_Name'])) ;
    
    $currentEmployee="currEmployeeID=".$Employee_ID.'&'.$_SERVER['QUERY_STRING'];
    
    $sqlItems=  mysqli_query($conn,"
            SELECT count(item_ID) as Number_Of_Test FROM `tbl_item_list_cache` il 
            JOIN tbl_payment_cache pc ON il.Payment_Cache_ID=pc.Payment_Cache_ID
            JOIN tbl_patient_registration AS pr ON pr.Registration_ID =pc.Registration_ID
            JOIN tbl_sponsor AS sp ON sp.Sponsor_ID =pr.Sponsor_ID
            WHERE `Consultant_ID`='$Employee_ID' and 
            Check_In_Type='$check_in_type' $filter
            ") or die(mysqli_error($conn));
    $Number_Of_Test=  mysqli_fetch_assoc($sqlItems)['Number_Of_Test'];
    
    $location='';
    if($check_in_type=='Radiology'){
        $location='';
    }
     echo '<tr>';
     echo '<td>'.$sn++.'</td>';
     echo "<td><span class='todialog' onclick='showDetails(\"".$currentEmployee."\",\"".$Employee_Name."\",\"".$testName."\")'>".$Employee_Name."</span></td>";
     echo "<td style='text-align:center'><span class='todialog' onclick='showDetails(\"".$currentEmployee."\",\"".$Employee_Name."\",\"".$testName."\")'>".number_format($Number_Of_Test)."</span></td>";
     echo '</tr>';
      
    ?>
    <?php
} echo "
   </table>
  </center>";


function clean($str){
    $str=  str_replace("\"", '', $str);
    $str=  str_replace("/", '', $str);
    $str=  str_replace("'", '', $str);
    
    $str=strip_tags(trim($str)); 
    
    $str=mysqli_real_escape_string($conn,$str);
    
    return $str;
}
?>