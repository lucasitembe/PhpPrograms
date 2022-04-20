<?php

// if($_POST['uploadconsent']){
    /* Getting file name */
    $filename = $_FILES['file']['name'];

    /* Location */
    $location = "attachment/".$filename;
    $uploadOk = 1;
    $imageFileType = pathinfo($location,PATHINFO_EXTENSION);

    /* Valid Extensions */
    $valid_extensions = array("jpg","jpeg","png","pdf", 'txt', "xls","csv");
    /* Check file extension */
    if( !in_array(strtolower($imageFileType),$valid_extensions) ) {
    $uploadOk = 0;
    }
    if($uploadOk == 0){
        echo 0;
    }else{
    /* Upload file */
    // echo 1;


    $ext=substr(strrchr($_FILES['file']['name'],'.'),1);
    $picName=date('YmdHis').''.$ext;
    if(move_uploaded_file($_FILES['file']['tmp_name'],"attachment/".$picName)){
        echo $picName;
    }else{
        echo 'Nothing';
    }
    }
// }