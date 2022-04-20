<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
require_once('../includes/connection.php');

if (isset($_SESSION['userinfo'])) {
$Employee_ID = $_SESSION['userinfo']['Employee_ID'];
}
else{
@session_destroy();
header("Location: ../../index.php?InvalidPrivilege=yes");
}
$action=$_GET['action'];

switch ($action) {
    case 'submit':
        $sponsor=$_POST['sponsor'];
        $admission_id=$_POST['admission_id'];
        $highProtein=getValue("highProtein");
        $lightDiet=getValue("lightDiet");
        $saltFree=getValue("saltFree");
        $fatFree=getValue("fatFree");
        $diabeticDiet=getValue("diabeticDiet");
        $doctorSpecial=getValue("doctorSpecial");
        $normalDiet=getValue("normalDiet");
        $milk=getValue("milk");
        $breakFast=getValue("breakFast");
        $lunch=getValue("lunch");
        $dinner=getValue("dinner");

        
        $query="INSERT INTO tbl_diet_specification(admission_id,sponsor,highProtein,lightDiet,saltFree,fatFree,diabeticDiet,specialDiet,normalDiet,milk,breakFast,lunch,dinner,nurse) VALUES($admission_id,'$sponsor',$highProtein,$lightDiet,$saltFree,$fatFree,$diabeticDiet,$doctorSpecial,$normalDiet,$milk,$breakFast,$lunch,$dinner,$Employee_ID)";
        
        $insert=mysqli_query($conn,$query);
        if ($insert) {
            $result['status']="success";
            $result['data']="Succefully Submited";
            echo json_encode($result);
        } else {
            $result['status']="failure";
            $result['data']="Data failed to submit\nPlease Retry";
            echo json_encode($result);
        }
        
        break;
        case 'getDietSpecs':
            $admission_id=$_GET['admission_id'];
            $query="SELECT * FROM tbl_diet_specification WHERE admission_id=$admission_id";
            
            $select=mysqli_query($conn,$query);
            if (mysqli_num_rows($select)>0) {
                $row=mysqli_fetch_assoc($select);
                $result['status']="has submitted";
                $result['data']=$row;
                echo json_encode($result);
            } else {
                $result['status']="not submitted";
                echo json_encode($result);
            }
            break;
            case 'update':
                $sponsor=$_POST['sponsor'];
                $specId=$_POST['specId'];
                $highProtein=getValue("highProtein");
                $lightDiet=getValue("lightDiet");
                $saltFree=getValue("saltFree");
                $fatFree=getValue("fatFree");
                $diabeticDiet=getValue("diabeticDiet");
                $doctorSpecial=getValue("doctorSpecial");
                $normalDiet=getValue("normalDiet");
                $milk=getValue("milk");
                $breakFast=getValue("breakFast");
                $lunch=getValue("lunch");
                $dinner=getValue("dinner");
        
                
                $query="UPDATE tbl_diet_specification SET sponsor='$sponsor',highProtein=$highProtein,lightDiet=$lightDiet,saltFree=$saltFree,fatFree=$fatFree,diabeticDiet=$diabeticDiet,specialDiet=$doctorSpecial,normalDiet=$normalDiet,milk=$milk,breakFast=$breakFast,lunch=$lunch,dinner=$dinner,nurse=$Employee_ID WHERE id=$specId";
                
                $update=mysqli_query($conn,$query);
                if ($update) {
                    $result['status']="success";
                    $result['data']="Succefully Updated";
                    echo json_encode($result);
                } else {
                    $result['status']="failure";
                    $result['data']="Data failed to Update\nPlease Retry";
                    echo json_encode($result);
                }
                
                break;
}

function getValue($index)
{
    if (isset($_POST["$index"])) {
        return 1;
    } else {
        return 0;
    }
    
}