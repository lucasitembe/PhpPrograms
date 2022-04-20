<?php
    $location='';
    if(isset($_GET['location']) && $_GET['location']=='otherdepartment'){
       include("./includes/header_general.php"); 
       header("Location:directdepartmentalpayments.php?location=otherdepartment&DirectDepartmentalList=DirectDepartmentalListThisForm");
    }
    include("./includes/connection.php");
    include("./includes/header.php");
    $controlforminput = '';
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    
    if(isset($_SESSION['userinfo']['Revenue_Center_Works'])){
	if($_SESSION['userinfo']['Revenue_Center_Works'] != 'yes' && $_SESSION['userinfo']['Msamaha_Works'] != 'yes'){
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    

    if(isset($_GET['Section'])){
        $Section = "Section=".$_GET['Section']."&";
    }else{
        $Section = '';
    }
?>

<?php
    if(isset($_GET['Section']) && strtolower($_GET['Section']) == 'msamaha'){
        echo "<a href='msamahapanel.php?EditMsamaha=LISTMsamahaPatientsForm' class='art-button-green'>BACK</a>";
    } else if(isset($_GET['from']) && $_GET['from'] == "newApproval") {        
	    echo "<a href='credittransactions.php?from=newApproval' class='art-button-green'>BACK</a>";
    } else{        
	    echo "<a href='./departmentpatientbillingpage.php?DepartmentPatientBilling=DepartmentPatientBillingThisPage' class='art-button-green'>BACK</a>";
    }
?>
    <?php 
        $Today_Date = mysqli_query($conn,"select now() as today");
        while($row = mysqli_fetch_array($Today_Date)){
            $Today = $row['today'];
        }
        $today_start_date=mysqli_query($conn,"select cast(current_date() as datetime)");
        while($start_dt_row=mysqli_fetch_assoc($today_start_date)){
            $today_start=$start_dt_row['cast(current_date() as datetime)'];
        }
    ?>
<style>
    table,tr,td{
       
        border:none !important;
    }
    tr:hover{
        background-color:#eeeeee;
        cursor:pointer;
    }
    .approve_credit_trsns_out_p_bill_tbl table, .approve_credit_trsns_out_p_bill_tbl tr, .approve_credit_trsns_out_p_bill_tbl td{
       border:1px solid #CCCCCC!important; 
    }
</style>
<br/>
<br/>
<center><p style="margin-top:10px;color: #0079AE;font-weight:bold"><i> For Credit Patient Select APPROVE CREDIT TRANSACTION, For Cash/Bill Patient Select APPROVE CASH BILL TRANSACTION </i></p></center>

<fieldset>  
    <legend align="center"><b>CREDIT TRANSACTIONS</b></legend>
    <table width=100%>
        <tr>
            <td width="15%">
                <input type="text" name="Patient_Name" id="Patient_Name" autocomplete="off" onkeyup="filter_patient()"  style="text-align: center;" placeholder="~~~ ~~~ Enter Patient Name ~~~ ~~~">
            </td>
            <td width="15%">
                <input type="text" name="Patient_Number" id="Patient_Number" autocomplete="off" onkeyup="filter_patient()"  style="text-align: center;" placeholder="~~~ Enter Patient Number ~~~">
            </td>
            <!-- <td style="text-align: left; width:7%;"><b>Sponsor Name</b></td> -->
            <td>
                <select name="Sponsor_ID" id="Sponsor_ID" class="form-control" onchange="filter_patient()">
                    <option selected="selected" value="0">~~~ Select Sponsor ~~~</option>
                    <?php
                    $select = mysqli_query($conn,"select Guarantor_Name, Sponsor_ID from tbl_sponsor where Require_Document_To_Sign_At_receiption = 'Mandatory' order by Guarantor_Name") or die(mysqli_error($conn));
                    $num = mysqli_num_rows($select);
                    if ($num > 0) {
                        while ($data = mysqli_fetch_array($select)) {
                            ?>
                            <option value="<?php echo $data['Sponsor_ID']; ?>"><?php echo $data['Guarantor_Name']; ?></option>
                            <?php
                        }
                    }
                    ?>
                </select>
            </td>
            <td>
                <select name="" id="approval_type" onchange="filter_patient()" class="form-control" style='border: 2px solid red !important;'>
                    <option value="approve_credit_transaction">Approve Credit Transaction</option>
                    <option value="approve_outpatient_bill_transaction">Approve Cash  Transaction</option>
                </select>
            </td>
            <!-- <td style='text-align: right;'><b>Start Date</b></td> -->
            <td>
                <input type='text' name='Date_From' id='Date_From' style='text-align: center;' placeholder='Start Date' readonly='readonly' class="form-control" value="<?= $today_start ?>">
            </td>
            <!-- <td style='text-align: right;'><b>End Date</b></td> -->
            <td>
                <input type='text' name='Date_To' id='Date_To' class="form-control" style='text-align: center;' placeholder='End Date' readonly='readonly' value="<?= $Today ?>" >
            </td>
            <td style='text-align: center;' width=7%>
                <input type='button' name='Filter' id='Filter' value='FILTER' class='art-button-green' onclick='filter_patient()'>
            </td>           
        </tr>
        
    </table>
    
</fieldset>
<fieldset>
    <div class="box box-primary" style="height: 450px;overflow-y: scroll;overflow-x: hidden">
        <table class="table">
            <tr>
                <td style="width:50px"><b>S/No</b></td>
                <td><b>Patient Name</b></td>
                <td><b>Patient Reg#</b></td>
                <td><b>Age</b></td>
                <td><b>Gender</b></td>
                <td><b>Sponsor</b></td>
                <td><b>Member Number</b></td>
                <td><b>Sent Date</b></td>
                
            </tr>
            <tbody id='patient_sent_for_approval'>
                
            </tbody>
        </table>
    </div>
</fieldset>
<?php
    include("./includes/footer.php");
?>
<script type="text/javascript">
    $(document).ready(function () {
        $('#consultpatients').DataTable({
            "bJQueryUI": true
        });
        $('#Date_From').datetimepicker({
            dayOfWeekStart: 1,
            lang: 'en',
        });
        $('#Date_From').datetimepicker({value: '', step: 01});
        $('#Date_To').datetimepicker({
            dayOfWeekStart: 1,
            lang: 'en',
        });
        $('#Date_To').datetimepicker({value: '', step: 01});
    });
</script>
    <script>
        function filter_patient(){
            var approval_type = $("#approval_type").val();
            var Patient_Name = $("#Patient_Name").val();
            var Patient_Number = $("#Patient_Number").val();
            var Sponsor_ID  = $("#Sponsor_ID").val();
            var Date_From = $("#Date_From").val();
            var Date_To =$("#Date_To").val();
            var Section ='<?php echo $Section; ?>';
            document.getElementById('patient_sent_for_approval').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';

            $.ajax({
                type:'POST',
                url:'Ajax_credittransactionsapproval.php',
                data:{Sponsor_ID:Sponsor_ID, Date_From:Date_From,Section:Section, Date_To:Date_To, approval_type:approval_type, Patient_Name:Patient_Name, Patient_Number:Patient_Number },
                success:function(responce){
                    $("#patient_sent_for_approval").html(responce);
                }
            });
        }
    </script>

<link rel="stylesheet" type="text/css" href="css/jquery.datetimepicker.css"/>
<link rel="stylesheet" href="media/css/jquery.dataTables.css" media="screen">
<link rel="stylesheet" href="media/themes/smoothness/dataTables.jqueryui.css" media="screen">
<script src="media/js/jquery.js" type="text/javascript"></script>
<script src="media/js/jquery.dataTables.js" type="text/javascript"></script>
<script src="css/jquery.datetimepicker.js" type="text/javascript"></script>
<script src="css/jquery-ui.js"></script>

<script>
    $(document).ready(function () {
        $('select').select2();
        filter_patient();
    })
</script>
<link rel="stylesheet" href="css/select2.min.css" media="screen">
<script src="js/select2.min.js"></script>
