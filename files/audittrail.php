<?php
@session_start();
include("./includes/connection.php");
//ob_start();
//the function to log entries
function audit($Employee_ID=null,$Description=null,$Location=null,$Acted_On_User_ID=null,$Branch_ID=null,$Login_Date_And_Time=null,$Logout_Date_And_Time=null,$Authentication_Date_And_Time=null,$Authentication=null){
    $IP_Address=get_client_ip();
    $MAC_Address='';//'';
    $PC_Name=$_SERVER['REMOTE_ADDR'];//$_SERVER['REMOTE_ADDR'];
    $Date_And_Time=date('Y-m-d H:i:s');
    
    if(empty($Acted_On_User_ID) || is_null($Acted_On_User_ID)){
        $Acted_On_User_ID='NULL';
    }
    //run the query to insert data into into the audit table
    $query="INSERT INTO tbl_audit SET
            Employee_ID='$Employee_ID',
            Login_Date_And_Time='$Login_Date_And_Time',
            Logout_Date_And_Time = '$Logout_Date_And_Time',
            Description='$Description',
            Date_And_Time='$Date_And_Time',
            Location='$Location',
            IP_Address='$IP_Address',
            MAC_Address='$MAC_Address',
            PC_Name='$PC_Name',
            Acted_On_User_ID=$Acted_On_User_ID,
            Branch_ID = '$Branch_ID' ";
            
            
        //execute the query
    $result=mysqli_query($conn,$query) or die(mysqli_error($conn));   
    
}

//function to record authentication
function authentication($Employee_ID=null,$Authentication_Date_And_Time=null,$Authentication=null){
    $IP_Address=get_client_ip();
    $MAC_Address='';
    $PC_Name=$_SERVER['REMOTE_ADDR'];
    $Date_And_Time=date('Y-m-d H:i:s');
    $Employee_ID=$_SESSION['userinfo']['Employee_ID'];//logged in user

    //run the query to select details
    $select_audit_trail = @mysqli_query($conn,"SELECT MAX(ID) AS ID FROM tbl_audit WHERE Employee_ID = '$Employee_ID' ");
    $ID = mysqli_fetch_array($select_audit_trail)['ID'];


    //run another query to select details
//    $Authentication = $Authentication;
//    $Authentication_Date_And_Time = $Authentication_Date_And_Time;
    $Authentication_Date_And_Time1=date('Y-m-d H:i:s');
    $Authentication1=$Authentication;
    $select_details = mysqli_query($conn,"SELECT * FROM tbl_audit WHERE ID = '$ID'");
    while($row = mysqli_fetch_array($select_details)){
            $ID = $row['ID'];
            $Authentication= $row['Authentication'].' ';
            $Authentication_Date_And_Time= $row['Authentication_Date_And_Time'].' ';
        if($Authentication_Date_And_Time){
            $Authentication_Date_And_Time.=$Authentication_Date_And_Time1.';';
        }
        if($Authentication){
            $Authentication.=$Authentication1.';';
        }
    }

//run the query to update the details for login
    $query=mysqli_query($conn,"UPDATE tbl_audit SET
                                Authentication = '$Authentication',
                                Authentication_Date_And_Time = '$Authentication_Date_And_Time'
                                WHERE ID='$ID' ");
}

//function to record authentication
function activity_log($Employee_ID=null,$Activity_Date_And_Time=null,$Activity=null){
    $IP_Address=get_client_ip();
    $MAC_Address='';
    $PC_Name=$_SERVER['REMOTE_ADDR'];
    $Date_And_Time=date('Y-m-d H:i:s');
    $Employee_ID=$_SESSION['userinfo']['Employee_ID'];//logged in user

    //run the query to select details
    $select_audit_trail = @mysqli_query($conn,"SELECT MAX(ID) AS ID FROM tbl_audit WHERE Employee_ID = '$Employee_ID' ");
    $ID = mysqli_fetch_array($select_audit_trail)['ID'];


    //run another query to select details
    $Activity_Date_And_Time1=date('Y-m-d H:i:s');
    $Activity1=$Activity;
    $select_details = mysqli_query($conn,"SELECT * FROM tbl_audit WHERE ID = '$ID'");
    while($row = mysqli_fetch_array($select_details)){
        $ID = $row['ID'];
        $Activity= $row['Activity'].' ';
        $Activity_Date_And_Time= $row['Activity_Date_And_Time'].' ';
        if($Activity_Date_And_Time){
            $Activity_Date_And_Time.=$Activity_Date_And_Time1.';';
        }
        if($Activity){
            $Activity.=$Activity1.';';
        }
    }

//run the query to update the details for login
    $query=mysqli_query($conn,"UPDATE tbl_audit SET
                                Activity = '$Activity',
                                Activity_Date_And_Time = '$Activity_Date_And_Time'
                                WHERE ID='$ID' ");
}

// Function to get the client IP address
function get_client_ip() {
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if(getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if(getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if(getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if(getenv('HTTP_FORWARDED'))
       $ipaddress = getenv('HTTP_FORWARDED');
    else if(getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}
//get_client_ip();

function get_mac_address(){
    // Turn on output buffering
    ob_start();
    //Get the ipconfig details using system commond
    system('ipconfig /all');
    // Capture the output into a variable
    $mycom=ob_get_contents();
    // Clean (erase) the output buffer
    ob_clean();
    $findme = "Physical";
    //Search the "Physical" | Find the position of Physical text
    $pmac = strpos($mycom, $findme);
    // Get Physical Address
    $mac=substr($mycom,($pmac+36),17);
    //Display Mac Address
	//ob_clean();
    return $mac; 
}

get_mac_address();
function get_host_name(){
    $hostname =$_SERVER['REMOTE_ADDR']; //gethostbyaddr($_SERVER['REMOTE_ADDR']);
    return $hostname;
}
function GetMAC(){

    $MAC = exec('getmac'); 
  
    // Storing 'getmac' value in $MAC 
    $MAC_address = strtok($MAC, ' ');
    return $MAC_Address; 
}
//ob_end_clean();





?>
