<?php
include("./includes/connection.php");
$Registration_ID = $_GET['Registration_ID'];
$consultation_ID = $_GET['consultation_ID'];
$autospy = $_GET['autospy'];
$Priority = $_GET['Priority'];
$birth_region = $_GET['birth_region'];
$birth_district = $_GET['birth_district'];
$birth_village = $_GET['birth_village'];
$birth_year = $_GET['birth_year'];
$Employee_ID = $_GET['Employee_ID'];
$resident_year = $_GET['resident_year'];
// if(isset($_GET['Payment_Item_Cache_List_ID'])){
    $Payment_Item_Cache_List_ID = $_GET['Payment_Item_Cache_List_ID'];
// }else{
//     $Payment_Item_Cache_List_ID = NULL;
// }

// echo $Payment_Item_Cache_List_ID;

$Payment_Item_Cache_List_ID = !empty($Payment_Item_Cache_List_ID) ? "'$Payment_Item_Cache_List_ID'" : "NULL";

if(!empty($consultation_ID) && !empty($Registration_ID)){
    $Biopsy_ID = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Biopsy_ID FROM tbl_histological_examination WHERE consultation_ID = '$consultation_ID' AND Registration_ID = '$Registration_ID' AND Employee_ID = '$Employee_ID' AND DATE(Requested_Datetime) = CURDATE() AND Biposy_Status='pending'"))['Biopsy_ID'];

    if($Biopsy_ID <= 0){
        $INSERT_BBIOPSY = mysqli_query($conn, "INSERT INTO tbl_histological_examination (consultation_ID, Registration_ID, Employee_ID, Requested_Datetime, Payment_Item_Cache_List_ID) VALUES('$consultation_ID', '$Registration_ID', '$Employee_ID', NOW(), $Payment_Item_Cache_List_ID)") or die(mysqli_error($conn));

        if($INSERT_BBIOPSY){
            $Biopsy_ID = mysqli_insert_id($conn);
            echo '5';
        }
    }
    
    $Update_biopsy = mysqli_query($conn, "UPDATE tbl_histological_examination SET autospy='$autospy', Priority='$Priority', Payment_Item_Cache_List_ID = $Payment_Item_Cache_List_ID, birth_region='$birth_region', birth_district='$birth_district', birth_village = '$birth_village', birth_year='$birth_year', resident_year='$resident_year' WHERE Biopsy_ID='$Biopsy_ID'");

        if($Update_biopsy){
            echo '1';
        }else{
            echo '2' . mysqli_error($conn);
        }
}

mysqli_close($conn);
?>