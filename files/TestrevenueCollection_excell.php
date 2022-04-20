<?php
include("includes/connection.php");
                $fromDate=$_GET['fromDate'];
              $toDate=$_GET['toDate'];
              $sponsorName=$_GET['sponsorName'];

              if($sponsorName=='All'){
               $numberSpecimen="SELECT * ,SUM(Price) AS TOTALAMOUNT,COUNT(Patient_Payment_Item_List_ID) AS TOTALITEMS FROM tbl_patient_payment_item_list AS tppit INNER JOIN tbl_patient_payments tpp ON tppit.Patient_Payment_ID=tpp.Patient_Payment_ID INNER JOIN tbl_items ti ON
                   ti.Item_ID=tppit.Item_ID WHERE ti.Consultation_Type='Laboratory' AND Payment_Date_And_Time BETWEEN '$fromDate' AND  '$toDate' GROUP BY tppit.Item_ID,Billing_Type";
                }  else {

                   $numberSpecimen="SELECT * ,SUM(Price) AS TOTALAMOUNT,COUNT(Patient_Payment_Item_List_ID) AS TOTALITEMS FROM tbl_patient_payment_item_list AS tppit INNER JOIN tbl_patient_payments tpp ON tppit.Patient_Payment_ID=tpp.Patient_Payment_ID INNER JOIN tbl_items ti ON
                   ti.Item_ID=tppit.Item_ID WHERE ti.Consultation_Type='Laboratory' AND tpp.Sponsor_ID='$sponsorName' AND Payment_Date_And_Time BETWEEN '$fromDate' AND  '$toDate' GROUP BY tppit.Item_ID,Billing_Type";

              }
             //echo $numberSpecimen;
             $totalrevenue= mysqli_query($conn,$numberSpecimen);

             $output ="<table class='display' id='revenuespecReport'> 
             <thead>
                <tr>
                    <th style='text-align:left'>S/n</th>
                    <th style='text-align:left'>Test Name</th>
                    <th style='text-align:left'>Transaction Type</th>
                    <th style='text-align:left'>Quantity</th>
                    <th style='text-align:left'>Total Amount(Tsh)</th>
                </tr>
            </thead>";             
            $sn=1;  
            $grandTotal=0;
            while ($row2=  mysqli_fetch_assoc($totalrevenue)){
            $Total=$row2['TOTALITEMS']*$row2['Price'];
            $grandTotal=$grandTotal+$Total;
            $output .='<tr>';
            $output .='<td>'.$sn++.'</td>';
            $output .='<td><p class="totalItems" id="'.$row2['Item_ID'].'" trans="'.$row2['Billing_Type'].'" style="cursor:pointer">'.$row2['Product_Name'].'</p></td>';
            $output .='<td style="text-align:left;">'.$row2['Billing_Type'].'</td>';
            $output .='<td style="text-align:left;">'.$row2['TOTALITEMS'].'</td>';
            $output .='<td style="text-align:left;">'.number_format($row2['TOTALAMOUNT']).'</td>';//
            $output .='</tr>';
            $Jumla_PEsa += $row2['TOTALAMOUNT'];
          }
          $output .="<tr><td colspan='4'>TOTAL AMOUNT COLLECTED</td><td>".number_format($Jumla_PEsa)."</td></tr>";
          $output .='</table>';  
          
          header("Content-Type:application/xls");
          header("content-Disposition: attachement; filename=Test_Revenue_Collection_Report.xls;");
          echo $output;          
?>