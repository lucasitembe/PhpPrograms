<div class="row">
    <div class="col-lg-12">
        <ol class="breadcrumb">
            <li><i class="fa fa-home"></i><a href="<?= base_url('chop') ?>"> Home</a></li>
            <li><i class="fa fa-laptop"></i> Dashboard</li>						  	
        </ol>
    </div>
</div>
<div class="row" >
    <div class="col-xs-2"></div>
    <div class="col-xs-8">
        <div class="panel panel-primary ">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-bar-chart-o"></i> Manage Permissions  </h3>
            </div>
            <div class="panel-body">
                        <?php
                        $attributes = array('class' => 'form-group');
                        echo form_open('account/role_permission', $attributes);
                        ?>
                        <center style="font-family:Times new roman"><h2>Manage Permissions </h2></center>
                        <div style="overflow-y:auto;overflow-x:hidden;height:350px" >
                            <div class="row" style="margin-top:0px">		   		
                                <div class="col-xs-3" align="right"><label>Role name:</label></div>
                                <div class="col-xs-7">
                                    <div class="form-group ">
                                        <select id="role_name" required type="text" name="role_name"  class="form-control" placeholder="Role name" aria-describedby="basic-addon1" >
                                            <option value="" disabled="disabled" selected="selected">--Select role--</option>
                                            <?php foreach ($roles as $view) { ?>
                                                <option  value="<?php echo $view['role_id']; ?>"><?php echo $view['role_name']; ?></option>   
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xs-2"></div>
                            </div>
                            <div align="center" style="display:none" id="progressStatus"><img src="<?= base_url('assets/images/ajax-loader_1.gif'); ?>" width="" style="border-color:white "></div>
                            <div class="row" style="margin-top:0px">		   		
                                <div class="col-xs-3"><label></label></div>
                                <div id="access" class="col-xs-7">

                                    </table>
                                </div>
                                <div class="col-xs-2"></div>
                            </div>
                            <div style="display:none" id="assign_role" class="row">
                                <div class="col-xs-5"></div>
                                <div class="col-xs-5"> 
                                    <p><a href="<?php echo base_url('chop/index'); ?>" class="btn btn-default" role="button">Cancel</a> <button class="btn btn-primary" role="button">Submit</button></p>
                                </div>
                            </div>
                        </div>
                        </form>    
            </div>
        </div>
        <div class="col-xs-2"></div>
    </div>
</div>
