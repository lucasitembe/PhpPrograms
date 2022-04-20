<?php
include("includes/connection.php");
if(isset($_POST['discountreport'])){
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $i=1;
    
    $sql_discount = mysqli_query($conn, "SELECT pp.Registration_ID,ppl.Patient_Payment_ID,pp.Employee_ID,  pr.Gender,pr.Patient_Name,Date_Of_Birth,Phone_Number FROM tbl_patient_payments pp, tbl_patient_registration pr, tbl_patient_payment_item_list ppl WHERE pp.Registration_ID=pr.Registration_ID AND pp.Patient_Payment_ID= ppl.Patient_Payment_ID AND  ppl.Discount > 0 AND Transaction_Date_And_Time BETWEEN '$start_date' AND '$end_date' GROUP BY ppl.Patient_Payment_ID") or die(mysqli_error($conn));
    if(mysqli_num_rows($sql_discount)>0){
        while($rwdt = mysqli_fetch_assoc($sql_discount)){
            $Patient_Payment_ID = $rwdt['Patient_Payment_ID'];
            $patient_name = $rwdt['Patient_Name'];
            $gender = $rwdt['Gender'];
          
            $Registration_ID = $rwdt['Registration_ID'];
            $phone_number = $rwdt['Phone_Number'];
            $dob = $rwdt['Date_Of_Birth'];
            $Employee_ID = $rwdt['Employee_ID'];
            $then = $dob;
            $then = new DateTime($dob);
            $now = new DateTime();
            $sinceThen = $then->diff($now);
            $dob = $sinceThen->y.' y '. $sinceThen->m.' m '. $sinceThen->d.' d';
            $Employee_Name = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Employee_Name FROM tbl_employee WHERE Employee_ID='$Employee_ID'"))['Employee_Name'];
            $select_discount= mysqli_query($conn, "SELECT Check_In_Type ,Discount, Price ,Item_ID FROM tbl_patient_payment_item_list WHERE Patient_Payment_ID='$Patient_Payment_ID' AND Discount > 0  ") or die(mysqli_error($conn));
            $Total_Discount=0;
            $Total_price=0;
            $Amountpaid=0;
            while($dsc = mysqli_fetch_assoc($select_discount)){
                $Discount = $dsc['Discount'];
                $price = $dsc['Price'];
                $Item_ID = $dsc['Item_ID'];
                $Total_Discount +=$Discount;
                $Total_price +=$price;
                $Amountpaid = $Total_price - $Total_Discount; 
            }

        echo "<tr>
                <td  style='width:5%;box-sizing:border-box;'>".$i."</td>
                <td  style='width:20%;box-sizing:border-box;'><h6><b>".ucfirst($patient_name)."</b></h6></td>
                <td  style='text-align:center;width:7%;box-sizing:border-box;'>".$Registration_ID."</td>
                <td  style='text-align:center;width:7%;box-sizing:border-box;'>".$gender."</td>
                <td  style='text-align:center;width:7%;box-sizing:border-box;'>".$dob."</td>
                <td  style='text-align:center;width:7%;box-sizing:border-box;'>".($phone_number)."</td>
                <td class='rowlist' onclick='view_patent_dialog($Patient_Payment_ID)' style='text-align:center;width:7%;box-sizing:border-box;'>".number_format($Total_price)."</td>
                <td class='rowlist' onclick='view_patent_dialog($Patient_Payment_ID)' style='text-align:center;width:7%;box-sizing:border-box;'>".number_format($Total_Discount)."</td>
                <td class='rowlist' onclick='view_patent_dialog($Patient_Payment_ID)' style='text-align:center;width:7%;box-sizing:border-box;'>".number_format($Amountpaid)."</td>
                <td style='text-align:left;width:15%;box-sizing:border-box;'>$Employee_Name</td>
        </tr>";
            $i++;
            $total +=$Total_price;
            $overal_total_discount +=$Total_Discount;
            $overal_total_paid += $Amountpaid;
        }
    }else{
        echo "<tr><td colspan='9'>No result Found</td></tr>";
    }
    
    echo "<tr>
            <td style='width:20%;box-sizing:border-box;text-align:center' colspan='6'><h5><b>Total</b></h5></td>
            <td style='text-align:center;width:7%;box-sizing:border-box;'><h5><b>".number_format($total)."</b></h5></td>
            <td style='text-align:center;width:7%;box-sizing:border-box;'><h5><b>".number_format($overal_total_discount)."</b></h5></td>
            <td style='text-align:center;width:7%;box-sizing:border-box;'><h5><b>".number_format($overal_total_paid)."</b></h5></td>
            <td style='text-align:center;width:7%;box-sizing:border-box;'><h5><b></b></h5></td>
            </tr>";
    echo "</table>";
}

