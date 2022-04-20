
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>eHMS Signature</title>
  <link rel="shortcut icon" href="../files/images/icon.png"> 

  <meta name="description" content="Signature Pad - HTML5 canvas based smooth signature drawing using variable width spline interpolation.">

  <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">

  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black">

  <link rel="stylesheet" href="css/signature-pad.css">

  <script type="text/javascript">
    var _gaq = _gaq || [];
    _gaq.push(['_setAccount', 'UA-39365077-1']);
    _gaq.push(['_trackPageview']);

    (function() {
      var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
      ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
      var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
    })();
  </script>
</head>
<body onselectstart="return false">
  <!--<a id="github" href="https://github.com/szimek/signature_pad">
    <img style="position: absolute; top: 0; right: 0; border: 0;" src="https://s3.amazonaws.com/github/ribbons/forkme_right_gray_6d6d6d.png" alt="Fork me on GitHub">
  </a>-->
  <div id="signature-pad" class="m-signature-pad">
    <div id="signature-pad-header">
    <p style="float: left;">
        <input type="submit" name="search" value="BACK" style="padding:7px;" onclick="history.go(-1)">
      </p>
      <p style="float: right;">
        <input type="text" name="reg_no" placeholder="Search Employee ID" id="reg_no" style="max-width:80%;padding:7px;" <?php if(isset($_GET['Employee_ID'])){echo "value='{$_GET['Employee_ID']}'";} ?> >
        <input type="submit" name="search" value="Get info" id="searchDoctorBtn" style="padding:7px;">
      </p>
    </div>
    <div class="m-signature-pad--body">
      <canvas></canvas>
    </div>
    <div class="m-signature-pad--footer">
      <div class="description">Employee Name</div>
      <button type="button" class="button clear" data-action="clear">Clear</button>
      <button type="button" class="button save" data-action="saveEmpBtn">Save</button>
    </div>
  </div>
  <script type="text/javascript" src="js/jquery.js"></script>
  <script src="js/signature_pad.js"></script>
  <script type="text/javascript" src="js/custom.js"></script>
  <script src="js/app.js"></script>
  
</body>
</html>