<?php 
    # connection 
    include './includes/connection.php';

    class Audit_Trail{
        public $activity_message;
        public $conn;

        function __construct($activity_message,$conn){
            $this->activity = $activity_message;
            $this->conn = $conn;
        }

        function perform_audit(){
            $audit_details = explode(';',$_SERVER['HTTP_USER_AGENT']);
            $current_user_id = $_SESSION['userinfo']['Employee_ID'];
            $os = $audit_details[1].$audit_details[2];
            $ip_address = $_SERVER['REMOTE_ADDR'];

            #perfom audit trail action
            $insert_audit_log = mysqli_query($this->conn,"INSERT INTO trail(activity,date_time,user,ip,os) 
                                                    VALUES ('$this->activity',NOW(),'$current_user_id','$ip_address','$os')");
            if(!$insert_audit_log){
                echo "Something went wrong, please with trail";
            }
        }
    }
?>
