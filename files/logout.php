<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
session_start();
include("./includes/connection.php");
require_once("audittrail.php");
echo '<a href="./login.php"></a>';

//	employee id from logout
$emp_id=mysqli_real_escape_string($conn,$_GET['emp_id']);

#audit trail
include 'audit_trail_file.php';
$Audit = new Audit_Trail($emp_id,"");
$Audit->perfomAuditLogout();

//	get logout time 
if(isset($emp_id) && $emp_id !='' ){
	$last_attendance=mysqli_fetch_assoc(mysqli_query($conn,"SELECT attend_id FROM tbl_attendance WHERE employee_id = $emp_id ORDER BY attend_id DESC LIMIT 1"))['attend_id'];
	mysqli_query($conn,"UPDATE tbl_attendance SET check_out = (SELECT NOW()) WHERE  attend_id = $last_attendance");
}
$Employee_ID = $_SESSION['userinfo']['Employee_ID'];
$select_audit_trail = @mysqli_query($conn,"SELECT MAX(ID) AS ID FROM tbl_audit WHERE Employee_ID = '$Employee_ID' ");
$ID = mysqli_fetch_array($select_audit_trail)['ID'];

//run the query to update the details for login
$Logout_Date_And_Time = date('Y-m-d H:i:s');
$query = @mysqli_query($conn,"UPDATE tbl_audit SET Logout_Date_And_Time = '$Logout_Date_And_Time' WHERE ID='$ID' ");
//function to audit
//audit($_SESSION['userinfo']['Employee_ID'],'Logged out','Login Page','',$_SESSION['userinfo']['Branch_ID']);
session_destroy();

$url="http://127.0.0.1/spireportal/savelogins.php";
$data=array('type'=>'remove');
$options =array(
    'http'=>array(
        'header'=>'Content-type: application/x-www-form-urlencoded',
        'method'=>'POST',
        'content'=> http_build_query($data)
    )
);

$context= stream_context_create($options);
$result= file_get_contents($url,false,$context);
?>
<script type="text/javascript">
window.location = "../index.php"
</script>