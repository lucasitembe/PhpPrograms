<?php
include("./includes/connection.php");
$Biopsy_ID = $_GET['Biopsy_ID'];
$New_Case = $_GET['New_Case'];
$relevant_clinical_data = $_GET['relevant_clinical_data'];
$Laboratory_Number = $_GET['Laboratory_Number'];
$Site_Biopsy = $_GET['Site_Biopsy'];
$Previous_Laboratory = $_GET['Previous_Laboratory'];
$Duration_Condition = $_GET['Duration_Condition'];
$Comments = $_GET['Comments'];
$Referred_From = $_GET['Referred_From'];
if(!empty($Biopsy_ID)){   
    $Update_biopsy = mysqli_query($conn, "UPDATE tbl_histological_examination SET New_Case='$New_Case', relevant_clinical_data='$relevant_clinical_data', Laboratory_Number='$Laboratory_Number', Site_Biopsy='$Site_Biopsy', Previous_Laboratory = '$Previous_Laboratory', Duration_Condition='$Duration_Condition', Comments='$Comments', Referred_From='$Referred_From' WHERE Biopsy_ID='$Biopsy_ID'");
}

?>