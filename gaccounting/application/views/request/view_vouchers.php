<div class="row">
    <div class="col-xs-1"></div>
    <div class="col-xs-10">
        <div class="row" style="background-color: white;border:1px solid black">
            <div  class="col-xs-2"></div>
            <div style="min-height: 40px" class="col-xs-8"><h2>Payment voucher</h2></div>
            <div  class="col-xs-2"></div>
        </div>
        <div class="row" style="background-color: white;border:1px solid black">
            <div style="border-right:1px solid black;min-height: 40px" class="col-xs-6"><p>Amount: <?php echo number_format($view['0']['amount']); ?></p></div>
            <div style="min-height: 40px" class="col-xs-6"><p>Date:  <?php echo $view['0']['v_date_posted']; ?></p></div>
        </div>
        <div class="row" style="background-color: white;border:1px solid black;min-height: 40px">
            <div style="border-right:1px solid black;min-height: 40px" class="col-xs-6"><p>Cash: <?php // echo $view['0']['amount'];  ?></p></div>
            <div style="min-height: 40px" class="col-xs-6"><p>Check #: <?php echo $view['0']['check_no']; ?></p></div>
        </div>
        <div class="row" style="background-color: white;border:1px solid black">
            <div style="min-height: 40px" class="col-xs-12"><p>To: Gabriel Patrick</p></div>
        </div>
        <div class="row" style="background-color: white;border:1px solid black">
            <div style="min-height: 40px" class="col-xs-12"><p>Being:  <?php echo $view['0']['activity_name']; ?> </p></div>
        </div>
        <div class="row" style="background-color: white;border:1px solid black">
            <div style="border-right:1px solid black;min-height: 40px" class="col-xs-4"><p>Approved by: </p></div>
            <div style="border-right:1px solid black;min-height: 40px" class="col-xs-4"><p>Paid by: </p></div>
            <div style="min-height: 40px" class="col-xs-4"><p>Signature: </p></div>
        </div>
    </div>
    <div class="col-xs-1"></div>
</div>

<div class="row">
   			  		
    <div class="col-xs-12"> 
        <form class="form-group" method="post" action="<?php // echo base_url('request/approve'); ?>" >
            <input type="hidden" name="needed_amount" value="<?php //echo $view[0]['amount']; ?>"/>
            <input type="hidden" name="rqst_id" value="<?php //echo $view[0]['request_id']; ?>"/>
            <input type="hidden" name="activity" value="<?php //echo $view[0]['description']; ?>"/>
            <input type="hidden" name="gfs_code" value="<?php //echo $view[0]['ref_gfs_code']; ?>"/>
            <input type="hidden" name="dept" value="<?php //echo $view[0]['cost_centre']; ?>"/>
            <input type="hidden" name="src_fund" value="<?php //echo $view[0]['source_of_fund_id']; ?>"/>
            <input type="hidden" name="yob" value="<?php //echo $view[0]['rqst_bgt_year']; ?>"/>               
            <input type="hidden" name="exp_bgt_id" value="<?php //echo $data1[0]['budget_id']; ?>"/>
<div class="row" style="margin-top: 15px">
        <div class="col-xs-6"></div>
        <div align="right" class="col-xs-5"> 
            <p><a href="#" class="btn btn-default" role="button">Decline</a> <button class="btn btn-primary" role="button">Authorize</button></p>
        </div>
        <div class="col-xs-1"></div>
    </div>

        </form>
  
    </div>
</div>



<!--                        <div class="col-xs-2"></div>
                        <div class="col-xs-8">
                            <table style="background-color:white" class="table  table-bordred table-triped table-hover">
                                <tbody>
                                    <tr>
                                      <td>Amount: 500,000</td>
                                      
                                      <td></td>
                                      <td>Date: 2016-10-17</td>
                                    </tr>  
                                    <tr>
                                      <td>Cash: 500,000</td>
                                      
                                      <td></td>
                                       <td>Check #: 887768</td>
                                    </tr>
                                    <tr>
                                      <td>To:</td>
                                      <td></td>
                                      <td></td>
                                    </tr>
                                    <tr>
                                      <td>Being:</td>
                                      <td></td>
                                      <td></td>
                                    </tr>
                                    <tr>
                                      <td>Approved by:</td> 
                                      <td>Paid by:</td> 
                                      <td>Signature:</td> 
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-xs-2"></div>-->

