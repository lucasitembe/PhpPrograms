<?php
include("./includes/functions.php");
//include("./includes/laboratory_specimen_collection_header.php");
include("./includes/header.php");
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
<a href="print_pendnotdone_radiology.php" target="_blank" id="printPreview" class="art-button-green">PRINT REPORT</a>
<a href="radiologyreports.php" class="art-button-green">BACK</a>
<fieldset style='margin-top:15px;'>
    <legend align=right style="background-color:#006400;color:white;padding:5px;"><b>PATIENTS WITH RADIOLOGY ACTIVE / PENDING / NOT DONE  REPORT</b></legend>

    <form action="#" method='POST'>
        <center>
            <table  class="hiv_table" style="width:100%">
                <tr>

                    <td style="text-align: center">
                        <input type="text" autocomplete="off" style='text-align: center;width:15%;display:inline' name='Date_From' id='date_From' placeholder="Start Date"/>
                        <input type="text" autocomplete="off" style='text-align: center;width:15%;display:inline' name='Date_To' id='date_To' placeholder="End Date"/>&nbsp;
                        <select id="sponsorID" style='text-align: center;padding:4px; width:15%;display:inline'>
                            <?php echo $dataSponsor ?>
                        </select>
                        <select id="procedureStatus" style='text-align: center;padding:4px; width:15%;display:inline'>
                            <option value="All" selected="selected">All</option>
                            <option value="active" >Active</option>
                            <option value="pending" >Pending</option>
                            <option value="not done">Not Done</option>
                        </select>
                        <input type="submit" name="submit" id="filterDate" value="Filter" class="art-button-green" />
                    </td>
                </tr>
            </table>
        </center>
    </form>
        <center>
            <table  class="hiv_table" style="width:100%">
                <tr>
                    <td>
                        <div style="width:100%; height:390px;overflow-x: hidden;overflow-y: auto"  id="Search_Iframe">
                            <?php include 'patient_pendnotdone_radiology_iframe.php'; ?> 
                        </div>
                    </td>
                </tr>
            </table>
            
        </center>
        </fieldset>
       
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
    $('#date_From').datetimepicker({value:'',step:1});
    $('#date_To').datetimepicker({
    dayOfWeekStart : 1,
    lang:'en',
    startDate:'now'
    });
    $('#date_To').datetimepicker({value:'',step:1});
    
    $('#filterDate').click(function(e){
        e.preventDefault();
       var fromDate= $('#date_From').val();
       var toDate=$('#date_To').val();
       var procedureStatus = $('#procedureStatus').val();
       var sponsorID = $('#sponsorID').val();
       
       if(fromDate =='' || toDate==''){
           alert('Please enter both date');
           exit;
       }
       
       $('#printPreview').attr('href', 'print_pendnotdone_radiology.php?fromDate=' + fromDate + '&toDate=' + toDate+ '&procedureStatus=' + procedureStatus+ '&sponsorID=' + sponsorID);

        $.ajax({
            type:'GET',
            url:'patient_pendnotdone_radiology_iframe.php',
            data:'action=search&fromDate='+fromDate+'&toDate='+toDate+ '&procedureStatus=' + procedureStatus+ '&sponsorID=' + sponsorID,
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
          $('#patientdoneradiology').dataTable({
            "bJQueryUI": true
          });
      });
    </script>
