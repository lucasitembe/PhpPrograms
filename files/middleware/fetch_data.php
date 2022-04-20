<?php 
    require "middleware.php";
    $receive_data=file_get_contents('php://input');
    print_r(query_select($receive_data));
?>