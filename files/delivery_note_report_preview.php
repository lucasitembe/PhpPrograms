<?php if (session_status() == PHP_SESSION_NONE) { session_start(); } ?>
<?php
    include("./includes/connection.php");
    include_once("./functions/database.php");

    if(isset($_GET['FilterCategory'])){
        $without_order=false;
        $preview_without_order=false;
        if (isset($_GET['Start_Date'])) {
            $Start_Date = mysqli_real_escape_string($conn,$_GET['Start_Date']);
        } else {
            $Start_Date = '';
        }
        if (isset($_GET['End_Date'])) {
            $End_Date = mysqli_real_escape_string($conn,$_GET['End_Date']);
        } else {
            $End_Date = '';
        }
        if (isset($_GET['rv_number_search'])) {
            $rv_number_search= mysqli_real_escape_string($conn,$_GET['rv_number_search']);
        } else {
            $rv_number_search = '';
        }
        if (isset($_GET['Sub_Department_ID'])) {
            $Sub_Department_ID= mysqli_real_escape_string($conn,$_GET['Sub_Department_ID']);
        } else {
            $Sub_Department_ID = 0;
        }

        $sql_select=mysqli_query($conn,"SELECT gpo.created_date_time ,gpo.RV_Number, gpo.Grn_Purchase_Order_ID, gpo.Purchase_Order_ID ,gpo.Debit_Note_Number,gpo.Delivery_Date, gpo.Delivery_Person,sup.Supplier_Name, emp.Employee_Name,gpo.Invoice_Number FROM tbl_grn_purchase_order gpo, tbl_purchase_order po,tbl_employee emp,tbl_supplier sup WHERE emp.Employee_ID=gpo.Employee_ID and sup.Supplier_ID=gpo.supplier_id and gpo.Purchase_Order_ID=po.Purchase_Order_ID and po.Sub_Department_ID=$Sub_Department_ID and gpo.Created_Date_Time between '{$Start_Date}' and '{$End_Date}'");

        if(!empty(trim($rv_number_search))){
            $sql_select=mysqli_query($conn,"SELECT gpo.created_date_time , gpo.RV_Number, gpo.Purchase_Order_ID ,gpo.Grn_Purchase_Order_ID ,gpo.Debit_Note_Number,gpo.Delivery_Date, gpo.Delivery_Person,sup.Supplier_Name, emp.Employee_Name,gpo.Invoice_Number FROM tbl_grn_purchase_order gpo, tbl_purchase_order po,tbl_employee emp,tbl_supplier sup WHERE emp.Employee_ID=gpo.Employee_ID and sup.Supplier_ID=gpo.supplier_id and gpo.Purchase_Order_ID=po.Purchase_Order_ID and po.Sub_Department_ID=$Sub_Department_ID and gpo.RV_Number='$rv_number_search'");

            $no=mysqli_num_rows($sql_select);
            if($no ==0){
                $sql_select=mysqli_query($conn,"SELECT gpo.Grn_ID, gpo.Grn_Date_And_Time ,gpo.RV_Number ,gpo.Debit_Note_Number,gpo.Delivery_Date,sup.Supplier_Name, emp.Employee_Name,gpo.Invoice_Number FROM tbl_grn_without_purchase_order gpo ,tbl_employee emp,tbl_supplier sup WHERE emp.Employee_ID=gpo.Employee_ID and sup.Supplier_ID=gpo.supplier_id and gpo.Sub_Department_ID=$Sub_Department_ID and gpo.RV_Number='$rv_number_search' ")or die(mysqli_error($conn));
                $without_order=true;
            }
            } else {
                $preview_without_order=true;
            }
        }

        $select_sub_dept=mysqli_query($conn,"SELECT Sub_Department_Name FROM tbl_sub_department WHERE Sub_Department_ID=$Sub_Department_ID") ;
        $row=mysqli_fetch_assoc($select_sub_dept);
        $Sub_Department_Name=$row['Sub_Department_Name'];

    $htm  = "<table width ='100%' height = '30px'>";
    $htm .= "<tr> <td> <img src='./branchBanner/branchBanner.png' width=100%> </td> </tr>";
    $htm .= "<tr>";
    $htm .= "<td style='text-align: center;'><h2> DELIVERY NOTE REPORT</h2></td>";
    $htm .= "</tr>";
    $htm .= "</table><br/>";

    $htm .= "<table>";
    $htm .= "<tr>";
    $htm .= "<td><b>START DATE :</b> </td><td> {$Start_Date} </td>";
    $htm .= "<td><b>END DATE :</b> </td><td> {$End_Date} </td>";
    $htm .= "</tr>";
    $htm .= "<tr>";
    $htm .= "<td><b>LOCATION :</b> </td><td> {$Sub_Department_Name} </td>";
    $htm .= "<td> </td><td> </td>";
    $htm .= "</tr>";
    $htm .= "</table>";
    $htm .= "<br/>";

    $htm .= "<table id='items' width='100%' >";
    $title = "<thead><tr>";
    $title .= "<td width='5%'><b>SN</b></td>";
    $title .= "<td width='10%'><b>DELIVERY NOTE #</b></td>";
    $title .= "<td width='7%' style='text-align: right;'><b>GRN #</b></td>";
    $title .= "<td width='5%' style='text-align: right;'><b>RV #</b></td>";
    $title .= "<td width='7%' style='text-align: right;'><b>GRN DATE</b></td>";
    $title .= "<td width='15%' style='text-align: left;'><b>SUPPLIER</b></td>";
    $title .= "<td width='10%' style='text-align: right;'><b>DELIVERY DATE</b></td>";
    $title .= "<td width='10%' style='text-align: right;'><b>DELIVERY PERSON</b></td>";
    $title .= "<td width='16%' style='text-align: right;'><b>RECEIVED BY</b></td>";
    $title .= "<td width='7%' style='text-align: right;'><b>INVOICE #</b></td>";
    $title .= "<td width='13%' style='text-align: right;'><b>TOTAL</b></td>";
    $title .= "</tr></thead>";

    //$Grand_Stock = 0;
    $temp = 1;
    $htm .= $title;
    $General_Grand_Total = 0;

    if($without_order==false){

    while($row = mysqli_fetch_array($sql_select)) {
        $Grn_Note = $row['Debit_Note_Number'];
        $RV_Number = $row['RV_Number'];
        $Grn_Date = new DateTime($row['created_date_time']);
        $Supplier = $row['Supplier_Name'];
        $Delivery_Date = $row['Delivery_Date'];
        $Delivery_Person = $row['Delivery_Person'];
        $Received_by = $row['Employee_Name'];
        $Invoice_Number = $row['Invoice_Number'];
        $Grn_Number = $row['Grn_Purchase_Order_ID'];
        $Purchase_Order_ID=$row['Purchase_Order_ID'];
        
      
        /*selecting the grn total amount*/
        $sql_select_grn_amount=mysqli_query($conn,"SELECT SUM(Quantity_Received*Price) AS grn_amount FROM tbl_purchase_order_items WHERE Purchase_Order_ID='{$Purchase_Order_ID}'");
        $grn_results=mysqli_fetch_assoc($sql_select_grn_amount);
        $grn_amount=$grn_results['grn_amount'];

        $grn_total_amount +=floatval($grn_amount);


        $htm .= "<tr><td >".$temp."<b>.</b></td>";
        $htm .= "<td >{$Grn_Note}</td>";
        $htm .= "<td style='text-align: right;'>{$Grn_Number}</td>";
        $htm .= "<td style='text-align: right;'>{$RV_Number}</td>";
        $htm .= "<td style='text-align: right;'>".$Grn_Date->format('Y-m-d')."</td>";
        $htm .= "<td style='text-align: left;'>{$Supplier}</td>";
        $htm .= "<td style='text-align: right;'>{$Delivery_Date}</td>";
        $htm .= "<td style='text-align: left;'>{$Delivery_Person}</td>";
        $htm .= "<td style='text-align: left;'>{$Received_by}</td>";
        $htm .= "<td style='text-align: left;'>{$Invoice_Number}</td>";
        $htm .= "<td style='text-align: right;'>".number_format($grn_amount)."</td>";
     /*   if($Total_Balance > 0){ $Total = $Total_Balance; }else{ $Total = 0; }
        $htm .= "<td style='text-align: right;'>".number_format($Total * $Total_Average_Price)."</td>";
*/
        $htm .= "</tr>";
        $temp++;
        //$General_Grand_Total += ($Total * $Total_Average_Price);
        }

    }
    if($without_order==true || $preview_without_order==true){
        if($preview_without_order==true)
        {
            $sql_select=mysqli_query($conn,"SELECT gpo.Grn_ID, gpo.Grn_Date_And_Time ,gpo.RV_Number ,gpo.Debit_Note_Number,gpo.Delivery_Date,sup.Supplier_Name, emp.Employee_Name,gpo.Invoice_Number FROM tbl_grn_without_purchase_order gpo ,tbl_employee emp,tbl_supplier sup WHERE emp.Employee_ID=gpo.Employee_ID and sup.Supplier_ID=gpo.supplier_id and gpo.Sub_Department_ID=$Sub_Department_ID and gpo.Grn_Date_And_Time between '{$Start_Date}' and '{$End_Date}'")or die(mysqli_error($conn)); 
        }
    while($row = mysqli_fetch_array($sql_select)){
        $Grn_ID = $row['Grn_ID'];
            $Grn_Note = $row['Debit_Note_Number'];
            $RV_Number = $row['RV_Number'];
            $Grn_Date = new DateTime($row['Grn_Date_And_Time']);
            $Supplier = $row['Supplier_Name'];
            $Delivery_Date = $row['Delivery_Date'];
            $Delivery_Person = '';
            $Received_by = $row['Employee_Name'];
            $Invoice_Number = $row['Invoice_Number'];
            $Grn_Number = '';
            $Purchase_Order_ID='';

        /*selecting the grn total amount*/
        $sql_select_grn_amount=mysqli_query($conn,"SELECT SUM(Quantity_Required*Price) AS grn_amount FROM tbl_grn_without_purchase_order_items WHERE Grn_ID={$Grn_ID}") or die(mysqli_error($conn));
        $grn_results=mysqli_fetch_assoc($sql_select_grn_amount);
        $grn_amount=$grn_results['grn_amount'];
        $grn_total_amount +=floatval($grn_amount);

        $htm .= "<tr><td >".$temp."<b>.</b></td>";
        $htm .= "<td >{$Grn_Note}</td>";
        $htm .= "<td style='text-align: right;'>{$Grn_ID}</td>";
        $htm .= "<td style='text-align: right;'>{$RV_Number}</td>";
        $htm .= "<td style='text-align: right;'>".$Grn_Date->format('Y-m-d')."</td>";
        $htm .= "<td style='text-align: left;'>{$Supplier}</td>";
        $htm .= "<td style='text-align: right;'>{$Delivery_Date}</td>";
        $htm .= "<td style='text-align: left;'>{$Delivery_Person}</td>";
        $htm .= "<td style='text-align: left;'>{$Received_by}</td>";
        $htm .= "<td style='text-align: left;'>{$Invoice_Number}</td>";
        $htm .= "<td style='text-align: right;'>".number_format($grn_amount)."</td>";
     /*   if($Total_Balance > 0){ $Total = $Total_Balance; }else{ $Total = 0; }
        $htm .= "<td style='text-align: right;'>".number_format($Total * $Total_Average_Price)."</td>";
*/
        $htm .= "</tr>";
        $temp++;
        //$General_Grand_Total += ($Total * $Total_Average_Price);
        }
    }
    $htm .= "<tr><td colspan='10' style='text-align: center;'><b>GRN TOTAL AMOUNT </b></td><td style='text-align: right;'><b>".number_format($grn_total_amount)."</b></td></tr>";
    $htm .= "</table>";

    $htm .= "<style>";
    $htm .= "body { font-size: 14px; }";
    $htm .= "table#items tr td { font-size: 10px; }";
    $htm .= "table#items { border-collapse: collapse; border: 1px solid black; }";
    $htm .= "table#items td { border: 1px solid black; padding:3px 5px; }";
    $htm .= "</style>";

    include("MPDF/mpdf.php");
    $mpdf = new mPDF('s', 'A4');
    $mpdf->SetFooter('|Page {PAGENO} of {nb}|{DATE d-m-Y}');
    $mpdf->WriteHTML($stylesheet, 1); // The parameter 1 tells that this is css/style only and no body/html/text
    $mpdf->WriteHTML($htm);
    $mpdf->Output();
?>