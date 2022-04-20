<?php if (session_status() == PHP_SESSION_NONE) { session_start(); } ?>
<?php
    if(isset($_SESSION['Issue_ID'])){
        unset($_SESSION['Issue_ID']);
    }

    header("Location: ./issuenote.php");
?>