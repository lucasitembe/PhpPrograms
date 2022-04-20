<?php 
  include("./includes/connection.php");
        $month = $_GET['month'];
        if($month==1){
            $Month_name= 'January';
        }else if($month==2){
            $Month_name= 'February';
        }else if($month==3){
            $Month_name= 'March';
        }else if($month==4){
            $Month_name= 'April';
        }else if($month==5){
            $Month_name= 'May';
        }else if($month==6){
            $Month_name= 'June';
        }else if($month==7){
            $Month_name= 'July';
        }else if($month==8){
            $Month_name= 'August';
        }else if($month==9){
            $Month_name= 'September';
        }else if($month==10){
            $Month_name= 'October';
        }else if($month==11){
            $Month_name= 'November';
        }else if($month==12){
            $Month_name= 'December';
        }
        
        $year = $_GET['year'];
        $sponsor_id = $_GET['sponsor_id'];

        if($sponsor_id != 'All'){
            $filter  =" AND pp.Sponsor_ID='$sponsor_id'";
        }else{
            $filter="";
        }
        $select_invoice = mysqli_query($conn,"SELECT Invoice_ID FROM tbl_invoice WHERE invoice_month = '$month' AND invoice_year ='$year' AND sponsor_id = '$sponsor_id'");

        if(mysqli_num_rows($select_invoice) > 0){
            $invoice_id = mysqli_fetch_assoc($select_invoice)['Invoice_ID'];
            $invoice_id = sprintf("%'.07d\n",$invoice_id);
        }else{
            $invoice_id = 'INVOICE NOT CREATED YET';
        }
        $select_hospital_info = mysqli_query($conn,"SELECT * FROM tbl_system_configuration");
        $hospital_info =mysqli_fetch_assoc($select_hospital_info);

        $resultsamount = mysqli_query($conn,"SELECT   COUNT(*) AS Quantity, sum((ppl.price - ppl.discount) * ppl.quantity) AS Total_Amount  FROM  tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_bills c, tbl_claim_folio cf WHERE cf.Registration_ID=pp.Registration_ID AND cf.Bill_ID=c.Bill_ID AND pp.Patient_Payment_ID = ppl.Patient_Payment_ID AND pp.bill_id = c.Bill_ID and ppl.Status<>'removed'  AND claim_month = '$month'  AND claim_year = '$year' AND c.e_bill_delivery_status = 1 AND  pp.Billing_Type IN ('Inpatient Credit','Outpatient Credit') $filter ") or die(mysqli_error($conn)."==1");
    $patientTotal_Amount=0;
    if(mysqli_num_rows($resultsamount)>0){
        while($row = mysqli_fetch_assoc($resultsamount)){
            $patientTotal_Amount =$row['Total_Amount'];
        }
    }
    $male=0;
    $female=0;
    
    $foliosentfemale = mysqli_query($conn, "SELECT COUNT(cf.Registration_ID) AS female FROM tbl_claim_folio cf, tbl_bills b, tbl_patient_registration pr  WHERE cf.Bill_ID=b.Bill_ID AND e_bill_delivery_status =1 AND claim_month='$month' AND claim_year='$year' AND cf.Registration_ID=pr.Registration_ID AND Gender ='Female'  ") or die(mysqli_error($conn));
    if(mysqli_num_rows($foliosentfemale)>0){
        while($rw = mysqli_fetch_assoc($foliosentfemale)){
            $female = $rw['female'];
        }
    }else{
        $female=0;
    }
    
    $foliosentmale = mysqli_query($conn, "SELECT COUNT(cf.Registration_ID) AS male FROM tbl_claim_folio cf, tbl_bills b, tbl_patient_registration pr  WHERE cf.Bill_ID=b.Bill_ID AND e_bill_delivery_status =1 AND claim_month='$month' AND claim_year='$year' AND cf.Registration_ID=pr.Registration_ID AND Gender ='Male'  ") or die(mysqli_error($conn));
    if(mysqli_num_rows($foliosentmale)>0){
        while($rw = mysqli_fetch_assoc($foliosentmale)){
            $male = $rw['male'];
        }
    }else{
        $male=0;
    }



    $total = $female + $male;

    $foliosent = mysqli_query($conn, "SELECT COUNT(Folio_No) AS TotalFolio FROM tbl_claim_folio cf, tbl_bills b WHERE cf.Bill_ID=b.Bill_ID AND e_bill_delivery_status =1 AND claim_month='$month' AND claim_year='$year' ") or die(mysqli_error($conn));
    if(mysqli_num_rows($foliosent)>0){
        while($rw = mysqli_fetch_assoc($foliosent)){
            $TotalFolio = $rw['TotalFolio'];
        }
    }else{
        $TotalFolio=0;
    }
  $html="<table width ='100%' >
        <tr><td width='40%'></td> <td width='20%'> <img src='./images/nhifstamp.jpeg' width=40% height='150px'> </td>
            <td width='40%'></td>
        </tr>
        <tr><th colspan='3'>NATIONAL HEALTH INSURANCE FUND</th></tr>
        <tr><td colspan='3' style='text-align:center;'>Dedicated to providing quality health care to its beneficiaries <small><b>Revised from NHIF 6(Reguration No 6)</b></small></td></tr>
        <tr><th colspan='3'>MONTHLY REPORT INVOICE NO. $invoice_id </th></tr>

    </table><br/>
    ";

    $html.="<table  style='width: 100%;font-size: 15px;'>
            <thead>
                    <tr><th>1</th>
                        <td >ACCREDITATION NUMBER:</td>
                        <td ><b>".$hospital_info['Fax']." </b></td>
                    </tr>
                    <tr>
                        <th>2</th>
                        <td >NAME OF FACILITY:</td>
                        <td ><b>".$hospital_info['Hospital_Name']." </b></td>
                    </tr>
                    <tr>
                        <th>3</th>
                        <td >ADDRESS: </td>
                        <td ><b>".$hospital_info['Box_Address']." </b></td>
                    </tr>
                    <tr>
                        <th>4</th>
                        <td  colspan='2'>REGION .............................. DISTRICT ...........................</td>
                    </tr>
                    <tr>
                        <th>5</th>
                        <td>FACILITY CODE</td>
                        <td><b>".$hospital_info['facility_code']."</b></td>
                    </tr>
                    <tr><th>6</th>
                        <td > BENEFITIERIES TREATED</td>
                        <td >
                            Male <input type='text'  style='width: 13%; display:inline;' value='$male'>
                            Female <input type='text'  style='width: 13%; display:inline;' value='$female'>
                            Total <input type='text'  style='width: 13%; display:inline;' value='$total'>
                        </td>
                    </tr>

                    <tr><th>7</th>
                        <td >DATE OF TREATEMENT</td>
                        <td >
                            FROM <input type='text'  style='width: 20%; display:inline;' value='$Month_name/$year'> 
                            TO  <input type='text'  style='width: 20%; display:inline;' value='$Month_name/$year'>
                        </td>
                    </tr>
                    <tr>
                        <td>8</td>
                        <td>NUMBER OF FOLIO</td>
                        <td>".number_format($TotalFolio)." </td>
                    </tr>
                    <tr><th>9</th>
                        <td >AMOUNT CLAIMED: TZsh</td>
                        <td >
                             <b>".number_format($patientTotal_Amount)."/=</b>
                        </td>
                    </tr>
                </thead>
        </thead>
    </table>";
  $html .="<table border = 1 style='width: 100%;font-size: 12px; border-collapse: collapse;'>
	<tr><td colspan='4'><b>BREAK DOWN OF AMOUNT CLAIMED</b></td></tr>
                <tr>
                    <th>SN</th>
                    <th>Category</th>
                    <th style='text-align: center;'>Quantity</th>
                    <th  style='text-align: right;'>Amount</th>
                </tr>";

                $count =1;
                $results = mysqli_query($conn,"SELECT i.Item_ID,ic.Item_Category_ID, ic.Item_Category_Name, COUNT(*) AS Quantity, sum((ppl.price - ppl.discount) * ppl.quantity) AS Total_Amount  FROM tbl_items i, tbl_item_subcategory isub, tbl_patient_payments pp, tbl_patient_payment_item_list ppl,  tbl_bills b, tbl_item_category ic, tbl_bills c, tbl_claim_folio cf WHERE cf.Registration_ID=pp.Registration_ID AND cf.Bill_ID=c.Bill_ID AND pp.Patient_Payment_ID = ppl.Patient_Payment_ID AND pp.bill_id = c.Bill_ID AND  i.Item_Subcategory_ID = isub.Item_Subcategory_ID AND ic.Item_Category_ID = isub.Item_Category_ID AND i.Item_ID = ppl.Item_ID and ppl.Status<>'removed' AND pp.Patient_Payment_ID = ppl.Patient_Payment_ID AND pp.bill_id = b.Bill_ID  AND claim_month = '$month'  AND claim_year = '$year' AND b.e_bill_delivery_status = 1 AND   pp.Billing_Type IN ('Outpatient Credit', 'Inpatient Credit') $filter  GROUP BY ic.Item_Category_ID") or die(mysqli_error($conn));

               
               $OutpatientTotal_Amount = 0;
               while ($row = mysqli_fetch_assoc($results)) {

                   $html .= "<tr><td>".$count."</td><td>".strtoupper($row['Item_Category_Name'])."</td><td style='text-align: center;'>".$row['Quantity']."</td><td style='text-align: right;'>".number_format($row['Total_Amount'])."</td></tr>";
                   $count++;
                   $OutpatientTotal_Amount +=$row['Total_Amount'];
				   
               }


            
            $html .="<tr>
                <td colspan='3'  style='text-align:right;'><b>Total Amount:</b></td>
                <td style='text-align: right;'><b>".number_format($OutpatientTotal_Amount)."</b></td>
            </tr>
            </table>";

              


            
           $html .=" 
	<tr><td colspan='4'><b><hr></b></td></tr>
	<tr><td colspan='3' style='text-align:right;'><b>Grand Total:</b></td><td style='text-align: right;'><b>".number_format($OutpatientTotal_Amount)."</b></td></tr>
	<tr><td colspan='4'><b><hr></b></td></tr>
