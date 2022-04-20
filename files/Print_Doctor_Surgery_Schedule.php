<?php
session_start();
    include("includes/connection.php");
    $Employee = $_SESSION['userinfo']['Employee_Name'];

    $Patient_Name = $_GET['Patient_Name'];   
    $Patient_Number = $_GET['Patient_Number'];
    $Sponsor_ID = $_GET['Sponsor_ID'];
    $Employee_ID = $_GET['Employee_ID'];
    $date_From = $_GET['date_From'];
    $date_To = $_GET['date_To'];
    $number = $_GET['number'];
    $filter = '';

			
	//today function
	$Today_Date = mysqli_query($conn,"SELECT now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
	// $age ='';
    }
	//end

if(!empty($Patient_Number)){
	$filter .= " AND pc.Registration_ID = '$Patient_Number'";
}

if(!empty($Patient_Name)){
	$filter .= " AND pr.Patient_Name LIKE '%$Patient_Name%'";
}

if(($Sponsor_ID) != 'All' && !empty($Sponsor_ID)){
	$filter .= " AND pc.Sponsor_ID =  '$Sponsor_ID'";
    $Sponsor_Name = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Guarantor_Name from tbl_sponsor WHERE Sponsor_ID = '$Sponsor_ID'"))['Guarantor_Name'];
}else{
    $Sponsor_Name = 'All';
}

if(($Employee_ID) != 'All' && !empty($Employee_ID)){
	$filter .= " AND ilc.Consultant_ID =  '$Employee_ID'";
    $Doactor = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Employee_Name FROM tbl_employee WHERE Employee_ID = '$Employee_ID'"))['Employee_Name'];
}else{
    $Doactor ='All';
}


if(!empty($date_From) && !empty($date_To)){
	$filter .= " AND ilc.Service_Date_And_Time BETWEEN '$date_From' AND '$date_To'";
}

if(isset($number) && !empty($number)){
    if($number !='All'){
        $filter .= " ORDER BY ilc.Payment_Item_Cache_List_ID DESC LIMIT $number";
    }else{
        $filter .= " ORDER BY ilc.Payment_Item_Cache_List_ID DESC";
    }
}else{
    $filter .= " ORDER BY ilc.Payment_Item_Cache_List_ID DESC LIMIT 100";
}


        $disp= "<table width ='100%' height = '30px'>
		<tr>
		    <td>
			<img src='./branchBanner/branchBanner.png' width=100%>
		    </td>
		</tr>
		<tr>
		   <td style='text-align: center;'><b>DOCTOR'S  SURGERY SCHEDULE</b></td>
		</tr>
                <tr>
		   <td style='text-align: center;'>FROM: <b>".$date_From."</b> - TO: <b>".$date_To."</b></td>
		</tr>
                
                <tr>
		   <td style='text-align: center;'><b>SPONSOR: ".ucwords($Sponsor_Name)."</b></td>
		</tr>
                
                <tr>
		   <td style='text-align: center;'><b>DOCTOR: ".ucwords($Doactor)."</b></td>
        </tr>

                <tr>
                    <td style='text-align: center;'><hr></td>
                </tr>
            </table>";
    $disp.= '<center><table border="1" id="doctorperformancereportsummarised" class="display" style="width:100%;border-collapse: collapse;">';
    $disp.= "<thead><tr id='thead'><td style='width:3%; text-align: center;'><b>SN</b></td>
    <td style='width:12%; text-align: center;'><b>DOCTOR&#39;S NAME</b></td>
    <td style='width:12%; text-align: center;'><b>PATIENT NAME</b></td>
    <td style='width:6%; text-align: center;'><b>PATIENT #</b></td>
    <td style='text-align: center;width:12%;'><b>AGE</b></td>
    <td style='text-align: center; width: 5%;'><b>GENDER</b></td>
    <td style='width:7%;text-align: center;'><b>PHONE NUMBER</b></td>
    <td style='width:15%;text-align: center;'><b>SURGERY NAME</b></td>
    <td style='width:9%;text-align: center;'><b>SURGERY DATE</b></td>
    <td style='text-align: center;' style='width:12%'><b>LOCATION</b></td>
    <td style='text-align: center;width:9%;'><b>OPERATION ROOM</b></td> 
    <td style='text-align: center;width:9%;'><b>CONSENT SIGNED</b></td> 
		     </tr></thead>";

