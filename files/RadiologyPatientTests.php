<?php
include("includes/header.php");
include("includes/connection.php");

	/* ****************************SESSION CONTROL****************************** */
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Reception_Works'])){
	    if($_SESSION['userinfo']['Radiology_Works'] != 'yes' && $_SESSION['userinfo']['	Doctors_Page_Inpatient_Work'] != 'yes' && $_SESSION['userinfo']['	Doctors_Page_Outpatient_Work'] != 'yes'){
			header("Location: ./index.php?InvalidPrivilege=yes");
	    } else {
                if($_SESSION['userinfo']['Radiology_Works'] == 'yes'){   
					@session_start();
                    if(!isset($_SESSION['Radiology_Supervisor'])){ 
                        header("Location: ./deptsupervisorauthentication.php?SessionCategory=Radiology&InvalidSupervisorAuthentication=yes");
                    }
				}
            }
	}else{
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	    header("Location: ../index.php?InvalidPrivilege=yes");
    }
	/* **************************** SESSION ********************************** */

	if(isset($_GET['Registration_ID'])){
		$Registration_ID = $_GET['Registration_ID'];
	} else {
		$Registration_ID = 0;
	}
	
	if(isset($_GET['PatientType'])){
		$PatientType = $_GET['PatientType'];
	} else {
		$PatientType = '';
	}
	
	isset($_GET['SI']) ? $Supervisor_ID = $_GET['SI'] : $Supervisor_ID = 0;
	isset($_GET['listtype']) ? $listtype = $_GET['listtype'] : $listtype = 0;
?>
<input type='button' name='patient_file' id='patient_file' value='PATIENT FILE' onclick='Show_Patient_File()' class='art-button-green'/>
                
<a href='PatientRadiology.php?RadiologyWorksPage=RadiologyWorksPageThisPage&PatientType=<?php echo $PatientType; ?>&listtype=<?php echo $listtype; ?>' class='art-button-green'> BACK </a>
<br><br>
<?php $Supervisor_ID = $_SESSION['Radiology_Supervisor']['Employee_ID']; ?>

