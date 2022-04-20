<?php
//header("Content-Type:application/json");
set_time_limit(0);
include("./includes/connection.php");
include_once './nhif3/constants.php';
include_once './nhif3/ServiceManager.php';

$manager = new ServiceManager();

$temp = 0;
print_r(nhif_row_data_package(json_encode(json_decode($manager->GetPricePackageExcluded(FacilityCode),true)['ExcludedServices'])));

function nhif_row_data_package($result){
    global $conn;
    $data = json_decode($result,true);
	if(sizeof($data) > 10){
		mysqli_query($conn, "DELETE FROM `tbl_nhif_services_status` WHERE 1 ");
	}
    foreach ($data as $rows){
        $AuthorizationNo = $rows['ItemCode'];
        $AuthorizationStatus = $rows['SchemeID'];
        $CHNationalID = $rows['SchemeName'];
        $packages =  explode(',',$rows['ExcludedForProducts']);
        // $items = trim(explode('~',$packages));
        foreach ($packages as $package) {
          $CardExistence = explode('~',$package)[0];
        $check = mysqli_query($conn, "SELECT * FROM `tbl_nhif_services_status` WHERE `ItemCode`='$AuthorizationNo' AND `package_id`='$CardExistence'") or die(mysqli_error($conn));
        if(mysqli_num_rows($check) > 0){

        }else{
            $ql = mysqli_query($conn, "INSERT IGNORE INTO tbl_nhif_services_status (ItemCode,package_id) VALUES ('$AuthorizationNo','$CardExistence')") or die(mysqli_error($conn));
            if($ql){
                $ql2 = mysqli_query($conn, "INSERT IGNORE INTO tbl_nhif_package_services (ItemCode) VALUES ('$AuthorizationNo')") or die(mysqli_error($conn));
                if($ql2){}
            }
        }
      }
    }
    return $result;
}
?>
