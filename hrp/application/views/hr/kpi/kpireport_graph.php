<script type="text/javascript">
    $(document).ready(function(){
        // third example
        $("#red").treeview({
            animated: "slow",
            collapsed: false,
            unique: true,
            persist: "cookie",
            toggle: function() {
                window.console && console.log("%o was toggled", this);
            }
        });
    });
</script>
<h2 style="padding-bottom: 0px; margin-bottom: 0px;">KPIs Dashboard</h2><hr/>
<div style=" display: block;">
    <div style="float: left; width: 250px; border: 1px solid #ccc; padding-bottom: 20px;">
        <ul id="red" class="treeview">
            <li><span><?php echo anchor('hr/kpireport/','All Category'); ?></span></li>
            <?php
            $cat = $this->HR->kpicategorylist();
            foreach ($cat as $key => $value) {
                ?>
            <li><span><?php echo anchor('hr/kpireport/'.$value->id,$value->name); ?></span>
                    <ul> 
                        <?php
                        $ind = $this->HR->kpi_indicator_list_data(null, $value->id);

                        foreach ($ind as $k => $v) {
                            ?>
                            <li>
                                <span>
                            <?php echo anchor('hr/kpireport/'.$value->id.'/'.$v->id, $v->name); ?>
                                    </span> 
                            </li>
    <?php } ?>
                    </ul>
                </li>

<?php } ?>

    </div>
    <div style="float: left; margin-left: 20px; display: inline-block;">
        <div class="formdata formdata-content" style="width: 900px;" >


                <?php echo form_open('hr/kpireport', 'style="width:870px; padding:0px; margin:0px;"'); ?>
            <table style="width: 870px;">
                <?php
                if (isset($error_in)) {
                    ?>
                    <tr>
                        <td colspan="7">
                            <div class="message">
    <?php echo $error_in; ?>
                            </div>
                        </td>
                    </tr>
<?php } ?>
                <tr>
                    <td>
                        From date : 
                    </td>
                    <td>
                        <input type="text" name="from_date" value="<?php echo $this->session->userdata('from_date');
; ?>"/>
                        <img    style="cursor: pointer;"
                                src="<?php echo base_url(); ?>images/calendar.png"
                                onclick="displayCalendar(document.forms[0].from_date,'yyyy-mm-dd',this)" />       

<?php echo form_error('from_date'); ?>
                    </td>
                    <td>
                        Up to  : 
                    </td>
                    <td>
                        <input type="text" name="to_date" value="<?php echo $this->session->userdata('to_date'); ?>"/>
                        <img    style="cursor: pointer;"
                                src="<?php echo base_url(); ?>images/calendar.png"
                                onclick="displayCalendar(document.forms[0].to_date,'yyyy-mm-dd',this)" />       

<?php echo form_error('to_date'); ?>
                    </td>
                    <td>&nbsp;</td>
                    <td>
                        <input type="submit" name="set_date_range" value="Change Date Range"/>
                    </td>
                </tr>
            </table>

            <hr/>

<?php echo form_close() ?>


            <div style="padding-top: 20px; overflow-x:auto;">

<?php foreach ($report as $key => $value) { 

        
    ?>
                    <div style="font-size: 16px; text-transform: uppercase; font-weight: bold;">
                        <br/>
                        Category : <?php
    $kpi_cat = $this->HR->kpicategorylist($key);
    echo $kpi_cat[0]->name;
    ?></div>
                    <table class="view_data" style="width: auto;" cellspacing="0" cellpadding="0">
                        <tr>
                            <th style="min-width: 400px;"> &nbsp; KPI Indicator &nbsp; </th>
                           
                            <th style="min-width: 300px;"> &nbsp; &nbsp; <?php echo (isset ($show_graph) ? anchor('hr/kpireport/'.$main_cat.'/'.$key, 'KPIs Status') :  'KPIs Status'); ?> &nbsp; &nbsp; </th>
                       
                        </tr>
                        <?php
                        foreach ($report[$key] as $in_key => $in_val) {
                            $indc= $this->HR->kpi_indicator_list($in_key);
                            ?>
                        <tr>
                            <td>
                                <?php echo $indc[0]->name; ?>
                            </td>
                            <?php
                            
                                ?>
                            <td style="padding: 0px 7px 0px 7px;"> 
                                <?php
                                $str='';
                                    foreach ($report[$key][$in_key] as $st_key => $st_val) {
                                        $str.=$st_key." = ".$st_val.', ';
                                    }
                                    echo trim($str, ', ');
                                ?>
                                </td>
                        
                        </tr>
                        <?php }     ?>
                    </table>
                <?php
                }
                echo  '</div>';
                //echo '<pre>';
                //print_r($report);
                //echo '</pre>';
                
                if(isset ($show_graph) && !isset ($raw)){
                    $this->load->view('hr/kpi/kpi_graph');
                }else if(isset ($show_graph) && isset ($raw)){
                  $this->load->view('hr/kpi/kpi_graph_raw');  
                }
                ?>
                
               
           


        </div>
<?php ?>
    </div>
    <div style="clear: both;"></div>
</div>
