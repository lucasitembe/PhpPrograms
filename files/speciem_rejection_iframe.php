<?php
  include './includes/connection.php';
     echo "<table class='display' id='specimenRejection' width='100%'> 
             <thead>
                <tr>
                    <th style='text-align:left;width:5%'>S/n</th>
                    <th style='text-align:left'>Patient Name</th>
                     <th style='text-align:left'>Test Name</th>
                    <th style='text-align:left'>Rejected specimen</th>
                    <th style='text-align:left'>Rejection reason</th>
                    <th style='text-align:left'>Date and Time</th>
                    <th style='text-align:left'>Rejected by</th>
   
                </tr>
            </thead>";    
     
           $filter = '   WHERE DATE(sr.Date_Rejected) BETWEEN CURDATE()-INTERVAL 1 DAY AND DATE(NOW())';
      
          if(isset($_POST['action']) && $_POST['action']=='getItem'){
               $fromDate=$_POST['fromDate'];
              $toDate=$_POST['toDate'];
              $sponsorName=$_POST['Sponsor'];
              $filter = "  WHERE sr.Date_Rejected BETWEEN '" . $fromDate . "' AND '" . $toDate . "' AND sr.Rejection_Status='Rejected'";
              if ($sponsorName != 'All') {
                    $filter .=" AND pp.Sponsor_ID='$sponsorName'";
               }
           }
             
                  $number_of_item="SELECT * FROM tbl_specimen_results sr INNER JOIN tbl_item_list_cache ilc ON ilc.Payment_Item_Cache_List_ID=sr.payment_item_ID JOIN tbl_laboratory_specimen ls ON sr.Specimen_ID=ls.Specimen_ID JOIN tbl_payment_cache pp ON pp.Payment_Cache_ID =ilc.Payment_Cache_ID JOIN tbl_patient_registration pr ON pr.Registration_ID=pp.Registration_ID JOIN tbl_employee te ON te.Employee_ID=sr.rejected_by JOIN tbl_sponsor sp ON sp.Sponsor_ID=pp.Sponsor_ID JOIN tbl_items ti ON ti.Item_ID=ilc.Item_ID $filter";
                  
            // echo $number_of_item;exit;
             $number_of_item_results= mysqli_query($conn,$number_of_item) or die(mysqli_error($conn));
                    
            $sn=1;  
            $grandTotal=0;
            while ($row=  mysqli_fetch_assoc($number_of_item_results)){
                
                  $number_item_count="SELECT ls.Specimen_ID FROM tbl_specimen_results sr INNER JOIN tbl_item_list_cache ilc ON ilc.Payment_Item_Cache_List_ID=sr.payment_item_ID JOIN tbl_laboratory_specimen ls ON sr.Specimen_ID=ls.Specimen_ID JOIN tbl_payment_cache pp ON pp.Payment_Cache_ID =ilc.Payment_Cache_ID JOIN tbl_sponsor sp ON sp.Sponsor_ID=pp.Sponsor_ID $filter AND sr.Specimen_ID='".$row['Specimen_ID']."'";
                  
                  //die($number_item_count);
                  $number_of_item_count_results= mysqli_query($conn,$number_item_count) or die(mysqli_error($conn));
                  $itemCount=  mysqli_num_rows($number_of_item_count_results);
                  //$itemCount=  mysqli_fetch_assoc($number_of_item_count_results)['Number_Of_Item'];
                
                echo '<tr>';
                echo '<td>'.$sn++.'.</td>';
                echo '<td>'.$row['Patient_Name'].'</td>';
                echo '<td>'.$row['Product_Name'].'</td>';
                echo '<td>'.$row['Specimen_Name'].'</td>';
                echo '<td>'.$row['rejected_reason'].'</td>';
                echo '<td>'.$row['Date_Rejected'].'</td>';
                echo '<td>'.$row['Employee_Name'].'</td>';
//                echo '<td style="text-align:left;">'.$itemCount.'</td>'; 
                //echo '<td style="text-align:left;">'.$row['Guarantor_Name'].'</td>';//
                echo '</tr>';
            }
          echo '</table>';  
       
?>
<div id="revenueitemsList" style="display: none">
    <div id="showrevenueitemList">
      
        
    </div>
</div>
<script>
   $('#specimenRejection').dataTable({
        "bJQueryUI": true
    });
</script>