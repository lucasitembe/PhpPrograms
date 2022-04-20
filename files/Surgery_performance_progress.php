<?php
include("includes/connection.php");
include("includes/header.php");
if(!isset($_SESSION['userinfo'])){
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if(isset($_SESSION['userinfo'])){
// if(isset($_SESSION['userinfo']['Theater_Works'])){
//     if($_SESSION['userinfo']['Theater_Works'] != 'yes'){
//     header("Location: ./index.php?InvalidPrivilege=yes");
//     }else{
//         @session_start();
//         if(!isset($_SESSION['Theater_Supervisor'])){ 
//             header("Location: ./deptsupervisorauthentication.php?SessionCategory=Surgery&InvalidSupervisorAuthentication=yes");
//         }
//     }
// }else{
//     header("Location: ./index.php?InvalidPrivilege=yes");
// }
}else{
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}

$Sub_Department_Name = $_SESSION['Theater_Department_Name'];
$Current_Employee_ID = $_SESSION['userinfo']['Employee_ID'];

$Today_Date = mysqli_query($conn, "SELECT now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
    $Leohii = $Today." 00:00";
    // $age ='';
    $thisDate = date('l jS, F Y', strtotime($Today)) . '';

}

$Surgery_done = mysqli_query($conn, "SELECT COUNT(ilc.Payment_Item_Cache_List_ID) AS Surgeries FROM tbl_item_list_cache ilc, tbl_payment_cache pp WHERE pp.Payment_Cache_ID = ilc.Payment_Cache_ID AND ilc.Status IN('Active','paid', 'served') AND DATE(ilc.Service_Date_And_Time) = CURDATE() AND ilc.Payment_Item_Cache_List_ID IN(SELECT Payment_Item_Cache_List_ID FROM tbl_surgery_appointment WHERE Surgery_Status = 'completed')");
while($rows = mysqli_fetch_assoc($Surgery_done)){
    $Surgeries_done = $rows['Surgeries']; 
}
?>
<!-- <a href='theaterworkspage.php?TheaterWorkPage=TheaterWorkPageThisPage' class='art-button-green'>
    BACK
</a> -->

<input type="button" value="BACK" onclick="history.go(-1)" class="art-button-green">


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
<link rel='stylesheet' href='fixHeader.css'>
<a href='#' style='float: right;color: white;font-weight:bold; background: #006400 ; padding: 10px; border-radius: 5px; font-size: 15px; text-decoration: none;' onclick='completed_list()'>Completed Surgery <span style='background-color:red; padding:2px 5px 2px 5px; color:#fff; font-size:16px; border-radius:9px;'><?php echo $Surgeries_done; ?></span></a>

<br/>
<br/>

<fieldset width=''>
    <legend style='font-size: 17px;'align='center'>TODAY SURGERY LIST - <?php echo $thisDate ?></legend>
<div class="box box-primary" style="height: 620px;overflow-y: scroll;overflow-x: hidden;" id='scroll_bar'>
        <table class="table table-collapse table-striped fixTableHead" style="border-collapse: collapse;border:1px solid black">
            <tr  style='background: #dedede;'>
                <td style="width:30px"><h5><b>S/NO</b></h5></td>
                <td><h5><b>PATIENT NAME</b></h5></td>
                <td><h5><b>PATIENT #</b></h5></td>
                <td><h5><b>GENDER</b></h5></td>
                <td><h5><b>AGE</b></h5></td>
                <td style='width: 13%;'><h5><b>DIAGNOSIS</b></h5></td>
                <td style='width: 10%;'><h5><b>SURGERY</b></h5></td>
                <td><h5><b>SURGEON</b></h5></td>
                <td><h5><b>ANAESTHESIA TYPE</b></h5></td>
                <td><h5><b>TIME</b></h5></td>
                <td><h5><b>THEATER</b></h5></td>
                <td><h5><b>ROOM</b></h5></td>
                <td><h5><b>STATUS</b></h5></td>
            </tr>
            <tbody id='scroll_bar' class='patient_sent_to_cashier_tbl'>
                
            </tbody>
        </table>
    </div>
    <div style='text-align: center;'>
    <span style='font-weight: bold;'><h3>COLOR CODE KEY: 
    <input type="button"  style='background: #dedede; padding: 2px 10px; border: 1px solid #000;font-weight: bold;' title='This color will only appear to the Regular Surgeries' value='REGULAR SURGERY' name="" id="">
    <input type="button"  style='background: #bd0d0d; padding: 2px 10px; color: #fff; border: none;font-weight: bold;' title='This color will only appear to the Emergency Surgeries' value='EMERGENCY SURGERY' name="" id="">
    </h3><span>
    </div>
    <!-- <center>
    <p style="margin:3px;color: #bd0d0d;font-weight:bold; font-size: 14px;"><i> The User Logged IN, will be required to Logout end of his/her shift </i></p>
    </center> -->
</fieldset>
<div id="loading_data">
    <center id='Appointment_area'>
        
    </center>
</div>


<script>
    $(document).ready(function () {
        filter_list_of_patient_sent_to_cashier();
        $("#scroll_bar").animate({scrollTop: 3000}, 300000);
    });
    $(document).ready(function () {
        $("#loading_data").dialog({autoOpen: false, width: '85%', height: 650, title: 'COMPLETED OPERATION LIST', modal: true});
    });
    function filter_list_of_patient_sent_to_cashier(){
        Sub_Department_Name = '<?= $Sub_Department_Name ?>';
        document.getElementsByClassName('patient_sent_to_cashier_tbl').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';
            $.ajax({
                type:'POST',
                url:'ajax_surgery_progress_status.php',
                data:{Sub_Department_Name:Sub_Department_Name},
                cache: false,
                    success:function(data){
                        $(".patient_sent_to_cashier_tbl").html(data);
                    }
                });
            }


     var time = new Date().getTime();
     $(document.body).bind("mousemove keypress", function(e) {
         time = new Date().getTime();
     });

     function refresh() {
         if(new Date().getTime() - time >= 60000) 
             window.location.reload(true);
            //  filter_list_of_patient_sent_to_cashier();
         else 
             setTimeout(refresh, 180000);
     }
     setTimeout(refresh, 180000);

     function completed_list(){
        Current_Employee_ID = '<?= $Current_Employee_ID ?>';
        if (window.XMLHttpRequest) {
            myObjectPost = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectPost = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectPost.overrideMimeType('text/xml');
        }

        myObjectPost.onreadystatechange = function () {
            dataPost = myObjectPost.responseText;
            if (myObjectPost.readyState == 4) {
                document.getElementById('Appointment_area').innerHTML = dataPost;
                $("#loading_data").dialog("open");
            }
        }; //specify name of function that will handle server response........

        myObjectPost.open('GET', 'ajax_completed_surgery.php?Current_Employee_ID='+Current_Employee_ID, true);
        myObjectPost.send();
     }
</script>
<script src="js/select2.min.js"></script>
<link rel="stylesheet" href="css/uploadfile.css" media="screen">
<link rel="stylesheet" href="media/css/jquery.dataTables.css" media="screen">
<link rel="stylesheet" href="media/themes/smoothness/dataTables.jqueryui.css" media="screen">
<link rel="stylesheet" href="js/fancybox/jquery.fancybox.css" type="text/css" media="screen" />   
<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script><script src="css/jquery.datetimepicker.js"></script>
<script src="css/jquery.js"></script>
<script src="css/jquery.datetimepicker.js"></script>
<script src="css/jquery-ui.js"></script>
<script src="css/scripts.js"></script>
<script src="js/jquery.form.js"></script>
<script type="text/javascript" src="js/fancybox/jquery.fancybox.pack.js"></script>
<script src="media/js/jquery.dataTables.js" type="text/javascript"></script>
<link rel="stylesheet" href="css/ui.notify.css" media="screen">
<script src="js/jquery.notify.min.js"></script>
<?php
include("includes/footer.php");
?>