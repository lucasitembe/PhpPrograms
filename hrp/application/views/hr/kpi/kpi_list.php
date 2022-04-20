<h2 style="padding:0px; margin: 0px;">Key Performance Indicators</h2><hr/>
<div class="formdata" style=" width: 1000px;">
    <div class="formdata-title">
    <?php echo (isset ($id) ? 'Edit Indicator' : 'Add New Indicator'); ?>  
    </div>
    <div class="formdata-content" style="width: 97%;">
        
        <?php 
        $edit=(isset ($id) ? $id:'');
        
        echo form_open('hr/kpilist/'.$edit,'style="width:100%;"'); ?>
        
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
                <td>Indicator Name<span>*</span></td>
                <td><input type="text" name="name" value="<?php echo (isset ($kpikeydata) ? $kpikeydata[0]->name:set_value('name')); ?>"/>
<?php echo form_error('name'); ?>
                </td>
            </tr>


            <tr class="submit">
                <td colspan="2"><div>
                        <input type="submit" value="Save"/>
                        <input type="button" value="[ BACK ]" onclick="history.go(-1)"/>
                    </div></td>
            </tr>
        </table>
        <?php echo form_close(); ?>
    </div>
</div>

<div style="height: 10px;">&nbsp;</div>
<h3>Key Performance Indicators</h3> <hr/>
  <table class="view_data"  cellspacing="0" cellpadding="0" >
            <tr>
                <th style="width: 70px;">S/N</th><th>Name</th><th style="width: 100px;">Action</th>
            </tr>
            
<?php
if(count($kpi_list) > 0){
    $i=1;
    foreach ($kpi_list as $key => $value) { ?>
            <tr <?php echo ($i%2 == 0 ? 'class="even_tr"':''); ?> >
                <td align="right"><?php echo $i++; ?> &nbsp; </td>
                <td><?php echo $value->name ?></td>
                <td><?php echo anchor('hr/kpilist/'.$value->id,'Edit','style="color:blue;"'); ?></td>
            </tr>
    <?php } }else{ ?>
            <tr><td colspan="3"><b style="font-style: italic;"> No data found !!</b></td></tr>
    <?php } ?>

  </table>



