<?php
include("./includes/connection.php");
include_once("./functions/database.php");
$temp = 1;
if (isset($_GET['Registration_ID'])) {
    $Registration_ID = $_GET['Registration_ID'];
}if (isset($_GET['start'])) {
    $start_date = $_GET['start'];
}
if (isset($_GET['end'])) {
    $end_date = $_GET['end'];
} if (isset($_GET['consultation_ID'])) {
    $consultation_ID = $_GET['consultation_ID'];
}

if (isset($_GET['consultation_ID'])) {
    $consultation_ID = $_GET['consultation_ID'];
}

if ($Registration_ID != 0) {

    $select_patien_details = mysqli_query($conn,"
        SELECT Member_Number,Patient_Name,sp.Sponsor_ID, Registration_ID,Gender,Guarantor_Name,Date_Of_Birth
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
            $Sponsor_ID = $row['Sponsor_ID'];
            $DOB = $row['Date_Of_Birth'];
        }
    } else {
        $Member_Number = '';
        $Patient_Name = '';
        $Gender = '';
        $Sponsor_ID = 0;
        $Registration_ID = 0;
    }
} else {
    $Member_Number = '';
    $Patient_Name = '';
    $Gender = '';
    $Sponsor_ID = 0;
    $Registration_ID = 0;
}

$Today_Date = mysqli_query($conn,"select now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
}
 
$htm = "<center><img src='branchBanner/branchBanner.png' width='100%' ></center>";
$htm.="<p align='center'><b>PATIENT DISCONTINUED MEDICINE LIST" . $betweenDate . ""
        . "<br/><br/>"
        . "<b>" . $Patient_Name . "</b>  | <b>" . $Gender . "</b> | <b>" . $age . " years</b> | <b>" . $Sponsor . "</b>"
        . "<br/><br/>"
        . "</p>";
    $temp=1;
    $date="==";
   $select_day=mysqli_query($conn,"SELECT DATE(me.Time_Given) AS Time_Given FROM tbl_inpatient_medicines_given me, tbl_patient_registration pr WHERE Discontinue_Status = 'yes' AND me.Registration_ID=pr.Registration_ID AND me.Registration_ID='$Registration_ID' GROUP BY DATE(me.Time_Given) ORDER BY me.Time_Given ASC");

    $count=0;
    while($row_day=mysqli_fetch_assoc($select_day)){
        $time_given1=$row_day['Time_Given'];
            $count++;
            $date=$date."==".$time_given1;

        $age = date_diff(date_create($DOB), date_create('today'))->y;

        $select_services = "SELECT Price,given_time,Product_Name,Time_Given,Amount_Given,Nurse_Remarks,Discontinue_Status,Discontinue_Reason,Employee_Name FROM tbl_inpatient_medicines_given sg, tbl_items it, tbl_employee em, tbl_item_list_cache tilc WHERE em.Employee_ID = sg.Employee_ID AND it.Item_ID = sg.Item_ID AND sg.Payment_Item_Cache_List_ID = tilc.Payment_Item_Cache_List_ID AND sg.Registration_ID={$Registration_ID} AND sg.consultation_ID={$consultation_ID} AND Discontinue_Status='yes' AND DATE(Time_Given)=DATE('$time_given1') ORDER BY sg.Time_Given DESC";
         
        $selected_services = mysqli_query($conn,$select_services) or die(mysqli_error($conn));
        $rows_exe = mysqli_num_rows($selected_services);


  $time_given1=date("l jS \of F Y ",strtotime($time_given1));  
 $htm.= "<h5>$time_given1</h5>";
    $htm.= "<table width='100%' id='nurse_medicine'>";
$htm.= ""
 . "<tr nobr='true'>";
    $htm .="<th width='5%'> SN </th>";
    $htm .="<th> Medicine Name </th>";
    $htm .="<th> Route </th>";

    $htm .="<th>Nurse Significant Events and Interventions </th>";
    $htm .="<th> Discontinue Reason </th>";

    $htm .="<th width='5%'> Discontinued Time </th>";
    $htm .="<th> Dose </th>";

    $htm .="<th width='5%'> Amnt Given </th>";
    $htm .="<th width='5%'> Subtotal</th>";

    $htm .="<th> Discontinued By </th>";
    $htm .="</tr>";
    $htm .="</thead>";

$sn = 1;
while ($service = mysqli_fetch_assoc($selected_services)) {
    $Product_Name = $service['Product_Name'];
    $route_type = $service['route_type'];
    $given_time = $service['given_time'];
    $Time_Given = $service['Time_Given'];
    $Amount_Given = $service['Amount_Given'];
    $Nurse_Remarks = $service['Nurse_Remarks'];
    $Discontinue_Status = $service['Discontinue_Status'];
    $Discontinue_Reason = $service['Discontinue_Reason'];
    $Employee_Name = $service['Employee_Name'];
    $Price = $service['Price'];

    $sub_total = (int) $Price * (int) $Amount_Given;

    $htm .="<tr>";
    $htm .="<td id='thead'>" . $sn . "</td>";
    $htm .="<td>" . $Product_Name . "</td>";
    $htm .="<td>" . $route_type . "</td>";

    $htm .="<td>" . $Time_Given . "</td>";
    $htm .="<td>" . $Nurse_Remarks . "</td>";
    $htm .="<td>" . $Discontinue_Reason . "</td>";
    $htm .="<td>" . $given_time . "</td>";

    $htm .="<td>" . $Amount_Given . "</td>";
    $htm .="<td width='5%' style='text-align:right'>" . number_format($sub_total) . "</td>";
    $htm .="<td>" . $Employee_Name . "</td>";
    $htm .="</tr>";
    $sn++;
}
    
$htm .="</table>";
//$htm .=$count;
//$htm .=$date;
//$htm .="lkjoji".$rows_exe;
    }
  

    include("MPDF/mpdf.php");
    $mpdf = new mPDF('s', 'A4');
    $mpdf->SetFooter('|Page {PAGENO} of {nb}|{DATE d-m-Y}');

    $stylesheet = file_get_contents('patient_file.css');
    $mpdf->WriteHTML($stylesheet, 1); // The parameter 1 tells that this is css/style only and no body/html/text
    $mpdf->WriteHTML($htm);
    $mpdf->Output();

?>
