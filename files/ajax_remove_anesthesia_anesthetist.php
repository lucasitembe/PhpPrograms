
<?php
    include("./includes/connection.php");
        session_start();
        if(isset($_POST['anesthesia_anesthetist_id'])){
            $anesthesia_anesthetist_id= $_POST['anesthesia_anesthetist_id'];
        }else{
            $anesthesia_anesthetist_id="";  
        }

        $remove_anesthetist_selected = "DELETE FROM `tbl_anasthesia_anesthetist` WHERE anesthesia_anesthetist_id = '$anesthesia_anesthetist_id'";
        $remove_anesthetist_selected_result = mysqli_query($conn, $remove_anesthetist_selected); 