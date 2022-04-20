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

<?php
    $Today_Date = mysqli_query($conn,"select now() as today");
    while($row = mysqli_fetch_array($Today_Date)){
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d", strtotime($original_Date));
        $Today = $new_Date;
        
        $age = $Today - $original_Date; 
    }
?>

<a href='BloodBankReports.php?Appointments=AppointmentsThisPage' class='art-button-green'>
        BACK
    </a>
	
	<br>
	<br>

<?php

//    select patient information to perform check in process
    if(isset($_GET['Blood_ID'])){
        $Blood_ID = $_GET['Blood_ID']; 
        $select_Blood = mysqli_query($conn,"select
                            Blood_ID,Donor_ID,Blood_Group,Blood_Batch,
							Blood_Volume,Date_Of_Transfusion,Blood_Expire_Date,Transfusion_Date_Time
                                      
                                      from tbl_patient_blood_data 
                                        where Blood_ID = '$Blood_ID'") or die(mysqli_error($conn));
        $no = mysqli_num_rows($select_Blood);
        
        if($no>0){
            while($row = mysqli_fetch_array($select_Blood)){
                $Blood_ID = $row['Blood_ID'];
                $Donor_ID = $row['Donor_ID'];
                $Blood_Group = $row['Blood_Group'];
                $Blood_Volume = $row['Blood_Volume'];
                $Date_Of_Transfusion = $row['Date_Of_Transfusion'];
                $Blood_Expire_Date = $row['Blood_Expire_Date'];
                $Transfusion_Date_Time = $row['Transfusion_Date_Time'];
                
    
            }
            $age = floor( (strtotime(date('Y-m-d')) - strtotime($Date_Of_Birth)) / 31556926)." Years";
        }else{
                $Blood_ID = '';
                $Donor_ID = '';
                $Blood_Group = '';
                $Blood_Volume = '';
                $Date_Of_Transfusion = '';
                $Blood_Expire_Date = '';
                $Transfusion_Date_Time = '';           
        }
    }else{
           $Blood_ID = '';
                $Donor_ID = '';
                $Blood_Group = '';
                $Blood_Volume = '';
                $Date_Of_Transfusion = '';
                $Blood_Expire_Date = '';
                $Transfusion_Date_Time = '';
        }
?>
<script language="javascript" type="text/javascript">
    function searchBloodGroup(){

	 var Blood_Group=document.getElementById("Blood_Group").value;
	 var Date_Choosen=document.getElementById("Date_Choosen").value;
	
        
		document.getElementById('Search_Iframe').src="Blood_Register_Stock_Iframe.php?Blood_Group="+encodeURIComponent(Blood_Group)+"&Date_Choosen="+Date_Choosen;

    }
	
	
	
	
	</script>


<form action='#' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">
    <center>
	<table width='80%'>
        <tr>
	           <td style="text-align: right;width:50%"><b>Blood Group</b></td>
			   
               <td> <select name='Blood_Group' id='Blood_Group' required='required' onchange='searchBloodGroup()'>
					<option selected='selected'></option>
                      <option >A+</option>
					 <option >A-</option>
					 <option >B+</option>
					 <option >B-</option>
					 <option >O+</option> 
					 <option >O-</option>
				     <option>AB</option>
					 <option >AB-</option>
					 </select>
			</td>
			
			          <td style="text-align: right;"><b>Date</b></td>
                      <td style='width:40%'><input type='text' name='Date_Choosen' required='required' id='Date_Choosen' onchange='searchBloodGroup()' ></td>
								   
					 
					 <td style="text-align: right;">
					             
							<script>
								function Print_Pdf(){
									var Blood_Group = document.getElementById("Blood_Group").value;
									var Date_Choosen=document.getElementById("Date_Choosen").value;
                                    if(Blood_Group != null && Blood_Group != ''){
										window.open("RegisterPrint.php?Blood_Group="+encodeURIComponent(Blood_Group)+"&Date_Choosen="+Date_Choosen+"&BloodGroupReport=BloodGroupReportThisPage","_Blank");
										
										
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
<fieldset style='overflow-y: scroll;'>  
            
        <center>
            <table width=100% >
                <tr>
            <td  >
		<iframe id='Search_Iframe' width='100%'  height=320px src='Blood_Register_Stock_Iframe.php?Blood_Expire_Date='></iframe>
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
							
							dateFormat: "yy-mm-dd",
							
						
						
					});
				</script> 

<?php
    include("./includes/footer.php");
?>