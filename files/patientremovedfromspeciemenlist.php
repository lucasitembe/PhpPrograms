        <?php
            include("./includes/laboratory_result_header.php");
        ?>
        <fieldset style="margin-top:20px;">
        <legend id="resultsconsultationLablist" style="font-weight: bold;font-size:16px;background-color: #006400;width: auto"><b>REMOVED PATIENTS - UNCONSULTED LAB RESULTS</b>  </legend>
        <script language="javascript" type="text/javascript">
        </script>

                <center>
                 <table  class="hiv_table" style="width:100%;margin-top:5px;display: none">
                    <tr> 
                        <td style="width: 10px;text-align: right"><b>Search BarCode</b></td>
                        <td style="width: 20px"><input style="padding-left: 4px" type="text" id="searchbarcode" /></td>
<!--                    <td style="text-align:right;width:10px"><b>Date From<b></td>
                        <td width="70px"><input type='text' name='Date_From' id='date_From' required="required"></td>
                        <td style="text-align:right;width:10px"><b>Date To<b></td>
                        <td width="70px"><input type='text' name='Date_To' id='date_To' required="required"></td>
                        <td width="30px"><input type="submit" name="submit" value="Filter" class="art-button-green" /></td>-->
                    </tr> 
                 </table>
                </center>

 
                    <hr width="100%">
                    <center>
                        <table  class="hiv_table" style="width:100%">
                            <tr>
                                <td>
                                    <div id='Search_Iframe' style="width:100%;height:420px;overflow-x: hidden;overflow-y: auto">
                                        <?php include 'getPatientRemovedfromspeciemenlist.php';?>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </center>
</fieldset>


<div id="labResults" style="display: none">
    <div id="showLabResultsHere"></div>
    
</div>


<div id="labGeneral" style="display: none">
    <div id="showGeneral"></div>
    
</div>
<div id="historyResults1" style="display:none">
    
    
</div>


<?php
    include("./includes/footer.php");
?>
    <script src="css/jquery.js"></script>
    <script src="css/jquery.datetimepicker.js"></script>
    <script src="css/jquery-ui.js"></script>
    <script src="css/scripts.js"></script>
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
//    
    $('#searchbarcode').keydown(function(){
      var value=$(this).val();
      $.ajax({
          type:'POST',
          url:'getPatientfromspeciemenlist.php',
          data:'action=barcode&value='+value,
          success:function(html){
            $('#Search_Iframe').html(html);  
          }
      });
    });
    
     $('#searchbarcode').keyup(function(){
      var value=$(this).val();
      $.ajax({
          type:'POST',
          url:'getPatientfromspeciemenlist.php',
          data:'action=barcode&value='+value,
          success:function(html){
            $('#Search_Iframe').html(html);  
          }
      });
    });

    </script>
    <!--End datetimepicker-->