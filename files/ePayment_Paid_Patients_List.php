<?php
session_start();
include("./includes/connection.php");
$temp = 0;
$temp2 = 0;
$Grand_Total = 0;

 $Date_From = '';
if (isset($_GET['Date_From'])) {
    $Date_From = $_GET['Date_From'];
} 

$Date_To = '';
if (isset($_GET['Date_To'])) {
    $Date_To = $_GET['Date_To'];
} 

$Report_Type = '';
if (isset($_GET['Report_Type'])) {
    $Report_Type = $_GET['Report_Type'];
}

$Payment_Mode = '';
if (isset($_GET['Payment_Mode'])) {
    $Payment_Mode = $_GET['Payment_Mode'];
} 

/*
* transacion mode (normal=online,offline, both)
*/
$trans_mode = '';
if (isset($_GET['trans_mode'])) {
    $trans_mode = $_GET['trans_mode'];
}

$limit = "LIMIT 500";
if (isset($_GET['number_recordes'])) {
    $number_recordes = $_GET['number_recordes'];
}

if ($number_recordes == '500_Rec')
    $limit = "LIMIT 500";
else if ($number_recordes == '300_Rec')
    $limit = "LIMIT 300";
else if ($number_recordes == '100_Rec')
    $limit = "LIMIT 100";
else if ($number_recordes == '50_Rec')
    $limit = "LIMIT 50";
else if ($number_recordes == 'All')
    $limit = "";


$cashier_id = '';
if(isset($_GET['cashier_id'])){
    $cashier_id = $_GET['cashier_id'];
}

$Terminal_ID='';
if (isset($_GET['Terminal_ID'])) {
    $Terminal_ID = $_GET['Terminal_ID'];
}

$filterterminal = '';
if (!empty($Terminal_ID) && $Terminal_ID != 'all') {
    $filterterminal = " AND ba.Terminal_ID='$Terminal_ID'";
}
if(!empty($trans_mode) && $trans_mode !='All'){
    $trans_mode = strtolower($trans_mode);
    if($trans_mode=='normal'){
        $filterterminal .= " AND (lower(ba.trans_type)='$trans_mode' OR ba.trans_type='')";
    } else {
        $filterterminal .= " AND lower(ba.trans_type)='$trans_mode'";
    } 
}

