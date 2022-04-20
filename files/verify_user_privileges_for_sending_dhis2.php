<?php 
include("./includes/connection.php");
if(isset($_POST['username'])&&isset($_POST['password'])){
    $username=$_POST['username'];
    $password=$_POST['password'];
    $password=md5($password);
    $sql_select_usename_n_password_result=mysqli_query($conn,"SELECT Given_Username FROM tbl_privileges WHERE Given_Username='$username' AND Given_Password='$password'") or die(mysqli_error($conn));
    if(mysqli_num_rows($sql_select_usename_n_password_result)>0){
       echo "access_granted"; 
    }else{
        echo "access_denied";
    }
}
?>