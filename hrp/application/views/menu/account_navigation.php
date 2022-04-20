<style>
.dropdown:hover .dropdown-menu {
  display: block;
}
</style>

<nav class="navbar navbar-default" style="border-top:3px solid white;">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="#">HRP <small>v</small>2.0</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse " id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
<!-- <ul class="sf-menu"> -->
    <li>
        <?php echo anchor('account/', 'Dashboard'); ?>
    </li>
    <li class="dropdown">
    <a href="account/" class="dropdown-toggle" onclick="return false;" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Configuration <span class="caret"></span></a>
        <ul class="dropdown-menu">
            <li style="width: 160px;"> <?php echo anchor('account/year', 'Add New Year'); ?> </li>
            <li style="width: 160px;"> <?php echo anchor('account/payee', 'PAYEE'); ?> </li>
            <li style="width: 160px;"> <?php echo anchor('account/salaryitem', 'Salary Items'); ?> </li>
            <li style="width: 160px;"> <?php echo anchor('account/nssf', 'NSSF PENSION'); ?> </li>
           
        </ul>
    </li>
    
    <li class="dropdown">
    <a href="account/payroll" class="dropdown-toggle" onclick="return false;" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Manage Payroll <span class="caret"></span></a>

         <ul class="dropdown-menu">
            <li style="width: 160px;"> <?php echo anchor('account/payslip', 'Payslip'); ?> </li>
            <li style="width: 160px;"> <?php echo anchor('account/monthreport', 'Monthly Report'); ?> </li>
             
        </ul>
    </li>
    
     <li class="dropdown">
     <a href="account/" class="dropdown-toggle" onclick="return false;" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> Loan Management <span class="caret"></span></a>
        <ul class="dropdown-menu">
            <li style="width: 200px;"> <?php echo anchor('account/createloan', 'Manage Loan'); ?> </li>
            <li style="width: 200px;"> <?php echo anchor('account/approveloan', 'Approve Loan'); ?> </li>
            <li style="width: 200px;"> <?php echo anchor('account/deliverloan', 'Deliver Loan'); ?> </li>
            <li style="width: 200px;"> <?php echo anchor('account/repaymentschedule', 'Repayment schedule'); ?> </li>
            <li style="width: 200px;"> <?php echo anchor('account/repayment', 'Loan Repayment'); ?> </li>
            <li style="width: 200px;"> <?php echo anchor('account/skiprepayment', 'Skip Loan Repayment'); ?> </li>
           
        </ul>
    </li>
</ul>

<?php  $group_name = $this->session->userdata('group_name');?>

<ul class="nav navbar-nav navbar-right">
        <!-- <li><a href="#">Link</a></li> -->
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> <i class="fa fa-user"></i> <?= $this->session->userdata('username') ?> <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="#" onclick="return false;" > <i class="fa fa-user"></i>
               <span class="text-info"> <?= $group_name; ?></span>
             </a></li>
             <li role="separator" class="divider"></li>

            <li><a href="#"><i class="fa fa-cog"></i> Change password</a></li>
             <li role="separator" class="divider"></li>
            <li><a href="<?php echo site_url('auth/logout');?>"> <i class="fa fa-lock"></i> Logout</a></li>
          </ul>
        </li>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>
