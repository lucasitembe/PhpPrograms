<?php
    @session_start();
    include("./includes/connection.php");
    date_default_timezone_set("Africa/Dar_es_Salaam");
    echo "<link rel='stylesheet' href='fixHeader.css'>";

    if(isset($_GET['Date_From'])){
        $Date_From = $_GET['Date_From'];
    }else{
        $Date_To = '';
    }

    if(isset($_GET['Date_To'])){
        $Date_To = $_GET['Date_To'];
    }else{
        $Date_To = '';
    }

    if(isset($_GET['Search_Value'])){
        $Search_Value = $_GET['Search_Value'];
    }else{
        $Search_Value = '';
    }

    if(isset($_GET['Sponsor_ID'])){
        $Sponsor_ID = $_GET['Sponsor_ID'];
    }else{
        $Sponsor_ID = 0;
    }


    if(isset($_GET['Type_Of_Check_In'])){
        $Type_Of_Check_In = $_GET['Type_Of_Check_In'];
    }else{
        $Type_Of_Check_In = 'All';
    }

    if(isset($_GET['Employee_ID'])){
        $Employee_ID = $_GET['Employee_ID'];
    }else{
        $Employee_ID = '0';
    }
?>
<div id="Patients_progress"></div>
<legend style="background-color:#006400;color:white;padding:5px;" align='right'><b>LIST OF CHECKED IN PATIENTS</b></legend>
<table width=100% class="fixTableHead">
    <thead>
        <tr style="background-color: #ccc;">
            <td width=5%><b>SN</b></td>
            <td width=10%><b>PATIENT NAME</b></td>
            <td width=5%><b>PATIENT#</b></td>
            <td width=5%><b>SPONSOR</b></td>
            <td width=10%><b>PHONE#</b></td>
            <td width=10%><b>CHECKED-IN DATE</b></td>
            <td width=10%><b>CHECKED-IN BY</b></td>
            <td width=10%><b>PATIENT DIRECTION</b></td>
            <td width=15%><b>CONSULTED BY</b></td>
            <td width=10%><b>CONSULTED DATE</b></td>
            <td width=25%><b>WAITING TIME</b></td>
        </tr>
    </thead>

    <?php
        $temp = 0;
        $sql = "select
                    sp.Guarantor_Name,
                    pr.Registration_ID,
                    ppl.Patient_Direction,
                    pr.Patient_Name,
                    pr.Phone_Number,
                    ci.Type_Of_Check_In,
                    ci.Check_In_Date_And_Time,
                    ci.AuthorizationNo,
                    ci.visit_type,
                    tc.employee_ID,
                    emp.Employee_Name,
                    tc.Consultation_Date_And_Time as serveddatetime
                    from tbl_check_in ci, tbl_employee emp, tbl_patient_registration pr, tbl_sponsor sp ,`tbl_patient_payments` pp,tbl_patient_payment_item_list ppl,tbl_consultation tc
                    where
                        pr.Registration_ID = ci.Registration_ID and
                        ci.Employee_ID = emp.Employee_ID and
                        pr.Sponsor_ID = sp.Sponsor_ID and
                        tc.Patient_Payment_Item_List_ID = ppl.Patient_Payment_Item_List_ID AND
                        ppl.Patient_Payment_ID = pp.Patient_Payment_ID AND
                        ppl.Patient_Direction in ('Direct To Doctor','Direct To Clinic') AND
                        ppl.Check_In_Type = 'Doctor Room' AND
                        ppl.Process_Status != 'not served' AND
                        pp.Check_In_ID = ci.Check_In_ID AND";
        //get list of checked in patients
        if($Type_Of_Check_In == 'All'){
            if($Sponsor_ID == 0){
                if($Employee_ID == '0'){
                    if(isset($_GET['Search_Value'])){
                        $select = mysqli_query($conn,"$sql ci.Check_In_Date_And_Time between '$Date_From' and '$Date_To' and
                                                pr.Patient_Name like '%$Search_Value%'
                                                order by ci.Check_In_Date_And_Time desc") or die(mysqli_error($conn));
                    }else{
                        $select = mysqli_query($conn,"$sql ci.Check_In_Date_And_Time between '$Date_From' and '$Date_To'
                                                order by ci.Check_In_Date_And_Time desc") or die(mysqli_error($conn));
                    }
                }else{
                    if(isset($_GET['Search_Value'])){
                        $select = mysqli_query($conn,"$sql ci.Check_In_Date_And_Time between '$Date_From' and '$Date_To' and
                                                pr.Patient_Name like '%$Search_Value%' and
                                                ci.Employee_ID = '$Employee_ID'
                                                order by ci.Check_In_Date_And_Time desc") or die(mysqli_error($conn));
                    }else{
                        $select = mysqli_query($conn,"$sql ci.Check_In_Date_And_Time between '$Date_From' and '$Date_To' and
                                                ci.Employee_ID = '$Employee_ID'
                                                order by ci.Check_In_Date_And_Time desc") or die(mysqli_error($conn));
                    }
                }
            }else{
                if($Employee_ID == 0){
                    if(isset($_GET['Search_Value'])){
                        $select = mysqli_query($conn,"$sql pr.Sponsor_ID = '$Sponsor_ID' and
                                                ci.Check_In_Date_And_Time between '$Date_From' and '$Date_To' and
                                                pr.Patient_Name like '%$Search_Value%'
                                                order by ci.Check_In_Date_And_Time desc") or die(mysqli_error($conn));
                    }else{
                        $select = mysqli_query($conn,"$sql pr.Sponsor_ID = '$Sponsor_ID' and
                                                ci.Check_In_Date_And_Time between '$Date_From' and '$Date_To'
                                                order by ci.Check_In_Date_And_Time desc") or die(mysqli_error($conn));
                    }
                }else{
                    if(isset($_GET['Search_Value'])){
                        $select = mysqli_query($conn,"$sql pr.Sponsor_ID = '$Sponsor_ID' and
                                                ci.Check_In_Date_And_Time between '$Date_From' and '$Date_To' and
                                                pr.Patient_Name like '%$Search_Value%' and
                                                ci.Employee_ID = '$Employee_ID'
                                                order by ci.Check_In_Date_And_Time desc") or die(mysqli_error($conn));
                    }else{
                        $select = mysqli_query($conn,"$sql pr.Sponsor_ID = '$Sponsor_ID' and
                                                ci.Check_In_Date_And_Time between '$Date_From' and '$Date_To' and
                                                ci.Employee_ID = '$Employee_ID'
                                                order by ci.Check_In_Date_And_Time desc") or die(mysqli_error($conn));
                    }
                }
            }
        }else{
            if($Sponsor_ID == 0){
                if($Employee_ID == '0'){
                    if(isset($_GET['Search_Value'])){
                        $select = mysqli_query($conn,"$sql ci.Check_In_Date_And_Time between '$Date_From' and '$Date_To' and
                                                pr.Patient_Name like '%$Search_Value%' and
                                                ci.Type_Of_Check_In = '$Type_Of_Check_In'
                                                order by ci.Check_In_Date_And_Time desc") or die(mysqli_error($conn));
                    }else{
                        $select = mysqli_query($conn,"$sql ci.Check_In_Date_And_Time between '$Date_From' and '$Date_To' and
                            ci.Type_Of_Check_In = '$Type_Of_Check_In'
                                                order by ci.Check_In_Date_And_Time desc") or die(mysqli_error($conn));
                    }
                }else{
                    if(isset($_GET['Search_Value'])){
                        $select = mysqli_query($conn,"$sql ci.Check_In_Date_And_Time between '$Date_From' and '$Date_To' and
                                                pr.Patient_Name like '%$Search_Value%' and
                                                ci.Employee_ID = '$Employee_ID' and
                                                ci.Type_Of_Check_In = '$Type_Of_Check_In'
                                                order by ci.Check_In_Date_And_Time desc") or die(mysqli_error($conn));
                    }else{
                        $select = mysqli_query($conn,"$sql ci.Check_In_Date_And_Time between '$Date_From' and '$Date_To' and
                                                ci.Employee_ID = '$Employee_ID' and
                                                ci.Type_Of_Check_In = '$Type_Of_Check_In'
                                                order by ci.Check_In_Date_And_Time desc") or die(mysqli_error($conn));
                    }
                }
            }else{
                if($Employee_ID == 0){
                    if(isset($_GET['Search_Value'])){
                        $select = mysqli_query($conn,"$sql pr.Sponsor_ID = '$Sponsor_ID' and
                                                ci.Check_In_Date_And_Time between '$Date_From' and '$Date_To' and
                                                pr.Patient_Name like '%$Search_Value%' and
                                                ci.Type_Of_Check_In = '$Type_Of_Check_In'
                                                order by ci.Check_In_Date_And_Time desc") or die(mysqli_error($conn));
                    }else{
                        $select = mysqli_query($conn,"$sql pr.Sponsor_ID = '$Sponsor_ID' and
                                                ci.Check_In_Date_And_Time between '$Date_From' and '$Date_To' and
                                                ci.Type_Of_Check_In = '$Type_Of_Check_In'
                                                order by ci.Check_In_Date_And_Time desc") or die(mysqli_error($conn));
                    }
                }else{
                    if(isset($_GET['Search_Value'])){
                        $select = mysqli_query($conn,"$sql pr.Sponsor_ID = '$Sponsor_ID' and
                                                ci.Check_In_Date_And_Time between '$Date_From' and '$Date_To' and
                                                pr.Patient_Name like '%$Search_Value%' and
                                                ci.Employee_ID = '$Employee_ID' and
                                                ci.Type_Of_Check_In = '$Type_Of_Check_In'
                                                order by ci.Check_In_Date_And_Time desc") or die(mysqli_error($conn));
                    }else{
                        $select = mysqli_query($conn,"$sql pr.Sponsor_ID = '$Sponsor_ID' and
                                                ci.Check_In_Date_And_Time between '$Date_From' and '$Date_To' and
                                                ci.Employee_ID = '$Employee_ID' and
                                                ci.Type_Of_Check_In = '$Type_Of_Check_In'
                                                order by ci.Check_In_Date_And_Time desc") or die(mysqli_error($conn));
                    }
                }
            }
        }

        $result_date = "";
        $Today_Date = mysqli_query($conn,"select now() as today") or die(mysqli_error($conn));
        while ($row = mysqli_fetch_array($Today_Date)) {
            $original_Date = $row['today'];
            $result_date = $original_Date;
        }

        $result_date = $row['serveddatetime'];

        $num = mysqli_num_rows($select);
        if($num > 0){
            while($row = mysqli_fetch_array($select)){
               $visit_type= $row['visit_type'];
               if($visit_type==1){
                   $visit_type="Normal visity";
               }else if($visit_type==2){
                    $visit_type="Emegency";
               }else if($visit_type==3){
                    $visit_type="Refferal";
               }else{
                   $visit_type="";
               }

               $resultdate = date('Y-m-d H:i:s',strtotime('+1 hour',strtotime($row['serveddatetime'])));

               $date1 = new DateTime($resultdate);
                $date2 = new DateTime($row['Check_In_Date_And_Time']);
                $diff = $date1->diff($date2);
                $lapsed_time = $diff->d . " days, ";
                $days = $diff->d;
                $lapsed_time .= $diff->h . " hours, ";
                $hrs = $diff->h;
                $lapsed_time .= $diff->i . " minutes,";
                $min = $diff->i;
                $lapsed_time .= $diff->s . " Seconds";

                $style = "background: green;";
                $query = mysqli_query($conn,"select configvalue from tbl_config where configname='Patient Waiting Time'") or die(mysqli_error($conn));
                $normal_range  = mysqli_fetch_assoc($query)['configvalue'];
                $tatrang = explode("Min",  $normal_range);
                if((int)$days > 0){
                    $style = "background: red;";
                }else{
                    if($tatrang[0]."Min" == $normal_range){
                        if ((((int)$hrs*60) + (int)$min) > (int)$tatrang[0]){
                            $style = "background: red;";
                        }

                    } else {
                        $tatrang = explode("Hrs",  $normal_range);
                        if($tatrang[0]."Hrs" == $normal_range){
                        if (((int)$days*24) > (int)$tatrang[0]){
                            $style = "background: red;";
                        } elseif ((((int)$hrs *60) + (int)$min) > ((int)$tatrang[0] * 60)) {
                            $style = "background: red;";
                        }
                    }
                    }
                }
                $employee_id = $row['employee_ID'];
                $ConsultantQuery = mysqli_query($conn,"select Employee_Name from tbl_employee where Employee_ID='$employee_id'") or die(mysqli_error($conn));
                $Consultant  = mysqli_fetch_assoc($ConsultantQuery)['Employee_Name'];
                // echo "<tr>
                //     <td>".++$temp."</td>
                //     <td>".$row['Patient_Name']."</td>
                //     <td>".$row['Registration_ID']."</td>
                //     <td>".$row['Guarantor_Name']."</td>
                //     <td>".$row['Phone_Number']."</td>
                //     <td>".$row['Type_Of_Check_In']."</td>
                //     <td>".$visit_type."</td>
                //     <td>".$row['Check_In_Date_And_Time']."</td>
                //     <td>".$row['Employee_Name']."</td>
                //     <td>".$lapsed_time."</td>
                // </tr>";
                echo "<tr style='".$style."color: white;'>
                    <td>".++$temp."</td>
                    <td>".$row['Patient_Name']."</td>
                    <td>".$row['Registration_ID']."</td>
                    <td>".$row['Guarantor_Name']."</td>
                    <td>".$row['Phone_Number']."</td>
                    <td>".$row['Check_In_Date_And_Time']."</td>
                    <td>".$row['Employee_Name']."</td>
                    <td>".$row['Patient_Direction']."</td>
                    <td>".$Consultant."</td>
                    <td>".$resultdate."</td>
                    <td>".$lapsed_time."</td>
                </tr>";
            }
        }
    ?>
</table>

<script type="text/javascript">
   document.getElementById('Patients_progress').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';
</script>

