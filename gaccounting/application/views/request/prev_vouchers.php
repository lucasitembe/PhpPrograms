<div class="row">
    <div class="col-lg-12">
        <ol class="breadcrumb">
            <li><i class="fa fa-home"></i><a href="<?=base_url('chop')?>"> Home</a></li>
            <li><i class="fa fa-laptop"></i> Dashboard</li>						  	
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-bar-chart-o"></i> List of Vouchers to be approved! </h3>
            </div>
            <div class="panel-body">
                <div class="table-responsive"> 
                    <table class="table table-bordered  table-striped table-hover"> 
                        <thead> 
                            <tr>
                                <th>voucher no</th>   
                                <th>Check no</th>
                                <th>Cost cetre</th>
                                <th>Date</th>			  
                                <th>View more</th>

                            </tr>
                        </thead>
                        <tbody class="tbody-backc-color">
                            <?php foreach ($show as $view) { ?>       
                                <tr>
                                    <td><?php echo $view['voucher_id']; ?></td>
                                    <td><?php echo $view['check_no']; ?></td>
                                    <td><?php echo $view['dept_name']; ?></td>
                                    <td><?php echo $view['v_date_posted']; ?></td>
                                    <td><a  class="viewmore" href="<?php echo base_url('request/view_vouchers') . '/' . $view['voucher_id']; ?>">View more..</a>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                    <div align="right" >         
                        <?php //echo $pagination ?>            
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class='col-xs-12'>
        <div  class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Payment voucher</h4>
                    </div>

                    <div class="modal-body">
                        <div class="x_content" id="currform_modal">
                            <br />
                            <div id="vochaframe">

                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


