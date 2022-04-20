<?php
include("includes/connection.php");
$Employee_ID = $_POST['Employee_ID'];
$consultation_ID = $_POST['consultation_ID'];
$Registration_ID = $_POST['Registration_ID'];
$Type_of_brachytherapy = $_POST['Type_of_brachytherapy'];
$Number_of_Fraction = $_POST['Number_of_Fraction'];
$Dose_per_Fraction = $_POST['Dose_per_Fraction'];
$Brachytherapy_ID = $_POST['Brachytherapy_ID'];
$Name_of_Applicator = $_POST['Name_of_Applicator'];
$type_of_applicator = $_POST['type_of_applicator'];
$Planned_Time = $_POST['Planned_Time'];
$Rectum_Percentage = $_POST['Rectum_Percentage'];
$Bladder_Percentage = $_POST['Bladder_Percentage'];
$Treated_Time = $_POST['Treated_Time'];
$Commulative_Dose = $_POST['Commulative_Dose'];
$Insertion_ID = $_POST['Insertion_ID'];
$action = $_POST['action'];
$Comment_Insertion = $_POST['Comment_Insertion'];
$Comment_Calculation = $_POST['Comment_Calculation'];
$Remarks = $_POST['Remarks'];
$display = '';

if($action == 'Treatment' && !empty($Insertion_ID)){
    $GetBrachytherapy_ID =mysqli_query($conn, "SELECT br.Insertion_Status, Number_of_Fraction FROM tbl_brachytherapy_requests br, WHERE Brachytherapy_ID = bri.Brachytherapy_ID AND bri.Insertion_ID = '$Insertion_ID'");

    $Update_Phase = mysqli_query($conn, "UPDATE tbl_brachytherapy_insertion SET Insertion_Status = 'Delivery', Remarks = '$Remarks', Treated_Time = '$Treated_Time', Comulative_Dose = '$Commulative_Dose', Treated_By = '$Employee_ID', Treated_DateTime = NOW() WHERE Insertion_ID = '$Insertion_ID' AND  Insertion_Status <> 'Delivery'");
        if(($Update_Phase)){
            echo 200;
        }else{
            echo 201;
        }

}elseif($action == 'Get Data' && !empty($Insertion_ID)){
    $SN = 1;
    $Select_treatment = mysqli_query($conn, "SELECT em.Employee_Name, bri.Treated_DateTime, bri.Comulative_Dose, br.Dose_per_Fraction, bri.Fraction_Number, bri.Treated_Time FROM tbl_employee em, tbl_brachytherapy_insertion bri, tbl_brachytherapy_requests br WHERE bri.Insertion_ID = '$Insertion_ID' AND em.Employee_ID = bri.Treated_By AND br.Brachytherapy_ID = bri.Brachytherapy_ID") or die(mysqli_error($conn));
        if(mysqli_num_rows($Select_treatment)>0){
            while($dts = mysqli_fetch_assoc($Select_treatment)){
                $Employee_Name = $dts['Employee_Name'];
                $Treated_DateTime = $dts['Treated_DateTime'];
                $Comulative_Dose = $dts['Comulative_Dose'];
                $Dose_per_Fraction = $dts['Dose_per_Fraction'];
                $Fraction_Number = $dts['Fraction_Number'];
                $Treated_Time = $dts['Treated_Time'];

                echo "<tr>
                            <td>".$SN."</td>
                            <td>".$Treated_DateTime."</td>
                            <td>".ucwords($Employee_Name)."</td>
                            <td>".$Dose_per_Fraction."</td>
                            <td>".$Comulative_Dose."</td>
                            <td>".$Fraction_Number."</td>
                            <td>".$Treated_Time."</td></tr>";
            }
        }

}elseif($action == 'Calculation' && !empty($Insertion_ID)){

    $Update_Phase = mysqli_query($conn, "UPDATE tbl_brachytherapy_insertion SET Insertion_Status = 'Calculated', Planned_Time = '$Planned_Time', Bladder_Percentage = '$Bladder_Percentage', Rectum_Percentage = '$Rectum_Percentage', calculation_Employee = '$Employee_ID', Comment_Calculation= '$Comment_Calculation', Calculation_DateTime = NOW() WHERE Insertion_ID = '$Insertion_ID'");
        if($Update_Phase){
            echo 200;
        }else{
            echo 201;
        }

}elseif($action == 'Insertion' && !empty($Brachytherapy_ID)){
    $GetBrachytherapy_ID =mysqli_query($conn, "SELECT Insertion_Status, Number_of_Fraction FROM tbl_brachytherapy_requests WHERE Brachytherapy_ID = '$Brachytherapy_ID'");
    if(mysqli_num_rows($GetBrachytherapy_ID) > 0){
        while($dets = mysqli_fetch_assoc($GetBrachytherapy_ID)){
            $Insertion_Status = $dets['Insertion_Status'];
            $Number_of_Fraction = $dets['Number_of_Fraction'];

            $Insertion_Status_new = $Insertion_Status + 1;

            $Insertion = mysqli_query($conn, "INSERT INTO tbl_brachytherapy_insertion (Insertion_Status, type_of_applicator, Name_of_Applicator, Insertion_DateTime, Insertion_Employee, Brachytherapy_ID, Comment_Insertion) VALUES('Inserted', '$type_of_applicator', '$Name_of_Applicator', NOW(), '$Employee_ID', '$Brachytherapy_ID', '$Comment_Insertion')") or die(mysqli_error($conn));
            if($Insertion){
                $Insertion_ID = mysqli_insert_id($conn);

                if($Insertion_Status_new >= $Number_of_Fraction){
                    $Update_Phase_Insertion = mysqli_query($conn, "UPDATE tbl_brachytherapy_insertion SET Fraction_Number = '$Insertion_Status_new' WHERE Insertion_ID = '$Insertion_ID'");
                    $Update_Phase = mysqli_query($conn, "UPDATE tbl_brachytherapy_requests SET Request_Status = 'Completed', Insertion_Status = 'completed' WHERE Brachytherapy_ID = '$Brachytherapy_ID'");
                            if($Update_Phase){
                                echo 200;
                            }else{
                                echo 201;
                            }          
                }else{
                    $Update_Phase = mysqli_query($conn, "UPDATE tbl_brachytherapy_requests SET Insertion_Status = '$Insertion_Status_new' WHERE Brachytherapy_ID = '$Brachytherapy_ID'");
                    if($Update_Phase){
                        echo 200;
                    }else{
                        echo 201;
                    }       
                }
            }
        }
          
    // $Update_Phase = mysqli_query($conn, "UPDATE tbl_brachytherapy_requests SET Request_Status = 'Inserted', type_of_applicator = '$type_of_applicator', Name_of_Applicator = '$Name_of_Applicator', Insertion_Status = 'Inserted', Insertion_Employee = '$Employee_ID', Insertion_DateTime = NOW() WHERE Brachytherapy_ID = '$Brachytherapy_ID'");
    //     if($Update_Phase){
    //         echo 200;
    //     }else{
    //         echo 201;
    //     }
    }
}elseif($action == 'Submit' && !empty($consultation_ID)){
    $Brachytherapy_ID =mysqli_fetch_assoc(mysqli_query($conn, "SELECT Brachytherapy_ID FROM tbl_brachytherapy_requests WHERE consultation_ID = '$consultation_ID'"))['Brachytherapy_ID'];
    if($Brachytherapy_ID > 0){
            $Update_Phase = mysqli_query($conn, "UPDATE tbl_brachytherapy_requests SET Request_Status = 'Submitted' WHERE Brachytherapy_ID = '$Brachytherapy_ID'");
                if($Update_Phase){
                    echo 200;
                }else{
                    echo 201;
                }
    }    
}else{
    $Brachytherapy_ID =mysqli_fetch_assoc(mysqli_query($conn, "SELECT Brachytherapy_ID FROM tbl_brachytherapy_requests WHERE consultation_ID = '$consultation_ID'"))['Brachytherapy_ID'];
    if($Brachytherapy_ID > 0){
            $Update_Phase = mysqli_query($conn, "UPDATE tbl_brachytherapy_requests SET Number_of_Fraction = '$Number_of_Fraction', Dose_per_Fraction = '$Dose_per_Fraction', Type_of_brachytherapy = '$Type_of_brachytherapy' WHERE Brachytherapy_ID = '$Brachytherapy_ID'");
                if($Update_Phase){
                    echo 200;
                }else{
                    echo 201;
                }       
    }else{
        $Insert_Radiotherapy = mysqli_query($conn, "INSERT INTO tbl_brachytherapy_requests(consultation_ID, Registration_ID, Employee_ID, Request_Status, Date_Time) VALUES('$consultation_ID', '$Registration_ID', '$Employee_ID', 'pending', NOW())") or die(mysqli_error($conn));
        if($Insert_Radiotherapy){
            $Brachytherapy_ID = mysqli_insert_id($conn);
            if($Brachytherapy_ID > 0){
                $Update_Phase = mysqli_query($conn, "UPDATE tbl_brachytherapy_requests SET Number_of_Fraction = '$Number_of_Fraction', Dose_per_Fraction = '$Dose_per_Fraction', Type_of_brachytherapy = '$Type_of_brachytherapy' WHERE Brachytherapy_ID = '$Brachytherapy_ID'");
                if($Update_Phase){
                    echo 200;
                }else{
                    echo 201;
                }  
            }
        }
    }
}

mysqli_close($conn);

?>