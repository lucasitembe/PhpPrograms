<?php
include("./includes/functions.php");
//include("./includes/laboratory_specimen_collection_header.php");
include("./includes/header.php");
$DateGiven = date('Y-m-d');
?>
<?php
//get sub department id
$Sub_Department_ID = '';
if (isset($_SESSION['Laboratory'])) {
    $Sub_Department_Name = $_SESSION['Laboratory'];
    $select_sub_department = mysqli_query($conn,"select Sub_Department_ID from tbl_sub_department where Sub_Department_Name = '$Sub_Department_Name'");
    while ($row = mysqli_fetch_array($select_sub_department)) {
        $Sub_Department_ID = $row['Sub_Department_ID'];
    }
} else {
    $Sub_Department_ID = '';
}

?>
<a href="Laboratory_Reports.php?LaboratoryReportThisPage=ThisPage" class="art-button-green">BACK</a>
<fieldset style='margin-top:15px;'>
    <legend align="right" style="background-color:#006400;color:white;padding:5px;"><b>EPISODE OF CARE REPORT</b></legend>
    <link rel="stylesheet" type="text/css" href="css/jquery.datetimepicker.css"/>
    <script src="css/jquery.datetimepicker.js" type="text/javascript"></script>

    <form action="#" method='POST'>
        <center>
            <table  class="hiv_table" style="width:100%">
                <tr>

                    <td style="text-align: center">
                        <input type="text" autocomplete="off" style='text-align: center;width:15%;display:inline' name='Date_From' id='date_From' placeholder="Start Date"/>
                        <input type="text" autocomplete="off" style='text-align: center;width:15%;display:inline' name='Date_To' id='date_To' placeholder="End Date"/>&nbsp;
                        <input type="text" name="Product Name" id="Product_Name" style='text-align: center;width:15%;display:inline' placeholder="Test Name" onkeyup="filter_TAT_list()">
                        Select Category: <select id="labSubCategoryID" onchange='filter_TAT_list()' style='text-align: center;padding:4px; width:15%;display:inline'>
                        <?php
                         $labQuery = mysqli_query($conn,"SELECT sb.Item_Subcategory_ID,sb.Item_Subcategory_Name FROM `tbl_item_subcategory` sb INNER JOIN tbl_items i ON i.`Item_Subcategory_ID` = sb.`Item_Subcategory_ID` WHERE i.Consultation_Type='Laboratory' GROUP BY i.`Item_Subcategory_ID` ORDER BY sb.Item_Subcategory_Name ") or die(mysqli_error($conn));

                            echo '<option value="All">All Subcategory</option>';
                            while ($row = mysqli_fetch_array($labQuery)){
                                echo '<option value="' . $row['Item_Subcategory_ID'] . '">' . $row['Item_Subcategory_Name'] . '</option>';
                            }
                        ?>
                       </select>Select Sponsor: <select id="sponsorID" onchange='filter_TAT_list()' style='text-align: center;padding:4px; width:15%;display:inline'>
                        <?php
                         $Sponspor_filter = mysqli_query($conn,"SELECT Guarantor_Name, Sponsor_ID FROM tbl_sponsor WHERE active_sponsor = 'active'") or die(mysqli_error($conn));

                            echo '<option value="All">All Sponsors</option>';
                            while ($row = mysqli_fetch_array($Sponspor_filter)){
                                $Guarantor_Name = $row['Guarantor_Name'];
                                $Sponsor_ID = $row['Sponsor_ID'];
                                echo '<option value="' . $Sponsor_ID . '">' . $Guarantor_Name . '</option>';
                            }
                        ?>
                       </select>
                        <input type="button" name="submit" value="Filter" onclick='filter_TAT_list()' class="art-button-green" />
                        <input type='button' onclick='print_excell()' class="art-button-green" value='PRINT REPORT EXCEL'>
                    </td>
                </tr></table>
        </center>
    </form>

</center>
<center>
    <hr width="100%">
</center>

<center>
    <table  class="hiv_table" style="width:100%">
        <tr>
            <td>
                <div style="width:100%; height:380px;overflow-x: hidden;overflow-y: auto" id='Search_Iframe'>
                </div>
            </td>
        </tr>
    </table>
</center>
</fieldset>


<?php
include("./includes/footer.php");
?>
<script type="text/javascript">

    $(document).ready(function (e){
        filter_TAT_list();
        $("#sponsorID").select2();
        $("#labSubCategoryID").select2();
    });
    function filter_TAT_list(){

        // alert("MIMI");
        // exit();
        var fromDate = $('#date_From').val();
        var toDate = $('#date_To').val();
        var sponsorID = $('#sponsorID').val();
        var labSubCategoryID = $('#labSubCategoryID').val();
        Product_Name = $("#Product_Name").val();

        // if (fromDate == '' || toDate == '') {
        //     alert('Please enter both dates to filter');
        //     exit;
        // }
        $.ajax({
            type: 'GET',
            url: 'episode_of_care_lab_result_iframe.php',
            data: 'filterlabpatientdate=true&fromDate=' + fromDate + '&toDate=' + toDate + '&sponsorID=' + sponsorID + '&labSubCategoryID=' + labSubCategoryID + '&Product_Name='+Product_Name,
            beforeSend: function (xhr) {
                document.getElementById('Search_Iframe').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';
            },
            success: function (html) {
                if (html != '') {
                    $('#Search_Iframe').html(html);
                    $('#patient-lab-result').dataTable({
                        "bJQueryUI": true
                    });
                }
            }
        });


    };


    function print_excell(){
            var fromDate = $('#date_From').val();
            var toDate = $('#date_To').val();
            var sponsorID=$('#sponsorID').val();
            var labSubCategoryID=$('#labSubCategoryID').val();
            var Product_Name=$('#Product_Name').val();

            // check dates 
            if(date_From == ""){
                alert("Enter Start Date");
                exit;
            }else if(date_To == ""){
                alert("Enter End Date");
                exit;
            }else{
                window.open("Episode_of_care_Excell_Report.php?fromDate="+fromDate+"&toDate="+toDate + "&sponsorID=" + sponsorID + "&Product_Name=" + Product_Name + "&labSubCategoryID=" + labSubCategoryID);
            }
        }

    $('#date_From').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en',
        startDate: 'now',
        dateFormat: 'yy-mm-dd'
    });
    $('#date_From').datetimepicker({value: '', step: 30});
    $('#date_To').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en',
        startDate: 'now',
        dateFormat: 'yy-mm-dd'
    });
    $('#date_To').datetimepicker({value: '', step: 30});
</script>
<!--End datetimepicker-->
