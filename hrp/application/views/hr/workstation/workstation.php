<h2 style="padding:0px; margin: 0px;">Work Station</h2><hr/>
<div>
<div style="float: left; display: inline;">
    <a href="<?php echo site_url('hr/addworkstation');?>" class="btn btn-default">Add new work station </a>
    </div>

    <div class="pull-right4" style="float: right; display: inline;">
    <?php echo form_open('hr/workstation'); ?>
    Search : <input name="key" style="max-width:500px; min-height:32px;" class="form-controtl"/> 
    <input type="submit" class="btn btn-default" value="Search"/>
    <?php echo form_close(); ?>

    
</div>
<div style="clear: both;"></div>
</div>

<div class="table_list col-md-10 col-md-offset-1"  style="padding-top: 10px;">
    <table class="table table-bordered view_data" cellspacing="0" cellpadding="0">
        <th style="width:50px;">S/No</th><th>Name</th><th>Description</th><th style="width:100px;">No of Employee</th><th style="width:100px;">Action</th>
</tr>

<?php 
$i=1;
if(count($workstation) > 0){
 foreach ($workstation as $key => $value){ 
?>
<tr <?php echo ($i%2 == 0 ? 'class="even_tr"':''); ?> >
    <td align="center"> <?php echo $i++; ?></td>
    <td><?php echo $value->Name; ?></td>
    <td><?php echo $value->Description; ?></td>
    <td align="center">0</td>
    <td>
clinicalnotes</td>
 </tr>   
<?php
}
}else{

 echo '<tr ><td colspan="5" align="center">No data found<td></tr>';
}
?>
 </table>
    <?php
    echo $links;
    ?>
</div>
