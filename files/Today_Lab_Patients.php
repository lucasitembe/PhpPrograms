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
<fieldset style='margin-top:10px;'>
                <legend align=right><b>TODAY'S LABORATORY PATIENT LIST</b></legend>
                <script language="javascript" type="text/javascript">
                    $('#patientlist').change(function(){
                        var txt=$(this).val();
                        $.ajax({
                           type:'POST',
                           url:'Today_Lab_Patients_Iframe.php',
                           data:'action=report&txt='+txt,
                           success:function(html){
                               $('#Search_Iframe').html(html);
                             // alert('html');
                           }
                        }); 
                     });
                     
                    
                </script>



<form action="Today_Lab_Patients_Filter.php?TodayLaboratoryPatientThisPage=ThisPage" method='POST'>
<center>
    <table  class="hiv_table" style="width:100%;margin-top:5px;">
        <tr> 
                <td style="text-align:right;width: 80px;"><b>Date From<b></td>
                <td width="150px"><input type='text' name='Date_From' id='date_From' required='required'></td>
                <td style="text-align:right;width: 80px;"><b>Date To<b></td>
                <td width="150px"><input type='text' name='Date_To' id='date_To' required='required'></td>
                <td width="50px"><input type="submit" id="filterSubmit" name="submit" value="Filter" class="art-button-green" /></td>
        </tr> 
    </table>
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
				<div style="width:100%;height:480px;overflow-y: auto;overflow-x: hidden" id='Search_Iframe'>
					<?php include_once 'Today_Lab_Patients_Iframe.php';?>
				</div>
					
			</td>
		</tr>
	</table>
</center>
</fieldset>
<center><a href="#" class="art-button-green">PRINT REPORT</a></center>
 <?php
   //include("./includes/footer.php");
                                                            ?>
    <!--<script src="css/jquery.js"></script>-->
    <link rel="stylesheet" href="media/css/jquery.dataTables.css" media="screen">
    <link rel="stylesheet" href="media/themes/smoothness/dataTables.jqueryui.css" media="screen">
    <script src="media/js/jquery.js"></script>
    <script src="media/js/jquery.dataTables.js" type="text/javascript"></script>
    <script>
        $(document).ready(function(){
            $('#attendedlist').hide();
            $('.hidemehere').hide();
        });
    </script>
    <script>
        $('#labPatients').dataTable({
          "bJQueryUI":true, 
      });
    </script>                      
    <script src="css/jquery.datetimepicker.js"></script>
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
    
    
    
     $('#filterSubmit').click(function(e){
        e.preventDefault();
        var patientlist=$('#patientlist').val();
        var date_From=$('#date_From').val();
        var date_To=$('#date_To').val();
                $.ajax({
                    type:'POST',
                    url:'Today_Lab_Patients_Iframe.php',
                    data:'action=filter&patientlist='+patientlist+'&date_From='+date_From+'&date_To='+date_To,
                    success:function(html){
                       $('#Search_Iframe').html(html);
                    }
                });
         });
    </script>
	<!--End datetimepicker-->