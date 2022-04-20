<h2 style="padding:0px; margin: 0px;">Manage KPIs Category</h2><hr/>


<div class="formdata" style="width: 700px;">
    <div class="formdata-title">
        <?php $edit='';
        if(isset($id)){$edit=$id;
        echo 'Assign/ Edit Indicators for the Selected Category';
        }else{ echo 'Select Category to  assign Indictors';
        } ?>
    </div>
    <div class="formdata-content">

            <?php echo form_open('hr/managekpi/'.$edit,'style="width:100%;"'); ?>

        <table style="width: 500px;">
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
                <td>KPI Category<span>*</span></td>
                <td>
                    <select name="category">
                        <option value=""></option>
                        <?php
                        $sel = (isset ($kpikeydata) ? $kpikeydata[0]->id : set_value('category') );
                        foreach ($kpicategory as $key => $value) { ?>
                        <option <?php echo (($sel == $value->id) ? 'selected="selected"' :''); ?> value="<?php echo $value->id; ?>"><?php echo $value->name; ?></option>

                        <?php } ?>

                        </select>
                        <?php echo form_error('category');
                        ?>
                </td>
                <td class="submit">
                    <div>
                        
                        <input type="submit" value="Load Indicators"/>
                      
                    </div></td>
            </tr>
        </table>
<?php echo form_close() ?>
    </div>
</div>

<div style="height: 10px;">&nbsp;</div>
<?php if(isset ($id)) { ?>
<h2>Assign Indicators to : <?php echo $kpikeydata[0]->name; ?></h2><hr/>
<?php echo form_open('hr/managekpi/'.$edit) ?>
<input type="hidden" value="<?php echo $id; ?>" name="category"/>
<?php
if(isset ($error_found)){ ?>
<div class="message" style="width: 890px;"><?php echo $error_found; ?></div>
<?php } ?>
<table class="view_data"  cellspacing="0" cellpadding="0" style="width: 900px;">
            <tr>
                <th>Name</th><th style="width: 100px;">Select</th>
            </tr>
            <?php
            $i=1;
             foreach ($kpi_list as $key => $value) { ?>
            <tr <?php echo ($i%2 == 0 ? 'class="even_tr"':''); $i++;?>>
                
                <td><?php echo $value->name ?></td>
                <td><input type="checkbox" name="kpi_submited[]" <?php echo (in_array($value->id, $assigned_kpi) ? 'checked="checked"' : ''); ?> value="<?php echo $value->id; ?>"/></td>
            </tr>

             <?php } ?>

                

</table>
<p style="width: 800px; text-align: right;"> <input style="background-color: #996;padding: 5px 10px 5px 10px;border: 1px solid #CCC;border-bottom: 2px solid black" type="submit" value="Record selected KPIs" class="submit" name="save_record"/></p>

<?php
echo form_close();

} ?>

