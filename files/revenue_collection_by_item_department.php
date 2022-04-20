<?php include ("./includes/header.php");
@session_start();
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_SESSION['from']) && $_SESSION['from'] == "ebill") {
    unset($_SESSION['from']);
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
$sql_date_time = mysqli_query($conn, "select now() as Date_Time ") or die(mysqli_error($conn));
while ($date = mysqli_fetch_array($sql_date_time)) {
    $Current_Date_Time = $date['Date_Time'];
}
$Filter_Value = substr($Current_Date_Time, 0, 11);
$Start_Date = $Filter_Value . ' 00:00';
$End_Date = $Current_Date_Time;;
echo '<style>
    table,tr,td{
        border-collapse:collapse !important;
        border:none !important;
    }
    .rows_list{
        cursor: pointer;
    }
    .rows_list:active{
        color: #328CAF!important;
        font-weight:normal!important;
    }
    .rows_list:hover{
        color:#00416a;
        background: #CCCCCC;
        font-weight:bold;
    }
</style>
<a href="generalledgercenter.php" class="art-button-green">BACK</a>
<fieldset>
    <legend align=\'center\'><b>REVENUE COLLECTION BY ITEM DEPARTMENT</b></legend>
    <center><div class="progess_div" id="progess_div_progres"></div></center>
    <div class="row">
        <div class="col-md-12">
            <center>
                <table cla ss="table">
                    <tr>
                        <td><input type="tex" class="form-control" placeholder="Start Date" style="background:#FFFFFF!important" value="';
echo $Start_Date;
echo '" readonly="readonly"id="start_date"></td>
                        <td><input type="tex" class="form-control" placeholder="End Date" style="background:#FFFFFF!important" value="';
echo $End_Date;
echo '"readonly="readonly" id="end_date"></td>
                        <td>
                            <select name="Sponsor_ID" id="Sponsor_ID" onchange="filter_revenue_collection_by_category_report()">
                                    <option value="">All</option>
                                    ';
$select = mysqli_query($conn, "select Guarantor_Name, Sponsor_ID from tbl_sponsor order by Guarantor_Name") or die(mysqli_error($conn));
$num = mysqli_num_rows($select);
if ($num > 0) {
    while ($data = mysqli_fetch_array($select)) {;
        echo '                                                            <option value="';
        echo $data['Sponsor_ID'];;
        echo '">';
        echo $data['Guarantor_Name'];;
        echo '</option>
                                    ';
    }
};
echo '                            </select>
                <select name="Patient_type" id="Patient_type">
                    <option value="">All</option>
                    <option value="Outpatient">Outpatient</option>
                    <option value="Inpatient">Inpatient</option>
                </select>
            </td>
            
                        <td>
                            <a href="#" class="art-button-green" onclick="filter_revenue_collection_by_category_report()">FILTER</a>
                        </td>
                        <td><input type=\'button\' onclick=\'preview_revenue_collection_by_item_category()\' value=\'PREVIEW\' class=\'art-button\'/></td>
                    </tr>
                </table>
            </center>
        </div>
        <div class="col-md-12">
            <div class="box box-primary" style="height:70vh;overflow-y: scroll;overflow-x: hidden">
                <table class="table">
                    <tr>
                        <td style=\'text-align:right;width:50px\'><b>S/No.</b></td>
                        <td><b>DEPARTMENT NAME</b></th>
                        <td colspan=\'6\'><th>
                    </tr>
                    <tbody id=\'item_category_tbl_bdy\'></tbody>
                </table>
            </div>
        </div>
    </div>
</fieldset>
<div id="selected_department_div"></div>
<div id="open_category_detail_div"></div>
<div id="open_item_detail_div"></div>
<div id="open_sub_category_detail_div"></div>
<div id="open_department_detail_div"></div>
<div id="open_sub_department_detail_div"></div>
<script src="css/jquery.js"></script>
<script src="css/jquery.datetimepicker.js"></script>
<script src="css/jquery.js"></script>
<script src="css/jquery.datetimepicker.js"></script>
<script>
    function open_selected_department_details(category_id,Item_Category_Name){
       var start_date= $(\'#start_date\').val();
       var end_date= $(\'#end_date\').val();
       var Sponsor_ID= $(\'#Sponsor_ID\').val();
       var Patient_type = $(\'#Patient_type\').val();
       document.getElementById(\'progess_div_progres\').innerHTML = \'<tr><td colspan="8"><h4 style="color:#099015"><b>Processing...Please Wait . . . </b></h4><div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div></td></tr>\';
       $.ajax({
           type:\'POST\',
           url:\'ajax_item_category_collection_iframe.php\',
           data:{category_id:category_id,start_date:start_date,end_date:end_date,Sponsor_ID:Sponsor_ID,Patient_type:Patient_type},
           success:function(data){
               document.getElementById(\'progess_div_progres\').innerHTML=\'\';
               $("#selected_department_div").html(data);
               $("#selected_department_div").dialog({
                        title: Item_Category_Name+\' Revenue Collection From \'+start_date+ \' To \'+end_date,
                        width: \'90%\',
                        height: 550,
                        modal: true,
                    }); 
                    $("#progress_bar").html("");
           }
       }); 
    }
    
    $(\'#start_date\').datetimepicker({
        dayOfWeekStart: 1,
        lang: \'en\',
        //startDate:    \'now\'
    });
    $(\'#start_date\').datetimepicker({value: \'\', step: 01});
    $(\'#end_date\').datetimepicker({
        dayOfWeekStart: 1,
        lang: \'en\',
        //startDate:\'now\'
    });
    $(\'#end_date\').datetimepicker({value: \'\', step: 01});
    function filter_revenue_collection_by_category_report(){
        var start_date= $(\'#start_date\').val();
        var end_date= $(\'#end_date\').val();
        var Sponsor_ID = $(\'#Sponsor_ID\').val();
        var Patient_type = $(\'#Patient_type\').val();
        document.getElementById(\'item_category_tbl_bdy\').innerHTML = \'<tr><td colspan="8"><h4 style="color:#099015"><b>Processing...Please Wait . . . </b></h4><div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div></td></tr>\';
        $.ajax({
            type:\'POST\',
            url:\'ajax_filter_revenue_collection_by_department_report.php\',
            data:{start_date:start_date,end_date:end_date,Sponsor_ID:Sponsor_ID,Patient_type:Patient_type},
            success:function(data){
                $("#item_category_tbl_bdy").html(data);
            }
        });
    }
    function preview_revenue_collection_by_item_category(){
        var start_date= $(\'#start_date\').val();
        var end_date= $(\'#end_date\').val();
        var Sponsor_ID = $(\'#Sponsor_ID\').val();
        var Patient_type = $(\'#Patient_type\').val();
        window.open("preview_revenue_collection_by_item_department_pdf.php?start_date="+start_date+"&end_date="+end_date+"&Sponsor_ID="+Sponsor_ID+"$Patient_type="+Patient_type,"_blank");
    }
    
    function preview_selected_department_details(Item_Category_ID){
        var start_date= $(\'#start_date\').val();
        var end_date= $(\'#end_date\').val();
        var Sponsor_ID = $(\'#Sponsor_ID\').val();
        window.open("preview_selected_department_details_pdf.php?start_date="+start_date+"&end_date="+end_date+"&Sponsor_ID="+Sponsor_ID+"&Item_Category_ID="+Item_Category_ID,"_blank");
    }
    
    $(document).ready(function(){
        filter_revenue_collection_by_category_report();
    })
</script>
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>
';
include ("./includes/footer.php");