<?php
    session_start();
    session_destroy();
    include("./includes/connection.php");
    require_once("files/audittrail.php");
    if(isset($_POST['submittedloginform'])){
	
        $username = mysqli_real_escape_string($conn,trim($_POST['username']));
        $password = mysqli_real_escape_string($conn,trim(MD5($_POST['password'])));
        $branch = $_POST['branch'];
	
        $query="select * from tbl_branches b, tbl_branch_employee be, tbl_employee e, tbl_privileges p, tbl_department dep
                where b.branch_id = be.branch_id and
		e.employee_id = e.employee_id and
		dep.department_id = e.department_id
                and e.employee_id = p.employee_id and p.Given_Username = '{$username}' and
		p.Given_Password  = '{$password}' and b.Branch_Name = '{$branch}';
            ";
        
        //DML excution select from..
        $result= mysqli_query($conn,$query);
        $no=mysqli_num_rows($result);
	if($no>0){
            $row=mysqli_fetch_assoc($result);
	    session_start();	    
            $_SESSION['userinfo']=$row;
	    
	    $get_client_ip=get_client_ip();
	    $get_mac_address=get_mac_address();
	    $hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']);
	    
	    
	    
	    //function to audit
	    audit($_SESSION['userinfo']['Employee_ID'],'Logged in','Dashboard','',$_SESSION['userinfo']['Branch_ID']);
	    
	    //set the timer here
	    
	    
	    $_SESSION['Reception'] = "Dashboard";
	    $_SESSION['Admision'] = "Dashboard";
	    $_SESSION['Radiology'] = "Dashboard";
	    $_SESSION['Dialysis'] = "Dashboard";
	    $_SESSION['Physiotherapy'] = "Dashboard";
	    $_SESSION['Optical'] = "Dashboard";
	    $_SESSION['Pharmacy'] = "Dashboard";
	    $_SESSION['Revenue Center'] = "Dashboard";
	    $_SESSION['Dressing'] = "Dashboard";
	    $_SESSION['Maternity'] = "Dashboard";
	    $_SESSION['Cecap'] = "Dashboard";
	    $_SESSION['Quality_Assuarance'] = "Dashboard";
	    $_SESSION['Procurement'] = "Dashboard";
	    $_SESSION['Eram'] = "Dashboard";
	    $_SESSION['Nurse_Station'] = "Dashboard";
	    $_SESSION['Laboratory'] = "Dashboard";
	    $_SESSION['Storage_And_Supply'] = "Dashboard";
	    $_SESSION['Theater'] = "Dashboard";
	    $_SESSION['Dental'] = "Dashboard";
	    $_SESSION['Ear'] = "Dashboard";
	    $_SESSION['Management'] = "Dashboard";
	    $_SESSION['Finance'] = "Dashboard";
	    $_SESSION['Setup_And_Configuration'] = "Dashboard";
	    header("Location:./files/index.php");
        }
        else { 
	    echo "<script type='text/javascript'>
                            alert('INVALID USERNAME OR PASSWORD');
                        </script>";
        }
    }
?>


<!DOCTYPE html>
<html dir="ltr" lang="en-US"><head><!-- Created by GPITG LIMITED (eHMS 2.0) -->
    <meta charset="utf-8">
    <title>Home</title>
    <meta name="viewport" content="initial-scale = 1.0, maximum-scale = 1.0, user-scalable = no, width = device-width">

    <link rel="shortcut icon" href="images/icon.png"> 
    <link rel="stylesheet" href="style.css" media="screen">
    <!--[if lte IE 7]><link rel="stylesheet" href="style.ie7.css" media="screen" /><![endif]-->
    <link rel="stylesheet" href="style.responsive.css" media="all">
 

    <script src="jquery.js"></script>
    <script src="script.js"></script>
    <script src="script.responsive.js"></script>


<style>.art-content .art-postcontent-0 .layout-item-0 { margin-bottom: 10px;  }
.art-content .art-postcontent-0 .layout-item-1 { padding-right: 10px;padding-left: 10px;  }
.ie7 .art-post .art-layout-cell {border:none !important; padding:0 !important; }
.ie6 .art-post .art-layout-cell {border:none !important; padding:0 !important; }

</style></head>
<body>
<div id="art-main">
<header class="art-header">


    <div class="art-shapes">

            </div>
 
</header>
<nav class="art-nav">
    <ul class="art-hmenu"></ul> 
    </nav>
<div class="art-sheet clearfix">
            <div class="art-layout-wrapper">
                <div class="art-content-layout">
                    <div class="art-content-layout-row">
                        <div class="art-layout-cell art-content"><article class="art-post art-article">
                                
                                                
                                <div class="art-postcontent art-postcontent-0 clearfix"><div class="art-content-layout-wrapper layout-item-0">
<div class="art-content-layout">
    <div class="art-content-layout-row">
    <div class="art-layout-cell layout-item-1" style="width: 100%" >
        <div class="clearfix"><div class="image-caption-wrapper" style="float: left;">
        </div>
        </div>
	
	<br/><br/><br/>
        <h3 style="text-align: center;">
        <img width="687" height="171" style="border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;" alt="" class="art-lightbox" src="images/Capture1.png">
            <br></h3>
        
    </div>
    </div>
</div>
</div>
</div><br/><br/>
        <center> 
	    <span style='text-decoration: none; color: #037CB0; font-size: larger;'>
		It seems that the account is </span>
		    <span style='color: red;'><b>BLOCKED</b></span>
		    <span style='color: #037CB0; font-size: larger;'> by MANAGEMENT. <br/> Please contact MANAGEMENT/ System Administrator for assistance!</span><br/><br/>
	    <a href='index.php?Home=True' class='art-button-green'>BACK</a>
        </center>
</article></div>
                    </div>
                </div>
            </div>
    </div>
<footer class="art-footer">
  <div class="art-footer-inner">
<p>Copyright &copy; 2014. All Rights Reserved. <a href='http://www.gpitg.com/' title='GPITG LIMITED' target='_blank'><b>GPITG LIMITED</b></a></p>
     
  </div>
</footer>

</div>


</body></html>