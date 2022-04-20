<?php
include("./includes/functions.php");

include("./includes/header.php");

if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}

$DateGiven = date('Y-m-d');
?>
<?php
//get sub department id


$query = mysqli_query($conn,"SELECT Sponsor_ID,Guarantor_Name FROM tbl_sponsor") or die(mysqli_error($conn));
$dataSponsor = '';
$dataSponsor.='<option value="All">All Sponsors</option>';

while ($row = mysqli_fetch_array($query)) {
    $dataSponsor.= '<option value="' . $row['Sponsor_ID'] . '">' . $row['Guarantor_Name'] . '</option>';
}


?>
<a href='governmentReports.php?GovernmentReports=GovernmentReportsThisPage' class='art-button-green'>
        BACK
    </a>

<fieldset style='margin-top:15px;'>
    <legend align="right" style="text-align:right;background-color:#006400;color:white;padding:5px;"><b>DHIS2 REPORTS</b></legend>
    <center>
        <table  class="hiv_table" style="width:100%;margin-top:5px;">
            <tr> 
                <td style="width: 20px;text-align:center ">
                    <b>Date From:</b> <input type="text" autocomplete="off" style='text-align: center;width:10%;display:inline' id="date_From" placeholder="Start Date"/>
                    <b>To: </b><input type="text" autocomplete="off" style='text-align: center;width:10%;display:inline' id="date_To" placeholder="End Date"/>&nbsp;
                    <b>Report Type:</b> 
                    <select  style='padding:4px;' id="laboratory_report_type" style='text-align: center;padding:4px; width:20%;display:inline'>
                    <option value="laboratory_test">Laboratory Test</option>
                    <option value="blood_transfusion">Blood Transfusion</option>
                   </select>
                    Select Category: <select id="labSubCatID" style='text-align: center;padding:4px; width:15%;display:inline'>
                    <?php
                     $labQuery = mysqli_query($conn,"SELECT sb.Item_Subcategory_ID,sb.Item_Subcategory_Name FROM `tbl_item_subcategory` sb INNER JOIN tbl_items i ON i.`Item_Subcategory_ID` = sb.`Item_Subcategory_ID` WHERE i.Consultation_Type='Laboratory' GROUP BY i.`Item_Subcategory_ID` ORDER BY sb.Item_Subcategory_Name ") or die(mysqli_error($conn));
                    
                        echo '<option value="All">All Subcategory</option>';
                        while ($row = mysqli_fetch_array($labQuery)){
                            echo '<option value="' . $row['Item_Subcategory_ID'] . '">' . $row['Item_Subcategory_Name'] . '</option>';
                        }
                    ?>
                   </select>
                    
                    <input type="button" name="filter" value="FILTER" class="art-button-green" onclick="Filter_Laboratory();"> 
                </td>
            </tr>

        </table>
    </center>
    <center>
        <table  class="hiv_table" style="width:100%">
            <tr>
                <td>
                    <div style="width:100%; height:500px;overflow-x: hidden;overflow-y: auto;margin: 2px 2px 20px 2px;"  id="Search_Iframe">
                    </div>
                </td>
            </tr>
        </table>

    </center>
</fieldset>

<br/>
<center> 
    <input type="submit"  onclick="Excel_Report();" class="art-button-green" value='DOWNLOAD EXCEL REPORT'>
</center> 

<br/>
<?php
include("./includes/footer.php");
?>

<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
    <script src="js/jquery-1.8.0.min.js"></script>
    <script src="js/jquery-ui-1.8.23.custom.min.js"></script>
    <script src="css/jquery.datetimepicker.js"></script>

<script>
    $('#date_From').datetimepicker({
    dayOfWeekStart: 1,
    lang: 'en',
    startDate: 'now'
    });
    $('#date_From').datetimepicker({value: '', step: 1});
    $('#date_To').datetimepicker({
    dayOfWeekStart: 1,
    lang: 'en',
    startDate: 'now'
    });
    $('#date_To').datetimepicker({value: '', step: 1});
</script>
<script type="text/javascript">
    $("#ipd_report_category").on("change",function(){
        if($("#ipd_report_category").val()=='ipd_attendance'){
            $("#second_row").hide();
        }
        if($("#ipd_report_category").val()=='ipd_diagnosis'){
            $("#second_row").show();
        }
    });

    function Filter_Laboratory(){
        var fromDate=$("#date_From").val();
        var toDate=$("#date_To").val();
        var SubCategory=$("#labSubCatID").val();
        var laboratory_report_type=$("#laboratory_report_type").val();
        if(fromDate.trim()!=='' && toDate.trim()!==''){
                $('#Search_Iframe').html('<div align="center" style="display:block;" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>');
                $.ajax({
                    url:'government_laboratoty_reports.php',
                    type:'post',
                    data:{Filter_Category:'yes',fromDate:fromDate,toDate:toDate,laboratory_report_type:laboratory_report_type,SubCategory:SubCategory},
                    success:function(result){
                        if (result != '') {
                            $('#Search_Iframe').html(result);
                        }
                    }
                });
            }else{
            alert('FILL THE START DATE AND END DATE');
        }
    }
   
</script>
<script type="text/javascript">
    function Excel_Report(){
        var fromDate=$("#date_From").val();
        var toDate=$("#date_To").val();
        var SubCategory=$("#labSubCatID").val();
        var laboratory_report_type=$("#laboratory_report_type").val();
        if(fromDate.trim()!=='' && toDate.trim()!==''){
            window.location.href='print_laboratory_excel_report.php?fromDate=' + fromDate + '&toDate=' + toDate + '&Sponsor=All' + '&SubCategory=' + SubCategory;
        }else{
            alert('FILL THE START DATE AND END DATE');
        }
    }
</script>