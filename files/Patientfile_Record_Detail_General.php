
<?php 
    include("./includes/header_general.php");
    include("./includes/connection.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	die( "<style>.art-content{background-color: #FFFFFF;}</style><p style='color:red;text-align:center;font-family:widen latin;font-size:40px;margin-bottom:200px'>You don't have access to this page yet.Try login again or contact administrator for support!<p>");

    }
    if(isset($_SESSION['userinfo'])){
       if(isset($_SESSION['userinfo']['Patient_Record_Works'])){
	    if($_SESSION['userinfo']['Patient_Record_Works'] != 'yes'){
		die( "<style>.art-content{background-color: #FFFFFF;}</style><p style='color:red;text-align:center;font-family:widen latin;font-size:40px;margin-bottom:200px'>You don't have access to this resource.Please contact administrator for support!<p>");
	    }
	}else{
	    	die( "<style>.art-content{background-color: #FFFFFF;}</style><p style='color:red;text-align:center;font-family:widen latin;font-size:40px;margin-bottom:200px'>You don't have access to this page yet.Try login again or contact administrator for support!<p>");

	}
      
    }else{
	@session_destroy();
	   	die( "<style>.art-content{background-color: #FFFFFF;}</style><p style='color:red;text-align:center;font-family:widen latin;font-size:40px;margin-bottom:200px'>You don't have access to this page yet.Try login again or contact administrator for support!<p>");

    }
    
    //get section for back buttons
    if(isset($_GET['section'])){
	$section = $_GET['section'];
    }else{
	$section = '';
    }
    
    $Registration_ID=$_GET['Registration_ID'];
    //$Patient_Payment_ID=$_GET['Patient_Payment_ID'];
    //@$Patient_Payment_Item_List_ID=$_GET['Patient_Payment_Item_List_ID'];

?>

<a href="patientfile_scroll_general.php?<?php echo $_SERVER['QUERY_STRING']?>" class="art-button-green">PATIENT FILE SCROLL VIEW</a>
<a href="previouspatientfile.php?Registration_ID=<?php echo $Registration_ID; ?>&consultation_ID=<?php echo $consultation_ID; ?>&Patient_Payment_ID=<?php echo $Temp_Patient_Payment_ID; ?>&Patient_Payment_Item_List_ID=<?php echo $Temp_Patient_Payment_Item_List_ID; ?>&PreviousPatientFile=PreviousPatientFileThisPage" class="art-button-green">PREVIOUS PATIENT FILE</a>

<?php
//get the current date
		$Today_Date = mysqli_query($conn,"select now() as today");
		while($row = mysqli_fetch_array($Today_Date)){
		    $original_Date = $row['today'];
		    $new_Date = date("Y-m-d", strtotime($original_Date));
		    $Today = $new_Date;
		}
//    select patient information
    if(isset($_GET['Registration_ID'])){
        $Registration_ID = $_GET['Registration_ID'];
        $select_Patient = mysqli_query($conn,"select
                            Old_Registration_Number,Title,Patient_Name,pr.Sponsor_ID,Date_Of_Birth,
                                    Gender,pr.Region,pr.District,pr.Ward,
                                        Member_Number,Member_Card_Expire_Date,
                                            pr.Phone_Number,Email_Address,Occupation,
                                                Employee_Vote_Number,Emergence_Contact_Name,
                                                    Emergence_Contact_Number,Company,Registration_ID,
                                                        Employee_ID,Registration_Date_And_Time,Guarantor_Name,Claim_Number_Status,
                                                        Registration_ID
                                      from tbl_patient_registration pr, tbl_sponsor sp
                                        where pr.Sponsor_ID = sp.Sponsor_ID and 
                                        Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
        $no = mysqli_num_rows($select_Patient);
        if($no>0){
            while($row = mysqli_fetch_array($select_Patient)){
                $Registration_ID = $row['Registration_ID'];
                $Old_Registration_Number = $row['Old_Registration_Number'];
                $Title = $row['Title'];
                $Patient_Name = $row['Patient_Name'];
                $Sponsor_ID = $row['Sponsor_ID'];
                $Date_Of_Birth = $row['Date_Of_Birth'];
                $Gender = $row['Gender'];
                $Region = $row['Region'];
                $District = $row['District'];
                $Ward = $row['Ward'];
                $Guarantor_Name = $row['Guarantor_Name'];
		$Claim_Number_Status = $row['Claim_Number_Status'];
                $Member_Number = $row['Member_Number'];
                $Member_Card_Expire_Date = $row['Member_Card_Expire_Date'];
                $Phone_Number = $row['Phone_Number'];
                $Email_Address = $row['Email_Address'];
                $Occupation = $row['Occupation'];
                $Employee_Vote_Number = $row['Employee_Vote_Number'];
                $Emergence_Contact_Name = $row['Emergence_Contact_Name'];
                $Emergence_Contact_Number = $row['Emergence_Contact_Number'];
                $Company = $row['Company'];
                $Employee_ID = $row['Employee_ID'];
                $Registration_Date_And_Time = $row['Registration_Date_And_Time'];
               // echo $Ward."  ".$District."  ".$Ward; exit;
            }
	    
		$date1 = new DateTime($Today);
		$date2 = new DateTime($Date_Of_Birth);
		$diff = $date1 -> diff($date2);
		$age = $diff->y." Years, ".$diff->m." Months, ".$diff->d." Days, ".$diff->h." Hours";
        }else{
            $Registration_ID = '';
            $Old_Registration_Number = '';
            $Title = '';
            $Patient_Name = '';
            $Sponsor_ID = '';
            $Date_Of_Birth = '';
            $Gender = '';
            $Region = '';
            $District = '';
            $Ward = '';
            $Guarantor_Name = '';
            $Claim_Number_Status = '';
	    $Member_Number = '';
            $Member_Card_Expire_Date = '';
            $Phone_Number = '';
            $Email_Address = '';
            $Occupation = '';
            $Employee_Vote_Number = '';
            $Emergence_Contact_Name = '';
            $Emergence_Contact_Number = '';
            $Company = '';
            $Employee_ID = '';
            $Registration_Date_And_Time = '';
	    $age =0;
        }
    }else{
            $Registration_ID = '';
            $Old_Registration_Number = '';
            $Title = '';
            $Sponsor_ID = '';
            $Date_Of_Birth = '';
            $Gender = '';
            $Region = '';
            $District = '';
            $Ward = '';
            $Guarantor_Name = '';
	    $Claim_Number_Status = '';
            $Member_Number = '';
            $Member_Card_Expire_Date = '';
            $Phone_Number = '';
            $Email_Address = '';
            $Occupation = '';
            $Employee_Vote_Number = '';
            $Emergence_Contact_Name = '';
            $Emergence_Contact_Number = '';
            $Company = '';
            $Employee_ID = '';
            $Registration_Date_And_Time = '';
	    $age =0;
        }
?>
<style>
	table,tr,td{
    border:none !important;	
	 border-collapse:collapse !important;
	}
	tr:hover{
	background-color:#eeeeee;
	cursor:pointer;
	}
</style>

<br/><br/>
<!-- get employee id-->
<?php
    if(isset($_SESSION['userinfo']['Employee_ID'])){
	$Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    }
?>
<!-- get id, date, Billing Type,Folio number and type of chech in -->
<?php
    if(isset($_GET['Registration_ID']) && isset($_GET['Patient_Payment_ID'])){
	//select the current Patient_Payment_ID to use as a foreign key
	
	$qr = "select * from tbl_patient_payments pp
					    where pp.Patient_Payment_ID = ".$_GET['Patient_Payment_ID']."
					    and pp.registration_id = '$Registration_ID'";
	$sql_Select_Current_Patient = mysqli_query($conn,$qr);
		$row = @mysqli_fetch_array($sql_Select_Current_Patient);
		$Patient_Payment_ID = $row['Patient_Payment_ID'];
		$Payment_Date_And_Time = $row['Payment_Date_And_Time'];
		//$Check_In_Type = $row['Check_In_Type'];
		$Folio_Number = $row['Folio_Number'];
		$Claim_Form_Number = $row['Claim_Form_Number'];
		$Billing_Type = $row['Billing_Type'];
		//$Patient_Direction = $row['Patient_Direction'];
		//$Consultant = $row['Consultant'];
	    }else{
		$Patient_Payment_ID = '';
		$Payment_Date_And_Time = '';
		//$Check_In_Type = $row['Check_In_Type'];
		$Folio_Number = '';
		$Claim_Form_Number = '';
		$Billing_Type = '';
		//$Patient_Direction = '';
		//$Consultant ='';
	    }
?>
<!--Getting employee name -->
<?php
    if(isset($_SESSION['userinfo']['Employee_Name'])){
	$Employee_Name = $_SESSION['userinfo']['Employee_Name'];
    }else{
	$Employee_Name = 'Unknown Employee';
    }
?>

<script type='text/javascript'>
    function access_Denied(){ 
   alert("Access Denied");
   document.location = "./index.php";
    }
</script>

<div id="labResults" style="display: none">
    <div id="showLabResultsHere"></div>
    
</div>


<div id="labGeneral" style="display: none">
    <div id="showGeneral"></div>
    
</div>
<div id="historyResults1" style="display:none">
    
</div>

<div id="showdata" style="width:100%;overflow-x:hidden;display:none;overflow-y:scroll">
		   <div id="my">
		   </div>
</div>
<div id="showdataComm" style="width:100%;overflow-x:hidden;height:520px;display:none;overflow-y:scroll">
		   <div id="myComm">
		   </div>
</div>
<div id="showdataResult" style="width:100%;overflow:hidden;display:none;background-color:white; border:none">
		   <div id="myRs">
		    <table border="0" cellpadding="0" cellspacing="0" width="100%">
			  <tr>
			    <td style='text-align:center'>
				 <select onchange="consultResult(this.value)" id="consType" style="padding:5px;margin:5px;font-size:18px;font-weight:100">
				  <option>SELECT CONSULTATION TYPE</option>
				  <option>Pharmacy</option>
				  <option>Laboratory</option>
				  <option>Radiology</option>
				  <option>Surgery</option>
				  <option>Procedure</option>
				  <option>Others</option>
				 <select>
				</td>
			  </tr>
			  <tr>
			    <td >
				 <div id="myDiv" style="width:100%;text-align:center;overflow-x:hidden;height:400px;overflow-y:scroll">
				 
				 </div>
				
				</td>
			  </tr>
			</table>
		   </div>
        </div>
<fieldset style='background: #006400 !important;color: white'>
        <center>
		<legend align="right" style="background-color:silver;color:black;padding:5px;">PATIENT RECORDS</legend>
		
	    <!--<b>PATIENT RECORDS</b>-->
            <!--<p>Patient File</p>-->
	    <p><?php echo ucwords(strtolower($Patient_Name)).", ".$Gender.", ".$Guarantor_Name.", (".$age.")";?></p>
        </center>
</fieldset>
<fieldset style='background: white; color:black'>
    <div id="radPatTest" style="width:100%;height:400px;oveflow-y:scroll;overflow-x:hidden">
		<?php include 'Patientfile_Record_Detail_Iframe_General.php';?>
	</div>
</fieldset>
<!--END HERE-->
<script>
  function consultResult(consultType,href,consultedDate,Registration_ID){
    //alert(consultType+' '+href+' '+consultedDate+' '+Registration_ID);
	 var datastring=href+'&consultedDate='+consultedDate+'&consultType='+    consultType;
    
     $.ajax({
        type:'GET',
        url:'requests/PatientDetailsResults.php',
        data:datastring,
		success:function(result){
		  //alert(result);
		  
          $("#myDiv").html(result);
        },error:function(err,msg,errorThrows){
		   alert(err);
		}
     }); 
	
  }
</script>
<script >
  function parentResult(href,PatientName,consultedDate,Registration_ID){
    //alert(href+' '+PatientName+' '+consultedDate);
	$('#consType').attr("onchange","consultResult(this.value,'"+href+"','"+consultedDate+"','"+Registration_ID+"')");
	$("#myDiv").html('');
	$("#showdataResult").dialog("option","title","PATIENT RESULT ( "+PatientName+" ) | Date:"+consultedDate);
	$("#showdataResult").dialog("open");
  }
</script>
<script>
$(document).ready(function(){
   $("#showdata").dialog({ autoOpen: false, width:'90%', title:'PATIENT RADIOLOGY IMAGING',modal: true,position:'middle'});
   $("#showdataResult").dialog({ autoOpen: false, width:'98%',height:500, title:'PATIENT RESULT',modal: true,position:'middle'});
   $("#showdataComm").dialog({ autoOpen: false, width:'90%',height:650, title:'COMMENT AND DESCRIPTION',modal: true,position:'center'});
   $('.fancybox').fancybox();
   
});
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
<script>
function uploadImages(){
	$('#radimagingform').ajaxSubmit({
         beforeSubmit: function() {
           //alert('submiting');
        },
		 success: function(result) {
		      // alert(result);
                 var data=result.split('<1$$##92>');
				  if(data[0] !=''){
					 alert(data[0]);
				  }
				 // alert(data[1]);
			      $('#my').html(data[1]); 
          
        }
        
        });
	return false;
}
</script>
<script >
  function radiologyviewimage(href,itemName){
   var datastring=href;
	 //alert(datastring);
	 $("#showdata").dialog("option","title","PATIENT RADIOLOGY IMAGING ( "+itemName+" )");
    $.ajax({ 
        type:'GET',
        url:'requests/radiologyviewimage_doctor.php',
        data:datastring,
		 success:function(result){
		  $('#my').html(result);
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
        url:'requests/RadiologyPatientTestsComments_doctor.php',
        data:datastring,
		success:function(result){
		  //alert(result);
		  $("#myComm").html(result);
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
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
<link rel="stylesheet" href="js/fancybox/jquery.fancybox.css" type="text/css" media="screen" />
<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>
<script type="text/javascript" src="js/fancybox/jquery.fancybox.pack.js"></script>
	
	<script>
        $(document).ready(function() {
            $('.fancybox').fancybox();
        });
    </script>
<?php
   // include("./includes/footer.php");
?>

   