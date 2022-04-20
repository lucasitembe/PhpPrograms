<?php
include("./includes/connection.php");
@session_start();
$Employee_logedin_id=$_SESSION['userinfo']['Employee_ID'];
// Turn off all error reporting
 error_reporting(!E_NOTICE || !E_WARNING);
$htm = "";

?>

<?php
if(isset($_GET['Patient_Payment_ID']))
        $Patient_Payment_ID = $_GET['Patient_Payment_ID'];

//        $sql_select_receipt_items_result = mysqli_query($conn,"SELECT Edited_Quantity,Check_In_Type,Category,Item_ID,Discount,Price,Quantity,Patient_Direction,Consultant,Consultant_ID,"
//                . "Sub_Department_ID,Clinic_ID,finance_department_id,clinic_location_id FROM tbl_item_list_cache "
//              . "WHERE Patient_Payment_ID='$Patient_Payment_ID' AND Status='paid' AND Item_ID NOT IN (SELECT Item_ID FROM tbl_patient_payment_item_list WHERE Patient_Payment_ID = '$Patient_Payment_ID')") or die(mysqli_error($conn));
//        if (mysqli_num_rows($sql_select_receipt_items_result) > 0) {
//            while ($receipt_item_rows = mysqli_fetch_assoc($sql_select_receipt_items_result)) {
//                $Check_In_Type = $receipt_item_rows['Check_In_Type'];
//               $Category = $receipt_item_rows['Category'];
//                $Item_ID = $receipt_item_rows['Item_ID'];
//                $Discount = $receipt_item_rows['Discount'];
//                $Price = $receipt_item_rows['Price'];
//                $Quantity = $receipt_item_rows['Quantity'];
//               $Edited_Quantity = $receipt_item_rows['Edited_Quantity'];
//                $Patient_Direction = $receipt_item_rows['Patient_Direction'];
//                $Consultant = $receipt_item_rows['Consultant'];
//                $Consultant_ID = $receipt_item_rows['Consultant_ID'];
//                $Sub_Department_ID = $receipt_item_rows['Sub_Department_ID'];
//                $Clinic_ID = $receipt_item_rows['Clinic_ID'];
//                $finance_department_id = $receipt_item_rows['finance_department_id'];
//                $clinic_location_id = $receipt_item_rows['clinic_location_id'];
//                
//                if ($Edited_Quantity > 0) {
//                    $Quantity = $Edited_Quantity;
 //               }

                //create receipt item
//                $sql_select_receipt_item_result = mysqli_query($conn,"INSERT INTO tbl_patient_payment_item_list(Check_In_Type,Category,Item_ID,Discount,Price,Quantity,Patient_Direction,Consultant,Consultant_ID,Clinic_ID,Patient_Payment_ID,Sub_Department_ID,finance_department_id,clinic_location_id,Transaction_Date_And_Time) VALUES('$Check_In_Type','$Category','$Item_ID','$Discount','$Price','$Quantity','$Patient_Direction','$Consultant','$Consultant_ID','$Clinic_ID','$Patient_Payment_ID','$Sub_Department_ID','$finance_department_id','$clinic_location_id',NOW())") or die("concultant=========================>>".mysqli_error($conn));
//                if (!$sql_select_receipt_item_result) {
//                }
 //           }
 //       }

