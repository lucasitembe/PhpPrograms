<link rel="stylesheet" href="tablewe.css" media="screen"> 
<?php
include("./includes/connection.php");
if (isset($_GET['Payment_Cache_ID'])) {
    $Payment_Cache_ID = $_GET['Payment_Cache_ID'];
    $payment_cache_ID = $Payment_Cache_ID;
} else {
    $Payment_Cache_ID = 0;
}
if (isset($_GET['Consultation_Type'])) {
    $Consultation_Type = $_GET['Consultation_Type'];
} else {
    $Consultation_Type = 0;
}
if (isset($_GET['Registration_ID'])) {
    $Registration_ID = $_GET['Registration_ID'];
} else {
    $Registration_ID = 0;
}
if (isset($_GET['Round_ID'])) {
    $Round_ID = $_GET['Round_ID'];
}
?>

<table width='100%'>
    <tr id='thead'>
        <td style='width: 1%'><b>SN</b></td>
        <td><b>Description</b></td>
<?php if (strtolower($Consultation_Type) == "pharmacy") { ?>
            <td width='35%'><b>Dosage</b></td>
        <?php } else { ?>
            <td width='35%'><b>Comments</b></td>
        <?php }
        ?>
        <?php //if (strtolower($Consultation_Type) == "pharmacy") { ?>
            <td ><b>QTY</b></td>
        <?php //}
        ?>
        <?php if (strtolower($Consultation_Type) == "pharmacy") { ?>
           <!-- <td ><b>Balance</b></td>-->
        <?php } ?>
        <td align='right'><b>Cash</b></td>
        <td align='right'><b>Credit</b></td>
        <td align='center'><b>Action</b></td>
    </tr>
        <?php
        $qr = "SELECT * 
		FROM 
			tbl_item_list_cache il,
			tbl_payment_cache pc,
			tbl_items it
		    WHERE 
				pc.Payment_Cache_ID=il.Payment_Cache_ID AND
				pc.Round_ID = $Round_ID AND 
				il.Item_ID = it.Item_ID AND il.Check_In_Type ='$Consultation_Type' ORDER BY il.Payment_Item_Cache_List_ID ASC";
        $result = mysqli_query($conn,$qr) or die(mysqli_error($conn));
        $i = 1;
        $Cashsum = 0;
        $Creditsum = 0;
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_array($result)) {

                if ($row['Transaction_Type'] == 'Cash' || $row['Transaction_Type'] == 'Outpatient Cash') {
                    $Cash = ($row['Price'] - $row['Discount']);
                    $Cashsum +=($row['Price'] - $row['Discount']) * $row['Quantity'];
                    $Credit = 0;
                } else {
                    $Credit = $row['Price'];
                    $Creditsum += $row['Price'] * $row['Quantity'];
                    $Cash = 0;
                }
                ?>
            <tr>
                <td style="border:1px solid #ccc;"><b><?php echo $i; ?></b></td>
                <td style="border:1px solid #ccc;"><?php echo $row['Product_Name']; ?>
            <?php
            $Sub_Department_ID = $row['Sub_Department_ID'];
            $select_sub_category_name = mysqli_query($conn,"SELECT Sub_Department_Name FROM tbl_sub_department WHERE Sub_Department_ID = '$Sub_Department_ID' ");
            while ($namerow = mysqli_fetch_array($select_sub_category_name)) {
                echo "(<b>" . $Sub_Department_Name = $namerow['Sub_Department_Name'] . "</b>)";
            }

            $dcm = $row['Doctor_Comment'];
            ?>
                </td>
                <td style="border:1px solid #ccc;">
                    <textarea style="height:35px" oninput="updateDoctorItemCOmment('<?php echo $row['Payment_Item_Cache_List_ID']; ?>',this.value)"><?php echo $dcm; ?></textarea>
                </td>
            <!--<td><?php //echo $row['Quantity'];?>&nbsp;</td>-->
                    <?php //if (strtolower($Consultation_Type) == "pharmacy") { ?>
                    <td style="border:1px solid #ccc;"><?php echo $row['Quantity'] ?></td>
                    <?php //}
                    ?>
                <td align='right' style="text-align: right;border:1px solid #ccc;" ><?php if ($Cash != 0) {
                        echo number_format($Cash);
                    } else {
                        echo '0';
                    } ?></td>
                <td align='right' style="text-align: right;border:1px solid #ccc;"><?php if ($Credit != 0) {
            echo number_format($Credit);
        } else {
            echo '0';
        } ?></td>
                <td align='center' width='2%' style="border:1px solid #ccc;">
                    <?php
                    if ($row['Status'] != 'paid') {
                        ?>
                        <input style="border:1px solid #ccc;" type='button' value='Remove' onclick="check_item('<?php echo $row['Payment_Item_Cache_List_ID'] ?>', '<?php echo $row['Payment_Cache_ID'] ?>')">
                    <?php } else {
                        echo '&nbsp'; ?>
                    </td>
                </tr>
                <?php
            }

            if (mysqli_num_rows($result) == 0) {
                ?>
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <?php
            }
            $i++;
        }
        ?>
        <tr>


            <td colspan='4' ><center><b>TOTAL</b></center></td>
    <?php
} else {
    ?>
    <td colspan='4' ><center><b>TOTAL</b></center></td>
    <?php
}
?>	    
<td align='right' style="text-align: right"><b><?php echo number_format($Cashsum); ?></b></td>
<td align='right' style="text-align: right"><b><?php echo number_format($Creditsum); ?></b></td>
<td align='center'><b><?php echo number_format($Cashsum + $Creditsum); ?></b></td>
</tr>
</table>