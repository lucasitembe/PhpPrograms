<?php 
    include './includes/header.php';
    include 'procurement/procure.interface.php';

    $LPO_ID = $_GET['ID'];
    $Procure = new ProcureInterface();
    $LPO_Details = $Procure->fetchAllSubmittedLPOWithoutPRSingle_($LPO_ID);
    $count = 1;
    $grand_total = 0;
?>
<style>
    .table,tr,td{
        border:none!important;
    }

    .table,tr{
        border:1px solid #ccc!important;
    }
    .table{
        border:none!important;
    }
</style>

<a href="approve_lpo_without_lpo.php" class="art-button-green">BACK</a>

<br><br>

<fieldset>
    <legend>APPROVE LPO WITHOUT PURCHASE REQUISITION</legend>
    <table width='100%'>
        <tr>
        <td width='15%' style="padding: 8px;font-weight:500">Document Number</td>
            <td width='17%' style="padding: 3px;font-weight:500"><input type="text" style="padding: 6px;" readonly id="Document_Number" value="<?=$_GET['ID']?>"></td>
            
            <td width='15%' style="padding: 8px;font-weight:500">Supplier</td>
            <td width='17%' style="padding: 3px;font-weight:500"><input type="text" value="<?=$LPO_Details[0]['Supplier_Name']?>"></td>

            <td width='15%' style="padding: 8px;font-weight:500">Created Date</td>
            <td width='17%' style="padding: 3px;font-weight:500"><input type="text" placeholder="Created Date" style="padding: 6px;" value="<?=$LPO_Details[0]['Created_AT']?>"></td>
        </tr>

        <tr>
            <td width='15%' style="padding: 8px;font-weight:500">Store</td>
            <td width='17%' style="padding: 3px;font-weight:500"><input type="text" value="<?=$LPO_Details[0]['Sub_Department_Name']?>"></td>

            <td width='15%' style="padding: 8px;font-weight:500">Account Number</td>
            <td width='17%' style="padding: 3px;font-weight:500"><input type="text" placeholder="Account Number" style="padding: 6px;" value="<?=$LPO_Details[0]['Account_Number']?>"></td>

            <td width='15%' style="padding: 8px;font-weight:500">Created By</td>
            <td width='17%' style="padding: 3px;font-weight:500"><input type="text" style="padding: 6px;" readonly id="Employee_Name" value="<?=$LPO_Details[0]['Employee_Name']?>"></td>
        </tr>
        <tr>
            <td width='15%' style="padding: 8px;font-weight:500">LPO Description</td>
            <td width='17%' style="padding: 3px;font-weight:500"><input type="text" style="padding: 6px;" value="<?=$LPO_Details[0]['Description']?>"></td>
        </tr>
    </table>
</fieldset>

<fieldset style="height: 300px;overflow-y:scroll">
    <table width='100%'>
        <tr style="background-color: #ddd;font-weight:500">
            <td width='8%' style="padding: 6px;"><center>S/N</center></td>
            <td width='' style="padding: 6px;">ITEM NAME</td>
            <td width='12%' style="padding: 6px;"><center>UNIT OF MEASURE</center></td>
            <td width='12%' style="padding: 6px;"><center>QUANTITY</center></td>
            <td width='12%' style="padding: 6px;text-align:end">BUYING PRICE</td>
            <td width='12%' style="padding: 6px;text-align:end">SUB TOTAL</td>
        </tr>

        <tbody style="background-color: #fff;">
            <?php 
                foreach($Procure->getAlreadyAddedItem($LPO_ID) as $Items) : 
                $sub_total = $Items['Quantity'] * $Items['Buying_Price'];
                $grand_total += $sub_total;
            ?>
            <tr>
                <td width='8%' style="padding: 6px;"><center><?=$count++?></center></td>
                <td width='' style="padding: 6px;"><?=$Items['Product_Name'];?></td>
                <td width='12%' style="padding: 6px;"><center><?=$Items['Unit_Of_Measure'];?></center></td>
                <td width='12%' style="padding: 6px;"><center><?=$Items['Quantity'];?></center></td>
                <td width='12%' style="padding: 6px;text-align:end"><?=number_format($Items['Buying_Price'],2)?></td>
                <td width='12%' style="padding: 6px;text-align:end"><?=number_format($sub_total,2)?></td>
            </tr>
            <?php endforeach; ?>
            <tr style="font-weight: 500;">
                <td style="padding: 6px;" colspan="5">GRAND TOTAL</td>
                <td style="padding: 6px;text-align:end"><?=number_format($grand_total,2)?></td>
            </tr>
        </tbody>
    </table>
</fieldset>

