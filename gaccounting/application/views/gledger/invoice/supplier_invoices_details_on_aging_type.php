<?php
if($invoices){
                    $temp_total_30 = 0;
                    $temp_total_60 = 0;
                    $temp_total_90 = 0;
                    $temp_total_120 = 0;
                    $temp_total_150 = 0;
                    $temp_total_others = 0;
                    foreach ($invoices as $invoice) {
                      $date1 = new DateTime($invoice->transaction_date);
                      $interval = $date1->diff($date2);
                      $days=$interval->days; 
                      if (0 <= $days  && $days < 30) {
                          $temp_total_30 += $invoice->Amount; 
                      }else if (30 <= $days  && $days < 60) {
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