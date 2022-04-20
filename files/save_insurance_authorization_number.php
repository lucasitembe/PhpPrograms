
<?php include ("./includes/connection.php");
$from = @$_POST['from'];
if ($from == 'edit_claim') {
    $Payment_ID = $_POST['Payment_ID'];
    $Employee_ID = $_POST['Employee_ID'];
    $Treatment_Authorization_No = $_POST['Treatment_Authorization_No'];
    $updated= mysqli_query($conn, "UPDATE tbl_patient_payment_item_list SET Treatment_Authorization_No = '$Treatment_Authorization_No', Treatment_Authorizer = '$Employee_ID' WHERE Patient_Payment_Item_List_ID = '$Payment_ID'") or die(mysqli_error($conn));
    if($updated){
        $result['code'] = 200;
        echo json_encode($result);
    }else{
        $result['code'] = 400;
        echo json_encode($result);
    }
}  
if ($from == 'failed_claim'){
    $Item_ID = $_POST['Item_ID'];
    $Registration_ID = $_POST['Registration_ID'];
    $Payment_ID = $_POST['Payment_ID'];
    $Employee_ID = $_POST['Employee_ID'];
    $treatment_authorization_no = $_POST['treatment_authorization_no'];
    
    $results = mysqli_query($conn, "UPDATE tbl_item_list_cache SET Treatment_Authorization_No = $treatment_authorization_no, Treatment_Authorizer = $Employee_ID WHERE Item_ID= $Item_ID AND Payment_Item_Cache_List_ID = $Payment_ID") or die(mysqli_error($conn));
    if ($results) {
        $select_authorize = mysqli_query($conn, "SELECT Treatment_Authorization_No FROM tbl_item_list_cache WHERE Item_ID= $Item_ID AND Payment_Item_Cache_List_ID = $Payment_ID ") or die(mysqli_error($conn));
        $authorization_no = mysqli_fetch_assoc($select_authorize) ['Treatment_Authorization_No'];
        $result['code'] = 200;
        $result['number'] = $authorization_no;
        echo json_encode($result);
    } else {
        $result['code'] = 300;
        echo json_encode($result);
    }
}else{
    $result['code'] = 400;
    echo json_encode($result);
};

// ALTER TABLE `tbl_item_list_cache` ADD `Treatment_Authorizer` INT(15) NOT NULL AFTER `discount_by`, ADD `Treatment_Authorization_No` VARCHAR(50) NOT NULL AFTER `Treatment_Authorizer`; 

// ALTER TABLE `tbl_patient_payment_item_list` ADD `Treatment_Authorizer` INT NOT NULL AFTER `Treatment_Authorization_No`; 