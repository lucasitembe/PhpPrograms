<?php
    include("./includes/header.php");
    $Control = 'yes';
    if(strtolower($_SESSION['systeminfo']['Departmental_Stock_Movement']) == 'yes') {
        $Control = 'no';
    }
    if (isset($_GET['Registration_ID'])) {
        $Registration_ID = $_GET['Registration_ID'];
    }
    if (isset($_GET['Patient_Payment_ID'])) {
        $Patient_Payment_ID = $_GET['Patient_Payment_ID'];
    }
    if (isset($_GET['Patient_Payment_Item_List_ID'])) {
        $Patient_Payment_Item_List_ID = $_GET['Patient_Payment_Item_List_ID'];
    }
    if (isset($_GET['consultation_ID'])) {
        $consultation_ID = $_GET['consultation_ID'];
    }
    // if(!isset($_SESSION['userinfo'])){
	// @session_destroy();
	// header("Location: ../index.php?InvalidPrivilege=yes");
    // }
    // if(isset($_SESSION['userinfo'])){
	// if(isset($_SESSION['userinfo']['Laboratory_Works'])){
	//     if($_SESSION['userinfo']['Laboratory_Works'] != 'yes'){
	// 	header("Location: ./index.php?InvalidPrivilege=yes");
	//     }else{
    //                 @session_start();
    //                 if(!isset($_SESSION['Laboratory_Supervisor'])){ 
                        
	// 					//echo 'here'; exit;
	// 					header("Location: ./deptsupervisorauthentication.php?SessionCategory=Laboratory&InvalidSupervisorAuthentication=yes");
					
    //                 }
					
	// 				// }else{
	// 				// echo 'there'; exit;
	// 				// }
    //         }
	// }else{
	//     header("Location: ./index.php?InvalidPrivilege=yes");
	// }
    // }else{
	// @session_destroy();
	//     header("Location: ../index.php?InvalidPrivilege=yes");
    // }
?>
<a href='deptsupervisorauthentication.php?SessionCategory=Laboratory&ChangeLocationLaboratory=ChangeLocationLaboratryThisPage' class='art-button-green'>CHANGE DEPARTMENT</a>
<a href="index.php?DashboardThisPage=ThisPage" class="art-button-green">BACK</a>
<script type='text/javascript'>
    function access_Denied(){ 
   alert("Access Denied");
   document.location = "./index.php";
    }

</script>
    
  <script type='text/javascript'>
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
                                        <!-- <td colspan="2">
                                            <a href='patient_atendace.php'>
                                                <button style='width: 100%; height: 100%'>
                                                   Attendace List
                                                </button>
                                            </a>
                                        </td> -->
                                  
                                        <td colspan="2" style='text-align: center; height: 40px; width: 33%;' <?php if($Control == 'yes'){ echo "colspan='2'"; } ?>>
                                            <?php if(isset($_SESSION['userinfo']))
                                            {
                        			         if($_SESSION['userinfo']['Laboratory_Works'] == 'yes')
                                             {
                        			             ?>
                                                 <a href="emergency_earchpatientlaboratorylist.php?     Registration_ID=<?= $Registration_ID ?>&Patient_Payment_ID=<?= $Patient_Payment_ID ?>&Patient_Payment_Item_List_ID=<?= $Patient_Payment_Item_List_ID ?>&consultation_ID=<?= $consultation_ID;?>">
                                                        <button style='width: 100%; height: 100%'>Collect Specimen</button>
                                                    </a>
                        			             <!-- <a href='searchpatientlaboratorylist.php'><button style='width: 100%; height: 100%'>Collect Specimen</button></a> -->
                        		              <?php 
                                            }
                                            }else{
                                             ?>
                                                <button style='width: 100%; height: 100%' onclick="return access_Denied();">Specimen Collection</button>
                        		          <?php } ?>
                                        </td>
                                        <?php if(strtolower($_SESSION['systeminfo']['Departmental_Stock_Movement']) == 'yes') {  ?>
										<td style='text-align: center; height: 40px; width: 33%; ' class="hide">
                    <?php if($_SESSION['userinfo']['Laboratory_Works'] == 'yes'){ ?>
                    <a href='laboratoryrequisition.php?LaboratoryRequisition=LaboratoryRequisitionThisPage'>
                        <button style='width: 100%; height: 100%'>
                            Requisition Note
                        </button>
                    </a>
                    <?php }else{ ?>
                     
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Requisition Note 
                        </button>
                  
                    <?php } ?>
                </td>
                <?php } ?>
                                    </tr>
                                    <tr>
                                        <!-- <td colspan="2">
                                            <a href='list_of_patients_arleady_collected_specimen.php'>
                                                <button style='width: 100%; height: 100%'>
                                                   Receive Specimen 
                                                </button>
                                            </a>
                                        </td> -->
                                   
                                    
                                    <td colspan="2" style='text-align: center; height: 40px; width: 33%;' <?php if($Control == 'yes'){ echo "colspan='2'"; } ?>>
                                        <?php if(isset($_SESSION['userinfo'])){
                                        if($_SESSION['userinfo']['Laboratory_Works'] == 'yes'){
                                        ?>
                                                     <!-- <a href='seachpatientfromspeciemenlist.php'><button style='width: 100%; height: 100%'>Patient Lab Results</button></a> -->
                                                     
                                                     <a href="emergency_eachpatientfromspeciemenlist.php?Registration_ID=<?= $Registration_ID ?>&Patient_Payment_ID=<?= $Patient_Payment_ID ?>&Patient_Payment_Item_List_ID=<?= $Patient_Payment_Item_List_ID ?>&consultation_ID=<?= $consultation_ID;?>">
                                                        <button style='width: 100%; height: 100%'>Patient Lab Results</button>
                                                    </a>
                                                    <?php }
                                                    }else{
                                                     ?>
                                                                <button style='width: 100%; height: 100%' onclick="return access_Denied();">Patient Lab Results</button>
                                                    <?php 
                                                    } 
                                                    ?>
                                            </td>
                        <?php if(strtolower($_SESSION['systeminfo']['Departmental_Stock_Movement']) == 'yes') {  ?>
			<td style='text-align: center; height: 40px; width: 33%;' class="hide">
                    <?php if($_SESSION['userinfo']['Laboratory_Works'] == 'yes'){ ?>
                    <a href='employeeconsumptionnote.php?EmployeeeConsumptionNote=EmployeeeConsumptionNoteThisPage'>
                        <button style='width: 100%; height: 100%'>
                            Consumption Note
                        </button>
                    </a>
                    <?php }else{ ?>
                     
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Consumption Note
                        </button>
                  
                    <?php } ?>
                </td>
                <?php } ?>
         </tr>


        </table>
        </div>
</fieldset>
<br/>
<?php
    include("./includes/footer.php");
?>