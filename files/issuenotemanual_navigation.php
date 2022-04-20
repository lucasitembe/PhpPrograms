<?php
    if (isset($_SESSION['userinfo'])) {
         if(isset($_GET['from_phamacy_works'])){
            $from_phamacy_works="&from_phamacy_works=yes";
        }else{
            $from_phamacy_works="";
        }
        echo "<a href='session_clearer.php?sessionname=IssueManual_ID$from_phamacy_works' class='art-button-green'>NEW ISSUE NOTE (MANUAL)</a>";
    }

    if (isset($_SESSION['userinfo'])) {
        echo "<a href='issuenotemanualpending.php' class='art-button-green'>PENDING</a>";
    }

    if (isset($_SESSION['userinfo'])) {
        echo "<a href='issuenotemanualsaved.php' class='art-button-green'>PROCESSED ISSUE NOTES</a>";
    }


    if (isset($_SESSION['userinfo'])) {
        if(isset($_GET['from_phamacy_works'])){
            echo "<a href='pharmacyworks.php?section=Pharmacy&PharmacyWorks=PharmacyWorksThisPage' class='art-button-green'>BACK</a>";
        }else{
            echo "<a href='storageandsupply.php?StorageAndSupply=StorageAndSupplyThisPage' class='art-button-green'>BACK</a>";
        }
    }
?>