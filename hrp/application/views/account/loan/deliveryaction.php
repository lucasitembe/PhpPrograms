<script type="text/javascript">
    function savedata(){
        con = confirm("Are you sure you want to save this delivery informations for this Loan ??");
        if(con){
            return true;
        }else{
            return false;
        }
    }
    
</script>

<?php



$user_info = $this->ion_auth->user()->row();
$user_id = $user_info->id;
$name = $user_info->first_name.' '.$user_info->last_name;


$acc1 = file_get_contents("http://localhost/Final_One/gaccounting/Api/ledger");
$acc2 = json_decode($acc1);
$credentials=array('id'=>$id);
$query = $this->db->get_where('loan',$credentials)->result_array();


$loan_amount =$query[0]['Loan_Amount'];
$Interest=$query[0]['Interest'];
$base_amount=$query[0]['Base_Amount'];
$source=$query[0]['Employee'];

?>

<h2 style="padding:0px; margin: 0px;">Set Delivery Information</h2><hr/>
<div class="formdata">
    <div class="formdata-title">
        Enter Delivery Information
    </div>
    <div class="formdata-content">
        
        <?php echo form_open('account/deliverloan/'.$id); ?>
        
        <table>
             <?php 
 
            if($this->session->flashdata('message') !=''){
            	?>
            	<tr>
            	<td colspan="2">
            	<div class="message">
            	<?php echo $this->session->flashdata('message'); ?>
            	</div>
                <div class="message">
                <?php echo $this->session->flashdata('message1'); ?>
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
                    <td>Start Deduct From <span>*</span></td>
                    <td>
                        <select name="month">
                            <option value=""> Month</option>
                            <?php
                            $month=  month_generator();
                            foreach ($month as $key => $value) {
                                $sel=  set_value('month');
                                ?>
                            <option <?php echo ($sel == $key ? 'selected="selected"':''); ?> value="<?php echo $key; ?>"><?php echo $value; ?></option>
                            <?php }
                            ?>
                        </select>
                        <select name="year">
                            <option value=""> Year</option>
                            <?php
                            $year=  $this->account->year();
                            foreach ($year as $key => $value) {
                                $sel=  set_value('year');
                                ?>
                            <option <?php echo ($sel == $value->Name ? 'selected="selected"':''); ?> value="<?php echo $value->Name; ?>"><?php echo $value->Name; ?></option>
                            <?php }
                            ?>
                        </select>
                    <?php echo form_error('month'); ?>
                    <?php echo form_error('year'); ?>
                    </td>
                </tr>
                  <tr>
                    <td>Ledger<span>*</span></td>
                    <td>
                        <select required="required" name='ledger' width=20% id='ledger_id'  >                              
                                    <option selected="selected" disabled="disabled" value="">--Select ledger--</option>
                                    <?php foreach ($acc2 as $acc) { ?>
                                        <option value="<?php echo $acc->ledger_id; ?>"><?php echo $acc->ledger_name; ?></option>
                                    <?php } ?>
                                </select>
                    <?php echo form_error('category'); ?>
                    </td>
                </tr>
                
                <tr class="submit">
                    <td colspan="2"><div>
                        <input type="hidden" name="userid" value="<?php echo $user_id;?>">
                        <input type="hidden" name="name" value="<?php echo $name;?>">
                        <input type="hidden" name="loan_amount" value="<?php echo $loan_amount;?>">
                        <input type="hidden" name="base_amount" value="<?php echo $base_amount;?>">
                          <input type="hidden" name="interest" value="<?php echo $Interest;?>">
                          <input type="hidden" name="source" value="<?php echo $source;?>">
                            <input type="submit" value="Save " onclick="return savedata()"/>
                             <input type="button" value="[ BACK ]" onclick="history.go(-1)"/>
                        </div></td>
                </tr>
        </table>
        <?php echo form_close() ?>
    </div>
</div>