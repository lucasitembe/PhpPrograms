<?php if (session_status() == PHP_SESSION_NONE) { session_start(); } ?>
<?php
    if(isset($_SESSION['Requisition_ID'])){
        unset($_SESSION['Requisition_ID']);
    }

    header("Location: ./requisition.php");
?>