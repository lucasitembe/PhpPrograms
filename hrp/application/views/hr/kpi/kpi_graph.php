<style type="text/css">
    object{
        z-index: -999;
    }
</style>
<div style="height: 40px;"></div>
<div style="margin-right: 20px; ">
<h2 style="padding:20px 0px 0px 0px;  margin: 0px;">Graphical Report</h2><hr/>
   <div style="height: 20px;"></div>
<script type="text/javascript" src="<?php echo base_url() ?>assets/js/swfobject.js"></script>
    <script type="text/javascript">
        <?php
         if(isset ($show_3_graph)){
             $status= $this->HR->kpistatuslist();
             foreach ($status as $key=>$value){
        ?>
            
             swfobject.embedSWF(
              "<?php echo base_url() ?>assets/swf/open-flash-chart.swf", "<?php echo $value->id ?>-miltone", "900", "300",
              "9.0.0", "expressInstall.swf",
              {"data-file":"<?php echo urlencode($graph_path."/".$value->id); ?>"}
              );
            
            
            <?php } }else{ ?>
            swfobject.embedSWF(
              "<?php echo base_url() ?>assets/swf/open-flash-chart.swf", "miltone", "700", "300",
              "9.0.0", "expressInstall.swf",
              {"data-file":"<?php echo urlencode($graph_path); ?>"}
              );
                 <?php } ?>
                </script>
              
                     <?php
         if(isset ($show_3_graph)){
        $status= $this->HR->kpistatuslist();
             foreach ($status as $key=>$value){
        ?>
                
                <div style="height: 20px;"></div>
                <div  id="<?php echo $value->id ?>-miltone"  style="z-index: -99;"></div>
                
                <?php } }else{ ?>
                <div style="height: 20px;"></div>
                <div  id="miltone"  style="z-index: -99;"></div>
                <?php } ?>
</div>