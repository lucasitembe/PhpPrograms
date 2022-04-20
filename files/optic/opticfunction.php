<?php
#developed by malopa
# get employee name as consultant name


function getEmpleyeeName($employeeId){
    $query = "SELECT Employee_Name 
            FROM tbl_employee 
            WHERE Employee_ID = '$employeeId'";
            $host = "localhost";
            $username = "root";
            $database = "ehms_database";
            $password = "2#dbrooti#";
            $conn= mysqli_connect($host,$username,$password,$database);
    $result = mysqli_query($conn,$query) or die(mysqli_error($conn));
    if($result){
        while($row= mysqli_fetch_assoc($result)){
            $employeeName = $row['Employee_Name'];
        }
        return $employeeName;    
    }
}


#get user receipt number;
function getPatientReceiptNumber($patientId){
    $today = date("Y-m-d");
    $sql = "SELECT Patient_Payment_ID 
            FROM tbl_patient_payments 
            WHERE Registration_ID='$patientId' AND DATE(Payment_Date_And_Time) = '$today'";
            $host = "localhost";
            $username = "root";
            $database = "ehms_database";
            $password = "2#dbrooti#";
            $conn= mysqli_connect($host,$username,$password,$database);
    $result = mysqli_query($conn,$sql);
    if($result){
        while($row = mysqli_fetch_assoc($result)){
            $receiptNumber = $row['Patient_Payment_ID'];
        }
    }else{
        $receiptNumber="";
    }
}

#get patientName
function getPatientName($patientId){
    $patientName = "";
    $sql = "SELECT Patient_Name 
            FROM tbl_patient_registration 
            WHERE Registration_ID = '$patientId'";
            $host = "localhost";
            $username = "root";
            $database = "ehms_database";
            $password = "2#dbrooti#";
            $conn= mysqli_connect($host,$username,$password,$database);
    $result = mysqli_query($conn,$sql) or die(mysqli_error($conn));

    if($result){
        while($row= mysqli_fetch_assoc($result)){
            $patientName = $row['Patient_Name'];
        }
        return $patientName;
    }else{
        return $patientName;
    }
}

#get patientAddress
function getPatientAddress($patientId){
    $patientAddress = "";
    $sql = "SELECT Region,District 
            FROM tbl_patient_registration 
            WHERE Registration_ID = '$patientId'";
            $host = "localhost";
            $username = "root";
            $database = "ehms_database";
            $password = "2#dbrooti#";
            $conn= mysqli_connect($host,$username,$password,$database);
    $result = mysqli_query($conn,$sql) or die(mysqli_error($conn));

    if($result){
        while($row= mysqli_fetch_assoc($result)){
            $patientAddress = $row['Region'].", ". $row['District'];
        }
        return $patientAddress;
    }else{
        return $patientAddress;
    }
}


#get reftaction Distance right value
function getPatientRightDistanceReading($patientId,$date){
    $response =array();

    $sql = "SELECT * FROM tbl_distance_reading_right 
            WHERE patient_id = '$patientId' 
            AND datetime='$date'";
            $host = "localhost";
            $username = "root";
            $database = "ehms_database";
            $password = "2#dbrooti#";
            $conn= mysqli_connect($host,$username,$password,$database);
    $result = mysqli_query($conn,$sql) or die(mysqli_error($conn));
    if($result){
        while($row = mysqli_fetch_assoc($result)){
              $sph = $row['sph'];
              $cyl = $row['cyl'];
              $prism_base = $row['prism_base'];
              array_push($response,array("sph"=>$sph,"cyl"=>$cyl,"prism_base"=>$prism_base)); 
        }
        return $response;
    }
}

