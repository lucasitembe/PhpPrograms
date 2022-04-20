<?php session_start(); ?>
<?php require_once("./audittrail.php")?>
<!DOCTYPE html>
<html dir="ltr" lang="en-US">

    
    <link rel="stylesheet" href="style.css" media="screen">
    	<!-- New Date Picker -->
	<link rel="stylesheet" href="pikaday.css">
        
    <!--[if lte IE 7]><link rel="stylesheet" href="style.ie7.css" media="screen" /><![endif]-->
    
    <link rel="stylesheet" href="style.responsive.css" media="all">
 

    <script src="jquery.js"></script>
    <script src="script.js"></script>
    <script src="script.responsive.js"></script>
<style>.art-content .art-postcontent-0 .layout-item-0 { margin-bottom: 10px;  }
.art-content .art-postcontent-0 .layout-item-1 { padding-right: 10px;padding-left: 10px;  }
.ie7 .art-post .art-layout-cell {border:none !important; padding:0 !important; }
.ie6 .art-post .art-layout-cell {border:none !important; padding:0 !important; }

</style>



<!--Script to timeout users-->
<script>
            //var timer;
            //var wait = 10*60*1000;
            //$(document).ready(function(){   
            //    timer = setTimeout(logout,wait);
            //  });
            //
            //document.onkeypress=setTimeOut;
            //document.onmousemove=setTimeOut;
            //
            //function setTimeOut()
            //{
            //    clearTimeout(timer);
            //    timer=setTimeout(logout,wait);
            //}
            //function logout()
            //{
            //    window.location.href='logout.php';
            //}
        </script>

        
        
        
<script type="text/javascript">
    function ConfirmLogout() {
        //function to confirm users' action to logout
        var logout=confirm("Are you  sure you want to logout?");
        if (logout) {
            location.href="logout.php";
            return true;
        }else{
            location.href="#";
            return false;
        }
    }
</script>
    
    <!--    Datepicker script-->
    <link rel="stylesheet" href="css/smoothness/jquery-ui-1.10.1.custom.min.css" />
    <script src="js/jquery-1.9.1.js"></script>
    <script src="js/jquery-ui-1.10.1.custom.min.js"></script>
    <script>
        $(function () { 
            $("#date").datepicker({ 
                changeMonth: true,
                changeYear: true,
                showWeek: true,
                showOtherMonths: true,  
                //buttonImageOnly: true, 
                //showOn: "both",
                dateFormat: "yy-mm-dd",
                //showAnim: "bounce"
            });
            
        });
    </script>
    
<!--    end of datepicker script-->
    
<!--    Datepicker script-->
    <link rel="stylesheet" href="css/smoothness/jquery-ui-1.10.1.custom.min.css" />
    <script src="js/jquery-1.9.1.js"></script>
    <script src="js/jquery-ui-1.10.1.custom.min.js"></script>
    <script>
        $(function () { 
            $("#date2").datepicker({ 
                changeMonth: true,
                changeYear: true,
                showWeek: true,
                showOtherMonths: true,  
                //buttonImageOnly: true, 
                //showOn: "both",
                dateFormat: "yy-mm-dd",
                //showAnim: "bounce"
            });
			
			$("#date3").datepicker({ 
                changeMonth: true,
                changeYear: true,
                showWeek: true,
                showOtherMonths: true,  
                //buttonImageOnly: true, 
                //showOn: "both",
                dateFormat: "yy-mm-dd",
                //showAnim: "bounce"
            });
			
			$("#date4").datepicker({ 
                changeMonth: true,
                changeYear: true,
                showWeek: true,
                showOtherMonths: true,  
                //buttonImageOnly: true, 
                //showOn: "both",
                dateFormat: "yy-mm-dd",
                //showAnim: "bounce"
            });
            
        });
    </script>
    
<!--    end of datepicker script-->






</head>
 
<body>
<div id="art-main">

<!--<nav class="art-nav">
    <ul class="art-hmenu"></ul> 
    </nav>-->
<div class="art-sheet clearfix">
            <div class="art-layout-wrapper">
                <div class="art-content-layout">
                    <div class="art-content-layout-row">
                        <div class="art-layout-cell art-content"><article class="art-post art-article">    
                                <div class="art-postcontent art-postcontent-0 clearfix">
                                    <div class="art-content-layout-wrapper layout-item-0">
<div class="art-content-layout">
    
    <style type="text/css">
	table,tr,td{
	    border: solid 1px #ccc ! important;
	}
    </style>
    