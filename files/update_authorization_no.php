<?php
include "./includes/connection.php";
session_start();
$Employee_ID = $_SESSION['userinfo']['Employee_ID'];
$Branch_ID = $_SESSION['userinfo']['Branch_ID'];

if(isset($_POST['AuthorizationNo']) && $_POST['AuthorizationNo'] != "" && isset($_POST['Registration_ID']) && $_POST['Registration_ID'] != "") {
    $AuthorizationNo = $_POST['AuthorizationNo'];
    $Registration_ID = $_POST['Registration_ID'];
    $package_id = $_POST['ProductCode'];
    $Guarantor_Name = $_POST['Guarantor_Name'];
    $Today_Date = mysqli_query($conn, "select now() as today");
    $Check_In_Date_And_Time = "";
    while ($row = mysqli_fetch_array($Today_Date)) {
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Check_In_Date_And_Time = date("Y-m-d H:i:s", strtotime($original_Date));
        $Today = $new_Date;
    }
    $Sponsor_ID =mysqli_fetch_assoc(mysqli_query($conn, "SELECT Sponsor_ID FROM tbl_sponsor WHERE Guarantor_Name='$Guarantor_Name'"))['Sponsor_ID'];
    $query = mysqli_query($conn, "SELECT Registration_ID FROM tbl_check_in WHERE Check_In_Date = '$Today' AND Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));

    if(mysqli_num_rows($query) > 0) {
        $run_query = mysqli_query($conn, "UPDATE tbl_check_in SET AuthorizationNo = '$AuthorizationNo', package_id = '$package_id' WHERE Check_In_Date = '$Today' AND Registration_ID = '$Registration_ID") or die(mysqli_error($conn));

        echo 1;
    } else {

        $query1 = mysqli_query($conn, "SELECT Folio_Number,ToBe_Admitted,Admission_ID,Admit_Status,Employee_ID,Ward_suggested,consultation_ID FROM tbl_check_in_details cd,tbl_admission ad  WHERE ad.Admision_ID=cd.Admission_ID AND Admission_Status='admitted' AND  Registration_ID = '$Registration_ID'  ORDER BY Admision_ID DESC LIMIT 1") or die(mysqli_error($conn));
        if(mysqli_num_rows($query1)>0){
            while($row = mysqli_fetch_array($query)) {
                $Admit_Status = $row['Admit_Status'];
                $Employee_ID = $row['Employee_ID'];
                $ToBe_Admitted = $row['ToBe_Admitted'];
                $Folio_Number = $row['Folio_Number'];
                $Admission_ID = $row['Admission_ID'];
                $consultation_ID = $row['consultation_ID'];
                $Ward_suggested = $row['Ward_suggested'];

                $run_query = mysqli_query($conn, "INSERT INTO tbl_check_in(Registration_ID,Visit_Date,Employee_ID,Check_In_Date_And_Time,Check_In_Date,Type_Of_Check_In,AuthorizationNo,CardStatus,Check_In_Status,Branch_ID,Folio_Status,package_id) VALUES('$Registration_ID','$Today','$Employee_ID','$Check_In_Date_And_Time','$Today','Continuous','$AuthorizationNo','ACCEPTED','saved','$Branch_ID','generated','$package_id')") or die(mysqli_error($conn));
                $Check_In_ID = mysqli_insert_id($conn);
                if($run_query){
                     $run_query2 = mysqli_query($conn, "INSERT INTO tbl_check_in_details(Registration_ID,Check_In_ID,Sponsor_ID,Admit_Status,ToBe_Admitted,Folio_Number,Admission_ID,Employee_ID,consultation_ID,Ward_suggested) VALUES('$Registration_ID','$Check_In_ID','$Sponsor_ID','$Admit_Status','$ToBe_Admitted','$Folio_Number','$Admission_ID','$Employee_ID','$consultation_ID','$Ward_suggested')") or die(mysqli_error($conn));
                     if($run_query2){
                         echo 1;
                     }else{
                         echo 2;
                     }
                    
                }else{
                    echo 2;
                }               

            }
        }else{
            $run_query = mysqli_query($conn, "INSERT INTO tbl_check_in(Registration_ID,Visit_Date,Employee_ID,Check_In_Date_And_Time,Check_In_Date,Type_Of_Check_In,AuthorizationNo,CardStatus,Check_In_Status,Branch_ID,Folio_Status,package_id) VALUES('$Registration_ID','$Today','$Employee_ID','$Check_In_Date_And_Time','$Today','Continuous','$AuthorizationNo','ACCEPTED','saved','$Branch_ID','generated','$package_id')") or die(mysqli_error($conn));
            $Check_In_ID = mysqli_insert_id($conn);
            if($run_query){
                $run_query2 = mysqli_query($conn, "INSERT INTO tbl_check_in_details(Registration_ID,Check_In_ID,Sponsor_ID,Admit_Status,ToBe_Admitted,Folio_Number,Admission_ID,Employee_ID,consultation_ID,Ward_suggested) VALUES('$Registration_ID','$Check_In_ID','$Sponsor_ID','no','not admitted','$Folio_Number',NULL,'$Employee_ID',NULL,NULL)") or die(mysqli_error($conn));
                if($run_query2){
                    echo 1;
                }else{
                    echo 2;
                }
            }else{
                echo 2;
            }
        }

    }
} else if(!isset($_POST['AuthorizationNo']) && isset($_POST['Registration_ID']) && $_POST['Registration_ID'] != "") {
    $Registration_ID = $_POST['Registration_ID'];

    $Today_Date = mysqli_query($conn, "select now() as today");
    while ($row = mysqli_fetch_array($Today_Date)) {
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
    }

    $query = mysqli_query($conn, "SELECT Registration_ID FROM tbl_check_in WHERE Check_In_Date = '$Today' AND Registration_ID = '$Registration_ID'");

    if(mysqli_num_rows($query) > 0) {
        echo 1;
    } else {
        echo 2;
    }
} else {}
