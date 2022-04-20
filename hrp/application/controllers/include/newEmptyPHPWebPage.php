 include APPPATH.'/libraries/charts.php';
     $start_date = $this->session->userdata('from_date');
     $end_date = $this->session->userdata('to_date');
     $data =  $this->HR->kpi_general_report($start_date,$end_date,$category,$indicator);
      
      $status = $this->HR->kpistatuslist();
      
      $max = 0;
      
          
             $bar = new bar_glass();
             $bar->set_on_show(new bar_on_show('grow-up', 2.5, 0));
    
             $bar->set_tooltip("Total: #val#");
      
         
      
     if($indicator == null){
         
         $label=array();
         foreach ($data[$category] as $key => $value) {
             $indicator_n = $this->HR->kpi_indicator_list($key);
             $label[] =$indicator_n[0]->name; 
            
             foreach ($status as $xx => $yy) {
                 
                $bar->set_values($value[$deprtment][$yy->name]); 
                if($max < 10){
                    $max = $value[$deprtment][$yy->name];
                }else if( $value[$deprtment][$yy->name] > $max){
                    $max = $value[$deprtment][$yy->name];
                }
             }
             
      
         }
         
         $cat = $this->HR->kpicategorylist($category);
         $deprt = $this->HR->department($deprtment);
         $title = new title( $cat[0]->name.' - '.$deprt[0]->Name );

    
   
    
         
         
         
    $y_axis = new y_axis();
     
    if ( $max >10) {
            $y_axis->set_range(0, $max, 2);
        } else {
            $y_axis->set_range(0, 10, 2);
        }
     
        $x_axis = new x_axis();
        $x_labels = new x_axis_labels();
        
        $x_labels->set_labels($label);
        $x_labels->rotate(-45);
        $x_axis->set_labels($x_labels);
        
                

        $chart = new open_flash_chart();
        $chart->set_title($title);
        $chart->add_element($bar);
        $chart->add_y_axis($y_axis);
        $chart->set_x_axis($x_axis);

         
         
         
         
     }

      echo $chart->toPrettyString();