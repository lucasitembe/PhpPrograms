<?php
	session_start();
	include("./includes/connection.php");

	if(isset($_SESSION['userinfo']['Employee_Name'])){
		$E_Name = $_SESSION['userinfo']['Employee_Name'];
	}else{
		$E_Name = '';
	}

	if(isset($_GET['start_date'])){
		$start_date = $_GET['start_date'];
	}else{
		$start_date = '';
	}

	
	if(isset($_GET['end_date'])){
		$end_date = $_GET['end_date'];
	}else{
		$end_date = '';
    }
    if(isset($_GET['ward_id'])){
		$ward_id = $_GET['ward_id'];
	}else{
		$ward_id = '';
    }
    if($start_date==""||$end_date==""){
        $filter=" AND DATE(saved_date)=CURDATE()"; 
     }else{
         $filter=" AND saved_date BETWEEN '$start_date' AND '$end_date'";
     }
    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
    }
    $Select_Ward=mysqli_query($conn,"SELECT Hospital_Ward_ID,Hospital_Ward_Name FROM tbl_hospital_ward WHERE Hospital_Ward_ID='$ward_id'") or die(mysqli_error($conn));
    while($Ward_Row=mysqli_fetch_array($Select_Ward)){
        $Hospital_Ward_Name=$Ward_Row['Hospital_Ward_Name'];
    }
    $htm = "<table width ='100%' height = '30px'>
			<tr><td><img src='./branchBanner/branchBanner.png' width=100%></td></tr>
		    <tr><td></td></tr></table>";
		
	$htm .= '<center><table width="100%">
	            <tr><td style="text-align: center;"><span style="font-size: x-small;">WARD NURSE REPORT OF '.strtoupper($Hospital_Ward_Name).'</span></td></tr>
	            <tr><td style="text-align: center;"><span style="font-size: x-small;">FROM <b>'.@date("d F Y H:i:s",strtotime($start_date)).'</b> TO <b>'.@date("d F Y H:i:s",strtotime($end_date)).'</b></span></td></tr>
	        </table></center>';


	$htm .=	'<table width="100%" border=1 style="border-collapse: collapse;" class="table table-striped">
			    <thead><tr>
                <th style="text-align:left;"><b>SN</b></th>
                <th style="text-align:left;width:250px;"><b>Nurse Report</b></th>
                <th style="text-align:left;"><b>Report Date</b></th>
                <th style="text-align:left;"><b>Reported By</b></th>
                </tr></thead>';

                $sql_select_saved_nurse_ward_report_result=mysqli_query($conn,"SELECT Employee_Name,nurse_ward_report,ward_id,saved_by,saved_date FROM tbl_nurse_ward_report nwr,tbl_employee emp WHERE  nwr.saved_by=emp.Employee_ID AND  ward_id='$ward_id' $filter") or die(mysqli_error($conn));

                if(mysqli_num_rows($sql_select_saved_nurse_ward_report_result)>0){
                    $count=1;
                    while($rows=mysqli_fetch_assoc($sql_select_saved_nurse_ward_report_result)){
                        $nurse_ward_report=$rows['nurse_ward_report'];
                        $saved_by=$rows['Employee_Name'];
                        $saved_date=$rows['saved_date'];
                        $htm.= "<tr>
                                <td>$count.</td>
                                <td>$nurse_ward_report</td>
                                <td>$saved_date</td>
                                <td>$saved_by</td>
                             </tr>";
                        $count++;
                    }
                }
	$htm .= '</table>';

	include("./MPDF/mpdf.php");
	$mpdf=new mPDF('s','A4-L', 0, '', 15,15,20,40,15,35, 'P');
    $mpdf->SetFooter('Printed By '.strtoupper($E_Name).'|Page {PAGENO} Of {nb}|{DATE d-m-Y} Powered by GPITG');
    $mpdf->WriteHTML($htm);
    $mpdf->Output();
?>