<?php
$patient_details = mysqli_query($conn,"SELECT * FROM tbl_patient_registration pr
                    JOIN tbl_sponsor AS sp ON sp.Sponsor_ID=pr.Sponsor_ID
                    WHERE Registration_ID = '$Registration_ID'") or die (mysqli_error($conn));
    $rows_count = mysqli_num_rows($patient_details);
    if($rows_count > 0){
		while($row = mysqli_fetch_array($patient_details)){
			 $Patient_Name = $row['Patient_Name'];
			 $Gender = $row['Gender'];
			 $Date_Of_Birth = $row['Date_Of_Birth'];
			 $Sponsor_Name=$row['Guarantor_Name'];
			 //calculate age
			 //$Date_Of_Birth = '1984-08-04';
			$date1 = new DateTime(date('Y-m-d'));
			$date2 = new DateTime($Date_Of_Birth);
			$diff = $date1 -> diff($date2);
			$age = $diff->y." Years, ";
			$age.= $diff->m." Months and ";
			$age.= $diff->d." Days ";
		}
    }
?>
<div id="showdata" style="width:100%;overflow:hidden;display:none;">
		   <div id="my">
		   </div>
</div>
<div id="showdataComm" style="width:100%;overflow:hidden;display:none;">
		   <div id="myComm">
		   </div>
</div>
<center>
<strong><?php  echo $Patient_Name ."</strong>  | <strong> ". $Gender."</strong>  | <strong> ".$age."  |  ".$Sponsor_Name."</strong>"; ?> <br />
</center>
<fieldset >
	<legend align="right" style="background-color:#006400;color:white;padding:5px;"><b> RADIOLOGY PATIENT TESTS</b></legend>
	<center>
	
		<table  class="hiv_table" style="width:100%">
			<tr>
				<td id='Search_Iframe'>
				<?php
				isset($_GET['listtype']) ? $listtype = $_GET['listtype']: $listtype = '';
				if($listtype == 'FromDoc'){
				     $RPTI_src = "RadiologyPatientTests_Iframe_FromDoc.php";
				}elseif($listtype == 'FromDocConsul'){
				     $RPTI_src = "RadiologyPatientTests_Iframe_FromDoc_Consulted.php";
				} else {
				     $RPTI_src = "RadiologyPatientTests_Iframe.php";
				}
				
				  //echo $listtype;
				?>
				
				
			<div id="radPatTest" style="width:100%;height:400px;oveflow-y:scroll;overflow-x:hidden">
			  <?php include $RPTI_src;?>
			</div>
				
				</td> 
			</tr>
		</table>
		
	</center>
</fieldset>
<script>
 function closeDialog(){
   if(confirm('Are you sure you want to close?')){
     $("#showdataComm").dialog('close');
   }
 }
</script>
<script>
	function CloseImage(){
		document.getElementById('imgViewerImg').src = '';
		document.getElementById('imgViewer').style.visibility = 'hidden';
	}
	
	function zoomIn(imgId,inVal){
		if(inVal == 'in'){
			var zoomVal = 10;
		}else{
			var zoomVal = -10;
		}
		var Sizevalue = document.getElementById(imgId).style.width;
		Sizevalue = parseInt(Sizevalue)+zoomVal;
		document.getElementById(imgId).style.width = Sizevalue+'%';
	}
	
	
</script>

<script >
  function radiologyviewimage(href,itemName){
   var datastring=href;
	 //alert(datastring);
	 $("#showdata").dialog("option","title","PATIENT RADIOLOGY IMAGING ( "+itemName+" )");
    $.ajax({ 
        type:'GET',
        url:'requests/radiologyviewimage.php',
        data:datastring,
		 success:function(result){
		  //alert(result);
		  var data=result.split('<1$$##92>');
			  if(data[0] !=''){
				 alert(data[0]);
			  }
          $('#my').html(data[1]);
		  $("#showdata").dialog("open");
        },error:function(err,msg,errorThrows){
		   alert(err);
		}
    }); 
	
	
  }
</script>

<script >
  function commentsAndDescription(href,itemName){
   var datastring=href;
    $("#showdataComm").dialog("option","title","COMMENT AND DESCRIPTION ( "+itemName+" )");
	 //alert(datastring);
    $.ajax({
        type:'GET',
        url:'requests/RadiologyPatientTestsComments.php',
        data:datastring,
		success:function(result){
		  //alert(result);
		  
          $('#myComm').html(result);
		  $("#showdataComm").dialog("open");
        },error:function(err,msg,errorThrows){
		   alert(err);
		}
    }); 
	
	
  }
</script>
<script type="text/javascript">
    function readImage(input){
		if(input.files && input.files[0]) {
			var reader = new FileReader();
			reader.onload = function(e){
				$('#Patient_Picture').attr('src',e.target.result).width('30%').height('20%');
			};
			reader.readAsDataURL(input.files[0]);
		}
    }
    function clearPatientPicture() {
        document.getElementById('Patient_Picture_td').innerHTML="<img src='./patientImages/default.png' id='Patient_Picture' name='Patient_Picture' width=50% height=50%>"
    }
</script>
<script>
function SelectViewer(imgSrc){
	parent.document.getElementById('imgViewerImg').src = imgSrc;
	parent.document.getElementById('imgViewer').style.visibility = 'visible';
}
</script>
<script>
$(document).ready(function(){
   $("#showdata").dialog({ autoOpen: false, width:'90%', title:'PATIENT RADIOLOGY IMAGING',modal: true,position:'middle'});
   $("#showdataComm").dialog({ autoOpen: false, width:'90%', title:'COMMENT AND DESCRIPTION',modal: true,position:'middle'});
   $("#uploadImage").click(function(e){//
      
	  alert('Submiited');
   });
});
</script>
<script>
function UpdateStat(thestatus,Registration_ID,Item_ID,PPILI){
	//alert(thestatus);
	/* var PPILI ='<?php echo $Patient_Payment_Item_List_ID; ?>';
	var Item_ID = '<?php $Item_ID; ?>';
	var Registration_ID = '<?php  $Registration_ID; ?>'; */		
	//alert(thestatus+PPILI);
	//alert(PPILI+' '+Item_ID+' '+Registration_ID);
/*								
*/								
	if(window.XMLHttpRequest) {
		stat = new XMLHttpRequest();
	}
	else if(window.ActiveXObject){ 
		stat = new ActiveXObject('Microsoft.XMLHTTP');
		stat.overrideMimeType('text/xml');
	}
	stat.onreadystatechange = AJAXStat; 
	stat.open('GET','RadiologyPatientTestsResults_Status.php?Stat='+thestatus+'&PPILI='+PPILI+'&RI='+Registration_ID+'&II='+Item_ID,true);
	stat.send();

	function AJAXStat() {
		var respond = stat.responseText;
		var message = "<div style='background-color:#006400; color:yellow; z-index:1000;display:inline;width:auto;margin:0px; padding:5px;'>"+respond+"</div>";		
		document.getElementById('status_respond').innerHTML = message;	
	}	
}
</script>
<script>
    function Show_Patient_File() {
// var printWindow= window.open("http://www.w3schools.com", "_blank", "toolbar=yes, scrollbars=yes, resizable=yes, top=500, left=500, width=400, height=400");
        var winClose = popupwindow('Patientfile_Record_Detail_General.php?Section=Doctor&Registration_ID=<?php echo $_GET['Registration_ID']; ?>&PatientFile=PatientFileThisForm', 'Patient File', 1300, 700);
        //winClose.close();
        //openPrintWindow('http://www.google.com', 'windowTitle', 'width=820,height=600');

    }

    function popupwindow(url, title, w, h) {
        var wLeft = window.screenLeft ? window.screenLeft : window.screenX;
        var wTop = window.screenTop ? window.screenTop : window.screenY;

        var left = wLeft + (window.innerWidth / 2) - (w / 2);
        var top = wTop + (window.innerHeight / 2) - (h / 2);
        var mypopupWindow = window.showModalDialog(url, title, 'dialogWidth:' + w + '; dialogHeight:' + h + '; center:yes;dialogTop:' + top + '; dialogLeft:' + left);//'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=no, copyhistory=no, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);

        return mypopupWindow;
    }

</script>


<br/>
<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>
<script src="js/jquery.form.js"></script>
<?php
include("./includes/footer.php");
?>
