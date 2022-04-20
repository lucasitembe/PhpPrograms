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
         
            
           
            $amount_30=0;
            $amount_60=0;
            $amount_90=0;
            $amount_120=0;
            $amount_150=0;
            $amount_other=0;

             $grandTotal = 0;
             $total_30=0;
             $total_60=0;
             $total_90=0;
             $total_120=0;
             $total_150=0;
             $total_other=0;
            $i = 1;
            if ($ledgerStatementReport != null) {
                $amount_90=0;
                foreach ($ledgerStatementReport as $val) {

                     $supplier_name =$this->Helper->getSupplierById($val['ivid'])->suppliername;
                    $date2 = new DateTime(date('y-m-d'));
                    $date1 = new DateTime($val['trans_date']);
                    $interval = $date1->diff($date2);

                      
                    $days=$interval->days; 

                    $grandTotal += $val['amount'];
                   

                    echo '<tr>';
                    echo '<td class="text-center">' . $i++ . '</td>';

                    echo '<td class="text-center">' . $supplier_name . '</td>';
                  if (0 <= $days  && $days < 30) {
                          $amount_30=$val['amount'] ; 
                        echo '<td class="text-center"><a href="../assets/invoice_image/'.$val['iv_image'].'">' .number_format( $amount_30). '</a></td>';
                     }else{
                        $amount_30=0;
                        echo '<td class="text-center">' . $amount_30. '</td>';

                     }
                     if (30 <= $days  && $days < 60) {
                          $amount_60=$val['amount'] ; 
                        echo '<td class="text-center">' .number_format( $amount_60). '</td>';
                     }else{
                        $amount_60=0;
                       echo '<td class="text-center">' . $amount_60 . '</td>';
                     }
                    
                   
                    if (60 <= $days  && $days < 90) {
                          $amount_90=$val['amount'] ; 
                        echo '<td class="text-center">' .number_format( $amount_90). '</td>';
                     }else{
                        $amount_90=0;
                       echo '<td class="text-center">' . $amount_90 . '</td>';
                     }

                     if (90 <= $days  && $days < 120) {
                          $amount_120=$val['amount'] ; 
                        echo '<td class="text-center">' . number_format($amount_120 ). '</td>';
                     }else{
                        $amount_120=0;
                        echo '<td class="text-center">' . number_format($amount_120) . '</td>';
                     }
                    
                     if (120 <= $days  && $days < 150) {
                          $amount_150=$val['amount'] ; 
                        echo '<td class="text-center">' . number_format($amount_150) . '</td>';
                     }else{
                        $amount_150=0;
                        echo '<td class="text-center">' . $amount_150 . '</td>';
                     }
                     if (150 <= $days) {
                          $amount_other=$val['amount'] ; 
                       echo '<td class="text-center">' .number_format($amount_other). '</td></tr>';
                     }else{
                        $amount_other=0;
                        echo '<td class="text-center">' . $amount_other . '</td>';
                     }

                   
                    

                    

                    echo '</tr>';

                    $total_30 += $amount_30;
                    $total_60 += $amount_60;
                    $total_90 += $amount_90;
                    $total_120 += $amount_120;
                    $total_150 += $amount_150;
                    $total_other += $amount_other;
                    
                }

                


                echo '<tr><td colspan="2" class="text-center"><label class="control-label">Total</label></td>
                <td class="text-center"><label class="control-label">' .number_format($total_30). '</label></td>
                <td class="text-center"><label class="control-label">' .number_format($total_60) . '</label></td>
                <td class="text-center"><label class="control-label">' .number_format($total_90). '</label></td>
                <td class="text-center"><label class="control-label">' .number_format($total_120). '</label></td>
                <td class="text-center"><label class="control-label">' .number_format( $total_150). '</label></td>
                <td class="text-center"><label class="control-label">' .number_format($total_other). '</label></td>
                </tr>';
            }
            ?>

        </tbody> 
    </table> 
</div>