<?php

include("./includes/connection.php");
session_start();
$temp = 1;
// if (isset($_GET['Registration_ID'])) {
//     $Registration_ID = $_GET['Registration_ID'];
// }if (isset($_GET['start'])) {
//     $start_date = $_GET['start'];
// } if (isset($_GET['consultation_ID'])) {
//     $consultation_ID = $_GET['consultation_ID'];
// }
if (isset($_GET['admission_id'])) {
    $admision_id = $_GET['admission_id'];
  }
  
  if (isset($_GET['patient_id'])) {
   $Registration_ID = $_GET['patient_id'];
  }
if(isset($_SESSION['userinfo']['Employee_Name'])){
    $E_Name = $_SESSION['userinfo']['Employee_Name'];
  }else{
    $E_Name = '';
  }

$select_patien_details = mysqli_query($conn,"
		SELECT Member_Number,Patient_Name, Registration_ID,Gender,Guarantor_Name,Date_Of_Birth
			FROM 
				tbl_patient_registration pr, 
				tbl_sponsor sp
			WHERE 
				pr.Registration_ID = '$Registration_ID' AND
				sp.Sponsor_ID = pr.Sponsor_ID
				") or die(mysqli_error($conn));
$no = mysqli_num_rows($select_patien_details);
if ($no > 0) {
    while ($row = mysqli_fetch_array($select_patien_details)) {
        $Member_Number = $row['Member_Number'];
        $Patient_Name = $row['Patient_Name'];
        $Registration_ID = $row['Registration_ID'];
        $Gender = $row['Gender'];
        $Sponsor = $row['Guarantor_Name'];
        $DOB = $row['Date_Of_Birth'];
    }
} else {
    $Member_Number = '';
    $Patient_Name = '';
    $Gender = '';
    $Registration_ID = 0;
}
$age = date_diff(date_create($DOB), date_create('today'))->y;

$htm = "<center><img src='branchBanner/branchBanner.png' width='100%' ></center>";
$htm.="<p align='center'><b>PATOGRAPH RECORDS"
        . "<br/><br/>"
        . "<b>" . $Patient_Name . "</b>  | <b>" . $Gender . "</b> | <b>" . $age . " years</b> | <b>" . $Sponsor . "</b>"
        . "<br/><br/>"
        . "</p>";

$htm.= '<center><table width =100% id="nurse_obsv" class="table table-striped" border=1 style="border-collapse: collapse;" class="table table-striped">';
$htm.= '<thead>
            <tr>
                <td style="font-size:20px;"><b>Fetal Heart Rate</b></td>
                <td style="font-size:20px;"><b>Time(Hrs)</b></td>
                <td style="font-size:20px;"><b>Saved Time</b></td>
                <td style="font-size:20px;" colspan="2"><b>Saved By</b></td>
            </tr>
        </thead>'; 
        $select_date=mysqli_query($conn,"SELECT DISTINCT date(date_time), admission_id  FROM tbl_fetal_heart_rate_cache where patient_id='$Registration_ID' order by fetal_heart_rate_cache_id DESC");
    //    die("SELECT date(saved_time)  FROM pediatric_graph where Registration_ID='$Registration_ID'");
        while($takedate=mysqli_fetch_array($select_date)){
            $saved_time=$takedate[0];
            $admission_id=$takedate[1];
        }
           
            $fetal_heart_rate=mysqli_query($conn,"SELECT pd.fetal_heart_rate_cache_id, pd.x,pd.y, pd.date_time,emp.Employee_Name FROM tbl_fetal_heart_rate_cache as pd,tbl_employee as emp where pd.Employee_ID=emp.Employee_ID AND pd.patient_id='$Registration_ID' AND admission_id='$admission_id' ");

            $htm.= "<tr><td style='font-size:20px;text-align:center;' colspan='11'><b> $saved_time=====$admision_id</b></td></tr>";
            while ($row = mysqli_fetch_array($fetal_heart_rate)) {
                $htm.= "<tr >";
                $htm.= "<td style='font-size:15px;'>" . $row['x'] . "</td>";
                $htm.= "<td style='font-size:15px;'>" . $row['y'] . "</td>";
                $htm.= "<td style='font-size:15px;'>" . $row['date_time'] . "</td>";
                $htm.= "<td style='font-size:15px;'colspan='2'>" . $row['Employee_Name'] . "</td>";
                $htm.= "</tr>";
            
            }
            
            $liqour_remark=mysqli_query($conn,"SELECT  pd.liqour_remark,pd.liqour_remark_time, pd.date_time,emp.Employee_Name FROM tbl_mould_liqour as pd,tbl_employee as emp where pd.Employee_ID=emp.Employee_ID AND pd.patient_id='$Registration_ID' AND admission_id='$admission_id'");
            $htm.= '<thead>
                <tr>
                    <td style="font-size:20px;"><b>Liqour Remark</b></td>
                    <td style="font-size:20px;"><b>Time(Hrs)</b></td>
                    <td style="font-size:20px;"><b>Saved Time</b></td>
                    <td style="font-size:20px;" colspan="2"><b>Saved By</b></td>
                </tr>
                </thead>';
            while ($row = mysqli_fetch_array($liqour_remark)) {
                $htm.= "<tr >";
                $htm.= "<td style='font-size:15px;'>" . $row['liqour_remark'] . "</td>";
                $htm.= "<td style='font-size:15px;'>" . $row['liqour_remark_time'] . "</td>";
                $htm.= "<td style='font-size:15px;'>" . $row['date_time'] . "</td>";
                $htm.= "<td style='font-size:15px;'colspan='2'>" . $row['Employee_Name'] . "</td>";
                $htm.= "</tr>";
                // 77846
            }
            $select_moulding=mysqli_query($conn,"SELECT  pd.moulding,pd.moulding_time, pd.date_time,emp.Employee_Name FROM tbl_moulding as pd,tbl_employee as emp where pd.Employee_ID=emp.Employee_ID AND pd.patient_id='$Registration_ID' AND admission_id='$admission_id'");
            $htm.= '<thead>
                <tr>
                    <td style="font-size:20px;"><b>Moulding</b></td>
                    <td style="font-size:20px;"><b>Time(Hrs)</b></td>
                    <td style="font-size:20px;"><b>Saved Time</b></td>
                    <td style="font-size:20px;" colspan="2"><b>Saved By</b>
                    </td>
                </tr>
                </thead>';
            while ($row_moulding = mysqli_fetch_array($select_moulding)) {
                $htm.= "<tr >";
                $htm.= "<td style='font-size:15px;'>" . $row_moulding['moulding'] . "</td>";
                $htm.= "<td style='font-size:15px;'>" . $row_moulding['moulding_time'] . "</td>";
                $htm.= "<td style='font-size:15px;'>" . $row_moulding['date_time'] . "</td>";
                $htm.= "<td style='font-size:15px;' colspan='2'>" . $row_moulding['Employee_Name'] . "</td>";
                $htm.= "</tr>";
            }
            $select_progress_labour=mysqli_query($conn,"SELECT  pd.fx,pd.fy,pd.sx,pd.sy,pd.date_time,emp.Employee_Name FROM tbl_progress_of_labour as pd,tbl_employee as emp where pd.Employee_ID=emp.Employee_ID AND pd.patient_id='$Registration_ID' AND admission_id='$admission_id'");
            $htm.= '<thead>
                <tr>
                    <td style="font-size:20px;"><b>Cervical Dilation</b></td>Descent
                    <td style="font-size:20px;"><b>Descent</b></td>
                    <td style="font-size:20px;"><b>Time(Hrs)</b></td>
                    <td style="font-size:20px;"><b>Saved Time</b></td>
                    <td style="font-size:20px;"><b>Saved By</b></td>
                </tr>
                </thead>';
            while ($row_progress_labour= mysqli_fetch_array($select_progress_labour)) {
                $htm.= "<tr >";
                $htm.= "<td style='font-size:15px;'>" . $row_progress_labour['fx'] . "</td>";
                $htm.= "<td style='font-size:15px;'>" . $row_progress_labour['sx'] . "</td>";
                $htm.= "<td style='font-size:15px;'>" . $row_progress_labour['fy'] . "</td>";
                $htm.= "<td style='font-size:15px;'>" . $row_progress_labour['date_time'] . "</td>";
                $htm.= "<td style='font-size:15px;'>" . $row_progress_labour['Employee_Name'] . "</td>";
                $htm.= "</tr>";
            }
            $select_contraction=mysqli_query($conn,"SELECT  pd.contraction,pd.c_time,pd.actual_time,emp.Employee_Name FROM tbl_contraction as pd,tbl_employee as emp where pd.Employee_ID=emp.Employee_ID AND pd.patient_id='$Registration_ID' AND admission_id='$admission_id'");
            $htm.= '<thead>
                <tr>
                    <td style="font-size:20px;"><b>contraction</b></td>
                    <td style="font-size:20px;"><b>Time(Hrs)</b></td>
                    <td style="font-size:20px;"><b>Saved Time</b></td>
                    <td style="font-size:20px;"><b>Saved By</b></td>
                </tr>
                </thead>';
            while ($row_contraction= mysqli_fetch_array($select_contraction)) {
                $htm.= "<tr >";
                $htm.= "<td style='font-size:15px;'>" . $row_contraction['contraction'] . "</td>";
                $htm.= "<td style='font-size:15px;'>" . $row_contraction['c_time'] . "</td>";
                $htm.= "<td style='font-size:15px;'>" . $row_contraction['actual_time'] . "</td>";
                $htm.= "<td style='font-size:15px;'>" . $row_contraction['Employee_Name'] . "</td>";
                $htm.= "</tr>";
            }

            $select_oxytocine=mysqli_query($conn,"SELECT  pd.oxytocine,pd.oxytocine_time,pd.actual_oxytocine_time,emp.Employee_Name FROM tbl_oxytocine as pd,tbl_employee as emp where pd.Employee_ID=emp.Employee_ID AND pd.patient_id='$Registration_ID' AND admission_id='$admission_id'");
            $htm.= '<thead>
                <tr>
                    <td style="font-size:20px;"><b>Oxyticin IU</b></td>
                    <td style="font-size:20px;"><b>Time(Hrs)</b></td>
                    <td style="font-size:20px;"><b>Saved Time</b></td>
                    <td style="font-size:20px;"><b>Saved By</b></td>
                </tr>
                </thead>';
            while ($row_oxytocine= mysqli_fetch_array($select_oxytocine)) {
                $htm.= "<tr >";
                $htm.= "<td style='font-size:15px;'>" . $row_oxytocine['oxytocine'] . "</td>";
                $htm.= "<td style='font-size:15px;'>" . $row_oxytocine['oxytocine_time'] . "</td>";
                $htm.= "<td style='font-size:15px;'>" . $row_oxytocine['actual_oxytocine_time'] . "</td>";
                $htm.= "<td style='font-size:15px;'>" . $row_oxytocine['Employee_Name'] . "</td>";
                $htm.= "</tr>";
            }
            
            $select_drops=mysqli_query($conn,"SELECT  pd.drops,pd.oxytocine_time,pd.actual_time,emp.Employee_Name FROM tbl_oxytocine_drops as pd,tbl_employee as emp where pd.Employee_ID=emp.Employee_ID AND pd.patient_id='$Registration_ID' AND admission_id='$admission_id'");
            $htm.= '<thead>
                <tr>
                    <td style="font-size:20px;"><b>Drops/Minute Pulse</b></td>
                    <td style="font-size:20px;"><b>Time(Hrs)</b></td>
                    <td style="font-size:20px;"><b>Saved Time</b></td>
                    <td style="font-size:20px;"><b>Saved By</b></td>
                </tr>
                </thead>';
            while ($row_drops= mysqli_fetch_array($select_drops)) {
                $htm.= "<tr >";
                $htm.= "<td style='font-size:15px;'>" . $row_drops['drops'] . "</td>";
                $htm.= "<td style='font-size:15px;'>" . $row_drops['oxytocine_time'] . "</td>";
                $htm.= "<td style='font-size:15px;'>" . $row_drops['actual_time'] . "</td>";
                $htm.= "<td style='font-size:15px;'>" . $row_drops['Employee_Name'] . "</td>";
                $htm.= "</tr>";
            }
            $select_temp=mysqli_query($conn,"SELECT  pd.temp,pd.tr_time,pd.actual_temp_resp_time,emp.Employee_Name FROM tbl_temp_resp as pd,tbl_employee as emp where pd.Employee_ID=emp.Employee_ID AND pd.patient_id='$Registration_ID' AND admission_id='$admission_id'");
            $htm.= '<thead>
                <tr>
                    <td style="font-size:20px;"><b>Temperature</b></td>
                    <td style="font-size:20px;"><b>Time(Hrs)</b></td>
                    <td style="font-size:20px;"><b>Saved Time</b></td>
                    <td style="font-size:20px;"><b>Saved By</b></td>
                </tr>
                </thead>';
            while ($row_temp= mysqli_fetch_array($select_temp)) {
                $htm.= "<tr >";
                $htm.= "<td style='font-size:15px;'>" . $row_temp['temp'] . "</td>";
                $htm.= "<td style='font-size:15px;'>" . $row_temp['tr_time'] . "</td>";
                $htm.= "<td style='font-size:15px;'>" . $row_temp['actual_temp_resp_time'] . "</td>";
                $htm.= "<td style='font-size:15px;'>" . $row_temp['Employee_Name'] . "</td>";
                $htm.= "</tr>";
            }
            $liqour_resp=mysqli_query($conn,"SELECT  pd.resp,pd.resp_time, pd.actual_resp_time,emp.Employee_Name FROM tbl_resp as pd,tbl_employee as emp where pd.Employee_ID=emp.Employee_ID AND pd.patient_id='$Registration_ID' AND admission_id='$admission_id'");
            $htm.= '<thead>
                <tr>
                    <td style="font-size:20px;"><b>Respiratory</b></td>
                    <td style="font-size:20px;"><b>Time(Hrs)</b></td>
                    <td style="font-size:20px;"><b>Saved Time</b></td>
                    <td style="font-size:20px;" colspan="2"><b>Saved By</b></td>
                </tr>
                </thead>';
            while ($row = mysqli_fetch_array($liqour_resp)) {
                $htm.= "<tr >";
                $htm.= "<td style='font-size:15px;'>" . $row['resp'] . "</td>";
                $htm.= "<td style='font-size:15px;'>" . $row['resp_time'] . "</td>";
                $htm.= "<td style='font-size:15px;'>" . $row['actual_resp_time'] . "</td>";
                $htm.= "<td style='font-size:15px;'colspan='2'>" . $row['Employee_Name'] . "</td>";
                $htm.= "</tr>";
            }
            $liqour_pressure=mysqli_query($conn,"SELECT  pd.pressure,pd.pressure_time, pd.actual_pressure_time,emp.Employee_Name FROM tbl_pressure as pd,tbl_employee as emp where pd.Employee_ID=emp.Employee_ID AND pd.patient_id='$Registration_ID' AND admission_id='$admission_id'");
            $htm.= '<thead>
                <tr>
                    <td style="font-size:20px;"><b>pressure</b></td>
                    <td style="font-size:20px;"><b>Time(Hrs)</b></td>
                    <td style="font-size:20px;"><b>Saved Time</b></td>
                    <td style="font-size:20px;" colspan="2"><b>Saved By</b></td>
                </tr>
                </thead>';
            while ($row_pressure = mysqli_fetch_array($liqour_pressure)) {
                $htm.= "<tr >";
                $htm.= "<td style='font-size:15px;'>" . $row_pressure['pressure'] . "</td>";
                $htm.= "<td style='font-size:15px;'>" . $row_pressure['pressure_time'] . "</td>";
                $htm.= "<td style='font-size:15px;'>" . $row_pressure['actual_pressure_time'] . "</td>";
                $htm.= "<td style='font-size:15px;'colspan='2'>" . $row_pressure['Employee_Name'] . "</td>";
                $htm.= "</tr>";
            }
            $htm.= '<thead>
            <tr>
                <td style="font-size:20px;"><b>Acetone</b></td>
                <td style="font-size:20px;"><b>Time(Hrs)</b></td>
                <td style="font-size:20px;"><b>Saved Time</b></td>
                <td style="font-size:20px;" colspan="2"><b>Saved By</b></td>
            </tr>
            </thead>';
            $acetone=mysqli_query($conn,"SELECT pd.acetone, pd.acetone_time,pd.actual_acetone_time,emp.Employee_Name FROM tbl_acetone as pd,tbl_employee as emp where pd.Employee_ID=emp.Employee_ID AND pd.patient_id='$Registration_ID' AND admission_id='$admission_id' ");
            while ($row = mysqli_fetch_array($acetone)) {
                $htm.= "<tr >";
                $htm.= "<td style='font-size:15px;'>" . $row['acetone'] . "</td>";
                $htm.= "<td style='font-size:15px;'>" . $row['acetone_time'] . "</td>";
                $htm.= "<td style='font-size:15px;'>" . $row['actual_acetone_time'] . "</td>";
                $htm.= "<td style='font-size:15px;'colspan='2'>" . $row['Employee_Name'] . "</td>";
                $htm.= "</tr>";
            
            }
            $htm.= '<thead>
            <tr>
                <td style="font-size:20px;"><b>Volume</b></td>
                <td style="font-size:20px;"><b>Time(Hrs)</b></td>
                <td style="font-size:20px;"><b>Saved Time</b></td>
                <td style="font-size:20px;" colspan="2"><b>Saved By</b></td>
            </tr>
            </thead>';
            $volume=mysqli_query($conn,"SELECT pd.volume, pd.volume_time,pd.actual_volume_time,emp.Employee_Name FROM tbl_volume as pd,tbl_employee as emp where pd.Employee_ID=emp.Employee_ID AND pd.patient_id='$Registration_ID' AND admission_id='$admission_id'' ");
            while ($row = mysqli_fetch_array($volume)) {
                $htm.= "<tr >";
                $htm.= "<td style='font-size:15px;'>" . $row['volume'] . "</td>";
                $htm.= "<td style='font-size:15px;'>" . $row['volume_time'] . "</td>";
                $htm.= "<td style='font-size:15px;'>" . $row['actual_volume_time'] . "</td>";
                $htm.= "<td style='font-size:15px;'colspan='2'>" . $row['Employee_Name'] . "</td>";
                $htm.= "</tr>";
            
            }
            $htm.= '<thead>
            <tr>
                <td style="font-size:20px;"><b>Protein</b></td>
                <td style="font-size:20px;"><b>Time(Hrs)</b></td>
                <td style="font-size:20px;"><b>Saved Time</b></td>
                <td style="font-size:20px;" colspan="2"><b>Saved By</b></td>
            </tr>
            </thead>';
            $volume=mysqli_query($conn,"SELECT pd.protein, pd.urine_time,pd.actual_urine_time,emp.Employee_Name FROM tbl_urine as pd,tbl_employee as emp where pd.Employee_ID=emp.Employee_ID AND pd.patient_id='$Registration_ID' AND admission_id='$admission_id'");
            while ($row = mysqli_fetch_array($volume)) {
                $htm.= "<tr >";
                $htm.= "<td style='font-size:15px;'>" . $row['protein'] . "</td>";
                $htm.= "<td style='font-size:15px;'>" . $row['urine_time'] . "</td>";
                $htm.= "<td style='font-size:15px;'>" . $row['actual_urine_time'] . "</td>";
                $htm.= "<td style='font-size:15px;'colspan='2'>" . $row['Employee_Name'] . "</td>";
                $htm.= "</tr>";
            
            }      
    // }
            $data=mysqli_query($conn,"SELECT pd.date_birth, pd.weight,pd.weight,emp.Employee_Name,pd.weight,pd.sex,pd.apgar,pd.method_delivery,pd.first_stage,pd.second_stage,pd.third_stage,pd.placenta_membrane,pd.blood_loss,pd.fourth_stage,pd.reason_pph,pd.perineum,pd.repair_by,pd.delivery_by,pd.supervision_by,pd.save_date FROM summary_labour as pd,tbl_employee as emp where pd.Employee_ID=emp.Employee_ID AND pd.patient_id='$Registration_ID' AND admission_id='$admission_id'");
            while ($row = mysqli_fetch_array($data)) {
                $htm.= "<tr>";
                $htm.= "<td style='font-size:15px;'>First Stage Duration.</td>";
                $htm.= "<td style='font-size:15px;'>"   . $row['first_stage'] . "</td>";
                $htm.= "<td style='font-size:15px;'>Second Stage Duration</td>";
                $htm.= "<td style='font-size:15px;'>" . $row['second_stage'] . "</td>";
                $htm.= "<td style='font-size:15px;'>Third Stage Duration</td>";
                $htm.= "<td style='font-size:15px;'>" . $row['third_stage'] . "</td>";
                $htm.= "</tr>";

                $htm.= "<tr >";
                $htm.= "<td style='font-size:15px;'> Fourth Stage Duration </td>";
                $htm.= "<td style='font-size:15px;'>"   . $row['fourth_stage'] . "</td>";
                $htm.= "<td style='font-size:15px;'> Reason For PPH </td>";
                $htm.= "<td style='font-size:15px;'>" . $row['reason_pph'] . "</td>";
                $htm.= "<td style='font-size:15px;'>Perineum </td>";
                $htm.= "<td style='font-size:15px;'>" . $row['perineum'] . "</td>";
                $htm.= "</tr>";

                $htm.= "<tr >";
                $htm.= "<td style='font-size:15px;'>Placenta And Membranes </td>";
                $htm.= "<td style='font-size:15px;'>"   . $row['placenta_membrane'] . "</td>";
                $htm.= "<td style='font-size:15px;'>Blood Loss </td>";
                $htm.= "<td style='font-size:15px;'>" . $row['blood_loss'] . "</td>";
                $htm.= "<td style='font-size:15px;'>Repair By </td>";
                $htm.= "<td style='font-size:15px;'>" . $row['repair_by'] . "</td>";
                $htm.= "</tr>";

                $htm.= "<tr >";
                $htm.= "<td style='font-size:15px;'>Placenta And Membranes </td>";
                $htm.= "<td style='font-size:15px;'>"   . $row['placenta_membrane'] . "</td>";
                $htm.= "<td style='font-size:15px;'> Blood Loss </td>";
                $htm.= "<td style='font-size:15px;'>" . $row['blood_loss'] . "</td>";
                $htm.= "<td style='font-size:15px;'>Repair By </td>";
                $htm.= "<td style='font-size:15px;'>" . $row['repair_by'] . "</td>";
                $htm.= "</tr>";

                $htm.= "<tr >";
                $htm.= "<td style='font-size:15px;'>Delivery By </td>";
                $htm.= "<td style='font-size:15px;'>"   . $row['delivery_by'] . "</td>";
                $htm.= "<td style='font-size:15px;'> Supervision By </td>";
                $htm.= "<td style='font-size:15px;'>" . $row['supervision_by'] . "</td>";
                $htm.= "</tr>";
            
            }
    
        $htm.= "</table></center>";

include("./MPDF/mpdf.php");
$mpdf=new mPDF('s','A4-L', 0, '', 15,15,20,40,15,35, 'L');
$mpdf->SetFooter('Printed By '.strtoupper($E_Name).'|Page {PAGENO} Of {nb}|{DATE d-m-Y} Powered by GPITG');
$mpdf->WriteHTML($htm);
$mpdf->Output();
exit;
