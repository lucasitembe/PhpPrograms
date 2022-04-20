<?php
  include("./includes/header.php");
  include("./includes/connection.php");

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
  

 //get all item details based on item id
    if(isset($_GET['RI'])){
	$Registration_ID = $_GET['RI'];
    }else{
	$Registration_ID = '';
    }
	if(isset($_GET['PPILI'])){
		$Patient_Payment_Item_List_ID = $_GET['PPILI'];
	}else{
		$Patient_Payment_Item_List_ID = '';
	}

	if(isset($_GET['PPI'])){
		$Patient_Payment_ID = $_GET['PPI'];
	}else{
		$Patient_Payment_ID = '';
	}
if(isset($_GET['Status_From'])){
	$Status_From = $_GET['Status_From'];
}else{
	$Status_From = '';
}
if(isset($_GET['II'])){
	$Item_ID = $_GET['II'];
}else{
	$Item_ID = '';
}

$data='';

isset($_GET['listtype']) ? $listtype = $_GET['listtype'] : $listtype = '';
isset($_GET['PatientType']) ? $PatientType = $_GET['PatientType'] : $PatientType = '';

    echo "<a href='RadiologyPatientTests.php?Registration_ID=".$Registration_ID."&listtype=".$listtype."&PatientType=".$PatientType."' class='art-button-green'>BACK</a>";

$data="<table id='imgViewer' style='background: rgba(0, 0, 0, .8);position:fixed;z-index: 700;visibility:hidden;height:100%'>
	<tr>
		<td width='80%'>
		<legend style='color:white;text-align:center;'><b>PATIENT IMAGE</b></legend>
			<fieldset style='height:500px;width:90%;overflow:scroll;resize:none;'>
			
				<center>
					<img id='imgViewerImg' style='width:70%;' onclick='zoomIn(\'imgViewerImg\',\'in\')'  src=''/>
				</center>
				
			</fieldset>
			<div style='position:absolute;right:30px;top:30px;color:white;background-color:white;border-radius:2px;opacity:.9;'>
				<center>
					<p onclick='CloseImage()' style='cursor:pointer'>&nbsp;<img src='./images/close.png' width='20px'/></p>
					<p >&nbsp;&nbsp;<img onclick='zoomIn(\'imgViewerImg\',\'in\')' style='cursor:pointer'  src='./images/zoomin.png' width='20px'/></p>
					<p >&nbsp;&nbsp;<img onclick='zoomIn(\'imgViewerImg\',\'out\')'  style='cursor:pointer' src='./images/zoomout.png' width='20px'/></p>
				</center>
			
			</div>
		</td>
		<td style='text-align:center;'>
		<br><br><br><br><br><br><h4 style='color:white;'><b>select other images</b></h4><br>
		<iframe width='60%' height='80%'  src='radiologyimageviewDBS_framVertical.php?Registration_ID=".$Registration_ID."'></iframe>
		</td>
		
	</tr>
	
</table>";
?>
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
 

 <?php
    //get all item details based on item id
    if(isset($_GET['RI'])){
	$Registration_ID = $_GET['RI'];
    }else{
	$Registration_ID = '';
    }
    
?>
<?php
//connection scripts to database

	if(isset($_POST['submitted'])){
				if($_FILES['Radiology_Image']['name'] != '' && $_FILES['Radiology_Image']['name']!=null && !empty($_FILES['Radiology_Image']['name'])){
					error_reporting(E_ERROR | E_PARSE);
					$Registration_ID=$_GET['RI'];
					$path = $_POST['Radiology_Image'];
					$target = "RadiologyImage/";
					$Upload_Date =date('Y-m-d H:i:s');
					$target = $target . basename($_FILES['Radiology_Image']['name']);
					$Patient_Payment_Item_List_ID=$_GET['PPILI'];
					$Patient_Payment_ID=$_GET['PPI'];

					if(move_uploaded_file($_FILES['Radiology_Image']['tmp_name'], $target)){
						$sql = "
							INSERT INTO tbl_radiology_image(
								Registration_ID,
								Item_ID,
								Radiology_Image,
								Patient_Payment_Item_List_ID,
								Upload_Date) 
									VALUES(
									'$Registration_ID',
									'$Item_ID',
									'$target',
									'$Patient_Payment_Item_List_ID',
									'$Upload_Date')";
						$result = mysqli_query($conn,$sql) or die(mysqli_error($conn));
						if($result){?>
							<script>
								alert("Image Successfully Uploaded.");
							</script>
						<?php }else{?>
							<script>
								alert("Failed To Upload Image,Try again or contact the system admin");
							</script>
						<?php }
					}else{?>
						<script>
							alert("Failed to move the image to the specified directory.");
						</script>
					<?php }
				}else{?>
					<script>
						alert("No image selected for upload,Please choose one to proceed.");
					</script>
				<?php }
	}
