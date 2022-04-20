<?php

require_once('includes/connection.php');

if (isset($_POST['paymentID'])) {
    $paymentID = $_POST['paymentID'];
} else {
    $paymentID = '';
}

if (isset($_POST['sensitive'])) {
    $sensitive = $_POST['sensitive'];
} else {
    $sensitive = '';
}
if (isset($_POST['antibiotic'])) {
    $antibiotic = $_POST['antibiotic'];
} else {
    $antibiotic= '';
}

if (isset($_POST['biometrict_test'])) {
    $biometrict_test = $_POST['biometrict_test'];
} else {
    $biometrict_test = '';
}
if (isset($_POST['new_organism_1'])) {
    $new_organism_1 = $_POST['new_organism_1'];
} else {
    $new_organism_1 = '';
}
if (isset($_POST['wet'])) {
    $wet = $_POST['wet'];
} else {
    $wet = '';
}
if (isset($_POST['sign'])) {
    $sign = $_POST['sign'];
} else {
    $sign = '';
}
if (isset($_POST['desease'])) {
    $desease = $_POST['desease'];
} else {
    $desease = '';
}
if (isset($_POST['stein'])) {
    $stein = $_POST['stein'];
} else {
    $stein = '';
}
if (isset($_POST['specimen'])) {
    $specimen = $_POST['specimen'];
} else {
    $specimen = '';
}
if (isset($_POST['Remarks'])) {
    $Remarks = $_POST['Remarks'];
} else {
    $Remarks = '';
}
                   $Culture_ID="";
//                   UPDATE MyGuests SET lastname='Doe' WHERE id=2
//                   mysqli_query($conn,"DELETE FROM tbl_culture_results cr,tbl_biotest bot,tbl_antibiotic abtc WHERE cr.Culture_ID=bot.Culture_ID AND cr.Culture_ID=abtc.Culture_ID AND payment_item_ID='$paymentID'") or die(mysqli_error($conn));
                   $mysql_resul = mysqli_query($conn,"UPDATE tbl_culture_results SET Specimen='$specimen', Organism1='$new_organism_1', Remarks='$Remarks', wet_prepation='$wet', gram_stein='$stein', sign='$sign' WHERE payment_item_ID='$paymentID'");
                   $Culture_ID = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Culture_ID FROM tbl_culture_results WHERE payment_item_ID ='$paymentID'"))['Culture_ID'];
                    $delete1  = mysqli_query($conn,"DELETE FROM tbl_biotest WHERE culture_id='$Culture_ID'");
                   if($mysql_resul){
                        foreach ($biometrict_test as $biometrict_name){
                        $biometrict_name;
                        $sql_attache_result=mysqli_query($conn,"INSERT INTO tbl_biotest (biotest,culture_id)VALUES('$biometrict_name','$Culture_ID')") or dei(mysqli_error($conn));   
                   } 
                   $i=0;
                    $delete  = mysqli_query($conn,"DELETE FROM tbl_antibiotic WHERE culture_id='$Culture_ID'");
                   foreach ($antibiotic as $antibiotic_name){
                         $antibiotic = $antibiotic_name;
                         $sensitive_name  = $sensitive[$i];
               
                    $inser_data = mysqli_query($conn,"INSERT INTO tbl_antibiotic(antibiotic,antibiotic_result,culture_id)VALUES('$antibiotic','$sensitive_name','$Culture_ID')");
//                 
               
                    echo $antibiotic."name new";
                    echo $sensitive_name ."\n.";
                        $i++;
//         $sql_attache=mysqli_query($conn,"UPDATE tbl_antibiotic SET antibiotic='$antibiotic',antibiotic_result='$sensitive_name' WHERE culture_id='$Culture_ID'") or dei(mysqli_error($conn));   
                   }
                      
                   }
                   
                  


