<?php session_start(); ?>
<?php
  
  if(isset($_SESSION['Disposal_ID'])){
      unset($_SESSION['Disposal_ID']);
  }

  header("Location: ./itemsdisposal.php");