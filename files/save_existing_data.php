
<?php
    include("./includes/connection.php");
    $existing_condition=$_POST['existing_condition'];
    $sql="INSERT INTO pre_existing_condition(pre_existing_Name) VALUES ('$existing_condition')" or die(mysqli_error($conn));
    if(mysqli_query($conn,$sql)){
        echo "inserted";
    }
    else{
        echo "Not inserted";
    }
?>