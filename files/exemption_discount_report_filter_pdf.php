<?php 
include("./includes/connection.php");
        $start_date = $_GET['start_date'];
        $end_date = $_GET['end_date'];
        $i=1;
        $htm = "<table width ='100%' height = '30px'>
		    <tr><td><img src='./branchBanner/branchBanner.png' width=100%></td></tr>
		    <tr><td style='text-align: center;'><b><span style='font-size: x-small;'>DISCOUNT ANALYSIS REPORT</span></b></td></tr>
		    <tr><td style='text-align: center;'><b><span style='font-size: x-small;'>FROM ".@date("d F Y H:i:s",strtotime($start_date))." TO ".@date("d F Y H:i:s",strtotime($end_date))."</span></b></td></tr>
		    </table>";
        $htm .= "<table width='100%'>";
        $Counter=0;
        $get_discounts = mysqli_query($conn, "SELECT Check_In_Type ,Item_ID FROM tbl_patient_payments pp,  tbl_patient_payment_item_list ppl WHERE  pp.Patient_Payment_ID= ppl.Patient_Payment_ID AND  ppl.Discount > 0 AND Transaction_Date_And_Time BETWEEN '$start_date' AND '$end_date' GROUP BY Check_In_Type") or die(mysqli_error($conn));
        $Check_In_Type='';
			if(mysqli_num_rows($get_discounts) > 0){
                while($data = mysqli_fetch_assoc($get_discounts)){
                    $Check_In_Type = $data['Check_In_Type'];
				$htm.= '<tr><td colspan="2"><table width="100%" border=1 style="border-collapse: collapse;">';
				$htm.=  "<tr><td colspan='6'><b><span style='font-size: x-small;'>".++$Counter."&nbsp;&nbsp;&nbsp;CHECK IN TYPE : </b>".strtoupper($data['Check_In_Type'])."</span></b></td></tr>";
				$htm.= "<tr><td  width=5% style='background:#ccc; text-align: right;' ><span style=' font-size: x-small;'><b>S/N</b></span></td>
							<td style='background:#ccc; text-align: center;' width='18%'><b><span style=' font-size: x-small;'>ITEM NAME</span></b></td>
							<td  style='background:#ccc; text-align: right;' width='18%'><b><span style=' font-size: x-small;'>QUANTITY</span></b></td>
							<td  style='background:#ccc; text-align: center;' width='15%'><b><span style=' font-size: x-small;'>TOTAL PRICE</span></b></td>
							<td  style='background:#ccc; text-align: center;' width='15%'><b><span style=' font-size: x-small;'>DISCOUNT</span></b></td>
							<td  style='background:#ccc; text-align: center;' width='15%'><b><span style=' font-size: x-small;'>TOTAL AMOUNT</span></b></td>
                        </tr>";
                $Tty_Quantity=0;
                $Total_price=0;
                $Total_Discount=0;
                $con=0;  
                $Ttlafterall =0; 
                $Ttyqty=0;  
                $Ttydsc=0; 
                $Ttyamount=0;  
                $get_discounts_item = mysqli_query($conn, "SELECT Check_In_Type ,Discount,Quantity, SUM(Quantity ) AS Tty_Quantity, SUM(Discount) AS Total_Discount,SUM(Quantity * Price) AS Total_amount , Price ,ppl.Item_ID, Product_Name FROM tbl_patient_payments pp, tbl_items i, tbl_patient_payment_item_list ppl WHERE  pp.Patient_Payment_ID= ppl.Patient_Payment_ID  AND i.Item_ID=ppl.Item_ID AND Check_In_Type='$Check_In_Type' AND  ppl.Discount > 0 AND Transaction_Date_And_Time BETWEEN '$start_date' AND '$end_date' GROUP BY ppl.Item_ID") or die(mysqli_error($conn));
                
				while ($rw = mysqli_fetch_assoc($get_discounts_item)) {
                    $Quantity = $rw['Quantity'];
                    $Price = $rw['Price'];
                    $Discount = $rw['Discount'];
                    $Tty_Quantity = $rw['Tty_Quantity'];
                    $Total_Discount =$rw['Total_Discount'];
                    $Total_amount=$rw['Total_amount'];
					$htm.= "<tr>
								<td width=5%><span style='font-size: x-small;'>".++$con."</span></td>
								<td style='text-align: left;'><span style='font-size: x-small;'>".strtoupper($rw['Product_Name'])."</span></td>
								<td style='text-align: right;'><span style='font-size: x-small;'>".number_format($Tty_Quantity)."</span></td>
								<td style='text-align: center;'><span style='font-size: x-small;'>".number_format($Total_amount)."</span></td>
								<td style='text-align: center;'><span style='font-size: x-small;'>".number_format($Total_Discount)."</span></td>
								<td style='text-align: center;'><span style='font-size: x-small;'>".number_format($Total_amount - $Total_Discount)."</span></td>
							</tr>";
                        $Ttyqty +=$Tty_Quantity;
                        $Ttyamount +=$Total_amount;
                        $Ttydsc += $Total_Discount;
                        $Ttlafterall +=($Total_amount - $Total_Discount);
                }
                $htm.= "<tr>
								<th colspan='2'>Total</th>
								<th style='background:#ccc; text-align: right;'><span style='font-size: x-small;'>".number_format($Ttyqty)."</span></th>
								<th style='background:#ccc; text-align: center;'><span style='font-size: x-small;'>".number_format($Ttyamount)."</span></th>
								<th style='background:#ccc; text-align: center;'><span style='font-size: x-small;'>".number_format($Ttydsc)."</span></th>
								<th style='background:#ccc; text-align: center;'><span style='font-size: x-small;'>".number_format($Ttlafterall)."</span></th>
							</tr>";
                $htm.= "</table>";

            }   
                $htm.="<table width='100%'>";
                while($datarw = mysqli_fetch_assoc($get_discounts)){
                    $Check_In_Type = $datarw['Check_In_Type'];
                    $htm.=  "<tr><td ><b><span style='font-size: x-small;'>&nbsp;&nbsp;&nbsp;CHECK IN TYPE : </b>".strtoupper($Check_In_Type)."</span></b></td></tr>";

                }
                $htm.="</table>";
				
				$htm.=  "</table>";
            }
            
            	
	include("./MPDF/mpdf.php");
    $mpdf=new mPDF('','A3', 0, '', 15,15,20,40,15,35, 'P');
    $mpdf->SetFooter('Printed By '.strtoupper($E_Name).'|{PAGENO}/{nb}|{DATE d-m-Y}');
    $mpdf->WriteHTML($htm);
    $mpdf->Output();