<style>

.dropdown-submenu {
    position:relative;
}

.dropdown-submenu>.dropdown-menu {
   top:0;left:100%;
   margin-top:-6px;margin-left:-1px;
   -webkit-border-radius:0 6px 6px 6px;-moz-border-radius:0 6px 6px 6px;border-radius:0 6px 6px 6px;
 }
  
.dropdown-submenu > a:after {
  border-color: transparent transparent transparent #333;
  border-style: solid;
  border-width: 5px 0 5px 5px;
  content: " ";
  display: block;
  float: right;  
  height: 0;     
  margin-right: -10px;
  margin-top: 5px;
  width: 0;
}
 
.dropdown-submenu:hover>a:after {
    border-left-color:#555;
 }


  
@media (max-width: 767px) {
  .navbar-nav  {
     display: inline;
  }
  .navbar-default .navbar-brand {
    display: inline;
  }
  .navbar-default .navbar-toggle .icon-bar {
    background-color: #fff;
  }
  .navbar-default .navbar-nav .dropdown-menu > li > a {
    color: red;
    background-color: #ccc;
    border-radius: 4px;
    margin-top: 2px;   
  }
   .navbar-default .navbar-nav .open .dropdown-menu > li > a {
     color: #333;
   }
   .navbar-default .navbar-nav .open .dropdown-menu > li > a:hover,
   .navbar-default .navbar-nav .open .dropdown-menu > li > a:focus {
     background-color: #ccc;
   }

   .navbar-nav .open .dropdown-menu {
     border-bottom: 1px solid white; 
     border-radius: 0;
   }
  .dropdown-menu {
      padding-left: 10px;
  }
  .dropdown-menu .dropdown-menu {
      padding-left: 20px;
   }
   .dropdown-menu .dropdown-menu .dropdown-menu {
      padding-left: 30px;
   }
   li.dropdown.open {
    border: 0px solid red;
   }

}
 
@media (min-width: 768px) {
  ul.nav li:hover > ul.dropdown-menu {
    display: block;
  }
  #navbar {
    text-align: center;
  }
}  

</style>

