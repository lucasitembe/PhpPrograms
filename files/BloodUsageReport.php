<?php
    include("./includes/header.php");
    include("./includes/connection.php");
	
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

<a href='BloodBankReports.php?Appointments=AppointmentsThisPage' class='art-button-green'>
        BACK
    </a>
	<br>
	<br>

<?php
    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
        
        $age = $Today - $original_Date; 
    }
?>

<?php

//    select patient information to perform check in process
    if(isset($_GET['Blood_Checked_ID'])){
        $Blood_Checked_ID = $_GET['Blood_Checked_ID']; 
        $select_Blood_Used = mysqli_query($conn,"select
                            Blood_Checked_ID,Blood_Group, BloodRecorded,Blood_Status,Patient_Given,Reason,Date_Taken,Registered_Date_And_Time
                                      
                                      from tbl_blood_checked
                                        where Blood_Checked_ID = '$Blood_Checked_ID'") or die(mysqli_error($conn));
        $no = mysqli_num_rows($select_Blood_Used);
        
        if($no>0){
            while($row = mysqli_fetch_array($select_Blood_Used)){
                $Blood_Checked_ID = $row['Blood_Checked_ID'];
                $Blood_Group = $row['Blood_Group'];
                $BloodRecorded=$row['BloodRecorded'];
				$Blood_Status=$row['Blood_Status'];
                $Patient_Given = $row['Patient_Given'];
                $Blood_Expire_Date = $row['Blood_Expire_Date'];
                $Reason = $row['Reason'];
                $Date_Taken = $row['Date_Taken'];
				$Registered_Date_And_Time = $row['Registered_Date_And_Time'];
               // echo $Ward."  ".$District."  ".$Ward; exit;
            }
            $age = floor( (strtotime(date('Y-m-d')) - strtotime($Date_Of_Birth)) / 31556926)." Years";
        }else{
               $Blood_Checked_ID = '';
                $Blood_Group = '';
                $Blood_Used = '';
				$Blood_Disposed = '';
                $Patient_Given = '';
                $Blood_Expire_Date = '';
                $Reason = '';
                $Date_Taken = '';
				$Registered_Date_And_Time = '';       
        }
    }else{
                $Blood_Checked_ID = '';
                $Blood_Group = '';
                $Blood_Used = '';
				$Blood_Disposed = '';
                $Patient_Given = '';
                $Blood_Expire_Date = '';
                $Reason = '';
                $Date_Taken = '';
				$Registered_Date_And_Time = ''; 
        }
?>
<script language="javascript" type="text/javascript">
    function searchBloodGroup(){
	var Blood_Group=document.getElementById("Blood_Group").value;
	var Blood_Status=document.getElementById("Blood_Status").value;
	
	
	document.getElementById('Search_Iframe').src = "Blood_Usage_Report_Iframe.php?Blood_Group="+encodeURIComponent(Blood_Group)+"&Blood_Status="+Blood_Status;
    }
	
	
</script>



<form action='#' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">
    <center>
	   <table width='40%'>
	   
	  <tr>
	  <td style="text-align: center;"><b>Blood Status</b></td>
	  <td style="text-align: center;"><b>Blood Group</b></td>
</tr>	  
            <tr>
			<td style="text-align: center;">
			<select name='Blood_Status' id='Blood_Status' required='required' onchange='searchBloodGroup()'>
			<option selected='selected'></option> 
			<option value='USED'>Blood Used</option>
			<option value='DISPOSED'>Blood Expired</option>
			</select>
			</td>
			
			
	            
                                  <td style="text-align: center;"> 
								   <select name='Blood_Group' id='Blood_Group' required='required' onchange='searchBloodGroup()'>
							       <option selected='selected'></option> 
			                       <option value='A+'>A+</option>
							       <option value='A-'>A-</option>
							       <option  value='B+'>B+</option>
							       <option  value='B-'>B-</option>
							       <option value='O+' >O+</option> 
							       <option value='O-' >O-</option>
							       <option  value='AB+'>AB</option>
							       <option  value='AB-'>AB-</option>
						           </select>
					             </td>
								 
					    
						   
			           
					   <td style="text-align: center;">
					              
							<script>
								function Print_Pdf(){
									var Blood_Group = document.getElementById("Blood_Group").value;
                                    var Blood_Status = document.getElementById("Blood_Status").value; 
									var Temp = ''; 
									if(Blood_Group != null && Blood_Group != '' && Blood_Status != null && Blood_Status != ''){
										window.open("usageprint.php?Blood_Group="+encodeURIComponent(Blood_Group)+"&Blood_Status="+Blood_Status+"&BloodGroupReport=BloodGroupReportThisPage","_Blank");
										
									}else if((Blood_Group == '' || Blood_Group == null) && Blood_Status == 'USED'){ 
										window.open("usageprint.php?Blood_Status="+Blood_Status+"&BloodGroupReport=BloodGroupReportThisPage","_Blank");
									}else if((Blood_Group == '' || Blood_Group == null) && Blood_Status == 'DISPOSED'){ 
										window.open("usageprint.php?Blood_Status="+Blood_Status+"&BloodGroupReport=BloodGroupReportThisPage","_Blank");
									}else{
										alert("Select Blood Group First");
									}
								}
							</script>
								  <input type='button' name='Print' id='Print' value='PRINT' class='art-button-green' onclick="Print_Pdf()">
					</td>
							
			         
								   
		    </tr>
			
               
        
        </table>
    
</center>
</form>
<br>

<fieldset>  
            
        <center>
            <table width=100% >
                <tr>
            <td >
		<iframe id='Search_Iframe' width='100%' height=320px src='Blood_Usage_Report_Iframe.php?Patient_Name="+Patient_Name+"'></iframe>
            </td>
        </tr>
            </table>
        </center>
</fieldset><br/>



<link rel="stylesheet" href="css/smoothness/jquery-ui-1.10.1.custom.min.css" />
				<script src="js/jquery-1.9.1.js"></script>
				<script src="js/jquery-ui-1.10.1.custom.min.js"></script>
				<script>
					$(function () { 
						$("#Date_Choosen").datepicker({ 
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





























<?php
    include("./includes/footer.php");
?>