<?php 
include("./includes/header.php");
include("./includes/connection.php");
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}

if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['General_Ledger'])) {
        if ($_SESSION['userinfo']['General_Ledger'] != 'yes') {
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
<a href="generalledgercenter.php" class="art-button-green">BACK</a>
<br/>
<br/>
<fieldset> 
    <legend>REVENUE GENERAL REPORT</legend>
    <table width="100%">
        <tr>  
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
            <td width="15%"><input type="text" autocomplete="off" value="<?= $today_start ?>" style='text-align: center; width:50%;' id="Date_From" placeholder="Start Date"/><input type="text" autocomplete="off" value="<?= $Today ?>" style='text-align: center; width:50%;' id="Date_To" placeholder="End Date"/>&nbsp;   </td>      
            
            <td width="100%">
                <!-- <select name="Item_Category_ID" id="Item_Category_ID" style="text-align:center;width:30%;">
					<option selected="selected" value="0">All Category</option>
					<?php
						$select_details = mysqli_query($conn,"select Item_Category_ID, Item_Category_Name from tbl_item_category order by Item_Category_Name") or die(mysqli_error($conn));
		    				$num = mysqli_num_rows($select_details);
		    				if($num > 0){
		    					while ($data = mysqli_fetch_array($select_details)) {
					?>
									<option value="<?php echo $data['Item_Category_ID']; ?>"><?php echo ucwords(strtolower($data['Item_Category_Name'])); ?></option>

					<?php 		}
							}
					?>
				</select> -->
                <select name='Billing_Type' id='Billing_Type' required='required' style="text-align:center;width:20%;">
                    <option value="All">All Billing Type</option>
                    <option value="Credit">Credit</option>
                    <option value="Cash">Cash</option>
                </select>
            
                <select id="working_department_ipd" class="form-control" style="text-align:center;width:15%;">
                    <option  value="All">All department</option>
                        <?php 
                            $sql_select_working_department_result=mysqli_query($conn,"SELECT finance_department_code,finance_department_id,finance_department_name FROM tbl_finance_department WHERE enabled_disabled='enabled'") or die(mysqli_error($conn));
                            if(mysqli_num_rows($sql_select_working_department_result)>0){
                                while($finance_dep_rows=mysqli_fetch_assoc($sql_select_working_department_result)){
                                    $finance_department_id=$finance_dep_rows['finance_department_id'];
                                    $finance_department_name=$finance_dep_rows['finance_department_name'];
                                    $finance_department_code=$finance_dep_rows['finance_department_code'];
                                    echo "<option value='$finance_department_id'>$finance_department_name-->$finance_department_code</option>";
                                }
                            }
                        ?>
                </select>
            
                <select name='Check_In_Type' id='Check_In_Type'  class="form-control" style="width:15% ">
                    <option selected='selected' value="All">All Consultation Type</option>
                    <?php
                    
                    ?>
                    <option value="Doctor Room">Consultation Charges</option>
                    <option value="Pharmacy">Pharmacy</option>
                    <option value="Radiology">Radiology</option>
                    <option value="Laboratory">Laboratory</option>
                    <option value="Surgery">Surgery</option>
                    <option value="Procedure">Procedure</option>
                    <option value="Others">Others</option>   
                </select>
                
                <select name="" id="Item_ID" style="text-align:center; width:20%;">
                    <option value="All">~~~All Service~~~</option>
                    <?php
                        $selectitem = mysqli_query($conn, "SELECT Item_ID, Product_Name FROM tbl_items WHERE Status='Available' ORDER By Consultation_Type DESC  ") or die(mysqli_error($conn));
                        while($rw = mysqli_fetch_assoc($selectitem)){
                            $Item_ID = $rw['Item_ID'];
                            $Product_Name = $rw['Product_Name'];
                            echo "<option value='$Item_ID'>$Product_Name</option>";
                        }
                    ?>
                </select>
                <select name='Status' id='Status'  class="form-control" style="width:10% ">
                    <option selected='selected' value="All">~~~ All service Status~~~</option>
                   
                    <option value="Done">Done</option>
                    <option value="Active">Not  Done</option>
                    <option value="Paid">Paid</option>  
                </select>
                <select name='Sponsor_ID' id='Sponsor_ID'  style="text-align:center; width:15%">
                    <option value="All">~~~All Sponsors ~~</option>
                    <?php
                    $sponsor_results = mysqli_query($conn,"SELECT * FROM tbl_sponsor");
                    while ($sponsor_rows = mysqli_fetch_assoc($sponsor_results)) {
                        ?>
                        <option value='<?php echo $sponsor_rows['Sponsor_ID']; ?>'><?php echo $sponsor_rows['Guarantor_Name']; ?></option>
                        <?php
                    }
                    ?>
                </select>
            </td>
            <td width="10%" style="text-align: right;">
            <input type="button" value="FILTER" class="art-button-green" onclick="filter_revenue_report()">
            <input type="button" class="art-button-green" value="PRINT" onclick="generalreportBypdf()">
            </td>
            
        </tr>       
    </table>
</fieldset>
<fieldset style='overflow-y: scroll; height: 68vh; background-color: white;'>
    <legend align='center'> SERVICE ORDERED REPORT</legend>
    <div id="generalreportdiv">
                    
    </div>
</fieldset>
<div id="patientinservice"></div>
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
    function filter_revenue_report(){
        var Date_From = $("#Date_From").val();
        var Date_To = $("#Date_To").val();
        var working_department_ipd = $("#working_department_ipd").val();
        var Check_In_Type = $("#Check_In_Type").val();
        var Sponsor_ID = $("#Sponsor_ID").val();
        var Item_ID = $("#Item_ID").val();
        var Billing_Type = $("#Billing_Type").val();
        var Status = $("#Status").val();
        if(Sponsor_ID==""){
            $("#Sponsor_ID").css("border","2px solid red");

        }else{
        document.getElementById('generalreportdiv').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';

        // alert(Date_From +"=="+Date_To+"----"+working_department_ipd+"---->"+Check_In_Type+"---="+Sponsor_ID)
            $.ajax({
                type:'POST',
                url:'revenue_general_report_iframe.php',
                data:{Sponsor_ID:Sponsor_ID, Date_From:Date_From,Billing_Type:Billing_Type, Date_To:Date_To, working_department_ipd:working_department_ipd,Item_ID:Item_ID, Check_In_Type:Check_In_Type, Status:Status },
                success:function(responce){
                    $("#generalreportdiv").html(responce);
                }
            });
        }
    }

    function generalreportBypdf(){
        var Date_From = $("#Date_From").val();
        var Date_To = $("#Date_To").val();
        var working_department_ipd = $("#working_department_ipd").val();
        var Check_In_Type = $("#Check_In_Type").val();
        var Sponsor_ID = $("#Sponsor_ID").val();
        var Item_ID = $("#Item_ID").val();
        var Billing_Type = $("#Billing_Type").val();
        var Status = $("#Status").val();
       window.open('revenue_general_report_iframe_pdf.php?working_department_ipd='+working_department_ipd+'&Check_In_Type='+Check_In_Type+'&Date_To='+Date_To+'&Date_From='+Date_From+'&Sponsor_ID='+Sponsor_ID+'&Status='+Status+'&Billing_Type='+Billing_Type+'&Item_ID='+Item_ID);  
    };

    function view_patent_dialog(Item_ID, Product_name){
        var Date_From = $("#Date_From").val();
        var Date_To = $("#Date_To").val();
        var Billing_Type = $("#Billing_Type").val();
        var Status = $("#Status").val();
        var Check_In_Type = $("#Check_In_Type").val();
        var Sponsor_ID = $("#Sponsor_ID").val();
        var working_department_ipd = $("#working_department_ipd").val();
        $.ajax({
            type:'POST',
            url:'revenue_general_report_iframe_patients.php',
            data:{
                Item_ID:Item_ID,
                Date_From:Date_From,
                Date_To:Date_To,
                Product_name:Product_name,
                Billing_Type:Billing_Type,Sponsor_ID:Sponsor_ID,working_department_ipd:working_department_ipd, Check_In_Type:Check_In_Type, Status:Status
                },
            success:function(responce){                
                $("#patientinservice").dialog({
                    title: 'PATIENTS GIVEN '+Product_name,
                    width: '80%',
                    height: 550,
                    modal: true,
                });
                $("#patientinservice").html(responce);
            }
        });
    }

    function print_by_Item(Item_ID, Product_name){
        var Date_From = $("#Date_From").val();
        var Date_To = $("#Date_To").val();
        var working_department_ipd = $("#working_department_ipd").val();
        var Check_In_Type = $("#Check_In_Type").val();
        var Sponsor_ID = $("#Sponsor_ID").val();
        var Billing_Type = $("#Billing_Type").val();
        var Status = $("#Status").val();
        window.open('revenue_general_report_byservice_pdf.php?working_department_ipd='+working_department_ipd+'&Check_In_Type='+Check_In_Type+'&Date_To='+Date_To+'&Date_From='+Date_From+'&Sponsor_ID='+Sponsor_ID+'&Status='+Status+'&Billing_Type='+Billing_Type+'&Item_ID='+Item_ID+'&Product_name='+Product_name);
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
    })
</script>
<link rel="stylesheet" href="css/select2.min.css" media="screen">
<script src="js/select2.min.js"></script>
