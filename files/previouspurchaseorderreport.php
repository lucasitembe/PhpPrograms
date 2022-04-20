<?php
    include("./includes/connection.php");
    include("./functions/purchaseorder.php");
    @session_start();
    
    //get Purchase_Order_ID
    if(isset($_GET['Purchase_Order_ID'])){
        $Purchase_Order_ID = $_GET['Purchase_Order_ID'];
    }else{
        $Purchase_Order_ID = 0;
    }
    $Employee_ID = '';
    
    $htm = "<table style='font-size:12px' width ='100%' height = '20px'>
		        <tr><td> <img src='./branchBanner/branchBanner.png' width=100%> </td></tr>
                <tr><td style='text-align: center;'><h2>PURCHASE ORDER</h2></td></tr>
            </table>";

    $htm .= "<br/>";

    $htm .= "<table style='font-size:10px;' width=100%>";

    $Purchase_Order = Purchase_Order($Purchase_Order_ID);
    if (!is_null($Purchase_Order)) {
        $htm .= "<tr><td width=25%><b>Order Number  </b></td><td width=25%>".$Purchase_Order['Purchase_Order_ID']."</td>";
        $htm .= "<td width=25%><b>Order Date  </b></td><td>".$Purchase_Order['Created_Date']."</td></tr>";
	    $htm .="<tr><td colspan=4><hr/></td></tr>";
        $htm .= "<tr><td><b>Store Need  </b></td><td style='text-align: left;'>".$Purchase_Order['Sub_Department_Name']."</td>";
        $htm .= "<td width=25%><b>Supplier  </b></td><td>".$Purchase_Order['Supplier_Name'].'<br/>'.$Purchase_Order['Postal_Address']."</td></tr>";
		$htm .="<tr><td colspan=4><hr/></td></tr>";
        $htm .= "<tr><td><b>Prepared By  </b></td><td>".$Purchase_Order['Employee_Name']."</td><td><b>Contact Person</b>
		</td><td>".$Purchase_Order['Contact_person'].'<br/>'.$Purchase_Order['Telephone'].'<br/>'.$Purchase_Order['Supplier_Email']."</td></tr>";
		$htm .="<tr><td colspan=4><hr/></td></tr>";
        $Employee_ID = $Purchase_Order['Employee_ID'];
        $Employee_Name = $Purchase_Order['Employee_Name'];
        $Employee_Type = $Purchase_Order['Employee_Type'];
        $Created_Date = $Purchase_Order['Created_Date'];
        $Store_Order_ID = $Purchase_Order['Store_Order_ID'];
    }
                    
    $htm .= "</table><br/><table style='font-size:10px;' width='100%'>
            <tr>
                <td width=5%><b>Sn</b></td>
                <td><b>Item Name</b></td>
                <!--td width=7% style='text-align: center;'><b>Containers</b></td-->
                <!--td width=15% style='text-align: center;'><b>Items per Container</b></td-->
				<td width=7% style='text-align: right;'><b>Package</b></td>
				<td width=12% style='text-align: right;'><b>SI Unit</b></td>
                <td width=9% style='text-align: right;'><b>Quantity</b></td>
                <td width=14% style='text-align: right;'><b>Unit Price</b></td>
				<td width=14% style='text-align: right;'><b>VAT</b></td>
                <td width=14% style='text-align: right;'><b>Total</b></td>
                </tr>";
$htm .= "<tr><td colspan=8><hr></td></tr>";

