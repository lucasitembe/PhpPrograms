
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
  <script type="text/javascript" src="SigWebTablet.js"></script>
  <!--SigWebTablet.js is required for SigWeb to function-->
  <!--SigWebTablet.js is located here and may be copied for your own use-->
  <!--http://www.sigplusweb.com/SigWebTablet.js-->


  <script type="text/javascript">
  var tmr;

  var resetIsSupported = false;
  var SigWeb_1_6_4_0_IsInstalled = false; //SigWeb 1.6.4.0 and above add the Reset() and GetSigWebVersion functions
  var SigWeb_1_7_0_0_IsInstalled = false; //SigWeb 1.7.0.0 and above add the GetDaysUntilCertificateExpires() function

  window.onload = function(){
		if(IsSigWebInstalled()){
			var sigWebVer = "";
			try{
				sigWebVer = GetSigWebVersion();
			} catch(err){console.log("Unable to get SigWeb Version: "+err.message)}
			
			if(sigWebVer != ""){				
				try {
					SigWeb_1_7_0_0_IsInstalled = isSigWeb_1_7_0_0_Installed(sigWebVer);
				} catch( err ){console.log(err.message)};
				//if SigWeb 1.7.0.0 is installed, then enable corresponding functionality
				if(SigWeb_1_7_0_0_IsInstalled){
					 
					resetIsSupported = true;
					try{
						var daysUntilCertExpires = GetDaysUntilCertificateExpires();
						document.getElementById("daysUntilExpElement").innerHTML = "SigWeb Certificate expires in " + daysUntilCertExpires + " days.";
					} catch( err ){console.log(err.message)};
					var note = document.getElementById("sigWebVrsnNote");
					note.innerHTML = "SigWeb 1.7.0 installed";
				} else {
					try{
						SigWeb_1_6_4_0_IsInstalled = isSigWeb_1_6_4_0_Installed(sigWebVer);
						//if SigWeb 1.6.4.0 is installed, then enable corresponding functionality						
					} catch( err ){console.log(err.message)};
					if(SigWeb_1_6_4_0_IsInstalled){
						resetIsSupported = true;
						var sigweb_link = document.createElement("a");
						sigweb_link.href = "https://www.topazsystems.com/software/sigweb.exe";
						sigweb_link.innerHTML = "https://www.topazsystems.com/software/sigweb.exe";

						var note = document.getElementById("sigWebVrsnNote");
						note.innerHTML = "SigWeb 1.6.4 is installed. Install the newer version of SigWeb from the following link: ";
						note.appendChild(sigweb_link);
					} else{
						var sigweb_link = document.createElement("a");
						sigweb_link.href = "https://www.topazsystems.com/software/sigweb.exe";
						sigweb_link.innerHTML = "https://www.topazsystems.com/software/sigweb.exe";

						var note = document.getElementById("sigWebVrsnNote");
						note.innerHTML = "A newer version of SigWeb is available. Please uninstall the currently installed version of SigWeb and then install the new version of SigWeb from the following link: ";
						note.appendChild(sigweb_link);
					}	
				}	
			} else{
				//Older version of SigWeb installed that does not support retrieving the version of SigWeb (Version 1.6.0.2 and older)
				var sigweb_link = document.createElement("a");
				sigweb_link.href = "https://www.topazsystems.com/software/sigweb.exe";
				sigweb_link.innerHTML = "https://www.topazsystems.com/software/sigweb.exe";

				var note = document.getElementById("sigWebVrsnNote");
				note.innerHTML = "A newer version of SigWeb is available. Please uninstall the currently installed version of SigWeb and then install the new version of SigWeb from the following link: ";
				note.appendChild(sigweb_link);
			}
		}
		else{
			alert("Unable to communicate with SigWeb. Please confirm that SigWeb is installed and running on this PC.");
		}
		}
	
  function isSigWeb_1_6_4_0_Installed(sigWebVer){
    var minSigWebVersionResetSupport = "1.6.4.0";

    if(isOlderSigWebVersionInstalled(minSigWebVersionResetSupport, sigWebVer)){
      console.log("SigWeb version 1.6.4.0 or higher not installed.");
      return false;
    }
    return true;
  }
  
  function isSigWeb_1_7_0_0_Installed(sigWebVer) {
	var minSigWebVersionGetDaysUntilCertificateExpiresSupport = "1.7.0.0";
	
	if(isOlderSigWebVersionInstalled(minSigWebVersionGetDaysUntilCertificateExpiresSupport, sigWebVer)){
      console.log("SigWeb version 1.7.0.0 or higher not installed.");
      return false;
    }
    return true;
  }

  function isOlderSigWebVersionInstalled(cmprVer, sigWebVer){    
      return isOlderVersion(cmprVer, sigWebVer);
  }

  function isOlderVersion (oldVer, newVer) {
    const oldParts = oldVer.split('.')
    const newParts = newVer.split('.')
    for (var i = 0; i < newParts.length; i++) {
      const a = parseInt(newParts[i]) || 0
      const b = parseInt(oldParts[i]) || 0
      if (a < b) return true
      if (a > b) return false
    }
    return false;
  }

  function onSign()
  {
    if(IsSigWebInstalled()){
      var ctx = document.getElementById('cnv').getContext('2d');
      SetDisplayXSize( 500 );
      SetDisplayYSize( 100 );
      SetTabletState(0, tmr);
      SetJustifyMode(0);
      ClearTablet();
      if(tmr == null)
      {
        tmr = SetTabletState(1, ctx, 50);
      }
      else
      {
        SetTabletState(0, tmr);
        tmr = null;
        tmr = SetTabletState(1, ctx, 50);
      }
    } else{
      alert("Unable to communicate with SigWeb. Please confirm that SigWeb is installed and running on this PC.");
    }
  }

  function onClear()
  {
    ClearTablet();
  }

  function onDone()
  {
    if(NumberOfTabletPoints() == 0)
    {
      alert("Please sign before continuing");
    }
    else
    {
      SetTabletState(0, tmr);
      //RETURN TOPAZ-FORMAT SIGSTRING
      SetSigCompressionMode(1);
      document.FORM1.bioSigData.value=GetSigString();
      document.FORM1.sigStringData.value = GetSigString();
      //this returns the signature in Topaz's own format, with biometric information


      //RETURN BMP BYTE ARRAY CONVERTED TO BASE64 STRING
      SetImageXSize(500);
      SetImageYSize(100);
      SetImagePenWidth(5);
      GetSigImageB64(SigImageCallback);
    }
  }

  function SigImageCallback( str )
  {
    document.FORM1.sigImageData.value = str;
  }

  function endDemo()
  {
    ClearTablet();
    SetTabletState(0, tmr);
  }

  function close(){
    if(resetIsSupported){
      Reset();
    } else{
      endDemo();
    }
  }

  //Perform the following actions on
  //	1. Browser Closure
  //	2. Tab Closure
  //	3. Tab Refresh
  window.onbeforeunload = function(evt){
    close();
    clearInterval(tmr);
    evt.preventDefault(); //For Firefox, needed for browser closure
  };
