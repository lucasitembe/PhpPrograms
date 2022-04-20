<?php

include("./includes/connection.php");
@session_start();

if (isset($_POST['currentValue'])) {

    if (isset($_POST['src']) && $_POST['src'] == 'referral') {
        $Name = mysqli_real_escape_string($conn,$_POST['currentValue']);
        $id = mysqli_real_escape_string($conn,$_POST['id']);

        $sql = "UPDATE tbl_referral_hosp SET ref_hosp_name='$Name' WHERE hosp_ID='$id'";

        if (!mysqli_query($conn,$sql)) {
            $error = '1062yes';
            if (mysql_errno() . "yes" == $error) {
                echo 0;
            }
        } else {
            echo 1;
        }
    }
}