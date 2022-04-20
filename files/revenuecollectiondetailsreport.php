<?php
    @session_start();
    include("./includes/connection.php");
    
    if(isset($_SESSION['userinfo']['Employee_Name'])){
        $Employee_Name = $_SESSION['userinfo']['Employee_Name'];
    }else{
        $Employee_Name = '';
    }

    if(isset($_GET['Date_From'])){
        $Date_From = $_GET['Date_From'];
    }else{
        $Date_From = '';
    }
    
    if(isset($_GET['Date_To'])){
        $Date_To = $_GET['Date_To'];
    }else{
        $Date_To = '';
    }
    
    if(isset($_GET['Billing_Type'])){
        $Billing_Type_Value = $_GET['Billing_Type'];
    }else{
        $Billing_Type_Value = '';
    }

    //generate billing type caption
    if(strtolower($Billing_Type_Value) == 'all'){
        $Billing_Type_Display = 'Outpatient & Inpatient';
    }else{
        $Billing_Type_Display = $Billing_Type_Value;
    }
    
    if(isset($_GET['Item_Category_ID'])){
        $Item_Category_ID = $_GET['Item_Category_ID'];
    }else{
        $Item_Category_ID = 0;
    }

    if(isset($_GET['Sponsor_ID'])){
        $Sponsor_ID = $_GET['Sponsor_ID'];
    }else{
        $Sponsor_ID = '';
    }

    //generate sponsor name caption
    $get_sponsor = mysqli_query($conn,"select Guarantor_Name from tbl_sponsor where Sponsor_ID = '$Sponsor_ID'") or die(mysqli_error($conn));
    $num = mysqli_num_rows($get_sponsor);
    if(isset($_GET['Sponsor_ID'])){
        if($Sponsor_ID == 0){
            $Guarantor_Name = 'All';
        }else{
            if($num > 0){
                while ($dt = mysqli_fetch_array($get_sponsor)) {
                    $Guarantor_Name = $dt['Guarantor_Name'];
                }
            }else{
                $Guarantor_Name = '';
            }
        }
        
    }else{
        $Guarantor_Name = '';
    }
        

    //get item category name
    if(isset($_GET['Item_Category_ID'])){
        if($Item_Category_ID == 0){
            $Item_Category_Name = 'All';
        }else{
            $get_category = mysqli_query($conn,"select Item_Category_Name from tbl_item_category where Item_Category_ID = '$Item_Category_ID'") or die(mysqli_error($conn));
            $num = mysqli_num_rows($get_category);
            if($num > 0){
                while ($data = mysqli_fetch_array($get_category)) {
                    $Item_Category_Name = $data['Item_Category_Name'];
                }
            }else{
                $Item_Category_Name = '';
            }
        }
    }else{
        $Item_Category_Name = '';
    }

    //select printing date and time
    $select_Time_and_date = mysqli_query($conn,"select now() as datetime");
    while($row = mysqli_fetch_array($select_Time_and_date)){
       $Date_Time = $row['datetime'];
    }

    $htm = "<table width ='100%' height = '30px'>
            <tr><td>
            <img src='./branchBanner/branchBanner.png'>
            </td></tr>
            <tr><td style='text-align: left;'><b>Revenue Collection Details</b></td></tr>
            <tr><td style='text-align: left;'><b>Start Date ~ </b>".$Date_From."</td></tr>
            <tr><td style='text-align: left;'><b>End Date ~ </b>".$Date_To."</td></tr>
            <tr><td style='text-align: left;'><b>Sponsor ~ </b>".$Guarantor_Name."</td></tr>
            <tr><td style='text-align: left;'><b>Category ~ </b>".$Item_Category_Name."</td></tr>
            <tr><td style='text-align: left;'><b>Bill Type ~ </b>".$Billing_Type_Display."</td></tr>
            </table><br/>";
?>

