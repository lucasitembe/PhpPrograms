<?php
// echo "<pre>";
// print_r($ageingReport);
// exit();

?>
<div class="table-responsive"> 
    <table class="table table-bordered"> 
        <thead> 
            <tr> 
                <th class="text-center">SN</th> 
                <th class="text-center">Supplier Name</th> 
                <th class="text-center">Invoice No</th> 
                <th class="text-center">Invoice Date</th> 
                <th class="text-center">Amount</th>   
            </tr> 
        </thead> 
        <tbody> 

        	<?php
             $grandTotal = 0;
          
            $i = 1;
            if ($ageingReport != null) {
                $amount_90=0;
                foreach ($ageingReport as $val) {

                      $supplier_name =$this->Helper->getSupplierById($val->supplier_id)->suppliername;
        
                   

            

            echo '<tr>
                   <td class="text-center">' . $i++ . '</td>
                   <td class="text-center">' . $supplier_name . '</td>
                    <td class="text-center">'.$val->invoice_no .'</td>
                    <td class="text-center">'.$val->transaction_date.'</td>
                    <td class="text-center">' .number_format($val->Amount). '</td>
             </tr>';

                    $grandTotal += $val->Amount;
              
                }

                


                echo '<tr><td colspan="4" class="text-center"><label class="control-label">Total</label></td>
                <td class="text-center"><label class="control-label">' .number_format($grandTotal). '</label></td>
                
                </tr>';
            }
            ?>





      </tbody> 
    </table> 
</div>




