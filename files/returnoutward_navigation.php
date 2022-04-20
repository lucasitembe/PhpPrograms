<?php
    if (isset($_SESSION['userinfo'])) {
        echo "<a href='returnoutward_session_clearer.php?sessionname=Outward_ID' class='art-button-green'>NEW</a>";

        echo "<a href='returnoutwardpending.php' class='art-button-green'>PENDING</a>";

        echo "<a href='returnoutwardsaved.php' class='art-button-green'>SAVED</a>";

        echo "<a href='returninwardoutwardworks.php?ReturnInwardOutward=ReturnInwardOutwardThisPage' class='art-button-green'>BACK</a>";
    }
?>