#get patient refraction distance left value
function getPatientLeftDistanceReading($patientId,$date){
    $response = array();
    $sql = "SELECT * FROM tbl_distance_reading_left 
            WHERE patient_id = '$patientId' 
            AND datetime='$date'";
            $host = "localhost";
            $username = "root";
            $database = "ehms_database";
            $password = "2#dbrooti#";
            $conn= mysqli_connect($host,$username,$password,$database);
    $result = mysqli_query($conn,$sql) or die(mysqli_error($conn));
    if($result){
        while($row = mysqli_fetch_assoc($result)){
              $sph = $row['sph'];
              $cyl = $row['cyl'];
              $prism_base = $row['prism_base'];
              array_push($response,array("sph"=>$sph,"cyl"=>$cyl,"prism_base"=>$prism_base)); 
        }
        return $response;
    }
}


#get patient spectacle distance left value
function getPatientLeftSepctacleDistance($patientId,$date){
    $response = array();
    $sql = "SELECT * FROM tbl_spectacle_distance_left 
            WHERE patient_id = '$patientId' 
            AND datetime='$date'";
            $host = "localhost";
            $username = "root";
            $database = "ehms_database";
            $password = "2#dbrooti#";
            $conn= mysqli_connect($host,$username,$password,$database);
    $result = mysqli_query($conn,$sql) or die(mysqli_error($conn));
    if($result){
        while($row = mysqli_fetch_assoc($result)){
              $sph = $row['sph'];
              $cyl = $row['cyl'];
              $prism_base = $row['prism_base'];
              array_push($response,array("sph"=>$sph,"cyl"=>$cyl,"prism_base"=>$prism_base)); 
        }
        return $response;
    }
}

#get patient spectacle distance right value
function getPatientRightSepctacleDistance($patientId,$date){
    $response = array();
    $sql = "SELECT * FROM tbl_spectacle_distance_right 
            WHERE patient_id = '$patientId' 
            AND datetime='$date'";
            $host = "localhost";
            $username = "root";
            $database = "ehms_database";
            $password = "2#dbrooti#";
            $conn= mysqli_connect($host,$username,$password,$database);
    $result = mysqli_query($conn,$sql) or die(mysqli_error($conn));

    if($result){
        while($row = mysqli_fetch_assoc($result)){
              $sph = $row['sph'];
              $cyl = $row['cyl'];
              $prism_base = $row['prism_base'];
              array_push($response,array("sph"=>$sph,"cyl"=>$cyl,"prism_base"=>$prism_base)); 
        }
        return $response;
    }
}

#get patient iv distance right value
function getIpRightValue($patientId,$date){
    $response = array();
    $sql = "SELECT * FROM tbl_ip_distance_right 
            WHERE patient_id = '$patientId' 
            AND datetime='$date'";
            $host = "localhost";
            $username = "root";
            $database = "ehms_database";
            $password = "2#dbrooti#";
            $conn= mysqli_connect($host,$username,$password,$database);
    $result = mysqli_query($conn,$sql) or die(mysqli_error($conn));

    if($result){
        while($row = mysqli_fetch_assoc($result)){
              $sph = $row['sph'];
              $cyl = $row['cyl'];
              $prism_base = $row['prism_base'];
              array_push($response,array("sph"=>$sph,"cyl"=>$cyl,"prism_base"=>$prism_base)); 
        }
        return $response;
    }
}

#get iv distance left value
function getIpDistanceLeftValue($patientId,$date){
    $response = array();
    $sql = "SELECT * FROM tbl_ip_distance_left 
            WHERE patient_id = '$patientId' 
            AND datetime='$date'";
            $host = "localhost";
            $username = "root";
            $database = "ehms_database";
            $password = "2#dbrooti#";
            $conn= mysqli_connect($host,$username,$password,$database);
    $result = mysqli_query($conn,$sql) or die(mysqli_error($conn));

    if($result){
        while($row = mysqli_fetch_assoc($result)){
              $sph = $row['sph'];
              $cyl = $row['cyl'];
              $prism_base = $row['prism_base'];
              array_push($response,array("sph"=>$sph,"cyl"=>$cyl,"prism_base"=>$prism_base)); 
        }
        return $response;
    }
}

