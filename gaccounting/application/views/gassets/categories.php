<div class="row">
    <div class="col-lg-12">
        <ol class="breadcrumb">
            <li><i class="fa fa-home"></i><a href="<?= base_url('gassets') ?>">Assets Management</a></li>
          
            <li><i class="fa fa-object-group"></i> Categories</li>						  	
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
                <h3 class="panel-title"><i class="fa fa-bar-chart-o"></i> Asset Categories </h3>
            </div>
            <div class="panel-body">
                <div class="table-responsive"> 
                    <table class="table table-bordered  table-striped table-hover"> 
                        <thead> 
                            <tr> 
                                <th>#</th> 
                                <th>Category name</th> 
                                <th>Last update</th> 
                                <th>&nbsp;</th> 
                            </tr> </thead> 
                        <tbody> 
                            <?php
                            
                            
                            
                            $i = 1+$page;
                            if($categoryList){
                            foreach ($categoryList as $dt) {
                                echo "<tr>";
                                echo "<td>" . $i++ . "</td>";
                                echo "<td>" . $dt->cat_name . "</td>";
                                echo "<td>" . $dt->modified_date . "</td>";
                                echo "<td width='18%'>";
                                echo  "<div class='btn-group'>
                                      <a class='btn btn-primary btn-xs editCategoryBtn' href='".base_url('gassets/categoryform/'.$dt->cat_id)."'><i class='fa fa-pencil-square-o'></i> Edit</a> ";
                                if($dt->enable){
                                   echo "<a class='btn btn-info btn-xs' href='".base_url('gassets/disableAssetsCategory/'.$dt->cat_id)."'><i class='fa fa-check'></i> Enabled</a>
                                      <div>";
                                } else {
                                   echo "<a class='btn btn-danger btn-xs' href='".base_url('gassets/enableAssetsCategory/'.$dt->cat_id)."'><i class='fa fa-close'></i> Disabled</a>
                                      <div>";
                                }
                                     
                                 echo "</td>";
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
                <div class="modal fade  model-reload" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myModalLabel">Add new category</h4>
                            </div>
                            <?php
                            $attributes = array('autocomplete' => 'off', 'class' => 'form-horizontal form-label-left', 'data-toggle' => 'validator', 'role' => 'form', 'id' => 'assetcategoryform');
                            echo form_open('gassets/categories', $attributes);
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
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="currncy-name">Category Name <span class="required">*</span>
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <input  type="text" id="currncy-name" name="category_name" maxlength="45" required class="form-control col-md-7 col-xs-12">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="ledger_id">Asset Ledger<span class="required">*</span>
                                        </label>
                                        <div class="col-md-8 col-sm-6 col-xs-12">
                                            <select id="ledger_id" name="ledger_id"  required class="form-control chosen-select col-md-12 col-xs-12">
                                            <option value="">Please select Ledger</option>
                                            <?php if(count($assetsLedgers)>0){
                                                foreach ($assetsLedgers as $ledger) {
                                                    echo '<option value="'.$ledger->ledger_id.'">'.$ledger->ledger_name.'( '.$ledger->acc_name.' )</option>';
                                                }
                                                } ?>
                                                
                                            </select>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                <button type="submit" name="submitAssetCategory" class="btn btn-primary" value="submitAssetCategory">Save changes</button>
                            </div>

                            </form>
                        </div>
                    </div>
                </div>           

            </div>
        </div>
    </div>



     <!-- update category modal -->
                 <!-- Modal -->
               <div class="modal fade  model-reload" id="editCategoryModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myModalLabel">Update Category</h4>
                            </div>
                             <?php
                            $attributes = array('autocomplete' => 'off', 'class' => 'form-horizontal form-label-left', 'data-toggle' => 'validator', 'role' => 'form', 'id' => 'updateCategoryform');
                            echo form_open('gassets/updateAssetCategory', $attributes);
                            ?>
                             <div class="modal-body">
                                <div class="x_content">
                                    <br />

                                    <div class="row">
                                        <div class="col-md-12 col-sm-12">
                                            <div class="alert alert-success" role="alert" data-dismiss="alert" id="alertSuccess1">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                            <strong>Success!</strong> <span id="succmsg1"></span>
                                        </div>
                                        <div class="alert alert-danger" role="alert" data-dismiss="alert" id="alertError1">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                            <strong>Error!</strong> <span id="errmsg1"></span>
                                        </div>
                                        </div>
                                    </div>
                            <div id="modalBody">
                                
                            </div>
                             </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                <button type="submit" name="" class="btn btn-primary" value="">Save changes</button>
                            </div>

                            </form>
                        </div>
                    </div>
                </div>
