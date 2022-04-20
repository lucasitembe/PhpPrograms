<?php session_start(); ?>
<?php
  
  if(isset($_SESSION['IssueManual_ID'])){
      unset($_SESSION['IssueManual_ID']);
  }
 if(isset($_GET['from_phamacy_works'])){
            $from_phamacy_works="?from_phamacy_works=yes";
        }else{
            $from_phamacy_works="";
        }
  header("Location: ./issuenotemanual.php$from_phamacy_works");