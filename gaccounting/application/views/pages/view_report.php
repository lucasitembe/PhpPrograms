<div class="row">
    <div class="col-lg-12">
        <div id='table-options' class="pull-right">
            <a href="#" class="btn btn-primary"><i class="fa fa-print"></i> Print</a> 
        </div>
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-bar-chart-o"></i> Expenditure report </h3>
            </div>
            <div class="panel-body" >
                <div class="table-responsive"> 
                    <table class="table  table-bordered table-striped table-hover"> 
                        <thead>
                            <tr>
                                <th>GFS code</th>
                                <th>Description</th>
                                <th>Department</th>
<!--                                <th>Source of fund</th>-->
                                <th>Expenditures</th>
                                <th>Budget</th>
                                <th>Budget balance</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php
                            if (!empty($report)) {
                                $i = 0;
                                foreach ($report as $view) {
                                    ?>
                                    <tr><td><?php echo $view['code']; ?></td>
                                        <td><?php echo $view['grf_desc']; ?></td>
                                        <td><?php echo $view['dept_name']; ?></td>
<!--                                        <td><?php // echo $view['source_name'] . "  ( " . $view['fund_code'] . ")"; ?></td>-->
                                        <td><?php echo $view['exp_amount']; ?></td>
                                        <td><?php echo $view['bgt_amount']; ?></td>
                                        <td><?php echo $view['exp_bgt_balance']; ?></td>
                                        <?php
                                        $i++;
                                    }
                                }
                                ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
