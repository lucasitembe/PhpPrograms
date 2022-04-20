
<?php

include("./includes/connection.php");

$Guarantor_Name="All";
$toDate='';
$fromDate='';
      
          if(isset($_GET['fromDate'])){
               $fromDate=$_GET['fromDate'];
              $toDate=$_GET['toDate'];
              $sponsorName=$_GET['Sponsor'];
              $filter = "  WHERE ilc.Transaction_Date_And_Time BETWEEN '" . $fromDate . "' AND '" . $toDate . "' AND ilc.Check_In_Type='Radiology' AND ilc.Status = 'served'";
               if ($sponsorName != 'All') {
                    $filter .=" AND pp.Sponsor_ID='$sponsorName'";
                    
                    $rs=  mysqli_query($conn,"SELECT Guarantor_Name FROM tbl_sponsor WHERE Sponsor_ID='$sponsorName'") or die(mysqli_error($conn));
               
                    $Guarantor_Name=  mysqli_fetch_assoc($rs)['Guarantor_Name'];
              }
           }
           
$htm = "<center><img src='branchBanner/branchBanner.png' width='100%' ></center>";
$htm.="<p align='center'><b>RADIOLOGY REVENUE COLLECTION REPORT <br/>FROM</b> <b style=''>" . $fromDate . "</b> <b>TO</b> <b style=''>" . $toDate . "</b>"
        . "<br/>"
        . "<b>Sponsor:</b>$Guarantor_Name"
        . "</p>";
 $htm .= "<table class='display' id='numberTests' width='100%'> 
             <thead>
                <tr>
                    <th style='text-align:left;width:5%'>S/n</th>
                    <th style='text-align:left'>Test Name</th>
                    <th style='text-align:right;width:15%'>Quantity</th>
                    <th style='text-align:right;width:15%'>Price</th>
                </tr>
             </thead> 
                <tr>
                 <td colspan='4'><hr width='100%'/></td>
                <tr/>
            </thead>";
             
             $number_of_item="SELECT i.Product_Name,i.Item_ID,Guarantor_Name FROM tbl_item_list_cache ilc  INNER JOIN tbl_payment_cache pp ON pp.Payment_Cache_ID =ilc.Payment_Cache_ID JOIN tbl_sponsor sp ON sp.Sponsor_ID=pp.Sponsor_ID JOIN tbl_items i ON ilc.Item_ID=i.Item_ID $filter GROUP BY Product_Name";
                   
            // echo $number_of_item;exit;
             $number_of_item_results= mysqli_query($conn,$number_of_item) or die(mysqli_error($conn));
                    
            $sn=1;  
            $grandTotal=0;
            while ($row=  mysqli_fetch_assoc($number_of_item_results)){
                
                
                  $number_item_count="SELECT SUM(ilc.Quantity) as Item_Total, SUM((ilc.Price-ilc.Discount)*ilc.Quantity) as Item_Sum FROM tbl_item_list_cache ilc  INNER JOIN tbl_payment_cache pp ON pp.Payment_Cache_ID =ilc.Payment_Cache_ID JOIN tbl_sponsor sp ON sp.Sponsor_ID=pp.Sponsor_ID JOIN tbl_items i ON ilc.Item_ID=i.Item_ID $filter AND ilc.Item_ID='".$row['Item_ID']."'";
                  
                  //die($number_item_count);
                  $number_of_item_count_results= mysqli_query($conn,$number_item_count) or die(mysqli_error($conn));
                  $itemCount=  mysqli_num_rows($number_of_item_count_results);
                  $item= mysqli_fetch_array($number_of_item_count_results);
                  //$itemCount=  mysqli_fetch_assoc($number_of_item_count_results)['Number_Of_Item'];
                  $grandTotal +=$item['Item_Sum'];
                $htm .= '<tr>';
                $htm .= '<td>'.$sn++.'</td>';
                $htm .= '<td>'.$row['Product_Name'].'</td>';
                $htm .= '<td style="text-align:right;">'.number_format($item['Item_Total']).'</td>';
                $htm .= '<td style="text-align:right;">'.number_format($item['Item_Sum']).'</td>';
                //echo '<td style="text-align:left;">'.$row['Guarantor_Name'].'</td>';//
                $htm .= '</tr>';
                $htm .= '</tr>'
                        . '<tr>
                            <td colspan="4"><hr width="100%"/></td>
                           <tr/>';
            }
             $htm .= "<tr>
                    <td style='text-align:right;' colspan='4'>Grand Total: <strong>".number_format($grandTotal)."</strong></td>
                   </tr>";
          $htm .= '</table>';  
         
            

        include("MPDF/mpdf.php");
       
        $mpdf=new mPDF(); 

        $mpdf->SetDisplayMode('fullpage');
        $mpdf->WriteHTML($htm);
        $mpdf->Output();
        exit; 


?>



