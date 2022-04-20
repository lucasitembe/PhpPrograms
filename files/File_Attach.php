<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    include("allFunctions.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Patient_Record_Works'])){
	    if($_SESSION['userinfo']['Patient_Record_Works'] != 'yes'){
		header("Location: ./index.php?InvalidPrivilege=yes");
	    }
	}else{
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	    header("Location: ../index.php?InvalidPrivilege=yes");
    }
    //$Patient_Payment_ID=$_GET['Patient_Payment_ID'];
    //$Patient_Payment_Item_List_ID=$_GET['Patient_Payment_Item_List_ID'];
?>
<!--START HERE-->
<?php

    if(isset($_GET['Registration_ID'])){
        $Registration_ID = $_GET['Registration_ID'];
    }
    $PtDetails = json_decode(getPatientDetails($Registration_ID), true); 
    $Registration_ID = $_GET['Registration_ID'];
    $Date_Of_Birth= $PtDetails[0]['Date_Of_Birth'];
    $age =getCurrentPatientAge($PtDetails[0]['Date_Of_Birth']);

		if(isset($_GET['Section']) && $_GET['Section'] == 'Doctor'){
			echo "<a href='Patientfile_Record_Detail.php?Section=Doctor&Registration_ID=".$_GET['Registration_ID']."&Patient_Payment_ID=".$_GET['Patient_Payment_ID']."&Patient_Payment_Item_List_ID=".$_GET['Patient_Payment_Item_List_ID']."&NR=true&PatientBilling=PatientBillingThisForm' class='art-button-green'>BACK</a>";
		}elseif(isset($_GET['Section']) && $_GET['Section'] == 'DoctorLab'){
			echo "<a href='Patientfile_Record_Detail.php?Section=DoctorLab&Registration_ID=".$_GET['Registration_ID']."&Patient_Payment_ID=".$_GET['Patient_Payment_ID']."&Patient_Payment_Item_List_ID=".$_GET['Patient_Payment_Item_List_ID']."' class='art-button-green'>BACK</a>";
		}elseif(isset($_GET['Section']) && $_GET['Section'] == 'DoctorRad'){
			echo "<a href='Patientfile_Record_Detail.php?Section=DoctorRad&Registration_ID=".$_GET['Registration_ID']."&Patient_Payment_ID=".$_GET['Patient_Payment_ID']."&Patient_Payment_Item_List_ID=".$_GET['Patient_Payment_Item_List_ID']."&PatientType=&listtype=FromRec&Doctor=yes' class='art-button-green'>BACK</a>";
		}elseif (isset($_GET['Section']) && $_GET['Section'] == 'ManagementPatient') {
                    echo "<a href='Patientfile_Record_Detail.php?Section=ManagementPatient&consultation_ID=".$row['consultation_ID']."&Registration_ID=".$_GET['Registration_ID']."&Employee_ID=".$_GET['Employee_ID']."&Date_From=".$_GET['Date_From']."&Date_To=".$_GET['Date_To']."&DoctorsPerformanceDetailThisPage=DoctorsPerformanceDetailsThisPage' class='art-button-green'>BACK</a>";
                }else{
                                 if(isset($_GET['fromPatientFile'])){
            if($_GET['fromPatientFile']=='true'){
                
                echo "<a href='Patientfile_Record_Detail.php?Registration_ID=".$_GET['Registration_ID']."&PatientFile=PatientFileThisForm&fromPatientFile=true' class='art-button-green'>BACK</a>";
//              echo "<a href='Patientfile_Record_Detail.php?section=Patient&PatientFileRecordThisPage=ThisPage' class='art-button-green'>BACK</a>";
//  
            }
            
        }elseif (isset ($_GET['Patient_Payment_ID']) && ($_GET['Patient_Payment_Item_List_ID'])) {
           if($_GET['position']=='out'){
             echo "<a href='Patientfile_Record_Detail.php?Registration_ID=".$_GET['Registration_ID']."&Patient_Payment_ID=".$_GET['Patient_Payment_ID']."&Patient_Payment_Item_List_ID=".$_GET['Patient_Payment_Item_List_ID']."&SearchListDoctorsPagePatientBilling=SearchListDirectCashPatientBillingThisPage&position=in' class='art-button-green'>BACK</a>";
       
            }elseif ($_GET['position']=='in') {
              echo "<a href='Patientfile_Record_Detail.php?Registration_ID=".$_GET['Registration_ID']."&Patient_Payment_ID=".$_GET['Patient_Payment_ID']."&Patient_Payment_Item_List_ID=".$_GET['Patient_Payment_Item_List_ID']."&SearchListDoctorsPagePatientBilling=SearchListDirectCashPatientBillingThisPage&position=in' class='art-button-green'>BACK</a>";
         
            }
            
        }elseif(isset ($_GET['Registration_ID']) && ($_GET['consultation_ID'])){
            if($_GET['position']=='out'){
                 echo "<a href='Patientfile_Record_Detail.php?Registration_ID=".$_GET['Registration_ID']."&consultation_ID=".$_GET['consultation_ID']."&position=in' class='art-button-green'>BACK</a>";
            }else if($_GET['position']=='in'){
                  echo "<a href='Patientfile_Record_Detail.php?Registration_ID=".$_GET['Registration_ID']."&consultation_ID=".$_GET['consultation_ID']."&item_ID=".$GET['item_ID']."&position=in' class='art-button-green'>BACK</a>";
  
            }
        
        }
                    
                    
			//echo "<a href='Patientfile_Record_Detail.php?Registration_ID=".$_GET['Registration_ID']."&Patient_Payment_ID=".$_GET['Patient_Payment_ID']."&Patient_Payment_Item_List_ID=".$_GET['Patient_Payment_Item_List_ID']."&PatientType=&listtype=FromRec&Doctor=yes' class='art-button-green'>BACK</a>";
			//echo "<a href='Patientfile_Record.php?PatientFileRecordThisPage=ThisPage' class='art-button-green'>BACK</a>";
	  }
