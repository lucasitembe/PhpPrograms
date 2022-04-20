<?php

include("./includes/connection.php");


$Employee_Name = $_SESSION['userinfo']['Employee_Name'];

$is_perf_by_signe_off = $_SESSION['hospitalConsultaioninfo']['req_perf_by_signed_off'];
$is_const_per_day_count = $_SESSION['hospitalConsultaioninfo']['en_const_per_day_count'];



if (isset($_POST['Registration_ID'])) {
    $Registration_ID = $_POST['Registration_ID'];
} else {
    $Registration_ID = '';
}
if(isset($_POST['exemptionID'])){
    $exemptionID = $_POST['exemptionID'];
}else{
    $exemptionID ='';
}
    $select_patient = mysqli_query($conn, "SELECT Patient_Name, tef.Registration_ID,Nurse_Exemption_ID, Employee_Name,Employee_Title, Exemption_ID, tef.Employee_ID, tef.created_at FROM tbl_employee e, tbl_temporary_exemption_form tef, tbl_patient_registration pr WHERE tef.Registration_ID='$Registration_ID' AND Exemption_ID='$exemptionID' AND tef.Employee_ID=e.Employee_ID AND tef.Registration_ID=pr.Registration_ID ORDER BY created_at DESC ") or die(mysqli_error($conn));

?>
<table class="table">
    <thead>
        <th>#</th>
        <th>PATIENT NAME</th>
        <th>PATIENT REGISTRATION</th>
        <th>EMPLOYEE NAME</th>
        <th>EMPLOYEE TITLE</th>
        <th>DATE ASSIGNED </th>
        <th>ACTION</th>
    </thead>
    <tbody>
        <?php
            $num= 0;
            while($row = mysqli_fetch_assoc($select_patient)){
                $Exemption_ID =$row['Exemption_ID'];
                $Patient_Name = $row['Patient_Name'];
                $Registration_ID = $row['Registration_ID'];
                $Employee_Name = $row['Employee_Name'];
                $Employee_Title = $row['Employee_Title'];
                $created_at = $row['created_at'];
                $Nurse_Exemption_ID= $row['Nurse_Exemption_ID'];
                $num++;
        ?>
                <tr>
                    <td><?php echo $num; ?></td>
                    <td><?php echo $Patient_Name;?></td>
                    <td><?php echo $Registration_ID;?></td>
                    <td><?php echo $Employee_Name;?></td>
                    <td><?php echo $Employee_Title;?></td>
                    <td><?php echo $created_at;?></td>
                    <td>
                        <div class="btn-group hidden-sm"> 
                            <span ><a href="preview_exemptionform.php?Registration_ID=<?=$Registration_ID?>&Exemption_ID=<?=$Exemption_ID?>&Nurse_Exemption_ID=<?=$Nurse_Exemption_ID?>created_at=<?=$created_at?>" class="btn btn-primary btn-sm">PREVIEW</a><span> 
                            <?php $select_form = mysqli_query($conn, "SELECT Exemption_ID FROM tbl_temporary_exemption_form WHERE Exemption_ID NOT IN (SELECT Exemption_ID FROM tbl_exemption_maoni_dhs WHERE Exemption_ID='$Exemption_ID') GROUP BY Exemption_ID") or die(mysqli_error($conn));
                            if((mysqli_num_rows($select_form))>0){
                                echo "<span><a href='newexemptionform.php?Registration_ID=$Registration_ID&Exemption_ID=$Exemption_ID&Nurse_Exemption_ID=$Nurse_Exemption_ID&created_at=$created_at' class='btn btn-primary btn-sm'>EDIT</a><span>";

                                while($form = mysqli_fetch_assoc($select_form)){

                                }
                            }else{

                            }
                            ?>
                            
                        <div>
                    </td>
                </tr>
                <?php } ?>
    </tbody>
</table>