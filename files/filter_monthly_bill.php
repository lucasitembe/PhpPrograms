<?php 
  include("./includes/connection.php");
  
  if(isset($_POST['filterreport'])){
            $month = $_POST['month'];
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
            $year = $_POST['year'];
            $sponsor_id = $_POST['sponsor_id'];
            if($sponsor_id != 'All'){
                $filter  =" AND pp.Sponsor_ID='$sponsor_id'";
            }else{
                $filter="";
            }
            $conmpinfo = mysqli_query($conn, "SELECT * FROM tbl_system_configuration") or die(mysqli_error($conn));
            if(mysqli_num_rows($conmpinfo)>0){
                while($row = mysqli_fetch_assoc($conmpinfo)){
                    $Hospital_Name = $row['Hospital_Name'];
                    $Box_Address = $row['Box_Address'];
                    $Cell_Phone = $row['Cell_Phone'];
                    $facility_code =$row['facility_code'];
                    $Fax = $row['Fax'];
                    $Tin = $row['Tin'];
                }
            }else{
                $Hospital_Name = 'Not Set';
                $Box_Address = 'Not Set';
                $Cell_Phone = 'Not Set';
                $facility_code ='Not Set';
                $Fax = 'Not Set';
                $Tin= 'Not Set';
            }
            $Employee_ID = $_SESSION['userinfo']['Employee_ID'];

            $conmpinfo = mysqli_query($conn, "SELECT Employee_Name,Employee_Title,e.Phone_Number,employee_signature, trans_datetime,invoice_date, invoice_month, invoice_year,amount, Guarantor_Name FROM tbl_invoice i, tbl_employee e, tbl_sponsor s WHERE s.sponsor_id=i.Sponsor_ID AND  i.employee_id=e.Employee_ID AND i.Employee_ID='$Employee_ID' ") or die(mysqli_error($conn));
            if(mysqli_num_rows($conmpinfo)>0){
                while($row = mysqli_fetch_assoc($conmpinfo)){
                    $invoice_month = $row['invoice_month'];
                    $invoice_year = $row['invoice_year'];
                    $amount = $row['amount'];
                    $trans_datetime =$row['trans_datetime'];
                    $invoice_date = $row['invoice_date'];
                    $Guarantor_Name = $row['Guarantor_Name'];
                    $Employee_Name =$row['Employee_Name'];
                    $Employee_Title = $row['Employee_Title'];
                    $Phone_Number = $row['Phone_Number'];
                    $employee_signature = $row['employee_signature'];

                    if($employee_signature==""||$employee_signature==null){
                        $signature="________________________";
                    }else{
                        $signature="<img src='../esign/employee_signatures/$employee_signature' style='height:25px'>";
                    }
                }
            }else{
                $invoice_month = 'Not Set';
                $invoice_year = 'Not Set';
                $amount = 'Not Set';
                $trans_datetime ='Not Set';
                $invoice_date = 'Not Set';
                $Guarantor_Name= 'Not Set';
                $Employee_Title ='Not Set';
                $Employee_Name ='Not set';
                $Phone_Number ='Not Set';
            }  
    $resultsamount = mysqli_query($conn,"SELECT   COUNT(*) AS Quantity, sum((ppl.price - ppl.discount) * ppl.quantity) AS Total_Amount  FROM  tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_bills c, tbl_claim_folio cf WHERE cf.Registration_ID=pp.Registration_ID AND cf.Bill_ID=c.Bill_ID AND pp.Patient_Payment_ID = ppl.Patient_Payment_ID AND pp.bill_id = c.Bill_ID and ppl.Status<>'removed'  AND claim_month = '$month'  AND claim_year = '$year' AND c.e_bill_delivery_status = 1 AND  pp.Billing_Type IN ('Inpatient Credit','Outpatient Credit') $filter ") or die(mysqli_error($conn)."==1");
    $patientTotal_Amount=0;
    if(mysqli_num_rows($resultsamount)>0){
        while($row = mysqli_fetch_assoc($resultsamount)){
            $patientTotal_Amount =$row['Total_Amount'];
        }
    }
    $male=0;
    $female=0;
    // $foliocount = mysqli_query($conn,"SELECT  count(case when Gender='Male' then 1 end) as male_count, count(case when Gender='Female' then 1 end) as female_count  FROM tbl_patient_registration pr, tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_claim_folio cf, tbl_bills b WHERE cf.Bill_ID=b.Bill_ID AND cf.Registration_ID=pr.Registration_ID AND  pp.Patient_Payment_ID = ppl.Patient_Payment_ID AND pp.bill_id = cf.Bill_ID and ppl.Status<>'removed' AND  claim_month='$month' AND claim_year='$year' AND b.e_bill_delivery_status = 1  AND  pp.Billing_Type IN ('Inpatient Credit','Outpatient Credit') $filter   GROUP BY pp.Check_In_ID , pp.Bill_ID") or die(mysqli_error($conn)."===2");
    // while($rwb = mysqli_fetch_assoc($foliocount)){
    //     $male_count = $rwb['male_count'];
    //     $female_count = $rwb['female_count'];
    //     $counttotal =$male_count + $female_count;
    // }

    
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
 ?>
  <p> <h3>eCLAIM MONTHLY REPORT</h3> </p>
    <table  style="width: 80%;font-size: 15px;">
    <thead>
                    <tr>
                        <td >ACCREDITATION NUMBER</td>
                        <td ><?=$Fax ?></td>
                    </tr>
                    <tr>
                        <td >NAME OF FACILITY</td>
                        <td ><b><?=$Hospital_Name ?></b></td>
                    </tr>
                    <tr>
                        <td >ADDRESS: </td>
                        <td ><b><?=$Box_Address ?></b></td>
                    </tr>
                    <tr>
                        <td  colspan="2">REGION ............. DISTRICT ................</td>
                    </tr>
                    <tr>
                        <td >NUMBER OF BENEFITIERIES TREATED</td>
                        <td >
                            Male  <input type="text"  style="width: 20%; display:inline; text-align:center;" readonly value="<?php echo $male; ?>">
                            Female <input type="text"  style="width: 20%; display:inline; text-align:center;" readonly value="<?php echo  $female ?>">
                            Total <input type="text"  style="width: 20%; display:inline; text-align:center;" readonly value="<?php echo  $total ?>">
                        </td>
                    </tr>
                    <tr>
                        <td>NUMBER OF FOLIO</td>
                        <td><?php echo number_format($TotalFolio); ?></td>
                    </tr>
                    <tr>
                        <td >DATE OF TREATEMENT</td>
                        <td >
                            FROM <input type="text" value="<?php echo $Month_name.'/'.$year; ?>" style="width: 20%; display:inline; text-align:center;" readonly> 
                            TO  <input type="text" value="<?php echo $Month_name.'/'.$year; ?>" style="width: 20%; display:inline; text-align:center;" readonly></td>
                    </tr>
                    <tr>
                        <td >AMOUNT CLAIMED: TZsh</td>
                        <td >
                             <input type="text"  style="width: 20%; display:inline; text-align:center;" readonly value="<?php echo number_format($patientTotal_Amount)."/="; ?>">
                        </td>
                    </tr>
                </thead>
        </thead>
    </table>
<table style="width: 80%;font-size: 18px;">
	<tr><td colspan='4'><b>BREAK DOWN OF AMOUNT CLAIMED</b></td></tr>
                <tr>
                    <th>SN</th>
                    <th>Category</th>
                    <th style='text-align: center;'>Quantity</th>
                    <th  style='text-align: right;'>Amount</th>
                </tr>
            <?php 
                $count =1;
                
                $results = mysqli_query($conn,"SELECT i.Item_ID,ic.Item_Category_ID, ic.Item_Category_Name, COUNT(*) AS Quantity, sum((ppl.price - ppl.discount) * ppl.quantity) AS Total_Amount  FROM tbl_items i, tbl_item_subcategory isub, tbl_patient_payments pp, tbl_patient_payment_item_list ppl,  tbl_bills b, tbl_item_category ic, tbl_bills c, tbl_claim_folio cf WHERE cf.Registration_ID=pp.Registration_ID AND cf.Bill_ID=c.Bill_ID AND pp.Patient_Payment_ID = ppl.Patient_Payment_ID AND pp.bill_id = c.Bill_ID AND  i.Item_Subcategory_ID = isub.Item_Subcategory_ID AND ic.Item_Category_ID = isub.Item_Category_ID AND i.Item_ID = ppl.Item_ID and ppl.Status<>'removed' AND pp.Patient_Payment_ID = ppl.Patient_Payment_ID AND pp.bill_id = b.Bill_ID  AND claim_month = '$month'  AND claim_year = '$year' AND b.e_bill_delivery_status = 1 AND   pp.Billing_Type IN ('Outpatient Credit', 'Inpatient Credit') $filter  GROUP BY ic.Item_Category_ID") or die(mysqli_error($conn));
               $OutpatientTotal_Amount = 0;
               while ($row = mysqli_fetch_assoc($results)) {
                    $Item_Category_ID = $row['Item_Category_ID'];
                   echo "<tr><td>".$count."</td><td class='rows_list' onclick='getCategoryitems($Item_Category_ID)'>".strtoupper($row['Item_Category_Name'])."<input id='Product_name' value='$Item_Category_Name' style='display:none;'></td><td style='text-align: center;'>".$row['Quantity']."</td><td style='text-align: right;'>".number_format($row['Total_Amount'])."</td></tr>";
                   $count++;
                   $OutpatientTotal_Amount +=$row['Total_Amount'];
				   
               }


            ?>
            <tr>
                <td colspan="3"  style='text-align:right;'><b>Total Amount:</b></td>
                <td style='text-align: right;'><b><?=number_format($OutpatientTotal_Amount);?></b></td>
            </tr>
           
	<tr><td colspan='4'><b><hr></b></td></tr>
	<tr><td colspan='3' style='text-align:right;'><b>Grand Total:</b></td><td style='text-align: right;'><b><?=number_format($OutpatientTotal_Amount);?></b></td></tr>
	<tr><td colspan='4'><b><hr></b></td></tr>
</table>
        <table style="width: 80%;font-size: 15px;">
            <tr>
                <td>
                <table class="table">
            <tr>
                <th>NAME  </th>
                <th><?=$Employee_Name ?></th>
            
                <th>DESIGNATION</th>
                <th><?=$Employee_Title ?></th>
            </tr>
            <tr>
                <th>CONTACT: </th>
                <th><?=$Phone_Number ?></th>
            
                <th colspan="">DATE </th>
                <th><?php echo $invoice_date; ?></th>
            </tr>
            <tr>
                <th colspan="4">Signature:   <?php echo $signature; ?></th>
                
            </tr>
            
        </table>
                </td>
                <td width='13%'> 
                    <table>
                            <tr>
                                <th width='100%'>
                                <img src='images/stamp.png' width='100' height='100' style='float:left;'>
                                </th>
                            </tr>
                            
                    </table>
                </td>
            </tr>
        </table>
<?php }