#get patient  va right eye value
function getVaRightEye($patientId,$date){
    $response = array();  
    $sql = "SELECT * FROM tbl_right_va 
            WHERE patient_id = '$patientId' 
            AND datetime='$date'";
            $host = "localhost";
            $username = "root";
            $database = "ehms_database";
            $password = "2#dbrooti#";
            $conn= mysqli_connect($host,$username,$password,$database);
    $result = mysqli_query($conn,$sql) or die(mysqli_error($conn));

    if($result){
        while($row = mysqli_fetch_assoc($result)){
              $sph = $row['vau'];
              $cyl = $row['ph'];
              $prism_base = $row['glasses'];
              array_push($response,array("vau"=>$sph,"ph"=>$cyl,"glasses"=>$prism_base)); 
        }
        return $response;
    }
}

#get patient Left va left eye value
function getVaLeftEyeValue($patientId,$date){
    $response = array();
    $sql = "SELECT * FROM tbl_left_va 
            WHERE patient_id = '$patientId' 
            AND datetime='$date'";
            $host = "localhost";
            $username = "root";
            $database = "ehms_database";
            $password = "2#dbrooti#";
            $conn= mysqli_connect($host,$username,$password,$database);
    $result = mysqli_query($conn,$sql) or die(mysqli_error($conn));

    if($result){
        while($row = mysqli_fetch_assoc($result)){
              $sph = $row['vau'];
              $cyl = $row['ph'];
              $prism_base = $row['glasses'];
              array_push($response,array("vau"=>$sph,"ph"=>$cyl,"glasses"=>$prism_base)); 
        }
        return $response;
    }
}

#check if spectacle should be offered
function checkIfSpectacleShouldBeOffered($patientId,$date){
    $status = "";
    $sql = "SELECT status 
            FROM tbl_spectacle_status 
            WHERE patient_id='$patientId'
            AND created_at='$date'";
            $host = "localhost";
            $username = "root";
            $database = "ehms_database";
            $password = "2#dbrooti#";
            $conn= mysqli_connect($host,$username,$password,$database);
    $result = mysqli_query($conn,$sql) or die(mysqli_error($conn));

    if($result){
        while($row = mysqli_fetch_assoc($result)){
            $status = $row['status'];
        }
        return $status;
    }
}

#get reason for not getting spectacle
function getNoSpectacleReason($patientId,$date){
    $reason = "empty";
    $sql = "SELECT no_reason 
            FROM tbl_spectacle_status 
            WHERE patient_id='$patientId'
            AND created_at='$date'";
            $host = "localhost";
            $username = "root";
            $database = "ehms_database";
            $password = "2#dbrooti#";
            $conn= mysqli_connect($host,$username,$password,$database);
    $result = mysqli_query($conn,$sql) or die(mysqli_error($conn));

    if($result){
        while($row = mysqli_fetch_assoc($result)){
            $reason = $row['no_reason'];
        }
        return $reason;
    }
}


#get sponsor name
function sponsorName($patientId){
    $sponsor = array();
    $sql = "SELECT Guarantor_Name,sp.Sponsor_ID 
            FROM tbl_sponsor sp 
            JOIN tbl_patient_registration pt 
            ON sp.Sponsor_ID = pt.Sponsor_ID 
            AND pt.Registration_ID = '$patientId'";
            $host = "localhost";
            $username = "root";
            $database = "ehms_database";
            $password = "2#dbrooti#";
            $conn= mysqli_connect($host,$username,$password,$database);
    $result = mysqli_query($conn,$sql) or die(mysqli_error($conn));
    while($row = mysqli_fetch_assoc($result) ){
        $sponsorName = $row['Guarantor_Name'];
        $sponsorId = $row['Sponsor_ID'];
        array_push($sponsor,$sponsorName,$sponsorId);
    }
    return $sponsor;
}
?>