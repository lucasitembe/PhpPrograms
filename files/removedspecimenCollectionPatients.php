<?php
include("./includes/functions.php");
        include("./includes/laboratory_specimen_collection_header.php");
                $DateGiven = date('Y-m-d');
?>
<?php
//get sub department id
$Sub_Department_ID='';
if(isset($_SESSION['Laboratory'])){
    $Sub_Department_Name = $_SESSION['Laboratory'];
    $select_sub_department = mysqli_query($conn,"select Sub_Department_ID from tbl_sub_department where Sub_Department_Name = '$Sub_Department_Name'");
    while($row = mysqli_fetch_array($select_sub_department)){
    $Sub_Department_ID = $row['Sub_Department_ID'];
    }
}else{
    $Sub_Department_ID = '';
}
?>
<fieldset style='margin-top:30px;'>
    <legend id="consultationLablist" style="font-weight: bold;font-size: 16px;background-color: transparent;width: auto"><b>REMOVED PATIENT LIST</b></legend>
<!--                <script language="javascript" type="text/javascript">
                    function searchPatient(Patient_Name=''){
                        var DateGiven_From = '<?php echo $DateGiven?>';
                        var DateGiven_To = '<?php echo $DateGiven?>';
                        var Sub_Department_ID='<?php echo $Sub_Department_ID;?>';

                        document.getElementById('Search_Iframe').innerHTML = 
                             "<iframe width='100%' height=420px src='search_patient_laboratory_list_iframe.php?Patient_Name="+Patient_Name+"&DateGiven_From="+DateGiven_From+"&DateGiven_To="+DateGiven_To+"&Sub_Department_ID="+Sub_Department_ID+"'></iframe>";
                        
                    }
                </script>-->
<!--            <form action="searchpatientlaboratorylistfilter.php?searchpatientlaboratorylistfilterThisPage=ThisPage" method='POST' id="SearchingResults" style="display: none">
                <center>
                    <table  class="hiv_table" style="width:100%;margin-top:5px;">
                        <tr> 
                            <td style="text-align:right;width: 80px;"><b>Date From<b></td>
                            <td width="150px"><input type='text' name='Date_From' id='date_From' required='required'></td>
                            <td style="text-align:right;width: 80px;"><b>Date To<b></td>
                            <td width="150px"><input type='text' name='Date_To' id='date_To' required='required'></td>
                            <td width="50px"><input type="submit" name="submit" value="Filter" class="art-button-green" /></td>
                            <td style="text-align:right;width: 80px;"><b>Search Patient<b></td>
                            <td width="150px"><input type="text" name="search_patient" id="search_patient" onkeyup=""/></td>
                        </tr> 
                    </table>
                </center>
            </form>  -->

<!--            <div id="displaySearchresults" style="display: none">
              <table  class="hiv_table" style="width:100%;margin-top:5px;">
                  <tr> 
                      <td style="text-align:right;width: 80px;"><b>Date From<b></td>
                      <td width="150px"><input type="text" name="Date_From" id="dates_From" required="required"></td>
                      <td style="text-align:right;width: 80px;"><b>Date To<b></td>
                      <td width="150px"><input type="text" name="Date_To" id="dates_To" required="required"></td>
                      <td width="50px"><input type="button" name="submit" value="Filter" class="art-button-green Filter" /></td>
                      <td style="text-align:right;width: 80px;"><b>Search Patient<b></td>
                      <td width="150px"><input type="text" id="searchName" style="width:300px" /></td>

                  </tr> 
              </table>   
            </div>-->
                
                
                </center>
                <center>
                    <hr width="100%">
                </center>

		<center>
                    <table  class="hiv_table" style="width:100%">
                    <tr>
                                <td id='Search_Iframe'>
                                    <div id="iframeDiv" style="height: 420px;overflow-y: auto;overflow-x: hidden">
                                        <?php include 'requests/removedpatientsfromspecimenlist.php';?>
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

   
    <!--<script src="css/jquery.js"></script>-->
<!--    <script src="css/jquery.datetimepicker.js"></script>
   <script src="css/jquery-ui.js"></script>
   <script src="css/jquery-ui.css"></script>
   -->
   
    <script type="text/javascript">  
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
    
    $('#viewPatientCategory').click(function(){
        alert('am visible');
    });
    
    
    $("#patientlist").change(function(){
        $('#consultationLablist').text('UNCONSULTED LAB PATIENT LIST');
        var txt=$(this).val();
        $.ajax({
            type:'POST',
            url:"requests/Search_Inpatient.php",
            data:"action=changed&patient="+txt,
            success:function(html){
               $('#iframeDiv').html(html); 
            }
        });
    });
    
    
    setInterval(
     function(){
//        loadlab();
     },900);
     
     function loadlab(){
      $('#iframeDiv').load("requests/Search_Inpatient.php");   
     }
     
        $('#attendedlist').click(function(){
//            $('#SearchingResults').hide();
//            $('#displaySearchresults').show();
            $('#consultationLablist').text('CONSULTED LAB PATIENT LIST');
            $.ajax({
                type:'POST',
                url:"requests/Search_Inpatient.php",
                data:"viewAttended=attended&",
                success:function(html){
                   $('#iframeDiv').html(html); 
                }
            });
        });

    </script>
    <!--End datetimepicker-->