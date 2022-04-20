
<?php
    include("./includes/connection.php");
        session_start();
        if(isset($_POST['assist_anasthetist_id'])){
            $assist_anasthetist_id= $_POST['assist_anasthetist_id'];
        }else{
            $assist_anasthetist_id="";  
        }

        $remove_assist_anesthetist_selected = "DELETE FROM `tbl_anasthesia_assist_anasthetist` WHERE assist_anasthetist_id = '$assist_anasthetist_id'";
        $remove_assist_anesthetist_selected_result = mysqli_query($conn, $remove_assist_anesthetist_selected); 