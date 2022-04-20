<?php

$acc1 = file_get_contents("http://localhost/Final_One/files/get_sponsor_invoice.php");
 $data = json_decode($acc1);

 // print_r($data);
 // exit();
  
 $length=count( $data);

 $sponsor = file_get_contents("http://localhost/Final_One/files/get_sponsors_list.php");
 $sponsor_data = json_decode( $sponsor);

  $length_sponsor=count($sponsor_data);


?>

<div class="row">
    <div class="col-lg-12">
        <ol class="breadcrumb">
            <li><i class="fa fa-home"></i><a href="<?= site_url('Home') ?>"> Dashboard</a></li>
            <li><i class="fa fa-laptop"></i><a href="<?= site_url('gledger') ?>"> General Ledger</a></li>                               
        </ol>
    </div>
</div>

<br/>
</div>
<div class="row">
    <div class="col-lg-12">
        <div id='table-options' class="pull-right">
            <a href="customer_aging_report?report" id="ajaxUpdateContainerPdf" class="btn btn-primary" target="_blank"><i class="fa fa-print"></i> Pdf</a> 
        </div>
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-bar-chart-o"></i> Ageing Report </h3>
            </div>
            <div class="panel-body" id="ajaxUpdateContainer">
               
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
         
            
            $date2 = new DateTime(date('y-m-d'));
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
            $x = 1;
            // if ($ledgerStatementReport != null) {
                $amount_90=0;
                for ($i=0; $i < $length ; $i++) { 

                    // $supplier_name =$this->Helper->getSupplierById($val['supplier_id'])->suppliername;

                    $date1 = new DateTime($data[$i]->trans_date);
                    $interval = $date1->diff($date2);

                      
                    $days=$interval->days; 

                    $grandTotal += $data[$i]->amount;
                   

                    echo '<tr>';
                    echo '<td class="text-center">' . $x++ . '</td>';

                    echo '<td class="text-center">' .$data[$i]->sponsor . '</td>';
                  if (0 <= $days  && $days < 30) {
                          $amount_30=$data[$i]->amount ; 
                        echo '<td class="text-center">'.number_format($amount_30) . '</td>';
                     }else{
                        $amount_30=0;
                        echo '<td class="text-center">' .number_format( $amount_30). '</td>';

                     }
                     if (30 <= $days  && $days < 60) {
                          $amount_60=$data[$i]->amount ; 
                        echo '<td class="text-center">' . number_format($amount_60). '</td>';
                     }else{
                        $amount_60=0;
                       echo '<td class="text-center">' .number_format( $amount_60). '</td>';
                     }
                    
                   
                    if (60 <= $days  && $days < 90) {
                          $amount_90=$data[$i]->amount; 
                        echo '<td class="text-center">' . number_format($amount_90). '</td>';
                     }else{
                        $amount_90=0;
                       echo '<td class="text-center">' .number_format( $amount_90). '</td>';
                     }

                     if (90 <= $days  && $days < 120) {
                          $amount_120=$data[$i]->amount ; 
                        echo '<td class="text-center">'.number_format( $amount_120) . '</td>';
                     }else{
                        $amount_120=0;
                        echo '<td class="text-center">' .number_format( $amount_120) . '</td>';
                     }
                    
                     if (120 <= $days  && $days < 150) {
                          $amount_150=$data[$i]->amount; 
                        echo '<td class="text-center">' .number_format( $amount_150) . '</a></td>';
                     }else{
                        $amount_150=0;
                        echo '<td class="text-center"><a></a>' . number_format($amount_150) . '</td>';
                     }
                     if (150 <= $days) {
                          $amount_other=$data[$i]->amount; 
                       echo '<td class="text-center">' .number_format($amount_other). '</td></tr>';
                     }else{
                        $amount_other=0;
                        echo '<td class="text-center">' . number_format($amount_other ). '</td>';
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
                <td class="text-center"><label class="control-label">' .number_format($total_150). '</label></td>
                <td class="text-center"><label class="control-label">' .number_format($total_other). '</label></td>
                </tr>';

                  
            // }
            ?>

        </tbody> 
    </table> 
</div>
            </div>
        </div>
    </div>
</div>