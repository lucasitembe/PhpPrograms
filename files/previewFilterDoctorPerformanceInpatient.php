<?php
    include("./includes/connection.php");
    session_start();
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Doctors_Page_Outpatient_Work']) && $_SESSION['userinfo']['Doctors_Page_Outpatient_Work'] == 'yes'){
	    
	}else if(isset($_SESSION['userinfo']['Mtuha_Reports']) && $_SESSION['userinfo']['Mtuha_Reports'] == 'yes'){
        }else{
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	    header("Location: ../index.php?InvalidPrivilege=yes");
    }
?>

<script type='text/javascript'>
    function access_Denied(){ 
   alert("Access Denied");
   document.location = "./index.php";
    }
</script>
<br/><br/>

<?php
    $Date_From=mysqli_real_escape_string($conn,$_GET['Date_From']);
    $Date_To=mysqli_real_escape_string($conn,$_GET['Date_To']);
    $Sponsor= mysqli_real_escape_string($conn,$_POST['Sponsor_ID']);

$filter="  wr.Ward_Round_Date_And_Time BETWEEN '$Date_From' AND '$Date_To' ";

$Guarantor_Name="All";

if (!empty($Sponsor) && $Sponsor != 'All') {
     $filter .="  AND pr.Sponsor_ID=$Sponsor";
     
      $rs = mysqli_query($conn,"SELECT Guarantor_Name FROM tbl_sponsor WHERE Sponsor_ID='$Sponsor'") or die(mysqli_error($conn));

    $Guarantor_Name = mysqli_fetch_assoc($rs)['Guarantor_Name'];
}
?>
 
<?php
    
    $htm = "<table width ='100%'>
		    <tr><td style='text-align:center'>
			<img src='./branchBanner/branchBanner.png'>
		    </td></tr>
		    <tr><td style='text-align: center;'><span><b>DOCTOR'S ROUND REPORT SUMMERY</b></span></td></tr>
                    <tr><td style='text-align: center;'><span><b>FROM</b>&nbsp;&nbsp;</b><b style='color:#002166;'>" . date('j F, Y H:i:s', strtotime($Date_From)) . "</b><b>&nbsp;&nbsp;TO</b>&nbsp;&nbsp; <b style='color: #002166;'>" . date('j F, Y H:i:s', strtotime($Date_To)) . "</b></td></tr>
                    <tr><td style='text-align: center;'><span><b>SPONSOR</b>&nbsp;&nbsp;</b><b style='color:#002166;'>" . $Guarantor_Name . "</b></td></tr>
                </table><br/>";
    
        $htm.="<center>";
        $htm.="<center><table width =100% border=0>";
        $htm.="<br><br>";
		 $htm.= "<thead><tr>
			    <th width=3% style='text-align:left'>SN</th>
			    <th style='text-align:left'>DOCTOR'S NAME</th>
			    <th style='text-align: right;' width=12%>PATIENTS</th>
		     </tr>
                     <tr>
                       <td colspan='3'><hr width='100%' /></td>
                     </tr> 
                    </thead>";
        //run the query to select all data from the database according to the branch id
        $select_doctor_query = "SELECT DISTINCT(emp.Employee_ID),emp.Employee_Name,emp.Employee_Type FROM tbl_employee emp INNER JOIN tbl_ward_round wr ON wr.Employee_ID=emp.Employee_ID  WHERE Employee_Type='Doctor' ORDER BY Employee_Name ASC";


        $select_doctor_result = mysqli_query($conn,$select_doctor_query) or die(mysqli_error($conn));

        $empSN = 0;
        while ($select_doctor_row = mysqli_fetch_array($select_doctor_result)) {//select doctor
            $employeeID = $select_doctor_row['Employee_ID'];
            $employeeName = $select_doctor_row['Employee_Name'];
            //$employeeNumber=$select_doctor_row['Employee_Number'];

            $result_patient_no = mysqli_query($conn,"SELECT COUNT(DISTINCT(wr.Registration_ID)) AS numberOfPatients ,wr.employee_ID FROM tbl_ward_round wr JOIN tbl_patient_registration pr ON pr.Registration_ID=wr.Registration_ID WHERE $filter AND wr.Employee_ID='$employeeID'  AND wr.Process_Status='served'") or die(mysqli_error($conn));

            if (mysqli_num_rows($result_patient_no) > 0) {

                $patient_no_number = mysqli_fetch_assoc($result_patient_no)['numberOfPatients'];

                $empSN ++;
                $htm .= "<tr><td>" . ($empSN) . "</td>";
                $htm .= "<td style='text-align:left'> ". $employeeName . "</td>";
               
                $htm .= "<td style='text-align:center'>" . number_format($patient_no_number) . "</td></tr>";
                 $htm .= "<tr>
                       <td colspan='3'><hr width='100%' /></td>
                     </tr>";
            }
        }
        
        $htm .= '</table>';

	include("MPDF/mpdf.php");
        $mpdf=new mPDF(); 
        $mpdf->WriteHTML($htm);
        $mpdf->Output();
        exit; 




?>