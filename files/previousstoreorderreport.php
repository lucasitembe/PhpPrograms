<?php
    @session_start();
    include("./includes/connection.php");
    
    //get Store_Order_ID
    if(isset($_GET['Store_Order_ID'])){
        $Store_Order_ID = $_GET['Store_Order_ID'];
    }else{
        $Store_Order_ID = 0;
    }
    
     
    $htm = "<table width ='100%' height = '30px'>
		<tr>
		    <td>
			<img src='./branchBanner/branchBanner.png' width=100%>
		    </td>
		</tr>
        <tr><td>&nbsp;</td></tr>
		<tr>
		    <td style='text-align: center;'><b>STORE ORDER</b></td>
		</tr></table><br/>";
    $htm .= "<table width=100%>";
    //get all basic information
    $select_info = mysqli_query($conn,"SELECT * from tbl_store_orders so, tbl_sub_department sd, tbl_employee emp where
                                    so.Employee_ID = emp.Employee_ID and
                                    sd.Sub_Department_ID = so.Sub_Department_ID and
                                    so.Store_Order_ID = '$Store_Order_ID'") or die(mysqli_error($conn));
    
    while($row = mysqli_fetch_array($select_info)){
        $jobcard_ID = $row['jobcard_ID'];
        $htm .= "<tr><td width=20%><b>Order N<u>o</u>  </b></td><td width=20%>".$row['Store_Order_ID']."</td>";
        $htm .= "<td width=20%><b>Order Date  </b></td><td>".$row['Created_Date_Time']."</td></tr>";
        $htm .= "<tr><td><b>Store Ordered  </b></td><td style='text-align: left;'>".$row['Sub_Department_Name']."</td>";
        if($jobcard_ID > 0){
            $htm .="<td><b>****JobCard ID</b></td><td>".$jobcard_ID."</td>";
        }
        $htm.="</tr>";
        $htm .= "<tr><td><b>Prepared By </b></td><td style='text-align: left;'>".ucwords(strtolower($row['Employee_Name']))."</td>";
        $htm .= "<td><b>Description </b></td><td>".$row['Order_Description']."</td></tr>";
    }
                    
    $htm .= "</table><br/><table border='1' style='border-collapse:collapse;font-family:arial;font-weight:500' width='100%'>
            <tr style='background-color:#ddd'>
                <td width=5% style='padding:5px;text-align:center'>SN</td>
                <td style='padding:5px'>PRODUCT</td>
		<td style='padding:5px'>UOM</td>
                <td width=12% style='text-align: center;padding:5px'>UNIT</td>
                <td width=18% style='text-align: center;padding:5px'>ITEM PER UNIT</td>
                <td width=12% style='text-align: center;padding:5px'>QUANTITY</td>
                <td width=12% style='padding:5px'>REMARK</td>
                </tr>";
    //select data from the table tbl_purchase_order_items
    $temp = 1; $Amount = 0; $Grand_Total = 0;
    $select_data = mysqli_query($conn,"select * from tbl_store_order_items rqi, tbl_items it where
                                rqi.item_id = it.item_id and Store_Order_ID = '$Store_Order_ID'") or die(mysqli_error($conn));
    while($row = mysqli_fetch_array($select_data)){
        $htm .= "<tr><td style='padding:6px;text-align:center'>".$temp.".</td><td style='padding:6px'>".$row['Product_Name']."</td><td style='padding:5px'>".$row['Unit_Of_Measure']."</td>";
        $htm .= "<td style='text-align: center;padding:6px'>".$row['Container_Qty']."</td>";
        $htm .= "<td style='text-align: center;padding:6px'>".$row['Items_Qty']."</td>";
        $htm .= "<td style='text-align: center;padding:6px'>".$row['Quantity_Required']."</td>";
        $htm .= "<td style='text-align: right;padding:6px'>".$row['Item_Remark']."</td>";
        //$htm .= "<td style='text-align: left;'>&nbsp;&nbsp;&nbsp;".$row['Item_Remark']."</td>";
	$temp++;
    }
    $htm .= "</table>";
    $htm .= "<br/><table style='width:100%;border:1px solid white'><tr style='border:1px solid white'>
            <td colspan='3' style='border:1px solid white'><b>Approved By:</b></td>
        </tr><tr style='border:1px solid white'>";
    //select list of approver
$sql_select_list_of_approver_result=mysqli_query($conn,"SELECT employee_signature,Employee_Name,emp.Employee_Title, dac.document_approval_level_title FROM tbl_document_approval_level_title dalt, tbl_document_approval_level dal,tbl_employee_assigned_approval_level eaal,tbl_document_approval_control dac,tbl_employee emp WHERE dalt.document_approval_level_title_id=dal.document_approval_level_title_id AND dal.document_approval_level_id=eaal.document_approval_level_id AND eaal.assgned_Employee_ID=dac.approve_employee_id AND dac.approve_employee_id=emp.Employee_ID AND document_number='$Store_Order_ID' AND dac.document_type='store_order' GROUP BY eaal.assgned_Employee_ID") or die(mysqli_error($conn));
if(mysqli_num_rows($sql_select_list_of_approver_result)>0){
    while($approver_rows=mysqli_fetch_assoc($sql_select_list_of_approver_result)){
        $Employee_Name=$approver_rows['Employee_Name'];
        $document_approval_level_title=$approver_rows['document_approval_level_title'];
        $Employee_Title = $approver_rows['Employee_Title'];
        $employee_signature=$approver_rows['employee_signature'];

        if($document_approval_level_title == ''){
            $document_approval_level_title = $Employee_Title;
        }else{
            $document_approval_level_title = $document_approval_level_title;
        }
        if($employee_signature==""||$employee_signature==null){
            $signature="________________________";
        }else{
            $signature="<img src='../esign/employee_signatures/$employee_signature' style='height:25px'>";
        }
        $htm .="
                <td style='border:1px solid white'>
                    <table width='100%' style='border:1px solid white'>
                        <tr style='border:1px solid white'>
                            <td style='border:1px solid white'>$signature</td>
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