</table>";
// $html.="dfdf";
$html.="<table style='width: 100%;font-size: 12px;'>
        <tr>
            <th>NAME  </th>
            <th>$Employee_Name </th>

            <th>DESIGNATION</th>
            <th>$Employee_Title </th>
        </tr>
       
        <tr>
            <th>CONTACT: </th>
            <th>$Phone_Number </th>

            <th colspan='>DATE </th>
            <th> $invoice_date; </th>
        </tr>
        
        </table>";

// <tr>
//     <td>
   $html.=" <table 'width:100%'>
        <tr>
            <th>NAME  </th>
            <th>$Employee_Name </th>

            <th>DESIGNATION</th>
            <th>$Employee_Title </th>
        </tr>
        <tr>
            <th>CONTACT: </th>
            <th>$Phone_Number </th>

            <th colspan='>DATE </th>
            <th> $invoice_date; </th>
        </tr>
        <tr>
            <th >Signature:    $signature; </th>
            <th width='100%'>
            <img src='./images/stamp.png' width='100' height='100' style='float:left;'>
            </th>
        </tr>";

    //     </table>
    // </td>
    // <td width='13%'> 
    //     <table>
$html.="               
        </table>";
//     </td>
// </tr>
// </table>";

     //      echo $html;

    include("MPDF/mpdf.php");
    $mpdf = new mPDF('s', 'A4');
    $mpdf->SetFooter('|Page {PAGENO} of {nb}|{DATE d-m-Y}');
    $mpdf->WriteHTML($stylesheet, 1); // The parameter 1 tells that this is css/style only and no body/html/text
    $mpdf->WriteHTML($html);
    $mpdf->Output();

?>


