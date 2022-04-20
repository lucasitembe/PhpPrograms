<?php
    if (isset($_SESSION['userinfo'])) {
        echo "<a href='returninward_session_clearer.php?sessionname=Inward_ID' class='art-button-green'>NEW</a>";

        echo "<a href='returninwardpending.php' class='art-button-green'>PENDING</a>";

        echo "<a href='returninwardsaved.php' class='art-button-green'>SAVED</a>";

        echo "<a href='returninwardoutwardworks.php?ReturnInwardOutward=ReturnInwardOutwardThisPage' class='art-button-green'>BACK</a>";
    }
?>