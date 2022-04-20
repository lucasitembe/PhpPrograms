<?php
    @session_start();
    include("./includes/connection.php");
    require_once  './includes/ehms.function.inc.php';
    
    if(isset($_GET['Registration_ID'])){
        $Registration_ID = $_GET['Registration_ID'];
    }else{
        $Registration_ID = 0;
    }
    
    
    if(isset($_SESSION['userinfo']['Employee_ID'])){
        $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    }else{
        $Employee_ID = 0;
    }
    
    //get sponsor name 
    $select = mysqli_query($conn,"select Guarantor_Name,pr.Sponsor_ID from tbl_patient_registration pr, tbl_sponsor s where
                                pr.Sponsor_ID = s.Sponsor_ID and pr.Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
    $num = mysqli_num_rows($select);
    if($num > 0){
        while ($row = mysqli_fetch_array($select)) {
            $Guarantor_Name = $row['Guarantor_Name'];
            $Sponsor_ID = $row['Sponsor_ID'];
        }
    }else{
        $Guarantor_Name = '';
        $Sponsor_ID = '';
    }

    $select_Transaction_Items = mysqli_query($conn,
				 "select Billing_Type
				    from tbl_departmental_items_list_cache alc
				    where alc.Employee_ID = '$Employee_ID' and
				    Registration_ID = '$Registration_ID' LIMIT 1") or die(mysqli_error($conn));
    
    $no = mysqli_num_rows($select_Transaction_Items);
    if($no > 0){
        echo '<select name="Billing_Type" id="Billing_Type">';
        while($row = mysqli_fetch_array($select_Transaction_Items)){
?>
            <option><?php echo $row['Billing_Type']; ?></option>
<?php
        }
    echo "</select>";
    }else{
        if(strtolower($Guarantor_Name) == 'cash'  || strtolower(getPaymentMethod($Sponsor_ID))=='cash'){
?>
        <select name="Billing_Type" id="Billing_Type">
            <option selected="selected">Outpatient Cash</option>
        </select>
<?php
        }else{
?>
            <select name="Billing_Type" id="Billing_Type">
                <option selected="selected">Outpatient Credit</option>
                <option>Outpatient Cash</option>
            </select>
<?php
        }
    }
?>