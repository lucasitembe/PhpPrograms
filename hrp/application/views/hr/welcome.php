<style type="text/css">
    .fl{
        float: left;
        border: 1px solid red;
        
    }
    
</style>

   <h2 style="padding:20px 0px 0px 0px;  margin: 0px;">Dashboard</h2><hr/>
   <div style="height: 40px;"></div>
<script type="text/javascript" src="<?php echo base_url() ?>assets/js/swfobject.js"></script>
        
         <script type="text/javascript">
            swfobject.embedSWF(
              "<?php echo base_url() ?>assets/swf/open-flash-chart.swf", "gender", "300", "300",
              "9.0.0", "expressInstall.swf",
              {"data-file":"<?php echo urlencode($graph['data_url_gender']); ?>"}
              );
            swfobject.embedSWF(
              "<?php echo base_url() ?>assets/swf/open-flash-chart.swf", "department", "300", "300",
              "9.0.0", "expressInstall.swf",
              {"data-file":"<?php echo urlencode($graph['data_url_department']); ?>"}
              );
            swfobject.embedSWF(
              "<?php echo base_url() ?>assets/swf/open-flash-chart.swf", "age", "300", "300",
              "9.0.0", "expressInstall.swf",
              {"data-file":"<?php echo urlencode($graph['data_url_age']); ?>"}
                 );
            swfobject.embedSWF(
              "<?php echo base_url() ?>assets/swf/open-flash-chart.swf", "education", "300", "300",
              "9.0.0", "expressInstall.swf",
              {"data-file":"<?php echo urlencode($graph['data_url_education']); ?>"}
              );
        </script>
        <div style="z-index: -100">
            <div class="fl" id="gender"></div>
            
            <div class="fl" id="department"></div>
            
            <div class="fl" id="age"></div>
            
            <div class="fl" id="education"></div>
            

</div>