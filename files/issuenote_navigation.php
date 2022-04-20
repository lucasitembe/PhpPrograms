<?php
    if (isset($_SESSION['userinfo'])) {
        echo "<a href='issuenote_session_clearer.php?sessionname=Issue_ID' class='art-button-green'>NEW</a>";

        echo "<a href='issuenotepending.php' class='art-button-green'>PENDING</a>";

        echo "<a href='issuenotesaved.php' class='art-button-green'>SAVED</a>";

        echo "<a href='issuenoteoutwardworks.php?ReturnInwardOutward=ReturnInwardOutwardThisPage' class='art-button-green'>BACK</a>";
    }
?>