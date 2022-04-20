<div class="row">
    <div class="col-lg-12">
        <ol class="breadcrumb">
            <li><i class="fa fa-home"></i><a href="<?= site_url('Home') ?>"> Dashboard</a></li>
            <li><i class="fa fa-laptop"></i><a href="<?= site_url('gledger') ?>"> General Ledger</a></li>                 
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div id='table-options' class="pull-right">
            <a href="<?= base_url('gledger/chartofledgers') ?>?report" id="ajaxUpdateContainerPdf" class="btn btn-primary" target="_blank"><i class="fa fa-print"></i> Pdf</a> 
        </div>
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-bar-chart-o"></i> Charts Of Ledgers Report </h3>
            </div>
            <div class="panel-body" id="ajaxUpdateContainer">
                <?php
                if (count($chartofledgers) > 0) {
                    foreach ($chartofledgers as $ch => $sec_arra) {
                        foreach ($sec_arra as $section => $ledgers) {
                            $sn = 0;
                            ?>
                            <div class="table-responsive"> 
                                <table class="table table-hover"> 
                                    <thead> 
                                        <tr>
                                            <th colspan="3"><h3><?= $section ?></h3></th>
                                        </tr>
                                        <tr> 
                                            <th>SN</th> 
                                            <th>Ledger Name</th> 
                                            <th>Account Title</th>  
                                            <th>Group Name</th>
                                            <th>Description</th>
                                        </tr> 
                                    </thead> 
                                    <tbody class="tbody-backc-color"> 
                                        <?php
                                        foreach ($ledgers as $led) {
                                            ?>
                                            <tr>
                                                <td><?= ++$sn ?></td>
                                                <td><?= $led->ledger_name ?></td>
                                                <td><?= $led->acc_name ?></td>
                                                <td><?= $led->group_name ?></td>
                                                <td><?= $led->dis?></td>
                                            </tr> 
                                            <?php
                                        }
                                        ?>
                                    </tbody> 
                                </table> 
                            </div>

                            <?php
                        }
                    }
                    echo '<br/>';
                }
                ?>
            </div>
        </div>
    </div>
</div>