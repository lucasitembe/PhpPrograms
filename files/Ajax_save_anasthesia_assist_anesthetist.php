
        <?php
        include("./includes/connection.php");
        session_start();
        if(isset($_POST['Employee_ID'])){ 
        $Assist_anesthetist_ID= $_POST['Employee_ID'];
        }else{
        $Assist_anesthetist_ID="";   
        }
 


        if(isset($_POST['Registration_ID'])){
            $Registration_ID= $_POST['Registration_ID'];
        }else{
        $Registration_ID="";   
        }
        if(isset($_POST['Payment_Cache_ID'])){
            $Payment_Cache_ID = $_POST['Payment_Cache_ID'];
        }else{
            $Payment_Cache_ID= "";
        }
        $anasthesia_record = "SELECT anasthesia_record_id FROM tbl_anasthesia_record_chart WHERE Registration_ID = '$Registration_ID' AND Anaesthesia_status='OnProgress'";
        $anasthesia_record_result=mysqli_query($conn,$anasthesia_record) or die(mysqli_error($conn));
        if(mysqli_num_rows($anasthesia_record_result)>0){
            $anasthesia_record_id = mysqli_fetch_assoc($anasthesia_record_result)['anasthesia_record_id'];
        }else{
            $anasthesia_employee_id=$_SESSION['userinfo']['Employee_ID'];
            $sql_insert_anasthesia_record = mysqli_query($conn, "INSERT INTo tbl_anasthesia_record_chart(Registration_ID, anasthesia_created_at, anasthesia_employee_id,Payment_Cache_ID) VALUES('$Registration_ID', NOW(), '$anasthesia_employee_id','$Payment_Cache_ID')") or die(mysqli_error($conn));
            $anasthesia_record_id=mysqli_insert_id($conn);            
        }

        $Employee_ID=$_SESSION['userinfo']['Employee_ID'];
        $assist_anasthetist_record = mysqli_query($conn, "SELECT Assist_anesthetist_ID FROM tbl_anasthesia_assist_anasthetist WHERE Registration_ID = '$Registration_ID' AND DATE(created_at)=CURDATE() AND Assist_anesthetist_ID ='$Assist_anesthetist_ID' ");
        if((mysqli_num_rows($assist_anasthetist_record))>0){
            $Assist_anesthetist_ID = mysqli_fetch_assoc($assist_anasthetist_record);
        }else{
        $sql_insert_selected_anesthetist_result=mysqli_query($conn,"INSERT INTO tbl_anasthesia_assist_anasthetist(anasthesia_record_id,Assist_anesthetist_ID, Employee_ID, Registration_Id) VALUES('$anasthesia_record_id', '$Assist_anesthetist_ID','$Employee_ID', '$Registration_ID' )") or die(mysqli_error($conn));

        }

