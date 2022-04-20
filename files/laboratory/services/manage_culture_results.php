<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
 require_once('../../includes/connection.php');

 if (isset($_SESSION['userinfo'])) {
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
}
else{
    @session_destroy();
    header("Location: ../../../index.php?InvalidPrivilege=yes");
}
 $action=$_GET['action'];
 switch ($action) {
     case 'save':
        $paymentID=$_POST['paymentID'];
        $test=$_POST['test'];
        $specimenType=$_POST['specimenType'];
        $anatomical=$_POST['anatomical'];
        $colour=$_POST['colour'];
        $blood_check=$_POST['blood_check'];
        $blood_specification='';
        if (isset($_POST['blood_specification'])) {
           $blood_specification=$_POST['blood_specification'];
        }
        $wbc=$_POST['wbc'];
        $rbc=$_POST['rbc'];
        $deffCount=$_POST['deffCount'];
        $totalProtein=$_POST['totalProtein'];
        $mononuclear=$_POST['mononuclear'];
        $totalCells=$_POST['totalCells'];
        $polymonuclear=$_POST['polymonuclear'];
        $glucose=$_POST['glucose'];
        $pandyTest=$_POST['pandyTest'];
        $leucocytes=$_POST['leucocytes'];
        $indianInk=$_POST['indianInk'];
        $macroGramStain=$_POST['macroGramStain'];
        $znStain=$_POST['znStain'];
        $immyTest=$_POST['immyTest'];
        $wet_prep=$_POST['wet_prep'];
        $wet_specification='';
        if (isset($_POST['wet_specification'])) {
           $wet_specification=$_POST['wet_specification'];
        }
        $microGramStain=$_POST['microGramStain'];
        $microZnStain=$_POST['microZnStain'];
        $giemsa=$_POST['giemsa'];
        $otherMicroStain=$_POST['otherMicroStain'];
        $kohPrep=$_POST['kohPrep'];
        $cultureResults=$_POST['cultureResults'];
        $sensitives=$_POST['sensitive'];
        $intermidiates=$_POST['intermidiate'];
        $resistants=$_POST['resistant'];
        foreach ($sensitives as $sensitive) {
            $sensitiveData[]=$sensitive;
        }
        $reaction_check['sensitive']=$sensitiveData;
       
       
        foreach ($intermidiates as $intermidiate) {
            $intermidiateData[]=$intermidiate;
        }
        $reaction_check['intermidiate']=$intermidiateData;
       
       
        foreach ($resistants as $resistant) {
            $resistantData[]=$resistant;
        }
        $reaction_check['resistant']=$resistantData;
       
        $reaction_check=json_encode($reaction_check);
       
       $query="INSERT INTO tbl_culture_results(payment_item_ID,test,sample,anatomical,colour,wet_preparation,wet_specification,blood_check,blood_specification,wbc,rbc,deffCount,totalProtein,mononuclear,totalCells,polymonuclear,glucose,pandyTest,leucocytes,indianInk,macroGramStain,znStain,immyTest,microGramStain,microZnStain,giemsa,otherMicroStain,kohPrep,cultureResults,reaction_check,saved_by) VALUES($paymentID,$test,$specimenType,$anatomical,'$colour','$wet_prep','$wet_specification','$blood_check','$blood_specification','$wbc','$rbc','$deffCount','$totalProtein','$mononuclear','$totalCells','$polymonuclear','$glucose','$pandyTest','$leucocytes','$indianInk','$macroGramStain','$znStain','$immyTest','$microGramStain','$microZnStain','$giemsa','$otherMicroStain','$kohPrep','$cultureResults','$reaction_check','$Employee_ID')";
       
       $insert=mysqli_query($conn,$query);
       if ($insert) {
        $results['status']="success";
        $results['data']="Saved Successfull!!!";
        echo json_encode($results);
       } else {
           $results['status']="failure";
           $results['data']=mysqli_error($conn);
          echo json_encode($results);
       }
         break;
     
     case 'checkTestAvailability':
        $test=$_GET['test'];
        $paymentID=$_GET['paymentID'];
        $query="SELECT cr.Culture_ID,cr.sample,cr.anatomical,cr.colour,cr.wet_preparation,cr.wet_specification,cr.blood_check,cr.blood_specification,cr.wbc,cr.rbc,cr.deffCount,cr.totalProtein,cr.mononuclear,cr.totalCells,cr.polymonuclear,cr.glucose,cr.pandyTest,cr.leucocytes,cr.indianInk,cr.macroGramStain,cr.znStain,cr.immyTest,cr.microZnStain,cr.microGramStain,cr.kohPrep,cr.giemsa,cr.otherMicroStain,cr.cultureResults,cr.reaction_check,ls.Specimen_Name,a.name AS anatomical_name,e.Employee_Name FROM tbl_culture_results cr JOIN tbl_laboratory_specimen ls ON cr.sample=ls.Specimen_ID JOIN tbl_anatomical_site a ON a.id=cr.anatomical JOIN tbl_employee e ON e.Employee_ID=cr.saved_by WHERE payment_item_ID='$paymentID' AND test='$test' ORDER BY cr.Culture_ID DESC";
        $select=mysqli_query($conn,$query);
        if (mysqli_num_rows($select)>0) {
            $data=mysqli_fetch_assoc($select);
            $result['status']="results found";
            $result['data']=$data;
            echo json_encode($result);
        } else {
            $result['status']="Not found";
            echo json_encode($result);
        }
         break;
         case 'validate':
            $Culture_ID=$_GET['Culture_ID'];
             $query="UPDATE tbl_culture_results SET validated_by=$Employee_ID WHERE Culture_ID=$Culture_ID";

             $update=mysqli_query($conn,$query);
             if ($update) {
              $results['status']="success";
              $results['data']="Validate Successfull!!!";
                echo json_encode($results);
             } else {
                 $results['status']="failure";
                 $results['data']=mysqli_error($conn);
                echo json_encode($results);
             }
             break;
             case 'send':
                $Culture_ID=$_GET['Culture_ID'];
                 $query="UPDATE tbl_culture_results SET sent_by=$Employee_ID WHERE Culture_ID=$Culture_ID";
    
                 $update=mysqli_query($conn,$query);
                 if ($update) {
                  $results['status']="success";
                  $results['data']="Send Successfull!!!";
                    echo json_encode($results);
                 } else {
                     $results['status']="failure";
                     $results['data']=mysqli_error($conn);
                    echo json_encode($results);
                 }
                 break;
         case 'update':
            $paymentID=$_POST['paymentID'];
            $Culture_ID=$_POST['Culture_ID'];
            $test=$_POST['test'];
            $specimenType=$_POST['specimenType'];
            $anatomical=$_POST['anatomical'];
            $colour=$_POST['colour'];
            $blood_check=$_POST['blood_check'];
            $blood_specification='';
            if (isset($_POST['blood_specification'])) {
               $blood_specification=$_POST['blood_specification'];
            }
            $wbc=$_POST['wbc'];
            $rbc=$_POST['rbc'];
            $deffCount=$_POST['deffCount'];
            $totalProtein=$_POST['totalProtein'];
            $mononuclear=$_POST['mononuclear'];
            $totalCells=$_POST['totalCells'];
            $polymonuclear=$_POST['polymonuclear'];
            $glucose=$_POST['glucose'];
            $pandyTest=$_POST['pandyTest'];
            $leucocytes=$_POST['leucocytes'];
            $indianInk=$_POST['indianInk'];
            $macroGramStain=$_POST['macroGramStain'];
            $znStain=$_POST['znStain'];
            $immyTest=$_POST['immyTest'];
            $wet_prep=$_POST['wet_prep'];
            $wet_specification='';
            if (isset($_POST['wet_specification'])) {
               $wet_specification=$_POST['wet_specification'];
            }
            $microGramStain=$_POST['microGramStain'];
            $microZnStain=$_POST['microZnStain'];
            $giemsa=$_POST['giemsa'];
            $otherMicroStain=$_POST['otherMicroStain'];
            $kohPrep=$_POST['kohPrep'];
            $cultureResults=$_POST['cultureResults'];
            $sensitives=$_POST['sensitive'];
            $intermidiates=$_POST['intermidiate'];
            $resistants=$_POST['resistant'];
            foreach ($sensitives as $sensitive) {
                $sensitiveData[]=$sensitive;
            }
            $reaction_check['sensitive']=$sensitiveData;
           
           
            foreach ($intermidiates as $intermidiate) {
                $intermidiateData[]=$intermidiate;
            }
            $reaction_check['intermidiate']=$intermidiateData;
           
           
            foreach ($resistants as $resistant) {
                $resistantData[]=$resistant;
            }
            $reaction_check['resistant']=$resistantData;
           
            $reaction_check=json_encode($reaction_check);
           
           $query="UPDATE tbl_culture_results SET sample=$specimenType,anatomical=$anatomical,colour='$colour',wet_preparation='$wet_prep',wet_specification='$wet_specification',blood_check='$blood_check',blood_specification='$blood_specification',wbc='$wbc',rbc='$rbc',deffCount='$deffCount',totalProtein='$totalProtein',mononuclear='$mononuclear',totalCells='$totalCells',polymonuclear='$polymonuclear',glucose='$glucose',pandyTest='$pandyTest',leucocytes='$leucocytes',indianInk='$indianInk',macroGramStain='$macroGramStain',znStain='$znStain',immyTest='$immyTest',microGramStain='$microGramStain',microZnStain='$microZnStain',giemsa='$giemsa',otherMicroStain='$otherMicroStain',kohPrep='$kohPrep',cultureResults='$cultureResults',reaction_check='$reaction_check',saved_by='$Employee_ID' WHERE Culture_ID=$Culture_ID";
           
           
           $update=mysqli_query($conn,$query);
           if ($update) {
            $results['status']="success";
            $results['data']="Update Successfull!!!";
              echo json_encode($results);
           } else {
               $results['status']="failure";
               $results['data']=mysqli_error($conn);
              echo json_encode($results);
           }
             break;
 }