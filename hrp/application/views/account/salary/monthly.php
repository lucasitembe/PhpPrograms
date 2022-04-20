<h2 style="padding:0px; margin: 0px;">REPORT</h2><hr/>
<div class="formdata">
    <div class="formdata-title">
Monthly Report
    </div>
    <div class="formdata-content">
        
        <?php echo form_open('account/monthreport/'); ?>
        
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
                    <td>Month<span>*</span></td>
                    <td>
                        <select name="month">
                            <option value=""> --Select--</option>
                            <?php 
                            $month=month_generator();
                                        foreach ($month as $key => $value) { 
                                            $sel = set_value('month');
                                            ?>
                            
                            <option <?php echo ($sel ==$key ? 'selected="selected"':''); ?> value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                        <?php }
                            ?>
                        </select>
                    <?php echo form_error('month'); ?>
                    </td>
                </tr>
                
                <tr>
                    <td>Year<span>*</span></td>
                    <td>
                        <select name="year">
                            <option value=""> --Select--</option>
                            <?php 
                            
                                        foreach ($yearlist as $key => $value) { 
                                            $sel = set_value('year');
                                            ?>
                            
                            <option <?php echo ($sel ==$value->Name ? 'selected="selected"':''); ?> value="<?php echo $value->Name; ?>"><?php echo $value->Name; ?></option>
                                        <?php }
                            ?>
                        </select>
                    <?php echo form_error('year'); ?>
                    </td>
                </tr>
                
                
                
                <tr class="submit">
                    <td colspan="2"><div>
                            <input type="submit" value="Generate"/>
                             <input type="button" value="[ BACK ]" onclick="history.go(-1)"/>
                        </div></td>
                </tr>
        </table>
        <?php echo form_close() ?>
    </div>
</div>