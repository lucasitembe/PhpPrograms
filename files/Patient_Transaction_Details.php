<?php
    session_start();
    include("./includes/connection.php");
    $temp = 0;
    $Grand_Total = 0;
    if(!isset($_SESSION['userinfo'])){
    	@session_destroy();
    	header("Location: ../index.php?InvalidPrivilege=yes");
    }

    if(isset($_GET['Transaction_ID'])){
        $Transaction_ID = $_GET['Transaction_ID'];
    }else{
        $Transaction_ID = 0;
    }

    if(isset($_GET['Source'])){
        $Source = $_GET['Source'];
    }else{
        $Source = 'Reception';
    }

    //get patient details
    $select = mysqli_query($conn,"select pr.Registration_ID, Patient_Name, Payment_Code, Guarantor_Name, Amount_Required, pr.Phone_Number, tc.Transaction_Status, tc.Transaction_Date_Time, emp.Employee_Name from 
                            tbl_bank_transaction_cache tc, tbl_sponsor sp, tbl_patient_registration pr, tbl_employee emp where
                            tc.Registration_ID = pr.Registration_ID and
                            emp.Employee_ID = tc.Employee_ID and
                            pr.Sponsor_ID = sp.Sponsor_ID and
                            Transaction_ID = '$Transaction_ID'") or die(mysqli_error($conn));
    $num = mysqli_num_rows($select);
    if($num > 0){
        while ($data = mysqli_fetch_array($select)) {
            $Patient_Name = $data['Patient_Name'];
            $Payment_Code = $data['Payment_Code'];
            $Guarantor_Name = $data['Guarantor_Name'];
            $Amount_Required = $data['Amount_Required'];
            $Phone_Number = $data['Phone_Number'];
            $Registration_ID = $data['Registration_ID'];
            $Transaction_Status = $data['Transaction_Status'];
            $Transaction_Date_Time = $data['Transaction_Date_Time'];
            $Employee_Name = ucwords(strtolower($data['Employee_Name']));
        }
    }else{
        $Patient_Name = '';
        $Payment_Code = '';
        $Guarantor_Name = '';
        $Amount_Required = '';
        $Phone_Number = '';
        $Registration_ID = '';
        $Transaction_Status = '';
        $Transaction_Date_Time = '';
        $Employee_Name = '';
    }
?>
<fieldset>
    <center>
        <table width = 100%>
            <tr>
                <td width="12%" style="text-align: right;">Patient Name</td>
                <td><input type="text" value="<?php echo $Patient_Name; ?>" readonly="readonly"></td>
                <td width="12%" style="text-align: right;">Registration Number</td>
                <td><input type="text" value="<?php echo $Registration_ID; ?>" readonly="readonly"></td>
                <td width="12%" style="text-align: right;">Phone Number</td>
                <td><input type="text" value="<?php echo $Phone_Number; ?>" readonly="readonly"></td>
                <td width="12%" style="text-align: right;">Sponsor Name</td>
                <td><input type="text" value="<?php echo $Guarantor_Name; ?>" readonly="readonly"></td>
            </tr>
            <tr>
                <td style="text-align: right;">Amount Required</td>
                <td><input type="text" value="<?php echo number_format($Amount_Required); ?>" readonly="readonly"></td>
                <td style="text-align: right;">Payment Code</td>
                <td><input type="text" value="<?php echo $Payment_Code; ?>" readonly="readonly"></td>
                <td style="text-align: right;">Date</td>
                <td><input type="text" value="<?php echo $Transaction_Date_Time; ?>" readonly="readonly"></td>
                <td style="text-align: right;">Prepared By</td>
                <td><input type="text" value="<?php echo $Employee_Name; ?>" readonly="readonly"></td>
            </tr>
        </table>
    </center>
</fieldset>

<fieldset style='overflow-y: scroll; height: 180px; background-color:white;'>
    <table width="100%">
        <tr><td colspan="8"><hr></td></tr>
        <tr>
            <td width="3%"><b>SN</b></td>
            <td  width="7%"><b>CHECK-IN</b></td>
            <td  width="9%"><b>LOCATION</b></td>
            <td><b>ITEM NAME</b></td>
            <td width="7%" style="text-align: right;"><b>PRICE</b></td>
            <td width="7%" style="text-align: right;"><b>DISCOUNT</b></td>
            <td width="7%" style="text-align: right;"><b>QUANTITY</b></td>
            <td width="7%" style="text-align: right;"><b>SUB TOTAL</b></td>
        </tr>
        <tr><td colspan="8"><hr></td></tr>
    <?php

        if(strtolower($Source) == 'reception'){
            $select = mysqli_query($conn,"select *
                                from tbl_bank_items_detail_cache cc, tbl_items i where
                                i.Item_ID = cc.Item_ID and
                                Transaction_ID = '$Transaction_ID'") or die(mysqli_error($conn));
            $num = mysqli_num_rows($select);

            if($num > 0){
                while ($data = mysqli_fetch_array($select)) {
                    $Grand_Total += (($data['Price'] - $data['Discount']) * $data['Quantity']);
?>
                    <tr>
                        <td><?php echo ++$temp.'.'; ?></td>
                        <td><?php echo $data['Check_In_Type']; ?></td>
                        <td><?php echo $data['Patient_Direction']; ?></td>
                        <td><?php echo $data['Product_Name']; ?></td>
                        <td style="text-align: right;"><?php echo number_format($data['Price']); ?></td>
                        <td style="text-align: right;"><?php echo number_format($data['Discount']); ?></td>
                        <td style="text-align: right;"><?php echo $data['Quantity']; ?></td>
                        <td style="text-align: right;"><?php echo number_format(($data['Price'] - $data['Discount']) * $data['Quantity']); ?></td>
                    </tr>
<?php
                }
                echo '<tr><td colspan="8"><hr></td></tr>';
                echo '<tr><td colspan="7" style="text-align: right;"><b>GRAND TOTAL</b></td><td style="text-align: right;"><b>'.number_format($Grand_Total).'</b></td></tr>';
                echo '<tr><td colspan="8"><hr></td></tr>';
            }
        }else if(strtolower($Source) == 'revenue center'){
            $select = mysqli_query($conn,"select ilc.Check_In_Type, i.Product_Name, ilc.Price, ilc.Quantity, ilc.Edited_Quantity, ilc.Discount from
                                    tbl_item_list_cache ilc, tbl_items i where
                                    i.Item_ID = ilc.Item_ID and
                                    ilc.Transaction_ID = '$Transaction_ID'") or die(mysqli_error($conn));
            if($num > 0){
                $Qty = 0;
                while ($data = mysqli_fetch_array($select)) {
                    //get quantity
                    if($data['Edited_Quantity'] > 0){
                        $Qty = $data['Edited_Quantity'];
                    }else{
                        $Qty = $data['Quantity'];
                    }
                    //generate grand total
                    $Grand_Total += (($data['Price'] - $data['Discount']) * $Qty);
?>
                    <tr>
                        <td><?php echo ++$temp.'.'; ?></td>
                        <td><?php echo $data['Check_In_Type']; ?></td>
                        <td><?php echo $data['Check_In_Type']; ?></td>
                        <td><?php echo $data['Product_Name']; ?></td>
                        <td style="text-align: right;"><?php echo number_format($data['Price']); ?></td>
                        <td style="text-align: right;"><?php echo number_format($data['Discount']); ?></td>
                        <td style="text-align: right;"><?php echo $Qty; ?></td>
                        <td style="text-align: right;"><?php echo number_format(($data['Price'] - $data['Discount']) * $Qty); ?></td>
                    </tr>
<?php
                }
                echo '<tr><td colspan="8"><hr></td></tr>';
                echo '<tr><td colspan="7" style="text-align: right;"><b>GRAND TOTAL</b></td><td style="text-align: right;"><b>'.number_format($Grand_Total).'</b></td></tr>';
                echo '<tr><td colspan="8"><hr></td></tr>';
            }
        }
    ?>
    </table>
</fieldset><br/>
<table width="100%">
    <tr>
        <td style="text-align: right;">
            <b>
            <?php
                if(strtolower($Transaction_Status) == 'completed'){
                    echo 'TOTAL AMOUNT PAID ~ '.number_format($Grand_Total);
                }else{
                    echo 'TOTAL AMOUNT REQUIRED ~ '.number_format($Grand_Total);
                }
            ?></b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        </td>
    </tr>
</table>