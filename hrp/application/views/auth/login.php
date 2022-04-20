<html>
    <head>
        <title>Human Resource and Payroll System</title>
        <link type="text/css" href="<?php echo base_url(); ?>media/css/login.css" rel="stylesheet" media="all" rel="stylesheet" />
        <link type="text/css" href="<?php echo base_url(); ?>media/css/jtip.css" rel="stylesheet" media="all" rel="stylesheet" />
        <script type="text/javascript" src="<?php echo base_url(); ?>media/js/jquery.js" ></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>media/js/jtip.js" ></script>


        <!-- bootstrap files -->
        <script src="<?php echo base_url(); ?>assets/datatables/js/jquery-3.3.1.js"></script><!-- jquery file -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/bootstrap/css/bootstrap.min.css">
        <script type="text/javascript" src="<?php echo base_url(); ?>assets/bootstrap/js/bootstrap.min.js" ></script>
         <!-- end bootstrap files -->
         
        <style type="text/css">
            .error{
                color: red;
            }
        </style>
    </head>
    <body>
<style>
    div#home{
        width: 100%;
        margin: 0px;
        padding: 0px;
        width: 100%;
        
    }
    div#head{
        height: 100px;
        border: 0px;
        margin: -10px -5px 0px -5px;
        background-color: #0079AE;
    }
    div#head2{
        height: 40px;
        border: 0px;
        margin: 0px -5px 0px -5px;
        background-image: url('../../select.png');
    }
    body{
        margin: 1px;
        padding: 1px;
        border: 0px;
    }
    
    
    div#footer{
    position:fixed;
    left:0px;
    bottom:0px;
    height:40px;
    width:100%;
    color: white;
    background-color: #0079AE;
}

/* IE 6 */
* html div#footer {
    position:absolute;
    top:expression((0-(footer.offsetHeight)+(document.documentElement.clientHeight ? document.documentElement.clientHeight : document.body.clientHeight)+(ignoreMe = document.documentElement.scrollTop ? document.documentElement.scrollTop : document.body.scrollTop))+'px');
}

div#footer p#copyright{
    margin-left: 40px;
    float: left;
}

div#footer p#developers{
    margin-right: 40px;
    float: right;
}

div#footer p span{
    font-style: italic;
}

div#footer p a{
    color: white;
    text-decoration: none;
    font-weight: bold;
}

div#footer p a:hover{
    color: #999;
    text-decoration: underline;
    font-weight: bold;
}
    
</style>
<div id="home">
    <div id="head">
        <!--<div style="float: left; width: 200px; height: 90px; margin: 0px 0px 0px 30px;  border: 0px solid red;">
            <img src="<?php echo base_url() ?>images/logo.jpg" style="width: 180px; margin: 10px 0px 0px 0px; height: 90px;"/>    
            
        </div>-->
        <div style="float: left; margin: 30px 0px 0px 10px;">
            <div style="color: white; font-size: 30px; margin-left: 30px; font-weight: bold;">   
            <?php
           // echo company_info()->Name;
		   echo "HUMAN RESOURCES AND PAYROLL";
            ?>
            </div>
            <div style="color: yellow; font-size: 25px;  margin: 25px 0px 0px 30px;"> HRP <small>v</small>2.0</div>
        </div>
        <div style="clear: both;"></div>
    </div>
    
    <div id="head2">
        <div style="float: left; color: #FFFFFF; font-weight: bold; font-size: 15px; margin: 15px 0px 0px 50px; width: 300px;"> <?php echo anchor('hrnew/available','Available Vacancies','style="text-decoration:none; color:#FFFFFF;"'); ?></div>
        <div style="float:right; color: #FFFFFF; font-weight: bold; font-size: 15px; margin: 15px 0px 0px 0px; width: 300px;"><?php echo date('F j, Y'); ?></div>
        <div style="clear: both;"></div>
    </div>
    
    <div style="width: 1000px; border: 0px solid red; margin: auto;">
        <div style="float: left; width: 500px; border: 0px; margin: 20px 0px 0px 10px; font-size: 12px;">
            
         <p>          <b> Welcome to eHRP  </b><br/>
              <span style="margin-left: 15px; display: block; ">Electronic Human Resources na Payroll System (eHRP) holds all the information relating to employee.</span>
          </p>
          
          <p><b style="display: block;">Human Resource Manager/Officer</b></label>
              <span style="display: block; margin: 0px 5px 0px 10px;"><b>*</b> Record Departments, Workstations,Positions etc</span>
              <span style="display: block; margin: 0px 5px 0px 10px;"><b>*</b> Record Employee Informations </span>
              <span style="display: block; margin: 0px 5px 0px 10px;"><b>*</b> Performance Management </span>
              <span style="display: block; margin: 0px 5px 0px 10px;"><b>*</b> Leave Management </span>
              <span style="display: block; margin: 0px 5px 0px 10px;"><b>*</b> Recruitment </span>
              <span style="display: block; margin: 0px 5px 0px 10px;"><b>*</b> Generate && Export Various  Reports </span>
                   
       </p>
      
       <p>
           <b style="display: block;">Account - Payroll</b>
           <span style="display: block; margin: 0px 5px 0px 10px;"><b>*</b> Configuration for PAYE </span>
           <span style="display: block; margin: 0px 5px 0px 10px;"><b>*</b> Configuration for Salary Items </span>
           <span style="display: block; margin: 0px 5px 0px 10px;"><b>*</b> PENSION FUNDS </span>
           <span style="display: block; margin: 0px 5px 0px 10px;"><b>*</b> Manage Loans </span>
           <span style="display: block; margin: 0px 5px 0px 10px;"><b>*</b> Payroll </span>
           <span style="display: block; margin: 0px 5px 0px 10px;"><b>*</b> Generate Employee Salary slip </span>
      
       </p>

        </div>
        
        
        <div style="float: left; height: 350px; border: 1px solid #494949; width: 0px; margin: 20px 0px 0px 0px;"></div>
        <div style="float: left; width: 400px; border: 0px; margin: 20px 0px 0px 20px;">
            
             <div class="signin">Login</div>

                    <h5 style="color: red;"> <?php echo ($message != '' ? $message:validation_errors());?> </h5>

                    <?php echo form_open("auth/login"); ?>

                    <h5>
                        <label for="identity">E-mail</label><br/>
                        <?php echo form_input($identity); ?>
                    </h5>

                    <h5>
                        <label for="password">Password</label><br/>
                        <?php echo form_input($password); ?>
                    </h5>

                  
                   <?php echo form_submit('submit', 'Login', 'class="submit btn btn-primary"'); ?>
                  
			<a href='../../../files/index.php?Bashboard=BashboardThisPage' class="btn btn-default" style='text-decoration: none;'>
			    Dashboard
			</a>


                    <?php echo form_close(); ?>
            
            
        </div>
        <div style="clear: both;"></div>
    </div>
</div>
<div id="footer">
    <h5 id="copyright" style="float: left; font-weight: bold;">&copy; <?php echo date('Y'); ?> <a href="<?php echo company_info()->Website; ?>" target="_blank"> <?php echo company_info()->Name; ?></a> </h5>
            <h5 id="developers" class="pull-right"><span> Designed and Developed by :</span> <a href="http://www.gpitg.com" target="_blank"> GPITG LTD</a></h5>
</div>
    </body>
</html>
