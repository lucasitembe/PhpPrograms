<?php
include("./includes/header.php");
$Control = 'yes';
if(strtolower($_SESSION['systeminfo']['Departmental_Stock_Movement']) == 'yes') {
$Control = 'no';
}
if(!isset($_SESSION['userinfo'])){
@session_destroy();
header("Location: ../index.php?InvalidPrivilege=yes");
}
if(isset($_SESSION['userinfo'])){
if(isset($_SESSION['userinfo']['Laboratory_Works'])){
if($_SESSION['userinfo']['Laboratory_Works'] != 'yes'){
header("Location: ./index.php?InvalidPrivilege=yes");
}else{
@session_start();
if(!isset($_SESSION['Laboratory_Supervisor'])){
header("Location: ./deptsupervisorauthentication.php?SessionCategory=Laboratory&InvalidSupervisorAuthentication=yes");
}
}
}else{
header("Location: ./index.php?InvalidPrivilege=yes");
}
}else{
@session_destroy();
header("Location: ../index.php?InvalidPrivilege=yes");
}
;echo '<a href="deptsupervisorauthentication.php?SessionCategory=Laboratory&ChangeLocationLaboratory=ChangeLocationLaboratryThisPage" class="art-button-green">CHANGE DEPARTMENT</a>
<a href="index.php?DashboardThisPage=ThisPage" class="art-button-green">BACK</a>
<script type="text/javascript">
    function access_Denied(){ 
   alert("Access Denied");
   document.location = "./index.php";
    }

</script>
    
  <script type="text/javascript">
    function consulted_access_Denied(){ 
   alert("Access denied!");
   //document.location = "./index.php";
    }
 </script>
