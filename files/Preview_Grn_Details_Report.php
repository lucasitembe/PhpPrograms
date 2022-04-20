<?php

session_start();
include("./includes/connection.php");
$temp = 0;
$Grand_Total = 0;
if (isset($_GET['Grn_Open_Balance_ID'])) {
$Grn_Open_Balance_ID = $_GET['Grn_Open_Balance_ID'];
} else {
$Grn_Open_Balance_ID = '';
}

$canPakage = false;
$display = "style='display:none'";

if (isset($_SESSION['systeminfo']['enable_receive_by_package']) && $_SESSION['systeminfo']['enable_receive_by_package'] == 'yes') {
$canPakage = true;
$display = "";
}


$sql_select = mysqli_query($conn,"select gob.Grn_Open_Balance_ID, emp.Employee_Name, gob.Saved_Date_Time, gob.Grn_Open_Balance_Description, gob.Employee_ID, sd.Sub_Department_Name
								from tbl_grn_open_balance gob, tbl_employee emp, tbl_sub_department sd where
								emp.Employee_ID = gob.Supervisor_ID and 
								sd.Sub_Department_ID = gob.Sub_Department_ID and 
								gob.Grn_Open_Balance_ID = '$Grn_Open_Balance_ID' and
								gob.Grn_Open_Balance_Status = 'saved' order by Grn_Open_Balance_ID desc") or die(mysqli_error($conn));

$num = mysqli_num_rows($sql_select);
if ($num > 0) {
while ($row = mysqli_fetch_array($sql_select)) {
//get employee prepared
$Prep_Employee = $row['Employee_ID'];
$sel = mysqli_query($conn,"select Employee_Name from tbl_employee where Employee_ID = '$Prep_Employee'") or die(mysqli_error($conn));
$Pre_no = mysqli_num_rows($sel);
if ($Pre_no > 0) {
while ($dt = mysqli_fetch_array($sel)) {
$Created_By = $dt['Employee_Name'];
}
} else {
$Created_By = '';
}

$Saved_Date_Time = $row['Saved_Date_Time'];
$Grn_Open_Balance_Description = $row['Grn_Open_Balance_Description'];
$Employee_Name = $row['Employee_Name']; //supervisor name
$Sub_Department_Name = $row['Sub_Department_Name'];
}
} else {
$Saved_Date_Time = '';
$Grn_Open_Balance_Description = '';
$Employee_Name = ''; //supervisor name
$Sub_Department_Name = '';
}
$htm = "<table  width ='100%' border='0'  class='nobordertable' >
		    <tr><td width=100%><img src='./branchBanner/branchBanner.png' width=100%></td></tr>
		    <tr><td>&nbsp;</td></tr>
			<tr><td style='text-align: center;'><b>GRN - STOCK TAKING REPORT</b></td></tr>
		</table>";

$htm .= "<table  width ='100%' border='0'  class='nobordertable' >
			    <tr>
					<td width='50%'><b>Grn Number : </b>" . $Grn_Open_Balance_ID . "</td>
					<td width='50%'><b>Grn Date : </b>" . $Saved_Date_Time . "</td>
			   </tr>
			    
			    <tr>
					<td width='10%'><b>Location : </b>" . $Sub_Department_Name . "</td>
					<td width='10%'><b>Grn Description : </b>" . $Grn_Open_Balance_Description . "</td>
			   	</tr>
			</table>";

$htm .= '<table width="100%">
			<tr>
                            <td width="5%"><b>SN</b></td>
                            <td width="36%"><b>Item Name</b></td>
                            <td width="9%"><b>UOM</b></td>
                            <td width="9%"><b>F/No.</b></td>
                            <td width="10%" style="text-align: right;"><b>Previous Quantity</b></td>';
if ($canPakage) {
$htm .= '  <td width="6%" style="text-align: right;"><b>Containers</b></td>'
        . ' <td width="8%" style="text-align: right;"><b>Items per Container</b></td>';
}

                        $htm .=   ' <td width="6%" style="text-align: right;"><b>Quantity</b></td>
                            <td width="7%" style="text-align: right;"><b>Buying Price</b></td>
                            <td width="10%" style="text-align: right;"><b>Batch No</b></td>
                            <td width="9%" style="text-align: right;"><b>Exp. Date</b></td>
                            <td width="15%" style="text-align: right;"><b>Amount</b></td>
                            <td width="20%" style="text-align: right;"><b>Reasons</b></td>
			</tr>';


 $select = mysqli_query($conn,"select ads.reasons,i.item_folio_number,obi.batch_no,i.Product_Name, obi.Item_Quantity, obi.Buying_Price, obi.Manufacture_Date, obi.Expire_Date, ibh.Item_Balance, obi.Container_Qty, obi.Items_Per_Container, i.Unit_Of_Measure
							from tbl_grn_open_balance_items obi, tbl_items i, tbl_items_balance_history ibh,tbl_reasons_adjustment ads where 
							i.Item_ID = obi.Item_ID and
							ibh.Grn_Open_Balance_ID = obi.Grn_Open_Balance_ID and obi.reason_id=ads.reason_id and
							ibh.Item_ID = obi.Item_ID and
							obi.Grn_Open_Balance_ID = '$Grn_Open_Balance_ID'") or die(mysqli_error($conn));
$num = mysqli_num_rows($select);
$Amount = 0;
$Grand_Total = 0;
if ($num > 0) {
while ($data = mysqli_fetch_array($select)) {
$Amount = $data['Item_Quantity'] * $data['Buying_Price'];
if ($data['Manufacture_Date'] == '0000-00-00') {
$Manufacture_Date = 'Unspecified';
} else {
$Manufacture_Date = $data['batch_no'];
}
$htm .= '<tr>
				<td width="2%">' . ++$temp . '</td>
				<td>' . $data['Product_Name'] . '</td>
				<td style="text-align: center;">' . $data['Unit_Of_Measure'] . '</td>
				<td style="text-align: center;">' . $data['item_folio_number'] . '</td>
				<td style="text-align: center;">' . $data['Item_Balance'] . '</td>
				<td style="text-align: center;">' . $data['Item_Quantity'] . '</td>';

if ($canPakage) {
$htm .= "<td style='text-align: center;'>" . $data['Container_Qty'] . "</td>
		 <td style='text-align: center;'>" . $data['Items_Per_Container'] . "</td>";
}

$htm .= '<td style = "text-align: right;">' . number_format($data['Buying_Price'],2) . '</td>
<td style = "text-align: right;">' . $data['batch_no'] . '</td>
<td style = "text-align: right;">' . $data['Expire_Date'] . '</td>
<td style = "text-align: right;">' . number_format($Amount,2) . '</td>
<td style = "text-align: right;">' . $data['reasons'] . '</td>
</tr>';
           $Grand_Total = $Grand_Total + $Amount;
                $Amount = 0;
    }
    $htm .= " <tr><td colspan='12' style='text-align: right;
'><b>Grand Total &nbsp;&nbsp;&nbsp;&nbsp;</b><b>". number_format($Grand_Total,2) . "</b></td></tr>";
   
}

$htm .= '</table><br/><br/><table style="width:100%;border:1px solid white" >';
$htm .= "<tr style='border:1px solid white'>
            <td colspan='3' style='border:1px solid white'><b>Approved By:</b></td>
        </tr><tr style='border:1px solid white'>";

//select list of approver
$sql_select_list_of_approver_result=mysqli_query($conn,"SELECT employee_signature,Employee_Name,dac.document_approval_level_title FROM tbl_document_approval_level_title dalt, tbl_document_approval_level dal,tbl_employee_assigned_approval_level eaal,tbl_document_approval_control dac,tbl_employee emp WHERE dalt.document_approval_level_title_id=dal.document_approval_level_title_id AND dal.document_approval_level_id=eaal.document_approval_level_id AND eaal.assgned_Employee_ID=dac.approve_employee_id AND dac.approve_employee_id=emp.Employee_ID AND document_number='$Grn_Open_Balance_ID' AND dac.document_type='grn_physical_counting_as_open_balance' GROUP BY eaal.assgned_Employee_ID") or die(mysqli_error($conn));
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
$htm .= '</tr></table>';
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
