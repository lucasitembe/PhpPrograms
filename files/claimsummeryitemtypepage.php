<?php
include("./includes/header.php");
include("./includes/connection.php");
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ./index.php?InvalidPrivilege=yes");
}
if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Quality_Assurance'])) {
        if ($_SESSION['userinfo']['Quality_Assurance'] != 'yes') {
            header("Location: ./index.php?InvalidPrivilege=yes");
        }
    } else {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ./index.php?InvalidPrivilege=yes");
}
if (isset($_GET['Registration_ID'])) {
    $Registration_ID = $_GET['Registration_ID'];
} else {
    $Registration_ID = 0;
}
?>

<style>
    table,tr,td{
        border-collapse:collapse !important;
        border:none !important;

    }
    tr:hover{
        background-color:#eeeeee;
        cursor:pointer;
    }
</style>
 <a href='./previous_invoices.php' class='art-button-green'>
            PREVIOUS INVOICES
        </a>
<?php
if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Quality_Assurance'] == 'yes') {
        ?>
        <a href='./qualityassuarancework.php?QualityAssuranceWork=QualityAssuranceWorkThiPage' class='art-button-green'>
            BACK
        </a>
        <?php
    }
}
?>

<fieldset style='overflow-y: scroll; height: 380px; background-color: white;'>
    <div align="center" style="display: none" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>
    <div id="Transaction_List">
        <table width ='100%'>
            <tr><td colspan="3"><hr></td></tr>
            <tr>
                <td width="5%"><b>SN</b></td>
                <td><b>Item Category</b></td>
                <td width="15%" style="text-align: right;"><b>AMOUNT</b></td>
            </tr>
            <tr><td colspan="3"><hr></td></tr>
        </table>
<!-- <center>
 <table width=100% border=1>
     <tr>
         <td id='Iframe_Area'>
             <iframe width='100%' height='400px' src="eclaim_Iframe.php?Date_From=<?php echo $Date_From; ?>&Date_To=<?php echo $Date_To; ?>&Payment_Type=<?php echo $Payment_Type; ?>&Insurance=<?php echo $Insurance; ?>&Patient_Type=<?php echo $Patient_Type; ?>" ></iframe>
         </td>
     </tr>
 </table>
</center> -->
    </div>
</fieldset>

<script>
    function Filter_Transaction() {
        var month = document.getElementById("month").value;
        var year = document.getElementById("year").value;
        var Sponsor_ID = document.getElementById("Sponsor_ID").value;

        if (Sponsor_ID != null && Sponsor_ID != '' && month != null && month != '' && year != null && year != '') {
            var datastring = 'month=' + month + '&year=' + year + '&Sponsor_ID=' + Sponsor_ID;

            $.ajax({
                type: 'GET',
                url: 'claimsummeryitemtypepage_frame.php',
                data: datastring,
                beforeSend: function (xhr) {
                    $('#progressStatus').show();
                },
                success: function (data) {
                    $('#Transaction_List').html(data);
                }, complete: function (jqXHR, textStatus) {
                    $('#progressStatus').hide();
                }, error: function (jqXHR, textStatus, errorThrown) {
                    $('#progressStatus').hide();
                }
            });
        } else {
            if (Start_Date == '' || Start_Date == null) {
                document.getElementById("date").style = 'border: 3px solid red; text-align: center;';
            }

            if (End_Date == '' || End_Date == null) {
                document.getElementById("date2").style = 'border: 3px solid red; text-align: center;';
            }

            if (Sponsor_ID == '' || Sponsor_ID == null) {
                document.getElementById("Sponsor_ID").style = 'border: 3px solid red';
            }
        }
    }
</script>

<script type="text/javascript">
    function Preview_Details(Folio_Number, Sponsor_ID, Registration_ID, Patient_Bill_ID, Check_In_ID) {
        var winClose = popupwindow('eclaimreport.php?Folio_Number=' + Folio_Number + '&Sponsor_ID=' + Sponsor_ID + '&Registration_ID=' + Registration_ID + '&Patient_Bill_ID=' + Patient_Bill_ID + '&Check_In_ID=' + Check_In_ID, 'e-CLAIM DETAILS', 1200, 500);
    }

    function popupwindow(url, title, w, h) {
        var wLeft = window.screenLeft ? window.screenLeft : window.screenX;
        var wTop = window.screenTop ? window.screenTop : window.screenY;

        var left = wLeft + (window.innerWidth / 2) - (w / 2);
        var top = wTop + (window.innerHeight / 2) - (h / 2);
        var mypopupWindow = window.showModalDialog(url, title, 'dialogWidth:' + w + '; dialogHeight:' + h + '; center:yes;dialogTop:' + top + '; dialogLeft:' + left);
        return mypopupWindow;
    }
</script>

<script>
    $(document).ready(function () {
        $('select').select2();
    })
</script>
<link rel="stylesheet" href="css/select2.min.css" media="screen">
<script src="js/select2.min.js"></script>

<?php
include("./includes/footer.php");
?>