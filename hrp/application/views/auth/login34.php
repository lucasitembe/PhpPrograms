<html>
    <head>
        <title>Student Information Management System</title>
        <link type="text/css" href="<?php echo base_url(); ?>media/css/login.css" rel="stylesheet" media="all" rel="stylesheet" />
        <link type="text/css" href="<?php echo base_url(); ?>media/css/jtip.css" rel="stylesheet" media="all" rel="stylesheet" />
        <script type="text/javascript" src="<?php echo base_url(); ?>media/js/jquery.js" ></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>media/js/jtip.js" ></script>
        <style type="text/css">
            .error{
                color: red;
            }
        </style>
    </head>
    <body>
        <div id="wrapper">
             <div id="school_name">
              <?php
 // $collg= get_collage_info();
  //echo $collg[0]->Name;
             
?>
                  ICT SOLUTIONS DESIGN
            </div>
             <div id="title">
               HUMAN RESOURCE AND PAYROLL SYSTEM { HRPS }
            </div>
            <div id="loginArea" style="height: 350px;">
                <div id="school_logo" style="font-size: 12px;">
          <p>          <b> Welcome to HRP </b><br/>
              <span style="margin-left: 15px; display: block; ">The Human Resource and Payroll System (HRP) holds all the information relating to employee.</span>
          </p>
          <p><b style="display: block;">Human Resource Manager/Officer</b></label>
              <span style="display: block; margin: 0px 5px 0px 10px;"><b>*</b> Record Departments, Workstations,Positions etc</span>
              <span style="display: block; margin: 0px 5px 0px 10px;"><b>*</b> Record Employee Informations </span>
              <span style="display: block; margin: 0px 5px 0px 10px;"><b>*</b> Generate Various  Reports </span>
              <span style="display: block; margin: 0px 5px 0px 10px;"><b>*</b> Export Reports </span>
           
         
       </p>
       <p>
           <b style="display: block;">Account - Payroll</b>
           <span style="display: block; margin: 0px 5px 0px 10px;"><b>*</b> Configuration for PAYEE </span>
           <span style="display: block; margin: 0px 5px 0px 10px;"><b>*</b> Configuration for Salary Items </span>
           <span style="display: block; margin: 0px 5px 0px 10px;"><b>*</b> PENSION FUNDS </span>
           <span style="display: block; margin: 0px 5px 0px 10px;"><b>*</b> Manage Loans </span>
           <span style="display: block; margin: 0px 5px 0px 10px;"><b>*</b> Payroll </span>
           <span style="display: block; margin: 0px 5px 0px 10px;"><b>*</b> Generate Employee Salary slip </span>
       </p>
      
                </div>
                <div id="loginbox">

                    <div class="signin">Login</div>

                    <div style="color: red;"> <?php echo ($message != '' ? $message:validation_errors());?> </div>

                    <?php echo form_open("auth/login"); ?>

                    <p>
                        <label for="identity">Username</label><br/>
                        <?php echo form_input($identity); ?>
                    </p>

                    <p>
                        <label for="password">Password</label><br/>
                        <?php echo form_input($password); ?>
                    </p>

                    <p>
                        <?php echo form_checkbox('remember', '1', FALSE); ?>
                        <label for="remember" style="font-size: 15px; ">Remember me</label>
                         <a href="<?php echo site_url('auth/ajax?width=230'); ?>" class="jTip" id="help" name="Student login help :">
                            <img src="<?php echo base_url('images/help.png'); ?>" style="border: 0px;" />
                        </a>  
                    <p><?php echo form_submit('submit', 'Login', 'class="submit"'); ?></p>


                    <?php echo form_close(); ?>

                </div>
                <div class="clear"></div>
                
            </div>
            <div style="text-align: center; margin: 5px 0px 0px 0px; font-size: 12px;">For more communication use simssystem2012@gmail.com</div> 
        </div>
      
    </body>
</html>