<br/><br/>
<br/><br/>
<br/><br/>
                        <fieldset>
                            <legend align=center><b>LABORATORY WORKS</b></legend>
                               
                        	    <div class="col-md-3"></div><div class="col-md-6"><table width = 60% class="table">
                                    <tr>
                                        <td colspan="2">
                                            <a href="patient_atendace.php">
                                                <button style="width: 100%; height: 100%">
                                                   Attendace List
                                                </button>
                                            </a>
                                        </td>
                                  
                                        <td colspan="2" style="text-align: center; height: 40px; width: 33%;" ';if($Control == 'yes'){echo "colspan='2'";};echo '>
                                            ';if(isset($_SESSION['userinfo']))
{
if($_SESSION['userinfo']['Laboratory_Works'] == 'yes')
{
;echo '                        			             <a href="searchpatientlaboratorylist.php"><button style="width: 100%; height: 100%">Collect Specimen</button></a>
                        		              ';
}
}else{
;echo '                                                <button style="width: 100%; height: 100%" onclick="return access_Denied();">Specimen Collection</button>
                        		          ';};echo '                                        </td>
                                        ';if(strtolower($_SESSION['systeminfo']['Departmental_Stock_Movement']) == 'yes') {;echo '										<td style="text-align: center; height: 40px; width: 33%; " class="hide">
                    ';if($_SESSION['userinfo']['Laboratory_Works'] == 'yes'){;echo '                    <a href="laboratoryrequisition.php?LaboratoryRequisition=LaboratoryRequisitionThisPage">
                        <button style="width: 100%; height: 100%">
                            Requisition Note
                        </button>
                    </a>
                    ';}else{;echo '                     
                        <button style="width: 100%; height: 100%" onclick="return access_Denied();">
                            Requisition Note 
                        </button>
                  
                    ';};echo '                </td>
                ';};echo '                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <a href="list_of_patients_arleady_collected_specimen.php">
                                                <button style="width: 100%; height: 100%">
                                                   Receive Specimen 
                                                </button>
                                            </a>
                                        </td>
                                   
                                    
                                    <td colspan="2" style="text-align: center; height: 40px; width: 33%;" ';if($Control == 'yes'){echo "colspan='2'";};echo '>
                                        ';if(isset($_SESSION['userinfo'])){
if($_SESSION['userinfo']['Laboratory_Works'] == 'yes'){
;echo '                                                     <a href="seachpatientfromspeciemenlist.php"><button style="width: 100%; height: 100%">Patient Lab Results</button></a>
                                                    ';}
}else{
;echo '                                                                <button style="width: 100%; height: 100%" onclick="return access_Denied();">Patient Lab Results</button>
                                                    ';
}
;echo '                                            </td>
                        ';if(strtolower($_SESSION['systeminfo']['Departmental_Stock_Movement']) == 'yes') {;echo '			<td style="text-align: center; height: 40px; width: 33%;" class="hide">
                    ';if($_SESSION['userinfo']['Laboratory_Works'] == 'yes'){;echo '                    <a href="employeeconsumptionnote.php?EmployeeeConsumptionNote=EmployeeeConsumptionNoteThisPage">
                        <button style="width: 100%; height: 100%">
                            Consumption Note
                        </button>
                    </a>
                    ';}else{;echo '                     
                        <button style="width: 100%; height: 100%" onclick="return access_Denied();">
                            Consumption Note
                        </button>
                  
                    ';};echo '                </td>
                ';};echo '         </tr>


            <tr>
                <td colspan="2" style="text-align: center; height: 40px; width: 33%;" ';if($Control == 'yes'){echo "colspan='2'";};echo '>
                    ';if(isset($_SESSION['userinfo'])){
if(($_SESSION['userinfo']['Laboratory_Works'] == 'yes') &&($_SESSION['userinfo']['Laboratory_consulted_patients'] == 'yes'))
{
;echo '            <a href="consultedlabpatientlist.php?LaboratoryResultsThisPage=ThisPage">
                <button style="width: 100%; height: 100%">
                View Previous Lab Results
                </button>
            </a>
            ';}else{;echo '                        <button style="width: 100%; height: 100%" onclick="return consulted_access_Denied();">
                            View Previous Lab Results
                        </button>
                    ';}};echo '                </td>
                ';if(strtolower($_SESSION['systeminfo']['Departmental_Stock_Movement']) == 'yes') {;echo '				<td style="text-align: center; height: 40px; width: 33%;" class="hide">
                    ';if($_SESSION['userinfo']['Laboratory_Works'] == 'yes'){;echo '                    <a href="laboratorygoodreceivingnote.php?LaboratoryGrn=LaboratoryGrnThisPage">
                        <button style="width: 100%; height: 100%">
                            Goods Received Note
                        </button>
                    </a>
                    ';}else{;echo '
                        <button style="width: 100%; height: 100%" onclick="return access_Denied();">
                            Goods Received Note
                        </button>

                    ';};echo '                </td>
				';};echo '            
                <td colspan="2" style="text-align: center; height: 40px; width: 33%;" ';if($Control == 'yes'){echo "colspan='2'";};echo '>
                    ';if(isset($_SESSION['userinfo']['Laboratory_Works'])){
;echo '            <a href="Laboratory_Reports.php?LaboratoryResultsThisPage=ThisPage">
                <button style="width: 100%; height: 100%">
                Laboratory Reports
                </button>
            </a>
            ';}else{;echo '                        <button style="width: 100%; height: 100%" onclick="return access_Denied();">
                            Laboratory Reports
                        </button>
            ';};echo '                </td>
            ';if(strtolower($_SESSION['systeminfo']['Departmental_Stock_Movement']) == 'yes') {;echo '		<td style="text-align: center; height: 40px; width: 33%;" class="hide">
                    ';if($_SESSION['userinfo']['Laboratory_Works'] == 'yes'){;echo '                    <a href="#.php">
                        <button style="width: 100%; height: 100%">
                            Issue Note
                        </button>
                    </a>
                    ';}else{;echo '                     
                        <button style="width: 100%; height: 100%" onclick="return access_Denied();">
                            Issue Note 
                        </button>
                  
                    ';};echo '                </td>
            ';};echo '            </tr>

            <tr>
			   <td class="hide" style="text-align: center; height: 40px; width: 33%;">
                    ';if($_SESSION['userinfo']['Laboratory_Works'] == 'yes'){;echo '                    <a href="#.php">
                        <button style="width: 100%; height: 100%">
                            Appointments & Schedule
                        </button>
                    </a>
                    ';}else{;echo '                     
                        <button style="width: 100%; height: 100%" onclick="return access_Denied();">
                            Appointments & Schedule
                        </button>
                  
                    ';};echo '                </td>
                
		<td style="text-align: center; height: 40px; width: 33%;" colspan="2">
                    ';if($_SESSION['userinfo']['Laboratory_Works'] == 'yes'){
;echo '			<a href="laboratory_setup.php?LaboratorySetup=LaboratorySetupThisPage">
			    <button style="width: 100%; height: 100%">
			    Laboratory Setup
			    </button>
			</a>
			';}else{;echo '				    <button style="width: 100%; height: 100%" onclick="return access_Denied();">
					 Laboratory Setup
				    </button>
			';};echo '		    </td>
            <td style="text-align: center; height: 40px; width: 33%;" colspan="2">
            ';if($_SESSION['userinfo']['Laboratory_Works'] == 'yes'){
;echo '            <a href="blood_tranfusion_module.php">
        <button style="width: 100%; height: 100%">
        Blood Transfusion
        </button>
    </a>
    ';}else{;echo '		    <button style="width: 100%; height: 100%" onclick="return access_Denied();">
    Blood Transfusion
    </button>
    ';};echo '                </td>
            </tr>

            <tr class="hide">
            <td style="text-align: center; height: 40px; width: 33%;" colspan="2">
                    ';if(isset($_SESSION['userinfo'])){
;echo '            <a href="#">
                <button style="width: 100%; height: 100%">
                Lab Devices Integration / Management
                </button>
            </a>
            ';}else{;echo '		    <button style="width: 100%; height: 100%" onclick="return access_Denied();">
			Lab Devices Integration / Management
		    </button>
            ';};echo '                </td>
				
            </tr>

        </table>
        </div>
</fieldset>
<br/>
';
include("./includes/footer.php");
?>