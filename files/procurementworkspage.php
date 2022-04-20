<?php
    include("./includes/header.php");
    if(!isset($_SESSION['userinfo'])){
	    @session_destroy();
	    header("Location: ../index.php?InvalidPrivilege=yes");
    }

    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Procurement_Works'])){
	    if($_SESSION['userinfo']['Procurement_Works'] != 'yes'){
		header("Location: ./index.php?InvalidPrivilege=yes");
	    }else{
                    @session_start();
                    if(!isset($_SESSION['Procurement_Supervisor'])){ 
                        header("Location: ./deptsupervisorauthentication.php?SessionCategory=Procurement&InvalidSupervisorAuthentication=yes");
                    }
            }
	}else{
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	    header("Location: ../index.php?InvalidPrivilege=yes");
    }
?>
<a href='deptsupervisorauthentication.php?SessionCategory=Procurement&ChangeLocationProcurement=ChangeLocationProcurementThisPage' class='art-button-green'>CHANGE DEPARTMENT</a>
<script type='text/javascript'>
    function access_Denied(){ 
   alert("Access Denied");
   document.location = "./index.php";
    }
</script>
<br/><br/>
<fieldset>  
    <legend align=center><b>PROCUREMENT WORKS</b></legend>
        <center><table width = 40%>
	    <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if($_SESSION['userinfo']['Procurement_Works'] == 'yes'){ ?>
                    <a href='purchaseorder.php?status=new&NPO=True&PurchaseOrder=PurchaseOrderThisPage'>
                        <button style='width: 100%; height: 100%'>
                            Purchase Orders
                        </button>
                    </a>
                    <?php }else{ ?>
                     
                        <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                            Purchase Orders 
                        </button>
                  
                    <?php } ?>
                </td>
            </tr>
		<?php if(strtolower($_SESSION['systeminfo']['Departmental_Stock_Movement']) == 'yes') {  ?>
	    <tr class='hide'>
            <td style='text-align: center; height: 40px; width: 33%;'>
                <?php if($_SESSION['userinfo']['Procurement_Works'] == 'yes'){ ?>
                <a href='procurementrequisition.php?ProcurementRequisition=ProcurementRequisitionThisPage'>
                    <button style='width: 100%; height: 100%'>
                        Requisitions
                    </button>
                </a>
                <?php }else{ ?>
                 
                    <button style='width: 100%; height: 100%' onclick="return access_Denied();">
                        Requisitions 
                    </button>
              
                <?php } ?>
            </td>
        </tr>
        <tr>
            <td  style='text-align: center; height: 40px;'>
                <a href='edit_item_details.php?from_procure_ment'>
                    <button style='width: 100%; height: 100%'>
                      Edit Items Details
                    </button>
                </a>
            </td>
        </tr>
        <?php } ?>
	        <tr>
                <td style='text-align: center; height: 40px; width: 33%;'>
                    <?php if($_SESSION['userinfo']['Procurement_Works'] == 'yes'){ ?>
                    <a href='procurementreports.php?ProcurementReports=ProcurementReportsThisPage'>
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
        </table>
        </center>
</fieldset>
<?php
    include("./includes/footer.php");
?>