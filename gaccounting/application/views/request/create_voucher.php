<div class="container" style="min-height:647px">
    <div class="row" style="margin-top:100px;">
        <div class="col-xs-2"></div>
        <div class="col-xs-8">
            <div class="well" style="min-height:400px;box-shadow: 10px 10px 5px #888888;">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active"><a class="btn btn-primary" href="#reg" aria-controls="home" role="tab" data-toggle="tab">Create voucher</a></li>
                    <li role="presentation"><a class="btn btn-primary" href="#view" aria-controls="profile" role="tab" data-toggle="tab">View registered users</a></li>
                </ul>
                <!-- Tab panes -->
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="reg">          
                        <center style="font-family:Times new roman"><h2>Create voucher</h2></center>
                            <?php
                            $attributes = array('class' => 'form-group');
                            echo form_open('request/create_voucher_action', $attributes);
                            ?> 
                            <div class="row" style="">		   		
                                <div class="col-xs-3"><label>Check no:</label></div>
                                <div class="col-xs-7">
                                    <div class="form-group ">
                                        <input required type="text" name="check_no"  class="form-control" placeholder="Check no" aria-describedby="basic-addon1" >
                                    </div>
                                </div>
                                <div class="col-xs-2"></div> 
                            </div>
                            <div class="row" style="margin-top:0px">		   		
                                <div class="col-xs-3"><label>Amount:</label></div>
                                <div class="col-xs-7">
                                    <div class="form-group ">
                                        <input readonly required  type="text" class="form-control" value="<?php echo $vou['0']['amount']; ?>" aria-describedby="basic-addon1" >
                                        <input type="hidden" value="<?php echo $vou['0']['request_id']; ?>" name="request_id" />
                                    </div>
                                </div>
                                <div class="col-xs-2"></div>
                            </div>	
                            <div class="row" style="margin-top:0px">		   		
                                <div class="col-xs-3"><label>Being:</label></div>
                                <div class="col-xs-7">
                                    <div class="form-group ">
                                        <textarea readonly name="objective"  class="form-control" aria-describedby="basic-addon1" ><?php echo $vou['0']['activity_name']; ?></textarea>
                                    </div>
                                </div>
                                <div class="col-xs-2"></div>
                            </div>
                            <div class="row" style="">		   		
                                <div class="col-xs-3"><label>Item:</label></div>
                                <div class="col-xs-7">
                                    <div class="form-group ">
                                        <input readonly required type="text" name="user_title"  class="form-control" value="<?php echo $vou['0']['grf_desc']; ?>" aria-describedby="basic-addon1" >
                                    </div>
                                </div>
                                <div class="col-xs-2"></div>
                            </div>	
                            <div class="row">
                                <div class="col-xs-7"></div>

                                <div class="col-xs-3"> 
                                    <p><a href="<?php echo base_url('chop/index'); ?>" class="btn btn-default" role="button">Cancel</a> <button class="btn btn-primary" role="button">Submit</button></p>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="view">
                        <div class="row" style="">

                        </div>
                    </div>

                </div>
            </div>

            <div class="col-xs-2"></div>
        </div>
    </div>
</div>




<script>
    $('.viewmore').click(function (e) {
        e.preventDefault();
        if (!<?php echo isset($_SESSION['sess_id']['access_name']) ? 'true' : 'false'; ?>) {
            window.location.reload();
        }
        $("#ViewUserDialog").dialog('open');
        $('#userframe').html('');
        var datastring = $(this).attr('href');
        $.ajax({
            type: "GET",
            url: datastring,
            beforeSend: function () {
                $('#progressStatus').show();
            },
            success: function (data) {
                $('#userframe').html(data);
            },
            complete: function () {
                $('#progressStatus').hide();
            },
            error: function (data) {
                $('#progressStatus').hide();
            }
        });
    });
</script>
<script>
    $(document).ready(function () {
        $("#ViewUserDialog").dialog({
            title: 'User Details',
            autoOpen: false,
            resizable: false,
            height: 300,
            width: 700,
            show: {effect: 'slide', direction: "left"},
            hide: {effect: 'slide', direction: "right"},
            modal: true,
            draggable: true
        });
    });
</script>
