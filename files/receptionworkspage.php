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
    
    $countreff=mysqli_query($conn,"SELECT COUNT(referr_id) AS total  FROM tbl_referral_patient WHERE status='active'") or die(mysqli_error($conn));
    $totalCount=  mysqli_fetch_array($countreff)['total'];
?>
<script type='text/javascript'>
    function access_Denied(){ 
        alert("Access Denied");
    }
</script>
<script type="text/javascript" src="js/afya_card.js"></script>
<input type="button" value="AFYA CARD" class="art-button-green" id="afya_card_btn" onclick="read_afya_card_infomation_and_process()"/>
<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Reception_Works'] == 'yes'){ 
?>
    <a href='./index.php?Reception=ReceptionThisPage' class='art-button-green'>
        BACK
    </a>
<?php  } } ?>
<br/><br/>
<fieldset>
    <legend align=center><b>RECEPTION WORKS</b></legend>
    <div class="row">
        <div class="col-sm-2"></div>
        <div class="col-sm-4">
            <table class="table">
                <tr>
                    <td>
                        <a href='searchvisitorsoutpatientlist.php?VisitorForm=VisitorFormThisPage'>
                            <button style='width: 100%; height: 100%'>
                                Registration / Visitors
                            </button>
                        </a>
                    </td>
                </tr>
                <!-- <tr>
                    <td>
                         <a href='prepaidpatientslist.php?Section=Reception&PrePaidPatientsList=PrePaidPatientsListThisPage'>
                            <button style='width: 100%; height: 100%'>
                                Pre / Post Paid Patients
                            </button>
                        </a>
                    </td>
                </tr> -->
                <tr>
                    <td style='text-align: center; height: 40px; width: 33%;'>
                            <a href='searchappointmentPatient.php?section=Admission&AdmisionWorks=AdmisionWorksThisPage&frompage=reception'>
                                    <button style='width: 100%; height: 100%'>
                                            Patient Appointments
                                    </button>
                            </a>
                    </td>
                </tr>
               
                <tr>
                    <td style='text-align: center; height: 40px; width: 33%;'>
                        <a href='editpatientlist.php?EditPatientThisPage'>
                            <button style='width: 100%; height: 100%'>
                                Edit Patient
                            </button>
                        </a>
                    </td>
                </tr>
                <tr>
                    <td style='text-align: center; height: 40px; width: 33%;'>
                        <?php if($_SESSION['userinfo']['Patient_Transfer']=='yes'){ ?>
                        <a href='transferdoctor.php?section=reception'>
                            <button style='width: 100%; height: 100%'>
                                Patient Transfer
                            </button>
                        </a>
                        <?php }else{ ?>

                            <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                                Patient Transfer
                            </button>

                        <?php } ?>
                    </td>
                </tr>
            </table>
        </div>
        <div class="col-sm-4 hide">
            <table class="table">
                 
                <?php if(strtolower($_SESSION['systeminfo']['Departmental_Stock_Movement']) == 'yes') {  ?>
            <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <a href='#'>
                        <button style='width: 100%; height: 100%'>
                            Requisition Note
                        </button>
                    </a>
                </td>
            </tr>
            <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <a href='#'>
                        <button style='width: 100%; height: 100%'>
                            Goods Received Note
                        </button>
                    </a>
                </td>
            </tr>
            <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <a href='#'>
                        <button style='width: 100%; height: 100%'>
                            Issue Note
                        </button>
                    </a>
                </td>
            </tr>
            <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <a href='#'>
                        <button style='width: 100%; height: 100%'>
                            Consumption Note
                        </button>
                    </a>
                </td>
            </tr>
	    <?php } ?>
            </table>
        </div>
        <div class="col-sm-4">
            <table class="table" style="width:100%">
                <tr>
                    <td style='text-align: center; height: 40px; width: 33%;'>
                        <a href='receptionsetup.php?ReceptionSetup=ReceptionSetupThisPage'>
                            <button style='width: 100%; height: 100%'>
                                Reception Setup
                            </button>
                        </a>
                    </td>
                </tr>
                <tr>
                    <td style='text-align: center; height: 40px; width: 33%;'>
                        <?php if($_SESSION['userinfo']['Reception_Works'] == 'yes'){ ?>
                        <a href='referral_patients_list.php'>
                            <button style='width: 100%; height: 100%'>
                                Referral Patient
                                 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    <span style='background-color: red; border-radius: 8px; color: white; padding: 6px;'>
                                        <?php echo $totalCount; ?>
                                    </span>
                            </button>
                        </a>
                        <?php }else{ ?>

                            <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                                Referral Patient
                            </button>

                        <?php } ?>
                    </td>
                </tr>

                <tr>
                    <td class='hide' style='text-align: center; height: 40px; width: 33%;'>
                        <?php if($_SESSION['userinfo']['Reception_Works'] == 'yes'){ ?>
                        <a href='PatientListLocation.php?Section=Reception&CheckInPatient=CheckInPatientThisPage'>
                            <button style='width: 100%; height: 100%'>
                                Patient Progress

                            </button>
                        </a>
                        <?php }else{ ?>

                            <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                                Patient Location
                            </button>

                        <?php } ?>
                    </td>
                </tr>
                 <tr>
                    <td style='text-align: center; height: 40px; width: 33%;'>
                        <a href='receptionReports.php?Section=Reception&ReceptionReportThisPage'>
                            <button style='width: 100%; height: 100%'>
                                Reports
                            </button>
                        </a>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</fieldset>
<?php
    include("./includes/footer.php");
?>
<script>
$(document).ready(function(){
    check_if_afya_card_config_is_on()
    
});

function check_if_afya_card_config_is_on(){
    $.ajax({
        type:'POST',
        url:'ajax_check_if_afya_card_config_is_on.php',
        data:{function_module:"afya_card_module"},
        success:function(data){
            if(data=="enabled"){
                read_afya_card_infomation_and_process();
            }else{
                $("#afya_card_btn").val("AFYA CARD OFF");
                $("#afya_card_btn").prop("class","hide");
            }
        }
    });
}
</script>