///clear for item dublication
    // $sql_select_dublicate_item_result="SELECT MAX(Patient_Payment_Item_List_ID) AS Patient_Payment_Item_List_ID FROM `tbl_patient_payment_item_list` WHERE `Patient_Payment_ID`='$Patient_Payment_ID' GROUP BY Item_ID,`Quantity` HAVING COUNT(Item_ID)>1";
    // if(mysqli_query($conn,$sql_select_dublicate_item_result)){
    //     $sql_select_dublicate_item_result=mysqli_query($conn,$sql_select_dublicate_item_result);
    //     if(mysqli_num_rows($sql_select_dublicate_item_result)>0){
    //        while($dublicate_item_rows=mysqli_fetch_assoc($sql_select_dublicate_item_result)){
    //            $Patient_Payment_Item_List_ID=$dublicate_item_rows['Patient_Payment_Item_List_ID'];
    //            $sql_delete_dublicate_item_result=mysqli_query($conn,"DELETE FROM tbl_patient_payment_item_list WHERE Patient_Payment_Item_List_ID='$Patient_Payment_Item_List_ID'") or die(mysqli_error($conn));
    //        } 
    //     }
    // }else{
    //   $sql_select_dublicate_item_result=mysqli_query($conn,"SELECT MIN(Patient_Payment_Item_List_ID) AS Patient_Payment_Item_List_ID FROM `tbl_patient_payment_item_list` WHERE `Patient_Payment_ID`='$Patient_Payment_ID' GROUP BY Item_ID,`Quantity` HAVING COUNT(Item_ID)>1") or die(mysqli_error($conn));
    //    if(mysqli_num_rows($sql_select_dublicate_item_result)>0){
    //        while($dublicate_item_rows=mysqli_fetch_assoc($sql_select_dublicate_item_result)){
    //            $Patient_Payment_Item_List_ID=$dublicate_item_rows['Patient_Payment_Item_List_ID'];
    //            $sql_delete_dublicate_item_result=mysqli_query($conn,"DELETE FROM tbl_patient_payment_item_list WHERE Patient_Payment_Item_List_ID='$Patient_Payment_Item_List_ID'") or die(mysqli_error($conn));
    //        } 
    //     } 
    // }
        // check for the maximun number of receipt to print:
$select_receipt_limit =  "SELECT max_receipt FROM tbl_receipt_config";
if ($result_receipt_limit = mysqli_query($conn,$select_receipt_limit)) {
    while ($row = mysqli_fetch_assoc($result_receipt_limit)) {
    $receipt_max = $row['max_receipt'];    
}
}else{
    echo "<script>".mysqli_error($conn)."</scipt>";
}



// select the number of receipt already printed:
$select_printed_receipt_count = "SELECT receipt_count FROM tbl_patient_payments WHERE Patient_Payment_ID = '$Patient_Payment_ID'";
if ($result_printed_receipt = mysqli_query($conn,$select_printed_receipt_count)) {
    while ($row1 = mysqli_fetch_assoc($result_printed_receipt)) {
    $printed_receipt = $row1['receipt_count'];
}

echo "<script>This is not valid {$printed_receipt} receipt</script>";    
}else{
    echo "<script>".mysqli_error($conn)."</script>";
}


