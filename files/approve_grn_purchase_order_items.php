<?php
@session_start();
include("./includes/header.php");
include("./includes/connection.php");
include("return_unit_of_measure.php");
if (!isset($_SESSION['userinfo'])) {
    @session_destroy(); 
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Storage_And_Supply_Work'])) {
        if ($_SESSION['userinfo']['Storage_And_Supply_Work'] != 'yes') {
            header("Location: ./index.php?InvalidPrivilege=yes");
        }if ($_SESSION['userinfo']['can_edit'] != 'yes' && isset($_GET['src']) && $_GET['src'] = 'edit') {
            header("Location: ./previousgrnlist.php?PreviousGrn=PreviousGrnThisPage");
        }
    } else {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if(isset($_GET['grn_local_purchase_order_cache_id'])){
    $grn_local_purchase_order_cache_id=$_GET['grn_local_purchase_order_cache_id'];
    $grn_local_purchase_order_cache_id=" AND grn_local_purchase_order_cache_id='$grn_local_purchase_order_cache_id'";
}else{
    $grn_local_purchase_order_cache_id="";
}
?>
<?php 
    if(isset($_GET['from_procurement'])){
        echo'
            <a href="approve_grn_purchase_order.php?from_procurement=yes" class="art-button-green">BACK</a>
            <input value="yes" id="from_procurement" type="hidden">
        ';
    }else{
        echo'
            <a href="approve_grn_purchase_order.php" class="art-button-green">BACK</a>
            <input value="yes" id="from_procurement" type="hidden">
        ';
    }
?>

<br/><br/>
<style>
    table,tr,td{
        border:none!important;
        padding: 10px !important;
    }

    table,tr{
        border-bottom:1px solid #ccc !important;
    }

    .table,tr,td{
        border:none!important;
    }
    .table{
        border:none!important;
    }

    .table,tr{
        border:1px solid #ddd !important;
    }

    .aproval-table{
       border:'1px solid black !important';
       width: 100%;
    }

    .aproval-table tbody{
        background-color: #fff;
    }

    .aproval-table tr td{
        padding: 10px;
    }
</style>
<fieldset>
    <legend align="center">
        <b>APPROVE GRN AGAINST PURCHASE ORDER</b>
    </legend>
    <table class="table">
     <?php 
            $local_purchase_order_id="";
            $Sub_Department_ID = $_SESSION['Storage_Info']['Sub_Department_ID'];

            $verify = (isset($_GET['from_procurement'])) ? "yes" : "no";

            if($verify == "yes"){
                $sql ="SELECT glpoc.local_purchase_order_id,glpoc.grn_local_purchase_order_cache_id,glpoc.Delivery_Note_Number,glpoc.Invoice_Number,glpoc.Delivery_Date,glpoc.Delivery_Person,glpoc.RV_Number,emp.Employee_Name,sub.Sub_Department_Name,sp.Supplier_Name FROM tbl_grn_local_purchase_order_cache glpoc,tbl_employee emp,tbl_sub_department sub,tbl_supplier sp WHERE glpoc.receiver_Employee_ID=emp.Employee_ID AND glpoc.Sub_Department_ID=sub.Sub_Department_ID AND glpoc.Supplier_ID=sp.Supplier_ID $grn_local_purchase_order_cache_id";
            }else{
                $sql ="SELECT glpoc.local_purchase_order_id,glpoc.grn_local_purchase_order_cache_id,glpoc.Delivery_Note_Number,glpoc.Invoice_Number,glpoc.Delivery_Date,glpoc.Delivery_Person,glpoc.RV_Number,emp.Employee_Name,sub.Sub_Department_Name,sp.Supplier_Name FROM tbl_grn_local_purchase_order_cache glpoc,tbl_employee emp,tbl_sub_department sub,tbl_supplier sp WHERE glpoc.receiver_Employee_ID=emp.Employee_ID AND glpoc.Sub_Department_ID=sub.Sub_Department_ID AND glpoc.Supplier_ID=sp.Supplier_ID AND glpoc.Sub_Department_ID='$Sub_Department_ID' $grn_local_purchase_order_cache_id";
            }

            $sql_select_grn_purchase_order_result=mysqli_query($conn,$sql) or die(mysqli_error($conn));
            if(mysqli_num_rows($sql_select_grn_purchase_order_result)>0){
                $count=1;
                while($grn_purchase_order_rows=mysqli_fetch_assoc($sql_select_grn_purchase_order_result)){
                    $grn_local_purchase_order_cache_id=$grn_purchase_order_rows['grn_local_purchase_order_cache_id'];
                    $Delivery_Note_Number=$grn_purchase_order_rows['Delivery_Note_Number'];
                    $Invoice_Number=$grn_purchase_order_rows['Invoice_Number'];
                    $Delivery_Date=$grn_purchase_order_rows['Delivery_Date'];
                    $Delivery_Person=$grn_purchase_order_rows['Delivery_Person'];
                    $RV_Number=$grn_purchase_order_rows['RV_Number'];
                    $Employee_Name=$grn_purchase_order_rows['Employee_Name'];
                    $Sub_Department_Name=$grn_purchase_order_rows['Sub_Department_Name'];
                    $Supplier_Name=$grn_purchase_order_rows['Supplier_Name'];
                    $local_purchase_order_id=$grn_purchase_order_rows['local_purchase_order_id'];
                    
                    echo "<tr><td><b>LPO No.</b></td><td><input type='text' value='$local_purchase_order_id' readonly='readonly'/></td><td><b>Store</b></td><td><input type='text' value='$Sub_Department_Name' readonly='readonly'/></td><td><b>Supplier</b></td><td><input type='text' value='$Supplier_Name' readonly='readonly'/></td><td><b>Employee Receiving</b></td><td><input type='text' value='$Employee_Name' readonly='readonly'/></td></tr>";
                    echo "<tr><td><b>Delivery Note No.</b></td><td><input type='text' value='$Delivery_Note_Number' readonly='readonly'/></td><td><b>Invoice No.</b></td><td><input type='text' value='$Invoice_Number' readonly='readonly'/></td><td><b>Delivery Date</b></td><td><input type='text' value='$Delivery_Date' readonly='readonly'/></td><td><b>Delivery Person</b></td><td><input type='text' value='$Delivery_Person' readonly='readonly'/></td></tr>";
                    
                }
            }
        ?>  
    </table>
</fieldset>
<fieldset style="height:250px;overflow-y: scroll;background: #FFFFFF">
    <table class="table">
        <tr style="background-color: #eee;">
            <td width='5%'><center><b>S/No.</b></center></td>
            <td><b>ITEM NAME</b></td>
            <td width='10%'><center><b>PACK</b></center></td>
            <td style='text-align:center'><b>ITEMS PER PACK</b></td>
            <td style='text-align:center'><b>QTY RECIEVED</b></td>
            <td style='text-align:center'><b>QTY REJECTED</b></td>
            <td style="text-align: left;"><b>EXPIRE DATE</b></td>
            <td style="text-align: right;"><b>BUYING PRICE</b></td>
            <td style='text-align:center'><b>BATCH NO</b></td>
            <td><b>REJECTION REASONS</b></td>
            <td width="10%"><b>STATUS</b></td>
        </tr>
        <?php 
            $sql_purchase_grn_items_result=mysqli_query($conn,"SELECT Grn_Status,buying_price,Item_ID,container_quantity,item_per_container,quantity_required_,expiredate,batch_no,rejected_quantity,rejection_reason FROM tbl_grn_local_purchase_order_items_cache WHERE grn_local_purchase_order_cache_id='$grn_local_purchase_order_cache_id'") or die(mysqli_error($conn));
            if(mysqli_num_rows($sql_purchase_grn_items_result)>0){
                $count=1;
                while($grn_purchase_items_result_rows=mysqli_fetch_assoc($sql_purchase_grn_items_result)){
                   $Item_ID=$grn_purchase_items_result_rows['Item_ID'];
                   $container_quantity=$grn_purchase_items_result_rows['container_quantity'];
                   $item_per_container=$grn_purchase_items_result_rows['item_per_container'];
                   $quantity_required_=$grn_purchase_items_result_rows['quantity_required_'];
                   $expiredate=$grn_purchase_items_result_rows['expiredate'];
                   $batch_no=$grn_purchase_items_result_rows['batch_no'];
                   $rejected_quantity=$grn_purchase_items_result_rows['rejected_quantity'];
                   $rejection_reason=$grn_purchase_items_result_rows['rejection_reason'];
                   $buying_price=$grn_purchase_items_result_rows['buying_price'];
                   $Grn_Status=$grn_purchase_items_result_rows['Grn_Status'];
                   
                   //get item name and folio number
                    $sql_select_item_detail_result=mysqli_query($conn,"SELECT Product_Name,item_folio_number FROM tbl_items WHERE Item_ID='$Item_ID'") or die(mysqli_error($conn));
                    if(mysqli_num_rows($sql_select_item_detail_result)>0){
                        while($items_rows=mysqli_fetch_assoc($sql_select_item_detail_result)){
                            $Product_Name=$items_rows['Product_Name'];
                            $item_folio_number=$items_rows['item_folio_number'];
                        }
                    }
                    $Grn_Status=strtolower($Grn_Status);
                    if($Grn_Status == 'outstanding') { 
                        $new_Grn_Status = 'partial recieved'; 
                    }else{
                        $new_Grn_Status=strtolower($Grn_Status);
                    }
                    
                    echo "<tr>
                                <td><center>$count.</center></td>
                                <td>$Product_Name</td>
                                <td style='text-align:center'>$container_quantity</td>
                                <td style='text-align:center'>$item_per_container</td>
                                <td style='text-align:center'>$quantity_required_</td>
                                <td style='text-align:center'>$rejected_quantity</td>
                                <td>$expiredate</td>
                                <td style='text-align:right'>".number_format($buying_price,2)."</td>
                                <td style='text-align:center'>$batch_no</td>
                                <td>$rejection_reason</td>
                                <td>$new_Grn_Status</td>
                            </tr>";
                    $count++;
                }
            }
        ?>
    </table>
</fieldset>

<fieldset>

    <h5>Document Approval Summary ~ <b>GRN AGAINST PURCHASE ORDER</b></h5>
    <br>
    <table border="1" class='aproval-table'>
        <thead>
            <tr>
                <td style="text-align: center;">S/N</td>
                <td>Employee Name</td>
                <td>Approval Title</td>
                <td>Approval Date</td>
                <td>Approval Status</td>
            </tr>
        </thead> 

        <tbody>
            <?php 
                $count = 1;
                $check = "";

                $queryEmp = mysqli_query($conn,"SELECT * FROM tbl_employee emp,tbl_document_approval_level dal,tbl_employee_assigned_approval_level eal WHERE emp.Employee_ID=eal.assgned_Employee_ID AND dal.document_approval_level_id=eal.document_approval_level_id AND document_type='grn_against_purchases_order' GROUP BY eal.document_approval_level_id") or die(mysqli_error($conn));
                
                if(mysqli_num_rows($queryEmp) > 0){
                    while($name = mysqli_fetch_assoc($queryEmp)){
                        $no = $name['assigned_approval_level_id'];
                        $nu = $name['document_approval_level_title_id'];
                        $id = $name['Employee_ID'];

                        $sup = mysqli_fetch_assoc(mysqli_query($conn,"SELECT document_approval_level_title FROM tbl_document_approval_level_title WHERE document_approval_level_title_id = '$nu'"))['document_approval_level_title'];

                        $sql_select_approver_result=mysqli_fetch_assoc(mysqli_query($conn,"SELECT date_time FROM tbl_employee emp,tbl_document_approval_control dac WHERE emp.Employee_ID=dac.approve_employee_id AND emp.Employee_ID = '$id' AND dac.document_number='$purchase_requisition_id' AND dac.document_type = 'purchase_requisition'"))['date_time'];

                        if(empty($sql_select_approver_result)){
                            $check_status = "<span style='background-color:red;color:white;padding:5px;font-wight:500'><b>Not Approved</b></span>";
                        }else{
                            $check_status = "<span style='background-color:green;color:white;padding:5px'><b>Approved</b></span>";
                        }

                        echo "<tr>
                                <td style='text-align: center;'>".$count++."</td>
                                <td>".$name['Employee_Name']."</td>
                                <td>".$sup."</td>
                                <td>".$sql_select_approver_result."</td>
                                <td>".$check_status."</td>
                            </tr>";

                        $check_status = "";
                        
                    }
                }else{
                    echo "<tr><td colspan='4' style='text-align:center'><b>No Approval Found</b></td></tr>";
                }
            ?>
            
        </tbody>
    </table>


<fieldset>
    <table class="table">
        <tr>
            <td>
                <select style="width:100%;display:none">
                    <option></option>
                    <?php 
                        $sql_select_approver_result=mysqli_query($conn,"SELECT document_approval_level_title,Employee_Name FROM tbl_employee emp,tbl_document_approval_control dac WHERE emp.Employee_ID=dac.approve_employee_id AND document_number='$local_purchase_order_id' AND document_type='grn_against_purchases_order'") or die(mysqli_error($conn));
                        if(mysqli_num_rows($sql_select_approver_result)>0){
                            while ($approver_rows=mysqli_fetch_assoc($sql_select_approver_result)) {
                                $document_approval_level_title=$approver_rows['document_approval_level_title'];
                                $Employee_Name=$approver_rows['Employee_Name'];
                                echo "<option>$Employee_Name ~ $document_approval_level_title</option>";
                            }
                        }
                    ?>
                </select>
            </td>
            <td><input type="text" placeholder="Username" id="Username" value=""style="text-align:center" class="form-control" /></td>
            <td><input type="password" placeholder="Password" id="Password" value="" style="text-align:center" class="form-control" /></td>
            <td width="10%">
                <input type="button" value="APPROVE GRN" class="art-button-green" onclick='confirm_approval()'/>
            </td>
            <td width="10%" style="display:none">
                <input  type="button" value="CANCEL PR" onclick="" class="art-button-green"/>
            </td>
        </tr>
    </table>
</fieldset>

<script>
function confirm_approval(){
        var Supervisor_Username = document.getElementById("Username").value;
        var Supervisor_Password = document.getElementById("Password").value;
        //console.log("==>");
        if(Supervisor_Username==""){
            $("#Username").css("border","2px solid red");
        }else if(Supervisor_Password==""){
            $("#Username").css("border","");
            $("#Password").css("border","2px solid red");
        }else{
            $("#Username").css("border","");
            $("#Password").css("border","");
           if(confirm("Are you sure you want to approve this Order?")){
            check_if_valid_user_to_approve_this_document();
        }  
      }   
    }
    function check_if_valid_user_to_approve_this_document(){
        var Supervisor_Username = document.getElementById("Username").value;
        var Supervisor_Password = document.getElementById("Password").value;
        var local_purchase_order_id = '<?php echo $local_purchase_order_id; ?>';
         $.ajax({
                type: 'GET',
                url: 'verify_approver_privileges_support.php',
                data: 'Username=' + Supervisor_Username + '&Password=' + Supervisor_Password + '&document_number=' + local_purchase_order_id+"&document_type=grn_against_purchases_order",
                cache: false,
                success: function (feedback) {
                    if (feedback == 'all_approve_success') {
                        alert("Approved Successfully");
                        $(".remove_btn").hide();
                        create_grn_against_purchase_order();
                        
                    }else if(feedback=="invalid_privileges"){
                        alert("Invalid Username or Password or you do not have enough privilage to approve this requisition");
                    }else if(feedback=="fail_to_approve"){
                        alert("Fail to approve..please try again");  
                    }else{
                        $(".remove_btn").hide();
                        alert(feedback);
                    }
                }
            });
    }
    function create_grn_against_purchase_order(){ 
        var grn_local_purchase_order_cache_id='<?= $grn_local_purchase_order_cache_id ?>';
        var local_purchase_order_id='<?= $local_purchase_order_id ?>';
        var from_procurement = $('#from_procurement').val();
         $.ajax({
                type: 'GET',
                url: 'ajax_create_grn_against_purchase_order.php',
                data: {grn_local_purchase_order_cache_id:grn_local_purchase_order_cache_id,local_purchase_order_id:local_purchase_order_id},
                cache: false,
                success: function (data) {
                    if(data==1){
                        document.location="list_of_grn_against_purchase_order.php?from_procurement="+from_procurement;
                    }
                }
            });
    }
</script>
<?php
    include("./includes/footer.php");
?>