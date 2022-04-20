
<html>
    <head>
        <title>Human Resource and Payroll System</title>
         <script type="text/javascript">
            var base_url = '<?php echo base_url(); ?>';
        </script>
        <title> <?php //echo $title;  ?> Human Resource and Payroll System </title>
        <link type="text/css" href="<?php echo base_url(); ?>media/css/style2.css" rel="stylesheet" media="all" rel="stylesheet" />
        <link type="text/css" href="<?php echo base_url(); ?>media/css/superfish.css" rel="stylesheet" media="all" rel="stylesheet" />        
        <link type="text/css" href="<?php echo base_url(); ?>media/css/calendar.css" rel="stylesheet" media="all" rel="stylesheet" />        
        <script type="text/javascript" src="<?php echo base_url(); ?>media/js/jquery.js" ></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>media/js/superfish.js" ></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>media/js/calendar.js" ></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>media/js/chain.js" ></script>
       
        <link type="text/css" href="<?php echo base_url(); ?>media/css/style.css" rel="stylesheet" media="all" rel="stylesheet" />
        
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
        background-color: #126784;
    }
    div#head2{
        height: 40px;
        border: 0px;
        margin: 0px -5px 0px -5px;
        background-image: url('<?php echo base_url() ?>select.png');
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
    color: #999;
    background-color: #162529;
}

/* IE 6 */
* html div#footer {
    position:absolute;
    top:expression((0-(footer.offsetHeight)+(document.documentElement.clientHeight ? document.documentElement.clientHeight : document.body.clientHeight)+(ignoreMe = document.documentElement.scrollTop ? document.documentElement.scrollTop : document.body.scrollTop))+'px');
}

div#footer p#copyright{
    margin-left: 40px;
    float: left;
    font-size: 13px;
}

div#footer p#developers{
    margin-right: 40px;
    float: right;
    font-size: 13px;
}

div#footer p span{
    font-style: italic;
}

div#footer p a{
    color: #999;
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
        <div style="float: left; width: 200px; height: 90px; margin: 0px 0px 0px 30px;  border: 0px solid red;">
            <img src="<?php echo base_url() ?>images/logo.jpg" style="width: 200px; margin: 10px 0px 0px 0px; height: 90px;"/>    
            
        </div>
         <div style="float: left; margin: 30px 0px 0px 10px;">
            <div style="color: yellow; font-size: 30px; margin-left: 30px; font-weight: bold;">   
            <?php
            echo company_info()->Name;
            ?>
            </div>
            <div style="color: yellow; font-size: 25px;  margin: 20px 0px 0px 30px;">  eHURIS - ELECTRONIC HUMAN RESOURCES INFORMATION SYSTEM</div>
        </div>
        <div style="clear: both;"></div>
        
        
    </div>
    <div id="head2">
        
        <div style="float: left; color: #FFFFFF; font-weight: bold; font-size: 15px; margin: 15px 0px 0px 50px; width: 300px;"> <?php echo anchor('auth/','Back to Login','style="text-decoration:none; color:#FFFFFF;font-weight:bold;"'); ?></div>
        <div style="float:right; color: #FFFFFF; font-weight: bold; font-size: 15px; margin: 15px 0px 0px 0px; width: 300px;"><?php echo date('F j, Y'); ?></div>
        <div style="clear: both;"></div>
    </div>
  
    <div style="margin: 20px; ">
        
        <?php $this->load->view($content); ?>
        
    </div>
</div>
   <div id="footer">
    <p id="copyright" style="float: left; font-weight: bold;">&copy; <?php echo date('Y'); ?> <a href="<?php echo company_info()->Website; ?>" target="_blank"> <?php echo company_info()->Name; ?></a> </p>
            <p id="developers"><span> Designed and Developed by :</span> <a href="http://www.gpitg.com" target="_blank"> GPITG LTD</a></p>
</div>
    </body>
</html>