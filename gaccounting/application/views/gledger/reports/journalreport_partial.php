<div class="table-responsive"> 
    <table class="table table-hover"> 
        <thead> 
            <tr> 
                <th>SN</th> 
                <th>Date Time</th> 
                <th>Ref no.</th>
                <th>Account code</th>
                <th>Account</th>
                <th>Narration</th> 
                <th>Ledgers</th> 
                <th>Posted by</th> 
                <th class="text-right">Amount</th> 
                
            </tr> 
        </thead> 
        <tbody class="tbody-backc-color"> 
            <?php
            $grandTotal = 0;
            $i = 1;
            $k=0;
//
//
//            $new_array = array();
//            foreach ($journalreport as $item)
//                if (!array_key_exists($item->trans_id, $new_array))
//                    $new_array[$item->trans_id] = $item;
//                
//            echo '<pre>';            print_r($new_array);    

            if ($journalreport != null) {
                $i=1;
                $k=0;
                foreach ($JournalEntryIDs as $id) {
                    foreach ($journalreport as $val) {
                        if ($id['trans_id'] == $val['trans_id']) {
                            $acc_code = $val['acc_code'];
                            $prefix = strtoupper(substr($val['sec_desc'], 0, 2));
                            $code = $prefix . "-" . $acc_code;
                              $sn=$i;
                             if($k == $i){
                                 $sn='';
                             }
                            echo '<tr>';
                            echo '<td>' .$sn. '</td>';
                            echo '<td>' . $val['trans_date_time'] . '</td>';
                            echo '<td>' . $val['trans_id'] . '</td>';
                            echo '<td>' . $code . '</td>';
                            echo '<td>' . $val['acc_name'] . '</td>';
                            echo '<td>' . $val['comment'] . '</td>';
                            echo '<td>' . $val['ledger_name'] . '</td>';
                            if ($val['user_type'] == 1) {
                                echo '<td>' . $val['Employee_name'] . '</td>';
                            } elseif ($val['user_type'] == 2) {
                                echo '<td>' . $val['Employee_name'] . '</td>';
                            } else {
                                echo '<td>' . $val['user'] . '</td>';
                            }
                            echo '<td class="text-right">' . number_format($val['amount'], 2) . '</td>';
                            

                            echo '</tr>';
                            
                            $k=$i;
                        }
                        
                         
                    }
                    
                    $i++;
                   
                }

                echo '<tr><td colspan="8" class="text-left"><b>Balance</b></td><td class="text-right"><b>' . $grandTotal . '</b></td></tr>';
            }
            ?>

        </tbody> 
    </table> 
</div>
