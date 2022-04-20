<?php
    include("./includes/connection.php");
    
    $Registration_ID = '';$data='';
    
    if(isset($_GET['Registration_ID'])){
	$Registration_ID = $_GET['Registration_ID'];
    }
    if(isset($_GET['folio'])){
        $folio = $_GET['folio'];
    }if(isset($_GET['Item_ID'])){
        $Item_ID= $_GET['Item_ID'];
    }if(isset($_GET['Billing_Type'])){
        $Billing_Type = $_GET['Billing_Type'];
    }
    
     
   $data .= "<center><table width ='100%' height = '30px'>
		    <tr><td colspan='4' style='text-align: center;'>
			<img src='./branchBanner/branchBanner.png'>
		    </td></tr>
		    <tr><td style='text-align: center;'><span><b>PATIENT BILLING REPORT</b></span></td></tr>
                     </table></center>
		    ";
    
     //$qr="SELECT p.Patient_Name,s.Sponsor_Name FROM tbl_patient_registration p JOIN tbl_sponsor s ON s.Sponsor_ID=p.Sponsor_ID WHERE p.Registration_ID='$Registration_ID' ";
   $select_Patients_details = mysqli_query($conn,
            "select * from tbl_patient_registration pr ,tbl_sponsor s, tbl_admission ad,tbl_employee e,tbl_hospital_ward hp,tbl_beds bd where
		pr.registration_id = ad.registration_id and Admission_Status = 'pending' and
		s.Sponsor_ID = pr.Sponsor_ID and e.Employee_ID= ad.Admission_Employee_ID
                and hp.Hospital_Ward_ID= ad.Hospital_Ward_ID and bd.Bed_ID= ad.Bed_ID
		AND pr.Registration_ID='$Registration_ID'
		") or die(mysqli_error($conn));
  //$rs=  mysqli_query($conn,$qr) or die(mysqli_error($conn));
   $patient=  mysqli_fetch_array($select_Patients_details);
   
  // $patient_Name=$patient['Patient_Name'];
   
   $data .= '<br/><br/><center>
          <table border="0" width="100%">
           <tr>
                <td style="text-align:ridght"><b>Patient Name</b></td><td>'.$patient['Patient_Name'].'</td><td style="text-align:rigdht" ><b>Sponsor</b></td><td colspan="">'.$patient['Guarantor_Name'].'</td>
               </tr>
               <tr>
                <td style="text-align:ridght"><b>Admitted By</b></td><td>'.$patient['Employee_Name'].'</td><td style="text-align:ridght"><b>Admission Date</b></td><td>'.$patient['Admission_Date_Time'].'</td>
               </tr>
               <tr>
                <td style="text-align:ridght"><b>Ward</b></td><td>'.$patient['Hospital_Ward_Name'].'</td><td style="text-align:rigdht"><b>Bed #</b></td><td colspan="">'.$patient['Bed_Name'].'</td>
               </tr>
               <tr>
                 <td style="text-align:ridght" ><b>Follio #</b></td><td style="text-align:left" colspan="">'.$folio.'</td><td style="text-align:rigdht"><b>Billing Type</b></td><td>'.$Billing_Type.'</td> 
               </tr>
          </table>
          </fieldset><br/>
        ';
   
   
  
    $selec_items_in_recept="
               SELECT pp.Payment_Date_And_Time,pp.Patient_Payment_ID,ppil.Patient_Payment_Item_List_ID,i.Product_Name,ppil.Check_In_Type,ppil.Discount,ppil.Price,ppil.Quantity,ppil.ServedBy,ppil.ItemOrigin,e.Employee_Name,e.Employee_Type FROM `tbl_patient_payment_item_list` ppil INNER JOIN tbl_patient_payments pp ON ppil.Patient_Payment_ID=pp.Patient_Payment_ID JOIN tbl_items i ON i.Item_ID=ppil.Item_ID JOIN tbl_employee e ON e.Employee_ID=ppil.Consultant_ID WHERE pp.Billing_Type = '$Billing_Type' AND pp.Registration_ID='$Registration_ID' AND Folio_Number = '$folio' AND ppil.Status !='Removed' AND ppil.Item_ID = '$Item_ID'
            ";
 
    
   //echo ($selec_items_in_recept);exit;
    $results_total_item = mysqli_query($conn,$selec_items_in_recept) or die(mysqli_error($conn));
    $sn=1;
    
     $data .= '<center>
          <table border="0" width="100%">
            <tr>
             <td><b>SN</b></td><td ><b>PARTICULAR</b></td><td ><b>CONSULTATION</b></td><td><b>PRICE</b></td><td><b>DISCOUNT</b><td><b>QUANTITY</b></td><td><b>TOTAL</b></td><td><b>BY</b></td><td><b>TITLE</b></td><td><b>DATE</b></td>
            </tr>
            <tr>
            <td colspan="10"><hr/></td>
             </tr>
        ';
      $total=0;
    //Foreach Iterm
    while ($row = mysqli_fetch_array($results_total_item)) {
       $data.='<tr>
                <td>'.$sn++.'</td>
                <td>'.$row['Product_Name'].'</td>
                <td>'.$row['Check_In_Type'].'</td>
                <td>'.number_format($row['Price']).'</td>
                <td >'.$row['Discount'].'</td>
                <td>'.$row['Quantity'].'</td>
                <td>'.number_format(($row['Price']-$row['Discount'])*$row['Quantity']).'</td>
                <td>'.$row['Employee_Name'].'</td>
                <td>'.$row['Employee_Type'].'</td>
                <td>'.$row['Payment_Date_And_Time'].'</td>
            </tr>'; 
        $total +=($row['Price']-$row['Discount'])*$row['Quantity'];
    }
    
    $data.='<tr><td colspan="10"><hr/></td></tr>'
            . '<tr><td colspan="9" style="text-align:right"><b>Total Amount</b></td><td><b>'.$total.'</b></td></tr>'
            . '</table></center>';
    
   
    
   // echo $data;
    
     //echo $htm;
    $htm .= "</table>";
    include("MPDF/mpdf.php");

    $mpdf=new mPDF('','Letter',0,'',12.7,12.7,14,12.7,8,8); 
    $mpdf->SetFooter('|{PAGENO}|{DATE d-m-Y}');
    $mpdf->WriteHTML($data);
    $mpdf->Output();
    exit;
    
//    echo "<tr><td colspan='5'<hr></td></tr>";
//    echo "<tr><td colspan=3 style='text-align: right;'><b> TOTAL :   </b></td>
//    <td style='text-align: right;'><b>".number_format($inactive_sum)."</b></td>
//    <td style='text-align: right;'><b>".number_format($amount_billed)."</b></td>
//    <td style='text-align: right;'><b>".number_format($amount_paid)."</b></td>
//    <td style='text-align: right;'><b>".number_format($ballance)."</b></td></tr>";
//    echo "<tr><td colspan=7><hr></td></tr></table></center>";
?>