<?php if (session_status() == PHP_SESSION_NONE) { session_start(); } ?>
<?php
    include("./includes/connection.php");
    ob_start();

    include_once("./functions/database.php");
    include_once("./functions/stockledger.php");
    include_once("./functions/department.php");
    include_once("./functions/items.php");
    if(isset($_GET['rv_number'])){
        if($_GET['search_type']==='specific'){
            $rv_number=mysqli_real_escape_string($conn,$_GET['rv_number']);
            $grn=mysqli_real_escape_string($conn,$_GET['grn']);
            $order_type=mysqli_real_escape_string($conn,$_GET['order_type']);



    if($order_type=='with_order'){

    $htm = "<table  width ='100%' border='0'  class='nobordertable' >";
    $htm .= "<tr> <td> <img src='./branchBanner/branchBanner.png' width=100%> </td> </tr>";
    $htm .= "<tr>";
    $htm .= "<td style='text-align: center;'><h2> RECEIVED GOODS WITH PURCHASE ORDER</h2></td>";
    $htm .= "</tr>";
    $htm .= "</table><br/>";

    $sql_select_purchase_order = mysqli_query($conn,"SELECT po.Purchase_Order_ID, e.Employee_ID, gpo.Delivery_Date, e.Employee_Name,e.Employee_Branch_Name, d.Department_Name, sd.Sub_Department_Name ,s.Supplier_ID,s.Supplier_Name,s.Mobile_Number,s.Supplier_Email, s.Postal_Address, gpo.Invoice_Number,gpo.Grn_Purchase_Order_ID, gpo.RV_Number FROM tbl_employee e,tbl_sub_department sd,tbl_supplier s,tbl_branches b,tbl_grn_purchase_order gpo, tbl_purchase_order po, tbl_department d WHERE e.Employee_ID=gpo.Employee_ID and s.Supplier_ID=gpo.Supplier_ID and sd.Sub_Department_ID=po.Sub_Department_ID and b.Branch_ID=po.Branch_ID and gpo.Purchase_Order_ID=po.Purchase_Order_ID and d.Department_ID=sd.Department_ID and gpo.Purchase_Order_ID=$grn and gpo.RV_Number=$rv_number");
    $purchase_row=mysqli_fetch_assoc($sql_select_purchase_order);

    $branch_name=$purchase_row['Employee_Branch_Name'];
    $department_name=$purchase_row['Sub_Department_Name'];
    $supplier_name=$purchase_row['Supplier_Name'];
    $prepared_by=$purchase_row['Employee_Name'];
    $supplier_address=$purchase_row['Postal_Address'];
    $RV_Number=$purchase_row['RV_Number'];
    $Invoice_Number=$purchase_row['Invoice_Number'];
    $Purchase_Order_ID=$purchase_row['Purchase_Order_ID'];
    $Grn_Number=$purchase_row['Grn_Purchase_Order_ID'];
    $Delivery_Date=$purchase_row['Delivery_Date'];


      $htm .= "<table  width='100%' border='0'  class='nobordertable' >";
      $htm .= "<tr>";
    $htm .= "<td style='text-align:right;width:25%;'><b>Branch Name :</b> </td><td style='text-align:left;width:25%;'> {$branch_name} </td>";
    $htm .= "<td style='text-align:right;width:25%;'><b>Department Name :</b> </td><td style='text-align:left;width:25%;'> {$department_name} </td>";
    $htm .= "</tr>";
    $htm .= "<tr>";
    $htm .= "<td style='text-align:right;width:25%;'><b>Supplier Name :</b> </td><td style='text-align:left;width:25%;'> {$supplier_name} </td>";
    $htm .= "<td style='text-align:right;width:25%;'><b>Received By :</b> </td><td style='text-align:left;width:25%;'> {$prepared_by} </td>";
    $htm .= "</tr>";
    $htm .= "<tr>";
    $htm .= "<td style='text-align:right;'><b>Supplier Address :</b> </td><td> {$supplier_address} </td>";
    $htm .= "<td style='text-align:right;'><b>GRN Number :</b> </td><td> {$Grn_Number} </td>";
    $htm .= "</tr>";
    $htm .= "<tr>";
    $htm .= "<td style='text-align:right;'><b>Invoice No:</b></td><td> {$Invoice_Number} </td>";
    $htm .= "<td style='text-align:right;'><b>RV Number :</b> </td><td> {$RV_Number} </td>";
    $htm .= "</tr>";
    $htm .= "<tr>";
    $htm .= "<td style='text-align:right;'><b>Delivery Date</b></td><td> {$Delivery_Date} </td>";
    $htm .= "</tr>";
    $htm .= "</table>";
    $htm .= "<br/>";




    
    $sql_select = mysqli_query($conn,"SELECT i.Product_Name, poi.Quantity_Required,i.Unit_Of_Measure , poi.Quantity_Required,poi.Quantity_Received,poi.Containers_Required, poi.Buying_Price, poi.Price FROM tbl_purchase_order_items poi, tbl_items i, tbl_grn_purchase_order gpo WHERE i.Item_ID=poi.Item_ID and gpo.Purchase_Order_ID=poi.Purchase_Order_ID and gpo.Invoice_Number='$Invoice_Number' and gpo.Purchase_Order_ID=$Purchase_Order_ID and gpo.Purchase_Order_ID=$grn and  gpo.RV_Number=$rv_number ");

     $htm .= "<table id='items' width='100%' >";
    $title = "<thead><tr>";
    $title .= "<td width='5%'><b>SN</b></td>";
    $title .= "<td width='60%'><b>ITEM NAME</b></td>";
    $title .= "<td width='10%'><b>UOM</b></td>";
    $title .= "<td width='10%' style='text-align: right;'><b>BUYING PRICE</b></td>";
    $title .= "<td width='10%' style='text-align: right;'><b>QUANTITY RECEIVED</b></td>";
    $title .= "<!--td width='5%' style='text-align: right;'><b>CONTAINER REQUIRED</b></td-->";
    $title .= "<td width='10%' style='text-align: right;'><b>PRICE</b></td>";
    $title .= "</tr></thead>";
    $htm.=$title;
    $htm.="<tbody>";
    $temp=1;
    $totalAmount=0;
    while($row = mysqli_fetch_array($sql_select)){
        $Buying_Price = ($row['Price']);

        $htm.="<tr><td>".$temp."</td><td>".$row['Product_Name']."</td><td>".$row['Unit_Of_Measure']."</td><td style='text-align:right;'>".number_format($Buying_Price,2)."</td><td style='text-align:center;'>".$row['Quantity_Received']."</td><!--td>".$row['Containers_Required']."</td--><td style='text-align:right;'>".number_format($Buying_Price*$row['Quantity_Received'],2)."</td></tr>";
        $temp++;
        $totalAmount+=($Buying_Price*$row['Quantity_Received']);
    }
    $htm.="<tr><td colspan='5' style='text-align:center;'><b>Total Amount</b></td><td style='text-align:right;'><b>".number_format($totalAmount,2)."</b></td></tr>";

    $htm.= "</tbody><</table>";    
}


    if($order_type=='without_order'){

             $htm = "<table  width ='100%' border='0'  class='nobordertable' >";
    $htm .= "<tr> <td> <img src='./branchBanner/branchBanner.png' width=100%> </td> </tr>";
    $htm .= "<tr>";
    $htm .= "<td style='text-align: center;'><h2> RECEIVED GOODS WITHOUT PURCHASE ORDER</h2></td>";
    $htm .= "</tr>";
    $htm .= "</table><br/>";

        $Grn_ID=$grn;
    $sql_select_purchase_order = mysqli_query($conn,"SELECT e.Employee_ID, gpo.Delivery_Date, e.Employee_Name,e.Employee_Branch_Name, d.Department_Name, sd.Sub_Department_Name ,s.Supplier_ID,s.Supplier_Name,s.Mobile_Number,s.Supplier_Email, s.Postal_Address,gpo.Invoice_Number , gpo.RV_Number FROM tbl_employee e,tbl_sub_department sd,tbl_supplier s, tbl_grn_without_purchase_order gpo, tbl_department d WHERE e.Employee_ID=gpo.Employee_ID and s.Supplier_ID=gpo.Supplier_ID and d.Department_ID=sd.Department_ID and gpo.Grn_ID=$Grn_ID and gpo.RV_Number='$rv_number' and sd.Sub_Department_ID=gpo.Sub_Department_ID ");
    $purchase_row=mysqli_fetch_assoc($sql_select_purchase_order);

    
    $branch_name=$purchase_row['Employee_Branch_Name'];
    $department_name=$purchase_row['Sub_Department_Name'];
    $supplier_name=$purchase_row['Supplier_Name'];
    $prepared_by=$purchase_row['Employee_Name'];
    $supplier_address=$purchase_row['Postal_Address'];
    $RV_Number=$purchase_row['RV_Number'];
    $Invoice_Number=$purchase_row['Invoice_Number'];
    $Purchase_Order_ID="";
    $Grn_Number="";
    $Delivery_Date=$purchase_row['Delivery_Date'];


      $htm .= "<table  width='100%' border='0'  class='nobordertable' >";
      $htm .= "<tr>";
    $htm .= "<td style='text-align:right;width:25%;'><b>Branch Name :</b> </td><td style='text-align:left;width:25%;'> {$branch_name} </td>";
    $htm .= "<td style='text-align:right;width:25%;'><b>Department Name :</b> </td><td style='text-align:left;width:25%;'> {$department_name} </td>";
    $htm .= "</tr>";
    $htm .= "<tr>";
    $htm .= "<td style='text-align:right;width:25%;'><b>Supplier Name :</b> </td><td style='text-align:left;width:25%;'> {$supplier_name} </td>";
    $htm .= "<td style='text-align:right;width:25%;'><b>Received By :</b> </td><td style='text-align:left;width:25%;'> {$prepared_by} </td>";
    $htm .= "</tr>";
    $htm .= "<tr>";
    $htm .= "<td style='text-align:right;'><b>Supplier Address :</b> </td><td> {$supplier_address} </td>";
    $htm .= "<td style='text-align:right;'><b>GRN Number :</b> </td><td> {$Grn_ID}</td>";
    $htm .= "</tr>";
    $htm .= "<tr>";
    $htm .= "<td style='text-align:right;'><b>Invoice No :</b></td><td> {$Invoice_Number} </td>";
    $htm .= "<td style='text-align:right;'><b>RV Number :</b> </td><td> {$RV_Number} </td>";
    $htm .= "</tr>";
    $htm .= "<tr>";
    $htm .= "<td style='text-align:right;'><b>Delivery Date: </b></td><td>{$Delivery_Date}  </td>";
    $htm .= "</tr>";
    $htm .= "</table>";
    $htm .= "<br/>";
    
    $sql_select = mysqli_query($conn,"SELECT i.Product_Name,i.Unit_Of_Measure,poi.Quantity_Required, poi.Price FROM tbl_grn_without_purchase_order_items poi, tbl_items i, tbl_grn_without_purchase_order gpo WHERE i.Item_ID=poi.Item_ID and gpo.Grn_ID=poi.Grn_ID and gpo.Grn_ID=$Grn_ID and gpo.RV_Number='$rv_number'");

     $htm .= "<table id='items' width='100%' >";
    $title = "<thead><tr>";
    $title .= "<td width='5%'><b>SN</b></td>";
    $title .= "<td width='60%'><b>ITEM NAME</b></td>";
    $title .= "<td width='10%'><b>UOM</b></td>";
    $title .= "<td width='10%' style='text-align: right;'><b>BUYING PRICE</b></td>";
    $title .= "<td width='10%' style='text-align: right;'><b>QUANTITY RECEIVED</b></td>";
    $title .= "<!--td width='5%' style='text-align: right;'><b>CONTAINER REQUIRED</b></td-->";
    $title .= "<td width='10%' style='text-align: right;'><b>PRICE</b></td>";
    $title .= "</tr></thead>";
    $htm.=$title;
    $htm.="<tbody>";
    $temp=1;
    $totalAmount=0;
    while($row = mysqli_fetch_array($sql_select)){
        $Buying_Price = ($row['Buying_Price'] != '') ? $row['Buying_Price'] : $row['Price'];

        $htm.="<tr><td>".$temp."</td><td>".$row['Product_Name']."</td><td>".$row['Unit_Of_Measure']."</td><td style='text-align:right;'>".number_format($Buying_Price,2)."</td><td style='text-align:center;'>".$row['Quantity_Required']."</td><!--td>".$row['Containers_Required']."</td--><td style='text-align:right;'>".number_format($Buying_Price*$row['Quantity_Required'],2)."</td></tr>";
        $temp++;
        $totalAmount+=($Buying_Price*$row['Quantity_Required']);
    }
    $htm.="<tr><td colspan='5' style='text-align:center;'><b>Total Amount</b></td><td style='text-align:right;'><b>".number_format($totalAmount,2)."</b></td></tr>";

    $htm.= "</tbody><</table>";    
}

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
        }
    }


?>