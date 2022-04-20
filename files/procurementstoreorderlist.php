<?php
    include("./includes/header.php");
    include("./includes/connection.php");

    $Employee_ID = (isset($_SESSION['userinfo']['Employee_ID'])) ? $_SESSION['userinfo']['Employee_ID'] : 0;
    $Employee_Name = (isset($_SESSION['userinfo']['Employee_Name'])) ? $_SESSION['userinfo']['Employee_Name'] : 0;
	
    if(!isset($_SESSION['userinfo'])){
        @session_destroy();
        header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
        if(isset($_SESSION['userinfo']['Procurement_Works'])){
            if($_SESSION['userinfo']['Procurement_Works'] != 'yes'){
                    header("Location: ./index.php?InvalidPrivilege=yes");
                }
        }else{ header("Location: ./index.php?InvalidPrivilege=yes"); }
    }else{
        @session_destroy();header("Location: ../index.php?InvalidPrivilege=yes");
    } 
    
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Procurement_Works'] == 'yes'){
            echo "<a href='purchaseorder.php?status=new&NPO=True&PurchaseOrder=PurchaseOrderThisPage' class='art-button-green'>BACK</a>";
        }
    }
?>

<br/><br/>
<fieldset>
    <center>
    <table width=70%>
        <tr>
            <td style='text-align: right;' width=15%><b>Start Date</b></td>
            <td width=30%><input type='text' name='Date_From' id='date' placeholder='Start Date' style='text-align: center;'></td>
            <td style='text-align: right;' width=15%><b>End Date</b></td>
            <td width=30%><input type='text' name='Date_To' id='date2' placeholder='End Date' style='text-align: center;'></td>
            <td style='text-align: center;' width=7%><input name='Filter' type='button' value='FILTER' class='art-button-green' onclick='loadStoreOrder()'></td>
        </tr>
    </table>
    </center>
</fieldset>

<fieldset style='overflow-y: scroll; height: 550px;' id='Previous_Fieldset_List'>
    <legend align=left>LIST OF APPROVED STORE ORDERS</legend>
        <center><table width = 100% border=0>
        <tr id='thead' style='background-color:#ddd'>
                <td style='font-weight:500;padding:8px;text-align:center' width=4%>S/N</td>
                <td style='font-weight:500;padding:8px;' width=12%>EMPLOYEE CREATED </td>
                <td style='font-weight:500;padding:8px;' width=12% ><center>STORE ORDER N<u>o</u></center></td>
                <td style='font-weight:500;padding:8px;' width=12%>APPROVED DATE</td>
                <td style='font-weight:500;padding:8px;' width=12%>ORDERING STORE</td>
                <td style='font-weight:500;padding:8px;' width=12%>ORDER DESCRIPTION</td>
                <td style='font-weight:500;padding:8px;text-align:center' style='text-align: center;' width=20%>ACTION</td>
            </tr>

            <tbody id='display-data' style="background-color: white;"></tbody>
        </table>
</fieldset>

    <script>
        $(document).ready(() => {
            loadStoreOrder();
        });

        function loadStoreOrder(){
            var start_date = $('#date').val();
            var end_date = $('#date2').val();

            $.get('procurement/procure.common.php',{ request:'load_store_orders',start_date:start_date,end_date:end_date },
                (response) => {
                    $('#display-data').html(response);
                }
            );
        }
    </script>
    
<?php include('./includes/footer.php'); ?>