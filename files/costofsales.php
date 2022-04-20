<?php
include("./includes/header.php");
include_once ("./functions/items.php");

@session_start();
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
if (isset($_GET['Section']) && $_GET['Section'] == 'managementworkspage') {

    $_SESSION['Section_managementworkspage'] = true;
}

if (isset($_SESSION['Section_managementworkspage']) && $_SESSION['Section_managementworkspage'] == true) {

    echo '<a href="managementworkspage.php?ManagementWorksPage=ThisPage" style=""><button type="button" class="art-button-green">Back</button></a>
';
} else {
    echo '<a href="generalledgercenter.php" style=""><button type="button" class="art-button-green">BACK</button></a>
';
}

$Sub_Department_Query = "SELECT sd.Sub_Department_ID, sd.Sub_Department_Name
                                                                FROM tbl_sub_department sd
                                                                ORDER BY Sub_Department_Name";

$Sub_Department_List = mysqli_query($conn,$Sub_Department_Query) or die(mysqli_error($conn));

$classifications = Get_Item_Classification();

$Start_Date=$_GET['Start_Date'];
$End_Date=$_GET['End_Date'];
//$Sub_Department_ID=$_GET['Sub_Department_ID'];

?>
<script type='text/javascript'>
    function access_Denied() {
        alert("Access Denied");
        document.location = "./index.php";
    }
</script>

<script type="text/javascript">
    function Warning() {
        alert("This report is under construction. Please use the report named REVENUE COLLECTION - DETAIL instead");
    }
</script>

<style>
    table,tr,td{
        border-collapse:collapse !important;
        border:none !important;
    }
    .inputCost{
        margin-bottom:15px; 
        text-align: center;
        width:100%;
    }
    .hover a{
        text-decoration: none;
    }
    tr.hover:hover{
        background-color: #CCC;  
    }
</style>
<br/><br/>
<center>
    <fieldset>
        <legend align='right' style="background-color:#006400;color:white;padding:5px;">
            <b>COST OF SALE SUMMARY REPORT</b>
        </legend>
        <table width="100%">
            <tr>
                <td  style="text-align: center;width:30%;background-color:white" >
                    <table width="100%">
                        <tr>
                            <td> <input type="text" name="Start_Date" class="inputCost" id="date" placeholder="Start Date" value="<?php echo $Start_Date ?>"></td>
                        </tr>
                        <tr>
                            <td> <input type="text" name="End_Date" class="inputCost" id="date2" placeholder="End Date" value="<?php echo $End_Date ?>" ></td>
                        </tr>
                        <tr>
                            <td style="text-align: center;">  
                                <select name='Item_Classification' style="text-align: center;" class="inputCost" id='Item_Classification' onchange='filter_cost_of_sale()' >
                                    <option  selected value="All">All Classification</option>
                                    <?php
                                    foreach ($classifications as $value) {
                                        echo "<option value='" . $value['Name'] . "' > " . $value['Name'] . " </option>";
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: center;">   
                                <select name="Sub_Department_ID" style="text-align: center;" class="inputCost" id="Sub_Department_ID" onchange="filter_cost_of_sale();" >
                                    <option selected value="All">All Departments</option>
                                    <?php
                                    while ($rowSub = mysqli_fetch_array($Sub_Department_List)) {
                                        echo "<option value='" . $rowSub['Sub_Department_ID'] . "' > " . $rowSub['Sub_Department_Name'] . " </option>";
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: center;">  
                                <input type="button" name="Search" id="Search" value="SEARCH" class="art-button-green" onclick="filter_cost_of_sale();" />
                                <a href="costofsaleprint.php" id="Preview" value="PREVIEW" target="_blank" class="art-button-green" >PREVIEW</a>
                            </td>
                        </tr>
                    </table>
                </td>
                <td>
                    <div align="center"  id="progressStatus"  style="display: none "><img src="images/ajax-loader_1.gif" style="border-color:white;"></div>
                    <div id='Search_Iframe' style="background-color:white;width: 100%;">
                        <?php include 'costofsale_frame.php' ?>
                    </div>
                </td>
            </tr> 
            </tr>
        </table>
    </fieldset><br/>
</center>    

<link rel="stylesheet" href="css/select2.min.css" media="screen">
<link rel="stylesheet" href="css/dialog/zebra_dialog.css" media="screen">
<script src="media/js/jquery.js" type="text/javascript"></script>
<script src="css/jquery-ui.js"></script>
<script src="js/select2.min.js"></script>
<script src="js/zebra_dialog.js"></script>
<script src="js/ehms_zebra_dialog.js"></script>
<script src="js/functions.js"></script>

<script type='text/javascript'>
    $(document).ready(function () {
        $('select').select2();
    });
</script>
 
<script>
    function filter_cost_of_sale() {
        var Start_Date = $('#date').val();
        var End_Date = $('#date2').val();
        var Item_Classification = $('#Item_Classification').val();
        var Sub_Department_ID = $('#Sub_Department_ID').val();

        if (Start_Date == '' || End_Date == '') {
           // alertMsg(msg, title, type, width, overlay_close,auto_close,position,center_buttons, btnText,modal,overlay_opacity,show_close_button)
              alertMsg("All dates are required", "Invalid Data", 'error', 0, false, false, "", true, "Ok", true, .5, true);
              if(Start_Date == ''){ $('#date').css('border','1px solid red');}else{$('#date').css('border','1px solid #ccc');}
              if(End_Date == ''){ $('#date2').css('border','1px solid red');}else{$('#date2').css('border','1px solid #ccc');}
             
              exit;
        }
        
        $('#date').css('border','1px solid #ccc');
        $('#date2').css('border','1px solid #ccc');

        $('#Preview').attr('href', 'costofsaleprint.php?Start_Date=' + Start_Date + '&End_Date=' + End_Date + '&Item_Classification=' + Item_Classification + '&Sub_Department_ID=' + Sub_Department_ID);

        $.ajax({
            type: 'GET',
            url: 'costofsale_frame.php',
            data: 'Start_Date=' + Start_Date + '&End_Date=' + End_Date + '&Item_Classification=' + Item_Classification + '&Sub_Department_ID=' + Sub_Department_ID,
            beforeSend: function (xhr) {
                $("#progressStatus").show();
            },
            success: function (html) {
                $("#Search_Iframe").html(html);
            }, complete: function () {
                $("#progressStatus").hide();
            }, error: function (html, jjj) {
                alert(html);
            }
        });

    }
</script>

<?php
include("./includes/footer.php");
?>
