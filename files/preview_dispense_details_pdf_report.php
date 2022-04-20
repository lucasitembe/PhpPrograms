<?php
session_start();
include("./includes/connection.php"); 
include("calculate_buying_price.php");
    $temp = 1;
    //get current date and time
    $sql_date_time = mysqli_query($conn,"select now() as Date_Time ") or die(mysqli_error($conn));
    while($date = mysqli_fetch_array($sql_date_time)){
        $Current_Date_Time = $date['Date_Time'];
    }
    $Filter_Value = substr($Current_Date_Time,0,11);
    
    if(isset($_GET['Start_Date'])){
        $Start_Date = $_GET['Start_Date'];
    }else{
        $Start_Date = $Filter_Value;
    }
    
    
    if(isset($_GET['End_Date'])){
        $End_Date = $_GET['End_Date'];
    }else{
        $End_Date = $Filter_Value;
    }

    if(isset($_SESSION['Storage'])){
        $Sub_Department_Name = $_SESSION['Storage'];
    }else{
        $Sub_Department_Name = '';
    }

    if(isset($_GET['Item_ID'])){
        $Item_ID = $_GET['Item_ID'];
    }else{
        $Item_ID = 0;
    }
    //get item name
    $sql_get_item = mysqli_query($conn,"select Product_Name from tbl_items where Item_ID = '$Item_ID'") or die(mysqli_error($conn));
    $no = mysqli_num_rows($sql_get_item);
    if($no > 0){
        while($r = mysqli_fetch_array($sql_get_item)){
            $Item_Name = $r['Product_Name'];
        }
    }else{
        $Item_Name = '';
    }
    $filter = '';
    if(isset($_GET['sponsorID']) && $_GET['sponsorID']!=''){
        $sponsorID = $_GET['sponsorID'];
        $filter .= " and ts.Sponsor_ID='$sponsorID'";
    }
    $Sub_Department_ID=$_GET['Sub_Department_ID'];
    $sub_dept=mysqli_fetch_assoc(mysqli_query($conn,"SELECT Sub_Department_Name FROM tbl_sub_department WHERE Sub_Department_ID=$Sub_Department_ID"))['Sub_Department_Name'];
    //////////////////////////////////////////////////////////
    /*
    function get_item_buying_price($Item_ID,$Dispense_Date_Time){
        $Sub_Department_ID = $_SESSION['Pharmacy_ID'];
        $select_list_of_buying_price_result=mysqli_query($conn,"SELECT Approval_Date_Time,Selling_Price,Last_Buying_Price FROM tbl_requisition req INNER JOIN tbl_requisition_items reqi ON req.Requisition_ID=reqi.Requisition_ID WHERE Store_Need='$Sub_Department_ID' AND Item_ID='$Item_ID' AND req.Requisition_Status='Received' ORDER BY Approval_Date_Time DESC") or die(mysqli_error($conn));
        if(mysqli_num_rows($select_list_of_buying_price_result)>0){
            while($buying_price_rows=mysqli_fetch_assoc($select_list_of_buying_price_result)){
                $Approval_Date_Time=$buying_price_rows['Approval_Date_Time'];
                $Selling_Price=$buying_price_rows['Selling_Price'];
                $Last_Buying_Price=$buying_price_rows['Last_Buying_Price'];
                if($Dispense_Date_Time<$Approval_Date_Time){
                    
                }else{
                    if($Selling_Price==0){
                      return $Last_Buying_Price;
                    }
                    if(isset($_GET['buying_selling_price'])&&$_GET['buying_selling_price']=="original_buying_price"){
                        return $Last_Buying_Price;
                    }else{
                       return $Selling_Price;  
                    }
                }
            }
        }
        return "not_seted";
     }
    */
$htm  = "<table width ='100%' height = '30px'>";
$htm .= "<tr> <td> <img src='./branchBanner/branchBanner.png' width=100%> </td> </tr>";
$htm .= "<tr><td style='text-align:center;'>Dispensed Medicatioin Details From {$Start_Date} To {$End_Date}</td></tr>";
$htm .="<tr><td><b>Location:</b> {$sub_dept}</td></tr>";
$htm .= "</table><br/>";

