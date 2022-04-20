<link rel="stylesheet" href="table.css" media="screen">
<style>
    table,tr,td{
        border-collapse:collapse !important;
        border:none !important;
        padding: 8px;
        font-size: 13px;

    }
    .idadi:hover{
        background: #eee;
        cursor: pointer;
        width: 100% important;
    }
    .row tr:nth-child(even){
        background-color: #eee;
    }
    .row tr:nth-child(odd){
        background-color: #fff;
        
    }
    .row tr:hover{
        background-color: #ccc;
        
    }
</style>

<?php
include("./includes/connection.php");
$temp = 1;

$Date_From = $_GET['Date_From'];
$Date_To = $_GET['Date_To'];
$Patient_Name = $_GET['Patient_Name'];
$Patient_Number = $_GET['Patient_Number'];

$filter = '';



//today function
$Today_Date = mysqli_query($conn, "SELECT now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
    // $age ='';
}
//end

if (!empty($Patient_Number)) {
    $filter .= " AND bt.Registration_ID = '$Patient_Number'";
}

if (!empty($Patient_Name)) {
    $filter .= " AND pr.Patient_Name LIKE '%$Patient_Name%'";
}

if(!empty($Date_From) && !empty($Date_To)){
    $filter .= " AND bt.Saved_date BETWEEN '$Date_From' AND '$Date_To'";
}else{
    $sql_date_time = mysqli_query($conn,"select now() as Date_Time ") or die(mysqli_error($conn));
    while($date = mysqli_fetch_array($sql_date_time)){
        $Current_Date_Time = $date['Date_Time'];
    }
    $Filter_Value = substr($Current_Date_Time,0,11);
    $Date_From = $Filter_Value.' 00:00';
    $Date_To = $Current_Date_Time;

    $filter .= " AND bt.Saved_date BETWEEN '$Date_From' AND '$Date_To'";

}

$display_table ='<table width =100% border=0 class="display">';

