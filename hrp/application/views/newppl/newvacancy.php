<style type="text/css">
    .new_kaz{
        float: left;
        width: 500px;
        border: 1px solid #CCCCCC;
        margin: 10px;
    }
</style>
<div style="width: 100%; ">
<?php

function arrange_date($date){
    $ex = explode('-', $date);
    return $ex[2].'-'.$ex[1].'-'.$ex[0];
}
$p=0;
        foreach ($list as $key => $value) {
            if($value->to_date >= date('Y-m-d')){
                $p++;
                ?>
                
    <div class="new_kaz">
        <div style="margin: 5px;">
            <p>
                <b style="display: block;"> Job Title   : <?php echo $value->Title; ?></b>
                <b style="display: block;"> Posted Date : <?php echo arrange_date($value->from_date); ?></b>
                <b style="display: block;"> Application Deadline: <?php echo arrange_date($value->to_date); ?></b>
                
                
            </p>
            <p><b>Job Description :</b> <br/> <?php echo $value->Description; ?>
            
                <?php
                if($value->Attach != ''){ ?>
                <b style="display: block; padding-top: 5px;"> <?php echo anchor(base_url().'uploads/vacancy/'.$value->Attach,'Download Document','target="_blank" download') ?></b>
                <?php }
                ?>
            </p>
            <p style="text-align: right;">
                <b><?php echo anchor('hrnew/applynew/'.$value->id,'Apply this Job',''); ?></b>
            </p>
        </div>
    </div> 
                
            <?php }
        }
       
        if($p == 0){
            echo '<h3>No Job Vacancy is available now !!</h3>';
        }
        ?>
    <div style="clear: both;"></div>
</div>