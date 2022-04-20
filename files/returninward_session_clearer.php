<?php if (session_status() == PHP_SESSION_NONE) { session_start(); } ?>
<?php
    if(isset($_SESSION['Inward_ID'])){
        unset($_SESSION['Inward_ID']);
    }

    header("Location: ./returninward.php");
?>