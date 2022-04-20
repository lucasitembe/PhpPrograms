<?php
    session_start();
    include("./includes/connection.php");
	
	$Grn_Status = '';
	$Grn_Issue_Note_ID = '';
	$Insert_Status = 'false';

	//get sub department id & name
	if(isset($_SESSION['Storage_Info'])){
		$Sub_Department_ID = $_SESSION['Storage_Info']['Sub_Department_ID'];
		$Sub_Department_Name = $_SESSION['Storage_Info']['Sub_Department_Name'];
	}else{
		$Sub_Department_Name = $_SESSION['Storage_Info']['Sub_Department_Name'];
		$Sub_Department_ID = $_SESSION['Storage_Info']['Sub_Department_ID'];
	}
        
        
$canPakage = false;
$display = "style='display:none'";

if (isset($_SESSION['systeminfo']['enable_receive_by_package']) && $_SESSION['systeminfo']['enable_receive_by_package'] == 'yes') {
$canPakage = true;
$display = "";
}
?>


<?php
	//get all important Requisition details
	if(isset($_GET['Issue_ID'])){
		$Issue_ID = $_GET['Issue_ID'];
	}else{
		$Issue_ID = 0;
	}
	
	$select = mysqli_query($conn,"select rq.Requisition_ID, rq.Sent_Date_Time, rq.Requisition_Status, rq.Requisition_Description, emp.Employee_Name, rq.Employee_ID, rq.Store_Need, rq.Store_Issue
							from tbl_requisition rq, tbl_requisition_items ri, tbl_employee emp where
							rq.Requisition_ID = ri.Requisition_ID and
							emp.Employee_ID = rq.Employee_ID and
							Issue_ID = '$Issue_ID' group by Issue_ID") or die(mysqli_error($conn));
	$no = mysqli_num_rows($select);
	
	if($no > 0){
		while($data = mysqli_fetch_array($select)){
			$Requisition_ID = $data['Requisition_ID'];
			$Sent_Date_Time = $data['Sent_Date_Time'];
			$Requisition_Description = $data['Requisition_Description'];
			$Employee_Name = $data['Employee_Name'];
			$Store_Need = $data['Store_Need'];
			$Store_Issue = $data['Store_Issue'];
			$Requisition_Status = $data['Requisition_Status'];
			$Temp_Employee_ID = $data['Employee_ID']; //employee id (prepare the requisition)
		}
	}else{
		$Requisition_ID = 0;
		$Sent_Date_Time = '';
		$Requisition_Description = '';
		$Employee_Name = '';
		$Store_Need = 0;
		$Store_Issue = 0;
		$Requisition_Status = '';
		$Temp_Employee_ID = 0; //employee id (prepare the requisition)
	}
	
	//get sub department names and employee prepare selected requisition
	if($Requisition_ID != 0){
		//get store need
		$select = mysqli_query($conn,"select Sub_Department_Name from tbl_sub_department where Sub_Department_ID = '$Store_Need'") or die(mysqli_error($conn));
		$num = mysqli_num_rows($select);
		if($num > 0){
			while($row = mysqli_fetch_array($select)){
				$Temp_Store_Need = $row['Sub_Department_Name'];
			}
		}else{
			$Temp_Store_Need = '';
		}
		
		
		//get store issue
		$select = mysqli_query($conn,"select Sub_Department_Name from tbl_sub_department where Sub_Department_ID = '$Store_Issue'") or die(mysqli_error($conn));
		$num = mysqli_num_rows($select);
		if($num > 0){
			while($row = mysqli_fetch_array($select)){
				$Temp_Store_Issue = $row['Sub_Department_Name'];
			}
		}else{
			$Temp_Store_Issue = '';
		}
		
		
		//get employee prepare 
		$select = mysqli_query($conn,"select Employee_Name from tbl_employee where Employee_ID = '$Temp_Employee_ID'") or die(mysqli_error($conn));
		$num = mysqli_num_rows($select);
		if($num > 0){
			while($row = mysqli_fetch_array($select)){
				$Temp_Employee_Name = $row['Employee_Name'];
			}
		}else{
			$Temp_Employee_Name = '';
		}
		
		
	}else{
		$Temp_Store_Issue = '';
		$Temp_Store_Need = '';
		$Temp_Employee_Name = '';
	}
?>

<?php
	//get Issue ID then will help to search issue details
	if(isset($_GET['Issue_ID'])){
		$Issue_ID = $_GET['Issue_ID'];
	}else{
		$Issue_ID = 0;
	}
	
	$select_grn_details = mysqli_query($conn,"select * from tbl_grn_issue_note  g join tbl_employee e ON g.Employee_ID=e.Employee_ID 
						where Issue_ID = '$Issue_ID'") or die(mysqli_error($conn));
	$nop = mysqli_num_rows($select_grn_details);
	$Control = 'False';
	if($nop > 0){
		$Control = 'True';
		while($Grn_row = mysqli_fetch_array($select_grn_details)){
			$Created_Date_Time = $Grn_row['Created_Date_Time'];
			$Grn_Issue_Note_ID = $Grn_row['Grn_Issue_Note_ID'];
			$Issue_Description = $Grn_row['Issue_Description'];
                        $Receiver = $Grn_row['Employee_Name'];
		}
	}else{
		$Control = 'False';
		$Grn_Issue_Note_ID = 'New';
		$Created_Date_Time = '';
		$Issue_Description = '';
                $Receiver='';
	}

		$htm = "<table  width ='100%' border='0'  class='nobordertable' >
			<tr>
			    <td>
				<img src='./branchBanner/branchBanner.png' width=100%>
			    </td>
			</tr>
	        <tr><td>&nbsp;</td></tr>
			<tr>
			    <td style='text-align: center;'><b>GOOD RECEIVED NOTE (AGAINST ISSUE NOTE)</b></td>
			</tr></table><br/>";
	$htm .= "<table  width ='100%' border='0'  class='nobordertable' >
			<tr>
				<td width='10%' style='text-align: right;'>Requisition Number : </td><td width='26%'>".$Requisition_ID."</td>
				<td width='10%' style='text-align: right;'>Requisition Date : </td><td width='26%'>".$Sent_Date_Time."</td>
			</tr>                               
			<tr>
				<td width='10%' style='text-align: right;'>GRN Number : </td>
				<td width='16%'>".$Grn_Issue_Note_ID."</td>
				<td style='text-align: right;'>GRN Date : </td>
				<td width='16%'>".$Created_Date_Time."</td>
			</tr>                              
			<tr>
				<td width='10%' style='text-align: right;'>Received From : </td>
				<td width='26%'>".$Temp_Store_Issue."</td>
				<td style='text-align: right;'>Store Need : </td>
				<td width='16%'>".$Temp_Store_Need."</td>
			</tr> 
			<tr>
				<td width='10%' style='text-align: right;'>Requisition Description : </td>
				<td width='26%'>".$Requisition_Description."</td>			
				<td style='text-align: right;'>Received By : </td>
				<td width='26%'>
					".$Receiver."
				</td>
			</tr>
			<tr>
				<td style='text-align: right;'>Prepared By : </td>
				<td>".$Temp_Employee_Name."</td>
				<td style='text-align: right;'>GRN Description : </td>
				<td>".$Issue_Description."</td>
			</tr>
		</table>
        <br/><br/><br/>";
        
        $htm .= "<table width='100%'>
			<tr>
                               <td width=3% style='text-align: center;'>Sn</td>
				<td>Item Name</td>
				<td width=9% style='text-align: center;'>Qty Required</td>";
if ($canPakage) {
$htm .= "<td width=9% style='text-align: center;'>Units Issued</td>
         <td width=13% style='text-align: center;'>Items per Unit</td>";
}

                        $htm .=   "<td width=9% style='text-align: center;'>Qty Issued</td>
								   <td width=9% style='text-align: center;'>Qty Received</td>
								   <td width=13% style='text-align: right;'>Buying Price</td>
								   <td width=15% style='text-align: right;'>Total</td>
								</tr>";
		
		//get list of item ordered
		$select_items = mysqli_query($conn,"select i.Product_Name, rqi.Quantity_Issued, rqi.Quantity_Required, rqi.Quantity_Issued, rqi.Quantity_Received, 
										i.Item_ID, rqi.Requisition_Item_ID, rqi.Item_Remark, rqi.Container_Issued, rqi.Items_Per_Container, rqi.Last_Buying_Price, rqi.Selling_Price from
										tbl_items i, tbl_requisition_items rqi where
										i.Item_ID = rqi.Item_ID and
										rqi.Issue_ID = '$Issue_ID'") or die(mysqli_error($conn));
		$no2 = mysqli_num_rows($select_items);
		$temp = 1; $Grand_Total = 0;
		if($no2 > 0){
			while($row = mysqli_fetch_array($select_items)){
			 	$htm .= "<tr><td style='text-align: center;'>".$temp."</td>";
				$htm .= "<td>".$row['Product_Name']."</td>";
                                
                if ($canPakage) {
					$htm .= "<td style='text-align: center;'>".$row['Container_Issued']."</td>";
					$htm .= "<td style='text-align: center;'>".$row['Items_Per_Container']."</td>";
               	}

				$htm .= "<td style='text-align: center;'>".$row['Quantity_Required']."</td>";
				$htm .= "<td style='text-align: center;'>".$row['Quantity_Issued']."</td>";
				$htm .= "<td style='text-align: center;'>".$row['Quantity_Received']."</td>";
				$htm .= "<td style='text-align: right;'>".number_format($row['Selling_Price'])."</td>";
				$htm .= "<td style='text-align: right;'>".number_format($row['Selling_Price'] * $row['Quantity_Received'])."</td>";
				$htm .= "</tr>";
				$temp++;
				$Grand_Total += ($row['Last_Buying_Price'] * $row['Quantity_Received']);
			}
		}
		$htm .= "<tr><td colspan='8'><b>GRAND TOTAL</b></td><td style='text-align: right;'><b>".number_format($Grand_Total)."</b></td></tr>";
		$htm .= "</table>";

                       
$htm .= "<br/><table style='width:100%;border:1px solid white'><tr style='border:1px solid white'>
            <td colspan='3' style='border:1px solid white'><b>Approved By:</b></td>
        </tr><tr style='border:1px solid white'>";
    //select list of approver
$sql_select_list_of_approver_result=mysqli_query($conn,"SELECT employee_signature,Employee_Name,dac.document_approval_level_title FROM tbl_document_approval_level_title dalt, tbl_document_approval_level dal,tbl_employee_assigned_approval_level eaal,tbl_document_approval_control dac,tbl_employee emp WHERE dalt.document_approval_level_title_id=dal.document_approval_level_title_id AND dal.document_approval_level_id=eaal.document_approval_level_id AND eaal.assgned_Employee_ID=dac.approve_employee_id AND dac.approve_employee_id=emp.Employee_ID AND document_number='$Requisition_ID' AND dac.document_type='grn_against_issue_note' GROUP BY eaal.assgned_Employee_ID") or die(mysqli_error($conn));
if(mysqli_num_rows($sql_select_list_of_approver_result)>0){
    while($approver_rows=mysqli_fetch_assoc($sql_select_list_of_approver_result)){
        $Employee_Name=$approver_rows['Employee_Name'];
        $document_approval_level_title=$approver_rows['document_approval_level_title'];
        $employee_signature=$approver_rows['employee_signature'];
        if($employee_signature==""||$employee_signature==null){
            $signature="________________________";
        }else{
            $signature="<img src='../esign/employee_signatures/$employee_signature' style='height:25px'>";
        }
        $htm .="
                <td style='border:1px solid white'>
                    <table width='100%' style='border:1px solid white'>
                        <tr style='border:1px solid white'>
                            <td style='border:1px solid white'>$signature </td>
                        </tr>
                        <tr style='border:1px solid white'>
                            <td style='border:1px solid white'>$Employee_Name</td>
                        </tr>
                        <tr style='border:1px solid white'>
                            <td style='border:1px solid white;text-align:left'><b><i>$document_approval_level_title </i></b></td>
                        </tr>
                    </table>
                </td>
                ";
    }
}
$htm .="</tr></table>";
include("MPDF/mpdf.php");
$mpdf = new mPDF('s', 'A4');
$mpdf->SetDisplayMode('fullpage');
$mpdf->list_indent_first_level = 0; // 1 or 0 - whether to indent the first level of a list
// LOAD a stylesheet
$stylesheet = file_get_contents('patient_file.css');
$mpdf->SetFooter('|Page {PAGENO} of {nb}|{DATE d-m-Y}');
$mpdf->WriteHTML($stylesheet, 1); // The parameter 1 tells that this is css/style only and no body/html/text
$mpdf->WriteHTML($htm, 2);
$mpdf->Output();