<?php
    include("./includes/connection.php");
        session_start();
        if(isset($_POST['Procedure_ID'])){
            $Procedure_ID= $_POST['Procedure_ID'];
        }else{
            $Procedure_ID="";  
        }

        $remove_procedure_selected = "DELETE FROM `tbl_anasthesia_procedure` WHERE Procedure_ID = '$Procedure_ID'";
        $remove_procedure_selected_result = mysqli_query($conn, $remove_procedure_selected);