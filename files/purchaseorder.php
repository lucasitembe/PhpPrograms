<script src='js/functions.js'></script>
<?php
include("./includes/header.php");
include("./includes/connection.php");
include './procurement/procure.interface.php';
$Interface = new ProcureInterface();

if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Procurement_Works'])) {
        if ($_SESSION['userinfo']['Procurement_Works'] != 'yes') {
            header("Location: ./index.php?InvalidPrivilege=yes");
        }
    } else {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
?>


<a href='procurementworkspage.php?ProcurementWork=ProcurementWorkThisPage' class='art-button-green'>BACK</a>

<br><br>
<fieldset>
    <legend align=center>PROCUREMENT WORKS</legend>
    <center>
        <table width=45%>
            <tr>
                <td width='50%'>
                    <a href='procurementstoreorderlist.php?StoreOrderList=StoreOrderListThisPage'>
                        <button style='width: 100%; height: 100%'>
                            Approved Store Orders ~ <b><span style="color: red;">(<?=$Interface->countStoreOrders();?>)</span></b>
                        </button>
                    </a>
                </td>

                <td width='50%'>
                    <a href='procurementpendingorders.php?ProcurementPendingOrders=ProcurementPendingOrdersThisPage'>
                        <button style='width: 100%; height: 100%'>
                            Previous Orders
                        </button>
                    </a>
                </td>
            </tr>

            <tr>
                <td width='50%'>
                    <a href='approve_purchase_requisition.php?pr=active'>
                        <button style='width: 100%; height: 100%'>
                            Approve Purchase Requisition ~ (PR) ~ <b><span style="color: red;"> (<?=$Interface->countCreatedPurchaseRequisition('active');?>) </span></b>
                        </button>
                    </a>
                </td>

                <td width='50%'>
                    <a href='list_of_approved_purchase_requisition.php'>
                        <button style='width: 100%; height: 100%'>
                            Approved Purchase Requisition ~ (PR)
                        </button>
                    </a>
                </td>
            </tr>

            <tr>
                <td width='50%'>
                    <a href='list_of_local_purchase_order_to_approve.php?lpo=active'>
                        <button style='width: 100%; height: 100%'>
                            Approve Local Purchase Order ~ (LPO) ~ <b><span style="color: red;"> (<?=sizeof($Interface->fetchLocalPurchaseOrderList("","","","all","all","active"));?>) </span></b>
                        </button>
                    </a>
                </td>

                <td width='50%'>
                    <a href='list_of_local_purchase_order.php'>
                        <button style='width: 100%; height: 100%'>
                            Approved Local Purchase Order ~ (LPO)
                        </button>
                    </a>
                </td>
            </tr>

            <tr>
                <td width='50%' colspan="2">
                    <a href='procurement-recieving.php?InvalidSupervisorAuthentication=yes&from=procurement'>
                        <button style='width: 100%; height: 100%'>
                            Recieving
                        </button>
                    </a>
                </td>
            </tr>

            <tr>
                <td width='50%' colspan="2">
                    <a href='requisition_without_supplier.php'>
                        <button style='width: 100%; height: 100%'>
                            Purchase Requisition With No Supplier
                        </button>
                    </a>
                </td>
            </tr>

            <tr>
                <td width='50%'>
                    <a href='lpo_without_pr.php?status=new'>
                        <button style='width: 100%; height: 100%'>
                            Create LPO Without Purchase Requisition
                        </button>
                    </a>
                </td>

                <td width='50%'>
                    <a href='approve_lpo_without_lpo.php'>
                        <button style='width: 100%; height: 100%'>
                            APPROVE LPO Without Purchase Requisition
                        </button>
                    </a>
                </td>
            </tr>

            <tr>
                <td width='50%' colspan="2">
                    <a href='approve_purchase_requisition.php?pr=cancelled'>
                        <button style='width: 100%; height: 100%'>
                            Disapproved Purchase Requisition (PR) <b><span style="color: red;"> (<?=$Interface->countCreatedPurchaseRequisition('cancelled');?>) </b></span>
                        </button>
                    </a>
                </td>
            </tr>


            <tr>
                <td width='50%' colspan="2">
                    <a href='list_of_local_purchase_order_to_approve.php?lpo=cancelled'>
                        <button style='width: 100%; height: 100%'>
                            Disapproved Local Purchase Requisition (LPR) <b><span style="color: red;"> (<?=sizeof($Interface->fetchLocalPurchaseOrderList(NULL,NULL,NULL,"all","all","cancelled"));?>) </b></span>
                        </button>
                    </a>
                </td>
            </tr>

	   <tr>
                <td width='50%' colspan="2">
                    <a href='approve_store_order.php?From=Procure'>
                        <button style='width: 100%; height: 100%'>
                            Approve Store Orders
                        </button>
                    </a>
                </td>
            </tr>

        </table>
    </center>
</fieldset>

<?php
include("./includes/footer.php");
?>
