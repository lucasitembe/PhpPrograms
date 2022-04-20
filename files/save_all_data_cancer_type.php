<?php

require_once('includes/connection.php');


if (isset($_POST['type'])) {
    $type = $_POST['type'];
} else {
    $type = '';
}
if (isset($_POST['cancer_id'])) {
    $cancer_id = $_POST['cancer_id'];
} else {
    $cancer_id = '';
}
if (isset($_POST['adjuvant'])) {
    $adjuvant= $_POST['adjuvant']; 
} else {
    $adjuvant = '';
}
if (isset($_POST['duration'])) {
    $duration = $_POST['duration'];
} else {
    $duration = '';
}
if (isset($_POST['volume'])) {
    $volume = $_POST['volume'];
} else {
    $volume = '';
}
if (isset($_POST['minutes'])) {
    $minutes = $_POST['minutes'];
} else {
    $minutes = '';
}

if (isset($_POST['cancer_id'])) {
    $cancer_id = $_POST['cancer_id'];
} else {
    $cancer_id = '';
}

if (isset($_POST['item'])) {
    $item = $_POST['item'];
} else {
    $item= '';
}

if (isset($_POST['dose'])) {
    $dose = $_POST['dose'];
} else {
    $dose = 'hhh';
}
if (isset($_POST['route'])) {
    $route = $_POST['route'];
} else {
    $route = '';
}
if (isset($_POST['admin'])) {
    $admin = $_POST['admin'];
} else {
    $admin = '';
}
if (isset($_POST['frequence'])) {
    $frequence = $_POST['frequence'];
} else {
    $frequence = '';
}
if (isset($_POST['drug'])) {
    $drug = $_POST['drug'];
} else {
    $drug = '';
}
if (isset($_POST['ddose'])) {
    $ddose = $_POST['ddose'];
} else {
    $ddose = '';
}
if (isset($_POST['dstreangth'])) {
    $dstreangth = $_POST['dstreangth'];
} else {
    $dstreangth = '';
}
if (isset($_POST['dvolume'])) {
    $dvolume = $_POST['dvolume'];
} else {
    $dvolume= '';
}
if (isset($_POST['droute'])) {
    $droute= $_POST['droute'];
} else {
    $droute= '';
}
if (isset($_POST['dadmin'])) {
    $dadmin = $_POST['dadmin'];
} else {
    $dadmin= '';
}
if (isset($_POST['dfrequence'])) {
    $dfrequence = $_POST['dfrequence'];
} else {
    $dfrequence= '';
}
if (isset($_POST['medication'])) {
    $medication = $_POST['medication'];
} else {
    $medication= '';
}

if (isset($_POST['item_ID'])) {
    $item_ID = $_POST['item_ID'];
} else {
    $item_ID= '';
}
if (isset($_POST['ditem_id'])) {
    $ditem_ID = $_POST['ditem_id'];
} else {
    $ditem_ID= '';
}
if(isset($_POST['adjuvantstrenth'])){
    $adjuvantstrenths = $_POST['adjuvantstrenth'];
}else{
    $adjuvantstrenths='';
}

              //include("../includes/connection.php");
//     function Start_Transaction(){
//         mysqli_query($conn,"START TRANSACTION");
//     }

//     function Commit_Transaction(){
//         mysqli_query($conn,"COMMIT");
//     }

//     function Rollback_Transaction(){
//         mysqli_query($conn,"ROLLBACK");
//     }
    