$temp =1;   
                
             $select_Filtered_Donors = mysqli_query($conn, "SELECT pc.Registration_ID, pr.Patient_Name, ilc.Transaction_Date_And_Time, 
             pr.Date_Of_Birth, pr.Gender, pc.consultation_ID, pr.Phone_Number, em.Employee_Name, ilc.Consultant_ID, i.Product_Name,ilc.theater_room_id, 
             ilc.Service_Date_And_Time, ilc.Payment_Item_Cache_List_ID, ilc.Sub_Department_ID FROM tbl_item_list_cache ilc, tbl_payment_cache pc, 
             tbl_patient_registration pr, tbl_items i, tbl_employee em WHERE pr.Registration_ID = pc.Registration_ID AND 
             pc.Payment_Cache_ID = ilc.Payment_Cache_ID AND ilc.Check_In_Type = 'Surgery' AND ilc.Status IN ('active','paid') AND 
             i.Item_ID = ilc.Item_ID AND em.Employee_ID = ilc.Consultant_ID $filter") or die(mysqli_error($conn));

		    
             while($row = mysqli_fetch_array($select_Filtered_Donors)){
                 
                $date1 = new DateTime($Today);
                $date2 = new DateTime($row['Date_Of_Birth']);
                $diff = $date1 -> diff($date2);
                $age = $diff->y." Years, ";
                $age .= $diff->m." Months, ";
                $age .= $diff->d." Days";
            
                $Registration_ID = $row['Registration_ID'];
                $theater_room_id = $row['theater_room_id'];

                $theater_room_name = mysqli_fetch_assoc(mysqli_query($conn, "SELECT theater_room_name FROM tbl_theater_rooms WHERE theater_room_id='$theater_room_id'"))['theater_room_name'];
                if (empty($theater_room_name) || $theater_room_name == "" || $theater_room_name == null) {
                    $theater_room_name = "<b style='color: red;'>Not Assigned</b>";
                }
                //check if is available
                $consultation_id = $row['consultation_ID'];
                $Payment_Item_Cache_List_ID = $row['Payment_Item_Cache_List_ID'];
                $Service_Date_And_Time = $row['Service_Date_And_Time'];
                $Consultant_ID = $row['Consultant_ID'];
                $Sub_Department_ID = $row['Sub_Department_ID'];
        
                $Doctors_name = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Employee_Name FROM tbl_employee WHERE Employee_ID = '$Consultant_ID'"))['Employee_Name'];
                $Sub_Department_Name = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Sub_Department_Name FROM tbl_sub_department WHERE Sub_Department_ID = '$Sub_Department_ID'"))['Sub_Department_Name'];
        
                // die("SELECT Admision_ID FROM tbl_admission WHERE Registration_ID = '$Registration_ID' AND Admision_Status = 'Admitted' AND Bed_Name <> '' ORDER BY Admision_ID DESC LIMIT 1");
                $Admision_ID = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Admision_ID FROM tbl_admission WHERE Registration_ID = '$Registration_ID' AND Admission_Status = 'Admitted' AND Bed_Name <> '' ORDER BY Admision_ID DESC LIMIT 1 "))['Admision_ID'];
        
        
                $check = mysqli_query($conn,"SELECT `date`, consent_amputation from tbl_consert_forms_details where Registration_ID = '$Registration_ID' AND Payment_Item_Cache_List_ID='$Payment_Item_Cache_List_ID'") or die(mysqli_error($conn));
                $num = mysqli_num_rows($check);
        
        
                while($data = mysqli_fetch_assoc($check)){
                    $consent_amputation = $data['consent_amputation'];
                    $Operative_Date_Time = $data['date'];
                }
                if($Service_Date_And_Time == '0000-00-00 00:00:00'){
                    $Service_Date_And_Time = "<span style='font-weight: bold;'>DATE NOT SELECTED</span>";
                }
                if($num > 0){
                                $disp .="<tr style=' color: green;'><td id='thead'>".$temp."</td>";
                                $disp .="<td>".ucwords(strtolower($Doctors_name))."</td>";
                                $disp .="<td>".ucwords(strtolower($row['Patient_Name']))."</td>";
                                $disp .="<td>".$row['Registration_ID']."</td>";
                                $disp .="<td>".$age ."</td>";
                                $disp .="<td>".$row['Gender']."</td>";
                                $disp .="<td>".$row['Phone_Number']."</td>";
                                $disp .="<td>".$row['Product_Name']."</td>";
                                $disp .="<td>".$Service_Date_And_Time."</td>";				
                                $disp .="<td>".$Sub_Department_Name."</td>";
                                $disp .= "<td>" . $theater_room_name . "</td>";
                                $disp .="<td>".$Operative_Date_Time."</td>";
                            
                        $temp++; 
                        $disp .="</tr>";
                }else{
                            $disp .="<tr><td id='thead'>".$temp."</td>";
                            $disp .="<td>".ucwords(strtolower($Doctors_name))."</td>";
                            $disp .="<td>".ucwords(strtolower($row['Patient_Name']))."</td>";
                            $disp .="<td>".$row['Registration_ID']."</td>";
                            $disp .="<td>".$age ."</td>";
                            $disp .="<td>".$row['Gender']."</td>";
                            $disp .="<td>".$row['Phone_Number']."</td>";
                            $disp .="<td>".$row['Product_Name']."</td>";
                            $disp .="<td>".$Service_Date_And_Time."</td>";					
                            $disp .="<td>".$Sub_Department_Name."</td>";
                            $disp .= "<td>" . $theater_room_name . "</td>";
                            $disp .="<td style='font-weight: bold;'>NO CONSENT SIGNED</td>";
                        
                    $temp++; 
                    $disp .="</tr>";
                }
         
}
$disp.= "</table>";



    include("MPDF/mpdf.php");
    $mpdf = new mPDF('c', 'Letter-L');
    $mpdf->SetFooter('{PAGENO}/{nb}|  Printed By '.$Employee.'                   {DATE d-m-Y H:m:s}');
    $mpdf->WriteHTML($disp);
    $mpdf->Output();
    exit;
