<?php
include("./includes/connection.php");
$spu_lgn_username=$_POST['spu_lgn_username'];
$spu_lgn_password=$_POST['spu_lgn_password'];
$spu_lgn_password=MD5($spu_lgn_password);
$sql_select_user_prevalage_information="SELECT Given_Username,Given_Password FROM tbl_privileges WHERE Given_Username='$spu_lgn_username' "
        . "AND Given_Password='$spu_lgn_password' AND super_user='yes'";
$sql_select_user_prevalage_information=mysqli_query($conn,$sql_select_user_prevalage_information) or die(mysqli_error($conn));
if(mysqli_num_rows($sql_select_user_prevalage_information)>0){
  echo 1; 
  $_SESSION['super_user_login']="yes";
}else{
  $_SESSION['super_user_login']="no";
  echo 0;
}
?>
