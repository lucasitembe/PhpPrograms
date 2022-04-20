<?php if (!empty($bgt1)) { ?>
    <div class="panel panel-default" style="min-height:335px">
        <!-- Default panel contents -->
        <div class="panel-heading" style="font-family:Times new roman">
            <center>
                <h4>Budget for Department of <?php echo $bgt['0']['dept_name']; ?> </h4>
                <p style="font-size:15px">Year of budget <?php echo $bgt['0']['year_of_bgt'] ?>.</p>
            </center></div>
        <div class="panel-body" style="overflow-y:auto;height:350px">
            <table style="background-color:white;font-family:Times new roman;" class="table  table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>Objective</th>
                        <th>Intervention</th>
                        <th>Activity</th>
    <!--                    <th>Source of fund</th>-->
                        <th>GFS code</th>			  
                        <th>Inputs</th>
                        <th>Unit</th>
                        <th>Unit cost</th>
                        <th>Nbr</th>
                        <th>Total</th>
                        <th>Total activity cost</th>
                        <th>When</th>
                        <th>By who</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($bgt1 as $view1) {
                        ?>
                        <?php
                        $sum = 0;
                        foreach ($bgt as $view) {
                            if ($view1['activity_ref'] == $view['activity_ref']) {
                                ?>
                                <tr><td><?php echo $view['objective']; ?></td>
                                    <td><?php echo $view['intervention']; ?></td>
                                    <td><?php echo $view['activity_name']; ?></td>
                <!--                                    <td><?php // echo $view['fund_code'];  ?></td>-->
                                    <td><?php echo $view['b_grf_code']; ?></td>
                                    <td><?php echo $view['grf_desc']; ?></td>
                                    <td><?php echo $view['unit']; ?></td>
                                    <td><?php echo number_format($view['unit_cost'], 2); ?></td>
                                    <td><?php echo $view['nbr']; ?></td>
                                    <td><?php echo number_format($view['bgt_amount'], 2); ?></td>
                                    <td> </td>
                                    <td><?php echo $view['when']; ?></td>
                                    <td><?php echo $view['by_who']; ?></td></tr>
                                <?php $sum += str_replace(",", "", $view['bgt_amount']); ?>
                            <?php } ?>
                        <?php } ?>
                        <tr><td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td><b><?php echo number_format($sum, 2); ?></b></td>
                            <td></td>
                            <td></td>
                        </tr>
                    <?php }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php
} else {
    echo "<center style='color:red'><h4>No budget found</h4></center>";
} 