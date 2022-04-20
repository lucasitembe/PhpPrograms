<?php
	session_start();
	include("./includes/connection.php");
	$Number = 0;
	if(isset($_SESSION['userinfo']['Employee_ID'])){
		$Employee_ID = $_SESSION['userinfo']['Employee_ID'];
	}else{
		$Employee_ID = 0;
    }

    function checkbox($data){
        return ($data == 'yes') ? 'checked':'';
    }

	if(isset($_SESSION['userinfo']['Employee_Name'])){
		$Emp_Name = $_SESSION['userinfo']['Employee_Name'];
	}else{
		$Emp_Name = '';
	}

	if(isset($_GET['Requisition_ID'])){
		$Requisition_ID = $_GET['Requisition_ID'];
	}else{
		$Requisition_ID = 0;
	}

	$Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
		$age ='';
    }
	//get patient details
	$get_details = mysqli_query($conn,"SELECT * FROM tbl_engineering_requisition
											where requisition_ID = '$Requisition_ID'") or die(mysqli_error($conn));
                                $no = mysqli_num_rows($get_details);
                                if ($no > 0) {
                                    while ($data2 = mysqli_fetch_array($get_details)) {
                                        $Department_ID = $data2['select_dept'];
                                        $employee = $data2['employee_name'];
                                        $title = $data2['title'];
                                        $floor = $data2['floor'];
                                        $requisition_date = $data2['date_of_requisition'];
                                        $equipment_name = $data2['equipment_name'];
                                        $equipment_ID = $data2['equipment_ID'];
                                        $equipment_serial=$data2['equipment_serial'];
                                        $equipment_code=$data2['equipment_code'];
                                        $description_works_to_done = $data2['description_works_to_done'];
                                        $assigned_engineer = $data2['assigned_engineer'];
                                        $assistance_engineer = $data2['assistance_engineer'];
                                        $type_of_work = $data2['type_of_work'];
                                        $section_required = $data2['section_required'];
                                        $job_notes=$data2['job_notes'];
                                        $spare_required=$data2['spare_required'];
                                        $part_date=$data2['part_date'];
                                        $procurerer=$data2['procurerer'];
                                        $issue_date=$data2['issue_date'];
                                        $issuer=$data2['issuer'];
                                        $engineers=$data2['engineers'];
                                        $procurement_order=$data2['procurement_order'];
                                        $form_id=$data2['form_id'];
                                        $client_info=$data2['client_info'];
                                        $visual_test=$data2['visual_test'];
                                        $electrical_test=$data2['electrical_test'];
                                        $functional_test=$data2['functional_test'];
                                        $engineer_sign=$data2['engineer_sign'];
                                        $final_status=$data2['final_status'];
                                        $recommendations=$data2['recommendations'];
                                        $satisfy=$data2['satisfy'];
                                        $date_done=$data2['date_done'];
                                        $helpdesk=$data2['helpdesk'];
                                        $assigned_at=$data2['assigned_at'];
                                        $feedback=$data2['feedback'];
                                        $comments_recon=$data2['comments_recon'];
                                        
                                                }
                                        }else{
                                        $Department_ID = '';
                                        $title = '';
                                        $floor = '';
                                        $requisition_date = '';
                                        $equipment_name = '';
                                        $equipment_ID = '';
                                        $equipment_serial= '';
                                        $equipment_code= '';
                                        $description_works_to_done = '';
                                        $assigned_engineer = '';
                                        $assistance_engineer = '';
                                        $type_of_work = '';
                                        $section_required = '';
                                        $job_notes= '';
                                        $spare_required='';
                                        $part_date='';
                                        $procurerer='';
                                        $issue_date='';
                                        $issuer= '';
                                        $engineers='';
                                        $procurement_order='';
                                        $form_id='';
                                        $client_info='';
                                        $visual_test='';
                                        $electrical_test='';
                                        $functional_test='';
                                        $engineer_sign='';
                                        $final_status='';
                                        $recommendations='';
                                        $satisfy='';
                                        $date_done='';
                                        $helpdesk='';
                                        $assigned_at='';
                                        $feedback='';
                                        $comments_recon='';
                                            }
                                    $department = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Department_Name FROM tbl_department WHERE Department_ID='$Department_ID'"))['Department_Name'];

                                    $requested = mysqli_fetch_assoc(mysqli_query($conn,"Select Employee_Name from tbl_employee where Employee_ID = '$employee'"))['Employee_Name'];

                                    $jobcard = mysqli_fetch_assoc(mysqli_query($conn,"SELECT jobcard_ID FROM tbl_jobcards Where Requisition_ID='$Requisition_ID'"))['Requisition_ID'];

                                    $employee_signature = mysqli_fetch_assoc(mysqli_query($conn,"Select employee_signature from tbl_employee where Employee_ID = '$employee'"))['employee_signature'];
                                    if($employee_signature==""||$employee_signature==null){
                                        $signature="________________________";
                                    }else{
                                        $signature="<img src='../esign/employee_signatures/$employee_signature' style='height:25px'>";
                                    }
                                    $assigned_fundi = mysqli_fetch_assoc(mysqli_query($conn,"Select employee_signature from tbl_employee where Employee_Name = '$assigned_engineer'"))['employee_signature'];
                                    if($assigned_fundi==""||$assigned_fundi==null){
                                        $saini="________________________";
                                    }else{
                                        $saini="<img src='../esign/employee_signatures/$assigned_fundi' style='height:25px'>";
                                    }
                                    $msaidizi_wake = mysqli_fetch_assoc(mysqli_query($conn,"Select employee_signature from tbl_employee where Employee_ID = '$employee'"))['employee_signature'];
                                    if($msaidizi_wake==""||$msaidizi_wake==null){
                                        $msaidizi="________________________";
                                    }else{
                                        $msaidizi="<img src='../esign/employee_signatures/$msaidizi_wake' style='height:25px'>";
                                    }

                                    $helpdesk_attendant = mysqli_fetch_assoc(mysqli_query($conn,"Select Employee_Name from tbl_employee where Employee_ID = '$helpdesk'"))['Employee_Name'];

                                    $satisfication ='';
                                        if($satisfy == '5')
                                        {
                                            $satisfication = 'Excellent Service';
                                        }elseif($satisfy == '4')
                                        {
                                            $satisfication = 'Above Expectation';
                                        }elseif($satisfy == '3')
                                        {
                                                $satisfication = 'Met Expectation';
                                            }elseif($satisfy == '2')
                                        {
                                            $satisfication = 'Below Expectation';
                                        }elseif($satisfy == '1')
                                        {
                                                $satisfication = 'Poor Service';
                                        }else{
                                            $satisfication = 'Waiting Feedback From User';
                                        }



    $htm .= "<table width ='100%'>
		    <tr>
		    <td colspan='2'><center>
			<img src='./branchBanner/branchBanner.png' width=85%></center>
		    </td>
		</tr>
		<tr>
            <td style='text-align: center;'><b>BMC ENGINEERING DEPARTMENT</b></td>
            <td style='text-align: right; text-align:right;'><input type='text' style='width: 20%; background: transparent;'readonly='readonly' value='MRV ID: ".$Requisition_ID."'></td></tr>
            <tr>
            <td style='text-align: center; width:60%;'><i>Maintenance Request Voucher and Job Card</i>
            </td>
            
		</tr></table>"; // border=1 style='border-collapse: collapse;

            $htm .= "<b><span style='font-size: small; text-align: center; background: #000; color: #fff;'>&nbsp;&nbsp;<i>1. Maintenance Request Voucher&nbsp;&nbsp;&nbsp;</i></span></b>
            <table width='100%' border=1 style='border-collapse: collapse;'>
            
				<tr>
                    <td width='60%' style='text-align: center; background: #b2b7bf;' colspan='6'><span style='font-size: small; font-weight: bold;'><i>Request Information</i></span></td><
                </tr>
                <tr>
                    <td><span style='font-size: small;'><i>Date:</i></span></td>
                    <td><span style='font-size: small;'>".$requisition_date."</span></td>
                    <td><span style='font-size: small;'><i>Department:</i></span></td>
                    <td><span style='font-size: small;'>".$department."</span></td>
                    <td><span style='font-size: small;'><i>Place/Floor/Room:</i></span></td>
					<td><span style='font-size: small;'>".$floor."</span></td>
				</tr>
				<tr>
                    <td colspan='6'><span style='font-size: small;'><i>Name of Requesting Staff:</i></span> &nbsp;&nbsp;&nbsp;<b>".$requested."</b></td>
                </tr>
                </table><br/>
                <table width='100%' border=1 style='border-collapse: collapse;'>
				<tr>
                    <td colspan='3'><span style='font-size: small;'><i>Equipment Name:</i></span> &nbsp;&nbsp;&nbsp;<b>".$equipment_name."</b></td>
                    <td colspan='3'><span style='font-size: small;'><i>Equipment ID:</i></span> &nbsp;&nbsp;&nbsp;<b>".$equipment_code."</b></td>
                </tr>
                <tr>
                    <td colspan='6'><span style='font-size: small;'><i>Type of Work:</i></span> &nbsp;&nbsp;&nbsp;<b>".$type_of_work."</b></td>
                </tr>
                <tr>
                    <td><span style='font-size: small;'><i>Description Of Work:</i></span></td>
					<td colspan='5'><span style='font-size: small;'>".$description_works_to_done."</span></td>		
                </tr>
                </table><br/>
                <table width='100%' border=1 style='border-collapse: collapse;'>
				<tr>
                    <td colspan='6'><span style='font-size: small;'><i>Section Required:</i></span> &nbsp;&nbsp;&nbsp;<b>".$section_required."</b></td>
                </tr>
            </table><hr>";
            
                $htm .= "<b><span style='font-size: small; text-align: center; background: #000; color: #fff;'>&nbsp;&nbsp;<i>2. Job Assignment & Collection&nbsp;&nbsp;&nbsp;</i></span></b>
                <table width='100%' border=1 style='border-collapse: collapse;'>
					<tr>
                    <td colspan='3'><span style='font-size: small;'><i>Assigned Enginner:</i></span> &nbsp;&nbsp;&nbsp;<b>".$assigned_engineer."</b></td>
                    <td colspan='3'><span style='font-size: small;'><i>Assisting Enginner:</i></span> &nbsp;&nbsp;&nbsp;<b>".$assistance_engineer."</b></td>
                    </tr></table><hr>";
                    
                    $htm .= "<b><span style='font-size: small; text-align: center; background: #000; color: #fff;'>&nbsp;&nbsp;<i>3. Maintenance & Procurement&nbsp;&nbsp;&nbsp;</i></span></b>
                <table width='100%' border=1 style='border-collapse: collapse;'>
                <tr>
                    <td width='60%' style='text-align: center; background: #b2b7bf;' colspan='6'><span style='font-size: small; font-weight: bold;'><i>Job Notes</i></span></td><
                </tr>
                <tr>
                    <td colspan='6'>".$job_notes."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                </tr>
                <tr>
                    <td width='60%' style='text-align: center; background: #b2b7bf;' colspan='6'><span style='font-size: small; font-weight: bold;'><i>Spare Parts and Procurement Tracker</i></span></td><
                </tr>";
                    if($spare_required == 'yes'){
                        $spare_required = "checked='checked'";
                    }
                    if($procurement_order == 'yes'){
                        $procurement_order = "checked='checked'";
                    }
                    if($client_info == 'yes'){
                        $client_info = "checked='checked'";
                    }
                $htm .="<tr>
                    <td colspan='2' style='text-align: center;'><input type='checkbox' $spare_required >Spare Required</td>
                    <td colspan='2' style='text-align: center;'><input type='checkbox' $procurement_order >Procurement Order Made</td>
                    <td colspan='2' style='text-align: center;'><input type='checkbox' $client_info >Client Informed</td>
                </tr>
                <tr><td colspan='2'></td>
                        <td colspan='2' style='text-align: center;'><span style='font-size: small;'><i>FORM ID:</i></span> &nbsp;&nbsp;&nbsp;<b>".$jobcard."</b></td>
                        <td colspan='2'></td>
                    </tr>";
                
                
                $htm .="<tr>
                    <td colspan='3'><span style='font-size: small;'><i>Date Requested:</i></span> &nbsp;&nbsp;&nbsp;<b>".$part_date."</b></td>
                    <td colspan='3'><span style='font-size: small;'><i>Requested Staff:</i></span> &nbsp;&nbsp;&nbsp;<b>".$assigned_engineer."</b></td>
                    </tr>
                    <tr>
                    <td width='100%' style='text-align: center; background: #b2b7bf;' colspan='6'><span style='font-size: small; font-weight: bold;'><i>Safety Testing</i></span></td>
                </tr>";
                if($visual_test == 'yes'){
                    $visual_test = "checked='checked'";
                }
                if($electrical_test == 'yes'){
                    $electrical_test = "checked='checked'";
                }
                if($functional_test == 'yes'){
                    $functional_test = "checked='checked'";
                }
                $htm .="<tr>
                    <td colspan='2' style='text-align: center;'><input type='checkbox' $visual_test >Physical Inspection Completed</td>
                    <td colspan='2' style='text-align: center;'><input type='checkbox' $electrical_test >Electrical Safety Test Completed</td>
                    <td colspan='2' style='text-align: center;'><input type='checkbox' $functional_test >Funtional Test Completed</td>
                </tr>
                <tr>
                    <td colspan='6'><span style='font-size: small;'><b><i>Recommendations:</i></b></span> &nbsp;&nbsp;&nbsp;".$comments_recon."</td>
                </tr>
                    </table><br/>";
		

                $htm .= "
                <b><span style='font-size: small; text-align: center; background: #000; color: #fff;'>&nbsp;&nbsp;<i>4. Final Status & Job Closure&nbsp;&nbsp;&nbsp;</i></span></b>
                <table colspan='6' width='100%' border=1 style='border-collapse: collapse;'>
                <tr>
                    <td width='100%' style='text-align: center; background: #b2b7bf;' colspan='6'><span style='font-size: small; font-weight: bold;'><i>Final Job Status</i></span></td>
                </tr>
                <tr>
                <td colspan='6'><span style='font-size: small;'><b><i>Job Status:</i></b></span> &nbsp;&nbsp;&nbsp;".$final_status."</td>
                </tr>
                
                <tr>
                    <td colspan='6'><span style='font-size: small;'><b><i>Recommendations:</i></b></span> &nbsp;&nbsp;&nbsp;".$recommendations."</td>
                    </tr>";
				
				$htm .= "</table><br/>";

			//MANAGEMENT RECOMMENDATION
			$htm .= "<table colspan='6' width='100%' border=1 style='border-collapse: collapse;'>
            <tr>
                <td width='100%' style='text-align: center; background: #b2b7bf;' colspan='6'><span style='font-size: small; font-weight: bold;'><i>Requesting Staff Feedback</i></span></td>
            </tr>
            <tr>
                <td colspan='6'><span style='font-size: small;'><b><i>User Feedback:</i></b></span> &nbsp;&nbsp;&nbsp;".$feedback."</td></tr>
                <tr>
                <td colspan='6'><span style='font-size: small;'><b><i>Satisfaction Score:</i></b></span> &nbsp;&nbsp;&nbsp;".$satisfication."</td></tr></table><br/>";

            $htm .= "<table colspan='6' width='100%' border=1 style='border-collapse: collapse;'>
            <tr>
                <td width='100%' style='text-align: center; background: #b2b7bf;' colspan='6'><span style='font-size: small; font-weight: bold;'><i>Job Handover and Registration</i></span></td>
            </tr>
            <tr>
                <td colspan='3'><span style='font-size: small;'><b><i>Lead Engineer Signature:</i></b></span> &nbsp;&nbsp;&nbsp;".$saini."</td>
                <td colspan='3'><span style='font-size: small;'><b><i>Date:</i></b></span> &nbsp;&nbsp;&nbsp;".$date_done."</td>
            </tr>
            <tr>
                <td colspan='3'><span style='font-size: small;'><b><i>Requesting Staff Signature:</i></b></span> &nbsp;&nbsp;&nbsp;".$signature."</td>
                <td colspan='3'><span style='font-size: small;'><b><i>Date:</i></b></span> &nbsp;&nbsp;&nbsp;".$requisition_date."</td>
            </tr>
            <tr>
                <td colspan='3'><span style='font-size: small;'><b><i>Help Desk (Job Logged & ID Assigned):</i></b></span> &nbsp;&nbsp;&nbsp;".$helpdesk_attendant."</td>
                <td colspan='3'><span style='font-size: small;'><b><i>Date:</i></b></span> &nbsp;&nbsp;&nbsp;".$assigned_at."</td>
            </tr>
                </table>";


    include("./MPDF/mpdf.php");
    $mpdf=new mPDF('utf-8','A4', 0, '', 15,15,5,15,15,10, 'P');
    $mpdf->SetFooter('Printed By '.strtoupper($Emp_Name).'|Page {PAGENO} of {nb}|{DATE d-m-Y}');
    $mpdf->WriteHTML($htm);
    $mpdf->Output();
?>
