<?php 
    include("./includes/connection.php");

    class Audit_Trail{
        private static $conn;
        private static $user_Id;
        private static $action;

        function __construct($user_Id,$action){
            self::$user_Id = $user_Id;
            self::$action = $action;
        }

        function perfomAuditLogin(){
            global $conn;

            $ip_address = $_SERVER['REMOTE_ADDR'];
            $user_info = $_SERVER['HTTP_USER_AGENT'];
            self::$conn = $conn;

            date_default_timezone_set('Africa/Nairobi');
            $current_date = date('Y/m/d');
            $action_date_time = date('Y/m/d h:i:s');

            $insert_audtit = mysqli_query(self::$conn,"INSERT INTO tbl_audit(Employee_Id,Login_Date_And_Time,Ip_Address,Browser_Os) VALUES ('".self::$user_Id."',NOW(),'$ip_address','$user_info')");
            if(!$insert_audtit){
                die(mysqli_error(self::$conn));
            }
        }

        function perfomAuditLogout(){
            global $conn;
            self::$conn = $conn;
            $get_last_login_id = mysqli_fetch_array(mysqli_query(self::$conn,"SELECT Id FROM tbl_audit WHERE Employee_Id = '".self::$user_Id."' ORDER BY Id DESC LIMIT 1"))['Id'];

            date_default_timezone_set('Africa/Nairobi');
            $current_date = date('Y/md');
            $action_date_time = date('Y/m/d h:i:s');


            $update_login_logout = mysqli_query(self::$conn,"UPDATE tbl_audit SET logout_Time = NOW() WHERE Id = '$get_last_login_id'");
            // if(!$update_login_logout){
            //     die(mysqli_errno(self::$conn));
            // }
        }

        function perfomAuditActivities(){
            global $conn;
            self::$conn = $conn;

            date_default_timezone_set('Africa/Nairobi');
            $current_date = date('Y/m/d');
            $action_date_time = date('Y/m/d h:i:s');
            $get_last_login_id = mysqli_fetch_array(mysqli_query(self::$conn,"SELECT Id FROM tbl_audit WHERE Employee_Id = '".self::$user_Id."' ORDER BY Id DESC LIMIT 1"))['Id'];

            $insert_activity = mysqli_query(self::$conn,"INSERT INTO audit_logs (Activities_Log_Id,Action,Date_Time) VALUES ($get_last_login_id,'".self::$action."',NOW())");
            
            // if(!$insert_activity){
            //     die(mysqli_errno(self::$conn));
            // }
        }
    }
?>