<div class="row">
    <div class="col-lg-12">
        <ol class="breadcrumb">
            <li><i class="fa fa-home"></i><a href="index.html"> Home</a></li>
            <li><i class="fa fa-laptop"></i> Dashboard</li>						  	
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div id='table-options' class="pull-right">
            <button type="button" class="btn btn-primary"  data-toggle="modal" data-target="#myModal"><i class="fa fa-plus"></i> Add New</button>
            <a href="#" class="btn btn-primary"><i class="fa fa-print"></i> Print</a> 
        </div>
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-bar-chart-o"></i> Roles </h3>
            </div>
            <div class="panel-body">
                <div class="table-responsive"> 
                    <table class="table table-bordered  table-striped table-hover"> 
                        <thead> 
                            <tr> 
                                <th>#</th> 
                                <th>First name</th> 
                                <th>Last name</th> 
                                <th>Phone</th>
                                <th>Email</th>
                                <th>Gender</th>
                                <th>Role</th>
                                <th>Account status</th>
                                <th>&nbsp;</th> 
                            </tr> </thead> 
                        <tbody> 
                            <?php
                            $i = 1 + $page;

                            if (!empty($userList)) {
                                foreach ($userList as $dt) {
                                    echo "<tr>";
                                    echo "<td>" . $i++ . "</td>";
                                    echo "<td>" . $dt->fname . "</td>";
                                    echo "<td>" . $dt->lname . "</td>";
                                    echo "<td>" . $dt->phone . "</td>";
                                    echo "<td>" . $dt->email . "</td>";
                                    echo "<td>" . $dt->gender . "</td>";
                                    echo "<td>" . $dt->role_name . "</td>";
                                    echo "<td>" . $dt->account_status . "</td>";
                                    
                                    echo "<td>
                                    <div class='btn-group'>
                                      <a class='btn btn-primary' href='#'><i class='fa fa-pencil-square-o'></i> Edit</a>
                                      <a class='btn btn-danger' href='#'><i class='fa fa-close'></i> Delete</a>
                                      <div>
                                  </td>";
                                    echo "</tr>";
                                }
                            }
                            ?>
                        </tbody> 
                    </table> 

                    <center> <?php echo $links; ?></center>

                </div>

                <!--currency form model-->

                <!-- Modal -->
                <div class="modal fade model-reload" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myModalLabel">Add new role</h4>
                            </div>
                            <?php
                            $attributes = array('autocomplete' => 'off', 'class' => 'form-horizontal form-label-left', 'data-toggle' => 'validator', 'role' => 'form', 'id' => 'defaultform');
                            echo form_open('account/account', $attributes);
                            ?>

                            <div class="modal-body">
                                <div class="x_content" id="currform_modal">
                                    <br />

                                    <div class="alert alert-success" role="alert" data-dismiss="alert" id="alertSuccess">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <strong>Success!</strong> <span id="succmsg"></span>
                                    </div>
                                    <div class="alert alert-danger" role="alert" data-dismiss="alert" id="alertError">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <strong>Error!</strong> <span id="errmsg"></span>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="fname">First name <span class="required">*</span>
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input  type="text" id="fname" name="fname" maxlength="45" required class="form-control col-md-7 col-xs-12">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="lname">Last name <span class="required">*</span>
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input  type="text" id="lname" name="lname" maxlength="45" required class="form-control col-md-7 col-xs-12">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="phone">Phone <span class="required">*</span>
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input  type="text" id="phone" name="phone" maxlength="45" required class="form-control col-md-7 col-xs-12">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="email">Email <span class="required">*</span>
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input  type="email" id="email" name="email" data-error="Email address is invalid" required class="form-control col-md-7 col-xs-12">
                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12">Gender <span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div id="gender" class="btn-group" data-toggle="buttons">
                                                <label class="btn btn-default" data-toggle-class="btn-primary" data-toggle-passive-class="btn-default">
                                                    <input type="radio" name="gender"  required value="male"> &nbsp; Male &nbsp;
                                                </label>
                                                <label class="btn btn-default" data-toggle-class="btn-primary" data-toggle-passive-class="btn-default">
                                                    <input type="radio" name="gender" required value="female"> Female
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="acc_status">Account status  <span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <select class="form-control col-md-7 col-xs-12" required id="acc_status" name="acc_status">
                                                <option value=""></option>
                                                <option value="opened">opened</option>
                                                <option value="closed">Closed</option>
                                                <option value="suspended">Suspended</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- start @mfoy dn -->
                                    <div class="form-group">
                                        <label class="control-label col-md-9 col-sm-9 col-xs-9" for="">
                                            How do you want to set user credentials?
                                        </label>
                                        <label class="control-label col-md-6 col-sm-6 col-xs-6" for="rad1">
                                            <input  type="radio" id="rad1" checked name="select" class="" value="manual"> Add Manually
                                        </label>
                                        <label class="control-label col-md-4 col-sm-4 col-xs-4" for="rad2">
                                            <input  type="radio" id="rad2" name="select"  class="" value="fromehms"> From eHMS
                                        </label>
                                    </div>
                                    <!-- end @mfoy dn -->


                                    <div class="form-group">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="username">Username <span class="required">*</span>
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input  type="text" id="username" name="username" maxlength="45" required class="form-control col-md-7 col-xs-12">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="email">Password <span class="required">*</span>
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input  type="password" id="password" name="password" required class="form-control col-md-7 col-xs-12">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="role">Role  <span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <select class="form-control col-md-7 col-xs-12" required id="role" name="role">
                                                <option value=""></option>
                                                <?php
                                                if (!empty($roleList)) {
                                                    foreach ($roleList as $dt) {
                                                        echo '<option value="' . $dt->role_id . '">' . $dt->role_name . '</option>';
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="ln_solid"></div>
                                    <div class="form-group">
                                        <!--                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                                                  <button type="submit" class="btn btn-primary">Cancel</button>
                                                                  <button type="submit" class="btn btn-success">Submit</button>
                                                                </div>-->
                                    </div>

                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                <button type="submit" name="submitrole" class="btn btn-primary" value="submitrole">Save changes</button>
                            </div>
                            </form>
                        </div>
                    </div>
                </div>           

            </div>
        </div>
    </div>
