<style>
    .dates{
        color:#cccc00;
    }
    .changepatientstatus{
        width: 100%;
    }
</style>
<?php
include("./includes/header.php");
include("./includes/connection.php");
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'])) {
        if ($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'] != 'yes') {
            header("Location: ./index.php?InvalidPrivilege=yes");
        }
    } else {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_GET['Registration_ID'])) {
    $Registration_ID = $_GET['Registration_ID'];
} else {
    $Registration_ID = '';
}

$months = array('January', 'February', 'March', 'April', 'May', 'June', 'July ', 'August', 'September', 'October', 'November', 'December');
?>
<a href='./generalledgercenter.php' class='art-button-green'>
    BACK
</a>
<br/><br/><br/>
<center>
    <fieldset>  
        <table width="100%">
            <tr>
                <td style="text-align:center">
                    <select id="graph_type" style='text-align: center;width:20%;display:inline' onchange="disable_others(this.value)">
                        <option value='' selected>Select Report Type</option>
                        <option value='monthly'>Monthly</option>
                        <option value='annually'>Annually</option>
                    </select>
                    <select id='graph_category' class="select2-default" style='text-align: center;width:20%;display:inline'>
                        <option value="all">All Category</option>
                        <?php
                        $selectDoctor = mysqli_query($conn,"SELECT Item_Category_ID,Item_Category_Name FROM tbl_item_category  ORDER BY Item_Category_Name ASC
                                ") or die(mysqli_error($conn));

                        while ($data = mysqli_fetch_array($selectDoctor)) {
                            ?>
                            <option value="<?php echo $data['Item_Category_ID']; ?>"><?php echo ucwords(strtolower($data['Item_Category_Name'])); ?></option>
                            <?php
                        }
                        ?>
                    </select>
                    <select id="graph_month" style='text-align: center;width:12%;display:inline'>
                        <option value='' selected>Select Month</option>
<?php
for ($i = 0; $i < count($months); $i++) {
    echo "<option value='" . ($i + 1) . "'>" . $months[$i] . "</option>";
}
?>
                    </select>
                    <select id="graph_year" style='text-align: center;width:12%;display:inline'>
<?php
$selectDoctor = mysqli_query($conn,"SELECT DISTINCT(YEAR(Payment_Date_And_Time)) AS Years FROM tbl_patient_payments ORDER BY YEAR(Payment_Date_And_Time) DESC 
                                ") or die(mysqli_error($conn));

while ($data = mysqli_fetch_array($selectDoctor)) {
    ?>
                            <option><?php echo ucwords(strtolower($data['Years'])); ?></option>
                            <?php
                        }
                        ?>
                    </select>

                    <input type="button" value="Get Graph" class="art-button-green" onclick="get_Graph()">
                    <!--<a href="" id="preview" class="art-button-green" target="_blank">Preview</a>-->
                </td>
            </tr>

        </table>
    </fieldset>  
</center>
<br/>
<fieldset>  
    <legend align=center ><b id="dateRange">REVENUE SUMMARY STATISTICS REPORT</b></legend>
    <center>
        <table width=100% border=1>
            <tr>
                <td >
                    <div align="center" id="progressStatus" style="display:none"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>
                    <div id="Search_Iframe" style="height: 400px;overflow-y: auto;overflow-x: hidden;text-align: center">
                        
                    </div>
                    <input type="hidden" value='' id='querystring'/>
                    <!--<iframe width='100%' height=380px src='doctorcurrentpatientlist_Pre_Iframe.php'></iframe>-->
                </td>
            </tr>
        </table>
    </center>
</fieldset><br/>


<link rel="stylesheet" href="css/select2.min.css" media="screen">
<link rel="stylesheet" href="css/dialog/zebra_dialog.css" media="screen">
<script src="media/js/jquery.js" type="text/javascript"></script>
<script src="js/select2.min.js"></script>
<script src="js/zebra_dialog.js"></script>
<script src="js/ehms_zebra_dialog.js"></script>
<script src="css/jquery-ui.js"></script>

<script>
    function disable_others(type) {
        if (type == 'annually') {
            $('#graph_month,#graph_category').select2('destroy');
            $('#graph_month').prop('disabled', true);
            $('#graph_month').val('');
            $('#graph_category').val('all');
            $('#graph_month,#graph_category').select2();
        } else {
            $('#graph_month').select2('destroy');
            $('#graph_month').prop('disabled', false);
            $('#graph_month').select2();
        }
    }
</script>
<script>
    function get_Graph() {
        var graph_type = $('#graph_type').val();
        var graph_category = $('#graph_category').val();
        var graph_month = $('#graph_month').val();
        var graph_year = $('#graph_year').val();
//alertMsg(msg, title, type, width, overlay_close,auto_close,position,center_buttons, btnText,modal,overlay_opacity,show_close_button) 
        if (graph_type == '') {
            alertMsgSimple("Please select report type", "Field required", "error", 0, false, 'Ok');
            exit;
        }
        if (graph_type == 'monthly' && graph_month == '') {//
            alertMsgSimple("Please select report month", "Field required", "error", 0, false, 'Ok');
            exit;
        }

        var datastring = 'graph_type=' + graph_type + '&graph_category=' + graph_category + '&graph_month=' + graph_month + '&graph_year=' + graph_year ;
        
        $.ajax({
            type: "GET",
            url: "revenusummstats_Iframe.php",
            data: 'prev=&'+datastring,
            beforeSend: function (xhr) {
                $('#progressStatus').show();
                $('#preview').attr('href','revenusummstats_Iframe.php?prev=y&'+datastring);
            },
            success: function (html) {
                    $('#Search_Iframe').html(html);
            }, complete: function (xcf, gfd) {
                $('#progressStatus').hide();
            }
        });
    }
</script>
<script type="text/javascript">
    $(document).ready(function () {
        $('select').select2();
    });

</script>


<?php
include("./includes/footer.php");
?>
