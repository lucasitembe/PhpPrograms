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
            <td width="15%"><input type="text" autocomplete="off" value="<?= $today_start ?>" style='text-align: center;' id="Date_From" placeholder="Start Date"/></td>
            <td width="15%"><input type="text" autocomplete="off" value="<?= $Today ?>" style='text-align: center;' id="Date_To" placeholder="End Date"/>&nbsp;   </td>      
            
            <td width="5%">Service</td>
            <td width="15%">
                <select name='Check_In_Type' id='Check_In_Type'  class="form-control" style="width:100%; text-align:center;">
                    <option selected='selected' value="All">~~~ Select Service ~~~</option>
                   
                    <option value="Doctor Room">Consultation Charges</option>
                    <option value="Pharmacy">Pharmacy</option>
                    <option value="Radiology">Radiology</option>
                    <option value="Laboratory">Laboratory</option>
                    <option value="Sugery">Sugery</option>
                    <option value="Others">Others</option>   
                </select>
            </td>
            <td width="5%">Sponsor</td>
            <td width="15%">
            <select name='Sponsor_ID' id='Sponsor_ID'  style="text-align:center;">
                    <option value="">~~~~~~~~~~ All Sponsors ~~~~~</option>
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
            <td width="10%" style="text-align: right;"><input type="button" value="FILTER" class="art-button-green" onclick="filter_revenue_report()"></td>
            <td style="text-align: right;" width="10%" >
                <!-- <input type="button" name="Report_Button" id="Report_Button" value="REPORT" class="art-button-green" onclick="Preview_Report()"> -->
            </td>
        </tr>       
    </table>
</fieldset>
<fieldset style='overflow-y: scroll; height: 68vh; background-color: white;'>
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
        // var working_department_ipd = $("#working_department_ipd").val();
        var Check_In_Type = $("#Check_In_Type").val();
        var Sponsor_ID = $("#Sponsor_ID").val();
        if(Sponsor_ID==""){
            $("#Sponsor_ID").css("border","2px solid red");

        }else{
        document.getElementById('generalreportdiv').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';

        // alert(Date_From +"=="+Date_To+"----"+working_department_ipd+"---->"+Check_In_Type+"---="+Sponsor_ID)
            $.ajax({
                type:'POST',
                url:'freeservicecost_load.php',
                data:{Sponsor_ID:Sponsor_ID, Date_From:Date_From, Date_To:Date_To,  Check_In_Type:Check_In_Type },
                success:function(responce){
                    $("#generalreportdiv").html(responce);
                }
            });
        }
    }

    $('#Report_Button').click(function(){
        var Date_From = $("#Date_From").val();
        var Date_To = $("#Date_To").val();
        // var working_department_ipd = $("#working_department_ipd").val();
        var Check_In_Type = $("#Check_In_Type").val();
        var Sponsor_ID = $("#Sponsor_ID").val();
       window.open('freeservicecost_pdf.php?working_department_ipd='+working_department_ipd+'&Check_In_Type='+Check_In_Type+'&Date_To='+Date_To+'&Date_From='+Date_From+'&Sponsor_ID='+Sponsor_ID+'');  
    });

    function view_patent_dialog(Item_ID,Sponsor_ID, Product_name){
        var Date_From = $("#Date_From").val();
        var Date_To = $("#Date_To").val();
        $.ajax({
            type:'POST',
            url:'freeservicecost_patients.php',
            data:{
                Item_ID:Item_ID,
                Date_From:Date_From,
                Date_To:Date_To,
                Sponsor_ID:Sponsor_ID,
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
