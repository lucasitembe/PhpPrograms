<?php include './includes/header.php'; ?>
    <a href="managementworkspage.php?ManagementWorksPage=ThisPage" class="art-button-green">BACK</a>
    <br><br>

    <fieldset>
        <legend style="margin: 0;font-weight: 500" align="center">DOCUMENT APPROVAL</legend>
        <center>
            <table width="35%">
                <tr><td><a href='approve_store_order.php?From=Management_Works'><button style='width: 100%; height: 100%'>STORE ORDERS</button></a></td></tr>
                <tr><td><a href='approve_purchase_requisition.php?pr=active&From=Management_Works'><button style='width: 100%; height: 100%'>PURCHASE REQUISITION</button></a></td></tr>
                <tr><td><a href='list_of_local_purchase_order_to_approve.php?lpo=active&From=Management_Works'><button style='width: 100%; height: 100%'>LOCAL PURCHASE REQUISITION</button></a></td></tr>
                <tr><td><a href='approve_lpo_without_lpo.php?From=Management_Works'><button style='width: 100%; height: 100%'>LPO WITHOUT PURCHASE REQUISITION</button></a></td></tr>
            </table>
        </center>
    </fieldset>

<?php include './includes/footer.php'; ?>