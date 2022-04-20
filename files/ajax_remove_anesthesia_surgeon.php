
<?php
    include("./includes/connection.php");
        session_start();
        if(isset($_POST['anesthesia_surgeon_id'])){
            $anesthesia_surgeon_id= $_POST['anesthesia_surgeon_id'];
        }else{
            $anesthesia_surgeon_id="";  
        }

        $remove_surgeon_selected = "DELETE FROM `tbl_anasthesia_surgeon` WHERE anesthesia_surgeon_id = '$anesthesia_surgeon_id'";
        $remove_surgeon_selected_result = mysqli_query($conn, $remove_surgeon_selected);