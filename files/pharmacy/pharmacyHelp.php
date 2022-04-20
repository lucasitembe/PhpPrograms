<?php
    $helpId = (isset($_GET['helpId']) ? $_GET['helpId'] : 0);

    if($helpId == 1){
        include 'how-to-dispense-cash.php';
    }else if($helpId == 2){
        include 'how-to-dispense-credit.php';
    }else if($helpId == 3){
        include 'change-department.php';
    }else{
        echo '<h2 style="text-align:center;font-family:arial">Updates Coming</h2>';
    }
?>