<?php
    ob_start();
    @session_start();
    include("./includes/connection.php");
    
    //get Issue_ID
    if(isset($_GET['Issue_ID'])){
        $Issue_ID = $_GET['Issue_ID'];
    }else{
        $Issue_ID = 0;
    }
    
    $htm = "<table width ='100%' height = '30px'>
		    <tr><td>
			<img src='./branchBanner/branchBanner.png' width=100%>
		    </td></tr>
                    <tr><td style='text-align: center;'><b>ISSUE NOTE</b></td></tr>
                    </table>";
    $htm .= "<table width=100%>";
    //get all basic information
    $select_info = mysqli_query($conn,"SELECT
                                    *
                                FROM
                                    tbl_issues iss, tbl_requisition rq, tbl_employee emp, tbl_sub_department sd
                                WHERE
                                    iss.Requisition_ID = rq.Requisition_ID AND
                                    rq.Employee_ID = emp.Employee_ID AND
                                    sd.Sub_Department_ID = rq.Store_Issue AND
                                    iss.Issue_ID = '$Issue_ID'
                                    limit 1") or die(mysqli_error($conn));
    
    $num = mysqli_num_rows($select_info);
    if($num > 0){
	while($row = mysqli_fetch_array($select_info)){
        $Requisition_ID = $row['Requisition_ID'];
	    $htm .= "<tr><td width=20%><b><span style='font-size: x-small;'>Issue N<u>o</u></span></b></td>
	    <td width=20%><span style='font-size: x-small;'>".$row['Issue_ID']."</span></td>";
	    $htm .= "<td width=20%><b><span style='font-size: x-small;'>Issue Date</span></b></td>
		    <td><span style='font-size: x-small;'>".$row['Issue_Date_And_Time']."</span></td></tr>";
	    
	    $htm .= "<tr><td width=20%><b><span style='font-size: x-small;'>Requisition N<u>o</u></span></b></td>
	    <td width=20%><span style='font-size: x-small;'>".$row['Requisition_ID']."</span></td>";
	    $htm .= "<td width=20%><b><span style='font-size: x-small;'>Requisition Date</span></b></td>
		    <td><span style='font-size: x-small;'>".$row['Issue_Date_And_Time']."</span></td></tr>";
	    
	    //get store need
	    $Sub_Department_ID2 = $row['Store_Need'];
	    $select_store_need = mysqli_query($conn,"select Sub_Department_Name from tbl_sub_department where sub_department_id = '$Sub_Department_ID2'") or die(mysqli_error($conn));
	    $no = mysqli_num_rows($select_store_need);
	    if($no > 0){
		while($row2 = mysqli_fetch_array($select_store_need)){
		    $Store_Need = $row2['Sub_Department_Name'];
		}
	    }else{
            $Store_Need = '';
	    }
	    
	    $htm .= "<tr><td><b><span style='font-size: x-small;'>Request From</span></b></td>
		    <td style='text-align: left;'><span style='font-size: x-small;'>".$Store_Need."</span></td>";
	    $htm .= "<td width=15%><b><span style='font-size: x-small;'>Requisited By</span></b></td>
		    <td><span style='font-size: x-small;'>".$row['Employee_Name']."</span></td></tr>";

        $htm .= "<tr><td width=15%><b><span style='font-size: x-small;'>Description</span></b></td>
		    <td colspan='3'><span style='font-size: x-small;'>".$row['Requisition_Description']."</span></td></tr>";

        $htm .= "<tr><td><span style='font-size: x-small;'><b>Issuing Store</b></span></td>
		    <td style='text-align: left;'><span style='font-size: x-small;'>".$row['Sub_Department_Name']."</span></td>";
        $htm .= "<td width=15%><b><span style='font-size: x-small;'>Issue Description</span></b></td>
		    <td><span style='font-size: x-small;'>".$row['Issue_Description']."</span></td></tr>";

        $htm .= "<tr><td><span style='font-size: x-small;'><b>IV Number</b></span></td>
            <td style='text-align: left;'><span style='font-size: x-small;'>".$row['IV_Number']."</span></td>";

	    $Prepared_By = "".$row['Employee_Name']."";
        $Received_By = "".$row['Receiving_Officer']."";
	}
    }
    $htm .= "</table><br/><table width='100%' border=1 style='border-collapse: collapse;'>
            <thead><tr>
                <td width=3%><b><span style='font-size: x-small;'>Sn</span></b></td>
                <td width='7%'><b><span style='font-size: x-small;'>Item Code</span></b></td>
                <td width='7%'><b><span style='font-size: x-small;'>Folio Number</span></b></td>
                <td><b><span style='font-size: x-small;'>Item Name</span></b></td>
                <td width='7%'><b><span style='font-size: x-small;'>UOM</span></b></td>
                <td width=9% style='text-align: right;'><b><span style='font-size: x-small;'>Qty Required</span></b></td>
                <td width=9% style='text-align: right;'><b><span style='font-size: x-small;'>Qty Issued</span></b></td>
                <td width=9% style='text-align: right;'><b><span style='font-size: x-small;'>Buying Price</span></b></td>
                <td width=9% style='text-align: right;'><b><span style='font-size: x-small;'>Total</span></b></td>
                <td width=9% style='text-align: right;'><b><span style='font-size: x-small;'>Selling Price</span></b></td>
                <td width=9% style='text-align: right;'><b><span style='font-size: x-small;'>Total</span></b></td>
                <td width=9% style='text-align: right;'><b><span style='font-size: x-small;'>Profit/Loss</span></b></td>
               
            </tr></thead>";
    //select data from the table tbl_purchase_order_items
    $temp = 1; $Amount = 0; $Grand_Total = 0;$Grand_Total_sell=0;
    $select_data = mysqli_query($conn,"select * from tbl_requisition_items rqi, tbl_items it where
                                rqi.item_id = it.item_id and rqi.Issue_ID = '$Issue_ID'") or die(mysqli_error($conn));
    while($row = mysqli_fetch_array($select_data)){
        $htm .= "<tr class='issue_note_items'><td><span style='font-size: x-small;'>".$temp."</span></td>
                <td><span style='font-size: x-small;'>".$row['Product_Code']."</span></td>
                <td><span style='font-size: x-small;'>".$row['item_folio_number']."</span></td>
                <td><span style='font-size: x-small;'>".$row['Product_Name']."</span></td>";
        $htm .= "<td style='text-align: left;'><span style='font-size: x-small;'>".$row['Unit_Of_Measure']."</span></td>";
        $htm .= "<td style='text-align: right;'><span style='font-size: x-small;'>".$row['Quantity_Required']."</span></td>";
        $htm .= "<td style='text-align: right;'><span style='font-size: x-small;'>".$row['Quantity_Issued']."</span></td>";
        $htm .= "<td style='text-align: right;'><span style='font-size: x-small;'>".number_format($row['Last_Buying_Price'],2)."</span></td>";
        $htm .= "<td style='text-align: right;'><span style='font-size: x-small;'>".number_format($row['Last_Buying_Price'] * $row['Quantity_Issued'])."</span></td></tr>";
        $htm .= "<td style='text-align: right;'><span style='font-size: x-small;'>".number_format($row['Selling_Price'])."</span></td>";
        $htm .= "<td style='text-align: right;'><span style='font-size: x-small;'>".number_format($row['Selling_Price'] * $row['Quantity_Issued'])."</span></td></tr>";
        $htm .= "<td style='text-align: right;'><span style='font-size: x-small;'>".number_format(($row['Selling_Price'] * $row['Quantity_Issued'])-($row['Last_Buying_Price'] * $row['Quantity_Issued']))."</span></td></tr>";
        $Grand_Total += ($row['Last_Buying_Price'] * $row['Quantity_Issued']);
        $Grand_Total_sell += ($row['Selling_Price'] * $row['Quantity_Issued']);
        $temp++;
    }
    $htm .= "<tr><td colspan='8'><span style='font-size: x-small;'><b>GRAND TOTAL</b></td>
                <td style='text-align: right;'><span style='font-size: x-small;'><b>".number_format($Grand_Total)."</b></span></td><td></td>
                <td style='text-align: right;'><span style='font-size: x-small;'><b>".number_format($Grand_Total_sell)."</b></span></td>
                <td style='text-align: right;'><span style='font-size: x-small;'><b>".number_format($Grand_Total_sell-$Grand_Total)."</b></span></td>
            </tr>";
    //get employee prepare issue note
    $select_details = mysqli_query($conn,"select Employee_Name from tbl_employee emp, tbl_issues iss where
				    emp.Employee_ID = iss.Employee_ID and
					iss.Issue_ID = '$Issue_ID'") or die(mysqli_error($conn));
    $no = mysqli_num_rows($select_details);
    if($no > 0){
	while($row = mysqli_fetch_array($select_details)){
	    $Employee_Name = $row['Employee_Name'];
	}
    }else{
	$Employee_Name = '';
    }
    $htm .= '</table><br/>';
    $htm .= '<table width="100%" border=0>';
    $htm .= "<tr><td style='text-align:left;width:20%'><b><span style='font-size: x-small;'>Issuing Officer Sign : </span></b></td>";
    $htm .= "<td style='text-align:left;width:30%'><b>_______________________</b></td>";
    $htm .= "<td style='text-align:right;width:30%'><b><span style='font-size: x-small;'>Receiving Officer Sign : </span></b></td>";
    $htm .= "<td style='text-align:left;width:20%'><b>_______________________</b></td></tr>";

    $htm .= "<tr><td style='text-align:left;'><b><span style='font-size: x-small;'>Issue Prepared By : </span></b></td>";
    $htm .= "<td style='text-align:left;'><b><span style='font-size: x-small;'>".$Employee_Name."</span></b></td>";
    $htm .= "<td style='text-align:center;'><b><span style='font-size: x-small;'>Received By : </span></b></td>";
    $htm .= "<td style='text-align:left;'><b><span style='font-size: x-small;'>".$Received_By."</span></b></td></tr>";
    

    $issue_details = mysqli_query($conn,"select Supervisor_ID, Approval_Date_Time, Supervisor_Comment, Requisition_Status from
                                    tbl_requisition where Requisition_ID = '$Requisition_ID'") or die(mysqli_error($conn));
    $n_details = mysqli_num_rows($issue_details);
    if($n_details > 0){
        while ($rw = mysqli_fetch_array($issue_details)) {
            $Supervisor_ID = $rw['Supervisor_ID'];
            $Approval_Date_Time = $rw['Approval_Date_Time'];
            $Supervisor_Comment = $rw['Supervisor_Comment'];
            $Requisition_Status = $rw['Requisition_Status'];
        }
    }else{
        $Supervisor_ID = 0;
        $Approval_Date_Time = '';
        $Supervisor_Comment = '';
        $Requisition_Status = '';
    }

    if($Requisition_Status == 'Served'){
        //get employee approve
        $slct = mysqli_query($conn,"select Employee_Name from tbl_employee where Employee_ID = '$Supervisor_ID'") or die(mysqli_error($conn));
        $num_slct = mysqli_num_rows($slct);
        if($num_slct > 0){
            while ($dt = mysqli_fetch_array($slct)) {
                $Supervisor_Name = $dt['Employee_Name'];
            }
        }else{
            $Supervisor_Name = '';
        }

        $htm .= "<tr><td colspan=7 style='text-align: left;'><b><span style='font-size: x-small;'>Approval Status : Approved</span></b></td></tr>";
        //$htm .= "<tr style='display:none'><td colspan=7 style='text-align: left;'><b><span style='font-size: x-small;'>Approved By : ".$Supervisor_Name."</span></b></td></tr>";
        //$htm .= "<tr style='display:none'><td colspan=7 style='text-align: left;'><b><span style='font-size: x-small;'>Approval Date : ".$Approval_Date_Time."</span></b></td></tr>";
        //$htm .= "<tr style='display:none'><td colspan=7 style='text-align: left;'><b><span style='font-size: x-small;'>Comment : ".$Supervisor_Comment."</span></b></td></tr>";
    }else{
        $htm .= "<tr><td colspan=7 style='text-align: left;'><b><span style='font-size: x-small;'>Approval Status : Pending</span></b></td></tr>";
    }

    $htm .= "</table>";
    $htm .= "<style> .issue_note_items td { border: 1px solid #000; display: inline-block; } </style>";
    
    
        
$htm .= "<br/><table style='width:100%;border:1px solid white'><tr style='border:1px solid white'>
            <td colspan='3' style='border:1px solid white'><b>Approved By:</b></td>
        </tr><tr style='border:1px solid white'>";
    //select list of approver
$sql_select_list_of_approver_result=mysqli_query($conn,"SELECT employee_signature,Employee_Name,dac.document_approval_level_title FROM tbl_document_approval_level_title dalt, tbl_document_approval_level dal,tbl_employee_assigned_approval_level eaal,tbl_document_approval_control dac,tbl_employee emp WHERE dalt.document_approval_level_title_id=dal.document_approval_level_title_id AND dal.document_approval_level_id=eaal.document_approval_level_id AND eaal.assgned_Employee_ID=dac.approve_employee_id AND dac.approve_employee_id=emp.Employee_ID AND document_number='$Issue_ID' AND dac.document_type='issue_note' GROUP BY eaal.assgned_Employee_ID") or die(mysqli_error($conn));
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
?>

<?php

    include("./functions/makepdf.php");
?>