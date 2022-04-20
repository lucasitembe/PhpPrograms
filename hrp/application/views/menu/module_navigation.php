<?php  $group_name = $this->session->userdata('group_name');?>

<ul class="sf-menu">
    <li>
        <?php echo anchor('hr/', 'Dashboard'); ?>
    </li>
    <?php if(miltone_check("HR Manager") || miltone_check("Head Department")||$group_name=='HR Manager'||$group_name=='Head Department') { ?>
    <li>
        <?php echo anchor('hr/', 'Configuration','onclick="return false;"'); ?>
        <ul>
            <li style="width: 160px;"> <?php echo anchor('hr/workstation', 'Work Station List'); ?> </li>
            <li style="width: 160px;"> <?php echo anchor('hr/location', 'Location'); ?> </li>
            <li style="width: 160px;"> <?php echo anchor('hr/trainingtype', 'Training Type'); ?> </li>
            <?php if(miltone_check("HR Manager")){ ?>
             <li style="width: 160px;"> <?php echo anchor('hr/leavetype', 'Leave Type'); ?> </li>
           
           <?php } ?>
        </ul>
    </li>
      <li>
        <?php echo anchor('hr/', 'Department','onclick="return false;"'); ?>
        <ul>
            <li style="width: 160px;"> <?php echo anchor('hr/department', 'Department List'); ?> </li>
            <li style="width: 160px;"> <?php echo anchor('hr/position', 'Position'); ?> </li>
        </ul>
    </li>
    <?php } ?>
    <?php if(miltone_check("HR Manager") ||$group_name=='HR Manager') { ?>
    <li>
        <?php echo anchor('hr/', 'PIM','onclick="return false;"'); ?>
        <ul>
            <li style="width: 160px;"> <?php echo anchor('hr/employeelist', 'Employee List'); ?> </li>
            <li style="width: 160px;"> <?php echo anchor('hr/addemployee', 'Add New Employee'); ?> </li>
        </ul>
    </li>
     <?php } ?>
    <?php if(miltone_check("HR Manager") || miltone_check("Head Department") ||$group_name=='HR Manager'||$group_name=='Head Department' ) { ?>
    <li>
        <?php echo anchor('hr/', 'Overtime','onclick="return false;"'); ?>
        <ul>
            <li style="width: 160px;"> <?php echo anchor('hr/addovertime', 'Add Overtime'); ?> </li>
            <li style="width: 160px;"> <?php echo anchor('hr/overtimereport', 'Overtime Report'); ?> </li>
        </ul>
    </li>
    <?php } ?>
       <li>
        <?php echo anchor('hr/', 'Leave','onclick="return false;"'); ?>
        <ul>
            <li style="width: 160px;"> <?php echo anchor('hr/assignleave', 'Assign Leave'); ?> </li>
            <?php if(miltone_check("HR Manager")){ ?>
            <li style="width: 160px;">
              <?php echo anchor('hr/addroster', 'Leave Roster','onclick="return false;"'); ?>   
                <ul>
                    <li style="width: 160px;"><?php echo anchor('hr/addroster', 'Add in Roster'); ?>   </li>
                    <li style="width: 160px;"><?php echo anchor('hr/rosterreport', 'Roster Report'); ?>   </li>
                </ul>
            </li>
            <li style="width: 160px;"> <?php echo anchor('hr/approveleave', 'Approve Leave'); ?> </li>
            <li style="width: 160px;"> <?php echo anchor('hr/leavesummary', 'Leave Summary'); ?> </li>
            <li style="width: 160px;"> <?php echo anchor('hr/leavelist', 'Leave List'); ?> </li>
            <?php } ?>
        </ul>
    </li>
    <?php if(miltone_check("HR Manager") ||$group_name=='HR Manager'){ ?>
       <li>
        <?php echo anchor('hr/report', 'Report'); ?>
        </li>
        <?php } ?>
        <li>
            <?php if(miltone_check("HR Manager") || miltone_check("Head Depertment") ||$group_name=='HR Manager'||$group_name=='Head Department'){ ?>
        <?php echo anchor('hr/#', 'Recruitment','onclick="return false;"'); ?>
            <ul>
                <li style="width: 200px;"> <?php echo anchor('hr/openvacancy', 'Advirtise Job Vacancy'); ?> </li>
                
                <li style="width: 200px;"> <?php echo anchor('hr/candidates/', 'Candidates'); ?> </li>
            </ul>
        </li>
        <?php } ?>
        <?php if(miltone_check("HR Manager") || miltone_check("Head Department") ||$group_name=='HR Manager'||$group_name=='Head Department') { ?>
          <li>
        <?php echo anchor('hr/', 'KPIs','onclick="return false;" title="Key Performance Indicators"'); ?>
              <ul style="z-index: 1;">
           <!-- <li style="width: 200px;"> <?php echo anchor('hr/kpicategory', 'KPIs Category'); ?> </li>-->
            <li style="width: 200px;"> <?php echo anchor('hr/kpilist', 'KPIs List'); ?> </li>
            <li style="width: 200px;"> <?php echo anchor('hr/managekpi', 'Manage Category KPIs'); ?> </li>
            <li style="width: 200px;"> <?php echo anchor('hr/kpistatus', 'KPIs Status'); ?> </li>
            <!--<li style="width: 200px;"> <?php //echo anchor('hr/kpiemployee', 'Assign KPIs to Employee'); ?> </li>-->
            <li style="width: 200px;"> <?php echo anchor('hr/assignkpi', 'Record KPIs for Employee'); ?> </li>
            <li style="width: 200px;"> <?php echo anchor('hr/#', 'Report','onclick="return false;"'); ?>
                <ul>
                    <li style="width: 200px;"> <?php echo anchor('hr/kpireport', 'General Reports'); ?>
                    <li style="width: 200px;"> <?php echo anchor('hr/kpireportraw', 'Report Individual Raw data'); ?>
                </ul>
            </li>
            </ul>
    </li>
    <?php } 
    
    if(miltone_check("Normal Employee") ||$group_name=='Normal Employee'){
    ?>
    
    
     <li>
        <?php echo anchor('hr/', 'KPIs','onclick="return false;" title="Key Performance Indicators"'); ?>
              <ul style="z-index: 1;">
                   <li style="width: 200px;"> <?php echo anchor('hr/assignkpi_employee', 'Record KPIs yourself'); ?> </li>
              </ul>
     </li>
    <?php } ?>
    
         <?php if(miltone_check("admin")||$group_name=='admin'){ ?>
       <li>
        <?php echo anchor('auth/', 'Manage Account'); ?>
        <ul>
            <li style="width: 160px;"> <?php echo anchor('auth/create_user', 'Add User'); ?> </li>
            
            
        </ul>
    </li>
    <?php } ?>
    
    <?php if(miltone_check("HR Manager") || miltone_check("Head Department") ||$group_name=='HR Manager'||$group_name=='Head Department') { ?>
    <li>
        <?php echo anchor('hr/training', 'Training','onclick="return false;"'); ?>
        <ul>
             <li style="width: 160px;"> <?php echo anchor('hr/add_to_training', 'Add in Training'); ?> </li>
             <li style="width: 160px;"> <?php echo anchor('hr/trainingreport', 'Training Report'); ?> </li>
        </ul>
    </li>
    <?php } ?>
    
    
    <li>
        <?php echo anchor('auth/change_password', 'Change Password'); ?>
        </li>
</ul>
<!-- <div class="clear"></div>-->