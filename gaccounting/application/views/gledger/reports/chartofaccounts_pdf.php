
                <?php
                if (count($chartofaccounts) > 0) {
                    foreach ($chartofaccounts as $ch=> $sec_arra) {
                        foreach ($sec_arra as $section => $accounts) {
                             $sn=0;
                        ?>
                        <div class="table-responsive"> 
                            <table class="table table-hover"> 
                                <thead> 
                                    <tr>
                                        <th colspan="5"><h3><?=$section?></h3></th>
                                    </tr>
                                    <tr> 
                                        <th>SN</th> 
                                        <th>Account Code</th>
                                        <th>Account Title</th>  
                                        <th>Group Name</th>
                                        <th>Description</th>
                                    </tr> 
                                </thead> 
                                <tbody class="tbody-backc-color"> 
                                    <?php
                                    foreach ($accounts as $ac) {
                                        ?>
                                        <tr>
                                            <td><?= ++$sn ?></td>
                                            <td><?php if ($ac->sec_id==1) {
                                                echo 'AS0'.$sn;
                                            }else if($ac->sec_id==2){

                                                echo 'EX0'.$sn;
                                            }else if($ac->sec_id==3){

                                                echo 'LI0'.$sn;
                                            }else if($ac->sec_id==4){

                                                echo 'EQ0'.$sn;
                                            }else{

                                                echo 'RE0'.$sn;
                                            }

                                             

                                            ?></td>
                                            <td><?= $ac->acc_name ?></td>
                                             <td><?= $ac->group_name ?></td>
                                              <td><?= $ac->description ?></td>
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