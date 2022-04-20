<?php
include("./includes/functions.php");

include("./includes/header.php");

if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}

?>

<a href='managementworkspage.php?DhisWork=DhisWorkThisPage' class='art-button-green'> BACK </a>

<fieldset style='margin-top:15px;'>
    <legend align="right" style="text-align:right;background-color:#006400;color:white;padding:5px;"><b>PATIENTS ON CALL REPORT</b></legend>
    <center>
        <table  class="hiv_table" style="width:100%;margin-top:5px;">
            <tr> 
                <td style="width: 20px;text-align:center ">
                    <b>Date From:</b> <input type="text" autocomplete="off" style='text-align: center;width:10%;display:inline' id="date_From" placeholder="Start Date"/>
                    <b>To: </b><input type="text" autocomplete="off" style='text-align: center;width:10%;display:inline' id="date_To" placeholder="End Date"/>&nbsp;
                    
                     <b>SPONSOR:</b>
                        		<select name="Sponsor_ID" id="Sponsor_ID">
        			<option selected="selected" value="all">All</option>
<?php
					$select = mysqli_query($conn,"select Sponsor_ID, Guarantor_Name from tbl_sponsor order by Guarantor_Name") or die(mysqli_error($conn));
					$num = mysqli_num_rows($select);
					if($num > 0){
						while ($data = mysqli_fetch_array($select)) {
?>
							<option value="<?php echo $data['Sponsor_ID']; ?>"><?php echo $data['Guarantor_Name']; ?></option>
<?php
						}
					}
?>
        		</select>
                <b>Employee</b>
                <select name="" id="Employee_ID">
                    <option value="All">All</option>
                    <?php
					$select = mysqli_query($conn,"SELECT Employee_ID,  Employee_Name FROM tbl_employee WHERE Employee_Type IN ('Doctor' OR 'Nurse')") or die(mysqli_error($conn));
					$num = mysqli_num_rows($select);
					if($num > 0){
						while ($data = mysqli_fetch_array($select)) {
                    ?>
							<option value="<?php echo $data['Employee_ID']; ?>"><?php echo $data['Employee_Name']; ?></option>
                    <?php
						}
					}
                    ?>
                </select>
                
                    
                    <input type="button" name="filter" value="FILTER" class="art-button-green" onclick="filter_data();"> 
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

<?php
include("./includes/footer.php");
?>

<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
    <script src="js/jquery-1.8.0.min.js"></script>
    <script src="js/jquery-ui-1.8.23.custom.min.js"></script>
    <script src="css/jquery.datetimepicker.js"></script>
    
    <link rel="stylesheet" href="css/select2.min.css" media="screen">
<script src="js/select2.min.js"></script> 

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

    function filter_data(){
        var fromDate=$("#date_From").val();
        var toDate=$("#date_To").val(); 
        var patient_number=$("#patient_number").val();
        var Sponsor_ID=$("#Sponsor_ID").val();
        var Employee_ID=$("#Employee_ID").val();
        if(fromDate.trim()!=='' && toDate.trim()!==''){
                $('#Search_Iframe').html('<div align="center" style="display:block;" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>');
                $.ajax({
                    url:'patients_oncall_report_iframe.php',
                    type:'post',
                    data:{fromDate:fromDate,toDate:toDate,patient_number:patient_number,Employee_ID:Employee_ID, Sponsor_ID:Sponsor_ID},
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
    $(document).ready(function () {
//        $("#displayPatientList").dialog({autoOpen: false, width: '90%',height:'550', title: 'PATIENTS LIST', modal: true, position: 'middle'});
        /*$('.numberTests').dataTable({
            "bJQueryUI": true
        });*/
 $('select').select2();
    });
</script>