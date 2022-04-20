<?php session_start(); ?>
<?php
  
  if(isset($_SESSION['Stock_Taking_ID'])){
      unset($_SESSION['Stock_Taking_ID']);
  }

  header("Location: ./stocktaking.php");