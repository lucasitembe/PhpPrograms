<?php

require_once('includes/connection.php');


if (isset($_POST['main'])) {
   echo $main = $_POST['main'];
} else {
    $main = '';
}
if (isset($_POST['history'])) {
  echo  $history = $_POST['history'];
} else {
    $history = '';
}

  foreach ($main as $main_name){
                       
               $main = $main_name;
//                    $duration_name  = $duration[$i];
//                        $i++;
//                        $sql_attache=mysqli_query($conn,"INSERT INTO tbl_consultation (maincomplain) VALUES('$main'") or dei(mysqli_error($conn));   
                   }
//                   
//   foreach ($history as  $history_name){
//                       
//                   $history_name;
////                    $duration_name  = $duration[$i];
////                        $i++;
//                        $sql_attache=mysqli_query($conn,"INSERT INTO tbl_consultation (history_present_illness) VALUES('$history_name'") or dei(mysqli_error($conn));   
//                   }
