<?php
    @session_start();
    include("./includes/connection.php");
    
    if(isset($_GET['Registration_ID'])){
        $Registration_ID = $_GET['Registration_ID'];
    }else{
        $Registration_ID = 0;
    }
    
    if(isset($_GET['Guarantor_Name'])){
        $Guarantor_Name = $_GET['Guarantor_Name'];
    }else{
        $Guarantor_Name = '';
    }
    
    if(isset($_SESSION['userinfo']['Employee_ID'])){
        $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    }else{
        $Employee_ID = 0;
    }
    
    
    $select_Transaction_Items = mysqli_query($conn,
				 "select Billing_Type
				    from tbl_pharmacy_inpatient_items_list_cache alc
				    where alc.Employee_ID = '$Employee_ID' and
				    Registration_ID = '$Registration_ID' LIMIT 1") or die(mysqli_error($conn));
    
    $no = mysqli_num_rows($select_Transaction_Items);
    if($no > 0){
        while($row = mysqli_fetch_array($select_Transaction_Items)){
            $Billing_Type = $row['Billing_Type'];
        }
        echo '<option selected="selected">'.$Billing_Type.'</option>';
    }else{
        if(strtolower($Guarantor_Name) == 'cash'){
            echo '<option selected="selected">Inpatient Cash</option>';
        }else{
            echo '<option selected="selected">Inpatient Credit</option>';
            echo '<option>Inpatient Cash</option>';
        }
    }
?>