<?php  $group_name = $this->session->userdata('group_name');?>

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
        <li>
            <?php echo anchor('hr/', 'Dashboard'); ?>
        </li>

        <?php if($group_name=='HR Manager'||$group_name=='Head Department') { ?>

        <li class="dropdown">
          <a href="hr/" class="dropdown-toggle" onclick="return false;" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Configuration <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li> <?php echo anchor('hr/workstation', 'Work Station List'); ?> </li>
            <li> <?php echo anchor('hr/location', 'Location'); ?> </li>
            <li> <?php echo anchor('hr/trainingtype', 'Training Type'); ?> </li>
            <?php if($group_name=='HR Manager'){ ?>
             <li> <?php echo anchor('hr/leavetype', 'Leave Type'); ?> </li>
           <?php } ?>
          </ul>
        </li>
        <li class="dropdown">
            <a href="hr/" class="dropdown-toggle" onclick="return false;" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Department <span class="caret"></span></a>
            <ul class="dropdown-menu">
                <li> <?php echo anchor('hr/department', 'Department List'); ?> </li>
                <li> <?php echo anchor('hr/position', 'Position'); ?> </li>
            </ul>
        </li>
        <?php } ?>
        <?php if($group_name=='HR Manager') { ?>
        <li class="dropdown">
        <a href="hr/" class="dropdown-toggle" onclick="return false;" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">PIM <span class="caret"></span></a>
            <ul class="dropdown-menu">
                <li> <?php echo anchor('hr/employeelist', 'Employee List'); ?> </li>
                <li> <?php echo anchor('hr/addemployee', 'Add New Employee'); ?> </li>
            </ul>
        </li>
        <?php } ?>
        <?php if($group_name=='HR Manager'){ ?>
       <li>
        <?php echo anchor('hr/report', 'Report'); ?>
        </li>
        <?php } ?>
        <?php if($group_name=='HR Manager'||$group_name=='Head Department'){ ?>
        <li class="dropdown">
            <a href="hr/" class="dropdown-toggle" onclick="return false;" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Recruitment <span class="caret"></span></a>
            <ul class="dropdown-menu">
                <li> <?php echo anchor('hr/openvacancy', 'Advirtise Job Vacancy'); ?> </li>
                
                <li> <?php echo anchor('hr/candidates/', 'Candidates'); ?> </li>
            </ul>
        </li>
        <?php } ?>
        <?php if($group_name=='HR Manager'||$group_name=='Head Department') { ?>
          <li class="dropdown">
          <a href="hr/" class="dropdown-toggle" onclick="return false;" title="Key Performance Indicators" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">KPIs <span class="caret"></span></a>
            <ul class="dropdown-menu" style="z-index: 1;">
           <!-- <li style="width: 200px;"> <?php echo anchor('hr/kpicategory', 'KPIs Category'); ?> </li>-->
            <li> <?php echo anchor('hr/kpilist', 'KPIs List'); ?> </li>
            <li> <?php echo anchor('hr/managekpi', 'Manage Category KPIs'); ?> </li>
            <li> <?php echo anchor('hr/kpistatus', 'KPIs Status'); ?> </li>
            <!--<li style="width: 200px;"> <?php //echo anchor('hr/kpiemployee', 'Assign KPIs to Employee'); ?> </li>-->
            <li> <?php echo anchor('hr/assignkpi', 'Record KPIs for Employee'); ?> </li>
            <li class="dropdown dropdown-submenu">
             <?php echo anchor('hr/', 'Report','onclick="return false;"'); ?>
                <ul class="dropdown-menu">
                    <li> <?php echo anchor('hr/kpireport', 'General Reports'); ?>
                    <li> <?php echo anchor('hr/kpireportraw', 'Report Individual Raw data'); ?>
                </ul>
            </li>
            </ul>
    </li>
    <?php } ?>
    
        <?php if($group_name=='HR Manager'||$group_name=='Head Department' ) { ?>
        <li class="dropdown">
        <a href="hr/" class="dropdown-toggle" onclick="return false;" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Overtime <span class="caret"></span></a>
            <ul class="dropdown-menu">
                <li> <?php echo anchor('hr/addovertime', 'Add Overtime'); ?> </li>
                <li> <?php echo anchor('hr/overtimereport', 'Overtime Report'); ?> </li>
            </ul>
        </li>
        <?php } ?>
       <li class="dropdown">
       <a href="hr/" class="dropdown-toggle" onclick="return false;" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Leave <span class="caret"></span></a>
        <ul class="dropdown-menu">
            <li> <?php echo anchor('hr/assignleave', 'Assign Leave'); ?> </li>
            <?php if($group_name=='HR Manager'){ ?>
            <li class="dropdown dropdown-submenu"> 
              <?php echo anchor('hr/addroster', 'Leave Roster','onclick="return false;"'); ?>   
                <ul class="dropdown-menu">
                    <li><?php echo anchor('hr/addroster', 'Add in Roster'); ?>   </li>
                    <li><?php echo anchor('hr/rosterreport', 'Roster Report'); ?>   </li>
                </ul>
            </li>
            <li> <?php echo anchor('hr/approveleave', 'Approve Leave'); ?> </li>
            <li> <?php echo anchor('hr/leavesummary', 'Leave Summary'); ?> </li>
            <li> <?php echo anchor('hr/leavelist', 'Leave List'); ?> </li>
            <?php } ?>
        </ul>
    </li>
 
    
    <?php if($group_name=='Normal Employee'){
    ?>
    
    
     <li class="dropdown">
     <a href="hr/" class="dropdown-toggle" onclick="return false;" title="Key Performance Indicators" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">KPIs <span class="caret"></span></a>
        <ul class="dropdown-menu" style="z-index: 1;">
            <li style="width: 200px;"> <?php echo anchor('hr/assignkpi_employee', 'Record KPIs yourself'); ?> </li>
        </ul>
     </li>
    <?php } ?>
    
         <?php if($group_name=='admin'){ ?>
       <li class="dropdown">
       <a href="auth/" class="dropdown-toggle" onclick="return false;" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Manage Account <span class="caret"></span></a>
        <ul class="dropdown-menu">
            <li> <?php echo anchor('auth/create_user', 'Add User'); ?> </li>
        </ul>
    </li>
    <?php } ?>
    
    <?php if($group_name=='HR Manager'||$group_name=='Head Department') { ?>
    <li class="dropdown">
    <a href="hr/" class="dropdown-toggle" onclick="return false;" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Training <span class="caret"></span></a>
        <ul class="dropdown-menu">
             <li> <?php echo anchor('hr/add_to_training', 'Add in Training'); ?> </li>
             <li> <?php echo anchor('hr/trainingreport', 'Training Report'); ?> </li>
        </ul>
    </li>
    <?php } ?>
      </ul>

      <ul class="nav navbar-nav navbar-right">
        <!-- <li><a href="#">Link</a></li> -->
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> <i class="fa fa-user"></i> <?= $this->session->userdata('username') ?> <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="#" onclick="return false;" > <i class="fa fa-user"></i>
               <span style="color:#000;"> <?= $group_name; ?></span>
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