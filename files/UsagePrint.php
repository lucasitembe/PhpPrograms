<?php
	include("./includes/connection.php");
	$temp = 1;
 
 
    if(isset($_GET['Blood_Group'])){
        $Blood_Group = $_GET['Blood_Group'];   
    }else{
        $Blood_Group = ''; 
		
    } 

	if(isset($_GET['Blood_Status'])){
        $Blood_Status = $_GET['Blood_Status'];   
    }else{
        $Blood_Status = '';
    }

   $htm = '<center><table width =100% border=1 style="border-collapse:collapse;">';
	
    $htm .= '<tr><td width=5%><span style="font-size: small;"><b>SN</b></td>';
	if($Blood_Status == 'USED'){
		$htm .= '<td width=40%><span style="font-size: small;"><b>PATIENT NAME</b></td>';
	}
	$htm .= '<td width=15%><span style="font-size: small;"><b>BLOOD BATCH</b></td>
	       <td width=15%><span style="font-size: small;"><b>BLOOD GROUP</b></td>
	       <td width=25%><span style="font-size: small;"><b>BLOOD VOLUME(ml)</b></td>
		   <td width=25%><span style="font-size: small;"><b>BLOOD ID</b></td>
           <td width=25%><span style="font-size: small;"><b>DATE ISSUED</b></td>
                                
								</tr>';
								
if($Blood_Group != ''){						
    $select_Filtered_Patient = mysqli_query($conn,
            "Select Blood_Checked_ID,Blood_Group,Blood_Batch,BloodRecorded,Blood_Status,Blood_ID,Patient_Given,Reason,
			Date_Taken,Registered_Date_And_Time from tbl_blood_checked where Blood_Group = '$Blood_Group'and 
			Blood_Status='$Blood_Status' order by BloodRecorded desc ") or die(mysqli_error($conn));
}else{
	$select_Filtered_Patient = mysqli_query($conn,
			"Select Blood_Checked_ID,Blood_Group,Blood_Batch,BloodRecorded,Blood_Status,Blood_ID,Patient_Given,Reason,
			Date_Taken,Registered_Date_And_Time from tbl_blood_checked where 
			Blood_Status='$Blood_Status' order by BloodRecorded desc ") or die(mysqli_error($conn));
}
		    
    while($row = mysqli_fetch_array($select_Filtered_Patient)){
	$htm .= "<tr><td ><span style='font-size: small;'>".$temp."</td>";
	    if($Blood_Status == 'USED'){
			$htm .= "<td style='text-align: left;'><span style='font-size: xx-small;'>".$row['Patient_Given']."</td>";
		}
		$htm .= "<td style='text-align: left;'><span style='font-size: xx-small;'>".$row['Blood_Batch']."</td>";
        $htm .= "<td style='text-align: center;'><span style='font-size: xx-small;'>".$row['Blood_Group']."</td>";
        $htm .= "<td style='text-align: center;'><span style='font-size: xx-small;'>".$row['BloodRecorded']."</td>";
		$htm .= "<td style='text-align: center;'><span style='font-size: xx-small;'>".$row['Blood_ID']."</td>";
		 $htm .= "<td style='text-align: center;'><span style='font-size: xx-small;'>".$row['Date_Taken']."</td>";
        
       $temp++; 
    }   $htm .= "</tr>";
	$htm .= "</table></center>";
	
	
	include("MPDF/mpdf.php");
	//echo $htm; exit;
    $mpdf = new mPDF(); 
	 $mpdf->SetFooter('|{PAGENO}|{DATE d-m-Y}');
    $mpdf->WriteHTML($htm);
    $mpdf->Output();
    exit;
?>

