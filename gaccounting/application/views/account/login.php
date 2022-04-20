<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">  
        <title>Login | gAccounting</title>

        <link rel="stylesheet" href="<?= base_url() ?>assets/css/style.css"  />


        <style type="text/css">
            #title{
                color: #fff;
            }
            
            body{
                 background-image: url("<?= base_url() ?>assets/images/background_banner_medifiac.gif");
                 background-repeat:no-repeat;
                 background-size:cover;
                     
            }

        </style> 
    </head>

    <body>
         <div style="color: rgb(255, 255, 255); text-align: center;" id="title-header">
                <h1 style="font-size: 80px; margin: 0px;">G-Accounting</h1>
                <h3 style="margin-top: 0px;">Integrated Accounting Software</h3>
            </div>
        <div class="main">
            <div class="box">
                <h2>Sign in to your account</h2>
                <h3>Please enter your name and password to log in.</h3>
                <?php if ($this->session->flashdata('login_error') != null) { ?> 
                    <div class="alert alert-danger" role="alert" data-dismiss="alert" id="alertError">
                       <strong>Error!</strong> <span id="errmsg"> <?= $this->session->flashdata('login_error') ?> </span>
                    </div>
                    <?php
                }

                $attributes = array('autocomplete' => 'off', 'class' => 'form', 'id' => 'defaultform');


                echo form_open(site_url("Account/authorize"), $attributes);
                ?>
                <fieldset>
                    <div class="row">
                        <input type="text" class="login" id="userName" autocomplete="off" name="userName"  placeholder="Username" />
                        <!-- To mark the incorrectly filled input, you must add the class "error" to the input -->
                        <!-- example: <input type="text" class="login error" name="login" value="Username" /> -->
                    </div>
                    <div class="row">
                        <input type="password" class="password" id="userPassword"  autocomplete="off" name="userPassword" placeholder="Password"/>
                        <a class="forgot" href="#">I forgot my password</a>
                    </div>  
                    <div class="row">


                        <input type="submit" value="Sign in" />
                    </div>
                </fieldset>
                </form>     
            </div>
            <input type="hidden" id="crsTokenName" value="<?= $this->security->get_csrf_token_name(); ?>">
            <input type="hidden" id="baseurl" value="<?= base_url(); ?>">

            <!-- /#wrapper --> 
            <script type="text/javascript" src="<?= base_url() ?>assets/js/jquery-3.1.1.min.js"></script>
            <script type="text/javascript" src="<?= base_url() ?>assets/bootstrap/js/bootstrap.min.js"></script>
            <script type="text/javascript" src="<?= base_url() ?>assets/jquery-validation/jquery.validate.min.js"></script>

            <span class="copy">Copyright © <?php echo date("Y"); ?>  GPITG Ltd.</span>
        </div>
    </body>
</html>