?>
<br/><br/>
<!-- get employee id-->
<?php
    if(isset($_SESSION['userinfo']['Employee_ID'])){
	$Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    }
?>
<!-- get id, date, Billing Type,Folio number and type of chech in -->
<?php
$Patient_Payment_ID = $_GET['Patient_Payment_ID'];
    
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
<fieldset>
        <center>
	    <b>HOSPITAL PATIENT RECORDS</b>
            <p>Patient Attachments</p>
	    <p><?php echo $PtDetails[0]['Patient_Name'].", ".$PtDetails[0]['Gender'].", ".$PtDetails[0]['Guarantor_Name'].", (".$age.")";?></p>
        </center>
</fieldset>


<fieldset>
     <center>
   
   
    <form action='#' method='post' enctype="multipart/form-data">
        <table width='100%'>
            <tr>
                <td>
                <center>
                <table>
                    <tr>
                        <td>
                            Description :
                        </td>
                        <td>
                            <input type='text' id='description' name='description'/>
			    <input type='hidden' id='Registration_ID' name='Registration_ID' value='<?php echo $Registration_ID;?>'/>
                            <input type='hidden' id='Patient_Payment_ID' name='Patient_Payment_ID' value='<?php echo $Patient_Payment_ID;?>'/>
			    <input type='hidden' id='section' name='section' value='<?php echo $section;?>'/>
			    <!-- <input type='hidden' id='department_upload_submitted' name='department_upload_submitted' value='true'> -->
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Nature Of The File :
                        </td>
                        <td>
                            <select id='department' name='department' required='required'>
                                <option selected='selected'>
                                    <option>Radiology</option>
                                    <option>Dialysis</option>
                                    <option>Physiotherapy</option>
                                    <option>Optical</option>
                                    <option>Doctor Room</option>
                                    <option>Dressing</option>
                                    <option>Matenity</option>
                                    <option>Cecap</option>
                                    <option>Laboratory</option>
                                    <option>Theater</option>
                                    <option>Dental</option>
                                    <option>Ear</option>
                                    <option >Refferal Letter</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Attachment :
                        </td>
                        <td>
                            <input type='file' id='attachment' name='attachment'/>
                        </td>
                    </tr>
                    <tr>
                        <td colspan=2>
                            <input type='button'  value='UPLOAD' class='art-button-green' onclick="uploadattachmentfiles()"/>
                            
                        </td>
                    </tr>
		    
                </table>   
                    
                </center>    
                    
                </td>
            </tr>
        </table>
    </form>
    </center>
</fieldset>
<fieldset style="height:350px;margin:auto;  overflow-x:hidden;overflow-y:scroll;">
    <legend>ATTACHMENT UPLOADED</legend>
    <div id="imagesection" ></div>
</fieldset>

<script>
      function uploadattachmentfiles(){
        var file_input= $("#attachment").val();
        var fd = new FormData();
        var files = $('#attachment')[0].files[0];
        fd.append('file',files);
        
        $.ajax({
            type:'POST',
            url:'attachmentupload.php',
            data:fd,
            processData: false,
            contentType: false,
            success:function(responce){  
                if(responce=='Nothing')  {
                    alert("Failed To upload try again or contact System administrator");
                } else{          
                    saveImages(responce);
                }             
            }
        });
    }

    function saveImages(attachment){
        var description= $("#description").val();
        var department =$("#department").val();
        var Registration_ID='<?= $Registration_ID ?>'; 
        var Patient_Payment_ID='<?= $Patient_Payment_ID ?>'; 
        $.ajax({
            type:'POST',
            url:'Ajax_attachmentupload.php',
            data:{attachment:attachment, Registration_ID:Registration_ID,description:description,department:department, Patient_Payment_ID:Patient_Payment_ID, save_image:''},           
            success:function(responce){  
                load_image_attached(); 
                $("#attachment").val('');   
                $("#department").val('');
                $("#description").val('');   
            }
        });
    }

    function load_image_attached(){
        var Registration_ID='<?= $Registration_ID ?>'; 
        var Patient_Payment_ID='<?= $Patient_Payment_ID ?>'; 
        $.ajax({
            type:'POST',
            url:'Ajax_attachmentupload.php',
            data:{Registration_ID:Registration_ID, Patient_Payment_ID:Patient_Payment_ID, load_image:''},           
            success:function(responce){  
                $("#imagesection").html(responce);          
            }
        });
    }
    function removeImage(Attachment_ID){
        if(confirm("Are you sure you want to delete this attachment?")){
            $.ajax({
                type:'POST',
                url:'Ajax_attachmentupload.php',
                data:{Attachment_ID:Attachment_ID, remove_image:''},           
                success:function(responce){  
                    console.log(responce)
                    load_image_attached();            
                }
            });
        }
    }
    $(document).ready(function(){
        load_image_attached();
    })
</script>
<!--END HERE-->
<?php
    include("./includes/footer.php");
?>