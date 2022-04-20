<?php
    @session_start();
    include("./includes/connection.php");
    $filter = mysqli_real_escape_string($conn,$_POST['filter']);
    $Date_From=mysqli_real_escape_string($conn,$_POST['Date_From']);
    $Date_To=mysqli_real_escape_string($conn,$_POST['Date_To']);
    $Employee_ID=mysqli_real_escape_string($conn,$_POST['Employee_ID']);
?>

<legend style="background-color:#006400;color:white;padding:5px; text-align: center;"><b>POS TRANSACTIONS REPORT </b></legend>
<center>
    <?php


if($Employee_ID != 0){
	$filter_employee .=" em.Employee_ID='$Employee_ID'";
}

if($filter == 'yes'){
    if($Employee_ID != 0){
  echo "<table width=100% style='background: #fff; border: 2px solid #000;'>
            <tr id='thead' style='background-color:#ccc;'>
			    <td width='5%' style='text-align: center;'><b>SN</b></td>
			    <td style='text-align: center;'><b>BILL ID</b></td>
			    <td style='text-align: center;'><b>PATIENT NAME</b></td>
			    <td style='text-align: center;'><b>BILL RECEIPT No:</b></td>
			    <td style='text-align: center;'><b>BATCH NUMBER</b></td>
			    <td style='text-align: center;'><b>BILL AMOUNT</b></td>
			    <td style='text-align: center;'><b>PAID AMOUNT</b></td>
			    <td style='text-align: center;'><b>TERMINAL ID</b></td>
			    <td style='text-align: center;'><b>PAYMENT DATE</b></td>
			    <td style='text-align: center;'><b>AUTH CODE</b></td>
			    <td style='text-align: center;'><b>EMPLOYEE NAME</b></td>
            </tr>";

        $pos_filtered_details = mysqli_query($conn, "SELECT em.Employee_Name, b.BillID, b.Customer, b.BillAmount, b.UserID, b.CardNumber, b.ReceiptNumber, b.AmountPaid, b.BankBatchNo, b.TerminalID, b.TransmissionDate, b.AuthorizationCode FROM tbl_employee em, billdetails b WHERE b.UserID = '$Employee_ID' AND em.Employee_ID = b.UserID AND b.TransmissionDate BETWEEN '$Date_From' AND '$Date_To' ORDER BY b.BillID ASC") or die(mysqli_error($conn));
 
    $num = mysqli_num_rows($pos_filtered_details);
            $SN = 1;
if($num > 0){
    while($datas = (mysqli_fetch_array($pos_filtered_details))){
        $Employee_Name = $datas['Employee_Name'];
        $BillID = $datas['BillID'];
        $Customer = $datas['Customer'];
        $BillAmount = $datas['BillAmount'];
        $ReceiptNumber = $datas['ReceiptNumber'];
        $AmountPaid = $datas['AmountPaid'];
        $BankBatchNo = $datas['BankBatchNo'];
        $TerminalID = $datas['TerminalID'];
        $TransmissionDate = $datas['TransmissionDate'];
        $AuthorizationCode = $datas['AuthorizationCode'];
		$Grand_Total = $Grand_Total + $AmountPaid;
        


            echo"<tr style:'border: 2px solid #000; height: 3px;'>
                    <td style='text-align: center;'>".$SN."</td>
					<td>".$BillID."</td>
                    <td>".$Customer."</td>
                    <td style='text-align: center;'>".$ReceiptNumber."</td>
                    <td style='text-align: right;'>".$BankBatchNo."</td>
                    <td style='text-align: right;'>".number_format($BillAmount)."</td>
                    <td style='text-align: right;'>".number_format($AmountPaid)."</td>
                    <td style='text-align: right;'>".$TerminalID."</td>
                    <td style='text-align: right;'>".$TransmissionDate."</td>
                    <td style='text-align: right;'>".$AuthorizationCode."</td>
                    <td style='text-align: center;'>".$Employee_Name."</td>";
    $SN++;
	echo "</tr>";
    }
	echo "<tr style='background-color:#ccc; padding:2px;'>
	<td colspan='11'><hr>
	</td></tr>
	<tr style='background-color:#ccc; padding:2px;'>
	<td colspan='6'><span style='font-size: 19px; font-weight: bold;'>Grand Total</span></td>
	<td style='text-align: right; font-weight: bold; font-size: 19px;'> Tshs. ".number_format($Grand_Total)."/=</td>
	<td colspan='4'></td></tr>
	<tr style='background-color:#ccc; padding:2px;'>
	<td colspan='11'><hr>
	</td></tr>";
}else{

    echo "<tr>
    <td style='text-align: center;' colspan='11'><span style='color: red; font-size: 24px; font-weight: bold;'>No Transaction Done By ".$Employee_Name." From ".$Date_From." To ".$Date_To;
}
    }else{
        echo "<table width=100% style='background: #fff; border: 2px solid #000;'>
            <tr id='thead' style='background-color:#ccc;'>
			    <td width='5%' style='text-align: center;'><b>SN</b></td>
			    <td style='text-align: center;'><b>BILL ID</b></td>
			    <td style='text-align: center;'><b>PATIENT NAME</b></td>
			    <td style='text-align: center;'><b>BILL RECEIPT No:</b></td>
			    <td style='text-align: center;'><b>BATCH NUMBER</b></td>
			    <td style='text-align: center;'><b>BILL AMOUNT</b></td>
			    <td style='text-align: center;'><b>PAID AMOUNT</b></td>
			    <td style='text-align: center;'><b>TERMINAL ID</b></td>
			    <td style='text-align: center;'><b>PAYMENT DATE</b></td>
			    <td style='text-align: center;'><b>AUTH CODE</b></td>
			    <td style='text-align: center;'><b>EMPLOYEE NAME</b></td>
            </tr>";

        $pos_filtered_details = mysqli_query($conn, "SELECT em.Employee_Name, b.BillID, b.Customer, b.BillAmount, b.UserID, b.CardNumber, b.ReceiptNumber, b.AmountPaid, b.BankBatchNo, b.TerminalID, b.TransmissionDate, b.AuthorizationCode FROM tbl_employee em, billdetails b WHERE em.Employee_ID = b.UserID AND b.TransmissionDate BETWEEN '$Date_From' AND '$Date_To' ORDER BY b.BillID ASC") or die(mysqli_error($conn));
 
    $num = mysqli_num_rows($pos_filtered_details);
            $SN = 1;
if($num > 0){
}
    while($datas = (mysqli_fetch_array($pos_filtered_details))){
        $Employee_Name = $datas['Employee_Name'];
        $BillID = $datas['BillID'];
        $Customer = $datas['Customer'];
        $BillAmount = $datas['BillAmount'];
        $ReceiptNumber = $datas['ReceiptNumber'];
        $AmountPaid = $datas['AmountPaid'];
        $BankBatchNo = $datas['BankBatchNo'];
        $TerminalID = $datas['TerminalID'];
        $TransmissionDate = $datas['TransmissionDate'];
        $AuthorizationCode = $datas['AuthorizationCode'];
		$Grand_Total = $Grand_Total + $AmountPaid;
        


            echo"<tr style:'border: 2px solid #000; height: 3px;'>
                    <td style='text-align: center;'>".$SN."</td>
					<td>".$BillID."</td>
                    <td>".$Customer."</td>
                    <td style='text-align: center;'>".$ReceiptNumber."</td>
                    <td style='text-align: right;'>".$BankBatchNo."</td>
                    <td style='text-align: right;'>".number_format($BillAmount)."</td>
                    <td style='text-align: right;'>".number_format($AmountPaid)."</td>
                    <td style='text-align: right;'>".$TerminalID."</td>
                    <td style='text-align: right;'>".$TransmissionDate."</td>
                    <td style='text-align: right;'>".$AuthorizationCode."</td>
                    <td style='text-align: center;'>".$Employee_Name."</td>";
    $SN++;
	echo "</tr>";
    }
	echo "<tr style='background-color:#ccc; padding:2px;'>
	<td colspan='11'><hr>
	</td></tr>
	<tr style='background-color:#ccc; padding:2px;'>
	<td colspan='6'><span style='font-size: 19px; font-weight: bold;'>Grand Total</span></td>
	<td style='text-align: right; font-weight: bold; font-size: 19px;'> Tshs. ".number_format($Grand_Total)."/=</td>
	<td colspan='4'></td></tr>
	<tr style='background-color:#ccc; padding:2px;'>
	<td colspan='11'><hr>
	</td></tr>";

}
}
 
    mysqli_close($conn);
        ?>
    </table>
</center>