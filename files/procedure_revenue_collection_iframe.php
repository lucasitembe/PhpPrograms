<?php
  include './includes/connection.php';
     echo "<table class='display' id='numberTests' width='100%'> 
             <thead>
                <tr>
                    <th style='text-align:left;width:5%'>S/n</th>
                    <th style='text-align:left'>Test Name</th>
                    <th style='text-align:right;width:15%'>Quantity</th>
                    <th style='text-align:right;width:15%'>Price</th>
                </tr>
            </thead>";    
     
      $filter = '';
      
          if(isset($_POST['action']) && $_POST['action']=='getItem'){
               $fromDate=$_POST['fromDate'];
              $toDate=$_POST['toDate'];
              $sponsorName=$_POST['Sponsor'];
              $filter = "  WHERE ilc.Transaction_Date_And_Time BETWEEN '" . $fromDate . "' AND '" . $toDate . "' AND ilc.Check_In_Type='Procedure' AND ilc.Status = 'served'";
              if ($sponsorName != 'All') {
                    $filter .=" AND pp.Sponsor_ID='$sponsorName'";
               }
           
             
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
                echo '<tr>';
                echo '<td>'.$sn++.'</td>';
                echo '<td>'.$row['Product_Name'].'</td>';
                echo '<td style="text-align:right;">'.number_format($item['Item_Total']).'</td>';
                echo '<td style="text-align:right;">'.number_format($item['Item_Sum']).'</td>';
                //echo '<td style="text-align:left;">'.$row['Guarantor_Name'].'</td>';//
                echo '</tr>';
            }
            
//            echo "<tr>
//                    <td style='text-align:right;' colspan='4'>Grand Total: ".number_format($grandTotal)."</td>
//                </tr>";
          }
          
         
          if(isset($_POST['action']) && $_POST['action']=='getItem'){ 
          echo "<table class='display' id='numberTests' width='100%'"; 
           echo "<tr>
                  <td style='text-align:right;font-size:24px' colspan='4'>Grand Total: <strong>".number_format($grandTotal)."</strong></td>
                </tr>";
           echo '</table>';  
          }
          
     echo "</table>";   
?>
<div id="revenueitemsList" style="display: none">
    <div id="showrevenueitemList">
      
        
    </div>
</div>
<!--<script>
   $.fn.dataTableExt.sErrMode = 'throw';
   $('#numberTests').dataTable({
        "bJQueryUI": true
    });
</script>-->