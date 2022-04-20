<?php
    if (isset($_SESSION['userinfo'])) {
        echo "<a href='requisition_session_clearer.php?sessionname=Requisition_ID' class='art-button-green'>NEW</a>";

        echo "<a href='requisitionpending.php' class='art-button-green'>APPROVE REQUISITION</a>";

        echo "<a href='requisitionsaved.php' class='art-button-green'>SAVED</a>";

        echo "<a href='storageandsupply.php?StorageAndSupply=StorageAndSupplyThisPage' class='art-button-green'>BACK</a>";
    }
?>