//select data from the table tbl_purchase_order_items ,
$temp = 1;
$Amount = 0;
$Grand_Total = 0;
$Total_VAT = 0;
$select_data = mysqli_query($conn,"select * from tbl_purchase_order_items poi, tbl_items it where
                                poi.item_id = it.item_id and Purchase_Order_ID = '$Purchase_Order_ID'") or die(mysqli_error($conn));
    while($row = mysqli_fetch_array($select_data)){
        $htm .= "<tr><td>".$temp.".</td><td>".$row['Product_Name']."</td>";
		$htm .= "<td style='text-align: right;'>".$row['Package_Type']."</td>";
		$htm .= "<td style='text-align: right;'>".$row['Unit_Of_Measure']."</td>";
        $htm .= "<!--td style='text-align: right;'>".$row['Containers_Required']."</td>";
        $htm .= "<td style='text-align: center;'>".$row['Items_Per_Container_Required']."</td-->";
        $htm .= "<td style='text-align: right;'>".$row['Quantity_Required']."</td>";
        $htm .= "<td style='text-align: right;'>".number_format($row['Price'],2)."</td>";
        $Amount = $row['Quantity_Required'] * $row['Price'];
        $Grand_Total = $Grand_Total + $Amount;	
		if($row['Tax']=="taxable"){
		$vat=0.18*$Amount;
		$Total_VAT=$Total_VAT+$vat;
		$htm .= "<td style='text-align: right;'>".number_format($vat,2)."</td>";
		}else if($row['Tax']=="non_taxable"){
		$htm .= "<td style='text-align: right;'>---</td>";
		}	else{
		$htm .= "<td style='text-align: right;'>Error</td>";
		}
		
        $htm .= "<td style='text-align: right;'>".number_format($Amount,2)."</td></tr>";
        $temp++;
    }
	$Total_iclusive=$Grand_Total+$Total_VAT; 
    $htm .= "<tr><td colspan=8><hr></td></tr>";
    $htm .= "<tr><td colspan=7 style='text-align: left;'><b>Total (Excl) : </td><td style='text-align: right;'><b>".number_format($Grand_Total,2)."</b></td></tr>";
	$htm .= "<tr><td colspan=7 style='text-align: left;'><b>Total VAT : </td><td style='text-align: right;'><b>".number_format($Total_VAT,2)."</b></td></tr>";
    $htm .= "<tr><td colspan=8><hr></td></tr>";
	$htm .= "<tr><td colspan=7 style='text-align: left;'><b>Total (Incl) : </td><td style='text-align: right;'><b>".number_format($Total_iclusive,2)."</b></td></tr>";
	$htm .= "<tr><td colspan=8><hr></td></tr>";
    $htm .= "</table>";
    $htm .= "<br/><br/><br/>";
/*
    $Last_Approval_Level = Last_Approval_Level();

    $htm .= "<table style='width:30%'>";
    $htm .= "<tr>";
    $htm .= "<td style='text-align:left;width:30%'><b>{$Last_Approval_Level['Approval_Title']} Sign : </b></td>";
    $htm .= "<td style='text-align:left;width:15%'><b>________________________________</b></td>";
    $htm .= "</tr>";
    $htm .= "<tr>";

    $htm .= "<td style='text-align:left;width:30%'><b> {$Last_Approval_Level['Approval_Title']} : </b></td>";
    $htm .= "<td style='text-align:left;width:15%'><b> {$Disposal_Officer} </b></td>";
    $htm .= "</tr>";
    $htm .= "</table>";

    $htm .= "<br/><br/><br/>";

    $counter = 0;
    //approval progress/
    /*
    $htm .= "<b style='font-size 8px;'>Approval details</b><table id='approvalProgress' width='100%'>";
    $htm .= "<tr><td colspan='5'><hr></td></tr>";
    $htm .= "<tr><td width='5%'>Sn</td><td width='18%'>Employee Title</td><td>Employee Name</td><td width='12%'>Status</td><td width='18%'>Date & Time</td></tr>";
    $htm .= "<tr><td colspan='5'><hr></td></tr>";

    $Approvals = Approval_Levels($Purchase_Order_ID);

    //display employee created purchase order
    $htm .= "<tr><td width='5%'>".++$counter."<b>.</b></td>
                            <td width='18%'>".ucwords(strtolower($Employee_Type))."</td>
                            <td>".ucwords(strtolower($Employee_Name))."</td>
                            <td width='12%'>Created</td>
                            <td width='18%'>".$Created_Date."</td></tr>"; 
    for($i = 2; $i <= $Approvals; $i++){
        $get_details = mysqli_query($conn,"select emp.Employee_Name, pop.Approval_Date_Time, al.Approval_Title from
                                    tbl_purchase_order_approval_process pop, tbl_employee emp, tbl_approval_level al where
                                    pop.Employee_ID = emp.Employee_ID and
                                    pop.Purchase_Order_ID = '$Purchase_Order_ID' and
                                    al.Approval_ID = pop.Approval_ID and
                                    pop.Approval_ID = '$i'") or die(mysqli_error($conn));
        $no = mysqli_num_rows($get_details);
        if($no > 0){
            while ($data = mysqli_fetch_array($get_details)) {
                $htm .= "<tr><td width='5%'>".++$counter."<b>.</b></td>
                            <td width='18%'>".ucwords(strtolower($data['Approval_Title']))."</td>
                            <td>".ucwords(strtolower($data['Employee_Name']))."</td>
                            <td width='12%'>Approved</td>
                            <td width='18%'>".$data['Approval_Date_Time']."</td></tr>";                
            }
        }else{
            $Approval_Level = Approval_Level($i);
            if (!is_null($Approval_Level)) {
                $htm .= "<tr><td width='5%'>".++$counter."<b>.</b></td>
                            <td width='18%'>".ucwords(strtolower($Approval_Level['Approval_Title']))."</td>
                            <td>----- ----- ----- ----- </td>
                            <td width='12%'>Pending</td>
                            <td width='18%'>Pending</td></tr>";
            }
        }
    }*/
    $htm .= "</table>";
    $htm .= "<style> body { font-size: 12px; } #approvalProgress { font-size: 8px; } </style>";
    
    
    $htm .= "<br/><table style='width:100%;border:1px solid white'><tr style='border:1px solid white'>
            <td colspan='3' style='border:1px solid white'><b>Approved By:</b></td>
        </tr><tr style='border:1px solid white'>";
    //select list of approver
$sql_select_list_of_approver_result=mysqli_query($conn,"SELECT employee_signature,Employee_Name,dac.document_approval_level_title FROM tbl_document_approval_level_title dalt, tbl_document_approval_level dal,tbl_employee_assigned_approval_level eaal,tbl_document_approval_control dac,tbl_employee emp WHERE dalt.document_approval_level_title_id=dal.document_approval_level_title_id AND dal.document_approval_level_id=eaal.document_approval_level_id AND eaal.assgned_Employee_ID=dac.approve_employee_id AND dac.approve_employee_id=emp.Employee_ID AND document_number='$Store_Order_ID' AND dac.document_type='purchase_order' GROUP BY eaal.assgned_Employee_ID") or die(mysqli_error($conn));
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

    include("./functions/makepdf.php");
?>