$display_table .='<thead>
        <tr>
            <td style="width:2%; text-align: center;"><b>SN</b></td>
            <td style="width:10%; text-align: center;"><b>PATIENT NAME</b></td>
            <td style="width:4%; text-align: center;"><b>PATIENT #</b></td>
            <td style="width:8%; text-align: center;"><b>GENDER</b></td>
            <td style="text-align: center;width:8%;"><b>AGE</b></td>
            <td style="width: 8%;"><b>REQUESTED BY</b></td>                   
            <td style="text-align: center; width: 6%;"><b>DATE OF REQUEST</b></td>                  
            <td style="text-align: center; width: 3%;"><b>PRIORITY</b></td>                  
	 </tr>
     </thead>';

             
             
     $display_table.="</tr>";
				
		
     $query_data=mysqli_query($conn,"SELECT bt.Consent_ID, bt.Blood_Transfusion_ID, bt.Registration_ID, pr.Patient_Name, pr.Gender, bt.Saved_date, bt.Priority, pr.Date_Of_Birth, em.Employee_Name  FROM tbl_blood_transfusion_requests bt, tbl_employee em, tbl_patient_registration pr WHERE em.Employee_ID = bt.Employee_ID AND pr.Registration_ID = bt.Registration_ID AND bt.Process_Status <> 'processed' $filter ORDER BY bt.Blood_Transfusion_ID ASC ");
     
     $num=0;
     
        while($result_query=mysqli_fetch_array($query_data)){
                $Consent_ID=$result_query['Consent_ID'];
                $Blood_Transfusion_ID=$result_query['Blood_Transfusion_ID'];
                $Registration_ID=$result_query['Registration_ID'];
                $Patient_Name=$result_query['Patient_Name'];
                $Gender=$result_query['Gender'];
                $Saved_date=$result_query['Saved_date'];
                $Priority=$result_query['Priority'];
                $Date_Of_Birth=$result_query['Date_Of_Birth'];
                $Employee_Name=$result_query['Employee_Name'];

                $date1 = new DateTime($Today);
                $date2 = new DateTime($Date_Of_Birth);
                $diff = $date1->diff($date2);
                $age = $diff->y . " Years, " . $diff->m . " Months, " . $diff->d . " Days, " . $diff->h . " Hours";
                $num++;
            if($Priority == 'Urgent'){
                $display_table.="   
                <tr> 
                <td><center>".$num."</a></center></td>
                <td><a href='blood_transfusion_processing.php?Registration_ID=$Registration_ID&BTID=$Blood_Transfusion_ID' style='text-decoration: none;color: red; font-weight: bold;' target='_parent' title='Click To Process Blood Request Form'>".$Patient_Name."</a></td>
                <td style='text-align: center;'><a href='blood_transfusion_processing.php?Registration_ID=$Registration_ID&BTID=$Blood_Transfusion_ID' style='text-decoration: none;color: red; font-weight: bold;' target='_parent' title='Click To Process Blood Request Form'>".$Registration_ID."</a></td>
                <td style='text-align: center;'><a href='blood_transfusion_processing.php?Registration_ID=$Registration_ID&BTID=$Blood_Transfusion_ID' style='text-decoration: none;color: red; font-weight: bold;' target='_parent' title='Click To Process Blood Request Form'>".$Gender."</a></td>
                <td><a href='blood_transfusion_processing.php?Registration_ID=$Registration_ID&BTID=$Blood_Transfusion_ID' style='text-decoration: none;color: red; font-weight: bold;' target='_parent' title='Click To Process Blood Request Form'>".$age."</a></td>
                <td><a href='blood_transfusion_processing.php?Registration_ID=$Registration_ID&BTID=$Blood_Transfusion_ID' style='text-decoration: none;color: red; font-weight: bold;' target='_parent' title='Click To Process Blood Request Form'>".$Employee_Name."</a></td>
                <td style='text-align: center;'><a href='blood_transfusion_processing.php?Registration_ID=$Registration_ID&BTID=$Blood_Transfusion_ID' style='text-decoration: none;color: red; font-weight: bold;' target='_parent' title='Click To Process Blood Request Form'>".$Saved_date."</a></td>
                <td style='text-align: center;'><a href='blood_transfusion_processing.php?Registration_ID=$Registration_ID&BTID=$Blood_Transfusion_ID' style='text-decoration: none;color: red; font-weight: bold;' target='_parent' title='Click To Process Blood Request Form'>".$Priority."</a></td>
                </tr>";
            }else{
                $display_table.="   
                <tr> 
                <td><center>".$num."</a></center></td>
                <td><a href='blood_transfusion_processing.php?Registration_ID=$Registration_ID&BTID=$Blood_Transfusion_ID' style='text-decoration: none;' target='_parent' title='Click To Process Blood Request Form'>".$Patient_Name."</a></td>
                <td style='text-align: center;'><a href='blood_transfusion_processing.php?Registration_ID=$Registration_ID&BTID=$Blood_Transfusion_ID' style='text-decoration: none;' target='_parent' title='Click To Process Blood Request Form'>".$Registration_ID."</a></td>
                <td style='text-align: center;'><a href='blood_transfusion_processing.php?Registration_ID=$Registration_ID&BTID=$Blood_Transfusion_ID' style='text-decoration: none;' target='_parent' title='Click To Process Blood Request Form'>".$Gender."</a></td>
                <td><a href='blood_transfusion_processing.php?Registration_ID=$Registration_ID&BTID=$Blood_Transfusion_ID' style='text-decoration: none;' target='_parent' title='Click To Process Blood Request Form'>".$age."</a></td>
                <td><a href='blood_transfusion_processing.php?Registration_ID=$Registration_ID&BTID=$Blood_Transfusion_ID' style='text-decoration: none;' target='_parent' title='Click To Process Blood Request Form'>".$Employee_Name."</a></td>
                <td style='text-align: center;'><a href='blood_transfusion_processing.php?Registration_ID=$Registration_ID&BTID=$Blood_Transfusion_ID' style='text-decoration: none;' target='_parent' title='Click To Process Blood Request Form'>".$Saved_date."</a></td>
                <td style='text-align: center;'><a href='blood_transfusion_processing.php?Registration_ID=$Registration_ID&BTID=$Blood_Transfusion_ID' style='text-decoration: none;' target='_parent' title='Click To Process Blood Request Form'>".$Priority."</a></td>
                </tr>";
            }

        }
     
     $display_table.="</table>";
     
     echo $display_table;
?>
</table>


