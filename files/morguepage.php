<?php
    include("./includes/header.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Morgue_Works'])){
	    if($_SESSION['userinfo']['Morgue_Works'] != 'yes'){
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
<input type='button' name='patient_outpatient' id='patient_outpatient' value='DIRECT DEPT - OUTPATIENT' onclick='outpatient()' class='art-button hide' />
<input type='button' name='patient_inpatient' id='patient_inpatient' value='DIRECT DEPT - INPATIENT' onclick='inpatient()' class='art-button hide' />

<script type='text/javascript'>
    function access_Denied(){ 
   alert("Access Denied");
   document.location = "./index.php";
    }
</script>
<?php 
//COUNT DECEASED TO BE DISCHARGED
		$count_deceased = "SELECT COUNT(ma.Admision_ID) as deceased FROM tbl_mortuary_admission ma JOIN tbl_admission ad ON ma.Admision_ID=ad.Admision_ID WHERE ad.Discharge_Clearance_Status = 'cleared'  AND ad.Admission_Status != 'Discharged'";
		$counted_deceased = mysqli_query($conn,$count_deceased) or die(mysqli_error($conn));	
		while($deceasedcount = mysqli_fetch_assoc($counted_deceased)){
			$deceasedDischarge = $deceasedcount['deceased']; 
		}
		?>
<br/><br/><br/><br/><br/><br/>
<fieldset>  
            <legend align="center"><b>MORGUE WORKS</b></legend>
        <center><table width = 60%>
            <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if($_SESSION['userinfo']['Morgue_Works'] == 'yes'){ ?>
                    <a href='list_of_patient_cheked_in_n_from_inpatient.php'>
                        <button style='width: 100%; height: 100%'>
                            <?php 
                             $sql_select_patient="SELECT pr.Registration_ID,Patient_Name,pr.District as Hospital_Ward_Name,ci.Check_In_Date_And_Time as dis_check_time,emp.Employee_Name as dischareged_chckin_by FROM tbl_check_in ci INNER JOIN tbl_patient_registration pr ON ci.Registration_ID=pr.Registration_ID INNER JOIN tbl_employee emp ON ci.Employee_ID=emp.Employee_ID WHERE ci.Registration_ID NOT IN (SELECT Corpse_ID as Registration_ID FROM tbl_mortuary_admission) AND Diceased='yes' UNION SELECT pr.Registration_ID,Patient_Name,hw.Hospital_Ward_Name,ad.Discharge_Date_Time as dis_check_time,emp.Employee_Name as dischareged_chckin_by FROM tbl_patient_registration pr INNER JOIN tbl_admission ad ON pr.Registration_ID=ad.Registration_ID INNER JOIN tbl_discharge_reason dr ON ad.Discharge_Reason_ID=dr.Discharge_Reason_ID INNER JOIN tbl_hospital_ward hw ON ad.Hospital_Ward_ID=hw.Hospital_Ward_ID INNER JOIN tbl_employee emp ON ad.pending_setter=emp.Employee_ID WHERE (ad.Admission_Status='Discharged' OR ad.Admission_Status='pending') AND ward_type<>'mortuary_ward' AND ad.Registration_ID NOT IN (SELECT Corpse_ID as Registration_ID FROM tbl_mortuary_admission) AND discharge_condition='dead'";
                            $sql_select_patient_result=mysqli_query($conn,$sql_select_patient) or die(mysqli_error($conn));
                            $count_bodies=mysqli_num_rows($sql_select_patient_result);
                            ?>
                            Register Dead Body <span class="badge" style="background: red"><?= $count_bodies ?></span>
                        </button>
                    </a>
                    <?php }else{ ?>
                     
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Register Dead Body 
                        </button>
                    
                    <!--ocdeville-->
                  
                    <?php } ?>
                    <?php if($_SESSION['userinfo']['Morgue_Works'] == 'yes'){ ?>
                    <a href='list_of_patient_check_in_and_not_admitted_to_morgue.php' class='hide'>
                        <button style='width: 100%; height: 100%'>
                            <?php 
                             $sql_select_patient="SELECT pr.Registration_ID,Patient_Name,pr.District as Hospital_Ward_Name,ci.Check_In_Date_And_Time as dis_check_time,emp.Employee_Name as dischareged_chckin_by FROM tbl_check_in ci INNER JOIN tbl_patient_registration pr ON ci.Registration_ID=pr.Registration_ID INNER JOIN tbl_employee emp ON ci.Employee_ID=emp.Employee_ID WHERE ci.Registration_ID NOT IN (SELECT Corpse_ID as Registration_ID FROM tbl_mortuary_admission) AND Diceased='yes' UNION SELECT pr.Registration_ID,Patient_Name,hw.Hospital_Ward_Name,ad.Discharge_Date_Time as dis_check_time,emp.Employee_Name as dischareged_chckin_by FROM tbl_patient_registration pr INNER JOIN tbl_admission ad ON pr.Registration_ID=ad.Registration_ID INNER JOIN tbl_discharge_reason dr ON ad.Discharge_Reason_ID=dr.Discharge_Reason_ID INNER JOIN tbl_hospital_ward hw ON ad.Hospital_Ward_ID=hw.Hospital_Ward_ID INNER JOIN tbl_employee emp ON ad.pending_setter=emp.Employee_ID WHERE (ad.Admission_Status='Discharged' OR ad.Admission_Status='pending') AND ward_type<>'mortuary_ward' AND ad.Registration_ID NOT IN (SELECT Corpse_ID as Registration_ID FROM tbl_mortuary_admission) AND discharge_condition='dead'";
                            $sql_select_patient_result=mysqli_query($conn,$sql_select_patient) or die(mysqli_error($conn));
                            $count_bodies=mysqli_num_rows($sql_select_patient_result);
                            ?>
                            Verified body without Admitted to Mortuary<span class="badge" style="background: red"><?= $count_bodies ?></span>
                        </button>
                    </a>
                    <?php }else{ ?>
                     
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Verified body without Admitted to Mortuary
                        </button>
                    
                    <!--ocdeville-->
                  
                    <?php } ?>
                </td>
                </tr>
                <tr>
                    <td style='text-align: center; height: 40px; width: 33%;'>
                       <a href='motuary_admitted_list.php?section=<?php echo $section; ?>'>
                           <button style='width: 100%; height: 100%'>Add Mortuary Services</button>
                        </a>
                    </td>
                </tr>
                <tr>
                    <td style='text-align: center; height: 40px; width: 33%;'>
                       <a href='mortuaryforcedischarge.php?section=<?php echo $section; ?>'>
                           <button style='width: 100%; height: 100%'>Make Body In Discharge State</button>
                        </a>
                    </td>
                </tr>
                <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if(isset($_SESSION['userinfo'])){
                    if($_SESSION['userinfo']['Morgue_Works'] == 'yes'){ ?>
		    <a href='searchlistofmortuaryadmited.php?section=<?php echo $section;?>&ContinuePatientBilling=ContinuePatientBillingThisPage&from_morgue=yes'>
			<button style='width: 100%; height: 100%'>
                            List of Bodies Ready to be discharged&nbsp;&nbsp;<?php if($deceasedDischarge > 0){ ?><span style='background-color:red; padding:2px 5px 2px 5px; color:#fff; font-size:16px; border-radius:9px;'><?php echo $deceasedDischarge; ?></span><?php } ?>
                        </button>
                    </a>
		    <?php }}else{ ?>
                     
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            List of Bodies Ready to be discharged
                        </button>
                  
                    <?php } ?>
                </td>
            </tr>
            
				<tr>
				 <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if($_SESSION['userinfo']['Morgue_Works'] == 'yes'){ ?>
                    <a href='mortuary_report.php'>
                        <button style='width: 100%; height: 100%'>
                            Reports
                        </button>
                    </a>
                    <?php }else{ ?>
                     
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Reports 
                        </button>
                  
                    <?php } ?>
                </td>
				</tr>
                                <tr style="display: none">
				 <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if($_SESSION['userinfo']['Morgue_Works'] == 'yes'){ ?>
                    <a href='morgueName.php'>
                        <button style='width: 100%; height: 100%'>
                            Setup
                        </button>
                    </a>
                    <?php }else{ ?>
                     
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            setup 
                        </button>
                  
                    <?php } ?>
                </td>
				</tr>
				
				
		
            <tr>		
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
$(document).ready(function () {
    update_Morgue();
});
function update_Morgue(){
    Action = 'Update Morgue';
    $.ajax({
        type: "POST",
        url: "update_morgue_details.php",
        data: {
            Action:Action
        },
        cache: false,
        success: function (response) {
            
        }
    });
}

</script>
<?php
    include("./includes/footer.php");
?>