if(isset($_POST['viewitemdiscounted'])){
    $Patient_Payment_ID = $_POST['Patient_Payment_ID'];
    
    echo "<table class='table'>
        <tr>
            <th style='background:#ccc;'>SN</th>
            <th style='background:#ccc;'>Item Name</th>
            <th style='background:#ccc;'>Price</th>
            <th style='background:#ccc;'>Quantity</th>
            <th style='background:#ccc;'>Amount</th>
            <th style='background:#ccc;'>Discount</th>
            <th style='background:#ccc;'>Total</th>
            <th style='background:#ccc;'>Date</th>
        </tr>
    ";
    $num=1;
    $sql_discount = mysqli_query($conn, "SELECT Transaction_Date_And_Time,Quantity, Check_In_Type ,Discount, Price ,ppl.Item_ID , Product_Name FROM  tbl_items i, tbl_patient_payment_item_list ppl WHERE ppl.Discount > 0 AND ppl.Item_ID=i.Item_ID AND ppl.Patient_Payment_ID= '$Patient_Payment_ID' ") or die(mysqli_error($conn));
    if(mysqli_num_rows($sql_discount)>0){
        while($rwdt = mysqli_fetch_assoc($sql_discount)){
            $Check_In_Type = $rwdt['Check_In_Type'];
            $Discount = $rwdt['Discount'];
            $Product_Name = $rwdt['Product_Name'];   
            $Quantity = $rwdt['Quantity'];       
            $Price = $rwdt['Price'];
            $Date = $rwdt['Transaction_Date_And_Time'];
            $Amount =$Price * $Quantity;
            $Total = $Amount -$Discount;
            echo "<tr>
                    <td>$num</td>
                    <td>$Product_Name</td>
                    <td>".number_format($Price)." /=</td>
                    <td>$Quantity</td>
                    <td>".number_format($Amount)." /=</td>
                    <td>".number_format($Discount)."/=</td>
                    <td>".number_format($Total)." /=</td>
                    <td>$Date</td>
                </tr>";
                $num++;
                $TotalAmount += $Amount;
                $Total_Discount += $Discount;
                $Total_Total += $Total;
        }
    }
    echo "<tr>
                    <th colspan='4'>Total</th>                    
                    <td style='background:#ccc;'>".number_format($TotalAmount)." /=</td>
                    <td style='background:#ccc;'>".number_format($Total_Discount)." /=</td>
                    <td style='background:#ccc;'>".number_format($Total_Total)." /=</td>
                    
                </tr>";
    echo "</table>";
}

    if(isset($_POST['discountsummaryreport'])){
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];
        $i=1;
        echo "<table class='table'>";
        $Counter=0;
        $get_discounts = mysqli_query($conn, "SELECT Check_In_Type ,Item_ID FROM tbl_patient_payments pp,  tbl_patient_payment_item_list ppl WHERE  pp.Patient_Payment_ID= ppl.Patient_Payment_ID AND  ppl.Discount > 0 AND Transaction_Date_And_Time BETWEEN '$start_date' AND '$end_date' GROUP BY Check_In_Type") or die(mysqli_error($conn));
        $Check_In_Type='';
			if(mysqli_num_rows($get_discounts) > 0){
                while($data = mysqli_fetch_assoc($get_discounts)){
                    $Check_In_Type = $data['Check_In_Type'];
				echo '<tr><td colspan="2"><table class="table" border=1 style="border-collapse: collapse;">';
				echo  "<tr><td colspan='6'><b><span style='font-size: x-small;'>".++$Counter."&nbsp;&nbsp;&nbsp;GCHECK IN TYPE : </b>".strtoupper($data['Check_In_Type'])."</span></b></td></tr>";
				echo "<tr><td  width=5% style='background:#ccc; text-align: right;'><span style=' font-size: x-small;'><b>S/N</b></span></td>
							<td style='background:#ccc; text-align: right;'><b><span style=' font-size: x-small;'>ITEM NAME</span></b></td>
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
					echo "<tr>
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
                echo "<tr>
								<th colspan='2'>Total</th>
								<th style='background:#ccc; text-align: right;'><span style='font-size: x-small;'>".number_format($Ttyqty)."</span></th>
								<th style='background:#ccc; text-align: center;'><span style='font-size: x-small;'>".number_format($Ttyamount)."</span></th>
								<th style='background:#ccc; text-align: center;'><span style='font-size: x-small;'>".number_format($Ttydsc)."</span></th>
								<th style='background:#ccc; text-align: center;'><span style='font-size: x-small;'>".number_format($Ttlafterall)."</span></th>
							</tr>";
                echo "</table>";

            }
				
				echo  "</table>";
			}
    }