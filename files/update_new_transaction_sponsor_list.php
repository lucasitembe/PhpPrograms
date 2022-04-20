<?php 
include("./includes/connection.php");
$Sponsor_ID=$_GET['Sponsor_ID'];
$Billing_Type_filter=$_GET['Billing_Type'];
$Guarantor_Name=$_GET['Guarantor_Name'];
if($Billing_Type_filter=="Outpatient Credit"||$Billing_Type_filter=="Inpatient Credit"){
?>
<option value="<?= $Sponsor_ID ?>"><?= $Guarantor_Name ?></option>
<?php 
}else{
 ?>
<option value="" selected="selected"></option>
<?php    
}
    if($Billing_Type_filter=="Outpatient Credit"||$Billing_Type_filter=="Inpatient Credit"){
        $filter_sponsor="credit";
    }else{
        $filter_sponsor="WHERE payment_method='cash'";
    }
    $sql_select_sponsor_result=mysqli_query($conn,"SELECT Sponsor_ID,Guarantor_Name FROM tbl_sponsor $filter_sponsor") or die(mysqli_error($conn));
    if(mysqli_num_rows($sql_select_sponsor_result)>0){
       while($sponsor_rows=mysqli_fetch_assoc($sql_select_sponsor_result)){
          $Sponsor_ID_ch=$sponsor_rows['Sponsor_ID'];
          $Guarantor_Name_ch=$sponsor_rows['Guarantor_Name'];
          echo "<option value='$Sponsor_ID_ch'>$Guarantor_Name_ch</option>";
       } 
    }
?>

