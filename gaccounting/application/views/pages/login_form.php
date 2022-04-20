<div class="container" style="min-height:627px">
    <div class="row" style="margin-top:100px">
        <div class="well" style="min-height:400px;box-shadow: 5px 10px  10px #888888">
            <?php
            if ($error == 1) {
                ?>
                <div class="alert alert-info alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <center style="color:red"><span style="color:red" class="glyphicon glyphicon-alert"></span> <b>Wrong username or password!</b></center>
                </div>

                <?php
            }
            ?>

            <?php
            $attributes = array('class' => 'form-group');
            echo form_open('chop_login/login', $attributes);
            ?> 
            <div class="col-md-3" style="padding-top:20px;height:300px"></div>
            <div class="col-xs-12 col-md-6 " style="border:2px solid white;padding-top:20px;box-shadow: 0 0  10px #888888;margin-top:50px">
                <center><h1>Login here</h1></center>
                <div class="row">
                    <div class="col-xs-12 col-md-1" ></div>			
                    <div class="col-xs-12 col-md-3" ><label>Username:</label></div>
                    <div class="col-xs-12 col-md-8" >
                        <div class="form-group ">
                            <input type="text" class="form-control" name="user_name" id="mail" placeholder="Enter Username" aria-describedby="basic-addon1" required>
                        </div>
                    </div>
                </div>
                <div class="row"> 
                    <div class="col-xs-12 col-md-1" ></div>
                    <div class="col-xs-12 col-md-3" ><label>Password:</label></div>
                    <div class="col-xs-12 col-md-8" >
                        <div class="form-group ">
                            <input type="password" class="form-control" name="pass_word" id="address" placeholder="Enter password" aria-describedby="basic-addon1" required>
                        </div>
                    </div>
                </div>  
                <div class="row"> 
                    <div class="col-xs-9 " ><label></label></div>	 
                    <div class="col-xs-3" >
                        <div class="form-group ">
                            <input type="submit"  class="btn btn-primary" value="Submit" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3" style="padding-top:20px;height:300px"></div>
            </form>
        </div>
    </div>
</div>

<!-- Calling Add file -->
<script src="<?php echo base_url('js/add_file.js'); ?>"></script>