<fieldset style="height: 200px;overflow-y:scroll">
    <h5>APPROVAL SUMMARY</h5>
    <table width='100%'>
        <tr style="font-weight: 500;background-color:#ddd">
            <td style="padding: 6px;" width='8%'><center>S/N</center></td>
            <td style="padding: 6px;" width='23%'>Employee Name</td>
            <td style="padding: 6px;" width='23%'>APPROVAL TITLE</td>
            <td style="padding: 6px;" width='23%'>APPROVAL DATE</td>
            <td style="padding: 6px;" width='23%'>STATUS</td>
        </tr>
        <tbody style="background-color:#fff">
            <?php 
                $count = 1;
                $check = "";

                $queryEmp = mysqli_query($conn,"SELECT * FROM tbl_employee emp,tbl_document_approval_level dal,tbl_employee_assigned_approval_level eal WHERE emp.Employee_ID=eal.assgned_Employee_ID AND dal.document_approval_level_id=eal.document_approval_level_id AND document_type='purchase_order' GROUP BY eal.document_approval_level_id") or die(mysqli_error($conn));

                if(mysqli_num_rows($queryEmp) > 0){
                    while($name = mysqli_fetch_assoc($queryEmp)){
                        $no = $name['assigned_approval_level_id'];
                        $nu = $name['document_approval_level_title_id'];
                        $id = $name['Employee_ID'];

                        $sup = mysqli_fetch_assoc(mysqli_query($conn,"SELECT document_approval_level_title FROM tbl_document_approval_level_title WHERE document_approval_level_title_id = '$nu'"))['document_approval_level_title'];
                        $sql_select_approver_result=mysqli_fetch_assoc(mysqli_query($conn,"SELECT date_time,Employee_Name FROM tbl_employee emp,tbl_document_approval_control dac WHERE emp.Employee_ID=dac.approve_employee_id AND emp.Employee_ID = '$id' AND dac.document_number='$purchase_requisition_id' AND dac.document_type = 'purchase_without_order'"));

                        $check_status = (empty($sql_select_approver_result)) ? "<span style='background-color:red;color:white;padding:5px;font-wight:500'><b>Not Approved</b></span>" :"<span style='background-color:green;color:white;padding:5px'><b>Approved</b></span>";
                        
                        echo "<tr>
                                <td style='text-align: center;'>".$count++."</td>
                                <td style='padding:6px'>".$sql_select_approver_result['Employee_Name']."</td>
                                <td style='padding:6px'>".$sup."</td>
                                <td style='padding:6px'>".$sql_select_approver_result['date_time']."</td>
                                <td style='padding:6px'>".$check_status."</td>
                            </tr>";

                        $check_status = "";
                        
                    }
                }else{
                    echo "<tr><td colspan='5' style='text-align:center'><b>No Approval Found</b></td></tr>";
                }
            ?>
        </tbody>
    </table>
    <table class="table" border="0">
        <tr>
            <td><input type="text" placeholder="Username" id="Username" value="" style="text-align:center" /></td>
            <td><input type="password" placeholder="Password" id="Password" value="" style="text-align:center" class="" /></td>
            <td width="10%">
                <input type="button" id='verifyTitles' value="APPROVE LPO" class="art-button-green" onclick='confirm_approval()' />
            </td>
            <td width="10%" style="display: none;">
                <input  type="button" id='cancel_pr' value="DISAPPROVE LPO" onclick="cancelLpoDoc()" class="art-button-green"/>
            </td>
        </tr>
    </table>
</fieldset>

<script>
    function confirm_approval() {
        var Supervisor_Username = document.getElementById("Username").value;
        var Supervisor_Password = document.getElementById("Password").value;
        if (Supervisor_Username == "") {
            $("#Username").css("border", "2px solid red");
        } else if (Supervisor_Password == "") {
            $("#Username").css("border", "");
            $("#Password").css("border", "2px solid red");
        } else {
            $("#Username").css("border", "");
            $("#Password").css("border", "");
            if (confirm("Are you sure you want to approve this Order?")) {
                check_if_valid_user_to_approve_this_document();
            }
        }
    }

    function check_if_valid_user_to_approve_this_document() {
        var Supervisor_Username = document.getElementById("Username").value;
        var Supervisor_Password = document.getElementById("Password").value;
        var purchase_requisition_id = '<?php echo $purchase_requisition_id; ?>';
        $.ajax({
            type: 'GET',
            url: 'verify_approver_privileges_support.php',
            data: 'Username=' + Supervisor_Username + '&Password=' + Supervisor_Password + '&document_number=' + purchase_requisition_id + "&document_type=purchase_requisition",
            cache: false,
            success: function(feedback) {
                if (feedback == 'all_approve_success') {
                    alert("Approved Successfully");
                    $(".remove_btn").hide();
                    approve_lpo_without_req();
                } else if (feedback == "invalid_privileges") {
                    alert("Invalid Username or Password or you do not have enough privilage to approve this LPO");
                } else if (feedback == "fail_to_approve") {
                    alert("Fail to approve..please try again");
                } else {
                    $(".remove_btn").hide();
                    alert(feedback);
                }
            }
        });
    }

    function approve_lpo_without_req(){
        $.ajax({
            type: "POST",
            url: "procurement/procure.common.php",
            data: {ID:<?=$_GET['ID']?>,final_approval_lpo:'final_approval_lpo'},
            success: (response) => {
                location.href = "approve_lpo_without_lpo.php";
            }
        });
    }
</script>

<?php include './includes/footer.php'; ?>