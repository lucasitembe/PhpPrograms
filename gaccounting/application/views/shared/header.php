
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>gAccounting - Home</title>
        <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/bootstrap/css/bootstrap.min.css" />
        <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/font-awesome/css/font-awesome.min.css" />
        <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/css/jquery.datetimepicker.css" />
	<link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/css/jquery.jqplot.min.css" />
        <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/css/iosOverlay.css" />
        <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/css/local.css" />
        <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/bootstrap-chosen/bootstrap-chosen.css" />


        <!-- you need to include the shieldui css and js assets in order for the charts to work -->
<!--        <link rel="stylesheet" type="text/css" href="http://www.shieldui.com/shared/components/latest/css/light-bootstrap/all.min.css" />
        <link id="gridcss" rel="stylesheet" type="text/css" href="http://www.shieldui.com/shared/components/latest/css/dark-bootstrap/all.min.css" />-->

    </head>
    <body>
        <div id="wrapper">
            <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="index.html">gAccounting</a>
                </div>
                <div class="collapse navbar-collapse navbar-ex1-collapse" style="z-index:-10;">
                    <ul id="active" class="nav navbar-nav side-nav" >
                        <li class="selected"><a href="<?=base_url()?>"><i class="fa fa-bullseye"></i> Dashboard</a></li>
                        <li><a href="<?=base_url('gledger')?>"><i class="fa fa-tasks"></i> General Ledger</a></li>
                        <li><a href="<?=base_url('gledger/journalReport')?>"><i class="fa fa-archive"></i> Journal Report</a></li>
                        <li><a href="<?=base_url('gledger/LedgerStatementReport')?>"><i class="fa fa-book"></i> Ledger Statement</a></li>
                        <li><a href="<?=base_url('gledger/trialBalance')?>"><i class="fa fa-area-chart"></i> Trial Balance Report</a></li>
                         <li><a href="<?=base_url('gledger/profitloss')?>"><i class="fa fa-briefcase"></i> Income Statement </a></li>
                          <li><a href="<?=base_url('gledger/balancesheet')?>"><i class="fa fa-calculator"> </i> Balance sheet</a></li>
                        <li><a href="<?=base_url('gassets')?>"><i class="fa fa-globe"></i> Asset Management</a></li>
                        <li><a href="<?=base_url('chop')?>"><i class="fa fa-suitcase"></i> Budget Management</a></li>
                        <li><a href="http://localhost/Final_One/hrp/index.php/auth/login" target="blanc"><i class="fa fa-briefcase"></i> Human Resource</a></li>
                        <li><a href="<?=base_url('setting')?>"><i class="fa fa-cog"></i> Settings</a></li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right navbar-user">
                        <li class="dropdown messages-dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-envelope"></i> Messages <span class="badge">2</span> <b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li class="dropdown-header">2 New Messages</li>
                                <li class="message-preview">
                                    <a href="#">
                                        <span class="avatar"><i class="fa fa-bell"></i></span>
                                        <span class="message">Security alert</span>
                                    </a>
                                </li>
                                <li class="divider"></li>
                                <li class="message-preview">
                                    <a href="#">
                                        <span class="avatar"><i class="fa fa-bell"></i></span>
                                        <span class="message">Security alert</span>
                                    </a>
                                </li>
                                <li class="divider"></li>
                                <li><a href="#">Go to Inbox <span class="badge">2</span></a></li>
                            </ul>
                        </li>
                        <li class="dropdown user-dropdown">
                            <?php
                             $name=$this->session->userinfo->fname.' '.$this->session->userinfo->lname;
                             //$name=$userinfo->fname;
                            ?>
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> <?= $name ?><b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li><a href="#"><i class="fa fa-user"></i> Profile</a></li>
                                <li><a href="#"><i class="fa fa-gear"></i> Settings</a></li>
                                <li class="divider"></li>
                                <li><a href="<?= site_url('Account/logout')?>"><i class="fa fa-power-off"></i> Log Out</a></li>

                            </ul>
                        </li>
                        <li>
                            <form class="navbar-search">
                                <input type="text" placeholder="Search" class="form-control">
                            </form>
                        </li>
                    </ul>
                </div>
            </nav>
            
<div id="page-wrapper">
    <div id="loading"></div>
