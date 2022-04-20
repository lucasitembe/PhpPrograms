<?php 
    include ("./includes/connection.php");
    include("allFunctions.php");
    @session_start();
    $fromDate = mysqli_real_escape_string($conn, $_GET['fromDate']);
    $toDate = mysqli_real_escape_string($conn, $_GET['toDate']);
    $Sponsor_ID = mysqli_real_escape_string($conn, $_GET['Sponsor_ID']);
    $Type_patient_case = $_GET['Type_patient_case'];
    $agetype = mysqli_real_escape_string($conn, $_GET['agetype']);
    $doctors = $_GET['doctors'];
    $Clinic_ID = $_GET['Clinic_ID'];
    if(isset($_POST['Clinic_Name'])){
        $Clinic_Name = $_POST['Clinic_Name'];
    }else{
        $Clinic_Name='';
    }
    $filter_report_by = $_GET['filter_report_by'];
    if(isset($_GET['ageFrom'])){
        $ageFrom = $_GET['ageFrom'];
    }else{
        $ageFrom = 0;
    }

    if(isset($_GET['ageTo'])){
        $ageTo = $_GET['ageTo'];
    }else{
        $ageTo = 0;
    }
    $filter = " AND Consultation_Date_And_Time BETWEEN DATE('$fromDate') AND DATE('$toDate') AND TIMESTAMPDIFF($agetype ,pr.Date_Of_Birth,CURDATE()) BETWEEN '".$ageFrom."' AND '".$ageTo."'";
    if ($Sponsor_ID != 'All') {
        $filter .= "  AND pr.Sponsor_ID=$Sponsor_ID ";
    } 
    if ($Type_patient_case != 'all') {
        $filter .= " AND c.Type_of_patient_case='$Type_patient_case'";
    }
    if ($doctors != 'all') {
        $filter .= " AND c.employee_ID='$doctors'";
    } 
    if ($Clinic_ID != 'all') {
        $filter.= " AND c.Clinic_ID='$Clinic_ID'";
    } 
    $Today_Date = mysqli_query($conn,"select now() as today");
    while ($row = mysqli_fetch_array($Today_Date)) {
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
        $age = '';
    }
    if (isset($_GET['filter_report_by'])) {
 
    $htm= "<center><table width='98%' style='background-color:white;font-size:15px;'>";
    $htm.= "<tr><td colspan='5'>CLINIC ATTENDANCE REPORT</td></tr><tr>";
    $htm.= "<th>SN</th><th>CLINIC</th><th>Male</th><th>Female</th><th>Total</th>";
    $htm.= "</tr>";
    $counter = 1;
    $total_male = 0;
    $total_female = 0;
    $male_count=0;
    $female_count=0;
    $selectclinicConsultation = mysqli_query($conn, "SELECT DISTINCT Clinic_Name,   c.Clinic_ID FROM tbl_consultation c, tbl_clinic tc WHERE c.Clinic_ID=tc.Clinic_ID AND  Consultation_Date_And_Time BETWEEN DATE('$fromDate') AND DATE('$toDate')  ") or die(mysqli_error($conn));
    
    if (mysqli_num_rows($selectclinicConsultation) > 0) {
        $Clinic_ID = "";
        $count_sn = 1;
        while ($clinic_rows = mysqli_fetch_assoc($selectclinicConsultation)) {
            $Clinic_ID = $clinic_rows['Clinic_ID'];
            $Clinic_Name =$clinic_rows['Clinic_Name'];

            $countGender =mysqli_query($conn,"SELECT count(case when Gender='Male' then 1 end) as male_count,count(case when Gender='Female' then 1 end) as female_count FROM tbl_consultation c, tbl_patient_registration pr WHERE pr.Registration_ID=c.Registration_ID   AND c.Clinic_ID='$Clinic_ID'  $filter ");
            
            while($rw = mysqli_fetch_assoc($countGender)){
                $male_count = $rw['male_count'];
                $female_count = $rw['female_count'];
            }

        $htm.= "<tr><td>$count_sn.</td><td>$Clinic_Name</td><td>" . number_format($male_count) . "</td><td>" . number_format($female_count) . "</td><td>" . number_format($male_count + $female_count) . "</td></tr>";
                $count_sn++;
                          
            $total_female +=$female_count;
            $total_male+=$male_count;
        }
    }
    
    $htm.= "</tr><td colspan='5'><hr></td>";
    $htm.= "<tr><td colspan='2'><b>Total Attendance</b></td><td style='text-align:center;'><b>" . number_format($total_male) . "</b></td><td style='text-align:center;'><b>" . number_format($total_female) . "</b></td><td style='text-align:right;padding-right:10px;'><b>" . number_format($total_male + $total_female) . "</b></td></tr>";
    $htm.= "<tr><td colspan='5'><hr></td></tr>";
    $htm.= "</table></center>";
}


if(isset($_GET['filter_report_byclinic'])){
    $htm="<table>";
    $htm.= "<thead><tr><td colspan='7'> Patients List Of ".$Clinic_Name." </td></tr>";
    $htm.="<tr><td colspan='7'> List Of Patients attendence From:{$fromDate} To:{$toDate} </td></tr>";
    $htm.= "<tr><th>SN</th><th>Patient Name</th><th>Reg No</th><th>Age</th><th>Date of Birth</th><th>Gender</th><td>Consultation Time</td></tr>";
    $htm.= "</thead>";
    $count=1;
    $PtList = mysqli_query($conn,"SELECT Patient_Name, Date_Of_Birth, c.Registration_ID, Gender, Consultation_Date_And_Time FROM tbl_consultation c, tbl_patient_registration pr WHERE pr.Registration_ID=c.Registration_ID  AND Process_Status='served'  AND c.Clinic_ID='$Clinic_ID'  $filter ");
    if(mysqli_num_rows($PtList)>0){
        while($rws = mysqli_fetch_assoc($PtList)){
            $Patient_Name = $rws['Patient_Name'];
            $Date_Of_Birth = $rws['Date_Of_Birth'];
            $Registration_ID = $rws['Registration_ID'];
            $Gender = $rws['Gender'];
            $Consultation_Date_And_Time =$rws['Consultation_Date_And_Time'];
            $date1 = new DateTime($Today);
            $date2 = new DateTime($Date_Of_Birth);
            $diff = $date1->diff($date2);
            $age = $diff->y . " Years, ";
            $age .= $diff->m . " Months, ";
            $age .= $diff->d . " Days";
            $htm.= "<tr>
                    <td>$count</td>
                    <td>$Patient_Name</td>
                    <td>$Registration_ID</td>                    
                    <td>$age</td>
                    <td>$Date_Of_Birth</td>
                    <td>$Gender</td>
                    <td>$Consultation_Date_And_Time</td>
                </tr>";
            $$count++;
        }
    }
    
    $htm.= "</table></fieldset>";
}

header("Content-Type:application/xls");
header("content-Disposition: attachement; filename= clinicattendance.xls");
echo $htm;
// mysqli_close($conn);