$htm .= "<table width ='100%'  border='1' style='border-collapse: collapse; ' cellpadding=5 cellspacing=10>";
$htm.="<tr>";
$htm.="<td><b>SN</b></td>";
$htm.="<td><b>PATIENT NAME</b></td>";
$htm.="<td><b>PATIENT NUMBER</b></td>";
$htm.="<td><b>SPONSOR</b></td>";
$htm.="<td><b>PHONE NUMBER</b></td>";
$htm.="<td><b>DISPENSED DATE & TIME</b></td>";
$htm.="<td><b>QUANTITY</b></td>";
$htm.="<td><b>BUYING PRICE</b></td>";
$htm.="<td><b>TOTAL BUYING PRICE</b></td>";
$htm.="<td><b>SELLING PRICE</b></td>";
$htm.="<td><b>TOTAL SELLING PRICE</b></td>";
$htm.="<td><b>PROFIT / LOSS</b></td>";
$htm.="<td><b>RECEIPT NUMBER</b></td>";

        $worksheetTitle='';            
                $Sub_Department_ID = $_SESSION['Pharmacy_ID'];
                         if(isset($_GET['Sub_Department_ID'])){
                                $Sub_Department_ID=$_GET['Sub_Department_ID'];
                            }
                $sql_select = mysqli_query($conn,"select ilc.Dispense_Date_Time,ilc.price,Last_Buy_Price,i.Product_Name, pr.Phone_Number,pc.Billing_Type, ts.Guarantor_Name, pr.Registration_ID, ilc.Dispense_Date_Time, pr.Patient_Name, ilc.Patient_Payment_ID, ilc.Quantity, ilc.Edited_Quantity from
                                            tbl_payment_cache pc, tbl_item_list_cache ilc, tbl_patient_registration pr,tbl_sponsor ts, tbl_items i where pr.Sponsor_ID = ts.Sponsor_ID and
                                            pc.Payment_Cache_ID = ilc.Payment_Cache_ID and
                                            i.Item_ID = ilc.Item_ID and
                                            pr.Registration_ID = pc.Registration_ID and
                                            ilc.Dispense_Date_Time between '$Start_Date' and '$End_Date' and
                                            ilc.Status = 'dispensed' and
                                            ilc.Sub_Department_ID = '$Sub_Department_ID' and
                                            i.Item_ID = '$Item_ID' $filter") or die(mysqli_error($conn));
           

                $num_rows = mysqli_num_rows($sql_select);
                $grand_total_buying_price=0;
                $grand_total_selling_price=0;
                $grand_total_profit_or_loss=0;
                $total_buying_price=0;
                $total_seling_price=0;
                if($num_rows > 0) {
                    while($row = mysqli_fetch_array($sql_select)){
                        $price=$row['price'];
//                        $Last_Buy_Price=$row['Last_Buy_Price'];
                    $Dispense_Date_Time = $row['Dispense_Date_Time'];
                    $Last_Buy_Price=get_item_buying_price($Item_ID,$Dispense_Date_Time);
                     if($Last_Buy_Price=="not_seted"){
                        $Last_Buy_Price = $row['Last_Buy_Price']; 
                     }
                        $ispenced_quantity=0;
                         if($row['Edited_Quantity'] != 0 && $row['Edited_Quantity'] != null && $row['Edited_Quantity'] != ''){
                            $ispenced_quantity=$row['Edited_Quantity'];
                        }else{
                            $ispenced_quantity=$row['Quantity'];
                        }
                $quantity=($row['Edited_Quantity'] != 0 && $row['Edited_Quantity'] != null && $row['Edited_Quantity'] != '')?$row['Edited_Quantity']:$row['Quantity'];
                $profit_loss=(($price*$ispenced_quantity)-($Last_Buy_Price*$ispenced_quantity));
                
$htm.="<tr>";
$htm.="<td>{$temp}</td>";
$htm.="<td>".ucwords(strtolower($row['Patient_Name']))."</td>";
$htm.="<td>".$row['Registration_ID']."</td>";
$htm.="<td>".$row['Guarantor_Name']."</td>";
$htm.="<td>".$row['Phone_Number']."</td>";
$htm.="<td>".$row['Dispense_Date_Time']."</td>";
$htm.="<td style='text-align:center;'>".$quantity."</td>";
$htm.="<td style='text-align:right;'>".number_format($Last_Buy_Price)."</td>";
$htm.="<td style='text-align:right;'>".number_format(($Last_Buy_Price*$ispenced_quantity))."</td>";
$htm.="<td style='text-align:right;'>".number_format($price)."</td>";
$htm.="<td style='text-align:right;'>".number_format(($price*$ispenced_quantity))."</td>";
$htm.="<td style='text-align:right;'>".number_format($profit_loss)."</td>";
$htm.="<td>".$row['Patient_Payment_ID']."</td>";
$htm.="</tr>";
                $total_buying_price+=$Last_Buy_Price;
                $total_seling_price+=$price;
                $grand_total_buying_price+=($Last_Buy_Price*$ispenced_quantity);
                $grand_total_selling_price+=($price*$ispenced_quantity);
                $grand_total_profit_or_loss+=(($price*$ispenced_quantity)-($Last_Buy_Price*$ispenced_quantity));
                $temp++;
                }
               
    $htm.="<tr>";
    $htm.="<td colspan='6' style='text-align:center;'>GRAND TOTAL</td>";
    $htm.="<td> </td>";
    $htm.="<td style='text-align:right;'>".number_format($total_buying_price)."</td>";
    $htm.="<td style='text-align:right;'>".number_format($grand_total_buying_price)."</td>";
    $htm.="<td style='text-align:right;'>".number_format($total_seling_price)."</td>";
    $htm.="<td style='text-align:right;'>".number_format($grand_total_selling_price)."</td>";
    $htm.="<td style='text-align:right;'>".number_format($grand_total_profit_or_loss)."</td>";
    $htm.="<td> </td>";
    $htm.="<tr>";
            }
$htm.="</table>";
    $Employee_Name = $_SESSION['userinfo']['Employee_Name'];
    include("MPDF/mpdf.php");
    $mpdf = new mPDF('s', 'A4-L');
    $mpdf->SetFooter('Printed By '.ucwords(strtolower($Employee_Name)).'  {DATE d-m-Y}|Page {PAGENO} of {nb}| Powered By GPITG LTD');
    $mpdf->WriteHTML($stylesheet, 1); // The parameter 1 tells that this is css/style only and no body/html/text
    $mpdf->WriteHTML($htm);
    $mpdf->Output('dispence_detail_report.pdf','I');
?>