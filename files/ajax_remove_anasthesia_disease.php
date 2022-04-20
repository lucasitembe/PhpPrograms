
<?php
    include("./includes/connection.php");
        session_start();
        if(isset($_POST['anasthesia_diagnosis_id'])){
            $anasthesia_diagnosis_id= $_POST['anasthesia_diagnosis_id'];
        }else{
            $anasthesia_diagnosis_id="";  
        }

        $remove_disease_selected = "DELETE FROM `tbl_anasthesia_diagnosis` WHERE anasthesia_diagnosis_id = '$anasthesia_diagnosis_id'";
        $remove_disease_selected_result = mysqli_query($conn, $remove_disease_selected);