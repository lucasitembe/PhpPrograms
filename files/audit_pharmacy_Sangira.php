<?php
include("includes/connection.php");
$Payment_Item_Cache_List_ID = $_POST['Payment_Item_Cache_List_ID'];
$Employee_ID = $_POST['Employee_ID'];
$Action = $_POST['Action'];
$Epay_ID = $_POST['Epay_ID'];
$Edited_Quantity = $_POST['id_for_dispense'];

if(isset($_POST['Employee_ID']) && isset($_POST['Payment_Item_Cache_List_ID'])){
    if($Action == 'update_quantity'){
        $insert_audit = mysqli_query($conn, "INSERT INTO tbl_pharmacy_audit(Employee_ID, Payment_Item_Cache_List_ID, Bill_Payment_Code, Date_Time_Edited, Edited_Quantity) VALUES('$Employee_ID', '$Payment_Item_Cache_List_ID', '$Epay_ID', NOW(), '$Edited_Quantity')") or die(mysqli_error($conn));
    }
}
mysqli_close($conn);
?>