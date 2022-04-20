<?php
    include("./includes/header.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Reception_Works'])){
	    if($_SESSION['userinfo']['Reception_Works'] != 'yes'){
		header("Location: ./index.php?InvalidPrivilege=yes");
	    }
	}else{
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	    header("Location: ../index.php?InvalidPrivilege=yes");
    }
	
?>
<input type='button' name='patient_outpatient' id='patient_outpatient' value='DIRECT DEPT - OUTPATIENT' onclick='outpatient()' class='art-button-green' />
<input type='button' name='patient_inpatient' id='patient_inpatient' value='DIRECT DEPT - INPATIENT' onclick='inpatient()' class='art-button-green' />
       
<?php
 if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Reception_Works'] == 'yes'){ 
?>
    <a href='index.php?Bashboard=BashboardThisPage' class='art-button-green'>
         BACK
    </a>
<?php  } } ?>

<script type='text/javascript'>
    function access_Denied(){ 
   alert("Access Denied");
   document.location = "./index.php";
    }
</script>
<br/><br/><br/><br/><br/><br/>
<fieldset>  
            <legend align=center><b>FOOD AND LAUNDRY WORKS</b></legend>
        <center><table width = 60%>
            <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if($_SESSION['userinfo']['Reception_Works'] == 'yes'){ ?>
                    <a href='foodworkpage.php'>
                        <button style='width: 100%; height: 100%'>
                            Food Workpage
                        </button>
                    </a>
                    <?php }else{ ?>
                     
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Food Workpage
                        </button>
                  
                    <?php } ?>
                </td>
                </tr>
				<tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if($_SESSION['userinfo']['Reception_Works'] == 'yes'){ ?>
                    <a href='laundryworkpage.php'>
                        <button style='width: 100%; height: 100%'>
                            Laundry Workpage
                        </button>
                    </a>
                    <?php }else{ ?>
                     
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Laundry Workpage
                        </button>
                  
                    <?php } ?>
                </td>
				</tr>
				
        </table>
        </center>
</fieldset><br/>
<script type='text/javascript'>
      function outpatient(){
        //alert('outpatient');
        var winClose=popupwindow('directdepartmentalpayments.php?location=otherdepartment&DirectDepartmentalList=DirectDepartmentalListThisForm', 'Outpatient Item Add', 1300, 700);
     
      }
    </script>
    <script type='text/javascript'>
      function inpatient(){
         var winClose=popupwindow('adhocinpatientlist.php?location=otherdepartment&AdhocInpatientList=AdhocInpatientListThisPage', 'Intpatient Item Add', 1300, 700);
     
      }
    </script>
    
    <script>
  function popupwindow(url, title, w, h) {
  var  wLeft = window.screenLeft ? window.screenLeft : window.screenX;
   var wTop = window.screenTop ? window.screenTop : window.screenY;

    var left = wLeft + (window.innerWidth / 2) - (w / 2);
    var top = wTop + (window.innerHeight / 2) - (h / 2);
    var mypopupWindow= window.showModalDialog(url, title,'dialogWidth:' + w + '; dialogHeight:' + h+'; center:yes;dialogTop:' + top + '; dialogLeft:' + left );//'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=no, copyhistory=no, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);
      
      return mypopupWindow;
}


</script>
<?php
    include("./includes/footer.php");
?>