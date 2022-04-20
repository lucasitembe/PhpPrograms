<?php
    session_start();
    include("./includes/connection.php");
    
    
    if(isset($_SESSION['Disposal_ID'])){
        $Disposal_ID = $_SESSION['Disposal_ID'];
    }else{
        $Disposal_ID = 'new';
    }
    echo $Disposal_ID;    
?>