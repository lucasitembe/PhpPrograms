               
<div class="table-responsive"> 
    <table class="table table-bordered"> 
        <thead> 
            <tr> 
                <th>SN</th> 
                <th>Supplier Name</th> 
                <th>30 Days</th> 
                <th>60 Days</th> 
                <th>90 Days</th> 
                <th>120 Days</th> 
                <th>150 Days</th> 
                <th>Above 150 Days</th> 
                
            </tr> 
        </thead> 
        <tbody> 
            <?php
              if(count($suppliers)){
                 $sn = 1;
                 $grandTotal = 0;
                 $total_30=0;
                 $total_60=0;
                 $total_90=0;
                 $total_120=0;
                 $total_150=0;
                 $total_other=0;

                 $grand_total_30=0;
                 $grand_total_60=0;
                 $grand_total_90=0;
                 $grand_total_120=0;
                 $grand_total_150=0;
                 $grand_total_other=0;

                 $date2 = new DateTime(date('y-m-d'));
                foreach ($suppliers as $supplier) { 
                  //get the list of invoices

                  $invoices = $this->Helper->getInvoicesBySupplierId($supplier->supplier_id);
                  if($invoices){
                    $temp_total_30 = 0;
                    $temp_total_60 = 0;
                    $temp_total_90 = 0;
                    $temp_total_120 = 0;
                    $temp_total_150 = 0;
                    $temp_total_others = 0;
                    foreach ($invoices as $invoice) {
                       // print_r($invoice);
                       // exit();
                      $date1 = new DateTime($invoice->transaction_date);
                      $interval = $date1->diff($date2);
                      $days=$interval->days; 
                      if (0 <= $days  && $days < 30) {
                          $temp_total_30 += $invoice->Amount; 
                      }
                      if (30 <= $days  && $days < 60) {
                          $temp_total_60 += $invoice->Amount; 
                      }
                      if (60 <= $days  && $days < 90) {
                          $temp_total_90 += $invoice->Amount; 
                      }
                      if (90 <= $days  && $days < 120) {
                          $temp_total_120 += $invoice->Amount; 
                      }
                      if (120 <= $days  && $days < 150) {
                          $temp_total_150 += $invoice->Amount; 
                      }
                      if (150 <= $days) {
                          $temp_total_others += $invoice->Amount; 
                      }

                    
                    }
                     $grand_total_30 += $temp_total_30;
                     $grand_total_60 += $temp_total_60;
                     $grand_total_90 += $temp_total_90;
                     $grand_total_120 += $temp_total_120;
                     $grand_total_150 += $temp_total_150;
                     $grand_total_other += $temp_total_others;

                    $total_30 = $temp_total_30; $temp_total_30 = 0;
                    $total_60 = $temp_total_60; $temp_total_60 = 0;
                    $total_90 = $temp_total_90; $temp_total_90 = 0;
                    $total_120 = $temp_total_120; $temp_total_120 = 0;
                    $total_150 = $temp_total_150; $temp_total_150 = 0;
                    $total_other = $temp_total_others; $temp_total_others = 0;
                  }
                  ?>
                  <tr>
                    <td><?= $sn ?></td>
                    <td><?= $supplier->suppliername ?></td>
                    <td class="text-center"><?= number_format($total_30,2)?></td>
                    <td class="text-center"><?= number_format($total_60,2) ?></td>
                    <td class="text-center"><?= number_format($total_90,2) ?></td>
                    <td class="text-center"><?= number_format($total_120,2) ?></td>
                    <td class="text-center"><?= number_format($total_150,2) ?></td>
                    <td class="text-center"><?= number_format($total_other,2) ?></td>
                  </tr>
            <?php    }
            echo '<tr><td colspan="2" class="text-center"><label class="control-label">Total</label></td>
                <td class="text-center"><label class="control-label">' .number_format($grand_total_30,2). '</label></td>
                <td class="text-center"><label class="control-label">' .number_format($grand_total_60,2) . '</label></td>
                <td class="text-center"><label class="control-label">' .number_format($grand_total_90,2). '</label></td>
                <td class="text-center"><label class="control-label">' .number_format($grand_total_120,2). '</label></td>
                <td class="text-center"><label class="control-label">' .number_format($grand_total_150,2). '</label></td>
                <td class="text-center"><label class="control-label">' .number_format($grand_total_other,2). '</label></td>
                </tr>';
              }
            ?>
            
        </tbody> 
    </table> 
</div>
         
        