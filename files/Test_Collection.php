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
    <legend align=right style="background-color:#006400;color:white;padding:5px;"><b>PATIENTS TESTS TAKEN REPORT</b></legend>

    <form action="Test_Collection_Filter.php?TodaySampleCollectionThisPage=ThisPage" method='POST'>
        <center>
            <table  class="hiv_table" style="width:100%;margin-top:5px;">
                <tr> 

                    <td style="text-align:right;width: 80px;"><b>Date From<b></td>
                                <td width="150px"><input type='text' name='Date_From' id='date_From' required='required'></td>
                                <td style="text-align:right;width: 80px;"><b>Date To<b></td>
                                <td width="150px"><input type='text' name='Date_To' id='date_To' required='required'></td>
                                <td width="50px"><input type="submit" name="submit" value="Filter" id="DateSubmit" class="art-button-green" /></td>
                </tr> 
            </table>
        </center>
    </form>
        <center>
            <table  class="hiv_table" style="width:100%">
                <tr>
                    <td>
                        <div style="width:100%; height:390px;overflow-x: hidden;overflow-y: auto"  id="Search_Iframe">
                            <?php include 'Test_Collection_Iframe.php'; ?> 
                        </div>
                    </td>
                </tr>
            </table>
            
        </center>
        </fieldset>
<center> 
    <a href="print_test_collection.php" target="_blank" id="printPreview" class="art-button-green">PRINT REPORT</a>
</center> 
       
        <br/>
        <?php
        include("./includes/footer.php");
        ?>

        
<link rel="stylesheet" href="media/css/jquery.dataTables.css" media="screen">
<link rel="stylesheet" href="media/themes/smoothness/dataTables.jqueryui.css" media="screen">
<script src="media/js/jquery.js"></script>
<script src="media/js/jquery.dataTables.js" type="text/javascript"></script>
<script src="css/jquery.datetimepicker.js"></script>

<script>
    $('#date_From').datetimepicker({
    dayOfWeekStart : 1,
    lang:'en',
    startDate:  'now'
    });
    $('#date_From').datetimepicker({value:'',step:30});
    $('#date_To').datetimepicker({
    dayOfWeekStart : 1,
    lang:'en',
    startDate:'now'
    });
    $('#date_To').datetimepicker({value:'',step:30});
    
    $('#DateSubmit').click(function(e){
        e.preventDefault();
       var fromDate= $('#date_From').val();
       var toDate=$('#date_To').val();
       
       $('#printPreview').attr('href', 'print_test_collection.php?fromDate=' + fromDate + '&toDate=' + toDate);

        $.ajax({
            type:'POST',
            url:'Test_Collection_Iframe.php',
            data:'action=search&fromDate='+fromDate+'&toDate='+toDate,
            beforeSend: function (xhr) {
                document.getElementById('Search_Iframe').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';
            },
            success:function(html){
               if (html != '') {
                  $('#Search_Iframe').html(html); 
               }
            }
            
        });
        
    });
    
    
    </script>
    
    <script>
      $(document).ready(function(){
          $('#patientspecimenCollected').dataTable({
            "bJQueryUI": true
          });
      });
    </script>