if (!empty($cashier_id) && $cashier_id != 'All') {
    $filterterminal .= " AND emp.Employee_ID='$cashier_id'";
}
?>
<legend align=right><b>ePayment Collections Reports ~ Paid Transactions</b></legend>
<center>
    <table width = 100%>
        <tr><td colspan="17"><hr></td></tr>
        <tr>
            <td width="5%"><b>SN</b></td>
            <td><b>PATIENT NAME</b></td>
            <td width="10%"><b>PATIENT NUMBER</b></td>
            <td width="12%"><b>SPONSOR</b></td>
            <td><b>TRANS MODE</b></td>
            <td width="10%" style="text-align: right;"><b>PREPARED DATE</b></td>
            <td width="10%" style="text-align: right;"><b>EMPLOYEE PREPARED</b></td>
            <td width="10%">&nbsp;&nbsp;&nbsp;<b>BILL NUMBER</b></td>
            <td width="10%"><b>TRANSACTION REF</b></td>
            <td width="10%"><b>TRANSACTION DATE</b></td>
            <td width="10%"><b>TERMINAL ID</b></td>
            <td width="10%"><b>MERCHANT ID</b></td>
            <td width="10%"><b>BATCH NO</b></td>
            <td width="10%"><b>AUTH NO</b></td>
            <td width="10%" style="text-align: right;"><b>AMOUNT</b></td>
            <td width="10%" style="text-align: right;">&nbsp;</td>
        </tr>
        <tr><td colspan="17"><hr></td></tr>
        <?php
        //$myTransIDs = array();

        $select = mysqli_query($conn,"select DISTINCT(Auth_No), tc.Source,trans_type, tc.Amount_Required,ba.Amount_Paid, pr.Patient_Name, tc.Transaction_ID, 
                            tc.Payment_Code, sp.Guarantor_Name, emp.Employee_Name, tc.Transaction_Date_Time, pr.Registration_ID,
                            ba.Transaction_Ref, ba.Transaction_Date,Terminal_ID,Merchant_ID,Batch_No,ba.P_ID
                            from tbl_bank_transaction_cache tc
                            JOIN tbl_patient_registration pr ON tc.Registration_ID = pr.Registration_ID 
                            JOIN tbl_sponsor sp ON pr.Sponsor_ID = sp.Sponsor_ID
                            JOIN tbl_employee emp ON emp.Employee_ID = tc.Employee_ID
                            JOIN tbl_bank_api_payments_details ba ON ba.Payment_Code=tc.Payment_Code
                            WHERE
                            ba.Transaction_Date between '$Date_From' and '$Date_To' AND
                            tc.Transaction_Status = 'Completed'
                            $filterterminal
                            order by ba.Transaction_Date desc $limit") or die(mysqli_error($conn));

        $num = mysqli_num_rows($select);
        if ($num > 0) {
            while ($data = mysqli_fetch_array($select)) {
                $Grand_Total += $data['Amount_Required'];
                $Transaction_ID = $data['Transaction_ID'];
                $Payment_Code = $data['Payment_Code'];
               // $myTransIDs[] = $Transaction_ID;

                
//                $qr = mysqli_query($conn,"select Price,Discount,Quantity
//                                from tbl_bank_items_detail_cache cc, tbl_items i where
//                                i.Item_ID = cc.Item_ID and
//                                Transaction_ID = '$Transaction_ID' ") or die(mysqli_error($conn));
//                $suCatTotal=0;
//                while ($data2 = mysqli_fetch_array($qr)) {
//                    $suCatTotal += (($data2['Price'] - $data2['Discount']) * $data2['Quantity']);
//                }
               
                //get Transaction_Ref & Transaction_Date

                $Transaction_Ref = $data['Transaction_Ref'];
                $Transaction_Date = $data['Transaction_Date'];
                $Terminal_ID = $data['Terminal_ID'];
                $Merchant_ID = $data['Merchant_ID'];
                $Batch_No = $data['Batch_No'];
                $Auth_No = $data['Auth_No'];
                ?>
                <tr>
                    <td><?php echo ++$temp; ?></td>
                    <td>
                        <label onclick="open_Dialog('<?php echo $Transaction_ID; ?>', '<?php echo $data['Source']; ?>')">
                            <?php echo ucwords(strtolower($data['Patient_Name'])); ?>
                        </label>
                    </td>
                    <td>
                        <label onclick="open_Dialog('<?php echo $Transaction_ID; ?>', '<?php echo $data['Source']; ?>')">
                            <?php echo $data['Registration_ID']; ?></label></td>
                    <td>
                        <label onclick="open_Dialog('<?php echo $Transaction_ID; ?>', '<?php echo $data['Source']; ?>')">
                            <?php echo $data['Guarantor_Name']; ?>
                        </label>
                    </td>
                    <td>
                        <label onclick="open_Dialog('<?php echo $Transaction_ID; ?>', '<?php echo $data['Source']; ?>')">
                            <?php
                            if($data['trans_type']!='' && strtolower($data['trans_type'])!='normal'){
                                echo strtoupper($data['trans_type']);
                            } else {
                                echo 'ONLINE';
                            }

                             //echo !empty($data['trans_type'])?  strtoupper($data['trans_type']) : 'Online'; ?>
                        </label>
                    </td>
                    <td style="text-align: right;">
                        <label onclick="open_Dialog('<?php echo $Transaction_ID; ?>', '<?php echo $data['Source']; ?>')">
                            <?php echo $data['Transaction_Date_Time']; ?>
                        </label>
                    </td>
                    <td style="text-align: right;">
                        <label onclick="open_Dialog('<?php echo $Transaction_ID; ?>', '<?php echo $data['Source']; ?>')">
                            <?php echo $data['Employee_Name']; ?>
                        </label>
                    </td>
                    <td>
                        <label onclick="open_Dialog('<?php echo $Transaction_ID; ?>', '<?php echo $data['Source']; ?>')">
                            &nbsp;&nbsp;&nbsp;<?php echo $data['Payment_Code']; ?>
                        </label>
                    </td>
                    <td>
                        <label onclick="open_Dialog('<?php echo $Transaction_ID; ?>', '<?php echo $data['Source']; ?>')">
                            <?php echo $Transaction_Ref; ?>
                        </label>
                    </td>
                    <td>
                        <label onclick="open_Dialog('<?php echo $Transaction_ID; ?>', '<?php echo $data['Source']; ?>')">
                            <?php echo $Transaction_Date; ?>
                        </label>
                    </td>
                    <td>
                        <label onclick="open_Dialog('<?php echo $Transaction_ID; ?>', '<?php echo $data['Source']; ?>')">
                            <?php echo $Terminal_ID; ?>
                        </label>
                    </td>
                    <td>
                        <label onclick="open_Dialog('<?php echo $Transaction_ID; ?>', '<?php echo $data['Source']; ?>')">
                            <?php echo $Merchant_ID; ?>
                        </label>
                    </td>
                    <td>
                        <label onclick="open_Dialog('<?php echo $Transaction_ID; ?>', '<?php echo $data['Source']; ?>')">
                            <?php echo $Batch_No; ?>
                        </label>
                    </td>
                    <td>
                        <label onclick="open_Dialog('<?php echo $Transaction_ID; ?>', '<?php echo $data['Source']; ?>')">
                            <?php echo $Auth_No; ?>
                        </label>
                    </td>
                    <td style="text-align: right;">
                        <label onclick="open_Dialog('<?php echo $Transaction_ID; ?>', '<?php echo $data['Source']; ?>')">
                            <?php echo number_format($data['Amount_Required'],2);?>
                        </label>
                    </td>
                    <td style="text-align: right;">
                        <button type="button" class="art-button-green" onclick="print_epayment('<?php echo $data['Payment_Code']; ?>', '<?php echo $data['P_ID']; ?>')">PRINT RECEIPT</button>
                    </td>
                    <td style="text-align: right;">
                        <input type="button" class="art-button-green" value="PRINT DETAIL RECEIPT" onclick="Print_Receipt_Payment('<?php echo $data['Payment_Code'] ?>')">
                    </td>
                </tr>
                <?php
            }
            echo '<tr><td colspan="17"><hr></td></tr>';
            echo '<tr><td colspan="14" style="text-align: left;"><b>GRAND TOTAL</b></td><td style="text-align: right;">' . number_format($Grand_Total,2) . '</td></tr>';
            echo '<tr><td colspan="17"><hr></td></tr>';
        } else {
            
        }

        $htm = '</table></center><br/><br/><br/>';
//        $htm .= '<tr><td width="5%"><b>SN</b></td>';
//        $htm .= '<td width="55%"><b>SUB CATEGORY NAME</b></td>';
//        $htm .= '<td width="15%" style="text-align: right;" colspan="2"><b>AMOUNT</b></td></tr>'
//                . '<tr><td colspan=5><hr/></td></tr>';
//        $getSubCategories = "SELECT Item_Subcategory_ID, Item_Subcategory_Name FROM tbl_item_subcategory";
//        $getSub = mysqli_query($conn,$getSubCategories) or die(mysqli_error($conn));
//
//        $grandTotoalSub = 0;
//        while ($rowSub = mysqli_fetch_array($getSub)) {
//            $Item_Subcategory_ID = $rowSub['Item_Subcategory_ID'];
//            $Item_Subcategory_Name = $rowSub['Item_Subcategory_Name'];
//
//            $suCatTotal = 0;
//
//            foreach ($myTransIDs as $key => $TransactionID) {
////                $query = "
////                   SELECT SUM((Price-Discount)*Quantity) AS AMOUNT FROM tbl_patient_payment_item_list pl 
////                   JOIN tbl_patient_payments pp ON pl.Patient_Payment_ID=pp.Patient_Payment_ID
////                   JOIN  tbl_items i ON pl.Item_ID=i.Item_ID
////                   WHERE pp.Payment_Code='$Payment_Code' AND i.Item_Subcategory_ID='$Item_Subcategory_ID'
////                  ";
//
//                $qr = mysqli_query($conn,"select Price,Discount,Quantity
//                                from tbl_bank_items_detail_cache cc, tbl_items i where
//                                i.Item_ID = cc.Item_ID and
//                                Transaction_ID = '$TransactionID'  AND i.Item_Subcategory_ID='$Item_Subcategory_ID'") or die(mysqli_error($conn));
//
//                while ($data = mysqli_fetch_array($qr)) {
//                    $suCatTotal += (($data['Price'] - $data['Discount']) * $data['Quantity']);
//                }
//
//                //$qr = mysqli_query($conn,$query) or die(mysqli_error($conn));
////                $fetchAm = mysqli_fetch_assoc($qr);
////                $suCatTotal += $fetchAm['AMOUNT'];
//            }
//
//            $grandTotoalSub += $suCatTotal;
//
//            if ($suCatTotal > 0) {
//				$htm .= '<tr><td>' . ++$temp2 . '</td>';
//				$htm .= '<td>' . ucwords(strtolower($Item_Subcategory_Name)) . '</td>';
//				$htm .= '<td style="text-align: right;"  colspan="2">' . number_format($suCatTotal,2) . '</td></tr>';
//           }
//        }
//		
//		 $qr2 = mysqli_query($conn,"select Amount_Required,tc.Transaction_ID
//                                from tbl_bank_transaction_cache tc
//								where tc.Transaction_ID IN  (".implode(',',$myTransIDs).") and
//								tc.Transaction_ID NOT IN  (select distinct(Transaction_ID) from tbl_bank_items_detail_cache)"
//								) or die(mysqli_error($conn));
//		
//		  $adjTotal=0;
//            while ($data3 = mysqli_fetch_assoc($qr2)) {
//                $adjTotal += $data3['Amount_Required'];
//                $Transaction_ID = $data3['Transaction_ID'];
//				
//	      /*   $htm .= '<tr><td>' . ++$temp2 . '</td>';
//            $htm .= '<td>' . ucwords(strtolower($Transaction_ID)) . '</td>';
//            $htm .= '<td style="text-align: right;"  colspan="2">' . number_format($data3['Amount_Required'],2) . '</td></tr>'; */
//			}
//            $htm .= '<tr><td>' . ++$temp2 . '</td>';
//            $htm .= '<td>' . ucwords(strtolower('Adjustiment')) . '</td>';
//            $htm .= '<td style="text-align: right;"  colspan="2">' . number_format($adjTotal,2) . '</td></tr>';
//			
//        $htm .= '<tr><td colspan="5"><hr></td></tr>';
//        $htm .= '<tr><td colspan="3" style="text-align: left;"><b>GRAND TOTAL</b></td><td style="text-align: right;">' . number_format($grandTotoalSub+$adjTotal,2) . '</td></tr>';
//        $htm .= '<tr><td colspan="5"><hr></td></tr>';
//        $htm .= '</table>';

        echo $htm;
        ?>
