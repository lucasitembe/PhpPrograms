<?php
    include("./includes/connection.php");
	$temp = 1;
    if(isset($_GET['Date_Choosen'])){
        $Date_Choosen = $_GET['Date_Choosen'];   
    }else{
        $Date_Choosen = '';
    }
	
	
	if(isset($_GET['Blood_Group'])){
        $Blood_Group = $_GET['Blood_Group'];   
    }else{
        $Blood_Group = '';
    }
	/* if(isset($_GET['Blood_Group']) && isset($_GET['Date_Choosen'])){
	$Blood_Group = $_GET['Blood_Group'];   
	$Date_Choosen = $_GET['Date_Choosen'];
	
	}else{
	 $Blood_Group = '';
	$Date_Choosen = '';
	
	
	} */
	
	
    $htm = '<center><table width =100% border=1 style="border-collapse:collapse;" >';
	
    $htm .=  '<tr><td width=5%><span style="font-size: x-small;"><b>SN</b></td>
	       <td style="text-align:center;width:15%;"><span style="font-size: x-small;"><b>BLOOD BATCH</b></td>
		   <td style="text-align:center;width:15%;"><span style="font-size: x-small;"><b>BLOOD GROUP</b></td>
		   <td style="text-align:center;width:15%;"><span style="font-size: x-small;"><b>BLOOD VOLUME(ml)</b></td>
		   <td style="text-align:center;width:15%;"><span style="font-size: x-small;"><b>BLOOD ID</b></td>
		   <td style="text-align:center;width:30%;"><span style="font-size: x-small;"><b>EXPIRED DATE</b></td>
              
                                
								</tr>';
    $select_Filtered_Donors = mysqli_query($conn,
            "Select Blood_Group,Blood_Batch,Blood_Volume,Blood_ID, Blood_Expire_Date,Transfusion_Date_Time 
			from 
			tbl_patient_blood_data where Blood_Volume>0 and Blood_Group like '%$Blood_Group%' && Blood_Expire_Date like '%$Date_Choosen%' order by Blood_Volume desc") or die(mysqli_error($conn));

		    
    while($row = mysqli_fetch_array($select_Filtered_Donors)){
	$htm .=  "<tr><td style='text-align: left;'><span style='font-size: small;'>".$temp."</a></td>";
        $htm .=  "<td style='text-align: left;'><span style='font-size: xx-small;'>".$row['Blood_Batch']."</a></td>";
        $htm .=  "<td style='text-align: center;'><span style='font-size: xx-small;'>".$row['Blood_Group']."</a></td>";
		$htm .=  "<td style='text-align: center;'><span style='font-size: xx-small;'>".$row['Blood_Volume']."</a></td>";
		$htm .=  "<td style='text-align: center;'><span style='font-size: xx-small;'>".$row['Blood_ID']."</a></td>";
		$htm .=  "<td style='text-align: center;'><span style='font-size: xx-small;'>".$row['Blood_Expire_Date']."</a></td>";
        
       $temp++; 
    }   $htm .=  "</tr>";
	$htm .= "</table></center>";
	
	include("MPDF/mpdf.php");
	
    $mpdf=new mPDF(); 
	 $mpdf->SetFooter('|{PAGENO}|{DATE d-m-Y}');
    $mpdf->WriteHTML($htm);
    $mpdf->Output();
    exit;

?>