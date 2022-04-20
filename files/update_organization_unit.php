<?php 
include("./includes/connection.php");
ini_set('display_errors', true);
if(isset($_GET['organization_unit_id'])&&isset($_GET['orgUnit'])){
    $organization_unit_id=$_GET['organization_unit_id'];
    $orgUnit=$_GET['orgUnit'];
    ///check if exist
    $sql_select_orgunit_result=mysqli_query($conn,"SELECT organization_unit_id FROM tbl_organization_unit WHERE organization_unit_id='$organization_unit_id'") or die(mysqli_error($conn));
    if(mysqli_num_rows($sql_select_orgunit_result)>0){
       $sql_update_orgUnit=mysqli_query($conn,"UPDATE tbl_organization_unit SET orgUnit='$orgUnit'") or die(mysqli_error($conn));
    }else{
        $sql_insert_org_unit=mysqli_query($conn,"INSERT INTO tbl_organization_unit(orgUnit) VALUES('$orgUnit')") or die(mysqli_error($conn));
    }
}

