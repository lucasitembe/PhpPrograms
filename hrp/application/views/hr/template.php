<?php 
      if ($this->session->userdata('loggedin')!='TRUE'|| empty($this->session->userdata('loggedin'))) {
        //redirect them to the login page
        redirect('auth/login', 'refresh');
    }
    
?>

<!DOCTYPE html>
<html>
    <head>
         <script type="text/javascript">
            var base_url = '<?php echo base_url(); ?>';
        </script>
        <title> <?php //echo $title;  ?> Human Resource and Payroll System </title>

         <script src="<?php echo base_url(); ?>assets/datatables/js/jquery-3.3.1.js"></script><!-- jquery file -->

        <link type="text/css" href="<?php echo base_url(); ?>media/css/styles.css" rel="stylesheet" media="all" rel="stylesheet" />
        <link type="text/css" href="<?php echo base_url(); ?>media/css/superfish.css" rel="stylesheet" media="all" rel="stylesheet" />        
        <link type="text/css" href="<?php echo base_url(); ?>media/css/calendar.css" rel="stylesheet" media="all" rel="stylesheet" />        
        <link type="text/css" href="<?php echo base_url(); ?>media/css/jquery.treeview.css" rel="stylesheet" media="all" rel="stylesheet" />        
        <!-- <script type="text/javascript" src="<?php //echo base_url(); ?>media/js/jquery.js" ></script> -->
        <script type="text/javascript" src="<?php echo base_url(); ?>media/js/superfish.js" ></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>media/js/calendar.js" ></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>media/js/chain.js" ></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>media/js/jquery.treeview.js" ></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>media/js/jquery.cookie.js" ></script>
       
        
        <!-- fontawsome files -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/font-awesome/css/font-awesome.min.css">
        <!-- end  fontawsome files -->

        <!-- bootstrap files -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/bootstrap/css/bootstrap.css">
        <script type="text/javascript" src="<?php echo base_url(); ?>assets/bootstrap/js/bootstrap.js" ></script>
         <!-- end bootstrap files -->

         <!-- datatable files -->
         <link rel="stylesheet" href="<?php echo base_url(); ?>assets/datatables/css/dataTables.bootstrap.min.css">
        
         <script src="<?php echo base_url(); ?>assets/datatables/js/jquery.dataTables.min.js"></script>
         <script src="<?php echo base_url(); ?>assets/datatables/js/dataTables.bootstrap.min.js"></script>
         <!-- datatable files -->

         <link type="text/css" href="<?php echo base_url(); ?>media/css/style.css" rel="stylesheet" media="all" rel="stylesheet" />

    </head>
    <body class="container-fluid1">
        <div id="header-box">
            <div id="header">
                <div id="logo">
                    <div style="color: #fff; margin: 20px 5px 0px 20px; font-size: 30px; font-weight: bold;">
                    HUMAN RESOURCES AND PAYROLL INFORMATION SYSTEM
                    <!-- HRP <small>v</small>2.0 -->
                    </div>
                </div>
                <div class="clear"></div>
            </div>
                        <!-- load navigation bar -->
            <?php $this->load->view('menu/navbar'); ?>


        </div>
        <div id="wrapper">
         
            <div id="content">
                <div style="height: 15px;"></div>
                    <?php $this->load->view($content); ?>
                <div style="height: 30px;"></div>
            </div>
        </div>
        <div id="footer">
            <p id="copyright" style="float: left;">&copy; <?php echo date('Y'); ?><a href="<?php echo company_info()->Website; ?>" target="_blank"><?php echo company_info()->Name; ?></a></p>
             <p id="developers"><span> Designed and Developed by :</span> <a href="http://gpitg.com" target="_blank">GPITG LTD</a></p>
        </div>
    </body>
</html>

<script>
$(document).ready(function() {
    $('#dataTable').DataTable();
} );
</script>
