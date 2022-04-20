<?php
 include("./includes/connection.php");
    
    $Registration_ID = '';
    
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
   $data='';
   $data .= '<br/><br/><fieldset><center>
          <table border="0" width="80%">
            <tr>
             <td style="text-align:ridght"><b>PATIENT NAME</b></td><td>'.$patient['Patient_Name'].'</td><td style="text-align:rigdht" ><b>SPONSOR</b></td><td colspan="2">'.$patient['Guarantor_Name'].'</td>
            </tr>
            <tr>
             <td style="text-align:ridght"><b>ADMITTED BY</b></td><td>'.$patient['Employee_Name'].'</td><td style="text-align:ridght"><b>Admimision_Date</b></td><td>'.$patient['Admission_Date_Time'].'</td><td><a style="float:right" href="print_item_billed_details.php?Registration_ID='.$Registration_ID.'&folio='.$folio.'&Billing_Type='.$Billing_Type.'&Item_ID='.$Item_ID.'" target="_blank" class="art-button-green">Print</a></td>
            </tr>
            <tr>
             <td style="text-align:ridght"><b>WARD</b></td><td>'.$patient['Hospital_Ward_Name'].'</td><td style="text-align:rigdht"><b>Bed #</b></td><td colspan="2">'.$patient['Bed_Name'].'</td>
            </tr>
            <tr>
              <td style="text-align:ridght" ><b>FOLLIO #</b></td><td style="text-align:left" colspan="">'.$folio.'</td><td style="text-align:rigdht"><b>Billing Type</b></td><td>'.$Billing_Type.'</td> 
           </tr>
          </table></center>
          </fieldset><br/>
        ';
   
   
  
    $selec_items_in_recept="
               SELECT pp.Payment_Date_And_Time,pp.Patient_Payment_ID,ppil.Patient_Payment_Item_List_ID,i.Product_Name,ppil.Check_In_Type,ppil.Discount,ppil.Price,ppil.Quantity,ppil.ServedBy,ppil.ItemOrigin,e.Employee_Name,e.Employee_Type FROM `tbl_patient_payment_item_list` ppil INNER JOIN tbl_patient_payments pp ON ppil.Patient_Payment_ID=pp.Patient_Payment_ID JOIN tbl_items i ON i.Item_ID=ppil.Item_ID JOIN tbl_employee e ON e.Employee_ID=ppil.Consultant_ID WHERE pp.Billing_Type = '$Billing_Type' AND ppil.Status !='Removed' AND pp.Registration_ID='$Registration_ID' AND Folio_Number = '$folio' AND ppil.Item_ID = '$Item_ID'
            ";
 
    
  // echo ($selec_items_in_recept);exit;
    $results_total_item = mysqli_query($conn,$selec_items_in_recept) or die(mysqli_error($conn));
    $sn=1;
    
     $data .= '<center>
          <table border="0" width="100%">
            <tr>
             <td><b>SN</b></td>
             <td ><b>PARTICULAR</b></td>
             <td ><b>CONSULTATION</b></td>
             <td style="text-align:right"><b>PRICE</b></td>
             <td style="text-align:right"><b>DISCOUNT</b><td><b>QUANTITY</b></td>
             <td style="text-align:right"><b>TOTAL</b></td>
             <td style="text-align:right"><b>BY</b></td>
             <td style="text-align:right"><b>TITLE</b></td>
             <td style="text-align:right"><b>DATE</b></td>
             <td style="text-align:center"><b>ACTION</b></td>
            </tr>
            <tr>
            <td colspan="11"><hr/></td>
             </tr>
        ';
      $total=0;
    //Foreach Iterm
    while ($row = mysqli_fetch_array($results_total_item)) {
       $ppil=$row['Patient_Payment_Item_List_ID'];
       $data.='<tr>
                <td>'.$sn++.'</td>
                <td>'.$row['Product_Name'].'</td>
                <td>'.$row['Check_In_Type'].'</td>
                <td style="text-align:right">'.number_format($row['Price']).'</td>
                <td style="text-align:right">'.$row['Discount'].'</td>
                <td style="text-align:right">'.$row['Quantity'].'</td>
                <td style="text-align:right">'.number_format(($row['Price']-$row['Discount'])*$row['Quantity']).'</td>
                <td style="text-align:right">'.$row['Employee_Name'].'</td>
                <td style="text-align:right">'.$row['Employee_Type'].'</td>
                <td style="text-align:right">'.$row['Payment_Date_And_Time'].'</td>
                <td style="text-align:center" width="20%">
                   <button type="button" onclick="removeItem('.$ppil.',\''.$row['Product_Name'].'\')" class="art-button-green">Remove</button>
                   <button type="button" onclick="editItem('.$ppil.',\''.$row['Product_Name'].'\','.$row['Quantity'].')" class="art-button-green">Edit</button>
                </td>
            </tr>'; 
        $total +=($row['Price']-$row['Discount'])*$row['Quantity'];
    }
    
    $data.='<tr><td colspan="11"><hr/></td></tr>'
            . '<tr><td colspan="9" style="text-align:right"><b>Total Amount</b></td><td><b>'.$total.'</b></td></tr>'
            . '</table></center>';
    
    echo $data;