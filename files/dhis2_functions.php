<?php

function dateRange( $first, $last, $step = '+1 day', $format = 'Y/m/d' ) {

          $dates = array();
          $current = strtotime( $first );
          $last = strtotime( $last );
        
          while( $current <= $last ) {
        
                $dates[] = date( $format, $current );
                $current = strtotime( $step, $current );
          }
        
            return $dates;
        
        }
?>