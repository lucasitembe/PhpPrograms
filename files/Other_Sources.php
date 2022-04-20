<table width="100%">
    <tr><td colspan="7"><hr></td></tr>
    <tr>
        <td width="5%"><b>SN</b></td>
        <td><b>CATEGORY NAME</b></td>
        <td style="text-align: center;" width="15%"><b>NO OF COSTUMERS/SUPPLIERS</b></td>
        <td style="text-align: center;" width="15%"><b>NO OF SERVICES</b></td>
        <td style="text-align: right;" width="15%"><b>AMOUNT</b></td>
    </tr>
    <tr><td colspan="7"><hr></td></tr>
    <?php
    $get_categories = mysqli_query($conn,"SELECT DISTINCT osp.customer_type FROM tbl_other_sources_payments osp WHERE  osp.Payment_Date_And_Time BETWEEN '$Start_Date' AND '$End_Date' ORDER BY osp.customer_type ASC") or die(mysqli_error($conn));
    $num_cat = mysqli_num_rows($get_categories);
    if ($num_cat > 0) {
        $temper = 0;
        $Grand_Total_Cash = 0;
        $total_customer_number=0;
        $Grand_Quantity = 0;
        while ($cat = mysqli_fetch_array($get_categories)) {
            $customer_type = $cat['customer_type'];
            $Quantity = 0;
            $total_amount = 0;
            $select_services=mysqli_query($conn,"SELECT Price FROM tbl_other_sources_payment_item_list pil, tbl_other_sources_payments osp WHERE pil.Payment_ID=osp.Payment_ID AND customer_type='$customer_type' AND osp.Payment_Date_And_Time BETWEEN '$Start_Date' AND '$End_Date'");
            $services_number=mysqli_num_rows($select_services);
            $select_customer=mysqli_query($conn,"SELECT DISTINCT osp.Customer_ID FROM tbl_other_sources_payments osp WHERE osp.Payment_Date_And_Time BETWEEN '$Start_Date' AND '$End_Date' AND osp.customer_type='$customer_type'");
            $customer_number=mysqli_num_rows($select_customer);
            $total_customer_number+=$customer_number;
            while ($row=mysqli_fetch_assoc($select_services)) {
              $total_amount+=$row['Price'];
            }
            $Grand_Quantity+=$services_number;
            $Grand_Total_Cash+=$total_amount;
            ?>

            <tr><td><b><?php echo ++$temper; ?></b></td>
                <td style='color: #0079AE;'><b><label onclick="Preview_Other_Sources('<?php echo $cat['customer_type']; ?>')"><?php echo strtoupper($cat['customer_type']); ?></label></b></td><td style="text-align:center;"><b><?=$customer_number; ?></b></td><td style='text-align:center;'><b><?=$services_number; ?><b></td><td style='text-align:right;'><b><?=number_format($total_amount); ?><b></td></tr>
                <?php
            }
            ?>
        <tr><td colspan="7"><hr></td></tr>
        <tr>
            <td colspan="2"><b>GRAND TOTAL</b></td>
            <td style="text-align:center;"><b><?=$total_customer_number;?></b></td>
            <td style="text-align: center;"><b><?php if ($_SESSION['systeminfo']['price_precision'] == 'yes') {
                echo $Grand_Quantity;
            } else {
                echo $Grand_Quantity;
            } ?></b></td>
            <td style="text-align: right;"><b><?php if ($_SESSION['systeminfo']['price_precision'] == 'yes') {
                echo $Currency_Code . '&nbsp;' . number_format($Grand_Total_Cash, 2);
            } else {
                echo $Currency_Code . '&nbsp;' . number_format($Grand_Total_Cash);
            } ?></b>&nbsp;</td>
        </tr>
    <?php
}
?>
</table>
