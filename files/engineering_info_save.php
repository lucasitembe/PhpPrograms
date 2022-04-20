<?php
if (isset($_SESSION['userinfo'])) {
    $Employee_Name = $_SESSION['userinfo']['Employee_Name'];
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
} else {
    $Employee_Name = 'Unknown Officer';
    $Employee_ID = 0;
}

if($_POST)
{
$Jobcard_ID=$_POST['Jobcard_ID'];
$username=$_POST['username'];
$password=$_POST['password'];
$Employee_ID = $_SESSION['userinfo']['Employee_ID'];
mysqli_query($conn,"UPDATE tbl_jobcards set status='Certified', Certified_by='$Employee_ID', Certfied_at=NOW() where Jobcard_ID='$Jobcard_ID'");
}else {
    echo "FAILED";
}

?>