</script>
  <script type="text/javascript">
    /*var _gaq = _gaq || [];
    _gaq.push(['_setAccount', 'UA-39365077-1']);
    _gaq.push(['_trackPageview']);

    (function() {
      var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
      ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
      var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
    })();*/
  </script>
</head>
<body onselectstart="return false">
  <!--<a id="github" href="https://github.com/szimek/signature_pad">
    <img style="position: absolute; top: 0; right: 0; border: 0;" src="https://s3.amazonaws.com/github/ribbons/forkme_right_gray_6d6d6d.png" alt="Fork me on GitHub">
  </a>-->
  <div id="signature-pad" class="m-signature-pad">
    <div id="signature-pad-header">
    <p style="float: left;">
       <input type="submit" name="search" value="Back" style="padding:7px;cursor:pointer;" onclick="history.go(-1)" title="Go back">
      </p>
      <p style="float: right;">
        <input type="hidden" id="Check_In_ID" value="<?=$_GET['Check_In_ID'];?>">
        <input type="text" name="reg_no" placeholder="Search Patient Reg #" <?php if(isset($_GET['Registration_ID'])){echo "value='{$_GET['Registration_ID']}'";}?> id="reg_no" style="max-width:80%;padding:7px;">
        <input type="submit" name="search" value="Search" id="searchPatientBtn" style="padding:7px;">
      </p>
    </div>
    <div class="m-signature-pad--body">
      <canvas id="cnv" name="cnv" width="500" height="100"></canvas>
    </div>
    <div class="m-signature-pad--footer">
      <div class="description">Patient Name <br> Reg # : ------, SPONSOR</div>
      <input type="button" class="button"  id="SignBtn" name="SignBtn" type="button" value="Sign"  onclick="javascript:onSign()">Sign</input>
      <button type="button" class="button" data-action="clear" id="button1" name="ClearBtn" type="button" value="Clear" onclick="javascript:onClear()" style="margin-left: 3rem;">Clear</button>
      <button type="button" class="button save" data-action="save">Save</button>
    </div>
  </div>
  <script type="text/javascript" src="js/jquery.js"></script>
  <script src="js/signature_pad.js"></script>
  <script type="text/javascript" src="js/custom.js"></script>
  <script src="js/app.js"></script>
  
</body>
</html>