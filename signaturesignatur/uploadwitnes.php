<?php
$host = "localhost";
$username = "root";
$database = "ehms";
$password = "root";
$conn = mysqli_connect($host, $username, $password, $database);

    $folderPath = "uploadwitnessignature/";

    $image_parts = explode(";base64,", $_POST['signed']);
    $ID=trim($_POST['registrationID']);
    $image_type_aux = explode("image/", $image_parts[0]);
    $image_type = $image_type_aux[1];
    $image_base64 = base64_decode($image_parts[1]);
   $file = $folderPath . uniqid() . '.'.$image_type;
   $image =  uniqid() . '.'.$image_type;
    $sendImage=file_put_contents($file, $image_base64);



    if($sendImage)
    {
      $query = "
      UPDATE consent_form_table 
          SET WITNESS_SIGNATURE = '$file'
              WHERE
              PATIENT_ID = '$ID' 		
         ";

         // die($query);
      // $query = "UPDATE consent_form_table SET WITNESS_SIGNATURE= ".$image." WHERE PATIENT_ID=".$ID." ";
     $result = mysqli_query($conn,$query); 
     if($result)
     {
        echo "Signature Uploaded Successfully.";
        header('location:index.php');

      echo $ID;  echo"<br/>";
      echo $image; echo"<br/>";
      echo $file;

     }
    }
     
    
    
?>
