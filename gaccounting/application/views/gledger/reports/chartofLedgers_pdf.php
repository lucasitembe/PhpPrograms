
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
                            <th colspan="5"><h3><?= $section ?></h3></th>
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
                                <td><?= $led->dis ?></td>
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