<?php if (session_status() == PHP_SESSION_NONE) { session_start(); } ?>
<?php
    if(isset($_SESSION['Outward_ID'])){
        unset($_SESSION['Outward_ID']);
    }

    header("Location: ./returnoutward.php");
?>