<center>
    <?php
        $temp = 0;
        $total_cash = 0;
        $total_credit = 0;
        $total_cancelled = 0;
        $sub_total_cash = 0;
        $sub_total_credit = 0;
        $sub_total_cancelled = 0;
        
        $grand_total_cash = 0;
        $grand_total_credit = 0;
        $grand_total_cancelled = 0;
        $general_grand_total = 0;
        $Quantity = 0;
        $Grand_Quantity = 0;

        $control = 'yes';

    //if billing type = all
    if(strtolower($Billing_Type_Value) == 'all'){

        if($Item_Category_ID == 0){
            //get all categories
            //if sponsor = 0 (all sponsor's transactions will be considered)
            if($Sponsor_ID == 0){
                $select_details = mysqli_query($conn,"select ic.Item_Category_ID, ic.Item_Category_Name from
                                        tbl_item_category ic, tbl_item_subcategory isu, tbl_items i, tbl_patient_payment_item_list ppl where
                                        ic.Item_Category_ID = isu.Item_Category_ID and
                                        isu.Item_Subcategory_ID = i.Item_Subcategory_ID and
                                        i.Item_ID = ppl.Item_ID group by ic.Item_Category_ID order by ic.Item_Category_Name") or die(mysqli_error($conn));
                $num = mysqli_num_rows($select_details);
                
                if($num > 0){
                    while($row = mysqli_fetch_array($select_details)){
                        $htm .= "<table width=100% >";
                        $htm .= '<tr><td colspan="7" style="text-align: left;"><b>'.strtoupper($row['Item_Category_Name']).'</b></td></tr>';
                        $htm .= '<tr><td colspan="7"><hr></td></tr>';
                        //get sub categories
                        $Item_Category_ID = $row['Item_Category_ID'];
                        $select_sub = mysqli_query($conn,"select * from tbl_item_subcategory where Item_Category_ID = '$Item_Category_ID' order by Item_Subcategory_Name") or die(mysqli_error($conn));
                        $no = mysqli_num_rows($select_sub);
                        if($no > 0){
                            while($data = mysqli_fetch_array($select_sub)){
                                if($control == 'yes'){
                                        $htm .= "<tr id='thead'>
                                                <td width=5%><b>SN</b></td>
                                                <td><b>SUBCATEGORY NAME</b></td>
                                                <td width=5% style='text-align: right;'><b>QTY</b></td>
                                                <td style='text-align: right;' width='14%'><b>CASH</b></td>
                                                <td style='text-align: right;' width='14%'><b>CREDIT</b></td>
                                                <td style='text-align: right;' width='14%'><b>CANCELED</b></td>
                                                <td style='text-align: right;' width='14%'><b>TOTAL</b></td>
                                            </tr>";
                                        $htm .= '<tr><td colspan="7"><hr></td></tr>';
                                        $control = 'no';
                                } //end if
                                //get all transaction based on selected sub category
                                $Item_Subcategory_ID = $data['Item_Subcategory_ID'];
                                $Item_Subcategory_Name = $data['Item_Subcategory_Name'];

                                $get_transactions = mysqli_query($conn,
                                        "select pp.Billing_Type, pp.Transaction_status, sum((ppl.Price - ppl.Discount) * ppl.Quantity) as Amount, sum(ppl.Quantity) as Quantity, pp.payment_type 
                                        from tbl_patient_payment_item_list ppl, tbl_patient_payments pp, tbl_items i where
                                        pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                                        i.Item_ID = ppl.Item_ID and
                                        i.Item_Subcategory_ID = '$Item_Subcategory_ID' and
                                        pp.Payment_Date_And_Time between '$Date_From' and '$Date_To' group by pp.Patient_Payment_ID") or die(mysqli_error($conn));

                                $num_r = mysqli_num_rows($get_transactions);
                                if($num_r > 0){
                                    //$htm .= "<tr><td colspan='7'>".$Item_Subcategory_Name."</td></tr>";
                                    while ($data2 = mysqli_fetch_array($get_transactions)) {
                                        $payment_type = $data2['payment_type'];
                                        $Billing_Type = $data2['Billing_Type'];
                                        $Amount = $data2['Amount'];
                                        $Transaction_status = $data2['Transaction_status'];
                                        
                                        if(strtolower($Transaction_status) == 'cancelled'){
                                            $total_cancelled = $total_cancelled + $Amount;
                                            $grand_total_cancelled = $grand_total_cancelled + $Amount;
                                        }else{
                                            if(strtolower($Billing_Type) == 'outpatient cash' or (strtolower($Billing_Type) == 'inpatient cash' && strtolower($payment_type) == 'pre')){
                                                $total_cash = $total_cash + $Amount;
                                                $grand_total_cash = $grand_total_cash + $Amount;
                                            }else if(strtolower($Billing_Type) == 'outpatient credit' or strtolower($Billing_Type) == 'inpatient credit' or (strtolower($Billing_Type) == 'inpatient cash' && strtolower($payment_type) == 'post')){
                                                $total_credit = $total_credit + $Amount;
                                                $grand_total_credit = $grand_total_credit + $Amount;
                                            }
                                            $Quantity += $data2['Quantity'];
                                            $Grand_Quantity += $data2['Quantity'];
                                        }
                                    } //end while
                                } //end if

                                //displaying data...
                                    $htm .= "<tr>
                                            <td width=5%>".++$temp."</b></td>
                                            <td>".ucwords(strtolower($Item_Subcategory_Name))."</td>
                                            <td style='text-align: right;'>".$Quantity."</td>
                                            <td style='text-align: right;'>".number_format($total_cash)."</td>
                                            <td style='text-align: right;'>".number_format($total_credit)."</td>
                                            <td style='text-align: right;'>".number_format($total_cancelled)."</td>
                                            <td style='text-align: right;'>".number_format($total_cash + $total_credit)."</td>
                                        </tr>";
                                $sub_total_cash = $sub_total_cash + $total_cash;
                                $sub_total_credit = $sub_total_credit + $total_credit;
                                $sub_total_cancelled = $sub_total_cancelled + $total_cancelled;
                                $total_cash = 0;
                                $total_credit = 0;
                                $total_cancelled = 0;
                                $Quantity = 0;
                            } //end while

                            $temp = 0;
                            $control = 'yes';
                        } //end if
                        $htm .= "<tr><td colspan='7'><hr></td></tr>";
                        $htm .= "<tr>
                                <td colspan='2' style='text-align: left;'>TOTAL</td>
                                <td style='text-align: right;'>".$Grand_Quantity."</td>
                                <td style='text-align: right;'>".number_format($sub_total_cash)."</td>
                                <td style='text-align: right;'>".number_format($sub_total_credit)."</td>
                                <td style='text-align: right;'>".number_format($sub_total_cancelled)."</td>
                                <td style='text-align: right;'>".number_format($sub_total_cash + $sub_total_credit)."</td>
                            </tr>";
                            $Grand_Quantity = 0;
                        $htm .= "<tr><td colspan='7'><hr></td></tr>";
                        $htm .= "</table><br/>";
                        $sub_total_cash = 0;
                        $sub_total_credit = 0;
                        $sub_total_cancelled = 0;
                    } //end while
                }
            }else{
                //specific sponsor transaction
                $select_details = mysqli_query($conn,"select ic.Item_Category_ID, ic.Item_Category_Name from
                                        tbl_item_category ic, tbl_item_subcategory isu, tbl_items i, tbl_patient_payment_item_list ppl where
                                        ic.Item_Category_ID = isu.Item_Category_ID and
                                        isu.Item_Subcategory_ID = i.Item_Subcategory_ID and
                                        i.Item_ID = ppl.Item_ID group by ic.Item_Category_ID order by ic.Item_Category_Name") or die(mysqli_error($conn));
                $num = mysqli_num_rows($select_details);
                
                if($num > 0){
                    while($row = mysqli_fetch_array($select_details)){
                        $htm .= "<table width=100% >";
                        $htm .= '<tr><td colspan="7" style="text-align: left;"><b>'.strtoupper($row['Item_Category_Name']).'</b></td></tr>';
                        $htm .= '<tr><td colspan="7"><hr></td></tr>';
                        //get sub categories
                        $Item_Category_ID = $row['Item_Category_ID'];
                        $select_sub = mysqli_query($conn,"select * from tbl_item_subcategory where Item_Category_ID = '$Item_Category_ID' order by Item_Subcategory_Name") or die(mysqli_error($conn));
                        $no = mysqli_num_rows($select_sub);
                        if($no > 0){
                            while($data = mysqli_fetch_array($select_sub)){
                                if($control == 'yes'){
                                        $htm .= "<tr id='thead'>
                                                <td width=5%><b>SN</b></td>
                                                <td><b>SUBCATEGORY NAME</b></td>
                                                <td width=5% style='text-align: right;'><b>QTY</b></td>
                                                <td style='text-align: right;' width='14%'><b>CASH</b></td>
                                                <td style='text-align: right;' width='14%'><b>CREDIT</b></td>
                                                <td style='text-align: right;' width='14%'><b>CANCELED</b></td>
                                                <td style='text-align: right;' width='14%'><b>TOTAL</b></td>
                                            </tr>";
                                            $htm .= '<tr><td colspan="7"><hr></td></tr>';
                                            $control = 'no';
                                } //end if
                                //get all transaction based on selected sub category
                                $Item_Subcategory_ID = $data['Item_Subcategory_ID'];
                                $Item_Subcategory_Name = $data['Item_Subcategory_Name'];

                                $get_transactions = mysqli_query($conn,
                                        "select pp.Billing_Type, pp.Transaction_status, sum((ppl.Price - ppl.Discount) * ppl.Quantity) as Amount, sum(ppl.Quantity) as Quantity, pp.payment_type 
                                        from tbl_patient_payment_item_list ppl, tbl_patient_payments pp, tbl_items i where
                                        pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                                        i.Item_ID = ppl.Item_ID and
                                        i.Item_Subcategory_ID = '$Item_Subcategory_ID' and
                                        pp.Sponsor_ID = '$Sponsor_ID' and
                                        pp.Payment_Date_And_Time between '$Date_From' and '$Date_To' group by pp.Patient_Payment_ID") or die(mysqli_error($conn));

                                $num_r = mysqli_num_rows($get_transactions);
                                if($num_r > 0){
                                    //$htm .= "<tr><td colspan='7'>".$Item_Subcategory_Name."</td></tr>";
                                    while ($data2 = mysqli_fetch_array($get_transactions)) {
                                        $payment_type = $data2['payment_type'];
                                        $Billing_Type = $data2['Billing_Type'];
                                        $Amount = $data2['Amount'];
                                        $Transaction_status = $data2['Transaction_status'];
                                        
                                        if(strtolower($Transaction_status) == 'cancelled'){
                                            $total_cancelled = $total_cancelled + $Amount;
                                            $grand_total_cancelled = $grand_total_cancelled + $Amount;
                                        }else{
                                            if(strtolower($Billing_Type) == 'outpatient cash' or (strtolower($Billing_Type) == 'inpatient cash' && strtolower($payment_type) == 'pre')){
                                                $total_cash = $total_cash + $Amount;
                                                $grand_total_cash = $grand_total_cash + $Amount;
                                            }else if(strtolower($Billing_Type) == 'outpatient credit' or strtolower($Billing_Type) == 'inpatient credit' or (strtolower($Billing_Type) == 'inpatient cash' && strtolower($payment_type) == 'post')){
                                                $total_credit = $total_credit + $Amount;
                                                $grand_total_credit = $grand_total_credit + $Amount;
                                            }
                                            $Quantity += $data2['Quantity'];
                                            $Grand_Quantity += $data2['Quantity'];
                                        }
                                    } //end while
                                } //end if

                                //displaying data...
                                    $htm .= "<tr>
                                            <td width=5%>".++$temp."</b></td>
                                            <td>".ucwords(strtolower($Item_Subcategory_Name))."</td>
                                            <td style='text-align: right;'>".$Quantity."</td>
                                            <td style='text-align: right;'>".number_format($total_cash)."</td>
                                            <td style='text-align: right;'>".number_format($total_credit)."</td>
                                            <td style='text-align: right;'>".number_format($total_cancelled)."</td>
                                            <td style='text-align: right;'>".number_format($total_cash + $total_credit)."</td>
                                        </tr>";
                                $sub_total_cash = $sub_total_cash + $total_cash;
                                $sub_total_credit = $sub_total_credit + $total_credit;
                                $sub_total_cancelled = $sub_total_cancelled + $total_cancelled;
                                $total_cash = 0;
                                $total_credit = 0;
                                $total_cancelled = 0;
                                $Quantity = 0;
                            } //end while

                            $temp = 0;
                            $control = 'yes';
                        } //end if
                        $htm .= "<tr><td colspan='7'><hr></td></tr>";
                        $htm .= "<tr>
                                <td colspan='2' style='text-align: left;'>TOTAL</td>
                                <td style='text-align: right;'>".$Grand_Quantity."</td>
                                <td style='text-align: right;'>".number_format($sub_total_cash)."</td>
                                <td style='text-align: right;'>".number_format($sub_total_credit)."</td>
                                <td style='text-align: right;'>".number_format($sub_total_cancelled)."</td>
                                <td style='text-align: right;'>".number_format($sub_total_cash + $sub_total_credit)."</td>
                            </tr>";
                            $Grand_Quantity = 0;
                        $htm .= "<tr><td colspan='7'><hr></td></tr>";
                        $htm .= "</table><br/>";
                        $sub_total_cash = 0;
                        $sub_total_credit = 0;
                        $sub_total_cancelled = 0;
                    } //end while
                }
            }
        }else{
            //if sponsor = 0 (all sponsor's transactions will be considered)
            if($Sponsor_ID == 0){
                //get item category name
                $slt = mysqli_query($conn,"select Item_Category_Name from tbl_item_category where Item_Category_ID = '$Item_Category_ID'") or die(mysqli_error($conn));
                $nm = mysqli_num_rows($slt);
                if($nm > 0){
                    while ($cname = mysqli_fetch_array($slt)) {
                        $Item_Category_Name = $cname['Item_Category_Name'];
                    }
                }else{
                    $Item_Category_Name = '';
                }
                $htm .= "<table width='100%'>";
                $htm .= '<tr><td colspan="7" style="text-align: left;"><b>'.strtoupper($Item_Category_Name).'</b></td></tr>';
                $htm .= '<tr><td colspan="7"><hr></td></tr>';

                $select_sub = mysqli_query($conn,"select * from tbl_item_subcategory where Item_Category_ID = '$Item_Category_ID' order by Item_Subcategory_Name") or die(mysqli_error($conn));
                $no = mysqli_num_rows($select_sub);
                if($no > 0){
                    while($data = mysqli_fetch_array($select_sub)){
                        if($control == 'yes'){
                            $htm .= "<tr id='thead'>
                                    <td width=5%><b>SN</b></td>
                                    <td><b>SUBCATEGORY NAME</b></td>
                                    <td width=5% style='text-align: right;'><b>QTY</b></td>
                                    <td style='text-align: right;' width='14%'><b>CASH</b></td>
                                    <td style='text-align: right;' width='14%'><b>CREDIT</b></td>
                                    <td style='text-align: right;' width='14%'><b>CANCELED</b></td>
                                    <td style='text-align: right;' width='14%'><b>TOTAL</b></td>
                                </tr>";
                            $htm .= '<tr><td colspan="7"><hr></td></tr>';
                                $control = 'no';
                        } //end if
                        //get all transaction based on selected sub category
                        $Item_Subcategory_ID = $data['Item_Subcategory_ID'];
                        $Item_Subcategory_Name = $data['Item_Subcategory_Name'];

                        $get_transactions = mysqli_query($conn,
                                "select pp.Billing_Type, pp.Transaction_status, sum((ppl.Price - ppl.Discount) * ppl.Quantity) as Amount, sum(ppl.Quantity) as Quantity, pp.payment_type 
                                from tbl_patient_payment_item_list ppl, tbl_patient_payments pp, tbl_items i where
                                pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                                i.Item_ID = ppl.Item_ID and
                                i.Item_Subcategory_ID = '$Item_Subcategory_ID' and
                                pp.Payment_Date_And_Time between '$Date_From' and '$Date_To' group by pp.Patient_Payment_ID") or die(mysqli_error($conn));

                        $num_r = mysqli_num_rows($get_transactions);
                        if($num_r > 0){
                            //$htm .= "<tr><td colspan='7'>".$Item_Subcategory_Name."</td></tr>";
                            while ($data2 = mysqli_fetch_array($get_transactions)) {
                                $payment_type = $data2['payment_type'];
                                $Billing_Type = $data2['Billing_Type'];
                                $Amount = $data2['Amount'];
                                $Transaction_status = $data2['Transaction_status'];
                                
                                if(strtolower($Transaction_status) == 'cancelled'){
                                    $total_cancelled = $total_cancelled + $Amount;
                                    $grand_total_cancelled = $grand_total_cancelled + $Amount;
                                }else{
                                    if(strtolower($Billing_Type) == 'outpatient cash' or (strtolower($Billing_Type) == 'inpatient cash' && strtolower($payment_type) == 'pre')){
                                        $total_cash = $total_cash + $Amount;
                                        $grand_total_cash = $grand_total_cash + $Amount;
                                    }else if(strtolower($Billing_Type) == 'outpatient credit' or strtolower($Billing_Type) == 'inpatient credit' or (strtolower($Billing_Type) == 'inpatient cash' && strtolower($payment_type) == 'post')){
                                        $total_credit = $total_credit + $Amount;
                                        $grand_total_credit = $grand_total_credit + $Amount;
                                    }
                                    $Quantity += $data2['Quantity'];
                                    $Grand_Quantity += $data2['Quantity'];
                                }
                            } //end while
                        } //end if

                        //displaying data...
                            $htm .= "<tr>
                                    <td width=5%>".++$temp."</b></td>
                                    <td>".ucwords(strtolower($Item_Subcategory_Name))."</td>
                                    <td style='text-align: right;'>".$Quantity."</td>
                                    <td style='text-align: right;'>".number_format($total_cash)."</td>
                                    <td style='text-align: right;'>".number_format($total_credit)."</td>
                                    <td style='text-align: right;'>".number_format($total_cancelled)."</td>
                                    <td style='text-align: right;'>".number_format($total_cash + $total_credit)."</td>
                                </tr>";
                        $sub_total_cash = $sub_total_cash + $total_cash;
                        $sub_total_credit = $sub_total_credit + $total_credit;
                        $sub_total_cancelled = $sub_total_cancelled + $total_cancelled;
                        $total_cash = 0;
                        $total_credit = 0;
                        $total_cancelled = 0;
                    } //end while

                        $temp = 0;
                        $control = 'yes';
                } //end if
                $htm .= "<tr><td colspan='7'><hr></td></tr>";
                $htm .= "<tr>
                        <td colspan='2' style='text-align: left;'>TOTAL</td>
                        <td style='text-align: right;'>".$Grand_Quantity."</td>
                        <td style='text-align: right;'>".number_format($sub_total_cash)."</td>
                        <td style='text-align: right;'>".number_format($sub_total_credit)."</td>
                        <td style='text-align: right;'>".number_format($sub_total_cancelled)."</td>
                        <td style='text-align: right;'>".number_format($sub_total_cash + $sub_total_credit)."</td>
                    </tr>";
                    $Grand_Quantity = 0;
                $htm .= "<tr><td colspan='7'><hr></td></tr>";
                $htm .= "</table><br/>";
            }else{

                //get category name
                $slt = mysqli_query($conn,"select Item_Category_Name from tbl_item_category where Item_Category_ID = '$Item_Category_ID'") or die(mysqli_error($conn));
                $nm = mysqli_num_rows($slt);
                if($nm > 0){
                    while ($cname = mysqli_fetch_array($slt)) {
                        $Item_Category_Name = $cname['Item_Category_Name'];
                    }
                }else{
                    $Item_Category_Name = '';
                }
                $htm .= "<table width='100%'>";
                $htm .= '<tr><td colspan="7" style="text-align: left;"><b>'.strtoupper($Item_Category_Name).'</b></td></tr>';
                $htm .= '<tr><td colspan="7"><hr></td></tr>';

                $select_sub = mysqli_query($conn,"select * from tbl_item_subcategory where Item_Category_ID = '$Item_Category_ID' order by Item_Subcategory_Name") or die(mysqli_error($conn));
                $no = mysqli_num_rows($select_sub);
                if($no > 0){
                    while($data = mysqli_fetch_array($select_sub)){
                        if($control == 'yes'){
                            $htm .= "<tr id='thead'>
                                    <td width=5%><b>SN</b></td>
                                    <td><b>SUBCATEGORY NAME</b></td>
                                    <td width=5% style='text-align: right;'><b>QTY</b></td>
                                    <td style='text-align: right;' width='14%'><b>CASH</b></td>
                                    <td style='text-align: right;' width='14%'><b>CREDIT</b></td>
                                    <td style='text-align: right;' width='14%'><b>CANCELED</b></td>
                                    <td style='text-align: right;' width='14%'><b>TOTAL</b></td>
                                </tr>";
                            $htm .= '<tr><td colspan="7"><hr></td></tr>';
                                $control = 'no';
                        } //end if
                        //get all transaction based on selected sub category
                        $Item_Subcategory_ID = $data['Item_Subcategory_ID'];
                        $Item_Subcategory_Name = $data['Item_Subcategory_Name'];

                        $get_transactions = mysqli_query($conn,
                                "select pp.Billing_Type, pp.Transaction_status, sum((ppl.Price - ppl.Discount) * ppl.Quantity) as Amount, sum(ppl.Quantity) as Quantity, pp.payment_type 
                                from tbl_patient_payment_item_list ppl, tbl_patient_payments pp, tbl_items i where
                                pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                                i.Item_ID = ppl.Item_ID and
                                i.Item_Subcategory_ID = '$Item_Subcategory_ID' and
                                pp.Sponsor_ID = '$Sponsor_ID' and
                                pp.Payment_Date_And_Time between '$Date_From' and '$Date_To' group by pp.Patient_Payment_ID") or die(mysqli_error($conn));

                        $num_r = mysqli_num_rows($get_transactions);
                        if($num_r > 0){
                            //$htm .= "<tr><td colspan='7'>".$Item_Subcategory_Name."</td></tr>";
                            while ($data2 = mysqli_fetch_array($get_transactions)) {
                                $payment_type = $data2['payment_type'];
                                $Billing_Type = $data2['Billing_Type'];
                                $Amount = $data2['Amount'];
                                $Transaction_status = $data2['Transaction_status'];
                                
                                if(strtolower($Transaction_status) == 'cancelled'){
                                    $total_cancelled = $total_cancelled + $Amount;
                                    $grand_total_cancelled = $grand_total_cancelled + $Amount;
                                }else{
                                    if(strtolower($Billing_Type) == 'outpatient cash' or (strtolower($Billing_Type) == 'inpatient cash' && strtolower($payment_type) == 'pre')){
                                        $total_cash = $total_cash + $Amount;
                                        $grand_total_cash = $grand_total_cash + $Amount;
                                    }else if(strtolower($Billing_Type) == 'outpatient credit' or strtolower($Billing_Type) == 'inpatient credit' or (strtolower($Billing_Type) == 'inpatient cash' && strtolower($payment_type) == 'post')){
                                        $total_credit = $total_credit + $Amount;
                                        $grand_total_credit = $grand_total_credit + $Amount;
                                    }
                                    $Quantity += $data2['Quantity'];
                                    $Grand_Quantity += $data2['Quantity'];
                                }
                            } //end while
                        } //end if

                        //displaying data...
                            $htm .= "<tr>
                                    <td width=5%>".++$temp."</b></td>
                                    <td>".ucwords(strtolower($Item_Subcategory_Name))."</td>
                                    <td style='text-align: right;'>".$Quantity."</td>
                                    <td style='text-align: right;'>".number_format($total_cash)."</td>
                                    <td style='text-align: right;'>".number_format($total_credit)."</td>
                                    <td style='text-align: right;'>".number_format($total_cancelled)."</td>
                                    <td style='text-align: right;'>".number_format($total_cash + $total_credit)."</td>
                                </tr>";
                        $sub_total_cash = $sub_total_cash + $total_cash;
                        $sub_total_credit = $sub_total_credit + $total_credit;
                        $sub_total_cancelled = $sub_total_cancelled + $total_cancelled;
                        $total_cash = 0;
                        $total_credit = 0;
                        $total_cancelled = 0;
                        $Quantity = 0;
                    } //end while

                        $temp = 0;
                        $control = 'yes';
                } //end if
                $htm .= "<tr><td colspan='7'><hr></td></tr>";
                $htm .= "<tr>
                        <td colspan='2' style='text-align: left;'>TOTAL</td>
                        <td style='text-align: right;'>".$Grand_Quantity."</td>
                        <td style='text-align: right;'>".number_format($sub_total_cash)."</td>
                        <td style='text-align: right;'>".number_format($sub_total_credit)."</td>
                        <td style='text-align: right;'>".number_format($sub_total_cancelled)."</td>
                        <td style='text-align: right;'>".number_format($sub_total_cash + $sub_total_credit)."</td>
                    </tr>";
                    $Grand_Quantity = 0;
                $htm .= "<tr><td colspan='7'><hr></td></tr>";
                $htm .= "</table><br/>";
            }
        }

    }else{
        $Billing_string = "pp.Billing_Type = ''";
        //generate billing type
        if(strtolower($Billing_Type_Value) == 'outpatient'){
            $Billing_string = "(pp.Billing_Type = 'Outpatient Cash' or Billing_Type = 'Outpatient Credit')";
        }else{
            $Billing_string = "(Billing_Type = 'Inpatient Cash' or Billing_Type = 'Inpatient Credit')";
        }
        //specific billing type.................
        if($Item_Category_ID == 0){
            //get all categories
            //if sponsor = 0 (all sponsor's transactions will be considered)
            if($Sponsor_ID == 0){
                $select_details = mysqli_query($conn,"select ic.Item_Category_ID, ic.Item_Category_Name from
                                        tbl_item_category ic, tbl_item_subcategory isu, tbl_items i, tbl_patient_payment_item_list ppl where
                                        ic.Item_Category_ID = isu.Item_Category_ID and
                                        isu.Item_Subcategory_ID = i.Item_Subcategory_ID and
                                        i.Item_ID = ppl.Item_ID group by ic.Item_Category_ID order by ic.Item_Category_Name") or die(mysqli_error($conn));
                $num = mysqli_num_rows($select_details);
                
                if($num > 0){
                    while($row = mysqli_fetch_array($select_details)){
                        $htm .= "<table width=100% >";
                        $htm .= '<tr><td colspan="7" style="text-align: left;"><b>'.strtoupper($row['Item_Category_Name']).'</b></td></tr>';
                        $htm .= '<tr><td colspan="7"><hr></td></tr>';
                        //get sub categories
                        $Item_Category_ID = $row['Item_Category_ID'];
                        $select_sub = mysqli_query($conn,"select * from tbl_item_subcategory where Item_Category_ID = '$Item_Category_ID' order by Item_Subcategory_Name") or die(mysqli_error($conn));
                        $no = mysqli_num_rows($select_sub);
                        if($no > 0){
                            while($data = mysqli_fetch_array($select_sub)){
                                if($control == 'yes'){
                                        $htm .= "<tr id='thead'>
                                                <td width=5%><b>SN</b></td>
                                                <td><b>SUBCATEGORY NAME</b></td>
                                                <td width=5% style='text-align: right;'><b>QTY</b></td>
                                                <td style='text-align: right;' width='14%'><b>CASH</b></td>
                                                <td style='text-align: right;' width='14%'><b>CREDIT</b></td>
                                                <td style='text-align: right;' width='14%'><b>CANCELED</b></td>
                                                <td style='text-align: right;' width='14%'><b>TOTAL</b></td>
                                            </tr>";
                                        $htm .= '<tr><td colspan="7"><hr></td></tr>';
                                        $control = 'no';
                                } //end if
                                //get all transaction based on selected sub category
                                $Item_Subcategory_ID = $data['Item_Subcategory_ID'];
                                $Item_Subcategory_Name = $data['Item_Subcategory_Name'];

                                $get_transactions = mysqli_query($conn,
                                        "select pp.Billing_Type, pp.Transaction_status, sum((ppl.Price - ppl.Discount) * ppl.Quantity) as Amount, sum(ppl.Quantity) as Quantity, pp.payment_type 
                                        from tbl_patient_payment_item_list ppl, tbl_patient_payments pp, tbl_items i where
                                        pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                                        i.Item_ID = ppl.Item_ID and
                                        i.Item_Subcategory_ID = '$Item_Subcategory_ID' and
                                        ".$Billing_string." and
                                        pp.Payment_Date_And_Time between '$Date_From' and '$Date_To' group by pp.Patient_Payment_ID") or die(mysqli_error($conn));

                                $num_r = mysqli_num_rows($get_transactions);
                                if($num_r > 0){
                                    //$htm .= "<tr><td colspan='7'>".$Item_Subcategory_Name."</td></tr>";
                                    while ($data2 = mysqli_fetch_array($get_transactions)) {
                                        $payment_type = $data2['payment_type'];
                                        $Billing_Type = $data2['Billing_Type'];
                                        $Amount = $data2['Amount'];
                                        $Transaction_status = $data2['Transaction_status'];
                                        
                                        if(strtolower($Transaction_status) == 'cancelled'){
                                            $total_cancelled = $total_cancelled + $Amount;
                                            $grand_total_cancelled = $grand_total_cancelled + $Amount;
                                        }else{
                                            if(strtolower($Billing_Type) == 'outpatient cash' or (strtolower($Billing_Type) == 'inpatient cash' && strtolower($payment_type) == 'pre')){
                                                $total_cash = $total_cash + $Amount;
                                                $grand_total_cash = $grand_total_cash + $Amount;
                                            }else if(strtolower($Billing_Type) == 'outpatient credit' or strtolower($Billing_Type) == 'inpatient credit' or (strtolower($Billing_Type) == 'inpatient cash' && strtolower($payment_type) == 'post')){
                                                $total_credit = $total_credit + $Amount;
                                                $grand_total_credit = $grand_total_credit + $Amount;
                                            }
                                            $Quantity += $data2['Quantity'];
                                            $Grand_Quantity += $data2['Quantity'];
                                        }
                                    } //end while
                                } //end if

                                //displaying data...
                                    $htm .= "<tr>
                                            <td width=5%>".++$temp."</b></td>
                                            <td>".ucwords(strtolower($Item_Subcategory_Name))."</td>
                                            <td style='text-align: right;'>".$Quantity."</td>
                                            <td style='text-align: right;'>".number_format($total_cash)."</td>
                                            <td style='text-align: right;'>".number_format($total_credit)."</td>
                                            <td style='text-align: right;'>".number_format($total_cancelled)."</td>
                                            <td style='text-align: right;'>".number_format($total_cash + $total_credit)."</td>
                                        </tr>";
                                $sub_total_cash = $sub_total_cash + $total_cash;
                                $sub_total_credit = $sub_total_credit + $total_credit;
                                $sub_total_cancelled = $sub_total_cancelled + $total_cancelled;
                                $total_cash = 0;
                                $total_credit = 0;
                                $total_cancelled = 0;
                                $Quantity = 0;
                            } //end while

                            $temp = 0;
                            $control = 'yes';
                        } //end if
                        $htm .= "<tr><td colspan='7'><hr></td></tr>";
                        $htm .= "<tr>
                                <td colspan='2' style='text-align: left;'>TOTAL</td>
                                <td style='text-align: right;'>".$Grand_Quantity."</td>
                                <td style='text-align: right;'>".number_format($sub_total_cash)."</td>
                                <td style='text-align: right;'>".number_format($sub_total_credit)."</td>
                                <td style='text-align: right;'>".number_format($sub_total_cancelled)."</td>
                                <td style='text-align: right;'>".number_format($sub_total_cash + $sub_total_credit)."</td>
                            </tr>";
                            $Grand_Quantity = 0;
                        $htm .= "<tr><td colspan='7'><hr></td></tr>";
                        $htm .= "</table><br/>";
                        $sub_total_cash = 0;
                        $sub_total_credit = 0;
                        $sub_total_cancelled = 0;
                    } //end while
                }
            }else{
                //specific sponsor transaction
                $select_details = mysqli_query($conn,"select ic.Item_Category_ID, ic.Item_Category_Name from
                                        tbl_item_category ic, tbl_item_subcategory isu, tbl_items i, tbl_patient_payment_item_list ppl where
                                        ic.Item_Category_ID = isu.Item_Category_ID and
                                        isu.Item_Subcategory_ID = i.Item_Subcategory_ID and
                                        i.Item_ID = ppl.Item_ID group by ic.Item_Category_ID order by ic.Item_Category_Name") or die(mysqli_error($conn));
                $num = mysqli_num_rows($select_details);
                
                if($num > 0){
                    while($row = mysqli_fetch_array($select_details)){
                        $htm .= "<table width=100% >";
                        $htm .= '<tr><td colspan="7" style="text-align: left;"><b>'.strtoupper($row['Item_Category_Name']).'</b></td></tr>';
                        $htm .= '<tr><td colspan="7"><hr></td></tr>';
                        //get sub categories
                        $Item_Category_ID = $row['Item_Category_ID'];
                        $select_sub = mysqli_query($conn,"select * from tbl_item_subcategory where Item_Category_ID = '$Item_Category_ID' order by Item_Subcategory_Name") or die(mysqli_error($conn));
                        $no = mysqli_num_rows($select_sub);
                        if($no > 0){
                            while($data = mysqli_fetch_array($select_sub)){
                                if($control == 'yes'){
                                        $htm .= "<tr id='thead'>
                                                <td width=5%><b>SN</b></td>
                                                <td><b>SUBCATEGORY NAME</b></td>
                                                <td width=5% style='text-align: right;'><b>QTY</b></td>
                                                <td style='text-align: right;' width='14%'><b>CASH</b></td>
                                                <td style='text-align: right;' width='14%'><b>CREDIT</b></td>
                                                <td style='text-align: right;' width='14%'><b>CANCELED</b></td>
                                                <td style='text-align: right;' width='14%'><b>TOTAL</b></td>
                                            </tr>";
                                        $htm .= '<tr><td colspan="7"><hr></td></tr>';
                                        $control = 'no';
                                } //end if
                                //get all transaction based on selected sub category
                                $Item_Subcategory_ID = $data['Item_Subcategory_ID'];
                                $Item_Subcategory_Name = $data['Item_Subcategory_Name'];

                                $get_transactions = mysqli_query($conn,
                                        "select pp.Billing_Type, pp.Transaction_status, sum((ppl.Price - ppl.Discount) * ppl.Quantity) as Amount, sum(ppl.Quantity) as Quantity, pp.payment_type 
                                        from tbl_patient_payment_item_list ppl, tbl_patient_payments pp, tbl_items i where
                                        pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                                        i.Item_ID = ppl.Item_ID and
                                        i.Item_Subcategory_ID = '$Item_Subcategory_ID' and
                                        pp.Sponsor_ID = '$Sponsor_ID' and
                                        ".$Billing_string." and
                                        pp.Payment_Date_And_Time between '$Date_From' and '$Date_To' group by pp.Patient_Payment_ID") or die(mysqli_error($conn));

                                $num_r = mysqli_num_rows($get_transactions);
                                if($num_r > 0){
                                    //$htm .= "<tr><td colspan='7'>".$Item_Subcategory_Name."</td></tr>";
                                    while ($data2 = mysqli_fetch_array($get_transactions)) {
                                        $payment_type = $data2['payment_type'];
                                        $Billing_Type = $data2['Billing_Type'];
                                        $Amount = $data2['Amount'];
                                        $Transaction_status = $data2['Transaction_status'];
                                        
                                        if(strtolower($Transaction_status) == 'cancelled'){
                                            $total_cancelled = $total_cancelled + $Amount;
                                            $grand_total_cancelled = $grand_total_cancelled + $Amount;
                                        }else{
                                            if(strtolower($Billing_Type) == 'outpatient cash' or (strtolower($Billing_Type) == 'inpatient cash' && strtolower($payment_type) == 'pre')){
                                                $total_cash = $total_cash + $Amount;
                                                $grand_total_cash = $grand_total_cash + $Amount;
                                            }else if(strtolower($Billing_Type) == 'outpatient credit' or strtolower($Billing_Type) == 'inpatient credit' or (strtolower($Billing_Type) == 'inpatient cash' && strtolower($payment_type) == 'post')){
                                                $total_credit = $total_credit + $Amount;
                                                $grand_total_credit = $grand_total_credit + $Amount;
                                            }
                                            $Quantity += $data2['Quantity'];
                                            $Grand_Quantity += $data2['Quantity'];
                                        }
                                    } //end while
                                } //end if

                                //displaying data...
                                    $htm .= "<tr>
                                            <td width=5%>".++$temp."</b></td>
                                            <td>".ucwords(strtolower($Item_Subcategory_Name))."</td>
                                            <td style='text-align: right;'>".$Quantity."</td>
                                            <td style='text-align: right;'>".number_format($total_cash)."</td>
                                            <td style='text-align: right;'>".number_format($total_credit)."</td>
                                            <td style='text-align: right;'>".number_format($total_cancelled)."</td>
                                            <td style='text-align: right;'>".number_format($total_cash + $total_credit)."</td>
                                        </tr>";
                                $sub_total_cash = $sub_total_cash + $total_cash;
                                $sub_total_credit = $sub_total_credit + $total_credit;
                                $sub_total_cancelled = $sub_total_cancelled + $total_cancelled;
                                $total_cash = 0;
                                $total_credit = 0;
                                $total_cancelled = 0;
                                $Quantity = 0;
                            } //end while

                            $temp = 0;
                            $control = 'yes';
                        } //end if
                        $htm .= "<tr><td colspan='7'><hr></td></tr>";
                        $htm .= "<tr>
                                <td colspan='2' style='text-align: left;'>TOTAL</td>
                                <td style='text-align: right;'>".$Grand_Quantity."</td>
                                <td style='text-align: right;'>".number_format($sub_total_cash)."</td>
                                <td style='text-align: right;'>".number_format($sub_total_credit)."</td>
                                <td style='text-align: right;'>".number_format($sub_total_cancelled)."</td>
                                <td style='text-align: right;'>".number_format($sub_total_cash + $sub_total_credit)."</td>
                            </tr>";
                            $Grand_Quantity = 0;
                        $htm .= "<tr><td colspan='7'><hr></td></tr>";
                        $htm .= "</table><br/>";
                        $sub_total_cash = 0;
                        $sub_total_credit = 0;
                        $sub_total_cancelled = 0;
                    } //end while
                }
            }
        }else{
            //if sponsor = 0 (all sponsor's transactions will be considered)
            if($Sponsor_ID == 0){
                //get item category name
                $slt = mysqli_query($conn,"select Item_Category_Name from tbl_item_category where Item_Category_ID = '$Item_Category_ID'") or die(mysqli_error($conn));
                $nm = mysqli_num_rows($slt);
                if($nm > 0){
                    while ($cname = mysqli_fetch_array($slt)) {
                        $Item_Category_Name = $cname['Item_Category_Name'];
                    }
                }else{
                    $Item_Category_Name = '';
                }
                $htm .= "<table width='100%'>";
                $htm .= '<tr><td colspan="7" style="text-align: left;"><b>'.strtoupper($Item_Category_Name).'</b></td></tr>';
                $htm .= '<tr><td colspan="7"><hr></td></tr>';

                $select_sub = mysqli_query($conn,"select * from tbl_item_subcategory where Item_Category_ID = '$Item_Category_ID' order by Item_Subcategory_Name") or die(mysqli_error($conn));
                $no = mysqli_num_rows($select_sub);
                if($no > 0){
                    while($data = mysqli_fetch_array($select_sub)){
                        if($control == 'yes'){
                            $htm .= "<tr id='thead'>
                                    <td width=5%><b>SN</b></td>
                                    <td><b>SUBCATEGORY NAME</b></td>
                                    <td width=5% style='text-align: right;'><b>QTY</b></td>
                                    <td style='text-align: right;' width='14%'><b>CASH</b></td>
                                    <td style='text-align: right;' width='14%'><b>CREDIT</b></td>
                                    <td style='text-align: right;' width='14%'><b>CANCELED</b></td>
                                    <td style='text-align: right;' width='14%'><b>TOTAL</b></td>
                                </tr>";
                            $htm .= '<tr><td colspan="7"><hr></td></tr>';
                            $control = 'no';
                        } //end if
                        //get all transaction based on selected sub category
                        $Item_Subcategory_ID = $data['Item_Subcategory_ID'];
                        $Item_Subcategory_Name = $data['Item_Subcategory_Name'];

                        $get_transactions = mysqli_query($conn,
                                "select pp.Billing_Type, pp.Transaction_status, sum((ppl.Price - ppl.Discount) * ppl.Quantity) as Amount, sum(ppl.Quantity) as Quantity, pp.payment_type 
                                from tbl_patient_payment_item_list ppl, tbl_patient_payments pp, tbl_items i where
                                pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                                i.Item_ID = ppl.Item_ID and
                                i.Item_Subcategory_ID = '$Item_Subcategory_ID' and
                                ".$Billing_string." and
                                pp.Payment_Date_And_Time between '$Date_From' and '$Date_To' group by pp.Patient_Payment_ID") or die(mysqli_error($conn));

                        $num_r = mysqli_num_rows($get_transactions);
                        if($num_r > 0){
                            //$htm .= "<tr><td colspan='7'>".$Item_Subcategory_Name."</td></tr>";
                            while ($data2 = mysqli_fetch_array($get_transactions)) {
                                $payment_type = $data2['payment_type'];
                                $Billing_Type = $data2['Billing_Type'];
                                $Amount = $data2['Amount'];
                                $Transaction_status = $data2['Transaction_status'];
                                
                                if(strtolower($Transaction_status) == 'cancelled'){
                                    $total_cancelled = $total_cancelled + $Amount;
                                    $grand_total_cancelled = $grand_total_cancelled + $Amount;
                                }else{
                                    if(strtolower($Billing_Type) == 'outpatient cash' or (strtolower($Billing_Type) == 'inpatient cash') && strtolower($payment_type) == 'pre'){
                                        $total_cash = $total_cash + $Amount;
                                        $grand_total_cash = $grand_total_cash + $Amount;
                                    }else if(strtolower($Billing_Type) == 'outpatient credit' or strtolower($Billing_Type) == 'inpatient credit' or (strtolower($Billing_Type) == 'inpatient cash' && strtolower($payment_type) == 'post')){
                                        $total_credit = $total_credit + $Amount;
                                        $grand_total_credit = $grand_total_credit + $Amount;
                                    }
                                    $Quantity += $data2['Quantity'];
                                    $Grand_Quantity += $data2['Quantity'];
                                }
                            } //end while
                        } //end if

                        //displaying data...
                            $htm .= "<tr>
                                    <td width=5%>".++$temp."</b></td>
                                    <td>".ucwords(strtolower($Item_Subcategory_Name))."</td>
                                    <td style='text-align: right;'>".$Quantity."</td>
                                    <td style='text-align: right;'>".number_format($total_cash)."</td>
                                    <td style='text-align: right;'>".number_format($total_credit)."</td>
                                    <td style='text-align: right;'>".number_format($total_cancelled)."</td>
                                    <td style='text-align: right;'>".number_format($total_cash + $total_credit)."</td>
                                </tr>";
                        $sub_total_cash = $sub_total_cash + $total_cash;
                        $sub_total_credit = $sub_total_credit + $total_credit;
                        $sub_total_cancelled = $sub_total_cancelled + $total_cancelled;
                        $total_cash = 0;
                        $total_credit = 0;
                        $total_cancelled = 0;
                        $Quantity = 0;
                    } //end while

                        $temp = 0;
                        $control = 'yes';
                } //end if
                $htm .= "<tr><td colspan='7'><hr></td></tr>";
                $htm .= "<tr>
                        <td colspan='2' style='text-align: left;'>TOTAL</td>
                        <td style='text-align: right;'>".$Grand_Quantity."</td>
                        <td style='text-align: right;'>".number_format($sub_total_cash)."</td>
                        <td style='text-align: right;'>".number_format($sub_total_credit)."</td>
                        <td style='text-align: right;'>".number_format($sub_total_cancelled)."</td>
                        <td style='text-align: right;'>".number_format($sub_total_cash + $sub_total_credit)."</td>
                    </tr>";
                    $Grand_Quantity = 0;
                $htm .= "<tr><td colspan='7'><hr></td></tr>";
                $htm .= "</table><br/>";
            }else{

                //get category name
                $slt = mysqli_query($conn,"select Item_Category_Name from tbl_item_category where Item_Category_ID = '$Item_Category_ID'") or die(mysqli_error($conn));
                $nm = mysqli_num_rows($slt);
                if($nm > 0){
                    while ($cname = mysqli_fetch_array($slt)) {
                        $Item_Category_Name = $cname['Item_Category_Name'];
                    }
                }else{
                    $Item_Category_Name = '';
                }
                $htm .= "<table width='100%'>";
                $htm .= '<tr><td colspan="7" style="text-align: left;"><b>'.strtoupper($Item_Category_Name).'</b></td></tr>';
                $htm .= '<tr><td colspan="7"><hr></td></tr>';

                $select_sub = mysqli_query($conn,"select * from tbl_item_subcategory where Item_Category_ID = '$Item_Category_ID' order by Item_Subcategory_Name") or die(mysqli_error($conn));
                $no = mysqli_num_rows($select_sub);
                if($no > 0){
                    while($data = mysqli_fetch_array($select_sub)){
                        if($control == 'yes'){
                            $htm .= "<tr id='thead'>
                                    <td width=5%><b>SN</b></td>
                                    <td><b>SUBCATEGORY NAME</b></td>
                                    <td width=5% style='text-align: right;'><b>QTY</b></td>
                                    <td style='text-align: right;' width='14%'><b>CASH</b></td>
                                    <td style='text-align: right;' width='14%'><b>CREDIT</b></td>
                                    <td style='text-align: right;' width='14%'><b>CANCELED</b></td>
                                    <td style='text-align: right;' width='14%'><b>TOTAL</b></td>
                                </tr>";
                            $htm .= '<tr><td colspan="7"><hr></td></tr>';
                            $control = 'no';
                        } //end if
                        //get all transaction based on selected sub category
                        $Item_Subcategory_ID = $data['Item_Subcategory_ID'];
                        $Item_Subcategory_Name = $data['Item_Subcategory_Name'];

                        $get_transactions = mysqli_query($conn,
                                "select pp.Billing_Type, pp.Transaction_status, sum((ppl.Price - ppl.Discount) * ppl.Quantity) as Amount, sum(ppl.Quantity) as Quantity, pp.payment_type 
                                from tbl_patient_payment_item_list ppl, tbl_patient_payments pp, tbl_items i where
                                pp.Patient_Payment_ID = ppl.Patient_Payment_ID and
                                i.Item_ID = ppl.Item_ID and
                                i.Item_Subcategory_ID = '$Item_Subcategory_ID' and
                                pp.Sponsor_ID = '$Sponsor_ID' and
                                ".$Billing_string." and
                                pp.Payment_Date_And_Time between '$Date_From' and '$Date_To' group by pp.Patient_Payment_ID") or die(mysqli_error($conn));

                        $num_r = mysqli_num_rows($get_transactions);
                        if($num_r > 0){
                            //$htm .= "<tr><td colspan='7'>".$Item_Subcategory_Name."</td></tr>";
                            while ($data2 = mysqli_fetch_array($get_transactions)) {
                                $payment_type = $data2['payment_type'];
                                $Billing_Type = $data2['Billing_Type'];
                                $Amount = $data2['Amount'];
                                $Transaction_status = $data2['Transaction_status'];
                                
                                if(strtolower($Transaction_status) == 'cancelled'){
                                    $total_cancelled = $total_cancelled + $Amount;
                                    $grand_total_cancelled = $grand_total_cancelled + $Amount;
                                }else{
                                    if(strtolower($Billing_Type) == 'outpatient cash' or (strtolower($Billing_Type) == 'inpatient cash' && strtolower($payment_type) == 'pre')){
                                        $total_cash = $total_cash + $Amount;
                                        $grand_total_cash = $grand_total_cash + $Amount;
                                    }else if(strtolower($Billing_Type) == 'outpatient credit' or strtolower($Billing_Type) == 'inpatient credit' or (strtolower($Billing_Type) == 'inpatient cash' && strtolower($payment_type) == 'post')){
                                        $total_credit = $total_credit + $Amount;
                                        $grand_total_credit = $grand_total_credit + $Amount;
                                    }
                                    $Quantity += $data2['Quantity'];
                                    $Grand_Quantity += $data2['Quantity'];
                                }
                            } //end while
                        } //end if

                        //displaying data...
                            $htm .= "<tr>
                                    <td width=5%>".++$temp."</b></td>
                                    <td>".ucwords(strtolower($Item_Subcategory_Name))."</td>
                                    <td style='text-align: right;'>".$Quantity."</td>
                                    <td style='text-align: right;'>".number_format($total_cash)."</td>
                                    <td style='text-align: right;'>".number_format($total_credit)."</td>
                                    <td style='text-align: right;'>".number_format($total_cancelled)."</td>
                                    <td style='text-align: right;'>".number_format($total_cash + $total_credit)."</td>
                                </tr>";
                        $sub_total_cash = $sub_total_cash + $total_cash;
                        $sub_total_credit = $sub_total_credit + $total_credit;
                        $sub_total_cancelled = $sub_total_cancelled + $total_cancelled;
                        $total_cash = 0;
                        $total_credit = 0;
                        $total_cancelled = 0;
                        $Quantity = 0;
                    } //end while

                        $temp = 0;
                        $control = 'yes';
                } //end if
                $htm .= "<tr><td colspan='7'><hr></td></tr>";
                $htm .= "<tr>
                        <td colspan='2' style='text-align: left;'>TOTAL</td>
                        <td style='text-align: right;'>".$Grand_Quantity."</td>
                        <td style='text-align: right;'>".number_format($sub_total_cash)."</td>
                        <td style='text-align: right;'>".number_format($sub_total_credit)."</td>
                        <td style='text-align: right;'>".number_format($sub_total_cancelled)."</td>
                        <td style='text-align: right;'>".number_format($sub_total_cash + $sub_total_credit)."</td>
                    </tr>";
                    $Grand_Quantity = 0;
                $htm .= "<tr><td colspan='7'><hr></td></tr>";
                $htm .= "</table><br/>";
            }
        }
    }
    $htm .= "<table width='100%'>
                    <tr><td colspan='7'><hr></td></tr>
                    <tr><td colspan='3' style='text-align: left;'><b>GRAND TOTAL</b></td>
                        <td style='text-align: right;' width='14%'><b>".number_format($grand_total_cash)."</b></td>
                        <td style='text-align: right;' width='14%'><b>".number_format($grand_total_credit)."</b></td>
                        <td style='text-align: right;' width='14%'><b>".number_format($grand_total_cancelled)."</b></td>
                        <td style='text-align: right;' width='14%'><b>".number_format($grand_total_cash + $grand_total_credit)."</b></td>
                    </tr>";
            $htm .= "<tr><td colspan='7'><hr></td></tr>";
            $htm .= '</table>';
?>
</center>



<?php
    $htm .= "</table>";

    //echo .= $htm; exit();

    include("MPDF/mpdf.php");

    $mpdf=new mPDF('','Letter',0,'',12.7,12.7,14,12.7,8,8); 
    $mpdf->SetFooter('Printed By '.strtoupper($Employee_Name).'|{PAGENO}/{nb}|{DATE d-m-Y}');
    $mpdf->WriteHTML($htm);
    $mpdf->Output();
    
?>
