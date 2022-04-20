<?php
echo $_FILES['file']['name'];
if($_FILES['file']['name'] != ''){
    $test = explode('.', $_FILES['file']['name']);
    $extension = end($test);    
     $name = rand(100,999).'.'.$extension;

    $location = 'bill_excemption_attachment/'.$name;
    $uploading_feedback=move_uploaded_file($_FILES['file']['tmp_name'], $location);
    if($uploading_feedback){
       echo $name; 
    }else{
       echo "uploading_fail";
    }
}else{
    echo "uploading_fail".$_FILES['file']['name']."";
}

?>