//      Start_Transaction();
//    $an_error_occured=FALSE;
    $mysql_delete_data_in_tbl_adjuvant_duration=mysqli_query($conn,"DELETE FROM tbl_adjuvant_duration WHERE cancer_type_id='$cancer_id' AND date(date_and_time)=CURDATE()");
    if($mysql_delete_data_in_tbl_adjuvant_duration){
            $i=0;
            foreach ($adjuvant as $adjuvant_name){                       
                $adjuvant = $adjuvant_name;
                $duration_name  = $duration[$i];
                $adjuvantstrenth = $adjuvantstrenths[$i];
                $i++;
                $sql_attache=mysqli_query($conn,"INSERT INTO tbl_adjuvant_duration (cancer_type_id,adjuvant,adjuvantstrenth,duration,date_and_time) VALUES('$cancer_id','$adjuvant','$adjuvantstrenth','$duration_name',NOW())") or die(mysqli_error($conn));   
            }
            if(!$sql_attache){
                    echo "Failed to insert adjuvant";
            }else{
                echo "Adjuvant saved";
            }
    }


    $mysql_delete_data_in_tbl_physician=mysqli_query($conn,"DELETE FROM tbl_physician WHERE cancer_type_id='$cancer_id' AND date(date_and_time)=CURDATE()");
    if($mysql_delete_data_in_tbl_physician){  
        $totalvolume = sizeof($volume);
        for($i=0;$i<$totalvolume;$i++) {
            $volume_name = $volume[$i];
            $type_name  = $type[$i];
            $minutes_name = $minutes[$i];                        
            $sql_attache=mysqli_query($conn,"INSERT INTO tbl_physician (physician_volume,physician_type,physician_minutes,date_and_time,cancer_type_id) VALUES('$volume_name','$type_name','$minutes_name',NOW(),'$cancer_id')") or die(mysqli_error($conn));  
            
        }
        if(!$sql_attache){
            echo "Failed to insert physician";
        }else{
            echo "physician saved";
        }
    }
              
              
           
      $mysql_delete_data_in_tbl_supportive_treatment=mysqli_query($conn,"DELETE FROM tbl_supportive_treatment WHERE cancer_type_id='$cancer_id' AND date(date_and_time)=CURDATE()");
   if($mysql_delete_data_in_tbl_supportive_treatment){      
            $totalitem = sizeof($item);                   
                for($i=0;$i<$totalitem;$i++) {                       
                    $item_name = $item[$i];
                    $dose_name  = $dose[$i];
                    $route_name  = $route[$i];
                    $admin_name  = $admin[$i];
                    $frequence_name  = $frequence[$i];
                    $medication_name  = $medication[$i];
                    $treatment_item_ID =$item_ID[$i];
                    
                        $sql_attache=mysqli_query($conn,"INSERT INTO tbl_supportive_treatment(cancer_type_id,item_ID,supportive_treatment,Dose,Route,Administration_Time,Frequence,Medication_Instructions,date_and_time)VALUES('$cancer_id','$treatment_item_ID','$item_name','$dose_name','$route_name','$admin_name','$frequence_name','$medication_name',NOW())") or die(mysqli_error($conn)); 
                        
                        if($sql_attache){
                            $item_id_update = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Item_ID FROM tbl_items WHERE Product_Name='$item_name'"))['Item_ID'];
                            $sql_update_status=mysqli_query($conn,"UPDATE tbl_items_cancer SET Status='success' WHERE item_ID='$item_id_update' AND cancer_id='$cancer_id'");
                            echo "yes supportive";
                        }else{
                            echo "no supportive";
                        }

                }
       
            if(!$sql_attache){
                echo "Failed to insert supportive treatment";
            }else{
                echo "supportive treatment saved";
            }
   }
                   
              
          
    $mysql_delete_data_in_tbl_chemotherapy_drug=mysqli_query($conn,"DELETE FROM tbl_chemotherapy_drug WHERE cancer_type_id='$cancer_id' AND date(date_and_time)=CURDATE()");
    if($mysql_delete_data_in_tbl_chemotherapy_drug){
       
                 $totaldrug = sizeof($drug);
                   
                 for($i=0;$i<$totaldrug;$i++) {
                       
                    $drug_name = $drug[$i];
                    $ddose_name  = $ddose[$i];
                    $dvolume_name  = $dvolume[$i];
                    $droute_name  = $droute[$i];
                    $dadmin_name  = $dadmin[$i];
                    $dfrequence_name  = $dfrequence[$i];
                    $drug_item_ID =$ditem_ID[$i];

                    $sql_attache2=mysqli_query($conn,"INSERT INTO tbl_chemotherapy_drug(cancer_type_id,Chemotherapy_Drug,Dose,Volume,Route,Admin_Time,Frequency,date_and_time)VALUES('$cancer_id','$drug_name','$ddose_name','$dvolume_name','$droute_name','$dadmin_name','$dfrequence_name',NOW())") or die(mysqli_error($conn)); 
                        
                //        $sql_save_drug=mysqli_query($conn,"INSERT INTO tbl_chemotherapy_drug(cancer_type_id,ditem_ID,Chemotherapy_Drug,Dose,Volume,Route,Admin_Time,Frequency,date_and_time)VALUES('$cancer_id','$drug_item_ID','$drug_name','$ddose_name','$dvolume_name','$droute_name','$dadmin_name','$dfrequence_name',NOW())") or die(mysqli_error($conn));
                       if($sql_attache2){
                        $item_id_update2 = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Item_ID FROM tbl_items WHERE Product_Name='$drug_name'"))['Item_ID'];
                        $sql_update_status2=mysqli_query($conn,"UPDATE tbl_items_cancer_drug SET Status='success' WHERE item_id='$item_id_update2' AND cancer_id='$cancer_id'") or die(mysqli_error($conn));
                            if($sql_update_status2){
                                echo "updated successful";
                            }else{
                                echo "Couldn't updated";
                            }
                       } else{
                        echo "did not insert 1";
                       }
                        // $item_id_update2 = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Item_ID FROM tbl_items WHERE Product_Name='$drug_name'"))['Item_ID'];
                        // $sql_update_status2=mysqli_query($conn,"UPDATE tbl_items_cancer_drug SET Status='success' WHERE item_id='$item_id_update2' AND cancer_id='$cancer_id'") or die(mysqli_error($conn));
                   }
           
                   if(!$sql_attache2){
                        echo "Failed to insert chemodrug";
                    }else{
                        echo "chemodrug saved";
                    }
   }
           

// if(!$an_error_occured){
//     Commit_Transaction();
//     echo "success";
// }else{
//     Rollback_Transaction(); 
//     echo "faild";
// }
