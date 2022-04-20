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
         <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-bar-chart-o"></i> System Parameters </h3>
            </div>
            <div class="panel-body">
                <div class="table-responsive"> 
                    <?php
                    $attributes = array('autocomplete' => 'off', 'class' => 'form-horizontal form-label-left');
                    echo form_open('setting/systemparams', $attributes);
                    ?>
                    <table class="table table-bordered  table-striped table-hover"> 
                        <thead> 
                            <tr> 
                                <th>#</th> 
                                <th>Config name</th> 
                                <th>Config value</th> 
                            </tr> </thead> 
                        <tbody> 
                            <?php
                            $i = 1;
                            foreach ($paramsList as $dt) {
                                echo "<tr>";
                                echo "<td>" . $i++ . "</td>";

                                $cnfname = '';
                                $pieces = preg_split('/(?=[A-Z])/', $dt->config_name);

                                foreach ($pieces as $piec) {
                                    $cnfname .= ' ' . $piec;
                                }

                                echo "<td>" . $cnfname . "</td>";
                                
                                if($dt->config_name =='IncomeProjectionLevel'){
                                     echo "<td><select  name='" . $dt->config_name . "'>";
                                     echo "<option ".(($dt->config_value=='acc_group')?"selected":"")." value='acc_group'>Account Group</option>";
                                     echo "<option ".(($dt->config_value=='main_acc')?"selected":"")." value='main_acc'>Main Account</option>";
                                     echo "<option ".(($dt->config_value=='gl_acc')?"selected":"")." value='gl_acc'>General Ledger Account</option>";
                                     echo "</select></td>";
                                }elseif($dt->config_name =='AccountYearOpeningClosing'){
                                     echo "<td><select  name='" . $dt->config_name . "'>";
                                     echo "<option ".(($dt->config_value=='auto')?"selected":"")." value='auto'>Automatic</option>";
                                     echo "<option ".(($dt->config_value=='manual')?"selected":"")." value='manual'>Manually</option>";
                                     echo "</select></td>";
                                }else{
                                echo "<td><input type='text' name='" . $dt->config_name . "' value='" . $dt->config_value . "'></td>";
                                }
                                echo "</tr>";
                            }
                            ?>
                        </tbody> 
                    </table> 
                    <center><button type="submit" name="submit" class="btn btn-primary" >Save changes</button></center>
                    </form>
                </div>


            </div>
        </div>
    </div>
