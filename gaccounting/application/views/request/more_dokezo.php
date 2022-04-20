<table style="background-color:white;font-family:Times new roman" class="table  table-bordered table-striped table-hover">
    <tbody>
        <tr><td align="right"><label>Requested amount</label></td><td><?php echo $data[0]['amount']; ?></td></tr>
<!--        <tr><td align="right"><label>Source of income</label></td><td ><?php // echo $data[0]['source_name'];  ?></td></tr>-->
        <tr><td align="right"><label>Cost centre<label></td><td><?php echo $data[0]['dept_name']; ?></td></tr>                     
                        <tr><td align="right"><label>GFS code<label></td><td><?php echo $data[0]['ref_gfs_code']; ?></td></tr>                     
                                        <tr><td align="right"><label>Activity</label></td><td><?php echo $data[0]['activity_name']; ?></td></tr>
                                        <tr><td align="right"><label>Year of budget</label></td><td><?php echo $data[0]['rqst_bgt_year']; ?></td></tr>

                                        <tr><td align="right"><label>Allocated amount</label></td><td><?php echo number_format($data1[0]['bgt_amount'], 2); ?></td></tr>
                                        <tr><td align="right"><label>Disbursed amount (To date)</label></td><td><?php
                                                $ans = $data1[0]['bgt_amount'] - $data1[0]['bgt_amount_left'];
                                                echo number_format($ans, 2);
                                                ?></td></tr>
                                        <tr><td align="right"><label>Budget balance</label></td><td><?php echo number_format($data1[0]['bgt_amount_left'], 2); ?></td></tr>
                                        </tbody>
                                        </table>
                                        <div class="row">
                                            <div class="col-xs-7"></div>			  		
                                            <div class="col-xs-3"> 
                                                <?php
                                                $attributes = array('class' => 'form-group');
                                                echo form_open('request/approve', $attributes);
                                                ?> 
                                                <input type="hidden" name="needed_amount" value="<?php echo $data[0]['amount']; ?>"/>
                                                <input type="hidden" name="rqst_id" value="<?php echo $data[0]['request_id']; ?>"/>
                                                <input type="hidden" name="activity" value="<?php echo $data[0]['description']; ?>"/>
                                                <input type="hidden" name="gfs_code" value="<?php echo $data[0]['ref_gfs_code']; ?>"/>
                                                <input type="hidden" name="dept" value="<?php echo $data[0]['cost_centre']; ?>"/>
<!--                                            <input type="hidden" name="src_fund" value="<?php // echo $data[0]['source_of_fund_id'];  ?>"/>-->
                                                <input type="hidden" name="yob" value="<?php echo $data[0]['rqst_bgt_year']; ?>"/>               
                                                <input type="hidden" name="exp_bgt_id" value="<?php echo $data1[0]['budget_id']; ?>"/>
                                                <div class='row'>

                                                    <div  class='col-xs-6'>
                                                        <a  href="<?php echo base_url('request/view_dokezo'); ?>" class="btn btn-default" role="button">Decline</a>
                                                    </div>  
                                                    <div class='col-xs-6'>
                                                        <input type="submit" value="Approve" class="btn btn-primary" />
                                                    </div>  
                                                </div>
                                                </form>
                                            </div>
                                        </div>