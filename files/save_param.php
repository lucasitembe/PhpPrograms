<?php

require_once('includes/connection.php');

    if (isset($_POST['htmlcodeC']))
    $htmlcode = $_POST['htmlcodeC'];
    if (isset($_POST['location']))
    $location = $_POST['location'];
    if (isset($_POST['Registration_ID']))
    $Registration_ID = $_POST['Registration_ID'];


    if($location == "to_update"){
        $check= "SELECT * FROM tbl_consert_forms_details WHERE   Registration_ID = '$Registration_ID'";
        $sql1=mysqli_query($conn,$check);
        while($row=mysqli_fetch_array($sql1))
        {
            $id=$row['ID'];
        }
        
            $update_comment = "UPDATE tbl_consert_forms_details  SET discusion = '$htmlcode'  WHERE   Registration_ID = '$Registration_ID' AND ID='$id' 		
            ";
            $sql1=mysqli_query($conn,$update_comment);
            if($sql1)
            {
                echo "Saved Succseful!!!!";
            }else{
                echo "not saved";
            }
       
    
//    $sql=" INSERT INTO  tbl_consert_forms_details (ID, Registration_ID,discusion) VALUES(null,'$Registration_ID','$htmlcode') ";  

        
    }

?>