<h2 style="padding:0px; margin: 0px;">KPIs Status</h2><hr/>


<div class="formdata">
    <div class="formdata-title">
        <?php $edit='';
        if(isset($id)){$edit=$id; echo 'Edit KPIs Status '; }else{ echo 'Add New KPIs Status';} ?>
    </div>
    <div class="formdata-content">
        
        <?php echo form_open('hr/kpistatus/'.$edit); ?>
        
        <table>
             <?php 
 
            if($this->session->flashdata('message') !=''){
            	?>
            	<tr>
            	<td colspan="2">
            	<div class="message">
            	<?php echo $this->session->flashdata('message'); ?>
            	</div>
            	</td>
            	</tr>
            	<?php
            }else if(isset($error_in)){
            	?>
            	<tr>
            	<td colspan="2" >
            	<div class="message">
            	<?php echo $error_in; ?>
            	</div>
            	</td>
            	</tr>
            	
            	<?php 
            }
            ?>
                <tr>
                    <td>KPI Status Name<span>*</span></td>
                    <td><input type="text" name="name" value="<?php echo (isset ($kpistatusdata) ? $kpistatusdata[0]->name:set_value('name')) ;?>"/>
                    <?php echo form_error('name'); ?>
                    </td>
                </tr>
                <tr>
                    <td>KPI Point <span>*</span></td>
                    <td><input type="text" name="point" value="<?php echo (isset ($kpistatusdata) ? $kpistatusdata[0]->Point:set_value('point')) ;?>"/>
                    <?php echo form_error('point'); ?>
                    </td>
                </tr>
                <tr>
                    <td>Status Color <span>*</span></td>
                    <td><input type="text" name="color" value="<?php echo (isset ($kpistatusdata) ? $kpistatusdata[0]->Color:set_value('color')) ;?>"/>
                    <?php echo form_error('color'); ?>
                    </td>
                </tr>
              
                
                <tr class="submit">
                    <td colspan="2"><div>
                            <input type="submit" value="Save"/>
                             <input type="button" value="[ BACK ]" onclick="history.go(-1)"/>
                        </div></td>
                </tr>
        </table>
        <?php echo form_close() ?>
    </div>
</div>

<div style="height: 10px;">&nbsp;</div>


<div class="table_list" >
    <table class="view_data" cellspacing="0" cellpadding="0">
        <th style="width:50px;">S/No</th><th>Name</th><th>Point</th> <th>Color</th><th style="width:100px;">Action</th>
</tr>

<?php 
$i=1;
if(count($kpistatuslist) > 0){
 foreach ($kpistatuslist as $key => $value){ 
?>
<tr <?php echo ($i%2 == 0 ? 'class="even_tr"':''); ?> >
    <td align="center"> <?php echo $i++; ?></td>
    <td><?php echo $value->name; ?></td>
    <td><?php echo $value->Point; ?></td>
    <td><?php echo $value->Color; ?></td>
    <td><?php echo anchor('hr/kpistatus/'.$value->id,'Edit','style="color:blue;"'); ?></td>
 </tr>   
<?php
}
}else{

 echo '<tr ><td colspan="4" align="center">No data found</td></tr>';
}
?>
 </table>
 
</div>
