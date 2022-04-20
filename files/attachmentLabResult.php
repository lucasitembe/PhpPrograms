<?php

@session_start();
$Employee_ID = $_SESSION['userinfo']['Employee_ID'];

include("./includes/connection.php");
include './includes/cleaninput.php';
//print_r( $_FILES);
//exit;
if (isset($_POST['attachFIlesLabvaldate']) && $_POST['attachFIlesLabvaldate'] == 1) {
//echo 1;exit;

$items = $_POST['cache_ID'];
    $overalRemarks2 = $_POST['overallRemarks'];
    $otherResult2 = $_POST['otherResults'];
    // $datas = (explode(",",$items));

    // print_r($items);
    // exit();
for($i=0; $i<= sizeof($items); $i++){
    $ppilc = $items[$i];
    $overalRemarks = $overalRemarks2[$i];
    $otherResults = $otherResults2[$i];

            $Registration_ID = $_POST['Registration_id'];
            // $ppilc = $_POST['ppilc'];
            // $overalRemarks = $_POST['overallRemarks_'];

        $testrs = mysqli_query($conn,"SELECT test_result_ID FROM tbl_test_results WHERE payment_item_ID='" . $ppilc . "' ") or die(mysqli_error($conn));
        $ref_test_result_ID = mysqli_fetch_assoc($testrs)['test_result_ID'];


        $update_validated = "UPDATE tbl_tests_parameters_results SET Validated='Yes',TimeValidate=NOW(),ValidatedBy='" . $Employee_ID . "' WHERE ref_test_result_ID='" . $ref_test_result_ID . "' AND  Validated IN ('No','') ";
        mysqli_query($conn,$update_validated) or die(mysqli_error($conn));


        echo 1;
    }
} elseif (isset($_POST['attachFIlesLabsubmit']) && $_POST['attachFIlesLabsubmit'] == 1) {
//echo 2;exit;
    $items = $_POST['cache_ID'];
    $overalRemarks2 = $_POST['overallRemarks'];
    $otherResult2 = $_POST['otherResults'];
    // $datas = (explode(",",$items));

    // print_r($items);
    // exit();
for($i=0; $i<= sizeof($items); $i++){
    $ppilc = $items[$i];
    $overalRemarks = $overalRemarks2[$i];
    $otherResults = $otherResults2[$i];

            $Registration_ID = $_POST['Registration_id'];
            // $ppilc = $_POST['ppilc'];
            $dir = getcwd()."/patient_attachments/";
            // $overalRemarks = $_POST['overallRemarks_'];

        $testrs = mysqli_query($conn,"SELECT test_result_ID FROM tbl_test_results WHERE payment_item_ID='" . $ppilc . "' ") or die(mysqli_error($conn));
        $ref_test_result_ID = mysqli_fetch_assoc($testrs)['test_result_ID'];

// die("UPDATE tbl_tests_parameters_results SET Submitted='Yes',TimeSubmitted=NOW() WHERE ref_test_result_ID='" . $ref_test_result_ID . "' AND  Submitted IN ('No','') ");
        $update_submitted = "UPDATE tbl_tests_parameters_results SET Submitted='Yes',TimeSubmitted=NOW() WHERE ref_test_result_ID='$ref_test_result_ID' AND Validated='Yes' AND  Submitted IN ('No','') ";

        mysqli_query($conn,$update_submitted) or die(mysqli_error($conn));

        echo 1;
    }
} else if (isset($_POST['attachFIlesLabsave']) && $_POST['attachFIlesLabsave'] == 1) {
    $items = (explode(",",$_POST['cache_ID']));
    $overalRemarks2 = (explode(",",$_POST['overallRemarks']));
    $otherResult2 = (explode(",",$_POST['otherResult']));
    // $datas = (explode(",",$items));
// die(sizeof($items));
    // print_r($datas."<br>");
    // exit();
for($i=0; $i<= sizeof($items); $i++){
    $ppilc = $items[$i];
    $overalRemarks = $overalRemarks2[$i];
    $otherResults = $otherResult2[$i];

    if($ppilc != 0){

    // include ("/patient_attachments/try.php");
    // echo $Trial;
    // echo $items[$i]."<br>".$i;
    // exit();
    //$_POST=  sanitize_input($_POST);
    $Registration_ID = $_POST['Registration_id'];
    $file_name = $_FILES['file']['name'];
    $file_size = $_FILES['file']['size'];
    $file_tmp = $_FILES['file']['tmp_name'];
    $file_type = $_FILES['file']['type'];
    // echo $Registration_ID."<br>";
    // $ppilc = $_POST['ppilc'];
    $dir = getcwd()."/patient_attachments/";

    $result = '';

    if (!empty($otherResults) && $otherResults != 'None') {
        $result = $otherResults;
    }

    $name = '';

    $testrs = mysqli_query($conn,"SELECT test_result_ID FROM tbl_test_results WHERE payment_item_ID='" . $ppilc . "' ") or die(mysqli_error($conn));
    $ref_test_result_ID = mysqli_fetch_assoc($testrs)['test_result_ID'];

    $checkIFinserted = mysqli_query($conn,"SELECT * FROM tbl_tests_parameters_results WHERE ref_test_result_ID='" . $ref_test_result_ID . "' ") or die(mysqli_error($conn));

    if (mysqli_num_rows($checkIFinserted) > 0) {
        
    } else {
        $QueryResults = "SELECT test_result_ID,parameter_ID FROM tbl_item_list_cache 
	               INNER JOIN tbl_test_results ON Payment_Item_Cache_List_ID=payment_item_ID 
                        INNER JOIN tbl_tests_parameters ON ref_item_ID=Item_ID 
                        INNER JOIN tbl_payment_cache ON tbl_payment_cache.Payment_Cache_ID=tbl_item_list_cache.Payment_Cache_ID 
                        JOIN tbl_parameters ON parameter_ID=ref_parameter_ID  
                        WHERE Registration_ID='" . $Registration_ID . "' AND payment_item_ID='" . $ppilc . "'";

// die($QueryResults);
        $result_para = mysqli_query($conn,$QueryResults) or die(mysqli_error($conn));

//echo $numRows;exit;
        while ($row = mysqli_fetch_assoc($result_para)) {
            $resultID = $row['test_result_ID'];
            $parameterID = $row['parameter_ID'];

            $Insert = "INSERT INTO tbl_tests_parameters_results (ref_test_result_ID,parameter,result,modified,Validated,Submitted,status) VALUES ('$resultID','$parameterID','$result','','','','')";
            // die($Insert);
            $Query = mysqli_query($conn,$Insert) or die(mysqli_error($conn));
            if ($Query) {
                echo "success";
            } else {
                echo "failure";
            }
            
        }
    }

//End Check if there paramenter results for this Item
    $validated_check = "SELECT Validated FROM tbl_tests_parameters_results WHERE Validated='Yes' AND ref_test_result_ID='" . $ref_test_result_ID . "'";
    $validated = mysqli_query($conn,$validated_check) or die(mysqli_error($conn));

    if (!empty($file_name)) {
        // echo "Yessssss<br>";
        
        // $location = "/patient_attachments/";

        // // echo $target_file;
        // $imageFileType = pathinfo($location,PATHINFO_EXTENSION);
        // $imageFileType_ = strtolower($imageFileType);

        // foreach ($_FILES['file']['name'] as $key => $tmp_name) {
        // for($key=0; $key<= sizeof($_FILES['file']['name']); $key++){

            // echo $file_name."+".$file_size."+".$file_tmp."+".$file_type;
        $target_file = $location.basename($file_name);

                    // $location = "patientImages/";

        // $target_file = $location.basename($_FILES["file"]["name"]);

        // // echo $target_file;
        // $imageFileType = pathinfo(PATHINFO_EXTENSION);
        // $imageFileType_ = strtolower($imageFileType);
        // echo $newFile = $file_type;



            // echo $file_name.".".$file_type." = ".$file_size." > ".$file_tmp;
           // $file_name=mysqli_real_escape_string($conn,$file_name);
            // $file_name = str_replace("'","", $target_file);
            $file_name = str_replace("'","", $file_name);
            
           // $file_tmp =mysqli_real_escape_string($conn,$file_tmp );
            $file_tmp = str_replace("'","", $file_tmp);
             
            if (!empty($file_name)) {
                $name = $Registration_ID . $Employee_ID . date("YmdHs") . $file_name;
                $name=mysqli_real_escape_string($conn,$name);
            }

            // echo date("YmdHs"); exit();
// die("SELECT Attachment_ID from tbl_attachment where Registration_ID='" . $Registration_ID . "' AND item_list_cache_id='$ppilc' AND image_name_flag = '$file_name' AND Check_In_Type='Laboratory'");
            $checkIfuploaded = mysqli_query($conn,"SELECT Attachment_ID from tbl_attachment where Registration_ID='$Registration_ID' AND item_list_cache_id='$ppilc' AND image_name_flag = '$file_name' AND Check_In_Type='Laboratory'");

            $Attachment_ID = mysqli_fetch_assoc($checkIfuploaded)['Attachment_ID'];
            $sql = '';
            $insertmodify = '';

            if (mysqli_num_rows($checkIfuploaded) > 0) {
                // echo "uploaded";
                if (mysqli_num_rows($validated) > 0) {
                    $insertmodify = "INSERT INTO tbl_attachment_modification (Attachment_ID, Employee_ID, Description, Attachment_Url, Attachment_Date, image_name_flag) SELECT Attachment_ID,Employee_ID, Description, Attachment_Url, Attachment_Date,image_name_flag FROM tbl_attachment WHERE Attachment_ID='$Attachment_ID'";
                }
                $sql = "UPDATE tbl_attachment SET Employee_ID='$Employee_ID',Description='$overalRemarks',Attachment_Url='$name',Attachment_Date=NOW() where Registration_ID='" . $Registration_ID . "' AND item_list_cache_id='$ppilc' AND image_name_flag = '$file_name'  AND Check_In_Type='Laboratory'";
            } else {
                // die("INSERT INTO tbl_attachment (Attachment_ID, Registration_ID, Employee_ID, Description, Check_In_Type, Attachment_Url, Attachment_Date, item_list_cache_id, payment_item_list_id,image_name_flag) VALUES('','$Registration_ID','$Employee_ID','$overalRemarks','Laboratory','$name',NOW(),'$ppilc','','$file_name')");
                $sql = "INSERT INTO tbl_attachment (Attachment_ID, Registration_ID, Employee_ID, Description, Check_In_Type, Attachment_Url, Attachment_Date, item_list_cache_id, payment_item_list_id,image_name_flag) VALUES('','$Registration_ID','$Employee_ID','$overalRemarks','Laboratory','$name',NOW(),'$ppilc','','$file_name')";
            }

            if (!empty($insertmodify)) {
                mysqli_query($conn,$insertmodify) or die(mysqli_error($conn));
            }

            if (mysqli_query($conn,$sql)) {
                if (!empty($name)) {
                //    echo "ime upload ya kwanza $file_tmp $dir $name <br/>";
                    //echo"============================================";
                    $tmp_name=mysqli_real_escape_string($conn,$file_tmp);
                    move_uploaded_file($tmp_name, $dir.$name);
                    // if(move_uploaded_file($tmp_name, $dir.$name)) echo "";
                    // else echo "IMEKWAMA";
                }
            } else {
                echo "-----";
                die(mysqli_error($conn));
            }
        // }
    } else {
        // echo "NOOOOOOOOOOOOOO<br>";

        $checkIfuploaded = mysqli_query($conn,"SELECT Attachment_ID from tbl_attachment where Registration_ID='" . $Registration_ID . "' AND item_list_cache_id='$ppilc' AND Check_In_Type='Laboratory'");

        $Attachment_ID = mysqli_fetch_assoc($checkIfuploaded)['Attachment_ID'];
        $sql = '';
        $insertmodify = '';

        if (mysqli_num_rows($checkIfuploaded) > 0) {
            if (mysqli_num_rows($validated) > 0) {
                $insertmodify = "INSERT INTO tbl_attachment_modification (Attachment_ID, Employee_ID, Description, Attachment_Url, Attachment_Date, image_name_flag) SELECT Attachment_ID,Employee_ID, Description, Attachment_Url, Attachment_Date,image_name_flag FROM tbl_attachment WHERE Attachment_ID='$Attachment_ID'";
            }
            $sql = "UPDATE tbl_attachment SET Employee_ID='$Employee_ID',Description='$overalRemarks',	Attachment_Date=NOW() where Registration_ID='" . $Registration_ID . "' AND item_list_cache_id='$ppilc' AND Check_In_Type='Laboratory'";
        } else {
            $sql = "INSERT INTO tbl_attachment (Attachment_ID, Registration_ID, Employee_ID, Description, Check_In_Type, Attachment_Url, Attachment_Date, item_list_cache_id) VALUES('','$Registration_ID','$Employee_ID','$overalRemarks','Laboratory','$name',NOW(),'$ppilc')";
        }

        if (!empty($insertmodify)) {
            mysqli_query($conn,$insertmodify) or die(mysqli_error($conn));
        }

        if (mysqli_query($conn,$sql)) {
            if (!empty($name)) {
            //  echo "ime upload";
                move_uploaded_file($tmp_name, $dir . $name);
            }
        } else {
            die(mysqli_error($conn));
        }
    }

    $update_saved = "UPDATE tbl_tests_parameters_results SET Saved='Yes',SavedBy='" . $Employee_ID . "',result='" . $result . "' WHERE ref_test_result_ID='" . $ref_test_result_ID . "' AND result IN ('None','negative','negative*','positive','normal','abnormal','high','low','') ";

    mysqli_query($conn,$update_saved) or die(mysqli_error($conn));

   echo 1;

    }
}
}
//exit;
if (isset($_POST['attachFIlesLabvaldate']) && $_POST['attachFIlesLabvaldate'] == 2) {
//echo 1;exit;
    $Registration_ID = $_POST['Registration_id'];
    $ppilc = $_POST['ppilc'];
    $dir = "patient_attachments/";
    $overalRemarks = $_POST['overallRemarks_' . $ppilc];

    $testrs = mysqli_query($conn,"SELECT test_result_ID FROM tbl_test_results WHERE payment_item_ID='" . $ppilc . "' ") or die(mysqli_error($conn));
    $ref_test_result_ID = mysqli_fetch_assoc($testrs)['test_result_ID'];


    $update_validated = "UPDATE tbl_tests_parameters_results SET Validated='Yes',TimeValidate=NOW(),ValidatedBy='" . $Employee_ID . "' WHERE ref_test_result_ID='" . $ref_test_result_ID . "' AND  Validated IN ('No','') ";
    mysqli_query($conn,$update_validated) or die(mysqli_error($conn));





    echo 1;
} elseif (isset($_POST['attachFIlesLabsubmit']) && $_POST['attachFIlesLabsubmit'] == 2) {
//echo 2;exit;
    $Registration_ID = $_POST['Registration_id'];
    $ppilc = $_POST['ppilc'];
    $dir = "patient_attachments/";
    $overalRemarks = $_POST['overallRemarks_' . $ppilc];

    $testrs = mysqli_query($conn,"SELECT test_result_ID FROM tbl_test_results WHERE payment_item_ID='" . $ppilc . "' ") or die(mysqli_error($conn));
    $ref_test_result_ID = mysqli_fetch_assoc($testrs)['test_result_ID'];


    $update_submitted = "UPDATE tbl_tests_parameters_results SET Submitted='Yes',TimeSubmitted=NOW() WHERE ref_test_result_ID='" . $ref_test_result_ID . "' AND  Submitted IN ('No','') ";

    mysqli_query($conn,$update_submitted) or die(mysqli_error($conn));




    echo 1;
} else if (isset($_POST['attachFIlesLabsave']) && $_POST['attachFIlesLabsave'] == 2) {
    //$_POST=  sanitize_input($_POST);
    $Registration_ID = $_POST['Registration_id'];
    $ppilc = $_POST['ppilc'];
    $dir = "patient_attachments/";
    $overalRemarks = $_POST['overallRemarks_' . $ppilc];
    $otherResult = $_POST['otherResult_' . $ppilc];

    $result = 'None';

    if (!empty($otherResult)) {
        $result = $otherResult;
    }

    $name = '';

    $testrs = mysqli_query($conn,"SELECT test_result_ID FROM tbl_test_results WHERE payment_item_ID='" . $ppilc . "' ") or die(mysqli_error($conn));
    $ref_test_result_ID = mysqli_fetch_assoc($testrs)['test_result_ID'];

    $checkIFinserted = mysqli_query($conn,"SELECT * FROM tbl_tests_parameters_results WHERE ref_test_result_ID='" . $ref_test_result_ID . "' ") or die(mysqli_error($conn));

    if (mysqli_num_rows($checkIFinserted) > 0) {
        
    } else {
        $QueryResults = "SELECT test_result_ID,parameter_ID FROM tbl_item_list_cache 
	               INNER JOIN tbl_test_results ON Payment_Item_Cache_List_ID=payment_item_ID 
                        INNER JOIN tbl_tests_parameters ON ref_item_ID=Item_ID 
                        INNER JOIN tbl_payment_cache ON tbl_payment_cache.Payment_Cache_ID=tbl_item_list_cache.Payment_Cache_ID 
                        JOIN tbl_parameters ON parameter_ID=ref_parameter_ID  
                        WHERE Registration_ID='" . $Registration_ID . "' AND payment_item_ID='" . $ppilc . "'";

// die($QueryResults);
        $result_para = mysqli_query($conn,$QueryResults) or die(mysqli_error($conn));

//echo $numRows;exit;
        while ($row = mysqli_fetch_assoc($result_para)) {
            $resultID = $row['test_result_ID'];
            $parameterID = $row['parameter_ID'];

            $Insert = "INSERT INTO tbl_tests_parameters_results (ref_test_result_ID,parameter,result,modified,Validated,Submitted,status) VALUES ('$resultID','$parameterID','$result','','','','')";
            $Query = mysqli_query($conn,$Insert) or die(mysqli_error($conn));
        }
    }

//End Check if there paramenter results for this Item
    $validated_check = "SELECT Validated FROM tbl_tests_parameters_results WHERE Validated='Yes' AND ref_test_result_ID='" . $ref_test_result_ID . "'";
    $validated = mysqli_query($conn,$validated_check) or die(mysqli_error($conn));


    if (isset($_FILES['labfile_' . $ppilc]['tmp_name'])) {
        foreach ($_FILES['labfile_' . $ppilc]['tmp_name'] as $key => $tmp_name) {
            $file_name = $_FILES['labfile_' . $ppilc]['name'][$key];
            $file_size = $_FILES['labfile_' . $ppilc]['size'][$key];
            $file_tmp = $_FILES['labfile_' . $ppilc]['tmp_name'][$key];
            $file_type = $_FILES['labfile_' . $ppilc]['type'][$key];
           // $file_name=mysqli_real_escape_string($conn,$file_name);
            $file_name = str_replace("'","", $file_name);
            
           // $file_tmp =mysqli_real_escape_string($conn,$file_tmp );
            $file_tmp = str_replace("'","", $file_tmp);
             
            if (!empty($file_name)) {
                $name = $Registration_ID . $Employee_ID . $ppilc . date("YmdHsm") . $file_name;
                $name=mysqli_real_escape_string($conn,$name);
            }


            $checkIfuploaded = mysqli_query($conn,"SELECT Attachment_ID from tbl_attachment where Registration_ID='" . $Registration_ID . "' AND item_list_cache_id='$ppilc' AND image_name_flag = '$file_name' AND Check_In_Type='Laboratory'");

            $Attachment_ID = mysqli_fetch_assoc($checkIfuploaded)['Attachment_ID'];
            $sql = '';
            $insertmodify = '';

            if (mysqli_num_rows($checkIfuploaded) > 0) {
                if (mysqli_num_rows($validated) > 0) {
                    $insertmodify = "INSERT INTO tbl_attachment_modification (Attachment_ID, Employee_ID, Description, Attachment_Url, Attachment_Date, image_name_flag) SELECT Attachment_ID,Employee_ID, Description, Attachment_Url, Attachment_Date,image_name_flag FROM tbl_attachment WHERE Attachment_ID='$Attachment_ID'";
                }
                $sql = "UPDATE tbl_attachment SET Employee_ID='$Employee_ID',Description='$overalRemarks',Attachment_Url='$name',Attachment_Date=NOW() where Registration_ID='" . $Registration_ID . "' AND item_list_cache_id='$ppilc' AND image_name_flag = '$file_name'  AND Check_In_Type='Laboratory'";
            } else {
                $sql = "INSERT INTO tbl_attachment (Attachment_ID, Registration_ID, Employee_ID, Description, Check_In_Type, Attachment_Url, Attachment_Date, item_list_cache_id, payment_item_list_id,image_name_flag) VALUES('','$Registration_ID','$Employee_ID','$overalRemarks','Laboratory','$name',NOW(),'$ppilc','','$file_name')";
            }

            if (!empty($insertmodify)) {
                mysqli_query($conn,$insertmodify) or die(mysqli_error($conn));
            }

            if (mysqli_query($conn,$sql)) {
                if (!empty($name)) {
                  //  echo "ime upload ya kwanza $tmp_name $dir $name <br/>";
                    //echo"============================================";
                    $$tmp_name=mysqli_real_escape_string($conn,$tmp_name);
                    move_uploaded_file($tmp_name, $dir . $name);
                }
            } else {
                echo "-----";
                die(mysqli_error($conn));
            }
        }
    } else {
        $checkIfuploaded = mysqli_query($conn,"SELECT Attachment_ID from tbl_attachment where Registration_ID='" . $Registration_ID . "' AND item_list_cache_id='$ppilc' AND Check_In_Type='Laboratory'");

        $Attachment_ID = mysqli_fetch_assoc($checkIfuploaded)['Attachment_ID'];
        $sql = '';
        $insertmodify = '';

        if (mysqli_num_rows($checkIfuploaded) > 0) {
            if (mysqli_num_rows($validated) > 0) {
                $insertmodify = "INSERT INTO tbl_attachment_modification (Attachment_ID, Employee_ID, Description, Attachment_Url, Attachment_Date, image_name_flag) SELECT Attachment_ID,Employee_ID, Description, Attachment_Url, Attachment_Date,image_name_flag FROM tbl_attachment WHERE Attachment_ID='$Attachment_ID'";
            }
            $sql = "UPDATE tbl_attachment SET Employee_ID='$Employee_ID',Description='$overalRemarks',	Attachment_Date=NOW() where Registration_ID='" . $Registration_ID . "' AND item_list_cache_id='$ppilc' AND Check_In_Type='Laboratory'";
        } else {
            $sql = "INSERT INTO tbl_attachment (Attachment_ID, Registration_ID, Employee_ID, Description, Check_In_Type, Attachment_Url, Attachment_Date, item_list_cache_id) VALUES('','$Registration_ID','$Employee_ID','$overalRemarks','Laboratory','$name',NOW(),'$ppilc')";
        }

        if (!empty($insertmodify)) {
            mysqli_query($conn,$insertmodify) or die(mysqli_error($conn));
        }

        if (mysqli_query($conn,$sql)) {
            if (!empty($name)) {
            //  echo "ime upload";
                move_uploaded_file($tmp_name, $dir . $name);
            }
        } else {
            die(mysqli_error($conn));
        }
    }

    $update_saved = "UPDATE tbl_tests_parameters_results SET Saved='Yes',SavedBy='" . $Employee_ID . "',result='" . $result . "' WHERE ref_test_result_ID='" . $ref_test_result_ID . "' AND result IN ('None','negative','negative*','positive','normal','abnormal','high','low','') ";

    mysqli_query($conn,$update_saved) or die(mysqli_error($conn));
    //die("hiiiiiii");
//echo "uiuiuiuiui";
   echo 1;
}