if(isset($_POST['Item_Category_ID'])){
    $month = $_POST['month'];
    $year = $_POST['year'];
    $sponsor_id = $_POST['sponsor_id'];
    $Item_Category_ID = $_POST['Item_Category_ID'];
    if($sponsor_id != 'All'){
        $filter  =" AND pp.Sponsor_ID='$sponsor_id'";
    }else{
        $filter="";
    }
    
    $htm.= "<table  class='table table-condensed table-hover table-stripped'>";
    $category = mysqli_query($conn, "SELECT * FROM  tbl_item_subcategory  WHERE Item_Category_ID='$Item_Category_ID' AND enabled_disabled='enabled' ") or die(mysqli_error($conn));
    
    $num=1;
    $Gran_GrandTotalAmount=0;
    if(mysqli_num_rows($category)>0){
        while($row = mysqli_fetch_assoc($category)){
            $Item_Subcategory_ID = $row['Item_Subcategory_ID'];
            $Item_Subcategory_Name = $row['Item_Subcategory_Name'];
            $htm.="<tr><td  style='background-color:#ccc;'>$num</td><th style='background-color:#ccc;'>$Item_Subcategory_Name</th></tr>";
            $htm.="<tr><td colspan='2'><table class='table table-condensed table-hover table-stripped'>
            <tr><td><b>#</b></td><td><b>Service Name</b></td><td><b>Quantity</b></td><td><b>Amount</b></td></tr>";
            $items =mysqli_query($conn, "SELECT i.Item_ID,Product_Name, COUNT(*) AS Quantity, sum((ppl.price - ppl.discount) * ppl.quantity) AS Total_Amount  FROM tbl_items i,  tbl_patient_payments pp, tbl_patient_payment_item_list ppl, tbl_check_in ci, tbl_bills b WHERE i.Item_Subcategory_ID = '$Item_Subcategory_ID'  AND i.Item_ID = ppl.Item_ID and ppl.Status<>'removed' AND pp.Patient_Payment_ID = ppl.Patient_Payment_ID AND pp.bill_id = b.Bill_ID AND ci.Check_In_ID = pp.Check_In_ID AND MONTHNAME(ci.Visit_Date) = '$month'  AND YEAR(ci.Visit_Date) = '$year'  AND b.e_bill_delivery_status = 1 AND  pp.Billing_Type IN ('Inpatient Credit','Outpatient Credit') $filter AND pp.Bill_ID IS NOT NULL GROUP BY i.Item_ID") or die(mysqli_error($conn));
            $i=1;
            $Quantity =0;
            $GrandTotalAmount =0;
            if(mysqli_num_rows($items)>0){
                while($rows = mysqli_fetch_assoc($items)){
                    $Product_Name =$rows['Product_Name'];
                    $Quantity = $rows['Quantity'];
                    $Total_Amount= $rows['Total_Amount'];
                    $htm.="<tr><td>$i</td><td>$Product_Name</td><td>".number_format($Quantity)."</td><td style='text-align:right;'>".number_format($Total_Amount)."</td></tr>";
                    $GrandTotalAmount +=$Total_Amount; 
                    $i++;
                }
            }
            $htm.=" <tr><td colspan='3'><b>Grand Total</b></td><td style='text-align:right;'><b>".number_format($GrandTotalAmount)."</b></td></tr>
            </table></td></tr>";
            $num++;
            $Gran_GrandTotalAmount +=$GrandTotalAmount;
        }
    }
    $htm.= "<tr><td><b>Total </b></td><td style='text-align:right;'><b>".number_format($Gran_GrandTotalAmount)."</b></td></tr></table>
    <input type='button' class='btn btn-info btn-sm' value='PRINT PDF' onclick='previewBycategory($Item_Category_ID)'>";

    echo $htm;

}