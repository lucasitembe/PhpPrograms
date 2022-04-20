<div class="row">
    <div class="col-lg-12">
        <ol class="breadcrumb">
            <li><i class="fa fa-home"></i><a href="index.html"> Home</a></li>
            <li><i class="fa fa-laptop"></i> Dashboard</li>						  	
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-lg-4 col-lg-offset-2">
        <div class="panel panel-primary ">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-bar-chart-o"></i> Account Management </h3>
            </div>
            <div class="panel-body">
                <div class="list-group">
                    <a class="list-group-item" href="<?= base_url('account/account')?>">Add New Account</a>
                    <a class="list-group-item" href="javascript:;">Edit Account</a>
                    <a class="list-group-item" href="javascript:;">Report</a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
       <div class="panel panel-primary ">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-bar-chart-o"></i> Role Management </h3>
            </div>
            <div class="panel-body">
            <div class="list-group">
                <a class="list-group-item" href="<?= base_url('account/role')?>">Add New Role</a>
                    <a class="list-group-item" href="<?= base_url('account/access_control')?>">Manag Role permission</a>
                    <a class="list-group-item" href="javascript:;">Report</a>
            </div>
            </div>
       </div>
    </div>
</div>