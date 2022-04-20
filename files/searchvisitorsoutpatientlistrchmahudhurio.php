<!--<script src="css/jquery.datetimepicker.js" type="text/javascript"></script>-->

<script src="ui2\jquery-ui.js"></script>
<link rel="stylesheet" type="text/css" href="ui2\jquery-ui.css">

<?php
include("./includes/header.php");
include("./includes/connection.php");
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_SESSION['userinfo'])) {
   if(isset($_SESSION['userinfo']['Rch_Works'])){
	    if($_SESSION['userinfo']['Rch_Works'] != 'yes'){
		header("Location: ./index.php?InvalidPrivilege=yes");
	    }
	}else{
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
?>
<?php
echo 'From:<input type="text" id="fromDate" autocomplete="off" style="width:200px;padding-left:4px">';
echo 'To:<input type="text" id="toDate" autocomplete="off" style="width:200px;padding-left:4px">';
?>

<?php
if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Rch_Works'] == 'yes') {
        ?>
        <select id="selectReport" style="height: 27px;border-radius:2px">
            <option value="">
                ~~~~~~~~~Select Report~~~~~~~
            </option>
            <option value="mtotonamama">Mtoto na Mama Baada ya Kujifungua</option>
            <option value="familyplaning">Uzazi wa Mpango</option>
            <option value="wajawazito">Rejesta ya Wajawazito</option>
            <option value="watoto">Rejesta ya Watoto</option>
            <option value="wazazi">Rejesta ya Wazazi</option>
        </select>

        <a href='rchworkspace.php?VisitorForm=VisitorFormThisPage' class='art-button-green'>
            BACK
        </a>
    <?php }
}
?>


<?php
$Today_Date = mysqli_query($conn,"select now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
    $age = $Today - $original_Date;
}
?>

<script type='text/javascript'>
    function access_Denied() {
        alert("Access Denied");
        document.location = "./index.php";
    }
</script>

<br/>

<fieldset>  
    <legend id="legendTital" align=center><b></b></legend>
    <center>
        <table width=100% border=1>
            <tr>
                <td >
                    <div class="tabcontents">
                        <div id="Search_Iframe" style="width:1350px;overflow-x: auto;height: 400px;overflow-y: auto;background-color: rgb(250,250,250)">

                        </div>
                    </div>
                </td>
            </tr>
        </table>
    </center>
</fieldset>
<div id="viewPatientDetails" style="display: none">
    <div id="DisplayHere">


    </div>
</div>
<br/>

<?php
include("./includes/footer.php");
?>

<script>
    $(".tabcontents").tabs();

    $('#fromDate,#toDate').datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd'
    });


    $('#selectReport').on('change', function () {
        var fromDate = $('#fromDate').val();
        var toDate = $('#toDate').val();
        if (fromDate == '' || toDate == '') {
            alert('select Date');
            return false();
        } else {
            var fromDate = $('#fromDate').val();
            var toDate = $('#toDate').val();
            var select = $(this).val();
            if (select == 'mtotonamama') {
                $('#legendTital').html('Mtoto na Mama Baada ya Kujifungua');
            } else if (select == 'familyplaning') {
                $('#legendTital').html('Uzazi wa Mpango');
            } else if (select == 'wajawazito') {
                $('#legendTital').html('Rejesta ya Wajawazito');
            } else if (select == 'watoto') {
                $('#legendTital').html('Rejesta ya Watoto');
            } else if (select == 'wazazi') {
                $('#legendTital').html('Rejesta ya Wazazi');
            } else if (select == '') {
                $('#legendTital').html('');
            }
             $.ajax({
                type: 'POST',
                url: 'requests/view_dhis_reports.php',
                data: 'action=view&fromDate=' + fromDate + '&toDate=' + toDate + '&select=' + select,
                cache: false,
                success: function (html) {
                    $('#Search_Iframe').html(html);
                }
            });
        }
    });
</script>