if ($printed_receipt >= $receipt_max) {
?>
    <input type="hidden" name="receipt" id="printed_receipt" value="<?=$printed_receipt?>">
    <input type="hidden" name="receiptp" id="max_receipt" value="<?=$max_receipt?>"> 
  <script type="text/javascript">
  var doc = document.getElementById("max_receipt").value;

  alert("You have reached maximum Recipt count " + doc)
  window.close();
  // location.replace("http://localhost:8080/march/final_one/files/employeeperfomancereport.php");
  </script>

<?php
}else{


    $Sselect_receipt_count = "SELECT receipt_count FROM tbl_patient_payments 
        WHERE Patient_Payment_ID = '$Patient_Payment_ID'";
            // $receipt_count = 0;
        if ($select_result = mysqli_query($conn,$Sselect_receipt_count)) {
            while ($rows = mysqli_fetch_assoc($select_result)) {
                $receipt_count = $rows['receipt_count'];
            }

            //update receipt count 
            $receipt_count = $receipt_count + 1;
            if ( ($update_receipt_count = mysqli_query($conn,"UPDATE tbl_patient_payments 
                        SET receipt_count='$receipt_count' WHERE Patient_Payment_ID ='$Patient_Payment_ID' "))){
                echo "<script>successfullt updated " .$receipt_count . "</script>";
                }else{ 
                    echo  "<script>".mysqli_error($conn)."</script>";
                }
             } else{
                echo "<script>".mysqli_error($conn)."</script>";
             }


}

$Employee_logged_Name = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Employee_Name FROM tbl_employee WHERE Employee_Id = '$Employee_logedin_id'"))['Employee_Name'];
if ($Employee_logged_Name){
    $Print_receipt = mysqli_query($conn, "INSERT INTO tbl_cashier_print_receipt (Patient_Payment_ID, Employee_ID, Transaction_date) VALUES ('$Patient_Payment_ID', '$Employee_logged_Name', NOW())");
}


?>
<script type="text/javascript">
// var doc = document.getElementById("max_receipt").value;
// var doc1 = document.getElementById("printed_receipt").value;
// if (doc1 >= doc) {
    // alert("malopa" + doc + doc1)

// };
</script>
<?php
$isReceipt = false;
                $q = mysqli_query($conn,"SELECT * FROM tbl_printer_settings") or die(mysqli_error($conn));
                $row = mysqli_fetch_assoc($q);
                $exist = mysqli_num_rows($q);
                if ($exist > 0) {
                    $Paper_Type = $row['Paper_Type'];
                    $Include_Sponsor_Name_On_Printed_Receipts = $row['Include_Sponsor_Name_On_Printed_Receipts'];
                    if ($Paper_Type == 'Receipt') {
                        $isReceipt = true;
                        echo "Sponsor name: " +$Include_Sponsor_Name_On_Printed_Receipts;
                    }
                }else{
                    $Include_Sponsor_Name_On_Printed_Receipts = 'yes';
                }
   
    if(!$isReceipt){  //Not receipt
        if(isset($_GET['Patient_Payment_ID'])){
		$Patient_Payment_ID = $_GET['Patient_Payment_ID'];
	}else{
		$Patient_Payment_ID = '';
	}



	if(isset($_GET['Registration_ID'])){
		$Registration_ID = $_GET['Registration_ID'];
	}else{
		$Registration_ID = '';
	}
        include 'otherreciepttype.php';
    }   else{         
?>

<!DOCTYPE html >
<html>

    <head>
        <meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />

        <title>EHMS SYSTEM:: PATIENT RECEIPT</title>

        <style>
            body {

                font-family:courier;
                font-size:12px; 
                font-weight:bold; 

            }
            table{
                font-family:courier;
                font-weight: normal;
                font-weight:600;
            }




        </style>
    </head>

    <body>
        <div id="page-wrap"  style="font-weight:bold">

            <div id="identity">
                <center>
                    <?php
                    include("./includes/reportheaderreceipt.php");
                    echo $htm;
                    $htm = '';
                    ?>
                </center> 
                <div style="clear:both"></div>

                <?php
                if (isset($_GET['Patient_Payment_ID'])) {
                    $Patient_Payment_ID = $_GET['Patient_Payment_ID'];
                } else {
                    $Patient_Payment_ID = 0;
                }
                $total = 0;

                $datetime = mysqli_fetch_assoc(mysqli_query($conn,"SELECT NOW() AS TODAYD"))['TODAYD'];
                //select printing date and time


                echo '<div id="customer" style="clear:both;">';


                $select_Transaction_Items = mysqli_query($conn,
                        "SELECT pp.Pre_Paid,pp.Employee_ID,pp.Billing_Type,pp.Payment_Date_And_Time,preg.Patient_Name,preg.Registration_ID,preg.Old_Registration_Number,Guarantor_Name, sp.Exemption, pp.payment_type from  tbl_patient_payment_item_list ppl INNER JOIN tbl_patient_payments pp ON pp.Patient_Payment_ID = ppl.Patient_Payment_ID
                JOIN tbl_items t ON t.Item_ID = ppl.Item_ID
                JOIN tbl_employee emp ON emp.Employee_ID = pp.Employee_ID
                JOIN tbl_patient_registration preg ON preg.Registration_ID = pp.Registration_ID
                JOIN tbl_sponsor sp ON sp.Sponsor_ID = preg.Sponsor_ID
                where
		    ppl.Patient_Payment_ID = '$Patient_Payment_ID' limit 1");



                echo '<table width="100%" border="0" cellpadding="0" cellspacing="0">';
                while ($row = mysqli_fetch_array($select_Transaction_Items)) {
                    $Pre_Paid = $row['Pre_Paid'];
                    //echo $row['Employee_ID'];exit;
                    echo '<tr><td colspan="5"><hr height="2px"></td></tr>';
                    //SELECT BILLING TYPE
                    if (strtolower($row['Billing_Type']) == 'outpatient cash' || (strtolower($row['Billing_Type']) == 'inpatient cash' && strtolower($row['payment_type']) == 'pre')) {
                        $Billing_Type = 'Cash';
                    } elseif (strtolower($row['Billing_Type']) == 'outpatient credit' || strtolower($row['Billing_Type']) == 'inpatient credit' || (strtolower($row['Billing_Type']) == 'inpatient cash' && strtolower($row['payment_type']) == 'post')) {
                        $Billing_Type = 'Credit';
                    }

                    
                    //select the id of employee who made the transaction
                    $Employee_ID = $row['Employee_ID'];
                    $Payment_Date_And_Time = $row['Payment_Date_And_Time'];
                    echo ' <tr>
                       <td colspan="2">Name: ' . $row['Patient_Name'] . ' </td></tr>';
                    echo '
                    <tr>
                      <td>
                        Patient #: ' . $row['Registration_ID'] . ' 
                      </td>
                       <td>
                        Old #: ' . $row['Old_Registration_Number'] . ' 
                      </td>
                      <tr>
                      <td>';
                      if($Billing_Type == 'Credit'){
                        echo '<b>DEDIT NOTE NUMBER :</b> ' . $Patient_Payment_ID . ' </td>';
                      }else{
                        echo 'Receipt Number: ' . $Patient_Payment_ID . ' </td>';
                      }
                        
                      if(strtolower($Include_Sponsor_Name_On_Printed_Receipts) == 'no'){
                        echo '<td>&nbsp;</td>';
                      }else{
                        echo '<td>Sponsor: ' . $row['Guarantor_Name'] . '</td>';
                      }
                    echo '</tr>
                    <tr>
                      <td>
                         Mode: ' . $row['Billing_Type'] . ' 
                      </td>
                       <td>
                       Date: ' . $Payment_Date_And_Time . ' 
                      </td>
                      
                    </tr>
                    ';

                    if($Pre_Paid == 1 && strtolower($Billing_Type) == 'cash'){
                        echo '<tr><td colspan="4" style="text-align: center;"><h3>PRE / POST PAID TRANSACTION RECEIPT</h3></td></tr>';
                    }
                }
                echo '</table>';

                echo '</div>';
                $select_category = mysqli_query($conn,"select * from tbl_item_category") or die(mysqli_error($conn));


                $subtotal = 0;
                $total = 0;
//  while($rowCat = mysqli_fetch_array($select_category)){
//       $catName=$rowCat['Item_Category_Name'];
//	   $catID=$rowCat['Item_Category_ID'];
                $select_Transaction_Items = mysqli_query($conn,"select * from
			    tbl_employee emp, tbl_patient_registration preg,
				tbl_patient_payments pp, tbl_patient_payment_item_list ppl,
				tbl_items t, tbl_item_subcategory ts, tbl_item_category ic
				where emp.employee_id = pp.employee_id and
				preg.registration_id = pp.registration_id and
				pp.patient_payment_id = ppl.patient_payment_id and
				t.item_id = ppl.item_id and
				t.item_subcategory_id = ts.item_subcategory_id and
				ts.item_category_id = ic.item_category_id and
				pp.Patient_Payment_ID = '$Patient_Payment_ID'") or die(mysqli_error($conn));
//if($Billing_Type == 'Cash'){
                if (mysqli_num_rows($select_Transaction_Items) > 0) {
                    echo '<table id="items" width="100%" border="0" cellpadding="0" cellspacing="0">';


                    echo "<tr><td colspan='4'><hr height='3px'></td></tr>";
                    echo '<tr>';
                    echo "<th width='4%' style='text-align:left;'>Qty</th>";
                    echo "<th style='text-align:center;'>Item</th>";
                    echo "<th style='text-align:right;'>Total</th>";
                    echo "</tr>";
                    echo "<tr><td colspan='10'><hr height='3px'></td></tr>";

                    while ($row = mysqli_fetch_array($select_Transaction_Items, MYSQL_BOTH)) {

                        echo '<tr class="item-row">';
                        echo '<td class="qty" width="4%">' . $row['Quantity'] . '</td>';
                        if ($isReceipt) {
                            echo "<td class='description'>" . substr($row['Product_Name'], 0, 16) . " </td>";
                        } else {
                            echo "<td class='description'>" . $row['Product_Name'] . " </td>";
                        }
                        //echo '<td class="description">'.$row['Product_Name'].'</td>';
                        //echo '<td class="cost">'.$row['Price'].'</td>';

                        echo '<td style="text-align:right;"><span class="price" >' . (($_SESSION['systeminfo']['price_precision'] == 'yes') ? number_format(($row['Price'] - $row['Discount']) * $row['Quantity'], 2) : number_format(($row['Price'] - $row['Discount']) * $row['Quantity']));
                        echo '</td>'
                        . '
                                       </tr>';
                        $subtotal = $subtotal + (($row['Price'] - $row['Discount']) * $row['Quantity']);

                        // $total = $total + (($row['Price'] - $row['Discount'])*$row['Quantity'])."</i>";
                        echo "<tr><td width='11px' height='4px'></td></tr>";
                    }
                    echo "</table>";
                    //echo "<tr><td colspan=6 style='text-align: right;'>Sub Total : ".number_format(($row['Price'] - $row['Discount'])*$row['Quantity']);"
                }
                /* }else{
                  echo '<center><h4><b>Invalid cash receipt <br/><b>Invalid cash receipt <br/><b>Invalid cash receipt <br/><b>Invalid cash receipt <br/>You are not allowed to print debit note</b></h4><center>';
                  } */
                $htm = '';
                $total = $total + $subtotal;
                $subtotal = 0;
                //}
    //  echo  "<tr><td style='text-align: right;' colspan=7><strong><u> TOTAL : ".number_format(($row['Price']-$row['Discount'])*$row['Quantity']);"</u></strong></td></tr>";
                //echo "<hr/>";

                echo "<table width='100%' border='0' cellpadding='0' cellspacing='0'><tr><td colspan='4'><hr height='3px'></td></tr>";
                echo "<tr><td style='text-align: right;font-weight:bold;' colspan='4'><strong> TOTAL : " . (($_SESSION['systeminfo']['price_precision'] == 'yes') ? number_format($total, 2) : number_format($total)) . '  ' . $_SESSION['hospcurrency']['currency_code'] . "</strong></td></tr>";
                echo "<tr><td colspan='4'><hr height='3px'></td></tr>";
                // Turn off all error reporting
                error_reporting(0);

                //select the name of the employee who made the transaction based on employee id we got above
                $select_Employee_Name = mysqli_query($conn,"select Employee_Name from tbl_employee where employee_id = '$Employee_ID'") or die(mysqli_error($conn));
                while ($row = mysqli_fetch_array($select_Employee_Name)) {
                    $Employee_Name = $row['Employee_Name'];
                }

                if ($isReceipt) {
                    echo '<tr class="enddesc">'
                    . '<td colspan="" style="text-align:left;">Prepared By: ' . $Employee_Name . ''
                    . '<td  style="text-align:right">&nbsp;&nbsp;<span style="font-size: small;">Sign</span></b>________________</td>'
                    . '</tr>';
                } else {
                    echo '<tr class="enddesc">'
                    . '<td colspan="" style="text-align:left;">Prepared By: ' . $Employee_Name . ''
                    . '<td  style="text-align:right">&nbsp;&nbsp;<span style="font-size: small;">Patient Signature</span></b>______________________________</td>'
                    . '</tr>';
                }


                echo '</table>';
                ?>

            </div></div>
<script>

// function returnHome(){
//     return false;
// }
    window.print(false);
    CheckWindowState();

    function PrintWindow() {
        window.print();
        CheckWindowState();
    }

    function CheckWindowState() {
        if (document.readyState == "complete") {
            window.close();
        } else {
            setTimeout("CheckWindowState()", 2000);
        }
    }
</script>


    </body>

</html>

    <?php }?>