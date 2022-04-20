<h2 style="padding:0px; margin: 0px;"> KPI Raw Data Report</h2><hr/>


<div class="formdata" style=" width: 1200px;">
    <div class="formdata-title">
   Select Criteria
    </div>
    <div class="formdata-content" style="width: 97%;">
        
        <?php echo form_open('hr/kpireportraw/','style="width:100%;"'); ?>
        
        <table style=" width: 100%;">
             <?php 
 
            if($this->session->flashdata('message') !=''){
            	?>
            	<tr>
            	<td colspan="4">
            	<div class="message">
            	<?php echo $this->session->flashdata('message'); ?>
            	</div>
            	</td>
            	</tr>
            	<?php
            }else if(isset($error_in)){
            	?>
            	<tr>
            	<td colspan="4" >
            	<div class="message">
            	<?php echo $error_in; ?>
            	</div>
            	</td>
            	</tr>
            	
            	<?php 
            }
            ?>
                <tr>
                    <td>Employee ID<span>*</span></td>
                    <td>
            <input type="text" id="employee" name="employee" value="<?php echo set_value('employee') ;?>"/>
                       <img    style="cursor: pointer;"
                                                            src="<?php echo base_url(); ?>images/search.png"
                                                            onclick='targetitem = document.forms[0].employee; 
 window.open("employeelist_search/"+targetitem.name, "dataitem", "toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=no, width=1000,height=500,left=430,top=23");' /> 
            <?php echo form_error('employee'); ?>
                    </td>
                    
                     <td> From Date<span>*</span></td>
                    <td>
                        <input type="text" name="date" value="<?php echo set_value('date') ;?>"/>
                 <img    style="cursor: pointer;"
                                                            src="<?php echo base_url(); ?>images/calendar.png"
                                                            onclick="displayCalendar(document.forms[0].date,'yyyy-mm-dd',this)" />       
                   
                    <?php echo form_error('date'); ?>
                    </td>
                    <td> Up to <span>*</span></td>
                    <td>
                        <input type="text" name="update" value="<?php echo set_value('update') ;?>"/>
                 <img    style="cursor: pointer;"
                                                            src="<?php echo base_url(); ?>images/calendar.png"
                                                            onclick="displayCalendar(document.forms[0].update,'yyyy-mm-dd',this)" />       
                   
                    <?php echo form_error('update'); ?>
                    </td>
                    <td>&nbsp;</td>
                    <td><input type="submit" value="Generate Report"/></td>
                </tr>
              
                
        </table>
        <?php echo form_close() ?>
    </div>
</div>

<div style="height: 10px;"></div>
<?php

if(isset ($report)){
     
    ?>
<h2 style="padding-bottom: 0px; margin-bottom: 0px;">Available Report for KPIs from <?php echo $from; ?> up to <?php echo $to; ?></h2><hr/>
<?php
    if(count($report) > 0){ ?>
<div style="overflow-x: auto; padding-bottom: 20px;">
<table>
    <tr>
        <td style="width: 100px;" valign="top">
    
        <img src="<?php echo base_url().'uploads/photo/'.employee_photo($id); ?>" style="width: 150px; border: 2px solid #CCCCCC; height: 180px;"/>
    
        </td>
        <td valign="top">
            <div style="margin-left: 20px;">
                <?php
                $in_data=employee_basic_data($id);
                ?>
            <h3 style=" color: green; padding: 0px 0px 0px 10px; margin: 5px;">Employee ID: <?php echo $in_data[0]->EmployeeId; ?>  &nbsp; &nbsp; &nbsp; Employee Name : <?php echo $in_data[0]->FirstName.' '.$in_data[0]->LastName; ?></h3>        
            
            <?php
            foreach ($report as $key => $value) { ?>
            <div style="font-size: 16px; text-transform: uppercase; font-weight: bold;">
                <br/>
                Category : <?php
            $kpi_cat = $this->HR->kpicategorylist($key);
            echo $kpi_cat[0]->name; ?></div>
            <?php
            $indicators = array_keys($report[$key]);
            $from_date=explode("-", $from);
            $up_date=explode("-", $to);
           $from_date_timestamp = mktime(0,0,0,$from_date[1],$from_date[2],$from_date[0]);
           $up_date_timestamp = mktime(0,0,0,$up_date[1],$up_date[2],$up_date[0]);
            ?>
            <table class="view_data" style="width: auto;" cellspacing="0" cellpadding="0">
                <tr>
                    <th style="min-width: 300px;"> &nbsp; KPI Indicator &nbsp; </th>
                    <?php
                    $index = $from_date_timestamp;
                    $pp=true;
                    while($pp){
                  ?>
                    <th style="min-width: 80px;"> &nbsp; <?php echo date('d-m-Y',$index); ?>&nbsp; <br/>
                    &nbsp; <?php echo date('l',$index) ?>&nbsp;
                    </th>
                  <?php   
                $index1 = strtotime('+1 days',$index);
                $index = $index1;
                
                if($index > $up_date_timestamp){
                  $pp=FALSE;  
                  }
                  } 
                  ?>
                    <th style="min-width: 300px;  width: auto;">  &nbsp; Total</th>
                </tr>
                
                
                <?php
                
               
                            foreach ($indicators as $kk => $vv) {
                                 $overall=array();
                                ?>
                <tr>
                    <td style="padding: 0px 5px 0px 5px;"><?php 
                     $indicator_info = $this->HR->kpi_indicator_list($vv);
                    echo $indicator_info[0]->name; ?></td>
                     <?php
                     
                    $index = $from_date_timestamp;
                    $pp=true;
                    while($pp){
                  ?>
                    <td><?php 
                     if(array_key_exists(date('Y-m-d',$index), $report[$key][$vv])){
                    $date_dd =$report[$key][$vv][date('Y-m-d',$index)];
                    foreach ($date_dd as $a => $b) {
                        if($b > 0){
                            $status = $this->HR->kpistatuslist($a);
                            echo $status[0]->name .' = '.$b;
                            $overall[$status[0]->name]=+$b;
                        }
                    }
                    }else{
                    echo '';
                    }
                    ?></td>
                  <?php   
                $index1 = strtotime('+1 days',$index);
                $index = $index1;
                
                if($index > $up_date_timestamp){
                  $pp=FALSE;  
                  }
                  } 
                  ?>
                    <td>
                        <?php 
                        $disp='';
                      foreach ($overall as $st => $co) {
                          $disp .= $st.' = '.$co.', ';
                      }
                      echo rtrim($disp, ', ');
                      
                        ?>
                    </td>
                </tr>
                           <?php  }          ?>
                
            </table>
            <? }   ?>
            
            </div>
        
        </td>
    </tr>
</table>
</div>
    <?php } } //else{
    // echo 'No data found !!' ;  
   // }
}
?>