?>
  
<?php 
	//Getting the Item Name
	$select_item = "SELECT Product_Name FROM tbl_items WHERE Item_ID = '$Item_ID'";
	$select_item_qry = mysqli_query($conn,$select_item) or die(mysqli_error($conn));
	while($theitem = mysqli_fetch_assoc($select_item_qry)){
		$item_name = $theitem['Product_Name'];
	}
?>  
 

<?php
$patient_details = mysqli_query($conn,"SELECT * FROM tbl_patient_registration WHERE Registration_ID = '$Registration_ID'") or die (mysqli_error($conn));
    $rows_count = mysqli_num_rows($patient_details);
    if($rows_count > 0){
		while($row = mysqli_fetch_array($patient_details)){
			 $Patient_Name = $row['Patient_Name'];
			 $Gender = $row['Gender'];
			 $Date_Of_Birth = $row['Date_Of_Birth'];
			 
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

  
 $data.=' <br><br>		
	<fieldset>

    <legend align="right" style="background-color:#006400;color:white;padding:5px;"><b>PATIENT RADIOLOGY IMAGING</b></legend>
    <center>
	<td>
	<table width="90%" height="80%">
		

		<form  action="#" method="POST" enctype="multipart/form-data">
		<table  border="0" align="center">
			<tr>
				<td><input type="file" name="Radiology_Image"  ></td>
				<td>
				<input type="submit" name="submit" value="UPLOAD" class="art-button-green">
				<input type="hidden" name="submitted"/>
				</td> 
				<td><input type="Reset" name="reset" value="CANCEL" class="art-button-green"></td>
			</tr>
		</table>
		
		</form>
		<table width="90%" align="center">
			<tr>
				<td id="Search_Iframe">';
				?>
				   <div style="width:100%; height:250px;oveflow-y:scroll;overflow-x:hidden">
				     <?php include 'radiologyimageviewDBS_iframe.php'?>
				   </div>
					<!-- <iframe width="100%" height="250px" frameborder="0" src="radiologyimageviewDBS_iframe.php?RI=<?php echo $Registration_ID;?>&Status_From=<?php echo $Status_From?>&II=<?php echo $Item_ID?>&PPI=<?php echo $Patient_Payment_ID?>&PPILI=<?php echo $Patient_Payment_Item_List_ID?>"></iframe>-->
			<?php		 
			$data.='	</td>
			</tr>
		</table>

		<tr >
			<td colspan=4 style="text-align:center;">
				<!-- Radiologyverryfyimagepage -->
				<a href="RadiologyPatientTestsComments.php?Status_From=<?php echo $Status_From;?>&II=<?php echo $Item_ID;?>&PPILI=<?php echo $Patient_Payment_Item_List_ID;?>&PPI=<?php echo $Patient_Payment_ID;?>&RI=<?php echo $Registration_ID; ?>&PatientType=<?php echo $PatientType; ?>&listtype=<?php echo $listtype; ?>&RadiologyImagesThisPage=ThisPage" >
				<button class="art-button-green">COMMENTS AND DESCRIPTION</button></a> 									
			</td>
		</tr>
		</center>
	</table>
	</fieldset>';

    echo $data;
?>
<script type="text/javascript">
   //var options = { target: '#output'};
    $('#radimagingform').submit(function() {
        $(this).ajaxSubmit({
         beforeSubmit: function() {
           alert('submiting');
        },
		 success: function(result) {
                var data=result.split('<1$$##92>');
				  if(data[0] !=''){
					 alert(data[0]);
				  }
			      $('#my').html(data[1]);
          
        }
        
        });
        return false;
    });
</script>					