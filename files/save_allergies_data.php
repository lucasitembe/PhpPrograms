
<?php
    include("./includes/connection.php");
    $allergies_Name=$_POST['allergies_Name'];
    if(mysqli_num_rows(mysqli_query($conn,"SELECT allergies_Name FROM allergies WHERE allergies_Name='$allergies_Name'")) > 0){
        echo "Allergy Already Exist";
    }else{
        $sql="INSERT INTO allergies(allergies_Name) VALUES ('$allergies_Name')" or die(mysqli_error($conn));
    if(mysqli_query($conn,$sql)){
        echo "Allergy Added Successfullly";
    }
    else{
        echo "Fail To Add Allegy";
    }
    }
    
    
?>