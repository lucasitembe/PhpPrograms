<?php 
include '../includes/connection.php';
$id=$_POST['id'];
$sql_delete_terminal="DELETE FROM tbl_epay_offline_terminals_config WHERE id='$id'";
$sql_delete_terminal_result=mysqli_query($conn,$sql_delete_terminal) or die(mysqli_error($conn));
if($sql_delete_terminal_result){
    echo "yes";
}else{
    echo "no";
}
?>