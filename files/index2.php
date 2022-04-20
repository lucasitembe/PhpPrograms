<?php
    session_start();
    include("./includes/connection.php"); 
    if(isset($_POST['submittedloginform'])){
        $username=trim($_POST['username']);
        $password=trim(MD5($_POST['password']));
        $branch = $_POST['branch'];
	
        $query="select * from tbl_branches b, tbl_branch_employee be, tbl_employee e, tbl_privileges p
                where b.branch_id = be.branch_id and e.employee_id = e.employee_id
                and e.employee_id = p.employee_id and p.Given_Username = '$username' and
		p.Given_Password  = '$password' and b.Branch_Name = '$branch';
            ";
        
        //DML excution select from..
        $result= mysqli_query($conn,$query);
        $no=mysqli_num_rows($result);
	if($no>0){
            $row=mysqli_fetch_assoc($result);
            $_SESSION['userinfo']=$row;
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
<html dir="ltr" lang="en-US"><head><!-- Created by Artisteer v4.1.0.59861 -->
    <meta charset="utf-8">
    <title>Home</title>
    <meta name="viewport" content="initial-scale = 1.0, maximum-scale = 1.0, user-scalable = no, width = device-width">

    <!--[if lt IE 9]><script src="https://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
    <link rel="stylesheet" href="style.css" media="screen">
    <!--[if lte IE 7]><link rel="stylesheet" href="style.ie7.css" media="screen" /><![endif]-->
    <link rel="stylesheet" href="style.responsive.css" media="all">
<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Ubuntu&amp;subset=latin">

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
        <h3 style="text-align: center;">
        <img width="687" height="171" style="border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;" alt="" class="art-lightbox" src="images/Capture1.png">
            <br></h3>
        
    </div>
    </div>
</div>
</div>
</div><br/><br/>
        <center>
            <form action='#' method='post' name='login' id='login' name="myForm" onsubmit="return validateForm();">
                <table width=35%>
                    <tr>
                        <td style='text-align: right;'><b>USERNAME</b></td>
                        <td><input type='text' name='username' id='username' required='required' size=30></td>
                    </tr>
                    <tr>
                        <td style='text-align: right;'><b>PASSWORD</b></td>
                        <td><input type='password' name='password' id='password' required='required' size=30></td>
                    </tr>
                    <tr>
                        <td style='text-align: right;'><b>SELECT BRANCH</b></td>
                        <td>
                            <select name='branch' id='branch'> 
                                        <?php
                                            $data = mysqli_query($conn,"select * from tbl_branches");
                                            while($row = mysqli_fetch_array($data)){
                                                echo '<option>'.$row['Branch_Name'].'</option>';
                                            }
                                        ?> 
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td colspan=2 style='text-align: center;'>
                            <input type='submit' name='submit' id='submit' class='art-button-green' value='         LOGIN         ' title='LOGIN'>
                            <input type='hidden' name='submittedloginform' id='submittedloginform' value='true'>
                        </td>
                    </tr></form>
                    <tr> 
                        <td colspan=2 style='text-align: center;'><a href='#'><button name='changepassword' id='changepassword' class='art-button-green'>CHANGE PASSWORD</button></a></td>
                    </tr>
                </table>
            
        </center>
</article></div>
                    </div>
                </div>
            </div>
    </div>
<footer class="art-footer">
  <div class="art-footer-inner">
<p>Copyright © 2014. All Rights Reserved. <a href='http://www.gpitg.com/' title='GPITG LIMITED' target='><b>GPITG LIMITED</b></a></p>
     
  </div>
</footer>

</div>


</body></html>